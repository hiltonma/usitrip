<?php
/**
 * 发送短信
 *
 * @param string $phone 要发送的号码
 * @param string $content 要发送的内容
 * @param string $chartset 短信编码'GB2312'
 * @return int
 */
function sms_send($phone,$content,$chartset='GB2312',$sendTime=''){
	!isset($GLOBALS['tmp_sms']) && $GLOBALS['tmp_sms']=new cpunc_SMS;
	$sms = $GLOBALS['tmp_sms'];
	$maxsend = 100;
	if($phone!=''){
		$phone = explode(',',$phone);
		$phone = array_unique($phone);//去除重复项
		$phone = array_diff($phone, array(NULL,'null','',' '));//去除空项
		$return = array();
		$z=0;
		$countphone = count($phone);
		while(true){
			$sendphone = array();
			$start = $z*$maxsend;
			$z++;
			$end = $start + $maxsend;
			$end > $countphone && $end = $countphone;
			for($i=$start;$i<$end;$i++){
				$sendphone[] = $phone[$i];
			}
			$sendphone = join(',',$sendphone);
			
			$return[] = $sms->SendSMS($sendphone,$content,$chartset,$sendTime);//发送并返回结果
			if($countphone==$end)break;
		}
		$return_tmp = array_diff($return, array(false));
		if(count($return)!=count($return_tmp)){
			return 2;//部分成功，部分失败
		}else{
			return 1;//成功
		}
	}
	return 0;//失败
}