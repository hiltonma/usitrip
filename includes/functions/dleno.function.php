<?php
function Add_S(&$array){
	if (is_array($array)) {
		foreach ($array as $key => $value) {
			if (!is_array($value)) {
				$array[$key] = addslashes($value);
			} else {
				Add_S($array[$key]);
			}
		}
	}
}
function search_addseo($seomode){
	global $the_title,$the_key_words,$the_desc;
	if($seomode){
		$the_title = $seomode.' - '.$the_title;
		$the_key_words = $seomode.', '.$the_key_words;
		$the_desc = $seomode.', '.$the_desc;
	}
}
//根据参数构造URL
function makeDirUrl($urlParme,$mod='',$val='',$dir=true){
	$url = "";
	$isHaveMod = false;
	$val = urlencode(db_to_html($val));
	foreach($urlParme as $k=>$v){
		$v = urlencode(db_to_html($v));
		if($mod!=$k&&$v!=''){
			if($dir)$url .= "{$k}-{$v}/";
			else $url .= "&{$k}={$v}";
		}elseif($mod==$k&&$val!=''){
			if($dir)$url .= "{$mod}-{$val}/";
			else $url .= "&{$mod}={$val}";
			$isHaveMod = true;
		}
	}
	if(!$isHaveMod && $val!=''){
		if($dir)$url .= "{$mod}-{$val}/";
		else $url .= "&{$mod}={$val}";
	}
	return $url;
}
function dformatString($msg){
	$msg = str_replace('&',"&amp;",$msg);
	$msg = str_replace('"',"&quot;",$msg);
	$msg = str_replace("'","&#39;",$msg);
	$msg = str_replace("<","&lt;",$msg);
	$msg = str_replace(">","&gt;",$msg);
	$msg = str_replace(" ","&nbsp;",$msg);
	$msg = str_replace("\t","   &nbsp;  &nbsp;",$msg);
	$msg = str_replace("\r","<br>",$msg);
	$msg = str_replace("\n","",$msg);
	$msg = str_replace("   "," &nbsp; ",$msg);	
	return $msg;
}
//get $cPathOnly ,code from : TEMPLATE_NAME . '/header.php'
function getcPathOnly($cPath){
	if(preg_match('/^193_195/',$cPath)){//特殊情况
		$cPathOnly = '195';
	}else{
		$cPathOnly = explode('_',$cPath);
		$cPathOnly = intval($cPathOnly[0]);
	}
	return $cPathOnly;
}

function utf8tohtml($var){
	switch (gettype($var)) {
		case 'boolean':
			$var = $var ? true : false;
			break;
		case 'NULL':
			$var =  '';
			break;
		case 'integer':
			$var =  (int) $var;
			break;
		case 'double':
			$var =  (float) $var;
			break;
		case 'float':
			$var =  (float) $var;
			break;
		case 'string':
			$var =  iconv('utf-8',CHARSET.'//IGNORE',$var);
			break;
		case 'array':
			foreach($var as $key=>$v){
				$var[$key] = utf8tohtml($v);
			}
			break;
	    case 'object':
			break;
		default:
			break;
	}
	return $var;
}

function htmltoutf8($var){
	switch (gettype($var)) {
		case 'boolean':
			$var = $var ? true : false;
			break;
		case 'NULL':
			$var =  '';
			break;
		case 'integer':
			$var =  (int) $var;
			break;
		case 'double':
			$var =  (float) $var;
			break;
		case 'float':
			$var =  (float) $var;
			break;
		case 'string':
			$var =  iconv(CHARSET,'utf-8//IGNORE',db_to_html($var));
			break;
		case 'array':
			foreach($var as $key=>$v){
				$var[$key] = htmltoutf8($v);
			}
			break;
	    case 'object':
			break;
		default:
			break;
	}
	return $var;
}

function dCookie($ck_Var,$ck_Value = NULL,$ck_Time = "F",$ckpath='/',$ckdomain='',$httponly = true){
	$timestamp = time();
	//GET
	if($ck_Value === NULL){//=== eq,can not ==
		$ck_Value=isset($_COOKIE[$ck_Var])?$_COOKIE[$ck_Var]:'';
		return $ck_Value;
	}else{
	//SET
		//ckpath & ckdomain
		$islocalhost = ($_SERVER['HTTP_HOST']=='localhost' || preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}(\:[0-9]{2,})?$/',$_SERVER['HTTP_HOST'])) ? 1 : 0;
		if ($islocalhost) {
			$ckpath = '/'; $ckdomain = '';
		} else {
			if (!$ckdomain) {
				$pre_host = strtolower(substr($_SERVER['HTTP_HOST'],0,strpos($_SERVER['HTTP_HOST'],'.'))+1);
				$ckdomain = substr($_SERVER['HTTP_HOST'],strpos($_SERVER['HTTP_HOST'],'.')+1);
				$ckdomain = '.'.((strpos($ckdomain,'.')===false) ? $_SERVER['HTTP_HOST'] : $ckdomain);
				if (strpos($B_url,$pre_host)!==false) {
					$ckdomain = $pre_host.$ckdomain;
				}
			}
		}
		//cktime
		if(!is_numeric($ck_Time)){
			$ck_Time = (trim($ck_Value)!='')?0:$timestamp-31536000;
		}else{
			$ck_Time = ($ck_Time==0)?0:$timestamp+$ck_Time;
		}
		//https
		$ishttps=false;
		$https = array();
		isset($_SERVER['REQUEST_URI']) && $https = @parse_url($_SERVER['REQUEST_URI']);
		if (empty($https['scheme'])) {
			if (!empty($_SERVER['HTTP_SCHEME'])) {
				$https['scheme'] = $_SERVER['HTTP_SCHEME'];
			} else {
				$https['scheme'] = (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) != 'off') ? 'https' : 'http';
			}
		}
		if ($https['scheme'] == 'https'){
			$ishttps=true;
		}
		//save
		if (version_compare(PHP_VERSION, '5.2.0', '>=')) {
			return setcookie($ck_Var, $ck_Value, $ck_Time, $ckpath, $ckdomain, $ishttps, $httponly);
		} else {
			return setcookie($ck_Var, $ck_Value, $ck_Time, $ckpath.($httponly ? '; HttpOnly' : ''), $ckdomain, $ishttps);
		}
	}
}
function makePager($count,$pagename,$per,$dir=true,$type=0){
	global $$pagename,$ajaxTypename;
	$ajaxclass = $ajaxTypename.'_ajax';
	$page = &$$pagename;
	$next=3;
	$sum=ceil($count/$per);
	$page > $sum && $page=$sum;
	(!is_numeric($page) || $page <1) && $page=1;
	$pervpage=$page-1;
	(!is_numeric($pervpage) || $pervpage <1) && $pervpage=1;
	$nextpage=$page+1;
	(!is_numeric($nextpage) || $nextpage >$sum) && $nextpage=$sum;
	$ret = '';
	if($sum>1){
		if($page>1){
			$ret .="<a href='".makesearchUrl($pagename,1,$dir)."' class='go first {$ajaxclass} first{$type}' mod='{$pagename}' val='1'>" . ((int)$type == 0 ? '' : db_to_html('首页')) . "</a>";
			$ret .= "<a href='".makesearchUrl($pagename,$pervpage,$dir)."' class='go pre {$ajaxclass} pre{$type}' mod='{$pagename}' val='{$pervpage}'>". db_to_html('上一页') ."</a>";
		}
		$ret .= '<span>' . ($type == 0 ? '|' : '');
		if($page>$sum-$next)$next=$next*2;
		$min=min($sum,$page+$next);
		for($i=$page-$next;$i <= $min;$i++){
			if($i<1){
				$i=1;
				$min = $next*2+1>$sum?$sum:$next*2+1;
			}
			$ret .= $i==$page ? "<b>$i</b>" . ((int)$type == 0 ? "|" : "") : "<a href='".makesearchUrl($pagename,$i,$dir)."' class='{$ajaxclass} type{$type}' mod='{$pagename}' val='{$i}'>$i</a>" . ((int)$type == 0 ? "|" : '');
		}
		$ret .= '</span>';
		if($page<$sum){
			$ret .= "<a href='".makesearchUrl($pagename,$nextpage,$dir)."' class='go next {$ajaxclass} next{$type}' mod='{$pagename}' val='{$nextpage}'>" . db_to_html('下一页') . "</a>";
			$ret .= "<a href='".makesearchUrl($pagename,$sum,$dir)."' class='go last {$ajaxclass} last{$type}' mod='{$pagename}' val='{$sum}'>" .((int)$type == 0 ? '' : db_to_html('末页')) . "</a>";
		}
	}else{
		$ret=" ";
	}
	return $ret;
}

function ObHeader($url,$time=0){
	$url = str_replace(array("\n", "\r"), '', $url);
	if (!headers_sent()) {
		if(0===$time) {
			header("Location: ".$url);
		}else {
			header("refresh:{$time};url={$url}");
		}
		exit();
	}else {
		exit("<meta http-equiv='Refresh' content='{$time};URL={$url}'>");
	}
}

//=====================================================
function writeover($filename,$data,$method="rb+",$iflock=1,$check=1,$chmod=1){
	$dirname = dirname($filename);
	@chmod($dirname,0777);
	@touch($filename);
	$handle = @fopen($filename,$method);
	$iflock && @flock($handle,LOCK_EX);
	@fwrite($handle,$data);
	$method=="rb+" && @ftruncate($handle,strlen($data));
	@fclose($handle);
	$chmod && @chmod($filename,0777);
} 
function readover($filename,$method='rb',$readsize='D'){
	$filedata="";
	if(file_exists($filename)){
		$filesize = @filesize($filename);
		$readsize!='D' && $filesize = min($filesize,$readsize);
		$filedata = '';
		if ($handle = @fopen($filename,$method)) {
			@flock($handle,LOCK_SH);
			$filedata = @fread($handle,$filesize);
			@fclose($handle);
		}
	}
	return $filedata;
}

function download($filename,$basename='',$ob = true){
	$timestamp=time();
	if(is_file($filename)){
		$ob && ob_end_clean();
		header('Last-Modified: '.gmdate('D, d M Y H:i:s',$timestamp+86400).' GMT');
		header('Cache-control: max-age=86400');
		header('Expires: '.gmdate('D, d M Y H:i:s',$timestamp+86400).' GMT');
		header('Content-Encoding: none');
		$basename!='' && $basename = basename($filename);
		$fileext  = substr(strrchr($basename,'.'),1);
		$filesize = filesize($filename);
		$attachment='attachment';
		header('Content-Disposition: '.$attachment.'; filename='.$basename);
		header('Content-type: '.$fileext);
		$filesize && header('Content-Length: '.$filesize);
		readfile($filename);
	}else{
		exit('Not found file!');	
	}
	exit;
}

function showmsg($msg,$jumpurl='',$style="msg",$t=3,$showthis=true){
	@extract($GLOBALS, EXTR_SKIP);
	!$style && $style="msg";
	$msg = str_replace("\r\n",'</li><li>',$msg);
	$msg = str_replace('\r\n','</li><li>',$msg);
	$msg = "<li>$msg</li>";
	$ifjump = '';
	if($showthis){
		ob_end_clean();
		include(DIR_FS_INCLUDES . 'application_top_gzip.php');
		!$_SERVER['HTTP_REFERER'] && $_SERVER['HTTP_REFERER'] = '/';
		!$jumpurl && $jumpurl=$_SERVER['HTTP_REFERER'];
		if(!ereg("^javascript:",$jumpurl)){
			$ifjump="<META HTTP-EQUIV='Refresh' CONTENT='$t; URL=$jumpurl'>";
		}else{
			$jsurl=true;
		}
		$breadcrumb && $breadcrumb->add(db_to_html('信息提示'),'javascript:void(0);');
		$content = 'showmsg';
		
		$BreadOff = false;
		if($seoNotHtml){
			$the_title = db_to_html($the_title);
			$the_desc = db_to_html($the_desc);
			$the_key_words = db_to_html($the_key_words);
		}
		require_once(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
		require_once(DIR_FS_INCLUDES . 'application_bottom.php');
		ob_end_flush();
		exit;
	}
	return $msg;
}

function cut_cnstr($str, $len = 80, $etc = ' ...'){
	$return_str = '';
    if($len>0){
		$sLen=strlen($str);
		if($len>=$sLen){
			$return_str = $str;
		}else{
			for($i=0;$i<($len-1);$i++){
				if(ord(substr($str,$i,1))>0xa0){
					$i++;
				}
			}
			
			if($i>=$len)
				$return_str = substr($str,0,$len);
			elseif(ord(substr($str,$i,1))>0xa0)
				$return_str = substr($str,0,$len-1);
			else
				$return_str = substr($str,0,$len);
				
			$return_str .= $etc;
		}
	}
	return $return_str;
}

function write_success_notes($out_time, $notes_content, $gotourl){
	$tpl_content = file_get_contents(DIR_FS_CONTENT . 'html_tpl/'.'out_time_notes.tpl.html');
	$tpl_content = str_replace('{notes_content}',$notes_content,$tpl_content);
	$tpl_content = str_replace('{out_time}',$out_time,$tpl_content);	
	$js_str = 'var gotourl = "'.$goto_url.'";
	var notes_contes = "'.addslashes($tpl_content).'";
	write_success_notes('.$out_time.', notes_contes, gotourl);';
	$js_str = preg_replace('/[[:space:]]+/',' ',$js_str);
	return $js_str;
}
?>