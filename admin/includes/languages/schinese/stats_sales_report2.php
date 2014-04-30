<?php
/*
  $Id: stats_customers.php,v 1.9 2002/03/30 15:03:59 harley_vb Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('REPORT_DATE_FORMAT', 'm. d. Y');

define('HEADING_TITLE', '销售报表2');

define('REPORT_TYPE_YEARLY', 'Yearly');
define('REPORT_TYPE_MONTHLY', 'Monthly');
define('REPORT_TYPE_WEEKLY', 'Weekly');
define('REPORT_TYPE_DAILY', 'Daily');
define('REPORT_START_DATE', 'from date');
define('REPORT_END_DATE', 'to date (inclusive)');
define('REPORT_DETAIL', 'detail');
define('REPORT_MAX', 'show top');
define('REPORT_ALL', 'all');
define('REPORT_SORT', 'sort');
define('REPORT_EXP', 'export');
define('REPORT_SEND', 'send');
define('EXP_NORMAL', 'normal');
define('EXP_HTML', 'HTML only');
define('EXP_CSV', 'CSV');

define('TABLE_HEADING_DATE', '购买日期');
define('TABLE_HEADING_ORDERS', '订单数');
define('TABLE_HEADING_ITEMS', '订单产品数');
define('TABLE_HEADING_REVENUE', '销售金额');
define('TABLE_HEADING_COST', '成本(Cost)');
define('TABLE_HEADING_SHIPPING', 'Shipping');

define('DET_HEAD_ONLY', 'no details');
define('DET_DETAIL', 'show details');
define('DET_DETAIL_ONLY', 'details with amount');

define('SORT_VAL0', 'standard');
define('SORT_VAL1', 'description');
define('SORT_VAL2', 'description desc');
define('SORT_VAL3', '#Items');
define('SORT_VAL4', '#Items desc');
define('SORT_VAL5', 'Revenue');
define('SORT_VAL6', 'Revenue desc');

define('REPORT_STATUS_FILTER', 'Status');

define('SR_SEPARATOR1', ';');
define('SR_SEPARATOR2', ';');
define('SR_NEWLINE', '\n\r');

define('TABLE_HEADING_GROSS_PROFIT', '毛利(Gross Profit)');
define('TABLE_HEADING_GROSS_PROFIT_PERCENTAGE', '毛利率 Gross Profit<br>(%)');
?>
