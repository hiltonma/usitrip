<?php
/**
 * 常见问题类
 * @author wtj
 * @date 2013-1-6
 */
class CommonProblem {
	/**
	 * 内部考核评分类
	 * @var Assessment_Score
	 */
	private static $Score = null;
	private $number; // 列表总书目
	private $page_number = 40; // 每页显示消息的条数
	/**
	 * 构造函数 初始化每页显示多少条记录
	 *
	 * @param int $page_number
	 *        	每页显示多少条记录
	 */
	function __construct($page_number = '') {
		if ($page_number)
			$this->page_number = $page_number;
		if (isset($_GET['id']))
			$_GET['id'] = ( int ) $_GET['id'];
		if (isset($_GET['status']))
			$_GET['status'] = ( int ) $_GET['status'];
			// $_POST = tep_db_input($_POST);
		$_POST=$this->myStripslashes($_POST);
	}
	function myStripslashes($post) {
		foreach ( $post as $key => $value ) {
			if(is_array($value)){
				$post[$key]=$this->myStripslashes($value);
			}else{
			$post[$key] = htmlspecialchars(($value));
			}
		}
		return $post;
	}
	/**
	 * 获取状体的array
	 *'3' => '已整理' 
	 * @return multitype:array
	 */
	function getStatusArray() {
		return array(
				'0' => '未回答',
				'1' => '已回答',
				'2' => '已审核',
				
		);
	}
	/**
	 * 获取重要数组 ^_^
	 * 
	 * @return array
	 */
	function getImprotant() {
		return array(
				'0' => array(
						'text' => '普通',
						'color' => '#000000' 
				),
				'1' => array(
						'text' => '重要',
						'color' => '#0033CC' 
				),
				'2' => array(
						'text' => '非常重要',
						'color' => 'red' 
				) 
		);
	}
	function getProblemType(){
		return array('问题类别','接送机','乘车点','用餐','导游','酒店','费用','线路','支付','保险','签证','订购问题','走四方资质','安全','订购协议','其他');
	}
	/**
	 * 获取列表，或者获取单个相关的习题
	 *
	 * @param int $problem_id
	 *        	提问的ID 默认为空，为空就查list
	 * @param int $ower_id
	 *        	提问所有者ID
	 * @param datetime $b_time
	 *        	开始时间
	 * @param datetime $e_time
	 *        	结束时间
	 * @param int $status
	 *        	状体对应 getStatusArray的状态
	 * @param string $value
	 *        	回答的内容包含
	 * @param int $page
	 *        	页码
	 */
	function getList($problem_id = '', $ower_id = '', $b_time = '', $e_time = '', $status = '', $value = '', $page = 1,$important='',$angency_id='',$travel_code='',$problem_type='') {
// 		print_r($_SESSION);
		$data = array();
		$str_sql = 'SELECT 
		t1.problem_content,
		t1.ower_id as ower_id,
		t1.add_time as one_time,
		t1.status,
		t1.coss_time,
		t1.problem_id,
		t1.is_important,
		t1.angency_id,
		t1.travel_code,
		t1.problem_type
		from internal_question t1  where 1';
		
// 		$str_sql = 'select t1.problem_content,
// 				t1.ower_id as ower_id,
// 				t1.add_time as one_time,
// 				t1.status,
// 				t1.coss_time,
// 				t1.problem_id,
// 				t3.admin_firstname,
// 				t3.admin_lastname
// 				from internal_question t1 ,
// 				admin t3 where t1.ower_id=t3.admin_id';
		
		if ($problem_id) {
			$str_sql .= ' and t1.problem_id=' . $problem_id;
		} else {
			$ower_id ? $str_sql .= ' and t1.ower_id=' . $ower_id : '';
			$b_time ? $str_sql .= ' and t1.add_time>="' . $b_time . ' 00:00:01"' : '';
			$e_time ? $str_sql .= ' and t1.add_time<="' . $e_time . ' 23:59:59"' : '';
			if($status!='-1') {
				
				$str_sql .= ' and t1.status=' . (int)$status;
			}
			$value ? $str_sql .= ' and t1.problem_content like "%' . $value . '%"' : '';
			$important?$str_sql.=' and t1.is_important='.$important:'';
			$problem_type?$str_sql.=' AND t1.problem_type='.$problem_type:'';
			$$travel_code?$str_sql.=' AND t1.$travel_code like "%'.$$travel_code.'%"':'';
			if($angency_id){
				$tmp=explode('---', $angency_id);
				$str_sql.=' and t1.angency_id like "%'.$tmp[0].'%"';
			}
// 			$angency_id?$str_sql.=' and t1.angency_id like "%'.$angency_id.'%"':'';
			$str_sql .= ' GROUP BY t1.problem_id order by t1.add_time DESC  ';
			$query_tmp = tep_db_query($str_sql);
			$this->number = tep_db_num_rows($query_tmp);
			$page ? $str_sql .= ' limit ' . (($page - 1) * $this->page_number) . ',' . $this->page_number : '';
		}
		$sql_query = tep_db_query($str_sql);
// 		echo $str_sql;
		while ( $rows = tep_db_fetch_array($sql_query) ) {
			$data[] = $rows;
		}
		// print_r($data);
		return $data;
	}
	/**
	 * 修改某一个表的某一列的某一个值
	 *
	 * @param string $table
	 *        	表名
	 * @param int $id
	 *        	id value
	 * @param string $key_name
	 *        	修改的字段名称
	 * @param int||string $value
	 *        	值
	 * @param string $id_name
	 *        	表的ID字段名
	 */
	function changeOneColumn($table, $id, $key_name, $value, $id_name) {
		$str_sql = "update $table set $key_name='$value' where $id_name=$id";
		tep_db_query($str_sql);
	}
	/**
	 * 回答
	 *
	 * @param int $pro_id
	 *        	提问ID
	 * @param string $content
	 *        	回答的内容
	 * @param int $user_id
	 *        	回答的人的ID
	 */
	function addAnswer($pro_id, $content, $user_id) {
		$array = array(
				'problem_id' => $pro_id,
				'content' => $content 
		);
		$array['add_time'] = date('Y-m-d H:i:s');
// 		$array['admin_job_number'] = tep_get_admin_customer_name($user_id);
		$array['ower_id']=$user_id;
		// $array = tep_db_prepare_input($array);
		
		tep_db_perform('internal_question_answer', $array);
		
		// $str_sql = 'insert into
		// internal_question_answer(problem_id,content,add_time,ower_id)
		// values(' .
		// $pro_id . ',"' . $content . '",now(),' . $user_id . ')';
		// tep_db_query($str_sql);
	}
	/**
	 * 添加一个问题
	 *
	 * @param string $content
	 *        	问题的内容
	 * @param int $user_id
	 *        	用户ID
	 */
	function addProblem($content, $user_id,$agency_name,$travel_code,$problem_type) {
		$arr = array(
				'problem_content' => $content,
				'ower_id' => $user_id,
				'add_time' => date('Y-m-d H:i:s'),
				'status' => 0 ,
				'angency_id'=>$agency_name,
				'travel_code'=>$travel_code,
				'problem_type'=>$problem_type
		);
		tep_db_perform('internal_question', $arr);
		// $str_sql = 'insert into
		// internal_question(problem_content,ower_id,add_time,status)values("' .
		// $content . '",' . $user_id . ',now(),0)';
		// tep_db_query($str_sql);
	}
	/**
	 * 修改问题
	 *
	 * @param int $id
	 *        	问题ID
	 * @param string $content
	 *        	回答的内容
	 */
	function changeProblem($id, $content) {
		$str_sql = 'update internal_question set problem_content="' . $content . '" where problem_id=' . $id;
		tep_db_query($str_sql);
	}
	/**
	 * 获取一个问题
	 *
	 * @param int $id
	 *        	问题ID
	 * @return multitype:array
	 */
	function getOne($id) {
		$str_sql = 'select * from internal_question where problem_id=' . $id;
		$sql_query = tep_db_query($str_sql);
		return tep_db_fetch_array($sql_query);
	}
	/**
	 * 获取一个问题的回答
	 *
	 * @param int $id
	 *        	问题的ID
	 * @return multitype:multitype:array
	 */
	function getOneAnswer($id) {
		$data = array();
		$str_sql = 'select answer_id,problem_id,content,add_time as two_time,ower_id as answer_ower_id from internal_question_answer where problem_id=' . $id;
		$sql_query = tep_db_query($str_sql);
		while ( $row = tep_db_fetch_array($sql_query) ) {
			$data[] = $row;
		}
		return $data;
	}
	/**
	 * 处理分页要用的URL
	 *
	 * @param string $url
	 *        	URL
	 * @return Ambigous <>
	 */
	function doUrl($url) {
		$arr_tmp = explode('&page', $url);
		$str_tmp = $arr_tmp[0];
		if (false === strpos($str_tmp, '?'))
			$str_tmp .= '?';
		else
			$str_tmp .= '&';
		return $str_tmp;
	}
	/**
	 * 创建分页所需的URL
	 *
	 * @param string $url
	 *        	URL
	 * @return string
	 */
	function createPage($url) {
		$str_back = '';
		$page = ceil($this->number / $this->page_number);
		if ($page > 1) {
			$str_back = '--共' . $page . '页--<select name="page" id="problem_page">';
			$str_back .= '<option value="">请选择页码</option>';
			for($i = 1; $i <= $page; $i ++) {
				$str_back .= '<option value="' . $i . '">第-' . $i . '-页</option>';
			}
			$str_back .= '</select>';
			$str_back .= '<input type="button" value="确定" onclick="location.href=\'' . $this->doUrl($url) . 'page=\'+document.getElementById(\'problem_page\').value"';
		}
		return $str_back;
	}
	function getProductId($product_code){
		$str_sql='SELECT products_id FROM products WHERE products_model="'.$product_code.'"';
		$query=tep_db_query($str_sql);
		$info=tep_db_fetch_array($query);
		return $info;
	}
	/**
	 * 获取地接商信息
	 */
	public function getAgency(){
		$agency_array_search = array(array('id' => '', 'text' => TEXT_NONE));
		$agency_query_search = tep_db_query("select agency_id, agency_name1 from " . TABLE_TRAVEL_AGENCY . " order by agency_name");
		while ($agency_result_search = tep_db_fetch_array($agency_query_search)) {
			$agency_array_search[] = array('id' => $agency_result_search['agency_id'],
											'text' => $agency_result_search['agency_name1']);
		}
		return $agency_array_search;
	}
	/**
	 * 获取地接商信息
	 */
	public function getAgencySimple(){
		$agency_query_search = tep_db_query("select agency_id, agency_name1 from " . TABLE_TRAVEL_AGENCY . " order by agency_name");
		while ($agency_result_search = tep_db_fetch_array($agency_query_search)) {
			$agency_array_search[] = $agency_result_search['agency_id'].'---'.urlencode($agency_result_search['agency_name1']);
		}
		return $agency_array_search;
	}
	/**
	 * 获取地接商ID
	 */
	function getAgencyId(){
		$agency_query_search = tep_db_query("select agency_id from " . TABLE_TRAVEL_AGENCY . " order by agency_name");
		while ($agency_result_search = tep_db_fetch_array($agency_query_search)) {
			$agency_array_search[] = $agency_result_search['agency_id'];
		}
		return $agency_array_search;
	}
	/**
	 * 获取地接商问题个数
	 * @return string
	 */
	function getAgencyNumber(){
		$return='';
		$agency_id=$this->getAgencyId();
		foreach($agency_id as $value){
			$str_sql='SELECT count(problem_id) as total FROM internal_question WHERE angency_id LIKE "%'.$value.'%"';
			$query=tep_db_query($str_sql);
			$info=tep_db_fetch_array($query);
			if($info['total']>0)
			$return.="<a href='?agency_name=$value'><font color='red'>$value($info[total])</font></a>&nbsp;&nbsp;&nbsp;";
		}
		return $return;
	}
	function doSelect($str_sql){
		$return=array();
		$query=tep_db_query($str_sql);
		while($row=tep_db_fetch_array($query)){
			$return[]=$row;
		}
		return $return;
	}
	function getAnswerByProblem2($problem_id){
		$str_sql='SELECT answer_id,problem_id,content,add_time,ower_id FROM internal_question_answer WHERE problem_id='.(int)$problem_id.' ORDER BY add_time DESC LIMIT 1';
		return $this->doSelect($str_sql);
	}
	function getAnswerByProblem($problem_id){
		$str_sql='SELECT answer_id,problem_id,content,add_time,ower_id FROM internal_question_answer WHERE problem_id='.(int)$problem_id;
		return $this->doSelect($str_sql);
	}
	/**
	 * 
	 * @param array $post
	 * @return number
	 */
	function changeAnswer($post,$user_id,$problem_id){
		foreach($post['answer_content'] as $key=>$value){
			$str_sql='insert into internal_question_answer SET problem_id='.(int)$problem_id.',content="'.$value.'",add_time="'.date('Y-m-d H:i:s').'",ower_id='.(int)$user_id;
			tep_db_query($str_sql);
		}
		return 1;
	}
	/**
	 * 触发积分类
	 * @author Howard
	 */
	private function _triggerScoreObj(){
		if(self::$Score === null){
			require_once 'includes/classes/Assessment_Score.class.php';
			self::$Score = new Assessment_Score();
		}
	}
	/**
	 * 给提问者添加积分
	 * @param int $admin_id 提问人管理员id
	 * @param int $score 分数，加分 > 0 > 减分 
	 * @param int $problem_id 问题id
	 * @author Howard
	 * @return int 返回被添加成功的积分记录id
	 */
	public function addQuestionScore($admin_id, $score, $problem_id){		
		$this->_triggerScoreObj();
		return self::$Score->add_pending_score($admin_id, $score, '后台常见问题：有效提问'.$score.'积分', 'problem_id', $problem_id, 1, $_SESSION['login_id'], 3, $problem_id );
	}
	/**
	 * 给回答人加、减分
	 * @param int $admin_id 回答人管理员id
	 * @param int $score 分数，加分 > 0 > 减分 
	 * @param int $answer_id 回答id
	 * @author Howard
	 * @return int 返回被添加成功的积分记录id
	 */
	public function addAnswerScore($admin_id, $score, $answer_id){
		$this->_triggerScoreObj();
		return self::$Score->add_pending_score($admin_id, $score, '后台常见问题：回答'.$score.'积分', 'answer_id', $answer_id, 1, $_SESSION['login_id'], 4, $answer_id );
	}
	/**
	 * 取得某个问题的积分总额
	 * @param int $problem_id 问题id
	 * @author Howard
	 */
	public function getQuestionScore($problem_id){
		$this->_triggerScoreObj();
		$data = self::$Score->get_score_list(array('problem_id'=>(int)$problem_id, 'score_type'=>'3'));
		if($data['list']){
			$num = 0;
			for($i=0, $n=sizeof($data['list']); $i < $n; $i++){
				$num += $data['list'][$i]['score_value_total'];
			}
			return $num;
		}
		return 0;
	}
	/**
	 * 取得某个回答的积分总额
	 * @param unknown_type $answer_id
	 * @author Howard
	 */
	public function getAnswerScore($answer_id){
		$this->_triggerScoreObj();
		$data = self::$Score->get_score_list(array('answer_id'=>(int)$answer_id, 'score_type'=>'4'));
		if($data['list']){
			$num = 0;
			for($i=0, $n=sizeof($data['list']); $i < $n; $i++){
				$num += $data['list'][$i]['score_value_total'];
			}
			return $num;
		}
		return 0;
	}
}
?>