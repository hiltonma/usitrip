<?php
/*
$Id: provider_order_products_status.php,v 1.1.1.1 2004/03/04 23:38:51 ccwjr Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2003 osCommerce

Released under the GNU General Public License
*/

require('includes/application_top.php');
// 备注添加删除
if($_GET['ajax']=="true"){
	include DIR_FS_CLASSES . 'Remark.class.php';
	$remark = new Remark('provider_order_products_status');
	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
}
$action = (isset($_GET['action']) ? $_GET['action'] : '');

if (tep_not_null($action)) {
	switch ($action) {
		case 'insert':
		case 'save':
			if (isset($_GET['oID'])) $provider_order_status_id = tep_db_prepare_input($_GET['oID']);

			$languages = tep_get_languages();
			for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
				$provider_order_status_name_array = $_POST['provider_order_status_name'];
				$provider_order_status_for_array = $_POST['provider_order_status_for'];
				$provider_sort_order_array = $_POST['sort_order'];
				$language_id = $languages[$i]['id'];

				$sql_data_array = array('provider_order_status_name' => tep_db_prepare_input($provider_order_status_name_array[$language_id]),
										'provider_order_status_for' => tep_db_prepare_input($provider_order_status_for_array[$language_id]),
										'sort_order' => $provider_sort_order_array[$language_id]);

				if ($action == 'insert') {
					tep_db_perform(TABLE_PROVIDER_ORDER_PRODUCTS_STATUS, $sql_data_array);
					$provider_order_status_id=tep_db_insert_id();
				} elseif ($action == 'save') {
					if($provider_order_status_for_array[$language_id]=="1"){
						$sql_data_array['orders_status_id'] = tep_db_prepare_input($_POST['orders_status_id']);
					}
					tep_db_perform(TABLE_PROVIDER_ORDER_PRODUCTS_STATUS, $sql_data_array, 'update', "provider_order_status_id = '" . (int)$provider_order_status_id . "'");
				}
			}

			tep_redirect(tep_href_link(FILENAME_PROVIDERS_ORDERS_PROD_STATUS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $provider_order_status_id));
			break;
		case 'deleteconfirm':
			$oID = tep_db_prepare_input($_GET['oID']);

			tep_db_query("delete from " . TABLE_PROVIDER_ORDER_PRODUCTS_STATUS . " where provider_order_status_id = '" . tep_db_input($oID) . "'");

			tep_redirect(tep_href_link(FILENAME_PROVIDERS_ORDERS_PROD_STATUS, tep_get_all_get_params(array('oID', 'action')) ));
			break;
		case 'delete':
			$oID = tep_db_prepare_input($_GET['oID']);

			$status_query = tep_db_query("select count(*) as count from " . TABLE_PROVIDER_ORDER_PRODUCTS_STATUS_HISTORY . " where provider_order_status_id = '" . (int)$oID . "'");
			$status = tep_db_fetch_array($status_query);

			$remove_status = true;
			if ($oID == DEFAULT_PROVIDER_ORDER_PRODUCTS_STATUS_ID) {
				$remove_status = false;
				$messageStack->add(ERROR_REMOVE_DEFAULT_ORDER_STATUS, 'error');
			} elseif ($status['count'] > 0) {
				$remove_status = false;
				$messageStack->add(ERROR_STATUS_USED_IN_ORDERS, 'error');
			}
			break;
	}
}
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
$listrs = new Remark('provider_order_products_status');
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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
              	<td class="dataTableHeadingContent" nowrap>
				<?php //echo "Status-ID"; 
$HEADING_STATUS_ID ='Status-ID ' ;

$HEADING_STATUS_ID .= '<a href="' . tep_href_link(basename($_SERVER['PHP_SELF']), tep_get_all_get_params(array('sortby','sortd','oID','page','action')) . 'sortby=id&sortd=ascending').'">';

$HEADING_STATUS_ID .= '&nbsp;<img src="'.DIR_WS_IMAGES.'arrow_up.gif" border="0"></a>';

$HEADING_STATUS_ID .= '<a href="' . tep_href_link(basename($_SERVER['PHP_SELF']), tep_get_all_get_params(array('sortby','oID','page','sortd','action')) . 'sortby=id&sortd=decending').'">';

$HEADING_STATUS_ID .= '&nbsp;<img src="'.DIR_WS_IMAGES.'arrow_down.gif" border="0"></a>';
echo $HEADING_STATUS_ID;

				?>
				</td>
                <td class="dataTableHeadingContent">
				<?php // echo TABLE_HEADING_ORDERS_STATUS;
				$HEADING_HEADING_ORDERS_STATUS  = TABLE_HEADING_ORDERS_STATUS ;

				$HEADING_HEADING_ORDERS_STATUS  .= '<a href="' . tep_href_link(basename($_SERVER['PHP_SELF']), tep_get_all_get_params(array('sortby','sortd','oID','page','action')) . 'sortby=name&sortd=ascending').'">';

				$HEADING_HEADING_ORDERS_STATUS  .= '&nbsp;<img src="'.DIR_WS_IMAGES.'arrow_up.gif" border="0"></a>';

				$HEADING_HEADING_ORDERS_STATUS  .= '<a href="' . tep_href_link(basename($_SERVER['PHP_SELF']), tep_get_all_get_params(array('sortby','oID','page','sortd','action')) . 'sortby=name&sortd=decending').'">';

				$HEADING_HEADING_ORDERS_STATUS  .= '&nbsp;<img src="'.DIR_WS_IMAGES.'arrow_down.gif" border="0"></a>';
				echo $HEADING_HEADING_ORDERS_STATUS ;

				?>
				
				</td>
				<td class="dataTableHeadingContent">
				<?php // echo TABLE_HEADING_ORDERS_STATUS;
				echo $HEADING_HEADING_ORDERS_STATUS_FOR  = TABLE_HEADING_ORDERS_STATUS_FOR ;
				 ?>
				
				</td>
				<td class="dataTableHeadingContent">
				<?php 
				$HEADING_HEADING_SORT_ORDER = TABLE_HEADING_SORT_ORDER;
				$HEADING_HEADING_SORT_ORDER  .= '<a href="' . tep_href_link(basename($_SERVER['PHP_SELF']), tep_get_all_get_params(array('sortby','sortd','oID','page','action')) . 'sortby=sort_order&sortd=ascending').'">';
				$HEADING_HEADING_SORT_ORDER  .= '&nbsp;<img src="'.DIR_WS_IMAGES.'arrow_up.gif" border="0"></a>';
				$HEADING_HEADING_SORT_ORDER  .= '<a href="' . tep_href_link(basename($_SERVER['PHP_SELF']), tep_get_all_get_params(array('sortby','oID','page','sortd','action')) . 'sortby=sort_order&sortd=decending').'">';
				$HEADING_HEADING_SORT_ORDER  .= '&nbsp;<img src="'.DIR_WS_IMAGES.'arrow_down.gif" border="0"></a>';
				echo $HEADING_HEADING_SORT_ORDER ;
				?>
				</td>
                <td class="dataTableHeadingContent">供应商更新下单状态时我们对应的订单状态</td>
				<td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php

$sortorder_status = ' order by provider_order_status_for ASC, ';

if($_GET["sortby"] == 'id') {

	if($_GET["sortd"] == 'ascending') {

		$sortorder_status .= 'provider_order_status_id asc ';

	} else {

		$sortorder_status .= 'provider_order_status_id desc ';

	}

}elseif($_GET["sortby"] == 'name') {

	if($_GET["sortd"] == 'ascending') {

		$sortorder_status .= 'provider_order_status_name  asc ';

	} else {

		$sortorder_status .= 'provider_order_status_name desc ';

	}

}elseif($_GET["sortby"] == 'sort_order') {
	if($_GET["sortd"] == 'ascending') {
		$sortorder_status .= 'sort_order asc ';
	} else {
		$sortorder_status .= 'sort_order desc ';
	}
}else{
	//$sortorder_status .= 'provider_order_status_id asc ';
	$sortorder_status .= 'sort_order asc ';
}

$orders_status_query_raw = "select provider_order_status_id, provider_order_status_name, provider_order_status_for, sort_order, if(provider_order_status_for=1, 'Providers', 'Admin') as nm_status_for, orders_status_id from " . TABLE_PROVIDER_ORDER_PRODUCTS_STATUS . " where 1 ".$sortorder_status; //language_id = '" . (int)$languages_id . "'

$prev_nm_status_for="";
$orders_status_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $orders_status_query_raw, $orders_status_query_numrows);
$orders_status_query = tep_db_query($orders_status_query_raw);
$not_in = array(0);
while ($orders_status = tep_db_fetch_array($orders_status_query)) {
	if((int)$orders_status['orders_status_id']){
		$not_in[] = $orders_status['orders_status_id'];
	}
	if ((!isset($_GET['oID']) || (isset($_GET['oID']) && ($_GET['oID'] == $orders_status['provider_order_status_id']))) && !isset($oInfo) && (substr($action, 0, 3) != 'new')) {
		$oInfo = new objectInfo($orders_status);
	}

	if($prev_nm_status_for != $orders_status['nm_status_for']){
		$prev_nm_status_for=$orders_status['nm_status_for'];
		echo '                  <tr><td class="dataTableContent"><strong>'.$prev_nm_status_for.'</strong></td></tr>' . "\n";
	}else{
	}

	if (isset($oInfo) && is_object($oInfo) && ($orders_status['provider_order_status_id'] == $oInfo->provider_order_status_id)) {
		echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_PROVIDERS_ORDERS_PROD_STATUS, tep_get_all_get_params(array('oID','action')) . 'oID=' . $oInfo->provider_order_status_id . '&action=edit') . '\'">' . "\n";
	} else {
		echo '                  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_PROVIDERS_ORDERS_PROD_STATUS, tep_get_all_get_params(array('oID','action')) .  'oID=' . $orders_status['provider_order_status_id']) . '\'">' . "\n";
	}

	echo '<td class="dataTableContent">' . $orders_status['provider_order_status_id'] . '</td>';

	if (DEFAULT_PROVIDER_ORDER_PRODUCTS_STATUS_ID == $orders_status['provider_order_status_id']) {
		echo '                <td class="dataTableContent"><b>' . $orders_status['provider_order_status_name'] . ' (' . TEXT_DEFAULT . ')</b></td>' . "\n";
	} else {
		echo '                <td class="dataTableContent">' . $orders_status['provider_order_status_name'] . '</td>' . "\n";
	}
	echo '<td class="dataTableContent">' . $orders_status['nm_status_for'] . '</td>';
	echo '<td class="dataTableContent">' . $orders_status['sort_order'] . '</td>';
?>
                <td class="dataTableContent"><?php echo tep_get_orders_status_name($orders_status['orders_status_id']);?>&nbsp;</td>
				<td class="dataTableContent" align="right">
				<?php echo '<a href="' . tep_href_link(FILENAME_PROVIDERS_ORDERS_PROD_STATUS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $orders_status['provider_order_status_id']) . '&action=edit">' . tep_image('images/icons/edit.gif', '编辑') . '</a>&nbsp;';?>
				<?php if (isset($oInfo) && is_object($oInfo) && ($orders_status['provider_order_status_id'] == $oInfo->provider_order_status_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_PROVIDERS_ORDERS_PROD_STATUS,  tep_get_all_get_params(array('oID','action')) . 'oID=' . $orders_status['provider_order_status_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;
				
				</td>
              </tr>
<?php
}
?>
              <tr>
                <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $orders_status_split->display_count($orders_status_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS_STATUS); ?></td>
                    <td class="smallText" align="right"><?php echo $orders_status_split->display_links($orders_status_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'oID', 'action'))); ?></td>
                  </tr>
<?php
if (empty($action)) {
?>
                  <tr>
                    <td colspan="2" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_PROVIDERS_ORDERS_PROD_STATUS, 'page=' . $_GET['page'] . '&action=new') . '">' . tep_image_button('button_insert.gif', IMAGE_INSERT) . '</a>'; ?></td>
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
	case 'new':
		$heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_ORDERS_STATUS . '</b>');

		$contents = array('form' => tep_draw_form('status', FILENAME_PROVIDERS_ORDERS_PROD_STATUS, 'page=' . $_GET['page'] . '&action=insert'));
		$contents[] = array('text' => TEXT_INFO_INSERT_INTRO);

		$orders_status_inputs_string = '';
		$languages = tep_get_languages();
		for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
			$orders_status_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('provider_order_status_name[' . $languages[$i]['id'] . ']');
			$orders_status_inputs_string .= '<br>' . TABLE_HEADING_SORT_ORDER . ':<br>' . tep_draw_separator('pixel_trans.gif', '30', '1') . '&nbsp;' . tep_draw_input_field('sort_order[' . $languages[$i]['id'] . ']', 0, 'size="2"');
			$orders_status_inputs_string .= '<br>' . '&nbsp;' . tep_draw_input_field('provider_order_status_for[' . $languages[$i]['id'] . ']', '0', 'checked="checked"', false, 'radio'). TEXT_ADMIN. '&nbsp;' . tep_draw_input_field('provider_order_status_for[' . $languages[$i]['id'] . ']', '1', '', false, 'radio'). TEXT_PROVIDERS;
			$orders_status_default_subject_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('orders_status_default_subject[' . $languages[$i]['id'] . ']');
			$orders_status_default_comment_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_textarea_field('orders_status_default_comment[' . $languages[$i]['id'] . ']', 'soft', '70', '15');

		}

		$contents[] = array('text' => '<br>' . TEXT_INFO_ORDERS_STATUS_NAME . $orders_status_inputs_string);

		//     $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT);
		$contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_insert.gif', IMAGE_INSERT) . ' <a href="' . tep_href_link(FILENAME_PROVIDERS_ORDERS_PROD_STATUS, 'page=' . $_GET['page']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
		break;
	case 'edit':
		$heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_ORDERS_STATUS . '</b>');

		$contents = array('form' => tep_draw_form('status', FILENAME_PROVIDERS_ORDERS_PROD_STATUS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->provider_order_status_id  . '&action=save'));
		$contents[] = array('text' => TEXT_INFO_EDIT_INTRO);

		$orders_status_inputs_string = '';
		$languages = tep_get_languages();
		for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
			$orders_status_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('provider_order_status_name[' . $languages[$i]['id'] . ']', tep_get_provider_order_status_name($oInfo->provider_order_status_id, $languages[$i]['id']));
			$orders_status_inputs_string .= '<br>' . TABLE_HEADING_SORT_ORDER . ':<br>' . tep_draw_separator('pixel_trans.gif', '30', '1') . '&nbsp;' . tep_draw_input_field('sort_order[' . $languages[$i]['id'] . ']', $oInfo->sort_order, 'size="2"');
			$orders_status_inputs_string .= '<br>' . '&nbsp;' . tep_draw_input_field('provider_order_status_for[' . $languages[$i]['id'] . ']', '0', 'checked="checked"', false, 'radio'). TEXT_ADMIN. '&nbsp;' . tep_draw_input_field('provider_order_status_for[' . $languages[$i]['id'] . ']', '1', ($oInfo->provider_order_status_for=="1"?'checked="checked"':''), false, 'radio'). TEXT_PROVIDERS;
			$orders_status_default_subject_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('orders_status_default_subject[' . $languages[$i]['id'] . ']', tep_get_orders_status_default_subject($oInfo->provider_order_status_id, $languages[$i]['id']));
			$orders_status_default_comment_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_textarea_field('orders_status_default_comment[' . $languages[$i]['id'] . ']', 'soft', '70', '15', tep_get_orders_status_default_comment($oInfo->provider_order_status_id, $languages[$i]['id']));

		}

		$contents[] = array('text' => '<br>' . TEXT_INFO_ORDERS_STATUS_NAME . $orders_status_inputs_string);

		$not_in = $not_in;
		if(array_search($oInfo->orders_status_id, $not_in)!==false) unset($not_in[array_search($oInfo->orders_status_id, $not_in)]);
		
		$sql = tep_db_query('SELECT orders_status_id, orders_status_name FROM `orders_status` WHERE os_groups_id="14" and orders_status_id not in('.implode(',',$not_in).') order by orders_status_name ');
		$orders_status_options = array();
		$orders_status_options[] = array('id'=>"",'text'=>"请选择对应的状态");
		while($rows = tep_db_fetch_array($sql)){
			$orders_status_options[] = array('id'=>$rows['orders_status_id'],'text'=>$rows['orders_status_name']);
		}

		$contents[] = array('text' => '<br>供应商更新下单状态时我们对应的订单状态：<br>'.tep_draw_pull_down_menu('orders_status_id',$orders_status_options, $oInfo->orders_status_id) );

		//   if (DEFAULT_PROVIDER_ORDER_PRODUCTS_STATUS_ID != $oInfo->provider_order_status_id) $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT);
		$contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . tep_href_link(FILENAME_PROVIDERS_ORDERS_PROD_STATUS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->provider_order_status_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
		break;
	case 'delete':
		$heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_ORDERS_STATUS . '</b>');

		$contents = array('form' => tep_draw_form('status', FILENAME_PROVIDERS_ORDERS_PROD_STATUS,  tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->provider_order_status_id  . '&action=deleteconfirm'));
		$contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
		$contents[] = array('text' => '<br><b>' . $oInfo->provider_order_status_name . '</b>');
		if ($remove_status) $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_PROVIDERS_ORDERS_PROD_STATUS,  tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->provider_order_status_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
		break;
	default:
		if (isset($oInfo) && is_object($oInfo)) {
			$heading[] = array('text' => '<b>' . $oInfo->provider_order_status_name . '</b>');

			$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_PROVIDERS_ORDERS_PROD_STATUS,  tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->provider_order_status_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_PROVIDERS_ORDERS_PROD_STATUS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->provider_order_status_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');

			$orders_status_inputs_string = '';
			$languages = tep_get_languages();
			for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
				$orders_status_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_get_provider_order_status_name($oInfo->provider_order_status_id, $languages[$i]['id']);
			}

			$contents[] = array('text' => $orders_status_inputs_string);
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
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>