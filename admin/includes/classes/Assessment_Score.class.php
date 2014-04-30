<?php
/**
 * 内部考核评分类
 * 主要用于商务中心的客服人员考核用，以后也可扩展至商品部等考核用等
 * 注意：本类的积分是指员工考核评分，与用户的积分是不同的概念
 * @author Howard by 2013-03-04
 */
class Assessment_Score {
	/**
	 * 分数的状态
	 * @var array
	 */
	public $score_status = array('0'=>'待定','1'=>'已确认积分');
	public function __construct(){

	}
	/**
	 * 给客服添加评分
	 * @param int $admin_id 给予评分的客服id
	 * @param int $score 添加的分数，注意：如果是(-)负数就是扣分
	 * @param string $score_content 分数描述
	 * @param string $id_field_name 加分的订单、留言、邮件对应的订单状态更新历史表、短信发送记录表主键的字段名称如：orders_id、notebook_id、orders_status_history_id、cpunc_sms_history_id、h_id
	 * @param int $primary_key_id 具体的id值，如订单的id、留言的id、邮件对应的订单状态更新历史表、短信发送记录表的主键id值等
	 * @param int $status 分数的状态id，默认为0（待定分数）
	 * @param int $auditor_admin_id 评分人id
	 * @param int $score_type 积分类型：1为订单，2为留言本，3为常见问题的（问），4为常见问题的（答）
	 * @param int $score_type_id 订单号id、留言id、常见问题问id或常见问题答id的值
	 * @return 0|id 返回成功添加的主键id或0
	 */
	public function add_pending_score($admin_id, $score=1, $score_content='', $id_field_name, $primary_key_id, $status = '0', $auditor_admin_id, $score_type, $score_type_id){
		$date_time = date('Y-m-d H:i:s');
		$array = array(
				'admin_id' => $admin_id, 
				'score_value' => $score, 
				'score_content' => $score_content, 
				'field_name' => $id_field_name, 
				'field_name_id' => $primary_key_id, 
				'score_status' => $status, 
				'auditor_admin_id' => $auditor_admin_id, 
				'added_date' => $date_time, 
				'edited_date' => $date_time, 
				'score_type' => $score_type, 
				'score_type_id' => $score_type_id 
		);
		$array = tep_db_prepare_input($array);
		tep_db_perform('assessment_score',$array);
		if($id_field_name == orders_status_history_id && $score_type == 1){
			$this->addScoreToOrderHistory($primary_key_id, $score);
		}
		$assessment_score_id = tep_db_insert_id();
		return $assessment_score_id;
	}
	/**
	 * 添加分数的记录到订单历史记录
	 * @param int $id orders_status_history_id 订单状态历史ID
	 * @param int $score 分数数目
	 */
	private function addScoreToOrderHistory($id,$score){
		$str_sql='update orders_status_history set score=score+'.$score.' where orders_status_history_id='.(int)$id;
		tep_db_query($str_sql);
	}
	/**
	 * 改变某个分数的状态
	 * @param int $score_id 分数记录id
	 * @param int $score_status 分数状态id
	 * @param int $action_admin_id 操作人id
	 */
	public function change_score_status($score_id, $score_status, $action_admin_id=CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID){

	}
	/**
	 * 设置某个订单下面的相关积分状态
	 * @param int $orders_id 订单号
	 * @param int $score_status 积分状态
	 * @param array $outwith_admin_ids 排除的销售客服id（不是工号）
	 * @return NULL
	 */
	public function set_orders_score_status($orders_id, $score_status, array $outwith_admin_ids){
		$f_where = '';
		if($outwith_admin_ids){
			$f_where .= ' and admin_id NOT IN('.implode(',', $outwith_admin_ids).')';
		}
		tep_db_query('update assessment_score set score_status="'.(int)$score_status.'" where score_type="1" and score_type_id="'.(int)$orders_id.'" '.$f_where);
	}
	/**
	 * 取得某个订单更新历史状态是否有给销售添加过积分的记录数
	 * @param int $status_history_id 订单历史状态id
	 * @return int
	 */
	public function get_orders_status_history_score($status_history_id){
		$sql = tep_db_query('SELECT count(assessment_score_id) AS total FROM `assessment_score` WHERE field_name="orders_status_history_id" and field_name_id="'.(int)$status_history_id.'" ');
		$row = tep_db_fetch_array($sql);
		return (int)$row['total'];
	}
	/**
	 * 取得日期工号积分比的数据
	 * @param array $get $_GET变量
	 * @return array
	 */
	public function get_job_num_date_score(array $get){
		$data = array();
		//某个时间段内的系统总积分
		$_s_get = array('time_start'=>$get['time_start'], 'time_end'=>$get['time_end'],'add_time_start'=>$get['add_time_start'],'add_time_end'=>$get['add_time_end']);
		$system_score_sum = $this->get_system_score_sum($_s_get);
		$job_nums = explode(',', rawurldecode($get['job_num']));
		foreach ($job_nums as $job_num){
			if((int)$job_num){
				$orderAmount = 0;	//某工号的订单总金额
				$scoreCount = 0;	//某工号的总积分（订单加留言）
				$admin_id = tep_get_admin_id_from_job_number($job_num);
				$_s_get['admin_id'] = $admin_id;
				$_s_get['job_num'] = $job_num;				
				$orderAmount = $this->get_orders_amount($_s_get);
				$scoreCount = $this->get_system_score_sum($_s_get);
				$data[] = array('jobNum'=>(int)$job_num,'orderAmount'=>'$'.number_format($orderAmount,2,'.',''),'scoreCount'=>$scoreCount, 'averageValue'=>'$'.number_format(($orderAmount/max(1,$scoreCount)),2,'.',''),'personalDivisionCollective'=> $scoreCount.':'.$system_score_sum);
			}
		}
		return $data;
	}
	/**
	 * 取得已出团的订单总金额(必须有考核积分的)
	 * @param array $get 条件参数组array(time_start,time_end,admin_id...)
	 * @return int
	 */
	public function get_orders_amount(array $get){
		$orderAmount = 0;
		$ex_where = '';
		if($get['admin_id']) $ex_where .= ' and ase.admin_id="'.(int)$get['admin_id'].'" ';
		if($get['time_start']) $ex_where .= ' and op.products_departure_date >="'.date('Y-m-d',strtotime($get['time_start'])).' 00:00:00" ';
		if($get['time_end']) $ex_where .= ' and op.products_departure_date <="'.date('Y-m-d',strtotime($get['time_end'])).' 23:59:59" ';
		
		$sql_str = 'SELECT o.orders_id, ot.value FROM orders o, orders_products op, orders_total ot, assessment_score ase WHERE ot.orders_id=o.orders_id and ot.class="ot_total" and op.orders_id=o.orders_id AND o.orders_status=100006 and ase.score_status="1" and ase.score_type="1" and o.orders_id=ase.score_type_id '.$ex_where.' GROUP BY o.orders_id';
		$orders_sql = tep_db_query($sql_str);
		while ($o = tep_db_fetch_array($orders_sql)) {
			$orderAmount += $o['value'];
		}
		return $orderAmount;
	}
	/**
	 * 取得系统总积分
	 * @param array $get 条件数组
	 * @return int
	 */
	public function get_system_score_sum(array $get){
		$o_get = $get;
		$orders_score = $this->get_orders_score_sum($o_get);
		$notebook_score = $this->get_notebook_score_sum($get);
		return ($orders_score + $notebook_score);
	}
	/**
	 * 取得订单总积分
	 * @param array $get 条件数组（主要是出团日期time_start、time_end和订单状态orders_status数据）
	 * @return int
	 */
	public function get_orders_score_sum(array $get){
		$ex_where = '';
		$in_sql = 'SELECT distinct o.orders_id from orders o, orders_products op where op.orders_id=o.orders_id ';
		$in_sql.= ' and o.orders_status="100006"';	//只计已出团的数据
		if($get['time_start']){	//出团日期start
			$in_sql.= ' and op.products_departure_date>="'.date('Y-m-d 00:00:00',strtotime($get['time_start'])).'" ';
		}
		if($get['time_end']){	//出团日期end
			$in_sql.= ' and op.products_departure_date<="'.date('Y-m-d 23:59:59',strtotime($get['time_end'])).'" ';
		}
		if($get['job_num']){	//工号
			$job_nums = explode(',', rawurldecode($get['job_num']));
			$admin_ids = array();
			foreach ((array)$job_nums as $jobN){
				$id = tep_get_admin_id_from_job_number($jobN);
				if($id){
					$admin_ids[]= $id;
				}
			}
			$ex_where.= ' and admin_id in('.implode(',',$admin_ids).') ';
			
		}
		if($get['add_time_start']){	//出团日期start
			$ex_where.= ' and added_date>"'.date('Y-m-d 00:00:00',strtotime($get['add_time_start'])).'"';
		}
		if($get['add_time_end']){	//出团日期end
			$ex_where.= ' and added_date<="'.date('Y-m-d 23:59:59',strtotime($get['add_time_end'])).'"';
		}
		if($get['time_start']||$get['time_end'])
			$in_sql='and score_type_id in('.$in_sql.') and score_status="1" ';
		else $in_sql='';
		$sql = 'SELECT SUM(score_value) as num FROM `assessment_score` where score_type="1" '.$in_sql.' '.$ex_where;
		$sql_row = tep_db_fetch_array(tep_db_query($sql));
		return $sql_row['num'];
	}
	/**
	 * 取得留言本总积分
	 * @param array $get 条件数组（主要是留言给分日期time_start和time_end数据）
	 * @return int
	 */
	public function get_notebook_score_sum(array $get){
		$ex_where = '';
		if($get['time_start']){
			$ex_where.= ' and added_date>="'.date('Y-m-d 00:00:00',strtotime($get['time_start'])).'" ';
		}
		if($get['time_end']){
			$ex_where.= ' and added_date<="'.date('Y-m-d 23:59:59',strtotime($get['time_end'])).'" ';
		}
		if($get['job_num']){	//工号
			$job_nums = explode(',', rawurldecode($get['job_num']));
			$admin_ids = array();
			foreach ((array)$job_nums as $jobN){
				$id = tep_get_admin_id_from_job_number($jobN);
				if($id){
					$admin_ids[]= $id;
				}
			}
			
			$ex_where.= ' and admin_id in('.implode(',',$admin_ids).') ';
		}
		if($get['add_time_start']){	//出团日期start
			$ex_where.= ' and added_date>"'.date('Y-m-d 00:00:00',strtotime($get['add_time_start'])).'"';
		}
		if($get['add_time_end']){	//出团日期end
			$ex_where.= ' and added_date<="'.date('Y-m-d 23:59:59',strtotime($get['add_time_end'])).'"';
		}
		$sql = 'SELECT SUM(score_value) as num FROM `assessment_score` where score_type="2" and score_status="1" '.$ex_where;
		$sql_row = tep_db_fetch_array(tep_db_query($sql));
		return $sql_row['num'];
	}
	/**
	 * 取得后台积分统计表(用于工作人员评分统计)
	 * @param array $get GET参数
	 * @example assessment_score.php
	 * @return array
	 */
	public function get_score_list(array $get){
		//积分查询 start {
		$fields = '';
		$tables = '';
		$where = '';
		$group_by = '';
		$having = '';	//HAVING orders_updated_num="2"
		$order_by = '';
		//积分范围
		switch($get['score_type']){
			case '4': 
				if($get['score_type']=='4'){
					//后台问题回答积分
					$fields = 'iqa.answer_id, iqa.ower_id, sum(ase.score_value) as score_value_total ';
					$tables = 'internal_question_answer iqa, assessment_score ase ';
					$where = ' ase.score_type="4" AND ase.score_type_id=iqa.answer_id AND ase.score_status="1" ';
					if($get['answer_id']){
						$where.= ' AND iqa.answer_id = "'.(int)$get['answer_id'].'" ';
					}
					$group_by = ' group by iqa.answer_id ';
				}
			case '3': 
				if($get['score_type']=='3'){
					//后台问题积分
					$fields = 'iq.problem_id, iq.ower_id, sum(ase.score_value) as score_value_total ';
					$tables = 'internal_question iq, assessment_score ase ';
					$where = ' ase.score_type="3" AND ase.score_type_id=iq.problem_id AND ase.score_status="1" ';
					if($get['problem_id']){
						$where.= ' AND iq.problem_id = "'.(int)$get['problem_id'].'" ';
					}
					$group_by = ' group by iq.problem_id ';
				}
			case '2': 
				if($get['score_type']=='2'){
					//留言本积分
					$fields = 'nb.notebook_id, nb.sent_login_id, sum(ase.score_value) as score_value_total ';
					$tables = 'notebook nb, assessment_score ase ';
					$where = ' ase.score_type="2" AND ase.score_type_id=nb.notebook_id AND ase.score_status="1" ';
					$group_by = ' group by nb.notebook_id ';
				}
				
				//搜索条件start {
				if($get['add_time_start']){
					$where.= ' and ase.added_date >="'.date('Y-m-d 00:00:00',strtotime($get['add_time_start'])).'" ';
				}
				if($get['add_time_end']){
					$where.= ' and ase.added_date <="'.date('Y-m-d 23:59:59',strtotime($get['add_time_end'])).'" ';
				}
				if($get['job_num']){
					$where.= ' and ase.admin_id="'.tep_get_admin_id_from_job_number($get['job_num']).'" ';
				}
				//搜索条件end }
				break;			
			case '1':	//订单相关的积分
			default: {
				//订单积分
				//1.如果搜索条件有日期则应先取得相关的订单号给in用
				//2.如果不存在第1点讲到的条件就列出按单统计的数据
				
				$count_tables = '';
				$count_where = '';
				$count_orders_in = '';
				
				$fields = 'o.orders_id, ot.value, sum(ase.score_value) as score_value_total ';
				$tables = 'orders o, orders_total ot , assessment_score ase ';
				$where = ' o.orders_id=ot.orders_id AND ot.class="ot_total" AND ase.score_type="1" AND ase.score_type_id=o.orders_id AND ase.score_status="1" ';
				$group_by = ' group by o.orders_id ';
				
				//搜索条件start {
				if($get['time_start'] || $get['time_end']||$get['add_time_start']||$get['add_time_end']){
					if(stristr($tables,'orders_products op')===false){
						$tables.= ',orders_products op ';
						$where.= ' and op.orders_id=o.orders_id ';
					}
				
					$group_by = ' group by op.orders_products_id ';
				
					if($get['time_start']){
						$where.= ' and op.products_departure_date >="'.date('Y-m-d 00:00:00',strtotime($get['time_start'])).'" ';
					}
					if($get['time_end']){
						$where .= ' and op.products_departure_date <="'.date('Y-m-d 23:59:59',strtotime($get['time_end'])).'" ';
					}
					if($get['time_end']||$get['time_start']){
						$where .= 'AND o.orders_status=100006 AND ase.score_status="1" ';
					}
					if($get['add_time_start']){
						$where.= ' and ase.added_date >="'.date('Y-m-d 00:00:00',strtotime($get['add_time_start'])).'" ';
					}
					if($get['add_time_end']){
						$where .= ' and ase.added_date <="'.date('Y-m-d 23:59:59',strtotime($get['add_time_end'])).'" ';
					}
				}
				
				if($get['job_num']){
					$where.= ' and ase.admin_id="'.tep_get_admin_id_from_job_number($get['job_num']).'" ';
					$count_where.= ' and ase1.admin_id="'.tep_get_admin_id_from_job_number($get['job_num']).'" ';
				}
				if($get['orders_id']){
					$where.= ' and o.orders_id="'.(int)$get['orders_id'].'" ';
				}
				//搜索条件end }
				//$fields.= ',(SELECT DISTINCTROW sum(1) FROM `assessment_score` ase1 '.$count_tables.' WHERE ase1.field_name="orders_sms_history_id" and ase1.score_type="1" and ase1.score_type_id=o.orders_id and ase1.score_status="1" '.$count_where.') as orders_sms_num ';	//订单短信发送数
				//$fields.= ',(SELECT DISTINCTROW sum(1) FROM `assessment_score` ase1 '.$count_tables.' WHERE ase1.field_name="orders_status_history_id" and ase1.score_type="1" and ase1.score_type_id=o.orders_id and ase1.score_status="1" '.$count_where.') as orders_updated_num ';	//订单更新数
				//$fields.= ',(SELECT DISTINCTROW sum(1) FROM `assessment_score` ase1 '.$count_tables.' WHERE ase1.field_name="orders_email_history_id" and ase1.score_type="1" and ase1.score_type_id=o.orders_id and ase1.score_status="1" '.$count_where.') as orders_email_num ';	//订单邮件数
				$fields.= ',(SELECT sum(score_value) FROM `assessment_score` ase1 '.$count_tables.' WHERE ase1.field_name="orders_sms_history_id" and ase1.score_type="1" and ase1.score_type_id=o.orders_id and ase1.score_status="1" '.$count_where.' group by ase1.score_type_id) as orders_sms_num ';	//订单短信发送数
				$fields.= ',(SELECT sum(score_value) FROM `assessment_score` ase1 '.$count_tables.' WHERE ase1.field_name="orders_status_history_id" and ase1.score_type="1" and ase1.score_type_id=o.orders_id and ase1.score_status="1" '.$count_where.' group by ase1.score_type_id) as orders_updated_num ';	//订单更新数
				$fields.= ',(SELECT sum(score_value) FROM `assessment_score` ase1 '.$count_tables.' WHERE ase1.field_name="orders_email_history_id" and ase1.score_type="1" and ase1.score_type_id=o.orders_id and ase1.score_status="1" '.$count_where.' group by ase1.score_type_id) as orders_email_num ';	//订单邮件数
					
				//相关订单总金额统计、总积分统计
				$statistical_sql = tep_db_query('SELECT DISTINCTROW ot.value FROM '.$tables.' WHERE '.$where.$group_by.$having);
				// 			echo 'SELECT DISTINCTROW ot.value FROM '.$tables.' WHERE '.$where.$group_by.$having;
				$statistical_money = 0;
				$statistical_score_value_total = $this->get_orders_score_sum($get);
				while($_rows = tep_db_fetch_array($statistical_sql)){
					$statistical_money += $_rows['value'];
					//$statistical_score_value_total += $_rows['score_value_total'];
				}
				break;
			}
		}
		
		$sql_str = 'SELECT DISTINCTROW '.$fields.' FROM '.$tables.' WHERE '.$where.$group_by.$having.$order_by;
		//echo $sql_str;exit;
		$MAX_DISPLAY_SEARCH_RESULTS_ADMIN = 50;
		$sql_numrows = 0;
		$split = new splitPageResults($get['page'], $MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $sql_str, $sql_numrows);
		$rows_query = tep_db_query($sql_str);

		$data = array();
		$data['list'] = array();
		$data['statistical_money'] = $statistical_money;
		$data['statistical_score_value_total'] = $statistical_score_value_total;
		$data['sql_numrows'] = $sql_numrows;
		while($rows = tep_db_fetch_array($rows_query)){
			$data['list'][] = $rows;
		}
		//积分查询 end }
		//分页的数组
		$data['split'] = array();
		if($data['list']){
			$data['split']['display_count'] = $split->display_count($sql_numrows, $MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $get['page'], 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> referrals)');
			$data['split']['display_links'] = $split->display_links($sql_numrows, $MAX_DISPLAY_SEARCH_RESULTS_ADMIN, 10, $get['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y')));

			return $data;
		}
		return false;
	}
	/**
	 * 取得后台积分统计汇总表(用于工作人员评分统计给Sofia他们看的)
	 * @param array $get GET参数
	 * @param string $admin_group 参与统计的人员，只限商务中心
	 * @example assessment_score_report.php
	 * @return array
	 */
	public function get_score_report_list(array $get, $admin_group = '7'){
		//if(!$get['time_start']) $get['time_start'] = '2000-01-01';
		//if(!$get['time_end']) $get['time_end'] = date('Y-m-d');
		//本方法的主表是管理员表
		//SQL汇集
		$fields = 'a.admin_id, a.admin_job_number ';
		$tables = 'admin a ';
		$where = '1 ';
		$group_by = '';
		$order_by = ' order by a.admin_job_number ';
		if($get['job_num']){
			$job_nums = explode(',', preg_replace('/[[:space:]]+/','',rawurldecode($get['job_num'])));
			$where.= ' and a.admin_job_number in('.implode(',', $job_nums).') ';
		}
		if($admin_group){
			$where.= ' and a.admin_groups_id in ('.$admin_group.') ';
		}
		if($get['add_time_start']){
			//$where .=' AND ';
		}
		$sql_str = 'SELECT '.$fields.' FROM '.$tables.' WHERE '.$where.$group_by.$order_by;
		
		$MAX_DISPLAY_SEARCH_RESULTS_ADMIN = 50;
		$split = new splitPageResults($get['page'], $MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $sql_str, $sql_numrows);
		$rows_query = tep_db_query($sql_str);
		
		$data = array();
		$data['list'] = array();
		$data['sql_numrows'] = $sql_numrows;
		
		$s_get = $get;
		//unset($s_get['job_num']);	//系统总积分要去除个人工号
		$system_score_sum = $this->get_system_score_sum($s_get);
		while($rows = tep_db_fetch_array($rows_query)){
			$a_get = $get;
			$a_get['job_num'] = $rows['admin_job_number'];
			$a_get['admin_id'] = $rows['admin_id'];
			$rows['adminScore'] = $this->get_system_score_sum($a_get);					//个人总积分
			$rows['adminVsSystemScore'] = (round(($rows['adminScore']/max(1,$system_score_sum)), 4)*100).'%';	//个人总积分:参与人的总积分
			$rows['ordersAmount'] = $this->get_orders_amount($a_get);					//订单金额
			$rows['ordersAmountVsAdminScore'] = '$'.number_format(($rows['ordersAmount']/max(1,$rows['adminScore'])),2,'.','');	//订单金额:个人总积分
			
			$data['list'][] = $rows;
		}
		//积分查询 end }
		//分页的数组
		$data['split'] = array();
		if($data['list']){
			$data['split']['display_count'] = $split->display_count($sql_numrows, $MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $get['page'], 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> referrals)');
			$data['split']['display_links'] = $split->display_links($sql_numrows, $MAX_DISPLAY_SEARCH_RESULTS_ADMIN, 10, $get['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y')));
		
			return $data;
		}
		return false;
	}
	/**
	 * 判断是否是登录的人在操作订单
	 * @param int $orders_id 订单ID
	 * @author wtj by 2013-07-30
	 * @return bool
	 */
	function checkLoginOwner($orders_id){
		$str_sql = 'select orders_owners from orders where orders_id='.(int)$orders_id;
		$arr_tmp = tep_db_fetch_array(tep_db_query($str_sql));
		//$arr_tmp['orders_owners'] = tep_db_get_field_value('orders_owners', 'orders', 'orders_id='.(int)$orders_id);
		$arr = explode(',',$arr_tmp['orders_owners']);
		$mark = true;
		$jobs_id=tep_get_job_number_from_admin_id($_SESSION['login_id']);
		foreach($arr as $value){
			if($value && $value == $jobs_id){
				$mark = false;
				break;
			}
		}
		return $mark;
	}
}

?>