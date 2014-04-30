<?php 

/**
 * 分页类
 * @author lwkai 2012-12-25 下午3:54:31
 *
 */
class Paging {
	
	/**
	 * SQL句子
	 * @var string
	 * @author lwkai 2012-12-26 上午10:41:15
	 */
	private $_sql = '';
	
	/**
	 * 当前URL上带的当前页码的名称
	 * @var string
	 * @author lwkai 2012-12-26 上午10:41:28
	 */
	private $_param_name = '';
	
	/**
	 * 当前页
	 * @var int
	 * @author lwkai 2012-12-26 上午10:41:57
	 */
	private $_current_page = 1;
	
	/**
	 * 一页显示多少条记录
	 * @var int
	 * @author lwkai 2012-12-26 上午10:42:13
	 */
	private $_page_size = 0;
	
	/**
	 * 记录总行数
	 * @var int
	 * @author lwkai 2012-12-26 上午10:42:33
	 */
	private $_rows_count = 0;
	
	/**
	 * 总页数
	 * @var int
	 * @author lwkai 2012-12-26 上午10:42:57
	 */
	private $_max_page = 1;
	
	/**
	 * 数据库操作类
	 * @var Db_Mysql
	 * @author lwkai 2012-12-26 上午10:14:58
	 */
	private $_db = null;
	
	/**
	 * URL构造类对象
	 * @var Url
	 * @author lwkai 2012-12-26 上午10:14:37
	 */
	private $_url = null;
	
	/**
	 * 初始化翻页类的一些必须的对象
	 * @param Db_Mysql $db 数据库对象
	 * @param Url $url Url构造对象
	 * @param string $query SQL句子
	 * @param int $max_rows 一页多少条记录
	 * @param string $count_key 统计多少条总记录时的统计字段，默认是*
	 * @param string $page_param_name 页码参数名称，默认是page
	 * @author lwkai 2012-12-26 上午10:43:15
	 */
	public function __construct($db, $url, $query, $max_rows, $count_key = '*', $page_param_name = 'page') {
		$this->_sql = $query;
		$this->_db = $db;
		$this->_url = $url;
		$this->_param_name = $page_param_name;
		if (isset($_GET[$page_param_name])) {
			$this->_current_page = (int)$_GET[$page_param_name];
		}elseif (isset($_POST[$page_param_name])) {
			$this->_current_page = (int)$_POST[$page_param_name];
		}
		if ($this->_current_page < 1) $this->_current_page = 1;
		$this->_page_size = $max_rows;
		$this->execute($count_key);
	}
	
	/**
	 * 执行分页操作。生成当前页的SQL
	 * @param string $count_key 统计记录总数的字段
	 * @author lwkai 2012-12-26 上午11:45:47
	 */
	private function execute($count_key) {
		$pos_to = strlen($this->_sql);
		$pos_from = stripos($this->_sql, ' from', 0);
		
		$pos_group_by = stripos($this->_sql, ' group by', $pos_from);
		if (($pos_group_by < $pos_to) && ($pos_group_by != false)) $pos_to = $pos_group_by;
		
		$pos_having = stripos($this->_sql, ' having', $pos_from);
		if (($pos_having < $pos_to) && ($pos_having != false)) $pos_to = $pos_having;
		
		$pos_order_by = stripos($this->_sql, ' order by', $pos_from);
		if (($pos_order_by < $pos_to) && ($pos_order_by != false)) $pos_to = $pos_order_by;
		
		if (stripos($this->_sql, 'distinct') || stripos($this->_sql, 'group by')) {
			$count_string = 'distinct ' . ($count_key == '*' ? $count_key : Convert::db_input($count_key));
		} elseif ($count_key != '*') {
			$count_string = Convert::escape($count_key);
		} else {
			$count_string = $count_key;
		}
		
		$count = $this->_db->query("select count(" . $count_string . ") as total " . substr($this->_sql, $pos_from, ($pos_to - $pos_from)))->getOne();
		$this->_rows_count = $count['total'];
		
		$this->_max_page = ceil($this->_rows_count / $this->_page_size);
		
		if ($this->_current_page > $this->_max_page) {
			$this->_current_page = $this->_max_page;
		}
		
		$offset = ($this->_page_size * ($this->_current_page - 1));
		
		$this->_sql .= " limit " . max($offset, 0) . ", " . $this->_page_size;
	}
	
	/**
	 * 生成翻页导航条的数据
	 * @param int $max_page_num 一页显示多少条记录
	 * @param array $parameters 需要去掉的参数
	 * @return array(
	 * 		'first'=>array('href'=>''),
	 * 		'last'=>array('href'=>''),
	 * 		'previous'=>array('href'=>''),
	 * 		'previous_x'=>array('href'=>''),
	 * 		'pages_list'=>array(array('href'=>'','text'=>''),...),
	 * 		'next_x'=>array('href'=>''),
	 * 		'next'=>array('href'=>'')
	 * )
	 * @author lwkai 2012-12-26 上午10:33:23
	 */
	public function getPageLinksToArray($max_page_num,$parameters = array()) {
		$get = $_GET;
		unset($get['parent_cpath']);
		foreach ($parameters as $key => $val) {
			if ($val == '') {
				unset($get[$key]);
			} else {
				$get[$key] = $val;
			}
		}
		if ($this->_current_page > 1){
			$tpl['previous']['href'] = $this->_url->create('', array_merge($get,array($this->_param_name => $this->_current_page -1)));
			$tpl['first']['href'] = $this->_url->create('', array_merge($get,array($this->_param_name => '1')));
		}
		
		// check if number_of_pages > $max_page_links
		$cur_window_num = intval($this->_current_page / $max_page_num);
		
		if ($this->_current_page % $max_page_num) $cur_window_num++;
		$cur_window_num < 1 && $cur_window_num = 1;
		$max_window_num = intval($this->_max_page / $max_page_num);
		if ($this->_max_page % $max_page_num) $max_window_num++;
		
		// 前N页 previous window of pages
		if ($cur_window_num > 1){
			$tpl['previous_x']['href'] = $this->_url->create('', array_merge($get, array($this->_param_name => ($cur_window_num - 1) * $max_page_num)));
		}
		
		// 第XX页的按钮集数组page nn button
		$tpl['pages_list'] = array();
		for ($jump_to_page = 1 + (($cur_window_num - 1) * $max_page_num); ($jump_to_page <= ($cur_window_num * $max_page_num)) && ($jump_to_page <= $this->_max_page); $jump_to_page++) {
			if ($jump_to_page == $this->_current_page) {
				$tpl['pages_list'][] = array('href'=>'', 'text'=>$jump_to_page);
			} else {
				$tpl['pages_list'][] = array('href'=> $this->_url->create('', array_merge($get,array($this->_param_name => $jump_to_page))), 'text' => $jump_to_page);
			}
		}
		
		// 下N页 window of pages
		if ($cur_window_num < $max_window_num){
			$tpl['next_x']['href'] = $this->_url->create('', array_merge($get, array($this->_param_name => $cur_window_num * $max_page_num + 1)));
		}
		
		// 下一页 next button
		if (($this->_current_page < $this->_max_page) && ($this->_max_page != 1)){
			$tpl['next']['href'] = $this->_url->create('',array_merge($get, array($this->_param_name => $this->_current_page + 1)));
			$tpl['last']['href'] = $this->_url->create('', array_merge($get, array($this->_param_name => $this->_max_page)));
		}
		return $tpl;
	}
	
	/**
	 * 返回SQL句子
	 * @return string
	 * @author lwkai 2012-12-25 下午5:33:44
	 */
	public function getSql() {
		return $this->_sql;
	}
	
	/**
	 * 返回记录总数
	 * @return number
	 * @author lwkai 2012-12-26 下午1:04:23
	 */
	public function getRowsCount(){
		return $this->_rows_count;
	}
}