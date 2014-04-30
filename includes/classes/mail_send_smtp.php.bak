<?php 
/**
 * SMTP 发送邮件类 服务器必须有smtp支持
 * 或者生成WORD文档也可用。
 * @version 1.0
 * @author lwkai 2012-07-23 e-mail:1275124829@163.com
 * @package
 * @example
 * $mail = new mail_send_smtp();
 * $mail->set_from_address('27310221@usitrip.com');
 * $mail->set_from_name('走四方');
 * $mail->set_subject('邮件标题－－测试邮件');
 * $mail->set_charset('gb2312');
 * $mail->set_to_name('李文凯');
 * $mail->set_to_address('2683692314@qq.com');
 * $mail->set_copy_to(array('1773247305@qq.com'=>'周豪华','643614934@qq.com'=>'何鹏飞'));//抄送
 * $mail->set_mail_type('html');
 * $mail->set_body('<html><head><title>测试邮件</title></head><body><p style="font-size:30px;color:#FF0000;">这是一封测试邮件</p></body></html>');//设置邮件正文
 * $ds = dirname(__FILE__) . DIRECTORY_SEPARATOR;
 * $mail->set_file(array($ds . 'http_imgload.jpg', $ds . 'keep_02.png'));//设置邮件附件
 * $temp = $mail->combination_of_email(true); //组合邮件，如果是用send_mail来发，此方法可以不用手动调用，直接send_mail
 * $temp = $mail->get_word_content();//把邮件组合之后，获取出来，可以把返回的内容保存为 DOC 文档，供用户下载，或者做为附件。注：只能用 OFFICE 打开。WPS打开会报错
 * $mail->send_mail();//发送邮件
 *
 */
class mail_send_smtp{
	
	/**
	 * 邮件类型 纯文本邮件或者HTML邮件
	 * @var string $mail_type text或者html
	 */
	private $mail_type = 'text/plain';
	
	/**
	 * 邮件正文内容
	 * array(array('charset'=>'文字编码','body'=>'文字内容')[,array('charset'=>'文字编码','body'=>'文字内容')[,...]])
	 * @var array $mail_body 邮件正文
	 */
	private $mail_body = array();
	
	/**
	 * 处理之后，等待发送的邮件正文，也可以说是处理过后的正文内容。
	 * 可用于邮件发送，也可用于保存为DOC的一部分。
	 * @var string
	 */
	protected  $send_body = '';
	
	/**
	 * 邮件附件内容
	 * @var array 
	 */
	private $mail_files = array();
	
	/**
	 * 接收者的名称
	 * @var string 
	 */
	private $mail_to_name = '';
	
	/**
	 * 接收者的邮箱地址
	 * @var string
	 */
	private $mail_to_address = '';
	
	/**
	 * 邮件抄送的目标地址
	 * @var string | array
	 */
	private $mail_copy_to = '';
	
	/**
	 * 邮件抄送的目标地址
	 */
	private $mail_bcc_to = '';
	
	/**
	 * 邮件发送方名称
	 * @var string 
	 */
	private $mail_from_name = '';
	
	/**
	 * 邮件发送方的邮箱地址
	 * @var string
	 */
	private $mail_from_address = '';
	
	/**
	 * 邮件退信地址
	 *
	 * @var string
	 */
	private $mail_return_address = '';
	
	/**
	 * 邮件信息头
	 *
	 * @var array
	 */
	protected $mail_header = array();
	
	/**
	 * 邮件标题
	 * array('charset'=>'标题的文字编码','subject'=>'标题的文字内容');
	 * @var array
	 */
	protected $mail_subject = array();
	
	/**
	 * 邮件正文编码
	 */
	protected $mail_charset = 'gb2312';
	
	/**
	 * 邮件发送出去的编码 试过多种 发现在简繁下 gbk 最好
	 * @var string
	 */
	protected $mail_to_charset = 'gbk';
	
	/**
	 * 邮件正文以什么加密方式进行发送
	 * @var string
	 */
	protected $mail_body_encode = 'base64';
	
	/**
	 * 如果需要把HTML中的图片内容附加在邮件中，此属性为TRUE
	 * @var boolean
	 */	
	private $img_to_mail = false;
	
	private $sub_boundary = '';
	
	private $boundary = '';
	/**
	 * 如果邮件正文是HTML的，而且图片需要附加在邮件中一并发送，则这里设置了可以
	 * @var unknown_type
	 */
	private $image_types = array(
		'gif'  => 'image/gif',
		'jpg'  => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'jpe'  => 'image/jpeg',
		'bmp'  => 'image/bmp',
		'png'  => 'image/png',
		'tif'  => 'image/tiff',
		'tiff' => 'image/tiff',
		'swf'  => 'application/x-shockwave-flash'
	);
	
	/**
	 * 如果需要把HTML中的图片附加在邮件中一并发送，
	 * 则需要设置图片对应HTML代码中的前缀，以保证图片路径正确
	 * @var string
	 */
	private $images_dir = '';
	
	/**
	 * SMTP 发送邮件初始化
	 *
	 * @param string $to_address	接收人的邮箱地址
	 * @param string $to_name		接收人的名称
	 * @param string $from_address	发送人的邮箱地址
	 * @param string $from_name		发送人的名称
	 * @param string $subject		发送的邮件标题
	 * @param string $body			发送的邮件正文
	 * @param string $charset		邮件文字的编码
	 * @param string $emailType		发送的邮件正文类型[html/text]
	 */
	public function __construct($to_address = '', $to_name = '', $from_address = '', $from_name = '', $subject = '', $body = '', $charset='gb2312', $emailType = 'text'){
		$this->set_to_address($to_address);
		$this->set_to_name($to_name);
		$this->set_from_address($from_address);
		$this->set_from_name($from_name);
		$this->set_subject($subject);
		$this->set_body($body);
		$this->set_charset($charset);
		$this->set_mail_type($emailType);
		$this->mail_header[] = "MIME-Version:1.0";
	}
	
	/**
	 * 设置邮件正文类型
	 *
	 * @param string $type html/text
	 */
	public function set_mail_type($type){
		$type = strtolower($type);
		switch ($type) {
			case 'html':
				$this->mail_type = 'text/html';
				break;
			default:
				$this->mail_type = 'text/plain';
				break;
		}
	}
	
	/**
	 * 设置邮件发送出去的编码，默认是GBK
	 */
	public function set_out_email_charset($charset){
		if (!empty($charset)) {
			$this->mail_to_charset = $charset;
		}
	}
	
	/**
	 * 设置邮件文字编码
	 *
	 * @param string $charset
	 */
	public function set_charset($charset){
		$this->mail_charset = $charset;
	}
	
	/**
	 * 设置收件人的邮箱地址
	 *
	 * @param string $address
	 */
	public function set_to_address($address){
		if (empty($address) == false) {
			$this->mail_to_address = $address;
		}
	}
	
	/**
	 * 设置收件人姓名
	 *
	 * @param string $name
	 */
	public function set_to_name($name){
		if (empty($name) == false) {
			$this->mail_to_name = $name;
		}
	}
	
	/**
	 * 设置邮件抄送人
	 * @param array|string $copyto 抄送给某某某
	 * @example 
	 * 		set_copy_to(array('123456@qq.com' => '李先生','55555@163.com' => '陈先生'));
	 * @example 
	 *      set_copy_to('123456@qq.com');
	 */
	public function set_copy_to($copyto){
		$this->mail_copy_to = $copyto;
	}
	
	/**
	 * 设置邮件暗送人 此邮件不会显示正常收件人的邮箱中
	 * @param array|string $bcc_to 暗送给某某某  
	 * @example
	 * 		set_bcc_to(array('123456@qq.com' => '李先生','55555@163.com' => '陈先生'));
	 * @example
	 *      set_bcc_to('123456@qq.com');
	 */	
	public function set_bcc_to($bcc_to) {
		if (is_array($bcc_to)) {
			foreach($bcc_to as $mail => $name) {
				if (is_numeric($mail)) {
					$this->mail_bcc_to[$name] = $mail;
				} else {
					$this->mail_bcc_to[$mail] = $name;
				}
			}
		} else {
			$this->mail_bcc_to[$bcc_to] = '';
		}
	}
	
	/**
	 * 设置邮件正文的编码方式
	 * base64 | quoted-printable ｜ 7bit
	 * @param string $encode
	 */
	public function set_body_encode($encode){
		if (!empty($encode)) {
			$this->mail_body_encode = $encode;
		}
	}
	
	/**
	 * 设置发件人邮箱
	 * 
	 * @param string $address 发件人邮箱地址
	 */
	public function set_from_address($address) {
		if (empty($address) == false) {
			$this->mail_from_address = $address;
		}
	}
	
	/**
	 * 设置发件人姓名
	 *
	 * @param string $name 发件人姓名
	 */
	public function set_from_name($name){
		if (empty($name) == false) {
			$this->mail_from_name = $name;
		}
	}
	
	/**
	 * 设置退信地址
	 *
	 * @param string $address
	 */
	public function set_return_address($address){
		if (empty($address) == false) {
			$this->mail_return_address = $address;
		}
	}
	
	/**
	 * 设置邮件标题
	 *
	 * @param string $subject
	 * @param string  $charset
	 */
	public function set_subject($subject,$charset='') {
		if (empty($subject) == false) {
			if(IS_LIVE_SITES!=true){
				$subject = "邮件测试 - " . $subject;
			}
			$this->mail_subject = array('subject' => $subject,'charset' => $charset);
		}
	}
	
	/**
	 * 添加邮件正文内容，可调用多次累加正文内容
	 * 
	 *
	 * @param string $body 邮件正文
	 * @param string $charset 邮件正文的文字编码,如果不设置，则用之前设置的编码，如果没设置，则默认是gb2312
	 */
	public function set_body($body, $charset = ''){
		if (empty($body) == false) {
			$this->mail_body[] = array('charset' => $charset, 'body' => $body);
		}
	}
	
	/**
	 * 设置邮件信头
	 *
	 * @param string|array $header
	 */
	public function set_header($header){
		if (is_array($header) == true) {
			foreach ($header as $val) {
				$this->mail_header[] = $val;
			}
		} else {
			$this->mail_header[] = $header;
		}
	}
	
	/**
	 * 设置HTML中的图片所在位置，结合HTML中的引用路径，
	 * 组成完整的图片所有地址
	 * @param string $dir
	 */
	public function set_image_dir($dir){
		if (!empty($dir)) {
			$this->images_dir = $dir;
		}
	}
	
	public function get_headers(){
		return $this->mail_header;
	}
	
	/**
	 * 根据扩展名，返回对应的文件类型
	 *
	 * @param string $type 文件扩展名
	 * @return string
	 */
	private function get_type($type){
		$rtn = '';
		if (isset($this->image_types[$type]) && $this->image_types[$type] != '') {
			$rtn = $this->image_types[$type];
		} else {
			$rtn = 'application/unknown';
		}
		return $rtn;
	}
	
	/**
	 * 读取文件内容
	 * @param string $file 需要读取的文件地址
	 * @param string $content_id_name 如果是正文中要显示的图片，并且这个图片的内容要附加在邮件中，则把这个文件名传过来
	 */
	private function read_file($file,$content_id_name = '') {
		if (file_exists($file) == true) {
			$file_content = file_get_contents($file);
			//$name = substr($file,strrpos($file,DIRECTORY_SEPARATOR) + 1);
			$name = basename($file);
			$ext = substr($name,strrpos($name,'.') + 1);
			$type = $this->get_type($ext);
			$this->set_file_content($file_content, $name, $type, $content_id_name);
		}
	}
	
	/**
	 * 直接设置附件，以直接传附件内容的方式，不是从文件
	 * @param string $file_content 附件文件内容
	 * @param string $name 附件文件名
	 * @param string $type 附件类型 
	 * @param string $content_id_name 如果是附加在邮件正文中的图片或者SWF，则需要指定该ID名
	 */
	public function set_file_content($file_content,$name,$type,$content_id_name = ''){
		if ($file_content != '' && $name != '' && $type != '') {
			if ($file_content) {
				//$len = count($this->mail_files);
				$this->mail_files[$name] = array(
						'content' => $file_content,
						'name'    => $name,
						'type'    => $type
				);
				if (!empty($content_id_name)) {
					$this->mail_files[$name]['Content-ID'] = $content_id_name;
				}
			}
		}
	}
	
	/**
	 * 把一个文件读取内容并存在附件数组中，以待发送
	 * eg: $file = "/var/www/html/aaa/ttt.jpg"
	 *     $file = array('/var/www/html/aaa/aa.jpg','/var/www/html/aaa/bb.jpg')
	 *     windows 
	 *     $file = "d:\aaa\a.jpg"
	 *     $file = array('d:\aaa\a.jpg','d:\aaa\b.jpg')
	 * @param string | array $file 附件完整地址
	 */
	public function set_file($file){
		if (is_array($file) == true) {
			foreach ($file as $val) {
				$this->read_file($val);
			}
		} else {
			$this->read_file($file);
		}
	}
	
	/**
	 * 对收件人姓名进行base64_encode编码还有邮件主题
	 * @param string $str 要编码的文字
	 * @param string $charset 文字编码。如果不设置，则默认用设置的邮件正文编码，如果都没设置，默认是gb2312
	 * @return string
	 */
	protected function base_encode($str, $charset = ""){
		if (empty($charset) == true) {
			$charset = $this->mail_charset;
		}
		if (!empty($str)) {
			return '=?' . $this->mail_to_charset . '?B?' . base64_encode(iconv($charset,$this->mail_to_charset . '//IGNORE',$str . " ")) . '?=';
		}
	}
	
	/**
	 * 判断是否是HTML内容，如果有html,table,div标记，则是HTML内容
	 * @param string $html 正文内容
	 * @return string
	 */
	private function find_images($html){
		if(!preg_match('/\<html/i',$html) && !preg_match('/\<table/i',$html) && !preg_match('/\<div/i',$html)) {
			return $html;
		} else {
			$html = $this->search_html_images($html);
			return $html;
		}
	}
	
	/**
	 * 从本类设定的图片类型中，把传进来的内容中所有的符合图片类型的图片找出来。
	 * 并加载到附件数组中，以供发送到外面的内容，图片能正常显示。返回处理后的内容
	 * @param string $html 正文内容
	 * @return string
	 * @throws Exception 如果在调用此方法之前，还未设置images_dir这个图片路径，则抛出错误
	 */
	private function search_html_images($html){
		if (is_array($this->image_types)) {
			foreach($this->image_types as $key => $val) {
				$extensions[] = $key;
			}
		}
		$images_dir = $this->images_dir;
		if ($this->images_dir == '') {
			throw new Exception('图片路径前缀未设置，请调用set_images_dir()方法来设置！');
		}
		preg_match_all('/"([^"]+\.(' . implode('|', $extensions).'))"/Ui', $html, $images);
		
		for ($i=0; $i<count($images[1]); $i++) {
			if (file_exists($images_dir . $images[1][$i])) {
				$name = basename($images[1][$i]);
				$index = strrpos($name,'.');
				$t_name = substr($name,0,$index) . microtime(true) . '.' . substr($name,$index+1);
				
				$this->read_file($images_dir . $images[1][$i], $t_name);
				$html = str_replace($images[1][$i], 'cid:' . $t_name, $html);
			}
		}
		return $html;
	}
	
	/**
	 * 如果需要生成为WORD，则调用此方法，返回的内容即可保存为DOC文件
	 */
	public function get_word_content(){
		$sub_word = clone $this;
		$arr = $sub_word->get_mail_header();
		if ($sub_word->send_body == '') {
			$sub_word->combination_of_email(true);
		}
		$body = $sub_word->send_body;
		$rtn = $arr['headers'];
		$rtn .= $body;
		return $rtn;
	}
	
	/**
	 * 组合邮件HEADER头部分。返回的数组方便mail函数发邮件用
	 * @return array('headers'=>'..','to_address'=>'...','subject'=>'...')
	 */
	private function get_mail_header(){
		$headers = join("\n", $this->mail_header);
		
		if ($this->mail_to_name != '') {
			$to_address = $this->base_encode($this->mail_to_name) . "<" . $this->mail_to_address . ">";
		} else {
			$to_address = $this->mail_to_address;
		}
		
		$subject = $this->base_encode($this->mail_subject['subject'],$this->mail_subject['charset']);
		return array(
			'headers'     => $headers,
			'to_address'  => $to_address,
			'subject'     => $subject,
			'bcc_address' => $this->mail_header['bcc']
		);
	}
	

	/**
	 * 组合邮件
	 * @param boolean $images 是否把HTML中的图片附加在邮件中发送
	 * @throws Exception 如果在此之前没设置图片的所有位置，[对应邮件正文中的路径]则会抛出错误！
	 */
	public function combination_of_email($images = false){
		$this->img_to_mail = $images;
		
		if (isset($this->mail_header['From']) == false) {
			$boundary = uniqid("");
			$this->boundary = $boundary;
			if ($this->mail_to_name != '') {
				$this->mail_header['From'] = "From: " . $this->base_encode($this->mail_from_name) . "<" . $this->mail_from_address . ">";
			} else {
				$this->mail_header['From'] = "From: " . $this->base_encode($this->mail_from_address)  . "<" . $this->mail_from_address . ">";
			}
		}
		
		if (!isset($boundary)) $boundary = $this->boundary;
		
		if ($this->mail_return_address != '' ) {//&& isset($this->mail_header['Return-Path']) != true
			$this->mail_header['Return-Path'] = "Return-Path: <" . $this->mail_return_address . ">";
		}
		
		if (isset($this->mail_header['cc']) == false) {
			if (is_array($this->mail_copy_to) == true) {
				foreach ($this->mail_copy_to as $key => $val) {
					if (!empty($val)) {
						$Cc[] = $this->base_encode($val) . "<" . $key . ">";
					} else {
						$Cc[] = $key;
					}
				}
				$this->mail_header['cc'] = "Cc: " . join(",",$Cc);
			} elseif (!empty($this->mail_copy_to)) {
				$this->mail_header['cc'] = "Cc: " . $this->mail_copy_to;
			}
		}
		
		if (isset($this->mail_header['bcc']) == false) {
			if (is_array($this->mail_bcc_to) == true) {
				foreach ($this->mail_bcc_to as $key => $val) {
					if (!empty($val)) {
						$Bcc[] = $this->base_encode($val) . "<" . $key . ">";
					} else {
						$Bcc[] = $key;
					}
				}
				$this->mail_header['bcc'] = "Bcc: " . join(",",$Bcc);
			} elseif (!empty($this->mail_bcc_to)) {
				$this->mail_header['bcc'] = "Bcc: " . $this->mail_bcc_to;
			}
		}
		
		
		if ($images == true) {
			if (isset($this->mail_header['boundary']) == false) { //如果已经设置了这个头信息，就不再设置
				$this->mail_header['boundary'] = "Content-type: multipart/mixed;boundary=\"$boundary\"\n";
				//$this->mail_header[] = "\nThis is a multi-part message in MIME format.\n";
				$this->mail_header[] = "--$boundary";
			}
		} else {
			if (isset($this->mail_header['boundary']) == false) { //如果已经设置了这个头信息，就不再设置
				$this->mail_header['boundary'] = "Content-type: multipart/mixed; boundary=\"$boundary\"";
			}
			$body = "--$boundary\n";
		}

		
		$body_temp = '';
	
		foreach ($this->mail_body as $key => $val){
			if (!empty($val['charset'])) {
				$body_temp .= iconv($val['charset'], $this->mail_to_charset . '//IGNORE', $val['body'] . " ") . "\n";
			} else {
				$body_temp .= iconv($this->mail_charset, $this->mail_to_charset . '//IGNORE', $val['body'] . " ") . "\n";
			}
		}
		
		
		if ($images == true) {
			
			if (isset($this->mail_header['subboundary']) == false) {
				$subboundary = uniqid("");
				$this->sub_boundary = $subboundary;
				$this->mail_header['subboundary'] = 'Content-Type: multipart/alternative;boundary="' . $subboundary . '"' . "\n\n";
			}
			if (!isset($subboundary)) $subboundary = $this->sub_boundary;
			
			$body .= '--' . $subboundary . "\n";
			
			$body .= 'Content-Type: ' . $this->mail_type . ';charset="' . $this->mail_to_charset . '"' . "\n";
			$body .= 'Content-Transfer-Encoding: ' . $this->mail_body_encode . "\n\n";
			
			
			$body_temp = $this->find_images($body_temp);
		} else {
			$body .= "Content-Type: $this->mail_type;charset=\"" . $this->mail_to_charset . "\"\n";
			$body .= "Content-transfer-encoding: " . $this->mail_body_encode . "\n\n";//8bit
		}

		
		if ($this->mail_type == 'text/html') {
			$temp = '<meta http-equiv="Content-Type" content="text/html; charset=' . $this->mail_to_charset . '" />';
			$body_temp = nl2br($temp . $body_temp);
		}
		switch ($this->mail_body_encode) {
			case 'base64':
				$body .= chunk_split(base64_encode(rtrim($body_temp)));
				break;
			case 'quoted-printable':
				$body .= $this->quoted_printable_encode($body_temp);
				break;
			default:
				$body .= $body_temp;
				break;
		}
		
		if ($images == true) {
			$body .= "\n--" . $subboundary . "--\n";
		}

		if (count($this->mail_files) > 0) {
			foreach ($this->mail_files as $val) {
				$body .= "\n--$boundary\n";
				$body .= "Content-type: " . $val['type'] . ";name=" . $val['name'] . "\n";
				if (isset($val['Content-ID']) && $val['Content-ID'] != '') {
					$body .= "Content-ID: <" . $val['Content-ID'] . ">\n";
				} else {
					$body .= "Content-disposition:attachment;filename=" . $val['name'] . "\n";
				}
				$body .= "Content-transfer-encoding: base64\n\n";
				$body .= chunk_split(base64_encode($val['content'])) . "\n";
			}
		}
		
		$body .= "--$boundary--\n";
		
		$this->send_body = $body;
		return $body;
	}
	
	/**
	 * 发送邮件
	 * 返回成功或者失败 即 true false
	 * @param mail_send_agent_smtp $send_mail_obj
	 * @param boolean $repeat 是否需要重新组合邮件内容
	 * @return boolean
	 */
	public function send_mail($send_mail_obj = null, $repeat = false){
		if ($this->send_body == '' || $repeat == true) {
			$this->combination_of_email($this->img_to_mail);
		}
		$body = $this->send_body;
		$arr = $this->get_mail_header();

		if (SEND_EMAILS != 'true') {
			echo '发送邮件功能关闭！';
			return false; //如果系统未打开发邮件功能，则不发送
		}
		// 添加后台发送时调用 后台默认的发邮件函数
		if (is_object($send_mail_obj)) {
			$send_mail_obj->connection();
			$rtn = $send_mail_obj->sendMail($arr['to_address'], $arr['subject'], $body, $arr['headers']);
		} else {
			$rtn = mail($arr['to_address'],$arr['subject'],$body,$arr['headers'],$arr['bcc_address']);
		}
		if ($rtn == true) {
			$rtn = true;
		} else {
			$rtn = false;
		}
		return $rtn;
	}
	
	/**
	 * 可打印字符引用编码
	 * 
	 * @param string $input 需要编码的内容
	 * @param int $line_max 行宽，最好是76 默认也是这个数，有些软件限制了宽度
	 * @return string
	 */
	private function quoted_printable_encode($input , $line_max = 76) {
		$lines = preg_split("/\r\n|\r|\n/", $input);
		$eol = "\n";
		$escape = '=';
		$output = '';
		if (is_array($lines)) {
			foreach ($lines as $key => $line) {
				$linlen = strlen($line);
				$newline = '';
		
				for ($i = 0; $i < $linlen; $i++) {
					$char = substr($line, $i, 1);
					$dec = ord($char);
		
					// convert space at eol only
					if ( ($dec == 32) && ($i == ($linlen - 1)) ) {
						$char = '=20';
					} elseif ($dec == 9) {
						// Do nothing if a tab.
					} elseif ( ($dec == 61) || ($dec < 32 ) || ($dec > 126) ) {
						$char = $escape . strtoupper(sprintf('%02s', dechex($dec)));
					}
		
					// $this->lf is not counted
					if ((strlen($newline) + strlen($char)) >= $line_max) {
						// soft line break; " =\r\n" is okay
						$output .= $newline . $escape . $eol;
						$newline = '';
					}
					$newline .= $char;
				}
				$output .= $newline . $eol;
			}
		}
		// Don't want last crlf
		$output = substr($output, 0, -1 * strlen($eol));
	
		return $output;
	}
}
?>
