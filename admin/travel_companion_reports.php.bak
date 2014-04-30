<?php
  require('includes/application_top.php');
  // 备注添加删除
  if($_GET['ajax']=="true"){
  	include DIR_FS_CLASSES . 'Remark.class.php';
  	$remark = new Remark('travel_companion_reports');
  	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
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

<script language="javascript"><!--

var Date_start = new ctlSpiffyCalendarBox("Date_start", "form_search", "start_date","btnDate3","<?php echo ($start_date); ?>",scBTNMODE_CUSTOMBLUE);
var Date_end = new ctlSpiffyCalendarBox("Date_end", "form_search", "end_date","btnDate4","<?php echo ($end_date); ?>",scBTNMODE_CUSTOMBLUE);
//--></script>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
<div id="spiffycalendar" class="text"></div>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">





<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('travel_companion_reports');
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
            <td class="pageHeading">结伴同游统计</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
          <!--search form start-->
		  <fieldset>
		  <legend align="left"> Search Module </legend>
		  <?php echo tep_draw_form('form_search', 'travel_companion_reports.php', tep_get_all_get_params(array('page','y','x', 'action')), 'get'); ?>
		  
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td><table border="0" cellspacing="0" cellpadding="0" style="margin:10px;">
                  <tr>
                    <td class="main" align="right">日期</td>
                    <td class="main" align="left">&nbsp;
					<?php echo tep_draw_input_field('start_date', tep_get_date_disp($_GET['start_date']), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"');?>
					<script language="javascript">//Date_start.writeControl(); Date_start.dateFormat="yyyy-MM-dd";</script></td>
                    <td>至</td>
                    <td class="main" align="right">&nbsp;<?php echo tep_draw_input_field('end_date', tep_get_date_disp($_GET['end_date']), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"');?><script language="javascript">//Date_end.writeControl(); Date_end.dateFormat="yyyy-MM-dd";</script></td>
                    <td class="main" align="left">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td colspan="4" align="right" class="main">&nbsp;</td>
                  </tr>
                  <tr>
                    <td class="main" align="right">&nbsp;</td>
                    <td class="main" align="left">&nbsp;<input name="Send" type="submit" value="Send" style="width:100px; height:30px; margin-top:10px;"></td>
                    <td>&nbsp;</td>
                    <td class="main" align="right">&nbsp;</td>
                    <td class="main" align="left">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td colspan="4" align="right" class="main">&nbsp;</td>
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
                <td class="dataTableHeadingContent" nowrap="nowrap">结伴同游主帖总数</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">回帖总数</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">点击总数</td>
                </tr>
              <tr class="dataTableRow" style="cursor:auto; background-color:#F0F0F0">
                <td class="dataTableContent">
				<?php
				$where_c = ' where t_companion_id >=1 ';
				if(strlen($_GET['start_date'])==10){
					$where_c .= ' AND last_time>="'.$_GET['start_date'].' 00:00:00" ';
				}
				if(strlen($_GET['end_date'])==10){
					$where_c .= ' AND last_time<="'.$_GET['end_date'].' 23:59:59" ';
				}
				
				$travel_companion_sql= tep_db_query('SELECT count(*) as total FROM `travel_companion` '.$where_c);
				$travel_companion_row= tep_db_fetch_array($travel_companion_sql);
				echo $travel_companion_row['total'];
				?>
				</td>
                <td class="dataTableContent">
				<?php
				$where_r = ' where t_c_reply_id >=1 ';
				if(strlen($_GET['start_date'])==10){
					$where_r .= ' AND last_time>="'.$_GET['start_date'].' 00:00:00" ';
				}
				if(strlen($_GET['end_date'])==10){
					$where_r .= ' AND last_time<="'.$_GET['end_date'].' 23:59:59" ';
				}
				$travel_companion_reply_sql= tep_db_query('SELECT count(*) as total FROM `travel_companion_reply` '.$where_r);
				$travel_companion_reply_row= tep_db_fetch_array($travel_companion_reply_sql);
				echo $travel_companion_reply_row['total'];
				?>

				</td>
                <td class="dataTableContent">
				<?php
				$travel_click_num_sql= tep_db_query('SELECT SUM(click_num) as total FROM `travel_companion` ');
				$travel_click_num_row= tep_db_fetch_array($travel_click_num_sql);
				echo $travel_click_num_row['total'];
				?>
				</td>
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
