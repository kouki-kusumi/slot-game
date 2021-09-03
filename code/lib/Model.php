<?php

class Model
{
    protected $db;

    public function __construct()
    {
        try {
            $this->db = new \PDO(\constant\DbConst::DSN, \constant\DbConst::USERNAME, \constant\DbConst::PASSWORD);
        } catch (\PDOException $e) {
            echo $e->getMessage();
            return;
        }
    }
}
