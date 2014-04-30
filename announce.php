<?php
/*
  $Id: articles.php, v1.0 2003/12/04 12:00:00 ra Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

// the following tPath references come from application_top.php
  $topic_depth = 'top';
/*
  if (isset($tPath) && tep_not_null($tPath)) {
    $topics_articles_sql = "select count(*) as total from " . TABLE_ARTICLES_TO_TOPICS . " where topics_id = '" . (int)$current_topic_id . "'"; echo 'sql:'.$topics_articles_sql;exit();
    $topics_articles_query = tep_db_query($topics_articles_sql);
    $topics_articles = tep_db_fetch_array($topics_articles_query);
    if ($topics_articles['total'] > 0) {
      $topic_depth = 'articles'; // display articles
    } else {
	  $topic_parent_sql = "select count(*) as total from " . TABLE_TOPICS . " where parent_id = '" . (int)$current_topic_id . "'"; echo 'sql2:'.$topic_parent_sql;exit();
      $topic_parent_query = tep_db_query($topic_parent_sql);
      $topic_parent = tep_db_fetch_array($topic_parent_query);
      if ($topic_parent['total'] > 0) {
        $topic_depth = 'nested'; // navigate through the topics
      } else {
        $topic_depth = 'articles'; // topic has no articles, but display the 'no articles' message
      }
    }
  }
*/
  
  //echo DIR_WS_LANGUAGES . $language . '/' . FILENAME_ARTICLES;
  //require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_ARTICLES);

  if ($topic_depth == 'top' && !(isset($HTTP_GET_VARS['authors_id'])) ) {
  	$breadcrumb->add(db_to_html('网站公告'), tep_href_link('announce.php'));
  }

  $content = 'announce';
  
$announce_id = (int)$_GET['id'];
if (0 == $announce_id)
{
	/*没有参数,获取公告列表*/
	$sql = 'SELECT a.articles_id,DATE_FORMAT(a.articles_date_added,\'%Y-%m-%d\') AS articles_date_added, b.articles_name FROM articles AS a,articles_description AS b WHERE a.articles_status=1 AND a.article_type=\'announce\' AND IFNULL(a.articles_date_available,now())<=now() AND a.articles_id=b.articles_id ORDER BY a.articles_date_added DESC';
	//echo $sql;
	$sql_query = tep_db_query($sql);
	$announce = false;
	while($row = tep_db_fetch_array($sql_query))
	{
		$announce[] = $row;
	}
	$the_title = db_to_html('usitrip走四方旅游网网站公告_美国当地华人旅行社');
}
else
{
	/*有参数,获取公告详情*/
	$sql = 'SELECT a.articles_head_title_tag,a.articles_head_desc_tag,a.articles_head_keywords_tag,a.articles_id,a.articles_name,a.articles_description,DATE_FORMAT(b.articles_date_added,\'%Y-%m-%d %H:%i:%s\') AS articles_date_added FROM articles_description AS a,articles AS b WHERE a.articles_id='.$announce_id.' AND a.articles_id=b.articles_id AND b.article_type=\'announce\' AND b.articles_status=1';
	$sql_query = tep_db_query($sql);
	$announce_detail = tep_db_fetch_array($sql_query);
	if(is_array($announce_detail))
	{
		$breadcrumb->add(db_to_html($announce_detail[articles_name]), tep_href_link('announce.php'));
		if ($announce_detail['articles_head_title_tag'] != '') {
			$the_title = db_to_html($announce_detail['articles_head_title_tag']);
		} else {
			$the_title = db_to_html($announce_detail['articles_name']);
		}
		if ($announce_detail['articles_head_keywords_tag'] != '') {
			$the_key_words = db_to_html($announce_detail['articles_head_keywords_tag']);
		} else {
			$the_key_words = '';
		}
		if ($announce_detail['articles_head_desc_tag'] != '') {
			$the_desc = db_to_html($announce_detail['articles_head_desc_tag']);
		} else {
			$the_desc= db_to_html(cutword(str_replace(strip_tags($announce_detail['articles_description']),'&nbsp;',''),200));
		}
		
	}

//! isset($the_title) || ! isset($the_key_words) || ! isset($the_desc)
}
  
  //echo '$content:'.$content;
  //echo '<br/>'.DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE;
  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
  

  require(DIR_FS_INCLUDES . 'application_bottom.php');
 ?> 