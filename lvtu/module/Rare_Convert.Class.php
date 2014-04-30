<?php
/**
 * 特殊文字转换，繁简转换的时候有些特殊文字不能转，这里预先做处理
 * @author lwkai
 * @date 2012-11-15 下午1:33:35
 * @link <1275124829@163.com>lwkai
 * @formatter:off
 */
class Rare_Convert {
	
	/**
	 * BIG5编码的特殊文字
	 * @var array
	 * @author lwkai
	 * @date 2012-11-15 下午1:32:50
	 */
	private static $_text_big5 = array();
	
	/**
	 * GB2312特殊文字
	 * @var array
	 * @author lwkai 2012-11-15 下午4:10:49
	 */
	private static $_text_gb2312 = array();

	/**
	 * 繁体中文转简体中文时，预先处理那些不能能过ICONV转换的文字
	 * @param string $str 需要转换的字符串
	 * @return string
	 * @author lwkai 2012-11-19 上午11:07:18
	 */
	public static function big5_to_gb2312($str) {
		if ( $str == '' ) {
			return $str;
		}
		if (!self::$_text_big5) {
			self::$_text_big5 = require('Rare_Big5.php');
		}
		foreach (self::$_text_big5 as $key => $val) {
			$str = str_replace($val, '&#'.$key.';', $str);
		}
		return $str;
	}
	
	/**
	 * 简体中文转繁体中文时，预先处理那些不能能过ICONV转换的文字
	 * @param string $str 需要处理的字符串
	 * @return string
	 * @author lwkai 2012-11-19 上午11:11:11
	 */
	public static function gb2312_to_big5($str) {
		if (!$str) {
			return '';
		}
		if (!self::$_text_gb2312) {
			self::$_text_gb2312 = require('Rare_Gb2312.php');
		}
		foreach (self::$_text_gb2312 as $key => $val) {
			$str = str_replace($val, '&#'.$key.';', $str);
		}
		return $str;
	}
	
}


?>