<?php
/* 我的个人主页 */
unset($id);
!defined('_MODE_KEY') && exit('Access error!');
if(isset($_GET['mod']) && ($_GET['mod'] == 'homepage')){
    if (isset($_GET['id']) && ((int)$_GET['id'])){
        $id = (int)$_GET['id'];
        /* 显示个人主页 */    
        
        if (tep_session_is_registered('customer_id') && ((int)$customer_id == $id)){          
            $account = tep_get_customers_info_fix( (int)$customer_id ); 
            if ($id ==  (int)$customer_id){
                $user = true;
            }
            if ($account['entry_city'] == 'undefined'){
                    $account['entry_city'] = ' ';
            }
            if ($account['entry_state'] == 'undefined'){
                $account['entry_state'] = ' ';
            }
            /*取得走四方达人相关信息*/
            $sql = "SELECT * FROM  `daren_user_info` WHERE customers_id=".$customer_id;
            $account_query = tep_db_query($sql);
            $account_talent = tep_db_fetch_array($account_query);
            $account['blog_url'] = $account_talent['blog_url'];
            $account['user_job'] = $account_talent['user_job'];
            $account['rem_trip'] = $account_talent['rem_trip'];
            $account['most_to'] = $account_talent['most_to'];
            
            /* 取得国家名称 */
           /* 根据国家id得到名称*/
            $sql = tep_db_query("SELECT countries_name FROM  `countries` WHERE countries_id = ".(int)$account['entry_country_id']);
            $country = tep_db_fetch_array($sql);    
            $account['entry_country_id'] = $country['countries_name'];
            
            /* 删除作品 */
            if (isset($_GET['action']) && $_GET['action'] == 'del'){               
                $works_id = intval($_GET['worksid']);                
                $sql = 'DELETE FROM `daren_works` WHERE `works_id` = '.$works_id;
                tep_db_query($sql);
                $sql = 'DELETE FROM `daren_works_comment` WHERE `works_id` = '.$works_id;
                tep_db_query($sql);
                $sql = 'DELETE FROM `daren_votes` WHERE `works_id` = '.$works_id;
                tep_db_query($sql);
                tep_redirect('trip_player.php?mod=homepage&id='.(int)$customer_id.'&pp='.intval($_GET['pp']));
            }
            
        }else{
            
            $account = tep_get_customers_info_fix($id); 
          
        }
        
        
        
        
        
            /* 作品列表 */
        if (tep_session_is_registered('customer_id') && ((int)$customer_id == $id)){   
            $sql = "SELECT COUNT(*) AS rows FROM  `daren_works` WHERE customers_id=".$id;
        }else{
            $sql = "SELECT COUNT(*) AS rows FROM  `daren_works` WHERE works_is_view=1 AND customers_id=".$id;
        }
          $works_comment_res = tep_db_query($sql);
            $works_comment_rows = tep_db_fetch_array($works_comment_res);
            
            include('mode/tours-talent-contest/lib/function.php');
             $total = (int)$works_comment_rows["rows"];
             //$db_perpage = MAX_DISPLAY_SEARCH_RESULTS;
             $db_perpage = 7;
             $url = 'trip_player.php?mod=homepage&id='.$id.'&pp=';
            if (isset($_GET['pp']) && (int)$_GET['pp']){
                $pp = (int)$_GET['pp'];
            }else{
                $pp = 1;
            }
            $pages = makePager1($total,$url,$pp,$db_perpage,false);
            
            $limit = " LIMIT ".($pp-1)*$db_perpage.",$db_perpage;";
            
            
        if (tep_session_is_registered('customer_id') && ((int)$customer_id == $id)){   
            $sql = "SELECT * FROM  `daren_works` WHERE  customers_id=".$id.' ORDER BY works_addtime DESC, works_readnum DESC, works_commnum DESC'.$limit;        
        }else{
            $sql = "SELECT * FROM  `daren_works` WHERE works_is_view=1 AND  customers_id=".$id.' ORDER BY works_addtime DESC, works_readnum DESC, works_commnum DESC'.$limit;
        
        }
            $works_res = tep_db_query($sql);    
            $i = 0;
            while($works = tep_db_fetch_array($works_res)){
                $works_array[$i]['works_id'] = $works['works_id'];
                $works_array[$i]['works_title'] = $works['works_title'];
                //$works_array[$i]['works_title'] = $works['works_title'];
                $works_frontcover = substr($works['works_frontcover'],'1');
                if (file_exists($works_frontcover)){
                    $works_array[$i]['works_frontcover'] = $works['works_frontcover'];
                }else{
                    $works_array[$i]['works_frontcover'] = 'image/daren_uploadphoto.gif';
                }
                $works_frontcover_thumb = substr($works['works_frontcover_thumb'],'1');
                if (file_exists($works_frontcover_thumb)){
                    $works_array[$i]['works_frontcover_thumb'] = $works['works_frontcover_thumb'];
                }else{
                    $works_array[$i]['works_frontcover_thumb'] = 'image/daren_uploadphoto.gif';
                }
                
                
                
                $works_array[$i]['works_url'] = $works['works_url'];
                $works_array[$i]['works_addtime'] = $works['works_addtime'];
                $works_array[$i]['works_readnum'] = $works['works_readnum'];
                //$works_array[$i]['works_commnum'] = $works['works_commnum'];
                
                /* 作品评论 */
                $sql = "SELECT COUNT(*) AS rows FROM  `daren_works_comment` WHERE works_comment_is_view=1 AND works_id=".$works['works_id'];
                $works_comment_res = tep_db_query($sql);
                $works_comment_rows = tep_db_fetch_array($works_comment_res);
                $works_array[$i]['works_commnum'] = $works_comment_rows['rows'];
                
                $works_array[$i]['works_votes'] = $works['works_votes'];            
                $works_array[$i]['works_content'] = cutword(strip_tags($works['works_content']),200);
                //$works_array[$i]['works_content'] = cutword(strip_tags($works['works_content']),200);
                $works_array[$i]['works_author'] = $works['works_author'];
                $works_array[$i]['works_is_view'] = $works['works_is_view'];
                $works_array[$i]['customer_id'] = $works['customer_id'];
                $i++;
            }
         
        $smarty->assign('pp',$pp);
        $smarty->assign('user',$user);
        $smarty->assign('pages',$pages);
        $smarty->assign('works',$works_array);
        $smarty->assign('account',$account);        
    }else{
            tep_redirect('trip_player.php?');
    }
    
}
