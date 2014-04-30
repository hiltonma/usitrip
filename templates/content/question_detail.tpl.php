<?php ob_start();?>
	<div class="cui_wrap">
		<div class="cui_cmt_top">
			<div class="cui_cmt_hotkw">
				<dl>
					<dt>热门搜索：</dt>
					<dd>
					<?=$hit_key_string?$hit_key_string:''?>
					</dd>
				</dl>
			</div>
			<div class="cui_cmt_search">
				<h2>咨询前也可以搜索一下，看看别人是否也有相同的问题解答：</h2>
				<div class="cui_clearfix">
					<form action="<?php echo tep_href_link('all_question_answers.php')?>" method="get" name="question_search" id="question_search">
						<!--<input type="text" class="kw_input" placeholder="<?=('输入您想了解问题的关键字');?>" name="keyword"/>-->
						<?php $html_search_dafault_text = ('输入您想了解问题的关键字');echo tep_draw_input_field('keyword',$html_search_dafault_text, ' class="kw_input" onkeydown="this.style.color=\'#111\'" onblur="if(this.value==\'\'){this.value=\''.$html_search_dafault_text.'\';this.style.color=\'#ccc\'}" onfocus="if(this.value!=\''.$html_search_dafault_text.'\'){this.style.color=\'#111\'}else{this.value=\'\';this.style.color=\'#111\'}"   style="color: #ccc;"')?>
						<input type="submit" class="sh_button" value="搜索" />
							<input name="action" type="hidden" value="search" />
					</form>
				</div>
				<p>有关更多的参团常见问题，订购流程，支付方式等请详见帮助中心</p>
			</div>
		</div>
		<div class="cui_cmt_box">
			<div class="cui_cmt_cont cui_clearfix">
				<div class="cui_cmt_ask">
					<dl>
						<dt>
							<div class="cui_avator">
								<img src="<?php echo $head_img?>" alt="user icon" />
							</div>
							<p><?php echo (tep_db_output($question['customers_name']));?></p>
						</dt>
						<dd>
							<p class="cui_ask_question"><?php echo char2c((tep_db_output($question['question'])), tep_db_prepare_input($keyword), '#FF6600'); ?></p>
							<p class="cui_ask_from">
								咨询来源：<a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $question['products_id'])?>"><?php echo (tep_db_output($question['products_name']));?></a>
							</p>
							<p class="cui_ask_timer">
								<span><?php echo (date('Y-m-d H:i',strtotime($question['date'])));?></span>
							</p>
						</dd>
					</dl>
				</div>
				<div class="cui_cmt_abox">
					<i class="cui_role">&nbsp;</i>
					 <?php if(count($answers)>0){
					 $vin_default_comment_header ="/.*尊敬的(.|\n)*?您好.*(！|!).*\n+/";
					$vin_default_comment_footer= '/.*谢谢您的咨询(.|\n)*走四方网客服部(.|\n)*www\.usitrip\.com.*/';	
				    foreach($answers as $answer_rows){
					$answer_rows['ans'] = preg_replace($vin_default_comment_header , '',$answer_rows['ans']);
					$answer_rows['ans'] = preg_replace($vin_default_comment_footer , '',$answer_rows['ans']);	
					$ans = auto_add_tff_links($answer_rows['ans']);
					if($question_ans['modified_by'] > 0){
						$replyName = tep_get_admin_customer_name($answer_rows['modified_by']);
					}else 
						$replyName = $answer_rows['replay_name'];
					}
					?>
					<div class="cui_cmt_answer">
						<div class="cui_answer_cont">
							<h4><?php echo (sprintf('尊敬的 %s，您好！感谢您对走四方网的支持。',tep_db_output($question['customers_name'])));?></h4>
							<p><?=nl2br(($ans))?></p>
							<p>
								<span><?php echo (tep_db_output($answer_rows['date']))?></span>
							</p>
						</div>
						<div class="cui_answer_service">
							<div class="cui_answer_avator">
								<img src="/image/nav/xlogo_15.gif" />
							</div>
							<p><?php //echo ('走四方网客服'.tep_db_output($replyName))?>走四方旅游资深顾问</p>
						</div>
					</div>
					<?php }?>
				</div>
			</div>
			
			<div class="cui_cmt_list cui_clearfix">
			<?php if($products_list){ ?>
				<div class="cui_recom_mod">
					<div class="cui_list_head">
						<h3>走四方推荐线路：</h3>
						<a href="#">&nbsp;</a>
					</div>
					<ul>
					<?php foreach($products_list as $product){?>
						<li><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product['products_id'])?>" alt="<?php echo tep_db_output($product['name'])?>" title="<?php echo tep_db_output($product['name'])?>"><?php echo cutword(tep_db_output($product['name']),60)?></a><strong>
						<?php
						//price
						 $price_text = "";
 $tour_agency_opr_currency =
 tep_get_tour_agency_operate_currency($product['products_id']);
 if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
 $product['products_price'] =
 tep_get_tour_price_in_usd($product['products_price'],$tour_agency_opr_currency);
 }
 if (tep_get_products_special_price($product['products_id']))
 {
 $price_text.= '<del style="display:none">' .
 $currencies->display_price($product['products_price'],
 tep_get_tax_rate($product['products_tax_class_id'])) . '</del> <b>' .
 $currencies->display_price(tep_get_products_special_price($product['products_id']),
 tep_get_tax_rate($product['products_tax_class_id'])) . '</b>';
 }
 else
 {
 $price_text.= '<b>'.$currencies->display_price($product['products_price'],
 tep_get_tax_rate($product['products_tax_class_id'])).'</b>';
 }
 echo $price_text;
						
						?>
						</strong></li>
						<?php }?>
					</ul>
				</div>
				<?php }?>
				<div class="cui_related_mod">
					<div class="cui_list_head">
						<h3>
						<!-- 与<strong><?php echo ($question['question'])?></strong>的 -->
							相关问题：
						</h3>
						<a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $question['products_id'].'mnu=reviews&seeAll=all-reviews&'.tep_get_all_get_params(array('info','mnu','rn','seeAll')))?>#anchor2">更多&gt;&gt;</a>
					</div>
					<ul>
						<?php foreach($the_same_way as $value){?>
						<li><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $value['products_id'])?>"><?php echo cutword(tep_db_output($value['question']),60);?></a></li>
					<?php }?>
					</ul>
				</div>
				<div class="cui_questions_mod">
					<div class="cui_list_head">
						<h3>最新咨询问题列表</h3>
						<a href="<?php echo tep_href_link('all_question_answers.php')?>">更多&gt;&gt;</a>
					</div>
					<ul>
					<?php foreach($the_new_way as $value){?>
						<li><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $value['products_id'])?>"><?php echo cutword(tep_db_output($value['question']),60);?></a></li>
					<?php }?>
					</ul>
				</div>
			</div>
		</div>
	</div>
<?php echo  db_to_html(ob_get_clean());?>