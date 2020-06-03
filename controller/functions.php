<?php

require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// ************** Search DATA BASE REPORTING *************************
// ************** Search DATA BASE REPORTING *************************
// ************** Search DATA BASE REPORTING *************************

$host = getenv("host");
$dbname = getenv("dbname");
$username = getenv("username");
$password = getenv("password");

try {
  $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (\Exception $e) {
  echo $e->getMessage();
}

function reports($acc, $inv, $pm, $invioce_or_due_date, $from, $to){
  global $db;
  if ($acc == '9999' && $inv == '' && $from == '' && $to == '') {
    $invoices = $db->query("SELECT invoice, account, company,invoice_date, due_date, items, net,vat, gross, paid, outstanding FROM invoices WHERE outstanding $pm 0 ");
    // echo 'report1';
  } elseif ($inv == '' && $from == '' && $to == ''){
    $invoices = $db->query("SELECT invoice, account, company,invoice_date, due_date, items, net,vat, gross, paid, outstanding FROM invoices WHERE outstanding $pm 0 and account = '$acc'");
    // echo 'report2';
  } elseif ($inv == '' && $acc == '9999') {
    $invoices = $db->query("SELECT invoice, account, company,invoice_date, due_date, items, net,vat, gross, paid, outstanding from invoices WHERE outstanding $pm 0 and $invioce_or_due_date BETWEEN '$from' AND '$to' ");
    // echo 'report3';
  } elseif ($acc == '9999' && $from == '' && $to == '') {
    $invoices = $db->query("SELECT invoice, account, company,invoice_date, due_date, items, net,vat, gross, paid, outstanding from invoices WHERE outstanding $pm 0 and invoice = $inv ");
    // echo 'report3a';
  } elseif ($inv == '') {
    $invoices = $db->query("SELECT invoice, account, company,invoice_date, due_date, items, net,vat, gross, paid, outstanding from invoices WHERE account = '$acc' and outstanding $pm 0 and $invioce_or_due_date BETWEEN '$from' AND '$to' ");
    // echo 'report4';
  } elseif($acc == '9999') {
    $invoices = $db->query("SELECT invoice, account, company,invoice_date, due_date, items, net,vat, gross, paid, outstanding from invoices WHERE invoice = $inv and outstanding $pm 0 and $invioce_or_due_date BETWEEN '$from' AND '$to' ");
    // echo 'report5';
  } else {
    $invoices = $db->query("SELECT invoice, account, company,invoice_date, due_date, items, net,vat, gross, paid, outstanding from invoices WHERE account = '$acc' and invoice = $inv and outstanding $pm 0 and $invioce_or_due_date BETWEEN '$from' AND '$to' ");
    // echo 'report6 ';
  }
  $invoices = $invoices->fetchAll(PDO::FETCH_ASSOC);
  if(!empty($invoices)) return covert_to_int($invoices);
}

function total_sum($acc, $from, $to){
  global $db;
  if ($acc == '9999') {
    $total_sum = $db->query("SELECT account, company, sum(items) AS Items, sum(net) AS Cost, sum(vat) AS 'V.A.T', sum(gross) AS Total, sum(outstanding) AS Outstanding  FROM invoices WHERE invoice_date BETWEEN '$from' AND '$to' GROUP BY account ORDER BY Outstanding DESC");
  } else {
    $total_sum = $db->query("SELECT account, company, sum(items) AS Items, sum(net) AS Cost, sum(vat) AS 'V.A.T', sum(gross) AS Total, sum(outstanding) AS Outstanding  FROM invoices WHERE account = '$acc' AND invoice_date BETWEEN '$from' AND '$to' GROUP BY account ORDER BY Outstanding DESC");
  }
  $total_sum = $total_sum->fetchAll(PDO::FETCH_ASSOC);
  if(!empty($total_sum)) return covert_to_int($total_sum);
}

function get_company_name_and_account_number(){
  global $db;
  $account = $db->query('SELECT account, company FROM accounts');
  $account = $account->fetchAll(PDO::FETCH_ASSOC);
  return $account;
}

function company_address($account){
  global $db;
  $company_address = $db->query("SELECT * FROM accounts WHERE account = '$account'");
  $company_address = $company_address->fetchAll(PDO::FETCH_ASSOC);
  return $company_address;
}

function pdf_details($id){
  global $db;
  $invoice_pdf = $db->prepare('SELECT * FROM invoices WHERE invoice = :inv');
  $invoice_pdf->bindParam(':inv', $id);
  $invoice_pdf->execute();
  $invoice_pdf = $invoice_pdf->fetch(PDO::FETCH_ASSOC);
  return $invoice_pdf;
}


// This function take the array from SQL turn the floats from string back into floats.
// As Sql send everything back as strings.
// ALSO CONVERTS THE DATE TO UK DATE DD/MM//YY*************
function covert_to_int($invoices) {
  foreach ($invoices as $value) {
    foreach ($value as $key => $value) {
      if ($key == 'Outstanding' || $key == 'Cost' || $key == 'Total' || $key == 'V.A.T' || $key == 'net' || $key == 'gross' || $key == 'paid' || $key == 'outstanding' || $key == 'vat') {
        $fff = number_format(floatval($value), 2);
        $new1[$key] = '£'. $fff;
      } elseif ($key == 'invoice_date' || $key == 'due_date') {
        $new1[$key] = date("d-m-y", strtotime($value));
      }
      else {
        $new1[$key] = $value;
      }
    }
    $new[] = $new1;
  }
  return $new;
}

// ****************************************************************
// **************** Save and Edit invoice / Accounts ************************
// ****************************************************************

function get_company_name($acc){
  global $db;
  $account = $db->prepare("SELECT account, company FROM accounts WHERE account = :acc");
  $account->bindParam(':acc', $acc);
  $account->execute();
  $account = $account->fetchAll(PDO::FETCH_ASSOC);
  return $account;
}

function create_invoice($account, $company_name, $invoice_date, $invoice_due_date,  $number_of_items, $net, $vat_money, $total, $paid, $paid_date, $outstanding, $details){
  global $db;
  $db->exec("INSERT INTO
    invoices VALUES(null, '$account', '$company_name', '$invoice_date', '$invoice_due_date', '$number_of_items', '$net', '$vat_money', '$total', '$paid', '$paid_date', '$outstanding', '$details')" );
}

function inv(){
  global $db;
  $inv = $db->query('SELECT invoice FROM invoices ORDER BY invoice DESC LIMIT 1');
  $inv = $inv->fetch();
  return $inv;
}

function edit_invoice($inv, $account, $company_name, $invoice_date, $invoice_due_date,  $number_of_items, $net, $vat_money, $total, $paid, $paid_date, $outstanding, $details){
  global $db;
  $db->exec("UPDATE invoices SET account = '$account', company = '$company_name', invoice_date = '$invoice_date', due_date = '$invoice_due_date', items = '$number_of_items', net = '$net', vat = '$vat_money', gross = '$total', paid = '$paid', paid_date = '$paid_date', outstanding = '$outstanding',
            details = '$details'  WHERE invoice = '$inv' ");
}

function create_account($account, $company, $first_name, $last_name, $supplier_no, $email) {
  global $db;
  try {
    $db->exec("INSERT INTO accounts VALUES('$account', '$company', '$first_name', '$last_name', '$supplier_no', '$email')");
  } catch (\Exception $e) {
    return $e->getMessage();
  }
}

// ************** ALL Login  AND SECURITY *************************
// ************** ALL Login  AND SECURITY *************************
// ************** ALL Login  AND SECURITY *************************

function login($id, $password) {
    global $db;
    $user = $db->prepare('SELECT account, password FROM login WHERE account = :account');
    $user->bindParam(':account', $id);
    $user->execute();
    $user = $user->fetch(PDO::FETCH_ASSOC);
    if(!empty($user) && password_verify($password, $user['password']) ) {
      $jwt = Firebase\JWT\JWT::encode(
        [
          'iss' => $_SERVER['SERVER_NAME'],
          'sub' => $user['account'],
          'exp' => time() + 3600,
          'iat' => time(),
          'nbf' => time(),
          'log' => true
        ],
        getenv("SECRET_KEY"),
        'HS256'
      );
      setcookie('logged_in', $jwt, time() + 3600, '/');
      setcookie('message', "Logged IN", time() + 1, '/');
      header('Location: /../invoices');
      exit;
    } else {
      setcookie('failed', 'Please check your Password or account number', time() + 1, '/');
      header("Location: /../login");
      exit;
    }
}

function decode(){
  if (isset($_COOKIE['logged_in'])){
    try {
      Firebase\JWT\JWT::$leeway = 60;
      $jwt = Firebase\JWT\JWT::decode(
        $_COOKIE['logged_in'],
        getenv("SECRET_KEY"),
        ['HS256']
      );
      return $jwt;
    } catch (Exception $e) {
      return null;
    }
  }
  return null;
}

function auth(){
  $account_info = decode();
  if (!$account_info->log) {
    setcookie('message', 'Login or contact MR Chef', time() + 1, '/');
    setcookie('logged_in', null, -1, '/');
    header('Location: /../login.php');
    exit;
  } else {
    return $account_info;
  }
}

// **************************************************************
// ********************* Invoice LAYOUT *************************
// **************************************************************

function grid($reporting){
  if (!empty($invoices = $reporting)) {
    echo '<thead>';
    echo '<tr>';
    // header in graph ***
    foreach ($reporting[0] as $key => $value) {
      echo "<th>" . ucfirst($key) . "</th>";
    }
    echo '</tr>';
    echo '</thead>';
    // values in graph ***
    echo '<tbody>';
    foreach ($invoices as $value) {
      echo "<tr class='table_row'>";
      foreach ($value as $val) {
        echo"<td>$val</td>";
      }
    echo "</tr>";
    }
    echo '</tbody>';
  } else {
      echo '<h2 class="message_paid">No Invoices Found Please check and try again.</h2>';
  }
}

function report_grid($reporting){
  if (!empty($invoices = $reporting)) {
    echo '<thead>';
    echo '<tr>';
    // header in graph ***
    foreach ($invoices[0] as $key => $value) {
      echo "<th>" . ucfirst($key) . "</th>";
    }
    echo '<th>Downlaod</th>';
    echo '</tr>';
    echo '</thead>';
    // values in graph ***
    echo '<tbody>';
    foreach ($invoices as $value) {
      echo "<tr class='table_row'>";
      foreach ($value as $val) {
        echo"<td>$val</td>";
      }
    echo "<td><a href='controller/make_pdf.php?id=" . $value['invoice'] . "'>PDF</a></td>";
    echo "</tr>";
    }
    echo '</tbody>';
  } else {
      echo '<h2 class="message_paid">No Invoices Found Please check and try again.</h2>';
  }
}

// **************************************************************
// ********************* Messages flash *************************
// **************************************************************

function getmessage(){
  if (isset($_COOKIE['message']) ) {
    echo "<h1 class='flash'>" .  urldecode($_COOKIE['message']) .  '</h1>';
  }
  if (isset($_COOKIE['failed'])) {
    echo "<h1 class='flash'>" .  urldecode($_COOKIE['failed']) .  '</h1>';
  }
}
