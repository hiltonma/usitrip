<?php
/*
 wishlist.php,v 2.0  2003/11/22 Jesse Labrocca

 osCommerce, Open Source E-Commerce Solutions
 http://www.oscommerce.com

 Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_WAITLIST);
  
  if (tep_session_is_registered('customer_id')) {
    $account = tep_db_query("select customers_firstname, customers_cellphone, customers_email_address from " . TABLE_CUSTOMERS . " where customers_id = '" . $customer_id . "'");
    $account_values = tep_db_fetch_array($account);
  }
  
  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process')) {
   	$name = tep_db_prepare_input($HTTP_POST_VARS['name']);
	$email = tep_db_prepare_input($HTTP_POST_VARS['email']);
	$phone = tep_db_prepare_input($HTTP_POST_VARS['phone']);
	$product_id = tep_db_prepare_input($HTTP_POST_VARS['product_id']);
	$tour_code = tep_db_prepare_input($HTTP_POST_VARS['tour_code']);
	$travel_date = tep_db_prepare_input($HTTP_POST_VARS['traveldate']);
	$call_time = tep_db_prepare_input($HTTP_POST_VARS['phone_call_time']);	
		
	$wait_list_alredy_add = tep_db_query("select waitlist_id from " . TABLE_CUSTOMER_WAITLIST . " where cutomer_email = '" . $email . "' and tour_code = '" . $tour_code . "' and cutomer_travel_date = '".$travel_date." 00:00:00' limit 1");
	
	if(tep_db_num_rows($wait_list_alredy_add) > 0){
			tep_redirect(tep_href_link(FILENAME_WAITLIST, 'action=already&products_id='.$product_id.'&tour_code='.$tour_code, 'NONSSL'));
	}else{
			tep_db_query("insert into " . TABLE_CUSTOMER_WAITLIST . " (customer_name,cutomer_email,cutomer_travel_date,customer_request_date,cutomer_phone,products_id,tour_code,phone_call_time) values ('" . $name . "', '" . $email . "', '" . $travel_date . "', now(), '" . $phone . "','".$product_id."','".$tour_code."','".$call_time."')");
 
	   echo '<script type="text/javascript" language="javascript">
		parent.closePopup("popupWaitlist");
		parent.jQuery("#gWaitlistIframe").attr("src","");
		alert("'.db_to_html("资料发送成功！").'");
		</script>';
		exit;
	}
  }
  
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?= CHARSET?>" />
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>" />
<link href="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME.'/homepage-2010-0915.css';?>" rel="stylesheet" type="text/css" />
<link href="<?php echo TEMPLATE_STYLE;?>" rel="stylesheet" type="text/css" />
<style type="text/css">
body{ background:none;}
.wishlistCon{ margin:0 15px 15px 15px;}
.wishlistCon p{ line-height:24px;}
.wishlistCon .formCon li{ padding:5px 0;}
.wishlistCon .formCon li label{ display:inline-block; width:110px; color:#777;}
.wishlistCon .formCon li .text{ width:200px;}
input.required{ border:1px solid #d5d5d5;}
.wishlistCon .formCon li .validation-advice{ display:inline-block; *display:inline; *zoom:1; background:url(<?= DIR_WS_TEMPLATE_IMAGES;?>icons/tip_error.gif) no-repeat 0 center;}
.wishlistCon h2{ padding:15px 0 5px; line-height:24px;}
.wishlistCon .btnCenter{ padding:25px 0 15px; height:25px;}
</style>


<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
<title><?php echo HEADING_TITLE_WAITLIST.' '.$HTTP_GET_VARS['tour_code']; ?></title>
<script type="text/javascript">
var radio_error = "<?php echo RADIO_ERROR?>";
var select_option_error = "<?php echo SELECT_OPTION_ERROR?>";
</script>
<script type="text/javascript" src="jquery-1.3.2/merger/merger.min.js"></script>
<script type="text/javascript" src="<?php echo DIR_WS_JAVASCRIPT.VALIDATION_JS;?>"></script>
<?php
$sc_tw = 'tw';
if($language=='schinese'){
	$sc_tw = 'sc';
}
?>
<script type="text/javascript" src="javascript.php?language=<?=$sc_tw?>&files=global&Show_Calendar_JS=true"></script>
<script type="text/javascript" language="javascript">
function Wclose()
{
	self.close();
}
</script>
</head>
<body>
	<?php
	$product_id = (int)$HTTP_GET_VARS['products_id'];
	?>
	<div class="wishlistCon">		
		<?php echo tep_draw_form('waitlist_form', tep_href_link(FILENAME_WAITLIST, 'action=process'),'post', ' id="waitlist_form"'); ?>
		<?php echo tep_draw_hidden_field('product_id',$product_id); ?>
		<?php echo tep_draw_hidden_field('tour_code',$HTTP_GET_VARS['tour_code']); ?>

		<?php if($HTTP_GET_VARS['action'] == 'already'){?>
		<div style="background-color:#00CC66;color:#FFFFFF;"><?php echo TEXT_ALREADY_EXIST;?></div>
		<?php } ?>
		
		<h2><?php echo TEXT_HEADING.' '.$HTTP_GET_VARS['tour_code'];?>?</h2>
		<p><?php echo TEXT_DESC1; ?></p>
		<p><?php echo TEXT_DESC2; ?></p>
		<p style="color:#E40000;"><?php echo TEXT_CALL_COMMENT; ?></p>

		<h2><?php echo db_to_html("以下均为必填项"); ?></h2>

		<div class="formCon">
		
			<ul>
				<li>
					<label><?php echo TEXT_DATE; ?></label>
												
					<?php echo tep_draw_input_field('traveldate', tep_db_prepare_input(''), 'autocomplete="off" style="width: 120px; height: 16px; font-size: 12px; text-decoration: none; color:#223D6A;  margin-top:3px; padding:2px 0px 2px 5px; background:url('.DIR_WS_TEMPLATE_IMAGES.'time-selction.gif) no-repeat right center #FFFFFF;" id="traveldate" size="10" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" class="required" title="'.TEXT_DATE_ERROR.'"'); ?>
				</li>
				<li>
					<label><?php echo TEXT_FULL_NAME; ?></label>
					<?php echo tep_draw_input_field('name',db_to_html($account_values['customers_firstname']),'id="name" class="required validate-length-firstname text" title="'.ENTRY_FIRST_NAME_ERROR.'"'); ?>							
				</li>
				<li>
					<label><?php echo TEXT_EMAIL; ?></label>
					<?php echo tep_draw_input_field('email',$account_values['customers_email_address'],'id="email" class="required validate-email text" title="'.ENTRY_EMAIL_ADDRESS_CHECK_ERROR.'"'); ?>
				</li>
				
				<li>
					<label><?php echo TEXT_EMAIL_CONFIRM; ?></label>
					<?php echo tep_draw_input_field('c_email_address','','id="c_email_address" class="required validate-email-confirm text" title="'.ERROR_EMAIL_CONFIRM.'"'); ?>
				</li>
				
				<li>
					<label><?php echo TEXT_PHONE; ?></label>
					<?php echo tep_draw_input_field('phone',$account_values['customers_cellphone'],'id="phone" class="required text" title="'.TEXT_PHONE_ERROR.'"'); ?>							
				</li>
				<li>
					<label><?php echo TEXT_PHONE_CALL_TIME; ?></label>
					<?php echo tep_draw_input_field('phone_call_time','','id="phone_call_time" class="text"'); ?>						
				</li>
			</ul>

			<div class="btnCenter">
				<a class="btn btnOrange" href="javascript:;"><button type="submit"><?= db_to_html("提 交");?></button></a>&nbsp;&nbsp;
				<a class="btn btnGrey" href="javascript:;" onclick="parent.closePopup('popupWaitlist')"><button type="button"><?= db_to_html("取 消");?></button></a>
			</div>
		</div>	
		<?php echo '</form>'; ?>
		</div>	

	<script type="text/javascript">
function formCallback(result, form) {
	window.status = "valiation callback for form '" + form.id + "': result = " + result;
}

var valid = new Validation('waitlist_form', {immediate : true,useTitles:true, onFormValidate : formCallback});
Validation.addAllThese([
['validate-email-confirm', '<?php echo ERROR_EMAIL_CONFIRM; ?>', {
								equalToField : 'email'
							}]
]);
</script>


<?php if($language=='schinese'){?>
<script type="text/javascript" src="big5_gb-min.js"></script>
<?php }else{?>
<script type="text/javascript" src="gb_big5-min.js"></script>
<?php }
?>
</body>
</html>
<script type="text/javascript">
//parent.jQuery('#popupConWaitlist').width(725);
parent.jQuery('#gWaitlisttips').hide();
parent.jQuery('#gWaitlistIframe').show();
parent.showPopup('popupWaitlist','popupConWaitlist',true,0,0,'','',true);
</script>
<?php
	//$javascript = 'product_info.js.php';
  require(DIR_FS_INCLUDES . 'application_bottom.php');
?>
