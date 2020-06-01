<?php
require_once  __DIR__ . '/controller/functions.php';
$account_info = decode();
include('inc/header_invoice.php');
getmessage();
?>


<main>
  <?php if (!$account_info) { ?>
    <div id="login">
      <form class="login_form" action="controller/login.php" method="post">
        <label for="account_login">Account ID</label>
        <input id="account_login" type="text"  placeholder=" Example abcd1234" name="account_login">

        <label for="password_login">Password</label>
        <input id="password_login" type="password" placeholder="Password" name="password_login">

        <input  class="btn" type="submit" value="Login">
      </form>
    </div>  <!-- END reporting_details-->
</main>
  <?php } else {
    // $customer_info = company_address($account_info->sub);
    // $customer_info = $customer_info[0];
     ?>
<main>
    <div id="account_details">
      <h1>Account Details</h1>
      <table>
        <tr>
          <th>Account Number</th>
          <td><?= $account_info->sub ?></td>
        </tr>
        <!-- <tr>
          <th>Company Name</th>
          <td><?= $customer_info['company'] ?></td>
        </tr>
        <tr>
          <th>Account Holder</th>
          <td><?= $customer_info['last_name'] ?></td>
        </tr>
        <tr>
          <th>Addess</th>
          <td><?= $customer_info['first_line_address'] ?></td>
        </tr>
        <tr>
          <th>Area</th>
          <td><?= $customer_info['second_line_address'] ?></td>
        </tr>
        <tr>
          <th>PostCode</th>
          <td><?= $customer_info['post_code'] ?></td>
        </tr>
        <tr>
          <th>E-mail</th>
          <td><?= $customer_info['email'] ?></td>
        </tr>
        <tr>
          <th>Contact Number</th>
          <td><?= $customer_info['phone'] ?></td>
        </tr> -->
      </table>
    </div>  <!-- END account_details-->
</main>
  <?php } ?>
