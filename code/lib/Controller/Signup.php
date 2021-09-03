<?php

namespace Controller;

class Signup extends \Controller
{
    public function run()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->signupProcess();
        }
    }

    private function signupProcess()
    {
        // 検証
        try {
            $this->validate();
        } catch (\Exception $e) {
            $this->setErrors('signup', $e->getMessage());
        }

        if ($this->hasError()) {
            return;
        } else {
            try {
                // 登録処理
                $userModel = new \Model\User();
                $userModel->create([
                    'name' => $_POST['name'],
                    'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                    'coin' => 100,
                ]);
            } catch (\PDOException $e) {
                $this->setErrors('signup', $e->getMessage());
                return;
            }
            try {
                $userModel = new \Model\User();
                $user = $userModel->find($_POST['name']);
            } catch (\PDOException $e) {
                $this->setErrors('signup', $e->getMessage());
            }
            $_SESSION['loginUser'] = $user;
            header('Location: ' . getSiteUrl());
        }
    }

    private function validate()
    {
        // 値が入力されていない
        if (empty($_POST['name']) || empty($_POST['password'])) {
            throw new Exception('値を入力してください');
        }
        // 無効なトークン
        if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
            throw new \Exception('無効なトークン');
        }
        // パスワードが一致しない
        if ($_POST['password'] !== $_POST['confirm-password']) {
            throw new \Exception('パスワードが一致しません');
        }
        // パスワードがMINMAXの範囲外
        if (\Constant\PasswordConst::MIN > mb_strlen($_POST['password']) || mb_strlen($_POST['password']) > \Constant\PasswordConst::MAX) {
            throw new \Exception('パスワードは' . \Constant\PasswordConst::MIN . '文字以上' . \Constant\PasswordConst::MAX . '文字以上の値を入力してください');
        }
        // nameの文字数制限
        if (\Constant\NameConst::MIN > mb_strlen($_POST['name']) || mb_strlen($_POST['name']) > \Constant\NameConst::MAX) {
            throw new \Exception('名前は' . \Constant\NameConst::MAX . '文字以下の値を入力してください');
        }
        // すでに同じ名前が登録されている
        $db = new \Model\User();
        if (!empty($db->find($_POST['name']))) {
            throw new \Exception($_POST['name'] . 'はすでに登録されています');
        }
    }
}
