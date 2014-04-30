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
  //---------------------------------------------------------------------------//
  function tep_get_region_heading_title($regions_id, $language_id) {
    $region_query = tep_db_query("select regions_heading_title from " . TABLE_REGIONS_DESCRIPTION . " where regions_id = '" . $regions_id . "' and language_id = '" . $language_id . "'");
    $region = tep_db_fetch_array($region_query);
    return $region['regions_heading_title'];
  }

  function tep_get_region_description($regions_id, $language_id) {
    $region_query = tep_db_query("select regions_description from " . TABLE_REGIONS_DESCRIPTION . " where regions_id = '" . $regions_id . "' and language_id = '" . $language_id . "'");
    $region = tep_db_fetch_array($region_query);
    return $region['regions_description'];
  }
 function tep_get_region_head_title_tag($regions_id, $language_id) {
    $region_query = tep_db_query("select regions_head_title_tag from " . TABLE_REGIONS_DESCRIPTION . " where regions_id = '" . $regions_id . "' and language_id = '" . $language_id . "'");
    $region = tep_db_fetch_array($region_query);
    return $region['regions_head_title_tag'];
  }
   function tep_get_region_head_desc_tag($regions_id, $language_id) {
      $region_query = tep_db_query("select regions_head_desc_tag from " . TABLE_REGIONS_DESCRIPTION . " where regions_id = '" . $regions_id . "' and language_id = '" . $language_id . "'");
      $region = tep_db_fetch_array($region_query);
      return $region['regions_head_desc_tag'];
  }
   function tep_get_region_head_keywords_tag($regions_id, $language_id) {
      $region_query = tep_db_query("select regions_head_keywords_tag from " . TABLE_REGIONS_DESCRIPTION . " where regions_id = '" . $regions_id . "' and language_id = '" . $language_id . "'");
      $region = tep_db_fetch_array($region_query);
      return $region['regions_head_keywords_tag'];
  }
  
?>
