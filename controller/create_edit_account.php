<?php
require_once  __DIR__ . '/functions.php';
$account_info = auth();

$account = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

$acc = $account['account'];
$company = $account['company'];
$first_name = $account['first_name'];
$last_name  = $account['last_name'];
$supplier_no = $account['supplier_no'];
$email = $account['email'];
$mess = create_account($acc, $company, $first_name, $last_name, $supplier_no, $email);

if (!empty($mess)) {
  setcookie('message', $mess, time() + 2, '/');
} else {
  setcookie('message', 'Account Created', time() + 2, '/');
}

header("Location: /../new_invoice.php");
exit;
