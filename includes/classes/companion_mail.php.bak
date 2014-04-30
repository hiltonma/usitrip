<?php 
/**
 * 结伴同游基础类 请不要直接实例化
 * @author lwkai 2012-06-11
 *
 */
class companion_mail extends send_mail_ready {
	/**
	 * 结伴同游发起贴ID
	 * @var int
	 */
	protected $companion_id = 0;

	/**
	 * 结伴同游发起人ID
	 * @var int
	 */
	protected $customers_id = 0;

	/**
	 * 所属栏目ID
	 * @var int
	 */
	protected $categories_id = 0;

	/**
	 * 结伴同游标题
	 * @var string
	 */
	protected $companion_title = '';

	/**
	 * 发送邮件开关 默认是关闭发送邮件的。
	 * @var string 'true|false'
	 */
	protected $travel_companion_email_switch = 'false';

	/**
	 * 产品ID
	 * @var int
	 */
	protected $products_id = 0;
	
	/**
	 * 产品页面名称 带.html
	 * @var string
	 */
	protected $products_urlname = '';
	
	/**
	 * 产品名称标题
	 * @var string
	 */
	protected $products_name = '';
	
	/**
	 * 构造函数
	 */
	public function __construct(){
		if ($this->travel_companion_email_switch == 'true') {
			parent::__construct();
		}
	}
	
	protected function add_session(){
		if ($this->travel_companion_email_switch == 'true') {
			parent::add_session();
		}
	}

	/**
	 * 取得结伴同游发起人的相关信息
	 */
	protected function get_companion_user_info(){
		//print_r('SELECT customers_id,customers_name,email_address,categories_id,t_companion_title FROM `travel_companion` WHERE t_companion_id="' . $this->companion_id . '" Limit 1 ');
		$mail_sql = tep_db_query('SELECT customers_id,customers_name,email_address,categories_id,t_companion_title,products_id FROM `travel_companion` WHERE t_companion_id="' . $this->companion_id . '" Limit 1 ');
		$mail_row = tep_db_fetch_array($mail_sql);
		$this->customers_id = $mail_row['customers_id'];
		$this->to_name = strip_tags($mail_row['customers_name']) . " ";
		$this->to_email_address = strip_tags($mail_row['email_address']);
		$this->categories_id = $mail_row['categories_id'];
		$this->companion_title = $mail_row['t_companion_title'];
		$this->products_id = $mail_row['products_id'];
	}
	
	/**
	 * 取得对应产品标题与URL页面地址
	 * @param int $products_id 产品ID
	 */
	protected function get_products_info($products_id = 0){
		if ((int)$products_id == 0) {
			$products_id = $this->products_id;
		}
		if($products_id > 0) {
			/* 取得结伴同游所在线路的名称和URL地址 */
			$sql = tep_db_query("select p.products_urlname,pd.products_name from products as p,products_description as pd where p.products_id=pd.products_id and p.products_id='" . $products_id . "'");
			if (tep_db_num_rows($sql) > 0) {
				$product_row = tep_db_fetch_array($sql);
				$this->products_urlname = $product_row['products_urlname'] . '.html';
				$this->products_name = $product_row['products_name'];
			}
		}
		
	}
}

?>
