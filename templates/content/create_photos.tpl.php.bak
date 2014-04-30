<?php
if((int)$products_id){
?>
<br></br>

<a class="buletext" href="<?= tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_id)?>" target="_blank"><?= db_to_html(tep_get_products_name($products_id))?></a>
    
<?php
}
?>
<!--上传相片 的弹出层start-->       
<div id="travel_companion_tips_20121221" class="jb_fb_tcAddXx" style="display:none;">
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
//$save_dir = DIR_FS_CATALOG.'tmp/';
$width_height_px = '上传照片支持格式jpg,gif,png，每张图片大小不超过'.$file_size.'KB!';
$need_up_form_id = 'photos_form'; //提交完成后需要更新的表单id
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
	var pForm = document.getElementById("photos_form");
	//fForm.elements['save_file_name'].value = "<?= date('YmdHis').'_'.$customer_id.'_'?>"+i+"_random_"+randNum;
	fForm.elements['need_up_img_id'].value = "photo_box_"+i;
	fForm.elements['need_up_form_input_name'].value = "photo_name["+i+"]";
	fForm.elements['FileDomain'].value = "";
	show_travel_companion_tips(1,20121221);
}

//删除指定的图片(假删除)
function remove_photos_from_input_box(i){
	var box = document.getElementById("photo_name["+i+"]");
	var photo_box = document.getElementById("photo_box_"+i);
	box.value = "";
	photo_box.src = "image/photo_none.gif";
	
}

//提交图片信息
function submit_photos(form_obj){
	var form = form_obj;
	//检查
	var error = false;
	var error_sms = '';
	if(form.elements['photo_books_id'].value<1){
		error = true;
		error_sms += "* " + '<?php echo db_to_html('请选择相册！');?>'+"\n\n";
                form.elements['photo_books_id'].focus();
	}
	if(form.elements['photo_title[0]'].value.length<1){
		error = true;
		error_sms += "* " + '<?php echo db_to_html('标题 不能为空！');?>'+"\n\n";
                //form.elements['photo_title[0]'].focus();
	}
	if(form.elements['photo_content[0]'].value.length<1){
		error = true;
		error_sms += "* " + '<?php echo db_to_html('内容 不能为空！');?>'+"\n\n";
                //form.elements['photo_content[0]'].focus();

	}
	if(form.elements['photo_title[0]'].value == form.elements['photo_title[0]'].title){
		error = true;
		error_sms += "* " + form.elements['photo_title[0]'].title+"\n\n";
                form.elements['photo_title[0]'].focus();
	}
	if(form.elements['photo_content[0]'].value == form.elements['photo_content[0]'].title){
		error = true;
		error_sms += "* " + form.elements['photo_content[0]'].title+"\n\n";
                form.elements['photo_content[0]'].focus();
	}
	
	if(form.elements['photo_name[0]'].value.length<1){
		error = true;
		error_sms += "* " + '<?php echo db_to_html('相片 不能为空！');?>'+"\n\n";
               
	}
	if(document.getElementById('products_id')!=null && form.elements['products_id'].checked == true){
		if(form.elements['products_id'].value<1){
			error = true;
			error_sms += "* " + '<?php echo db_to_html('您已选择同时以游记的形式发出，请选择您去过的地方！');?>'+"\n\n";
                        form.elements['products_id'].focus();
		}
		if(form.elements['p_name'].value.length<2){
			error = true;
			error_sms += "* " + '<?php echo db_to_html('您已选择同时以游记的形式发出，请为游记填写标题！');?>'+"\n\n";
                        form.elements['p_name'].focus();
		}
	}
	
	
	
	if(error == true){
		alert(error_sms);
		return false;
	}
	var Submit_Photo_Button = document.getElementById("submit_photo_button");
	var Load_Icon = document.getElementById("load_icon");
	Submit_Photo_Button.disabled = true;
	Load_Icon.style.display = "";
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_create_photos.php','action=process')) ?>");
	var form_id = form.id;
	var success_msm = "";
	var success_go_to = "";

	ajax_post_submit(url,form_id,success_msm,success_go_to);
}

//显示更多上传框
function show_more_module(num){
	var li = document.getElementById("ul_top").getElementsByTagName("li");
	var beigin_num = 0;
	for(i=0; i<li.length; i++){
		if(li[i].className == "ul_li_photo_list" && li[i].style.display=="none"){
			beigin_num++;
			li[i].style.display = "";
			if(num==beigin_num){ break; }
		}
	}
	
}

//
function setup_products_id_box(obj,pid){
	var box = document.getElementById("products_id");
	box.checked = true;
	box.value = pid;
	var P_Name = document.getElementById("p_name_1");
	P_Name.style.display = "";
	P_Name.value = obj.innerHTML;
	closeDivS('select_my_tours');
}
function set_p_name(obj){
	var P_Name_1 = document.getElementById("p_name_1");
        var P_Name_2= document.getElementById("p_name_2");
	if(obj.checked == true){
		P_Name_1.style.display = "";
                P_Name_2.style.display = "";
	}else{
		P_Name_1.value = "";
                P_Name_2.value = "";
		P_Name_1.style.display = "none";
                P_Name_2.style.display = "none";
	}
}
</script>

<?php
//创建相册功能
require_once('ajax_create_album.php');
?>

<?php
  if ($messageStack->size('create_photos') > 0) {
?>
	  <div><?php echo $messageStack->output('create_photos'); ?></div>
	 
<?php
  }
?>

		
<div class="jb_grzx_yj">
<form action="" method="post" enctype="multipart/form-data" name="photos_form" id="photos_form" onsubmit="submit_photos(this);return false;">
    <h3 class="jb_grzx_yj_mar"><?= db_to_html('请选择相册：')?></h3>
    <ul id="ul_top">
        <li>
		<?php
		echo tep_draw_pull_down_menu('photo_books_id',$books_option);
		?>
		&nbsp;&nbsp;&nbsp;<img src="image/icons/cr_new.gif" />
		<a onclick="create_album();" class="jb_fb_tc_bt_a" href="JavaScript:void(0)"><?= db_to_html('创建新相册')?></a>
		</li>
        <li><p class="fl_r"><?= db_to_html($width_height_px)?></p></li>
        <?php
		for($i=0; $i<10; $i++){
			$display_li = "";
			if($i>=3){ $display_li = ' style="display: none;" '; }
		?>
		<li class="ul_li_photo_list" <?= $display_li?>>
		   <div class="sc_none">
                       <div class="sc_item"><a href="JavaScript:void(0)" onclick="load_upload_module(<?= $i?>)"><img id="photo_box_<?= $i?>" src="image/photo_none.gif" height="109" width="145" /></a>
			   <?= tep_draw_hidden_field('photo_name['.$i.']','',' id="photo_name['.$i.']" ');?>
			   </div>
			   
			   <div class="sc_item a_mid"><a href="JavaScript:void(0)" onclick="load_upload_module(<?= $i?>)" class="jb_fb_tc_bt_a"><?= db_to_html('换张照片')?></a>&nbsp;&nbsp;&nbsp;<a href="JavaScript:void(0)" onclick="remove_photos_from_input_box(<?= $i?>);" class="jb_fb_tc_bt_a"><?= db_to_html('删除')?></a></div>
		   </div>
		   <div class="sc_text">
			   <div class="sc_item"><p><?= db_to_html('标题：')?><?= tep_draw_input_field('photo_title['.$i.']',db_to_html('请输入相片标题'),' class="text5" title="'.db_to_html('请输入相片标题').'" onFocus="Check_Onfocus(this)" onBlur="Check_Onblur(this)"');?></p></div>
			   <div class="sc_item"><p><span class="v_top"><?= db_to_html('内容：')?></span><?= tep_draw_textarea_field('photo_content['.$i.']','',50,5,db_to_html('请输入对照片的描述或旅游到此的心情或感受以及所见所闻。'),' class="textarea4" title="'.db_to_html('请输入对照片的描述或旅游到此的心情或感受以及所见所闻。').'" onFocus="Check_Onfocus(this)" onBlur="Check_Onblur(this)" ');?></p></div>
		   </div>
		</li>
        
		<?php
		}
		?>
        
        <li>
            <div class="sc_more"><a href="JavaScript:void(0)" onclick="show_more_module(1);" class="jb_fb_tc_bt_a"><?= db_to_html('再传一张照片')?></a>&nbsp;&nbsp; <img src="image/icons/up.gif"  class="m_l" />&nbsp;<a href="JavaScript:void(0)" class="jb_fb_tc_bt_a"><?= db_to_html('上传更多照片')?></a>&nbsp;
			<select name="tmp_select" id="tmp_select" onchange="show_more_module(this.value)">
            	<?php for($i=1; $i<8; $i++){?>
				<option value="<?= $i?>"><?= db_to_html('添加'.$i.'张');?></option>
				<?php }?>
            </select>
			
			</div>
        </li>
<?php
//我去过的地方
$my_tours_sql = tep_db_query('SELECT op.products_id FROM `orders` o, `orders_products` op WHERE o.customers_id ="'.$customer_id.'" AND o.orders_status = "100006" AND op.orders_id = o.orders_id Group By op.products_id ');
$my_tours = tep_db_fetch_array($my_tours_sql);
$my_tours_p = array();

if((int)$my_tours['products_id']){
do{
	$my_tours_p[] = (int)$my_tours['products_id'];
}while($my_tours = tep_db_fetch_array($my_tours_sql));

?>
        <li>
            <div class="sc_more">

			<?php
			if((int)$_GET['products_id']){
				$products_id = (int)$_GET['products_id'];
				$products_checked = true;
				//$p_name = tep_get_products_name($products_id);
                                $p_name = "";
				$p_name_display = "";
			}else{
				$products_id = 0;
				$products_checked = false;
				$p_name = "";
				$p_name_display ="display:none";
			}
			

			?>
                        <?php
                        if(!(int)$_GET['products_id']){
                            echo tep_draw_checkbox_field('products_id',$products_id, $products_checked, ' id="products_id" onclick="set_p_name(this)" ');?>
			&nbsp;<?= db_to_html('同时以游记的形式发出 ')?> <a href="JavaScript:void(0)" class="jb_fb_tc_bt_a" onclick="showDivS('select_my_tours')"><?= db_to_html('选择我去过的地方')?></a><br />
                            <input id="p_name_1" style="border:1px solid #FFFFFF; <?php echo $p_name_display;?> "  name="p_name_1" class="text5" title="<?= db_to_html('我去过的地方')?>" value="<?= db_to_html($p_name);?>" readonly="readonly" />
                       <?php
                        }else{
                             echo tep_draw_checkbox_field('products_id',$products_id, $products_checked, ' id="products_id" onclick="set_p_name(this)" style="display: none;" ');
                        }
                        ?>
                            <br /><br />
                            <div><p id="p_name_2" style="<?php echo $p_name_display;?> "><?= db_to_html('游记标题：')?><?php echo tep_draw_input_field('p_name','','id="p_name" "class="text4" style="width:370px;" ');?></p></div>
			</div>
        </li>



<?php
}
?>
        <li>
            <div class="sc_more a_mid"><button type="submit" class="jb_fb_all" id="submit_photo_button" > <?= db_to_html('确定');?></button><img id="load_icon" src="image/snake_transparent.gif" style="display:none" /></div>
        </li>
           
    </ul>
 </form>
 </div>

<?php
if((int)count($my_tours_p)){
?>
<!--我去过的地方 start-->
<div id="select_my_tours" class="jb_fb_tcAddXx" style="text-decoration:none; display:none; width:620px; height:200px; overflow:auto;">
<?php echo tep_pop_div_add_table('top');?>
<div>
     <div class="jb_fb_tc_bt">
       <h3 id="action_h3"><?php echo db_to_html('选择我去过的地方')?></h3>&nbsp;&nbsp;
	   <button type="button" title="<?php echo db_to_html('关闭');?>" onclick="closeDivS('select_my_tours')" class="icon_fb_bt"/></button>
    </div>
     <div class="jb_fb_tc_tab">
      <table>
	  <?php for($i=0; $i<count($my_tours_p); $i++){?>
	  <tr>
	  <td><?= ($i+1).". ";?><a href="JavaScript:void(0)" onclick="setup_products_id_box(this, <?= $my_tours_p[$i]?>)"><?= db_to_html(tep_get_products_name($my_tours_p[$i]));?></a>
	  </td>
	  </tr>
	  <?php
	  }
	  ?>
	  </table> 
     </div>
</div>
<?php echo tep_pop_div_add_table('foot');?>
</div>
<!--我去过的地方 end-->
<?php
}
?>