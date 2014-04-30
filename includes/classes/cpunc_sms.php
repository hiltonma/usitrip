<?php
require_once(DIR_FS_CATALOG.'includes/nusoaplib/nusoap.php');

/**
 * 验证流程概要:https://www.b2m.cn/
 * 
 * 第一次使用时，需使用[序列号]和[密码]进行login(登录操作),并在登录同时产生一个session key
 * 
 * 登录成功后，称为[已登录状态],需要保存此产生的session key,用于以后的相关操作(如发送短信等操作)
 * 
 * logout(注销操作)后, session key将失效，并且不能再发短信了, 除非再进行login(登录操作)
 * 
 */
class cpunc_SMS{
	
	/**
	 * 网关地址
	 */
	//var $url = 'http://sdk999ws.eucp.b2m.cn:8080/sdk/SDKService';
	var $url = 'http://sdkhttp.eucp.b2m.cn/sdk/SDKService?wsdl';
	
	/**
	 * 序列号,请通过亿美销售人员获取
	 * 0SDK-EBB-0130-NERNP
	 */
	var $serialNumber = '3SDK-EMY-0130-NHZNM';
	
	/**
	 * 密码,请通过亿美销售人员获取
	 * 953836
	 */
	var $password = '254816';
	
	/**
	 * 登录后所持有的SESSION KEY，即可通过login方法时创建
	 */
	var $sessionKey = '621163';

	/**
	* 连接超时时间，单位为秒，默认0，为不超时
	*/
	var $timeout = 2;
	
	/**
	* 远程信息读取超时时间，单位为秒，默认30
	*/ 
	var $response_timeout = 10;
	
	/**
	$proxyhost		可选，代理服务器地址，默认为 false ,则不使用代理服务器
	$proxyport		可选，代理服务器端口，默认为 false
	$proxyusername	可选，代理服务器用户名，默认为 false
	$proxypassword	可选，代理服务器密码，默认为 false
	*/	
	var $proxyhost = false;
	var $proxyport = false;
	var $proxyusername = false;
	var $proxypassword = false; 
	
	/**
	 * webservice客户端
	 */
	var $soap;
	
	/**
	 * 默认命名空间
	 */
	var $namespace = 'http://sdkhttp.eucp.b2m.cn/';
	
	/**
	 * 往外发送的内容的编码,默认为 GB2312
	 */
	var $outgoingEncoding = "GB2312";
	
	/**
	 * 向内接受的内容的编码,默认为 GB2312
	 */
	var $incomingEncoding = '';
	
	
	function cpunc_SMS()
	{
		//$this->serialNumber = urlencode(CPUNC_ID);
		//$this->password = urlencode(CPUNC_PWD);
		/**
		 * 初始化 webservice 客户端
		 */	
		$this->soap = new nusoap_client($this->url,false,$this->proxyhost,$this->proxyport,$this->proxyusername,$this->proxypassword,$this->timeout,$this->response_timeout); 
		$this->soap->soap_defencoding = $this->outgoingEncoding;
		$this->soap->decode_utf8 = false;
	}
	
	/**
	 * 设置发送内容 的字符编码
	 * @param string $outgoingEncoding 发送内容字符集编码
	 */
	function setOutgoingEncoding($outgoingEncoding)
	{
		$this->outgoingEncoding = $outgoingEncoding;
		$this->soap->soap_defencoding = $this->outgoingEncoding;
	}
	
	/**
	 * 设置接收内容 的字符编码
	 * @param string $incomingEncoding 接收内容字符集编码
	 */
	function setIncomingEncoding($incomingEncoding)
	{
		$this->incomingEncoding = $incomingEncoding;
		$this->soap->xml_encoding = $this->incomingEncoding;
	}
	
	function setNameSpace($ns)
	{
		$this->namespace = $ns;
	}
	
	function getSessionKey()
	{
		return $this->sessionKey;
	}
	
	function getError()
	{		
		return $this->soap->getError();
	}
	
	/**
	 * 
	 * 指定一个 session key 并 进行登录操作
	 * 
	 * @param string $sessionKey 指定一个session key 
	 * @return int 操作结果状态码
	 * 
	 * 代码如:
	 * 
	 * $sessionKey = $cpunc_sms->generateKey(); //产生随机6位数 session key
	 * 
	 * if ($cpunc_sms->login($sessionKey)==0)
	 * {
	 * 	 //登录成功，并且做保存 $sessionKey 的操作，用于以后相关操作的使用
	 * }else{
	 * 	 //登录失败处理
	 * }
	 * 
	 * 
	 */
	function login($sessionKey='')
	{
				
		if ($sessionKey!='')
		{
			$this->sessionKey = $sessionKey;
		}
		$params = array('arg0'=>$this->serialNumber,'arg1'=>$this->sessionKey, 'arg2'=>$this->password);
		$result = $this->soap->call("registEx",$params,	$this->namespace);
		return $result;
	}
	
	/**
	 * 注销操作  (注:此方法必须为已登录状态下方可操作)
	 * 
	 * @return int 操作结果状态码
	 * 
	 * 之前保存的sessionKey将被作废
	 * 如需要，可重新login
	 */
	function logout()
	{
		$params = array('arg0'=>$this->serialNumber,'arg1'=>$this->sessionKey);
		print_r($params); 
		$result = $this->soap->call("logout", $params ,
			$this->namespace
		);

		return $result;
	}
	
	/**
	 * 获取版本信息
	 * @return string 版本信息
	 */
	function getVersion()
	{
		$result = $this->soap->call("getVersion",
			array(),
			$this->namespace
		);
		return $result;
	}
	
	/**
	 * 记录已发送的信息到数据库
	 *
	 * @param string $phone
	 * @param string $content
	 * @param string $return_code
	 * @param string $type
	 * @param datetime $datetime
	 * @return 返回被插入的主键id值
	 */
	function saveSMS($phone, $content, $return_code, $type='b2m.cn-send', $datetime=''){
		if($datetime==''){$datetime = date('Y-m-d H:i:s');}
		$phone = tep_db_input($phone);
		$content = tep_db_input($content);
		$type = tep_db_input($type);
		$return_code = tep_db_input($return_code);
		$data_array = array('to_phone'=>$phone, 'to_content'=> $content, 'to_type'=> $type, 'return_code'=> $return_code, 'add_date'=> $datetime);
		tep_db_perform('`cpunc_sms_history`', $data_array);
		return tep_db_insert_id();
	}
	
	/**
	 * 更新余额到数据库
	 *
	 */
	function upBalance(){
		$_value = $this->getBalance();
		$balance_value = "&#65509;".$_value;
		$today = date('Y-m-d H:i:s');
		tep_db_query('update configuration SET configuration_value="'.tep_db_input($balance_value).'",last_modified="'.$today.'" WHERE configuration_key="CPUNC_BALANCE" ');
	}
	
	/**
	 * 短信发送  (注:此方法必须为已登录状态下方可操作)
	 * 
	 * @param string $strMobile		手机号, 多个则用逗号(,)隔开 
	 * @param string $content		短信内容
	 * @param string $charset 		内容字符集, 默认GB2312
	 * @param string $sendTime		定时发送时间，格式为 yyyymmddHHiiss, 即为 年年年年月月日日时时分分秒秒,例如:20090504111010 代表2009年5月4日 11时10分10秒，如果不需要定时发送，请为'' (默认)
	 * @param string $addSerial 	扩展号, 默认为 ''
	 * @param int $priority 		优先级, 默认5
	 * @param int $smsId 			信息序列ID(唯一的正整数)
	 * @return int 若失败返回操作结果状态码0，成功则返回被插入到历史记录表中的主键id值
	 */
	function SendSMS($strMobile,$content,$charset='GB2312',$sendTime='',$addSerial='',$priority=5,$smsId=8888)
	{
		if(!defined('CPUNC_SWITCH') || CPUNC_SWITCH !='true'){ return false; }	//总开关
		//if(!defined('CPUNC_ID') || !defined('CPUNC_PWD') ){ return false; }
		$strMobile = str_replace(' ','',$strMobile);
		//检查手机号码是否在指定范围 start
		if(defined('CPUNC_PHONE_NUMBER_HEADER') && CPUNC_PHONE_NUMBER_HEADER!=""){
			$numbers_header = explode(',',CPUNC_PHONE_NUMBER_HEADER);
			$numbers = explode(',',$strMobile);
			$strMobile = '';
			$strMobiles = array();
			for($i=0; $i<count($numbers); $i++){
				for($j=0; $j<count($numbers_header); $j++){
					if(preg_match('/^'.preg_quote($numbers_header[$j]).'/',$numbers[$i])){
					$strMobiles[]= $numbers[$i];
					break;
					}else{
						$split_h = explode('-',$numbers_header[$j]);
						if(count($split_h)==2){
							$substr_num = substr($numbers[$i],0,strlen($split_h[0]));
							if($substr_num>=$split_h[0] && $substr_num<=$split_h[1]){
							  $strMobiles[]= $numbers[$i];
							  break;
							}
						}
					}
				}
			}
			$strMobiles = array_unique($strMobiles);
			$strMobile = implode(',',$strMobiles);
		}else{
			$strMobiles = explode(',',$strMobile);
			$strMobiles = array_unique($strMobiles);
			$strMobile = implode(',',$strMobiles);
		}
		//检查手机号码是否在指定范围 end
		
		//移除被过滤的号码start
		if(defined('CPUNC_PHONE_FLITER_NUMBERS') && CPUNC_PHONE_FLITER_NUMBERS!=""){
			$f = ereg_replace('[:space:]','',CPUNC_PHONE_FLITER_NUMBERS);
			$f = explode(',',$f);
			$strMobiles = explode(',',$strMobile);
			$strMobiles = array_diff($strMobiles,$f);
			$strMobile = implode(',',$strMobiles);
		}
		//移除被过滤的号码end
		
		if($strMobile==''){
			return false;
		}
		/*
		if(defined('CPUNC_TEST_STATUS') && CPUNC_TEST_STATUS=='true'){
			echo $strMobile. ' on testing, You can change test status it on back admin!';
			exit;
		}*/
		
		//手机号，如 array('159xxxxxxxx')； 如果需要多个手机号群发,如 array('159xxxxxxxx','159xxxxxxx2')
		$mobiles = explode(',', $strMobile);
		$content = iconv($charset, "GB2312//IGNORE", $content);
		$content .= "【请勿直接回复，走四方网】";
		
		/**
		 * 多个号码发送的xml内容格式是 
		 * <arg3>159xxxxxxxx</arg3>
		 * <arg3>159xxxxxxx2</arg3>
		 * ....
		 * 所以需要下面的单独处理
		 * 
		 */
		$params = array('arg0'=>$this->serialNumber,'arg1'=>$this->sessionKey,'arg2'=>$sendTime,
			'arg4'=>$content,'arg5'=>$addSerial, 'arg6'=>$charset,'arg7'=>$priority,'arg8'=>$smsId
		);
		foreach($mobiles as $mobile)
		{
			array_push($params,new soapval("arg3",false,$mobile));	
		}
		$result = $this->soap->call("sendSMS",$params,$this->namespace);
		$error = $this->soap->getError();
		
		$key_id = $this->saveSMS($strMobile, $content, $result);
		$this->upBalance();
		
		if($result!=null && $result=="0"){	//发送成功
			return $key_id; //true;
		}elseif(CPUNC_SHOW_ERROR_AND_STOP=='true'){
			echo $error; exit;
		}
		return false;
	}
	
	/**
	 * 余额查询  (注:此方法必须为已登录状态下方可操作)
	 * @return double 余额
	 */
	function getBalance()
	{
		$params = array('arg0'=>$this->serialNumber,'arg1'=>$this->sessionKey);
		$result = $this->soap->call("getBalance",$params,$this->namespace);
		return $result;
	}
	
	/**
	 * 取消短信转发  (注:此方法必须为已登录状态下方可操作)
	 * @return int 操作结果状态码
	 */
	function cancelMOForward()
	{
		$params = array('arg0'=>$this->serialNumber,'arg1'=>$this->sessionKey);
		$result = $this->soap->call("cancelMOForward",$params,$this->namespace);
		return $result;
	}
	
	/**
	 * 短信充值  (注:此方法必须为已登录状态下方可操作)
	 * @param string $cardId [充值卡卡号]
	 * @param string $cardPass [密码]
	 * @return int 操作结果状态码
	 * 
	 * 请通过亿美销售人员获取 [充值卡卡号]长度为20内 [密码]长度为6
	 */
	function chargeUp($cardId, $cardPass)
	{
		$params = array('arg0'=>$this->serialNumber,'arg1'=>$this->sessionKey,'arg2'=>$cardId,'arg3'=>$cardPass);
		$result = $this->soap->call("chargeUp",$params,$this->namespace);
		return $result;
	}
	
	/**
	 * 查询单条费用  (注:此方法必须为已登录状态下方可操作)
	 * @return double 单条费用
	 */
	function getEachFee()
	{
		$params = array('arg0'=>$this->serialNumber,'arg1'=>$this->sessionKey);
		$result = $this->soap->call("getEachFee",$params,$this->namespace);
		return $result;
	}

	/**
	 * 得到上行短信  (注:此方法必须为已登录状态下方可操作)
	 * 
	 * @return array 上行短信列表, 每个元素是Mo对象, Mo对象内容参考最下面
	 * 
	 * 
	 * 如:
	 * 
	 * $moResult = $cpunc_sms->getMO();
	 * echo "返回数量:".count($moResult);
	 * foreach($moResult as $mo)
	 * {
	 * 	  //$mo 是位于 cpunc_sms.php 里的 Mo 对象
	 * 	  echo "发送者附加码:".$mo->getAddSerial();
	 *	  echo "接收者附加码:".$mo->getAddSerialRev();
	 *	  echo "通道号:".$mo->getChannelnumber();
	 *	  echo "手机号:".$mo->getMobileNumber();
	 * 	  echo "发送时间:".$mo->getSentTime();
	 *	  echo "短信内容:".$mo->getSmsContent();
	 * }
	 * 
	 * 
	 */
	function getMO()
	{
		$ret = array();
		$params = array('arg0'=>$this->serialNumber,'arg1'=>$this->sessionKey);
		$result = $this->soap->call("getMO",$params,$this->namespace);
		//print_r($this->soap->response);
		//print_r($result);
		if (is_array($result) && count($result)>0)
		{
			if (is_array($result[0]))
			{
				foreach($result as $moArray)
					$ret[] = new Mo($moArray);	
			}else{
				$ret[] = new Mo($result);
			}
				
		}
		return $ret;
	}
	
	/**
	 * 得到状态报告  (注:此方法必须为已登录状态下方可操作)
	 * @return array 状态报告列表, 一次最多取5个
	 */
	function getReport()
	{
		$params = array('arg0'=>$this->serialNumber,'arg1'=>$this->sessionKey);
		$result = $this->soap->call("getReport",$params,$this->namespace);
		return $result;
	}
	
	/**
	 * 企业注册  [邮政编码]长度为6 其它参数长度为20以内
	 * 
	 * @param string $eName 	企业名称
	 * @param string $linkMan 	联系人姓名
	 * @param string $phoneNum 	联系电话
	 * @param string $mobile 	联系手机号码
	 * @param string $email 	联系电子邮件
	 * @param string $fax 		传真号码
	 * @param string $address 	联系地址
	 * @param string $postcode  邮政编码
	 * 
	 * @return int 操作结果状态码
	 * 
	 */
	function registDetailInfo($eName,$linkMan,$phoneNum,$mobile,$email,$fax,$address,$postcode)
	{
		$params = array('arg0'=>$this->serialNumber,'arg1'=>$this->sessionKey,
			'arg2'=>$eName,'arg3'=>$linkMan,'arg4'=>$phoneNum,
			'arg5'=>$mobile,'arg6'=>$email,'arg7'=>$fax,'arg8'=>$address,'arg9'=>$postcode		
		);
		$result = $this->soap->call("registDetailInfo",$params,$this->namespace);
		return $result;
	}
	
   	/**
   	 * 修改密码  (注:此方法必须为已登录状态下方可操作)
   	 * @param string $newPassword 新密码
   	 * @return int 操作结果状态码
   	 */
   	function updatePassword($newPassword) 
   	{
   		$params = array('arg0'=>$this->serialNumber,'arg1'=>$this->sessionKey,
			'arg2'=>$this->password,'arg3'=>$newPassword		
		);
		$result = $this->soap->call("serialPwdUpd",$params,$this->namespace);
		return $result;
   	}       
   	
   	/**
   	 * 
   	 * 短信转发
   	 * @param string $forwardMobile 转发的手机号码
   	 * @return int 操作结果状态码
   	 * 
   	 */
   	function setMOForward($forwardMobile)
   	{
   		
   		$params = array('arg0'=>$this->serialNumber,'arg1'=>$this->sessionKey,
			'arg2'=>$forwardMobile	
		);
		
		$result = $this->soap->call("setMOForward",$params,$this->namespace);
		return $result;
   	}
   	
   	/**
   	 * 短信转发扩展
   	 * @param array $forwardMobiles 转发的手机号码列表, 如 array('159xxxxxxxx','159xxxxxxxx');
   	 * @return int 操作结果状态码
   	 */
   	function setMOForwardEx($forwardMobiles=array())
   	{
		$params = array('arg0'=>$this->serialNumber,'arg1'=>$this->sessionKey);
			
		/**
		 * 多个号码发送的xml内容格式是 
		 * <arg2>159xxxxxxxx</arg2>
		 * <arg2>159xxxxxxx2</arg2>
		 * ....
		 * 所以需要下面的单独处理
		 * 
		 */
		foreach($forwardMobiles as $mobile)
		{
			array_push($params,new soapval("arg2",false,$mobile));	
		}
		$result = $this->soap->call("setMOForwardEx",$params,$this->namespace);
		return $result;
   	}
   	
	/**
	 * 生成6位随机数
	 */
	function generateKey()
	{
		return rand(100000,999999);
	}
}

class Mo{
	/**
	 * 发送者附加码
	 */
	var $addSerial;
	
	/**
	 * 接收者附加码
	 */
	var $addSerialRev;
	
	/**
	 * 通道号
	 */
	var $channelnumber;
	
	/**
	 * 手机号
	 */
	var $mobileNumber;
	
	/**
	 * 发送时间
	 */
	var $sentTime;
	
	/**
	 * 短信内容
	 */
	var $smsContent;
	
	function Mo(&$ret=array())
	{
		$this->addSerial = $ret[addSerial];
		$this->addSerialRev = $ret[addSerialRev];
		$this->channelnumber = $ret[channelnumber];
		$this->mobileNumber = $ret[mobileNumber];
		$this->sentTime = $ret[sentTime];
		$this->smsContent = $ret[smsContent];
	}
	
	function getAddSerial()
	{
		return $this->addSerial;
	}
	function getAddSerialRev()
	{
		return $this->addSerialRev;
	}
	function getChannelnumber()
	{
		return $this->channelnumber;
	}
	function getMobileNumber()
	{
		return $this->mobileNumber;
	}
	function getSentTime()
	{
		return $this->sentTime;
	}
	function getSmsContent()
	{
		return $this->smsContent;
	}
}

?>
