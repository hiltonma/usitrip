<?php
//快速登录账号小模块
require_once('includes/application_top.php');
$p=array('/&amp;/','/&quot;/');
$r=array('&','"');

if($_POST['email_address']!="" && $_POST['password']!="" && $_POST['ajax']=='true'){
	header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
	header( "Cache-Control: no-cache, must-revalidate" );
	header( "Pragma: no-cache" );
	
	require_once('includes/application_top.php');
	require_once(DIR_FS_INCLUDES . 'ajax_encoding_control.php');
	include('login.php');
	//echo '[SUCCESS]1[/SUCCESS]';
	
	//ajax_shenqin_table.php
        
	if($_POST['next_file']=="top_window"){	//不打开新文件，直接在原窗口进行下一步操作
		$js_str = '[JS]closePopup("travel_companion_tips_2065");var form_obj=document.getElementById("CompanionFormReply");
		form_obj.elements["t_c_reply_content"].setAttribute("onfocus","");
		var Re_Submit_Button = document.getElementById("re_submit_button");
		if(Re_Submit_Button!=null){ Re_Submit_Button.setAttribute("onclick","");}
        var sms_form_obj=document.getElementById("site_inner_sms_form");
        if(sms_form_obj!=null){sms_form_obj.elements["sms_content"].setAttribute("onfocus","");}
		[/JS]';
        $js_str = preg_replace('/[[:space:]]+/',' ',$js_str);
		echo $js_str;
        exit;
	}else if($_POST['next_file']=="top_window_photo_list"){
		$js_str = '[JS]closePopup("travel_companion_tips_2066");
		var form_obj=document.getElementById("bbs_form");
		form_obj.elements["tcomments"].setAttribute("onfocus","");
		var Re_Submit_Button = document.getElementById("rew_submit_button");
		if(Re_Submit_Button!=null){ Re_Submit_Button.setAttribute("onclick","");}
		[/JS]';
		$js_str = preg_replace('/[[:space:]]+/',' ',$js_str);
		echo $js_str;
		exit;
	}else if($_POST['next_file']=="_Ajax_FastLogin"){
		$js_str = '[JS]closePopup("_Ajax_FastLogin");
		isLogin=true;
		[/JS]';
		$js_str = preg_replace('/[[:space:]]+/',' ',$js_str);
		echo $js_str;
		exit;
	}else{
		$popupConCompareId = "CreateNewCompanionCon";
		$width = "528";
		if($_POST['next_file']=="travel_companion_tpl_tpl.php"){
			$popupConCompareId = "CreateNewCompanionCon";
			$width = "528";
		}
		$width = "100%"; //by lwkai add
		echo tep_pop_div_add_table_now('top',$popupConCompareId,$width);
		include($_POST['next_file']);
		echo tep_pop_div_add_table_now('foot');
	}
	exit;
        
}
//$replace_id = 'travel_companion_tips_2064';
//$next_file = 'ajax_shenqin_table.php';
if(!tep_not_null($replace_id)){
	die('not $replace_id ');
}
if(!tep_not_null($next_file)){
	die('not $next_file ');
}

?>

<div>
<?php echo tep_draw_form('login_'.$replace_id, tep_href_link(FILENAME_LOGIN, 'action=process', 'SSL'),'post','id="login_'.$replace_id.'"  onSubmit="submit_and_login_'.$replace_id.'(this); return false;" '); ?>
<input name="next_file" type="hidden" value="<?= $next_file;?>" />
<?php
//载入登录成功后的所需字段
if(!(int)$categories_id){
	$categories_id = (int)$categories;
}
if(!(int)$categories_id){
	$categories_id = (int)$cPathOnly;
}
//结伴同游BBS中的发帖
if (isset($TcPath_array) && count($TcPath_array) ) {
	$categories_id = $TcPath_array[count($TcPath_array)-1];
}

if(tep_not_null($categories_id)){
	echo tep_draw_hidden_field('categories_id');
}
if(tep_not_null($products_id)){
	echo tep_draw_hidden_field('products_id');
}
?>
<div class="jb_fb_tc_bt">
       <h3><?php echo db_to_html('快速登录')?></h3>
	   <button type="button" title="<?php echo db_to_html('关闭');?>" onclick="javascript:closePopup(&quot;<?= $replace_id?>&quot;)" class="icon_fb_bt"/></button>
</div>
<div class="jb_fb_tc_tab">
<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;clear:both;" width="100%" >

	  <tr>
		<td class="main" style="padding-left:10px;">

	<table border="0" cellspacing="0" cellpadding="0" align="left">
	  <tr>
		<td align="right" class="create_rows" height="30"><?php echo ENTRY_EMAIL_ADDRESS ?></td>
		<td><?php echo tep_draw_input_field('email_address','',' class="sign_in_box text"') ?></td>
								
	  </tr>
	  <tr>
		<td align="right" class="create_rows" height="30"><?php echo ENTRY_PASSWORD ?></td>
		<td><?php echo tep_draw_password_field('password','',' class="sign_in_box text"')?></td>
	  </tr>
	  <tr>
		<td class="main" height="30">&nbsp;</td>
		<td><span class="smalltext"><?php echo tep_image_submit('button_login.gif', IMAGE_BUTTON_LOGIN) ?></span></td>
		</tr>
	  <tr>
		<td class="main" height="30">&nbsp;</td>
		<td><span class="smalltext"><?php echo '<a href="' . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . '" style="text-decoration:underline;">' . db_to_html('忘记密码？') . '</a>' ?></span> &nbsp;&nbsp;<span class="smalltext"><?php echo db_to_html('新用户请 <a href="'.tep_href_link("create_account.php","", "SSL").'" class="sp3">注册</a>');?></span></td>
		</tr>
	</table>
		</td>
	  </tr>
</table>
</div>
<input name="ajax" type="hidden" value="true" />
</form>
</div>


<script type="text/javascript">
function submit_and_login_<?=$replace_id?>(obj){
	var form = obj;
	var error_msn = '';
	var error = false;
	if(form.elements['email_address'].value.lenth<1 || form.elements['email_address'].value == form.elements['email_address'].title){
		error = true;
		error_msn += "<?php echo db_to_html('请输入您的登录账号。')?>" + "\n\n";
	}
	if(form.elements['password'].value.lenth<1 || form.elements['password'].value == form.elements['password'].title){
		error = true;
		error_msn += "<?php echo db_to_html('请输入您的密码。')?>" + "\n\n";
	}
	if(error == true){
		alert(error_msn);
		return false;
	}
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_fast_login.php','action=process')) ?>");
	var form_id = form.id;
	var success_msm = "";
	var success_go_to = "";
	var replace_id = "<?= $replace_id?>";
	ajax_post_submit(url,form_id,success_msm,success_go_to, replace_id);
}
</script>