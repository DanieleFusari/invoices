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
      </table>

      <form class="" action="controller/change_password.php" method="post">
        <table>
          <tr>
            <th><label for="new_password">Enter New Password</label> </th>
            <td><input id="new_password" type="text" name="new_password"></td>
          </tr>
        </table>
        <input  hidden type="text" name="id" value="<?=$account_info->sub ?>">

        <input type="submit" value="Change Password">
      </form>
    </div>  <!-- END account_details-->
</main>
  <?php } ?>
