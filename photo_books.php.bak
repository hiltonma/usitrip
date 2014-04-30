<?php
require_once('includes/application_top.php');

//取得所有相册
$all_books_sql = 'SELECT * FROM `photo_books` Order By photo_sum DESC, photo_books_id DESC ';
$all_books_split = new splitPageResults($all_books_sql, 15);
$all_books_query = tep_db_query($all_books_split->sql_query);
$all_photo_books = tep_db_fetch_array($all_books_query);

//取得最受关注的相册，即评论多的相册。
$hot_books_sql = 'SELECT * FROM `photo_books` Order By books_hot DESC, photo_books_id DESC Limit 5';
$hot_books_query = tep_db_query($hot_books_sql);
$hot_photo_books = tep_db_fetch_array($hot_books_query);

$content = "photo_books";
$javascript = $content . '.js.php';
$other_css_base_name = "new_travel_companion_index";
$javascript = 'new_travel_companion.js.php';

require_once(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require_once(DIR_FS_INCLUDES . 'application_bottom.php');
?>