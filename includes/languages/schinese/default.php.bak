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

define('TEXT_MAIN', '本线上购物系统仅为展示之用， <b>任何订购的商品都不会出货或产生帐单</b>，所有商品的任何资讯也都视为无效。<br><br>如果您想下载这个线上购物系统或是分享程式给我们，请拜访<a href="http://oscommerce.com"><u>支援网站</u></a>。本线上购物系统架构于 <font color="#f0000"><b>' . PROJECT_VERSION . '</b></font>.<br><br>若要修改上面所显示的文字，可以手动修改，档案路径为 [webroot]/catalog/includes/languages/schinese/default.php<br>或是经由系统工具/语系定义选项，或系统工具/档案总管 来修改 default.php');

define('TABLE_HEADING_NEW_PRODUCTS', '%s份新进商品');
define('TABLE_HEADING_UPCOMING_PRODUCTS', '商品上市预告');
define('TABLE_HEADING_DATE_EXPECTED', '预计上市日期');

if ($category_depth == 'products' || $HTTP_GET_VARS['manufacturers_id']) {
  define('HEADING_TITLE', '商品列表');
  define('TABLE_HEADING_IMAGE', '');
  define('TABLE_HEADING_MODEL', '型号');
  define('TABLE_HEADING_PRODUCTS', '品名');
  define('TABLE_HEADING_MANUFACTURER', '制造厂商');
  define('TABLE_HEADING_QUANTITY', '数量');
  define('TABLE_HEADING_PRICE', '价格');
  define('TABLE_HEADING_WEIGHT', '重量');
  define('TABLE_HEADING_BUY_NOW', '马上买');
  define('TEXT_NO_PRODUCTS', '本目录目前没有任何商品.');
  define('TEXT_NO_PRODUCTS2', '本制造场商目前没有任何商品.');
  define('TEXT_NUMBER_OF_PRODUCTS', '商品数量: ');
  define('TEXT_SHOW', '<b>显示:</b>');
  define('TEXT_BUY', '马上买\'');
  define('TEXT_NOW', '\'');
  define('TEXT_ALL', '全部');
} elseif ($category_depth == 'top') {
  define('HEADING_TITLE', '有什么新鲜的？');
} elseif ($category_depth == 'nested') {
  define('HEADING_TITLE', '商品分类');
}
?>