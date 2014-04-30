<?php
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );
require_once('includes/application_top.php');
require_once(DIR_FS_INCLUDES . 'ajax_encoding_control.php');

if($_POST['ajax']=='true'){
	
	//检测用户
	if(!tep_session_is_registered('customer_id') || !(int)$customer_id){
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
	
	//添加\编辑相册 start
	if($_GET['action']=='process' && $error == false){
		$error_sms = "";	
		$photo_books_name = tep_db_prepare_input($_POST['photo_books_name']);
		if(strlen($photo_books_name)<2){
			$error_sms .= db_to_html('请输入相册名称。\n\n');
			$error = true;
		}
		$photo_books_description = tep_db_prepare_input($_POST['photo_books_description']);
		if(strlen($photo_books_description)<2){
			$error_sms .= db_to_html('请输入相册描述。\n\n');
			$error = true;
		}
		if($error == true){
			echo db_to_html($error_sms);
			exit;
		}
		$add_date = date('Y-m-d H:i:s');
		
		$photo_books_name = iconv('utf-8',CHARSET.'//IGNORE',$photo_books_name);
		$photo_books_description = iconv('utf-8',CHARSET.'//IGNORE',$photo_books_description);
		$sql_data_array = array('customers_id' => (int)$customer_id ,
								'photo_books_name' => $photo_books_name,
								'photo_books_description' => $photo_books_description,
								'photo_books_date' => $add_date,
								'photo_books_privacy_settings' => '3');
	
		$sql_data_array = html_to_db($sql_data_array);
		if((int)$_POST['photo_books_id']){	//更新
			tep_db_perform('`photo_books`', $sql_data_array, 'update', ' photo_books_id="'.(int)$_POST['photo_books_id'].'" and customers_id="'.(int)$customer_id.'" ');
			echo '[SUCCESS]1[/SUCCESS]';
			
			
			$notes_content = '您的相册更新成功！';
			$out_time = 3; //延迟3秒关闭
			$tpl_content = file_get_contents(DIR_FS_CONTENT . 'html_tpl/'.'out_time_notes.tpl.html');
			$tpl_content = str_replace('{notes_content}',$notes_content,$tpl_content);
			$tpl_content = str_replace('{out_time}',$out_time,$tpl_content);
			
			$js_str = '
			var form = document.getElementById("create_album_form");
			var gotourl = "";
			var notes_contes = "'.addslashes($tpl_content).'";
			write_success_notes('.$out_time.', notes_contes, gotourl);
			
			var Photo_Books_Name = document.getElementById("photo_books_name_'.(int)$_POST['photo_books_id'].'");
			Photo_Books_Name.innerHTML = form.elements["photo_books_name"].value;
			var Photo_Books_Description = document.getElementById("photo_books_description_'.(int)$_POST['photo_books_id'].'");
			Photo_Books_Description.innerHTML = form.elements["photo_books_description"].value;
			closeDiv("cr_photo_books"); 
			';
			$js_str = '[JS]'.preg_replace('/[[:space:]]+/',' ',$js_str).'[/JS]';
			echo db_to_html($js_str);
			exit;
			
		}else{	//添加
			tep_db_perform('`photo_books`', $sql_data_array);
			$photo_books_id = tep_db_insert_id();
			echo '[SUCCESS]'.(int)$photo_books_id.'[/SUCCESS]';
			$books_cover = "image/photo_1.gif";
			$photo_list_href = tep_href_link('photo_list.php','photo_books_id='.$photo_books_id);
			$js_str =  
			'[JS]
			if(confirm("相册创建成功！现在去添加相片到此相册吗？")){
				location="'.tep_href_link('create_photos.php','photo_books_id='.$photo_books_id).'";
			}else{
				var Photo_Books_Ul = document.getElementById("photo_books_ul");
				if(Photo_Books_Ul!=null){
					var tmp_code = \'
					<li id="photo_li_'.$photo_books_id.'"> <a href="'.$photo_list_href.'" class="jb_photo_a">
            		<div class="jb_photo"><img src="'.$books_cover.'" /></div>
            		</a>
            		<p style="text-align:center" class="jb_photo_p col_5"><a href="'.$photo_list_href.'" id="photo_books_name_'.$photo_books_id.'">'.html_to_db(tep_db_output($photo_books_name)).'</a>
					<br />
              		<span>'.('共0张').'</span></p>
			  		<p id="photo_books_description_'.$photo_books_id.'" style="display:none">'.html_to_db(tep_db_output($photo_books_description)).'</p>
					<p style="text-align:center"><a href="JavaScript:void(0)" onclick="update_album('.$photo_books_id.');" class="jb_fb_tc_bt_a">'.('编辑').'</a>&nbsp;<a href="JavaScript:void(0)" class="jb_fb_tc_bt_a" onclick="remove_album('.$photo_books_id.',&quot;photo_li_'.$photo_books_id.'&quot;)">'.('删除').'</a></p>
          </li>
\';
					Photo_Books_Ul.innerHTML = tmp_code + Photo_Books_Ul.innerHTML;
				}
			}
			var form = document.getElementById("create_album_form");
			form.elements["photo_books_description"].value = "";
			closeDiv("cr_photo_books"); 
			[/JS]';
			
			$js_str = preg_replace('/[[:space:]]+/',' ',$js_str);
			echo db_to_html($js_str);
			exit;
		}
		
		
	}
	//添加\编辑相册 end
}

//删除相册 start
if($_GET['action']=='remove_album' && (int)$customer_id && tep_not_null($_GET['photo_books_id'])  && tep_not_null($_GET['dis_id']) && $error == false){
	/*删除没有与团绑定的相片*/
	$photo_sql = tep_db_query('SELECT photo_id, photo_name FROM `photo` WHERE `photo_books_id` = "'.(int)$_GET['photo_books_id'].'" and customers_id ="'.(int)$customer_id.'" and products_id < 1 ');
	while($photo = tep_db_fetch_array($photo_sql)){
		if(tep_not_null($photo['photo_name'])){
			@unlink(DIR_PHOTOS_FS_IMAGES.$photo['photo_name']);
		}
		tep_db_query('DELETE FROM `photo` WHERE `photo_id` = "'.$photo['photo_id'].'" ');
	}
	tep_db_query('DELETE FROM `photo_books` WHERE `photo_books_id` = "'.(int)$_GET['photo_books_id'].'" and customers_id ="'.(int)$customer_id.'" LIMIT 1');
	echo '[SUCCESS]1[/SUCCESS]';
	$js_str =  
	'[JS]
	var tmp_obj = document.getElementById("'.$_GET['dis_id'].'"); 
	if(tmp_obj!=null){
		var obj_id = "#"+tmp_obj.id;
		$(obj_id).fadeOut(1000);
		/*tmp_obj.parentNode.removeChild(tmp_obj);*/
	}
	[/JS]';
	
	$js_str = preg_replace('/[[:space:]]+/',' ',$js_str);
	echo db_to_html($js_str);
	exit;
}
//删除相册 end

?>

<!--弹出层相册 start-->
<div class="jb_fb_tcAddXx" id="cr_photo_books" style="text-decoration:none; display:none">
<?php echo tep_pop_div_add_table('top');?>
  <form method="post" enctype="multipart/form-data" id="create_album_form" onsubmit="create_update_album_confirm(this); return false;">
  <input name="photo_books_id" type="hidden" value="" />
  <input name="create_ro_update" type="hidden" value="create" />
  <div>
     <div class="jb_fb_tc_bt">
       <h3 id="action_h3"><?php echo db_to_html('创建新相册')?></h3>&nbsp;&nbsp;
	   <button type="button" title="<?php echo db_to_html('关闭');?>" onclick="closeDiv('cr_photo_books')" class="icon_fb_bt"/></button>
    </div>
     <div class="jb_fb_tc_tab">
      <table>
	  <tr>
	  <td><?= db_to_html("相册名称：")?>
	  </td>
	  <td><?= tep_draw_input_field('photo_books_name','',' maxlength="98" size="76" ');?>
	  </td>
	  <tr>
	  <tr>
	  <td><?= db_to_html("相册描述：")?>
	  </td>
	  <td><?= tep_draw_textarea_field('photo_books_description','',50,5);?>
	  </td>
	  <tr>
	  <td>&nbsp;
	  </td>
	  <td><button type="submit" class="jb_fb_all myjb_content_sq1_button"><?= db_to_html('确定')?></button>
	  </td>
	  
	  </tr>
	  </table> 
     </div>
</div>
</form>
<?php echo tep_pop_div_add_table('foot');?>
</div>
<script type="text/javascript">
//提交添加/编辑相册
function create_update_album_confirm(form_obj){
	var form = form_obj;
	if(form.elements['create_ro_update'].value == "create"){
		form.elements['photo_books_id'].value = "";
	}
	
	if(form.elements['photo_books_name'].value.length<2){
		alert('<?= db_to_html("请输入相册名称！");?>');
		return false;
	}
	if(form.elements['photo_books_description'].value.length<2){
		alert('<?= db_to_html("请输入相册描述！");?>');
		return false;
	}
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_create_album.php','action=process')) ?>");
	var form_id = form.id;
	ajax_post_submit(url,form_id);
}

//打开添加相册框
function create_album(){
	var form = document.getElementById("create_album_form");
	var h3 = document.getElementById("action_h3");
	form.elements['photo_books_id'].value = "";
	form.elements['create_ro_update'].value = "create";
	form.elements['photo_books_name'].value = "";
	form.elements['photo_books_description'].value = "";
	h3.innerHTML = "<?= db_to_html('添加相册');?>";
	showDiv('cr_photo_books');
}
//编辑相册
function update_album(books_id){
	var form = document.getElementById("create_album_form");
	var h3 = document.getElementById("action_h3");
	form.elements['photo_books_id'].value = books_id;
	form.elements['create_ro_update'].value = "update";
	var Photo_Books_Name = document.getElementById("photo_books_name_"+books_id);
	form.elements['photo_books_name'].value = Photo_Books_Name.innerHTML;
	var Photo_Books_Description = document.getElementById("photo_books_description_"+books_id);
	form.elements['photo_books_description'].value = Photo_Books_Description.innerHTML;
	h3.innerHTML = "<?= db_to_html('编辑相册');?>";
	showDiv('cr_photo_books');
}
//删除相册
function remove_album(books_id, dis_id){
	if(confirm("<?= db_to_html('是否真的删除该相册？该相册内的所有相片均被删除。')?>")){
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_create_album.php','action=remove_album')) ?>")+'&photo_books_id='+books_id+'&dis_id='+dis_id;
		ajax_get_submit(url);
		/*var tmp_obj = document.getElementById(dis_id);
		if(tmp_obj!=null){
			tmp_obj.parentNode.removeChild(tmp_obj);
		}*/
	}
}
</script>
<!--弹出层相册 end-->

