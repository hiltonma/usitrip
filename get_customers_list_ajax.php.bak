<?php
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );

require('includes/application_top.php');
require(DIR_FS_INCLUDES . 'ajax_encoding_control.php');

//$cus_sql = tep_db_query('SELECT customers_email_address FROM `customers` WHERE customers_email_address like("'.(string)$_GET['key'].'%") Order By customers_email_address ');
$cus_sql = tep_db_query('SELECT customers_email_address, customers_firstname FROM `customers` WHERE customers_email_address !="" Order By customers_email_address ');
$i=0;
$string="";
while($cus_rows = tep_db_fetch_array($cus_sql)){
	$string.= 'email_array['.$i.']="'.$cus_rows['customers_email_address'].'"; ';
	if(!preg_match('/\d+/', $cus_rows['customers_firstname'])){
		$string.= 'firstname_array['.$i.']="'.db_to_html(strip_tags($cus_rows['customers_firstname'])).'"; ';
	}else{
		$string.= 'firstname_array['.$i.']=""; ';
	}
	$i++;
}

echo $string;

exit;
?>
