<?php
require('includes/application_top.php');
//require(DIR_FS_LANGUAGES . $language . '/question_detail.php');

$content = 'question_detail';
$breadcrumb->add(db_to_html('提问详细'), tep_href_link('question_detail.php','','SSL'));

$str_sql = '
		SELECT
		q.*,
		p.products_id,
		pd.products_name,
		p.products_image,
		p.departure_city_id
		FROM `tour_question` q ,
		`products` p,
		`products_description` pd
		WHERE q.que_id=' . ( int ) $_GET ['question_id'] . '
		and p.products_id = pd.products_id
		and q.products_id=p.products_id';
$head_img = "touxiang_no-sex.gif";
$head_img = 'image/' . $head_img;
$question_query = tep_db_query ( $str_sql );
$question = tep_db_fetch_array ( $question_query );
// 提问

// 回答
$answers = vin_db_fetch_all ( 'SELECT * FROM `tour_question_answer` WHERE  que_id ="' . ( int ) $_GET ['question_id'] . '" ORDER BY  date DESC ', 100 );
// 回答

// 推荐路线
$cityid = array_pop ( explode ( ',', $question ['departure_city_id'] ) );
if (tep_not_null ( $cityid )) {
	$product_list_sql = '
			SELECT
			p.products_id ,
			p.products_price,
			p.products_tax_class_id,
			pd.products_name as name,
			p.products_price as price ,
			p.products_urlname as url
			FROM ' . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . ' pd
					WHERE pd.products_id = p.products_id
					AND p.products_id <>"' . ( int ) $question ['products_id'] . '"
					AND p.products_status=1
					AND pd.language_id = \'' . ( int ) $languages_id . '\'
					AND  p.departure_city_id IN (  ' . $cityid . ') Limit 6 ';
	$products_list = vin_db_fetch_all ( $product_list_sql );
}

// 推荐路线

// 相同线路下其它咨询
$str_sql = 'select * from tour_question where products_id=' . ( int ) $question ['products_id'] . ' and que_id<>' . ( int ) $_GET ['question_id'] . ' order by date DESC limit 6';
$the_same_way = vin_db_fetch_all ( $str_sql );
// 相同线路下其它咨询

// 最新咨询
$str_sql = 'select * from tour_question where  que_id<>' . ( int ) $_GET ['question_id'] . ' order by date DESC limit 6';
$the_new_way = vin_db_fetch_all ( $str_sql );
// 最新资讯

//热门搜索
//自定义的热门搜索

$hit_key_string = '';

$tmp_key = '黄石公园,洛杉矶,纽约,拉斯维加斯,美东,美西,夏威夷,加拿大';
$tmp_keys = explode(',',$tmp_key);
$j=count($tmp_keys);
for($i=0; $i<$j; $i++){
	$hit_key_string .= '<a href="'.tep_href_link('all_question_answers.php','keyword='.($tmp_keys[$i])).'">'.($tmp_keys[$i]).'</a>';
}
$key_sql = tep_db_query('SELECT *, count(key_name) as total FROM `tour_question_keywords` WHERE 1 Group By key_name Order By total DESC Limit 0');
while($key_rows = tep_db_fetch_array($key_sql)){
	$hit_key_string .= '<a href="'.tep_href_link('all_question_answers.php','keyword='.tep_db_output($key_rows['key_name'])).'">'.tep_db_output($key_rows['key_name']).'</a>';
}
//热门搜索
// print_r($the_new_way);
// echo '<br />';
// print_r($the_same_way);
// echo '<br />';
// print_r($products_list);
// echo '<br />';

require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

require(DIR_FS_INCLUDES . 'application_bottom.php');
?>