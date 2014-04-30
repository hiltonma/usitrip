<?php
//err msn
function err_msn($str='出错了！'){
	return '<span style="color:#FF0000">'.$str.'</span><br />';
}
//good msn
function ok_msn($str='正确！'){
	return '<span style="color:#0066FF">'.$str.'</span><br />';
}

/**
 * 返回当前 Unix 时间戳和微秒数(四位小数)
 *
 * @return unknown
 */
function microtime_float()
{
	list($msec, $sec) = explode(" ", microtime());
	return ((float)$msec + (float)$sec);
}

/**
 * 根据关键词给源字符串添加html标识
 * @author Howard
 * @param unknown_type $source_string 源字符串
 * @param unknown_type $keyword 关键词
 * @param unknown_type $start_tag 添加的开头标识 默认<em>
 * @param unknown_type $end_tag 添加的结尾标识 默认</em>
 * @param unknown_type $charset 字符串编码 默认gb2312
 */
function keyword_add_css_to_string($source_string, $keyword, $start_tag='<em>',$end_tag='</em>', $charset='gb2312'){
	if($source_string=='' || $keyword=='') return $source_string;
	$array = mb_string_to_array($keyword, $charset);
	foreach($array as $val){
		$val = iconv($charset, 'utf-8//IGNORE',$val);
		$rep_array[$val] = $start_tag.$val.$end_tag;
	}
	$source_string = strtr(iconv($charset, 'utf-8//IGNORE', $source_string), $rep_array);
	$source_string = str_replace($end_tag.$start_tag , '', $source_string);
	
	//说明：以上内容一定要转成utf-8之后替换，否则容易出现乱码(出现的机率不大，但还是有)。
	$source_string = iconv('utf-8', $charset.'//IGNORE', $source_string);
	return $source_string;
}

/**
 * 改变搜索到的字元的底色（不好用，用上面那个函数）
 *
 * @param unknown_type $char
 * @param unknown_type $key
 * @param unknown_type $color_value
 * @return unknown
 */
function char2c_old($char, $key, $color_value = '#0000FF')
{
	if(isset($key) && $key!="")
	{
		$keys= trim(eregi_replace("  "," ",$key));
		$keys= eregi_replace(" ","|",$keys);
		$keysary= explode("|",$keys);
		$nchar=$char;
		for($i=0; $i<count($keysary); $i++)
		{
			$color="#00CC00";
			if($i%2==0){ $color=$color_value; }
			$nchar= str_replace($keysary[$i],"<font style='color:$color '>".$keysary[$i]."</font>",$nchar);
		}
		return $nchar;

	}
	else return $char;
}

function char2c($char, $key, $notuse='')
{
	return keyword_add_css_to_string($char, $key, '<i>', '</i>', CHARSET);
	/*if(isset($key) && $key!="")
	{
		$keys= trim(eregi_replace("  "," ",$key));
		$keys= eregi_replace(" ","|",$keys);
		$keysary= explode("|",$keys);
		$nchar=$char;
		for($i=0; $i<count($keysary); $i++)
		{
			if($i%2==0){ $color=$color_value; }
			$nchar= str_replace($keysary[$i],"<i>".$keysary[$i]."</i>",$nchar);
		}
		return $nchar;

	}
	else return $char;*/
}


/**
 * 自动微缩图生成
 *
 * @param unknown_type $srcFile
 * @param unknown_type $dstFile
 * @param unknown_type $dstW
 * @param unknown_type $dstH
 */
function makethumb($srcFile,$dstFile,$dstW,$dstH)
{

	$data = @getimagesize($srcFile);

	switch ($data[2])
	{
		case 1:
			$imgsrc = @imagecreatefromgif($srcFile);
			break;
		case 2:
			$imgsrc = imagecreatefromjpeg($srcFile);
			break;
		case 3:
			$imgsrc = @imagecreatefrompng($srcFile);
			break;
		default:
			return;
	}

	$srcW = @imagesx($imgsrc);
	$srcH = @imagesy($imgsrc);
	if ($dstH==0) $dstH = ($srcH/$srcW)*$dstW;
	if ($dstH*$srcW > $dstW*$srcH)
	{
		if($srcW<$dstW) $dstW=$srcW;
		$dstH=$dstW*$srcH/$srcW;
	}
	else
	{
		if($srcH<$dstH) $dstH=$srcH;
		$dstW=$srcW*$dstH/$srcH;
	}
	if(function_exists('imagecreatetruecolor')){
		$ni = @imagecreatetruecolor($dstW, $dstH);
	}else{
		$ni = @imagecreate($dstW, $dstH);
	}
	if(function_exists('imagecopyresampled')){
		@imagecopyresampled($ni,$imgsrc,0,0,0,0,$dstW,$dstH,$srcW,$srcH);//不失真
	}else{
		@imagecopyresized($ni,$imgsrc,0,0,0,0,$dstW,$dstH,$srcW,$srcH);//失真
	}

	@imagegif($ni,$dstFile);

	@imagedestroy($ni);
	@imagedestroy($imgsrc);

}

/**
 * 根据图像宽(高)度取得成比例的高(宽)度
 *
 * @param string $PathFile  图片完整绝对路径
 * @param unknown_type $dstW 最大宽度
 * @param unknown_type $dstH 最大高度
 * @param boolean $arr 是否以数组方式返回
 * @return string|array  eg: return array('width'=>100,'height'=>100)
 * @author by lwkai modify 2012-04-06
 */
function getimgHW3hw($PathFile,$dstW,$dstH,$arr=false) //参数分别是路径档,设宽度和高度上限
{
	$info = @getimagesize($PathFile);
	$imgw = $info[0];
	$imgh = $info[1];

	if ($imgw > $dstW || $imgh > $dstH) {
		if ($imgw > $imgh) {
			$newWidth = $dstW;
			$newHeight = round($newWidth / (($imgw / $imgh)));
		} elseif ($imgw < $imgh) {
			$newHeight = $dstH;
			$newWidth = round($newHeight / ($imgh / $imgw));
		} else {
			if ($dstW < $dstH) {
				$newWidth = $dstW;
				$newHeight = round($newWidth / (($imgw / $imgh)));
			} else {
				$newHeight = $dstH;
				$newWidth = round($newHeight / ($imgh / $imgw));
			}
		}
	} else {
		$newWidth = $imgw > 0 ? $imgw : $dstW;
		$newHeight = $imgh > 0 ? $imgh : $dstH;
	}

	// 原来的算法 start {
	/*if ( ($info[0] > $dstW) || ($info[1] > $dstH) ) //宽度比设置的大,或高度比设置的大
	{
	if(($info[0] - $info[1]) >= 0) //宽比高大
	{
	$H = round($info[1]/$info[0]*$dstW);
	$W = $dstW;
	}
	elseif(($info[0] - $info[1]) < 0) //宽比高小
	{
	$W = round($info[0]/$info[1]*$dstH);
	$H = $dstH;
	}
	}
	else
	{
	$W = ((int)$info[0] > 0 ? $info[0] : $dstW);
	$H = ((int)$info[1] > 0 ? $info[1] : $dstH);
	}
	if($H>$dstH){
	$wh=" height='".$dstH."'";
	}else{
	$wh=" width='".$W."' height='".$H."'";
	}*/
	// 原来的算法 end  }



	if ($arr == true) {
		$wh = array('width'=> $newWidth, 'height' => $newHeight);
	} else {
		$wh = " width='" . $newWidth . "' height='" . $newHeight . "'";
	}
	return $wh;
}

/**
 * 根据图像宽(高)度取得成比例的高(宽)度
 *
 * @param unknown_type $PathFile
 * @param unknown_type $dstW
 * @param unknown_type $dstH
 * @return unknown
 */
function getimgHW3hw_wh($PathFile,$dstW,$dstH) //参数分别是路径档,设宽度和高度上限
{
	$info = @getimagesize($PathFile);
	if ( ($info[0] > $dstW) || ($info[1] > $dstH) ) //宽度比设置的大,或高度比设置的大
	{
		if(($info[0] - $info[1]) >= 0) //宽比高大
		{
			$H = round($info[1]/$info[0]*$dstW);
			$W = $dstW;
		}
		elseif(($info[0] - $info[1]) < 0) //宽比高小
		{
			$W = round($info[0]/$info[1]*$dstH);
			$H = $dstH;
		}
	}
	else
	{
		$W = $info[0];
		$H = $info[1];
	}
	// $W = $dstW;
	// $H = $dstH;
	//$wh="@".$W."@".$H;
	$wh=$W."@".$H;
	return $wh;
}

/**
 * 将日期时间格式化成中文日期，第一参数$datetime是带分秒的日期时间(必须有)，
 * 第二参数$datetype是显示结束范围“D”或“S”"I"为分"H"为时,
 * 第三参数$type是代表遇到前面有0是否去掉
 *
 * @param unknown_type $datetime
 * @param unknown_type $datetype
 * @param unknown_type $type
 * @return unknown
 */
function chardate($datetime, $datetype="S", $type="0")
{
	if(!isset($type)) { $type = "0";}
	$year = substr($datetime,0,4);
	$month = substr($datetime,5,2);
	$date = substr($datetime,8,2);
	$hour = substr($datetime,11,2);
	$minute = substr($datetime,14,2);
	$second = substr($datetime,17,2);
	if($type!=0)
	{
		$month = intval($month);
		$date = intval($date);
		$hour = intval($hour);
		//$minute = intval($minute);
		//$second = intval($second);
	}
	switch ($datetype)
	{
		case "D": $chardate = $year."年".$month."月".$date."日"; break;
		case "S": $chardate = $year."年".$month."月".$date."日".$hour."点".$minute."分".$second."秒"; break;
		case "I": $chardate = $year."年".$month."月".$date."日".$hour."点".$minute."分"; break;
		case "H": $chardate = $year."年".$month."月".$date."日".$hour."点"; break;
		default:  $chardate = $year."年".$month."月".$date."日".$hour."点".$minute."分".$second."秒";
	}
	return $chardate;
}

/**
 * 判断某YYYY-MM-DD的日期是否是有效的日期
 *
 * @param unknown_type $date
 * @return unknown
 */
function check_date($date){
	if($date==''){ return false;}
	$date_arry = explode("-",$date);
	$check_year = intval($date_arry[0]) ;
	$check_month = intval($date_arry[1]) ;
	$check_day = intval($date_arry[2]) ;
	$checkdate = checkdate($check_month,$check_day,$check_year);
	return $checkdate;
}

/**
 * 以YYYY-MM-DD的形式格式化YYYY-M-D的日期
 *
 * @param unknown_type $date
 * @return unknown
 */
function date2DATE($date)
{
	if($date!="")
	{
		$date_arry = explode("-",$date);
		$check_year = intval($date_arry[0]) ;
		$check_month = intval($date_arry[1]) ;
		$check_day = intval($date_arry[2]) ;
		$checkdate = checkdate($check_month,$check_day,$check_year);
		if($checkdate!=1){ echo "不存在的日期".$date ; exit;}
		else
		{
			if(strlen($check_year)==4){ $date = $check_year;}

			if(strlen($check_month)==2){ $date .= "-".$check_month;}
			elseif(strlen($check_month)==1){ $date .= "-0".$check_month;}
			else { echo "月份错误"; exit; }

			if(strlen($check_day)==2){ $date .= "-".$check_day;}
			elseif(strlen($check_day)==1){ $date .= "-0".$check_day;}
			else { echo "日数错误"; exit;}
		}
		return $date;
	}
	else { echo "日期为空"; }
}

/**
 * 以YYYY-MM-DD的形式格式化MM/DD/YYYY的日期
 *
 * @param unknown_type $date
 * @return unknown
 */
function endate_to_dbdate($date){
	if(strlen(trim($date))!=10){return false;}
	$array = explode('/',$date);
	if(count($array)!=3){ return false;}
	$format_date = $array[2].'-'.$array[0].'-'.$array[1];
	return $format_date;
}

/**
 * 计算指定的日期时间相隔n个工作日后的日期时间，返回标准格式的日期和时间
 *
 * @param unknown_type $date
 * @param unknown_type $n
 * @return unknown
 */
function get_date_working_date($date='',$n=0){
	if($date=='') $date=date('Y-m-d H:i:s');
	$max_day_num = (int)$n;
	$sql = tep_db_query('SELECT * FROM `holiday` ');
	$date_array = array();
	$ii=0;
	while($rows=tep_db_fetch_array($sql)){
		$date_array[$ii] = $rows['start_date'];
		$ii++;
		if($rows['end_date']>$rows['start_date']){
			$daynum = (strtotime($rows['end_date'])-strtotime($rows['start_date']))/86400;
			for($i=1; $i<($daynum+1); $i++){
				$date_array[$ii] = date("Y-m-d", strtotime($rows['start_date'].'+'.$i.' day'));
				$ii++;
			}
		}
	}

	for($i=1; $i<($max_day_num+1); $i++){
		$date_loop = date("w", strtotime($date.'+'.$i.' day'));
		$date_day =  date("Y-m-d", strtotime($date.'+'.$i.' day'));

		if($date_loop=='0' || $date_loop=='6'){	//如果遇到周日和周六就自动加+1
			$max_day_num++;
		}elseif(in_array($date_day, $date_array)){ //如遇到非星期六和星期日的假日也自动加1
			$max_day_num++;
		}
	}

	$expired_date = date("Y-m-d H:i:s", strtotime($date.'+'.$max_day_num.' day'));
	return $expired_date;
}

/**
* 将数位金额转换成中文大写数位 
* 例子: 231123.402 => 贰拾三万壹仟壹佰贰拾三元肆角整 
* 
* @param float $num 表示金额的浮点数 
* @return string 返回中文大写的字串 
*/ 
function trans2rmb($num)
{
	$rtn = '';
	$num = round($num, 2);

	$s = array(); // 存储数位的分解部分
	//==> 转化为字串,$s[0]整数部分,$s[1]小数部分
	$s = explode('.', strval($num));

	// 超过12位(大於千亿)则不予处理
	if (strlen($s[0]) > 12)
	{
		return '*'.$num;
	}

	// 中文大写数位阵列
	$c_num = array('零', '壹', '贰', '三', '肆', '伍', '陆', '柒', '捌', '玖');

	// 保存处理过程资料的阵列
	$r = array();

	//==> 处理 分/角 部分
	if (!empty($s[1]))
	{
		$jiao = substr($s[1], 0,1);
		if (!empty($jiao))
		{
			$r[0] .= $c_num[$jiao].'角';
		}
		else
		{
			$r[0] .= '零';
		}

		$cent = substr($s[1], 1,1);
		if (!empty($cent))
		{
			$r[0] .=  $c_num[$cent].'分';
		}
	}

	//==> 数字分为三截,四位一组,从右到左:元/万/亿,大於9位元的数字最高位元都归为"亿"
	$f1 = 1;
	for ($i = strlen($s[0])-1; $i >= 0; $i--, $f1 ++)
	{
		$f2 = floor(($f1-1)/4)+1; // 第几截
		if ($f2 > 3)
		{
			$f2 = 3;
		}

		// 当前数字
		$curr = substr($s[0], $i, 1);

		switch ($f1%4)
		{
			case 1:
				$r[$f2] = (empty($curr) ? '零' : $c_num[$curr]).$r[$f2];
				break;
			case 2:
				$r[$f2] = (empty($curr) ? '零' : $c_num[$curr].'拾').$r[$f2];
				break;
			case 3:
				$r[$f2] = (empty($curr) ? '零' : $c_num[$curr].'佰').$r[$f2];
				break;
			case 0:
				$r[$f2] = (empty($curr) ? '零' : $c_num[$curr].'仟').$r[$f2];
				break;
		}
	}

	$rtn .= empty($r[3]) ? '' : $r[3].'亿';
	$rtn .= empty($r[2]) ? '' : $r[2].'万';
	$rtn .= empty($r[1]) ? '' : $r[1].'元';
	$rtn .= $r[0];

	if(empty($s[1])) //==>如果角和分部分都没有就以整为结尾
	{
		$rtn .= '整';
	}
	//==> 规则:如果位数为零,在"元"之前不出现"零",在空位处且不在"元"之间的,则填充一个"零"(num为0的情况除外)
	if ($num != 0)
	{
		while(1)
		{
			if (substr_count($rtn, "零零") == 0 && substr_count($rtn, "零元") == 0
			&& substr_count($rtn, "零万") == 0 && substr_count($rtn, "零亿") == 0)
			{
				break;
			}
			$rtn = str_replace("零零", "零", $rtn);
			$rtn = str_replace("零元", "元", $rtn);
			$rtn = str_replace("零万", "万", $rtn);
			$rtn = str_replace("零亿", "亿", $rtn);
		}
	}
	return $rtn;
}

/**
 * 限制显示文字函数
 *
 * @param unknown_type $cutstring
 * @param unknown_type $cutno
 * @param unknown_type $endstr
 * @return unknown
 */
function cutword($cutstring,$cutno,$endstr="..."){
	if(strlen($cutstring) > $cutno) {
		for($i=0;$i<$cutno;$i++) {
			$ch=substr($cutstring,$i,1);
			if(ord($ch)>127) $i++;
		}
		$cutstring= substr($cutstring,0,$i).$endstr;
	}
	return $cutstring;
}

/**
 * 文件上传函数
 * 作用：上传符合要求的档并返回上传后的档案名。
 * up_file($up_type, $up_size_max, $up_server_folder,$up_server_file_name,$up_file_name,$img,$imgpix,$imgpix_type, $show_error_sms)
 * $up_type设置上传类型 用逗号分隔
 * $up_size_max文件大小上限k*1024
 * $up_server_folder目标档夹目录
 * $up_server_file_name设置档案名首码(可选)
 * $up_file_name原始档案域的名称(可选)
 * $img,如果指定此参数则代表是上传图片(可选)
 * $imgpix,如果指定此参数则代表限制图片的长宽最大像素大小(可选),格式为"长,宽"，如“"1024,768"”
 * $imgpix_type,此项定义图像素的类型，1为上传的图片像素必须==$imgpix
 * $show_error_sms,如果出错，是否显示，1为显示，其它值为不显示
 * @return unknown
 * @author Howard
 */
function up_file(){

	$numargs = func_num_args();
	$arg_list = func_get_args();
	if($numargs > 9 || $numargs < 3 ){ echo "Warning：函数up_file参数不合要求，不能少於3个或多於9个！"; exit; }
	$up_type = $arg_list[0];
	$up_size_max =  $arg_list[1];
	$up_server_folder = $arg_list[2];
	$up_server_file_name = $arg_list[3];
	$up_file_name = $arg_list[4];
	if(!$arg_list[3]) { $up_server_file_name = date("YmdHis");}
	if(!$arg_list[4]){ $up_file_name = "file";}
	if($arg_list[5]) { $img = "Y";}
	if($arg_list[6]) { $imgpix = $arg_list[6];}
	if($arg_list[7]) { $imgpix_type = $arg_list[7];}
	if($arg_list[8]) { $show_error_sms = $arg_list[8];}

	$error_arary = array();

	if($_FILES[$up_file_name]['name']!=""){
		#取得档副档名类型
		$spildname = preg_replace("/^(.*\.)/","", $_FILES[$up_file_name]['name']);
		//$spildname = $spildname[count($spildname)-1];

		#设置完整的档案名并，使用strtolower函数将副档名改成小写
		$newfile = $up_server_file_name.".".strtolower($spildname);
		#判断上传的文件类型
		$up_type = strtolower($up_type);
		$up_type_array = explode(",",$up_type);
		for($i=0; $i<count($up_type_array); $i++)
		{
			if(strtolower($spildname) == strtolower($up_type_array[$i])){ $typeY_N = "Y";  break; } else { $typeY_N = "N"; }
		}
		if($typeY_N == "N")
		{

			$error_sms = "对不起，只能上传".$up_type."类型的文件！";
			$error_arary[] = $error_sms;
			//echo $error_sms; exit;

			//return false;
			//exit;
		}else
		{
			$accept_overwrite = true;	//允许读写档
			if ($_FILES[$up_file_name]['size'] > $up_size_max) // 检查档大小
			{
				$error_sms = "对不起，你的文件大小为".ceil($_FILES[$up_file_name]['size']/1024)."K，大于".($up_size_max/1024)."K！上传失败。";
				$error_arary[] = $error_sms;
				//return false;
				//exit;
			}else
			{	//进行上传档的操作

				if(@!move_uploaded_file($_FILES[$up_file_name]['tmp_name'],$up_server_folder . $newfile))
				{
					$error_sms = "不明原因导致档上传失败!"."__".$_FILES[$up_file_name]['tmp_name']."_____".$up_server_folder . $newfile;
					$error_arary[] = $error_sms;
					//echo $error_sms ."__".$_FILES[$up_file_name]['tmp_name']."_____".$up_server_folder . $newfile; exit;
					//return false;
					//exit;
				}
			}
		}

		//针对图片档的检查
		#检测图片是否有效
		if($img == "Y")
		{
			$img_info = @getimagesize($up_server_folder . $newfile);
			if($img_info[0] < 1 || $img_info[1] < 1)
			{
				if(@unlink($up_server_folder . $newfile)) {
					$error_sms = "对不起，你上传的图片无法显示，请上传有效的图片!";
					/*echo "<script>alert('$error_sms')</script>";*/
					$error_arary[] = $error_sms;
					//return false;
				}
			}
		}
		#检测图片像素是否超出指定围
		if($imgpix_type=='1'){
			$imgpix=explode(",",$imgpix);
			$img_info = @getimagesize($up_server_folder . $newfile);
			if($img_info[0] != $imgpix[0] || $img_info[1] != $imgpix[1])
			{
				if(@unlink($up_server_folder . $newfile)) {
					$error_sms = '图片上传失败。你的图片大小为'.$img_info[0]."*".$img_info[1].'像素，请确保图片等于'.$imgpix[0].'*'.$imgpix[1].'像素！';
					/*echo "<script>alert('$error_sms')</script>"; */
					$error_arary[] = $error_sms;
					//return false;
				}
			}

		}elseif($imgpix){

			$imgpix=explode(",",$imgpix);
			$img_info = @getimagesize($up_server_folder . $newfile);
			if($img_info[0] > $imgpix[0] || $img_info[1] > $imgpix[1])
			{
				if(@unlink($up_server_folder . $newfile)) {
					$error_sms = '图片上传失败。你的图片大小为'.$img_info[0]."*".$img_info[1].'像素，请确保图片不大於'.$imgpix[0].'*'.$imgpix[1].'像素！';
					$error_arary[] = $error_sms;
					/* echo "<script>alert('$error_sms')</script>"; */
					//return false;
				}
			}
		}

		@chmod($up_server_folder . $newfile, 0644);

		//print_r($error_arary);
		if((int)count($error_arary)){
			if($show_error_sms=='1'){
				return $error_arary;
			}else{
				return false;
			}
		}
		return $newfile;
	}
}

/**
 * 全角数字转半角数字
 *
 * @param unknown_type $Number
 * @return unknown
 */
function NUM_num($Number){
	$array = array("０","１","２","３","４","５","６","７","８","９");
	$array1 = array("0","1","2","3","4","5","6","7","8","9");
	$Number = str_replace($array, $array1,$Number);
	return $Number;
}

/**
 * 半角数字转全角数字
 *
 * @param unknown_type $Number
 * @return unknown
 */
function num2NUM($Number){
	$array = array("0","1","2","3","4","5","6","7","8","9");
	$array1 = array("０","１","２","３","４","５","６","７","８","９");
	$string = str_replace($array, $array1,$Number);
	return $string;
}

/*
//字串转ASCII
function str_ascii($str){
for($i=0;$i<strlen($str);$i++){
$ascii.= ord($str[$i])." ";
}
return trim($ascii);
}

//ASCII转字串
function asscii_str($asscii){
$array=explode(" ",$asscii);
foreach ($array as $value){
$str.=chr($value);
}
return $str;
}
*/

/**
 * 字符串转ASCII数字串
 * 	@param $str 任意字符串			
 * 	@param $separator 生成数字串后的分隔符			
 */
function string2ascii($str,$separator=" "){
	if($str=="") return false;
	$array=array();
	for($i=0; $i< strlen($str); $i++){
		$CurrentStr = $str[$i];
		if(ord($str[$i])>127){
			$CurrentStr = $str[$i].$str[++$i];
		}
		$a = (unpack("C*",$CurrentStr));
		$array[]=implode('',$a);
	}
	return implode(" ",$array);
}
/**
 * ASCII数字串转字符串
 * 	@param $num_str 源数字串			
 * 	@param $separator 分割符			
 */
function ascii2string($num_str, $separator=" "){
	if(!(int)$num_str) return false;
	$array = explode($separator, $num_str);
	$str = "";
	foreach($array as $val){
		if(strlen($val)==6){
			$str.=chr(substr($val,0,3)).chr(substr($val,3,3));
		}else{
			$str.=chr($val);
		}
	}
	return $str;
}

/**
 * 用$_GET新值替换旧值，主要用於替换地址栏上的参数值
 *
 * @param unknown_type $str_name
 * @return unknown
 */
function rep_str($str_name){
	$queryString = "";
	if (!empty($_SERVER['QUERY_STRING'])) {
		$params = explode("&", $_SERVER['QUERY_STRING']);
		$newParams = array();
		foreach ($params as $param) {
			if (stristr($param, $str_name ) == false ) {
				array_push($newParams, $param);
			}
		}
		if (count($newParams) != 0) {
			$queryString = "&amp;" . implode("&", $newParams);
		}
	}
	return preg_replace("/^&amp;/","?",$queryString);
}
/**
 * 导入文本资料到资料库,以跳位字元\t为栏位分隔符号
 * $table_name为资料表名,$table_fields为资料表中的栏位名称,栏位之间用","号隔开,$file_name为上传的档域的名称,
 * $begin_row=0为从第一行开始导入,如果是第二行开始则为1,依次类推. $htmlspecialchars=1为转化特殊符号,0为不转
 * @param unknown_type $table_name
 * @param unknown_type $table_fields
 * @param unknown_type $file_name
 * @param unknown_type $begin_row
 * @param unknown_type $htmlspecialchars
 * @return unknown
 */
function get_text_to_data($table_name,$table_fields,$file_name,$begin_row=0,$htmlspecialchars=1){
	global $db;
	if($table_name==""||!isset($table_name)){die("function get_text_to_data() 错误:无数据表名！");}
	if(!$table_fields || !$file_name ){ die("function get_text_to_data() 错误:请检查函数get_text_data的参数!");}
	if($_FILES[$file_name]['name']!=""){
		define("DESTINATION_FOLDER", $servers_folder);
		//设置上传后的档案名
		$spildname = strtolower(preg_replace("/^.+\./","", $_FILES[$file_name]['name'])); 	//取得档副档名
		//判断文件类型
		if($spildname!="txt"){
			if(unlink($_FILES[$file_name]['tmp_name'])){ echo "档已被删除!<br />";}
			die("function get_text_to_data() 对不起，你上传的 {$_FILES[$file_name]['name']} 不是文字档案。");
		}
		// 限制档上传最大容量(bytes)
		$file_size_max = (1024*1024);
		if ($_FILES[$file_name]['size'] > $file_size_max) {// 检查档大小
			if(unlink($_FILES[$file_name]['tmp_name'])){ echo "档已被删除!<br />";}
			die("对不起，你的文件大小为".(int)($_FILES[$file_name]['size']/1024+1)."K，大於1M！上传失败。");
		}else{	//把文本内容写入资料库
			$lines_array=file($_FILES[$file_name]['tmp_name']);
			$lines_rows=count($lines_array);
			if($htmlspecialchars==1){ $htmlspecialchars=htmlspecialchars;}	//转化特殊符号
			else{$htmlspecialchars=trim;}	//不转化特殊符号
			$fields_array=explode(",",$table_fields);
			$fields_count=count($fields_array);
			$sum=0;
			for($i=$begin_row; $i<$lines_rows; $i++){
				//检查每行是否符合规定(统计每行的\t数)
				$row_fields_array=explode("\t",$lines_array[$i]);
				if(count($row_fields_array)!=$fields_count){
					$err_row=$i+1;
					echo "function get_text_to_data() 请检查文本的第 $err_row 行是否有问题，要导入的资料与资料库栏位总数不符！请检查跳位字元！<br />";
					echo "文本中的行数->".count($row_fields_array).": 已经填写的栏位总数->".$fields_count;
					die();
				}
				//写入资料库
				$SQL = " INSERT INTO $table_name ($table_fields) ";
				$SQL.= " VALUES (";
				for($j=0; $j<$fields_count; $j++){
					$SQL.= $db->GetSQL($htmlspecialchars(trim($row_fields_array[$j]))).",";	//"'$k_name', '$k_incname', '$k_jw', '$k_dianhua', '$k_fax', '$k_GSM', '$k_email', '$k_url', '$k_addr', '$k_post'";
				}
				$SQL = preg_replace("/,$/","",$SQL).");";
				mysql_query($SQL);
				$sum+=mysql_affected_rows();
			}
			if(!unlink($_FILES[$file_name]['tmp_name'])){ echo "<br />档删除失败!";}	//写入资料后删除已上传的档
			return $sum;
		}
	}else { die("function get_text_to_data() 对不起，你没有选择档！请单击“流览”按钮选择档再上传。"); }
}

/**
 * 取得客户端IP
 *
 * @return unknown
 */
function get_client_ip(){
	if(getenv('HTTP_CLIENT_IP')){
		$client_ip = getenv('HTTP_CLIENT_IP');
	}elseif(getenv('HTTP_X_FORWARDED_FOR')){
		$client_ip = getenv('HTTP_X_FORWARDED_FOR');
	}elseif(getenv('REMOTE_ADDR')){
		$client_ip = getenv('REMOTE_ADDR');
	}else{
		$client_ip = $_SERVER['REMOTE_ADDR'];
	}
	return $client_ip;
}

/**
 * 根据id判断某城市是不是有效的出发城市
 *
 * @param unknown_type $city_id
 * @return unknown
 */
function effective_starting_city($city_id){
	$sql = tep_db_query('SELECT  departure_city_id  FROM `products` WHERE departure_city_id="'.(int)$city_id.'" AND  products_status=1 limit 1');
	$row = tep_db_fetch_array($sql);
	if( (int)$row['departure_city_id']){
		return (int)$row['departure_city_id'];
	}else{
		$where_exc = " and FIND_IN_SET('".(int)$city_id."', departure_city_id) ";
		$sql_1 = tep_db_query('SELECT  departure_city_id  FROM `products` WHERE  products_status=1 '.$where_exc.' limit 1');
		$row1 = tep_db_fetch_array($sql_1);
		return (int)$row1['departure_city_id'];
	}
}

/**
 * 中文的星期替换英文的星期
 *
 * @param unknown_type $string
 * @return unknown
 */
function en_to_china_weeks($string){
	if(tep_not_null($string)){
		$pattern = array();			$replacement = array();
		$pattern[0] = '/Sun/';		$replacement[0] = db_to_html("周日"); //SUNDAY;
		$pattern[1] = '/Mon/';		$replacement[1] = db_to_html("周一"); //MONDAY;
		$pattern[2] = '/Tue/';		$replacement[2] = db_to_html("周二"); //TUESDAY;
		$pattern[3] = '/Wed/';		$replacement[3] = db_to_html("周三"); //WEDNESDAY;
		$pattern[4] = '/Thu/';		$replacement[4] = db_to_html("周四"); //THURSDAY;
		$pattern[5] = '/Fri/';		$replacement[5] = db_to_html("周五"); //FRIDAY;
		$pattern[6] = '/Sat/';		$replacement[6] = db_to_html("周六"); //SATURDAY;

		return preg_replace($pattern, $replacement , $string);
	}else{
		return $string;
	}
}
/**
 * ajax 转换字符 将字符串转换为UTF8编码,设置 $dontSendHeader = true 将不会发送UTF8头
 * <br>要启用该函数请设置全局变量$ajax = true 
 * @param $general_str 要转换的字符串
 * @param $dontSendHeader 是否发送UTF8头 ,默认发送,
 */
function general_to_ajax_string($general_str, $dontSendHeader = false){
	global $ajax;
	if($ajax=='true'||($ajax==true && $ajax!='false')){
		//FIX add by vincent add to overwrite DefaultCharset for AJAX request
		if(!defined('FLAG_SEND_AJAX_HEADER') && $dontSendHeader === false) {
			header('Content-Type: text/html;charset=utf-8');
			define('FLAG_SEND_AJAX_HEADER',1);
		}
		//FIX end
		if (is_string($general_str)) {
			return iconv(CHARSET,'utf-8'.'//IGNORE',$general_str);
		} elseif (is_array($general_str)) {
			reset($general_str);
			while (list($key, $value) = each($general_str)) {
				$general_str[$key] = general_to_ajax_string($value, $dontSendHeader);
			}
			return $general_str;
		}
	}
	return $general_str;
}

function ajax_to_general_string($str_ajax){
	global $ajax;
	if($ajax=='true'||($ajax==true && $ajax!='false')){
		if (is_string($str_ajax)) {
			return iconv('utf-8',CHARSET.'//IGNORE',$str_ajax);
		} elseif (is_array($str_ajax)) {
			reset($str_ajax);
			while (list($key, $value) = each($str_ajax)) {
				$str_ajax[$key] = ajax_to_general_string($value);
			}
			return $str_ajax;
		}
	}
	return $str_ajax;
}

/**
 * str_to_html_entitiles - 转换字符串至 NCR 如:汉字 -> &#27721;&#23383;
 * mb_convert_encoding($a, 'HTML-ENTITIES', 'gb2312')
 * @param unknown_type $str
 * @param unknown_type $encode
 * @return unknown
 */
function str_to_html_entitiles($str, $encode = "gb2312"){
	$output = mb_convert_encoding($str, 'HTML-ENTITIES', $encode);
	return $output;
	
	/*
	if (!function_exists("iconv") || !function_exists("mb_strlen")) {
		return $str;
	}

	$str = iconv($encode, "utf-16", $str);

	for ($i = 0; $i < mb_strlen($str); $i+=2) {
		$code = ord($str{$i}) * 256 + ord($str{$i + 1});
		if ($code < 128) {
			$output .= chr($code);
		} else if ($code != 65279) {
			$output .= "&#" . $code . ";";
		}
	}
	*/
	

}

/**
 * 把xml文档的内容转成数组
 *
 * @param unknown_type $xml_file
 * @param unknown_type $get_attributes
 * @param unknown_type $priority
 * @return unknown
 */
function xml2array($xml_file, $get_attributes=1, $priority = 'tag',$contents = '') {
	if (strlen($contents)==0)
	{
		$contents = file_get_contents($xml_file);
	}

	if(!$contents) return array();

	if(!function_exists('xml_parser_create')) {
		//print "'xml_parser_create()' function not found!";
		return array();
	}

	//Get the XML parser of PHP - PHP must have this module for the parser to work
	$parser = xml_parser_create('');
	@xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss
	xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
	xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
	@xml_parse_into_struct($parser, trim($contents), $xml_values);
	xml_parser_free($parser);

	if(!$xml_values) return;//Hmm...

	//Initializations
	$xml_array = array();
	$parents = array();
	$opened_tags = array();
	$arr = array();

	$current = &$xml_array; //Refference

	//Go through the tags.
	$repeated_tag_index = array();//Multiple tags with same name will be turned into an array
	foreach($xml_values as $data) {
		unset($attributes,$value);//Remove existing values, or there will be trouble

		//This command will extract these variables into the foreach scope
		// tag(string), type(string), level(int), attributes(array).
		extract($data);//We could use the array by itself, but this cooler.

		$result = array();
		$attributes_data = array();

		if(isset($value)) {
			if($priority == 'tag') $result = $value;
			else $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
		}

		//Set the attributes too.
		if(isset($attributes) and $get_attributes) {
			foreach($attributes as $attr => $val) {
				if($priority == 'tag') $attributes_data[$attr] = $val;
				else $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
			}
		}

		//See tag status and do the needed.
		if($type == "open") {//The starting of the tag '<tag>'
			$parent[$level-1] = &$current;
			if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
				$current[$tag] = $result;
				if($attributes_data) $current[$tag. '_attr'] = $attributes_data;
				$repeated_tag_index[$tag.'_'.$level] = 1;

				$current = &$current[$tag];

			} else { //There was another element with the same tag name

				if(isset($current[$tag][0])) {//If there is a 0th element it is already an array
					$current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
					$repeated_tag_index[$tag.'_'.$level]++;
				} else {//This section will make the value an array if multiple tags with the same name appear together
					$current[$tag] = array($current[$tag],$result);//This will combine the existing item and the new item together to make an array
					$repeated_tag_index[$tag.'_'.$level] = 2;

					if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
						$current[$tag]['0_attr'] = $current[$tag.'_attr'];
						unset($current[$tag.'_attr']);
					}

				}
				$last_item_index = $repeated_tag_index[$tag.'_'.$level]-1;
				$current = &$current[$tag][$last_item_index];
			}

		} elseif($type == "complete") { //Tags that ends in 1 line '<tag />'
			//See if the key is already taken.
			if(!isset($current[$tag])) { //New Key
				$current[$tag] = $result;
				$repeated_tag_index[$tag.'_'.$level] = 1;
				if($priority == 'tag' and $attributes_data) $current[$tag. '_attr'] = $attributes_data;

			} else { //If taken, put all things inside a list(array)
				if(isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array...

					// ...push the new element into that array.
					$current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;

					if($priority == 'tag' and $get_attributes and $attributes_data) {
						$current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
					}
					$repeated_tag_index[$tag.'_'.$level]++;

				} else { //If it is not an array...
					$current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value
					$repeated_tag_index[$tag.'_'.$level] = 1;
					if($priority == 'tag' and $get_attributes) {
						if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well

							$current[$tag]['0_attr'] = $current[$tag.'_attr'];
							unset($current[$tag.'_attr']);
						}

						if($attributes_data) {
							$current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
						}
					}
					$repeated_tag_index[$tag.'_'.$level]++; //0 and 1 index is already taken
				}
			}

		} elseif($type == 'close') { //End of tag '</tag>'
			$current = &$parent[$level-1];
		}
	}

	return($xml_array);
}

/**
 * 生成带有水印的图片
 * $srcFile原图\$srcLowFile水印素材\$dstFile目标图\$azimuth位置1-9，\$pct水印透明度\$word字符水印（不支持汉字）\$use_ttf使用ttf字体支持汉字
 * 注意$word字符必须要转成utf-8编码才能支持汉字
 * @param unknown_type $srcFile
 * @param unknown_type $srcLowFile
 * @param unknown_type $dstFile
 * @param unknown_type $azimuth 位置1-9
 * @param unknown_type $pct
 * @param unknown_type $word
 * @param unknown_type $use_ttf
 * @param unknown_type $ttf_font
 * @return unknown
 */
function makeCopyright($srcFile,$srcLowFile="",$dstFile="",$azimuth=1,$pct=100,$word="",$use_ttf=false, $ttf_font = 12){
	$data = getimagesize($srcFile);
	if($data[0]<1 || $data[1]<1) { return 0; die(); }
	$srcW=$data[0];
	$srcH=$data[1];
	switch ($data[2]) {	//取得图片类型
		case 1:   $srcImg = @imagecreatefromgif($srcFile);  break;
		case 2:   $srcImg = @imagecreatefromjpeg($srcFile);  break;
		case 3:   $srcImg = @imagecreatefrompng($srcFile);  /*imagesavealpha($srcImg, true);*/  break;
	}
	if(function_exists('imagecreatetruecolor')){
		$dstimg=imagecreatetruecolor($data[0],$data[1]);//创建新图片并指定大小
	}else{
		$dstimg=imagecreate($data[0],$data[1]);
	}
	if(function_exists('imagecopyresampled')){
		imagecopyresampled($dstimg,$srcImg,0,0,0,0,$data[0],$data[1],$srcW,$srcH);	//不失真
	}else{
		imagecopyresized($dstimg,$srcImg,0,0,0,0,$data[0],$data[1],$srcW,$srcH); //失真
	}

	//取得要做水印的源图
	if($srcLowFile!=""){
		$dataLow = getimagesize($srcLowFile);
		switch ($dataLow[2]) {	//取得图片类型
			case 1:   $srcImgLow = @imagecreatefromgif($srcLowFile);  break;
			case 2:   $srcImgLow = @imagecreatefromjpeg($srcLowFile);  break;
			case 3:   $srcImgLow = @imagecreatefrompng($srcLowFile); /*imagealphablending($srcImgLow,false); imagesavealpha($srcImgLow, true); */ break;

		}
		$dst_x=0;
		$dst_y=0;
		switch($azimuth){
			case 1: $dst_x=0; $dst_y=0; break;	//左上角
			case 2: $dst_x=intval(($data[0]-$dataLow[0])/2); $dst_y=0; break; //中上
			case 3: $dst_x=intval($data[0]-$dataLow[0]); $dst_y=0; break; //右上角
			case 4: $dst_x=0; $dst_y=intval(($data[1]-$dataLow[1])/2); break; //左中
			case 5: $dst_x=intval(($data[0]-$dataLow[0])/2); $dst_y=intval(($data[1]-$dataLow[1])/2); break; //中心
			case 6: $dst_x=intval($data[0]-$dataLow[0]); $dst_y=intval(($data[1]-$dataLow[1])/2); break; //右中
			case 7: $dst_x=0; $dst_y=intval($data[1]-$dataLow[1]); break; //左下角
			case 8: $dst_x=intval(($data[0]-$dataLow[0])/2); $dst_y=intval($data[1]-$dataLow[1]); break; //中下
			case 9: $dst_x=intval($data[0]-$dataLow[0]); $dst_y=intval($data[1]-$dataLow[1]); break; //右下角
		}
	}
	@imagecopymerge($dstimg,$srcImgLow,$dst_x, $dst_y,0,0,$dataLow[0],$dataLow[1],$pct);	//最后一个是指透明度100为不透明


	//定义要写入的文字
	if($word!=""){
		$font=5;//字体
		$len=strlen($word)*9;
		$wordColor=imagecolorallocate($dstimg,0,0,0);//前景色
		$wordX=($data[0]-$len)/2;//x坐标
		$wordY=($data[1]-5)/2;//y坐标
		switch($azimuth){
			case 1: $wordX=0+$dataLow[0]+2; $wordY=0; break;	//左上角
			case 2: $wordX=intval(($data[0]-$len)/2)+2; $wordY=$dataLow[1]; break; //中上
			case 3: $wordX=($data[0]-$len)-$dataLow[0]-2; $wordY=0; break; //右上角
			case 4: $wordX=0; $wordY=intval(($data[1]+$dataLow[1])/2)-6; break; //左中
			case 5: $wordX=intval(($data[0]-$len)/2); $wordY=($data[1]+$dataLow[1])/2-6; break;  //中心
			case 6: $wordX=($data[0]-$len)-2; $wordY=($data[1]+$dataLow[1])/2-6; break; //右中
			case 7: $wordX=0+2+$dataLow[0]; $wordY=$data[1]-9-6; break; //左下角
			case 8: $wordX=intval(($data[0]-$len)/2); $wordY=$data[1]-9-6-$dataLow[1]; break; //中下
			case 9: $wordX=$data[0]-$len-2-$dataLow[0]; $wordY=$data[1]-9-6; break; //右下角
		}
		if($use_ttf==true){
			$font = $ttf_font;
			$ttffont = '/usr/share/fonts/chinese/TrueType/ukai.ttf';	//字体的路径
			@imagettftext($dstimg, $font, 0, $wordX, $wordY, $wordColor, $ttffont, $word);//写入文字(支持汉字)
		}else{
			@imagestring($dstimg,$font,$wordX,$wordY,$word,$wordColor);//写入文字
		}
	}

	if($dstFile!=""){
		switch ($data[2]) {	//输出图片到文件$dstFile
			case 1:   imagegif($dstimg,$dstFile);  break;
			case 2:   imagejpeg($dstimg,$dstFile,100);   break;
			case 3:   /*imagesavealpha($dstimg, true);*/ imagepng($dstimg,$dstFile);  break;
		}
	}else{	//输出图片到屏幕
		header("Content-type: image/png");
		header("Cache-Control: no-cache");
		switch ($data[2]) {
			case 1:   imagegif($dstimg);  break;
			case 2:   imagejpeg($dstimg,'',100);   break;
			case 3:   /*imagesavealpha($dstimg, true);*/ imagepng($dstimg);  break;
		}
	}

	@imagedestroy($dstimg);
	@imagedestroy($srcImg);
	@imagedestroy($srcImgLow);
	return 1;
}

//调用
//makeCopyright("http://howard-dev.usitrip.com/images/usa_all_banner_home.jpg","http://howard-dev.usitrip.com/image/logo.gif","123456logo.gif",9,30,"http://www.pconelie.com.cn.net.com");
//makeCopyright("94083.gif","","low_Sunset.jpg",9,100);

/**
 * 检查html代码的完整性
 *
 * @param unknown_type $html_string
 * @return unknown
 */
function check_html_code($html_string){
	$return = true;
	$array = array();
	$array[] = array('<div', '</div>');
	$array[] = array('<ul', '</ul>');
	$array[] = array('<li', '</li>');
	$array[] = array('<dd', '</dd>');
	$array[] = array('<dl', '</dl>');
	$array[] = array('<dt', '</dt>');
	$array[] = array('<tt', '</tt>');
	$array[] = array('<b', '</b>');
	$array[] = array('<strong', '</strong>');
	$array[] = array('<i', '</i>');
	$array[] = array('<em', '</em>');
	$array[] = array('<form', '</form>');
	$array[] = array('<small', '</small>');
	$array[] = array('<script', '</script>');
	$array[] = array('<style', '</style>');

	$array[] = array('<table', '</table>');
	$array[] = array('<tbody', '</tbody>');
	$array[] = array('<tr', '</tr>');
	$array[] = array('<td', '</td>');

	$array[] = array('<h1', '</h1>');
	$array[] = array('<h2', '</h2>');
	$array[] = array('<h3', '</h3>');
	$array[] = array('<h4', '</h4>');
	$array[] = array('<h5', '</h5>');
	$array[] = array('<h6', '</h6>');
	$array[] = array('<pre', '</pre>');

	$array[] = array('<p', '</p>');
	$array[] = array('<font', '</font>');
	$array[] = array('<span', '</span>');

	for($i=0; $i<count($array); $i++){
		$a = preg_match_all('/'.preg_quote($array[$i][0]).'[^\<rmn]/i',$html_string,$m);
		$bb = preg_match_all('/'.preg_quote($array[$i][1],'/').'/i',$html_string,$mm);
		if((int)$a!=(int)$bb){
			echo htmlspecialchars($array[$i][0]."::".$array[$i][1])."::".$a.'::'.$bb.'<br>';
			$return = false;
		}
	}
	return $return;
}

/**
 * 取得当前页面绝对地址，含https
 *
 * @return unknown
 */
function AbsoluteUrl() {
	GLOBAL $HTTP_SERVER_VARS;
	$HTTPS      =$HTTP_SERVER_VARS["HTTPS"];
	$HTTP_HOST =$HTTP_SERVER_VARS["HTTP_HOST"];
	$SCRIPT_URL =$HTTP_SERVER_VARS["SCRIPT_URL"];
	$PATH_INFO =$HTTP_SERVER_VARS["PATH_INFO"];
	$REQUEST_URI=$HTTP_SERVER_VARS["REQUEST_URI"];
	$SCRIPT_NAME=$HTTP_SERVER_VARS["SCRIPT_NAME"];

	$QUERY_STRING=$HTTP_SERVER_VARS["QUERY_STRING"];
	if (get_magic_quotes_gpc()==1) $QUERY_STRING=stripslashes($QUERY_STRING);
	if ($QUERY_STRING!="") $QUERY_STRING="?".$QUERY_STRING;

	$uri_http=(((strtolower($HTTPS)=="off")or($HTTPS==0)) ? 'http' : 'https') . '://' . $HTTP_HOST ;

	if (isset($SCRIPT_URL))
	$url=$SCRIPT_URL;

	else if (isset($PATH_INFO))
	$url = $PATH_INFO;

	else if (isset($REQUEST_URI))
	$url = $REQUEST_URI;

	else if (isset($SCRIPT_NAME))
	$url = $SCRIPT_NAME;

	//$url=$uri_http.$url.$QUERY_STRING;
	$url=$uri_http.$url;

	return $url;
}

/**
 *     判断是否为搜索引擎蜘蛛
 *
 *     @author     Eddy
 *     @return     bool
 */
function isCrawler() {
	$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
	if (!empty($agent)) {
		$spiderSite = array(
		"TencentTraveler",
		"Baiduspider+",
		"BaiduGame",
		"Googlebot",
		"msnbot",
		"Sosospider+",
		"Sogou web spider",
		"ia_archiver",
		"Yahoo! Slurp",
		"YoudaoBot",
		"Yahoo Slurp",
		"MSNBot",
		"Java (Often spam bot)",
		"BaiDuSpider",
		"Voila",
		"Yandex bot",
		"BSpider",
		"twiceler",
		"Sogou Spider",
		"Speedy Spider",
		"Google AdSense",
		"Heritrix",
		"Python-urllib",
		"Alexa (IA Archiver)",
		"Ask",
		"Exabot",
		"Custo",
		"OutfoxBot/YodaoBot",
		"yacy",
		"SurveyBot",
		"legs",
		"lwp-trivial",
		"Nutch",
		"StackRambler",
		"The web archive (IA Archiver)",
		"Perl tool",
		"MJ12bot",
		"Netcraft",
		"MSIECrawler",
		"WGet tools",
		"larbin",
		"Fish search",
		"AdsBot-Google",
		"Googlebot-Video",
		"Googlebot-News",
		"Googlebot-Image",
		"Googlebot-Mobile",
		"Mediapartners-Google",
		"Baiduspider",
		"Baiduspider-mobile",
		"Baiduspider-image",
		"Baiduspider-video",
		"Baiduspider-news",
		"Baiduspider-favo",
		"Baiduspider-cpro",
		"Baiduspider-ads",
		"Bingbot"
		);
		foreach ($spiderSite as $val) {
			$str = strtolower($val);
			if (strpos($agent, $str) !== false) {
				return $val;
			}
		}
	}

	return false;
}

/**
 * 将$url记录到访问历史记录中 并计数
 * 如果$url未设置则记录当前页面URL,
 * 如果$url或者$extra_msg包含汉字请自己做好 html_to_db转码操作
 * @param string $url 要计数的URL
 * @param boolean $just_count 只进行计数不保存历史
 * @param string $extra_msg 附加信息
 * @author vincent
 * @modify by vincent at 2011-5-25 下午01:59:05
 */
function tep_statistics_addpage($url = '',$extra_msg='',$just_count = false){
	if($url == '') $url = tep_current_url();
	$time = date('Y-m-d H:i:s' ,time());
	$url = tep_db_prepare_input($url);
	if($just_count == false){
		$insert_data = array(
		'url'=>$url ,
		'refer_url'=>tep_db_prepare_input($_SERVER['HTTP_REFERER']),
		'ip'=>get_client_ip(),
		'created'=>$time,
		'extra_msg'=>tep_db_prepare_input(strval($extra_msg)),
		);
		tep_db_perform('`statistics_pageview_history`', $insert_data,'insert');
	}

	$sql = "UPDATE `statistics_pageview_counter` SET total=total+1 ,last_time = '".$time."' WHERE url='".$url."' ";
	tep_db_query($sql);
	if(tep_db_affected_rows() == 0 ){
		$sql = "INSERT INTO `statistics_pageview_counter` (url,total ,last_time)VALUES('".$url."',1,'".$time."') ";
		tep_db_query($sql);
	}
}

/**
 * 取得一组数字的所有不重复的排列组合
 * @param $Result 保存结果的数组变量
 * @param $source 数字的来源数据(array)
 * @param $startKey 起点key，如0
 * @param $endKey 结束点的key，如sizeof($s)-1
 * @return 数组
 * @author Howard
 * @modify by Howard at 2011-07-13 10:54:05
 */
function uniqueCombinationNumber(&$Result, $source, $startKey , $endKey) {
	if ($startKey == $endKey) {
		$tmp_str = "";
		for ($i = 0; $i <= $endKey; $i++) {
			$tmp_str.=$source[$i];
		}
		$Result[] = $tmp_str;
	} else {
		$temp = NULL;
		for ($i = $startKey; $i <= $endKey; $i++) {
			$temp = $source[$i];
			$source[$i] = $source[$startKey];
			$source[$startKey] = $temp;

			uniqueCombinationNumber($Result, $source, $startKey + 1, $endKey);

			$temp = $source[$i];
			$source[$i] = $source[$startKey];
			$source[$startKey] = $temp;
		}
	}
	return $Result;
}

/**
 * 两个日期相减
 * @param unknown_type $date1
 * @param unknown_type $date2
 * @param $format
 */
function date1SubDate2($date1,$date2,$format="day"){
	$num = 0;
	//if($format=="day"){ $num=($num/86400);}	此方法用在美国时间有问题的，如夏令时切换时有1小时的差距
	if($format=="day"){	//返回天
		$date1 = date('Y-m-d', strtotime($date1));
		$date2 = date('Y-m-d', strtotime($date2));
		$num = round((strtotime($date1)-strtotime($date2))/86400);	//四舍五入能完美解决夏令时、冬令时转换的问题
	}else{	//返回秒
		$num = strtotime($date1)-strtotime($date2);
	}
	return $num;
}

/**
 * 读取文件的真实类型
 *
 * @param unknown_type $filename 完整的文件路径
 * @return unknown
 */
 
function get_file_type($filename){
	if(!file_exists($filename)) return '';
	$file = fopen($filename, "rb");
	$bin = fread($file, 2); //只读2字节
	fclose($file);
	$strInfo = @unpack("C2chars", $bin);
	$typeCode = intval($strInfo['chars1'].$strInfo['chars2']);
	$fileType = '';
	switch ($typeCode){
		case 7790:
			$fileType = 'exe';
			break;
		case 7784:
			$fileType = 'midi';
			break;
		case 8297:
			$fileType = 'rar';
			break;
		case 255216:
			$fileType = 'jpg';
			break;
		case 7173:
			$fileType = 'gif';
			break;
		case 6677:
			$fileType = 'bmp';
			break;
		case 13780:
			$fileType = 'png';
			break;
		case 9168:
			$fileType = 'ini';
		case 3780:
			$fileType = 'pdf';
			break;
		case 208207:
			$fileType = 'doc';
			break;
		case 8075:
			$fileType = 'zip';
			break;
		case 6980:
			$fileType = 'mdi';
			break;
		default:
			$fileType = '';
			//echo 'unknown';
	}

	return $fileType;
	//return $fileType.' code:'.$typeCode;

}

function get_file_type2($filename){
	if(!file_exists($filename)) return '';
	$fileType = get_file_type($filename);
	if($fileType==''){
		$file = fopen($filename, "rb");
		$bin = fread($file, 2); //只读2字节
		fclose($file);
		$strInfo = @unpack("C2chars", $bin);
		$fileType = intval($strInfo['chars1'].$strInfo['chars2']);
	}
	return $fileType;
}

/**
 * 将字符串（中英文均可）切换成数组
 *
 * @param unknown_type $str 源字符串
 * @param unknown_type $charset 字符串的编码，如gb2312,utf-8等
 * @return array
 */
function mb_string_to_array($str,$charset) {
	$strlen = mb_strlen($str);
	if(!(int)$strlen) return false;
	while($strlen){
		$array[] = mb_substr($str,0,1,$charset);
		$str = mb_substr($str,1,$strlen,$charset);
		$strlen = mb_strlen($str);
	}
	return $array;
}

/**
 * 清除数组中值的首尾空格
 *
 * @param unknown_type $string_or_array 待处理的字符串或数组
 * @param unknown_type $charlist 可选参数，过滤字符也可由 charlist 参数指定。
 * 如果不指定第二个参数，trim() 将去除这些字符：
 * " " (ASCII 32 (0x20))，普通空格符。
 * "\t" (ASCII 9 (0x09))，制表符。 
 * "\n" (ASCII 10 (0x0A))，换行符。 
 * "\r" (ASCII 13 (0x0D))，回车符。 
 * "\0" (ASCII 0 (0x00))，空字节符。 
 * "\x0B" (ASCII 11 (0x0B))，垂直制表符。 
 */
function array_trim($string_or_array, $charlist=''){
	if(is_string($string_or_array)){
		if($charlist==''){
			$string_or_array = trim($string_or_array);
		}else{
			$string_or_array = trim($string_or_array, $charlist);
		}
	}elseif (is_array($string_or_array)){
		reset($string_or_array);
		while (list($key, $value) = each($string_or_array)) {
			$string_or_array[$key] = array_trim($value, $charlist);
		}
	}
	return $string_or_array;
}

/**
 * 半角数字和英文转全角
 * @param string $str 待转的字符
 * @param string $charset 源字符编码(目前只支持gb2312)
 * @return string
 * @author lwk by 2013-05-24
 */
function width_half_to_full($str, $charset='gb2312') {
	$arr = array (
			'a' => 'ａ',
			'b' => 'ｂ',
			'c' => 'ｃ',
			'd' => 'ｄ',
			'e' => 'ｅ',
			'f' => 'ｆ',
			'g' => 'ｇ',
			'h' => 'ｈ',
			'i' => 'ｉ',
			'j' => 'ｊ',
			'k' => 'ｋ',
			'l' => 'ｌ',
			'm' => 'ｍ',
			'n' => 'ｎ',
			'o' => 'ｏ',
			'p' => 'ｐ',
			'q' => 'ｑ',
			'r' => 'ｒ',
			's' => 'ｓ',
			't' => 'ｔ',
			'u' => 'ｕ',
			'v' => 'ｖ',
			'w' => 'ｗ',
			'x' => 'ｘ',
			'y' => 'ｙ',
			'z' => 'ｚ',
			'A' => 'Ａ',
			'B' => 'Ｂ',
			'C' => 'Ｃ',
			'D' => 'Ｄ',
			'E' => 'Ｅ',
			'F' => 'Ｆ',
			'G' => 'Ｇ',
			'H' => 'Ｈ',
			'I' => 'Ｉ',
			'J' => 'Ｊ',
			'K' => 'Ｋ',
			'L' => 'Ｌ',
			'M' => 'Ｍ',
			'N' => 'Ｎ',
			'O' => 'Ｏ',
			'P' => 'Ｐ',
			'Q' => 'Ｑ',
			'R' => 'Ｒ',
			'S' => 'Ｓ',
			'T' => 'Ｔ',
			'U' => 'Ｕ',
			'V' => 'Ｖ',
			'W' => 'Ｗ',
			'X' => 'Ｘ',
			'Y' => 'Ｙ',
			'Z' => 'Ｚ',
			'1' => '１',
			'2' => '２',
			'3' => '３',
			'4' => '４',
			'5' => '５',
			'6' => '６',
			'7' => '７',
			'8' => '８',
			'9' => '９',
			'0' => '０' 
	);
	if ($str || $str == '0') {
		$str = strtr($str, $arr);
	}
	return $str;
}

/**
 * 注册的时候不允许出现的字符串
 * @author lwkai 2013-08-02
 * @return array
 */
function reg_filter() {
	$rtn = array('usitrip','ustrip','走四方');
	return $rtn;
}

/**
 * 检测一个产品是否是美东栏目内的，并且是买二送二,是返回真，否则返回假
 * @param $regions_id 栏目ID
 * @param $tour_type_icon 优惠标签
 * @return boolean
 */
function checkIsEastBuyTwoGetTwo($regions_id,$tour_type_icon) {
	$isMeiDong = false;
	$isBuyTwoGetTwo = true;
	if (tep_not_null($regions_id)) {
		$sql = "select regions_name from regions_description where regions_id='" . intval($regions_id) . "'";
		$result = tep_db_query($sql);
		$rs = tep_db_fetch_array($result);
		if ($rs && $rs['regions_name'] == '美东') {
			$isMeiDong = true;
		}
	}
	if (strpos($tour_type_icon,'buy2-get-2') === false) {
		$isBuyTwoGetTwo = false;
	}
	return ($isMeiDong && $isBuyTwoGetTwo);
}
?>