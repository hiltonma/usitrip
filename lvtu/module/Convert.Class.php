<?php
/**
 * 一些常用的转换
 * @author lwkai
 * @date 2012-11-9 下午4:48:53
 * @link <1275124829@163.com>lwkai
 * @formatter:off
 */
class Convert {
	
	/**
	 * 对单引号和双引号进行转义。
	 * 单引号（'）、双引号（"）、反斜线（\）与 NUL（NULL 字符）。 
	 * @param string $string
	 * @return string
	 */
	private static function _escape($string) {
		if(!get_magic_quotes_gpc()){
			$string = addslashes($string);
		}
		return $string;
	}
	
	/**
	 * 对单引号和双引号进行转义。
	 * 单引号（'）、双引号（"）、反斜线（\）与 NUL（NULL 字符）。 
	 * @param string|array $string 需要转义的对象，字符串或者数组
	 * @return string|array
	 */
	public static function escape($string) {
		if (is_string($string) == true) {
			$string = self::_escape($string);
		} elseif (is_array($string) == true) {
			foreach ($string as $key => $val) {
				$string[$key] = self::escape($val);
			}
		}
	}
	
	/**
	 * 对数据进行格式化，以便写入数据库 注意 不支持数组
	 * @param string $string 要处理的字符串数据
	 * @return string
	 * @author lwkai 2013-2-28 下午4:17:36
	 */
	public static function db_input($string){
		if (function_exists('mysql_real_escape_string')) {
			return mysql_real_escape_string($string);
		} elseif (function_exists('mysql_escape_string')) {
			return mysql_escape_string($string);
		} else {
			return self::escape($string);
		}	
	}
	
	/**
	 * 对单引号和双引号进行反转义。
	 * 单引号（'）、双引号（"）、反斜线（\）与 NUL（NULL 字符）。
	 * @param string $string
	 * @return string
	 */
	private static function _unescape($string) {
		$string = str_replace("\\\"", "\"", $string);
		$string = str_replace("\\'", "'", $string);
		if (preg_match("/\\\\/",$string)) {
			$string = str_replace("\\\\", "\\", $string);
		}
		return $string;
	}
	
	/**
	 * 对单引号和双引号进行反转义。
	 * 单引号（'）、双引号（"）、反斜线（\）与 NUL（NULL 字符）。
	 * @param string|Array $string
	 * @return string|Array
	 */
	public static function unescape($string) {
		if (is_string($string)) {
			$string = trim(self::_unescape($string));
		} elseif (is_array($string)) {
			foreach ($string as $key => $value) {
				$string[$key] = self::unescape($value);
			}
		}
		return $string;
	}
	
	/**
	 * 把HTML特殊字符转换成标准的HTML代码。
	 * & => &amp;   (&amp;amp;)  
	 * " => &quot;  (&amp;quot;) 
	 * ' => ' 
	 * < => &lt;    (&amp;lt;)　
	 * > => &gt;    (&amp;gt;) 
	 * @param string $string
	 * @return string|array
	 */
	private static function _special_chars_html($string) {
		return preg_replace("/&amp;/", "&", htmlspecialchars($string,ENT_QUOTES));
	}
	
	/**
	 * 把HTML特殊字符转换成标准的HTML代码。
	 * & => &amp;   (&amp;amp;)
	 * " => &quot;  (&amp;quot;)
	 * ' => '
	 * < => &lt;    (&amp;lt;)　
	 * > => &gt;    (&amp;gt;)
	 * @param string|array $string 需要转换的对象，字符串或者数组
	 * @return string|array
	 */
	public static function special_chars_html($string) {
		if (is_string($string) == true) {
			$string = self::_special_chars_html($string);
		} elseif (is_array($string) == true) {
			foreach ($string as $key => $val) {
				$string[$key] = self::_special_chars_html($val);
			}
		}
		return $string;
	}
	
	/**
	 * 把文字转换成GB2312的编码
	 * @param string|array $string 需要转换的对象，字符串或者字符串数组
	 * @return string|array
	 * @author lwkai 2012-11-19 上午11:22:19
	 */
	public static function html_to_db($string, $charset) {
		if ($charset == 'big5') {
			if (is_array($string) == true) {
				foreach ($string as $key => $val) {
					$string[$key] = self::html_to_db($val, $charset);
				}
			} elseif (is_string($string) == true) {
				$string = self::_html_to_db($string, $charset);
			}
		}
		return $string;
	}
	
	/**
	 * 转换字符串为GB2312编码
	 * @param string $string 需要转换的字符串
	 * @return string
	 * @author lwkai 2012-11-19 上午11:23:28
	 */
	private static function _html_to_db($string, $charset) {
		if ($charset == 'big5') {
			$string = rare_convert::big5_to_gb2312($string);
			$arr = array('许' => "许\\", '功' => "功\\", '盖' => "盖\\", '谷' => "谷\\", '餐' => "餐\\", '泪' => "泪\\", '枯' => "枯\\", '娉' => "娉\\");
			foreach ($arr as $key => $val) {
				$string = str_replace($val, $key, $string);
			}
			$string = iconv($charset,'gb2312//IGNORE',$string);
		}
		return $string;
	}

	/**
	 * 简体转繁体
	 * @param string|array $string 需要转换的对象，字符串或者字符串数组
	 * @param string $charset 当前页面的编码
	 * @return string|array
	 * @author lwkai 2012-11-19 上午11:45:34
	 */
	public static function db_to_html($string,$charset) {
		if ($charset != 'gb2312') {
			if (is_array($string) == true) {
				foreach ($string  as $key => $val) {
					$string[$key] = self::db_to_html($val,$charset);
				}
			} elseif (is_string($string) == true) {
				$string = Rare_Convert::gb2312_to_big5($string);
				$string = iconv('gb2312',$charset . '//IGNORE',$string);
			}
		}
		return $string;
	}
	
	/**
	 * 去除字符串首尾空格,然后将字符串中的多个空格用一个空格替代, '<' 或者 '>' 符号用 '_' 替代
	 * @param string $string
	 * @return string
	 * @author lwkai 2012-12-26 下午1:36:45
	 */
	public static function sanitize_string($string) {
		$patterns = array ('/ +/','/[<>]/');
		$replace = array (' ', '_');
		return preg_replace($patterns, $replace, trim($string));
	}
	
	/**
	 *  当传入参数为字符串或者值为字符串的数组时,去除 字符串 或 数组中的值 的首尾空格,然后将字符串中的多个空格用一个空格替代, '<' 或者 '>' 符号用 '_' 替代
	 * 当传入非字符串或者值不是字符串的数组时, 不做处理
	 * @param string|array $string
	 * @return string
	 * @author lwkai 2012-12-26 下午1:37:57
	 */
	public static function db_prepare_input($string) {
		if (is_string($string)) {
			return trim(self::sanitize_string(stripslashes($string)));
		} elseif (is_array($string)) {
			foreach ($string as $key => $value) {
				//递归调用自己
				$string[$key] = self::db_prepare_input($value);
			}
			return $string;
		} else {
			return $string;
		}
	}
	
	/**
	 * 递归转文字编码
	 * @param string $inCharset 当前文字编码
	 * @param string $toCharset 转换后的文字编码
	 * @param string|array $str 转换的对象
	 * @return array|string
	 * @author lwkai 2013-2-27 下午2:33:39
	 */
	public static function iconv($inCharset,$toCharset,$str) {
		if (is_array($str) || is_object($str)) {
			foreach( $str as $key => $val) {
				$str[$key] = self::iconv($inCharset, $toCharset, $val);
			}
			return $str;
		} else {
			return iconv($inCharset,$toCharset . '//IGNORE',$str);
		} 
	}
	
	/**
	 * XML转成数组
	 * @param string|object $xml XML字符串或者 XML对象
	 * @param boolean $recursive 传进来的$xml参数值如果是string对象,则此参数应为true
	 * @return array 
	 * @author lwkai 2013-2-27 下午6:10:11
	 */
	public static function XML2Array($xml, $recursive = false) {
		if (!$recursive) {
			$obj = simplexml_load_string($xml);
		} else 	{
			$obj = $xml;
		}
		$_arr = is_object($obj) ? get_object_vars($obj) : $obj;
		$arr = array();
		foreach ($_arr as $key => $val)
		{
			$val = (is_array($val) || is_object($val)) ? self::XML2Array($val,true) : $val;
			$arr[$key] = $val;
		}
		return $arr;
	}
}
?>