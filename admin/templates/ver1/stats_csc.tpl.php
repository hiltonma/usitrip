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
.user{width:120px;background: #FFFFFF;font-size:12px;  border: 1px solid #FDD01B;display: inline;float: left; height: 16px; margin: 2px;cursor: pointer;padding: 0 8px;}
.user i{text-decoration:none;margin:0 5px 0;}
.users{width:400px;height:60px;overflow:scroll;margin:3px;background:#ECE9D8;border-top:1px solid #828177 ; border-left:1px solid #828177 ;border-bottom:1px solid #F9F8F3 ;border-right:1px solid #F9F8F3 ;}
.selectUser {display:none; background:#fff;border:1px solid #cecece;position:absolute;}
.selectUser .title {text-align:right;padding:5px;background:#ebebeb;height:20px;}
.userBox{width:380px;height:250px;overflow:scroll;background:#EBEBEB}
.tableList {font-size:12px;width:100%;border:1px solid #ccc;border-width:0px 1px 1px 1px;border-collapse:   collapse; }
.tableList td{padding:3px;border-top:1px solid #eee}
.tableList th{}
.tableList tr{padding:3px;}

.tableList th{text-align:left;padding:3px;}
.tableList .heading{background:#E0E0E0;color:#333;}
.tableList .total{color:red;font-weight:bold;}
.tableList caption{text-align:left;border:1px solid #ccc;border-width:1px 1px 0px 1px;font-size:14px;padding:5px;color:#3476B2}
.tableList caption h4 {width:25em;float:left;}
.textTime{font-size: 12px;    height: 20px;    line-height: 16px;    padding: 3px; width: 128px;}
</style>
</head>
<body>


<script type="text/javascript">

function addUser(id ,name){
	if(jQuery("#"+id+"_span").length >0) return  ;
	var users = jQuery("#users");
	users.append('<span class="user" id="'+id+'_span" ><i>'+name+'</i><a onclick="removeUser(\''+id+'\')" href="javascript:void(0);"><img src="../image/icons/icon_del.gif"></a></span><input type="hidden" id="'+id+'_id" name="userId[]" value="'+id+'" /><input id="'+id+'_name" type="hidden" name="userName[]" value="'+name+'" />');
}
function removeUser(id ){
	jQuery("#"+id+"_span").remove();
	jQuery("#"+id+"_id").remove();
	jQuery("#"+id+"_name").remove();
}
</script>
<div id="spiffycalendar" class="text"></div>
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top">
	<!-- left_navigation //-->
	<table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft"><?php require(DIR_WS_INCLUDES . 'column_left.php'); ?> </table>
	<!-- left_navigation_eof //-->
	</td>
    <td width="100%" valign="top">
	<table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr><td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr><td class="pageHeading">客服工作量统计</td>
          <td class="pageHeading" align="right"><button type="button" onclick="location.href='<?php echo tep_href_link('stats_workload.php')?>'"><strong>Workload Record Managment</strong></button><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td></tr>
        </table>
	  </td></tr>
      <tr><td>
          <!--search form start-->
		  <fieldset class="searchForm">
		  <legend align="left"  style="cursor:pointer"onclick="jQuery('#FormSearch').toggle()"> Search Option Setup</legend>
		  <?php echo tep_draw_form('form_search', 'stats_csc.php', tep_get_all_get_params(array('page','y','x', 'action')), 'get',' id="FormSearch"'); ?>
				<table border="0" cellspacing="0" cellpadding="0" style="margin:10px;"  id="FormSearch">                  
				  <tr>
                    <th width="80">统计项目</th>
                    <td width="600" class="main" align="left">
					<?php echo tep_draw_radio_field('search_type', 'orders_status', true,'',' onclick="javascript:jQuery(\'#DivAverge\').fadeOut(\'fast\')"')?> OrderStatus Update Detail 
					<?php echo tep_draw_radio_field('search_type', 'qa', '','',' onclick="javascript:jQuery(\'#DivAverge\').fadeIn(\'fast\')" ')?>Service Workload	
					<?php echo tep_draw_radio_field('search_type', 'accountant', '','',' onclick="javascript:jQuery(\'#DivAverge\').fadeIn(\'fast\')" ')?>Accountant Workload	
					<?php echo tep_draw_radio_field('search_type', 'report', '','','  onclick="javascript:jQuery(\'#DivAverge\').fadeOut(\'fast\')" ')?> Overview		
					</td>
					<th width="80">设置时间段</th>
				    <td align="left">
						<input type="text" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" value="<?php echo $start_date?>" name="start_date" class="textTime">
						<input type="text" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" value="<?php echo $end_date?>" name="end_date" class="textTime">
					</td>
				    </tr>
				<tr>
					<th valign="top">选择人员<br><small>(<a href="javascript:;"  onclick="jQuery('#SelectUser').toggle()">选择人员</a> &nbsp;<a href="javascript:;"  onclick="jQuery('#users').html('')">清空人员</a>)</small></th>
					<td colspan="3">
					<div id="users"   style="height:22px"></div>
					<div></div>
					
					<div id="SelectUser" class="selectUser">
					<div class="title">
					<div style="float:left;">
						<select onchange="jQuery('.userGroupDiv').hide();jQuery('#'+this.value).fadeIn('fast');" >
						<option value="allUser">全部(All) </option>
						<option value="CnServiceSenior">中国区Senior(china team senior group) </option>
						<option value="CnServiceJunior">中国区Junior(china team junior group) </option>						
						<option value="UsServiceSenior">美国区Senior(US team senior group)</option>
						<option value="UsServiceJunior" >美国区Junior(US team junior group)</option>
						<option value="Accountant">会计 (Accounting) </option>
						</select>
						</div>
					<a onclick="jQuery('#SelectUser').hide()"  href="javascript:void(0);"><img src="../image/icons/icon_del.gif"></a></div>
					<div class="userBox">					
					<?php 
					$service_senior_id = '5';
					$accountant_id = '11';
					$service_junior_id = '7';
					$accountants = array('112','125','166','64');
					
					$admin_query = tep_db_query("SELECT admin_lastname,admin_firstname,admin_id,admin_groups_id,admin_source FROM admin WHERE admin_groups_id IN (5,7,11,1) OR admin_id IN(".implode(',',$accountants).") ORDER by admin_firstname ASC");					
					$userlist = array(
						'UsServiceSenior'=>array(),
						'UsServiceJunior'=>array(),
						'CnServiceSenior'=>array(),
						'CnServiceJunior'=>array(),
						'Accountant'=>array()
					);
					$tmpHtml = '<div style="" class="userGroupDiv" id="allUser">';
					while($row = tep_db_fetch_array($admin_query)){
						$row['fullname'] = ucwords($row['admin_firstname'].' '.$row['admin_lastname']);
						$userlist[] = $row ;
						if($row['admin_source'] == '0'){	//US						
							if($row['admin_groups_id'] == $service_senior_id) {
								$userlist['UsServiceSenior'][] = $row;
							}else if($row['admin_groups_id'] == $service_junior_id) {
								$userlist['UsServiceJunior'][] = $row;
							}else if(in_array($row['admin_id'],$accountants)) {
								$userlist['Accountant'][] = $row;
							}
						}else if($row['admin_source'] == '1'){	//CN	
							if($row['admin_groups_id'] == $service_senior_id) {
								$userlist['CnServiceSenior'][] = $row;
							}else if($row['admin_groups_id'] == $service_junior_id) {
								$userlist['CnServiceJunior'][] = $row;
								}else if(in_array($row['admin_id'],$accountants)){
								$userlist['Accountant'][] = $row;
							}
						}
						$tmpHtml .='<div class="user" onclick="addUser(\''.$row['admin_id'].'\',\''.$row['fullname'].'\')" title="'.$row['fullname'].'" alt="'.$row['fullname'].'">'.$row['fullname']."</div>&nbsp;";
					}
					$tmpHtml .= '</div>';
					foreach($userlist as $divname=>$groupedUsers){
						$tmpHtml.= '<div class="userGroupDiv" style="display:none" id="'.$divname.'">';
						foreach($groupedUsers as $row){
							$tmpHtml.= '<div class="user" onclick="addUser(\''.$row['admin_id'].'\',\''.$row['fullname'].'\')" title="'.$row['fullname'].'" alt="'.$row['fullname'].'">'.$row['fullname']."</div>&nbsp;";
						}
						$tmpHtml.='</div>';
					}
					echo $tmpHtml;
					?>
					</div></div>
					<script type="text/javascript">
					<?php 
					foreach($users as $u){
						echo 'addUser("'.$u['id'].'","'.$u['name'].'");';
					}
					?>
					</script>
					</td>
				</tr>			
				<tr id="DivAverge" style="display: <?php echo  $_GET['search_type'] == 'orders_status' || $_GET['search_type'] == 'report' ? 'none':'';?>"><th>数据处理</th><td>
				<?php echo tep_draw_checkbox_field('average','1',$_GET['average']=='1')?> 统计每日工作量平均值
				</td></tr>
				<tr><td></td>
				<td  valign="middle" colspan="3" >
				<input type="hidden" name="download"  id="InputDownload" value="" />				
				<button type="button" onclick="document.getElementById('InputDownload').value='';document.getElementById('FormSearch').submit();" style="font-size:14px;font-weight:bold">     统计     </button>
				<button type="button" onclick="document.getElementById('InputDownload').value='download';document.getElementById('FormSearch').submit();" style="font-size:14px;font-weight:bold">     下载统计结果     </button>
				
				<small>(未指定人员将显示概况)</small></td></tr>
                </table>
		  <?php echo '</form>';?>
		  </fieldset>
		  <!--search form end-->
		  </td>
      </tr>
      <tr>
        <td>
		 <?php 
		 //load list content
		 if($CONTENT != ''){
		 	include DIR_FS_ADMIN . 'templates/ver1/'.$CONTENT.'.tpl.php';
		 }
		 ?>
		</td>
      </tr>
    </table></td>
  </tr>
</table>
<!-- body_eof //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
</body>
</html>