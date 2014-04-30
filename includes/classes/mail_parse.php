<?php
/*ini_set('display_errors', '1');
error_reporting(E_ALL & ~E_NOTICE);
require_once 'imap_get_mail.php';*/
class mail_parse{
	
	/**
	 * 需要读取进来的邮件路径
	 * @var string
	 */
	private $email_file = '';
	
	/**
	 * 读取回来的邮件头
	 * @var array
	 */
	private $email_header = array();
	
	/**
 	 * 邮件正文体,包括附件.
 	 * @var array
 	 */
	private $email_text = array();
	
	/**
	 * 邮件附件
	 * @var array
	 */
	private $attachment = array();
	/**
	 * 邮件类型
	 * @var string
	 */
	private $email_type = '';
	
	/**
	 * 临时图片存储位置
	 * @var string
	 */
	private $img_temp_save_path = '/admin/img_tmp/';
	
	/**
	 * 标题编码方式,有些邮件标题没有编码,尝试从邮件正文中找出编码,
	 * 并记录在此,以判断标题是否需要进一步转换
	 * @var string
	 */
	private $subject_charset = ''; 
	
	
	
	/**
	 * 设置需要读取的邮件文件
	 * @param string $file
	 */
	public function set_file($file) {
		if (!empty($file)) {
			$this->email_file = $file;
			$this->read_mail();
		}
	}
	
	/**
	 * 设置临时图片存储路径,必须以/结尾
	 * @param string $path
	 */
	public function set_img_temp_path($path){
		if (!empty($path)) {
			$this->img_temp_save_path = $path . date('Y-m-d') . '/';
		}
	}
	
	/**
	 * 查找特殊邮件中,邮件头与正文分开的地方.
	 * 如果找到,则返回索引,否则返回-1
	 * @param array $arr
	 * @return int
	 */
	private function find_unknown_header_separated($arr){
		$rtn = -1;
		if (is_array($arr)) {
			for($i = 0,$len = count($arr); $i < $len; $i++) {
				if (strpos($arr[$i],':') == false) {
					$rtn = $i;
					break;
				}
			}
		}
		return $rtn;
	}
	
	/**
	 * 设置一封邮件的正文 ,
	 * 在如果用户正好从服务器读回来邮件的时候,通过该方法,就可以直接设置邮件的正文,而不需要再重新从文件读取.
	 * @param array $content 邮件正文.数组中的每个单元都是文件中相应的一行，包括换行符在内
	 */
	public function set_mail_content($content){
		if (is_array($content)) {
			//var_dump($content);
			// 查找只有换行的这一行 以上部分是邮件头,以下部分是正文

			$index = array_search("\r\n",$content);
			if ($index === false) { // 注意要用三个等号，如果万一第一行就找到了返回键名是0 两个等号 则 0 也是FALSE
				$index = array_search("\n",$content);
			}
			if ($index === false) { //如果两种方式都找不到,则这是一封特殊邮件.
				$index = $this->find_unknown_header_separated($content);
			}

			// 一个空行表示头和正文的分隔. array_splice 拆分开
			$head = array_splice($content, 0,$index);
			$this->set_mail_head($head);
			$this->set_mail_text($content);
		} else {
			throw new Exception('邮件正文必须是一个数组,数组中的每个单元都是文件中相应的一行，包括换行符在内.');
		}
	}
	
	/**
	 * 设置邮件头部分
	 * @param array $head
	 * @throws Exception 如果传入的不是数组,则抛出异常
	 */
	public function set_mail_head($head){
		if (is_array($head)){
			$head = $this->format_mail_head($head);
			$this->filter_head($head);
		} else {
			throw new Exception('邮件头必须是一个数组.');
		}
	}
	
	/**
	 * 设置邮件正文内容,包括附件等
	 * @param array $text
	 * @throws Exception
	 */
	public function set_mail_text($text) {
		if (is_array($text)) {
			$len = count($text);
			/* 如果是IMAP收的邮件，则结束符是)，
			所以在此判断一下内容正文最后一行是否是这个符号，
			如果是则把他去掉之  */
			if (trim($text[$len-1]) == ')') { 
				array_splice($text, $len-1,1);
			}
			$this->email_text = $text;
		} else {
			throw new Exception('邮件正文必须是一个数组.');
		}
	}
	
	/**
	 * 获取邮件标题
	 * @return string
	 */
	public function get_subject(){
		return $this->email_header['subject'];
	}
	
	/**
	 * 获取发件人,返回array('发件人邮箱'=>'发件人名称')
	 * @return array
	 */
	public function get_from(){
		return $this->email_header['from_address'];
	}
	
	/**
	 * 获取收件人 返回 array('收件人邮件'=>'收件人名称')
	 * @return array
	 */
	public function get_to(){
		return $this->email_header['to_address'];
	}
	
	/**
	 * 获取邮件抄送人,返回array('收件人邮箱'=>'邮件人名称')
	 * 如果没有抄送人,则返回NULL
	 * @return array|NULL
	 */
	public function get_cc(){
		return $this->email_header['copy_to_address'];
	} 
	
	/**
	 * 获取邮件暗送地址
	 * @return array|NULL
	 */
	public function get_bcc(){
		return $this->email_header['bcc'];
	}
	
	/**
	 * 返回邮箱类型与编码
	 * eg:
	 *    text/html; charset="gbk"
	 */
	public function get_type(){
		return $this->email_header['content-type'];
	}
	
	/**
	 * 获取邮件发送日期
	 * @return string
	 */
	public function get_date(){
		return $this->email_header['date'][0];
	}
	
	/**
	 * 返回当前页面编码 如果是复合模式邮件,可能该方法返回错误的编码
	 * @return string
	 */
	public function get_encode(){
		return $this->email_header['content-transfer-encoding'];
	}
	
	/**
	 * 获取邮箱头中的其他信息.
	 * @param unknown_type $name
	 */
	public function get_other($name){
		return $this->email_header[$name];
	}
	/**
	 * 解析邮件内容
	 * @param string $file 邮件文件路径
	 */
	public function __construct($file = '',$img_tmp_path = ''){
		$this->set_file($file);
		$img_tmp_path = (!empty($img_tmp_path) ? $img_tmp_path : '/var/www/html/888trip.com/wwwroot/admin/email/img_tmp/');
		$this->set_img_temp_path($img_tmp_path);
	}
	
	/**
	 * 读取邮件到变量
	 * @throws Exception 如果文件不存在,则抛出错误
	 */
	private function read_mail(){
		if (file_exists($this->email_file)) {
			$text = file($this->email_file);
			if (is_array($text) && count($text) > 0){
				$this->set_mail_content($text);
			} else {
				throw new Exception('文件[' . $this->email_file . ']内容为空!');
			}
		} else {
			throw new Exception('文件[' . $this->email_file . ']不存在!');
		}
	}
	
	/**
	 * 解析邮件头中的内容，如标题，收件人名称与发件人名称
	 * @param string $str 需要转换的内容
	 * @return string
	 */
	private function head_decode($str){
		if (!empty($str)) {
			// 有可能一行文字过长，有时候会把标题什么的，弄成两条显示，则这里需要把两行拿出来，再组合进行转码
			$str_arr = explode("\n",$str);
			$value = '';
			foreach ($str_arr as $val){
				if (strpos($val,'=?') === false) {
					$value .= $val;
				} else {
					$temp_arr = explode('?',$val);
					$charset = $temp_arr[1];
					$this->subject_charset = $charset; //记录邮件标题的文字编码
					$b = strtolower($temp_arr[2]);
					switch ($b){
						case 'b'://如果不是按国际编码 则不进行BASE64解码
							$value .= trim(iconv($charset,'utf-8//IGNORE',base64_decode($temp_arr[3])));
							break;
						case 'q':
							// quoted_printable_decode 系统自带的解码函数
							$value .= trim(iconv($charset, 'utf-8//IGNORE', quoted_printable_decode($temp_arr[3])));
							break;
						default:
							throw new Exception('邮件标题中有未知的编码方式!');
					}
				}
			}
				
			return $value;
				
		}
		return '';
	}
	
	/**
	 * 对收发件人邮箱与名称格式化
	 * @param unknown_type $str
	 * @return multitype:string unknown
	 */
	private function format_address_name($str){
		$rtn = array('address'=>'','name'=>'');
		if (!empty($str)) {
				
			if (preg_match("/\"?([^\"<]+)\"?\s*\<(.+)\>/", $str,$matches)) {
				$rtn['address'] = $matches[2];
				$rtn['name'] = $this->head_decode($matches[1]);
			} else {
				$rtn['address'] = $str;
			}
		}
		return $rtn;
	}
	
	/**
	 * 过滤信息头信息，找出主要的内容
	 * @param array $head
	 */
	private function filter_head($head) {
		if (is_array($head) == true) {
			foreach($head as $key => $val) {
				switch ($key){
					case 'subject': //邮件标题
						$this->email_header['subject'] = $this->head_decode($val[0]);
						break;
					case 'to': // 收件人
						$to_temp = explode(",",$val[0]);
						foreach($to_temp as $key2 => $val2) {
							$address = $this->format_address_name($val2);
							$this->email_header['to_address'][$address['address']] = $address['name'];
						}
						break;
					case 'from': // 发件人
						$address = $this->format_address_name($val[0]);
						$this->email_header['from_address'][$address['address']] = $address['name'];
						break;
					case 'cc': //抄送人
						$cc_temp = explode(",", $val[0]);
						foreach ($cc_temp as $key2=>$val2){
							$address = $this->format_address_name($val2);
							$this->email_header['copy_to_address'][$address['address']] = $address['name'];
						}
						break;
					case 'date'://邮件发出日期
						$week = strtok($val[0],",");
						$this->email_header['date'][] = date('Y-m-d H:i:s',strtotime(strtok("+-")));
						break;
					case 'received': //邮件发送经过的服务器
						$this->email_header['received'][] = $val;
						break;
					case 'bcc'://暗送(即在邮箱中看不到邮件发给其他什么人,)
						$bcc_temp = explode(",", $val[0]);
						foreach ($bcc_temp as $key2 => $val2) {
							$address = $this->format_address_name($val2);
							$this->email_header['bcc'][$address['address']] = $address['name'];
						}
						break;
					case 'content-type'://邮件类型  如果有 boundary 则此邮件分为几大块.每块可能有不同编码,或者某一块或者几块为附件内容..
						$val[0] = str_replace(array("\r","\n"), "", $val[0]);
						//print_r($val[0]);
						$index = strpos($val[0],';');
						
						$content_type = substr($val[0],0,$index);
						$str = substr($val[0],$index+1);
						$name = $this->find_name(array($str), 'boundary', '=');
												
						if ($name != '') {
							
						//if (preg_match("/([^;]+.*?).*?boundary=\"?(.+)\"?/im", $val[0], $matchs)){
							$this->email_header['content-type'] = $content_type;//$temp[0];//$matchs[1];
							$this->email_header['separator'] = $name; //$value;//$separator[1];//$matchs[2];
						} else {
							$this->email_header['content-type'] = $val[0];
						}
						// 上次遇到这个取回来的文件页面编码后面带了个\r\n  "Content-type: text/html; charset=gb2312\r\n"
						$this->email_header['content-type'] = str_replace(array('\r','\n'), '', $this->email_header['content-type']);
						break;
					default: // 其他不知明信息..
						$this->email_header[$key] = $val;
						break;
				}
			}
		}
	}
	
	/**
	 * 格式化原始信息头
	 * @param array $headers 邮件头数组
	 */
	private function format_mail_head($headers){
		$head = array();
		if (is_array($headers)) {
			foreach($headers as $val) {
				if (preg_match("/^\w+/", $val)) {
					$temp = strtolower(strtok($val,":"));
					$head[$temp][] = substr($val, strlen($temp) + 1);
				} else {
					$head[$temp][count($head[$temp]) - 1] .=  "\n" . $val;
				}
			}
			return $head;
		}
	}
	
	/**
	 * 获取附件列表
	 */
	public function get_attachment(){
		return $this->attachment;
	}
	
	/**
	 * 取得邮件正文
	 * 分html和text
	 * @param string $type 邮件正文部分,默认是html正文,有时候还有纯文本正文
	 */
	public function get_content($type = 'html') {

		if ($type == 'text') {
			$type = 'text/plain';
		}
		if ($type == 'html') {
			$type = 'text/html';
		}
		if (empty($this->email_text['text'][$type])) {
			if (empty($this->email_text['text']['text/plain'])) {
				echo $this->email_text['text'][0];
			} else {
				echo $this->email_text['text']['text/plain'];
			}
		} else {
			echo $this->email_text['text'][$type];
		}
	}
	
	public function test(){
		print_r($this->email_text);
	}
	/**
	 * 对标题,收件人,发件人,没有设定文字编码的邮件,进行二次编码转换
	 * @param array|string $arr 需要转换的对象
	 * @param string $charset 原文字编码
	 */
	private function iconv_subject($arr,$charset){
		if (is_array($arr)) {
			foreach ($arr as $key => &$val) {
				$val = iconv($charset,'utf-8//IGNORE',$val);
			}
		} elseif (is_string($arr)) {
			$arr = iconv($charset,'utf-8//IGNORE',$arr);
		}
		return $arr;
	}
	
	/**
	 * 分解邮件正文
	 * @param array $email_text 邮件正文内容
	 * @param string $separator 如果是复合邮件,则此为分隔隔
	 * @throws Exception 如果获取不到正文编码,则导出异常
	 */
	public function format_mail_text($email_text = '', $separator = ''){
		if (empty($email_text) == true) {
			$email_text = $this->email_text;
			$this->email_text = array();
		}
		if (empty($separator) == true) {
			if (!empty($this->email_header['separator'])) {
				$separator = $this->email_header['separator'];
			}
		}
		//var_dump($separator);
		if (empty($separator) == true) { //邮件中不分多块内容.
			//print_r($this->email_header['content-type']);
			$index = strpos($this->email_header['content-type'],';');
			
			if ($index != false) {
				$charset = $this->find_name(array(substr($this->email_header['content-type'],$index + 1)), 'charset', '=');
				$type = trim(substr($this->email_header['content-type'],0,$index));
			} else {
				$charset = 'gb2312';
				$type = trim($this->email_header['content-type']);
			}
			//判断标题是否有编码,如果没有,则尝试用这里的编码方式进行转码. 不保证百分百对.
			if ($this->subject_charset == '') {
				$this->email_header['subject']         = $this->iconv_subject($this->email_header['subject'], $charset);
				$this->email_header['to_address']      = $this->iconv_subject($this->email_header['to_address'], $charset);
				$this->email_header['from_address']    = $this->iconv_subject($this->email_header['from_address'], $charset);
				$this->email_header['copy_to_address'] = $this->iconv_subject($this->email_header['copy_to_address'], $charset);
				$this->email_header['bcc']             = $this->iconv_subject($this->email_header['bcc'], $charset);
			}

			$encode = '';
			if (!empty($this->email_header['content-transfer-encoding'][0])) {
				$encode = $this->email_header['content-transfer-encoding'][0];
			}
			if (is_array($email_text)){
				//$this->email_header['content-transfer-encoding'][0] 邮件正文加密方式 base64 bit7 等
				
				$text = $this->decodeText(join('',$email_text),$encode);
				
			} elseif( is_string($email_text) && $email_text != '') {
				$text = $this->decodeText($email_text,$encode);
			}
			if ($type == 'text/plain') {
				$text = '<pre>' . $text . '</pre>';
			}

			$this->email_text['text'][$type] = iconv($charset,'utf-8//IGNORE',$text);

		} else {
			
			// 找出开始位置 
			$start = array_search('--' . $separator . "\r\n",$email_text);
			if ($start === false) { // 有可能不是以\r\n结尾,所以有可能返回FALSE
				$start = array_search('--' . $separator . "\n",$email_text);
			}
			// 结束位置
			$end   = array_search('--' . $separator . "--\r\n", $email_text);
			if ($end === false) {
				$end = array_search('--' . $separator . "--\n", $email_text);
			}
			
			if ($end === false) {
				$end = array_search('--' . $separator . '--', $email_text);
			}
			// 完整块
			//取得整个邮件的正文完整块 里面可能还分成几块..
			// $start + 1 是为了去掉第一行的 分隔符
			
			$text = array_splice($email_text, $start + 1,$end - ($start+1)); 
			print_r($text);
			echo "\r\n===================================================================================\r\n";
			do{
				// 查找分界线
				if (is_array($text)) {
					$next = array_search('--' . $separator . "\r\n", $text);
					if ($next === false) { //如果\r\n 找不到 则改用 \n来找
						$next = array_search('--' . $separator . "\n", $text);
					}
					echo '$next=' . $next . "\r\n<hr />";
					if ($next === false){
						$subText = $text;
					} else {
						$subText = array_splice($text, 0,$next);
					}
					print_r($subText);
					echo '<hr />';
					$start = array_search("\r\n", $subText);
					if ($start === false) {
						$start = array_search("\n", $subText);
					}
					$temp = array_splice($subText, 0, $start);

					if (count($temp) > 0){
						//print_r($temp);
						
						$key = $this->find_name($temp,'content-type',':');
						$key = trim(strtolower($key));
						switch($key){
							case 'image/jpeg': //图片
							case 'image/png':
							case 'application/octet-stream': //附件
							case 'application/zip':
							case 'application/msword':
							case 'application/pdf':
							case 'application/vnd.ms-excel':
							case 'image/gif':
								$this->parse_img($temp,$subText,$key);
								break;
							case 'text/plain':
							case 'text/html':
							case 'multipart/alternative':
							case 'multipart/mixed':
								//$temp = join("<::>",$temp);
								$this->parse_text($temp,$subText,$start,$key);
								break;
							default:
								throw new Exception('未知的类型块.type=' . $key);
						}
					}
				} else {
					$temp = $text;
					$this->parse_text($temp,'',0);
					$next = false;
				}
			} while ($next);
			
			
		}
		$this->save_tep_image();
		$this->replace_img();
	}
	
	/**
	 * 从信息头中查找文件的某一项
	 * 找到返回对应的名称,否则返回空
	 * eg: 
	 * find_name(array(
	 * 				'Content-Type: image/jpeg;',
	 * 				'	name="ADCD9B9B@D0E18A01.F9632350.jpg"',
	 * 				'Content-Transfer-Encoding: base64',
	 * 				'Content-ID: <ADCD9B9B@D0E18A01.F9632350.jpg>'
	 * 			),'name');
	 * 返回 ADCD9B9B@D0E18A01.F9632350.jpg
	 * @param array $arr 信息数组
	 * @param string $name 需要查找的名称
	 * @param string $separator 名称与值之间的分隔符
	 * @return string
	 */
	private function find_name($arr,$name,$separator) {
		$rtn = '';
		if (is_array($arr)) {
			foreach($arr as $key => $val) {
				$temp_arr = explode(';', $val);
				foreach ($temp_arr as $key2 => $val2) {
					$index = strpos($val2, $separator);
					if ($index != false) {
						$temp = substr($val2,0,$index);
						$temp_val = substr($val2,$index+1);
						if (strtolower(trim($temp)) == strtolower(trim($name))) {
							$rtn = trim($temp_val);
							$rtn = trim($rtn,'"');
							$rtn = trim($rtn,'<');
							$rtn = trim($rtn,'>');
							break;
						}
					}
				}
			}
		}
		return $rtn;
	}
	
	/**
	 * 分析邮件中的附件
	 * @param array $temp 附件头信息
	 * @param array|string $subText 文件内容
	 * @param string $key 附件的类型
	 */
	private function parse_img($temp,$subText,$key){
		$name = $this->find_name($temp, 'name', '=');
		$encode = $this->find_name($temp, 'Content-Transfer-Encoding', ':');
		$jpeg_id = $this->find_name($temp, 'content-id', ':');
		$charset = $this->find_name($temp, 'charset', '=');
		if (!empty($charset)) { //如果有设置文字编码
			$name = $this->head_decode($name);
		} else { //否则用标题的编码方式
			$name = $this->iconv_subject($name, $this->subject_charset);
		}
		$len = count($this->email_text['attachment']);
		if (count($subText) > 0) {
			$this->email_text['attachment'][$len]['content'] = join('',str_replace("\r\n","",$subText));//$this->decodeText(join('',$subText),$encode);
			$this->email_text['attachment'][$len]['encode'] = $encode;
			$this->email_text['attachment'][$len]['name'] = $name;
			$this->email_text['attachment'][$len]['content-type'] = $key;
			$this->email_text['attachment'][$len]['id'] = $jpeg_id;
		}
	}
	
	/**
	 * 分析邮件正文块
	 * @param unknown_type $temp    块信息头
	 * @param unknown_type $subText 块内容
	 * @param unknown_type $start   块开始部分
	 * @param unknown_type $key     块的类型
	 * @throws Exception
	 */
	private function parse_text($temp,$subText,$start,$key){
		//$temp = preg_replace("/\r\n/","",$temp);
		// 是否有分隔符  $temp_sub 返回分隔符
		$temp_sub = $this->find_name($temp, 'boundary', '=');
		if (!empty($temp_sub)){
			$this->format_mail_text(array_splice($subText, $start), $temp_sub);
		} else {
			//取得内容是GB2312还是UTF8或者其他什么编码
			$charset = $this->find_name($temp, 'charset', '=');
			if ($charset == '') { //如果没有编码,则默认2312 有部分邮件没有编码的.
				if ($this->subject_charset != '') {
					$charset = $this->subject_charset;
				} else {
					$charset = 'gb2312';
				}
			}
			//取得加密方式
			$encode = $this->find_name($temp, 'content-transfer-encoding', ':');
			if ($encode == '') {
				$encode = $this->find_name($temp, 'charset', '=');
			}
			if (empty($charset) || empty($encode)) {
				throw new Exception('邮件未知正文编码!');
			}
			if (count($subText) > 0){
				//print_r($subText);
				$text = $this->decodeText(join('',$subText),$encode);
				if ($key == 'text/plain') { //如果是纯文本的,则加个保持格式不变化的标签
					$text = '<pre>' . $text . '</pre>';
				}
				$this->email_text['text'][$key] = iconv($charset,'utf-8//IGNORE',$text);
			}
		}	
	}
	
	/**
	 * 替换掉内容中需要显示的图片路径.
	 */
	private function replace_img(){
		if (isset($this->email_text['attachment']) && is_array($this->email_text['attachment'])) {
			foreach($this->email_text['attachment'] as $val) {
				if (!empty($val['id'])) {
					if (is_array($this->email_text['text'])) {
						foreach($this->email_text['text'] as $key2 => &$val2) {
							$val2 = preg_replace("/cid:" . preg_quote(trim($val['id'])) . "/im",$val['save_path'],$val2);
						}
					}
				}
			}
		}
		//print_r($this->email_text['text']);
	}
	
	/**
	 * 对邮件正文解码
	 * @param string $str 需要解码的内容
	 * @param string $encode 内容编码方式 如 base64 
	 * @return string
	 */
	private function decodeText($str,$encode){
		$encode = strtolower($encode);
		switch(trim($encode)){
			case 'base64':
				$rtn = base64_decode($str);
				break;
			case 'quoted-printable':
				$rtn = quoted_printable_decode($str);
				// 系统的解码函数 
				// 系统的编码函数 quoted_printable_encode
				break;
			default:
				$rtn = $str;
				break;
		}
		return $rtn;
	}
	
	/**
	 * 把附件或者图片存到临时文件
	 * 中文附件,会编码一次,因为中文名在有些服务器上支持不好,导致下载文件失败,针对 LINUX
	 */
	private function save_tep_image() {

		$this->check_folder();
		$this->get_folder();
		if (isset($this->email_text['attachment']) && is_array($this->email_text['attachment'])) {
			foreach ($this->email_text['attachment'] as $key => &$val) {
				//有些附件名称用了加密方式,需要先解密
				$val['name'] = $this->head_decode($val['name']);
				$index = strrpos($val['name'],'.');
				$tep_name = substr($val['name'],0,$index);
				$tep_ext = substr($val['name'],$index);
				$new_name = md5($tep_name) . $tep_ext;
				file_put_contents($this->img_temp_save_path . $new_name, $this->decodeText($val['content'],$val['encode']));
				$val['save_path'] = 'http://cn.test.com/admin/email/img_tmp/'. date('Y-m-d') . '/' . $new_name;
				$this->attachment[$val['name']] = $val['save_path'];
			}
		}
	}
	
	/**
	 * 检测邮件临时图片文件夹是否存在
	 */
	private function check_folder(){
		$base_path = '/var/www/html/888trip.com/wwwroot/admin/';
		$path_arr = str_replace($base_path,'',$this->img_temp_save_path);
		$path_arr = explode('/', $path_arr);
		foreach ($path_arr as $key => $val) {
			if (!is_dir($base_path.$val)) {
				if (!@mkdir($base_path.$val,0777)) {
					throw new Exception('Create folder failed, please check if there is a permissions to create folders!');
				}
			}
			$base_path .= $val . '/';
			
		}

	}
	
	/**
	 * 检测路径下有没有过期的文件夹,如果有则删除,避免产生一堆垃圾文件
	 */
	private function get_folder(){
		$base_path = substr($this->img_temp_save_path,0,strrpos(substr($this->img_temp_save_path,0,-1),'/'));
		if (!is_dir($base_path)) {
			return;
		}
		$handle = opendir($base_path);
		while (false !== ($file = readdir($handle))) {
			if ($file != '.' && $file != '..' && $file != (date('Y-m-d'))) {
				if (is_dir($base_path . '/' . $file)) {
					$this->deldir($base_path . '/' . $file);
				}
			}
		}
	}
	
	/**
	 * 删除文件夹
	 * @param string $dir 要删除的文件夹
	 */
	private function deldir($dir) {
		//先删除目录下的文件：
		$dh=opendir($dir);
		while ($file=readdir($dh)) {
			if($file!="." && $file!="..") {
				$fullpath=$dir."/".$file;
				if(!is_dir($fullpath)) {
					if (!@unlink($fullpath)) {
						throw new Exception('删除文件' . $fullpath . '失败');	
					}
				} else {
					$this->deldir($fullpath);
				}
			}
		}
		closedir($dh);
		//删除当前文件夹：
		if(rmdir($dir)) {
			return true;
		} else {
			return false;
		}
	}
}


/*$a = new imap_get_mail('imap.qq.com','2683692314','123456789');
$a->set_debug(true);
$a->connection();
$total = $a->get_mail_totals();
$mail = $a->get_mail($total);
var_dump($mail);
*/



/*
	$email = array(
		'ZC0409-lY_VZoPqiXxf~mU1mieqT28.eml',
		'ZC3709-i5GQq04qhXBT~Gc3TECfA28.eml',
		'ZC1709-tK47jWhAYZS3lQpaRm9ZD28.eml',
		'ZC0409-yNJpWL3AqF1~73Ag_1HOD28.eml',
		'ZC0815-uaO39RBBtEFquSZ2aCbge28.eml',
		'ZC2016-hZ8sM9anxjMfkQ5eV4OYL28.eml'
	);
	if (is_numeric($_GET['i'])) {
		$index = (isset($_GET['i']) && !empty($_GET['i'])) ? (int)$_GET['i'] : 0;
		$email_name = $email[$index];
	} else if (isset($_GET['i'])) {
		header('Content-Type:text/html;charset=utf-8');
		$email_name = $_GET['i'];
	}
	if (isset($email_name)) {
		try{
			ini_set('display_errors', '1');
			error_reporting(E_ALL);
			$a = new mail_parse('/var/www/html/888trip.com/wwwroot/admin/email/'.$email_name);
			//ZC1709-tK47jWhAYZS3lQpaRm9ZD28.eml ZC0409-yNJpWL3AqF1~73Ag_1HOD28.eml ZC0815-uaO39RBBtEFquSZ2aCbge28.eml ZC2016-hZ8sM9anxjMfkQ5eV4OYL28.eml
			/ *echo iconv('gb2312','utf-8//IGNORE','邮件标题:') . $a->get_subject() . '<br/>';
			echo iconv('gb2312','utf-8//IGNORE','邮件发件人:<br/>');
			print_r($a->get_from());
			echo iconv('gb2312','utf-8//IGNORE','<br/>收件人:<br/>');
			print_r($a->get_to());
			echo iconv('gb2312','utf-8//IGNORE','<br/>抄送给:<br/>');
			var_dump($a->get_cc());
			echo iconv('gb2312','utf-8//IGNORE','<br/>邮件类型:<br/>'); 
			print_r($a->get_type());
			echo iconv('gb2312','utf-8//IGNORE','<br/>邮件发送日期:<br/>');
			print_r($a->get_date());
			echo iconv('gb2312','utf-8//IGNORE','<br/>邮件编码方式:<br/>');
			print_r($a->get_encode());
			echo iconv('gb2312','utf-8//IGNORE','<br/>邮件正文<br/>');* /
			//echo $a->get_other('separator');
			
			$a->format_mail_text();
			$from = $a->get_from();
			foreach ((array)$from as $key => $val) {
				echo 'form:' . $val . '&lt;' . $key . '&gt;<br/>';
			}
			echo 'Date:' . $a->get_date() . '<br/>';
			$to = $a->get_to();
			
			foreach ((array)$to as $key => $val) {
				echo 'to:' . $val . '&lt;' . $key . '&gt;<br/>';
			}
			echo 'subject:' . $a->get_subject() . '<br/>';
			$a->get_content();
			echo '<br/>attachment:<br/>';
			print_r($a->get_attachment());
			//echo '邮件的标题是:' . $a->get_subject() . '<br/>';
			//$a->test();
		}catch(Exception $e){
			header('Content-Type:text/html;charset=gb2312');
			var_dump($e);
			print_r($e);
		}
	}
	*/
?>