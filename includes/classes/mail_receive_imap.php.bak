<?php
/*ini_set('display_errors', '1');
error_reporting(E_ALL & ~E_NOTICE);
set_time_limit(50);
ob_end_flush();*/

/**
 * IMAP 方式收邮件
 * @author lwkai by 2012-08-03
 *
 */
class mail_receive_imap{

	/**
	 * POP 主机名
	 * @var string
	 */
	private $hostname = "";

	/**
	 * POP 用户名
	 * @var string
	 */
	private $user = '';

	/**
	 * POP 密码
	 * @var string
	 */
	private $pass = '';

	/**
	 * 主机的IMAP端口，一般是143号端口
	 * @var int
	 */
	private $port = 143;

	/**
	 * 连接主机的最大超时时间
	 * @var int
	 */
	private $timeout = 5;

	/**
	 * 保存与主机的连接
	 * @var resource
	 */
	private $connection = 0;

	/**
	 * 保存当前的状态 [字符串]
	 * @var string
	 */
	private $state = "DISCONNECTED";

	/**
	 * 调试开关 打开之后输出调试信息
	 * @var boolean
	 */
	private $debug = false;

	/**
	 * 如果出错，这里保存错误信息
	 * @var string
	 */
	private $err_str='';

	/**
	 * 如果出错，这里保存错误号码
	 * @var number
	 */
	private $err_no = 0;

	/**
	 * 临时保存服务器的响应信息
	 * @var string
	 */
	private $resp = '';

	/**
	 * 命令前缀符
	 * @var string
	 */
	private $tag = '';

	/**
	 * 邮件总数
	 * @var int
	 */
	private $mail_total = 0;
	
	/**
	 * 未读邮件数
	 * @var int
	 */
	private $mail_unseen = 0;

	/**
	 * 未读的邮件序号，读取时，直接用序号去服务器读
	 * @var array
	 */
	private $mail_unseen_num = array();
	
	/**
	 * 各邮件的总大小
	 * @var int
	 */
	private $size;

	/**
	 * 保存各个邮件的大小及其在邮件服务器上序号
	 * @var array
	 */
	private $mail_list = array();

	/**
	 * 邮件头的内容，数组
	 * @var array
	*/
	private $head=array();

	/**
	 * 邮件体的内容，数组;
	 * @var array
	*/
	private $body=array();
	
	
	/**
	 * 保存从服务器返回回来的数据
	 * @var array
	 */
	private $resp_arr = array();

	/**
	 * IMAP方式收取邮件
	 * @param string $hostname 收件地址
	 * @param string $user 登录帐号
	 * @param string $pass 登录密码
	 * @param int $port 收件端口
	 * @param int $time_out 超时时长
	*/
	public function __construct($hostname = "",$user = "", $pass = "", $port=143, $time_out=5) {
		$this->set_hostname($hostname);
		$this->set_port($port);
		$this->set_timeout($time_out);
		$this->set_user($user);
		$this->set_pass($pass);
		$str = 'abcdefghijklmnopqrstuvwxyz'; //标记字符串
		// 命令前缀
		$this->tag = $str[rand(0,25)] . $str[rand(0,25)];// 随机取一个
	}

	/**
	 * 设置收件IMAP地址
	 * eg: $hostname = 'imap.qq.com';
	 * @param string $hostname
	 */
	public function set_hostname($hostname) {
		if (!empty($hostname)) {
			$this->hostname = $hostname;
		}
	}

	/**
	 * 设置用户登录帐号
	 * @param string $user
	 */
	public function set_user($user){
		if (!empty($user)) {
			$this->user = $user;
		}
	}

	/**
	 * 设置用户登录密码
	 * @param string $pass
	 */
	public function set_pass($pass){
		if (!empty($pass)) {
			$this->pass = $pass;
		}
	}
	
	/**
	 * 设置收件端口，默认是143
	 * @param int $port
	 */
	public function set_port($port) {
		if ((int)$port > 0) {
			$this->port = $port;
		}
	}

	/**
	 * 设置主机超时时间，默认是5秒，以秒为单位
	 * @param int $timeout
	 */
	public function set_timeout($timeout) {
		if ((int)$timeout > 0) {
			$this->timeout = $timeout;
		}
	}

	/**
	 * 取得错误信息
	 */
	public function get_error_message(){
		return $this->err_str;
	}

	/**
	 * 设置调试开关
	 * @param boolean $switch
	 */
	public function set_debug($switch){
		if ((boolean)$switch == true) {
			$this->debug = true;
			//set_time_limit(50);
			//ob_end_flush();
		}
	}

	/**
	 * 接收数据，并检测是否成功执行命令
	 * @param string $tag 返加正确的那条消息的第一串字符，以空格分割后的第一个元素
	 */
	public function check($tag) {
		$this->resp_arr = array();
		do {
			$this->get_resp();
			$this->resp_arr[] = $this->resp . "\r\n";
			if($this->debug){
				$this->out_debug($this->resp);
				flush();
			}
			$str = explode(' ',$this->resp);
			// 如果最前面的前缀等于我设置的前缀，则表示，此行是需要进行判断的。
			// 因为在 select "inbox" 的时候，会返回N个OK的字符，在str[1]中，所以必须结合前缀来判断是否是需要判断的行。
			if ($str[0] == 'body[]') {
				$this->get_resp();
				$this->out_debug($this->resp);
				flush();
			}
			if ($str[0] == $tag || $str[0] == 'body[]') {
				switch (strtolower($str[1])) {
					case 'ok':
						return true;
						break;
					case 'no':
						return false;
						break;
					case 'bad':
						return false;
					break;
				}
			}
		} while (true);
	}
	
	/**
	 * 打开远程主机的连接
	 * @return boolean
	 */
	private function open(){
		if(empty($this->hostname)) {
			$this->err_str="无效的主机名!!";
			return false;
		}

		if ($this->debug) {
			echo "正在打开 $this->hostname,$this->port,$this->err_no, $this->err_str, $this->timeout<BR>";
		}

		if(!$this->connection = @fsockopen($this->hostname,$this->port,$this->err_no, $this->err_str, $this->timeout)) {
			$this->err_str="连接到IMAP服务器失败，错误信息：" . $this->err_str . "错误号：" . $this->err_no;
			if ($this->debug) {
				$this-> out_debug($this->err_str);
			}
			return false;
		} else {
			if (!$this->check('*')) {
				$msg = $this->resp_arr;
				if (is_array($msg)) {
					$msg = join("\r\n",$msg);
				}
				$this->err_str = "服务器返回无效的信息：". $msg ."请检查IMAP服务器是否正确";
				if ($this->debug) {
					$this->out_debug($this->err_str);
				}
				return false;
			}
			$this->state="AUTHORIZATION";
			return true;
		}
	}

	
	private function safe_feof($fp,&$start = NULL) {
		$start = microtime(true);
		return feof($fp);
	}

	/**
	 * 接收SOCKET传过来的数据
	 * @return boolean
	 */
	private function get_resp(){
		stream_set_timeout($this->connection, $this->timeout); // 设置SOCKET超时时长 单位为秒
		$this->resp = '';
		/*for($this->resp = ""; ; ){
			if(feof($this->connection)) {
				echo 'The connection has been disconnected';
				exit;
				return false;
			}*/
		$start = null;
		$timeout = ini_get('default_socket_timeout');
		while (!$this->safe_feof($this->connection,$start) && (microtime(true) - $start) < $timeout) {
			$this->resp .= fgets($this->connection);
			$length = strlen($this->resp);
			if($length>=2 && (substr($this->resp,$length-2,2)=="\r\n" || substr($this->resp,$length-1,1) == "\n")){
				$this->resp = strtok($this->resp,"\r\n");
				return true;
			}
		}
	}

	/**
	 * 输出调试信息，或者输出你想输出的信息
	 * 它的作用就是把调试信息$message显示出来，并把一些特殊字符进行转换以及在行尾加上<br>标签，这样是为了使其输出的调试信息便于阅读和分析。
	 * @param string $message
	 */
	private function out_debug($message) {
		echo htmlspecialchars($message)."<br>\n";
		var_dump($message);
		flush();
	}

	/**
	 * 用已经建立的与服务器的SOCKET连接，发送指令，如果执行命令之后，服务器返回执行成功，则返回服务器返回的信息，否则返回FALSE
	 * 建立起与服务器的sock连接之后，就要给服务器发送相关的命令,
	 * 每次都是发送一条命令，然后服务器给予一定的回应，如果命令的执行是对的，回应一般是以（标记+空格+[OK｜NO｜BAD]开头，后面是一些描述信息，
	 * @param string $command 发送给服务器的命令
	 * @return boolean|服务器返回的信息数组
	 */
	private function command($command) {
		if($this->connection == 0) {
			$this->err_str = "没有连接到任何服务器，请检查网络连接";
			if ($this->debug) {
				$this->out_debug($this->err_str);
				flush();
			}
			return false;
		}
		if($this->debug) {
			$this->out_debug(">>> $command");
		}
		if (!fputs($this->connection,"$command\r\n")) {
			$this->err_str = "无法发送命令" . $command;
			if ($this->debug) {
				$this->out_debug($this->err_str);
			}
			return false;
		} else {
			$rtn = $this->check($this->tag);
			if ($rtn == true) {
				return $this->resp_arr;
			}
			return false;
		}
	}

	/**
	 * 发送用户名及密码，登录到服务器
	 * @return boolean
	 * @throws 登录失败，则抛出异常
	 */
	private function login() {
		if($this->state!="AUTHORIZATION") {
			$this->err_str = "还没有连接到服务器或状态不对";
			if ($this->debug) {
				$this-> out_debug($this->err_str);
			}
			return false;
		}
		$rtn = $this->command($this->tag . ' login ' . $this->user . ' ' . $this->pass);
		if ($rtn == false){
			$this->err_str = "登录失败！";
			if ($this->debug) {
				$this->out_debug($this->err_str);
			}
			throw new Exception('登录失败！',10065);
		}
		$this->state = "TRANSACTION"; // 用户认证通过，进入传送模式
		return true;
	}
	
	/**
	 * 选择收信的箱子,一般INBOX就是收件箱
	 * @param string $box
	 * @return boolean
	 */
	private function selectBox($box = "INBOX"){
		if($this->state!="TRANSACTION") {
			$this->err_str = "还没有连接到服务器或状态不对";
			if ($this->debug) {
				$this-> out_debug($this->err_str);
			}
			return false;
		}
		$rtn = $this->command($this->tag . ' select "' . $box . '"');

		if ($rtn == false){
			return false;
		}
		$this->encodeBox($rtn);
	}

	/**
	 * 从选择的箱子中取得总共多少邮件,有几封未读
	 * @param array $arr 服务器返回的信息,每行为一个数组元素
	 */
	private function encodeBox($arr){
		if (is_array($arr) == true) {
			foreach ($arr as $val){
				switch (true){
					case preg_match("/\*\s(\d+)\sEXISTS/",$val,$matchs):
						$this->mail_total = $matchs[1];
						if($this->debug) {
							$this->out_debug('总共' . $this->mail_total . '封邮件!');
						}
						break;
					case preg_match("/\*\sOK\s\[UNSEEN\s(\d+)\]/",$val,$matchs):
						$this->mail_unseen = $matchs[1];
						
						if($this->debug) {
							$this->out_debug('未读邮件' . $this->mail_unseen . '封!');
						}
						break;
				}
			}
		}
		$this->unseen_identifier();
	}
	
	/**
	 * 取得未读邮件的序号,成功返回TRUE 失败返回 false
	 * @return boolean
	 */
	private function unseen_identifier(){
		if ($this->state != 'TRANSACTION') {
			$this->err_str = "还没有连接到服务器或状态不对";
			if ($this->debug) {
				$this-> out_debug($this->err_str);
			}
			return false;
		}
		$rtn = $this->command($this->tag . ' SEARCH UNSEEN');
		if ($rtn == false){
			return false;
		}
		if (is_array($rtn)) {
			$temp = trim($rtn[0]);
			$rtn_arr = explode(' ', $temp);
			array_shift($rtn_arr);
			array_shift($rtn_arr);
			$this->mail_unseen_num = $rtn_arr;
			return true;
		}
		return false;
	}
	
	/**
	 * 连接服务器
	 * @throws 登录失败，则抛出异常
	 */
	public function connection(){
		if (!$this->open()) {
			return false;
		}
		if (!$this->login()){
			return false;
		}
		
		$this->selectBox();
		return true;
	}

	/**
	 * 取得邮件总数
	 * @return int
	 */
	public function get_mail_totals() {
		return $this->mail_total;
	}

	/**
	 * 取得有几封未读邮件
	 * @return number
	 */
	public function get_unseen(){
		if ($this->mail_unseen > 0) {
			return $this->mail_unseen;
		} else {
			return count($this->mail_unseen_num);
		}
	}
	
	/**
	 * 获取未读邮件在服务器端的序号
	 * @return array;
	 */
	public function get_unseen_identifier() {
		return $this->mail_unseen_num;
	}
	
	/**
	 * 获取所有邮件的唯一标识符
	 * return array(array(0=>'邮件序号',1=>'标识符'[,array(0=>'邮件序号',1=>'标识符')[,...]]))
	 * @return boolean|array
	 */
	public function get_identifier_all(){
		if ($this->state != 'TRANSACTION') {
			// 如果没连接则试着去接接
			if (!$this->connection()){
				$this->err_str="还没有连接到服务器或没有成功登录";
				return false;
			}
		}
		if (count($this->mail_total) == 0) {
			return false;
		}
		$rtn = $this->get_identifier('1:' . $this->mail_total); 
		return $rtn;
	}

	/**
	 * 获取邮件唯一标识符
	 * 返回 array(array(0=>'邮件序号',1=>'标识符')[,array(0=>'邮件序号',1=>'标识符')[,...]])
	 * @param string $num 邮件序号 (5或者1:5) 1:5 表示从序号1开始,到5结束的所有UID
	 * @return array
	 */
	public function get_identifier($num){
		if($this->state!="TRANSACTION") {
			// 如果没连接则试着去接接
			if (!$this->connection()){
				$this->err_str="还没有连接到服务器或没有成功登录";
				return false;
			}
		}
		if ((int)$num > 0) {
			$command = $this->tag . ' fetch ' . $num . ' uid';
			$temp = $this->command("$command");
			if ($temp == false) {
				return false;
			} else {
				$rtn = array();
				foreach ($temp as $key => $val) {
					if (preg_match("/\*\s(\d+)\sFETCH\s\(UID\s(\d+)\)/i", $val,$matches)){
						$rtn[] = array($matches[1],$matches[2]);
					}
				} 
				return $rtn;
			}
		}
		return false;
	}

	/**
	 * 取得邮件的内容，$num是邮件的序号,
	 * 如果成功，则返回一个数组，否则返回FALSE
	 * @param int $num 邮件的序号
	 * @param int $type 取邮件的方式,默认是 all 取全部 可以是 all,head,text,或者你自己传过来IMAP的请求参数
	 * 完整的命令是   tag fetch mailIndex Command . 你只需要传 Command就可以了
	 * @return array | boolean
	 */
	public function get_mail($num=1,$type='all') {
		if($this->state!="TRANSACTION")	{
			// 如果没连接则试着去接接
			if (!$this->connection()){
				$this->err_str="还没有连接到服务器或没有成功登录";
				return false;
			}
		}
		
        $type = strtolower($type);
        
		switch(true){
			case $type == 'all':
				$command = $this->tag . ' fetch ' . $num . ' body[]';
				break;
			case $type == 'head':
				$command = $this->tag . ' fetch ' . $num . ' body[header]';
				break;
			case $type == 'text':
				$command = $this->tag . ' fetch ' . $num . ' body[text]';
				break;
			default:
				$command = $this->tag . ' fetch ' . $num . ' ' . $type;
				break;			
		}
		// 邮件正文体返回结束符好像是 ）
		$temp = $this->command($command);
		if ($temp == false) {
			return false;
		} else {
			if (is_array($temp)) {
				// 取得返回的大小 
				if(preg_match("/\*\s(\d+)\sFETCH\s[^\{]+\{(\d+)\}/", $temp[0],$matches)) {
					$this->size = $matches[2];
				}
				array_shift($temp);
				array_pop($temp);
			}
			return $temp;
		}
		return $temp;
	}
	
	/**
	 * 给邮件设置标记
	 * 
	 * @param string $mail_tag
	 */
	private function set_store($mail_tag){
		if (empty($mail_tag)) {
			$this->err_str="没有操作命令!";
			return false;
		}
		if($this->state!="TRANSACTION") {
			$this->err_str="操作失败! 未连接服务器或未登录!";
			return false;
		}
		
		$rtn = $this->command("$mail_tag");
		if ($rtn == false) {
			return false;
		} else {
			return true;
		}
	}
		
	/**
	 * 删除指定序号的邮件，$num 是服务器上的邮件序号
	 * @param int $num
	 * @return boolean
	 */
	public function dele($num) {
		if (!is_numeric($num)){
			return false;
		}
		$command = $this->tag . ' store ' . $num . ' +flags (\Deleted)';
		return $this->set_store($command);
	}
	
	/**
	 * 设置邮件为已读
	 * @param int $num
	 * @return boolean
	 */
	public function seen($num) {
		if (!is_numeric($num)) {
			return false;
		}
		$command = $this->tag . ' store ' . $num . ' +flags (\Seen)';
		return $this->set_store($command);
	}
	
	/**
	 * 设置邮件为未读
	 * @param int $num
	 * @return boolean
	 */
	public function unseen($num) {
		if (!is_numeric($num)) {
			return false;
		}
		$command = $this->tag . ' store ' . $num . ' -flags (\Seen)';
		return $this->set_store($command);
	}

	/**
	 * 最后要退出，并关闭与服务器的连接
	 */
	public function Close()	{
		if($this->connection!=0) {
			if($this->state=="TRANSACTION"){
				$this->command($this->tag . " logout", 5, "* BYE");
			}
			fclose($this->connection);
			$this->connection = 0;
			$this->state = "DISCONNECTED";
		}
	}

	/**
	 * 销毁掉类的时候，关闭连接
	 */
	public function __destruct(){
		$this->Close();
		//echo $this->state;
	}
}

/*
try{
if (isset($_GET['type']) && $_GET['type'] == 'qq') {
	$a = new mail_receive_imap('imap.qq.com','2683692314','');
} else {
	$a = new mail_receive_imap('ssl://imap.gmail.com','service@usitrip.com','','993');//557 没有信息返回
}
//$a->set_debug(true);
$a->connection();
//$arr = $a->get_identifier('1:5');
$news = $a->get_unseen();
//$arr = $a->unseen(3);
echo '未读邮件 ' . $news . '<br/>';
$arr = $a->get_unseen_identifier();
echo 'number:<br/>';
$a->Close();
print_r($arr);
//$a->seen($arr[0]);
//$arr = $a->get_mail(2);
//print_r($arr);
} catch (Exception $e) {
	print_r($e);
}
exit;

$total = $a->get_mail_totals();
$news = $a->get_unseen();
$arr = $a->unseen($total);
$a->seen($total);
var_dump($arr);

*/

/* $mb = imap_open("{imap.qq.com:143}inbox", "2683692314", "123456789");
$allheaders = imap_uid($mb);
imap_close($mb);
var_dump($allheaders);
 *//* echo "<pre>\n";
for ($i=0; $i < count($allheaders); $i++) {
echo $allheaders[$i]."<p><hr><p>\n";
}
echo "</pre>\n";  */
