<?php
/*
本统计表以订单为中心，可能有重复的客户产生
*/

require('includes/application_top.php');

$error_msn = '';
if(tep_not_null($_POST['submit'])){
	switch($_POST['submit']){
		case 'Add':
			$add_class_name = tep_db_prepare_input($_POST['add_class_name']);
			$meta_title = tep_db_prepare_input($_POST['meta_title']);
			$meta_keywords = tep_db_prepare_input($_POST['meta_keywords']);
			$meta_description = tep_db_prepare_input($_POST['meta_description']);

			$add_class_name_array = explode(';', $add_class_name);
			for($i=0; $i<count($add_class_name_array); $i++){
				$add_parent_id = (int)$_POST['add_parent_id'];
				if(tep_not_null($add_class_name_array[$i])){
					$check_sql = tep_db_query('select class_id from seo_class where class_name="'.$add_class_name_array[$i].'" AND parent_id = "'.(int)$add_parent_id.'" limit 1');
					$check_row = tep_db_fetch_array($check_sql);
					if((int)$check_row['class_id']){
						$error_msn .= '不能有重复的同级子目录！<br>';
					}
					if(!tep_not_null($error_msn)){
						$sql_data_array = array('class_name' => $add_class_name_array[$i],
												'parent_id' => $add_parent_id,
												'meta_title' => $meta_title,
												'meta_keywords' => $meta_keywords,
												'meta_description' => $meta_description
												);
						tep_db_perform('seo_class', $sql_data_array);
						tep_db_query('OPTIMIZE TABLE `seo_class` ');
						$done_msn = '添加成功。';
	
					}
				}else{
					$error_msn .= '不能添加空的类别。<br>';
				}
			}
			break;
		case 'Update':
			$class_name = tep_db_prepare_input($_POST['class_name']);
			$meta_title = tep_db_prepare_input($_POST['meta_title']);
			$meta_keywords = tep_db_prepare_input($_POST['meta_keywords']);
			$meta_description = tep_db_prepare_input($_POST['meta_description']);
			$class_id = (int)$_POST['class_id'];
			if(!tep_not_null($class_name)){
				$error_msn .= '类别名称不能为空。';
			}
			if(!tep_not_null($error_msn)){
				$sql_data_array = array('class_name' => $class_name,
										'meta_title' => $meta_title,
										'meta_keywords' => $meta_keywords,
										'meta_description' => $meta_description
										);
				tep_db_perform('seo_class', $sql_data_array,'update',' class_id="'.(int)$class_id.'"');
				tep_db_query('OPTIMIZE TABLE `seo_class` ');
				$done_msn = '目录更新成功。';
			}
			
			break;
		case 'Delete':
			$class_id = (int)$_POST['class_id'];
			if((int)$class_id){
				$class_ids = tep_get_seo_class_tree($class_id,'', '', '', 'true', 'true');
				$class_idstr = $class_id;
				foreach((array)$class_ids as $key => $val){
					$class_idstr .= ','.$val['id'];
				}
				tep_db_query('DELETE FROM `seo_class` WHERE `class_id` in('.$class_idstr.') ');
				tep_db_query('OPTIMIZE TABLE `seo_class` ');
				$done_msn = '目录删除Ok';
				tep_redirect(tep_href_link('seo_admin.php','done_msn='.$done_msn));
			}
			
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
            <td class="pageHeading">类别管理</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
          <!--search form start-->
		  <fieldset>
		  <legend align="left"> Search Module </legend>
		  <?php echo tep_draw_form('form_search', 'seo_admin.php', tep_get_all_get_params(array('page','y','x', 'action')), 'get'); ?>
		  
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td class="main">目录名：<?php echo tep_draw_input_field('search_class_name');?>
			    <input type="submit" name="Submit" value="搜索">
			    <input name="action" type="hidden" id="action" value="search">
			    <a href="<?php echo tep_href_link('seo_news.php')?>">返回文章管理页</a></td>
			  </tr>
			  <tr>
			    <td class="main">
				<?php
				if($_GET['action']=='search' && $_GET['search_class_name']){
					$search_class_name = tep_db_prepare_input($_GET['search_class_name']);
					$sql = tep_db_query('SELECT * FROM `seo_class` WHERE class_name Like "%'.$search_class_name.'%" Order By parent_id ');
					while($rows = tep_db_fetch_array($sql)){
						echo '<a href="'.tep_href_link('seo_admin.php','parent_id='.(int)$rows['class_id']).'">'.$rows['class_id'].'&nbsp;&nbsp;'.tep_db_output($rows['class_name']).'</a><br>';
					}
				}
				?>
				</td>
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
		  

		<table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top" class="main">
			<strong>当前目录树：</strong>
			<?php 
			$now_class_value ='';
			//$class_name ='';
			$parent_id = (int)$parent_id;
			$now_class = tep_get_seo_top_to_now_class($parent_id);
			$now_class_str ='';
			for($i=(int)(count($now_class)-1); $i>=0; $i--){
				if($parent_id==$now_class[$i]['id']){
					$now_class_str .= '<a href="'.tep_href_link('seo_admin.php','parent_id='.$now_class[$i]['id']).'"><b>'.$now_class[$i]['text'].'</b></a> &gt;&gt; ';
					$now_class_value=$now_class[$i]['text'];
					
					//取得当前的tag信息
					$meta_sql = tep_db_query('SELECT meta_title, meta_keywords, meta_description FROM `seo_class` WHERE class_id="'.(int)$now_class[$i]['id'].'" ');
					$meta_row = tep_db_fetch_array($meta_sql);
					$now_meta_title = $meta_row['meta_title'];
					$now_meta_keywords = $meta_row['meta_keywords'];
					$now_meta_description = $meta_row['meta_description'];
					
				}else{
					$now_class_str .= '<a href="'.tep_href_link('seo_admin.php','parent_id='.$now_class[$i]['id']).'">'.$now_class[$i]['text'].'</a> &gt;&gt; ';

				}
			}
			echo $now_class_str;

			?>			</td>
            <td valign="top" class="main"><?php
			$parent_id = (int)$parent_id;
			$class_tree = tep_get_seo_class_tree($parent_id,' | ', $parent_id, '', 'true', 'true');
			foreach((array)$class_tree as $key => $val){
				if($val['text']!='' ){
					echo '<a href="'.tep_href_link('seo_admin.php','parent_id='.(int)$val['id']).'">'.$val['text'].'</a><br>';
				}
			}
			?></td>
            </tr>
          <tr>
            <td align="right" valign="top" class="main"><strong>当前目录：</strong></td>
            <td valign="top" class="main">
			<form action="" method="post" name="ClassForm">
			<?php
			$style ='';
			if((int)$parent_id==0){
				$style = ' readonly="true" ';
			}
			echo tep_draw_input_field('class_name',$now_class_value, $style).'<br>';
			?>
			meta title <?php echo tep_draw_input_field('meta_title',$now_meta_title,' size="86"')?><br>
			meta keywords <?php echo tep_draw_input_field('meta_keywords',$now_meta_keywords,' size="80"')?><br>
			meta description <?php echo tep_draw_input_field('meta_description',$now_meta_description,' size="79"')?><br>

			<?php echo tep_draw_hidden_field('class_id',$parent_id)?>
			<input name="submit" type="submit" id="submit" value="Update"> Or
			<input name="submit" type="submit" id="submit" value="Delete" onClick="delclass(); return false">
			</form>
			<script type="text/javascript">
			function delclass(){
				var tmpvar = window.confirm('删除目录会连其所有的子目录都会删除，您真的要删除吗？');
				if(tmpvar==true){
					submit();
				}
			}
			</script>			</td>
            </tr>
          <tr>
            <td align="right" valign="top" class="main">&nbsp;</td>
            <td valign="top" class="main"><br>	<br>	
			<form action="" method="post" name="ClassForm_2">
			<?php
			$add_class_name ='';
			echo tep_draw_input_field('add_class_name');
			?>
			<?php echo tep_draw_hidden_field('add_parent_id',$parent_id)?>
			
			<input name="submit" type="submit" id="submit" value="Add" >

			子目录<br>			
			<?php
			$meta_title ='';
			$meta_keywords ='';
			$meta_description ='';
			?>
			meta title <?php echo tep_draw_input_field('meta_title','',' size="86"')?><br>
			meta keywords <?php echo tep_draw_input_field('meta_keywords','',' size="80"')?><br>
			meta description <?php echo tep_draw_input_field('meta_description','',' size="79"')?>
			
			</form>			</td>
          </tr>
          <tr>
            <td align="right" valign="top" class="main">&nbsp;</td>
            <td valign="top" class="main" style="color:#FF0000"><?php echo $error_msn?></td>
          </tr>
          <tr>
            <td align="right" valign="top" class="main">&nbsp;</td>
            <td valign="top" class="main" style="color:#00CC00"><?php echo $done_msn?></td>
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
