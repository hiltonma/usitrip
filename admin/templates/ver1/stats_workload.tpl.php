<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
<script language="JavaScript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="includes/menu.js"></script>
<script type="text/javascript" src="includes/big5_gb-min.js"></script>
<script type="text/javascript" src="includes/general.js"></script>
<style type="text/css">
body{margin:0px;padding:0px;background:#fff;}
.searchForm th{ width:10em;text-align:right;padding:0 0.5em 0;}
.searchForm legend {border:0;}
.user{background: #FFFFFF;font-size:12px;  border: 1px solid #FDD01B;display: inline;float: left; height: 16px; margin: 2px;cursor: pointer;padding: 0 8px;}
.user i{text-decoration:none;margin:0 5px 0;}
.users{width:600px;height:60px;overflow:scroll;margin:3px;background:#ECE9D8;border-top:1px solid #828177 ; border-left:1px solid #828177 ;border-bottom:1px solid #F9F8F3 ;border-right:1px solid #F9F8F3 ;}
.selectUser {display:none; background:#fff;border:1px solid #cecece;position:absolute;}
.selectUser .title {text-align:right;padding:5px;background:#ebebeb;height:20px;}
.userBox{width:420px;height:250px;overflow:scroll;}
.tableList {font-size:12px;width:100%;border:1px solid #ccc;border-width:0px 1px 1px 1px;border-collapse:   collapse; }
.tableList td{padding:3px;border-top:1px solid #eee}
.tableList th{}
.tableList tr{padding:3px;}

.tableList th{text-align:left;padding:3px;}
.tableList .heading{background:#E0E0E0;color:#333;}
.tableList .total{color:red;font-weight:bold;}
.tableList caption{text-align:left;border:1px solid #ccc;border-width:1px 1px 0px 1px;font-size:14px;padding:5px;color:#3476B2}
.tableList caption h4 {width:18em;float:left;}
.textTime{font-size: 12px;    height: 20px;    line-height: 16px;    padding: 3px; width: 128px;}
</style>
</head>
<body>
<div id="spiffycalendar" class="text"></div>
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
  	<td width="<?php echo BOX_WIDTH; ?>" valign="top">	
	<table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft"><?php require(DIR_WS_INCLUDES . 'column_left.php'); ?> </table>
	</td>
	
    <td width="100%" valign="top">
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
          	<td class="pageHeading">客服工作量数据管理 </td>
          	<td class="pageHeading" align="right">
			<button onclick="javascript:location.href='<?php echo tep_href_link('stats_workload.php')?>'"><strong>Add Workload Record </strong></button>
			<button onclick="javascript:location.href='<?php echo tep_href_link('stats_csc.php')?>'"><strong> Workload Report </strong></button>
			<?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>		
        </table>
        <?php echo tep_draw_form("workloadInfo", 'stats_workload.php','action='.$do_action);?>	
        <table border="0" width="100%" cellspacing="2" cellpadding="1" class="tableList">
        	<tr style="background:#C9C9C9;color:#fff">
        		<td>序号</td>
				<td>客服人员</td>
        		<td>日期</td>
        		<td>E-Mail</td>
        		<td>IP电话接听 </td>
        		<td>IP电话呼出 </td>
        		<td>启通宝接听 </td>
        		<td>启通宝呼出 </td>
        		<td>Action </td>
			</tr>
			<tr <?php
			 echo $do_action=='do_edit'? ' style="background:#cf0"':'';
			 $disabled = $do_action=='do_edit'? ' disabled ':'';
			?>>
				<td><?php echo  $edit_workload_id.'<input type="hidden" name="workload_id" value="'.$edit_workload_id.'"/>';?></td>
				<td><?php echo draw_service_list('admin_id',$admin_id,$disabled);?></td>
        		<td><?php echo tep_draw_input_field('workload_date', date_to_str($workload_date),' size="12"'.$disabled,false,'text',true);?></td>
        		<td><?php echo tep_draw_input_field('email_answer',$email_answer,' size="5"',false,'text',true)?></td>
        		<td><?php echo tep_draw_input_field('phone_callin',$phone_callin,' size="5"',false,'text',true)?></td>
        		<td><?php echo tep_draw_input_field('phone_callout',$phone_callout,' size="5"',false,'text',true)?></td>
        		<td><?php echo tep_draw_input_field('qtb_callin',$qtb_callin,' size="5"',false,'text',true)?></td>
        		<td><?php echo tep_draw_input_field('qtb_callout',$qtb_callout,' size="5"',false,'text',true)?></td>
        		<td><button type="submit">Submit</button></td>
			</tr>
			<tr style="background:#C9C9C9;color:#fff">
        		<td>序号</td>
				<td>客服人员</td>
        		<td>日期</td>
        		<td>E-Mail</td>
        		<td>IP电话接听 </td>
        		<td>IP电话呼出 </td>
        		<td>启通宝接听 </td>
        		<td>启通宝呼出 </td>
        		<td>Action </td>
			</tr>
			<?php foreach ( $workloadinfo_list as $workloadinfo){
				$modify_info = 'created '.tep_get_admin_customer_name($workloadinfo['crearted_user']).'@'.$workloadinfo['created_date'].' ; update:'.tep_get_admin_customer_name($workloadinfo['last_modified_user']).'@'.$workloadinfo['last_modified_date'];
			?>
			<tr onmouseover="this.style.background='#FFFF80'" onmouseout="this.style.background=''" alt=" <?php echo $modify_info ?>" title=" <?php echo $modify_info ?>">
				<td><?php echo $workloadinfo['workload_id'];?></td>
				<td><?php echo tep_get_admin_customer_name($workloadinfo['admin_id']);?></td>
				<td><?php echo date_to_str($workloadinfo['workload_date']);?></td>
				<td><?php echo $workloadinfo['email_answer'];?></td>
				<td><?php echo $workloadinfo['phone_callin'];?></td>
				<td><?php echo $workloadinfo['phone_callout'];?></td>
				<td><?php echo $workloadinfo['qtb_callin'];?></td>
				<td><?php echo $workloadinfo['qtb_callout'];?></td>
        		<td><a href="<?php echo tep_href_link('stats_workload.php','action=edit&workload_id='.$workloadinfo['workload_id'])?>">Edit</a> | 
        		<a href="javascript:;" onclick=" if(confirm('你确定要删除吗？删除后不能恢复!')) location.href='<?php echo tep_href_link('stats_workload.php','action=delete&workload_id='.$workloadinfo['workload_id'])?>'">Delete</a></td>
			</tr>
			<?php }?>
			<tr>
			<td colspan="4" align="left"><?php echo $workload_split->display_count($query_numrow, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_WORKLOAD); ?></td>
			<td colspan="5" align="right"><?php echo $workload_split->display_links($query_numrow, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></td>
			</tr>
        </table>
        
        <?php tep_draw_form_close('workloadInfo');?>	
	</td>
 </tr>
</table>
<!-- body_eof //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
</body>
</html>