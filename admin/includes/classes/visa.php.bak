<?php
/*
  $Id: visa.php,v 1.0.0.0 2012-04-06 15:16 aben Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class visa {
    var $info;
/*
ORD_ADM_STA_TAG
 
ORD_ADM_CON  6  确认成功
ORD_ADM_DEL  6  取消的订单
ORD_ADM_NONE  6  未处理
ORD_ADM_OK  6  可以操作
ORD_ADM_WAIT  6  等待确认
 
ORD_USR_STA_TAG
ORD_USR_DEL  5  用户取消
ORD_USR_NEW  5  新订单
ORD_USR_OK  5  用户确认
 
ORD_PAY_STA_TAG
ORD_PAY_OK  8  付款完成
ORD_PAY_OVER  8  超出
ORD_PAY_PART  2  部分支付
ORD_PAY_WAIT  2  等待付款
*/
/*
付款方式:
CAPINFO 首信易
alipay 支付宝
unionpay 银联在线
PAY_AGENT 代理支付
*/

/*路嘉那边的付款记录的栏位
ORD_PAY_ID 支付编号   
   ORD_ID 订单编号 
   BAS_TAG 标记(支付方式)   
   SUP_ID 供应商编号（走四方是2）   
  ORD_PAY_MONEY 支付  如果为0是未支付，如果和订单金额一致是支付完成
   ORD_PAY_OK 支付是否完成   （Y/N）
   ORD_PAY_DATE 开始日期
   ORD_PAY_OK_DATE 结束日期
   ORD_PAY_MEMO 备注
   ADM_ID 操作员
   ORD_PAY_PARA1 参数1
   ORD_PAY_PARA2 参数2
   ORD_PAY_PARA3 参数3
   ORD_PAY_PARA4 参数4
   ORD_PAY_PARA5 参数5
   ORD_PAY_PARA6 参数6
   ORD_PAY_PARA7 参数7
   ORD_PAY_PARA8 参数8
   ORD_PAY_PARA9 参数9
   ORD_PAY_PARA10 参数10
   ORD_OL_XML 网关支付XML
   ORD_OL_OID 网关支付形成的订单号
   ORD_OL_PROVIDER 在线支付供应商
*/


    /**
	 *后台: 签证 visa构造函数
	 *
	 * @param unknown_type $arr
	 */
  	function __construct($arr = array()){
		global $customer_id, $messageStack;

		if(is_array($arr) && count($arr)>0){
			foreach($arr as $key => $value){
				$this->$key = $value;
			}
		}
		$this->customer_id = $customer_id;

		/*
		//visa系统使用的域名
		$this->visa_domain = '';
		//安全IP列表
		$this->visa_safeIP = '';
		if(defined('IS_LIVE_SITES') && IS_LIVE_SITES === true)
		{
			$this->visa_domain = 'http://visa.usitrip.com';
			$this->visa_safeIP = '173.201.36.168,173.201.36.169,173.201.36.69,113.106.94.149,112.95.244.149,127.0.0.1,192.168.1.15';
		}else {
			$this->visa_domain = 'http://tech.samford.com.cn';
			$this->visa_safeIP = '173.201.36.168,173.201.36.169,173.201.36.69,113.106.94.149,112.95.244.149,127.0.0.1,192.168.1.15';
		}

		//visa系统之用户验证地址
		$this->visa_lujia_userCheckUrl = $this->visa_domain.'/OneWorld_Web/agents/valid.jsp?AG=usitrip&V=';

		//客人自己下单的地址
		$this->visa_UserOrder_url = $this->visa_domain.'/OneWorld_Web/agents/visa/visa_usa.jsp?AG=usitrip&USR_UNID=';

		//查看客人订单列表的地址
		$this->visa_UserOrderList_url = $this->visa_domain.'/OneWorld_Web/agents/visa/visa_order.jsp?AG=usitrip&USR_UNID=';

		//客服从后台直接下单的地址,会返回{"OID":"236","RST":true}
		$this->visa_adminOrder_url = $this->visa_domain.'/OneWorld_Web/agents/visa/order.jsp?AG=usitrip&USR_UNID=';
		*/
  	}
    /*if (!tep_session_is_registered(\'customer_id\')) { echo "未登录"; }*/

  	/**
  	 * 返回用户信息
  	 * @param int user_id 用户ID
  	 * @return array array{int customers_id,string customers_name,string customers_telephone,string visa_id}
  	 */	 
  	function get_user_info($user_id)
  	{
  		$data = false;
  		$sql = 'SELECT customers_id,CONCAT(customers_lastname,customers_firstname) AS customers_name,customers_telephone,visa_id,customers_email_address FROM customers';
  		$sql_query = tep_db_query($sql);
  		while($rows = tep_db_fetch_array($sql_query))
  		{
  			$data = $rows;
  		}
  		if(is_array($data))
  		{
  			return $data;
  		}
  		else
  		{
  			return false;
  		}
  	}

  	/**
  	 * 取得visa系统返回的用户认证信息(json,转换为数组再返回)
  	 * @param int user_id 用户ID
  	 * @return array visa信息 array{int USR_ID,string USL_VISA_ORDER,string URL_VISA_ORDER_LIST,stringUSR_UNID,boolean RST}
  	 */
  	function get_visa_info($user_id)
  	{
  		$rt = false;
  		$data = $this->get_user_info($user_id);
  		//print_r($data);
  		$user_name = $data['customers_name'];
  		$user_tel = $data['customers_telephone'];
  		$url = VISA_DOMAIN . VISA_LUJIA_USER_CHECK_URL . '{%22UID%22:'.$user_id.',%20%22UNAME%22:%22'.urlencode($user_name).'%22,%20%22UTELE%22:%22'.urlencode($user_tel).'%22,%20%22UEMAIL%22:%22'.$data['customers_email_address'].'%22}';
		//echo '$url:'.$url.'<br/>'; exit();
  		/*
  		 * 调用接口后,返回的是json格式:
  		 * {"USR_ID":1106,"URL_VISA_ORDER":"/OneWorld_Web/agents/visa/visa_usa.jsp?AG=usitrip&USR_UNID=b1e63671-8beb-4253-88e9-53854c3ed7b4",
  		 * "URL_VISA_ORDER_LIST":"/OneWorld_Web/agents/visa/visa_order.jsp?AG=usitrip&USR_UNID=b1e63671-8beb-4253-88e9-53854c3ed7b4",
  		 * "USR_UNID":"b1e63671-8beb-4253-88e9-53854c3ed7b4",
  		 * "RST":true}
  		 * */
  		$visa_info_json = file_get_contents($url);
  	  	$visa_info_arr = json_decode($visa_info_json,true);
  		if(is_array($visa_info_arr))
  		{
  			if($visa_info_arr['RST'] = true)
  			{
				$sql = 'UPDATE customers SET visa_id='.$visa_info_arr['USR_ID'] .' WHERE (customers_id='.$user_id.') AND (visa_id IS NULL)';
				$sql_query = tep_db_query($sql);
  				$rt = $visa_info_arr;
  			}
  		}

  		return $rt;
  	}
	
  	/**
  	*通过VISA订单号查询最后的付款时间
  	*@param string $ORD_ID签证订单号
  	*@return string 付款时间
  	*/	
	function get_visa_order_pay_date($ORD_ID)
	{
		$rt = '';
		$sql = 'SELECT `ORD_PAY_OK_DATE` FROM `visa_order_pay_history` WHERE ORD_ID='.$ORD_ID.' AND `ORD_PAY_OK`=\'Y\' ORDER BY visa_order_pay_history_id DESC LIMIT 1';
		$sql_query = tep_db_query($sql);
		while($rows = tep_db_fetch_array($sql_query))
		{
			$rt = $rows['ORD_PAY_OK_DATE'];
		}
		return $rt;
	}	
	
	
  	/**
  	*通过VISA订单号获取VISA(到大使馆)的状态名称
  	*@param string $ORD_ID签证订单号
  	*@return string 签证(到大使馆)的状态
  	*/	
	function get_visa_to_embassy_status($ORD_ID)
	{
		$rt = '';
		$sql = 'SELECT VIS_STATUS FROM visa_to_embassy_info WHERE FRO_ORD_ID='.$ORD_ID.' ORDER BY visa_to_embassy_info_id DESC LIMIT 1';
		$sql_query = tep_db_query($sql);
		while($rows = tep_db_fetch_array($sql_query))
		{
			$rt = $rows['VIS_STATUS'];
		}
		return $rt;
	}
	
	/*
	*VISA状态列表
	*@return array
	*/	
	public function show_VIS_STATUS()
	{
		$data=false;
		$data[]=array('id'=>'','text'=>'-----');
		$data[]=array('id'=>'NEW','text'=>'填写中');
		$data[]=array('id'=>'OK','text'=>'填写完毕');
		$data[]=array('id'=>'REG_WAIT','text'=>'等待注册');
		$data[]=array('id'=>'REG_START','text'=>'注册开始');
		$data[]=array('id'=>'REG_OK','text'=>'注册成功');
		$data[]=array('id'=>'REG_ERR','text'=>'注册失败');
		return $data;
	}	
	/*
	*通过VISA(到大使馆)的状态值返回中文名称
	*@para string $VIS_STATUS: VISA(到大使馆)的状态值
	@return string 状态的中文
	*/
	function match_visa_to_embassy_status_name($VIS_STATUS)
	{
		$rt = '';
		$data = $this->show_VIS_STATUS();
		$n=count($data);
		for($i=0;$i<$n;$i++){
			if($data[$i]['id']==$VIS_STATUS){
				return $data[$i]['text'];
			}
		}
		return $rt;
	}
	
	/*
	*订单状态列表
	*@return array
	*/
	public function show_ORD_PAY_TAG()
	{
		$data=false;
		$data[]=array('id'=>'','text'=>'----');
		$data[]=array('id'=>'PAY_AGENT','text'=>'走四方');
		$data[]=array('id'=>'lujia_all','text'=>'签证系统');
		$data[]=array('id'=>'CAPINFO','text'=>'签证系统(首信易)');
		$data[]=array('id'=>'unionpay','text'=>'签证系统(银联)');
		$data[]=array('id'=>'alipay','text'=>'签证系统(支付宝)');
		return $data;
	}
	/*
	*通过订单状态值,返回订单状态名称
	*@return string
	*/
	function match_visa_ORD_PAY_TAG($ORD_PAY_TAG)
	{	
		$rt = '';
		$data = $this->show_ORD_PAY_TAG();
		$n=count($data);
		for($i=0;$i<$n;$i++){
			if($data[$i]['id']==$ORD_PAY_TAG){
				return $data[$i]['text'];
			}
		}
		return $rt;
	}
	/*
	*VISA订单状态列表
	*@return array
	*/
	public function show_ORD_ADM_STA_TAG()
	{
		$data=false;
		$data[]=array('id'=>'','text'=>'----');
		$data[]=array('id'=>'ORD_ADM_CON','text'=>'确认成功');
		$data[]=array('id'=>'ORD_ADM_DEL','text'=>'取消的订单');
		$data[]=array('id'=>'ORD_ADM_NONE','text'=>'未处理');
		$data[]=array('id'=>'ORD_ADM_OK','text'=>'可以操作');
		$data[]=array('id'=>'ORD_ADM_WAIT','text'=>'等待确认');
		return $data;
	}
	/*
	*通过VISA订单的状态值(ORD_ADM_STA_TAG)返回中文名称
	*@para string $ORD_ADM_STA_TAG: VISA order的状态值
	@return string VISA状态的中文
	*/
	function match_visa_order_status_name($ORD_ADM_STA_TAG)
	{	
		$rt = '';
		$data = $this->show_ORD_ADM_STA_TAG();
		$n=count($data);
		for($i=0;$i<$n;$i++){
			if($data[$i]['id']==$ORD_ADM_STA_TAG){
				return $data[$i]['text'];
			}
		}
		return $rt;
	}	
	
	
  	/**
  	*获取后台admin通过VISA的订单号去查看客户visa订单列表的URL
  	*@param string $ORD_ID签证订单号
  	*@return array 返回get_visa_info获取到的结果
  	*/	
	function admin_goto_view_customer_visa_order($ORD_ID)
	{
		$rt = false;
		$ORD_ID = (int)$ORD_ID;

		$sql = 'SELECT customers_id FROM customers WHERE visa_id IN (SELECT DISTINCT USR_ID FROM visa_order_ordermain_from_lujia WHERE ORD_ID='.$ORD_ID.' )';
		$sql_query = tep_db_query($sql);
		$customer = tep_db_fetch_array($sql_query);
		
		$customer_id = (int)$customer['customers_id'];
		//echo '$customer_id: '.$customer_id;
		if($customer_id>0)
		{
			$rt = $this->get_visa_info($customer_id);
		}
		return $rt;		
	}
	
  	/**
  	*获取签证产品列表
  	*@param string $visa_srv_unid
  	*@return array 签证列表
  	*/
  	function get_visa_product_list()
  	{
  		$data = false;
  		$sql = 'SELECT * FROM `visa_products_list`';
  		$sql_query = tep_db_query($sql);
  		while($rows = tep_db_fetch_array($sql_query))
  		{
  			$data[] = $rows;
  		}
  		return $data;
  	}

  	/**
  	 * 通过订单号获取后台下的visa订单列表
  	 * @param int $orders_id 订单号
  	 * @return array
  	 */
  	function get_visa_order_list($orders_id)
  	{
  		$data = false;
  		//$sql = 'SELECT a.*,b.admin_job_number FROM(SELECT `visa_orderid`, `vis_tag_name`, `orders_info`, `add_date`, `login_id`, `is_deleted` FROM `visa_orders_byadmin` WHERE (orders_id = \''.$orders_id.'\') AND (is_deleted=\'0\') ) AS a LEFT JOIN admin AS b ON a.login_id=b.admin_id';
  		$sql = "SELECT a.`visa_orderid`, a.`vis_tag_name`, a.`orders_info`, a.`add_date`, a.`login_id`, a.`is_deleted`,b.admin_job_number FROM `visa_orders_byadmin` AS a LEFT JOIN admin AS b ON a.login_id=b.admin_id where a.orders_id = 50313 AND a.is_deleted=0";
  		$sql .= '';
  		$sql_query = tep_db_query($sql);
  		while($rows = tep_db_fetch_array($sql_query))
  		{
  			$data[] = $rows;
  		}
  		if(is_array($data))
  		{
  			return $data;
  		}
  		else
		{
			return false;
		}
  	}

	/**
	*通过订单号,初始化下visa订单的资料
	*@param int orders_id订单号
	*@return array array(客户1|客户2,客户数量,出发时间,用户id)
	*/
	function prepare_info_for_visa($orders_id)
	{
		$data = false;

		//guest_name的格式是(不包括双引号): "姓1名1 [英文姓名1]<:婴儿生日:>姓2名2 [英文姓名2]<::>姓2名3 [英文姓名3]<::>"

		$sql = 'SELECT a.id,a.guest_name,b.products_departure_date FROM(';
		$sql .= 'SELECT 1 AS id,guest_name FROM orders_product_eticket WHERE orders_id='.$orders_id;
		$sql .= ') AS a INNER JOIN(';
		$sql .= 'SELECT 1 AS id, MIN(products_departure_date) AS products_departure_date FROM orders_products WHERE orders_id='.$orders_id;
		$sql .= ') AS b ON a.id=b.id';
		//获取符合条件的用户id等信息
		$sql1 = 'SELECT 1 AS id,a1.customers_id,a1.orders_paid,b1.value FROM orders AS a1, orders_total AS b1 ';
		$sql1 .= 'WHERE (a1.orders_id='.$orders_id.') AND (b1.orders_id='.$orders_id.') AND (b1.class=\'ot_total\') AND (a1.orders_paid>=b1.value)';

		$sql = 'SELECT a2.customers_id,/*b2.guest_name,*/b2.products_departure_date AS tdate FROM ('.$sql1.') AS a2 INNER JOIN ('.$sql.') AS b2 ON a2.id=b2.id';
		$sql_query = tep_db_query($sql);
		while($rows = tep_db_fetch_array($sql_query))
		{
			$data = $rows;
		}
		if(is_array($data))
		{			
			$sql2 = 'SELECT a.guest_name,b.products_model,c.products_name FROM orders_product_eticket AS a, products AS b,products_description AS c WHERE a.orders_id='.$orders_id.' AND a.products_id=b.products_id AND a.products_id=c.products_id';
			//echo $sql2.'<hr/>';
			$sql_query2 = tep_db_query($sql2);
			while($rows2= tep_db_fetch_array($sql_query2))
			{
				$data['products'][]= $rows2;
			}
			//print_r($data);
			
			return $data;
		}
		else
		{
			return false;
		}
	}
	
	/**将从orders_products_eticket中读出来的guest_name变成数组
	 * @param string $string
	 * @return array
	 */	
	function order_guest_name_fromstring_toarray($string)
	{
		//guest_name格式(不包括双引号): "姓名1[英文名1]<::>姓名2[英文名2]<::>"
		
		$s1=preg_split("/[<::>]+/", $string);
		
		$data = false;

		foreach($s1 AS $key=>$value)
		{
			$data[] = preg_split("/[\[\]]/",$value) ;
		}
		return $data;
	}

	/**后台下单,并返回订单号
	 * @param array $post
	 * @param int $login_id登录人ID
	 * @return int VISA的订单号
	 */
	 function visa_order_admin($post,$login_id)
	 {
	 	$error = false;
	 	$rt = 0;
		$tmp = split(',',$post['vis_tag_name']);
	 	$orders_id = (int)($post['orders_id']);
	 	$customers_id = (int)($post['customers_id']);
	 	$vis_tag_name = $tmp[0];
		$vis_srv_unid = $tmp[1];
	 	$vis_to_date = $post['vis_to_date'];
	 	$vis_req_date = $post['vis_req_date'];
	 	$tdate = $post['tdate'];
		
		$guest_name = '';
		$guest_count = 0;
		if(is_array($_POST['guest_name']))
		{
			foreach($_POST['guest_name'] AS $key=>$value)
			{
				$guest_name = $guest_name.$value.'|';
				$guest_count++;
			}
		}
		else
		{
			$error=true;
			echo 'please select guest name';
			return 0;
			//exit();
		}
		$guest_name = substr($guest_name,0,strlen($guest_name)-1);		


		//echo strtotime($vis_to_date) .','. !strtotime($vis_req_date) .','. !strtotime($tdate); exit();
		//echo $vis_to_date.','.$vis_req_date.','.$tdate; exit();
		//echo $orders_id . ','.$customers_id; exit();

	 	if($orders_id == 0 || $customers_id ==0 || $guest_count==0 || !strtotime($vis_to_date)  || !strtotime($vis_req_date)|| !strtotime($tdate))
	 	{
	 		$error=true;
			//$messageStack->add('请确保 订单号,客人id,出发日期,预计赴美日期,期望签证日期','error');
			echo '请确保 订单号,客人id,出发日期,预计赴美日期,期望签证日期';
			return 0;
	 	}

	 	//对VIS_TAG_NAME进行编码
	 	$vis_tag_name2 = urlencode(iconv('gb2312','utf-8',urldecode($vis_tag_name)));

	 	//对客人姓名进行编码
	 	$arr = false;
	 	$guest_name2 = '';
	 	$arr = explode('|',$guest_name);
	 	$n = count($arr);
	 	for($i = 0; $i<$n; $i++)
	 	{
	 		$guest_name2 .= urlencode(iconv('gb2312','utf-8',urldecode($arr[$i])));
	 		if($i<($n-1)) $guest_name2 .='|';
	 	}

		//remote get visa user information
	 	$visa_info = $this->get_visa_info($customers_id);
	 	if(is_array($visa_info))
	 	{
	 		$visa_user_id = $visa_info['USR_ID'];
	 		$visa_cert_code = $visa_info['USR_UNID'];
	 	}
	 	else
	 	{
	 		$error=true;
			//$messageStack->add('Error: get visa info error.','error');
			return 0;
	 	}

	 	//structure request string of orderInfo
	 	$orderInfo='orderInfo={orderID:"'.$orders_id.'",guestName:"'.$guest_name2.'",tdate:"'.$tdate.'",guestCount:"'.$guest_count.'",orderUserId:'.$customers_id.',VIS_TO_DATE:"'.$vis_to_date.'",VIS_REQ_DATE:"'.$vis_req_date.'"}';

	 	$url = VISA_DOMAIN . VISA_ADMIN_ORDER_URL . $visa_cert_code . '&VIS_TAG_NAME=' .$vis_tag_name2 .'&'. $orderInfo.'&SRV_UNID='.$vis_srv_unid;
		//echo $url;exit();

		//remote check out order and get return info
	 	$visa_info_json = file_get_contents($url);
	 	$visa_info_arr = json_decode($visa_info_json,true);

		//print_r($visa_info_arr);

  		if(is_array($visa_info_arr))
  		{
  			if($visa_info_arr['RST'] == true)
  			{
  				$visa_order_id = $visa_info_arr['OID'];
  				//echo '<hr/>'.VISA_DOMAIN .$visa_info['URL_VISA_ORDER_LIST'];
				$sql = 'INSERT INTO `visa_orders_byadmin`(`orders_id`,`vis_tag_name`,`orders_info`,`visa_orderid`,`add_date`,`login_id`,`is_deleted`)';
				$sql .= 'VALUES('.$orders_id.',\''.$vis_tag_name.'\',\''.$orderInfo.'\','.$visa_order_id.',\''.date('Y-m-d H:i:s').'\','.$login_id.',0)';
				$sql_query = tep_db_query($sql);
  				$rt = $visa_order_id ;
  				//echo '<br/>'.$rt;
  			}
  			else
  			{
  				echo 'Error:<br/>'.$url.'<br/>';
  				print_r($visa_info_arr);
  				$error = true;
  			}
  		}
  		else
  		{
  			$error = true;
			//$messageStack->add('Error: get visa info error.','error');
  		}

	 	return $rt;
	 }
	 
	 function iconv_array_charencoding($from,$to,$array) {
	 	$rt = array();
	 	if (is_array($array)) {
			foreach($array AS $key=>$value) {
				if(is_array($value)) {
					if(count($value)>0) {
						$rt[$key] = $this->iconv_array_charencoding($from,$to,$value);
					} else {
						$rt[$key] = '';
					}
				} else {
					$rt[$key] = iconv($from,$to,$value);
				}
			}
		}
		return $rt;
	 }
	 
	 
	 /*
	 *从路嘉获取VISA订单列表并更新到数据库
	 *@return array: array(true成功/false失败,更新数量,错误信息)
	 */
	function visa_update_order_list_fromlujia()
	{
		$returns = Array('result'=>false,'inserted_count'=>0,'error_msg'=>'');
		
		//$xml = simplexml_load_file("http://www.888trip2.com/admin/VISA_ORDER_LIST.xml");
		$url = VISA_DOMAIN.VISA_ALL_ORDER_LIST_URL_LUJIA; 
		$xml = file_get_contents($url);		
		$arr = xml2array('',1,'tag',$xml);

		//print_r($arr); //exit();

		if(is_array($arr))
		{
			$datetime = date('Y-m-d H:i:s');
			$timestring = str_replace(' ','',str_replace(':','',str_replace('-','',$datetime)));
			//$timestring = date_format($datetime,'Ymd His');
			$data = array('update_time_string'=>$timestring,'add_date'=>$datetime);
			
			$inserted_id = 0;
			$inserted_id = tep_db_fast_insert('visa_order_updatetimestring',$data);
			$inserted_count = 0;
			if ($inserted_id > 0)
			{
				$data2 = $arr['root']['OrderMain']['Row'];
				for($i=0, $n = count($data2); $i<$n; $i++)
				{
					$data21 = $this->iconv_array_charencoding('utf-8','gb2312',$data2[$i]);
					$data21['ORD_DATE'] = date("Y-m-d",strtotime($data21['ORD_DATE']));	
					$data21['ORD_CDATE'] = date("Y-m-d H:i:s",strtotime($data21['ORD_CDATE']));	
					if(!empty($data21['ORD_MDATE'])){
						$data21['ORD_MDATE'] = date("Y-m-d H:i:s",strtotime($data21['ORD_MDATE']));	
					}
				
					$data21['update_time_string'] = $timestring;
					//print_r($data21); exit();
					tep_db_fast_insert('visa_order_ordermain_from_lujia',$data21);
					$inserted_count ++;
				}
				$returns['result']=true;
				$returns['inserted_count']=$inserted_count;
				
				$data3 = $arr['root']['OrderMainList']['Row'];
				//print_r($data3); exit();
				for($i=0, $n = count($data3); $i<$n; $i++)
				{
					$data31 = array();
					$data31 = $this->iconv_array_charencoding('utf-8','gb2312',tep_db_prepare_input($data3[$i]));
					$data31['update_time_string'] = $timestring;
					$data31['is_batch_added'] = '1';
					//print_r($data31);
					tep_db_fast_insert('visa_order_ordermainlist_from_lujia',$data31);
				}
				
				$data4 = $arr['root']['OrderPay']['Row'];//OrderPay
				//print_r($data4); //exit();
				for($i=0, $n = count($data4); $i<$n; $i++)
				{
					$data41 = array();
					$data41 = $this->iconv_array_charencoding('utf-8','gb2312',tep_db_prepare_input($data4[$i]));
					if(!empty($data41['ORD_PAY_DATE'])){ $data41['ORD_PAY_DATE'] = date("Y-m-d H:i:s",strtotime($data41['ORD_PAY_DATE'])); }
					if(!empty($data41['ORD_PAY_OK_DATE'])){ $data41['ORD_PAY_OK_DATE'] = date("Y-m-d H:i:s",strtotime($data41['ORD_PAY_OK_DATE'])); }
					$data41['update_time_string'] = $timestring;
					$data41['is_batch_added'] = '1';
					tep_db_fast_insert('visa_order_pay_history',$data41);
				}				
				
				
			}
			else
			{
				$returns['result']=false;
				$returns['error_msg']='插入时间点数据错误,可能是有其他人员在同时更新数据(同一秒种只允许一个更新的进程)';
			}
		}
		else{
			$returns['result']=false;
			$returns['error_msg']='路嘉主动传递订单到走四方数据库失败';
		}
		return $returns;
	}
	
	/*
	*通过VISA订单的USR_ID查询相关订单及用户信息
	*@return array
	*/
	function get_order_user_info_by_visa_order_id($visa_order_id,$visa_user_id)
	{
		$data = array('orders_id'=>'','customers_email_address'=>'','customers_name'=>'');
		$sql = 'SELECT orders_id FROM visa_orders_byadmin WHERE visa_orderid='.$visa_order_id;
		$sql_query = tep_db_query($sql);
		while($rows = tep_db_fetch_array($sql_query))
		{
			$data['orders_id'] = $rows['orders_id'];
		}
		$sql2 = 'SELECT customers_email_address,customers_firstname,customers_lastname FROM customers WHERE visa_id='.$visa_user_id;
		//echo '<br/>'.$sql2;
		$sql_query2 = tep_db_query($sql2);
		while($rows2 = tep_db_fetch_array($sql_query2))
		{
			$data['customers_email_address'] = $rows2['customers_email_address'];
			$data['customers_name'] = $rows2['customers_firstname'].'('.$rows2['customers_lastname'].')';
		}		
		
		return $data;
	}	
	
	/*
	*通过VISA订单号查询VISA订单信息
	*@return array
	*/
	function get_visa_order_info_by_visa_order_id($visa_order_id)
	{
		$sql = 'SELECT * FROM visa_order_ordermain_from_lujia WHERE ORD_ID='.$visa_order_id.' ORDER BY visa_order_ordermain_id DESC LIMIT 0,1';
		//echo $sql;
		$sql_query = tep_db_query($sql);
		return tep_db_fetch_array($sql_query);
	}		
	/*
	*获取updatetimestring列表
	*@return array
	*/
	function get_updatetimestring_list()
	{
		$data = false;
		$sql = 'SELECT update_time_string FROM `visa_order_updatetimestring` WHERE is_batch_added=\'1\' ORDER BY id DESC';
		$sql_query = tep_db_query($sql);
		while($rows = tep_db_fetch_array($sql_query))
		{
			$data[] = array('id'=>$rows['update_time_string'], 'text'=>$rows['update_time_string']);;
		}
		return $data;
	}
	
	/*
	*获取updatetimestring对应的数据
	*@param string: $update_time_string
	*@return array: array or false
	*/
	function get_visa_ordermain_list( $update_time_string )
	{
		$data = false;
		$sql = 'SELECT a.*,b.customers_email_address FROM `visa_order_ordermain_from_lujia` AS a, customers AS b WHERE a.update_time_string=\''.$update_time_string.'\' AND a.USR_ID=b.customers_id ORDER BY a.visa_order_ordermain_id DESC';
		$sql_query = tep_db_query($sql);
		while($rows = tep_db_fetch_array($sql_query))
		{
			$data[] = $rows;
		}
		return $data;
	}
	
	/*
	*通过visa的订单号,查找与路嘉的交流信息
	*@param int: $visa_order_id
	*@return array: array or false
	*/
	function visa_order_com_get_lists( $visa_order_id )
	{
		$data = false;
		$visa_order_id = (int)$visa_order_id;
		if ($visa_order_id < 1) { return false;}
		$sql = 'SELECT * FROM visa_order_communication WHERE visa_order_id='.$visa_order_id.' ORDER BY visa_order_com_parent_id ASC, add_date ASC';
		$sql_query = tep_db_query($sql);
		while($rows = tep_db_fetch_array($sql_query))
		{
			$data[] = $rows;
		}
		return $data;
	}
	
	/*获取所有visa订单留言的回复/阅读的状态汇整
	*@return array
	*/
	function visa_comunication_status()
	{
		$rt = false;
		$sql = 'SELECT a.`lujia_not_replied`,b.`lujia_not_read`,c.`usitrip_not_replied`,d.`usitrip_not_read` FROM  (SELECT 1 AS id, COUNT(1) AS `lujia_not_replied` FROM visa_order_communication WHERE admin_id >0 AND need_reply = \'1\' AND is_replied = \'0\') AS a, (SELECT 1 AS id, COUNT(1) AS `lujia_not_read`FROM visa_order_communication WHERE admin_id >0 AND is_read = \'0\') AS b, (SELECT 1 AS id, COUNT(1) AS `usitrip_not_replied`FROM visa_order_communication WHERE admin_id =0 AND need_reply = \'1\' AND is_replied = \'0\') AS c, (SELECT 1 AS id, COUNT(1) AS `usitrip_not_read` FROM visa_order_communication WHERE admin_id =0 AND is_read = \'0\') AS d WHERE a.id=b.id and a.id=c.id and a.id=d.id';
		$sql_query = tep_db_query($sql);
		$rows = tep_db_fetch_array($sql_query);
		$rt = array(
			'lujia_not_replied'=>$rows['lujia_not_replied'],
			'lujia_not_read'=>$rows['lujia_not_read'],
			'usitrip_not_replied'=>$rows['usitrip_not_replied'],
			'usitrip_not_read'=>$rows['usitrip_not_read']
		);
		return $rt;		
	}
	
	/*获取所有visa订单留言的回复/阅读的订单号
	*@return array
	*/
	function visa_comunication_status_detial()
	{
		$rt = array();
		$sql = 'SELECT visa_order_id,COUNT(1) AS e_count FROM visa_order_communication WHERE admin_id > 0 AND need_reply = \'1\' AND is_replied = \'0\' GROUP BY visa_order_id';
		$sql_query = tep_db_query($sql);
		while($rows = tep_db_fetch_array($sql_query))
		{
			$rt['lujia_not_replied'][] = $rows;
		}
		
		$sql = 'SELECT visa_order_id,COUNT(1) AS e_count FROM visa_order_communication WHERE admin_id > 0 AND is_read = \'0\' GROUP BY visa_order_id';
		$sql_query = tep_db_query($sql);
		while($rows = tep_db_fetch_array($sql_query))
		{
			$rt['lujia_not_read'][] = $rows;
		}
		
		$sql = 'SELECT visa_order_id,COUNT(1) AS e_count FROM visa_order_communication WHERE admin_id = 0 AND need_reply = \'1\' AND is_replied = \'0\' GROUP BY visa_order_id';
		$sql_query = tep_db_query($sql);
		while($rows = tep_db_fetch_array($sql_query))
		{
			$rt['usitrip_not_replied'][] = $rows;
		}
		
		$sql = 'SELECT visa_order_id,COUNT(1) AS e_count FROM visa_order_communication WHERE admin_id = 0 AND is_read = \'0\' GROUP BY visa_order_id';
		$sql_query = tep_db_query($sql);
		while($rows = tep_db_fetch_array($sql_query))
		{
			$rt['usitrip_not_read'][] = $rows;
		}		
				
		return $rt;
	}	
	
	/*通过visa订单号,获取路嘉最后一次的消息内容
	*@param int visa订单号
	*@return array
	*/
	function visa_com_get_lujia_last_mseeage_by_visa_order_id($visa_order_id)
	{
		$rt = false;
		$sql = 'SELECT title,message,add_date FROM visa_order_communication WHERE visa_order_id='.$visa_order_id.' AND admin_id=0 ORDER BY add_date DESC limit 0,1';
		$sql_query = tep_db_query($sql);
		while($rows = tep_db_fetch_array($sql_query))
		{
			$rt = $rows;
		}
		return $rt;
	}
	
  }
?>