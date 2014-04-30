<script type="text/javascript"> 
document.oncontextmenu=function(e){return false;} 
document.onselectstart=function(e){return false;} 
document.oncopy=function(e){return false;}
</script> 
<?php
//搜索 start
$spread_where = '';
if(tep_not_null($_GET['keyword'])){
	if(isset($_GET['tabid'])){
		unset($_GET['tabid']);
	}
	$keyword = preg_replace('/( |'.db_to_html('　').')+/',' ',trim($_GET['keyword']));
	$key_array = explode(' ',$keyword);
	$spread_like = '';
	foreach((array)$key_array as $key => $val){
		if(tep_not_null($val)){
			$spread_like .= ' q.question Like "%'.html_to_db(tep_db_prepare_input($val)).'%" ||';
		}
	}
	if(tep_not_null($spread_like)){
		$spread_like = preg_replace('/\|\|$/','',$spread_like);
		$spread_where .= ' and ('.$spread_like.') ';
	}
}

//*自动填充问题所属分类
if($_GET['auto']=='true'){
	$auto_sql = tep_db_query('SELECT que_id FROM `tour_question` WHERE products_id>0 ');
	while($auto_rows = tep_db_fetch_array($auto_sql)){
		$temp = auto_add_question_tag_id($auto_rows['que_id']);
	}
}

//为所有分类设置title,要去除的GET参数
		$close_parameters = array('page', 'tabid', 'x', 'y','keyword','action');
		$title_array = array();
		$title_array[] = array('id'=>0,'title'=>'所有咨询');
		$tab_sql = tep_db_query('SELECT * FROM `tour_question_tab` WHERE parent_id ="0" ORDER BY sort_order ASC ');
		while($tab_rows = tep_db_fetch_array($tab_sql)){
			$title_array[] = array('id'=>$tab_rows['question_tab_id'],'title'=>$tab_rows['question_tab_name']);
		}
		
//自定义的热门搜索	

	$hit_key_string = '';
	
	$tmp_key = '黄石公园,洛杉矶,纽约,拉斯维加斯,美东,美西,夏威夷,加拿大';
	$tmp_keys = explode(',',$tmp_key);
	for($i=0; $i<count($tmp_keys); $i++){
		$hit_key_string .= '&nbsp;&nbsp;<a href="'.tep_href_link('all_question_answers.php','keyword='.db_to_html($tmp_keys[$i])).'">'.db_to_html($tmp_keys[$i]).'</a>';
	}	
	$key_sql = tep_db_query('SELECT *, count(key_name) as total FROM `tour_question_keywords` WHERE 1 Group By key_name Order By total DESC Limit 0');
	while($key_rows = tep_db_fetch_array($key_sql)){
		$hit_key_string .= '&nbsp;&nbsp;<a href="'.tep_href_link('all_question_answers.php','keyword='.tep_db_output($key_rows['key_name'])).'">'.tep_db_output($key_rows['key_name']).'</a>';
	}

	//登录用户可以看到自己显示的问题
	if((int)$customer_id  &&  $customer_email_address !=''){	
		$customerCondition = 'AND ( que_replied > 0 OR customers_email = \''.$customer_email_address.'\')';
	}else{
		$customerCondition = ' AND que_replied > 0 AND replay_has_checked="1" ';
	}
	
	$question_sql_string = 'SELECT * FROM `tour_question` q WHERE q.languages_id ="'.(int)$languages_id.'" '.$customerCondition.' '.$spread_where.' ORDER BY is_top DESC, q.date DESC ';//查询全部问题
if((int)$_GET['tabid']){
	//查询指定分区的问题
	$question_sql_string = 'SELECT * FROM `tour_question` q, `tour_question_to_tab` qtt WHERE q.que_id = qtt.que_id and qtt.tour_question_tab_id ="'.(int)$_GET['tabid'].'" and languages_id ="'.(int)$languages_id.'" AND que_replied > 0 '.$spread_where.$customerCondition.' ORDER BY is_top DESC,date desc ';
}

$question_split = new splitPageResults($question_sql_string, 10);
?>

<div class="concultAll"> 
     <!--<h1 <?php if($language == 'tchinese') echo ' class="tr" ';?>><?php echo  db_to_html('浏览所有咨询');?></h1> --> 
    <div class="concultTop"> 
   
        <div class="left"> 
         <form action="<?php echo tep_href_link('all_question_answers.php')?>" method="get" name="question_search" id="question_search">
            <h3><?php echo db_to_html('咨询前也可以搜索一下，看看别人是否也有相同的问题解答：')?></h3> 
            <div class="textBorder"><?php $html_search_dafault_text = db_to_html('输入您想了解问题的关键字');echo tep_draw_input_field('keyword',$html_search_dafault_text, ' class="text" onkeydown="this.style.color=\'#111\'" onblur="if(this.value==\'\'){this.value=\''.$html_search_dafault_text.'\';this.style.color=\'#ccc\'}" onfocus="if(this.value!=\''.$html_search_dafault_text.'\'){this.style.color=\'#111\'}else{this.value=\'\';this.style.color=\'#111\'}"   style="color: #ccc;"')?><input name="action" type="hidden" value="search" /></div> 
            <a href="javascript:;" class="btn btnGrey"><button type="submit"><?php echo db_to_html('搜索')?></button></a> 
          </form></div> 
      
        <div class="right"> 
        <?php #db_to_html('网站使用、预订、支付和电子参团凭证提供请查询 <a href="">帮助中心</a>。<br />请注意由于季节（旅行日期和年份）、差价、有效性等原因，每个问题会有多种答案。您可以通过游览问题咨询版块获取其他可用信息。')?>
        <br/><?php echo db_to_html('有关更多的参团常见问题、订购流程、支付方式等请详见帮助中心');?>        
		<?php  if ((USE_POINTS_SYSTEM == 'true') && ((int)USE_POINTS_FOR_ANSWER)) {
			  //howard edited
			  echo '<h2>';
			  if((int)$customer_id)	echo sprintf('回答问题获取<b>%s</b>个走四方积分。请点击 %s 了解详情。', USE_POINTS_FOR_ANSWER, '<a href="' .tep_href_link('my_points.php','', 'SSL') . '" class="sp3" title="' . MY_POINTS_VIEW . '">' . MY_POINTS_VIEW . '</a>');
			  else	echo sprintf('回答问题获取<b>%s</b>个走四方积分。请点击 %s 了解详情。', USE_POINTS_FOR_ANSWER, '<a href="' . tep_href_link('points.php') . '" class="sp3" title="' . TEXT_MENU_JOIN_REWARDS4FUN . '">' . TEXT_MENU_JOIN_REWARDS4FUN . '</a>');
			  //howard edited end
			  echo '</h2>';
		  }?> </div> 
		  <?php	if(tep_not_null($hit_key_string)){?>  <p><span > <?php echo db_to_html('热门搜索：')?></span><?php echo $hit_key_string ?></p> <?php }?>
    </div> 
 <?php  if(tep_not_null($_GET['keyword'])){ ?>
   <div class="searchNav">
        <a href="<?php echo tep_href_link('all_question_answers.php')?>"><?php echo db_to_html('所有咨询')?></a> &gt;<?php echo db_to_html('搜索结果：')?> <b><?php echo tep_output_string($_GET['keyword'])?></b><?php echo db_to_html(sprintf("(%s条)",$question_split->number_of_rows))?>
    </div>
<?php }else{?>    
   <ul class="chooseTab"> 
        <?php for($i=0; $i<count($title_array); $i++){$new_tag_class =(int)$_GET['tabid'] == $title_array[$i]['id'] ?'selected':'';	?>
		<li class="<?=$new_tag_class?>" style="width: auto; margin-right: 5px;"><a href="<?php echo tep_href_link('all_question_answers.php','tabid='.$title_array[$i]['id'].'&'.tep_get_all_get_params($close_parameters))?>"><?php echo db_to_html($title_array[$i]['title'])?></a><span></span></li>
        <?php }	?>
   </ul>
  <?php }?>
   
    <?php //搜索 end?>
    </div>
    

<?php
if ($question_split->number_of_rows > 0) {
		if($_GET['action']=='search' && tep_not_null($key_array) && !isset($_GET['page'])){	//在特定的条件下记录搜索关键字
		foreach((array)$key_array as $key => $val){
			if(tep_not_null($val)){
				$date_array = array('key_name' => tep_db_prepare_input($val),
									'add_date' => date('Y-m-d H:i:s')
									);
				$date_array = html_to_db($date_array);
				tep_db_perform('tour_question_keywords', $date_array);
			}
		}
	}
?>

<div class="ui-reply-container">
<?php  
 	$question_query = tep_db_query($question_split->sql_query);	
 	$vin_default_comment_header ="/.*尊敬的(.|\n)*?您好.*(！|!).*\n+/";
	$vin_default_comment_footer= '/.*谢谢您的咨询(.|\n)*走四方网客服部(.|\n)*www\.usitrip\.com.*/';		
    while ($question = tep_db_fetch_array($question_query)) {
    	    	
		$products_row = vin_db_fetch_first('SELECT p.products_id, pd.products_name, p.products_image FROM `products` p,`products_description` pd WHERE p.products_id="'.(int)$question['products_id'].'" AND p.products_id = pd.products_id AND language_id ="'.(int)$languages_id.'" limit 1 ');	//取得问题对应的产品		
		$answers = vin_db_fetch_all('SELECT * FROM `tour_question_answer` WHERE  que_id ="'.(int)$question['que_id'].'" ORDER BY  date DESC ',100);//取得问题相应的答案		
   
	    $head_img = "touxiang_no-sex.gif";
		$head_img = 'image/'.$head_img;		
?>
<div class="ui-reply">
    	<div class="ui-reply-title">
        	<div class="ui-reply-userinfo">
            	<div class="ui-reply-uicon">
                    <span class="ui-mark"></span>
                    <img src="<?php echo $head_img?>" width="60" height="55" />
                </div>
                <em><?php echo db_to_html(tep_db_output($question['customers_name']));?></em>
            </div>
            <div class="ui-reply-info">
				<p class="ui-reply-text"><a href="<?php echo tep_href_link('question_detail.php',$question['que_id'])?>"><?php echo char2c(db_to_html(tep_db_output($question['question'])), tep_db_prepare_input($keyword), '#FF6600'); ?></a></p>
                <?php if(!empty($products_row)){?>
                <p><span> <?php echo db_to_html('咨询来源：');?></span><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_row['products_id'])?>"><?php echo db_to_html(tep_db_output($products_row['products_name']));?></a></p>
                <?php }//如果没有来源不显示?>
                <p class="ui-reply-time"><?php echo db_to_html(date('Y-m-d H:i',strtotime($question['date'])));?></p>
			</div>
        </div>
        <?php if(count($answers)>0){?>           
                <?php foreach($answers as $answer_rows){
					$answer_rows['ans'] = preg_replace($vin_default_comment_header , '',$answer_rows['ans']);
					$answer_rows['ans'] = preg_replace($vin_default_comment_footer , '',$answer_rows['ans']);
					//echo $question_ans['ans'];
					//$pet = '/(http:\/\/)*((www|cn)+\.usitrip\.com[\w\/\?\&\.\=%\-]*)/';
								
					//$ans = trim(tep_db_output($answer_rows['ans']));
					//$ans = preg_replace($pet,'<a target="_blank" href="http://$2">$1$2</a>',$ans);	
					$ans = auto_add_tff_links($answer_rows['ans']);
					if($question_ans['modified_by'] > 0){
						$replyName = tep_get_admin_customer_name($answer_rows['modified_by']);
					}else 
						$replyName = $answer_rows['replay_name'];
					?>
        <div class="ui-reply-answer">
        	<div class="ui-reply-tipsicon"></div>
            <div class="ui-replyer">
            	<div class="ui-replyer-icon"><img src="/image/nav/xlogo_15.gif" width="60" height="55" /></div>
                <p><?php //echo db_to_html(sprintf("走四方网客服  %s",tep_db_output($replyName)))?><?= db_to_html('走四方旅游资深顾问')?></p>
            </div>
            <div class="ui-reply-cnt">
            	<p class="ui-reply-name"><?php echo db_to_html(sprintf('尊敬的 %s，您好！感谢您对走四方网的支持。',tep_db_output($question['customers_name'])));?></p>
                <p><?php 
                
						
					echo nl2br(db_to_html($ans));?></p>
                <p class="ui-reply-time"><?php echo db_to_html(tep_db_output($answer_rows['date']))?>&nbsp;&nbsp;<span style="display:none"><?php echo db_to_html(sprintf("走四方网客服  %s",tep_db_output($replyName)))?></span></p>
            </div>
            <div class="ui-fix"></div>
        </div>
        <?php }?>
            
            <?php }?>
    </div>
    <?php //老版本的HTML 代码 start {
		/*?>
    <ul class="concultList"> 
    <li> 
            <div class="ask"> 
                <p><?php echo char2c(db_to_html(tep_db_output($question['question'])), tep_db_prepare_input($keyword), '#FF6600'); ?></p> 
                <?php if(!empty($products_row)){?>
                <p class="source"><span> <?php echo db_to_html('咨询来源：');?></span><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_row['products_id'])?>"><?php echo db_to_html(tep_db_output($products_row['products_name']));?></a></p> 
                <?php }//如果没有来源不显示?>
                <div class="signature"><span><?php echo db_to_html(tep_db_output($question['customers_name']));?></span><?php echo db_to_html(chardate($question['date'],'I'));?></div> 
            </div> 
          <?php if(count($answers)>0){?>           
                <?php foreach($answers as $answer_rows){?>
                 <div class="answer"> 
                <div class="arrow"></div> 
                <div class="headTitle"><?php echo db_to_html(sprintf('尊敬的 %s，您好！感谢您对走四方网的支持。',tep_db_output($question['customers_name'])));?></div> 
                <p> <?php 
                $answer_rows['ans'] = preg_replace($vin_default_comment_header , '',$answer_rows['ans']);
					$answer_rows['ans'] = preg_replace($vin_default_comment_footer , '',$answer_rows['ans']);
					//echo $question_ans['ans'];
					$pet = '/(http:\/\/)*((www|cn)+\.usitrip\.com[\w\/\?\&\.\=%\-]*)/';
					$ans = trim(tep_db_output($answer_rows['ans']));
					$ans = preg_replace($pet,'<a target="_blank" href="http://$2">$1$2</a>',$ans);	
					$ans = auto_add_tff_links($answer_rows['ans']);
					if($question_ans['modified_by'] > 0){
						$replyName = tep_get_admin_customer_name($answer_rows['modified_by']);
					}else 
						$replyName = $answer_rows['replay_name'];
						
					echo nl2br(db_to_html($ans));?></p> 
                <div class="signature"> <?php echo db_to_html(tep_db_output($answer_rows['date']))?><span><?php echo db_to_html(sprintf("走四方网客服  %s",tep_db_output($replyName)))?></span></div> 
                </div> 
				<?php }?>
            
            <?php }?>
        </li> 
        </ul>
<?php  */ //老版本HTML end  }
	}
} else{
	echo db_to_html('<div class="noResult">对不起！没有搜索到您想要的咨询，您可以尝试采用其它关键字再次进行搜索。</div>');
}?>
</div>
<?php if($question_split->number_of_rows > 0) {?>
<div  class="page">
<?php echo TEXT_RESULT_PAGE . ' ' . $question_split->display_links_2011(5, tep_get_all_get_params(array('page', 'info')));?>
</div>
<?php }?>
<script type="text/javascript">
function fast_login(form_id,login_for){
	var from = document.getElementById(form_id);
	if(from.elements["email_address"].value.length<2){
		alert("<?php echo db_to_html('请输入您的账号（电子邮箱）！')?>");
		return false;
	}
	if(from.elements["password"].value.length<2){
		alert("<?php echo db_to_html('请输入您的密码！')?>");
		return false;
	}
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('product_reviews_tabs_ajax.php','ajax=true&action=process')) ?>");
	url +="&login_for="+login_for;
	var success_msm="";
	var success_go_to="";
	var replace_id="";
	ajax_post_submit(url,form_id,success_msm,success_go_to, replace_id);
	return true;
}

function updateVVC(){
	 var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo(FILENAME_CREATE_ACCOUNT)) ?>");     
 	jQuery.get(url,{"action":"updateVVC",'random':Math.random()},function(data){
                jQuery("#vvc").attr('src', data); 
       });
}
</script>
<style>
#WriteQuestionForm label {width:7em;text-align:right;}
#WriteQuestionForm  img {margin-left:2px;}
#WriteQuestionForm  textarea {float:left;}
</style>
<div class="reviewNew">
   <div id="ask_question_form_id"  >
    <?php 
     /**
    * 检查是否是浏览所有咨询 
    * 检查用户是否登录 ，登录后则显示咨询提交表单
    * @author vincent
    */
    if((int)$customer_id > 0){
    	$WriteQuestionFormStyle='style="display:block"'; 
    	$QuickLoginBoxStyle='style="display:none"';    	
     }else{
     	$WriteQuestionFormStyle='style="display:none"'; 
    	$QuickLoginBoxStyle='style="display:block"';    	
     }
     ?>
    		<div id="question_new_added"></div>
    		
			<div id="question_result" class="newSuccess" style="display:block;"></div>
         <div class="reviewNewCon" id="WriteQuestionForm" >	
			 <h3><b><?php echo db_to_html('我想咨询')?></b>
			 <?php if((int)$customer_id) {?>
			 <a href="<?php echo tep_href_link("account.php","SSL")?>" id="customers_name_label"><?php echo db_to_html($first_or_last_name)?></a>
			 <?php echo date('Y-m-d H:i:s',time())?>
			 <?php }else{?>
			 <?php echo db_to_html('若已注册，请')?><a href="<?php echo tep_href_link(FILENAME_LOGIN, 'referer='.tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('info','seeAll','vin_tab','mnu','page','rn'))), 'SSL') ?>" ><?php echo db_to_html('登录')?></a><?php echo db_to_html(sprintf('后咨询，若是新用户，请现在<a href="%s">注册</a>后提问。',tep_href_link(FILENAME_CREATE_ACCOUNT,'referer='.tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('info','seeAll','vin_tab','mnu','page','rn'))))))?>
			 <?php }?></h3>	
			 
			  <?php echo tep_draw_form('product_queston_write', tep_href_link('tour_question.php', 'action=process'), 'post', 'id="product_question_write"');	 ?>	
			  <ul><?php 
			  $_user_email = tep_get_customers_email($customer_id);
			  if((int)$customer_id){
			  		echo tep_draw_hidden_field('customers_name', db_to_html($first_or_last_name)); 
					 echo tep_draw_hidden_field('customers_email', $_user_email); 
					 echo tep_draw_hidden_field('c_customers_email', $_user_email);		
			  }else{
				////vincent Urgent-增加注册用户注册验证码，QA全站提问验证码 BEGIN
			  	$RandomStr = md5(microtime());// md5 to generate the random string										
				$_SESSION['captcha_key'] = $ResultStr = substr($RandomStr,0,4);//trim 5 digit
				$RandomImg = '<img width="66" height="22" src="php_captcha.php?code='. base64_encode($_SESSION['captcha_key']).'" id="vvc" onclick="updateVVC()"  align="absmiddle" title="'.db_to_html('请输入图片中显示的字符，不区分大小写。').'"  alt="'.db_to_html('请输入图片中显示的字符，不区分大小写。').'" /> <a href="javascript:;" onclick="updateVVC()">'.db_to_html('看不清?点击换一张图。').'</a> ';
				////vincent Urgent-增加注册用户注册验证码，QA全站提问验证码 END
	
					  echo '<li><label>'.db_to_html("姓名：").'</label>'.tep_draw_input_field('customers_name', '',' title="'.db_to_html('请输入姓名').'"  alt="'.db_to_html('请输入姓名').'" class="required text " ').'</li>'; 
			  		 echo '<li><label>'.db_to_html("邮箱：").'</label>'.tep_draw_input_field('customers_email','',' title="'.db_to_html('请输入邮箱').'" alt="'.db_to_html('请输入邮箱').'" class="required text headline" ').'</li>';		
			  		  echo '<li><label>'.db_to_html("验证码：").'</label>'.tep_draw_input_field('visual_verify_code','',' title="'.db_to_html('请输入验证码').'" alt="'.db_to_html('请输入验证码').'" class="required text " style="width:70px"').$RandomImg.'</li>';		
			  }
			   echo tep_draw_hidden_field('products_id', $products_id);
			   echo tep_draw_hidden_field('accept_newsletter',1);
			 
			  ?>
			 <input type="hidden" name="ajxsub_send_questin" value="true"></input>
            <li><label><?php echo db_to_html("想咨询的问题：")?></label><?php echo tep_draw_textarea_field('question', 'soft', '', '','',' class="required textarea"  title="'.db_to_html('请输入您要咨询的内容').'"  onblur="this.value = simplized(this.value);"'); ?></li> 
        </ul> 
        <div class="btnCenter"> 
            <a class="btn btnOrange" href="javascript:;"><button  type="submit"  id="Button4"><?php echo db_to_html('提交咨询')?></button></a> 
        </div> 
         </form> 
    </div>  
 </div>
</div>