<?php //打开SESSION开关

//session_start();
//$_SESSION['MM_Username']="root";
//if($_SESSION['MM_Username']!="root"){ die("对不起，只有系统管理员才可以上传资料！");}
//配置文件

require('includes/application_top.php');

//require('includes/configure.php');	
$begin=microtime_float();
//检查用户模块

//require(DIR_FS_MODULES.'chk_user.php');	

//DM5码转换校验

//require(DIR_FS_FUNCTIONS.'password_funcs.php');
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />

<title>文件上传</title>
<link href="<?php echo DIR_FS_CSS;?>" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.disp {
	font-size: 9pt;
	overflow: auto;
	height: 400px;
	width: 800px;
	white-space: nowrap;
}
-->
</style>
</head>



<body>

<fieldset>
<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data" name="form1" id="form1">

<legend align="left">批量数据更新</legend>

  <table width="600" border="0" cellspacing="0" cellpadding="1">
    <tr>
      <td align="right" valign="middle">文件类型：</td>
      <td align="left" valign="middle"><input name="type" type="radio" disabled value="txt" />
        txt文本文件
        <input name="type" type="radio" value="csv" checked="checked" />
      Excel的csv文件</td>
    </tr>
	<tr>
      <td align="right" valign="middle">选择导入的档：</td>
      <td align="left" valign="middle"><input name="file_name" type="file" id="file_name" /></td>
    </tr>
    <tr>
      <td align="right" valign="middle">表格名：</td>
      <td align="left" valign="middle"><input name="table_name" type="text" id="table_name" value="<?php echo $table_name?>" /></td>
    </tr>
    <tr>
      <td align="right" valign="middle">条件字段：</td>
      <td align="left" valign="middle"><input name="key_field" type="text" id="key_field" value="<?php echo $key_field?>" /></td>
    </tr>
    <tr>
      <td align="right" valign="middle">要更新的字段：</td>
      <td align="left" valign="middle">
	  <textarea name="table_fields" cols="50" rows="10" wrap="virtual" id="table_fields"><?php if(isset($table_fields)){ echo $table_fields;}else{ echo "meta_title, meta_description, meta_keywords";}?></textarea></td>
    </tr>
    <tr>
      <td align="right" valign="middle">&nbsp;</td>
      <td align="left" valign="middle">从第
        <label for="begin_row">
        <input name="begin_row" type="text" id="begin_row" value="0" size="3" readonly="true" />
行开始导入</label></td>
    </tr>
    
    <tr>
      <td align="right" valign="middle">&nbsp;</td>
      <td align="left" valign="middle"><input type="submit" name="Submit" value="  Submit  " />
      <input name="Submit2" type="button" onclick="location='<?php echo tep_href_link('index.php');?>'" value="返回" /></td>
    </tr>
  </table>
  <br />
</form>
</fieldset>
  

      <div class="disp">
<?php
if($_POST['table_name']){
	if($_POST['type']=='' || !isset($_POST['type'])){ echo "错误，请选择档类型！";}
	if($_POST['type']=='txt'){	//导入txt文本文件
		////
		// 导入文本数据到数据库,以制表符\t为字段分隔符
		// $table_name为数据表名,$table_fields为数据表中的字段名称,字段之间用","号隔开,$file_name为上传的档域的名称,
		// $begin_row=0为从第一行开始导入,如果是第二行开始则为1,依次类推. $htmlspecialchars=1为转化特殊符号,0为不转
		if(get_text_to_data($table_name,$table_fields,'file_name',$begin_row,0) > 0){
			echo "档上传成功！";
		}else{
			echo "档上传失败。";
		}
	}
	if($_POST['type']=='csv'){ //导入CSV格式的檔
		$table_name=$_POST['table_name'];	//数据表名
		$field_key = trim(preg_replace('/,*$/','',$_POST['key_field']));	//条件字段
		$field_name=trim(preg_replace('/,*$/','',$_POST['table_fields']));	//字段名
		$field_count=count(explode(',',$field_name));
		
		$fname = $_FILES['file_name']['name'];    
		$do = copy($_FILES['file_name']['tmp_name'],$fname);    
		if ($do){     
			echo"（1）档上传成功<br>";     
		} else {    
			echo "";     
		}
		
		error_reporting(0);
		
		$connect=mysql_connect(DB_SERVER,DB_SERVER_USERNAME,DB_SERVER_PASSWORD) or die("could not connect to database");
		mysql_select_db(DB_DATABASE,$connect) or die (mysql_error());
		$fname = $_FILES['file_name']['name'];
		$handle=fopen("$fname","r");
		$j=1;
		
		//条件字段和更新字段集合
		$field_all=$field_key.','.$field_name;
		$field_array=explode(',',$field_all);
		
		//更新的字段中不能包含有条件字段
		$field_key_array=explode(',',$field_key);
		$field_name_array=explode(',',$field_name);
		foreach((array)$field_key_array as $val ){
			if(preg_match('/'.trim($val).'/i', $field_name)>=1){  echo "错误：更新的字段中含有条件字段！"; exit;}
		}
		//取得字段字名称和对应的值的编号
		$data_0 = fgetcsv($handle,10000,",");
		if(count($field_array)!=count($data_0)){ echo "错误：填写的字段总数：".count($field_array)." 与上传的字段总数：".count($data_0)." 不符！"; exit;}
		
		$field_key_id=array();
		$data_key_id=array();
		$field_name_id=array();
		$data_up_id=array();

		for($i=0; $i<count($data_0); $i++){
			for($j=0; $j<count($field_key_array); $j++){
				if(trim($data_0[$i])==trim($field_key_array[$j])){
					//确定哪个字段对准哪一列(条件部分)
					$field_key_id[] = $j;
					$data_key_id[] = $i;
					//echo $data_0[$i].'='.$field_key_array[$j]."<br />";
				}
			}
			for($k=0; $k<count($field_name_array); $k++){
				if(trim($data_0[$i])==trim($field_name_array[$k])){
					//确定哪个字段对准哪一列(更新字段部分)
					$field_name_id[] = $k;
					$data_up_id[] = $i;
					
					//echo $data_0[$i].'='.$field_name_array[$k]."<br />";
				}
			}
		}
		//print_r($field_key_id);
		//print_r($data_key_id);
		//print_r($field_name_id);
		//print_r($data_up_id);
		//exit;
		
		
		
		while($data = fgetcsv($handle,10000,",")){
			
			//设置条件
			$where=" ";
			foreach((array)$field_key_array as $key => $val ){
				$where.= trim($field_key_array[$field_key_id[$key]])." = '".trim($data[$data_key_id[$key]]). "' AND ";				
			}
			$where = preg_replace('/AND $/',' ', $where);
			
			//更新的值
			$values=" ";
			foreach((array)$field_name_array as $key => $val ){
				$values.= trim($field_name_array[$field_name_id[$key]])." = '".trim($data[$data_up_id[$key]]). "' ,";				
			}
			$values = preg_replace('/,$/',' ', $values);
		 
			$q="UPDATE $table_name SET $values WHERE $where;";
			mysql_query($q) or die (mysql_error());
			echo "<div>".$q."</div>";
			$j++;
		}
		
		if($j>1){ echo "（2）数据更新完毕！";}
		unset($j);
		fclose($handle);
	}
	$end=(int)((microtime_float()-$begin)*1000)/1000;
	echo "用时". $end."秒！";
}
?>
	  </div>

</body>
</html>