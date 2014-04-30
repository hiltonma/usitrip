
<div class="jb_grzx_yj_bt">
    <h3><?= $books_name?></h3><a name="apictag"></a><!--<p class="jb_grzx_yj_bt_p">共享到<input type="image" src="image/icons/fx_1.gif" /><input type="image" src="image/icons/fx_2.gif" /><input type="image" src="image/icons/fx_3.gif" /><input type="image" src="image/icons/fx_4.gif" /></p>-->
</div>
<?php
  if ($messageStack->size('update_photos') > 0) {
?>
	  <div id="msn_notes_div"><?php echo $messageStack->output('update_photos'); ?></div>
	 
<?php
  }
?>

<!--上传相片 的弹出层start-->       
<div id="travel_companion_tips_20121221" class="center_pop" style="display:none; z-index:1000;">
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

<?php
//是否有相片的判断
if(!(int)$photo_id){
	echo '<div class="jb_pic"><h3>'.db_to_html('此相册暂无相片').'</h3></div>';
}else{
?>
 
 <div class="jb_pic">
     <div class="jb_xc_ck">
        <div class="jb_xc_trun">
		<?php
		if((int)$back_photo_id){
			$back_links_href = tep_href_link('photo_list.php','photo_books_id='.$photo_books_id.'&photo_id='.$back_photo_id.'#apictag');
		?>
		<a href="<?= $back_links_href;?>"><img id="left_turn_l_n" src="image/turn_l.gif" /></a>
		<?php
		}else{
		?>
		<img id="left_turn_l_n" src="image/turn_l_n.gif" />
		<?php
		}
		?>
		</div>
        <div class="jb_xc_img_wz">
		<div class="jb_xc_img">
		<?php $photos_0_name = "images/photos/".$photos[$i_now]['name'];?>
		<a id="href_big_pic" href="<?= $photos_0_name?>" target="_blank"><img id="big_pic" src="<?= $photos_0_name?>" <?php echo getimgHW3hw($photos_0_name,600,500)?> /></a>
		</div>
		</div>
        <div class="jb_xc_trun">
		<?php
		if((int)$next_photo_id){
			$next_links_href = tep_href_link('photo_list.php','photo_books_id='.$photo_books_id.'&photo_id='.$next_photo_id.'#apictag');
		?>
		<a href="<?= $next_links_href;?>"><img id="right_turn_r" src="image/turn_r.gif" /></a>
		<?php
		}else{
		?>
		<img id="right_turn_r" src="image/turn_r_n.gif" />
		<?php
		}
		?>
		</div>
     </div>

     <div class="jb_xc_img_small">
      <div class="jb_xc_trun_s">
	  <?php
	  if(tep_not_null($back_links_href)){
	  ?>
	  <a href="<?= $back_links_href?>"><img id="top_turn_l_sn" src="image/turn_l_s.gif" /></a>
	  <?php
	  }else{
	  ?>
	  <img id="top_turn_l_sn" src="image/turn_l_sn.gif" />
	  <?php
	  }
	  ?>
	  </div>
       <ul class="jb_xc_simg">
         <?php
		 for($i=0; $i<count($photos); $i++){
		 	$small_display = "";
			if($i>4){
				$small_display = "display:none";
			}
			$src_file_name = "images/photos/".$photos[$i]['name'];
			$src_file_name = get_thumbnails($src_file_name);
			$selected_style = "";
			if($i_now == $i){
				$selected_style = " border:5px solid #FFCC01; ";
			}
		 ?>
           <li id="small_pic_<?=$i?>" style="<?= $small_display?>"><a style="<?= $selected_style?>" href="<?= tep_href_link('photo_list.php','photo_books_id='.$photo_books_id.'&photo_id='.$photos[$i]['id'].'#apictag')?>"><img src="<?=$src_file_name;?>" width="100px" height="75px" /></a></li>
         <?php
		 }
		 ?>
       </ul>
       <div class="jb_xc_trun_s">
	  <?php
	  if(tep_not_null($next_links_href)){
	  ?>
	  <a href="<?= $next_links_href?>"><img id="bottom_turn_r_s" src="image/turn_r_s.gif" /></a>
	  <?php
	  }else{
	  ?>
	  <img id="bottom_turn_r_s" src="image/turn_r_sn.gif" />
	  <?php
	  }
	  ?>
	   </div>
     </div>
 </div>
      <div class="jb_xc_ck_ms">
       <p class="jb_xc_ck_ms_b" id="photo_title"><?= tep_db_output($photos[$i_now]['title']);?></p>
       <p id="photo_content"><?= nl2br(tep_db_output($photos[$i_now]['content']))?></p>
        <div class="jb_item_1_l">
		<p class="col_5">
		<?php
		$cus_id = $photos[$i_now]['customers_id'];
		$upload_date = chardate($photos[$i_now]['date'], "I", 1);
		$cus_name = tep_customers_name($cus_id);
		$cus_genders = tep_get_gender_string(tep_customer_gender($cus_id), 1);
		?>
		<a class="t_c" href="<?= tep_href_link('individual_space.php','customers_id='.$cus_id);?>"><?= db_to_html($cus_name)?></a>&nbsp;<?= db_to_html($cus_genders)?> <?= db_to_html($upload_date.'上传');?>&nbsp;
		<?php
		if((int)$cus_id && $cus_id==$customer_id){
			$i = 0;
			$photo_box_images = get_thumbnails($photos_0_name);
                        
		?>
		<a id="EditPotoA" href="JavaScript:void(0)" class="jb_fb_tc_bt_a" onclick="showDiv('EditPotoDiv');"><?= db_to_html('编辑');?></a>&nbsp;
		<a href="JavaScript:void(0)" class="jb_fb_tc_bt_a" id="del_a"><?= db_to_html('删除');?></a>
		<div id="EditPotoDiv" class="jb_fb_tcAddXx" style="display:none">
		<?php echo tep_pop_div_add_table('top');?>
		<form id="EditPotoForm" action="" method="post" enctype="multipart/form-data">
		<div>
		<div class="jb_fb_tc_bt"><h3 id="action_h3"><?= db_to_html('编辑相片');?></h3><button class="icon_fb_bt" onclick="closeDiv('EditPotoDiv')" title="<?= db_to_html('关闭')?>" type="button"></button></div>
		<div class="jb_fb_tc_tab">
		<div class="sc_none">
			<div class="sc_item"><a href="JavaScript:void(0)" onclick="load_upload_module(<?= $i?>)"><img id="photo_box_<?= $i?>" src="<?=$photo_box_images?>" <?= getimgHW3hw($photo_box_images,145,109)?> /></a>
				<?= tep_draw_hidden_field('photo_name',$photos[$i_now]['name'],' id="photo_name" title="'.db_to_html('请上传相片').'" ');?>
				</div>
			
			<div class="sc_item a_mid"><a href="JavaScript:void(0)" onclick="load_upload_module(<?= $i?>)" class="jb_fb_tc_bt_a"><?= db_to_html('换张照片')?></a></div>
                        <div class="sc_item a_mid"><input name="set_cover" type="checkbox" value="1" /> <?php echo db_to_html('设为相册封面');?></div>
		</div>
				   <div class="sc_text">
					<div class="sc_item"><p><?= db_to_html('标题：')?><?= tep_draw_input_field('photo_title',strip_tags($photos[$i_now]['title']),' class="text5" title="'.db_to_html('请输入相片标题').'" onFocus="Check_Onfocus(this)" onBlur="Check_Onblur(this)"');?></p></div>
					<div class="sc_item"><p><span class="v_top"><?= db_to_html('内容：')?></span><?= tep_draw_textarea_field('photo_content','virtual',50,5,strip_tags($photos[$i_now]['content']),' class="textarea44" title="'.db_to_html('请输入对照片的描述或旅游到此的心情或感受以及所见所闻。').'" onFocus="Check_Onfocus(this)" onBlur="Check_Onblur(this)" ');?>
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
		}
		?>
		
		</p>
		</div>
 </div>

<?php
//网友评论 start
?>
<div class="jb_grzx_yj_xc">
          <div class="jb_hf"><h3 class="bt"><?= db_to_html('网友评论（<span id="comments_num">'.$comments_num.'</span>条）')?></h3></div>
          <?php
		  if($comments_num){
		  	for($i=0; $i<$comments_num; $i++){
		  		$customers_id = $comments[$i]['customers_id'];
				$customers_name = db_to_html(tep_customers_name($customers_id));
				$gender = tep_customer_gender($customers_id);
				$customers_genders = db_to_html(tep_get_gender_string($gender, 1));
				$head_img = 'tx_n_s.gif';
				if(strtolower($gender)=='m' || $gender=='1'){ $head_img = "tx_b_s.gif"; }
				if(strtolower($gender)=='f' || $gender=='2'){ $head_img = "tx_g_s.gif"; }
				$head_img = 'image/'.$head_img;
				$head_img = tep_customers_face($customers_id, $head_img);
				$customers_links_href = tep_href_link('individual_space.php','customers_id='.$customers_id);
		  ?>
		  <div class="jb_item_1 line1" id="comments_<?= $comments[$i]['id']?>">
              <div class="jb_item_1_l"><p class="col_5">
			  <a class="t_c" href="<?= $customers_links_href;?>"><?= $customers_name;?></a><?= $customers_genders?> <?= $comments[$i]['date']?>
			  <?php
			  if((int)$cus_id && $cus_id==$customer_id){
				  //此处删除评论只是做一个删除标记，让此页面不再显示这个评论
			  ?>
			  <a href="JavaScript:void(0)" onclick="remove_photo_comments(<?= $comments[$i]['id']?>);" class="jb_fb_tc_bt_a"><?= db_to_html('删除');?></a>
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
			   <form action="" method="post" enctype="multipart/form-data" name="bbs_form" id="bbs_form" onsubmit="submit_photo_comments(); return false;">
			<?php
			    echo tep_draw_hidden_field('email_address');
				echo tep_draw_hidden_field('password');
                
		        echo tep_draw_hidden_field('photo_id');
			    echo tep_draw_hidden_field('photo_books_id');
                if(!(int)$customer_id){
                      $onclick_onclick=' onfocus=check_login("travel_companion_tips_2066",false) ';
                }else{
                      $onclick_onclick='';
                }
			    echo tep_draw_textarea_field('tcomments','',50,5,'',' class="textarea33" '.$onclick_onclick.' ');
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
function submit_photo_comments(){
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
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_photo_list.php','action=process')) ?>");
	var form_id = form.id;
	var success_msm = "";
	var success_go_to = "";
	ajax_post_submit(url,form_id,success_msm,success_go_to);
}
</script>
 </div>
<?php
//网友评论 end
?>
<?php
}

?>