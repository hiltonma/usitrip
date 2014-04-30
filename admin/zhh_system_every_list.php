<?php
require('includes/application_top.php');
$error_msn = '';
$error = false;
$success_msn = '';

$where = '';

/* 用户名称 */
if (tep_not_null($_GET['admin_user'])){
    $where .= ' and a.admin_email_address Like Binary("%'.$_GET['admin_user'].'%")';
}
$db_query = "SELECT DISTINCT e.admin_id,  a.admin_firstname,a.admin_lastname, a.admin_logdate FROM admin a,everyone_to_read_remind e  where a.admin_id = e.admin_id " .$where;

$words_query_numrows = tep_db_num_rows(tep_db_query($db_query));
// 分页区域
$MAX_DISPLAY_SEARCH_RESULTS_ADMIN = 110;
$words_split = new splitPageResults($_GET['page'], $MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $db_query, $words_query_numrows);

$split_left_content = $words_split->display_count($words_query_numrows, $MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS);
$split_right_content = $words_split->display_links($words_query_numrows, $MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $_GET['page'],tep_get_all_get_params(array('page','y','x', 'action'))); 



$db_query_result = tep_db_query($db_query);
$n = 0;
while($remind_array = tep_db_fetch_array($db_query_result)){       
    /* 发表时间 */
    if(tep_not_null($_GET['added_start_date'])){
        $wheretime .= ' and w.added_time >="'.$_GET['added_start_date'].' 00:00:00" ';
    }
    if(tep_not_null($_GET['added_end_date'])){
        $wheretime .= ' and w.added_time <="'.$_GET['added_end_date'].' 23:59:59" ';
    }
    //$q2 = "SELECT *  FROM ".TABLE_EVERYONE_TO_READ_REMIND." WHERE admin_id=".$remind_array['admin_id']." AND is_expiration=1 AND is_read=0";
    $q2 = "SELECT * FROM ".TABLE_EVERYONE_TO_READ_REMIND." r, zhh_system_words  w WHERE w.words_id=r.words_id and  r.is_expiration=1  and  r.admin_id=".$remind_array['admin_id'] ;
    
    //echo $q2.'<br/>';
    $query = tep_db_query($q2);
    $unread = tep_db_num_rows($query);
    
    if ($unread > 0){ 
        $datas[$n]['id'] = $remind_array['admin_id'];
        $datas[$n]['unread'] = $unread;
        $datas[$n]['name'] = $remind_array['admin_firstname'].'.'.$remind_array['admin_lastname'];
        $datas[$n]['lastlogin'] = $remind_array['admin_logdate'];
        
        //echo "SELECT r.is_read,w.added_time, w.is_adjective, w.words_title, r.login_num, r.expiration_time FROM ".TABLE_EVERYONE_TO_READ_REMIND." r, zhh_system_words  w WHERE w.words_id=r.words_id and  r.is_expiration=1 and  r.admin_id=".$remind_array['admin_id'].'<br/>';
        $db_query_c = tep_db_query("SELECT r.is_read,w.added_time, w.is_adjective, w.words_title, r.login_num, r.expiration_time FROM ".TABLE_EVERYONE_TO_READ_REMIND." r, zhh_system_words  w WHERE w.words_id=r.words_id and  r.is_expiration=1 and  r.admin_id=".$remind_array['admin_id'].' '.$wheretime);
        
        $i = 0;
        while($db_list_array = tep_db_fetch_array($db_query_c)){
            
            
            if ($db_list_array['is_adjective']){
                $login_num =  ($db_list_array['login_num'] - 2) > 0 ? $db_list_array['login_num'] - 2 : 1;
            }else{
                $login_num = ($db_list_array['login_num'] - 4) >0 ? ($db_list_array['login_num'] - 4) : 1;
            }
            
            
            //if ($login_num != 0){
                $datas[$n]['list'][$i]['words_title'] = $db_list_array['words_title'];
                $datas[$n]['list'][$i]['login_num'] = $login_num;
                $datas[$n]['list'][$i]['is_adjective'] = $db_list_array['is_adjective'];
                $datas[$n]['list'][$i]['is_read'] = $db_list_array['is_read'];
                $datas[$n]['list'][$i]['expiration_time'] = $db_list_array['expiration_time'];
                $datas[$n]['list'][$i]['added_time'] = $db_list_array['added_time'];
            //}        
            $i++;
        }
       $n++;
    }
    
}


if (isset($_GET['output'])){        
        $filename = '每日必读过期未读详情_'.$_GET['added_start_date'].'-'.$_GET['added_end_date'];       
        $table = "<tr>";
        $table .= "<th>姓名</th>
                   <th>过期未读数</th>";
        $table .= "</tr>";      
       $db_query_result = tep_db_query($db_query);
        while($remind_array = tep_db_fetch_array($db_query_result)){ 
            $q2 = "SELECT * FROM ".TABLE_EVERYONE_TO_READ_REMIND." r, zhh_system_words  w WHERE w.words_id=r.words_id and  r.is_expiration=1   and  r.admin_id=".$remind_array['admin_id'];            
            $query = tep_db_query($q2);
            $unread = tep_db_num_rows($query);
            if ($unread > 0){
                $table .= "<tr>";
                $table .= "<td>". $remind_array['admin_firstname'].'.'.$remind_array['admin_lastname'] ."</td>";
                $table .= "<td>". $unread ."</td>";            
                $table .= "</tr>";
            }
        }
        $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office"  
        xmlns:x="urn:schemas-microsoft-com:office:excel"  
        xmlns="http://www.w3.org/TR/REC-html40">  
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">  
        <html>  
            <head>  
                <meta http-equiv="Content-type" content="text/html;charset=gbk" />  
                <style id="Classeur1_16681_Styles"></style>  
            </head>  
            <body>  
                <div id="Classeur1_16681" align=center x:publishsource="Excel">  
                    <table x:str border=1 cellpadding=0 cellspacing=0 width=100% style="border-collapse: collapse">  
                        '.
                         $table   
                        .'
                    </table>  
                </div>  
            </body>  
        </html>';
        
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=" .$filename.'.xls' );
        header('Cache-Control:   must-revalidate,   post-check=0,   pre-check=0');
        header('Expires:   0');
        header('Pragma:   public');         
        echo $html;        
        exit;
      
}


if (isset($_POST['action']) && $_POST['action'] == 'remove'){
    if ($_POST['ajax'] == 'true'){
        $admin_id_array = explode(',', $_POST['admin_id']);
        if (tep_not_null($admin_id_array)){
            for ($i=0; $i<count($admin_id_array); $i++){
                tep_db_query("DELETE FROM ". TABLE_EVERYONE_TO_READ_REMIND ." WHERE `admin_id` = '". $admin_id_array[$i] ."' AND `is_expiration`='1'");
            }
        }
        echo '1';
        exit;
    }
}


$main_file_name = "zhh_system_every_list";
$JavaScriptSrc[] = 'includes/jquery-1.3.2/jquery.nyroModal-1.6.2.js';
$JavaScriptSrc[] = 'includes/javascript/global.js';
$JavaScriptSrc[] = 'includes/javascript/calendar.js';

$CssArray = array(); //清空application_top中设置的CSS
$CssArray[] = 'includes/jquery-1.3.2/nyroModal.css';
$CssArray[] = 'css/new_sys_indexDdan.css';
$CssArray[] = 'css/daren.css';
$CssArray[] = "css/global.css";
$CssArray[] = 'includes/javascript/spiffyCal/spiffyCal_v2_1.css';

include_once(DIR_WS_MODULES.'zhh_system_header.php');	//引入头文件
include_once(DIR_FS_DOCUMENT_ROOT.'smarty-2.0/libs/write_smarty_vars.php');

$smarty->display($main_file_name.'.tpl.html');

require(DIR_WS_INCLUDES . 'application_bottom.php');

?>