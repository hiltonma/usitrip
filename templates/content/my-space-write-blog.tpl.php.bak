<?php
if ($messageStack->size('write_blog') > 0) {
	echo '<div id="messageStack" class="bg3_right_title_l">'. $messageStack->output('write_blog').'</div>';
}
?>

<script type="text/javascript"><!--
function check_blog(form_name){
	var error = false;
	var form_name = form_name;
	if(form_name.elements['blog_title'].value.length<1){
		error = true;
		alert('<?php echo db_to_html('标题 不能少於1个字')?>');
	}
	
	if(error == true){
		return false;
	}else{
		return true;
	}
}

function SaveDraft(){
	if(check_blog(document.getElementById("blog_form"))){
		document.getElementById("blog_draft").value ='1';
		document.getElementById("blog_form").submit();
	}
}
--></script>

<form action="" method="post" enctype="multipart/form-data"  id="blog_form" name="blog_form" onSubmit="return check_blog(blog_form);">


<div class="geren_content_rizhi">   
 
          <div class="rizhi_content">
          
          <div class="fenlei_geren"><div class="rizhi_head"> <div class="geren_content_nav2"> 
          <span class="dazi cu">编辑日志</span>
 </div>
          </div>
          <div class="fenlei_geren_center">
<div class="rizhi_timu4"><span class="cu">标题
</span><br />
<p>
   <?php
   echo tep_draw_input_field('blog_title','','class="textarea5"');
   ?>
</p>
<p>
  <?php echo tep_draw_textarea_field('blog_description', 'virtual','50','10') ?>
</p>
  <P>
      <label class="geren_content_tit">选择照片</label>
     
     <input name="blog_image_file" type="file" class="input_5" id="blog_image_file" />
  </p>
  
  <?php 
	if(tep_not_null($blog_image)){
		echo '<p><img src="images/blog/'.$blog_image.'" class="bg3_right_title_r3" '.getimgHW3hw(DIR_BLOG_FS_IMAGES.$blog_image,85,64).' /></p>';
	}
	?>
  
  <P>
      <label class="geren_content_tit">隐私设置</label>
 <?php
$privacy_sql = tep_db_query('SELECT * FROM `privacy_settings` ORDER BY `privacy_settings_id` DESC ');
$option_array=array();
while($privacy_rows = tep_db_fetch_array($privacy_sql)){
	$option_array[]=array('id'=>$privacy_rows['privacy_settings_id'], 'text'=>db_to_html($privacy_rows['privacy_settings_text']));
}

echo tep_draw_pull_down_menu('blog_privacy_settings', $option_array, '', 'class="textarea3"');
?>     
</p>
 <div class="rizhi_new_anniu"><table border="0" cellspacing="0" cellpadding="0">
  <tr>
   <td>
<?php echo tep_template_image_submit('rizhi_fabiao.gif', 'Save'); ?>
<input name="blog_draft" type="hidden" id="blog_draft" value="0" />
<input name="blog_id" type="hidden" id="blog_id" value="<?php echo (int)$blog_id?>" />
<input name="blog_image" type="hidden" id="blog_image" value="<?php echo $blog_image?>" />
<input name="action" type="hidden" id="action" value="<?php echo $action?>" /></td>  
   <td>&nbsp;</td>
   <td valign="bottom" nowrap="nowrap">
	 <a href="javascript:SaveDraft();"><?php echo db_to_html('保存为草稿')?></a>
	 </td>
   <td>&nbsp;</td>
   <td valign="bottom" nowrap="nowrap"><a href="<?php echo tep_href_link('my-space-logs.php') ?>"><?php echo db_to_html('取消')?></a></td>
 </tr>
 </table>
 </div>
 <div class="clear"></div>
</div>
 </div>
          </div>
          </div></div>
</form>