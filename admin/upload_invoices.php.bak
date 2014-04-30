<?php
//上传地接发票，此文件只有财务可以使用！
require('includes/application_top.php');
if($_FILES['invoices'] && $_POST['orders_id'] && $_POST['products_id'] && $_POST['orders_products_id']){
	if(uploadInvoices('invoices', (int)$_POST['orders_id'], (int)$_POST['products_id'], (int)$_POST['orders_products_id'], array('pdf','rar','zip'))){
		$messageStack->add_session('文件上传成功！', 'success');
		tep_redirect(tep_href_link('upload_invoices.php', 'do=done&'.tep_get_all_get_params(array('page','y','x', 'action'))));
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
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<style>
.dataTableContentUl { padding:0;}
.dataTableContentUl li{height:23px; width:152px;}
.dataTableContentUl li input{width:100px; display:block; float:right;}
.dataTableContentUl span{width:50px; display:block; float:left; text-align:right;}

</style>
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
            <td class="pageHeading">发票上传</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
          <!--search form start-->
		  <fieldset>
		  <legend align="left">上传地接提供的发票</legend>
		  <?php echo tep_draw_form('form_upload', 'upload_invoices.php', tep_get_all_get_params(array('page','y','x', 'action')), 'post', ' enctype="multipart/form-data" '); ?>
		  
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td class="main">
                <input name="invoices" type="file" size="100">
                <button type="submit">开始上传</button>
                订单号：<input readonly type="text" name="orders_id" value="<?= $_GET['orders_id']?>" />
                产品ID：<input readonly type="text" name="products_id" value="<?= $_GET['products_id']?>" />
                <input type="hidden" name="orders_products_id" value="<?= $_GET['orders_products_id']?>" />
                </td>
			  </tr>
			</table>

		  <?php echo '</form>';?>
		  </fieldset>
		  <!--search form end-->
		  </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
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
