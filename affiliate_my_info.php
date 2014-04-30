<?php
require('includes/application_top.php');

define('NAVBAR_TITLE_2', db_to_html("联盟账号信息"));

if (!tep_session_is_registered('affiliate_id')) {
	$navigation->set_snapshot();
	if(tep_not_null($_COOKIE['LoginDate'])){
		$messageStack->add_session('login', LOGIN_OVERTIME);
		setcookie('LoginDate', '');
	}
	tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
}

  // 网站联盟开关
  if (strtolower(AFFILIATE_SWITCH) === 'false') {
  	if ($_GET['action'] != '' || $_POST['ajax'] == 'true') {
  		echo '[JS]'.db_to_html('alert("此功能暂不开放！")').'[/JS]';
  	} else {
  		echo '<div align="center">此功能暂不开放！回<a href="' . tep_href_link('index.php') . '">首页</a></div>';
  	}
  	exit();
  }


require(DIR_FS_CLASSES . 'affiliate.php');
$affiliate = new affiliate;

$affiliate_id = $_SESSION['affiliate_id'];

//POST start{
if($_POST['ajax']=='true'){
	header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
	header( "Cache-Control: no-cache, must-revalidate" );
	header( "Pragma: no-cache" );
	require(DIR_FS_INCLUDES . 'ajax_encoding_control.php');
}

$submitBtnText = '提交申请信息';
$submitBtnText1 = '提交中……';
switch($_GET['action']){
	case "verify_email":
		$error = false;
		$js_str = '';
		$email_adderss = $_GET['email_adderss'];
		if(!tep_not_null($email_adderss)){
			$error = true;
			$error_msn .= '邮箱：不能为空！'.'\n'; 
		}elseif(tep_validate_email($email_adderss)==false){
			$error = true;
			$error_msn .= '邮箱：'.$email_adderss.'不是标准的邮箱'.'\n'; 
		}elseif((int)checkDuplicateAffiliateEmail($email_adderss, (int)$affiliate_id)){
			$error = true;
			$error_msn .= '邮箱：'.$email_adderss.'已经存在，请另选邮箱'.'\n'; 
		}
		if($error==false){
			if((int)send_affiliate_validation_mail($email_adderss)){
				$js_str.='jQuery("#EmailTips").html("验证邮件已经发送到您的邮箱：'.$email_adderss.'");';
				$js_str.='jQuery("#EmailTips").addClass("normalTip");';
				$js_str.='jQuery("#EmailTips").hide(0);';
				$js_str.='jQuery("#EmailTips").show(300);';
				$js_str.='jQuery("#verifyBtn button[type=\'button\']").html("重发验证邮件");';
			}else{
				$js_str = 'alert("发送失败，邮箱或许有重复");';
			}
		}else{
			$js_str = 'alert("'.$error_msn.'");';
		}
		$js_str .= 'CanSendVerify = 1; ';
		$js_str = preg_replace('/[[:space:]]+/',' ',$js_str);
		echo '[JS]'.db_to_html($js_str).'[/JS]';
		exit;
	break;
	case "edit":
		$submitBtnText = '确定保存';
		$submitBtnText1 = '保存中……';
	break;
	case "SubmitMyInfo":	//提交联盟申请资料
		
		if($_POST['ajax']=='true'){
			$error = false;
			$error_msn = "";
			$js_str = "";
			$affiliate_firstname = tep_db_prepare_input($_POST['affiliate_firstname']);
			$affiliate_lastname = tep_db_prepare_input(preg_replace('/[[:space:]]+/','',$_POST['surName'])).' '.tep_db_prepare_input(preg_replace('/[[:space:]]+/','',$_POST['givenName']));
			if(!preg_match('/^[\w]+ [\w]+$/',$affiliate_lastname)){
				$affiliate_lastname = "";
			}
			
			$affiliate_email_address = tep_db_prepare_input($_POST['affiliate_email_address']);
			$affiliate_mobile = tep_db_prepare_input($_POST['affiliate_mobile']);
			$affiliate_telephone = tep_db_prepare_input($_POST['affiliate_telephone']);
			$affiliate_qq = tep_db_prepare_input($_POST['affiliate_qq']);
			$affiliate_msn = tep_db_prepare_input($_POST['affiliate_msn']);
			$affiliate_homepage_name = tep_db_prepare_input($_POST['affiliate_homepage_name']);
			$affiliate_homepage = tep_db_prepare_input($_POST['affiliate_homepage']);
			$affiliate_site_type_id = (int)$_POST['affiliate_site_type_id'];
			$affiliate_site_profile = tep_db_prepare_input($_POST['affiliate_site_profile']);
			
			if(!tep_not_null($affiliate_firstname)){
				//$error = true;
				//$error_msn .= '中文姓名：不能为空！'.'\n'; 
			}
			if(!tep_not_null($affiliate_email_address)){
				//$error = true;
				//$error_msn .= '邮箱：不能为空！'.'\n'; 
			}elseif(tep_validate_email($affiliate_email_address)==false){
				//$error = true;
				//$error_msn .= '邮箱：'.$affiliate_email_address.'不是标准的邮箱'.'\n'; 
			}elseif((int)checkDuplicateAffiliateEmail($affiliate_email_address, (int)$affiliate_id)){
				//$error = true;
				//$error_msn .= '邮箱：'.$affiliate_email_address.'已经存在，请另选邮箱'.'\n'; 
			}elseif(update_affiliate_customers_email_address($affiliate_email_address)==false){	//此处是检测和修改客户表和博客表用户邮箱
				//$error = true;
				//$error_msn .= '邮箱：'.$affiliate_email_address.'已经被注册，请另选邮箱'.'\n'; 
			}
			
			if(!tep_not_null($affiliate_mobile)){
				//$error = true;
				//$error_msn .= '手机：不能为空！'.'\n'; 
			}
			if(!tep_not_null($affiliate_qq) && !tep_not_null($affiliate_msn)){
				$error = true;
				$error_msn .= 'QQ和MSN必填一项！'.'\n'; 
			}
						
			if($error==false){
				$sql_data_array = array('affiliate_firstname' => ajax_to_general_string($affiliate_firstname),
										'affiliate_lastname' => ajax_to_general_string($affiliate_lastname),
										'affiliate_email_address' => ajax_to_general_string($affiliate_email_address),
										'affiliate_mobile' => ajax_to_general_string($affiliate_mobile),
										'affiliate_telephone' => ajax_to_general_string($affiliate_telephone),
										'affiliate_qq' => ajax_to_general_string($affiliate_qq),
										'affiliate_msn' => ajax_to_general_string($affiliate_msn),
										'affiliate_homepage_name' => ajax_to_general_string($affiliate_homepage_name),
										'affiliate_homepage' => ajax_to_general_string($affiliate_homepage),
										'affiliate_site_type_id' => $affiliate_site_type_id,
										'affiliate_site_profile' => ajax_to_general_string($affiliate_site_profile),
										'verified' => '1'
										);
				$sql_data_array = html_to_db ($sql_data_array);
				tep_db_perform(TABLE_AFFILIATE, $sql_data_array, 'update', ' affiliate_id ="'.(int)$affiliate_id.'" ');
				
				
				
				
				//更新session
				if(!(int)$_SESSION['affiliate_verified']){
					$_SESSION['affiliate_verification_successful']=1;
				}else{
					$messageStack->add_session('affiliate_my_info', "恭喜，您的联盟资料更新成功！", 'success');
				}
				setSessionAffiliateInfo($customer_id);
				
				$js_str = 'document.location="'.tep_href_link('affiliate_my_info.php').'";';
				
			}else{
				$js_str = 'alert("'.$error_msn.'");';
				$js_str .= '_disabledAllowBtn("affiliateForm","show");';
			}
			
			$js_str = preg_replace('/[[:space:]]+/',' ',$js_str);
			//echo $affiliate_id;
			echo '[JS]'.db_to_html($js_str).'[/JS]';
		}
	exit;
	break;
}
//POST end}

//affiliate info {
$affiliateInfo = array();

// 目录这代码跑完后，有部分被后面给覆盖掉了。注意不一定能注释掉。因为 affiliate_affiliate表与下面的用户表的字段不一定完全一样 by lwkai add 注释 2012-06-12
$affiliate_sql = tep_db_query("select * from " . TABLE_AFFILIATE . " where affiliate_id=" . (int)$affiliate_id);
$affiliateInfo = tep_db_fetch_array($affiliate_sql);


$affiliateInfo['surName'] = '姓Surname';
$affiliateInfo['givenName'] = '名Given names';
$affiliateInfo['surNameParameter'] = 'onfocus="if(this.value!=\'姓Surname\'){this.style.color=\'#000\'}else{this.value=\'\';this.style.color=\'#000\'}" onblur="if(this.value==\'\'){this.value=\'姓Surname\';this.style.color=\'#b6b7b9\'}" class="text surName" ';
$affiliateInfo['givenNameParameter'] = 'onfocus="if(this.value!=\'名Given names\'){this.style.color=\'#000\'}else{this.value=\'\';this.style.color=\'#000\'}" onblur="if(this.value==\'\'){this.value=\'名Given names\';this.style.color=\'#b6b7b9\'}" class="text givenName" ';
if(tep_not_null($affiliateInfo['affiliate_lastname'])){
	$tmpArray = explode(' ',$affiliateInfo['affiliate_lastname']);
	$affiliateInfo['surName'] = $tmpArray[0];
	$affiliateInfo['givenName'] = $tmpArray[1];
}

$site_type_sql = tep_db_query("SELECT * FROM `affiliate_site_type` ORDER BY `affiliate_site_type_id` ASC ");
$affiliateInfo['siteTypeRadios'] = '';
$affiliateInfo['siteTypeString'] = '';
$radioFirst = ' class="radioFirst" ';
while($site_type_rows = tep_db_fetch_array($site_type_sql)){
	$_checked = '';
	if((int)$site_type_rows['affiliate_site_type_id'] && $site_type_rows['affiliate_site_type_id']==$affiliateInfo['affiliate_site_type_id']){
		$_checked = ' checked ';
		$affiliateInfo['siteTypeString'] = $site_type_rows['affiliate_site_type_name'];
	}
	$affiliateInfo['siteTypeRadios'] .= '<label><input type="radio" value="'.$site_type_rows['affiliate_site_type_id'].'" name="affiliate_site_type_id" '.$radioFirst.$_checked.' />'.$site_type_rows['affiliate_site_type_name']."</label>\n";
	unset($radioFirst);
}
// 以下开始覆盖部分数据。。 by lwkai 添加注释 2012-06-12

//因新需求，用个人账户信息的替代Af的信息{
$customers = tep_db_query("select * from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");
$customersInfo = tep_db_fetch_array($customers);

$affiliateInfo['affiliate_firstname'] = $customersInfo['customers_firstname'];
$affiliateInfo['affiliate_lastname'] = $customersInfo['customers_lastname'];
$tmpArray = explode(' ',$affiliateInfo['affiliate_lastname']);
$affiliateInfo['surName'] = $tmpArray[0];
$affiliateInfo['givenName'] = $tmpArray[1];
$affiliateInfo['affiliate_email_address'] = $customersInfo['customers_email_address'];
$affiliateInfo['affiliate_mobile'] = $customersInfo['customers_mobile_phone'];
$affiliateInfo['affiliate_telephone'] = $customersInfo['customers_telephone'];
//因新需求，用个人账户信息的替代Af的信息}

//处理输出变量用于提交申请表单数据{
foreach($affiliateInfo as $key => $val){
	$$key = tep_db_prepare_input($val);
}
if(!tep_not_null($affiliate_email_address)) $affiliate_email_address = $_SESSION['customer_email_address'];
//处理输出变量用于提交申请表单数据}

$affiliate_verified = $_SESSION['affiliate_verified'];
$post_verification_successful = 0;
if((int)$affiliate_verified){	//已经验证
	//提交申请成功后的页面处理{
	if((int)$_SESSION['affiliate_verification_successful']){
		$post_verification_successful = 1;
		unset($_SESSION['affiliate_verification_successful']);
		//热销行程推荐{
		$hotProducts = getAffiliateAllProducts(10);
		//热销行程推荐}
	}
	//}
	foreach($affiliateInfo as $key => $val){
		$affiliateInfo[$key] = tep_db_output($val);	//处理要显示的在html页面的数据
	}
}

//affiliate info }
$breadcrumb->add(NAVBAR_TITLE_2, tep_href_link('affiliate_my_info.php', '', 'SSL'));

$content = 'affiliate_my_info';
$validation_include_js = 'true';
$validation_div_span = 'span';
$is_my_account = true;
require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

require(DIR_FS_INCLUDES . 'application_bottom.php');
?>