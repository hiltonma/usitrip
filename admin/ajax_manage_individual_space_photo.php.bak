<?php
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );
require_once('includes/application_top.php');
header("Content-type: text/html; charset=".CHARSET);
$photo_root = DIR_FS_CATALOG.'images/photos/';

//删除相片评论 start
if($_GET['action']=='remover_comments' && $error == false){
	if((int)$_GET["comments_id"]){
		tep_db_query('UPDATE photo_comments SET has_remove="1" WHERE photo_comments_id="'.(int)$_GET["comments_id"].'" ');
		$comments_sql = tep_db_query('SELECT count(*) as total FROM `photo_comments` WHERE photo_id="'.(int)$_GET["photo_id"].'" and has_remove!="1" ');
		$comments = tep_db_fetch_array($comments_sql);
		$sms = '[JS]$("#comments_num").text("'.$comments['total'].'"); window.location.reload();[/JS]';
		echo db_to_html($sms);
	}
	exit;
}
//删除相片评论 end
//删除相片评论 start
if($_GET['action']=='manage_remover_comments' && $error == false){
	if((int)$_GET["comments_id"]){
		tep_db_query('UPDATE photo_comments SET has_remove="1" WHERE photo_comments_id="'.(int)$_GET["comments_id"].'" ');
		$comments_sql = tep_db_query('SELECT count(*) as total FROM `photo_comments` WHERE photo_id="'.(int)$_GET["photo_id"].'" and has_remove!="1" ');
		$comments = tep_db_fetch_array($comments_sql);
		$sms = '

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
//删除相片评论 end
//删除相片 start
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
		$("#href_big_pic").html("");
		$("div[class=\'jb_xc_ck_ms\']").html("");
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
//删除相片end
//
//删除相片个人管理中心列表 start
if($_GET['action']=='manage_del' && $error == false){
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
//删除相片个人管理中心列表end
//编辑相片 start
if($_GET['action']=='update' && $error == false && $_POST['update_photo_action']=="true"){
        $photo_name = tep_db_prepare_input($_POST['photo_name']);
	$photo_title = tep_db_prepare_input($_POST['photo_title']);
	$photo_content = tep_db_prepare_input($_POST['photo_content']);
	//先检查原相片资料
	$check_sql = tep_db_query('select photo_name,photo_books_id from photo where photo_id ="'.(int)$_POST['photo_id'].'" ');
	$check_row = tep_db_fetch_array($check_sql);
	$old_photo_name = tep_db_output($check_row['photo_name']);
	$$photo_books_id = (int)$check_row['photo_books_id'];
        $dir_photos_fs_images =$photo_root.$photo_name;
	$sql_data_array = array('photo_title' => ajax_to_general_string($photo_title),
							'photo_content' => ajax_to_general_string($photo_content),
							'photo_name' => $photo_name,
							'photo_update' => date('Y-m-d H:i:s'));
	//@rename(DIR_FS_CATALOG.'tmp/'.$photo_name, DIR_PHOTOS_FS_IMAGES.$photo_name);
	$sql_data_array = html_to_db($sql_data_array);
	tep_db_perform('photo', $sql_data_array, 'update', ' photo_id="'.(int)$_POST['photo_id'].'"');
	out_thumbnails($dir_photos_fs_images, DIR_FS_CATALOG."images/photos/thumb_".$photo_name, 145, 109);
	//$messageStack->add_session('update_photos', db_to_html('您的相片更新成功！').$photo_name, 'success');
	//if($old_photo_name!=$photo_name && tep_not_null($old_photo_name)){
		//@unlink(DIR_PHOTOS_FS_IMAGES.$old_photo_name);	//删除旧图
		//@unlink(DIR_PHOTOS_FS_IMAGES."thumb_".$old_photo_name);	//删除旧缩略图
	//}
	//将相片写到相册封面
	tep_db_query('UPDATE `photo_books` SET `photo_books_cover` = "'.$photo_name.'" WHERE `photo_books_id` = "'.$photo_books_id.'" LIMIT 1 ;');
       
	$img_wh = explode('@',getimgHW3hw_wh($dir_photos_fs_images,500,375));

	$sms = '
	var Submit_Photo_Button = document.getElementById("submit_photo_button"); if(Submit_Photo_Button!=null){ Submit_Photo_Button.disabled = false;}
	closeDiv("EditPotoDiv");
	$("#photo_title").hide();
	$("#photo_content").hide();
	$("#big_pic").hide();
	$("#load_icon").css({display: "none" });
	$("#photo_title").html("'.tep_db_output(html_to_db(ajax_to_general_string($photo_title))).'");
	$("#photo_title").slideDown(600);
	$("#photo_content").html("'.nl2br(tep_db_output(html_to_db(ajax_to_general_string($photo_content)))).'");
	$("#photo_content").slideDown(600);
	$("#big_pic").attr("src","/images/photos/'.$photo_name.'");
	$("#big_pic").attr("width","'.$img_wh[0].'");
	$("#big_pic").attr("height","'.$img_wh[1].'");
	$("#big_pic").fadeIn(600);
	$("#href_big_pic").attr("herf","images/photos/'.$photo_name.'");
	';
	$sms = '[JS]'.preg_replace('/[[:space:]]+/',' ',$sms).'[/JS]';
	echo db_to_html($sms);
	exit;
}
//编辑相片 end
//删除相册 start
if($_GET['action']=='remove_album'&& tep_not_null($_GET['photo_books_id'])  && tep_not_null($_GET['dis_id']) && $error == false){
	/*删除没有与团绑定的相片*/
	$photo_sql = tep_db_query('SELECT photo_id, photo_name FROM `photo` WHERE `photo_books_id` = "'.(int)$_GET['photo_books_id'].'" and products_id < 1 ');
	while($photo = tep_db_fetch_array($photo_sql)){
		if(tep_not_null($photo['photo_name'])){
			@unlink($photo_root.$photo['photo_name']);
		}
		tep_db_query('DELETE FROM `photo` WHERE `photo_id` = "'.$photo['photo_id'].'" ');
	}
	tep_db_query('DELETE FROM `photo_books` WHERE `photo_books_id` = "'.(int)$_GET['photo_books_id'].'" LIMIT 1');
	echo '[SUCCESS]1[/SUCCESS]';
	$js_str =
	'[JS]
	var tmp_obj = document.getElementById("'.$_GET['dis_id'].'");
	if(tmp_obj!=null){
		var obj_id = "#"+tmp_obj.id;
		$(obj_id).fadeOut(1000);
		/*tmp_obj.parentNode.removeChild(tmp_obj);*/
	}
	[/JS]';

	$js_str = preg_replace('/[[:space:]]+/',' ',$js_str);
	echo db_to_html($js_str);
	exit;
}
//删除相册 end

//添加\编辑相册 start
if($_GET['action']=='process' && $error == false){
        $error_sms = "";
        $photo_books_name = tep_db_prepare_input($_POST['photo_books_name']);
        if(strlen($photo_books_name)<2){
                $error_sms .= db_to_html('请输入相册名称。\n\n');
                $error = true;
        }
        $photo_books_description = tep_db_prepare_input($_POST['photo_books_description']);
        if(strlen($photo_books_description)<2){
                $error_sms .= db_to_html('请输入相册描述。\n\n');
                $error = true;
        }
        if($error == true){
                echo db_to_html($error_sms);
                exit;
        }
        $add_date = date('Y-m-d H:i:s');

        $photo_books_name = iconv('utf-8',CHARSET.'//IGNORE',$photo_books_name);
        $photo_books_description = iconv('utf-8',CHARSET.'//IGNORE',$photo_books_description);
        //查询customers_id
        $sql_search_customers_id = tep_db_query('select customers_id from photo_books where photo_books_id = "'.(int)$_POST['photo_books_id'].'"');
        $sql_search_customers_id_row = tep_db_fetch_array($sql_search_customers_id);
        $customer_id = $sql_search_customers_id_row['customers_id'];
        $sql_data_array = array('customers_id' => (int)$customer_id ,
                                                        'photo_books_name' => $photo_books_name,
                                                        'photo_books_description' => $photo_books_description,
                                                        'photo_books_date' => $add_date,
                                                        'photo_books_privacy_settings' => '3');

        $sql_data_array = html_to_db($sql_data_array);
        if((int)$_POST['photo_books_id']){	//更新
                tep_db_perform('`photo_books`', $sql_data_array, 'update', ' photo_books_id="'.(int)$_POST['photo_books_id'].'" ');
                echo '[SUCCESS]1[/SUCCESS]';


                

                $js_str = '
                var form = document.getElementById("create_album_form");
                

                var Photo_Books_Name = document.getElementById("photo_books_name_'.(int)$_POST['photo_books_id'].'");
                Photo_Books_Name.innerHTML = form.elements["photo_books_name"].value;
                var Photo_Books_Description = document.getElementById("photo_books_description_'.(int)$_POST['photo_books_id'].'");
                Photo_Books_Description.innerHTML = form.elements["photo_books_description"].value;
                closeDiv("cr_photo_books");
                alert("编辑成功!");
                window.location.reload();
                ';
                $js_str = '[JS]'.preg_replace('/[[:space:]]+/',' ',$js_str).'[/JS]';
                echo db_to_html($js_str);
                exit;

        }
}

//删除用户头像
if($_GET['action']=='remove_person_photo'&& tep_not_null($_GET['i_customers_id']) && $error == false){
    //检查用户是否上传了头像
    $check_sql = tep_db_query('SELECT customers_face FROM `customers` WHERE customers_id ="'.(int)$_GET['i_customers_id'].'"');
    $check_row = tep_db_fetch_array($check_sql);
    if(tep_not_null($check_row['customers_face'])){
            tep_db_query('UPDATE `customers` SET customers_face="" WHERE customers_id ="'.(int)$_GET['i_customers_id'].'" ');
            $sms = '
            var tmp_obj = document.getElementById("'.$_GET['i_customers_id'].'");
	    if(tmp_obj!=null){

		$(tmp_obj).fadeOut(1000);
		
	     }
            
            ';


            $sms .= '
            

            ';
            $sms = '[JS]'.preg_replace('/[[:space:]]+/',' ',$sms).'[/JS]';
            echo db_to_html($sms);
    }
    exit;


}

?>
