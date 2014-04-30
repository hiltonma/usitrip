<?php 
function smarty_function_gethref($params, &$smarty)
{
	global $request_type, $session_started, $SID, $spider_flag;
	$fun = $params['fun'];
	$urlencode = $params['urlencode'];
	$urlencode = $urlencode!='true'?false:true;
	
	if(!$fun || !function_exists($fun)){
		$fun = 'tep_href_link';
	}
	if($fun == 'tep_href_link'){
		$to[0] = $params['page'];
		
		$to[2] = $params['connection'];
		$to[2] = $to[2]?'SSL':'NONSSL';
		
		$to[3] = $params['add_session_id'];
		$to[3] = $to[3]!='false'?true:false;
		
		$to[4] = $params['search_engine_safe'];
		$to[4] = $to[4]!='false'?true:false;
		
		$to[5] = $params['tablink'];
		
		$to[6] = $params['s2pcat'];
		
		$seo = $params['seo'];
		$seo = $seo!='false'?true:false;
		
		unset($params['fun'],$params['urlencode'],$params['page'],$params['connection'],$params['add_session_id'],$params['search_engine_safe'],$params['tablink'],$params['s2pcat'],$params['seo']);
		$parameters = '';
		foreach($params as $pk=>$pv){
			if($pv!==''){
				$pv = rawurlencode($pv);
				$parameters .= ($parameters==''?'':'&')."{$pk}={$pv}";
			}
		}
		$to[1] = $parameters;
		$href = '';
		if($seo){
			$href = tep_href_link($to[0],$to[1],$to[2],$to[3],$to[4],$to[5],$to[6]);
		}else{
			$href = tep_href_link_noseo($to[0],$to[1],$to[2],$to[3],$to[4]);
		}
	}else{
		unset($params['fun'],$params['urlencode']);
		//除参数fun以外，其他参数必须注意传入参数与调用函数所接受的参数的顺序
		//为便于查找，最好将参数名字也写成一样
		/*
		{:gethref fun='href' arg1='1' arg2='2' arg3='3':}
		
		php:
		function href($a1,$a2,$a3){
			echo $a1."\r\n";
			echo $a2."\r\n";
			echo $a3."\r\n";
		}
		//echo:======
		1
		2
		3
		//===========
		*/
		$href = call_user_func_array($fun,$params);
	}
	
	$urlencode && $href = rawurlencode($href);
	return $href;
}
?>