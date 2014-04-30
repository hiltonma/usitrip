<?php
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );
require_once('includes/application_top.php');
header("Content-type: text/html; charset=".CHARSET);
$photo_root = DIR_FS_CATALOG.'images/photos/';


//删除游记评论start tom added 2010-7-7
if($_GET['action']=='remove_comments'&& $error == false){
       if((int)$_GET['comments_id']){
          tep_db_query('UPDATE travel_notes_comments SET has_remove="1" WHERE travel_notes_comments_id="'.(int)$_GET["comments_id"].'" AND travel_notes_id="'.(int)$_GET["travel_notes_id"].'" ');
          tep_db_query('UPDATE `travel_notes` SET `comment_num` =( comment_num -1) WHERE `travel_notes_id` = "'.(int)$_GET["travel_notes_id"].'"');
	  $comments_sql = tep_db_query('SELECT count(*) as total FROM `travel_notes_comments` WHERE travel_notes_id="'.(int)$_GET["travel_notes_id"].'" and has_remove!="1" ');
          $comments = tep_db_fetch_array($comments_sql);
          $sms = '[JS]$("#comments_num").text("'.$comments['total'].'"); window.location.reload();[/JS]';
          echo db_to_html($sms);
       }
       exit;
}
//删除游记评论end
//
//manager删除游记评论start tom added 2010-7-7
if($_GET['action']=='manage_remove_comments'&& $error == false){
       if((int)$_GET['comments_id']){
          tep_db_query('UPDATE travel_notes_comments SET has_remove="1" WHERE travel_notes_comments_id="'.(int)$_GET["comments_id"].'" AND travel_notes_id="'.(int)$_GET["travel_notes_id"].'" ');
          tep_db_query('UPDATE `travel_notes` SET `comment_num` =( comment_num -1) WHERE `travel_notes_id` = "'.(int)$_GET["travel_notes_id"].'"');
	  $comments_sql = tep_db_query('SELECT count(*) as total FROM `travel_notes_comments` WHERE travel_notes_id="'.(int)$_GET["travel_notes_id"].'" and has_remove!="1" ');
          $comments = tep_db_fetch_array($comments_sql);
          $sms = '
		alert("删除成功");
                window.location.reload();

		';
         $sms = '[JS]'.preg_replace('/[[:space:]]+/',' ',$sms).'[/JS]';
	 echo db_to_html($sms);
       }
       exit;
}
//manager删除站内短信
if($_GET['action']=='manage_remove_sms'&& $error == false){
       if((int)$_GET['sms_id']){
          tep_db_query('DELETE FROM `site_inner_sms` WHERE `sis_id` = "'.(int)$_GET['sms_id'].'" ');
          $sms = '
		alert("删除成功");
                window.location.reload();

		';
         $sms = '[JS]'.preg_replace('/[[:space:]]+/',' ',$sms).'[/JS]';
	 echo db_to_html($sms);
       }
       exit;
}
//manager删除站内短信end
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
	$$photo_books_id = (int)$check_row['photo_books_id'];

	$sql_data_array = array('photo_title' => ajax_to_general_string($photo_title),
							'photo_content' => ajax_to_general_string($photo_content),
							'photo_name' => $photo_name,
							'photo_update' => date('Y-m-d H:i:s'));
	@rename(DIR_FS_CATALOG.'tmp/'.$photo_name, DIR_PHOTOS_FS_IMAGES.$photo_name);
	$sql_data_array = html_to_db($sql_data_array);
	tep_db_perform('photo', $sql_data_array, 'update', ' photo_id="'.(int)$_POST['photo_id'].'"');
	out_thumbnails($photo_root.$photo_name, DIR_FS_CATALOG."images/photos/thumb_".$photo_name, 145, 109);
	//$messageStack->add_session('update_photos', db_to_html('您的相片更新成功！').$photo_name, 'success');
	if($old_photo_name!=$photo_name && tep_not_null($old_photo_name)){
		@unlink(DIR_PHOTOS_FS_IMAGES.$old_photo_name);	//删除旧图
		@unlink(DIR_PHOTOS_FS_IMAGES."thumb_".$old_photo_name);	//删除旧缩略图
	}
	//将相片写到相册封面
	tep_db_query('UPDATE `photo_books` SET `photo_books_cover` = "'.$photo_name.'" WHERE `photo_books_id` = "'.$photo_books_id.'" LIMIT 1 ;');
	$img_wh = explode('@',getimgHW3hw_wh(DIR_FS_CATALOG."images/photos/".$photo_name,500,375));

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
	$("#big_pic_'.$_POST['photo_id'].'").attr("src","/images/photos/'.$photo_name.'");
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
//删除游记里的相片
if($_GET['action']=='del' && $error == false){
	//先检查原相片上传人
	$check_sql = tep_db_query('select photo_id, photo_books_id, photo_name  from photo where photo_id ="'.(int)$_GET['photo_id'].'"');
	$check_row = tep_db_fetch_array($check_sql);
	if((int)$check_row['photo_id']){

		@unlink($photo_root.tep_db_output($check_row['photo_name']));	//删除旧图
		@unlink($photo_root."thumb_".tep_db_output($check_row['photo_name']));	//删除旧缩略图
		tep_db_query('DELETE FROM `photo` WHERE `photo_id` = "'.$check_row['photo_id'].'" ');
		tep_db_query('UPDATE `photo_books` SET photo_sum=(photo_sum-1) WHERE `photo_books_id` = "'.$check_row['photo_books_id'].'" ');
		tep_db_query('DELETE FROM `photo_comments` WHERE `photo_id` = "'.$check_row['photo_id'].'" ');

		$sms = '
		$("#href_big_pic_'.$_GET['photo_id'].'").html("");
                $("#photo_title_'.$_GET['photo_id'].'").hide();
	        $("#photo_content_'.$_GET['photo_id'].'").hide();
                $("#photo_p_'.$_GET['photo_id'].'").hide();
		
		';


		$sms .= '
		alert("删除成功");
                window.location.reload();

		';
		$sms = '[JS]'.preg_replace('/[[:space:]]+/',' ',$sms).'[/JS]';
		echo db_to_html($sms);
	}
	exit;
}
//删除游记在详情页面 start
if($_GET['action']=='del_travel_notes' && $error == false){
	//本功能不删除游记中涉及到的相片（相片可到相册中删除），只删除游记及游记的评论
	if(!(int)$_GET["travel_notes_id"]){  echo db_to_html('无travel_notes_id'); exit; }
	$notes_query = tep_db_query('SELECT travel_notes_id, photo_ids FROM `travel_notes` WHERE travel_notes_id = "'.(int)$_GET["travel_notes_id"].'"');
	$notes = tep_db_fetch_array($notes_query);
	if((int)$notes['travel_notes_id']){
		tep_db_query('DELETE FROM `travel_notes_comments` WHERE `travel_notes_id` = "'.$notes['travel_notes_id'].'" ');	//游记评论
		tep_db_query('DELETE FROM `travel_notes` WHERE `travel_notes_id` = "'.$notes['travel_notes_id'].'" ');	//游记
		$sms = '
		alert("删除成功");
                history.go(-1);

		';
		$sms = '[JS]'.preg_replace('/[[:space:]]+/',' ',$sms).'[/JS]';
		echo db_to_html($sms);
	}
	exit;
}
//删除游记 end
//删除游记在游记列表页面 start
if($_GET['action']=='del_travel_notes_list' && $error == false){
	//本功能不删除游记中涉及到的相片（相片可到相册中删除），只删除游记及游记的评论
	if(!(int)$_GET["travel_notes_id"]){  echo db_to_html('无travel_notes_id'); exit; }
	$notes_query = tep_db_query('SELECT travel_notes_id, photo_ids FROM `travel_notes` WHERE travel_notes_id = "'.(int)$_GET["travel_notes_id"].'"');
	$notes = tep_db_fetch_array($notes_query);
	if((int)$notes['travel_notes_id']){
		tep_db_query('DELETE FROM `travel_notes_comments` WHERE `travel_notes_id` = "'.$notes['travel_notes_id'].'" ');	//游记评论
		tep_db_query('DELETE FROM `travel_notes` WHERE `travel_notes_id` = "'.$notes['travel_notes_id'].'" ');	//游记
		$sms = '
		alert("删除成功");
                window.location.reload();

		';
		$sms = '[JS]'.preg_replace('/[[:space:]]+/',' ',$sms).'[/JS]';
		echo db_to_html($sms);
	}
	exit;
}
//删除游记 end

?>
