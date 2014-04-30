<?php
/*
  $Id: affiliate_summary.php,v 1.1.1.1 2004/03/04 23:38:10 ccwjr Exp $

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>

<?php require(DIR_WS_INCLUDES . 'javascript/sel_tours_experience_categories.js.php'); ?>

<script type="text/javascript"><!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=450,height=120,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
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
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">美国旅游建议目录管理 Tours Experience Categories Admin</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
	  <tr><td><?php echo tep_draw_separator('pixel_trans.gif', '1', '20'); ?></td></tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td><table width="90%" border="0" cellpadding="4" cellspacing="2" class="dataTableContent">
              <center>
                <tr>
                  <td width="45%" align="left" class="dataTableContent">
				  
				  <?php //
				  $categories_list = '';
				  function get_tours_experience_categories_list(&$categories_list,$parent_id = 0, $exc_str=''){
					  $cate_sql = tep_db_query('SELECT *  FROM `tours_experience_categories` WHERE parent_id= "'.(int)$parent_id.'" ORDER BY tours_experience_categories_id ASC');	
					  while($cate_rows = tep_db_fetch_array($cate_sql)){
						$categories_list.= $cate_rows['tours_experience_categories_id'].'&nbsp;'. $cate_rows['tours_experience_categories_name'] .   '&nbsp;&nbsp;'. $cate_rows['parent_id']."<br>";
						$cate1_sql = tep_db_query('SELECT *  FROM `tours_experience_categories` WHERE parent_id= "'.(int)$cate_rows['tours_experience_categories_id'].'" limit 1');
						while($cate1_rows = tep_db_fetch_array($cate1_sql)){
							get_tours_experience_categories_list($categories_list, $cate_rows['tours_experience_categories_id'], '&nbsp;&nbsp;&nbsp;&nbsp;');
						}
						
					  }
					  
				  }
				  get_tours_experience_categories_list($categories_list);
				  echo $categories_list;
				  ?>
				  
				  </td>
                  <td width="5%" class="dataTableContent">&nbsp;</td>
                  <td width="45%" align="right" class="dataTableContent">&nbsp;</td>
                  <td width="5%" class="dataTableContent">&nbsp;</td>
                </tr>
                <tr>
                  <td width="45%" align="right" class="dataTableContent">&nbsp;</td>
                  <td width="5%" class="dataTableContent">&nbsp;</td>
                  <td width="45%" align="right" class="dataTableContent">&nbsp;</td>
                  <td width="5%" class="dataTableContent">&nbsp;</td>
                </tr>
                <tr>
                  <td width="45%" align="right" class="dataTableContent">&nbsp;</td>
                  <td width="5%" class="dataTableContent">&nbsp;</td>
				  <td width="45%" align="right" class="dataTableContent">&nbsp;</td>
                  <td width="5%" class="dataTableContent"></td>
                </tr>
                
                <tr>
                  <td colspan="4"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                </tr>
                
              </center>
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
<?php require(DIR_WS_INCLUDES . 'application_bottom.php');?>
