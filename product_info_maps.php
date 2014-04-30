<?php
/*
  $Id: account.php,v 1.1.1.1 2004/03/04 23:37:52 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $maps_file = DIR_FS_CATALOG."products_swf_maps/".(int)$_GET['products_id'].".swf";
  $swf_file = "";
  if(file_exists($maps_file)){
  	$swf_file = "products_swf_maps/".(int)$_GET['products_id'].".swf";
  }
  
  $breadcrumb->add(db_to_html('╬╟╣Ц╣ьм╪'), tep_href_link('product_info_maps.php','products_id='.(int)$_GET['products_id']));
  $content = 'product_info_maps';
  //$javascript = $content . '.js';

  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');

?>