<?php
require('includes/application_top.php');
$travel_notes_id = (int)$_GET['travel_notes_id'];
$notes_sql = tep_db_query('SELECT * FROM `travel_notes` WHERE travel_notes_id ="'.$travel_notes_id.'" LIMIT 1 ');
$notes = tep_db_fetch_array($notes_sql);
if(!(int)$notes['travel_notes_id']){
	echo db_to_html('<a href="'.tep_href_link('index.php').'">不存在的游记或可能已经被删除！</a>'); exit;
}
$p_href = tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $notes['products_id']);
$notes_title = db_to_html(tep_db_output($notes['travel_notes_title']));
$notes_date = chardate($notes['added_time'],"I",1);
$photo_ids = $notes['photo_ids'];
$comment_num = (int)$notes['comment_num'];
$notes_author_id = (int)$notes['customers_id'];
$notes_author_name = tep_customers_name($notes_author_id);
$notes_author_genders = tep_get_gender_string(tep_customer_gender($notes_author_id), 1);








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
              <td class="pageHeading" id="travel_title"><?= $notes_title;?></td><td><a  href="JavaScript:void(0)"  onclick="showDiv('EditTravelDiv');"><font color="blue"><?php echo '&nbsp;&nbsp;'.db_to_html('编辑');?></font></a></td><td><a href="JavaScript:void(0)" onclick="del_travel_notes(<?= (int)$travel_notes_id?>);"><font color="blue"><?php echo db_to_html('删除');?></font></a></td>
              
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
          <td>
              <fieldset>
                  <legend align="left"><?php echo db_to_html('审核查看游记');?></legend>
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
                $img_src_bk= 'images/photos/'.tep_db_output($photo['photo_name']);
		$img_src = '/images/photos/'.tep_db_output($photo['photo_name']);
                $img_link = DIR_FS_CATALOG.$img_src_bk;
                $individual_space_link = tep_href_link('individual_space.php','customers_id='.$notes_author_id);
                $individual_space_link = eregi_replace('admin/', '',$individual_space_link);

                 echo tep_draw_form_front('EditPotoForm_'.$photo_id,'','post','id=EditPotoForm_'.$photo_id.' style="display:none"');
                 echo tep_draw_hidden_field('update_photo_action',"true");
		 echo tep_draw_hidden_field('photo_books_id',$photo_books_id);
	         echo tep_draw_hidden_field('photo_id',$photo_id);
                 echo tep_draw_hidden_field('photo_name',$photo['photo_name']);
                 echo tep_draw_hidden_field('photo_title',db_to_html($photo['photo_title']));
                 echo tep_draw_textarea_field('photo_content','virtual',50,5,db_to_html(strip_tags($photo['photo_content'])),' class="textarea4" style="display:none"');
                 echo '</form>';

		
?>
	 <a id="href_big_pic_<?=$photo_id?>"href="<?= $img_src?>" target="_blank"><img id="big_pic_<?=$photo_id?>" class="jb_grzx_yj_mar" src="<?= $img_src?>" <?php echo getimgHW3hw($img_link,500,600)?> /></a>
     <h3 class="jb_grzx_yj_mar" id="photo_title_<?=$photo_id?>"><?= db_to_html(tep_db_output($photo['photo_title']))?></h3>
     <p class="jb_grzx_yj_mar" id="photo_content_<?=$photo_id?>"><?= nl2br(db_to_html(tep_db_output($photo['photo_content'])))?></p>
     <div class="jb_item_1_l">
		<p class="col_5" id="photo_p_<?=$photo_id?>">
                    <a class="t_c" href="<?=$individual_space_link?>"><font color="blue"><?= db_to_html($notes_author_name)?></font></a>&nbsp;<?= db_to_html($notes_author_genders)?> <?= db_to_html($notes_date.'发表');?>&nbsp;&nbsp;
		<?php
	          $photo_box_images = get_thumbnails($img_src);
		?>
                <a id="EditPotoA_<?=$photo_id?>" href="JavaScript:void(0)" class="jb_fb_tc_bt_a" onclick="show_edit_travle('EditPotoForm_<?=$photo_id?>','<?=$photo_box_images?>');"><font color="blue"><?= db_to_html('编辑');?></font></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="JavaScript:void(0)" class="jb_fb_tc_bt_a" id="del_a_<?=$photo_id?>" onclick="remove_photo_in_notos()"><font color="blue"><?= db_to_html('删除');?></font></a>


		</p>
		</div>
<?php

	}

}
?>
</div>
		  
                  
                  

              </fieldset>
          </td>
      </tr>
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
                  <td class="dataTableContent"><a href="JavaScript:void(0)" onclick="admin_remove_notos_comments(<?= $comments[$i]['travel_notes_comments_id']?>);" class="jb_fb_tc_bt_a"><?= db_to_html('[删除]');?></a></td>
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
<!--编辑游记标记弹出层start-->
<div id="EditTravelDiv" class="center_pop" style="display:none">
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
</div>
<!--编辑游记标记弹出层end-->

<!--游记修改 弹出窗口 start-->
<div id="EditPotoDiv" class="center_pop" style="display:none">
                    <form id="EditPotoForm" action="" onsubmit="edite_travel_notes();return false;" method="post" enctype="multipart/form-data">
		<div>
		<div class="jb_fb_tc_bt"><h3 id="action_h3"><?= db_to_html('编辑游记');?></h3><button class="icon_fb_bt" onclick="closeDiv('EditPotoDiv')" title="<?= db_to_html('关闭')?>" type="button"></button></div>
		<div class="jb_fb_tc_tab">
		<div class="sc_none">
                    <div class="sc_item"><a href="JavaScript:void(0)" onclick="load_upload_module(0)"><img id="photo_box_0" src="" width="" height="" /></a>
				<?= tep_draw_hidden_field('photo_name',$photo['photo_name'],' id="photo_name" title="'.db_to_html('请上传相片').'" ');?>
				</div>

			
		</div>
				   <div class="sc_text">
					<div class="sc_item"><p><?= db_to_html('标题：')?><?= tep_draw_input_field('photo_title',strip_tags($photo['photo_title']),' class="text5" title="'.db_to_html('请输入相片标题').'" onFocus="Check_Onfocus(this)" onBlur="Check_Onblur(this)"');?></p></div>
					<div class="sc_item"><p><span class="v_top"><?= db_to_html('内容：')?></span><?= tep_draw_textarea_field('photo_content','virtual',50,5,strip_tags($photo['photo_content']),' class="textarea4" title="'.db_to_html('请输入对照片的描述或旅游到此的心情或感受以及所见所闻。').'" onFocus="Check_Onfocus(this)" onBlur="Check_Onblur(this)" ');?>
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
</div>
<!--游记修改 弹出窗口 end-->
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
function show_edit_travle(form_id,img_src){
    var fForm = document.getElementById(form_id);
    var tForm = document.getElementById("EditPotoForm");
    document.getElementById("photo_box_0").setAttribute('src',img_src);
    tForm.elements['photo_name'].value =fForm.elements['photo_name'].value;
    tForm.elements['photo_title'].value =fForm.elements['photo_title'].value;
    tForm.elements['photo_content'].value =fForm.elements['photo_content'].value;
    tForm.elements['update_photo_action'].value =fForm.elements['update_photo_action'].value;
    tForm.elements['update_photo_action'].value =fForm.elements['update_photo_action'].value;
    tForm.elements['photo_id'].value =fForm.elements['photo_id'].value;
    //tForm.elements['photo_box'].setAttribute('src',img_src);

    showDiv("EditPotoDiv");

}
function edite_travel_notes(){
        var form_id = document.getElementById("EditPotoForm");
	var error_msn = '';
	var error = false;

	for(i=0; i<form_id.elements.length; i++){
			if((form_id.elements[i].value==form_id.elements[i].title || form_id.elements[i].value.leng<2) && form_id.elements[i].title!=""){
				error = true;
				error_msn += "* "+ form_id.elements[i].title+"\t\n";
			}
        }
	if(error==true){
		alert(error_msn);
		return false;
        }
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_admin_travel_notes_detail.php','action=update')) ?>");
                var form_id = "EditPotoForm";
		var success_msm = "";
		var success_go_to = "";
		ajax_post_submit(url,form_id,success_msm,success_go_to);
                return false;

}

function edite_travel_notes_title(){
        var form_id = document.getElementById("EditTravelForm");
	var error_msn = '';
	var error = false;

	for(i=0; i<form_id.elements.length; i++){
			if((form_id.elements[i].value==form_id.elements[i].title || form_id.elements[i].value.leng<2) && form_id.elements[i].title!=""){
				error = true;
				error_msn += "* "+ form_id.elements[i].title+"\t\n";
			}
        }
	if(error==true){
		alert(error_msn);
		return false;
        }
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_admin_travel_notes_detail.php','action=edite_travel_title')) ?>");
                 var form_id = "EditTravelForm";
		var success_msm = "";
		var success_go_to = "";
		ajax_post_submit(url,form_id,success_msm,success_go_to);
                return false;

}

function admin_remove_notos_comments(comments_id){
    if(confirm("<?php echo db_to_html("您确定要删除这条评论吗？")?>")){
		//var aid = "#comments_"+comments_id;
		//$(aid).fadeOut(1000);
		var url =url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_admin_travel_notes_detail.php','action=remove_comments')) ?>");
		url += "&comments_id="+comments_id;
                url += "&travel_notes_id=<?php echo (int)$_GET['travel_notes_id'];?>";
		var success_msm = "";
		var success_go_to = "";
		ajax_get_submit(url,success_msm,success_go_to);
     }
}

function remove_photo_in_notos(){
    if(confirm("<?php echo db_to_html("您确实要删除这张相片吗？");?>")){
			var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_admin_travel_notes_detail.php','action=del')) ?>");
			url += "&photo_id=<?= (int)$photo_id?>";
			ajax_get_submit(url);
		}

}
function del_travel_notes(notte_id){	//删除游记
	if(confirm("<?php echo db_to_html("删除这个游记，将会同时删除该游记下面的所有评论，确定删除吗？");?>")){
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_admin_travel_notes_detail.php','action=del_travel_notes')) ?>");
		url += "&travel_notes_id="+notte_id;
		ajax_get_submit(url);
	}
	return false;
}

-->
</script>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
