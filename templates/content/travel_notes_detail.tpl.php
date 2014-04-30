
<div class="jb_grzx_yj_bt">
    <h3><a id="travel_title" href="<?= $p_href?>"><?= $notes_title;?></a>
        <?php
        if((int)$notes_author_id && $notes_author_id==$customer_id){?>
		&nbsp;&nbsp;
                <a href="JavaScript:void(0)"  class="jb_fb_tc_bt_a" onclick="showDiv('EditTravelDiv');"><font style="font-size:12px;font-weight:normal"><?php echo db_to_html('编辑');?></font></a>&nbsp;&nbsp;
                <a href="JavaScript:void(0)"  class="jb_fb_tc_bt_a" onclick="del_travel_notes(<?= (int)$travel_notes_id?>);"><font style="font-size:12px;font-weight: normal"><?php echo db_to_html('删除');?></font></a>
		
        <?php
		}
        ?>
	</h3>
	<p class="jb_grzx_yj_bt_p"><?= db_to_html($notes_date)?></p>
	<!--
	<p class="jb_grzx_yj_bt_p">共享到<input type="image" src="image/icons/fx_1.gif" /><input type="image" src="image/icons/fx_2.gif" /><input type="image" src="image/icons/fx_3.gif" /><input type="image" src="image/icons/fx_4.gif" /></p>-->
 </div>
<!--编辑游记标记弹出层start-->
<div id="EditTravelDiv" class="jb_fb_tcAddXx" style="display:none">
<?php echo tep_pop_div_add_table('top');?>
    <form id="EditTravelForm" action="" onsubmit="edite_travel_notes_title();return false;" method="post" enctype="multipart/form-data">
		<div>
		<div class="jb_fb_tc_bt"><h3 id="action_h3"><?= db_to_html('编辑游记');?></h3><button class="icon_fb_bt" onclick="closeDiv('EditTravelDiv')" title="<?= db_to_html('关闭')?>" type="button"></button></div>
		<div class="jb_fb_tc_tab">
			        <div class="sc_text">
					<div class="sc_item"><p><?= db_to_html('题目：')?><?= tep_draw_input_field('travel_title',strip_tags($notes_title),' class="text5" title="'.db_to_html('请输入新的游记题目').'" onFocus="Check_Onfocus(this)" onBlur="Check_Onblur(this)"');?></p></div>
					<div class="sc_item"><p>
					<?php echo tep_draw_hidden_field('update_travel_notos_action',"true");?>
					<?php echo tep_draw_hidden_field('travel_notes_id',(int)$_GET['travel_notes_id']);?>
					

                                        <button class="jb_fb_all" id="submit_travel_button" type="submit" style="margin-left:36px; margin-top:10px;"><?php echo db_to_html('确定')?></button>
					<img style="display: none;" src="image/snake_transparent.gif" id="load_icon">
					</p></div>
				</div>
			</div>
		</div>
		</form>
<?php echo tep_pop_div_add_table('foot');?>
</div>
<!--编辑游记标记弹出层end-->
<!--上传相片 的弹出层start-->
<div id="travel_companion_tips_20121221" class="jb_fb_tcAddXx" style="display:none; z-index:1000;">
<?php echo tep_pop_div_add_table('top');?>
<div class="jb_fb_tc_bt">
<h3><?= db_to_html('上传相片')?></h3>
<button class="icon_fb_bt" onclick="show_travel_companion_tips(0,20121221);" title="<?= db_to_html('关闭')?>" type="button"></button>
</div>
<div class="jb_fb_tc_tab">
<table>
<tr>
<td nowrap="nowrap"><?= db_to_html('选择要上传的相片：')?></td>
<td>
<?php
$file_size = 500; //文件大小上限
//$save_dir = DIR_PHOTOS_FS_IMAGES;	//保存的文件夹路径 相片的上传需要先传到tmp文件夹，在提交到数据库时再从tmp移动到DIR_PHOTOS_FS_IMAGES
$save_dir = DIR_FS_CATALOG.'tmp/';
$width_height_px = '上传照片支持格式jpg,gif,png，每张图片大小不超过'.$file_size.'KB!';
$need_up_form_id = 'EditPotoForm'; //提交完成后需要更新的表单id
$upload_type = 'photo';	//上传类型
$done_close_id = 'travel_companion_tips_20121221';
include_once("ajax_upload.php");
?>
</td>
</tr>
</table>
</div>
<?php echo tep_pop_div_add_table('foot');?>
</div>
<!--上传相片 的弹出层end-->
<script type="text/javascript">
//打开图片上传模块
function load_upload_module(i){
	var randNum=Math.floor(Math.random()*2000)+1;
	var fForm = document.getElementById("FaceForm");
	fForm.elements['save_file_name'].value = "<?= date('YmdHis').'_'.$customer_id.'_'?>"+i+"_random_"+randNum;
	fForm.elements['need_up_img_id'].value = "photo_box_"+i;
	fForm.elements['need_up_form_input_name'].value = "photo_name";
	fForm.elements['FileDomain'].value = "";
	show_travel_companion_tips(1,20121221);
}
</script>
 <div class="jb_grzx_yj">
<?php
if(tep_not_null($photo_ids)){
	$photo_sql = tep_db_query(' SELECT * FROM `photo` WHERE photo_id in('.$photo_ids.') order by photo_id ');
        $photo_id='';
        $photo_books_i='';
        $i=0;
	while($photo = tep_db_fetch_array($photo_sql)){
                $photo_books_id = $photo['photo_books_id'];
                $photo_id = $photo['photo_id'];
		$img_src = 'images/photos/'.tep_db_output($photo['photo_name']);
?>   
	<div id="PicBlock_<?=$photo_id?>">
	 <a id="href_big_pic_<?=$photo_id?>"href="<?= $img_src?>" target="_blank"><img id="big_pic_<?=$photo_id?>" class="jb_grzx_yj_mar" src="<?= $img_src?>" <?php echo getimgHW3hw($img_src,500,600)?> /></a>
     <h3 class="jb_grzx_yj_mar" id="photo_title_<?=$photo_id?>"><?= db_to_html(tep_db_output($photo['photo_title']))?></h3>
     <p class="jb_grzx_yj_mar" id="photo_content_<?=$photo_id?>"><?= nl2br(db_to_html(tep_db_output($photo['photo_content'])))?></p>
     <div class="jb_item_1_l" id="photo_sent_per_<?=$photo_id?>">
		<p class="col_5">
		<a class="t_c" href="<?= tep_href_link('individual_space.php','customers_id='.$notes_author_id);?>"><?= db_to_html($notes_author_name)?></a>&nbsp;<?= db_to_html($notes_author_genders)?> <?= db_to_html($notes_date.'发表');?>&nbsp;&nbsp;
		<?php
		if((int)$notes_author_id && $notes_author_id==$customer_id){
			
			$photo_box_images = get_thumbnails($img_src);
		?>
		<a id="EditPotoA_<?=$photo_id?>" href="JavaScript:void(0)" class="jb_fb_tc_bt_a" onclick="show_edit_travle('EditPotoForm_<?=$photo_id?>','<?=$photo_box_images?>');"><?= db_to_html('编辑');?></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="JavaScript:void(0)" class="jb_fb_tc_bt_a" id="DeletePotoA_<?=$photo_id?>" onclick="delete_note_poto(<?=$photo_id?>)"><?= db_to_html('删除');?></a>
		
		<?php
                 echo tep_draw_form('EditPotoForm_'.$photo_id,'','post','id=EditPotoForm_'.$photo_id.' style="display:none"');
                 echo tep_draw_hidden_field('update_photo_action',"true");
		 echo tep_draw_hidden_field('photo_books_id',$photo_books_id);
	         echo tep_draw_hidden_field('photo_id',$photo_id);
                 echo tep_draw_hidden_field('photo_name',$photo['photo_name']);
                 echo tep_draw_hidden_field('photo_title',db_to_html($photo['photo_title']));
                 echo tep_draw_textarea_field('photo_content','virtual',50,5,db_to_html(strip_tags($photo['photo_content'])),' class="textarea44" style="display:none"');
                 echo '</form>';
		}
		?>

		</p>
		</div>
	</div>
<?php
  
	}

}
?>
</div>
<?php //游记修改 弹出窗口
  $div_jb_fb_tc='jb_fb_tc';
  if(strpos($_SERVER["HTTP_USER_AGENT"],"Firefox")||strpos($_SERVER["HTTP_USER_AGENT"],"Safari")||strpos($_SERVER["HTTP_USER_AGENT"],"Chrome")||strpos($_SERVER["HTTP_USER_AGENT"],"Opera")){
      $div_jb_fb_tc = 'jb_fb_tcAddXx';
  }
?>
<div id="EditPotoDiv" class="<?=$div_jb_fb_tc?>" style="display:none">
<?php echo tep_pop_div_add_table('top');?>
     <form id="EditPotoForm" action="" onsubmit="edite_travel_notes();return false;" method="post" enctype="multipart/form-data">
		<div>
		<div class="jb_fb_tc_bt"><h3 id="action_h3"><?= db_to_html('编辑游记');?></h3><button class="icon_fb_bt" onclick="closeDiv('EditPotoDiv')" title="<?= db_to_html('关闭')?>" type="button"></button></div>
		<div class="jb_fb_tc_tab">
		<div class="sc_none">
                    <div class="sc_item"><a href="JavaScript:void(0)" onclick="load_upload_module(0)"><img id="photo_box_0" src="" width="145" height="109" /></a>
				<?= tep_draw_hidden_field('photo_name',$photo['photo_name'],' id="photo_name" title="'.db_to_html('请上传相片').'" ');?>
				</div>

			<div class="sc_item a_mid"><a href="JavaScript:void(0)" onclick="load_upload_module(0)" class="jb_fb_tc_bt_a"><?= db_to_html('换张照片')?></a></div>
		</div>
				   <div class="sc_text">
					<div class="sc_item"><p><?= db_to_html('标题：')?><?= tep_draw_input_field('photo_title',strip_tags($photo['photo_title']),' class="text5" title="'.db_to_html('请输入相片标题').'" onFocus="Check_Onfocus(this)" onBlur="Check_Onblur(this)"');?></p></div>
					<div class="sc_item"><p><span class="v_top"><?= db_to_html('内容：')?></span><?= tep_draw_textarea_field('photo_content','virtual',50,5,strip_tags($photo['photo_content']),' class="textarea44" title="'.db_to_html('请输入对照片的描述或旅游到此的心情或感受以及所见所闻。').'" onFocus="Check_Onfocus(this)" onBlur="Check_Onblur(this)" ');?>
					<br />
					<?php echo tep_draw_hidden_field('update_photo_action',"true");?>
					<?php echo tep_draw_hidden_field('photo_books_id');?>
					<?php echo tep_draw_hidden_field('photo_id');?>

                                        <button class="jb_fb_all" id="submit_photo_button" type="submit" style="margin-left:36px; margin-top:10px;"><?php echo db_to_html('确定')?></button>
					<img style="display: none;" src="image/snake_transparent.gif" id="load_icon">
					</p></div>
				</div>
			</div>
		</div>
		</form>
<?php echo tep_pop_div_add_table('foot');?>
</div>



<?php
//网友评论
$comments_sql = tep_db_query('SELECT * FROM `travel_notes_comments` WHERE travel_notes_id = "'.$travel_notes_id.'" and has_remove!="1" Order By travel_notes_comments_id ');
$comments = array();
while($comments_rows = tep_db_fetch_array($comments_sql)){
	$comments[] = array('travel_notes_id'=> $comments_rows['travel_notes_id'],
                            'travel_notes_comments_id'=>$comments_rows['travel_notes_comments_id'],
						'content'=> nl2br(db_to_html(tep_db_output($comments_rows['comments']))),
						'date'=>$comments_rows['added_time'],
						'customers_id'=>$comments_rows['customers_id']);
}
$comments_num = count($comments);
?>
<div class="jb_grzx_yj">
          <div class="jb_hf"><h3 class="bt"><?= db_to_html('网友评论（<span id="comments_num">'.$comments_num.'</span>条）')?></h3></div>
<?php
	     if($comments_num){
		  	for($i=0; $i<$comments_num; $i++){
		  		$customers_id = $comments[$i]['customers_id'];
				$customers_name = db_to_html(tep_customers_name($customers_id));
				$gender = tep_customer_gender($customers_id);
				$customers_genders = db_to_html(tep_get_gender_string($gender, 1));
				$head_img = 'touxiang_no-sex.gif';
				if(strtolower($gender)=='m' || $gender=='1'){ $head_img = "touxiang_boy.gif"; }
				if(strtolower($gender)=='f' || $gender=='2'){ $head_img = "touxiang_girl.gif"; }
				$head_img = 'image/'.$head_img;
				$head_img = tep_customers_face($customers_id, $head_img);
				$customers_links_href = tep_href_link('individual_space.php','customers_id='.$customers_id);
		  ?>
		  <div class="jb_item_1 line1" id="comments_<?= $comments[$i]['travel_notes_comments_id']?>">
              <div class="jb_item_1_l"><p class="col_5">
			  <a class="t_c" href="<?= $customers_links_href;?>"><?= $customers_name;?></a><?= $customers_genders?> <?= $comments[$i]['date']?>
			  <?php
			  if($notes_author_id == $customer_id){
				  //此处删除评论只是做一个删除标记，让此页面不再显示这个评论
			  ?>
			  <a href="JavaScript:void(0)" onclick="remove_travel_comments(<?= $comments[$i]['travel_notes_comments_id']?>);" class="jb_fb_tc_bt_a"><?= db_to_html('删除');?></a>
			  <?php
			  }
			  ?>
			  </p> <p class="col_2"><?= $comments[$i]['content']?></p></div>
              <div class="jb_item_1_r">
			  <a href="<?= $customers_links_href;?>"><img src="<?= $head_img?>" <?= getimgHW3hw($head_img,50,50)?> /></a>
			  <p><?= ($i+1).db_to_html('楼')?></p></div>
           </div>
          <?php
		        }
	    }
?>           
		   
           <div class="jb_yj_hf">
               <h3 class="bt"><?= db_to_html('我也说几句...')?></h3>
               
			   <div class="jb_yj_hf_n">
			   <?php
			   if((int)$customer_id){
					$head_img_my = "touxiang_no-sex.gif";
					$gender = tep_customer_gender($customer_id);
					if(strtolower($gender)=='m' || $gender=='1'){ $head_img_my = "touxiang_boy.gif"; }
					if(strtolower($gender)=='f' || $gender=='2'){ $head_img_my = "touxiang_girl.gif"; }
					$head_img_my = 'image/'.$head_img_my;
					$head_img_my = tep_customers_face($customer_id, $head_img_my);
			   ?>
			   <a href="<?= tep_href_link('individual_space.php','customers_id='.$customer_id)?>"><img src="<?= $head_img_my?>" <?= getimgHW3hw($head_img_my,50,50)?> /></a><p><a href="<?= tep_href_link('individual_space.php','customers_id='.$customer_id)?>"><?= db_to_html(tep_customers_name($customer_id));?></a></p>
			   <?php
			   }
			   ?>
			   </div>
 <!--点击弹出层-->
 <?php
  $div_jb_fb_tc='jb_fb_tc';
  if(strpos($_SERVER["HTTP_USER_AGENT"],"Firefox")||strpos($_SERVER["HTTP_USER_AGENT"],"Safari")||strpos($_SERVER["HTTP_USER_AGENT"],"Chrome")||strpos($_SERVER["HTTP_USER_AGENT"],"Opera")){
      $div_jb_fb_tc = 'jb_fb_tcAddXx';
  }
 ?>
  <div class="<?=$div_jb_fb_tc?>" id="travel_companion_tips_2066" style="text-decoration:none; display:none">
  <?php echo tep_pop_div_add_table('top');?>
  <?php
  if(!(int)$customer_id){

	$replace_id = 'travel_companion_tips_2066';
	$next_file = 'top_window_photo_list';
	require('ajax_fast_login.php');
  }
  ?>
  <?php echo tep_pop_div_add_table('foot');?>
 </div>
 <!--弹出结束-->
			   <div  class="jb_yj_hf_s" style="text-align:left;">
                <?php
                if(!(int)$customer_id){
                      $onclick_onclick=' onfocus=check_login("travel_companion_tips_2066",false) ';
                }else{
                      $onclick_onclick='';
                }
                ?>
               <form action="" method="post" enctype="multipart/form-data" name="bbs_form" id="bbs_form" onsubmit="submit_travel_notes_comments(); return false;">
			<?php
			if(!(int)$customer_id){
				/*
				echo db_to_html('您的账号：').tep_draw_input_field('email_address');
				echo db_to_html(' 密码：').tep_draw_password_field('password');
				echo "<br><br>";
				*/
			}
			echo tep_draw_hidden_field('travel_notes_id');
			echo tep_draw_textarea_field('tcomments','',50,5,'',' class="textarea33" '.$onclick_onclick);
			?>
			   <br /><br /><br />
<?php
$button_onclick = '';
if(!(int)$customer_id){
	$button_onclick = ' onclick="check_login(\'travel_companion_tips_2066\',false); return false;"';
}
?>
			   <button type="submit" <?= $button_onclick?> id="rew_submit_button" class="jb_fb_all" ><?= db_to_html('确定')?></button>
			   </form>
			   </div>
           </div>

<script type="text/javascript">
function submit_travel_notes_comments(){
	var form = document.getElementById("bbs_form");
	var error = false;
	if(form.elements['tcomments'].value.length<2){
		error = true;
		error_sms = "<?= db_to_html('请输入评论内容！')?>";
	}
	if(error == true){
		alert(error_sms);
		return false;
	}
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_travel_notes_detail.php','action=process')) ?>");
	var form_id = form.id;
	var success_msm = "";
	var success_go_to = "";
	ajax_post_submit(url,form_id,success_msm,success_go_to);
}
</script>

</div>
