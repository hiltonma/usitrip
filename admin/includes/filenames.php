<?php
/*
  $Id: filenames.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

//Admin begin
  define('FILENAME_ADMIN_ACCOUNT', 'admin_account.php');
  define('FILENAME_ADMIN_FILES', 'admin_files.php');
  define('FILENAME_ADMIN_MEMBERS', 'admin_members.php');
  define('FILENAME_FORBIDEN', 'forbiden.php');
  define('FILENAME_LOGIN', 'login.php');
  define('FILENAME_LOGOFF', 'logoff.php');
  define('FILENAME_PASSWORD_FORGOTTEN', 'password_forgotten.php');
//Admin end

// MaxiDVD Added Line For WYSIWYG HTML Area: BOF
  define('FILENAME_DEFINE_MAINPAGE', 'define_mainpage.php');
// MaxiDVD Added Line For WYSIWYG HTML Area: EOF

// Lango Added Line For Infobox configuration: BOF
  define('FILENAME_TEMPLATE_CONFIGURATION', 'template_configuration.php');
  define('FILENAME_INFOBOX_CONFIGURATION', 'infobox_configuration.php');
  define('FILENAME_INFOBOX_ADMIN', 'infobox_admin.php');
  define('FILENAME_POPUP_INFOBOX_HELP', 'popup_infobox_help.php');
// Lango Added Line For Infobox configuration: EOF

// Lango Added Line For Salemaker Mod: BOF
  define('FILENAME_SALEMAKER', 'salemaker.php');
  define('FILENAME_SALEMAKER_INFO', 'salemaker_info.php');
// Lango Added Line For Salemaker Mod: EOF

// BOF: Lango Added for Featured product MOD
  define('FILENAME_FEATURED', 'featured.php');
// EOF: Lango Added for Featured product MOD

// BOF: Lango Added for Order_edit MOD
  define('FILENAME_CREATE_ACCOUNT', 'create_account.php');
  define('FILENAME_CREATE_ACCOUNT_PROCESS', 'create_account_process.php');
  define('FILENAME_CREATE_ACCOUNT_SUCCESS', 'create_account_success.php');
  define('FILENAME_CREATE_ORDER_PROCESS', 'create_order_process.php');
  define('FILENAME_CREATE_ORDER', 'create_order.php');
  define('FILENAME_EDIT_ORDERS', 'edit_orders.php');
  define('FILENAME_C_ORDERS', 'c_orders.php');
  define('FILENAME_ORDERS_STATUS', 'orders_status.php');
  define('FILENAME_CREATE_ORDERS_ADMIN', 'create_order_admin.php');
// EOF: Lango Added for Order_edit MOD

//JASON 
  define('FILENAME_OFFERS_SMS_NOTIFICATION', 'offers_sms_notification.php');
//JASON

//yichi 
  define('FILENAME_OFFERS_SMS_CONTACT_GUEST', 'offers_sms_contact_guest.php');
//yichi

// BOF: Lango Added for Sales Stats MOD
define('FILENAME_STATS_MONTHLY_SALES', 'stats_monthly_sales.php');
// EOF: Lango Added for Sales Stats MOD

//tools
define('FILENAME_KEYWORDS', 'stats_keywords.php');

// define the filenames used in the project
  define('FILENAME_BACKUP', 'backup.php');
  define('FILENAME_BANNER_MANAGER', 'banner_manager.php');
  define('FILENAME_BANNER_STATISTICS', 'banner_statistics.php');
  define('FILENAME_CACHE', 'cache.php');
  define('FILENAME_CATALOG_ACCOUNT_HISTORY_INFO', 'account_history_info.php');
  define('FILENAME_CATEGORIES', 'categories.php');
  define('FILENAME_REGIONS', 'region.php');
  define('FILENAME_TRAVEL_AGENCY', 'travel_agency.php');
  define('FILENAME_CONFIGURATION', 'configuration.php');
  define('FILENAME_COUNTRIES', 'countries.php');
  define('FILENAME_CURRENCIES', 'currencies.php');
  define('FILENAME_CUSTOMERS', 'customers.php');
  define('FILENAE_INDIVIDUAL_SPACE','manage_individual_space.php');
  define('FILENAME_DOMESTIC','domestic_orders.php'); 
  define('FILENAME_DEFAULT', 'index.php');
  define('FILENAME_DEFINE_LANGUAGE', 'define_language.php');
  define('FILENAME_FILE_MANAGER', 'file_manager.php');
  define('FILENAME_GEO_ZONES', 'geo_zones.php');
  define('FILENAME_LANGUAGES', 'languages.php');
  define('FILENAME_MAIL', 'mail.php');
  define('FILENAME_MANUFACTURERS', 'manufacturers.php');
  define('FILENAME_CITY', 'city.php');  
  define('FILENAME_FEATURE_TOUR_SECTION_A', 'feature_tour_section_a.php');
  define('FILENAME_FEATURE_TOUR_SECTION_B', 'feature_tour_section_b.php');
  define('FILENAME_OTHER_TOUR_SECTION', 'other_tours_section.php');
  define('FILENAME_MODULES', 'modules.php');
  define('FILENAME_NEWSLETTERS', 'newsletters.php');
  define('FILENAME_NEWUI_FRAME','frame.php');
  define('FILENAME_ORDERS', 'orders.php');
  define('FILENAME_ORDERS_AGREE_RETURN', 'orders.php?agret=1');
  define('FILENAME_ORDERS_STATUS','orders_status.php');
  define('FILENAME_ORDERS_INVOICE', 'invoice.php');
  define('FILENAME_ORDERS_PACKINGSLIP', 'packingslip.php');
  define('FILENAME_CREATE_ORDERS_ADMIN', 'orders_status.php');
  define('FILENAME_POPUP_IMAGE', 'popup_image.php');
  define('FILENAME_PRODUCTS_ATTRIBUTES', 'products_attributes_tour_provider.php');
  define('FILENAME_PRODUCTS_EXPECTED', 'products_expected.php');
  define('FILENAME_POINTCARDS_ORDER','orders_pointcards.php');
  define('FILENAME_REVIEWS', 'reviews.php');
  define('FILENAME_SERVER_INFO', 'server_info.php');
  define('FILENAME_SHIPPING_MODULES', 'shipping_modules.php');
  define('FILENAME_SPECIALS', 'specials.php');
  define('FILENAME_STATS_CUSTOMERS', 'stats_customers.php');
  define('FILENAME_STATS_PRODUCTS_PURCHASED', 'stats_products_purchased.php');
  define('FILENAME_STATS_PRODUCTS_VIEWED', 'stats_products_viewed.php');
  define('FILENAME_TAX_CLASSES', 'tax_classes.php');
  define('FILENAME_TAX_RATES', 'tax_rates.php');
  define('FILENAME_WHOS_ONLINE', 'whos_online.php');
  define('FILENAME_ZONES', 'zones.php');
  define('FILENAME_XSELL_PRODUCTS', 'xsell_products.php'); // X-Sell
  define('FILENAME_EASYPOPULATE', 'easypopulate.php');
  define('FILENAME_EASYPOPULATE_BASIC', 'easypopulate_basic.php');
  define('FILENAME_EDIT_ORDERS', 'edit_orders.php');
  define('FILENAME_PAYPAL', 'paypal.php');

// VJ Links Manager v1.00 begin
  define('FILENAME_LINKS', 'links.php');
  define('FILENAME_LINK_CATEGORIES', 'link_categories.php');
  define('FILENAME_LINKS_CONTACT', 'links_contact.php');
// VJ Links Manager v1.00 end

//DWD Modify: Information Page Unlimited 1.1f - PT
  define('FILENAME_INFORMATION_MANAGER', 'information_manager.php');
//DWD Modify End

// added to support the new shop by price admin function
 define('FILENAME_SHOPBYPRICE', 'shopbyprice.php');

// product notifications
define('FILENAME_PRODUCT_NOTIFICATION','product_notifications.php');

//added for Backup mySQL (provided Courtesy Zen-Cart Team) DMG
define('FILENAME_BACKUP_MYSQL','backup_mysql.php');

// Export Import file name -- SCS
define('FILENAME_EXPORT', 'exportcsv.php'); 
//for report
define('FILENAME_STATS_SALES_REPORT', 'stats_sales_report.php');
define('FILENAME_STATS_SALES_REPORT2', 'stats_sales_report2.php'); 

define('FILENAME_PRODUCT_INFO','product_info.php');
// database maintenance
  define('FILENAME_DBMTCE_STATUS', 'dbstatus.php');
  define('FILENAME_DBMTCE_CHECK', 'dbcheck.php');
  define('FILENAME_DBMTCE_REPAIR', 'dbrepair.php');
  define('FILENAME_DBMTCE_ANALYZE', 'dbanalyze.php');
  define('FILENAME_DBMTCE_OPTIMIZE', 'dboptimize.php');

//for travel agences region for pick up   
  define('FILENAME_TOUR_PROVIDER_REGIONS', 'tour_provider_regions.php');
  define('FILENAME_QUESTION_ANSWERS', 'question_answers.php'); 
   define('FILENAME_STATS_AD_RESULTS', 'stats_ad_results.php');
  define('FILENAME_STATS_AD_RESULTS_DETAILS', 'stats_ad_results_details.php');
  define('FILENAME_STATS_AD_RESULTS_MEDIUM', 'stats_ad_results_medium.php');
  define('FILENAME_FAMOUS_ATTRACTION_PRODUCTS', 'famous_attraction_products.php');
  define('FILENAME_FAMOUS_ATTRACTION_SUBCATEGORIES', 'famous_attraction_subcategories.php');
     define('FILENAME_PRODUCT_CATEGORY_TYPE', 'product_category_type.php');
	define('FILENAME_TOUR_LEAD_QUESTION','tour_lead_question.php');
	define('FILENAME_AFFILIATE_BANNER_GROUPS','affiliate_banner_groups.php');
	define('FILENAME_AFFILIATE_BANNERS','affiliate_banners.php'); 
	//DMG :  FAQ System 2.1

  define('FILENAME_FAQ_MANAGER', 'faq_manager.php');
  define('FILENAME_FAQ_VIEW', 'faq_view.php');
  define('FILENAME_FAQ_VIEW_ALL', 'faq_view_all.php');
	
  define('FILENAME_FAQ_CATEGORIES', 'faq_categories.php');
	
//DMG :  FAQ System 2.1
 define('FILENAME_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER', 'products_attributes_tour_provider.php');// FOR PRODUCTS ATTRIBUTES TOUR PROVIDER.
 define('FILENAME_ORDERS_FAX','order_fax.php');	
 define('FILENAME_CUSTOMERS_REP_ORDERS','customer_rep_orders.php');	
 define('FILENAME_CUSTOMERS_REPEAT_ORDERS','customers_repeat_orders.php');	
 define('FILENAME_CUSTOMERS_VIEW_ORDERS','customers_view_orders.php');	
 
 define('FILENAME_MONTYLY_REPORT', 'stats_sales_report.php');
 define('FILENAME_STATS_DETAILED_MONTHLY_SALES', 'stats_detailed_monthly_sales.php');
 define('FILENAME_STATS_PAID_PAYMENT_ORDER_HISTORY', 'stats_paid_payment_order_history.php');
 define('FILENAME_STATS_INVOICE_AMOUNT_MISMATCH', 'stats_invoice_amount_mismatch.php');
  define('FILENAME_STATS_WITHOUT_INVOICE_AMOUNT', 'stats_without_invoice_amount.php');
  define('FILENAME_STATS_PAYMENT_ADJUSTED_ORDER_HISTORY', 'stats_payment_adjusted_order_history.php');  
 
  define('FILENAME_ARTICLE_REVIEWS', 'article_reviews.php');
  define('FILENAME_ARTICLES', 'articles.php');
  define('FILENAME_ARTICLES_CONFIG', 'articles_config.php');
  define('FILENAME_ARTICLES_XSELL', 'articles_xsell.php');
  define('FILENAME_AUTHORS', 'authors.php');
  
  define('FILENAME_TRAVELER_PHOTOS', 'traveler_photos.php');
  define('FILENAME_CANCELLED_ORDERS', 'cancelled_orders.php');
  
  define('FILENAME_RELATED_CATEGORIES', 'related_categories.php');
  define('FILENAME_LANDING_PAGES', 'landing_pages.php'); 
  define('FILENAME_HOTEL_ADMIN', 'hotel.php'); 
  define('FILENAME_BOX_CRUISES_ADMIN', 'cruises.php'); 
  define('FILENAME_TOUR_CODE_DECODE', 'tour_code_decode.php');
  define('FILENAME_TOUR_GROSS_PROFIT_REPORT', 'tour_gross_profit_report.php');
  define('FILENAME_TOUR_GROSS_PROFIT_MISMATCH', 'tour_gross_profit_mismatch.php');
  define('FILENAME_REPORT_DEPARTURE_CITIES','stats_departure_cities.php'); 
  define('FILENAME_REPORT_TRAVELERS_BY_PROVIDER','stats_travelers_by_provider.php'); 
  define('FILENAME_STATS_SALES_BY_CATEGORY','stats_sales_by_category.php'); 
  define('FILENAME_STATS_SALES_BY_CATEGORY_TREE','stats_sales_by_category_tree.php'); 
  define('FILENAME_STATS_ORDER_ITEM_WITHOUT_AMOUNT', 'stats_order_item_without_amount.php');
  
  // Points/Rewards Module V2.1rc2a BOF
  define('FILENAME_CUSTOMERS_POINTS', 'customers_points.php');
  define('FILENAME_CUSTOMERS_POINTS_PENDING', 'customers_points_pending.php');
  define('FILENAME_CUSTOMERS_POINTS_REFERRAL', 'customers_points_referral.php');
  define('FILENAME_CATALOG_MY_POINTS', 'my_points.php');
  define('FILENAME_CATALOG_MY_POINTS_HELP', 'my_points_help.php');
  define('FILENAME_REWARDS4FUN_SUMMARY', 'rewards4fun_summary.php');
  define('FILENAME_CUSTOMER_ACTIONS_HISTORY', 'customer_actions_history.php');
  // Points/Rewards Module V2.1rc2a EOF
  define('FILENAME_CCEXPIRED_ORDERS_REPORT', 'ccexpired_orders_report.php');
  define('FILENAME_STATS_SETTLEMENT_REPORT', 'stats_settlement_report.php');

//howard added 20090527
  define('FILENAME_STATS_ORDER_ANALYSIS', 'stats_order_analysis.php');

//howard added 20090527 end
define('FILENAME_STATS_SALES_BY_DURATIONS','stats_sales_by_durations.php');
define('FILENAME_STATS_INVOICE_ORDER_MATCH', 'stats_invoice_orders_match.php');
define('FILENAME_STATS_CHARGE_CAPTURED_REPORT','stats_charge_captured_report.php');
define('FILENAME_STATS_UNCHARGED_REPORT','stats_uncharged_report.php');
define('FILENAME_STATS_PAYABLE_REPORT','stats_payable_report.php');
define('FILENAME_STATS_PAID_PAYMENT_ORDER_HISTORY_NEW', 'stats_paid_payment_order_history_new.php');
define('FILENAME_STATS_FINANCIAL_PAYABLE_REPORT','stats_financial_payable_report.php');

//howard added 20100409
define('FILENAME_CPC_REPORT','cpc_report.php');

//Providers files
define('FILENAME_PROVIDERS_ORDERS_PROD_STATUS', 'provider_order_products_status.php');
define("DIR_WS_PROVIDERS", "/providers/");
define('FILENAME_PROVIDERS', 'providers.php');
if($_SERVER['HTTP_HOST'] == 'www.usitrip.com'){
	define('FILENAME_PROVIDERS_ORDERS', HTTPS_CATALOG_SERVER.'/providers/providers_orders.php');
}else{
	define('FILENAME_PROVIDERS_ORDERS', HTTP_CATALOG_SERVER.'/providers/providers_orders.php');
}


//domestic files
define('FILENAME_DOMESTIC_LOGO', 'domestic/image/logo.gif');
define('FILENAME_DOMESTIC_LOGO_1', 'domestic/image/logo_1.jpg');
define('FILENAME_DOMESTIC_ORDERS', 'domestic_orders.php');
define('FILENAME_DOMESTIC_DEFAULT', FILENAME_DOMESTIC_ORDERS);
define('FILENAME_DIR_WS_DOMESTIC_LOGIN','domestic_login.php');
define('FILENAME_DOMESTIC_HEADER', 'header_domestic.php');
define('FILENAME_DOMESTIC_FOOTER', 'footer_domestic.php');
//define('', $value)

define('FILENAME_ACCOUNTS_PAYABLE_REPORTS','accounts_payable_reports.php');
define('FILENAME_FEATURED_GROUP_DEAL','featured_group_deal.php');

define('FILENAME_WAITLIST','wait_list.php');
define('FILENAME_WAITLIST_ARCHIV','wait_list_archiv.php');
define('FILENAME_PHONE_BOOKING', 'phone_booking.php');
define('FILENAME_ACCOUNT_HISTORY_INFO', 'account_history_info.php');
//vincent add 20110531 客服工作量统计
define('FILENAME_STATS_CSC','stats_csc.php');
define('FILENAME_ETICKET_LOG','eticket_log.php');
define('FILENAME_ETICKET', 'eticket.php');
//
define('FILENAME_ZONES_CITY', 'zones_city.php');

?>