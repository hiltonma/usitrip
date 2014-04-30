<?php
require_once("includes/application_top.php");
require_once(DIR_FS_INCLUDES . 'ajax_encoding_control.php');
require_once(DIR_FS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_REVIEWS_WRITE);
require_once(DIR_FS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);
require_once(DIR_FS_LANGUAGES . $language . '/' . FILENAME_QUESTION_WRITE);
$p=array('/&amp;/','/&quot;/');
$r=array('&','"');

//回复和评论成功后的关闭间隔数5秒
$close_time_num = 5;

if((int)$customer_id){
			$styleLoged = ' style="display:"' ;$styleNotLoged = 'style="display:none"';
		}else{
			$styleLoged = ' style="display:none"' ;$styleNotLoged = 'style="display:"';
		}		
		
$html_review_button =   '
        <a id="review_fast_login_button_loged" href="javascript:;" onclick="jQuery(\'#reviewNewCon\').show();jQuery(\'html,body\').animate({scrollTop:jQuery(\'#review_box\').offset().top - 30});" class="btn btnGrey"  '.$styleLoged.'>'.db_to_html('我要点评').'</a>
        <a id="review_fast_login_button_notloged" href="javascript:;"     onclick="showPopupForm(\''.preg_replace($p,$r,tep_href_link_noseo('product_reviews_tabs_ajax.php','ajax=true&action=process&login_for=reviews&btnid=review_fast_login_button')).'\',\'CommonFastLoginPopup\', \'CommonFastLoginPopupConCompare\' ,\'off\')" class="btn btnGrey" '.$styleNotLoged.'>'.db_to_html('我要点评').'</a><div id="newBtnTip" class="tip" style="display: none;"><div class="bottom"></div></div>' ;//我要评论
//回复层
$popupTip = "replyPopup";
$popupConCompare = "replyPopupConCompare";
function get_reply_popup(){
	global $customer_id, $popupTip, $popupConCompare;
	$con_contents ='<div class="reviewNew replyPopup">';
	if(!(int)$customer_id){
		$h4_contents = db_to_html('<b>请先登录</b>');
		$con_contents .= tep_draw_form('reply_reviews_form','','post', ' id="reply_reviews_form" onsubmit="fast_login(this.id,&quot;reply&quot;); return false;" ');
		$con_contents .='<div class="login">
			<ul>
				<li><label>'.db_to_html('账号：').'</label>'.tep_draw_input_field('email_address','','class="required validate-email text username" title="'.db_to_html('请输入您的电子邮箱').'"').'</li>
				<li><label>'.db_to_html('密码：').'</label><input name="password" type="password" class="required text password" title="'.db_to_html('请输入正确的密码').'" /></li>';
		$con_contents .= tep_draw_hidden_field('products_id');
		$con_contents .= tep_draw_hidden_field('parent_reviews_id');
		$con_contents.=' <li><label>&nbsp;</label><input type="submit" class="loginBtn" value="'.db_to_html('&nbsp;登&nbsp;录').' "></li> <li><label>&nbsp;</label><a href="'.tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL').'">'.db_to_html('忘记密码?').'</a>'.db_to_html(sprintf('&nbsp;&nbsp;新用户请&nbsp;<a href="%s">注册</a>',tep_href_link("create_account.php","", "SSL"))).'</li>
        </ul>
    </div>';
		$con_contents .= '</form>';
	}else{
		$h4_contents = db_to_html('<b>回复</b>');
		$con_contents .= tep_draw_form('reply_reviews_form','','post', ' id="reply_reviews_form" onsubmit="submit_reply(this.id); return false;"');
		$con_contents .= tep_draw_textarea_field('reviews_text','',45,5,'',' id="reviews_text" class="textarea" ');
		$buttons = '<div class="popupBtn"><a href="javascript:;" class="btn btnOrange"><button id="SubmitReplyButton" type="submit">'.db_to_html("回复").'</button></a></div>';
		$con_contents .= tep_draw_hidden_field('products_id');
		$con_contents .= tep_draw_hidden_field('parent_reviews_id');		
		$con_contents .= $buttons;
		$con_contents .= '</form>';
		$con_contents .= '</div>';
	}
	
	$PopupHtml = tep_popup($popupTip, $popupConCompare, "500", $h4_contents, $con_contents );
	return $PopupHtml;
}
$PopupObj[] = get_reply_popup();
//回复成功层
/*
$reply_done_tip = "ReplyDoneTip";
$reply_done_con_id = "ReplyDoneTipConCompare";
$con_contents = db_to_html('<div class="replyPopup"><p>您已经成功回复，因为网站设置了缓存，回复显示有一定延迟，请您知晓。</p><p><span>提示信息'.$close_time_num.'秒后自动关闭</span></p></div>');
$h4_contents = db_to_html("回复成功");
$PopupObj[] = tep_popup($reply_done_tip, $reply_done_con_id, "500", $h4_contents, $con_contents );

//评论发表成功层
$review_done_tip = "messageStackSuccess";
$review_done_con_id = "messageStackSuccessConCompare";
$con_contents = db_to_html('<div class="replyPopup"><p>您的评论已经发表成功！待我们审核过后将在产品页面显示您的评论！</p><p><span>提示信息'.$close_time_num.'秒后自动关闭</span></p></div>');
$h4_contents = db_to_html("评论发表成功");
$PopupObj[] = tep_popup($review_done_tip, $review_done_con_id, "500", $h4_contents, $con_contents );*/

$reply_done_tip = "ReplyDoneTip";
$reply_done_con_id = "ReplyDoneTipConCompare";
$con_contents = db_to_html('
			<div class="popupCon  addSuccess">
			<div class="successTip">
            	<div class="img"><img src="'.DIR_WS_TEMPLATE_IMAGES.'success.jpg" /></div>
				<div class="words">
					<p> 您已经成功回复，因为网站设置了缓存，回复显示有一定延迟，请您知晓。</p>
					<div>提示信息'.$close_time_num.'秒后自动关闭</div>
				</div>
			</div>
			<div class="btnCenter"><a href="javascript:;" onclick="closePopup(&quot;'.$reply_done_tip.'&quot;);" class="btn btnOrange"><button type="button">关闭</button></a></div>');
$h4_contents = db_to_html("回复成功");
$PopupObj[] = tep_popup_alert($reply_done_tip, $reply_done_con_id, "500", $h4_contents, $con_contents );

$review_done_tip = "messageStackSuccess";
$review_done_con_id = "messageStackSuccessConCompare";
$con_contents = db_to_html('
			<div class="popupCon  addSuccess">
			<div class="successTip">
            	<div class="img"><img src="'.DIR_WS_TEMPLATE_IMAGES.'success.jpg" /></div>
				<div class="words">
					<p> 您的评论已经发表成功！待我们审核过后将在产品页面显示您的评论！</p>
					<div>提示信息'.$close_time_num.'秒后自动关闭</div>
				</div>
			</div>
			<div class="btnCenter"><a href="javascript:;" onclick="closePopup(&quot;'.$review_done_tip.'&quot;);" class="btn btnOrange"><button type="button">关闭</button></a></div>');
$h4_contents = db_to_html("评论发表成功");
$PopupObj[] = tep_popup_alert($review_done_tip, $review_done_con_id, "500", $h4_contents, $con_contents );


// ajax action process start
if($_GET['ajax']=="true" && tep_not_null($_GET["action"])){
	header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
	header( "Cache-Control: no-cache, must-revalidate" );
	header( "Pragma: no-cache" );
	switch($_GET["action"]){
		case "process":	//快速登录
			include('login.php');
			if($_GET['login_for']=="reply"){	// for 回复
				$js_str = '[JS]';
				$replyPopupHtml = get_reply_popup();
				$js_str .= 'jQuery("#replyPopup").replaceWith("'.addslashes($replyPopupHtml).'");';
				$js_str .= 'OpenReplyPopup("'.$popupTip.'","'.$popupConCompare.'",'. (int)$_POST['parent_reviews_id'].');';
				$js_str .= '[/JS]';
			}elseif($_GET['login_for']=="reviews"){	//for 评论
				$js_str = '[JS]';
				$js_str .= 'closePopup("CommonFastLoginPopup");jQuery("#reviewNewCon").show();';
				$js_str.='jQuery("#review_fast_login_button_loged").show();jQuery("#review_fast_login_button_notloged").hide();';						
				$js_str .= 'document.getElementById("WriteReviewForm").elements["email_address"].value="'.$customer_email_address.'";';
				$js_str .= 'jQuery("#review_box").fadeIn(300);';
				$js_str .= 'document.getElementById("customers_name_label_review").innerHTML="'.db_to_html($customer_first_name).'";';				
				$js_str .= '[/JS]';
			}elseif($_GET['login_for']=="question"){	//for 咨询 add by vincent 2011.3.23
				$js_str = '[JS]';
				//$js_str .= 'jQuery("#question_login_box").hide();';
				$js_str .= 'closePopup("CommonFastLoginPopup");jQuery("#WriteQuestionForm").show();';
				$js_str.='jQuery("#review_fast_login_button_loged").show();jQuery("#review_fast_login_button_notloged").hide();';
				//$js_str .= 'jQuery("#'.$_GET['btnid'].'_loged").show();jQuery("#'.$_GET['btnid'].'_notloged").hide();';				  
				$js_str .= 'document.getElementById("product_question_write").elements["customers_email"].value="'.$customer_email_address.'";';
				$js_str .= 'document.getElementById("product_question_write").elements["c_customers_email"].value="'.$customer_email_address.'";';
				$js_str .= 'document.getElementById("product_question_write").elements["customers_name"].value="'.$customer_first_name.'";';
				$js_str .= 'document.getElementById("customers_name_label").innerHTML="'.db_to_html($customer_first_name).'";';
				//$js_str .= 'jQuery("#review_box").fadeIn(300);';
				$js_str .= '[/JS]';
			}elseif($_GET['login_for'] == 'all_question'){ //for全部咨询 add by vincent 2011.3.24				
				$js_str = '[JS]';
				$js_str .= 'jQuery("#question_login_box").hide();';
				$js_str .= 'jQuery("#WriteQuestionForm").show();';				  
				$js_str .= 'document.getElementById("product_question_write").elements["customers_email"].value="'.$customer_email_address.'";';
				$js_str .= 'document.getElementById("product_question_write").elements["c_customers_email"].value="'.$customer_email_address.'";';
				$js_str .= 'document.getElementById("product_question_write").elements["customers_name"].value="'.$customer_first_name.'";';
				$js_str .= 'document.getElementById("customers_name_label").innerHTML="'.db_to_html($customer_first_name).'";';
				$js_str .= '[/JS]';
			}
			$js_str = preg_replace('/[[:space:]]+/',' ',$js_str);
			echo $js_str;
			exit;
		break;
		case "submit_reviews":
			$js_str = '[JS]';
			$customers_email = tep_db_prepare_input($_POST['email_address']);
			$reviews_title = ajax_to_general_string(tep_db_prepare_input($_POST['review_title']));
			$review = ajax_to_general_string(tep_db_prepare_input($_POST['review']));
			$booking_rating = max((int)$_POST['starsResult1'],1)*20;
			$travel_rating = max((int)$_POST['starsResult2'],1)*20;
			$products_id = (int)$_POST["products_id"];
			if($customers_email == ''){
			  $error = true;
			  $error_msg .= TEXT_ERROR_MSG_YOUR_EMAIL;
			}
			
			if($reviews_title == ''){
			  $error = true;
			  $error_msg .= TEXT_ERROR_MSG_REVIEW_TITLE;
			}
		 
			if (strlen($review) == 0) {
			  $error = true;
			  $error_msg .= TEXT_ERROR_MSG_REVIEW_TEXT;
			  //$messageStack->add('review', JS_REVIEW_TEXT);
			}
			//检查重复提交 vincent
			 $review_query = tep_db_query('SELECT rd.reviews_text  FROM '.TABLE_REVIEWS.' r ,'.TABLE_REVIEWS_DESCRIPTION.' rd WHERE r.customers_email =\''.tep_db_input($customers_email).'\' AND r.products_id='.$products_id);
		 $new_review = preg_replace("/(\s|\n)+/",'',html_to_db(ajax_to_general_string($_POST['review'])));			
		while($old_review = tep_db_fetch_array($review_query)) {
			$old_review = preg_replace("/(\s|\n)+/",'',$old_review['reviews_text']);	
			if($old_review == $new_review){
					$error = true; 
					$error_msg .= db_to_html("请不要重复提交评论!");
					break;
			}
		}

			if($error==true){
				$js_str .= 'alert("'.$error_msg.'");';
			}else{
				$customers_name = tep_customers_name($customer_id);
				if(!tep_not_null($customers_name)){
					$customers_name = $customers_email;
				}
				$reviews_status = "0"; //默认为未显示状态0
				$rating_total = min(100, ((int)($booking_rating+$travel_rating)/2));
				//rating_0和rating_1占20%其它占15%
				
				$rating_0 = $rating_1 = (int)($rating_total*0.2);
				$rating_2 = $rating_3 = $rating_4 = (int)($rating_total*0.15);
				$rating_5 = $rating_total-($rating_0+$rating_1+$rating_2+$rating_3+$rating_4);
				
				
				$sql_data_array = array('products_id'=>$products_id,
										'customers_id'=>(int)$customer_id,
										'customers_name'=>$customers_name,
										'rating_total'=>$rating_total,
										'customers_email'=>$customers_email,
										'date_added'=>date("Y-m-d H:i:s"),
										'booking_rating'=>$booking_rating,
										'travel_rating'=>$travel_rating,
										'travel_rating'=>$travel_rating,
										'rating_0'=>$rating_0,
										'rating_1'=>$rating_1,
										'rating_2'=>$rating_2,
										'rating_3'=>$rating_3,
										'rating_4'=>$rating_4,
										'rating_5'=>$rating_5,
										'reviews_status'=>$reviews_status
										);
				tep_db_perform(TABLE_REVIEWS, $sql_data_array);
				$reviews_id = tep_db_insert_id();
				
				$sql_data_array = array('reviews_id'=>$reviews_id,
										'languages_id'=>(int)$languages_id,
										'reviews_text'=>$review,
										'reviews_title'=>$reviews_title);
				tep_db_perform(TABLE_REVIEWS_DESCRIPTION, html_to_db($sql_data_array));
				
				// write to products_index
				$index_type = 'reviews';
				auto_add_product_index((int)$_POST['products_id'],$index_type );
				// write to products_index end
				
				#### Points/Rewards Module V2.1rc2a BOF ####*/
					if(isset($customer_id) && $customer_id!=''){
						if (USE_POINTS_SYSTEM == 'true' && (int)USE_POINTS_FOR_REVIEWS && tep_get_customers_reviews_total_today($customer_id) <= (int)EVERY_DAY_MAX_NUM_FOR_ADD_POINTS_FOR_REVIEWS ) {
							$points_toadd = USE_POINTS_FOR_REVIEWS;
							$comment = 'TEXT_DEFAULT_REVIEWS';
							$points_type = 'RV';
							tep_add_pending_points($customer_id, (int)$reviews_id , $points_toadd, $comment, $points_type, '', (int)$_POST['products_id']);
						}
					}
				#### Points/Rewards Module V2.1rc2a EOF ####*/
				
				if($reviews_status=="1"){
					
					$js_str .= 'alert("'.db_to_html("您的评论已经发表成功！").'");';
					$js_str .= 'sendFormData("frm_slippage_ajax_product_reviews","product_reviews_tabs_ajax.php?mnu=reviews&products_id='.(int)$_POST['products_id'].'&page=1","con_two_1","true");';
					
				}elseif($reviews_status=="0"){
					$js_str .= 'jQuery("#WriteReviewForm").find(":text").val("");';
					$js_str .= 'jQuery("#WriteReviewForm").find("textarea").val("");';
					$js_str .= 'showPopup("'.$review_done_tip.'","'.$review_done_con_id.'", 1);';
					$js_str .= 'sendFormData("frm_slippage_ajax_product_reviews","product_reviews_tabs_ajax.php?mnu=reviews&products_id='.(int)$_POST['products_id'].'&page=1","con_two_1","true"); ';
					$js_str .= 'window.setTimeout("closePopup(\''.$review_done_tip.'\');",'.($close_time_num*1000).');';
				}
			}
			$js_str .= '[/JS]';
			$js_str = preg_replace('/[[:space:]]+/',' ',$js_str);
			echo $js_str;
			exit;
		break;
		case "submit_reply":	//给评论回复
			$error = false;
			if(!(int)$_POST["products_id"]){
				$error = true;
				$error_msn .= db_to_html("无产品ID ");
			}
			if(!(int)$_POST["parent_reviews_id"]){
				$error = true;
				$error_msn .= db_to_html("无主帖ID ");
			}
			if(!tep_not_null($_POST["reviews_text"])){
				$error = true;
				$error_msn .= db_to_html("请输入回复内容！");
			}
			if($error == true){
				$js_str = '[JS]alert("'.$error_msn.'");[/JS]';
			}else{
				$customers_name = tep_customers_name($customer_id);
				if(!tep_not_null($customers_name)){
					$customers_name = tep_get_customers_email($customer_id);
				}
				$sql_data_array = array('parent_reviews_id'=>(int)$_POST["parent_reviews_id"],'products_id'=>(int)$_POST["products_id"],'customers_id'=>(int)$customer_id,'date_added'=>date("Y-m-d H:i:s"),'customers_name'=>$customers_name, 'reviews_status'=>"0");
				tep_db_perform(TABLE_REVIEWS, $sql_data_array);
				$reviews_id = tep_db_insert_id();
				
				$reviews_text = ajax_to_general_string(tep_db_prepare_input($_POST["reviews_text"]));
				$sql_data_array = array('reviews_id'=>$reviews_id,'languages_id'=>(int)$languages_id,'reviews_text'=>$reviews_text);
				tep_db_perform(TABLE_REVIEWS_DESCRIPTION, html_to_db($sql_data_array));
				$js_str = '[JS]';
				$js_str .= 'showPopup("'.$reply_done_tip.'","'.$reply_done_con_id.'", 1);';
				$replyPopupHtml = get_reply_popup();
				$js_str .= 'jQuery("#replyPopup").hide();';
				$js_str .= 'jQuery("#reviews_text").val("");';
				$js_str .= 'jQuery("#Reply_A_'.(int)$_POST["parent_reviews_id"].'").removeClass("replyAOn");';
				$js_str .= 'sendFormData("frm_slippage_ajax_product_reviews","product_reviews_tabs_ajax.php?mnu=reviews&products_id='.(int)$_POST['products_id'].'&page=1","con_two_1","true"); ';
				$js_str .= 'window.setTimeout("closePopup(\''.$reply_done_tip.'\');",'.($close_time_num*1000).');';
				$js_str .= '[/JS]';
			}
			$js_str = preg_replace('/[[:space:]]+/',' ',$js_str);
			echo $js_str;
			exit;
		break;
		case "SetAsGoodComment": 
			if((int)$_GET["reviews_id"]&&tep_not_null($_GET["good_or_bad"])){
				$can_repeat_vote = false;	//是否允许重复投票
				if($can_repeat_vote == false){
					if($_COOKIE['votes']['reviews'][(int)$_GET["reviews_id"]]=="true"){
						$js_str = '[JS]';
						$js_str .= 'jQuery("#replyTip_'.(int)$_GET["reviews_id"].'").html("'.db_to_html("<font color=#F00>请勿重复投票！</font>").'");';
						$js_str .= 'jQuery("#replyTip_'.(int)$_GET["reviews_id"].'").removeClass("tip tipSuccess").addClass("tip tipSuccess"); ';
						$js_str .= '[/JS]';
						$js_str = preg_replace('/[[:space:]]+/',' ',$js_str);
						echo $js_str;
						exit;
					}else{
						setcookie('votes[reviews]['.(int)$_GET["reviews_id"].']', 'true', time() +(3600*24*30*365));
					}
				}
				
				$set_field = ' `good_comment_num` = (`good_comment_num`+1) ';
				if($_GET["good_or_bad"]=="bad"){
					$set_field = ' `bad_comment_num` = (`bad_comment_num`+1) ';
				}
				tep_db_query('UPDATE `reviews` SET '.$set_field.' WHERE reviews_id="'.(int)$_GET["reviews_id"].'" ');
				$sql = tep_db_query('SELECT good_comment_num, bad_comment_num  FROM `reviews` WHERE reviews_id="'.(int)$_GET["reviews_id"].'" ');
				$row = tep_db_fetch_array($sql);
				$js_str = '[JS]';
				$js_str .= 'jQuery("#replyTip_'.(int)$_GET["reviews_id"].'").html("'.db_to_html("非常感谢您的评价！").'");';
				$js_str .= 'jQuery("#replyTip_'.(int)$_GET["reviews_id"].'").removeClass("tip tipSuccess").addClass("tip tipSuccess"); ';
				$js_str .= 'jQuery("#badNum_'.(int)$_GET["reviews_id"].'").html("'.$row['bad_comment_num'].'"); ';
				$js_str .= 'jQuery("#goodNum_'.(int)$_GET["reviews_id"].'").html("'.$row['good_comment_num'].'"); ';
				$js_str .= '[/JS]';
				$js_str = preg_replace('/[[:space:]]+/',' ',$js_str);
				echo $js_str;
				exit;
				
			}
			exit;
		break;
		
	}
}
// ajax action process end



//取得评论和评分总数
$reviews_count_query = tep_db_query("select count(*) as total from " . TABLE_REVIEWS . " where products_id = '" . (int)$_GET['products_id'] . "' and reviews_status='1' ");
$reviews_count = tep_db_fetch_array($reviews_count_query);
$reviews_total = $reviews_count['total'];

//$ratings_count_query = tep_db_query("select count(*) as total, AVG(rating_total) as rating_total_avg, AVG(booking_rating) as booking_rating_avg, AVG(travel_rating) as travel_rating_avg from " . TABLE_REVIEWS . " where products_id = '" . (int)$_GET['products_id'] . "' and reviews_status='1' and parent_reviews_id='0' ");

$ratings_count = tep_get_products_rating((int)$_GET['products_id']);
$ratings_total = $ratings_count['total'];
//取得满意度 start
//取得预订的满意度平均值booking_rating
//取得出行的满意度平均值travel_rating
$ratings_total_avg = number_format($ratings_count['rating_total_avg'],0);
$booking_rating_avg = number_format($ratings_count['booking_rating_avg'],0);
$travel_rating_avg = number_format($ratings_count['travel_rating_avg'],0);
//如果没有以上的数据则默认为总体的满意度
if($booking_rating_avg<60){ $booking_rating_avg = $ratings_total_avg;}
if($travel_rating_avg<60){ $travel_rating_avg = $ratings_total_avg;}
//只有平均值达到100的才是五星，其它的按-20递减

//$booking_ratings = get_ratings_datas($booking_rating_avg);
//$travel_ratings = get_ratings_datas($travel_rating_avg);

//取得满意度 end



?>
<script type="text/javascript">
//打开回复框
function OpenReplyPopup(box_id, box_con_id, parent_reviews_id){
	var from = document.getElementById("reply_reviews_form");
	if(from==null){ alert("no from reply_reviews_form"); }
	from.elements["parent_reviews_id"].value = parent_reviews_id;
	jQuery("a[id^=Reply_A_]").removeClass("replyAOn");
	jQuery("#Reply_A_"+parent_reviews_id).addClass("replyAOn");
	showPopup(box_id, box_con_id ,'off', '','','fixedTop','Reply_A_'+parent_reviews_id, "");
}
//快速登录 for reply or reviews
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
//提交回复内容
function submit_reply(form_id){
	var from = document.getElementById(form_id);
	var error = false;
	if(from.elements["reviews_text"].value.length<1){
		error = true;
		error_msn = "<?php echo db_to_html("请输入回复内容！");?>";
	}
	if(error == true){ alert(error_msn); return false;}
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('product_reviews_tabs_ajax.php','ajax=true&action=submit_reply')) ?>");
	var success_msm="";
	var success_go_to="";
	var replace_id="";
	ajax_post_submit(url,form_id,success_msm,success_go_to, replace_id);
	return true;
}

//提交评论内容
function Submit_Reviews(form_id) {
	var WRForm = document.getElementById(form_id);
	var error_msn = '';
	var error = false;
	
	for(i=0; i<WRForm.length; i++){
		if(WRForm.elements[i]!=null){
			if(WRForm.elements[i].value.length < 1 && WRForm.elements[i].className.search(/required/g)!= -1 && WRForm.elements[i].disabled!=true){
				error = true;
				error_msn +=  "* " + WRForm.elements[i].title + "\n\n";
			}
		}
	}
	
	if(error==true){
		alert(error_msn);
		return false;
	}
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('product_reviews_tabs_ajax.php','ajax=true&action=submit_reviews')) ?>");
	var success_msm="";
	var success_go_to="";
	var replace_id="";
	ajax_post_submit(url,form_id,success_msm,success_go_to, replace_id);
	return true;
}

</script>              
<?php

	$data_fields = "select r.*, rd.reviews_title, rd.reviews_text ";
	$data_tables = TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd ";
	$data_where = " where r.products_id = '" . (int)$products_id . "' and r.reviews_id = rd.reviews_id and r.reviews_status='1' and rd.languages_id = '" . (int)$languages_id . "' and r.parent_reviews_id='0' ";
	$data_group = " group by r.reviews_id ";
	$data_order = " order by r.reviews_id desc ";
	

if($_GET['reviews_type']=="tourism_has_been"){	//去过了
	//$data_tables .= ", orders o, orders_products op ";
	//$data_where .= " and o.customers_id = r.customers_id and o.orders_id = op.orders_id and op.products_id = r.products_id ";
	$data_where .= " and r.has_purchased ='2' ";
}elseif($_GET['reviews_type']=="will_to_travel"){	//打算去
	//$data_tables .= ", customers_basket cb ";
	//$data_where .= " and cb.customers_id = r.customers_id and CONCAT('#',cb.products_id,'{') like CONCAT('#',r.products_id,'{%') ";
	$data_where .= " and r.has_purchased ='1' ";
}elseif($_GET['reviews_type']=="essence"){
	$data_where .= " and r.is_essence ='1' ";
}elseif($_GET['reviews_type']=="top"){
	$data_where .= " and r.is_top ='1' ";
}

$reviews_str = $data_fields." from ".$data_tables.$data_where.$data_group.$data_order;
//echo $reviews_str;
$displayNum = tep_not_null($_GET['seeAll'])? 10:5; 
$reviews_split = new splitPageResults($reviews_str, $displayNum);

if($reviews_split->number_of_rows >  $displayNum)

if($reviews_split->number_of_rows > 0) {
	$extra_qa_query_send ='';				
	if(isset($HTTP_GET_VARS['mnu']) && $HTTP_GET_VARS['mnu'] == 'qanda'){	
		$extra_qa_query_send = 'mnu=qanda&';
	}elseif(isset($HTTP_GET_VARS['mnu']) && $HTTP_GET_VARS['mnu'] == 'reviews'){
		$extra_qa_query_send = 'mnu=reviews&';
	}else{
		$extra_qa_query_send = 'mnu=qanda&';
	}
	$reviews_split_pages_str = '';
	$reviews_split_pages_str .= tep_draw_form('frm_slippage_ajax_product_reviews', '' ,"post",'id=frm_slippage_ajax_product_reviews');
	$reviews_split_pages_str .= TEXT_RESULT_PAGE . ' ' . $reviews_split->display_links_ajax(MAX_DISPLAY_PAGE_LINKS, $extra_qa_query_send.tep_get_all_get_params(array('mnu','rn','page','info')),'product_reviews_tabs_ajax.php','frm_slippage_ajax_product_reviews','con_two_1'); 				
	$reviews_split_pages_str .= '<input type="hidden" name="selfpagename_top" value="products_qa_top">';
	$reviews_split_pages_str .= '<input type="hidden" name="ajxsub_send_qa_req" value="true">';
	$reviews_split_pages_str .= '</form>';
}
?>
      
<?php if($reviews_split->number_of_rows == 0) {?>
       <div class="noContentTop">
        <div class="left review"> <?php
			if (USE_POINTS_SYSTEM == 'true' && (int)USE_POINTS_FOR_REVIEWS && tep_get_customers_reviews_total_today($customer_id) <= (int)EVERY_DAY_MAX_NUM_FOR_ADD_POINTS_FOR_REVIEWS ) {
				//howard edited
				if((int)$customer_id){
					echo sprintf(REVIEW_HELP_LINK, USE_POINTS_FOR_REVIEWS, '<a style="display:none;" href="' . tep_href_link('my_points.php','', 'SSL') . '" title="' . MY_POINTS_VIEW . '">' .db_to_html('积分奖励') . '</a>');
				}else{
					echo sprintf(REVIEW_HELP_LINK, USE_POINTS_FOR_REVIEWS, '<a href="' . tep_href_link('points.php') . '" title="' . TEXT_MENU_JOIN_REWARDS4FUN . '">' . TEXT_MENU_JOIN_REWARDS4FUN . '</a>');//
				}
				//howard edited end
			}?></div>
        <div class="right">
         <?php echo $html_review_button?>
        </div>
    </div>
    <div class="noContent"><?php echo db_to_html(' 暂时没有游客评论。');?>  </div>
<?php }else{?>
        <div class="reviewTop">
          <div class="left">
            <h4><?php echo db_to_html("客户总体满意度");?></h4>
            <h2 id="comment_bai_fen_bi_h2"><?= $ratings_total_avg?>%</h2>
            <p><?php echo db_to_html('已有<b id="data_statistics_ratings">'.$ratings_total.'</b>人点评<span style="display:none"><b id="data_statistics_reviews">' . $reviews_total . '</b>人评论。</span>')?></p>
          </div>
		  
          <div class="right">
            <ul>
            	<li><?php echo db_to_html("订购过程：");?><div><div style="width:<?php echo $booking_rating_avg;?>%"></div></div><span><?php echo $booking_rating_avg;?>%</span>(<?php echo db_to_html(sprintf('%d人满意',$ratings_total));?>)</li>
                <li><?php echo db_to_html("行程安排：");?><div><div style="width:<?php echo $travel_rating_avg;?>%"></div></div><span><?php echo $travel_rating_avg;?>%</span>(<?php echo db_to_html(sprintf('%d人满意',$ratings_total));?>)</li>
            </ul>
            
            <?php 
				/* 

            <p><br />
			<?php echo db_to_html('共有<b id="data_statistics_reviews">'.$reviews_total.'</b>位游客参与了评论，<b id="data_statistics_ratings">'.$ratings_total.'</b>位游客进行了评分。');?>
			
			<br /></p>
            
			<!-- // Points/Rewards Module V2.1rc2a start //-->
			<p>
			<?php
			if (USE_POINTS_SYSTEM == 'true' && (int)USE_POINTS_FOR_REVIEWS && tep_get_customers_reviews_total_today($customer_id) <= (int)EVERY_DAY_MAX_NUM_FOR_ADD_POINTS_FOR_REVIEWS ) {
				//howard edited
				if((int)$customer_id){
					echo sprintf(REVIEW_HELP_LINK, USE_POINTS_FOR_REVIEWS, '<a href="' . tep_href_link('my_points.php','', 'SSL') . '" title="' . MY_POINTS_VIEW . '">' .db_to_html('积分奖励') . '</a>');
				}else{
					echo sprintf(REVIEW_HELP_LINK, USE_POINTS_FOR_REVIEWS, '<a href="' . tep_href_link('points.php') . '" title="' . TEXT_MENU_JOIN_REWARDS4FUN . '">' . TEXT_MENU_JOIN_REWARDS4FUN . '</a>');
				}
				//howard edited end
			}
			?>
			<br /><br />
			</p>
			<!-- // Points/Rewards Module V2.1rc2a eof //-->
			<div class="newBtn">
              
			    <?php 
			    
			  /*<a href="javascript:;" name="reviewNew" onclick="jQuery('#reviewNewCon').show(); jQuery('#SubmitLogin').focus(); jQuery('#SubmitRevieweButton').focus();" class="btn btnGrey"><button type="button"><?php echo db_to_html("我要评论")?></button></a>
			<a href="用户评论详细页面.html" class="btn btnGrey" onmouseover="jQuery('#newBtnTip').show();" onmouseout="jQuery('#newBtnTip').hide();"><button>我要评论</button></a>
              <div class="tip" id="newBtnTip"><div class="con"><!--按钮提示文字--></div><div class="bottom"></div></div>
              * /?>
            </div> */ ?>
          </div>
          <div class="dianpin">
          <?php echo $html_review_button ?>
          </div>
        </div>
        
        <ul class="reviewTab">
          <form id="reviews_type_from" name="reviews_type_from"></form>
		  <script type="text/javascript">
			function filter_reviews(reviews_type){
				sendFormData('reviews_type_from','product_reviews_tabs_ajax.php?mnu=reviews&products_id=<?php echo (int)$products_id;?>&page=1&reviews_type='+reviews_type,'con_two_1','true');
			}
		  </script>
		  <?php
		  $li_0_class = $li_1_class = $li_2_class = $li_3_class = $li_4_class = '';
		  if(!isset($_GET['reviews_type']) || $_GET['reviews_type']=="all"){ $li_0_class = 'class="selected"';}
		  if($_GET['reviews_type']=="tourism_has_been"){ $li_1_class = 'class="selected"';}
		  if($_GET['reviews_type']=="will_to_travel"){ $li_2_class = 'class="selected"';}
		  if($_GET['reviews_type']=="essence"){ $li_3_class = 'class="selected"';}
		  if($_GET['reviews_type']=="top"){ $li_4_class = 'class="selected"';}
		  ?>
		  <li <?=$li_0_class?>><a href="javascript:;" onclick="filter_reviews('all')"><?php echo db_to_html("所有评论")?></a><span></span></li>
          <li <?=$li_1_class?>><a href="javascript:;" onclick="filter_reviews('tourism_has_been')"><?php echo db_to_html("去过了")?></a><span></span></li>
          <li <?=$li_2_class?>><a href="javascript:;" onclick="filter_reviews('will_to_travel')"><?php echo db_to_html("打算去")?></a><span></span></li>
          <li <?=$li_3_class?>><a href="javascript:;" onclick="filter_reviews('essence')"><?php echo db_to_html("精华")?></a><span></span></li>
          <li <?=$li_4_class?>><a href="javascript:;" onclick="filter_reviews('top')"><?php echo db_to_html("置顶")?></a><span></span></li>
        </ul>        
	  <div id="reviews_list">
	  	<div id="NewReviewsList"></div>
        <div id="reviews_page_1" class="reviewCon">
	<?php
	$reviews_query = tep_db_query($reviews_split->sql_query);
	$loop_do = 0;
	while ($reviews = tep_db_fetch_array($reviews_query)) {	
		$loop_do++;

		//$box_class = 'review_box';
		if($loop_do%2==0 && $loop_do>0){
			//$box_class = 'review_box review_box_ou';
		}
		$has_purchase = "";
		if($reviews['has_purchased']=="2"){
			$has_purchase = "<b style='display:none'>[去过了]</b> ";
		}
		$good_comment_num = $reviews['good_comment_num'];
		$bad_comment_num = $reviews['bad_comment_num'];
		
		//取得回贴资料
		$reply_sql = tep_db_query("select r.reviews_id, r.customers_id, r.customers_name, DATE_FORMAT(r.date_added,'%m/%d/%Y %H:%i:%S') as date_added, r.admin_id, rd.reviews_text from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.parent_reviews_id = '" . $reviews['reviews_id'] . "' and r.reviews_id = rd.reviews_id and r.reviews_status='1' and rd.languages_id = '" . (int)$languages_id . "' order by r.reviews_id asc");

		$reply_total = tep_db_num_rows($reply_sql);
		if((int)$reply_total){
			$reply_loop = 0;
			while($reply_rows = tep_db_fetch_array($reply_sql)){
				$replys[$reviews['reviews_id']][$reply_loop] = array('id'=> $reply_rows['reviews_id'], 
																	'cust_id'=> $reply_rows['customers_id'],
																	'cust_name'=> db_to_html(tep_db_output($reply_rows['customers_name'])),
																	'date'=> $reply_rows['date_added'],
																	'text'=> nl2br(db_to_html(tep_db_output($reply_rows['reviews_text']))),
																	'floor'=> ($reply_loop+1).db_to_html('楼'),
																	'admin_id'=> $reply_rows['admin_id']
																	);
				$reply_loop++;
			}
		}
		
		$individual_space_links = NULL;
		if((int)$reviews['customers_id']){
		  $individual_space_links = tep_href_link('individual_space.php','customers_id='.$reviews['customers_id']);
		}
		//头像
		if(!tep_not_null($gender)){
			$gender = tep_customer_gender($reviews['customers_id']);
		}
		$head_img = "touxiang_no-sex.gif";
		if(strtolower($gender)=='m' || $gender=='1'){ $head_img = "touxiang_boy.gif"; }
		if(strtolower($gender)=='f' || $gender=='2'){ $head_img = "touxiang_girl.gif"; }
		$head_img = 'image/'.$head_img;
		$head_img = tep_customers_face($reviews['customers_id'], $head_img);
	?>
        <dl class="list">
          
            <?php
            $use_face_or_satisfaction = '';	//这块是用头像还是满意度，face是头像
			if($use_face_or_satisfaction == 'face'){
				if($individual_space_links != NULL){
			?>
              <dt>
			  <a target="_blank" href="<?= $individual_space_links;?>"><img src="<?= $head_img?>" width="48" height="48" /></a>
              <span><a target="_blank" href="<?= $individual_space_links;?>"><?php echo db_to_html($reviews['customers_name'])?></a></span>
			  </dt>
            <?php
				}else{
			?>
              <img src="<?= $head_img?>" width="48" height="48" />
              <span style="color:#999"><?php echo db_to_html($reviews['customers_name'])?></span>
            <?php
				}
			}else{?>
			 <dt class="s_1">
			 <?php echo db_to_html('满意度')?><br />
             <em><?php echo $reviews['rating_total']?>%</em>
			 </dt>
			<?php
			}
			?>
			
          </dt>
          <dd>
            <div class="top">
            	<?php
					$booking_rating_num = $reviews['booking_rating'];
					if(!(int)$reviews['booking_rating']){ $booking_rating_num = $reviews['rating_total'];}
					$booking_rating_r = get_ratings_datas($booking_rating_num);
					
					$travel_rating_num = $reviews['travel_rating'];
					if(!(int)$reviews['travel_rating']){ $travel_rating_num = $reviews['rating_total'];}
					$travel_rating_r = get_ratings_datas($travel_rating_num);
				?>
              <h2><em><?php echo db_to_html("订购:").$booking_rating_r[1];?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo db_to_html("行程:").$travel_rating_r[1];?></em><?php 
			  //    &nbsp;&nbsp;'.tep_db_output($reviews['reviews_title']) 服务不错 服务很好 等文字在这个元素里面  
			  echo db_to_html($has_purchase.'<b>'.$reviews['customers_name'].'</b><span class="date">(' . date('Y/m/d',strtotime($reviews['date_added'])) . ')</span>')?>
              	 
              </h2>

				<?php
				if($use_face_or_satisfaction == 'face'){
					if((int)$reviews['rating_total']){
						//笑脸图标
						$face_imgs = 'face_1.gif';
						if($reviews['rating_total']<90){
							$face_imgs = 'face_2.gif';
						}
						if($reviews['rating_total']<80){
							$face_imgs = 'face_3.gif';
						}
					}
				?>
              <span onmouseover="jQuery('#scoreTip_<?= $reviews['reviews_id']?>').show();" onmouseout="jQuery('#scoreTip_<?= $reviews['reviews_id']?>').hide();"><img src="image/icons/<?=$face_imgs?>" /><label><?= $reviews['rating_total']?>%</label></span>
              <div id="scoreTip_<?= $reviews['reviews_id']?>" class="scoreTip" style="display:none">
                <table width="100%" cellpadding="0" cellspacing="0" border="0" class="popupTable">
                <tr>
                  <td class="topLeft"></td><td class="side"></td><td class="topRight"></td></tr><tr><td class="side"></td>
                    <td class="con">
                      <div class="popupCon">
                          <h4><?php echo db_to_html("评分详情")?></h4>
                            <div class="vote">
							  <div class="voteCon">
                                <label><?php echo db_to_html("订购:")?></label>
							    <div class="star"><?php echo $booking_rating_r[0];?></div>
                                <div class="tip"><?php echo $booking_rating_r[1];?></div>
						      </div>
							  <div class="voteCon">
                                <label><?php echo db_to_html("出行:")?></label>
							    <div class="star"><?php echo $travel_rating_r[0];?></div>
                                <div class="tip"><?php echo $travel_rating_r[1];?></div>
						      </div>
                            </div>
                          </div>
                  </td><td class="side"></td></tr><tr><td class="botLeft"></td><td class="side"></td><td class="botRight"></td></tr>
                </table>
              </div>
			  <?php }else{?>
			  <p class="color_orange fr" style="display:none"><?php echo db_to_html("订购:").$booking_rating_r[1];?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo db_to_html("出行:").$travel_rating_r[1];?></p>
			  <?php }?>
            </div>
            <p><?php echo db_to_html(tep_db_output($reviews['reviews_text']))?></p>
            <div class="bot" style="display:none">
              <div class="right">
              	<a id="Reply_A_<?= $reviews['reviews_id']?>" href="javascript:;" onclick="OpenReplyPopup('<?=$popupTip?>','<?=$popupConCompare?>',<?= $reviews['reviews_id']?>); " class="replyA"><?php echo db_to_html('回复('.$reply_total.')')?></a>
                <span id="replyTip_<?= $reviews['reviews_id']?>" class="tip"><?php echo db_to_html("此评论对我")?></span>
                <a href="javascript:;" onclick="SetAsGoodComment(<?= $reviews['reviews_id']?>,'good'); "><?php echo db_to_html("有用")?>(<label id="goodNum_<?= $reviews['reviews_id']?>"><?= $good_comment_num?></label>)</a>
                <a href="javascript:;" onclick="SetAsGoodComment(<?= $reviews['reviews_id']?>,'bad'); "><?php echo db_to_html("没用")?>(<label id="badNum_<?= $reviews['reviews_id']?>"><?= $bad_comment_num?></label>)</a> 
              </div>
              <span style="display:none"><?php echo db_to_html('评论时间：').tep_date_long_review($reviews['date_added']);?></span>
              <div class="del_float"></div>
            </div>
          </dd>
		  <?php
		  $reply_style = ' style="display:none" ';
		  if((int)$reply_total){
			  $reply_style = ""; 
		  }
		  ?>
		  <dt class="reply" <?= $reply_style?>></dt>
          <dd class="reply" id="reply_list_<?= $reviews['reviews_id']?>" <?= $reply_style?>>
             <ul>
              <?php
			  $array = $replys[$reviews['reviews_id']];
			
			  for($j = 0; $j<sizeof($array); $j++){
			  	//获取用户的性别			  	
			  	$genderText = '';			  
			  	$gender = tep_customer_gender($array[$j]['cust_id']);
			  	
			    if(strtolower($gender)=='m' || $gender=='1'){ $genderText = db_to_html("先生"); }
				if(strtolower($gender)=='f' || $gender=='2'){ $genderText =  db_to_html("女士"); }
				
				$cust_name = '<a href="'.tep_href_link('individual_space.php','customers_id='.$array[$j]['cust_id']).'" target="_blank">'.$array[$j]['cust_name'].$genderText.'</a>';
			  	if(!(int)$array[$j]['cust_id']){
				  $cust_name = '<span style="color:#999">'.$array[$j]['cust_name'].$genderText.'</span>';
				}
				
				if((int)$array[$j]['admin_id']){
					$cust_name = '<span style="color:#EA7808">usitrip</span>';
				}
				$reply_li_class = '';
				if($j==0){
					$reply_li_class = ' class="first" ';
				}
			  ?>
			  <li <?= $reply_li_class?>><?= $cust_name;?> <?= $array[$j]['date']?><span><?= $array[$j]['floor']?></span><p><?= $array[$j]['text']?></p></li>
			  <?php
			  }
			  ?>
            </ul>
          </dd>
		  
        </dl>
     <?php
	}// while loop end
	 ?>
	 </div></div>
 <?php  } //end 有内容?>
 
		<?php 
		 if($_GET['seeAll']){
            if($reviews_split->number_of_rows > $displayNum) {
				?>
				<div class="page"> <?php echo  $reviews_split_pages_str?></div>
				<div class="reviewNew"><div class="newBtn"><?php #echo $html_review_button?></div></div>		
				<?php 
			}else{?>
           		<div class="reviewNew"><div class="newBtn"><?php #echo $html_review_button?></div></div>
				<?php 
			}
		}else {
			if($reviews_split->number_of_rows > $displayNum) { //大于页码 显示查看所有按钮?> 
				<div class="reviewNew">
                	<div class="newBtn"><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=reviews&seeAll=all-reviews&'.tep_get_all_get_params(array('info','mnu','rn','seeAll')))?>#anchor2"><?php echo db_to_html(sprintf('浏览所有评论&gt;&gt;<span>(共%d条)</span>',$reviews_split->number_of_rows))?></a><?php  #echo$html_review_button; ?></div>
                </div>
 				<?php 
			}elseif($reviews_split->number_of_rows > 0){//不够一页仅显示我要评论按钮?>
				<div class="reviewNew"><div class="newBtn"> <?php #echo $html_review_button?></div></div>
 				<?php 
			}else{
					   //为空时按钮会显示到上部
			}
		}   
		
		// 如果用户已经登录  则出现评论框 start {
		
		?>
		 
		  <div id="reviewNewCon" style="display:none">
		  <?php if(!(int)$customer_id){//no login
				$review_box_style = ' style="display:none;" ';
			?>
			<?php
			}else{//loging
				if(!tep_not_null($email_address)){
					$email_address = $customer_email_address;
				}
				$review_box_style = ' ';
			}
			?>
			<ul id="review_box"  class="reviewNewCon" <?= $review_box_style?>>
			
				<form onsubmit="Submit_Reviews(this.id); return false" id="WriteReviewForm" name="WriteReviewForm" method="post" action="">
				<?php
				echo tep_draw_hidden_field('email_address','',' readonly="true" class="required validate-email text" title="'.db_to_html('请输入您的电子邮箱').'"');
				echo tep_draw_hidden_field('products_id');
				?>
				<h3>
				  <b><?php echo db_to_html("评论")?></b>
				  <a  id="customers_name_label_review"  href="<?= tep_href_link('individual_space.php')?>"><?php echo db_to_html($customer_first_name)?></a>				
				  <?php echo db_to_html($Today_date);?>
				</h3>
				
				<li>
				  <label><?php echo db_to_html('标题：')?></label><?php echo tep_draw_input_field('review_title','',' title="'.db_to_html('请输入标题').'" class="required text headline" ') ?>
				</li>
				<li>
				  <label><?php echo db_to_html('内容：')?></label><?php echo tep_draw_textarea_field('review', 'soft', '', '','',' class="required textarea" id="review" title="'.db_to_html('请输入评论内容').'"'); ?>
				</li>
				<li>
				<label><?php echo db_to_html('评分：')?></label>
				
				<div class="vote">
                  <div class="voteCon">
				    <label><?php echo db_to_html('订购:')?></label>
				    <div onmouseout="starOut(1);" class="star">
					  <input type="hidden" name="starsResult1" id="starsResult1" value="5" />
					  <a href="javascript:;" id="star11" onmouseover="starOver(1,1);" onclick="starClick(1,1);">1</a>
					  <a href="javascript:;" id="star12" onmouseover="starOver(1,2);" onclick="starClick(1,2);">2</a>
					  <a href="javascript:;" id="star13" onmouseover="starOver(1,3);" onclick="starClick(1,3);">3</a>
					  <a href="javascript:;" id="star14" onmouseover="starOver(1,4);" onclick="starClick(1,4);">4</a>
					  <a href="javascript:;" id="star15" onmouseover="starOver(1,5);" onclick="starClick(1,5);">5</a>
				    </div>
				    <div class="tip" id="starsTips1"><?= RATING_STR_5;?></div>
				  </div>
                  <div class="voteCon">
				    <label><?php echo db_to_html('出行:')?></label>
				    <div onmouseout="starOut(2);" class="star">
					  <input type="hidden" name="starsResult2" id="starsResult2" value="5" />
					  <a href="javascript:;" id="star21" onmouseover="starOver(2,1);" onclick="starClick(2,1);">1</a>
					  <a href="javascript:;" id="star22" onmouseover="starOver(2,2);" onclick="starClick(2,2);">2</a>
				      <a href="javascript:;" id="star23" onmouseover="starOver(2,3);" onclick="starClick(2,3);">3</a>
					  <a href="javascript:;" id="star24" onmouseover="starOver(2,4);" onclick="starClick(2,4);">4</a>
					  <a href="javascript:;" id="star25" onmouseover="starOver(2,5);" onclick="starClick(2,5);">5</a>
				    </div>
				    <div class="tip" id="starsTips2"><?= RATING_STR_5;?></div>
				  </div>
				</div>
				<script type="text/javascript">
				//投票部分
				var result = 0;
				function starOver(m,n){
				 // if(result==0){
					for(var i=1; i<=n; i++){
					  document.getElementById("star"+m+i).className="";
					}
					for(var i=n+1; i<=5; i++){
					  document.getElementById("star"+m+i).className="no";
					}
					if(n==1){
					  document.getElementById("starsTips"+m).innerHTML="<?= RATING_STR_1;?>";
					}
					if(n==2){
					  document.getElementById("starsTips"+m).innerHTML="<?= RATING_STR_2;?>";
					}
					if(n==3){
					  document.getElementById("starsTips"+m).innerHTML="<?= RATING_STR_3;?>";
					}
					if(n==4){
					  document.getElementById("starsTips"+m).innerHTML="<?= RATING_STR_4;?>";
					}
					if(n==5){
					  document.getElementById("starsTips"+m).innerHTML="<?= RATING_STR_5;?>";
					}
					starClick(m,n);
				 // }
				}
				function starClick(m,n){
				  document.getElementById("starsResult"+m).value=n;
				  result=1;
				}
				function starOut(m){
				  if(result==0){
					for(var i=1; i<=5; i++){
					  document.getElementById("star"+m+i).className="";
					}
					document.getElementById("starsTips"+m).innerHTML="<?= RATING_STR_5;?>";
				  }
				}
				</script>
				</li>
				<div class="btnCenter">
				  <a href="javascript:;" class="btn btnOrange"><button id="SubmitRevieweButton" type="submit"><?php echo db_to_html("发表评论");?></button></a>
				</div>
				</form>
		  </ul>
          </div>
		<?php  
		//  } 评论框结束 end 
		?>
<script type="text/javascript">
//点击翻页到当前Tab头部
AutoToTabHead();
</script>