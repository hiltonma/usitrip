<?php
//@author Howard

//分享给好友的邮件后台动作 start
if($_POST['ajax']=="true" && $_GET['action']=="ShareEmailToFriend"){
	$error = false;
	if(!tep_not_null($_POST['from_name'])){
		$error = true;
	}
	if(!tep_not_null($_POST['to_email_address'])){
		$error = true;
	}else{
		$mails_address = explode(',', $_POST['to_email_address']);
		for($i=0; $i<count($mails_address); $i++){
			$mails_address[$i] = trim($mails_address[$i]);
			if(tep_validate_email($mails_address[$i]) == false){
				$error = true;
				break;
			}
		}
	}
	if(!tep_not_null($_POST['mail_subject'])){
		$error = true;
	}
	if(!tep_not_null($_POST['ProdName'])){
		$error = true;
	}
	if(!tep_not_null($_POST['ProdUrl'])){
	}
	if($error == true){
		die('消息不全！');
	}
	
	$from_email_name = iconv(CHARSET,'utf-8',db_to_html("走四方网 "));
	$email_subject = iconv(CHARSET,'utf-8',db_to_html('来自您朋友')).tep_db_output($_POST['from_name']).iconv(CHARSET,'utf-8',db_to_html('的推荐：')).tep_db_output($_POST['mail_subject']).' ';
	
	$from_email_address = 'automail@usitrip.com';
	
	$patterns = array();
	$patterns[0] = '{EmailContent}';
	$patterns[1] = '{HTTP_SERVER}';
	$patterns[2] = '{FromName}';
	$patterns[3] = '{ProdName}';
	$patterns[4] = '{ProdUrl}';
	$patterns[5] = '{CONFORMATION_EMAIL_FOOTER}';
	
	
	
	$replacements = array();
	$replacements[0] = tep_db_output($_POST['mail_text']);
	$replacements[1] = HTTP_SERVER;
	$replacements[2] = tep_db_output($_POST['from_name']);
	$replacements[3] = tep_db_output($_POST['ProdName']);
	$replacements[4] = tep_db_output($_POST['ProdUrl']);
	$replacements[5] = iconv(CHARSET,'utf-8',db_to_html(nl2br(CONFORMATION_EMAIL_FOOTER)));
	
	$email_tpl = file_get_contents(DIR_FS_CATALOG.'email_tpl/header.tpl.html');
	$email_tpl.= file_get_contents(DIR_FS_CATALOG.'email_tpl/share_to_friend.tpl.html');
	$email_tpl.= file_get_contents(DIR_FS_CATALOG.'email_tpl/footer.tpl.html');
	
	$email_text = str_replace($patterns ,$replacements, iconv(CHARSET,'utf-8'.'//IGNORE',db_to_html($email_tpl)));
	$email_text = preg_replace('/[[:space:]]+/',' ',$email_text);
	
	
	$as = count($_SESSION['need_send_email']);
	$mails_address = array_unique($mails_address);
	foreach((array)$mails_address as $key => $val){
		if(strpos($val, '@') >0 ){
			//howard add use session+ajax send email
			$_SESSION['need_send_email'][$as]['to_name'] = $to_name;
			$_SESSION['need_send_email'][$as]['to_email_address'] = $val;
			$_SESSION['need_send_email'][$as]['email_subject'] = $email_subject;
			$_SESSION['need_send_email'][$as]['email_text'] = $email_text;
			$_SESSION['need_send_email'][$as]['from_email_name'] = $from_email_name;
			$_SESSION['need_send_email'][$as]['from_email_address'] = $from_email_address;
			$_SESSION['need_send_email'][$as]['email_charset'] = 'utf-8';
			$_SESSION['need_send_email'][$as]['action_type'] = 'true';
			$as++;
		}
		//howard add use session+ajax send email end
	}
	$js_str = 'auto_send_session_mail();';
	$js_str .= 'jQuery("#emailCon").hide();';
	$js_str .= 'jQuery("#emailBtnCenter").hide();';
	$js_str .= 'jQuery("#emailConSuccess").show();';
	$js_str .= 'SendEmaiSuccessAction();';
	
	echo '[JS]'.$js_str.'[/JS]';
	//print_r($_SESSION['need_send_email']);
	//echo "Send done!";
	exit;
}
//分享给好友的邮件后台动作 end



if((!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != 'on')){
	# 新写的分享功能代码 start {
	?>
    <div class="share">
    <div class="icon jiathis"><a href="javascript:void(0)" class="jiathis" target="_blank"><img src="/image/nav/share.gif" border="0" id="jiathis_a" /></a></div>
    </div>
	
    
    <?php
	$endShowCodes .= '<!--线路分享Script Start-->' . '<script type="text/javascript" src="http://v2.jiathis.com/code_mini/jia.js" charset="utf-8"></script>' . '<!--线路分享Script End-->';
	# } 新写的分享功能代码 end
	/* #原来的分享功能代码
	
	$uuid = "60d7f710-7666-4897-a0cf-2c7b32c98388";
	if(strtolower(CHARSET)=="big5"){
		$uuid = "929efbd7-68d3-4cc2-b113-24c3cb6d957f";
	}
//分享给好友的弹出层 start

$PopupShareEmailObj = <<<EOD

<div class="popup" id="PopupShareEmail" >
<form action="" method="post" enctype="multipart/form-data" name="ShareEmailForm" id="ShareEmailForm" onSubmit="return false;">
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="popupTable">
    <tr>
      <td class="topLeft"></td><td class="side"></td><td class="topRight"></td></tr><tr><td class="side"></td>
        <td class="con">
          <div class="popupCon" id="PopupShareEmailCon" style="width:500px;">
            <div id="DragShareEmail" class="popupConTop" style="cursor:move;">
				<h3><b>Email推荐给好友</b></h3><span><a href="javascript:closePopup('PopupShareEmail')"><img src="image/icons/icon_x.gif"></a></span>
			</div>
            <ul id="emailCon" class="emailCon">
EOD;


$PopupShareEmailObj .= tep_draw_hidden_field('ProdUrl', tep_href_link('product_info.php', 'products_id='.(int)$products_id));
$PopupShareEmailObj .= tep_draw_hidden_field('ProdName', $products_name);
$PopupShareEmailObj .= '
				 <li><label>您的姓名:</label>'.tep_draw_input_field('from_name','','class="required text" title="请输入您的姓名"').'</li>
				<li><label>收件人邮箱:</label><input name="to_email_address" type="text" class="required validate-email text"  title="请输入正确的邮箱！" onblur="if(this.value==\'\'){this.value=\'多个邮箱请用“,”隔开。\';this.style.color=\'#777\';}" onfocus="if(this.value==\'多个邮箱请用“,”隔开。\'){this.value=\'\';this.style.color=\'#333\';}" value="多个邮箱请用“,”隔开。" style="ime-mode: disabled; color:#777;" /></li>
                <li><label>邮件标题:</label>'.tep_draw_input_field('mail_subject',''.$products_name,'class="required text" title="请输入邮件标题" ').' </li>
                <li><label>邮件内容:</label> '.tep_draw_textarea_field('mail_text','','','','我觉得这个行程不错！',' class="textarea" title="请输入邮件内容" ').' </li>';


$PopupShareEmailObj .= <<<EOD
            </ul>
            <div id="emailBtnCenter" class="btnCenter">
                <a href="javascript:;" class="btn btnOrange"><button type="submit">发 送</button></a>
            </div>
			<div id="emailConSuccess" class="emailConSuccess" style="display:none">
			邮件发送成功！<b id="emailConSuccessTime"></b> 秒后关闭此窗口！
			</div>
	     </div>
 </td><td class="side"></td></tr><tr><td class="botLeft"></td><td class="side"></td><td class="botRight"></td></tr>
  </table>
</form>
</div>
<script type="text/javascript">
jQuery(document).ready(function(){
	new divDrag([GetIdObj('DragShareEmail'),GetIdObj('PopupShareEmail')]); 
});
</script>
EOD;

$PopupObj[] = db_to_html($PopupShareEmailObj);
//分享给好友的弹出层 end

?>

<div class="share" onmouseover="jQuery('#ShareMain').show();" onmouseout="jQuery('#ShareMain').hide();">
    <div class="icon"></div>
    <div id="ShareMain" class="shareMain">
        <div class="arrow"></div>
        <div class="shareCon">
            <div class="shareTo">
                <div class="left"><?= db_to_html("分享到");?></div>
                <div class="right">

                    <style type="text/css">
                        .bshare-custom a{ float:left; width:16px; height:16px; margin:3px 2px 4px; padding:0;}
                        .bshare-custom a.bshare-more{ width:auto; font-size:12px; line-height:16px;}
						.shareRenren{ position:absolute; right:85px; top:5px; display:none; width:16px; height:16px; background:url(http://static.bshare.cn/frame/images/logos/s3/renren.gif);}
						.shareRenrenTr{ right:5px;}
						.shareRenren:hover{ opacity:0.75;}
                    </style>
					
					<?php
					$class_renren_ft = "";
					if(strtolower(CHARSET)=="big5"){
						$class_renren_ft = "shareRenrenTr";
					}
					?>
<script type="text/javascript">
jQuery(function(){
	jQuery(".shareRenren").show();		
});
</script>
					<a href="javascript:u='http://share.xiaonei.com/share/buttonshare.do?link='+location.href+'&amp;title='+encodeURIComponent(document.title);window.open(u,'xiaonei','toolbar=0,resizable=1,scrollbars=yes,status=1,width=626,height=436');void(0)" class="shareRenren <?= $class_renren_ft?>"></a>

                    <div class="bshare-custom">
                <?php
				if(strtolower(CHARSET)=="big5"){
				?>
						<a title="<?= db_to_html("分享到facebook");?>" class="bshare-facebook"></a>
						<a title="<?= db_to_html("分享到twitter");?>" class="bshare-twitter"></a>
						<a title="<?= db_to_html("分享到yahoo收藏");?>" class="bshare-byahoo"></a>
						<a title="<?= db_to_html("分享到plurk");?>" class="bshare-plurk"></a>
						<a title="<?= db_to_html("分享到新浪微博");?>" class="bshare-sinaminiblog"></a>
						<a class="bshareNull"></a>
						<a title="<?= db_to_html("分享到开心网");?>" class="bshare-kaixin001"></a>
						<a title="<?= db_to_html("分享到豆瓣");?>" class="bshare-douban"></a>
						<a title="<?= db_to_html("分享到qq空间");?>" class="bshare-qzone"></a>
				<?php
				}else{
				?>
                        <a class="bshare-sinaminiblog" title="<?= db_to_html("分享到新浪微博");?>"></a>
                        <a class="bshareNull"></a>
                        <a class="bshare-kaixin001" title="<?= db_to_html("分享到开心网");?>"></a>
                        <a class="bshare-facebook" title="<?= db_to_html("分享到facebook");?>"></a>
                        <a class="bshare-twitter" title="<?= db_to_html("分享到twitter");?>"></a>
                        <a class="bshare-byahoo" title="<?= db_to_html("分享到yahoo收藏");?>"></a>
                        <a class="bshare-douban" title="<?= db_to_html("分享到豆瓣");?>"></a>
                        <a class="bshare-qzone" title="<?= db_to_html("分享到qq空间");?>"></a>
				<?php
				}
				?>
                        <a class="bshare-more" title="<?= db_to_html("更多平台");?>"><?= db_to_html("更多");?>&gt;&gt;</a>
                    </div>
                    
                    
					<?php
					$endShowCodes .= '<script src="http://www.bshare.cn/buttonLite.js#uuid='.$uuid.'&amp;style=-1" type="text/javascript" language="javascript"></script>
					<script src="http://www.bshare.cn/bshareC1.js" type="text/javascript" language="javascript"></script>';
					?>
					 
					<script type="text/javascript">
					jQuery(function(){
						if(typeof(bShare)!='undefined'){
							bShare.addEntry({
								 title: "<?= db_to_html("我在@走四方网 上发现一个畅销旅行团非常不错，分享给大家：").db_to_html($products_name);?>",
								 pic: "<?php
										if(stripos($url_product_image_name, 'http://')!==false){
											echo $url_product_image_name;
										}elseif(tep_not_null($url_product_image_name)){
											echo HTTP_SERVER.'/'.$url_product_image_name;
										}else{
											echo HTTP_SERVER.'/images/'.$new_image;
										}
								 ?>"
							})
						}
					});
					</script>

                </div>
            </div>
            <div id="ShareToFriendLink" class="shareEmail"><a onclick="showPopup('PopupShareEmail','PopupShareEmailCon');" href="javascript:;"><?= db_to_html("Email推荐给好友");?></a></div>
            <div class="shareFollow">
                <?= db_to_html("跟随我们");?>
                <?php
				if(strtolower(CHARSET)=="big5"){
				?>
				<a href="http://www.facebook.com/pages/%E9%80%94%E9%A3%8E%E7%BD%91/10150121158485529" target="_blank" class="facebook"></a><a href="http://twitter.com/usitrip" class="twitter" target="_blank" ></a><a href="http://t.sina.com.cn/usitrip" class="sina" target="_blank"></a><a href="http://www.renren.com/profile.do?id=310329422" class="renren" target="_blank" ></a><a href="http://www.kaixin001.com/home/?uid=82033854" class="kaixin001" target="_blank" ></a>
				<?php
				}else{
				?>
				<a class="sina" target="_blank" href="http://t.sina.com.cn/usitrip"></a><a class="renren" target="_blank" href="http://www.renren.com/profile.do?id=310329422"></a><a class="kaixin001" target="_blank" href="http://www.kaixin001.com/home/?uid=82033854"></a><a class="facebook" target="_blank" href="http://www.facebook.com/pages/%E9%80%94%E9%A3%8E%E7%BD%91/10150121158485529"></a><a class="twitter" target="_blank" href="http://twitter.com/usitrip"></a>
				<?php
				}
				?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    <!--
    function ShareEmailFormCallback(result, form) {
        window.status = "valiation callback for form '" + form.id + "': result = " + result;
		if(result==true){
			//发送电子邮件给朋友
			var url = url_ssl("ajax_share_to_friend.php?action=ShareEmailToFriend");
			ajax_post_submit(url,form.id);
		}
		return false;
    }
	
	jQuery().ready(function() {
		var ShareEmailFormValid = new Validation('ShareEmailForm', {immediate : true,useTitles:true, onFormValidate : ShareEmailFormCallback});
	});
	
	function SendEmaiSuccessAction(){
		Num = 6;
		TimeObj = document.getElementById('emailConSuccessTime');
		if(TimeObj==null){
			alert("No id=emailConSuccessTime"); return false; 
		}else if(TimeObj!=null && TimeObj.innerHTML!=""){
			Num = TimeObj.innerHTML;
		}
		
		if(Num <= 1 ){
			jQuery("#emailCon").show();
			jQuery("#emailBtnCenter").show();
			jQuery("#emailConSuccess").hide();
			closePopup("PopupShareEmail");
			TimeObj.innerHTML = 6;
		}else{
			TimeObj.innerHTML = (Num-1);
			window.setTimeout("SendEmaiSuccessAction()",1000);
		}
	}
    //-->
</script>
<?php
	*/
}
?>