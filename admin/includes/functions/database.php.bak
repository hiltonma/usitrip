<?php
/*
$Id: database.php,v 1.1.1.1 2004/03/04 23:39:50 ccwjr Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2003 osCommerce

Released under the GNU General Public License

define('CLIENT_MULTI_RESULTS', 131072);
$link=mysql_connect("127.0.0.1","root","",1,CLIENT_MULTI_RESULTS)ordie("Could not connect: ".mysql_error());

*/
$_SQL_QUERY_NUM = 0;
function tep_db_connect($server = DB_SERVER, $username = DB_SERVER_USERNAME, $password = DB_SERVER_PASSWORD, $database = DB_DATABASE, $link = 'db_link') {
	global $$link;
	//die($server.$username.$password.$database);
	if (USE_PCONNECT == 'true') {
		$$link = mysql_pconnect($server, $username, $password, 131072);
	} else {
		$$link = mysql_connect($server, $username, $password, 1, 131072);	//支持存储过程
	}

	if ($$link) mysql_select_db($database);

	return $$link;
}

function tep_db_close($link = 'db_link') {
	global $$link;

	return mysql_close($$link);
}

function tep_db_error($query, $errno, $error) {
	die('<font color="#000000"><b>' . $errno . ' - ' . $error . '<br><br>' . $query . '<br><br><small><font color="#ff0000">[TEP STOP]</font></small><br><br></b></font>');
}

function tep_db_query($query, $link = 'db_link', $isshow_exectime=false) {
	global $$link, $logger, $login_id,$_SQL_QUERY_NUM;

	//暂时关闭由php触发的数据表优化功能 by howard 2013-05-28
	if(preg_match('/OPTIMIZE +TABLE/i', $query)) return false;
	
	if(defined('OPEN_SQL_EXPLAIN_CHECK') && OPEN_SQL_EXPLAIN_CHECK=='true'){
		echo '<div>explain '.$query.'</div>';
	}

	if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
		if (!is_object($logger)) $logger = new logger;
		$logger->write($query, 'QUERY');
	}

	$_db_query_time = 0;
	if($isshow_exectime){
		$_db_query_time = tep_db_mcrotime();
	}

	/*Mysql Cache Set Howard added*/
	$query = trim($query);
	if(preg_match('/^(select )/iU',$query, $m) && stripos($query, 'SQL_CACHE')===false && stripos($query, 'SQL_NO_CACHE')===false){
		if((stripos($query, 'sessions')===false && stripos($query, 'sql_query_logs')===false)){
			$query = preg_replace('/'.$m[1].'/iU',$m[1].' SQL_CACHE ', $query, 1);
		}else{
			$query = preg_replace('/'.$m[1].'/iU',$m[1].' SQL_NO_CACHE ', $query, 1);
		}
	}elseif(SQL_OPEN_IGNORE == true && preg_match('/^(update |insert )/iU',$query, $m) && stripos($query, ' IGNORE ')===false){	//添加忽略错误的写法
		$query = preg_replace('/'.$m[1].'/iU',$m[1].' IGNORE ', $query, 1);
	}

	$result = mysql_query($query, $$link) or tep_db_error($query, mysql_errno(), mysql_error());
	$_SQL_QUERY_NUM++;
	if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
		if (mysql_error()) $logger->write(mysql_error(), 'ERROR');
	}

	/*写更新或删除操作的日志到数据库，千万不能有记录新增的动作，否则tep_db_insert_id()功能失败！（已经暂停用）*/
	if(0 && preg_match('/^(update|delete)+/',trim(strtolower($query)) ,$m) && !preg_match('/(sessions)+/',trim(strtolower($query))) && !preg_match('/(whos\_online)+/',trim(strtolower($query)) ) ){
		$query_a = ('insert into `sql_query_logs` ( `admin_id` , `query_sql` , `query_time` , `url_file_name`, `query_type` ) VALUES ('.(int)$login_id.', "'.addslashes(trim($query)).'", "'.date("Y-m-d H:i:s").'", "'.$_SERVER['PHP_SELF'].'", "'.strtolower($m[1]).'");');
		mysql_query($query_a, $$link) or tep_db_error($query, mysql_errno(), mysql_error());
	}
	if($isshow_exectime){
		echo '<font style="color:red;">Query Execute Time :'.number_format((tep_db_mcrotime()-$_db_query_time),6).' (s)!</font><br>';
		echo 'SQL:<br>'.$query.'<hr>';
	}
	return $result;
}
function tep_db_query_check($query, $link = 'db_link') {
	global $$link, $logger, $login_id,$_SQL_QUERY_NUM;
	return tep_db_query($query, $link,true);
}

function tep_db_mcrotime(){
	$t_array = explode(' ',microtime());
	return $t_array[0] + $t_array[1];
}

function tep_db_perform($table, $data, $action = 'insert', $parameters = '', $link = 'db_link') {
	reset($data);
	if ($action == 'insert') {
		$query = 'insert into ' . $table . ' (';
		while (list($columns, ) = each($data)) {
			$query .= $columns . ', ';
		}
		$query = substr($query, 0, -2) . ') values (';
		reset($data);
		while (list(, $value) = each($data)) {
			switch ((string)$value) {
				case 'now()':
					$query .= 'now(), ';
					break;
				case 'null':
					$query .= 'null, ';
					break;
				default:
					$query .= '\'' . tep_db_input($value) . '\', ';
					break;
			}
		}
		$query = substr($query, 0, -2) . ')';
	} elseif ($action == 'update') {
		$query = 'update ' . $table . ' set ';
		while (list($columns, $value) = each($data)) {
			switch ((string)$value) {
				case 'now()':
					$query .= $columns . ' = now(), ';
					break;
				case 'null':
					$query .= $columns .= ' = null, ';
					break;
				default:
					$query .= $columns . ' = \'' . tep_db_input($value) . '\', ';
					break;
			}
		}
		$query = substr($query, 0, -2) . ' where ' . $parameters;
	}
	return tep_db_query($query, $link);
}

function tep_db_fetch_array($db_query) {
	return mysql_fetch_array($db_query, MYSQL_ASSOC);
}

function tep_db_result($result, $row, $field = '') {
	return mysql_result($result, $row, $field);
}

function tep_db_num_rows($db_query) {
	return mysql_num_rows($db_query);
}

function tep_db_data_seek($db_query, $row_number) {
	return mysql_data_seek($db_query, $row_number);
}

function tep_db_insert_id() {
	return mysql_insert_id();
}

function tep_db_free_result($db_query) {
	return mysql_free_result($db_query);
}

function tep_db_fetch_fields($db_query) {
	return mysql_fetch_field($db_query);
}

function tep_db_output($string) {
	return tep_htmlspecialchars($string);
}

function tep_db_input($string) {
	if(!get_magic_quotes_gpc()){
		$string = addslashes($string);
	}
	return $string;
}

function tep_db_prepare_input($string) {

	if (is_string($string)) {
		return trim(stripslashes2($string));
	} elseif (is_array($string)) {
		reset($string);
		while (list($key, $value) = each($string)) {
			$string[$key] = tep_db_prepare_input($value);
		}
		return $string;
	} else {
		return $string;
	}
}

//amit added to fixed for ill-font in chinese


function stripslashes2($string) {

	$string = str_replace("\\\"", "\"", $string);

	$string = str_replace("\\'", "'", $string);
	if(eregi("\\\\",$string)){
		$string = str_replace("\\\\", "\\", $string);
	}
	return $string;
}

/**
 * 返回指定查询的结果数组
 * 当结果超出maxrow会只提取前maxrow行数据 maxrow默认为500
 * @author vincent
 * @param string $sql_string
 * @param int maxrow 500
 */
function vin_db_fetch_all($sql_string,$maxrow=500){
	$result = tep_db_query($sql_string) ;
	$rows = array();
	$i = 0 ;
	while(($row =  tep_db_fetch_array($result, MYSQL_ASSOC)) && $i < $maxrow){
		$rows[] = $row ;
		$i++;
	}
	tep_db_free_result($result);
	return $rows;
}
/**
 * 返回指定查询的结果数组的第一行
 * @author vincent
 * @param string $sql_string
 */
function vin_db_fetch_first($sql_string){
	$result = tep_db_query($sql_string) ;
	$row =  tep_db_fetch_array($result, MYSQL_ASSOC);
	tep_db_free_result($result);
	return $row;
}

/**
 * 取得指定表的字段名称
 * @author howard
 * @param  string $table_name
 */
function tep_db_table_fields_names($table_name){
	$meta = false;
	$array = tep_db_table_fields($table_name);
	foreach ($array as $key => $val) {
		$meta[] = $val["Field"];
	}
	return $meta;
}
/**
 * 取得指定表的字段信息数组
 * @author howard
 * @param  string $table_name
 * @return array
 */
function tep_db_table_fields($table_name){
	$data = array();
	$describe_query = tep_db_query("describe $table_name ");
	while ($d_row = tep_db_fetch_array($describe_query)) {
		$data[] = $d_row;
	}
	return $data;
}
/**
 * 取得某表的主键名称
 * @param string $table_name
 * @return array
 */
function tep_db_table_primary_keys($table_name){
	$data = array();
	$fields = tep_db_table_fields($table_name);
	foreach ($fields as $field){
		if($field['Key']=='PRI') $data[] = $field['Field'];
	}
	return $data;
}

/**
 * 快速插入数据到指定数据表
 * @param string $table 表名
 * @param array $formFields 字段值数组（原始的表单数据，不用经过tep_db_prepare_input()处理）
 * @param string $disableFields 被排除的字段，即哪些字段不能被写到数据库
 * @return int
 */
function tep_db_fast_insert($table, $formFields, $disableFields = ""){
	if(tep_not_null($disableFields)){
		$disableFields = explode(',',preg_replace('/[[:space:]]+/','',$disableFields));
	}
	$insert_id = 0;
	$fields = tep_db_table_fields_names($table);
	$formFields = tep_db_prepare_input($formFields);
	$sql_data_array = false;
	if(sizeof($fields)){
		foreach($fields as $key => $val){
			if(array_key_exists($val, $formFields) && !is_array($formFields[$val])){
				if(!in_array($val, (array)$disableFields) || $disableFields=="" ){
					$sql_data_array[$val] = $formFields[$val];
				}
			}
		}

		if(is_array($sql_data_array)){
			tep_db_perform($table, $sql_data_array);
			$insert_id = tep_db_insert_id();
		}
	}

	return $insert_id;
}

/**
 * 快速更新表数据 *
 * @param string $table 表名
 * @param string $where 条件字符
 * @param array $formFields 字段值数组（原始的表单数据，不用经过tep_db_prepare_input()处理）
 * @param string $allowFields 允许更新的字段，如果值为*则可以更新所有字段
 * @return int
 */
function tep_db_fast_update($table, $where, $formFields, $allowFields = "*"){
	if($allowFields!="*"){
		$allowFields = explode(',',preg_replace('/[[:space:]]+/','',$allowFields));
	}
	$fields = tep_db_table_fields_names($table);
	$formFields = tep_db_prepare_input($formFields);
	$sql_data_array = false;
	if(sizeof($fields)){
		foreach($fields as $key => $val){
			if(array_key_exists($val, $formFields) && !is_array($formFields[$val])){
				if(in_array($val, (array)$allowFields) || $allowFields=="*" ){
					$sql_data_array[$val] = $formFields[$val];
				}
			}
		}
		if(is_array($sql_data_array)){
			tep_db_perform($table, $sql_data_array, 'update', $where);
			return (int)tep_db_affected_rows();
		}
	}
	return 0;
}

function tep_db_affected_rows(){
	return mysql_affected_rows();
}

/**
 * 存储过程调用的数据库查询
 * 说明：由于本系统用的储存过程较少，所以mysqli数据库连接调用一次连接一次！
 * @author Howard by 2013-03-06
 * @param string $query SQL查询语句
 * @return array()
 */
function tep_db_call_sp($query){
	$data = array();
	$mysqli = @new mysqli(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE);
	if($mysqli->connect_errno) {	//连接错误信息
		printf("Connect failed: %s\n", $mysqli->connect_error);
		printf("Error code is: %s\n", $mysqli->connect_errno);
		exit();
	}
	$result = $mysqli->query($query);
	if (!$result) {	//查询错误信息
		printf("Errormessage: %s\n", $mysqli->error);
		printf("SQL: %s\n", $query);
		exit();
	}
	while($rows = $result->fetch_array(MYSQLI_ASSOC)){
		$data[] = $rows;
	}
	$mysqli->close();
	return $data;
}
/**
 * 存储过程调用的连接
 * 说明：以下代码原来由许月方所写，会导致正常的数据库查询的mysql_insert_id()功能失效，所以注释它。Howard fixed by 2013-03-06
 */
/*function tep_db_call_sp($query, $server = DB_SERVER, $username = DB_SERVER_USERNAME, $password = DB_SERVER_PASSWORD, $database = DB_DATABASE, $link = 'db_link'){
if (USE_PCONNECT == 'true') {
$link = mysql_pconnect($server, $username, $password);
} else {
$link = mysql_connect($server, $username, $password,1,131072);//for procedure
}

if ($link) mysql_select_db($database);

global $sp_db_link, $logger, $login_id,$_SQL_QUERY_NUM;
if(defined('OPEN_SQL_EXPLAIN_CHECK') && OPEN_SQL_EXPLAIN_CHECK=='true'){
echo '<div>explain '.$query.'</div>';
}
if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
if (!is_object($logger)) $logger = new logger;
$logger->write($query, 'QUERY');
}
$_db_query_time = 0;
if($isshow_exectime){
$_db_query_time = tep_db_mcrotime();
}
//Mysql Cache Set Howard added
$query = trim($query);
if(preg_match('/^(select )/iU',$query, $m) && stripos($query, 'SQL_CACHE')===false && stripos($query, 'SQL_NO_CACHE')===false){
if((stripos($query, 'sessions')===false && stripos($query, 'sql_query_logs')===false)){
$query = preg_replace('/'.$m[1].'/iU',$m[1].' SQL_CACHE ', $query, 1);
}else{
$query = preg_replace('/'.$m[1].'/iU',$m[1].' SQL_NO_CACHE ', $query, 1);
}
}elseif(SQL_OPEN_IGNORE == true && preg_match('/^(update |insert )/iU',$query, $m) && stripos($query, ' IGNORE ')===false){	//添加忽略错误的写法
$query = preg_replace('/'.$m[1].'/iU',$m[1].' IGNORE ', $query, 1);
}

$result = mysql_query($query, $link) or tep_db_error($query, mysql_errno(), mysql_error());
$_SQL_QUERY_NUM++;
if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
if (mysql_error()) $logger->write(mysql_error(), 'ERROR');
}
//写更新或删除操作的日志到数据库
if(preg_match('/^(update|delete)+/',trim(strtolower($query)) ,$m) && !preg_match('/(sessions)+/',trim(strtolower($query))) && !preg_match('/(whos\_online)+/',trim(strtolower($query)) ) ){
$query_a = ('insert into `sql_query_logs` ( `admin_id` , `query_sql` , `query_time` , `url_file_name`, `query_type` ) VALUES ('.(int)$login_id.', "'.addslashes(trim($query)).'", "'.date("Y-m-d H:i:s").'", "'.$_SERVER['PHP_SELF'].'", "'.strtolower($m[1]).'");');
mysql_query($query_a, $link) or tep_db_error($query, mysql_errno(), mysql_error());
}
if($isshow_exectime){
echo '<font style="color:red;">Query Execute Time :'.number_format((tep_db_mcrotime()-$_db_query_time),6).' (s)!</font><br>';
echo 'SQL:<br>'.$query.'<hr>';
}
return $result;
//mysql_free_result($result);
}*/

/**
 * 判断数据表的某个字段是否存在
 *
 * @param unknown_type $table 表名
 * @param unknown_type $field 字段名
 * @return 返回布尔值
 */
function tep_db_field_exists($table, $field){
	$describe_query = tep_db_query("describe $table");
	while ($d_row = tep_db_fetch_array($describe_query)) {
		if ($d_row["Field"] == "$field")
		return true;
	}
	return false;
}

/**
 * 快速取得某表中某个字段的值，如快速取得产品ID为125的产品名称等
 * @param string $field_name 字段名称
 * @param string $table_name 数据表名称
 * @param string $where 筛选条件不带where字符
 * @example tep_db_get_field_value('products_name', 'products_description', 'products_id=125 AND language_id=1 ')
 */
function tep_db_get_field_value($field_name, $table_name, $where ='1' ){
	$field_name = trim($field_name);
	$sql = tep_db_query('SELECT '.(string)$field_name.' FROM '.(string)$table_name.' WHERE '.(string)$where.' Limit 1 ');
	$row = tep_db_fetch_array($sql);
	return $row[preg_replace('/(.+\.)/','',$field_name)];
}

?>
