<?php
/**
 * 工作人员评分统计
 * @package 
 * @author Howard
 */
require('includes/application_top.php');
// 备注添加删除
if($_GET['ajax']=="true"){
	include DIR_FS_CLASSES . 'Remark.class.php';
	$remark = new Remark('assessment_score');
	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
}
require('includes/classes/Assessment_Score.class.php');	//添加商务中心考核类
$Assessment_Score = new Assessment_Score();

$action = $_POST['action'] ? $_POST['action'] : ($_GET['action'] ? $_GET['action']:'');
switch($action){
	case 'ajax_search':	//日期工号积分比查询 ajax
	$json_array['data'] = $Assessment_Score->get_job_num_date_score($_GET);
	if($json_array['data']){
		$json_array['result'] = 'success';
	}
	echo json_encode(general_to_ajax_string($json_array, true));exit;
	break;
	default:	//默认是列统计列表
		$score_type = $_GET['score_type'] = max(1, (int)$_GET['score_type']);
		if($_GET['score_type']=='2'){
			$orders_id = $_GET['orders_id'] = '';
		}
		$list_datas = $Assessment_Score->get_score_list((array)$_GET);
	break;
}


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
<script type="text/javascript">
//取得表单数据
function get_form_data(form_id, type ){
	var form = document.getElementById(form_id);
	var aparams = new Array();															/* 创建一个阵列存表单所有元素和值 */
	var eval_string = new Array();
	for(var i=0; i<form.elements.length; i++){
		var name = encodeURIComponent(form.elements[i].name); 							/* 取得表单元素名 */
		var value = '';
		if(form.elements[i].type=="radio" || form.elements[i].type=="checkbox" ){		/* 处理单选、复选按钮值 */
			var a = '';
			if(form.elements[i].checked == true){
				a = form.elements[i].value;
				value = encodeURIComponent(a);   									/* 获得表单元素值 */
			}else{
				name ='';
			}
		}else{
			value = encodeURIComponent(form.elements[i].value);   					/* 获得表单元素值1 */
		}

		if(name!=""){
			var _l = aparams.length;
			aparams[_l] = new Array();
			aparams[_l][name] = value;
			eval_string[eval_string.length] = name + '='+value;
		}
	}
	if(type == "array"){
		return aparams;
	}
	var string = eval_string.join('&');
	string += "&ajax=true";
	return string;
}
//日期工号积分比查询
function ajax_search(){
	//$('form#ajaxSearch');
	var form = document.getElementById('ajaxSearch');
	if(form.elements['job_num'].value.search(/^[0-9]+(,[0-9]+)*$/) == -1){
		alert('工号不符合要求！');
		return false;
	}
	//if(form.elements['time_start'].value.search(/^[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2}$/) == -1 || form.elements['time_end'].value.search(/^[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2}$/) == -1){
		//alert('请选择日期！');
		//return false;
	//}
	$('#ajaxResult').html('<li>查询中，请稍候……</li>');
	var _data = get_form_data('ajaxSearch', 'eval_string')+'&randnumforajaxaction=' + Math.random();
	var url = 'assessment_score.php?action=ajax_search';
	$.get(url, _data, function(json){
		var ul_inner_html='<li><table cellspacing="1" cellpadding="2" border="0"><tr class="dataTableHeadingRow"><th scope="col">工号</th><th scope="col">订单金额</th><th scope="col">个人总积分</th><th scope="col">积分比</th><th scope="col">个人总积分:系统总积分</th></tr>';
		if(typeof(json['result'])!='undefined' && json['result']=='success'){
			var _tmp_class = 'dataTableRow';
			for(var i=0; i<json['data'].length; i++ ){
				_tmp_class = ( _tmp_class == 'dataTableRow' ? 'dataTableRow1' : 'dataTableRow');
				ul_inner_html += '<tr class="'+ _tmp_class +'"><td>'+json['data'][i]['jobNum']+'</td><td align="right">'+ json['data'][i]['orderAmount'] + '</td><td align="right">' + json['data'][i]['scoreCount'] + '</td><td align="right">' + json['data'][i]['averageValue'] +'</td><td align="right">' + json['data'][i]['personalDivisionCollective'] +'</td></tr>';
			}
		}
		ul_inner_html+='</table><li>';
		$('#ajaxResult').html(ul_inner_html);
		$('#ajaxResult td,th').attr('class','main');
	},'json');
}
</script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<style>
#ul_search li{float:left; display:block; margin:5px;}
</style>
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('assessment_score');
$list = $listrs->showRemark();
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">商务中心工作人员评分统计</td>
            <td class="pageHeading" align="right">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td class="main">
          <fieldset>
		  <legend style="text-align:left"> 日期工号积分比查询 </legend>
		  <div>
				<form id="ajaxSearch" onSubmit="ajax_search(); return false;">
				出团日期：
				<input type="text" name="time_start" class="textTime" onClick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" title="此日期也包括了留言给分的日期" />
				至
				<input type="text" name="time_end" class="textTime" onClick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" title="此日期也包括了留言给分的日期" />
				工号：<input name="job_num" style="ime-mode:disabled" type="text" class="text" title="多个工号用英文的“,”号隔开" />
				日期：<input type="text" name="add_time_start" class="textTime" onClick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" title="此日期也包括了留言给分的日期" />
				至
				<input type="text" name="add_time_end" class="textTime" onClick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" title="此日期也包括了留言给分的日期" />
				<button type="submit">查询</button>
				<ul id="ajaxResult">
				</ul>
				</form>
			</div>
		  </fieldset>
		  <!--search form start-->
          <fieldset>
          <legend align="left"> Search Module </legend>
          <?php echo tep_draw_form('form_search', 'assessment_score.php', tep_get_all_get_params(array('page','y','x', 'action')), 'get'); ?>
          
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><table border="0" cellspacing="0" cellpadding="0" style="margin:10px;">
                  
                  <?php
                  $s_option_display = 'none';
                  if($search_type =='orders'){
                  	$s_option_display = '';
                  }
                  ?>
                  <!-- 修改搜索（参考前台UI） start-->
                  <!-- by panda修改搜索（参考前台UI） end-->
                  <tr>
                  	<td class="main" align="left">
					<ul id="ul_search">
					<li>
					积分范围：
					<label><?= tep_draw_radio_field('score_type','1');?> 订单</label>
					<label><?= tep_draw_radio_field('score_type','2');?> 留言本</label>
					<label><?= tep_draw_radio_field('score_type','3');?> 常见问题（提问）</label>
					<label><?= tep_draw_radio_field('score_type','4');?> 常见问题（回复）</label>
					</li>
					<li>
					出团日期：<?= tep_draw_input_num_en_field('time_start','','class="textTime" onClick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" title="此日期也包括了留言给分的日期"');?>
					至
					<?= tep_draw_input_num_en_field('time_end','','class="textTime" onClick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" title="此日期也包括了留言给分的日期"');?>
					</li>
					<li>
					工号：<?= tep_draw_input_field('job_num','','style="ime-mode:disabled" title="只能输入一个工号"');?>
					</li>
					<li>
					订单号：<?= tep_draw_input_field('orders_id','','style="ime-mode:disabled" title="只能输入一个订单号"');?>
					</li>
					<li>
					日期：<input type="text" name="add_time_start" class="textTime" onClick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" title="此日期也包括了留言给分的日期" value="<?=$_GET['add_time_start']?>"/>
				至
				<input type="text" name="add_time_end" class="textTime" onClick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" title="此日期也包括了留言给分的日期" value="<?=$_GET['add_time_end']?>"/>
				</li>
					</ul>
					
					</td>
                  	</tr>
                  <tr>
                    <td class="main" align="left">&nbsp;
                    	<button name="Search" type="submit" style="font-size:14px">Search</button> <a href="<?= tep_href_link('assessment_score.php')?>">清除搜索条件</a>
                        <a href="<?= tep_href_link('assessment_score_report.php')?>">统计报表</a>
                        </td>
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
          <legend style="text-align:left"> Stats Results </legend>

        <?php if($_GET['score_type']=='1'){ //订单积分列表?>
		<table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="1" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" nowrap="nowrap">订单号</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">订单更新</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">订单短信</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">订单邮件</td>
                
                <td class="dataTableHeadingContent" nowrap="nowrap">订单积分总计</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">订单金额</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">操作</td>
              </tr>
			<?php
			for($i=0, $n=sizeof($list_datas['list']); $i<$n; $i++){
				$D = $list_datas['list'][$i];
			?>
			  <tr class="dataTableRow" style="cursor:auto; background-color:<?php echo $bg_color = ($bg_color == "#F0F0F0") ? '#ECFFEC':'#F0F0F0';?>">
                <td class="dataTableContent"><a target="_blank" href="<?php echo tep_href_link('edit_orders.php','oID='.$D['orders_id'].'&action=edit')?>"><?php echo $D['orders_id']?></a></td>
                <td class="dataTableContent"><?php echo $D['orders_updated_num'];$total1+=(int)$D['orders_updated_num'];?></td>
                <td class="dataTableContent"><?php echo $D['orders_sms_num'];$total2+=(int)$D['orders_sms_num'];?></td>
                <td class="dataTableContent"><?php echo $D['orders_email_num']; $total3+=(int)$D['orders_email_num'];?></td>
                <td class="dataTableContent"><?php echo $D['score_value_total'];$total4+=(int)$D['score_value_total'];?></td>
                <td class="dataTableContent">$<?php echo $my_tmp=number_format($D['value'], 2, '.', '');$total5+=(float)$my_tmp;?></td>
                <td class="dataTableContent">&nbsp;</td>
              </tr>
			  <?php }?>
			  <tr>
			  <td>统计</td>
			  <td><?php echo $total1;?></td>
			  <td><?php echo $total2;?></td>
			  <td><?php echo $total3;?></td>
			  <td><?php echo $total4;?></td>
			  <td><?php echo $total5;?></td>
			  <td></td>
			  </tr>
            </table></td>
          </tr>
		  <tr>
		  <td class="main">
		  <div><span>总积分：<?php echo $list_datas['statistical_score_value_total'];?></span> <span>订单总金额：$<?php echo number_format($list_datas['statistical_money'], 2, '.', '');?></span> <span>总积分/订单数=<?php echo round($list_datas['statistical_score_value_total']/max(1,$list_datas['sql_numrows']))?></span> <span>订单金额/订单总积分=$<?php echo number_format($list_datas['statistical_money']/max(1,$list_datas['statistical_score_value_total']),2,'.','');?></span></div>
		  </td>
		  </tr>
        </table>
		
        <?php }else if($_GET['score_type']=='2'){ //留言积分列表?>
		<table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="1" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" nowrap="nowrap">留言编号</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">留言人</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">积分</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">操作</td>
              </tr>
			<?php
			for($i=0, $n=sizeof($list_datas['list']); $i<$n; $i++){
				$D = $list_datas['list'][$i];
			?>
			  <tr class="dataTableRow" style="cursor:auto; background-color:<?php echo $bg_color = ($bg_color == "#F0F0F0") ? '#ECFFEC':'#F0F0F0';?>">
                <td class="dataTableContent"><?php echo $D['notebook_id']?></td>
                <td class="dataTableContent"><?php echo tep_get_admin_customer_name($D['sent_login_id']);?></td>
                <td class="dataTableContent"><?php echo $D['score_value_total']?></td>
                <td class="dataTableContent">&nbsp;</td>
              </tr>
			  <?php }?>
            </table></td>
          </tr>
        </table>
		<?php }else if($_GET['score_type']=='3'){//常见问题积分列表?>
		<table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="1" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" nowrap="nowrap">问题编号</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">提问人</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">积分</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">操作</td>
              </tr>
			<?php
			for($i=0, $n=sizeof($list_datas['list']); $i<$n; $i++){
				$D = $list_datas['list'][$i];
			?>
			  <tr class="dataTableRow" style="cursor:auto; background-color:<?php echo $bg_color = ($bg_color == "#F0F0F0") ? '#ECFFEC':'#F0F0F0';?>">
                <td class="dataTableContent"><?php echo $D['problem_id']?></td>
                <td class="dataTableContent"><?php echo tep_get_admin_customer_name($D['ower_id']);?></td>
                <td class="dataTableContent"><?php echo $D['score_value_total']?></td>
                <td class="dataTableContent">&nbsp;</td>
              </tr>
			  <?php }?>
            </table></td>
          </tr>
        </table>
		<?php }else if($_GET['score_type']=='4'){//常见问题回答的积分列表?>
		<table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="1" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" nowrap="nowrap">回答编号</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">回答人</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">积分</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">操作</td>
              </tr>
			<?php
			for($i=0, $n=sizeof($list_datas['list']); $i<$n; $i++){
				$D = $list_datas['list'][$i];
			?>
			  <tr class="dataTableRow" style="cursor:auto; background-color:<?php echo $bg_color = ($bg_color == "#F0F0F0") ? '#ECFFEC':'#F0F0F0';?>">
                <td class="dataTableContent"><?php echo $D['answer_id']?></td>
                <td class="dataTableContent"><?php echo tep_get_admin_customer_name($D['ower_id']);?></td>
                <td class="dataTableContent"><?php echo $D['score_value_total']?></td>
                <td class="dataTableContent">&nbsp;</td>
              </tr>
			  <?php }?>
            </table></td>
          </tr>
        </table>
		<?php	
		}
		?>
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