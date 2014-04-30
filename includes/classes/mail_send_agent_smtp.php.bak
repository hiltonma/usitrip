<?php
/*ini_set('display_errors', '1');
error_reporting(E_ALL & ~E_NOTICE);
set_time_limit(50);
ob_end_flush();*/
/**
 * 通过SMTP第三方接口发送邮件
 * @author lwkai 2013-1-7 下午4:47:45
 *
 */
class mail_send_agent_smtp {
	/**
	 * POP 主机名
	 * @var string
	 */
	private $_hostname = "";
	
	/**
	 * POP 用户名
	 * @var string
	 */
	private $_user = '';
	
	/**
	 * POP 密码
	 * @var string
	 */
	private $_pass = '';
	
	/**
	 * 主机的smtp端口，一般是110号端口
	 * @var int
	 */
	private $_port = '25';
	
	/**
	 * 保存与主机的连接
	 * @var resource
	 */
	private $_connection = 0;
	
	/**
	 * 调试开关 打开之后输出调试信息
	 * @var boolean
	 */
	private $_debug = false;
	
	/**
	 * 临时保存服务器的响应信息
	 * @var string
	 */
	private $_resp = '';
	
	/**
	 * 是否需要记录日志 0不记录 1记录错误日志 2记录错误和成功
	 * @var int
	 */
	private $_write_log = 0;
	
	/**
	 * 日志记录的位置
	 * @var string
	 * @author lwkai 2013-1-7 下午1:34:13
	 */
	private $_log_path = '';
	
	/**
	 * 记录出错信息
	 * @var array
	 * @author lwkai 2013-1-7 下午1:13:12
	 */
	private $_error = array();
	
	/**
	 * 
	 * @var unknown_type
	 * @author lwkai 2013-1-7 下午2:54:15
	 */
	private $_helo_name = '';
	
	private $_log_info = array();
	
	private $_timeout = 30;
	private $_err_no = 0;
	private $_err_str = '';
	
	/**
     * Returns the server hostname or 'localhost.localdomain' if unknown.
      * @return string
     */
    private function serverHostname() {
    	if (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] != "") {
            $result = $_SERVER['SERVER_NAME'];
    	} else {
            $result = "localhost.localdomain";
    	}
        return $result;
    }
    
	/**
	 * 初始化发送邮件的服务器信息
	 * @param string $pop_server 服务器地址
	 * @param string $user 用户名
	 * @param string $pass 密码
	 * @param string $port 端口
	 * @param boolean $debug 是否打开调试
	 * @param int $write_log 是否记录日志[0不记录[默认],1记录错误日志,2记录错误和成功日志]
	 * @param string $log_path 日志记录的位置
	 * @author lwkai 2013-1-7 下午1:37:23
	 */
	public function __construct($pop_server,$user,$pass,$port = '25',$debug = false, $write_log = 0, $log_path = '') {
		$this->_hostname = $pop_server;
		$this->_user = $user;
		$this->_pass = $pass;
		$this->_port = $port;
		$this->_debug = $debug;
		if (is_dir($log_path)) {
			$this->_write_log = $write_log;
			$this->_log_path = $log_path;
		}
	}
	
	private function writeLog($status) {
		
		if ($this->_write_log >= 1 && strtolower($status) == 'failed') {
			$data = "\n\n----------------------------------------\n";
			$data .= join("\n", $this->_log_info);
			$data .= '出错信息:' . "\n";
			$data .= print_r($this->_error,true);
			$data .= "\n";
			
			if (is_dir($this->_log_path)) {
				if (!in_array(substr($this->_log_path,-1,1),array('/','\\'))) {
					$this->_log_path .= DIRECTORY_SEPARATOR; 
				}
				$error_log_file = $this->_log_path . 'smtp_send_failed.txt';
				
			}
		}
		
		if ($this->_write_log == 2 && strtolower($status) == 'success') {
			$data = "\n\n----------------------------------------\n";
			$data .= join("\n", $this->_log_info);
			$data .= "\n";
				
			if (is_dir($this->_log_path)) {
				if (!in_array(substr($this->_log_path,-1,1),array('/','\\'))) {
					$this->_log_path .= DIRECTORY_SEPARATOR;
				}
				$error_log_file = $this->_log_path . 'smtp_send_success.txt';
			
			}
		}
		if ($this->_write_log) {
			$write_type = "ab";
			$file_max_size = 1024*1024*2; //2M
			if(@filesize($error_log_file)>$file_max_size){
				copy($error_log_file,$error_log_file . '.' . date('Ymd_His') . '.txt');
				$write_type = "wb";
			}
			if($handle = fopen($error_log_file, $write_type)){
				fwrite($handle, $data);
				fclose($handle);
			}
		}
	}
	/**
	 * 抛出异常
	 * @throws Exception
	 * @author lwkai 2013-1-7 下午1:25:56
	 */
	private function throwErr() {
		$this->writeLog('failed');
		throw new Exception($this->_error['error']);
	}
	
	/**
	 * 打开服务器连接
	 * @return boolean
	 * @author lwkai 2013-1-7 下午1:52:53
	 */
	private function open() {
		if(empty($this->_hostname)) {
			$this->_error = array(
				"error" => "Hostname can't of empty! [主机名不能为空]",
				"smtp_code" => '',
				"smtp_msg" => ''
			);
			$this->outDebug($this->_error['error']);
			$this->throwErr();
		}
		
		$this->outDebug("正在连接 $this->_hostname,$this->_port,<br/>");
		
		if(!$this->_connection = fsockopen($this->_hostname,$this->_port,$this->_err_no, $this->_err_str, $this->_timeout)) {
			$this->_error = array(
				"error" => "Failed of connect server! [连接服务器失败！]",
				"errno" => $this->_err_no,
				"errstr" => $this->_err_str
			);
			$this->throwErr();
		} else {
			# sometimes the SMTP server takes a little longer to respond
			# so we will give it a longer timeout for the first read
			// Windows still does not have support for this timeout function
			if(substr(PHP_OS, 0, 3) != "WIN")
				socket_set_timeout($this->_connection, $this->_timeout, 0);
			
			if (!$this->check('220')) {
				$this->_error = array(
					"error" => "Invalid information! [连接服务器后返回无效信息！]",
					"errorno" => '',
					"errstr" => $this->_resp
				);
				$this->throwErr();
			}
			return true;
		}
	}
	
	/**
	 * 检查当前连接是否有效
	 * @return boolean
	 * @author lwkai 2013-1-7 下午2:33:11
	 */
	private function connected() {
		if(!empty($this->_connection)) {
			$sock_status = socket_get_status($this->_connection);
			if($sock_status["eof"]) {
				$this->_error = array(
					'error'  => 'EOF caught while checking if connected! [当前连接已超时！]',
					'errno'  => '',
					'errstr' =>	''
				);
				$this->outDebug($this->_error['error']);
				$this->throwErr();
			}
			return true;
		}
		return false;
	}
		
	/**
	 * 登录
	 * 
	 * @author lwkai 2013-1-7 下午2:35:58
	 */
	private function login() {
		if (!$this->command(base64_encode($this->_user),'334') || !$this->command(base64_encode($this->_pass), '235')) {
			$this->_error = array(
					"error"   => "Failed of Username or Password! [错误的用户名或者密码！]",
					"smtp_code" => $this->_user . ' ' . $this->_pass,
					"errstr"  => $this->_resp
			);
			$this->outDebug($this->_error['error']);
			$this->throwErr();
		}
	}
	
	/**
	 * 与服务器打个招呼。。
	 * @return boolean
	 * @author lwkai 2013-1-7 下午3:19:55
	 */
	private function hello(){
		if (!$this->connected()) {
			$this->_error = array(
				"error" => "without being connected! [连接已超时！]"
			);
			$this->outDebug($this->_error['error']);
			$this->throwErr();
		}

		if ($this->_helo_name) {
			$host = $this->_helo_name;
		} else {
			$host = $this->serverHostname();
		}
		
		if (!$this->sendHello("HELO", $host)) {
			if (!$this->sendHello("EHLO", $host)) {
				return false;
			}
		}
		return true;
	}
	
	/**
	 * Sends a HELO/EHLO command.
	 * @param string $hello
	 * @param string $host
	 * @return boolean
	 * @author lwkai 2013-1-7 下午2:39:31
	 */
	private function sendHello($hello, $host) {

		if (!$this->command($hello . " " . $host, "250")) {
			$this->_error = array(
				'error' => $hello . " not accepted from server",
				'smtp_code' => $hello . " " . $host,
				'smtp_msg' => $this->_resp
			);
			$this->outDebug($this->_error['error']);
			$this->throwErr();
		}
			
		return true;
	}
	
	/**
	 * 连接服务器并登录
	 * 
	 * @author lwkai 2013-1-7 下午3:20:18
	 */
	public function connection() {
		$this->open();
		$this->hello();
		
		if (!$this->command("auth login",'334')) {
			$this->_error = array(
					'error' => "AUTH not accepted from server",
					'smtp_code' => 'auth login',
					'smtp_msg' => $this->_resp
			);
			$this->outDebug($this->_error['error']);
			$this->throwErr();
		}
		$this->login();
		$this->mailFrom($this->_user);
	}
	
	/**
	 * 接收数据，并检测是否成功执行命令
	 * @param string $tag 返加正确的那条消息的第一串字符，以空格分割后的第一个元素
	 */
	private function check($tag = array('250')) {
		is_string($tag) && $tag = array($tag);
		$this->getResp();
		$this->outDebug($this->_resp);
		$str = explode(' ',$this->_resp);
		if (in_array($str[0],$tag)) {
			return true;
		}
		return false;
	}
	
	private function safeFeof($fp,&$start = NULL) {
		$start = microtime(true);
		return feof($fp);
	}

	/**
	 * 接收SOCKET传过来的数据
	 * @return boolean
	 */
	private function getResp(){
		stream_set_timeout($this->_connection, $this->_timeout); // 设置SOCKET超时时长 单位为秒
		$this->_resp = '';
		$start = null;
		$timeout = ini_get('default_socket_timeout');
		while (!$this->safeFeof($this->_connection,$start) && (microtime(true) - $start) < $timeout) {
			$this->_resp .= fgets($this->_connection);
			$length = strlen($this->_resp);
			if($length >= 2 && (substr($this->_resp, $length - 2, 2) == "\r\n" || substr($this->_resp, $length - 1, 1) == "\n")){
				$this->_resp = strtok($this->_resp,"\r\n");
				return true;
			}
		}
	}

	/**
	 * 输出调试信息，或者输出你想输出的信息
	 * 它的作用就是把调试信息$message显示出来，并把一些特殊字符进行转换以及在行尾加上<br>标签，这样是为了使其输出的调试信息便于阅读和分析。
	 * @param string $message
	 */
	private function outDebug($message) {
		if ($this->_debug) {
			echo htmlspecialchars($message)."<br>\n";
			flush();
		}
	}
	
	/**
	 * 发送指令
	 * @param string $command 发送的命令字符串
	 * @param string $tag 判断返回的字符串与此字符串是否相符，用以判断命令执行结果,false则不进行检测
	 * @throws Exception 
	 * @return string|boolean
	 * @author lwkai 2012-12-28 下午2:59:24
	 */
	private function command($command, $tag) {
		if(!$this->_connection) {
			$this->_error = array(
				'error' => 'Not connection to server! [没有连接到服务器！]',
				'errno' => '',
				'errstr' => ''	
			);
			$this->outDebug($this->_error['error']);
			$this->throwErr();
		}
		$this->outDebug(">>> $command");
		if (!fputs($this->_connection,"$command\r\n")) {
			$this->_error = array(
				'error' => 'Failed of send command! [发送指令失败！]',
				'smtp_code' => $command,
				'smtp_msg' => ''
			);
			$this->outDebug($this->_error['error']);
			$this->throwErr();
		} else {
			$tag && $rtn = $this->check($tag);
			if ($rtn == true) {
				return $this->_resp;
			}
			return false;
		}
	}
	
	/**
	 * 设置当前邮件是从哪个邮箱发出
	 * @param unknown_type $string
	 * @author lwkai 2012-12-28 下午3:02:30
	 */
	private function mailFrom($string) {
		if (strpos($string,'@') === false) {
			$temp = explode('.',$this->_hostname);
			unset($temp[0]);
			$string = $string . '@' . join('.',$temp);
		}
		$this->_log_info[] = 'mail from: <' . $string . '>';
		if (!$this->command('mail from:<' . $string . '>', '250')) {
			$this->_error = array(
				'error' => 'mail from 命令执行失败！',
				'smtp_code' => 'mail from:<' . $string . '>',
				'smtp_msg' => $this->_resp
			);
			$this->outDebug($this->_error['error']);
			$this->throwErr();
		}
	}
	
	
	private function rcpt($to_address) {
		$to_mail = substr($to_address,strpos($to_address,'<'));
		if (!$this->command('rcpt to:' . $to_mail, array('250','251'))) {
			$this->_error[] = array(
					'error' => 'RCPT not accepted from server',
					'smtp_code' => 'rcpt to:' . $to_address,
					'smtp_smg' => $this->_resp
			);
			$this->outDebug($this->_error['error']);
			$this->throwErr();
		}
	}
	
	
	public function sendMail($to_address,$subject,$message,$headers) {
		if(!$this->connected()) {
			$this->_error = array(
					"error" => "Call sendMail without being connected!"
			);
			$this->outDebug($this->_error['error']);
			$this->throwErr();
		}
		
		$this->_error = array();
		$this->_log_info[] = 'to_address:' . $to_address;
		$this->rcpt($to_address);

		$headers = explode("\n",$headers);
		$bcc = $this->search('bcc', $headers);
		if ($bcc) {
			$this->_log_info[] = 'bcc:' . $bcc;
			$arr = explode(',', $bcc);
			foreach ($arr as $key => $val) {
				$this->rcpt($val);
			}
		}
		$cc = $this->search('cc', $headers);
		if ($cc) {
			$this->_log_info[] = 'cc:' . $cc;
			$arr = explode(',', $cc);
			foreach ($arr as $key => $val) {
				$this->rcpt($val);
			}
		}
		
		
		if (!$this->command('data', '354')) {
			$this->_error[] = array(
					'error' => 'DATA command not accepted from server',
					'smtp_code' => 'data',
					'smtp_smg' => $this->_resp
			);
			$this->outDebug($this->_error['error']);
			$this->throwErr();
		}
		
		$headers[] = 'To:' . $to_address;
		$headers[] = 'date:' . date('Y-m-d H:i:s');
		$headers[] = 'subject:' . $subject;
		$this->_log_info[] = 'date:' . date('Y-m-d H:i:s');
		$this->_log_info[] = 'subject:' . $subject;
		$this->command(join("\n",$headers), false);


		$this->command("\n",false);
		
		$this->command($message, false);

		
		if (!$this->command('.','250')) {
			$this->_error =	array(
				"error" => "DATA not accepted from server",
				"smtp_code" => '',
				"smtp_msg" => $this->_resp
			);
			$this->outDebug($this->_error['error']);
			$this->throwErr();
		}
		$this->writeLog('success');
		return true;
	}
	
	/**
	 * 查找指定的头信息,找不到 返回 flase
	 * @param string $tag 头信息标记
	 * @param array $array 头信息数组
	 * @return string
	 * @author lwkai 2013-1-7 下午2:31:31
	 */
	private function search($tag,$array) {
		if (is_string($array)) $array = array($array);
		if (is_array($array)) {
			foreach ($array as $key => $val) {
				$temp = explode(':',$val);
				if (strtolower($temp[0]) == strtolower($tag)) {
					return $temp[1];
				}
			}
		}
		return false;
	}
	
	/**
	 * 销毁掉类的时候，断开连接
	 * 
	 * @author lwkai 2012-12-28 下午3:00:55
	 */
	public function __destruct() {
		if ($this->_connection) {
			$this->command("quit",'221');
		}
		$this->close();
	}
	
	/**
	 * 关闭连接
	 * 
	 * @author lwkai 2013-1-7 下午2:30:26
	 */
	private function close() {
		if(!empty($this->_connection)) {
			fclose($this->_connection);
			$this->_connection = 0;
		}
	}
}
/*
try {
//$sendmail = new mail_send_agent_smtp('smtp.qq.com','username@qq.com','password','25',true);
$sendmail = new mail_send_agent_smtp('ssl://smtp.gmail.com', 'username', 'password','465',true,2,'/var/www/html/lwkai/usitrip.1/wwwroot/tmp/');
$sendmail->connection();
$sendmail->sendMail('<2355652780@qq.com>', 'test mail',"Hi , test2\nThis is a test mail,you don't reply it." . date('Y-m-d H:i:s'),"From:lwkai<li1275124829@gmail.com>\r\nCc:<27310221@qq.com>\r\nBcc:<2683692314@qq.com>\r\nDate:Mon,25 Oct 2013 14:24:27 +0800");
}catch (Exception $e) {
	echo($e->getMessage());
}
*/