<?php
  require('includes/application_top.php');
  // 备注添加删除
  if($_GET['ajax']=="true"){
  	include DIR_FS_CLASSES . 'Remark.class.php';
  	$remark = new Remark('categories_meta_tag');
  	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
  }
  if(isset($_POST['ajax'])&&$_POST['ajax']){
  	switch($_POST['type']){
  		case 'mt' : 
  			$str_sql='update categories_meta_tags set meta_title="'.strip_tags(iconv('utf-8', CHARSET,$_POST['value'])).'" where categories_id='.(int)$_POST['id'].' AND tab_tag="'.$_POST['tab_tag'].'"';
  			echo $str_sql;
  			tep_db_query($str_sql);
  			echo true;
  			exit;
  			break;
  		case 'mk' :
  			$str_sql='update categories_meta_tags set meta_keywords="'.strip_tags(iconv('utf-8', CHARSET,$_POST['value'])).'" where categories_id='.(int)$_POST['id'].' AND tab_tag="'.$_POST['tab_tag'].'"';
  			tep_db_query($str_sql);
  			echo true;
  			exit;break;
  		default: exit;
  	}
  }
$error = false;
$action = $_GET['action'];
switch($action){
	case 'update':
		$sql = tep_db_query('SELECT cmt.*,c.categories_urlname FROM `categories_meta_tags` cmt,categories c WHERE c.categories_id=cmt.categories_id AND cmt.categories_id="'.(int)$_GET['cID'].'" AND cmt.tab_tag="'.tep_db_prepare_input($_GET['tab_tag']).'" ');
		$row = tep_db_fetch_array($sql);
		$Info = new objectInfo($row);	
		$meta_title = $Info->meta_title;
		$meta_keywords = $Info->meta_keywords;
		$meta_description = $Info->meta_description;
		$url_name=$Info->categories_urlname;
	break;
	
	case 'DelConfirmed':
		tep_db_query("DELETE FROM `categories_meta_tags` WHERE `categories_id` = '".(int)$_GET['cID']."' AND `tab_tag` = '".tep_db_prepare_input($_GET['tab_tag'])."' LIMIT 1");
		$messageStack->add_session('数据删除成功！', 'success');
		tep_redirect(tep_href_link('categories_meta_tag.php', 'cID=' . (int)$_GET['cID']));
	break;
}

//插入/更新信息
if(tep_not_null($_GET['InsertSubmit'])){
	$categories_id = (int)$_GET['cID'];
	if(!(int)$categories_id){
		$error = true;
		$messageStack->add('Categories：请选择有效的目录', 'error');
	}
	$tab_tag = tep_db_prepare_input($_GET['tab_tag']);
	if(strlen($tab_tag)<2){
		$error = true;
		$messageStack->add('Tab Tag：请选择有效的Tag', 'error');
	}
	$meta_title = tep_db_prepare_input($_GET['meta_title']);
	if(!tep_not_null($meta_title)){
		$error = true;
		$messageStack->add('Meta Title：不能为空！', 'error');
	}
	$meta_keywords = tep_db_prepare_input($_GET['meta_keywords']);
	if(!tep_not_null($meta_keywords)){
		$error = true;
		$messageStack->add('Meta Keywords：不能为空！', 'error');
	}
	$meta_description = tep_db_prepare_input($_GET['meta_description']);
	if(!tep_not_null($meta_description)){
		$error = true;
		$messageStack->add('Meta Description：不能为空！', 'error');
	}
	
	if($error==false){
		$data_array = array('meta_title'=>$meta_title, 'meta_keywords'=>$meta_keywords , 'meta_description'=>$meta_description);
		//如果数据库中已经有该$tab_tag和$categories_id的信息了就更新，否则就插入
		$check_sql = tep_db_query('SELECT categories_id FROM `categories_meta_tags` WHERE categories_id="'.$categories_id.'" and tab_tag="'.$tab_tag.'" ');
		$check_row = tep_db_fetch_array($check_sql);
		if(!(int)$check_row['categories_id']){
			$data_array['categories_id'] = $categories_id;
			$data_array['tab_tag'] = $tab_tag;
			tep_db_perform('categories_meta_tags', $data_array);
			$messageStack->add_session('数据添加成功！', 'success');
			tep_redirect(tep_href_link('categories_meta_tag.php', (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . 'cID=' . $categories_id));
		}else{
			tep_db_perform('categories_meta_tags', $data_array, 'update', ' categories_id="'.$categories_id.'" and tab_tag="'.$tab_tag.'" ');
			$messageStack->add_session('数据更新成功！', 'success');
			tep_redirect(tep_href_link('categories_meta_tag.php', (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . 'cID=' . $categories_id));
		}
	}
}

//搜索

  $where_exc = '';
  if((int)$_GET['cID']){
  	$where_exc .= ' AND cmt.categories_id="'.(int)$_GET['cID'].'" ';
  }
  if(isset($_GET['url_name'])&&$_GET['url_name']){
  	$where_exc.=' AND c.categories_urlname like "%'.$_GET['url_name'].'%"';
  }
  $search_query_raw = "SELECT cmt.*, cd.categories_name,c.categories_urlname FROM `categories_meta_tags` cmt, `categories_description` cd,categories c WHERE cd.categories_id=cmt.categories_id AND cd.categories_id=c.categories_id AND cd.language_id='".$languages_id."' ".$where_exc." Order By cmt.categories_id";
  $search_query_numrows = 0;
  $MAX_DISPLAY_SEARCH_RESULTS_ADMIN = MAX_DISPLAY_SEARCH_RESULTS_ADMIN;
  //$MAX_DISPLAY_SEARCH_RESULTS_ADMIN = 3;
  $search_split = new splitPageResults($_GET['page'], $MAX_DISPLAY_SEARCH_RESULTS_ADMIN , $search_query_raw, $search_query_numrows);

  $search_query = tep_db_query($search_query_raw);
  
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>

<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
<script language="javascript" src="includes/general.js"></script>
<?php
$p=array('/&amp;/','/&quot;/');
$r=array('&','"');
?>
<script type="text/javascript">
<!--

function DelMetaTags(cID, tab_tag){
	if(cID<1){ alert('no id'); return false;}
	if(window.confirm("您真的要删除这个记录吗？请谨慎操作。\t")==true){
		parent.location = "<?php echo preg_replace($p,$r,tep_href_link('categories_meta_tag.php','action=DelConfirmed'))?>&cID="+cID+"&tab_tag="+tab_tag;
		return false;
	}
}

function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
//-->
</script>
<script type="text/javascript">
function myShow(doc){
	doc=doc.parent('td');
	if(doc.find("#text").is(":hidden")){
	doc.find("#text").show();
	doc.find("#text>input")[0].focus();
	doc.find("#show").hide();
	}	
}
function changeOne(type,value,doc,id,tab_tag){
	var doc_td=doc.parent('span').parent('td');
	
	if(doc_td.find("#show").text()!=value){
		jQuery.post("categories_meta_tag.php",{ajax:true,type:type,value:value,id:id,tab_tag:tab_tag},function(result){
		if(result){
			doc_td.find("#text").hide();
			doc_td.find("#show").text(value);
			doc_td.find("#show").show();
		}
	});
	}
}
</script>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">





<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('categories_meta_tag');
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
            <td class="pageHeading"><?php echo db_to_html('Categories Meta Tags 管理'); ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
          <!--search form start-->
		  <fieldset>
		  <legend align="left"> Search Module </legend>
		  <?php echo tep_draw_form('form_search', 'categories_meta_tag.php', tep_get_all_get_params(array('page','y','x', 'action', 'InsertSubmit')), 'get'); ?>
		  
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td><table border="0" cellspacing="0" cellpadding="0" style="margin:10px;">
                  
                  <tr>
                    <td colspan="2" align="left" class="main"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td align="right">Categories:</td>
                        <td align="left"><?php echo tep_draw_pull_down_menu('cID', tep_get_category_tree(), $cID, 'onChange="this.form.submit();"');?></td>
                        <td>&nbsp;</td>
                        <td align="right">Tab Tag:</td>
                        <td align="left">
						<?php
						$tags_optin = array();
						$tags_optin[] = array('id'=>0,'text'=>'All Tag');
						$tags_optin = array_merge($tags_optin,get_categories_tab_tags());
						echo tep_draw_pull_down_menu('tab_tag', $tags_optin, $tab_tag);
						?>						</td>
                        </tr>
                      <tr>
                        <td align="right">Meta Title:</td>
                        <td align="left" colspan="4"><?php echo tep_draw_input_field('meta_title', '', 'size="73"')?></td>
                        </tr>
                      <tr>
                        <td align="right">Meta Keywords:</td>
                        <td align="left" colspan="4"><?php echo tep_draw_input_field('meta_keywords', '', 'size="73"')?></td>
                        </tr>
                      <tr>
                        <td align="right">Meta Description:</td>
                        <td colspan="4" align="left"><?php echo tep_draw_textarea_field('meta_description', 'soft', '100', '5');?></td>
                        </tr>
						<tr>
                        <td align="right">Url Name:</td>
                        <td align="left" colspan="4"><?php echo tep_draw_input_field('url_name', '', 'size="73"')?></td>
                        </tr>
                    </table></td>
                    </tr>
                  <tr>
                    <td class="main" align="center">&nbsp;<input name="Send" type="submit" value="搜索" style="width:100px; height:30px; margin-top:10px;"><input name="InsertSubmit" type="submit" id="InsertSubmit" style="width:100px; height:30px; margin-top:10px;" value="插入/更新"></td>
                    <td class="main" align="left"><input style="width:100px; height:30px; margin-top:10px;" type="button" onClick="location.href='categories_meta_tag.php'" value="清除搜索结果" /></td>
                  </tr>
                </table></td>
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

		<table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="1" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" nowrap="nowrap"><?php echo db_to_html('Categories ID'); ?></td>
				<td class="dataTableHeadingContent" nowrap="nowrap"><?php echo db_to_html('Categories Name'); ?></td>
				<td class="dataTableHeadingContent" nowrap="nowrap"><?php echo db_to_html('Tab Tag'); ?></td>
				<td class="dataTableHeadingContent" nowrap="nowrap"><?php echo db_to_html('Meta Title'); ?></td>
				<td class="dataTableHeadingContent" nowrap="nowrap"><?php echo db_to_html('Meta Keywords'); ?></td>
				<!-- <td class="dataTableHeadingContent" nowrap="nowrap"><?php echo db_to_html('Meta Description'); ?></td>-->
				<td class="dataTableHeadingContent" nowrap="nowrap"><?php echo db_to_html('Url Name'); ?></td>
				<td class="dataTableHeadingContent" nowrap="nowrap"><?php echo db_to_html('Action'); ?></td>
              </tr>
<?php
  
  while ($search = tep_db_fetch_array($search_query)) {
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
                <td class="dataTableContent"><a href="<?php echo tep_href_link('categories.php','action=edit_category&cID='.$search['categories_id'])?>"><?php echo tep_db_output($search['categories_id']); ?></a></td>
				<td class="dataTableContent"><?php echo tep_db_output($search['categories_name']); ?></td>
				<td class="dataTableContent"><b><?php echo db_to_html(get_categories_tab_tags($search['tab_tag'])); ?></b></td>
				<td class="dataTableContent">
				<?php if(!$search['meta_title']){?>
				<input type="button" onClick="myShow(jQuery(this))" value="+++" />
				<?php }?>
				<span id="show" onClick="myShow(jQuery(this))" style="width:600px">
				<?=db_to_html(tep_db_output($search['meta_title']))?>
				</span>
				<span style="display:none" id="text">
				<input type="text" value="<?=db_to_html(tep_db_output($search['meta_title']))?>" onBlur="changeOne('mt',this.value,jQuery(this),<?=$search['categories_id']?>,'<?=$search['tab_tag']?>')" size="100"/>
				</span>
				<?php db_to_html(tep_db_output($search['meta_title'])); ?></td>
				<td class="dataTableContent">
				<?php if(!$search['meta_keywords']){?>
				<input type="button" onClick="myShow(jQuery(this))" value="+++" />
				<?php }?>
				<span id="show" onClick="myShow(jQuery(this))" style="width:600px">
				<?=db_to_html(tep_db_output($search['meta_keywords']))?>
				</span>
				<span style="display:none" id="text">
				<input type="text" value="<?=db_to_html(tep_db_output($search['meta_keywords']))?>" onBlur="changeOne('mk',this.value,jQuery(this),<?=$search['categories_id']?>,'<?=$search['tab_tag']?>')" size="100"/>
				</span>
				<?php  db_to_html(tep_db_output($search['meta_keywords'])); ?></td>
				<!--<td class="dataTableContent"><?php echo db_to_html(tep_db_output($search['meta_description'])); ?></td>-->
				<td class="dataTableContent"><?php echo db_to_html(tep_db_output($search['categories_urlname'])); ?></td>
				<td class="dataTableContent"><a href="<?php echo tep_href_link('categories_meta_tag.php',(isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '').'cID='.$search['categories_id'].'&tab_tag='.$search['tab_tag'].'&action=update');?>">Update</a>&nbsp;&nbsp;<a href="JavaScript:void(0);" onClick="DelMetaTags(<?= (int)$search['categories_id']?>,'<?= $search['tab_tag']?>'); return false;">Delete</a></td>
              </tr>
			  
<?php
  }
?>
            </table></td>
          </tr>
		  
		  <tr>
            
			<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $search_split->display_count($search_query_numrows, $MAX_DISPLAY_SEARCH_RESULTS_ADMIN , $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                <td class="smallText" align="right"><?php echo $search_split->display_links($search_query_numrows, $MAX_DISPLAY_SEARCH_RESULTS_ADMIN , MAX_DISPLAY_PAGE_LINKS, $_GET['page'],tep_get_all_get_params(array('page','y','x', 'action', 'InsertSubmit'))); ?>&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table>
		
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
