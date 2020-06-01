<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/normalize.css">
    <link rel="stylesheet" href="/css/invoice.css">
    <link href="https://fonts.googleapis.com/css?family=Italianno|Trirong&display=swap" rel="stylesheet">
    <!--  font-family: 'Trirong', serif;
          font-family: 'Italianno', cursive;
    --------------------------------------------------------------------------->
    <title>Invoices</title>
  </head>
  <body>
    <header>
      <nav class="nav_options">
        <a href="https://www.mrchefcatering.co.uk/">Mr Chef</a>
        <a href="invoices">Invoices</a>
        <a href="login">Account Details</a>

        <?php
        if (isset($account_info) && $account_info->sub === '9999') {
          echo "<a href='new_invoice'>Create Invoice</a>";
        }
        if (isset($account_info) && $account_info->log) {
          echo "<a href='controller/logout'>Logout</a>";
        }
          ?>

      </nav>
    </header>
