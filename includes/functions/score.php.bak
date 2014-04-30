<?php
//取得客户的用户号user_id
function get_user_id($customer_id){
	if(!(int)$customer_id){ return false; }
	$sql = tep_db_query('SELECT customers_id FROM `user` WHERE customers_id="'.(int)$customer_id.'" limit 1');
	$row = tep_db_fetch_array($sql);
	return (int)$row['customers_id'];
}
//取得客户的昵称
function get_user_nickname($customer_id){
	if(!(int)$customer_id){ return false; }
	$sql = tep_db_query('SELECT user_nickname FROM `user` WHERE customers_id="'.(int)$customer_id.'" limit 1');
	$row = tep_db_fetch_array($sql);
	if(tep_not_null($row['user_nickname'])){
		return $row['user_nickname'];
	}else{
		$sql = tep_db_query('SELECT customers_firstname FROM `customers` WHERE customers_id="'.(int)$customer_id.'" limit 1');
		$row = tep_db_fetch_array($sql);
		return $row['customers_firstname'];
	}
}
//取得客户的Face
function get_user_face($customer_id){
	if(!(int)$customer_id){ return false; }
	$sql = tep_db_query('SELECT user_face FROM `user` WHERE customers_id="'.(int)$customer_id.'" limit 1');
	$row = tep_db_fetch_array($sql);
	return $row['user_face'];
}

//取得客户的总积分
function get_user_score($customer_id){
	if(!(int)$customer_id){ return false; }
	$sql = tep_db_query('SELECT user_score_total FROM `user` WHERE customers_id="'.(int)$customer_id.'" limit 1');
	$row = tep_db_fetch_array($sql);
	return (int)$row['user_score_total'];
}
//增加或减少用户积分
function update_user_score($customer_id, $value, $description, $up_sum='true'){
	if(!(int)$customer_id){ return false; }
	
	$sql_data_array = array();
	$sql_data_array = array('customers_id' => (int)$customer_id,
							'user_score_history_value' => $value,
							'user_score_history_description' => $description,
							'user_score_history_date' => date('Y-m-d H:i:s'));
	tep_db_perform('user_score_history', $sql_data_array);
	if($up_sum=='true'){
		/*SUM customer score*/
		$sum_sql = tep_db_query(' SELECT SUM(`user_score_history_value`) as score_total FROM `user_score_history` WHERE customers_id="'.(int)$customer_id.'" ');
		$sum_row = tep_db_fetch_array($sum_sql);
		tep_db_query('UPDATE user SET user_score_total="'.max($sum_row['score_total'],0).'" WHERE customers_id="'.(int)$customer_id.'" ');
	}
}

//检测用户A和用户B是不是朋友
function check_friend($A_id,$B_id){
	if(!(int)$A_id || !(int)$B_id){ return false; }
	$sql = tep_db_query('SELECT f0_customers_id FROM `friends_list` WHERE (f0_customers_id="'.(int)$A_id.'" AND f1_customers_id="'.(int)$B_id.'") || (f1_customers_id="'.(int)$A_id.'" AND f0_customers_id="'.(int)$B_id.'") limit 1');
	$row = tep_db_fetch_array($sql);
	return (int)$row['f0_customers_id'];
}

//取得某个用户的全部好友或N个，结果为一个数组
function get_friends_list($customers_id,$N='all',$orderby=' f_date DESC '){
	if(!(int)$customers_id){ return false; }
	$limit = "";
	if((int)$N > 0){
		$limit = ' limit '.(int)$N;
	}
	$sql = tep_db_query('SELECT * FROM `friends_list` WHERE f0_customers_id="'.(int)$customers_id.'" || f1_customers_id="'.(int)$customers_id.'" order by '.$orderby.$limit);
	$array = array();
	while($rows = tep_db_fetch_array($sql)){
		if((int)$rows['f0_customers_id']!=(int)$customers_id){
			$array[] = (int)$rows['f0_customers_id'];
		}
		if((int)$rows['f1_customers_id']!=(int)$customers_id){
			$array[] = (int)$rows['f1_customers_id'];
		}
	}
	return $array;
}

//取得某个用户的相册列表，结果为一个数组
function get_user_photo_books_list($customers_id,$where_exc='', $orderby=' photo_books_date DESC ', $N='all'){
	if(!(int)$customers_id){ return false; }
	$limit = "";
	if((int)$N > 0){
		$limit = ' limit '.(int)$N;
	}
	if($where_exc!=''){
		$where_exc = ' AND '.$where_exc;
	}
	if($orderby!=''){
		$orderby = '  order by '.$orderby;
	}
	$sql = tep_db_query('SELECT * FROM `photo_books` WHERE customers_id="'.(int)$customers_id.'" '.$where_exc.$orderby.$limit);
	$array = array();
	while($rows = tep_db_fetch_array($sql)){
		$array[]= array('id'=>(int)$rows['photo_books_id'], 'name'=>$rows['photo_books_name'], 'description'=>$rows['photo_books_description'],'date'=>$rows['photo_books_date'], 'photo_sum'=>$rows['photo_sum'],
						'privacy_settings'=>$rows['photo_books_privacy_settings'],'cover'=>$rows['photo_books_cover']);
	}
	return $array;
}

//取得某个相册的相片总数
function get_photo_books_sum($photo_books_id){
	if(!(int)$photo_books_id){ return false; }
	$sql = tep_db_query('SELECT count(*) as total FROM `photo` WHERE photo_books_id="'.(int)$photo_books_id.'"');
	$total = tep_db_result($sql,"0","total");
	return $total;
}

//取得某个相册的相片list
function get_photo_list_for_photo_books($photo_books_id, $order_by =' photo_update DESC ', $limit=' limit 3 '){
	if(!(int)$photo_books_id){ return false; }
	$sql = tep_db_query('SELECT * FROM `photo` WHERE photo_books_id="'.(int)$photo_books_id.'"  order by '.$order_by.$limit);
	$array = array();
	while($rows = tep_db_fetch_array($sql)){
		$array[]= array('id'=>(int)$rows['photo_id'], 'name'=>$rows['photo_name'], 'tag'=>$rows['photo_tag'], 'update'=>$rows['photo_update'],
						'customers_id'=>$rows['customers_id']);
	}
	return $array;
}

?>