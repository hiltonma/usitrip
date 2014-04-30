<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>Untitled Document</title>
</head>

<body>
<?php
$html_addr = 'http://usa.bytravel.cn/v/52/2/';
$html=@file_get_contents($html_addr);

$html = eregi_replace("(\r|\n)", " ", $html);
$html = preg_replace("/[[:space:]]+/", " ", $html);
$html = preg_replace("/\<\/A\>/", "</A>\n", $html);

//$p = '/］\<A HREF="(.+)" class=blue14 target=\_blank\>\<strong\>(.+)\<\/strong\>\<\/A\>/';
//$p = '/］<A HREF="(.+)" class=blue14 target=\_blank><strong>(.+)<\/strong><\/A>/U';
$p = '/<A HREF="(.+)"(.+)><strong>(.+)<\/strong><\/A>/U';

preg_match_all($p, $html, $matches);


//print_r($matches);
for($i=0; $i<count($matches[1]); $i++){
	echo "Link $i:".$matches[1][$i]."\n<br>";
	echo "Title $i:".$matches[3][$i]."\n\n<br><br>";
}
echo 'end';

//get content
$url = 'http://usa.bytravel.cn/art/09n/09nyhszsyxnqy5dzx/';
$html=@file_get_contents($url);
$html = eregi_replace("(\r|\n)", " ", $html);
$html = preg_replace("/[[:space:]]+/", " ", $html);

$p = '/<table  width="100%" height="0" border="0" align="center" cellpadding="5" cellspacing="0" style="TABLE-LAYOUT: fixed">(.+)</table>/U';


exit;
$html ='<div><h1>111</h1><h1>222</h1></div>';
preg_match_all("/<h[^\>]+>(.*)<\/[^>]+>/U", $html, $strTag, PREG_PATTERN_ORDER);
print_r($strTag);
?>
</body>
</html>
