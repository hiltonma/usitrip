<?php
/**
 * 首页类：首页用到的一些函数集合，只用在首页
 */

class Index{
	/**
	 * 取得首页的特价，返回数组
	 *
	 */
	public function specials(){
		global $languages_id;
		$data = false;
		//$p_id_str = '970,719,411';
		if(defined('TOURS_HOMEPAGE_SPECIAL_OFFERS')){
			$p_id_str = TOURS_HOMEPAGE_SPECIAL_OFFERS;
		}
		$specials_sql = tep_db_query("select s.specials_new_products_price, p.products_image, p.products_id,pd.products_name, p.products_price,p.products_tax_class_id from ".TABLE_SPECIALS." as s, ".TABLE_PRODUCTS." as p, ".TABLE_PRODUCTS_DESCRIPTION." as pd  where s.products_id in(".$p_id_str.") and s.products_id = p.products_id AND p.products_id = pd.products_id and pd.language_id='".(int) $languages_id."' and p.products_status=1 and s.status=1 Order BY s.specials_date_added DESC limit 6 ");
		while ($rows = tep_db_fetch_array($specials_sql)) {
			$data[] = $rows;
		}
		return $data;
	}
	/**
	 * 取得首页的特惠团(买2送1买，2送2)
	 *
	 */
	public function buy2_get_12(){
		global $languages_id;
		$data = false;
		//$p_id_str = '140,1054,151,441,545,476';
		$p_id_str = '441,545,476,32,349,322 ';
		
		if(defined('TOURS_HOMEPAGE_BUY2_GET_12')){
			$p_id_str = TOURS_HOMEPAGE_BUY2_GET_12;
		}
		$sql_str = ("select p.products_image, p.products_id, pd.products_name, p.products_price,p.products_tax_class_id, p.tour_type_icon from ".TABLE_PRODUCTS." as p, ".TABLE_PRODUCTS_DESCRIPTION." as pd  where p.products_id in(".$p_id_str.") AND p.products_id = pd.products_id and pd.language_id='".(int) $languages_id."' and p.products_status=1 Order BY FIND_IN_SET(p.products_id,'".$p_id_str."') limit 6 ");
		$sql = tep_db_query($sql_str);
		while ($rows = tep_db_fetch_array($sql)) {
			$data[] = $rows;
		}
		return $data;
	}

	/**
	 * 取得热销排行榜数据，返回数组
	 *
	 */
	public function best_sellers(){
		$data = false;
		$prod_id_str ='';
		if(defined('TOURS_HOMEPAGE_BEST_SELLERS')){
			$prod_id_str = TOURS_HOMEPAGE_BEST_SELLERS;
		}
		if(tep_not_null($prod_id_str)){
			$prod_id_array = explode(',',$prod_id_str);
			foreach($prod_id_array as $key => $val){
				$sql = tep_db_query("select p.products_id,products_name,products_tax_class_id,products_price FROM products as p, products_description as pd WHERE p.products_id=pd.products_id AND p.products_status='1' AND p.products_id ='".(int)$val."' ");
				$row = tep_db_fetch_array($sql);
				if((int)$row['products_id']){
					$data[] = $row;
				}
			}
		}
		return $data;
	}

	/**
	 * 取得热门推荐
	 * @param $catalog_id 目录id如123,789,41
	 */
	public function hot_recommend($catalog_id){
		$data = false;
		$catalog_ids = explode(',',$catalog_id);
		$loop = 0;
		foreach ($catalog_ids as $key => $c_id){
			$data[$loop]['cPath'] = tep_get_category_patch((int)$c_id);
			$data[$loop]['title'] = tep_get_category_name((int)$c_id);
			$data[$loop]['image'] = tep_get_categories_image((int)$c_id);
			$sql = tep_db_query("select categories_recommended_tours_ids from categories where categories_id='" . (int)$c_id . "'");
			$order_list = tep_db_fetch_array($sql);
			if (tep_not_null($order_list['categories_recommended_tours_ids'])) {
				$sql = tep_db_query("select p.products_id,products_name,products_tax_class_id,products_price FROM products as p, products_description as pd, products_to_categories p2c WHERE p.products_id=pd.products_id AND p.products_id=p2c.products_id AND p.products_status='1' AND p2c.categories_id ='".(int)$c_id."' and p.products_id in (" . $order_list['categories_recommended_tours_ids'] . ") order by find_in_set(p.products_id,'" . $order_list['categories_recommended_tours_ids'] . "') Limit 4");
				$data[$loop]['content'] = array();
				while ($rows = tep_db_fetch_array($sql)) {
					$data[$loop]['content'][] = $rows;
				}
			}
			$loop++;
		}
		return $data;
	}

	/**
	 * 取得最新的客户咨询
	 *
	 */
	public function question(){
		$data = false;
		$sql = tep_db_query('SELECT customers_name, question, products_id, date FROM `tour_question` WHERE `replay_has_checked`="1" AND languages_id="1" AND products_id!="" ORDER BY is_top DESC, date DESC LIMIT 6 ');
		while ($rows = tep_db_fetch_array($sql)){
			$data[] = $rows;
		}
		return $data;
	}
	
	/**
	 * 取得客户评论
	 *
	 */
	public function reviews(){
		$data = false;
		$sql = tep_db_query("select r.reviews_id, r.rating_total, rd.reviews_text as reviews_text, rd.reviews_title, r.reviews_rating, r.date_added, p.products_id, pd.products_name, p.products_image, r.customers_name from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = r.products_id and r.reviews_id = rd.reviews_id and r.reviews_status='1' and p.products_id = pd.products_id and pd.language_id = '1' and rd.languages_id = '1' and r.parent_reviews_id=0 order by r.reviews_id DESC Limit 2 ");
		while ($rows = tep_db_fetch_array($sql)){
			$data[] = $rows;
		}
		return $data;		
	}
	/**
	 * 取得相片分享
	 *
	 */
	public function photos(){
		$data = false;
		$sql = tep_db_query('SELECT traveler_photo_id,products_id,image_title,image_name FROM '.TABLE_TRAVELER_PHOTOS.' WHERE image_status ="1" AND is_display_homepage="1" ORDER BY  traveler_photo_id DESC Limit 12');		
		$loop = 0;
		while($rows=tep_db_fetch_array($sql)){
			$data[$loop] = $rows;
			$basename_image_name = basename($rows['image_name']);
			$image_name0 = str_replace($basename_image_name, 'thumb_'.$basename_image_name, $rows['image_name'] );
			$data[$loop]['img'] = 'images/reviews/'.$image_name0;
			$loop++;
		}
		return $data;
	}
	/**
	 * 取得团购产品信息
	 * @param string $limit 取几条[默认2]
	 * @return array
	 */
	public function group_buys($limit='2'){
		$data = false;
		$Today_date = date('Y-m-d');
		$sql_str = 'SELECT p.products_id, p.products_price, s.specials_new_products_price, s.start_date, s.expires_date, s.specials_type, s.specials_max_buy_num, s.invite_info, s.remaining_num, pd.products_name, pd.products_small_description 
		FROM `products` p, `products_description` pd,  specials s 
		WHERE pd.products_id = p.products_id AND s.products_id = p.products_id AND p.products_status = "1" AND p.products_stock_status="1" AND s.status="1" AND s.specials_type>=1 AND s.start_date <="'.$Today_date.'" AND s.expires_date >"'.$Today_date.'" Order By s.specials_type ASC limit ' . $limit;
		$sql = tep_db_query($sql_str);
		while($row = tep_db_fetch_array($sql)){
			if((int)$row['products_id']){
				$temp = $row;
				$temp['Savings'] = $row['products_price']-$row['specials_new_products_price'];
				$temp['Discount'] = round(($row['specials_new_products_price'] / max(1,(int)$row['products_price'])),2)*10;
				$temp['NowDateTime'] = date('Y-m-d H:i:s');
				$temp['CountdownEndTime'] = strtotime($temp['expires_date'])-strtotime($temp['NowDateTime']);
				$data[] = $temp;
			}
		}
		return $data;
	}
	
	/**
	 * 取得最新的公告
	 *
	 */
	public function get_latest_announce($rows_num = 4){
		$data = false;
		
		$sql = 'SELECT a.articles_id, b.articles_name FROM articles AS a,articles_description AS b WHERE a.articles_status=1 AND a.article_type=\'announce\' AND IFNULL(a.articles_date_available,now())<=now() AND a.articles_id=b.articles_id ORDER BY a.articles_date_added DESC LIMIT '.(int)$rows_num;
		
		$sql_query = tep_db_query($sql);
		while($rows = tep_db_fetch_array($sql_query))
		{
			$data[] = $rows;
		}
		return $data;
	}	
}

?>