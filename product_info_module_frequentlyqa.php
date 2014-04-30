<?php
//当前产品常见问题
$frequently_question_query_raw = "select customers_name,que_id,question,DATE_FORMAT(date,'%Y-%m-%d %H:%i') as add_date from " . TABLE_QUESTION ." where products_id = '" . (int)$_GET['products_id'] . "' and set_hit='1' and languages_id = '" . (int)$languages_id . "' order by que_id desc limit 5 ";

$frequently_question_total = tep_db_num_rows(tep_db_query($frequently_question_query_raw));

if($frequently_question_total<1){	//如果当前产品没有常见问题就用整个网站的
	//$frequently_question_query_raw = "select * from " . TABLE_QUESTION ." where set_hit='1' and languages_id = '" . (int)$languages_id . "' order by que_id desc limit 100 ";
	//$frequently_question_total = tep_db_num_rows(tep_db_query($frequently_question_query_raw));
}
$frequently_question_sql = tep_db_query($frequently_question_query_raw);

if((int)$frequently_question_total){ ?> 
        <ul class="concultList">
          <?php	  while($f_question=tep_db_fetch_array($frequently_question_sql)){?>
		<li>
            <div class="ask">
            <dl>
                	<dt>
                    	<span><img src="/image/nav/user_2_02.gif" width="60" height="55"/></span>
                        <em><?php echo db_to_html($f_question['customers_name'])?></em>
                    </dt>
                    <dd>
                    	<ul>
                        	<li class="s_1 font_bold color_blue"><?php 
            	echo tep_db_output(db_to_html($f_question['question']));
            	?></li>
                            <li class="s_3 color_b3b3b3"><?php echo db_to_html($f_question['add_date']) ?></li>
                        </ul>
                    </dd>
                  </dl>
                  <?php #以下是原来的HTML start { ?>
                <p style="display:none"><?php echo tep_db_output(db_to_html($f_question['question']));?></p>
                <div class="signature" style="display:none"><span><?php echo db_to_html($f_question['customers_name'])?></span><?php echo db_to_html($f_question['add_date']) ?></div>
                <?php # } old end ?>
            </div>            
             <?php
				//答案 start
				$frequently_question_query_answer_raw = tep_db_query("select replay_name,ans,DATE_FORMAT(date,'%Y-%m-%d  %H:%i') as add_date  from " . TABLE_QUESTION_ANSWER ." where que_id = '" . (int)$f_question['que_id'] . "' order by date desc");
				$answer_coust = 1;
				$vin_default_comment_header ="/.*尊敬的(.|\n)*?您好.*(！|!).*\n+/";
				$vin_default_comment_footer= '/.*谢谢您的咨询(.|\n)*走四方网客服部(.|\n)*www\.usitrip\.com.*/';
					
				while ($frequently_question_ans = tep_db_fetch_array($frequently_question_query_answer_raw)) {
					$frequently_question_ans['ans'] = preg_replace($vin_default_comment_header , '',$frequently_question_ans['ans']);//删除回复模板前后缀
					$frequently_question_ans['ans'] = preg_replace($vin_default_comment_footer , '',$frequently_question_ans['ans']);
					$pet = '/(http:\/\/)*((www|cn)+\.tours(for|4)fun\.com[\w\/\?\&\.\=%\-]*)/';
					$ans = (tep_db_output($frequently_question_ans['ans']));
					$ans = preg_replace($pet,'<a target="_blank" href="http://$2">$1$2</a>',$ans);
					
					if($question_ans['modified_by'] > 0){
						$replyName = tep_get_admin_customer_name($question_ans['modified_by']);
					}else 
						$replyName = $question_ans['replay_name'];
			  ?>
			  <div class="answer">
                <div class="arrow"></div>
                <dl>
                	<dt>
                    	<ul>
                        	<li class="color_blue"><?php echo db_to_html(sprintf("尊敬的 %s，您好！感谢您对走四方网的支持。",ucfirst($frequently_question_ans['replay_name'])));?></li>
                            <li class="s_2"><?php echo nl2br(db_to_html($ans));?></li>
                            <li class="s_3 coloe_b3b3b3"><?php echo  $frequently_question_ans['add_date']?></li>
                        </ul>
                    </dt>
                    <dd>
                    	<span><img src="/image/nav/xlogo_15.gif" width="60" height="55"/></span>
                        <em><?php echo db_to_html(sprintf('走四方网客服 :%s',ucfirst($frequently_question_ans['replay_name'])));?></em>
                    </dd>
                </dl>
                <?php # old html start { 
				/*?>
                <div class="headTitle"><?php echo db_to_html(sprintf("尊敬的 %s，您好！感谢您对走四方网的支持。",ucfirst($frequently_question_ans['replay_name'])));?></div>
                <p><?php echo nl2br(db_to_html($ans));?></p>
                <div class="signature"> <?php echo  $frequently_question_ans['add_date']?><span><?php echo db_to_html(sprintf('走四方网客服 :%s',ucfirst($frequently_question_ans['replay_name'])));?></span></div>
                <?php 
				*/
				# } old html end?>
                </div>
           <?php }?>
            
        </li>		 
		  <?php	} ?>
	 </ul>
<?php }?>

