<?php
!defined('_MODE_KEY') && exit('Access error!');
/* 添加作品 */
if(tep_session_is_registered('customer_id') ){
   
    include('js/FCKeditor/fckeditor.php');
    include("mode/tours-talent-contest/lib/function.php");
    /*
    if ($_POST['action'] == 'upimg'){
        $up_type = 'jpg,gif,png';
        $up_size_max = 200*1024;
        $up_server_folder = 'images/talent/';
        $up_server_file_name = $_POST['save_file_name'];
        $up_file_name = 'FileDomain';
        $img = "Y";
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
        
        $src = 'images/talent/'.$new_file_name;
        $img = '<img src="'.$src.'" width="114" height="114"/>';
        $script_code = '$(document).ready(function(){            
            $(window.parent.document).find("#fengmian").html(\''.$img.'\');
            $(window.parent.document).find("#FileDomain").val("");
            $(window.parent.document).find("#submit_images_FaceForm").removeAttr("disabled"); 
            $(window.parent.document).find("#action").val("add");
            $(window.parent.document).find("#filename").val("'.$new_file_name.'");
          });';
        echo $new_file_name;
        echo '<script src="jquery-1.3.2/jquery-1.4.2.min.js"  type="text/javascript"></script>';
        echo '<script type="text/javascript">'.$script_code.'</script>';
        
    }elseif ($_POST['action'] == 'add'){
        
        print_r($_POST);        
    }
    */
    $customers_id = $_SESSION['customer_id'];
    if (isset($_GET['id']) &&($_GET['action'] == 'edit')){
       
        $id = (int)$_GET['id'];
        $works_res = tep_db_query("SELECT * FROM `daren_works` WHERE works_id=".$id);
        $works_rows = tep_db_fetch_array($works_res);
        
         
         if ($works_rows['customers_id'] == $customers_id){
            
            $src = $works_rows['works_frontcover'];
            $WH = getimgHW3hw_wh($src,114,114);
            $wh_array=explode("@",$WH);
            $works_rows['face_width'] = $wh_array[0];
            $works_rows['face_height'] = $wh_array[1];
            //$works_rows['works_content'] = htmlspecialchars_decode($works_rows['works_content']);
            $works_rows['works_content'] = getSafeHtml($works_rows['works_content']);
            $works_rows['works_title'] = getSafeHtml($works_rows['works_title']); 
            $works_rows['works_id'] = $works_rows['works_id'];
            
            //if (file_exists($works_rows['works_frontcover'])){
                $works_rows['works_frontcover_thumb'] = $works_rows['works_frontcover_thumb'];
            //}else{
            //    $works_rows['works_frontcover'] = 'image/daren_uploadphoto.gif';
            //}
            //$works_rows['works_content'] = $works_rows['works_content'];
            //$works_rows['works_title'] = $works_rows['works_title'];            
            
            $smarty->assign('works',$works_rows);
        }else{
            echo '<script>history.back();</script>';
        }
    }
    if($_POST['action'] == 'add'){
        
        if(tep_session_is_registered('customer_id') ){            
        
            $id = (int)$_POST['work_id'];
            
               
                /*
                $works_name = urldecode($HTTP_POST_VARS['works_name']);        
                
                $works_name = html_to_db(tep_db_prepare_input($works_name));
                
                
                $content = urldecode($HTTP_POST_VARS['content']);             
                $content =iconv("UTF-8","GB2312",$content);
                $content = html_to_db(tep_db_prepare_input($content));      
                */
                
                if (tep_not_null($_POST['works_name'])){
                    $works_name = tep_db_prepare_input($_POST['works_name']);                                              
                    //$works_name = getSafeHtml($works_name);                
                    $works_name = iconv('utf-8','gb2312',$works_name);
                    /*                
                    $works_name = tep_db_prepare_input($works_name);  
                    
                    */
                }else{
                    echo "<script>alert('".db_to_html('作品名称不得为空!')."');</script>";
                    exit;
                }
                if (tep_not_null($_POST['content'])){
                    //$content = tep_db_prepare_input($_POST['content']);                
                    //$content = html_to_db($content);
                    
                    $content = tep_db_prepare_input($_POST['content']);
                    $content = iconv('utf-8','gb2312',$content);
                    /*
                    $content = iconv('utf-8','gb2312',js_unescape($_POST['works_name']));
                    $content = tep_db_prepare_input($content);   
                    $content = getSafeHtml($content);                
                    */
                }else{
                    echo "<script>alert('".db_to_html('作品内容不得为空!')."');</script>";
                    exit;
                }
                if (tep_not_null($_POST['works_frontcover'])){
                    $works_frontcover = tep_db_prepare_input($_POST['works_frontcover']);
                    /* 如果有修改图片则删除原来的图片 */                
                    $query = tep_db_query("SELECT works_frontcover, works_frontcover_thumb FROM  `daren_works` WHERE works_id=".$id);
                    $rows = tep_db_fetch_array($query);
                    
                    if ($works_frontcover != $rows['works_frontcover']){
                                      
                        $works_f =substr($rows['works_frontcover'],'1');                    
                        $works_frt = substr($rows['works_frontcover_thumb'],'1');                    
                       
                        @unlink($works_f);
                        
                        @unlink($works_frt);
                    }
                }
                if (tep_not_null($_POST['works_frontcover_thumb'])){
                    $works_frontcover_thumb = tep_db_prepare_input($_POST['works_frontcover_thumb']);
                }
                $data_array['works_title'] = $works_name;
                $data_array['works_content'] = $content;  
                $data_array['works_frontcover'] = $works_frontcover; 
                $data_array['works_frontcover_thumb'] = $works_frontcover_thumb; 
                
                
                
                //print_r($data_array);   
                filterwords($content);
                if ($id >0){  
                    $works_edit_num = (int)$_POST['works_edit_num'];
                    $data_array['works_edit_num'] = $works_edit_num + 1;
                    $data_array['works_is_edit'] = '1';
                    tep_db_perform('daren_works', $data_array, 'update', ' works_id='.$id);
                    exit;
                }else{              
                    $data_array['customers_id'] = $customers_id;
                    $data_array['works_author'] = $_SESSION['customer_first_name'];
                    $data_array['works_is_view'] = 0;     
                    $data_array['works_addtime'] = date('Y-m-d H:i:s');
                    tep_db_perform('daren_works', $data_array);
                    $insert_id = tep_db_insert_id();
                    echo $insert_id;
                    exit;
                }
            }else{
                echo "<script>alert('登录超时,请重新登录!');location.href='trip_player.php'</script>";                
            }
            /*
            $ar = array('"',"'");
            $content = str_replace($ar,'',$content);            
            $works_name = str_replace($ar,'',$works_name);           
            $sql = "UPDATE `daren_works` SET `works_title` = '".$works_name."',`works_content` = '".$content."' WHERE `works_id` =".$id;
            tep_db_query($sql);         
             */
       
     
    }
    
    $gloabal_js = '<script src="includes/javascript/global.js.php" type="text/javascript"></script>';
    $smarty->assign('ref_action',$_GET['action']);
    $smarty->assign('gloabal_js',$gloabal_js);
    $smarty->assign('now_mouth',date('Ym'));
    $smarty->assign('customers_id',$customers_id);
    
}else{
    tep_redirect('trip_player.php');
}
?>