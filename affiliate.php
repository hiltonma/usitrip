<?php
/*
  $Id: links.php,v 1.2 2004/03/12 19:28:57 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');

require(DIR_FS_CLASSES . 'affiliate.php');
$affiliate = new affiliate;

$error = false;

//公告信息{
include(DIR_FS_MODULES . FILENAME_AFFILIATE_NEWS);
$AffiliateNews = getAffiliateNews(5);
//公告信息}


//seo信息
$the_title = db_to_html('合作联盟-走四方网');
$the_desc = db_to_html('　');
$the_key_words = db_to_html('　');
//seo信息 end

$breadcrumb->add(db_to_html('合作联盟'), tep_href_link('affiliate.php'));

$add_div_footpage_obj = true;
$close53kf = true;
$content = 'affiliate';
$other_css_base_name = 'mytours';

require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

require(DIR_FS_INCLUDES . 'application_bottom.php');
?>
