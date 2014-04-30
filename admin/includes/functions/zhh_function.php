<?php
//err msn
function err_msn($str='出错了！'){
	return '<span style="color:#FF0000">'.$str.'</span><br />';
}
//good msn
function ok_msn($str='正确！'){
	return '<span style="color:#0066FF">'.$str.'</span><br />';
}

//返回当前 Unix 时间戳和微秒数(四位小数)
function microtime_float()
{
    list($msec, $sec) = explode(" ", microtime());
    return ((float)$msec + (float)$sec);
}

/*------------replace find for char 改变搜索到的字元的底色-------------------*/
function char2c($char, $key) 
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
			if($i%2==0){ $color="#0000FF"; } 
			$nchar= str_replace($keysary[$i],"<font style='color:$color '>".$keysary[$i]."</font>",$nchar);
		}
		return $nchar;
   
   }
   else return $char;
 }

/**
 * 自动微缩图生成
 * @param $srcFile
 * @param $dstFile
 * @param $dstW
 * @param $dstH
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
 * 根据图像宽(高)度取得等比例的高(宽)度
 * @param $PathFile
 * @param $dstW
 * @param $dstH
 */
function getimgHW3hw($PathFile,$dstW,$dstH) //参数分别是路径档,设宽度和高度上限
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
$wh=" width='".$W."' height='".$H."'";
return $wh;	
}

/**
 * 将日期时间格式化成中文日期
 * @param $datetime 是带分秒的日期时间(必须有)
 * @param $datetype 是显示结束范围“D”或“T”"I"为分"H"为时
 * @param $type 代表遇到前面有0是否去掉
 */
function chardate($datetime, $datetype="T", $type="0")
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
		case "T": $chardate = $year."年".$month."月".$date."日".$hour."点".$minute."分".$second."秒"; break;
		case "I": $chardate = $year."年".$month."月".$date."日".$hour."点".$minute."分"; break;
		case "H": $chardate = $year."年".$month."月".$date."日".$hour."点"; break;
		default: $chardate = $year."年".$month."月".$date."日".$hour."点".$minute."分".$second."秒";
	}
	return $chardate;
}

/**
 * 以YYYY-MM-DD的形式格式化YYYY-M-D的日期
 * @param $date 
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
 * @param $date
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
 * @param unknown_type $date
 * @param unknown_type $n
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
?>
<?php 
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
 * @param $cutstring 文字
 * @param $cutno 显示的文字个数
 * @param $endstr 结尾的标识符
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
 * 文件上传函数，上传符合要求的档并返回上传后的文件名。
 * 	up_file($up_type, $up_size_max, $up_server_folder,$up_server_file_name,$up_file_name,$img,$imgpix,$imgpix_type)
 * 	@param $up_type设置上传类型 用逗号分隔			
	@param $up_size_max文件大小上限k*1024				
	@param $up_server_folder目标档夹目录			
	@param $up_server_file_name设置档案名首码(可选)	
	@param $up_file_name原始档案域的名称(可选)			
	@param $img,如果指定此参数则代表是上传图片(可选)
	@param $imgpix,如果指定此参数则代表限制图片的长宽最大图元大小(可选),格式为"长,宽"，如“"1024,768"”
	@param $imgpix_type,此项定义图图元的类型，1为上传的图片图元必须==$imgpix
 */
function up_file()
{
	
	$numargs = func_num_args();
	$arg_list = func_get_args();
	if($numargs > 8 || $numargs < 3 ){ echo "Warning：函数up_file参数不合要求，不能少於3个或多於8个！"; exit; }
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
	
		
	if($_FILES[$up_file_name]['name']!="")
	{
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
			
			//$imgmax = "对不起，只能上传".$up_type."类型的文件！";
			//echo $imgmax; exit;
			
			return false;
			//exit;
		}else
		{	
			$accept_overwrite = true;	//允许读写档
			if ($_FILES[$up_file_name]['size'] > $up_size_max) // 检查档大小
			{			
				//$imgmax = "对不起，你的文件大小为".ceil($_FILES[$up_file_name]['size']/1024)."K，大於".($up_size_max/1024)."K！上传失败。";		
				//echo $imgmax;  exit;
				return false;
				//exit;
			}else
			{	//进行上传档的操作
				
				if(@!move_uploaded_file($_FILES[$up_file_name]['tmp_name'],$up_server_folder . $newfile))
				{
					//$imgmax = "不明原因导致档上传失败!";
					//echo $imgmax ."__".$_FILES[$up_file_name]['tmp_name']."_____".$up_server_folder . $newfile; exit;
					
					return false;
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
					/* $imgmax = "对不起，你上传的图片无法显示，请上传有效的图片!"; 
					echo "<script>alert('$imgmax')</script>"; */
					return false;
				}			
			}
		}
		#检测图片图元是否超出指定围
		if($imgpix_type=='1'){
			$imgpix=explode(",",$imgpix);
			$img_info = @getimagesize($up_server_folder . $newfile);
			if($img_info[0] != $imgpix[0] || $img_info[1] != $imgpix[1])
			{
				if(@unlink($up_server_folder . $newfile)) {
					/* $imgmax = '图片上传失败。你的图片大小为'.$img_info[0]."*".$img_info[1].'图元，请确保图片等於'.$imgpix[0].'*'.$imgpix[1].'图元！';
					echo "<script>alert('$imgmax')</script>"; */
					return false; 
				}	
			}
			
		}elseif($imgpix){
			
			$imgpix=explode(",",$imgpix);
			$img_info = @getimagesize($up_server_folder . $newfile);
			if($img_info[0] > $imgpix[0] || $img_info[1] > $imgpix[1])
			{
				if(@unlink($up_server_folder . $newfile)) {
					/* $imgmax = '图片上传失败。你的图片大小为'.$img_info[0]."*".$img_info[1].'图元，请确保图片不大於'.$imgpix[0].'*'.$imgpix[1].'图元！';
					echo "<script>alert('$imgmax')</script>"; */
					return false; 
				}	
			}
		}
		
		@chmod($up_server_folder . $newfile, 0644);
		
		return $newfile;
	}
}

/**
 * 全角数字转半角数字
 * @param unknown_type $Number
 */
function NUM_num($Number)	
{
	if(eregi("０|１|２|３|４|５|６|７|８|９",$Number))
	{
		$array = array("０","１","２","３","４","５","６","７","８","９");
		for($i=0; $i<count($array); $i++) { $Number = ereg_replace($array[$i],"$i",$Number);}
	}
return $Number;
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

//用$_GET新值替换旧值，主要用於替换地址栏上的参数值
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

////
// 导入文本资料到资料库,以跳位字元\t为栏位分隔符号
// $table_name为资料表名,$table_fields为资料表中的栏位名称,栏位之间用","号隔开,$file_name为上传的档域的名称,
// $begin_row=0为从第一行开始导入,如果是第二行开始则为1,依次类推. $htmlspecialchars=1为转化特殊符号,0为不转
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

//取得客户端IP
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

//根据id判断某城市是不是有效的出发城市
function effective_starting_city($city_id){
	$sql = tep_db_query('SELECT  departure_city_id  FROM `products` WHERE departure_city_id="'.(int)$city_id.'" AND  products_status=1 limit 1');
	$row = tep_db_fetch_array($sql);
	if( (int)$row['departure_city_id']){
		return (int)$row['departure_city_id'];
	}else{
		$where_exc = ' AND (departure_city_id Like "'.(int)$city_id.'" || departure_city_id Like "'.(int)$city_id.',%" || departure_city_id Like "%,'.(int)$city_id.',%" || departure_city_id Like "%,'.(int)$city_id.'") ';
		$sql_1 = tep_db_query('SELECT  departure_city_id  FROM `products` WHERE  products_status=1 '.$where_exc.' limit 1');
		$row1 = tep_db_fetch_array($sql_1);
		return (int)$row1['departure_city_id'];
	}
}

//中文的星期替换英文的星期
function en_to_china_weeks($string){
	if(tep_not_null($string)){
		$pattern = array();			$replacement = array();
		$pattern[0] = '/Sun/';		$replacement[0] = SUNDAY;
		$pattern[1] = '/Mon/';		$replacement[1] = MONDAY;
		$pattern[2] = '/Tue/';		$replacement[2] = TUESDAY;
		$pattern[3] = '/Wed/';		$replacement[3] = WEDNESDAY;
		$pattern[4] = '/Thu/';		$replacement[4] = THURSDAY;
		$pattern[5] = '/Fri/';		$replacement[5] = FRIDAY;
		$pattern[6] = '/Sat/';		$replacement[6] = SATURDAY;
		
		return preg_replace($pattern, $replacement , $string);
	}else{
		return $string;
	}
}

//ajax 转换字符
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

//把xml文档的内容转成数组
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
    xml_parse_into_struct($parser, trim($contents), $xml_values);
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
 * 给图片加水印
 * @param string $srcFile　需要加上水印的图片
 * @param string $watermarkFile 水印图片
 * @param int $azimuth 水印添加位置[1左上2顶部中间3右上4左边中间5中间6右边中间7左下8底边中间9右下]
 * @param int $quality 图片品质1-100 100最完美的　当然1　就是最差的品质
 * @author lwkai 2012-06-26
 */
function makeCopyrightLwk($srcFile,$watermarkFile,$azimuth = 9,$quality = 100){
	// pictrues 图片  watermark 水印
	$imgArr = array(
			'pictrues' => array(	//原图
					'img' => '',	//　原图　/var/www/html/xxx/images/xxx.jpg
					'copyX' => 0,	//  原图需要从Ｘ轴开始切取　　　当然本过程中　不需要　从最左边开始　即0
					'copyY' => 0,	//　原图需要从Ｙ轴开始切取　　　当然本过程中　不需要　从最左边开始　即0
					'copyW' => 0,	//  原图需要切取Ｘ轴宽度　　　　当然本过程中　是全部　即整个图片的宽度
					'copyH' => 0,	//  原图需要切取Ｙ轴高度		　当然本过程中　是全部　即整个图片的高度
					'toX' => 0,		//  图片复制到新图的Ｘ轴坐标　　当然我们这个图是背景图　当然是最左边了　即0坐标
					'toY' => 0),	//  图片复制到新图的Ｙ轴坐标　　当然我们这个图是背景图　当然是最顶部了　即0坐标
			'watermark' => array(	//水印
					'img' => '',
					'copyX' => 0,
					'copyY' => 0,
					'copyW' => 0,
					'copyH' => 0,
					'toX' => 0,
					'toY' => 0));

	if (tep_not_null($srcFile) != true || tep_not_null($watermarkFile) != true) {
		return;
	}

	$imgArr['pictrues']['img'] = $srcFile;
	$imgArr['watermark']['img'] = $watermarkFile;

	// $srcArr 索引 0 宽度　1　高度　2　类型　3　宽高的HTML代码
	// Array ( [0] => 159 [1] => 103 [2] => 2 [3] => width="159" height="103" [bits] => 8 [channels] => 3 [mime] => image/jpeg )
	$srcArr = GetImageSize($imgArr['pictrues']['img']);
	$imgArr['pictrues']['copyW'] = $srcArr[0];
	$imgArr['pictrues']['copyH'] = $srcArr[1];

	$srcArr = GetImageSize($imgArr['watermark']['img']);
	$imgArr['watermark']['copyW'] = $srcArr[0];
	$imgArr['watermark']['copyH'] = $srcArr[1];

	// 算出水印位置
	switch($azimuth){
		case 1:// 左上角
			$imgArr['watermark']['toX'] = 0;
			$imgArr['watermark']['toY'] = 0;
			break;
		case 2:// 上边中间
			$imgArr['watermark']['toX'] = ($imgArr['pictrues']['copyW'] - $imgArr['watermark']['copyW']) / 2;
			$imgArr['watermark']['toY'] = 0;
			break;
		case 3://  右上角
			$imgArr['watermark']['toX'] = $imgArr['pictrues']['copyW'] - $imgArr['watermark']['copyW'];
			$imgArr['watermark']['toY'] = 0;
			break;
		case 4://　左边中间
			$imgArr['watermark']['toX'] = 0;
			$imgArr['watermark']['toY'] = ($imgArr['pictrues']['copyH'] - $imgArr['watermark']['copyH']) / 2;
			break;
		case 5://　中间
			$imgArr['watermark']['toX'] = ($imgArr['pictrues']['copyW'] - $imgArr['watermark']['copyW']) / 2;
			$imgArr['watermark']['toY'] = ($imgArr['pictrues']['copyH'] - $imgArr['watermark']['copyH']) / 2;
			break;
		case 6: //　右边中间
			$imgArr['watermark']['toX'] = $imgArr['pictrues']['copyW'] - $imgArr['watermark']['copyW'];
			$imgArr['watermark']['toY'] = ($imgArr['pictrues']['copyH'] - $imgArr['watermark']['copyH']) / 2;
			break;
		case 7://  左下角
			$imgArr['watermark']['toX'] = 0;
			$imgArr['watermark']['toY'] = $imgArr['pictrues']['copyH'] - $imgArr['watermark']['copyH'];
			break;
		case 8://　下边中间
			$imgArr['watermark']['toX'] = ($imgArr['pictrues']['copyW'] - $imgArr['watermark']['copyW']) / 2;
			$imgArr['watermark']['toY'] = $imgArr['pictrues']['copyH'] - $imgArr['watermark']['copyH'];
			break;
		case 9://  右下角
			$imgArr['watermark']['toX'] = $imgArr['pictrues']['copyW'] - $imgArr['watermark']['copyW'];
			$imgArr['watermark']['toY'] = $imgArr['pictrues']['copyH'] - $imgArr['watermark']['copyH'];
				
			break;
		default:
	}

	//print_r($imgArr);
	//新建一个真彩色图像
	$img = @ImageCreateTrueColor($imgArr['pictrues']['copyW'],$imgArr['pictrues']['copyH']);
	//为一幅图像分配颜色
	$white = ImageColorAllocate($img,255,255,255);
	//画一矩形并填充
	imagefilledrectangle($img,0,0,$imgArr['pictrues']['copyW'],$imgArr['pictrues']['copyH'],$white);// 填充背景色
	foreach ($imgArr as $k) {
		$wimage_data = GetImageSize($k['img']);
		switch($wimage_data[2])
		{
			case 1:
				$wimage=@ImageCreateFromGIF($k['img']);
				break;
			case 2:
				$wimage=@ImageCreateFromJPEG($k['img']);
				break;
			case 3:
				$wimage=@ImageCreateFromPNG($k['img']);
				break;
		}
		imagecopy($img,$wimage,$k['toX'],$k['toY'],$k['copyX'],$k['copyY'],$k['copyW'],$k['copyH']); //写入图片水印,水印图片大小默认为88*31
		imagedestroy($wimage);
	}

	ImageJpeg($img,$imgArr['pictrues']['img'],$quality);
	imagedestroy($img);
}



//生成带有水印的图片
//$srcFile原图\$srcLowFile水印素材\$dstFile目标图\$azimuth位置1-9，\$pct水印透明度\$word字符水印（不支持汉字）\$use_ttf使用ttf字体支持汉字
//注意$word字符必须要转成utf-8编码才能支持汉字
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

//检查html代码的完整性
function check_html_code($html_string,$show_error = true){
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
			if($show_error==true){
				echo htmlspecialchars($array[$i][0]."::".$array[$i][1])."::".$a.'::'.$bb.'<br>';
			}
			$return = false;
		}
	}
	return $return;
}

//检查html有问题的团
function check_html_code_for_tours(){
	$products_ids = array();  
	$fields = mysql_list_fields(DB_DATABASE,'products');
	$fields_array = array();
	while($r=mysql_fetch_field($fields)){  
        $fields_array[] = $r->name;  
	}
	$fields_array_count = count($fields_array);
	//print_r($fields_array);
	$sql = tep_db_query('SELECT * FROM `products` ');
	while($rows = tep_db_fetch_array($sql)){
		for($i=0; $i<$fields_array_count; $i++){
			if(check_html_code($rows[$fields_array[$i]], false) == false){
				$products_ids[]=$rows['products_id'];
				break;
			}
		}
	}
	
	$fields = mysql_list_fields(DB_DATABASE,'products_description');
	$fields_array = array();
	while($r=mysql_fetch_field($fields)){  
        $fields_array[] = $r->name;  
	}
	$fields_array_count = count($fields_array);
	//print_r($fields_array);
	$sql = tep_db_query('SELECT * FROM `products_description` ');
	while($rows = tep_db_fetch_array($sql)){
		for($i=0; $i<$fields_array_count; $i++){
			if(check_html_code($rows[$fields_array[$i]], false) == false){
				$products_ids[]=$rows['products_id'];
				break;
			}
		}
	}
	
	return array_unique($products_ids);
}

//取得当前页面绝对地址，含https
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

    $url=$uri_http.$url.$QUERY_STRING;

    return $url;
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
		default:
			$fileType = '';
			//echo 'unknown';
	}

	return $fileType;
	//return $fileType.' code:'.$typeCode;

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
 * lwk 打印一个对象到页面，当 IS_QA_SITES 为真才会有输出
 */
function lwk_print($str) {
	if (IS_QA_SITES) {
		echo '<pre>';
		print_r($str);
		echo '</pre>';
	}
}

/**
 * 根据联盟ID，取得联盟的支付信息
 * @param int $affilite_id 联盟ID
 * @return array
 */
function showAffiliatePaypalInfo($affilite_id){
	$sql = "select * from affiliate_affiliate where affiliate_id='" . intval($affilite_id) . "'";
	$result = tep_db_query($sql);
	$rs = tep_db_fetch_array($result);
	$rtn = array();
	if ($rs) {
		switch($rs['affiliate_default_payment_method']) {
			case "Paypal":
				// affiliate_payment_check  affiliate_payment_paypal  affiliate_mobile 手机号
				$rtn = array(
					'affiliate_default_payment_method' => 'Paypal',                        //
					'affiliate_payment_check'          => $rs['affiliate_payment_check'],  //收款人姓名(Paypal)
					'affiliate_payment_paypal'         => $rs['affiliate_payment_paypal'], //收款人的Paypal账号
					'affiliate_mobile'                 => $rs['affiliate_mobile']          //手机号
				);
				break;
			case "Bank":
				//affiliate_payment_bank_account_name  affiliate_payment_bank_name 收款人银行名称 affiliate_payment_bank_account_number 收款人银行账号
				$rtn = array(
					'affiliate_default_payment_method'      => 'Bank',
					'affiliate_payment_bank_account_name'   => $rs['affiliate_payment_bank_account_name'],   //收款人姓名(银行转账)
					'affiliate_payment_bank_name'           => $rs['affiliate_payment_bank_name'],           //收款人银行名称
					'affiliate_payment_bank_account_number' => $rs['affiliate_payment_bank_account_number'], //收款人银行账号
					'affiliate_mobile'                      => $rs['affiliate_mobile']                       //联系电话
				);
				break;
			case "Alipay":
				$rtn = array(
					'affiliate_default_payment_method' => 'Alipay',
					'affiliate_payment_alipay_name'    => $rs['affiliate_payment_alipay_name'],  //支付宝收款人姓名
					'affiliate_payment_alipay'         => $rs['affiliate_payment_alipay'],       //支付宝收款人姓名
					'affiliate_mobile'                 => $rs['affiliate_mobile']                //联系电话
				);
				break;
			default:
				$rtn = array(
					'affiliate_default_payment_method' => '未填写'
				);
		}
	}
	return $rtn;
}

/**
 * 取得支付信息，显示在TITLE
 * @param int $affiliate_id 联盟ID
 * @return string
 */
function showAffiliatePaypalInfoToTitle($affiliate_id) {
	$info = showAffiliatePaypalInfo($affiliate_id);
	$str = '';
	switch($info['affiliate_default_payment_method']) {
		case "Paypal":
			// affiliate_payment_check  affiliate_payment_paypal  affiliate_mobile 手机号
			$str = '支付方式:Paypal';                        //
			isset($info['affiliate_payment_check']) && $str .= "\r\n收款人姓名:" . $info['affiliate_payment_check'];  //收款人姓名(Paypal)
			isset($info['affiliate_payment_paypal']) && $str .= "\r\nPaypal账号:" . $info['affiliate_payment_paypal'];     //收款人的Paypal账号
			isset($info['affiliate_mobile']) && $str .= "\r\n联系电话:" . $info['affiliate_mobile'];   //手机号
			break;
		case "Bank":
			$str = '支付方式:银行转帐收款'; 
			isset($info['affiliate_payment_bank_account_name']) && $str .= "\r\n收款人姓名:" . $info['affiliate_payment_bank_account_name'];   //收款人姓名(银行转账)
			isset($info['affiliate_payment_bank_name']) && $str .= "\r\n银行名称:" . $info['affiliate_payment_bank_name'];           //收款人银行名称
			isset($info['affiliate_payment_bank_account_number']) && $str .= "\r\n银行账号:" . $info['affiliate_payment_bank_account_number']; //收款人银行账号
			isset($info['affiliate_mobile']) && $str .= "\r\n联系电话:" . $info['affiliate_mobile'];                       //联系电话
			break;
		case "Alipay":
			$str = '支付方式:支付宝';
			isset($info['affiliate_payment_alipay_name']) && $str .= "\r\n收款人姓名:" . $info['affiliate_payment_alipay_name'];  //支付宝收款人姓名
			isset($info['affiliate_payment_alipay']) && $str .= "\r\n支付宝帐号:" . $info['affiliate_payment_alipay'];       //支付宝收款人姓名
			isset($info['affiliate_mobile']) && $str .= "\r\n联系电话:" . $info['affiliate_mobile'];                       //联系电话
			break;
		default:
			$str = "未填写";
	}
	return $str;
}
?>