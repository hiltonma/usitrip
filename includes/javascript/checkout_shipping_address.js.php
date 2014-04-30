<?php
$is_js_file = false;	/* 如果为false将以php的格式一行一行列到页面 */
if($base_php_self == "javascript.php"){
	$is_js_file = true;
}
if($is_js_file==false){
?>
<script type="text/javascript"><!--

<?php
}
?>

var selected;

function selectRowEffect(object, buttonSelect) {
  if (!selected) {
    if (document.getElementById) {
      selected = document.getElementById('defaultSelected');
    } else {
      selected = document.all['defaultSelected'];
    }
  }

  if (selected) selected.className = 'moduleRow';
  object.className = 'moduleRowSelected';
  selected = object;

/*  one button is not an array */
  if (document.checkout_address.address[0]) {
    document.checkout_address.address[buttonSelect].checked=true;
  } else {
    document.checkout_address.address.checked=true;
  }
}

function rowOverEffect(object) {
  if (object.className == 'moduleRow') object.className = 'moduleRowOver';
}

function rowOutEffect(object) {
  if (object.className == 'moduleRowOver') object.className = 'moduleRow';
}

function check_form_optional(form_name) {
  var form = form_name;

  var firstname = form.elements['firstname'].value;
  var lastname = form.elements['lastname'].value;
  var street_address = form.elements['street_address'].value;

  if (firstname == '' && lastname == '' && street_address == '') {
    return true;
  } else {
    return check_form(form_name);
  }
}


<?php
if($is_js_file==false){
?>
//--></script>
<?php
}
?>

<?php require(DIR_FS_JAVASCRIPT . 'form_check.js.php'); ?>
