<script type="text/javascript">
var old_show_id = "EditPhotoBooks";
function show_hidden(show_id){
	window.location.href = window.location.href.split("#")[0] + '#tag=' + show_id;
	//alert(window.location.href);
	
	if(document.getElementById(show_id)!=null){
		document.getElementById(old_show_id).style.display='none';
		document.getElementById(old_show_id+'Li').className='';
		old_show_id = show_id;
		document.getElementById(show_id).style.display='';
		document.getElementById(show_id+'Li').className='x';
	}else{
		show_hidden('EditPhoto');
	}
}

function CheckPhotoSubmit(PhotoId){
	var submit_action = true;
	var ListForm = document.getElementById("ListForm"+PhotoId);
	if(ListForm.elements["DelAction"].checked==true){
		var truthBeTold = window.confirm("<?php echo db_to_html('您认定要删除照片吗？')?>");
		if (!truthBeTold){
			submit_action = false;
			ListForm.elements["DelAction"].checked = false;
		}
	}
	if(submit_action == true){
		ListForm.submit();
	}
}

function ChangeToNewBooks(PhotoId){
	var submit_action = true;
	var ListForm = document.getElementById("ListForm"+PhotoId);
	if(ListForm.elements["photo_books_id"].value > 0){
		CheckPhotoSubmit(PhotoId);
	}
}
</script>

<?php
if ($messageStack->size('Photo') > 0) {
	echo '<div id="messageStack" class="bg3_right_title_l">'. $messageStack->output('Photo').'</div>';
}
?>

<div class="tab2" >
		     	<ul>
					<li class="x" id="EditPhotoBooksLi"><a href="javascript:show_hidden('EditPhotoBooks')"><?php echo db_to_html('编辑相册信息')?></a></li>
                    <li id="EditPhotoLi"><a href="javascript:show_hidden('EditPhoto')"><?php echo db_to_html('编辑照片')?></a></li>
                    <li><a href="<?php echo tep_href_link('my-space-upload-photo.php','photo_books_id='.(int)$photo_book[0]['id'])?>"  ><?php echo db_to_html('上传照片')?></a></li>
				</ul>
		  </div>
<div class="rizhi_content">
          <div><div class="fenlei_geren"><div class="rizhi_head"> <div class="geren_content_nav2"> 
          <span class="huise"><?php echo '当前相册： <b>'.db_to_html($photo_book[0]['name']).'</b> '.db_to_html('共'.get_photo_books_sum($photo_books_id).'张照片')?></span></div><div class="bg3_right_title_r "><a href="<?php echo tep_href_link('my-space-photos.php')?>"><?php echo db_to_html('返回相册')?></a></div>
          </div>

<!--当前相册编辑框 start -->
	<form action="" method="post" enctype="multipart/form-data" name="PhotoBookEdit_Form" id="PhotoBookEdit_Form">
	<div id="EditPhotoBooks" class="fenlei_geren_center">
	<div class="photo_cj">
	<p>
	<label class="geren_content_tit"><?php echo db_to_html('相册名')?></label>
	<?php
	if(!$photo_books_name){
		$photo_books_name = db_to_html($photo_book[0]['name']);
	}
	echo tep_draw_input_field('photo_books_name','','class="textarea2"');
	?>
	</p>
	<P>
	<label class="geren_content_tit"><?php echo db_to_html('描述')?></label>
	<?php
	if(!$photo_books_description){
		$photo_books_description = db_to_html($photo_book[0]['description']);
	}
	echo tep_draw_textarea_field('photo_books_description', 'virtual','45','5','', 'class="textarea"');
	?>
	</p>
	<P>
	<label class="geren_content_tit"><?php echo db_to_html('隐私设置')?></label>
	</p>
	
<?php
if(!$photo_books_privacy_settings){
	$photo_books_privacy_settings = $photo_book[0]['privacy_settings'];
}
$privacy_sql = tep_db_query('SELECT * FROM `privacy_settings` ORDER BY `privacy_settings_id` DESC ');
$option_array=array();
while($privacy_rows = tep_db_fetch_array($privacy_sql)){
	$option_array[]=array('id'=>$privacy_rows['privacy_settings_id'], 'text'=>db_to_html($privacy_rows['privacy_settings_text']));
}
echo tep_draw_pull_down_menu('photo_books_privacy_settings', $option_array, '', 'class="textarea3"');
?>
	<div class="geren_content_11"><div class="bg3_right_title_l">
	  <?php echo tep_template_image_submit('save_11.gif', 'Save'); ?>
      <input name="action" type="hidden" id="action" value="EditPhotoBooks">
	  <input name="photo_books_id" type="hidden" value="<?php echo (int)$photo_book[0]['id']?>">
	  </div>
	
	</div>
	</div>         
	</div>
	</form>
<!--当前相册编辑框 end -->

          </div>
          </div>
          <div id="EditPhoto"><div class="fenlei_geren2">
          <div class="fenlei_geren_center">
<!--取得相片列表 start-->
<?php
//取得客户的相册列表用作转移选择框使用

$photo_books_list = get_user_photo_books_list($customer_id);


$photo_list = get_photo_list_for_photo_books($photo_book[0]['id'], ' photo_update DESC ', '');

if((int)$photo_list[0]['id']){
	for($i=0; $i<count($photo_list); $i++){
?>              

<div class="photo_cj">
<form action="" method="post" enctype="multipart/form-data" name="ListForm<?php echo $photo_list[$i]['id'] ?>" id="ListForm<?php echo $photo_list[$i]['id'] ?>" onSubmit="CheckPhotoSubmit(<?php echo $photo_list[$i]['id'] ?>);return false;" >
            <table width="98%" border="0" cellpadding="0" cellspacing="0" style="float:left">
  <tr>
    <td valign="top"><P><?php echo db_to_html('描述')?></p>
     <p>
	 <?php
	$photo_tag = db_to_html($photo_list[$i]['tag']);
	echo tep_draw_input_field('photo_tag','',' size="30" ');
	 
	 ?>
	 </p>
	 <p>
<?php
$option_array=array();
$option_array[0]=array('id'=>'0','text'=>db_to_html('转移到') );
foreach((array)$photo_books_list as $key){
	$option_array[]=array('id'=>$key['id'], 'text'=>db_to_html($key['name']));
}
echo tep_draw_pull_down_menu('photo_books_id', $option_array ,'','onChange="ChangeToNewBooks('.(int)$photo_list[$i]['id'].')"');
?>
       </p>
	 <P><input name="DelAction"  type="checkbox" id="DelAction" style="margin:0px;" value="1" onClick="CheckPhotoSubmit(<?php echo $photo_list[$i]['id'] ?>)"/> <?php echo db_to_html('删除')?>
	   <input type="hidden" name="photo_id" value="<?php echo $photo_list[$i]['id'] ?>" />
	   <input name="photo_name" type="hidden" id="photo_name" value="<?php echo tep_db_output($photo_list[$i]['name']) ?>" />
	 </p>
	 <p><input name="set_cover" type="checkbox" value="1" /><?php echo db_to_html('同时设为相册封面')?></p>
	 <p><?php echo tep_template_image_submit('save_11.gif', 'Save'); ?><input name="action" type="hidden" value="EditPhoto"></p>
     </td>
    <td>&nbsp;</td>
    <td align="right" valign="top"> 
    <p>
	
	<a href="images/photos/<?php echo $photo_list[$i]['name']?>" target="_blank"><img alt="<?php echo $photo_tag ?>" src="images/photos/<?php echo $photo_list[$i]['name']?>" <?php echo getimgHW3hw(DIR_PHOTOS_FS_IMAGES.$photo_list[$i]['name'],125,100) ?> /></a><!--宽定，高不定-->
	</p>
    
     </td>
  </tr>
</table>
</form>
</div>
<!--取得相片列表 end-->
<?php 
	}
}
?>
      
           
</div>
</div>
</div>

</div>
		  
		  
<script type="text/javascript">
<?php
if(tep_not_null($tag)){
	echo 'show_hidden("'.$tag.'")';
}
?>

/* 根据不同的锚点显示相应的标签 start */
var LastHashLogs ="";
//var HashArray = new Array();
//var HashArrayKey = 0;

function CheckForHash_A(){
	if(document.location.hash){
		var HashLocationName = document.location.hash;
		HashLocationName1 = HashLocationName.replace("#","");
		if((HashLocationName1 != LastHashLogs) && HashLocationName1 != "" ){
										
				LastHashLogs = HashLocationName1;
				document.location.hash = HashLocationName1;
				//HashArray[HashArrayKey] = HashLocationName1;
				//HashArrayKey++;
				
				//alert(LastHashLogs);
				//if(LastHashLogs!=""){
					show_hidden(LastHashLogs.replace("tag=",""));
				//}
				//for(i=0; i<HashArray.length; i++){
					//alert(HashArray[i]);
				//}
		}
	}
}

var HashCheckInterval_A = setInterval("CheckForHash_A()", 20);
/* 根据不同的锚点显示相应的标签 end */

</script>