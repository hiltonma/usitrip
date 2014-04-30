<?php
//吉信通的，已经没用。
//cpunc_sms手机短信平台
//中国手机短信发送接口
//网址http://www.cpunc.com/
//更新为http://www.winic.org/
//请注意接口是采用GB2312编码

class cpunc_SMS {
	var $id;	//账号
	var $pwd;	//密码
	function cpunc_SMS($uid="",$passwd=""){
		$this->id = urlencode(CPUNC_ID);
		$this->pwd = urlencode(CPUNC_PWD);
		if($uid!="" && $passwd!=""){
		  $this->id = urlencode($uid);
		  $this->pwd = urlencode($passwd);
		}
	}
	
	//记录已发送的信息到数据库
	function SaveSMS($to,$content,$return_code,$type='cpunc'){
		$data_array = array('to_phone'=>$to, 'to_content'=> tep_db_prepare_input($content), 'to_type'=>$type, 'return_code'=>tep_db_prepare_input($return_code), 'add_date'=> date('Y-m-d H:i:s'));
		tep_db_perform('`cpunc_sms_history`', $data_array); 
	}
	//更新余额到数据库
	function UpBalance(){
		$balance_value = "&#65509;".$this->GetBalance();
		tep_db_query('update configuration SET configuration_value="'.tep_db_input($balance_value).'" WHERE configuration_key="CPUNC_BALANCE" ');
	}
	//取得余额
	function GetBalance(){
	  $url="http://service.winic.org:8009/webservice/public/remoney.asp?uid=%s&pwd=%s";
	  $rurl = sprintf($url, $this->id, $this->pwd);

	  $ch = curl_init();
	  $timeout = 5;
	  curl_setopt($ch, CURLOPT_URL, $rurl);
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	  //在需要用户检测的网页里需要增加下面两行
	  //curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	  //curl_setopt($ch, CURLOPT_USERPWD, US_NAME.”:”.US_PWD);
	  $result = curl_exec($ch);
	  curl_close($ch);
	  return $result;
	  
	}
	
	//发送信息$strMobile为手机号码,$content内容,$chartset为内容的源编码
	function SendSMS($strMobile,$content, $chartset='GB2312'){
	  if(!defined('CPUNC_SWITCH') || CPUNC_SWITCH !='true'){ return false; }	//总开关
	  $strMobile = str_replace(' ','',$strMobile);
	  if(!defined('CPUNC_ID') || !defined('CPUNC_PWD') ){ return false; }
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
	  
	  $url="http://service.winic.org:8009/sys_port/gateway/?id=%s&pwd=%s&to=%s&content=%s&time=";
	  $to = urlencode($strMobile);
	  $content = iconv($chartset,"GB2312".'//IGNORE',$content); //将utf-8转为gb2312再发
	  $content = urlencode($content);
	  $rurl = sprintf($url, $this->id, $this->pwd, $to, $content);
	  
	  $ch = curl_init();
	  curl_setopt($ch, CURLOPT_POST, true);
	  curl_setopt($ch, CURLOPT_HEADER, false);
	  curl_setopt($ch, CURLOPT_URL,$rurl);
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	  curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
	  $result = curl_exec($ch);
	  curl_close($ch);
	  
	  //$ret = file($result);
	  //000/Send:1/Consumption:.1/Tmoney:0/sid:1 
	  //000为成功发送
	  //echo $result;
	  $send_status = explode('/',$result);
	  //print_r($send_status);
	  $this->SaveSMS(urldecode($strMobile),urldecode($content),$result);
	  $this->UpBalance();
	  if($send_status[0]=='000'){	//发送成功
		  return true; 
	  }elseif(CPUNC_SHOW_ERROR_AND_STOP=='true'){
		  echo $send_status[0]; exit;
	  }
	  return false;
  }
	
}
?>