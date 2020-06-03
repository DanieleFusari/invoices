function create_input(name, type){
  let input = document.createElement('input');
  input.setAttribute("class", 'label_input');
  input.setAttribute("type", type);
  input.setAttribute("id", name);
  input.setAttribute("name", name);
  input.setAttribute("value", ' ');
  return input;
}

for (var i = 0; i < number; i++) {
  let head = 'event_heads' + i;
  let cost_ph = 'item_cost_ph' + i;
  let headId = document.getElementById(head);
  let cost_phId = document.getElementById(cost_ph);
  headId.addEventListener('keyup', addup);
  cost_phId.addEventListener('keyup', addup);
  headId.addEventListener('keydown', addup);
  cost_phId.addEventListener('keydown', addup);
  headId.addEventListener('click', addup);
  cost_phId.addEventListener('click', addup);
}

// paid and VAT box at the top
top_paid.addEventListener('click', addup);
top_paid.addEventListener('keyup', addup);
top_paid.addEventListener('keydown', addup);
top_VAT.addEventListener('click', addup);
top_VAT.addEventListener('keyup', addup);
top_VAT.addEventListener('keydown', addup);

// number = number + 1;
// added to hidden input
number_of_items.value = number;

add_event.addEventListener('click', ()=> {
  let item_event = create_input(('item_event' + number), 'text');
  let item_date = create_input(('item_date' + number), 'date');
  let event_menu = create_input(('event_menu' + number), 'text');
  let event_heads = create_input(('event_heads' + number), 'number');
  let item_cost_ph = create_input(('item_cost_ph' + number), 'number');
  item_cost_ph.setAttribute('step','any');
  let item_cost = create_input(('item_cost' + number), 'number');
  item_cost.setAttribute('step','any');
  item_cost.setAttribute('readonly', '');
  let item_total = create_input(('item_total' + number), 'number');
  item_total.setAttribute('step','any');
  item_total.setAttribute('readonly', '');

  let tr = document.createElement('tr');
  let td = document.createElement('td');

  td = document.createElement('td');
  td.appendChild(item_event);
  tr.appendChild(td);

  td = document.createElement('td');
  td.appendChild(item_date);
  tr.appendChild(td);

  td = document.createElement('td');
  td.appendChild(event_menu);
  tr.appendChild(td);

  td = document.createElement('td');
  td.appendChild(event_heads);
  tr.appendChild(td);

  td = document.createElement('td');
  td.appendChild(item_cost_ph);
  tr.appendChild(td);

  td = document.createElement('td');
  td.appendChild(item_cost);
  tr.appendChild(td);

  td = document.createElement('td');
  td.appendChild(item_total);
  tr.appendChild(td);

  add_events.appendChild(tr);

  let head = document.getElementById('event_heads'+number);
  let cost_ph = document.getElementById('item_cost_ph'+number);
  head.addEventListener('keyup', addup);
  cost_ph.addEventListener('keyup', addup);
  head.addEventListener('keydown', addup);
  cost_ph.addEventListener('keydown', addup);
  head.addEventListener('click', addup);
  cost_ph.addEventListener('click', addup);
  number +=1;
  number_of_items.value = number;
});

// this adds up the line /row
function addup() {
    let nets = 0.00;
    let vats = 0.00;
    let grosss = 0.00;
    for (var i = 0; i < number; i++) {
      let head = document.getElementById('event_heads'+i);
      let cost_ph = document.getElementById('item_cost_ph'+i);
      let cost = document.getElementById('item_cost'+i);
      let total = document.getElementById('item_total'+i);

      let net = cost_ph.value * head.value;
      let vat = net/100 * top_VAT.value;
      let gross = vat + net;

      cost.value = net.toFixed(2);
      total.value = gross.toFixed(2);

      nets += parseFloat(net);
      vats += parseFloat(vat);
      grosss += parseFloat(gross);

      total_cost.innerHTML = nets.toFixed(2);
      total_vat.innerHTML = vats.toFixed(2);
      top_total.value = grosss.toFixed(2);
      total_total.innerHTML = (grosss - top_paid.value).toFixed(2);

      // hidden values to send.
      net_cost.value = nets.toFixed(2);
      vat_money.value = vats.toFixed(2);
      outstanding.value = (grosss - top_paid.value).toFixed(2);

      }
}


let create_account = document.querySelector('.create_account');
let account = document.querySelector('#account');
let compnay = document.querySelector('#compnay');
let first_name = document.querySelector('#first_name');
let last_name = document.querySelector('#last_name');
let supplier_no = document.querySelector('#supplier_no');
let email = document.querySelector('#supplier_no');

account.addEventListener('keyup', chckempty);
compnay.addEventListener('keyup', chckempty);
first_name.addEventListener('keyup', chckempty);
last_name.addEventListener('keyup', chckempty);
supplier_no.addEventListener('keyup', chckempty);
email.addEventListener('keyup', chckempty);


create_account.disabled = true;
create_account.style.color = 'gray';


function chckempty(){
  if (account.value.length > 2 && compnay.value.length > 2 && first_name.value.length > 2 && last_name.value.length > 2 && supplier_no.value.length > 2 && email.value.length > 2 ) {
    create_account.disabled = false;
    create_account.style.color = 'red';
    console.log(account.value.length);
  }
}













// QQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQ
// QQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQ
// QQQQQQQQQQQQ

// // this adds up the coloumn
// function add_up_totals(){
//   let costs = 0;
//   let vats = 0;
//   let totals = 0;
//   for (var i = 0; i < number; i++) {
//     const cost = document.getElementById('item_cost'+i);
//     const total = document.getElementById('item_total'+i);
//
//     costs += parseFloat(cost.value);
//     totals += parseFloat(total.value);
//
//     total_cost.innerHTML = costs.toFixed(2);
//     total_total.innerHTML = totals.toFixed(2);
//
//     top_cost.value = costs.toFixed(2);
//     top_total.value = totals.toFixed(2);
//   }
// }



// **************************************************************
// **************************************************************
// *******************  Not used any more   ********************
// **************************************************************
// **************************************************************


// function create_label(for_att, event_Name){
//   let label = document.createElement('label');
//   label.setAttribute('class', 'label_form');
//   label.setAttribute('for', for_att);
//   label.textContent = event_Name;
//   return label;
// }
