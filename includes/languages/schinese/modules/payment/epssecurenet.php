<?php
/*
  $Id: epssecurenet.php,v 1.0 2003/08/19 Exp $

// EPSsecurenet
// Built For osCommerce 2.2MS2 version 
//--------------------------------
// Developed BY Greg Goodman
// Copyright 2003 Electronic Payment Systems
//--------------------------------
// This code is provided as is. EPS provides no warranty for the code
// and the use of this code is done so with the express understanding that
// it is used at the user's risk. Furthermore EPS may not be held
// accountable for any losses resulting from the use of this code, including
// associated modules.
//--------------------------------

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
define('MODULE_PAYMENT_EFSNET_TEXT_TITLE', 'Credit Card : EPSsecurenet');
	define('MODULE_PAYMENT_EPSSECURENET_CART_SYSTEM_NAME', 'OsCommerce');
	define('MODULE_PAYMENT_EPSSECURENET_CONNECTION_TYPE', 'sd');
	define('MODULE_PAYMENT_EPSSECURENET_ORDER_DESCRIP', 'Refer to OScommerce Customer Order Screen');
  define('MODULE_PAYMENT_EPSSECURENET_TEXT_TITLE', 'EPSsecurenet');
  define('MODULE_PAYMENT_EPSSECURENET_TEXT_DESCRIPTION', 'EPSsecurenet Payment Module Credit Card Test Info:<br><br>CC#: 5123456789012346<br>Expiry: Any'); 
	define('MODULE_PAYMENT_EPSSECURENET_TEXT_CREDIT_CARD_OWNER', 'Credit Card Owner:');
  define('MODULE_PAYMENT_EPSSECURENET_TEXT_CREDIT_CARD_NUMBER', 'Credit Card Number:');
  define('MODULE_PAYMENT_EPSSECURENET_TEXT_CREDIT_CARD_EXPIRES', 'Credit Card Expiry Date:');
	define('MODULE_PAYMENT_EPSSECURENET_TEXT_CREDIT_CARD_CVV2', 'CVV2 Security Number:');
  define('MODULE_PAYMENT_EPSSECURENET_TEXT_JS_CC_OWNER', '* The owner\'s name of the credit card must be at least ' . CC_OWNER_MIN_LENGTH . ' characters.\n');
  define('MODULE_PAYMENT_EPSSECURENET_TEXT_JS_CC_NUMBER', '* The credit card number must be at least ' . CC_NUMBER_MIN_LENGTH . ' characters.\n');
  define('MODULE_PAYMENT_EPSSECURENET_TEXT_JS_CC_CVV2', '* The CVV2 Number must at least be three or four characters.\n    -For VISA/MasterCard this number is on the back of the Card.\n    -For American Express it is 4 digits and located on the front of the Card.\n');
  define('MODULE_PAYMENT_EPSSECURENET_TEXT_ERROR_MESSAGE', 'There has been an error processing your credit card. Please try again.');
  define('MODULE_PAYMENT_EPSSECURENET_TEXT_ERROR', 'Credit Card Error!');
?>
