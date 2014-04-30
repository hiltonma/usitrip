<?php
/*
  $Id: languages.php,v 1.1.1.1 2004/03/04 23:38:39 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  // 备注添加删除
  if($_GET['ajax']=="true"){
  	include DIR_FS_CLASSES . 'Remark.class.php';
  	$remark = new Remark('vote_system');
  	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
  }
  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'setstate':
	    if((int)$_GET['v_s_id']){
			$v_s_state = (int)$_GET['v_s_state'];
			tep_db_query('UPDATE `vote_system` SET `v_s_state` = "'.$v_s_state.'" WHERE `v_s_id` = "'.$_GET['v_s_id'].'"  ');
			$messageStack->add_session('状态更新成功！', 'success');
		}
		tep_redirect(tep_href_link('vote_system.php', 'page=' . $HTTP_GET_VARS['page'] . '&v_s_id=' . $_GET['v_s_id'].(isset($HTTP_GET_VARS['sortorder']) ? '&sortorder=' . $HTTP_GET_VARS['sortorder'] . '' : '')));

		break;
		
	  case 'insert':
		$v_s_title = tep_db_prepare_input($HTTP_POST_VARS['v_s_title']);
	  	$v_s_description = tep_db_prepare_input($HTTP_POST_VARS['v_s_description']);
	  	$v_s_sort = (int)($HTTP_POST_VARS['v_s_sort']);
		$v_s_start_date = ($HTTP_POST_VARS['v_s_start_date']);
		$v_s_end_date = ($HTTP_POST_VARS['v_s_end_date']);
		$v_s_date = date('Y-m-d H:i:s');
		$v_s_points = (int)$_POST['v_s_points'];
		$v_s_repeat = (int)$_POST['v_s_repeat'];
		
		if(tep_not_null($v_s_title)){
			$sql_data_array = array('v_s_title' => $v_s_title,
									'v_s_description' => $v_s_description,
									'v_s_sort' => $v_s_sort,
									'v_s_start_date' => $v_s_start_date,
									'v_s_end_date' => $v_s_end_date,
									'v_s_date' => $v_s_date,
									'v_s_state' => $v_s_state,
									'v_s_points' => $v_s_points,
									'v_s_repeat' => $v_s_repeat
									);
			tep_db_perform('vote_system', $sql_data_array);
			$v_s_id = tep_db_insert_id();
			//写入调查项目到 vote_system_item
			foreach((array)$_POST['v_s_i_title'] as $key => $value){
				if(tep_not_null($value)){
					$sql_data_array = array('v_s_i_title' => $value,
											'v_s_id' => $v_s_id,
											'v_s_i_type' => (int)$_POST['v_s_i_type'][$key],
											'v_s_i_sort' => (int)$_POST['v_s_i_sort'][$key]
											);
					tep_db_perform('vote_system_item', $sql_data_array);
					$v_s_i_id = tep_db_insert_id();
					//写入调查项目的答案选项到vote_system_item_options
					if($_POST['v_s_i_type'][$key] !='2'){
						foreach((array)$_POST['v_s_i_o_title'][$key] as $key_o => $value_o){
							if(tep_not_null($value_o)){
								$sql_data_array = array('v_s_i_o_title' => $value_o,
														'v_s_i_id' => $v_s_i_id
														);
								tep_db_perform('vote_system_item_options', $sql_data_array);
							}
						}
					}
				}
			}
			
			$messageStack->add_session('新调查添加成功！', 'success');
			tep_redirect(tep_href_link('vote_system.php'));
		}

		
        break;
      case 'save':
		$v_s_title = tep_db_prepare_input($HTTP_POST_VARS['v_s_title']);
	  	$v_s_description = tep_db_prepare_input($HTTP_POST_VARS['v_s_description']);
	  	$v_s_sort = (int)($HTTP_POST_VARS['v_s_sort']);
		$v_s_start_date = ($HTTP_POST_VARS['v_s_start_date']);
		$v_s_end_date = ($HTTP_POST_VARS['v_s_end_date']);
		$v_s_date = date('Y-m-d H:i:s');
		$v_s_points = (int)$_POST['v_s_points'];
		$v_s_repeat = (int)$_POST['v_s_repeat'];

		if(tep_not_null($v_s_title)){
			$sql_data_array = array('v_s_title' => $v_s_title,
									'v_s_description' => $v_s_description,
									'v_s_sort' => $v_s_sort,
									'v_s_start_date' => $v_s_start_date,
									'v_s_end_date' => $v_s_end_date,
									'v_s_date' => $v_s_date,
									'v_s_state' => $v_s_state,
									'v_s_points' => $v_s_points,
									'v_s_repeat' => $v_s_repeat
									);
			tep_db_perform('vote_system', $sql_data_array,'update', ' v_s_id = "'.(int)$_POST['v_s_id'].'"');
		
			//更新调查选项信息
			$v_s_id = (int)$_POST['v_s_id'];
			
			foreach((array)$_POST['v_s_i_title'] as $key => $value){
				if(tep_not_null($value) && (int)$_POST['v_s_i_id'][$key]){
					$sql_data_array = array('v_s_i_title' => $value,
											//'v_s_id' => $v_s_id,
											'v_s_i_type' => (int)$_POST['v_s_i_type'][$key],
											'v_s_i_sort' => (int)$_POST['v_s_i_sort'][$key]
											);
					tep_db_perform('vote_system_item', $sql_data_array,'update', ' v_s_i_id ="'.(int)$_POST['v_s_i_id'][$key].'" ');
					
					/*//写入调查项目的答案选项到vote_system_item_options
					if($_POST['v_s_i_type'][$key] !='2'){
						foreach((array)$_POST['v_s_i_o_title'][$key] as $key_o => $value_o){
							if(tep_not_null($value_o)){
								$sql_data_array = array('v_s_i_o_title' => $value_o,
														'v_s_i_id' => $v_s_i_id
														);
								tep_db_perform('vote_system_item_options', $sql_data_array);
							}
						}
					}
					*/
					
				}elseif(tep_not_null($value) && !(int)$_POST['v_s_i_id'][$key]){
					$sql_data_array = array('v_s_i_title' => $value,
											'v_s_id' => $v_s_id,
											'v_s_i_type' => (int)$_POST['v_s_i_type'][$key],
											'v_s_i_sort' => (int)$_POST['v_s_i_sort'][$key]
											);
					tep_db_perform('vote_system_item', $sql_data_array);
					$v_s_i_id = tep_db_insert_id();
					//写入调查项目的答案选项到vote_system_item_options
					if($_POST['v_s_i_type'][$key] !='2'){
						foreach((array)$_POST['v_s_i_o_title'][$key] as $key_o => $value_o){
							if(tep_not_null($value_o)){
								$sql_data_array = array('v_s_i_o_title' => $value_o,
														'v_s_i_id' => $v_s_i_id
														);
								tep_db_perform('vote_system_item_options', $sql_data_array);
							}
						}
					}

				}
			}
			
			
		}
		
		$messageStack->add_session('调查更新成功！', 'success');
		tep_redirect(tep_href_link('vote_system.php'));

        break;
      case 'deleteconfirm':
        if((int)$_GET['v_s_id']){
			$vsi_sql = tep_db_query('SELECT v_s_i_id FROM `vote_system_item` WHERE `v_s_id` = "'. (int)$_GET['v_s_id'].'" ');
			while($vsi_rows = tep_db_fetch_array($vsi_sql)){
				tep_db_query('DELETE FROM `vote_system_item_options` WHERE `v_s_i_id` = "'. (int)$vsi_rows['v_s_i_id'].'" ');
			} 
			tep_db_query('DELETE FROM `vote_system_item` WHERE `v_s_id` = "'. (int)$_GET['v_s_id'].'" ');
			tep_db_query('DELETE FROM `vote_system_results` WHERE `v_s_id` = "'. (int)$_GET['v_s_id'].'" ');
			tep_db_query('DELETE FROM `vote_system` WHERE `v_s_id` = "'. (int)$_GET['v_s_id'].'" ');
			
			$messageStack->add_session('调查删除成功！', 'success');
			tep_redirect(tep_href_link('vote_system.php'));
		}
		break;
    }
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
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
<?php
if($_GET['action']=='new'){
	echo 'var item_num = 0;';
}elseif($_GET['action']=='edit'){
	$item_sql = tep_db_query('SELECT count(*) as total FROM `vote_system_item` WHERE v_s_id="'.(int)$_GET['v_s_id'].'" ');
	$item_total = tep_db_result($item_sql,"0","total");
	
	echo 'var item_num = '.$item_total.';';
}
?>

function add_item(){
	var vote_item = document.getElementById('vote_item');
	var url = "<?php echo preg_replace($p,$r,tep_href_link('vote_system_item_ajax.php','item_num=')) ?>"+ item_num ;
	ajax.open("GET", url, true);
	ajax.send(null); 
	
	ajax.onreadystatechange = function() { 
		if (ajax.readyState == 4 && ajax.status == 200 ) { 
			vote_item.innerHTML = vote_item.innerHTML + ajax.responseText;
			item_num ++;
			
		}		
	}
}

function edit_item(){
	var vote_item = document.getElementById('vote_item');
	var url = "<?php echo preg_replace($p,$r,tep_href_link('vote_system_item_edit_ajax.php','v_s_id='.(int)$_GET['v_s_id'])) ?>";
	ajax.open("GET", url, true);
	ajax.send(null); 
	
	ajax.onreadystatechange = function() { 
		if (ajax.readyState == 4 && ajax.status == 200 ) { 
			vote_item.innerHTML = vote_item.innerHTML + ajax.responseText;
		}		
	}
}
function delele_item_ajax(item_id,v_s_i_id){
	if(v_s_i_id>0){
		var vote_item = document.getElementById(item_id);
		var url = "<?php echo preg_replace($p,$r,tep_href_link('vote_system_item_edit_ajax.php','action=del&v_s_i_id='))?>" + v_s_i_id;
		ajax.open("GET", url, true);
		ajax.send(null); 
		
		ajax.onreadystatechange = function() { 
			if (ajax.readyState == 4 && ajax.status == 200 ) { 
				vote_item.parentNode.removeChild(vote_item);
			}		
		}
	}
}

<?php
if($_GET['action']=='edit' && (int)$_GET['v_s_id']){
	$edit_load = 'edit_item();';
}else{
	$edit_load ='';
}
?>

function delele_item(obj){
	obj.innerHTML = '';
}

function set_item_option(id,int){
	var id = document.getElementById(id);
	id.value = int;
	
}

function get_options_total(total_id, type_id, output_id, item_num){
	var total_id = document.getElementById(total_id);
	var type_id = document.getElementById(type_id);
	var output_id = document.getElementById(output_id);
	var output_html = '';
	for(i=0; i < total_id.value; i++){
		switch(type_id.value){
			case '0': output_html += '<input name="radio_tmp" type="radio" disabled><?php echo tep_draw_input_field('v_s_i_o_title[\'+item_num+\'][]', '', 'size="10" ');?><br>' ;
			  break;
			case '1': output_html += '<input name="checkbox_tmp" type="checkbox" disabled><?php echo tep_draw_input_field('v_s_i_o_title[\'+item_num+\'][]', '', 'size="10" ');?><br>' ;
			  break;
			case '2': output_html = '<input name="text_tmp" type="text" disabled><br>' ;
			  break;
			default:  output_html += '' ;
			
		}
	}
	output_id.innerHTML = output_html;
}
</script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();<?php echo $edit_load;?>">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('vote_system');
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
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo TOURS_TOTE_SYSTEM; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellpadding="0" cellspacing="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent">调查编号</td>
				<td class="dataTableHeadingContent">调查名称</td>
				<td class="dataTableHeadingContent">给予积分</td>
				<td class="dataTableHeadingContent">可否重复</td>
                <td class="dataTableHeadingContent">开始日期</td>
                <td class="dataTableHeadingContent">结束日期</td>
                <td class="dataTableHeadingContent">排序</td>
                <td class="dataTableHeadingContent">状态</td>
              </tr>
<?php
  $vote_system_query_raw = "select * from vote_system order by v_s_sort";
  $vote_system_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $vote_system_query_raw, $vote_system_query_numrows);
  $vote_system_query = tep_db_query($vote_system_query_raw);

  while ($vote_system = tep_db_fetch_array($vote_system_query)) {
    if (isset($HTTP_GET_VARS['v_s_id']) && !isset($VInfo) && substr($action, 0, 3) != 'new' && $HTTP_GET_VARS['v_s_id'] == $vote_system['v_s_id']) {
      $VInfo = new objectInfo($vote_system);
    }

    if (isset($VInfo) && is_object($VInfo) && $VInfo->v_s_id == $vote_system['v_s_id'] && $vote_system['v_s_id'] ==$HTTP_GET_VARS['v_s_id']) {
      echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link('vote_system.php', 'page=' . $HTTP_GET_VARS['page'] . '&action=edit&v_s_id='.$VInfo->v_s_id) . '\'">' . "\n";
    } else {
      echo '                  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link('vote_system.php', 'page=' . $HTTP_GET_VARS['page'] .'&v_s_id='.$vote_system['v_s_id']). '\'">' . "\n";
    }

?>
                <td class="dataTableContent" align="left"><?php echo str_replace('-','',$vote_system['v_s_start_date']).$vote_system['v_s_id']?></td>
				<td class="dataTableContent" align="left"><?php echo $vote_system['v_s_title']?></td>
                <td class="dataTableContent" align="left"><?php echo $vote_system['v_s_points']?></td>
				<td class="dataTableContent" align="left"><?php echo ((int)$vote_system['v_s_repeat']) ? '可以':'否';?></td>
				<td class="dataTableContent" align="left"><?php echo $vote_system['v_s_start_date']?></td>
                <td class="dataTableContent" align="left"><?php echo $vote_system['v_s_end_date']?></td>
                <td class="dataTableContent" align="left"><?php echo $vote_system['v_s_sort']?></td>
                <td class="dataTableContent" align="left">
				
				<?php
					  if ($vote_system['v_s_state'] == '1') {
						echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link('vote_system.php', 'action=setstate&v_s_state=0&v_s_id=' . $vote_system['v_s_id'].(isset($HTTP_GET_VARS['page']) ? '&page=' . $HTTP_GET_VARS['page'] . '' : '').(isset($HTTP_GET_VARS['sortorder']) ? '&sortorder=' . $HTTP_GET_VARS['sortorder'] . '' : '')) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
					  } else {
						echo '<a href="' . tep_href_link('vote_system.php', 'action=setstate&v_s_state=1&v_s_id=' . $vote_system['v_s_id'] .(isset($HTTP_GET_VARS['page']) ? '&page=' . $HTTP_GET_VARS['page'] . '' : ''). (isset($HTTP_GET_VARS['sortorder']) ? '&sortorder=' . $HTTP_GET_VARS['sortorder'] . '' : '')) .'">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
					  }
				?>
				</td>
				
                </tr>
<?php
  }
?>
              <tr>
                <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $vote_system_split->display_count($vote_system_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_VOTE); ?></td>
                    <td class="smallText" align="right"><?php echo $vote_system_split->display_links($vote_system_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></td>
                  </tr>
<?php
  if (empty($action)) {
?>
                  <tr>
                    <td align="right" colspan="2"><?php echo '<a href="' . tep_href_link('vote_system.php', 'page=' . $HTTP_GET_VARS['page'] .'&action=new') . '">' . tep_image_button('button_new_vote.gif', IMAGE_NEW_LANGUAGE) . '</a>'; ?></td>
                  </tr>
<?php
  }
?>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'new':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_VOTE . '</b>');

      $contents = array('form' => tep_draw_form('vote', 'vote_system.php', 'action=insert'));
      $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_INFO_VOTE_NAME . '<br>' . tep_draw_input_field('v_s_title','','size="34"',true));
      $contents[] = array('text' => '<br>' . TEXT_INFO_VOTE_DESCRIPTION . '<br>' . tep_draw_textarea_field('v_s_description','10','40','5'));
      $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_SORT_ORDER . '<br>' . tep_draw_input_field('v_s_sort','','size="4"'));
      $contents[] = array('text' => '<br>给予积分<br>' . tep_draw_input_field('v_s_points','','size="6"'));
      $contents[] = array('text' => '<br>' . TEXT_INFO_VOTE_START_DATE . '<br>' . tep_draw_input_field('v_s_start_date'));
      $contents[] = array('text' => '<br>' . TEXT_INFO_VOTE_END_DATE . '<br>' . tep_draw_input_field('v_s_end_date'));
      $contents[] = array('text' => '<br>状态<br>' . tep_draw_radio_field('v_s_state', '0'). '未激活&nbsp;'.tep_draw_radio_field('v_s_state', '1').'激活');

	  $contents[] = array('text' => '<br>用户可以重复提交调查吗？<br>' . tep_draw_radio_field('v_s_repeat', '0'). '否&nbsp;'.tep_draw_radio_field('v_s_repeat', '1').'可以');

	  
      $contents[] = array('text' => '<div id="vote_item"></div>');
      $contents[] = array('text' => '<br><a href="javascript:add_item();">'.tep_image_button('button_add_vote_item.gif','添加调查选项').'</a>');

     
	  $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_insert.gif', IMAGE_INSERT) . ' <a href="' . tep_href_link('vote_system.php', 'page=' . $HTTP_GET_VARS['page'] . '&v_s_id=' . $HTTP_GET_VARS['v_s_id']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_VOTE . '</b>');

      $contents = array('form' => tep_draw_form('vote', 'vote_system.php', 'page=' . $HTTP_GET_VARS['page'] . '&action=save'));
      $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_INFO_VOTE_NAME . '<br>' . tep_draw_input_field('v_s_title', $VInfo->v_s_title,'size="34"'). tep_draw_hidden_field('v_s_id',$VInfo->v_s_id));
      $contents[] = array('text' => '<br>' . TEXT_INFO_VOTE_DESCRIPTION . '<br>' . tep_draw_textarea_field('v_s_description','10','40','5', $VInfo->v_s_description));
      $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_SORT_ORDER . '<br>' . tep_draw_input_field('v_s_sort', $VInfo->v_s_sort,'size="4"'));
	  $contents[] = array('text' => '<br>给予积分<br>' . tep_draw_input_field('v_s_points',$VInfo->v_s_points,'size="6"'));
      $contents[] = array('text' => '<br>' . TEXT_INFO_VOTE_START_DATE . '<br>' . tep_draw_input_field('v_s_start_date',$VInfo->v_s_start_date));
      $contents[] = array('text' => '<br>' . TEXT_INFO_VOTE_END_DATE . '<br>' . tep_draw_input_field('v_s_end_date',$VInfo->v_s_end_date));
      
	  if($VInfo->v_s_state=='1'){
	  	$contents[] = array('text' => '<br>状态<br>' . tep_draw_radio_field('v_s_state', '0'). '未激活&nbsp;'.tep_draw_radio_field('v_s_state', '1',true).'激活');
      }else{
	  	$contents[] = array('text' => '<br>状态<br>' . tep_draw_radio_field('v_s_state', '0',true). '未激活&nbsp;'.tep_draw_radio_field('v_s_state', '1').'激活');
	  }
	  
	  if($VInfo->v_s_repeat=='1'){
	  	$contents[] = array('text' => '<br>用户可以重复提交调查吗？<br>' . tep_draw_radio_field('v_s_repeat', '0'). '否&nbsp;'.tep_draw_radio_field('v_s_repeat', '1',true).'可以');
      }else{
	  	$contents[] = array('text' => '<br>用户可以重复提交调查吗？<br>' . tep_draw_radio_field('v_s_repeat', '0',true). '否&nbsp;'.tep_draw_radio_field('v_s_repeat', '1').'可以');
	  }
	  
      $contents[] = array('text' => '<div id="vote_item"></div>');
      $contents[] = array('text' => '<br><a href="javascript:add_item();">'.tep_image_button('button_add_vote_item.gif','添加调查选项').'</a>');

	  $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . tep_href_link('vote_system.php', 'page=' . $HTTP_GET_VARS['page']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    
	case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_VOTE . '</b>');
		
		if($login_groups_id=='1'){
			$remove_language = true;
		}
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $VInfo->v_s_title . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br>' . (($remove_language) ? '<a href="' . tep_href_link('vote_system.php', 'page=' . $HTTP_GET_VARS['page'] . '&v_s_id=' . $HTTP_GET_VARS['v_s_id'] . '&action=deleteconfirm') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>' : '') . ' <a href="' . tep_href_link('vote_system.php', 'page=' . $HTTP_GET_VARS['page']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (is_object($VInfo)) {
        $heading[] = array('text' => '<b>' . $VInfo->v_s_title . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link('vote_system.php', 'page=' . $HTTP_GET_VARS['page'] . '&v_s_id=' . $HTTP_GET_VARS['v_s_id'] . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link('vote_system.php', 'page=' . $HTTP_GET_VARS['page'] .'&v_s_id=' . $HTTP_GET_VARS['v_s_id'] . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> <a href="' . HTTP_SERVER.'/vote_system.php?v_s_id=' . $HTTP_GET_VARS['v_s_id'] . '&action=preview' . '" target="_blank">' . tep_image_button('button_preview.gif', 'preview') . '</a> ');
        $contents[] = array('text' => '<br>' . TEXT_INFO_VOTE_NAME . '：' . $VInfo->v_s_title);
        $contents[] = array('text' => TEXT_INFO_VOTE_DESCRIPTION . '：' . $VInfo->v_s_description);
        $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_SORT_ORDER . '：' . $VInfo->v_s_sort);
		
		//引入详细调查项目
        $contents[] = array('text' => '<br><b>以下是调查选项</b>');
		$item_sql = tep_db_query('SELECT * FROM `vote_system_item`  WHERE v_s_id="'.(int)$VInfo->v_s_id.'" Order By v_s_i_sort ASC, v_s_i_id ASC ');
		$item_num = 0;
		while($item_rows = tep_db_fetch_array($item_sql)){
			$item_num++;
			$contents[] = array('text' => '<br><b>'.$item_num.'、'.$item_rows['v_s_i_title'].'</b>');
			//引入调查答案选项
			$v_s_i_total_sql = tep_db_query('SELECT SUM(v_s_i_o_total) as v_s_i_total FROM `vote_system_item_options` WHERE v_s_i_id="'.(int)$item_rows['v_s_i_id'].'"');
			$v_s_i_total_row = tep_db_fetch_array($v_s_i_total_sql);
			$v_s_i_total = (int)$v_s_i_total_row['v_s_i_total'];
			
			if($item_rows['v_s_i_type']=='2'){
				$t_sql = tep_db_query('SELECT count(*) as total FROM `vote_system_results` WHERE v_s_i_id="'.(int)$item_rows['v_s_i_id'].'" AND text_vote!="" AND v_s_i_o_id < 1 ');
				$v_s_i_total = '<a href="'.tep_href_link('vote_system_customers.php','v_s_id='.(int)$v_s_id.'&v_s_i_id='.(int)$item_rows['v_s_i_id']).'" target="_blank" >'.tep_db_result($t_sql,"0","total").'<b>[详细]</b></a>';
			}
			
			$item_options_sql = tep_db_query('SELECT * FROM `vote_system_item_options` WHERE v_s_i_id="'.(int)$item_rows['v_s_i_id'].'" Order By v_s_i_o_id ');
			while($item_options_rows = tep_db_fetch_array($item_options_sql)){
				switch($item_rows['v_s_i_type']){
					case '0': $output_html = '<input name="radio_tmp" type="radio" disabled>' ;
					  break;
					case '1': $output_html = '<input name="checkbox_tmp" type="checkbox" disabled>' ;
					  break;
					case '2': $output_html = '<input name="text_tmp" type="text" disabled>' ;
					  break;
					default:  $output_html = '' ;
				}

				//统计该答案选项所占百分比
				$percentage = @(int)(round($item_options_rows['v_s_i_o_total']/$v_s_i_total,2)*100).'%';
				$img_max_width = 180;
				$img_width = @(int)($img_max_width * ($percentage/100));
				$contents[] = array('text' => '&nbsp;&nbsp;&nbsp;&nbsp;<a href="'.tep_href_link('vote_system_customers.php','v_s_id='.(int)$v_s_id.'&v_s_i_id='.(int)$item_rows['v_s_i_id'].'&v_s_i_o_id='.$item_options_rows['v_s_i_o_id']).'" target="_blank" title="查看投此票的客户列表">'.$output_html.$item_options_rows['v_s_i_o_title'].'<div>&nbsp;&nbsp;&nbsp;&nbsp;<img src="'.DIR_WS_IMAGES.'percentage_color.gif" width="'.$img_width.'" height="3"><small>'.$percentage.'</small></div></a>');
			}
			
			//该项目参与总人数
			$contents[] = array('text' => '&nbsp;&nbsp;参与总人数：'.$v_s_i_total);
			
		}
      }
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
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