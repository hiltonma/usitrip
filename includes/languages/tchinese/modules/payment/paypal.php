<?php
/*
  $Id: paypal.php,v 2.8 2004/09/11 devosc Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  DevosC, Developing open source Code
  http://www.devosc.com

  Copyright (c) 2002 osCommerce
  Copyright (c) 2004 DevosC.com

  Released under the GNU General Public License
*/

  define('MODULE_PAYMENT_PAYPAL_TEXT_TITLE', 'PayPal');
  define('MODULE_PAYMENT_PAYPAL_TEXT_DESCRIPTION', 'PayPal');

  define('MODULE_PAYMENT_PAYPAL_CC_TEXT', "信用卡 %s%s%s%s 或者 %s");
  define('MODULE_PAYMENT_ACC', "PayPal帳戶");

  define('MODULE_PAYMENT_PAYPAL_IMAGE_BUTTON_CHECKOUT', 'PayPal Checkout');
  define('MODULE_PAYMENT_PAYPAL_CC_DESCRIPTION','您可以使用信用卡而不註冊paypal帳戶');
  define('MODULE_PAYMENT_PAYPAL_CC_URL_TEXT','[詳情]');

  define('MODULE_PAYMENT_PAYPAL_CUSTOMER_COMMENTS', 'Add Comments About Your Order');
  define('MODULE_PAYMENT_PAYPAL_TEXT_TITLE_PROCESSING', 'Processing transaction');
  define('MODULE_PAYMENT_PAYPAL_TEXT_DESCRIPTION_PROCESSING', 
  '
  <dl>
		<dt><img alt="paypal" src="/image/pic10.jpg"></dt>
		<dd>
			<ul>
				<li><strong class="font_size14 color_blue">Paypal使用說明：</strong></li>
				<li>1、走四方提供的paypal支付方式是全球通用的安全級別較高的支付方式  </li>
				<li>2、可以從全球190個國家和地區進行付款，輕鬆方便，快捷高效，無需填寫繁瑣的詳情</li>
				<li>3、即時到賬，無任何手續費，走四方讓您省錢輕鬆旅遊</li>
			</ul>
		</dd>
	</dl>
  ');
  define('MODULE_PAYMENT_PAYPAL_IMAGE_BUTTON_CHECKOUT', 'PayPal Checkout');
  define('MODULE_PAYMENT_PAYPAL_TEXT_WARM_TIPS','&nbsp;');

?>