<?php
require_once('database.class.php');

$config = [
    'host' => 'localhost',
    'user' => 'root',
    'password' => '',
    'name' => 'tin_tuc'
];

$domain = 'https://php_basic.test';

$db = new database($config);
