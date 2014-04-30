<?php
define('NO_SET_SNAPSHOT',true);
require_once("includes/application_top.php");
require(DIR_FS_INCLUDES . 'ajax_encoding_control.php');
require_once(DIR_FS_LANGUAGES . $language . '/' . FILENAME_QUESTION_WRITE);
require_once(DIR_FS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_REVIEWS_WRITE);
require_once(DIR_FS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);

//提交咨询成功层
$close_time_num = 5;
$question_done_tip = "QuestionDoneTip";
$question_done_con_id = "QuestionDoneTipConCompare";
$con_contents = db_to_html('
			<div class="popupCon  addSuccess">
			<div class="successTip">
            	<div class="img"><img src="'.DIR_WS_TEMPLATE_IMAGES.'success.jpg" /></div>
				<div class="words">
					<p> 您的问题已经提交，我们将把处理结果发送到您的电子邮箱，请注意查收。</p>
					<div>提示信息'.$close_time_num.'秒后自动关闭</div>
				</div>
			</div>
			<div class="btnCenter"><a href="javascript:;" onclick="closePopup(\''.$question_done_tip.'\');jQuery(\'html,body\').animate({scrollTop: jQuery(\'#two2\').offset().top}, 1000);" class="btn btnOrange"><button type="button">关闭</button></a></div>');
$h4_contents = db_to_html("提交咨询成功");
$PopupObj[] = tep_popup_alert($question_done_tip, $question_done_con_id, "490", $h4_contents, $con_contents );


$is_search_result = false;
$customerCondition = '';//已经登录的用户可以查看 自己发布的内容
if((int)$customer_id  &&  $customer_email_address !=''){	
	$customerCondition = 'AND ( que_replied = 1 OR customers_email = \''.$customer_email_address.'\')';
}else{
	$customerCondition = ' AND que_replied = 1 AND replay_has_checked="1" ';
}

$question_query_raw = "select customers_name,que_id,question,DATE_FORMAT(date,'%Y-%m-%d %H:%i') as add_date from " . TABLE_QUESTION ." where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and question != ''  AND languages_id = '" . (int)$languages_id . "' ".$customerCondition."  order by is_top,date desc";

if(tep_not_null($_GET['keyword'])){
	$keyword = $_GET['keyword'];
	$is_search_result = true ;
	$question_query_raw = "select customers_name,que_id,question,DATE_FORMAT(date,'%Y-%m-%d %H:%i') as add_date from " . TABLE_QUESTION ." where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and question like  '%".html_to_db(tep_db_input($keyword))."%' and languages_id = '" . (int)$languages_id . "'  ".$customerCondition."  order by date desc";
}
$displayNum = tep_not_null($_GET['seeAll'])? 10:5; 
$question_split = new splitPageResults($question_query_raw, $displayNum);

?>
<?php 
$html_question_button =   '
        <a id="question_fast_login_button_loged" href="javascript:;" onclick="jQuery(\'#WriteQuestionForm\').toggle()" class="btn btnGrey" onmouseover="jQuery(\'#newBtnTip\').show()" onmouseout="jQuery(\'#newBtnTip\').hide()" >'.db_to_html('我要咨询').'</a>
        <div id="newBtnTip" class="tip" style="display: none;"><div class="con">'.db_to_html('对于未在走四方网上预定行程, 但是对此行程费用、行程细则、接应服务、 酒店等有疑问的贵宾， 请在此提问。我们的专业客服人员会在8小时内为您解答. 让您在明明白白的状态下安排境外旅游').'</div><div class="bottom"></div></div>' ;//我要咨询按钮和提示
	

if ($question_split->number_of_rows > 0 ||tep_not_null($_GET['keyword'])) {	
?>	
<div class="concultTop"><?php # 搜索框 ?> 
		<form name="question_search_form" enctype="application/x-www-form-urlencoded"  method="get"  id="question_search" action=""  >		
        <div class="left"> 
            <h3><?php echo db_to_html('咨询前请先搜索，方便又快捷：')?></h3> 
            <div class="textBorder"><?php $html_search_dafault_text = db_to_html('输入您想了解问题的关键字');echo tep_draw_input_field('keyword',$html_search_dafault_text, ' class="text" onkeydown="this.style.color=\'#111\'" onblur="if(this.value==\'\'){this.value=\''.$html_search_dafault_text.'\';this.style.color=\'#ccc\'}" onfocus="if(this.value!=\''.$html_search_dafault_text.'\'){this.style.color=\'#111\'}else{this.value=\'\';this.style.color=\'#111\'}"   style="color: #ccc;"')?></div> 
            <a href="javascript:;" class="btn btnGrey"><button type="button" onclick="window.location='<?php echo tep_href_link('all_question_answers.php','action=search')?>&keyword='+document.question_search_form.keyword.value+'#anchor2'"><?php echo db_to_html('搜索')?></button></a> 
        </div> 
        </form>
        <div class="right"> 
        <p><?php echo db_to_html('网站使用、线路预订、操作流程和电子参团凭证更多信息请查看帮助中心！');#QUE_AND_ANSWER_TAB_TOP_NOTES;?></p>          
		<?php  if ((USE_POINTS_SYSTEM == 'true') && ((int)USE_POINTS_FOR_ANSWER)) {
			  //howard edited
			  echo '<h2>';
			  if((int)$customer_id)	echo sprintf(ANSWER_HELP_LINK, USE_POINTS_FOR_ANSWER, '<a href="' .tep_href_link('my_points.php','', 'SSL') . '" class="sp3" title="' . MY_POINTS_VIEW . '">' . MY_POINTS_VIEW . '</a>');
			  else	echo sprintf(ANSWER_HELP_LINK, USE_POINTS_FOR_ANSWER, '<a href="' . tep_href_link('points.php') . '" class="sp3" title="' . TEXT_MENU_JOIN_REWARDS4FUN . '">' . TEXT_MENU_JOIN_REWARDS4FUN . '</a>');
			  //howard edited end
			  echo '</h2>';
		  }?> </div> 
</div> 
<?php }else {	//如果没有咨询	?>
	<div class="noContentTop">
        <div class="left"><?php echo QUE_AND_ANSWER_TAB_TOP_NOTES;?></div>
        <div class="right">
            <?php echo $html_question_button;?>
        </div>
    </div>
<?php }?>
<?php 
//如果用户进行了搜索 显示 搜索结果计数
if(tep_not_null($_GET['keyword'])){ ?>
 <div class="searchNav">
        <a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=qanda&seeAll=all-questions&'.tep_get_all_get_params(array('info','mnu','rn','keyword','seeAll')))?>"><?php echo db_to_html('问题咨询')?></a> &gt;<?php echo db_to_html('搜索结果：')?> <b><?php echo stripslashes($keyword)?></b><?php echo db_to_html(sprintf("(%s条)",$question_split->number_of_rows))?>
    </div>
<?php } ?>

<?php if ($question_split->number_of_rows > 0){?>
<ul class="concultList" id="ConcultList">
	<?php
	$question_query = tep_db_query($question_split->sql_query);
	$li_i = 0;
	$myanchor='';
	$myAnchorName = '#anchor2';
	
	while ($question = tep_db_fetch_array($question_query)) {
		$li_i++;
		if($li_i == 4){
			$myanchor= '<a name="anchor_row" ></a>';
			$myAnchorName = '#anchor_row';
		}
		$question_query_answer_raw = tep_db_query("select modified_by,replay_name,ans,DATE_FORMAT(date,'%Y-%m-%d  %H:%i') as add_date from " . TABLE_QUESTION_ANSWER ." where que_id = '" . (int)$question['que_id'] . "' order by date desc");//查询答案每个问题的答案
		$answer_coust = 1;
		
		$head_img = "touxiang_no-sex.gif";
		$head_img = 'image/'.$head_img;
		//print_r($question);
		//exit();
		//$head_img = tep_customers_face($question['customers_id'], $head_img);

	?>
          <li><?php echo $myanchor;?>
            <div class="ask">
            	<dl>
                	<dt>
                    	<span><img src="<?php echo $head_img;?>" width="60" height="55"/></span>
                        <em><?php echo tep_db_output(db_to_html($question['customers_name']))?></em>
                    </dt>
                    <dd>
                    	<ul>
                        	<li class="s_1 font_bold color_blue"><?php 
            	if($is_search_result){
            		 echo char2c(db_to_html(tep_db_output($question['question'])), tep_db_prepare_input($keyword));
            	}else 
            	   	echo tep_db_output(db_to_html($question['question']));
            	?></li>
                            <li class="s_3 color_b3b3b3"><?php echo db_to_html($question['add_date']);?></li>
                        </ul>
                    </dd>
                  </dl>
            	<p style="display:none">
            	<?php 
            	if($is_search_result){
            		 echo char2c(db_to_html(tep_db_output($question['question'])), tep_db_prepare_input($keyword));
            	}else 
            	   	echo tep_db_output(db_to_html($question['question']));
            	?></p>
            	<div class="signature" style="display:none"><span><?php echo tep_db_output(db_to_html($question['customers_name']))?></span><?php echo db_to_html($question['add_date']);?></div> 
            </div>
             <?php 
            	$numanswers =  tep_db_num_rows($question_query_answer_raw);            	
				$vin_default_comment_header ="/.*尊敬的(.|\n)*?您好.*(！|!).*\n+/";
				$vin_default_comment_footer= '/.*谢谢您的咨询(.|\n)*走四方网客服部(.|\n)*www\.usitrip\.com.*/';
				
				while ($question_ans = tep_db_fetch_array($question_query_answer_raw)){?>				
				<?php 
					$question_ans['ans'] = preg_replace($vin_default_comment_header , '',$question_ans['ans']);
					$question_ans['ans'] = preg_replace($vin_default_comment_footer , '',$question_ans['ans']);
					//echo $question_ans['ans'];
					$pet = '/(http:\/\/)*((www|cn)+\.usitrip\.com[\w\/\?\&\.\=%\-]*)/';
					$ans = trim(tep_db_output($question_ans['ans']));
					$ans = preg_replace($pet,'<a target="_blank" href="http://$2">$1$2</a>',$ans);		
					$ans = nl2br($ans);	
					
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
                        	<li class="color_blue"><?php echo db_to_html('尊敬的  '.ucfirst($question['customers_name']).'，您好！感谢您对走四方网的支持。') ?></li>
                            <li class="s_2"><?php echo db_to_html($ans)?></li>
                            <li class="s_3 coloe_b3b3b3"><?php echo $question_ans['add_date']?></li>
                        </ul>
                    </dt>
                    <dd>
                    	<span><img src="/image/nav/xlogo_15.gif" width="60" height="55"/></span>
                        <em><?php //echo db_to_html('走四方网客服 '.ucfirst($replyName));?><?= db_to_html('走四方旅游资深顾问');?></em>
                    </dd>
                </dl>
                <?php # 以下是原来的HTML ?>
                <div class="headTitle" style="display:none"><?php echo db_to_html('尊敬的  '.ucfirst($question['customers_name']).'，您好！感谢您对走四方网的支持。') ?></div>
                <p style="display:none"><?php echo db_to_html($ans)?></p>
                <div class="signature" style="display:none"> <?php echo $question_ans['add_date']?><span><?php echo db_to_html('走四方网客服 '.ucfirst($replyName));?></span></div>
                </div> 
                <?php  }?> 
             	<?php
				$all_can_answer = false;
				$all_can_answer = ($all_can_answer==false && USE_POINTS_SYSTEM == 'true' && (int)USE_POINTS_FOR_ANSWER ) ? true :false;				
				if($all_can_answer==true){	?>
				<!--回答按钮 start-->
				 <div>
				<form name="frm_question_answer_write_ajax_<?php echo $question['que_id'];?>" id="frm_question_answer_write_ajax_<?php echo $question['que_id'];?>">
				<span class="showAnswer"><a href="javascript:;" onclick="toggel_div_show('div_question_answer_form_<?php echo $question['que_id'];?>'); sendFormData('frm_question_answer_write_ajax_<?php echo $question['que_id'];?>','<?php echo tep_href_link('tour_question_answer_write_ajax.php', 'que_id='.$question['que_id'].'&cPath='.$cPath.'&products_id=' . $products_id);?>','div_question_answer_form_<?php echo $question['que_id'];?>','true');"><?php echo db_to_html("我来回答")?></a></span>
				<input type="hidden" name="action" value="create_form" />
				<input type="hidden" name="qacall" value="true" />
				</form>													
				</div>
				<div id="div_question_answer_form_<?php echo $question['que_id'];?>" > </div>				
				<!--回答按钮 end-->
				<?php }?> 
				
          </li>          
	<?php	}  //end of while loop	?>    
	</ul>  
        <?php
}else{
	if(tep_not_null($_GET['keyword'])){
?>
	<ul class="concultList" id="ConcultList" style="display:none"><li style="display:none"></li></ul>
    <div  id="QuestionNoContent"  class="noResult" ><?php  echo db_to_html('对不起！没有搜索到您想要的咨询，您可以尝试采用其它关键字再次进行搜索。');?> </div>
 <?php }else{ ?>
	<ul class="concultList" id="ConcultList" style="display:none"><li style="display:none"></li></ul>
   <div   id="QuestionNoContent" class="noContent"><?php echo db_to_html('暂时没有游客咨询。')  ?> </div>

<?php }
 }?>
        <?php 
        $html_page_nagv = '<div class="page">';
        $html_page_nagv.=tep_draw_form('frm_slippage_ajax_product_qas', '' ,"post",'id=frm_slippage_ajax_product_qas');
		$extra_qa_query_send ='';				
		if(isset($HTTP_GET_VARS['mnu']) && $HTTP_GET_VARS['mnu'] == 'qanda') $extra_qa_query_send = 'mnu=qanda&';
		elseif(isset($HTTP_GET_VARS['mnu']) && $HTTP_GET_VARS['mnu'] == 'reviews')$extra_qa_query_send = 'mnu=reviews&';
		else $extra_qa_query_send = 'mnu=qanda&';
		$html_page_nagv.=TEXT_RESULT_PAGE . ' ' . $question_split->display_links_ajax(MAX_DISPLAY_PAGE_LINKS, $extra_qa_query_send.tep_get_all_get_params(array('mnu','rn','page','info')),'question_info.php','frm_slippage_ajax_product_qas','con_two_2'); 
		$html_view_all_button = ' <a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=qanda&seeAll=all-questions&'.tep_get_all_get_params(array('info','mnu','rn','keyword','seeAll'))).$myAnchorName.'" >'.db_to_html(sprintf('浏览所有咨询<span>(共<span>%d</span>条)</span>',$question_split->number_of_rows)).'</a>';/*/javascript:sendFormData('frm_slippage_ajax_product_qas','question_info.php?mnu=qanda&language=sc&cPath=<?php echo $cPath ?>&mnu=<?php echo $mnu;?>&products_id=<?php echo $HTTP_GET_VARS['products_id']?>&addhash=true&page=2&addhash=true','con_two_2','true'); */
		$html_page_nagv.=	'<input type="hidden" name="selfpagename_top" value="products_qa_top"><input type="hidden" name="ajxsub_send_qa_req" value="true"></form></div>';//分页按钮
		
		if((int)$customer_id){
			$styleLoged = ' style="display:"' ;$styleNotLoged = 'style="display:none"';
		}else{
			$styleLoged = ' style="display:none"' ;$styleNotLoged = 'style="display:"';
		}
			
      /* $html_question_button =   '
        <a id="question_fast_login_button_loged" href="javascript:;" onclick="jQuery(\'#WriteQuestionForm\').toggle()" class="btn btnGrey" onmouseover="jQuery(\'#newBtnTip\').show()" onmouseout="jQuery(\'#newBtnTip\').hide()" '.$styleLoged.'><button>'.db_to_html('我要咨询').'</button></a>
        <a id="question_fast_login_button_notloged" href="javascript:;"     onclick="showPopupForm(\''.preg_replace($p,$r,tep_href_link_noseo('product_reviews_tabs_ajax.php','ajax=true&action=process&login_for=question&btnid=question_fast_login_button')).'\',\'CommonFastLoginPopup\', \'CommonFastLoginPopupConCompare\' ,\'off\')" class="btn btnGrey" onmouseover="jQuery(\'#newBtnTip\').show()" onmouseout="jQuery(\'#newBtnTip\').hide()" '.$styleNotLoged.'><button>'.db_to_html('我要咨询').'</button></a><div id="newBtnTip" class="tip" style="display: none;"><div class="con">'.db_to_html('对于未在走四方网上预定行程, 但是对此行程费用、行程细则、接应服务、 酒店等有疑问的贵宾， 请在此提问。我们的专业客服人员会在8小时内为您解答. 让您在明明白白的状态下安排境外旅游').'</div><div class="bottom"></div></div>' ;*///我要咨询按钮和提示
        
        ?>	
<?php if($_GET['seeAll']){ //显示所有模式下
        		  if($question_split->number_of_rows > $displayNum) { //大于页码 显示页码 按钮?> 
        		  	<?php echo $html_page_nagv;?> <div class="reviewNew"><div class="newBtn"><?php echo $html_question_button?></div></div>
        <?php }elseif($question_split->number_of_rows > 0){//不够一页仅显示我要咨询按钮?>
					<div class="reviewNew"><div class="newBtn"><?php echo $html_question_button?></div></div>
		<?php }else {  //为空时按钮会显示到上部?>
					<div class="reviewNew"><div class="newBtn"><?php echo $html_question_button?></div></div>
				 <?php }?>
<?php }else{ //产品详情模式?>
				<?php if($question_split->number_of_rows > $displayNum) { //大于页码 显示查看所有按钮?> 
					<div class="reviewNew"><div class="newBtn"><?php echo $html_view_all_button;?> <?php echo $html_question_button?></div></div>
				<?php }elseif($question_split->number_of_rows > 0){//不够一页仅显示我要咨询按钮?>
					<div class="reviewNew"><div class="newBtn"> <?php echo $html_question_button?></div></div>
				<?php }else{
					   //为空时按钮会显示到上部
				  } ?>
<?php }?>
         <div class="reviewNewCon" id="WriteQuestionForm"  style="display:none">	
			 <h3><b><?php echo db_to_html('咨询')?></b>
			 <?php if((int)$customer_id) {?>
			 <a href="<?php echo tep_href_link("account.php","SSL")?>" id="customers_name_label"><?php echo db_to_html($first_or_last_name)?></a>
			 <?php echo date('Y-m-d H:i:s',time())?>
			 <?php }else{?>
			 <a href="<?php echo tep_href_link(FILENAME_LOGIN, 'referer='.tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('info','seeAll','vin_tab','mnu','page','rn'))), 'SSL') ?>" ><?php echo db_to_html('登录')?></a><?php echo db_to_html(sprintf('新用户请<a href="%s">注册</a>',tep_href_link(FILENAME_CREATE_ACCOUNT,'referer='.tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('info','seeAll','vin_tab','mnu','page','rn'))))))?>
			 <?php }?><?php echo db_to_html('(以下信息必填)') ?></h3>	
			 
			   <?php echo tep_draw_form('product_question_write','','','id=product_question_write');	 ?>	
			  <ul><?php 
			  $_user_email = tep_get_customers_email($customer_id);
			  if((int)$customer_id){
			  		echo tep_draw_hidden_field('customers_name', db_to_html($first_or_last_name)); 
					 echo tep_draw_hidden_field('customers_email', $_user_email); 
					 echo tep_draw_hidden_field('c_customers_email', $_user_email);		
			  }else{			  		 
					  echo '<li><label>'.db_to_html("姓名：").'</label>'.tep_draw_input_field('customers_name', '',' title="'.db_to_html('请输入姓名').'" class="required text " ').'</li>'; 
			  		 echo '<li><label>'.db_to_html("邮箱：").'</label>'.tep_draw_input_field('customers_email','',' title="'.db_to_html('请输入邮箱').'" class="required text headline" ').'</li>';		
			  }
			   echo tep_draw_hidden_field('products_id', $products_id);
			   echo tep_draw_hidden_field('accept_newsletter',1);
			 
			  ?>
			 <input type="hidden" name="ajxsub_send_questin" value="true"/>
            <li><label><?php echo db_to_html("问题：")?></label><?php echo tep_draw_textarea_field('questions', 'soft', '', '','',' class="required textarea"  title="'.db_to_html('请输入您要咨询的内容').'"  onblur="this.value = simplized(this.value);"'); ?></li> 
        </ul> 
         <div class="btnCenter"> 
            <a class="btn btnOrange btnOrange2" href="javascript:;" onclick="submit_question_data('product_question_write','<?php echo tep_href_link(FILENAME_QUESTION_WRITE, 'action=process&cPath='.$cPath.'&mnu='.$mnu.'&products_id=' . $HTTP_GET_VARS['products_id']);?>','question_result','true');" type="button"  id="Button4"><?php echo db_to_html('提交咨询')?></a> 
        </div> 
        <!--  
        <div class="btnCenter"> 
            <a class="btn btnOrange" href="javascript:;"><button onclick="sendFormData('product_question_write','<?php echo tep_href_link(FILENAME_QUESTION_WRITE, 'action=process&cPath='.$cPath.'&mnu='.$mnu.'&products_id=' . $HTTP_GET_VARS['products_id']);?>','question_result','true');" type="button"  id="Button4"><?php echo db_to_html('提交咨询')?></button></a> 
        </div> -->
         </form> 
    </div>  
             


<script type="text/javascript">
//点击翻页到当前Tab头部
AutoToTabHead();
</script>