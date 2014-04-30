<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-cache, must-revalidate");           // HTTP/1.1
header("Pragma: no-cache");

require_once('includes/application_top.php');
require_once(DIR_FS_INCLUDES . 'ajax_encoding_control.php');

if(tep_not_null($_GET['total_adult'])&&tep_not_null($_GET['products_id'])&&tep_not_null($_GET['departure_date'])){
	$check_sql = tep_db_query('SELECT remaining_seats_num FROM `products_remaining_seats` WHERE products_id ="'.tep_db_prepare_input($_GET['products_id']).'" AND departure_date ="'.tep_db_prepare_input($_GET['departure_date']).'"');
	if((int)tep_db_num_rows($check_sql)>'0'){

			$sql = tep_db_query('SELECT remaining_seats_num FROM `products_remaining_seats` WHERE products_id ="'.tep_db_prepare_input($_GET['products_id']).'" AND departure_date="'.tep_db_prepare_input($_GET['departure_date']).'"');
			$row = tep_db_fetch_array($sql);
			if($row['remaining_seats_num']<$_GET['total_adult']){
                echo '<a id="check_remaining_seats_buy" href="javascript:;" title="book_now_out" class="btnBuy btnBuyBook" onmousemove="check_remaining_seats()" onclick="alert(\''.(db_to_html('该团剩余座位小于订购人数,请选其它日期的团！')).'\')" ><ins><button type="submit">'.db_to_html('立即预订').'</button></ins></a>'; 
				              
				//echo tep_template_image_submit('book_now_out.gif', (db_to_html('订购并支付')), 'onclick="alert(\''.(db_to_html('该团剩余座位小于订购人数,请选其它日期的团！')).'\')" onmousemove="check_remaining_seats()"');
                                
			}else{
                               
                echo '<a id="check_remaining_seats_buy" href="javascript:;" title="book_now" class="btnBuy btnBuyBook" onmousemove="check_remaining_seats()" onclick="return SubmitCartQuantityFrom()" ><ins><button type="submit">'.db_to_html('立即预订').'</button></ins></a>'; 
				//echo tep_template_image_submit('book_now.gif', (db_to_html('订购并支付')), 'onclick="return validate()" onmousemove="check_remaining_seats()"');
                                
			}

    }else{
		echo '<a id="check_remaining_seats_buy" href="javascript:;" title="book_now" class="btnBuy btnBuyBook" onmousemove="check_remaining_seats()" onclick="return SubmitCartQuantityFrom()" ><ins><button type="submit">'.db_to_html('立即预订').'</button></ins></a>'; 
           //echo tep_template_image_submit('book_now.gif', (db_to_html('订购并支付')), 'onclick="return validate()" onmousemove="check_remaining_seats()""');
    }
}
?>