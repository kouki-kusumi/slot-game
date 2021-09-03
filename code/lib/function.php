<?php

spl_autoload_register(function ($class) {
    $classFilePath = __DIR__ . '/../lib/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($classFilePath)) {
        require $classFilePath;
    }
});

function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function getSiteUrl()
{
    return 'http://' . $_SERVER['HTTP_HOST'];
}
