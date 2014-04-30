<?php
/*
  $Id: create_order.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

*/


  require('includes/application_top.php');
  // 备注添加删除
  if($_GET['ajax']=="true"){
  	include DIR_FS_CLASSES . 'Remark.class.php';
  	$remark = new Remark('create_order');
  	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
  }
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ORDER);

//amit added to unset session strat
	 unset($_SESSION['customer_id']);
  unset($_SESSION['gender']);
  unset($_SESSION['firstname']);
  unset($_SESSION['lastname']);
  unset($_SESSION['dob']);
  unset($_SESSION['email_address']);
  unset($_SESSION['telephone']);
  unset($_SESSION['fax']);
  unset($_SESSION['newsletter']);
  unset($_SESSION['password']);
  unset($_SESSION['confirmation']);
  unset($_SESSION['street_address']);
  unset($_SESSION['company']);
  unset($_SESSION['suburb']);
  unset($_SESSION['postcode']);
  unset($_SESSION['city']);
  unset($_SESSION['zone_id']);
  unset($_SESSION['state']);
  unset($_SESSION['country']);

	//amit added to unset session end
// #### Get Available Customers

	$query = tep_db_query("select customers_id, customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " ORDER BY customers_lastname DESC");
    $result = $query;


	if (tep_db_num_rows($result) > 0)
{
   // Query Successful
     $SelectCustomerBox = "<select name='Customer'><option value=''>". BUTTON_TEXT_CHOOSE_CUST . "</option>\n";
     while($db_Row = tep_db_fetch_array($result))
     { $SelectCustomerBox .= "<option value='" . $db_Row["customers_id"] . "'";
	   if(IsSet($HTTP_GET_VARS['Customer']) and $db_Row["customers_id"]==$HTTP_GET_VARS['Customer'])
		$SelectCustomerBox .= " SELECTED ";
	  //$SelectCustomerBox .= ">" . $db_Row["customers_lastname"] . " , " . $db_Row["customers_firstname"] . " - " . $db_Row["customers_id"] . "</option>\n";
	   $SelectCustomerBox .= ">" . $db_Row["customers_lastname"] . " , " . $db_Row["customers_firstname"] . "</option>\n";

		}

		$SelectCustomerBox .= "</select>\n";
	}

if(IsSet($HTTP_GET_VARS['Customer']))
{
 $account_query = tep_db_query("select * from " . TABLE_CUSTOMERS . " where customers_id = '" . $HTTP_GET_VARS['Customer'] . "'");
 $account = tep_db_fetch_array($account_query);
 $customer = $account['customers_id'];
 $address_query = tep_db_query("select * from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $HTTP_GET_VARS['Customer'] . "'");
 $address = tep_db_fetch_array($address_query);
 //$customer = $account['customers_id'];
} elseif (IsSet($HTTP_GET_VARS['Customer_nr']))
{
 $account_query = tep_db_query("select * from " . TABLE_CUSTOMERS . " where customers_id = '" . $HTTP_GET_VARS['Customer_nr'] . "'");
 $account = tep_db_fetch_array($account_query);
 $customer = $account['customers_id'];
 $address_query = tep_db_query("select * from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $HTTP_GET_VARS['Customer_nr'] . "'");
 $address = tep_db_fetch_array($address_query);
 //$customer = $account['customers_id'];
}

// #### Generate Page
	?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
  <title>Step-by-Step Manual Order Entry - Step 1</title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
<script language="javascript" src="includes/general.js"></script>
<?php require('includes/form_check.js.php'); ?>

<script type="text/javascript">
//快搜客户
function SearchCustomer(){
	var CusForm = document.getElementById("CusForm");
	var Customer = CusForm.elements['Customer'];
	var Search_Customer = CusForm.elements['Search_Customer'];
	if(Search_Customer.value.length){
		for(i=0; i<Customer.options.length; i++){
			var case_a = Customer.options[i].text.toLowerCase();
			var case_b = Search_Customer.value.toLowerCase();
			if(case_a.indexOf(case_b) > -1){
				Customer.selectedIndex = i;
			}
		}
	}
}
</script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('create_order');
$list = $listrs->showRemark();
?>

<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->

<td valign="top">
		<table border='0' bgcolor='#7c6bce' width='100%'>
			<tr><td class=main><font color='#ffffff'><b><?php echo HEADING_STEP1 ?></b></font></td></tr>
		</table>
		<table border='0' cellpadding='7'><tr><td class="main" valign="top">


<form name="CusForm" id="CusForm" action="<?php echo FILENAME_CREATE_ORDER; ?>" method="GET">
<table border="0"><tr>
<td><font class="main"><b><?php echo TEXT_SELECT_CUST; ?></b></font><br><?php echo $SelectCustomerBox;?></td>
<td valign="bottom" class=main><input type="submit" value="<?php echo BUTTON_TEXT_SELECT_CUST;?>">&nbsp;   key:<input type="text" name="Search_Customer" >
<!--onKeyPress="SearchCustomer()" onKeyDown="SearchCustomer()" onKeyUp="SearchCustomer()"//-->
<input type="button" onclick="SearchCustomer()" value="搜索"/>
</td>
</tr></table></form>

<?php
echo  '<form action="' . FILENAME_CREATE_ORDER . '" method="GET">' . "\n";
echo  '<table border="0"><tr>' . "\n";
echo  '<td><font class="main"><b>' . TEXT_OR_BY . '</b></font><br><input type="text" name="Customer_nr"></td>' . "\n";
echo  '<td valign="bottom"><input type="submit" value="' . BUTTON_TEXT_CHOOSE_CUST . '"></td>' . "\n";
echo  '</tr></table></form>' . "\n";
?>

		</tr>

    <td width="100%" valign="top"><?php echo tep_draw_form('create_order', FILENAME_CREATE_ORDER_PROCESS, '', 'post', '', '') . tep_draw_hidden_field('customers_id', $account['customers_id']); ?><table border="0" width="100%" cellspacing="0" cellpadding="0">

	 </tr> <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_CREATE; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td>
<?php

//onSubmit="return check_form();"

  require(DIR_WS_MODULES . 'create_order_details.php');

?>
        </td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT, '', 'SSL') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
            <td class="main" align="right"><?php echo tep_image_submit('button_confirm.gif', IMAGE_BUTTON_CONFIRM); ?></td>
          </tr>
        </table></td>
      </tr>
    </table></form></td>
<!-- body_text_eof //-->

  </tr>
</table>
<!-- body_eof //-->
</table>

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php
require(DIR_WS_INCLUDES . 'application_bottom.php');

if($HTTP_GET_VARS['forcesumit'] == 'true' && $HTTP_GET_VARS['Customer'] != '' && $HTTP_GET_VARS['Customer'] != '0'){
?>
<script language="javascript">
document.create_order.submit();
</script>
<?php
}
?>