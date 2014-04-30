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
  else
  {
  	//echo 'aaaaaaaa';
  }
  
  //echo DIR_WS_LANGUAGES . $language . '/' . FILENAME_ARTICLES;
  require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_ARTICLES);

  if ($topic_depth == 'top' && !(isset($HTTP_GET_VARS['authors_id'])) ) {
    $breadcrumb->add(NAVBAR_TITLE_DEFAULT, tep_href_link(FILENAME_ARTICLES));
  }

  $content = CONTENT_ARTICLES;
  //echo '$content:'.$content;
  //echo '<br/>'.DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE;
  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php'); 
 ?>