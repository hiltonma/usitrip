<?php
/*
  $Id: english.php,v 1.3 2004/03/15 12:13:02 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// look in your $PATH_LOCALE/locale directory for available locales
// or type locale -a on the server.
// Examples:
// on RedHat try 'en_US'
// on FreeBSD try 'en_US.ISO_8859-1'
// on Windows try 'en', or 'English'
@setlocale(LC_TIME, 'en_US.ISO_8859-1');

define('DATE_FORMAT_SHORT', '%m/%d/%Y');  // this is used for strftime()
define('DATE_FORMAT_LONG', '%A %d %B, %Y'); // this is used for strftime()
define('DATE_FORMAT', 'm/d/Y'); // this is used for date()
define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');

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

// if USE_DEFAULT_LANGUAGE_CURRENCY is true, use the following currency, instead of the applications default currency (used when changing language)
define('LANGUAGE_CURRENCY', 'USD');

// Global entries for the <html> tag
define('HTML_PARAMS','dir="ltr" lang="en"');

// charset for web pages and emails
define('CHARSET', 'gb2312');

// page title
define('TITLE', 'Your Store Name, change in catalog/includes/languages/   your language');

// ######## CCGV
define('BOX_INFORMATION_GV', 'Gift Voucher FAQ');
define('VOUCHER_BALANCE', 'Voucher Balance');
define('BOX_HEADING_GIFT_VOUCHER', 'Gift Voucher Account'); 
define('GV_FAQ', 'Gift Voucher FAQ');
define('ERROR_REDEEMED_AMOUNT', 'Congratulations, you have redeemed ');
define('ERROR_NO_REDEEM_CODE', 'You did not enter a redeem code.');  
define('ERROR_NO_INVALID_REDEEM_GV', 'Invalid Gift Voucher Code'); 
define('TABLE_HEADING_CREDIT', 'Credits Available');
define('GV_HAS_VOUCHERA', 'You have funds in your Gift Voucher Account. If you want <br>
                         you can send those funds by <a class="pageResults" href="');
       
define('GV_HAS_VOUCHERB', '"><b>email</b></a> to someone'); 
define('ENTRY_AMOUNT_CHECK_ERROR', 'You do not have enough funds to send this amount.'); 
define('BOX_SEND_TO_FRIEND', 'Send Gift Voucher');

define('VOUCHER_REDEEMED', 'Voucher Redeemed');
define('CART_COUPON', 'Coupon :');
define('CART_COUPON_INFO', 'more info');
// header text in includes/header.php
define('HEADER_TITLE_CREATE_ACCOUNT', 'Create an Account');
define('HEADER_TITLE_MY_ACCOUNT', 'My Account');
define('HEADER_TITLE_CART_CONTENTS', 'Cart Contents');
define('HEADER_TITLE_CHECKOUT', 'Checkout');
define('HEADER_TITLE_TOP', 'usitrip');
define('HEADER_TITLE_CATALOG', 'Catalog');
define('HEADER_TITLE_LOGOFF', 'Log Off');
define('HEADER_TITLE_LOGIN', 'Log In');

// footer text in includes/footer.php
define('FOOTER_TEXT_REQUESTS_SINCE', 'requests since');

// text for gender
define('MALE', 'Male');
define('FEMALE', 'Female');
define('MALE_ADDRESS', 'Mr.');
define('FEMALE_ADDRESS', 'Ms.');

// text for date of birth example
define('DOB_FORMAT_STRING', 'mm/dd/yyyy');

// categories box text in includes/boxes/categories.php
//define('BOX_HEADING_CATEGORIES_MAIN_PAGE', 'Categories');

// categories mainpage
define('BOX_HEADING_CATEGORIES_MAIN_PAGE', 'Categories');


// manufacturers box text in includes/boxes/manufacturers.php
//define('BOX_HEADING_MANUFACTURERS', 'Manufacturers');

// whats_new box text in includes/boxes/whats_new.php
//define('BOX_HEADING_WHATS_NEW', 'What\'s New?');

// quick_find box text in includes/boxes/quick_find.php
//define('BOX_HEADING_QUICK_SEARCH', 'Search');
define('BOX_SEARCH_TEXT', 'Use keywords to find the tour you are looking for.');
define('BOX_SEARCH_ADVANCED_SEARCH', 'Advanced Search');

// specials box text in includes/boxes/specials.php
//define('BOX_HEADING_SPECIALS', 'Specials');

// reviews box text in includes/boxes/reviews.php
//define('BOX_HEADING_REVIEWS', 'Reviews');
define('BOX_REVIEWS_WRITE_REVIEW', 'Write a review on this tour!');
define('BOX_REVIEWS_NO_REVIEWS', 'There are currently no tour reviews');
define('BOX_REVIEWS_TEXT_OF_5_STARS', '%s of 5 Stars!');

// shopping_cart box text in includes/boxes/shopping_cart.php
//define('BOX_HEADING_SHOPPING_CART', 'Shopping Cart');
define('BOX_SHOPPING_CART_EMPTY', '0 items');

// order_history box text in includes/boxes/order_history.php
//define('BOX_HEADING_CUSTOMER_ORDERS', 'Order History');

// best_sellers box text in includes/boxes/best_sellers.php
//define('BOX_HEADING_BESTSELLERS', 'Bestsellers');
define('BOX_HEADING_BESTSELLERS_IN', 'Bestsellers in<br>&nbsp;&nbsp;');

// notifications box text in includes/boxes/products_notifications.php
//define('BOX_HEADING_NOTIFICATIONS', 'Notifications');
define('BOX_NOTIFICATIONS_NOTIFY', 'Notify me of updates to <b>%s</b>');
define('BOX_NOTIFICATIONS_NOTIFY_REMOVE', 'Do not notify me of updates to <b>%s</b>');

// manufacturer box text
//define('BOX_HEADING_MANUFACTURER_INFO', 'Manufacturer Info');
define('BOX_MANUFACTURER_INFO_HOMEPAGE', '%s Homepage');
define('BOX_MANUFACTURER_INFO_OTHER_PRODUCTS', 'Other products');

// languages box text in includes/boxes/languages.php
//define('BOX_HEADING_LANGUAGES', 'Languages');

// currencies box text in includes/boxes/currencies.php
//define('BOX_HEADING_CURRENCIES', 'Currencies');


// tell a friend box text in includes/boxes/tell_a_friend.php
//define('BOX_HEADING_TELL_A_FRIEND', 'Tell A Friend');
define('BOX_TELL_A_FRIEND_TEXT', 'Tell someone you know about this tour.');

//BEGIN allprods modification
define('BOX_INFORMATION_ALLPRODS', 'View All Products');
//END allprods modification

//BEGIN all categories and products modification
define ('ALL_PRODUCTS_LINK', 'All Products sorted by Categories');
//END categories and products modification

//BEGIN all categories and products modification
define ('ALL_PRODUCTS_MANF', 'All Products sorted by Manufacturers');
//END categories and products modification

// VJ Links Manager v1.00 begin
define('BOX_INFORMATION_LINKS', '链接');
// VJ Links Manager v1.00 end

// checkout procedure text
define('CHECKOUT_BAR_DELIVERY', 'Delivery Information');
define('CHECKOUT_BAR_PAYMENT', 'Payment Information');
define('CHECKOUT_BAR_CONFIRMATION', 'Confirmation');
define('CHECKOUT_BAR_FINISHED', 'Finished!');

// pull down default text
define('PULL_DOWN_DEFAULT', 'Please Select');
define('TYPE_BELOW', 'Type Below');

// javascript messages
define('JS_ERROR', 'Errors have occured during the process of your form.\n\nPlease make the following corrections:\n\n');

define('JS_REVIEW_TEXT', '* The \'Review Text\' must have at least ' . REVIEW_TEXT_MIN_LENGTH . ' characters.\n');
define('JS_REVIEW_RATING', '* 您需要?定旅行的等?.\n');

define('JS_ERROR_NO_PAYMENT_MODULE_SELECTED', '* Please select a payment method for your order.\n');

define('JS_ERROR_SUBMITTED', 'This form has already been submitted. Please press Ok and wait for this process to be completed.');

define('ERROR_NO_PAYMENT_MODULE_SELECTED', 'Please select a payment method for your order.');

define('CATEGORY_COMPANY', 'Company Details');
define('CATEGORY_PERSONAL', '个人详细资讯');
define('CATEGORY_ADDRESS', 'Your Address');
define('CATEGORY_CONTACT', 'Your Contact Information');
define('CATEGORY_OPTIONS', 'Options');
define('CATEGORY_PASSWORD', 'Your Password');

define('ENTRY_COMPANY', 'Company Name:');
define('ENTRY_COMPANY_ERROR', '');
define('ENTRY_COMPANY_TEXT', '');
define('ENTRY_GENDER', 'Gender:');
define('ENTRY_GENDER_ERROR', 'Please select your Gender.');
define('ENTRY_GENDER_TEXT', '*');
define('ENTRY_FIRST_NAME', '　:');
define('ENTRY_FIRST_NAME_ERROR', 'Your First Name must contain a minimum of ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' characters.');
define('ENTRY_FIRST_NAME_TEXT', '*');
define('ENTRY_LAST_NAME', 'Last Name:');
define('ENTRY_LAST_NAME_ERROR', 'Your Last Name must contain a minimum of ' . ENTRY_LAST_NAME_MIN_LENGTH . ' characters.');
define('ENTRY_LAST_NAME_TEXT', '*');
define('ENTRY_DATE_OF_BIRTH', 'Date of Birth:');
define('ENTRY_DATE_OF_BIRTH_ERROR', 'Your Date of Birth must be in this format: MM/DD/YYYY (eg 05/21/1970)');
define('ENTRY_DATE_OF_BIRTH_TEXT', '* (eg. 05/21/1970)');
define('ENTRY_EMAIL_ADDRESS', 'E-Mail Address:');
define('ENTRY_EMAIL_ADDRESS_ERROR', 'Your E-Mail Address must contain a minimum of ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' characters.');
define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', 'Your E-Mail Address does not appear to be valid - please make any necessary corrections.');
define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', 'Your E-Mail Address already exists in our records - please log in with the e-mail address or create an account with a different address.');
define('ENTRY_EMAIL_ADDRESS_TEXT', '*');
define('ENTRY_STREET_ADDRESS', 'Street Address:');
define('ENTRY_STREET_ADDRESS_ERROR', 'Your Street Address must contain a minimum of ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' characters.');
define('ENTRY_STREET_ADDRESS_TEXT', '*');
define('ENTRY_SUBURB', 'Suburb:');
define('ENTRY_SUBURB_ERROR', '');
define('ENTRY_SUBURB_TEXT', '');
define('ENTRY_POST_CODE', 'Zip Code:');
define('ENTRY_POST_CODE_ERROR', 'Your Zip Code must contain a minimum of ' . ENTRY_POSTCODE_MIN_LENGTH . ' characters.');
define('ENTRY_POST_CODE_TEXT', '*');
define('ENTRY_CITY', 'City:');
define('ENTRY_CITY_ERROR', 'Your City must contain a minimum of ' . ENTRY_CITY_MIN_LENGTH . ' characters.');
define('ENTRY_CITY_TEXT', '*');
define('ENTRY_STATE', 'State/Province:');
define('ENTRY_STATE_ERROR', 'Your State must contain a minimum of ' . ENTRY_STATE_MIN_LENGTH . ' characters.');
define('ENTRY_STATE_ERROR_SELECT', 'Please select a state from the States pull down menu.');
define('ENTRY_STATE_TEXT', '*');
define('ENTRY_COUNTRY', 'Country:');
define('ENTRY_COUNTRY_ERROR', 'You must select a country from the Countries pull down menu.');
define('ENTRY_COUNTRY_TEXT', '*');
define('ENTRY_TELEPHONE_NUMBER', 'Telephone Number:');
define('ENTRY_TELEPHONE_NUMBER_COUNTRY_CODE', 'Country code ');
define('ENTRY_TELEPHONE_NUMBER_ERROR', 'Your Telephone Number must contain a minimum of ' . ENTRY_TELEPHONE_MIN_LENGTH . ' characters.');
define('ENTRY_TELEPHONE_NUMBER_TEXT', '*');
define('ENTRY_CELLPHONE_NUMBER',"(Please provide if available for emergency contact usage only):");
define('ENTRY_CELLPHONE_NUMBER_TEXT', '');
define('ENTRY_FAX_NUMBER', 'Fax Number:');
define('ENTRY_FAX_NUMBER_ERROR', '');
define('ENTRY_FAX_NUMBER_TEXT', '');
define('ENTRY_NEWSLETTER', 'Newsletter:');
define('ENTRY_NEWSLETTER_TEXT', '');
define('ENTRY_NEWSLETTER_YES', 'Subscribed');
define('ENTRY_NEWSLETTER_NO', 'Unsubscribed');
define('ENTRY_NEWSLETTER_ERROR', '');
define('ENTRY_PASSWORD', 'Password:');
define('ENTRY_PASSWORD_ERROR', 'Your Password must contain a minimum of ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.');
define('ENTRY_PASSWORD_ERROR_NOT_MATCHING', 'The Password Confirmation must match your Password.');
define('ENTRY_PASSWORD_TEXT', '*');
define('ENTRY_PASSWORD_CONFIRMATION', 'Password Confirmation:');
define('ENTRY_PASSWORD_CONFIRMATION_TEXT', '*');
define('ENTRY_PASSWORD_CURRENT', 'Current Password:');
define('ENTRY_PASSWORD_CURRENT_TEXT', '*');
define('ENTRY_PASSWORD_CURRENT_ERROR', 'Your Password must contain a minimum of ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.');
define('ENTRY_PASSWORD_NEW', 'New Password:');
define('ENTRY_PASSWORD_NEW_TEXT', '*');
define('ENTRY_PASSWORD_NEW_ERROR', 'Your new Password must contain a minimum of ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.');
define('ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING', 'The Password Confirmation must match your new Password.');
define('PASSWORD_HIDDEN', '--HIDDEN--');

define('FORM_REQUIRED_INFORMATION', '* Required information');

// constants for use in tep_prev_next_display function
define('TEXT_RESULT_PAGE', 'Result Pages:');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> tours)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> orders)');
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> reviews)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_NEW', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> new tours)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> specials)');
define('TEXT_DISPLAY_NUMBER_OF_FEATURED', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> featured tours)');
define('TEXT_DISPLAY_NUMBER_OF_REFERRALS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> referrals)');

define('PREVNEXT_TITLE_FIRST_PAGE', 'First Page');
define('PREVNEXT_TITLE_PREVIOUS_PAGE', 'Previous Page');
define('PREVNEXT_TITLE_NEXT_PAGE', 'Next Page');
define('PREVNEXT_TITLE_LAST_PAGE', 'Last Page');
define('PREVNEXT_TITLE_PAGE_NO', 'Page %d');
define('PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE', '前 %d 页');
define('PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE', '后 %d 页');
define('PREVNEXT_BUTTON_FIRST', '&lt;&lt;FIRST');
define('PREVNEXT_BUTTON_PREV', '[&lt;&lt;&nbsp;Prev]');
define('PREVNEXT_BUTTON_NEXT', '[Next&nbsp;&gt;&gt;]');
define('PREVNEXT_BUTTON_LAST', 'LAST&gt;&gt;');

define('IMAGE_BUTTON_ADD_ADDRESS', 'Add Address');
define('IMAGE_BUTTON_ADDRESS_BOOK', 'Address Book');
define('IMAGE_BUTTON_BACK', 'Back');
define('IMAGE_BUTTON_BUY_NOW', 'Buy Now');
define('IMAGE_BUTTON_CHANGE_ADDRESS', 'Change Address');
define('IMAGE_BUTTON_CHECKOUT', 'Checkout');
define('IMAGE_BUTTON_CONFIRM_ORDER', 'Confirm Order');
define('IMAGE_BUTTON_CONTINUE', 'Continue');
define('IMAGE_BUTTON_CONTINUE_SHOPPING', 'Continue Shopping');
define('IMAGE_BUTTON_DELETE', 'Delete');
define('IMAGE_BUTTON_EDIT_ACCOUNT', 'Edit Account');
define('IMAGE_BUTTON_HISTORY', 'Order History');
define('IMAGE_BUTTON_LOGIN', 'Sign In');
define('IMAGE_BUTTON_IN_CART', 'Add to Cart');
define('IMAGE_BUTTON_NOTIFICATIONS', 'Notifications');
define('IMAGE_BUTTON_QUICK_FIND', 'Quick Find');
define('IMAGE_BUTTON_REMOVE_NOTIFICATIONS', 'Remove Notifications');
define('IMAGE_BUTTON_REVIEWS', 'Reviews');
define('IMAGE_BUTTON_SEARCH', 'Search');
define('IMAGE_BUTTON_SHIPPING_OPTIONS', 'Shipping Options');
define('IMAGE_BUTTON_TELL_A_FRIEND', 'Tell a Friend');
define('IMAGE_BUTTON_UPDATE', 'Update');
define('IMAGE_BUTTON_UPDATE_CART', 'Update Cart');
define('IMAGE_BUTTON_WRITE_REVIEW', 'Write Review');

define('SMALL_IMAGE_BUTTON_DELETE', 'Delete');
define('SMALL_IMAGE_BUTTON_EDIT', '编辑');
define('SMALL_IMAGE_BUTTON_VIEW', 'View');

define('ICON_ARROW_RIGHT', 'more');
define('ICON_CART', 'In Cart');
define('ICON_ERROR', 'Error');
define('ICON_SUCCESS', 'Success');
define('ICON_WARNING', 'Warning');

define('TEXT_CUSTOMER_GREETING_HEADER', 'Our Customer Greeting');
define('TEXT_GREETING_PERSONAL', 'Welcome back <span class="greetUser">%s!</span> Would you like to see which <a href="%s"><u>new products</u></a> are available to purchase?');
define('TEXT_GREETING_PERSONAL_RELOGON', '<small>If you are not %s, please <a href="%s"><u>log yourself in</u></a> with your account information.</small>');
define('TEXT_GREETING_GUEST', 'Welcome <span class="greetUser">Guest!</span> Would you like to <a href="%s"><u>log yourself in</u></a>? Or would you prefer to <a href="%s"><u>create an account</u></a>?');

define('TEXT_SORT_PRODUCTS', 'Sort products ');
define('TEXT_DESCENDINGLY', 'descending');
define('TEXT_ASCENDINGLY', 'ascending');
define('TEXT_BY', ' by ');

define('TEXT_REVIEW_BY', 'by %s');
define('TEXT_REVIEW_WORD_COUNT', '%s words');
define('TEXT_REVIEW_RATING', 'Rating: %s [%s]');
define('TEXT_REVIEW_DATE_ADDED', 'Date Added: %s');
define('TEXT_NO_REVIEWS', 'There are currently no tours reviews.');

define('TEXT_NO_NEW_PRODUCTS', 'There are currently no products.');

define('TEXT_NO_PRODUCTS', 'There are currently no products in this range.');

define('TEXT_UNKNOWN_TAX_RATE', 'Unknown tax rate');

define('TEXT_REQUIRED', '<span class="errorText">Required</span>');

// Down For Maintenance
define('TEXT_BEFORE_DOWN_FOR_MAINTENANCE', 'NOTICE: This website will be down for maintenance on: ');
define('TEXT_ADMIN_DOWN_FOR_MAINTENANCE', 'NOTICE: The website is currently Down For Maintenance to the public');

define('ERROR_TEP_MAIL', '<font face="Verdana, Arial" size="2" color="#ff0000"><b><small>TEP ERROR:</small> Cannot send the email through the specified SMTP server. Please check your php.ini setting and correct the SMTP server if necessary.</b></font>');
define('WARNING_INSTALL_DIRECTORY_EXISTS', 'Warning: Installation directory exists at: ' . dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/install. Please remove this directory for security reasons.');
define('WARNING_CONFIG_FILE_WRITEABLE', 'Warning: I am able to write to the configuration file: ' . dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/includes/configure.php. This is a potential security risk - please set the right user permissions on this file.');
define('WARNING_SESSION_DIRECTORY_NON_EXISTENT', 'Warning: The sessions directory does not exist: ' . tep_session_save_path() . '. Sessions will not work until this directory is created.');
define('WARNING_SESSION_DIRECTORY_NOT_WRITEABLE', 'Warning: I am not able to write to the sessions directory: ' . tep_session_save_path() . '. Sessions will not work until the right user permissions are set.');
define('WARNING_SESSION_AUTO_START', 'Warning: session.auto_start is enabled - please disable this php feature in php.ini and restart the web server.');
define('WARNING_DOWNLOAD_DIRECTORY_NON_EXISTENT', 'Warning: The downloadable products directory does not exist: ' . DIR_FS_DOWNLOAD . '. Downloadable products will not work until this directory is valid.');

/*
define('TEXT_CCVAL_ERROR_INVALID_DATE', 'The expiry date entered for the credit card is invalid.<br>Please check the date and try again.');
define('TEXT_CCVAL_ERROR_INVALID_NUMBER', 'The credit card number entered is invalid.<br>Please check the number and try again.');
define('TEXT_CCVAL_ERROR_UNKNOWN_CARD', 'The first four digits of the number entered are: %s <br>If that number is correct, we do not accept that type of credit card.<br>If it is wrong, please try again.');
*/

define('TEXT_CCVAL_ERROR_INVALID_DATE', 'The expiry date entered for the credit card is invalid. Please check the date and try again.');
define('TEXT_CCVAL_ERROR_INVALID_NUMBER', 'The credit card number entered is invalid. Please check the number and try again.');
define('TEXT_CCVAL_ERROR_UNKNOWN_CARD', 'The first four digits of the number entered are: %s  If that number is correct, we do not accept that type of credit card. If it is wrong, please try again.');


/*
  The following copyright announcement can only be
  appropriately modified or removed if the layout of
  the site theme has been modified to distinguish
  itself from the default Chainreaction-copyrighted
  theme.

  For more information please read the following
  Frequently Asked Questions entry on the osCommerce
  support site:

  http://www.oscommerce.com/community.php/faq,26/q,50

  Please leave this comment intact together with the
  following copyright announcement.
*/
//define('FOOTER_TEXT_BODY', 'Copyright &copy; 2003 <a href="http://www.oscommerce.com" target="_blank">Oscommerce</a> and <a href="http://www.chainreactionweb.com" target="_blank">CRE Loaded Team</a><br>Powered by <a href="http://www.oscommerce.com" target="_blank">Oscommerce</a><font color="red"> Supercharged by</font> <a href="http://www.chainreactionweb.com" target="_blank">CRE Loaded Team</a><br>Using Version ' . PROJECT_VERSION);
define('FOOTER_TEXT_BODY', 'Copyright &copy;2005-2006 usitrip.com, All rights reserved. <br>All prices and specifications are subject to change without notice. <br>usitrip.com is not responsible for typographical errors. All typographical errors are subject to correction.');

require(DIR_FS_LANGUAGES . 'add_ccgvdc_english.php');
/////////////////////////////////////////////////////////////////////
// HEADER.PHP
// Header Links
define('HEADER_LINKS_DEFAULT','HOME');
define('HEADER_LINKS_WHATS_NEW','WHAT\'S NEW?');
define('HEADER_LINKS_SPECIALS','SPECIALS');
define('HEADER_LINKS_REVIEWS','REVIEWS');
define('HEADER_LINKS_LOGIN','LOGIN');
define('HEADER_LINKS_LOGOFF','LOG OFF');
define('HEADER_LINKS_PRODUCTS_ALL','CATALOG');
define('HEADER_LINKS_ACCOUNT_INFO','ACCOUNT INFO');
define('HEADER_LINKS_LINKS','LINKS');
define('HEADER_LINKS_FAQ','FAQS');
define('HEADER_LINKS_NEWS','NEWS');
define('HEADER_LINKS_INFORMATION','INFORMATION');
/////////////////////////////////////////////////////////////////////

// BOF: Lango added for print order mod
define('IMAGE_BUTTON_PRINT_ORDER', 'Order printable');
// EOF: Lango added for print order mod

// WebMakers.com Added: Attributes Sorter
require(DIR_FS_LANGUAGES . $language . '/' . 'attributes_sorter.php');

// wishlist box text in includes/boxes/wishlist.php
define('BOX_HEADING_CUSTOMER_WISHLIST', 'My Wishlist');
define('BOX_WISHLIST_EMPTY', 'You have no items on your Wishlist');
define('IMAGE_BUTTON_ADD_WISHLIST', 'Add to Wishlist');
define('TEXT_WISHLIST_COUNT', 'Currently %s items are on your Wishlist.');
define('TEXT_DISPLAY_NUMBER_OF_WISHLIST', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> items on your wishlist)');
define('BOX_HEADING_CUSTOMER_WISHLIST_HELP', 'Wishlist Help');
define('BOX_HEADING_SEND_WISHLIST', 'Send your Wishlist');
define('BOX_TEXT_MOVE_TO_CART', 'Move to Cart');
define('BOX_TEXT_DELETE', 'Delete');

//DWD Modify: Information Page Unlimited 1.1f - PT
// define('BOX_HEADING_INFORMATION', 'Info System');
 define('BOX_INFORMATION_MANAGER', 'Info Manager');
//DWD Modify End
//include('includes/languages/english_support.php');
include('includes/languages/english_newsdesk.php');
include('includes/languages/english_faqdesk.php');
/*Begin Checkout Without Account images*/
define('IMAGE_BUTTON_CREATE_ACCOUNT', 'Create Account');
define('NAV_ORDER_INFO', 'Order Info');
/*End Checkout WIthout Account images*/


/*advance search*/
define('DURATION', 'Duration:');
define('DEPARTURE_CITY', '恁　杰　Select Departure City:');
define('TEXT_NONE', '-- Select Departure City --');
define('OPTIONAL_KEYWORD', 'Optional Keywords:');
define('START_DATE', 'Start Date:');
define('IGNORE','Ignore');

define('HEADING_SHIPPING_INFORMATION', 'Shipping Information');

// about us
  define('BOX_INFORMATION_ABOUT_US','About Us');
  define('BOX_INFORMATION_CONDITIONS', 'Conditions of Use');
  define('BOX_INFORMATION_SITE_MAP', 'Site Map');
  define('BOX_INFORMATION_CONTACT', 'Contact Us');
  //amit added new for language start
  define('BOX_INFORMATION_PRIVACY_AND_POLICY', 'Privacy and Security Policy');
  define('BOX_INFORMATION_PAYMENT_FAQ','Payment FAQ');
  define('BOX_INFORMATION_COPY_RIGHT','Copy Right');
  define('BOX_INFORMATION_CUSTOMER_AGREEMENT','Customer Agreement');
  define('BOX_INFORMATION_LINK_TO_US','Link to us');
  define('BOX_INFORMATION_CANCELLATION_REFUND_POLICY','Cancellation and Refund Policy');  
  define('BOX_INFORMATION_VIEW_ALL_TOURS','View All Tours'); 
  define('HEADING_ATTRACTION', 'Attraction:'); 
//amit added new for language end
    define('TEXT_DISPLAY_NUMBER_OF_QUESTIONS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> questions)');
	
//added for product listing page start
define('TEXT_WELCOME_TO','欢迎来到');
define('TEXT_CUSTOMER_AGREE_BOOK','Please read Customer Agreement before booking online.');
define('TEXT_TOUR_PICKUP_NOTE','A <FONT COLOR="#0000ff">Tour Package</FONT> usually includes airport pick-up and/or drop-off.');
define('TEXT_SORT_BY','Sort By:');
define('TEXT_TELL_YOUR_FRIEND','Tell your friend');
define('TEXT_ABOUT',' about ');
define('TEXT_AND_MAKE','and make');
define('TEXT_COMMISSION','commission!');
define('TEXT_TOUR_ITINERARY','旅行线路:');
define('TEXT_DEPART_FROM','Depart from:');
define('TEXT_OPERATE','Operate:');
define('TEXT_PRICE','Price:');
define('TEXT_HIGHLIGHTS','Highlights:');
define('TEXT_DURATION','Duration:');
define('TEXT_DETAILS','Details');
//added for product listing page end

//why book form us text

define('TEXT_TOP_HEADING_BOOK','Why Book From Us?');

define('TAB_SPECIALLY_DESIGN_TOURS','specially Designed Tours');
define('TAB_LOW_PRICE_GUANRANTEED','Low Price Guanranteed');
define('TAB_EXPERIENCED_DRIVER','Experienced Driver');
define('TAB_PROFESSIONAL_TOUR_DUIDE','Professional Tour Guide');
define('TAB_EXCELLETN_CUSTOMER_SERVICES','Excellent Customer Service');

define('TEXT_PARA_SPECIALLY_DESIGN_TOURS','We have specially designed tours for our customers this season in this year, such as Yellowstone & Mt. Rushmore Tours, Yellowstone & Grand Canyon Tours, and more. You can find these special offers from No One Else.');
define('TEXT_PARA_LOW_PRICE_GUANRANTEED','We are tour providers as well as direct agency with a lot of well-known tour providers all over the states. We guanrantee our customers get real value for the money spent. You will spend 4 times more if you plan the same tours on your own, no kidding.');
define('TEXT_PARA_EXPERIENCED_DRIVER','We have had years of experience planning tours and vacations, we know that you will not enjoy your staying in a mini bus, jeep, or van on the 3-10 day tours. That\'s why we provide you with wide space and luxury equipped motor coach. It is equipped with climate controller air conditioning, reclining seats, individual reading lights, storage compartment, VCD/DVD and monitors, and clean restroom. Our driver has years of experience driving motor coach.');
define('TEXT_PARA_PROFESSIONAL_TOUR_DUIDE','We provide tour guiding service, our professional tour guide will tell you about local history, geology, and a lot more and will share his/her experience and funny stories with you. So you won\'t get bored on the trip.');
define('TEXT_PARA_EXCELLETN_CUSTOMER_SERVICES','Customer is in the first place at usitrip.com. Our professional customer service representatives are working hard to ensure that every guest is satisfied from start to end.');

define('TEXT_NO_QUESTION_FOUND','No Question Found.');
define('TEXT_SEARCH_FOR_YOUR_TOUR','Search for Your Tours Here');
?>
