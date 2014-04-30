<?php
  /*
  Module: Information Pages Unlimited
        File date: 2003/03/02
      Based on the FAQ script of adgrafics
        Adjusted by Joeri Stegeman (joeri210 at yahoo.com), The Netherlands

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Released under the GNU General Public License
  */


require('includes/application_top.php');
// 备注添加删除
if($_GET['ajax']=="true"){
	include DIR_FS_CLASSES . 'Remark.class.php';
	$remark = new Remark('travel_agency');
	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
}

require(DIR_WS_LANGUAGES . $language . '/' . 'travel_agency.php');

function duplicate_email($email, $agency_id="", $check_type="0")//$check_type 0=duplicate email, 1=recore exists
{
	$cond="";
	if(tep_not_null($agency_id))
		$cond=" AND providers_agency_id!='".$agency_id."'";
	
	if($check_type=="1")
		$qry_email="SELECT providers_id FROM ".TABLE_PROVIDERS_LOGIN." WHERE providers_agency_id='".$agency_id."' AND parent_providers_id=0";
	else
		$qry_email="SELECT providers_id FROM ".TABLE_PROVIDERS_LOGIN." WHERE providers_email_address='".$email."'".$cond;
	
	$res_email=tep_db_query($qry_email);
	if(tep_db_num_rows($res_email)>0)
		return tep_db_num_rows($res_email);
	else
		return 0;
}

function randomize() {
	$salt = "ABCDEFGHIJKLMNOPQRSTUVWXWZabchefghjkmnpqrstuvwxyz0123456789";
	srand((double)microtime()*1000000);
	$i = 0;
	
	while ($i <= 7) {
		$num = rand() % 33;
		$tmp = substr($salt, $num, 1);
		$pass = $pass . $tmp;
		$i++;
	}
	return $pass;
}

function insert_provider($agency_id, $data)
{
	if($data['btnPassword']!="")
	{
		$sql_data_array=array('providers_email_address' => tep_db_prepare_input($data['providers_email_address']),
							'providers_agency_id' => $agency_id
						 );
		$data['providers_password']=$makePassword = randomize();
		if(tep_not_null($data['providers_password']))
		{
			$sql_data_array['providers_password']=md5($data['providers_password']);
		}
	
		if(duplicate_email("", $agency_id, 1))
		{
			tep_db_perform(TABLE_PROVIDERS_LOGIN, $sql_data_array, 'update', "providers_agency_id = '" . (int)$agency_id . "' AND parent_providers_id=0");
			set_providers_agency(tep_db_input($data['providers_email_address']), $agency_id);
			
			if(tep_not_null($data['providers_password']))
			{
				tep_mail($data['providers_email_address'], $data['providers_email_address'], PROVIDER_EMAIL_EDIT_SUBJECT, sprintf(PROVIDER_EMAIL_EDIT_TEXT, HTTP_SERVER . DIR_WS_PROVIDERS, $data['providers_email_address'], $data['providers_password'], STORE_OWNER), PROVIDER_MAIL_FROM, STORE_OWNER_EMAIL_ADDRESS);
			}
		}
		else
		{
			$sql_data_array['providers_status']=1;
			tep_db_perform(TABLE_PROVIDERS_LOGIN, $sql_data_array);
			set_providers_agency(tep_db_input($data['providers_email_address']), $agency_id);
			tep_mail($data['providers_email_address'], $data['providers_email_address'], PROVIDER_EMAIL_SUBJECT, sprintf(PROVIDER_EMAIL_TEXT, HTTP_SERVER . DIR_WS_PROVIDERS, $data['providers_email_address'], $data['providers_password'], STORE_OWNER), PROVIDER_MAIL_FROM, STORE_OWNER_EMAIL_ADDRESS);
		}
	}
}

function browse_information () {

if (isset($_GET['search']) && tep_not_null($_GET['search'])) {
	 $keywords = tep_db_input(tep_db_prepare_input($_GET['search']));
	 $where .= " and (agency_name like '%" . $keywords . "%' or agency_code like '%".$keywords."%' or agency_oper_lang like '%".$keywords."%' or agency_id='".$keywords."' ) ";
	
}
						   
if($_GET["sort"] == 'timezone') {
   if($_GET["order"] == 'ascending') {
		$sortorder .= 'agency_timezone asc ';
  } else {
		$sortorder .= 'agency_timezone desc ';
  }
}
else if($_GET["sort"] == 'name') {
   if($_GET["order"] == 'ascending') {
		$sortorder .= 'agency_name asc ';
  } else {
		$sortorder .= 'agency_name desc ';
  }
}
else if($_GET["sort"] == 'tourcode') {
   if($_GET["order"] == 'ascending') {
		$sortorder .= 'agency_code asc ';
  } else {
		$sortorder .= 'agency_code desc ';
  }
}else if($_GET["sort"] == 'aid') {
   if($_GET["order"] == 'ascending') {
		$sortorder .= 'agency_id asc ';
  } else {
		$sortorder .= 'agency_id desc ';
  }
}

else
{
	$sortorder .= 'agency_id ASC';
}
global $languages_id;
  $query="SELECT * FROM " . TABLE_TRAVEL_AGENCY . " WHERE languages_id=$languages_id ".$where." ORDER BY ".$sortorder." ";
  $daftar = tep_db_query($query) or die("Information ERROR: ".mysql_error());$c=0;
  while ($buffer = mysql_fetch_array($daftar)) {$result[$c]=$buffer;$c++;}
return $result;
}

function read_data ($agency_id) {
  //$result=mysql_fetch_array(mysql_db_query(DB_DATABASE, "SELECT * FROM " . TABLE_TRAVEL_AGENCY . " WHERE agency_id=$agency_id"));
	$result=mysql_fetch_array(tep_db_query("SELECT * FROM " . TABLE_TRAVEL_AGENCY . " t LEFT JOIN ".TABLE_PROVIDERS_LOGIN." l ON t.agency_id=l.providers_agency_id AND l.parent_providers_id=0 WHERE t.agency_id='" . intval($agency_id) . "'"));
	return $result;
}

$warning=tep_image(DIR_WS_ICONS . 'warning.gif', WARNING_INFORMATION);

function error_message($error) {
  global $warning;
  switch ($error) {
    case "20":return "<tr class=messageStackError><td>$warning ." . ERROR_20_INFORMATION . "</td></tr>";break;
		case "50":return "<tr class=messageStackError><td>$warning " . ERROR_50_INFORMATION . "</td></tr>";break;
    case "80":return "<tr class=messageStackError><td>$warning " . ERROR_80_INFORMATION . "</td></tr>";break;
	case "100":return "<tr class=messageStackError><td>$warning " . ERROR_100_INFORMATION . "</td></tr>";break;
    default:return $error;
  }
}

$display_delete_confirmation_form="0";
if(tep_not_null($_POST['btnDeleteAccount'])){
	$adgrafics_information="Edit";
	$display_delete_confirmation_form="1";
}
if(tep_not_null($_POST['btnDeleteCancel'])){
	tep_redirect(tep_href_link(FILENAME_TRAVEL_AGENCY));
}
if(tep_not_null($_POST['btnDeleteConfirmed'])){
	$qry_delete_provider_acc="DELETE FROM ".TABLE_PROVIDERS_LOGIN." WHERE providers_agency_id='".tep_db_input($_POST['agency_id'])."'";
	$res_delete_provider_acc=tep_db_query($qry_delete_provider_acc);
	
	$qry_hide_status_history="UPDATE ".TABLE_TRAVEL_AGENCY." SET providers_start_date='', providers_display_status_hist=0 WHERE agency_id='".tep_db_input($_POST['agency_id'])."'";
	$res_hide_status_history=tep_db_query($qry_hide_status_history);
	//tep_redirect(tep_href_link(FILENAME_TRAVEL_AGENCY, 'adgrafics_information=deleted'));
	$adgrafics_information="deleted";
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />
<script type="text/javascript" src="includes/menu.js"></script>

<?php if ((HTML_AREA_WYSIWYG_DISABLE == 'Enable') or (HTML_AREA_WYSIWYG_DISABLE_JPSY == 'Enable')) { ?>
<script language="Javascript1.2"><!-- // load htmlarea
//MaxiDVD Added WYSIWYG HTML Area Box + Admin Function v1.8 <head>
      _editor_url = "<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN; ?>htmlarea/";  // URL to htmlarea files
        var win_ie_ver = parseFloat(navigator.appVersion.split("MSIE")[1]);
         if (navigator.userAgent.indexOf('Mac')        >= 0) { win_ie_ver = 0; }
          if (navigator.userAgent.indexOf('Windows CE') >= 0) { win_ie_ver = 0; }
           if (navigator.userAgent.indexOf('Opera')      >= 0) { win_ie_ver = 0; }
       <?php if (HTML_AREA_WYSIWYG_BASIC_PD == 'Basic'){ ?>  if (win_ie_ver >= 5.5) {
       document.write('<scr' + 'ipt src="' +_editor_url+ 'editor_basic.js"');
       document.write(' language="Javascript1.2"></scr' + 'ipt>');
          } else { document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); }
       <?php } else{ ?> if (win_ie_ver >= 5.5) {
       document.write('<scr' + 'ipt src="' +_editor_url+ 'editor_advanced.js"');
       document.write(' language="Javascript1.2"></scr' + 'ipt>');
          } else { document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); }
       <?php }?>
// --></script>
<?php }?>

<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<script type="text/javascript" src="includes/javascript/spiffyCal/spiffyCal_v2_1_2008_04_01-min.js"></script>
<div id="spiffycalendar" class="text"></div>
<script type="text/javascript"><!--

var dateAvailable = new ctlSpiffyCalendarBox("dateAvailable", "travel_agency_frm", "next_update_due_date","btnDate","<?php echo $edit[next_update_due_date]; ?>",scBTNMODE_CUSTOMBLUE);
//--></script>
<script language="javascript" type="text/javascript">
function show_auto_charge_days(val)
{
	if(val == '1'){	
		document.getElementById('auto_charged_stop').style.display="block";
	}else{
		var auto_yes = document.getElementById('provider_auto_charged_yes').checked;		
		if(auto_yes == '1'){
			document.getElementById('auto_charged_stop').style.display="block";
		}else{
			document.getElementById('auto_charged_stop').style.display="none";
		}
	}
}

function show_auto_charge_days_package(val)
{
	if(val == '1'){	
		document.getElementById('auto_charged_stop_package').style.display="block";
	}else{
		var package_yes = document.getElementById('provider_auto_charged_package_yes').checked;
	
		if(package_yes == '1'){
			document.getElementById('auto_charged_stop_package').style.display="block";
		}else{
			document.getElementById('auto_charged_stop_package').style.display="none";
		}
	}
}

function check_form(divid)
{
		re = /^\d{1,2}:\d{2}([ap]m)?$/;		
		
		if(divid.value != '' && !divid.value.match(re)) 
		{ 			
			alert("Invalid time format: " + divid.value); 		
			divid.value = '';
			//divid.focus();
			return false; 
		}
					
	//return true;		
}
</script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
		<?php
		//echo $login_id;
		include DIR_FS_CLASSES . 'Remark.class.php';
		$listrs = new Remark('travel_agency');
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
    <td width="100%" valign="top">
<table border=0 width="100%">
<tr><td align=right><?php echo $language; ?></td></tr>
<?php
switch($adgrafics_information) {

case "Added":
    $data=browse_information();
    $no=1;
     if (sizeof($data) > 0) {while (list($key, $val)=each($data)) {$no++; } } ;
    $title="" . ADD_QUEUE_INFORMATION . " #$no";
    echo tep_draw_form('travel_agency_frm',FILENAME_TRAVEL_AGENCY, 'adgrafics_information=AddSure');
    include('travel_agency_form.php');
  break;

case "AddSure":
	if($allow_travle_agency_edit != true){
		break;
	}
	function add_information ($data) {
		global $languages_id;
		$acc_payment_frequency=$data['acc_payment_frequency'];
		if($data['acc_payment_frequency']=='1' && $data['txt_acc_payment_frequency']!=""){
			$acc_payment_frequency=$data['txt_acc_payment_frequency'];
		}
		$charge_time = '';
		$charge_time_package = '';	
		if($data['provider_auto_charged'] == 1){
			$charge_time = $data['sunday_s'].'-'.$data['sunday_e'].'!###!'.
						   $data['monday_s'].'-'.$data['monday_e'].'!###!'.
						   $data['tuesday_s'].'-'.$data['tuesday_e'].'!###!'.
						   $data['wednesday_s'].'-'.$data['wednesday_e'].'!###!'.
						   $data['thursday_s'].'-'.$data['thursday_e'].'!###!'.
						   $data['friday_s'].'-'.$data['friday_e'].'!###!'.
						   $data['saturday_s'].'-'.$data['saturday_e'];
		}
	
	
		if($data['provider_auto_charged_package'] == 1){
			$charge_time_package = $data['sunday_s_p'].'-'.$data['sunday_e_p'].'!###!'.
						   $data['monday_s_p'].'-'.$data['monday_e_p'].'!###!'.
						   $data['tuesday_s_p'].'-'.$data['tuesday_e_p'].'!###!'.
						   $data['wednesday_s_p'].'-'.$data['wednesday_e_p'].'!###!'.
						   $data['thursday_s_p'].'-'.$data['thursday_e_p'].'!###!'.
						   $data['friday_s_p'].'-'.$data['friday_e_p'].'!###!'.
						   $data['saturday_s_p'].'-'.$data['saturday_e_p'];
		}
		$query ="INSERT INTO " . TABLE_TRAVEL_AGENCY . " (`agency_id`,  `agency_name`,  `agency_name1`,  `emailaddress`,  `address`,  `city`,  `state`,  `zip`,  `country`,  `phone`,  `fax`,  `contactperson`,  `emerency_contactperson`,  `emerency_number`,  `languages_id`,  `data_join`,  `date_added`,  `last_modified`,  `agency_code`,  `agency_oper_lang`,  `website`,  `agency_timezone`,  `major_categories`,  `last_update_by`,  `next_update_due_date`,  `operate_currency_code`,  `default_max_allow_child_age`,  `default_transaction_fee`,  `provider_cxln_policy`,  `store_cxln_policy`,  `acc_phone`,  `acc_fax`,  `acc_email`,  `acc_address`,  `acc_payment_method`,   `acc_payment_frequency`,  `acc_notes`,  `accounting_contactperson`,  `providers_start_date`,  `providers_display_status_hist`,  `providers_separate_tours_package`,  `providers_can_send_eticket`,  `providers_default_eticket_comment`,  `book_limit_days`,  `book_limit_days_type`,  `book_limit_days_air`,  `book_limit_days_type_air`,  `provider_auto_charged`,  `provider_auto_charged_package`,  `time_difference_from_china`,  `formula`,  `res_notes`,  `auto_charged_stop_duration`,  `auto_charged_stop_duration_package`,  `max_auto_cap_amount`,  `max_auto_cap_amount_package`,  `is_birth_info`,  `is_gender_info`,  `is_hotel_pickup_info`,  `is_customer_signature`,  `local_operator_phone`,  `complaints_number`) VALUES(null, '$data[agency_name]', '$data[agency_name1]','$data[emailaddress]', '$data[address]', '$data[city]', '$data[state]', '$data[zip]', '$data[country]', '$data[phone]', '$data[fax]', '$data[contactperson]','$data[emerency_contactperson]', '$data[emerency_number]','$languages_id','now()','now()','now()', '$data[agency_code]', '$data[agency_oper_lang]','$data[website]','$data[agency_timezone]','$data[major_categories]','$data[last_update_by]', '$data[next_update_due_date]', '$data[operate_currency_code]','$data[default_max_allow_child_age]','$data[default_transaction_fee]','$data[provider_cxln_policy]','$data[store_cxln_policy]', '$data[acc_phone]', '$data[acc_fax]', '$data[acc_email]', '$data[acc_address]', '$data[acc_payment_method]', '$acc_payment_frequency', '$data[acc_notes]', '$data[accounting_contactperson]', '".tep_get_date_db($data['providers_start_date'])."', '".($data['providers_display_status_hist']=="on"?'1':'0')."', '".($data['providers_separate_tours_package']=="on"?'1':'0')."', '".($data['providers_can_send_eticket']=="on"?'1':'0')."', '$data[providers_default_eticket_comment]', '$data[book_limit_days]','$data[days_hours_types]', '$data[book_limit_days_air]','$data[days_hours_types_air]','$data[provider_auto_charged]','$data[provider_auto_charged_package]','$data[time_difference_from_china]','$data[formula]','$data[res_notes]','$charge_time','$charge_time_package','$data[max_auto_cap_amount]','$data[max_auto_cap_amount_package]','$data[is_birth_info]','$data[is_gender_info]','$data[is_hotel_pickup_info]', '$data[is_customer_signature]',	'$data[local_operator_phone]','0')";
		tep_db_query($query) or die ("Information ERROR: ".mysql_error());
	
		$agency_id=tep_db_insert_id();
	 	insert_provider($agency_id, $data);
	 	return $agency_id;
  }
	if($btnPassword!=""){
		if(!tep_not_null($providers_email_address) || !tep_validate_email($providers_email_address)){
				$error="100";
				break;
		}
	}
	if ($agency_name && $address && $zip)
	{
		if(duplicate_email($providers_email_address))
		{
			$error="50";
		}
		else
		{
					$agency_id=add_information($HTTP_POST_VARS);
			$data=browse_information();
				$title="" . tep_image(DIR_WS_ICONS . 'confirm_red.gif', CONFIRM_INFORMATION) .SUCCED_INFORMATION . ADD_QUEUE_INFORMATION . " $agency_name ";
				include('travel_agency_list.php');
		}
		//} else {$error="20";}
	} else {$error="80";}
  break;

case "Edit":
    if ($agency_id) {
    $edit=read_data($agency_id);

    $data=browse_information();
    $button=array("Update");
    $title="" . EDIT_ID_INFORMATION . " $agency_id";
    //echo form("$PHP_SELF?adgrafics_information=Update", $hidden);
    echo tep_draw_form('travel_agency_frm',FILENAME_TRAVEL_AGENCY, 'adgrafics_information=Update');
    echo tep_draw_hidden_field('agency_id', "$agency_id");
    include('travel_agency_form.php');
    } else {$error="80";}
  break;

case "Update":
	if($allow_travle_agency_edit != true){
		break;
	}
  function update_information ($data) {
	$acc_payment_frequency=$data['acc_payment_frequency'];
	if($data['acc_payment_frequency']=='1' && $data['txt_acc_payment_frequency']!=""){
		$acc_payment_frequency=$data['txt_acc_payment_frequency'];
	}
	$charge_time = '';
	$charge_time_package = '';
	if($data['provider_auto_charged'] == 1){
		$charge_time = $data['sunday_s'].'-'.$data['sunday_e'].'!###!'.
					   $data['monday_s'].'-'.$data['monday_e'].'!###!'.
					   $data['tuesday_s'].'-'.$data['tuesday_e'].'!###!'.
					   $data['wednesday_s'].'-'.$data['wednesday_e'].'!###!'.
					   $data['thursday_s'].'-'.$data['thursday_e'].'!###!'.
					   $data['friday_s'].'-'.$data['friday_e'].'!###!'.
					   $data['saturday_s'].'-'.$data['saturday_e'];
	}
	if($data['providers_email_notification']=='on'){
		$providers_email_notification = '1';
	}else{
		$providers_email_notification = '0';
	}
	tep_db_query("UPDATE " . TABLE_PROVIDERS_LOGIN . " SET  providers_email_notification = '$providers_email_notification' WHERE providers_agency_id = $data[agency_id] AND parent_providers_id=0"); 
	if($data['provider_auto_charged_package'] == 1){
		$charge_time_package = $data['sunday_s_p'].'-'.$data['sunday_e_p'].'!###!'.
					   $data['monday_s_p'].'-'.$data['monday_e_p'].'!###!'.
					   $data['tuesday_s_p'].'-'.$data['tuesday_e_p'].'!###!'.
					   $data['wednesday_s_p'].'-'.$data['wednesday_e_p'].'!###!'.
					   $data['thursday_s_p'].'-'.$data['thursday_e_p'].'!###!'.
					   $data['friday_s_p'].'-'.$data['friday_e_p'].'!###!'.
					   $data['saturday_s_p'].'-'.$data['saturday_e_p'];
	}
	tep_db_query("UPDATE " . TABLE_TRAVEL_AGENCY . " SET agency_name='$data[agency_name]', agency_name1='$data[agency_name1]', emailaddress='$data[emailaddress]', agency_oper_lang='$data[agency_oper_lang]', website='$data[website]', next_update_due_date='$data[next_update_due_date]', operate_currency_code='$data[operate_currency_code]', default_max_allow_child_age='$data[default_max_allow_child_age]', default_transaction_fee='$data[default_transaction_fee]', provider_cxln_policy='$data[provider_cxln_policy]', store_cxln_policy='$data[store_cxln_policy]', agency_timezone='$data[agency_timezone]', major_categories='$data[major_categories]', last_update_by ='$data[last_update_by]', address='$data[address]', city='$data[city]', state='$data[state]', zip='$data[zip]', country='$data[country]', phone='$data[phone]', fax='$data[fax]', contactperson='$data[contactperson]', emerency_contactperson='$data[emerency_contactperson]', emerency_number='$data[emerency_number]', agency_code='$data[agency_code]', acc_phone='$data[acc_phone]', acc_fax='$data[acc_fax]', acc_email='$data[acc_email]', acc_address='$data[acc_address]', acc_payment_method='$data[acc_payment_method]', acc_payment_frequency='$acc_payment_frequency', acc_notes='$data[acc_notes]', accounting_contactperson='$data[accounting_contactperson]', providers_start_date='".tep_get_date_db($data['providers_start_date'])."', providers_display_status_hist = '".($data['providers_display_status_hist']=="on"?'1':'0')."', providers_separate_tours_package = '".($data['providers_separate_tours_package']=="on"?'1':'0')."', providers_can_send_eticket = '".($data['providers_can_send_eticket']=="on"?'1':'0')."', providers_default_eticket_comment='$data[providers_default_eticket_comment]', book_limit_days='$data[book_limit_days]',book_limit_days_type='$data[days_hours_types]', book_limit_days_air='$data[book_limit_days_air]',book_limit_days_type_air='$data[days_hours_types_air]',provider_auto_charged='$data[provider_auto_charged]', provider_auto_charged_package='$data[provider_auto_charged_package]', time_difference_from_china='$data[time_difference_from_china]', res_notes='$data[res_notes]' , formula='$data[formula]' , auto_charged_stop_duration = '$charge_time', auto_charged_stop_duration_package = '$charge_time_package',max_auto_cap_amount='$data[max_auto_cap_amount]',max_auto_cap_amount_package='$data[max_auto_cap_amount_package]',is_birth_info = '$data[is_birth_info]',is_gender_info = '$data[is_gender_info]',is_hotel_pickup_info = '$data[is_hotel_pickup_info]',is_customer_signature = '$data[is_customer_signature]', local_operator_phone='$data[local_operator_phone]' WHERE agency_id=$data[agency_id]") or die ("update_information: ".mysql_error());
  $agency_id=$data['agency_id'];
  insert_provider($agency_id, $data);
  }
  if($btnPassword!=""){
		if(!tep_not_null($providers_email_address) || !tep_validate_email($providers_email_address)){
				$error="100";
				break;
		}
  }
	
	if ($agency_id && $address && $agency_name && $zip)
	{
		if(duplicate_email($providers_email_address, $agency_id))
		{
			$error="50";
		}
		else
		{
			update_information($HTTP_POST_VARS);
			$data=browse_information();
			$title="$confirm " . UPDATE_ID_INFORMATION . " $agency_id " . SUCCED_INFORMATION . "";
			//include('travel_agency_list.php');
			$messageStack->add_session('Updated Successfully!!!', 'success');
			?><script> window.location="<?php echo tep_href_link(FILENAME_TRAVEL_AGENCY, 'adgrafics_information=Edit&agency_id='.(int)$agency_id.''); ?>";</script>
			<?php
			exit;
		}
	} else {$error="80";}
  break;

      case 'Visible':
  function tep_set_information_visible($agency_id, $visible) {
  if ($visible == '1') {
  return tep_db_query("update " . TABLE_TRAVEL_AGENCY . " set visible = '0' where agency_id = '" . $agency_id . "'");
  } else{
  return tep_db_query("update " . TABLE_TRAVEL_AGENCY . " set visible = '1' where agency_id = '" . $agency_id . "'");
  }
  }
    tep_set_information_visible($agency_id, $visible);
    $data=browse_information();
    if ($visible == '1') {  $vivod=DEACTIVATION_ID_INFORMATION;
    }else{$vivod=ACTIVATION_ID_INFORMATION;}
    $title="$confirm $vivod $agency_id " . SUCCED_INFORMATION . "";
    include('travel_agency_list.php');
        break;

case "Delete":
	if($allow_travle_agency_edit != true){
		break;
	}
    if ($agency_id) {
    $delete=read_data($agency_id);
    $data=browse_information();
    $title="" . DELETE_CONFITMATION_ID_INFORMATION . " $agency_id";
    echo "<tr class=pageHeading><td>$title  </td></tr>";
    echo "<tr><td>" . TEXT_AGENCY_NAME . " $delete[agency_name]</td></tr><tr><td align=right>";
    echo tep_draw_form('',FILENAME_TRAVEL_AGENCY, "adgrafics_information=DelSure&agency_id=$val[agency_id]");
    echo tep_draw_hidden_field('agency_id', "$agency_id");
    echo tep_image_submit('button_delete.gif', IMAGE_DELETE);
    echo '<a href="' . tep_href_link(FILENAME_TRAVEL_AGENCY, tep_get_all_get_params(array('agency_id','adgrafics_information')), 'NONSSL') . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
    echo "</form></td></tr>";
    } else {$error="80";}
    break;


case "DelSure":
	if($allow_travle_agency_edit != true){
		break;
	}
  function delete_information ($agency_id) {
  tep_db_query( "DELETE FROM " . TABLE_TRAVEL_AGENCY . " WHERE agency_id=$agency_id");
  }
    if ($agency_id) {
    delete_information($agency_id);
    $data=browse_information();
    $title="$confirm " . DELETED_ID_INFORMATION . " $agency_id " . SUCCED_INFORMATION . "";
    include('travel_agency_list.php');
    } else {$error="80";}
    break;
case "deleted":
	$title="$confirm " . DELETED_PROVIDER_INFORMATION . " $agency_id " . SUCCED_INFORMATION . "";
	$data=browse_information();
	include('travel_agency_list.php');
	break;
default:
    $data=browse_information();
    $title="" . MANAGER_INFORMATION . "";
    include('travel_agency_list.php');
  }
if ($error) {
    $content=error_message($error);
    echo $content;
	$edit=read_data($agency_id);
    $data=browse_information();
    $no=1;
     if (sizeof($data) > 0) {while (list($key, $val)=each($data)) {$no++; } } ;
    $title="" . ADD_QUEUE_INFORMATION . " $no";
		$adgrafics_information=$_GET['adgrafics_information'];
	if(!tep_not_null($adgrafics_information))
		$adgrafics_information='AddSure';
	
    echo tep_draw_form('travel_agency_frm',FILENAME_TRAVEL_AGENCY, 'adgrafics_information='.$adgrafics_information);
	echo tep_draw_hidden_field('agency_id', "$agency_id");
    include('travel_agency_form.php');
}
?>
</table>
</td>


<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
