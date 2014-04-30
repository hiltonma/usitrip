<?php
$table_bg_color = '#C0E1F6';
if($validation_result==true){
	$table_bg_color = '#ffffff';
}
?>
<table border="0" cellpadding="0" cellspacing="0" bgcolor="<?=$table_bg_color?>" style="width:740px;margin:10px auto;background:#FFFFFF;">

<?php
//$validation_result = false;
//$messageStack->add('validation',db_to_html('请输入验证码！'));
if($validation_result==false){

  if ($messageStack->size('validation') > 0) {
?>
	  
 <tr>
    <td height="30" style="width:290px;text-align:right;">
		<img src="image/notice.jpg" style="float:right;vertical-align:top;" alt="<?php echo db_to_html("操作失败!")?>">
	</td>
    <td valign="middle" style="width:450px;">
		<h2 id="valtxt" style="font-family:'Microsoft Yahei';"><?php echo strip_tags($messageStack->output('validation')); ?></h2>
        <script type="text/javascript">
			var dom = document.getElementById("valtxt");
			var domtxt = dom.innerHTML;
			if(domtxt.indexOf('，') != -1){
				var s1 = domtxt.split('，')[0];
				var s2 = domtxt.split('，')[1];
				var domstyle = '';
				domstyle += '<span style="font-size:18px;color:#FF6600;">' + s1 + '</span><br />';
				domstyle += '<span style="font-size:14px;color:#1962ca;">' + s2 + '</span>';
				dom.innerHTML = domstyle;
			}
		</script>
	</td>
  </tr>
	  <tr>
		<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
	  </tr>
<?php
  }
?>
  <tr>
    <td colspan="2"  height="30" style="padding:40px 90px 10px 90px;border-left:1px solid #ffe0ab;border-top:1px solid #ffe0ab;border-right:1px solid #ffe0ab;background:#fffeed;">
	<p style="margin:0;padding:0;line-height:24px;font-size:12px;"><?php echo db_to_html('您的账号目前还未激活，请登录您的邮箱 '.$customer_email_address.' 查收激活信，并及时激活账号。<br />将额外获得'.VALIDATION_ACCOUNT_POINT_AMOUNT.'走四方积分奖励！');?></p>
    </td>
  </tr>  
  <tr>
    <td colspan="2"  height="30" valign="top" nowrap style="padding:0 90px 40px 90px;font-size:14px;border-left:1px solid #ffe0ab;border-bottom:1px solid #ffe0ab;border-right:1px solid #ffe0ab;background:#fffeed;">
    <form action="customers_validation.php" method="post" name="resend" id="resend"><?php echo tep_template_image_submit('again_email.gif', 'Send Validation Mail','style="vertical-align:middle; "');  ?>
        <input name="action" type="hidden" id="action" value="resend" >
    </form>
    <form action="customers_validation.php" method="post" name="inputcode" id="inputcode">
	<span style="color:#7f7f79;font-size:12px;padding-right:15px;"><?php echo db_to_html('或输入您的验证码进行激活！');?></span>
    <?php echo tep_draw_input_field('customers_validation_code','',' style="margin-top:2px;border:1px solid #cdd2d6;background:#FFFFFF;padding:1px 5px;font-size:14px;font-family:tahoma;" size="18" maxlength="10" ');?>	
    
    <?php echo tep_template_image_submit('queren_jihuo.gif', 'Enter Validation Code','style="vertical-align:middle; "'); ?><input name="action" type="hidden" id="action" value="inputcode"></td>
  </tr>
  </form>
<?php
}elseif($validation_result==true){
?>
  <tr>
    <td height="30" valign="top" nowrap style="width:230px;padding:20px 10px 15px 100px;text-align:right;">
	<img src="<?= DIR_WS_TEMPLATE_IMAGES;?>success.jpg" alt="OK">
	</td>
    <td valign="middle" style="width:510px;font-size:14px;">
	<h2><?php echo db_to_html('恭喜，您的账号已成功通过验证！');?></h2>
	<?php echo '<a class="btn btnOrange" href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '" style="margin-top:8px;text-align:center;color:#fff">' .db_to_html("继 续") . '</a>'; ?>
	</td>
  </tr>
<?php
}
?>
</table>
