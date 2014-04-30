<?php
/*
  $Id: default.php,v 1.1.1.1 2003/03/22 16:56:02 nickle Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2002 osCommerce
  Released under the GNU General Public License

  Traditional Chinese language pack(Big5 code) for osCommerce 2.2 ms1
  Community: http://forum.kmd.com.tw 
  Author(s): Nickle Cheng (nickle@mail.kmd.com.tw)
  Released under the GNU General Public License ,too!!

*/

define('TEXT_MAIN', '本線上購物系統僅為展示之用， <b>任何訂購的商品都不會出貨或產生帳單</b>，所有商品的任何資訊也都視為無效。<br><br>如果您想下載這個線上購物系統或是分享程式給我們，請拜訪<a href="http://oscommerce.com"><u>支援網站</u></a>。本線上購物系統架構於 <font color="#f0000"><b>' . PROJECT_VERSION . '</b></font>.<br><br>若要修改上面所顯示的文字，可以手動修改，檔案路徑為 [webroot]/catalog/includes/languages/tchinese/default.php<br>或是經由系統工具/語系定義選項，或系統工具/檔案總管 來修改 default.php');

define('TABLE_HEADING_NEW_PRODUCTS', '%s份新進商品');
define('TABLE_HEADING_UPCOMING_PRODUCTS', '商品上市預告');
define('TABLE_HEADING_DATE_EXPECTED', '預計上市日期');

if ($category_depth == 'products' || $HTTP_GET_VARS['manufacturers_id']) {
  define('HEADING_TITLE', '商品列表');
  define('TABLE_HEADING_IMAGE', '');
  define('TABLE_HEADING_MODEL', '型號');
  define('TABLE_HEADING_PRODUCTS', '品名');
  define('TABLE_HEADING_MANUFACTURER', '製造廠商');
  define('TABLE_HEADING_QUANTITY', '數量');
  define('TABLE_HEADING_PRICE', '價格');
  define('TABLE_HEADING_WEIGHT', '重量');
  define('TABLE_HEADING_BUY_NOW', '馬上買');
  define('TEXT_NO_PRODUCTS', '本目錄目前沒有任何商品.');
  define('TEXT_NO_PRODUCTS2', '本製造場商目前沒有任何商品.');
  define('TEXT_NUMBER_OF_PRODUCTS', '商品數量: ');
  define('TEXT_SHOW', '<b>顯示:</b>');
  define('TEXT_BUY', '馬上買\'');
  define('TEXT_NOW', '\'');
  define('TEXT_ALL', '全部');
} elseif ($category_depth == 'top') {
  define('HEADING_TITLE', '有什麼新鮮的？');
} elseif ($category_depth == 'nested') {
  define('HEADING_TITLE', '商品分類');
}
?>