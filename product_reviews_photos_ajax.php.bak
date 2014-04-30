<?php
require_once("includes/application_top.php");
require(DIR_FS_INCLUDES . 'ajax_encoding_control.php');
require_once(DIR_FS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_REVIEWS_WRITE);
require_once(DIR_FS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);

if(isset($_POST['aryFormData']))
  {
 		$aryFormData = $_POST['aryFormData'];
	
		foreach ($aryFormData as $key => $value )
		{
		  foreach ($value as $key2 => $value2 )
		  {	  
		  	
			$HTTP_POST_VARS[$key] = stripslashes(str_replace('@@amp;','&',$value2));   
			//echo "$key=>$value2<br>";  	   
		  }
		}
		if(isset($HTTP_GET_VARS['products_id'])){
		$product_info['products_id'] = $HTTP_GET_VARS['products_id'];
		}
}	
?>

<?php
				$reviews_query_raw = "select * from ".TABLE_TRAVELER_PHOTOS." where products_id =".$_GET['products_id']." and image_status=1 order by traveler_photo_id desc";
				$reviews_split = new splitPageResults($reviews_query_raw, MAX_DISPLAY_NEW_REVIEWS, 'traveler_photo_id','rn');
				if ($reviews_split->number_of_rows > 0) {		
//echo MAX_DISPLAY_PAGE_LINKS;
				  if ($reviews_split->number_of_rows > MAX_DISPLAY_NEW_REVIEWS && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
				 ?>
				<tr>
	<td align="right" colspan="2">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr><td width="80%" align="left" nowrap="nowrap">
		<span class="sp6 sp10">
		<?php echo $reviews_split->display_count(TEXT_DISPLAY_NUMBER_OF_PHOTOS); ?>
	   </span></td><td align="right" width="20%" nowrap="nowrap"><span class="sp6 sp10">
				<?php  echo tep_draw_form('frm_slippage_ajax_product_photo_top', '' ,"",'id=frm_slippage_ajax_product_photo_top');	?>
				<?php echo  ' ' . $reviews_split->display_links_ajax_nextprev_only(5, 'mnu=photos&'.tep_get_all_get_params(array('rn','page','mnu','info')),'product_reviews_photos_ajax.php','frm_slippage_ajax_product_photo_top','con_two_3'); 
				echo '</form>';
				?>
				</span></td></tr></table>
				<?php
				// echo '<input type="hidden" name="selfpagename_treview" value="products_detail_review">';
				// echo '<input type="hidden" name="ajxsub_send_treview_req" value="true">';
				//    echo '</form>';
				
				//echo TEXT_RESULT_PAGE . ' ' . $reviews_split->display_links(6, 'mnu=photos&'.tep_get_all_get_params(array('rn','page','mnu','info')));
				/*echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links_ajax(6, tep_get_all_get_params(array('mnu', 'page','page1', 'products_durations1' , 'departure_city_id1' , 'tours_type1' ,'sort1', 'info', 'x', 'y')),'product_listing_index_products_ajax.php','frm_slippage_ajax_product_bottom','div_product_listing');
								  
*/									?>
</td></tr>
		
				  <?php
				  }
				  
				  ?>
				  <tr>
	<td  align="center" width="300">
				  <?php
 					$reviews_query = tep_db_query($reviews_split->sql_query);											
					$count=1;
					while ($reviews = tep_db_fetch_array($reviews_query)) {							
						?>
							<?php
							//echo DIR_WS_IMAGES.'reviews/thumb_'.$image_name;
							$image_name = $reviews['image_name'];
							if($image_name!='')
							{
								 echo tep_draw_form('product_photos_write'.$reviews['traveler_photo_id'],'','','id="product_photos_write'.$reviews['traveler_photo_id'].'"');
								 tep_draw_hidden_field('products_id',$_GET['products_id']);
								 tep_draw_hidden_field('traveler_photo_id',$reviews['traveler_photo_id']);
								 //tep_draw_hidden_field('products_id',$_GET['products_id']);
								 
								 echo '</form>';
								 $traveler_photo_id = $reviews['traveler_photo_id'];
								if(file_exists(DIR_FS_IMAGES.'reviews/thumb_'.$image_name)) 
								{
								 ?>
								  <div style="float:left; "><a href="javascript:sendFormData('product_photos_write<?php echo $reviews['traveler_photo_id']; ?>','<?php echo tep_href_link('product_photos_write.php', 'action=process&products_id='.$_GET['products_id'].'&traveler_photo_id='.$traveler_photo_id);?>','review_result_photo','true');">
								 <?php
								 echo tep_image(DIR_WS_IMAGES.'reviews/thumb_'.$image_name,'',75,60,' class="middle_l_2_2_photo" onmouseover="javascript:this.className=\'middle_l_2_2_photo1\'" onmouseout="javascript:this.className=\'middle_l_2_2_photo\'"');  
								 ?>
								 <!--<img src="<?php //echo DIR_WS_IMAGES.'reviews/thumb_'.$image_name; ?>" class="middle_l_2_2_photo" onmouseover="javascript:this.className='middle_l_2_2_photo1'" onmouseout="javascript:this.className='middle_l_2_2_photo'" height="60" width="70" />&nbsp;-->
								 
								 </a></div>
								 <?php
								 }
								else
								 {
								 	//echo tep_image(DIR_WS_IMAGES.'reviews/'.$image_name,'',80,70);  
								 ?>
								  <div style="float:left;"><a href="javascript:sendFormData('product_photos_write<?php echo $reviews['traveler_photo_id']; ?>','<?php echo tep_href_link('product_photos_write.php', 'action=process&products_id='.$_GET['products_id'].'&traveler_photo_id='.$traveler_photo_id);?>','review_result_photo','true');" >
								 <?php
								 echo tep_image(DIR_WS_IMAGES.'reviews/'.$image_name,'',75,60,' class="middle_l_2_2_photo" onmouseover="javascript:this.className=\'middle_l_2_2_photo1\'" onmouseout="javascript:this.className=\'middle_l_2_2_photo\'"');  
								 ?>
								 <!--<img src="<?php echo DIR_WS_IMAGES.'reviews/'.$image_name; ?>" class="middle_l_2_2_photo" onmouseover="javascript:this.className='middle_l_2_2_photo1'" onmouseout="javascript:this.className='middle_l_2_2_photo'" height="60" width="70" />&nbsp;-->
								 </a></div>
								 <?php
								 }
							}
						
						//echo tep_draw_separator('pixel_trans.gif', '1', '10');
						$count++;
					} //end of while loop
					
				}else{
				//now review of tour
				
				}
				?>
</td>
</tr>
									
								 
				
				<?php
				  
				  if ($reviews_split->number_of_rows > MAX_DISPLAY_NEW_REVIEWS && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
				 ?>
				 <tr><td align="right" colspan="2">		
<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr><td align="left" width="80%%" nowrap="nowrap">
		<span class="sp6 sp10">
<?php 
							   //echo $_GET['mnu'];
							   echo $reviews_split->display_count(TEXT_DISPLAY_NUMBER_OF_PHOTOS); ?>
	</span></td><td align="right" width="20%" nowrap="nowrap"><span class="sp6 sp10">
				<?php
				echo tep_draw_form('frm_slippage_ajax_product_bottom', '' ,"post",'id="frm_slippage_ajax_product_bottom"');		
								 ?>
							   <?php echo  ' ' . $reviews_split->display_links_ajax_nextprev_only(5, tep_get_all_get_params(array('mnu', 'page','page1', 'info', 'x', 'y')),'product_reviews_photos_ajax.php','frm_slippage_ajax_product_bottom','con_two_3');
							   echo '</form>';
							    ?>
				</span>	</td></tr></table>		</td></tr>
				<?php } ?>	
				
		
				
