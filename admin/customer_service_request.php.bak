<?php
require('includes/application_top.php');
$action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : 'list');
if(!defined('VIN_TMP_PATH'))define('VIN_TMP_PATH' , realpath('./templates').DIRECTORY_SEPARATOR); //Ä£°åÂ·¾¶
$error = false;
$processed = false;
 if($action  == 'delete'){
	tep_db_query("DELETE FROM ".TABLE_CUSTOMERS_SERVICE_REQUEST.' WHERE  customers_transfer_request_id = '.intval($_GET['customers_transfer_request_id']));
	$messageStack->add('Delete Record  #'.$_GET['customers_transfer_request_id'].' Success', 'success');
}


$content = 'customer_service_request_list.php';
$query_raw = "SELECT * FROM ".TABLE_CUSTOMERS_SERVICE_REQUEST." WHERE 1 ORDER BY  created_time DESC ";
$pager = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $query_raw, $query_numrows);
$page_data_query= tep_db_query($query_raw);
$page_data = array();
while ($row = tep_db_fetch_array($page_data_query)) {
	$page_data[] = $row;
}
//pager
$pager_toolbar = '
<table width="100%" class="pageToolbar">
	<tr><td class="smallText" valign="top" colspan="3">
				'.$pager->display_count($query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS).'>
			</td>
			<td class="smallText" align="right" colspan="3">
				'.$pager->display_links($query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID'))).'
			</td>
</tr></table>';

include(VIN_TMP_PATH.$content);
require(DIR_WS_INCLUDES . 'application_bottom.php');

?>