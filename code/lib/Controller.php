<?php

class Controller
{
    protected $_values;
    protected $_errors;

    public function __construct()
    {
        if (!isset($_SESSION['token'])) {
            $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(24));
        }

        $this->_values = new \stdClass();
        $this->_errors = new \stdClass();
    }

    // setter getter
    public function setValues($key, $value)
    {
        $this->_values->$key = $value;
    }
    public function getValues()
    {
        return $this->_values;
    }
    public function setErrors($key, $value)
    {
        $this->_errors->$key = $value;
    }
    public function getError($key)
    {
        return isset($this->_errors->$key) ? $this->_errors->$key : '';
    }

    public function hasError()
    {
        return !empty(get_object_vars($this->_errors));
    }
}
