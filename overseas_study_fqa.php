<?php
/**
 * 海外游学FAQ
 * @package 
 * 判断如果某支付方式已经关闭就不再显示相关内容，西联汇款和上门付款除外
 */

require('includes/application_top.php');

//seo信息
$the_title = db_to_html('海外游学FAQ-走四方网');
$the_desc = db_to_html('　');
$the_key_words = db_to_html('　');
//seo信息 end


$add_div_footpage_obj = true;
$content = 'overseas_study_fqa';
$breadcrumb->add(db_to_html('海外游学FAQ'), tep_href_link('overseas_study_fqa.php'));

require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_FS_INCLUDES . 'application_bottom.php');
?>
