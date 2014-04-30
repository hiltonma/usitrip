<?php
/**
 * 数据库同步检查，此文件可放到主服务器定时执行！建议每隔10分钟跑一次。
 * @author Howard
 */
class dbRsyncCheck{
	/**
	 * 主数据库资源
	 */
	private $dbM;
	/**
	 * 从数据库资源
	 */	
	private $dbS;
	/**
	 * 主服务器的登录信息
	 */
	private $master;
	/**
	 * 从服务器的登录信息
	 */
	private $slave;
	/**
	 * 初始化时调入主从数据库的账号、登录等信息
	 * @param array $dbInfo 数据库登录等所必须的资料，master是主数据库的，slave是从数据库的
	 */
	public function __construct(array $dbInfo){
		$this->master = $dbInfo['master'];
		$this->slave = $dbInfo['slave'];
		$this->logTextEnd = $this->master['host'].' to '.$this->slave['host'];
		//主库连接
		$this->dbM = new mysqli($this->master['host'], $this->master['user'], $this->master['passwd'], $this->master['dbname'], $this->master['port']);
		if ($this->dbM->connect_error) {
			die('db_m Connect Error: ' . $this->dbM->connect_error);
		}		
		$this->dbM->query('set names '.$this->dbM->character_set_name());
		//从库连接
		$this->dbS = new mysqli($this->slave['host'], $this->slave['user'], $this->slave['passwd'], $this->slave['dbname'], $this->slave['port']);
		if ($this->dbS->connect_error) {
			die('db_m Connect Error: ' . $this->dbS->connect_error);
		}
		$this->dbS->query('set names '.$this->dbS->character_set_name());
		
	}
	/**
	 * 取得主数据库的状态信息，返回对象
	 * @return 对象
	 */
	public function getMasterInfo(){
		$m = $this->dbM->query('show master status');
		$rows = $m->fetch_object();
		//echo $rows->File."<br>";
		//echo $rows->Position;
		//print_r($rows);
		//exit;
		return $rows;
	}
	/**
	 * 取得从数据库的同步状态信息，返回对象
	 * @return 对象
	 */
	public function getSlaveInfo(){
		$s = $this->dbS->query('show slave status');
		$rows = $s->fetch_object();
		//echo $rows->Slave_IO_Running;
		//echo $rows->Slave_SQL_Running;
		//echo $rows->Relay_Master_Log_File;
		return $rows;
	}
	
	/**
	 * 开始数据同步检查
	 * @return boolean 有问题返回问题信息数组，正常就返回假
	 */
	public function check(){
		$error = false;
		$msg = '';
		$mInfo = $this->getMasterInfo();
		$sInfo = $this->getSlaveInfo();
		switch (true){
			case $mInfo->File != $sInfo->Relay_Master_Log_File:
				$error = true;
				$msg = ('二制日志文件不一致，主：['.$mInfo->File.'] 从：['.$sInfo->Relay_Master_Log_File.']['.$sInfo->Master_Log_File.']');
			break;
			case strtoupper($sInfo->Slave_IO_Running)!='YES':
				$error = true;
				$msg = ('数据库同步已经停止IO运行！');
			break;
			case strtoupper($sInfo->Slave_SQL_Running)!='YES':
				$error = true;
				$msg = ('数据库同步已经停止SQL运行！');
			break;
			case $sInfo->Last_IO_Errno > 0:
				$error = true;
				$msg = ('数据库同步传输出错:'.$sInfo->Last_IO_Error);
			break;
			case $sInfo->Last_SQL_Errno > 0:
				$msg = ('数据库SQL语句出错:'.$sInfo->Last_SQL_Error);
			break;
			case $sInfo->Read_Master_Log_Pos != $mInfo->Position :
				$done_rate = round($sInfo->Read_Master_Log_Pos/$mInfo->Position, 4)*100;
				if($done_rate < 99.9){	//暂定完成率小于99.9%时就认为是数据没跟上
					$error = true;				
					$msg = ('数据流同步未跟上，Position：'.$mInfo->Position.'，Read_Master_Log_Pos：'.$sInfo->Read_Master_Log_Pos.'。完成率：'.$done_rate.'%');
				}
				if($done_rate < 99.5){	//小于99.5时就要重新启动从数据库的同步工作
					$this->slaveRestart();
					$error = true;
					$msg = ('同步比率'.$done_rate.'%太慢，重启同步操作！');
				}
			break;
			default:break;
		}
		
		if($error === true){
			$msg.=$this->logTextEnd;
			$this->writeLog($msg);
			return array('error'=>'1', 'error_msg'=>$msg);
		}
		return false;
	}
	/**
	 * 写错误日志记录，主要是写到主服务器的db_rsync_check_log数据库中
	 * @param string $logText 日志内容
	 */
	public function writeLog($logText = ''){
		$db = new mysqli($this->master['host'], $this->master['user'], $this->master['passwd'], 'db_rsync_check_log', $this->master['port']);
		$db->query('set names '.$db->character_set_name());
		$query = 'INSERT INTO `error_log` (`add_time` ,`text`) VALUES ("'.date('Y-m-d H:i:s').'", "'.$db->real_escape_string($logText).'");';
		$db->query($query);
		$db->close();
		//echo $logText.PHP_EOL;
	}
	/**
	 * 发送系统邮件给通知系统管理员
	 * @param string $to 电子邮箱地址
	 * @param string $subject 邮件主题
	 * @param string $message 邮件内容
	 */
	public function emailToAdmin($to, $subject='数据库同步异常', $message='数据库同步异常'){
		$date = date('Y-m-d H:i:s');
		$subject.= $date;
		$message.= "\n".$date;
		$message = wordwrap($message, 70);
		mail($to, $subject, $message);
	}
	/**
	 * 重新启动从数据库的同步操作
	 * 注意：只用于数据流同步未跟上时的情况
	 */
	private function slaveRestart(){
		$this->dbS->query('stop slave;');
		$this->dbS->query('slave start;');
		return 1;
	}
	
	public function __destruct(){
		$this->dbM->close();
		$this->dbS->close();
	}
}

/**
 * 一些功能集
 * @author Howard Administrator
 */
class sys{
	/**
	 * 取得命令行模式下的GET参数
	 * @param unknown_type $args
	 * @return multitype:boolean unknown Ambigous <>
	 */
	public static function getArgs($args) {
		$out = array();
		$last_arg = null;
		for($i = 1, $il = sizeof($args); $i < $il; $i++) {
			if( (bool)preg_match("/^--(.+)/", $args[$i], $match) ) {
				$parts = explode("=", $match[1]);
				$key = preg_replace("/[^a-z0-9]+/", "", $parts[0]);
				if(isset($parts[1])) {
					$out[$key] = $parts[1];
				}
				else {
					$out[$key] = true;
				}
				$last_arg = $key;
			}
			else if( (bool)preg_match("/^-([a-zA-Z0-9]+)/", $args[$i], $match) ) {
				for( $j = 0, $jl = strlen($match[1]); $j < $jl; $j++ ) {
					$key = $match[1]{$j};
					$out[$key] = true;
				}
				$last_arg = $key;
			}
			else if($last_arg !== null) {
				$out[$last_arg] = $args[$i];
			}
		}
		return $out;
	}
}


/**
 * 逻辑判断
 * @author Howard Administrator
 */
class start{
	public function __construct($_SERVER){
		$args = sys::getArgs($_SERVER['argv']);
		
		if(isset($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR']){
			$masterHost = $_SERVER['SERVER_ADDR'];
		}elseif($args['host']){
			$masterHost = $args['host']; 
		}else{
			die('无主机参数！');
		}
		
		$ip0 = '120.136.45.200';
		$ip1 = '113.106.94.150';
		if($masterHost==$ip0){
			$slaveHost = $ip1;
		}elseif($masterHost==$ip1){
			$slaveHost = $ip0;
		}else{
			die('无效IP！');
		}
		
		$db = array('master'=>array('host'=>$masterHost, 'user'=>'zhhrsync2013', 'passwd'=>'2013rsynczhh2099', 'dbname'=>'usitrip_com', 'port'=>'3306'), 
					'slave' =>array('host'=>$slaveHost, 'user'=>'zhhrsync2013', 'passwd'=>'2013rsynczhh2099', 'dbname'=>'usitrip_com', 'port'=>'3306'));
		$c = new dbRsyncCheck($db);
		
		$errors = $c->check();
		if($errors === false){
			$c->writeLog('正常');	
		}else{
			$c->emailToAdmin('Howard Zhou <2355652776@qq.com>', '数据库同步异常', '数据库同步异常。快去'.$masterHost.'数据库db_rsync_check_log查看日志！错误信息如下：'."\n".$errors['error_msg']);
		}
	}
}
new start($_SERVER);

?>