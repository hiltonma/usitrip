<?php
/*
  $Id: specials.php,v 1.2 2004/03/05 00:36:42 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Specials');

define('TABLE_HEADING_PRODUCTS', 'Products');
define('TABLE_HEADING_PRODUCTS_PRICE', 'Products Price');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_ACTION', 'Action');

define('TEXT_SPECIALS_PRODUCT', 'Product:');
define('TEXT_SPECIALS_SPECIAL_PRICE', 'Special Price:');
define('TEXT_SPECIALS_EXPIRES_DATE', 'Expiry Date:');
define('TEXT_SPECIALS_PRICE_TIP', '<b>Specials Notes:</b><ul><li>You can enter a percentage to deduct in the Specials Price field, for example: <b>20%</b></li><li>If you enter a new price, the decimal separator must be a \'.\' (decimal-point), example: <b>49.99</b></li><li>Leave the expiry date empty for no expiration</li></ul>');

define('TEXT_INFO_DATE_ADDED', 'Date Added:');
define('TEXT_INFO_LAST_MODIFIED', 'Last Modified:');
define('TEXT_INFO_NEW_PRICE', 'New Price:');
define('TEXT_INFO_ORIGINAL_PRICE', 'Original Price:');
define('TEXT_INFO_PERCENTAGE', 'Percentage:');
define('TEXT_INFO_EXPIRES_DATE', 'Expires At:');
define('TEXT_INFO_STATUS_CHANGE', 'Status Change:');

define('TEXT_INFO_HEADING_DELETE_SPECIALS', 'Delete Special');
define('TEXT_INFO_DELETE_INTRO', 'Are you sure you want to delete the special products price?');

define('SPECIALS_TYPE0', '普通特价');
define('SPECIALS_TYPE1', '限量团');
define('SPECIALS_TYPE2', '限时团');
define('SPECIALS_TITLE_TYPE0', '普通特价');
define('SPECIALS_TITLE_TYPE1', '限量团：即倒数团，产品具有专一性，产品一定，出团时间一定，名额限制，倒数团购。根据能选择的最近出发日期来倒数。');
define('SPECIALS_TITLE_TYPE2', '限时团购：限时优惠，线路一定，出发时间不定，限时优惠，就是说只有在某段时间段内价格是优惠的。（根据特价中设定的到期日设置倒计时，如果特价中没设置到期日期则，根据能选择的最近出发日期来倒数）');

define('GROUP_BUY_TAG','[团购]');
define('MAX_BUY_NUM','每个团购阶段最多<br>购买人数[限量团]：');
define('REMAINING_NUM','剩余人数[限量团]：');
define('JS_ERROR_MSN','团购团必须要选择开始日期和结束日期。');
define('JS_ERROR_MSN1','请设置最多购买人数。');
define('INVITE_INFO','提示信息：');
define('ISSUE_NUM', '期数：');
define('ISSUE_NUM_TIPS',"关于团购期数：
一、默认情况下，设置的团购产品与其它团产品的“Start Date”和“Expiry Date”一样的话，将自动设置为与那个团购为相同的期数；人工干涉除外！
二、每次同时修改“Start Date”和“Expiry Date”时，程序将自动视为增加新一期。
三、只有限时团有期数的概念，而限量团则无这个概念。
四、如有疑问，可与“Engineering::分期记录历史团购页面_设计 added”这个主题的邮件对比。
");
?>