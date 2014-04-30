<a name="parts"></a> <?php echo tep_draw_form('parts_quantity', tep_href_link_noseo(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'action=add_parts')); ?> 
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#ffffff">
              <tr> 
                <td>
				
				<table width="100%" border="0" cellpadding="1" cellspacing="1">
                    <?php
    $part_category_query = tep_db_query("select categories_id, parts_code from " . TABLE_CATEGORIES . " where parts_code =  '" . $product_info['products_model'] . "'");
	$part_category = tep_db_fetch_array($part_category_query);
	$part_category_id = $part_category['categories_id'];
	$parts_query_raw = 'select p.products_id, p.products_model, p.products_image, p.products_price, pd.products_name, pd.products_id, p2c.categories_id, p2c.products_id, p2c.categories_id from '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd , ' . TABLE_PRODUCTS_TO_CATEGORIES . ' p2c where p.products_id = pd.products_id and p.products_id = p2c.products_id and p2c.categories_id = "'.(int)$part_category_id.'" and pd.language_id = "'.(int)$languages_id.'" order by p.products_id asc';
    //$parts_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS_XSELL, $parts_query_raw, $$parts_query_numrows);
    
	
	//$parts_count_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$part_category_id . "'");
    //$parts_count = tep_db_fetch_array($parts_count_query);

	
	$parts_query = tep_db_query($parts_query_raw);
    while ($parts = tep_db_fetch_array($parts_query)) {
		//$xsold_query = tep_db_query('select * from '.TABLE_PRODUCTS_XSELL.' where products_id = "'.$_GET['add_related_product_ID'].'" and xsell_id = "'.$products['products_id'].'"');
?>
                    <tr bgcolor='#DFE4F4'> 
                      <td width="21%" align="center" class="main"><A HREF="parts_images_list.php?products_id=<?php echo $parts['products_id']?>" TARGET="_blank" ONCLICK="window.open('parts_images_list.php?products_id=<?php echo $parts['products_id']?>', 'Valid_Products', 'scrollbars=no,resizable=no,menubar=no,width=400,height=400'); return false"><u>view 
                        image</u></a></td>
                      <td width="45%" class="main">&nbsp;<?php echo $parts['products_name'] . '[' . $parts['products_model'] .']';?>&nbsp;</td>
                      <td width="14%" class="main">&nbsp;<?php echo $currencies->format($parts['products_price']);?>&nbsp;</td>
                      <td width="20%" class="main">&nbsp;<?php echo tep_draw_checkbox_field('parts_sell[]', $parts['products_id'], ((tep_db_num_rows($parts_query) > 0) ? false : false), '', ' onMouseOver="this.style.cursor=\'hand\'"');?>&nbsp; 
                        <?php echo 'choose it';?> 
                        &nbsp;</td>
                    </tr>
                    <?php
   $parts_exsit=1;
    }
?>
                    <tr align="right"> 
                      <td colspan="4"><?php 
					  if($parts_exsit){
					  echo tep_draw_hidden_field('products_id', $product_info['products_id']) . tep_image_submit('button_in_cart.gif', IMAGE_BUTTON_IN_CART); 
					  }
					  ?></td>
                    </tr></form>
                  </table>
				 
				  
				  </td>
              </tr>
            </table>
