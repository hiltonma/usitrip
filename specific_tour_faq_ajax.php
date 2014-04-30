<?php
require_once('includes/application_top.php');
require(DIR_FS_INCLUDES . 'ajax_encoding_control.php');
require_once(DIR_FS_LANGUAGES . $language . '/' . FILENAME_FAQ);
//$products_faqs['products_id']
$diveshow_qa_style ='style="DISPLAY: none"';
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
		$products_faqs['products_id'] = $HTTP_GET_VARS['products_id'];
		}
		$diveshow_qa_style='';
}	
?>
<div id="div_product_faq_question_<?php echo $products_faqs['products_id'];?>" <?php echo $diveshow_qa_style;?>>		
									<!-- start while loop of qustion -->
									<?php
									$product_question_query_raw = "select q.que_id,q.question, q.customers_name, q.date from " . TABLE_QUESTION ." as q," . TABLE_QUESTION_ANSWER ." qa where q.que_id = qa.que_id and q.products_id = '" . (int)$products_faqs['products_id']. "' group by q.que_id order by q.que_id desc";
									$product_question_query_split = new splitPageResults($product_question_query_raw, MAX_DISPLAY_SEARCH_RESULTS, 'q.que_id','qpage');
									 
									//$question_query = tep_db_query($question_query_raw);	
									$question_query = tep_db_query($product_question_query_split->sql_query);
									while ($question = tep_db_fetch_array($question_query)) {	
									?>
					   			   <div class="pr_q2_faq_q1" >
									<div class="pr_q2_q_t sp11 sp14blue">				
										<TABLE WIDTH="98%" align="center" style="margin-left:15px;" BORDER=0 CELLPADDING=0 CELLSPACING=0>
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
													 
														<td width="100%" class="main" valign="top" >														
														<a class="sp14blue" style="CURSOR: pointer" onclick="javascript:toggel_div('div_question_ans_div_<?php echo $question['que_id'];?>')"><?php echo tep_output_string_protected($question['question']);?></a> 
														<?php echo tep_draw_separator('pixel_trans.gif', '15', '1'); ?>|<?php echo tep_draw_separator('pixel_trans.gif', '15', '1'); ?><img src="image/queby_icon.gif"><?php echo tep_draw_separator('pixel_trans.gif', '5', '1'); ?><?php echo ucfirst($question['customers_name']); ?><?php echo tep_draw_separator('pixel_trans.gif', '25', '1'); ?><img src="image/clock_icon.gif"><?php echo tep_draw_separator('pixel_trans.gif', '5', '1'); ?><?php echo sprintf(tep_date_long_review($question['date']));?>
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
								
								
									<!-- start while loop of answser-->							
									
									<div id="div_question_ans_div_<?php echo $question['que_id'];?>" style="DISPLAY: none">	
									  <?php 
									$question_query_answer_raw = tep_db_query("select * from " . TABLE_QUESTION_ANSWER ." where que_id = '" . (int)$question['que_id'] . "' order by date desc");
									while ($question_ans = tep_db_fetch_array($question_query_answer_raw)) {									
									?>
									
									
									<div class="sp_pr_q2_faq_a">
									<div class="pr_b_a_1 sp10 sp6">
										<table  border="0" cellpadding="0" cellspacing="0">
										  <tr height="25" ><td width="25" ><div class="pr_q2_q sp1">A:</div></td>
										  <td nowrap width="90%">
										  <span class="sp1"><?php echo ucfirst($question_ans['replay_name']);?></span><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?><font color="#B7E3FB">|</font><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?><?php echo sprintf(tep_date_long_review($question_ans['date']));?></td>					     
										  <td valign="top">
										  <?php /* if($answer_coust == 1){ ?>
										  <img src="image/pr_s.gif" />
										  <?php  } */ ?>
										  <?php echo tep_draw_separator('pixel_trans.gif', '80', '1'); ?></td>
									  </tr></table>
									</div>  
								    <div class="sp10 sp6" style="float: left; width: 96%;  margin-top: 5px; margin-left: 10px; ">
									<?php echo tep_output_string_protected(tep_db_prepare_input($question_ans['ans'])); ?>
									</div>
									
									</div>
												
									<?php 									
										}  
									 ?>
									</div>			
									<!-- end while loop of answser-->					
									
									<?php } //end of question while loop ?>
									
									
									 <?php
									  if ($product_question_query_split->number_of_rows > MAX_DISPLAY_SEARCH_RESULTS && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
									 ?>
									<div class="tab1_2_2">									
									<span class="sp10 sp6" style="margin-right:15px; ">
									<?php  echo tep_draw_form('frm_slippage_ajax_product_qans_top_'.$products_faqs['products_id'], '' ,"",'id=frm_slippage_ajax_product_qans_top_'.$products_faqs['products_id']);	?>
									<?php echo $product_question_query_split->display_count(TEXT_DISPLAY_NUMBER_OF_QUESTIONS) . ': ' . $product_question_query_split->display_links_ajax(MAX_DISPLAY_PAGE_LINKS, 'products_id='.$products_faqs['products_id'].'&'.tep_get_all_get_params(array('rpage','page','cID','mnu','info')),'specific_tour_faq_ajax.php','frm_slippage_ajax_product_qans_top_'.$products_faqs['products_id'],'div_product_faq_question_'.$products_faqs['products_id']); 
									?>
									<?php
									echo '<input type="hidden" name="selfpagename_bqans" value="products_detail_faq">';
									echo '<input type="hidden" name="ajxsub_send_bqans_req" value="true">';
									echo '</form>';
									?>
									</span>
									</div>
									  <?php
									  }
									  ?>
							<!-- while loop of qustion end-->		
									
					</div>		