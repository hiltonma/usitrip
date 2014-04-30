
<?php echo tep_get_design_body_header(db_to_html('ËùÓÐ¾°µã')); ?><!-- body //-->

<table border="0" width="100%" cellspacing="3" cellpadding="3">

  <tr>

    

<!-- body_text //-->

    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">

        <tr> 

          <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

              <tr> 

                <td class='tableHeading'>
				<?php 
				//if($cId){echo HEADING_TITLE . ': ' . $title;}else{echo HEADING_TITLE;}
				
				if($cId !=''){
					$titledis='';
					$linkcroot = array();
					tep_get_parent_categories($linkcroot, $cId);
					$linkcroot = array_reverse($linkcroot);
					//$cRootsize = sizeof($linkcroot);
				
					foreach($linkcroot as $lkey => $lval)
						{
						$breadcrumb->add(db_to_html(tep_get_category_name($lval)));
						$titledis .= db_to_html(tep_get_category_name($lval)).": ";
						/*
						echo "size".(int)$cRootsize."</br>";
						echo "$lkey => $lval"."</br>";
						 if((int)$cRootsize < sizeof($linkcroot)){
						  $breadcrumb->add(tep_get_category_name($lval), FILENAME_LINKS . '?cRoot=' . $lval);
						 }else{
						 $breadcrumb->add(tep_get_category_name($lval), FILENAME_LINKS . '?lPath=' . $lval);		 
						 }
						 $cRootsize=$cRootsize -1;
						*/
						}

						$titledis .= db_to_html(tep_get_category_name($cId));		
					}

				if($cId){echo HEADING_TITLE . ': ' . $titledis;}else{echo HEADING_TITLE;}
				?>
				
				
				
				
				
				</td>

                <td class='tableHeading' align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_specials.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>

              </tr>

            </table></td>

        </tr>

        <tr> 

          <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

        </tr>


        <tr> 

          <td ><table border="0" width="95%" cellspacing="1" cellpadding="2">

              <tr> 

                <td width="50%" valign="top" class="main">&nbsp;</td>

                <td width="50%" valign="top" class="main">&nbsp;</td>

              </tr>

              <tr> 

                <td colspan="2" valign="top" class="main"> 

                  <?php 

				  if(!$cId){

				  require DIR_FS_CLASSES . 'allpro_tree.php'; $osC_CategoryTree = new osC_CategoryTree; echo db_to_html($osC_CategoryTree->buildTree()); 

				  }else{

				  	$product_query = tep_db_query("select pd.products_id, pd.products_name from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c  where pd.language_id = '" . (int)$languages_id . "' and pd.products_id = p2c.products_id and p2c.categories_id = '" . $cId . "'" );

                    while ($product = tep_db_fetch_array($product_query)){

	                echo ' <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cId . '&' : '') . 'products_id=' . $product['products_id']) . '">' .db_to_html($product['products_name']) . '</a><br>';

	

	                     }

					echo '<br><br><a href="allprods.php" class="main"><b> << '.TEXT_BACK.'</b></a>';	 

				  }

				  ?>

                </td>

              </tr>

            </table></td>

        </tr>

      </table></td>

<!-- body_text_eof //-->

    

  </tr>

</table>

<!-- body_eof //-->

<?php echo tep_get_design_body_footer();?>
