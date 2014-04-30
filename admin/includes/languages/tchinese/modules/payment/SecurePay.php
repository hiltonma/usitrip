<?php
/*

 Based on payment Module of iongate.php (06/11/2003) Modified for Securepay.com by:

Tony Reynolds  <tonyr@securepay.com>

SecurePay.php version 1.2 06/11/2003

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  define('MODULE_PAYMENT_SECUREPAY_TEXT_TITLE', 'Credit Card : SecurePay');
  define('MODULE_PAYMENT_SECUREPAY_TEXT_DESCRIPTION', 'SecurePay payment module. <BR>Credit Card Test Info:<br>Aprovals<br>CC#: 4111111111111111<br>Expiry: Any <BR> Declines <BR>CC#: 5105105105105100<br>Expiry: Any ');
  define('MODULE_PAYMENT_SECUREPAY_TEXT_TYPE', 'Credit Card Type:');
  define('MODULE_PAYMENT_SECUREPAY_TEXT_CREDIT_CARD_OWNER', 'Credit Card Owner:');
  define('MODULE_PAYMENT_SECUREPAY_TEXT_CREDIT_CARD_NUMBER', 'Credit Card Number:');
  define('MODULE_PAYMENT_SECUREPAY_TEXT_CREDIT_CARD_EXPIRES', 'Credit Card Expiry Date:');
  define('MODULE_PAYMENT_SECUREPAY_TEXT_JS_CC_OWNER', '* The owner\'s name of the credit card must be at least ' . CC_OWNER_MIN_LENGTH . ' characters.\n');
  define('MODULE_PAYMENT_SECUREPAY_TEXT_JS_CC_NUMBER', '* The credit card number must be at least ' . CC_NUMBER_MIN_LENGTH . ' characters.\n');
  define('MODULE_PAYMENT_SECUREPAY_TEXT_ERROR_MESSAGE', 'There has been an error processing your credit card. Please try again.');
  define('MODULE_PAYMENT_SECUREPAY_TEXT_GATEWAY_TIMEOUT', 'There was an error contacting the credit card processor. Please try again.');
  define('MODULE_PAYMENT_SECUREPAY_TEXT_ERROR', 'There was a problem processing your Credit Card! Not-Approved');
  define('MODULE_PAYMENT_SECUREPAY_TEXT_WRONG_TYPE', 'The Credit Card Number does not match the Credit Card Type.');
?>
