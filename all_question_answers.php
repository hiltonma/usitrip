<?php
require('includes/application_top.php');
require(DIR_FS_LANGUAGES . $language . '/all_question_answers.php');

$content = 'all_question_answers';

$breadcrumb->add(db_to_html('所有用户咨询'), tep_href_link('all_question_answers.php','','SSL'));

require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

require(DIR_FS_INCLUDES . 'application_bottom.php');
?>