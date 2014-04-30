<?php
require('includes/application_top.php');
// 备注添加删除
if($_GET['ajax']=="true"){
	include DIR_FS_CLASSES . 'Remark.class.php';
	$remark = new Remark('manage_individual_space');
	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
}
//$where = 'WHERE c.customers_id = p.customers_id AND p.customers_id =pb.customers_id';
$where =' WHERE customers_id >=0 ';
$order_by =' ORDER BY `photo_update` DESC';
$sql= 'SELECT * FROM `photo`';
$group_by = '';
$sql_onload = $sql.$where.$order_by.$group_by;
//echo $sql_onload;
$companion_query_numrows = 0;
//時間排序
$sort_time = '';
if($_GET['search']=='1'&&(tep_not_null($_GET['search_start_date'])||tep_not_null($_GET['search_end_date']))){
        switch ((int)$_GET['individual_space']){
            case '0':if(tep_not_null($_GET['search_start_date'])){
                        $sort_time .=' AND photo_update >= "'.$_GET['search_start_date'].' 00:00:00" ';
	             }
  	            if(tep_not_null($_GET['search_end_date'])){
                        $sort_time .=' AND photo_update <= "'.$_GET['search_end_date'].' 23:59:59" ';
	            }
                    break;
            case '1':if(tep_not_null($_GET['search_start_date'])){
                        $sort_time .=' AND photo_books_date >= "'.$_GET['search_start_date'].' 00:00:00" ';
	             }
  	            if(tep_not_null($_GET['search_end_date'])){
                        $sort_time .=' AND photo_books_date <= "'.$_GET['search_end_date'].' 23:59:59" ';
	            }
                    break;
            case '2':if(tep_not_null($_GET['search_start_date'])){
                        $sort_time .=' AND added_time >= "'.$_GET['search_start_date'].' 00:00:00" ';
	             }
  	            if(tep_not_null($_GET['search_end_date'])){
                        $sort_time .=' AND added_time <= "'.$_GET['search_end_date'].' 23:59:59" ';
	            }
                    break;
            case '3':if(tep_not_null($_GET['search_start_date'])){
                        $sort_time .=' AND added_time >= "'.$_GET['search_start_date'].' 00:00:00" ';
	             }
  	            if(tep_not_null($_GET['search_end_date'])){
                        $sort_time .=' AND added_time <= "'.$_GET['search_end_date'].' 23:59:59" ';
	            }
                    break;
            case '4':if(tep_not_null($_GET['search_start_date'])){
                        $sort_time .=' AND added_time >= "'.$_GET['search_start_date'].' 00:00:00" ';
	             }
  	            if(tep_not_null($_GET['search_end_date'])){
                        $sort_time .=' AND added_time <= "'.$_GET['search_end_date'].' 23:59:59" ';
	            }
                    break;
           case '6':if(tep_not_null($_GET['search_start_date'])){
                       $sort_time .=' AND add_date >= "'.$_GET['search_start_date'].' 00:00:00" ';
                    }
                    if(tep_not_null($_GET['search_end_date'])){
                       $sort_time .=' AND add_date <= "'.$_GET['search_end_date'].' 23:59:59" ';
                    }
                    break;

         }
}


//排序
$order_by ='';
if($_GET['sort']=='photo_date_up'){ $order_by = ' order by photo_update asc ';}
if($_GET['sort']=='photo_date_down'){ $order_by = ' order by photo_update desc ';}
if($_GET['sort']=='photo_book_date_up'){ $order_by = ' order by photo_books_date asc ';}
if($_GET['sort']=='photo_book_date_down'){ $order_by = ' order by photo_books_date desc ';}
if($_GET['sort']=='travel_notes_date_up'){ $order_by = ' order by added_time asc ';}
if($_GET['sort']=='travel_notes_date_down'){ $order_by = ' order by added_time desc ';}
if($_GET['sort']=='travel_notes_comments_date_up'){ $order_by = ' order by added_time asc ';}
if($_GET['sort']=='travel_notes_comments_date_down'){ $order_by = ' order by added_time desc ';}
if($_GET['sort']=='photo_comments_date_up'){ $order_by = ' order by added_time asc ';}
if($_GET['sort']=='photo_comments_date_down'){ $order_by = ' order by added_time desc ';}
if($_GET['sort']=='individual_space_date_up'){ $order_by = ' order by photo_update asc ';}
if($_GET['sort']=='individual_space_date_down'){ $order_by = ' order by photo_update desc ';}
if($_GET['sort']=='site_inner_sms_down'){ $order_by = ' order by add_date desc ';}
if($_GET['sort']=='site_inner_sms_up'){ $order_by = ' order by add_date asc ';}

if($_GET['search']=='1'){
    //选择性搜索开始
        if($_GET['individual_space']!='0'){
                    if((int)$_GET['individual_space']>'0'){
                             switch ((int)$_GET['individual_space']){
                                 case '1':if(!tep_not_null($_GET['s_search_text_photo'])){
                                               if($_GET['sort']=='photo_book_date_up'||$_GET['sort']=='photo_book_date_down'){
                                                  $sql = 'SELECT * FROM `photo_books`';
                                                  $sql_onload = $sql.$where.$sort_time.$order_by.$group_by;
                                               }else{
                                                 $sql = 'SELECT * FROM `photo_books`';
                                                 $sql_onload = $sql.$where.$sort_time.' ORDER BY `photo_books_date` DESC '.$group_by;
                                               }
                                          }else{
                                              if($_GET['sort']=='photo_book_date_up'||$_GET['sort']=='photo_book_date_down'){
                                                 $sql ='SELECT * FROM `photo_books`';
                                                 $where.= ' AND photo_books_name LIKE "%'.tep_db_input($_GET['s_search_text_photo']).'%"';
                                                 $sql_onload = $sql.$where.$sort_time.' ORDER BY `photo_books_date` DESC'.$group_by;
                                              }else{
                                                 $sql ='SELECT * FROM `photo_books`';
                                                 $where.= ' AND photo_books_name LIKE "%'.tep_db_input($_GET['s_search_text_photo']).'%"';
                                                 $sql_onload = $sql.$where.$sort_time.$order_by.$group_by;
                                                 //$sql_onload='SELECT * FROM `photo_books` WHERE photo_books_name LIKE "%'.tep_db_input($_GET['s_search_text_photo']).'%" ORDER BY `photo_books_date` DESC';
                                              }
                                          }
                                          
                                          $companion_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $sql_onload, $companion_query_numrows);
                                          $sql_onload_query = tep_db_query($sql_onload);
                                          break;
                                 case '2':if(!tep_not_null($_GET['s_search_text_notos'])){
                                             if($_GET['sort']=='travel_notes_date_up'||$_GET['sort']=='travel_notes_date_down'){
                                                $sql = 'SELECT * FROM `travel_notes`';
                                                $sql_onload = $sql.$where.$sort_time.$order_by.$group_by;
                                             }else{
                                                 $sql = 'SELECT * FROM `travel_notes`';
                                                 $sql_onload = $sql.$where.$sort_time.' ORDER BY `added_time` DESC '.$group_by;
                                             }
                                          }else{
                                              if($_GET['sort']=='travel_notes_date_up'||$_GET['sort']=='travel_notes_date_down'){
                                                   $sql = 'SELECT * FROM `travel_notes`';
                                                   $where.=' AND travel_notes_title  LIKE "%'.tep_db_input($_GET['s_search_text_notos']).'%"';
                                                   $sql_onload = $sql.$where.$sort_time.$order_by.$group_by;
                                              }else{
                                                 $sql = 'SELECT * FROM `travel_notes`';
                                                 $where.=' AND travel_notes_title  LIKE "%'.tep_db_input($_GET['s_search_text_notos']).'%"';
                                                 $sql_onload = $sql.$where.$sort_time.$order_by.$group_by;
                                              }
                                          }
                                          //echo $sql_onload;
                                          $companion_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $sql_onload, $companion_query_numrows);
                                          $sql_onload_query = tep_db_query($sql_onload);
                                 break;
                                 case '3': if(!tep_not_null($_GET['s_search_text_id'])){
                                               if($_GET['sort']=='photo_comments_date_up'||$_GET['sort']=='photo_comments_date_down'){
                                                   $sql = 'SELECT * FROM `photo_comments`';
                                                   $where.=' AND has_remove != "1" ';
                                                   $sql_onload = $sql.$where.$sort_time.$order_by.$group_by;
                                               }else{
                                                 $sql  = 'SELECT * FROM `photo_comments` ';
                                                 $where.=' AND has_remove != "1" ';
                                                 $sql_onload = $sql.$where.$sort_time.' ORDER BY `added_time` DESC '.$group_by;
                                               }
                                           }else{
                                               if($_GET['sort']=='photo_commentss_date_up'||$_GET['sort']=='photo_comments_date_down'){
                                                  $sql = 'SELECT * FROM `photo_comments` ';
                                                  $where.=' AND customers_id ="'.(int)$_GET['s_search_text_id'].'" AND has_remove != "1"';
                                                  $sql_onload = $sql.$where.$order_by.$group_by;
                                               }else{
                                                    $sql = 'SELECT * FROM `photo_comments` ';
                                                    $where.=' AND customers_id ="'.(int)$_GET['s_search_text_id'].'" AND has_remove != "1"';
                                                    $sql_onload = $sql.$where.$sort_time.' ORDER BY `added_time` DESC '.$group_by;
                                               }
                                           }
                                           //echo $sql_onload;
                                           $companion_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $sql_onload, $companion_query_numrows);
                                           $sql_onload_query = tep_db_query($sql_onload);
                                 break;
                                 case '4': if(!tep_not_null($_GET['s_search_text_id'])){
                                              if($_GET['sort']=='travel_notes_comments_date_up'||$_GET['sort']=='travel_notes_comments_date_down'){
                                                 $sql = 'SELECT * FROM `travel_notes_comments`';
                                                 $where.=' AND has_remove != "1" ';
                                                 $sql_onload = $sql.$where.$sort_time.$order_by.$group_by;
                                              }else{
                                                 $sql = 'SELECT * FROM `travel_notes_comments`';
                                                 $where.=' AND has_remove != "1" ';
                                                 $sql_onload = $sql.$where.$sort_time.' ORDER BY `added_time` DESC '.$group_by;
                                                 //$sql_onload = 'SELECT * FROM `travel_notes_comments` WHERE has_remove != "1" ORDER BY `added_time` DESC ';
                                              }
                                           }else{
                                              if($_GET['sort']=='travel_notes_comments_date_up'||$_GET['sort']=='travel_notes_comments_date_down'){
                                                 $sql = 'SELECT * FROM `travel_notes_comments`';
                                                 $where.=' AND customers_id ="'.(int)$_GET['s_search_text_id'].'" AND has_remove != "1"';
                                                 $sql_onload = $sql.$where.$order_by.$group_by;
                                              }else{
                                                 $sql = 'SELECT * FROM `travel_notes_comments`';
                                                 $where.=' AND customers_id ="'.(int)$_GET['s_search_text_id'].'" AND has_remove != "1"';
                                                 $sql_onload = $sql.$where.$sort_time.' ORDER BY `added_time` DESC '.$group_by;
                                              }
                                           }
                                           //echo $sql_onload;
                                           $companion_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $sql_onload, $companion_query_numrows);
                                           $sql_onload_query = tep_db_query($sql_onload);
                                 break;
                                 case '5':if(!tep_not_null($_GET['s_search_text_id'])){
                                             /*$where =' WHERE c.customers_id =  p.customers_id OR c.customers_id = pb.customers_id OR c.customers_id =  tn.customers_id';
                                             $sql= 'SELECT c.customers_id,c.customers_firstname FROM customers AS c, photo AS p, photo_books AS pb,travel_notes  AS tn';
                                             $group_by = ' GROUP BY c.customers_id';
                                             $sql_onload = $sql.$where.$group_by;*/
                                             $sql_onload = 'SELECT * FROM `customers` ORDER BY `customers_id` DESC';
                                             $companion_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $sql_onload, $companion_query_numrows);
                                             $sql_onload_query = tep_db_query($sql_onload);
                                              $customers_detail='';
                                                $array_invidual='';
                                                $i=0;
                                                while($rows = tep_db_fetch_array($sql_onload_query)){
                                                      $customers_detail[$i]['customers_id']=$rows['customers_id'];
                                                      $customers_detail[$i]['customers_firstname']=$rows['customers_firstname'];
                                                      //echo  $customers_detail[$i]['customers_id'];
                                                      //取得相册总数
                                                      $sql_photo_num = 'SELECT count(photo_books_id) as total FROM  `photo_books` where customers_id ="'.$customers_detail[$i]['customers_id'].'"';
                                                     //echo $sql_photo_num;
                                                      $sql_photo_num_query = tep_db_query($sql_photo_num);
                                                      $sql_photo_num_res = tep_db_fetch_array($sql_photo_num_query);
                                                      $photo_num= $sql_photo_num_res['total'];
                                                    //取得游记总数
                                                      $sql_notos_num ='SELECT count(*) as total FROM `travel_notes` WHERE customers_id="'.$customers_detail[$i]['customers_id'].'"';
                                                      $sql_notos_num_query = tep_db_query($sql_notos_num);
                                                      $sql_notos_num_res = tep_db_fetch_array($sql_notos_num_query);
                                                      $notos_num = $sql_notos_num_res['total'];
                                                      //echo  $notos_num;
                                                    //取得最后的更新时间 取消更新时间
                                                      $sql_update = 'SELECT photo_update  FROM `photo` WHERE customers_id ="'.$customers_detail[$i]['customers_id'].'" ORDER BY `photo_update` DESC  LIMIT 1';
                                                      $sql_update_query = tep_db_query($sql_update);
                                                      $sql_update_res = tep_db_fetch_array($sql_update_query);
                                                      $update = $sql_update_res['photo_update'];
                                                      //$i++;
                                                      $array_invidual[$i]=array('custmoers_id'=>$customers_detail[$i]['customers_id'],'customers_firstname'=> $customers_detail[$i]['customers_firstname'],
                                                                               'photo_book_num'=>$photo_num,'notos_num'=>$notos_num,'last_update'=>$update);
                                                      $i++;
                                              }
                                          }else{
                                              $sql_select = 'SELECT c.customers_id,c.customers_firstname FROM customers AS c, photo AS p, photo_books AS pb,travel_notes AS tn';
                            $sql_where.=' WHERE c.customers_id = "'.(int)$_GET['s_search_text_id'].'" AND(p.customers_id="'.(int)$_GET['s_search_text_id'].'" or pb.customers_id="'.(int)$_GET['s_search_text_id'].'" or tn.customers_id="'.(int)$_GET['s_search_text_id'].'")';
                            $sql_group_by.=' GROUP BY c.customers_id';
                            $sql_onload = $sql_select.$sql_where.$sql_group_by;
                            echo  $sql_onload;
                            $companion_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $sql_onload, $companion_query_numrows);
                            $sql_onload_query = tep_db_query($sql_onload);
                            $customers_detail='';
                            //$array_invidual='';
                            $i=0;
                            while($rows = tep_db_fetch_array($sql_onload_query)){
                                  $customers_detail[$i]['customers_id']=$rows['customers_id'];
                                  $customers_detail[$i]['customers_firstname']=$rows['customers_firstname'];
                                  //echo  $customers_detail[$i]['customers_id'];
                                  //取得相册总数
                                  $sql_photo_num = 'SELECT count(photo_books_id) as total FROM  `photo_books` where customers_id ="'.$customers_detail[$i]['customers_id'].'"';
                                 //echo $sql_photo_num;
                                  $sql_photo_num_query = tep_db_query($sql_photo_num);
                                  $sql_photo_num_res = tep_db_fetch_array($sql_photo_num_query);
                                  $photo_num= $sql_photo_num_res['total'];
                                //取得游记总数
                                  $sql_notos_num ='SELECT count(*) as total FROM `travel_notes` WHERE customers_id="'.$customers_detail[$i]['customers_id'].'"';
                                  $sql_notos_num_query = tep_db_query($sql_notos_num);
                                  $sql_notos_num_res = tep_db_fetch_array($sql_notos_num_query);
                                  $notos_num = $sql_notos_num_res['total'];
                                  //echo  $notos_num;
                                //取得最后的更新时间
                                  $sql_update = 'SELECT photo_update  FROM `photo` WHERE customers_id ="'.$customers_detail[$i]['customers_id'].'" ORDER BY `photo_update` DESC  LIMIT 1';
                                  $sql_update_query = tep_db_query($sql_update);
                                  $sql_update_res = tep_db_fetch_array($sql_update_query);
                                  $update = $sql_update_res['photo_update'];
                                  //$i++;
                                  $array_invidual[$i]=array('custmoers_id'=>$customers_detail[$i]['customers_id'],'customers_firstname'=> $customers_detail[$i]['customers_firstname'],
                                                           'photo_book_num'=>$photo_num,'notos_num'=>$notos_num,'last_update'=>$update);
                                  $i++;
                            }
                                              
                                          }

                                 break;
                                 case '6':if(!tep_not_null($_GET['s_search_text_id'])){
                                              if($_GET['sort']=='site_inner_sms_up'||$_GET['sort']=='site_inner_sms_down'){
                                                 $sql = 'SELECT * FROM `site_inner_sms`';
                                                 $where.=' AND type_name ="travel_companion" ';
                                                 $sql_onload = $sql.$where.$sort_time.$order_by.$group_by;
                                              }else{
                                                 $sql = 'SELECT * FROM `site_inner_sms`';
                                                 $where.=' AND type_name ="travel_companion" ';
                                                 $sql_onload = $sql.$where.$sort_time.' ORDER BY `add_date` DESC '.$group_by;
                                                 //$sql_onload = 'SELECT * FROM `travel_notes_comments` WHERE has_remove != "1" ORDER BY `added_time` DESC ';
                                              }
                                           }else{
                                              if($_GET['sort']=='site_inner_sms_up'||$_GET['sort']=='site_inner_sms_down'){
                                                 $sql = 'SELECT * FROM `site_inner_sms`';
                                                 $where.=' AND customers_id ="'.(int)$_GET['s_search_text_id'].'" AND type_name ="travel_companion"';
                                                 $sql_onload = $sql.$where.$order_by.$group_by;
                                              }else{
                                                 $sql = 'SELECT * FROM `site_inner_sms`';
                                                 $where.=' AND customers_id ="'.(int)$_GET['s_search_text_id'].'" AND type_name ="travel_companion"';
                                                 $sql_onload = $sql.$where.$sort_time.' ORDER BY `add_date` DESC '.$group_by;
                                              }
                                           }
                                           //echo $sql_onload;
                                           $companion_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $sql_onload, $companion_query_numrows);
                                           $sql_onload_query = tep_db_query($sql_onload);
                                   break;
                             }
                    }
                  

        }else if($_GET['individual_space']=='0'){
              //搜索关键词 '不限'其他为空
               if(!tep_not_null($_GET['s_search_text_id'])){
                   if($_GET['sort']=='photo_date_up'||$_GET['sort']=='photo_date_down'){
                           $sql = 'SELECT * FROM `photo`';
                           $sql_onload = $sql.$where.$sort_time.$order_by.$group_by;
                   }else{
                           $sql = 'SELECT * FROM `photo`';
                           $sql_onload = $sql.$where.$sort_time.' ORDER BY `photo_update` DESC '.$group_by;
                   }
                   //echo $sql_onload;
                   $companion_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $sql_onload, $companion_query_numrows);
                   $sql_onload_query = tep_db_query($sql_onload);
               }else{
                   if($_GET['sort']=='photo_date_up'||$_GET['sort']=='photo_date_down'){
                          $sql = 'SELECT * FROM `photo` ';
                          $where.= ' AND customers_id ="'.(int)$_GET['s_search_text_id'].'"';
                          $sql_onload = $sql.$where.$sort_time.$order_by.$group_by;
                   }else{
                       $sql = 'SELECT * FROM `photo` ';
                       $where.= ' AND customers_id ="'.(int)$_GET['s_search_text_id'].'"';
                       $sql_onload = $sql.$where.$sort_time.$order_by.$group_by;
                       //$sql_onload = 'SELECT * FROM `photo` WHERE customers_id ="'.(int)$_GET['s_search_text_id'].'" ORDER BY `photo_update` DESC ';
                   }
                   //echo $sql_onload;
                   $companion_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $sql_onload, $companion_query_numrows);
                   $sql_onload_query = tep_db_query($sql_onload);

               }
               
                
        }
}else{
        //页面第一次加载时候的默认搜索
        $companion_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $sql_onload, $companion_query_numrows);
        $sql_onload_query = tep_db_query($sql_onload);
    
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>
<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>
<script type="text/javascript" src="includes/javascript/usitrip-tabs-2009-06-19.js"></script>
<script type="text/javascript" src="includes/javascript/menujs-2008-04-15-min.js"></script>
<script type="text/javascript" src="includes/javascript/add_global.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="includes/ajx.js"></script>
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
<script language="javascript"><!--
    //var Date_Reg_start = new ctlSpiffyCalendarBox("Date_Reg_start", "form_search", "search_start_date","btnDate1","<?php echo ($search_start_date); ?>",scBTNMODE_CUSTOMBLUE);
//var Date_Reg_end = new ctlSpiffyCalendarBox("Date_Reg_end", "form_search", "search_end_date","btnDate2","<?php echo ($search_end_date); ?>",scBTNMODE_CUSTOMBLUE);

<?php
$p=array('/&amp;/','/&quot;/');
$r=array('&','"');
?>
function manage_del_photos(photo_id){
    if(confirm("<?php echo db_to_html("您确实要删除这张相片吗？删除相片会删除相片下的所有评论");?>")){
			var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_manage_individual_space_photo.php','action=manage_del')) ?>");
			url += "&photo_id="+photo_id;
			ajax_get_submit(url);
		}

}
function manage_remove_photo_comments(t_id){
	if(confirm("<?php echo db_to_html("您确定要删除这条评论吗？")?>")){
		//var aid = "#comments_"+comments_id;
		//$(aid).fadeOut(1000);
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_manage_individual_space_photo.php','action=manage_remover_comments')) ?>");
		url += "&comments_id="+t_id;
                url += "&photo_id=<?php echo (int)$photo_id;?>";
	        var success_msm = "";
		var success_go_to = "";
		ajax_get_submit(url,success_msm,success_go_to);
	}
}
function manage_remove_notos_comments(comments_id,notes_id){
    if(confirm("<?php echo db_to_html("您确定要删除这条评论吗？")?>")){
		//var aid = "#comments_"+comments_id;
		//$(aid).fadeOut(1000);
		var url =url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_admin_travel_notes_detail.php','action=manage_remove_comments')) ?>");
		url += "&comments_id="+comments_id;
                url += "&travel_notes_id="+notes_id;
		var success_msm = "";
		var success_go_to = "";
		ajax_get_submit(url,success_msm,success_go_to);
     }
}
function manage_remove_inner_sms(sms_id){
    if(confirm("<?php echo db_to_html("您确定要删除这条短信吗？")?>")){
		//var aid = "#comments_"+comments_id;
		//$(aid).fadeOut(1000);
		var url =url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_admin_travel_notes_detail.php','action=manage_remove_sms')) ?>");
		url += "&sms_id="+sms_id;
                var success_msm = "";
		var success_go_to = "";
		ajax_get_submit(url,success_msm,success_go_to);
     }

}

    
//--></script>
<div id="spiffycalendar" class="text"></div>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('manage_individual_space');
$list = $listrs->showRemark();
?>
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo db_to_html('个人中心管理'); ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
          <!--search form start-->
		  <fieldset>
		  <legend align="left"> Search Module </legend>
		  <?php echo tep_draw_form('form_search', 'manage_individual_space.php', tep_get_all_get_params(array('page','y','x', 'action')), 'get'); ?>

		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td><table border="0" cellspacing="0" cellpadding="0" style="margin:10px;">

				  <tr>
				    <td align="right" valign="middle" nowrap class="main"><?php echo db_to_html('类型')?></td>
				    <td align="left" valign="middle" nowrap class="main">

				<?php
				$option_array = array();
				$option_array[0] = array('id'=>'0', 'text'=>'相片','onclick'=>'location.href="manage_individual_space.php?individual_space=0&search=1"');
                                $option_array[1] = array('id'=>'3','text'=>'相片评论','onclick'=>'location.href="manage_individual_space.php?individual_space=3&search=1"');
                                $option_array[2] = array('id'=>'4','text'=>'游记评论','onclick'=>'location.href="manage_individual_space.php?individual_space=4&search=1"');
				$option_array[3] = array('id'=>'1','text'=>'相册','onclick'=>'location.href="manage_individual_space.php?individual_space=1&search=1"');
                                $option_array[4] = array('id'=>'2','text'=>'游记','onclick'=>'location.href="manage_individual_space.php?individual_space=2&search=1"');
                                $option_array[5] = array('id'=>'6','text'=>'站内短信','onclick'=>'location.href="manage_individual_space.php?individual_space=6&search=1"');
                                //$option_array[5] = array('id'=>'5','text'=>'站内短信','onclick'=>'location.href="manage_individual_space.php?individual_space=5&search=1"');
				$option_array = db_to_html($option_array);
				echo tep_draw_pull_down_menu_onclick('individual_space', $option_array);
                                $display_id = "";
                                $display_photo ="display:none";
                                $display_notos ="display:none";
                                if($_GET['individual_space']=='0'){$display_id = "";$display_photo ="display:none"; $display_notos ="display:none";}
                                if($_GET['individual_space']=='1'){$display_id = "display:none";$display_photo =""; $display_notos ="display:none";}
                                if($_GET['individual_space']=='2'){$display_id = "display:none";$display_photo ="display:none"; $display_notos ="";}
                                if($_GET['individual_space']=='3'){$display_id = "";$display_photo ="display:none"; $display_notos ="display:none";}
                                if($_GET['individual_space']=='5'){$display_id = "";$display_photo ="display:none"; $display_notos ="display:none";}
                                if($_GET['individual_space']=='6'){$display_id = "";$display_photo ="display:none"; $display_notos ="display:none";}
				?>
					&nbsp;</td>
                                     
                                     <td height="30" align="right" valign="middle" nowrap class="main" id="search_id" style="<?=$display_id?>"><?php echo db_to_html('用户ID'); ?>&nbsp;<?php echo tep_draw_input_field('s_search_text_id')?></td>
                                     
                                     <td height="30" align="right" valign="middle" nowrap class="main" style="<?=$display_photo?>" id="search_photo"><?php echo db_to_html('相册名称'); ?><?php echo tep_draw_input_field('s_search_text_photo')?></td>
                                     
                                     <td height="30" align="right" valign="middle" nowrap class="main" style="<?=$display_notos?>" id="search_notos"><?php echo db_to_html('游记题目'); ?>&nbsp;<?php echo tep_draw_input_field('s_search_text_notos')?></td>
                  
                    
                    <td align="right" nowrap class="main"><?php echo db_to_html('更新时间'); ?></td>
                    <td class="main" align="left">&nbsp;
                      <table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td nowrap class="main">&nbsp;<?php echo tep_draw_input_field('search_start_date', tep_get_date_disp($search_start_date), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"');?><script language="javascript">//Date_Reg_start.writeControl(); Date_Reg_start.dateFormat="yyyy-MM-dd";</script></td>
                          <td class="main">&nbsp;至&nbsp;</td>
                          <td nowrap class="main"><?php echo tep_draw_input_field('search_end_date', tep_get_date_disp($search_end_date), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"');?><script language="javascript">//Date_Reg_end.writeControl(); Date_Reg_end.dateFormat="yyyy-MM-dd";</script></td>
                        </tr>
                      </table></td>
                  </tr>
                  
                  <tr>
                    <td class="main" align="right">&nbsp;</td>
                    <td class="main" align="right">&nbsp;</td>
                    <td class="main" align="right">&nbsp;</td>
                    <td class="main" align="left">&nbsp;<input name="Send" type="submit" value="搜索" style="width:100px; height:30px; margin-top:10px;"></td>
                    <td>&nbsp;</td>
                     <td colspan="2" align="right" class="main"><input name="search" type="hidden" id="search" value="1"></td>
                    
                    </tr>
                </table>

				</td>
			  </tr>
			</table>

		  <?php echo '</form>';?>
		  </fieldset>
		  <!--search form end-->
		  </td>
      </tr>
      <tr>
        <td>
          <!--search form start-->
		  <fieldset>
                   <?php
                   //景点推荐部分，查询已经推荐的景点，提交新的推荐景点。
                   $sql_recommend = 'SELECT * FROM `configuration` WHERE configuration_key ="TRAVEL_COMPANION_RECOMMEND_CATEGORIES"';
                   $sql_recommend_query = tep_db_query($sql_recommend);
                   $sql_recommend_query_rows = tep_db_fetch_array($sql_recommend_query);
                   //echo $sql_recommend_query_rows['configuration_value'];
                   if($_GET['viewpoint_recommend']!=''&&$_GET['viewpoint_recommend']!=$sql_recommend_query_rows['configuration_value']){
                       $db_input_recommend = preg_replace('/[[:space:]]+/',' ',$db_input_recommend);
                       $db_input_recommend = preg_replace("/,{2,}/",",",$_GET['viewpoint_recommend']);
                      
                       //echo $db_input_recommend;
                       $sql_recommend = 'UPDATE `configuration` SET `configuration_value` ="'.$db_input_recommend.'"WHERE configuration_key ="TRAVEL_COMPANION_RECOMMEND_CATEGORIES"';
                       tep_db_query($sql_recommend);
                      
                   }

                   ?>
		  <legend align="left"> 添加推荐景点 </legend>
                   <?php echo tep_draw_form('form_recommend', 'manage_individual_space.php', tep_get_all_get_params(array('page','y','x', 'action')), 'get'); ?>

		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td><table border="0" cellspacing="0" cellpadding="0" style="margin:10px;">
                                        <h4><font color="red"><?php echo db_to_html('注意: 主要用于结伴同游中推荐景点。输入格式：24,25,33')?></font></h4>
                                        <h5><font color="blue"><?php echo db_to_html('目前已推荐的景点:').$sql_recommend_query_rows['configuration_value']?></font></h5>
				  <tr>
				    <td align="right" valign="middle" nowrap class="main"><?php echo db_to_html('推荐景点')?></td>
                                    <?= tep_draw_textarea_field('viewpoint_recommend','virtual',65,1,$sql_recommend_query_rows['configuration_value'],' class="textarea4" title="'.db_to_html('你输入的景点数字号码将出现在结伴同游首页上，请务必核对输入。').'" style="ime-mode: disabled;" onFocus="Check_Onfocus(this)" onBlur="Check_Onblur(this)" ');?>
                                    <td class="main" align="left">&nbsp;<input name="Send" type="submit" value="提交" style="width:100px; height:30px; margin-top:10px;"></td>
                                  </tr>
                                    </table>
                                </td>
                          </tr>
                  </table>

                  </fieldset>
        </td>
        </tr>
      <tr>
        <td>
		<fieldset>
		  <legend align="left"> Stats Results </legend>
                  <?php
                  if($_GET['search']=='1'){
                       if($_GET['individual_space']!='0'){
                            if((int)$_GET['individual_space']>'0'){
                                switch ((int)$_GET['individual_space']){
                                    case '2':?>
                                              <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="1" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" nowrap="nowrap">游记ID</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">游记题目</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">产品ID</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">作者(用户)</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">最后更新时间
                    <a href="<?php echo tep_href_link('manage_individual_space.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=travel_notes_date_down');?>" title="down"><img src="images/arrow_down.gif" border="0"></a>
                    <a href="<?php echo tep_href_link('manage_individual_space.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=travel_notes_date_up');?>" title="up"><img border="0" src="images/arrow_up.gif"></a>
                </td>
                <td class="dataTableHeadingContent" nowrap="nowrap">回复数</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">操作</td>
              </tr>
                                             <?php

                                             while($rows = tep_db_fetch_array($sql_onload_query)){
                                                   $rows_num++;
					           if (strlen($rows) < 2) {
				                      $rows_num = '0' . $rows_num;
				                   }
						   $bg_color = "#F0F0F0";
				                   if((int)$rows_num %2 ==0 && (int)$rows_num){
					               $bg_color = "#ECFFEC";
				                   }
                                                   $admin_travel_notes_detail_link = tep_href_link('admin_travel_notes_detail.php','travel_notes_id='.$rows['travel_notes_id']);
                                                   $customers_name = tep_customers_name($rows['customers_id']);
                                                   ?>
                                          <tr class="dataTableRow" style="cursor:auto; background-color:<?= $bg_color;?>">
                                               <td class="dataTableContent" nowrap="nowrap"><?php echo tep_db_output($rows['travel_notes_id'])?></td>
                                               <td class="dataTableContent"><?php echo tep_db_output($rows['travel_notes_title'])?></td>
                                               <td class="dataTableContent" nowrap="nowrap"><?php echo tep_db_output($rows['products_id'])?></td>
                                               <td class="dataTableContent" nowrap="nowrap"><a href="<?php echo tep_href_link('individual_space_customers_detail.php','i_custmoers_id='.$rows['customers_id']).'&i_custmoers_name='.$customers_name?>"><font color="blue"><?php echo '('.tep_db_output($rows['customers_id']).')'.$customers_name?></font></a></td>
                                               <td class="dataTableContent" nowrap="nowrap"><?php echo tep_db_output($rows['added_time'])?></td>
                                               <td class="dataTableContent" nowrap="nowrap"><?php echo tep_db_output($rows['comment_num'])?></td>
                                               <td class="dataTableContent" nowrap="nowrap"><font color="blue">[<a href="<?= $admin_travel_notes_detail_link?>"><font color="blue">详情查看</font></a>]</font></td>
                                          </tr>

                                                 
                                            <?php }?>
                                             </table></td>
                                            </tr>
                                            <tr>
                                              <td colspan="<?= $colspan?>"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                                          <tr>
                                        <td class="smallText" valign="top"><?php echo $companion_split->display_count($companion_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                                        <td class="smallText" align="right"><?php echo $companion_split->display_links($companion_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $_GET['page'],tep_get_all_get_params(array('page','y','x', 'action', 'SortSubmit','sort_order_array'))); ?>&nbsp;</td>
                                      </tr>
                                    </table></td>
                                      </tr>
                                              </table><?php

                                    break;
                                    case '1':?>
                                        <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="1" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" nowrap="nowrap">相册ID</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">相册名称</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">相册描述</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">作者(用户)</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">发表时间
                    <a href="<?php echo tep_href_link('manage_individual_space.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=photo_book_date_down');?>" title="down"><img src="images/arrow_down.gif" border="0"></a>
                    <a href="<?php echo tep_href_link('manage_individual_space.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=photo_book_date_up');?>" title="up"><img border="0" src="images/arrow_up.gif"></a>
                </td>
                <td class="dataTableHeadingContent" nowrap="nowrap">相片数</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">点击率</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">操作</td>
              </tr>
                                             <?php

                                             while($rows = tep_db_fetch_array($sql_onload_query)){
                                                   $rows_num++;
					           if (strlen($rows) < 2) {
				                      $rows_num = '0' . $rows_num;
				                   }
						   $bg_color = "#F0F0F0";
				                   if((int)$rows_num %2 ==0 && (int)$rows_num){
					               $bg_color = "#ECFFEC";
				                   }
                                                   $photo_list_href = tep_href_link('admin_photo_list.php','photo_books_id='.$rows['photo_books_id'].'&i_custmoers_id='.$rows['customers_id']);
                                                   $customers_name = tep_customers_name($rows['customers_id']);
                                                   ?>
                                          <tr class="dataTableRow" style="cursor:auto; background-color:<?= $bg_color;?>">
                                               <td class="dataTableContent" nowrap="nowrap"><?php echo tep_db_output($rows['photo_books_id'])?></td>
                                               <td class="dataTableContent" nowrap="nowrap"><?php echo tep_db_output($rows['photo_books_name'])?></td>
                                               <td class="dataTableContent" nowrap="nowrap"><?php echo tep_db_output($rows['photo_books_description'])?></td>
                                               <td class="dataTableContent" nowrap="nowrap"><a href="<?php echo tep_href_link('individual_space_customers_detail.php','i_custmoers_id='.$rows['customers_id']).'&i_custmoers_name='.$customers_name?>"><font color="blue"><?php echo '('.tep_db_output($rows['customers_id']).')'.$customers_name?></font></a></td>
                                               <td class="dataTableContent" nowrap="nowrap"><?php echo tep_db_output($rows['photo_books_date'])?></td>
                                               <td class="dataTableContent" nowrap="nowrap"><?php echo tep_db_output($rows['photo_sum'])?></td>
                                               <td class="dataTableContent" nowrap="nowrap"><?php echo tep_db_output($rows['books_hot'])?></td>
                                               <td class="dataTableContent" nowrap="nowrap"><font color="blue">[<a href="<?= $photo_list_href?>"><font color="blue">详情查看</font></a>]</font></td>
                                          </tr>

                                                 
                                            <?php }?>
                                             </table></td>
                                            </tr>
                                            <tr>
                                              <td colspan="<?= $colspan?>"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                                          <tr>
                                        <td class="smallText" valign="top"><?php echo $companion_split->display_count($companion_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                                        <td class="smallText" align="right"><?php echo $companion_split->display_links($companion_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $_GET['page'],tep_get_all_get_params(array('page','y','x', 'action', 'SortSubmit','sort_order_array'))); ?>&nbsp;</td>
                                      </tr>
                                    </table></td>
                                      </tr>
                                              </table><?php

                                    break;
                                  case '3':?>
                                         <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="1" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent">相片ID</td>
                <td class="dataTableHeadingContent" nowrap="nowrap"><?php echo db_to_html('图片预览'); ?></td>
                <td class="dataTableHeadingContent">评论人</td>
                <td class="dataTableHeadingContent">评论ID</td>
                <td class="dataTableHeadingContent">评论内容</td>
                <td class="dataTableHeadingContent">评论时间
                    <a href="<?php echo tep_href_link('manage_individual_space.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=photo_comments_date_down');?>" title="down"><img src="images/arrow_down.gif" border="0"></a>
                    <a href="<?php echo tep_href_link('manage_individual_space.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=photo_comments_date_up');?>" title="up"><img border="0" src="images/arrow_up.gif"></a>
                </td>
                <td class="dataTableHeadingContent" nowrap="nowrap">操作</td>
              </tr>
                                             <?php

                                             while($rows = tep_db_fetch_array($sql_onload_query)){
                                                   $rows_num++;
					           if (strlen($rows) < 2) {
				                      $rows_num = '0' . $rows_num;
				                   }
						   $bg_color = "#F0F0F0";
				                   if((int)$rows_num %2 ==0 && (int)$rows_num){
					               $bg_color = "#ECFFEC";
				                   }
                                                   $photo_list_href = tep_href_link('admin_photo_list.php','photo_books_id='.$rows['photo_books_id'].'&i_custmoers_id='.$rows['customers_id']);
                                                   $customers_name = tep_customers_name($rows['customers_id']);
                                                   $sql_query =tep_db_query('SELECT photo_name,photo_books_id FROM `photo` WHERE photo_id ="'.(int)$rows['photo_id'].'"');
                                                   $sql_query_rows = tep_db_fetch_array($sql_query);
                                                   $photos_0_name = "images/photos/".$sql_query_rows['photo_name'];
                                                   $photo_link = DIR_FS_CATALOG.$photos_0_name;
                                                   $thumbnails_img = get_thumbnails(DIR_FS_CATALOG.$photos_0_name);
                                                   $s = strlen(DIR_FS_CATALOG)-1;
                                                   $l = strlen($thumbnails_img);
                                                   $thumbnails_img = substr($thumbnails_img,$s, $l = strlen($thumbnails_img)-$s);
                                                   $WH = getimgHW3hw_wh($photo_link,144,109);
                                                   $wh_array=explode("@",$WH);
                                                   $manage_edit_photo_link= tep_href_link('admin_photo_list.php','photo_books_id='.$sql_query_rows['photo_books_id'].'&photo_id='.$rows['photo_id'].'#apictag');
                                                   ?>
                                          <tr class="dataTableRow" style="cursor:auto; background-color:<?= $bg_color;?>">
                                               <td class="dataTableContent"><?php echo tep_db_output($rows['photo_id'])?></td>
                                               <td class="dataTableContent" nowrap="nowrap"><a id="href_big_pic" href="<?= '/'.$photos_0_name?>" target="_blank"><?PHP echo tep_image($thumbnails_img, '',$wh_array[0],$wh_array[1])?></a></td>
                                               
                                               <td class="dataTableContent" nowrap="nowrap"><a href="<?php echo tep_href_link('individual_space_customers_detail.php','i_custmoers_id='.$rows['customers_id']).'&i_custmoers_name='.$customers_name?>"><font color="blue"><?php echo '('.tep_db_output($rows['customers_id']).')'.$customers_name?></font></a></td>
                                               <td class="dataTableContent"><?php echo tep_db_output($rows['photo_comments_id'])?></td>
                                               <td class="dataTableContent"><?php echo tep_db_output($rows['photo_comments_content'])?></td>
                                               <td class="dataTableContent"><?php echo tep_db_output($rows['added_time'])?></td>
                                               <td class="dataTableContent" nowrap="nowrap"><font color="blue">[<a href="<?=$manage_edit_photo_link?>"><font color="blue">编辑查看</font></a>]</font><a href="JavaScript:void(0)" onClick="manage_remove_photo_comments(<?=$rows['photo_comments_id']?>);"><font color="blue"><?php echo db_to_html('[删除]');?></font></a></td>

                                          </tr>


                                            <?php }?>
                                             </table></td>
                                            </tr>
                                            <tr>
                                              <td colspan="<?= $colspan?>"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                                          <tr>
                                        <td class="smallText" valign="top"><?php echo $companion_split->display_count($companion_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                                        <td class="smallText" align="right"><?php echo $companion_split->display_links($companion_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $_GET['page'],tep_get_all_get_params(array('page','y','x', 'action', 'SortSubmit','sort_order_array'))); ?>&nbsp;</td>
                                      </tr>
                                    </table></td>
                                      </tr>
                                              </table><?php
                                    break;
                                  case '4':
                                      ?>
                                         <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="1" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" nowrap="nowrap">游记ID</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">游记题目</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">评论ID</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">评论内容</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">评论时间
                    <a href="<?php echo tep_href_link('manage_individual_space.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=travel_notes_comments_date_down');?>" title="down"><img src="images/arrow_down.gif" border="0"></a>
                    <a href="<?php echo tep_href_link('manage_individual_space.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=travel_notes_comments_date_up');?>" title="up"><img border="0" src="images/arrow_up.gif"></a>
                </td>
                <td class="dataTableHeadingContent" nowrap="nowrap">评论人</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">操作</td>
              </tr>
                                             <?php

                                             while($rows = tep_db_fetch_array($sql_onload_query)){
                                                   $rows_num++;
					           if (strlen($rows) < 2) {
				                      $rows_num = '0' . $rows_num;
				                   }
						   $bg_color = "#F0F0F0";
				                   if((int)$rows_num %2 ==0 && (int)$rows_num){
					               $bg_color = "#ECFFEC";
				                   }
                                                   $admin_travel_notes_detail_link = tep_href_link('admin_travel_notes_detail.php','travel_notes_id='.$rows['travel_notes_id']);
                                                   $customers_name = tep_customers_name($rows['customers_id']);
                                                   $notos_titile_query = tep_db_query('SELECT travel_notes_title FROM `travel_notes`WHERE `travel_notes_id`= "'.(int)$rows['travel_notes_id'].'"');
                                                   $notos_titile_rows = tep_db_fetch_array($notos_titile_query);

                                                   ?>
                                          <tr class="dataTableRow" style="cursor:auto; background-color:<?= $bg_color;?>">
                                               <td class="dataTableContent"><?php echo tep_db_output($rows['travel_notes_id'])?></td>
                                               <td class="dataTableContent"><?php echo tep_db_output($notos_titile_rows['travel_notes_title'])?></td>
                                               <td class="dataTableContent"><?php echo tep_db_output($rows['travel_notes_comments_id'])?></td>
                                               <td class="dataTableContent"><?php echo tep_db_output($rows['comments'])?></td>
                                               <td class="dataTableContent"><?php echo tep_db_output($rows['added_time'])?></td>
                                               <td class="dataTableContent" nowrap="nowrap"><a href="<?php echo tep_href_link('individual_space_customers_detail.php','i_custmoers_id='.$rows['customers_id']).'&i_custmoers_name='.$customers_name?>"><font color="blue"><?php echo '('.tep_db_output($rows['customers_id']).')'.$customers_name?></font></a></td>
                                               <td class="dataTableContent" nowrap="nowrap"><font color="blue">[<a href="<?= $admin_travel_notes_detail_link?>"><font color="blue">详情查看</font></a>]</font><a href="JavaScript:void(0)" onClick="manage_remove_notos_comments(<?=$rows['travel_notes_comments_id']?>,<?=$rows['travel_notes_id']?>);"><font color="blue"><?php echo db_to_html('[删除]');?></font></a></td>

                                          </tr>


                                            <?php }?>
                                             </table></td>
                                            </tr>
                                            <tr>
                                              <td colspan="<?= $colspan?>"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                                          <tr>
                                        <td class="smallText" valign="top"><?php echo $companion_split->display_count($companion_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                                        <td class="smallText" align="right"><?php echo $companion_split->display_links($companion_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $_GET['page'],tep_get_all_get_params(array('page','y','x', 'action', 'SortSubmit','sort_order_array'))); ?>&nbsp;</td>
                                      </tr>
                                    </table></td>
                                      </tr>
                                              </table><?php
                                    break;
                                   case '5':?>
                                         <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                      <tr>
                                        <td valign="top"><table border="0" width="100%" cellspacing="1" cellpadding="2">
                                          <tr class="dataTableHeadingRow">
                                            <td class="dataTableHeadingContent" nowrap="nowrap">&nbsp;</td>
                                            <td class="dataTableHeadingContent" nowrap="nowrap"><?php echo db_to_html('用户ID'); ?>
                                                
                                            </td>
                                                            <td class="dataTableHeadingContent" nowrap="nowrap"><?PHP echo db_to_html('用户姓名')?></td>
                                                            <td class="dataTableHeadingContent" nowrap="nowrap"><?PHP echo db_to_html('相册')?></td>
                                                            <td class="dataTableHeadingContent" nowrap="nowrap"><?PHP echo db_to_html('游记')?></td>
                                                            <td class="dataTableHeadingContent" nowrap="nowrap"><?php echo db_to_html('更新时间'); ?>
                                                            </td>



                                                            <td class="dataTableHeadingContent" nowrap="nowrap"><?php echo db_to_html('操作'); ?></td>
                                          </tr>
                                                    <?php
                                                    $rows_num=0;
                                                    for($i=0;$i<count($array_invidual);$i++){
                                                         $rows_num++;
                                                         if(strlen($rows) < 2) {
                                                              $rows_num = '0' . $rows_num;
                                                         }
                                                         $bg_color = "#F0F0F0";
                                                        if((int)$rows_num %2 ==0 && (int)$rows_num){
                                                         $bg_color = "#ECFFEC";
                                                       }?>
                                                     <tr class="dataTableRow" style="cursor:auto; background-color:<?= $bg_color;?>">
                                                         <td class="dataTableContent" nowrap="nowrap"></td>
                                                         <td class="dataTableContent"><?php echo db_to_html($array_invidual[$i]['custmoers_id']);?></td>
                                                         <td class="dataTableContent"><?php echo db_to_html($array_invidual[$i]['customers_firstname']);?></td>
                                                         <td class="dataTableContent"><?php echo db_to_html($array_invidual[$i]['photo_book_num']);?></td>
                                                         <td class="dataTableContent"><?php echo db_to_html($array_invidual[$i]['notos_num']);?></td>
                                                         <td class="dataTableContent"><?php echo db_to_html($array_invidual[$i]['last_update']);?></td>
                                                         <td nowrap class="dataTableContent"><font color="blue">[<a href="<?php echo tep_href_link('individual_space_customers_detail.php','i_custmoers_id='.$array_invidual[$i]['custmoers_id']).'&i_custmoers_name='.$array_invidual[$i]['customers_firstname']?>"><font color="blue">查看个人空间</font></a>]</font>
                                                         </td>

                                                    </tr>
                                                    <?php
                                                    }
                                                    ?>

                                        </table></td>
                                      </tr>

                                              <tr>

                                                    <td colspan="<?= $colspan?>"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                                          <tr>
                                            <td class="smallText" valign="top"><?php echo $companion_split->display_count($companion_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                                            <td class="smallText" align="right"><?php echo $companion_split->display_links($companion_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $_GET['page'],tep_get_all_get_params(array('page','y','x', 'action', 'SortSubmit','sort_order_array'))); ?>&nbsp;</td>
                                          </tr>
                                        </table></td>
                                      </tr>
                                    </table><?php

                                    break;
                                  case '6':?>
                                         <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="1" cellpadding="2">
                    <tr class="dataTableHeadingRow">
                        <?php
                        $total_sms_query = tep_db_query('SELECT count( * ) AS total FROM `site_inner_sms` ');
                        $total_sms_rows = tep_db_fetch_array($total_sms_query);
                        ?>
                    <td colspan="11" nowrap="nowrap" class="dataTableHeadingContent"><?= db_to_html('数据库总计（<span id="comments_num">'.$total_sms_rows['total'].'</span>条）')?></td>
                    </tr>
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" nowrap="nowrap">短信ID</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">类型</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">相关帖子</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">发件人</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">收件人</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">短信内容</td>
                 <td class="dataTableHeadingContent" nowrap="nowrap">发信时间
                    <a href="<?php echo tep_href_link('manage_individual_space.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=site_inner_sms_down');?>" title="down"><img src="images/arrow_down.gif" border="0"></a>
                    <a href="<?php echo tep_href_link('manage_individual_space.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=site_inner_sms_up');?>" title="up"><img border="0" src="images/arrow_up.gif"></a>
                </td>
                <td class="dataTableHeadingContent" nowrap="nowrap">短信所有者</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">是否阅读</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">短信类型</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">操作</td>
              </tr>
                                             <?php

                                             while($rows = tep_db_fetch_array($sql_onload_query)){
                                                   $rows_num++;
					           if (strlen($rows) < 2) {
				                      $rows_num = '0' . $rows_num;
				                   }
						   $bg_color = "#F0F0F0";
				                   if((int)$rows_num %2 ==0 && (int)$rows_num){
					               $bg_color = "#ECFFEC";
				                   }
                                                   $travel_companion_detail_link = tep_href_link('travel_companion_re.php','t_companion_id='.$rows['key_id']);
                                                   $customers_name = tep_customers_name($rows['customers_id']);
                                                   $to_customers_name = tep_customers_name($rows['to_customers_id']);
                                                   $travel_companion_query = tep_db_query('SELECT t_companion_title  FROM `travel_companion` WHERE `t_companion_id`= "'.(int)$rows['key_id'].'"');
                                                   $travel_companion_rows = tep_db_fetch_array($travel_companion_query);

                                                   ?>
                                          <tr class="dataTableRow" style="cursor:auto; background-color:<?= $bg_color;?>">
                                               <td class="dataTableContent"><?php echo tep_db_output($rows['sis_id'])?></td>
                                               <td class="dataTableContent"><?php echo tep_db_output($rows['type_name'])?></td>
                                                <td class="dataTableContent"><a href="<?= $travel_companion_detail_link?>"><font color="blue"><?php echo '('.tep_db_output($rows['key_id']).')'.$travel_companion_rows['t_companion_title']?></font></a></td>
                                               <td class="dataTableContent" nowrap="nowrap"><a href="<?php echo tep_href_link('individual_space_customers_detail.php','i_custmoers_id='.$rows['customers_id']).'&i_custmoers_name='.$customers_name?>"><font color="blue"><?php echo '('.tep_db_output($rows['customers_id']).')'.$customers_name?></font></a></td>
                                               <td class="dataTableContent" nowrap="nowrap"><a href="<?php echo tep_href_link('individual_space_customers_detail.php','i_custmoers_id='.$rows['to_customers_id']).'&i_custmoers_name='.$to_customers_name?>"><font color="blue"><?php echo '('.tep_db_output($rows['to_customers_id']).')'.$to_customers_name?></font></a></td>
                                               <td class="dataTableContent"><?php echo tep_db_output($rows['sms_content'])?></td>
                                               <td class="dataTableContent"><?php echo tep_db_output($rows['add_date'])?></td>
                                               <td class="dataTableContent"><?php echo tep_db_output($rows['owner_id'])?></td>
                                               <td class="dataTableContent"><?php echo tep_db_output($rows['has_read'])?></td>
                                               <td class="dataTableContent"><?php echo tep_db_output($rows['sms_type'])?></td>
                                               <td class="dataTableContent" nowrap="nowrap"><font color="blue"><a href="JavaScript:void(0)" onClick="manage_remove_inner_sms(<?=$rows['sis_id']?>);"><font color="blue"><?php echo db_to_html('[删除]');?></font></a></font></td>

                                          </tr>


                                            <?php }?>
                                             </table></td>
                                            </tr>
                                            <tr>
                                              <td colspan="<?= $colspan?>"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                                          <tr>
                                        <td class="smallText" valign="top"><?php echo $companion_split->display_count($companion_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                                        <td class="smallText" align="right"><?php echo $companion_split->display_links($companion_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $_GET['page'],tep_get_all_get_params(array('page','y','x', 'action', 'SortSubmit','sort_order_array'))); ?>&nbsp;</td>
                                      </tr>
                                    </table></td>
                                      </tr>
                                              </table><?php
                                    break;
                                }

                           }
                            
                       }else if($_GET['individual_space']=='0'){ ?>
                           
                                <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                      <tr>
                                        <td valign="top"><table border="0" width="100%" cellspacing="1" cellpadding="2">
                                          <tr class="dataTableHeadingRow">
                                            <td class="dataTableHeadingContent" nowrap="nowrap">&nbsp;</td>
                                            <td class="dataTableHeadingContent" nowrap="nowrap"><?php echo db_to_html('相片ID'); ?></td>
                                                            <td class="dataTableHeadingContent" nowrap="nowrap"><?php echo db_to_html('图片预览'); ?>
                                                            <td class="dataTableHeadingContent" nowrap="nowrap"><?PHP echo db_to_html('相片题目')?></td>
                                                            <td class="dataTableHeadingContent" nowrap="nowrap"><?PHP echo db_to_html('相片内容')?></td>
                                                            <td class="dataTableHeadingContent" nowrap="nowrap"><?PHP echo db_to_html('相册ID')?></td>
                                                            <td class="dataTableHeadingContent" nowrap="nowrap"><?php echo db_to_html('发布人'); ?>
                                                            <td class="dataTableHeadingContent" nowrap="nowrap"><?php echo db_to_html('所属产品ID'); ?>
                                                            <td class="dataTableHeadingContent" nowrap="nowrap"><?php echo db_to_html('最后更新时间'); ?>
                                                            <a href="<?php echo tep_href_link('manage_individual_space.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=photo_date_down');?>" title="down"><img src="images/arrow_down.gif" border="0"></a>
                                                            <a href="<?php echo tep_href_link('manage_individual_space.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=photo_date_up');?>" title="up"><img border="0" src="images/arrow_up.gif"></a>				</td>



                                                            <td class="dataTableHeadingContent" nowrap="nowrap"><?php echo db_to_html('操作'); ?></td>
                                          </tr>
                                                    <?php

                                             while($rows = tep_db_fetch_array($sql_onload_query)){
                                                   $rows_num++;
					           if (strlen($rows) < 2) {
				                      $rows_num = '0' . $rows_num;
				                   }
						   $bg_color = "#F0F0F0";
				                   if((int)$rows_num %2 ==0 && (int)$rows_num){
					               $bg_color = "#ECFFEC";
				                   }
                                                   $customers_name = tep_customers_name($rows['customers_id']);
                                                   $photos_0_name = "images/photos/".$rows['photo_name'];
                                                   $photo_link = DIR_FS_CATALOG.$photos_0_name;
                                                   $thumbnails_img = get_thumbnails(DIR_FS_CATALOG.$photos_0_name);
                                                   $s = strlen(DIR_FS_CATALOG)-1;
                                                   $l = strlen($thumbnails_img);
                                                   $thumbnails_img = substr($thumbnails_img,$s, $l = strlen($thumbnails_img)-$s);
                                                   $WH = getimgHW3hw_wh($photo_link,144,109);
                                                   $wh_array=explode("@",$WH);
                                                   $manage_edit_photo_link= tep_href_link('admin_photo_list.php','photo_books_id='.$rows['photo_books_id'].'&photo_id='.$rows['photo_id'].'#apictag');
                                                   ?>
                                          <tr class="dataTableRow" style="cursor:auto; background-color:<?= $bg_color;?>">
                                               <td class="dataTableHeadingContent" nowrap="nowrap">&nbsp;</td>
                                               <td class="dataTableContent"><?php echo tep_db_output($rows['photo_id'])?></td>
                                               <td class="dataTableContent" nowrap="nowrap"><a id="href_big_pic" href="<?= '/'.$photos_0_name?>" target="_blank"><?PHP echo tep_image($thumbnails_img, '',$wh_array[0],$wh_array[1])?></a></td>
                                               <td class="dataTableContent" nowrap="nowrap"><?php echo tep_db_output($rows['photo_title'])?></td>
                                               <td class="dataTableContent"><?php echo tep_db_output($rows['photo_content'])?></td>
                                               <td class="dataTableContent" nowrap="nowrap"><?php echo tep_db_output($rows['photo_books_id'])?></td>
                                               <td class="dataTableContent" nowrap="nowrap"><a href="<?php echo tep_href_link('individual_space_customers_detail.php','i_custmoers_id='.$rows['customers_id']).'&i_custmoers_name='.$customers_name?>"><font color="blue"><?php echo '('.tep_db_output($rows['customers_id']).')'.$customers_name?></font></a></td>
                                               <td class="dataTableContent" nowrap="nowrap"><?php echo tep_db_output($rows['products_id'])?></td>
                                               <td class="dataTableContent" nowrap="nowrap"><?php echo tep_db_output($rows['photo_update'])?></td>
                                               <td class="dataTableContent" nowrap="nowrap"><font color="blue">[<a href="<?=$manage_edit_photo_link?>"><font color="blue">编辑查看</font></a>]</font><a href="JavaScript:void(0)" onClick="manage_del_photos(<?= (int)$rows['photo_id']?>);"><font color="blue"><?php echo db_to_html('[删除]');?></font></a></td>
                                          </tr>


                                            <?php }?>
                                             </table></td>
                                            </tr>
                                            <tr>
                                              <td colspan="<?= $colspan?>"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                                          <tr>
                                        <td class="smallText" valign="top"><?php echo $companion_split->display_count($companion_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                                        <td class="smallText" align="right"><?php echo $companion_split->display_links($companion_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $_GET['page'],tep_get_all_get_params(array('page','y','x', 'action', 'SortSubmit','sort_order_array'))); ?>&nbsp;</td>
                                      </tr>
                                    </table></td>
                                      </tr>
                                              </table>
                          <?}
                          

                       
                  }else{
                  ?>

                                            <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                      <tr>
                                        <td valign="top"><table border="0" width="100%" cellspacing="1" cellpadding="2">
                                          <tr class="dataTableHeadingRow">
                                            <td class="dataTableHeadingContent" nowrap="nowrap">&nbsp;</td>
                                            <td class="dataTableHeadingContent" nowrap="nowrap"><?php echo db_to_html('相片ID'); ?></td>
                                                            <td class="dataTableHeadingContent" nowrap="nowrap"><?php echo db_to_html('图片预览'); ?></td>
                                                            <td class="dataTableHeadingContent" nowrap="nowrap"><?PHP echo db_to_html('相片题目')?></td>
                                                            <td class="dataTableHeadingContent" nowrap="nowrap"><?PHP echo db_to_html('相片内容')?></td>
                                                            <td class="dataTableHeadingContent" nowrap="nowrap"><?PHP echo db_to_html('相册ID')?></td>
                                                            <td class="dataTableHeadingContent" nowrap="nowrap"><?php echo db_to_html('发布人'); ?></td>
                                                            <td class="dataTableHeadingContent" nowrap="nowrap"><?php echo db_to_html('所属产品ID'); ?></td>
                                                            
                                                            <?php    if($_GET['search']=='1'){ ?>
                                                                <td class="dataTableHeadingContent" nowrap="nowrap"><?php echo db_to_html('最后更新时间'); ?>
                                                                <a href="<?php echo tep_href_link('manage_individual_space.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=photo_date_down');?>" title="down"><img src="images/arrow_down.gif" border="0"></a>
                                                                <a href="<?php echo tep_href_link('manage_individual_space.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=photo_date_up');?>" title="up"><img border="0" src="images/arrow_up.gif"></a>				</td>
                                                                <?php }else{?>
                                                                <td class="dataTableHeadingContent" nowrap="nowrap"><?php echo db_to_html('最后更新时间'); ?>
                                                                <a href="<?php echo tep_href_link('manage_individual_space.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=photo_date_down&individual_space=0&search=1');?>" title="down"><img src="images/arrow_down.gif" border="0"></a>
                                                                <a href="<?php echo tep_href_link('manage_individual_space.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=photo_date_upindividual_space=0&search=1');?>" title="up"><img border="0" src="images/arrow_up.gif"></a>				</td>
                                                                <?php }?>


                                                            <td class="dataTableHeadingContent" nowrap="nowrap"><?php echo db_to_html('操作'); ?></td>
                                          </tr>
                                                    <?php

                                             while($rows = tep_db_fetch_array($sql_onload_query)){
                                                   $rows_num++;
					           if (strlen($rows) < 2) {
				                      $rows_num = '0' . $rows_num;
				                   }
						   $bg_color = "#F0F0F0";
				                   if((int)$rows_num %2 ==0 && (int)$rows_num){
					               $bg_color = "#ECFFEC";
				                   }
                                                   $customers_name = tep_customers_name($rows['customers_id']);
                                                   $photos_0_name = "images/photos/".$rows['photo_name'];
                                                   $photo_link = DIR_FS_CATALOG.$photos_0_name;
                                                   $thumbnails_img = get_thumbnails(DIR_FS_CATALOG.$photos_0_name);
                                                   $s = strlen(DIR_FS_CATALOG)-1;
                                                   $l = strlen($thumbnails_img);
                                                   $thumbnails_img = substr($thumbnails_img,$s, $l-$s);
                                                   $WH = getimgHW3hw_wh($photo_link,144,109);
                                                   $wh_array=explode("@",$WH);
                                                   $manage_edit_photo_link= tep_href_link('admin_photo_list.php','photo_books_id='.$rows['photo_books_id'].'&photo_id='.$rows['photo_id'].'#apictag');
                                                   ?>
                                          <tr class="dataTableRow" style="cursor:auto; background-color:<?= $bg_color;?>">
                                               <td class="dataTableHeadingContent" nowrap="nowrap">&nbsp;</td>
                                               <td class="dataTableContent"><?php echo tep_db_output($rows['photo_id'])?></td>
                                               <td class="dataTableContent" nowrap="nowrap"><a id="href_big_pic" href="<?= '/'.$photos_0_name?>" target="_blank"><?PHP echo tep_image($thumbnails_img, '',$wh_array[0],$wh_array[1])?></a></td>
                                               <td class="dataTableContent" nowrap="nowrap"><?php echo tep_db_output($rows['photo_title'])?></td>
                                               <td class="dataTableContent"><?php echo tep_db_output($rows['photo_content'])?></td>
                                               <td class="dataTableContent" nowrap="nowrap"><?php echo tep_db_output($rows['photo_books_id'])?></td>
                                               <td class="dataTableContent" nowrap="nowrap"><a href="<?php echo tep_href_link('individual_space_customers_detail.php','i_custmoers_id='.$rows['customers_id']).'&i_custmoers_name='.$customers_name?>"><font color="blue"><?php echo '('.tep_db_output($rows['customers_id']).')'.$customers_name?></font></a></td>
                                               <td class="dataTableContent" nowrap="nowrap"><?php echo tep_db_output($rows['products_id'])?></td>
                                               <td class="dataTableContent" nowrap="nowrap"><?php echo tep_db_output($rows['photo_update'])?></td>
                                               <td class="dataTableContent" nowrap="nowrap"><font color="blue">[<a href="<?=$manage_edit_photo_link?>"><font color="blue">编辑查看</font></a>]</font><a href="JavaScript:void(0)" onClick="manage_del_photos(<?= (int)$rows['photo_id']?>);"><font color="blue"><?php echo db_to_html('[删除]');?></font></a></td>
                                          </tr>


                                            <?php }?>
                                             </table></td>
                                            </tr>
                                            <tr>
                                              <td colspan="<?= $colspan?>"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                                          <tr>
                                        <td class="smallText" valign="top"><?php echo $companion_split->display_count($companion_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                                        <td class="smallText" align="right"><?php echo $companion_split->display_links($companion_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $_GET['page'],tep_get_all_get_params(array('page','y','x', 'action', 'SortSubmit','sort_order_array'))); ?>&nbsp;</td>
                                      </tr>
                                    </table></td>
                                      </tr>
                                              </table>
                  <?php }?>

		</fieldset>
		</td>
      </tr>
      </table></td>
   </tr>
</table>

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
