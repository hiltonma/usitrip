<?php
require('includes/application_top.php');

//submit comments
$error = false;
if($_POST['action']=='submit_comments'){
	$blog_id = (int)$_POST['blog_id'];
	$customers_id = (int)$_POST['customers_id'];
	$client_ip = get_client_ip();
	$blog_comments_date = date('Y-m-d H:i:s');
	$blog_comments_text = tep_db_prepare_input($_POST['blog_comments_text']);

	if(strlen(trim($blog_comments_text))<1){
		$error = true;
		$messageStack->add('submit_comments', db_to_html('请输入评论内容'));
	}
	if($error == false){
		$sql_data_array = array('blog_id' => $blog_id,
								'customers_id' => $customers_id,
								'client_ip' => $client_ip,
								'blog_comments_date' => $blog_comments_date,
								'blog_comments_text' => $blog_comments_text
								);
		$sql_data_array = html_to_db($sql_data_array);
		tep_db_perform('blog_comments', $sql_data_array);
		// update blog_comments sum
        $count_query = tep_db_query('select count(*) as total FROM blog_comments WHERE blog_id ="'.(int)$blog_id.'" ');
		$comments_total = tep_db_result($count_query,"0","total");
		tep_db_query('UPDATE blog SET blog_comments="'.(int)$comments_total.'" WHERE blog_id ="'.(int)$blog_id.'" ');

		$messageStack->add_session('submit_comments', db_to_html('评论添加成功！'),'success');
		tep_redirect(tep_href_link('user-space-logs-details.php','cser='.(int)$_GET['cser'].'&blog_id='.(int)$_GET['blog_id']));
	}
}

//先确定此空间是哪一个用户的$cser
//$cser = customer's id
if(!(int)$cser){ echo "Error not User!"; exit;}
if(!(int)$blog_id){ echo "Error not blog_id!"; exit;}

//rwite blog_clicks
tep_db_query('UPDATE blog set blog_clicks = blog_clicks+1 WHERE blog_id="'.(int)$blog_id.'" AND customers_id="'.(int)$cser.'" ');

//get sser name
$cser_sql = tep_db_query('SELECT c.customers_id,c.customers_firstname, u.user_nickname  FROM `customers` c ,`user` u WHERE c.customers_id=u.customers_id AND c.customers_id="'.(int)$cser.'" ');
$cser_row = tep_db_fetch_array($cser_sql);

//get user blog details
$blog_sql = tep_db_query('SELECT * FROM `blog` WHERE blog_id="'.(int)$blog_id.'" AND customers_id="'.(int)$cser.'" ');
$blog_row = tep_db_fetch_array($blog_sql);

$is_user_space = true;

$content = 'user-space-logs-details';
require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_FS_INCLUDES . 'application_bottom.php');
?>