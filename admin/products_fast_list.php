<?php
require('includes/application_top.php');
// 备注添加删除
if($_GET['ajax']=="true"){
	include DIR_FS_CLASSES . 'Remark.class.php';
	$remark = new Remark('products_fast_list');
	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
}
//数据库查询
function __query($get){
	global $languages_id;
	$data = false;
	// 如果不选途经景点，则只需要这两个表。
	$tables = TABLE_PRODUCTS . " p, " . TABLE_PRODUCT_DESCRIPTION . " pd ";
	$where = "p.products_status='1' and p.products_id=pd.products_id and  pd.language_id = '" . (int)$languages_id . "' and p.agency_id!='".GLOBUS_AGENCY_ID."' and pd.products_name != '' ";
	if (isset($get['search']) && $get['search'] != '') {	//关键字搜索
		$search = tep_db_prepare_input($get['search']);
		$where .= "and (pd.products_name like '%" . tep_db_input($search) . "%'  or p.products_model like '%" . tep_db_input($search) . "%'or p.provider_tour_code like '%" . tep_db_input($search) . "%'  or p.provider_tour_code like '%" . tour_code_decode(tep_db_input($search)) . "%')";
	}

	if(isset($get['provider']) && $get['provider'] != '') {	//供应商搜索
		$where .= " and p.agency_id='".$get['provider']."'";
	}
	
	if (isset($get['days']) && $get['days'] != '') { //天数搜索
		$where .= " and products_durations_type<>1 and ";
		switch ($get['days']) {
			case '1': //一天以内
				$where .= " products_durations <='1'";
				break;
			case '2': // 2-3天
				$where .= " products_durations >= '2' and products_durations <= '3'";
				break;
			case '3': // 4-5天
				$where .= " products_durations >= '4' and products_durations <= '5'";
				break;
			case '4': // 6-7天
				$where .= " products_durations >= '6' and products_durations <= '7'";
				break;
			case '5'://8-10天
				$where .= " products_durations >= '8' and products_durations <= '10'";
				break;
			case '6'://10天以上
				$where .= " products_durations > '10'";
				break;
			
		}
	}
	
	// 出发城市
	if (isset($get['from_city']) && $get['from_city'] != '') {
		$where .= " and p.departure_city_id='" . (int)$get['from_city'] . "'";
	}
	
	// 结束城市
	if (isset($get['to_city']) && $get['to_city'] != '') {
		$where .= " and p.departure_end_city_id = '" . (int)$get['to_city'] . "'";
	}
	
	// 途经景点
	if (isset($get['via_attractions']) && $get['via_attractions'] != '') {
		$tables .= " , `products_destination` pdt ";
		$where .= " and p.products_id = pdt.products_id and pdt.city_id = '" . (int)$get['via_attractions'] ."'";
		
	}
	$sortorder = 'order by p.products_id ASC ';


	$select_products_query = "SELECT pd.*, p.* FROM " . $tables . " where " . $where . $sortorder."  ";

	$select_products_row = tep_db_query($select_products_query);
	
	while($products = tep_db_fetch_array($select_products_row)){
		$data[] = $products;
	}


	if($data!=false){
		foreach ((array)$data as $key => $row) {
			$array_products_id[$key]  = $row['products_id'];
			$array_products_name[$key] = $row['products_name'];
			$array_products_model[$key] = $row['products_model'];
			$array_products_model_encoded[$key] = $row['provider_tour_code'];
		}

		if($get["order"]=='decending') {
			$sortorder_direction = SORT_DESC;
		} else {
			$sortorder_direction = SORT_ASC;
		}
		$array_by_sort_name = $array_products_model;
		switch ($get["sort"]) {
			case 'tourname':
				array_multisort(array_map('strtolower',$array_products_name), $sortorder_direction, SORT_STRING, $data);
				break;
			case 'tourcode':
				array_multisort(array_map('strtolower',$array_products_model), $sortorder_direction, SORT_STRING, $data);
				break;
			case 'tourcodeencode':
				array_multisort(array_map('strtolower',$array_products_model_encoded), $sortorder_direction, SORT_STRING, $data);
				break;
			default:
				//array_multisort(array_map('strtolower',$array_products_model), $sortorder_direction, SORT_STRING, $data);
				break;
		}
	}
	return $data;
}

$data = __query($_GET);
//导出Excel文件
if($_GET['download']=='1' && $data){
	$filename = basename(__FILE__, '.php').'_'.date("YmdHis").'.xls';
	header("Content-type: text/html; charset=utf-8");	//用utf-8格式下载才行
	//header("Content-type: text/x-csv");
	header("Content-type: application/vnd.ms-excel");
	header("Content-Type: application/force-download");
	header("Content-Type: application/octet-stream");
	header("Content-Type: application/download");
	header("Content-Transfer-Encoding:binary");
	header("Content-Disposition: attachment;filename=".$filename);
    header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
    header('Expires:0');
    header('Pragma:public');
	header("HTTP/1.0 200 OK");
	header("Status: 200 OK");
	ob_start();
	
	//echo '"Provider Code","产品ID","产品编号","供应商ID","供应商代号","产品名称","旧站名称","天数","出发城市","结束城市","单人价","单人配房价","双人价","三人均价","四人均价","小孩价","途经景点"'."\n";
	echo '<table border="1" cellpadding="0" cellspacing="0">';
	echo '<tr><td>Provider Code</td><td>产品ID</td><td>产品编号</td><td>供应商ID</td><td>供应商代号</td><td>产品名称</td><td>旧站名称</td><td>天数</td><td>出发城市</td><td>结束城市</td><td>单人价</td><td>单人配房价</td><td>双人价</td><td>三人均价</td><td>四人均价</td><td>小孩价</td><td>途经景点</td></tr>';
	foreach($data as $key => $val){
		//echo '<tr><td>Provider Code</td><td>产品ID</td><td>产品编号</td><td>供应商ID</td><td>供应商代号</td><td>产品名称</td><td>旧站名称</td><td>天数</td><td>出发城市</td><td>结束城市</td><td>单人价</td><td>单人配房价</td><td>双人价</td><td>三人均价</td><td>四人均价</td><td>小孩价</td><td>途经景点</td></tr>';
		echo '
		<tr>
		<td>'.$data[$key]['provider_tour_code'].'</td>
		<td>'.$data[$key]['products_id'].'</td>
		<td>'.$data[$key]['products_model'].'</td>
		<td>'.$data[$key]['agency_id'].'</td>
		<td>'.$data[$key]['agency_id'].'</td>
		<td>'.$data[$key]['products_name'].'</td>
		<td>'.$data[$key]['products_name_provider'].'</td>
		<td>'.$data[$key]['products_durations'].($data[$key]['products_durations_type']=="1" ? '小时' : '').'</td>
		<td>'.implode(',',(array)tep_get_city_names($data[$key]['departure_city_id'])).'</td>
		<td>'.implode(',',(array)tep_get_city_names($data[$key]['departure_end_city_id'])).'</td>
		<td>'.($data[$key]['products_single']>0 ? $data[$key]['products_single'] : '&nbsp;').'</td>
		<td>'.($data[$key]['products_single_pu']>0 ? $data[$key]['products_single_pu'] : '&nbsp;').'</td>
		<td>'.($data[$key]['products_double']>0 ? $data[$key]['products_double'] : '&nbsp;').'</td>
		<td>'.($data[$key]['products_triple']>0 ? $data[$key]['products_triple'] : '&nbsp;').'</td>
		<td>'.($data[$key]['products_quadr']>0 ? $data[$key]['products_quadr'] : '&nbsp;').'</td>
		<td>'.($data[$key]['products_kids']>0 ? $data[$key]['products_kids'] : '&nbsp;').'</td>
		<td>'.implode(',',(array)tep_get_city_names(implode(',',(array)tep_get_product_destination_city_ids($data[$key]['products_id'])))).'</td>
		</tr>';
	}
	echo '</table>';
	echo iconv('gb2312','utf-8//IGNORE',ob_get_clean());
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
<script type="text/javascript" src="includes/javascript/categories.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<style type="text/css">
.highlight_word{
    background-color: pink;
}
</style> 
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<?php
		//echo $login_id;
		include DIR_FS_CLASSES . 'Remark.class.php';
		$listrs = new Remark('products_fast_list');
		$list = $listrs->showRemark();
		?>
<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top">
	
	<table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">产品快速查询</td>
            <td align="right" class="main">&nbsp;</td>
          </tr> 
		  </table>
		<fieldset>
		<legend align="left"> 搜索区 </legend>
		  <?php 
		  echo tep_draw_form('frmtourcodesearch','products_fast_list.php','','get');
			?>
			
			<table border="0" cellspacing="2" cellpadding="2">
			  <tr>
				 <td  class="smallText">供应商：</td>
				 <td class="smallText"><?php

				 $provider_array = array(array('id' => '', 'text' => TEXT_NONE));
				 $provider_query = tep_db_query("select agency_id,agency_name from " . TABLE_TRAVEL_AGENCY . " order by agency_name");
				 while($provider_result = tep_db_fetch_array($provider_query))
				 {
				 	$provider_array[] = array('id' => $provider_result['agency_id'],
				 	'text' => $provider_result['agency_name']);
				 }
					 echo tep_draw_pull_down_menu('provider', $provider_array, $_GET['provider'], 'style="width:200px; " '); ?>
					</td>
					<td class="smallText"  rowspan="5">途经景点：</td>
					<td rowspan="5">
					<?php 
					
					$sql = " SELECT pd.`city_id`,tc.`city` FROM `products_destination` pd, `tour_city` tc where pd.city_id=tc.city_id GROUP BY pd.`city_id` ORDER BY tc.city desc ";
					$result = tep_db_query($sql);
					$mydata = array(array('id'=>'','text'=>'请选择途经景点'));
					while ($row = tep_db_fetch_array($result)) {
						$mydata[(int)$row['city_id']] = array(
							'id'   => (int)$row['city_id'],
							'text' => $row['city'] //tep_get_city_name((int)$row['city_id'])
						);
					}
					echo tep_draw_pull_down_menu('via_attractions', $mydata,'',' size=10 id="via_attractions"');
					?><input type="text" id="key_search" onKeyUp="_jsSearch(this)"/>输入途经景点
					<script type="text/javascript">
					function _jsSearch(obj){
						var word = obj.value;
						if (!word) return;
						var slt = document.getElementById('via_attractions');
						var len = slt.options.length;
						var stats = false;
						for(var i=0; i < len; i++) {
							var regtxt = "^" + word;
							var reg = new RegExp(regtxt,'img');
							var str = slt.options[i].text;
							if ( reg.exec(str) != null) {
								slt.options[i].selected = true;
								stats = true;
								return;
							}
						}
						if (stats == false) {
							for(var i=0; i < len; i++) {
								var str = slt.options[i].text;
								if ( str.indexOf(word) != -1) {
									slt.options[i].selected = true;
									return;
								}
							}
						}
					}
					</script>
					</td>
				</tr>
				<tr>
					<td class="smallText">天数：</td>
					<td><?php 
					$days_data = array(
						array('id'=>'','text'=>'选择持续天数'),
						array('id'=>'1','text'=>'1天以内'),
						array('id'=>'2','text'=>'2-3天'),
						array('id'=>'3','text'=>'4-5天'),
						array('id'=>'4','text'=>'6-7天'),
						array('id'=>'5','text'=>'8-10天'),
						array('id'=>'6','text'=>'10天及以上'),
					);
					echo tep_draw_pull_down_menu('days', $days_data);
					?></td>
				</tr>
				<tr>
					<td class="smallText">出发城市：</td>
					<td><?php 
					$sql = "select distinct `departure_city_id` from `products` where `departure_city_id` <> '' and `departure_city_id` is not null";
					$result = tep_db_query($sql);
					$mydata = array(array('id'=>'','text'=>'请选择出发城市'));
					while ($row = tep_db_fetch_array($result)) {
						$ids = explode(',',$row['departure_city_id']);
						foreach ($ids as $key => $val) {
							$mydata[(int)$val] = array(
								'id'   => (int)$val,
								'text' => tep_get_city_name((int)$val)
							);
						}
					}
					echo tep_draw_pull_down_menu('from_city', $mydata);
					?></td>
				</tr>
				<tr>
					<td class="smallText">结束城市：</td>
					<td>
					<?php 
					
					$sql = "select distinct `departure_end_city_id` from `products` where `departure_end_city_id` <> '' and `departure_end_city_id` is not null";
					$result = tep_db_query($sql);
					$mydata = array(array('id'=>'','text'=>'请选择结束城市'));
					while ($row = tep_db_fetch_array($result)) {
						$ids = explode(',',$row['departure_end_city_id']);
						foreach ($ids as $key => $val) {
							$mydata[(int)$val] = array(
								'id'   => (int)$val,
								'text' => tep_get_city_name((int)$val)
							);
						}
					}
					echo tep_draw_pull_down_menu('to_city', $mydata);
					?>
					</td>
				</tr>
			  <tr>
				<td class="smallText" nowrap="nowrap">关键词：</td>
				<td><?php echo tep_draw_input_field('search');?></td>				
			  </tr>	
			  <tr>
				<td class="smallText" nowrap="nowrap">&nbsp;</td>				
				<td colspan="3"> 			
				<input type="submit" name="Serbtn" value="Search">
				<a href="<?= tep_href_link('products_fast_list.php');?>">清除搜索选项</a>
				<a href="<?= tep_href_link('products_fast_list.php','download=1&'.tep_get_all_get_params(array('page','y','x', 'action', 'download')));?>">下载到本地</a>
				</td>
			  </tr>
			  <tr>
			  	<td class="smallText" nowrap="nowrap">&nbsp;</td>
			  	<td class="smallText" colspan="3">
				<b>
				重要说明：
				<br>
				出团日期和价格均请到前台查看，这里列出的价格只是标准价，并不是具体的价格。
				<br>
				出团日期的计算程序比较复杂，目前只有前台的程序可以看到整合后的日期！
				</b>
				</td>
		  	</tr>			 
			</table>

			<?php
			echo '</form>';
			?>
		</fieldset>
		
		<fieldset>
		<legend align="left"> 列表区 </legend>
		<table border="0" width="100%" cellspacing="6" cellpadding="0">
          <tr>
            <td valign="top">
			
			<table border="0" width="100%" cellspacing="0" cellpadding="5">
              <tr class="dataTableHeadingRow">
              	<td class="dataTableHeadingContent" nowrap>
				Provider Code
				<?php
				//$HEADING_TORUCODE = 'Encoded Tour Code';
				$HEADING_TORUCODE = '';
				$HEADING_TORUCODE .= '<a href="' . $_SERVER['PHP_SELF'] . '?sort=tourcodeencode&order=ascending'.(isset($_GET['search']) ? '&search=' . $_GET['search'] . '' : '').'">';
				$HEADING_TORUCODE .= '&nbsp;<img src="images/arrow_up.gif" border="0"></a>';
				$HEADING_TORUCODE .= '<a href="' . $_SERVER['PHP_SELF'] . '?sort=tourcodeencode&order=decending'.(isset($_GET['search']) ? '&search=' . $_GET['search'] . '' : '').'">';
				$HEADING_TORUCODE .= '&nbsp;<img src="images/arrow_down.gif" border="0"></a>';
				echo $HEADING_TORUCODE;
				  ?>
				</td>
				<td class="dataTableHeadingContent" nowrap>产品ID</td>
              	<td class="dataTableHeadingContent" nowrap>
				产品编号
				<?php
				//$HEADING_TORUCODE = 'Tour Code';
				$HEADING_TORUCODE = '';
				$HEADING_TORUCODE .= '<a href="' . $_SERVER['PHP_SELF'] . '?sort=tourcode&order=ascending'.(isset($_GET['search']) ? '&search=' . $_GET['search'] . '' : '').'">';
				$HEADING_TORUCODE .= '&nbsp;<img src="images/arrow_up.gif" border="0"></a>';
				$HEADING_TORUCODE .= '<a href="' . $_SERVER['PHP_SELF'] . '?sort=tourcode&order=decending'.(isset($_GET['search']) ? '&search=' . $_GET['search'] . '' : '').'">';
				$HEADING_TORUCODE .= '&nbsp;<img src="images/arrow_down.gif" border="0"></a>';
				echo $HEADING_TORUCODE;
				?>
				</td>
              	<td class="dataTableHeadingContent" nowrap>供应商ID</td>
              	<td class="dataTableHeadingContent" nowrap>供应商代号</td>
                <td class="dataTableHeadingContent" nowrap>
				产品名称
				<?php
				$HEADING_TORUNAME = '';
				$HEADING_TORUNAME .= '<a href="' . $_SERVER['PHP_SELF'] . '?sort=tourname&order=ascending'.(isset($_GET['search']) ? '&search=' . $_GET['search'] . '' : '').'">';
				$HEADING_TORUNAME .= '&nbsp;<img src="images/arrow_up.gif" border="0"></a>';
				$HEADING_TORUNAME .= '<a href="' . $_SERVER['PHP_SELF'] . '?sort=tourname&order=decending'.(isset($_GET['search']) ? '&search=' . $_GET['search'] . '' : '').'">';
				$HEADING_TORUNAME .= '&nbsp;<img src="images/arrow_down.gif" border="0"></a>';
				echo $HEADING_TORUNAME;
				  ?>
				</td>
                <td class="dataTableHeadingContent" nowrap>旧站名称</td>
                <td class="dataTableHeadingContent" nowrap>天数</td>
                <td class="dataTableHeadingContent" nowrap>出发城市</td>
                <td class="dataTableHeadingContent" nowrap>结束城市</td>
                <td class="dataTableHeadingContent" nowrap>出团时间</td>
                <td class="dataTableHeadingContent" nowrap>单人价</td>
                <td class="dataTableHeadingContent" nowrap>单人配房价</td>
                <td class="dataTableHeadingContent" nowrap>双人价</td>
                <td class="dataTableHeadingContent" nowrap>三人均价</td>
                <td class="dataTableHeadingContent" nowrap>四人均价</td>
                <td class="dataTableHeadingContent" nowrap>小孩价</td>
                <td class="dataTableHeadingContent" nowrap>途经景点</td>
              </tr>
<?php

if(is_array($data)) {
	$iic = 0;
	foreach($data as $key => $val){


			$calss_row = 'class="dataTableRow"';
				?>
								<tr <?php echo $calss_row;?> onMouseOut="rowOutEffect(this)" onMouseOver="rowOverEffect(this)">
									<td class="dataTableContent" nowrap>
									<?php echo '<a target="_blank" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . tep_get_products_catagory_id($data[$key]['products_id']) . '&pID=' . $data[$key]['products_id'].'&action=new_product') . '">' .  highlightWords($data[$key]['provider_tour_code'],$search) . '</a>' ?>
									</td>
									<td class="dataTableContent" nowrap>
									<?= $data[$key]['products_id']?>
									</td>
									<td class="dataTableContent" nowrap>
									<?php echo '<a target="_blank" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . tep_get_products_catagory_id($data[$key]['products_id']) . '&pID=' . $data[$key]['products_id'].'&action=new_product') . '">' . highlightWords($data[$key]['products_model'],$search) . '</a>' ?>
									</td>
									<td class="dataTableContent" nowrap><?= $data[$key]['agency_id']?></td>
									<td class="dataTableContent" nowrap><?= tep_get_agency_code($data[$key]['agency_id']);?></td>
								<td class="dataTableContent" nowrap><?php echo '<a target="_blank" href="' . tep_catalog_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $data[$key]['products_id']) . '">' . highlightWords(tep_db_prepare_input($data[$key]['products_name']),$search).'</a>';?>
								</td>

								<td class="dataTableContent" nowrap><?= $data[$key]['products_name_provider']?>
								</td>
								<td class="dataTableContent" nowrap>
								<?php
								echo $data[$key]['products_durations'];
								if($data[$key]['products_durations_type']=="1"){
									echo ' 小时';
								}
								
								?>
								</td>
								<td class="dataTableContent" nowrap>
								<?php
								foreach((array)$data[$key]['departure_city_id'] as $sid){
									echo tep_get_city_name((int)$sid).' ';
								}
								?>
								</td>
								<td class="dataTableContent" nowrap>
								<?php
								foreach((array)$data[$key]['departure_end_city_id'] as $eid){
									echo tep_get_city_name((int)$eid).' ';
								}
								?>														
								</td>
								<td class="dataTableContent" nowrap>
								<a target="_blank" href="<?= tep_catalog_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $data[$key]['products_id'])?>">到前台查看</a>
								</td>
								<td class="dataTableContent" nowrap><?= $data[$key]['products_single']>0 ? $data[$key]['products_single'] : '&nbsp;';?></td>
								<td class="dataTableContent" nowrap><?= $data[$key]['products_single_pu']>0 ? $data[$key]['products_single_pu'] : '&nbsp;';?></td>
								<td class="dataTableContent" nowrap><?= $data[$key]['products_double']>0 ? $data[$key]['products_double'] : '&nbsp;';?></td>
								<td class="dataTableContent" nowrap><?= $data[$key]['products_triple']>0 ? $data[$key]['products_triple'] : '&nbsp;';?></td>
								<td class="dataTableContent" nowrap><?= $data[$key]['products_quadr']>0 ? $data[$key]['products_quadr'] : '&nbsp;';?></td>
								<td class="dataTableContent" nowrap><?= $data[$key]['products_kids']>0 ? $data[$key]['products_kids'] : '&nbsp;';?></td>
								<td class="dataTableContent" nowrap>
								<?php 
								$city_ids = tep_get_product_destination_city_ids($data[$key]['products_id']);
								$city_str = false;
								foreach ((array)$city_ids as $did){
									$city_str[] = tep_get_city_name($did);
								}
								echo implode(',', $city_str);
								?>
								</td>               
							  	</tr>
				<?php
				$iic++;


	}//foreach

}
// echo $iic++;
?>
              
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
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

