<?php
  require('includes/application_top.php');
  // 备注添加删除
  if($_GET['ajax']=="true"){
  	include DIR_FS_CLASSES . 'Remark.class.php';
  	$remark = new Remark('travel_companion');
  	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
  }
  $type_array=array(array('id'=>'','text'=>'不限'),array('id'=>'1','text'=>'跟团拼房'),array('id'=>'2','text'=>'自由行'),array('id'=>'3','text'=>'自驾游'));
  $type_array2=array('0'=>'','1'=>'跟团拼房','2'=>'自由行','3'=>'自驾游');
  switch($_GET['action']){
  	case 'update_bbs_sum':
		if((int)update_categories_tc_bbs_total()){
			$update_categories_tc_bbs_total_done = '更新结伴同游目录更新完毕！';
		}
		
	  break;
	case 'setstate': 
	 	if((int)$_GET['t_companion_id']){
			tep_db_query('update travel_companion set status="'.(int)$_GET['status'].'" where t_companion_id="'.(int)$_GET['t_companion_id'].'"');
		}
	 break;
	case 'setstate_bbs': 
	 	if((int)$_GET['categories_id']){
			tep_db_query('update '.TABLE_CATEGORIES.' set categories_status_for_tc_bbs="'.(int)$_GET['bbs_status'].'" where categories_id="'.(int)$_GET['categories_id'].'"');
			if(USE_MCACHE) MCache::update_categories(array('method'=>'update','categories_id'=>(int)$_GET['categories_id'])); //更新缓存-vincent-2011-4-22
		}
	 break;
	 
	case 'setstate_bbs_display': 
	 	if((int)$_GET['categories_id']){
			tep_db_query('update '.TABLE_CATEGORIES.' set categories_status_for_tc_bbs_display="'.(int)$_GET['bbs_display_status'].'" where categories_id="'.(int)$_GET['categories_id'].'"');
			if(USE_MCACHE) MCache::update_categories(array('method'=>'update','categories_id'=>(int)$_GET['categories_id'])); //更新缓存-vincent-2011-4-22
	 	}
	 break;
	 
	case 'DelConfirmed':
		if((int)$_GET['t_companion_id']){
			tep_db_query('DELETE FROM `travel_companion_reply` WHERE `t_companion_id` = "'.(int)$_GET['t_companion_id'].'" ');
			tep_db_query('DELETE FROM `travel_companion` WHERE `t_companion_id` = "'.(int)$_GET['t_companion_id'].'" ');
			tep_db_query('OPTIMIZE TABLE `travel_companion` , `travel_companion_reply` ');
		}
	 break;
  }
  
  if(isset($_GET['SortSubmit'])){
  	if(isset($_GET['sort_order_array'])){
		foreach((array)$_GET['sort_order_array'] as $key => $val){
			tep_db_query('update '.TABLE_CATEGORIES.' set sort_order="'.(int)$val.'" where categories_id="'.(int)$key.'" ');
			if(USE_MCACHE) MCache::update_categories(array('method'=>'update','categories_id'=>(int)$key)); //更新缓存-vincent-2011-4-22
		}
	}
  }
  
  
  //条件
  $where =' where categories_id >= 0 ';
  if($_GET['search']=='1'){
  	if(tep_not_null($_GET['s_search_text'])){
		$where .=' AND t_companion_title like "%'.$_GET['s_search_text'].'%" ';
	}
  	if(tep_not_null($_GET['search_start_date'])){
		$where .=' AND last_time >= "'.$_GET['search_start_date'].' 00:00:00" ';
	}
  	if(tep_not_null($_GET['search_end_date'])){
		$where .=' AND last_time <= "'.$_GET['search_end_date'].' 23:59:59" ';
	}
	if(tep_not_null($_GET['_type'])){
		$where .=' AND _type = '.$_GET['_type'];
	}
	if((int)$_GET['bbs_type']>0){
		$where .=' AND bbs_type = "'.(int)$_GET['bbs_type'].'" ';
	}
  }
if(tep_not_null($_GET['TcPath'])){
	$Tccurrent_category_id = preg_replace('/.+\_/','',$_GET['TcPath']);
	if($Tccurrent_category_id>0){
		$cate_string = (int)$Tccurrent_category_id;
		$child_categories_array=array();
		$child_categories_array = tep_get_categories('', (int)$Tccurrent_category_id);
		for($i=0; $i<count($child_categories_array); $i++ ){
			$cate_string.= ','.$child_categories_array[$i]['id'];
		}
		
		//echo $cate_string;
		$where.= ' AND categories_id in('.$cate_string.') ';
	}
}

  //排序
  $order_by = ' order by bbs_type asc, last_time desc ';
  if($_GET['sort']=='total_up'){ $order_by = ' order by reply_num asc ';}
  if($_GET['sort']=='total_down'){ $order_by = ' order by reply_num desc ';}
  if($_GET['sort']=='date_up'){ $order_by = ' order by last_time asc ';}
  if($_GET['sort']=='date_down'){ $order_by = ' order by last_time desc ';}
  if($_GET['sort']=='click_down'){ $order_by = ' order by click_num desc ';}
  if($_GET['sort']=='click_up'){ $order_by = ' order by click_num desc ';}

	$sql_str = 'select * from `travel_companion` '.$where.$order_by;
    
	$replay_sum_sql = 'select sum(reply_num) as replay_sum from `travel_companion` '.$where;
	$replay_sum = tep_db_query($replay_sum_sql);
	$replay_sum = tep_db_fetch_array($replay_sum);
	$replay_sum = number_format(intval($replay_sum['replay_sum']));
	
	$companion_query_numrows = 0;
	$companion_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $sql_str, $companion_query_numrows);
	
	$companion_query = tep_db_query($sql_str);

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
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
<script language="javascript"><!--

//var Date_Reg_start = new ctlSpiffyCalendarBox("Date_Reg_start", "form_search", "search_start_date","btnDate1","<?php echo ($search_start_date); ?>",scBTNMODE_CUSTOMBLUE);
//var Date_Reg_end = new ctlSpiffyCalendarBox("Date_Reg_end", "form_search", "search_end_date","btnDate2","<?php echo ($search_end_date); ?>",scBTNMODE_CUSTOMBLUE);

<?php
$p=array('/&amp;/','/&quot;/');
$r=array('&','"');
?>

function DelTravel(t_id){
	if(t_id<1){ alert('no id'); return false;}
	if(window.confirm("您真的要删除这个帖子及其所有的回帖吗？\t")==true){
		parent.location = "<?php echo preg_replace($p,$r,tep_href_link('travel_companion.php','action=DelConfirmed'))?>&t_companion_id="+t_id;
		return false;
	}
}
//--></script>
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
$listrs = new Remark('travel_companion');
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
        <td>
          <!--search form start-->
		  <fieldset>
		  <legend align="left"> Search Module </legend>
		  <?php echo tep_draw_form('form_search', 'travel_companion.php', tep_get_all_get_params(array('page','y','x', 'action')), 'get'); ?>
		  
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td><table border="0" cellspacing="0" cellpadding="0" style="margin:10px;">
                  
				  <tr>
				    <td align="right" valign="middle" nowrap class="main"><?php echo db_to_html('类型')?></td>
				    <td align="left" valign="middle" nowrap class="main">
				<?php
				$option_array = array();
				$option_array[0] = array('id'=>'', 'text'=>'不限');
				$t_sql = tep_db_query('SELECT * FROM `travel_companion_bbs_type` Order By type_id ASC ');
				while($t_rows=tep_db_fetch_array($t_sql)){
					$option_array[]=array('id'=> $t_rows['type_id'], 'text'=> $t_rows['type_name']);
				}
				$option_array = db_to_html($option_array);
				echo tep_draw_pull_down_menu('bbs_type', $option_array);
				?>
					&nbsp;</td>
					<td align="right" valign="middle" nowrap class="main"><?php echo db_to_html('类型2')?></td>
				    <td align="left" valign="middle" nowrap class="main">
					<?php echo tep_draw_pull_down_menu('_type', $type_array);?>
					</td>
                    <td height="30" align="right" valign="middle" nowrap class="main"><?php echo TABLE_HEADING_KEYWORD; ?></td>
                    <td align="left" valign="middle" class="main">&nbsp;<?php echo tep_draw_input_field('s_search_text')?></td>
                    <td>&nbsp;</td>
                    <td align="right" nowrap class="main">&nbsp;</td>
                    <td align="right" nowrap class="main"><?php echo TABLE_HEADING_TIME; ?></td>
                    <td class="main" align="left">&nbsp;
                      <table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td nowrap class="main">&nbsp;
						  <?php echo tep_draw_input_field('search_start_date', tep_get_date_disp($_GET['search_start_date']), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"');?>
						  <script language="javascript">//Date_Reg_start.writeControl(); Date_Reg_start.dateFormat="yyyy-MM-dd";</script></td>
                          <td class="main">&nbsp;至&nbsp;</td>
                          <td nowrap class="main">
						  <?php echo tep_draw_input_field('search_end_date', tep_get_date_disp($_GET['search_end_date']), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"');?>
						  <script language="javascript">//Date_Reg_end.writeControl(); Date_Reg_end.dateFormat="yyyy-MM-dd";</script></td>
                        </tr>
                      </table></td>
                  </tr>
                  <tr>
                    <td class="main" align="right"><?php echo db_to_html('所属景点')?></td>
                    <td colspan="7" align="left" class="main">
						<?php echo tep_draw_hidden_field('TcPath')?>
						<?php include('travel_companion_categories_list.php');?>
					</td>
                    </tr>
                  <tr>
                    <td class="main" align="right">&nbsp;</td>
                    <td class="main" align="right">&nbsp;</td>
                    <td class="main" align="right">&nbsp;</td>
                    <td class="main" align="left">&nbsp;<input name="Send" type="submit" value="搜索" style="width:100px; height:30px; margin-top:10px;"></td>
                    <td>&nbsp;</td>
                    <td class="main" align="right"><a href="<?php echo tep_href_link('travel_companion_create.php')?>"><input type="button" name="Submit" value="新增帖子" style="width:100px; height:30px; margin-top:10px;"></a></td>
                    <td colspan="2" align="right" class="main">
					<input name="search" type="hidden" id="search" value="1">
					<a href="<?php echo tep_href_link('travel_companion_bbs_meta_tages.php')?>"><?php echo db_to_html('[设置结伴同游BBS Meta Tags]');?></a>
					<a href="<?php echo tep_href_link('travel_companion.php','action=update_bbs_sum')?>"><?php echo db_to_html('<b>[更新结伴同游目录]</b>');?></a>
					<?php
					if(tep_not_null($update_categories_tc_bbs_total_done)){
						echo '<div style="color:#339900"><b>'.db_to_html($update_categories_tc_bbs_total_done).'</b></div>';
					}
					?>					</td>
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
                <td class="dataTableHeadingContent" nowrap="nowrap">&nbsp;</td>
                <td class="dataTableHeadingContent" nowrap="nowrap"><?php echo TABLE_HEADING_KEYWORD; ?></td>
				<td class="dataTableHeadingContent" nowrap="nowrap">内容</td>

				<td class="dataTableHeadingContent" nowrap="nowrap"><?php echo TABLE_HEADING_TIME; ?>
				<a href="<?php echo tep_href_link('travel_companion.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=date_down');?>" title="down"><img src="images/arrow_down.gif" border="0"></a>
				<a href="<?php echo tep_href_link('travel_companion.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=date_up');?>" title="up"><img border="0" src="images/arrow_up.gif"></a>				</td>

				<td class="dataTableHeadingContent" nowrap="nowrap"><?php echo TABLE_HEADING_TOTAL; ?>
				<a href="<?php echo tep_href_link('travel_companion.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=total_down');?>" title="down"><img src="images/arrow_down.gif" border="0"></a>
				<a href="<?php echo tep_href_link('travel_companion.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=total_up');?>" title="up"><img border="0" src="images/arrow_up.gif"></a>				</td>
				<td class="dataTableHeadingContent" nowrap="nowrap">点击量
				<a href="<?php echo tep_href_link('travel_companion.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=click_down');?>" title="down"><img src="images/arrow_down.gif" border="0"></a>
				<a href="<?php echo tep_href_link('travel_companion.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=click_up');?>" title="up"><img border="0" src="images/arrow_up.gif"></a>				</td>
				<td class="dataTableHeadingContent" nowrap="nowrap">景点</td>
				<td class="dataTableHeadingContent" nowrap="nowrap">产品</td>
				<td class="dataTableHeadingContent" nowrap="nowrap">状态</td>
				<td class="dataTableHeadingContent" nowrap="nowrap">状态2</td>
				<td class="dataTableHeadingContent" nowrap="nowrap"><?php echo TABLE_HEADING_ACTION; ?></td>
              </tr>
			<?php
			$page_replay_sum = 0;
			while($rows = tep_db_fetch_array($companion_query)){
			    $rows_num++;
				$page_replay_sum += intval($rows['reply_num']);
				if (strlen($rows) < 2) {
				  $rows_num = '0' . $rows_num;
				}
				
				$bg_color = "#F0F0F0";
				if((int)$rows_num %2 ==0 && (int)$rows_num){
					$bg_color = "#ECFFEC";
				}

			?>
              <tr class="dataTableRow" style="cursor:auto; background-color:<?= $bg_color;?>">
                <td class="dataTableContent" nowrap="nowrap">
				<?php
				$d_type = tep_bbs_type_name($rows['bbs_type']);
				if(tep_not_null($d_type)){
					echo '<b style="color:#FF6600">['.db_to_html($d_type).']</b>'; 
				}
				?>
				</td>
                <td class="dataTableContent"><?php echo tep_db_output($rows['t_companion_title'])?></td>
                <td class="dataTableContent"><?php echo nl2br(tep_db_output($rows['t_companion_content']))?></td>
				
				<td class="dataTableContent"><?php echo tep_db_output($rows['last_time'])?></td>
				<td class="dataTableContent"><?php echo tep_db_output($rows['reply_num'])?></td>
				<td class="dataTableContent"><?php echo tep_db_output($rows['click_num'])?></td>
				<td class="dataTableContent">
				
				<?php
				$categories = array();
				
				if((int)$rows['categories_id']){
					tep_get_parent_categories($categories, (int)$rows['categories_id']);
					foreach((array)$categories as $val){
						echo tep_get_category_name($val,'1').' &gt;&gt;<br>'; 
					}
					echo tep_get_category_name($rows['categories_id'],'1');
				}
				?>
&nbsp;				</td>
				<td class="dataTableContent">
				
				<?php
				if((int)$rows['products_id']){
					echo '<a href="../product_info.php?products_id='.$rows['products_id'].'" target="_blank">'.tep_get_products_name($rows['products_id'],'1').'</a>';
				}
				?>
&nbsp;				</td>
				
				<td class="dataTableContent">
				<?php
					  if ($rows['status'] == '1') {
						echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link('travel_companion.php', 'action=setstate&status=0&t_companion_id=' . $rows['t_companion_id'].(isset($HTTP_GET_VARS['page']) ? '&page=' . $HTTP_GET_VARS['page'] . '' : '').(isset($HTTP_GET_VARS['sortorder']) ? '&sortorder=' . $HTTP_GET_VARS['sortorder'] . '' : '')) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
					  } else {
						echo '<a href="' . tep_href_link('travel_companion.php', 'action=setstate&status=1&t_companion_id=' . $rows['t_companion_id'] .(isset($HTTP_GET_VARS['page']) ? '&page=' . $HTTP_GET_VARS['page'] . '' : ''). (isset($HTTP_GET_VARS['sortorder']) ? '&sortorder=' . $HTTP_GET_VARS['sortorder'] . '' : '')) .'">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
					  }
				?>				</td>
				<td class="dataTableContent"><?php echo $type_array2[$rows['_type']];?></td>
				<td nowrap class="dataTableContent">[<a href="<?php echo tep_href_link('travel_companion_re.php','t_companion_id='.$rows['t_companion_id'])?>">编辑</a>]
				
				[<a href="JavaScript:void(0);" onClick="DelTravel(<?php echo $rows['t_companion_id']?>); return false;">删除</a>]</td>
              </tr>
			<?php
			}
			$page_replay_sum = number_format($page_replay_sum);
			?>  

            </table></td>
          </tr>
		  
		  <tr>

			<td colspan="<?= $colspan?>">
            
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top">符合条件的回帖总数：<b><?php echo $replay_sum;?></b> ；当前页回帖总数：<b><?php echo $page_replay_sum;?></b> 。</td>
              </tr>
            </table>
            
            
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $companion_split->display_count($companion_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                <td class="smallText" align="right"><?php echo $companion_split->display_links($companion_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $_GET['page'],tep_get_all_get_params(array('page','y','x', 'action', 'SortSubmit','sort_order_array'))); ?>&nbsp;</td>
              </tr>
            </table>
            </td>
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
