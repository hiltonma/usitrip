<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");  
header("Content-type: text/html; charset=big5");  
require('includes/application_top.php');
// 备注添加删除
if($_GET['ajax']=="true"){
	include DIR_FS_CLASSES . 'Remark.class.php';
	$remark = new Remark('stats_sales_by_category_tree');
	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
}
?>

  <script type="text/javascript">
		function close_div(divid,displaytype){
				document.getElementById(divid).style.display=displaytype;
		}
  </script>
  <?php
  if(isset($HTTP_GET_VARS['action_view_order_detail']) && $HTTP_GET_VARS['action_view_order_detail']=='true')
  { 
	//$orders_query = "select o.orders_id, op.products_id, op.products_name, op.products_model, op.final_price_cost as gross_cost, op.final_price as gross_price FROM " . TABLE_ORDERS . " as o, " . TABLE_ORDERS_PRODUCTS . " as op where o.orders_id = op.orders_id and o.date_purchased >= '".$HTTP_GET_VARS['where_date']."' and op.products_id in (select products_id from ".TABLE_PRODUCTS_TO_CATEGORIES." where categories_id='".$HTTP_GET_VARS['categories_id']."') group by o.orders_id";
	
	/*$sortorder = '';
	if($_GET["sort"] == 'quantitysum') {
	   if($_GET["order"] == 'ascending') {
			$sortorder .= 'quantitysum asc ';
	  } else {
			$sortorder .= 'quantitysum desc ';
	  }
	}else{
		$sortorder .= 'op.products_id';
	}*/
	if(isset($_GET['tour_pack_filter']) && $_GET['tour_pack_filter'] != '')
	  {
		$extra_where_cond = " and p.products_vacation_package='".$_GET['tour_pack_filter']."' ";
	  }
			  
	$orders_query = "select DISTINCT(op.products_id), p2c.categories_id, op.products_model, p.products_id, cd.categories_name,  op.products_name,sum(op.products_quantity) as quantitysum, sum(op.final_price_cost) as gross_cost, sum(op.final_price) as gross_price, o.orders_id FROM " . TABLE_ORDERS . " as o, " . TABLE_ORDERS_PRODUCTS . " AS op, " . TABLE_PRODUCTS . " as p, ".TABLE_PRODUCTS_TO_CATEGORIES." as p2c, ".TABLE_CATEGORIES_DESCRIPTION." as cd WHERE o.orders_id = op.orders_id and op.products_id = p.products_id and p2c.products_id = op.products_id and p2c.products_id = p.products_id and p2c.categories_id=cd.categories_id ".$extra_where_cond." and p2c.categories_id in (select min(categories_id) from ".TABLE_PRODUCTS_TO_CATEGORIES." where products_id=op.products_id group by products_id) and p2c.categories_id = '".$HTTP_GET_VARS['categories_id']."' and cd.language_id = '".$languages_id."' and o.date_purchased >= '".$HTTP_GET_VARS['where_date']."' group by op.products_id order by op.products_id";//group by p2c.categories_id 

	$products_query_raw = tep_db_query($orders_query);
	if(tep_db_num_rows($products_query_raw)>0){
		?>
		<table width="700" cellpadding="2" style="border:1px solid #006666" bgcolor="#DDEEEB" cellspacing="2" class="dataTableContent">
			<tr style="background-color:#006666; color:#FFFFFF;">
				<?php /*<td width="50"><strong>Order#</strong></td>*/?>
				<td width="200"><strong>Tours</strong></td>
				<td width="50" align="right"><strong>Sales</strong>
				<?php //echo '<a href="' . tep_href_link(FILENAME_STATS_SALES_BY_CATEGORY_TREE, tep_get_all_get_params(array('page','sort','order')).'sort=quantitysum&order=ascending', 'NONSSL').'"><img src="images/arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_STATS_SALES_BY_CATEGORY_TREE, tep_get_all_get_params(array('page','sort','order')).'sort=quantitysum&order=decending', 'NONSSL').'"><img src="images/arrow_down.gif" border="0"></a>&nbsp;'; ?>
				</td>
				<td align="right" width="100" nowrap><strong>Revenue</strong></td>
				<?php if($access_full_edit == 'true') { ?>	
				<td align="right" width="100" nowrap><strong>Cost</strong></td>
				<td align="right" width="100" nowrap><strong>Gross Profit</strong></td>
				<td align="right" width="100" nowrap valign="top"><strong style="line-height:10px; vertical-align:text-top ">Gross Profit(%)</strong></td><td align="right" width="15"><?php echo tep_image(DIR_WS_IMAGES.'close_window.jpg','close',15,13,' onclick="javascript:close_div(\'responsediv'.$HTTP_GET_VARS['categories_id'].'\',\'none\');"'); ?></td>
				<?php } ?>
			</tr>
			<?php
			$total_gross_price = 0.00;
			$total_gross_cost = 0.00;
			while($row_products = tep_db_fetch_array($products_query_raw)){
				$gross_profit = sprintf('%01.2f', ($row_products['gross_price'] - $row_products['gross_cost']));
				if($row_products['gross_price'] != 0){	
					$margin = tep_round((((($row_products['gross_price'])-($row_products['gross_cost']))/($row_products['gross_price']))*100), 0);
				}else{
					$margin = 0;
				}
				
				$total_gross_price = $total_gross_price + $row_products['gross_price'];
				$total_gross_cost = $total_gross_cost + $row_products['gross_cost'];
			?>
			<tr>
				<?php //echo '<td><a href="edit_orders.php?action=edit&oID='.$row_products['orders_id'].'" target="_blank"><strong>'.$row_products['orders_id'].'</strong></a></td>'; ?>
				<td width="50%"><?php echo '<a target="_blank" href="' . tep_catalog_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $row_products['products_id']) . '">'.$row_products['products_name'].'</a>'; ?> <?php echo '[<a target="_blank" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . tep_get_products_catagory_id($row_products['products_id']) . '&pID=' . $row_products['products_id'].'&action=new_product') . '">'.$row_products['products_model'].'</a>]'; ?></td>
				<td align="right"><?php echo $row_products['quantitysum']; ?></td>
				<td align="right">$<?php echo number_format($row_products['gross_price'],2); ?></td>
				<?php if($access_full_edit == 'true') { ?>	
				<td align="right">$<?php echo number_format($row_products['gross_cost'],2); ?></td>
				<td align="right">$<?php echo $gross_profit; ?></td>
				<td align="right" colspan="2"><?php echo $margin . '%';?></td>
				<?php } ?>
			</tr>				
			<?php } ?>
			
			<tr style="background-color:#006666; color:#FFFFFF;">
				<td colspan="2" align="right"><b>Total:</b></td>
				<td align="right"><b>$<?php echo number_format($total_gross_price,2); ?></b></td>
				<?php if($access_full_edit == 'true') { ?>	
				<td align="right"><b>$<?php echo number_format($total_gross_cost,2); ?></b></td>
				<?php
				$total_gross_profit = sprintf('%01.2f', ($total_gross_price - $total_gross_cost));
				?>
				<td align="right">$<?php echo $total_gross_profit; ?></td>
				<td align="right" colspan="2"><b>
					<?php 
						if($total_gross_price != 0 ){
						$margin = tep_round(((($total_gross_price-$total_gross_cost)/($total_gross_price))*100), 0);
						}else{
						$margin = '0';
						}
						echo $margin . '%';
					?></b>
				</td>
				<?php } ?>
			</tr>
			</table>
			
		<?php
	}
	echo '|!!!!!|'.$HTTP_GET_VARS['categories_id'];
	exit;
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />

<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>

<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css" />
<script type="text/javascript" src="includes/javascript/spiffyCal/spiffyCal_v2_1_2008_04_01-min.js"></script>
<div id="spiffycalendar" class="text"></div>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('stats_sales_by_category_tree');
$list = $listrs->showRemark();
?>
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top">
			<table width="99%" cellpadding="2" cellspacing="0" border="0">
				<?php  echo tep_draw_form('search', FILENAME_STATS_SALES_BY_CATEGORY_TREE, '', 'get'); ?>
				<?php /*?><tr>
							<td class="smallText" width="10%" ><?php echo TABLE_HEADING_CATEGORY_SELECT; ?>:</td>
							<td class="smallText" align="left">
							<?php
							 echo tep_draw_hidden_field('action', 'view_report');
							 echo tep_draw_pull_down_menu('categories_id', tep_get_category_tree(), $current_category_id); //onChange="this.form.submit();" ?>
							
							</td>
				</tr><?php */?>
				<tr>
				<?php
				if(!isset($HTTP_GET_VARS['action']) && !isset($HTTP_GET_VARS['order_date_purchased_from']) && !isset($HTTP_GET_VARS['order_date_purchased_to'])){		
	  $_GET['order_date_purchased_from'] = $HTTP_GET_VARS['order_date_purchased_from'] = date('m/01/Y');
	}
				?>
				<script type="text/javascript">
//var order_date_purchased_start = new ctlSpiffyCalendarBox("order_date_purchased_start", "search", "order_date_purchased_from","btnDate3","<?php echo tep_get_date_disp($_GET['order_date_purchased_from']); ?>",scBTNMODE_CUSTOMBLUE);
//var order_date_purchased_end = new ctlSpiffyCalendarBox("order_date_purchased_end", "search", "order_date_purchased_to","btnDate4","<?php echo tep_get_date_disp($_GET['order_date_purchased_to']); ?>",scBTNMODE_CUSTOMBLUE);
</script>
				<?php //echo tep_draw_form('search', FILENAME_TOUR_GROSS_PROFIT_REPORT, '', 'get'); ?>
					<td class="smallText" width="10%" >Order Date Start</td>
					<td class="smallText" >From:
					<?php echo tep_draw_input_field('order_date_purchased_from', tep_get_date_disp($_GET['order_date_purchased_from']), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1);GeCalendar.OutPutType=\'m/d/y\'; GeCalendar.SetDate(this);"');?>
					<script type="text/javascript">//order_date_purchased_start.writeControl(); order_date_purchased_start.dateFormat="MM/dd/yyyy";</script> To:
					<?php echo tep_draw_input_field('order_date_purchased_to', tep_get_date_disp($_GET['order_date_purchased_to']), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1);GeCalendar.OutPutType=\'m/d/y\'; GeCalendar.SetDate(this);"');?>
						<script type="text/javascript">//order_date_purchased_end.writeControl(); order_date_purchased_end.dateFormat="MM/dd/yyyy";</script>
					</td>
				</tr>
				<tr>
					<td class="smallText" nowrap="nowrap" ><?php echo 'Filter By Tour/Package'; ?>:</td>
					<td class="smallText" align="left">
					<?php
					$tour_package_filetr_array = array(array('id' => '', 'text' => 'All'));
					$tour_package_filetr_array[] = array('id' => '0', 'text' => 'Tours');
					$tour_package_filetr_array[] = array('id' => '1', 'text' => 'Package');
					?>
					<?php echo tep_draw_pull_down_menu('tour_pack_filter', $tour_package_filetr_array, $_GET['tour_pack_filter'], 'style="width:200px; "'); //onChange="this.form.submit();" ?>
					
					</td>
				</tr>	
				<tr>
					<td></td><td>&nbsp;<?php echo tep_image_submit('button_search.gif', IMAGE_SEARCH).'&nbsp;'; ?></td>
				</tr>
				<?php echo '</form>'; ?>
			</table>			
			
			<?php   
			
			/*if($HTTP_GET_VARS['order_date_purchased_from']>date("m/d/Y")){
				$_GET['order_date_purchased_from'] = $HTTP_GET_VARS['order_date_purchased_from'] = date('m/01/Y');
			}*/
			$where_date .= " and (o.orders_status <> 6 and o.orders_status <> 100060 and o.orders_status <> 100055) ";			
			 if(isset($_GET['tour_pack_filter']) && $_GET['tour_pack_filter'] != '')
			  {
				$where_date .= " and p.products_vacation_package='".$_GET['tour_pack_filter']."'";
			  }
			if ((isset($HTTP_GET_VARS['order_date_purchased_from']) && tep_not_null($HTTP_GET_VARS['order_date_purchased_from'])) && (isset($HTTP_GET_VARS['order_date_purchased_to']) && tep_not_null($HTTP_GET_VARS['order_date_purchased_to'])) ) 
						  {
						  
						  $make_start_date = tep_get_date_db($HTTP_GET_VARS['order_date_purchased_from']). " 00:00:00";					 
						  $make_end_date = tep_get_date_db($HTTP_GET_VARS['order_date_purchased_to']). " 23:59:59";
						  $where_date .= " and  o.date_purchased >= '" . $make_start_date . "' and o.date_purchased <= '" . $make_end_date . "' ";
						  
						  }
						  else if ((isset($HTTP_GET_VARS['order_date_purchased_from']) && tep_not_null($HTTP_GET_VARS['order_date_purchased_from'])) && (!isset($HTTP_GET_VARS['order_date_purchased_to']) or !tep_not_null($HTTP_GET_VARS['order_date_purchased_to'])) ) {
							
						  $make_start_date = tep_get_date_db($HTTP_GET_VARS['order_date_purchased_from']) . " 00:00:00";
						  
						  $where_date .= " and  o.date_purchased >= '" . $make_start_date . "' ";
						  
						  
						  }
						  else if ((!isset($HTTP_GET_VARS['order_date_purchased_from']) or !tep_not_null($HTTP_GET_VARS['order_date_purchased_from'])) && (isset($HTTP_GET_VARS['order_date_purchased_to']) && tep_not_null($HTTP_GET_VARS['order_date_purchased_to'])) ) {
							
						 $make_end_date = tep_get_date_db($HTTP_GET_VARS['order_date_purchased_to']) . " 23:59:59";
						  
						 $where_date .= " and  o.date_purchased <= '" . $make_end_date . "' ";
						 
						 }
						 
$products_query_raw = "select count(DISTINCT(op.products_id)) as prod_count, p2c.categories_id, p.products_id, cd.categories_name,  op.products_name,sum(op.products_quantity) as quantitysum, sum(op.final_price_cost*op.products_quantity) as gross_cost, sum(op.final_price*op.products_quantity) as gross_price, o.orders_id FROM " . TABLE_ORDERS . " as o, " . TABLE_ORDERS_PRODUCTS . " AS op, " . TABLE_PRODUCTS . " as p, ".TABLE_PRODUCTS_TO_CATEGORIES." as p2c, ".TABLE_CATEGORIES_DESCRIPTION." as cd WHERE o.orders_id = op.orders_id and op.products_id = p.products_id and p2c.products_id = op.products_id and p2c.products_id = p.products_id and p2c.categories_id=cd.categories_id and p2c.categories_id in (select min(categories_id) from ".TABLE_PRODUCTS_TO_CATEGORIES." where products_id=op.products_id group by products_id) and cd.language_id = '".$languages_id."' ".$where_date." group by p2c.categories_id order by op.products_id";


$products_query = tep_db_query($products_query_raw);
if(tep_db_num_rows($products_query)>0){
	while($products = tep_db_fetch_array($products_query)){
		/*$existing_cat_ids[] = array('id' => $products['categories_id'],
								   'text' => $products['quantitysum']);*/
		   $existing_cat_ids[] = $products['categories_id'];
		   
		   if($products['gross_price'] != 0){	
				$margin = tep_round((((($products['gross_price'])-($products['gross_cost']))/($products['gross_price']))*100), 0);
			}else{
				$margin = 0;
			}

		   if($products['quantitysum']>0){
		   	$existing_cat_ids_count[$products['categories_id']] = '[Sales: '.$products['quantitysum'].'&nbsp;&nbsp;Revenue: $'.sprintf('%01.2f', $products['gross_price']).'&nbsp;&nbsp;Cost: $'.sprintf('%01.2f', $products['gross_cost']).'&nbsp;&nbsp;Margin: '.$margin.'%]';//$products['quantitysum'];
		   } else {
		   	$existing_cat_ids_count[$products['categories_id']] = '';//$products['quantitysum'];
		   }
		   //echo $products['products_id'].'-';
	}
}



	 ?>
				<table width="100%" align="center" cellpadding="1" cellspacing="3" border="0">
				  <tr>
				  
				  <td class="dataTableContent"><b>Categories</b></td>
				  
				  </tr>
				  <tr>
										
										<td>
<?php


  /*$info_box_contents[] = array('align' => 'left',
                               'text'  => BOX_HEADING_CATEGORIES
                              );*/
 // new infoBoxHeading($info_box_contents, true, false);

    $number_top_levels = 0;
    $categories_string='';
	$order_date_purchased_from = tep_get_date_db($_GET['order_date_purchased_from']);
    $number_top_levels = build_menus(0,'','',0,$existing_cat_ids,$existing_cat_ids_count,$order_date_purchased_from);

	if($HTTP_GET_VARS['cat_id'] > 0){
    $currentCPath = $HTTP_GET_VARS['cat_id'];
	}
    if (! isset($currentCPath)) {
        if (isset($HTTP_GET_VARS['products_id'])) {
            $cPathQuery = tep_db_query("select c.categories_id, c.parent_id  from " . TABLE_CATEGORIES . " c, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p2c.products_id = '" . $HTTP_GET_VARS['products_id'] . "' and c.categories_id = p2c.categories_id");
            if ($cp = tep_db_fetch_array($cPathQuery))  {
                $currentCPath = $cp['parent_id'] . "_" . $cp['categories_id'];
            }
        }
    }

    echo "<SCRIPT LANGUAGE=\"JavaScript\" SRC=\"includes/javascript/jscooktree/JSCookTree.js\"></SCRIPT>\n";
    echo "<LINK REL=\"stylesheet\" HREF=\"includes/javascript/jscooktree/ThemeXP/theme.css\" TYPE=\"text/css\">\n";
    echo "<SCRIPT LANGUAGE=\"JavaScript\" SRC=\"includes/javascript/jscooktree/ThemeXP/theme.js\"></SCRIPT>\n";

?>
<?php

   $tabletext .= "<div id=\"myID\"></div>\n";
   $tabletext .= "<script type=\"text/javascript\"><!--
        var catTree =
        [\n";
   $tabletext .= $categories_string;
   $tabletext .= "];
     --></script>\n";

    /*$info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => $tabletext);
    new infoBox($info_box_contents);*/
	
	print_r ($tabletext);

    echo "<script type=\"text/javascript\"><!--\n";
    echo "    var treeIndex = ctDraw ('myID', catTree, ctThemeXP1, 'ThemeXP', 0, 0);\n";
    //if (isset($currentCPath)) {
     if(is_array($existing_cat_ids)){
		 foreach($existing_cat_ids as $key=>$val)   {
	
			echo "    var treeItem = ctExposeItem (0, '" . $val . "'); \n";
			echo "    ctOpenFolder (treeItem);\n";
		}
    }
	//}
    echo "--></script>\n";

?>
           </td>
		   		
		  </tr>
		   </table>
			</td>
          </tr>
          
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->
<?php
function build_menus($currentParID,$menustr,$catstr, $indent,$existing_cat_ids,$existing_cat_ids_count,$order_date_purchased_from) {

//echo $currentCPath;
    global $categories_string, $id, $languages_id;
    $tmpCount;

    $tmpCount = 0;
    $haschildren = 0; //default

    $categories_query_catmenu = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . $currentParID . "' and c.categories_id = cd.categories_id and cd.language_id='" . $languages_id ."' order by sort_order, cd.categories_name");
    $numberOfRows = tep_db_num_rows($categories_query_catmenu);
    $currentRow = 0;
    while ($categories = tep_db_fetch_array($categories_query_catmenu))  {
        $currentRow ++;
        $catName = addslashes($categories['categories_name']);
        $tmpCount += 1;
        $haschildren = tep_has_category_subcategories($categories['categories_id']);

        if (SHOW_COUNTS == 'true') {
            $products_in_category = tep_count_products_in_category($categories['categories_id']);
            if ($products_in_category > 0) {
                $catName .= ' (' . $products_in_category . ')';
            }
        }

        if($catstr != ''){
            $cPath_new = 'cPath=' . $catstr . '_' . $categories['categories_id'];
        } else {
            $indent = 0;
            $cPath_new = 'cPath=' . $categories['categories_id'];
        }

        if($menustr != ''){
            $menu_tmp = $menustr . '_' . $tmpCount;
        } else {
            $menu_tmp = $tmpCount;
        }

        $indentStr="";
        for($i=0; $i<$indent; $i++) {
            $indentStr .= "   ";
        }
	$count = '';
	if(is_array($existing_cat_ids)){
		if(in_array($categories['categories_id'],$existing_cat_ids)){
			//$orders_query = "select o.orders_id, op.products_id, op.products_name, op.products_model, op.final_price_cost as gross_cost, op.final_price as gross_price FROM " . TABLE_ORDERS . " as o, " . TABLE_ORDERS_PRODUCTS . " as op where o.orders_id = op.orders_id and o.date_purchased >= '".$order_date_purchased_from."' and op.products_id in (select products_id from ".TABLE_PRODUCTS_TO_CATEGORIES." where categories_id='".$categories['categories_id']."') group by o.orders_id";
			//$count = ' <b>('.tep_db_num_rows(tep_db_query($orders_query)).')</b>';
			$count = ' <b>'. $existing_cat_ids_count[$categories['categories_id']].'</b>';
		}
	}
	else
	{
		$count = '';
	}


	if ($haschildren) {		
         if(is_array($existing_cat_ids_count) && ($existing_cat_ids_count[$categories['categories_id']]!='')){
		  $categories_string .=  $indentStr . "['<input type=image src=\"images/icon_info.gif\" name=cat_check_array[] value=\"".$categories['categories_id']."\" onClick=\"ajax_view_order(this.value,\'".$order_date_purchased_from."\');\">', '" . $catName . $count . "','".$categories['categories_id']."','_self','". $order_date_purchased_from ."'";	
		 }else{
		  $categories_string .=  $indentStr . "[null, '" . $catName . $count . "','".$categories['categories_id']."','_self','". $tmpString ."'";	
		 }
		 //$categories_string .=  $indentStr . "[null, '" . $catName . " <b>" . $count ."<b>','".$categories['categories_id']."','_self','onChange=\"ajax_view_order('".$select_check_order_product_payment_history['ord_prod_payment_id']."',this.value);\"'";
	}else{
         if(is_array($existing_cat_ids_count) && ($existing_cat_ids_count[$categories['categories_id']]!='')){
		  $categories_string .=  $indentStr . "['<input type=image src=\"images/icon_info.gif\" name=cat_check_array[] value=\"".$categories['categories_id']."\" onClick=\"ajax_view_order(this.value,\'".$order_date_purchased_from."\');\">', '" . $catName . $count . "','".$categories['categories_id']."','_self','". $order_date_purchased_from ."'";	
		 }else{
		  $categories_string .=  $indentStr . "[null, '" . $catName . $count . "','".$categories['categories_id']."','_self','". $tmpString ."'";	
		 }
			
	}
	//$categories_string .= '<div id="responsediv'.$categories['categories_id'].'"> here response	</div>';
        if ($haschildren) {
            $indent += 1;
            $categories_string .= ",\n";
			
            if($menustr != ''){
                $menu_tmp = $menustr . '_' . $tmpCount;
            } else {
                $menu_tmp = $tmpCount;
            }
            if($catstr != ''){
                $cat_tmp = $catstr . '_' . $categories['categories_id'];
            } else {
                $cat_tmp = $categories['categories_id'];
            }
            $NumChildren = build_menus($categories['categories_id'], $menu_tmp, $cat_tmp, $indent,$existing_cat_ids,$existing_cat_ids_count,$order_date_purchased_from);
            if ($currentRow < $numberOfRows) {
                $categories_string .= $indentStr . "],\n";
            } else {
                $categories_string .= $indentStr . "]\n";
            }
        } else {
            if ($currentRow < $numberOfRows) {
                $categories_string .= "],\n";
            } else {
                $categories_string .= "]\n";
            }
            $NumChildren = 0;
        }
		
    }
    return $tmpCount;
}

?>

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
		
				function ajax_view_order(categories_id,where_date)
				{
				close_div('responsediv'+categories_id,'inline');
				try{
							<?php if(isset($_GET['tour_pack_filter']) && $_GET['tour_pack_filter'] != '') { ?>
							http1.open('get', 'stats_sales_by_category_tree.php?categories_id='+categories_id+'&where_date='+where_date+'&tour_pack_filter=<?php echo $_GET['tour_pack_filter'];?>&action_view_order_detail=true');
							<?php }else{?>
							http1.open('get', 'stats_sales_by_category_tree.php?categories_id='+categories_id+'&where_date='+where_date+'&action_view_order_detail=true');
							<?php } ?>
							http1.onreadystatechange = hendleInfo_add_comment;
							http1.send(null);
					}catch(e){ 
						//alert(e);
					}
				}
				
				function hendleInfo_add_comment()
					{
						if(http1.readyState == 4)
						{
						 var response1 = http1.responseText;
						 var responsecomment = response1.split("|!!!!!|");
						 document.getElementById("responsediv"+responsecomment[1]).innerHTML = responsecomment[0];
						 
						}
					}
					
</script>		
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>