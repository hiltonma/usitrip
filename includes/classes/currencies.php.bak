<?php
/*
$Id: currencies.php,v 1.1.1.1 2004/03/04 23:40:42 ccwjr Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2003 osCommerce

Released under the GNU General Public License
*/

////
// Class to handle currencies
// TABLES: currencies
/**
 * 货币处理类
 * @package 
 *
 */
class currencies {
	var $currencies;

	// class constructor
	function currencies() {
		$this->currencies = array();
		$currencies_query = tep_db_query("select code, title, symbol_left, symbol_right, decimal_point, thousands_point, decimal_places, value from " . TABLE_CURRENCIES);
		while ($currencies = tep_db_fetch_array($currencies_query)) {
			$this->currencies[$currencies['code']] = array('title' => $currencies['title'],
			'symbol_left' => $currencies['symbol_left'],
			'symbol_right' => $currencies['symbol_right'],
			'decimal_point' => $currencies['decimal_point'],
			'thousands_point' => $currencies['thousands_point'],
			'decimal_places' => $currencies['decimal_places'],
			'value' => $currencies['value']);
		}
	}

	// class methods
	/**
	 * 格式化输出货币值
	 *
	 * @param unknown_type $number	源货币数值
	 * @param unknown_type $calculate_currency_value 是否要计算货币转换值
	 * @param unknown_type $currency_type 要输出的币种
	 * @param unknown_type $currency_value 汇率
	 * @return unknown
	 */
	function format($number, $calculate_currency_value = true, $currency_type = '', $currency_value = '') {
		global $currency;

		if (empty($currency_type)) $currency_type = $currency;
		if (empty($currency_type)) $currency_type = 'USD';
		if (empty($this->currencies[$currency_type]['decimal_point'])) $this->currencies[$currency_type]['decimal_point'] = '.';

		if ($calculate_currency_value == true) {
			$rate = (tep_not_null($currency_value)) ? $currency_value : $this->currencies[$currency_type]['value'];
			$format_string = $this->currencies[$currency_type]['symbol_left'] . number_format(tep_round($number * $rate, $this->currencies[$currency_type]['decimal_places']), $this->currencies[$currency_type]['decimal_places'], $this->currencies[$currency_type]['decimal_point'], $this->currencies[$currency_type]['thousands_point']) . $this->currencies[$currency_type]['symbol_right'];	
				
			// if the selected currency is in the european euro-conversion and the default currency is euro,
			// the currency will displayed in the national currency and euro currency
			if ( (DEFAULT_CURRENCY == 'EUR') && ($currency_type == 'DEM' || $currency_type == 'BEF' || $currency_type == 'LUF' || $currency_type == 'ESP' || $currency_type == 'FRF' || $currency_type == 'IEP' || $currency_type == 'ITL' || $currency_type == 'NLG' || $currency_type == 'ATS' || $currency_type == 'PTE' || $currency_type == 'FIM' || $currency_type == 'GRD') ) {
				$format_string .= ' <small>[' . $this->format($number, true, 'EUR') . ']</small>';
			}
		} else {
			$format_string = $this->currencies[$currency_type]['symbol_left'] . number_format(tep_round($number, $this->currencies[$currency_type]['decimal_places']), $this->currencies[$currency_type]['decimal_places'], $this->currencies[$currency_type]['decimal_point'], $this->currencies[$currency_type]['thousands_point']) . $this->currencies[$currency_type]['symbol_right'];
		}

		// BOF: WebMakers.com Added: Down for Maintenance
		if (DOWN_FOR_MAINTENANCE=='true' && DOWN_FOR_MAINTENANCE_PRICES_OFF=='true') {
			$format_string= '';
		}
		// BOF: WebMakers.com Added: Down for Maintenance

		if(preg_match('/-/',$format_string)){
			$format_string = '-'.str_replace('-','',$format_string);
		}
		return $format_string;
	}

	function is_set($code) {
		if (isset($this->currencies[$code]) && tep_not_null($this->currencies[$code])) {
			return true;
		} else {
			return false;
		}
	}

	function get_value($code) {
		return $this->currencies[$code]['value'];
	}

	function get_decimal_places($code) {
		return $this->currencies[$code]['decimal_places'];
	}

	function display_price($products_price, $products_tax, $quantity = 1) {


		global $currency;
		global $content;

		if($content == 'index_default' || $content == 'index_nested' || $content == 'index_products' || $content == 'ajax_landingPages'){
			return $this->format(tep_add_tax($products_price, $products_tax) * $quantity, true, 'USD');
		}else if($content =='product_info'){
			return $this->format(tep_add_tax($products_price, $products_tax) * $quantity, true, 'USD');
		}else if($content == 'account'){
			return $this->format(tep_add_tax($products_price, $products_tax) * $quantity, true, 'USD');
		}else if($content =='budget_calculation'){
			return $this->format(tep_add_tax($products_price, $products_tax) * $quantity, true, 'USD') .' / '. $this->format(tep_add_tax($products_price, $products_tax) * $quantity, true, 'CNY');
		}else{
			return $this->format(tep_add_tax($products_price, $products_tax) * $quantity);
		}

	}
}
?>
