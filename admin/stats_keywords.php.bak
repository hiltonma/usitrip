<?php
  require('includes/application_top.php');
  // 备注添加删除
  if($_GET['ajax']=="true"){
  	include DIR_FS_CLASSES . 'Remark.class.php';
  	$remark = new Remark('stats_keywords');
  	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
  }  
  if (isset($_GET['page']) && ($_GET['page'] > 1)) {$rows = $_GET['page'] * MAX_DISPLAY_SEARCH_RESULTS_ADMIN - MAX_DISPLAY_SEARCH_RESULTS_ADMIN;}else{
  	$rows = 0;
  }
  if(!tep_not_null($_GET['search_type'])){
  	$search_type ='search_queries';
  }

//执行审核或删除操作
if($_GET['action']=='Approve' && $_GET['search_type'] =='search_queries'){
	$sql = tep_db_query('SELECT * FROM `search_queries` WHERE search_id="'.(int)$_GET['search_id'].'" limit 1');
	$row = tep_db_fetch_array($sql);
	if((int)$row['search_id']){
		tep_db_query('INSERT INTO `search_date_stored` ( `search_text` , `search_date` ) VALUES ("'.tep_db_input($row['search_text']).'", "'.$row['search_date'].'");');
		tep_db_query('DELETE FROM `search_queries` WHERE `search_id` = "'.(int)$row['search_id'].'" ');
	}
}
if($_GET['action']=='Approve_ALL' && $_GET['search_type'] =='search_queries'){
	$sql = tep_db_query('SELECT * FROM `search_queries` ');
	while($rows = tep_db_fetch_array($sql)){
		if((int)$rows['search_id']){
			tep_db_query('INSERT INTO `search_date_stored` ( `search_text` , `search_date` ) VALUES ("'.tep_db_input($rows['search_text']).'", "'.$rows['search_date'].'");');
			tep_db_query('DELETE FROM `search_queries` WHERE `search_id` = "'.(int)$rows['search_id'].'" ');
		}
	}
}

if($_GET['action']=='Delete'){
	if($_GET['search_type'] =='search_queries'){
		tep_db_query('DELETE FROM `search_queries` WHERE `search_id` = "'.(int)$_GET['search_id'].'" ');
	}
	if($_GET['search_type'] =='search_date_stored'){
		tep_db_query('DELETE FROM `search_date_stored` WHERE `search_text` = "'.$_GET['search_text'].'" ');
	}
}

if($_GET['action']=='Delete_ALL'){
	if($_GET['search_type'] =='search_queries'){
		tep_db_query('TRUNCATE TABLE `search_queries` ');
	}
	if($_GET['search_type'] =='search_date_stored'){
		tep_db_query('TRUNCATE TABLE `search_date_stored` ');
	}
}
  
  //公共搜索
  $where_exc = ''; 
  if($_GET['search_action']=='1'){
  	if(tep_not_null($_GET['s_search_text'])){
		$s_search_text = trim($_GET['s_search_text']);
		$where_exc .= ' AND search_text Like binary ("%'.$s_search_text.'%")  ';
	}

	if(tep_not_null($_GET['search_start_date'])){
		$search_start_date = trim($_GET['search_start_date']);
		$where_exc .= ' AND search_date >="'.$search_start_date.' 00:00:00" ';
	}
	if(tep_not_null($_GET['search_end_date'])){
		$search_end_date = trim($_GET['search_end_date']);
		$where_exc .= ' AND search_date <="'.$search_end_date.' 23:59:59" ';
	}
  }
  
  //排序
$sort = $_GET['sort'];
//$sort =='total_down';
//$sort =='total_up';
//$sort =='date_down';
//$sort =='date_up';
$Order_By = ' Order By search_date DESC ';
if($sort =='total_down'){ $Order_By = ' Order By total DESC ';}
if($sort =='total_up'){ $Order_By = ' Order By total ASC ';}
if($sort =='date_down'){ $Order_By = ' Order By search_date DESC ';}
if($sort =='date_up'){ $Order_By = ' Order By search_date ASC ';}
  
  if($search_type =='search_queries'){
	  
	  $search_query_raw = "select *, count(search_text) as total FROM search_queries where search_id >0 ".$where_exc." Group By search_id $Order_By ";
  
  }elseif($search_type =='search_date_stored'){
	  $search_query_raw = "select *, count(search_text) as total, max(search_date) as search_date FROM search_date_stored where search_text!='' ".$where_exc." Group By search_text $Order_By ";
  
  }elseif($search_type =='search_queries_sorted'){
	  //如果是报表则先将通过审核的关键字生成报表再来处理
	  if(!isset($_GET['page']) && !tep_not_null($_GET['sort'])){
	  	tep_db_query('TRUNCATE TABLE `search_queries_sorted`');
		$sql = tep_db_query('select *, count(search_text) as total FROM search_date_stored Group By search_text ');
		while($rows = tep_db_fetch_array($sql)){
			tep_db_query('INSERT INTO `search_queries_sorted` ( `search_text`,`search_count`)
						VALUES ("'.tep_db_input($rows['search_text']).'", "'.$rows['total'].'");');
		}
	  }
	  
	  $Order_By_C = ' Order By total DESC ';
	  if($sort =='total_up'){ $Order_By_C = ' Order By total ASC ';}
	  $search_query_raw = "select *, search_count as total FROM search_queries_sorted $Order_By_C ";
  }
	
	  $search_query_numrows = 0;
	  $search_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $search_query_raw, $search_query_numrows);
	
	  $search_query = tep_db_query($search_query_raw);

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
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<div id="spiffycalendar" class="text"></div>





<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('stats_keywords');
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
		  <?php echo tep_draw_form('form_search', 'stats_keywords.php', tep_get_all_get_params(array('page','y','x', 'action')), 'get'); ?>
		  
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td><table border="0" cellspacing="0" cellpadding="0" style="margin:10px;">
                  
				  <tr>
                    <td height="30" align="right" valign="middle" nowrap class="main">搜索目标</td>
                    <td class="main" align="left">
					
					&nbsp;<?php echo tep_draw_radio_field('search_type', 'search_queries', '','',' onClick="form_search.submit(); document.getElementById(\'s_option\').style.display=\'\'" ')?> 未审核 <?php echo tep_draw_radio_field('search_type', 'search_date_stored', '','',' onClick="form_search.submit(); document.getElementById(\'s_option\').style.display=\'none\'" ')?> 已通过审核					</td>
                    <td><input name="search_action" type="hidden" id="search_action" value="1"></td>
                    <td class="main" align="right">&nbsp;</td>
                    <td class="main" align="right">&nbsp;</td>
                    <td class="main" align="left">&nbsp;</td>
                  </tr>
				  <tr>
                    <td height="30" align="right" valign="middle" nowrap class="main"><?php echo TABLE_HEADING_KEYWORD; ?></td>
                    <td align="left" valign="middle" class="main">&nbsp;<?php echo tep_draw_input_field('s_search_text')?></td>
                    <td>&nbsp;</td>
                    <td align="right" nowrap class="main">&nbsp;</td>
                    <td align="right" nowrap class="main"><?php echo TABLE_HEADING_TIME; ?></td>
                    <td class="main" align="left">&nbsp;
                      <table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td nowrap class="main">&nbsp;<?php echo tep_draw_input_field('search_start_date', tep_get_date_disp($search_start_date), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"');?></td>
                          <td class="main">&nbsp;至&nbsp;</td>
                          <td nowrap class="main"><?php echo tep_draw_input_field('search_end_date', tep_get_date_disp($search_end_date), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"');?></td>
                        </tr>
                      </table></td>
                  </tr>
                  <tr>
                    <td class="main" align="right">&nbsp;</td>
                    <td class="main" align="left">&nbsp;<input name="Send" type="submit" value="搜索" style="width:100px; height:30px; margin-top:10px;"></td>
                    <td>&nbsp;</td>
                    <td class="main" align="right">&nbsp;</td>
                    <td colspan="2" align="right" class="main"><a href="<?php echo tep_href_link('stats_keywords.php','search_type=search_queries_sorted');?>"><b>生成统计报表</b></a></td>
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
                <td class="dataTableHeadingContent" nowrap="nowrap"><?php echo TABLE_HEADING_KEYWORD; ?></td>
                <?php if($search_type !='search_queries_sorted'){?>
				<td class="dataTableHeadingContent" nowrap="nowrap"><?php echo TABLE_HEADING_TIME; ?>
				<a href="<?php echo tep_href_link('stats_keywords.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=date_down');?>" title="down"><img src="images/arrow_down.gif" border="0"></a>
				<a href="<?php echo tep_href_link('stats_keywords.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=date_up');?>" title="up"><img border="0" src="images/arrow_up.gif"></a>
				
				</td>
                <?php }?>
				<td class="dataTableHeadingContent" nowrap="nowrap"><?php echo TABLE_HEADING_TOTAL; ?>
				<a href="<?php echo tep_href_link('stats_keywords.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=total_down');?>" title="down"><img src="images/arrow_down.gif" border="0"></a>
				<a href="<?php echo tep_href_link('stats_keywords.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=total_up');?>" title="up"><img border="0" src="images/arrow_up.gif"></a>

				
				</td>
                <?php if($search_type !='search_queries_sorted'){?>
				<td class="dataTableHeadingContent" nowrap="nowrap"><?php echo TABLE_HEADING_ACTION; ?></td>
				<?php }?>
              </tr>
<?php
  while ($search = tep_db_fetch_array($search_query)) {
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
                <td class="dataTableContent"><?php echo '<a href="' . HTTP_SERVER.'/advanced_search_result.php?keywords='.$search['search_text'].'&url_from=admin&country=us-tours" target="_blank">' . tep_db_output($search['search_text']) . '</a>'; ?></td>
                
				<?php if($search_type !='search_queries_sorted'){?>
				<td class="dataTableContent"><?php echo $search['search_date'];?></td>
                <?php }?>
				<td class="dataTableContent"><?php echo $search['total'];?></td>
                <?php if($search_type !='search_queries_sorted'){?>
				<td class="dataTableContent">
					<?php if($search_type =='search_queries'){?>
					<a href="<?php echo tep_href_link('stats_keywords.php','action=Approve&search_type='.$search_type.'&search_id='.(int)$search['search_id']).'&'.tep_get_all_get_params(array('search_type','y','x', 'action','search_id','search_text'));?>">Approve</a>&nbsp;&nbsp;&nbsp;&nbsp;
					<?php } ?>
					<a href="<?php echo tep_href_link('stats_keywords.php','action=Delete&search_type='.$search_type.'&search_id='.(int)$search['search_id'].'&search_text='.$search['search_text']).'&'.tep_get_all_get_params(array('search_type','y','x', 'action','search_id','search_text'));?>">Delete</a>
				
				</td>
				<?php }?>
              </tr>
			  
<?php
  }
?>
            </table></td>
          </tr>
		  
		<?php if($search_type !='search_queries_sorted'){?>  
		  <tr><td align="center" class="dataTableContent">
		 <?php if($search_type =='search_queries'){?>
		  <a href="<?php echo tep_href_link('stats_keywords.php',tep_get_all_get_params(array('search_type','y','x', 'action','search_id','search_text')).'action=Approve_ALL&search_type='.$search_type);?>">Approve ALL</a>
		 <?php }?>
		  &nbsp;&nbsp;&nbsp;&nbsp;
		  <a href="<?php echo tep_href_link('stats_keywords.php',tep_get_all_get_params(array('search_type','y','x', 'action','search_id','search_text')).'action=Delete_ALL&search_type='.$search_type);?>">Delete ALL</a>
		  </td>
		  </tr>
        <?php }?>
		  
		  <tr>
            <?php $colspan = 3; if($search_type =='search_queries_sorted'){ $colspan = 1;}?>
			<td colspan="<?= $colspan?>"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $search_split->display_count($search_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                <td class="smallText" align="right"><?php echo $search_split->display_links($search_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $_GET['page'],tep_get_all_get_params(array('page','y','x', 'action'))); ?>&nbsp;</td>
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
