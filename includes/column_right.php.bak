<?php
/*
  $Id: column_right.php,v 1.1.1.1 2004/03/04 23:40:37 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  $column_query = tep_db_query('select display_in_column as cfgcol, infobox_file_name as cfgtitle, infobox_display as cfgvalue, infobox_define as cfgkey, box_heading, box_template, box_heading_font_color from ' . TABLE_INFOBOX_CONFIGURATION . ' where template_id = ' . TEMPLATE_ID . ' and infobox_display = "yes" and display_in_column = "right" order by location');
  while ($column = tep_db_fetch_array($column_query)) {

if ( file_exists(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes/' . $column['cfgtitle'])) {

define($column['cfgkey'],$column['box_heading']);
$infobox_define = $column['box_heading'];
$infobox_template = $column['box_template'];
$font_color = $column['box_heading_font_color'];
$infobox_class = $column['box_template'];
if ((USE_CACHE == 'true') && empty($SID) && ($column['cfgtitle'] == 'categories4.php') ) {
     echo tep_cache_categories_box4();
 } else if ((USE_CACHE == 'true') && empty($SID) && ($column['cfgtitle'] == 'manufacturers.php') ) {
     echo tep_cache_manufacturers_box();
 } else {
require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes/' . $column['cfgtitle']);
      }
   // end cache control code   
}else{
define($column['cfgkey'],$column['box_heading']);
$infobox_define = $column['box_heading'];
$infobox_template = $column['box_template'];
$font_color = $column['box_heading_font_color'];
$infobox_class = $column['box_template'];
require(DIR_FS_BOXES . $column['cfgtitle']);
}
}
?>
