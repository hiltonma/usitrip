<?php
die("此页严重影响了服务器的稳定性，永久禁止访问！");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-cache, must-revalidate");           // HTTP/1.1
header("Pragma: no-cache");   

include_once "includes/configure.php";
include_once "includes/database_tables.php";
include_once "includes/functions/database.php";
include_once "includes/functions/html_output.php";
include_once "includes/functions/general.php";
include_once "includes/functions/sessions.php";
tep_db_connect();
$divedisplay_id = $_GET['cangeid'];

echo $_GET['cangeid'].'#####';



  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  

  
    if (isset($HTTP_GET_VARS['start_date'])) {
    $start_date = $HTTP_GET_VARS['start_date'];
  } else {
    $start_date = '';
  }

  if (isset($HTTP_GET_VARS['end_date'])) {
    $end_date = $HTTP_GET_VARS['end_date'];
  } else {
    $end_date = '';
  }

    switch ($_GET["sort"]) {
      case 'utm_medium':
        if($_GET["order"]=="ascending") {
          $sortorder = ' order by utm_medium asc';
        } else {
          $sortorder = ' order by utm_medium  desc';
        }
      break;
      case 'utm_source':
        if($_GET["order"]=="ascending") {
          $sortorder = ' order by customers_advertiser asc';
        } else {
          $sortorder = ' order by customers_advertiser desc';
        }
      break;
      default:        
          $sortorder = ' order by customers_advertiser  asc';       
		
      break;
    }

     $extragroupby = '  group by customers_advertiser';
	 
   if($start_date!='' && $end_date != '' ){
   $extrawhere = " AND  clicks_date BETWEEN '" . $start_date . "' AND '" . $end_date . " 23:59:59'  ";
    $extrawhere_total = " AND  date_purchased BETWEEN '" . $start_date . "' AND '" . $end_date . " 23:59:59'  ";
   }
   
?>  
						   <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#F1F5F5">
						   <?php
						    $ads['customers_advertiser'] = $HTTP_GET_VARS['customers_advertiser'];
							$ads_details['customers_advertiser'] = $HTTP_GET_VARS['customers_advertiser'];
 							 $ads_details['utm_medium'] =  $HTTP_GET_VARS['utm_medium'];
  							$ad_query_details_all['utm_term'] = $HTTP_GET_VARS['utm_term'];
						   $detailsand = " and customers_advertiser='".tep_db_input($ads['customers_advertiser'])."' ";
						   
						 
						   if($_GET['start_limit'] > 0 ){
							$limitfrst = 0;
							$limitend = $_GET['start_limit'] + 50;
							$key_limit = " limit ".$limitend;
						   }?>
						  
						   <?php
						   
						   $sortorder_all = ' order by key_cnt desc, utm_term asc '.$key_limit; 
						   $ad_query_details_raw_all = "select *, count(*) as key_cnt from ".TABLE_AD_SOURCE_CLICKS_STORES." where utm_medium = '".tep_db_input($ads_details['utm_medium'])."'  ".$extrawhere." ".$detailsand." group by utm_term ".$sortorder_all  ;
						  $ad_query_details_all_array = tep_db_query($ad_query_details_raw_all);
							$keyword_count = 0;
							while ($ad_query_details_all = tep_db_fetch_array($ad_query_details_all_array)) {
							$keyword_count++;
						   ?>
						  
						   <tr>
						   <td class="dataTableContent" height="16" width="30%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull;&nbsp;<? //=$keyword_count;?><?php echo tep_db_prepare_input($ad_query_details_all['utm_term']); ?></td>
						   <td class="dataTableContent" width="15%" style="text-indent: 1px;"><?php  echo tep_db_prepare_input($ad_query_details_all['key_cnt']); ?></td>
						   <td class="dataTableContent" width="15%" style="text-indent: 3px;">
						   <?php
							//amit added for individule query start
							$ad_query_raw_total_source = "select distinct orders.customers_advertiser, count(*) as count, sum(value) as total_value from " . TABLE_CUSTOMERS . ", " . TABLE_ORDERS . ", " . TABLE_ORDERS_TOTAL . ", ".TABLE_AD_SOURCE_CLICKS_STORES." as ascs WHERE ascs.clicks_id=orders.customers_ad_click_id and orders.customers_advertiser <> '' and ascs.utm_term = '".tep_db_input($ad_query_details_all['utm_term'])."'  AND ascs.utm_medium = '".tep_db_input($ads_details['utm_medium'])."' and orders.customers_advertiser ='".tep_db_input(tep_db_prepare_input($ads_details['customers_advertiser']))."' and orders.customers_advertiser = ascs.customers_advertiser   ".$extrawhere_total." AND customers.customers_id = orders.customers_id and orders.orders_id = orders_total.orders_id and class = 'ot_subtotal' group by ascs.utm_medium ORDER BY orders.customers_advertiser";
				 			$ad_query_total_source = tep_db_query($ad_query_raw_total_source);
				   			$ads_total_source = tep_db_fetch_array($ad_query_total_source);		
							//amit added for individule source end
							?>
							<?php				
							 echo (int)$ads_total_source['count']; 				 
							 ?>
						   <?php //echo tep_db_prepare_input($ad_query_details_all['utm_campaign']); ?></td>
						  <td class="dataTableContent" width="20%" align="right"><?php 
							echo '$'.number_format($ads_total_source['total_value'], 2, '.', '');					
							?>&nbsp;</td>
						  <td class="dataTableContent" width="20%" align="right">
						     <?php
							 if($ad_query_details_all['key_cnt']>0){
							 echo '$'.number_format($ads_total_source['total_value']/$ad_query_details_all['key_cnt'], 2, '.', '');			
							 }
							 ?>
						   </td>
						  </tr>						  
						   <?php }?>
						  
						  <tr>
						   <td class="dataTableContent" colspan="5" align="right">
						    <?php
								echo '<b><a  onclick="agent.call(\''.tep_href_link('stats_ad_results_details_ajax.php', 'start_limit='.$limitend.'&'. tep_get_all_get_params(array('action','start_limit','show_all'))).'\',\'hello\',\'callback_hello\');return false;" href="#">Show More 50</a></b>'; 
							?>
						  </td>
						   </tr>						  
						   </table>
						   
<?php 
  // STEP 1: INCLUDING AJAX AGENT FRAMEWORK/LIBRARY
  include_once("agent.php");
//    $agent->init(); 
?>
