<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);
/**
 * IMAP 方式收邮件
 * @author lwkai by 2012-08-03
 *
 */
class imap_get_mail{

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
		}
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
			return false;
		} else {
			$this->get_resp();
			if($this->debug){
				$this->out_debug($this->resp);
			}
			if (substr($this->resp,0,4)!="* OK")	{
				$this->err_str="服务器返回无效的信息：".$this->resp."请检查IMAP服务器是否正确";
				return false;
			}
			$this->state="AUTHORIZATION";
			return true;
		}
	}


	/**
	 * 接收SOCKET传过来的数据
	 * @return boolean
	 */
	private function get_resp(){
		for($this->resp = ""; ; ){
			if(feof($this->connection)) {
				return false;
			}
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
	 * 这个方法取得服务器端的返回信息并进行简单的处理：去掉最后的回车换行符，将返回信息保存在resp这个内部变量中。这个方法在后面的多个操作中都将用到。另外，还有个小方法也在后面的多个操作中用到：
	 * 它的作用就是把调试信息$message显示出来，并把一些特殊字符进行转换以及在行尾加上<br>标签，这样是为了使其输出的调试信息便于阅读和分析。
	 * @param string $message
	 */
	private function out_debug($message) {
		echo htmlspecialchars($message)."<br>\n";
	}

	/**
	 * 用已经建立的与服务器的SOCKET连接，发送指令
	 *
	 * 建立起与服务器的sock连接之后，就要给服务器发送相关的命令了（请参见上面的与服务器对话的过程）从上面对 POP对话的分析可以看到，
	 * 每次都是发送一条命令，然后服务器给予一定的回应，如果命令的执行是对的，回应一般是以+OK开头，后面是一些描述信息，
	 * 对于一般的pop操作来说，如果服务器的返回第一个字符为"+"，则可以认为命令是正确执行了。也可以用前面提到过的三个字符"+OK"做为判断的标识。
	 * @param string $command 发送给服务器的命令
	 * @param int $return_lenth 从服务器返回中取值的长度
	 * @param string $return_code 从服务器取回的值与此值是否相同，以判断服务器是否返回了正常的信息
	 * @return boolean
	 */
	private function command($command,$return_lenth=1,$return_code='+') {
		if($this->connection == 0) {
			$this->err_str = "没有连接到任何服务器，请检查网络连接";
			return false;
		}

		if($this->debug) {
			$this->out_debug(">>> $command");
		}

		if (!fputs($this->connection,"$command\r\n")) {
			$this->err_str = "无法发送命令" . $command;
			return false;
		} else {
			$this->get_resp();
			if($this->debug) {
				$this->out_debug($this->resp);
			}
			if (substr($this->resp,0,$return_lenth) != $return_code) {
				$this->err_str = $command . " 命令服务器返回无效:" . $this->resp;
				return false;
			} else {
				return true;
			}
		}
	}

	/**
	 * 发送用户名及密码，登录到服务器
	 * @param string $user 登录用户名
	 * @param string $password 登录密码
	 * @return boolean
	 */
	private function login() {
		if($this->state!="AUTHORIZATION") {
			$this->err_str = "还没有连接到服务器或状态不对";
			return false;
		}

		if (!$this->command($this->tag . ' login ' . $this->user . ' ' . $this->pass, strlen($this->tag) + 3, $this->tag . " OK")){
			//return false;
		}
		echo 'get 1<hr>';
		flush();
		$this->get_resp();
		echo 'output<hr>';	
		if($this->debug) {
				$this->out_debug($this->resp);
			}
		$this->state = "TRANSACTION"; // 用户认证通过，进入传送模式
		echo 'agen send messag<hr>';
		$box ='INBOX';
		$this->command($this->tag . ' select "' . $box . '"',2,"* ");
		echo '============';
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
			return false;
		}
		if (!$this->command($this->tag . ' select "' . $box . '"',2,"* ")){
			return false;
		}
		$rtn = array();
		$rtn[] = $this->resp;
		$this->get_resp();
		if($this->debug) {
			$this->out_debug($this->resp);
		}
		$endTag = $this->tag . " OK";
		while (substr($this->resp,0,strlen($endTag)) != $endTag){
			$rtn[] = $this->resp;
			$this->get_resp();
			if($this->debug) {
				$this->out_debug($this->resp);
			}
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
	}
	
	
	/**
	 * 连接服务器
	 */
	public function connection(){
		if (!$this->open()) {
			return false;
		}
		echo '<hr>';
		if (!$this->login()){
			return false;
		}
		
		exit;
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
		return $this->mail_unseen;
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
			if (!$this->command("$command", 2, "* ")) {
				return false;
			} else {
				$rtn = array();
				do {
					if (preg_match("/\*\s(\d+)\sFETCH\s\(UID\s(\d+)\)/i", $this->resp,$matches)){
						$rtn[] = array($matches[1],$matches[2]);
					}
					$this->get_resp();
					if($this->debug) {
						$this->out_debug($this->resp);
					}
				} while (strtolower(substr($this->resp,strlen('Completed') * -1)) != 'completed');
				
				//+OK 1 ZL1526-aXMdkHUdbptyqjV68F5hS26
				//$rtn = explode(' ',$this->resp);
				//array_shift($rtn);
				return $rtn;
			}
		}
		return false;
	}

	/**
	 * 取得邮件的内容，$num是邮件的序号,取得的内容存放到内部变量$head，$body两个数组里，数组里的每一个元素对应的是邮件源代码的一行。
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
				$command = $this->tag . " fetch " . $num . " body[]";
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
		
		$rtn = array('head'=>'','body'=>'');
		if (!$this->command("$command",2,"* ")) {
			return false;
		} else {

			$mail = array();
				
			$rtn = array();
			
			// 取邮件序号 加 返回的大小
			if(preg_match("/\*\s(\d+)\sFETCH\s[^\{]+\{(\d+)\}/", $this->resp,$matches)) {
				$rtn['num'] = $matches[1];
				$rtn['size'] = $matches[2];
				
			}
			
			
			$this->get_resp();
			$is_head=true;
			
			while (strtolower(substr($this->resp,strlen('completed') * -1)) != 'completed') {
				
				if ($this->resp == ')') {
					$mail[] = $rtn;
					$rtn = array();
					$is_head = true;
					$this->get_resp();
					if(preg_match("/\*\s(\d+)\sFETCH\s[^\{]+\{(\d+)\}/", $this->resp,$matches)) {
						$rtn['num'] = $matches[1];
						$rtn['size'] = $matches[2];
						$this->get_resp();
					}
					if (strtolower(substr($this->resp,strlen('completed') * -1)) == 'completed') {
						break;
					}
				}
				
				if ($this->debug) {
					$this->out_debug($this->resp);
				}
				if (substr($this->resp,0,1)==".") {
					$this->resp=substr($this->resp,1,strlen($this->resp)-1);
				}
				// 邮件头与正文部分的是一个空行
				if (trim($this->resp)=="") {
					$is_head=false;
				}
				if ($is_head) {
					//$this->head[]=$this->resp;
					$rtn['head'][] = $this->resp;
				} else {
					//$this->body[]=$this->resp;
					$rtn['body'][] = $this->resp;
				}
				$this->get_resp();
				

			}
			
			foreach ($mail as $key => $val) {
				$indent = $this->get_identifier($val['num']);
				if ($indent[0][0] == $val['num']) {
					$mail[$key]['indentifier'] = $indent[0][1];
				}
			}
			/*$indent = $this->get_identifier($m_index);
			if ($indent[0] == $m_index) {
				$rtn['indentifier'] = $indent[1];
			}*/
			
			return $mail;
		}
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
		
		if (!$this->command("$mail_tag",0,"")) {
			return false;
		} else {
			while (strtolower(substr($this->resp,strlen('completed') * -1)) != 'completed') {
				$this->get_resp();
				if ($this->debug) {
					$this->out_debug($this->resp);
				}
			}
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
		echo $this->state;
	}
}




//$a = new imap_get_mail('imap.qq.com','2683692314','usitrip2012');
$a = new imap_get_mail('ssl://imap.gmail.com','service@usitrip.com','KLda$071233USI429','993');//557 没有信息返回
$a->set_debug(true);
$a->connection();
$total = $a->get_mail_totals();
$news = $a->get_unseen();
$arr = $a->unseen($total);
var_dump($arr);



/* $mb = imap_open("{imap.qq.com:143}inbox", "2683692314", "123456789");
$allheaders = imap_uid($mb);
imap_close($mb);
var_dump($allheaders);
 *//* echo "<pre>\n";
for ($i=0; $i < count($allheaders); $i++) {
echo $allheaders[$i]."<p><hr><p>\n";
}
echo "</pre>\n";  */
