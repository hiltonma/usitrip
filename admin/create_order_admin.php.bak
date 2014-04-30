<?php
/*
  $Id: create_order_admin.php,v 1.1 2004/08/19 23:38:51 teo Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'insert':
      case 'save':
        if (isset($HTTP_GET_VARS['oID'])) $pay_methods_id = tep_db_prepare_input($HTTP_GET_VARS['oID']);

        $languages = tep_get_languages();
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $pay_method_array = $HTTP_POST_VARS['pay_method'];
          $pay_method = $languages[$i]['id'];

          $sql_data_array = array('pay_method' => tep_db_prepare_input($pay_method_array[$pay_method]));

          if ($action == 'insert') {
            if (empty($pay_methods_id)) {
              $next_id_query = tep_db_query("select max(pay_methods_id) as pay_methods_id from " . TABLE_ORDERS_PAY_METHODS . "");
              $next_id = tep_db_fetch_array($next_id_query);
              $pay_methods_id = $next_id['pay_methods_id'] + 1;
            }

            $insert_sql_data = array('pay_methods_id' => $pay_methods_id,
                                     'pay_method' => $pay_method,
                                     'date_added' => 'now()',
                                     );

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            tep_db_perform(TABLE_ORDERS_PAY_METHODS, $sql_data_array);
          } elseif ($action == 'save') {
            tep_db_perform(TABLE_ORDERS_PAY_METHODS, $sql_data_array, 'update', "pay_methods_id = '" . (int)$pay_methods_id . "' and pay_method = '" . (int)$pay_method . "'");
          }
        }

               tep_redirect(tep_href_link(FILENAME_CREATE_ORDERS_ADMIN, 'page=' . $HTTP_GET_VARS['page'] . '&oID=' . $pay_methods_id));
        break;
      case 'deleteconfirm':
        $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);

        $orders_status_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'DEFAULT_ORDERS_STATUS_ID'");
        $orders_status = tep_db_fetch_array($orders_status_query);


        tep_db_query("delete from " . TABLE_ORDERS_PAY_METHODS . " where pay_methods_id = '" . tep_db_input($oID) . "'");

        tep_redirect(tep_href_link(FILENAME_CREATE_ORDERS_ADMIN, 'page=' . $HTTP_GET_VARS['page']));
        break;
      case 'delete':
        $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);

           $remove_status = true;
         
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
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
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
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CREATE_ORDERS_ADMIN; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $orders_status_query_raw = "select pay_methods_id, pay_method from " . TABLE_ORDERS_PAY_METHODS . " order by pay_methods_id";
  $orders_status_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $orders_status_query_raw, $orders_status_query_numrows);
  $orders_status_query = tep_db_query($orders_status_query_raw);
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    if ((!isset($HTTP_GET_VARS['oID']) || (isset($HTTP_GET_VARS['oID']) && ($HTTP_GET_VARS['oID'] == $orders_status['pay_methods_id']))) && !isset($oInfo) && (substr($action, 0, 3) != 'new')) {
      $oInfo = new objectInfo($orders_status);
    }

    if (isset($oInfo) && is_object($oInfo) && ($orders_status['pay_methods_id'] == $oInfo->pay_methods_id)) {
      echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CREATE_ORDERS_ADMIN, 'page=' . $HTTP_GET_VARS['page'] . '&oID=' . $oInfo->pay_methods_id . '&action=edit') . '\'">' . "\n";
    } else {
      echo '                  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CREATE_ORDERS_ADMIN, 'page=' . $HTTP_GET_VARS['page'] . '&oID=' . $orders_status['pay_methods_id']) . '\'">' . "\n";
    }

    if (DEFAULT_ORDERS_STATUS_ID == $orders_status['pay_methods_id']) {
      echo '                <td class="dataTableContent"><b>' . $orders_status['pay_method'] . ' (' . TEXT_DEFAULT . ')</b></td>' . "\n";
    } else {
      echo '                <td class="dataTableContent">' . $orders_status['pay_method'] . '</td>' . "\n";
    }
?>
                <td class="dataTableContent" align="right"><?php if (isset($oInfo) && is_object($oInfo) && ($orders_status['pay_methods_id'] == $oInfo->pay_methods_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_CREATE_ORDERS_ADMIN, 'page=' . $HTTP_GET_VARS['page'] . '&oID=' . $orders_status['pay_methods_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $orders_status_split->display_count($orders_status_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS_STATUS); ?></td>
                    <td class="smallText" align="right"><?php echo $orders_status_split->display_links($orders_status_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></td>
                  </tr>
<?php
  if (empty($action)) {
?>
                  <tr>
                    <td colspan="2" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_CREATE_ORDERS_ADMIN, 'page=' . $HTTP_GET_VARS['page'] . '&action=new') . '">' . tep_image_button('button_insert.gif', IMAGE_INSERT) . '</a>'; ?></td>
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

      $contents = array('form' => tep_draw_form('status', FILENAME_CREATE_ORDERS_ADMIN, 'page=' . $HTTP_GET_VARS['page'] . '&action=insert'));
      $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);

      $orders_status_inputs_string = '';
      $languages = tep_get_languages();
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
        $orders_status_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('pay_method[' . $languages[$i]['id'] . ']');
      }

      $contents[] = array('text' => '<br>' . TEXT_INFO_ORDERS_STATUS_NAME . $orders_status_inputs_string);
      $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_insert.gif', IMAGE_INSERT) . ' <a href="' . tep_href_link(FILENAME_CREATE_ORDERS_ADMIN, 'page=' . $HTTP_GET_VARS['page']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'edit':
     $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_PAYMENT . '</b>');
     $contents = array('form' => tep_draw_form('status', FILENAME_CREATE_ORDERS_ADMIN, 'page=' . $HTTP_GET_VARS['page'] . '&oID=' . $oInfo->pay_methods_id  . '&action=save'));
     $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
     $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT);

     $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_insert.gif', IMAGE_INSERT) . ' <a href="' . tep_href_link(FILENAME_CREATE_ORDERS_ADMIN, 'page=' . $HTTP_GET_VARS['page']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');

     $orders_status_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('pay_method[' . $languages[$i]['id'] . ']', tep_get_pay_method($oInfo->pay_methods_id, $languages[$i]['id']));


      $contents[] = array('text' => '<br>' . TEXT_INFO_ORDERS_STATUS_NAME . $orders_status_inputs_string);
      if (DEFAULT_ORDERS_STATUS_ID != $oInfo->pay_methods_id) $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . tep_href_link(FILENAME_CREATE_ORDERS_ADMIN, 'page=' . $HTTP_GET_VARS['page'] . '&oID=' . $oInfo->pay_methods_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_DELETE_INTRO . '</b>');

      $contents = array('form' => tep_draw_form('status', FILENAME_CREATE_ORDERS_ADMIN, 'page=' . $HTTP_GET_VARS['page'] . '&oID=' . $oInfo->pay_methods_id  . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $oInfo->pay_method . '</b>');
      if ($remove_status) $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_CREATE_ORDERS_ADMIN, 'page=' . $HTTP_GET_VARS['page'] . '&oID=' . $oInfo->pay_methods_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (isset($oInfo) && is_object($oInfo)) {
        $heading[] = array('text' => '<b>' . $oInfo->pay_method . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CREATE_ORDERS_ADMIN, 'page=' . $HTTP_GET_VARS['page'] . '&oID=' . $oInfo->pay_methods_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_CREATE_ORDERS_ADMIN, 'page=' . $HTTP_GET_VARS['page'] . '&oID=' . $oInfo->pay_methods_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');

        $orders_status_inputs_string = '';
       // $languages = tep_get_languages();
       // for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
       //   $orders_status_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_get_pay_method($oInfo->pay_methods_id, $languages[$i]['id']);
       // }

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
