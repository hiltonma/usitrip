<?php
/**
 * 防火墙类
 * @author Howard
 */
class ipTables{
	private $WhiteList = '24.176.192.22, 65.60.88.74, 207.145.170.146, 76.89.193.251, 183.63.48.194, 113.106.94.150, 120.136.45.200, 173.255.216.188';	//白名单ip, 
	private $mysqli;
	private $table;
	public function __construct($host, $user, $passwd, $database, $port = 3306){
		$this->mysqli = new mysqli($host, $user, $passwd, $database, $port);
		if ($this->mysqli->connect_error) {
			die('Connect Error: ' . $this->mysqli->connect_error);
		}
		$this->table = 'ip_blacklist';
	}
	/**
	 * 取得列表
	 * @param string $where 条件
	 */
	public function getList($where = ''){
		$data = array();
		$_where = ' 1 ';
		if($where){
			$_where .= ' and '.$where;
		}
		$query = 'SELECT * FROM '.$this->table.' WHERE '.$_where.' ORDER BY ip ASC';
		//echo $query;exit; 
		$r = $this->mysqli->query($query);
		if($r && $r->num_rows > 0){
			while ($rows = $r->fetch_array(MYSQLI_ASSOC)){
			//while ($rows = $r->fetch_row()){
				$data[] = $rows; 
			}
		}
		return $data;
	}
	/**
	 * 添加一条ip
	 * @param string $ip ip地址
	 * @param int $is_disable 是否封锁
	 */
	public function add($ip, $is_disable = 1){
		//如果ip在白名单中则不能封
		$wips = explode(',', str_replace(' ', '', $this->WhiteList));
		if(in_array($ip, $wips) && $is_disable == '1'){
			die($ip.' is White List! You can\'t do it.');
		}
		$query = 'replace into '.$this->table.' set ip="'.$ip.'", is_disable="'.(int)$is_disable.'", md5_val="'.time().'" ';
		return $this->mysqli->query($query);
	}
	/**
	 * 更新一条记录
	 * @param string $ip ip地址
	 * @param int $is_disable 是否封锁
	 */
	public function update($ip, $is_disable = 1, $md5val = ''){
		$query = 'update '.$this->table.' set is_disable="'.(int)$is_disable.'", md5_val="'.$md5val.'" where ip="'.$ip.'" ';
		return $this->mysqli->query($query);
	}
	/**
	 * 删除一条（没解锁的不能删）
	 * @param string $ip
	 */
	public function delete($ip){
		$query = 'DELETE FROM '.$this->table.' WHERE ip="'.$ip.'" and is_disable="0" ';
		return $this->mysqli->query($query);
	}
	/**
	 * 执行系统指令
	 * @param string $command 指令行内容
	 */
	private function shell($command){
		system($command, $status);
		//exec($command, $status);
		if($status == 'true'){
			echo "OK\n";
			return true;
		}else{
			echo "Failure\n";
			return false;
		}
	}
	/**
	 * 检查ip是否合法
	 */
	private function checkIp($ip){
		if(!preg_match('/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/', $ip)){
			die('IP error!'.$ip);
			return false;
		}
		return true;
	}
	/**
	 * 封锁某个IP
	 * @param string $ip 201.112.123.211
	 */
	public function drop_shell($ip){
		$this->checkIp($ip);
		//如果ip在白名单中则不能封
		$wips = explode(',', str_replace(' ', '', $this->WhiteList));
		if(in_array($ip, $wips)){
			die($ip.' is White List!');
		}
		//iptables -I INPUT -s 192.168.1.88 -j DROP
		//iptables -I INPUT -s 157.0.0.0/8 -j DROP
		//iptables -I INPUT -s 157.55.0.0/16 -j DROP
		//iptables -I INPUT -s 157.55.33.0/24 -j DROP
		$drop_com = 'iptables -I INPUT -s %s -j DROP';	//-I是插入
		$_com = sprintf($drop_com, $ip);
		return $this->shell($_com);
	}
	/**
	 * 解除封锁某个IP
	 * @param string $ip 201.112.123.211
	 */
	public function undrop_shell($ip){
		$this->checkIp($ip);
		//iptables -D INPUT -s 113.118.195.151 -j DROP
		$com = 'iptables -D INPUT -s %s -j DROP';	//-D是删除
		$com = sprintf($com, $ip);
		return $this->shell($com);
	}
	/**
	 * 根据数据库资料来封锁IP
	 */
	public function drop_ip(){
		$data = $this->getList('is_disable="1" ');
		$run = '';
		if($data){
			foreach($data as $rows){
				if($rows['md5_val'] == md5($rows['ip'].$rows['is_disable'])) continue;
				if($this->drop_shell($rows['ip']) == true){
					$this->update($rows['ip'], 1, md5($rows['ip'].'1'));
					$run.= "Lock ".$rows['ip']."\n";
					$this->del_whos_online($rows['ip']);
				}
			}
		}
		return $run;
	}
	/**
	 * 根据数据库资料来解锁IP
	 */
	public function undrop_ip(){
		$data = $this->getList('is_disable="0" ');
		$run = '';
		if($data){
			foreach($data as $rows){
				if($rows['md5_val'] == md5($rows['ip'].$rows['is_disable'])) continue;
				if($this->undrop_shell($rows['ip']) == true){
					$this->update($rows['ip'], 0, md5($rows['ip'].'0'));
					$run.= "UnLock ".$rows['ip']."\n";
				}
			}
		}
		return $run;
	}
	/**
	 * 删除某个ip的在线信息
	 * @param string $ip
	 */
	public function del_whos_online($ip){
		return true;//下表已经没资料，不用删除了！
		$sql = 'DELETE FROM `whos_online` WHERE ip_address = "'.$ip.'" ';
		return $this->mysqli->query($sql);
	}
}
//$abc = '/root/abcd.txt';
//$text = file_get_contents($abc);
//echo $text;
//$ip = new ipTables('localhost', 'usitrip2013', '&92319*87HRz.com59', 'usitrip_com');
//$ip = new ipTables('localhost', 'root', '7778906', 'usitrip_com');

//$add = $ip->add('192.168.1.9');
//
//$list = $ip->getList();
//print_r($list);

//echo $ip->drop_ip();
//sleep(20);
//echo $ip->undrop_ip();
?>