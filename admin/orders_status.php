<?php
/*
  $Id: orders_status.php,v 1.1.1.1 2004/03/04 23:38:51 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

require('includes/application_top.php');
// 备注添加删除
if($_GET['ajax']=="true"){
	include DIR_FS_CLASSES . 'Remark.class.php';
	$remark = new Remark('orders_status');
	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
}
$order_dispaly_status_control_access = false;
if ($login_groups_id == '1') {
    $order_dispaly_status_control_access = true;
}

$action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

if (tep_not_null($action)) {
    switch ($action) {
        case 'insert':
        case 'save':
            if (isset($HTTP_GET_VARS['oID']))
                $orders_status_id = tep_db_prepare_input($HTTP_GET_VARS['oID']);

            $languages = tep_get_languages();
            for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
                $orders_status_name_array = $HTTP_POST_VARS['orders_status_name'];
                $orders_status_name_1_array = $HTTP_POST_VARS['orders_status_name_1'];
                $orders_status_default_subject_array = $HTTP_POST_VARS['orders_status_default_subject'];
                $orders_status_default_comment_array = $HTTP_POST_VARS['orders_status_default_comment'];
                $orders_status_groups_id_array = $HTTP_POST_VARS['os_groups_id'];
                $sort_id_array = $HTTP_POST_VARS['sort_id'];
                $language_id = $languages[$i]['id'];

                $sql_data_array = array('orders_status_name' => tep_db_prepare_input($orders_status_name_array[$language_id]),
					'orders_status_name_1' => tep_db_prepare_input($orders_status_name_1_array[$language_id]),
                    'orders_status_default_subject' => tep_db_prepare_input($orders_status_default_subject_array[$language_id]),
                    'orders_status_default_comment' => tep_db_prepare_input($orders_status_default_comment_array[$language_id]),
					'os_groups_id' => (int)$orders_status_groups_id_array[$language_id],
					'sort_id' => (int)$sort_id_array[$language_id]
                );

                if ($action == 'insert') {
                    if (empty($orders_status_id)) {
                        $next_id_query = tep_db_query("select max(orders_status_id) as orders_status_id from " . TABLE_ORDERS_STATUS . "");
                        $next_id = tep_db_fetch_array($next_id_query);
                        $orders_status_id = $next_id['orders_status_id'] + 1;
                    }

                    $insert_sql_data = array('orders_status_id' => $orders_status_id,
                        'language_id' => $language_id);

                    $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

                    tep_db_perform(TABLE_ORDERS_STATUS, $sql_data_array);
                } elseif ($action == 'save') {
                    tep_db_perform(TABLE_ORDERS_STATUS, $sql_data_array, 'update', "orders_status_id = '" . (int) $orders_status_id . "' and language_id = '" . (int) $language_id . "'");
                }
            }

            if (isset($HTTP_POST_VARS['default']) && ($HTTP_POST_VARS['default'] == 'on')) {
                tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . tep_db_input($orders_status_id) . "' where configuration_key = 'DEFAULT_ORDERS_STATUS_ID'");
               MCache::update_main_config();//update cache by vincent
            }
			
            tep_redirect(tep_href_link(FILENAME_ORDERS_STATUS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $orders_status_id));
            break;
        case 'deleteconfirm':
            $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);

            $orders_status_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'DEFAULT_ORDERS_STATUS_ID'");
            $orders_status = tep_db_fetch_array($orders_status_query);

            if ($orders_status['configuration_value'] == $oID) {
                tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '' where configuration_key = 'DEFAULT_ORDERS_STATUS_ID'");
                MCache::update_main_config();//update cache by vincent
            }

            tep_db_query("delete from " . TABLE_ORDERS_STATUS . " where orders_status_id = '" . tep_db_input($oID) . "'");

            tep_redirect(tep_href_link(FILENAME_ORDERS_STATUS, tep_get_all_get_params(array('oID', 'action'))));
            break;
        case 'delete':
            $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);

            $status_query = tep_db_query("select count(*) as count from " . TABLE_ORDERS . " where orders_status = '" . (int) $oID . "'");
            $status = tep_db_fetch_array($status_query);

            $remove_status = true;
            if ($oID == DEFAULT_ORDERS_STATUS_ID) {
                $remove_status = false;
                $messageStack->add(ERROR_REMOVE_DEFAULT_ORDER_STATUS, 'error');
            } elseif ($status['count'] > 0) {
                $remove_status = false;
                $messageStack->add(ERROR_STATUS_USED_IN_ORDERS, 'error');
            } else {
                $history_query = tep_db_query("select count(*) as count from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_status_id = '" . (int) $oID . "'");
                $history = tep_db_fetch_array($history_query);
                if ($history['count'] > 0) {
                    $remove_status = false;
                    $messageStack->add(ERROR_STATUS_USED_IN_HISTORY, 'error');
                }
            }
            break;
        case 'setflag':
            if ((($HTTP_GET_VARS['flag'] == '0') || ($HTTP_GET_VARS['flag'] == '1')) && $order_dispaly_status_control_access == true) {

                if (isset($HTTP_GET_VARS['oID'])) {
                    $orders_status_id = tep_db_prepare_input($HTTP_GET_VARS['oID']);

//exit;
                    tep_db_query("update " . TABLE_ORDERS_STATUS . " set orders_status_display = '" . $HTTP_GET_VARS['flag'] . "' where orders_status_id = '" . $orders_status_id . "'");
                }
            }
            tep_redirect(tep_href_link(FILENAME_ORDERS_STATUS, 'page=' . $HTTP_GET_VARS['page'] . '&oID=' . $orders_status_id . (isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : '') . (isset($HTTP_GET_VARS['sortd']) ? '&sortd=' . $HTTP_GET_VARS['sortd'] . '' : '') . (isset($HTTP_GET_VARS['sortby']) ? '&sortby=' . $HTTP_GET_VARS['sortby'] . '' : '')));
            break;
    }
}

// 取得所有订单状态组
$orders_status_groups_array = array();
$orders_status_groups_array[] = array('id' => "0", 'text' => "All Groups");
$groups_sql = tep_db_query('SELECT * FROM `orders_status_groups` Order By sort_id , os_groups_id; ');
while ($groups_rows = tep_db_fetch_array($groups_sql)) {
    $orders_status_groups_array[] = array('id' => $groups_rows['os_groups_id'], 'text' => tep_db_output($groups_rows['os_groups_name']));
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
$listrs = new Remark('orders_status');
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
                            <td valign="top">
                                <form action="" method="get" id="FilterForm" name="FilterForm">
                                    <table border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td align="right">&nbsp;<?php echo TITLE_FILTER; ?></td>
                                            <td align="left">
<?php
if (sizeof($orders_status_groups_array) > 1) {
    echo tep_draw_pull_down_menu('os_groups_id', $orders_status_groups_array, $os_groups_id, 'onchange="document.getElementById(\'FilterForm\').submit()"');
}
?>
                                            &nbsp;</td>
											<td>&nbsp;<a href="<?php echo tep_href_link('orders_status_groups.php');?>"><?php echo TEXT_INFO_MANAGEMENT_GROUPS;?></a></td>
                                        </tr>
                                    </table>
                                </form>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                                                <tr class="dataTableHeadingRow">
                                                    <td class="dataTableHeadingContent"  nowrap>
<?php
//echo "Status-ID"; 
$HEADING_STATUS_ID = 'Status-ID ';

$HEADING_STATUS_ID .= '<a href="' . tep_href_link(basename($_SERVER['PHP_SELF']), tep_get_all_get_params(array('sortby', 'sortd', 'oID', 'page', 'action')) . 'sortby=id&sortd=ascending') . '">';

$HEADING_STATUS_ID .= '&nbsp;<img src="images/arrow_up.gif" border="0"></a>';

//$HEADING_STATUS_ID .= '<a href="' . $_SERVER['PHP_SELF'] . '?sort=departure_date&order=decending'.(isset($HTTP_GET_VARS['status']) ? '&status=' . $HTTP_GET_VARS['status'] . '' : '').$search_extra_get.'">';
$HEADING_STATUS_ID .= '<a href="' . tep_href_link(basename($_SERVER['PHP_SELF']), tep_get_all_get_params(array('sortby', 'oID', 'page', 'sortd', 'action')) . 'sortby=id&sortd=decending') . '">';

$HEADING_STATUS_ID .= '&nbsp;<img src="images/arrow_down.gif" border="0"></a>';
echo $HEADING_STATUS_ID;
?>
                                                    </td>
                                                    <td class="dataTableHeadingContent">
<?php
// echo TABLE_HEADING_ORDERS_STATUS;
$HEADING_HEADING_ORDERS_STATUS = TABLE_HEADING_ORDERS_STATUS;

$HEADING_HEADING_ORDERS_STATUS .= '<a href="' . tep_href_link(basename($_SERVER['PHP_SELF']), tep_get_all_get_params(array('sortby', 'sortd', 'oID', 'page', 'action')) . 'sortby=name&sortd=ascending') . '">';

$HEADING_HEADING_ORDERS_STATUS .= '&nbsp;<img src="images/arrow_up.gif" border="0"></a>';

//$HEADING_HEADING_ORDERS_STATUS  .= '<a href="' . $_SERVER['PHP_SELF'] . '?sort=departure_date&order=decending'.(isset($HTTP_GET_VARS['status']) ? '&status=' . $HTTP_GET_VARS['status'] . '' : '').$search_extra_get.'">';
$HEADING_HEADING_ORDERS_STATUS .= '<a href="' . tep_href_link(basename($_SERVER['PHP_SELF']), tep_get_all_get_params(array('sortby', 'oID', 'page', 'sortd', 'action')) . 'sortby=name&sortd=decending') . '">';

$HEADING_HEADING_ORDERS_STATUS .= '&nbsp;<img src="images/arrow_down.gif" border="0"></a>';
echo $HEADING_HEADING_ORDERS_STATUS;
?>

                                                    </td>
													<td class="dataTableHeadingContent">订单状态（客人看到的）</td>
<?php if ($order_dispaly_status_control_access == true) { // only top admin can manage  ?>
                                                    <td class="dataTableHeadingContent" >Dispaly Status</td>
                                                    <?php } ?>
                                                    <td class="dataTableHeadingContent" align="right" nowrap>
													Sort Id
<?php
$HEADING_HEADING_SORT_ID = '<a href="' . tep_href_link(basename($_SERVER['PHP_SELF']), tep_get_all_get_params(array('sortby', 'sortd', 'oID', 'page', 'action')) . 'sortby=sort&sortd=ascending') . '">';
$HEADING_HEADING_SORT_ID .= '&nbsp;<img src="images/arrow_up.gif" border="0"></a>';
$HEADING_HEADING_SORT_ID .= '<a href="' . tep_href_link(basename($_SERVER['PHP_SELF']), tep_get_all_get_params(array('sortby', 'oID', 'page', 'sortd', 'action')) . 'sortby=sort&sortd=decending') . '">';
$HEADING_HEADING_SORT_ID .= '&nbsp;<img src="images/arrow_down.gif" border="0"></a>';
echo $HEADING_HEADING_SORT_ID;
?>
													</td>
													<td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
                                                </tr>
<?php
                                                    $sortorder_status = ' order by ';

                                                    if ($order_dispaly_status_control_access == false) {
                                                        $extr_where_con = " and orders_status_display='1'";
                                                    }
                                                    if ((int) $_GET['os_groups_id']) {
                                                        $extr_where_con .= " and os_groups_id ='" . (int) $_GET['os_groups_id'] . "' ";
                                                    }

                                                    if ($_GET["sortby"] == 'id') {

                                                        if ($_GET["sortd"] == 'ascending') {

                                                            $sortorder_status .= 'orders_status_id asc ';
                                                        } else {

                                                            $sortorder_status .= 'orders_status_id desc ';
                                                        }
                                                    } elseif ($_GET["sortby"] == 'name') {

                                                        if ($_GET["sortd"] == 'ascending') {

                                                            $sortorder_status .= 'orders_status_name  asc ';
                                                        } else {

                                                            $sortorder_status .= 'orders_status_name desc ';
                                                        }
                                                    } elseif ($_GET["sortby"] == 'sort') {

                                                        if ($_GET["sortd"] == 'ascending') {

                                                            $sortorder_status .= 'sort_id  asc ';
                                                        } else {

                                                            $sortorder_status .= 'sort_id desc ';
                                                        }
                                                    } else {

                                                        $sortorder_status .= ' sort_id asc, orders_status_id asc ';
                                                    }

                                                    $orders_status_query_raw = "select * from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int) $languages_id . "'  " . $extr_where_con . " " . $sortorder_status . "";


                                                    $orders_status_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $orders_status_query_raw, $orders_status_query_numrows);
                                                    $orders_status_query = tep_db_query($orders_status_query_raw);
                                                    while ($orders_status = tep_db_fetch_array($orders_status_query)) {
                                                        if ((!isset($HTTP_GET_VARS['oID']) || (isset($HTTP_GET_VARS['oID']) && ($HTTP_GET_VARS['oID'] == $orders_status['orders_status_id']))) && !isset($oInfo) && (substr($action, 0, 3) != 'new')) {
                                                            $oInfo = new objectInfo($orders_status);
                                                        }

                                                        if (isset($oInfo) && is_object($oInfo) && ($orders_status['orders_status_id'] == $oInfo->orders_status_id)) {
                                                            echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_ORDERS_STATUS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_status_id . '&action=edit') . '\'">' . "\n";
                                                        } else {
                                                            echo '                  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_ORDERS_STATUS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $orders_status['orders_status_id']) . '\'">' . "\n";
                                                        }

                                                        echo '<td class="dataTableContent">' . $orders_status['orders_status_id'] . '</td>';

                                                        if (DEFAULT_ORDERS_STATUS_ID == $orders_status['orders_status_id']) {
                                                            echo '                <td class="dataTableContent"><b>' . $orders_status['orders_status_name'] . ' (' . TEXT_DEFAULT . ')</b></td>' . "\n";
                                                        } else {
                                                            echo '                <td class="dataTableContent">' . $orders_status['orders_status_name'] . '</td>' . "\n";
                                                        }
														
														echo '<td class="dataTableContent">' . $orders_status['orders_status_name_1'] . '</td>';
?>
                                                <?php if ($order_dispaly_status_control_access == true) { // only top admin can manage 
?>
                                                            <td class="dataTableContent" >            
                                                <?php
                                                            if ($orders_status['orders_status_display'] == '1') {
                                                                echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_ORDERS_STATUS, 'action=setflag&flag=0&oID=' . $orders_status['orders_status_id'] . (isset($HTTP_GET_VARS['page']) ? '&page=' . $HTTP_GET_VARS['page'] . '' : '') . (isset($HTTP_GET_VARS['sortby']) ? '&sortby=' . $HTTP_GET_VARS['sortby'] . '' : '') . (isset($HTTP_GET_VARS['sortd']) ? '&sortd=' . $HTTP_GET_VARS['sortd'] . '' : '')) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
                                                            } else {
                                                                echo '<a href="' . tep_href_link(FILENAME_ORDERS_STATUS, 'action=setflag&flag=1&oID=' . $orders_status['orders_status_id'] . (isset($HTTP_GET_VARS['page']) ? '&page=' . $HTTP_GET_VARS['page'] . '' : '') . (isset($HTTP_GET_VARS['sortby']) ? '&sortby=' . $HTTP_GET_VARS['sortby'] . '' : '') . (isset($HTTP_GET_VARS['sortd']) ? '&sortd=' . $HTTP_GET_VARS['sortd'] . '' : '')) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
                                                            }
                                                ?>					
                                                            </td>            
<?php } ?>
                                                        <td class="dataTableContent" align="right"><?php echo $orders_status['sort_id'];?></td>
														<td class="dataTableContent" align="right">
														
														<?php if (isset($oInfo) && is_object($oInfo) && ($orders_status['orders_status_id'] == $oInfo->orders_status_id)) {
                                                            echo '<a href="' . tep_href_link(FILENAME_ORDERS_STATUS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $orders_status['orders_status_id']) . '&action=edit">' . tep_image('images/icons/edit.gif', '编辑') . '</a>&nbsp;';
															echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', '');
                                                        } else {
															
                                                            echo '<a href="' . tep_href_link(FILENAME_ORDERS_STATUS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $orders_status['orders_status_id']) . '&action=edit">' . tep_image('images/icons/edit.gif', '编辑') . '</a>&nbsp;';
															echo '<a href="' . tep_href_link(FILENAME_ORDERS_STATUS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $orders_status['orders_status_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>';
                                                        } ?>&nbsp;</td>
                                        </tr>    
                                                    <?php
                                                    }
                                                    ?>
                                        <tr>    
                                            <td colspan="<?php if ($order_dispaly_status_control_access == true) {
                                                        echo '5';
                                                    } else {
                                                        echo '4';
                                                    } ?>"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                                                                <tr>                
                                                                    <td class="smallText" valign="top"><?php echo $orders_status_split->display_count($orders_status_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS_STATUS); ?></td>
                                                                    <td class="smallText" align="right"><?php echo $orders_status_split->display_links($orders_status_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'oID', 'action'))); ?></td>
                                                                </tr>                
<?php
                                                    if (empty($action)) {
?>
                                                        <tr>        
                                                            <td colspan="2" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_ORDERS_STATUS, 'page=' . $HTTP_GET_VARS['page'] . '&action=new') . '">' . tep_image_button('button_insert.gif', IMAGE_INSERT) . '</a>'; ?></td>
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

                                                            $contents = array('form' => tep_draw_form('status', FILENAME_ORDERS_STATUS, 'page=' . $HTTP_GET_VARS['page'] . '&action=insert'));
                                                            $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);

                                                            $orders_status_inputs_string = '';
                                                            $languages = tep_get_languages();
                                                            for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
                                                                $orders_status_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('orders_status_name[' . $languages[$i]['id'] . ']');
                                                                $orders_status_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('orders_status_name_1[' . $languages[$i]['id'] . ']').'(to customers)';
                                                                $orders_status_default_subject_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('orders_status_default_subject[' . $languages[$i]['id'] . ']');
                                                                $orders_status_default_comment_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_textarea_field('orders_status_default_comment[' . $languages[$i]['id'] . ']', 'soft', '70', '15');
																if (sizeof($orders_status_groups_array) > 1) {
																	$orders_status_groups_array[0]['text'] = 'Select one Status Group';
																	$orders_status_groups_string = tep_draw_pull_down_menu('os_groups_id[' . $languages[$i]['id'] . ']', $orders_status_groups_array);
																}
																$orders_sort_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('sort_id[' . $languages[$i]['id'] . ']', $oInfo->sort_id); 
                                                            }

															$contents[] = array('text' => '<br>' . TEXT_INFO_GROUPS . $orders_status_groups_string);
                                                            $contents[] = array('text' => '<br>' . 'Sort Id:' . $orders_sort_string);
                                                            $contents[] = array('text' => '<br>' . TEXT_INFO_ORDERS_STATUS_NAME . $orders_status_inputs_string);
                                                            $contents[] = array('text' => '<br>' . TEXT_INFO_ORDERS_DEFAULT_SUBJECT_STRING . $orders_status_default_subject_string);
                                                            $contents[] = array('text' => '<br>' . TEXT_INFO_ORDERS_DEFAULT_COMMENT_STRING . $orders_status_default_comment_string);

                                                            $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT);
                                                            $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_insert.gif', IMAGE_INSERT) . ' <a href="' . tep_href_link(FILENAME_ORDERS_STATUS, 'page=' . $HTTP_GET_VARS['page']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                                                            break;
                                                        case 'edit':
                                                            $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_ORDERS_STATUS . '</b>');

                                                            $contents = array('form' => tep_draw_form('status', FILENAME_ORDERS_STATUS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_status_id . '&action=save'));
                                                            $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);

                                                            $orders_status_inputs_string = '';
															$orders_sort_string = '';
                                                            $languages = tep_get_languages();
                                                            for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
                                                                $orders_status_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('orders_status_name[' . $languages[$i]['id'] . ']', tep_get_orders_status_name($oInfo->orders_status_id, $languages[$i]['id']));
																$orders_status_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('orders_status_name_1[' . $languages[$i]['id'] . ']', tep_get_orders_status_name_1($oInfo->orders_status_id, $languages[$i]['id'])).'(to customers)';
                                                                $orders_status_default_subject_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('orders_status_default_subject[' . $languages[$i]['id'] . ']', tep_get_orders_status_default_subject($oInfo->orders_status_id, $languages[$i]['id']));
                                                                $orders_status_default_comment_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_textarea_field('orders_status_default_comment[' . $languages[$i]['id'] . ']', 'soft', '70', '15', tep_get_orders_status_default_comment($oInfo->orders_status_id, $languages[$i]['id']));
                                                            
																if (sizeof($orders_status_groups_array) > 1) {
																	$orders_status_groups_array[0]['text'] = 'Select one Status Group';
																	$orders_status_groups_string = tep_draw_pull_down_menu('os_groups_id[' . $languages[$i]['id'] . ']', $orders_status_groups_array, $oInfo->os_groups_id);
																}
																$orders_sort_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('sort_id[' . $languages[$i]['id'] . ']', $oInfo->sort_id); 
															}
															
                                                            $contents[] = array('text' => '<br>' . TEXT_INFO_GROUPS . $orders_status_groups_string);
                                                            $contents[] = array('text' => '<br>' . 'Sort Id:' . $orders_sort_string);
															
                                                            $contents[] = array('text' => '<br>' . TEXT_INFO_ORDERS_STATUS_NAME . $orders_status_inputs_string);
                                                            $contents[] = array('text' => '<br>' . TEXT_INFO_ORDERS_DEFAULT_SUBJECT_STRING . $orders_status_default_subject_string);
                                                            $contents[] = array('text' => '<br><h2>注意：凡是有{}括起的文字都不可修改，如有问题请找技术部！</h2><br>' . TEXT_INFO_ORDERS_DEFAULT_COMMENT_STRING . $orders_status_default_comment_string);

                                                            if (DEFAULT_ORDERS_STATUS_ID != $oInfo->orders_status_id)
                                                                $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT);
                                                            $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . tep_href_link(FILENAME_ORDERS_STATUS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_status_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                                                            break;
                                                        case 'delete':
                                                            $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_ORDERS_STATUS . '</b>');

                                                            $contents = array('form' => tep_draw_form('status', FILENAME_ORDERS_STATUS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_status_id . '&action=deleteconfirm'));
                                                            $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
                                                            $contents[] = array('text' => '<br><b>' . $oInfo->orders_status_name . '</b>');
                                                            if ($remove_status)
                                                                $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_ORDERS_STATUS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_status_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                                                            break;
                                                        default:
                                                            if (isset($oInfo) && is_object($oInfo)) {
                                                                $heading[] = array('text' => '<b>' . $oInfo->orders_status_name . '</b>');

                                                                $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_ORDERS_STATUS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_status_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS_STATUS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_status_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');

                                                                $orders_status_inputs_string = '';
                                                                $languages = tep_get_languages();
                                                                for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
                                                                    $orders_status_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_get_orders_status_name($oInfo->orders_status_id, $languages[$i]['id']);
                                                                }

                                                                $contents[] = array('text' => $orders_status_inputs_string);
                                                            }
                                                            break;
                                                    }

                                                    if ((tep_not_null($heading)) && (tep_not_null($contents))) {
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