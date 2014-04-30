<?php
/*
  $Id: banner.php,v 1.1.1.1 2004/03/04 23:40:48 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

////
// 取得广告组以便于前台显示，返回值为数组
function get_banners($banners_group, $language='schinese'){
	//global $language, $current_category_id;
	
	$banner_obj = array();
	$Today_date = date('Y-m-d H:i:s');
	$big_banner_sql = tep_db_query('SELECT * FROM '. TABLE_BANNERS .' WHERE status = "1" AND banners_group = "'.$banners_group.'" AND (banner_language_code_name ="all" || banner_language_code_name="'.$language.'") AND ((date_scheduled<="'.$Today_date.'" || date_scheduled IS NULL ) AND (expires_date>="'.$Today_date.'" || expires_date IS NULL )) order by banner_sort_order ASC');
	while($big_banner_rows = tep_db_fetch_array($big_banner_sql)){
		$having_cid = false;
		if(tep_not_null($big_banner_rows['categories_ids'])){
			$tmp_array = explode(",", tep_db_output($big_banner_rows['categories_ids']));
			foreach($tmp_array as $key => $val){
				if((int)$current_category_id && $val == $current_category_id){
					$having_cid = true;
				}
			}
		}
		if(!tep_not_null($big_banner_rows['categories_ids']) || ((int)$current_category_id  && $having_cid == true)){
			$FinalCode = '';
			$links = HTTP_SERVER.'/redirect.php?action=banner&goto='.$big_banner_rows['banners_id'];
			
			if(!isset($_SESSION['advertiser']) || $_SESSION['advertiser']=='SiteInnerAds'){
				//$links = HTTP_SERVER.'/redirect.php?action=banner&goto='.$big_banner_rows['banners_id'].'&utm_source=SiteInnerAds&utm_medium=banners_id&utm_campaign=&utm_term='.$big_banner_rows['banners_id'];
			}
			
			$src = HTTP_SERVER.'/images/'.$big_banner_rows['banners_image'];
			$alt = (tep_db_output($big_banner_rows['banners_title']));
			$text = ($big_banner_rows['banners_html_text']);
			if($big_banner_rows['banners_type']=='Flash'){
				$stat_flash = @getimagesize($src);
				$FinalCode = '
				<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" '.$stat_flash[3].' title="'.$alt.'">
  <param name="movie" value="'.$src.'">
  <param name="quality" value="high">
  <embed src="'.$src.'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" '.$stat_flash[3].'></embed>
</object>
				';
			}elseif($big_banner_rows['banners_type']=='HtmlText' || $big_banner_rows['banners_type']=='Couplet'){
				$FinalCode = $text;
			}
			
			$banner_obj[] = array('links'=> $links,
								'src'=> $src,
								'alt'=> $alt,
								'type'=>$big_banner_rows['banners_type'],
								'text'=>$text,
								'FinalCode'=>$FinalCode);
			//提示:如果FinalCode不为空则只显示FinalCode
			//后台不统计广告展示次数
			//tep_update_banner_display_count($big_banner_rows['banners_id']);
		}
	}
	if(!(int)count($banner_obj)){
		return false;
	}
	return $banner_obj;
}

?>
