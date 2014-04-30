<?php
/*
  $Id: specials.php,v 1.1.1.1 2004/03/04 23:38:58 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');
if($_GET['ajax']=="true"){
	include DIR_FS_CLASSES . 'Remark.class.php';
	$remark = new Remark('specials');
	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
}
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

//Howard更新团购产品所属期数{
function _update_issue_num($action='insert'){
	global $specials_id, $products_id, $start_date, $expires_date, $login_id, $specials_type;
	$confirmAdd = true;
	$start_date = date('Y-m-d',strtotime($start_date));
	$expires_date = date('Y-m-d',strtotime($expires_date));
	if((int)$_POST['issue_num']){
		$issue_num = (int)$_POST['issue_num'];
	}else{
		$chSql = tep_db_query('SELECT issue_num FROM `specials_group_buy_history` WHERE start_date="'.$start_date.'" and expires_date="'.$expires_date.'" ORDER BY `issue_num` DESC Limit 1');
		$chRow = tep_db_fetch_array($chSql);
		$issue_num = (int)$chRow['issue_num'];
		$issue_num_need_add = true;
		if($action=='update'){
			//每次同时修改“Start Date”和“Expiry Date”时，程序将自动视为增加新一期。
			$dateCheckSql = tep_db_query('SELECT start_date, expires_date FROM '.TABLE_SPECIALS.' WHERE specials_id="'.(int)$specials_id.'" ');
			$dateCheckRow = tep_db_fetch_array($dateCheckSql);
			$expires_date_old = substr($dateCheckRow['expires_date'],0,10);
			if($start_date == $start_date_old || $expires_date==$expires_date_old){
				$issue_num_need_add = false;
			}
		}
		
		if(!(int)$issue_num && $issue_num_need_add==true){
			$Sql = tep_db_query('SELECT issue_num FROM `specials_group_buy_history` WHERE 1 ORDER BY `issue_num` DESC Limit 1');
			$Row = tep_db_fetch_array($Sql);
			$issue_num = (int)$Row['issue_num']+1;
		}
	}
	//只有限时团有期数的概念，而限量团则无这个概念。默认都为1{
	if($specials_type!="2"){
		$issue_num = 1;
	}
	//}
	$addCheckSql = tep_db_query('SELECT specials_group_buy_history_id FROM `specials_group_buy_history` WHERE start_date="'.$start_date.'" and expires_date="'.$expires_date.'" and issue_num ="'.$issue_num.'" Limit 1');
	$addCheckRow = tep_db_fetch_array($addCheckSql);
	if((int)$addCheckRow['specials_group_buy_history_id']){ $confirmAdd = false; }
	if($confirmAdd==true){
		tep_db_query('INSERT INTO `specials_group_buy_history` (`specials_id` , `products_id` , `start_date` , `expires_date` , `issue_num`, `added_date`, `admin_id`,`specials_type` ) 
					  VALUES ("'.(int)$specials_id.'", "'.(int)$products_id.'", "'.$start_date.'", "'.$expires_date.'", "'.(int)$issue_num.'", "'.date("Y-m-d H:i:s").'","'.(int)$login_id.'","'.(int)$specials_type.'");');
		tep_db_query('OPTIMIZE TABLE specials_group_buy_history');
	}
}
//Howard更新团购产品所属期数}


  $error = false;

  $action = (isset($_POST['action']) ? $_POST['action'] : $_GET['action']);

  if (tep_not_null($action)) {
    switch ($action) {
      case 'SubMitConFigConfirm':	//设置首页显示的特价产品id
	   	if(!tep_not_null($_POST['configuration_value'])){
			$error = true;
			$messageStack->add(db_to_html('首页显示的特价产品不能为空！'), 'error');
		}elseif(!preg_match('/^(\d+\,{0,1})*(\d+)$/',trim($_POST['configuration_value']))){
			$error = true;
			$messageStack->add(db_to_html('首页显示的特价产品id必须为数字，多个id用英文的,号隔开例如：123,456,789'), 'error');
		}
		if($error == false){
			tep_db_query('update `configuration` set configuration_value ="'.tep_db_prepare_input(trim($_POST['configuration_value'])).'" where configuration_key="'.tep_db_prepare_input($_POST['configuration_key']).'" ');
			$messageStack->add_session(db_to_html('数据更新成功'), 'success');
			tep_redirect(tep_href_link(FILENAME_SPECIALS, tep_get_all_get_params(array('action', 'info', 'sID'))));
		}
	   break;
	  case 'setflag':	//设置特价产品的状态
        del_customers_basket_for_products(specials_id_to_products_id((int)$_GET['id']));
		tep_set_specials_status($_GET['id'], $_GET['flag']);

        tep_redirect(tep_href_link(FILENAME_SPECIALS, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . 'sID=' . $_GET['id'], 'NONSSL'));
        break;
      case 'insert':	//更新和添加特价产品
      case 'update':
		$products_id = tep_db_prepare_input($_POST['products_id']);
        del_customers_basket_for_products($products_id);
        
		$products_price = tep_db_prepare_input($_POST['products_price']);
        $specials_price = tep_db_prepare_input($_POST['specials_price']);
		$start_date = tep_db_prepare_input($_POST['start_date']);
		$expires_date = tep_db_prepare_input($_POST['expires_date']);
		$specials_type = (int)$_POST['specials_type'];
		$specials_max_buy_num = (int)$_POST['specials_max_buy_num'];
		if($specials_type!="1"){
			$specials_max_buy_num = 0;
		}
		$invite_info = tep_db_prepare_input(strip_tags($_POST['invite_info']));
		$remaining_num = tep_db_prepare_input(strip_tags($_POST['remaining_num']));

		$specials_new_products_single = tep_db_prepare_input($_POST['specials_new_products_single']);
		$specials_new_products_single_pu = tep_db_prepare_input($_POST['specials_new_products_single_pu']);
		$specials_new_products_double = tep_db_prepare_input($_POST['specials_new_products_double']);
		$specials_new_products_triple = tep_db_prepare_input($_POST['specials_new_products_triple']);
		$specials_new_products_quadr = tep_db_prepare_input($_POST['specials_new_products_quadr']);
		$specials_new_products_kids = tep_db_prepare_input($_POST['specials_new_products_kids']);
		$related_product_id = (int)$_POST['related_product_id'];

        if (substr($specials_price, -1) == '%') {
			$new_special_insert_query = tep_db_query("select products_id, products_price, products_single, products_single_pu, products_double, products_triple, products_quadr, products_kids from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
			$new_special_insert = tep_db_fetch_array($new_special_insert_query);
			
			$products_price = $new_special_insert['products_price'];
			$products_single = $new_special_insert['products_single'];
			$products_single_pu = $new_special_insert['products_single_pu'];
			$products_double = $new_special_insert['products_double'];
			$products_triple = $new_special_insert['products_triple'];
			$products_quadr = $new_special_insert['products_quadr'];
			$products_kids = $new_special_insert['products_kids'];
			
			$percentage = ($specials_price / 100);
			
			$specials_price = round($products_price - ( $percentage * $products_price));
			$specials_new_products_single = round($products_single - ($percentage * $products_single));
			$specials_new_products_single_pu = round($products_single_pu - ($percentage * $products_single_pu));
			$specials_new_products_double = round($products_double - ($percentage * $products_double));
			$specials_new_products_triple = round($products_triple - ($percentage * $products_triple));
			$specials_new_products_quadr = round($products_quadr - ($percentage * $products_quadr));
			$specials_new_products_kids = round($products_kids - ($percentage * $products_kids));
        }
		
		$check_query = tep_db_query("select display_room_option from " . TABLE_PRODUCTS . " WHERE products_id='".(int)$products_id."' ");
		$check_row = tep_db_fetch_array($check_query);
		if($check_row['display_room_option']!="1"){
			$specials_new_products_single_pu = $specials_new_products_double = $specials_new_products_triple = $specials_new_products_quadr = '';
		}
		
		$date_time = date("Y-m-d H:i:s");
		$_status = (date("Y-m-d H:i:s",strtotime($start_date)) <= $date_time) ? 1 : 0;
		
        $datas_array = array('products_id'=>(int)$products_id,
							'specials_new_products_price'=>$specials_price,
							'specials_date_added'=>$date_time,
							'specials_last_modified'=>$date_time,
							'start_date'=>$start_date,
							'expires_date'=>$expires_date,
							'specials_type'=>$specials_type,
							'status'=>$_status,
							'specials_new_products_single'=>$specials_new_products_single,
							'specials_new_products_single_pu'=>$specials_new_products_single_pu,
							'specials_new_products_double'=>$specials_new_products_double,
							'specials_new_products_triple'=>$specials_new_products_triple,
							'specials_new_products_quadr'=>$specials_new_products_quadr,
							'specials_new_products_kids'=>$specials_new_products_kids,
							'specials_max_buy_num'=>$specials_max_buy_num,
							'invite_info'=>$invite_info,
							'related_product_id'=>$related_product_id,
							'remaining_num'=>$remaining_num
							);
		if($action=="insert"){
			tep_db_query('DELETE FROM '.TABLE_SPECIALS.' WHERE products_id="'.(int)$products_id.'" '); //删除旧的相同ID的特价产品
			tep_db_perform(TABLE_SPECIALS, $datas_array); //增加
			$specials_id = tep_db_insert_id();
			_update_issue_num($action); //Howard更新团购产品所属期数
			tep_db_query('OPTIMIZE TABLE '.TABLE_SPECIALS);
			
			tep_redirect(tep_href_link(FILENAME_SPECIALS, 'page=' . $_GET['page']));
		}elseif($action=="update"){
			unset($datas_array['specials_date_added']);
			$specials_id = tep_db_prepare_input($_POST['specials_id']);
			_update_issue_num($action); //Howard更新团购产品所属期数
			
			tep_db_perform(TABLE_SPECIALS, $datas_array, 'update', ' specials_id='.(int)$specials_id );	//更新
			tep_db_query('OPTIMIZE TABLE '.TABLE_SPECIALS);
			
			tep_redirect(tep_href_link(FILENAME_SPECIALS, 'page=' . $_GET['page'] . '&sID=' . $specials_id));
		}else{ die('Will you insert or update?'); }
        
		
		break;
      case 'deleteconfirm':	//删除特价产品
        $specials_id = tep_db_prepare_input($_GET['sID']);
		del_customers_basket_for_products(specials_id_to_products_id((int)$specials_id));

        tep_db_query("delete from " . TABLE_SPECIALS . " where specials_id = '" . (int)$specials_id . "'");
		tep_db_query('OPTIMIZE TABLE '.TABLE_SPECIALS);

        tep_redirect(tep_href_link(FILENAME_SPECIALS, 'page=' . $_GET['page']));
        break;
    }
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery.js"></script>
<script type="text/javascript">
//表单检查
function check_form(){
	var F = document.getElementById('new_special');
	if(F.elements['specials_type'][1].checked == true || F.elements['specials_type'][2].checked == true){
		if(F.elements['start_date'].value.length <10 || F.elements['expires_date'].value.length <10 ){
			alert('<?= JS_ERROR_MSN?>');
			return false;
		}
		if(F.elements['specials_type'][1].checked == true && F.elements['specials_max_buy_num'].value<1){
			alert('<?= JS_ERROR_MSN1?>');
			return false;
		}
	}
	
	F.submit();
}
</script>
<?php
  if ( ($action == 'new') || ($action == 'edit') ) {
?>
<link rel="stylesheet" type="text/css" href="includes/javascript/calendar.css">
<script language="JavaScript" src="includes/javascript/calendarcode.js"></script>

<?php
  }
?>

<style type="text/css">
<!--
.main {
	padding:10px;
}
.dataTableContent {
	padding:5px;
}
-->
</style>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
<div id="popupcalendar" class="text"></div>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">
			<form action="<?php echo tep_href_link(FILENAME_SPECIALS);?>" method="post" enctype="multipart/form-data" name="SetConFigFrom" id="SetConFigFrom">
			<?php echo HEADING_TITLE; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<span class="main">
			<?php
			$con_sql = tep_db_query('SELECT configuration_key, configuration_value FROM `configuration` WHERE configuration_key ="TOURS_HOMEPAGE_SPECIAL_OFFERS" ');
			$con_row = tep_db_fetch_array($con_sql);
			$conInfo = new objectInfo($con_row);
			$configuration_value = $conInfo -> configuration_value;
			$configuration_key = $conInfo -> configuration_key;
			
			echo db_to_html('首页显示的特价团ID：');
			echo tep_draw_input_num_en_field('configuration_value','',' size="80" style="ime-mode:disabled"');
			echo tep_draw_hidden_field('configuration_key');
			echo tep_draw_hidden_field('action','SubMitConFigConfirm');
			
			?>
			<input name="SubMitConFig" type="submit" id="SubMitConFig" value="Submit">
			</span>
			</form>
			</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
<?php
  if ( ($action == 'new') || ($action == 'edit') ) {
    $form_action = 'insert';
    if ( ($action == 'edit') && isset($_GET['sID']) ) {
      $form_action = 'update';
	  
	 

      $product_query = tep_db_query("select p.products_id, p.display_room_option, pd.products_name, p.products_price, s.* from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SPECIALS . " s where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = s.products_id and s.specials_id = '" . (int)$_GET['sID'] . "'");
      $product = tep_db_fetch_array($product_query);
	  
	  
	    $tour_agency_opr_currency = tep_get_tour_agency_operate_currency($product['products_id']);		
		if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
		$display_tour_agency_opr_currency_note = '<span class="errorText">'.$tour_agency_opr_currency.':</span>';
		}else{
		$display_tour_agency_opr_currency_note = '';
		}


      $sInfo = new objectInfo($product);
    } else {
      $sInfo = new objectInfo(array());

// create an array of products on special, which will be excluded from the pull down menu of products
// (when creating a new product on special)
      $specials_array = array();
      $specials_query = tep_db_query("select p.products_id from " . TABLE_PRODUCTS . " p, " . TABLE_SPECIALS . " s where s.products_id = p.products_id");
      while ($specials = tep_db_fetch_array($specials_query)) {
        $specials_array[] = $specials['products_id'];
      }
    }
?>
      <form name="new_special" id="new_special" <?php echo 'action="' . tep_href_link(FILENAME_SPECIALS, tep_get_all_get_params(array('action', 'info', 'sID')) . 'action=' . $form_action, 'NONSSL') . '"'; ?> method="post" onSubmit="check_form(); return false;"><?php if ($form_action == 'update'){ echo tep_draw_hidden_field('specials_id', $_GET['sID']); echo tep_draw_hidden_field('products_id', $sInfo->products_id); } ?><tr>
        <td><br><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo TEXT_SPECIALS_PRODUCT; ?>&nbsp;</td>
            <td class="main">
			<?php 
			echo (isset($sInfo->products_name))
			? 
			$sInfo->products_name . ' <small>(' .$display_tour_agency_opr_currency_note. $currencies->format($sInfo->products_price) . ')</small>' 
			: 
			'Input id: '.tep_draw_input_field('products_id','',' size="10" onChange="new_special.products_id_array.value=this.value"').' Or Select: '.
			tep_draw_products_pull_down('products_id_array', 'style="font-size:12px" onChange="new_special.products_id.value=this.value" ', $specials_array); 
			
			echo tep_draw_hidden_field('products_price', (isset($sInfo->products_price) ? $sInfo->products_price : ''));
			?>
			</td>
          </tr>
          <tr>
            <td class="main">Related Product ID:&nbsp;</td>
            <td class="main"><?php echo tep_draw_input_field('related_product_id',$sInfo->related_product_id);?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_SPECIALS_SPECIAL_PRICE; ?>&nbsp;</td>
            <td class="main"><?php echo $display_tour_agency_opr_currency_note.tep_draw_input_field('specials_price', (isset($sInfo->specials_new_products_price) ? $sInfo->specials_new_products_price : '')); ?></td>
          </tr>
		  
		  <tr>
		  <td class="main">Price Details:&nbsp;</td>
		  <td class="main">
			<?php
			//特价明细
			if (!isset($sInfo->display_room_option)) $sInfo->display_room_option = '1';
			if($sInfo->display_room_option=="1"){//有房间
				echo 'Single Price:'.tep_draw_input_field('specials_new_products_single', $sInfo->specials_new_products_single, 'size="7"')."&nbsp;";
				echo 'Single Up Price:'.tep_draw_input_field('specials_new_products_single_pu', $sInfo->specials_new_products_single_pu, 'size="7"')."&nbsp;";
				echo 'Double Price:'.tep_draw_input_field('specials_new_products_double', $sInfo->specials_new_products_double, 'size="7"')."&nbsp;";
				echo 'Triple Price:'.tep_draw_input_field('specials_new_products_triple', $sInfo->specials_new_products_triple, 'size="7"')."&nbsp;";
				echo 'Quadruple Price:'.tep_draw_input_field('specials_new_products_quadr', $sInfo->specials_new_products_quadr, 'size="7"')."&nbsp;";
				echo 'Kids Price:'.tep_draw_input_field('specials_new_products_kids', $sInfo->specials_new_products_kids, 'size="7"')."&nbsp;";
			}else{//无房间
				echo 'Adult Price:'.tep_draw_input_field('specials_new_products_single', $sInfo->specials_new_products_single, 'size="7"')."&nbsp;";
				echo 'Child Price:'.tep_draw_input_field('specials_new_products_kids', $sInfo->specials_new_products_kids, 'size="7"')."&nbsp;";
			}
			?>
		  </td>
		  </tr>
          
		  <tr>
		  <td class="main">Start Date:</td>
		  <td class="main">
		  <?php
		  $sInfo->start_date = trim(substr($sInfo->start_date,0,10));
		  if($sInfo->start_date=="0000-00-00"){ $sInfo->start_date=""; }
		  echo tep_draw_input_field('start_date', $sInfo->start_date, 'size="7" style="ime-mode: disabled;" class="textTime" onClick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" ');
		  ?>
		  </td>
		  </tr>
		  <tr>
            <td class="main"><?php echo TEXT_SPECIALS_EXPIRES_DATE; ?>&nbsp;</td>
            <td class="main">
		  <?php
		  $sInfo->expires_date = trim(substr($sInfo->expires_date,0,10));
		  if($sInfo->expires_date=="0000-00-00"){ $sInfo->expires_date=""; }
		  echo tep_draw_input_field('expires_date', $sInfo->expires_date, 'size="7" style="ime-mode: disabled;" class="textTime" onClick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" ');
		  ?>
			</td>
          </tr>
		  <tr>
		  <td class="main"><?= ISSUE_NUM;?></td>
		  <td class="main" title="<?= ISSUE_NUM_TIPS?>">
		  <div style="color:#999"><b>注意：如果您想生成新一期的团购请选择“由系统自动分期”，同时您需要选择新的开始日期(Start Date)和结束日期(Expiry Date)</b></div>
		  <?php
		  $issueSql = tep_db_query('SELECT issue_num FROM `specials_group_buy_history` WHERE 1 ORDER BY `issue_num` DESC limit 1');
		  $issueRow = tep_db_fetch_array($issueSql);
		  
		  //取得当前团购产品的其数（Start Date、Expiry Date、specials_id相同）
		  $issue_num = "";
		  $issueNowSql = tep_db_query('SELECT issue_num FROM `specials_group_buy_history` WHERE 1 and specials_id="'.(int)$_GET['sID'].'" and start_date="'.date("Y-m-d",strtotime($sInfo->start_date)).'" and expires_date="'.date("Y-m-d",strtotime($sInfo->expires_date)).'" ORDER BY `added_date` DESC, `issue_num` DESC ');
		  $tmpLoop=0;
		  while($issueNowRows=tep_db_fetch_array($issueNowSql)){
		  	$tmpLoop++;
			$issue_num = $issueNowRows['issue_num'];
		  }
		  if($tmpLoop>1){ echo '<div class="errorText">specials_group_buy_history表数据有问题，请通知开发人员检查！</div>';}
		  
		  for($i=1; $i<=(int)$issueRow['issue_num']; $i++){
		  	echo '<div style="float:left; width:80px"><label>'.tep_draw_radio_field('issue_num',$i).' 第'.$i.'期</label></div>';
		  }
		  echo '<div style="float:left; width:120px"><label>'.tep_draw_radio_field('issue_num','').' 由系统自动分期</label></div>';
		  ?>
		  <div style="clear:both"></div>
		  </td>
		  </tr>
		  <tr>
		  <td class="main">Special Type:&nbsp;</td>
		  <td class="main">
		  <?php
		  $specials_type = "0";
		  if(tep_not_null($sInfo->specials_type)){ $specials_type = $sInfo->specials_type; }
		  echo '<label title="'.SPECIALS_TITLE_TYPE0.'">'.tep_draw_radio_field('specials_type','0').' '.SPECIALS_TYPE0.'</label> &nbsp;&nbsp;';
		  echo '<label title="'.SPECIALS_TITLE_TYPE1.'">'.tep_draw_radio_field('specials_type','1').' '.SPECIALS_TYPE1.'</label> &nbsp;&nbsp;';
		  echo '<label title="'.SPECIALS_TITLE_TYPE2.'">'.tep_draw_radio_field('specials_type','2').' '.SPECIALS_TYPE2.'</label> &nbsp;&nbsp;';
		  
		  echo INVITE_INFO.tep_draw_textarea_field('invite_info','','100','2',$sInfo->invite_info);
		  ?>
		  
		  </td>
		  </tr>
		  <tr>
		  <td class="main"><?= MAX_BUY_NUM;?></td>
		  <td class="main"><?= tep_draw_input_field('specials_max_buy_num', $sInfo->specials_max_buy_num, 'size="7"');?></td>
		  </tr>
		  <tr>
		  <td class="main"><?= REMAINING_NUM;?></td>
		  <td class="main"><?= tep_draw_input_field('remaining_num', $sInfo->remaining_num, 'size="7"');?> <b>注意：0代表剩余0个座位。如果不想设置此值请保留为空即可。此字段只显示在前台，不参加程序运算</b></td>
		  </tr>
		  
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><br><?php echo TEXT_SPECIALS_PRICE_TIP; ?></td>
            <td class="main" align="right" valign="top"><br><?php echo (($form_action == 'insert') ? tep_image_submit('button_insert.gif', IMAGE_INSERT) : tep_image_submit('button_update.gif', IMAGE_UPDATE)). '&nbsp;&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_SPECIALS, 'page=' . $_GET['page'] . (isset($_GET['sID']) ? '&sID=' . $_GET['sID'] : '')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
          </tr>
        </table></td>
      </tr></form>
<?php
  } else {
?>
      <tr>
        <td>
		
		<?php
		//echo $login_id;
		include DIR_FS_CLASSES . 'Remark.class.php';
		$listrs = new Remark('specials');
		$list = $listrs->showRemark();
		?>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRODUCTS_PRICE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $specials_query_raw = "select ta.operate_currency_code, p.products_id, p.products_model, pd.products_name, p.products_price, s.* from " . TABLE_PRODUCTS . " p, " . TABLE_SPECIALS . " s, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_TRAVEL_AGENCY . " ta where p.agency_id = ta.agency_id and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = s.products_id order by s.specials_type ASC , pd.products_name";
    $max_show_rows = 100;
	$specials_split = new splitPageResults($_GET['page'], $max_show_rows, $specials_query_raw, $specials_query_numrows);
    $specials_query = tep_db_query($specials_query_raw);
    while ($specials = tep_db_fetch_array($specials_query)) {
	
	if($specials['operate_currency_code'] != 'USD' && $specials['operate_currency_code'] != ''){
	 $specials['products_price'] = tep_get_tour_price_in_usd($specials['products_price'],$specials['operate_currency_code']);
	 $specials['specials_new_products_price'] = tep_get_tour_price_in_usd($specials['specials_new_products_price'],$specials['operate_currency_code']);
	}
	
      if ((!isset($_GET['sID']) || (isset($_GET['sID']) && ($_GET['sID'] == $specials['specials_id']))) && !isset($sInfo)) {
        $products_query = tep_db_query("select products_image from " . TABLE_PRODUCTS . " where products_id = '" . (int)$specials['products_id'] . "'");
        $products = tep_db_fetch_array($products_query);
        $sInfo_array = array_merge($specials, $products);
        $sInfo = new objectInfo($sInfo_array);
      }

      if (isset($sInfo) && is_object($sInfo) && ($specials['specials_id'] == $sInfo->specials_id)) {
        echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_SPECIALS, 'page=' . $_GET['page'] . '&sID=' . $sInfo->specials_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '                  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_SPECIALS, 'page=' . $_GET['page'] . '&sID=' . $specials['specials_id']) . '\'">' . "\n";
      }
?>
                <td  class="dataTableContent">
				<?php
				if((int)$specials['specials_type']==1){
					echo '<span class="col_red_b">['.SPECIALS_TYPE1.']</span>';
				}
				if((int)$specials['specials_type']==2){
					echo '<span class="col_red">['.SPECIALS_TYPE2.']</span>';
				}
				?>
				<?php echo '<a target="_blank" href="' . tep_catalog_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $specials['products_id']) . '">'.$specials['products_name']. '</a><a target="_blank" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . tep_get_products_catagory_id($specials['products_id']) . '&pID=' . $specials['products_id'].'&action=new_product&tabedit=room_and_price') . '"> ['.$specials['products_model'].']</a>'; ?> </td>
                <td  class="dataTableContent" align="right"><span class="oldPrice"><?php echo $currencies->format($specials['products_price']); ?></span> <span class="specialPrice"><?php echo $currencies->format($specials['specials_new_products_price']); ?></span></td>
                <td  class="dataTableContent" align="right">
<?php
      if ($specials['status'] == '1') {
        echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10); /*. '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_SPECIALS, 'action=setflag&flag=0&id=' . $specials['specials_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';*/
      } else {
        echo /*'<a href="' . tep_href_link(FILENAME_SPECIALS, 'action=setflag&flag=1&id=' . $specials['specials_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . */tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
      }
?></td>
                <td class="dataTableContent" align="right"><?php if (isset($sInfo) && is_object($sInfo) && ($specials['specials_id'] == $sInfo->specials_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_SPECIALS, 'page=' . $_GET['page'] . '&sID=' . $specials['specials_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
      </tr>
<?php
    }
?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellpadding="0"cellspacing="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $specials_split->display_count($specials_query_numrows, $max_show_rows, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_SPECIALS); ?></td>
                    <td class="smallText" align="right"><?php echo $specials_split->display_links($specials_query_numrows, $max_show_rows, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
<?php
  if (empty($action)) {
?>
                  <tr>
                    <td colspan="2" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_SPECIALS, 'page=' . $_GET['page'] . '&action=new') . '">' . tep_image_button('button_new_product.gif', IMAGE_NEW_PRODUCT) . '</a>'; ?></td>
                  </tr>
<?php
  }
?>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_SPECIALS . '</b>');

      $contents = array('form' => tep_draw_form('specials', FILENAME_SPECIALS, 'page=' . $_GET['page'] . '&sID=' . $sInfo->specials_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $sInfo->products_name . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . '&nbsp;<a href="' . tep_href_link(FILENAME_SPECIALS, 'page=' . $_GET['page'] . '&sID=' . $sInfo->specials_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (is_object($sInfo)) {
        $heading[] = array('text' => '<b>' . $sInfo->products_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_SPECIALS, 'page=' . $_GET['page'] . '&sID=' . $sInfo->specials_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_SPECIALS, 'page=' . $_GET['page'] . '&sID=' . $sInfo->specials_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_DATE_ADDED . ' ' . tep_date_short($sInfo->specials_date_added));
        $contents[] = array('text' => '' . TEXT_INFO_LAST_MODIFIED . ' ' . tep_date_short($sInfo->specials_last_modified));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_info_image($sInfo->products_image, $sInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT));
        $contents[] = array('text' => '<br>' . TEXT_INFO_ORIGINAL_PRICE . ' ' . $currencies->format($sInfo->products_price));
        $contents[] = array('text' => '' . TEXT_INFO_NEW_PRICE . ' ' . $currencies->format($sInfo->specials_new_products_price));
        $contents[] = array('text' => '' . TEXT_INFO_PERCENTAGE . ' ' . number_format(100 - (($sInfo->specials_new_products_price / max(1,$sInfo->products_price)) * 100)) . '%');

        $contents[] = array('text' => '<br>' . TEXT_INFO_EXPIRES_DATE . ' <b>' . tep_date_short($sInfo->expires_date) . '</b>');
        $contents[] = array('text' => '' . TEXT_INFO_STATUS_CHANGE . ' ' . tep_date_short($sInfo->date_status_change));
      }
      break;
  }
  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
}
?>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
