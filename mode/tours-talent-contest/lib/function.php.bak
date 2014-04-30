<?php

function makePager1($count,$pageurl,$pagename,$per,$dir=true){
     
	$page = $pagename;
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
			$ret .="<a href='$pageurl' class='go first {$ajaxclass}' mod='{$pagename}' val='1'></a>";
			$ret .= "<a href='".$pageurl.$pervpage."' class='go pre {$ajaxclass}' mod='{$pagename}' val='{$pervpage}'>".db_to_html('上一页')."</a>";
		}
		$ret .='<span>|';
		if($page>$sum-$next)$next=$next*2;
		$min=min($sum,$page+$next);
		for($i=$page-$next;$i <= $min;$i++){
			if($i<1){
				$i=1;
				$min = $next*2+1>$sum?$sum:$next*2+1;
			}
			$ret .= $i==$page ? "<b>$i</b>|" : "<a href='".$pageurl.$i."' class='{$ajaxclass}' mod='{$pagename}' val='{$i}'>$i</a>|";
		}
		$ret .='</span>';
		if($page<$sum){
			$ret .= "<a href='".$pageurl.$nextpage."' class='go next {$ajaxclass}' mod='{$pagename}' val='{$nextpage}'>".db_to_html('下一页')."</a>";
			$ret .= "<a href='".$pageurl.$sum."' class='go last {$ajaxclass}' mod='{$pagename}' val='{$sum}'></a>";
		}
	}else{
		$ret=" ";
	}
	return $ret;
}

/* php实现的js的 escape 和 unescape函数*/

function phpescape($str){  
    $sublen=strlen($str);  
    $reString="";  
    for ($i=0;$i<$sublen;$i++){  
        if(ord($str[$i])>=127){  
            $tmpString=bin2hex(iconv("GBK","ucs-2",substr($str,$i,2)));    //此处GBK为目标代码的编码格式，请实际情况修改  
            if (!eregi("WIN",PHP_OS)){  
                $tmpString=substr($tmpString,2,2).substr($tmpString,0,2);  
            }  
            $reString.="%u".$tmpString;  
            $i++;  
        } else {  
            $reString.="%".dechex(ord($str[$i]));  
        }  
    }  
    return $reString;  
}  


//处理JS escape 过来的中文
/*
function js_unescape($str)  
{  
    $ret = '';  
    $len = strlen($str);  
    for ($i = 0; $i < $len; $i++) {  
        if ($str[$i] == '%' && $str[$i+1] == 'u') {  
            $val = hexdec(substr($str, $i+2, 4));  
            if ($val < 0x7f) $ret .= chr($val);  
            else if($val < 0x800) $ret .= chr(0xc0|($val>>6)).chr(0x80|($val&0x3f));  
            else $ret .= chr(0xe0|($val>>12)).chr(0x80|(($val>>6)&0x3f)).chr(0x80|($val&0x3f));  
            $i += 5;  
        } else if ($str[$i] == '%') {  
            $ret .= urldecode(substr($str, $i, 3));  
            $i += 2;  
        } else $ret .= $str[$i];  
    }  
    return $ret;  
}
*/

function uh($str)
{ 
    $farr = array("/\s /","/<(\/?)(script|i?frame|style|html|body|title|link|meta|\?|\%)([^>]*?)>/isU", "/(<[^>]*)on[a-zA-Z] \s*=([^>]*>)/isU"); 
    $tarr = array(" ", "＜\\1\\2\\3＞","\\1\\2");
    $str = preg_replace( $farr,$tarr,$str); 
    return $str; 
}

/* 过滤不允许使用的html标签 */
/**
* 获取安全的html
*
* @param  string $html
* @return string
*/
 
function getSafeHtml($html)
{
$allowedTags = array(
'<a>', '<font>', '<span>', '<p>', '<br>', '<div>', '<li>', '<u>', '<strike>',
'<strong>', '<table>', '<tr>', '<td>', '<tbody>', '<hr>', '<blockquote>',
'<sub>', '<sup>', '<ul>', '<ol>', '<img>', '<b>', '<em>', '<h1>', '<h2>', '<h3>',
'<h4>', '<h5>', '<h6>', '<i>','<b>','<th>','<embed>'
);
return stripTagsAttributes($html, $allowedTags);
}
 
/**
* 过滤标签属性
*
* @param  string $html
* @param  array  $allowedTags
*/
function stripTagsAttributes($html, $allowedTags = array())
{
    if (empty($html)) return '';
    $disabledAttributes = array(
    'onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate',
    'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus',
    'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur',
    'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu',
    'oncontrolselect', 'oncopy', 'oncut', 'ondataavaible', 'ondatasetchanged',
    'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragdrop',
    'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart',
    'ondrop', 'onerror', 'onerrorupdate', 'onfilterupdate', 'onfinish', 'onfocus',
    'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup',
    'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter',
    'onmouseleave', 'onmousemove', 'onmoveout', 'onmouseover', 'onmouseup',
    'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste',
    'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend',
    'onresizestart', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll',
    'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit',
    'onunload');
 
    $html = preg_replace(
    '/<(.*?)>/ie',
    "'<' . preg_replace(array('/javascript:[^\"\']*/i', '/(" . implode('|', $disabledAttributes) . ")[ \\t\\n]*=[ \\t\\n]*[\"\'][^\"\']*[\"\']/i', '/\s+/'), array('', '', ' '), stripslashes('\\1')) . '>'",
    strip_tags($html, implode('', $allowedTags))
    );
    $html = preg_replace('/\s(' . implode('|', $disabledAttributes) . ').*?([\s\>])/', '\\2', $html); 
    return $html;
}

/**
 * 将字符串转换成unicode编码
 *
 * @param string $input
 * @param string $input_charset
 * @return string
 */
function str_to_unicode($input, $input_charset = 'gbk'){
 $input = iconv($input_charset, "gbk", $input);
 preg_match_all("/[\x80-\xff]?./", $input, $ar);
 $b = array_map('utf8_unicode_', $ar[0]);
 $outstr = join("", $b);
 return $outstr;
}

function utf8_unicode_($c, $input_charset = 'gbk'){
 $c = iconv($input_charset, 'utf-8', $c);
 return utf8_unicode($c);
}
// utf8 -> unicode
function utf8_unicode($c) {
 switch(strlen($c)) {
 case 1:
 return $c;
 case 2:
 $n = (ord($c[0]) & 0x3f) << 6;
 $n += ord($c[1]) & 0x3f;
 break;
 case 3:
 $n = (ord($c[0]) & 0x1f) << 12;
 $n += (ord($c[1]) & 0x3f) << 6;
 $n += ord($c[2]) & 0x3f;
 break;
 case 4:
 $n = (ord($c[0]) & 0x0f) << 18;
 $n += (ord($c[1]) & 0x3f) << 12;
 $n += (ord($c[2]) & 0x3f) << 6;
 $n += ord($c[3]) & 0x3f;
 break;
 }
 return "&#$n;";
}

/**
 * 将unicode字符转换成普通编码字符
 *
 * @param string $str
 * @param string $out_charset
 * @return string
 */
function str_from_unicode($str, $out_charset = 'gbk'){
 $str = preg_replace_callback("|&#([0-9]{1,5});|", 'unicode2utf8_', $str);
 $str = iconv("UTF-8", $out_charset, $str);
 return $str;
}

function unicode2utf8_($c){
 return unicode2utf8($c[1]);
}
function unicode2utf8($c){
 $str="";
 if ($c < 0x80) {
 $str.=$c;
 } else if ($c < 0x800) {
 $str.=chr(0xC0 | $c>>6);
 $str.=chr(0x80 | $c & 0x3F);
 } else if ($c < 0x10000) {
 $str.=chr(0xE0 | $c>>12);
 $str.=chr(0x80 | $c>>6 & 0x3F);
 $str.=chr(0x80 | $c & 0x3F);
 } else if ($c < 0x200000) {
 $str.=chr(0xF0 | $c>>18);
 $str.=chr(0x80 | $c>>12 & 0x3F);
 $str.=chr(0x80 | $c>>6 & 0x3F);
 $str.=chr(0x80 | $c & 0x3F);
 }
 return $str;
}

/**
 * 模拟JS里的unescape
 *
 * @param unknown_type $str
 * @return unknown
 */
function unescape($str) {
 $str = rawurldecode($str);
 preg_match_all("/(?:%u.{4})|&#x.{4};|&#\d+;|.+/U",$str,$r);
 $ar = $r[0];
 #print_r($ar);
 foreach($ar as $k=>$v) {
 if(substr($v,0,2) == "%u")
 $ar[$k] = iconv("UCS-2","GB2312",pack("H4",substr($v,-4)));
 elseif(substr($v,0,3) == "&#x")
 $ar[$k] = iconv("UCS-2","GB2312",pack("H4",substr($v,3,-1)));
 elseif(substr($v,0,2) == "&#") {
 echo substr($v,2,-1)."
";
 $ar[$k] = iconv("UCS-2","GB2312",pack("n",substr($v,2,-1)));
 }
 }
 return join("",$ar);
}


function Unescape1($str)
{
    
  $str = rawurldecode($str);
  //$str=str_replace("%B4","&curren;",$str);
  preg_match_all("/%u.{4}|&#x.{4};|&#\d+;|.+/U",$str,$r);
  $ar = $r[0];
  foreach($ar as $k=>$v)  
 {
  if(substr($v,0,2) == "%u")
  $ar[$k] = iconv("UCS-2","utf-8",pack("H4",substr($v,-4)));
  elseif(substr($v,0,3) == "&#x")
  $ar[$k] = iconv("UCS-2","utf-8",pack("H4",substr($v,3,-1)));
  elseif(substr($v,0,2) == "&#") {
  $ar[$k] = iconv("UCS-2","utf-8",pack("n",substr($v,2,-1)));
 }
}
return join("",$ar);


}//UNescape

function js_unescape($str)  
{  
    $ret = '';  
    $len = strlen($str);  
    for ($i = 0; $i < $len; $i++) {  
        if ($str[$i] == '%' && $str[$i+1] == 'u') {  
            $val = hexdec(substr($str, $i+2, 4));  
            if ($val < 0x7f) $ret .= chr($val);  
            else if($val < 0x800) $ret .= chr(0xc0|($val>>6)).chr(0x80|($val&0x3f));  
            else $ret .= chr(0xe0|($val>>12)).chr(0x80|(($val>>6)&0x3f)).chr(0x80|($val&0x3f));  
            $i += 5;  
        } else if ($str[$i] == '%') {  
            $ret .= urldecode(substr($str, $i, 3));  
            $i += 2;  
        } else $ret .= $str[$i];  
    }  
    return $ret;  
}  

/* 敏感词过滤 */
function filterwords($words=''){
    if (empty($words)) return '';
    if(is_file("./filterwords.txt")){
        $filter_words = file("./filterwords.txt");     
        for($i=0; $i<count($filter_words); $i++)
        {
             if(preg_match("/".trim($filter_words[$i])."/i", $words))
            {
                  echo "<script>alert('包含敏感词".$filter_words[$i]."');</script>";
                  exit;
            }
        }
    }
}

   
