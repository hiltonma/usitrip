<?php
/*
本统计表以订单为中心，可能有重复的客户产生
*/

require('includes/application_top.php');
// 备注添加删除
if($_GET['ajax']=="true"){
	include DIR_FS_CLASSES . 'Remark.class.php';
	$remark = new Remark('seo_news');
	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
}
$error_msn = '';

switch($_GET['action']){
	case 'DelConfirmed':
		if((int)$_GET['news_id']){
			tep_db_query('DELETE FROM `seo_news` WHERE `news_id` = "'.(int)$_GET['news_id'].'" ');
			tep_db_query('DELETE FROM `seo_news_to_class` WHERE `news_id` = "'.(int)$_GET['news_id'].'" ');
			tep_db_query('DELETE FROM `seo_news_description` WHERE `news_id` = "'.(int)$_GET['news_id'].'" ');
			tep_db_query('OPTIMIZE TABLE `seo_news` , `seo_news_to_class`, `seo_news_description` ');
			
		}
	break;
	
}


//搜索
$where_exc = '';
if(tep_not_null($_GET['search_news_title'])){
	$where_exc .= ' AND news_title like "%'.(string)$_GET['search_news_title'].'%" ';
}
if((int)$_GET['class_id']){
	$in_cids = (int)$_GET['class_id'];
	$class_array = tep_get_seo_class_tree((int)$_GET['class_id'],'', '', '', 'true', 'true');
	if((int)count($class_array)){
		for($i=0; $i< count($class_array); $i++){
			$in_cids.= ','.$class_array[$i]['id'];
		}
	}
	$where_exc .= ' AND itc.class_id in ('.$in_cids.') ';
}

//sql  
$sql_str = 'SELECT * FROM `seo_news` i, `seo_news_to_class` itc, `seo_news_description` id  WHERE i.news_id=itc.news_id AND i.news_id=id.news_id '.$where_exc.' Order By i.news_id DESC ';
//echo $sql_str;
//载入分页类
$news_query_numrows = 0;
$news_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $sql_str, $news_query_numrows);

$news_query = tep_db_query($sql_str);

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
<!--

function DelInfo(t_id){
	if(t_id<1){ alert('no id'); return false;}
	if(window.confirm("您真的要删除这个记录吗？请谨慎操作。\t")==true){
		parent.location = "<?php echo preg_replace($p,$r,tep_href_link('seo_news.php','action=DelConfirmed'))?>&news_id="+t_id;
		return false;
	}
}

function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
//-->
</script>
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
$listrs = new Remark('seo_news');
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
            <td class="pageHeading"><?php echo SEO_NEWS_SYSTEM?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
          <!--search form start-->
		  <fieldset>
		  <legend align="left"> Search Module </legend>
		  <?php echo tep_draw_form('form_search', 'seo_news.php', tep_get_all_get_params(array('page','y','x', 'action')), 'get'); ?>
		  
		  <table border="0" cellspacing="0" cellpadding="0">
			  <tr>
			    <td align="right" nowrap class="main">&nbsp;</td>
			    <td colspan="7" align="left" class="main">
				<?php
				if(tep_not_null($_SESSION['msn_str'])){
					echo $_SESSION['msn_str'];
					unset($_SESSION['msn_str']);
				}
				?>
				</td>
		      </tr>
			  <tr>
				<td align="right" nowrap class="main">标题：</td>
			    <td align="left" class="main"><?php echo tep_draw_input_field('search_news_title');?></td>
			    <td class="main">&nbsp;</td>
			    <td align="right" nowrap class="main">类别：</td>
			    <td class="main">
				<?php
				$top_seo_class_tree = tep_get_seo_class_tree('0','', '', '', 'true', 'true');
				echo tep_draw_pull_down_menu('class_id', $top_seo_class_tree, $class_id );
				
				?>				</td>
			    <td class="main">&nbsp;<a href="<?php echo tep_href_link('seo_admin.php')?>">加类别</a></td>
			    <td class="main">&nbsp;</td>
			    <td class="main">&nbsp;</td>
			  </tr>
			  <tr>
			    <td class="main"><input type="submit" name="Submit" value="搜索">
                  <input name="action" type="hidden" id="action" value="search"></td>
			    <td class="main">&nbsp;</td>
			    <td colspan="6" align="right" class="main"><input name="Submit2" type="button" onClick="MM_goToURL('parent','seo_news_create.php?class_id=<?php echo $class_id?>');return document.MM_returnValue" value="添加新记录"></td>
		      </tr>
			</table>

		  <?php echo '</form>';?>
		  </fieldset>
		  <!--search form end-->		  </td>
      </tr>
      <tr>
        <td>
		<fieldset>
		  <legend align="left"> Stats Results </legend>
		  <table width="100%" border="0" cellspacing="1" cellpadding="0">
			  <tr class="dataTableHeadingRow">
			    <td align="center" nowrap="nowrap" class="dataTableHeadingContent">ID号</td>
				<td align="center" nowrap="nowrap" class="dataTableHeadingContent">标题</td>
			    <td align="center" nowrap="nowrap" class="dataTableHeadingContent">Meta Title</td>
			    <td align="center" nowrap="nowrap" class="dataTableHeadingContent">Meta Keywords</td>
			    <td align="center" nowrap="nowrap" class="dataTableHeadingContent">Meta Description</td>
			    <td align="center" nowrap="nowrap" class="dataTableHeadingContent">类别</td>
			    <td align="center" nowrap="nowrap" class="dataTableHeadingContent">日期</td>
			    <td align="center" nowrap="nowrap" class="dataTableHeadingContent">浏览量</td>
			    <td align="center" nowrap="nowrap" class="dataTableHeadingContent">状态</td>
			    <td align="center" nowrap="nowrap" class="dataTableHeadingContent">操作</td>
			  </tr>
			<?php while($news_rows = tep_db_fetch_array($news_query)){?>  
			  <tr class="dataTableRow">
			    <td class="dataTableContent"><?php echo tep_db_output($news_rows['news_id']);?></td>
			    <td height="25" class="dataTableContent"><?php echo tep_db_output($news_rows['news_title']);?></td>
		        <td class="dataTableContent"><?php echo tep_db_output($news_rows['meta_title']);?></td>
		        <td class="dataTableContent"><?php echo tep_db_output($news_rows['meta_keywords']);?></td>
			    <td class="dataTableContent"><?php echo tep_db_output($news_rows['meta_description']);?></td>
			    <td class="dataTableContent">
				
				<?php
				$now_class = tep_get_seo_top_to_now_class((int)$news_rows['class_id']);
				$now_class_str ='';
				for($i=(int)(count($now_class)-2); $i>=0; $i--){
					if($parent_id==$now_class[$i]['id']){
						$now_class_str .= '<a href="'.tep_href_link('seo_news.php','class_id='.$now_class[$i]['id']).'"><b>'.$now_class[$i]['text'].'</b></a> &gt;&gt; ';
						$now_class_value=$now_class[$i]['text'];
						
					}else{
						$now_class_str .= '<a href="'.tep_href_link('seo_news.php','class_id='.$now_class[$i]['id']).'">'.$now_class[$i]['text'].'</a> &gt;&gt; ';
	
					}
				}
				echo preg_replace('/&gt;&gt; $/','',$now_class_str);
				?>				</td>
			    <td class="dataTableContent"><?php echo tep_db_output($news_rows['news_add_date']);?></td>
			    <td class="dataTableContent"><?php echo tep_db_output($news_rows['news_click_num']);?></td>
			    <td class="dataTableContent"><?php echo tep_db_output($news_rows['news_state']);?></td>
			    <td nowrap class="dataTableContent">
				
				[<a href="<?php echo tep_href_link('seo_news_edit.php','news_id='.(int)$news_rows['news_id'])?>">编辑</a>]&nbsp;
				[<a href="JavaScript:void(0);" onClick="DelInfo(<?php echo $news_rows['news_id']?>); return false;">删除</a>]				</td>
			  </tr>
			  
			<?php }?>  
			</table>
		</fieldset>		</td>
      </tr>
      <tr>
            
			<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $news_split->display_count($news_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                <td class="smallText" align="right"><?php echo $news_split->display_links($news_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $_GET['page'],tep_get_all_get_params(array('page','y','x', 'action'))); ?>&nbsp;</td>
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
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
