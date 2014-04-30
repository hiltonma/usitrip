<?php 
require_once('includes/application_top.php');

function ajax_str($str){
	global $include;
	if($include!=true){
		return iconv(CHARSET,'utf-8'.'//IGNORE',$str);
	}else{
		return $str;
	}
}
function ajax_str_db($str){
	global $include;
	if($include!=true){
		return iconv('utf-8',CHARSET.'//IGNORE',$str);
	}else{
		return $str;
	}
}

if($_GET['action']=='delete_confirmation'){//删除相册
	if((int)$_GET['photo_books_id'] && (int)$customer_id){
		//先删除相片再删除相册
		$sql = tep_db_query('SELECT photo_name FROM photo WHERE photo_books_id="'.(int)$_GET['photo_books_id'].'" AND customers_id="'.(int)$customer_id.'" ');
		while($rows = tep_db_fetch_array($sql)){
			if(tep_not_null($rows['photo_name'])){
				@unlink(DIR_PHOTOS_FS_IMAGES.$rows['photo_name']);
			}
		}
		
		tep_db_query('DELETE FROM photo WHERE photo_books_id="'.(int)$_GET['photo_books_id'].'" AND customers_id="'.(int)$customer_id.'" ');
		tep_db_query('DELETE FROM photo_books WHERE photo_books_id="'.(int)$_GET['photo_books_id'].'" AND customers_id="'.(int)$customer_id.'" ');
		$msn = '[1]';
	}else{
		$msn = '[0]';
	}
}


if($_POST['action']=='AddPhotoBooks' || $_POST['action']=='EditPhotoBooks'){//增加和删除相册

	$photo_books_name = tep_db_prepare_input($_POST['photo_books_name']);
	$photo_books_description = tep_db_prepare_input($_POST['photo_books_description']);
	$photo_books_privacy_settings = intval($_POST['photo_books_privacy_settings']);
	$date_time = date('Y-m-d H:i:s');
	
	if(!tep_not_null($photo_books_name)){
		$error = true;
		if($include==true){
			$messageStack->add('Photo', ajax_str(db_to_html('请填写相册名')));
		}else{
			$msn = '[0]';//ajax_str(db_to_html('请填写相册名'));
		}
	}
	if($error == false){
		$sql_data_array = array('photo_books_name'=> ajax_str_db($photo_books_name),
								'photo_books_description'=>ajax_str_db($photo_books_description),
								'photo_books_privacy_settings'=>$photo_books_privacy_settings,
								'photo_books_date'=>$date_time,
								'customers_id'=> (int)$customer_id);
		if($_POST['action']=='AddPhotoBooks'){
			$check_sql = tep_db_query('SELECT photo_books_id FROM `photo_books` WHERE customers_id="'.(int)$customer_id.'" AND photo_books_name="'.ajax_str_db($photo_books_name).'" limit 1');
			$check_row = tep_db_fetch_array($check_sql);
			if(!(int)$check_row['photo_books_id']){
				$sql_data_array = html_to_db($sql_data_array);
				tep_db_perform('photo_books', $sql_data_array);
				$photo_books_id = tep_db_insert_id();
				$photo_books_name = $photo_books_name;
				
				if($include==true){
					$messageStack->add_session('Photo', ajax_str(db_to_html('相册添加成功！')),'success');
					tep_redirect(tep_href_link('my-space-photos.php'));
				}else{
					//$msn = '[1]';//ajax_str(db_to_html('相册添加成功！'));
					$msn ='[ID]'.$photo_books_id.'[/ID]';
					$msn .='[NAME]'.$photo_books_name.'[/NAME]';
				}
				
			}else{
				if($include==true){
					$messageStack->add('Photo', ajax_str(db_to_html('相册 '.$photo_books_name.' 重名！')));
				}else{
					$msn = '[2]';//ajax_str(db_to_html('相册重名！')).$photo_books_name;
				}
			}
			
		}
		if($_POST['action']=='EditPhotoBooks'){
			$check_sql = tep_db_query('SELECT photo_books_id FROM `photo_books` WHERE customers_id="'.(int)$customer_id.'" AND photo_books_name="'.ajax_str_db($photo_books_name).'" AND photo_books_id !="'.(int)$_POST['photo_books_id'].'" limit 1');
			$check_row = tep_db_fetch_array($check_sql);
			if(!(int)$check_row['photo_books_id']){
				$sql_data_array = html_to_db($sql_data_array);
				tep_db_perform('photo_books', $sql_data_array,'update', ' customers_id="'.(int)$customer_id.'" AND photo_books_id ="'.(int)$_POST['photo_books_id'].'" ');
				$photo_books_id = (int)$_POST['photo_books_id'];
				$photo_books_name = $photo_books_name;
				
				if($include==true){
					$messageStack->add_session('Photo', ajax_str(db_to_html('相册更新成功！')),'success');
					tep_redirect(tep_href_link('my-space-photos.php'));
				}else{
					//$msn = '[1]';//ajax_str(db_to_html('相册添加成功！'));
					$msn ='[ID]'.$photo_books_id.'[/ID]';
					$msn .='[NAME]'.$photo_books_name.'[/NAME]';
				}
				
			}else{
				if($include==true){
					$messageStack->add('Photo', ajax_str(db_to_html('相册 '.$photo_books_name.' 重名！')));
				}else{
					$msn = '[2]';//ajax_str(db_to_html('相册重名！')).$photo_books_name;
				}
			}
		}
	}
}
echo $msn;
?>