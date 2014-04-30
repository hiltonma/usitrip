<?php
require('includes/application_top.php');

$error_msn = '';
$noull_selcet_width = ' class="selectText" style=" min-width:200px; height:auto; " ';
$all_selcet_width = ' class="selectText" style=" color:#CCCCCC; min-width:200px; height:auto; " ';

if (tep_not_null($_POST['submit_action'])) {
    switch ($_POST['submit_action']) {
        case 'Add':
            $add_class_name = tep_db_prepare_input($_POST['add_class_name']);
            $dir_description = tep_db_prepare_input($_POST['dir_description']);

            $add_class_name_array = explode(';', $add_class_name);
            for ($i = 0; $i < count($add_class_name_array); $i++) {
                $add_parent_id = (int)$_POST['add_parent_id'];
                if (tep_not_null($add_class_name_array[$i])) {
                    $check_sql = tep_db_query('select dir_id from zhh_system_dir where dir_name="' . $add_class_name_array[$i] . '" AND parent_id = "' . (int)$add_parent_id . '" limit 1');
                    $check_row = tep_db_fetch_array($check_sql);
                    if ((int)$check_row['dir_id']) {
                        $error_msn .= '不能有重复的同级子目录！ <br>';
                    }
                    if (!tep_not_null($error_msn)) {
                        $r_groups_id =$rw_groups_id =$rwd_groups_id = "1";
						$parent_permissions_sql = tep_db_query('select r_groups_id,rw_groups_id,rwd_groups_id from zhh_system_dir where dir_id = "' . (int)$add_parent_id . '" limit 1');
						$parent_permissions = tep_db_fetch_array($parent_permissions_sql);
						if(tep_not_null($parent_permissions['r_groups_id'])){	//默认权限为继承上一级目录的权限
							$r_groups_id = $parent_permissions['r_groups_id'];
						}
						if(tep_not_null($parent_permissions['rw_groups_id'])){	//默认权限为继承上一级目录的权限
							$rw_groups_id = $parent_permissions['rw_groups_id'];
						}
						if(tep_not_null($parent_permissions['rwd_groups_id'])){	//默认权限为继承上一级目录的权限
							$rwd_groups_id = $parent_permissions['rwd_groups_id'];
						}
						
						$sql_data_array = array('dir_name' => $add_class_name_array[$i],
                            					'parent_id' => $add_parent_id, 
												'r_groups_id' => $r_groups_id,
												'rw_groups_id' => $rw_groups_id,
												'rwd_groups_id' => $rwd_groups_id
												);
                        tep_db_perform('zhh_system_dir', $sql_data_array);
                        $dir_id = tep_db_insert_id();
                        tep_db_query('update zhh_system_dir set sort_num="'.$dir_id.'" where dir_id="'.$dir_id.'" ');
						
						$sql_data_array = array();
                        $sql_data_array = array('dir_id' => $dir_id,
                            'dir_description' => $dir_description,
                        );
                        tep_db_perform('zhh_system_dir_description', $sql_data_array);

                        $done_msn = '添加成功。 ';

                        tep_db_query('OPTIMIZE TABLE `zhh_system_dir`,`zhh_system_dir_description` ');
                    }
                } else {
                    $error_msn .= '不能添加空的类别。 <br>';
                }
            }
            break;
        case 'Update':
            $dir_name = tep_db_prepare_input($_POST['dir_name']);
			$sort_num = (int)$_POST['sort_num'];
            $dir_description = tep_db_prepare_input($_POST['dir_description']);

            $dir_id = (int)$_POST['dir_id'];
            if (!tep_not_null($dir_name)) {
                $error_msn .= '类别名称不能为空。 ';
            }
			$r_groups_id = implode(',',(array)$_POST['r_groups_ids']);
            if(!in_array('1',(array)$_POST['r_groups_ids'])){
				$r_groups_id = '1,'.$r_groups_id;
			}
			$rw_groups_id = implode(',',(array)$_POST['rw_groups_ids']);
            if(!in_array('1',(array)$_POST['rw_groups_ids'])){
				$rw_groups_id = '1,'.$rw_groups_id;
			}
			$rwd_groups_id = implode(',',(array)$_POST['rwd_groups_ids']);
            if(!in_array('1',(array)$_POST['rwd_groups_ids'])){
				$rwd_groups_id = '1,'.$rwd_groups_id;
			}
			$r_groups_id = preg_replace('/,$/','',$r_groups_id);
			$rw_groups_id = preg_replace('/,$/','',$rw_groups_id);
			$rwd_groups_id = preg_replace('/,$/','',$rwd_groups_id);
			if (!tep_not_null($error_msn)) {
                $sql_data_array = array('dir_name' => $dir_name, 
										'sort_num' => $sort_num, 
										'r_groups_id' => $r_groups_id,
										'rw_groups_id' => $rw_groups_id,
										'rwd_groups_id' => $rwd_groups_id
										);
                tep_db_perform('zhh_system_dir', $sql_data_array, 'update', ' dir_id="' . (int)$dir_id . '"');

                $sql_data_array = array();
                $sql_data_array = array('dir_description' => $dir_description);
                $check_class_d_sql = tep_db_query('SELECT dir_id FROM `zhh_system_dir_description` WHERE dir_id="' . (int)$dir_id . '"');
                $check_class_d_row = tep_db_fetch_array($check_class_d_sql);
                if (!(int)$check_class_d_row['dir_id']) {
                    $sql_data_array['dir_id'] = (int)$dir_id;
                    tep_db_perform('zhh_system_dir_description', $sql_data_array);
                } else {
                    tep_db_perform('zhh_system_dir_description', $sql_data_array, 'update', ' dir_id="' . (int)$dir_id . '"');
                }
				
				//更新当前目录下的所有子级目录权限
				if($_POST['permissions_all_child']=="1" && (int)$dir_id){
					//echo $r_groups_id; exit;
					$dirs = tep_get_class_tree((int)$dir_id,'','','', false, true);
					$dirs[]['id'] = (int)$dir_id;
					foreach((array)$dirs as $key => $val){
						//读
						tep_db_query('update zhh_system_dir set r_groups_id="'.$r_groups_id.'" where dir_id="'.(int)$val['id'].'"');
						//更新文章权限
						$w_sql = tep_db_query('SELECT words_id from zhh_words_to_dir where dir_id = "'.(int)$val['id'].'" Group By words_id ');
						while($w_rows = tep_db_fetch_array($w_sql)){
							tep_db_query('update zhh_system_words set r_groups_id ="'.$r_groups_id.'" where words_id="'.$w_rows['words_id'].'" ');
						}
						//写
						tep_db_query('update zhh_system_dir set rw_groups_id="'.$rw_groups_id.'" where dir_id="'.(int)$val['id'].'" ');
						//更新文章权限
						@mysql_data_seek($w_sql,0);
						while($w_rows = tep_db_fetch_array($w_sql)){
							tep_db_query('update zhh_system_words set rw_groups_id ="'.$rw_groups_id.'" where words_id="'.$w_rows['words_id'].'" ');
						}
						//全部权限
						tep_db_query('update zhh_system_dir set rwd_groups_id="'.$rwd_groups_id.'" where dir_id="'.(int)$val['id'].'" ');
						//更新文章权限
						@mysql_data_seek($w_sql,0);
						while($w_rows = tep_db_fetch_array($w_sql)){
							tep_db_query('update zhh_system_words set rwd_groups_id ="'.$rwd_groups_id.'" where words_id="'.$w_rows['words_id'].'" ');
						}
					}
				}
				
				

                tep_db_query('OPTIMIZE TABLE `zhh_system_dir`,`zhh_system_dir_description` ');
                $done_msn = '目录更新成功。 ';
            }

            break;
        case 'Delete':
            $dir_id = (int)$_POST['dir_id'];
            if ((int)$dir_id) {
                $class_ids = tep_get_class_tree($dir_id, '', '', '', 'true', 'true');
                $class_idstr = $dir_id;
                foreach ((array) $class_ids as $key => $val) {
                    $class_idstr .= ',' . $val['id'];
                }
                tep_db_query('DELETE FROM `zhh_system_dir` WHERE `dir_id` in(' . $class_idstr . ') ');
                tep_db_query('DELETE FROM `zhh_system_dir_description` WHERE `dir_id` in(' . $class_idstr . ') ');

                tep_db_query('OPTIMIZE TABLE `zhh_system_dir`,`zhh_system_dir_description` ');
                $done_msn = '目录删除Ok';
                tep_redirect(tep_href_link('zhh_system_dir_admin.php', 'done_msn=' . $done_msn));
            }

            break;
    }
}

//搜索模块 start
$input_field_search_class_name = tep_draw_input_field('search_class_name','','class="textAll"');
if ($_GET['action'] == 'search' && $_GET['search_class_name']) {
    $search_class_name = tep_db_prepare_input($_GET['search_class_name']);
    $search_result="";
	$sql = tep_db_query('SELECT * FROM `zhh_system_dir` WHERE dir_name Like "%' . $search_class_name . '%" Order By parent_id ');
    while ($rows = tep_db_fetch_array($sql)) {
        $search_result.= '<a href="' . tep_href_link('zhh_system_dir_admin.php', 'parent_id=' . (int)$rows['dir_id']) . '">' . $rows['dir_id'] . '&nbsp; &nbsp;' . tep_db_output($rows['dir_name']) . '</a><br>';
    }
}
//搜索模块 end

//当前目录树 start
$now_class_value = '';
$parent_id = (int)$parent_id;
$now_class = tep_get_top_to_now_class($parent_id);
$now_class_str = '';
for ($i = (int)(count($now_class) - 1); $i >= 0; $i--) {
    if ($parent_id == $now_class[$i]['id']) {
        $now_class_str .= '<a href="' . tep_href_link('zhh_system_dir_admin.php', 'parent_id=' . $now_class[$i]['id']) . '"><b>' . $now_class[$i]['text'] . '</b></a> &gt;&gt; ';
        $now_class_value = $now_class[$i]['text'];
    } else {
        $now_class_str .= '<a href="' . tep_href_link('zhh_system_dir_admin.php', 'parent_id=' . $now_class[$i]['id']) . '">' . $now_class[$i]['text'] . '</a> &gt;&gt; ';
    }
}

$parent_id = (int)$parent_id;
$class_tree = tep_get_class_tree($parent_id, ' | ', $parent_id, '', 'true', 'true');
$class_tree_string = "";
foreach ((array) $class_tree as $key => $val) {
	if ($val['text'] != '') {
		$class_tree_string .= '<a href="' . tep_href_link('zhh_system_dir_admin.php', 'parent_id=' . (int)$val['id']) . '">' . $val['text'] . '</ a><br>';
	}
}

$style = '';
if ((int)$parent_id == 0) {
	$style = ' readonly="true" ';
}
$input_field_dir_name = tep_draw_input_field('dir_name', $now_class_value, $style);
$sql = tep_db_query('SELECT * FROM `zhh_system_dir` c , `zhh_system_dir_description` cd WHERE c.dir_id="' . (int)$parent_id . '" AND cd.dir_id = c.dir_id limit 1');
$rows = tep_db_fetch_array($sql);
if ((int)$rows['dir_id']) {
	$classInf = new objectInfo($rows);
	$dir_description = $classInf->dir_description;
	$sort_num = $classInf->sort_num;
	$r_groups_id = $classInf->r_groups_id;
	$rw_groups_id = $classInf->rw_groups_id;
	$rwd_groups_id = $classInf->rwd_groups_id;
}
//当前目录
$input_field_sort_num = tep_draw_input_field('sort_num', $sort_num, ' size="6" ');
$hidden_field_dir_id = tep_draw_hidden_field('dir_id', $parent_id);
//目录说明
$textarea_field_dir_description = tep_draw_textarea_field('dir_description', 'virtual', '77', '3', '', ' style="font-size:12px; margin-top:10px;"');
//当前目录树 end

//权限设置框 start
  //只读
  $r_groups_ids = explode(',',$r_groups_id);
  $selected_vals = array();
  foreach($r_groups_ids as $key => $value){
	  $selected_vals[]['id'] = $value;
  }
  
  $r_groups_array = array();
  if(tep_not_null($r_groups_id)){
	$groups_query = tep_db_query("select admin_groups_id, admin_groups_name from " . TABLE_ADMIN_GROUPS. " where admin_groups_id in(".preg_replace('/,$/','',$r_groups_id).") ");
	while ($groups = tep_db_fetch_array($groups_query)) {
	  $r_groups_array[] = array('id' => $groups['admin_groups_id'],
							  'text' => $groups['admin_groups_name']);
	}
  }
  $mselect_menu_r_groups_ids = tep_draw_mselect_menu('r_groups_ids[]',$r_groups_array,$selected_vals,' id="r_groups_id" size="4" '.$noull_selcet_width);
  //读写
  $rw_groups_ids = explode(',',$rw_groups_id);
  $selected_vals = array();
  foreach($rw_groups_ids as $key => $value){
	  $selected_vals[]['id'] = $value;
  }
  
  $rw_groups_array = array();
  if(tep_not_null($rw_groups_id)){
	$groups_query = tep_db_query("select admin_groups_id, admin_groups_name from " . TABLE_ADMIN_GROUPS. " where admin_groups_id in(".preg_replace('/,$/','',$rw_groups_id).") ");
	while ($groups = tep_db_fetch_array($groups_query)) {
	  $rw_groups_array[] = array('id' => $groups['admin_groups_id'],
							  'text' => $groups['admin_groups_name']);
	}
  }
  $mselect_menu_rw_groups_ids = '<br>只允许以下用户组查看和编辑<br>'.tep_draw_mselect_menu('rw_groups_ids[]',$rw_groups_array,$selected_vals,' id="rw_groups_id" size="4" '.$noull_selcet_width);
  //全部权限
  $rwd_groups_ids = explode(',',$rwd_groups_id);
  $selected_vals = array();
  foreach($rwd_groups_ids as $key => $value){
	  $selected_vals[]['id'] = $value;
  }
  
  $rwd_groups_array = array();
  if(tep_not_null($rwd_groups_id)){
	$groups_query = tep_db_query("select admin_groups_id, admin_groups_name from " . TABLE_ADMIN_GROUPS. " where admin_groups_id in(".preg_replace('/,$/','',$rwd_groups_id).") ");
	while ($groups = tep_db_fetch_array($groups_query)) {
	  $rwd_groups_array[] = array('id' => $groups['admin_groups_id'],
							  'text' => $groups['admin_groups_name']);
	}
  }
  $mselect_menu_rwd_groups_ids = '<br>最高权限<br>'.tep_draw_mselect_menu('rwd_groups_ids[]',$rwd_groups_array,$selected_vals,' id="rwd_groups_id" size="4" '.$noull_selcet_width);

  //所有用户组
	$all_groups_query = tep_db_query("select admin_groups_id, admin_groups_name from " . TABLE_ADMIN_GROUPS);
	$all_groups_array = array();
	while ($groups = tep_db_fetch_array($all_groups_query)) {
	  $all_groups_array[] = array('id' => $groups['admin_groups_id'],
							  'text' => $groups['admin_groups_name']);
	}
	$mselect_menu_all_class_box = tep_draw_mselect_menu('all_class_box',$all_groups_array,array(),' id="all_class_box" size="14" '.$all_selcet_width);

//权限设置框 end

//子目录输入框 start
$add_class_name = '';
$input_field_add_class_name = tep_draw_input_field('add_class_name','','class="textAll"');
$hidden_field_add_parent_id = tep_draw_hidden_field('add_parent_id', $parent_id); 
	
//子目录输入框 end

$main_file_name = "zhh_system_dir_admin";
$JavaScriptSrc[] = 'includes/javascript/'.$main_file_name.'.js';
$CssArray[] = 'css/new_sys_index.css';
$CssArray[] = 'css/new_sys_indexDdan.css';

include_once(DIR_FS_DOCUMENT_ROOT.'smarty-2.0/libs/write_smarty_vars.php');
$smarty->display($main_file_name.'.tpl.html');
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>