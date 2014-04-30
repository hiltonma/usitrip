<?php
/*
  $Id: products_attributes.php,v 1.3 2004/03/16 22:36:34 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  // 备注添加删除
  if($_GET['ajax']=="true"){
  	include DIR_FS_CLASSES . 'Remark.class.php';
  	$remark = new Remark('other_tours_section');
  	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
  }
  
  if(isset($HTTP_GET_VARS['edit']) && $HTTP_GET_VARS['edit'] != "")
  {
  	if($HTTP_GET_VARS['edit'] == 'ny2wdc_tour_section')
	{
		$other_description_1 = addslashes($HTTP_POST_VARS['other_description_1']);
		tep_db_query("update ".TABLE_OTHER_TOUR_SECTION." set other_description = '".$other_description_1."' where other_section_name = 'ny2wdc_tour_section' ");
	}
	elseif($HTTP_GET_VARS['edit'] == 'top_package_tour_section')
	{
		$other_description_2 = addslashes($HTTP_POST_VARS['other_description_2']);
		tep_db_query("update ".TABLE_OTHER_TOUR_SECTION." set other_description = '".$other_description_2."' where other_section_name = 'top_package_tour_section' ");
	}
	elseif($HTTP_GET_VARS['edit'] == 'popular_destination_tour_section')
	{
		$other_description_3 = addslashes($HTTP_POST_VARS['other_description_3']);
		tep_db_query("update ".TABLE_OTHER_TOUR_SECTION." set other_description = '".$other_description_3."' where other_section_name = 'popular_destination_tour_section' ");
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

<script language="javascript"><!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')
}
//--></script>

<?php if ((HTML_AREA_WYSIWYG_DISABLE == 'Enable') or (HTML_AREA_WYSIWYG_DISABLE_JPSY == 'Enable')) { ?>
<script language="Javascript1.2"><!-- // load htmlarea
//MaxiDVD Added WYSIWYG HTML Area Box + Admin Function v1.8 <head>
      _editor_url = "<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN; ?>htmlarea/";  // URL to htmlarea files
        var win_ie_ver = parseFloat(navigator.appVersion.split("MSIE")[1]);
         if (navigator.userAgent.indexOf('Mac')        >= 0) { win_ie_ver = 0; }
          if (navigator.userAgent.indexOf('Windows CE') >= 0) { win_ie_ver = 0; }
           if (navigator.userAgent.indexOf('Opera')      >= 0) { win_ie_ver = 0; }
       <?php if (HTML_AREA_WYSIWYG_BASIC_PD == 'Basic'){ ?>  if (win_ie_ver >= 5.5) {
       document.write('<scr' + 'ipt src="' +_editor_url+ 'editor_basic.js"');
       document.write(' language="Javascript1.2"></scr' + 'ipt>');
          } else { document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); }
       <?php } else{ ?> if (win_ie_ver >= 5.5) {
       document.write('<scr' + 'ipt src="' +_editor_url+ 'editor_advanced.js"');
       document.write(' language="Javascript1.2"></scr' + 'ipt>');
          } else { document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); }
       <?php }?>
// --></script>
<?php }?>
<?php
// WebMakers.com Added: Java Scripts - popup window
include(DIR_WS_INCLUDES . 'javascript/' . 'webmakers_added_js.php')
?>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('other_tours_section');
$list = $listrs->showRemark();
?>
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top">
	<table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->

<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table>
	</td>
<!-- body_text //-->
    <td width="100%" valign="top">
	<table width="100%" border="0" cellspacing="0" cellpadding="2">
<!-- options //-->

<?php 
$sections_query = tep_db_query("select * from ".TABLE_OTHER_TOUR_SECTION." order by other_tour_section_id ");
while($sections_result = tep_db_fetch_array($sections_query))
{
	if($sections_result['other_section_name'] == 'ny2wdc_tour_section')
	{
		$other_description_1 = $sections_result['other_description'];
	}
	if($sections_result['other_section_name'] == 'top_package_tour_section')
	{
		$other_description_2 = $sections_result['other_description'];
	}
	if($sections_result['other_section_name'] == 'popular_destination_tour_section')
	{
		$other_description_3 = $sections_result['other_description'];
	}
}
?>
<form name="ny2wdctour" action="<?php echo FILENAME_OTHER_TOUR_SECTION.'?edit=ny2wdc_tour_section';?>" method="post">

          <tr>
           <td width="28%" valign="top" class="main"><?php echo TEXT_EDIT_NEWYORK_WDC_TOUR; ?></td>
           <td width="72%" valign="top" class="main"><?php echo tep_draw_textarea_field('other_description_1', 'soft', '70', '15', $other_description_1); ?></td>
		 </tr>
		 <tr>
		   <td></td>
		   <td><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></td>
		 </tr>
		 <tr>
		 <td colspan="2"><hr></td>
		 </tr>

</form>
<script language="JavaScript1.2" defer>
             var config = new Object();  // create new config object
             config.width = "<?php echo HTML_AREA_WYSIWYG_WIDTH; ?>px";
             config.height = "<?php echo HTML_AREA_WYSIWYG_HEIGHT; ?>px";
             config.bodyStyle = 'background-color: <?php echo HTML_AREA_WYSIWYG_BG_COLOUR; ?>; font-family: "<?php echo HTML_AREA_WYSIWYG_FONT_TYPE; ?>"; color: <?php echo HTML_AREA_WYSIWYG_FONT_COLOUR; ?>; font-size: <?php echo HTML_AREA_WYSIWYG_FONT_SIZE; ?>pt;';
             config.debug = <?php echo HTML_AREA_WYSIWYG_DEBUG; ?>;
             editor_generate('other_description_1',config);
            </script>

<form name="toppakage" action="<?php echo FILENAME_OTHER_TOUR_SECTION.'?edit=top_package_tour_section';?>" method="post">
		<tr>
           <td class="main" valign="top"><?php echo "Hot Tour Deals Section"; ?></td>
           <td class="main" valign="top"><?php echo tep_draw_textarea_field('other_description_2', 'soft', '70', '15', $other_description_2); ?></td>
		 </tr>
		 <tr>
		   <td></td>
		   <td><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></td>
		 </tr>
		 <tr>
		 <td colspan="2"><hr></td>
		 </tr>
</form>
<script language="JavaScript1.2" defer>
             var config = new Object();  // create new config object
             config.width = "<?php echo HTML_AREA_WYSIWYG_WIDTH; ?>px";
             config.height = "<?php echo HTML_AREA_WYSIWYG_HEIGHT; ?>px";
             config.bodyStyle = 'background-color: <?php echo HTML_AREA_WYSIWYG_BG_COLOUR; ?>; font-family: "<?php echo HTML_AREA_WYSIWYG_FONT_TYPE; ?>"; color: <?php echo HTML_AREA_WYSIWYG_FONT_COLOUR; ?>; font-size: <?php echo HTML_AREA_WYSIWYG_FONT_SIZE; ?>pt;';
             config.debug = <?php echo HTML_AREA_WYSIWYG_DEBUG; ?>;
             editor_generate('other_description_2',config);
            </script>
<form name="popular" action="<?php echo FILENAME_OTHER_TOUR_SECTION.'?edit=popular_destination_tour_section';?>" method="post">
		<tr>
           <td class="main" valign="top"><?php echo TEXT_EDIT_POPULAR_DESTINATION; ?></td>
           <td class="main" valign="top"><?php echo tep_draw_textarea_field('other_description_3', 'soft', '70', '15', $other_description_3); ?></td>
		 </tr>
		 <tr>
		   <td></td>
		   <td><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></td>
		 </tr>
		 <tr>
		 <td colspan="2"><hr></td>
		 </tr>
</form>
<script language="JavaScript1.2" defer>
             var config = new Object();  // create new config object
             config.width = "<?php echo HTML_AREA_WYSIWYG_WIDTH; ?>px";
             config.height = "<?php echo HTML_AREA_WYSIWYG_HEIGHT; ?>px";
             config.bodyStyle = 'background-color: <?php echo HTML_AREA_WYSIWYG_BG_COLOUR; ?>; font-family: "<?php echo HTML_AREA_WYSIWYG_FONT_TYPE; ?>"; color: <?php echo HTML_AREA_WYSIWYG_FONT_COLOUR; ?>; font-size: <?php echo HTML_AREA_WYSIWYG_FONT_SIZE; ?>pt;';
             config.debug = <?php echo HTML_AREA_WYSIWYG_DEBUG; ?>;
             editor_generate('other_description_3',config);
            </script>
    </table>
	<td>
 </tr>
</table>
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
