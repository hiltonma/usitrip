<?php
require('global.php');
//====check user=====================================
$proUser = '';
if(isset($_GET['u']) && !empty($_GET['u'])){
	$proUser = trim($_GET['u'].'');
}
$proPass = '';
if(isset($_GET['p']) && !empty($_GET['p'])){
	$proPass = trim($_GET['p'].'');
}
if(empty($proUser) || empty($proPass)){
	message('please give me "u" and "p" parameters!');
}
$userDir = API_ROOT.'userdir/'.$proUser.'/';
$userProductsData = $userDir.'#productsdata';
if(!is_dir($userDir)){
	message('user "'.$proUser.'" is not exists!');
}
//=========check user end==============================
//==============验证缓存和更改 start===================
$userProductsData_xls = $userProductsData.'.xls';
$userProductsData_php = $userProductsData.'.php';
$userProductsData_cash = $userProductsData.'.cash';
$modifiedTime_xls = DlFilemtime($userProductsData_xls);
$modifiedTime_cash = intval(readover($userProductsData_cash));
//==============验证缓存和更改 end=====================
//==========载入或生成cash start=======================
if($modifiedTime_xls!=$modifiedTime_cash){
	require_once(API_ROOT.'PHPExcel/PHPExcel.php');
	require_once(API_ROOT.'PHPExcel/PHPExcel/IOFactory.php');
	require_once(API_ROOT.'PHPExcel/PHPExcel/Reader/IReadFilter.php');
	/*
	class MyReadFilter implements PHPExcel_Reader_IReadFilter{
		public function readCell($column, $row, $worksheetName = '') {
			if ($row > 1){//排除第一行标题
				return true;
			}
			return false;
		}
	}
	*/
	$objReader = PHPExcel_IOFactory::createReader('Excel5');
	//$objReader->setReadFilter(new MyReadFilter());
	$objReader->setReadDataOnly(true);
	$objPHPExcel = $objReader->load($userProductsData_xls);
	$objSheet = $objPHPExcel->getSheet(0);
	$allRow = $objSheet->getHighestRow();
	$allColumn = $objSheet->getHighestColumn();
	$allColumnIndex = PHPExcel_Cell::columnIndexFromString($allColumn);
	
	$dataString = "<?php\r\n";
	$checkproPass = '';
	$productsData = array();
	for($row = 1; $row <= $allRow; ++$row) {
		for ($col = 0; $col <= $allColumn; ++$col) {
			$value = $objSheet->getCellByColumnAndRow($col,$row)->getValue();
			if(!empty($value)){
				if($row == 1){//第一行为设置的utm_medium类型
					$utmMedium = $value;
					$dataString .= '$utmMedium = '.var_export($utmMedium,true).";\r\n";
				}elseif($row == 2){//第二行为设置的utm_term起始时间
					$utmTerm = str_replace('#','-',$value);
					$dataString .= '$utmTerm = '.var_export($utmTerm,true).";\r\n";
				}elseif($row == 3){//第三行为设置的密码
					$checkproPass = $value;
					$dataString .= '$checkproPass = '.var_export($checkproPass,true).";\r\n";
				}else{
					$pid = explode('-',$value);
					$pid = $pid[count($pid)-1];
					$productsData[$pid] = $value;
				}
			}
		}
	}
	$dataString .= '$productsData = '.var_export($productsData,true);
	$dataString .= "\r\n?>";
	writeover($userProductsData_php,$dataString);
	writeover($userProductsData_cash,$modifiedTime_xls);
}else{
	require_once($userProductsData_php);
}
//==========载入或生成cash end=======================
//==========check user pass start====================
if($proPass!==$checkproPass){
	message('password is error!');
}
//==========check user pass end======================
?>