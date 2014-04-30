<?php
//提交评论的层
if($_GET['action']=='process' && $_POST['ajax']=='true'){

	header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
	header( "Cache-Control: no-cache, must-revalidate" );
	header( "Pragma: no-cache" );
	
	require_once('includes/application_top.php');
	require_once(DIR_FS_INCLUDES . 'ajax_encoding_control.php');

	//检测用户
	if((!tep_session_is_registered('customer_id') || !(int)$customer_id) && (int)$_POST['anonymous']<1){
		if(!tep_not_null($_POST['password'])){
			echo db_to_html('[ERROR]请输入您的登录密码！[/ERROR]');
			exit;
		}
		if(!tep_not_null($HTTP_GET_VARS['action'])){ $HTTP_GET_VARS['action'] = 'process'; }else{ $old_action = $HTTP_GET_VARS['action']; $HTTP_GET_VARS['action'] = 'process'; }
		$ajax = $_POST['ajax'];
		include('login.php');
		if(tep_not_null($old_action)){
			$HTTP_GET_VARS['action'] = $old_action;
		}
	}
	
	//print_r($_POST);
	//exit;
	
	if($_GET['action']=='process' && $error == false){	//写数据库
		$customers_name = ajax_to_general_string($_POST['customers_name']);
		$customers_email = ajax_to_general_string($_POST['email_address']);
		$reviews_title = ajax_to_general_string($_POST['review_title']); 
		$rating_0 = $_POST['rating_0'];
		$rating_1 = $_POST['rating_1'];
		$rating_2 = $_POST['rating_2'];
		$rating_3 = $_POST['rating_3'];
		$rating_4 = $_POST['rating_4'];
		$rating_5 = $_POST['rating_5'];

		//$rating = $_POST['rating'];
		$review =ajax_to_general_string($_POST['review']);
		
		$error = false;
	  
	 
		if($customers_name == ''){
		  $error = true;
		  $error_msg .= TEXT_ERROR_MSG_YOUR_NAME;
		}
		
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
	
		if(!(int)$rating_0){
		  $error = true;
		  $error_msg .=  db_to_html('请为预定选择一个评级');
		}
		if(!(int)$rating_1){
		  $error = true;
		  $error_msg .=  db_to_html('请为客服选择一个评级');
		}
		if(!(int)$rating_2){
		  $error = true;
		  $error_msg .=  db_to_html('请为住宿选择一个评级');
		}
		if(!(int)$rating_3){
		  $error = true;
		  $error_msg .=  db_to_html('请为交通选择一个评级');
		}
		if(!(int)$rating_4){
		  $error = true;
		  $error_msg .=  db_to_html('请为导游选择一个评级');
		}
		if(!(int)$rating_5){
		  $error = true;
		  $error_msg .=  db_to_html('请为行程选择一个评级');
		}
		/*if (($rating < 1) || ($rating > 5)) {
		  $error = true;
		  $error_msg .=  TEXT_ERROR_MSG_REVIEW_RATING;
		  //$messageStack->add('review', JS_REVIEW_RATING);
		}*/
		
		if($error==false){
			//星转分数 1星：20-30分   2星：40-50分    3星：60-70分    4星：80-90分   5星：100分
			$rating = $rating_0+$rating_1+$rating_2+$rating_3+$rating_4+$rating_5;
			
			tep_db_query(html_to_db ("insert into " . TABLE_REVIEWS . " (products_id, customers_id, customers_name, rating_total, date_added, reviews_status, customers_email, rating_0, rating_1, rating_2, rating_3, rating_4, rating_5) values ('" . (int)$_POST['products_id'] . "', '" . (int)$customer_id . "', '" . tep_db_input($customers_name) . "', '" . tep_db_input($rating) . "', now(), '0', '".tep_db_input($customers_email)."', '".$rating_0."' , '".$rating_1."' , '".$rating_2."' , '".$rating_3."' , '".$rating_4."' , '".$rating_5."' )"));
			$insert_id = tep_db_insert_id();
			
			tep_db_query(html_to_db ("insert into " . TABLE_REVIEWS_DESCRIPTION . " (reviews_id, languages_id, reviews_text, reviews_title) values ('" . (int)$insert_id . "', '" . (int)$languages_id . "', '" . tep_db_input($review) . "',  '" . tep_db_input($reviews_title) . "')"));
			
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
					tep_add_pending_points($customer_id, (int)$insert_id, $points_toadd, $comment, $points_type, '', (int)$_POST['products_id']);
				}
			 }
			#### Points/Rewards Module V2.1rc2a EOF ####*/
		
			echo '[SUCCESS]1[/SUCCESS]';
		}
	
	}

	if($error == true && $error_msg!=""){
		echo '[ERROR]'.$error_msg.'[/ERROR]';
	}

	exit;
}

?>

<!--写评论层-->
<div id="WriteNewReview" class="popup" >
  <table width="100%" cellpadding="0" cellspacing="0" border="0" class="popupTable">
		<tr>
		  <td class="topLeft"></td><td class="side"></td><td class="topRight"></td></tr><tr><td  class="side"></td>
			<td class="con">
			  <div class="popupCon" id="WriteNewReviewCon" style="width:528px; ">
			  <div class="popupConTop">
				  <h4><?php echo db_to_html('发表评论')?></h4><span><a href="javascript:closePopup(&quot;WriteNewReview&quot;)"><img src="<?= DIR_WS_TEMPLATE_IMAGES;?>popup/icon_x.gif" /></a></span>
				</div>
				<form action="" method="post" name="WriteReviewForm" id="WriteReviewForm" onSubmit="Submit_Reviews(); return false" >
	<table style="float:left" cellSpacing=0 cellPadding=0 width="100%" border=0>
	
	<tr><td align="center" style="font-weight: normal;">
	
	<table border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td height="25" colspan="2" align="center" class="title_line">
		  <?php
		  if(!(int)$categories_id){
			$categories_id = (int)$categories;
		  }
		  if(!(int)$categories_id){
			$categories_id = (int)$cPathOnly;
		  }
		  ?>
		<input name="categories_id" type="hidden" id="categories_id" value="<?= $categories_id?>" />
		<input name="products_id" type="hidden" id="products_id" value="<?= $products_id?>" />
		</td>
		</tr>
	  <tr>
		<td height="25" align="right" valign="top" class="title_line"><?php echo db_to_html('姓名')?>&nbsp;</td>
		<td align="left" valign="top"><?php echo tep_draw_input_field('customers_name','','  class="required validate-length-lastname" style="width: 242px;" title="'.db_to_html('请输入姓名').'"') ?>
		<span class="inputRequirement"> * </span></td>
	  </tr>
	  
	  <?php if(!(int)$customer_id){//no login?>
	  <tr>
		<td height="25" align="right" valign="top" class="title_line"><?php echo db_to_html('用户名/邮箱&nbsp;')?></td>
		<td align="left" valign="top">
		<?php echo tep_draw_input_field('email_address','','class="required validate-email" style="width: 160px;" title="'.db_to_html('请输入您的电子邮箱').'"') . '&nbsp;' . (tep_not_null(ENTRY_EMAIL_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_EMAIL_ADDRESS_TEXT . '</span>': ''); ?>
		<?php echo db_to_html('新用户请先 <a href="'.tep_href_link("create_account.php","", "SSL").'" class="sp3">注册</a>');?>	</td>
	  </tr>
	  <tr>
		<td height="25" align="right" valign="top" class="title_line"><?php echo db_to_html('密码')?>&nbsp;</td>
		<td align="left" valign="top">
		<input name="password" type="password" class="required" id="password" title="<?php echo db_to_html('请输入正确的密码')?>" style="width: 160px;" />
		<label><input name="anonymous" type="checkbox" id="anonymous" onClick="anonymous_comments(this)" value="1"> 
		<?php echo db_to_html('匿名评论，放弃获取积分')?></label>		</td>
	  </tr>
	  <?php
	  }else{//loging
		  if(!tep_not_null($email_address)){
			$email_address = $customer_email_address;
		  }
	  ?>
	  
	  <tr>
		<td height="25" align="right" valign="top" class="title_line"><?php echo db_to_html('邮箱')?>&nbsp;</td>
		<td align="left" valign="top"><?php echo tep_draw_input_field('email_address','',' readonly="true" class="required validate-email" style="width: 242px;" title="'.db_to_html('请输入您的电子邮箱').'"') . '&nbsp;' . (tep_not_null(ENTRY_EMAIL_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_EMAIL_ADDRESS_TEXT . '</span>': ''); ?></td>
	  </tr>
	  
	  <?php }?>
	  
	  <tr>
		<td height="25" align="right" valign="top" class="title_line"><?php echo db_to_html('标题')?>&nbsp;</td>
		<td align="left" valign="top"><?php echo tep_draw_input_field('review_title','','  class="required" title="'.db_to_html('请输入标题').'" style="width: 242px;"') ?><span class="inputRequirement"> * </span></td>
	  </tr>
	  <tr>
		<td height="25" align="right" valign="top" class="title_line"><?php echo db_to_html('评论内容')?>&nbsp;</td>
		<td align="left" valign="top" style="padding-top:2px;"><?php echo tep_draw_textarea_field('review', 'soft', '', '','',' class="required "  style="width: 242px; height: 80px; " id="review" title="'.db_to_html('请输入评论内容').'"'); ?><span class="inputRequirement"> * </span></td>
	  </tr>
	  
	  <tr>
	    <td height="25" colspan="2" align="center" valign="middle"><?php echo db_to_html('<b>请您评分</b>')?></td>
	    </tr>
	  <?php
	  /*取消评级 
	  <tr>
		<td height="25" align="right" valign="middle" class="title_line"><?php echo db_to_html('评级')?>&nbsp;</td>
		<td align="left" valign="top" style="padding-top:2px;">
		
		<span class="sp1"><font color="#ff0000"><b><?php echo db_to_html('差')?></b></font></span> 													
		<input name="rating" value="1" type="radio" class="required" title="<?php echo db_to_html('请选择一个评级');?>">
		<input name="rating" value="2" type="radio" class="required" title="<?php echo db_to_html('请选择一个评级');?>">
		<input name="rating" value="3" type="radio" class="required" title="<?php echo db_to_html('请选择一个评级');?>">
		<input name="rating" value="4" type="radio" class="required" title="<?php echo db_to_html('请选择一个评级');?>">
		<input name="rating" value="5" type="radio" class="required" title="<?php echo db_to_html('请选择一个评级');?>">
		<span class="sp1"><font color="#ff0000"><b><?php echo db_to_html('好')?></b></font></span>		</td>
	  </tr>
	  */?>
	  <?php
	  $reviews_array = get_reviews_array();
	  for($i=0; $i<count($reviews_array); $i++){
	  ?>
	  <tr>
	    <td height="25" align="right" valign="middle" class="title_line"><?php echo db_to_html($reviews_array[$i]['title'])?>&nbsp;</td>
	    <td align="left" valign="middle" style="padding-top:2px;">
		<?php
		foreach($reviews_array[$i]['opction'] as $key_val => $text){
			echo '<label><input name="rating_'.$i.'" value="'.$key_val.'" type="radio" class="required" title="'.db_to_html('请为'.$reviews_array[$i]['title'].'选择一个评级').'"> '.db_to_html($text).'</label> ';
		}
		?>
		</td>
	  </tr>
	  <?php 	  }
	  ?>
	  
	  <tr>
		<td height="45" align="right" class="title_line">&nbsp;</td>
		<td align="left"><?php echo tep_template_image_submit('fabiao-button.gif', db_to_html('发表')); ?></td>
	  </tr>
	</table>
	
	</td>
	</tr>
	</table>
	</form>
			  </div>
		  </td><td class="side"></td></tr><tr><td class="botLeft"></td><td class="side"></td><td class="botRight"></td></tr>
	  </table>
</div>

<?php
$p=array('/&amp;/','/&quot;/');
$r=array('&','"');
?>

<script type="text/javascript">
var WRForm = document.getElementById('WriteReviewForm');
function anonymous_comments(obj){
	var password = WRForm.elements['password'];
	if(password==null){ return false;}
	if(obj.checked==true){
		password.value = "";
		password.disabled = true;
	}else{
		password.disabled = false;
	}
}

function check_radio(name){
	var form_ = WRForm;
	if(form_.elements[name]!=null){
		for(i=0; i<form_.elements[name].length; i++){
			if(form_.elements[name][i].checked == true){
				return true;
			}
		}
	}
	return false;	
	
}


function Submit_Reviews() {
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
	
	//check radio
	/*for(i=0; i<WRForm.length; i++){
		if(WRForm.elements[i]!=null){
			if(WRForm.elements[i].type=='radio'){
				var radio_name = WRForm.elements[i].name.toString();
				if(check_radio(radio_name)==false){
					//alert(radio_name);
					error = true;
					error_msn +=  "* " + WRForm.elements[radio_name][0].title + "\n\n";
				}
				i++;
			}
		}
	 }*/
	  <?php
	  $reviews_array = get_reviews_array();
	  for($i=0; $i<count($reviews_array); $i++){
	  ?>
		if(check_radio('rating_<?= $i?>')==false){
			error = true;
			if(WRForm.elements['rating_<?= $i?>']!=null){
				error_msn +=  "* " + WRForm.elements['rating_<?= $i?>'][0].title + "\n\n";
			}
		}
	  
	  <?php
	  }
	  ?>
	
	
	if(error==true){
		alert(error_msn);
		return false;
	}else{
		var form = WRForm;
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('write_review_ajax.php','action=process')) ?>");
		var aparams=new Array();  //创建一个阵列存表单所有元素和值
	
		for(i=0; i<form.length; i++){
			if(form.elements[i].type=="radio" || form.elements[i].type=="checkbox" ){	//处理单选、复选按钮值
				var a = '';
				if(form.elements[i].checked){
					var sparam=encodeURIComponent(form.elements[i].name);  //取得表单元素名
					sparam+="=";     //名与值之间用"="号连接
					a = form.elements[i].value;
					sparam+=encodeURIComponent(a);   //获得表单元素值
					aparams.push(sparam);   //push是把新元素添加到阵列中去
				}
			}else{
				var sparam=encodeURIComponent(form.elements[i].name);  //取得表单元素名
				sparam+="=";     //名与值之间用"="号连接
				sparam+=encodeURIComponent(form.elements[i].value);   //获得表单元素值1
				aparams.push(sparam);   //push是把新元素添加到阵列中去
			}
		}
		var post_str = aparams.join("&");		//使用&将各个元素连接
		post_str += "&ajax=true";
	
	
		ajax.open("POST", url, true); 
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
		ajax.send(post_str);
	
		ajax.onreadystatechange = function() { 
			if (ajax.readyState == 4 && ajax.status == 200 ) { 
				//alert(ajax.responseText);
				
				var error_regxp = /(.*\[ERROR\])|(\[\/ERROR\].*[:space:]*.*)/g;
				if(ajax.responseText.search(/(\[ERROR\].+\[\/ERROR\])/g)!=-1){
					var error = ajax.responseText.replace(error_regxp,'');
					error = error.replace(/\<br\/\>/g,"\n");
					alert(error);
				}
	
				var success_regxp = /(.*\[SUCCESS\])|(\[\/SUCCESS\].*[:space:]*.*)/g;
				if(ajax.responseText.search(/(\[SUCCESS\]\d+\[\/SUCCESS\])/g)!=-1){		
					alert("<?php echo db_to_html('评论发表成功！');?>");
					
					//写新内容到评论列表
					var NewReviewsList = document.getElementById('NewReviewsList');
					if(NewReviewsList!=null){
						NewReviewsList.innerHTML = '<div class="pr_b_q"><div class="pr_b_q_1 sp10 sp6"><table width="100%"><tr><td width=18 align="left"><img src="image/q.gif" width="13" height="19"></td><td><B>'+ form.elements['review_title'].value +'</B></td><td align="right"></td></tr></table></div><div class="pr_b_qq sp10 sp6"><p style="text-align:right; padding-right:5px;">'+ form.elements['customers_name'].value +'&nbsp;|&nbsp;<span style="color:#F7860F"><?php echo db_to_html('[审核中]');?></span><p><div>'+ form.elements['review'].value.replace(/\n/g,'<br/>') +'</div></div></div><div class="pr_b_qing"><div class="pr_b_qimg"><img src="image/pr_s.gif"></div></div>' + NewReviewsList.innerHTML;
					}
					
					form.elements['review_title'].value='';
					form.elements['review'].value='';
					closePopup('WriteNewReview');
					
				}
				
			}
			
		}

	}
}

</script>
<!--写评论层 end-->
