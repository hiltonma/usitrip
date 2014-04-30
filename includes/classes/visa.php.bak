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

    /**
	 *签证 visa构造函数
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
  	 * 判断来访的是否合法
  	 * @param array $cert的格式:{code:"",md5:""}
  	 * @return boolen
  	 */   
	function get_cert($cert)
	{
		//code是一个unix的时间戳,API_SIGNATURE是密钥,md5是时间戳与密钥字符串相加后再md5加密的结果字符串
		
		$cert = str_replace('\\"','"',str_replace('\\\'','"',urldecode($cert)));
		//echo '<br/>cert:'.$cert.'<br/>';
		$rt = false;
		if(tep_not_null($cert))
		{
			$cert_arr = json_decode(iconv('utf-8',CHARSET,$cert),true);
			//$cert_arr = json_decode($cert,true);
			//echo '<br/>print_r:';
			//print_r($cert_arr);

			$API_SIGNATURE = 'ArRerOJAo5TWrRjto4AcSV5';
			$cert_code = $cert_arr['code'];
			
			if ($cert_code > strtotime("-30 minutes"))
			{
				$cert_code = $cert_code.$API_SIGNATURE;
				$cert_md5 = $cert_arr['md5'];
				$md5_new = md5($cert_code);
	
				if( tep_not_null($cert_code) && tep_not_null($cert_md5) && ( strtolower($cert_md5) == strtolower($md5_new) ) )
				{
					$rt = true;
				}
			}
		}
		return $rt;
	}
	
	
	
  	/**
  	 * 返回用户信息
  	 * @param int user_id 用户ID
  	 * @return array 
  	 */
  	function get_user_info($user_id)
  	{
  		$data = false;
		if ((int)$user_id ==0)
		{
			return false;
		}
  		$sql = 'SELECT customers_id,CONCAT(customers_lastname,customers_firstname) AS customers_name,customers_telephone,visa_id,customers_email_address FROM customers WHERE customers_id='.$user_id;
  		$sql_query = tep_db_query($sql);
  		while($rows = tep_db_fetch_array($sql_query))
  		{
  			$data = $rows;
  		}
		return $data;
  	}  	
  	
  	/**
  	 * 取得visa系统返回的用户认证信息(json,转换为数组再返回)
  	 * @param int user_id 用户ID
  	 * @return array visa信息
  	 */
  	function get_visa_info($user_id)
  	{
  		$rt = false;
		if ((int)$user_id ==0)
		{
			return false;
		}
  		$data = $this->get_user_info($user_id);
  		//print_r($data);		
  		$user_name = iconv('gb2312','UTF-8',$data['customers_name']);
  		$user_tel = $data['customers_telephone'];
  		$url = VISA_DOMAIN . VISA_LUJIA_USER_CHECK_URL. '{%22UID%22:'.$user_id.',%20%22UNAME%22:%22'.urlencode($user_name).'%22,%20%22UTELE%22:%22'.$user_tel.'%22,%20%22UEMAIL%22:%22'.$data['customers_email_address'].'%22}';
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
  	*获取签证产品列表
  	*@param 
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
  	*通过签证产品ID获取签证产品信息
  	*@param int $visa_products_id签证产品ID
  	*@return array 签证产品资料
  	*/
	function get_product_by_visa_products_id($visa_products_id)
	{
		$rt = false;
		$visa_products_id = (int)$visa_products_id;
		if ($visa_products_id>0)
		{
			$sql = 'SELECT * FROM `visa_products_list` WHERE visa_products_id='.$visa_products_id;
			$sql_query = tep_db_query($sql);
			while($row =  tep_db_fetch_array($sql_query))
			{
				$rt =  $row;
			}
		}
		return $rt;
	}
  	
  	function get_visa_order_list($orders_id)
  	{
  		
  	}
	
	 /*
	 *客人自行在前台下了visa订单后,路嘉返回订单数据并更新到数据库
	 *@param xml 
	 *@return array: array(true成功/false失败,更新数量,错误信息)
	 */
	function visa_add_order_info_returned_fromlujia($xml)
	{
		$returns = Array('result'=>false,'inserted_count'=>0,'error_msg'=>'');
		
		//$xml = simplexml_load_file("http://www.888trip2.com/admin/VISA_ORDER_LIST.xml");
		//$xml = $_POST['order'];

		//$xml = iconv('utf-8','gb2312',$xml);

		$arr = xml2array('',1,'tag',$xml);

		if(is_array($arr))
		{
			$datetime = date('Y-m-d H:i:s');
			$timestring = str_replace(' ','',str_replace(':','',str_replace('-','',$datetime)));
			//$timestring = date_format($datetime,'Ymd His');
			$data = array('update_time_string'=>$timestring,'add_date'=>$datetime,'is_batch_added'=>'0');
			
			$inserted_id = 0;
			$inserted_id = tep_db_fast_insert('visa_order_updatetimestring',$data);
			$inserted_count = 0;
			if ($inserted_id > 0)
			{
				$data2 = $arr['root']['OrderMain']['Row'];

				//print_r($data2); exit();
				$data21 = $this->iconv_array_charencoding('utf-8','gb2312',$data2);
				$data21['ORD_DATE'] = date("Y-m-d",strtotime($data21['ORD_DATE']));	
				$data21['ORD_CDATE'] = date("Y-m-d H:i:s",strtotime($data21['ORD_CDATE']));	
				if(!empty($data21['ORD_MDATE'])){
					$data21['ORD_MDATE'] = date("Y-m-d H:i:s",strtotime($data21['ORD_MDATE']));	
				}
			
				$data21['update_time_string'] = $timestring;
				$data21['is_batch_added'] = '0';
				tep_db_fast_insert('visa_order_ordermain_from_lujia',$data21);
				$inserted_count ++;

				$returns['result']=true;
				$returns['inserted_count']=$inserted_count;
				
				$data3 = $arr['root']['OrderMainList']['Row'];

				$data31 = array();
				$data31 = $this->iconv_array_charencoding('utf-8','gb2312',tep_db_prepare_input($data3));
				$data31['update_time_string'] = $timestring;
				tep_db_fast_insert('visa_order_ordermainlist_from_lujia',$data31);
				
				
				$data4 = $arr['root']['OrderPay']['Row'];
				for($i=0, $n = count($data4); $i<$n; $i++)
				{
					$data41 = array();
					$data41 = $this->iconv_array_charencoding('utf-8','gb2312',tep_db_prepare_input($data4[$i]));
					if(!empty($data41['ORD_PAY_DATE'])){ $data41['ORD_PAY_DATE'] = date("Y-m-d H:i:s",strtotime($data41['ORD_PAY_DATE'])); }
					if(!empty($data41['ORD_PAY_OK_DATE'])){ $data41['ORD_PAY_OK_DATE'] = date("Y-m-d H:i:s",strtotime($data41['ORD_PAY_OK_DATE'])); }
					$data41['update_time_string'] = $timestring;
					$data41['is_batch_added'] = '0';
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
			$returns['error_msg']='从路嘉获取订单列表失败';
		}
		return $returns;
	}	
	
	 /*
	 *路嘉将email的内容post到走四方,再由走四方发送出去
	 *@param array
	 *@return boolen: (true成功/false失败)
	 */
	function visa_forward_email_fromlujia($email_data)
	{	
		/*
		TO_EMAIL 收件人地址
		TO_NAME 收件人姓名
		TITLE 邮件标题
		CONTENT 邮件内容（HTML）
		FILE0，FILE1，FILE2... 附件（文件二进制的BASE64格式编码）,FILENAME0,FILENAME1,FILENAME2...
		*/

		$to_name = iconv('utf-8','gb2312//IGNORE',$email_data['TO_NAME']);
		$to_email_address  = iconv('utf-8','gb2312//IGNORE',$email_data['TO_EMAIL']);
		$email_subject = iconv('utf-8','gb2312//IGNORE',$email_data['TITLE']);
		$email_text = iconv('utf-8','gb2312//IGNORE',$email_data['CONTENT']);// 
		$email_text = str_replace('\r\n','',str_replace('\n','',$email_text));
		$email_text = str_replace(PHP_EOL,'',$email_text);
		$email_text = str_replace(array(chr(13),chr(10),"\n","\r"),"",$email_text);
		//$email_text = str_replace('"','\'',$email_text);		
		
		$from_email_name = 'usitrip';
		$from_email_address = 'visa@usitrip.com';
		
		$attachment_html = '';
		
		//echo '<br/>',$email_text,'<hr/>'; exit();

		foreach($email_data AS $key=>$value)
		{
			
			if (substr($key,0,4)=='FILE' && substr($key,0,8)!='FILENAME')
			{
				//echo '<br/>',$key,',',substr($key,0,4),', FILENAME',substr($key,4,1);
				
				$filename_old = $email_data['FILENAME'.substr($key,4,1)];
				//echo strlen($filename_old).'<br/>';
				if (strlen($filename_old)>0 )
				{
					$file_ext_arr = explode('.',$filename_old);
					$file_ext = $file_ext_arr[count($file_ext_arr)-1];
					$filename = 'images/visa/'.'visa_email_file_'.time().'_'.rand(1,999999).'_'.rand(1,999999).'.'.$file_ext;
					$file_email = DIR_FS_CATALOG.$filename;
					file_put_contents($file_email,base64_decode($email_data[$key]));
					$attachment_html = $attachment_html . '&nbsp;&nbsp;<a href="'.SCHINESE_HTTP_SERVER.'/'.$filename.'">'.$filename_old.'</a>';
				}
			}
		}
		
		if ( strlen($attachment_html) > 5 )
		{
			$email_text  = '<html><body>'.$email_text . '<p>附件:'.$attachment_html . '</p></body><html>';
		}
		
		/*if (tep_not_null($attachment_html))
		{
			
			$email_text = '<br/>附件:'.$attachment_html;
			$email_text = str_ireplace('</body>', $attachment_html.'</body>', $email_text);
		}*/
		//echo $email_text;	//exit();

		tep_mail($to_name." ", $to_email_address, $email_subject." ", $email_text." ", $from_email_name." ", $from_email_address,EMAIL_USE_HTML,'gb2312');
		
	}	
	 /*
	 *签证订单提交到大使馆后,如果状态改变,则路嘉回发数据到走四方.
	 *@param xml 
	 *@return array: array(true成功/false失败,更新数量,错误信息)
	 */
	function visa_add_visa_info_returned_fromlujia($post)
	{
		$returns = Array('result'=>false,'inserted_count'=>0,'error_msg'=>'');
		
		//$order = $visa_data;
		//tep_db_fast_insert();
		
		$filepath = 'tmp/visa_info_'.time().'_'.rand(1,9999).'.xml';
		$visa_data = stripslashes($visa_data);
		
		file_put_contents($filepath,$visa_data);
		$url = HTTP_SERVER.'/'.$filepath;
		//$url = HTTP_SERVER.'/tmp/down_data_ext.xml';
		//$url = HTTP_SERVER.'/tmp/visa_info_1340098049.xml';

		$arr = xml2array($url,1,'tag','');

		if(is_array($arr))
		{			
			$inserted_count = 0;			

			$data2 = $arr['VisaMain']['Row'];
			//print_r($data2);exit();

			//$data21 = $this->iconv_array_charencoding('utf-8','gb2312',$data2);
			$data21 = array();
			$data21['VIS_ID'] = $data2['VIS_ID'];
			$data21['VIS_GRP_ID'] = $data2['VIS_GRP_ID'];
			$data21['FRO_ORD_ID'] = $data2['FRO_ORD_ID'];
			$data21['USR_ID'] = $data2['USR_ID'];
			$data21['VIS_TYPE'] = $data2['VIS_TYPE'];
			$data21['VIS_CDATE'] = date("Y-m-d H:i:s",strtotime($data2['VIS_CDATE']));	
			$data21['VIS_MDATE'] = date("Y-m-d H:i:s",strtotime($data2['VIS_MDATE']));	
			$data21['VIS_STATUS'] = $data2['VIS_STATUS'];
			$data21['VIS_XML'] = iconv("utf-8","gb2312",$data2['VIS_XML']);

			$data21['VIS_DATA'] = $data2['VIS_DATA'];
			$data21['ROB_ID'] = $data2['ROB_ID'];
			$data21['ROB_SDATE'] = $data2['ROB_SDATE'];
			$data21['ROB_EDATE'] = $data2['ROB_EDATE'];
			$data21['ROB_APP_ID'] = $data2['ROB_APP_ID'];
			$data21['ROB_PWD'] = $data2['ROB_PWD'];
			$data21['SUP_ID'] = $data2['SUP_ID'];
			$data21['VIS_GUID'] = $data2['VIS_GUID'];
			$data21['VIS_RESULT'] = $data2['VIS_RESULT'];
			$data21['VIS_REQ_FILE_TAG'] = $data2['VIS_REQ_FILE_TAG'];
			$data21['ADM_ID'] = $data2['ADM_ID'];
			$data21['add_date'] = date("Y-m-d H:i:s",time());
			
			if (!is_dir(DIR_FS_CATALOG.'images/visa/'))
			{
				mkdir(DIR_FS_CATALOG.'images/visa/');
			}
			
			//保存VIS_IMG到文件
			if (strlen($data2['VIS_IMG'])>0)
			{
				$filename = 'visa_file_'.time().'_'.rand(1,999999);
				$file_tmp = DIR_FS_CATALOG.'tmp/'.$filename.'.tmp';
				file_put_contents($file_tmp,base64_decode($data2['VIS_IMG']));
				$tmp_ext = get_file_type2($file_tmp);
				$file_new = 'images/visa/'.$filename.'.'.$tmp_ext;			
				copy($file_tmp,DIR_FS_CATALOG.$file_new);
				$data21['VIS_IMG'] = $file_new;
			}
			else
			{
				$data21['VIS_IMG'] = '';
			}
			
			//保存VIS_PRT到文件
			if (strlen($data2['VIS_PRT'])>0)
			{
				$filename = 'visa_file_'.time().'_'.rand(1,999999);
				$file_tmp = DIR_FS_CATALOG.'tmp/'.$filename.'.tmp';
				file_put_contents($file_tmp,base64_decode($data2['VIS_PRT']));
				$tmp_ext = get_file_type2($file_tmp);
				$file_new = 'images/visa/'.$filename.'.'.$tmp_ext;			
				copy($file_tmp,DIR_FS_CATALOG.$file_new);
				$data21['VIS_PRT'] = $file_new;		
			}
			else
			{
				$data21['VIS_PRT'] = '';	
			}
			

			//保存VIS_PRT1到文件
			if (strlen($data2['VIS_PRT1'])>0)
			{
				$filename = 'visa_file_'.time().'_'.rand(1,999999);
				$file_tmp = DIR_FS_CATALOG.'tmp/'.$filename.'.tmp';
				file_put_contents($file_tmp,base64_decode($data2['VIS_PRT1']));
				$tmp_ext = get_file_type2($file_tmp);
				$file_new = 'images/visa/'.$filename.'.'.$tmp_ext;			
				copy($file_tmp,DIR_FS_CATALOG.$file_new);
				$data21['VIS_PRT1'] = $file_new;
			}
			else
			{
				$data21['VIS_PRT1'] = '';
			}					
				
			//print_r($data21);			

			tep_db_fast_insert('visa_to_embassy_info',$data21);

		}
		else{
			$returns['result']=false;
			$returns['error_msg']='VISA信息解析失败';
		}
		return $returns;
	}		
	
	 function iconv_array_charencoding($from,$to,$array)
	 {
	 	$rt = array();
		if(is_array($array))
		{
			foreach($array AS $key=>$value)
			{
				if(is_array($value))
				{
					if(count($value)>0)
					{
						$rt[$key] = iconv_array_charencoding($from,$to,$value);
					}
					else
					{
						$rt[$key] = '';
					}
				}
				else
				{
					$rt[$key] = iconv($from,$to,$value);
				}
			}
		}
		else
		{
			$rt = iconv($from,$to,$rt);;
		}
		return $rt;
	 }
	/*
	*通过VISA订单号查询VISA订单信息
	*@return array
	*/
	function get_visa_order_info_by_visa_order_id($visa_order_id)
	{
		$data = false;
		$sql = 'SELECT * FROM visa_order_ordermain_from_lujia WHERE ORD_ID='.$visa_order_id.' ORDER BY visa_order_ordermain_id DESC LIMIT 0,1';
		//echo $sql;
		$sql_query = tep_db_query($sql);
		while($rows = tep_db_fetch_array($sql_query))
		{
			$data = $rows;
		}
		
		return $data;
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
}
?>