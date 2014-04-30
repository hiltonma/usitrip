<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require('includes/application_top_domestic.php');
/*
if (!session_is_registered('admin_id'))
{
	tep_redirect(tep_href_link(DIR_WS_DOMESTIC.FILENAME_DIR_WS_DOMESTIC_LOGIN, '', 'SSL'));
}*/
require(DIR_WS_INCLUDES . FILENAME_DOMESTIC_HEADER);
?>
<?php require(DIR_WS_INCLUDES . FILENAME_DOMESTIC_FOOTER);?>
