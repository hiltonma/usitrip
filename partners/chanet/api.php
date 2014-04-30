<?php
//成果网CPS API 接口
class chanet_api{
	var $SID; 
	var $db_table = "partners_sales_records";	//销售记录数据表
	function chanet_api() {
		if(tep_not_null($_GET['partners_id']) && tep_not_null($_GET['partners_source']) && $_GET['partners_source']=="chanet"){
			$this->SID = number_format($_GET['partners_id'],'','','');
			$this->set_cookie();	//记录cookie
			$this->create_table();
		}
    }
	function create_table(){	//创建表
		$sql = tep_db_query('CREATE TABLE IF NOT EXISTS '.$this->db_table.'
							(
							  records_id int(10) unsigned NOT NULL auto_increment,
							  records_time datetime NOT NULL,
							  s_id varchar(20) NOT NULL,
							  orders_id int(11) NOT NULL,
							  PRIMARY KEY  (records_id)
							);
							');
	}
	
	function set_cookie($period_day = 30){
	//如果是从成果网过来的页面则设置COOKIE记录，日期保留为30天该cookie地值为最后访问的时间
		setcookie('partners[chanet][time]',time(), time()+(86400*$period_day));
		setcookie('partners[chanet][SID]',$this->SID, time()+(86400*$period_day));
	}
	
	
	function save_sales_records($orders_id){ //写销售记录
		$s_id = $this->get_sid();
		if(!(int)$orders_id || !(int)$s_id){ return false; }
		$check_sql = tep_db_query('select orders_id from '.$this->db_table.' WHERE orders_id="'.(int)$orders_id.'" Limit 1');
		$check_row = tep_db_fetch_array($check_sql);
		if(!(int)$check_row['orders_id']){
			$data_array = array('records_time'=> date("Y-m-d H:i:s"),
								's_id'=> $s_id,
								'orders_id'=>(int)$orders_id
								);
			tep_db_perform($this->db_table, $data_array);
		}
		
	}
	
	function get_sid(){	//取得成果网SID COOKIE记录，用于下订单时记录相关销售信息
		return $_COOKIE['partners']['chanet']['SID'];
	}
	
	function ouput_sales_lists(){	//输出销售记录
		$array = "";
		$SetUserName = "chanet";
		$SetPass = "12ae56fbaad00b02";
		if($_GET['userName']!=$SetUserName || $_GET['pwd']!=$SetPass){
			echo "user name or password error.";
			return false;
		}
		$where_exp = '';
		if(tep_not_null($_GET['startdate'])){
			if(check_date($_GET['startdate'])==false){
				echo "startdate error for date format. eg. 2010-10-01";
				exit;
			}
			$where_exp .= ' and o.date_purchased >="'.$_GET['startdate'].' 00:00:00" ';
		}
		if(tep_not_null($_GET['enddate'])){
			if(check_date($_GET['enddate'])==false){
				echo "enddate error for date format. eg. 2010-10-31";
				exit;
			}
			$where_exp .= ' and o.date_purchased <="'.$_GET['enddate'].' 23:59:59" ';
		}
		if($_GET['orders_status']=="all"){
			//$where_exp .= ' and o.orders_status!="6" ';
		}else{
			$where_exp .= ' and o.orders_status="100006" ';
		}
		$sql = tep_db_query('select psr.orders_id, psr.s_id, o.date_purchased, o.orders_status, ot.value from '.$this->db_table.' psr, orders o, orders_total ot WHERE psr.orders_id=o.orders_id AND psr.orders_id=ot.orders_id AND ot.class="ot_total" '.$where_exp.' Order By psr.orders_id ASC');
		
		$orders_obj = array();
		while($rows=tep_db_fetch_array($sql)){
			//取得产品分类和数量
			//$p_sql = tep_db_query('select cd.categories_name from orders_products op, products_to_categories ptc, categories_description cd WHERE op.orders_id="'.$rows['orders_id'].'" and ptc.categories_id = cd.categories_id and op.products_id = ptc.products_id and cd.language_id="1" and cd.categories_name!="" Group By op.products_id ');
			$p_sql = tep_db_query('select p.products_model, op.products_departure_date from orders_products op, products p WHERE op.orders_id="'.$rows['orders_id'].'" and p.products_id = op.products_id Group By op.products_id ');
			$prod_num = 0;
			$cate_name = "";
			$has_finish = true;
			while($p_rows=tep_db_fetch_array($p_sql)){
				$prod_num++;
				$cate_name .=str_replace(' ','_',$p_rows['products_model']).";";
				if(substr($p_rows['products_departure_date'],0,10)>date("Y-m-d") && tep_not_null($p_rows['products_departure_date']) && $_GET['orders_status']!="all"){
					$has_finish = false;
				}
				$status_string = "未完成";
				if($rows['orders_status']=="100006" && substr($p_rows['products_departure_date'],0,10)<=date("Y-m-d")){
					$status_string = "已结束";
				}
				if($rows['orders_status']=="6"){
					$status_string = "已取消";
				}
			}
			$cate_name = substr($cate_name,0, -1);
			if($has_finish == true){
				$orders_obj[]=array('time'=>$rows['date_purchased'], 'SID'=>$rows['s_id'], 'order_id'=>$rows['orders_id'], 'price'=>number_format($rows['value'],2,'.',''),'prod_num'=>$prod_num, 'cate_name'=>$cate_name, 'status'=>$status_string);
			}
		}
		$array = $orders_obj;
		return $array;
	}
}
?>