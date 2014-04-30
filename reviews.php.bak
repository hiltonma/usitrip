<?php
/*
  $Id: reviews.php,v 1.1.1.1 2004/03/04 23:38:02 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_REVIEWS);

  $breadcrumb->add(db_to_html('用户点评'), tep_href_link(FILENAME_REVIEWS));

  $reviews_query_raw = "select r.rating_total, r.rating_0, r.rating_1, r.rating_2, r.rating_3, r.rating_4, r.rating_5, r.reviews_id, rd.reviews_text as reviews_text, rd.reviews_title, r.reviews_rating, r.date_added, p.products_id, pd.products_name, p.products_image, r.customers_name from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = r.products_id and r.reviews_id = rd.reviews_id and r.reviews_status='1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and rd.languages_id = '" . (int)$languages_id . "' and r.parent_reviews_id=0 order by r.date_added DESC";
  $reviews_split = new splitPageResults($reviews_query_raw, MAX_DISPLAY_NEW_REVIEWS);


	//seo信息
	$the_title = db_to_html('旅游评论-走四方旅游网');
	$the_desc = db_to_html('　');
	$the_key_words = db_to_html('　');
	//seo信息 end

  $content = CONTENT_REVIEWS;

  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');
?>
