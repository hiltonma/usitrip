<?php
require('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ETICKET);
require(DIR_WS_CLASSES.'split_page_results_outer.php');
require(DIR_WS_CLASSES . 'currencies.php');
header("Content-type: text/html; charset=".CHARSET."");

if(isset($HTTP_GET_VARS['action']) && $HTTP_GET_VARS['action']=='ajax_eticket_log_content')
  { //for ajax purpose
  	$eticket_logid = $HTTP_GET_VARS['eticket_logid'];
	$get_eticket_log_content = tep_db_query("select orders_eticket_content from ".TABLE_ORDERS_ETICKET_LOG." where orders_eticket_log_id = '".(int)$eticket_logid."'");
	if(tep_db_num_rows($get_eticket_log_content) > 0){
		$eticket_log_content = tep_db_fetch_array($get_eticket_log_content);		
		echo $eticket_logid . '|#####|' . $eticket_log_content['orders_eticket_content'];
	}
  exit();
  }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
		<title><?php echo TITLE; ?></title>
		<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
		<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">

<script type="text/javascript" src="includes/javascript/spiffyCal/spiffyCal_v2_1_2008_04_01-min.js"></script>

<script type="text/javascript" src="includes/javascript/categories.js"></script>
<script type="text/javascript"><!--
function popupImageWindow(url) {
  window.open(url,'popupImageWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=400,height=250,screenX=150,screenY=50,top=175,left=300')
}
function settle_ment_total_div(divid){
	if(document.getElementById(divid).style.display=="none"){
		document.getElementById(divid).style.display="";
	}else{
		document.getElementById(divid).style.display="none";
	}
}
//--></script>

<script language="JavaScript" type="text/javascript"> 
        function createRequestObject(){
        var request_;
        var browser = navigator.appName;
        if(browser == "Microsoft Internet Explorer"){
         request_ = new ActiveXObject("Microsoft.XMLHTTP");
        }else{
         request_ = new XMLHttpRequest();
        }
    return request_;
    }
    //var http = createRequestObject();
    var http1 = createRequestObject();
	
	function get_eticket_log_content(eticket_logid)
	{
		if(document.getElementById("row_eticket_log_content_"+eticket_logid).style.display=="none"){
			document.getElementById("row_eticket_log_content_"+eticket_logid).style.display="";
			try{
					http1.open('get', 'eticket_log.php?eticket_logid='+eticket_logid+'&action=ajax_eticket_log_content');
					http1.onreadystatechange = hendleInfo_eticket_log_content;
					http1.send(null);
			}catch(e){ 
				//alert(e);
			}
		}else{
			document.getElementById("row_eticket_log_content_"+eticket_logid).style.display="none";			
		}
	}
			
	function hendleInfo_eticket_log_content()
	{
		
		if(http1.readyState == 4)
		{
		 var response = http1.responseText;
		 //alert(response);
		 var response_split = response.split("|#####|");
		 document.getElementById("div_eticket_log_content_"+response_split[0]).innerHTML = response_split[1];		 
		}
	}
	</script>
<div id="spiffycalendar" class="text"></div>


	</head>
	<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
		<? if ( ! $_REQUEST['print'] ) require(DIR_WS_INCLUDES . 'header.php'); ?>
		<table border="0" width="100%" cellspacing="2" cellpadding="2">
  		<tr>

  			<td valign="top">
					<table border="0" width='100%' cellspacing="0" cellpadding="2">
						<tr>
							<td class="pageHeading" width="40%"><?= HEADING_TITLE ?></td>
                            <td width="60%" class="smallText" align="left">
                                <table>
                                <tr><td bgcolor="#F3EEE0" width="15">&nbsp;</td><td>Update completed by CSR</td></tr>
                                <tr><td bgcolor="#F4CFF4" width="15">&nbsp;</td><td>Update completed by Provider</td></tr>
                                </table>
                            </td>
                            <td valign="top">
								<?php echo tep_draw_form('search', FILENAME_ETICKET_LOG, '', 'get'); ?>
								
								
								<table  cellpadding="2" cellspacing="0" border="0">
									<tr>
										<td class="smallText" ><?php echo HEADING_TITLE_SEARCH_BY; ?></td>
										<td class="smallText"><?php echo tep_draw_input_field('search', $HTTP_GET_VARS['search'], '80'); ?></td>
                                        <td class="smallText" valign="middle" style="padding-top:10px;">
											<?php echo tep_image_submit('button_search.gif', IMAGE_SEARCH).'&nbsp;'; ?>
										</td>
									</tr>
								</table>
								<?php
								echo '</form>';
								?>
							</td>
						</tr>
					</table>
				<?php
				/////   start - search coding    ///////
				$where = ' ';
  				if (isset($HTTP_GET_VARS['search']) && tep_not_null($HTTP_GET_VARS['search'])) {
					 $keywords = tep_db_input(tep_db_prepare_input($HTTP_GET_VARS['search']));
					 $where .= " and op.orders_id='".$keywords."' ";
					
			    }
				/////   end - search coding    ///////
				?>
						<table border="0" width='100%' cellspacing="1" cellpadding="2">
						<?php echo tep_draw_form('orderitemcheck', FILENAME_ETICKET_LOG, tep_get_all_get_params(array('selected_box','action')).'action=proccess_payment', 'post', ' id="orderitemcheck"');
						?>						
						<tr class="dataTableHeadingRow">
							<td class="dataTableHeadingContent" width="5%">Order ID</td>
							<td class="dataTableHeadingContent">Order Items</td>
							<td class="dataTableHeadingContent">Last Modify Date &nbsp; 
							<?php echo '<a href="' . $_SERVER['PHP_SELF'] . '?'.tep_get_all_get_params(array('page', 'sort', 'x', 'y', 'order')).'sort=lastmodify&order=ascending">&nbsp;<img src="'.DIR_WS_IMAGES.'arrow_up.gif" border="0"></a><a href="' . $_SERVER['PHP_SELF'] . '?'.tep_get_all_get_params(array('page', 'sort', 'x', 'y', 'order')).'sort=lastmodify&order=decending">&nbsp;<img src="'.DIR_WS_IMAGES.'arrow_down.gif" border="0"></a>'; ?>
                            </td>
							<td class="dataTableHeadingContent">Status Changed By &nbsp; 
							<?php echo '<a href="' . $_SERVER['PHP_SELF'] . '?'.tep_get_all_get_params(array('page', 'sort', 'x', 'y', 'order')).'sort=updateby&order=ascending">&nbsp;<img src="'.DIR_WS_IMAGES.'arrow_up.gif" border="0"></a><a href="' . $_SERVER['PHP_SELF'] . '?'.tep_get_all_get_params(array('page', 'sort', 'x', 'y', 'order')).'sort=updateby&order=decending">&nbsp;<img src="'.DIR_WS_IMAGES.'arrow_down.gif" border="0"></a>'; ?>
                            </td>			
							<td class="dataTableHeadingContent">Notified Customer?</td>					
							<td class="dataTableHeadingContent">E-ticket Updates</td>					
						</tr>
						<?php
				
				 			   switch ($_GET["sort"]) {
								  case 'lastmodify':
									if($_GET["order"]=='ascending') {
									  $sortorder = 'order by el.orders_eticket_last_modified asc';
									} else {
									  $sortorder = 'order by el.orders_eticket_last_modified desc';
									}
									break;
								  case 'updateby':
									if($_GET["order"]=='ascending') {
									  $sortorder = 'order by el.orders_eticket_updated_by asc';
									} else {
									  $sortorder = 'order by el.orders_eticket_updated_by desc';
									}
									break;
								  default:									
									$sortorder = 'order by el.orders_eticket_log_id desc ';
									break;
								}
							
							
						$prods_query_sql = "select op.orders_id, op.products_id, op.products_model, el.*, (select GROUP_CONCAT(concat(el1.orders_eticket_last_modified,'||', el1.orders_eticket_updated_by,'||', el1.orders_eticket_is_customer_notified,'||', el1.orders_products_id,'||', el1.orders_eticket_log_id,'||', el1.orders_eticket_updator_type)  order by el1.orders_eticket_log_id desc SEPARATOR '##') as eticket_logs_subdata from ".TABLE_ORDERS_ETICKET_LOG." el1 where el1.orders_products_id = el.orders_products_id) as eticket_logs_data from ".TABLE_ORDERS_PRODUCTS." op, ".TABLE_ORDERS_ETICKET_LOG." el LEFT JOIN ".TABLE_ORDERS_ETICKET_LOG." el2 ON el.orders_products_id = el2.orders_products_id AND el.orders_eticket_log_id < el2.orders_eticket_log_id
AND el.orders_eticket_last_modified < el2.orders_eticket_last_modified where op.orders_products_id = el.orders_products_id and el2.orders_eticket_last_modified IS NULL ".$where." group by el.orders_products_id ".$sortorder." ";	
					    $product_split = new splitPageResults1($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $prods_query_sql, $prods_query_numrows);
							
							 $prods_query = tep_db_query($prods_query_sql);
							 
							 while ($prods = tep_db_fetch_array($prods_query)) {						
								
								if($prods['orders_payment_method'] != ''){
									$prods['payment_method'] = $prods['orders_payment_method'];
								}
									$table_select_row_color = 'bgcolor="#F9F7F0"';
									
									if((int)$prods['orders_eticket_updator_type']>0){
										$select_row_color = 'bgcolor="#F4CFF4"';
										$prods['orders_eticket_updated_by'] = tep_get_tour_agency_name_from_product_id($prods['products_id']).' - '.$prods['orders_eticket_updated_by'];
									}else{
										$select_row_color = 'bgcolor="#F3EEE0"';
										$prods['orders_eticket_updated_by'] = STORE_NAME.' - '.$prods['orders_eticket_updated_by'];
									}
								?>
	  
									<tr <?php echo $select_row_color;?>>
									<td class="dataTableContent" height="20"><?php echo '<a href="' . tep_href_link(FILENAME_EDIT_ORDERS, 'oID=' . $prods['orders_id']) . '" target="_blank"><b>'.$prods['orders_id'].'</b></a>'; ?></td>									
                                    <td class="dataTableContent" height="20"><?php echo $prods['products_model']; ?></td>									
									<td class="dataTableContent"><div id="div_last_modified_<?php echo $prods['orders_eticket_log_id'];?>"><?php echo tep_datetime_short($prods['orders_eticket_last_modified']); ?></div></td>
                                    <td class="dataTableContent" height="20"><div id="div_updated_by_<?php echo $prods['orders_eticket_log_id'];?>"><?php echo $prods['orders_eticket_updated_by']; ?></div></td>									
                                    <td class="dataTableContent"><div id="div_customer_notified_<?php echo $prods['orders_eticket_log_id'];?>"><?php echo ($prods['orders_eticket_is_customer_notified'] == 1 ? 'Yes' : 'No'); ?></div></td>
                                    <td class="dataTableContent" height="20"><?php echo '<a href="javascript:settle_ment_total_div(\'div_settlement_total_box_'.$prods['orders_eticket_log_id'].'\');"><b>View</b></a>'; ?></td>
									</tr>	
									
									  <?php 
                                        if(tep_not_null($prods['eticket_logs_data'])){
                                      ?>
                                      <tr id="div_settlement_total_box_<?php echo $prods['orders_eticket_log_id'];?>" height="0" style="display:none"><td colspan="12">
                                      <?php //echo str_replace('##', '<br />', $prods['eticket_logs_data']); 
									  $eticket_logs_data_array = explode('##', $prods['eticket_logs_data']);
									  if(sizeof($eticket_logs_data_array) > 0){
									  ?>
                                      <table cellspacing="1" <?php echo $table_select_row_color;?>  cellpadding="5" style="border:1px solid #006666" width="80%">
                                      <tr style="background-color:#006666; color:#FFFFFF;">											  
                                        <td class="smallText" width="25%" ><strong><?php echo $prods['products_model']; ?> E-Ticket Log</strong></td>
                                        <td class="smallText" width="10%"><strong>Sent?</strong></td>
                                        <td class="smallText" width="45%"><strong>E-Ticket Received</strong></td>
                                        <td class="smallText" width="20%"><strong>Updated By</strong></td>
                                      </tr>
                                                                      
                                      <?php
                                        $order_products_id_change = 0;
										foreach($eticket_logs_data_array as $key=>$val){
                                        $eticket_logs_row_data_array = explode("||", $val);
										
										if((int)$eticket_logs_row_data_array[5]>0){
											$select_row_color_sub = 'bgcolor="#C6E5E0"';
											$eticket_logs_row_data_array[1] = tep_get_tour_agency_name_from_product_id($prods['products_id']).' - '.$eticket_logs_row_data_array[1];
                                        }else{
											$select_row_color_sub = 'bgcolor="#DDEEEB"';
                                            $eticket_logs_row_data_array[1] = STORE_NAME.' - '.$eticket_logs_row_data_array[1];
                                        }										
                                      ?>
                                      <tr <?php echo $select_row_color_sub; ?>>
                                        <td class="smallText"><a href="javascript: get_eticket_log_content('<?php echo $eticket_logs_row_data_array[4];?>');"><?php echo tep_datetime_short($eticket_logs_row_data_array[0]);?></a></td>
                                        <td class="smallText"><?php echo ($eticket_logs_row_data_array[2] == 1 ? 'Yes' : 'No'); ?></td>
                                        <td class="smallText">
                                        <?php if($eticket_logs_row_data_array[2] == 1){
										 $eticket_sql = tep_db_query('SELECT email_track_id, email_track_date, email_address FROM '.TABLE_EMAIL_TRACKER.' WHERE key_field="orders_id" AND key_id="'.(int)$prods['orders_id'].'" AND email_type="eTicket" AND orders_eticket_log_id ='.(int)$eticket_logs_row_data_array[4].' ');
										 if(tep_db_num_rows($eticket_sql) > 0){
										 
										 }else{
										 $eticket_sql = tep_db_query('SELECT email_track_id, email_track_date, email_address FROM '.TABLE_EMAIL_TRACKER.' WHERE key_field="orders_id" AND key_id="'.(int)$prods['orders_id'].'" AND email_type="eTicket" AND orders_eticket_log_id=0');										 	
										 
										 }
										 
										 
										 while($eticket_row =tep_db_fetch_array($eticket_sql)){
											 if((int)$eticket_row['email_track_id']){
												echo tep_datetime_short($eticket_row['email_track_date'])." ".$eticket_row['email_address'].'<br>';
											 }
										 }
                                        }else{ echo '&nbsp;'; } 
										?>
                                        </td>
                                        <td class="smallText"><?php echo $eticket_logs_row_data_array[1]; ?></td>
                                      </tr>	
                                      <tr id="row_eticket_log_content_<?php echo $eticket_logs_row_data_array[4];?>" style="display:none;" height="0"><td colspan="3" bgcolor="#FFFFFF"><div style="width:90%;" class="main" id="div_eticket_log_content_<?php echo $eticket_logs_row_data_array[4];?>"></div></td><td align="center" valign="bottom"><b><a href="javascript:scroll(0,0);">Top</a></b></td></tr>
                                      <?php 
                                      } //end of while
                                       ?>
                                      </table>
                                      <?php } ?>
                                      </td></tr>	
                                       <?php
                                       }// end of if
                                       ?>	
											   
	  
						  <?php
						  $items_sold_cnt++;
						  }
						  
						  ?>
						</table>
						</form>						
						<table  border="0" width="100%" cellspacing="0" cellpadding="0">
						 <tr>
							<td colspan="11"><table border="0" width="100%" cellspacing="0" cellpadding="2">
							  <tr>
								<td class="smallText" valign="top"><?php echo $product_split->display_count1($prods_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
								<td class="smallText" align="right"><?php echo $product_split->display_links1($prods_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID'))); ?></td>
							  </tr>
							</table></td>
						  </tr>
						</table>
  			</td>
  		</tr>
		</table>
	
		<? require(DIR_WS_INCLUDES . 'footer.php'); ?>
	</body>
</html>  		