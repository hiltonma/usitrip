<?php
require('includes/application_top.php');



$photo_books_id = (int)$_GET['photo_books_id'];

$books_sql = tep_db_query('SELECT photo_books_id, photo_books_name FROM `photo_books` WHERE photo_books_id="'.$photo_books_id.'" ');
$books = tep_db_fetch_array($books_sql);
if(!(int)$books['photo_books_id']){
    die(db_to_html("相册不存在,或已经被删除"));
}


//相册名称
$books_name = db_to_html(tep_db_output($books['photo_books_name']));






?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<link rel="stylesheet" type="text/css" href="includes/new_travel_companion_index.css">



<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>
<script type="text/javascript" src="includes/javascript/usitrip-tabs-2009-06-19.js"></script>
<script type="text/javascript" src="includes/javascript/menujs-2008-04-15-min.js"></script>
<script type="text/javascript" src="includes/javascript/add_global.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="includes/ajx.js"></script>






<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script language="javascript">
<!--
<?php
$p=array('/&amp;/','/&quot;/');
$r=array('&','"');
?>


//-->
</script>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

    <!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
              <td class="pageHeading"><?php echo db_to_html('相册')?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
          <td>
              <?php
              /*相册start*/
              //取得相册的5张相片
                
                $where_exc = "";
                if((int)$_GET['photo_id']){
                        $photo_id5 = array();
                        $photo_id5[] = (int)$_GET['photo_id'];
                        $tmp_sql = tep_db_query('SELECT photo_id FROM `photo` WHERE photo_books_id="'.$photo_books_id.'" and photo_id < "'.(int)$_GET['photo_id'].'" Order By photo_id DESC Limit 2');
                        while($tmp_rows = tep_db_fetch_array($tmp_sql)){
                                $photo_id5[] = $tmp_rows['photo_id'];
                        }
                        $tmp1_sql = tep_db_query('SELECT photo_id FROM `photo` WHERE photo_books_id="'.$photo_books_id.'" and photo_id > "'.(int)$_GET['photo_id'].'" Order By photo_id ASC Limit 5');
                        while($tmp1_rows = tep_db_fetch_array($tmp1_sql)){
                                if(count($photo_id5)<5){
                                        $photo_id5[] = $tmp1_rows['photo_id'];
                                }
                        }

                        $where_exc = " and photo_id in(".implode(',',$photo_id5).") ";

                        $photo_id = (int)$_GET['photo_id'];
                }

                $photo_sql = tep_db_query('SELECT * FROM `photo` WHERE photo_books_id="'.$photo_books_id.'" '.$where_exc.' Order By photo_id Limit 5');

                $photos = array();
                $i_now = 0;
                while($photo_list = tep_db_fetch_array($photo_sql)){
                        $photos[] = array('id'=>$photo_list['photo_id'],'title'=>db_to_html($photo_list['photo_title']),'name'=>$photo_list['photo_name'], 'content'=> db_to_html($photo_list['photo_content']), 'customers_id'=> $photo_list['customers_id'], 'date'=> $photo_list['photo_update']);
                        if(!(int)$photo_id){ $photo_id = $photo_list['photo_id']; }
                        if($photo_id == $photo_list['photo_id']){
                                $i_now = max(0,(count($photos)-1));
                        }
                }

                //找出前一个、后一个相片ID
                $bakc_i = ($i_now-1);
                $next_i = ($i_now+1);
                $back_photo_id = $photos[$bakc_i]['id'];
                $next_photo_id = $photos[$next_i]['id'];
                //网友评论
                $comments_sql = tep_db_query('SELECT * FROM `photo_comments` WHERE photo_id="'.$photo_id.'" and has_remove!="1" Order By photo_comments_id ');
                $comments = array();
                while($comments_rows = tep_db_fetch_array($comments_sql)){
                        $comments[] = array('id'=> $comments_rows['photo_comments_id'],
                                                                'content'=> nl2br(db_to_html(tep_db_output($comments_rows['photo_comments_content']))),
                                                                'date'=>$comments_rows['added_time'],
                                                                'customers_id'=>$comments_rows['customers_id']);
                }
                $comments_num = count($comments);

              ?>
              <fieldset>
		  <legend align="left"><?php echo db_to_html($books_name);?></legend>
                  <table border="0" cellspacing="0" cellpadding="2">
             <tr>
                 <td valign="top">
                    
                        <table border="0" cellspacing="1" cellpadding="2">
                           <div class="jb_pic">
     <div class="jb_xc_ck">
        <div class="jb_xc_trun">
		<?php
		if((int)$back_photo_id){
			$back_links_href = tep_href_link('admin_photo_list.php','photo_books_id='.$photo_books_id.'&photo_id='.$back_photo_id.'#apictag');
		?>
		<a href="<?= $back_links_href;?>"><img id="left_turn_l_n" src="images/turn_l.gif" /></a>
		<?php
		}else{
		?>
		<img id="left_turn_l_n" src="images/turn_l_n.gif" />
		<?php
		}
		?>
		</div>
        <div class="jb_xc_img">
		<?php $photos_0_name = "images/photos/".$photos[$i_now]['name'];
                $photo_link = DIR_FS_CATALOG.$photos_0_name;
                $WH = getimgHW3hw_wh($photo_link,500,600);
                $wh_array=explode("@",$WH);
                ?>
		<a id="href_big_pic" href="<?= '/'.$photos_0_name?>" target="_blank"><?PHP echo tep_image('/'.$photos_0_name, '',$wh_array[0],$wh_array[1], 'id="big_pic"')?></a>
		</div>
        <div class="jb_xc_trun">
		<?php
		if((int)$next_photo_id){
			$next_links_href = tep_href_link('admin_photo_list.php','photo_books_id='.$photo_books_id.'&photo_id='.$next_photo_id.'#apictag');
		?>
		<a href="<?= $next_links_href;?>"><img id="right_turn_r" src="images/turn_r.gif" /></a>
		<?php
		}else{
		?>
		<img id="right_turn_r" src="images/turn_r_n.gif" />
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
	  <a href="<?= $back_links_href?>"><img id="top_turn_l_sn" src="images/turn_l_s.gif" /></a>
	  <?php
	  }else{
	  ?>
	  <img id="top_turn_l_sn" src="images/turn_l_sn.gif" />
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
			//$src_file_name = get_thumbnails('/'.$src_file_name);
                        $thumbnails_img = get_thumbnails(DIR_FS_CATALOG.$src_file_name);
                        $s = strlen(DIR_FS_CATALOG)-1;
                        $l = strlen($thumbnails_img);
                        $thumbnails_img = substr($thumbnails_img,$s, $l-$s);
			$selected_style = "";
			if($i_now == $i){
				$selected_style = " border:5px solid #FFCC01; ";
			}
		 ?>
		 <li id="small_pic_<?=$i?>" style="<?= $small_display?>"><a style="<?= $selected_style?>" href="<?= tep_href_link('admin_photo_list.php','photo_books_id='.$photo_books_id.'&photo_id='.$photos[$i]['id'].'#apictag')?>"><?PHP echo tep_image($thumbnails_img, '','100','')?></a></li>
         <?php
		 }
		 ?>
       </ul>
       <div class="jb_xc_trun_s">
	  <?php
	  if(tep_not_null($next_links_href)){
	  ?>
	  <a href="<?= $next_links_href?>"><img id="bottom_turn_r_s" src="images/turn_r_s.gif" /></a>
	  <?php
	  }else{
	  ?>
	  <img id="bottom_turn_r_s" src="images/turn_r_sn.gif" />
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
                $individual_space_link = tep_href_link('individual_space.php','customers_id='.$cus_id);
                $individual_space_link = eregi_replace('admin/', '',$individual_space_link);
                
		?>
		<a class="t_c" href="<?=$individual_space_link?>"><?= db_to_html($cus_name)?></a>&nbsp;<?= db_to_html($cus_genders)?> <?= db_to_html($upload_date.'上传');?>&nbsp;
		<?php
		
			$i = 0;
			$photo_box_images = get_thumbnails($photos_0_name);
		?>
		<a id="EditPotoA" href="JavaScript:void(0)" class="jb_fb_tc_bt_a" onclick="showDiv('EditPotoDiv');"><?= db_to_html('编辑');?></a>&nbsp;
		<a href="JavaScript:void(0)" class="jb_fb_tc_bt_a" id="del_a"><?= db_to_html('删除');?></a>

		
		<?php
		
		?>

		</p>
		</div>
 </div>
                        </table>
	          
                 </td>




          </tr>
        </table>

              </fieldset>
               





          </td>
      </tr>
      <tr><td>
              <fieldset>
		  <legend align="left"><?php echo db_to_html('评论回复');?></legend>
          
        
          <table border="0" width="100%" cellspacing="1" cellpadding="2">
                  <tr class="dataTableHeadingRow">
                    <td colspan="5" nowrap="nowrap" class="dataTableHeadingContent"><?= db_to_html('网友评论（<span id="comments_num">'.$comments_num.'</span>条）')?></td>
                    </tr>
                    <tr class="dataTableHeadingRow">
                        <td class="dataTableHeadingContent" nowrap="nowrap">回帖人</td>
                        <td class="dataTableHeadingContent" nowrap="nowrap">回帖内容</td>
                        <td class="dataTableHeadingContent" nowrap="nowrap">时间</td>
                        <td class="dataTableHeadingContent" nowrap="nowrap">操作</td>
                 </tr>
                  
          
          <?php
		  if($comments_num){
		  	for($i=0; $i<$comments_num; $i++){
		  		$customers_id = $comments[$i]['customers_id'];
				$customers_name = db_to_html(tep_customers_name($customers_id));
				$gender = tep_customer_gender($customers_id);
				$customers_genders = db_to_html(tep_get_gender_string($gender, 1));
				
				if(strtolower($gender)=='m' || $gender=='1'){ $head_img = "tx_b_s.gif"; }
				if(strtolower($gender)=='f' || $gender=='2'){ $head_img = "tx_g_s.gif"; }
				
				$customers_links_href = tep_href_link('individual_space.php','customers_id='.$customers_id);
                                $bg_color = "#ECFFEC";
				if((int)$i %2 ==0){
					$bg_color = "#F0F0F0";
				}
                                
		  ?>
		  
             
              <tr class="dataTableRow" style="cursor:auto; background-color:<?= $bg_color;?>">
                  <td class="dataTableContent"><?php echo tep_db_output($customers_name).'&nbsp;&nbsp;'.$customers_id?></td>
                  <td class="dataTableContent"><?php echo nl2br(tep_db_output($comments[$i]['content']))?></td>
                  <td class="dataTableContent"><?php echo tep_db_output($comments[$i]['date'])?></td>
                  <td class="dataTableContent"><a href="JavaScript:void(0)" onclick="admin_remove_photo_comments(<?= $comments[$i]['id']?>);" class="jb_fb_tc_bt_a"><?= db_to_html('[删除]');?></a></td>
              </tr>
              
              
                
          <?php
			}
		  }
		  ?>
               </table>
         
               </fieldset>
          </td></tr>
      

    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->
<div id="EditPotoDiv" class="center_pop" style="display:none">
		<form id="EditPotoForm" action="" method="post" enctype="multipart/form-data">
		<div>
		<div class="jb_fb_tc_bt"><h3 id="action_h3"><?= db_to_html('编辑');?></h3><button class="icon_fb_bt" onclick="closeDiv('EditPotoDiv')" title="<?= db_to_html('关闭')?>" type="button"></button></div>
		<div class="jb_fb_tc_tab">
		<div class="sc_none">
			<div class="sc_item">
				<?= tep_draw_hidden_field('photo_name',$photos[$i_now]['name'],' id="photo_name" title="'.db_to_html('请上传相片').'" ');?>
				</div>

			
		</div>
				   <div class="sc_text">
					<div class="sc_item"><p><?= db_to_html('标题：')?><?= tep_draw_input_field('photo_title',strip_tags($photos[$i_now]['title']),' class="text5" title="'.db_to_html('请输入相片标题').'" ');?></p></div>
					<div class="sc_item"><p><span class="v_top"><?= db_to_html('内容：')?></span><?= tep_draw_textarea_field('photo_content','virtual',50,5,strip_tags($photos[$i_now]['content']),' class="textarea4" title="'.db_to_html('请输入对照片的描述或旅游到此的心情或感受以及所见所闻。').'"');?>
					<br />
					<?php echo tep_draw_hidden_field('update_photo_action',"true");?>
					<?php echo tep_draw_hidden_field('photo_books_id',$photo_books_id);?>
					<?php echo tep_draw_hidden_field('photo_id',$photo_id);?>

					<button class="jb_fb_all" id="submit_photo_button" type="submit" style="margin-left:36px; margin-top:10px;"><?php echo db_to_html('确定')?></button>
					<img style="display: none;" src="image/snake_transparent.gif" id="load_icon">
					</p></div>
				</div>
			</div>
		</div>
		</form>
		</div>

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php
$p=array('/&amp;/','/&quot;/');
$r=array('&','"');


?>
<script type="text/JavaScript">
<!--
jQuery(document).ready(function(){
	<?php //点击编辑按钮?>
	$("#EditPotoA").click( function(){
		$("#EditPotoDiv").attr('style', function(){
			return this.style.display = "";
		});
		return false;
	});
	<?php //提交相片编辑?>
	$("#EditPotoForm").submit( function(){
		var error = false;
		var error_msn = "";
		for(i=0; i<this.elements.length; i++){
			if((this.elements[i].value==this.elements[i].title || this.elements[i].value.leng<2) && this.elements[i].title!=""){
				error = true;
				error_msn += "* "+ this.elements[i].title+"\t\n";
			}
		}
		if(error == true){
			alert(error_msn);
			return false;
		}
		var Submit_Photo_Button = document.getElementById("submit_photo_button");
		var Load_Icon = document.getElementById("load_icon");
		//Submit_Photo_Button.disabled = true;
		Load_Icon.style.display = "";
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_manage_individual_space_photo.php','action=update')) ?>");
		var form_id = this.id;
		var success_msm = "";
		var success_go_to = "";
		ajax_post_submit(url,form_id,success_msm,success_go_to);
		return false;
	});
	<?php //删除相片?>
	$("#del_a").click( function(){
		if(confirm("<?php echo db_to_html("您确实要删除这张相片吗？");?>")){
			var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_manage_individual_space_photo.php','action=del')) ?>");
			url += "&photo_id=<?= (int)$photo_id?>";
			ajax_get_submit(url);
		}
		return false;
	});

});





function admin_remove_photo_comments(t_id){
	if(confirm("<?php echo db_to_html("您确定要删除这条评论吗？")?>")){
		//var aid = "#comments_"+comments_id;
		//$(aid).fadeOut(1000);
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_manage_individual_space_photo.php','action=remover_comments')) ?>");
		url += "&comments_id="+t_id;
                url += "&photo_id=<?php echo (int)$photo_id;?>";
	        var success_msm = "";
		var success_go_to = "";
		ajax_get_submit(url,success_msm,success_go_to);
	}
}

-->
</script>
<?php

//photo_list页面的jquery代码 end
?>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

