<?php
require('includes/application_top.php');

//文件下载到本地 start{
if(tep_not_null($_GET['download']) && $_GET['download']=="1"){
	include_once(DIR_FS_CATALOG."includes/classes/download.php");
	$sql = tep_db_query('SELECT * FROM `zhh_system_words_annex` WHERE annex_id="'.(int)$_GET['annex_id'].'" ');
	$row = tep_db_fetch_array($sql);
	if(tep_not_null($row['annex_file_name'])){
		$file_name_base = explode('.', basename($row['annex_file_name']));
		$filename = tep_db_output(ascii2string($file_name_base[0],'_'));
		$realpath = preg_replace('/('.preg_quote('/','/').')+/','/',DIR_FS_CATALOG.$row['annex_file_name']);
		$extension_name = strtolower(preg_replace('/.+\.+/','',basename($realpath)));
		switch($extension_name){
			case 'xls'://直接浏览xls文件
				include_once (DIR_FS_CATALOG.'php-excel-reader-2.21/load_reader.php');
				$data = new load_cxcel_reader($realpath, 'gb2312');
				$data->output();
				echo '<noscript><iframe src="*.html"></iframe></noscript>';
				exit;
			break;
			case 'swf'://直接打开swf文件
				$url_address = str_replace(DIR_FS_DOCUMENT_ROOT, HTTP_SERVER.'/', $realpath);
				if(1){	//直接打开的方案
					$filename = $realpath;
					$filesize = filesize ($filename);
					$handle = fopen($filename, "r");
					$contents = fread($handle, $filesize);
					fclose($handle);
					header('Content-Type: application/x-shockwave-flash');
					header("Content-Length: ".$filesize);
					//header("Content-Disposition: inline; filename=".$filename);
					echo $contents;
					unset($contents);
				}else{	//嵌套后再打开
					
					$html = 
					'
					<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">  
					<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">  
					  <head>  
						<title>SWFObject - step 1</title>  
						<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />  
					  </head>  
					  <body>  
						<div>  
						  <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="1024" height="768">  
							<param name="movie" value="'.$url_address.'" />
							<!--[if !IE]>-->  
							<object type="application/x-shockwave-flash" data="'.$url_address.'" width="1024" height="768">  
							<!--<![endif]-->  
							  <p>Alternative content</p>  
							<!--[if !IE]>-->  
							</object>  
							<!--<![endif]-->  
						  </object>  
					  
						</div>  
					  </body>  
					</html>
					';
					echo $html;exit;
				}
				//echo '<noscript><iframe src="*.html"></iframe></noscript>';
				exit;
			break;
			default:	//下载
				download::ouput($realpath, $filename);
			break;
		}		
	}
	exit;
}
//文件下载到本地 end}
?>
