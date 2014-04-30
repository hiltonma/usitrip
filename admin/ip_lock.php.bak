<?php
header("Content-type: text/html; charset=gb2312");
/**
 * ip黑名单列表
 */
class ip_lock {
	public $o;

	public function __construct($action) {
		require_once 'includes/configure.php';
		require_once 'includes/classes/ipTables.php';
		$this->o = new ipTables(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE);
		$this->action($action);
	}
	/**
	 * 动作
	 * @param $aciton
	 */
	public function action($aciton){
		switch ($aciton){
			case 'addLock':	//添加新IP
				$ip = addslashes($_GET['ip']);
				if($this->o->add($ip, 1)){
					echo 'OK';
					exit;
				}
				break;
			case 'lock':	//封
				$ip = addslashes($_GET['ip']);
				if($this->o->update($ip, 1, md5($ip.time()))){
					echo 'OK';
					exit;
				}
			break;
			case 'unlock':	//解封
				$ip = addslashes($_GET['ip']);
				if($this->o->update($ip, 0, md5($ip.time()))){
					echo 'OK';
					exit;
				}
			break;
		}
	}
	public function getList() {
		return $this->o->getList();
	}
}

$action = ($_POST['action'] ? $_POST['action'] : ($_GET['action'] ? $_GET['action'] : ''));
$obj = new ip_lock($action);
$list = $obj->getList();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>IP黑名单列表</title>
<style>
y{color:#F00;}
n{color:#090;}
table tr th {background-color:#C9C9C9;}
table tr td {background-color:#ddd;}
</style>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script type="text/javascript">
function lock_ip(ip){
	var url = 'ip_lock.php';
	$.get(url,{"action":'lock', "ip":ip}, function(text){
		if(text=='OK'){
			alert('封锁成功！1分钟后生效。');
			window.location.reload();
		}
	}, 'text');
}

function unlock_ip(ip){
	var url = 'ip_lock.php';
	$.get(url,{"action":'unlock', "ip":ip}, function(text){
		if(text=='OK'){
			alert('解封成功！1分钟后生效。');
			window.location.reload();
		}
	}, 'text');
}
</script>
</head>
<body>
	<div>
		<h1>IP黑名单列表</h1>
	</div>
	<div>
		<fieldset>
			<legend align="left"> 搜索区 </legend>
			<form method="get" action="" name="form_search">
				<ul>
					<li>暂无开发</li>
				</ul>
			</form>
		</fieldset>
	</div>
	<div>
	<fieldset>
		<legend align="left"> 列表区 </legend>
		<table>
			<tr>
				<th>IP</th>
				<th>是否被封</th>
				<th>md5码</th>
				<th>动作</th>
			</tr>
			<?php foreach ((array)$list as $r){?>
			<tr>
				<td><?= $r['ip']?></td>
				<td><?= ($r['is_disable']=='1' ? '<y>是</y>' : '<n>否</n>');?></td>
				<td><?= $r['md5_val'];?></td>
				<td>
				<?php if($r['is_disable']=='0'){?>
					<button type="button" onclick="lock_ip('<?= $r['ip']?>')">封IP</button>
				<?php }else{?>
					<button type="button" onclick="unlock_ip('<?= $r['ip']?>')">解封</button>
				<?php }?>
				</td>
			</tr>
			<?php }?>
		</table>
	</fieldset>
	</div>
</body>
</html>