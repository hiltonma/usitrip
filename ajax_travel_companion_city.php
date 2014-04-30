<?php 
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );

require_once('includes/application_top.php');
require_once(DIR_FS_INCLUDES . 'ajax_encoding_control.php');
require_once DIR_FS_CLASSES . 'companions_line_filter.php';
$categories_id = $_POST['id'];
$action = $_POST['action'];

$start_city_id = $_POST['start_id'];
$end_city_id = $_POST['end_id'];

$compan_lin_filter = new companions_line_filter($categories_id);
switch ($action) {
	case "products":
		$products = $compan_lin_filter->get_products_name($start_city_id, $end_city_id);
		echo json_encode(array_iconv($products));
		break;
	default:
		$start_city = $compan_lin_filter->get_departure_city();

		$end_city = $compan_lin_filter->get_end_departure_city();
		
		$str =  '{"start_city" : ';
		if (count($start_city) > 0) {
			$start_city = array_iconv($start_city);
			$str .= json_encode($start_city);
		} else {
			$str .= '{}';
		}
		$str .= ',"end_city" : ';
		if (count($end_city) > 0){
			$end_city = array_iconv($end_city);
			$str .= json_encode($end_city);
		}else {
			$str .= '{}';
		}
		$str .= '}';
		echo $str;
}

?>