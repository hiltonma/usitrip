<?php
 	$entries_from = TABLE_ENTRIES ;
	$entries_where = " where entries_status > 2 AND entries_hidden='false' ";

//分页类	
	$pageNum_String='page';
	$now_page=max(1,$_GET[$pageNum_String]);
	$entries_maxRows = (4*6);
	if( preg_match('/^search/',$current_page) ){
		$entries_maxRows = (5*7);
		$entries_where .= $search_where;
	}
	
	if(!preg_match('/\-p[0-9_]+/',basename($_SERVER['PHP_SELF'])) && !empty($_SERVER['QUERY_STRING'])){
		$page_split = new set_pagination($entries_from, $entries_where, $now_page, $pageNum_String, 'form_page','6','12px','1',$entries_maxRows );
	}else{
		$page_split = new set_pagination_for_rewrit($entries_from, $entries_where, $now_page, $pageNum_String, 'form_page','6','12px','1',$entries_maxRows );
	}
	
	$page_split_display = $page_split -> pagination();
	$entries_totalRows = $page_split -> totalRows;
	$entries_startRow = max(0,($page_split -> startRow));
	$entries_totalPages = $page_split -> totalPages;
	$entries_now_page = $page_split -> now_page;
//排序
	$order_by=" ORDER BY entries_add_date DESC , entries_id DESC ";
	if(tep_not_null($_GET['order_key'])){
		if($_GET['order_key']=='date-za'){ $order_by=" ORDER BY entries_add_date DESC , entries_id DESC ";}
		if($_GET['order_key']=='date-az'){ $order_by=" ORDER BY entries_add_date ASC , entries_id ASC ";}
		if($_GET['order_key']=='score-za'){ $order_by=" ORDER BY entries_score DESC , entries_id DESC ";}
		if($_GET['order_key']=='score-az'){ $order_by=" ORDER BY entries_score ASC , entries_id ASC ";}
		if($_GET['order_key']=='clicks-za'){ $order_by=" ORDER BY entries_clicks DESC , entries_id DESC ";}
		if($_GET['order_key']=='clicks-az'){ $order_by=" ORDER BY entries_clicks ASC , entries_id ASC ";}
		if($_GET['order_key']=='message_number-za'){ $order_by=" ORDER BY entries_message_number DESC , entries_id DESC ";}
		if($_GET['order_key']=='message_number-az'){ $order_by=" ORDER BY entries_message_number ASC , entries_id ASC ";}
		if($_GET['order_key']=='score_frequency-za'){ $order_by=" ORDER BY entries_score_frequency DESC , entries_id DESC ";}
		if($_GET['order_key']=='score_frequency-az'){ $order_by=" ORDER BY entries_score_frequency ASC , entries_id ASC ";}
	}
	if($entries_totalRows>0){
	//查询设计列表
		$LIMIT = " limit $entries_startRow , $entries_maxRows ";
		$entries_query = tep_db_query("select * from " . $entries_from . $entries_where . $order_by .$LIMIT);
		$entries_rows = tep_db_fetch_array($entries_query);
	
	}	
?>