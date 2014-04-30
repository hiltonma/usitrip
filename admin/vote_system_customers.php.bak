<?php
/*
  $Id: languages.php,v 1.1.1.1 2004/03/04 23:38:39 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'insert':
		
        break;
      case 'save':

        break;
      case 'deleteconfirm':
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

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
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
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">
			<?php
			echo TOURS_TOTE_SYSTEM;
			echo '-&gt;';
			//取得调查名称
			$v_system_sql = tep_db_query('SELECT v_s_title FROM `vote_system` WHERE v_s_id="'.(int)$_GET['v_s_id'].'" limit 1');
			$v_system_row = tep_db_fetch_array($v_system_sql); 
			echo $v_system_row['v_s_title'];
			echo '-&gt;';

			//取得调查所属项目
			$v_item_sql = tep_db_query('SELECT v_s_i_title FROM `vote_system_item` WHERE  v_s_i_id="'.(int)$_GET['v_s_i_id'].'" limit 1');
			$v_item_row = tep_db_fetch_array($v_item_sql); 
			echo $v_item_row['v_s_i_title'];
			echo '-&gt;';

			//取得调查答案
			$v_options_sql = tep_db_query('SELECT v_s_i_o_title FROM `vote_system_item_options` WHERE  v_s_i_o_id="'.(int)$_GET['v_s_i_o_id'].'" limit 1');
			$v_options_row = tep_db_fetch_array($v_options_sql); 
			echo $v_options_row['v_s_i_o_title'];

			?>
			</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellpadding="0" cellspacing="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent">客户名称</td>
                <td class="dataTableHeadingContent">客户电子邮箱</td>
				<td class="dataTableHeadingContent">&nbsp;</td>
              </tr>
<?php
  $v_s_results_query_raw = "select * from vote_system_results WHERE v_s_i_o_id=".(int)$_GET['v_s_i_o_id']." order by v_s_r_date DESC";
  $v_s_results_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $v_s_results_query_raw, $v_s_results_query_numrows);
  $v_s_results_query = tep_db_query($v_s_results_query_raw);

  while ($v_s_results_rows = tep_db_fetch_array($v_s_results_query)) {

?>
			   <tr class="dataTableRow" onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)" >
		
				<td class="dataTableContent"><?php echo tep_customers_name($v_s_results_rows['customers_id'])?></td>
						
                <td class="dataTableContent" align="left">
				<?php
				if($can_see_customers_email_full_address === true){
					tep_get_customers_email($v_s_results_rows['customers_id']);
				}else{
					echo '[已隐藏]';
				}
				?>
				</td>
                
				<td class="dataTableContent" align="left"><?php echo nl2br(tep_db_output($v_s_results_rows['text_vote']))?></td>

				</tr>
<?php
  }
?>
              <tr>
                <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $v_s_results_split->display_count($v_s_results_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_VOTE); ?></td>
                    <td class="smallText" align="right"><?php echo $v_s_results_split->display_links($v_s_results_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></td>
                  </tr>

                </table></td>
              </tr>
            </table></td>
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