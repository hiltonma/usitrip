<?php
if($qurey_action!=true){

	//$entries_to_p_from = TABLE_ENTRIES.' e ,'.TABLE_CUSTOMERS.' c ';
	//$entries_to_p_where = " where c.customers_id = e.entries_customers_id AND e.entries_status = '5' ";
	$entries_to_p_from = TABLE_ENTRIES.' e  LEFT JOIN '.TABLE_CUSTOMERS.' c ON c.customers_id = e.entries_customers_id ';
	$entries_to_p_where = " where e.entries_status = '5' ";

	//分页类	
	$pageNum_String='page';
	$entries_to_p_maxRows = (4*5);
	if( preg_match('/^search/',$current_page) ){
		$entries_to_p_maxRows = (5*5);
		$entries_to_p_where .= $search_where;
	}
	
	$now_page=max(1,$_GET[$pageNum_String]);
	
	if(!preg_match('/\-p[0-9_]+/',basename($_SERVER['PHP_SELF'])) && !empty($_SERVER['QUERY_STRING'])){
		$page_split = new set_pagination($entries_to_p_from, $entries_to_p_where, $now_page, $pageNum_String, 'form_page','6','12px','1',$entries_to_p_maxRows );
	}else{
		$page_split = new set_pagination_for_rewrit($entries_to_p_from, $entries_to_p_where, $now_page, $pageNum_String, 'form_page','6','12px','1',$entries_to_p_maxRows );
	}
	
	$page_split_display = $page_split -> pagination();
	$entries_to_p_totalRows = $page_split -> totalRows;
	$entries_to_p_startRow = max(0,($page_split -> startRow));
	$entries_to_p_totalPages = $page_split -> totalPages;
	$entries_to_p_now_page = $page_split -> now_page;
//排序
	$order_by=" ORDER BY entries_add_date DESC , entries_id DESC ";
	if(tep_not_null($_GET['order_key'])){
		if($_GET['order_key']=='date-za'){ $order_by=" ORDER BY entries_add_date DESC , entries_id DESC ";}
		if($_GET['order_key']=='date-az'){ $order_by=" ORDER BY entries_add_date ASC , entries_id ASC ";}
		if($_GET['order_key']=='score-za'){ $order_by=" ORDER BY entries_score DESC , entries_id DESC ";}
		if($_GET['order_key']=='score-az'){ $order_by=" ORDER BY entries_score ASC , entries_id ASC ";}
		if($_GET['order_key']=='score_frequency-za'){ $order_by=" ORDER BY entries_score_frequency DESC , entries_id DESC ";}
		if($_GET['order_key']=='score_frequency-az'){ $order_by=" ORDER BY entries_score_frequency ASC , entries_id ASC ";}
	}
	if($entries_to_p_totalRows>0){
	//查询已发布成品的设计列表
		$LIMIT = " limit $entries_to_p_startRow , $entries_to_p_maxRows ";
		$entries_to_p_query = tep_db_query("select * from " . $entries_to_p_from . $entries_to_p_where . $order_by .$LIMIT);
		$entries_to_p = tep_db_fetch_array($entries_to_p_query);
	
	}	
 
}
?>