<script type="text/javascript">
<?php
$p=array('/&amp;/','/&quot;/');
$r=array('&','"');
?>
var old_show_id = "MyPhotoBooks";
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
		show_hidden('MyBlog');
	}
}

function DelPhotoBook(BookId){
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('add_edit_del_photo_books_ajax.php','action=delete_confirmation&photo_books_id=')) ?>") +BookId;
	var truthBeTold = window.confirm("<?php echo db_to_html('删除相册将会把此相册下面的所有照片删除，您认定要删除吗？')?>");
	if (!truthBeTold){
	}else{
		ajax.open('GET', url, true);  
		ajax.send(null);
		ajax.onreadystatechange = function() { 
			if (ajax.readyState == 4 && ajax.status == 200 ) { 
				if(ajax.responseText.search(/\[0\]/)!=-1){
					alert("<?php echo db_to_html('相册删除失败！') ?>");
				}
				if(ajax.responseText.search(/\[1\]/)!=-1){
					document.getElementById('PhotoBooksList'+BookId).innerHTML='';
					document.getElementById('PhotoBooksList'+BookId).style.display='none';
					if(document.getElementById('PhotoBookSum')!=null){
						document.getElementById('PhotoBookSum').innerHTML = document.getElementById('PhotoBookSum').innerHTML - 1;
					}
				}
			}
		}
	}
}
</script>


<?php
if ($messageStack->size('Photo') > 0) {
	echo '<div id="messageStack" class="bg3_right_title_l">'. $messageStack->output('Photo').'</div>';
}
?>


<div class="geren_content_rizhi">   
 <div class="tab2" >
		     	<ul>
					<li class="x" id="MyPhotoBooksLi"><a href="javascript:show_hidden('MyPhotoBooks')"><?php echo db_to_html('我的相册')?></a></li>
                     <li id="FriendBooksLi"><a href="javascript:show_hidden('FriendBooks')"><?php echo db_to_html('好友的最新相册')?></a></li>
				</ul>
		  </div>
          <div class="rizhi_content">
          
		  
		  <div id="MyPhotoBooks"><div class="fenlei_geren"><div class="rizhi_head"> <div class="geren_content_nav2"> 
          <span class="huise"><span id="PhotoBookSum"><?php echo count($my_photo_books_array)?></span><?php echo db_to_html('个相册') ?></span>| <a href="<?php echo tep_href_link('my-space-create-photo-books.php')?>"><?php echo db_to_html('创建新相册')?></a>
 </div>
 <div  class="bg3_right_title_r3"><a href="<?php echo tep_href_link('my-space-upload-photo.php')?>" ><img src="image/buttons/tchinese/new_photo.gif" /></a></div></div>
 <div class="fenlei_geren_center">
 
<!--MyPhotoBooksList start-->
<?php
if(count($my_photo_books_array) && is_array($my_photo_books_array)){
	foreach($my_photo_books_array as $key){

?>
 <div class="photo_list" id="PhotoBooksList<?php echo (int)$key['id']?>" ><div class="middle_img5">
 <?php
 $cover='<img src="image/photo_126px.gif" width="126" height="126" />';
 if(tep_not_null($key['cover'])){ $cover = '<img src="images/photos/'.$key['cover'].'" '.getimgHW3hw(DIR_PHOTOS_FS_IMAGES.$key['cover'], 126, 126).' />';}
 
 echo '<a href="'.tep_href_link('xiangce_neirong.html').'">'.$cover.'</a>';
 ?>
 <!--宽定，高定-->
 
 </div>
 <div class="margen_l bg3_right_title_l"><span class=" dazi cu"><a href="xiangce_neirong.html" ><?php echo db_to_html(tep_db_output($key['name']))?>
</a></span><br />
<span class="huise"><?php echo db_to_html("共 {$key['photo_sum']} 张照片")?>|<?php echo db_to_html('（'.tep_db_output($key['date']).'）')?></span><br />
<span class="huise"><a href="<?php echo tep_href_link('my-space-upload-photo.php','photo_books_id='.(int)$key['id'])?>"  ><?php echo db_to_html('上传照片')?></a> | <a href="<?php echo tep_href_link('my-space-photos-book-edit.php','photo_books_id='.(int)$key['id'])?>"  ><?php echo db_to_html('编辑相册')?></a> | <a href="javascript:DelPhotoBook(<?php echo (int)$key['id'] ?>)"  ><?php echo db_to_html('删除')?></a></span></div>
     <div class="photo_suoliu">
       <?php
	   //show 3 new photo
	   $poto_list = get_photo_list_for_photo_books($key['id']);
	   //print_r($poto_list);
	   ?>
	   <table border="0" cellspacing="0" cellpadding="0">
         <tr>
           <?php foreach((array)$poto_list as $key_p){?>
		   <td><img src="<?php echo  'images/photos/'.$key_p['name'] ?>" alt="<?php echo db_to_html($key_p['tag']) ?>" <?php echo getimgHW3hw(DIR_PHOTOS_FS_IMAGES.$key_p['name'],64,64) ?> align="absbottom"  /></td>
           <td>&nbsp;</td>
		   <?php }?>
		   
         </tr>
       </table>
     </div>
 </div>
<?php
	}
}
?>
<!--MyPhotoBooksList end-->
</div>
 
          </div>
          </div>
		  
		  
          <div id="FriendBooks" style="display:<?php echo 'none'?>"><div class="fenlei_geren"><div class="rizhi_head"> <div class="geren_content_nav2"> 
          <span class="huise">1个相册</span>
 </div>
 </div>
 <div class="fenlei_geren_center"><div class="photo_list"><div class="middle_img5"><a href="#"><img src="image/photo_126px.gif" /></a></div><div class="margen_l bg3_right_title_l"><span class=" dazi cu"><a href="xiangce_neirong2.html" >好友的相册123
</a></span><br />
<span class="huise">共8张照片</span><br />
<span class="huise"><a href="#"  >马爹力</a> | <a href="#"  class="lanzi4">(08/09/20 15：40）</a></span></div>
     <div class="photo_suoliu">
       <table width="100%" border="0" cellspacing="0" cellpadding="0">
         <tr>
           <td bgcolor="#FFFFFF">&nbsp;</td>
           <td width="2%">&nbsp;</td>
           <td>&nbsp;</td>
           <td width="2%">&nbsp;</td>
           <td>&nbsp;</td>
         </tr>
       </table>
     </div>
 </div>
 
 </div>
          </div></div>
          
          </div></div>
		  
		  
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