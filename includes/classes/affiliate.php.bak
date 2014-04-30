<?php
/**
 * 销售联盟类，目前不太完善，正在完善中，请见谅！
 * @author Howard
 */

class affiliate{
	/**
	 * 取得销售联盟成员总数
	 *
	 */
	public function getAffiliatePeopleTotal(){
		$sql = tep_db_query('SELECT count(affiliate_id) as total FROM `affiliate_affiliate` WHERE verified ="1" ');
		$row = tep_db_fetch_array($sql);
		return (int)$row['total'];
	}
	/**
	 * 取得上月会员收入排行榜（月收入龙虎榜）
	 * 条件：会员已经验证，上月已发的佣金
	 * @param unknown_type $topNum 取几位，默认前5位
	 */
	public function getAffiliateTopSalesRanking($topNum = 5){
		$data = false;
		$last_month_start = date('Y-m-d H:i:s',mktime(0, 0, 0, (date('m')-1), 1, date('Y')));
		$last_month_end = date('Y-m-d H:i:s',(strtotime(date('Y-m-01 23:59:59'))-86400));
		$sql = tep_db_query('SELECT sum(ap.affiliate_payment_total) as total, aa.affiliate_firstname FROM affiliate_affiliate aa, affiliate_payment ap WHERE aa.affiliate_id =ap.affiliate_id AND ap.affiliate_payment_status="1" AND aa.verified="1" AND ap.affiliate_payment_date >="'.$last_month_start.'" AND ap.affiliate_payment_date <="'.$last_month_end.'" GROUP BY aa.affiliate_id ORDER BY total DESC Limit '.$topNum);
		while ($rows = tep_db_fetch_array($sql)) {
			$data[] = $rows;
		}
		return $data;
	}

	/**
	 * 取得热销产品推荐
	 * 从首页的类取得
	 * @param unknown_type $rowNum
	 */
	public function bestSellers($rowNum = 4){
		$data = false;
		require_once(DIR_FS_CLASSES . 'index.php');
		$bestSellers = Index::best_sellers();
		if($bestSellers!=false){
			$lp = 0;
			foreach($bestSellers as $key => $val){
				$data[$key] = $val;
				if(!tep_not_null($data[$key]['thumbImageSrc'])){	//取得产品缩略图地址
					$minImage = tep_get_products_image((int)$data[$key]['products_id']);
					$minImage = ((stripos( $minImage,'http://')===false) ? 'images/':''). $minImage;
					if(strpos( $minImage,'/detail_')!==false){
						$minImage = str_replace('/detail_','/thumb_',$minImage);
					}
					$data[$key]['thumbImageSrc'] = $minImage;
				}
				$lp++;
				if($lp >= (int)$rowNum){ break; }
			}
		}
		return $data;
	}
	/**
	 * 取得某人的推广优惠码
	 *
	 * @param int $affiliate_id
	 */
	public static function couponCode($affiliate_id){
		$sql = tep_db_query('SELECT affiliate_coupon_code FROM `affiliate_affiliate` WHERE affiliate_id = "'.(int)$affiliate_id.'" and coupon_code_verify=0');
		$row = tep_db_fetch_array($sql);
		return $row ? $row['affiliate_coupon_code'] : '审核中，请完善结算中心信息。';
	}
	/**
	 * 为会员联盟创建Coupon Code(优惠码)，优惠码格式：AF-base64_encode(会员ID)，旧会员是从老站传过来的优惠码格式是无序字符
	 * 1.订单金额小于700美金不能使用优惠码(Coupon Code)进行打折，优惠值为订单总额的2%(P)
	 * 2.您的Coupon Code(优惠码)不允许用在自己的订单里。
	 * 3.使用了Coupon Code(优惠码)，将不再赠送积分。
	 * 4.旧站会员已领用的Coupon Code(优惠码)，新站将仍然有效。
	 * 优惠码保存到
	 * @param unknown_type $affiliate_id
	 */
	public function createCouponCode($affiliate_id){
		if(!(int)$affiliate_id){ echo ('no affiliate_id on '.__FILE__.' line:'.__LINE__); exit; }
		$affiliate_coupon_code = $this->couponCode($affiliate_id);
		//生成会员优惠码
		if(!tep_not_null($affiliate_coupon_code)){
			$affiliate_coupon_code = 'AF-'.str_replace('=','',base64_encode($affiliate_id));
			$sql = tep_db_query('update `affiliate_affiliate` set affiliate_coupon_code = "'.$affiliate_coupon_code.'" WHERE affiliate_id = "'.(int)$affiliate_id.'" ');
		}
		//创建会员优惠码到优惠券数据表
		$check_sql = tep_db_query('SELECT coupon_id FROM `coupons` WHERE affiliate_id="'.(int)$affiliate_id.'" ');
		$check_row = tep_db_fetch_array($check_sql);
		if(!(int)$check_row['coupon_id']){
			$date = date('Y-m-d H:i:s');
			$array =
			array('affiliate_id'=> $affiliate_id,
			'coupon_type'=> 'P',
			'coupon_code'=> $affiliate_coupon_code,
			'coupon_amount'=>'2.0000',
			'coupon_minimum_order'=>'700',
			'coupon_start_date'=>'2012-07-01 00:00:00',
			'coupon_expire_date'=>'2099-12-31 00:00:00',
			'need_customers_active'=>'0',
			'coupon_active'=>'Y',
			'date_created'=> $date,
			'date_modified'=> $date,
			'uses_per_coupon'=>'10000',
			'uses_per_user'=>'100'
			);
			tep_db_perform('coupons',$array);
			$coupon_id = tep_db_insert_id();
			tep_db_query('INSERT INTO coupons_description SET coupon_id ="'.$coupon_id.'", language_id="1", coupon_name="销售联盟会员优惠码" ');
		}
	}
	/**
	 * 自动帮老客户创建Coupon Code(优惠码)，上线时只用一次即可，以后不再使用此方法！
	 *
	 * @param unknown_type $max_customers_id 老客户的最大客户ID值，60000
	 */
	public function autoCreateCouponCodeForAllOldCustomers($max_customers_id=60000){
		$sql = tep_db_query('SELECT affiliate_id FROM `affiliate_affiliate` WHERE affiliate_id < '.(int)$max_customers_id);
		while ($rows = tep_db_fetch_array($sql)){
			if((int)$rows['affiliate_id']){
				$this->createCouponCode($rows['affiliate_id']);
			}
		}
		tep_db_query('OPTIMIZE TABLE `coupons` ');
	}
	/**
	 * 根据折扣券代码取得会员联盟的用户ID
	 *
	 * @param unknown_type $code
	 */
	public function getAffiliateIdFromCouponCode($code){
		$sql = tep_db_query('SELECT affiliate_id FROM `affiliate_affiliate` WHERE affiliate_coupon_code="'.tep_db_prepare_input(tep_db_input($code)).'" ');
		$row = tep_db_fetch_array($sql);
		return $row['affiliate_id'];
	}
	/**
	 * 取得某条affiliate_payment记录的付款状态
	 * @param $affiliate_payment_id
	 */
	public function getPaymentStatus($affiliate_payment_id){
		$sql = tep_db_query('SELECT aps.affiliate_payment_status_name FROM `affiliate_payment` ap, affiliate_payment_status aps WHERE ap.affiliate_payment_status=aps.affiliate_payment_status_id and ap.affiliate_payment_id="'.$affiliate_payment_id.'" ');
		$row = tep_db_fetch_array($sql);
		return $row['affiliate_payment_status_name'];
	}
}
?>