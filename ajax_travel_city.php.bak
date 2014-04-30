<?php
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
require_once ('includes/application_top.php');
$city = (iconv('utf-8', CHARSET.'//IGNORE',($_POST['city'])));
// var_dump(html_to_db(tep_db_input($city)));
$city = html_to_db(tep_db_input($city));
$a=count($city_array1 = explode(',', $city));
$b=count($city_array2 = explode(' ',$city));
$c=count($city_array3 =explode('гм',$city));
$x=max($a,$b,$c);
switch($x){
	case $a : $city_array=$city_array1;break;
	case $b : $city_array=$city_array2;break;
	case $c : $city_array=$city_array3;break;
	default: $city_array=$city_array1;break;
}
$city = array_pop($city_array);
$return = array ();
if ($city) {
	$str_sql = 'select city_id,city from tour_city where city like BINARY "%' . ($city). '%" limit ' . (int) $_POST['limit'];
	$sql_query = tep_db_query($str_sql);
	while ($row = tep_db_fetch_array($sql_query)) {
		$return[] = array (
				'id' => $row['city_id'],
				'text' => (iconv(CHARSET, 'utf-8'.'//IGNORE',db_to_html($row['city'])))
		);
	}
}
echo json_encode($return);
?>