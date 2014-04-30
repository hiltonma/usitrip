<script type="text/javascript">
<?php
$p=array('/&amp;/','/&quot;/');
$r=array('&','"');
?>
function AddNewPhotoBooks(){
	
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('add_edit_del_photo_books_ajax.php','action=AddPhotoBooks')) ?>");
	var form = document.getElementById("photo_form");
	var aparams=new Array();  
	for(i=0; i<form.length; i++){
		var sparam=encodeURIComponent(form.elements[i].name); 
		sparam+="=";
		sparam+=encodeURIComponent(form.elements[i].value);
		aparams.push(sparam);
	}
	var sparam1='action=AddPhotoBooks';
	aparams.push(sparam1);
	
	var post_str=aparams.join("&");
	
	ajax.open("POST", url, true); 
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
	ajax.send(post_str);
	
	ajax.onreadystatechange = function() { 
		if (ajax.readyState == 4 && ajax.status == 200 ) { 
			if(ajax.responseText.search(/\[0\]/)!=-1){
				alert("<?php echo db_to_html('请填写相册名！') ?>");
			}
			if(ajax.responseText.search(/\[2\]/)!=-1){
				alert("<?php echo db_to_html('相册重名！') ?>");
			}
			if(ajax.responseText.search(/\[ID\]/)!=-1){
				closeDiv('popDiv');
				document.getElementById("photo_books_id").style.display = '';
				var id_regxp = /(.*\[ID\])|(\[\/ID\].*[:space:]*.*)/g;
				var name_regxp = /(.*\[NAME\])|(\[\/NAME\].*[:space:]*.*)/g;
				var id = ajax.responseText.replace(id_regxp,'');
				var name = ajax.responseText.replace(name_regxp,'');
				var item;
				item = new Option(name,id);
				document.getElementById("photo_books_id").options.add(item);
				document.getElementById("photo_books_id").value = id;
				
				//alert("<?php echo db_to_html('相册添加成功！') ?>");
				
			}
			
		}
	}
}

function SubmitPhoto(){
	var fromf=document.getElementById("photo_form");
	var error = false;
	var error_msn = "";
	if(fromf.elements["photo_books_id"].value<1){
		error = true;
		error_msn += "<?php echo db_to_html('请选择或创建一个相册');?>"+"\n";
	}
	if(error == true){
		alert(error_msn);
		return false;
	}else{
		document.getElementById("submitbutton").disabled = true;
		document.getElementById("photo_form").submit();
	}
}

</script>

<?php
if ($messageStack->size('Photo') > 0) {
	echo '<div id="messageStack" class="bg3_right_title_l">'. $messageStack->output('Photo').'</div>';
}
?>

<div class="geren_content_rizhi">   
<form action="" method="post" enctype="multipart/form-data" name="photo_form" id="photo_form" onSubmit="SubmitPhoto(); return false;">
<input name="action" type="hidden" value="add_confirmation" />
 <div class="rizhi_head"><p class="nav_kongjian">上传相片</p></div>
          <div class="rizhi_content">
          <div><div class="fenlei_geren">
<div class="photo_cj">
 <p>
     <label class="geren_content_tit">选择相册</label>
<?php
$photo_books_array = get_user_photo_books_list($customer_id);
$option_array = array();
$option_array[0] = array('id'=>0,'text'=>db_to_html('--选择--'));
for($i=0; $i<count($photo_books_array); $i++){
	$option_array[]=array('id'=>$photo_books_array[$i]['id'], 'text'=>db_to_html($photo_books_array[$i]['name']));
}
echo tep_draw_pull_down_menu('photo_books_id', $option_array, '', 'id="photo_books_id" class="textarea3"');
?>	 
	 
    <label class="geren_content_tit3"><a href="javascript:showDiv('popDiv');" onclick="document.getElementById('photo_books_id').style.display = 'none';" class="cu">创建新相册</a></label>
 </p>
 <div class="xin_photo">
 <p>选择照片 <span class="huise "> （一次最多上传4张）</span></p>
 <p><input name="file[0]" type="file" class="input_5" id="file[0]" />
 </p>
 <p><input name="file[1]" type="file" class="input_5" id="file[1]" />
 </p>
 <p><input name="file[2]" type="file" class="input_5" id="file[2]" />
 </p>
 <p><input name="file[3]" type="file" class="input_5" id="file[3]" />
 </p></div>
      

      <div class="geren_content_11"><div class="bg3_right_title_l"><?php echo tep_template_image_submit('update_photo.gif', 'Save', 'id="submitbutton"'); ?></div>
      <p class="huise"><?php echo db_to_html('上传淫秽，色情，反动图片。有可能导致帐号删除；请确保您上传的相片jpg,jpeg或gif格式的相片！每张相片大小建议不要超过120K！')?></p>
      </div>
 
<!--pop start-->
      <div id="popDiv" class="center_pop" style="display:<?php echo 'none' ?>;" >
      <?php echo tep_pop_div_add_table('top');?>
	  <div class="geren_content_pop2"><div class="bg3_right_title_r"><a  href="javascript:closeDiv('popDiv')" onclick="document.getElementById('photo_books_id').style.display = '';" class="huise_di"><?php echo db_to_html('关闭')?></a></div></div>
      <p>
     <div class="geren_content_tit_pop"><?php echo db_to_html('相册名')?></div>
	 <?php echo tep_draw_input_field('photo_books_name','','class="textarea2_pop"'); ?>
     </p>  
     <P>
      <div class="geren_content_tit_pop" ><?php echo db_to_html('描述')?></div>
	 <?php echo tep_draw_textarea_field('photo_books_description', 'virtual','45','5','', 'class="textarea_pop"') ?>
     </p>
     <P>
      <div class="geren_content_tit_pop"><?php echo db_to_html('隐私设置')?></div>
    
<?php
$privacy_sql = tep_db_query('SELECT * FROM `privacy_settings` ORDER BY `privacy_settings_id` DESC ');
$option_array=array();
while($privacy_rows = tep_db_fetch_array($privacy_sql)){
	$option_array[]=array('id'=>$privacy_rows['privacy_settings_id'], 'text'=>db_to_html($privacy_rows['privacy_settings_text']));
}
echo tep_draw_pull_down_menu('photo_books_privacy_settings', $option_array, '', 'class="textarea3_pop"');
?>      
        </p>
      <div class="geren_content_pop">

	  <div class="bg3_right_title_l">
	  	  <?php echo tep_template_image_button('chuangjian_photo.gif', 'Save' , ' style="cursor: pointer;" onClick="AddNewPhotoBooks();"'); ?>
	  </div>
      </div>
	  <?php echo tep_pop_div_add_table('foot');?>
     </div>
<!--pop end-->

      </div>

          </div>
          </div>
          </div>
</form>
</div>

