<?php
/*
  $Id: faq_categories.php,v 1.1.1.1 2004/03/04 23:38:42 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');
// 备注添加删除
if($_GET['ajax']=="true"){
	include DIR_FS_CLASSES . 'Remark.class.php';
	$remark = new Remark('faq_categories');
	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
}

// define functions
require(DIR_WS_FUNCTIONS . 'faq.php');

// clean variables
$cID = '';
if (isset($HTTP_POST_VARS['cID']) && tep_not_null($HTTP_POST_VARS['cID'])) {
  $cID = (int)$HTTP_POST_VARS['cID'];
} elseif (isset($HTTP_GET_VARS['cID']) && tep_not_null($HTTP_GET_VARS['cID'])) {
  $cID = (int)$HTTP_GET_VARS['cID'];
}

$action = '';
if (isset($HTTP_POST_VARS['action']) && tep_not_null($HTTP_POST_VARS['action'])) {
  $action = tep_db_prepare_input($HTTP_POST_VARS['action']);
} elseif (isset($HTTP_GET_VARS['action']) && tep_not_null($HTTP_GET_VARS['action'])) {
  $action = tep_db_prepare_input($HTTP_GET_VARS['action']);
} 

$error = false;
$processed = false;

switch ($action) {
 case 'setflag':
   $status = tep_db_prepare_input($HTTP_GET_VARS['flag']);

   if ($status == '1') {
     tep_db_query("update " . TABLE_FAQ_CATEGORIES . " set categories_status = '1' where categories_id = '" . (int)$cID . "'");
   } elseif ($status == '0') {
     tep_db_query("update " . TABLE_FAQ_CATEGORIES . " set categories_status = '0' where categories_id = '" . (int)$cID . "'");
   }

   tep_redirect(tep_href_link(FILENAME_FAQ_CATEGORIES, '&cID=' . $cID));
   break;
 case 'insert':
 case 'update':
   $categories_sort_order = tep_db_prepare_input($HTTP_POST_VARS['categories_sort_order']);
   $categories_status = ((tep_db_prepare_input($HTTP_POST_VARS['categories_status']) == 'on') ? '1' : '0');

   $sql_data_array = array('categories_sort_order' => $categories_sort_order,
			   'categories_status' => $categories_status);

   if ($action == 'insert') {
     $insert_sql_data = array('categories_date_added' => 'now()');

     $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

     tep_db_perform(TABLE_FAQ_CATEGORIES, $sql_data_array);

     $cID = tep_db_insert_id();
   } elseif ($action == 'update') {
     $update_sql_data = array('categories_last_modified' => 'now()');

     $sql_data_array = array_merge($sql_data_array, $update_sql_data);

     tep_db_perform(TABLE_FAQ_CATEGORIES, $sql_data_array, 'update', "categories_id = '" . (int)$cID . "'");
   }

   $languages = tep_get_languages();
   for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
     $categories_name_array = $HTTP_POST_VARS['categories_name'];
     $categories_description_array = $HTTP_POST_VARS['categories_description'];

     $language_id = $languages[$i]['id'];

     $sql_data_array = array('categories_name' => tep_db_prepare_input($categories_name_array[$language_id]),
			     'categories_description' => tep_db_prepare_input($categories_description_array[$language_id]));

     if ($action == 'insert') {
       $insert_sql_data = array('categories_id' => $cID,
				'language_id' => $languages[$i]['id']);

       $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

       tep_db_perform(TABLE_FAQ_CATEGORIES_DESCRIPTION, $sql_data_array);
     } elseif ($action == 'update') {
       tep_db_perform(TABLE_FAQ_CATEGORIES_DESCRIPTION, $sql_data_array, 'update', "categories_id = '" . (int)$cID . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
     }
   }

   if ($categories_image = new upload('categories_image', DIR_FS_CATALOG_IMAGES)) {
     tep_db_query("update " . TABLE_FAQ_CATEGORIES . " set categories_image = '" . tep_db_input($categories_image->filename) . "' where categories_id = '" . (int)$cID . "'");
   }

   tep_redirect(tep_href_link(FILENAME_FAQ_CATEGORIES, '&cID=' . $cID));
   break;
 case 'delete_confirm':
   if (tep_not_null($cID)) {
     $faq_ids_query = tep_db_query("select faq_id from " . TABLE_FAQ_TO_CATEGORIES . " where categories_id = '" . (int)$cID . "'");

     while ($faq_ids = tep_db_fetch_array($faq_ids_query)) {
       tep_faq_remove_faq($faq_ids['faq_id']);
     }

     tep_faq_remove_category($cID);
   }

   tep_redirect(tep_href_link(FILENAME_FAQ_CATEGORIES));
   break;
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
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
$listrs = new Remark('faq_categories');
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
          <tr><?php echo tep_draw_form('search', FILENAME_FAQ_CATEGORIES, '', 'get'); 
          	if (isset($HTTP_GET_VARS[tep_session_name()])) {
							echo tep_draw_hidden_field(tep_session_name(), $HTTP_GET_VARS[tep_session_name()]);
						}
          ?>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td class="smallText" align="right"><?php echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search'); ?></td>
          </form></tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_NAME; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $search = '';
    if (isset($HTTP_GET_VARS['search']) && tep_not_null($HTTP_GET_VARS['search'])) {
      $keywords = tep_db_input(tep_db_prepare_input($HTTP_GET_VARS['search']));
      $search = " and icd.categories_name like '%" . $keywords . "%'";

      $categories_query_raw = "select ic.categories_id, ic.categories_image, ic.categories_status, ic.categories_sort_order, ic.categories_date_added, ic.categories_last_modified, icd.categories_name, icd.categories_description from " . TABLE_FAQ_CATEGORIES . " ic left join " . TABLE_FAQ_CATEGORIES_DESCRIPTION . " icd on ic.categories_id = icd.categories_id where icd.language_id = '" . (int)$languages_id . "'" . $search . " order by ic.categories_sort_order, icd.categories_name";
    } else {
      $categories_query_raw = "select ic.categories_id, ic.categories_image, ic.categories_status, ic.categories_sort_order, ic.categories_date_added, ic.categories_last_modified, icd.categories_name, icd.categories_description from " . TABLE_FAQ_CATEGORIES . " ic left join " . TABLE_FAQ_CATEGORIES_DESCRIPTION . " icd on ic.categories_id = icd.categories_id where icd.language_id = '" . (int)$languages_id . "' order by ic.categories_sort_order, icd.categories_name";
    }
    $categories_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $categories_query_raw, $categories_query_numrows);
    $categories_query = tep_db_query($categories_query_raw);
    while ($categories = tep_db_fetch_array($categories_query)) {
      if ((!isset($HTTP_GET_VARS['cID']) || (isset($HTTP_GET_VARS['cID']) && ($HTTP_GET_VARS['cID'] == $categories['categories_id']))) && !isset($cInfo)) {
        $faq_count_query = tep_db_query("select count(*) as categories_faq_count from " . TABLE_FAQ_TO_CATEGORIES . " where categories_id = '" . (int)$categories['categories_id'] . "'");
        $faq_count = tep_db_fetch_array($faq_count_query);

        $cInfo_array = array_merge($categories, $faq_count);
        $cInfo = new objectInfo($cInfo_array);
      }

      if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id)) {
        echo '          <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_FAQ_MANAGER, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->categories_id ) . '\'">' . "\n";
	//	 echo '          <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_FAQ_CATEGORIES, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->categories_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '          <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_FAQ_CATEGORIES, tep_get_all_get_params(array('cID')) . 'cID=' . $categories['categories_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_FAQ_MANAGER, 'cID=' . $categories['categories_id']) . '">' . tep_image(DIR_WS_ICONS . 'folder.gif', ICON_FOLDER) . '</a>&nbsp;'.$categories['categories_name']; ?></td>
                <td  class="dataTableContent" align="right">
<?php
      if ($categories['categories_status'] == '1') {
        echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_FAQ_CATEGORIES, 'action=setflag&flag=0&cID=' . $categories['categories_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_FAQ_CATEGORIES, 'action=setflag&flag=1&cID=' . $categories['categories_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
      }
?></td>
                <td class="dataTableContent" align="right"><?php if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_FAQ_CATEGORIES, tep_get_all_get_params(array('cID')) . 'cID=' . $categories['categories_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }
?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $categories_split->display_count($categories_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_FAQ_CATEGORIES); ?></td>
                    <td class="smallText" align="right"><?php echo $categories_split->display_links($categories_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'pages', 'x', 'y', 'cID'))); ?></td>
                  </tr>
                  <tr>
<?php
    if (isset($HTTP_GET_VARS['search']) && tep_not_null($HTTP_GET_VARS['search'])) {
?>
                    <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_FAQ_CATEGORIES) . '">' . tep_image_button('button_reset.gif', IMAGE_RESET) . '</a>'; ?></td>
                    <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_FAQ_CATEGORIES, 'page=' . $HTTP_GET_VARS['page'] . '&action=new') . '">' . tep_image_button('button_new_category.gif', IMAGE_NEW_CATEGORY) . '</a>'; ?></td>
<?php
    } else {
?>
                    <td align="right" colspan="2"><?php echo '<a href="' . tep_href_link(FILENAME_FAQ_CATEGORIES, 'page=' . $HTTP_GET_VARS['page'] . '&action=new') . '">' . tep_image_button('button_new_category.gif', IMAGE_NEW_CATEGORY) . '</a>'; ?></td>
<?php
    }
?>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'new':
      $heading[] = array('text' => '<b>' . TEXT_FAQ_HEADING_NEW_FAQ_CATEGORY . '</b>');

      $contents = array('form' => tep_draw_form('categories_new', FILENAME_FAQ_CATEGORIES, 'action=insert', 'post', 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_NEW_FAQ_CATEGORIES_INTRO);

      $category_inputs_string = '';
      $languages = tep_get_languages();
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
        $category_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']');
      }

      $category_description_inputs_string = '';
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
        $category_description_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;<br>' . tep_draw_textarea_field('categories_description[' . $languages[$i]['id'] . ']', 'soft', '40', '5');
      }

      $contents[] = array('text' => '<br>' . TEXT_FAQ_CATEGORIES_NAME . $category_inputs_string);
      $contents[] = array('text' => '<br>' . TEXT_FAQ_CATEGORIES_DESCRIPTION . $category_description_inputs_string);
      $contents[] = array('text' => '<br>' . TEXT_FAQ_CATEGORIES_IMAGE . '<br>' . tep_draw_file_field('categories_image'));
      $contents[] = array('text' => '<br>' . TEXT_FAQ_CATEGORIES_SORT_ORDER . '&nbsp;' . tep_draw_input_field('categories_sort_order', '', 'size="2"'));
      $contents[] = array('text' => '<br>' . TEXT_FAQ_CATEGORIES_STATUS . '&nbsp;&nbsp;' . tep_draw_radio_field('categories_status', 'on', true) . ' ' . TEXT_FAQ_CATEGORIES_STATUS_ENABLE . '&nbsp;&nbsp;' . tep_draw_radio_field('categories_status', 'off') . ' ' . TEXT_FAQ_CATEGORIES_STATUS_DISABLE);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_FAQ_CATEGORIES) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_FAQ_HEADING_EDIT_FAQ_CATEGORY . '</b>');

      $contents = array('form' => tep_draw_form('categories_edit', FILENAME_FAQ_CATEGORIES, 'action=update', 'post', 'enctype="multipart/form-data"') . tep_draw_hidden_field('cID', $cInfo->categories_id));
      $contents[] = array('text' => TEXT_EDIT_FAQ_CATEGORIES_INTRO);

      $category_inputs_string = '';
      $languages = tep_get_languages();
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
        $category_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']', tep_faq_get_category_name($cInfo->categories_id, $languages[$i]['id']));
      }

      $category_description_inputs_string = '';
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
        $category_description_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;<br>' . tep_draw_textarea_field('categories_description[' . $languages[$i]['id'] . ']', 'soft', '40', '5', tep_faq_get_category_description($cInfo->categories_id, $languages[$i]['id']));
      }

      $contents[] = array('text' => '<br>' . TEXT_FAQ_CATEGORIES_NAME . $category_inputs_string);
      $contents[] = array('text' => '<br>' . TEXT_FAQ_CATEGORIES_DESCRIPTION . $category_description_inputs_string);
      $contents[] = array('text' => '<br>' . tep_info_image($cInfo->categories_image, $cInfo->categories_name) . '<br>' . $cInfo->categories_image);
      $contents[] = array('text' => '<br>' . TEXT_FAQ_CATEGORIES_IMAGE . '<br>' . tep_draw_file_field('categories_image'));
      $contents[] = array('text' => '<br>' . TEXT_FAQ_CATEGORIES_SORT_ORDER . '&nbsp;' . tep_draw_input_field('categories_sort_order', $cInfo->categories_sort_order, 'size="2"'));
      $contents[] = array('text' => '<br>' . TEXT_FAQ_CATEGORIES_STATUS . '&nbsp;&nbsp;' . tep_draw_radio_field('categories_status', 'on', ($cInfo->categories_status == '1') ? true : false) . ' ' . TEXT_FAQ_CATEGORIES_STATUS_ENABLE . '&nbsp;&nbsp;' . tep_draw_radio_field('categories_status', 'off', ($cInfo->categories_status == '0') ? true : false) . ' ' . TEXT_FAQ_CATEGORIES_STATUS_DISABLE);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_FAQ_CATEGORIES, 'cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_FAQ_HEADING_DELETE_FAQ_CATEGORY . '</b>');

      $contents = array('form' => tep_draw_form('categories_delete', FILENAME_FAQ_CATEGORIES, 'action=delete_confirm') . tep_draw_hidden_field('cID', $cInfo->categories_id));
      $contents[] = array('text' => TEXT_DELETE_FAQ_CATEGORIES_INTRO);
      $contents[] = array('text' => '<br><b>' . $cInfo->categories_name . '</b>');
      if ($cInfo->categories_faq_count > 0) $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_PAGES, $cInfo->categories_faq_count));
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_FAQ_CATEGORIES, 'cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (isset($cInfo) && is_object($cInfo)) {
        $heading[] = array('text' => '<b>' . $cInfo->categories_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_FAQ_CATEGORIES, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->categories_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_FAQ_CATEGORIES, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->categories_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');

        $contents[] = array('text' => '<br>' . tep_info_image($cInfo->categories_image, $cInfo->categories_name, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT) . '<br>' . $cInfo->categories_image);
        $contents[] = array('text' => '<br>' . TEXT_FAQ_CATEGORY_DESCRIPTION . ' ' . $cInfo->categories_description);
        $contents[] = array('text' => '<br>' . TEXT_DATE_FAQ_CATEGORY_CREATED . ' ' . tep_date_short($cInfo->categories_date_added));
        if (tep_not_null($cInfo->categories_last_modified)) {
          $contents[] = array('text' => '<br>' . TEXT_DATE_FAQ_CATEGORY_LAST_MODIFIED . ' ' . tep_date_short($cInfo->categories_last_modified));
        }
        $contents[] = array('text' => '<br>' . TEXT_FAQ_CATEGORY_COUNT . ' '  . $cInfo->categories_faq_count);
        $contents[] = array('text' => '<br>' . TEXT_FAQ_CATEGORY_SORT_ORDER . ' '  . $cInfo->categories_sort_order);
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
