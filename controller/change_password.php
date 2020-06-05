<?php

require_once  'functions.php';
$new_password = filter_input(INPUT_POST, 'new_password', FILTER_SANITIZE_STRING);
$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);
$new_password = password_hash($new_password, PASSWORD_DEFAULT);

change_password($id, $new_password);

setcookie('message', "Password Changed", time() + 1, '/');

header('location: /login');
