<?php
!defined('_MODE_KEY') && exit('Access error!');
if($_GET['mod'] == 'view' && (int)$_GET['id']){
    $data = array();
    include("mode/tours-talent-contest/lib/function.php");
    /* 获取作品详情 */
    $id = (int)$_GET['id'];
    $sql = "SELECT * FROM  `daren_works` WHERE works_id=".$id;
    
    $works_res = tep_db_query($sql);
    $works_rows = tep_db_fetch_array($works_res);
    
    if ($works_rows['works_is_view'] != 1 && $works_rows['customers_id'] != $_SESSION['customer_id']){
       
        echo '<script>location.href="trip_player.php?mod=homepage&id='.$works_rows['customers_id'].'"</script>';
        exit;
    }
    $data['works'] = $works_rows;    
    $data['works']['works_title'] = getSafeHtml($data['works']['works_title']);
    $data['works']['works_content'] = getSafeHtml($data['works']['works_content']);
    $frontcover = substr($data['works']['works_frontcover'],1);
    if (file_exists($frontcover)){
        $data['works']['works_frontcover'] = $data['works']['works_frontcover'];
    }else{
        $data['works']['works_frontcover'] = '';
    }
    
    /*根据当前作品id获取上一篇 下一篇*/
    $prev_works_query = tep_db_query("SELECT works_title,works_id,customers_id FROM  `daren_works` WHERE works_id <".$id.' AND works_is_view =1 ORDER BY works_id	DESC LIMIT 1');
    $prev_works = tep_db_fetch_array($prev_works_query);
    $prev_works['works_title'] = strip_tags($prev_works['works_title']);
    $prev_works['works_title'] = strip_tags($prev_works['works_title']);
    
    $next_works_query = tep_db_query("SELECT works_title,works_id,customers_id FROM  `daren_works` WHERE works_id >".$id.' AND  works_is_view =1 ORDER BY works_id	ASC LIMIT 1');
    $next_works = tep_db_fetch_array($next_works_query);
    $next_works['works_title'] = cutword(strip_tags($next_works['works_title']),20);
    $next_works['works_title'] = cutword(strip_tags($next_works['works_title']),20);
    
    $back_user_list_url =  $works_rows['customers_id'];
    
    
    $sql = "UPDATE  `daren_works` SET  `works_readnum` =  works_readnum+1 WHERE  `works_id` =".$id;
    tep_db_query($sql);
    /* 作者信息 */
    $account = tep_get_customers_info_fix($works_rows['customers_id']);   
    $data['account'] = $account;
    /* 作品评论 */
    $sql = "SELECT COUNT(*) AS rows FROM  `daren_works_comment` WHERE works_comment_is_view=1 AND works_id=".$id;
    $works_comment_res = tep_db_query($sql);
    $works_comment_rows = tep_db_fetch_array($works_comment_res);
    $data['works']['works_commnum'] = $works_comment_rows['rows'];
   
     $total = (int)$works_comment_rows["rows"];
     $db_perpage = 5;
     $url = 'trip_player.php?mod=view&id='.$id."&pp=";
    if (isset($_GET['pp']) && (int)$_GET['pp']){
        $pp = (int)$_GET['pp'];
    }else{
        $pp = 1;
    }
    $pages = makePager1($total,$url,$pp,$db_perpage,false);
    
    $limit = " LIMIT ".($pp-1)*$db_perpage.",$db_perpage;";
    
    
    
    $sql = "SELECT * FROM  `daren_works_comment` WHERE works_comment_is_view=1 AND works_id=".$id.' '.$limit;
    $works_comment_res = tep_db_query($sql);    
    $i = 0;
    while($comment = tep_db_fetch_array($works_comment_res)){
        $data['comment'][$i]['flows'] = ($i+1) + (($pp-1)*$db_perpage) ;
        $data['comment'][$i]['works_comment_id'] = $comment['works_comment_id'];
        $data['comment'][$i]['works_id'] = $comment['works_id'];
        $data['comment'][$i]['works_comment_addtime'] = $comment['works_comment_addtime'];
        $data['comment'][$i]['works_comment_author'] = $comment['works_comment_author'];
        $data['comment'][$i]['works_comment_author_id'] = $comment['works_comment_author_id'];
        
        $data['comment'][$i]['works_comment_content'] = strip_tags(str_replace('@MYBR@','<br />',$comment['works_comment_content']),'<br>');
        //$data['comment'][$i]['works_comment_content'] = strtr&lt;br /&gt;
        //strtr($comment['works_comment_content'],'br /&gt;','<br>');
        
        $data['comment'][$i]['works_comment_ip'] = $comment['works_comment_ip'];    
        $i++;
    }
    //print_r($data);
    
    if (isset($_GET['action']) && ($_GET['action'] == 'updateVVC')) {//更换验证码	
        $RandomStr = md5(microtime());// md5 to generate the random string										
        $_SESSION['captcha_key'] = substr($RandomStr,0,4);//trim 5 digit
        $RandomImg = 'php_captcha.php?code='. base64_encode($_SESSION['captcha_key']);
        echo $RandomImg;
        exit;
    }else  if(isset($_POST['action']) && ($_POST['action'] == 'addcomment')){
       
       
        if (strtolower($_POST['visual_verify_code']) == strtolower($_SESSION['captcha_key'])){            
            
            
            if (tep_not_null($_POST['comment'])){
             
               //echo $comment = htmlentities(nl2br($_POST['comment']));
               $comment = addslashes(nl2br(tep_db_prepare_input($_POST['comment'])));   
               $comment = str_replace('<br />','@MYBR@',$comment);
               $comment = htmlspecialchars($comment, ENT_QUOTES);
            }
            
            if (tep_session_is_registered('customer_id')){
                $customer_name = $customer_first_name;
                $customer_id = $customer_id;
            }else{
                if (tep_not_null($_POST['comment_user'])){
                    $customer_name = '游客';
                    $customer_id = 0;
                }
            }
            
            $works_id = $id;
            $ip = tep_get_ip_address();
            if ($works_id){
                                      
                $customer_name = getSafeHtml($customer_name);
                //$comment = getSafeHtml($comment);
               
                $sql = "INSERT INTO  `daren_works_comment` (  `works_comment_id` ,  `works_id` ,  `works_comment_addtime` , `works_comment_author` , `works_comment_author_id`,  `works_comment_content` ,  `works_comment_ip` ) VALUES (NULL ,  '".$works_id."', NOW( ) ,  '".$customer_name."','".$customer_id."',  '".$comment."',  '".$ip."');";                
              
                tep_db_query($sql);
                $sql = "UPDATE  `daren_works` SET  `works_commnum` =  works_commnum+1 WHERE  `works_id` =".$works_id;
                tep_db_query($sql);
            }
            echo '<script>alert("评论成功,您的评论正在审核中，请稍后查看!");location.href="trip_player.php?mod=view&id='.$id.'#comment"</script>';
            //tep_redirect('trip_player.php?mod=view&id='.$id."#comment");
        }else{
            $str = '验证码错误!';
            echo '<script>alert("'.$str.'")</script>';
        }
    }
    
    /*评论者 */
    if (tep_session_is_registered('customer_id')){
        $comment_html = '<a href="trip_player.php?mod=homepage&id='.$customer_id.'" class="blue">'.$customer_first_name.'</a>';       
        $smarty->assign('customer_id_links', $comment_html);
    }else{
        $comment_html = db_to_html('游客');
        $smarty->assign('customer_id_links',$comment_html);
    }
    
	//验证码
	$RandomStr = md5(microtime());// md5 to generate the random string										
	$_SESSION['captcha_key'] = substr($RandomStr,0,4);//trim 5 digit
	$RandomImg = 'php_captcha.php?code='. base64_encode($_SESSION['captcha_key']);
    $url = 'trip_player.php?mod=view&id='.$id;
    
    $smarty->assign('back_user_list_url', $back_user_list_url);
    $smarty->assign('works_frontcover', $data['works']['works_frontcover']);
    $smarty->assign('data', $data);
    $smarty->assign('pages', $pages);
    $smarty->assign('comment', $data['comment']);  
    $smarty->assign('prev_works',$prev_works);
    $smarty->assign('next_works',$next_works);
    
    $smarty->assign('comment_total',$total);
    $smarty->assign('RandomImg',$RandomImg);    
    
}
?>