<?php
//培训系统导航菜单，属于左框架的内容
require('includes/application_top.php');
/**
$open_dir_id = "";
if((int)$_GET['open_dir_id']){	//当有参数传递时默认打开到该参数的dir
	$open_dir_id = (int)$_GET['open_dir_id'];
}


function get_menu_tree($parentid = 0, $has_loop_num = 0){
	global $login_groups_id;
	$where_permissions = ' and (FIND_IN_SET( "' . $login_groups_id . '", r_groups_id) || FIND_IN_SET( "' . $login_groups_id . '", rw_groups_id) || FIND_IN_SET( "' . $login_groups_id . '", rwd_groups_id)) ';
	$sql = "select * from zhh_system_dir where parent_id = {$parentid} {$where_permissions} order by sort_num asc";
	$result = tep_db_query($sql);
	
	$u_class = "ul_show";
	if((int)$has_loop_num){
		$u_class = "ul_hide";
	}
	
	while($row = tep_db_fetch_array($result)){
		$has_loop_num++;
		//检查是否还有下一级子目录
		$sql1 = tep_db_query("select dir_id from zhh_system_dir where parent_id = {$row['dir_id']} {$where_permissions} Limit 1 ");
		$row1 = tep_db_fetch_array($sql1);
		$s_class = "div_no_child";
		if((int)$row1['dir_id']){
		  $s_class = "div_add";
		}
		$id_string = $row['parent_id'].'_'.$row['dir_id'];
		$return_str .= '<ul class="'.$u_class.'" id="'.$id_string.'"><li id="li_'.$id_string.'">';
		$return_str .= '<div class="'.$s_class.'" id="div_'.$id_string.'"><a href="'.tep_href_link('zhh_system_words_list.php','dir_id='.$row['dir_id']).'">'.$row['dir_name']."</a></div>\n";
		if((int)$row1['dir_id']){
			$return_str .= get_menu_tree($row['dir_id'], $has_loop_num);
		}
		$return_str .= "</li></ul>"."\n";
	}
	return $return_str;
}
$dir_tree = get_menu_tree();
//print_r($dir_tree);
//exit;
**/
$main_file_name = "daren_system_left_menu";
$JavaScriptSrc[] = 'includes/javascript/global.js';
$CssArray = array();
$CssArray[] = "css/daren.css";
$CssArray[] = "css/global.css";



include_once(DIR_FS_DOCUMENT_ROOT.'smarty-2.0/libs/write_smarty_vars.php');

$smarty->display($main_file_name.'.tpl.html');

require(DIR_WS_INCLUDES . 'application_bottom.php');

?>