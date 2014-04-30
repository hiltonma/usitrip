<?php
require('includes/application_top.php');

function CheckDuplicateKeyword($key0, $key1, $where = ""){
	$sql = tep_db_query('SELECT key_id FROM '.TABLE_FAULT_TOLERANT_KEYWORDS.' WHERE (key_words0="'.tep_db_input($key0).'" AND key_words1="'.tep_db_input($key1).'") || (key_words0="'.tep_db_input($key1).'" AND key_words1="'.tep_db_input($key0).'") '.$where );
	$row = tep_db_fetch_array($sql);
	return (int)$row['key_id'];
}

$action = isset($_POST['action']) ? $_POST['action'] : $_GET['action'];
$error = false;
switch($action){
	case "addConfirm":
		if(tep_not_null($_POST['key_words0']) && tep_not_null($_POST['key_words1'])){
			$key_words0 = strip_tags(tep_db_prepare_input(ajax_to_general_string($_POST['key_words0'])));
			$key_words1 = strip_tags(tep_db_prepare_input(ajax_to_general_string($_POST['key_words1'])));
			if((int)CheckDuplicateKeyword($key_words0, $key_words1)){
				$js_str = '[JS]alert("数据库已经存在您输入的数据！");[/JS]';
				echo db_to_html($js_str);
				die();
			}elseif(trim($key_words0) == trim($key_words1)){
				$js_str = '[JS]alert("关键词与容错关键词重复！");[/JS]';
				echo db_to_html($js_str);
				die();
			}
			
			$data = array('key_words0' => $key_words0,
						  'key_words1' => $key_words1
						  );
			$data = html_to_db ($data);
		
			tep_db_perform(TABLE_FAULT_TOLERANT_KEYWORDS, $data);
			$key_id = tep_db_insert_id();
			tep_db_query('OPTIMIZE TABLE '.TABLE_FAULT_TOLERANT_KEYWORDS);
			$js_str = '[JS]keyAddedAction('.$key_id.');[/JS]';
			echo $js_str;
		}
		exit;
	break;
	case "updateConfirm":
		foreach($_GET as $key => $val){
			if(strpos($key,'key_words0')!==false || strpos($key,'key_words1')!==false){
				$tmp_get_array = explode('_',$key);
				$fName = $tmp_get_array[0].'_'.$tmp_get_array[1];
				$idValue = (int)$tmp_get_array[2];
				$value = strip_tags(tep_db_prepare_input(ajax_to_general_string($val)));
				// Check Data
				$sql = tep_db_query('SELECT * FROM '.TABLE_FAULT_TOLERANT_KEYWORDS.' WHERE key_id="'.$idValue.'" ' );
				$row = tep_db_fetch_array($sql);
				$_value = $row['key_words0'];
				if($fName=="key_words0"){ $_value = $row['key_words1']; }
				if((int)CheckDuplicateKeyword($value, $_value, ' AND key_id!="'.$idValue.'" ')){
					$js_str = '[JS]alert("数据库已经存在您输入的数据！");[/JS]';
					echo db_to_html($js_str);
					die();
				}elseif(trim($value) == trim($_value)){
					$js_str = '[JS]alert("关键词与容错关键词重复！");[/JS]';
					echo db_to_html($js_str);
					die();
				}
				
				$data = array($fName => $value);
				$data = html_to_db ($data);

				tep_db_perform(TABLE_FAULT_TOLERANT_KEYWORDS, $data, 'update', ' key_id="'.$idValue.'"');
				$js_str = '[JS]inputChangeToSpan(document.getElementById("'.$key.'")); jQuery("#old_'.$key.'").val("'.$value.'"); [/JS]';
				echo $js_str;
			}
			tep_db_query('OPTIMIZE TABLE '.TABLE_FAULT_TOLERANT_KEYWORDS);
		}
		exit;
	break;
	case "deleteConfirm":
		if((int)$_GET['key_id']){
			tep_db_query('DELETE FROM '.TABLE_FAULT_TOLERANT_KEYWORDS.' WHERE `key_id` = "'.(int)$_GET['key_id'].'" ');
			tep_db_query('OPTIMIZE TABLE '.TABLE_FAULT_TOLERANT_KEYWORDS);
			
			$js_str = '[JS]jQuery("#tr_'.(int)$_GET['key_id'].'").fadeOut(500);[/JS]';
			echo $js_str;
		}
		exit;
	break;
}

//数据查询 {
$table = TABLE_FAULT_TOLERANT_KEYWORDS.' as ftk ';
$where = ' 1 ';
$order_by = ' key_words1 ASC, key_words0 ASC ';
if(tep_not_null($_GET['key_words0'])){
	//$where .= ' AND (key_words0 Like "%'.tep_db_input(tep_db_prepare_input($_GET['key_words0'])).'%" || key_words1 Like "%'.tep_db_input(tep_db_prepare_input($_GET['key_words0'])).'%") ';
	$where .= ' AND key_words0 Like "%'.tep_db_input(tep_db_prepare_input($_GET['key_words0'])).'%" ';
}
if(tep_not_null($_GET['key_words1'])){
	//$where .= ' AND (key_words0 Like "%'.tep_db_input(tep_db_prepare_input($_GET['key_words1'])).'%" || key_words1 Like "%'.tep_db_input(tep_db_prepare_input($_GET['key_words1'])).'%") ';
	$where .= ' AND key_words1 Like "%'.tep_db_input(tep_db_prepare_input($_GET['key_words1'])).'%" ';
}

$sql_str = 'SELECT * FROM '.$table.' WHERE '.$where.' ORDER BY '.$order_by;
$keywords_query_numrows = 0;
$keywords_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $sql_str, $keywords_query_numrows);

$keywords_query = tep_db_query($sql_str);


//数据查询 }

$p=array('/&amp;/','/&quot;/');
$r=array('&','"');
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">

<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
<script language="javascript" src="includes/javascript/add_global.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script type="text/javascript">
	<?php if(strtolower(CHARSET)=="big5"){?>
	var onblur0 = 'obj.value = traditionalized(obj.value); ';
	<?php }else{?>
	var onblur0 = 'obj.value = simplized(obj.value); ';
	<?php }?>

function spanOnClick(obj){
	if(obj.tagName.toLowerCase()=="span"){
		jQuery(obj).replaceWith('<input id="'+obj.id+'" name="'+ obj.id +'" onblur="inputOnBlur(this)" onkeydown="Kdown(this)" type="text" value="'+ jQuery(obj).text() +'" />');
	}
}

/* 提交输入的数据 */
function inputOnBlur(obj){
	if(obj.tagName.toLowerCase()=="input"){
		eval(onblur0);
		val = obj.value.replace(/[\"\<\>]+/g,'');
		if(val.length<2){
			alert("输入框文字至少要2个字！");
			return false;
		}
		if(jQuery("#old_"+obj.id).val() != val){
			var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo("keywords_fault_tolerant.php",'action=updateConfirm')) ?>");
			url += '&'+obj.id+'='+encodeURIComponent(val);
			url += '&ajax=true';
			ajax_get_submit(url);
		}else{
			inputChangeToSpan(obj);
		}
	}
}

function inputChangeToSpan(obj){
	if(obj.tagName.toLowerCase()=="input"){
		val = obj.value.replace(/[\"\<\>]+/g,'');
		jQuery(obj).replaceWith('<span id="'+obj.id+'" onclick="spanOnClick(this)">'+ val +'</span>');
	}
}

/* 按回车键时提交数据 */
function Kdown(obj){
	obj.onkeydown = function(e){
		var e = window.event || e;
		if(e.keyCode==13){ inputOnBlur(obj); }
	}
}

function keyEdit(keyId){
	spanOnClick(document.getElementById('key_words0_'+keyId));
	spanOnClick(document.getElementById('key_words1_'+keyId));
}

function keyDelete(keyId){
	if(confirm("您确定要删除这个关键词？删除后不可再恢复！")){
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo("keywords_fault_tolerant.php",'action=deleteConfirm')) ?>");
		 url += '&key_id='+keyId;
		 ajax_get_submit(url);
	}
}

function keyAdd(){
	var form_id = "form_search";
	var form = document.getElementById(form_id);
	if(form.elements['key_words0'].value=="" || form.elements['key_words1'].value==""){
		alert("请输入关键词后再点击“增加”按钮！");
		return false;
	}
	
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo("keywords_fault_tolerant.php",'action=addConfirm')) ?>");
	ajax_post_submit(url,form_id);
}

function keyAddedAction(insert_id){
	var form_id = "form_search";
	var form = document.getElementById(form_id);
	var key_val0 = form.elements['key_words0'].value.replace(/[\"\<\>]+/g,'');
	var key_val1 = form.elements['key_words1'].value.replace(/[\"\<\>]+/g,'');
	form.elements['key_words0'].value = form.elements['key_words1'].value = "";
	var insertHtml = 
	'<tr id="tr_' +insert_id+ '" class="dataTableRow">' +
			    '<td class="dataTableContent">' +insert_id+ '</td>' +
			    '<td height="25" class="dataTableContent" ><span id="key_words0_' +insert_id+ '" onClick="spanOnClick(this)">' +key_val0+ '</span></td>' +
		        '<td nowrap class="dataTableContent" ><span id="key_words1_' +insert_id+ '" onClick="spanOnClick(this)">' +key_val1+ '</span></td>' +
		        '<td nowrap class="dataTableContent">' +
		        	'[<a href="javascript:void(0)" onClick="keyEdit(' +insert_id+ ')">编辑</a>]&nbsp;' +
		        	'[<a href="javascript:void(0)" onClick="keyDelete(' +insert_id+ ')">删除</a>]' +
					'<input type="hidden" id="old_key_words0_' +insert_id+ '" value="' +key_val0+ '" />' +
					'<input type="hidden" id="old_key_words1_' +insert_id+ '" value="' +key_val1+ '" />' +
					'</td>' +
			  '</tr>' ;
	jQuery("#dataTable tr:first").after(insertHtml);
	jQuery('#tr_' +insert_id).hide();
	jQuery('#tr_' +insert_id).fadeIn(400);
}

/* 快捷键搜索或增加记录 */
jQuery(document).ready(function(){
	jQuery("#form_search").submit(function(){
		formSearchSubmit(this);
	});
});

function formSearchSubmit(obj){
	obj.onkeydown = function(e){
		var e = window.event || e;
		//alert(e.keyCode);
		if(e.ctrlKey && e.keyCode==13){
			keyAdd();
		}else if(e.keyCode==13){ obj.submit();}
		
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
            <td class="pageHeading"><?php echo db_to_html('关键词容错表')?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
          <!--search form start-->
		  <fieldset>
		  <legend align="left"> Search Module </legend>
		  <?php echo tep_draw_form('form_search', 'keywords_fault_tolerant.php', tep_get_all_get_params(array('page','y','x', 'action')), 'get', 'id="form_search" onsubmit="return false;" '); ?>
			<table width="200" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="main" nowrap>主关键词：<?= tep_draw_input_field('key_words0')?>&nbsp;</td>
					<td class="main" nowrap>模糊关键词：<?= tep_draw_input_field('key_words1')?>&nbsp;</td>
					<td class="main" nowrap><input type="submit" style="display:none" /><input type="button" onClick="jQuery('#form_search').submit();" value="搜索(Enter)" /> 或 <input type="button" onClick="keyAdd()" value="增加(Ctrl+Enter)" /> <a href="<?= tep_href_link("keywords_fault_tolerant.php");?>">清空搜索选项</a></td>
				</tr>
			</table>
			<div>说明：主关键词是指我们网站上能搜索到的关键字，如旧金山，模糊关键词是指搜索不到的关键词，如三藩市。</div>
			<div><?php if($login_groups_id == '1'){ echo $sql_str; }?></div>

		  <?php echo '</form>';?>
		  </fieldset>
		  <!--search form end-->		  </td>
      </tr>
      <tr>
        <td>
		<fieldset>
		  <legend align="left"> Stats Results </legend>
		  <table id="dataTable" border="0" cellspacing="1" cellpadding="0">
			  <tr class="dataTableHeadingRow">
			    <td width="150" align="center" nowrap="nowrap" class="dataTableHeadingContent">ID号</td>
				<td width="300" align="center" nowrap="nowrap" class="dataTableHeadingContent">主关键词</td>
			    <td width="300" align="center" nowrap="nowrap" class="dataTableHeadingContent">模糊关键词</td>
			    <td align="center" nowrap="nowrap" class="dataTableHeadingContent">操作</td>
			  </tr>
			<?php while($keywords_rows = tep_db_fetch_array($keywords_query)){?>  
			  <tr id="tr_<?= $keywords_rows['key_id'];?>" class="dataTableRow">
			    <td class="dataTableContent"><?= $keywords_rows['key_id'];?></td>
			    <td height="25" class="dataTableContent" ><span id="key_words0_<?= $keywords_rows['key_id'];?>" onClick="spanOnClick(this)"><?= $keywords_rows['key_words0'];?></span></td>
		        <td nowrap class="dataTableContent" ><span id="key_words1_<?= $keywords_rows['key_id'];?>" onClick="spanOnClick(this)"><?= $keywords_rows['key_words1'];?></span></td>
		        <td nowrap class="dataTableContent">
		        	[<a href="javascript:void(0)" onClick="keyEdit(<?= $keywords_rows['key_id'];?>)">编辑</a>]&nbsp;
		        	[<a href="javascript:void(0)" onClick="keyDelete(<?= $keywords_rows['key_id'];?>)">删除</a>]
					<input type="hidden" id="old_key_words0_<?= $keywords_rows['key_id'];?>" value="<?= $keywords_rows['key_words0'];?>" />
					<input type="hidden" id="old_key_words1_<?= $keywords_rows['key_id'];?>" value="<?= $keywords_rows['key_words1'];?>" />
					</td>
			  </tr>
			  
			<?php }?>  
			</table>
		</fieldset>		</td>
      </tr>
      <tr>
            
			<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $keywords_split->display_count($keywords_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                <td class="smallText" align="right"><?php echo $keywords_split->display_links($keywords_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $_GET['page'],tep_get_all_get_params(array('page','y','x', 'action'))); ?>&nbsp;</td>
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
