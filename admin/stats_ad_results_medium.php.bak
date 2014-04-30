<?php
// die("此页严重影响了服务器的稳定性，已经被禁止访问！");

/*
  $Id: stats_ad_results.php, v 2.3 2006/03/22
  
  Date range, sorting and number of sales added
  by mr_absinthe,  www.originalabsinthe.com

  osCommerce, Open Source E-Commerce Solutions

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  // 备注添加删除
  if($_GET['ajax']=="true"){
  	include DIR_FS_CLASSES . 'Remark.class.php';
  	$remark = new Remark('stats_ad_results_medium');
  	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
  }
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
  if($start_date!='' && $end_date != '' ){
  	$extrawhere = " AND  clicks_date BETWEEN '" . $start_date . "' AND '" . $end_date . " 23:59:59'  ";
  	$extrawhere_total = " AND  date_purchased BETWEEN '" . $start_date . "' AND '" . $end_date . " 23:59:59'  ";
  }
  $extragroupby = '  group by utm_medium';
  switch($_GET['ajax']){
  	case 'one' :?><table border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#FFFAF3">
				
				<?php 
				$detailsand = " and utm_medium='".tep_db_input($_GET['value'])."' ";
				$ad_query_details_raw = "select *,count(*) as source_count from ".TABLE_AD_SOURCE_CLICKS_STORES." where utm_medium != ''  ".$extrawhere." ".$detailsand." group by customers_advertiser  "  ;
				
				$medium_rows = 0;
				$ad_query_details = tep_db_query($ad_query_details_raw);
  
				   $total_cnt_details = tep_db_num_rows($ad_query_details) ;
				   
				   if($total_cnt_details == 0){
				  
				  echo '<tr><td height="16" class="dataTableContent" colspan="4">No Source found.'.$ad_query_details_raw.'</td></tr>';
				  
				   }else {
				
				?>
				
				  <?php   
				  $rows=$_GET['key'];
						  while ($ads_details = tep_db_fetch_array($ad_query_details)) {
						  $medium_rows++;
						 
							?>
						  <tr>
						    <td class="dataTableContent" width="30%" height="16">
						 	<DIV id=cnt_<?=$medium_rows.'_'.$rows;?> ><span>&nbsp;&nbsp;<A href="javascript:showHide('<?=$medium_rows.'_'.$rows;?>','two','<?php echo $_GET['value'],'___',$ads_details['customers_advertiser']?>');"><IMG id=cnt_icon_<?=$medium_rows.'_'.$rows;?>  title="" height=11 alt="" src="images/icon_plus.gif" width=11 border=0> </A><?php echo tep_db_output($ads_details['customers_advertiser']);?> </span>
							</td>
							<td class="dataTableContent" width="15%" style="text-indent: 2px;"><?php echo tep_db_output($ads_details['source_count']); ?></td>
							<td class="dataTableContent" width="15%" style="text-indent: 3px;">
							<?php
							$ad_query_raw_total_source = "select distinct orders.customers_advertiser, count(*) as count, sum(value) as total_value from " . TABLE_CUSTOMERS . ", " . TABLE_ORDERS . ", " . TABLE_ORDERS_TOTAL . ", ".TABLE_AD_SOURCE_CLICKS_STORES." as ascs WHERE ascs.clicks_id=orders.customers_ad_click_id and orders.customers_advertiser <> '' AND ascs.utm_medium = '".tep_db_input($_GET['value'])."' and orders.customers_advertiser ='".tep_db_input(tep_db_prepare_input($ads_details['customers_advertiser']))."' and orders.customers_advertiser = ascs.customers_advertiser   ".$extrawhere_total." AND customers.customers_id = orders.customers_id and orders.orders_id = orders_total.orders_id and class = 'ot_subtotal' group by ascs.utm_medium ORDER BY orders.customers_advertiser";
				 			$ad_query_total_source = tep_db_query($ad_query_raw_total_source);
				   			$ads_total_source = tep_db_fetch_array($ad_query_total_source);		
							?>
							<?php				
							 echo (int)$ads_total_source['count']; 				 
							 ?>
							
							</td>
							<td class="dataTableContent" width="20%" align="right"><?php 
							echo $currencies->format($ads_total_source['total_value']); 				
							?>
							</td>
							<td class="dataTableContent" width="20%" align="right"><?php  
							 if($ads_details['source_count']>0){
							 echo $currencies->format($ads_total_source['total_value']/$ads_details['source_count']); 
							 }
							 ?>
							 </td>
						   </tr>
						   
						   <tr>
						   
						   <td colspan="5">
						   <DIV id=cnt_desc_<?=$medium_rows.'_'.$rows;?>  style="DISPLAY: none"></div></div>					   
						   </td></tr>
							<?php
							
							}
					}
					?>
				  
				 
				  
				  </table><?php exit;break;
case 'two' : ?><table width="100%" cellpadding="0" cellspacing="0" bgcolor="#F1F5F5">
						    <tr>
							  <td >
							  <table width="100%" border="0" cellspacing="0" cellpadding="0">
								<!-- amit added for terms  -->
								  <?php
								  $arr_tmp=explode('___', $_GET['value']);
								  $utm_medium=$arr_tmp[0];
								  $customers_advertiser=$arr_tmp[1];
								  $detailsand = " and utm_medium='".tep_db_input($utm_medium)."' ";
									$sortorder_all = ' order by utm_term asc'; 
									 $ad_query_details_raw_all = "select *, count(*) as key_cnt from ".TABLE_AD_SOURCE_CLICKS_STORES." where customers_advertiser = '".tep_db_input($customers_advertiser)."'  ".$extrawhere." ".$detailsand." group by utm_term ".$sortorder_all  ;
									$ad_query_details_all_array = tep_db_query($ad_query_details_raw_all);
									$keyword_count = 0;
									while ($ad_query_details_all = tep_db_fetch_array($ad_query_details_all_array)) {
									$keyword_count++;
								   ?>
								   <tr>
								   <td class="dataTableContent" width="30%" height="16">&nbsp;&nbsp;&nbsp;&nbsp;&bull;&nbsp;<? //=$keyword_count;?><?php echo tep_db_output($ad_query_details_all['utm_term']); ?></td>
								   <td class="dataTableContent" width="15%" style="text-indent: 2px;"><?php  echo tep_db_output($ad_query_details_all['key_cnt']); ?></td>
								   <td class="dataTableContent" width="15%" style="text-indent: 2px;">
								   <?php
									//amit added for individule query start
									$ad_query_raw_total_source = "select distinct orders.customers_advertiser, count(*) as count, sum(value) as total_value from " . TABLE_CUSTOMERS . ", " . TABLE_ORDERS . ", " . TABLE_ORDERS_TOTAL . ", ".TABLE_AD_SOURCE_CLICKS_STORES." as ascs WHERE ascs.clicks_id=orders.customers_ad_click_id and orders.customers_advertiser <> '' and ascs.utm_term = '".tep_db_input($ad_query_details_all['utm_term'])."'  AND ascs.utm_medium = '".tep_db_input($utm_medium)."' and orders.customers_advertiser ='".tep_db_input(tep_db_prepare_input($customers_advertiser))."' and orders.customers_advertiser = ascs.customers_advertiser   ".$extrawhere_total." AND customers.customers_id = orders.customers_id and orders.orders_id = orders_total.orders_id and class = 'ot_subtotal' group by ascs.utm_medium ORDER BY orders.customers_advertiser";
									$ad_query_total_source = tep_db_query($ad_query_raw_total_source);
									$ads_total_source = tep_db_fetch_array($ad_query_total_source);		
									//amit added for individule source end
									?>
									<?php				
									 echo (int)$ads_total_source['count']; 				 
									 ?>
								   <?php //echo tep_db_output($ad_query_details_all['utm_campaign']); ?></td>
								  <td class="dataTableContent" width="20%" align="right"><?php 
									echo $currencies->format($ads_total_source['total_value']); 				
									?></td>
								  <td class="dataTableContent" width="20%" align="right"><?php  
									 if($ad_query_details_all['key_cnt']>0){
									 echo $currencies->format($ads_total_source['total_value']/$ad_query_details_all['key_cnt']); 
									 }
									 ?></td>
								  </tr>
								 
								   <?php }?>							  
							  </table>							  
							  </td>
							  </tr>						   
						   </table><?php exit;break;
  }
?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>
<script type="text/javascript" src="includes/javascript/jquery-1.4.1.min.js"></script>
<div id="spiffycalendar" class="text"></div>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
<script language="javascript"><!--

//var dateAvailable = new ctlSpiffyCalendarBox("dateAvailable", "date_range", "start_date","btnDate","<?php echo $start_date; ?>",scBTNMODE_CUSTOMBLUE);
//var dateAvailable1 = new ctlSpiffyCalendarBox("dateAvailable1", "date_range", "end_date","btnDate1","<?php echo $end_date; ?>",scBTNMODE_CUSTOMBLUE);
//--></script>

<SCRIPT language=javascript type=text/javascript>
<!--
function getInfo(type,value,key){
	
	htmlobj=$.ajax({url:"stats_ad_results_medium.php?ajax="+type+'&value='+value+'&&key='+key+'&&start_date=<?php echo $start_date;?>&&end_date=<?php echo $end_date; ?>',async:false});
  	return htmlobj.responseText;
	}
  function showHide(elementID,type,value) {
    var desc = null;

    if (document.getElementById) {
      desc = document.getElementById("cnt_desc_" + elementID);
    } else if (document.all) {
      desc = document.all["cnt_desc_" + elementID];
    } else if (document.layers) {
      desc = document.layers["cnt_desc_" + elementID];
    }

    if (desc) {
      if (desc.style.display == 'none') {
	  if(desc.innerHTML=='')
	  desc.innerHTML=getInfo(type,value,elementID);
        expand(elementID);
      } else {
        collapse(elementID);
      }
    }
  }

  function expand(elementID) {
    var cnt = null;
    var desc = null;
    var icon = null;

    if (document.getElementById) {
      cnt = document.getElementById("cnt_" + elementID);
      desc = document.getElementById("cnt_desc_" + elementID);
      icon = document.getElementById("cnt_icon_" + elementID);
    } else if (document.all) {
      cnt = document.all["cnt_" + elementID];
      desc = document.all["cnt_desc_" + elementID];
      icon = document.all["cnt_icon_" + elementID];
    } else if (document.layers) {
      cnt = document.layers["cnt_" + elementID];
      desc = document.layers["cnt_desc_" + elementID];
      icon = document.layers["cnt_icon_" + elementID];
    }

    if (desc.style.display == 'none') {
	/*
      cnt.style.backgroundColor = '#FFFAF3';
      cnt.style.border = '1px dotted #000000';
      cnt.style.padding = '5px';
      cnt.style.marginBottom = '5px';
	  */
	   cnt.style.backgroundColor = '';
      cnt.style.border = '';
      cnt.style.padding = '';
      cnt.style.marginBottom = '';
      desc.style.display = 'block';
      icon.src = "images/icon_minus.gif"
    }
  }

  function collapse(elementID) {
    var cnt = null;
    var desc = null;
    var icon = null;

    if (document.getElementById) {
      cnt = document.getElementById("cnt_" + elementID);
      desc = document.getElementById("cnt_desc_" + elementID);
      icon = document.getElementById("cnt_icon_" + elementID);
    } else if (document.all) {
      cnt = document.all["cnt_" + elementID];
      desc = document.all["cnt_desc_" + elementID];
      icon = document.all["cnt_icon_" + elementID];
    } else if (document.layers) {
      cnt = document.layers["cnt_" + elementID];
      desc = document.layers["cnt_desc_" + elementID];
      icon = document.layers["cnt_icon_" + elementID];
    }

    if (desc.style.display != 'none') {
      cnt.style.backgroundColor = '';
      cnt.style.border = '';
      cnt.style.padding = '';
      cnt.style.marginBottom = '';
      desc.style.display = 'none';
      icon.src = "images/icon_plus.gif"
    }
  }

  function expandAll() {
    var cnt = null;

    if (document.body.getElementsByTagName) {
      cnt = document.body.getElementsByTagName('DIV');
    } else if (document.body.all) {
      cnt = document.body.all.tags('DIV');
    }

    if (cnt) {
      for (var i=0; i<cnt.length; i++) {
        if (cnt[i].id.substring(0, 4) == 'cnt_') {
          if (cnt[i].id.substring(0, 5) != 'cnt_d') {
            expand(cnt[i].id.substring(4));
          }
        }
      }
    }
  }

  function collapseAll() {
    var cnt = null;

    if (document.body.getElementsByTagName) {
      cnt = document.body.getElementsByTagName('DIV');
    } else if (document.body.all) {
      cnt = document.body.all.tags('DIV');
    }

    if (cnt) {
      for (var i=0; i<cnt.length; i++) {
        if (cnt[i].id.substring(0, 4) == 'cnt_') {
          if (cnt[i].id.substring(0, 5) != 'cnt_d') {
            collapse(cnt[i].id.substring(4));
          }
        }
      }
    }
  }
//-->
</SCRIPT>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php
  if ($printable != 'on') {
  require(DIR_WS_INCLUDES . 'header.php');
  }; ?>
<!-- header_eof //-->

<!-- body //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('stats_ad_results_medium');
$list = $listrs->showRemark();
?>
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
  <?php 
   if ($printable != 'on') {;?>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
        </table>
		<?php }; ?>
		</td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>     
      <tr><td>
	  <table>
<tr><td></td><td class="main">
<?php
    echo tep_draw_form('date_range','stats_ad_results_medium.php' , '', 'get');
    echo ENTRY_STARTDATE .  '&nbsp;'; //tep_draw_input_field('start_date', $start_date).
	?>
	<?php echo tep_draw_input_field('start_date', $start_date, ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"');?>
	<script language="javascript">//dateAvailable.writeControl(); dateAvailable.dateFormat="yyyy-MM-dd";</script>
	<?php
    echo '';
    echo ENTRY_TODATE .  '&nbsp;';//tep_draw_input_field('end_date', $end_date).
    ?>
	<?php echo tep_draw_input_field('end_date', $end_date, ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"');?>
	<script language="javascript">//dateAvailable1.writeControl(); dateAvailable1.dateFormat="yyyy-MM-dd";</script>
	<?php
	//echo ENTRY_PRINTABLE . tep_draw_checkbox_field('printable', $print). '&nbsp;';
   // echo ENTRY_SORTVALUE . tep_draw_checkbox_field('total_value', $total_value). '&nbsp;&nbsp;';
    echo '<input type="submit" value="'. ENTRY_SUBMIT .'">';
    echo '</form>';

    $grand_total_value = 0;
    $total_number_sales = 0;
?>
</td></tr>
</table></td></tr>          
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="2" cellpadding="2">
			<tr>
			<?php //echo tep_draw_form('ad_results', FILENAME_STATS_AD_RESULTS_DETAILS, 'action=new_product_preview', 'post', 'enctype="multipart/form-data"'); ?>
          </tr>
              <tr class="dataTableHeadingRow">
			  <?php			  
			      $HEADING_SOURCE = TABLE_HEADING_MEDIUM." ";
				  $HEADING_SOURCE .= '<a href="' . $_SERVER['PHP_SELF'] . '?'.tep_get_all_get_params(array('page','sort','order', 'action')) .'sort=utm_medium&order=ascending">';
				  $HEADING_SOURCE .= '&nbsp;<img src="images/arrow_up.gif" border="0"></a>';
				  $HEADING_SOURCE .= '<a href="' . $_SERVER['PHP_SELF'] . '?'.tep_get_all_get_params(array('page','sort','order', 'action')) .'sort=utm_medium&order=decending">';
				  $HEADING_SOURCE .= '&nbsp;<img src="images/arrow_down.gif" border="0"></a>';
			  ?>
                <td width="20%" nowrap class="dataTableHeadingContent" width="30%"><?php echo $HEADING_SOURCE; ?></td>
				 <td class="dataTableHeadingContent" width="15%"><?php echo TABLE_HEADING_CLICKS; ?></td>				  
                <td class="dataTableHeadingContent" width="15%"><?php echo TABLE_HEADING_NUMBER_OF_SALES; ?></td>
                <td class="dataTableHeadingContent" width="20%" align="right"><?php echo TABLE_HEADING_TOTAL_AMOUNT; ?>&nbsp;</td>
				<td class="dataTableHeadingContent"  align="right" width="20%"><?php echo '$/visits'; ?></td>
              </tr>
   <?php 
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
          $sortorder = ' order by utm_medium  asc';       
		
      break;
    }

     
	 
   
   
  $ad_query_raw = "select utm_medium, count(*) as ad_count from ".TABLE_AD_SOURCE_CLICKS_STORES." where 1 and utm_medium != ''  ".$extrawhere." ".$extragroupby." ".$sortorder;
	//echo $ad_query_raw;
	define('MAX_DISPLAY_SEARCH_AD_RESULTS','20');

	require(DIR_WS_CLASSES.'split_page_results_outer.php');

  $products_split = new splitPageResults1($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_AD_RESULTS, $ad_query_raw, $products_query_numrows);

  $ad_query = tep_db_query($ad_query_raw);
  
  //echo tep_db_num_rows($ad_query) ;
  while ($ads = tep_db_fetch_array($ad_query)) {
    $rows++;

    if (strlen($rows) < 2) {
      $rows = '0' . $rows;
    }
?>
				<DIV id=cnt_<?=$rows;?> >
                <tr class="dataTableRow" >
                <td class="dataTableContent" width="30%" >
				<A href="javascript:showHide('<?=$rows;?>','one','<?php echo $ads['utm_medium']?>');"><IMG id=cnt_icon_<?=$rows;?>  title="" height=11 alt="" src="images/icon_plus.gif" width=11 border=0> <?php // echo $ads['customers_advertiser']; ?></A>
				<b><?php echo tep_db_output($ads['utm_medium']); ?></b></td>				
                <td class="dataTableContent" width="15%"><b>
				<?php
				 $grand_total_ad_count = $grand_total_ad_count + $ads['ad_count'];				
				 echo tep_db_output($ads['ad_count']); ?></b></td>
				<?php 
				  if ($total_value =='on') {
					$ad_query_raw_total = "select distinct orders.customers_advertiser, count(*) as count, sum(value) as total_value from " . TABLE_CUSTOMERS . ", " . TABLE_ORDERS . ", " . TABLE_ORDERS_TOTAL . ", ".TABLE_AD_SOURCE_CLICKS_STORES." as ascs WHERE ascs.clicks_id=orders.customers_ad_click_id and orders.customers_advertiser <> '' AND orders.customers_advertiser ='".tep_db_input($ads['customers_advertiser'])."'  ".$extrawhere_total." AND customers.customers_id = orders.customers_id and orders.orders_id = orders_total.orders_id and class = 'ot_subtotal' group by orders.customers_advertiser ORDER BY total_value DESC";
				  } else {
					$ad_query_raw_total = "select distinct orders.customers_advertiser, count(*) as count, sum(value) as total_value from " . TABLE_CUSTOMERS . ", " . TABLE_ORDERS . ", " . TABLE_ORDERS_TOTAL . ", ".TABLE_AD_SOURCE_CLICKS_STORES." as ascs WHERE ascs.clicks_id=orders.customers_ad_click_id and orders.customers_advertiser <> '' AND ascs.utm_medium = '".tep_db_input($ads['utm_medium'])."' and orders.customers_advertiser = ascs.customers_advertiser   ".$extrawhere_total." AND customers.customers_id = orders.customers_id and orders.orders_id = orders_total.orders_id and class = 'ot_subtotal' group by ascs.utm_medium ORDER BY orders.customers_advertiser";
				   } 
				   
				   $ad_query_total = tep_db_query($ad_query_raw_total);
				   $ads_total = tep_db_fetch_array($ad_query_total);			
				 ?>
				 <td class="dataTableContent" width="15%"><b><?php				
				 echo (int)$ads_total['count']; 				 
				 ?></b></td>
                <td class="dataTableContent" width="20%" align="right"><b><?php 
				echo $currencies->format($ads_total['total_value']); 				
				?></b></td>
				 <td class="dataTableContent"  width="20%" align="right"><b><?php  
				 if($ads['ad_count']>0){
				 echo $currencies->format($ads_total['total_value']/$ads['ad_count']); 
				 }
				 ?></b></td>				
                </tr>
				<tr>
				<td colspan="5">
				<DIV id=cnt_desc_<?=$rows;?> style="DISPLAY: none"></DIV></DIV>
				</td>
				</tr>						
				
		 
	<?php
	 $grand_total_value = $grand_total_value + $ads_total['total_value'];
  	$total_number_sales = $total_number_sales + $ads_total['count'];
	
	 } ?>			
	 <!-- last row of each sections -->

                <tr bgcolor="#CDDADA">
                <td class="dataTableContent"><b><?php echo ENTRY_TOTAL; ?></b></td>
               <td class="dataTableContent"><b><?php echo $grand_total_ad_count;?><b></td>
				
                <td class="dataTableContent"><b><?php echo $total_number_sales; ?></b></td>
                <td class="dataTableContent" align="right"><b><?php echo $currencies->format($grand_total_value); ?></b></td>
				<td class="dataTableContent" align="right"><b>
				<?php
				if($grand_total_value > 0){
				echo $currencies->format($grand_total_value/$grand_total_ad_count);
				} ?>
				</b></td>
              </tr>
			  
			  <!-- last row of each sections -->
	 </table></td>
          </tr>
		
		  
		  
          <tr>
            <td colspan="3">			
						  <table border="0" width="100%" cellspacing="0" cellpadding="2">
						  <tr>
							<td class="smallText" valign="top"><?php echo $products_split->display_count1($products_query_numrows, MAX_DISPLAY_SEARCH_AD_RESULTS, $HTTP_GET_VARS['page'], ''); ?></td>
							<td class="smallText" align="right"><?php echo $products_split->display_links1($products_query_numrows, MAX_DISPLAY_SEARCH_AD_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'],tep_get_all_get_params(array('page', 'oID', 'action'))); ?>&nbsp;</td>
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

<!-- footer //-->
<?php
  if ($printable != 'on') {
   require(DIR_WS_INCLUDES . 'footer.php');
  }
?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>