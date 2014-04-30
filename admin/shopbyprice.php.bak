<?php
/*
  $Id: shopbyprice.php,v 1.1.1.1 2004/03/04 23:39:00 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  // 备注添加删除
  if($_GET['ajax']=="true"){
  	include DIR_FS_CLASSES . 'Remark.class.php';
  	$remark = new Remark('shopbyprice');
  	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
  }
  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');
  $error_message = '';
  if (!isset($HTTP_GET_VARS['oID'])) {
    $oid = 1;
  } else {
    $oid = $HTTP_GET_VARS['oID'];
  }

  if ($action == 'save') {
    if ($oid == 1) {
      $sbp_ranges = tep_db_prepare_input($HTTP_POST_VARS['sbp_ranges']);
      if (is_numeric($sbp_ranges)) {
        tep_db_query('update ' . TABLE_CONFIGURATION . ' set configuration_value = "' . (int)$sbp_ranges . '" where configuration_key = "MODULE_SHOPBYPRICE_RANGES" ');
        MCache::update_main_config();//update cache by vincent
        define('MODULE_SHOPBYPRICE_RANGES', $sbp_ranges);
      } else {
        $error_message .= TEXT_EDIT_ERROR_RANGES;
      }
      tep_db_query('update ' . TABLE_CONFIGURATION . ' set configuration_value = "' . $HTTP_POST_VARS['configuration_value'] . '" where configuration_key = "MODULE_SHOPBYPRICE_OVER" ');
	  MCache::update_main_config();//update cache by vincent
      if ($error_message != '') {
        $action = 'edit';
      }
    } else {
      $sbp_input_array = $HTTP_POST_VARS['sbp_range'];
      $sbp_array[0] = tep_db_prepare_input($sbp_input_array[0]);
      for ($i = 1, $ii = MODULE_SHOPBYPRICE_RANGES; $i < $ii; $i++) {
        $sbp_array[$i] = tep_db_prepare_input($sbp_input_array[$i]);
        if (! is_numeric($sbp_array[$i])) {
          $error_message .= TEXT_EDIT_ERROR_NUMERIC;
        } elseif ($sbp_array[$i] <= $sbp_array[$i - 1]) {
          $error_message .= TEXT_EDIT_ERROR_RANGE;
        }
      }
      if ($error_message == '') {
        $serial_array = serialize($sbp_array);
        $text = tep_db_input($serial_array);
        tep_db_query('update ' . TABLE_CONFIGURATION . ' set configuration_value = "' . $text . '" where configuration_key = "MODULE_SHOPBYPRICE_RANGE" ');
       MCache::update_main_config();//update cache by vincent
        define('MODULE_SHOPBYPRICE_RANGE', $serial_array);
      } else {
        $action = 'edit';
      }
    }
  }

  $sbp_array = unserialize(MODULE_SHOPBYPRICE_RANGE);
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
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('shopbyprice');
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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_OPTIONS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php

  if ($oid == 1) {
    echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_SHOPBYPRICE, 'oID=1&action=edit') . '\'">' . "\n";
  } else {
    echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_SHOPBYPRICE, 'oID=1') . '\'">' . "\n";
  }
?>
                <td class="dataTableContent"><?php echo TEXT_INFO_OPTION_1; ?></td>
                <td class="dataTableContent" align="right"><?php if ($HTTP_GET_VARS['oID'] == 1) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_SHOPBYPRICE, 'oID=1') . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  if ($oid == 2) {
      echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_SHOPBYPRICE, 'oID=2&action=edit') . '\'">' . "\n";
    } else {
      echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_SHOPBYPRICE, 'oID=2') . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo TEXT_INFO_OPTION_2; ?></td>
                <td class="dataTableContent" align="right"><?php if ($HTTP_GET_VARS['oID'] == 2) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_SHOPBYPRICE, 'oID=2') . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'edit':
      if ($oid == 1) {
        $heading[] = array('text' => '<b>' . TEXT_EDIT_HEADING_OPTIONS . '</b>');
        $contents = array('form' => tep_draw_form('sbp_options', FILENAME_SHOPBYPRICE, 'oID=1&action=save'));
        if ($error_message != '') {
          $contents[] = array('text' => '<font color="red">' . $error_message . '</font>');
        }
        $contents[] = array('text' => TEXT_EDIT_OPTIONS_INTRO);
        $contents[] = array('text' => '<br>' . TEXT_INFO_RANGES . '<br>' . tep_draw_input_field('sbp_ranges', MODULE_SHOPBYPRICE_RANGES));
        $contents[] = array('text' => '<br>' . TEXT_INFO_OVER . '<br>' . tep_cfg_select_option(array('True', 'False'),MODULE_SHOPBYPRICE_OVER));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_SHOPBYPRICE, 'oID=1') . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      } elseif (MODULE_SHOPBYPRICE_RANGES > 0) {
        $heading[] = array('text' => '<b>' . TEXT_EDIT_HEADING_RANGE . '</b>');
        $contents = array('form' => tep_draw_form('sbp_options', FILENAME_SHOPBYPRICE, 'oID=2&action=save'));
        if ($error_message != '') {
          $contents[] = array('text' => '<font color="red">' . $error_message . '</font>');
        }
        $contents[] = array('text' => TEXT_EDIT_RANGE_INTRO);
        $contents[] = array('text' => '<br>' . TEXT_INFO_UNDER . tep_draw_input_field('sbp_range[0]', $sbp_array[0]));
        for ($i = 1, $ii = MODULE_SHOPBYPRICE_RANGES; $i < $ii; $i++) {
          $contents[] = array('text' => '<br>' . TEXT_INFO_TO . tep_draw_input_field('sbp_range['.$i.']', $sbp_array[$i]));
        }
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_SHOPBYPRICE, 'oID=1') . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      }
      break;
    default:
      if ($oid == 1) {
        $heading[] = array('text' => '<b>' . TEXT_EDIT_HEADING_OPTIONS . '</b>');
        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_SHOPBYPRICE, 'oID=1&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_RANGES . ' ' . MODULE_SHOPBYPRICE_RANGES);
        $contents[] = array('text' => '' . TEXT_INFO_OVER . ' ' . MODULE_SHOPBYPRICE_OVER);
        $contents[] = array('text' => '<br>' . TEXT_INFO_OPTIONS_DESCRIPTION . '<br>' . $tcInfo->tax_class_description);
      } else {
        $heading[] = array('text' => '<b>' . TEXT_EDIT_HEADING_RANGE . '</b>');
        if (! MODULE_SHOPBYPRICE_RANGES > 0) {
          $contents[] = array('align' => 'center', 'text' => TEXT_INFO_ZERORANGE);
        } elseif (MODULE_SHOPBYPRICE_RANGE == '') {
          $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_SHOPBYPRICE, 'oID=2&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a>');
          $contents[] = array('align' => 'center', 'text' => TEXT_INFO_NORANGE);
        } else {
          $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_SHOPBYPRICE, 'oID=2&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a>');
          $contents[] = array('text' => '<br>' . TEXT_INFO_UNDER . $sbp_array[0]);
          for ($i = 1, $ii = count($sbp_array); $i < $ii; $i++) {
            $contents[] = array('text' => '<br>' . TEXT_INFO_TO . $sbp_array[$i]);
          }
          if (MODULE_SHOPBYPRICE_OVER) {
            $contents[] = array('text' => '<br>' . $sbp_array[$i-1] . TEXT_INFO_ABOVE);
          }
        }
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
