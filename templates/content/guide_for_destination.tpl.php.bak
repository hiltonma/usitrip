<?php echo tep_get_design_body_header(HEADING_TITLE); ?>  
   <table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
     <tr>
     <td>
     <?php  
     $total_cpath_array = '25,24,33';
     
     $new_arr_total_cpath_array = explode(',',$total_cpath_array);
     echo '<table align="center" border="0" width="100%" cellspacing="5" cellpadding="5"><tr><td align="left"><b>'.db_to_html('Ä¿µÄµØ£º').'</b>';
	 $i = 0;
     foreach($new_arr_total_cpath_array as $key11 => $val11){
	 if ($i == count($new_arr_total_cpath_array) - 1){
	 	echo '<a class="CatHeadingTitle" href="'.$PHP_SELF.'#'.$val11.'" >'.db_to_html(tep_get_category_name($val11)).'</a>';
	 }else{
	 	echo '<a class="CatHeadingTitle" href="'.$PHP_SELF.'#'.$val11.'" >'.db_to_html(tep_get_category_name($val11)).'</a> | ';
	 }
     
	 $i++;
     
     }
     echo '</td></tr></table></td></tr>';
                          
      foreach($new_arr_total_cpath_array as $key => $val){
        $cPath = $val;
        $current_category_id = $val;
        ?>
             
              <tr>
              <td class="TitleTextBg"><a name="<?php echo $current_category_id;?>"></a><b><?php echo db_to_html(tep_get_category_name($current_category_id));?></b> 
              </td>
              </tr>
              
                  <tr>
                    <td><table border="0" width="100%" cellspacing="3" cellpadding="5">
                      <tr>
                            <?php
                                if (isset($cPath) && strpos('_', $cPath)) {
                            // check to see if there are deeper categories within the current category
                                  $category_links = array_reverse($cPath_array);
                                  for($i=0, $n=sizeof($category_links); $i<$n; $i++) {
                                    $categories_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$category_links[$i] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "'");
                                    $categories = tep_db_fetch_array($categories_query);
                                    if ($categories['total'] < 1) {
                                      // do nothing, go through the loop
                                    } else {
                                      $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$category_links[$i] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");
                                      break; // we've found the deepest category the customer is in
                                    }
                                  }
                                } else {
                                  $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");
                                }
                            
                                $number_of_categories = tep_db_num_rows($categories_query);
                            
                                $rows = 0;
                                while ($categories = tep_db_fetch_array($categories_query)) {
                                  
                                  $rows++;
                                  $cPath_new = tep_get_path($categories['categories_id']);
                                  $width = (int)(100 / MAX_DISPLAY_CATEGORIES_PER_ROW) . '%';
                                  echo '                <td align="center" class="smallText" width="' . $width . '" valign="top"><a href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new) . '">' . tep_image(DIR_WS_IMAGES . $categories['categories_image'], db_to_html($categories['categories_name']), 100, 60) . '<br />' . db_to_html($categories['categories_name']) . '</a></td>' . "\n";

                                  if ((($rows / MAX_DISPLAY_CATEGORIES_PER_ROW) == floor($rows / MAX_DISPLAY_CATEGORIES_PER_ROW)) && ($rows != $number_of_categories)) {
                                    echo '              </tr>' . "\n";
                                    echo '              <tr>' . "\n";
                                  }
                                }
                            
                            // needed for the new products module shown below
                                $new_products_category_id = $current_category_id;
                            ?>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td  align="right" ><a class="TitleTextBlue" href="javascript:scroll(0,0);"><?php echo TEXT_TOP; ?></a></td>
                </tr>
                  <tr>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                </tr>
        <?php 
          }
         ?>
            
        </table>
<?php
//}
 ?>
<?php echo tep_get_design_body_footer();?>
