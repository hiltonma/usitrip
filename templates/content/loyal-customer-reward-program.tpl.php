<?php echo tep_get_design_body_header(TEXT_HEADING_LOYAL_CUSTOMER_REWARD); ?>
					 <!-- content main body start -->
					 		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
							 <tr>
								<td class="main" style="background-color:#FFFFFF;" width="60%">
								<table width="100%"  border="0" cellspacing="5" cellpadding="5">
								  <tr>
									<td>											
											<table width="100%"  border="0" cellspacing="0" cellpadding="0">
												<tr>
												<td colspan="2">
													<img src="image/loyal_welcometo.jpg" alt="<?php echo TEXT_ALTER_TAG; ?>" />
												</td>
												</tr>												
												 <tr>
													<td height="15" colspan="2"></td>
												 </tr>												  
												<tr>
												<td width="5%">&nbsp;</td>
												<td width="95%" class="main"><b><?php echo TEXT_LOAYAL_CUSTOSMER_PARA_1;?></b></td>
												</tr>
												 <tr>
													<td height="15" colspan="2"></td>
												  </tr>
												<tr>
													<td valign="top"><img src="image/loyal_checked_icon.gif" alt="<?php echo TEXT_ALTER_TAG; ?>" /></td>
													<td width="95%" class="main" valign="top">
													<?php echo TEXT_LOAYAL_CUSTOSMER_PARA_2;?>
													</td>
												</tr>
												 <tr>
													<td height="15" colspan="2"></td>
												</tr>
												<tr>
													<td valign="top"><img src="image/loyal_checked_icon.gif" alt="<?php echo TEXT_ALTER_TAG; ?>" /></td>
													<td width="95%" class="main" valign="top">
													<?php echo TEXT_LOAYAL_CUSTOSMER_PARA_3;?>
													</td>
												</tr>
												 <tr>
													<td height="15" colspan="2"></td>
												</tr>
												<tr>
													<td valign="top"><img src="image/loyal_checked_icon.gif" alt="<?php echo TEXT_ALTER_TAG; ?>" /></td>
													<td width="95%" class="main" valign="top">
													<?php echo TEXT_LOAYAL_CUSTOSMER_PARA_4;?>
													</td>
												</tr>
												 <tr>
													<td height="15" colspan="2"></td>
												</tr>
												<tr>
													<td valign="top"><img src="image/loyal_checked_icon.gif" alt="<?php echo TEXT_ALTER_TAG; ?>" /></td>
													<td width="95%" class="main" valign="top">
													<?php echo TEXT_LOAYAL_CUSTOSMER_PARA_5;?>
													</td>
												</tr>
												 <tr>
													<td height="15" colspan="2"></td>
												</tr>												  
										  </table>
									
									
									</td>
								  </tr>
								</table>

								
    							</td>
								<td width="2%"></td>
								<td class="main" valign="top" >
										<table width="100%"  class="loyal_table_border_orange"  border="0" cellspacing="0" cellpadding="0">
										  <tr >
											<td height="32"  colspan="2" class="loyal_hot_tour_heading" valign="middle">&nbsp;&nbsp;&nbsp;<?php echo TEXT_HEADIMG_HOT_TOURS_LOAYL;?></td>											
										  </tr>
										  <tr style="background-color:#FFFFFF;">
											<td height="10" colspan="2"></td>
										  </tr>
										   <?php

											  $new_arr_producs_array = explode(',',TOURS_HOMEPAGE_SPECIAL_OFFERS);
					
											  foreach($new_arr_producs_array as $key => $val){
					
												 $product_query_select_new = "select distinct p.products_id,pd.products_name, p.products_image, p.products_model from ".TABLE_PRODUCTS." as p, ".TABLE_PRODUCTS_DESCRIPTION." as pd where  p.products_id = pd.products_id and pd.language_id='".(int) $languages_id."' and p.products_status=1 and p.products_id = ".(int)$val."";
					
					
												  $new_products_query_arrive = tep_db_query($product_query_select_new);
					
												  while ($new_products_arrive = tep_db_fetch_array($new_products_query_arrive)) {
					
					
												  ?>
											  <tr style="background-color:#FFFFFF;">
												<td width="8" valign="top">&nbsp;&nbsp;&bull;&nbsp;
												</td>	
												<td valign="top"><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products_arrive['products_id']);?>" ><?php echo db_to_html(tep_db_prepare_input($new_products_arrive['products_name']));?></a> [<?php echo $new_products_arrive['products_model']; ?>]
												</td>											
											  </tr>					
											  
											    <?php
												 }
											  }
											  ?>		
											   <tr style="background-color:#FFFFFF;">
												<td height="10" colspan="2"></td>
											  </tr>			  
										</table>
    							</td>
							  </tr>							 
							</table>
<!-- content main body end -->
<?php echo tep_get_design_body_footer();?>