<?php
/*
  $Id: salemaker.php,v 1.1.1.1 2004/03/04 23:38:57 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
define('AUTOCHECK', 'False');
define('DISPLAYTTT', 'True');

  require('includes/application_top.php');
  // 备注添加删除
  if($_GET['ajax']=="true"){
  	include DIR_FS_CLASSES . 'Remark.class.php';
  	$remark = new Remark('salemaker');
  	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
  }
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $specials_condition_array = array(array('id' => '0', 'text' => SPECIALS_CONDITION_DROPDOWN_0),
                                    array('id' => '1', 'text' => SPECIALS_CONDITION_DROPDOWN_1),
                                    array('id' => '2', 'text' => SPECIALS_CONDITION_DROPDOWN_2));

  $deduction_type_array = array(array('id' => '0', 'text' => DEDUCTION_TYPE_DROPDOWN_0),
                                array('id' => '1', 'text' => DEDUCTION_TYPE_DROPDOWN_1),
                                array('id' => '2', 'text' => DEDUCTION_TYPE_DROPDOWN_2));

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'setflag':
        $salemaker_data_array = array('sale_status' => tep_db_prepare_input($HTTP_GET_VARS['flag']),
	                                  'sale_date_last_modified' => 'now()',
	                                  'sale_date_status_change' => 'now()');

        tep_db_perform(TABLE_SALEMAKER_SALES, $salemaker_data_array, 'update', "sale_id = '" . tep_db_prepare_input($HTTP_GET_VARS['sID']) . "'");

        tep_redirect(tep_href_link(FILENAME_SALEMAKER, '', 'NONSSL'));
        break;
      case 'insert':
      case 'update':
// insert a new sale or update an existing sale

// Create a string of all affected (sub-)categories
        if (tep_not_null($categories)) {
	      $categories_selected = array();
          $categories_all = array();
          foreach(tep_db_prepare_input($categories) as $category_path) {
            $category = array_pop(explode('_', $category_path));
            $categories_selected[] = $category;
            $categories_all[] = $category;
            foreach(tep_get_category_tree($category) as $subcategory) {
              if ($subcategory['id'] != '0') {
                $categories_all[] = $subcategory['id'];
              }
            }
          }
          asort($categories_selected);
          $categories_selected_string = implode(',', array_unique($categories_selected));
          asort($categories_all);
          $categories_all_string = ',' . implode(',', array_unique($categories_all)) . ',';
        } else {
          $categories_selected_string = 'null';
          $categories_all_string = 'null';
        }

        $salemaker_sales_data_array = array('sale_name' => tep_db_prepare_input($HTTP_POST_VARS['name']),
                                            'sale_deduction_value' => tep_db_prepare_input($HTTP_POST_VARS['deduction']),
                                            'sale_deduction_type' => tep_db_prepare_input($HTTP_POST_VARS['type']),
                                            'sale_pricerange_from' => tep_db_prepare_input($HTTP_POST_VARS['from']),
                                            'sale_pricerange_to' => tep_db_prepare_input($HTTP_POST_VARS['to']),
                                            'sale_specials_condition' => tep_db_prepare_input($HTTP_POST_VARS['condition']),
                                            'sale_categories_selected' => $categories_selected_string,
                                            'sale_categories_all' => $categories_all_string,
                                            'sale_date_start' => ((tep_db_prepare_input($HTTP_POST_VARS['start']) == '') ? '0000-00-00' : tep_date_raw($HTTP_POST_VARS['start'])),
                                            'sale_date_end' => ((tep_db_prepare_input($HTTP_POST_VARS['end']) == '') ? '0000-00-00' : tep_date_raw($HTTP_POST_VARS['end'])));

        if ($action == 'insert') {
          $salemaker_sales['sale_status'] = 0;
          $salemaker_sales_data_array['sale_date_added'] = 'now()';
          $salemaker_sales_data_array['sale_date_last_modified'] = '0000-00-00';
          $salemaker_sales_data_array['sale_date_status_change'] = '0000-00-00';
          tep_db_perform(TABLE_SALEMAKER_SALES, $salemaker_sales_data_array, 'insert');
        } else {
	      $salemaker_sales_data_array['sale_date_last_modified'] = 'now()';
          tep_db_perform(TABLE_SALEMAKER_SALES, $salemaker_sales_data_array, 'update', "sale_id = '" . tep_db_input($HTTP_POST_VARS['sID']) . "'");
        }

        tep_redirect(tep_href_link(FILENAME_SALEMAKER, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $HTTP_POST_VARS['sID']));
        break;
      case 'copyconfirm':
        $newname = tep_db_prepare_input($HTTP_POST_VARS['newname']);
        if (tep_not_null($newname)) {
          $salemaker_sales_query = tep_db_query("select * from " . TABLE_SALEMAKER_SALES . " where sale_id = '" . tep_db_input($HTTP_GET_VARS['sID']) . "'");
          if (tep_db_num_rows($salemaker_sales_query)) {
            $salemaker_sales = tep_db_fetch_array($salemaker_sales_query);
            $salemaker_sales['sale_id'] = 'null';
            $salemaker_sales['sale_name'] = $newname;
            $salemaker_sales['sale_status'] = 0;
            $salemaker_sales['sale_date_added'] = 'now()';
            $salemaker_sales['sale_date_last_modified'] = '0000-00-00';
            $salemaker_sales['sale_date_status_change'] = '0000-00-00';

            tep_db_perform(TABLE_SALEMAKER_SALES, $salemaker_sales, 'insert');
          }
        }

        tep_redirect(tep_href_link(FILENAME_SALEMAKER, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . tep_db_insert_id()));
        break;
      case 'deleteconfirm':
	    $sale_id = tep_db_prepare_input($HTTP_GET_VARS['sID']);

        tep_db_query("delete from " . TABLE_SALEMAKER_SALES . " where sale_id = '" . (int)$sale_id . "'");

        tep_redirect(tep_href_link(FILENAME_SALEMAKER, 'page=' . $HTTP_GET_VARS['page']));
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
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<?php
  if ( ($action == 'new') || ($action == 'edit') ) {
?>
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>
<script language="JavaScript">
function RowClick(RowValue) {
  for (i=0; i<document.sale_form.length; i++) {
    if(document.sale_form.elements[i].type == 'checkbox') {
      if(document.sale_form.elements[i].value == RowValue) {
        if(document.sale_form.elements[i].disabled == false) {
         document.sale_form.elements[i].checked = !document.sale_form.elements[i].checked;
        }
      }
    }
  }
  SetCategories()
}

function CheckBoxClick() {
  if(this.disabled == false) {
    this.checked = !this.checked;
  }
  SetCategories()
}

function SetCategories() {
  for (i=0; i<document.sale_form.length; i++) {
    if(document.sale_form.elements[i].type == 'checkbox') {
      document.sale_form.elements[i].disabled = false;
	  document.sale_form.elements[i].onclick = CheckBoxClick;
      document.sale_form.elements[i].parentNode.parentNode.className = 'SaleMakerOver';
    }
  }
  change = true;
  while(change) {
    change = false;
    for (i=0; i<document.sale_form.length; i++) {
      if(document.sale_form.elements[i].type == 'checkbox') {
        currentcheckbox = document.sale_form.elements[i];
        currentrow = currentcheckbox.parentNode.parentNode;
        if ( (currentcheckbox.checked) && (currentrow.className == 'SaleMakerOver') ) {
          currentrow.className = 'SaleMakerSelected';
          for (j=0; j<document.sale_form.length; j++) {
            if(document.sale_form.elements[j].type == 'checkbox') {
              relatedcheckbox = document.sale_form.elements[j];
              relatedrow = relatedcheckbox.parentNode.parentNode;
              if( (relatedcheckbox != currentcheckbox) && (relatedcheckbox.value.substr(0, currentcheckbox.value.length) == currentcheckbox.value) ) {
                if(!relatedcheckbox.disabled) {
<?php
    if ( (defined('AUTOCHECK')) && (AUTOCHECK == 'True') ) {
?>
                  relatedcheckbox.checked = true;
<?php
    }
?>
                  relatedcheckbox.disabled = true;
                  relatedrow.className = 'SaleMakerDisabled';
                  change = true;
                }
              }
            }
          }
        }
      }
    }
  }
}

function session_win() {
  window.open("<?php echo tep_href_link(FILENAME_SALEMAKER_INFO); ?>","salemaker_info","height=460,width=600,scrollbars=yes,resizable=yes").focus();
}
</script>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetCategories();SetFocus();">
<div id="spiffycalendar" class="text"></div>
<?php
  } else {
?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<?php
  }
?>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('salemaker');
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
          </tr>
        </table></td>
      </tr>
<?php
  if ( ($action == 'new') || ($action == 'edit') ) {
    $form_action = 'insert';
    if ( ($action == 'edit') && ($HTTP_GET_VARS['sID']) ) {
	  $form_action = 'update';

      $salemaker_sales_query = tep_db_query("select sale_id, sale_status, sale_name, sale_deduction_value, sale_deduction_type, sale_pricerange_from, sale_pricerange_to, sale_specials_condition, sale_categories_selected, sale_categories_all, sale_date_start, sale_date_end, sale_date_added, sale_date_last_modified, sale_date_status_change from " . TABLE_SALEMAKER_SALES . " where sale_id = '" . (int)$HTTP_GET_VARS['sID'] . "'");
      $salemaker_sales = tep_db_fetch_array($salemaker_sales_query);

      $sInfo = new objectInfo($salemaker_sales);
    } else {
      $sInfo = new objectInfo(array());
    }
?>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
<script language="javascript">
//var StartDate = new ctlSpiffyCalendarBox("StartDate", "sale_form", "start", "btnDate1","<?php echo (($sInfo->sale_date_start == '0000-00-00') ? '' : tep_date_short($sInfo->sale_date_start)); ?>",scBTNMODE_CUSTOMBLUE);
//var EndDate = new ctlSpiffyCalendarBox("EndDate", "sale_form", "end", "btnDate2","<?php echo (($sInfo->sale_date_end == '0000-00-00') ? '' : tep_date_short($sInfo->sale_date_end)); ?>",scBTNMODE_CUSTOMBLUE);
</script>
      <tr><form name="sale_form" <?php echo 'action="' . tep_href_link(FILENAME_SALEMAKER, tep_get_all_get_params(array('action', 'info', 'sID')) . 'action=' . $form_action, 'NONSSL') . '"'; ?> method="post"><?php if ($form_action == 'update') echo tep_draw_hidden_field('sID', $HTTP_GET_VARS['sID']); ?>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo TEXT_SALEMAKER_POPUP; ?></td>
            <td class="main" align="right" valign="top"><br><?php echo (($form_action == 'insert') ? tep_image_submit('button_insert.gif', IMAGE_INSERT) : tep_image_submit('button_update.gif', IMAGE_UPDATE)). '&nbsp;&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_SALEMAKER, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $HTTP_GET_VARS['sID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo TEXT_SALEMAKER_NAME; ?>&nbsp;</td>
            <td class="main"><?php echo tep_draw_input_field('name', $sInfo->sale_name, 'size="37"'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_SALEMAKER_DEDUCTION; ?>&nbsp;</td>
            <td class="main"><?php echo tep_draw_input_field('deduction', $sInfo->sale_deduction_value, 'size="8"') . TEXT_SALEMAKER_DEDUCTION_TYPE . tep_draw_pull_down_menu('type', $deduction_type_array, $sInfo->sale_deduction_type); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_SALEMAKER_PRICERANGE_FROM; ?>&nbsp;</td>
            <td class="main"><?php echo tep_draw_input_field('from', $sInfo->sale_pricerange_from, 'size="8"') . TEXT_SALEMAKER_PRICERANGE_TO . tep_draw_input_field('to', $sInfo->sale_pricerange_to, 'size="8"'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_SALEMAKER_SPECIALS_CONDITION; ?>&nbsp;</td>
            <td class="main"><?php echo tep_draw_pull_down_menu('condition', $specials_condition_array, $sInfo->sale_specials_condition); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_SALEMAKER_DATE_START; ?>&nbsp;</td>
            <td class="main"><?php echo tep_draw_input_field('start', ($sInfo->sale_date_start == '0000-00-00') ? '' : tep_date_short($sInfo->sale_date_start), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"');?><script language="javascript">//StartDate.writeControl(); StartDate.dateFormat="<?php echo DATE_FORMAT_SPIFFYCAL; ?>";</script>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_SALEMAKER_DATE_END; ?>&nbsp;</td>
            <td class="main"><?php echo tep_draw_input_field('end', ($sInfo->sale_date_end == '0000-00-00') ? '' : tep_date_short($sInfo->sale_date_end), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"');?><script language="javascript">//EndDate.writeControl(); EndDate.dateFormat="<?php echo DATE_FORMAT_SPIFFYCAL; ?>";</script>
          </tr>
        </table></td>
      </tr>
    </td>
    <td><table border="0" cellspacing="0" cellpadding="2">
<?php
    $categories_query = tep_db_query("select c.categories_id, c.parent_id, cd.categories_name from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "'");
    $categories_array = array();
    while($categories = tep_db_fetch_array($categories_query)) {
      $categories_array[] = $categories;
    }
    $n = sizeof($categories_array);
    for($i = 0; $i < $n; $i++) {
      $categories_array[$i]['path'] = $categories_array[$i]['categories_id'];
      $categories_array[$i]['indent'] = 0;
	  $parent = $categories_array[$i]['parent_id'];
      while($parent != 0) {
        $categories_array[$i]['indent']++;
        for($j = 0; $j < $n; $j++) {
          if($categories_array[$j]['categories_id'] == $parent) {
            $categories_array[$i]['path'] = $parent . '_' . $categories_array[$i]['path'];
            $parent = $categories_array[$j]['parent_id'];
            break;
          }
        }
      }
      $categories_array[$i]['path'] = '0_' . $categories_array[$i]['path'];
    }

    $order_changed = true;
    while($order_changed) {
      $order_changed = false;
      for($i = 0, $n = (sizeof($categories_array) - 1); $i < $n; $i++) {
        if($categories_array[$i]['path'] > $categories_array[$i + 1]['path']) {
          $tmp = $categories_array[$i];
          $categories_array[$i] = $categories_array[$i + 1];
          $categories_array[$i + 1] = $tmp;
          $order_changed = true;
        }
      }
    }

    $categories_selected = explode(',', $sInfo->sale_categories_selected);
    if (tep_not_null($sInfo->sale_categories_selected)) {
      $selected = in_array(0, $categories_selected);
    } else {
      $selected = false;
    }

    echo "      <tr>\n";
    echo '        <td valign="bottom">' . tep_draw_separator('pixel_trans.gif', '4', '1') . tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif') . "</td>\n";
    echo '        <td class="main"><br>' . TEXT_SALEMAKER_ENTIRE_CATALOG . "</td>\n";
    echo "      </tr>\n";
	echo '      <tr onClick="RowClick(\'0\')">' . "\n";
    echo '        <td width="10">' . tep_draw_checkbox_field('categories[]', '0', $selected) . "</td>\n";
    echo '        <td>' . TEXT_SALEMAKER_TOP . "</td>\n";
    echo "      </tr>\n";
    echo "      <tr>\n";
    echo '        <td valign="bottom">' . tep_draw_separator('pixel_trans.gif', '4', '1') . tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif') . "</td>\n";
    echo '        <td class="main"><br>' . TEXT_SALEMAKER_CATEGORIES . "</td>\n";
    echo "      </tr>\n";

    foreach($categories_array as $category) {
	  if (tep_not_null($sInfo->sale_categories_selected)) {
        $selected = in_array($category['categories_id'], $categories_selected);
      } else {
        $selected = false;
      }
	  echo '      <tr onClick="RowClick(\'' . $category['path'] . '\')">' . "\n";
      echo '        <td width="10">' . tep_draw_checkbox_field('categories[]', $category['path'], $selected) . "</td>\n";
      echo '        <td>' . str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $category['indent']) . $category['categories_name'] . "</td>\n";
      echo '      </tr>' . "\n";
    }
?>
        </table></td>
      </form></tr>
<?php
  } else {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_SALE_NAME; ?></td>
                <td colspan="2"><table border="0" width="100%" cellpadding="0"cellspacing="2">
                  <tr>
                    <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_SALE_DEDUCTION; ?></td>
                  </tr>
                </table></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_SALE_DATE_START; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_SALE_DATE_END; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $salemaker_sales_query_raw = "select sale_id, sale_status, sale_name, sale_deduction_value, sale_deduction_type, sale_pricerange_from, sale_pricerange_to, sale_specials_condition, sale_categories_selected, sale_categories_all, sale_date_start, sale_date_end, sale_date_added, sale_date_last_modified, sale_date_status_change from " . TABLE_SALEMAKER_SALES . " order by sale_name";
    $salemaker_sales_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $salemaker_sales_query_raw, $salemaker_sales_query_numrows);
    $salemaker_sales_query = tep_db_query($salemaker_sales_query_raw);
    while ($salemaker_sales = tep_db_fetch_array($salemaker_sales_query)) {
      if ((!isset($HTTP_GET_VARS['sID']) || (isset($HTTP_GET_VARS['sID']) && ($HTTP_GET_VARS['sID'] == $salemaker_sales['sale_id']))) && !isset($sInfo)) {
        $sInfo_array = $salemaker_sales;
        $sInfo = new objectInfo($sInfo_array);
      }

      if (isset($sInfo) && is_object($sInfo) && ($salemaker_sales['sale_id'] == $sInfo->sale_id)) {
        echo '                  <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_SALEMAKER, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $sInfo->specials_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '                  <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_SALEMAKER, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $specials['specials_id']) . '\'">' . "\n";
      }
?>
                <td  class="dataTableContent" align="left"><?php echo $salemaker_sales['sale_name']; ?></td>
                <td  class="dataTableContent" align="right"><?php echo $salemaker_sales['sale_deduction_value']; ?></td>
                <td  class="dataTableContent" align="left"><?php echo $deduction_type_array[$salemaker_sales['sale_deduction_type']]['text']; ?></td>
                <td  class="dataTableContent" align="center"><?php echo (($salemaker_sales['sale_date_start'] == '0000-00-00') ? TEXT_SALEMAKER_IMMEDIATELY : tep_date_short($salemaker_sales['sale_date_start'])); ?></td>
                <td  class="dataTableContent" align="center"><?php echo (($salemaker_sales['sale_date_end'] == '0000-00-00') ? TEXT_SALEMAKER_NEVER : tep_date_short($salemaker_sales['sale_date_end'])); ?></td>
                <td  class="dataTableContent" align="center">
<?php
      if ($salemaker_sales['sale_status'] == '1') {
        echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_SALEMAKER, 'action=setflag&flag=0&sID=' . $salemaker_sales['sale_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_SALEMAKER, 'action=setflag&flag=1&sID=' . $salemaker_sales['sale_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
      }
?></td>
                <td class="dataTableContent" align="right"><?php if ( (is_object($sInfo)) && ($salemaker_sales['sale_id'] == $sInfo->sale_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_SALEMAKER, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $salemaker_sales['sale_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
      </tr>
<?php
    }
?>
              <tr>
                <td colspan="7"><table border="0" width="100%" cellpadding="0"cellspacing="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $salemaker_sales_split->display_count($salemaker_sales_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_SALES); ?></td>
                    <td class="smallText" align="right"><?php echo $salemaker_sales_split->display_links($salemaker_sales_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></td>
                  </tr>
<?php
  if (empty($action)) {
?>
                  <tr>
                    <td colspan="2" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_SALEMAKER, 'page=' . $HTTP_GET_VARS['page'] . '&action=new') . '">' . tep_image_button('button_new_sale.gif', IMAGE_NEW_SALE) . '</a>'; ?></td>
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
    case 'copy':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_COPY_SALE . '</b>');

      $contents = array('form' => tep_draw_form('sales', FILENAME_SALEMAKER, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $sInfo->sale_id . '&action=copyconfirm'));
      $contents[] = array('text' => sprintf(TEXT_INFO_COPY_INTRO, $sInfo->sale_name));
      $contents[] = array('text' => '<br>&nbsp;' . tep_draw_input_field('newname', $sInfo->sale_name . '_', 'size="31"'));
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_copy.gif', IMAGE_COPY) . '&nbsp;<a href="' . tep_href_link(FILENAME_SALEMAKER, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $sInfo->sale_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_SALE . '</b>');

      $contents = array('form' => tep_draw_form('sales', FILENAME_SALEMAKER, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $sInfo->sale_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $sInfo->sale_name . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . '&nbsp;<a href="' . tep_href_link(FILENAME_SALEMAKER, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $sInfo->sale_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (is_object($sInfo)) {
        $heading[] = array('text' => '<b>' . $sInfo->sale_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_SALEMAKER, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $sInfo->sale_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_SALEMAKER, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $sInfo->sale_id . '&action=copy') . '">' . tep_image_button('button_copy_to.gif', IMAGE_COPY_TO) . '</a> <a href="' . tep_href_link(FILENAME_SALEMAKER, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $sInfo->sale_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_DATE_ADDED . ' ' . tep_date_short($sInfo->sale_date_added));
        $contents[] = array('text' => '' . TEXT_INFO_DATE_MODIFIED . ' ' . (($sInfo->sale_date_last_modified == '0000-00-00') ? TEXT_SALEMAKER_NEVER : tep_date_short($sInfo->sale_date_last_modified)));
        $contents[] = array('text' => '' . TEXT_INFO_DATE_STATUS_CHANGE . ' ' . (($sInfo->sale_date_status_change == '0000-00-00') ? TEXT_SALEMAKER_NEVER : tep_date_short($sInfo->sale_date_status_change)));

        $contents[] = array('text' => '<br>' . TEXT_INFO_DEDUCTION . ' ' . $sInfo->sale_deduction_value . ' ' . $deduction_type_array[$sInfo->sale_deduction_type]['text']);
        $contents[] = array('text' => '' . TEXT_INFO_PRICERANGE_FROM . ' ' . $currencies->format($sInfo->sale_pricerange_from) . TEXT_INFO_PRICERANGE_TO . $currencies->format($sInfo->sale_pricerange_to));
        $contents[] = array('text' => '<table class="dataTableContent" border="0" width="100%" cellspacing="0" cellpadding="0"><tr><td valign="top">' . TEXT_INFO_SPECIALS_CONDITION . '&nbsp;</td><td>' . $specials_condition_array[$sInfo->sale_specials_condition]['text'] . '</td></tr></table>');

        $contents[] = array('text' => '<br>' . TEXT_INFO_DATE_START . ' ' . (($sInfo->sale_date_start == '0000-00-00') ? TEXT_SALEMAKER_IMMEDIATELY : tep_date_short($sInfo->sale_date_start)));
        $contents[] = array('text' => '' . TEXT_INFO_DATE_END . ' ' . (($sInfo->sale_date_end == '0000-00-00') ? TEXT_SALEMAKER_NEVER : tep_date_short($sInfo->sale_date_end)));
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
<?php
if ( (defined('DISPLAYTTT')) && (DISPLAYTTT == 'True') ) {
?>
<table border="0" width="100%" cellpadding="0"cellspacing="2">
  <tr>
    <td align="center"><?php echo tep_draw_separator('pixel_trans.gif', '4', '1') . tep_image(DIR_WS_IMAGES . 'thinktank.jpg'); ?></td>
  </tr>
</table>
<?php
}
?>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
