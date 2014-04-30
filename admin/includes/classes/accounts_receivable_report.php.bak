<?php
/**
 * 生成到账图形报表类
 * @author Howard
 */
class accounts_receivable_report{
	/**
	 * 待输出的json数组
	 * @var array
	 */
	private $jsonData = array();
	/*
	 * 报表宽度
	 */
	private $pic_width = 800;
	/**
	 * 报表高度
	 */
	private $pic_height = 670;
	/**
	 * 初始化时传入GET数组
	 * @param array $get
	 */
	public function __construct(array $get){
		$this->jsonData['chart']['numberPrefix'] = '$';			//数字前缀
		$this->jsonData['chart']['formatNumberScale'] = '0';	//是否格式化数字,默认为1(True),自动的给你的数字加上K（千）或M（百万）；若取0,则不加K或M
		$this->jsonData['chart']['baseFontSize'] = '12';		//图表字体大小
		$this->jsonData['chart']['formatNumber'] = '0';			//逗号来分隔数字(千位，百万位),默认为1(True)；若取0,则不加分隔符
		
		$this->jsonData['chart']['yaxisname'] = iconv('gb2312','utf-8','到账报表');
		
		$this->tables = ' `orders_payment_history` oph, orders o  ';
		$this->where = ' 1 and oph.orders_id=o.orders_id ';
		$this->sql_str = 'SELECT SUM(oph.orders_value) as TotalRevenue FROM '.$this->tables.' WHERE '.$this->where;
		$date_start = date('Y-m-d',strtotime($get['add_date_start']));
		$date_end = date('Y-m-d',strtotime($get['add_date_end']));
	
		switch($get['reportType']){
			case 'year':	//生成年度报表数据				
				$Y_start = date('Y',strtotime($date_start));
				$Y_end = date('Y',strtotime($date_end));
				$this->getDataYear($Y_start, $Y_end);
				break;
			case 'week':	//生成当周报表数据
				$this->getDataWeek($date_start);
				break;
			case 'day':		//生成当月每天数据
				$this->getDataDay(date('Y-m',strtotime($date_start)));
				break;
			case 'month':	//生成月度报表数据
				$this->getDataMonth(date('Y',strtotime($date_start)));
				break;
			case 'hours':	//生成小时报表数据
				$this->getDataHours(date('Y-m-d',strtotime($date_start)));
				break;
			default:
				break;
		}	
	}
	/**
	 * 取得年数据
	 * @param int $start_year 起始年
	 * @param int $end_year 结束年
	 */
	public function getDataYear($start_year, $end_year){
		$this->jsonData['chart']['caption'] = iconv('gb2312','utf-8',$start_year.'-'.$end_year."年度到账报表");
		$this->jsonData['chart']['xaxisname'] = iconv('gb2312','utf-8',$start_year.'-'.$end_year);
		for($i=$start_year; $i<=$end_year; $i++){
			$sql = $this->sql_str.' and oph.add_date Like "'.$i.'-%" ';
			//echo $sql."\n"; 
			$sql = tep_db_query($sql);
			$array['data'] = array();
			while ($rows = tep_db_fetch_array($sql)){
				$this->jsonData['data'][] = array('label'=>$i.iconv('gb2312','utf-8','年'), 'value'=>$rows['TotalRevenue']);
			}
		}
	}
	/**
	 * 取得月数据
	 * @param int $year 要统计的年
	 */
	public function getDataMonth($year){
		$this->jsonData['chart']['caption'] = iconv('gb2312','utf-8',$year."年每月到账报表");
		$this->jsonData['chart']['xaxisname'] = iconv('gb2312','utf-8','1-12月');
		for($i=1; $i<=12; $i++){
			$sql = $this->sql_str.' and oph.add_date Like "'.(int)$year.'-'.str_pad($i,2,'0',STR_PAD_LEFT).'-%" ';
			$sql = tep_db_query($sql);
			$array['data'] = array();
			while ($rows = tep_db_fetch_array($sql)){
				$this->jsonData['data'][] = array('label'=>str_pad($i,2,'0',STR_PAD_LEFT).iconv('gb2312','utf-8','月'), 'value'=>$rows['TotalRevenue']);
			}
		}	
	}
	/**
	 * 取得每天数据
	 * @param int $year_month 要统计的年月2013-09
	 */
	public function getDataDay($year_month){
		$this->pic_width = 1200;
		$max_i = date('t',strtotime($year_month));
		$this->jsonData['chart']['caption'] = iconv('gb2312','utf-8',$year_month."月每天到账报表");
		$this->jsonData['chart']['xaxisname'] = iconv('gb2312','utf-8','1-'.$max_i.'日');
		for($i=1; $i<=$max_i; $i++){
			$sql = $this->sql_str.' and oph.add_date Like "'.(string)$year_month.'-'.str_pad($i,2,'0',STR_PAD_LEFT).' %" ';
			$sql = tep_db_query($sql);
			$array['data'] = array();
			while ($rows = tep_db_fetch_array($sql)){
				$this->jsonData['data'][] = array('label'=>str_pad($i,2,'0',STR_PAD_LEFT).iconv('gb2312','utf-8','日'), 'value'=>$rows['TotalRevenue']);
			}
		}
	}
	/**
	 * 取得当天每小时数据
	 * @param int $year_month 要统计的年月日2013-09-16
	 */
	public function getDataHours($year_month_day){
		$this->pic_width = 1200;
		$max_i = 23;
		$this->jsonData['chart']['caption'] = iconv('gb2312','utf-8',$year_month_day."每小时到账报表");
		$this->jsonData['chart']['xaxisname'] = iconv('gb2312','utf-8','0-'.$max_i.'点');
		for($i=0; $i<=$max_i; $i++){
			$sql = $this->sql_str.' and oph.add_date Like "'.(string)$year_month_day.' '.str_pad($i,2,'0',STR_PAD_LEFT).':%" ';
			$sql = tep_db_query($sql);
			$array['data'] = array();
			while ($rows = tep_db_fetch_array($sql)){
				$this->jsonData['data'][] = array('label'=>str_pad($i,2,'0',STR_PAD_LEFT).iconv('gb2312','utf-8','点'), 'value'=>$rows['TotalRevenue']);
			}
		}
	}
	/**
	 * 取得周数据
	 * @param Y-m-d $date 要统计当周所在日期2013-09-01
	 */
	public function getDataWeek($date){
		$now_week = date('w',strtotime($date));
		$week_start_date = date('Y-m-d', strtotime($date)-($now_week*86400));
		$whereDate = $week_start_date;
		$this->jsonData['chart']['caption'] = iconv('gb2312','utf-8',$date."当周到账报表");
		$this->jsonData['chart']['xaxisname'] = iconv('gb2312','utf-8','周日至周六');
		for($i=0; $i<=6; $i++){
			$sql = $this->sql_str.' and oph.add_date Like "'.(string)$whereDate.' %" ';
			$sql = tep_db_query($sql);
			$array['data'] = array();
			while ($rows = tep_db_fetch_array($sql)){
				$this->jsonData['data'][] = array('label'=>date('D(m-d)',strtotime($whereDate)), 'value'=>$rows['TotalRevenue']);
			}
			$whereDate = date('Y-m-d', strtotime($whereDate)+86400);
		}
	}
	/**
	 * 生成json数据
	 * var objJSON = {
     "chart": {
         "caption": "本周报告到账报表",
         "xaxisname": "本周报告",
         "yaxisname": "到账报表",
         "numberPrefix": "￥"
     },
     "data": [
         {
             "label": "星期日",
             "value": "14400"
         },
         {
             "label": "星期一",
             "value": "14400"
         }
     ]
                            };
	 */
	public function jsonData() {
		/* $array = array ();
		$array['chart'] = array (
								"caption" => iconv('gb2312','utf-8',"本周报告到账报表"),
								"xaxisname" => iconv('gb2312','utf-8',"本周报告"),
								"yaxisname" => iconv('gb2312','utf-8',"到账报表"),
								"numberPrefix" => "$" 
		);
		$array['data'] = array();
		$array['data'][] = array('label'=>'001','value'=>"14400");
		$array['data'][] = array('label'=>'002','value'=>"16000"); */
		return json_encode($this->jsonData);
	}
	/**
	 * 输出html图形代码
	 * @return string
	 */
	public function output(){
		$str = 
		'<script type="text/javascript" src="/includes/javascript/chart_report/FusionCharts.js"></script>
		<div id="chartContainer"></div>
		<script type="text/javascript">
			var objJSON = '.$this->jsonData().';
			var myChart = new FusionCharts("/includes/javascript/chart_report/Column3D.swf", "myChartId", "'.$this->pic_width.'", "'.$this->pic_height.'", "0", "0" );
            myChart.setJSONData(objJSON);
            myChart.render("chartContainer");
		</script>';
		return $str;
	}
}
?>