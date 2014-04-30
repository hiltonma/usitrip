<?php
if ($messageStack->size('Photo') > 0) {
	echo '<div id="messageStack" class="bg3_right_title_l">'. $messageStack->output('Photo').'</div>';
}
?>

<form action="" method="post" enctype="multipart/form-data" name="photo_form">
<div class="geren_content_rizhi">   
 <div class="rizhi_head"><p class="nav_kongjian">创建相册</p></div>
          <div class="rizhi_content">
          <div><div class="fenlei_geren">
<div class="photo_cj">
  <p>
     <label class="geren_content_tit">相册名</label>
	 <?php echo tep_draw_input_field('photo_books_name','','class="textarea2"'); ?>
     </p>
     <P>
      <label class="geren_content_tit"><?php echo db_to_html('描述')?></label>
     <?php echo tep_draw_textarea_field('photo_books_description', 'virtual','45','5','', 'class="textarea"') ?>
	 </p>
      <P>
      <label class="geren_content_tit">隐私设置</label>
     </p>

<?php
$privacy_sql = tep_db_query('SELECT * FROM `privacy_settings` ORDER BY `privacy_settings_id` DESC ');
$option_array=array();
while($privacy_rows = tep_db_fetch_array($privacy_sql)){
	$option_array[]=array('id'=>$privacy_rows['privacy_settings_id'], 'text'=>db_to_html($privacy_rows['privacy_settings_text']));
}
echo tep_draw_pull_down_menu('photo_books_privacy_settings', $option_array, '', 'class="textarea3"');
?>      
      <div class="geren_content_11"><div class="bg3_right_title_l">
	  <?php echo tep_template_image_submit('chuangjian_photo.gif', 'Save'); ?>
        <input name="action" type="hidden" id="action" value="AddPhotoBooks">
      </div>
      <div class="bg3_right_title_l margen_l"><a href="<?php echo tep_href_link('my-space-photos.php')?>"><?php echo db_to_html('取消')?></a></div>
      </div>
      </div>

          </div>
          </div>
          </div></div>
</form>