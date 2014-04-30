<?php
/* 邮件发送模块，主要是针对订单咨询、市场合作、佣金咨询*/
$error = false;
if (isset($_GET['action']) && ($_GET['action'] == 'send') && $_POST['ajax']=="true") {
	header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
	header( "Cache-Control: no-cache, must-revalidate" );
	header( "Pragma: no-cache" );
	require_once('includes/application_top.php');
	header("Content-type: text/html; charset=".CHARSET);

	if (tep_validate_email(trim($_POST['email']))) {
        $to_email_address = $_POST['to_email_address'];
		//$to_email_address = 'marketing@usitrip.com';
        //$to_email_address .= ', howard.zhou@usitrip.com';
        //$to_email_address = 'howard.zhou@usitrip.com';

        $mail_text = ajax_to_general_string($_POST['enquiry']);
        $mail_text .= "\n\n" . '---------------------------------------------------------' . "\n" . db_to_html('发件人邮箱：') . $_POST['email'];
        $mail_text .= "\n" . db_to_html('发送源位置：') . base64_decode($_POST['url']) . "\n";
        define('EMAIL_SUBJECT',db_to_html('顾客问题咨询！'.time()));
		tep_mail(STORE_OWNER, $to_email_address, EMAIL_SUBJECT, $mail_text, ajax_to_general_string($_POST['name']), 'automail@usitrip.com');
        //tep_redirect(tep_href_link(FILENAME_AFFILIATE_CONTACT, 'action=success'));
		$js_str = 'alert("邮件发送成功！");';
		$js_str .= 'jQuery("#SendSubmitBotton").attr("disabled",false); jQuery("#SendSubmitBotton").html("提 交");';
		$js_str .= 'jQuery("#email").val(""); jQuery("#enquiry").val("");';
		
		$js_str = '[JS]'.preg_replace('/[[:space:]]+/',' ',$js_str).'[/JS]';
		echo db_to_html($js_str);
    } else {
        $error = true;
    }
	exit;
}

$name = db_to_html($affiliate['affiliate_firstname'] . ' ' . $affiliate['affiliate_lastname']);
$email = db_to_html($affiliate['affiliate_email_address']);


/*- 设定默认的咨询类型 -*/
$first_p = "e_marketing";
if($content=="affiliate_contact"){
	$first_p = "e_affiliate";
}
if($content=="orders_ask"){
	$first_p = "e_service";
}
?>

		<?php echo tep_draw_form('contact_us', tep_href_link(FILENAME_AFFILIATE_CONTACT, 'action=send'), 'post', ' id="contact_us" onsubmit="return false;" '); ?>
		
		<ul class="myToursAsk">
			<li>
				<label><?php echo db_to_html("咨询的类型:")?></label>
				<div class="type">
					<p id="e_marketing"><input type="radio" value="marketing@usitrip.com" name="to_email_address" checked="checked" onclick="showType('span_a',this);" /><?php echo db_to_html("市场合作")?><span id="span_a" class="disTrue"><b>*</b><?php echo db_to_html("关于市场推广合作的疑问请点选此项，您也可以直接发邮件至")?> <a href="mailto:marketing@usitrip.com">marketing@usitrip.com</a></span></p>
					<p id="e_service"><input type="radio" value="service@usitrip.com" name="to_email_address" onclick="showType('span_b',this);"/><?php echo db_to_html("订单及行程咨询")?><span id="span_b"><b>*</b><?php echo db_to_html("关于已订订单及行程咨询等问题请点选此项，您也可以直接发邮件至")?> <a href="mailto:service@usitrip.com">service@usitrip.com</a></span></p>
					<p id="e_affiliate"><input type="radio" value="affiliate@usitrip.com" name="to_email_address" onclick="showType('span_c',this);"/><?php echo db_to_html("佣金咨询")?><span id="span_c"><b>*</b><?php echo db_to_html("关于推荐给朋友及拥金支付的咨询请点选此项，您也可以直接发邮件至")?> <a href="mailto:affiliate@usitrip.com">affiliate@usitrip.com</a></span></p>
				</div>
			</li>
			<li>
				<label><?php echo db_to_html("您的姓名:")?></label>
				<?php echo tep_draw_input_field('name', $name, 'size="20" class="text"'); ?>
			</li>
			<li>
				<label><?php echo db_to_html("您的E-mail:")?></label>
				<?php echo tep_draw_input_field('email', $email, 'id="email" class="required validate-email text" title="'.ENTRY_EMAIL_ADDRESS_CHECK_ERROR.'" size="40"'); if ($error) echo ENTRY_EMAIL_ADDRESS_CHECK_ERROR; ?>
			</li>
			<li>
				<label><?php echo db_to_html("您的问题:")?></label>
				<?php echo tep_draw_textarea_field('enquiry', 'soft', 100, 8, '', 'id="enquiry" class="required textarea" title="'.db_to_html('请输入您的问题').'"'); ?>
			</li>
			<li style="text-align:center">
				<input name="url" type="hidden" value="<?php echo base64_encode($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])?>"><button class="btn" type="submit" id="SendSubmitBotton" ><?php echo db_to_html("提 交")?></button>
			</li>
		</ul>

		</form>
		<div class="contact" id="OtherContactForOrdersAsk">
			<h4><?php echo db_to_html("您也可以通过以下方式联系我们：")?></h4>
			<ul>
			<?php
			$contact_phones = tep_get_us_contact_phone();
			ob_start();
			foreach($contact_phones as $key => $val){
			?>
			<li class="<?php echo $val['class']?>"><i></i><?php echo $val['name']?><em class="font_size14 color_blue font_bold"><?php echo $val['phone']?></em></li>
		<?php }?>
			<li class="s_5"><i></i>电子邮箱：<em class="font_size14 color_blue font_bold"><?php echo STORE_OWNER_EMAIL_ADDRESS;?></em></li>
			<?php
			echo db_to_html(ob_get_clean());
			?>
			</ul>
		</div>
		
		<?php
		$p=array('/&amp;/','/&quot;/');
		$r=array('&','"');
		?>
		<script type="text/javascript">
		function set_first_type_p(p_id){
			var p = jQuery(".type").find("p");
			if(p_id!=p[0].id){
				jQuery("#"+p_id).insertBefore("#"+p[0].id);
				jQuery("#"+p_id).find("input").click();
			}
		}
		set_first_type_p('<?= $first_p;?>');
		
		function showType(n,clickObj){
			jQuery(".type").find("span").hide();
			jQuery("#" + n).show();
			jQuery("#OtherContactForOrdersAsk").hide();
			if(clickObj.value=="service@usitrip.com"){
				jQuery("#OtherContactForOrdersAsk").show();
			}
		} 
		function formCallback(result, form) {
			window.status = "valiation callback for form '" + form.id + "': result = " + result;
			if(result==true){
				jQuery("#SendSubmitBotton").attr("disabled","disabled");
				jQuery("#SendSubmitBotton").html("<img src='image/loading_16x16.gif' />");
				var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_ask_module.php','action=send')) ?>");
				var form_id = "contact_us";
				ajax_post_submit(url,form_id);
			}
		}
		var valid = new Validation('contact_us', {immediate : true,useTitles:true, onFormValidate : formCallback});
		
		</script>
