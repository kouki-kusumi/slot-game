<?php

namespace Controller;

class Login extends \Controller
{
    public function run()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->loginProcess();
        }
    }

    private function loginProcess()
    {
        try {
            $this->validate();
        } catch (\Exception $e) {
            $this->setErrors('login', $e->getMessage());
        }

        if ($this->hasError()) {
            return;
        } else {
            $userModel = new \Model\User();
            $user = $userModel->find($_POST['name']);
            $_SESSION['loginUser'] = $user;
            header('Location: ' . getSiteUrl());
        }
    }

    private function validate()
    {
        // 値が入力されていない
        if (empty($_POST['name']) || empty($_POST['password'])) {
            throw new \Exception('値を入力してください');
        }
        // 無効なトークン
        if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
            throw new \Exception('無効なトークンです');
        }

        $userModel = new \Model\User();
        $user = $userModel->findAll($_POST['name']);
        // 存在しないユーザー名
        if (empty($user)) {
            throw new \Exception('ユーザーが存在しません');
        }
        // パスワードが違う
        if (!password_verify($_POST['password'], $user['password'])) {
            throw new \Exception('ユーザー名かパスワードが間違っています');
        }
    }
}
