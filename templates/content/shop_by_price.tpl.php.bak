<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
<?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_products_new.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
// BOF: Lango Added for template MOD
}else{
$header_text = HEADING_TITLE;
}
// EOF: Lango Added for template MOD
?>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>
      <tr>
        <td>
<?php
  // MELTUS - this is the section of interest
  // create column list
  $define_list = array('PRODUCT_LIST_MODEL' => PRODUCT_LIST_MODEL,
                       'PRODUCT_LIST_NAME' => PRODUCT_LIST_NAME,
                       'PRODUCT_LIST_MANUFACTURER' => PRODUCT_LIST_MANUFACTURER,
                       'PRODUCT_LIST_PRICE' => PRODUCT_LIST_PRICE,
                       'PRODUCT_LIST_QUANTITY' => PRODUCT_LIST_QUANTITY,
                       'PRODUCT_LIST_WEIGHT' => PRODUCT_LIST_WEIGHT,
                       'PRODUCT_LIST_IMAGE' => PRODUCT_LIST_IMAGE,
                       'PRODUCT_LIST_BUY_NOW' => PRODUCT_LIST_BUY_NOW);
  asort($define_list);

  $column_list = array();
  reset($define_list);
  while (list($column, $value) = each($define_list)) {
    if ($value) $column_list[] = $column;
  }

  $select_column_list = '';

  for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
    if ( ($column_list[$col] == 'PRODUCT_LIST_BUY_NOW') || ($column_list[$col] == 'PRODUCT_LIST_NAME') || ($column_list[$col] == 'PRODUCT_LIST_PRICE') ) {
      continue;
    }
  }

  $sbp_sql_range = '';
  if (MODULE_SHOPBYPRICE_RANGES > 0  && $_GET['range'] <= MODULE_SHOPBYPRICE_RANGES) {
    $sbp_array = unserialize(MODULE_SHOPBYPRICE_RANGE);
    $i = $_GET['range'];

   if ((is_numeric($sbp_array[$i])) || (is_numeric($sbp_array[$i-1]))){

      if ($i == MODULE_SHOPBYPRICE_RANGES) {
	  	    $sbp_sql_range = ' and p.products_price >= ' . $sbp_array[$i-1] . ' ';
          } elseif ($i == 0) {
       $sbp_sql_range = ' and p.products_price < ' . $sbp_array[$i] . ' ';
          } else {
        $sbp_sql_range = ' and (p.products_price >= ' . $sbp_array[$i-1] . ' and p.products_price < ' . $sbp_array[$i] . ') ';
	  }
    }
  }
//    $listing_sql = "select p.products_id, p.products_price, p.products_tax_class_id, p.products_image, pd.products_name, p.products_date_added, m.manufacturers_name from  " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS . " p where p.products_status = '1' and pd.products_id = p.products_id and pd.language_id = '" . $languages_id . "' " . $sbp_sql_range . " order by p.products_price, pd.products_name ";

    $listing_sql = "select p.products_id, pd.products_name, p.products_image, p.products_price, p.products_tax_class_id, p.products_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, p.products_date_added, m.manufacturers_name from " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id where p.products_status = '1' " . $sbp_sql_range . " order by p.products_price, pd.products_name";

include(DIR_FS_MODULES . FILENAME_PRODUCT_LISTING);
?>
        </td>
      </tr>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
    </table>
