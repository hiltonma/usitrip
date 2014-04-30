<?php 
require('includes/application_top.php');
require(DIR_FS_LANGUAGES . $language . '/vote_system_list.php');

$vote_sql = tep_db_query('SELECT * FROM `vote_system` WHERE v_s_state ="1" order by v_s_sort asc ');

$BreadOff = true;
$content = 'vote_system_list';

require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_FS_INCLUDES . 'application_bottom.php');

?>