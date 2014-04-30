<?php
/*
  $Id: products_attributes.php,v 1.3 2004/03/16 22:36:34 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  $languages = tep_get_languages();

  if ($HTTP_GET_VARS['action']) {
    $page_info = 'option_page=' . $HTTP_GET_VARS['option_page'] . '&value_page=' . $HTTP_GET_VARS['value_page'] . '&attribute_page=' . $HTTP_GET_VARS['attribute_page'];
    switch($HTTP_GET_VARS['action']) {
      case 'add_product_options':
        for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
          $option_name = $HTTP_POST_VARS['option_name'];
// WebMakers.com Added: Product Options Sort Order
          $products_options_sort_order = $HTTP_POST_VARS['products_options_sort_order'];
          tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS . " (products_options_id, products_options_name, language_id, products_options_sort_order) values ('" . $HTTP_POST_VARS['products_options_id'] . "', '" . $option_name[$languages[$i]['id']] . "', '" . $languages[$i]['id'] . "', '" . $products_options_sort_order[$languages[$i]['id']] . "')");
        }
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'add_product_option_values':
        $value_name_array = $HTTP_POST_VARS['value_name'];
        for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
          $value_name = tep_db_prepare_input($value_name_array[$languages[$i]['id']]);
          tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS_VALUES . " (products_options_values_id, language_id, products_options_values_name) values ('" . $HTTP_POST_VARS['value_id'] . "', '" . $languages[$i]['id'] . "', '" . tep_db_input($value_name) . "')");
        }
        tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " (products_options_id, products_options_values_id) values ('" . $HTTP_POST_VARS['option_id'] . "', '" . $HTTP_POST_VARS['value_id'] . "')");
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'add_product_attributes':
// BOF: WebMakers.com Added: Attribute Sorter
// OLD        tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES . " values ('', '" . $HTTP_POST_VARS['products_id'] . "', '" . $HTTP_POST_VARS['options_id'] . "', '" . $HTTP_POST_VARS['values_id'] . "', '" . $HTTP_POST_VARS['value_price'] . "', '" . $HTTP_POST_VARS['price_prefix'] . "', '" . $HTTP_POST_VARS['sort_order'] . "')");
        tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES . " values ('', '" . $HTTP_POST_VARS['products_id'] . "', '" . $HTTP_POST_VARS['options_id'] . "', '" . $HTTP_POST_VARS['values_id'] . "', '" . $HTTP_POST_VARS['value_price'] . "', '" . $HTTP_POST_VARS['price_prefix'] . "', '" . $HTTP_POST_VARS['sort_order'] . "')");
// EOF: WebMakers.com Added: Attribute Sorter
        $products_attributes_id = tep_db_insert_id();
        if ((DOWNLOAD_ENABLED == 'true') && $HTTP_POST_VARS['products_attributes_filename'] != '') {
          tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " values (" . $products_attributes_id . ", '" . $HTTP_POST_VARS['products_attributes_filename'] . "', '" . $HTTP_POST_VARS['products_attributes_maxdays'] . "', '" . $HTTP_POST_VARS['products_attributes_maxcount'] . "')");
        }
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'update_option_name':
        for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
// WebMakers.com Added: Product Options Sort Order
          $option_name = $HTTP_POST_VARS['option_name'];
          $products_options_sort_order = $HTTP_POST_VARS['products_options_sort_order'];
          tep_db_query("update " . TABLE_PRODUCTS_OPTIONS . " set products_options_name = '" . $option_name[$languages[$i]['id']] . "', products_options_sort_order = '" . $products_options_sort_order[$languages[$i]['id']] . "' where products_options_id = '" . $HTTP_POST_VARS['option_id'] . "' and language_id = '" . $languages[$i]['id'] . "'");
        }
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'update_value':
        for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
          $value_name = $HTTP_POST_VARS['value_name'];
          tep_db_query("update " . TABLE_PRODUCTS_OPTIONS_VALUES . " set products_options_values_name = '" . $value_name[$languages[$i]['id']] . "' where products_options_values_id = '" . $HTTP_POST_VARS['value_id'] . "' and language_id = '" . $languages[$i]['id'] . "'");
        }
        tep_db_query("update " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " set products_options_id = '" . $HTTP_POST_VARS['option_id'] . "', products_options_values_id = '" . $HTTP_POST_VARS['value_id'] . "'  where products_options_values_to_products_options_id = '" . $HTTP_POST_VARS['value_id'] . "'");
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'update_product_attribute':
// BOF: WebMakers.com Added: Attribute Sorter
          tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES . " set products_id = '" . $HTTP_POST_VARS['products_id'] . "', options_id = '" . $HTTP_POST_VARS['options_id'] . "', options_values_id = '" . $HTTP_POST_VARS['values_id'] . "', options_values_price = '" . $HTTP_POST_VARS['value_price'] . "', price_prefix = '" . $HTTP_POST_VARS['price_prefix'] . "', products_options_sort_order = '" . $HTTP_POST_VARS['sort_order'] . "' where products_attributes_id = '" . $HTTP_POST_VARS['attribute_id'] . "'");
//        tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES . " set products_id = '" . $HTTP_POST_VARS['products_id'] . "', options_id = '" . $HTTP_POST_VARS['options_id'] . "', options_values_id = '" . $HTTP_POST_VARS['values_id'] . "', options_values_price = '" . $HTTP_POST_VARS['value_price'] . "', price_prefix = '" . $HTTP_POST_VARS['price_prefix'] . "', products_options_sort_order = '" . $HTTP_POST_VARS['sort_order'] . "'");
// EOF: WebMakers.com Added: Attribute Sorter
// BOM Mod: allow for the download filename to be added or deleted when doing an edit
        if (DOWNLOAD_ENABLED == 'true') {
          $download_query_raw ="select products_attributes_filename from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . "
                              where products_attributes_id='" . $HTTP_POST_VARS['attribute_id'] . "'";
          $download_query = tep_db_query($download_query_raw);
          if (tep_db_num_rows($download_query) > 0) {
            $download_attribute_found = true;
          } else {
            $download_attribute_found = false;
          }
          if ($HTTP_POST_VARS['products_attributes_filename'] != '') {
            if ($download_attribute_found) {
              tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . "
                            set products_attributes_filename='" . $HTTP_POST_VARS['products_attributes_filename'] . "',
                                products_attributes_maxdays='" . $HTTP_POST_VARS['products_attributes_maxdays'] . "',
                                products_attributes_maxcount='" . $HTTP_POST_VARS['products_attributes_maxcount'] . "'
                            where products_attributes_id = '" . $HTTP_POST_VARS['attribute_id'] . "'");
            } else {
              tep_db_query("insert " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . "
                            set products_attributes_id = '" . $HTTP_POST_VARS['attribute_id'] . "',
                                products_attributes_filename='" . $HTTP_POST_VARS['products_attributes_filename'] . "',
                                products_attributes_maxdays='" . $HTTP_POST_VARS['products_attributes_maxdays'] . "',
                                products_attributes_maxcount='" . $HTTP_POST_VARS['products_attributes_maxcount'] . "'");
            }
          } else {
            if ($download_attribute_found) {
              tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . "
                            where products_attributes_id = '" . $HTTP_POST_VARS['attribute_id'] . "'");
            }
          }
        }
// EOM Mod:
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'delete_option':
        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . $HTTP_GET_VARS['option_id'] . "'");
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'delete_value':
        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . $HTTP_GET_VARS['value_id'] . "'");
        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . $HTTP_GET_VARS['value_id'] . "'");
        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " where products_options_values_id = '" . $HTTP_GET_VARS['value_id'] . "'");
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'delete_attribute':
        tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_attributes_id = '" . $HTTP_GET_VARS['attribute_id'] . "'");
// Added for DOWNLOAD_ENABLED. Always try to remove attributes, even if downloads are no longer enabled
        tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " where products_attributes_id = '" . $HTTP_GET_VARS['attribute_id'] . "'");
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
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
<script language="javascript"><!--
function go_option() {
  if (document.option_order_by.selected.options[document.option_order_by.selected.selectedIndex].value != "none") {
    location = "<?php echo tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'option_page=' . ($HTTP_GET_VARS['option_page'] ? $HTTP_GET_VARS['option_page'] : 1)); ?>&option_order_by="+document.option_order_by.selected.options[document.option_order_by.selected.selectedIndex].value;
  }
}
//--></script>
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
<!-- options and values//-->
      <tr>
        <td width="100%"><table width="100%" border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td valign="top" width="50%"><table width="100%" border="0" cellspacing="0" cellpadding="2">
<!-- options //-->
<?php
  if ($HTTP_GET_VARS['action'] == 'delete_product_option') { // delete product option
    $options = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . $HTTP_GET_VARS['option_id'] . "' and language_id = '" . $languages_id . "'");
    $options_values = tep_db_fetch_array($options);
?>
              <tr>
                <td class="pageHeading">&nbsp;<?php echo $options_values['products_options_name']; ?>&nbsp;</td>
                <td>&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '1', '53'); ?>&nbsp;</td>
              </tr>
              <tr>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td colspan="3"><?php echo tep_black_line(); ?></td>
                  </tr>
<?php
    $products = tep_db_query("select p.products_id, pd.products_name, pov.products_options_values_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pov.language_id = '" . $languages_id . "' and pd.language_id = '" . $languages_id . "' and pa.products_id = p.products_id and pa.options_id='" . $HTTP_GET_VARS['option_id'] . "' and pov.products_options_values_id = pa.options_values_id order by pd.products_name");
    if (tep_db_num_rows($products)) {
?>
                  <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</td>
                    <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT; ?>&nbsp;</td>
                    <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_VALUE; ?>&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="3"><?php echo tep_black_line(); ?></td>
                  </tr>
<?php
      while ($products_values = tep_db_fetch_array($products)) {
        $rows++;
?>
                  <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
                    <td align="center" class="smallText">&nbsp;<?php echo $products_values['products_id']; ?>&nbsp;</td>
                    <td class="smallText">&nbsp;<?php echo $products_values['products_name']; ?>&nbsp;</td>
                    <td class="smallText">&nbsp;<?php echo $products_values['products_options_values_name']; ?>&nbsp;</td>
                  </tr>
<?php
      }
?>
                  <tr>
                    <td colspan="3"><?php echo tep_black_line(); ?></td>
                  </tr>
                  <tr>
                    <td colspan="3" class="main"><br><?php echo TEXT_WARNING_OF_DELETE; ?></td>
                  </tr>
                  <tr>
                    <td align="right" colspan="3" class="main"><br><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, '&value_page=' . $value_page . '&attribute_page=' . $attribute_page, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', ' cancel '); ?></a>&nbsp;</td>
                  </tr>
<?php
    } else {
?>
                  <tr>
                    <td class="main" colspan="3"><br><?php echo TEXT_OK_TO_DELETE; ?></td>
                  </tr>
                  <tr>
                    <td class="main" align="right" colspan="3"><br><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_option&option_id=' . $HTTP_GET_VARS['option_id'], 'NONSSL') . '">'; ?><?php echo tep_image_button('button_delete.gif', ' delete '); ?></a>&nbsp;&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, '&order_by=' . $order_by . '&page=' . $page, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', ' cancel '); ?></a>&nbsp;</td>
                  </tr>
<?php
    }
?>
                </table></td>
              </tr>
<?php
  } else {
    if ($HTTP_GET_VARS['option_order_by']) {
      $option_order_by = $HTTP_GET_VARS['option_order_by'];
    } else {
      $option_order_by = 'products_options_id';
    }
?>
              <tr>
                <td colspan="2" class="pageHeading">&nbsp;<?php echo HEADING_TITLE_OPT; ?>&nbsp;</td>
                <td align="right"><form name="option_order_by" action="<?php echo FILENAME_PRODUCTS_ATTRIBUTES; ?>"><select name="selected" onChange="go_option()"><option value="products_options_id"<?php if ($option_order_by == 'products_options_id') { echo ' SELECTED'; } ?>><?php echo TEXT_OPTION_ID; ?></option><option value="products_options_name"<?php if ($option_order_by == 'products_options_name') { echo ' SELECTED'; } ?>><?php echo TEXT_OPTION_NAME; ?></option><option value="products_options_sort_order"<?php if ($option_order_by == 'products_options_sort_order') { echo ' SELECTED'; } ?>><?php echo TEXT_OPTION_SORTORDER; ?></option></select></form></td>
              </tr>
              <tr>
                <td colspan="3" class="smallText">
<?php
    $per_page = MAX_ROW_LISTS_OPTIONS;
    $options = "select * from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . $languages_id . "' order by " . $option_order_by;
    if (!$option_page) {
      $option_page = 1;
    }
    $prev_option_page = $option_page - 1;
    $next_option_page = $option_page + 1;

    $option_query = tep_db_query($options);

    $option_page_start = ($per_page * $option_page) - $per_page;
    $num_rows = tep_db_num_rows($option_query);

    if ($num_rows <= $per_page) {
      $num_pages = 1;
    } else if (($num_rows % $per_page) == 0) {
      $num_pages = ($num_rows / $per_page);
    } else {
      $num_pages = ($num_rows / $per_page) + 1;
    }
    $num_pages = (int) $num_pages;

    $options = $options . " LIMIT $option_page_start, $per_page";

    // Previous
    if ($prev_option_page)  {
      echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'option_page=' . $prev_option_page) . '"> &lt;&lt; </a> | ';
    }

    for ($i = 1; $i <= $num_pages; $i++) {
      if ($i != $option_page) {
        echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'option_page=' . $i) . '">' . $i . '</a> | ';
      } else {
        echo '<b><font color=red>' . $i . '</font></b> | ';
      }
    }

    // Next
    if ($option_page != $num_pages) {
      echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'option_page=' . $next_option_page) . '"> &gt;&gt; </a>';
    }
// WebMakers.com Added: Product Options Sort Order
?>
                </td>
              </tr>
              <tr>
                <td colspan="4"><?php echo tep_black_line(); ?></td>
              </tr>
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</td>
                <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_NAME; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="right">&nbsp;<?php echo TABLE_HEADING_OPTION_SORT_ORDER; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="4"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
    $next_id = 1;
    $options = tep_db_query($options);
    while ($options_values = tep_db_fetch_array($options)) {
      $rows++;
?>
              <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
      if (($HTTP_GET_VARS['action'] == 'update_option') && ($HTTP_GET_VARS['option_id'] == $options_values['products_options_id'])) {
        echo '<form name="option" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_option_name', 'NONSSL') . '" method="post">';
        $inputs = '';
        for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
// WebMakers.com Added: Product Options Sort Order
          $option_name = tep_db_query("select products_options_name, products_options_sort_order from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . $options_values['products_options_id'] . "' and language_id = '" . $languages[$i]['id'] . "' order by products_options_sort_order");
          $option_name = tep_db_fetch_array($option_name);
          $inputs .= $languages[$i]['code'] . ':&nbsp;<input type="text" name="option_name[' . $languages[$i]['id'] . ']" size="20" value="' . $option_name['products_options_name'] . '">&nbsp; Sort Order <input type="text" name="products_options_sort_order[' . $languages[$i]['id'] . ']" size="3" value="' . $option_name['products_options_sort_order'] . '"><br>';
        }
?>
                <td align="center" class="smallText">&nbsp;<?php echo $options_values['products_options_id']; ?><input type="hidden" name="option_id" value="<?php echo $options_values['products_options_id']; ?>">&nbsp;</td>
                <td class="smallText" colspan="2"><?php echo $inputs; ?></td>
                <td align="center" class="smallText">&nbsp;<?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?>&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, '', 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', IMAGE_CANCEL); ?></a>&nbsp;</td>
<?php
        echo '</form>' . "\n";
      } else {
// WebMakers.com Added: Product Options Sort Order
?>
                <td align="center" class="smallText">&nbsp;<?php echo $options_values["products_options_id"]; ?>&nbsp;</td>
                <td class="smallText">&nbsp;<?php echo $options_values["products_options_name"]; ?>&nbsp;</td>
                <td class="smallText" align="right">&nbsp;<?php echo $options_values["products_options_sort_order"]; ?>&nbsp;</td>
                <td align="center" class="smallText">&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_option&option_id=' . $options_values['products_options_id'] . '&option_order_by=' . $option_order_by . '&option_page=' . $option_page, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_edit.gif', IMAGE_UPDATE); ?></a>&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_product_option&option_id=' . $options_values['products_options_id'], 'NONSSL') , '">'; ?><?php echo tep_image_button('button_delete.gif', IMAGE_DELETE); ?></a>&nbsp;</td>
<?php
      }
?>
              </tr>
<?php
      $max_options_id_query = tep_db_query("select max(products_options_id) + 1 as next_id from " . TABLE_PRODUCTS_OPTIONS);
      $max_options_id_values = tep_db_fetch_array($max_options_id_query);
      $next_id = $max_options_id_values['next_id'];
    }
?>
              <tr>
                <td colspan="4"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
    if ($HTTP_GET_VARS['action'] != 'update_option') {
?>
              <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
// WebMakers.com Added: Product Options Sort Order
      echo '<form name="options" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=add_product_options&option_page=' . $option_page, 'NONSSL') . '" method="post"><input type="hidden" name="products_options_id" value="' . $next_id . '">';
      $inputs = '';
      for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
        $inputs .= $languages[$i]['code'] . ':&nbsp;<input type="text" name="option_name[' . $languages[$i]['id'] . ']" size="20"> Sort Order <input type="text" name="products_options_sort_order[' . $languages[$i]['id'] . ']" size="3">&nbsp;<br>';
      }
?>
                <td align="center" class="smallText">&nbsp;<?php echo $next_id; ?>&nbsp;</td>
                <td class="smallText" colspan="2"><?php echo $inputs; ?></td>
                <td align="center" class="smallText">&nbsp;<?php echo tep_image_submit('button_insert.gif', IMAGE_INSERT); ?>&nbsp;</td>
<?php
      echo '</form>';
?>
              </tr>
              <tr>
                <td colspan="4"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
    }
  }
?>
            </table></td>
<!-- options eof //-->
            <td valign="top" width="50%"><table width="100%" border="0" cellspacing="0" cellpadding="2">
<!-- value //-->
<?php
  if ($HTTP_GET_VARS['action'] == 'delete_option_value') { // delete product option value
    $values = tep_db_query("select products_options_values_id, products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . $HTTP_GET_VARS['value_id'] . "' and language_id = '" . $languages_id . "'");
    $values_values = tep_db_fetch_array($values);
?>
              <tr>
                <td colspan="3" class="pageHeading">&nbsp;<?php echo $values_values['products_options_values_name']; ?>&nbsp;</td>
                <td>&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '1', '53'); ?>&nbsp;</td>
              </tr>
              <tr>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td colspan="3"><?php echo tep_black_line(); ?></td>
                  </tr>
<?php
    $products = tep_db_query("select p.products_id, pd.products_name, po.products_options_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . $languages_id . "' and po.language_id = '" . $languages_id . "' and pa.products_id = p.products_id and pa.options_values_id='" . $HTTP_GET_VARS['value_id'] . "' and po.products_options_id = pa.options_id order by pd.products_name, pa.products_options_sort_order, po.products_options_sort_order");
    if (tep_db_num_rows($products)) {
?>
                  <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</td>
                    <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="right">&nbsp;<?php echo TABLE_HEADING_OPTION_SORT_ORDER; ?>&nbsp;</td>
                    <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_NAME; ?>&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="3"><?php echo tep_black_line(); ?></td>
                  </tr>
<?php
      while ($products_values = tep_db_fetch_array($products)) {
        $rows++;
?>
                  <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
                    <td align="center" class="smallText">&nbsp;<?php echo $products_values['products_id']; ?>&nbsp;</td>
                    <td class="smallText">&nbsp;<?php echo $products_values['products_name']; ?>&nbsp;</td>
                <td class="smallText" align="right">&nbsp;<?php echo $options_values["products_options_sort_order"]; ?>&nbsp;</td>
                    <td class="smallText">&nbsp;<?php echo $products_values['products_options_name']; ?>&nbsp;</td>
                  </tr>
<?php
      }
?>
                  <tr>
                    <td colspan="3"><?php echo tep_black_line(); ?></td>
                  </tr>
                  <tr>
                    <td class="main" colspan="3"><br><?php echo TEXT_WARNING_OF_DELETE; ?></td>
                  </tr>
                  <tr>
                    <td class="main" align="right" colspan="3"><br><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, '&value_page=' . $value_page . '&attribute_page=' . $attribute_page, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', ' cancel '); ?></a>&nbsp;</td>
                  </tr>
<?php
    } else {
?>
                  <tr>
                    <td class="main" colspan="3"><br><?php echo TEXT_OK_TO_DELETE; ?></td>
                  </tr>
                  <tr>
                    <td class="main" align="right" colspan="3"><br><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_value&value_id=' . $HTTP_GET_VARS['value_id'], 'NONSSL') . '">'; ?><?php echo tep_image_button('button_delete.gif', ' delete '); ?></a>&nbsp;&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, '&option_page=' . $option_page . '&value_page=' . $value_page . '&attribute_page=' . $attribute_page, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', ' cancel '); ?></a>&nbsp;</td>
                  </tr>
<?php
    }
?>
              	</table></td>
              </tr>
<?php
  } else {
?>
              <tr>
                <td colspan="3" class="pageHeading">&nbsp;<?php echo HEADING_TITLE_VAL; ?>&nbsp;</td>
                <td>&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '1', '36'); ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="4" class="smallText">
<?php
    $per_page = MAX_ROW_LISTS_OPTIONS;
    $values = "select pov.products_options_values_id, pov.products_options_values_name, pov2po.products_options_id from " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov left join " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " pov2po on pov.products_options_values_id = pov2po.products_options_values_id where pov.language_id = '" . $languages_id . "' order by pov.products_options_values_id";
    if (!$value_page) {
      $value_page = 1;
    }
    $prev_value_page = $value_page - 1;
    $next_value_page = $value_page + 1;

    $value_query = tep_db_query($values);

    $value_page_start = ($per_page * $value_page) - $per_page;
    $num_rows = tep_db_num_rows($value_query);

    if ($num_rows <= $per_page) {
      $num_pages = 1;
    } else if (($num_rows % $per_page) == 0) {
      $num_pages = ($num_rows / $per_page);
    } else {
      $num_pages = ($num_rows / $per_page) + 1;
    }
    $num_pages = (int) $num_pages;

    $values = $values . " LIMIT $value_page_start, $per_page";

    // Previous
    if ($prev_value_page)  {
      echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'option_order_by=' . $option_order_by . '&value_page=' . $prev_value_page) . '"> &lt;&lt; </a> | ';
    }

    for ($i = 1; $i <= $num_pages; $i++) {
      if ($i != $value_page) {
         echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'option_order_by=' . $option_order_by . '&value_page=' . $i) . '">' . $i . '</a> | ';
      } else {
         echo '<b><font color=red>' . $i . '</font></b> | ';
      }
    }

    // Next
    if ($value_page != $num_pages) {
      echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'option_order_by=' . $option_order_by . '&value_page=' . $next_value_page) . '"> &gt;&gt;</a> ';
    }
?>
                </td>
              </tr>
              <tr>
                <td colspan="4"><?php echo tep_black_line(); ?></td>
              </tr>
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</td>
                <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_NAME; ?>&nbsp;</td>
                <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_VALUE; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="4"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
    $next_id = 1;
    $values = tep_db_query($values);
    while ($values_values = tep_db_fetch_array($values)) {
      $options_name = tep_options_name($values_values['products_options_id']);
      $values_name = $values_values['products_options_values_name'];
      $rows++;
?>
              <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
      if (($HTTP_GET_VARS['action'] == 'update_option_value') && ($HTTP_GET_VARS['value_id'] == $values_values['products_options_values_id'])) {
        echo '<form name="values" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_value', 'NONSSL') . '" method="post">';
        $inputs = '';
        for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
          $value_name = tep_db_query("select products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . $values_values['products_options_values_id'] . "' and language_id = '" . $languages[$i]['id'] . "'");
          $value_name = tep_db_fetch_array($value_name);
          $inputs .= $languages[$i]['code'] . ':&nbsp;<input type="text" name="value_name[' . $languages[$i]['id'] . ']" size="15" value="' . $value_name['products_options_values_name'] . '">&nbsp;<br>';
        }
?>
                <td align="center" class="smallText">&nbsp;<?php echo $values_values['products_options_values_id']; ?><input type="hidden" name="value_id" value="<?php echo $values_values['products_options_values_id']; ?>">&nbsp;</td>
                <td align="center" class="smallText">&nbsp;<?php echo "\n"; ?><select name="option_id">
<?php
        $options = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . $languages_id . "' order by products_options_sort_order, products_options_name");
        while ($options_values = tep_db_fetch_array($options)) {
          echo "\n" . '<option name="' . $options_values['products_options_name'] . '" value="' . $options_values['products_options_id'] . '"';
          if ($values_values['products_options_id'] == $options_values['products_options_id']) {
            echo ' selected';
          }
          echo '>' . $options_values['products_options_name'] . '</option>';
        }
?>
                </select>&nbsp;</td>
                <td class="smallText"><?php echo $inputs; ?></td>
                <td align="center" class="smallText">&nbsp;<?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?>&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, '', 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', IMAGE_CANCEL); ?></a>&nbsp;</td>
<?php
        echo '</form>';
      } else {
?>
                <td align="center" class="smallText">&nbsp;<?php echo $values_values["products_options_values_id"]; ?>&nbsp;</td>
                <td align="center" class="smallText">&nbsp;<?php echo $options_name; ?>&nbsp;</td>
                <td class="smallText">&nbsp;<?php echo $values_name; ?>&nbsp;</td>
                <td align="center" class="smallText">&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_option_value&value_id=' . $values_values['products_options_values_id'] . '&value_page=' . $HTTP_GET_VARS['value_page'], 'NONSSL') . '">'; ?><?php echo tep_image_button('button_edit.gif', IMAGE_UPDATE); ?></a>&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_option_value&value_id=' . $values_values['products_options_values_id'], 'NONSSL') , '">'; ?><?php echo tep_image_button('button_delete.gif', IMAGE_DELETE); ?></a>&nbsp;</td>
<?php
      }
      $max_values_id_query = tep_db_query("select max(products_options_values_id) + 1 as next_id from " . TABLE_PRODUCTS_OPTIONS_VALUES);
      $max_values_id_values = tep_db_fetch_array($max_values_id_query);
      $next_id = $max_values_id_values['next_id'];
    }
?>
              </tr>
              <tr>
                <td colspan="4"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
    if ($HTTP_GET_VARS['action'] != 'update_option_value') {
?>
              <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
      echo '<form name="values" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=add_product_option_values&value_page=' . $value_page, 'NONSSL') . '" method="post">';
?>
                <td align="center" class="smallText">&nbsp;<?php echo $next_id; ?>&nbsp;</td>
                <td align="center" class="smallText">&nbsp;<select name="option_id">
<?php
      $options = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . $languages_id . "' order by products_options_sort_order, products_options_name");
      while ($options_values = tep_db_fetch_array($options)) {
        echo '<option name="' . $options_values['products_options_name'] . '" value="' . $options_values['products_options_id'] . '">' . $options_values['products_options_name'] . '</option>';
      }

      $inputs = '';
      for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
        $inputs .= $languages[$i]['code'] . ':&nbsp;<input type="text" name="value_name[' . $languages[$i]['id'] . ']" size="15">&nbsp;<br>';
      }
?>
                </select>&nbsp;</td>
                <td class="smallText"><input type="hidden" name="value_id" value="<?php echo $next_id; ?>"><?php echo $inputs; ?></td>
                <td align="center" class="smallText">&nbsp;<?php echo tep_image_submit('button_insert.gif', IMAGE_INSERT); ?>&nbsp;</td>
<?php
      echo '</form>';
?>
              </tr>
              <tr>
                <td colspan="4"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
    }
  }
?>
            </table></td>
          </tr>
        </table></td>
<!-- option value eof //-->
      </tr>
<!-- products_attributes //-->
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">&nbsp;<?php echo HEADING_TITLE_ATRIB; ?>&nbsp;</td>
            <td>&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '1', '53'); ?>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
<?php
    if ($HTTP_GET_VARS['action'] == 'update_attribute') {
      $form_action = 'update_product_attribute';
    } else {
      $form_action = 'add_product_attributes';
    }
?>

        <td><form name="attributes" action="<?php echo tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=' . $form_action . '&option_page=' . $option_page . '&value_page=' . $value_page . '&attribute_page=' . $attribute_page); ?>" method="post"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="10" class="smallText">
<?php
  $per_page = MAX_ROW_LISTS_OPTIONS;
  $attributes = "select pa.* from " . TABLE_PRODUCTS_ATTRIBUTES . " pa left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on pa.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' order by pd.products_name, pa.products_options_sort_order";
  if (!$attribute_page) {
    $attribute_page = 1;
  }
  $prev_attribute_page = $attribute_page - 1;
  $next_attribute_page = $attribute_page + 1;

  $attribute_query = tep_db_query($attributes);

  $attribute_page_start = ($per_page * $attribute_page) - $per_page;
  $num_rows = tep_db_num_rows($attribute_query);

  if ($num_rows <= $per_page) {
     $num_pages = 1;
  } else if (($num_rows % $per_page) == 0) {
     $num_pages = ($num_rows / $per_page);
  } else {
     $num_pages = ($num_rows / $per_page) + 1;
  }
  $num_pages = (int) $num_pages;

  $attributes = $attributes . " LIMIT $attribute_page_start, $per_page";

  // Previous
  if ($prev_attribute_page) {
    echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'attribute_page=' . $prev_attribute_page) . '"> &lt;&lt; </a> | ';
  }

  for ($i = 1; $i <= $num_pages; $i++) {
    if ($i != $attribute_page) {
      echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'attribute_page=' . $i) . '">' . $i . '</a> | ';
    } else {
      echo '<b><font color="red">' . $i . '</font></b> | ';
    }
  }

  // Next
  if ($attribute_page != $num_pages) {
    echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'attribute_page=' . $next_attribute_page) . '"> &gt;&gt; </a>';
  }
?>
            </td>
          </tr>
          <tr>
            <td colspan="10"><?php echo tep_black_line(); ?></td>
          </tr>
          <tr class="dataTableHeadingRow">
            <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</td>
            <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT; ?>&nbsp;</td>
<?php
// BOF: WebMakers.com Added: FREE-CALL
//
?>
            <td class="dataTableHeadingContent">&nbsp;<?php echo 'FILE NAME'; ?>&nbsp;</td>
<?php
// EOF: WebMakers.com Added: FREE-CALL
//
?>
            <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_NAME; ?>&nbsp;</td>
            <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_VALUE; ?>&nbsp;</td>
<?php
// BOF: WebMakers.com Added: FREE-CALL
//
?>
            <td class="dataTableHeadingContent" align="right">&nbsp;<?php echo 'Sort Order '; ?>&nbsp;</td>

<?php
// EOF: WebMakers.com Added: FREE-CALL
//
?>
            <td class="dataTableHeadingContent" align="right">&nbsp;<?php echo TABLE_HEADING_OPT_PRICE; ?>&nbsp;</td>
            <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_OPT_PRICE_PREFIX; ?>&nbsp;</td>
            <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_PRODUCT_ATTRIBUTE_ONE_TIME; ?>&nbsp;</td>
            <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="10"><?php echo tep_black_line(); ?></td>
          </tr>

<?php
  $next_id = 1;
  $attributes = tep_db_query($attributes);
  while ($attributes_values = tep_db_fetch_array($attributes)) {
    $products_name_only = tep_get_products_name($attributes_values['products_id']);
    $options_name = tep_options_name($attributes_values['options_id']);
    $values_name = tep_values_name($attributes_values['options_values_id']);
    $rows++;
?>
          <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
    if (($HTTP_GET_VARS['action'] == 'update_attribute') && ($HTTP_GET_VARS['attribute_id'] == $attributes_values['products_attributes_id'])) {
?>
            <td class="attributeBoxContent">&nbsp;<?php echo $attributes_values['products_attributes_id']; ?><input type="hidden" name="attribute_id" value="<?php echo $attributes_values['products_attributes_id']; ?>">&nbsp;</td>
            <td class="attributeBoxContent" colspan="2">&nbsp;<select name="products_id">
<?php
      $products = tep_db_query("select p.products_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . $languages_id . "' order by pd.products_name");
      while($products_values = tep_db_fetch_array($products)) {
        if ($attributes_values['products_id'] == $products_values['products_id']) {
          echo "\n" . '<option name="' . $products_values['products_name'] . '" value="' . $products_values['products_id'] . '" SELECTED>' . $products_values['products_name'] . '</option>';
        } else {
          echo "\n" . '<option name="' . $products_values['products_name'] . '" value="' . $products_values['products_id'] . '">' . $products_values['products_name'] . '</option>';
        }
      }
?>
            </select>&nbsp;</td>
            <td class="attributeBoxContent">&nbsp;<select name="options_id"   onChange="changevalueupdate()">
<?php
	  $optionvaluecom="";
      $options = tep_db_query("select * from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . $languages_id . "' order by products_options_sort_order, products_options_name");
      while($options_values = tep_db_fetch_array($options)) {
        if($_GET['optionvalue'] != "")
		{
			  if($_GET['optionvalue'] == $options_values['products_options_id'])
			  {
			   echo "\n" . '<option name="' . $options_values['products_options_name'] . '" value="' . $options_values['products_options_id'] . '" SELECTED>' . $options_values['products_options_name'] . '</option>';
			  }
			  else 
			  {
			   echo "\n" . '<option name="' . $options_values['products_options_name'] . '" value="' . $options_values['products_options_id'] . '">' . $options_values['products_options_name'] . '</option>';
			  }
		}
		else
		{
			if ($attributes_values['options_id'] == $options_values['products_options_id']) 
			{
			$optionvaluecom = $options_values['products_options_id'];
			  echo "\n" . '<option name="' . $options_values['products_options_name'] . '" value="' . $options_values['products_options_id'] . '" SELECTED>' . $options_values['products_options_name'] . '</option>';
			} 
			else 
			{
			  echo "\n" . '<option name="' . $options_values['products_options_name'] . '" value="' . $options_values['products_options_id'] . '">' . $options_values['products_options_name'] . '</option>';
			}
			
		}
		
		
      }
?>
            </select>&nbsp;</td>
            <td class="attributeBoxContent">&nbsp;<select name="values_id">
<?php

	if(isset($_GET['optionvalue']) && $_GET['optionvalue'] != "")
	{
	$values = tep_db_query("select pov.* from " . TABLE_PRODUCTS_OPTIONS_VALUES . "  pov , ".TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " p2p where pov.products_options_values_id = p2p.products_options_values_id  and  p2p.products_options_id = '".$_GET['optionvalue']."' and  pov.language_id = '" . $languages_id . "' order by pov.products_options_values_name");
	
	}
	else
	{
		//$values = tep_db_query("select * from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where language_id ='" . $languages_id . "' order by products_options_values_name");
    	$values = tep_db_query("select pov.* from " . TABLE_PRODUCTS_OPTIONS_VALUES . "  pov , ".TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " p2p where pov.products_options_values_id = p2p.products_options_values_id  and  p2p.products_options_id = '".$optionvaluecom."' and  pov.language_id = '" . $languages_id . "' order by pov.products_options_values_name");

	}
      
      while($values_values = tep_db_fetch_array($values)) 
	  {
			if ($attributes_values['options_values_id'] == $values_values['products_options_values_id']) {
			  echo "\n" . '<option name="' . $values_values['products_options_values_name'] . '" value="' . $values_values['products_options_values_id'] . '" SELECTED>' . $values_values['products_options_values_name'] . '</option>';
			} else {
			  echo "\n" . '<option name="' . $values_values['products_options_values_name'] . '" value="' . $values_values['products_options_values_id'] . '">' . $values_values['products_options_values_name'] . '</option>';
			}
      }
?>
            </select>&nbsp;</td>
<?php
// BOF: WebMakers.com Added: Attribute Sorter- Edit
//
?>
            <td align="right" class="attributeBoxContent">&nbsp;Sort Order <input type="text" name="sort_order" value="<?php echo $attributes_values['products_options_sort_order']; ?>" size="2">&nbsp;</td>
            <td align="right" class="attributeBoxContent">&nbsp;Price <input type="text" name="value_price" value="<?php echo $attributes_values['options_values_price']; ?>" size="6">&nbsp;</td>
            <td align="center" class="attributeBoxContent">&nbsp;<input type="text" name="price_prefix" value="<?php echo $attributes_values['price_prefix']; ?>" size="2">&nbsp;</td>
            <td class="attributeBoxContent">&nbsp;</td>
            <td align="center" class="attributeBoxContent">&nbsp;<?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?>&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, '&attribute_page=' . $attribute_page, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', IMAGE_CANCEL); ?></a>&nbsp;</td>
<?php
      if (DOWNLOAD_ENABLED == 'true') {
        $download_query_raw ="select products_attributes_filename, products_attributes_maxdays, products_attributes_maxcount
                              from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . "
                              where products_attributes_id='" . $attributes_values['products_attributes_id'] . "'";
        $download_query = tep_db_query($download_query_raw);
        if (tep_db_num_rows($download_query) > 0) {
          $download = tep_db_fetch_array($download_query);
          $products_attributes_filename = $download['products_attributes_filename'];
          $products_attributes_maxdays  = $download['products_attributes_maxdays'];
          $products_attributes_maxcount = $download['products_attributes_maxcount'];
        }
?>
          <tr class="attributeBoxContent">
            <td class="attributeBoxContent">&nbsp;</td>
            <td colspan="8">
              <table>
                <tr class="attributeBoxContent">
                  <td align="right" class="attributeBoxContent"><?php echo TABLE_HEADING_DOWNLOAD; ?>&nbsp;</td>
                  <td align="right" class="attributeBoxContent"><?php echo TABLE_TEXT_FILENAME; ?></td>
                  <td align="right" class="attributeBoxContent"><?php echo tep_draw_input_field('products_attributes_filename', $products_attributes_filename, 'size="35"'); ?>&nbsp;</td>
                  <td align="right" class="attributeBoxContent"><?php echo TABLE_TEXT_MAX_DAYS; ?></td>
                  <td align="right" class="attributeBoxContent"><?php echo tep_draw_input_field('products_attributes_maxdays', $products_attributes_maxdays, 'size="5"'); ?>&nbsp;</td>
                  <td align="right" class="attributeBoxContent"><?php echo TABLE_TEXT_MAX_COUNT; ?></td>
                  <td align="right" class="attributeBoxContent"><?php echo tep_draw_input_field('products_attributes_maxcount', $products_attributes_maxcount, 'size="5"'); ?>&nbsp;</td>
                </tr>
              </table>
            </td>
            <td>&nbsp;</td>
          </tr>
<?php
      }
?>



<?php /* BOF: WebMakers.com Added: Attribute Enhancements Coming Soon */ ?>
          <tr class="attributeBoxContent">
            <td class="attributeBoxContent">&nbsp;</td>
            <td class="attributeBoxContent" colspan="8">
            </td>
            <td>&nbsp;</td>
          </tr>
<?php /* EOF: WebMakers.com Added: Attribute Enhancements Coming Soon */ ?>





<?php
    } elseif (($HTTP_GET_VARS['action'] == 'delete_product_attribute') && ($HTTP_GET_VARS['attribute_id'] == $attributes_values['products_attributes_id'])) {
?>

<?php
// BOF: WebMakers.com Added: Attribute Sorter - Delete
?>
            <td class="smallText">&nbsp;<b><?php echo $attributes_values["products_attributes_id"]; ?></b>&nbsp;</td>
            <td class="smallText">&nbsp;<b><?php echo $products_name_only; ?></b>&nbsp;</td>

            <td class="smallText">&nbsp;<b><?php echo $the_download['products_attributes_filename']; ?></b>&nbsp;</td>
            <td class="smallText">&nbsp;<b><?php echo $options_name; ?></b>&nbsp;</td>
            <td class="smallText">&nbsp;<b><?php echo $values_name; ?></b>&nbsp;</td>

            <td align="right" class="smallText">&nbsp;<b><?php echo $attributes_values["products_options_sort_order"]; ?></td>
            <td align="right" class="smallText">&nbsp;<b><?php echo $attributes_values["options_values_price"]; ?></b>&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<b><?php echo $attributes_values["price_prefix"]; ?></b>&nbsp;</td>


            <td align="center" class="smallText">&nbsp;<b><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_attribute&attribute_id=' . $HTTP_GET_VARS['attribute_id']) . '">'; ?><?php echo tep_image_button('button_confirm.gif', IMAGE_CONFIRM); ?></a>&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, '&option_page=' . $option_page . '&value_page=' . $value_page . '&attribute_page=' . $attribute_page, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', IMAGE_CANCEL); ?></a>&nbsp;</b></td>
<?php
// EOF: WebMakers.com Added: Attribute Sorter - Delete
    } else {
?>
<?php
// BOF: WebMakers.com Added: FREE-CALL FOR PRICE-COMING SOON ETC.
        $the_download_query_raw ="select products_attributes_filename, products_attributes_maxdays, products_attributes_maxcount from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " where products_attributes_id='" . $attributes_values['products_attributes_id'] . "'";
        $the_download_query = tep_db_query($the_download_query_raw);
        $the_download= tep_db_fetch_array($the_download_query);

?>
            <td class="smallText">&nbsp;<?php echo $attributes_values["products_attributes_id"]; ?>&nbsp;</td>
            <td class="smallText">&nbsp;<?php echo $products_name_only; ?>&nbsp;</td>
<?php
// BOF: NOTE
// Could go into /admin/includes/configure.php
  define('DIR_FS_DOWNLOAD', DIR_FS_CATALOG . 'download/');
  $filename_is_missing='';
// EOF: NOTE
if ( $the_download['products_attributes_filename'] ) {
  if ( !file_exists(DIR_FS_DOWNLOAD . $the_download['products_attributes_filename']) ) {
    $filename_is_missing='<FONT COLOR="FF0000"> - <B>*** Missing</B></FONT>';
  } else {
    $filename_is_missing='<FONT COLOR="2F4F2F"> - <B>Good File</B></FONT>';
  }
}
?>
            <td class="smallText"><?php echo $the_download['products_attributes_filename'] . $filename_is_missing; ?>&nbsp;</td>
<?php
// EOF: WebMakers.com Added: FREE-CALL FOR PRICE-COMING SOON ETC.
?>
            <td class="smallText">&nbsp;<?php echo $options_name; ?>&nbsp;</td>
            <td class="smallText">&nbsp;<?php echo $values_name; ?>&nbsp;</td>
<?php
// BOF: WebMakers.com Added: FREE-CALL FOR PRICE-COMING SOON ETC.
//
?>
            <td align="right" class="smallText"><?php echo $attributes_values["products_options_sort_order"]; ?>&nbsp;</td>
<?php
// EOF: WebMakers.com Added: FREE-CALL FOR PRICE-COMING SOON ETC.
//
?>
            <td align="right" class="smallText">&nbsp;<?php echo $attributes_values["options_values_price"]; ?>&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<?php echo $attributes_values["price_prefix"]; ?>&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<?php echo $attributes_values["product_attributes_one_time"]; ?>&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_attribute&attribute_id=' . $attributes_values['products_attributes_id'] . '&attribute_page=' . $attribute_page, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_edit.gif', IMAGE_UPDATE); ?></a>&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_product_attribute&attribute_id=' . $attributes_values['products_attributes_id'] . '&attribute_page=' . $attribute_page, 'NONSSL') , '">'; ?><?php echo tep_image_button('button_delete.gif', IMAGE_DELETE); ?></a>&nbsp;</td>
<?php
    }
    $max_attributes_id_query = tep_db_query("select max(products_attributes_id) + 1 as next_id from " . TABLE_PRODUCTS_ATTRIBUTES);
    $max_attributes_id_values = tep_db_fetch_array($max_attributes_id_query);
    $next_id = $max_attributes_id_values['next_id'];
?>
          </tr>
<?php
  }
  if ($HTTP_GET_VARS['action'] != 'update_attribute') {
?>
          <tr>
            <td colspan="10"><?php echo tep_black_line(); ?></td>
          </tr>
          <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
            <td class="smallText">&nbsp;<?php echo $next_id; ?>&nbsp;</td>
<?php
// BOF: WebMakers.com Added: FREE-CALL FOR PRICE-COMING SOON ETC.
?>
      	    <td class="smallText" colspan="2">&nbsp;<select name="products_id">
<?php
// EOF: WebMakers.com Added: FREE-CALL FOR PRICE-COMING SOON ETC.
?>
<?php
    $products = tep_db_query("select p.products_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . $languages_id . "' order by pd.products_name");
    while ($products_values = tep_db_fetch_array($products)) {
      echo '<option name="' . $products_values['products_name'] . '" value="' . $products_values['products_id'] . '">' . $products_values['products_name'] . '</option>';
    }
?>
            </select>&nbsp;</td>
            <td class="smallText">&nbsp;<select name="options_id"  onChange="changevalue()">
<?php
	$optionvaluecom = "";
    $options = tep_db_query("select * from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . $languages_id . "' order by products_options_sort_order, products_options_name");
    while ($options_values = tep_db_fetch_array($options)) {
    	if($_GET['optionvalue'] == $options_values['products_options_id'])
		{
		echo '<option name="' . $options_values['products_options_name'] . '" value="' . $options_values['products_options_id'] . '" SELECTED>' . $options_values['products_options_name'] . '</option>';
		}
		else
		{
	  	echo '<option name="' . $options_values['products_options_name'] . '" value="' . $options_values['products_options_id'] . '">' . $options_values['products_options_name'] . '</option>';
		}
	
		
		if(!isset($_GET['optionvalue']) && $_GET['optionvalue'] == "" && $optionvaluecom == "")
		{
			$optionvaluecom = $options_values['products_options_id'];
		}
    }
?>
            </select>&nbsp;</td>
            <td class="smallText">&nbsp;<select name="values_id">
<?php
	if(isset($_GET['optionvalue']) && $_GET['optionvalue'] != "")
	{
	$values = tep_db_query("select pov.* from " . TABLE_PRODUCTS_OPTIONS_VALUES . "  pov , ".TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " p2p where pov.products_options_values_id = p2p.products_options_values_id  and  p2p.products_options_id = '".$_GET['optionvalue']."' and  pov.language_id = '" . $languages_id . "' order by pov.products_options_values_name");
	
	}
	else
	{
		$values = tep_db_query("select pov.* from " . TABLE_PRODUCTS_OPTIONS_VALUES . "  pov , ".TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " p2p where pov.products_options_values_id = p2p.products_options_values_id  and  p2p.products_options_id = '".$optionvaluecom."' and  pov.language_id = '" . $languages_id . "' order by pov.products_options_values_name");
    //$values = tep_db_query("select * from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where language_id = '" . $languages_id . "' order by products_options_values_name");
	}
    while ($values_values = tep_db_fetch_array($values)) {
      echo '<option name="' . $values_values['products_options_values_name'] . '" value="' . $values_values['products_options_values_id'] . '">' . $values_values['products_options_values_name'] . '</option>';
    }
?>
            </select>&nbsp;</td>
<?php
// BOF: WebMakers.com Added: FREE-CALL FOR PRICE-COMING SOON ETC.
?>
            <td align="right" class="smallText">&nbsp;<input type="text" name="sort_order" size="6">&nbsp;</td>
<?php
// EOF: WebMakers.com Added: FREE-CALL FOR PRICE-COMING SOON ETC.
?>
            <td align="right" class="smallText">&nbsp;<input type="text" name="value_price" size="6">&nbsp;</td>
            <td align="right" class="smallText">&nbsp;<input type="text" name="price_prefix" size="2" value="+">&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<input type="text" name="product_attributes_one_time" value="0" size="2">&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<?php echo tep_image_submit('button_insert.gif', IMAGE_INSERT); ?>&nbsp;</td>
          </tr>
<?php
      if (DOWNLOAD_ENABLED == 'true') {
        $products_attributes_maxdays  = DOWNLOAD_MAX_DAYS;
        $products_attributes_maxcount = DOWNLOAD_MAX_COUNT;
?>
          <tr class="<?php echo (!($rows % 2)? 'attributes-even' : 'attributes-odd');?>">
            <td>&nbsp;</td>
            <td colspan="8">
              <table>
                <tr class="<?php echo (!($rows % 2)? 'attributes-even' : 'attributes-odd');?>">
                  <td class="smallText"><?php echo TABLE_HEADING_DOWNLOAD; ?>&nbsp;</td>
                  <td class="smallText"><?php echo TABLE_TEXT_FILENAME; ?></td>
                  <td class="smallText"><?php echo tep_draw_input_field('products_attributes_filename', $products_attributes_filename, 'size="35"'); ?>&nbsp;</td>
                  <td class="smallText"><?php echo TABLE_TEXT_MAX_DAYS; ?></td>
                  <td class="smallText"><?php echo tep_draw_input_field('products_attributes_maxdays', $products_attributes_maxdays, 'size="5"'); ?>&nbsp;</td>
                  <td class="smallText"><?php echo TABLE_TEXT_MAX_COUNT; ?></td>
                  <td class="smallText"><?php echo tep_draw_input_field('products_attributes_maxcount', $products_attributes_maxcount, 'size="5"'); ?>&nbsp;</td>
                </tr>
              </table>
            </td>
            <td>&nbsp;</td>
          </tr>
<?php
      } // end of DOWNLOAD_ENABLED section
?>
<?php
  }
?>
          <tr>
            <td colspan="10"><?php echo tep_black_line(); ?></td>
          </tr>
        </table></form></td>
      </tr>
    </table></td>
<!-- products_attributes_eof //-->
  </tr>
</table>
<!-- body_text_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
<script>
/*  this function is to change option value according to the Product option in product attributes  */
function changevalue()
{
	window.location = "products_attributes.php?optionvalue="+document.attributes.options_id.value;
}

/*  this function is to change option value according to the Product option in product attributes while updating */
function changevalueupdate()
{
	window.location = "products_attributes.php?optionvalue="+document.attributes.options_id.value+"&action=<?php echo $_GET['action'] ?>&attribute_id=<?php echo $_GET['attribute_id'] ?>&attribute_page=<?php echo $_GET['attribute_page'] ?>";
}

</script>