<?php
$action = false;
if($action == false){ die('http://wwww.led112.com/');}

set_time_limit(0);
require('includes/application_top.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>Untitled Document</title>
</head>

<body>
<?php
$images_dir = 'E:/my_work/led112/images/info/';
$images_dir_ws = 'images/info/';
//□擂跤堤腔url□腕厍桴腔啭□ㄛ妇嬷□砉
if(!(int)$_GET['info_tmp_id']){
	$info_tmp_id = 0;
}else{
	$info_tmp_id = (int)$_GET['info_tmp_id'];
}
$sql = tep_db_query("SELECT * FROM `info_tmp` WHERE info_tmp_id > ".(int)$info_tmp_id." AND info_tmp_get_content='0' Order By info_tmp_id limit 1 ");
$row = tep_db_fetch_array($sql);
$info_url = $row['info_url'];
$info_title = $row['info_title'];
$info_date = $row['info_date'];
$meta_title =$row['info_title'];
$meta_description=$row['info_title'];
$meta_keywords=$row['info_title'];
$info_type_id = $row['info_type_id'];
$info_tmp_id = $row['info_tmp_id'];

$url ='';
if(tep_not_null($info_url)){
	$url = $info_url;
}
//$url = 'http://www.cnledw.com/technique/detail-13546.htm';
if(!tep_not_null($url)){
	echo '诺厍□';
	exit;
}

$html=file_get_contents($url);
if($html==false){
	for($i=0; $i<10; $i++){
		$html=file_get_contents($url);
		if($html!=false){
			break;	
		}			
	}
	if($html==false){
		echo '□杨□腕:'.$html_addr.'腔啭□［';
	}
}

$html = eregi_replace("(\r|\n)", " ", $html);
$html = preg_replace("/[[:space:]]+/", " ", $html);
//$p = '/<div class="newtext">(.*)<\/div>/U';
$x ='/(^.+)(<div.+$)/Ui';
$y ='/(.+<\/div>)(.+)$/i';

$html = preg_replace($x, '$2', $html);
$html = preg_replace($y, '$1', $html);

//□壶<div class="newtext">□□腔□衄测钨
$html = preg_replace('/^.+<div class="newtext">/','',$html);
//□壶</div>缀腔□衄测钨
$html = preg_replace('/<\/div>.+$/i','',$html);
if(preg_match('/<div/',$html)){
	echo $url.' 逊衄□□壶腔divㄛ森□发□猁忒驮眸□［';
}

//□□□□


$p = '/<img(.*src="((.+\/)(.+(\.jpg|\.gif|\.png)))")(.+>)/Ui';

preg_match_all($p, $html, $images);

if(count($images[2])!=count($images[4])){
	echo '□□缭噤睿□□靡备□□祥□餍ㄐ';exit;
}
for($im=0; $im<count($images[2]); $im++){
	$images_l = $images[2][$im];
	if(!preg_match('/^http:\/\//',$images_l)){
		$images_l = preg_replace('/^(http:\/\/[^\/]+).+$/i','$1',$url) .$images[2][$im];
		
	}
	$copy_img = true;
	//□陔□烩$images[4][$im]
	$images[4][$im] = basename($images[4][$im]);
	//echo $images[4][$im];exit;
	if(!copy($images_l, $images_dir.$images[4][$im])){
		$copy_img = false;
		for($ima=0; $ima<10; $ima++){
			if(copy($images_l, $images_dir.$images[4][$im])){ $copy_img = true; break; }
		}
		if($copy_img == false){
			$error_msn = '□杨葩□□□'.$images_l.'善'.$images_dir.$images[4][$im].'<br>';
			$error_msn .= '□□厍□ㄩ'.$url.'<br>';
			$error_msn .= 'info_tmp_idㄩ'.$info_tmp_id;
			$error_array = array('error_msn'=>$error_msn);
			tep_db_perform('error_log', $error_array);
		}
	}
	if($copy_img == true){
		$html = str_replace($images[2][$im], $images_dir_ws.$images[4][$im], $html);
	}
}

//□遥厍□ □窒 遥伧 http://www.led112.com/
//echo utf_to_gb($html);

//□测钨善□擂踱
$date_array = array('info_content'=> tep_db_prepare_input(utf_to_gb($html)),
					'info_title'=>$info_title,
					'info_date'=>$info_date,
					'meta_title'=>$meta_title,
					'meta_description'=>$meta_description,
					'meta_keywords'=>$meta_keywords);

tep_db_perform('info', $date_array);
$info_id = tep_db_insert_id();

tep_db_query("INSERT INTO `info_to_type` ( `info_id` , `info_type_id` ) VALUES ('".$info_id."', '".(int)$info_type_id."');");
tep_db_query("UPDATE info_tmp SET info_tmp_get_content=1 WHERE info_tmp_id='".(int)$info_tmp_id."' ");

//tep_redirect(tep_href_link('get_url_content.php', 'info_tmp_id='.(int)$info_tmp_id));
echo '<meta http-equiv="refresh" content="1;URL=http://www.led112.com/get_url_content.php?info_tmp_id='.(int)$info_tmp_id.'">';

//□赵桶
//tep_db_query('OPTIMIZE TABLE `info_tmp` ');

?>
</body>
</html>
