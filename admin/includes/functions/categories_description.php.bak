<?php
  //---------------------------------------------------------------------------//
  //
  //	Code:	categories_description
  //	Author:	Brian Lowe <blowe@wpcusrgrp.org>
  //	Date:	June 2002
  //
  //	Contains code snippets for the categories_description contribution to
  //	osCommerce.
  //---------------------------------------------------------------------------//
  //	Code:	categories_description MS2 1.5
  //	Editor:	Lord Illicious <shaolin-venoms@illicious.net>
  //	Date:	July 2003
  //
  //---------------------------------------------------------------------------//

  //---------------------------------------------------------------------------//
  //	Get a category heading_title or description
  // These should probably be in admin/includes/functions/general.php, but since
  // this is a contribution and not part of the base code, they are here instead
  //edit by vincent - 2011-4-22 以下函数全部从缓存中读取
  //---------------------------------------------------------------------------//
  
  
     function tep_get_categories_logo_alt_tag($category_id, $language_id) {
     	//$category = MCache::fetch_categories($categories_id);
		$category_query = tep_db_query("select categories_logo_alt_tag from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $category_id . "' and language_id = '" . $language_id . "'");
		$category = tep_db_fetch_array($category_query);
		return $category['categories_logo_alt_tag'];
  }
  
  
    function tep_get_categories_first_sentence($category_id, $language_id) {
    //$category = MCache::fetch_categories($categories_id);
    $category_query = tep_db_query("select categories_first_sentence from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $category_id . "' and language_id = '" . $language_id . "'");
    $category = tep_db_fetch_array($category_query);
    return $category['categories_first_sentence'];
  }
  
  function tep_get_categories_seo_description($category_id, $language_id) {
    $category_query = tep_db_query("select categories_seo_description from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $category_id . "' and language_id = '" . $language_id . "'");
    $category = tep_db_fetch_array($category_query);
    //$category = MCache::fetch_categories($categories_id);
    return $category['categories_seo_description'];
  }
  
  function tep_get_category_heading_title($category_id, $language_id) {
  $category_query = tep_db_query("select categories_heading_title from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $category_id . "' and language_id = '" . $language_id . "'");
    $category = tep_db_fetch_array($category_query);
   //$category = MCache::fetch_categories($categories_id);
    return $category['categories_heading_title'];
  }

  function tep_get_category_description($category_id, $language_id) {
    $category_query = tep_db_query("select categories_description from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $category_id . "' and language_id = '" . $language_id . "'");
    $category = tep_db_fetch_array($category_query);
   //$category = MCache::fetch_categories($categories_id);
    return $category['categories_description'];
  }
  
   function tep_get_categories_video_description($category_id, $language_id) {
    $category_query = tep_db_query("select categories_video_description from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $category_id . "' and language_id = '" . $language_id . "'");
    $category = tep_db_fetch_array($category_query);
   //$category = MCache::fetch_categories($categories_id);
    return $category['categories_video_description'];
  }
  
  
  //amit added for recommonded tour
  function tep_get_categories_recommended_tours($category_id, $language_id) {
    $category_query = tep_db_query("select categories_recommended_tours from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $category_id . "' and language_id = '" . $language_id . "'");
    $category = tep_db_fetch_array($category_query);
   //$category = MCache::fetch_categories($categories_id);
    return $category['categories_recommended_tours'];
  }
  
  function tep_get_categories_map($category_id, $language_id) {
	$category_query = tep_db_query("select categories_map from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $category_id . "' and language_id = '" . $language_id . "'");
    $category = tep_db_fetch_array($category_query);
    //$category = MCache::fetch_categories($categories_id);
    return $category['categories_map'];
  }

  
 function tep_get_category_head_title_tag($category_id, $language_id) {
    $category_query = tep_db_query("select categories_head_title_tag from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $category_id . "' and language_id = '" . $language_id . "'");
    $category = tep_db_fetch_array($category_query);
    //$category = MCache::fetch_categories($categories_id);
    return $category['categories_head_title_tag'];
  }
   function tep_get_category_head_desc_tag($category_id, $language_id) {
      $category_query = tep_db_query("select categories_head_desc_tag from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $category_id . "' and language_id = '" . $language_id . "'");
      $category = tep_db_fetch_array($category_query);
     //$category = MCache::fetch_categories($categories_id);
      return $category['categories_head_desc_tag'];
  }
   function tep_get_category_head_keywords_tag($category_id, $language_id) {
      $category_query = tep_db_query("select categories_head_keywords_tag from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $category_id . "' and language_id = '" . $language_id . "'");
     $category = tep_db_fetch_array($category_query);
     //$category = MCache::fetch_categories($categories_id);
      return $category['categories_head_keywords_tag'];
  }
?>
