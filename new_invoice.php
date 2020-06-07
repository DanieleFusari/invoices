<?php
require_once  __DIR__ . '/controller/functions.php';

$account_info = auth();

include('inc/header_invoice.php');

getmessage();

if($_SERVER['REQUEST_METHOD'] === 'POST') {
  $edit_details = pdf_details($_POST['inv']);
  $all_edit_details = json_decode($edit_details['details']);
}
?>
    <main>
      <section id="create_edit_invoice">
        <form class="get_invoice" action="new_invoice.php" method="post">
          <fieldset>
            <legend>Edit Invoice:</legend>

            <label for="get_inv">Invoice Number</label>
            <input id="get_inv" type="number" name="inv" value=<?php if(isset($edit_details)) echo $edit_details['invoice'];?>>

            <input type="submit" value="EDIT">
            <a class="create_new_account_link" href="#create_edit_account">Create a new Account</a>
          </fieldset>
        </form>
    <hr>

        <form class="create_invoice" action="controller/create_edit.php" method="post">
          <fieldset id="account_invoice_details">
            <legend>Account Invoice Details </legend>
      <!-- inv -->
            <table>
              <thead class="top_labels">
                <tr>
                  <th><label for="inv">Invoice Number</label></th>
                  <th><label for="drop_down_account">Mr Chef ID:</label></th>
                  <th><label for="inv_date">Invoice Date</label></th>
                  <th><label for="inv_due_date">Due Date</label></th>

                  <th><label for="top_VAT">VAT %</label></th>
                  <th><label for="top_total">Total</label></th>
                  <th><label for="top_paid">Paid</label></th>
                  <th><label for="paid_date">Date Paid</label></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><input readonly id="inv" type="text" name="inv" value=<?php if(isset($edit_details)) echo $edit_details['invoice']; else echo 'null';  ?>></td>
                  <td>
                    <select id="drop_down_account" name="account">
                      <?php
                      $account = get_company_name_and_account_number();

                      foreach ($account as $value){ ?>
                        <option <?php if(isset($edit_details) && $edit_details['account'] == $value['account']) echo 'selected' ?> value='<?=$value['account'];?>'>    <?= $value['account'] . ' - (' . $value['company'] .')'?> </option>;
                      <?php
                      } ?>
                    </select>
                  </td>
                  <td><input type="date" name="invoice_date" id="inv_date"         value=<?php if(isset($edit_details)) echo $edit_details['invoice_date']; else echo date('Y-m-d'); ?>></td>
                  <td><input type="date" name="invoice_due_date" id="inv_due_date" value=<?php if(isset($edit_details)) echo $edit_details['due_date'];     else echo date('Y-m-d', strtotime(date('Y-m-d') . ' + 14 days')); ?>></td>

                  <td><input type="number" step="any" id="top_VAT"   name="VAT"    value=<?php if(isset($edit_details)) echo $all_edit_details->VAT;        else echo '20.0'; ?>></td>
                  <td><input type="number" step="any" id="top_total" name="total"  value="<?php if(isset($edit_details)) echo $edit_details['gross'];?>" readonly>    </td>
                  <td><input type="number" step="any" id="top_paid"  name="paid"   value="<?php if(isset($edit_details)) echo $edit_details['paid'];?>">              </td>
                  <td><input type="date"         id="paid_date" name="paid_date"   value="<?php if(isset($edit_details)) echo $edit_details['paid_date'];?>">         </td>
                </tr>
              </tbody>
            </table>
          </fieldset>



          <fieldset id="invoice_order">
            <legend>Invoice Order</legend>
      <!-- Details This will be an array.-->
            <table>
              <thead>
                <tr>
                  <th><label class="label_form">Notes</label></th>
                  <th><label class="label_form">Event Date</label></th>
                  <th><label class="label_form">Event Menu</label></th>
                  <th><label class="label_form">Event Heads</label></th>
                  <th><label class="label_form">Cost Per Head</label></th>
                  <th><label class="label_form">Cost</label></th>
                  <th><label class="label_form">Total</label></th>
                </tr>
              </thead>
              <tbody id="add_events">
                <?php
                if($_SERVER['REQUEST_METHOD'] === 'POST') {
                  for ($i=0; $i < $all_edit_details->number_of_items; $i++) {
                    $item_event = 'item_event' . $i;
                    $item_date = 'item_date' . $i;
                    $event_menu = 'event_menu' . $i;
                    $event_heads = 'event_heads' . $i;
                    $item_cost_ph = 'item_cost_ph' . $i;
                    $item_cost = 'item_cost' . $i;
                    $item_total = 'item_total' . $i;
                    ?>
                    <tr>
                      <td><input class="label_input" type="text"   name=<?= $item_event; ?>   value="<?= $all_edit_details->$item_event ; ?>"               id="<?= $item_event;   ?>"></td>
                      <td><input class="label_input" type="date"   name=<?= $item_date; ?>    value="<?= $all_edit_details->$item_date ; ?>"                id="<?= $item_date;    ?>"></td>
                      <td><input class="label_input" type="text"   name=<?= $event_menu; ?>   value="<?= $all_edit_details->$event_menu ; ?>"               id="<?= $event_menu;   ?>"></td>
                      <td><input class="label_input" type="number" name=<?= $event_heads; ?>  value="<?= $all_edit_details->$event_heads ; ?>"              id="<?= $event_heads;  ?>"></td>
                      <td><input class="label_input" type="number" name=<?= $item_cost_ph; ?> value="<?= $all_edit_details->$item_cost_ph ; ?>"  step="any" id="<?= $item_cost_ph; ?>"></td>
                      <td><input class="label_input" type="number" name=<?= $item_cost; ?>    value="<?= $all_edit_details->$item_cost ; ?>"     step="any" id="<?= $item_cost;    ?>" readonly></td>
                      <td><input class="label_input" type="number" name=<?= $item_total; ?>   value="<?= $all_edit_details->$item_total ; ?>"    step="any" id="<?= $item_total;   ?>" readonly></td>
                    </tr>
            <?php } ?>
                  <script type="text/javascript">
                    let number = <?= $i ?>
                  </script>
            <?php } else { ?>
                  <tr>
                    <td><input class="label_input" type="text"   name="item_event0"   value="">                                          </td>
                    <td><input class="label_input" type="date"   name="item_date0"    value="">                                          </td>
                    <td><input class="label_input" type="text"   name="event_menu0"   value="">                                          </td>
                    <td><input class="label_input" type="number" name="event_heads0"  value=""                id="event_heads0" >        </td>
                    <td><input class="label_input" type="number" name="item_cost_ph0" value=""     step="any" id="item_cost_ph0">        </td>
                    <td><input class="label_input" type="number" name="item_cost0"    value=""     step="any" id="item_cost0"   readonly></td>
                    <td><input class="label_input" type="number" name="item_total0"   value=""     step="any" id="item_total0"  readonly></td>
                  </tr>
              <?php } ?>
            </tbody>
            </table>
            <button id="add_event" type="button">Add Event</button>
            <input type="submit" value="<?php if(isset($edit_details)) echo 'Save & Update'; else echo 'SAVE'; ?>">
          </fieldset>
          <input     id="number_of_items"              name="number_of_items"            hidden>
          <input     id="net_cost"                     name="net"                        hidden>
          <input     id="vat_money"                    name="vat_money"                  hidden>
          <input     id="outstanding"                  name="outstanding"                hidden>
        </form>

        <div class="totals_at_bottom">
          <p class="totals_bottom totals_left">Cost £</p> <p class="totals_bottom totals_right" id="total_cost"> 0.00</p>
          <p class="totals_bottom totals_left">VAT £</p>  <p class="totals_bottom totals_right" id="total_vat">  0.00</p>
          <p class="totals_bottom totals_left">LEFT TO PAY £</p><p class="totals_bottom totals_right" id="total_total">0.00</p>
        </div>

      </section>

      <section id="create_edit_account">
        <h1 class="headh1">CREATE A NEW ACCOUNT</h1>
        <fieldset id="account_invoice_details">
          <legend>Create an Account</legend>
        <form class="" action="controller/create_edit_account.php" method="post">
          <table>
            <thead>
              <tr>
                <th><label for="account">Mr Chef ID:</label> </th>
                <th><label for="compnay">Company</label></th>
                <th><label for="first_name">First Name</label></th>
                <th><label for="last_name">Last Name</label></th>
                <th><label for="supplier_no">Supplier Number</label></th>
                <th><label for="email">E-mail</label></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><input id="account" type="text" name="account" placeholder="abcd1234"></td>
                <td><input id="compnay" type="text" name="company" placeholder="Full Compnay Name"></td>
                <td><input id="first_name" type="text" name="first_name" placeholder="First name"></td>
                <td><input id="last_name" type="text" name="last_name" placeholder="Last Name"></td>
                <td><input id="supplier_no" type="number" name="supplier_no" placeholder="Supplier Number"></td>
                <td><input id="email" type="email" name="email" placeholder="E-mail"></td>
              </tr>
            </tbody>
          </table>
          <input class="create_account" type="submit" value="Create Account">
        </form>
      </fieldset>
      </section>

    </main>
    <?php  if(!isset($edit_details)) { ?>
    <script type="text/javascript">
      let number = 1;
    </script>
    <?php } ?>
      <script type="text/javascript" src="js/new_invoice.js"></script>
  </body>
</html>
