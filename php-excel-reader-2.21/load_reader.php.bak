<?php 
/**
 * 调用Excel阅读类
 * @author Howard Administrator
 */
class load_cxcel_reader{
	public $data;
	public $charset;
	/**
	 * Excel阅读类
	 * @param string $file excel 文件名
	 * @param string $out_charset 输出的字符编码,gb2312,utf-8,big5等 
	 */
	public function __construct($file, $out_charset = 'gb2312'){
		require_once 'excel_reader2.php';
		$this->data = new Spreadsheet_Excel_Reader($file, true, $out_charset);
		$this->charset = $out_charset;
	}
	/**
	 * 输出
	 */
	public function output(){
		header("Content-type: text/html; charset=".$this->charset);
		echo '<html><head>';
		echo $this->css();
		echo $this->js();
		echo '</head><body oncontextmenu="return false;" onselectstart="return false;" oncopy="return false;" onsave="return false;" oncut="return false;" ondragstart="return false;">';
		//echo '<noscript><iframe src="*.html"></iframe></noscript>';
		echo $this->data->dump(true,true);
		echo '</body></html>';
	}
	/**
	 * js代码
	 */
	private function js(){
		return
		'<script type="text/javascript">
		
		</script>
		';
	}
	/**
	 * 样式表
	 */
	private function css(){
		return 
		'
		<style>
		@media print{body{display:none}}
		table.excel {
			border-style:ridge;
			border-width:1;
			border-collapse:collapse;
			font-family:sans-serif;
			font-size:12px;
		}
		table.excel thead th, table.excel tbody th {
			background:#CCCCCC;
			border-style:ridge;
			border-width:1;
			text-align: center;
			vertical-align:bottom;
		}
		table.excel tbody th {
			text-align:center;
			width:20px;
		}
		table.excel tbody td {
			vertical-align:bottom;
		}
		table.excel tbody td {
			padding: 0 3px;
			border: 1px solid #EEEEEE;
		}
		</style>
		';
	}
}
?>