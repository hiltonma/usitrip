<?php
//上传文件模块
//要指定文件类型\大小\文件名路径
//带进度条的上传 start
$flash_upload = false;
if($flash_upload==true){
	require_once('ajax_flash_upload.php');
}
//带进度条的上传 end
else{	//普通上传 start
require_once("includes/application_top.php");

if(!tep_not_null($form_name)){ $form_name = "FaceForm"; }
if(!tep_not_null($iframe_id)){ $iframe_id = "hidden_frame";}

if($_POST['up_action']=="true"){
	@header("content-Type: text/html; charset=".CHARSET);
	$script_code = '';
	$script_code .= '
	var LoadingIconDiv = parent.document.getElementsByTagName("label");
	for(i=0; i<LoadingIconDiv.length; i++){
		if(LoadingIconDiv[i].id.indexOf("LoadingIcon")>-1){
			LoadingIconDiv[i].innerHTML = "";
		}
	}
	parent.document.getElementById("submit_images_'.$form_name.'").disabled=false;
	';

	if (!tep_session_is_registered('customer_id')) {
		echo db_to_html("登录已超时，请刷新页面重新登录！");
		exit;
	}
	$up_type = 'jpg,gif,png';
	
	//$up_size_max = (int)$_POST['file_size']*1024;
	//$up_server_folder = $_POST['save_dir'];
	//$up_server_file_name = $_POST['save_file_name'];
	$up_size_max = false;
	$up_server_folder = false;
	$up_server_file_name = false;
	/*正式处理*/
	switch($_POST['upload_type']){
		case "face": //上传头像的处理
			$up_size_max = 1024*1024*3; //文件大小上限 头像可以上传最大3M的文件
			$up_server_folder = DIR_FACE_FS_IMAGES;	//保存的文件夹路径
			$up_server_file_name = date('YmdHis').'_'.$customer_id;	//保存的文件名，不包括扩展名
		break;
		case "photo":
			$up_size_max = 1024*500;
			$up_server_folder = DIR_FS_CATALOG.'tmp/';
			$up_server_file_name = date('YmdHis').'_'.$customer_id;
		break;
        case "talent": //上传作品封面，已经不用这种方式上传
		break;    
	}
	
	if($up_size_max == false || $up_server_folder == false || $up_server_file_name == false){
		echo '<script type="text/javascript">alert("'.('Plx check $up_size_max $up_server_folder $up_server_file_name on ').basename(__FILE__).' upload_type:'.$_POST['upload_type'].'");</script>';
		exit;
	}
	
	
	$up_file_name = 'FileDomain';
	$img = "Y";
    /* 检测目录是否存在 */
    function createDir($path){  
        if (!file_exists($path)){  
            createDir(dirname($path));      
            mkdir($path, 0777);  
        }  
    }  
    createDir($up_server_folder);
	if(tep_not_null($_POST['imgpix'])){ $imgpix = $_POST['imgpix']; }else{ $imgpix = ""; }
	if(tep_not_null($_POST['imgpix_type'])){ $imgpix_type = $_POST['imgpix_type']; }else{ $imgpix_type = ""; }
	
	$new_file_name = up_file($up_type, $up_size_max, $up_server_folder,$up_server_file_name,$up_file_name,$img,$imgpix,$imgpix_type,'1');
	if($new_file_name==false || is_array($new_file_name)){
		foreach((array)$new_file_name as $val){
			$alert .= db_to_html($val).'\t\n';
		}
		if(tep_not_null($alert)){
			$script_code .= 'alert("'.$alert.'");';
		}
		$script_code = preg_replace('/[[:space:]]+/',' ',$script_code);
		echo '<script type="text/javascript">'.$script_code.'</script>';
		exit;
	}

	/*正式处理*/
	switch($_POST['upload_type']){
		case "face": //上传头像的处理
			$src = 'images/face/'.$new_file_name;	
			tep_image_makethumb($src,$src,114,114);
            $WH = getimgHW3hw_wh($src,114,114);
            $wh_array=explode("@",$WH);
			$customers_sql = tep_db_query('SELECT customers_face FROM `customers` WHERE customers_id="'.(int)$customer_id.'" ');
			$customers_row = tep_db_fetch_array($customers_sql);
			if(tep_not_null($customers_row['customers_face']) && $customers_row['customers_face']!=$new_file_name){
				@unlink(DIR_FACE_FS_IMAGES.$customers_row['customers_face']);
			}
			tep_db_query('UPDATE `customers` SET `customers_face` = "'.$new_file_name.'" WHERE `customers_id` = "'.(int)$customer_id.'" LIMIT 1;');
		break;
		case "photo":
			//$src = 'images/photos/'.$new_file_name;
			$src = 'tmp/'.$new_file_name;
                        $WH = getimgHW3hw_wh($src,145,109);
                        $wh_array=explode("@",$WH);
                        echo '<img src="'.$src.'"  />';
		break;
        case "talent":
		break;
            
	}
	
	
	//JS代码      
	$need_up_img_id = tep_db_output($_POST['need_up_img_id']);
	$need_up_form_id = tep_db_output($_POST['need_up_form_id']);
	$need_up_form_input_name = tep_db_output($_POST['need_up_form_input_name']);
	$done_close_id = tep_db_output($_POST['done_close_id']);
	if(tep_not_null($need_up_img_id)){
		$script_code .= '
		var img = parent.document.getElementById("'.$need_up_img_id.'"); 
		if(img!=null){
			img.src="'.$src.'"; 
		}
		';
	}
	
	if(tep_not_null($need_up_form_id) && tep_not_null($need_up_form_input_name)){
		if(!preg_match('/\[|\]/',$need_up_form_input_name)){
			$script_code .= '
			var form = parent.document.getElementById("'.$need_up_form_id.'"); 
			if(form!=null){
				form.elements["'.$need_up_form_input_name.'"].value = "'.$new_file_name.'";
			}
			';
		}else{
			$script_code .= ' parent.document.getElementById("'.$need_up_form_input_name.'").value = "'.$new_file_name.'" ;';
		}
	}
	if(tep_not_null($done_close_id)){
		$script_code .= '
		var close_id = parent.document.getElementById("'.$done_close_id.'"); 
		if(close_id!=null){
			close_id.style.display="none"; 
		}
		';
	}
	
	if(tep_not_null($script_code)){
                $script_code.='parent.parent.document.getElementById("'.$need_up_img_id.'").width="'.$wh_array[0].'";';
                $script_code.='parent.parent.document.getElementById("'.$need_up_img_id.'").height="'.$wh_array[1].'";';
		$script_code = preg_replace('/[[:space:]]+/',' ',$script_code);
		echo '<script type="text/javascript">'.$script_code.'</script>';
	}
    /* 以下为上传后改变图片的js START*/
    if (tep_not_null($_POST['self_js']) && ((int)$_POST['self_js'] == 1)){
        $js_code = "
        <script src=\"jquery-1.3.2/jquery-1.4.2.min.js\"  type=\"text/javascript\"></script>
        <script type='text/javascript'>
        jQuery(document).ready(function(){
            var img = \"<img src='".$src."' width='".$wh_array[0]."' height='".$wh_array[1]."'/>\";
            jQuery(window.parent.document).find('#need_up_img_id').html(img);
            jQuery(window.parent.document).find('#FileDomain').attr('value','');
        });
        </script>
        ";
        echo $js_code;
    }
    /* 以下为上次后改变图片的js  END */
	//print_r($new_file_name);
	//echo '<img src="http://www.google.com.hk/intl/zh-CN/images/logos/translate_logo.gif" />';
	//print_r($_FILES);
	//print_r($_POST);
	exit;
}
?>
<div>
 <form action="<?= tep_href_link('ajax_upload.php');?>" method="post" enctype="multipart/form-data" name="<?=$form_name?>" id="<?=$form_name?>" target="<?= $iframe_id?>" onSubmit="check_upload_submit(this); return false;">
 	<input name="up_action" type="hidden" value="true" />
	<?php
	//上传的类型
	echo tep_draw_hidden_field('upload_type');
	//由于安全问题以下参数不再用
	//echo tep_draw_hidden_field('save_dir');
	//echo tep_draw_hidden_field('save_file_name');
	//echo tep_draw_hidden_field('file_size',200);
	
	//提交完成后需要更新的图片框
	echo tep_draw_hidden_field('need_up_img_id');
	//提交完成后需要更新的表单
	echo tep_draw_hidden_field('need_up_form_id');
	//提交完成后需要更新的表单字段名称
	echo tep_draw_hidden_field('need_up_form_input_name');
	// 提交完成后需要关闭的层id,其实就是上传图片这个层
	echo tep_draw_hidden_field('done_close_id');
	?>
	

	<input name="FileDomain" type="file"  class="mytoursFileUpload"/>
	<input class="jb_fb_all" type="submit" id="submit_images_<?=$form_name?>" value="<?= db_to_html('开始上传')?>" /><label id="LoadingIcon<?=$form_name?>"></label><br />
	<span><?= db_to_html('允许上传jpg,gif,png格式的图片！'.$width_height_px)?></span>
 </form>
 </div>

 <div>
 <iframe name='<?= $iframe_id?>' id="<?= $iframe_id?>" style="display:none; width:0px; height:0px;" ></iframe>
 </div>

<script type="text/javascript">
function check_upload_submit(form){
	var LoadingIconDiv = document.getElementById('LoadingIcon<?=$form_name?>');
	if(LoadingIconDiv!=null){
		_img = document.createElement("img");
		_img.setAttribute("src","image/snake_transparent.gif");
		LoadingIconDiv.appendChild(_img);
	}
	if(form.elements['FileDomain'].value == ""){
		if(LoadingIconDiv!=null){
			LoadingIconDiv.innerHTML = "";
		}
		alert("<?= db_to_html('请选择要上传的图片！')?>");
		return false;
	}
	jQuery("#submit_images_<?=$form_name?>").attr("disabled","disabled");
	form.submit();
	
}
</script>
<?php
}
//普通上传 end
?>