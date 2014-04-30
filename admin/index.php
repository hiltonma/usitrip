<?php
/*
  $Id: index.php,v 1.2 2003/09/24 15:18:15 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

    require('includes/application_top.php');
    require('includes/classes/salestrack.php');
    auto_cancelled_orders_for_days();
	orders_pay_note();
	
	$salestrack = new salestrack;
	//计算订单归属 （速度慢。。。。）
	$salestrack->calc_order_owner_batch();

//amit added temp start
/*
//orders_id in ('1095', '1096', '1097', '1100', '1104', '1105', '1143', '1144')
$order_id = '1144';

  tep_db_query("delete from " . TABLE_ORDERS . " where orders_id = '" . (int)$order_id . "'");
    tep_db_query("delete from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$order_id . "'");
    tep_db_query("delete from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . (int)$order_id . "'");
    tep_db_query("delete from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . (int)$order_id . "'");
    tep_db_query("delete from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$order_id . "'");
    //amit added to remove other related orders start
    tep_db_query("delete from " . TABLE_ORDERS_PRODUCTS_ETICKET . " where orders_id = '" . (int)$order_id . "'");
    tep_db_query("delete from " . TABLE_ORDERS_PRODUCTS_FLIGHT . " where orders_id = '" . (int)$order_id . "'");
echo "done"	;
exit;

$stores_done_ids = '';

 //     $stores_coupons_query = tep_db_query("select o.orders_id from " . TABLE_ORDERS . " as o, " . TABLE_ORDERS_TOTAL . " as ot where ot.orders_id = o.orders_id and ot.class = 'ot_total' and o.orders_status = '1' order by o.orders_id");
$stores_coupons_query = tep_db_query("select orders_id from " . TABLE_ORDERS . "  where  orders_status = '1' order by orders_id");

      while($stores_coupons = tep_db_fetch_array($stores_coupons_query)){
      $stores_done_ids .= "'" . (int)$stores_coupons['orders_id'] . "', ";
    
      }
echo  $stores_done_ids = substr($stores_done_ids, 0, -2);
*/
//amit added temp end

        $template_id_select_query = tep_db_query("select template_id from " . TABLE_TEMPLATE . "  where template_name = '" . DEFAULT_TEMPLATE . "'");
$template_id_select =  tep_db_fetch_array($template_id_select_query);

  $cat = array(
//Admin begin
               array('title' => BOX_HEADING_MY_ACCOUNT,
                     'access' => tep_admin_check_boxes('admin_members.php'),
                     'image' => 'my_account.gif',
                     'href' => tep_href_link(FILENAME_ADMIN_ACCOUNT, 'selected_box=administrator'),
                     'cols' => 1,
                     'children' => array(array('title' => HEADER_TITLE_ACCOUNT, 'link' => tep_href_link(FILENAME_ADMIN_ACCOUNT, 'selected_box=administrator')),
                                         array('title' => HEADER_TITLE_LOGOFF, 'link' => tep_href_link(FILENAME_LOGOFF)))),


               array('title' => BOX_HEADING_CATALOG,
                     'access' => tep_admin_check_boxes('catalog.php'),
                     'image' => 'catalog.gif',
                     'href' => tep_href_link(FILENAME_CATEGORIES, 'selected_box=catalog'),
                     'cols' => 11,
                     'children' => array(array('title' => BOX_CATALOG_CATEGORIES_PRODUCTS, 'link' => tep_href_link(FILENAME_CATEGORIES, 'selected_box=catalog')),
                                         array('title' => BOX_CATALOG_CATEGORIES_PRODUCTS_ATTRIBUTES, 'link' => tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'selected_box=catalog')),
                                         array('title' => BOX_CATALOG_MANUFACTURERS, 'link' => tep_href_link(FILENAME_MANUFACTURERS, 'selected_box=catalog')),
                                         array('title' => BOX_CATALOG_REVIEWS, 'link' => tep_href_link(FILENAME_REVIEWS, 'selected_box=catalog')),
                                         array('title' => BOX_CATALOG_PHOTOS, 'link' => tep_href_link(FILENAME_TRAVELER_PHOTOS, 'selected_box=catalog')),
                                         array('title' => BOX_CATALOG_EASYPOPULATE, 'link' => tep_href_link(FILENAME_EASYPOPULATE, 'selected_box=catalog')),
                                         array('title' => BOX_CATALOG_EASYPOPULATE_BASIC, 'link' => tep_href_link(FILENAME_EASYPOPULATE_BASIC, 'selected_box=catalog')),
                                         array('title' => BOX_CATALOG_SPECIALS, 'link' => tep_href_link(FILENAME_SPECIALS, 'selected_box=catalog')),
                                         array('title' => BOX_CATALOG_SHOP_BY_PRICE, 'link' => tep_href_link(FILENAME_SHOPBYPRICE, 'selected_box=catalog')),
                                         array('title' => BOX_CATALOG_XSELL_PRODUCTS, 'link' => tep_href_link(FILENAME_XSELL_PRODUCTS, 'selected_box=catalog')),
                                         array('title' => BOX_CATALOG_SALEMAKER, 'link' => tep_href_link(FILENAME_SALEMAKER, 'selected_box=catalog')),
                                         array('title' => BOX_CATALOG_FEATURED, 'link' => tep_href_link(FILENAME_FEATURED, 'selected_box=catalog')),    
                                         array('title' => BOX_CATALOG_TRAVEL_AGENCY, 'link' => tep_href_link(FILENAME_TRAVEL_AGENCY, 'selected_box=catalog')),                                               									
                                         array('title' => BOX_CATALOG_TOUR_PROVIDER_REGIONS, 'link' => tep_href_link(FILENAME_TOUR_PROVIDER_REGIONS, 'selected_box=catalog')),
                                         array('title' => BOX_PRODUCT_CATEGORY_TYPE, 'link' => tep_href_link(FILENAME_PRODUCT_CATEGORY_TYPE, 'selected_box=catalog')),
                                         array('title' => BOX_CATALOG_PRODUCTS_EXPECTED, 'link' => tep_href_link(FILENAME_PRODUCTS_EXPECTED, 'selected_box=catalog')), 
                                         array('title' => BOX_TOUR_CODE_DECODE, 'link' => tep_href_link(FILENAME_TOUR_CODE_DECODE, 'selected_box=catalog')),					
                                         array('title' => BOX_LANDING_PAGES, 'link' => tep_href_link(FILENAME_LANDING_PAGES, 'selected_box=catalog')),				
                                         array('title' => BOX_HOTEL_ADMIN, 'link' => tep_href_link(FILENAME_HOTEL_ADMIN)),				
                                         array('title' => '结伴同行', 'link' => tep_href_link('travel_companion.php')),				
                                         array('title' => '双人折扣团管理', 'link' => tep_href_link('double_room_preferences.php')),				
                                         array('title' => '买二送一（二）管理', 'link' => tep_href_link('buy_two_get_one.php')),	
                                          array('title' => '走四方网会员积分卡管理', 'link' => tep_href_link('frame.php')),				
                                         )),


               array('title' => BOX_HEADING_CUSTOMERS,
                     'access' => tep_admin_check_boxes('customers.php'),
                     'image' => 'customers.gif',
                     'href' => tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers'),
             'cols' => 5,
                     'children' => array(array('title' => BOX_CUSTOMERS_CUSTOMERS, 'link' => tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers')),
                             array('title' => 'Orders Fast', 'link' => tep_href_link('orders_fast.php', 'selected_box=customers')),
                             array('title' => BOX_CUSTOMERS_ORDERS, 'link' => tep_href_link(FILENAME_ORDERS, 'selected_box=customers')),
                             array('title' => BOX_CREATE_ACCOUNT, 'link' => tep_href_link(FILENAME_CREATE_ACCOUNT, 'selected_box=customers')),
                             array('title' => BOX_CREATE_ORDER, 'link' => tep_href_link(FILENAME_CREATE_ORDER, 'selected_box=customers')),
                             array('title' => BOX_CUSTOMERS_LEADS, 'link' => tep_href_link(FILENAME_TOUR_LEAD_QUESTION, 'selected_box=customers')),
                             array('title' => BOX_CATALOG_QUESTION_ANSWERS, 'link' => tep_href_link(FILENAME_QUESTION_ANSWERS, 'selected_box=customers')),
                             //array('title' => BOX_CUSTOMERS_ORDERS_CHECK, 'link' => tep_href_link(FILENAME_ORDERS_CHECK, 'selected_box=customers')),
                             array('title' => BOX_CUSTOMERS_PAYPAL, 'link' => tep_href_link(FILENAME_PAYPAL, 'selected_box=customers')),
                             array('title' => BOX_CREATE_ORDERS_ADMIN, 'link' => tep_href_link(FILENAME_CREATE_ORDERS_ADMIN, 'selected_box=customers')),
                              array('title' => '客户自定义服务需求', 'link' => tep_href_link('customer_service_request.php', 'selected_box=customers'))
                             )),
							   array('title' => BOX_HEADING_CUSTOMERS,
                     'access' => tep_admin_check_boxes('customers.php'),
                     'image' => 'customers.gif',
                     'href' => tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers'),
             'cols' => 5,
                     'children' => array(array('title' => BOX_CUSTOMERS_CUSTOMERS, 'link' => tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers')),
                             array('title' => 'Orders Fast', 'link' => tep_href_link('orders_fast.php', 'selected_box=customers')),
                             array('title' => BOX_CUSTOMERS_ORDERS, 'link' => tep_href_link(FILENAME_ORDERS, 'selected_box=customers')),
                             array('title' => BOX_CREATE_ACCOUNT, 'link' => tep_href_link(FILENAME_CREATE_ACCOUNT, 'selected_box=customers')),
                             array('title' => BOX_CREATE_ORDER, 'link' => tep_href_link(FILENAME_CREATE_ORDER, 'selected_box=customers')),
                             array('title' => BOX_CUSTOMERS_LEADS, 'link' => tep_href_link(FILENAME_TOUR_LEAD_QUESTION, 'selected_box=customers')),
                             array('title' => BOX_CATALOG_QUESTION_ANSWERS, 'link' => tep_href_link(FILENAME_QUESTION_ANSWERS, 'selected_box=customers')),
                             //array('title' => BOX_CUSTOMERS_ORDERS_CHECK, 'link' => tep_href_link(FILENAME_ORDERS_CHECK, 'selected_box=customers')),
                             array('title' => BOX_CUSTOMERS_PAYPAL, 'link' => tep_href_link(FILENAME_PAYPAL, 'selected_box=customers')),
                             array('title' => BOX_CREATE_ORDERS_ADMIN, 'link' => tep_href_link(FILENAME_CREATE_ORDERS_ADMIN, 'selected_box=customers')),
                              array('title' => '客户自定义服务需求', 'link' => tep_href_link('customer_service_request.php', 'selected_box=customers'))
                             )),
			array('title'=>BOX_HEADING_QUESTION,
				  'href'=>''
			),
             array('title' => BOX_HEADING_REPORTS,
                     'access' => tep_admin_check_boxes('reports.php'),
                     'image' => 'reports.gif',
                     'cols' => 13,
                     'href' => tep_href_link(FILENAME_STATS_PRODUCTS_PURCHASED, 'selected_box=reports'),
                     'children' => array(array('title' => BOX_REPORTS_PRODUCTS_VIEWED, 'link' => tep_href_link(FILENAME_STATS_PRODUCTS_VIEWED, 'selected_box=reports')),
                                         array('title' => BOX_REPORTS_MONTHLY_SALES, 'link' => tep_href_link(FILENAME_STATS_MONTHLY_SALES, 'selected_box=reports')),
                                         array('title' => BOX_REPORTS_PRODUCTS_PURCHASED, 'link' => tep_href_link(FILENAME_STATS_PRODUCTS_PURCHASED, 'selected_box=reports')),
                                         array('title' => BOX_REPORTS_ORDERS_TOTAL, 'link' => tep_href_link(FILENAME_STATS_CUSTOMERS, 'selected_box=reports')),
                                         array('title' => BOX_CUSTOMER_ORDER_ANALYSIS, 'link' => tep_href_link(FILENAME_STATS_ORDER_ANALYSIS, 'selected_box=reports')),
                                         /*array('title' => BOX_REPORTS_SALES_REPORT, 'link' => tep_href_link(FILENAME_STATS_SALES_REPORT, 'selected_box=reports')),*/
                                         array('title' => BOX_REPORTS_SALES_REPORT2, 'link' => tep_href_link(FILENAME_STATS_SALES_REPORT2, 'selected_box=reports')),
                                         array('title' => BOX_REPORTS_SALES_CSV, 'link' => tep_href_link(FILENAME_STATS_SALES_REPORT2_GRAPH, 'selected_box=reports')),
                                         array('title' => BOX_REPORTS_AD_RESULTS, 'link' => tep_href_link(FILENAME_STATS_AD_RESULTS_DETAILS, 'selected_box=reports')),
                                         array('title' => BOX_REPORTS_AD_RESULTS_MEDIUM, 'link' => tep_href_link(FILENAME_STATS_AD_RESULTS_MEDIUM, 'selected_box=reports')),
                                         array('title' => BOX_REPORTS_DETAILD_MONTHLY_SALES, 'link' => tep_href_link(FILENAME_STATS_DETAILED_MONTHLY_SALES, 'selected_box=reports')),
                                         array('title' => BOX_REPORTS_PAID_PAYMENT_HISTORY, 'link' => tep_href_link(FILENAME_STATS_PAID_PAYMENT_ORDER_HISTORY_NEW, 'selected_box=reports')),
                                         array('title' => BOX_REPORTS_INVOICE_AMOUNT_MISMATCH, 'link' => tep_href_link(FILENAME_STATS_INVOICE_AMOUNT_MISMATCH, 'selected_box=reports')),
                                         array('title' => BOX_REPORTS_WITHOUT_INVOICE_AMOUNT, 'link' => tep_href_link(FILENAME_STATS_WITHOUT_INVOICE_AMOUNT, 'selected_box=reports')),
                                         array('title' => BOX_REPORTS_CUSTOMERS_REP_ORDERS, 'link' => tep_href_link(FILENAME_CUSTOMERS_REP_ORDERS, 'selected_box=reports')),
                                         array('title' => BOX_CUSTOMERS_REPEAT_ORDERSM, 'link' => tep_href_link(FILENAME_CUSTOMERS_REPEAT_ORDERS, 'selected_box=reports')),
                                         array('title' => BOX_REPORTS_PAYMENT_ADJUSTED_ORDER_HISTORY, 'link' => tep_href_link(FILENAME_STATS_PAYMENT_ADJUSTED_ORDER_HISTORY, 'selected_box=reports')),
                                         array('title' => BOX_REPORTS_CANCELLED_ORDERS, 'link' => tep_href_link(FILENAME_CANCELLED_ORDERS, 'selected_box=reports')),
                                         array('title' => BOX_REPORT_ORDERS_TOURS_WITHOUT_COST, 'link' => tep_href_link(FILENAME_STATS_ORDER_ITEM_WITHOUT_AMOUNT, 'selected_box=reports')),
                                         array('title' => BOX_REPORT_DEPARTURE_CITIES, 'link' => tep_href_link(FILENAME_REPORT_DEPARTURE_CITIES, 'selected_box=reports')),
                                         array('title' => BOX_REPORT_TRAVELERS_BY_PROVIDER, 'link' => tep_href_link(FILENAME_REPORT_TRAVELERS_BY_PROVIDER, 'selected_box=reports')),
                                         array('title' => BOX_REPORT_SALES_BY_CATEGORY, 'link' => tep_href_link(FILENAME_STATS_SALES_BY_CATEGORY, 'selected_box=reports')),
                                         array('title' => BOX_REPORT_SALES_BY_DURATTION, 'link' => tep_href_link(FILENAME_STATS_SALES_BY_DURATIONS, 'selected_box=reports')),
                                         array('title' => 'Workload Report', 'link' => tep_href_link('stats_csc.php', 'selected_box=reports'))
                                         )),

  
             array('title' => BOX_HEADING_TOOLS,
                     'access' => tep_admin_check_boxes('tools.php'),
                     'image' => 'tools.gif',
                     'href' => tep_href_link(FILENAME_BACKUP, 'selected_box=tools'),
                     'cols' => 7,
                     'children' => array(array('title' => TOOLS_BACKUP, 'link' => tep_href_link(FILENAME_BACKUP_MYSQL, 'selected_box=tools')),
                                        // array('title' => TOOLS_FILE_MANAGER, 'link' => tep_href_link(FILENAME_FILE_MANAGER, 'selected_box=tools')),
                                         array('title' => TOOLS_BANNERS, 'link' => tep_href_link(FILENAME_BANNER_MANAGER, 'selected_box=tools')),
                                         array('title' => TOOLS_CACHE, 'link' => tep_href_link(FILENAME_CACHE, 'selected_box=tools')),
                                         array('title' => TOOLS_DEFINE_LANGUAGES, 'link' => tep_href_link(FILENAME_DEFINE_LANGUAGE, 'selected_box=tools')),
                                         array('title' => TOOLS_EMAIL, 'link' => tep_href_link(FILENAME_MAIL, 'selected_box=tools')),
                                         array('title' => BOX_TOOLS_NEWSLETTER_MANAGER, 'link' => tep_href_link(FILENAME_NEWSLETTERS, 'selected_box=tools')),
                                         array('title' => TOOLS_SERVER_INFO, 'link' => tep_href_link(FILENAME_SERVER_INFO, 'selected_box=tools')),
                                         array('title' => TOOLS_WHOS_ONLINE, 'link' => tep_href_link(FILENAME_WHOS_ONLINE, 'selected_box=tools')),
                                         array('title' => BOX_TOOLS_KEYWORDS, 'link' => tep_href_link(FILENAME_KEYWORDS, 'selected_box=tools')),
                                         array('title' => BOX_TOOLS_BACKUP, 'link' => tep_href_link(FILENAME_BACKUP, 'selected_box=tools')),
                                         array('title' => BOX_INFORMATION_MANAGER, 'link' => tep_href_link(FILENAME_INFORMATION_MANAGER, 'selected_box=tools')),
                                         array('title' => BOX_TOOLS_FILE_MANAGER, 'link' => tep_href_link(FILENAME_FILE_MANAGER, 'selected_box=tools')),
                                         array('title' => TOURS_TOTE_SYSTEM, 'link' => tep_href_link('vote_system.php')),
                                         array('title' => EMAIL_TRACK_SYSTEM, 'link' => tep_href_link('email_track.php')),
                                         array('title' => DB_UPDATE_SYSTEM, 'link' => tep_href_link('update_data.php'))
                                         )),



array('title' => BOX_HEADING_AFFILIATE,
      'access' => tep_admin_check_boxes('affiliate.php'),
                     'image' => 'affiliate.gif',
                     'cols' => 5,
                     'href' => tep_href_link(FILENAME_AFFILIATE_SUMMARY, 'selected_box=affiliate'),
                     'children' => array(array('title' => BOX_AFFILIATE_SUMMARY, 'link' => tep_href_link(FILENAME_AFFILIATE_SUMMARY, 'selected_box=affiliate')),
                                         array('title' => BOX_AFFILIATE, 'link' => tep_href_link(FILENAME_AFFILIATE, 'selected_box=affiliate')),
                                         array('title' => BOX_AFFILIATE_PAYMENT, 'link' => tep_href_link(FILENAME_AFFILIATE_PAYMENT, 'selected_box=affiliate')),
                                         array('title' => BOX_AFFILIATE_SALES, 'link' => tep_href_link(FILENAME_AFFILIATE_SALES, 'selected_box=affiliate')),
                                         array('title' => BOX_AFFILIATE_CLICKS, 'link' => tep_href_link(FILENAME_AFFILIATE_CLICKS, 'selected_box=affiliate')),
                                         array('title' => BOX_AFFILIATE_BANNERS, 'link' => tep_href_link(FILENAME_AFFILIATE_BANNER_MANAGER, 'selected_box=affiliate')),
                                         array('title' => BOX_AFFILIATE_CONTACT, 'link' => tep_href_link(FILENAME_AFFILIATE_CONTACT, 'selected_box=affiliate')),
                                         array('title' => BOX_AFFILIATE_BANNERS, 'link' => tep_href_link(FILENAME_AFFILIATE_BANNER_GROUPS, 'selected_box=affiliate')),
                                         array('title' => BOX_AFFILIATE_NEWS, 'link' => tep_href_link(FILENAME_AFFILIATE_NEWS, 'selected_box=affiliate')),
                                                     
                                   
                                   )),

// added for faqdesk
    /*array('title' => BOX_HEADING_FAQDESK,
        'access' => tep_admin_check_boxes('faqdesk.php'),
        'image' => 'faq.gif',
        'cols' => 4,
        'href' => tep_href_link(FILENAME_FAQDESK, 'selected_box=faqdesk'),
        'children' => array(array('title' => 'Faqdesk Mgmt', 'link' => tep_href_link(FILENAME_FAQDESK, 'selected_box=faqdesk')),
            array('title' => 'Reviews Mgmt','link' => tep_href_link(FILENAME_FAQDESK_REVIEWS, 'selected_box=faqdesk')),
            array('title' => 'Listing Settings','link' => tep_href_link(FILENAME_FAQDESK_CONFIGURATION, 'gID=1')),
            array('title' => 'FrontPage Settings','link' => tep_href_link(FILENAME_FAQDESK_CONFIGURATION, 'gID=2')),
            array('title' => 'Reviews Settings','link' => tep_href_link(FILENAME_FAQDESK_CONFIGURATION, 'gID=3')),
            array('title' => 'Sticky Settings','link' => tep_href_link(FILENAME_FAQDESK_CONFIGURATION, 'gID=4')),
            array( 'title' => 'Other Settings','link' => tep_href_link(FILENAME_FAQDESK_CONFIGURATION, 'gID=5')),
            )),*/
            
            array('title' => BOX_HEADING_FAQDESK,
        'access' => tep_admin_check_boxes(FILENAME_FAQ_CATEGORIES),
        'image' => 'faq.gif',
        'cols' => 4,
        'href' => tep_href_link(FILENAME_FAQ_CATEGORIES, 'selected_box=faq'),
        'children' => array(array('title' => BOX_FAQ_CATEGORIES, 'link' => tep_href_link(FILENAME_FAQ_CATEGORIES, 'selected_box=faq')),
            /*array('title' => 'Reviews Mgmt','link' => tep_href_link(FILENAME_FAQDESK_REVIEWS, 'selected_box=faqdesk')),
            array('title' => 'Listing Settings','link' => tep_href_link(FILENAME_FAQDESK_CONFIGURATION, 'gID=1')),
            array('title' => 'FrontPage Settings','link' => tep_href_link(FILENAME_FAQDESK_CONFIGURATION, 'gID=2')),
            array('title' => 'Reviews Settings','link' => tep_href_link(FILENAME_FAQDESK_CONFIGURATION, 'gID=3')),
            array('title' => 'Sticky Settings','link' => tep_href_link(FILENAME_FAQDESK_CONFIGURATION, 'gID=4')),
            array( 'title' => 'Other Settings','link' => tep_href_link(FILENAME_FAQDESK_CONFIGURATION, 'gID=5')),*/
            )),
//end of faqdesk

// added gv
    array('title' => BOX_HEADING_GV,
        'access' => tep_admin_check_boxes('gv_admin.php'),
        'image' => 'gift.gif',
        'cols' => 2,
        'href' => tep_href_link(FILENAME_COUPON_ADMIN, 'selected_box=gv_admin'),
        'children' => array(array('title' => GV_COUPON_ADMIN,'link' => tep_href_link(FILENAME_COUPON_ADMIN, 'selected_box=gv_admin')),
                            array('title' => GV_EMAIL,'link' => tep_href_link(FILENAME_GV_MAIL, 'selected_box=gv_admin')),
                            array('title' => GV_QUEUE,'link' => tep_href_link(FILENAME_GV_QUEUE, 'selected_box=gv_admin')),
                            array('title' => GV_SENT,'link' => tep_href_link(FILENAME_GV_SENT, 'selected_box=gv_admin')))),
//end of gv
// added for newsdesk
    array('title' => BOX_HEADING_NEWSDESK,
          'access' => tep_admin_check_boxes('newsdesk.php'),
          'image' => 'news.gif',
          'cols' => 3,
          'href' => tep_href_link(FILENAME_NEWSDESK, 'selected_box=newsdesk'),
          'children' => array(
                        array('title' => 'Articles Mgmt','link' => tep_href_link(FILENAME_NEWSDESK, 'selected_box=newsdesk')),
                        array('title' => 'Reviews Mgmt','link' => tep_href_link(FILENAME_NEWSDESK_REVIEWS, 'selected_box=newsdesk')),
                        array('title' => 'Listing Settings','link' => tep_href_link(FILENAME_NEWSDESK_CONFIGURATION, 'gID=1')),
                        array('title' => 'FrontPage Settings','link' => tep_href_link(FILENAME_NEWSDESK_CONFIGURATION, 'gID=2')),
                        array('title' => 'Reviews Settings','link' => tep_href_link(FILENAME_NEWSDESK_CONFIGURATION, 'gID=3')),
                        array('title' => 'Sticky Settings','link' => tep_href_link(FILENAME_NEWSDESK_CONFIGURATION, 'gID=4')),
                        )),
//end of newsdesk

               array('title' => BOX_HEADING_CONFIGURATION,
                     'access' => tep_admin_check_boxes('configuration.php'),
                     'image' => 'configuration.gif',
                     'href' => tep_href_link(FILENAME_CONFIGURATION, 'selected_box=configuration&gID=1'),
                     'cols' => 10,
                     'children' => array(array('title' => BOX_CONFIGURATION_MYSTORE, 'link' => tep_href_link(FILENAME_CONFIGURATION, 'selected_box=configuration&gID=1')),
                                         array('title' => BOX_CONFIGURATION_MIN_VALUES, 'link' => tep_href_link(FILENAME_CONFIGURATION, 'selected_box=configuration&gID=2')),
                                         array('title' => BOX_CONFIGURATION_MAX_VALUES, 'link' => tep_href_link(FILENAME_CONFIGURATION, 'selected_box=configuration&gID=3')),
                                         array('title' => BOX_CONFIGURATION_IMAGES, 'link' => tep_href_link(FILENAME_CONFIGURATION, 'selected_box=configuration&gID=4')),
                                         array('title' => BOX_CONFIGURATION_CUSTOMER_DETAILS, 'link' => tep_href_link(FILENAME_CONFIGURATION, 'selected_box=configuration&gID=5')),
                                         array('title' => BOX_CONFIGURATION_SHIPPING, 'link' => tep_href_link(FILENAME_CONFIGURATION, 'selected_box=configuration&gID=7')),
                                         array('title' => BOX_CONFIGURATION_PRODUCT_LISTING, 'link' => tep_href_link(FILENAME_CONFIGURATION, 'selected_box=configuration&gID=8')),
                                         array('title' => BOX_CONFIGURATION_STOCK, 'link' => tep_href_link(FILENAME_CONFIGURATION, 'selected_box=configuration&gID=9')),
                                         array('title' => BOX_CONFIGURATION_LOGGING, 'link' => tep_href_link(FILENAME_CONFIGURATION, 'selected_box=configuration&gID=10')),
                                         array('title' => BOX_CONFIGURATION_CACHE, 'link' => tep_href_link(FILENAME_CONFIGURATION, 'selected_box=configuration&gID=11')),
                                         array('title' => BOX_CONFIGURATION_EMAIL, 'link' => tep_href_link(FILENAME_CONFIGURATION, 'selected_box=configuration&gID=12')),
                                         array('title' => BOX_CONFIGURATION_DOWNLOAD, 'link' => tep_href_link(FILENAME_CONFIGURATION, 'selected_box=configuration&gID=13')),
                                         array('title' => BOX_CONFIGURATION_GZIP, 'link' => tep_href_link(FILENAME_CONFIGURATION, 'selected_box=configuration&gID=14')),
                                         array('title' => BOX_CONFIGURATION_SESSIONS, 'link' => tep_href_link(FILENAME_CONFIGURATION, 'selected_box=configuration&gID=15')),
                                         array('title' => BOX_CONFIGURATION_WYSIWYG, 'link' => tep_href_link(FILENAME_CONFIGURATION, 'selected_box=configuration&gID=112')),
                                         array('title' => BOX_CONFIGURATION_AFFILIATE, 'link' => tep_href_link(FILENAME_CONFIGURATION, 'selected_box=configuration&gID=900')),
                                         array('title' => BOX_CONFIGURATION_MAINT, 'link' => tep_href_link(FILENAME_CONFIGURATION, 'selected_box=configuration&gID=16')),
                                         array('title' => BOX_CONFIGURATION_ACCOUNTS, 'link' => tep_href_link(FILENAME_CONFIGURATION, 'selected_box=configuration&gID=40')),
                                         array('title' => BOX_CONFIGURATION_LINKS, 'link' => tep_href_link(FILENAME_CONFIGURATION, 'selected_box=configuration&gID=901')),
                                         )),
//begin taxes  
                array('title' => BOX_HEADING_LOCATION_AND_TAXES,
                     'access' => tep_admin_check_boxes('taxes.php'),
                     'image' => 'location.gif',
                     'href' => tep_href_link(FILENAME_COUNTRIES, 'selected_box=taxes'),
                     'cols' => 4,
                     'children' => array(array('title' => BOX_TAXES_COUNTRIES, 'link' => tep_href_link(FILENAME_COUNTRIES, 'selected_box=taxes')),
                                         array('title' => BOX_TAXES_ZONES, 'link' => tep_href_link(FILENAME_ZONES, 'selected_box=taxes')),
                                         array('title' => BOX_TAXES_GEO_ZONES, 'link' => tep_href_link(FILENAME_GEO_ZONES, 'selected_box=taxes')),
                                         array('title' => BOX_TAXES_TAX_CLASSES, 'link' => tep_href_link(FILENAME_TAX_CLASSES, 'selected_box=taxes')),
                                         array('title' => BOX_TAXES_TAX_RATES, 'link' => tep_href_link(FILENAME_TAX_RATES, 'selected_box=taxes')),
                                         array('title' => BOX_CATALOG_CITIES, 'link' => tep_href_link(FILENAME_CITY, 'selected_box=taxes')),
                                         array('title' => BOX_CATALOG_ATTRACTIONS, 'link' => tep_href_link(FILENAME_CITY, 'selected_box=taxes&type=1')),
                                         array('title' => BOX_CATALOG_REGION, 'link' => tep_href_link(FILENAME_REGIONS, 'selected_box=taxes')),
                                         )),

//begin localization
               array('title' => BOX_HEADING_LOCALIZATION,
                     'access' => tep_admin_check_boxes('localization.php'),
                     'image' => 'localization.gif',
                     'href' => tep_href_link(FILENAME_CURRENCIES, 'selected_box=localization'),
                     'cols' => 2,
                     'children' => array(array('title' => BOX_LOCALIZATION_CURRENCIES, 'link' => tep_href_link(FILENAME_CURRENCIES, 'selected_box=localization')),
                                         array('title' => BOX_LOCALIZATION_LANGUAGES, 'link' => tep_href_link(FILENAME_LANGUAGES, 'selected_box=localization')),
                                         array('title' => BOX_LOCALIZATION_ORDERS_STATUS, 'link' => tep_href_link(FILENAME_ORDERS_STATUS, 'selected_box=localization'))
                                         )),

// Begin modules
               array('title' => BOX_HEADING_MODULES,
                     'access' => tep_admin_check_boxes('modules.php'),
                     'image' => 'modules.gif',
                     'href' => tep_href_link(FILENAME_MODULES, 'selected_box=modules&set=payment'),
                     'cols' => 2,
                     'children' => array(array('title' => BOX_MODULES_PAYMENT, 'link' => tep_href_link(FILENAME_MODULES, 'selected_box=modules&set=payment')),
                                         array('title' => BOX_MODULES_SHIPPING, 'link' => tep_href_link(FILENAME_MODULES, 'selected_box=modules&set=shipping')),
                                         array('title' => BOX_MODULES_ORDER_TOTAL, 'link' => tep_href_link(FILENAME_MODULES, 'selected_box=modules&set=ordertotal'))
                                         )),
//Begin admin
               array('title' => BOX_HEADING_ADMINISTRATOR,
                     'access' => tep_admin_check_boxes('administrator.php'),
                     'image' => 'administrator.gif',
                     'href' => tep_href_link(tep_selected_file('administrator.php'), 'selected_box=administrator'),
                     'cols' => 2,
                     'children' => array(array('title' => 'Member Groups', 'link' => tep_href_link(FILENAME_ADMIN_MEMBERS, 'selected_box=administrator'),
                                               'access' => tep_admin_check_boxes(FILENAME_ADMIN_MEMBERS, 'sub_boxes')),
                                         array('title' => 'Update Account', 'link' => tep_href_link(FILENAME_ADMIN_ACCOUNT, 'selected_box=administrator'),
                                               'access' => tep_admin_check_boxes(FILENAME_ADMIN_FILES, 'sub_boxes')),
                                         array('title' => BOX_ADMINISTRATOR_BOXES, 'link' => tep_href_link(FILENAME_ADMIN_FILES, 'selected_box=administrator'),
                                               'access' => tep_admin_check_boxes(FILENAME_ADMIN_FILES, 'sub_boxes'))
                                               )),
//begin design controls
               array('title' => BOX_HEADING_DESIGN_CONTROLS,
                     'access' => tep_admin_check_boxes('design_controls.php'),
                     'image' => 'design_controls.gif',
                     'href' => tep_href_link(FILENAME_TEMPLATE_CONFIGURATION, 'cID=' . $template_id_select[template_id] . '&selected_box=design_controls'),
                     'cols' => 2,
                     'children' => array(array('title' => BOX_HEADING_TEMPLATE_CONFIGURATION, 'link' => tep_href_link(FILENAME_TEMPLATE_CONFIGURATION, 'cID=' . $template_id_select[template_id] . '&selected_box=design_controls')),
                                         array('title' => BOX_HEADING_BOXES, 'link' => tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'gID=' . $template_id_select[template_id] . '&selected_box=design_controls')),
                                         array('title' => BOX_HEADING_FEATURE_SECTION_A, 'link' => tep_href_link(FILENAME_FEATURE_TOUR_SECTION_A, '&selected_box=design_controls')),
                                         array('title' => BOX_HEADING_FEATURE_SECTION_B, 'link' => tep_href_link(FILENAME_FEATURE_TOUR_SECTION_B, '&selected_box=design_controls')),
                                         array('title' => BOX_HEADING_OTHER_TOUR_SECTION, 'link' => tep_href_link(FILENAME_OTHER_TOUR_SECTION, '&selected_box=design_controls'))						 										 
                                         )),

// added for infosystem
   /* array('title' => BOX_HEADING_INFORMATION,
         'access' => tep_admin_check_boxes('information.php'),
          'image' => 'info.gif',
           'href' => tep_href_link(FILENAME_INFORMATION_MANAGER, 'selected_box=information'),
       'children' => array(array('title' => BOX_INFORMATION_MANAGER,'link' => tep_href_link(FILENAME_INFORMATION_MANAGER, 'selected_box=information')),
                                         array('title' => BOX_CATALOG_DEFINE_MAINPAGE, 'link' => tep_href_link(FILENAME_DEFINE_MAINPAGE, 'selected_box=catalog')),
            )),*/

//end of info system
// added for paypal ipn

    /*array('title' => BOX_HEADING_PAYPALIPN_ADMIN,
         'access' => tep_admin_check_boxes('paypalipn.php'),
        'image' => 'paypalipn.gif',
        'href' => tep_href_link(FILENAME_PAYPAL, 'selected_box=paypalipn'),
        'children' => array(array('title' => BOX_PAYPALIPN_ADMIN_TRANSACTIONS,'link' => tep_href_link(FILENAME_PAYPAL, 'selected_box=paypalipn')),
            array('title' => BOX_PAYPALIPN_ADMIN_TESTS, 'link' => tep_href_link(FILENAME_PAYPAL, 'action=itp'))
            )),*/
//end of paypal ipn


               array('title' => BOX_HEADING_LINKS,
                     'access' => tep_admin_check_boxes('links.php'),
                     'image' => 'links.gif',
                     'href' => tep_href_link(FILENAME_LINKS, 'selected_box=links'),
                     'cols' => 2,
                     'children' => array(array('title' => BOX_LINKS_LINKS, 'link' => tep_href_link(FILENAME_LINKS, 'selected_box=links')),
                                         array('title' => BOX_LINKS_LINK_CATEGORIES, 'link' => tep_href_link(FILENAME_LINK_CATEGORIES, 'selected_box=links')),
                                         array('title' => BOX_LINKS_LINKS_CONTACT, 'link' => tep_href_link(FILENAME_LINKS_CONTACT, 'selected_box=links')))),
                                         
                                         
                array('title' => BOX_HEADING_ARTICLES,
                     'access' => tep_admin_check_boxes(FILENAME_ARTICLES),
                     'image' => 'links.gif',
                     'href' => tep_href_link(FILENAME_ARTICLES, 'selected_box=articles'),
                     'cols' => 3,
                     'children' => array(array('title' => BOX_TOPICS_ARTICLES, 'link' => tep_href_link(FILENAME_ARTICLES, 'selected_box=articles')),
                                         array('title' => BOX_ARTICLES_CONFIG, 'link' => tep_href_link(FILENAME_ARTICLES_CONFIG, 'selected_box=articles')),
                                         array('title' => BOX_ARTICLES_AUTHORS, 'link' => tep_href_link(FILENAME_AUTHORS, 'selected_box=articles')),
                                         array('title' => BOX_ARTICLES_REVIEWS, 'link' => tep_href_link(FILENAME_ARTICLE_REVIEWS, 'selected_box=articles')), 
                                         array('title' => BOX_ARTICLES_XSELL, 'link' => tep_href_link(FILENAME_ARTICLES_XSELL, 'selected_box=articles'))
                                         )),
                                         
                array('title' => BOX_HEADING_REWARDS4FUN,
                     'access' => tep_admin_check_boxes(FILENAME_CUSTOMERS_POINTS),
                     'image' => 'money.gif',
                     'href' => tep_href_link(FILENAME_REWARDS4FUN_SUMMARY, 'selected_box=rewards4fun'),
                     'cols' => 3,
                     'children' => array(array('title' => BOX_REWARDS4FUN_SUMMARY, 'link' => tep_href_link(FILENAME_REWARDS4FUN_SUMMARY, 'selected_box=rewards4fun')),
                                         array('title' => BOX_CATALOG_REVIEWS, 'link' => tep_href_link(FILENAME_REVIEWS, 'selected_box=rewards4fun')),
                                         array('title' => BOX_CATALOG_PHOTOS, 'link' => tep_href_link(FILENAME_TRAVELER_PHOTOS, 'selected_box=rewards4fun')),
                                         array('title' => BOX_CUSTOMERS_POINTS, 'link' => tep_href_link(FILENAME_CUSTOMERS_POINTS, 'selected_box=rewards4fun&filter=2')),
                                        // array('title' => BOX_CUSTOMERS_POINTS_PENDING, 'link' => tep_href_link(FILENAME_CUSTOMERS_POINTS_PENDING, 'selected_box=rewards4fun')), 
                                         array('title' => BOX_CUSTOMERS_POINTS_REFERRAL, 'link' => tep_href_link(FILENAME_CUSTOMERS_POINTS_REFERRAL, 'selected_box=rewards4fun'))
                                         )),

//Admin end
);

  $languages = tep_get_languages();
  $languages_array = array();
  $languages_selected = DEFAULT_LANGUAGE;
  for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
    $languages_array[] = array('id' => $languages[$i]['code'],
                               'text' => $languages[$i]['name']);
    if ($languages[$i]['directory'] == $language) {
      $languages_selected = $languages[$i]['code'];
    }
  }

  
// 弹出“每日必读”提醒层
$login_id = (int)$login_id;
$remind_query = tep_db_query("SELECT * FROM ".TABLE_EVERYONE_TO_READ_REMIND." e,  zhh_system_words w WHERE e.words_id = w.words_id AND e.admin_id=".$login_id." AND e.is_read=0");
//$unreadnum_array = tep_db_fetch_array($remind_query);
//$unread = $unreadnum_array['unreadnum'];
$unread = tep_db_num_rows($remind_query);
// 紧急echo

$adjective_query = tep_db_query("SELECT *  FROM ".TABLE_EVERYONE_TO_READ_REMIND." r, zhh_system_words w  WHERE r.words_id = w.words_id AND  r.admin_id=".$login_id." AND r.is_read=0 AND w.is_adjective=1");
//$adjective_array = tep_db_fetch_array($adjective_query);
//$adjective = $adjective_array['adjective'];
$adjective = tep_db_num_rows($adjective_query);
// 非紧急
$unadjective_query = tep_db_query("SELECT * FROM ".TABLE_EVERYONE_TO_READ_REMIND." r, zhh_system_words w  WHERE r.words_id = w.words_id AND  r.admin_id=".$login_id." AND r.is_read=0 AND w.is_adjective=0");
//$unadjective_array = tep_db_fetch_array($unadjective_query);
//$unadjective = $unadjective_array['adjective'];
$unadjective = tep_db_num_rows($unadjective_query);

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<style type="text/css"><!--
.style1 {color: #FF0000}
a { color:#080381; text-decoration:none; }
a:hover { color:#aabbdd; text-decoration:underline; }
a.text:link, a.text:visited { color: #000000; text-decoration: none; }
a:text:hover { color: #000000; text-decoration: underline; }
a.main:link, a.main:visited { color: #7187BB; text-decoration: none; }
A.main:hover { color: #D3DBFF; text-decoration: underline; }
a.sub:link, a.sub:visited { color: #dddddd; text-decoration: none; }
A.sub:hover { color: #dddddd; text-decoration: underline; }
a:link.headerLink { font-family: Verdana, Arial, sans-serif; font-size:12px; color: #000000; font-weight: bold; text-decoration: none; }
a:visited.headerLink { font-family: Verdana, Arial, sans-serif; font-size:12px; color: #000000; font-weight: bold; text-decoration: none }
a:active.headerLink { font-family: Verdana, Arial, sans-serif; font-size:12px; color: #000000; font-weight: bold; text-decoration: none; }
a:hover.headerLink { font-family: Verdana, Arial, sans-serif; font-size:12px; color: #000000; font-weight: bold; text-decoration: underline; }

#.heading { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 20px; font-weight: bold; line-height: 1.5; color: #000000; }
.heading { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 20px; font-weight: bold; line-height: 1.5; color: #093570; }
.main { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 17px; font-weight: bold; line-height: 1.5; color: #000000; }
.sub { font-family: Verdana, Arial, Helvetica, sans-serif; font-size:12px; font-weight: bold; line-height: 1.5; color: #dddddd; }
.text { font-family: Verdana, Arial, Helvetica, sans-serif; font-size:12px; font-weight: bold; line-height: 1.5; color: #000000; }
#.menuBoxHeading { font-family: Verdana, Arial, Helvetica, sans-serif; font-size:12px; color: #ffffff; font-weight: bold; background-color: #093570; border-color: #093570; border-style: solid; border-width: 1px; }
.menuBoxHeading { font-family: Verdana, Arial, Helvetica, sans-serif; font-size:12px; color: #ffffff; font-weight: bold; background-color: #7187bb; border-color: #7187bb; border-style: solid; border-width: 1px; }
.infoBox { font-family: Verdana, Arial, Helvetica, sans-serif; font-size:12px; color: #080381; background-color: #ffffff; border-color: #7187bb; border-style: solid; border-width: 1px; }
.smallText { font-family: Verdana, Arial, sans-serif; font-size:12px; }

.menusub { font-family: Verdana, Arial, sans-serif; font-size:12px; }

//--></style>

<script type="text/javascript">
//创建ajax对象
var ajax = false;
if(window.XMLHttpRequest) {
     ajax = new XMLHttpRequest();
}
else if (window.ActiveXObject) {
    try {
            ajax = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
    try {
            ajax = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (e) {}
    }
}
if (!ajax) {
    window.alert("<?php echo db_to_html('不能创建XMLHttpRequest对象实例.')?>");
}
</script>

</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<table>
          <tr bgcolor="#ffffff">
            <td colspan="2">
			<?php
			if(0){	//功能菜单页
			?>
			<table border="0" width="100%" height="390" cellspacing="0" cellpadding="2">
              <tr valign="top">
                <td width="140" valign="top"><table border="0" width="140" height="390" cellspacing="0" cellpadding="2">
                  <tr>
                    <td valign="top"><br>
 <?php 
  $heading = array();
  $contents = array();

  $orders_contents = '';
  $orders_status_query = tep_db_query("select orders_status_name, orders_status_id from " . TABLE_ORDERS_STATUS . " where language_id = '" . $languages_id . "'");
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
 
 $orders_pending_query = tep_db_query("select count(*) as count from " . TABLE_ORDERS . " where orders_status = '" . $orders_status['orders_status_id'] . "'");
  
    $orders_pending = tep_db_fetch_array($orders_pending_query);
//Admin begin
    if (tep_admin_check_boxes(FILENAME_ORDERS, 'sub_boxes') == true) {
      $orders_contents .= '<a href="' . tep_href_link(FILENAME_ORDERS, 'selected_box=customers&status=' . $orders_status['orders_status_id']) . '">' . $orders_status['orders_status_name'] . '</a>: ' . $orders_pending['count'] . '<br>';
    } else {
      $orders_contents .= '' . $orders_status['orders_status_name'] . ': ' . $orders_pending['count'] . '<br>';
    }
//Admin end
  }
  $orders_contents = substr($orders_contents, 0, -4);

  $heading = array();
  $contents = array();

  $heading[] = array('params' => 'class="menuBoxHeading"',
                     'text'  => BOX_TITLE_ORDERS);

  $contents[] = array('params' => 'class="infoBox"',
                      'text'  => $orders_contents);

  $box = new box;
  echo $box->menuBox($heading, $contents);

  echo '<br>';

  $customers_query = tep_db_query("select count(*) as count from " . TABLE_CUSTOMERS);
  $customers = tep_db_fetch_array($customers_query);
  $products_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS . " where products_status = '1'");
  $products = tep_db_fetch_array($products_query);
  $reviews_query = tep_db_query("select count(*) as count from " . TABLE_REVIEWS);
  $reviews = tep_db_fetch_array($reviews_query);

  $heading = array();
  $contents = array();

  $heading[] = array('params' => 'class="menuBoxHeading"',
                     'text'  => BOX_TITLE_STATISTICS);

  $contents[] = array('params' => 'class="infoBox"',
                      'text'  => BOX_ENTRY_CUSTOMERS . ' ' . $customers['count'] . '<br>' .
                                 BOX_ENTRY_PRODUCTS . ' ' . $products['count'] . '<br>' .
                                 BOX_ENTRY_REVIEWS . ' ' . $reviews['count']);

  $box = new box;
  echo $box->menuBox($heading, $contents);

  echo '<br>';

  $contents = array();

  if (getenv('HTTPS') == 'on') {
    $size = ((getenv('SSL_CIPHER_ALGKEYSIZE')) ? getenv('SSL_CIPHER_ALGKEYSIZE') . '-bit' : '<i>' . BOX_CONNECTION_UNKNOWN . '</i>');
    $contents[] = array('params' => 'class="infoBox"',
                        'text' => tep_image(DIR_WS_ICONS . 'locked.gif', ICON_LOCKED, '', '', 'align="right"') . sprintf(BOX_CONNECTION_PROTECTED, $size));
  } else {
    $contents[] = array('params' => 'class="infoBox"',
                        'text' => tep_image(DIR_WS_ICONS . 'unlocked.gif', ICON_UNLOCKED, '', '', 'align="right"') . BOX_CONNECTION_UNPROTECTED);
  }

  $box = new box;
  echo $box->tableBlock($contents); 
?> 
                    </td>
                  </tr>
                </table></td>
        <td align="center"><table border="0" width="606" height="390" cellspacing="0" cellpadding="2">
                  <tr>
                    <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                      <tr><?php echo tep_draw_form('languages', 'index.php', '', 'get'); ?>
                        <td class="heading"><?php echo HEADING_TITLE; ?></td>
                        <td align="right">
                        <?php
                        /*关闭语言选择框
                         echo tep_draw_pull_down_menu('language', $languages_array, $languages_selected, 'onChange="this.form.submit();"'); 
                        */
                        ?>
                        </td>
                      </form></tr>
                    </table></td>
                  </tr>
<?php
  $col = 2;
  $counter = 0;
  for ($i = 0, $n = sizeof($cat); $i < $n; $i++) {
    $counter++;
    if ($counter < $col) {
//      echo '                  <tr>' . "\n";
    }

    $cn = ($i >= 9 ? 2 : 1);

    ${'c' . $cn}.= '                    <table border="0" cellspacing="0" cellpadding="2" width="100%">' . "\n" .
         '                      <tr>' . "\n" .
         '                        <td valign="top"><a href="' . $cat[$i]['href'] . '">' . tep_image(DIR_WS_IMAGES . 'categories/' . $cat[$i]['image'], $cat[$i]['title'], '32', '32') . '</a></td>' . "\n" .
         '                        <td width="100%"><table border="0" cellspacing="0" cellpadding="2" width="100%">' . "\n" .
         '                          <tr>' . "\n" .
         '                            <td class="main"><a href="' . $cat[$i]['href'] . '" class="main">' . $cat[$i]['title'] . '</a></td>' . "\n" .
         '                          </tr>' . "\n" .
         '                          <tr>' . "\n" .
         '                            <td class="menusub" width="100%">';

    $children = '';
    $children1 = '';
    $children2 = '';

    for ($j = 0, $k = sizeof($cat[$i]['children']); $j < $k; $j++) {

      if(isset($cat[$i]['cols'])) {
        if($j >= $cat[$i]['cols']) {
          $chn = 2;
        } else {
          $chn = 1;
        }
        ${'children'.$chn} .= '<a href="' . $cat[$i]['children'][$j]['link'] . '" class="menusub">' . $cat[$i]['children'][$j]['title'] . '</a><br>';
      } else {
        $children .= '<a href="' . $cat[$i]['children'][$j]['link'] . '" class="menusub">' . $cat[$i]['children'][$j]['title'] . '</a><br>';
      }
    }
    $children .= '<table width="100%"><tr><td valign="top" width="50%">' . $children1 . '</td><td valign="top" width="50%">' . $children2 . '</td></tr></table>';


    ${'c' . $cn} .= $children;

    ${'c' . $cn} .='</td> ' . "\n" .
         '                          </tr>' . "\n" .
         '                        </table></td>' . "\n" .
         '                      </tr>' . "\n" .
         '                    </table>' . "\n";

    if ($counter >= $col) {
//      echo '                  </tr>' . "\n";
      $counter = 0;
    }
  }
?>

              <tr>
                <td valign="top">
<table width="98%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
                          <tr>
                            <td bgcolor="#F2F4FF" class="heading">Store Management</td>
                          </tr></table>

                <?php
                //echo ${'c1'};
                echo $c1;
                ?>
                </td>
                <td valign="top">
<table width="98%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
                          <tr>
                            <td bgcolor="#F2F4FF" class="heading">Store Setup</td>
                          </tr></table>

                <? echo ${'c2'};?></td>
              </tr>

                </table></td>
              </tr>
            </table>
			<?php
			}else{ echo '<h1>您已登录系统，请选择上方的 说ソ胁僮鳎?/h1>'; }
			?>
			</td>
          </tr>

      </tr>
      </td>

      </tr>
    </table>


<div class="popup" id="popupMap">
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="popupTable">
<tr><td class="topLeft"></td><td class="side"></td><td class="topRight"></td></tr><tr><td class="side"></td><td class="con" style="float:none;margin:0;width:auto;">
  <div class="popupCon" id="popupConMap" style="width:420px;">
    <div class="popupConTop" id="dragMap">
      <h3 style=" padding-left:0px;"><b><?= db_to_html("每日必读");?></b></h3>
      <span onClick="closePopup('popupMap')"></span>      
    </div>
    <div class="success">
        <div class="promptImg"><img src="images/remind.jpg"></div>
        <div class="prompt">您有未读信息<b><?php echo $unread; ?></b>条<br/>其中紧急信息<b><?php echo $adjective; ?></b>条，非紧急信息<b><?php echo $unadjective; ?></b>条。</div>
        <div class="btnCenter">
            <a id="PopupErrorClose" href="zhh_system_index.php" class="btn btnOrange"> 立即阅读 </a>&nbsp;
            <a id="PopupErrorClose" href="orders.php" class="btn btnGrey" style="width:80px"> 稍后阅读 </a>
        </div>
    </div>


</div>
</td><td class="side"></td></tr><tr><td class="botLeft"></td><td class="side"></td><td class="botRight"></td></tr>
</table>
</div>
<div class="popup" id="popupRemind">
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="popupTable">
<tr><td class="topLeft"></td><td class="side"></td><td class="topRight"></td></tr><tr><td class="side"></td><td class="con" style="float:none;margin:0;width:auto;">
  <div class="popupCon" id="popupConRemind" style="width:510px;">
    <div class="popupConTop" id="dragRemind">
      <h3 style=" padding-left:0px;"><b><?= db_to_html("每日必读");?></b></h3>
      <span onClick="closePopup('popupRemind')"></span>      
    </div>
    <div class="success">
        <div class="promptImg"><img src="images/remind.jpg"></div>
        <div class="prompt">您 形炊列畔?b><?php echo $unread; ?></b>条<br/>其中紧急信息<b><?php echo $adjective; ?></b>条，非紧急信息<b><?php echo $unadjective; ?></b>条。</div>
        <div class="btnCenter">
            <a id="PopupErrorClose" href="zhh_system_words_list.php?dir_id=18" class="btn btnOrange"><button type="submit">立即阅读</button></a>
            <a id="PopupErrorClose" href="javascript:;" class="btn btnGrey"><button onClick="closePopup('popupRemind');" type="submit">稍后阅读</button></a>
        </div>
    </div>


</div>
</td><td class="side"></td></tr><tr><td class="botLeft"></td><td class="side"></td><td class="botRight"></td></tr>
</table>
</div>
<style>
.success { padding:20px; }
.success .btnCenter { margin-top:20px; clear: both; height: 25px;    text-align: center; }


.success .prompt { margin-left:90px; height:48px; line-height:24px; }
.success .promptImg { margin-left:10px; float:left; }

.btn {cursor: pointer; display: inline-block; height: 23px;line-height: 23px;overflow: hidden;white-space: nowrap;}

/*橙色button*/
.btnOrange {width: 80px;}
.btnOrange {background: url("/image/button_bg.gif") repeat scroll 0 0 transparent;border: 1px solid #F8B709;  font-weight: bold;}
/*灰色button*/
.btnGrey {width: 80px;}
.btnGrey {background: url("/image/button_bg.gif") repeat scroll 0 -46px transparent;border: 1px solid #E4E4E4;width: auto;}

</style>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="includes/javascript/popup.js"></script>

<script type="text/javascript">
<?php 
if ($unread > 0){
?>
showPopup('popupMap','popupConMap','200','true');
<?php 
}
?>
function showRemind(){
    showPopup('popupRemind','popupConRemind','200','off');
    return false;
}
function getAjax(){try{x=new ActiveXObject('Microsoft.XMLHTTP');}catch(e){try{x=ActiveXObject('MsXml2.XMLHTTP');}catch(e){try{x=new XMLHttpRequest();}catch(e){	aert('此浏览器 恢С諥JAX！');}}}return x;}
function auto_ation_cron(){
    var url = "<?php echo tep_href_link('auto_send_mail_for_cron_ajax.php') ?>";
    ajax.open('GET', url, true);  
    ajax.send(null);
}
function auto_ation_sms_cron(){
    ajax = getAjax();
    var url = "<?php echo tep_href_link('auto_send_sms_for_cron_ajax.php') ?>";
    ajax.open('GET', url, true);  
    ajax.send(null);
}
function auto_ation_greet_sms_cron(){
    ajax = getAjax();
    var url = "<?php echo tep_href_link('auto_send_greet_sms_for_cron_ajax.php') ?>";
    ajax.open('GET', url, true);  
    ajax.send(null);
}
function auto_ation_receive_sms_cron(){
    ajax = getAjax();
    var url = "<?php echo tep_href_link('../auto_send_mail_of_receive_sms_for_cron_ajax.php') ?>";
    ajax.open('GET', url, true);  
    ajax.send(null);
}
auto_ation_cron();
//auto_ation_sms_cron(); 
//auto_ation_greet_sms_cron();
//auto_ation_receive_sms_cron();
</script>


</body>

</html>
