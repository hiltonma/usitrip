<?php
/**
 * 工作人员评分统计报表，主要是给Sofia等领导看的
 * @package 
 * @author Howard
 */
require('includes/application_top.php');
// 备注添加删除
if($_GET['ajax']=="true"){
	include DIR_FS_CLASSES . 'Remark.class.php';
	$remark = new Remark('assessment_score_report');
	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
}
require('includes/classes/Assessment_Score.class.php');	//添加商务中心考核类
$Assessment_Score = new Assessment_Score();

$action = $_POST['action'] ? $_POST['action'] : ($_GET['action'] ? $_GET['action']:'');
switch($action){
	default:
		$list_datas = $Assessment_Score->get_score_report_list($_GET,'5,7,42,47,48');
	break;
}

//print_r($D);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script type="text/javascript" src="includes/menu.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="includes/big5_gb-min.js"></script>
<script type="text/javascript" src="includes/general.js"></script>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
<script>
//日期工号积分比查询
function search_check(obj){
	var form = obj;
	if(form.elements['job_num'].value!='' && form.elements['job_num'].value.search(/^[0-9]+(,[0-9]+)*$/) == -1){
		alert('工号不符合要求！');
		return false;
	}
	//if(form.elements['time_start'].value.search(/^[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2}$/) == -1 || form.elements['time_end'].value.search(/^[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2}$/) == -1){
		//alert('请选择日期！');
		//return false;
	//}
	obj.submit();
}
</script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<style type="text/css">
#ul_search li{float:left; display:block; margin:5px;}
</style>
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('assessment_score_report');
$list = $listrs->showRemark();
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">商务中心工作人员评分统计报表2</td>
            <td class="pageHeading" align="right">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td class="main">
		  <!--search form start-->
          <fieldset>
          <legend align="left"> Search Module </legend>
          <?php echo tep_draw_form('form_search', 'assessment_score_report.php', tep_get_all_get_params(array('page','y','x', 'action')), 'get', ' onsubmit="search_check(this); return false;" '); ?>
          
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><table border="0" cellspacing="0" cellpadding="0" style="margin:10px;">
                  
                  <?php
                  $s_option_display = 'none';
                  if(isset($search_type) && $search_type =='orders'){
                  	$s_option_display = '';
                  }
                  ?>
                  <!-- 修改搜索（参考前台UI） start-->
                  <!-- by panda修改搜索（参考前台UI） end-->
                  <tr>
                  	<td class="main" align="left">
					<ul id="ul_search">
					<li>
					出团日期：
					  <?= tep_draw_input_num_en_field('time_start','','class="textTime" onClick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" ');?>
					至
					<?= tep_draw_input_num_en_field('time_end','','class="textTime" onClick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" ');?>
					</li>
					<li>
					工号：<?= tep_draw_input_field('job_num','','size="100" style="ime-mode:disabled" ');?>
					</li>
					<li>
					添加日期：
					  <?= tep_draw_input_num_en_field('add_time_start','','class="textTime" onClick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" ');?>
					至
					<?= tep_draw_input_num_en_field('add_time_end','','class="textTime" onClick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" ');?>
					</li>					
					</ul>
					</td>
                  	</tr>
                  <tr>
                    <td class="main" align="left">&nbsp;
                    	<button name="Search" type="submit" style="font-size:14px">Search</button> <a href="<?= tep_href_link('assessment_score_report.php')?>">清除搜索条件</a></td>
                    </tr>
                </table>
				</td>
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
                <td class="dataTableHeadingContent" nowrap="nowrap">工号</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">个人总积分</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">个人积分百分比</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">订单金额</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">订单金额:个人总积分</td>
              </tr>
            <?php
            for($i=0, $n=sizeof($list_datas['list']); $i<$n; $i++){
				$D = $list_datas['list'][$i];
			?>
              <tr class="dataTableRow" style="cursor:auto; background-color:<?php echo $bg_color = ($bg_color == "#F0F0F0") ? '#ECFFEC':'#F0F0F0';?>">
                <td class="dataTableContent"><?php echo $D['admin_job_number']?></td>
                <td class="dataTableContent"><?php echo $D['adminScore']?></td>
                <td class="dataTableContent"><?php echo $D['adminVsSystemScore']?></td>
                <td class="dataTableContent"><?php echo '$'.number_format($D['ordersAmount'],2,'.','')?></td>
                <td class="dataTableContent"><?php echo $D['ordersAmountVsAdminScore']?></td>
              </tr>
            <?php }?>  
            </table></td>
          </tr>
        </table>
        <table border="0" width="100%" cellspacing="0" cellpadding="2">
		  <tr>
			<td class="smallText" valign="top"><?php echo $list_datas['split']['display_count']; ?></td>
			<td class="smallText" align="right"><?php echo $list_datas['split']['display_links']; ?></td>
		  </tr>
		</table>
        </fieldset>
        </td>
      </tr>
    </table>

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>

</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>