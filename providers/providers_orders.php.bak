<?php
/*
  $Id: providers_orders.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
require('includes/application_top_providers.php');
if (!session_is_registered('providers_id')) {
	tep_redirect(tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_LOGIN, '', 'SSL'));
}
$provider_id=$_SESSION['providers_id'];

require_once(DIR_FS_PROVIDERS_LANGUAGES . $language . '/eticket.php');
require(DIR_FS_PROVIDERS_LANGUAGES . $language . '/' . FILENAME_PROVIDERS_ORDERS);
//允许上传的文件类型
$allow_type = array('pdf','rar','zip');

$qry_providers_start_date = "SELECT providers_start_date FROM ".TABLE_TRAVEL_AGENCY." WHERE agency_id='".$_SESSION['providers_agency_id']."'";
$res_providers_start_date = tep_db_query($qry_providers_start_date);
$row_providers_start_date = tep_db_fetch_array($res_providers_start_date);

if(tep_not_null($row_providers_start_date['providers_start_date']) && $row_providers_start_date['providers_start_date']!="0000-00-00"){
	$cond_records_after_date = $row_providers_start_date['providers_start_date'];
}else{
	$cond_records_after_date = "2010-04-01 00:00:00";
}


if ($_GET['action'] == 'process') {
	
	if((tep_not_null($_POST['btnSubmit']) || tep_not_null($_POST['btnUpdate'])) && tep_not_null($_POST['oID'])) {
		//预防后台订单与供应商编辑订单时生产冲突应更新订单时间
		if($_POST['last_modified_old'] != tep_db_get_field_value('last_modified', 'orders', 'orders_id="'.(int)$_POST['orders_id'].'"')){
			tep_redirect(FILENAME_PROVIDERS_ORDERS . "?" . tep_get_all_get_params(array("oID", "action")) . "oID=" . $_POST['oID'] . "&action=edit_order&msg=3");
			exit();
		}
		
		$orders_id                = tep_db_prepare_input($_POST['orders_id']);
		$products_id              = tep_db_prepare_input($_POST['products_id']);
		$products_name            = tep_db_prepare_input($_POST['products_name']);
		$orders_products_id       = tep_db_prepare_input($_POST['oID']);
		$provider_order_status_id = tep_db_prepare_input($_POST['provider_order_status_id']);
		$provider_comment         = stripslashes($_POST['provider_comment']);
		$popc_user_type           = 1;
		//$popc_updated_by        = $_SESSION['providers_id'];
		$popc_updated_by          = tep_db_prepare_input($_POST['popc_updated_by']);

		if ($_POST['notify_usi4trip'] == 'on') {
			$notify_usi4trip = 1;
		} else {
			$notify_usi4trip = $_POST['notify_usi4trip'];
		}

		$customer_seat_no         = tep_db_prepare_input($_POST['customer_seat_no']);
		$customer_bus_no          = tep_db_prepare_input($_POST['customer_bus_no']);
		$customer_confirmation_no = tep_db_prepare_input($_POST['customer_confirmation_no']);
		$customer_invoice_no      = tep_db_prepare_input($_POST['customer_invoice_no']);
		$customer_invoice_total   = tep_db_prepare_input($_POST['customer_invoice_total']);
		$customer_invoice_comment = stripslashes($_POST['customer_invoice_comment']);
		$tour_arrangement_providers         = stripslashes($_POST['tour_arrangement_providers']);
		$special_note             = stripslashes($_POST['special_note']);
		$hotel_pickup_info        = stripslashes($_POST['hotel_pickup_info']);
		
		//Start - Add into price update history
		$qry_previous_price_detail = "SELECT op.customer_invoice_no, op.customer_invoice_total, op.customer_invoice_comment FROM " . TABLE_ORDERS_PRODUCTS . " op WHERE op.orders_products_id='" . $orders_products_id . "'";
		$res_previous_price_detail = tep_db_query($qry_previous_price_detail);
		$row_previous_price_detail = tep_db_fetch_array($res_previous_price_detail);
		
		$update_pricing_history = 0;
		if( $row_previous_price_detail['customer_invoice_no'] != $customer_invoice_no || $row_previous_price_detail['customer_invoice_total'] != $customer_invoice_total ) {
			$update_pricing_history = 1;
		}
		
		//$status_updated_by = tep_get_providers_agency($_SESSION['providers_id']) . " - " . $popc_updated_by;
		//2013-09-22 Sofia要求只记录地接编号不要名称 updated by Howard
		$status_updated_by = $_SESSION['providers_id'] . " - " . $popc_updated_by;
		if( $update_pricing_history == '1' ) {
			$sql_data_array_original_insert = array(
				'orders_products_id' => $orders_products_id,
				'products_model'     => $products_details["model"],
				'products_name'      => str_replace("'", "&#39;", $products_details["name"]),
				'retail'             => 0,
				'cost'               => 0,
				'invoice_number'     => $customer_invoice_no,
				'invoice_amount'     => $customer_invoice_total,
				'comment'            => $customer_invoice_comment."!!###!!"."!!###!!".$status_updated_by,
				'update_user_type'   => 1,
				'last_updated_date'  => 'now()'
			);
			tep_db_perform(TABLE_ORDERS_PRODUCTS_UPDATE_HISTORY, html_to_db($sql_data_array_original_insert));
		}
		//End - Add into price update history
		
		$sql_data_array = array(
			'customer_seat_no'         => $customer_seat_no,
			'customer_bus_no'          => $customer_bus_no,
			'customer_confirmation_no' => $customer_confirmation_no,
			'customer_invoice_no'      => $customer_invoice_no,
			'customer_invoice_total'   => $customer_invoice_total,
			'customer_invoice_comment' => $customer_invoice_comment,
			'hotel_pickup_info'        => $hotel_pickup_info,
			'provider_order_status_id' => $provider_order_status_id,
			'supplier_deal_with_state' => '2'
		);
		tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array, 'update', "orders_products_id='" . $orders_products_id . "'");
		if ($tour_arrangement_providers != '') {
				
			function tep_get_ordersid_productsid_from_orderproducts_provider($orders_products_id) {
				$sql = "select orders_id, products_id from " . TABLE_ORDERS_PRODUCTS . "  where orders_products_id = '" . tep_db_input($orders_products_id) . "'";
				if (($rs = tep_db_query($sql)) && tep_db_num_rows($rs)) {
					return tep_db_fetch_array($rs);
				}
				return array(); 
			}
			
			$array_prod_order_id = tep_get_ordersid_productsid_from_orderproducts_provider($orders_products_id);			
			
			$tour_initenary_sql_data_array = array('tour_arrangement_providers' => $tour_arrangement_providers, 'special_note' => $special_note);
			tep_db_perform(TABLE_ORDERS_PRODUCTS_ETICKET, html_to_db($tour_initenary_sql_data_array),'update',"orders_products_id = '".$orders_products_id."' ");
		}				

		//E-ticket Log Start	
		if (!tep_not_null($popc_updated_by)) {
			$popc_updated_by = tep_get_admin_customer_name($providers_id, '1');
		}
		//记录供应商更新行程历史记录
		if (stripslashes($_POST['tour_arrangement_providers']) != stripslashes($_POST['previous_tour_arrangement']) ) {
			//tep_get_eticket_log_content($orders_id, $orders_products_id, $popc_updated_by,1,0);
			$data_array = array('orders_eticket_id' => (int)$_POST['orders_eticket_id'],
								'tour_arrangement_providers' => stripslashes($_POST['tour_arrangement_providers']),
								'last_modified' => date('Y-m-d H:i:s'),
								'providers_agency_id' => $_SESSION['providers_agency_id'],
								'providers_email_address' => $_SESSION['providers_email_address']
								);
			
			tep_db_perform('orders_product_eticket_tour_arrangement_providers_log', html_to_db($data_array) );
		}
		
		//E-ticket Log End	
		
		if(tep_not_null($provider_order_status_id) && $provider_order_status_id > 0) {
			$ordres_items_status_query     = tep_db_query("select op.orders_id from " .TABLE_ORDERS. " as o, " . TABLE_ORDERS_PRODUCTS . " op where o.orders_id=op.orders_id and op.orders_id = '" . (int)$orders_id . "'");
			$ordres_items_status_row_count = tep_db_num_rows($ordres_items_status_query);
			// stop auto confirmation without confirmation 
			$is_hotel_query                = tep_db_query("select  products_id from " . TABLE_PRODUCTS_TO_CATEGORIES . "  where categories_id = '182' and products_id = " . (int)$products_id);
			$is_hotel_row_count            = tep_db_num_rows($is_hotel_query);
			
			if ($customer_confirmation_no == '' && $is_hotel_row_count > 0 && $ordres_items_status_row_count == 1) {
				$ordres_items_status_row_count == 2;
			}		  
			//if (($provider_order_status_id == 23 || $provider_order_status_id == 2 || $provider_order_status_id == 6) && !tep_not_null($provider_comment) && $ordres_items_status_row_count == 1 ) {
			    //amit added for auto canceled order start  
			  	//供应商自动确认订单并发电子参团凭证等
				////////////////////////////////include('providers_orders_auto_confirm.php');
			    //amit added for auto canceled order end	
			//}
		
			//$qry_remove_existing="DELETE FROM ".TABLE_PROVIDER_ORDER_PRODUCTS_STATUS_HISTORY." WHERE orders_products_id='".$orders_products_id."'";
			//$res_remove_existing=tep_db_query($qry_remove_existing);
		
			$sql_data_array = array(
				'orders_products_id'          => $orders_products_id,
				'provider_order_status_id'    => $provider_order_status_id,
				'provider_comment'            => $provider_comment,
				'provider_status_update_date' => 'now()',
				'popc_user_type'              => $popc_user_type,
				'popc_updated_by'             => $popc_updated_by,
				'notify_usi4trip'             => $notify_usi4trip
			);
			tep_db_perform(TABLE_PROVIDER_ORDER_PRODUCTS_STATUS_HISTORY, html_to_db($sql_data_array));
			
			//Howard 删除已下单给地接记录表 start {
			tep_add_or_sub_sent_provider_not_re_rows ($orders_products_id, CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID, 'sub');
			//Howard 删除已下单给地接记录表 end }
			
			//当地接更新信息时直接也更新订单状态
			if ($orders_status_id = tep_get_orders_status_id_form_provider_order_status($provider_order_status_id)) {
				tep_update_orders_status($orders_id, $orders_status_id, CUSTOMER_SERVICE_SYSTEM_ACCOUNT_ID,'供应商更新，系统自动更新订单状态！');
			}
			
			$qry_providers_detail = "SELECT agency_name FROM ".TABLE_TRAVEL_AGENCY." WHERE agency_id='".$_SESSION['providers_agency_id']."'";
			$res_providers_detail = tep_db_query($qry_providers_detail);
			$row_providers_detail = tep_db_fetch_array($res_providers_detail);
			$orders_link          = tep_href_link(FILENAME_ADMIN_ORDERS, 'action=edit&oID=' . $orders_id);

			if (isset($HTTP_POST_VARS['notify_usi4trip']) && ($HTTP_POST_VARS['notify_usi4trip'] == 'on')) {

				/* 地接所有回复所有邮件通知都不需要再发到serivce邮箱了
				tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, sprintf(EMAIL_ORDERS_PRODUCTS_STATUS_CHANGED_SUBJECT, $orders_id, 
					tep_get_provider_order_status_name($provider_order_status_id)), sprintf(EMAIL_ORDERS_PRODUCTS_STATUS_CHANGED_BODY, 
					$row_providers_detail['agency_name'], $products_id." - ".$products_name, $orders_id, $orders_link, $orders_link), 
					$row_providers_detail['agency_name'], $_SESSION['providers_email_address']
				);
				*/
				//amit added blinking management start
				tep_get_order_start_stop($orders_id,1);
				//amit added blinking management end
			}			
		}
		//更新订单最后修改时间
		tep_db_query("update orders set last_modified = now() where orders_id = '" . (int)$orders_id . "';");
		//处理用户上传的发票pdf文件，允许上传PDF、RAR、ZIP和图片文件
		if (isset($_FILES['vFile'])) {
			uploadInvoices('vFile', $orders_id, $products_id, $orders_products_id, $allow_type);
		}
		
		if (tep_not_null($_POST['btnUpdate'])) {
			tep_redirect(FILENAME_PROVIDERS_ORDERS . "?" . tep_get_all_get_params(array("oID", "action")) . "oID=" . $_POST['oID'] . "&action=edit_order&msg=1");
		} else {
			tep_redirect(FILENAME_PROVIDERS_ORDERS . "?" . tep_get_all_get_params(array("oID", "action")) . "msg=1");
		}
	}
}

require(DIR_FS_PROVIDERS_INCLUDES . FILENAME_PROVIDERS_HEADER);

if (!tep_not_null($_SESSION['providers_agency_id'])) {
	$_SESSION['providers_agency_id'] = 0;
}
?>
<link href="css/gb.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<script type="text/javascript" src="includes/javascript/spiffyCal/spiffyCal_v2_1_2008_04_01-min.js"></script>
<script type="text/javascript"><!--
var dateAvailable_dept_start = new ctlSpiffyCalendarBox("dateAvailable_dept_start", "search_orders", "dept_start_date","btnDate3","<?php echo tep_get_date_disp($dept_start_date); ?>",scBTNMODE_CUSTOMBLUE);
var dateAvailable_dept_end = new ctlSpiffyCalendarBox("dateAvailable_dept_end", "search_orders", "dept_end_date","btnDate4","<?php echo tep_get_date_disp($dept_end_date); ?>",scBTNMODE_CUSTOMBLUE);
//--></script>
<div id="spiffycalendar" class="text"></div>
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="body_min_height">
	<tr valign="top">
		<td colspan="2" align="center">
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
			<?php 
			if ($_GET['msg'] == '1') { ?>
				<tr valign="top">
					<td class="successMsg" colspan="2" align="center">&nbsp;<?php echo MSG_SUCCESS; ?></td>
				</tr>
			<?php 
			}

			if ($_GET['msg'] == '2') { ?>
				<tr valign="top">
					<td class="successMsg" colspan="2" align="center">&nbsp;<?php echo MSG_SUCCESS_ETICKET_TEMPLATE; ?></td>
				</tr>
				<?php 
			}
			if($_GET['msg'] == '3'){
			?>
			<tr valign="top">
				<td class="errorMsg" colspan="2" align="center">&nbsp;更新失败：订单资料已非最新，请再次编辑，谢谢……</td>
			</tr>
			<?php
			}

			if ($_GET['action'] != 'edit_order') {
				echo tep_draw_form('search_orders', FILENAME_PROVIDERS_ORDERS . '?' . tep_get_all_get_params(array("oID", "action", "search", "btnok")), 'get');
				if ($_GET['tour_type'] != "") {
					echo tep_draw_hidden_field('tour_type', $_GET['tour_type']);
				}
				?>
				<tr class="login">
					<td class="login_heading">
						<?php //我们网站的联系方式?>
						<table border="0" cellpadding="3" cellspacing="0" width="100%" class="login_heading grayBorder" align="right">
							<tr><td><b><?php echo TXT_CUSTOMER_SERVICE; ?>&nbsp;</b></td></tr>
							<tr><td><?php echo CUSTOMER_SERVICE_PHONE; ?>&nbsp;<br /><?php echo CUSTOMER_SERVICE_HOURS;?></td></tr>
						</table>
					</td>
					<td width="64%" align="left">
						<table cellpadding="3" cellspacing="3" border="0" class="login">
							<tr>
								<td><?php echo TEXT_SEARCH;?></td>
							</tr>
							<tr>
								<td><?php //echo TEXT_SEARCH." ".tep_draw_input_field('search', tep_db_output($_GET['search']));
										echo TEXT_SEARCH_INVOICE;?>
								</td>
								<td><?php echo tep_draw_input_field('search_invoice', tep_db_output($_GET['search_invoice']));?></td>
								<td><?php echo TEXT_SEARCH_ORDER;?></td>
								<td><?php echo tep_draw_input_field('search_order', tep_db_output($_GET['search_order']));?></td>
								<td>游客:</td>
								<td><?php echo tep_draw_input_field('search_name', tep_db_output($_GET['search_name']));?></td>
							</tr>
							<tr>
								<td><?php echo TEXT_SEARCH_TOUR_CODE;?></td>
								<td><?php echo tep_draw_input_field('search_tour_code', tep_db_output($_GET['search_tour_code']));?></td>
								<td><?php echo TEXT_SEARCH_TRAVRLER_NAME;?></td>
								<td><?php echo tep_draw_input_field('search_traveler', tep_db_output($_GET['search_traveler']));?></td>
								<?php /*?><td><?php echo TEXT_SEARCH_ORDER_STATUS;?></td>
								<td>
									<?php 
										$ar_orders_status = array(
											array('id' => '', 'text' => SELECT_NONE),
											array('id' => '100038', 'text' => 'AAA Urgent'),
											array('id' => '100009', 'text' => 'New'),
											array('id' => 'fi', 'text' => 'Processing'),
											array('id' => '100021', 'text' => 'Flight Information Received'),
											array('id' => 'p7', 'text' => 'Closed')
										);
										echo tep_draw_pull_down_menu('search_order_status', $ar_orders_status, tep_db_output($_GET['search_order_status']), 'style="width:137px;"');?>
								</td><?php */
								?>
							</tr>
							<tr>
								<td><?php echo TXT_TOUR_START_DATE;?></td>
								<td>
									<script type="text/javascript">dateAvailable_dept_start.writeControl(); dateAvailable_dept_start.dateFormat="MM/dd/yyyy";</script><?php //echo tep_draw_input_field('dept_start_date', tep_db_output($_GET['dept_start_date']));?></td>
								<td><?php echo TXT_TOUR_END_DATE;?></td>
								<td>
									<script type="text/javascript">dateAvailable_dept_end.writeControl(); dateAvailable_dept_end.dateFormat="MM/dd/yyyy";</script>
									<?php //echo tep_draw_input_field('dept_end_date', tep_db_output($_GET['dept_end_date']));?>
									<?php echo tep_draw_input_field('btnok', TEXT_OK, '', 'submit');?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<?php
				echo '</form>'; 
			}?>
			<!-- TinyMCE -->
			<script type="text/javascript" src="includes/javascript/tiny_mce/tiny_mce.js"></script>
			<script type="text/javascript">
				tinyMCE.init({
					// General options
					charset : "<?php echo strtolower(CHARSET);?>",
					mode : "specific_textareas",
					editor_selector : "mceEditor",
					theme : "simple",
					plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount",

					// Theme options
					theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
					theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
					theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
					theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
					theme_advanced_toolbar_location : "top",
					theme_advanced_toolbar_align : "left",
					theme_advanced_statusbar_location : "bottom",
					theme_advanced_resizing : true,

					// Example content CSS (should be your site CSS)
					content_css : "css/content.css",

					// Drop lists for link/image/media/template dialogs
					template_external_list_url : "lists/template_list.js",
					external_link_list_url : "lists/link_list.js",
					external_image_list_url : "lists/image_list.js",
					media_external_list_url : "lists/media_list.js"

				});
			</script>
			<!-- /TinyMCE -->
			<tr valign="top">
				<td colspan="2" height="100%" valign="top" align="center">
					<table border="0" width="100%" cellspacing="0" cellpadding="1" class="login">
						<tr>
							<?php
							//逻辑代码{	
							
							/* 下面提老句子 
							$qry_providers_orders = "SELECT 
  								o.orders_id, o.date_purchased, o.orders_status, 
  								op.orders_products_id, op.products_id, op.products_name, op.products_departure_date, op.products_departure_time, op.customer_invoice_no,op.is_hotel,  
  								p.provider_tour_code, p.provider_tour_code, p.products_durations, p.products_durations_type,
  								a.agency_code, 
  								os.orders_status_name,
  								ps.provider_order_status_id, ps.provider_order_status_name, 
  								opsh.popc_id,  opsh.provider_status_update_date,
  								f.airline_name, f.flight_no, f.airline_name_departure, f.flight_no_departure, f.airport_name, f.airport_name_departure, f.arrival_date, f.arrival_time, 
  								f.departure_date , f.departure_time, f.show_warning_on_admin, f.sent_confirm_email_to_provider FROM ".TABLE_ORDERS." o 
							INNER JOIN ".TABLE_ORDERS_PRODUCTS." op ON o.orders_id=op.orders_id 
							INNER JOIN ".TABLE_PRODUCTS." p ON op.products_id=p.products_id AND p.agency_id IN (".$_SESSION['providers_agency_id'].")
							INNER JOIN ".TABLE_ORDERS_PRODUCTS_ETICKET." ope ON ope.products_id = p.products_id AND ope.orders_id=o.orders_id
							INNER JOIN ".TABLE_TRAVEL_AGENCY." a ON p.agency_id=a.agency_id
							LEFT JOIN ".TABLE_ORDERS_STATUS." os ON os.orders_status_id=o.orders_status
							INNER JOIN ".TABLE_PROVIDER_ORDER_PRODUCTS_STATUS_HISTORY." opsh ON op.orders_products_id=opsh.orders_products_id 
							LEFT JOIN ".TABLE_PROVIDER_ORDER_PRODUCTS_STATUS_HISTORY." opsh2 ON opsh.orders_products_id=opsh2.orders_products_id AND opsh.popc_id < opsh2.popc_id 
							LEFT JOIN ".TABLE_PROVIDER_ORDER_PRODUCTS_STATUS." ps ON ps.provider_order_status_id=opsh.provider_order_status_id
							LEFT JOIN ".TABLE_ORDERS_PRODUCTS_FLIGHT." f ON op.orders_id = f.orders_id AND op.products_id=f.products_id 
							WHERE ( 
									o.date_purchased >= '".date('Y-m-d 00:00:00',strtotime($cond_records_after_date))."' OR 
								    (op.order_item_purchase_date >= '".date('Y-m-d 00:00:00',strtotime($cond_records_after_date))."' && op.order_item_purchase_date != '0000-00-00 00:00:00')
								
								) AND 
								opsh2.provider_status_update_date IS NULL ";
							老句子结束 */
							/* by lwkai 添加两个字段后，只需要这样查一下就能有结果 */
							 $qry_providers_orders = "SELECT
							op.orders_products_id, op.orders_id, op.supplier_deal_with_state, op.provider_order_status_id
							FROM `orders_products` AS op, products AS p, orders AS o
							WHERE p.agency_id = " . (int)$_SESSION['providers_agency_id'] . " AND op.products_id = p.products_id AND o.orders_id = op.orders_id AND
							(
									o.date_purchased >= '".date('Y-m-d 00:00:00',strtotime($cond_records_after_date))."' OR
									(
											op.order_item_purchase_date >= '".date('Y-m-d 00:00:00',strtotime($cond_records_after_date))."' && op.order_item_purchase_date != '0000-00-00 00:00:00'
									)
							)";
							
							/* by lwkai add end */
							 
							 
							/*$txt_search = tep_db_input($_GET['search']);
							$condition  = "";
							$search_tour_packages=1;
							if (tep_not_null($txt_search)) {
								$search_tour_packages=0;
								$condition=" AND (o.orders_id LIKE '%".$txt_search."%' OR o.customers_name LIKE '%".$txt_search."%' OR o.date_purchased LIKE '%".$txt_search."%' OR 
									o.payment_method LIKE '%".$txt_search."%' OR op.orders_products_id LIKE '%".$txt_search."%' OR op.products_id LIKE '%".$txt_search."%' OR 
									p.provider_tour_code LIKE '%".$txt_search."%' OR op.products_model LIKE '%".$txt_search."%' OR op.products_name LIKE '%".$txt_search."%' OR 
									op.products_departure_date LIKE '%".$txt_search."%' OR op.products_departure_time LIKE '%".$txt_search."%' OR op.products_room_info LIKE '%".$txt_search."%' OR 
									p.provider_tour_code LIKE '%".$txt_search."%' OR a.agency_code LIKE '%".$txt_search."%' OR os.orders_status_name LIKE '%".$txt_search."%' OR 
									ps.provider_order_status_name LIKE '%".$txt_search."%' OR opsh.provider_comment LIKE '%".$txt_search."%') ";
							}

							if (tep_not_null($_GET['search_invoice'])) {
								$search_tour_packages = 0;
								$condition .= " AND op.customer_invoice_no='" . tep_db_input($_GET['search_invoice']) . "'";
							}
							if (tep_not_null($_GET['search_name'])) {
								$search_tour_packages = 0;
								$condition .= " AND ope.guest_name like '%" . tep_db_input($_GET['search_name']) . "%'";
							}
							if(tep_not_null($_GET['search_order'])){
								$search_tour_packages=0;
								$condition.=" AND o.orders_id='".tep_db_input($_GET['search_order'])."'";
							}
							if(tep_not_null($_GET['search_tour_code'])){
								$search_tour_packages=0;
								$condition.=" AND (p.products_id = '".tep_db_input($_GET['search_tour_code'])."' OR p.provider_tour_code LIKE '%".tep_db_input($_GET['search_tour_code'])."%')";
							}
							if(tep_not_null($_GET['order_status'])){
								$search_tour_packages=0;
								$condition.=" AND ps.provider_order_status_id='".tep_db_input($_GET['order_status'])."'";
							}
							if(tep_not_null($_GET['search_traveler'])){
								$search_tour_packages=0;
								$condition.=" AND o.orders_id IN (SELECT et.orders_id FROM ".TABLE_ORDERS_PRODUCTS_ETICKET." et WHERE et.guest_name LIKE '%".tep_db_input($_GET['search_traveler'])."%')";
							}
							$make_start_date = tep_get_date_db($HTTP_GET_VARS['dept_start_date']);
							$make_end_date = tep_get_date_db($HTTP_GET_VARS['dept_end_date']);
							if((isset($make_start_date) && tep_not_null($make_start_date)) && (isset($make_end_date) && tep_not_null($make_end_date)) ) {
								$search_tour_packages=0;
								$condition .= " AND date_format(op.products_departure_date, '%Y-%m-%d') >= '" . $make_start_date . "' AND date_format(op.products_departure_date, '%Y-%m-%d') <= '" . $make_end_date . "' ";
							}else if ((isset($make_start_date) && tep_not_null($make_start_date)) && (!isset($make_end_date) || !tep_not_null($make_end_date)) ) {
								$search_tour_packages=0;
								$condition .= " AND date_format(op.products_departure_date, '%Y-%m-%d') >= '" . $make_start_date . "' ";
							}else if ((!isset($make_start_date) || !tep_not_null($make_start_date)) && (isset($make_end_date) && tep_not_null($make_end_date)) ) {
								$search_tour_packages=0;
								$condition .= " AND date_format(op.products_departure_date, '%Y-%m-%d') <= '" . $make_end_date . "' ";
							}

							$order_by=" GROUP BY op.orders_products_id ORDER BY ";
							if($_GET["sort"]!="")
								$order_by.=$_GET["sort"];
							else
								$order_by.="o.orders_id";

							if($_GET["order"]!="")
								$order_by.=" ".$_GET["order"];
							else
								$order_by.=" DESC";
								
								
							print_r($qry_providers_orders);*/
							
							$ar_status_wise_rec_cntr=array();
							$res_get_status=tep_db_query($qry_providers_orders);
							while($row_get_status=tep_db_fetch_array($res_get_status)){
								$ar_status_wise_rec_cntr[$row_get_status['provider_order_status_id']]++;
							}
							/*if($condition!="")
								$qry_providers_orders.= $condition;
							
							$qry_providers_orders.=$order_by;*/
							//逻辑代码}	

		
		if($_GET['action']=='edit_order' && $oID!=""){
		}else{?>
				<td id="statusList" valign="top">
					<table border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#F0F0FF" class="grayBorder login">
						<tr class="dataTableHeadingRow">
							<th class="dataTableHeadingContent"><?php echo TEXT_T4F_ORDERS_STATUS;?><br /></th>
						</tr>
				<?php 
					$qry_status="SELECT * FROM ".TABLE_PROVIDER_ORDER_PRODUCTS_STATUS." WHERE provider_order_status_for=0 ORDER BY sort_order ASC";
					$res_status=tep_db_query($qry_status);
					while($row_status=tep_db_fetch_array($res_status)){?>
						<tr><td style="padding:3px;"><a href="<?php echo tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_ORDERS, 'tour_type='.$tour_type.'&order_status='.$row_status['provider_order_status_id'], "SSL");?>" class="statuslst_link"><?php echo $row_status['provider_order_status_name'].": ".(int)$ar_status_wise_rec_cntr[$row_status['provider_order_status_id']];?></a></td></tr>
				<?php
					}?>
						
						<tr><td>&nbsp;</td></tr>
						<tr class="dataTableHeadingRow">
							<th class="dataTableHeadingContent"><?php echo TEXT_PROVIDERS_STATUS;?><br /></th>
						</tr>
				<?php 
					$qry_status="SELECT * FROM ".TABLE_PROVIDER_ORDER_PRODUCTS_STATUS." WHERE provider_order_status_id NOT IN (5, 23) AND provider_order_status_for=1 ORDER BY sort_order ASC";
					$res_status=tep_db_query($qry_status);
					while($row_status=tep_db_fetch_array($res_status)){?>
						<tr><td style="padding:3px;"><a href="<?php echo tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_ORDERS, 'tour_type='.$tour_type.'&order_status='.$row_status['provider_order_status_id'], "SSL");?>" class="statuslst_link"><?php echo $row_status['provider_order_status_name'].": ".(int)$ar_status_wise_rec_cntr[$row_status['provider_order_status_id']];?></a></td></tr>
				<?php
					}?>
					</table>
				</td>
	<?php }?>
				<td id="ordersList" valign="top">
			<?php 
		$oID=tep_db_input($_GET['oID']);
		if($_GET['action']=='edit_order' && $oID!=""){	//编辑页面
		 /*
		 include('includes/javascript/editor.php');
		echo tep_load_html_editor();
		echo tep_insert_html_editor('tour_arrangement_providers','simple','200');
		*/

			echo tep_draw_form('providers_orders', FILENAME_PROVIDERS_ORDERS.'?'.tep_get_all_get_params(array("oID", "action")).'action=process', 'post', 'onsubmit="return validate_form();" enctype="multipart/form-data" ');
			echo tep_draw_hidden_field('oID', $oID);
			
			$qry_orders_detail = "SELECT 
				o.orders_id, o.customers_id, o.customers_name, o.customers_telephone, o.guest_emergency_cell_phone, o.date_purchased, o.orders_status, o.payment_method, o.last_modified,
			 
				op.orders_products_id, op.orders_id, op.products_id,op.products_model, op.products_name, op.products_price, op.final_price, op.products_tax, op.products_quantity, 
				op.products_departure_date, op.products_departure_time, op.products_departure_location, op.products_room_price, op.products_room_info, op.total_room_adult_child_info, 
				op.final_price_cost, op.products_room_info, op.customer_seat_no, op.customer_bus_no, op.customer_confirmation_no, op.customer_invoice_no, op.customer_invoice_total,
				op.customer_invoice_comment, op.hotel_pickup_info, op.is_hotel, op.hotel_checkout_date, op.customer_invoice_files, op.products_departure_location_sent_to_provider_confirm, 
			   
				p.provider_tour_code, p.provider_tour_code, p.products_durations, p.products_durations_type, p.departure_city_id, p.note_to_agency, 
				a.agency_code, a.is_hotel_pickup_info, 
				os.orders_status_name, 
				ps.provider_order_status_id, ps.provider_order_status_name, 
				opsh.provider_comment, 
				f.airline_name, f.flight_no, f.airline_name_departure, f.flight_no_departure, f.airport_name, f.airport_name_departure, f.arrival_date, f.arrival_time, f.departure_date , 
				f.departure_time, f.show_warning_on_admin, f.sent_confirm_email_to_provider  
			FROM ".TABLE_ORDERS." o 
	INNER JOIN ".TABLE_ORDERS_PRODUCTS." op ON o.orders_id=op.orders_id 
	INNER JOIN ".TABLE_PRODUCTS." p ON op.products_id=p.products_id AND p.agency_id IN (".$_SESSION['providers_agency_id'].")
	INNER JOIN ".TABLE_PRODUCTS_DESCRIPTION." pd ON p.products_id=pd.products_id AND pd.language_id = 1
	INNER JOIN ".TABLE_TRAVEL_AGENCY." a ON p.agency_id=a.agency_id
	LEFT JOIN ".TABLE_ORDERS_STATUS." os ON os.orders_status_id=o.orders_status
	LEFT JOIN ".TABLE_PROVIDER_ORDER_PRODUCTS_STATUS_HISTORY." opsh ON op.orders_products_id=opsh.orders_products_id
	LEFT JOIN ".TABLE_PROVIDER_ORDER_PRODUCTS_STATUS." ps ON ps.provider_order_status_id=opsh.provider_order_status_id
	LEFT JOIN ".TABLE_ORDERS_PRODUCTS_FLIGHT." f ON op.orders_id = f.orders_id AND op.products_id=f.products_id 
	WHERE op.orders_products_id='".$oID."'";
			$res_orders_detail=tep_db_query($qry_orders_detail);
			if(tep_db_num_rows($res_orders_detail)=="0"){
				echo '<table border="0" width="100%" height="100%" cellspacing="0" cellpadding="0" bgcolor="#F0F0FF" class="login">
					<tr class="login">
						<td width="18%"><strong>'.MSG_RECORD_NOT_FOUND.'</strong></td>
						<td>&nbsp;</td>
					</tr>';
			}else{
			$row_orders_detail = tep_db_fetch_array($res_orders_detail);
			$display_eticket_itinerary_new_format=false;
			if((int)$row_orders_detail['orders_id'] > 10650){
				$display_eticket_itinerary_new_format=true;
			}
			?>
				<table id="edit_table" class="grayBorder login" bgcolor="#F0F0FF" border="0" cellspacing="0" cellpadding="0" width="100%" height="100%"><tr><td valign="top">
				<table border="0" width="100%" height="100%" cellspacing="0" cellpadding="0" >
					<tr class="login">
						<td width="18%"><strong><?php echo TEXT_ORDER_ID;?></strong></td>
						<td>
						<?php
						echo tep_db_output(tep_get_products_orders_id_str($row_orders_detail['orders_id'], $row_orders_detail['is_hotel'])).getFormatOrdersIdTagToAgency($oID, $row_orders_detail['orders_id'], $_SESSION['providers_agency_id']);
						echo tep_draw_hidden_field('orders_id', $row_orders_detail['orders_id']);
						echo tep_draw_hidden_field('last_modified_old', $row_orders_detail['last_modified']);
						?>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<strong><?php echo TEXT_PRODUCT_ID;?></strong>
						<b><?php echo tep_db_output($row_orders_detail['provider_tour_code']);?></b>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<?php
						/* 取消订单日期 
						<strong><?php echo TEXT_ORDER_DATE;?></strong>
						<?php echo tep_date_short($row_orders_detail['date_purchased']);?>
						*/ ?>
						
						<?php
						//给酒店配上与此酒店相关的行程。注意：相关的行程必须也是当前供应商的，否则不显示！
						
						$order = new order($row_orders_detail['orders_id']);
						$related_prod = '';
						foreach((array) $order->products as $key){
							if(tep_get_provider_order_products_status_history($key['orders_products_id'])&&$key['id'] != $row_orders_detail['products_id'] && $key['agency_id'] == $_SESSION['providers_agency_id'] && $key['is_hotel'] != $row_orders_detail['is_hotel']){
								$checkout_date = date('Y-m-d', strtotime($key['hotel_checkout_date']));
								$departure_date = date('Y-m-d', strtotime($row_orders_detail['products_departure_date']));
								$_tmp_var = '';
								if($key['is_hotel']=="1" && $departure_date > '0000-00-00'){
									if($checkout_date == $departure_date ){
										$_tmp_var = ' <b>(行程前加订)</b>';
									}elseif($checkout_date >= $departure_date){
										$_tmp_var = ' <b>(行程后续订)</b>';
									}
								}
								$related_prod .= '<a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_ORDERS, tep_get_all_get_params(array("oID", "action", "msg")).'&oID=' . $key['orders_products_id'] . '&action=edit_order', "SSL").'">'.$key['name'].$_tmp_var."</a><br>";
							}
						}
						if(tep_not_null($related_prod)){
							if($row_orders_detail['is_hotel']=="1"){
								echo '<table align="right" border="0" cellspacing="0" cellpadding="0"><tr><td><b>相关行程：</b></td><td>'.$related_prod.'</td></tr></table>';
							}else{
								echo '<table align="right" border="0" cellspacing="0" cellpadding="0"><tr><td><b>加订酒店：</b></td><td>'.$related_prod.'</td></tr></table>';
							}
						}
						?>
						</td>
					</tr>
					<tr class="login">
						<td><strong><?php echo TEXT_PRODUCT;?></strong></td>
						<td><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $row_orders_detail['products_id']);?>" class="orddtl_link" target="_blank"><?php echo preg_replace('/\*\*.+/','',tep_db_output($row_orders_detail['products_name'])); //不显示副标题 ?></a>
					<?php	echo tep_draw_hidden_field('products_id', $row_orders_detail['products_id']);
								echo tep_draw_hidden_field('products_name', $row_orders_detail['products_name']);?></td>
					</tr>
					<tr class="login" valign="top">
						<td><strong><?php //echo TEXT_PRODUCT_ID;?></strong></td>
						<td>
							<?php 
								$qry_product_attributes = "SELECT products_options, products_options_values FROM ".TABLE_ORDERS_PRODUCTS_ATTRIBUTES." WHERE orders_products_id = '".$row_orders_detail['orders_products_id']."'";
								$res_product_attributes = tep_db_query($qry_product_attributes);
								if(tep_db_num_rows($res_product_attributes) > 0){
									while($row_product_attributes = tep_db_fetch_array($res_product_attributes)){
										echo '<nobr>- '.$row_product_attributes['products_options']." : ".$row_product_attributes['products_options_values'].'<nobr><br>';
									}
								}
//								echo '<br><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'];
							?>
						</td>
					</tr>
					<?php /*?><tr class="login">
						<td><strong><?php echo TEXT_PRODUCT_AKA;?></strong></td>
						<td><?php echo tep_db_output($row_orders_detail['products_name_provider']);?></td>
					</tr><?php */?>
					<?php if(tep_not_null($row_orders_detail['note_to_agency'])){?>
					<tr class="login">
						<td><strong style="color:#F00">注意事项：</strong></td>
						<td style="color:#F00"><?php echo nl2br(tep_db_output($row_orders_detail['note_to_agency']));?>
						</td>
					</tr>
					<?php }?>
					<tr class="login" style="display:none"><?php //不显示我们products_model，但要记录?>
						<td><strong><?php echo TEXT_TOUR_CODE;?></strong></td>
						<td>
							<?php 
								echo tep_db_output($row_orders_detail['products_model']);
								echo tep_draw_hidden_field('products_model', $row_orders_detail['products_model']);
							?>
						</td>
					</tr>
					
					<?php if($row_orders_detail['is_hotel'] == 1){ ?>
					<tr class="login">
						<td><strong><?php echo TEXT_HOTEL_CHECKIN_DATE;?></strong></td>
						<td><?php echo tep_date_short($row_orders_detail['products_departure_date'])." ".tep_db_output($row_orders_detail['products_departure_time']);?></td>
					</tr>
					<tr class="login">
						<td><strong><?php echo TEXT_HOTEL_CHECKOUT_DATE;?></strong></td>
						<td>
						<?php
						echo tep_date_short($row_orders_detail['hotel_checkout_date']);
						echo ' ('.date1SubDate2($row_orders_detail['hotel_checkout_date'], $row_orders_detail['products_departure_date']).'晚)'; 
						?>
						</td>
					</tr>
					<?php }else{ ?>
					<tr class="login">
						<td><strong><?php echo TEXT_DEPARTURE_DATE;?></strong></td>
						<td id="products_departure_date">
						<?php
						$departure_location_history_str = tep_get_departure_location_history_str($row_orders_detail['orders_products_id']);
						/*
						if(tep_not_null($departure_location_history_str)){
							echo $departure_location_history_str;
						}else{
							echo tep_date_short($row_orders_detail['products_departure_date'])." ".tep_db_output($row_orders_detail['products_departure_time']);
						}
						*/
						if($row_orders_detail['products_departure_location_sent_to_provider_confirm'] == "1"){
							echo tep_date_short($row_orders_detail['products_departure_date'])." ".tep_db_output($row_orders_detail['products_departure_time']).$departure_location_history_str;
						}else{
						?>
						<b>出发日期时间、地址等信息有更新但未最后确认，目前暂时不可见！您可联系我司相关人员解决此事！</b>
						<?php
						}
						?>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<strong>出发城市：</strong>						
						<?php echo implode(' / ', tep_get_city_names($row_orders_detail['departure_city_id']));?>
						</td>
					</tr>
					<?php 
					}
					
					?>
					
					<tr class="login">
						<td><strong><?php echo TEXT_DEPARTURE_LOCATION;?></strong></td>
						<td>
						<?php
						if($row_orders_detail['products_departure_location_sent_to_provider_confirm'] == "1"){
							echo tep_db_output($row_orders_detail['products_departure_location']);
						}else{
							echo '&nbsp;';
						}
												
						

						?>
						
						</td>
					</tr>
					<tr class="login" valign="top">
						<td><strong><?php echo TEXT_ROOM_INFO;?></strong></td>
						<td><?php /*						
						$finalrommstring = str_replace('No','Number',$row_orders_detail['products_room_info']); 
						$finalrommstring = str_replace('no','Number',$finalrommstring);
						$finalrommstring = str_replace('#','Number',$finalrommstring);
						$finalrommstring = str_replace('room','Room',$finalrommstring);
						$finalrommstring = str_replace('childs','children',$finalrommstring);
						//$finalrommstring = str_replace("<br>".TEXT_SHOPPIFG_CART_TOTAL_FARES_TRANSACTION_FEE,'',$finalrommstring);
						$finalrommstring = str_replace("<br>".TEXT_SHOPPIFG_CART_TOTAL_FARES_TRANSACTION_FEE_NO_PERSENT,'',$finalrommstring);
						$finalrommstring = preg_replace('/Total Fares \([0-9.]+% transaction fee included\)/','',$finalrommstring);
	
					//	echo $finalrommstring; 					
						if(eregi('- Total :',stripslashes($finalrommstring))){
							$req_roomarray = explode('- Total :',stripslashes($finalrommstring));
						}else if(eregi('Total :',stripslashes($finalrommstring))){
							$req_roomarray = explode('Total :',stripslashes($finalrommstring));
						}else if(eregi('Total :-',stripslashes($finalrommstring))){
							$req_roomarray = explode('Total :-',stripslashes($finalrommstring));
						}else if(eregi('- Total of Room',stripslashes($finalrommstring))){						
							$req_roomarray[0] = preg_replace('/-[[:space:]]Total[[:space:]]of[[:space:]]Room[[:space:]][0-9]+:[[:space:]]\$[0-9\,]+.[0-9]+/', '', stripslashes($finalrommstring));
						}else if(eregi('Total of Room',stripslashes($finalrommstring))){						
							$req_roomarray[0] = preg_replace('/Total[[:space:]]of[[:space:]]Room[[:space:]][0-9]+:[[:space:]]\$[0-9\,]+.[0-9]+/', '', stripslashes($finalrommstring));
						}else{
							$req_roomarray[0] = preg_replace('/Total[[:space:]]of[[:space:]]Room[[:space:]][0-9]+:[[:space:]]\$[0-9\,]+.[0-9]+/', '', stripslashes($finalrommstring));
						
						}						
						echo html_entity_decode(tep_db_output($req_roomarray[0])); */?>
						<table cellpadding="0" cellspacing="0" width="100%" border="0" class="login">
								 <tr><td valign="top" align="left" width="500" id="roomNumInfo">
								<?php //Howard add 显示床型信息{
								$bedOptions=array();
								if(strpos($row_orders_detail['products_room_info'],'Bed type')!==false || strpos($row_orders_detail['products_room_info'],'床型')!==false){
									
									$bed_option_infos = explode('<br>',$row_orders_detail['products_room_info']);
									for($bedI=0, $N=sizeof($bed_option_infos); $bedI<$N; $bedI++){
										if(strpos($bed_option_infos[$bedI],'Bed type')!==false || strpos($bed_option_infos[$bedI],'床型')!==false){
											$bedOptions[sizeof($bedOptions)+1]=preg_replace('/.+:/','',$bed_option_infos[$bedI]);
										}
									}
								}
								//Howard add 显示床型信息}
								?>
								 
								 <?php
								 if(tep_not_null($row_orders_detail['total_room_adult_child_info'])){
										$total_rooms = get_total_room_from_str($row_orders_detail['total_room_adult_child_info']);
										 if($total_rooms > 0){
											?>
											<table width="100%" class="login">
												 <tr><td width="34%" align="center" nowrap><?php echo TXT_ETICKET_NUMBER_OF_ROOM;?></td>
												 <td align="center"><?php echo TXT_ETICKET_ADULT;?></td>
												 <td align="center"><?php echo TXT_ETICKET_CHILD;?></td>
												 <td align="center"><?php if(sizeof($bedOptions)>0) echo BED_OPTION_INFO;?></td>
												 </tr>
											<?php
											for($t=1;$t<=$total_rooms;$t++){
												$chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($row_orders_detail['total_room_adult_child_info'],$t);
												echo '<tr><td align="center">第'.$t.'房间</td><td align="center">'.$chaild_adult_no_arr[0].'</td><td align="center">'.$chaild_adult_no_arr[1].'<td align="center">'.$bedOptions[$t].'</td></td></tr>';
											}
											?></table><?php
										 }else{
												$chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($row_orders_detail['total_room_adult_child_info'], 1);
												$total_adults = $chaild_adult_no_arr[0];
												$total_children = $chaild_adult_no_arr[1];
												echo '<table width="100%" class="login"><tr><td width="50%" align="center">'.TXT_ETICKET_ADULT.'</td><td width="50%" align="center">'.TXT_ETICKET_CHILD.'</td></tr><tr><td align="center">'.$total_adults.'</td><td align="center">'.$total_children.'</td></tr></table>';
										 }
								 }else{
								 $finalrommstring = str_replace('No','Number',$row_orders_detail['products_room_info']); 
								$finalrommstring = str_replace('no','Number',$finalrommstring);
								$finalrommstring = str_replace('#','Number',$finalrommstring);
								$finalrommstring = str_replace('room','Room',$finalrommstring);
								$finalrommstring = str_replace('childs','children',$finalrommstring);
								//$finalrommstring = str_replace("<br>".TEXT_SHOPPIFG_CART_TOTAL_FARES_TRANSACTION_FEE,'',$finalrommstring);
								$finalrommstring = str_replace("<br>".TEXT_SHOPPIFG_CART_TOTAL_FARES_TRANSACTION_FEE_NO_PERSENT,'',$finalrommstring);
								$finalrommstring = preg_replace('/Total Fares \([0-9.]+% transaction fee included\)/','',$finalrommstring);
			
							//	echo $finalrommstring; 					
								if(eregi('- Total :',stripslashes2($finalrommstring))){
									$req_roomarray = explode('- Total :',stripslashes2($finalrommstring));
								}else if(eregi('Total :',stripslashes2($finalrommstring))){
									$req_roomarray = explode('Total :',stripslashes2($finalrommstring));
								}else if(eregi('Total :-',stripslashes2($finalrommstring))){
									$req_roomarray = explode('Total :-',stripslashes2($finalrommstring));
								}else if(eregi('- Total of Room',stripslashes2($finalrommstring))){						
									$req_roomarray[0] = preg_replace('/-[[:space:]]Total[[:space:]]of[[:space:]]Room[[:space:]][0-9]+:[[:space:]]\$[0-9\,]+.[0-9]+/', '', stripslashes2($finalrommstring));
								}else if(eregi('Total of Room',stripslashes2($finalrommstring))){						
									$req_roomarray[0] = preg_replace('/Total[[:space:]]of[[:space:]]Room[[:space:]][0-9]+:[[:space:]]\$[0-9\,]+.[0-9]+/', '', stripslashes2($finalrommstring));
								}else{
									$req_roomarray[0] = preg_replace('/Total[[:space:]]of[[:space:]]Room[[:space:]][0-9]+:[[:space:]]\$[0-9\,]+.[0-9]+/', '', stripslashes2($finalrommstring));
								
								}						
								echo db_to_html($req_roomarray[0]);
								 }//End of check if of total_room_adult_child_info
								 ?>
								 </td>
								 <td></td>
								 </tr>
							 </table>
						</td>
						
					</tr>
					<tr class="login" valign="top">
						<td><strong><?php echo TEXT_CUSTOMER_NAME;?></strong></td>
						<td id="customers_names">
						<?php
						$single_pu_tags = get_single_pu_tags($row_orders_detail['orders_id'], $row_orders_detail['products_id']);
						$customers_name = tep_get_products_guest_names_lists($row_orders_detail['orders_products_id'], $row_orders_detail['products_departure_date']);//tep_db_output($row_orders_detail['customers_name']);
						if(tep_not_null($single_pu_tags)){
							//$customers_name = str_replace('(m)','&nbsp;&nbsp;<b style="color:#F00">[单人部分配房](MALE)</b>',$customers_name);
							//$customers_name = str_replace('(f)','&nbsp;&nbsp;<b style="color:#F00">[单人部分配房](FEMALE)</b>',$customers_name);
							$customers_name.= '<div> <b style="color:#F00">[单人配房人员]</b>'.tep_filter_guest_chinese_name($single_pu_tags).'</div>';
						}
						
						$histories_action = show_histories_action($row_orders_detail['orders_products_id']);
						$can_show_guest_info = tep_can_show_guest_info($row_orders_detail['orders_products_id'], $histories_action);
						
						//显示最新客人信息和历史记录						
						if($can_show_guest_info == true){
							echo $customers_name.'<br>'.$histories_action;
							
						}else{
							//关闭人数信息
						?>
							<script>
							document.getElementById('roomNumInfo').style.display = 'none';
							</script>
							
							<b>游客信息有更新但未最后确定，目前暂时不可见！您可联系我司相关人员解决此事！</b>
						<?php
						}
						?>
						
						</td>
					</tr>
					<tr class="login">
						<td><strong><?php echo TEXT_CUSTOMER_CONTACT_NO;?></strong></td>
						<td><?php echo tep_db_output($row_orders_detail['customers_telephone']);?></td>
					</tr>
                    <tr class="login">
						<td><strong><?php echo TXT_ETICKET_GUEST_CELL_PHONE_EMERGENCY_ONLY_CELL;?></strong></td>
						<td> <?php echo tep_db_output($row_orders_detail['guest_emergency_cell_phone']);?></td>
					</tr>
					<?php if($row_orders_detail['hotel_pickup_info'] != '' || $row_orders_detail['is_hotel_pickup_info'] == '1'){ ?>
                    <tr class="login">
                    	<td valign="top"><strong><?php echo TXT_GUEST_HOTEL_INFORMATION;?></strong></td>
						<td><?php echo tep_draw_textarea_field("hotel_pickup_info", $wrap, 60, 3, stripslashes($row_orders_detail['hotel_pickup_info']));?></td>
                    </tr>
                    <?php } ?>
					<tr class="login" valign="top">
						<td><strong><?php echo TABLE_FLIGHT_INFO;?>:</strong></td>
						<td class="login">
				<?php 
					if(tep_not_null($row_orders_detail["arrival_date"]) && tep_not_null($row_orders_detail["departure_date"]) && $row_orders_detail["sent_confirm_email_to_provider"]=="1")
					{
						$products_departure_date = tep_get_date_disp($row_orders_detail["products_departure_date"]);
						if($row_orders_detail['products_durations'] > 0 && $row_orders_detail['products_durations_type'] == 0){
							$prod_dura_day = $row_orders_detail["products_durations"]-1;
						}else{
							$prod_dura_day = 0;
						}
					?>
							<table border="1" cellspacing="0" cellpadding="5" class="login" width="95%">
								<tr>
									<td class="text" colspan="2" align="center"><?php echo TITLE_AR.' ('.sprintf(TXT_TOUR_STARTS_ON, $products_departure_date).')';?></td>
									<td class="text" colspan="2" align="center"><?php echo TITLE_DR.' ('.sprintf(TXT_TOUR_ENDS_ON, date_add_day($prod_dura_day, 'd', $products_departure_date)).')';?></td>
								</tr>
								<tr>
									<td width="18%" class="text"><?php echo TITLE_AR_AIRLINE_NAME;?></td>
									<td width="32%"><?php echo tep_db_output($row_orders_detail["airline_name"]);?></td>
									<td width="18%" class="text"><?php echo TITLE_DR_AIRLINE_NAME;?></td>
									<td width="32%"><?php echo tep_db_output($row_orders_detail["airline_name_departure"]);?></td>
								</tr>
								<tr>
									<td class="text"><?php echo TITLE_AR_FLIGHT_NUM;?></td>
									<td><?php echo tep_db_output($row_orders_detail["flight_no"]);?></td>
									<td class="text"><?php echo TITLE_DR_FLIGHT_NUM;?></td>
									<td><?php echo tep_db_output($row_orders_detail["flight_no_departure"]);?></td>
								</tr>
								<tr>
									<td class="text"><?php echo TITLE_AR_AIRPORT_NAME;?></td>
									<td><?php echo tep_db_output($row_orders_detail["airport_name"]);?></td>
									<td class="text"><?php echo TITLE_DR_AIRPORT_NAME;?></td>
									<td><?php echo tep_db_output($row_orders_detail["airport_name_departure"]);?></td>
								</tr>
								<tr>
									<td class="text"><?php echo TITLE_AR_DATE;?></td>
									<td><?php echo tep_db_output($row_orders_detail["arrival_date"]);?></td>
									<td class="text"><?php echo TITLE_DR_DATE;?></td>
									<td><?php echo tep_db_output($row_orders_detail["departure_date"]);?></td>
								</tr>
								<tr>
									<td class="text"><?php echo TITLE_AR_TIME;?></td>
									<td><?php echo tep_db_output($row_orders_detail["arrival_time"]);?></td>
									<td class="text"><?php echo TITLE_DR_TIME;?></td>
									<td><?php echo tep_db_output($row_orders_detail["departure_time"]);?></td>
								</tr>
							</table>
						</span></a></span>
				<?php }
					else
						echo TEXT_FLIGHT_NA;
				?>	</td>
					</tr>
					<tr class="login">
						<td style="border-bottom: dashed #666666 ">&nbsp;</td>
						<td style="border-bottom: dashed #666666 ">&nbsp;</td>
					</tr>
					<!-- <tr class="login">
						<td><strong><?php //echo TEXT_CUSTOMER_GENDER;?></strong></td>
						<td><?php //echo tep_db_output($row_orders_detail['']);?></td>
					</tr> -->
					<tr class="login">
						<td><strong><?php echo TEXT_CONFIRMATION_NO;?></strong></td>
						<td>
						<?php echo tep_draw_input_field("customer_confirmation_no", tep_db_output($row_orders_detail['customer_confirmation_no']));?>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<?php /*
						<strong><?php echo TEXT_SEAT_NO;?></strong>
						<?php echo tep_draw_input_field("customer_seat_no", tep_db_output($row_orders_detail['customer_seat_no']));?>
						*/?>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<strong><?php echo TEXT_BUS_NO;?></strong>
						<?php echo tep_draw_input_field("customer_bus_no", tep_db_output($row_orders_detail['customer_bus_no']));?>
						</td>
					</tr>
					<tr class="login">
						<td><strong><?php echo TEXT_INVOICE_NO;?></strong></td>
						<td>
						<?php echo tep_draw_input_field("customer_invoice_no", tep_db_output($row_orders_detail['customer_invoice_no']));?>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<strong><?php echo TEXT_INVOICE_TOTAL;?></strong>
						<?php echo tep_draw_input_field("customer_invoice_total", tep_db_output($row_orders_detail['customer_invoice_total']));?></td>
					</tr>
					<tr class="login" valign="top">
						<td><strong><?php echo TEXT_INVOICE_COMMENT;?></strong></td>
						<td><?php echo tep_draw_textarea_field("customer_invoice_comment", $wrap, 80, 3, stripslashes($row_orders_detail['customer_invoice_comment']));?></td>
					</tr>
					<?php if(@ini_get("file_uploads")) {	//文件上传?>
					<tr class="login" valign="top">
						<td><b>上传发票附件：</b></td>
						<td>
							<input name="vFile" type="file" id="vFile" size="75" />
							<br />
							允许上传<?php echo ini_get("upload_max_filesize");?>之内的文件，只能上传<?php echo implode('、',$allow_type);?>文件。<br />
							
							<?php
							$_files_val = explode(';',(string)$row_orders_detail['customer_invoice_files']);
							foreach((array)$_files_val as $ival){
								echo '<div><a href="/images/invoices/'.$ival.'" target="_blank">'.$ival.'</a></div>';
							}
							?>
						</td>
					</tr>
					<?php }?>
					
<!-- Itinerary - Start -->
<?php
	if($orders_eticket_result['special_note']=='')
	{
		$special_query = tep_db_query("select products_special_note, products_vacation_package from ".TABLE_PRODUCTS."  where products_id = '" . (int)$row_orders_detail['products_id'] . "' ");
		$special_result = tep_db_fetch_array($special_query);
		$special_note = $special_result['products_special_note'];
	}else{
		$special_note = $orders_eticket_result['special_note'];
	}
	if($special_result['products_vacation_package'] == '1'){
			if($display_eticket_itinerary_new_format==true){
				$special_note_itinerary = 'If you will self transfer to hotel on day one, please connect with customer service dept or local tour contact to re-confirm hotel information three days prior to departure date.<br>如果您需要在第一天自行前往酒店,请在出发前三日联系走四方网客服部门或地接社以最后确定酒店信息。';
			}
	}
?>
<tr class="login" valign="top">
		<td>
		<strong><?php echo TEXT_TOUR_ITINERARY;?></strong><br />
		<strong style="color:#F00"><?php echo TEXT_TOUR_ITINERARY_NOTES;?></strong>
		</td>
		<td class="login">
			<table width="100%" cellpadding="3" cellspacing="0" class="login">
				<?php 
				$languages_id=1;
				$agency_query = tep_db_query("select a.*,p.products_vacation_package from ".TABLE_PRODUCTS." as p, ".TABLE_TRAVEL_AGENCY." as a where p.products_id = '" . (int)$row_orders_detail['products_id'] . "' and p.agency_id = a.agency_id ");
				$agency_result = tep_db_fetch_array($agency_query);
				$arr_highlight_agency_details=array(0); //array(33,67,68,3,35,36); 特殊的供应商0为无
				$style_highlight_agency='';
				if(in_array($agency_result['agency_id'], $arr_highlight_agency_details)){
					$style_highlight_agency='style="font-weight:bold; font-size:15px; color:#000000;"';
				}
				$orders_eticket_query = tep_db_query("select * from ".TABLE_ORDERS_PRODUCTS_ETICKET." where orders_products_id = '" . (int)$row_orders_detail['orders_products_id'] . "' ");
				$orders_eticket_result = tep_db_fetch_array($orders_eticket_query);
				$tour_arrangement_providers = tep_not_null($orders_eticket_result['tour_arrangement_providers']) ? $orders_eticket_result['tour_arrangement_providers'] : $orders_eticket_result['tour_arrangement']; 

				if($tour_arrangement_providers == ''){
					if($display_eticket_itinerary_new_format==true && $forceelase == true){
						$suggested_tour_itinerary_date = tep_date_short($row_orders_detail['products_departure_date']);										
						$suggested_tour_itinerary_pick_up_time = $row_orders_detail['products_departure_time'].' '.$row_orders_detail['products_departure_location'];				
						$suggested_tour_eticket_itinerary = tep_get_products_eticket_itinerary($row_orders_detail['products_id'],$languages_id);				
						$suggested_tour_eticket_hotel = tep_get_products_eticket_hotel($row_orders_detail['products_id'],$languages_id);		
						$suggested_tour_eticket_notes = $special_note_itinerary.'<br /><br />'.tep_get_products_eticket_notes($row_orders_detail['products_id'],$languages_id);
						$suggested_tour_eticket_local_contact="";
						if($orders_eticket_result['tour_provider'] != ''){
							$suggested_tour_eticket_local_contact.=stripslashes2($orders_eticket_result['tour_provider']);
						}else{
							$suggested_tour_eticket_local_contact.=$agency_result['agency_name'].' '.TXT_ETICKET_TEL.' '.$agency_result['phone'];
						}
						$suggested_tour_eticket_local_contact.="<br><br>".TXT_ETICKET_EMERGENCY_CONTACT_PERSON."<br>";
						if($orders_eticket_result['emergency_contact_person'] != ''){
							$suggested_tour_eticket_local_contact.=stripslashes2($orders_eticket_result['emergency_contact_person']);
						}else{
							$suggested_tour_eticket_local_contact.=stripslashes2($agency_result['emerency_contactperson']);
						}
						$suggested_tour_eticket_local_contact.="<br><br>".TXT_ETICKET_EMERGENCY_CONTACT_NUMBER."<br>";
						if($orders_eticket_result['emergency_contact_no'] != ''){
							$suggested_tour_eticket_local_contact.=stripslashes2($orders_eticket_result['emergency_contact_no']);
						}else{
							$suggested_tour_eticket_local_contact.=stripslashes2($agency_result['emerency_number']);
						}
						
						$suggested_tour_eticket_local_contact_arr_explode = explode('!##!',$suggested_tour_eticket_local_contact);
						$suggested_tour_eticket_itinerary_arr_explode = explode('!##!',$suggested_tour_eticket_itinerary);
						$suggested_tour_eticket_hotel_arr_explode = explode('!##!',$suggested_tour_eticket_hotel);
						$suggested_tour_eticket_notes_arr_explode = explode('!##!',$suggested_tour_eticket_notes);
						
						$exra_auto_row_load_eticke ='';
						for ($lp_i = 0; $lp_i < sizeof($suggested_tour_eticket_itinerary_arr_explode); $lp_i++) {
							if($lp_i > 0){
							$suggested_tour_itinerary_pick_up_time = '';
							}
								$exra_auto_row_load_eticke .= '<TR valign="top">
			
													<TD>'.date_add_day($lp_i,'d',$suggested_tour_itinerary_date).'<br /><br />'.$suggested_tour_itinerary_pick_up_time.'&nbsp;</TD>
													<TD>'.$suggested_tour_eticket_local_contact_arr_explode[$lp_i].'&nbsp;</TD>
													
													<TD>'.$suggested_tour_eticket_itinerary_arr_explode[$lp_i].'&nbsp;</TD>
													
													<TD>'.$suggested_tour_eticket_hotel_arr_explode[$lp_i].'&nbsp;</TD>
													
													<TD>'.$suggested_tour_eticket_notes_arr_explode[$lp_i].'&nbsp;</TD></TR>';
											
						}
					}else{
		
					$suggested_tour_itinerary_date = tep_get_date_disp($row_orders_detail['products_departure_date']);								
					$suggested_tour_itinerary_pick_up_time = $row_orders_detail['products_departure_time'].' '.$row_orders_detail['products_departure_location'];			
					if(in_array($agency_result['agency_id'], $arr_highlight_agency_details)){				
						$suggested_tour_itinerary_pick_up_time = '<span '.$style_highlight_agency.' >'.$row_orders_detail['products_departure_time'].' '.$row_orders_detail['products_departure_location'].'</span>';	
					}		
					
					$suggested_tour_eticket_itinerary = tep_get_products_eticket_itinerary($row_orders_detail['products_id'],$languages_id);				
					$suggested_tour_eticket_pickup_details = tep_get_products_eticket_pickup($row_orders_detail['products_id'],$languages_id);
					$suggested_tour_eticket_hotel = tep_get_products_eticket_hotel($row_orders_detail['products_id'],$languages_id);		
					$suggested_tour_eticket_notes = tep_get_products_eticket_notes($row_orders_detail['products_id'],$languages_id);			
				
					$suggested_tour_eticket_itinerary_arr_explode = explode('!##!',$suggested_tour_eticket_itinerary);
					$suggested_tour_eticket_pickup_details_arr_explode = explode('!##!',$suggested_tour_eticket_pickup_details);
					$suggested_tour_eticket_hotel_arr_explode = explode('!##!',$suggested_tour_eticket_hotel);
					$suggested_tour_eticket_notes_arr_explode = explode('!##!',$suggested_tour_eticket_notes);
				
					$exra_auto_row_load_eticke ='';
					for ($lp_i = 0; $lp_i < sizeof($suggested_tour_eticket_itinerary_arr_explode); $lp_i++) {
						
						if($lp_i > 0){
							$suggested_tour_itinerary_pick_up_time = '';
						}else{
							if($agency_result['products_vacation_package']){
								$suggested_tour_eticket_notes_arr_explode[$lp_i] = ''.$suggested_tour_eticket_notes_arr_explode[$lp_i];
							}else{
								$suggested_tour_eticket_notes_arr_explode[$lp_i] = TXT_ETICKET_NOTE_CALL_TO_RECONFIRM1.$suggested_tour_eticket_notes_arr_explode[$lp_i];
							}
						}
						$exra_auto_row_load_eticke .= '
						<tr>
							<td style="padding-top:10px">
							<table width="100%" border="1" cellpadding="2" cellspacing="0" >
							  <tr><td colspan="2" bgcolor="#CCCCCC" height="25" style="padding:5px 10px"><b>'.sprintf(TXT_DAY_ETICKIT, ($lp_i+1)).'</b></td></tr>
							  <tr><td colspan="2"><table width="100%"><tr><td width="20%" height="25">'.TXT_DATE_ETICKIT.'</td>
							  <td width="80%" height="25">'.date_add_day($lp_i,'d',$suggested_tour_itinerary_date).'&nbsp;</td></tr></table></td>
							  </tr>
							  <tr><td colspan="2"><table width="100%"><tr><td width="20%" height="25">'.TXT_PICKUP_DETAILS_ETICKIT.'</td><td width="80%" height="25" valign="top">'.($suggested_tour_itinerary_pick_up_time != '' ? $suggested_tour_itinerary_pick_up_time.'<br />'.$suggested_tour_eticket_pickup_details_arr_explode[$lp_i] : $suggested_tour_eticket_pickup_details_arr_explode[$lp_i]).'</td></tr></table></td></tr>
							  
							  <tr><td colspan="2"><table width="100%"><tr><td width="20%" height="25">'.TXT_ETICKET_ITINERARY.'</td><td width="80%" height="25">'.$suggested_tour_eticket_itinerary_arr_explode[$lp_i].'&nbsp;</td></tr></table></td></tr>
							  <tr><td colspan="2"><table width="100%"><tr><td valign="top" width="20%" height="25">'.TXT_ETICKET_HOTEL.'</td>
							  <td width="80%" height="25">'.$suggested_tour_eticket_hotel_arr_explode[$lp_i].'&nbsp;</td></tr></table></td></tr>
							  <tr><td colspan="2"><table width="100%"><tr><td valign="top" width="20%" height="25">'.TXT_ETICKET_NOTE.'</td>
							  <td width="80%" height="25">'.$suggested_tour_eticket_notes_arr_explode[$lp_i].'&nbsp;</td></tr></table></td></tr>
							</table>
							</td>
						</tr>';
												
										
					} // end for loop
				}
				$tour_arrangement_providers = '<TABLE width="100%" border="0">'.$exra_auto_row_load_eticke.'</TABLE>';
			}
				?>

				<?php
				//供应商修改电子票行程信息历史记录
				//20120921 Sofia要求。文件位置(功能修改.docx 中的问题1)
				//当我们员工修改电子票后，不要同步显示到地接这里的行程表。
				//但是如果地接员工修改了电子票行程内容后，必须同步给我们订单后台的让我们员工可以直接看到地接的修改，并且要在这里添加一个历史记录功能，记录地接员工自己的所有修改记录(但不显示我们的修改记录)。
				$hsql = tep_db_query('SELECT * FROM `orders_product_eticket_tour_arrangement_providers_log` WHERE orders_eticket_id="'.(int)$orders_eticket_result['orders_eticket_id'].'" order By orders_product_eticket_tour_arrangement_providers_log_id DESC ');
				$hrows = tep_db_fetch_array($hsql);
				if((int)$hrows['orders_product_eticket_tour_arrangement_providers_log_id']){
				?>
				<tr>
					<td class="main" colspan="4">
					<a href="javascript:void(0);" class="thumbnail2"><strong><nobr><u>[行程及酒店历史记录]</u></nobr></strong><span>
					<table style="width:1000px;" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<th scope="col">序号</th>
						<th scope="col">行程内容</th>
						<th scope="col">修改日期</th>
					</tr>
					<?php
					$sort_num = 0;
					do{
						$sort_num++;
						//$_tour_arrangement = getHtmlTagsContent($hrows['tour_arrangement_providers'],'gb2312','div','id','tour_arrangement');
						$_tour_arrangement = $hrows['tour_arrangement_providers'];
						
					?>
					<tr>
						<td valign="top" nowrap="nowrap"><?= ($sort_num==1 ? '最后一次更新' : $sort_num );?></td>
						<td valign="top" nowrap="nowrap"><?= stripslashes($_tour_arrangement);?></td>
						<td valign="top" nowrap="nowrap"><?= $hrows['last_modified']?></td>
					</tr>
					<tr>
						<td colspan="3"><hr /></td>
					</tr>
					<?php }while($hrows = tep_db_fetch_array($hsql));?>
					</table>
					
					</span></a>
					</td>
				</tr>
				<?php
				}
				?>
				<tr>
					<td class="main" colspan="4">
					<?php 
						//提示，这里显示的只是供应商自己修改的行程信息，并不是电子票最终的信息！
						echo tep_draw_textarea_field('tour_arrangement_providers', $wrap, '80', '30', stripslashes($tour_arrangement_providers), 'class="mceEditor"');
						echo '<div style="display:none;">'.
							 tep_draw_textarea_field('previous_tour_arrangement', $wrap, '80', '30', stripslashes($tour_arrangement_providers)).'			 
							 </div>';
					?>
					<input type="hidden" name="orders_eticket_id" value="<?= $orders_eticket_result['orders_eticket_id']?>" />
                    </td>
				</tr>
				
			</table>
		</td>

      </tr>
<!-- Itinerary - End -->
	<?php if(0){	//隐藏 更新行程模板?>
	<tr class="login" valign="top">
		<td>&nbsp;</td>
		<td><a href="<?php echo tep_href_link(DIR_WS_PROVIDERS.FILENAME_ETICKET_TEMPLATE, tep_get_all_get_params(array("action", "msg"))."&products_id=".(int)$row_orders_detail['products_id']."&orders_products_id=".(int)$row_orders_detail['orders_products_id']."", "SSL");?>" class="login"><b><?php echo LNK_UPDATE_TEMPLATE;?></b></a></td>
  </tr>
  <?php }?>

	<tr class="login" valign="top" style="display:none;"><?php //隐藏 注意事项?>
		<td><b><?php echo TEXT_SPECIAL_NOTE;?></b></td>
		<td><?php echo tep_draw_textarea_field('special_note', '', '80', '5', stripslashes($special_note)); ?></td>
  </tr>
  
	<tr class="login" valign="top">
		<td>&nbsp;<div id="is_updated_by_validation_required" style="visibility:hidden; ">1</div></td>
		<td><?php echo tep_draw_input_field('btnUpdate', '确定更新', 'class="btnclass"  onclick=document.getElementById(\'is_updated_by_validation_required\').innerHTML=0', 'submit');?></td>
  </tr>

					<tr class="login">
						<td>&nbsp;</td>
						<td>
							<?php echo '<a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_ORDERS, tep_get_all_get_params(array("action", "msg")), "SSL").'"><strong>'.'返回订单'.'</strong></a>&nbsp; | &nbsp;<a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_ORDERS_PREVIEW, 'oID='.$row_orders_detail['orders_id'].'&products_id='.$row_orders_detail['products_id'].'&action=eticket&i=0', "SSL").'" target="_blank"><strong>'.LINK_PREVIEW.'</strong></a>';?>
							<?php /*echo ' | &nbsp;<a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_ORDERS_PREVIEW_ALL, 'opID='.$row_orders_detail['orders_products_id'].'&action=eticket_printone', "SSL").'" target="_blank"><strong>'.LINK_PREVIEW_PRINT_FIRST.'</strong></a>';*/?>
							<?php echo ' | &nbsp;<a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_ORDERS_PREVIEW_ALL, 'opID='.$row_orders_detail['orders_products_id'].'&action=eticket_printall', "SSL").'" target="_blank"><strong>'.LINK_PREVIEW_PRINT_ALL.'</strong></a>';?>
						</td>
					</tr>
				</table>
				</td>
				<td align="right" valign="top">
				<table border="0" cellspacing="0" cellpadding="0">
          <tr class="dataTableHeadingRow">
            <td class="dataTableHeadingContent" align="center" colspan="2"><b>联络记录(如遇订单信息与联络记录有冲突的话请以联络记录为准！)</b></td>
          </tr>
<?php
	//列出我们与供应商的交流数据{
	$historys = tep_get_provider_order_products_status_history($oID);
	if(is_array($historys)){
	?>	
	<tr>
	<td class="borderb borderlr paddinglr">
	<table width="560" cellspacing="0" cellpadding="0" border="0" class="bor_tab table_td_p">
	<tbody>
		<tr class="bor_tab table_td_p">
		<td height="30" align="center" class="tab_t tab_line1" style="background-color:#FFF; padding:5px; border-bottom: 1px solid #F4CF91; border-right: 1px solid #F4CF91;">&nbsp;
		<b><?php echo $historys['heardTitie'][0]?></b>		</td>
		<td align="center" class="tab_t tab_line1" style=" background-color:#FEFBED; padding:5px; border-bottom: 1px solid #F4CF91;"><b><?php echo $historys['heardTitie'][1]?></b></td>
	</tr>
	<tr>
	<?php
	for($i=0, $n=sizeof($historys)-1; $i<$n; $i++){
		//显示内容以我们发布的内容为主导，如果是供应商回复的内容应该跟到我们右边的单元格，如果是我们回复供应商的则要跳下一行左边单元格显示。
		if(tep_not_null($historys[$i][0]) || tep_not_null($historys[$i+1][1])){
	?>
	<tr>
		<td valign="bottom" style="width:50%; background-color:#FFF; padding:5px; border-bottom: 1px solid #F4CF91; border-right: 1px solid #F4CF91;"><?php if(tep_not_null($historys[$i][0])){ echo $historys[$i][0]['provider_order_status_name'].' - '.nl2br(tep_db_output($historys[$i][0]['provider_comment'])).'<br><span style="font-size:12px; color:#999999;">'.date('n/j/Y H:i:s',strtotime($historys[$i][0]['provider_status_update_date']))." By ".tep_get_admin_customer_name($historys[$i][0]['popc_updated_by'], (int)$historys[$i][0]['popc_user_type']).' </span>'; }?>&nbsp;</td>
		
		<td align="left" valign="bottom" style="width:50%; background-color:#FEFBED; padding:5px; border-bottom: 1px solid #F4CF91;">
		<?php
		if(tep_not_null($historys[$i+1][1])){
			echo $historys[$i+1][1]['provider_order_status_name'].' - '.nl2br(tep_db_output($historys[$i+1][1]['provider_comment'])).'<br><span style="font-size:12px; color:#999999;">'.date('n/j/Y H:i:s',strtotime($historys[$i+1][1]['provider_status_update_date']))." By ".tep_db_output($historys[$i+1][1]['popc_updated_by']).'</span>'; 
		}
		?>
		
		&nbsp;</td>
	</tr>
	<?php
		}
	}
	
	?>
		</tbody>
		</table>
		</td>
	</tr>
	<?php
	}
	//列出我们与供应商的交流数据}

?>
        </table>
				<table><tr><td class="login">
				<strong>请一定选择回复方式：</strong>
				</td><td>
				<?php
			//注意：4,5在后台没有对应的订单状态，如果需要打开这两个状态就应提前在后台添加它们对应的订单状态。/admin/provider_order_products_status.php
			$qry_pro_status ="select * from " . TABLE_PROVIDER_ORDER_PRODUCTS_STATUS ." os WHERE os.provider_order_status_for=1 and provider_order_status_id NOT IN (4,5,23) ORDER BY os.sort_order ASC, os.provider_order_status_name ASC"; 
			$res_pro_status=tep_db_query($qry_pro_status);
			$pro_status_array[] = array('id' => 0,
										'text' => SELECT_NONE);
			while ($row_pro_status = tep_db_fetch_array($res_pro_status)) {
			$pro_status_array[] = array('id' => $row_pro_status['provider_order_status_id'],
									'text' => $row_pro_status['provider_order_status_name']);
			}
			echo tep_draw_pull_down_menu('provider_order_status_id', $pro_status_array, $_POST['provider_order_status_id'], $parameters = '');?>&nbsp;
				</td></tr>
					<tr>
						<td class="login"><strong>回复内容：</strong></td>
						<td>
						<?php echo tep_draw_textarea_field("provider_comment", $wrap, 60, 3, $_POST['provider_comment']);?>
						</td>
					</tr>
					<?php //隐藏通知我们的复选框，强制不选中！?>
					<tr class="login" style="display:none">
						<td class="main"><strong><?php echo ENTRY_NOTIFY_TFF_SERVICES_TEAM; ?></strong></td>
						<td><?php echo tep_draw_checkbox_field('notify_usi4trip', '', true); ?></td>		
					</tr>					
					<tr class="login">
						<td><strong><?php //echo TEXT_COMMENT_UPDATED_BY;?>回复者（请必须填写）：</strong></td>
						<td><?php echo tep_draw_input_field("popc_updated_by", tep_db_output($_POST['popc_updated_by']));?></td>
					</tr>
					<tr class="login">
						<td>&nbsp;</td>
						<td>
						<?php echo tep_draw_input_field('btnSubmit', '发送给走四方', 'class="btnclass" onclick=document.getElementById(\'is_updated_by_validation_required\').innerHTML=1', 'submit');?>
						</td>
					</tr>
				</table>
				</td>
				</tr></table>
			</form>
			<?php }
		}else{
			//订单列表
			?>
				<table border="0" width="100%" height="100%" cellspacing="0" cellpadding="0" bgcolor="#F0F0FF" class="grayBorder login">
<?php
$HEADING_ORDER_ID=TABLE_HEADING_ORDER_ID;
$HEADING_ORDER_ID.=' <a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_ORDERS, tep_get_all_get_params(array("sort", "action", "msg", "order")).'&sort=o.orders_id&order=asc', "SSL").'"><img src="'.DIR_WS_TEMPLATE_IMAGES.'arrow_up.gif" border="0"></a>';
$HEADING_ORDER_ID.= ' <a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_ORDERS, tep_get_all_get_params(array("sort", "action", "msg", "order")).'&sort=o.orders_id&order=desc', "SSL").'"><img src="'.DIR_WS_TEMPLATE_IMAGES.'arrow_down.gif" border="0"></a>';
$HEADING_INVOICE_NO=TABLE_HEADING_INVOICE_NO;
$HEADING_INVOICE_NO.=' <a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_ORDERS, tep_get_all_get_params(array("sort", "action", "msg", "order")).'&sort=customer_invoice_no&order=asc', "SSL").'"><img src="'.DIR_WS_TEMPLATE_IMAGES.'arrow_up.gif" border="0"></a>';
$HEADING_INVOICE_NO.= ' <a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_ORDERS, tep_get_all_get_params(array("sort", "action", "msg", "order")).'&sort=customer_invoice_no&order=desc', "SSL").'"><img src="'.DIR_WS_TEMPLATE_IMAGES.'arrow_down.gif" border="0"></a>';
$HEADING_PRODUCT_ID=TABLE_HEADING_PRODUCT_ID;
$HEADING_PRODUCT_ID.=' <a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_ORDERS, tep_get_all_get_params(array("sort", "action", "msg", "order")).'&sort=p.provider_tour_code&order=asc', "SSL").'"><img src="'.DIR_WS_TEMPLATE_IMAGES.'arrow_up.gif" border="0"></a>';
$HEADING_PRODUCT_ID.= ' <a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_ORDERS, tep_get_all_get_params(array("sort", "action", "msg", "order")).'&sort=p.provider_tour_code&order=desc', "SSL").'"><img src="'.DIR_WS_TEMPLATE_IMAGES.'arrow_down.gif" border="0"></a>';
$HEADING_PRODUCT=TABLE_HEADING_PRODUCT;
$HEADING_PRODUCT.=' <a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_ORDERS, tep_get_all_get_params(array("sort", "action", "msg", "order")).'&sort=op.products_name&order=asc', "SSL").'"><img src="'.DIR_WS_TEMPLATE_IMAGES.'arrow_up.gif" border="0"></a>';
$HEADING_PRODUCT.= ' <a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_ORDERS, tep_get_all_get_params(array("sort", "action", "msg", "order")).'&sort=op.products_name&order=desc', "SSL").'"><img src="'.DIR_WS_TEMPLATE_IMAGES.'arrow_down.gif" border="0"></a>';
$HEADING_DATE_PURCHASED=TABLE_HEADING_DATE_PURCHASED;
$HEADING_DATE_PURCHASED.=' <a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_ORDERS, tep_get_all_get_params(array("sort", "action", "msg", "order")).'&sort=o.date_purchased&order=asc', "SSL").'"><img src="'.DIR_WS_TEMPLATE_IMAGES.'arrow_up.gif" border="0"></a>';
$HEADING_DATE_PURCHASED.= ' <a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_ORDERS, tep_get_all_get_params(array("sort", "action", "msg", "order")).'&sort=o.date_purchased&order=desc', "SSL").'?sort=o.date_purchased&order=desc"><img src="'.DIR_WS_TEMPLATE_IMAGES.'arrow_down.gif" border="0"></a>';
$HEADING_DATE_DEPARTURE=TABLE_HEADING_DATE_DEPARTURE;
$HEADING_DATE_DEPARTURE.=' <a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_ORDERS, tep_get_all_get_params(array("sort", "action", "msg", "order")).'&sort=op.products_departure_date&order=asc', "SSL").'"><img src="'.DIR_WS_TEMPLATE_IMAGES.'arrow_up.gif" border="0"></a>';
$HEADING_DATE_DEPARTURE.= ' <a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_ORDERS, tep_get_all_get_params(array("sort", "action", "msg", "order")).'&sort=op.products_departure_date&order=desc', "SSL").'"><img src="'.DIR_WS_TEMPLATE_IMAGES.'arrow_down.gif" border="0"></a>';
$HEADING_TOUR_CODE=TABLE_HEADING_TOUR_CODE;
$HEADING_TOUR_CODE.=' <a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_ORDERS, tep_get_all_get_params(array("sort", "action", "msg", "order")).'&sort=p.provider_tour_code&order=asc', "SSL").'"><img src="'.DIR_WS_TEMPLATE_IMAGES.'arrow_up.gif" border="0"></a>';
$HEADING_TOUR_CODE.= ' <a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_ORDERS, tep_get_all_get_params(array("sort", "action", "msg", "order")).'&sort=p.provider_tour_code&order=desc', "SSL").'"><img src="'.DIR_WS_TEMPLATE_IMAGES.'arrow_down.gif" border="0"></a>';
$HEADING_PROVIDERS_CODE=TABLE_HEADING_PROVIDERS_CODE;
$HEADING_PROVIDERS_CODE.=' <a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_ORDERS, tep_get_all_get_params(array("sort", "action", "msg", "order")).'&sort=a.agency_code&order=asc', "SSL").'"><img src="'.DIR_WS_TEMPLATE_IMAGES.'arrow_up.gif" border="0"></a>';
$HEADING_PROVIDERS_CODE.= ' <a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_ORDERS, tep_get_all_get_params(array("sort", "action", "msg", "order")).'&sort=a.agency_code&order=desc', "SSL").'"><img src="'.DIR_WS_TEMPLATE_IMAGES.'arrow_down.gif" border="0"></a>';
$HEADING_ORDERS_STATUS=TABLE_HEADING_ORDERS_STATUS;
$HEADING_ORDERS_STATUS.=' <a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_ORDERS, tep_get_all_get_params(array("sort", "action", "msg", "order")).'&sort=os.orders_status_name&order=asc', "SSL").'"><img src="'.DIR_WS_TEMPLATE_IMAGES.'arrow_up.gif" border="0"></a>';
$HEADING_ORDERS_STATUS.= ' <a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_ORDERS, tep_get_all_get_params(array("sort", "action", "msg", "order")).'&sort=os.orders_status_name&order=desc', "SSL").'"><img src="'.DIR_WS_TEMPLATE_IMAGES.'arrow_down.gif" border="0"></a>';
$HEADING_PROVIDER_ORDERS_STATUS=TABLE_HEADING_PROVIDER_ORDERS_STATUS;
$HEADING_PROVIDER_ORDERS_STATUS.=' <a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_ORDERS, tep_get_all_get_params(array("sort", "action", "msg", "order")).'&sort=ps.provider_order_status_name&order=asc', "SSL").'"><img src="'.DIR_WS_TEMPLATE_IMAGES.'arrow_up.gif" border="0"></a>';
$HEADING_PROVIDER_ORDERS_STATUS.= ' <a href="'.tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_ORDERS, tep_get_all_get_params(array("sort", "action", "msg", "order")).'&sort=ps.provider_order_status_name&order=desc', "SSL").'"><img src="'.DIR_WS_TEMPLATE_IMAGES.'arrow_down.gif" border="0"></a>';
$HEADING_ACTION=TABLE_HEADING_ACTION;
$HEADING_FLIGHT_INFO=TABLE_FLIGHT_INFO;
?>
					<tr class="dataTableHeadingRow">
						<td class="dataTableHeadingContent"><?php echo $HEADING_INVOICE_NO;?></td>
						<td class="dataTableHeadingContent"><?php echo $HEADING_ORDER_ID; ?></td>
						<td class="dataTableHeadingContent"><?php echo $HEADING_PRODUCT_ID; ?></td>
						<td class="dataTableHeadingContent"><?php echo $HEADING_DATE_PURCHASED; ?></td>
						<td class="dataTableHeadingContent"><?php echo $HEADING_DATE_DEPARTURE; ?></td>
						<td class="dataTableHeadingContent"><?php echo $HEADING_PROVIDER_ORDERS_STATUS; ?></td>
						<td class="dataTableHeadingContent"><?php echo $HEADING_FLIGHT_INFO; ?></td>
						<td class="dataTableHeadingContent"><?php echo $HEADING_ACTION; ?></td>
					</tr>
<?php
	//用分页类有问题，不用了。by lwkai add 2013-12-24
	//$db_providers_orders = new splitPageResults($qry_providers_orders, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, 'o.orders_id', 'page');
	$db_providers_orders = new splitPageResults('', MAX_DISPLAY_SEARCH_RESULTS_ADMIN);
	//$res_providers_orders = tep_db_query($db_providers_orders->sql_query);
	
	$pagesize = MAX_DISPLAY_SEARCH_RESULTS_ADMIN;
	
	$tables = "`orders_products` AS op, products AS p, orders AS o,".TABLE_PROVIDER_ORDER_PRODUCTS_STATUS." AS ps";
	
	$where = "ps.provider_order_status_id=op.provider_order_status_id AND p.agency_id = " . (int)$_SESSION['providers_agency_id'] . " AND op.products_id = p.products_id AND o.orders_id = op.orders_id AND
		(
			o.date_purchased >= '".date('Y-m-d 00:00:00',strtotime($cond_records_after_date))."' OR
			(
				op.order_item_purchase_date >= '".date('Y-m-d 00:00:00',strtotime($cond_records_after_date))."' && op.order_item_purchase_date != '0000-00-00 00:00:00'
			)
		)"; 
	$order_by = '';
	if(tep_not_null($_GET['order_status'])){
		$where .= " AND op.provider_order_status_id='".tep_db_input($_GET['order_status'])."'";
	}
	if (tep_not_null($_GET['search_invoice'])) {
		$where .= " AND op.customer_invoice_no='" . tep_db_input($_GET['search_invoice']) . "'";
	}
	if(tep_not_null($_GET['search_order'])){
		$where.=" AND o.orders_id='".tep_db_input($_GET['search_order'])."'";
	}
	if (tep_not_null($_GET['search_name'])) {
		$tables .= ",".TABLE_ORDERS_PRODUCTS_ETICKET." ope";
		$where .= "ope.orders_products_id=op.orders_products_id AND ope.guest_name like '%" . tep_db_input($_GET['search_name']) . "%'";
	}
	if(tep_not_null($_GET['search_tour_code'])){
		$where.=" AND (p.products_id = '".tep_db_input($_GET['search_tour_code'])."' OR p.provider_tour_code LIKE '%".tep_db_input($_GET['search_tour_code'])."%')";
	}
	if(tep_not_null($_GET['search_traveler'])){
		//$where.=" AND o.orders_id IN (SELECT et.orders_id FROM ".TABLE_ORDERS_PRODUCTS_ETICKET." et WHERE et.guest_name LIKE '%".tep_db_input($_GET['search_traveler'])."%')";
		$where .= " AND op.products_name like '%" . tep_db_input($_GET['search_traveler']) . "%'";
	}
	$make_start_date = tep_get_date_db($HTTP_GET_VARS['dept_start_date']);
	$make_end_date = tep_get_date_db($HTTP_GET_VARS['dept_end_date']);
	if((isset($make_start_date) && tep_not_null($make_start_date)) && (isset($make_end_date) && tep_not_null($make_end_date)) ) {
		$where .= " AND op.products_departure_date >= '" . date('Y-m-d H:i:s',strtotime($make_start_date)) . "' AND op.products_departure_date <= '" . date('Y-m-d H:i:s',strtotime($make_end_date)) . "' ";
	}else if ((isset($make_start_date) && tep_not_null($make_start_date)) && (!isset($make_end_date) || !tep_not_null($make_end_date)) ) {
		$where .= " AND op.products_departure_date >= '" . date('Y-m-d H:i:s',strtotime($make_start_date)) . "' ";
	}else if ((!isset($make_start_date) || !tep_not_null($make_start_date)) && (isset($make_end_date) && tep_not_null($make_end_date)) ) {
		$where .= " AND op.products_departure_date <= '" . date('Y-m-d H:i:s',strtotime($make_end_date)) . "' ";
	}
	if($_GET["sort"]!="")
		$order_by.=$_GET["sort"];
	else
		$order_by.="o.orders_id";

	if($_GET["order"]!="")
		$order_by.=" ".$_GET["order"];
	else
		$order_by.=" DESC";
	
	
	$sql = "select count(op.orders_products_id) as num FROM " . $tables . " where " . $where . " order by " . $order_by; 

	$result = tep_db_query($sql);
	$resordset = tep_db_fetch_array($result);
	$resordCount = $resordset['num'];
	$maxPage = ceil($resordCount / $pagesize);
	$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
	$page = $page > 0 ? $page : 1;
	$list_sql = "SELECT o.orders_id, o.date_purchased, o.orders_status, 
  					op.orders_products_id, op.products_id, op.products_name, op.products_departure_date, op.products_departure_time, op.customer_invoice_no,op.is_hotel,  
  					p.provider_tour_code, p.provider_tour_code, p.products_durations, p.products_durations_type,
					ps.provider_order_status_name
				FROM " . $tables . " where " . $where . ' order by ' . $order_by . " limit " . ($page -1)*$pagesize . ',' . $pagesize;
		
	$res_providers_orders = tep_db_query($list_sql);
	if(tep_db_num_rows($res_providers_orders)>0){
	while ($row_providers_orders = tep_db_fetch_array($res_providers_orders)){
		
		echo '<tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'">' . "\n";
		?>
				<td style="padding:5px 0;"><?php echo tep_db_output($row_providers_orders['customer_invoice_no']);?></td>
				<td><?php echo '<a href="' . tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_ORDERS, tep_get_all_get_params(array("oID", "action", "msg")).'&oID=' . $row_providers_orders['orders_products_id'] . '&action=edit_order', "SSL") . '">' . tep_db_output(tep_get_products_orders_id_str($row_providers_orders['orders_id'], $row_providers_orders['is_hotel'])) .getFormatOrdersIdTagToAgency($row_providers_orders['orders_products_id'], $row_providers_orders['orders_id'], $_SESSION['providers_agency_id']). '</a>'; ?></td>
				<td><?php echo tep_db_output($row_providers_orders['provider_tour_code']);?></td>
				<td><?php echo tep_date_short($row_providers_orders['date_purchased']);?></td>
				<td><?php echo tep_date_short($row_providers_orders['products_departure_date'])." ".$row_providers_orders['products_departure_time'];?></td>
				<td><?php echo tep_db_output($row_providers_orders['provider_order_status_name']);?></td>
				<td>
				<?php 
					if(tep_not_null($row_providers_orders["arrival_date"]) && tep_not_null($row_providers_orders["departure_date"]) && $row_providers_orders["sent_confirm_email_to_provider"]=="1")
					{
						$products_departure_date = tep_get_date_disp($row_providers_orders["products_departure_date"]);
						if($row_providers_orders['products_durations'] > 0 && $row_providers_orders['products_durations_type'] == 0){
							$prod_dura_day = $row_providers_orders["products_durations"]-1;
						}else{
							$prod_dura_day = 0;
						}
					?>
						<span><a class="thumbnail" href="#"><?php echo TEXT_FLIGHT_AVAILABLE;?>
						<span style="width:580px;text-align:left;">
							<table class="login" border="0" width="100%">
								<tr>
									<td class="text" colspan="4"><?php echo TITLE_FLIGHT_INFO;?></td>
								</tr>
								<tr>
									<td class="text" colspan="2" align="center"><?php echo TITLE_AR.' ('.sprintf(TXT_TOUR_STARTS_ON, $products_departure_date).')';?></td>
									<td class="text" colspan="2" align="center"><?php echo TITLE_DR.' ('.sprintf(TXT_TOUR_ENDS_ON, date_add_day($prod_dura_day, 'd', $products_departure_date)).')';?></td>
								</tr>
								<tr>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td width="18%" class="text"><?php echo TITLE_AR_AIRLINE_NAME;?></td>
									<td width="32%"><?php echo tep_db_output($row_providers_orders["airline_name"]);?></td>
									<td width="18%" class="text"><?php echo TITLE_DR_AIRLINE_NAME;?></td>
									<td width="32%"><?php echo tep_db_output($row_providers_orders["airline_name_departure"]);?></td>
								</tr>
								<tr>
									<td class="text"><?php echo TITLE_AR_FLIGHT_NUM;?></td>
									<td><?php echo tep_db_output($row_providers_orders["flight_no"]);?></td>
									<td class="text"><?php echo TITLE_DR_FLIGHT_NUM;?></td>
									<td><?php echo tep_db_output($row_providers_orders["flight_no_departure"]);?></td>
								</tr>
								<tr>
									<td class="text"><?php echo TITLE_AR_AIRPORT_NAME;?></td>
									<td><?php echo tep_db_output($row_providers_orders["airport_name"]);?></td>
									<td class="text"><?php echo TITLE_DR_AIRPORT_NAME;?></td>
									<td><?php echo tep_db_output($row_providers_orders["airport_name_departure"]);?></td>
								</tr>
								<tr>
									<td class="text"><?php echo TITLE_AR_DATE;?></td>
									<td><?php echo tep_db_output($row_providers_orders["arrival_date"]);?></td>
									<td class="text"><?php echo TITLE_DR_DATE;?></td>
									<td><?php echo tep_db_output($row_providers_orders["departure_date"]);?></td>
								</tr>
								<tr>
									<td class="text"><?php echo TITLE_AR_TIME;?></td>
									<td><?php echo tep_db_output($row_providers_orders["arrival_time"]);?></td>
									<td class="text"><?php echo TITLE_DR_TIME;?></td>
									<td><?php echo tep_db_output($row_providers_orders["departure_time"]);?></td>
								</tr>
							</table>
						</span></a></span>
				<?php }
					else
						echo TEXT_FLIGHT_NA;
				?>				</td>
				<td><?php echo '<a href="' . tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_ORDERS, tep_get_all_get_params(array("oID", "action", "msg")).'&oID=' . $row_providers_orders['orders_products_id'] . '&action=edit_order', "SSL") . '">' . tep_image(DIR_WS_ICONS . 'small_edit.gif', ICON_EDIT) . '</a>'; ?></td>
			</tr>
<?php }?>
			<tr>
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="login" valign="top"><?php //echo $db_providers_orders->display_count($db_providers_orders->number_of_rows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_MEMBERS); ?><br><?php //echo $db_providers_orders->display_links($db_providers_orders->number_of_rows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); 
					echo $db_providers_orders->display_links_providers($resordCount, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page')), $page_name = 'page')
					?></td>
                  </tr>
                </table></td>
              </tr>
	<?php }else{?>
			<tr><td><?php echo MSG_RECORD_NOT_FOUND;?></td></tr>
	<?php }
		}?></table>
				</td>
				</tr>
            </table>
    		</td>
 		</tr>
		</table>
		</td>
	</tr>
</table>
<script type="text/javascript">
function validate_form(){
	with(document.providers_orders){
		if(customer_invoice_total.value !="" && isNaN(customer_invoice_total.value)){
			alert("Please enter valid Invoice Total (insert digits only, e.g. 366.45)");
			customer_invoice_total.focus();
			return false;
		}
/*		if(parseInt(provider_order_status_id.value)>0){*/
			if(popc_updated_by.value=="" && document.getElementById('is_updated_by_validation_required').innerHTML == "1"){
				alert("请必须填写回复者姓名");
				popc_updated_by.focus();
				return false;
			}
		//}
	}
}
</script>



<?php require(DIR_FS_PROVIDERS_INCLUDES . FILENAME_PROVIDERS_FOOTER);?>