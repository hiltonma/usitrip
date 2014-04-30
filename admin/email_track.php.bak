<?php
require('includes/application_top.php');

$where_exc ='';
if(tep_not_null($_GET['s_customers_name'])){
	$where_exc .= ' AND ( c.customers_firstname like "%'.$_GET['s_customers_name'].'%" ';
	$where_exc .= ' || c.customers_lastname like "%'.$_GET['s_customers_name'].'%" ) ';
}
if(tep_not_null($_GET['key_id'])){
	$where_exc .= ' AND et.key_id like "%'.$_GET['key_id'].'%" ';
}
if(tep_not_null($_GET['key_field'])){
	$where_exc .= ' AND et.key_field = "'.$_GET['key_field'].'" ';
}
if(tep_not_null($_GET['s_email_address'])){
	$where_exc .= ' AND et.email_address like "%'.$_GET['s_email_address'].'%" ';
}

if(tep_not_null($_GET['search_email_type'])){
	$where_exc .= ' AND et.email_type = "'.$_GET['search_email_type'].'" ';
}

$orderBy = ' order by et.email_track_id DESC ';	
$track_query_raw = 'SELECT et.*, c.customers_firstname, c.customers_lastname FROM `email_track` et, customers c WHERE c.customers_email_address = et.email_address '.$where_exc.$orderBy;
$track_query_numrows = 0;
$track_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $track_query_raw, $track_query_numrows);

$track_query = tep_db_query($track_query_raw);

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">

<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>

<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script type="text/javascript">
//创建ajax对象
var ajax = false;
if(window.XMLHttpRequest) {
	 ajax = new XMLHttpRequest();
}
else if (window.ActiveXObject) {
	try {
			ajax = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
	try {
			ajax = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (e) {}
	}
}
if (!ajax) {
	window.alert("<?php echo db_to_html('不能创建XMLHttpRequest对象实例.')?>");
}
</script>
<?php
$p=array('/&amp;/','/&quot;/');
$r=array('&','"');
?>

<script type="text/javascript">
function write_ref_id(ref_id, cus_id){
	if(document.getElementById('td_ref_' + cus_id)!=null){
		document.getElementById('td_ref_' + cus_id).focus();
	}
	var url = "<?php echo preg_replace($p,$r,tep_href_link('stats_order_analysis_ajax.php','action=1')) ?>" +"&customers_id="+ cus_id +"&customers_referer_type=" + ref_id;
	ajax.open("GET", url, true);
	ajax.send(null); 
	
	ajax.onreadystatechange = function() { 
		if (ajax.readyState == 4 && ajax.status == 200 ) { 
			//alert('修改成功！');
		}		
	}
}
</script>
<script language="javascript"><!--

var Date_Reg_start = new ctlSpiffyCalendarBox("Date_Reg_start", "form_search", "reg_start_date","btnDate1","<?php echo ($reg_start_date); ?>",scBTNMODE_CUSTOMBLUE);
var Date_Reg_end = new ctlSpiffyCalendarBox("Date_Reg_end", "form_search", "reg_end_date","btnDate2","<?php echo ($reg_end_date); ?>",scBTNMODE_CUSTOMBLUE);

var Date_Buy_start = new ctlSpiffyCalendarBox("Date_Buy_start", "form_search", "buy_start_date","btnDate3","<?php echo ($buy_start_date); ?>",scBTNMODE_CUSTOMBLUE);
var Date_Buy_end = new ctlSpiffyCalendarBox("Date_Buy_end", "form_search", "buy_end_date","btnDate4","<?php echo ($buy_end_date); ?>",scBTNMODE_CUSTOMBLUE);
//--></script>
<div id="spiffycalendar" class="text"></div>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">





<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
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
            <td class="pageHeading">电子邮件跟踪系统</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
          <!--search form start-->
		  <fieldset>
		  <legend align="left"> Search Module </legend>
		  <?php echo tep_draw_form('form_search', 'email_track.php', tep_get_all_get_params(array('page','y','x', 'action')), 'get'); ?>
		  
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td><table border="0" cellspacing="0" cellpadding="0" style="margin:10px;">
                  <tr>
                    <td align="right" nowrap class="main">客户名称：</td>
                    <td class="main" align="left"><?php echo tep_draw_input_field('s_customers_name')?></td>
                    <td align="right" nowrap class="main">&nbsp;Key：
					<?php
					$type_sql = tep_db_query('SELECT DISTINCT key_field FROM `email_track` WHERE key_field!="" ');
					$values_array = array();
					$values_array[0] = array('id'=>'','text'=>'');
					while($type_rows = tep_db_fetch_array($type_sql)){
						$values_array[]=array('id'=>$type_rows['key_field'],'text'=>$type_rows['key_field']);
					}
					echo tep_draw_pull_down_menu('key_field', $values_array,'','onchange="form_search.submit()"')." = ";
					?>
					</td>
                    <td class="main" align="left"><?php echo tep_draw_input_field('key_id','',' size="10" ')?></td>
                    <td align="left" nowrap class="main">&nbsp;电子邮箱：</td>
                    <td><?php echo tep_draw_input_field('s_email_address')?></td>
                    <td colspan="4" align="right" nowrap class="main">&nbsp;类型：</td>
                    <td align="left" nowrap class="main">
					<?php
					$type_sql = tep_db_query('SELECT DISTINCT email_type FROM `email_track` WHERE email_type!="" Group By email_type');
					$values_array = array();
					$values_array[0] = array('id'=>'','text'=>'');
					while($type_rows = tep_db_fetch_array($type_sql)){
						$values_array[]=array('id'=>$type_rows['email_type'],'text'=>$type_rows['email_type']);
					}
					echo tep_draw_pull_down_menu('search_email_type', $values_array,'','onchange="form_search.submit()"');
					?>
					</td>
                  </tr>
                  <tr>
                    <td class="main" align="right">&nbsp;</td>
                    <td class="main" align="left">&nbsp;<input name="Send" type="submit" value="Send" style="width:100px; height:30px; margin-top:10px;"></td>
                    <td>&nbsp;</td>
                    <td class="main" align="right">&nbsp;</td>
                    <td class="main" align="left">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td colspan="4" align="right" class="main">&nbsp;</td>
                    <td align="right" class="main">&nbsp;</td>
                  </tr>
                </table></td>
			  </tr>
			</table>

		  <?php echo '</form>';?>
		  </fieldset>
		  <!--search form end-->
		  </td>
      </tr>
      <tr>
        <td>
		<fieldset>
		  <legend align="left"> Stats Results </legend>

		<table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="1" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" nowrap="nowrap">跟踪ID</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">Key ID</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">客户名称</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">电子邮箱</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">邮件类型</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">最后跟踪日期</td>
                </tr>
<?php
  while ($track = tep_db_fetch_array($track_query)) {
    $rows++;

    if (strlen($rows) < 2) {
      $rows = '0' . $rows;
    }
	
	$bg_color = "#F0F0F0";
	if((int)$rows %2 ==0 && (int)$rows){
		$bg_color = "#ECFFEC";
	}
?>
              <tr class="dataTableRow" style="cursor:auto; background-color:<?= $bg_color;?>">
                <td class="dataTableContent"><?php echo $track['email_track_id']; ?></td>
                <td class="dataTableContent">
				<?php
				if((int)$track['key_id']){
					echo $track['key_field'].' '. $track['key_id'];
					if($track['key_field']=="orders_id"){
						echo ' [<a href="'.tep_href_link('edit_orders.php','oID='.(int)$track['key_id'].'&action=edit').'" target="_blank">查看</a>]';
					}elseif($track['key_field']=="newsletters_id"){
						echo ' [<a href="'.tep_href_link('newsletters.php','nID='.(int)$track['key_id'].'&action=edit').'" target="_blank">查看newsletters</a>]';
					}
				}
				?>
				</td>
                <td class="dataTableContent"><?php echo tep_db_output($track['customers_firstname'].' '.$track['customers_lastname']);?></td>
                <td class="dataTableContent"><a href="<?php echo tep_href_link('customers.php','search='.rawurlencode($track['email_address']))?>" target="_blank">
				<?php
				if($can_see_customers_email_full_address === true){	//有权查看邮箱全称的人员才可以看完整的地址
					echo $track['email_address'];
				}else{
					echo substr($track['email_address'], 2, (strpos($track['email_address'],'@')-2)).'***';
				}
				?>
				</a>
				</td>
                <td class="dataTableContent"><a href="<?php echo tep_href_link('email_track.php','search_email_type='.$track['email_type'])?>"><?php echo $track['email_type']; ?></a></td>
                
				<td class="dataTableContent"><?php echo $track['email_track_date'];?></td>
                </tr>
			  
<?php
  }
?>
            </table></td>
          </tr>
          <tr>
            <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $track_split->display_count($track_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                <td class="smallText" align="right"><?php echo $track_split->display_links($track_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'],tep_get_all_get_params(array('page','y','x', 'action'))); ?>&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table>
		
		</fieldset>
		</td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
