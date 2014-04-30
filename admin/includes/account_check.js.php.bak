<?php
/*
$Id: account_check.js.php,v 1.1.1.1 2004/03/04 23:39:39 ccwjr Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2003 osCommerce

Released under the GNU General Public License
*/
?>

<?php
if (substr(basename($PHP_SELF), 0, 12) == 'admin_member') {
?>

<script language="JavaScript" type="text/JavaScript">
<!--
function validateForm() {
	var p,z,xEmail,errors='',dbEmail,result=0,i;

	var adminName1 = document.newmember.admin_firstname.value;
	var adminName2 = document.newmember.admin_lastname.value;
	var adminEmail = document.newmember.admin_email_address.value;

	if (adminName1 == '') {
		errors+='<?php echo JS_ALERT_FIRSTNAME; ?>';
	} else if (adminName1.length < <?php echo ENTRY_FIRST_NAME_MIN_LENGTH; ?>) {
		errors+='- Firstname length must over  <?php echo (ENTRY_FIRST_NAME_MIN_LENGTH); ?>\n';
	}

	if (adminName2 == '') {
		errors+='<?php echo JS_ALERT_LASTNAME; ?>';
	} else if (adminName2.length < <?php echo ENTRY_FIRST_NAME_MIN_LENGTH; ?>) {
		errors+='- Lastname length must over  <?php echo (ENTRY_LAST_NAME_MIN_LENGTH);  ?>\n';
	}

	if (adminEmail == '') {
		errors+='<?php echo JS_ALERT_EMAIL; ?>';
	} else if (adminEmail.indexOf("@") <= 1 || adminEmail.indexOf("@") >= (adminEmail.length - 3) || adminEmail.indexOf("@.") >= 0 ) {
		errors+='<?php echo JS_ALERT_EMAIL_FORMAT; ?>';
	} else if (adminEmail.length < <?php echo ENTRY_EMAIL_ADDRESS_MIN_LENGTH; ?>) {
		errors+='<?php echo JS_ALERT_EMAIL_FORMAT; ?>';
	}

	if (errors) alert('The following error(s) occurred:\n'+errors);
	document.returnValue = (errors == '');
}


function checkGroups(obj) {
	var objIdNum = parseFloat((obj.id).substring(7));
	if(obj.checked == true){
		jQuery('input[class="subgroups_'+objIdNum+'"]').attr("checked", true);
	}else{
		jQuery('input[class="subgroups_'+objIdNum+'"]').attr("checked", false);
	}
	return true;
}

function checkSub(obj) {
	var pIdNum = parseFloat((obj.className).substring(10));
	if(obj.checked == true){
		jQuery('#groups_'+pIdNum).attr("checked", true);
	}else{
		var cboxs = jQuery('input[class="subgroups_'+pIdNum+'"]');
		var groupsChecked = false;
		for(var i=0; i<cboxs.length; i++){
			if(cboxs[i].checked==true){
				groupsChecked = true;
				break;
			}
		}
		jQuery('#groups_'+pIdNum).attr("checked", groupsChecked);
	}
	return true;
}
//-->
</script>

<?php
} else {
?>

<script language="JavaScript" type="text/JavaScript">
<!--
function validateForm() {
	var p,z,xEmail,errors='',dbEmail,result=0,i;

	var adminName1 = document.account.admin_firstname.value;
	var adminName2 = document.account.admin_lastname.value;
	var adminEmail = document.account.admin_email_address.value;
	var adminPass1 = document.account.admin_password.value;
	var adminPass2 = document.account.admin_password_confirm.value;

	if (adminName1 == '') {
		errors+='<?php echo JS_ALERT_FIRSTNAME; ?>';
	} else if (adminName1.length < <?php echo ENTRY_FIRST_NAME_MIN_LENGTH; ?>) {
		errors+='<?php echo JS_ALERT_FIRSTNAME_LENGTH . ENTRY_FIRST_NAME_MIN_LENGTH; ?>\n';
	}

	if (adminName2 == '') {
		errors+='<?php echo JS_ALERT_LASTNAME; ?>';
	} else if (adminName2.length < <?php echo ENTRY_LAST_NAME_MIN_LENGTH; ?>) {
		errors+='<?php echo JS_ALERT_LASTNAME_LENGTH . ENTRY_LAST_NAME_MIN_LENGTH;  ?>\n';
	}

	if (adminEmail == '') {
		errors+='<?php echo JS_ALERT_EMAIL; ?>';
	} else if (adminEmail.indexOf("@") <= 1 || adminEmail.indexOf("@") >= (adminEmail.length - 3) || adminEmail.indexOf("@.") >= 0 ) {
		errors+='<?php echo JS_ALERT_EMAIL_FORMAT; ?>';
	} else if (adminEmail.length < <?php echo ENTRY_EMAIL_ADDRESS_MIN_LENGTH; ?>) {
		errors+='<?php echo JS_ALERT_EMAIL_FORMAT; ?>';
	}

	if (adminPass1 == '') {
		errors+='<?php echo JS_ALERT_PASSWORD; ?>';
	} else if (adminPass1.length < <?php echo ENTRY_PASSWORD_MIN_LENGTH; ?>) {
		errors+='<?php echo JS_ALERT_PASSWORD_LENGTH . ENTRY_PASSWORD_MIN_LENGTH; ?>\n';
	} else if (adminPass1 != adminPass2) {
		errors+='<?php echo JS_ALERT_PASSWORD_CONFIRM; ?>';
	}

	if (errors) alert('The following error(s) occurred:\n'+errors);
	document.returnValue = (errors == '');
}

//-->
</script>

<?php
}
?>
