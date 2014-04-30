<?php
/*
  $Id: lodging.php,v 1.1.1.1 2004/03/04 23:38:44 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'insert':
      case 'save':
        if (isset($HTTP_GET_VARS['lID'])) $lodging_id = tep_db_prepare_input($HTTP_GET_VARS['lID']);
        $lodgingtype = tep_db_prepare_input($HTTP_POST_VARS['lodgingtype']);

        $sql_data_array = array('lodgingtype' => $lodgingtype);

        if ($action == 'insert') {
          $insert_sql_data = array('date_added' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

          tep_db_perform(TABLE_TOUR_LODGING_TYPE, $sql_data_array);
          $lodging_id = tep_db_insert_id();
        } elseif ($action == 'save') {
          $update_sql_data = array('last_modified' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $update_sql_data);

          tep_db_perform(TABLE_TOUR_LODGING_TYPE, $sql_data_array, 'update', "lodging_id = '" . (int)$lodging_id . "'");
        }
       

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('lodging');
        }

        tep_redirect(tep_href_link(FILENAME_LODGING_TYPE, (isset($HTTP_GET_VARS['page']) ? 'page=' . $HTTP_GET_VARS['page'] . '&' : '') . 'lID=' . $lodging_id));
        break;
      case 'deleteconfirm':
        $lodging_id = tep_db_prepare_input($HTTP_GET_VARS['lID']);

        tep_db_query("delete from " . TABLE_TOUR_LODGING_TYPE . " where lodging_id = '" . (int)$lodging_id . "'");
        /* tep_db_query("delete from " . TABLE_TOUR_LODGING_TYPE_INFO . " where lodging_id = '" . (int)$lodging_id . "'"); */

        
        if (USE_CACHE == 'true') {
          tep_reset_cache_block('lodging');
        }

        tep_redirect(tep_href_link(FILENAME_LODGING_TYPE, 'page=' . $HTTP_GET_VARS['page']));
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
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
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
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_LODGING_TYPE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $lodging_query_raw = "select lodging_id, lodgingtype, date_added, last_modified from " . TABLE_TOUR_LODGING_TYPE . " order by lodgingtype";
  $lodging_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $lodging_query_raw, $lodging_query_numrows);
  $lodging_query = tep_db_query($lodging_query_raw);
  while ($lodging = tep_db_fetch_array($lodging_query)) {
  if ((!isset($HTTP_GET_VARS['lID']) || (isset($HTTP_GET_VARS['lID']) && ($HTTP_GET_VARS['lID'] == $lodging['lodging_id']))) && !isset($lInfo) && (substr($action, 0, 3) != 'new')) {
  $lInfo = new objectInfo($lodging);
  }
    /* if ((!isset($HTTP_GET_VARS['lID']) || (isset($HTTP_GET_VARS['lID']) && ($HTTP_GET_VARS['lID'] == $lodging['lodging_id']))) && !isset($lInfo) && (substr($action, 0, 3) != 'new')) {
      $manufacturer_products_query = tep_db_query("select count(*) as products_count from " . TABLE_PRODUCTS . " where lodging_id = '" . (int)$lodging['lodging_id'] . "'");
      $manufacturer_products = tep_db_fetch_array($manufacturer_products_query);

      $lInfo_array = array_merge($lodging, $manufacturer_products);
      $lInfo = new objectInfo($lInfo_array);
    } */

    if (isset($lInfo) && is_object($lInfo) && ($lodging['lodging_id'] == $lInfo->lodging_id)) {
      echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_LODGING_TYPE, 'page=' . $HTTP_GET_VARS['page'] . '&lID=' . $lodging['lodging_id'] . '&action=edit') . '\'">' . "\n";
    } else {
      echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_LODGING_TYPE, 'page=' . $HTTP_GET_VARS['page'] . '&lID=' . $lodging['lodging_id']) . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo $lodging['lodgingtype']; ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($lInfo) && is_object($lInfo) && ($lodging['lodging_id'] == $lInfo->lodging_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . tep_href_link(FILENAME_LODGING_TYPE, 'page=' . $HTTP_GET_VARS['page'] . '&lID=' . $lodging['lodging_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $lodging_split->display_count($lodging_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_LODGING_TYPE); ?></td>
                    <td class="smallText" align="right"><?php echo $lodging_split->display_links($lodging_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></td>
                  </tr>
                </table></td>
              </tr>
<?php
  if (empty($action)) {
?>
              <tr>
                <td align="right" colspan="2" class="smallText"><?php echo '<a href="' . tep_href_link(FILENAME_LODGING_TYPE, 'page=' . $HTTP_GET_VARS['page'] . '&lID=' . $lInfo->lodging_id . '&action=new') . '">' . tep_image_button('button_insert.gif', IMAGE_INSERT) . '</a>'; ?></td>
              </tr>
<?php
  }
?>
            </table></td>
<?php
  $heading = array();
  $contents = array();

	
  switch ($action) {
    case 'new':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_NEW_LODGING_TYPE . '</b>');

      $contents = array('form' => tep_draw_form('lodging', FILENAME_LODGING_TYPE, 'action=insert', 'post', 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_NEW_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_LODGING_TYPE_NAME . '<br>' . tep_draw_input_field('lodgingtype'));
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_LODGING_TYPE, 'page=' . $HTTP_GET_VARS['page'] . '&lID=' . $HTTP_GET_VARS['lID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_EDIT_LODGING_TYPE . '</b>');

      $contents = array('form' => tep_draw_form('lodging', FILENAME_LODGING_TYPE, 'page=' . $HTTP_GET_VARS['page'] . '&lID=' . $lInfo->lodging_id . '&action=save', 'post', 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_EDIT_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_LODGING_TYPE_NAME . '<br>' . tep_draw_input_field('lodgingtype', $lInfo->lodgingtype));
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_LODGING_TYPE, 'page=' . $HTTP_GET_VARS['page'] . '&lID=' . $lInfo->lodging_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_DELETE_LODGING_TYPE . '</b>');

      $contents = array('form' => tep_draw_form('lodging', FILENAME_LODGING_TYPE, 'page=' . $HTTP_GET_VARS['page'] . '&lID=' . $lInfo->lodging_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $lInfo->lodgingtype . '</b>');
	  $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_LODGING_TYPE, 'page=' . $HTTP_GET_VARS['page'] . '&lID=' . $lInfo->lodging_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (isset($lInfo) && is_object($lInfo)) {
        $heading[] = array('text' => '<b>' . $lInfo->lodgingtype . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_LODGING_TYPE, 'page=' . $HTTP_GET_VARS['page'] . '&lID=' . $lInfo->lodging_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_LODGING_TYPE, 'page=' . $HTTP_GET_VARS['page'] . '&lID=' . $lInfo->lodging_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => '<br>' . $lInfo->lodgingtype);
		$contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' ' . tep_date_short($lInfo->date_added));
        if (tep_not_null($lInfo->last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . tep_date_short($lInfo->last_modified));
        
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
