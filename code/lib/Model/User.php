<?php

namespace Model;

use PDOException;

class User extends \Model
{

    public function create($valuse)
    {
        $stmt = $this->db->prepare('insert into users (name, password, coin, created, modified) values(:name, :password, :coin, now(), now())');
        $response = $stmt->execute([
            ':name' => $valuse['name'],
            ':password' => $valuse['password'],
            ':coin' => $valuse['coin']
        ]);
        if ($response === false) {
            throw new \PDOException();
        }
    }

    public function find($name)
    {
        $stmt = $this->db->prepare('select name, coin from users where name = :name;');
        $stmt->bindParam(':name', $name, \PDO::PARAM_STR);
        $response = $stmt->execute();

        if ($response === false) {
            throw new PDOException();
        } else {
            $user = $stmt->fetch();
            return $user;
        }
    }

    public function findAll($name)
    {
        $stmt = $this->db->prepare('select * from users where name = :name;');
        $stmt->bindParam(':name', $name, \PDO::PARAM_STR);
        $response = $stmt->execute();

        if ($response === false) {
            throw new PDOException();
        } else {
            $user = $stmt->fetch();
            return $user;
        }
    }

    public function coinUpdate($coin, $name)
    {
        $stmt = $this->db->prepare('update users set coin = :coin where name = :name;');
        $stmt->execute([
            ':coin' => $coin,
            ':name' => $name
        ]);
        if ($response === false) {
            throw new \PDOException();
        }
    }
}
