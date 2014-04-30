<?php
/*
  $Id: english.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/


//Admin begin
// header text in includes/header.php
define('HEADER_TITLE_ACCOUNT', 'My Account/Password');
define('HEADER_TITLE_LOGOFF', 'Log Off');

// Admin Account
define('BOX_HEADING_MY_ACCOUNT', 'My Account');

// configuration box text in includes/boxes/administrator.php
define('BOX_HEADING_ADMINISTRATOR', 'Administrator');
define('BOX_ADMINISTRATOR_MEMBERS', 'Member Groups');
define('BOX_ADMINISTRATOR_MEMBER', 'Members');
define('BOX_ADMINISTRATOR_BOXES', 'File Access');
define('BOX_ADMINISTRATOR_ACCOUNT_UPDATE', 'Update Account');

// tools text in includes/boxes/dbmtce.php
define('BOX_HEADING_DBMTCE', 'Database Mtce');
define('BOX_DBMTCE_STATUS', 'Tables Status');
define('BOX_DBMTCE_CHECK', 'Check Tables');
define('BOX_DBMTCE_REPAIR', 'Repair Tables');
define('BOX_DBMTCE_ANALYZE', 'Analyze Tables');
define('BOX_DBMTCE_OPTIMIZE', 'Optimize Tables');
// images
define('IMAGE_FILE_PERMISSION', 'File Permission');
define('IMAGE_GROUPS', 'Groups List');
define('IMAGE_INSERT_FILE', 'Insert File');
define('IMAGE_MEMBERS', 'Members List');
define('IMAGE_NEW_GROUP', 'New Group');
define('IMAGE_NEW_MEMBER', 'New Member');
define('IMAGE_NEXT', 'Next');

// constants for use in tep_prev_next_display function
define('TEXT_DISPLAY_NUMBER_OF_FILENAMES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> filenames)');
define('TEXT_DISPLAY_NUMBER_OF_MEMBERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> members)');
//Admin end

// look in your $PATH_LOCALE/locale directory for available locales..
// on RedHat6.0 I used 'en_US'
// on FreeBSD 4.0 I use 'en_US.ISO_8859-1'
// this may not work under win32 environments..
setlocale(LC_TIME, 'schinese');
define('DATE_FORMAT_SHORT', '%m/%d/%Y');  // this is used for strftime()
define('DATE_FORMAT_LONG', '%A %d %B, %Y'); // this is used for strftime()
define('DATE_FORMAT', 'm/d/Y'); // this is used for date()
define('PHP_DATE_TIME_FORMAT', 'm/d/Y H:i:s'); // this is used for date()
define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');
define('DATE_FORMAT_SPIFFYCAL', 'MM/dd/yyyy');  //Use only 'dd', 'MM' and 'yyyy' here in any order
define('DATE_FORMAT_WITH_DAY_AT_END', 'm/d/Y l'); // get day
define('DATE_FORMAT_WITH_DAY', 'l, m/d/Y'); // this is used for date()

////
// Return date in raw format
// $date should be in format mm/dd/yyyy
// raw date is in format YYYYMMDD, or DDMMYYYY
function tep_date_raw($date, $reverse = false) {
  if ($reverse) {
    return substr($date, 3, 2) . substr($date, 0, 2) . substr($date, 6, 4);
  } else {
    return substr($date, 6, 4) . substr($date, 0, 2) . substr($date, 3, 2);
  }
}

// Global entries for the <html> tag
define('HTML_PARAMS','dir="ltr" lang="zh-cn"'); 

// charset for web pages and emails
//define('CHARSET', 'iso-8859-1');
define('CHARSET', 'gb2312');

// page title
define('TITLE', 'usitrip');

// domain name on email subject
define('STORE_OWNER_DOMAIN_NAME','usitrip.com');
define('ORDER_EMAIL_PRIFIX_NAME','');
// header text in includes/header.php
define('HEADER_TITLE_TOP', 'Admin');
define('HEADER_TITLE_SUPPORT_SITE', 'Support Site');
define('HEADER_TITLE_ONLINE_CATALOG', 'Catalog');
define('HEADER_TITLE_ADMINISTRATION', 'Admin');
define('HEADER_TITLE_CHAINREACTION', 'Chainreactionweb');
define('HEADER_TITLE_CRELOADED', 'CRE Loaded Project');
// MaxiDVD Added Line For WYSIWYG HTML Area: BOF
define('BOX_CATALOG_DEFINE_MAINPAGE', 'Define MainPage');
// MaxiDVD Added Line For WYSIWYG HTML Area: EOF

// text for gender
define('MALE', '男');
define('FEMALE', '女');

// text for date of birth example
define('DOB_FORMAT_STRING', 'mm/dd/yyyy');

// configuration box text in includes/boxes/configuration.php
define('BOX_HEADING_CONFIGURATION', 'Configuration');
define('BOX_CONFIGURATION_MYSTORE', 'My Store');
define('BOX_CONFIGURATION_LOGGING', 'Logging');
define('BOX_CONFIGURATION_CACHE', 'Cache');

// added for super-friendly admin menu:
define('BOX_CONFIGURATION_MIN_VALUES', 'Min Values');
define('BOX_CONFIGURATION_MAX_VALUES', 'Max Values');
define('BOX_CONFIGURATION_IMAGES', 'Image Configuration');
define('BOX_CONFIGURATION_CUSTOMER_DETAILS', 'Customer Details');
define('BOX_CONFIGURATION_SHIPPING', 'Default Shipping Settings');
define('BOX_CONFIGURATION_PRODUCT_LISTING', 'Product Listing');
define('BOX_CONFIGURATION_EMAIL', 'Email');
define('BOX_CONFIGURATION_DOWNLOAD', 'Download Manager');
define('BOX_CONFIGURATION_GZIP', 'GZip');
define('BOX_CONFIGURATION_SESSIONS', 'Sessions');
define('BOX_CONFIGURATION_STOCK', 'Stock Control');
define('BOX_CONFIGURATION_WYSIWYG', 'WYSIWYG Editor 1.7');
define('BOX_CONFIGURATION_AFFILIATE', 'Affiliate Program');
define('BOX_CONFIGURATION_MAINT', 'Site Maintenance');
define('BOX_CONFIGURATION_ACCOUNTS', 'Purchase Without Account');
define('BOX_CONFIGURATION_LINKS', 'Links Manager');

// modules box text in includes/boxes/modules.php
define('BOX_HEADING_MODULES', 'Modules');
define('BOX_MODULES_PAYMENT', 'Payment');
define('BOX_MODULES_SHIPPING', 'Shipping');
define('BOX_MODULES_ORDER_TOTAL', 'Order Total');

// categories box text in includes/boxes/catalog.php
define('BOX_HEADING_CATALOG', 'Catalog');
define('BOX_CATALOG_CATEGORIES_PRODUCTS', 'Categories/Products');
define('BOX_CATALOG_CATEGORIES_PRODUCTS_ATTRIBUTES', 'Products Attributes');
define('BOX_CATALOG_MANUFACTURERS', 'Manufacturers');
define('BOX_CATALOG_AGE', 'Age Type');
define('BOX_CATALOG_HOTELS', 'Hotels');
define('BOX_CATALOG_LODGING_TYPE', 'Lodging Type');
define('BOX_CATALOG_CITIES', 'Cities');
define('BOX_CATALOG_STATES', 'States');
define('BOX_CATALOG_REGION', 'Regions');
define('BOX_CATALOG_TRAVEL_AGENCY', 'Travel Agency');
define('BOX_CATALOG_REVIEWS', 'Reviews');
define('BOX_CATALOG_QUESTION_ANSWERS', 'Questions Asked');
define('BOX_CATALOG_SPECIALS', 'Specials');
define('BOX_CATALOG_PRODUCTS_EXPECTED', 'Products Expected');
define('BOX_CATALOG_EASYPOPULATE', 'Easy Populate');
define('BOX_CATALOG_EASYPOPULATE_BASIC', 'Easy Populate Basic');

define('BOX_CATALOG_TOUR_PROVIDER_REGIONS', '接送地点管理');

define('BOX_FAMOUS_ATTRACTION_PRODUCTS', 'Famous Attraction Top Section');
define('BOX_FAMOUS_ATTRACTION_SUBCATEGORIES', 'Famous Attraction Yellow Window');
define('BOX_PRODUCT_CATEGORY_TYPE', 'Tour Categories');
define('BOX_CATALOG_SALEMAKER', 'SaleMaker');

define('BOX_CATALOG_SHOP_BY_PRICE', 'Shop by Price');
// customers box text in includes/boxes/customers.php
define('BOX_HEADING_CUSTOMERS', '订单/客户管理');
define('BOX_HEADING_QUESTION', '常见问题');
define('BOX_CUSTOMERS_CUSTOMERS', 'Customers');
define('BOX_CUSTOMERS_INDIVIDUAL_SPACE','Individual space');
define('BOX_CUSTOMERS_DOMESTIC','转账支付管理系统');
define('BOX_CUSTOMERS_POINTCARDS','会员积分卡管理系统');
//JSSON  customers box text in offers_sms_notification.php
define('BOX_OFFERS_SMS_NOTIFICATION','优惠活动短信通知');
//JSSON  customers box text in offers_sms_notification.php
//yichi  customers box text in offers_sms_contact_guest.php
define('BOX_OFFERS_SMS_CONTACT_GUEST','短信联系客户');
//yichi  customers box text in offers_sms_contact_guest.php
define('BOX_CUSTOMERS_LEADS','Leads');
//added for Super-Friendly Admin Menu:
define('BOX_CUSTOMERS_ORDERS', 'Orders');
define('BOX_AGREE_RETURN_VISIT', '愿意接受回访的定单');
define('BOX_CUSTOMERS_EDIT_ORDERS', 'Edit Orders');
//begin PayPal_Shopping_Cart_IPN
define('BOX_CUSTOMERS_PAYPAL', 'PayPal IPN');
//end PayPal_Shopping_Cart_IPN
define('BOX_CREATE_ACCOUNT', 'Create New Account');
define('BOX_CREATE_ORDER', 'Create New Order');
define('BOX_CREATE_ORDERS_ADMIN', 'Create Orders Admin');
// taxes box text in includes/boxes/taxes.php
define('BOX_HEADING_LOCATION_AND_TAXES', 'Locations/Taxes');
define('BOX_TAXES_COUNTRIES', 'Countries');
define('BOX_TAXES_ZONES', 'Zones');
define('BOX_TAXES_ZONES_CITY', 'Zone Cities');
define('BOX_TAXES_GEO_ZONES', 'Tax Zones');
define('BOX_TAXES_TAX_CLASSES', 'Tax Classes');
define('BOX_TAXES_TAX_RATES', 'Tax Rates');

// reports box text in includes/boxes/reports.php
define('BOX_HEADING_REPORTS', 'Reports');
define('BOX_REPORTS_PRODUCTS_VIEWED', 'Products Viewed');
define('BOX_REPORTS_PRODUCTS_PURCHASED', 'Products Purchased');
define('BOX_REPORTS_ORDERS_TOTAL', 'Customer Orders-Total');
define('BOX_CUSTOMER_ORDER_ANALYSIS', 'Customer/Order Analysis');
define('BOX_REPORTS_CUSTOMERS_REP_ORDERS','Customer Rep Orders');
define('BOX_CUSTOMERS_REPEAT_ORDERSM', 'Repeat Customer Orders');
// added for super-friendly admin menu:
define('BOX_REPORTS_MONTHLY_SALES', 'Monthly Sales/Tax');
// tools text in includes/boxes/tools.php
define('BOX_HEADING_TOOLS', 'Tools');
define('BOX_TOOLS_BACKUP', 'Database Backup');
define('BOX_TOOLS_BANNER_MANAGER', 'Banner Manager');
define('BOX_TOOLS_CACHE', 'Cache Control');
define('BOX_TOOLS_DEFINE_LANGUAGE', 'Define Languages');
define('BOX_TOOLS_FILE_MANAGER', 'File Manager');
define('BOX_TOOLS_MAIL', 'Send Email');
define('BOX_TOOLS_NEWSLETTER_MANAGER', 'Newsletter Manager');
define('BOX_TOOLS_SERVER_INFO', 'Server Info');
define('BOX_TOOLS_WHOS_ONLINE', 'Who\'s Online');

// localizaion box text in includes/boxes/localization.php
define('BOX_HEADING_LOCALIZATION', 'Localization');
define('BOX_LOCALIZATION_CURRENCIES', 'Currencies');
define('BOX_LOCALIZATION_LANGUAGES', 'Languages');
define('BOX_LOCALIZATION_ORDERS_STATUS', 'Orders Status');

// infobox box text in includes/boxes/info_boxes.php
define('BOX_HEADING_BOXES', 'Infobox Admin');
define('BOX_HEADING_TEMPLATE_CONFIGURATION', 'Template Admin');
define('BOX_HEADING_DESIGN_CONTROLS', 'Design Controls');
define('BOX_HEADING_FEATURE_SECTION_A', 'Feature Section-A');
define('BOX_HEADING_FEATURE_SECTION_B', 'Feature Section-B');
define('BOX_HEADING_OTHER_TOUR_SECTION', 'Other Sections');

// VJ Links Manager v1.00 begin
// links manager box text in includes/boxes/links.php
define('BOX_HEADING_LINKS', 'Links Manager');
define('BOX_LINKS_LINKS', 'Links');
define('BOX_LINKS_LINK_CATEGORIES', 'Link Categories');
define('BOX_LINKS_LINKS_CONTACT', 'Links Contact');
// VJ Links Manager v1.00 end

// javascript messages
define('JS_ERROR', 'Errors have occured during the process of your form!\nPlease make the following corrections:\n\n');

define('JS_OPTIONS_VALUE_PRICE', '* The new product atribute needs a price value\n');
define('JS_OPTIONS_VALUE_PRICE_PREFIX', '* The new product atribute needs a price prefix\n');

define('JS_PRODUCTS_NAME', '* The new product needs a name\n');
define('JS_PRODUCTS_DESCRIPTION', '* The new product needs a description\n');
define('JS_PRODUCTS_PRICE', '* The new product needs a price value\n');
define('JS_PRODUCTS_WEIGHT', '* The new product needs a weight value\n');
define('JS_PRODUCTS_QUANTITY', '* The new product needs a quantity value\n');
define('JS_PRODUCTS_MODEL', '* The new product needs a model value\n');
define('JS_PRODUCTS_IMAGE', '* The new product needs an image value\n');

define('JS_SPECIALS_PRODUCTS_PRICE', '* A new price for this product needs to be set\n');

define('JS_GENDER', '* The \'Gender\' value must be chosen.\n');
define('JS_FIRST_NAME', '* The \'First Name\' entry must have at least ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' characters.\n');
define('JS_LAST_NAME', '* The \'Last Name\' entry must have at least ' . ENTRY_LAST_NAME_MIN_LENGTH . ' characters.\n');
define('JS_DOB', '* The \'Date of Birth\' entry must be in the format: xx/xx/xxxx (month/date/year).\n');
define('JS_EMAIL_ADDRESS', '* The \'E-Mail Address\' entry must have at least ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' characters.\n');
define('JS_ADDRESS', '* The \'Street Address\' entry must have at least ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' characters.\n');
define('JS_POST_CODE', '* The \'Post Code\' entry must have at least ' . ENTRY_POSTCODE_MIN_LENGTH . ' characters.\n');
define('JS_CITY', '* The \'City\' entry must have at least ' . ENTRY_CITY_MIN_LENGTH . ' characters.\n');
define('JS_STATE', '* The \'State\' entry is must be selected.\n');
define('JS_STATE_SELECT', '-- Select Above --');
define('JS_ZONE', '* The \'State\' entry must be selected from the list for this country.');
define('JS_COUNTRY', '* The \'Country\' value must be chosen.\n');
define('JS_TELEPHONE', '* The \'Telephone Number\' entry must have at least ' . ENTRY_TELEPHONE_MIN_LENGTH . ' characters.\n');
define('JS_PASSWORD', '* The \'Password\' amd \'Confirmation\' entries must match amd have at least ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.\n');

define('JS_ORDER_DOES_NOT_EXIST', 'Order Number %s does not exist!');
/* User Friendly Admin Menu */
define('CATALOG_CATEGORIES', 'Categories');
define('CATALOG_ATTRIBUTES', 'Product Attributes');
define('CATALOG_REVIEWS', 'Product Reveiws');
define('CATALOG_SPECIALS', 'Specials');
define('CATALOG_EXPECTED', 'Products Expected');
define('REPORTS_PRODUCTS_VIEWED', 'Veiwed Products');
define('REPORTS_PRODUCTS_PURCHASED', 'Products Purchased');
define('TOOLS_FILE_MANAGER', 'File Manager');
define('TOOLS_CACHE', 'Cache Control');
define('TOOLS_DEFINE_LANGUAGES', 'Define Languages');
define('TOOLS_EMAIL', 'Email Customers');
define('TOOLS_NEWSLETTER', 'Newsletters');
define('TOOLS_SERVER_INFO', 'Server Info');
define('TOOLS_WHOS_ONLINE', 'Who\'s Online');
define('BOX_HEADING_GV', 'Coupon/Voucher');
define('GV_COUPON_ADMIN', 'Discount Coupons');
define('GV_EMAIL', 'Send Gift Voucher');
define('GV_QUEUE', 'Gift Voucher Redeem');
define('GV_SENT', 'Gift Voucher\'s Sent');
/* User Friedly Admin Menu */


define('CATEGORY_PERSONAL', 'Personal');
define('CATEGORY_ADDRESS', 'Address');
define('CATEGORY_CONTACT', 'Contact');
define('CATEGORY_COMPANY', 'Company');
define('CATEGORY_OPTIONS', 'Options');

define('ENTRY_GENDER', 'Gender:');
define('ENTRY_GENDER_ERROR', '&nbsp;<span class="errorText">required</span>');
define('ENTRY_FIRST_NAME', 'Chinese Name:');
define('ENTRY_FIRST_NAME_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' chars</span>');
define('ENTRY_LAST_NAME', 'English Name:');
define('ENTRY_LAST_NAME_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_LAST_NAME_MIN_LENGTH . ' chars</span>');
define('ENTRY_DATE_OF_BIRTH', 'Date of Birth:');
define('ENTRY_DATE_OF_BIRTH_ERROR', '&nbsp;<span class="errorText">(eg. 05/21/1970)</span>');
define('ENTRY_EMAIL_ADDRESS', 'E-Mail Address:');
define('ENTRY_EMAIL_ADDRESS_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' chars</span>');
define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', '&nbsp;<span class="errorText">The email address doesn\'t appear to be valid!</span>');
define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', '&nbsp;<span class="errorText">This email address already exists!</span>');
define('ENTRY_COMPANY', 'Company name:');
define('ENTRY_COMPANY_ERROR', '');
define('ENTRY_STREET_ADDRESS', 'Street Address:');
define('ENTRY_STREET_ADDRESS_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' chars</span>');
define('ENTRY_SUBURB', 'Suburb:');
define('ENTRY_SUBURB_ERROR', '');
define('ENTRY_POST_CODE', 'Post Code:');
define('ENTRY_POST_CODE_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_POSTCODE_MIN_LENGTH . ' chars</span>');
define('ENTRY_CITY', 'City:');
define('ENTRY_CITY_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_CITY_MIN_LENGTH . ' chars</span>');
define('ENTRY_STATE', 'State:');
define('ENTRY_STATE_ERROR', '&nbsp;<span class="errorText">required</span>');
define('ENTRY_COUNTRY', 'Country:');
define('ENTRY_COUNTRY_ERROR', '');
define('ENTRY_TELEPHONE_NUMBER', 'Telephone Number:');
define('ENTRY_TELEPHONE_NUMBER_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_TELEPHONE_MIN_LENGTH . ' chars</span>');
define('ENTRY_FAX_NUMBER', 'Fax Number:');
define('ENTRY_FAX_NUMBER_ERROR', '');
define('ENTRY_CELL_NUMBER', 'Cell Number:');
define('ENTRY_CELL_NUMBER_ERROR', '');
define('ENTRY_NEWSLETTER', 'Newsletter:');
define('ENTRY_NEWSLETTER_YES', 'Subscribed');
define('ENTRY_NEWSLETTER_NO', 'Unsubscribed');
define('ENTRY_NEWSLETTER_ERROR', '');

// images
define('IMAGE_ANI_SEND_EMAIL', 'Sending E-Mail');
define('IMAGE_BACK', 'Back');
define('IMAGE_BACKUP', 'Backup');
define('IMAGE_CANCEL', 'Cancel');
define('IMAGE_CONFIRM', 'Confirm');
define('IMAGE_COPY', 'Copy');
define('IMAGE_COPY_TO', 'Copy To');
define('IMAGE_DETAILS', 'Details');
define('IMAGE_DELETE', 'Delete');
define('IMAGE_EDIT', '编辑');
define('IMAGE_EMAIL', 'Email');
define('IMAGE_FILE_MANAGER', 'File Manager');
define('IMAGE_ICON_STATUS_GREEN', 'Active');
define('IMAGE_ICON_STATUS_GREEN_LIGHT', 'Set Active');
define('IMAGE_ICON_STATUS_RED', 'Inactive');
define('IMAGE_ICON_STATUS_RED_LIGHT', 'Set Inactive');
define('IMAGE_ICON_INFO', 'Info');
define('IMAGE_INSERT', 'Insert');
define('IMAGE_LOCK', 'Lock');
define('IMAGE_MODULE_INSTALL', 'Install Module');
define('IMAGE_MODULE_REMOVE', 'Remove Module');
define('IMAGE_MOVE', 'Move');
define('IMAGE_NEW_BANNER', 'New Banner');
define('IMAGE_NEW_CATEGORY', 'New Category');
define('IMAGE_NEW_COUNTRY', 'New Country');
define('IMAGE_NEW_CURRENCY', 'New Currency');
define('IMAGE_NEW_FILE', 'New File');
define('IMAGE_NEW_FOLDER', 'New Folder');
define('IMAGE_NEW_LANGUAGE', 'New Language');
define('IMAGE_NEW_NEWSLETTER', 'New Newsletter');
define('IMAGE_NEW_PRODUCT', 'New Product');
define('IMAGE_NEW_SALE', 'New Sale');
define('IMAGE_NEW_TAX_CLASS', 'New Tax Class');
define('IMAGE_NEW_TAX_RATE', 'New Tax Rate');
define('IMAGE_NEW_TAX_ZONE', 'New Tax Zone');
define('IMAGE_NEW_ZONE', 'New Zone');
define('IMAGE_ORDERS', 'Orders');
define('IMAGE_ORDERS_INVOICE', 'Invoice');
define('IMAGE_ORDERS_PACKINGSLIP', 'Packing Slip');
define('IMAGE_PREVIEW', 'Preview');
define('IMAGE_RESTORE', 'Restore');
define('IMAGE_RESET', 'Reset');
define('IMAGE_SAVE', 'Save');
define('IMAGE_SEARCH', '搜索');
define('IMAGE_SELECT', '选择');
define('IMAGE_SEND', 'Send');
define('IMAGE_SEND_EMAIL', 'Send Email');
define('IMAGE_UNLOCK', 'Unlock');
define('IMAGE_UPDATE', 'Update');
define('IMAGE_UPDATE_CURRENCIES', 'Update Exchange Rate');
define('IMAGE_UPLOAD', 'Upload');

define('ICON_CROSS', 'False');
define('ICON_CURRENT_FOLDER', 'Current Folder');
define('ICON_DELETE', 'Delete');
//added for quick product edit DMG
define('ICON_EDIT','??');
define('ICON_ERROR', 'Error');
define('ICON_FILE', 'File');
define('ICON_FILE_DOWNLOAD', 'Download');
define('ICON_FOLDER', 'Folder');
define('ICON_LOCKED', 'Locked');
define('ICON_PREVIOUS_LEVEL', 'Previous Level');
define('ICON_PREVIEW', 'Preview');
define('ICON_STATISTICS', 'Statistics');
define('ICON_SUCCESS', 'Success');
define('ICON_TICK', 'True');
define('ICON_UNLOCKED', 'Unlocked');
define('ICON_WARNING', 'Warning');

// constants for use in tep_prev_next_display function
define('TEXT_RESULT_PAGE', 'Page %s of %d');
define('TEXT_DISPLAY_NUMBER_OF_BANNERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> banners)');
define('TEXT_DISPLAY_NUMBER_OF_COUNTRIES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> countries)');
define('TEXT_DISPLAY_NUMBER_OF_CUSTOMERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> customers)');
define('TEXT_DISPLAY_NUMBER_OF_CURRENCIES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> currencies)');
define('TEXT_DISPLAY_NUMBER_OF_LANGUAGES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> languages)');
define('TEXT_DISPLAY_NUMBER_OF_MANUFACTURERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> manufacturers)');
define('TEXT_DISPLAY_NUMBER_OF_AGENCYS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> Travel Agency)');
define('TEXT_DISPLAY_NUMBER_OF_AGE_TYPE', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> age type)');
define('TEXT_DISPLAY_NUMBER_OF_HOTELS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> hotels)');
define('TEXT_DISPLAY_NUMBER_OF_LODGING_TYPE', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> lodging type)');
define('TEXT_DISPLAY_NUMBER_OF_OTHER_TYPE', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> other type)');
define('TEXT_DISPLAY_NUMBER_OF_NEWSLETTERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> newsletters)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', '显示 <b>%d</b> 至 <b>%d</b> (总共 <b>%d</b> 条记录)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS_STATUS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> orders status)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> products)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_EXPECTED', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> products expected)');
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> product reviews)');
define('TEXT_DISPLAY_NUMBER_OF_SALES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> sales)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> products on special)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_CLASSES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> tax classes)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_ZONES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> tax zones)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_RATES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> tax rates)');
define('TEXT_DISPLAY_NUMBER_OF_ZONES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> zones)');
define('TEXT_DISPLAY_NUMBER_OF_VOTE', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> Vote)');


define('PREVNEXT_BUTTON_PREV', '&lt;&lt;');
define('PREVNEXT_BUTTON_NEXT', '&gt;&gt;');

define('TEXT_DEFAULT', 'default');
define('TEXT_SET_DEFAULT', 'Set as default');
define('TEXT_FIELD_REQUIRED', '&nbsp;<span class="fieldRequired">* Required</span>');

define('ERROR_NO_DEFAULT_CURRENCY_DEFINED', 'Error: There is currently no default currency set. Please set one at: Administration Tool->Localization->Currencies');

define('TEXT_NONE', '--none--');
define('TEXT_TOP', 'Top');
define('TEXT_REGION', '-- Select Region --');
define('TEXT_STATE', '-- Select States --');
define('TEXT_CITY', '-- Select Cities --');

define('ERROR_DESTINATION_DOES_NOT_EXIST', 'Error: Destination does not exist.');
define('ERROR_DESTINATION_NOT_WRITEABLE', 'Error: Destination not writeable.');
define('ERROR_FILE_NOT_SAVED', 'Error: File upload not saved.');
define('ERROR_FILETYPE_NOT_ALLOWED', 'Error: File upload type not allowed.');
define('SUCCESS_FILE_SAVED_SUCCESSFULLY', 'Success: File upload saved successfully.');
define('WARNING_NO_FILE_UPLOADED', 'Warning: No file uploaded.');
define('WARNING_FILE_UPLOADS_DISABLED', 'Warning: File uploads are disabled in the php.ini configuration file.');
define('TEXT_DISPLAY_NUMBER_OF_PAYPALIPN_TRANSACTIONS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> transactions)'); // PAYPALIPN

define('BOX_HEADING_PAYPALIPN_ADMIN', 'Paypal IPN'); // PAYPALIPN
define('BOX_PAYPALIPN_ADMIN_TRANSACTIONS', 'Transactions'); // PAYPALIPN
define('BOX_PAYPALIPN_ADMIN_TESTS', 'Send Test IPN'); // PAYPALIPN
define('BOX_CATALOG_XSELL_PRODUCTS', 'Cross Sell Products'); // X-Sell

REQUIRE(DIR_WS_LANGUAGES . 'add_ccgvdc_schinese.php');

define('IMAGE_BUTTON_PRINT_ORDER', 'Order Printable');

// BOF: Lango Added for print order MOD
define('IMAGE_BUTTON_PRINT', 'Print');
// EOF: Lango Added for print order MOD

// BOF: Lango Added for Featured product MOD
  define('BOX_CATALOG_FEATURED', 'Featured Products');
// EOF: Lango Added for Featured product MOD

// BOF: Lango Added for Sales Stats MOD
define('BOX_REPORTS_MONTHLY_SALES', 'Monthly Sales/Tax');
// EOF: Lango Added for Sales Stats MOD

// BOF: Lango Added for template MOD
// WebMakers.com Added: Attribute Sorter, Copier and Catalog additions
require(DIR_WS_LANGUAGES . $language . '/' . 'attributes_sorter.php');

//DWD Modify: Information Page Unlimited 1.1f - PT
  define('BOX_HEADING_INFORMATION', 'Info System');
  define('BOX_INFORMATION_MANAGER', 'Info Manager');
//DWD Modify End

//include('includes/languages/english_support.php');
include('includes/languages/schinese_newsdesk.php');
include('includes/languages/schinese_faqdesk.php');
include('includes/languages/order_edit_schinese.php');
//included for CRE Loaded 6.1 Release edition

 define('BOX_TITLE_CRELOADED', 'CRE Loaded Project');
 define('LINK_CRE_FORUMS','CRE Loaded Forums');
 define('LINK_CRW_SUPPORT','Technical Support');
// General Release Edition
 define('LINK_SF_CRELOADED','Source Forge Home');
 define('LINK_SF_BUGTRACKER','Bug Tracker');
 define('LINK_CRE_FILES','CRE Downloads');
 define('LINK_SF_SUPPORT','Support Request');
 define('LINK_SF_TASK','Task Tracker');
 define('LINK_SF_CVS','Browse CVS');
 define('LINK_CRE_FILES','CRE Downloads');
 define('LINK_SF_FEATURE','Feature Request');
//included for Backup mySQL (courtesy Zen-Cart Team) DMG
 define('BOX_TOOLS_MYSQL_BACKUP','Backup mySQL');
 //scs added define
 // customization for the design layout
//  define('BOX_WIDTH', 125); // how wide the boxes should be in pixels (default: 125)
  
  //tools
  define('BOX_TOOLS_KEYWORDS', 'Keyword Manager');
  //amit added for report constranint
   define('BOX_REPORTS_SALES_REPORT2', 'SalesReport2');
 define('BOX_REPORTS_SALES_REPORT', 'SalesReport');
 define('BOX_REPORTS_AD_RESULTS', 'Ad Results Source'); 
  define('BOX_REPORTS_AD_RESULTS_MEDIUM', 'Ad Results Medium'); 
   define('BOX_REPORTS_SALES_CSV', 'Sales Graph');

//DMG FAQ System 2.1
  define('BOX_HEADING_FAQ', 'FAQ System');
  define('BOX_FAQ_MANAGER', 'FAQ Manager');
  define('BOX_FAQ_CATEGORIES', 'FAQ Categories');
  define('BOX_FAQ_VIEW', 'FAQ View');
  define('BOX_FAQ_VIEW_ALL', 'FAQ View All');
  
define('TEXT_TOTAL_OF_ROOMS','总房间数：');

define('TEXT_OF_ADULTS_IN_ROOM1','房间一内成人数：');
define('TEXT_OF_ADULTS_IN_ROOM2','房间二内成人数：');
define('TEXT_OF_ADULTS_IN_ROOM3','房间三内成人数：');
define('TEXT_OF_ADULTS_IN_ROOM4','房间四内成人数：');
define('TEXT_OF_ADULTS_IN_ROOM5','房间五内成人数：');
define('TEXT_OF_ADULTS_IN_ROOM6','房间六内成人数：');
define('TEXT_OF_ADULTS_IN_ROOM7','房间七内成人数：');
define('TEXT_OF_ADULTS_IN_ROOM8','房间八内成人数：');
define('TEXT_OF_ADULTS_IN_ROOM9','房间九内成人数：');
define('TEXT_OF_ADULTS_IN_ROOM10','房间十内成人数：');
define('TEXT_OF_ADULTS_IN_ROOM11','房间十一内成人数：');
define('TEXT_OF_ADULTS_IN_ROOM12','房间十二内成人数：');
define('TEXT_OF_ADULTS_IN_ROOM13','房间十三内成人数：');
define('TEXT_OF_ADULTS_IN_ROOM14','房间十四内成人数：');
define('TEXT_OF_ADULTS_IN_ROOM15','房间十五内成人数：');

define('TEXT_OF_CHILDREN_IN_ROOM1','房间一内儿童数：');
define('TEXT_OF_CHILDREN_IN_ROOM2','房间二内儿童数：');
define('TEXT_OF_CHILDREN_IN_ROOM3','房间三内儿童数：');
define('TEXT_OF_CHILDREN_IN_ROOM4','房间四内儿童数：');
define('TEXT_OF_CHILDREN_IN_ROOM5','房间五内儿童数：');
define('TEXT_OF_CHILDREN_IN_ROOM6','房间六内儿童数：');
define('TEXT_OF_CHILDREN_IN_ROOM7','房间七内儿童数：');
define('TEXT_OF_CHILDREN_IN_ROOM8','房间八内儿童数：');
define('TEXT_OF_CHILDREN_IN_ROOM9','房间九内儿童数：');
define('TEXT_OF_CHILDREN_IN_ROOM10','房间十内儿童数：');
define('TEXT_OF_CHILDREN_IN_ROOM11','房间十一内儿童数：');
define('TEXT_OF_CHILDREN_IN_ROOM12','房间十二内儿童数：');
define('TEXT_OF_CHILDREN_IN_ROOM13','房间十三内儿童数：');
define('TEXT_OF_CHILDREN_IN_ROOM14','房间十四内儿童数：');
define('TEXT_OF_CHILDREN_IN_ROOM15','房间十五内儿童数：');

define('TEXT_TOTLE_OF_ROOM1','房间一小计：');//第一房间总费用(Subtotal 1)：
define('TEXT_TOTLE_OF_ROOM2','房间二小计：');
define('TEXT_TOTLE_OF_ROOM3','房间三小计：');
define('TEXT_TOTLE_OF_ROOM4','房间四小计：');
define('TEXT_TOTLE_OF_ROOM5','房间五小计：');
define('TEXT_TOTLE_OF_ROOM6','房间六小计：');
define('TEXT_TOTLE_OF_ROOM7','房间七小计：');
define('TEXT_TOTLE_OF_ROOM8','房间八小计：');
define('TEXT_TOTLE_OF_ROOM9','房间九小计：');
define('TEXT_TOTLE_OF_ROOM10','房间十小计：');
define('TEXT_TOTLE_OF_ROOM11','房间十一小计：');
define('TEXT_TOTLE_OF_ROOM12','房间十二小计：');
define('TEXT_TOTLE_OF_ROOM13','房间十三小计：');
define('TEXT_TOTLE_OF_ROOM14','房间十四小计：');
define('TEXT_TOTLE_OF_ROOM15','房间十五小计：');

define('TEXT_BED_OF_ROOM1','房间一床型：');
define('TEXT_BED_OF_ROOM2','房间二床型：');
define('TEXT_BED_OF_ROOM3','房间三床型：');
define('TEXT_BED_OF_ROOM4','房间四床型：');
define('TEXT_BED_OF_ROOM5','房间五床型：');
define('TEXT_BED_OF_ROOM6','房间六床型：');
define('TEXT_BED_OF_ROOM7','房间七床型：');
define('TEXT_BED_OF_ROOM8','房间八床型：');
define('TEXT_BED_OF_ROOM9','房间九床型：');
define('TEXT_BED_OF_ROOM10','房间十床型：');
define('TEXT_BED_OF_ROOM11','房间十一床型：');
define('TEXT_BED_OF_ROOM12','房间十二床型：');
define('TEXT_BED_OF_ROOM13','房间十三床型：');
define('TEXT_BED_OF_ROOM14','房间十四床型：');
define('TEXT_BED_OF_ROOM15','房间十五床型：');

define('TEXT_BED_STANDARD','不限制');
define('TEXT_BED_KING','一张King-sized大床');
define('TEXT_BED_QUEEN','两张标准床');

define('TEXT_TOTLE_OF_ROOM_STRING_CHECK','房间总费用');
define('TEXT_DEFULAT_TOUR_ARRANMGENT','<TR>
<TD><STRONG>Date<BR><SPAN lang=ZH-TW style=\"FONT-SIZE: 10.5pt; FONT-FAMILY: PMingLiU; mso-bidi-font-size: 12.0pt; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\'; mso-font-kerning: 1.0pt; mso-ansi-language: EN-US; mso-fareast-language: ZH-TW; mso-bidi-language: AR-SA\">日期</SPAN></STRONG></TD>
<TD><STRONG>Pick-up Time<BR><SPAN lang=ZH-CN style=\"FONT-SIZE: 10.5pt; FONT-FAMILY: SimSun; mso-bidi-font-size: 12.0pt; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\'; mso-font-kerning: 1.0pt; mso-ansi-language: EN-US; mso-fareast-language: ZH-CN; mso-bidi-language: AR-SA\">上车</SPAN><SPAN lang=ZH-TW style=\"FONT-SIZE: 10.5pt; FONT-FAMILY: PMingLiU; mso-bidi-font-size: 12.0pt; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\'; mso-font-kerning: 1.0pt; mso-ansi-language: EN-US; mso-fareast-language: ZH-TW; mso-bidi-language: AR-SA\">时间</SPAN></STRONG></TD>
<TD><STRONG>Itinerary<BR><SPAN lang=ZH-TW style=\"FONT-SIZE: 10.5pt; FONT-FAMILY: PMingLiU; mso-bidi-font-size: 12.0pt; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\'; mso-font-kerning: 1.0pt; mso-ansi-language: EN-US; mso-fareast-language: ZH-TW; mso-bidi-language: AR-SA\">线路</SPAN></STRONG></TD>
<TD><STRONG>Hotel<BR><SPAN lang=ZH-TW style=\"FONT-SIZE: 10.5pt; FONT-FAMILY: PMingLiU; mso-bidi-font-size: 12.0pt; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\'; mso-font-kerning: 1.0pt; mso-ansi-language: EN-US; mso-fareast-language: ZH-TW; mso-bidi-language: AR-SA\">酒店</SPAN></STRONG></TD>
<TD><STRONG>Note<BR><SPAN lang=ZH-TW style=\"FONT-SIZE: 10.5pt; FONT-FAMILY: PMingLiU; mso-bidi-font-size: 12.0pt; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\'; mso-font-kerning: 1.0pt; mso-ansi-language: EN-US; mso-fareast-language: ZH-TW; mso-bidi-language: AR-SA\"><SPAN lang=ZH-TW style=\"FONT-SIZE: 10.5pt; FONT-FAMILY: PMingLiU; mso-bidi-font-size: 12.0pt; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\'; mso-font-kerning: 1.0pt; mso-ansi-language: EN-US; mso-fareast-language: ZH-TW; mso-bidi-language: AR-SA\">备注</SPAN></SPAN></STRONG></TD></TR>
');

define('TEXT_DEFULAT_TOUR_ARRANMGENT_NEW','<TR>
<TD><STRONG>Date and Pickup Details<BR><SPAN lang=ZH-TW style=\"FONT-SIZE: 10.5pt; FONT-FAMILY: PMingLiU; mso-bidi-font-size: 12.0pt; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\'; mso-font-kerning: 1.0pt; mso-ansi-language: EN-US; mso-fareast-language: ZH-TW; mso-bidi-language: AR-SA\">出发时间和地点信息</SPAN></STRONG></TD>
<TD><STRONG>Local Tour Contact<BR><SPAN lang=ZH-CN style=\"FONT-SIZE: 10.5pt; FONT-FAMILY: SimSun; mso-bidi-font-size: 12.0pt; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\'; mso-font-kerning: 1.0pt; mso-ansi-language: EN-US; mso-fareast-language: ZH-CN; mso-bidi-language: AR-SA\">当地地接巴士公司</SPAN></STRONG></TD>
<TD><STRONG>Itinerary<BR><SPAN lang=ZH-TW style=\"FONT-SIZE: 10.5pt; FONT-FAMILY: PMingLiU; mso-bidi-font-size: 12.0pt; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\'; mso-font-kerning: 1.0pt; mso-ansi-language: EN-US; mso-fareast-language: ZH-TW; mso-bidi-language: AR-SA\">线路</SPAN></STRONG></TD>
<TD><STRONG>Hotel<BR><SPAN lang=ZH-TW style=\"FONT-SIZE: 10.5pt; FONT-FAMILY: PMingLiU; mso-bidi-font-size: 12.0pt; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\'; mso-font-kerning: 1.0pt; mso-ansi-language: EN-US; mso-fareast-language: ZH-TW; mso-bidi-language: AR-SA\">酒店</SPAN></STRONG></TD>
<TD><STRONG>Note<BR><SPAN lang=ZH-TW style=\"FONT-SIZE: 10.5pt; FONT-FAMILY: PMingLiU; mso-bidi-font-size: 12.0pt; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\'; mso-font-kerning: 1.0pt; mso-ansi-language: EN-US; mso-fareast-language: ZH-TW; mso-bidi-language: AR-SA\"><SPAN lang=ZH-TW style=\"FONT-SIZE: 10.5pt; FONT-FAMILY: PMingLiU; mso-bidi-font-size: 12.0pt; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'; mso-bidi-font-family: \'Times New Roman\'; mso-font-kerning: 1.0pt; mso-ansi-language: EN-US; mso-fareast-language: ZH-TW; mso-bidi-language: AR-SA\">备注</SPAN></SPAN></STRONG></TD></TR>
');

define('TEXT_HEADING_DEPARTURE_DATE_HOLIDAY_PRICE','Holiday Price');
define('TEXT_HEADING_PRODUCT_ATTRIBUTE_SPECIAL_PRICE','Upgrade Price');
define('TEXT_HEADING_REGULAR_SPECIAL_PRICE','Standard Price');

define('TEXT_SHOPPIFG_CART_TOTAL_OF_ROOMS','总房间数量');
define('TEXT_SHOPPIFG_CART_ADULTS_IN_ROOMS','&nbsp;&nbsp;# 房间内成人数量');
define('TEXT_SHOPPIFG_CART_CHILDREDN_IN_ROOMS','&nbsp;&nbsp;# 房间内小孩数量');
define('TEXT_SHOPPIFG_CART_ADULTS_NO','&nbsp;&nbsp;# 成人');
define('TEXT_SHOPPIFG_CART_CHILDREDN_NO','&nbsp;&nbsp;# 小孩');
define('TEXT_SHOPPIFG_CART_TOTAL_DOLLOR_OF_ROOM','总房间数量');
define('TEXT_SHOPPIFG_CART_NO_ROOM_TOTAL','小计');
define('PLEASE_SELECT_COUNTRY','-- Select County --');
// DMG Article Manager
define('BOX_HEADING_ARTICLES', 'Article Manager');
define('BOX_TOPICS_ARTICLES', 'Topics/Articles');
define('BOX_ARTICLES_CONFIG', 'Configuration');
define('BOX_ARTICLES_AUTHORS', 'Authors');
define('BOX_ARTICLES_REVIEWS', 'Article Reviews');
define('BOX_ARTICLES_XSELL', 'Cross-Sell Articles');
define('IMAGE_NEW_TOPIC', 'New Topic');
define('IMAGE_NEW_ARTICLE', 'New Article');
define('TEXT_DISPLAY_NUMBER_OF_AUTHORS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> authors)');
define('IMAGE_NEW_AUTHOR', 'New Author');
define('TEXT_WARNING_NO_AUTHORS', 'WARNING:  Empty Authors Table!&nbsp;&nbsp;You MUST add at least one Author before you will be able to add any Articles');

define('TEXT_DISPLAY_NUMBER_OF_REVIEWS_ARTICLE', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> article reviews)');
// Article Statistics Report DMG
define('BOX_REPORTS_DETAILD_MONTHLY_SALES', 'Monthly Accounting Report');
define('BOX_REPORTS_PAID_PAYMENT_HISTORY', 'Payment History Report (current)');
define('BOX_REPORTS_INVOICE_AMOUNT_MISMATCH', 'Invoice Amount Mismatch');
define('BOX_REPORTS_WITHOUT_INVOICE_AMOUNT', 'Without Invoice Amount');
define('BOX_REPORTS_PAYMENT_ADJUSTED_ORDER_HISTORY', 'Payment Adjusted Orders');

define('TEXT_DISPLAY_NUMBER_OF_PHOTOS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> traveler photos)');

define('BOX_CATALOG_PHOTOS', 'Traveler Photos');
define('BOX_REPORTS_CANCELLED_ORDERS', 'Cancelled Orders');
define('TEXT_TOUR_CODE_ENCODE_ROTATE_VALUE',15);
define('BOX_LANDING_PAGES','Landing Pages');
define('BOX_HOTEL_ADMIN','酒店资料管理');
define('BOX_CRUISES_ADMIN','邮轮资料管理');

define('BOX_TOUR_CODE_DECODE','Tour Code Decode');
define('GLOBUS_AGENCY_ID','0'); /// donot modify without permission

define('BOX_CATALOG_ATTRACTIONS', 'Attractions');
define('BOX_REPORT_GROSS_PROFIT', 'Tour Gross Profit Report');
define('BOX_REPORT_GROSS_PROFIT_MISMATCH', 'Tour Gross Profit Mismatch');
define('BOX_REPORT_DEPARTURE_CITIES','Sales by Departure City');
define('BOX_REPORT_TRAVELERS_BY_PROVIDER','Travelers by Provider');
define('BOX_REPORT_SALES_BY_CATEGORY','Sales by Category');
define('BOX_REPORT_SALES_BY_CATEGORY_TREE','Sales by Category Tree');
define('BOX_REPORT_ORDERS_TOURS_WITHOUT_COST','Orders/Tours Without Cost');

######## Points/Rewards Module V2.1rc2a BOF ##################
define('BOX_HEADING_REWARDS4FUN', '积分管理');
define('BOX_CUSTOMERS_POINTS', 'Customers Points');
define('BOX_CUSTOMERS_POINTS_PENDING', 'Pending Points');
define('BOX_CUSTOMERS_POINTS_REFERRAL', 'Action Management');
define('SUCCESS_POINTS_UPDATED', 'Success: Customer Points account has been successfully updated.');
define('BOX_REWARDS4FUN_SUMMARY', 'Summary');
######## Points/Rewards Module V2.1rc2a BOF ##################
define('BOX_REPORTS_CCEXPIRED_ORDERS_REPORT','CC Authorization Expiration');

define('TEXT_CHINESE_DEFAULT_AUTO_TEXT_FOOTER','');

define('EMAIL_TEXT_DEAR','Dear ');
define('EMAIL_TEXT_DEAR_A','尊敬的顾客');

define('SHOPPING_CART_CALL','走四方网购物车提醒！');

define('POINTS_CALL_MAIL','走四方积分提醒！');
define('MY_POINTS_CURRENT_BALANCE', '总积分： <b style="color:#FF0000">%s</b>  价值： <b style="color:#FF0000">%s</b> ');

###################### Tours Vote System ##########################
define('TOURS_TOTE_SYSTEM','走四方调查系统');

define('DB_UPDATE_SYSTEM','数据库数据更新');

define('EMAIL_TRACK_SYSTEM','电子邮件跟踪系统');
define('BOX_REPORTS_SETTLEMENT','Settlement Report');
define('TEXT_SHOPPIFG_CART_TOTAL_FARES_TRANSACTION_FEE','总价(包括3%服务费)');
define('SEO_NEWS_SYSTEM','SEO文章管理');
define('BOX_REPORT_SALES_BY_DURATTION','Sales by Durations');
define('BOX_CHARGE_CAPTURED_REPORT', 'Charge Captured Report');
define('BOX_UNCHARGED_REPORT', 'Uncharged Report');

define('TEXT_HOW_SAVE','');
define('TEXT_SAVINGS','');
//define('TEXT_HOW_SAVE','积分现金折扣比例');
//define('TEXT_SAVINGS','第一次订购优惠可达<span style="color: rgb(241, 115, 13);">3%-6%</span>！<br>第二次订购优惠可达<span style="color: rgb(241, 115, 13);">4%-7%</span>！<br>订购超过两次优惠可达<span style="color: rgb(241, 115, 13);">5%-8%</span>！');

define('TFF_POINTS_DESCRIPTION','走四方积分兑换说明');
define('TFF_POINTS_DESCRIPTION_CONTENT','您可以在订购走四方网的旅游行程时使用您的积分，获取一定比例的折扣。<br>
兑换方法：当您在预定某行程时，在结帐页面，“积分兑换”部分，您可以看到您目前的积分总数及订购此行程可以使用的最高积分数及节省的金额。点击“兑换积分”按钮，系统就将自动扣住您的积分，并减去相应的金额，展示积分折扣后的订单总价。');

define('BOX_HEADING_SALES_REPORTS', 'Sales Reports');
define('BOX_HEADING_MARKETING_REPORTS', 'Marketing Reports');
define('BOX_HEADING_PRODUCT_REPORTS', 'Product Reports');
define('BOX_HEADING_SERVICE_REPORTS', 'Service Reports');
define('BOX_HEADING_PAYMENT_REPORTS', 'Payment Reports');
define('BOX_HEADING_ACCOUNTING_REPORTS', 'Accounting Reports');
define('BOX_HEADING_ACCOUNTANT_REPORTS', 'Accountant Reports');
define('BOX_CSC_REPORT','Workload Report ');

define('BOX_PAYABLE_REPORT_DEPARTURE','Payable by Departure Month');
define('BOX_PAYABLE_REPORT_RESERVATION','Payable by Reservation Month');
define('BOX_REPORTS_PAID_PAYMENT_HISTORY_OLD', 'Payment History Report (01/2008 to 01/31/2010)');
define('BOX_FINANCIAL_PAYABLE_REPORT','Finacial Payable Report');

define('BOX_CPC_REPORT','CPC Report');

//howard added 2010-5-18
define('E_TICKET_SMS','您的订单%s的电子参团凭证已发送到邮箱，它是您的参团凭证，请打印出来。登陆走四方网至“用户中心”查阅详情--usitrip.com');
define('BOX_LOCALIZATION_PROVIDERS_ORDERS_PROD_STATUS', 'Providers Orders Product Status');

//howard added 2010-07-29
define('BOOK_LIMIT_DAYS_NUMBER_TITLE', 'How many hours can be booked before departure date( for tour without airport transfer):');	//出团前预定天数限制:
define('BOOK_LIMIT_DAYS_NUMBER_TITLE_WITCH_AIR', 'How many hours can be booked before departure date(for tour with airport transfer):');	//出团前预定天数限制（接机团）:
define('AUTO_GET_FROM_AGENCY', 'Subject to setting for travel agency');	//自动从供应商处取得
define('BOOK_LIMIT_DAYS_NUMBER_TIPS', 'Note: Limit for each tour subject to setting on travel agency edit page. leave blank if you want to use Agency Default-2 Days.');	//提示：一般情况下可不设置，自动从供应商处得即可，只有在几个不同供应商组合的团才可能需要更改此设置。
define('TEXT_SHOPPIFG_CART_ADULTS_NO_ANOTHER','&#160;&#160;# 成人');
define('TEXT_SHOPPIFG_CART_CHILDREDN_NO_ANOTHER','&#160;&#160;# 小孩');

define('TEXT_SHOPPIFG_CART_ADULTS_NO_ANOTHER1','&nbsp;&nbsp;# 成人');
define('TEXT_SHOPPIFG_CART_CHILDREDN_NO_ANOTHER1','&nbsp;&nbsp;#小孩');

define('TEXT_TOTAL_OF_ROOMS_ANOTHER', '- 总房间数:');
define('TEXT_TOTAL_OF_ROOMS_ANOTHER1','总房间数：');

define('TEXT_OF_ADULTS_IN_ROOM1_ANOTHER','房间一成人数：');

define('TEXT_OF_ADULTS_IN_ROOM2_ANOTHER',' - 房间二总费用 ');
define('TEXT_OF_ADULTS_IN_ROOM3_ANOTHER',' - 房间三总费用 ');
define('TEXT_OF_ADULTS_IN_ROOM4_ANOTHER',' - 房间四总费用 ');
define('TEXT_OF_ADULTS_IN_ROOM5_ANOTHER',' - 房间五总费用 ');
define('TEXT_OF_ADULTS_IN_ROOM6_ANOTHER',' - 房间六总费用 ');


define('TEXT_OF_CHILDREN_IN_ROOM1','房间一内儿童数：');
define('TEXT_OF_CHILDREN_IN_ROOM2','房间二内儿童数：');
define('TEXT_OF_CHILDREN_IN_ROOM3','房间三内儿童数：');
define('TEXT_OF_CHILDREN_IN_ROOM4','房间四内儿童数：');
define('TEXT_OF_CHILDREN_IN_ROOM5','房间五内儿童数：');
define('TEXT_OF_CHILDREN_IN_ROOM6','房间六内儿童数：');

define('CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID','96'); // donot modify without permission 系统自动更新的虚拟管理账号的id，此值不可修改！

define('SUNDAY','周日');
define('MONDAY','周一');
define('TUESDAY','周二');
define('WEDNESDAY','周三');
define('THURSDAY','周四');
define('FRIDAY','周五');
define('SATURDAY','周六');

define('BOX_ACCOUNTS_PAYABLE_REPORTS','应付账款报表');
define('BOX_FEATURED_GROUP_DEAL', 'Group Deals');

define('TEXT_FILENAME_WAITLIST','Wait List');
define('BOX_PHONE_BOOKING','Phone Booking');

define('EMAIL_FOOTER_SIGNATURE', "\n \n Best Regards \n\n 旅游顾问工号(Travel Advisor): %s \n <a href=http://208.109.123.18 target=_blank>usitrip.com</a>(走四方)&nbsp;&nbsp;&nbsp;&nbsp;Email: service@usitrip.com \n %s ");

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Order Update');
define('EMAIL_TEXT_ORDER_NUMBER', '您的订单号（Order Number）:');
define('EMAIL_TEXT_INVOICE_URL', '订单详情（Order Details）:');
define('EMAIL_TEXT_DATE_ORDERED', '预定日期（Order Date）:');
define('EMAIL_TEXT_STATUS_UPDATE', '您的订单最新状态为: <b>%s</b>');
//define('EMAIL_TEXT_REPLY_MSN', '如有疑问，请您直接回复邮件。（Please reply to this email if you have any questions.）');
define('EMAIL_TEXT_REPLY_MSN', '');
//define('EMAIL_TEXT_COMMENTS_UPDATE', '您订单的最新提示（The comments for your order are）：' . "\n\n%s\n\n");
define('EMAIL_TEXT_COMMENTS_UPDATE', "\n\n%s\n\n");
//hotel-extension
define('HOTEL_EXT_ATTRIBUTE_OPTION_ID','9999'); // donot modify without permission

define('START_CITY_TO_END_CITY_HEADING','出发城市与目的城市对应表');
define('TXT_ETICKET_HOTEL_CHECK_IN_DATE', '入住日期：');//Check In Time and Date
define('TXT_ETICKET_HOTEL_CHECK_OUT_DATE', '离店日期：');//Check Out Time and Date:
define('BOX_HEADING_FILENAME_ETICKET_LOG', 'E-ticket log');
define('TXT_ADD_FEATURES_TOUR_IDS', '');
define('PRIORITY_MAIL_PRODUCTS_OPTIONS_ID', '146'); // donot modify without permission
define('PRIORITY_MAIL_PRODUCTS_OPTIONS_VALUES_ID', '866'); // donot modify without permission
define('TXT_PRIORITY_MAIL_TICKET_NEEDED_DATE', '门票邮递日期 : ');
define('TXT_PRIORITY_MAIL_DELIVERY_ADDRESS', '邮递地址 : ');
define('TXT_PRIORITY_MAIL_RECIPIENT_NAME', '收件人 : ');
define('NEW_PAYMENT_METHOD_T4F_CREDIT', '虚拟账户');
define('TOUR_IDS_FOR_ATTR_THEME_PARK_NOTE', '');
define('ATTR_THEME_PARK_INCLUDE_NOTE', '已经付清主题公园门票费用, 请保留座位。');
define('ATTR_THEME_PARK_EXCLUDE_NOTE', '请不为客人保留座位。');
define('HOTEL_PRICE_PER_DAYS_ATTR_NAME', '请选择早餐类别'); //please donot modify

//添加积分相关的常量
define('HEADING_POINTS_COMMENT', '积分记录');
define('HEADING_POINTS_STATUS', '积分状态');
define('HEADING_POINTS_TOTAL', '分值');

define('TEXT_DEFAULT_COMMENT', '购买产生的积分');
define('TEXT_DEFAULT_REDEEMED', '兑换积分');
define('TEXT_WELCOME_POINTS_COMMENT', '欢迎积分');
define('TEXT_VALIDATION_ACCOUNT_POINT_COMMENT','电子邮箱通过验证奖励积分');
define('TEXT_ORDER_CANCELLED_COMMENT','订单撤销退还积分给我司');
define('TEXT_ORDER_CANCELLED_BUY_NEW_COMMENT','撤销原单退还积分给我司');
define('TEXT_ORDER_REFUNDED_COMMENT','取消订单，退款给客人，积分归还我司！');
define('TEXT_VOTE_POINTS_COMMENT','参加走四方调查活动获得积分');
define('TEXT_DEFAULT_REFERRAL', '朋友推荐获得积分');
define('TEXT_DEFAULT_REVIEWS', '评论积分');

define('TEXT_NEW_GROUP_BUY_REFERRAL', '邮件推荐朋友参加团购获得积分');

define('TEXT_DEFAULT_FEEDBACK_APPROVAL' , '信息反馈积分' ) ;
define('TEXT_DEFAULT_REVIEWS_PHOTOS' , '照片上传获得积分' ) ;
define('TEXT_DEFAULT_ANSWER' , '回答问题获得积分' ) ;

define('TEXT_OLD_SITE_WELCOME_POINTS_COMMENT' , '老站客户转新站赠予积分' ) ;
?>