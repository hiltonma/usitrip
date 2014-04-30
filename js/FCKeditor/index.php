<?php 
include('fckeditor.php') ;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
	<head>
		<title>FCKeditor</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="robots" content="noindex, nofollow">
	</head>
	<body>
		<h1>FCKeditor</h1>
		<p><a href="?toolbar=Basic">Basic</a> - <a href="?toolbar=Default">Default</a></p>
		<form action="posteddata.php" method="post">
<?php
$sBasePath = $_SERVER['PHP_SELF'] ;
$sBasePath = substr( $sBasePath, 0, strpos( $sBasePath, "_samples" ) ) ;

$oFCKeditor = new FCKeditor('FCKeditor1') ;
$oFCKeditor->BasePath	= $sBasePath ;
if ($_GET['toolbar'] == 'Basic') {
	$oFCKeditor->ToolbarSet = 'Basic';
}
$oFCKeditor->Value		= '<h2>FCKeditor 2.6 精简版第一版 by 4ngel (<a href="http://www.sablog.net/blog" target="_blank">http://www.sablog.net/blog</a>)</h2>
<p>该版本基于 FCKeditor 2.6 修改，旨在提高加载速度，删除不常用的功能，达到精简和优化的目的。</p>
<p>本次修改是基于源代码精简和优化再重新编译，保证功能正常的情况下，确保不残留任何垃圾、无用的代码。通过修改内容可以看出，几乎都是为了提高加载速度和提高实用程度而做的修改。Sablog-X和Sa系列程序将一直延续使用本人所精简的FCKeditor。</p>
<p><span style="color: rgb(255, 255, 255);"><span style="background-color: rgb(255, 0, 0);">FCKeditor 2.6 精简版第一版修改内容如下：</span></span></p>
<ol>
    <li>删除模板、打印、保存、拼写检查</li>
    <li>删除页面属性</li>
    <li>删除右键菜单功能</li>
    <li>删除表单功能</li>
    <li>删除文件上传和浏览服务器功能</li>
    <li>删除特殊字符功能</li>
    <li>删除选择更多颜色功能</li>
    <li>删除语言文件仅保留简体中文并精简语言包</li>
    <li>删除除默认意外的两个模板</li>
    <li>删除工具栏的折叠功能</li>
    <li>删除显示区块、全屏功能</li>
    <li>删除一些一般不会更改的配置选项及其连带功能</li>
    <li>删除N多判断</li>
    <li>优化显示工具栏使在各个浏览器下达到视觉统一</li>
    <li>精简链接、图片、FLASH插入功能，确保最精简的视觉和最快捷的操作</li>
    <li>多个细节调整</li>
</ol>
<p>第一版给需要一些常用的功能，但是又对加载速度要求很高的朋友或者程序开发人员。因此，这个版本并不算极度精简版。但是在速度和体积上，已经远远优于官方原版。以后有空将继续在此版本基础上进行精简，并作更大程度的优化。</p>
<p>FCKeditor版权归其开发团队所有。</p>
<p>有好的精简建议请联系我。如果有BUG，麻烦请看官方的演示，确保是精简造成的，也可以联系我。谢谢。</p>
<p><img src="http://www.sablog.net/blog/attachments/date_200802/78f297f2a935e00d010ff0deedc857c6.png" alt="" /></p>';
$oFCKeditor->Create() ;
?>
			<br />
			<input type="submit" value="Submit">
		</form>
	</body>
</html>