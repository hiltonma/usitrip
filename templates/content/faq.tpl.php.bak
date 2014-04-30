<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
  </tr>
  <tr>
    <td>
	<?php
	
	$faq_category_query = tep_db_query("select icd.categories_id, icd.categories_name, icd.categories_description from " . TABLE_FAQ_CATEGORIES_DESCRIPTION . " icd, " . TABLE_FAQ_CATEGORIES . " ic where ic.categories_id = icd.categories_id and icd.language_id = '" . (int)$languages_id . "' and ic.categories_status = '1' order by categories_sort_order");
  	
	if(tep_db_num_rows($faq_category_query) > 0) {
	?>
	
			<table width="100%" class="faq_tab_background" border="0" cellpadding="0" cellspacing="0">
			  <tr>
				<td>
					<table   border="0" cellpadding="0" cellspacing="0">
					  <tr height="30">
					  <?php 					   $faq_row_cnts = 0;
					   $display_faq_selected = 'class="faq_normal"';
					   while($faq_category = tep_db_fetch_array($faq_category_query)){
					  
					   if( $display_mode != 'faq'){
						
							if($faq_row_cnts == 0){
							 	$display_faq_selected = 'class="faq_selected"';
								$cID = (int)$faq_category['categories_id'];
								$cate_short_description = $faq_category['categories_description'];
							}else{
								$display_faq_selected = 'class="faq_normal"';
							}
					   
					   
					   }else{
					   	
							if($cID == $faq_category['categories_id']){
							 	$display_faq_selected = 'class="faq_selected"';
								$cate_short_description = $faq_category['categories_description'];
							}else{
								$display_faq_selected = 'class="faq_normal"';
							}
					   
					   }
					  ?>
						 <td <?php echo $display_faq_selected; ?>>&nbsp;&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_FAQ, 'cID=' . (int)$faq_category['categories_id']) . '">' . $faq_category['categories_name'] . '</a>';?>&nbsp;&nbsp;&nbsp;</td>
						 <?php
						  $faq_row_cnts++;
						  } ?>
					  </tr>
					</table>
				</td>
			  </tr>
			</table>
	<?php } ?>
	</td>
  </tr>
  
  <?php
  if(strtolower($faq_categories_value['categories_name']) == "specific tour faq"){
 ?>
	

			

   <tr>
   <td class="mainbodybackground" >	
		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
			     <tr ><td height="15" ></td></tr>
				 <tr><td height="20" class="main" > <?php echo tep_draw_separator('pixel_trans.gif', '30', '1').$cate_short_description; ?>
				 </td></tr>
				 <tr><td style="border-top:1px solid #B7E3FB;">&nbsp;</td></tr>
				  <?php
				   $products_faq_query_raw = "select p.products_id, pd.products_name, p.products_model from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, ". TABLE_QUESTION ." qa  where p.products_id = pd.products_id and p.products_id = qa.products_id and p.products_status='1' and qa.question != '' and qa.languages_id = '" . (int)$languages_id . "' and pd.language_id = '" . (int)$languages_id . "' group by p.products_id  order by products_name";
				
				  	$products_faq_split = new splitPageResults($products_faq_query_raw, MAX_DISPLAY_SEARCH_RESULTS);
				  
				  
					 if (($products_faq_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
				?>
						 <tr>
							<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
						  </tr>
						  <tr>
							<td><table border="0" width="95%" class="automarginclass"  align="center" cellspacing="0" cellpadding="2">
							  <tr>
								<td class="smallText"><?php echo $products_faq_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
								<td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $products_faq_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info'))); ?></td>
							  </tr>
							</table></td>
						  </tr>
						  <tr>
							<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '15'); ?></td>
						  </tr>
				<?php
				  }
				?>
				 <tr><td>
				 <?php
				 
					$products_faq_query = tep_db_query($products_faq_split->sql_query);
				
					while ($products_faqs = tep_db_fetch_array($products_faq_query)) {
					//$select_row_color =1;
					?>
					<div class="pr_q2_faq_q1">
					<div class="sp10 sp6" style="float: left; width: 700px; margin-top: 5px;  line-height:20px; ">
					 <A  class="sp14blue" style="CURSOR: pointer" onclick="javascript:toggel_div('div_product_faq_question_<?php echo $products_faqs['products_id'];?>')"><b>&bull; <?php echo $products_faqs['products_name'];?></b></a>&nbsp;&nbsp; <a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_faqs['products_id']); ?>" class="sp3">[View tour detail] </a>
					</div>
					</div>
									
					<?php
					include('specific_tour_faq_ajax.php');				
					}
				 ?>
				 </td></tr>
				 
				 <?php /*
				  <tr>
					<td>
							<?php
								$toc_query = tep_db_query("select ip.faq_id, ip.question, ip.answer from " . TABLE_FAQ . " ip left join " . TABLE_FAQ_TO_CATEGORIES . " ip2c on ip2c.faq_id = ip.faq_id where ip2c.categories_id = '" . (int)$cID . "' and ip.language = '" . $language . "' and ip.visible = '1' order by ip.v_order, ip.question");
								$faq_row_que_cnts  = 0;
								while ($toc = tep_db_fetch_array($toc_query)) {
								
								 ?>
								    <div class="pr_q2_faq_q1">
									<div class="pr_q2_q_t sp11 sp14blue">				
										<TABLE WIDTH="100%" align="center" BORDER=0 CELLPADDING=0 CELLSPACING=0>
										<TR>
											<TD WIDTH=9 HEIGHT=8>
												<IMG SRC="image/que_ask_cornner_01.gif" WIDTH=9 HEIGHT=8 ALT=""></TD>
											<TD style="background-image: url(image/que_ask_cornner_02.gif);background-repeat: repeat-x">
												</TD>
											<TD WIDTH=11 HEIGHT=8>
												<IMG SRC="image/que_ask_cornner_04.gif" WIDTH=11 HEIGHT=8 ALT=""></TD>
										</TR>
										<TR>
											<TD style="background-image: url(image/que_ask_cornner_05.gif);background-repeat: repeat-y" WIDTH=9 >
												</TD>
											<TD bgcolor="#E1F3FD" valign="top" width="100%">
													<table width="100%"  border="0" cellspacing="0" cellpadding="0">
													  <tr>
													  <td nowrap class="pr_q2_q sp14blue" valign="top"><b>Q:&nbsp;</b></td>
														<td width="100%" class="main" valign="top" >														
														 <A  class="sp14blue" style="CURSOR: pointer" onclick="javascript:toggel_div('div_faq_question_<?php echo $toc['faq_id'];?>')"><?php echo $toc['question'];?></a> 
														</td>														
													  </tr>
													</table>			
											</TD>
											<TD  style="background-image: url(image/que_ask_cornner_07.gif);background-repeat: repeat-y" WIDTH=11>
											</TD>
										</TR>
										<TR>
											<TD WIDTH=9 HEIGHT=10>
												<IMG SRC="image/que_ask_cornner_10.gif" WIDTH=9 HEIGHT=10 ALT=""></TD>
											<TD style="background-image: url(image/que_ask_cornner_11.gif);background-repeat: repeat-x"  WIDTH=7 HEIGHT=10>
												</TD>		
											<TD WIDTH=11 HEIGHT=10>
												<IMG SRC="image/que_ask_cornner_13.gif" WIDTH=11 HEIGHT=10 ALT=""></TD>
										</TR>
									</TABLE>				
									</div>
									</div>
									
									<div id="div_faq_question_<?php echo $toc['faq_id'];?>" style="DISPLAY: none">   
								    <div class="pr_q2_faq_a">
									<div class="pr_q2_faq_qqq sp1">A:</div>
									<div class="sp10 sp6" style="float: left; width: 615px;  margin-top: 5px; margin-left: 2px; ">
									<?php		
												echo  $toc['answer'];
												?>
									</div>
									</div>
									</div>
									
								 
								<?php
								$faq_row_que_cnts++;
								}
								
								?>
								
					</td>
				  </tr>				
				  
				  */ ?>
				  
				 <?php
					 if (($products_faq_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
				?>
						 <tr>
							<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '15'); ?></td>
						  </tr>
						  <tr>
							<td><table border="0" width="95%" class="automarginclass"  align="center" cellspacing="0" cellpadding="2">
							  <tr>
								<td class="smallText"><?php echo $products_faq_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
								<td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $products_faq_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info'))); ?></td>
							  </tr>
							</table></td>
						  </tr>
						  <tr>
							<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '15'); ?></td>
						  </tr>
				<?php
				  }
				?>
			</table>
	
	</td>
	</tr>
	
	
  <?php }else{
  ?>
  <tr>
    <td class="mainbodybackground">
	<?php
	if($faq_row_cnts > 0){
	?>
	
			
			<table width="100%"  border="0" cellspacing="0" cellpadding="0">
			     <tr ><td height="15" ></td></tr>
				 <tr><td height="20" class="main" > <?php echo tep_draw_separator('pixel_trans.gif', '30', '1').$cate_short_description; ?>
				 </td></tr>
				 <tr><td style="border-top:1px solid #B7E3FB;">&nbsp;</td></tr>
				  <tr>
					<td>
							<?php
								$toc_query = tep_db_query("select ip.faq_id, ip.question, ip.answer from " . TABLE_FAQ . " ip left join " . TABLE_FAQ_TO_CATEGORIES . " ip2c on ip2c.faq_id = ip.faq_id where ip2c.categories_id = '" . (int)$cID . "' and ip.language = '" . $language . "' and ip.visible = '1' order by ip.v_order, ip.question");
								$faq_row_que_cnts  = 0;
								while ($toc = tep_db_fetch_array($toc_query)) {
								
								 ?>
								    <div class="pr_q2_faq_q1">
									<div class="pr_q2_q_t sp11 sp14blue">				
										<TABLE WIDTH="100%" align="center" BORDER=0 CELLPADDING=0 CELLSPACING=0>
										<TR>
											<TD WIDTH=9 HEIGHT=8>
												<IMG SRC="image/que_ask_cornner_01.gif" WIDTH=9 HEIGHT=8 ALT=""></TD>
											<TD style="background-image: url(image/que_ask_cornner_02.gif);background-repeat: repeat-x">
												</TD>
											<TD WIDTH=11 HEIGHT=8>
												<IMG SRC="image/que_ask_cornner_04.gif" WIDTH=11 HEIGHT=8 ALT=""></TD>
										</TR>
										<TR>
											<TD style="background-image: url(image/que_ask_cornner_05.gif);background-repeat: repeat-y" WIDTH=9 >
												</TD>
											<TD bgcolor="#E1F3FD" valign="top" width="100%">
													<table width="100%"  border="0" cellspacing="0" cellpadding="0">
													  <tr>
													  <td nowrap class="pr_q2_q sp14blue" valign="top"><b>Q:&nbsp;</b></td>
														<td width="100%" class="main" valign="top" >														
														 <A  class="sp14blue" style="CURSOR: pointer" onclick="javascript:toggel_div('div_faq_question_<?php echo $toc['faq_id'];?>')"><?php echo $toc['question'];?></a> 
														</td>														
													  </tr>
													</table>			
											</TD>
											<TD  style="background-image: url(image/que_ask_cornner_07.gif);background-repeat: repeat-y" WIDTH=11>
											</TD>
										</TR>
										<TR>
											<TD WIDTH=9 HEIGHT=10>
												<IMG SRC="image/que_ask_cornner_10.gif" WIDTH=9 HEIGHT=10 ALT=""></TD>
											<TD style="background-image: url(image/que_ask_cornner_11.gif);background-repeat: repeat-x"  WIDTH=7 HEIGHT=10>
												</TD>		
											<TD WIDTH=11 HEIGHT=10>
												<IMG SRC="image/que_ask_cornner_13.gif" WIDTH=11 HEIGHT=10 ALT=""></TD>
										</TR>
									</TABLE>				
									</div>
									</div>
									
									<div id="div_faq_question_<?php echo $toc['faq_id'];?>" style="DISPLAY: none">   
								    <div class="pr_q2_faq_a">
									<div class="pr_q2_faq_qqq sp1">A:</div>
									<div class="sp10 sp6" style="float: left; width: 660px;  margin-top: 5px; margin-left: 2px; ">
									<?php		
												echo  $toc['answer'];
												?>
									</div>
									</div>
									</div>
									
								 
								<?php
								$faq_row_que_cnts++;
								}
								
								?>
								
					</td>
				  </tr>
				  <?php if($faq_row_que_cnts > 0) {
				  /*
				   <tr><td height="30" align="right"><div class="ladt_tt_t"><br />
								 <a href="javascript:scroll(0,0);" target="_self" class="a_3">TOP</a>
								<br /></div><?php echo tep_draw_separator('pixel_trans.gif', '20', '1'); ?>
								</td></tr>
				  */
				   ?>			 
				 <?php }else{ ?> 
				  <tr>
					<td  class="main"><?php echo tep_draw_separator('pixel_trans.gif', '20', '1'); ?><?php echo TEXT_NO_CATEGORIES;  ?></td>
				  </tr>
				  <?php } ?>
				  <tr><td height="30"></td></tr>
			</table>
	<?php
	}else{
	?>
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
		  <tr>
			<td height="30">&nbsp;</td>
		  </tr>
		  <tr>
			<td width="15">&nbsp;</td><td  class="main"> <?php echo TEXT_NO_CATEGORIES;  ?></td>
		  </tr>
		  <tr>
			<td height="30">&nbsp;</td>
		  </tr>
		</table>

	
	<?php } ?>
	</td>
  </tr>
<?php 
} // end if check specific tour listing
?>	
</table>