<?php
require('includes/application_top.php');
if($_POST['action']=='AddConfirmation'){
	$error = false;
	$t_companion_title = tep_db_prepare_input($HTTP_POST_VARS['t_companion_title']);
	if(!tep_not_null($t_companion_title)){
		$error = true;
		$messageStack->add(db_to_html('标题：不能为空'),'error');
	}
	$t_companion_content = tep_db_prepare_input($HTTP_POST_VARS['t_companion_content']);
	if(!tep_not_null($t_companion_content)){
		$error = true;
		$messageStack->add(db_to_html('内容：不能为空'),'error');
	}
	$customers_name = tep_db_prepare_input($HTTP_POST_VARS['customers_name']);
	$customers_id = (int)$HTTP_POST_VARS['customers_id'];
	$customers_phone = tep_db_prepare_input($HTTP_POST_VARS['customers_phone']);
	$email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);
	$reply_num = (int)$HTTP_POST_VARS['reply_num'];
	$products_id = (int)$HTTP_POST_VARS['products_id'];
	$categories_id = (int)$HTTP_POST_VARS['categories_id'];
	$status = (int)$HTTP_POST_VARS['status'];
	$add_time = date('Y-m-d H:i:s');
	$last_time = $add_time;
	$click_num = (int)($HTTP_POST_VARS['click_num']);
	$bbs_type = (int)($HTTP_POST_VARS['bbs_type']);
	$admin_id = $login_id;
	
	if($error==false){
		$date_array = array('t_companion_title'=>$t_companion_title,
							'customers_name'=>$customers_name,
							'customers_id'=>$customers_id,
							'customers_phone'=>$customers_phone,
							'email_address'=>$email_address,
							't_companion_content'=>$t_companion_content,
							'reply_num'=>$reply_num,
							'products_id'=>$products_id,
							'categories_id'=>$categories_id,
							'status'=>$status,
							'add_time'=>$add_time,
							'last_time'=>$last_time,
							'click_num'=>$click_num,
							'bbs_type'=> $bbs_type,
							'admin_id'=> $admin_id
							 );
		
		$date_array = html_to_db($date_array);
		tep_db_perform('travel_companion', $date_array);
		$messageStack->add_session('帖子添加成功', 'success');
		tep_redirect(tep_href_link('travel_companion.php'));
	}
}

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
<script language="javascript">
<!--
<?php
$p=array('/&amp;/','/&quot;/');
$r=array('&','"');
?>

function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
//-->
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
            <td class="pageHeading">结伴同游增加</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
		
		
		<fieldset>
		  <legend align="left"> <?php echo tep_db_output('新增帖子')?> </legend>

		<table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top">
			<form action="<?php tep_href_link('travel_companion_re.php','action=Update')?>" method="post" name="edit_form" id="edit_form">
              <table border="0" cellspacing="1" cellpadding="2">
			  <tr>
                <td align="right" nowrap="nowrap" class="dataTableHeadingRow dataTableHeadingContent">类型：</td>
                <td align="left" nowrap="nowrap" class="dataTableRow dataTableContent">
				<?php
				$option_array = array();
				$t_sql = tep_db_query('SELECT * FROM `travel_companion_bbs_type` Order By type_id ASC ');
				while($t_rows=tep_db_fetch_array($t_sql)){
					$option_array[]=array('id'=> $t_rows['type_id'], 'text'=> $t_rows['type_name']);
				}
				
				echo tep_draw_pull_down_menu('bbs_type', $option_array);
				?>				</td>
              </tr>
              <tr>
                <td align="right" nowrap="nowrap" class="dataTableHeadingRow dataTableHeadingContent">标题：</td>
				<td align="left" nowrap="nowrap" class="dataTableRow dataTableContent"><?php echo tep_draw_input_field('t_companion_title','',' size="50" ')?></td>
				</tr>
              <tr>
                <td align="right" nowrap="nowrap" class="dataTableHeadingRow dataTableHeadingContent">内容：</td>
                <td align="left" class="dataTableRow dataTableContent"><?php echo tep_draw_textarea_field('t_companion_content','virtual',50,5)?></td>
				</tr>
<?php /*             
			  <tr>
                <td align="right" nowrap="nowrap" class="dataTableHeadingRow dataTableHeadingContent">姓名：</td>
                <td align="left" class="dataTableRow dataTableContent"><?php echo tep_draw_input_field('customers_name','',' size="50" ')?></td>
              </tr>
              <tr>
                <td align="right" nowrap="nowrap" class="dataTableHeadingRow dataTableHeadingContent">客户ID：</td>
                <td align="left" class="dataTableRow dataTableContent"><?php echo tep_draw_input_field('customers_id')?></td>
              </tr>
              <tr>
                <td align="right" nowrap="nowrap" class="dataTableHeadingRow dataTableHeadingContent">电话：</td>
                <td align="left" class="dataTableRow dataTableContent"><?php echo tep_draw_input_field('customers_phone','',' size="50" ')?></td>
              </tr>
              <tr>
                <td align="right" nowrap="nowrap" class="dataTableHeadingRow dataTableHeadingContent">电子邮箱：</td>
                <td align="left" class="dataTableRow dataTableContent"><?php echo tep_draw_input_field('email_address','',' size="50" ')?></td>
              </tr>
              
			  <tr>
                <td align="right" nowrap="nowrap" class="dataTableHeadingRow dataTableHeadingContent">回贴数：</td>
                <td align="left" class="dataTableRow dataTableContent"><?php echo tep_draw_input_field('reply_num','',' readonly="true" size="10"')?></td>
              </tr>
*/?>
              <tr>
                <td align="right" nowrap="nowrap" class="dataTableHeadingRow dataTableHeadingContent">相关产品：</td>
                <td align="left" class="dataTableRow dataTableContent"><?php echo tep_draw_input_field('products_id')?>可输入产品id如：789</td>
              </tr>
              <tr>
                <td align="right" nowrap="nowrap" class="dataTableHeadingRow dataTableHeadingContent">相关目录：</td>
                <td align="left" class="dataTableRow dataTableContent"><?php echo tep_draw_input_field('categories_id')?>可输入目录id如：256</td>
              </tr>
              <tr>
                <td align="right" nowrap="nowrap" class="dataTableHeadingRow dataTableHeadingContent">状态：</td>
                <td align="left" class="dataTableRow dataTableContent"><?php echo tep_draw_input_field('status')?>0是关闭，1是显示</td>
              </tr>
<?php /*              
              <tr>
                <td align="right" nowrap="nowrap" class="dataTableHeadingRow dataTableHeadingContent">点击量：</td>
                <td align="left" class="dataTableRow dataTableContent"><?php echo tep_draw_input_field('click_num')?></td>
              </tr>
*/?>
              <tr>
                <td align="right" nowrap="nowrap" class="dataTableRow dataTableContent"><input name="action" type="hidden" id="action" value="AddConfirmation"></td>
                <td align="left" class="dataTableRow dataTableContent"><input type="submit" name="Submit" value="确定"><input type="reset" value="重置"><input name="" type="button" onClick="MM_goToURL('parent','<?php echo tep_href_link('travel_companion.php')?>');return document.MM_returnValue" value="返回上一页"></td>
              </tr>
            </table>
			</form>			</td>
            </tr>
        </table>
		</fieldset>		</td>
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
