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

?>
<?php /*------------replace find for char 改变搜索到的字符的底色-------------------*/
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
			$nchar= eregi_replace($keysary[$i],"<font style='color:$color '>".$keysary[$i]."</font>",$nchar);
		}
		return $nchar;
   
   }
   else return $char;
 }
?>
<?php 
//自动微缩图生成
function makethumb($srcFile,$dstFile,$dstW,$dstH)
{

	$data = @getImagesize($srcFile);

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
	$ni   = @imagecreate($dstW, $dstH);
	@imagecopyresized($ni,$imgsrc,0,0,0,0,$dstW,$dstH,$srcW,$srcH);
	@imagegif($ni,$dstFile);
}

//根据图像宽(高)度取得成比例的高(宽)度
function getimgHW3hw($PathFile,$dstW,$dstH) //参数分别是路径文件,设宽度和高度上限
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

/*将日期时间格式化成中文日期，第一参数$datetime是带分秒的日期时间(必须有)，
第二参数$datetype是显示结束范围“D”或“T”"I"为分"H"为时,
第三参数$type是代表遇到前面有0是否去掉*/
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

//==>以YYYY-MM-DD的形式格式化YYYY-M-D的日期
function date2DATE($date)	
{
	if($date!="")
	{
		$date_arry = explode("-",$date);
		$check_year = intval($date_arry[0]) ;
		$check_month = intval($date_arry[1]) ;
		$check_day = intval($date_arry[2]) ;
		$checkdate = checkdate($check_month,$check_day,$check_year);
		if($checkdate!=1){ echo "不存在的日期" ; exit;}
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

?>
<?php 
/** 
* 将数字金额转换成中文大写数字 
* 例子: 231123.402 => 贰拾叁万壹仟壹佰贰拾叁元肆角整 
* 
* @param float $num 表示金额的浮点数 
* @return string 返回中文大写的字符串 
*/ 
function trans2rmb($num) 
{ 
    $rtn = ''; 
    $num = round($num, 2); 
    
    $s = array(); // 存储数字的分解部分 
    //==> 转化为字符串,$s[0]整数部分,$s[1]小数部分 
    $s = explode('.', strval($num)); 
    
    // 超过12位(大于千亿)则不予处理 
    if (strlen($s[0]) > 12) 
    { 
        return '*'.$num; 
    } 
    
    // 中文大写数字数组 
    $c_num = array('零', '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖'); 
    
    // 保存处理过程数据的数组 
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
  
    //==> 数字分为三截,四位一组,从右到左:元/万/亿,大于9位的数字最高位都归为"亿" 
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

//限制显示文字函数
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

//文件上传函数
	#作用：上传符合要求的文件并返回上传后的文件名。
	#up_file($up_type, $up_size_max, $up_server_folder,$up_server_file_name,$up_file_name,$img,$imgpix,$imgpix_type)
	#$up_type设置上传类型 用逗号分隔			
	#$up_size_max文件大小上限k*1024				
	#$up_server_folder目标文件夹目录			
	#$up_server_file_name设置文件名前缀(可选)	
	#$up_file_name源文件域的名称(可选)			
	#$img,如果指定此参数则代表是上传图片(可选)
	#$imgpix,如果指定此参数则代表限制图片的长宽最大像素大小(可选),格式为"长,宽"，如“"1024,768"”
	#$imgpix_type,此项定义图像素的类型，1为上传的图片像素必须==$imgpix

function up_file()
{
	
	$numargs = func_num_args();
	$arg_list = func_get_args();
	if($numargs > 8 || $numargs < 3 ){ echo "Warning：函数up_file参数不合要求，不能少于3个或多于8个！"; exit; }
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
		#取得文件扩展名类型
		$spildname = preg_replace("/^(.*\.)/","", $_FILES[$up_file_name]['name']); 	
		//$spildname = $spildname[count($spildname)-1];
		
		#设置完整的文件名并，使用strtolower函数将扩展名改成小写
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
			$accept_overwrite = true;	//允许读写文件
			if ($_FILES[$up_file_name]['size'] > $up_size_max) // 检查文件大小
			{			
				//$imgmax = "对不起，你的文件大小为".ceil($_FILES[$up_file_name]['size']/1024)."K，大于".($up_size_max/1024)."K！上传失败。";		
				//echo $imgmax;  exit;
				return false;
				//exit;
			}else
			{	//进行上传文件的操作
				
				if(@!copy($_FILES[$up_file_name]['tmp_name'],$up_server_folder . $newfile))
				{
					//$imgmax = "不明原因导致文件上传失败!";
					//echo $imgmax ."__".$_FILES[$up_file_name]['tmp_name']."_____".$up_server_folder . $newfile; exit;
					
					return false;
					//exit;
				} 
			}
		}
		
		//针对图片文件的检查
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
		#检测图片像素是否超出指定围
		if($imgpix_type=='1'){
			$imgpix=explode(",",$imgpix);
			$img_info = @getimagesize($up_server_folder . $newfile);
			if($img_info[0] != $imgpix[0] || $img_info[1] != $imgpix[1])
			{
				if(@unlink($up_server_folder . $newfile)) {
					/* $imgmax = '图片上传失败。你的图片大小为'.$img_info[0]."*".$img_info[1].'像素，请确保图片等于'.$imgpix[0].'*'.$imgpix[1].'像素！';
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
					/* $imgmax = '图片上传失败。你的图片大小为'.$img_info[0]."*".$img_info[1].'像素，请确保图片不大于'.$imgpix[0].'*'.$imgpix[1].'像素！';
					echo "<script>alert('$imgmax')</script>"; */
					return false; 
				}	
			}
		}
		
		$up_server_folder . $newfile;
		
		return $newfile;
	}
}

//全角数字转半角数字
function NUM_num($Number)	
{
	if(eregi("０|１|２|３|４|５|６|７|８|９",$Number))
	{
		$array = array("０","１","２","３","４","５","６","７","８","９");
		for($i=0; $i<count($array); $i++) { $Number = ereg_replace($array[$i],"$i",$Number);}
	}
return $Number;
}

//字符串转ASCII
function str_ascii($str){
	for($i=0;$i<strlen($str);$i++){  
		$ascii.= ord($str[$i])." ";
	}
	return trim($ascii);
}

//ASCII转字符串
function asscii_str($asscii){
	$array=explode(" ",$asscii);
	foreach ($array as $value){
		$str.=chr($value);
	}
	return $str;
}

//用$_GET新值替换旧值，主要用于替换地址栏上的参数值
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
// 导入文本资料到数据库,以制表符\t为字段分隔符
// $table_name为数据表名,$table_fields为数据表中的字段名称,字段之间用","号隔开,$file_name为上传的文件域的名称,
// $begin_row=0为从第一行开始导入,如果是第二行开始则为1,依次类推. $htmlspecialchars=1为转化特殊符号,0为不转
function get_text_to_data($table_name,$table_fields,$file_name,$begin_row=0,$htmlspecialchars=1){
	global $db;
	if($table_name==""||!isset($table_name)){die("function get_text_to_data() 错误:无数据表名！");}
	if(!$table_fields || !$file_name ){ die("function get_text_to_data() 错误:请检查函数get_text_data的参数!");}
	if($_FILES[$file_name]['name']!=""){
		define("DESTINATION_FOLDER", $servers_folder);
		//设置上传后的文件名
		$spildname = strtolower(preg_replace("/^.+\./","", $_FILES[$file_name]['name'])); 	//取得文件扩展名
		//判断文件类型
		if($spildname!="txt"){ 
			if(unlink($_FILES[$file_name]['tmp_name'])){ echo "文件已被删除!<br />";}
			die("function get_text_to_data() 对不起，你上传的 {$_FILES[$file_name]['name']} 不是文本文件。");
		}
		// 限制文件上传最大容量(bytes)
		$file_size_max = (1024*1024);
		if ($_FILES[$file_name]['size'] > $file_size_max) {// 检查文件大小
			if(unlink($_FILES[$file_name]['tmp_name'])){ echo "文件已被删除!<br />";}
			die("对不起，你的文件大小为".(int)($_FILES[$file_name]['size']/1024+1)."K，大于1M！上传失败。");
		}else{	//把文本内容写入数据库
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
					echo "function get_text_to_data() 请检查文本的第 $err_row 行是否有问题，要导入的数据与数据库字段总数不符！请检查制表符！<br />";
					echo "文本中的行数->".count($row_fields_array).": 已经填写的字段总数->".$fields_count;
					die();
				}
				//写入数据库
				$SQL = " INSERT INTO $table_name ($table_fields) ";
				$SQL.= " VALUES (";
				for($j=0; $j<$fields_count; $j++){
					$SQL.= $db->GetSQL($htmlspecialchars(trim($row_fields_array[$j]))).",";	//"'$k_name', '$k_incname', '$k_jw', '$k_dianhua', '$k_fax', '$k_GSM', '$k_email', '$k_url', '$k_addr', '$k_post'";
				}
				$SQL = preg_replace("/,$/","",$SQL).");";
				mysql_query($SQL);
				$sum+=mysql_affected_rows();
			}
			if(!unlink($_FILES[$file_name]['tmp_name'])){ echo "<br />文件删除失败!";}	//写入数据后删除已上传的文件
			return $sum;
		}
	}else { die("function get_text_to_data() 对不起，你没有选择文件！请单击“浏览”按钮选择文件再上传。"); }
}

?>