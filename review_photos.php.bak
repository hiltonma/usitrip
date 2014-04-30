<?php
/*
$Id: article_info.php, v1.0 2003/12/04 12:00:00 ra Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2003 osCommerce

Released under the GNU General Public License

特别说明：关于本模块内的上传相片的所用JS代码和相关功能全部都在product_info.tpl.php文件的关于 zip_load 图片压缩上传工具 部分。
*/
define('NO_SET_SNAPSHOT',true);
require_once("includes/application_top.php");

require_once(DIR_FS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_REVIEWS_WRITE);
require_once(DIR_FS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);
require_once(DIR_FS_INCLUDES . 'ajax_encoding_control.php');
require_once(DIR_FS_LANGUAGES . $language . '/' . FILENAME_QUESTION_WRITE);

//未登录情况下点击上传按钮start
$popupTip = "UploadLoginPopup";
$popupConCompare = "UploadLoginPopupConCompare";
/*
function get_login_box_upload_photo_popup(){
	global $customer_id, $popupTip, $popupConCompare;
	$con_contents ='<div class="reviewNew replyPopup">';
	$buttons = '<div class="popupBtn"><a href="javascript:;" class="btn btnOrange"><button id="SubmitReplyButton" type="submit">'.db_to_html("登录").'</button></a></div>';
	$h4_contents = db_to_html('<b>请先登录</b>');
	$con_contents .= tep_draw_form($popupTip.'_form','','post', ' id="'.$popupTip.'_form" onsubmit="fast_login_photot_upload(this.id); return false;" ');
	$con_contents .='<li><label>'.db_to_html('账号：').'</label>'.tep_draw_input_field('email_address','','class="required validate-email text username" title="'.db_to_html('请输入您的电子邮箱').'"').'</li>';
	$con_contents .='<li><label>'.db_to_html('密码：').'</label><input name="password" type="password" class="required text password" title="'.db_to_html('请输入正确的密码').'" /></li>';
	$con_contents .= $buttons;
	$con_contents .= '</form>';
	$con_contents .= '</div>';
	$PopupHtml = tep_popup($popupTip, $popupConCompare, "500", $h4_contents, $con_contents );
	return $PopupHtml;
}
$PopupObj[] = get_login_box_upload_photo_popup();
//未登录情况下点击上传按钮end
*/

// Howard added 快速编辑相片文字 start
if($_GET['action']=="UpdatePhotoText" && $_POST['ajax']=='true'){
	if((int)$_POST['traveler_photo_id']){
		$image_title = tep_db_prepare_input($_POST['image_title']);
		$image_desc = tep_db_prepare_input($_POST['image_desc']);
		$sql_data_array = array();
		if(tep_not_null($image_title)){
			$sql_data_array['image_title'] = ajax_to_general_string($image_title);
		}
		if(tep_not_null($image_desc)){
			$sql_data_array['image_desc'] = ajax_to_general_string($image_desc);
		}
		$sql_data_array = html_to_db($sql_data_array);
		if(!tep_not_null($sql_data_array)){
			die("Null Value");
		}
		tep_db_perform(TABLE_TRAVELER_PHOTOS, $sql_data_array, 'update', ' traveler_photo_id = "'.(int)$_POST['traveler_photo_id'].'" ');
		
		$sql = tep_db_query('select traveler_photo_id, image_title, image_desc FROM '.TABLE_TRAVELER_PHOTOS.' WHERE traveler_photo_id="'.(int)$_POST['traveler_photo_id'].'" ');
		$row = tep_db_fetch_array($sql);
		
		$fast_edit_photo_form_inner_html = '
		<input name="traveler_photo_id" type="hidden" value="'.$row['traveler_photo_id'].'"/>
				<p class="name"><span class="edit" id="image_title_'.(int)$_POST['traveler_photo_id'].'" title="image_title"><b>'.db_to_html(tep_db_output($row['image_title'])).'</b><label>'.db_to_html('编辑').'</label></span></p>
				<p class="edit" id="image_desc_'.(int)$_POST['traveler_photo_id'].'" title="image_desc">'.nl2br(db_to_html(tep_db_output($row['image_desc']))).'<label>'.db_to_html('编辑').'</label></p>
		';
		
		$js_str = '[JS]';
		$js_str .= 'jQuery("#FastEditPhotoForm_'.(int)$_POST['traveler_photo_id'].'").html("'.addslashes($fast_edit_photo_form_inner_html).'");';
		//再次激活jQuery代码
		$js_str .= 'obj_hover();';
		$js_str .= 'obj_click();';
		//$js_str .= 'alert(jQuery("#FastEditPhotoForm_'.(int)$_POST['traveler_photo_id'].'").html());';
		
		$js_str .= '[/JS]';
		$js_str = preg_replace('/[[:space:]]+/',' ',$js_str);
		echo $js_str;
		
	}
	die();
}
// Howard added 快速编辑相片文字 end
// Howard added 取消编辑相片文字 start
if($_GET['action']=="CloseUpdatePhotoText"){
	if((int)$_GET['traveler_photo_id']){
		$sql = tep_db_query('select traveler_photo_id, image_title, image_desc FROM '.TABLE_TRAVELER_PHOTOS.' WHERE traveler_photo_id="'.(int)$_GET['traveler_photo_id'].'" ');
		$row = tep_db_fetch_array($sql);
		if((int)$row['traveler_photo_id']){
			$fast_edit_photo_form_inner_html = '
			<input name="traveler_photo_id" type="hidden" value="'.$row['traveler_photo_id'].'"/>
					<p class="name"><span class="edit" id="image_title_'.$row['traveler_photo_id'].'" title="image_title"><b>'.db_to_html(tep_db_output($row['image_title'])).'</b><label>'.db_to_html('编辑').'</label></span></p>
					<p class="edit" id="image_desc_'.$row['traveler_photo_id'].'" title="image_desc">'.nl2br(db_to_html(tep_db_output($row['image_desc']))).'<label>'.db_to_html('编辑').'</label></p>
			';
			$js_str = '[JS]';
			$js_str .= 'jQuery("#FastEditPhotoForm_'.$row['traveler_photo_id'].'").html("'.addslashes($fast_edit_photo_form_inner_html).'");';
			//再次激活jQuery代码
			$js_str .= 'obj_hover();';
			$js_str .= 'obj_click();';
			//$js_str .= 'alert(jQuery("#FastEditPhotoForm_'.(int)$_POST['traveler_photo_id'].'").html());';
			
			$js_str .= '[/JS]';
			$js_str = preg_replace('/[[:space:]]+/',' ',$js_str);
			echo $js_str;
		}
	}
	die();
}
// Howard added 取消编辑相片文字 end

// Howard added 删除相片 start
if($_GET['action']=="RemovePhoto"){
	if((int)$_GET['traveler_photo_id']){
		$del_sql = tep_db_query('select * FROM '.TABLE_TRAVELER_PHOTOS.' WHERE traveler_photo_id="'.(int)$_GET['traveler_photo_id'].'" ');
		$del_row = tep_db_fetch_array($del_sql);
		//先删图片再删数据
		if(!(int)$customer_id || $customer_id!=$del_row['customer_id']){
			die("No permission");
		}
		//thumb_
		//detail_
		//watermark_detail_
		$basename_image_name = basename($del_row['image_name']);
		$image_name0 = str_replace($basename_image_name, 'thumb_'.$basename_image_name, $del_row['image_name'] );
		$image_name1 = str_replace($basename_image_name, 'detail_'.$basename_image_name, $del_row['image_name'] );
		$image_name2 = str_replace($basename_image_name, 'watermark_detail_'.$basename_image_name, $del_row['image_name'] );
		
		$unlink_file0 = DIR_FS_CATALOG.'images/reviews/'.$image_name0;
		$unlink_file1 = DIR_FS_CATALOG.'images/reviews/'.$image_name1;
		$unlink_file2 = DIR_FS_CATALOG.'images/reviews/'.$image_name2;
		$js_str = '[JS]';
		
		//$action_done = false;
		$action_done = true;
		if(@unlink($unlink_file0)==true){ $action_done = true;}
		if(@unlink($unlink_file1)==true){ $action_done = true;}
		if(@unlink($unlink_file2)==true){ $action_done = true;}
		if($action_done == true){
			tep_db_query('DELETE FROM '.TABLE_TRAVELER_PHOTOS.' WHERE `traveler_photo_id` = "'.(int)$_GET['traveler_photo_id'].'" ');
			//$js_str .= 'alert("'.db_to_html('相片删除成功！').'"); ';
		}
		
		$js_str .= "sendFormData('','".tep_href_link('review_photos.php', 'products_id=' . (int)$_GET['products_id'])."&active_photo=1','review_result_photo','true');";
		$js_str .= '[/JS]';
		$js_str = preg_replace('/[[:space:]]+/',' ',$js_str);
		echo $js_str;
	}
	die();
}
// Howard added 删除相片 end
// Howard added 快速登录后自动打开上传框 start
if($_GET['action']=="FastLoginProcess"){
	$HTTP_GET_VARS['action'] = $_GET['action']= $action ="process";
	include('login.php');
	$js_str = '[JS]';
	$js_str .= 'closePopup("CommonFastLoginPopup");';
	$js_str .= 'jQuery("#ALinkAddPhoto").show();';
	$js_str .= 'jQuery("#ALinkAddPhotoNoLogin").hide();';
	$js_str .= 'closePopup("'.$popupTip.'");';
	$js_str .= '[/JS]';
	$js_str = preg_replace('/[[:space:]]+/',' ',$js_str);
	echo $js_str;
	die();
}
// Howard added 快速登录后自动打开上传框 end

if(isset($_POST['aryFormData'])){
	$aryFormData = $_POST['aryFormData'];
	foreach ($aryFormData as $key => $value ){
		foreach ($value as $key2 => $value2 ){	  
			$value2 = iconv('utf-8',CHARSET.'//IGNORE',$value2);
			$HTTP_POST_VARS[$key] = stripslashes(str_replace('@@amp;','&',$value2));   
		}
	}
}


?>
        
		<ul id="review_result_photo" class="photoList">
		<?php
		$where = ' and image_status=1 ';
		if(intval($customer_id)){
			$where = " and (image_status=1 or customer_id='{$customer_id}' or customers_email='{$customer_email_address}') ";
		}
		$reviews_query_raw = "select * from ".TABLE_TRAVELER_PHOTOS." where products_id =".$_GET['products_id'].$where." order by traveler_photo_id desc";
		
		$MAX_DISPLAY_NEW_REVIEWS = 10;
		if((!tep_not_null($_GET['seeAll']) || $_GET['seeAll']!="all-photos")){
			$MAX_DISPLAY_NEW_REVIEWS = 5;
		}
		
		$reviews_split = new splitPageResults($reviews_query_raw, $MAX_DISPLAY_NEW_REVIEWS, 'traveler_photo_id','rn');
		if ($reviews_split->number_of_rows > 0) {		
			if ($reviews_split->number_of_rows > MAX_DISPLAY_NEW_REVIEWS && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
				$pages_split_top_html = 
					'<div class="page">'.
					tep_draw_form('frm_slippage_ajax_product_photo_top', '' ,"",'id=frm_slippage_ajax_product_photo_top').	
					TEXT_RESULT_PAGE .' ' . $reviews_split->display_links_ajax_withouthash(MAX_DISPLAY_PAGE_LINKS, 'mnu=photos&'.tep_get_all_get_params(array('rn','page','mnu','info')),'review_photos.php','frm_slippage_ajax_product_photo_top','review_result_photo'). 
					'<input type="hidden" name="selfpagename_treview" value="products_detail_review">'.
					'<input type="hidden" name="ajxsub_send_treview_req" value="true">'.
					'</form>'.
					'</div>';
				
				$pages_split_bottom_html = 
					'<div class="page">'.
					tep_draw_form('frm_slippage_ajax_product_bottom', '' ,"post",'id=frm_slippage_ajax_product_bottom').
					TEXT_RESULT_PAGE .' ' . $reviews_split->display_links_ajax_withouthash(MAX_DISPLAY_PAGE_LINKS, 'mnu=photos&'.tep_get_all_get_params(array('rn','page','mnu','info')),'review_photos.php','frm_slippage_ajax_product_bottom','review_result_photo').
					'<input type="hidden" name="selfpagename_treview" value="products_detail_review">'.
					'<input type="hidden" name="ajxsub_send_treview_req" value="true">'.
					'</form>'.
					'</div>';
				$pages_split_bottom_html .= '<script type="text/javascript">jQuery("#view_all_counter").html("'.$reviews_split->number_of_rows .'");</script>';
				
				
				if((!tep_not_null($_GET['seeAll']) || $_GET['seeAll']!="all-photos")){
					$pages_split_top_html = '';
					$pages_split_bottom_html = '<div class="showMore"><a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=photos&seeAll=all-photos'.tep_get_all_get_params(array('info','mnu','rn','seeAll','vin_tab'))).'">'.db_to_html('浏览所有照片').'&gt;&gt; <span>('.db_to_html('共'.$reviews_split->number_of_rows.'张').')</span></a></div>';
				}
				//顶部翻页
				//echo $pages_split_top_html;
			}
			
		?>
			<ul id="reviews_photos_ul">
			  
			  <?php
				$reviews_query = tep_db_query($reviews_split->sql_query);																										
				$count=1;
				while ($row_reviews1 = tep_db_fetch_array($reviews_query)) {	
					$image = "";
					$image_name = $row_reviews1['image_name'];
					
					$basename_image_name = basename($row_reviews1['image_name']);
					$image_name0 = str_replace($basename_image_name, 'thumb_'.$basename_image_name, $row_reviews1['image_name'] );
					$image_name1 = str_replace($basename_image_name, 'detail_'.$basename_image_name, $row_reviews1['image_name'] );
					$image_name2 = str_replace($basename_image_name, 'watermark_detail_'.$basename_image_name, $row_reviews1['image_name'] );
					
					if ($image_name != '') {
						if (file_exists(DIR_FS_IMAGES . 'reviews/' . $image_name1)) {
							$thumb_image_dir_file_name="";
							if(file_exists(DIR_FS_IMAGES . 'reviews/' . $image_name0)) {
								$thumb_image_dir_file_name = DIR_WS_IMAGES . 'reviews/' . $image_name0;
							}
							
							if (file_exists(DIR_FS_IMAGES . 'reviews/' . $image_name2)) {
							//输出带水印的图片
								$image_dir_file_name = DIR_WS_IMAGES . 'reviews/' . $image_name2;
							} else {
								$image_dir_file_name = DIR_WS_IMAGES . 'reviews/' . $image_name1;
							}
							//$image = tep_image(tep_not_null($thumb_image_dir_file_name) ? $thumb_image_dir_file_name: $image_dir_file_name);
							$image = tep_image($image_dir_file_name, '', '500', '500');//显示的是原图
							$image_url_links = $image_dir_file_name;
						} else if (file_exists(DIR_FS_IMAGES . 'reviews/' . $image_name)) {
							//输出带水印的图片
							if (file_exists(DIR_FS_IMAGES . 'reviews/watermark_' . $image_name)) {
								$image_dir_file_name = DIR_WS_IMAGES . 'reviews/watermark_' . $image_name;
							} else {
								$image_dir_file_name = DIR_WS_IMAGES . 'reviews/' . $image_name;
							}
							$image = tep_image($image_dir_file_name, '', '500', '500');
							$image_url_links = $image_dir_file_name;
						} else {
							$image = tep_image(DIR_WS_IMAGES . 'noimage_large.jpg', '', '500', '500');
							$image_url_links = DIR_WS_IMAGES . 'noimage_large.jpg';
						}
					} else {
						$image = tep_image(DIR_WS_IMAGES . 'noimage_large.jpg', '', '500', '500');
						$image_url_links = DIR_WS_IMAGES . 'noimage_large.jpg';
					}
					if($reviews_split->current_page_number <=1 && !tep_not_null($_GET['active_photo']) && strpos($_SERVER['HTTP_USER_AGENT'],'MSIE 6.0')===false
						&& $_GET['seeAll']!="all-photos"
					){
						$image = str_replace('src=','src2=',$image);
					}
			  ?>
			  
			  <li>
				
				<div class="pic">
				  <a href="<?php echo $image_url_links?>" title="<?php echo db_to_html(tep_db_output($row_reviews1['image_title'])); ?>" target="_blank" ><?php echo $image; ?></a>
				</div>
				<?php
				$this_class = "";
				if((int)$customer_id && $row_reviews1['customer_id']==(int)$customer_id ){
					$this_class = "edit";
					if(!tep_not_null($row_reviews1['image_desc'])){ $row_reviews1['image_desc'] = '请输入描述';}
				}
				?>
				
				<form id="FastEditPhotoForm_<?= $row_reviews1['traveler_photo_id'];?>" onsubmit="SubmitFastEditPhotoForm(this); return false;">
				<input name="traveler_photo_id" type="hidden" value="<?= $row_reviews1['traveler_photo_id'];?>"/>
				<p class="name"><span class="<?=$this_class?>" id="image_title_<?= $row_reviews1['traveler_photo_id'];?>" title="image_title"><b><?php echo db_to_html(tep_db_output($row_reviews1['image_title'])); ?></b><label><?php echo db_to_html('编辑'); ?></label></span></p>
				<p class="<?=$this_class?>" id="image_desc_<?= $row_reviews1['traveler_photo_id'];?>" title="image_desc"><?php echo nl2br(db_to_html(tep_db_output($row_reviews1['image_desc'])));?><label><?php echo db_to_html('编辑'); ?></label></p>
				</form>
				<?php
				//$added_date = explode(" ",$row_reviews1['added_date']);						
				//$date = strtotime($added_date[0]);
				$date_disp = date("Y/m/d H:i",strtotime($row_reviews1['added_date']));
				?>
				<p class="info"><?php echo db_to_html($date_disp.' '.tep_db_output($row_reviews1['customers_name']).' 上传'); ?>
                <?php if($row_reviews1['image_status']!='1'){?>
				<font style="color:red;"><?php echo db_to_html('&nbsp;&nbsp;未审核');?></font>
                <a class="blue" href="javascript:void(0)" onclick="removePhoto(<?= $row_reviews1['traveler_photo_id'];?>)"><?php echo db_to_html('删除');?></a>
				<?php }?>
				</p>
			  </li>
			  <?php
				} //end of while loop
			  ?>
			</ul>
			
			<?php
				//底部翻页
				echo $pages_split_bottom_html;
			?>
		<?php
		}else{
			echo '<ul class="sharePic" id="reviews_photos_ul" style="display:none;"></ul><div class="noContent" id="no_reviews_photos_div">'.db_to_html('暂无旅友分享照片').'</div>';
		}
		?>
		
	</ul>


<script type="text/javascript">
    <!--
    <?php //鼠标移到文字显示编辑效果 start
	if(strtolower(CHARSET)=='gb2312'){
		$onblur = 'this.value = simplized(this.value);';
	}else{
		$onblur = 'this.value = traditionalized(this.value);';
	}
	?>
	jQuery(function(){
		//lightbox
		//jQuery('.pic a').lightBox();
		obj_hover();
		obj_click();
	});
	
	function obj_hover(){	
		jQuery(".edit").hover(function(){
			jQuery(this).addClass("editActive");
		},function(){
			jQuery(this).removeClass("editActive");
		});
	}
	
	function obj_click(){
		jQuery(".edit").click(function(){
			if(jQuery(this).find(':input').length<1){
				var InputName = jQuery(this).attr('title');
				var text_value = jQuery(this).text().replace(/<?= db_to_html("编辑")?>/,'');
				var inputbox = '<input name="'+ InputName+'" value="" class="text" onblur="<?= $onblur;?> "/>';
				var btnClass = '';
				if(InputName.indexOf('image_desc')>-1){
					inputbox = '<textarea name="'+ InputName +'" class="textarea" onblur="<?= $onblur;?> "></textarea>';
					btnClass = 'btnCenter';
				}
				
				jQuery(this).html(inputbox);
				
				var thisIdNumber = jQuery(this).attr("id").substring(jQuery(this).attr("id").lastIndexOf("_")+1);
				var next_obj = '<span class="'+btnClass+'"><a class="btn btnOrange" href="javascript:SubmitFastEditPhotoForm('+ jQuery("#FastEditPhotoForm_"+thisIdNumber) +');"><button type="submit"><?= db_to_html("确定");?></button></a> <a class="btn btnGrey" href="javascript:;" onclick="closeUpdate(\''+ jQuery(this).attr("id") +'\');"><button type="button"><?= db_to_html("取消");?></button></a></span>';
				jQuery(this).after(next_obj);
				if(InputName.indexOf('image_desc')>-1){
					jQuery(this).find("textarea[name='"+InputName+"']").val(text_value);
				}else{
					jQuery(this).find("input[name='"+InputName+"']").val(text_value);
				}
			}
			//alert(jQuery(this).html());
		});
	}	
	
	function SubmitFastEditPhotoForm(obj){
		var inputObj = jQuery(obj).find(':input');
		for(var i=0; i<inputObj.length; i++){
		<?php
		if(strtolower(CHARSET)=='gb2312'){
			echo 'inputObj[i].value = simplized(inputObj[i].value); ';
		}else{
			echo 'inputObj[i].value = traditionalized(inputObj[i].value); ';
		}
		?>
		}

		var url =  url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('review_photos.php','action=UpdatePhotoText')) ?>");
		ajax_post_submit(url,obj.id);
	}
	function closeUpdate(ConId){
		var pid = ConId.substring(ConId.lastIndexOf("_")+1);
		var url =  url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('review_photos.php','action=CloseUpdatePhotoText')) ?>")+'&traveler_photo_id='+pid;
		ajax_get_submit(url);
	}
    
	function removePhoto(TravelerPhotoId){
		var url =  url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('review_photos.php','action=RemovePhoto&products_id='.(int)$_GET['products_id'])) ?>")+"&traveler_photo_id="+TravelerPhotoId;
		ajax_get_submit(url);
	}
	
	<?php //鼠标移到文字显示编辑效果 end?>
	
	<?php //上传相片前的登录判断相关JS start?>
	//快速登录 for photot upload
	function fast_login_photot_upload(form_id){
		var from = document.getElementById(form_id);
		if(from.elements["email_address"].value.length<2){
			alert("<?php echo db_to_html('请输入您的账号（电子邮箱）！')?>");
			return false;
		}
		if(from.elements["password"].value.length<2){
			alert("<?php echo db_to_html('请输入您的密码！')?>");
			return false;
		}
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('review_photos.php','action=FastLoginProcess')) ?>");
		ajax_post_submit(url,form_id);
		return true;
	}
	
	jQuery('#review_result_photo .pic a').lightBox(); /* 幻灯图片 */
	
	<?php //上传相片前的登录判断相关JS end?>

	function stopUpload(success,image_title,image_description){
        var result = '';
        if (success == 1){
            //result = 'The file was uploaded successfully!';
            result = '';
            /*var image_title_transfer = image_title;
var image_desc_transfer = image_description;
document.getElementById('front_title').value = image_title_transfer;
document.getElementById('front_desc').value = image_desc_transfer;*/

            var str = image_title;
            str = str.substr(str.lastIndexOf("\\")+1,str.length);
            //alert(str);
            document.getElementById('front_title').value = str ;

            var str1 = image_description;
            str1 = str1.substr(str1.lastIndexOf("\\")+1,str1.length);
            //alert(str);
            document.getElementById('front_desc').value = str1 ;


        }
        else {
            //result = 'There was an error during file upload!';

        }
        sendFormData('product_reviews_write1','<?php echo tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, 'action=process_photos&products_id=' . (int)$_GET['products_id']); ?>','photo_result','true');
        document.getElementById('f1_upload_process').style.visibility = 'hidden';
        document.getElementById('f1_upload_form').innerHTML = result;
		// + '<label>File: <input name="myfile" type="file" size="30" /><\/label><label><input type="submit" name="submitBtn" class="sbtn" value="Upload" /><\/label>'
        document.getElementById('f1_upload_form').style.visibility = 'visible';      
        return true;   
    }
	
	//点击翻页到当前Tab头部
	AutoToTabHead();
    //-->
</script>
