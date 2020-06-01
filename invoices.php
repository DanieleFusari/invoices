<?php
require_once  __DIR__ . '/controller/functions.php';
$account_info = auth();

include('inc/header_invoice.php');

getmessage();

$account_numbers = $account_info->sub;
$invioce_or_due_date = '';
$invoice_number = '';
$date_from = '';
$date_to = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if ($account_info->sub == '9999'  || $account_info->sub == 'view') $account_numbers = filter_input(INPUT_POST, "account", FILTER_SANITIZE_STRING);
  $invoice_number = filter_input(INPUT_POST, "invoice", FILTER_SANITIZE_NUMBER_INT);
  if (!isset($_GET['t']) ) $invioce_or_due_date = $_POST['invioce_or_due_date'];
  $date_from = filter_input(INPUT_POST, "from", FILTER_SANITIZE_NUMBER_INT);
  $date_to = filter_input(INPUT_POST, "to", FILTER_SANITIZE_NUMBER_INT);
}
?>

    <main>
      <div id="reporting_details">
        <?php
        if ($account_info->sub === '9999' || $account_info->sub == 'view') {
          if (isset($_GET['t']) ) {
            echo '<a  class="switch" href="invoices"><input type="button" value="Invoices"></a>';
          } else {
            echo '<a  class="switch" href="invoices?t"><input type="button" value="Total"></a>';
          }
        }  ?>

        <form class="search_form" action=<?php if(!isset($_GET['t']) ) echo "invoices"; else echo "invoices?t"; ?> method="post">

          <?php
          if ($account_info->sub === '9999' || $account_info->sub == 'view') {
            ?>

            <label for="drop_down_account">Account Number</label>
            <select class="search_box_opt" id="drop_down_account" name="account">
              <?php
              $account = get_company_name_and_account_number();
              foreach ($account as $value){ ?>
                <option <?php if ($value['account'] == $account_numbers) echo "SELECTED"; ?> value='<?=$value['account']?>'> <?= $value['account']?> </option>;
              <?php } ?>
            </select>
          <?php }?>

          <label class="search_box_opt" for="invoiceN">Invoice Number:</label>
          <input class="search_box_opt" id="invoiceN" type="number" name="invoice" placeholder="33039" <?php if (isset($invoice_number) ) echo "value='" . $invoice_number . "';" ?>>



          <?php if (!isset($_GET['t']) ) { ?>
            <label for="drop_down_due_inv">Issued or Due</label>
            <select class="search_box_opt" name="invioce_or_due_date">
              <option <?php if ($invioce_or_due_date == 'invoice_date') echo "SELECTED"; ?> value="invoice_date">Invoice Date</option>
              <option <?php if ($invioce_or_due_date == 'due_date') echo "SELECTED"; ?> value="due_date">Due Date</option>
            </select>
        <?php } ?>
          <label class="search_box_opt" for="date_from">Date from:</label>
          <input class="search_box_opt" id="date_from" type="date" name="from" value="<?php if ( $date_from == '' ) { echo date('Y-m-01'); } else { echo  $date_from; } ?>" >
          <label class="search_box_opt" for="date_to">Date to:</label>
          <input class="search_box_opt" id="date_to" type="date" name="to" value="<?php if ( $date_from == '' ) { echo date('Y-m-t'); } else { echo  $date_to; } ?>" >
          <input class="search_box_opt" type="submit" value="Search...">
        </form>
      </div>

      <div id="reporting_report">
        <div class="outstanding">
          <h1><?php if (!isset($_GET['t']) )echo 'Outstanding'; else echo 'Totals'; ?></h1>
          <table>
            <?php if (!isset($_GET['t']) ) {
              report_grid(reports($account_numbers, $invoice_number, ">", $invioce_or_due_date, $date_from, $date_to));
            } else {
              grid(total_sum($account_numbers, $date_from, $date_to));
            }?>
          </table>
        </div>    <!-- finsihed outstanding -->

        <?php if (!isset($_GET['t']) ) { ?>
        <div class="paid">
          <h1>Paid</h1>
          <table>
            <?php if (!isset($_GET['t']) ) {
              if($_SERVER['REQUEST_METHOD'] === 'POST') report_grid(reports($account_numbers, $invoice_number, "<=", $invioce_or_due_date, $date_from, $date_to));
            } ?>
          </table>
        </div>   <!-- finsihed paid -->
      </div> <!-- finsihed reporting report-->
<?php } ?>

    </main>
        <script type="text/javascript" src="js/script.js"></script>
  </body>
</html>
