<?php
/**
 * 数据库连接类
 * @author lwkai
 * @date 2012-11-9 下午4:49:37
 * @link <1275124829@163.com>lwkai
 * @formatter:off
 */
class Db_Mysql {
	
	/**
	 * 执行的SQL次数
	 * @var int
	 */
	private $_query_num = 0;
	
	/**
	 * 执行SQL所用时间的总和
	 * @var int
	 */
	private $_query_time = 0;
	
	/**
	 * 最后一条SQL执行的时间
	 * @var int
	 */
	private $_last_query_time = 0;
	
	/**
	 * 数据库资源连接
	 * @var resources
	 */
	private $_link = null;
	
	/**
	 * 执行SQL之后的资源结果集
	 * @var resources
	 */
	private $_result = null;
	
	/**
	 * 不能缓存的表
	 * @var array
	 */
	private $_no_cache_tables = array();
	
	/**
	 * 连接数据库
	 * @param string $dbname database配置中的KEY字符串

	 */
	public function __construct($dbname) {
		if (defined('DIR_FS_DATABASE') && DIR_FS_DATABASE != '' && file_exists(DIR_FS_DATABASE)) {
			$dbinfo = require DIR_FS_DATABASE;
		} elseif (defined('DIR_FS_ROOT') && DIR_FS_ROOT != '') {
			$dbinfo = require(DIR_FS_ROOT . 'public/Datebase.php');
		} else {
			My_Exception::mythrow('dberr','数据库配置文件未找到!');
		}
		$dbinfo = $dbinfo[$dbname];
		$user     = !empty($dbinfo['user']) ? $dbinfo['user'] : My_Exception::mythrow('dberr','数据库连接用户名未设置！');
		$server   = !empty($dbinfo['host']) ? $dbinfo['host'] : My_Exception::mythrow('dberr','数据库连接服务器地址未设置！');
		$password = !empty($dbinfo['pwd']) ? $dbinfo['pwd'] : My_Exception::mythrow('dberr','数据库连接密码未设置！');
		$database = !empty($dbinfo['dbname']) ? $dbinfo['dbname'] : My_Exception::mythrow('dberr','数据库库名未设置！');
		$port     = !empty($dbinfo['port']) ? $dbinfo['port'] : '3306';
		$charset  = !empty($dbinfo['charset']) ? $dbinfo['charset'] : 'utf8';

		$is_lasting = defined('DB_LASTING') && DB_LASTING == true ? DB_LASTING : false ;
		if ($is_lasting === true) {
			$this->_link = mysql_pconnect("{$server}:{$port}", $user, $password, 131072);
		} else {
			$this->_link = mysql_connect("{$server}:{$port}", $user, $password, 1, 131072);
		}
		if (!$this->_link) My_Exception::mythrow('dberr', '数据库连接失败！');
		$this->_link && mysql_select_db($database,$this->_link);
		$charset && mysql_query('set names ' . $charset, $this->_link);
		
		/* 不需要进行缓存的表 */
		$this->_no_cache_tables = defined('NO_CACHE_TABLES') && NO_CACHE_TABLES != '' ? explode(',',NO_CACHE_TABLES) : array();
	}
	
	/**
	 * 获取成功执行了多少次SQL
	 * @return number
	 */
	public function getQueryNum() {
		return $this->_query_num;
	}
	
	/**
	 * 获取所有SQL执行的时间总和
	 * @return number
	 */
	public function getQueryTime() {
		return $this->_query_time;
	}
	
	/**
	 * 获取最后一次SQL执行所用的时间
	 * @return number
	 */
	public function getLastQueryTime() {
		return $this->_last_query_time;
	}
	
	/**
	 * 检测SQL是否不能缓存，是返回true,否则false
	 * @param string $sql
	 * @return boolean
	 */
	private function isDisableCache($sql){
		if ($this->_no_cache_tables) {
			foreach ($this->_no_cache_tables as $key => $val) {
				if (stripos($sql, $val) !== false) {
					return true;
				}
			}
		}
		return false;
	}
	
	/**
	 * 开启数据库缓存后，处理SQL句子的过程
	 * @param string $query SQL语句
	 * @return string
	 */
	private function isCache($query){
		if (defined('ENABLE_SQL_CACHE') && ENABLE_SQL_CACHE == true) {
			if (preg_match('/^(select )/iU',$query, $m) && stripos($query, 'SQL_CACHE') === false && stripos($query, 'SQL_NO_CACHE') === false) {
				
				if ($this->isDisableCache($query)){
					$query = preg_replace('/'.$m[1].'/iU',$m[1].' SQL_NO_CACHE ', $query, 1);
				}else{
					$query = preg_replace('/'.$m[1].'/iU',$m[1].' SQL_CACHE ', $query, 1);
				}
			}elseif(defined('SQL_OPEN_IGNORE') && SQL_OPEN_IGNORE == true && preg_match('/^(update |insert )/iU',$query, $m) && stripos($query, ' IGNORE ')===false){	//添加忽略错误的写法
				$query = preg_replace('/'.$m[1].'/iU',$m[1].' IGNORE ', $query, 1);
			}
		}
		return $query;
	}
	
	/**
	 * 记录日志到文件
	 * @param string $txt
	 */
	private function writeLog($txt) {
		/* 如果打开了记录查询日志记录功能，则记录查询日志到 STORE_PAGE_PARSE_TIME_LOG 常量指定的位置  （常量配置在数据库中） */
		if (defined('STORE_DB_TRANSACTIONS') && STORE_DB_TRANSACTIONS == 'true') {
			if (defined('DIR_FS_ROOT') && defined('STORE_PAGE_PARSE_TIME_LOG')) {
				error_log($txt . "\n", 3, DIR_FS_ROOT . STORE_PAGE_PARSE_TIME_LOG);
			}
		}
	}
	
	/**
	 * 执行SQL语句查询
	 * @param string $query
	 * @return 对象本身
	 */
	public function query($query) {
		
		$query = trim($query);
		$this->writeLog('QUERY ' . $query);
		$query = $this->isCache($query);
		$_db_query_time = 0;
		$_db_query_time = microtime(true);
		$result = mysql_query($query, $this->_link);
		if (!$result) {
			$result_error = mysql_error();
			$this->writeLog('RESULT ' . $result . ' ' . $result_error);
			My_Exception::mythrow('dberr', 'SQL语句执行失败！',$query, mysql_errno(), mysql_error());
		}
		$this->_query_num ++;
		$this->_last_query_time = microtime(true) - $_db_query_time;
		$this->_query_time += $this->_last_query_time;
	
		/*写更新或删除操作的日志到数据库*/
		//if(preg_match('/^(update|delete)+/',trim(strtolower($query)) ,$m) && !preg_match('/(sessions)+/',trim(strtolower($query)) ) ){
		/*if(preg_match('/^(delete)+/',trim(strtolower($query)) ,$m) && !preg_match('/(sessions)+/',trim(strtolower($query))) && !preg_match('/(whos\_online)+/',trim(strtolower($query)) ) ){
			$query_a = ('insert into `sql_query_logs` ( `admin_id` , `query_sql` , `query_time` , `url_file_name`, `query_type` ) VALUES (9999999, "'.addslashes(trim($query)).'", "'.date("Y-m-d H:i:s").'", "'.$_SERVER['PHP_SELF'].'", "'.strtolower($m[1]).'");');
			mysql_query($query_a, $$link) or tep_db_error($query, mysql_errno(), mysql_error());
		}
	
		if($isshow_exectime){
			echo '<font style="color:red;">Query Execute Time :'.number_format((tep_db_mcrotime()-$_db_query_time),6).' (s)!</font><br>';
			echo 'SQL:<br>'.$query.'<hr>';
		}*/
		$this->_result = $result;
		return $this;
	}
	
	/**
	 * 获取所有记录结果集
	 * @param int $type 可选 MYSQL_ASSOC，MYSQL_NUM 和 MYSQL_BOTH 默认是MYSQL_ASSOC
	 */
	public function getAll($type = MYSQL_ASSOC) {
		$data = array();
		if (is_resource($this->_result)) {
			while (false != $row = mysql_fetch_array($this->_result, $type)) {
				$data[] = $row;
			}
		} else {
			my_exception::mythrow('dberr', '数据结果集不是一个有效的资源连接！');
		}
		return $data;
	}
	
	/**
	 * 只获取一条记录
	 * @param int $type 可选 MYSQL_ASSOC，MYSQL_NUM 和 MYSQL_BOTH 默认是MYSQL_ASSOC
	 */
	public function getOne($type = MYSQL_ASSOC) {
		$data = array();
		if (is_resource($this->_result)) {
			$data = mysql_fetch_array($this->_result, $type);
		} else {
			my_exception::mythrow('dberr', '数据结果集不是一个有效的资源连接！');
		}
		return $data;
	}
	
	/**
	 * 取得指定表的结构信息
	 * @param  string $table 需要获取结构的表名
	 * @return array
	 */
	public function getFields($table){
		return $this->query('describe ' . $table)->get_all();
	}
	
	/**
	 * 检测是否有需要被排除的字段，即哪些字段不能被写到数据库，返回过滤后的数组
	 * @param string $table
	 * @param array $formFields
	 * @param string $disableFields
	 * @return array
	 */
	private function filterFileds($table, $formFields, $disableFields = ""){
		if(!!$disableFields){
			$disableFields = explode(',',preg_replace('/[[:space:]]+/','',$disableFields));
		}
		$insert_id = 0;
		$fields = $this->getFields($table);
		$fields_new  = array();
		foreach($fields as $key => $val) {
			$fields_new[] = $val['Field'];
		}
		$fields = $fields_new;
		$formFields = convert::unescape($formFields);
		$sql_data_array = array();
		if ($fields) {
			foreach ($fields as $key => $val) {
				if (array_key_exists($val, $formFields) && !is_array($formFields[$val])) {
					if ($disableFields == '' || !in_array($val, (array)$disableFields)) {
						$sql_data_array[$val] = $formFields[$val];
					}
				}
			}
		}
		return $sql_data_array;
	}
	
	/**
	 * 对数据进行格式化，以便写入数据库 注意 不支持数组
	 * @param string $string 要处理的字符串数据
	 * @return string
	 * @author lwkai 2013-2-28 下午4:17:36
	 */
	private function db_input($string) {
		if (function_exists('mysql_real_escape_string')) {
			return mysql_real_escape_string($string);
		} elseif (function_exists('mysql_escape_string')) {
			return mysql_escape_string($string);
		} else {
			if(!get_magic_quotes_gpc()){
				$string = addslashes($string);
			}
			return $string;
		}
	}
		
	/**
	 * 格式化传进来的数据格式，转换成对应的SQL语句，以便正确执行。
	 * @param array $data 
	 * @return string
	 */
	private function formatSql($data) {
		$query = '';
		if (is_array($data)) {
			foreach ($data as $columns => $value) {
				switch (true) {
					case (string)$value == 'now()':
						$query .= $columns . ' = now(), ';
						break;
					case (string)$value == 'null':
						$query .= $columns . ' = null, ';
						break;
					case (preg_match('/^' . preg_quote($columns) . '\s*[\+\-]\s*\d+/', $value)):
						$query .= $columns . ' = ' . $value . ', ';
						break;
					default:
						$query .= $columns . ' = \'' . $this->db_input($value) . '\', ';
						break;
				}
			}
		}
		return $query;
	}
	
	/**
	 * 执行插入数据动作,并返回插入成功后的这条记录的ID
	 * @param string $table 需要对入数据的表名
	 * @param array $data 要插入的数据 格式是 array('fileds'=>'values'[,'fileds'=>'values'[,...]])
	 * @param string $disableFields 被排除的字段，即哪些字段不能被写到数据库
	 * @return number
	 */	
	public function insert($table, $data, $disableFields = "") {
			$query = 'insert into ' . $table . ' set ';
			if ($disableFields && $disableFields != "") {
				$data = $this->filterFileds($table, $data,$disableFields);
			}
			
			$query .= $this->formatSql($data);
			$query = substr($query, 0, -2);
			$this->query($query);
			return mysql_insert_id($this->_link);
	}
	
	/**
	 * 执行更新数据动作，并返回影响的记录有多少条
	 * @param string $table 需要进行更新的表名
	 * @param array $data 要更新的数据 格式是 array('fileds'=>'values'[,'fileds'=>'values'[,...]])
	 * @param string $where 更新条件
	 * @param string $disableFields 被排除的字段，即哪些字段不能被更新
	 * @return int
	 */
	public function update($table, $data, $where = '', $disableFields = ""){
			$query = 'update ' . $table . ' set ';
			if ($disableFields) {
				$data = $this->filterFileds($table, $data,$disableFields);
			}
			$query .= $this->formatSql($data);
			$query = substr($query, 0, -2) . ' where ' . $where;
			$this->query($query);
			return mysql_affected_rows($this->_link);
	}
	
	/**
	 * 执行删除动作，返回影响的记录条数
	 * @param string $table 要删除数据的表名
	 * @param string $where 删除条件
	 * @return number
	 */
	public function delete($table,$where) {
		$query = "delete from " . $table . " where " . $where;
		$this->query($query);
		return mysql_affected_rows($this->_link);
	}
	
	/**
	 * 执行特殊SQL语句
	 * @param string $sql SQL句子
	 * @author lwkai 2013-1-8 下午3:41:27
	 */
	public function execute($sql) {
		$sql = trim($sql);
		if ($sql) {
			$_db_query_time = microtime(true);
			$result = mysql_query($sql, $this->_link);
			if (!$result) {
				$result_error = mysql_error();
				$this->writeLog('RESULT ' . $result . ' ' . $result_error);
				My_Exception::mythrow('dberr', 'SQL语句执行失败！',$sql, mysql_errno(), mysql_error());
			}
			$this->_query_num ++;
			$this->_last_query_time = microtime(true) - $_db_query_time;
			$this->_query_time += $this->_last_query_time;
			return $result;
		}
	}
}