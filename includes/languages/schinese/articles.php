<?php
/*
  $Id: articles.php, v1.0 2003/12/04 12:00:00 ra Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('TEXT_MAIN', '');
define('TABLE_HEADING_NEW_ARTICLES', '有关于 %s 的讯息');

if ( ($topic_depth == 'articles') || (isset($HTTP_GET_VARS['authors_id'])) ) {
  define('HEADING_TITLE', $topics['topics_name']);
  define('TABLE_HEADING_ARTICLES', '文章');
  define('TABLE_HEADING_AUTHOR', '作者');
  define('TEXT_NO_ARTICLES', '目前无此主题之相关文章');
  define('TEXT_NO_ARTICLES2', '目前无此作者之相关文章');
  define('TEXT_NUMBER_OF_ARTICLES', '文章数量: ');
  define('TEXT_SHOW', '显示:');
  define('TEXT_NOW', '\' 目前');
  define('TEXT_ALL_TOPICS', '所有主题');
  define('TEXT_ALL_AUTHORS', '所有作者');
  define('TEXT_ARTICLES_BY', '文章由');
  define('TEXT_ARTICLES', '以下是最近新的讯息、文章');  
  define('TEXT_DATE_ADDED', '已出版:');
  define('TEXT_AUTHOR', '作者:');
  define('TEXT_TOPIC', '有关于:');
  define('TEXT_BY', '由');
  define('TEXT_READ_MORE', '详细内容...');
  define('TEXT_MORE_INFORMATION', '更多详细资料请参阅作者网站 <a href="http://%s" target="_blank">网页</a>.');
} elseif ($topic_depth == 'top') {
  define('HEADING_TITLE', '所有文章');
  define('TEXT_ALL_ARTICLES', '以下文章是最近新一期的文章');
  define('TEXT_CURRENT_ARTICLES', '目前文章');
  define('TEXT_UPCOMING_ARTICLES', '即将推出的文章');
  define('TEXT_NO_ARTICLES', '目前无文章');
  define('TEXT_DATE_ADDED', '已出版:');
  define('TEXT_DATE_EXPECTED', '预计推出:');
  define('TEXT_AUTHOR', '作者:');
  define('TEXT_TOPIC', '主题:');
  define('TEXT_BY', '由');
  define('TEXT_READ_MORE', '详细内容...');
} elseif ($topic_depth == 'nested') {
  define('HEADING_TITLE', '文章');
}

?>