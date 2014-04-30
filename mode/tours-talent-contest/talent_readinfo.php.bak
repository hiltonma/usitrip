<?php
/* 查看填写的信息*/
!defined('_MODE_KEY') && exit('Access error!');
if(tep_session_is_registered('customer_id') ){
    
    /* 显示用户相关信息 */
    $account = tep_get_customers_info_fix( (int)$customer_id );    
    
    /* 根据国家id得到名称*/
    $sql = tep_db_query("SELECT countries_name FROM  `countries` WHERE countries_id = ".$account['entry_country_id']);
    $country = tep_db_fetch_array($sql);    
    $account['entry_country_id'] = $country['countries_name'];
    /*取得走四方达人相关信息*/
    $sql = "SELECT * FROM  `daren_user_info` WHERE customers_id=".$customer_id;
    $account_query = tep_db_query($sql);
    $account_talent = tep_db_fetch_array($account_query);
    $account['blog_url'] = $account_talent['blog_url'];
    $account['user_job'] = $account_talent['user_job'];
    $account['rem_trip'] = $account_talent['rem_trip'];
    $account['most_to'] = $account_talent['most_to'];
    
    if ($account['entry_city'] == 'undefined'){
        $account['entry_city'] = ' ';
    }
    if ($account['entry_state'] == 'undefined'){
        $account['entry_state'] = ' ';
    }
    
    $src = 'images/face/'.$account['customers_face'];
    $WH = getimgHW3hw_wh($src,114,114);
    $wh_array=explode("@",$WH);
    $account['face_width'] = $wh_array[0];
    $account['face_height'] = $wh_array[1];
    
    $smarty->assign('account',$account);  
    
}else{
    
    tep_redirect('trip_player.php');
}
?>