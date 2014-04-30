<?php
/*
 * $Id: banner.php,v 1.1.1.1 2004/03/04 23:40:48 ccwjr Exp $ osCommerce, Open
 * Source E-Commerce Solutions http://www.oscommerce.com Copyright (c) 2003
 * osCommerce Released under the GNU General Public License
 */

/**
 * 设置广告状态
 * @param int $banners_id 广告id
 * @param int $status 状态值
 * @return Ambigous <boolean, resource>|number
 */
function tep_set_banner_status($banners_id, $status) {
	if ($status == '1' || $status == '0') {
		return tep_db_query("update " . TABLE_BANNERS . " set status = '".(int)$status."', date_status_change = now() where banners_id = '" . (int) $banners_id . "'");
	}else {
		return -1;
	}
}

/**
 * 自动激活广告
 */
function tep_activate_banners() {
	$banners_query = tep_db_query("select banners_id, date_scheduled from " . TABLE_BANNERS . " where date_scheduled <= now() and status=0 and expires_date >= now()");
	if (tep_db_num_rows($banners_query)) {
		while ($banners = tep_db_fetch_array($banners_query)) {
			tep_set_banner_status($banners['banners_id'], '1');			
		}
	}
}

/**
 * 自动关闭过期广告
 */
function tep_expire_banners() {
	/*
	 * $banners_query = tep_db_query("select b.banners_id, b.expires_date,
	 * b.expires_impressions, sum(bh.banners_shown) as banners_shown from " .
	 * TABLE_BANNERS . " b, " . TABLE_BANNERS_HISTORY . " bh where b.status =
	 * '1' and b.banners_id = bh.banners_id group by b.banners_id"); if
	 * (tep_db_num_rows($banners_query)) { while ($banners =
	 * tep_db_fetch_array($banners_query)) { if
	 * (tep_not_null($banners['expires_date'])) { if (date('Y-m-d H:i:s') >=
	 * $banners['expires_date']) { tep_set_banner_status($banners['banners_id'],
	 * '0'); } } elseif (tep_not_null($banners['expires_impressions'])) { if (
	 * ($banners['expires_impressions'] > 0) && ($banners['banners_shown'] >=
	 * $banners['expires_impressions']) ) {
	 * tep_set_banner_status($banners['banners_id'], '0'); } } } }
	 */
	$banners_query = tep_db_query("select b.banners_id from " . TABLE_BANNERS . " b where b.status = '1' and b.expires_date <= now() ");
	while ($banners = tep_db_fetch_array($banners_query)) {
		if (tep_not_null($banners['banners_id'])) {
			tep_set_banner_status($banners['banners_id'], '0');
		}
	}
}

////
// Display a banner from the specified group or banner id ($identifier)
function tep_display_banner($action, $identifier) {
	if ($action == 'dynamic') {
		$banners_query = tep_db_query("select count(*) as count from " . TABLE_BANNERS . " where status = '1' and banners_group = '" . $identifier . "'");
		$banners = tep_db_fetch_array($banners_query);
		if ($banners['count'] > 0) {
			$banner = tep_random_select("select banners_id, banners_title, banners_image, banners_html_text from " . TABLE_BANNERS . " where status = '1' and banners_group = '" . $identifier . "'");
		} else {
			return '<b>TEP ERROR! (tep_display_banner(' . $action . ', ' . $identifier . ') -> No banners with group \'' . $identifier . '\' found!</b>';
		}
	} elseif ($action == 'static') {
		if (is_array($identifier)) {
			$banner = $identifier;
		} else {
			$banner_query = tep_db_query("select banners_id, banners_title, banners_image, banners_html_text from " . TABLE_BANNERS . " where status = '1' and banners_id = '" . (int) $identifier . "'");
			if (tep_db_num_rows($banner_query)) {
				$banner = tep_db_fetch_array($banner_query);
			} else {
				return '<b>TEP ERROR! (tep_display_banner(' . $action . ', ' . $identifier . ') -> Banner with ID \'' . $identifier . '\' not found, or status inactive</b>';
			}
		}
	} else {
		return '<b>TEP ERROR! (tep_display_banner(' . $action . ', ' . $identifier . ') -> Unknown $action parameter value - it must be either \'dynamic\' or \'static\'</b>';
	}
	
	if (tep_not_null($banner['banners_html_text'])) {
		$banner_string = $banner['banners_html_text'];
	} else {
		$banner_string = '<a href="' . tep_href_link(FILENAME_REDIRECT, 'action=banner&goto=' . $banner['banners_id']) . '" target="_blank">' . tep_image(DIR_WS_IMAGES . $banner['banners_image'], db_to_html($banner['banners_title'])) . '</a>';
	}
	
	tep_update_banner_display_count($banner['banners_id']);
	
	return $banner_string;
}

////
// Check to see if a banner exists
function tep_banner_exists($action, $identifier) {
	if ($action == 'dynamic') {
		return tep_random_select("select banners_id, banners_title, banners_image, banners_html_text from " . TABLE_BANNERS . " where status = '1' and banners_group = '" . $identifier . "'");
	} elseif ($action == 'static') {
		$banner_query = tep_db_query("select banners_id, banners_title, banners_image, banners_html_text from " . TABLE_BANNERS . " where status = '1' and banners_id = '" . (int) $identifier . "'");
		return tep_db_fetch_array($banner_query);
	} else {
		return false;
	}
}

////
// Update the banner display statistics
function tep_update_banner_display_count($banner_id) {
	$data = date('Y-m-d');
	//$banner_check_query = tep_db_query("select count(*) as count from " . TABLE_BANNERS_HISTORY . " where banners_id = '" . (int)$banner_id . "' and date_format(banners_history_date, '%Y%m%d') = date_format(now(), '%Y%m%d')");
	$banner_check_query = tep_db_query("select count(*) as count from " . TABLE_BANNERS_HISTORY . " where banners_id = '" . (int) $banner_id . "' and banners_history_date Like '" . $data . "%' ");
	$banner_check = tep_db_fetch_array($banner_check_query);
	
	if ($banner_check['count'] > 0) {
		tep_db_query("update " . TABLE_BANNERS_HISTORY . " set banners_shown = banners_shown + 1 where banners_id = '" . (int) $banner_id . "' and banners_history_date Like '" . $data . "%' ");
	} else {
		tep_db_query("insert into " . TABLE_BANNERS_HISTORY . " (banners_id, banners_shown, banners_history_date) values ('" . (int) $banner_id . "', 1, now())");
	}
}

////
// Update the banner click statistics
function tep_update_banner_click_count($banner_id) {
	$data = date('Y-m-d');
	tep_db_query("update " . TABLE_BANNERS_HISTORY . " set banners_clicked = banners_clicked + 1 where banners_id = '" . (int) $banner_id . "' and banners_history_date Like '" . $data . "%' ");
}

////
// 取得广告组以便于前台显示，返回值为数组
function get_banners($banners_group, $for_email = false) {
	global $language, $current_category_id;
	$banner_obj = array ();
	$Today_date = date('Y-m-d H:i:s');
	$big_banner_sql = tep_db_query('SELECT * FROM ' . TABLE_BANNERS . ' WHERE status = "1" AND banners_group = "' . $banners_group . '" AND (banner_language_code_name ="all" || banner_language_code_name="' . $language . '") AND ((date_scheduled<="' . $Today_date . '" || date_scheduled IS NULL ) AND (expires_date>="' . $Today_date . '" || expires_date IS NULL )) order by banner_sort_order ASC');
	while ($big_banner_rows = tep_db_fetch_array($big_banner_sql)) {
		$having_cid = false;
		if (tep_not_null($big_banner_rows['categories_ids'])) {
			$tmp_array = explode(",", tep_db_output($big_banner_rows['categories_ids']));
			foreach ($tmp_array as $key => $val) {
				if ((int) $current_category_id && $val == $current_category_id) {
					$having_cid = true;
				}
			}
		}
		if (!tep_not_null($big_banner_rows['categories_ids']) || ((int) $current_category_id && $having_cid == true)) {
			$FinalCode = '';
			$links = tep_href_link('redirect.php', 'action=banner&goto=' . $big_banner_rows['banners_id']);
			if (!isset($_SESSION['advertiser']) || $_SESSION['advertiser'] == 'SiteInnerAds') {
				//$links = tep_href_link('redirect.php', 'action=banner&goto='.$big_banner_rows['banners_id'].'&utm_source=SiteInnerAds&utm_medium=banners_id&utm_campaign=&utm_term='.$big_banner_rows['banners_id']);
			}
			
			$src = DIR_WS_IMAGES . $big_banner_rows['banners_image'];
			if ($for_email == true) {
				$src = DIR_WS_IMAGES . $big_banner_rows['banners_image'];
			}
			$alt = db_to_html(tep_db_output($big_banner_rows['banners_title']));
			$text = db_to_html($big_banner_rows['banners_html_text']);
			if ($big_banner_rows['banners_type'] == 'Flash') {
				$stat_flash = @getimagesize($src);
				$FinalCode = '
				<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" ' . $stat_flash[3] . ' title="' . $alt . '">
  <param name="movie" value="' . $src . '">
  <param name="quality" value="high">
  <embed src="' . $src . '" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" ' . $stat_flash[3] . '></embed>
</object>
				';
			} elseif ($big_banner_rows['banners_type'] == 'HtmlText' || $big_banner_rows['banners_type'] == 'Couplet') {
				$FinalCode = $text;
			}
			
			$banner_obj[] = array (
					'links' => $links,
					'src' => $src,
					'alt' => $alt,
					'type' => $big_banner_rows['banners_type'],
					'text' => $text,
					'FinalCode' => $FinalCode,
					'directLinks'=>$big_banner_rows['banners_url']
			);
			//提示:如果FinalCode不为空则只显示FinalCode
			tep_update_banner_display_count($big_banner_rows['banners_id']);
		}
	}
	if (!(int) count($banner_obj)) {
		return false;
	}
	return $banner_obj;
}

?>
