<?php
require('includes/application_top.php');

$action = (isset($_GET['action']) ? $_GET['action'] : '');
switch($action){
	case 'setstate':	//修改目录状态
	if((int)$_GET['dg_categories_id']){
		$dg_categories_state = (int)$_GET['dg_categories_state'];
		tep_db_query('UPDATE `destination_guide_categories` SET `dg_categories_state` = "'.$dg_categories_state.'" WHERE `dg_categories_id` = "'.$_GET['dg_categories_id'].'"  ');
		$messageStack->add_session('状态更新成功！', 'success');
	}
		$tmp_link = tep_href_link('destination_guide.php', 'dg_categories_id=' . $_GET['dg_categories_id'].'&'.tep_get_all_get_params(array('x','y','action','dg_categories_state','dg_categories_id')) );
		tep_redirect($tmp_link);
	break;
	
	case 'setstatetours':	//修改文章状态
	if((int)$_GET['tours_experience_id']){
		$tours_experience_status = (int)$_GET['tours_experience_status'];
		tep_db_query('UPDATE `tours_experience` SET `tours_experience_status` = "'.$tours_experience_status.'" WHERE `tours_experience_id` = "'.(int)$_GET['tours_experience_id'].'" ');
		$messageStack->add_session('状态更新成功！', 'success');
		$tmp_link = tep_href_link('destination_guide.php', tep_get_all_get_params(array('x','y','action','tours_experience_status','tours_experience_id')) );
		tep_redirect($tmp_link);
	}
	break;
	
	case 'DelConfirmed':	//删除目录
	if((int)$_GET['dg_categories_id']){
		$ids_array = array('0'=> (int)$_GET['dg_categories_id'] );
		tep_get_child_dg_categories_ids_string($ids_array, (int)$_GET['dg_categories_id']);
		$ids_string = implode(',',$ids_array);
		tep_db_query('DELETE FROM `destination_guide_categories` WHERE `dg_categories_id` in('.$ids_string.') ');
		tep_db_query('DELETE FROM `tours_experience_to_guide_categories` WHERE `dg_categories_id` in('.$ids_string.') ');
		tep_db_query('DELETE FROM `destination_guide_categories_description` WHERE `dg_categories_id` in('.$ids_string.') ');
		tep_db_query('OPTIMIZE TABLE `destination_guide_categories`, `destination_guide_categories_description`, `tours_experience_to_guide_categories` ');
		
		$messageStack->add_session('目录删除成功！', 'success');
		$tmp_link = tep_href_link('destination_guide.php', tep_get_all_get_params(array('x','y','action','dg_categories_id')) );
		tep_redirect($tmp_link);
	}
	break;
	case 'DelToursExperienceConfirmed':	//删除文章
	if((int)$_GET['tours_experience_id']){
		$tours_experience_id =(int)$_GET['tours_experience_id'];
		tep_db_query('DELETE FROM `tours_experience` WHERE `tours_experience_id` ='.(int)$tours_experience_id.' ');
		tep_db_query('DELETE FROM `tours_experience_to_guide_categories` WHERE `tours_experience_id` ='.(int)$tours_experience_id.' ');
		tep_db_query('OPTIMIZE TABLE `tours_experience` , `tours_experience_to_guide_categories` ');
		
		$messageStack->add_session('文章删除成功！', 'success');
		$tmp_link = tep_href_link('destination_guide.php', tep_get_all_get_params(array('x','y','action','tours_experience_id')) );
		tep_redirect($tmp_link);
	}
	break;
	
}

$where_exc =' WHERE parent_id ="0" ';
if(isset($current_dg_category_id)){
	$where_exc =' WHERE parent_id ="'.$current_dg_category_id.'" ';
}


$orderBy = ' ORDER BY sort_order ASC, dg_categories_id DESC ';	
$guide_query_raw = 'SELECT * FROM `destination_guide_categories` '.$where_exc.$orderBy;
$guide_query_numrows = 0;
$guide_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $guide_query_raw, $guide_query_numrows);

$guide_query = tep_db_query($guide_query_raw);

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
function DeleteDgCategories(Categories_id){
	if(Categories_id<1){ alert('no id'); return false;}
	if(window.confirm("您真的要删除这个目录吗？删除目录会连同其所有子级目录均被删除，请谨慎操作。\t")==true){
		parent.location = "<?php echo preg_replace($p,$r,tep_href_link('destination_guide.php','action=DelConfirmed&DgPath='.$DgPath))?>&dg_categories_id="+Categories_id;
		return false;
	}
}

function DeleteToursExperience(tours_experience_id){
	if(tours_experience_id<1){ alert('no id'); return false;}
	if(window.confirm("您真的要删除这篇文章吗？\t")==true){
		parent.location = "<?php echo preg_replace($p,$r,tep_href_link('destination_guide.php','action=DelToursExperienceConfirmed&DgPath='.$DgPath))?>&tours_experience_id="+tours_experience_id;
		return false;
	}
}
</script>

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
            <td class="pageHeading">
			<a href="<?php echo tep_href_link('destination_guide.php')?>" style="font-size:18px">目的地指南</a> 
			<?php
				$count_DgPath = count($DgPath_array);
				if($count_DgPath){
					for($i=0; $i<$count_DgPath; $i++){
						$cate_sql = tep_db_query('SELECT dg_categories_id,dg_categories_name FROM `destination_guide_categories` WHERE dg_categories_id="'.$DgPath_array[$i].'" ');
						$cate_row = tep_db_fetch_array($cate_sql);
						echo ' &gt;&gt; <a style="font-size:18px" href="'.tep_href_link('destination_guide.php','DgPath='.tep_get_gd_category_patch($cate_row['dg_categories_id'])).'">'.db_to_html($cate_row['dg_categories_name']).'</a>';
					}
				}
			?>
			</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
          <!--search form start-->
		  <fieldset>
		  <legend align="left"> Search Module </legend>
		  <?php echo tep_draw_form('form_search', 'destination_guide.php', tep_get_all_get_params(array('page','y','x', 'action')), 'get'); ?>
		  
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td>
				搜索功能暂未做，当有一定的数据资料时请通知我做此功能。并列出搜索功能的需求，以使开发一步到位，减少修改程序的时间！
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

		<?php
		//if((int)$guide_split->query_num_rows){//显示目录
		$display_categories = true;
		if($display_categories){
		?>
		<table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top">
			<table border="0" width="100%" cellspacing="1" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" nowrap="nowrap">ID</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">目录名称</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">状态</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">操作</td>
              </tr>
<?php
  while ($guide = tep_db_fetch_array($guide_query)) {
    $rows++;

    if (strlen($rows) < 2) {
      $rows = '0' . $rows;
    }
	
	$bg_color = "#F0F0F0";
	if((int)$rows %2 ==0 && (int)$rows){
		$bg_color = "#ECFFEC";
	}
	$C_DgPath=tep_get_gd_category_patch($guide['dg_categories_id']);
	
?>
              <tr class="dataTableRow" style="cursor:auto; background-color:<?= $bg_color;?>">
                <td class="dataTableContent"><?php echo $guide['dg_categories_id']?></td>
                <td class="dataTableContent"><a href="<?=tep_href_link('destination_guide.php', 'DgPath=' . $C_DgPath . '&DgID=' . $guide['dg_categories_id'])?>"><?php echo db_to_html($guide['dg_categories_name'])?></a></td>
                <td class="dataTableContent">
				<?php
					  if ($guide['dg_categories_state'] == '1') {
						echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link('destination_guide.php', 'action=setstate&dg_categories_state=0&dg_categories_id=' . $guide['dg_categories_id'].'&'.tep_get_all_get_params(array('x','y','action','dg_categories_state','dg_categories_id'))) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
						
					  } else {
						echo '<a href="' . tep_href_link('destination_guide.php', 'action=setstate&dg_categories_state=1&dg_categories_id=' . $guide['dg_categories_id'] .'&'.tep_get_all_get_params(array('x','y','action','dg_categories_state','dg_categories_id'))) .'">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
					  }
				?>				</td>
                <td class="dataTableContent">
				<?php echo '<a href="' . tep_href_link('destination_guide_article.php', 'DgPath=' . $C_DgPath . '&DgID=' . $guide['dg_categories_id'] . '&action=create_info') . '">' . tep_image(DIR_WS_ICONS . 'create1.gif', db_to_html('在该目录新建文章')) . '</a>'; ?>
				&nbsp;
				<?php echo '<a href="' . tep_href_link('destination_guide_category.php', 'DgPath=' . $C_DgPath . '&DgID=' . $guide['dg_categories_id'] . '&action=create_category') . '">' . tep_image(DIR_WS_ICONS . 'create.gif', db_to_html('新建子目录')) . '</a>'; ?>
				&nbsp;
				<?php echo '<a href="' . tep_href_link('destination_guide_category.php', 'DgPath=' . $C_DgPath . '&DgID=' . $guide['dg_categories_id'] . '&action=edit_category') . '">' . tep_image(DIR_WS_ICONS . 'edit.gif', db_to_html('编辑')) . '</a>'; ?>
				&nbsp;
				<?php
				echo '<a href="JavaScript:void(0)" onClick="DeleteDgCategories(&quot;'.$guide['dg_categories_id'].'&quot;)">' . tep_image(DIR_WS_ICONS . 'delete.gif', db_to_html('删除')) . '</a>';
				
				?>
				</td>
              </tr>
			  
<?php
  }
?>
            </table>
			
			
			</td>
          </tr>
          <tr>
            <td colspan="3">
			<table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $guide_split->display_count($guide_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                <td class="smallText" align="right"><?php echo $guide_split->display_links($guide_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $_GET['page'],tep_get_all_get_params(array('page','y','x', 'action'))); ?>&nbsp;</td>
              </tr>
              <tr>
                <td align="left" valign="middle" class="smallText">
				<?php
				if((int)$current_dg_category_id){
					$ParentDgPath ='';
					for($i=0; $i<max((count($DgPath_array)-1), 0); $i++){
						$ParentDgPath.=$DgPath_array[$i].'_';
					}
					$ParentDgPath = substr($ParentDgPath,0,(strlen($ParentDgPath)-1));
					echo '<a href="' . tep_href_link('destination_guide.php', 'DgPath='.$ParentDgPath.'&'.tep_get_all_get_params(array('DgPath','DgID','y','x', 'action')) ). '">' .tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';
				}
				?>
				</td>
                <td align="right" valign="middle" class="smallText">
				<?php echo '<a href="' . tep_href_link('destination_guide_category.php', 'DgPath=' . $DgPath . '&action=create_category') . '">' . tep_image_button('button_new_category.gif', IMAGE_NEW_CATEGORY) . '</a>'; ?>
				<?php echo '<a href="' . tep_href_link('destination_guide_article.php', 'DgPath=' . $DgPath . '&action=create_info') . '">' . tep_image_button('button_new_article.gif', db_to_html('新增文章')) . '</a>'; ?>
				</td>
              </tr>
            </table>
			</td>
          </tr>
        </table>
		<?php }
		
		$display_article = true;
		if($display_article==true){//显示文章
		?>
		<table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top">
			<table border="0" width="100%" cellspacing="1" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" nowrap="nowrap">ID</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">文章标题</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">状态</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">操作</td>
              </tr>
<?php
$where_tours = ' WHERE dg_categories_id ="'.$current_dg_category_id.'" AND te.tours_experience_id = tetgc.tours_experience_id  ';
$tours_orderBy = ' ORDER BY te.tours_experience_id DESC ';	
$tours_query_raw = 'SELECT * FROM `tours_experience` te, `tours_experience_to_guide_categories` tetgc '.$where_tours.$tours_orderBy;
$tours_query_numrows = 0;
$max_now = MAX_DISPLAY_SEARCH_RESULTS_ADMIN;
//$max_now = 2;
$tours_split = new splitPageResults($_GET['tours_page'], $max_now, $tours_query_raw, $tours_query_numrows);

$tours_query = tep_db_query($tours_query_raw);

  $rows=0;
  while ($tours = tep_db_fetch_array($tours_query)) {
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
                <td class="dataTableContent"><?php echo $tours['tours_experience_id']?></td>
                <td class="dataTableContent"><a target="_blank" href="../tours-experience.php?tours_experience_id=<?=$tours['tours_experience_id']?>"><?php echo db_to_html(tep_db_output($tours['tours_experience_title']))?></a></td>
                <td class="dataTableContent">
				<?php
					  if ($tours['tours_experience_status'] == '1') {
						echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link('destination_guide.php', 'action=setstatetours&tours_experience_status=0&tours_experience_id=' . $tours['tours_experience_id'].'&'.tep_get_all_get_params(array('x','y','action','tours_experience_status','tours_experience_id'))) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
						
					  } else {
						echo '<a href="' . tep_href_link('destination_guide.php', 'action=setstatetours&tours_experience_status=1&tours_experience_id=' . $tours['tours_experience_id'] .'&'.tep_get_all_get_params(array('x','y','action','tours_experience_status','tours_experience_id'))) .'">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
					  }
				?>
				</td>
                <td class="dataTableContent">&nbsp;
				<?php echo '<a href="' . tep_href_link('destination_guide_article.php', 'DgPath=' . $DgPath . '&tours_experience_id=' . $tours['tours_experience_id'] . '&action=edit_info') . '">' . tep_image(DIR_WS_ICONS . 'edit.gif', db_to_html('编辑')) . '</a>'; ?>
				&nbsp;
				<?php
				echo '<a href="JavaScript:void(0)" onClick="DeleteToursExperience(&quot;'.$tours['tours_experience_id'].'&quot;)">' . tep_image(DIR_WS_ICONS . 'delete.gif', db_to_html('删除')) . '</a>';
				
				?>				</td>
              </tr>
			  
<?php
  }
?>
            </table>
			
			
			</td>
          </tr>
          <tr>
            <td colspan="3">
			<table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $tours_split->display_count($tours_query_numrows, $max_now, $_GET['tours_page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                <td class="smallText" align="right"><?php echo $tours_split->display_links($tours_query_numrows, $max_now, MAX_DISPLAY_PAGE_LINKS, $_GET['tours_page'],tep_get_all_get_params(array('tours_page','y','x', 'action')), 'tours_page'); ?></td>
              </tr>
              <tr>
                <td align="left" valign="middle" class="smallText">&nbsp;</td>
                <td align="right" valign="middle" class="smallText">&nbsp;</td>
              </tr>
            </table>
			</td>
          </tr>
        </table>
		
		<?php
		}
		?>
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
