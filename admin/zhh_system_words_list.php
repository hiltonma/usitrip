<?php
require('includes/application_top.php');
$error_msn = '';
$error = false;
$success_msn = '';
$dir_id = (int)$_GET['dir_id'];
if ($dir_id == 0){
    $dir_id = 45;
}
$can_create_words = false;
// 判断当前栏目是否是 “每日必读”栏目
$dir_ids = tep_get_top_to_now_class($dir_id);
$current_str = '';
foreach($dir_ids as $key=>$value){
    $current_str .= $value['text'].','; 	
}  
if (preg_match('/每日必读/',  $current_str)){
    $have_everyone = true;
}else{
    $have_everyone = false;
}


//判断当前目录下用户能否添加文章
if($login_groups_id==1){
    $can_create_words = true;
}elseif((int)$dir_id){
    $check_dir_sql = tep_db_query('SELECT * FROM `zhh_system_dir` WHERE dir_id="'.$dir_id.'" ');
    $check_dir = tep_db_fetch_array($check_dir_sql);
    if(!(int)$check_dir['dir_id']){ echo ("不存在的目录！").(int)$dir_id; }
    $rwd_array = explode(',',$check_dir['rwd_groups_id']);
    $rw_array = explode(',',$check_dir['rw_groups_id']);
    $r_array = explode(',',$check_dir['r_groups_id']);
    if(in_array($login_groups_id,$rwd_array)){
        $can_create_words = true;
    }elseif(in_array($login_groups_id,$rw_array)){
        $can_create_words = true;
    }
}

//条件

$where = " WHERE (FIND_IN_SET('".$login_groups_id."',rwd_groups_id) || FIND_IN_SET('".$login_groups_id."',rw_groups_id) || FIND_IN_SET('".$login_groups_id."',r_groups_id))  and sw.words_id = wtd.words_id ";


if((int)$_GET['dir_id']){
    $dir_ids_string = $dir_id; 
    $dir_ss = $dir_id;
    $dir_ids = tep_get_class_tree($dir_id,'','','', false, true, true);       
    foreach((array)$dir_ids as $key => $val){
        if((int)$val['id']){ $dir_ids_string .= ','.$val['id']; }
    }
    
    if ($have_everyone){
        
        $sql = tep_db_query('select * from `zhh_system_dir` where parent_id = "'.(int)$dir_id.'" ');
        $ever_ids[] = $dir_id;
        while($row = tep_db_fetch_array($sql)){
            $ever_ids[] = $row['dir_id'];
        }	
        $dir_ids_string = '';
        for($i=0;$i<count($ever_ids);$i++){
            if ($i == count($ever_ids) - 1){
                $dir_ids_string .= $ever_ids[$i];
            }else{
                $dir_ids_string .= $ever_ids[$i].',';
            }
        }
        
    }    
    $where .= ' and wtd.dir_id in('.$dir_ids_string.') ';
}

if(tep_not_null($_GET['keyword'])){
    $keyword = tep_db_prepare_input($_GET['keyword']);
    $is_serch_content = (int)$_GET['is_serch_content'];
    if (!$is_serch_content){
        
        $checked = 0;
        $where .= ' and sw.words_title Like Binary ("%'.$keyword.'%")';
    }else{
        $checked = 1;
        //$where .= ' AND CONCAT(sw.words_title, sw.words_content) Like Binary ("%'.$keyword.'%") ';
        $where .= ' AND (sw.words_title  Like Binary ("%'.$keyword.'%") or sw.words_content Like Binary ("%'.$keyword.'%")) ';
    }
     
}

if(tep_not_null($_GET['added_start_date'])){
    $where .= ' and sw.added_time >="'.$_GET['added_start_date'].' 00:00:00" ';
}
if(tep_not_null($_GET['added_end_date'])){
    $where .= ' and sw.added_time <="'.$_GET['added_end_date'].' 23:59:59" ';
}
if(tep_not_null($_GET['updated_start_date'])){
    $where .= ' and sw.updated_time >="'.$_GET['updated_start_date'].' 00:00:00" ';
}
if(tep_not_null($_GET['updated_end_date'])){
    $where .= ' and sw.updated_time <="'.$_GET['updated_end_date'].' 23:59:59" ';
}
if (tep_not_null($_GET['provider'])){
    $provider = tep_db_prepare_input($_GET['provider']);
    $where .= ' and sw.provider Like Binary ("%'.$provider.'%") ';
}
if (tep_not_null($_GET['adjective'])){
    $adjective = (int)$_GET['adjective'];
    $where .= ' and  sw.is_adjective = '.$adjective;
}
if ($have_everyone){    

    if (tep_not_null($_GET['serch_admin_name'])){
        $serch_admin_name = tep_db_prepare_input($_GET['serch_admin_name']);
        $where .= ' and admin.admin_email_address Like Binary("%'.$serch_admin_name.'%")  AND admin.admin_id = sw.admin_id' ;
    }
   
}
//排序

$order_by = " ORDER BY sw.added_time DESC ";


//分页
if ($have_everyone){    
   
    if (tep_not_null($_GET['adjective'])){
        $adjective = (int)$_GET['adjective'];
        $where = ' sw.is_adjective = '.$adjective;
        $where .= '  AND  r.admin_id = '.$login_id.' AND r.is_read=0 AND sw.words_id=r.words_id';
        $order_by = " ORDER BY sw.added_time DESC";
        $words_query_raw = 'SELECT sw.admin_id,sw.words_id,sw.words_title,sw.words_content,sw.added_time,sw.updated_time,sw.r_groups_id,sw.rw_groups_id,sw.rwd_groups_id,sw.r_everyone_group_ids,sw.is_adjective FROM zhh_system_words sw, '.TABLE_EVERYONE_TO_READ_REMIND .' r WHERE '.$where.' Group By sw.words_id '.$order_by;
    }else{
        $words_query_raw = 'SELECT  sw.admin_id,sw.words_id,sw.words_title,sw.words_content,sw.added_time,sw.updated_time,sw.r_groups_id,sw.rw_groups_id,sw.rwd_groups_id,sw.r_everyone_group_ids,sw.is_adjective FROM zhh_system_words sw, zhh_words_to_dir wtd, admin '.$where.' Group By sw.words_id '.$order_by;
    }
    
}else{    
    $words_query_raw = 'SELECT  sw.admin_id,sw.words_id,sw.words_title,sw.words_content,sw.added_time,sw.updated_time,sw.r_groups_id,sw.rw_groups_id,sw.rwd_groups_id,sw.r_everyone_group_ids,sw.is_adjective FROM zhh_system_words sw, zhh_words_to_dir wtd '.$where.' Group By sw.words_id '.$order_by;
}

$words_query_numrows = 0;
$MAX_DISPLAY_SEARCH_RESULTS_ADMIN = min(20,MAX_DISPLAY_SEARCH_RESULTS_ADMIN);
$words_split = new splitPageResults($_GET['page'], $MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $words_query_raw, $words_query_numrows);

$words_query = tep_db_query($words_query_raw);
$words = tep_db_fetch_array($words_query);

//js 代码中的变量
$p = array('/&amp;/', '/&quot;/');
$r = array('&', '"');
$js_urladdress = preg_replace($p,$r,tep_href_link_noseo('zhh_system_words_detail_admin.php','ajax=true&action=delete'));

//搜索区 fieldset start
$input_field_keyword = tep_draw_input_field('keyword','','class="textAll"');
$input_field_provider = tep_draw_input_field('provider', $provider, 'class="textAll"');
$input_field_serch_admin_name = tep_draw_input_field('serch_admin_name', $serch_admin_name, 'class="textAll"');
/*
if ($checked == 1){
    //$input_field_isserch_content = tep_draw_checkbox_field('is_serch_content','1', true);
    $input_field_isserch_content = '<input type="checkbox" name="is_serch_content" checked="checked" value="1"/>';
}else{
    $input_field_isserch_content = '<input type="checkbox" name="is_serch_content"  value="1"/>';
}
*/
// 如果为“每日必读”栏目,则统计未读条目
if ($have_everyone){
    //    
    $read_db_query = tep_db_query("SELECT  r.admin_id, r.is_read, w.is_adjective FROM  ".TABLE_EVERYONE_TO_READ_REMIND." AS r, zhh_system_words  AS w WHERE w.words_id = r.words_id AND r.is_read=0 AND  r.admin_id=".$login_id);
    $adjective = 0;
    $unadjective = 0;
    $unread = 0;
    $allread = 0;
    while ($read_array = tep_db_fetch_array($read_db_query)){
        if ($read_array['is_read'] == 0){
            $unread += 1;
        }
        if ($read_array['is_adjective'] == 1){
            $adjective +=1;
        }else{
            $unadjective +=1;
        }
        $allread += 1;
    }   
}
$dir_option = tep_get_class_tree(0, '','', '', true, true, true);
$dir_option[0]=array('id'=>0, 'text'=>'不限');
$pull_down_menu_dir_id = tep_draw_pull_down_menu('dir_id',$dir_option,'','class="selectText"');
$href_link_zhh_system_words_list = tep_href_link('zhh_system_words_list.php','dir_id=0');
//搜索区 fieldset end

//列表区 start
$datas="";
$n = 0;
if((int)$words['words_id']){
  do{      
    
      //所属目录
      $this_dirs = get_words_dirs($words['words_id']);
      $this_dirs_string = "";
      for($i=0; $i<sizeof($this_dirs); $i++){
          $this_dirs_string_sss = "";
          for($j=max(0, sizeof($this_dirs[$i])-2); $j>=0;  $j--){
              $this_dirs_string_sss.= '&gt; <a href="'.tep_href_link('zhh_system_words_list.php','dir_id='.$this_dirs[$i][$j]['id']).'">'. $this_dirs[$i][$j]['text'].'</a> ';
          }
          $this_dirs_string .= substr($this_dirs_string_sss,4);
          $this_dirs_string .="<br>";
      }
      //附件
      $annexs = "";
      $annex_sql = tep_db_query('SELECT * FROM `zhh_system_words_annex` WHERE words_id="'.$words['words_id'].'" ORDER BY annex_id ASC ');
      while($annex = tep_db_fetch_array($annex_sql)){
          $annexs .= '<div><a href="'.$annex['annex_file_name'].'" target="_self">'.preg_replace('/.*\//','',$annex['annex_file_name']).'</a></div>';
      }
      //删除和编辑按钮
      $del_button = "";
      $edit_button = "";
      $rwd_array = explode(',',$words['rwd_groups_id']);
      $rw_array = explode(',',$words['rw_groups_id']);
      
      if(in_array($login_groups_id,(array)$rwd_array)){
          $del_button = '<a href="javascript:void(0)" onClick="del_words('.$words['words_id'].',this)" class="dosome" >删除</a>'."&nbsp;";
      }
      if(tep_not_null($del_button) || in_array($login_groups_id,(array)$rw_array)){
          $edit_button = '<a href="'.tep_href_link('zhh_system_words_detail_admin.php','words_id='.$words['words_id']).'" class="dosome" >编辑</a>'."&nbsp;";
      }
      
      $rw_array = explode(',',$words['rw_groups_id']);
      //查看按钮
      $view_button = '[<a href="'.tep_href_link('zhh_system_words_detail.php','words_id='.$words['words_id']).'" class="dosome" >查看</a>]&nbsp;';
      
      $datas[$n]=$words;
      $datas[$n]['words_title'] = '<a class="indexDdanyjXxadr" href="'.tep_href_link('zhh_system_words_detail.php','words_id='.$words['words_id']).'" >'.$words['words_title'].'</a>';
      $datas[$n]['view_button'] = $view_button;
      $datas[$n]['edit_button'] = $edit_button;
      $datas[$n]['del_button'] = $del_button;
      $datas[$n]['this_dirs_string'] = $this_dirs_string;
      $datas[$n]['annexs'] = $annexs;      
      $datas[$n]['sent_name'] = tep_get_admin_customer_name($words['admin_id']);
      $datas[$n]['up_per_name'] = tep_get_admin_customer_name($words['last_admin_id']);
      if($have_everyone){
          
          $datas[$n]['is_read'] = $words['is_read'];
          $is_read_query = tep_db_query("SELECT * FROM ".TABLE_EVERYONE_TO_READ_REMIND." WHERE admin_id=".$login_id." AND words_id=".$words['words_id']);
          $is_read_array = tep_db_fetch_array($is_read_query);
          
          $is_read_num_rows = tep_db_num_rows($is_read_query);
          if ($is_read_num_rows > 0){
                $datas[$n]['is_read'] = $is_read_array['is_read'];
                $datas[$n]['is_adjective'] = $words['is_adjective'];
          }else{
                $datas[$n]['is_read'] = '1';
                $datas[$n]['is_adjective'] = $words['is_adjective'];
          }
          
          // 查询本条文章是否为自己添加
          $is_my_word_query = tep_db_query("SELECT admin_id FROM zhh_system_words WHERE words_id='".$words['words_id']."'");
          $is_my_word = tep_db_fetch_array($is_my_word_query);
          $work_admin_id = $is_my_word['admin_id'];
          if ($work_admin_id == $login_id){
            $datas[$n]['is_my_work'] = '1';
          }else{
            $datas[$n]['is_my_work'] = '0';  
          }
          
          
      }
      
      $n++;
  }while($words = tep_db_fetch_array($words_query));
}

//列表区 end
//print_r($datas);
//新增文章和管理目录按钮
$href_link_create_words="";
$href_link_admin_dir="";
if($can_create_words==true){
    $href_link_create_words = tep_href_link('zhh_system_words_detail_admin.php','dir_id='.$dir_id);
    if($login_groups_id==1 || $login_groups_id==5){
        $d_params = "";
        if((int)$dir_id){
            $d_params = "parent_id=".$dir_id;
        }
        $href_link_admin_dir = tep_href_link('zhh_system_dir_admin.php',$d_params);
    }
}

//分页区域
$split_left_content = $words_split->display_count($words_query_numrows, $MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS);
$split_right_content = $words_split->display_links($words_query_numrows, $MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $_GET['page'],tep_get_all_get_params(array('page','y','x', 'action'))); 

foreach($breadcrumb as $k=>$v){
    for ($i=0; $i<count($v); $i++){
       
        if ($v[$i]['title'] == 'Top'){
            if ($i == count($v)-1){
                $Bread .='<a href="'.$v[$i]['link'].'">职员培训系统</a>';
            }else{
                $Bread .='<a href="'.$v[$i]['link'].'">职员培训系统</a> ->';
            }
            
        }else{
            if ($i == count($v)-1){
                $Bread .='<a href="'.$v[$i]['link'].'">'.$v[$i]['title'].'</a>';
            }else{
                $Bread .='<a href="'.$v[$i]['link'].'">'.$v[$i]['title'].'</a> ->';
            }
        }
    }
}
$main_file_name = "zhh_system_words_list";
$JavaScriptSrc[] = 'includes/jquery-1.3.2/jquery.nyroModal-1.6.2.js';
$JavaScriptSrc[] = 'includes/javascript/'.$main_file_name.'.js';
$JavaScriptSrc[] = 'includes/javascript/calendar.js';
$CssArray = array(); //清空application_top中设置的CSS
$CssArray[] = 'includes/jquery-1.3.2/nyroModal.css';
$CssArray[] = 'css/new_sys_indexDdan.css';
$CssArray[] = 'css/new_sys_index.css';
$CssArray[] = 'includes/javascript/spiffyCal/spiffyCal_v2_1.css';

include_once(DIR_WS_MODULES.'zhh_system_header.php');	//引入头文件
include_once(DIR_FS_DOCUMENT_ROOT.'smarty-2.0/libs/write_smarty_vars.php');

$smarty->display($main_file_name.'.tpl.html');

require(DIR_WS_INCLUDES . 'application_bottom.php');

?>