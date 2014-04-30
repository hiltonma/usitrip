<?php
/*
  $Id: articles.php, v1.0 2003/12/04 12:00:00 ra Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('TEXT_MAIN', '');
define('TABLE_HEADING_NEW_ARTICLES', '有關於 %s 的訊息');

if ( ($topic_depth == 'articles') || (isset($HTTP_GET_VARS['authors_id'])) ) {
  define('HEADING_TITLE', $topics['topics_name']);
  define('TABLE_HEADING_ARTICLES', '文章');
  define('TABLE_HEADING_AUTHOR', '作者');
  define('TEXT_NO_ARTICLES', '目前無此主題之相關文章');
  define('TEXT_NO_ARTICLES2', '目前無此作者之相關文章');
  define('TEXT_NUMBER_OF_ARTICLES', '文章數量: ');
  define('TEXT_SHOW', '顯示:');
  define('TEXT_NOW', '\' 目前');
  define('TEXT_ALL_TOPICS', '所有主題');
  define('TEXT_ALL_AUTHORS', '所有作者');
  define('TEXT_ARTICLES_BY', '文章由');
  define('TEXT_ARTICLES', '以下是最近新的訊息、文章');  
  define('TEXT_DATE_ADDED', '已出版:');
  define('TEXT_AUTHOR', '作者:');
  define('TEXT_TOPIC', '有關於:');
  define('TEXT_BY', '由');
  define('TEXT_READ_MORE', '詳細內容...');
  define('TEXT_MORE_INFORMATION', '更多詳細資料請參閱作者網站 <a href="http://%s" target="_blank">網頁</a>.');
} elseif ($topic_depth == 'top') {
  define('HEADING_TITLE', '所有文章');
  define('TEXT_ALL_ARTICLES', '以下文章是最近新一期的文章');
  define('TEXT_CURRENT_ARTICLES', '目前文章');
  define('TEXT_UPCOMING_ARTICLES', '即將推出的文章');
  define('TEXT_NO_ARTICLES', '目前無文章');
  define('TEXT_DATE_ADDED', '已出版:');
  define('TEXT_DATE_EXPECTED', '預計推出:');
  define('TEXT_AUTHOR', '作者:');
  define('TEXT_TOPIC', '主題:');
  define('TEXT_BY', '由');
  define('TEXT_READ_MORE', '詳細內容...');
} elseif ($topic_depth == 'nested') {
  define('HEADING_TITLE', '文章');
}

?>