<?php
/*
  $Id: countries.php,v 1.1.1.1 2004/03/04 23:38:18 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  // 备注添加删除
  if($_GET['ajax']=="true"){
  	include DIR_FS_CLASSES . 'Remark.class.php';
  	$remark = new Remark('countries');
  	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
  }
  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'insert':
        $countries_name = tep_db_prepare_input($HTTP_POST_VARS['countries_name']);
        $countries_iso_code_2 = tep_db_prepare_input($HTTP_POST_VARS['countries_iso_code_2']);
        $countries_iso_code_3 = tep_db_prepare_input($HTTP_POST_VARS['countries_iso_code_3']);
        $address_format_id = tep_db_prepare_input($HTTP_POST_VARS['address_format_id']);
        $countries_tel_code = tep_db_prepare_input($HTTP_POST_VARS['countries_tel_code']);

        tep_db_query("insert into " . TABLE_COUNTRIES . " (countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id,countries_tel_code) values ('" . tep_db_input($countries_name) . "', '" . tep_db_input($countries_iso_code_2) . "', '" . tep_db_input($countries_iso_code_3) . "', '" . (int)$address_format_id . "','".tep_db_input($countries_tel_code)."')");

        tep_redirect(tep_href_link(FILENAME_COUNTRIES,tep_get_all_get_params(array('action')) ));
        break;
      case 'save':
        $countries_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);
        $countries_name = tep_db_prepare_input($HTTP_POST_VARS['countries_name']);
        $countries_iso_code_2 = tep_db_prepare_input($HTTP_POST_VARS['countries_iso_code_2']);
        $countries_iso_code_3 = tep_db_prepare_input($HTTP_POST_VARS['countries_iso_code_3']);
        $address_format_id = tep_db_prepare_input($HTTP_POST_VARS['address_format_id']);
        $countries_tel_code = tep_db_prepare_input($HTTP_POST_VARS['countries_tel_code']);

        tep_db_query("update " . TABLE_COUNTRIES . " set countries_name = '" . tep_db_input($countries_name) . "', countries_iso_code_2 = '" . tep_db_input($countries_iso_code_2) . "', countries_iso_code_3 = '" . tep_db_input($countries_iso_code_3) . "', address_format_id = '" . (int)$address_format_id . "', countries_tel_code='".tep_db_input($countries_tel_code)."' where countries_id = '" . (int)$countries_id . "'");

        tep_redirect(tep_href_link(FILENAME_COUNTRIES, tep_get_all_get_params(array('action','page','cID')).'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $countries_id));
        break;
      case 'deleteconfirm':
        $countries_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);

        tep_db_query("delete from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$countries_id . "'");

        tep_redirect(tep_href_link(FILENAME_COUNTRIES, tep_get_all_get_params(array('action','page')).'page=' . $HTTP_GET_VARS['page']));
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
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!--
 onLoad="SetFocus();"
 header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('countries');
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
	  	<td class="dataTableContent"><?php tep_get_atoz_filter_links(FILENAME_COUNTRIES); ?></td>
	  </tr>
	  <tr><td height="5"></td></tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" width="400"><?php echo TABLE_HEADING_COUNTRY_NAME; ?>
				<?php echo '<a href="' . tep_href_link(FILENAME_COUNTRIES, tep_get_all_get_params(array('sort','order','page','cID')).'sort=country_name&order=ascending', 'NONSSL').'"><img src="images/arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_COUNTRIES, tep_get_all_get_params(array('sort','order','page','cID')).'sort=country_name&order=decending', 'NONSSL').'"><img src="images/arrow_down.gif" border="0"></a>&nbsp;'; ?>
				</td>
                <td class="dataTableHeadingContent" align="center" width="150"><?php echo TABLE_HEADING_COUNTRY_CODES; ?>
				<?php echo '<a href="' . tep_href_link(FILENAME_COUNTRIES, tep_get_all_get_params(array('sort','order','page','cID')).'sort=iso_code2&order=ascending', 'NONSSL').'"><img src="images/arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_COUNTRIES, tep_get_all_get_params(array('sort','order','page','cID')).'sort=iso_code2&order=decending', 'NONSSL').'"><img src="images/arrow_down.gif" border="0"></a>&nbsp;'; ?>
				</td>
				<td class="dataTableHeadingContent" align="center" width="150"><?php echo TABLE_HEADING_COUNTRY_CODES; ?>
				<?php echo '<a href="' . tep_href_link(FILENAME_COUNTRIES, tep_get_all_get_params(array('sort','order','page','cID')).'sort=iso_code3&order=ascending', 'NONSSL').'"><img src="images/arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_COUNTRIES, tep_get_all_get_params(array('sort','order','page','cID')).'sort=iso_code3&order=decending', 'NONSSL').'"><img src="images/arrow_down.gif" border="0"></a>&nbsp;'; ?>
				</td>
				<td class="dataTableHeadingContent" align="left" width="200"><?php echo  TABLE_HEADING_COUNTRY_TEL_CODE; ?>
				<?php echo '<a href="' . tep_href_link(FILENAME_COUNTRIES, tep_get_all_get_params(array('sort','order','page','cID')).'sort=tel_code&order=ascending', 'NONSSL').'"><img src="images/arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_COUNTRIES, tep_get_all_get_params(array('sort','order','page','cID')).'sort=tel_code&order=decending', 'NONSSL').'"><img src="images/arrow_down.gif" border="0"></a>&nbsp;'; ?>
				</td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php

	if($_GET["sort"] == 'country_name') {
	   if($_GET["order"] == 'ascending') {
			$sortorder .= 'countries_name asc ';
	  } else {
			$sortorder .= 'countries_name desc ';
	  }
	}
	else if($_GET["sort"] == 'iso_code2') {
	   if($_GET["order"] == 'ascending') {
			$sortorder .= 'countries_iso_code_2 asc ';
	  } else {
			$sortorder .= 'countries_iso_code_2 desc ';
	  }
	}
	else if($_GET["sort"] == 'iso_code3') {
	   if($_GET["order"] == 'ascending') {
			$sortorder .= 'countries_iso_code_3 asc ';
	  } else {
			$sortorder .= 'countries_iso_code_3 desc ';
	  }
	}else if($_GET["sort"] == 'tel_code') {
	   if($_GET["order"] == 'ascending') {
			$sortorder .= 'countries_tel_code asc ';
	  } else {
			$sortorder .= 'countries_tel_code desc ';
	  }
	}
	else
	{
		$sortorder .= 'countries_name ';
	}
	
	
	if(isset($_GET['filter_search']) && $_GET['filter_search']!=''){
		$filter_search_query = " and countries_name like '".$_GET['filter_search']."%'";
	}
	
  $countries_query_raw = "select countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id,countries_tel_code from " . TABLE_COUNTRIES . " where countries_id>0 ".$filter_search_query." order by ".$sortorder."";
  $countries_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $countries_query_raw, $countries_query_numrows);
  $countries_query = tep_db_query($countries_query_raw);
  while ($countries = tep_db_fetch_array($countries_query)) {
    if ((!isset($HTTP_GET_VARS['cID']) || (isset($HTTP_GET_VARS['cID']) && ($HTTP_GET_VARS['cID'] == $countries['countries_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
      $cInfo = new objectInfo($countries);
    }

    if (isset($cInfo) && is_object($cInfo) && ($countries['countries_id'] == $cInfo->countries_id)) {
      echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_COUNTRIES, tep_get_all_get_params(array('action','page','cID')).'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->countries_id . '&action=edit') . '\'">' . "\n";
    } else {
      echo '                  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_COUNTRIES, tep_get_all_get_params(array('action','page','cID')).'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $countries['countries_id']) . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo $countries['countries_name']; ?></td>
                <td class="dataTableContent" align="center" ><?php echo $countries['countries_iso_code_2']; ?></td>
                <td class="dataTableContent" align="center" ><?php echo $countries['countries_iso_code_3']; ?></td>
                <td class="dataTableContent" align="left" ><?php echo $countries['countries_tel_code']; ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($cInfo) && is_object($cInfo) && ($countries['countries_id'] == $cInfo->countries_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_COUNTRIES,tep_get_all_get_params(array('action','page','cID')). 'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $countries['countries_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $countries_split->display_count($countries_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_COUNTRIES); ?></td>
                    <td class="smallText" align="right"><?php echo $countries_split->display_links($countries_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'],tep_get_all_get_params(array('page','x','y'))); ?></td>
                  </tr>
<?php
  if (empty($action)) {
?>
                  <tr>
                    <td colspan="2" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_COUNTRIES,tep_get_all_get_params(array('action','page')). 'page=' . $HTTP_GET_VARS['page'] . '&action=new') . '">' . tep_image_button('button_new_country.gif', IMAGE_NEW_COUNTRY) . '</a>'; ?></td>
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
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_COUNTRY . '</b>');

      $contents = array('form' => tep_draw_form('countries', FILENAME_COUNTRIES, tep_get_all_get_params(array('action','page')).'page=' . $HTTP_GET_VARS['page'] . '&action=insert'));
      $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_NAME . '<br>' . tep_draw_input_field('countries_name'));
      $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_CODE_2 . '<br>' . tep_draw_input_field('countries_iso_code_2'));
      $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_CODE_3 . '<br>' . tep_draw_input_field('countries_iso_code_3'));
      $contents[] = array('text' => '<br>' .TEXT_INFO_COUNTRY_TEL_CODE .'<br>' . tep_draw_input_field('countries_tel_code'));
      $contents[] = array('text' => '<br>' . TEXT_INFO_ADDRESS_FORMAT . '<br>' . tep_draw_pull_down_menu('address_format_id', tep_get_address_formats()));
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_insert.gif', IMAGE_INSERT) . '&nbsp;<a href="' . tep_href_link(FILENAME_COUNTRIES,tep_get_all_get_params(array('action','page')). 'page=' . $HTTP_GET_VARS['page']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_COUNTRY . '</b>');

      $contents = array('form' => tep_draw_form('countries', FILENAME_COUNTRIES, tep_get_all_get_params(array('action','page','cID')).'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->countries_id . '&action=save'));
      $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_NAME . '<br>' . tep_draw_input_field('countries_name', $cInfo->countries_name));
      $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_CODE_2 . '<br>' . tep_draw_input_field('countries_iso_code_2', $cInfo->countries_iso_code_2));
      $contents[] = array('text' => '<br>' .TEXT_INFO_COUNTRY_TEL_CODE . '<br>' . tep_draw_input_field('countries_iso_code_3', $cInfo->countries_iso_code_3));
      $contents[] = array('text' => '<br>Country Tel Code <br>' . tep_draw_input_field('countries_tel_code', $cInfo->countries_tel_code));
      $contents[] = array('text' => '<br>' . TEXT_INFO_ADDRESS_FORMAT . '<br>' . tep_draw_pull_down_menu('address_format_id', tep_get_address_formats(), $cInfo->address_format_id));
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_COUNTRIES, tep_get_all_get_params(array('action','page','cID')).'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->countries_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_COUNTRY . '</b>');

      $contents = array('form' => tep_draw_form('countries', FILENAME_COUNTRIES, tep_get_all_get_params(array('action','page','cID')).'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->countries_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $cInfo->countries_name . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_COUNTRIES, tep_get_all_get_params(array('action','page','cID')).'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->countries_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (is_object($cInfo)) {
        $heading[] = array('text' => '<b>' . $cInfo->countries_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_COUNTRIES, tep_get_all_get_params(array('action','page','cID')).'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->countries_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_COUNTRIES, tep_get_all_get_params(array('action','page','cID')).'page=' . $HTTP_GET_VARS['page'] . '&cID=' . $cInfo->countries_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_NAME . '<br>' . $cInfo->countries_name);
        $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_CODE_2 . ' ' . $cInfo->countries_iso_code_2);
        $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_CODE_3 . ' ' . $cInfo->countries_iso_code_3);
         $contents[] = array('text' =>'<br>' .TEXT_INFO_COUNTRY_TEL_CODE . ' ' . $cInfo->countries_tel_code );
        $contents[] = array('text' => '<br>' . TEXT_INFO_ADDRESS_FORMAT . ' ' . $cInfo->address_format_id);
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
