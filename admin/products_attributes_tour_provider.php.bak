<?php
/*
$Id: products_attributes.php,v 1.3 2004/03/16 22:36:34 ccwjr Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2002 osCommerce

Released under the GNU General Public License
*/

require('includes/application_top.php');
// 备注添加删除
if($_GET['ajax']=="true"){
	include DIR_FS_CLASSES . 'Remark.class.php';
	$remark = new Remark('products_attributes_tour_provider');
	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
}

$languages = tep_get_languages();

if($_POST['action']){
	switch ($_POST['action']){
		case 'update_products_options_price':	//用预设的产品属性价格成本统一更新所有产品的属性价格成本
			if($_POST['options_values_id']){
				$last_date = date('Y-m-d H:i:s');
				//根据系数倍更新属性价格和成本
				tep_db_query('UPDATE products_attributes pa inner join products_options_values pov ON (pov.products_options_values_id=pa.options_values_id)
							SET 
								pa.options_values_price=(pov.def_price * pa.multiplier), pa.single_values_price=(pov.def_single * pa.multiplier), pa.double_values_price=(pov.def_double * pa.multiplier), pa.triple_values_price=(pov.def_triple * pa.multiplier), pa.quadruple_values_price=(pov.def_quadruple * pa.multiplier), pa.kids_values_price=(pov.def_kids * pa.multiplier),
								pa.options_values_price_cost=(pov.def_cost * pa.multiplier), pa.single_values_price_cost=(pov.def_single_cost * pa.multiplier), pa.double_values_price_cost=(pov.def_double_cost * pa.multiplier), pa.triple_values_price_cost=(pov.def_triple_cost * pa.multiplier), pa.quadruple_values_price_cost=(pov.def_quadruple_cost * pa.multiplier), pa.kids_values_price_cost=(pov.def_kids_cost * pa.multiplier), pa.last_price_updated="'.$last_date.'"
							WHERE pa.options_values_id="'.(int)$_POST['options_values_id'].'" ');
				//记录更新日志
				tep_db_query("UPDATE products_options_values SET unified_update_log=CONCAT(unified_update_log,'\n".$last_date." ".$login_id."') WHERE products_options_values_id='".(int)$_POST['options_values_id']."';");
				//此步需要更新产品价格最后更新记录，同时还要去客户购物车清空价格被修改的产品
				require(DIR_WS_CLASSES . 'Price_Change_Alert.class.php');
				$PCA = new Price_Change_Alert;
				$sql = tep_db_query('SELECT products_id FROM `products_attributes` where last_price_updated="'.$last_date.'" ');
				while($rows = tep_db_fetch_array($sql)){
					$PCA->update_product_price_last_modified($rows['products_id'], 'products_attributes_tour_provider.php');
					del_customers_basket_for_products($rows['products_id']);
				}
				echo 'ok';
				exit;
			}
		break;
	}
}

if ($_GET['action']) {
	$page_info = 'option_page=' . $_GET['option_page'] . '&value_page=' . $_GET['value_page'] . '&attribute_page=' . $_GET['attribute_page'];

	// $agency_page_info = 'action=view_travels_attri&agency_id='.$_POST['agency_id'].'&option_page=' . $_GET['option_page']. '&value_page=' . $_GET['value_page']. '&option_value_id=' . $_GET['option_value_id'];

	$agency_page_info = 'action=view_travels_attri&agency_id='.$_GET['agency_id'].'&option_page=' . $_GET['option_page']. '&value_page=' . $_GET['value_page']. '&option_value_id=' . $_GET['option_value_id'];

	if($products_attributes_permissions['能看不能编'] != true || stripos($_GET['action'],'delete') === false){
		switch($_GET['action']) {

			case 'delete_agency_attributes';

			if($_POST['delete_agency_attributes']!='')
			{
				$delete_agency_attri = $_POST['delete_agency_attributes'];
				$rem_flag=0;
				foreach($delete_agency_attri as $key=>$val)
				{
					//echo "$key=>$val<br>";



					$query="select products_id from ". TABLE_PRODUCTS ." where agency_id='".$_GET['agency_id']."'";
					$products_query_qry = tep_db_query($query);
					while($products_query_qry_rec = tep_db_fetch_array($products_query_qry))
					{


						$query_select= "select distinct pa.products_id from ". TABLE_PRODUCTS_ATTRIBUTES ." pa, " . TABLE_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER . " patp  where patp.products_options_id ='" . $val . "' and patp.products_options_id=pa.options_id and pa.products_id='".$products_query_qry_rec['products_id']."' and patp.agency_id='".$_GET['agency_id']."' ";
						$products_delete_attri_qry = tep_db_query($query_select);

						if(tep_db_num_rows($products_delete_attri_qry))
						{
							$rem_flag=1;
							while($products_delete_attri_rec = tep_db_fetch_array($products_delete_attri_qry))
							{
								//echo 'For Id'. $val ."=>" .$products_delete_attri_rec['products_id'].'<br>';

								//$rem_pid.='For Id '. $val ."=>" .$products_delete_attri_rec['products_id'].', ';

								$query_select_ptc= "select categories_id from ". TABLE_PRODUCTS_TO_CATEGORIES ." where products_id= '".$products_delete_attri_rec['products_id']."' ";
								$cate_selcet__ptc = tep_db_query($query_select_ptc);
								$cate_selcet__ptc_rec = tep_db_fetch_array($cate_selcet__ptc);
								$static_val= '<a target=_blank href=' . tep_href_link(FILENAME_CATEGORIES, 'cPath='.$cate_selcet__ptc_rec['categories_id'].'&pID=' . $products_delete_attri_rec['products_id'].'&action=new_product', 'NONSSL') . '>'.$products_delete_attri_rec['products_id'].'</a>';
								$rem_pid.='Please delete attributes options for '.tep_get_products_options_name($val).' in '.$static_val.' (Products id)<br> &nbsp;&nbsp;&nbsp;';


							}
						}
						else
						{

							$del_flag=0;
						}

						/*echo $query_select= "select pa.products_id from ". TABLE_PRODUCTS_ATTRIBUTES ." pa, " . TABLE_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER . " patp  where patp.products_options_id ='" . $val . "' and patp.products_options_id=pa.options_id and patp.agency_id='".$_GET['agency_id']."' ";
						$products_delete_attri_qry = tep_db_query($query_select);
						while($products_delete_attri_rec = tep_db_fetch_array($products_delete_attri_qry))
						{
						echo $products_delete_attri_rec['products_id'].'<br>';

						}
						*/

					}
					if($del_flag==0 && $rem_flag!=1)
					{
						tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER . " where agency_id='".$_GET['agency_id']."' and products_options_id=".$val);
					}




				}

				if($rem_flag==1)
				{
					$messageStack->add_session($rem_pid, 'error');
				}
				else
				{
					$messageStack->add_session(ERROR_ENTER_PRODUCT_OPT_DELETE, 'success');
				}
			}
			else
			{
				$messageStack->add_session(ERROR_ENTER_PRODUCT_OPTION_DELETE, 'error');
			}



			tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER, $agency_page_info));
			break;
			case 'add_product_options':
				$add_count=0;
				for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
					$option_name = $_POST['option_name'];
					// WebMakers.com Added: Product Options Sort Order
					if($option_name[$languages[$i]['id']]!='')
					{

						$exist_products_option = tep_db_query("select products_options_name from ". TABLE_PRODUCTS_OPTIONS ." where products_options_name ='" . $option_name[$languages[$i]['id']] . "'");


						$products_options_sort_order = $_POST['products_options_sort_order'];
						tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS . " (products_options_id, products_options_name, language_id, products_options_sort_order) values ('" . $_POST['products_options_id'] . "', '" . $option_name[$languages[$i]['id']] . "', '" . $languages[$i]['id'] . "', '" . $products_options_sort_order[$languages[$i]['id']] . "')");

						$add_count++;
					}
				}

				if($add_count==0)
				{
					$messageStack->add_session(ERROR_ENTER_PRODUCT_OPTION, 'error');
				}
				else
				{
					tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER . " (agency_id, products_options_id) values ('" . $_GET['agency_id'] . "','" . $_POST['products_options_id'] . "' )");

					$messageStack->add_session(ENTER_PRODUCT_OPTION, 'success');
				}
				//// insert into products attributes tour provider



				tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER, $agency_page_info));
				break;
			case 'add_product_option_values':





				$add_count=0;
				$value_name_array = $_POST['value_name'];
				for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {

					if($value_name_array[$languages[$i]['id']]!='')
					{

						$value_name = tep_db_prepare_input($value_name_array[$languages[$i]['id']]);
						tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS_VALUES . " (products_options_values_id, language_id, products_options_values_name, is_per_order_option, def_price, def_single, def_double, def_triple, def_quadruple, def_kids , def_cost, def_single_cost, def_double_cost, def_triple_cost, def_quadruple_cost, def_kids_cost ) values ('" . max(1, $_POST['value_id']) . "', '" . $languages[$i]['id'] . "', '" . tep_db_input($value_name) . "', '" . $_POST['is_per_order_option'] . "', '" . $_POST['def_price'] . "', '" . $_POST['def_single'] . "', '" . $_POST['def_double'] . "', '" . $_POST['def_triple'] . "', '" . $_POST['def_quadruple'] . "', '" . $_POST['def_kids'] . "', '" . $_POST['def_cost'] . "', '" . $_POST['def_single_cost'] . "', '" . $_POST['def_double_cost'] . "', '" . $_POST['def_triple_cost'] . "', '" . $_POST['def_quadruple_cost'] . "', '" . $_POST['def_kids_cost'] . "')");
						/*Added for products_attributes_values_tour_provider*/

						tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES_VALUES_TOUR_PROVIDER . "
			 (agency_id, products_options_id, products_options_values_id, language_id) values ('" . $_POST['agency_id'] . "','" . $_POST['option_id'] . "','" . max(1, $_POST['value_id']) . "', '" . $languages[$i]['id'] . "')");
						/*Ended for products_attributes_values_tour_provider*/

						$add_count++;
					}
				}


				if($add_count==0)
				{
					$messageStack->add_session(ERROR_ENTER_PRODUCT_OPTION_VALUE, 'error');
				}
				else
				{

					tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " (products_options_id, products_options_values_id) values ('" . $_POST['option_id'] . "', '" . max(1, $_POST['value_id']) . "')");
					$messageStack->add_session('产品选项值添加成功！', 'success');
				}


				tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER, $agency_page_info));
				break;
			case 'add_product_attributes':
				// BOF: WebMakers.com Added: Attribute Sorter
				// OLD        tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES . " values ('', '" . $_POST['products_id'] . "', '" . $_POST['options_id'] . "', '" . $_POST['values_id'] . "', '" . $_POST['value_price'] . "', '" . $_POST['price_prefix'] . "', '" . $_POST['sort_order'] . "')");
				tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES . " values ('', '" . $_POST['products_id'] . "', '" . $_POST['options_id'] . "', '" . $_POST['values_id'] . "', '" . $_POST['value_price'] . "', '" . $_POST['price_prefix'] . "', '" . $_POST['sort_order'] . "')");
				// EOF: WebMakers.com Added: Attribute Sorter
				$products_attributes_id = tep_db_insert_id();
				if ((DOWNLOAD_ENABLED == 'true') && $_POST['products_attributes_filename'] != '') {
					tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " values (" . $products_attributes_id . ", '" . $_POST['products_attributes_filename'] . "', '" . $_POST['products_attributes_maxdays'] . "', '" . $_POST['products_attributes_maxcount'] . "')");
				}
				tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER, $page_info));
				break;
			case 'update_option_name':
				for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
					// WebMakers.com Added: Product Options Sort Order
					$add_count=0;
					if($option_name[$languages[$i]['id']]!='')
					{
						$option_name = $_POST['option_name'];
						$products_options_sort_order = $_POST['products_options_sort_order'];
						tep_db_query("update " . TABLE_PRODUCTS_OPTIONS . " set products_options_name = '" . $option_name[$languages[$i]['id']] . "', products_options_sort_order = '" . $products_options_sort_order[$languages[$i]['id']] . "' where products_options_id = '" . $_POST['option_id'] . "' and language_id = '" . $languages[$i]['id'] . "'");
						$add_count++;

					}



				}


				if($add_count==0)
				{
					$messageStack->add_session(ERROR_ENTER_PRODUCT_OPTION, 'error');
				}
				else
				{
					$messageStack->add_session('产品选项添加成功！', 'success');
				}

				tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER, $agency_page_info));
				break;
			case 'update_value':
				$add_count=0;
				//echo sizeof($languages);
				for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {


					if($value_name[$languages[$i]['id']]!='')
					{
						$value_name = $_POST['value_name'];
						tep_db_query("update " . TABLE_PRODUCTS_OPTIONS_VALUES . " set products_options_values_name = '" . $value_name[$languages[$i]['id']] . "', is_per_order_option = '" . $_POST['is_per_order_option'] . "', def_price = '" . $_POST['def_price'] . "', def_single = '" . $_POST['def_single'] . "', def_double = '" . $_POST['def_double'] . "', def_triple = '" . $_POST['def_triple'] . "', def_quadruple = '" . $_POST['def_quadruple'] . "', def_kids = '" . $_POST['def_kids'] . "', def_cost = '" . $_POST['def_cost'] . "', def_single_cost = '" . $_POST['def_single_cost'] . "', def_double_cost = '" . $_POST['def_double_cost'] . "', def_triple_cost = '" . $_POST['def_triple_cost'] . "', def_quadruple_cost = '" . $_POST['def_quadruple_cost'] . "', def_kids_cost = '" . $_POST['def_kids_cost'] . "' where products_options_values_id = '" . $_POST['value_id'] . "' and language_id = '" . $languages[$i]['id'] . "'");
						$add_count++;
					}

				}

				if($add_count==0)
				{
					$messageStack->add_session(ERROR_ENTER_PRODUCT_OPTION_VALUE, 'error');
				}
				else
				{

					tep_db_query("update " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " set products_options_id = '" . $_POST['option_id'] . "', products_options_values_id = '" . max(1, $_POST['value_id']) . "'  where products_options_values_to_products_options_id = '" . $_POST['new_value_id'] . "'");


					tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES_VALUES_TOUR_PROVIDER . " set products_options_id = '" . $_POST['option_id'] . "', agency_id= '".$_POST['agency_id']."', products_options_values_id = '" . max(1, $_POST['value_id']) . "'  where provider_option_value_id = '" . $_POST['provider_option_value_id'] . "'");


					$messageStack->add_session('产品选项值更新成功！', 'success');
				}

				// echo $agency_page_info;
				tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER, $agency_page_info));
				break;
			case 'update_product_attribute':
				// BOF: WebMakers.com Added: Attribute Sorter
				tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES . " set products_id = '" . $_POST['products_id'] . "', options_id = '" . $_POST['options_id'] . "', options_values_id = '" . $_POST['values_id'] . "', options_values_price = '" . $_POST['value_price'] . "', price_prefix = '" . $_POST['price_prefix'] . "', products_options_sort_order = '" . $_POST['sort_order'] . "' where products_attributes_id = '" . $_POST['attribute_id'] . "'");
				//        tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES . " set products_id = '" . $_POST['products_id'] . "', options_id = '" . $_POST['options_id'] . "', options_values_id = '" . $_POST['values_id'] . "', options_values_price = '" . $_POST['value_price'] . "', price_prefix = '" . $_POST['price_prefix'] . "', products_options_sort_order = '" . $_POST['sort_order'] . "'");
				// EOF: WebMakers.com Added: Attribute Sorter
				// BOM Mod: allow for the download filename to be added or deleted when doing an edit
				if (DOWNLOAD_ENABLED == 'true') {
					$download_query_raw ="select products_attributes_filename from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . "
                              where products_attributes_id='" . $_POST['attribute_id'] . "'";
					$download_query = tep_db_query($download_query_raw);
					if (tep_db_num_rows($download_query) > 0) {
						$download_attribute_found = true;
					} else {
						$download_attribute_found = false;
					}
					if ($_POST['products_attributes_filename'] != '') {
						if ($download_attribute_found) {
							tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . "
                            set products_attributes_filename='" . $_POST['products_attributes_filename'] . "',
                                products_attributes_maxdays='" . $_POST['products_attributes_maxdays'] . "',
                                products_attributes_maxcount='" . $_POST['products_attributes_maxcount'] . "'
                            where products_attributes_id = '" . $_POST['attribute_id'] . "'");
						} else {
							tep_db_query("insert " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . "
                            set products_attributes_id = '" . $_POST['attribute_id'] . "',
                                products_attributes_filename='" . $_POST['products_attributes_filename'] . "',
                                products_attributes_maxdays='" . $_POST['products_attributes_maxdays'] . "',
                                products_attributes_maxcount='" . $_POST['products_attributes_maxcount'] . "'");
						}
					} else {
						if ($download_attribute_found) {
							tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . "
                            where products_attributes_id = '" . $_POST['attribute_id'] . "'");
						}
					}
				}
				// EOM Mod:
				tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER, $page_info));
				break;
			case 'delete_option':

				$select_attri_query=  tep_db_query("select * from " . TABLE_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER . " where agency_id != '".$_GET['agency_id']."' and products_options_id ='".$_GET['option_id']."' ");
				if(tep_db_num_rows($select_attri_query))
				{
					$delete_attri_opt_from_old_table=false;
				}
				else
				{
					$delete_attri_opt_from_old_table=true;
				}

				if($delete_attri_opt_from_old_table==true) // start delete_attri_opt_from_old_table
				{
					$products_remaining = tep_db_query("select p.products_id, pd.products_name, pov.products_options_values_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pov.language_id = '" . $languages_id . "' and pd.language_id = '" . $languages_id . "' and pa.products_id = p.products_id and pa.options_id='" . $_GET['option_id'] . "' and pov.products_options_values_id = pa.options_values_id  and p.agency_id != '".$_GET['agency_id']."' order by pd.products_name");
					if (!tep_db_num_rows($products_remaining)) {


						tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . $_GET['option_id'] . "'");
						//amit added to fixed to delete it form option value and product option  table
						$products_op_delete_sql = tep_db_query("select * from ".TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS." where products_options_id='".$_GET['option_id']."' ");

						while($products_op_delete_rowd = tep_db_fetch_array($products_op_delete_sql)){
							tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . $products_op_delete_rowd['products_options_values_id'] . "'");
						}
						tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " where products_options_id = '" . $_GET['option_id'] . "'");


						//amit added to fixed to delete it form option value and product option  table
					}

				} // end delete_attri_opt_from_old_table
				tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_VALUES_TOUR_PROVIDER . " where agency_id = '".$_GET['agency_id']."' and products_options_id ='".$_GET['option_id']."' ");

				tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER . " where agency_id='".$_GET['agency_id']."' and products_options_id=".$_GET['option_id']);



				tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER, $agency_page_info));
				break;
			case 'delete_value':


				tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_VALUES_TOUR_PROVIDER . " where products_options_values_id = '" . $_GET['value_id'] . "' and agency_id = '".$_GET['agency_id']."' and products_options_id ='".$_GET['option_value_id']."' ");

				$select_attri_query=  tep_db_query("select * from " . TABLE_PRODUCTS_ATTRIBUTES_VALUES_TOUR_PROVIDER . " where products_options_values_id = '" . $_GET['value_id'] . "' and agency_id != '".$_GET['agency_id']."' and products_options_id ='".$_GET['option_value_id']."' ");
				if(tep_db_num_rows($select_attri_query))
				{
					$delete_attri_from_old_table=false;
				}
				else
				{
					$delete_attri_from_old_table=true;
				}

				if($delete_attri_from_old_table==true) // start delete_attri_from_old_table
				{

					$products_remaining = tep_db_query("select p.products_id, pd.products_name, po.products_options_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . $languages_id . "' and po.language_id = '" . $languages_id . "' and pa.products_id = p.products_id and pa.options_values_id='" . $_GET['value_id'] . "' and po.products_options_id = pa.options_id and p.agency_id!='".$_GET['agency_id']."' order by pd.products_name, pa.products_options_sort_order, po.products_options_sort_order");
					if (!tep_db_num_rows($products_remaining)) {
						tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . $_GET['value_id'] . "'");
						/* tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . $_GET['value_id'] . "'");*/
						tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " where products_options_values_id = '" . $_GET['value_id'] . "'");
					}

				}// end of delete_attri_from_old_table

				tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER, $agency_page_info));
				break;
			case 'delete_attribute':
				tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_attributes_id = '" . $_GET['attribute_id'] . "'");
				// Added for DOWNLOAD_ENABLED. Always try to remove attributes, even if downloads are no longer enabled
				tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " where products_attributes_id = '" . $_GET['attribute_id'] . "'");
				tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER, $page_info));
				break;
		}
	}
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="includes/javascript/add_global.js"></script>
<script type="text/javascript"><!--
function go_option() {
	if (document.option_order_by.selected.options[document.option_order_by.selected.selectedIndex].value != "none") {
		location = "<?php echo tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER, 'option_page=' . ($_GET['option_page'] ? $_GET['option_page'] : 1)); ?>&option_order_by="+document.option_order_by.selected.options[document.option_order_by.selected.selectedIndex].value;
	}
}
//--></script>
<script language="javascript" src="includes/menu.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
		<?php
		//echo $login_id;
		include DIR_FS_CLASSES . 'Remark.class.php';
		$listrs = new Remark('products_attributes_tour_provider');
		$list = $listrs->showRemark();
		?>
		
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top">
	<table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table>
	</td>
<!-- body_text //-->
    <td width="100%" valign="top">
	<?php
	if($products_attributes_permissions['能看不能编'] == true){
		ob_start();
	}
	?>
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
<!-- options and values//-->
      <tr>
        <td width="100%"><table width="100%" border="0" cellspacing="0" cellpadding="5">
     		<tr>
            <td valign="top" width="50%"><table width="70%" border="0" cellspacing="0" cellpadding="2" >
<!-- options //-->
<?php
if (isset($_GET['action']))
{
	if ($_GET['action'] == 'delete_product_option') { // delete product option


		$options = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . $_GET['option_id'] . "' and language_id = '" . $languages_id . "'");
		$options_values = tep_db_fetch_array($options);
?>

              <tr>
                <td class="pageHeading">&nbsp;<?php echo $options_values['products_options_name']; ?>&nbsp;</td>
                <td>&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '1', '53'); ?>&nbsp;</td>
              </tr>
              <tr>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td colspan="4"><?php echo tep_black_line(); ?></td>
                  </tr>
<?php


$products = tep_db_query("select p.products_id, p.products_model, pd.products_name, pov.products_options_values_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pov.language_id = '" . $languages_id . "' and pd.language_id = '" . $languages_id . "' and pa.products_id = p.products_id and pa.options_id='" . $_GET['option_id'] . "' and pov.products_options_values_id = pa.options_values_id  and p.agency_id='".$_GET['agency_id']."' order by pd.products_name");




if (tep_db_num_rows($products)) {
?>
                  <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</td>
                    <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_MODEL; ?>&nbsp;</td>
					<td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT; ?>&nbsp;</td>
                    <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_VALUE; ?>&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="4"><?php echo tep_black_line(); ?></td>
                  </tr>
<?php
while ($products_values = tep_db_fetch_array($products)) {
	$rows++;
?>
                  <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
                    <td align="center" class="smallText">&nbsp;<?php echo $products_values['products_id']; ?>&nbsp;</td>
					 <td align="left" class="smallText">&nbsp;<?php echo $products_values['products_model']; ?>&nbsp;</td>
                    <td class="smallText">&nbsp;<?php echo $products_values['products_name']; ?>&nbsp;</td>
                    <td class="smallText">&nbsp;<?php echo $products_values['products_options_values_name']; ?>&nbsp;</td>
                  </tr>
<?php
}
?>
                  <tr>
                    <td colspan="4"><?php echo tep_black_line(); ?></td>
                  </tr>
                  <tr>
                    <td colspan="3" class="main"><br><?php echo TEXT_WARNING_OF_DELETE; ?></td>
                  </tr>
                  <tr>
                    <td align="right" colspan="3" class="main"><br><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER, 'action=view_travels_attri&agency_id='.$_GET['agency_id'].'&option_page='.$_GET['option_page'].'&value_page=' . $value_page . '&attribute_page=' . $attribute_page, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', ' cancel '); ?></a>&nbsp;</td>
                  </tr>
<?php
} else {
?>
                  <tr>
                    <td class="main" colspan="3"><br><?php echo TEXT_OK_TO_DELETE; ?></td>
                  </tr>
                  <tr>
                    <td class="main" align="right" colspan="3"><br><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER, 'action=delete_option&option_id=' . $_GET['option_id'] . '&agency_id='.$_GET['agency_id'].'&option_page='.$_GET['option_page'], 'NONSSL') . '">'; ?><?php echo tep_image_button('button_delete.gif', ' delete '); ?></a>&nbsp;&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER, 'action=view_travels_attri&agency_id='.$_GET['agency_id'].'&option_page='.$_GET['option_page'].'&order_by=' . $order_by . '&page=' . $page, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', ' cancel '); ?></a>&nbsp;</td>
                  </tr>
<?php
}
?>
                </table></td>
              </tr>
<?php
	} else {
		if ($_GET['option_order_by']) {
			$option_order_by = $_GET['option_order_by'];
		} else {
			$option_order_by = 'products_options_id';
		}
?>
              <tr>
                <td colspan="3" class="pageHeading">&nbsp;<?php echo HEADING_TITLE_OPT. tep_get_travel_agency_name($_GET['agency_id']) ?> &nbsp;</td>
               <!-- <td align="right"><form name="option_order_by" action="<?php echo FILENAME_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER; ?>"><select name="selected" onChange="go_option()"><option value="products_options_id"<?php if ($option_order_by == 'products_options_id') { echo ' SELECTED'; } ?>><?php echo TEXT_OPTION_ID; ?></option><option value="products_options_name"<?php if ($option_order_by == 'products_options_name') { echo ' SELECTED'; } ?>><?php echo TEXT_OPTION_NAME; ?></option><option value="products_options_sort_order"<?php if ($option_order_by == 'products_options_sort_order') { echo ' SELECTED'; } ?>><?php echo TEXT_OPTION_SORTORDER; ?></option></select></form></td>-->
              </tr>
              <tr>
                <td colspan="3" class="smallText">
<?php

$agency_page_info_for_paging = 'action=view_travels_attri&agency_id='.$_GET['agency_id'];

$per_page = MAX_ROW_LISTS_OPTIONS;
$options = "select * from " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER . " patp where po.language_id = '" . $languages_id . "' and po.products_options_id = patp.products_options_id and patp.agency_id= '".$_GET['agency_id']."' order by po." . $option_order_by;
if (!$option_page) {
	$option_page = 1;
}
$prev_option_page = $option_page - 1;
$next_option_page = $option_page + 1;

$option_query = tep_db_query($options);

$option_page_start = ($per_page * $option_page) - $per_page;
$num_rows = tep_db_num_rows($option_query);

if ($num_rows <= $per_page) {
	$num_pages = 1;
} else if (($num_rows % $per_page) == 0) {
	$num_pages = ($num_rows / $per_page);
} else {
	$num_pages = ($num_rows / $per_page) + 1;
}
$num_pages = (int) $num_pages;

$options = $options . " LIMIT $option_page_start, $per_page";

// Previous
if ($prev_option_page)  {
	echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER, 'option_page=' . $prev_option_page.'&'.$agency_page_info_for_paging) . '"> &lt;&lt; </a> | ';
}

for ($i = 1; $i <= $num_pages; $i++) {
	if ($i != $option_page) {
		echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER, 'option_page=' . $i . '&'.$agency_page_info_for_paging) . '">' . $i . '</a> | ';
	} else {
		echo '<b><font color=red>' . $i . '</font></b> | ';
	}
}

// Next
if ($option_page != $num_pages) {
	echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER, 'option_page=' . $next_option_page.'&'.$agency_page_info_for_paging) . '"> &gt;&gt; </a>';
}
// WebMakers.com Added: Product Options Sort Order
?>
                </td>
              </tr>
              <tr>
                <td colspan="5"><?php echo tep_black_line(); ?></td>
              </tr>
              <tr class="dataTableHeadingRow">
			  <!--<td class="dataTableHeadingContent">&nbsp;<?php //echo TABLE_REM_FROM_PROVIDER; ?>&nbsp;</td>-->
                <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</td>
                <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_NAME; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="right">&nbsp;<?php echo TABLE_HEADING_OPTION_SORT_ORDER; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="5"><?php echo tep_black_line(); ?></td>
              </tr>
			  

		
<?php if (isset($_GET['action']) && $_GET['action']!='update_option')
{

	echo '<form name="delete_agency_attributes_form" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER, 'action=delete_agency_attributes&option_page=' . $option_page .'&agency_id=' . $_GET['agency_id'], 'NONSSL') . '" method="post">';
	?>		  


	 <?php
}
	  ?>		
			  
<?php

$agency_page_info_for_paging_all = 'agency_id='.$_GET['agency_id'].'&option_page='.$option_page;
$next_id = 1;
$options = tep_db_query($options);
while ($options_values = tep_db_fetch_array($options)) {
	$rows++;
?>
              <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
if (($_GET['action'] == 'update_option') && ($_GET['option_id'] == $options_values['products_options_id'])) {
	echo '<form name="option" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER, 'action=update_option_name&option_page='.$_GET['option_page'].'&agency_id=' . $_GET['agency_id'], 'NONSSL') . '" method="post">';
	$inputs = '';
	for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
		// WebMakers.com Added: Product Options Sort Order
		$option_name = tep_db_query("select products_options_name, products_options_sort_order from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . $options_values['products_options_id'] . "' and language_id = '" . $languages[$i]['id'] . "' order by products_options_sort_order");
		$option_name = tep_db_fetch_array($option_name);
		$inputs .= $languages[$i]['code'] . ':&nbsp;'.tep_draw_input_field('option_name[' . $languages[$i]['id'] . ']', $option_name['products_options_name'], 'size="20"').'&nbsp; Sort Order '.tep_draw_input_field('products_options_sort_order[' . $languages[$i]['id'] . ']', $option_name['products_options_sort_order'], 'size="3"').'<br>';
	}
?>
			<!--	<td align="center" class="smallText">&nbsp;&nbsp;</td>-->
                <td align="center" class="smallText">&nbsp;<?php echo $options_values['products_options_id']; ?>
				<input type="hidden" name="option_id" value="<?php echo $options_values['products_options_id']; ?>">&nbsp;</td>
                <td class="smallText" colspan="2"><?php echo $inputs; ?></td>
                <td align="center" class="smallText">&nbsp;<?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?>&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER,'action=view_travels_attri&'. $agency_page_info_for_paging_all, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', IMAGE_CANCEL); ?></a>&nbsp;</td>
<?php
echo '</form>' . "\n";
} else {
	// WebMakers.com Added: Product Options Sort Order
?>
	
	
	
               <!-- <td align="center" class="smallText"><?php //echo tep_draw_checkbox_field('delete_agency_attributes[]',$options_values["products_options_id"])?></td>-->
				<td align="center" class="smallText">&nbsp;<?php echo $options_values["products_options_id"]; ?>&nbsp;</td>
                <td class="smallText">&nbsp;
				<?php //echo $options_values["products_options_name"]; ?>
				
				<a href="<?php echo tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER, 'action=view_travels_attri&agency_id=' . $_GET['agency_id'].'&option_value_id='.$options_values["products_options_id"].'&option_page=' . $option_page.'#optionvalue', 'NONSSL') ?>" title="click here to view option value" ><?php echo $options_values["products_options_name"]; ?></a>&nbsp;
				</td>
                <td class="smallText" align="right">&nbsp;<?php echo $options_values["products_options_sort_order"]; ?>&nbsp;</td>
                <td align="center" class="smallText">&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER, 'action=update_option&agency_id='.$_GET['agency_id'].'&option_id=' . $options_values['products_options_id'] . '&option_order_by=' . $option_order_by . '&option_page=' . $option_page, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_edit.gif', IMAGE_UPDATE); ?></a>&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER, 'action=delete_product_option&agency_id='.$_GET['agency_id'].'&option_page='.$_GET['option_page'].'&option_id=' . $options_values['products_options_id'], 'NONSSL') , '">'; ?><?php echo tep_image_button('button_delete.gif', IMAGE_DELETE); ?></a>&nbsp;</td>
<?php
}
?>
              </tr>
			  
			 
			  
			  
<?php

}

$max_options_id_query = tep_db_query("select max(products_options_id) + 1 as next_id from " . TABLE_PRODUCTS_OPTIONS);
$max_options_id_values = tep_db_fetch_array($max_options_id_query);
$next_id = $max_options_id_values['next_id'];
?>

 
<?php if (isset($_GET['action']) && $_GET['action']!='update_option')
{
	?>		  <!--<tr>
			  <td align="center" class="smallText" ><?php echo tep_image_submit('button_update.gif', IMAGE_DELETE); ?></td>
			    <td colspan="5" align="center" class="smallText" >&nbsp;</td>
			  </tr>-->
	 </form>	  
	 <?php
}
	  ?>
              <tr>
                <td colspan="5"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
if ($_GET['action'] != 'update_option') {
?>
              <tr  class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
			  
			  
			  
<?php
// WebMakers.com Added: Product Options Sort Order
echo '<form name="options" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER, 'action=add_product_options&option_page=' . $option_page.'&agency_id=' . $_GET['agency_id'], 'NONSSL') . '" method="post"><input type="hidden" name="products_options_id" value="' . $next_id . '">';
$inputs = '';
for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
	$inputs .= $languages[$i]['code'] . ':&nbsp;'.tep_draw_input_field('option_name[' . $languages[$i]['id'] . ']','', 'size="20"').' Sort Order '.tep_draw_input_field('products_options_sort_order[' . $languages[$i]['id'] . ']','', 'size="3"').'&nbsp;<br>';
}
?>
  				<!--<td align="center" class="smallText" >&nbsp;</td>          -->    
				<td align="center" class="smallText" >&nbsp;<?php echo $next_id; ?>&nbsp;</td>
                <td class="smallText" colspan="2" ><?php echo $inputs; ?></td>
                <td align="center" class="smallText">&nbsp;<?php echo tep_image_submit('button_insert.gif', IMAGE_INSERT); ?>&nbsp;<a href="<?php echo tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER, '', 'NONSSL') ?>"><?php echo tep_image_button('button_back.gif', 'Back '); ?>
</a></td>
				
				 

<?php
echo '</form>';
?>
              </tr>
              <tr>
                <td colspan="5"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
}
	}
?>
            </table></td>
<!-- options eof //-->
            
          </tr>  
		 <tr>
		 	<td valign="top" width="50%">
		<!-- Options Value Bof //-->		

<?php			
if(isset($_GET['option_value_id']) && $_GET['option_value_id']!='')
{

	$agency_page_info_for_delete_value_paging = $agency_page_info_for_paging_all.'&value_page='.$_GET['value_page'].'&option_value_id='.$_GET['option_value_id'];
?>
<table width="85%" border="0" cellspacing="0" cellpadding="2" >
<!-- value //-->
<?php
if ($_GET['action'] == 'delete_option_value') { // delete product option value
	$values = tep_db_query("select * from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . $_GET['value_id'] . "' and language_id = '" . $languages_id . "'");
	$values_values = tep_db_fetch_array($values);
?>
              <tr>
                <td colspan="3" class="pageHeading">&nbsp;<?php echo $values_values['products_options_values_name']; ?>&nbsp;</td>
                <td>&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '1', '53'); ?>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td colspan="5"><?php echo tep_black_line(); ?></td>
                  </tr>
<?php

/*echo "select p.products_id, pd.products_name, po.products_options_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . $languages_id . "' and po.language_id = '" . $languages_id . "' and pa.products_id = p.products_id and pa.options_values_id='" . $_GET['value_id'] . "' and po.products_options_id = pa.options_id and p.agency_id='".$_GET['agency_id']."' order by pd.products_name, pa.products_options_sort_order, po.products_options_sort_order";*/

$products = tep_db_query("select p.products_id, p.products_model, pd.products_name, po.products_options_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . $languages_id . "' and po.language_id = '" . $languages_id . "' and pa.products_id = p.products_id and pa.options_values_id='" . $_GET['value_id'] . "' and po.products_options_id = pa.options_id and p.agency_id='".$_GET['agency_id']."' order by pd.products_name, pa.products_options_sort_order, po.products_options_sort_order");
if (tep_db_num_rows($products)) {
?>
                  <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</td>
					 <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_MODEL; ?>&nbsp;</td>
                    <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="right">&nbsp;<?php echo TABLE_HEADING_OPTION_SORT_ORDER; ?>&nbsp;</td>
                    <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_NAME; ?>&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="5"><?php echo tep_black_line(); ?></td>
                  </tr>
<?php
while ($products_values = tep_db_fetch_array($products)) {
	$rows++;
?>
                  <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
                    <td align="center" class="smallText">&nbsp;<?php echo $products_values['products_id']; ?>&nbsp;</td>
					<td class="smallText" align="center">&nbsp;<?php echo $products_values['products_model']; ?>&nbsp;</td>
                    <td class="smallText">&nbsp;<?php echo $products_values['products_name']; ?>&nbsp;</td>
                <td class="smallText" align="right">&nbsp;<?php echo $options_values["products_options_sort_order"]; ?>&nbsp;</td>
                    <td class="smallText">&nbsp;<?php echo $products_values['products_options_name']; ?>&nbsp;</td>
                  </tr>
<?php
}
?>
                  <tr>
                    <td colspan="5"><?php echo tep_black_line(); ?></td>
                  </tr>
                  <tr>
                    <td class="main" colspan="3"><br><?php echo TEXT_WARNING_OF_DELETE; ?></td>
                  </tr>
                  <tr>
                    <td class="main" align="right" colspan="3" ><br><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER, 'action=view_travels_attri&'.$agency_page_info_for_delete_value_paging . '&attribute_page=' . $attribute_page, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', ' cancel '); ?></a>&nbsp;</td>
                  </tr>
<?php
} else {
?>
                  <tr>
                    <td class="main" colspan="5"><br><?php echo TEXT_OK_TO_DELETE; ?></td>
                  </tr>
                  <tr>
                    <td class="main" align="right" colspan="5" ><br><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER, 'action=delete_value&value_id=' . $_GET['value_id'] . '&'.$agency_page_info_for_delete_value_paging, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_delete.gif', ' delete '); ?></a>&nbsp;&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER, 'action=view_travels_attri&'.$agency_page_info_for_delete_value_paging . '&attribute_page=' . $attribute_page, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', ' cancel '); ?></a>&nbsp;</td>
                  </tr>
<?php
}
?>
              	</table></td>
              </tr>
<?php
} else {
?>
              <tr>
                <td colspan="10" class="pageHeading" >&nbsp;<A NAME="optionvalue"  ></A><?php echo HEADING_TITLE_VAL. tep_get_products_options_name($_GET['option_value_id']); ?>&nbsp;</td>
                <td>&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '1', '36'); ?></td>
              </tr>
              <tr>
                <td colspan="11" class="smallText">
				
<?php

//echo $_GET[' '];

$products_options_info_for_paging = "action=view_travels_attri&".$agency_page_info_for_paging_all.'&option_value_id='.$_GET['option_value_id']."#optionvalue";

if(isset($_GET['option_value_id']))
{
	//	 $sub_query ="pov2po.products_options_id='".$_GET['option_value_id']."' and pov2po.agency_id='".$_GET['agency_id']."' and ";

	$sub_query ="and pavtp.products_options_id = '" . $_GET['option_value_id'] . "' and pavtp.agency_id='".$_GET['agency_id']."' ";
}
$per_page = MAX_ROW_LISTS_OPTIONS;
/*$values = "select pov.products_options_values_id, pov.products_options_values_name, pov2po.products_options_id from " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov left join " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " pov2po on pov.products_options_values_id = pov2po.products_options_values_id  where ".$sub_query."  pov.language_id = '" . $languages_id . "' order by pov.products_options_values_id";*/


$values = "select pov.products_options_values_id, pov.products_options_values_name, pov.def_price, pov.def_single, pov.def_double, pov.def_triple, pov.def_quadruple, pov.def_kids, pov.def_cost, pov.def_single_cost, pov.def_double_cost, pov.def_triple_cost, pov.def_quadruple_cost, pov.def_kids_cost, pavtp.products_options_id, pavtp.provider_option_value_id, p2p.products_options_values_to_products_options_id, pov.is_per_order_option from " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " p2p, " . TABLE_PRODUCTS_ATTRIBUTES_VALUES_TOUR_PROVIDER . " pavtp where pov.products_options_values_id = p2p.products_options_values_id  and p2p.products_options_id = pavtp.products_options_id ".$sub_query." and pavtp.products_options_values_id = p2p.products_options_values_id  and pov.language_id = '" . $languages_id . "' order by pov.products_options_values_id";

/*
$values = "select pov.products_options_values_id, pov.products_options_values_name, pov2po.products_options_id, pov2po.provider_option_value_id from " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov left join " . TABLE_PRODUCTS_ATTRIBUTES_VALUES_TOUR_PROVIDER . " pov2po on pov.products_options_values_id = pov2po.products_options_values_id  where ".$sub_query."  pov.language_id = '" . $languages_id . "' order by pov.products_options_values_id";
*/


if (!$value_page) {
	$value_page = 1;
}
$prev_value_page = $value_page - 1;
$next_value_page = $value_page + 1;

$value_query = tep_db_query($values);

$value_page_start = ($per_page * $value_page) - $per_page;
$num_rows = tep_db_num_rows($value_query);

if ($num_rows <= $per_page) {
	$num_pages = 1;
} else if (($num_rows % $per_page) == 0) {
	$num_pages = ($num_rows / $per_page);
} else {
	$num_pages = ($num_rows / $per_page) + 1;
}
$num_pages = (int) $num_pages;

$values = $values . " LIMIT $value_page_start, $per_page";

// Previous
if ($prev_value_page)  {
	echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER, 'option_order_by=' . $option_order_by . '&value_page=' . $prev_value_page.'&'.$products_options_info_for_paging) . '"> &lt;&lt; </a> | ';
}

for ($i = 1; $i <= $num_pages; $i++) {
	if ($i != $value_page) {
		echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER, 'option_order_by=' . $option_order_by . '&value_page=' . $i.'&'.$products_options_info_for_paging) . '">' . $i . '</a> | ';
	} else {
		echo '<b><font color=red>' . $i . '</font></b> | ';
	}
}

// Next
if ($value_page != $num_pages) {
	echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER, 'option_order_by=' . $option_order_by . '&value_page=' . $next_value_page.'&'.$products_options_info_for_paging) . '"> &gt;&gt;</a> ';
}
?>
                </td>
              </tr>
              <tr>
                <td colspan="11"><?php echo tep_black_line(); ?></td>
              </tr>
              <tr class="dataTableHeadingRow" >
                <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</td>
                <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_NAME; ?>&nbsp;</td>
                <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_VALUE; ?>&nbsp;</td>
                <td class="dataTableHeadingContent">&nbsp;<?php echo 'Apply '.TEXT_HEADING_PER_PERSON.'/'.TEXT_HEADING_PER_ORDER; ?>&nbsp;</td>
                <td class="dataTableHeadingContent">价格</td>
                <td class="dataTableHeadingContent">单人</td>
                <td class="dataTableHeadingContent">双人</td>
                <td class="dataTableHeadingContent">三人</td>
                <td class="dataTableHeadingContent">四人</td>
                <td class="dataTableHeadingContent">小孩</td>
                <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="11"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
$agency_page_info_for_value_paging = $agency_page_info_for_paging_all.'&value_page='.$value_page.'&option_value_id='.$_GET['option_value_id'];

$next_id = 1;
$values = tep_db_query($values);
while ($values_values = tep_db_fetch_array($values)) {
	$options_name = tep_options_name($values_values['products_options_id']);
	$values_name = $values_values['products_options_values_name'];
	$rows++;
?>
              <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php


if (($_GET['action'] == 'update_option_value') && ($_GET['value_id'] == $values_values['products_options_values_id'])) {
	echo '<form name="values" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER, 'action=update_value&'. $agency_page_info_for_value_paging, 'NONSSL') . '" method="post">';
	$inputs = '';
	for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
		$value_name = tep_db_query("select products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . $values_values['products_options_values_id'] . "' and language_id = '" . $languages[$i]['id'] . "'");
		$value_name = tep_db_fetch_array($value_name);
		$inputs .= $languages[$i]['code'] . ':&nbsp;'.tep_draw_input_field('value_name[' . $languages[$i]['id'] . ']', $value_name['products_options_values_name'], 'size="15"').'&nbsp;<br>';
	}
?>
                <td align="center" class="smallText">&nbsp;<?php echo $values_values['products_options_values_id']; ?><input type="hidden" name="value_id" value="<?php echo $values_values['products_options_values_id']; ?>">&nbsp;<input type="hidden" name="new_value_id" value="<?php echo $values_values['products_options_values_to_products_options_id']; ?>">&nbsp;
<input type="hidden" name="provider_option_value_id" 
value="<?php echo $values_values['provider_option_value_id']; ?>">&nbsp;<input type="hidden" name="agency_id" value="<?php echo $_GET['agency_id']; ?>">
				
			
				</td>
                <td align="center" class="smallText">&nbsp;<?php echo "\n"; ?><select name="option_id">
<?php
/*$options = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . $languages_id . "' order by products_options_sort_order, products_options_name");*/

$options = tep_db_query("select po.products_options_id, po.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER . " patp where po.language_id = '" . $languages_id . "' and po.products_options_id = patp.products_options_id and patp.agency_id= '".$_GET['agency_id']."' order by po.products_options_sort_order, po.products_options_name");

while ($options_values = tep_db_fetch_array($options)) {
	echo "\n" . '<option name="' . $options_values['products_options_name'] . '" value="' . $options_values['products_options_id'] . '"';
	if ($values_values['products_options_id'] == $options_values['products_options_id']) {
		echo ' selected';
	}
	echo '>' . $options_values['products_options_name'] . '</option>';
}
?>
                </select>&nbsp;</td>
                <td class="smallText"><?php echo $inputs; ?></td>
                <td class="smallText">
                <?php
                $products_option_value_per_array =array();
                $products_option_value_per_array[] = array('id' => '0', 'text' => TEXT_HEADING_PER_PERSON);
                $products_option_value_per_array[] = array('id' => '1', 'text' => TEXT_HEADING_PER_ORDER);
                echo tep_draw_pull_down_menu('is_per_order_option', $products_option_value_per_array,$values_values["is_per_order_option"]);
				?>
				</td>
                <td class="smallText"><?php echo '卖价：'.tep_draw_input_num_en_field('def_price', $values_values["def_price"], 'size="7"').'<br>成本：'.tep_draw_input_num_en_field('def_cost', $values_values["def_cost"], 'size="7"');?></td>
                <td class="smallText"><?php echo '卖价：'.tep_draw_input_num_en_field('def_single', $values_values["def_single"], 'size="7"').'<br>成本：'.tep_draw_input_num_en_field('def_single_cost', $values_values["def_single_cost"], 'size="7"');?></td>
                <td class="smallText"><?php echo'卖价：'. tep_draw_input_num_en_field('def_double', $values_values["def_double"], 'size="7"').'<br>成本：'.tep_draw_input_num_en_field('def_double_cost', $values_values["def_double_cost"], 'size="7"');?></td>
                <td class="smallText"><?php echo '卖价：'.tep_draw_input_num_en_field('def_triple', $values_values["def_triple"], 'size="7"').'<br>成本：'.tep_draw_input_num_en_field('def_triple_cost', $values_values["def_triple_cost"], 'size="7"');?></td>
                <td class="smallText"><?php echo '卖价：'.tep_draw_input_num_en_field('def_quadruple', $values_values["def_quadruple"], 'size="7"').'<br>成本：'.tep_draw_input_num_en_field('def_quadruple_cost', $values_values["def_quadruple_cost"], 'size="7"');?></td>
                <td class="smallText"><?php echo '卖价：'.tep_draw_input_num_en_field('def_kids', $values_values["def_kids"], 'size="7"').'<br>成本：'.tep_draw_input_num_en_field('def_kids_cost', $values_values["def_kids_cost"], 'size="7"');?></td>
                <td align="center" class="smallText">&nbsp;<?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?>&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER,'action=view_travels_attri&' . $agency_page_info_for_value_paging, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', IMAGE_CANCEL); ?></a>&nbsp;</td>
<?php
echo '</form>';
} else {
?>
                <td align="center" class="smallText">&nbsp;<?php echo $values_values["products_options_values_id"]; ?>&nbsp;</td>
                <td align="center" class="smallText">&nbsp;<?php echo $options_name; ?>&nbsp;</td>
                <td class="smallText">&nbsp;<?php echo $values_name; ?>&nbsp;</td>
                <td class="smallText">&nbsp;<?php echo ( ($values_values["is_per_order_option"] == 1) ? TEXT_HEADING_PER_ORDER : TEXT_HEADING_PER_PERSON); ?>&nbsp;</td>
				<td>卖价：<?php echo $values_values["def_price"]; ?><br>成本：<?php echo $values_values["def_cost"]; ?></td>
				<td>卖价：<?php echo $values_values["def_single"]; ?><br>成本：<?php echo $values_values["def_single_cost"]; ?></td>
				<td>卖价：<?php echo $values_values["def_double"]; ?><br>成本：<?php echo $values_values["def_double_cost"]; ?></td>
				<td>卖价：<?php echo $values_values["def_triple"]; ?><br>成本：<?php echo $values_values["def_triple_cost"]; ?></td>
				<td>卖价：<?php echo $values_values["def_quadruple"]; ?><br>成本：<?php echo $values_values["def_quadruple_cost"]; ?></td>
				<td>卖价：<?php echo $values_values["def_kids"]; ?><br>成本：<?php echo $values_values["def_kids_cost"]; ?></td>
                <td align="center" class="smallText">&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER, 'action=update_option_value&value_id=' . $values_values['products_options_values_id'] . '&'. $agency_page_info_for_value_paging, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_edit.gif', IMAGE_UPDATE); ?></a>&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER, 'action=delete_option_value&value_id=' . $values_values['products_options_values_id'].'&'. $agency_page_info_for_value_paging, 'NONSSL') , '">'; ?><?php echo tep_image_button('button_delete.gif', IMAGE_DELETE); ?></a>&nbsp;
				<button type="button" onClick="update_products_options_price(<?php echo $values_values['products_options_values_id'];?>);" title="只要点击此按扭就可以将此地接商的与此信息相关的产品属性价格统一更新">更新产品属性价格</button>
				</td>
<?php
}
/*$max_values_id_query = tep_db_query("select max(products_options_values_id) + 1 as next_id from " . TABLE_PRODUCTS_OPTIONS_VALUES);
$max_values_id_values = tep_db_fetch_array($max_values_id_query);
$next_id = $max_values_id_values['next_id'];*/
}
$max_values_id_query = tep_db_query("select max(products_options_values_id) + 1 as next_id from " . TABLE_PRODUCTS_OPTIONS_VALUES);
$max_values_id_values = tep_db_fetch_array($max_values_id_query);
$next_id = $max_values_id_values['next_id'];
?>
              </tr>
              <tr>
                <td colspan="11"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
if ($_GET['action'] != 'update_option_value') {
?>
              <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
echo '<form name="values" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER, 'action=add_product_option_values&'. $agency_page_info_for_value_paging , 'NONSSL') . '" method="post">';
?>

		
                <td align="center" class="smallText">&nbsp;<?php echo $next_id; ?>&nbsp;</td>
				
                <td align="center" class="smallText">&nbsp;<select name="option_id">
<?php



$options = tep_db_query("select po.products_options_id, po.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER . " patp where po.language_id = '" . $languages_id . "' and po.products_options_id = patp.products_options_id and patp.agency_id= '".$_GET['agency_id']."' order by po.products_options_sort_order, po.products_options_name");
/*
while ($options_values = tep_db_fetch_array($options)) {
echo '<option name="' . $options_values['products_options_name'] . '"  value="' . $options_values['products_options_id'] . '">' . $options_values['products_options_name'] . '</option>';
}
*/
while ($options_values = tep_db_fetch_array($options)) {
	echo "\n" . '<option name="' . $options_values['products_options_name'] . '" value="' . $options_values['products_options_id'] . '"';
	if ($_GET['option_value_id'] == $options_values['products_options_id']) {
		echo ' selected';
	}
	echo '>' . $options_values['products_options_name'] . '</option>';
}

$inputs = '';
for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
	$inputs .= $languages[$i]['code'] . ':&nbsp;'.tep_draw_input_field('value_name[' . $languages[$i]['id'] . ']', '', 'size="15"').'&nbsp;<br>';
}
?>
                </select>&nbsp;</td>
                <td class="smallText">
				
				<input type="hidden" name="value_id" value="<?php echo $next_id; ?>">
				<input type="hidden" name="agency_id" value="<?php echo $_GET['agency_id']; ?>">
				
				<?php echo $inputs; ?></td>
                <td class="smallText">
                 <?php
                 $products_option_value_per_array =array();
                 $products_option_value_per_array[] = array('id' => '0', 'text' => TEXT_HEADING_PER_PERSON);
                 $products_option_value_per_array[] = array('id' => '1', 'text' => TEXT_HEADING_PER_ORDER);
                 echo tep_draw_pull_down_menu('is_per_order_option', $products_option_value_per_array,$values_values["is_per_order_option"]);
				?>
                </td>
                <td class="smallText"><?php echo '卖价：'.tep_draw_input_num_en_field('def_price','', 'size="7"').'<br>成本：'.tep_draw_input_num_en_field('def_cost','', 'size="7"');?></td>
                <td class="smallText"><?php echo '卖价：'.tep_draw_input_num_en_field('def_single','', 'size="7"').'<br>成本：'.tep_draw_input_num_en_field('def_single_cost','', 'size="7"');?></td>
                <td class="smallText"><?php echo '卖价：'.tep_draw_input_num_en_field('def_double','', 'size="7"').'<br>成本：'.tep_draw_input_num_en_field('def_double_cost','', 'size="7"');?></td>
                <td class="smallText"><?php echo '卖价：'.tep_draw_input_num_en_field('def_triple','', 'size="7"').'<br>成本：'.tep_draw_input_num_en_field('def_triple_cost','', 'size="7"');?></td>
                <td class="smallText"><?php echo '卖价：'.tep_draw_input_num_en_field('def_quadruple','', 'size="7"').'<br>成本：'.tep_draw_input_num_en_field('def_quadruple_cost','', 'size="7"');?></td>
                <td class="smallText"><?php echo '卖价：'.tep_draw_input_num_en_field('def_kids','', 'size="7"').'<br>成本：'.tep_draw_input_num_en_field('def_kids_cost','', 'size="7"');?></td>
                <td align="center" class="smallText">&nbsp;<?php echo tep_image_submit('button_insert.gif', IMAGE_INSERT); ?>&nbsp;</td>
<?php
echo '</form>';
?>
              </tr>
              <tr>
                <td colspan="11"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
}
}
?>
            </table>
			
<?php }?>			
				
			<!-- Options Value Eof //-->		
			</td>
		 </tr> 
		

<?php } // end for product attributes code
else {

	function browse_information () {
		global $languages_id;
		$query="SELECT * FROM " . TABLE_TRAVEL_AGENCY . " WHERE languages_id=$languages_id ORDER BY agency_id";
		$daftar = mysql_db_query(DB_DATABASE, $query) or die("Information ERROR: ".mysql_error());$c=0;
		while ($buffer = mysql_fetch_array($daftar)) {$result[$c]=$buffer;$c++;}
		return $result;
	}
?>		  

<Tr>
	<td width="100%" align="left">
			
		<table width="50%" cellpadding="5" cellspacing="5"> 
				
<tr class=pageHeading><td><?php echo MANAGER_INFORMATION ?></td></tr>
<tr><td>
<table border="0" width="100%"  cellpadding="5" cellspacing="1">
<tr class="dataTableHeadingRow">
	<td align=center class="dataTableHeadingContent"><?php echo NO_INFORMATION;?></td>
	<td align="left" class="dataTableHeadingContent">
<?php  echo HEADING_TITLE_TRAVEL ?>
</td>
</tr>
<?
$data=browse_information();
$no=1;
if (sizeof($data) > 0) {
	while (list($key, $val)=each($data)) {


		/*
		$query= tep_db_query("select * from products_options");
		while($query_rs = tep_db_fetch_array($query))
		{
		tep_db_query("insert into products_attributes_tour_provider (agency_id, products_options_id) values('".$val[agency_id]."','".$query_rs[products_options_id]."')");
		//echo "select pov2po.products_options_values_id from products_options_values_to_products_options pov2po, products_options_values patp where patp.products_options_values_id=pov2po.products_options_values_id  and pov2po.products_options_id='".$query_rs[products_options_id]."' ";

		$query1= tep_db_query("select pov2po.products_options_values_id,patp.language_id  from products_options_values_to_products_options pov2po, products_options_values patp where patp.products_options_values_id=pov2po.products_options_values_id  and pov2po.products_options_id='".$query_rs[products_options_id]."' ");
		while($query_rs1 = tep_db_fetch_array($query1))
		{
		tep_db_query("insert into products_attributes_values_tour_provider (agency_id, products_options_id,products_options_values_id,language_id) values('".$val[agency_id]."','".$query_rs[products_options_id]."','".$query_rs1['products_options_values_id']."','".$query_rs1['language_id']."')");
		}

		}
		*/

		$no % 2 ? $bgcolor="#DEE4E8" : $bgcolor="#F0F1F1";
?>
   <tr bgcolor="<?php echo $bgcolor?>">

    <td align="right" class="dataTableContent"><?php echo  $val[agency_id];?></td>
    <td align="left" class="dataTableContent">
	<a href="<?php echo tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER, '&action=view_travels_attri&agency_id=' . $val[agency_id], 'NONSSL') ?>">
	<?php echo $val[agency_name];?>
	</a>
	</td>
   </tr>
<?php $no++;
  }} else {?>
   <tr bgcolor="#DEE4E8">
    <td colspan=6><?php echo ALERT_INFORMATION;?></td>
   </tr>
<?php }?>
</table>
</td></tr>
		</table>	
			
	
	</td>
</Tr>
<?php }?>		  
		     
        </table></td>
<!-- option value eof //-->
      </tr>
 <!-- </tr>-->
</table>
	<?php
	if($products_attributes_permissions['能看不能编'] == true){
		$ob_html = ob_get_clean();
		$ob_html = preg_replace('/\<[[:space:]]*form[[:space:]]*/is','<XXXform ',$ob_html);
		$ob_html = preg_replace('/\<[[:space:]]*\/[[:space:]]*form[[:space:]]*\>/is','</XXXform>',$ob_html);
		$ob_html = preg_replace('/\<[[:space:]]*input[[:space:]]*/is','<input disabled="disabled" ',$ob_html);
		$ob_html = preg_replace('/\<[[:space:]]*button[[:space:]]*/is','<button disabled="disabled" ',$ob_html);
		//去掉删除功能
		$ob_html = str_replace('action=delete','action=notDelete',$ob_html);
		echo $ob_html;
	}
	?>

</td>
</tr>
<Tr><td  height="30px" colspan="5">&nbsp;</td></Tr>
<!-- body_text_eof //-->
</table>
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
<script>
/*  this function is to change option value according to the Product option in product attributes  */
function changevalue()
{
	window.location = "products_attributes.php?optionvalue="+document.attributes.options_id.value;
}

/*  this function is to change option value according to the Product option in product attributes while updating */
function changevalueupdate()
{
	window.location = "products_attributes.php?optionvalue="+document.attributes.options_id.value+"&action=<?php echo $_GET['action'] ?>&attribute_id=<?php echo $_GET['attribute_id'] ?>&attribute_page=<?php echo $_GET['attribute_page'] ?>";
}
//用预设的产品选项属性成本和价格更新具体的产品属性成本和价格
function update_products_options_price(options_values_id){
	if(parseInt(options_values_id) < 1) return false;
	if(confirm('此操作会覆盖与此属性相关的具体产品属性价格，您真的确定执行此操作？一切风险由您自己承担！')){
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('products_attributes_tour_provider.php')) ?>");
		jQuery.post(url,{'action':"update_products_options_price", 'ajax':"true", 'options_values_id':options_values_id },function(text){
			if(text=='ok'){
				alert('操作成功！');
			}
		},'text');
	}
}
</script>