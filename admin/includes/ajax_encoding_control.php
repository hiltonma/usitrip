<?php
//echo $language;
if($language == "tchinese") {
	header("Content-type: text/html; charset=big5");
}elseif($language == "schinese") {
	header("Content-type: text/html; charset=gb2312");
}else{
	header("Content-type: text/html; charset=utf-8");
}
?>