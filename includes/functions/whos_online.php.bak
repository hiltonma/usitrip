<?php
/*
 * $Id: whos_online.php,v 1.11 2003/06/20 00:12:59 hpdl Exp $ osCommerce, Open
 * Source E-Commerce Solutions http://www.oscommerce.com Copyright (c) 2003
 * osCommerce Released under the GNU General Public License
 */
/**
 * 记录在线人数等信息
 * @see 在http://www.usitrip.com/admin/whos_online.php会用到这里记录的这些信息
 */
function tep_update_whos_online() {
	$wo_ip_address = tep_get_ip_address(); //getenv('REMOTE_ADDR');
	$wo_last_page_url = getenv('REQUEST_URI');
	$current_time = time();
	if (stripos($wo_last_page_url, 'images/') === false) {
		$_SESSION['online_ip_address'] = $wo_ip_address;
		$_SESSION['online_last_page_url'] = $wo_last_page_url;
		$_SESSION['online_http_referer'] = $_SERVER['HTTP_REFERER'];
		if (!$_SESSION['online_time_entry']) {
			$_SESSION['online_time_entry'] = $current_time;
		}
	}
}
?>
