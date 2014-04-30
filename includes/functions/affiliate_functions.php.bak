<?php
/*
  $Id: affiliate_functions.php,v 1.1.1.1 2004/03/04 23:40:47 ccwjr Exp $

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

  function affiliate_check_url($url) {
    return eregi("^https?://[a-z0-9]([-_.]?[a-z0-9])+[.][a-z0-9][a-z0-9/=?.&\~_-]+$",$url);
  }

  function affiliate_insert ($sql_data_array, $affiliate_parent = 0) {
    // LOCK TABLES
    tep_db_query("LOCK TABLES " . TABLE_AFFILIATE . " WRITE");
    if ($affiliate_parent > 0) {
      $affiliate_root_query = tep_db_query("select affiliate_root, affiliate_rgt, affiliate_lftfrom  " . TABLE_AFFILIATE . " where affiliate_id = '" . $affiliate_parent . "' ");
      // Check if we have a parent affiliate
      if ($affiliate_root_array = tep_db_fetch_array($affiliate_root_query)) {
        tep_db_query("update " . TABLE_AFFILIATE . " SET affiliate_lft = affiliate_lft + 2 WHERE affiliate_root  =  '" . $affiliate_root_array['affiliate_root'] . "' and  affiliate_lft > "  . $affiliate_root_array['affiliate_rgt'] . "  AND affiliate_rgt >= " . $affiliate_root_array['affiliate_rgt'] . " ");
        tep_db_query("update " . TABLE_AFFILIATE . " SET affiliate_rgt = affiliate_rgt + 2 WHERE affiliate_root  =  '" . $affiliate_root_array['affiliate_root'] . "' and  affiliate_rgt >= "  . $affiliate_root_array['affiliate_rgt'] . "  ");


        $sql_data_array['affiliate_root'] = $affiliate_root_array['affiliate_root'];
        $sql_data_array['affiliate_lft'] = $affiliate_root_array['affiliate_rgt'];
        $sql_data_array['affiliate_rgt'] = ($affiliate_root_array['affiliate_rgt'] + 1);
        tep_db_perform(TABLE_AFFILIATE, $sql_data_array);
        $affiliate_id = tep_db_insert_id();
      }
    // no parent -> new root
    } else {
      $sql_data_array['affiliate_lft'] = '1';
      $sql_data_array['affiliate_rgt'] = '2';
      tep_db_perform(TABLE_AFFILIATE, $sql_data_array);
      $affiliate_id = tep_db_insert_id();
      tep_db_query ("update " . TABLE_AFFILIATE . " set affiliate_root = '" . $affiliate_id . "' where affiliate_id = '" . $affiliate_id . "' ");
    }
    // UNLOCK TABLES
    tep_db_query("UNLOCK TABLES");
    return $affiliate_id;

  }



////
// Compatibility to older Snapshots
  if (!function_exists('tep_round')) {
    function tep_round($value, $precision) {
      if (PHP_VERSION < 4) {
        $exp = pow(10, $precision);
        return round($value * $exp) / $exp;
      } else {
        return round($value, $precision);
      }
    }
  }

////
// Output a form
  if (!function_exists('tep_draw_form')) {
    function tep_draw_form($name, $action, $method = 'post', $parameters = '') {
      $form = '<form name="' . tep_parse_input_field_data($name, array('"' => '&quot;')) . '" action="' . tep_parse_input_field_data($action, array('"' => '&quot;')) . '" method="' . tep_parse_input_field_data($method, array('"' => '&quot;')) . '"';

      if (tep_not_null($parameters)) $form .= ' ' . $parameters;

      $form .= '>';

      return $form;
    }
  }

////
// This funstion validates a plain text password with an encrpyted password
  if (!function_exists('tep_validate_password')) {
    function tep_validate_password($plain, $encrypted) {
      if (tep_not_null($plain) && tep_not_null($encrypted)) {
// split apart the hash / salt
        $stack = explode(':', $encrypted);

        if (sizeof($stack) != 2) return false;

        if (md5($stack[1] . $plain) == $stack[0]) {
          return true;
        }
      }

      return false;
    }
  }

////
// This function makes a new password from a plaintext password.
  if (!function_exists('tep_encrypt_password')) {
    function tep_encrypt_password($plain) {
      $password = '';

      for ($i=0; $i<10; $i++) {
        $password .= tep_rand();
      }

      $salt = substr(md5($password), 0, 2);

      $password = md5($salt . $plain) . ':' . $salt;

      return $password;
    }
  }

////
// Return a random value
  if (!function_exists('tep_rand')) {
    function tep_rand($min = null, $max = null) {
      static $seeded;

      if (!isset($seeded)) {
        mt_srand((double)microtime()*1000000);
        $seeded = true;
      }

      if (isset($min) && isset($max)) {
        if ($min >= $max) {
          return $min;
        } else {
          return mt_rand($min, $max);
        }
      } else {
        return mt_rand();
      }
    }
  }

//推荐给朋友的产品连接。$unique_id是如果有积分id的话就添加unique_id号，以便使用户点击邮件过来时自动将id值为unique_id的表 customers_points_pending中对应的记录状态设置为2(确定)
function tep_get_refer_friend_products_link($products_id,$afflinks_id,$unique_id=0){
	$affiliate_products_query = "select p.products_id, p.products_image, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . $products_id . "' and p.products_id = pd.products_id";
	$affiliate_products_row = tep_db_query($affiliate_products_query);
	if($affiliate_products = tep_db_fetch_array($affiliate_products_row)){
		 $product_image = $affiliate_products['products_image'];
		 $products_name = $affiliate_products['products_name'];
//		 $link = '<a href="' . HTTP_SERVER . DIR_WS_HTTP_CATALOG . FILENAME_PRODUCT_INFO . '?ref=' . $afflinks_id . '&products_id=' . $affiliate_products['products_id'] . '&affiliate_banner_id=1" target="_blank"><img src="' . DIR_WS_IMAGES . $product_image . '" border="0" alt="' . $affiliate_products['products_name'] . '"></a>';
		 $unique_id_str = '';
		 if((int)$unique_id){
		 	$unique_id_str = '&refriend_points_unique_id='.(int)$unique_id;
		 }
		 $link = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO ,'ref=' . $afflinks_id . '&utm_source='. $afflinks_id .'&utm_medium=af&utm_term=refertofriends&' .$unique_id_str. '&products_id=' . $affiliate_products['products_id'] . '&affiliate_banner_id=1').'" target="_blank"><b>'.$affiliate_products['products_name'].'</b></a>';
	}
return   $link; 
}

//推荐给朋友的目录连接
function tep_get_refer_friend_categories_link($cat_id,$afflinks_id,$unique_id=0){
	$affiliate_cat_query = "select c.categories_id, c.categories_image, cd.categories_name from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . $cat_id . "' and c.categories_id = cd.categories_id ";
	$affiliate_cat_row = tep_db_query($affiliate_cat_query);
	if($affiliate_cat = tep_db_fetch_array($affiliate_cat_row)){

//		$link = '<a href="' . tep_href_link(FILENAME_DEFAULT , 'ref=' . $affiliate_id . '&cPath=' . $cat_id . '&affiliate_banner_id=1').'" target="_blank"><img src="' . HTTPS_SERVER . DIR_WS_CATALOG . FILENAME_AFFILIATE_SHOW_BANNER . '?ref=' . $affiliate_id . '&affiliate_pbanner_id=' . $individual_banner_id . '" border="0" alt="' . $affiliate_cat['categories_name'] . '"></a>';
		 $unique_id_str = '';
		 if((int)$unique_id){
		 	$unique_id_str = '&refriend_points_unique_id='.(int)$unique_id;
		 }
		$link = '<a href="' . tep_href_link(FILENAME_DEFAULT , 'ref='.$afflinks_id.'&utm_source='.$afflinks_id.'&utm_medium=af&utm_term=refertofriends&' . '&cPath=' . $affiliate_cat['categories_id'] .$unique_id_str. '&affiliate_banner_id=1').'" target="_blank"><b>'.$affiliate_cat['categories_name'].'</b></a>';

	}
return   $link; 
}



function tep_get_category_tree($parent_id = '0', $spacing = '', $exclude = '', $category_tree_array = '', $include_itself = false) {
    global $languages_id;

    if (!is_array($category_tree_array)) $category_tree_array = array();
    if ( (sizeof($category_tree_array) < 1) && ($exclude != '0') ) $category_tree_array[] = array('id' => '0', 'text' => TEXT_TOP);

    if ($include_itself) {
      /*$category_query = tep_db_query("select cd.categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " cd where cd.language_id = '" . (int)$languages_id . "' and cd.categories_id = '" . (int)$parent_id . "'");
      $category = tep_db_fetch_array($category_query);*/
      $category = MCache::fetch_categories((int)$parent_id);
      $category_tree_array[] = array('id' => $parent_id, 'text' => db_to_html($category['categories_name']));
    }
	/*
    $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and c.parent_id = '" . (int)$parent_id . "' order by c.sort_order, cd.categories_name");
    while ($categories = tep_db_fetch_array($categories_query)) {
      if ($exclude != $categories['categories_id']) $category_tree_array[] = array('id' => $categories['categories_id'], 'text' => $spacing . db_to_html($categories['categories_name']));
      $category_tree_array = tep_get_category_tree($categories['categories_id'], $spacing . '&nbsp;&nbsp;&nbsp;', $exclude, $category_tree_array);
    }*/
     $category_list = MCache::search_categories('parent_id', intval($parent_id),true);
  	foreach ($category_list as $categories ) {
      if ($exclude != $categories['categories_id']) $category_tree_array[] = array('id' => $categories['categories_id'], 'text' => $spacing . db_to_html($categories['categories_name']));
      $category_tree_array = tep_get_category_tree($categories['categories_id'], $spacing . '&nbsp;&nbsp;&nbsp;', $exclude, $category_tree_array);
    }
    

    return $category_tree_array;
  }

function tep_get_affiliate_percent_display(){
//
$affilatevalue = AFFILIATE_PERCENT;

//amit commented old code
return number_format($affilatevalue).'%';

//return '10－60';
} 

/**
 * 取得某人的网站联盟账户信息
 * @param $affiliate_id
 * @author Howard
 * @return Array
 */
function getAffiliateInfo($affiliate_id){	
  $infoArray = array();
  $affiliate_banner_history_raw = "select sum(affiliate_banners_shown) as count from " . TABLE_AFFILIATE_BANNERS_HISTORY .  " where affiliate_banners_affiliate_id  = '" . (int)$affiliate_id . "'";
  $affiliate_banner_history_query=tep_db_query($affiliate_banner_history_raw);
  $affiliate_banner_history = tep_db_fetch_array($affiliate_banner_history_query);
  $affiliate_impressions = $affiliate_banner_history['count'];
  if ($affiliate_impressions == 0) $affiliate_impressions="n/a";
  //广告显示总次数
  $infoArray['affiliate_impressions'] = $affiliate_impressions;

  $affiliate_clickthroughs_raw = "select count(*) as count from " . TABLE_AFFILIATE_CLICKTHROUGHS . " where affiliate_id = '" . (int)$affiliate_id . "'";
  $affiliate_clickthroughs_query = tep_db_query($affiliate_clickthroughs_raw);
  $affiliate_clickthroughs = tep_db_fetch_array($affiliate_clickthroughs_query);
  $affiliate_clickthroughs =$affiliate_clickthroughs['count'];
  //广告点击总次数
  $infoArray['affiliate_clickthroughs'] = $affiliate_clickthroughs;

  $affiliate_sales_raw = "
			select count(*) as count, sum(affiliate_value) as total, sum(affiliate_payment) as payment from " . TABLE_AFFILIATE_SALES . " a 
			left join " . TABLE_ORDERS . " o on (a.affiliate_orders_id=o.orders_id) 
			where a.affiliate_id = '" . (int)$affiliate_id . "' and a.affiliate_isvalid = 1 /* and o.orders_status >= " . AFFILIATE_PAYMENT_ORDER_MIN_STATUS . " */
			";
  $affiliate_sales_query = tep_db_query($affiliate_sales_raw);
  $affiliate_sales = tep_db_fetch_array($affiliate_sales_query);

  $affiliate_transactions=$affiliate_sales['count'];
  //交易订单总数
  $infoArray['affiliate_transactions'] = $affiliate_transactions;
  if ($affiliate_clickthroughs > 0) {
	$affiliate_conversions = tep_round(($affiliate_transactions / $affiliate_clickthroughs)*100, 2) . "%";
  } else {
    $affiliate_conversions = "n/a";
  }
  //转换率
  $infoArray['affiliate_conversions'] = $affiliate_conversions;
  
  $affiliate_amount = $affiliate_sales['total'];
  //销售总金额
  $infoArray['affiliate_amount'] = $affiliate_amount;
  
  if ($affiliate_transactions>0) {
	$affiliate_average = tep_round($affiliate_amount / $affiliate_transactions, 2);
  } else {
	$affiliate_average = "n/a";
  }
  //平均销售额
  $infoArray['affiliate_average'] = $affiliate_average;

  $affiliate_commission = $affiliate_sales['payment'];
  //佣金总额
  $infoArray['affiliate_commission'] = $affiliate_commission;

  $affiliate_values = tep_db_query("select * from " . TABLE_AFFILIATE . " where affiliate_id = '" . (int)$affiliate_id . "'");
  $affiliate = tep_db_fetch_array($affiliate_values);
  $affiliate_percent = 0;
  $affiliate_percent = $affiliate['affiliate_commission_percent'];
  if ($affiliate_percent < AFFILIATE_PERCENT) $affiliate_percent = AFFILIATE_PERCENT;
  //佣金比例
  $infoArray['affiliate_percent'] = $affiliate_percent;
  
  return $infoArray;
}

/**
 * 记录某人的网站联盟的Session信息
 * @author Howard
 * @param $customer_id
 */
function setSessionAffiliateInfo($customer_id){
	$customer_id = (int)$customer_id;
	//amit added to check if user exits in affiliate table start {
	$check_affilate = "select affiliate_id, verified from " . TABLE_AFFILIATE . " where affiliate_id=" . $customer_id;
	$check_affilate_query = tep_db_query($check_affilate);
	if (!tep_db_num_rows($check_affilate_query)) {
		$sql_data_array = array('affiliate_id' => $customer_id);
		$sql_data_array['affiliate_lft'] = '1';
		$sql_data_array['affiliate_rgt'] = '2';
		tep_db_perform(TABLE_AFFILIATE, $sql_data_array);
		tep_db_query("update " . TABLE_AFFILIATE . " set affiliate_root = '" . $customer_id . "' where affiliate_id = '" . $customer_id . "' ");

		/*
		  if(isset($HTTP_SESSION_VARS['affiliate_ref'])){
		  $testaffiliate_id = affiliate_insert ($sql_data_array, $HTTP_SESSION_VARS['affiliate_ref']);
		  }else{
		  $sql_data_array['affiliate_lft'] = '1';
		  $sql_data_array['affiliate_rgt'] = '2';
		  tep_db_perform(TABLE_AFFILIATE, $sql_data_array);
		  tep_db_query ("update " . TABLE_AFFILIATE . " set affiliate_root = '" . $affiliate_id . "' where affiliate_id = '" . $customer_id . "' ");
		  }
		 */
	}else{
		$row = tep_db_fetch_array($check_affilate_query);
		$verified = $row['verified'];
	}
	//amit added to check if user exits in affilite table end }
	$affiliate_id = $customer_id;
	$affiliate_verified = (int)$verified;
	//tep_session_register('affiliate_id');
	//tep_session_register('affiliate_verified');
	//记录用户affiliate_id和是否已验证账号的信息
	$_SESSION['affiliate_id']=$affiliate_id;
	$_SESSION['affiliate_verified']=$affiliate_verified;
}

/**
 * 取得所有的Affiliate Products
 * @author Howard
 * @param $limitNum 默认取得10个产品
 * @return Array
 */
function getAffiliateAllProducts($limitNum=0){
	$hotProducts = array();
	$limitStr = "";
	if((int)$limitNum){ $limitStr = ' Limit '.(int)$limitNum; }
	//产品部自定义的推荐产品
	$sql = tep_db_query('SELECT p.products_id, pd.products_name, count(op.products_id) as total FROM `orders_products` op, `products` p, `products_description` pd, `categories` c, `products_to_categories` ptc WHERE p.products_id=op.products_id and p.products_status="1" and p.products_stock_status="1" and pd.products_id=p.products_id and pd.language_id="1" and p.products_id=ptc.products_id and ptc.categories_id=c.categories_id and c.categories_id=271  Group By p.products_id Order By total DESC '.$limitStr);
	//如果没有自定义则由系统根据销售量推荐
	if(!tep_db_num_rows($sql)){
		$sql = tep_db_query('SELECT p.products_id, pd.products_name, count(op.products_id) as total FROM `orders_products` op, `products` p, `products_description` pd WHERE p.products_id=op.products_id and p.products_status="1" and p.products_stock_status="1" and pd.products_id=p.products_id and pd.language_id="1" Group By p.products_id Order By total DESC '.$limitStr);
	}
	
	while($rows = tep_db_fetch_array($sql)){
		$hotProducts[]=$rows;
	}
	return $hotProducts;
}

/**
 * 取得affiliate的邮箱
 * @author Howard
 * @param $affiliate_id
 * @return String
 */
function tep_get_affiliate_email_address($affiliate_id){
	$sql_str = "select affiliate_email_address from affiliate_affiliate where affiliate_id = '" . tep_db_input($affiliate_id) . "'";
	$query = tep_db_query($sql_str);
	if($row = tep_db_fetch_array($query)) {
		if(tep_not_null($row['affiliate_email_address'])){
			return $row['affiliate_email_address'];
		}
	}
	return false;
}

/**
 * 检查是否有与某个affiliate重复的邮箱
 * @author Howard
 * @param $affiliate_email_address 要检查的邮箱地址，$without_affiliate_id被排除的$affiliate_id
 * @return 0 or affiliate_id
 */
function checkDuplicateAffiliateEmail($affiliate_email_address, $without_affiliate_id=0){
	$sql = tep_db_query('SELECT `affiliate_id` FROM `affiliate_affiliate` WHERE `affiliate_email_address` = "'.tep_db_input(tep_db_prepare_input($affiliate_email_address)).'" and affiliate_id!="'.(int)$without_affiliate_id.'" limit 1');
	$row = tep_db_fetch_array($sql);
	return (int)$row['affiliate_id'];
}

/**
 * 发送网站联盟邮箱验证邮件
 * @author Howard
 * @param $affiliate_id
 * @return String
 */
function send_affiliate_validation_mail($affiliate_email_address){
	global $affiliate_id;
	
	if((int)checkDuplicateAffiliateEmail($affiliate_email_address, $affiliate_id)){
		return false;
	}
	if(update_affiliate_customers_email_address($affiliate_email_address)==false){
		return false;
	}

	$t1 = date("mdy");
	srand ((float) microtime() * 10000000);
	$input = array ("A", "B", "C", "D", "E", "F", "G", "H", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
	$rand_keys = array_rand ($input, 3);
	$l1 = $input[$rand_keys[0]];
	$r1 = rand(0,9);
	$l2 = $input[$rand_keys[1]];
	$l3 = $input[$rand_keys[2]];
	$r2 = rand(0,9);
	$validation_code = $l1.$r1.$l2.$l3.$r2.$r2;
	
	//input new code to db
	//tep_db_query('UPDATE `affiliate_affiliate` SET `affiliate_email_address_verified`=0 ,`affiliate_email_address_verifi_code` = "'.$validation_code.'" WHERE `affiliate_email_address` = "'.tep_db_input(tep_db_prepare_input($affiliate_email_address)) .'" LIMIT 1 ;');
	tep_db_query('UPDATE `affiliate_affiliate` SET `affiliate_email_address_verified`=0 ,`affiliate_email_address_verifi_code` = "'.$validation_code.'" , `affiliate_email_address` = "'.tep_db_input(tep_db_prepare_input($affiliate_email_address)) .'" WHERE affiliate_id="'.(int)$affiliate_id.'"  LIMIT 1 ;');
	
	$to_name = '走四方网站联盟用户 ';
	$to_email_address = $affiliate_email_address;
	$email_subject = '走四方网网站联盟邮箱验证邮件'.' ';
	$from_email_name = STORE_OWNER;
	$from_email_address = STORE_OWNER_EMAIL_ADDRESS;
	$validation_url = HTTP_SERVER.'/affiliate_validation.php?affiliate_id='.$affiliate_id.'&action=inputcode&affiliate_email_address_verifi_code='.$validation_code;
	
	//send mail
	$patterns = array();
	$patterns[0] = '{CustomerName}';
	$patterns[1] = '{images}';
	$patterns[2] = '{HTTP_SERVER}';
	$patterns[3] = '{VCode}';
	$patterns[4] = '{ValidationUrl}';
	$patterns[5] = '{EMAIL}';
	$patterns[6] = '{CONFORMATION_EMAIL_FOOTER}';
	
	$replacements = array();
	$replacements[0] = $to_name;
	$replacements[1] = HTTP_SERVER.'/email_tpl/images';
	$replacements[2] = HTTP_SERVER;
	$replacements[3] = $validation_code;
	$replacements[4] = $validation_url;
	$replacements[5] = $to_email_address;
	$replacements[6] = nl2br(CONFORMATION_EMAIL_FOOTER);
	
	$email_tpl = file_get_contents(DIR_FS_CATALOG.'email_tpl/header.tpl.html');
	$email_tpl.= file_get_contents(DIR_FS_CATALOG.'email_tpl/affiliate_validation_code.tpl.html');
	$email_tpl.= file_get_contents(DIR_FS_CATALOG.'email_tpl/footer.tpl.html');
	
	$email_text = str_replace( $patterns ,$replacements, $email_tpl). email_track_code('affiliate_validation_code',$to_email_address);
	$email_text = preg_replace('/[[:space:]]+/',' ',$email_text);
	
	return tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address, 'true', 'gb2312');
}

/**
 * 检查是否已经验证过affiliate，否则到申请页
 * @author Howard
 */
function checkAffiliateVerified(){
	global $messageStack;
	$affiliate_verified = $_SESSION['affiliate_verified'];
	if(!(int)$affiliate_verified){
		$messageStack->add_session('affiliate_my_info', "请在下面填写申请资料才能使用网站联盟的功能！", 'error');
		tep_redirect(tep_href_link('affiliate_my_info.php', '', 'SSL'));
	}
}

?>