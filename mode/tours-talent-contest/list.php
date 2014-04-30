<?php
/* 作品列表 */
!defined('_MODE_KEY') && exit('Access error!');
 /* 最新发布的作品列表 */
 $sql = "SELECT COUNT(*) AS rows FROM  `daren_works`  WHERE works_is_view =1 ";
 $works_comment_res = tep_db_query($sql);
 $works_comment_rows = tep_db_fetch_array($works_comment_res);
        
 include('mode/tours-talent-contest/lib/function.php');
 $total = (int)$works_comment_rows["rows"];
 
 $db_perpage = 8;
 $url = 'trip_player.php?mod=list&pp=';
 if (isset($_GET['pp']) && (int)$_GET['pp']){
    $pp = (int)$_GET['pp'];
    $pphotshow = 0;
 }else{
    $pp = 1;
    
 }
 $pages = makePager1($total,$url,$pp,$db_perpage,false);        
 $limit = " LIMIT ".($pp-1)*$db_perpage.",$db_perpage;"; 
 
 $sql = "SELECT works_id,customers_id, works_title, works_frontcover, works_frontcover_thumb, works_addtime, works_author,works_content FROM  `daren_works` WHERE works_is_view =1 ORDER BY works_addtime DESC ".$limit;
 $work_list_res = tep_db_query($sql);
 $data = array();
 $i = 0;
 while($works = tep_db_fetch_array($work_list_res)){
    $neworks[$i]['works_id'] = $works['works_id'];
    $neworks[$i]['works_title'] = tep_db_output($works['works_title']);
    //$neworks[$i]['works_title'] = cutword(strip_tags($works['works_title']),20);
    
    $worksworks_frontcover = substr($works['works_frontcover'],1);
    if (file_exists($worksworks_frontcover)){
        $neworks[$i]['works_frontcover'] = $works['works_frontcover'];           
    }else{
        $neworks[$i]['works_frontcover'] = '';   
    }
    $neworks_works_frontcover_thumb = substr($works['works_frontcover_thumb'],1);
    if (file_exists($neworks_works_frontcover_thumb)){
        $neworks[$i]['works_frontcover_thumb'] = $works['works_frontcover_thumb'];
    }else{
        $neworks[$i]['works_frontcover_thumb'] = '';
    }
    
    $neworks[$i]['works_addtime'] = $works['works_addtime'];
    $neworks[$i]['works_author'] = tep_db_output($works['works_author']);
    $neworks[$i]['customers_id'] = tep_db_output($works['customers_id']);
    $works_content = cutword(preg_replace('/[[:space:]]+/','',strip_tags($works['works_content'])),56);  
    $works_content = str_replace('　','',$works_content);
    $works_content = str_replace('&nbsp;','',$works_content);
    $neworks[$i]['works_content'] = $works_content;
    //$neworks[$i]['works_author'] = $works['works_author'];
    //$neworks[$i]['works_content'] = cutword(strip_tags($works['works_content']),35);    
    $i++;
 }

 
 
 /* 人气排行列表 */
 $sql1 = "SELECT COUNT(*) AS rows FROM  `daren_works`  WHERE works_is_view =1 ";
 $works_comment_res1 = tep_db_query($sql1);
 $works_comment_rows1 = tep_db_fetch_array($works_comment_res1);

 $total1 = (int)$works_comment_rows1["rows"];
 $url = 'trip_player.php?mod=list&pphot=';
 if (isset($_GET['pphot']) && (int)$_GET['pphot']){
    $pphot = (int)$_GET['pphot'];
    $pphotshow = 1;
 }else{
    $pphot = 1;
    
 }
 $pageshot = makePager1($total1,$url,$pphot,$db_perpage,false);        
 $limithot = " LIMIT ".($pphot-1)*$db_perpage.",$db_perpage;"; 
 
 $sql1 = "SELECT works_id,customers_id, works_title, works_frontcover, works_frontcover_thumb, works_addtime, works_votes, works_author,works_content FROM  `daren_works`  WHERE works_is_view =1  ORDER BY works_votes DESC ".$limithot;
 $work_list_res1 = tep_db_query($sql1); 
 $n = 0;
 while($votes_array = tep_db_fetch_array($work_list_res1)){    
    $votes[$n]['works_id'] = $votes_array['works_id'];
    $votes[$n]['works_title'] = tep_db_output($votes_array['works_title']);
    //$votes[$n]['works_title'] = cutword(strip_tags($votes_array['works_title']),20);
    $works_frontcover = substr($votes_array['works_frontcover'],1);  
    if (file_exists($works_frontcover)){
        $votes[$n]['works_frontcover'] = $votes_array['works_frontcover'];
    }else{
        $votes[$n]['works_frontcover'] = '';
    }
    $votes_array_works_frontcover_thumb = substr($votes_array['works_frontcover_thumb'],1);
    if(file_exists($votes_array_works_frontcover_thumb)){
        $votes[$n]['works_frontcover_thumb'] = $votes_array['works_frontcover_thumb'];
    }else{
        $votes[$n]['works_frontcover_thumb'] = '';
    }
    
    $votes[$n]['works_votes'] = $votes_array['works_votes'];
    $votes[$n]['works_addtime'] = $votes_array['works_addtime'];
    $votes[$n]['works_author'] = $votes_array['works_author'];
    $votes[$n]['customers_id'] = $votes_array['customers_id'];
    $works_content = cutword(preg_replace('/[[:space:]]+/','',strip_tags($votes_array['works_content'])),56); 
    $works_content = str_replace('　','',$works_content);
    $works_content = str_replace('&nbsp;','',$works_content);
    $votes[$n]['works_content'] = $works_content;
    //$votes[$n]['works_author'] = $votes_array['works_author'];
    //$votes[$n]['works_content'] = cutword(strip_tags($votes_array['works_content']),35); 
    $n++;
 }

 
 $smarty->assign('votes',$votes);
 $smarty->assign('neworks',$neworks);
 $smarty->assign('pagehot',$pageshot);
 $smarty->assign('pages',$pages);
 $smarty->assign('pphotshow',$pphotshow);
