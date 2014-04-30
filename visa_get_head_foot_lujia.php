<?php
$order   = array("\r\n", "\n", "\r");
$replace = '';
$url = 'http://www.usitrip.com/visa_get_head_foot.php?position='.$_GET['position'].'&UID='.(int)($_GET['UID']);

// 1. 初始化
$ch = curl_init();
// 2. 设置选项，包括URL
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// 发送用户名和密码
curl_setopt($ch, CURLOPT_USERPWD, "szusitripcz:hao1612$");
curl_setopt($ch, CURLOPT_HEADER, 0);
// 3. 执行并获取HTML文档内容
$output = curl_exec($ch);
// 4. 释放curl句柄
curl_close($ch);


$output=str_replace($order, $replace, $output);
$output = iconv('gb2312','UTF-8',$output);
$html = json_encode($output);

?>
document.write(<?php echo $html; ?>);