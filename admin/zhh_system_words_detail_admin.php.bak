<?php
require('includes/application_top.php');
$error_msn = '';
$error = false;
$success_msn = '';
$noull_selcet_width = ' class="selectText" style=" min-width:200px; height:auto; " ';
$all_selcet_width = ' class="selectText" style=" color:#000; min-width:200px; height:205px; " ';


//删除文章 start
if($_GET['action']=="delete" && (int)$_GET['words_id']){
    //check Verification
    $check_sql = tep_db_query('SELECT * FROM `zhh_system_words` WHERE words_id="'.(int)$_GET['words_id'].'" LIMIT 1');
    $check = tep_db_fetch_array($check_sql);
    if(!(int)$check['words_id']){
        die("文章不存在！");
    }
    $groups = explode(',',$check['rwd_groups_id']);
    if(!in_array($login_groups_id, $groups)){
        die("你没有删除此文章的权限！");
    }
    tep_db_query('DELETE FROM `zhh_system_words` WHERE words_id="'.(int)$_GET['words_id'].'" and FIND_IN_SET( "' . $login_groups_id . '", rwd_groups_id) Limit 1 ');
    tep_db_query('DELETE FROM `zhh_words_to_dir` WHERE words_id="'.(int)$_GET['words_id'].'" ');
    $annex_sql = tep_db_query('SELECT * FROM `zhh_system_words_annex` WHERE words_id ="'.(int)$_GET['words_id'].'" ORDER BY annex_id  ' );
    while($annex_rows = tep_db_fetch_array($annex_sql)){
        $check_repeat_sql = tep_db_query('SELECT count(*) as total FROM `zhh_system_words_annex` WHERE annex_file_name ="'.$annex_rows['annex_file_name'].'" ');
        $check_repeat = tep_db_fetch_array($check_repeat_sql);
        if($check_repeat['total']<2){
            unlink(str_replace('//','/',DIR_FS_CATALOG.$annex_rows['annex_file_name']));
        }
    }
    tep_db_query('DELETE FROM `zhh_system_words_annex` WHERE words_id="'.(int)$_GET['words_id'].'" ');
    $success_msn = '文章删除成功！';
    echo general_to_ajax_string($success_msn);
    exit;
}
//删除文章 end

//修改或添加文章 start
if($_POST['submit_action']=="1"){
    
    $words_title = tep_db_prepare_input($_POST['words_title']);
    if(!tep_not_null($words_title)){
        $error = true;
        $error_msn = "标题不能为空！"."<br>";
    }
    $words_content = tep_db_prepare_input($_POST['words_content']);
    if(!tep_not_null($words_content)){
        if (check_html_code($words_content)){            
            $error = true;
            $error_msn .= "内容中有未闭合的html标签！"."<br>";            
        }
        $error = true;
        $error_msn .= "内容不能为空！"."<br>";
    }
    if ((int)$_POST['have_everyone']){
        if(!tep_not_null($_POST['re_groups_ids'])){
            $error = true;
            $error_msn .= "请选择要通知的用户组！"."<br>";
        }
    }
    $main_dir_id = (int)$_POST['main_dir_id'];
    if(!(int)$main_dir_id){
        $error = true;
        $error_msn .= "请至少选择一个类别！"."<br>";
    }   
    
    $last_admin_id = (int)$login_id;
    $updated_time = date("Y-m-d H:i:s");
    $date_array = array('words_title'=> $words_title,
                        'words_content'=> $words_content,
                        'last_admin_id'=> $last_admin_id,
                        'updated_time'=> $updated_time
                        );
    if($error==false){
      /*权限设置*/
      $r_groups_id = implode(',',(array)$_POST['r_groups_ids']);//只允许以下用户查看
      $rw_groups_id = implode(',',(array)$_POST['rw_groups_ids']); //只允许以下用户查看和编辑
      $rwd_groups_id = implode(',',(array)$_POST['rwd_groups_ids']); //最高权限
      // 如果权限未设置，即全部为空，则查找对应目录的权限，赋给当前文章
      if (!$r_groups_id && !$rw_groups_id && !$rwd_groups_id) {
      	$groups_dir_query = tep_db_query("select * from zhh_system_dir where dir_id='" . intval($main_dir_id) . "'");
      	$groups_dir_rs = tep_db_fetch_array($groups_dir_query);
      	if ($groups_dir_rs) {
      		$r_groups_id = $groups_dir_rs['r_groups_id'];
      		$rw_groups_id = $groups_dir_rs['rw_groups_id'];
      		$rwd_groups_id = $groups_dir_rs['rwd_groups_id'];
      	}
      }
      if(!in_array($login_groups_id,(array)$_POST['r_groups_ids'])){// 添加登录用户所在的组
          $r_groups_id = $login_groups_id.','.$r_groups_id;
      }
      if(!in_array('1',(array)$_POST['r_groups_ids'])){// 默认把超级管理员的权限加进来
          $r_groups_id = '1,'.$r_groups_id;
      }
      /*让所选的目录中的可读用户组都有对该文档的可读属性 start */
      if(is_array($_POST['dirs_ids']) && sizeof($_POST['dirs_ids'])){
          $dirs_sql = tep_db_query('SELECT r_groups_id,rw_groups_id,rwd_groups_id FROM `zhh_system_dir` WHERE dir_id in('.implode(',',$_POST['dirs_ids']).') ');
          $new_tmp_array1 = array();
          while($dirs = tep_db_fetch_array($dirs_sql)){
              $tmp_r_groups_id = explode(',', $dirs['r_groups_id']);
              $tmp_rw_groups_id = explode(',', $dirs['rw_groups_id']);
              $tmp_rwd_groups_id = explode(',', $dirs['rwd_groups_id']);
              
              $new_tmp_array  =array_merge($tmp_r_groups_id, $tmp_rw_groups_id, $tmp_rwd_groups_id);
              $new_tmp_array1[] =array_unique($new_tmp_array);
          }
          
          $new_tmp_array2 = array();
          for($i=0; $i<count($new_tmp_array1); $i++){
              $new_tmp_array2 = array_merge($new_tmp_array2, $new_tmp_array1[$i]);
          }
          $new_tmp_array2 = array_merge($new_tmp_array2, explode(',',$r_groups_id));
          $new_tmp_array2 = array_unique($new_tmp_array2);
          $r_groups_id = implode(',',$new_tmp_array2);
      }
      /*让所选的目录中的可读用户组都有对该文档的可读属性 end */
      
      // 以下是只允许以下用户查看和编辑
      /*if(!in_array($login_groups_id,(array)$_POST['rw_groups_ids'])){
          $rw_groups_id = $login_groups_id.','.$rw_groups_id;
      }*/
      if(!in_array('1',(array)$_POST['rw_groups_ids'])){
          $rw_groups_id = '1,'.$rw_groups_id;
      }
      
      // 以下是最高权限
      /*if(!in_array($login_groups_id,(array)$_POST['rwd_groups_ids'])){
          $rwd_groups_id = $login_groups_id.','.$rwd_groups_id;
      }*/
      if(!in_array('1',(array)$_POST['rwd_groups_ids'])){
          $rwd_groups_id = '1,'.$rwd_groups_id;
      }
      
      // 每日必读用户组
     
      $re_groups_id = implode(',', array_filter((array)$_POST['re_groups_ids']));
     
      $r_groups_id = $r_groups_id  .$re_groups_id;      
      $r_groups_id = preg_replace('/,$/','',$r_groups_id);
      $rw_groups_id = preg_replace('/,$/','',$rw_groups_id);
      $rwd_groups_id = preg_replace('/,$/','',$rwd_groups_id);
      $re_groups_id = preg_replace('/,$/','',$re_groups_id);
    
      
      $date_array['r_groups_id'] = $r_groups_id;      
      $date_array['rw_groups_id'] = $rw_groups_id;
      $date_array['rwd_groups_id'] = $rwd_groups_id;
      $date_array['r_everyone_group_ids'] = $re_groups_id;
      
      $date_array['is_adjective'] = (int)$_POST['is_adjective'];
      if (tep_not_null($_POST['provider'])){
        $date_array['provider'] =  tep_db_prepare_input($_POST['provider']);
      }
      if((int)$_POST['words_id']){	//更新
          tep_db_perform('zhh_system_words', $date_array, 'update', ' words_id="'.(int)$_POST['words_id'].'" ');
          $words_id = (int)$_POST['words_id'];
          $success_msn = "数据更新成功！";
      }else{	//增加
          $date_array['admin_id'] = (int)$login_id;
          $date_array['added_time'] = $updated_time;
          tep_db_perform('zhh_system_words', $date_array);
          $words_id = tep_db_insert_id();
          $success_msn = "数据添加成功！";
      }
      
      // 如果是添加 “每日必读” 栏目则添加提醒数据
      if((int)$_POST['have_everyone']){        
        // 取得需要提醒的用户组        
        $re_groups_array = array_unique(explode(',', $re_groups_id));      
        foreach($re_groups_array as $key=>$v){
            //echo "SELECT admin_id, admin_email_address FROM ". TABLE_ADMIN ." WHERE admin_groups_id=".$v;
            $admin_users_query = tep_db_query("SELECT admin_id, admin_email_address FROM ". TABLE_ADMIN ." WHERE admin_groups_id=".$v);
            while($admin_users = tep_db_fetch_array($admin_users_query)){                
                $re_groups_arrays[] =  $admin_users['admin_id'];                
            }           
        }
        // 文章所有者
        (int)$_POST['words_id'];
        $dsql = tep_db_query('select admin_id from zhh_system_words where words_id='.(int)$_POST['words_id']);
        $admin_id = tep_db_fetch_array($dsql);
        $k = 0;
        foreach($re_groups_arrays as $key=>$value){ 
            if ($login_id != $value){
                $remind_query = tep_db_query("SELECT * FROM ".TABLE_EVERYONE_TO_READ_REMIND." WHERE words_id=". $words_id ." AND admin_id=".$value);
                $remind_query_num = tep_db_num_rows($remind_query);
                if ($remind_query_num == 0){
                    if ($admin_id['admin_id'] != $value){
                        $data_array['admin_id'] = $value;
                        $data_array['words_id'] = $words_id;
                        $data_array['is_read'] = '0';
                        $data_array['login_num'] = '0';
                        $data_array['added_time'] = 'now()';
                        $data_array['expiration_time'] = '';
                        tep_db_perform(TABLE_EVERYONE_TO_READ_REMIND, $data_array);
                    }
                }
            }
            $k++;
        }
        
      }	  
      //设置文章目录
      $dirs_ids = (array)$_POST['dirs_ids'];
      tep_db_query('DELETE FROM zhh_words_to_dir WHERE words_id = "'.$words_id.'" ');
      foreach($dirs_ids as $val){
          if($val!=$main_dir_id && (int)$val){
            tep_db_query('INSERT INTO `zhh_words_to_dir` (`dir_id`,`words_id`) VALUES ("'.$val.'", "'.$words_id.'");');
          }
      }
      //设置文章主目录
      if((int)$main_dir_id){
        tep_db_query('INSERT INTO `zhh_words_to_dir` (`dir_id`,`words_id`,`is_main`) VALUES ("'.$main_dir_id.'", "'.$words_id.'", "1");');
      }
      
      //上传文章附件 start
      $annex_sql = tep_db_query('SELECT * FROM `zhh_system_words_annex` WHERE words_id ="'.$words_id.'" ORDER BY annex_id  ' );
      while($annex_rows = tep_db_fetch_array($annex_sql)){
          if(!in_array($annex_rows['annex_file_name'], (array)$_POST['annex_files_name']) ){
              unlink(str_replace('//','/',DIR_FS_CATALOG.$annex_rows['annex_file_name']));
          }
      }
      tep_db_query("DELETE FROM `zhh_system_words_annex` WHERE `words_id` = '{$words_id}' ");
      for($i=0; $i<sizeof($_POST['annex_files_name']); $i++){
          if(tep_not_null($_POST['annex_files_name'][$i])){
            $date_array = array('words_id' => $words_id, 'annex_file_name'=>tep_db_prepare_input($_POST['annex_files_name'][$i]));
            tep_db_perform('zhh_system_words_annex', $date_array);
          }
      }
      if($_FILES['files']){
          for($i=0; $i<sizeof($_FILES['files']['name']); $i++){
              if($_FILES['files']['error'][$i]=="0"){
                  $file_name = 'UserFiles/'.tep_db_prepare_input(preg_replace('/[[:space:]]+/','_', string2ascii($_FILES['files']['name'][$i],'_') . preg_replace('/^.*\./','.',$_FILES['files']['name'][$i])));	//格式化文件名
                  //$file_name = 'UserFiles/'.tep_db_prepare_input(preg_replace('/[[:space:]]+/','_', $_FILES['files']['name'][$i] ));
                  //修改敏感文件扩展名
                  $p = array('/\.php$/i','/\.js$/i','/\.exe$/i','/\.dll$/i','/\.bat$/i');
                  $r = array('.p_h_p','.j_s','.e_x_e','.d_l_l','.b_a_t');
                  $file_name = preg_replace($p, $r, $file_name);
                  $file_url = '/'.$file_name;
                  if(!move_uploaded_file($_FILES['files']['tmp_name'][$i], DIR_FS_CATALOG.$file_name)){
                      echo "上传失败！";
                  }else{
                      $date_array = array('words_id' => $words_id, 'annex_file_name'=>$file_url);
                      tep_db_perform('zhh_system_words_annex', $date_array);
                  }
              }
          }
      }
      //上传文章附件 end
    }
}
//修改或添加文章 end


//默认值设置
if((int)$_GET['dir_id']){
    $dir_id = ((int)$_GET['dir_id']);
}
$upload_max_filesize = ini_get('upload_max_filesize');
$pageHeading = "添加文章";
$WordsHeading = "基本内容";

$words_id = (int)$words_id ? (int)$words_id : (int)$_GET['words_id'];

if((int)$words_id){
    //文章内容
    $words_sql = tep_db_query('SELECT * FROM `zhh_system_words` WHERE words_id="'.$words_id.'" and (FIND_IN_SET( "' . $login_groups_id . '", rw_groups_id) || FIND_IN_SET( "' . $login_groups_id . '", rwd_groups_id) || admin_id ="'.(int)$login_id.'" || last_admin_id ="'.(int)$login_id.'" ) Limit 1 ');
    $words = tep_db_fetch_array($words_sql);
    if(!(int)$words['words_id']){
        die("您无权修改此文章，或此文章不存在！");
    }
    $WordsInf = new objectInfo($words);
    foreach((array)$WordsInf as $key => $val){
        $$key = $val;       
    }
    //文章所在目录
    $dir_sql = tep_db_query('SELECT dir_id FROM `zhh_words_to_dir` WHERE words_id="'.$words_id.'" and is_main !="1" ' );
    $dirs_id = '';
    while($dir_rows = tep_db_fetch_array($dir_sql)){
        $dirs_id .=",".$dir_rows['dir_id'];
    }
    $dirs_id = substr($dirs_id,1);
    
    //文章附件
    $annex_sql = tep_db_query('SELECT * FROM `zhh_system_words_annex` WHERE words_id ="'.$words_id.'" ORDER BY annex_id  ' );
    $annexs = array();
    while($annex_rows = tep_db_fetch_array($annex_sql)){
        $annexs[] = array('id'=>$annex_rows['annex_id'], 'file_name'=>$annex_rows['annex_file_name']);
    }
    
    $pageHeading = "更新文章";
}

//基本内容fieldset内的变量设置 start
  if(tep_not_null($success_msn)){	//成功信息
      $show_msn = '<div style="color:#0C0">'.$success_msn.'</div>';
  }
  if(tep_not_null($error_msn)){	//错误提示信息
      $show_msn = '<div style="color:#F00">'.$error_msn.'</div>';
  }
  $sent_name = tep_get_admin_customer_name($admin_id).'('.$admin_id.')';
  $last_up_per_name = tep_get_admin_customer_name($last_admin_id).'('.$last_admin_id.')';
  
  $input_field_words_title = tep_draw_input_field('words_title','',' size="70" ');
  $textarea_field_words_content = tep_draw_textarea_field('words_content', 'virtual', '70', '20', '', 'style="display:none"');
  $radio_button_is_adjective = tep_draw_radio_field('is_adjective',1).'紧急';
  $radio_button_no_adjective = tep_draw_radio_field('is_adjective',0).'非紧急';
  $input_field_provider = tep_draw_input_field('provider');
//基本内容fieldset内的变量设置 end

//所属目录fieldset变量设置 start
  //"所有目录"框
  $perssion_dirs_array = tep_get_class_tree('0','', '', '',false, true, 'rw');
  
  $dirs_ids = array();
  if(tep_not_null($dirs_id)){
    $dirs_ids = explode(',',$dirs_id);
  }
  $selected_vals = array();
  /*/在当前目录$dir_id下时，先判断用户是否有权限写入到该目录 start
  if(!tep_not_null($dirs_ids) && (int)$dir_id){
      foreach((array)$perssion_dirs_array as $key => $value){
        if($value['id']==$dir_id){
            $dirs_id = $dir_id;
            break;
        }
      }
  }
  //在当前目录$dir_id下时，先判断用户是否有权限写入到该目录 end
  已经注释，有了主目录之后就不用这段代码了*/
  
  foreach($dirs_ids as $key => $value){
      $selected_vals[]['id'] = $value;
  }
  $now_dirs_array = array();
  if(tep_not_null($dirs_id)){
    $groups_query = tep_db_query("select dir_id, dir_name from zhh_system_dir where dir_id in(".preg_replace('/,$/','',$dirs_id).") ");
    while ($groups = tep_db_fetch_array($groups_query)) {
      $now_dirs_array[] = array('id' => $groups['dir_id'],
                              'text' => $groups['dir_name']);
    }
  } 
  $mselect_menu_dirs_ids = tep_draw_mselect_menu('dirs_ids[]',$now_dirs_array,$selected_vals,' id="dirs_id" '.str_replace('height:auto;','height:205px;',$noull_selcet_width));
  //取得当前文章的主目录 start
  $main_dir_id = "0";
  if((int)$words_id){
    $main_dir_query = tep_db_query("select wtd.dir_id from zhh_words_to_dir wtd, zhh_system_dir sd where wtd.words_id='".(int)$words_id."' and wtd.is_main ='1' and wtd.dir_id = sd.dir_id Limit 1");
    $main_dir = tep_db_fetch_array($main_dir_query);
    if((int)$main_dir['dir_id']){
        $main_dir_id = $main_dir['dir_id'];
    }
  }else{
    /*$first_dirs_string ="";
    $FirstDirsIds = "FirstDirsIds";
    $perssion_top_dirs = tep_get_class_tree('0','', '', '',false, false, 'rw');
    $options = array();
    $options[] = array('id'=>0, 'text'=>"请选择");
    for($i=0; $i<count($perssion_top_dirs); $i++){
        $options[] = array('id'=> $perssion_top_dirs[$i]['id'], 'text'=>$perssion_top_dirs[$i]['text']);
    }
    $first_dirs_string .= tep_draw_pull_down_menu($FirstDirsIds,$options,$default_id,' onchange="select_onchange(this)"')."\n";
    */
  }
  
  $confrim_id = (int)$main_dir_id ? $main_dir_id:$dir_id;
  $main_to_top_ids = tep_get_top_to_now_class($confrim_id);
  $first_dirs_string = "";
  $FirstDirsIds = "FirstDirsIds";
  for($i=max(0,(count($main_to_top_ids)-1)); $i>=0; $i--){
      $options = array();
      $dir_list_query = tep_db_query("select dir_id, parent_id, dir_name from zhh_system_dir where parent_id ='".$main_to_top_ids[$i]['id']."' and (FIND_IN_SET( '" . $login_groups_id . "', rw_groups_id) || FIND_IN_SET( '" . $login_groups_id . "', rwd_groups_id)) ORDER BY sort_num , dir_id");
      $dir_list = tep_db_fetch_array($dir_list_query);
      if((int)$dir_list['dir_id']){
          $default_id = "";
          if(empty($main_dir_id)){
            $options = array();
            $options[] = array('id'=>0, 'text'=>"请选择");
          }
          do{
              $options[] = array('id'=> $dir_list['dir_id'], 'text'=>$dir_list['dir_name']);
              if($dir_list['dir_id']==$main_dir_id){
                  $default_id = $dir_list['dir_id'];
              }elseif($main_to_top_ids[($i-1)]['id'] == $dir_list['dir_id']){
                  $default_id = $dir_list['dir_id'];
              }
          }while($dir_list = tep_db_fetch_array($dir_list_query));
          $first_dirs_string .= tep_draw_pull_down_menu($FirstDirsIds,$options,$default_id,' onchange="select_onchange(this)"')."\n";
          $FirstDirsIds.=$FirstDirsIds;
      }
  }
  if(!(int)$main_dir_id){ $main_dir_id = (int)$dir_id; }
  $first_dirs_string = '<div id="first_dirs_string">'.$first_dirs_string."</div>".tep_draw_hidden_field('main_dir_id',$main_dir_id,' id="main_dir_id" ');
  
  //取得当前文章的主目录 end
  
  // 取得当前目录的主目录是否是“每日必读” start by panda
  
  $top_to_current_dirs_arr = tep_get_top_to_now_class($main_dir_id);  
  $current_str = '';
  foreach($top_to_current_dirs_arr as $key=>$value){
    $current_str .= $value['text'].','; 	
  }
  
  if (preg_match('/每日必读/',  $current_str)){
    $have_everyone = true;
  }else{
    $have_everyone = false;
  }
  
  // 取得当前目录的主目录是否是“每日必读” end by panda
  
  //"所有目录"框 end
  $mselect_menu_all_dir_box = tep_draw_mselect_menu('all_dir_box',$perssion_dirs_array,array(),' id="all_dir_box" '.$all_selcet_width);
  
//所属目录fieldset变量设置 end

//权限设置 fieldset start
  //只读
 
  $r_groups_ids = array_filter(explode(',',$r_groups_id));
  
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
  // 需要每日必读用户组  
  $re_groups_ids = explode(',',$r_everyone_group_ids);
  
  $selected_vals = array();
  foreach($re_groups_ids as $key => $value){
      $selected_vals[]['id'] = $value;
  } 
  $re_groups_array = array();
  if(tep_not_null($r_everyone_group_ids)){
    $groups_query = tep_db_query("select admin_groups_id, admin_groups_name from " . TABLE_ADMIN_GROUPS. " where admin_groups_id in(".preg_replace('/,$/','',$r_everyone_group_ids).") ");
    while ($groups = tep_db_fetch_array($groups_query)) {
      $re_groups_array[] = array('id' => $groups['admin_groups_id'],
                              'text' => $groups['admin_groups_name']);
    }
  }
  $mselect_menu_erveryone_groups_ids = '<br>每日必读用户组<span style="color:#FF0000">(*)</span><br>'.tep_draw_mselect_menu('re_groups_ids[]',$re_groups_array,$selected_vals,' id="re_groups_id" size="4" '.$noull_selcet_width);
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
  $mselect_menu_all_class_box = tep_draw_mselect_menu('all_class_box',$all_groups_array,array(),' id="all_class_box" '.$all_selcet_width);
 

//权限设置 fieldset end

//文章附件 fieldset start
  $annexs_div_string = "";
  for($i=0; $i<sizeof($annexs); $i++){
      $file_name = $annexs[$i]['file_name'];
      $file_name_base = explode('.', basename($file_name));	//由于加密码的字符串中已经包括扩展名，所以这里不用再读它
      $annexs_div_string.= '<div>'.tep_draw_hidden_field('annex_files_name[]',$file_name).'<a class="a_black" href="'.tep_href_link('zhh_system_words_download.php','download=1&annex_id='.$annexs[$i]['id']).'" target="_blank">'.tep_db_output(ascii2string($file_name_base[0],'_')).'</a> <a onclick="div_remove(this);" href="JavaScript:void(0)"><img src="images/icons/u20.png" border="0" /></a></div>';
  }

//文章附件 fieldset end

$title=$pageHeading;
$main_file_name = "zhh_system_words_detail_admin";
$JavaScriptSrc[] = 'includes/javascript/'.$main_file_name.'.js';
$CssArray[] = 'css/new_sys_index.css';
$CssArray[] = 'css/new_sys_indexDdan.css';

$breadcrumb->add($words_title, tep_href_link('zhh_system_words_detail_admin.php','words_id='.$words_id));

include_once(DIR_WS_MODULES.'zhh_system_header.php');	//引入头文件
include_once(DIR_FS_DOCUMENT_ROOT.'smarty-2.0/libs/write_smarty_vars.php');

$smarty->display($main_file_name.'.tpl.html');

require(DIR_WS_INCLUDES . 'application_bottom.php');
?>