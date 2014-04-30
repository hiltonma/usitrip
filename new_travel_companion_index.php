<?php
require_once('includes/application_top.php');
require(DIR_FS_LANGUAGES . $language . '/bbs_travel_companion.php');
function getPlace($str){
	if($str){
		$arr=explode(',', $str);
		$str_sql='select city from tour_city where city_id in('.$str.')';
		$sql_query=tep_db_query($str_sql);
		while($row=tep_db_fetch_array($sql_query)){
			$return.=$row['city'].'   ';
		}
		return $return;
	}else return '';
}
//如果有搜索行为是否包括目录的选项
$action_search_include = true;
//根据条件取得BBS列表信息 start
$where='';
$search_str_check = db_to_html('请输入关键字，进一步搜索');
if(tep_not_null($_GET['tc_keyword'])&&($_GET['tc_keyword']!=$search_str_check)){
	$where.= ' AND t_companion_title Like "%'.tep_db_input(trim(html_to_db($_GET['tc_keyword']))).'%" ';
	$action_search_include = false;
}
if(tep_not_null($_GET['search_date_1'])&&tep_not_null($_GET['search_date_2'])){
	//$where.= ' AND (hope_departure_date ="'.tep_db_input(trim(html_to_db($_GET['search_date']))).'" ';
	//if((int)$_GET['date_step']>0){
		//$search_date_num = strtotime($_GET['search_date']);
		//$search_date_num_add = $search_date_num + ((int)$_GET['date_step']*24*60*60);
		//$search_date_num_sub = $search_date_num - ((int)$_GET['date_step']*24*60*60);
		//$add_final_date = date('Y-m-d', $search_date_num_add);
		//$sub_final_date = date('Y-m-d', $search_date_num_sub);
		//$where.= ' || (hope_departure_date < "'.$add_final_date.'" AND hope_departure_date > "'.$sub_final_date.'") ';
	//}
	//$where.= ' ) ';
	//$action_search_include = false;
        $add_final_date = tep_db_input(trim(html_to_db($_GET['search_date_2'])));
        $add_start_date = tep_db_input(trim(html_to_db($_GET['search_date_1'])));
        $where.= 'AND (hope_departure_date <= "'.$add_final_date.'" AND hope_departure_date >= "'.$add_start_date.'"';
        $where.= ' ) ';
        $action_search_include = false;
}
if(tep_not_null($_GET['search_date_1'])&&!tep_not_null($_GET['search_date_2'])){
	//$add_final_date = tep_db_input(trim(html_to_db($_GET['search_date_2'])));
        $add_start_date = tep_db_input(trim(html_to_db($_GET['search_date_1'])));
        $where.= 'AND (hope_departure_date >="'.$add_start_date.'"';
        $where.= ' ) ';
        $action_search_include = false;

}
if(!tep_not_null($_GET['search_date_1'])&&tep_not_null($_GET['search_date_2'])){
	$add_final_date = tep_db_input(trim(html_to_db($_GET['search_date_2'])));
        //$add_start_date = tep_db_input(trim(html_to_db($_GET['search_date_1'])));
        $where.= 'AND (hope_departure_date <= "'.$add_final_date.'"';
        $where.= ' ) ';
        $action_search_include = false;

}

if((int)$_GET['m_products_id']){
	$m_products_id = (int)$_GET['m_products_id'];
	if($m_products_id=="1"){
		$where.= ' and t.products_id > 1 ';
	}else{
		$where.= ' and t.products_id = 0 ';
	}
}

if((int)$_GET['departure_city']){
	$departure_city = (int)$_GET['departure_city'];
	$where.= " and FIND_IN_SET('".$departure_city."',p.departure_city_id) ";
}

//取得当前目录的所有下级目录
// 在application_top.php中865行 定义 
if($Tccurrent_category_id>0 && $action_search_include == true){
	$cate_string = $Tccurrent_category_id;
	$child_categories_array=array();
	$child_categories_array = tep_get_categories('', $Tccurrent_category_id);
	for($i=0; $i<count($child_categories_array); $i++ ){
		$cate_string.= ','.$child_categories_array[$i]['id'];
	}
	
	//echo $cate_string;
	$where.= ' AND categories_id in('.$cate_string.') ';
}

//搜索某人的所有帖子
if((int)$_GET['customers_id']){
	$customers_id = (int)$_GET['customers_id'];
	$where .= ' AND t.customers_id ="'.(int)$_GET['customers_id'].'" ';
}
//搜索某产品的所有帖子
if((int)$_GET['products_id']){
	$products_id = (int)$_GET['products_id'];
	$where .= ' AND t.products_id ="'.(int)$_GET['products_id'].'" ';
}

//搜索寻女伴(发起人为女)
if((int)$_GET['seeking_women']){
	$seeking_women = (int)$_GET['seeking_women'];
	//$where .= ' AND t.hope_people_woman > 0 ';
	$where .= ' AND t.t_gender = "2" ';
}

//搜索寻男伴(发起人为男)
if((int)$_GET['seeking_man']){
	$seeking_man = (int)$_GET['seeking_man'];
	//$where .= ' AND t.hope_people_man > 0 ';
	$where .= ' AND t.t_gender = "1" ';
}
if((int)$_GET['type']){
	$_type = (int)$_GET['type'];
	//$where .= ' AND t.hope_people_man > 0 ';
	$where .= ' AND t._type ='.$_type;
}
//搜索未完成结伴帖
if((int)$_GET['undone']){
	$undone = (int)$_GET['undone'];
	//$where .= ' AND t.has_expired != "1" AND (t.hope_people_man > 0 || t.hope_people_woman > 0 ) ';
	$where .= ' AND t.has_expired != "1" ';
}

//搜索已完成结伴帖
if((int)$_GET['done']){
	$done = (int)$_GET['done'];
	$where .= ' AND t.has_expired = "1" ';
}


$order_by = ' add_time ';
$a_d = ' DESC ';

$iclass_departure = "favicon";
$iclass_sendtime = "favicon";
//发布时间，出行时间排序修改  tom modify
switch($_GET['sort_name']){
	case 'departure': $order_by = ' hope_departure_date '; if($_GET['sort_by_d']=='DESC'){ $iclass_departure = "delicon"; $iclass_sendtime = "favicon";}else{$iclass_departure = "delicon_asc"; $iclass_sendtime = "favicon";}
	break;
	case 'sendtime': $order_by = ' add_time '; if($_GET['sort_by_s']=='DESC'){$iclass_departure = "favicon"; $iclass_sendtime = "delicon";}else{$iclass_departure = "favicon"; $iclass_sendtime = "delicon_asc";}
	break;
}

if(tep_not_null($_GET['sort_by_d'])||tep_not_null($_GET['sort_by_s'])){
    if(tep_not_null($_GET['sort_by_d'])&&!tep_not_null($_GET['sort_by_s'])){
        if((int)$_GET['departure_city']){
            $sql_str = 'SELECT t.* FROM travel_companion t, products p WHERE t.status="1" and p.products_id = t.products_id '.$where.' Group By t.t_companion_id ORDER BY t.bbs_type ,'.$order_by.' '.$_GET['sort_by_d'].' ';
        }else{
            $sql_str = 'SELECT * FROM travel_companion t WHERE t.status="1" '.$where.' Group By t.t_companion_id ORDER BY t.bbs_type ,'.$order_by.' '.$_GET['sort_by_d'].' ';
        }
    }
    if(!tep_not_null($_GET['sort_by_d'])&&tep_not_null($_GET['sort_by_s'])){
        if((int)$_GET['departure_city']){
            $sql_str = 'SELECT t.* FROM travel_companion t, products p WHERE t.status="1" and p.products_id = t.products_id '.$where.' Group By t.t_companion_id ORDER BY t.bbs_type ,'.$order_by.' '.$_GET['sort_by_s'].' ';
        }else{
            $sql_str = 'SELECT * FROM travel_companion t WHERE t.status="1" '.$where.' Group By t.t_companion_id ORDER BY t.bbs_type ,'.$order_by.' '.$_GET['sort_by_s'].' ';
        }
    }
}else{
    if((int)$_GET['departure_city']){
            $sql_str = 'SELECT t.* FROM travel_companion t, products p WHERE t.status="1" and p.products_id = t.products_id '.$where.' Group By t.t_companion_id ORDER BY t.bbs_type ,'.$order_by.' '.$a_d.' ';
    }else{
            $sql_str = 'SELECT * FROM travel_companion t WHERE t.status="1" '.$where.' Group By t.t_companion_id ORDER BY t.bbs_type ,'.$order_by.' '.$a_d.' ';
    }
}

//SELECT * FROM travel_companion t WHERE t.status="1"  AND categories_id in(55)  AND t.products_id ="1712"  Group By t.t_companion_id ORDER BY t.bbs_type , add_time   DESC 
//echo $sql_str;
$travel_split = new splitPageResults($sql_str, 20);
$rows_count = $travel_split->number_of_rows;	//记录总数

$rows_count_html_code = $travel_split->display_count(TEXT_DISPLAY_NUMBER_OF_REVIEWS);	//记录总数的信息页面
$page_type = 1; //翻页的显示方式
$rows_page_links_code = $travel_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info','utm_source','utm_medium','utm_term','utm_campaign')),$page_type); //翻页连接代码
$rows_count_pages = $travel_split->number_of_pages; //总页数


$travel_query = tep_db_query($travel_split->sql_query);
$dates=array();
while ($travel = tep_db_fetch_array($travel_query)) {
	$dates[] = array('id'=>$travel['t_companion_id'], 
					 'title'=>$travel['t_companion_title'],
					 'content'=>$travel['t_companion_content'],
					 'name'=>$travel['customers_name'],
					 'customers_id'=>$travel['customers_id'],
					 'gender'=>$travel['t_gender'],
					 'reply'=>$travel['reply_num'],
					 'click'=>$travel['click_num'],
					 'products_id'=>$travel['products_id'],
					 'categories_id'=>$travel['categories_id'],
					 'type'=>$travel['bbs_type'],
					 'add_time'=>$travel['add_time'],
					 'time'=>$travel['last_time'],
					 'admin_id'=>$travel['admin_id'],
					 'hope_man'=>(int)$travel['hope_people_man'],
					 'hope_woman'=>(int)$travel['hope_people_woman'],
					 'hope_departure_date'=>$travel['hope_departure_date'],
					 '_type'=>$travel['_type'],
					'end_place'=>getPlace($travel['end_place']),
					'plan'=>$travel['travel_plan']
					 );
}
// print_r($dates);
//根据条件取得BBS列表信息 end

//Writing view history start
if((int)$rows_count_pages && count($TcPath_array) && tep_not_null($TcPath)){
	$tmp_var = false;
	$i = (int)count($_COOKIE['view_history_bbs']);
	for($j=0; $j<$i; $j++){
		if($_COOKIE['view_history_bbs'][$j]['TcPath'] == $TcPath){
			setcookie('view_history_bbs['.$j.'][TcPath]', $TcPath, time() +(3600*24*30*365));
			setcookie('view_history_bbs['.$j.'][date_time]', date('YmdHis'), time() +(3600*24*30*365));
			$tmp_var = true;
		}
	}
	
	if($tmp_var == false){
		setcookie('view_history_bbs['.$i.'][TcPath]', $TcPath, time() +(3600*24*30*365));
		setcookie('view_history_bbs['.$i.'][date_time]', date('YmdHis'), time() +(3600*24*30*365));
	}
}
//Writing view history end

	$output_title = db_to_html('美国自助游结伴_结伴同游美国_结伴去美国_Usitrip走四方旅游网');
	$output_desc = db_to_html('美国当地华人旅行社-走四方旅游网独家提供结伴同行自助游信息,为游客搭建一个去美国旅游拼房,结伴去加拿大,相约去欧洲旅行交友的平台.');
	$output_key_words = db_to_html('美国结伴同游,结伴出境游,美国结伴自由行,美国旅游结伴拼房');
	
	for ($jib=0, $jnb=sizeof($breadcrumb->_trail); $jib<$jnb; $jib++) {
			if(($jnb-1) != $jib){							
				//$output_title .= trim($breadcrumb->_trail[$jib]['title']) . '  &gt;  ';	
				//$output_desc .=	trim($breadcrumb->_trail[$jib]['title']). ',';	
				//$output_key_words .= trim($breadcrumb->_trail[$jib]['title']). ',';	
			}elseif($jib > 0){
				//结伴同旅游详细页面的seo标签
				$output_title = trim($breadcrumb->_trail[$jib]['title']).db_to_html('结伴同游网-').trim($breadcrumb->_trail[$jib]['title']).db_to_html('-走四方网');
				$output_key_words = trim($breadcrumb->_trail[$jib]['title']).db_to_html('结伴同游');	
				$output_desc =	trim($breadcrumb->_trail[$jib]['title']).db_to_html('结伴同游网,结伴同游').trim($breadcrumb->_trail[$jib]['title']).db_to_html(',上走四方结伴同游网发布结伴同游帖,寻找游伴,拼房节省费用吧');	
			}
	}

	$seo_sql = tep_db_query('SELECT * FROM travel_companion_seo_tags WHERE categories_id="'.$Tccurrent_category_id.'" LIMIT 1');
	$seo_row = tep_db_fetch_array($seo_sql);
	if(tep_not_null($seo_row['meta_title']) && tep_not_null($seo_row['meta_keywords']) && tep_not_null($seo_row['meta_description']) ){
		$output_title = db_to_html(tep_db_output($seo_row['meta_title']));
		$output_desc = db_to_html(tep_db_output($seo_row['meta_description']));
		$output_key_words = db_to_html(tep_db_output($seo_row['meta_keywords']));
		
	}
	//seo信息
	$the_title = $output_title;
	$the_desc = $output_desc;
	$the_key_words = $output_key_words;
	//seo信息 end

$is_travel_companion_bbs = true;
$Show_Calendar_JS = "true";
$javascript = 'new_travel_companion.js.php';
$content = 'new_travel_companion_index';

$breadcrumb->add(db_to_html('结伴同游'), tep_href_link('new_travel_companion_index.php'));

//require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/main_page_for_bbs.tpl.php');
require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require_once(DIR_FS_INCLUDES . 'application_bottom.php');
?>