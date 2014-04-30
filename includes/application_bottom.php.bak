<?php
/*
  $Id: application_bottom.php,v 1.1.1.1 2004/03/04 23:40:36 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// close session (store variables)
  tep_session_close();

  if (STORE_PAGE_PARSE_TIME == 'true') {
    $time_start = explode(' ', PAGE_PARSE_START_TIME);
    $time_end = explode(' ', microtime());
    $parse_time = number_format(($time_end[1] + $time_end[0] - ($time_start[1] + $time_start[0])), 3);
    error_log(strftime(STORE_PARSE_DATE_TIME_FORMAT) . ' - ' . getenv('REQUEST_URI') . ' (' . $parse_time . 's)' . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);

    if (DISPLAY_PAGE_PARSE_TIME == 'true') {
    	$info = MCache::stat();	$str ='';
    	foreach($info as $k=>$v) $str .= $k.':'.$v." &nbsp;";
      echo '<span style="color:#FF6600">Parse Time: ' . $parse_time .'s SQL Time:'. $_SQL_QUERY_TIME.'s  SQL:'.$_SQL_QUERY_NUM.',</span>';
      echo db_to_html('<div style="color:#FF6600">MCacheÄ£¿é :'.$str.'</div>');
    }
  }

  if ( (GZIP_COMPRESSION == 'true') && ($ext_zlib_loaded == true) && ($ini_zlib_output_compression < 1) ) {
    if ( (PHP_VERSION < '4.0.4') && (PHP_VERSION >= '4') ) {
      tep_gzip_output(GZIP_LEVEL);
    }
  }
?>