<?php
require('includes/application_top.php');

$action = isset($_POST['action']) ? $_POST['action'] : $_GET['action'];
$error = false;
switch($action){
	case 'DelConfirmed':
	if((int)$_GET['os_groups_id']){
		tep_db_query('DELETE FROM `orders_status_groups` WHERE `os_groups_id` = "'.(int)$_GET['os_groups_id'].'"');
		$js_str = '[JS]DelDone("'.(int)$_GET['os_groups_id'].'");[/JS]';
		echo $js_str;
	}
	die();
	break;
	case 'AddConfirmed':
	case 'EditConfirmed':
		$js_str = '';
		if(is_array($_POST['os_groups_names'])){
			foreach($_POST['os_groups_names'] as $key => $val){
				if(tep_not_null($_POST['os_groups_names'][$key])){
					$sql_data_array = array('os_groups_name'=>ajax_to_general_string(tep_db_prepare_input($_POST['os_groups_names'][$key])), 'sort_id'=>(int)$_POST['sort_ids'][$key]);
					$sql_data_array = html_to_db($sql_data_array);
					if($action=='AddConfirmed'){
						tep_db_perform('orders_status_groups', $sql_data_array);
						$insert_id = tep_db_insert_id();
						$js_str .= 'InsertDone('.$insert_id.')';
					}
					if($action=='EditConfirmed'){
						tep_db_perform('orders_status_groups', $sql_data_array, 'update', 'os_groups_id ="'.$key.'" ');
						$js_str .= 'UpdateDone('.$key.');';
					}
				}else{
					$js_str .= 'alert("GroupsName不能为空！");';
				}
			}
		}
		if(tep_not_null($js_str)){
			$js_str = '[JS]'.preg_replace('/[[:space:]]+/',' ',$js_str).'[/JS]';
			echo general_to_ajax_string(db_to_html($js_str));
		}
		die();
	break;
}

//sql  
$sql_str = 'SELECT * FROM `orders_status_groups` WHERE 1 Order By sort_id ASC, os_groups_id ASC ';
//echo $sql_str;
//载入分页类
$orders_status_groups_query_numrows = 0;
$orders_status_groups_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $sql_str, $orders_status_groups_query_numrows);

$orders_status_groups_query = tep_db_query($sql_str);

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">

<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>

<script type="text/javascript" src="includes/menu.js"></script>
<script type="text/javascript" src="includes/big5_gb-min.js"></script>
<script type="text/javascript" src="includes/general.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="includes/javascript/add_global.js"></script>

<script type="text/javascript">
var input_box_tpl = '<?= tep_draw_input_field('TPL');?>';
var input_box_tpl_sub = '<?= tep_draw_input_field('TPL','','size="3"');?>';

function EditInfo(OsGroupsId){
	var name_str = jQuery('#os_groups_name_'+OsGroupsId).html();
	var sort_str = jQuery('#sort_id_'+OsGroupsId).html();
	var btn = '<button type="submit">OK</button>';
	if(name_str.indexOf('<input')==-1){
		name_input = input_box_tpl.replace('name="TPL"','name="os_groups_names['+OsGroupsId+']"');
		jQuery('#os_groups_name_'+OsGroupsId).html(name_input+btn);
		jQuery('#os_groups_name_'+OsGroupsId+' :input').val(name_str);
		
	}
	if(sort_str.indexOf('<input')==-1){
		sort_input = input_box_tpl_sub.replace('name="TPL"','name="sort_ids['+OsGroupsId+']"');
		jQuery('#sort_id_'+OsGroupsId).html(sort_input+btn);
		jQuery('#sort_id_'+OsGroupsId+' :input').val(sort_str);
	}
}

function EditInfoConfirm(obj){ //更新
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('orders_status_groups.php','action=EditConfirmed')) ?>");
	var form_id = obj.id;
	ajax_post_submit(url,form_id);
	return false;
}

function UpdateDone(OsGroupsId){	//更新完成后的操作
	if(OsGroupsId>0){
		var name_html = jQuery('#os_groups_name_'+OsGroupsId+' :input').val();
		var sort_html = jQuery('#sort_id_'+OsGroupsId+' :input').val();
		jQuery('#os_groups_name_'+OsGroupsId).html(name_html);
		jQuery('#sort_id_'+OsGroupsId).html(sort_html);
	}
}

function DelInfo(OsGroupsId){
	if(confirm("<?= JS_MSN_CONFIRM?>")){
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('orders_status_groups.php','action=DelConfirmed')) ?>") + '&os_groups_id='+OsGroupsId;
		ajax_get_submit(url);
	}
	return true;
}

function DelDone(OsGroupsId){ //删除完成后的操作
	jQuery("#TR_"+OsGroupsId).fadeOut(500);
}

function InsertInfo(){	//打开新插入记录窗口
	var html_str = '<form id="formInsert" action="" method="post" enctype="multipart/form-data" onSubmit="InsertInfoConfirm(this); return false;">GroupsName:'+input_box_tpl.replace('name="TPL"','name="os_groups_names[]"') +' Sort:'+ input_box_tpl_sub.replace('name="TPL"','name="sort_ids[]"')+'<button type="submit"> OK </button></form>';
	jQuery("#insertBox").html(html_str);
}

function InsertInfoConfirm(obj){ //插入新记录
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('orders_status_groups.php','action=AddConfirmed')) ?>");
	var form_id = obj.id;
	ajax_post_submit(url,form_id);
	return false;
}

function InsertDone(InsertOsGroupsId){	//插入完成后执行的动作
	var Inputs = jQuery("#insertBox :input");
	var NamesStr = "";
	var SortStr = "";
	for(var i=0; i<Inputs.length; i++){
		if(Inputs[i].name=="os_groups_names[]"){
			NamesStr = Inputs[i].value;
		}
		if(Inputs[i].name=="sort_ids[]"){
			SortStr = Inputs[i].value;
		}
	}
	var app_tr = '<tr id="TR_'+InsertOsGroupsId+'" class="dataTableRow"> <td class="dataTableContent">'+InsertOsGroupsId+'</td> <td height="25" class="dataTableContent" id="os_groups_name_'+InsertOsGroupsId+'">'+ NamesStr +'</td>  <td nowrap class="dataTableContent" id="sort_id_' +InsertOsGroupsId+ '">' +SortStr+ '</td>  <td nowrap class="dataTableContent"> [<a href="JavaScript:void(0);" onClick="EditInfo('+InsertOsGroupsId+')"><?= BTN_EDIT;?></a>]&nbsp; [<a href="JavaScript:void(0);" onClick="DelInfo('+InsertOsGroupsId+'); return false;"><?= BTN_DELETE;?></a>]&nbsp; </td> </tr>';
	jQuery("#StatusGroupsLists").append(app_tr);
	jQuery('#TR_'+InsertOsGroupsId).hide(0);
	jQuery('#TR_'+InsertOsGroupsId).fadeIn(500);
	var html_str = '<a href="javascript:void(0);" onClick="InsertInfo()"><?= tep_image_button('button_insert.gif', IMAGE_INSERT);?></a>';
	jQuery("#insertBox").html(html_str);
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
            <td class="pageHeading"><?php echo HEADING_TITLE;?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      
      <tr>
        <td>
		<fieldset>
		  <legend align="left"> Results Lists </legend>
		  <form id="formLists" name="formLists" action="" method="post" enctype="multipart/form-data" onSubmit="EditInfoConfirm(this); return false;">
		  <table id="StatusGroupsLists" width="100%" border="0" cellspacing="1" cellpadding="0">
			  <tr class="dataTableHeadingRow">
			    <td align="center" nowrap="nowrap" class="dataTableHeadingContent">GroupsId</td>
				<td align="center" nowrap="nowrap" class="dataTableHeadingContent">GroupsName</td>
			    <td align="center" nowrap="nowrap" class="dataTableHeadingContent">Sort</td>
			    <td align="center" nowrap="nowrap" class="dataTableHeadingContent">Action</td>
			  </tr>
			<?php while($orders_status_groups_rows = tep_db_fetch_array($orders_status_groups_query)){?>  
			  <tr id="TR_<?=$orders_status_groups_rows['os_groups_id']?>" class="dataTableRow">
			    <td class="dataTableContent"><?= $orders_status_groups_rows['os_groups_id'];?></td>
			    <td height="25" class="dataTableContent" id="os_groups_name_<?=$orders_status_groups_rows['os_groups_id']?>"><?= tep_db_output($orders_status_groups_rows['os_groups_name']);?></td>
		        <td nowrap class="dataTableContent" id="sort_id_<?=$orders_status_groups_rows['os_groups_id']?>"><?= tep_db_output($orders_status_groups_rows['sort_id']);?></td>
		        <td nowrap class="dataTableContent">
				
				[<a href="JavaScript:void(0);" onClick="EditInfo(<?=$orders_status_groups_rows['os_groups_id']?>)"><?= BTN_EDIT;?></a>]&nbsp;
				[<a href="JavaScript:void(0);" onClick="DelInfo(<?php echo $orders_status_groups_rows['os_groups_id']?>); return false;"><?= BTN_DELETE;?></a>]&nbsp;
				[<a href="<?= tep_href_link('orders_status.php','os_groups_id='.$orders_status_groups_rows['os_groups_id']);?>" target="_blank"><?= BTN_VIEW_ORDERS_STATUS;?></a>]				</td>
			  </tr>
			  
			<?php }?>  
			</table>
			</form>
		</fieldset>		</td>
      </tr>
      <tr>
            
			<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $orders_status_groups_split->display_count($orders_status_groups_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                <td class="smallText" align="right"><?php echo $orders_status_groups_split->display_links($orders_status_groups_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $_GET['page'],tep_get_all_get_params(array('page','y','x', 'action'))); ?>&nbsp;</td>
              </tr>
            </table></td>
          </tr>
      <tr>
            
			<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" align="right" id="insertBox">&nbsp;<a href="javascript:void(0);" onClick="InsertInfo()"><?= tep_image_button('button_insert.gif', IMAGE_INSERT);?></a></td>
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
