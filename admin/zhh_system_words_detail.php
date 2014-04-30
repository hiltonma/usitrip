<?php
require('includes/application_top.php');

//默认值设置
if((int)$_GET['dir_id']){
    $dir_id = ((int)$_GET['dir_id']);
}
$pageHeading = "浏览文章";
$WordsHeading = "基本内容";

$words_id = (int)$words_id ? (int)$words_id : (int)$_GET['words_id'];

if((int)$words_id){
    //文章内容
    $words_sql = tep_db_query('SELECT * FROM `zhh_system_words` WHERE words_id="'.$words_id.'" and (FIND_IN_SET( "' . $login_groups_id . '", r_groups_id) || FIND_IN_SET( "' . $login_groups_id . '", rw_groups_id) || FIND_IN_SET( "' . $login_groups_id . '", rwd_groups_id) || admin_id ="'.(int)$login_id.'" || last_admin_id ="'.(int)$login_id.'" ) Limit 1 ');
    $words = tep_db_fetch_array($words_sql);
    if(!(int)$words['words_id']){
        die("您无权浏览此文章，或此文章不存在！");
    }
    $WordsInf = new objectInfo($words);
    foreach((array)$WordsInf as $key => $val){
        $$key = $val;
    }
    $WordsHeading = tep_db_output($words_title);
    //文章所在目录
    $dir_sql = tep_db_query('SELECT dir_id FROM `zhh_words_to_dir` WHERE words_id="'.$words_id.'" ' );
    $dirs_id = '';
    // 取得当前目录的主目录是否是“每日必读” 
    $top_to_current_dirs_arr[] = array();    
    while($dir_rows = tep_db_fetch_array($dir_sql)){
        $dirs_id .=",".$dir_rows['dir_id'];
        $top_to_current_dirs_arr[] = tep_get_top_to_now_class($dir_rows['dir_id']); 
    }
   
    $dirs_id = substr($dirs_id,1);
    
    //文章附件
    $annex_sql = tep_db_query('SELECT * FROM `zhh_system_words_annex` WHERE words_id ="'.$words_id.'" ORDER BY annex_id  ' );
    $annexs = array();
    while($annex_rows = tep_db_fetch_array($annex_sql)){
        $annexs[] = array('id'=>$annex_rows['annex_id'], 'file_name'=>$annex_rows['annex_file_name']);
    }
    $annexs_dir_string = "";
    for($i=0; $i<sizeof($annexs); $i++){
        $file_name = $annexs[$i]['file_name'];
        $file_name_base = explode('.', basename($file_name));	//由于加密码的字符串中已经包括扩展名，所以这里不用再读它
        $annexs_dir_string.= '<div>'.tep_draw_hidden_field('annex_files_name[]',$file_name).'<a href="'.tep_href_link('zhh_system_words_download.php','download=1&annex_id='.$annexs[$i]['id']).'" target="_blank">'.tep_db_output(ascii2string($file_name_base[0],'_')).'</a></div>';
    }
    
    //副标题信息
    $sent_name = tep_get_admin_customer_name($admin_id).'('.$admin_id.')';
    $last_up_per_name = tep_get_admin_customer_name($last_admin_id).'('.$last_admin_id.')';
    
    
    
    
    
}else{
    die("没有ID号");
}
if ((int)$_POST['read'] == 1){
    // 如果是“每日必读文章”浏览后改变为已读状态    
    $words_id = (int)$_POST['words_id'];
    $db_query = tep_db_query("SELECT id FROM ".TABLE_EVERYONE_TO_READ_REMIND." WHERE words_id=".$words_id. " AND admin_id=".(int)$login_id);
    $remind_array = tep_db_fetch_array($db_query);   
    if (tep_not_null($remind_array)){
        $data_array['is_read'] = 1;
        tep_db_perform(TABLE_EVERYONE_TO_READ_REMIND, $data_array, 'update', "id=".$remind_array['id']);
    }
    tep_redirect('zhh_system_words_list.php');
}
//编辑和删除按钮
$EditAHref = '';
$DeleteAHref = '';
$rw_groups_ids = explode(',',$rw_groups_id);
$rwd_groups_ids = explode(',',$rwd_groups_id);
if(in_array($login_groups_id,(array)$rw_groups_ids) || in_array($login_groups_id,(array)$rwd_groups_ids)){
    $EditAHref = '<a href="'.tep_href_link('zhh_system_words_detail_admin.php','words_id='.$words_id).'" class="caozuo">编辑</a>';
}
if(in_array($login_groups_id,(array)$rwd_groups_ids)){
    $DeleteAHref = '<a href="javascript:void(0)" onClick="del_words('.$words_id.',this)" class="caozuo" >删除</a>';
}

// 取得当前目录的主目录是否是“每日必读”   

foreach($top_to_current_dirs_arr as $k => $v){    
    for($i=0; $i<count($v); $i++){
        $current_str .= $v[$i]['text'].',';
    }
}
/*
echo $top_to_current_dirs_arr_str;
$top_to_current_dirs_arr = tep_get_top_to_now_class($dirs_id); 
$current_str = '';
foreach($top_to_current_dirs_arr as $key=>$value){
    $current_str .= $value['text'].','; 	
}*/




$none_div = '<div style="display:none">'.$current_str;
if (preg_match('/每日必读/',  $current_str)){
    $have_everyone = true;
}else{
    $have_everyone = false;
}
$none_div .= $have_everyone.'</div>';
echo $none_div;
$main_file_name = "zhh_system_words_detail";
$JavaScriptSrc[] = 'includes/javascript/'.$main_file_name.'.js';
$CssArray = array(); //清空application_top中设置的CSS
$CssArray[] = 'css/new_sys_indexDdan.css';

//js 代码中的变量
$p = array('/&amp;/', '/&quot;/');
$r = array('&', '"');
$js_urladdress = preg_replace($p,$r,tep_href_link_noseo('zhh_system_words_detail_admin.php','ajax=true&action=delete'));

$breadcrumb->add($WordsHeading, tep_href_link('zhh_system_words_detail.php','words_id='.$words_id));

include_once(DIR_WS_MODULES.'zhh_system_header.php');	//引入头文件
include_once(DIR_FS_DOCUMENT_ROOT.'smarty-2.0/libs/write_smarty_vars.php');

$smarty->display($main_file_name.'.tpl.html');

require(DIR_WS_INCLUDES . 'application_bottom.php');
?>