<?php
/*
  $Id: index.php,v 1.1 2003/06/11 17:38:00 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/




//FOR TABEL HEADIGND
define('TABLE_HEADING_NEW_PRODUCTS', '特色景点');
define('TABLE_HEADING_UPCOMING_PRODUCTS', '即将推出的商品');
define('TABLE_HEADING_UPCOMING_SERVICES_ARTICLES', '即将推出的服务、工作坊');
define('TABLE_HEADING_DEFAULT_SPECIALS', '%s 特别推荐的特价商品');


define('TEXT_VIEW', '查看更多关于');
define('TEXT_TOURS', '点击查看全部');

define('TABLE_HEADING_DATE_EXPECTED', '预计日期');

if ( ($category_depth == 'products') || (isset($HTTP_GET_VARS['manufacturers_id'])) ) {
//  define('HEADING_TITLE', '来瞧瞧我们店里有什么');
  define('HEADING_TITLE', '');
  define('TABLE_HEADING_IMAGE', '图片');
  define('TABLE_HEADING_MODEL', '种类');
  define('TABLE_HEADING_PRODUCTS', '商品名称');
  define('TABLE_HEADING_MANUFACTURER', '制造厂商');
  define('TABLE_HEADING_QUANTITY', '数量');
  define('TABLE_HEADING_PRICE', '价格');
  define('TABLE_HEADING_WEIGHT', '重量');
  define('TABLE_HEADING_BUY_NOW', '下手了');
  define('TEXT_NO_PRODUCTS', '无此类相关产品');
  define('TEXT_NO_PRODUCTS2', '本制造场商目前没有任何商品.');
  define('TEXT_NUMBER_OF_PRODUCTS', '商品数量: ');
  define('TEXT_SHOW', '<b>显示:</b>');
  define('TEXT_BUY', '增加对购物篮子');
  define('TEXT_NOW', '\' 现在');
  define('TEXT_ALL_CATEGORIES', '景点目录');
  define('TEXT_ALL_MANUFACTURERS', '所有制造商');
} elseif ($category_depth == 'top') {
  define('HEADING_TITLE', '新到景点？');
} elseif ($category_depth == 'nested') {
  define('HEADING_TITLE', '景点目录');
}

define('TEXT_DURATION_DAY','天');
define('TEXT_DURATION_HOUR','小时');
define('TEXT_DURATION_DAYS','天');
define('TEXT_DURATION_HOURS','小时');

define('HEADING_TEXT_NEW_ARRIVE','<strong>新品推荐</strong>');
define('HEADING_TEXT_ON_SALES','<strong>近期促销</strong>');
define('HEADING_TEXT_BEST_SALLERS','<strong>精品路线</strong>');
define('TEXT_SHOW_HIDE_MAP','显示 / 隐藏 地图');
define('TEXT_DIRATION_MINUTES','分钟');
?>
