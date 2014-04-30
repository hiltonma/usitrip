<?php



define('NAVBAR_TITLE_ENGLISH','Acknowledgement of card billing');

define('HEADING_TITLE_ENGLISH','Acknowledgement of card billing');

define('TEXT_HEADING_I_ENGLISH','I, ');

define('TEXT_PARA_1_ENGLISH',' have authorized usitrip (Merchant) to charge my   [Visa Card]   [MasterCard]  (circle one) in the amount of $ ');

define('TEXT_PARA_1_2_ENGLISH',' US dollars.');

define('TEXT_CARD_NUMBER_ENGLISH','My card number is:');

define('TEXT_EXPIRATION_DATE_ENGLISH','My expiration date is:');

define('TEXT_CARD_CVV_ENGLISH','The card code (three or four digit code on back of card) is:');

define('TEXT_NAME_APPEAR_ON_CARD_ENGLISH','My name as it appears on my card is:');

define('TEXT_BILLING_ADDRESS_ENGLISH','The billing address for my card is:');

define('TEXT_SCANNER_NOTES_ENGLISH','Please make imprint of credit card in box to left with crayon, pencil or scanner.');

define('TEXT_CARD_HOLDERS_SIGN_ENGLISH',"(Cardholder’s Signature)");

define('TEXT_DATE_ENGLISH',"Date:");





define('NAVBAR_TITLE', '信用卡支付驗證書');

define('HEADING_TITLE', '信用卡支付驗證書');

define('TEXT_HEADING_I','我, ');

//define('TEXT_PARA_1',' 授權usitrip (銷售方) 通過本人的[Visa卡]   [Master卡]  (請在您的信用卡類別上畫圈)，為本人的訂單__________（訂單號）支付$');
define('TEXT_PARA_1',' 授權usitrip (銷售方) 通過本人的[Visa卡]   [Master卡]  (請在您的信用卡類別上畫圈)，為本人的訂單 <u>C'.$_POST['order_id'].'</u>（訂單號）支付$');

define('TEXT_PARA_1_2',' 美元。');

define('TEXT_CARD_NUMBER','本人的信用卡卡號:');

define('TEXT_EXPIRATION_DATE','本人的信用卡有效期限:');

define('TEXT_CARD_CVV','信用卡號（信用卡後三位或者四位的阿拉伯數字）是:');

define('TEXT_NAME_APPEAR_ON_CARD','本人在信用卡上顯示的名字是:');

define('TEXT_BILLING_ADDRESS','本人的信用卡帳戶地址是:');

define('TEXT_SCANNER_NOTES','請在右邊的方框內放入您的信用卡複印件，您可以 使用有色筆，鉛筆或者掃描儀進行複印。');

define('TEXT_CARD_HOLDERS_SIGN',"（信用卡持卡人簽名）");

define('TEXT_DATE',"日期：");





?>