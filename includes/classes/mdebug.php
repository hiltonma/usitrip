<?php
/**
 * 性能和测试相关函数
 * @author vincent
 *
 */
if(!defined('DEBUG_LOG_FILE')) define('DEBUG_LOG_FILE',DIR_FS_CATALOG.'/logs/mdebug.log');
if(!defined('DEBUG_DUMP_FILE')) define('DEBUG_DUMP_FILE',DIR_FS_CATALOG.'/logs/mdebug.dump.php');
class MDebug {
	 protected  $logfd = null;
	static private $instance = null;
	protected $sqls =array();
	function __construct(){
		/*
		if(file_exists(DEBUG_DUMP_FILE)){
			$arr = include(DEBUG_DUMP_FILE);
			if(is_array($arr)){
				foreach($arr as $key=>$v){
					$this->$key = $v ;
				}
			}
		}*/
	}
	
function __destruct(){
	/*
		self::log("SQL PARSE ".str_repeat("-",100),true);
		foreach ($this->sqls as $tablename=>$sql){
			$total = count($sql);
			$timeAll = 0 ;$sqlAll = array();
			foreach($sql as $s){
				$timeAll += $s['time'];
				$sqlAll[] = $s['sql'];
			}
			self::log("\n".$tablename."(".$total." ,".$timeAll.")\n".implode("\n",$sqlAll)."\n");
		}
		if($this->logfd != null) @fclose($this->logfd);*/
	}
	
	static function  saveStatus(){
		$self = MDebug::$instance ;
		$dumpData = array(
			'sqls'=>$self->sqls ,
		);
		file_put_contents(DEBUG_DUMP_FILE, "<?php \n return ".var_export($dumpData,true).';?>');
	}
	static public function instance(){
		if(self::$instance ==null){
			self::$instance = new MDebug() ;
		}
		return self::$instance;
	}
	
	/**
	 * 打印变量信息 用<pre>包含
	 * @param .... 多个参数
	 */
	static function dump(){	
		echo "<pre>";
		echo self::instance()->dumpVar(func_get_args());
		echo "</pre>";
	}
	/**
	 *打印变量信息..
	 * @param array $args
	 */
	function dumpVar($args){
		$obuffer = "";
		foreach($args as $arg){
			$vartype = gettype($arg);
			if($vartype == 'array' || $vartype == 'object'){
				$obuffer.= print_r($arg , true);
			}else if($vartype == 'NULL'){
				$obuffer.= 'NULL';
			}else if($vartype == 'integer' || $vartype == 'double'){
				$obuffer.= '[number]'.$arg;
			}elseif($vartype == 'boolean') {
				$obuffer.= $arg ===true?"true":'false';
			}elseif($vartype == 'resource') {
				$obuffer.= '['.get_resource_type($arg).']'.$arg;
			}else{
				$obuffer.= $arg ;
			}
		}
		return $obuffer;
  }
  	/**
  	 * 返回当前毫秒
  	 */
  static function mtime(){
  		list($usec, $sec) = explode(" ", microtime());
    	return ((float)$usec + (float)$sec);
  	}
	/**
	*返回由指定的时间到现在的秒数
	**/
	static function elapsed($startTime = 0,$format='s'){
  		if($startTime == 0 ) $startTime = PAGE_PARSE_START_TIME;
  		$elapsed = self::mtime() - $startTime ;
  		$format = strtolower($format);
  		if($format == 'ms'){
  			return $elapsed*1000;
  		}else
  			return floor($elapsed*1000+0.5)/1000;
  	}
  	/**
  	 * 根据表对sql进行分类
  	 * @param unknown_type $sql
  	 */
  	private function _parseSql($sql ,$runtime){
  		//$sql = str_replace('`','',$sql);
  		preg_match_all('/.*from\s+(.*?)\s+/i',$sql,$matchs);
  		if(!empty($matchs[1])){
  			foreach($matchs[1] as $tablename){
  				$tablename = str_replace("`",'',$tablename);
  				if(isset($this->sqls[$tablename])){
  					$this->sqls[$tablename][] =array( 'sql'=>$sql , 'time'=>$runtime);
  				}else 
  					$this->sqls[$tablename] = array(array( 'sql'=>$sql , 'time'=>$runtime));
  			}
  		}
  	}
  	
  	static function parseSql($query,$runtime){
  		self::instance()->_parseSql($query,$runtime);
  	}
  	
    static function log($msg , $addTimeStamp = false){
		return false;
  	$debug = self::instance();
  	if($debug->logfd == null){
  		$debug->logfd = fopen(DEBUG_LOG_FILE,'a');
  	}
  	$msg =  $addTimeStamp ? date('Y-m-d H:i:s',time()).$msg."\n" : $msg."\n";
  	fwrite($debug->logfd ,$msg);
  }
}
?>