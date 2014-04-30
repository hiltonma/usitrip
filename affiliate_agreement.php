<?php
/**
 * 合作联盟协议
 * @package 
 */
require('includes/application_top.php');

$cookie_day = (AFFILIATE_COOKIE_LIFETIME/3600/24);

$content = 'affiliate_agreement';
$breadcrumb->add(db_to_html('合作联盟协议'), 'affiliate_agreement.php');
require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_FS_INCLUDES . 'application_bottom.php');
?>