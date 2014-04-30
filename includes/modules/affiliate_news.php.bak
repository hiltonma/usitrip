<?php
/*
  $Id: affiliate_news.php,v 2.00 2003/10/12

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

// return $affiliate_news
function getAffiliateNews($limit=""){
  $AffiliateNews = array();
  $_limit = "";
  if((int)$limit){ $_limit = " limit ".(int)$limit; }
  $affiliate_news_query = tep_db_query('SELECT * from ' . TABLE_AFFILIATE_NEWS . ' WHERE status = 1 ORDER BY date_added DESC '.$_limit);

  if (!tep_db_num_rows($affiliate_news_query)) { // there is no news
  	return false;
  } else {

    while ($affiliate_news = tep_db_fetch_array($affiliate_news_query)) {
	  $AffiliateNews[] = array('id'=>$affiliate_news['news_id'], 'title'=> $affiliate_news['headline'], 'date'=>$affiliate_news['date_added'], 'content'=> nl2br($affiliate_news['content']));
    }
  }
  return $AffiliateNews;
}
?>