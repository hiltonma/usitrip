<?php
require('includes/application_top.php');

$action = (isset($_POST['action']) ? $_POST['action'] : $_GET['action']);
$error = false;
switch($action){
	case 'create_info_confirm':	//创建或修改文章
	case 'edit_info_confirm':
		$tours_experience_id = (int)$_POST['tours_experience_id'];
		$tours_experience_title = tep_db_prepare_input($_POST['tours_experience_title']);
		if(!tep_not_null($tours_experience_title)){
			$error = true;
			$messageStack->add('文章标题：不能为空！', 'error');
		}
		$tours_experience_content = tep_db_prepare_input($_POST['tours_experience_content']);
		$tours_experience_status = (int)$_POST['tours_experience_status'];
		
		$tours_experience_update_time = date('Y-m-d H:i:s');
		$tours_experience_add_time = $tours_experience_update_time;
		
		$meta_title = tep_db_prepare_input($_POST['meta_title']);
		$meta_keywords = tep_db_prepare_input($_POST['meta_keywords']);
		$meta_description = tep_db_prepare_input($_POST['meta_description']);
		$sort_order = (int)$_POST['sort_order'];
		$dg_categories_id = (int)$_POST['dg_categories_id'];
		
		if($error == false){
			$sql_date_array = array('tours_experience_title' => $tours_experience_title,
									'tours_experience_content' => $tours_experience_content,
									'tours_experience_status' => $tours_experience_status,
									'tours_experience_update_time' => $tours_experience_update_time,
									'meta_title' => $meta_title,
									'meta_keywords' => $meta_keywords,
									'meta_description' => $meta_description,
									'sort_order' => $sort_order);
			if($action=='create_info_confirm'){
				$sql_date_array['tours_experience_add_time'] = $tours_experience_add_time;
				tep_db_perform('tours_experience', $sql_date_array);
				$tours_experience_id = tep_db_insert_id();
				$messageStack->add_session('数据添加成功！', 'success');

				$tmp_link = tep_href_link('destination_guide.php', 'DgPath='.$DgPath.'&'.tep_get_all_get_params(array('x','y','action')) );
			}
			if($action=='edit_info_confirm'){
				$thesaurus_ids = @implode(',',$_POST['thesaurus_ids_array']);
				$sql_date_array['thesaurus_ids']=$thesaurus_ids;
				
				tep_db_perform('tours_experience', $sql_date_array, 'update', 'tours_experience_id="'.(int)$tours_experience_id.'" ');
				$messageStack->add_session('数据更新成功！', 'success');

				$tmp_link = tep_href_link('destination_guide.php', 'DgPath='.$DgPath.'&'.tep_get_all_get_params(array('x','y','action')) );
			}
			
			//定义所属目录
			tep_db_query('DELETE FROM `tours_experience_to_guide_categories` WHERE `tours_experience_id` = "'.(int)$tours_experience_id.'" ');
			tep_db_query('INSERT INTO `tours_experience_to_guide_categories` (`tours_experience_id`, `dg_categories_id`) VALUES ("'.(int)$tours_experience_id.'","'.(int)$dg_categories_id.'") ');
			
			//写Tag标签
			tep_db_query('DELETE FROM `tours_experience_to_tags` WHERE `tours_experience_id` = "'.(int)$tours_experience_id.'" ');
			$tag_array = explode('|',html_to_db($_POST['tours_experience_tags_name_string']));
			for($i=0; $i<count($tag_array); $i++){
				$tours_experience_tags_id = 0;
				$tmp_string = trim($tag_array[$i]);
				if(tep_not_null($tmp_string)){
					$check_tag = tep_db_query('SELECT tours_experience_tags_id FROM `tours_experience_tags` WHERE tours_experience_tags_name ="'.$tmp_string.'" limit 1 ');
					$check_row = tep_db_fetch_array($check_tag);
					if((int)$check_row['tours_experience_tags_id']){
						$tours_experience_tags_id = $check_row['tours_experience_tags_id'];
					}else{
						tep_db_query('INSERT INTO `tours_experience_tags` (`tours_experience_tags_name`) VALUES ("'.$tmp_string.'") ');
						$tours_experience_tags_id = tep_db_insert_id();
					}
				}
				if((int)$tours_experience_tags_id){
					tep_db_query('INSERT INTO `tours_experience_to_tags` (`tours_experience_id`, `tours_experience_tags_id`) VALUES ("'.(int)$tours_experience_id.'","'.(int)$tours_experience_tags_id.'") ');
				}
			}
			
			//优化数据表
			tep_db_query('OPTIMIZE TABLE `tours_experience_to_tags`,`tours_experience_tags`,`tours_experience_to_guide_categories`,`tours_experience`');

			if(tep_not_null($tmp_link)){
				tep_redirect($tmp_link);
			}
			exit;
			
		}
		
	
	break;
	
	case 'edit_info':
		if(!isset($_POST['tours_experience_id'])){
			$tours_experience_id = $_GET['tours_experience_id'];
			$tours_sql = tep_db_query('SELECT * FROM `tours_experience` te, `tours_experience_to_guide_categories` tetgc WHERE te.tours_experience_id="'.(int)$tours_experience_id.'" AND te.tours_experience_id = tetgc.tours_experience_id  Group By te.tours_experience_id ');
			$tours_row = tep_db_fetch_array($tours_sql);
			if((int)$tours_row['tours_experience_id']){
				$tours = new objectInfo($tours_row);
				foreach((array)$tours as $key => $val){
					$$key = $val;
				}
			}
		}
	break;
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
function DeleteDgCategories(Categories_id){
	if(Categories_id<1){ alert('no id'); return false;}
	if(window.confirm("您真的要删除这个目录吗？删除目录会连同其所有子级目录均被删除，请谨慎操作。\t")==true){
		parent.location = "<?php echo preg_replace($p,$r,tep_href_link('destination_guide.php','action=DelConfirmed&DgPath='.$DgPath))?>&dg_categories_id="+Categories_id;
		return false;
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
            <td class="pageHeading">目的地指南之旅游建议（攻略）</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
          <!--search form start-->
		  <fieldset>
		  <legend align="left"> Search Module </legend>
		  <?php echo tep_draw_form('form_search', 'destination_guide_article.php', tep_get_all_get_params(array('page','y','x', 'action')), 'get'); ?>
		  
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td>&nbsp;</td>
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
		  <?php echo tep_draw_form('form_info', 'destination_guide_article.php', tep_get_all_get_params(array('y','x', 'action')), 'post', 'id="form_info" enctype="multipart/form-data" '); ?>

		<table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top">
			
			<table border="0" cellspacing="1" cellpadding="2">
              
              <tr class="dataTableRow">
                <td align="right" class="dataTableContent">文章标题：</td>
                <td align="left" class="dataTableContent"><?php echo tep_draw_input_field('tours_experience_title','','size="100"');?>
                  <input name="tours_experience_id" type="hidden" id="tours_experience_id" value="<?php echo (int)$tours_experience_id?>">
                  <?php
				  if($action=='edit_info' || $action=='edit_info_confirm' ){
				  	$action_value = 'edit_info_confirm';
					$parent_id_value = $dg_categories_id ;
				  }else{
				  	$action_value = 'create_info_confirm';
					$parent_id_value = $current_dg_category_id;
				  }
				  ?>
				  <input name="action" type="hidden" id="action" value="<?=$action_value?>"></td>
                </tr>
              <tr class="dataTableRow">
                <td align="right" class="dataTableContent">所属类别：</td>
                <td align="left" class="dataTableContent">
				<?php
				$categories_array = array();
				$categories_array[0] = array('id'=>"0", 'text'=>"顶级");
				$categories_array = tep_get_dg_categories($categories_array);
				//去除$parent_id==$dg_categories_id的数组
				$categories_menu_array = array(); 
				foreach($categories_array as $key => $val){
					$categories_menu_array[] = array('id'=> $val['id'], 'text'=> $val['text']);
				}
				//print_r($categories_array);
				echo tep_draw_pull_down_menu('dg_categories_id ', $categories_menu_array, $parent_id_value);
				?>				</td>
              </tr>
              
              
              <tr class="dataTableRow">
                <td align="right" class="dataTableContent">文章内容：</td>
                <td align="left" class="dataTableContent">
				<?php
				$text_display='none';
				echo tep_draw_textarea_field('tours_experience_content', 'virtual', '110', '20', '', 'style="display:'.$text_display.'"');
				if($text_display=='none'){
				?>
			<iframe id="message___Frame_tours_experience_content" src="FCKeditor/editor/fckeditor.html?InstanceName=tours_experience_content&amp;Toolbar=Default" frameborder="no" height="300" scrolling="no" width="100%"></iframe>
				<?php
				}
				?>			</td>
                </tr>
              
			  <?php
				//文章中的词库关键词
			  if((int)$tours_experience_id){
			  	$thesaurus_text_list = get_thesaurus_checkbox($thesaurus_ids, $tours_experience_content , 'thesaurus_ids_array' );
			  	if(tep_not_null($thesaurus_text_list)){
			  ?>
			  <tr class="dataTableRow">
			    <td height="30" align="right" nowrap class="dataTableContent">文章中的词库关键词：</td>
			    <td align="left" class="dataTableContent">
				<?php
				echo db_to_html($thesaurus_text_list);
				?>
				<br>
				<span style="color:#FF0000; font-size:12px">提示：可选择关键词用于文章内链，如果不选则默认为全部 <a href="<?= tep_href_link('thesaurus.php')?>" target="_blank">到词库添加新词</a></span>
				</td>
		      </tr>
			  <?php
			  	}
			  }
			  ?>
              <tr class="dataTableRow">
                <td align="right" class="dataTableContent">meta_title：</td>
                <td align="left" class="dataTableContent"><?php echo tep_draw_input_field('meta_title','','size="100"');?></td>
                </tr>
              <tr class="dataTableRow">
                <td align="right" class="dataTableContent">meta_keywords：</td>
                <td align="left" class="dataTableContent"><?php echo tep_draw_input_field('meta_keywords','','size="100"');?></td>
                </tr>
              <tr class="dataTableRow">
                <td align="right" class="dataTableContent">meta_description：</td>
                <td align="left" class="dataTableContent"><?php echo tep_draw_input_field('meta_description','','size="100"');?></td>
                </tr>
              
              
              <tr class="dataTableRow">
                <td align="right" class="dataTableContent">Tag：</td>
                <td align="left" class="dataTableContent">
				<?php
				//取得标签
				if(!isset($_POST['tours_experience_tags_name_string'])){
					$tag_sql = tep_db_query('SELECT * FROM `tours_experience_tags` tag, `tours_experience_to_tags` tot WHERE tag.tours_experience_tags_id=tot.tours_experience_tags_id AND tot.tours_experience_id='.(int)$tours_experience_id.' Group BY tag.tours_experience_tags_id ');
					$exp_tags ='';
					while($tag_rows=tep_db_fetch_array($tag_sql)){
						$exp_tags .= db_to_html(tep_db_output($tag_rows['tours_experience_tags_name'])).' | ';
					}
					$tours_experience_tags_name_string = preg_replace('/\| $/','',$exp_tags);
				}
				echo tep_draw_input_field('tours_experience_tags_name_string','','size="100"');
				?>
				<br>多个标签请用"|"隔开				</td>
              </tr>
              <tr class="dataTableRow">
                <td align="right" class="dataTableContent">状态：</td>
                <td align="left" class="dataTableContent">
				<?php
				if($tours_experience_status == '1'){
					$tours_experience_checked = 'checked';
					$tours_experience_checked_0 = '';
				}else{
					$tours_experience_checked = '';
					$tours_experience_checked_0 = 'checked';
				}
				?>
				<input name="tours_experience_status" type="radio" value="1" <?=$tours_experience_checked?>>
				显示
				<input type="radio" name="tours_experience_status" value="0" <?=$tours_experience_checked_0?>>关闭				</td>
              </tr>
              <tr class="dataTableRow">
                <td align="right" class="dataTableContent">排列顺序：</td>
                <td align="left" class="dataTableContent"><?php echo tep_draw_input_field('sort_order',$sort_order,'size="4"');?></td>
              </tr>
            </table>
			
			</td>
          </tr>
          <tr>
            <td colspan="3">
			<table border="0" width="100%" cellspacing="0" cellpadding="2">
              
              <tr>
                <td align="right" valign="middle" class="smallText">
				<?php
				if((int)$current_dg_category_id){
					echo '<a href="' . tep_href_link('destination_guide.php', 'DgPath='.$DgPath.'&'.tep_get_all_get_params(array('DgPath','tours_experience_id','y','x', 'action')) ). '">' .tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';
				}
				?>				</td>
                <td align="left" valign="middle" class="smallText">
				  <input name="submit" type="submit" id="submit" value="<?= db_to_html(' 确定 ')?>">
				<input name="reset" type="reset" id="reset" value="<?= db_to_html(' 重置 ')?>">	
				<?php if((int)$tours_experience_id){?>
				<a href="./../tours-experience.php?tours_experience_id=<?= (int)$tours_experience_id?>" target="_blank">查看前台页面</a>
				<?php }?>
				</td>
              </tr>
            </table>
			</td>
          </tr>
        </table>
			
		  <?= '</form>'?>
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
