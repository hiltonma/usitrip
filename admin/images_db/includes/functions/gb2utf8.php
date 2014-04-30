<?
//Program writen by sadly www.phpx.com
//modified by agun 2000/6/20
//二次修改：VWsoft 2002.6.23 vwsoft.myetang.com vwsoft.yeah.net
//if(!extension_loaded('iconv')){
//	echo "你无法使用这个iconv扩展！";
	$gb_filename="gb2312.txt";
	$tmp_gbstr=file($gb_filename);
	$china_codetable=array();
	while(list($key,$value)=each($tmp_gbstr))
	{$china_codetable[hexdec(substr($value,0,6))]=substr($value,7,6);}
	
	
	function gb2utf8($gb)
	{
	global $china_codetable;   //码表作为全局变量
	if(!trim($gb))
	return $gb;
	$ret="";
	$utf8="";
	while($gb)
	{
	if (ord(substr($gb,0,1))>127)
	{
	$this=substr($gb,0,2);
	$gb=substr($gb,2,strlen($gb));
	$utf8=u2utf8(hexdec($china_codetable[hexdec(bin2hex($this))-0x8080]));
	for($i=0;$i<strlen($utf8);$i+=3)
	$ret.=chr(substr($utf8,$i,3));
	}
	else
	{
	$ret.=substr($gb,0,1);
	$gb=substr($gb,1,strlen($gb));
	}
	}
	return $ret;
	}
	
	function u2utf8($c)
	{
	for($i=0;$i<count($c);$i++)
	$str="";
	if ($c < 0x80) {
	$str.=$c;
	}
	else if ($c < 0x800) {
	$str.=(0xC0 | $c>>6);
	$str.=(0x80 | $c & 0x3F);
	}
	else if ($c < 0x10000) {
	$str.=(0xE0 | $c>>12);
	$str.=(0x80 | $c>>6 & 0x3F);
	$str.=(0x80 | $c & 0x3F);
	}
	else if ($c < 0x200000) {
	$str.=(0xF0 | $c>>18);
	$str.=(0x80 | $c>>12 & 0x3F);
	$str.=(0x80 | $c>>6 & 0x3F);
	$str.=(0x80 | $c & 0x3F);
	}
	return $str;
	}
//}
?>