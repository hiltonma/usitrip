<?php
if ($messageStack->size('user_edit') > 0) {
	echo '<div id="messageStack" class="bg3_right_title_l">'. $messageStack->output('user_edit').'</div>';
}
?>

<form action="" method="post" enctype="multipart/form-data" name="user_form">
<div class="geren_content"><div class="geren_xinxi"><div class="geren_xinxi_tu">
<?php
$face_src = 'image/geren_touxiang_da.gif';
if(tep_not_null($user_face)){
	$face_src = 'images/face/'.$user_face;
}?>
<img alt="My face" width="155" height="155" src="<?php echo $face_src ?>" />
</div>

 <div class="geren_xinxi_jiben"><span class="nav_kongjian"><?php echo db_to_html('我的昵称：').$user_nickname ?></span>
 <p><?php echo db_to_html('积分：').$my_score_sum ?></p>
 <p><a href="#" class="lanzi4">积分的好处</a>  |  <a href="#" class="lanzi4">如何获取更多积分?</a></p>
 <p><span class="cu">换头像</span> <input name="face_file" type="file"  class="input_search4" id="face_file"/>
 </p>
 <span class="huise margen_l">大小：155px*155px</span></div>
 <div class="clear"></div></div>
<div class="geren_xinxi_qita">
  <div class="geren_xinxi_jiben2">
  <h3 >个人信息</h3>
 <p><label class="geren_content_tit"><?php echo db_to_html('昵称：')?></label>
   <?php
   echo tep_draw_input_field('user_nickname','','class="input_search3"');
   ?>
 </p>
 <p><label class="geren_content_tit"><?php echo ENTRY_GENDER; ?></label>
   <?php
	if($user_gender=='m'){
		$male = $user_gender;
	}elseif($user_gender=='f'){
		$female = $user_gender;
	}
	echo tep_draw_radio_field('user_gender', 'm', $male) . '&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('user_gender', 'f', $female) . '&nbsp;' . FEMALE . '&nbsp;' ;
   ?>
 </p>
 <label class="geren_content_tit2"><?php echo db_to_html('旅游爱好')?></label>
 <div class="geren_xinxi_aihao">
 
 <?php
 //取得所有的旅游爱好 3列
 $tours_loving_sql = tep_db_query('SELECT * FROM `tours_loving` ');
 $tours_loving_rows = tep_db_fetch_array($tours_loving_sql);
 ?>
 
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
<?php
function get_loving_to_user($tours_loving_id, $customer_id){
	if(!(int)$tours_loving_id){ return false;}
	$sql = tep_db_query('SELECT * FROM `tours_loving_to_user` where tours_loving_id="'.(int)$tours_loving_id.'" AND customers_id="'.(int)$customer_id.'" ');
	$row = tep_db_fetch_array($sql);
	return $row['tours_loving_id'];
}

$J=0;
 do{
 	if($J>0 && $J%3==0){
		echo "</tr><tr>";
	}
$checked_loving = '';	

if(get_loving_to_user($tours_loving_rows['tours_loving_id'],$customer_id)){
	$checked_loving = 'checked="checked"';
}
?>
    <td width="10%" height="25" align="center"><input name="tours_loving[<?= $J ?>]" type="checkbox" id="tours_loving[<?= $J ?>]" value="<?= $tours_loving_rows['tours_loving_id'] ?>" <?= $checked_loving?> /></td>
    <td><label for="tours_loving[<?= $J ?>]"><?php echo db_to_html($tours_loving_rows['tours_loving_name']); ?></label></td>
<?php
 $J++;
 }while($tours_loving_rows = tep_db_fetch_array($tours_loving_sql));
?> 
	
  </tr>
  <tr>
    <td height="25" colspan="6" align="left">&nbsp;&nbsp;<span class="huise"><?php echo db_to_html('可以多选')?></span></td>
    </tr>
</table> 
 
</div>
<p><label class="geren_content_tit"><?php echo db_to_html('描述')?></label>

 <?php echo tep_draw_textarea_field('user_description', 'virtual','20','20','', 'class="textarea4"') ?>
</p>  
<p><label class="geren_content_tit"><?php echo db_to_html('隐私设置')?></label>
<?php
$privacy_sql = tep_db_query('SELECT * FROM `privacy_settings` ORDER BY `privacy_settings_id` DESC ');
$option_array=array();
while($privacy_rows = tep_db_fetch_array($privacy_sql)){
	$option_array[]=array('id'=>$privacy_rows['privacy_settings_id'], 'text'=>db_to_html($privacy_rows['privacy_settings_text']));
}
echo tep_draw_pull_down_menu('user_privacy_settings', $option_array, '', 'class="input_search4"');
?>
</p> 
<p>

<?php echo tep_template_image_submit('save_11.gif', 'Save'); ?>
<input name="action" type="hidden" id="action" value="user_edit" />
</p> 

</div><div class="clear"></div></div></div>
</form>