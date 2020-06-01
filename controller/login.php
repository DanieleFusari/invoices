<?php
require_once  __DIR__ . '/functions.php';

$id = filter_input(INPUT_POST, 'account_login', FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'password_login', FILTER_SANITIZE_STRING);
login($id, $password);
