<?php
    
    require_once("includes/application_top.php");
    
    /*if (!tep_session_is_registered('customer_id')) {
		echo db_to_html("登录已超时，请刷新页面重新登录！");
		exit;
	}*/
    /* 检测目录是否存在 */
    function createDir($path){  
        if (!file_exists($path)){  
            createDir(dirname($path));      
            mkdir($path, 0755);  
        }  
    }  
    $tmp_microtime = str_replace(array('.',' '),array('',''),microtime());
    $new_name = 'detail_'.mt_rand().'_'.$tmp_microtime;
    
    /*定义文件路径*/
    
    //$image_root = '/images/talent/'.date('Ym').'/';
    $image_root = '/images/talent/userfiles/';
    $dir = $_SERVER['DOCUMENT_ROOT'].$image_root;
    $http_url = 'http://'.$_SERVER['HTTP_HOST'].$image_root;
    //createDir($image_root);
    
    $headers = getallheaders();
    
    $exc_name = preg_replace('/^.*\./','.',$headers['Image-Name']);
    if (strripos($exc_name, '.jpg') === false){
        $exc_name .= '.jpg';
    }    
    /*上传后文件名称*/
    $new_name .= strtolower($exc_name);
    //file_put_contents('tes.txt', $new_name);
    $image_name = $dir.$new_name;
    $file = fopen($image_name, 'wb');
    if(fwrite($file, $GLOBALS['HTTP_RAW_POST_DATA'])=== FALSE){
        echo "0";
        exit();
    }    
    //imageCompression($image_name,160, str_replace('detail_','thumb_',$image_name));
    //out_image_cut($image_name,str_replace('detail_','thumb_',$image_name));
    //out_image_cut($image_name,str_replace('detail_','smallthumb_',$image_name), 114, 114);
    
    echo "1"."|".$image_root.$new_name;
    //echo "1"."|".$new_name."|".$dir."|".$image_root.$new_name."|".$image_root.str_replace('detail_','thumb_',$new_name)."|".$image_root.str_replace('detail_','smallthumb_',$new_name)."|".$http_url.$new_name."|".$http_url.str_replace('detail_','thumb_',$new_name);	//状态码|文件名|目录名|全名（目录+文件）|图片网址|图片缩略图
    fclose($file);