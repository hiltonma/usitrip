<?php 

class mail_receive_pop3{
	
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
	 * 主机的POP3端口，一般是110号端口
	 * @var int
	 */
	private $port = 110;
	
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
	 * 指示需要使用加密方式进行密码验证，一般服务器不需要
	 * @var boolean
	 */
	private $apop = false;
	
	/**
	 * 邮件总数
	 * @var unknown_type
	 */
	private $messages;
	
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
	 * POP3方式收取邮件
	 * @param string $hostname 收件地址
	 * @param string $user 登录帐号
	 * @param string $pass 登录密码
	 * @param int $port 收件端口
	 * @param int $time_out 超时时长
	*/
	public function __construct($hostname = "",$user = "", $pass = "", $port=110, $time_out=5) {
		$this->set_hostname($hostname);
		$this->set_port($port);
		$this->set_timeout($time_out);
		$this->set_user($user);
		$this->set_pass($pass);
	}
	
	/**
	 * 设置收件POP地址
	 * eg: $hostname = 'pop.qq.com';
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
	 * 设置收件端口，默认是110
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
	 * 指示需要使用加密方式进行密码验证，一般服务器不需要
	 * @param boolean $bool
	 */
	public function set_ssl($bool){
		if ($bool === true) {
			$this->apop = true;
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
		
		if(!$this->connection = fsockopen($this->hostname,$this->port,$this->err_no, $this->err_str, $this->timeout)) {
			
			$this->err_str="连接到POP服务器失败，错误信息：" . $this->err_str . "错误号：" . $this->err_no;
			return false;
		} else {
			$this->get_resp();
			if($this->debug){
				$this->out_debug($this->resp);
			}
			if (substr($this->resp,0,3)!="+OK")	{
				$this->err_str="服务器返回无效的信息：".$this->resp."请检查POP服务器是否正确";
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
			$this->resp .= fgets($this->connection, 100);
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
		//服务器是否采用APOP用户认证
		if (!$this->apop) {
			if (!$this->command("USER $this->user", 3, "+OK")) {
				return false;
			}
			if (!$this->command("PASS $this->pass", 3, "+OK")){
				return false;
			}
		}else{
			if (!$this->command("APOP $this->user " . md5($this->pass), 3, "+OK")){
				return false;
			}
		}

		$this->state = "TRANSACTION"; // 用户认证通过，进入传送模式
		return true;
	}

	/**
	 * 连接服务器
	 */
	private function connection(){
		if (!$this->open()) {
			return false;
		}
		if (!$this->login()){
			return false;
		}
		return true;
	}
	
	/**
	 * 取得邮件总数与所有邮件的总大小
	 * 统计有多少邮件，一共多少字节
	 * 返回 array('mail_num'=>邮件总数,'mail_size'=>邮件总大小);
	 * 失败则返回 false
	 * @return array | boolean
	 */
	public function get_mail_totals() {
		if($this->state!="TRANSACTION")	{
			if (!$this->connection()){
				$this->err_str="还没有连接到服务器或没有成功登录";
				return false;
			}
		}

		if (!$this->command("STAT",3,"+OK")) {
			return false;
		} else {
			$this->resp=strtok($this->resp," ");
			$this->messages=strtok(" "); // 取得邮件总数
			$this->size=strtok(" "); //取得总的字节大小
			//return true;
			return array('mail_num'=>$this->messages,'mail_size'=>$this->size);
		}
	}
	
	/**
	 * 获取所有邮件的唯一标识符
	 * return array(array(0=>'邮件序号',1=>'标识符'[,array(0=>'邮件序号',1=>'标识符')[,...]]))
	 * @return boolean|array
	 */
	public function get_identifier_all(){
		if (count($this->mail_list) == 0) {
			if (!$this->get_list()){
				return false; 
			}
		}
		$rtn = array();
		foreach($this->mail_list as $val){
			$rtn[] = $this->get_identifier($val['num']);
		}
		return $rtn;
	}
	
	/**
	 * 获取邮件唯一标识符
	 * 返回 array(0=>'邮件序号',1=>'标识符')
	 * @param int $num 邮件序号
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
			$command = 'UIDL ' . $num;
			if (!$this->command("$command", 3, "+OK")) {
				return false;
			} else {
				//+OK 1 ZL1526-aXMdkHUdbptyqjV68F5hS26
				$rtn = explode(' ',$this->resp);
				array_shift($rtn);
				return $rtn;
			}
		}
		return false;
	}
	
	/**
	 * 取得的各个邮件的对应序号与大小
	 * 执行正确，则返回邮件列表，包括邮件号和大小,否则返回false
	 * 返回 array(array('num'=>'邮件序号','size'=>234234),array('num'=>'aaa','size'=>234234)...)
	 * @param int $num 需要获取大小的邮件序号，默认取所有
	 * @return array|boolean
	 */
	public function get_list($num = 0) {
		if($this->state!="TRANSACTION") {
			// 如果没连接则试着去接接
			if (!$this->connection()){
				$this->err_str="还没有连接到服务器或没有成功登录";
				return false;
			}
		}
		
		if ((int)$num > 0) {
			$command = "LIST " . $num;
			if (!$this->command($command, 3 ,"+OK")) {
				return false;
			} else {
				$rtn = explode(' ',$this->resp);
				array_shift($rtn);
				return array('num'=>$rtn[0],'size'=>$rtn[1]);
			}
		} else {
			$command="LIST ";
		}
		
		if (!$this->command($command, 3, "+OK")) {
			return false;
		} else {
			$i=0;
			$this->mail_list=array();
			$this->get_resp();
			while ($this->resp!=".")
			{
				$i++;
				if ($this->debug){
					$this->out_debug($this->resp);
				}
				
				/* strtok 按标记分割字符串
				 * 按空格一次取字符串的前一个内容，eg:
				* $str = 'a b c';
				* strtok($str,' ') 返回a; 再继续 strtok(' '); 则返回B，注意 第二次 不需要再传入原始字符串。
				*/
				$this->mail_list[$i]['num'] = intval(strtok($this->resp," "));
				$this->mail_list[$i]['size'] = intval(strtok(" "));
				$this->get_resp();
			}
			return $this->mail_list;
		}
	}
	
	/**
	 * 获取服务器上的所有邮件
	 * @return array
	 */
	public function get_mail_all(){
		if (count($this->mail_list) == 0) {
			if (!$this->get_list()) {
				return false;
			}
		}
		$rtn = array();
		foreach($this->mail_list as $val){
			$rtn[] = $this->get_mail($val['num']);
		}
		return $rtn;
	}
	
	/**
	 * 取得邮件的内容，$num是邮件的序号，$line是指定共取得正文的多少行。有些时候，如邮件比较大而我们只想先查看邮件的主题时是必须指定行数的。
	 * 默认值$line=-1，即取回所有的邮件内容，取得的内容存放到内部变量$head，$body两个数组里，数组里的每一个元素对应的是邮件源代码的一行。
	 * 如果成功，则返回一个数组，否则返回FALSE
	 * @param int $num 邮件的序号
	 * @param int $line 取邮件的多少行 默认全部 即完整的邮件
	 * @return array | boolean
	 */
	public function get_mail($num=1,$line=-1) {
		if($this->state!="TRANSACTION")	{
			/*$this->err_str="不能收取信件，还没有连接到服务器或没有成功登录";
			return false;*/
			// 如果没连接则试着去接接
			if (!$this->connection()){
				$this->err_str="还没有连接到服务器或没有成功登录";
				return false;
			}
		}
		if ($line<0) {
			$command="RETR $num";
		} else {
			$command="TOP $num $line";
		}
		$rtn = array('head'=>'','body'=>'');
		if (!$this->command("$command",3,"+OK")) {
			return false;
		} else {
			$this->get_resp();
			$is_head=true;
			// . 号是邮件结束的标识
			$rtn = array();
			while ($this->resp!=".") {
				if ($this->debug) {
					$this->out_debug($this->resp);
				}
				if (substr($this->resp,0,1)==".") {
					$this->resp=substr($this->resp,1,strlen($this->resp)-1);
				}
				// 邮件头与正文部分的是一个空行
				/*if (trim($this->resp)=="") {
					$is_head=false;
				}
				if ($is_head) {
					//$this->head[]=$this->resp;
					$rtn['head'][] = $this->resp;
				} else {
					//$this->body[]=$this->resp;
					$rtn['body'][] = $this->resp;
				}*/
				$rtn[] = $this->resp . "\r\n";
				$this->get_resp();
			}
			//return true;
			return $rtn;
		}
	}
			
	/**
	 * 删除指定序号的邮件，$num 是服务器上的邮件序号
	 * @param unknown_type $num
	 * @return boolean
	 */
	public function dele($num) {
		if($this->state!="TRANSACTION") {
			$this->err_str="不能删除远程信件，还没有连接到服务器或没有成功登录";
			return false;
		}
		
		if (!$num) {
			$this->err_str="删除的参数不对";
			return false;
		}

		if ($this->command("DELE $num ",3,"+OK")) {
			return true;
		} else {
			return false;
		}
	}

	//通过以上几个方法，我们已经可以实现邮件的查看、收取、删除的操作，不过别忘了最后要退出，并关闭与服务器的连接，调用下面的这个方法：
	/**
	 * 最后要退出，并关闭与服务器的连接
	 */
	public function Close()	{
		if($this->connection!=0) {
			if($this->state=="TRANSACTION"){
				$this->command("QUIT", 3, "+OK");
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
	}
}

/*
$host="pop.qq.com";
$user="2683692314@qq.com";
$pass="123456789";
*/




//$rec = new pop3_get_mail($host,$user,$pass);
//$rec->set_debug(true);
//$mail_count = $rec->get_mail_totals();
//print_r($mail_count);
//$list = $rec->get_list();
//print_r($list);
//$rtn = $rec->get_mail(50);
//$indent_arr = $rec->get_identifier(49);
//$rec->get_list(1);
/*$mail_arr = $rec->get_mail_all();
foreach($mail_arr as $key => $val){
	$content = join("\r\n", $val['head']) . "\r\n" . join("\r\n", $val['body']);
	echo $val['indentifier'] . "<br/>";
	file_put_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . $val['indentifier'] . '.eml', $content);
}*/
/*$email_content = $rec->get_mail(49);
$content = join("\r\n",$email_content['head']) . "\r\n" . join("\r\n",$email_content['body']);
file_put_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . $indent_arr[1] . '.eml',$content);
*/
//print_r($rtn);


/* $html = join("\n",$rtn['head']);
$html .= join("\n",$rtn['body']);
echo $html; */
/*
$rec=new pop3($host,110,2);
if (!$rec->open()) die($rec->err_str);
echo "open ";
if (!$rec->login($user,$pass)) die($rec->err_str);
echo "login";
if (!$rec->stat()) die($rec->err_str);
echo "共有".$rec->messages."封信件，共".$rec->size."字节大小<br>";
if($rec->messages>0)
{
	if (!$rec->listmail()) die($rec->err_str);
	echo "有以下信件：<br>";

	for ($i=1;$i<=count($rec->mail_list);$i++)
	{
	echo "信件".$rec->mail_list[$i][num]."大小：".$rec->mail_list[$i][size]."<BR>";
	}

	$rec->getmail(1);
	echo "邮件头的内容：<br>";
	for ($i=0;$i<count($rec->head);$i++)
	echo htmlspecialchars($rec->head[$i])."<br>\n";

	echo "邮件正文　：<BR>";
    for ($i=0;$i<count($rec->body);$i++)
	echo htmlspecialchars($rec->body[$i])."<br>\n";
}
$rec->close();
*/
?>