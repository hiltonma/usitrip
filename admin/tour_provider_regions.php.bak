<?php
/*
$Id: tour_providers.php,v 1.1.1.1 2004/03/04 23:38:44 ccwjr Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2003 osCommerce

Released under the GNU General Public License
*/

require('includes/application_top.php');
// 备注添加删除
if($_GET['ajax']=="true"){
	include DIR_FS_CLASSES . 'Remark.class.php';
	$remark = new Remark('tour_provider_regions');
	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
}

require_once('includes/classes/tour_provider_regions.php');
$tour_provider_regions = new tour_provider_regions;

$action = (isset($_GET['action']) ? $_GET['action'] : '');

// 如果是产品经理 则有权进行一键更新操作
if ($can_one_update == true) {
	if ($action == 'oneupdate') {
		$lwk_id = (int)$_GET['mID'];
		$rs_lwk = tep_db_query("select * from tour_provider_regions where tour_provider_regions_id='" . $lwk_id . "'");
		$rs_lwk = tep_db_fetch_array($rs_lwk);
		if ($rs_lwk) {
			$data = array();
			$data['departure_region'] = $rs_lwk['region'];
			$data['departure_address'] = $rs_lwk['address'];
			$data['departure_time'] = $rs_lwk['departure_time'];
			$data['map_path'] = $rs_lwk['map_path'];
			$data['departure_full_address'] = $rs_lwk['full_address'];
			$data['departure_tips'] = $rs_lwk['departure_tips'];
			$data['products_hotels_ids'] = $rs_lwk['products_hotels_ids'];
			tep_db_perform('products_departure',$data,'update'," tour_provider_regions_id='" . $lwk_id . "' ");
			$messageStack->add_session('批量更新成功！', 'success');
			header("Location:" . tep_href_link(FILENAME_TOUR_PROVIDER_REGIONS,tep_get_all_get_params(array('action'))));
			exit;
		}
	}
	
}
$agency_array = array(array('id' => '', 'text' => TEXT_AGENCY));
$agency_query = tep_db_query("select agency_id, agency_name from " . TABLE_TRAVEL_AGENCY . " order by agency_name");
while ($agency_result = tep_db_fetch_array($agency_query)){
	$agency_array[] = array('id' => $agency_result['agency_id'], 'text' => $agency_result['agency_name']);
}

if (tep_not_null($action)) {
	if($tour_provider_regions_permissions['能看不能编'] != true){
		switch ($action) {
			case 'insert':
			case 'save':
				if($action=='insert'){
					$tour_provider_regions_id = $tour_provider_regions->insert($_POST);
				}elseif ($action=='save') {
					$tour_provider_regions_id = $tour_provider_regions->update($_POST);
				}			
				tep_redirect(tep_href_link(FILENAME_TOUR_PROVIDER_REGIONS, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . 'mID=' . $tour_provider_regions_id .(isset($_GET['aid']) ? '&aid=' . $_GET['aid'] . '' : '')));
				break;
			
			case 'deleteconfirm':
				$tour_provider_regions_id  = tep_db_prepare_input($_GET['mID']);
				$tour_provider_regions->delete((int)$tour_provider_regions_id);
				tep_redirect(tep_href_link(FILENAME_TOUR_PROVIDER_REGIONS, 'page=' . $_GET['page'].(isset($_GET['aid']) ? '&aid=' . $_GET['aid'] . '' : '')));
				break;
		}
	}
}
$tour_providers_query_raw = "select tpr.*,ta.agency_name from " . TABLE_TOUR_PROVIDER_REGIONS . " as tpr, " . TABLE_TRAVEL_AGENCY . " as ta where FIND_IN_SET(ta.agency_id, tpr.agency_ids) ";
$tour_providers_where='';
// foreach($_GET as $key=>$value){
// 	if (! get_magic_quotes_gpc()) 		// 判断magic_quotes_gpc是否为打开
// 		{
// 			$_GET[$key] = addslashes($value); // 进行magic_quotes_gpc没有打开的情况对提交数据的过滤
// 		}
// 		$_GET[$key] = str_replace("_", "\_", $_GET[$key]); // 把 '_'过滤掉
// 		$_GET[$key] = str_replace("%", "\%", $_GET[$key]); // 把' % '过滤掉
// 		$_GET[$key] = nl2br($_GET[$key]); // 回车转换
// 		$_GET[$key] = htmlspecialchars($_GET[$key]);
// }
if(isset($_GET['aid']) && $_GET['aid'] != ''){
	$tour_providers_where=' and ta.agency_id ='.(int)$_GET['aid'];
}
if(isset($_GET['s_regions'])&&$_GET['s_regions']!=''){
	$s_regions=$_GET['s_regions'];
	$tour_providers_where.=' and tpr.region like "%'.tep_db_prepare_input($s_regions).'%"';
}
if(isset($_GET['s_departure_time'])&&$_GET['s_departure_time']){
	$s_departure_time=$_GET['s_departure_time'];
	$tour_providers_where.=' and tpr.departure_time like "%'.tep_db_prepare_input($s_departure_time).'%"';
}
if(isset($_GET['s_location'])&&$_GET['s_location']){
	$s_location=$_GET['s_location'];
	$tour_providers_where.=' and tpr.address like "%'.tep_db_prepare_input($s_location).'%"';
}
$tour_providers_query_raw.=$tour_providers_where.' order by  ta.agency_name , tpr.departure_time ';
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
		<?php
		//echo $login_id;
		include DIR_FS_CLASSES . 'Remark.class.php';
		$listrs = new Remark('tour_provider_regions');
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
			 <td align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr><?php echo tep_draw_form('agency', FILENAME_TOUR_PROVIDER_REGIONS, '', 'get'); ?>
			  <td>Regions:</td>
			  <td><input type="text" name="s_regions" value="<?=$s_regions?>" /></td>
			  <td>Location:</td>
			  <td><input type="text" name="s_location" value="<?=$s_location?>" /></td>
			  <td>Departure Time:</td>
			  <td><input type="text" name="s_departure_time" value="<?=$s_departure_time?>" /></td>
                <td class="smallText" ><?php echo HEADING_TITLE_SEARCH . ' ' . tep_draw_pull_down_menu('aid', $agency_array, $_GET['aid'], '');//onChange="this.form.submit();" ?></td>
				<td><input type="submit" value="查询" /></td>
				<td><a href="/admin/tour_provider_regions.php" ><input type="button" value="清空搜索结果" ></a></td>
              </form></tr>
             </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
		<?php
		if($tour_provider_regions_permissions['能看不能编'] == true) {
			ob_start();
		}
		?>
		<table id="TableList" border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <th class="dataTableHeadingContent"><?php echo TABLE_HEADING_MANUFACTURERS;?></th>
				<th class="dataTableHeadingContent"><?php echo TABLE_HEADING_REGIONS;?></th>
				<th class="dataTableHeadingContent"><?php echo TABLE_HEADING_REGIONS_HOTEL;?></th>
				<th class="dataTableHeadingContent"><?php echo TABLE_HEADING_REGIONS_TIME;?></th>
                <th class="dataTableHeadingContent"><?php echo TABLE_HEADING_ACTION;?>&nbsp;</th>
              </tr>
				<?php
					$counterstart = 0;
// 					$tour_providers_query_raw = "select tpr.*,ta.agency_name from " . TABLE_TOUR_PROVIDER_REGIONS . " as tpr, " . TABLE_TRAVEL_AGENCY . " as ta where FIND_IN_SET(ta.agency_id, tpr.agency_ids) ";
// 					if(isset($_GET['aid']) && $_GET['aid'] != ''){
// 						$tour_providers_where=' and ta.agency_id ='.$_GET['aid'];
// // 						$tour_providers_query_raw = "select tpr.*,ta.agency_name from " . TABLE_TOUR_PROVIDER_REGIONS . " as tpr, " . TABLE_TRAVEL_AGENCY . " as ta where FIND_IN_SET(ta.agency_id, tpr.agency_ids) and ta.agency_id ='".$_GET['aid']."' order by  ta.agency_name , tpr.departure_time ";  //tpr.tour_provider_regions_id
// 					}
// 					else{
// 						$tour_providers_query_raw = "select tpr.*,ta.agency_name from " . TABLE_TOUR_PROVIDER_REGIONS . " as tpr, " . TABLE_TRAVEL_AGENCY . " as ta where FIND_IN_SET(ta.agency_id, tpr.agency_ids) order by ta.agency_name, tpr.departure_time "; //tpr.tour_provider_regions_id
// 					}
					$tour_providers_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $tour_providers_query_raw,$tour_providers_query_numrows);
					$tour_providers_query = tep_db_query($tour_providers_query_raw);
					while ($tour_providers = tep_db_fetch_array($tour_providers_query)) {

						if (isset($_GET['mID']) && $_GET['mID'] == $tour_providers['tour_provider_regions_id'])
						{
							if($counterstart==0)
							{
								$agency_regions_query = tep_db_query("select tpr.*,ta.agency_name from " . TABLE_TOUR_PROVIDER_REGIONS . " as tpr, " . TABLE_TRAVEL_AGENCY . " as ta where FIND_IN_SET(ta.agency_id, tpr.agency_ids) and tpr.tour_provider_regions_id  = '" . (int)$tour_providers['tour_provider_regions_id'] . "' order by tpr.tour_provider_regions_id ");
								$agency_regions = tep_db_fetch_array($agency_regions_query);
								$counterstart++;
							}
	}
	elseif (!isset($_GET['mID']))
	{
		if($counterstart==0)
		{
			$agency_regions_query = tep_db_query("select tpr.*,ta.agency_name from " . TABLE_TOUR_PROVIDER_REGIONS . " as tpr, " . TABLE_TRAVEL_AGENCY . " as ta where FIND_IN_SET(ta.agency_id, tpr.agency_ids) and tpr.tour_provider_regions_id  = '" . (int)$tour_providers['tour_provider_regions_id'] . "' order by tpr.tour_provider_regions_id ");
			$agency_regions = tep_db_fetch_array($agency_regions_query);
			$counterstart++;
		}

	}

	if ($tour_providers['tour_provider_regions_id'] == $agency_regions['tour_provider_regions_id']){
		echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_TOUR_PROVIDER_REGIONS, 'page=' . $_GET['page'] . '&mID=' . $tour_providers['tour_provider_regions_id'] .(isset($_GET['aid']) ? '&aid=' . $_GET['aid'] . '' : ''). '&action=edit') . '\'">' . "\n";
	} else {
		echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_TOUR_PROVIDER_REGIONS, 'page=' . $_GET['page'] . '&mID=' . $tour_providers['tour_provider_regions_id']) .(isset($_GET['aid']) ? '&aid=' . $_GET['aid'] . '' : ''). '\'">' . "\n";
	}
?>
                <td class="dataTableContent"><?php echo $tour_providers['agency_name']; ?></td>
				<td class="dataTableContent"><?php echo $tour_providers['region']; ?></td>
				<td class="dataTableContent"><?php echo $tour_providers['address']; ?></td>
				<td class="dataTableContent"><?php echo $tour_providers['departure_time']; ?></td>
                <td class="dataTableContent" align="right"><?php if ($tour_providers['tour_provider_regions_id'] == $agency_regions['tour_provider_regions_id']) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . tep_href_link(FILENAME_TOUR_PROVIDER_REGIONS, 'page=' . $_GET['page'] . '&mID=' . $tour_providers['tour_provider_regions_id']).(isset($_GET['aid']) ? '&aid=' . $_GET['aid'] . '' : '') . '   ">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;
				
				<?php
				echo '<a href="' . tep_href_link(FILENAME_TOUR_PROVIDER_REGIONS, tep_get_all_get_params(array('mID', 'action')) . 'mID=' . $tour_providers['tour_provider_regions_id']) . '&action=edit">' . tep_image('images/icons/edit.gif', '编辑') . '</a>&nbsp;';
				if ($can_one_update === true) {
				?>
				<a class="btn_lwk" href="javascript:void(0)" onClick="oneupdate('<?php echo tep_href_link(FILENAME_TOUR_PROVIDER_REGIONS,tep_get_all_get_params(array('mID', 'action')) . 'mID=' . $tour_providers['tour_provider_regions_id']) . '&action=oneupdate';?>',event)">一键更新相关产品</a>
				<?php 
				}?>
				</td>
              </tr>
<?php
}
?>
              <tr>
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $tour_providers_split->display_count($tour_providers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_MANUFACTURERS); ?></td>
                    <td class="smallText" align="right"><?php echo $tour_providers_split->display_links($tour_providers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $_GET['page'],tep_get_all_get_params(array('page', 'action', 'info', 'x', 'y', 'mID'))); ?></td>
                  </tr>
                </table></td>
              </tr>
<?php
if (empty($action)) {
?>
              <tr>
                <td align="right" colspan="5" class="smallText"><?php echo '<a href="' . tep_href_link(FILENAME_TOUR_PROVIDER_REGIONS, tep_get_all_get_params(array('page', 'info', 'x', 'y', 'action','mID')).'page=' . $_GET['page'] . '&mID=' . $tour_providers['tour_provider_regions_id'] . '&action=new') . '">' . tep_image_button('button_insert.gif', IMAGE_INSERT) . '</a>'; ?></td>
              </tr>
<?php
}
?>
            </table></td>
<?php
$heading = array();
$contents = array();

if(isset($_GET['ag_id']) && $_GET['ag_id'] != ''){
	$agency_regions['agency_id'] = $_GET['ag_id'];
}
$region_array = array(array('id' => '', 'text' => TEXT_REGION_SELECTION));

if(isset($agency_regions['agency_id']) && $agency_regions['agency_id'] != ''){
	$region_query = tep_db_query("select region from " . TABLE_TOUR_PROVIDER_REGIONS . " where FIND_IN_SET('".$agency_regions['agency_id']."', agency_ids) group by region order by region");
}else{
	$region_query = tep_db_query("select DISTINCT region from " . TABLE_TOUR_PROVIDER_REGIONS . " ");
}
while ($region_result = tep_db_fetch_array($region_query)){
	if(tep_not_null($region_result['region'])){
		$region_array[] = array('id' => $region_result['region'],'text' => $region_result['region']);
	}
}



switch ($action) {
	case 'new':
		$heading[] = array('text' => '<b>' . TEXT_HEADING_NEW_MANUFACTURER . '</b>');

		$contents = array('form' => tep_draw_form('tour_providers', FILENAME_TOUR_PROVIDER_REGIONS, 'action=insert', 'post', 'enctype="multipart/form-data" onSubmit="return validation()"'));
		$contents[] = array('text' => TEXT_NEW_INTRO);
		//$contents[] = array('text' => '<br>' . TEXT_AGENCY_NAME . '<br>' . tep_draw_pull_down_menu('agency_ids', $agency_array, $_GET['ag_id'], 'onChange="onselectagency(this.form.agency_ids.value);"'));
		$selected_vals[] = array();
		if(tep_not_null($_GET['ag_id'])){
			$selected_vals[] = array('id'=>$_GET['ag_id']);
		}
		$contents[] = array('text' => '<br>' . TEXT_AGENCY_NAME . '<br>' . tep_draw_mselect_menu('agency_ids[]', $agency_array, $selected_vals, ' size="10" '));
		$contents[] = array('text' => '<br>' . TEXT_REGION_NAME . '<br>' . tep_draw_pull_down_menu('region_combo', $region_array, '', '') .' <br>OR (insert)<br> '. tep_draw_input_field('region'));
		$contents[] = array('text' => '<br>' . TEXT_HOTEL . '<br>' . tep_draw_input_field('address'));
		$contents[] = array('text' => '<br>' . TEXT_ADDRESS . '<br>' . tep_draw_input_field('full_address','','size=50'));
		$contents[] = array('text' => '<br>' . TEXT_TIME . ' (HH:MMam e.g:- 9:00am)<br>' . tep_draw_input_field('departure_time'));
		$contents[] = array('text' => '<br>' . TEXT_MAP_PATH . '<br>' . tep_draw_input_field('map_path'));
		$contents[] = array('text' => '<br>' . '附近酒店ID(多个ID用,号分隔)' . '<br>' . tep_draw_input_num_en_field('products_hotels_ids'));
		$contents[] = array('text' => '<br>' . '提示消息' . '<br>' . tep_draw_textarea_field('departure_tips','wrap',20,5));



		$contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_TOUR_PROVIDER_REGIONS, tep_get_all_get_params(array('page', 'info', 'x', 'y', 'mID','action')).'page=' . $_GET['page'] . '&mID=' . $_GET['mID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
		break;
	case 'edit':
		$heading[] = array('text' => '<b>' . TEXT_HEADING_EDIT_MANUFACTURER . '</b>');
		$contents = array('form' => tep_draw_form('tour_providers', FILENAME_TOUR_PROVIDER_REGIONS, 'action=save&'.(isset($_GET['aid']) ? '&aid=' . $_GET['aid'] . '' : '').(isset($_GET['page']) ? '&page=' . $_GET['page'] . '' : '').'', 'post', 'enctype="multipart/form-data" onSubmit="return validation()"'));
		//$contents[] = array('text' => '<br>' . TEXT_AGENCY_NAME . '<br>' . tep_draw_pull_down_menu('agency_ids', $agency_array, $agency_regions['agency_id'], 'onChange="onselectagency(this.form.agency_ids.value);"'));
		//$selected_vals[] = array('id'=>202);
		$selected_vals[] = array();
		$selected_vals[] = array('id'=>$agency_regions['agency_id']);
		if(tep_not_null($agency_regions['agency_ids'])){
			$agency_ids_a = explode(',',$agency_regions['agency_ids']);
			foreach($agency_ids_a as $val){
				$selected_vals[] = array('id'=>$val);
			}
		}
		$contents[] = array('text' => '<br>' . TEXT_AGENCY_NAME . '<br>' . tep_draw_mselect_menu('agency_ids[]', $agency_array, $selected_vals, ' size="10" '));
		$contents[] = array('text' => tep_draw_hidden_field('tour_provider_regions_id',$agency_regions['tour_provider_regions_id']));
		$contents[] = array('text' => '<br>' . TEXT_REGION_NAME . '<br>' . tep_draw_pull_down_menu('region_combo', $region_array, $agency_regions['region'], '') .' <br>OR (insert)<br> '. tep_draw_input_field('region'));
		$contents[] = array('text' => '<br>' . TEXT_HOTEL . '<br>' . tep_draw_input_field('address',$agency_regions['address']));
		$contents[] = array('text' => '<br>' . TEXT_ADDRESS . '<br>' . tep_draw_input_field('full_address',$agency_regions['full_address'],'size=50'));
		$contents[] = array('text' => '<br>' . TEXT_TIME . ' (HH:MMam e.g:- 9:00am)<br>' . tep_draw_input_field('departure_time',$agency_regions['departure_time']));
		$contents[] = array('text' => '<br>' . TEXT_MAP_PATH . '<br>' . tep_draw_input_field('map_path',$agency_regions['map_path']));
		$contents[] = array('text' => '<br>' . '附近酒店ID(多个ID用,号分隔)' . '<br>' . tep_draw_input_num_en_field('products_hotels_ids',$agency_regions['products_hotels_ids']));
		$contents[] = array('text' => '<br>' . '提示消息' . '<br>' . tep_draw_textarea_field('departure_tips','wrap',20,5,$agency_regions['departure_tips']));
		$contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_TOUR_PROVIDER_REGIONS, 'page=' . $_GET['page'] . '&mID=' . $agency_regions['tour_provider_regions_id'] ).(isset($_GET['aid']) ? '&aid=' . $_GET['aid'] . '' : '').'">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
		break;
	case 'delete':
		$heading[] = array('text' => '<b>' . TEXT_HEADING_DELETE_MANUFACTURER . '</b>');

		$contents = array('form' => tep_draw_form('tour_providers', FILENAME_TOUR_PROVIDER_REGIONS, 'page=' . $_GET['page'] . '&mID=' . $agency_regions['tour_provider_regions_id'] .(isset($_GET['aid']) ? '&aid=' . $_GET['aid'] . '' : ''). '&action=deleteconfirm'));
		$contents[] = array('text' => TEXT_DELETE_INTRO);
		$contents[] = array('text' => '<br>' . TEXT_AGENCY_NAME . ' ' .$agency_regions['agency_name']);
		$contents[] = array('text' => '<br>' . TEXT_REGION_NAME . ' ' .$agency_regions['region']);
		$contents[] = array('text' => '<br>' . TEXT_HOTEL . ' ' . $agency_regions['address']);
		$contents[] = array('text' => '<br>' . TEXT_ADDRESS . ' ' . $agency_regions['full_address']);
		//$contents[] = array('text' => '<br>' . TEXT_CITY_NAME . ' ' . $agency_regions['city']);
		$contents[] = array('text' => '<br>' . TEXT_TIME . ' ' . $agency_regions['departure_time']);
		$contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_TOUR_PROVIDER_REGIONS, 'page=' . $_GET['page'] . '&mID=' . $mInfo->tour_provider_regions_id ) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
		break;
	default:
		if ($agency_regions['tour_provider_regions_id'] != '') {
			$agency_names = false;
			$agency_ids = explode(',',$agency_regions['agency_ids']);
			foreach($agency_ids as $aid){
				$agency_names[]= tep_get_travel_agency_name($aid);
			}
			
			$heading[] = array('text' => '<b>' . implode(', ', $agency_names) . '</b>');

			$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_TOUR_PROVIDER_REGIONS, 'page=' . $_GET['page'] . '&mID=' . $agency_regions['tour_provider_regions_id'] .(isset($_GET['aid']) ? '&aid=' . $_GET['aid'] . '' : ''). '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_TOUR_PROVIDER_REGIONS, 'page=' . $_GET['page'] . '&mID=' . $agency_regions['tour_provider_regions_id'] .(isset($_GET['aid']) ? '&aid=' . $_GET['aid'] . '' : ''). '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
			$contents[] = array('text' => '<br>' . TEXT_AGENCY_NAME . ' ' .implode(', ', $agency_names));
			$contents[] = array('text' => '<br>' . TEXT_REGION_NAME . ' ' .$agency_regions['region']);
			$contents[] = array('text' => '<br>' . TEXT_HOTEL . ' ' . $agency_regions['address']);
			$contents[] = array('text' => '<br>' . TEXT_ADDRESS . ' ' . $agency_regions['full_address']);
			//	$contents[] = array('text' => '<br>' . TEXT_CITY_NAME . ' ' . $agency_regions['city']);
			$contents[] = array('text' => '<br>' . TEXT_TIME . ' ' . $agency_regions['departure_time']);
		}
		break;
}

if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
	echo '            <td width="25%" valign="top">' . "\n";

	$box = new box;
	echo $box->infoBox($heading, $contents);

	echo '            </td>' . "\n";
}
?>
          </tr>
        </table>
		<?php
		if($tour_provider_regions_permissions['能看不能编'] == true){
			$ob_html = ob_get_clean();
			$ob_html = preg_replace('/\<[[:space:]]*form[[:space:]]*/is','<XXXform ',$ob_html);
			$ob_html = preg_replace('/\<[[:space:]]*\/[[:space:]]*form[[:space:]]*\>/is','</XXXform>',$ob_html);
			$ob_html = preg_replace('/\<[[:space:]]*input[[:space:]]*/is','<input disabled="disabled" ',$ob_html);
			$ob_html = preg_replace('/\<[[:space:]]*button[[:space:]]*/is','<button disabled="disabled" ',$ob_html);
			$ob_html = preg_replace('/\<[[:space:]]*textarea[[:space:]]*/is','<textarea disabled="disabled" ',$ob_html);
			//去掉删除功能
			$ob_html = str_replace('action=delete','action=notDelete',$ob_html);
			echo $ob_html;
		}
		?>
		</td>
      </tr>
    </table></td>
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
<script>
function oneupdate(url,e){
	if (confirm('请确定是更新这个接送地点吗？该操作不可还原哦！')) {
		location.href = url;
	}
	e = e || event;
	if (e && e.stopPropagation) {
        e.stopPropagation();
	} else {
        window.event.cancelBubble=true
	}
	return false;

	
}
function validation()
{
	if(document.tour_providers.agency_ids.value == '')
	{
		alert("Please Select Tour Provider..!");
		document.tour_providers.agency_ids.focus();
		return false;
	}
	/*
	if(document.tour_providers.region.value == '' && document.tour_providers.region_combo.value == '')
	{
	alert("Please select or enter region..!");
	document.tour_providers.region.focus();
	return false;
	}*/
	if(document.tour_providers.region.value != '' && document.tour_providers.region_combo.value != '')
	{
		alert("Please select region or enter region..!");
		document.tour_providers.region.focus();
		return false;
	}

	if(document.tour_providers.address.value == '')
	{
		alert("Please enter address..!");
		document.tour_providers.address.focus();
		return false;
	}
	if(document.tour_providers.full_address.value == '')
	{
		alert("Please enter full_address..!");
		document.tour_providers.full_address.focus();
		return false;
	}
	/*
	if(document.tour_providers.city.value == '')
	{
	alert("Please enter city..!");
	document.tour_providers.city.focus();
	return false;
	}
	*/
	if(document.tour_providers.departure_time.value == '')
	{
		alert("Please enter time..!");
		document.tour_providers.departure_time.focus();
		return false;
	}


	return true;
}
</script>

<script type="text/javascript">
function onselectagency(ag_id){

	//alert("test--->"+ag_id);
	window.location = "tour_provider_regions.php?<?php echo tep_get_all_get_params(array('ag_id'))?>&ag_id="+ag_id;
}
</script>