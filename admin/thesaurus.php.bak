<?php
require('includes/application_top.php');

$action = isset($_POST['action']) ? $_POST['action'] : $_GET['action'];
$error = false;
switch($action){
	case 'DelConfirmed':
		if((int)$_GET['thesaurus_id']){
			tep_db_query('DELETE FROM `keyword_thesaurus` WHERE `thesaurus_id` = "'.(int)$_GET['thesaurus_id'].'" ');
			$messageStack->add_session('删除成功！', 'success');
			$tmp_link = tep_href_link('thesaurus.php', tep_get_all_get_params(array('x','y','action','thesaurus_id')) );
			tep_redirect($tmp_link);
		}
	break;
	case 'setstate':
		if((int)$_GET['thesaurus_id']){
			$thesaurus_state = (int)$_GET['thesaurus_state'];
			tep_db_query('UPDATE `keyword_thesaurus` SET `thesaurus_state` = "'.$thesaurus_state.'" WHERE `thesaurus_id` = "'.$_GET['thesaurus_id'].'"  ');
			$messageStack->add_session('状态更新成功！', 'success');
			$tmp_link = tep_href_link('thesaurus.php', 'thesaurus_id=' . $_GET['thesaurus_id'].'&'.tep_get_all_get_params(array('x','y','action','thesaurus_state','thesaurus_id')) );
			tep_redirect($tmp_link);
		}
	break;
	case 'set_use_search':
		if((int)$_GET['thesaurus_id']){
			$use_search = (int)$_GET['use_search'];
			tep_db_query('UPDATE `keyword_thesaurus` SET `use_search` = "'.$use_search.'" WHERE `thesaurus_id` = "'.$_GET['thesaurus_id'].'"  ');
			$messageStack->add_session('设置成功！', 'success');
			$tmp_link = tep_href_link('thesaurus.php', 'thesaurus_id=' . $_GET['thesaurus_id'].'&'.tep_get_all_get_params(array('x','y','action','use_search','thesaurus_id')) );
			tep_redirect($tmp_link);
		}
	break;
	case 'set_use_inner_link':
		if((int)$_GET['thesaurus_id']){
			$use_inner_link = (int)$_GET['use_inner_link'];
			tep_db_query('UPDATE `keyword_thesaurus` SET `use_inner_link` = "'.$use_inner_link.'" WHERE `thesaurus_id` = "'.$_GET['thesaurus_id'].'"  ');
			$messageStack->add_session('设置成功！', 'success');
			$tmp_link = tep_href_link('thesaurus.php', 'thesaurus_id=' . $_GET['thesaurus_id'].'&'.tep_get_all_get_params(array('x','y','action','use_inner_link','thesaurus_id')) );
			tep_redirect($tmp_link);
		}
	break;
	case 'search_or_add':
	case 'EditConfirmed':
		if(isset($_GET['add_new_key']) || $action=='EditConfirmed'){	//添加/编辑新词条
			$thesaurus_text = tep_db_prepare_input(strip_tags($_GET['search_thesaurus_text']));
			$thesaurus_text = str_replace(' ','',$thesaurus_text);
			
			$thesaurus_text_en = strtolower(strip_tags($_GET['search_thesaurus_text_en']));
			$thesaurus_text_en = str_replace(' ','',$thesaurus_text_en);
			
			if(!tep_not_null($thesaurus_text) || strlen($thesaurus_text)<4){
				$error = true;
				if($_GET['ajax']=='true'){
					$error='关键字：不能少于2个汉字或4个字母！';
					echo '[ERROR]'.db_to_html($error).'[/ERROR]';
					exit;
				}else{
					$messageStack->add('关键字：不能少于2个汉字或4个字母！', 'error');
				}
			}else{
				$where_exc='';
				if((int)$_GET['thesaurus_id']){
					$where_exc = ' AND thesaurus_id!='.(int)$_GET['thesaurus_id'];
				}
				$check_sql = tep_db_query('SELECT thesaurus_id FROM `keyword_thesaurus` WHERE thesaurus_text ="'.$thesaurus_text.'" '.$where_exc.' limit 1 ');
				$check_row = tep_db_fetch_array($check_sql);
				if((int)$check_row['thesaurus_id']){
					$error = true;
					if($_GET['ajax']=='true'){
						$error='关键字重复：数据库已经有相同的词了！';
						echo '[ERROR]'.db_to_html($error).'[/ERROR]';
						exit;
					}else{
						$messageStack->add('关键字重复：数据库已经有相同的词了！', 'error');
					}
				}
			}
			
			if(!tep_not_null($thesaurus_text_en) || strlen($thesaurus_text_en)<2 || !preg_match('/^[a-zA-Z0-9]+$/',$thesaurus_text_en)){
				$error = true;
				if($_GET['ajax']=='true'){
					$error='英文关键字：不能少于2个字母！需要是全英文或拼音';
					echo '[ERROR]'.db_to_html($error).'[/ERROR]';
					exit;
				}else{
					$messageStack->add('英文关键字：不能少于2个字母！需要是全英文或拼音', 'error');
				}
			}else{
				$where_exc='';
				if((int)$_GET['thesaurus_id']){
					$where_exc = ' AND thesaurus_id!='.(int)$_GET['thesaurus_id'];
				}
				$check_sql = tep_db_query('SELECT thesaurus_id FROM `keyword_thesaurus` WHERE thesaurus_text_en ="'.$thesaurus_text_en.'" '.$where_exc.' limit 1 ');
				$check_row = tep_db_fetch_array($check_sql);
				if((int)$check_row['thesaurus_id']){
					$error = true;
					if($_GET['ajax']=='true'){
						$error='英文关键字重复：数据库已经有相同的英文关键词了！';
						echo '[ERROR]'.db_to_html($error).'[/ERROR]';
						exit;
					}else{
						$messageStack->add('英文关键字重复：数据库已经有相同的英文关键词了！', 'error');
					}
				}
			}
			
			if($error == false){
				if($action=='EditConfirmed' && (int)$_GET['thesaurus_id']){
					tep_db_query('UPDATE `keyword_thesaurus` SET `thesaurus_text`="'.html_to_db($thesaurus_text).'", `thesaurus_text_en`="'.html_to_db($thesaurus_text_en).'", `thesaurus_text_length`="'.strlen($thesaurus_text).'" WHERE thesaurus_id="'.(int)$_GET['thesaurus_id'].'" ;');
					if($_GET['ajax']=='true'){
						echo db_to_html($thesaurus_text);
						exit;
					}else{
						$messageStack->add_session('数据更新成功！', 'success');
						$tmp_link = tep_href_link('thesaurus.php', 'thesaurus_id=' . $_GET['thesaurus_id'].'&'.tep_get_all_get_params(array('x','y','action','ajax','thesaurus_id','thesaurus_text','add_new_key')) );
					}
				}
				if(isset($_GET['add_new_key'])){
					tep_db_query('INSERT INTO `keyword_thesaurus` (`thesaurus_text`,`thesaurus_text_en`,`thesaurus_text_length`) VALUES ("'.html_to_db($thesaurus_text).'", "'.$thesaurus_text_en.'", "'.strlen($thesaurus_text).'");');
					$tmp_link = tep_href_link('thesaurus.php');
					$messageStack->add_session('数据添加成功！', 'success');
				}
				tep_db_query('OPTIMIZE TABLE `keyword_thesaurus` ');
				tep_redirect($tmp_link);
			}
			
		}
	break;
	case 'auto_get_from_city_and_categories':	//自动从景点库提取关键词
		$names = array();
		$cate_sql = tep_db_query('SELECT categories_name  FROM `categories`, categories_description` WHERE language_id="1" and categories_status="1" ');
		while($cate_rows = tep_db_fetch_array($cate_sql)){
			$cates = explode(' ',strip_tags($cate_rows['categories_name']));
			$chn = $cates[0];
			$enn = substr($cate_rows['categories_name'],strlen($chn), strlen($cate_rows['categories_name']));
			$enn = strtolower(str_replace(' ','',$enn));
			$enn = str_replace('/','',$enn);
			$names[] = array('chn'=>$chn, 'enn'=> $enn);
		}
		
		$city_sql = tep_db_query('SELECT city FROM `tour_city` where departure_city_status ="1" ');
		while($city_rows = tep_db_fetch_array($city_sql)){
			$chn = strtolower(strip_tags($city_rows['city']));
			$chn = (str_replace(' ','',$chn));
			$chn = (str_replace('/','',$chn));
			$enn = '';
			$names[] = array('chn'=>$chn, 'enn'=> $enn);
		}

		for($i=0; $i<count($names); $i++){
			$check_sql = tep_db_query('SELECT thesaurus_id FROM `keyword_thesaurus` WHERE thesaurus_text ="'.$names[$i]['chn'].'" limit 1 ');
			$check_row = tep_db_fetch_array($check_sql);
			if(!(int)$check_row['thesaurus_id']){
				$use_inner_link = '0';
				if(strlen($names[$i]['enn'])>2){ $use_inner_link = '1'; }
				tep_db_query('INSERT INTO `keyword_thesaurus` (`thesaurus_text`,`thesaurus_text_en`,`thesaurus_text_length`,`use_inner_link`) VALUES ("'.$names[$i]['chn'].'", "'.$names[$i]['enn'].'", "'.strlen($names[$i]['chn']).'", "'.$use_inner_link.'");');
			}
		}
		$tmp_link = tep_href_link('thesaurus.php');
		$messageStack->add_session('数据添加成功！', 'success');
		tep_db_query('OPTIMIZE TABLE `keyword_thesaurus` ');
		tep_redirect($tmp_link);
	break;
}


//搜索
$where_exc = '';
if(tep_not_null($_GET['search_thesaurus_text'])){
	if($_GET['match']=='1'){
		$where_exc .= ' AND thesaurus_text ="'.(string)$_GET['search_thesaurus_text'].'" ';
	}else{
		$where_exc .= ' AND thesaurus_text like "%'.(string)$_GET['search_thesaurus_text'].'%" ';
	}
}
if(tep_not_null($_GET['search_thesaurus_text_en'])){
	$where_exc .= ' AND thesaurus_text_en like "%'.(string)$_GET['search_thesaurus_text_en'].'%" ';
}

//sql  
$sql_str = 'SELECT * FROM `keyword_thesaurus` WHERE thesaurus_id > 0  '.$where_exc.' Order By thesaurus_id DESC ';
//echo $sql_str;
//载入分页类
$thesaurus_query_numrows = 0;
$thesaurus_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $sql_str, $thesaurus_query_numrows);

$thesaurus_query = tep_db_query($sql_str);

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
		parent.location = "<?php echo preg_replace($p,$r,tep_href_link('thesaurus.php','action=DelConfirmed'))?>&thesaurus_id="+t_id;
		return false;
	}
}
function EditInfo(id_name, h_id){
	var thesaurus_text_obj = document.getElementById(id_name);
	var thesaurus_text_obj_en = document.getElementById(id_name+"_en");
	if(thesaurus_text_obj!=null && h_id>0){
		var oldval = thesaurus_text_obj.innerHTML;
		var oldvalen = thesaurus_text_obj_en.innerHTML;
		if(oldval.indexOf('<form')==-1){
			thesaurus_text_obj.innerHTML = '<form action="" method="get" name="min_form_'+h_id+'" id="min_form_'+h_id+'" onSubmit="SubmitEditInfo(this); return false;"><input name="thesaurus_text" type="text" value="'+ oldval +'" onblur="this.value = simplized(this.value)"><input name="thesaurus_text_en" type="text" value="'+ oldvalen +'"><input name="thesaurus_id" type="hidden" value="'+h_id+'"><input type="submit" name="Submit" value="OK"></form>';
		}

	}
}

function SubmitEditInfo(form_obj){
	var url = "<?php echo preg_replace($p,$r,tep_href_link('thesaurus.php','ajax=true&action=EditConfirmed'))?>&search_thesaurus_text="+form_obj.elements['thesaurus_text'].value+"&search_thesaurus_text_en="+form_obj.elements['thesaurus_text_en'].value+"&thesaurus_id="+form_obj.elements['thesaurus_id'].value;
	ajax.open("GET", url, true);
	ajax.send(null); 
	
	ajax.onreadystatechange = function() { 
		if (ajax.readyState == 4 && ajax.status == 200 ) {
			if(ajax.responseText.indexOf('[ERROR]')>-1){
				alert(ajax.responseText);
			}else{
				var thesaurus_text = document.getElementById('thesaurus_text_'+form_obj.elements['thesaurus_id'].value);
				var thesaurus_text_en = document.getElementById('thesaurus_text_'+form_obj.elements['thesaurus_id'].value+'_en');
				if(thesaurus_text!=null){
					thesaurus_text.innerHTML = ajax.responseText;
				}
				if(thesaurus_text_en!=null){
					thesaurus_text_en.innerHTML = form_obj.elements['thesaurus_text_en'].value;
				}
			}
		}		
	}
}

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
            <td class="pageHeading"><?php echo db_to_html('词库管理')?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
          <!--search form start-->
		  <fieldset>
		  <legend align="left"> Search Module </legend>
		  <?php echo tep_draw_form('form_search', 'thesaurus.php', tep_get_all_get_params(array('page','y','x', 'action')), 'get'); ?>
		  
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
				<td align="right" nowrap class="main">关键字：</td>
			    <td align="left" class="main"><?php echo tep_draw_input_field('search_thesaurus_text');?> <?php echo tep_draw_checkbox_field('match','1');?>完全匹配&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			    <td class="main">&nbsp;</td>
			    <td align="right" nowrap class="main">英文关键字：</td>
			    <td class="main"><?php echo tep_draw_input_field('search_thesaurus_text_en');?></td>
			    <td class="main">&nbsp;</td>
			    <td class="main">&nbsp;</td>
			    <td class="main">&nbsp;</td>
			  </tr>
			  <tr>
			    <td class="main"><input type="submit" name="Submit" value="搜索">
                  <input name="action" type="hidden" id="action" value="search_or_add"></td>
			    <td class="main">&nbsp;</td>
			    <td colspan="6" align="right" class="main"><input name="add_new_key" type="submit" value="添加新词"> Or <a href="<?php echo tep_href_link('thesaurus.php','action=auto_get_from_city_and_categories');?>">自动从景点库提取关键词</a></td>
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
				<td align="center" nowrap="nowrap" class="dataTableHeadingContent">词语</td>
			    <td align="center" nowrap="nowrap" class="dataTableHeadingContent">词语[英文]</td>
			    <td align="center" nowrap="nowrap" class="dataTableHeadingContent">用于搜索扩展</td>
			    <td align="center" nowrap="nowrap" class="dataTableHeadingContent">用于文章内链</td>
			    <td align="center" nowrap="nowrap" class="dataTableHeadingContent">状态</td>
			    <td align="center" nowrap="nowrap" class="dataTableHeadingContent">操作</td>
			  </tr>
			<?php while($thesaurus_rows = tep_db_fetch_array($thesaurus_query)){?>  
			  <tr class="dataTableRow">
			    <td class="dataTableContent"><?php echo tep_db_output($thesaurus_rows['thesaurus_id']);?></td>
			    <td height="25" class="dataTableContent"><b id="thesaurus_text_<?=$thesaurus_rows['thesaurus_id']?>"><?php echo tep_db_output($thesaurus_rows['thesaurus_text']);?></b></td>
		        <td nowrap class="dataTableContent"><b id="thesaurus_text_<?=$thesaurus_rows['thesaurus_id']?>_en"><?php echo tep_db_output($thesaurus_rows['thesaurus_text_en']);?></b></td>
		        <td nowrap class="dataTableContent">
				<?php
				$tmp_var = ((int)$thesaurus_rows['use_search']) ? '<a href="' . tep_href_link('thesaurus.php', 'action=set_use_search&use_search=0&thesaurus_id=' . $thesaurus_rows['thesaurus_id'].'&'.tep_get_all_get_params(array('x','y','action','use_search','thesaurus_id'))) . '" title="点击切换为No" style="color:#009900">Yes</a>' : '<a href="' . tep_href_link('thesaurus.php', 'action=set_use_search&use_search=1&thesaurus_id=' . $thesaurus_rows['thesaurus_id'].'&'.tep_get_all_get_params(array('x','y','action','use_search','thesaurus_id'))) . '" title="点击切换为Yes" style="color:#FF0000">No</a>';
				echo $tmp_var;
				?>				</td>
		        <td nowrap class="dataTableContent">
				<?php
				$tmp_var = ((int)$thesaurus_rows['use_inner_link']) ? '<a href="' . tep_href_link('thesaurus.php', 'action=set_use_inner_link&use_inner_link=0&thesaurus_id=' . $thesaurus_rows['thesaurus_id'].'&'.tep_get_all_get_params(array('x','y','action','use_inner_link','thesaurus_id'))) . '" title="点击切换为No" style="color:#009900">Yes</a>' : '<a href="' . tep_href_link('thesaurus.php', 'action=set_use_inner_link&use_inner_link=1&thesaurus_id=' . $thesaurus_rows['thesaurus_id'].'&'.tep_get_all_get_params(array('x','y','action','use_inner_link','thesaurus_id'))) . '" title="点击切换为Yes" style="color:#FF0000">No</a>';
				echo $tmp_var;
				?>				</td>
		        <td nowrap class="dataTableContent">
				<?php
					  if ($thesaurus_rows['thesaurus_state'] == '1') {
							echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link('thesaurus.php', 'action=setstate&thesaurus_state=0&thesaurus_id=' . $thesaurus_rows['thesaurus_id'].'&'.tep_get_all_get_params(array('x','y','action','thesaurus_state','thesaurus_id'))) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
						
					  } else {
						echo '<a href="' . tep_href_link('thesaurus.php', 'action=setstate&thesaurus_state=1&thesaurus_id=' . $thesaurus_rows['thesaurus_id'] .'&'.tep_get_all_get_params(array('x','y','action','thesaurus_state','thesaurus_id'))) .'">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
					  }
				?>				</td>
		        <td nowrap class="dataTableContent">
				
				[<a href="JavaScript:void(0);" onClick="EditInfo(&quot;thesaurus_text_<?=$thesaurus_rows['thesaurus_id']?>&quot;,<?=$thesaurus_rows['thesaurus_id']?>)">编辑</a>]&nbsp;
				[<a href="JavaScript:void(0);" onClick="DelInfo(<?php echo $thesaurus_rows['thesaurus_id']?>); return false;">删除</a>]				</td>
			  </tr>
			  
			<?php }?>  
			</table>
		</fieldset>		</td>
      </tr>
      <tr>
            
			<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $thesaurus_split->display_count($thesaurus_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                <td class="smallText" align="right"><?php echo $thesaurus_split->display_links($thesaurus_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $_GET['page'],tep_get_all_get_params(array('page','y','x', 'action'))); ?>&nbsp;</td>
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
