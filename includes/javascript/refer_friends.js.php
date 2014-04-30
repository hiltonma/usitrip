<?php
$p=array('/&amp;/','/&quot;/');
$r=array('&','"');
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

function register_refral_validator(theForm)
{

  if (theForm.fname.value == "")
  {
    alert("Please enter the Full Name.");
    theForm.fname.focus();
    return (false);
  }


  if (theForm.email_address.value == "")
  {
    alert("Please enter a value for the \"Email Address\" field.");
    theForm.email_address.focus();
    return (false);
  }

 
  if (emailCheck(theForm.email_address.value) == false)
  {
    theForm.email_address.focus();
    return (false);
  }

  if (theForm.refer_frd_email_1.value == "")
  {
    alert("Please enter at least 1 friend's email address on the first line.");
    theForm.refer_frd_email_1.focus();
    return (false);
  }
  
  
  if (emailCheck(theForm.refer_frd_email_1.value) == false)
  {
    theForm.refer_frd_email_1.focus();
    return (false);
  }

  return (true);
}

<?php
if($is_js_file==false){
?>
//--></script>
<?php
}
?>
