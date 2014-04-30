<?php
/*
  $Id: account.php,v 1.1.1.1 2004/03/04 23:37:52 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
	if(tep_not_null($_COOKIE['LoginDate'])){
		$messageStack->add_session('login', LOGIN_OVERTIME);
		setcookie('LoginDate', '');
	}
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  require(DIR_FS_LANGUAGES . $language . '/my_favorites.php');

  $breadcrumb->add(db_to_html('我的收藏'), tep_href_link('my_favorites.php', '', 'SSL'));

	$favorites_query_str = 'SELECT cf.*, p.*, pd.products_name FROM `customers_favorites` cf, '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd WHERE customers_id ="'.(int)$customer_id.'" and pd.products_id = cf.products_id and cf.products_id=p.products_id and p.products_status="1" and pd.language_id="'.(int)$languages_id.'" Group BY p.products_id ORDER BY customers_favorites_id DESC ';
  	$favorites_split = new splitPageResults($favorites_query_str, 20);
	$favorites_query = tep_db_query($favorites_split->sql_query);
	while($favorites = tep_db_fetch_array($favorites_query)) {
		
		$tour_agency_opr_currency = tep_get_tour_agency_operate_currency($favorites['products_id']);
		if ($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != '') {
			$favorites['products_price'] = tep_get_tour_price_in_usd($favorites['products_price'], $tour_agency_opr_currency);
		}
		
		if (tep_get_products_special_price($favorites['products_id'])){
			$price_text = '<del>' .  $currencies->display_price($favorites['products_price'], tep_get_tax_rate($favorites['products_tax_class_id'])) . '</del> <b>' . $currencies->display_price(tep_get_products_special_price($favorites['products_id']), tep_get_tax_rate($favorites['products_tax_class_id'])) . '</b>';
		}else{
			$price_text = '<b>'.$currencies->display_price($favorites['products_price'], tep_get_tax_rate($favorites['products_tax_class_id'])).'</b>';
		}
		
		$wh = getimgHW3hw(DIR_FS_CATALOG . 'images/' . $products_rows['products_image'],95,65,true);
		
	
		
		//满意度
	$_rating = tep_get_products_rating($favorites['products_id']);
	$rt = ($_rating['rating_total_avg'] ? number_format($_rating['rating_total_avg'],0):'100').'%';
	//产品评论数
	$reviews = (int)get_product_reviews_num($favorites['products_id']);
	
		$FavoritesRows[] = array('img_w'=>$wh['width'],
								 'img_h'=>$wh['height'],
								 'satisfaction' => $rt,
								 'reviews' => $reviews,
								 'id'=>$favorites['customers_favorites_id'],
								 'p_image' => $favorites['products_image'],
								 'p_id'=>$favorites['products_id'], 
								 'p_name'=>db_to_html($favorites['products_name']),
								 'p_price'=>$price_text,
								 'p_is_transfer'=>$favorites['is_transfer'],
								 'p_is_cruises'=>$favorites['is_cruises'],
								 'href'=> tep_href_link('product_info.php','products_id='.$favorites['products_id']),
								 'have_room'=>$favorites['display_room_option'],
								 'guest_num'=>max(1,$favorites['min_num_guest']));
	}

	$shows['heading'] = db_to_html('我收藏的行程['.intval($favorites_split->number_of_rows).']');
	$shows['title']['name'] = db_to_html('线路名称');
	$shows['title']['price'] = db_to_html('线路价格');
	$shows['title']['action'] = db_to_html('操作');
	$shows['rows_num'] = db_to_html('总记录数：').$favorites_split->number_of_rows;
	$shows['pages_num'] = db_to_html("总页数 ").$favorites_split->number_of_pages;
	$shows['current_page'] = $favorites_split->current_page_number;
	$shows['page_links'] = TEXT_RESULT_PAGE . ' ' . $favorites_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info')));
	$shows['del'] = db_to_html("删 除");
	$shows['add_to_cart'] = db_to_html("立即购买");
	$shows['cart_0'] = db_to_html('行程“');
	$shows['cart_1'] = db_to_html('”已经放入购物车。');
	$shows['cart_2'] = db_to_html('购物车中已有');
	$shows['cart_3'] = db_to_html('个行程 共计：');
	$shows['cart_4'] = tep_href_link('shopping_cart.php','');
	$shows['cart_5'] = db_to_html('进入购物车');
	$shows['cart_6'] = db_to_html('继续购物');
	$shows['view_product'] = db_to_html('产品详情');
	$shows['reviews'] = db_to_html('满意度：');
	$shows['comment'] = db_to_html('条评论');
	
	

  $content = 'my_favorites';
  $javascript = $content . '.js.php';
  
  $is_my_account = true;

  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');

?>