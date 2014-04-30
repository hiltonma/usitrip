<?php
/*
  $Id: all_prods.php,v 3.0 2004/02/21 by Ingo (info@gamephisto.de)

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce
  Copyright (c) 2002 HMCservices

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  include(DIR_FS_LANGUAGES . $language . '/' . FILENAME_ALLPRODS);


// Set number of columns in listing
define ('NR_COLUMNS', 1);
//
  $breadcrumb->add(HEADING_TITLE, tep_href_link(FILENAME_ALLPRODS, '', 'NONSSL'));
   
  if($cId !=''){
	$linkcroot = array();
	tep_get_parent_categories($linkcroot, $cId);
	$linkcroot = array_reverse($linkcroot);
	//$cRootsize = sizeof($linkcroot);

	foreach($linkcroot as $lkey => $lval)
		{
		$breadcrumb->add(db_to_html(tep_get_category_name($lval)));
		/*
		echo "size".(int)$cRootsize."</br>";
		echo "$lkey => $lval"."</br>";
		 if((int)$cRootsize < sizeof($linkcroot)){
		  $breadcrumb->add(tep_get_category_name($lval), FILENAME_LINKS . '?cRoot=' . $lval);
		 }else{
		 $breadcrumb->add(tep_get_category_name($lval), FILENAME_LINKS . '?lPath=' . $lval);		 
		 }
		 $cRootsize=$cRootsize -1;
		*/
		}
		$breadcrumb->add(db_to_html(tep_get_category_name($cId)));		
	}

	//seo信息
	$the_title = db_to_html('所有景点导航-走四方旅游网');
	$the_desc = db_to_html('在这里您可以分类查看浏览到走四方网提供的世界各地旅游景点的汇总,有美国旅游景点,加拿大旅游景点,欧洲旅游景点和亚洲旅游景点和更多服务');
	$the_key_words = db_to_html('美国旅游景点,欧洲旅游景点,亚洲旅游景点');
	//seo信息 end

  $content = CONTENT_ALL_PRODS;

  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');
?>
