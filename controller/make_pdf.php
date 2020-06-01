<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('html_errors', 1);

require_once  __DIR__ . '/functions.php';
// auth();
$account_info = auth();

$pdf_inv_number = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

$all_pdf_details = pdf_details($pdf_inv_number);

if ($account_info->sub !== '9999') {
  if($all_pdf_details['account']  !== $account_info->sub) {
    header('Location: /../invoices.php');
  }
}

$pdf_details = json_decode($all_pdf_details['details']);

$company_address  =  company_address($all_pdf_details['account']);


$mpdf = new \Mpdf\Mpdf();

$stylesheet = file_get_contents('../css/make_pdf.css');
$mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);


$html = '
<div class="inv_head">
  <img class="logo" src="../img/logos/Black_Red.jpg">
  <h3 class="h12_header">The Professionall Caterers For all Occasions</h3>
  <h4 class="h12_header">We cater for all types of functions Confereces Management Meetings Training Courses</h4>
  <h6 class="h12_header">Mr Stuart Hope 57, Newey Road Wyken, Coventry CV2 5GZ</h6>
  <table class="table">
    <tr> <td> VAT Register No: 336393389 </td> <td> Email: mrchefcatering@hotmail.com </td> <td> TEL: 02476 453364 </td></tr>
  </table>
</div>';

$html .= '<div class="inv_acc">
  <div class="date_acc_inv">
    Invoice Date: '    . date('dS F Y', strtotime($all_pdf_details["invoice_date"]))  . '<br>
    Invoice Number: '  . $all_pdf_details["invoice"]      . '<br>
    Mr Chef ID: '      . $all_pdf_details["account"]      . '<br>
  </div>
  <div class="inv_address">'
    . 'Account Holder: '  . $company_address[0]["first_name"]  . ' ' . $company_address[0]["last_name"] . '<br>'
    . 'Supplier Number: ' . $company_address[0]["supplier_no"]    . '<br>' .'
  </div>
</div>';
$html .= '<div class="company">' .$all_pdf_details["company"] . '</div>';

$html .= '
<table class="table">
  <tr> <th class="left"></th> <th></th> <th></th> <th></th> <th></th> <th>Cost</th> <th> VAT </th> <th> Total </th> </tr>';
  for ($i=0; $i < $pdf_details->number_of_items; $i++) {
    $item_event = 'item_event' . $i;
    $item_date = 'item_date' . $i;
    $event_menu = 'event_menu' . $i;
    $event_heads = 'event_heads' . $i;
    $item_cost_ph = 'item_cost_ph' . $i;
    $item_cost = 'item_cost' . $i;
    $item_total = 'item_total' . $i;
    $html .= '<tr>
        <td class="left">' . $pdf_details->$item_event .  '</td>
        <td>' . date('d M', strtotime($pdf_details->$item_date)) .'</td>
        <td>' . $pdf_details->$event_menu .'</td>
        <td>' .'@' . $pdf_details->$event_heads . '</td>
        <td>' .'£ ' . $pdf_details->$item_cost_ph . '</td>
        <td>' .'£ ' . $pdf_details->$item_cost . '</td>
        <td>' .'£ ' . round($pdf_details->$item_cost /100 * 20, 2) . '</td>
        <td>' .'£ ' . $pdf_details->$item_total . '</td>
      </tr>';
    }
$html .='</table>';

$html .= '
<table class="table_total">
<tr>
  <th class="left">Cost</th>
  <td> £ ' . $all_pdf_details['net'] .  '</td>
</tr>
<tr>
  <th class="left">VAT</th>
  <td> £ ' . $all_pdf_details['vat'] . '</td>
</tr>
<tr>
  <th class="left">Total</th>
  <td> £ ' . $all_pdf_details['gross'] . '</td>
</tr>
</table>';
$html .='<p class="due"> Payment is due with in 14 days, Due Date: <strong> ' . date('dS F Y', strtotime($all_pdf_details["due_date"])) . '</strong>';

$html .= '
<table class="bank_details">
  <tr>
    <th>Bank Name</th>
    <th>Bank Address</th>
    <th>Account Name</th>
    <th>Account Number</th>
    <th>Sort Code</th>
    <th>SWIFT</th>
    <th>IBAN</th>
  </tr>
  <tr>
    <td>HSBC </td>
    <td>The Parade  Leamington Spa <br>CV32 4AG</td>
    <td>Mr S  Hope & Mr G Turnbull</td>
    <td>51552287 </td>
    <td>40-18-38</td>
    <td>HBUKGB4127G</td>
    <td>GB81HBUK40183851552287</td>
  </tr>
</table>';

$mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);

// Set nama of file and set to  download 'D';
$name = "MR Chef Invoice number " . $pdf_inv_number. ".pdf";
$mpdf->Output($name, 'I');
