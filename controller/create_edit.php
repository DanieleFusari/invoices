<?php
require_once  __DIR__ . '/functions.php';
$account_info = auth();

$details = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

$account =          $details['account'];
$company_name =     get_company_name($account);
$company_name =     $company_name[0]['company'];
$invoice_date =     $details['invoice_date'];
$invoice_due_date = $details['invoice_due_date'];
$number_of_items =  $details['number_of_items'];

$net =              $details['net'];
$vat_money =        $details['vat_money'];
$total =            $details['total'];
$paid =             $details['paid'];
$paid_date =        $details['paid_date'];
$outstanding =      $details['outstanding'];

$detailss = json_encode($details);

if ($details['inv'] === 'null') {
  create_invoice($account, $company_name, $invoice_date, $invoice_due_date,  $number_of_items, $net, $vat_money, $total, $paid, $paid_date, $outstanding, $detailss);
  $inv = inv();
  $invoice_number = "Invoice number  " . $inv['invoice'];
  setcookie('message', $invoice_number, time() + 2, '/');
} else {
  $inv = $details['inv'];
  edit_invoice($inv, $account, $company_name, $invoice_date, $invoice_due_date,  $number_of_items, $net, $vat_money, $total, $paid, $paid_date, $outstanding, $detailss);
  $invoice_number = "Invoice " . $inv . " Has been updated";
  setcookie('message', $invoice_number, time() + 2, '/');
}

header("Location: /../new_invoice.php");
exit;
