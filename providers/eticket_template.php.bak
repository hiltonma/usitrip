<?php
require('includes/application_top_providers.php');
if (!session_is_registered('providers_id')){
	tep_redirect(tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_LOGIN, '', 'SSL'));
}
$provider_id=$_SESSION['providers_id'];
$agency_id=$_SESSION['providers_agency_id'];
$products_id=$_GET['products_id'];
$languages[] = array("id" => 1, "name" => "English", "code" => "en", "image" => "icon.gif", "directory" => "english");

require(DIR_FS_PROVIDERS_LANGUAGES . $language . '/' . FILENAME_ETICKET_TEMPLATE);

if($_POST['btnupdate']!=""){
	$product_sql_data_array = array('products_special_note' => tep_db_prepare_input($HTTP_POST_VARS['products_special_note']), 
																	'products_last_modified' => 'now()');
	tep_db_perform(TABLE_PRODUCTS, $product_sql_data_array, 'update', "products_id = '" . (int)$products_id . "'");
	MCache::update_product($products_id);//MCache update
	if($HTTP_POST_VARS['products_durations_type'] == 0 && $HTTP_POST_VARS['products_durations'] != 0 && $HTTP_POST_VARS['products_durations'] > 0 ){
		$duration_count = $HTTP_POST_VARS['products_durations'];
	}else if($HTTP_POST_VARS['products_durations_type'] != 0){
		$duration_count = 1;
	}
	
	for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
		$language_id = $languages[$i]['id'];
		$eticket_itinerary_separated = '';
		$eticket_hotel_separated = '';
		$eticket_notes_separated = '';
		for($dj=1; $dj<=$duration_count; $dj++){
			//echo $HTTP_POST_VARS[eticket_notes][$language_id][$dj].'<br>'; //!##!
			if($eticket_itinerary_separated == ''){						
				if($HTTP_POST_VARS['eticket_itinerary'][$language_id][$dj] == ''){
					$eticket_itinerary_separated = ' ';
				}else{	
					$eticket_itinerary_separated = $HTTP_POST_VARS['eticket_itinerary'][$language_id][$dj];
				}
			}else{
				$eticket_itinerary_separated .= '!##!'.$HTTP_POST_VARS['eticket_itinerary'][$language_id][$dj];
			}
			
			if($eticket_hotel_separated == ''){
				if($HTTP_POST_VARS['eticket_hotel'][$language_id][$dj] == ''){
					$eticket_hotel_separated = ' ';
				}else{
					$eticket_hotel_separated = $HTTP_POST_VARS['eticket_hotel'][$language_id][$dj];
				}
			}else{
				$eticket_hotel_separated .= '!##!'.$HTTP_POST_VARS['eticket_hotel'][$language_id][$dj];
			}
			
			if($eticket_notes_separated == ''){
				if($HTTP_POST_VARS['eticket_notes'][$language_id][$dj] == ''){
					$eticket_notes_separated = ' ';
				}else{
					$eticket_notes_separated = $HTTP_POST_VARS['eticket_notes'][$language_id][$dj];
				}
			}else{
				$eticket_notes_separated .= '!##!'.$HTTP_POST_VARS['eticket_notes'][$language_id][$dj];
			}
		}					 
		
		$sql_data_array = array('eticket_itinerary' => tep_db_prepare_input($eticket_itinerary_separated),
														'eticket_hotel' => tep_db_prepare_input($eticket_hotel_separated),
														'eticket_notes' => tep_db_prepare_input($eticket_notes_separated));
		tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "' and language_id = '" . (int)$language_id . "'");
		MCache::update_product($products_id);//MCache update
	}
	
	tep_redirect(tep_href_link(DIR_WS_PROVIDERS.FILENAME_ETICKET_TEMPLATE, tep_get_all_get_params(array("action", "msg"))."msg=1", "SSL"));
}

require(DIR_FS_PROVIDERS_INCLUDES . FILENAME_PROVIDERS_HEADER);
?>
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="body_min_height">
	<tr valign="top">
		<td colspan="2" align="center">
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
		<tr valign="top">
			<td class="login_heading" colspan="2">&nbsp;<b><?php echo TEXT_HEADING_ETICKET_INFORMATION; ?></b></td>
		</tr>
<?php 
	if($_GET['msg']=='1'){?>
		<tr valign="top">
			<td class="successMsg" colspan="2" align="center">&nbsp;<?php echo MSG_SUCCESS; ?></td>
		</tr>
<?php 
	}
	if($products_id != ''){
		$qry_eticket_info = "SELECT p.products_id, p.products_durations, p.products_durations_type, pd.eticket_itinerary, pd.eticket_hotel, pd.eticket_notes, pd.products_pricing_special_notes, p.products_special_note FROM " . TABLE_PRODUCTS . " p, ".TABLE_PRODUCTS_DESCRIPTION." pd WHERE p.products_id = pd.products_id and p.products_id = '" . (int)$products_id . "' AND p.agency_id='".$agency_id."'";
		$res_eticket_info = tep_db_query($qry_eticket_info);
		$row_eticket_info = tep_db_fetch_array($res_eticket_info);
		if(tep_db_num_rows($res_eticket_info) > 0 && $row_eticket_info['products_id'] != ""){?>
			<tr>
				<td class="main" colspan="2">
					<form name="new_product"  id="new_product" action="" method="post">		
					<table border="0" width="100%" height="100%" cellspacing="3" cellpadding="2" bgcolor="#F0F0FF" class="grayBorder login">
					<tr>
					<td colspan="2">
						<table width="100%" border="0" cellspacing="0" cellpadding="2">  			
						<tr>			
						<td  valign="top">
						<div id="eticket_div" >
							<?php			
								if($row_eticket_info['products_durations_type'] == 0 && $row_eticket_info['products_durations'] !=0 && $row_eticket_info['products_durations'] > 0 ){
									$count = $row_eticket_info['products_durations'];
								}else if($row_eticket_info['products_durations_type'] != 0){
									$count = 1;
								}?>
									<table width = "100%"  >
										<tr>
											<td colspan=2 ></td>
											<td>
												<table width="100%"   border="0" >
																	<tr>
									<td width="10%"></td>
									
									<td class="main"  align="left"  width="30%">
									<b>	<?php echo TEXT_HEADING_ETICKET_ITINERARY; ?></b>
									</td>
									<td class="main" align="left" width="30%">
										<b><?php echo TEXT_HEADING_ETICKET_HOTEL; ?> </b>
									</td>
									<td class="main" align="left" width="30%" >
										<b><?php echo TEXT_HEADING_ETICKET_NOTES; ?> </b>
									</td>
								</tr>
								</table></td></tr>
					
					
							<?php
							 for ($i=0, $n=sizeof($languages); $i<$n; $i++){?>
							 <tr>
								<td colspan=2 class="main" >
								
									<?php echo TEXT_HEADING_ETICKET;//. ' ' .  tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp; &nbsp; &nbsp; &nbsp;
								</td>
					
								<td>
									<table width="60%"  border="0" >
										<tr>
											<td>		
					
								<table width="100%">				
									<?php 
									for($j=1; $j<=$count; $j++)
									{
										$content_prod=tep_get_products_eticket_itinerary($products_id, $languages[$i]['id']);
										$splite_content =  explode("!##!", $content_prod);
									
									
										$content_prod_hotel=tep_get_products_eticket_hotel($products_id, $languages[$i]['id']);
										$splite_content_hotel =  explode("!##!", $content_prod_hotel);
									
												$content_prod_notes=tep_get_products_eticket_notes($products_id, $languages[$i]['id']);
												$splite_content_notes =  explode("!##!", $content_prod_notes);
										
									?>				
												<tr>
													 <td class="main" align="center" width="10%" nowrap>Day <?php echo $j ?></td> 
													<td class="main" align="center" width="30%"><?php echo tep_draw_textarea_field('eticket_itinerary[' . $languages[$i]['id'] . ']['.$j.']', 'soft', '30', '6', (isset($eticket_itinerary[$languages[$i]['id']]) ? $eticket_itinerary[$languages[$i]['id']] : $splite_content[$j-1]),'id=eticket_itinerary[' . $languages[$i]['id'] . ']'); ?>
													</td>
									<td class="main" width="30%" align="center"><?php echo tep_draw_textarea_field('eticket_hotel[' . $languages[$i]['id'] . ']['.$j.']', 'soft', '30', '6', (isset($eticket_hotel[$languages[$i]['id']]) ? $eticket_hotel[$languages[$i]['id']] : $splite_content_hotel[$j-1]),'id=eticket_hotel[' . $languages[$i]['id'] . ']'); ?></td>
									
												 <td class="main" width="30%" align="center">
													<?php echo tep_draw_textarea_field('eticket_notes[' . $languages[$i]['id'] . ']['.$j.']', 'soft', '30', '6', (isset($eticket_notes[$languages[$i]['id']]) ? $eticket_notes[$languages[$i]['id']] : $splite_content_notes[$j-1]),'id=eticket_notes[' . $languages[$i]['id'] . ']'); ?>
													</td>
									
												</tr>
												
												
											<?php		
											}	
											?>
											</table>
												</td></tr></table></td></tr>
									
									<?php	
											}?>				
													</table>
						
						
						</div>	
						</td></tr></table>
						
						 
						<table width="100%" border="0" cellspacing="0" cellpadding="2">
							<tr>
							<td class="main" width="15%" nowrap><?php echo TEXT_HEADING_SPL_NOTES; ?></td>
							<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' .tep_draw_textarea_field('products_special_note', 'soft', '90', '15',$row_eticket_info['products_special_note']); ?></td>
							</tr>
							 <tr>
							<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
							</tr>
							</table>
					
					</td>
					</tr>
					<tr>
					<td colspan="2" align="center">
						<input type="submit" name="btnupdate" value="Update"> &nbsp; &nbsp; 
				<?php 
					if($_GET['frm'] == 'solddates'){
						$prev_page_link = tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_SOLD_DATES, "", "SSL");
					}else{
						$prev_page_link = tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_ORDERS,tep_get_all_get_params(array("action","msg"))."&action=edit_order", "SSL");
					}?>
					
					<a href="<?php echo $prev_page_link;?>" ><strong><?php echo TEXT_BACK;?></strong></a>
				<?php
						echo tep_draw_hidden_field('products_durations_type', $row_eticket_info['products_durations_type']);
						echo tep_draw_hidden_field('products_durations', $row_eticket_info['products_durations']);
					?>
					<input type="hidden" name="oID" value="<?php echo $_REQUEST['oID'];?>">
					<input type="hidden" name="req_section" value="tour_eticket">
					<input type="hidden" name="qaanscall" value="true">
					</td>
					</tr>
					 <tr>
								<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
							</tr>
						
					</table>
					</form>
				</td>
			</tr>
<?php	}else{
			echo '<tr><td>'.MSG_RECORD_NOT_FOUND.'</td></tr>';
		}
	}
?>
		</table>
		</td>
	</tr>
</table>