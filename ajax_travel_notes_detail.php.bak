<?php
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );

$p=array('/&amp;/','/&quot;/');
$r=array('&','"');

require_once('includes/application_top.php');
require_once(DIR_FS_INCLUDES . 'ajax_encoding_control.php');

//检测用户
if(!tep_session_is_registered('customer_id') || !(int)$customer_id){
	if(!tep_not_null($_POST['password'])){
		echo db_to_html('[ERROR]请输入您的登录密码！[/ERROR]');
		exit;
	}
	if(!tep_not_null($HTTP_GET_VARS['action'])){ $HTTP_GET_VARS['action'] = 'process';}else{ $old_action = $HTTP_GET_VARS['action']; $HTTP_GET_VARS['action'] = 'process'; }
	$ajax = $_POST['ajax'];
	include('login.php');
	if(tep_not_null($old_action)){
		$HTTP_GET_VARS['action'] = $old_action;
	}
}
//删除游记评论start tom added 2010-7-7
if($_GET['action']=='remove_comments'&& $error == false){
       if((int)$_GET['comments_id']){
          tep_db_query('UPDATE travel_notes_comments SET has_remove="1" WHERE travel_notes_comments_id="'.(int)$_GET["comments_id"].'" AND travel_notes_id="'.(int)$_GET["travel_notes_id"].'" ');
          tep_db_query('UPDATE `travel_notes` SET `comment_num` =( comment_num -1) WHERE `travel_notes_id` = "'.(int)$_GET["travel_notes_id"].'"');
	  $comments_sql = tep_db_query('SELECT count(*) as total FROM `travel_notes_comments` WHERE travel_notes_id="'.(int)$_GET["travel_notes_id"].'" and has_remove!="1" ');
          $comments = tep_db_fetch_array($comments_sql);
          $sms = '[JS] var aid = "#comments_'.(int)$_GET["comments_id"].'"; $(aid).fadeOut(1200); $("#comments_num").text("'.$comments['total'].'"); [/JS]';
          echo db_to_html($sms);
       }
       exit;
}
//删除游记评论end
//编辑游记题目start
if($_GET['action']=='edite_travel_title'&& $error == false && $_POST['update_travel_notos_action']=="true"){
       if((int)$_POST['travel_notes_id']){
           $travel_title = tep_db_prepare_input($_POST['travel_title']);
           if(!tep_not_null($travel_title)){
               echo db_to_html('[ERROR]请输入您的游记题目！[/ERROR]');
           }else{
               tep_db_query('UPDATE `travel_notes` SET `travel_notes_title` = "'.html_to_db(ajax_to_general_string($travel_title)).'" WHERE `travel_notes_id` = "'.(int)$_POST['travel_notes_id'].'"');
               $sms ='
                 var Submit_Travel_Button = document.getElementById("submit_travel_button"); if(Submit_Travel_Button!=null){ Submit_Travel_Button.disabled = false;}
                 closeDiv("EditTravelDiv");
                 $("#travel_title").hide();
                 $("#load_icon").css({display: "none" });
                 $("#travel_title").html("'.tep_db_output(html_to_db(ajax_to_general_string($travel_title))).'");
                 $("#travel_title").slideDown(600);';
               $sms = '[JS]'.preg_replace('/[[:space:]]+/',' ',$sms).'[/JS]';
               echo db_to_html($sms);
           }
       }
      exit;

}
//编辑游记题目end
//编辑相片 start
if($_GET['action']=='update' && $error == false && $_POST['update_photo_action']=="true"){
        $photo_name = tep_db_prepare_input($_POST['photo_name']);
	$photo_title = tep_db_prepare_input($_POST['photo_title']);
	$photo_content = tep_db_prepare_input($_POST['photo_content']);
	//先检查原相片资料
        //$sms ='[JS]alert(1111);[/JS]';
        //echo $sms;
	$check_sql = tep_db_query('select photo_name,photo_books_id from photo where photo_id ="'.(int)$_POST['photo_id'].'" ');
	$check_row = tep_db_fetch_array($check_sql);
	$old_photo_name = tep_db_output($check_row['photo_name']);
	$photo_books_id = (int)$check_row['photo_books_id'];

	$sql_data_array = array('photo_title' => ajax_to_general_string($photo_title),
							'photo_content' => ajax_to_general_string($photo_content),
							'photo_name' => $photo_name,
							'photo_update' => date('Y-m-d H:i:s'));
	@rename(DIR_FS_CATALOG.'tmp/'.$photo_name, DIR_PHOTOS_FS_IMAGES.$photo_name);
	$sql_data_array = html_to_db($sql_data_array);
	tep_db_perform('photo', $sql_data_array, 'update', ' photo_id="'.(int)$_POST['photo_id'].'" and customers_id="'.(int)$customer_id.'" ');
	out_thumbnails(DIR_PHOTOS_FS_IMAGES.$photo_name, DIR_PHOTOS_FS_IMAGES."thumb_".$photo_name, 145, 109);
	//$messageStack->add_session('update_photos', db_to_html('您的相片更新成功！').$photo_name, 'success');
	if($old_photo_name!=$photo_name && tep_not_null($old_photo_name)){
		@unlink(DIR_PHOTOS_FS_IMAGES.$old_photo_name);	//删除旧图
		@unlink(DIR_PHOTOS_FS_IMAGES."thumb_".$old_photo_name);	//删除旧缩略图
	}
	//将相片写到相册封面
	tep_db_query('UPDATE `photo_books` SET `photo_books_cover` = "'.$photo_name.'" WHERE `photo_books_id` = "'.$photo_books_id.'" LIMIT 1 ;');
	$img_wh = explode('@',getimgHW3hw_wh(DIR_PHOTOS_FS_IMAGES.$photo_name,500,375));
         
	$sms = '
	var Submit_Photo_Button = document.getElementById("submit_photo_button"); if(Submit_Photo_Button!=null){ Submit_Photo_Button.disabled = false;}
	closeDiv("EditPotoDiv");
	$("#photo_title_'.$_POST['photo_id'].'").hide();
	$("#photo_content_'.$_POST['photo_id'].'").hide();
        $("#big_pic_'.$_POST['photo_id'].'").hide();
        $("#load_icon").css({display: "none" });
	$("#photo_title_'.$_POST['photo_id'].'").html("'.tep_db_output(html_to_db(ajax_to_general_string($photo_title))).'");
	$("#photo_title_'.$_POST['photo_id'].'").slideDown(600);
	$("#photo_content_'.$_POST['photo_id'].'").html("'.nl2br(tep_db_output(html_to_db(ajax_to_general_string($photo_content)))).'");
	$("#photo_content_'.$_POST['photo_id'].'").slideDown(600);
	$("#big_pic_'.$_POST['photo_id'].'").attr("src","images/photos/'.$photo_name.'");
	$("#big_pic_'.$_POST['photo_id'].'").attr("width","'.$img_wh[0].'");
	$("#big_pic_'.$_POST['photo_id'].'").attr("height","'.$img_wh[1].'");
	$("#big_pic_'.$_POST['photo_id'].'").fadeIn(600);
	$("#href_big_pic_'.$_POST['photo_id'].'").attr("herf","images/photos/'.$photo_name.'");
	';
	$sms = '[JS]'.preg_replace('/[[:space:]]+/',' ',$sms).'[/JS]';
	echo db_to_html($sms);
        //echo '1111';
	exit;
}
//编辑相片 end

if($_GET['action']=='process' && $error == false){	//提交游记评论
	$error = false;
	$error_sms = '';
	$comments = tep_db_prepare_input($_POST['tcomments']);
	if(!tep_not_null($comments)){
		$error = true;
		$error_sms .= db_to_html('请输入评论内容。\n\n');
	}
	$travel_notes_id = (int)$_POST['travel_notes_id'];
	if(!(int)$travel_notes_id){
		$error = true;
		$error_sms .= db_to_html('Not travel_notes_id. \n\n');
	}
	if(!(int)$customer_id){
		$error = true;
		$error_sms .= db_to_html('Not customer_id. \n\n');
	}
	if($error == true){
		echo '[ERROR]'.	preg_replace('/[[:space:]]+/',' ',$error_sms).'[/ERROR]';
		exit;
	}
	$sql_data_array = array('comments' => ajax_to_general_string($comments),
							'travel_notes_id' => $travel_notes_id,
							'customers_id' => $customer_id,
							'added_time' => date('Y-m-d H:i:s'));
	$sql_data_array = html_to_db($sql_data_array);
	tep_db_perform('travel_notes_comments', $sql_data_array);
	$total_sql = tep_db_query('SELECT count(*) as total FROM `travel_notes_comments` WHERE travel_notes_id="'.$travel_notes_id.'" ');
	$total = tep_db_fetch_array($total_sql);
	tep_db_query('UPDATE `travel_notes` SET `comment_num` = "'.$total['total'].'" WHERE `travel_notes_id` ="'.$travel_notes_id.'" ');
	$close_parameters = array('action','ajax','page','x','y');
	
	$notes_content = '评论添加成功！';
	$out_time = 3; //延迟3秒关闭
	$tpl_content = file_get_contents(DIR_FS_CONTENT . 'html_tpl/'.'out_time_notes.tpl.html');
	$tpl_content = str_replace('{notes_content}',$notes_content,$tpl_content);
	$tpl_content = str_replace('{out_time}',$out_time,$tpl_content);
	$goto_url = preg_replace($p,$r,tep_href_link('travel_notes_detail.php','travel_notes_id='.$travel_notes_id.'&'.tep_get_all_get_params($close_parameters)));
	$js_str = '
	var gotourl = "'.$goto_url.'";
	var notes_contes = "'.addslashes($tpl_content).'";
	write_success_notes('.$out_time.', notes_contes, gotourl);
	';
	$js_str = '[JS]'.preg_replace('/[[:space:]]+/',' ',$js_str).'[/JS]';
	echo db_to_html($js_str);
	exit;
}
?>