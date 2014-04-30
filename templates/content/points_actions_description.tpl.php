<?php 
if($is_my_account != true){
echo tep_get_design_body_header('');
}
 ?>
					 <!-- content main body start -->
					 		<table width="100%"  border="0" cellspacing="3" cellpadding="0" style="border:1px solid #AED5FF;">
							  <tr>
								<td>
								<?php
								require('includes/rewards4fun_page_navi.php');
								?>
								</td>
							  </tr>
							  <tr>
								<td height="15"></td>
							  </tr>
							  <tr>
								<td class="main"> <?php echo TEXT_INTRO; ?></td>
							  </tr>
							   <tr>
								<td height="15"></td>
							  </tr>
							   <tr>
								<td class="main"><b><a href="<?php echo tep_href_link(FILENAME_DEFAULT,'', 'NONSSL'); ?>"><?php echo TITLE_TOUR_BOOK; ?></a></b></td>
							  </tr>
							  <tr>
								<td class="main"> <?php echo TEXT_TOUR_BOOK; ?></td>
							  </tr>
							  <tr>
								<td height="15"></td>
							  </tr>
							   <tr>
								<td class="main"><b><?php echo TITLE_REFER; ?></b></td>
							  </tr>
							   <tr>
								<td class="main"> <?php echo sprintf(TEXT_REFER,USE_REFERRAL_SYSTEM); ?></td>
							  </tr>
							  <tr>
								<td height="15"></td>
							  </tr>
							  <tr>
								<td class="main"><b><?php echo TITLE_REVIEW_PHOTO; ?></b></td>
							  </tr>
							   <tr>
								<td class="main"><?php echo sprintf(TEXT_REVIEW_PHOTO, USE_POINTS_FOR_REVIEWS, 100); ?></td>
							  </tr>
							   <tr>
								<td height="15"></td>
							  </tr>
							  <tr>
								<td class="main"><b><?php echo TITLE_FEEDBACK; ?></b></td>
							  </tr>
							   <tr>
								<td class="main"><?php echo TEXT_FEEDBACK; ?></td>
							  </tr>
							  <tr>
								<td height="15"></td>
							  </tr>
							  <tr>
								<td class="main"><b><?php echo TFF_POINTS_DESCRIPTION; ?></b></td>
							  </tr>
							   <tr>
								<td class="main"><?php echo TFF_POINTS_DESCRIPTION_CONTENT; ?></td>
							  </tr>
							  <tr>
								<td height="15"></td>
							  </tr>
							  
							  <tr>
								<td class="main"><b><?php echo TEXT_HOW_SAVE; ?></b></td>
							  </tr>
							   <tr>
								<td class="main"><?php echo db_to_html('有效积分可使用在预订中，').TEXT_SAVINGS; ?></td>
							  </tr>
							  <tr>
								<td height="20"></td>
							  </tr>
							  
							  <tr>
								<td class="main"><?php echo TEXT_BOTTOM; ?></td>
							  </tr>
							   <tr>
								<td height="15"></td>
							  </tr>
							  
							</table><!-- content main body end -->
<?php 
if($is_my_account != true){
echo tep_get_design_body_footer();
}
?>