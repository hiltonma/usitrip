<?php
/*
  $Id: gv_faq.php,v 1.2 2004/03/05 00:36:42 ccwjr Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Gift Voucher System v1.0
  Copyright (c) 2001,2002 Ian C Wilson
  http://www.phesis.org

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'ÊÙôÀÄü¡¡Ç»¶¼â±¡¡¡¡¼úÏæGift Voucher FAQ');
define('HEADING_TITLE', 'Gift Voucher FAQ');

define('TEXT_INFORMATION', '<a name="Top"></a>
  <a href="'.tep_href_link(FILENAME_GV_FAQ,'faq_item=1','NONSSL').'">»®Ç¹Äü¡¡Purchasing Gift Vouchers</a><br>
  <a href="'.tep_href_link(FILENAME_GV_FAQ,'faq_item=2','NONSSL').'">»ª¡¡µĞ¡¡Äü¡¡How to send Gift vouchers</a><br>
  <a href="'.tep_href_link(FILENAME_GV_FAQ,'faq_item=3','NONSSL').'">¡¡ò»Äü¡¡»®ÑôBuying with Gift Vouchers</a><br>
  <a href="'.tep_href_link(FILENAME_GV_FAQ,'faq_item=4','NONSSL').'">Ä¼Ò£Äü¡¡Redeeming Gift Vouchers</a><br>
  <a href="'.tep_href_link(FILENAME_GV_FAQ,'faq_item=5','NONSSL').'">¶¼â±¡¡¡¡When problems occur</a><br>
');
switch ($HTTP_GET_VARS['faq_item']) {
  case '1':
define('SUB_HEADING_TITLE','»®Ç¹Äü¡¡Purchasing Gift Vouchers.');
define('SUB_HEADING_TEXT','»®Ç¹Äü¡¡í¬¡¡»®Ç¹¡¡Ó¬ØÇèõ¡¡Ç»¡¡ÛæÀò¡¡¡¡¡¡¡¡µÈ¨ÛÀ¯ñİ¡¡Á£ò»¶¼ò»Ç»¡¡İáÔ´¡¡»®Ç¹¡²¡¡Í²»®Ç¹Ø÷÷Õ¨ÛÄü¡¡í¬ÆÄ¡¡Â¥ÉÆÀ¯Ç»Äü¡¡¡¡¡¡¡¡¨Û¾½±ğÀ¯Ç»Äü¡¡¡¡¡¡¡¡¡¡Ãİô¬¡¡Ìß¨ÛÀ¯ñİ¡¡Òù»®ÑôÚîÈÄÏ·ËÒÉÆÀ¯¡¡ÒùÇ»ÌßÍ¿¡²°¹¡¡õÙÀŞ¡¡¡¡¡¡¶¦Ç»ó°ÚÃÀ¯Ñ·ñİ¡¡¡¡³¹»×Á§ÎµÀ¯Ç»Äü¡¡¡¡¡¡õÓÀ¯Ç»À¿¡¡ÕÍÕÍ¡²Gift Vouchers are purchased just like any other item in our store.  You can 
  pay for them using the stores standard payment method(s).
  Once purchased the value of the Gift Voucher will be added to your own personal 
  Gift Voucher Account.  If you have funds in your Gift Voucher Account, you will 
  notice that the amount now shows in the Shopping Cart box, and also provides a 
  link to a page where you can send the Gift Voucher to some one via email.');
  break;
  case '2':
define('SUB_HEADING_TITLE','»ª¡¡µĞ¡¡Äü¡¡How to Send Gift Vouchers.');
define('SUB_HEADING_TEXT','¾½±ğÀ¯í¼µĞ¡¡Äü¡¡¨Û¥ïÉö·­¡¡Ó¬Ç»µĞ¡¡Äü¡¡¡¡¡¡¡²À¯ñİ¡¡Òù·ª¶å¡¡¡¡Ç»¡¡ß¯ÍíÏ·õ·Ç»»®ÑôÚîÈÄÏ·ËÒÉÆíñÊÙó°ÚÃ¡¡¡¡¡²ÒùÀ¯µĞ¡¡Äü¡¡Ç»¡¡½ô¨Û¡¡áû¡¡íÉ¡¡¡¡¡¡·Ì¨éÀ¯í¼µĞ¡¡Äü¡¡Ç»¡¡Ç»¡¡ÃÒª®»×¡¡»ª¡¡ª®Äü¡¡¡¡½²¨×¡¡íÉÀ¯Ïé¡¡áû²Îìµ¡¡ô¬Ç»Äü¡¡Òû¡¡µÌ¨Øª®¡¡ÉµÇ»ÒşêÉ¡¡¡¡ª®¡¡¶áÒù»×Á§Ø÷÷Õ¿¬¡¡¡¡¥´¨ÛÀ¯ô¬½ßËÃÙ¯ÆÄòÛ²Ü¡¡¡¡¡¡¡¡¨ÛËñ¡¡¥ïƒ¡¡¡¡¡@¡@¡¡hÌêÊ£¡¡¡¡¡¡ô¬¡¡¡¡¡²To send a Gift Voucher you need to go to our Send Gift Voucher Page. You can 
  find the link to this page in the Shopping Cart Box in the right hand column of 
  each page.
  When you send a Gift Voucher, you need to specify the following.
  The name of the person you are sending the Gift Voucher to.
  The email address of the person you are sending the Gift Voucher to.
  The amount you want to send. (Note you don\'t have to send the full amount that 
  is in your Gift Voucher Account.)
  A short message which will appear in the email.
  Please ensure that you have entered all of the information correctly, although 
  you will be given the opportunity to change this as much as you want before 
  the email is actually sent.');  
  break;
  case '3':
  define('SUB_HEADING_TITLE','¡¡ò»Äü¡¡»®ÑôBuying with Gift Vouchers.');
  define('SUB_HEADING_TEXT','¾½±ğÀ¯Ç»Äü¡¡¡¡¡¡¡¡ô¬¡¡ÌßÇ»¸Ï¨ÛÀ¯ñİ¡¡ò»¡¡¡¡¡¡Ìß»®Ç¹¡¡Ó¬ØÇèõ¡¡Ç»¡¡ÛæÀò¡¡¨ÛÒù¡¡İá¡¡¨ÛÆÄô¬ÇÚ¸ÏÒÅËÚµÌõÓÀ¯¡¡¡¡¨ÛİÇÆ§íñÜíÇ»ÇÚ¸ÏÒÅí¬ñİ¡¡ò»Äü¡¡Á¾¡¡¡¡İáÊ£¡²¥ï¡¡íÉ¨Û¾½±ğÀ¯Ç»Äü¡¡¡¡¡¡¡¡Ç»¡¡ÌßÏéåÍ¡¡¡¡İáÀ¯Ç»¡¡Ãº¨ÛÀ¯®÷°¯ñİ¡¡í¥¡¡¡¡ÛæÇ»¡¡İáÔ´¡¡¨Û¾½±ğÀ¯Ç»Äü¡¡¡¡¡¡Òù¡¡İá×º®÷ô¬¡¡Í¿¨Û¡¡¡¡¡¡Í¿ÎµÆÄÒşº¸À¯¡¡°ô¡¡Ãº¡¡ò»¡²If you have funds in your Gift Voucher Account, you can use those funds to 
  purchase other items in our store.  At the checkout stage, an extra box will 
  appear.  Ticking this box will apply those funds in your Gift Voucher Account. 
  Please note, you will still have to select another payment method if there 
  is not enough in your Gift Voucher Account to cover the cost of your purchase. 
  If you have more funds in your Gift Voucher Account than the total cost of 
  your purchase the balance will be left in your Gift Voucher Account for 
  future purchases.');
  break;
  case '4':
  define('SUB_HEADING_TITLE','Ä¼Ò£Äü¡¡Redeeming Gift Vouchers.');
  define('SUB_HEADING_TEXT','¾½±ğÀ¯¡¡³¹»×Á§¡¡ÉÆ¡¡¶åÄü¡¡¨Û¾ô¡¡¸¾äô¡¡¡¡ßù¡¡¨é¡¡Äü¡¡¡¡Ç»¡¡ÃÒó°°¹¡¡ÉµÇ»ÒşêÉ¨Û¡¡Ö¿Äü¡¡Ç»¡¡½²¡²À¯¡¡ÒÉÎµÄü¡¡ºş¡¡µÌ¶®Îµ¶®ò»¡¡Í³½¶î£»×ÕÊ¨ÛÀ¯ñİ¡¡¡¡³¹¡¡¡¡°ùóÑÔ´¡¡Ä¼Ò£Äü¡¡¨é1. ¡¡³¹İÇÆ§»×Á§¡¡¡¡¶¦Ç»ó°ÚÃ¨ÛÀ¯ñİ¡¡ÉÆÕ¿Ä¼Ò£Äü¡¡Ç»¡¡¡¡¨ÛÒùÀ¯¡¡ÁÄÊ£¡¡¶å¡¡¡¡×º¨ÛÀ¯Ç»Äü¡¡ÎµÑÚÓû»ê¨Û¡¡¡¡À¯í¬ñİ¡¡¡¡ò»Äü¡¡¡¡¡¡¡¡Ç»¡¡ÌßÁ¾¡¡»®ÑôÊ£¡²2. Òù¡¡İáÇ»¡¡½ô¨ÛÒùìµí¥¡¡¡¡İáÔ´¡¡Ç»íñ°¹¡¡¡¡¡¡ÆÄËÒÉÆ¡¡¶åáû§¡í³ÌêÄ¼Ò£ÑñÎÙÇ»»³ÌêÒÅ¨ÛÒù¡¡¾ô»³ÌêÀ¯Ç»Ä¼Ò£ÑñÎÙ¨Û¡¡İÇÆ§Ä¼Ò£Ù¼Ìı¨ÛÄ¼Ò£ÑñÎÙí¬ÆÄÑÚÓû»êÌğÎµÄü¡¡ÄÊÂ¥ÉÆÀ¯Ç»¡¡¡¡¡¡¨Û¡¡¡¡À¯í¬ñİ¡¡¡¡ò»¡¡¡¡Äü¡¡¶®»®Ç¹¡¡Ó¬ØÇèõ¡¡Ç»¡¡Ë¯Àò¡¡Ê£¡²If you receive a Gift Voucher by email it will contain details of who sent 
  you the Gift Voucher, along with possibly a short message from them.  The Email 
  will also contain the Gift Voucher Number.  It is probably a good idea to print 
  out this email for future reference.  You can now redeem the Gift Voucher in 
  two ways.<br>
  1. By clicking on the link contained within the email for this express purpose. 
  This will take you to the store\'s Redeem Voucher page.  You will then be requested 
  to create an account, before the Gift Voucher is validated and placed in your 
  Gift Voucher Account ready for you to spend it on whatever you want.<br>
  2. During the checkout process, on the same page that you select a payment method 
there will be a box to enter a Redeem Code.  Enter the code here, and click the redeem button.  The code will be
validated and added to your Gift Voucher account.  You can then use the amount to purchase any item from our store');
  break;
  case '5':
  define('SUB_HEADING_TITLE','¶¼â±¡¡¡¡When problems occur.');
  define('SUB_HEADING_TEXT','ÊÙôÀÄü¡¡¡¡¡¡ô¬¡¡Ë¯¡¡¡¡Ç»¸Ï¨Û¥ï¼»¡¡¨é¨Û¨Û¨Û¥ïÒù»×Á§¡¡¡¡¶¦¡¡ñİÙç¡¡¡¡Ç»íñÊÙ¡¡¡¡¡²¥ïí¥¡¡¡¡¡¡¡¡¡¡¡¡Ç»¡¡óÑ¡²For any queries regarding the Gift Voucher System, please contact the store 
  by email at '. STORE_OWNER_EMAIL_ADDRESS . '. Please make sure you give 
  as much information as possible in the email. ');
  break;
  default:
  define('SUB_HEADING_TITLE','');
  define('SUB_HEADING_TEXT','Please choose from one of the questions above.');

  }
?>
