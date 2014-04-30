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
            <td class="pageHeading" align="right"><?php // echo tep_image(DIR_WS_IMAGES . 'table_background_products_new.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
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

      <tr>
        <td>
<?php
    $featured_products_query_raw = "select p.products_id, p.products_price, p.products_tax_class_id, p.products_image from  " . TABLE_FEATURED . " f, " . TABLE_PRODUCTS . " p where p.products_id = f.products_id  and f.status = '1' order by rand() DESC limit " . MAX_DISPLAY_FEATURED_PRODUCTS . " ";
 //   $featured_products_query_raw = tep_db_query("select p.products_id, p.products_price, p.products_tax_class_id, p.products_image from  " . TABLE_FEATURED . " f, " . TABLE_PRODUCTS . " p where p.products_id = f.products_id  and f.status = '1' order by rand() DESC limit " . MAX_DISPLAY_FEATURED_PRODUCTS);

// $featured_products_query_raw = "select p.products_id, pd.products_name, p.products_image, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, p.products_date_added, m.manufacturers_name from " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_FEATURED . " f on p.products_id = f.products_id where p.products_status = '1' and f.status = '1' order by rand($mtm) LIMIT " . MAX_DISPLAY_FEATURED_PRODUCTS ." ";
 //  $featured_products_query_raw = "select p.products_id, pd.products_name, p.products_image, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, p.products_date_added, m.manufacturers_name from " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_FEATURED . " f on p.products_id = f.products_id where p.products_status = '1' and f.status = '1' order by p.products_date_added DESC, pd.products_name LIMIT " . MAX_DISPLAY_FEATURED_PRODUCTS ." ";

  $featured_products_split = new splitPageResults($featured_products_query_raw, MAX_DISPLAY_FEATURED_PRODUCTS_LISTING);



  if (($featured_products_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
      <tr>
        <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText"><?php echo $featured_products_split->display_count(TEXT_DISPLAY_NUMBER_OF_FEATURED); ?></td>
            <td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $featured_products_split->display_links(MAX_DISPLAY_FEATURED_PRODUCTS_LISTING, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
          </tr>
        </table></td>
      </tr>
<?php
  }
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD


  $featured_products_query = tep_db_query($featured_products_query_raw);
  while ($featured_products = tep_db_fetch_array($featured_products_query)) {


    $featured_products_array[] = array('id' => $featured_products['products_id'],
                                  'name' => $featured_products['products_name'],
                                  'image' => $featured_products['products_image'],
                                  'price' => $featured_products['products_price'],
                                  'specials_price' => $featured_products['specials_new_products_price'],
                                  'tax_class_id' => $featured_products['products_tax_class_id'],
                                  'date_added' => tep_date_long($featured_products['products_date_added']),
                                  'manufacturer' => $featured_products['manufacturers_name']);
  }


  require(DIR_FS_MODULES  . FILENAME_FEATURED_PRODUCTS);


// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
        </td>
      </tr>
<?php
  if (($featured_products_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>
      <tr>
        <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText"><?php echo $featured_products_split->display_count(TEXT_DISPLAY_NUMBER_OF_FEATURED); ?></td>
            <td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $featured_products_split->display_links(MAX_DISPLAY_FEATURED_PRODUCTS_LISTING, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
    </table>
