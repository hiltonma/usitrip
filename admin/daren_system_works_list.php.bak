<?php
require('includes/application_top.php');
$error_msn = '';
$error = false;
$success_msn = '';
/* 逻辑判断 */
// 查询条件
$where = 'WHERE 1=1 ';


/* 关键字 */
if(tep_not_null($_GET['keyword'])){
	$keyword = tep_db_prepare_input($_GET['keyword']);
    $where .= ' and works_title LIKE Binary("%'.$keyword.'%")';
}
/* 发表时间 */
if(tep_not_null($_GET['added_start_date'])){
	$where .= ' and works_addtime >="'.$_GET['added_start_date'].' 00:00:00" ';
}
if(tep_not_null($_GET['added_end_date'])){
	$where .= ' and works_addtime <="'.$_GET['added_end_date'].' 23:59:59" ';
}
/* 作者姓名 */
if(tep_not_null($_GET['author'])){
	$where .= ' and works_author Like Binary("%'.$_GET['author'].'%")';
}
/* 审核显示 */
if(tep_not_null($_GET['is_view']) && ($_GET['is_view'] != '请选择')){
   
   $where .= ' and works_is_view = "'.$_GET['is_view'].'"';
   
}else{
    $where .= ' ';
}

/* 排序 */
$order_by = " ORDER BY works_id DESC";
/* is_ajax翻页 */
if ((int)$_GET['ajax'] == 1){
    $ajax = true;
}
$works_query_raw = 'SELECT * FROM  daren_works '.$where.' '.$order_by;
/**隐藏分页**/
$works_query_numrows = 0;
$MAX_DISPLAY_SEARCH_RESULTS_ADMIN = min(8,MAX_DISPLAY_SEARCH_RESULTS_ADMIN);
$works_split = new splitPageResults($_GET['page'], $MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $works_query_raw, $works_query_numrows);
$works_query = tep_db_query($works_query_raw);
//$works = tep_db_fetch_array($works_query);
/* 作品列表 */ 
$datas = array();
$n = 0;
while($works = tep_db_fetch_array($works_query)){
    $datas[$n]['works_id'] = $works['works_id'];
    $datas[$n]['works_title'] =db_to_html($works['works_title']);
    $works_frontcover_thumb = '..'.$works['works_frontcover_thumb'];
    if (file_exists($works_frontcover_thumb)){
        $datas[$n]['works_frontcover'] = '..'.$works['works_frontcover_thumb'];
    }else{
        $datas[$n]['works_frontcover'] = '../image/daren_uploadphoto.gif';
    }    
    
    $datas[$n]['works_url'] = db_to_html($works['works_url']);
    $datas[$n]['works_addtime'] = db_to_html($works['works_addtime']);
    $datas[$n]['works_readnum'] = db_to_html($works['works_readnum']);
    $datas[$n]['works_commnum'] = db_to_html($works['works_commnum']);
    $datas[$n]['works_votes'] = db_to_html($works['works_votes']);      
    $datas[$n]['works_content'] = db_to_html($works['works_content']);
    $datas[$n]['works_is_view'] = db_to_html($works['works_is_view']);
    $datas[$n]['works_author'] = db_to_html($works['works_author']);
    $datas[$n]['customers_id'] = $works['customers_id'];  
    $datas[$n]['works_edit_num'] = $works['works_edit_num'];  
    $datas[$n]['works_is_edit'] = $works['works_is_edit']; 
   //作者信息 
    
    
    $datas[$n]['account'] = tep_get_customers_info_fix($works['customers_id']);    
    
    // 国家地区 
    $js_onchange_func_name = 'module_ai_refresh'.$works['works_id'];
   
    $datas[$n]['full_address_input'] = tep_draw_full_address_input('defaultAddress',$datas[$n]['account']['entry_country_id'],$datas[$n]['account']['entry_state'],$datas[$n]['account']['entry_city'],false);
    
    $datas[$n]['full_address_input_js'] = '<script type="text/javascript">'.tep_draw_full_address_input_js($js_onchange_func_name).'</script>';
    
    
    /* 旅游达人相关信息 */
    $talent_info_sql = "SELECT * FROM `daren_user_info` WHERE customers_id = ".$works['customers_id'];
    $talent_info_query = tep_db_query($talent_info_sql);
    $datas[$n]['talent_info'] = tep_db_fetch_array($talent_info_query);
    
    
    
    $n++;
}

/* 使用Editor */
    /*
    include_once('./FCKeditor/fckeditor.php');
    $FCKeditor = new FCKeditor('FCKeditor1') ;
	$FCKeditor->BasePath = 'FCKeditor/';						
	$FCKeditor->Value = '';
	$FCKeditor->Height = 500;
	$editor = $FCKeditor->CreateHtml();
    
    $smarty->assign('editor',$editor);
    */
/* 
获取作品列表 
if (!$ajax){
    //$works_list_count = count($datas);
    $works_list_count = count($datas);
    for ($i=0; $i<$works_list_count; $i++){
        //获取作品列表 
        $work_lists_sql = "SELECT customers_id,works_title,works_addtime,works_votes,works_readnum FROM `daren_works` WHERE 1 AND customers_id = ".$datas[$i]['customers_id']." ORDER BY works_addtime DESC";  
        
        $work_lists_query = tep_db_query($work_lists_sql); 
        $j = 0;
        
        while($work_lists = tep_db_fetch_array($work_lists_query)){
            $datas[$i]['work_lists'][$j]['customers_id'] = $work_lists['customers_id'];
            $datas[$i]['work_lists'][$j]['works_title'] = iconv('utf-8','gb2312',$work_lists['works_title']);
            $datas[$i]['work_lists'][$j]['works_addtime'] = $work_lists['works_addtime'];
            $datas[$i]['work_lists'][$j]['works_votes'] = $work_lists['works_votes'];
            $datas[$i]['work_lists'][$j]['works_readnum'] = $work_lists['works_readnum'];      
            $j++;
        }
        
        // 作品列表分页 
        //$datas[$i]['pagework']['customers_id'] = $datas[$i]['customers_id'];
        //$datas[$i]['pagework']['split_list_left_content'.$work_lists['customers_id']] = $work_lists_split->display_count($work_lists_query_numrows, $MAX_DISPLAY_SEARCH_RESULTS_ADMIN_WORKLIST, $_GET['worklist'], TEXT_DISPLAY_NUMBER_OF_ORDERS);
        $split_list_left_content = $work_lists_split->display_count($work_lists_query_numrows, $MAX_DISPLAY_SEARCH_RESULTS_ADMIN_WORKLIST, $_GET['worklist'], TEXT_DISPLAY_NUMBER_OF_ORDERS);
        //$datas[$i]['pagework']['split_list_right_content'.$work_lists['customers_id']] = $works_split->display_links_style1($work_lists_query_numrows, $MAX_DISPLAY_SEARCH_RESULTS_ADMIN_WORKLIST, MAX_DISPLAY_PAGE_LINKS, $_GET['worklist'],tep_get_all_get_params(array('worklist','y','x', 'action')),'worklist'); 
        $split_list_right_content = $works_split->display_links_style1($work_lists_query_numrows, $MAX_DISPLAY_SEARCH_RESULTS_ADMIN_WORKLIST, MAX_DISPLAY_PAGE_LINKS, $_GET['worklist'],tep_get_all_get_params(array('worklist','y','x', 'action')),'worklist'); 
        
        
    }
  */
    /**
}else{
    echo 'ajax获取';
    
}
**/
/* 获取评论列表 */
$works_list_count = count($datas);
for ($i=0; $i<$works_list_count; $i++){
    $work_comment_lists_sql = "SELECT works_comment_id,works_id,works_comment_addtime,works_comment_author,works_comment_content,works_comment_ip,works_comment_is_view FROM `daren_works_comment` WHERE 1 AND  works_id=".$datas[$i]['works_id']." ORDER BY works_comment_addtime DESC";
    /*
    $work_comment_query_numrows = 0;
    $MAX_DISPLAY_SEARCH_RESULTS_ADMIN_COMMENTLIST = min(3,MAX_DISPLAY_SEARCH_RESULTS_ADMIN);
    $work_comment_split = new splitPageResults($_GET['comlist'], $MAX_DISPLAY_SEARCH_RESULTS_ADMIN_COMMENTLIST, $work_comment_lists_sql, $work_comment_query_numrows);
    */
    $work_comment_query = tep_db_query($work_comment_lists_sql);  
    $k = 0;
    
    while($work_comment_lists = tep_db_fetch_array($work_comment_query)){
        $datas[$i]['work_comment_lists'][$k]['works_comment_id'] = $work_comment_lists['works_comment_id'];
        $datas[$i]['work_comment_lists'][$k]['works_comment_addtime'] = $work_comment_lists['works_comment_addtime'];
        $datas[$i]['work_comment_lists'][$k]['works_comment_author'] = $work_comment_lists['works_comment_author'];
        $datas[$i]['work_comment_lists'][$k]['works_comment_content'] = strip_tags(str_replace('@MYBR@','<br />',$work_comment_lists['works_comment_content']),'<br>'); 
        $datas[$i]['work_comment_lists'][$k]['works_comment_ip'] = $work_comment_lists['works_comment_ip'];    
        $datas[$i]['work_comment_lists'][$k]['works_comment_is_view'] = $work_comment_lists['works_comment_is_view'];         
        $k++;
    }
    
    /* 作品评论分页 
    $split_comment_left_content = $work_comment_split->display_count($work_comment_query_numrows, $MAX_DISPLAY_SEARCH_RESULTS_ADMIN_COMMENTLIST, $_GET['comlist'], TEXT_DISPLAY_NUMBER_OF_ORDERS);
    $split_list_right_content = $work_comment_split->display_links_style1($work_comment_query_numrows, $MAX_DISPLAY_SEARCH_RESULTS_ADMIN_COMMENTLIST, MAX_DISPLAY_PAGE_LINKS, $_GET['comlist'],tep_get_all_get_params(array('comlist','y','x', 'action')),'comlist'); 
    */

}
//print_r($datas);

$smarty->assign('datas',$datas);



/* ajax删除评论 */
if ($_GET['action'] == 'deletecomment' && $_GET['ajax'] == 'true'){
    $comment_id = (int)$_GET['comment_id'];
    $work_id = (int)$_GET['work_id'];
    $t_id = $_GET['tbody_id'];
    
    if ($comment_id > 0){
        $sql = "DELETE FROM `daren_works_comment` WHERE `works_comment_id` = ".$comment_id." LIMIT 1";
        $res = tep_db_query($sql);
        // 更新评论数
        $sql = "UPDATE  `daren_works` SET  `works_commnum` =  works_commnum-1  WHERE  `works_id` = ".$work_id;
        tep_db_query($sql);
        //echo $res;
        
        $work_comment_lists_sql = "SELECT works_comment_id,works_id,works_comment_addtime,works_comment_author,works_comment_content,works_comment_ip FROM `daren_works_comment` WHERE 1 AND  works_id=".$work_id." ORDER BY works_comment_addtime DESC";
        $work_comment_query = tep_db_query($work_comment_lists_sql);  
        $k = 0;
        /**
         <tr id="{:$datas[i].work_comment_lists[commentes].works_comment_id:}">
                        <td>{:$datas[i].work_comment_lists[commentes].works_comment_content:}</td>
                        <td>{:$datas[i].work_comment_lists[commentes].works_comment_addtime:}</td>
                        <td>{:$datas[i].work_comment_lists[commentes].works_comment_author:}</td>
                        <td><a href="javascript:deletecomment({:$datas[i].work_comment_lists[commentes].works_comment_id:}, 'tbody2{:$smarty.section.i.index:}', {:$datas[i].works_id:});">删除</a></td>
                    </tr>
         */
        $tr = ''; 
        $tbody_id = 'tbody'.$t_id;
        while($work_comment_lists = tep_db_fetch_array($work_comment_query)){
            $tr .= '<tr id="'.$work_comment_lists['works_comment_id'].'">';
            $tr .= "<td>".$work_comment_lists['works_comment_content']."</td>";
            $tr .= "<td>".$work_comment_lists['works_comment_addtime']."</td>";
            $tr .= "<td>".$work_comment_lists['works_comment_author']."</td>";
            $tr .= "<td><a href=\"javascript:deletecomment(".$work_comment_lists['works_comment_id'].", '".$tbody_id."', ".$work_id.");\">删除</a></td>";
            
            $tr .= '"</tr>"';
            $k++;
        }
        $tbody1 = 'tbody1'.$t_id;
        $tbody2 = 'tbody2'.$t_id;
        $table1 = 'table1'.$t_id;
        $table2 = 'table2'.$t_id;
        $js = '<script type="text/javascript">
						$(function(){
								page1'.$t_id.'=new Page(5,\''.$table1.'\',\''.$tbody1.'\');
								page2'.$t_id.'=new Page(5,\''.$table2.'\',\''.$tbody2.'\');
						});		
               </script>';
                    
        echo $tr.$js;
    }
    exit;
}
/* 评论审核 */
if ($_GET['action'] == 'allowview' && $_GET['ajax'] == 'true'){
    $comment_id = (int)$_GET['comment_id'];
    $work_id = (int)$_GET['work_id'];
    $t_id = $_GET['tbody_id'];
    
    if ($comment_id > 0){
        $sql = "UPDATE  `daren_works_comment` SET `works_comment_is_view` = 1  WHERE `works_comment_id` = ".$comment_id." LIMIT 1";
        tep_db_query($sql);
    }
    exit;
}
/* 批量删除和审核通过评论 */
if (isset($_POST['action']) && $_POST['ajax'] == 'true'){  
        
    $works_comm_id = $_POST['works_comm_id'];
    $works_comment_id_array = explode(',',$works_comm_id);
    $works_comment_num = count($works_comment_id_array);
    
    if ($_POST['action'] == 'deleAllComment'){
        //批量删除评论
        // 更新评论数
        $work_id = (int)$_POST['work_id'];
        $sql = "UPDATE  `daren_works` SET  `works_commnum` =  works_commnum-".$works_comment_num."  WHERE  `works_id` = ".$work_id;
        tep_db_query($sql);
        for ($i=0; $i<$works_comment_num ;$i++){
            $sql = "DELETE FROM `daren_works_comment` WHERE `works_comment_id` = ".intval($works_comment_id_array[$i])." LIMIT 1";
            tep_db_query($sql);
        }
        exit;
    }elseif($_POST['action'] == 'allowAllComment'){
        // 批量通过审核
        for ($i=0; $i<$works_comment_num ;$i++){
            $sql = "UPDATE  `daren_works_comment` SET `works_comment_is_view` = 1  WHERE `works_comment_id` = ".$works_comment_id_array[$i]." LIMIT 1";
            tep_db_query($sql);
        }
        exit;
    }
}

/* 点击 ‘查看详细’ 时重置文章修改状态为 0 */
if (isset($_POST['action']) && $_POST['ajax'] == 'true'){
    $works_id = (int)$_POST['work_id'];
    if ($_POST['action'] == 'removestatusedit'){
        $sql = "UPDATE `daren_works` SET `works_is_edit` = '0' WHERE `works_id` =".$works_id;
        tep_db_query($sql);
    }
    exit;
}
/* 更新数据 */
/*
    [customers_firstname] => 王泗全
    [customers_mobile_phone] => 13718168940
    [country] => 223
    [state] => 
    [city] => 
    [entry_street_address] => 霞光里66号远洋新干线A座1501室
    [blog_url] => 
    [user_job] => 
    [rem_trip] => 
    [most_to] => 
    [works_title] => askdhkashd;ashd
    [works_url] => a
    [works_is_view] => 0
    [work_id] => 27
*/
if ($_POST['action'] == 'update'){ 
    
    $customers_id = (int)($_POST['customers_id']);   
    
    $customers_firstname = html_to_db(tep_db_prepare_input($_POST['customers_firstname']));    
    $customers_mobile_phone = html_to_db(tep_db_prepare_input($_POST['customers_mobile_phone']));    
    $country = (int)$_POST['country'];    
    $state = html_to_db(tep_db_prepare_input($_POST['state']));
    $city = html_to_db(tep_db_prepare_input($_POST['city']));
    $entry_street_address = html_to_db(tep_db_prepare_input($_POST['entry_street_address']));
    $blog_url = html_to_db(tep_db_prepare_input($_POST['blog_url']));
    $user_job = html_to_db(tep_db_prepare_input($_POST['user_job']));
    $rem_trip = html_to_db(tep_db_prepare_input($_POST['rem_trip']));
    $most_to = html_to_db(tep_db_prepare_input($_POST['most_to']));
    $works_title = html_to_db(tep_db_prepare_input($_POST['works_title']));
    $works_url = html_to_db(tep_db_prepare_input($_POST['works_url']));
    $works_is_view = (int)$_POST['works_is_view'];
    $work_id = (int)$_POST['work_id'];
    
    $works_frontcover = html_to_db(tep_db_prepare_input($_POST['works_frontcover']));  
    
    $works_content = html_to_db(tep_db_prepare_input($_POST['FCKeditor_'.$work_id.'']));
    
    $ar = array('"',"'",'“','”',"‘","’");
    //$works_content = str_replace($ar,'',$works_content);
    //$works_title = str_replace($ar,'',$works_title);
    if ($_POST['action'] == 'update'){
        /* 更新客人信息 */
        $customerssql = "UPDATE customers SET customers_firstname='".$customers_firstname."',customers_mobile_phone='".$customers_mobile_phone."' WHERE customers_id=".$customers_id;
        tep_db_query($customerssql);
        /* 更新国家地区等信息 
        $addresssql = "UPDATE address_book SET entry_country_id=".$country.",entry_state='".$state."',entry_city='".$city."',entry_street_address='".$entry_street_address."' WHERE customers_id=".$customers_id;        
        tep_db_query($addresssql);
        */
        /* 更新作品信息 */
        //echo $daren_workssql = "UPDATE daren_works SET works_title ='".$works_title."',works_url='".$works_url."',works_is_view=".$works_is_view." ,works_content='".$works_content."' WHERE works_id=".$work_id;
        //tep_db_query($daren_workssql);
        
        $data_array['works_title'] = $works_title;
        $data_array['works_url'] = $works_url;
        $data_array['works_is_view'] = $works_is_view;
        $data_array['works_content'] = $works_content;
        
        tep_db_perform('daren_works', $data_array, 'update', ' works_id='.$work_id);
        
        
        
        
        /* 更新客人的最想去的信息 */
        $daren_user_infosql = "UPDATE daren_user_info SET blog_url='".$blog_url."',user_job='".$user_job."', rem_trip='".$rem_trip."',most_to='".$most_to."' WHERE customers_id=".$customers_id;
        $res = tep_db_query($daren_user_infosql);     
        
        tep_redirect('daren_system_works_list.php?page='.$_POST['currpage']);
    }
}

/* 传递works_content给ifrem */
if ($_GET['action'] == 'getcontent'){
    $id = (int)$_GET['id'];       
    $work_comment_query = tep_db_query("SELECT works_content FROM daren_works WHERE  works_id=".$id);  
    $work_content = tep_db_fetch_array($work_comment_query);
    echo $work_content['works_content'];
    exit;
}


/*ajax修改封面*/
if($_GET['action'] == 'editfrontcover' && isset($_GET['works_id'])){
    $works_id = (int)$_GET['works_id'];
    $up_file_name = $_GET['fileToUpload_id'];
   
	//用户上传图片处理文件
    if ((($_FILES[$up_file_name]["type"] == "image/gif")|| ($_FILES[$up_file_name]["type"] == "image/jpeg")|| ($_FILES[$up_file_name]["type"] == "image/pjpeg"))&& ($_FILES[$up_file_name]["size"] < 100000)){
            //控制允许上传的图片类型,最后的100000为允许的图片大小
            if ($_FILES[$up_file_name]["error"] > 0){
                echo "Return Code: " . $_FILES[$up_file_name]["error"] . "<br />";            //出错返回
            }
            else
            {
                /*        //这是上传图片的信息,去掉前后的注释就可以看到效果.
                echo "Upload: " . $_FILES["file"]["name"] . "<br />";
                echo "Type: " . $_FILES["file"]["type"] . "<br />";
                echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
                   echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";  */
                if (file_exists("../images/talent/" . $_FILES[$up_file_name]["name"])){
                    echo $_FILES[$up_file_name]["name"] . " already exists. ";
                }else{
                    move_uploaded_file($_FILES[$up_file_name]["tmp_name"],"../images/talent/" . $_FILES[$up_file_name]["name"]);
                }
        
                $date=date('Ymdhis');                    //得到当前时间,如;20070705163148
                $fileName=$_FILES[$up_file_name]['name'];        //得到上传文件的名字
                $name=explode('.',$fileName);            //将文件名以'.'分割得到后缀名,得到一个数组
                $newPath=$date.'.'.$name[1];            //得到一个新的文件为'20070705163148.jpg',即新的路径
                $oldPath=$_FILES[$up_file_name]['tmp_name'];    //临时文件夹,即以前的路径
                rename("../images/talent/".$fileName,"../images/talent/".$newPath); 
                
                $newfile = '/images/talent/'.$newPath;
                /* 更新数据库*/
                $frontcover_query = tep_db_query("SELECT * FROM  `daren_works` WHERE works_id=".$works_id);
                $frontcover = tep_db_fetch_array($frontcover_query);
                @unlink('../images/talent/'.date(Ym).'/'.$frontcover['works_frontcover']);
                $sql = "UPDATE daren_works SET works_frontcover ='".$newPath."' WHERE works_id=".$works_id;
                tep_db_query($sql);
        
                echo $newfile;
            }
        //这里可以写你的SQL语句,图片的地址是 "userupload/".$newPath
    }
    
    exit;
        
}

/* 批量操作 */
if (isset($_POST['action']))
{
    
    if ($_POST['action'] == 'passworks'){        
        if (tep_not_null($_POST['works_id'])){
            $works_id = $_POST['works_id'];
            $works_id_array = explode(',',$works_id);           
            for ($i=0; $i<count($works_id_array);$i++){
                $sql = "UPDATE `daren_works` SET `works_is_view` = '1' WHERE `works_id` =".$works_id_array[$i];
                tep_db_query($sql);
            }
            echo '<script>alert("操作成功");location.reload();</script>';
        }
    }
    
    if ($_POST['action'] == 'delworks'){        
        if (tep_not_null($_POST['works_id'])){
            $works_id = $_POST['works_id'];
            $works_id_array = explode(',',$works_id);
            for ($i=0; $i<count($works_id_array);$i++){
                $sql = "UPDATE `daren_works` SET `works_is_view` = '3' WHERE `works_id` =".$works_id_array[$i];
                tep_db_query($sql);
            }
             echo '<script>alert("操作成功");location.reload();</script>';
        }
    }
   exit; 
}
/* 导出CSV数据 */
if (isset($_GET['action']) && $_GET['action'] == 'output_csv'){
    $works_query = tep_db_query('SELECT works_id,works_title,works_addtime,works_readnum,works_commnum,works_votes,works_author FROM `daren_works` WHERE works_is_view=1 ORDER BY works_votes DESC');
    $filename = '走四方网旅游体验师大赛_'.date('YmdHis').'.csv';//文件命名
    $data = "作品ID,作品标题,发布时间      ,点击量,评论数,得票数,作者"."\n";  //自定义字段名
    while($works = tep_db_fetch_array($works_query)){
        $data .= $works['works_id'].',="'.getSaveCsv(db_to_html($works['works_title'])).'",="'.db_to_html($works['works_addtime'])
                .'",'.db_to_html($works['works_readnum']).','.db_to_html($works['works_commnum']).','.db_to_html($works['works_votes']).','.getSaveCsv(db_to_html($works['works_author']))."\n";            
    }    
    header("Content-type: text/csv");
    header ("Content-Disposition: attachment; filename=" . $filename);
    header('Cache-Control:   must-revalidate,   post-check=0,   pre-check=0');
    header('Expires:   0');
    header('Pragma:   public');
    echo $data;
    exit;
    
    
}
function getSaveCsv($value){
    if (tep_not_null($value)){
        $data =  str_replace(',', '', $value);
        return  str_replace(array("\r","\n"),array("",""),$data);
    }else{
        return false;
    }
}

/* 分页区域 */
$split_left_content = $works_split->display_count($works_query_numrows, $MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS);
$split_right_content = $works_split->display_links($works_query_numrows, $MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $_GET['page'],tep_get_all_get_params(array('page','y','x', 'action'))); 

$smarty->assign('currpage',$_GET['page']);

/* 作品总数 */
$sql = "SELECT COUNT(*) AS works_rows, SUM(works_readnum) AS 	works_readnum , SUM(works_commnum) AS works_commnum FROM daren_works";
$query = tep_db_query($sql);
$query_rows = tep_db_fetch_array($query);
$smarty->assign('query_rows',$query_rows);
/* 相关js css等 */
$main_file_name = "daren_index";
$JavaScriptSrc[] = 'includes/jquery-1.3.2/jquery.nyroModal-1.6.2.js';
$JavaScriptSrc[] = 'includes/javascript/global.js';
$JavaScriptSrc[] = 'includes/javascript/calendar.js';
$CssArray = array(); //清空application_top中设置的CSS
$CssArray[] = 'includes/jquery-1.3.2/nyroModal.css';
$CssArray[] = 'css/daren.css';
$CssArray[] = 'css/global.css';
$CssArray[] = 'includes/javascript/spiffyCal/spiffyCal_v2_1.css';

include_once(DIR_WS_MODULES.'zhh_system_header.php');	//引入头文件
include_once(DIR_FS_DOCUMENT_ROOT.'smarty-2.0/libs/write_smarty_vars.php');

$smarty->display($main_file_name.'.tpl.html');

require(DIR_WS_INCLUDES . 'application_bottom.php');