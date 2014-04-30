<?php
/**
 * 文件类 读取文件内容 保存内容到文件
 * @author lwkai 2012-4-15 <1275124829@163.com>
 * @version 1.0
 */
class read_file {
	/**
	 * 需要读取的文件
	 *
	 * @var string|array
	 */
	protected  $_files = null;
	
	/**
	 * 保存读取的文件内容，或者待写入的内容
	 * 如果读取的是数组，则该属性为数组
	 * 写入时，只能是字符串
	 *
	 * @var string|array
	 */
	protected $_string = '';
	
	/**
	 * 构造函数 初始化该类时 可传入需要读取的文件
	 *
	 * @param array|string $file
	 */
	public function __construct($file = array()) {
		$this->setFiles($file);
	}
	
	/**
	 * 设置需要读取的文件
	 *
	 * @param string|array $file
	 */
	public function setFiles($file = array()) {
		if (is_array($file)) {
			foreach ($file as $key => $val) {
				if (is_string($val)) {
					$this->_files[$key] = $val;
				}
			}
		} elseif (is_string($file)) {
			$this->_files = $file;
		}
	}
	
	/**
	 * 打开一个文件并读取内容,如果内容正确读取,则返回true 否则抛出异常。
	 * 
	 *
	 * @param string $keyName 需要打开的文件名,如果初始化时传入的是数组,则传入需要读取的数组键名,如果
	 * @return boolean 
	 */
	private function open($keyName){
		if (file_exists($keyName)) {
			$file = $keyName;
		} elseif (isset($this->_files[$keyName])) {
			$file = $this->_files[$keyName];
		} elseif (is_string($this->_files) && file_exists($this->_files)) {
			$file = $this->_files;
		}
		if (!file_exists($file)) {
			throw new Exception('[' . $file . ']文件不存在!');
		}
		$this->_string = file($file);
		return true;
		
	}
	
	/**
	 * 读取一个文件,返回指定的 array 或者 string
	 *
	 * @param string $file 文件或者初始化时传进来的数组KEY值
	 * @param array $type 返回的数据类型
	 */
	public function read($file,$type = 'string') {
		$this->open($file);
		return $this->_string;
	}
	
	/**
	 * 把指定的字符串写入文件
	 *
	 * @param string $fileName 写入的文件名[完整路径]
	 * @param string $mode 写入方式 a w 等等
	 * @return boolean 成功返回真 否则抛出异常
	 */
	public function write($fileName = '', $mode = 'a') {
		// 如果文件存在并且不可写 
		if (file_exists($fileName) && !is_writable($fileName)) {
			throw new Exception('文件存在,但不可写!');
		}
		if (!$handle = fopen($fileName,$mode)) {
			throw new Exception('不能打开文件[' . $fileName . ']');
		}
		if (fwrite($handle,$this->_string) == false) {
			throw new Exception('不能写入文件到[' . $fileName . ']');
		}
		fclose($handle);
		return true;
	}
}


if(!function_exists('mb_string_to_array')){
function mb_string_to_array($str,$charset) {
    $strlen = mb_strlen($str);
    while($strlen){
        $array[] = mb_substr($str,0,1,$charset);
        $str = mb_substr($str,1,$strlen,$charset);
        $strlen = mb_strlen($str);
    }
    return $array;
}
}
//$arr = mb_string_to_array($str,"gb2312");

/**
 * 格式化敏感词汇，生成词汇树
 * @author lwkai 2012-4-15 <1275124829@163.com>
 * @version 1.0
 */
class format extends read_file {
	
	/**
	 * 词汇树 多元数组
	 *
	 * @var array
	 */
	public $_tree = array();
	
	/**
	 * 构造函数 可事先传入要读取的文件
	 *
	 * @param string|array $file
	 */
	public function __construct($file = array()) {
		parent::__construct($file);
	}
	
	/**
	 * 判断当前词汇树是否已经存在于先前已经保存的词汇树中
	 * 
	 * 判断当前词汇树是否已经存在于先前已经保存的词汇树中，如果存在则返回true，否则返回false
	 * @param array $arr1
	 * @param array $arr2
	 * @return boolean
	 */
	private function array_intersect($arr1=array(),$arr2=array()) {
		foreach ($arr1 as $key => $val) {
			if (is_array($val) && isset($arr2[$key])) {
				 return $this->array_intersect($val,$arr2[$key]);
			} elseif (!is_array($val) && isset($arr2[$key])) {
				return true;
			} else {
				return false;
			}
		}
	}
	/**
	 * 把中文等号替换成英文等号,并且去掉换行符
	 *
	 * @param string $str
	 * @return string
	 */
	private function replaceEquals($str) {
		if (is_string($str)) {
			$str = preg_replace("/＝/",'=',$str);
			$str = preg_replace("/\r|\n/",'',$str);
		}
		return $str;
	}
	
	/**
	 * 此函数(方法)把文件中的内容 转成 词汇树。
	 *
	 * @param string $file
	 */
	public function read($file=''){
		// 读取文件，返回数组  [数组中的每个单元都是文件中相应的一行]
		$string = parent::read($file);
		$exit = false;
		if (is_array($string)) {
			foreach ($string as $key => $val) {
				$val = $this->replaceEquals($val);
				$arr = explode('=',$val);
				$temp = mb_string_to_array($arr[0],'gb2312');
				$arr_temp = array();
				for($i = $len = count($temp) - 1; $i >= 0; $i--) {
					if ($i == $len) {
						$arr_temp = array($temp[$i]=>array('text'=>$arr[1],'end' => true));
					} else {
						$arr_temp = array($temp[$i] => $arr_temp);
					}
				}
				// 去掉重复的词
				if (!$this->array_intersect($arr_temp,$this->_tree)) {
					$this->_tree = array_merge_recursive($this->_tree,$arr_temp);
				} 
			}
		}
	}
	
	/**
	 * 读取所有已经设置的文件，并转换成词汇树
	 *
	 */
	public function readAll(){
		set_time_limit(0);
		if (is_array($this->_files)) {
			foreach ($this->_files as $key => $val) {
				$this->read($val);
			}
		} else {
			$this->read($this->_files);
		}
	}
	
	/**
	 * 保存词汇树为文件[php文件]
	 *
	 * @param string $filePath 保存的文件名
	 */
	public function write($filePath) {
		$str = var_export($this->_tree, true);
		$this->_string = '<?php' . "\nreturn " . $str . '?>';
		// var_export 把变量以PHP标准的字符串返回, 第二个参数为 true 是返回这个变量 
		parent::write($filePath,'w');
	}
}

/**
 * 字符串过滤类，过滤非法关键词，避免出现在网站上。
 * @author lwkai
 *
 */
class stringFilter{
	/**
	 * 转换词汇树的对象
	 * @var Ojbect
	 */
	private $_format = null;
	/**
	 * 有可能用户在非法词中夹入的字符
	 *
	 * @var array
	 */
	private $_detection = array('*','#','@','　','!','$','%','^',' ','&','-','+','/','_');
	
	public function __construct($file = array()){
		if(!tep_not_null($file)){
			$file = DIR_FS_CATALOG.'txt'. DIRECTORY_SEPARATOR . 'MinGanCi_0523.txt';
		}
		$this->_format = new format($file);
		if (!file_exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'word_filter_array.php')) {
			$this->_format->readAll();
			$this->_format->write(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'word_filter_array.php');
		} else {
			$this->_format->_tree = include(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'word_filter_array.php');
		}
	}
	
	/**
	 * 是否是用户夹杂的词,是则返回true 否返回flase
	 *
	 * @param string $str
	 * @return boolean
	 */
	private function isDetection($str) {
		return !in_array($str,$this->_detection);
	}

	/**
	 * 替换敏感词为设定的内容
	 *
	 * @param string $word
	 * @param array $arr
	 * @return string
	 */
	private function replaceWord($word,$arr){
		foreach ($arr as $key => $val) {
			$word = str_replace($key,$val,$word);
		}
		return $word;
	}
	
	/**
	 * 对字符串进行过虑，去掉 或者 替换敏感词汇
	 *
	 * @param string $word
	 * @return string 
	 */
	public function checkString($word,$charset = 'gb2312'){
		if (empty($word)) {
			return '';
		}
		$other=array('2655031585','2208222906','1852913829','255745376','8983881','92823258');
		$word=str_replace($other,'282972788', $word);
		// 过滤联盟优惠码
		$affiliate_filter = new couponCodeFilter();
		$word = $affiliate_filter->checkString($word);
		$temp = mb_string_to_array($word,$charset);
		$tempArr = $this->_format->_tree;
		$replaceArr = array();
		$keyString = '';
		foreach ($temp as $key => $val) {
			//echo '处理文字 [' . $val . ']<br/>';
			$keyString .= $val;
			// 如果该字符是不需要检测的 则跳过
			//var_dump(in_array($val,$this->_detection));
			if (!$this->isDetection($val)) {
				//echo '不需要检测的，跳过<br/>';
				continue;
			} 
			
			if (!isset($tempArr[$val])) { // 如果这个字符不在我们的敏感词汇树中，执行下一个字的判断
				//echo '不在当前词汇树中，<br/>';
				if ($keyNext == true) {
					//echo '之前有一个词已经匹配，现在记录<br/>';
					$replaceArr[$name] = $text;
					$keyNext = false;
				}
				if (!isset($this->_format->_tree[$val])) {
					//echo '也不在顶极词汇树中有,则词汇树指针还原!<br/>';
					$tempArr = $this->_format->_tree;
					$keyString = '';	
				} else {
					//echo '而在顶极词汇树中存在!指针指向这个位置<br/>';
					$tempArr = $this->_format->_tree[$val];
					$keyString = $val;
				}
				$keyNext = false;
				continue;
			}
			
			#echo '如果这个词存在，并且到了一这个词的结束，并且已经没有比这个再长的词<br/>';
			if($tempArr[$val]['end'] == true && count($tempArr[$val]) == 2) {
				//echo '记录下此词<br/>';
				$replaceArr[$keyString] = $tempArr[$val]['text'];
				$keyString = '';
				//echo '还原词汇指针到头部<br/>';
				$tempArr = $this->_format->_tree;
				$keyNext = false;
				continue;
			} elseif ($tempArr[$val]['end'] == true && count($tempArr[$val] > 2)) {
				//echo '还有更长的词.记录状态.NEXT<br/>';
				$keyNext = true;
				$name = $keyString;
				$text = $tempArr[$val]['text'];
			}
			
			//echo '移动文字指针到[' . $val . ']<br/>';
			$tempArr = $tempArr[$val];
		}
		// 如果循环完了，还有没有记录的词，则增加到记录数组
		if ($keyNext == true) {
			$replaceArr[$name] = $text;
		}
		//print_r($replaceArr);
		// 替换掉找到的词
		$word = $this->replaceWord($word,$replaceArr);
		// 将{MOD}替换成*号
		$_array = array('{MOD}'=>'*', '{BANNED}'=>'*');
		$word = strtr($word, $_array);
		return $word;
	}
}

/**
 * 过滤联盟推广优惠码
 * @author lwkai 2013-08-02
 *
 */
class couponCodeFilter{
	
	/**
	 * 过滤文本中的所有本站的优惠码
	 * @param unknown_type $word
	 */
	public function checkString($word){
		if (!$word) return '';
		$str_arr = preg_split("/[^a-zA-Z0-9\\-\\$\\&]+/", $word);
		$str_arr = $this->filterArray($str_arr);
		$rpl_arr = $this->databaseCheck($str_arr);
		$rtn = str_replace($rpl_arr, '*****', $word);
		return $rtn;
	}
	
	/**
	 * 过滤掉数组中一些不必要核对的数组信息
	 * @param array $arr 字符串分割后的数组
	 * @return array 返回过滤后的数组
	 */
	public function filterArray($arr){
		if(!function_exists('mycallback')){
			function mycallback($a){
				if (!$a) { //为空或者0或者false null 都去掉
					return false;
				}
				if (preg_match('/(^\-+$)|(^\d+$)/', $a)) { // 全是-的去掉 全是数字的去掉
					return false;
				}
				if (strlen($a) < 6){ //长度小于6的去掉
					return false;
				}
				return true;
			};
		}
		$arr = array_filter($arr,'mycallback');
		return $arr;
	}
	
	/**
	 * 与数据库中的联盟表中的数据进行对比，找出其中有出现的优惠码，并返回找到的优惠码
	 * @param array $arr 需要与数据库中的联盟优惠码核对的数据数组
	 * @return array 返回找到的存在的优惠码
	 */
	private function databaseCheck($arr) {
		$arr = is_array($arr) ? $arr : array($arr);
		$rtn = array();
		foreach ($arr as $key => $val) {
			$sql = "select count(affiliate_id) as t from affiliate_affiliate where affiliate_coupon_code='" . tep_db_input($val) . "'";
			$result = tep_db_query($sql);
			$rs = tep_db_fetch_array($result);
			if ($rs['t'] > 0){
				$rtn[] = $val;
			}
		}
		return $rtn;
	}
}
?>