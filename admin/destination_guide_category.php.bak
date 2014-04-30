<?php
require('includes/application_top.php');

$action = (isset($_POST['action']) ? $_POST['action'] : $_GET['action']);
$error = false;

$ParentDgPath ='';
for($i=0; $i<max((count($DgPath_array)-1), 0); $i++){
	$ParentDgPath.=$DgPath_array[$i].'_';
}
$ParentDgPath = substr($ParentDgPath,0,(strlen($ParentDgPath)-1));


switch($action){
	case 'delete_image_confirm':	//删除概况图片
		if((int)$_GET['dg_categories_id'] && (int)$_GET['images_id']){
			//删除服务器图片，路径/images/destination_guide/
			$sql = tep_db_query('SELECT * FROM destination_guide_categories_images WHERE `dg_categories_id`="'.(int)$_GET['dg_categories_id'].'" AND images_id="'.(int)$_GET['images_id'].'" ;');
			$row = tep_db_fetch_array($sql);
			if(tep_not_null($row['images_name']) && !preg_match('/^http:\/\//',$row['images_name'])){
				$image_path = DIR_FS_DOCUMENT_ROOT.'images/destination_guide/'.$row['images_name'];
				@unlink($image_path);
			}
			
			tep_db_query('DELETE FROM `destination_guide_categories_images` WHERE `dg_categories_id`="'.(int)$_GET['dg_categories_id'].'" AND images_id="'.(int)$_GET['images_id'].'" ;');
			tep_db_query('OPTIMIZE TABLE `destination_guide_categories_images` ');
			echo '[DELDONE]';
		}
		exit;
	break;
	
	case 'create_category_confirm':	//确认添加或编辑目录
	case 'edit_category_confirm':
		$dg_categories_id = (int)$_POST['dg_categories_id'];
		$parent_id = (int)($_POST['parent_id']);
		$dg_categories_state = (int)$_POST['dg_categories_state'];
		$dg_categories_name = tep_db_prepare_input($_POST['dg_categories_name']);
		$dg_categories_name_en = tep_db_prepare_input(str_replace(' ','_',strtolower($_POST['dg_categories_name_en'])));
		$sort_order = (int)$_POST['sort_order'];
		$map_image = tep_db_prepare_input($_POST['map_image']);
		$hot_dg_categories_ids = tep_db_prepare_input($_POST['hot_dg_categories_ids']);
		$recommend_products_ids = tep_db_prepare_input($_POST['recommend_products_ids']);
		
		$overview_info = tep_db_prepare_input($_POST['overview_info']);
		$lodging_info = tep_db_prepare_input($_POST['lodging_info']);
		$traffic_info = tep_db_prepare_input($_POST['traffic_info']);
		$shopping_info = tep_db_prepare_input($_POST['shopping_info']);
		$food_info = tep_db_prepare_input($_POST['food_info']);
		$local_features = tep_db_prepare_input($_POST['local_features']);
		$weather = tep_db_prepare_input($_POST['weather']);
		
		if(!strlen(strip_tags($overview_info))){ $overview_info="";}
		if(!strlen(strip_tags($lodging_info))){ $lodging_info="";}
		if(!strlen(strip_tags($traffic_info))){ $traffic_info="";}
		if(!strlen(strip_tags($shopping_info))){ $shopping_info="";}
		if(!strlen(strip_tags($food_info))){ $food_info="";}
		if(!strlen(strip_tags($local_features))){ $local_features="";}
		
		$meta_title = tep_db_prepare_input($_POST['meta_title']);
		$meta_keywords = tep_db_prepare_input($_POST['meta_keywords']);
		$meta_description = tep_db_prepare_input($_POST['meta_description']);
		
		$overview_meta_title = tep_db_prepare_input($_POST['overview_meta_title']);
		$overview_meta_keywords = tep_db_prepare_input($_POST['overview_meta_keywords']);
		$overview_meta_description = tep_db_prepare_input($_POST['overview_meta_description']);

		$lodging_meta_title = tep_db_prepare_input($_POST['lodging_meta_title']);
		$lodging_meta_keywords = tep_db_prepare_input($_POST['lodging_meta_keywords']);
		$lodging_meta_description = tep_db_prepare_input($_POST['lodging_meta_description']);

		$traffic_meta_title = tep_db_prepare_input($_POST['traffic_meta_title']);
		$traffic_meta_keywords = tep_db_prepare_input($_POST['traffic_meta_keywords']);
		$traffic_meta_description = tep_db_prepare_input($_POST['traffic_meta_description']);

		$shopping_meta_title = tep_db_prepare_input($_POST['shopping_meta_title']);
		$shopping_meta_keywords = tep_db_prepare_input($_POST['shopping_meta_keywords']);
		$shopping_meta_description = tep_db_prepare_input($_POST['shopping_meta_description']);

		$food_meta_title = tep_db_prepare_input($_POST['food_meta_title']);
		$food_meta_keywords = tep_db_prepare_input($_POST['food_meta_keywords']);
		$food_meta_description = tep_db_prepare_input($_POST['food_meta_description']);
		
		$features_meta_title = tep_db_prepare_input($_POST['features_meta_title']);
		$features_meta_keywords = tep_db_prepare_input($_POST['features_meta_keywords']);
		$features_meta_description = tep_db_prepare_input($_POST['features_meta_description']);
		
		if(!tep_not_null($dg_categories_name)){
			$error = true;
			$messageStack->add('名称：不能为空！', 'error');
		}
		if(!tep_not_null($dg_categories_name_en)){
			$error = true;
			$messageStack->add('英文名称：不能为空！', 'error');
		}elseif(!preg_match('/^[0-9a-zA-Z_]+$/',$dg_categories_name_en)){
			$error = true;
			$messageStack->add('英文名称：只能输入英文或数字！', 'error');

		}else{
			$where_exc = '';
			if((int)$dg_categories_id){
				$where_exc = ' AND dg_categories_id!="'.(int)$dg_categories_id.'" ';
			}
			$check_sql = tep_db_query('SELECT dg_categories_id FROM `destination_guide_categories` WHERE dg_categories_name_en="'.$dg_categories_name_en.'" '.$where_exc.' limit 1 ');
			$check_row = tep_db_fetch_array($check_sql);
			if((int)$check_row['dg_categories_id']){
				$error = true;
				$messageStack->add('英文名称：输入的值与数据库有重复，请另起它名！', 'error');
	
			}
		}
		
		if($error == false){
			$sql_date_array = array('dg_categories_name' => $dg_categories_name,
									'dg_categories_name_en' => $dg_categories_name_en,
									'dg_categories_state' => $dg_categories_state,
									'parent_id' => $parent_id,
									'map_image' => $map_image,
									'hot_dg_categories_ids' => $hot_dg_categories_ids,
									'recommend_products_ids' => $recommend_products_ids,
									'sort_order' => $sort_order
									);
			
			$sql_date_array1 =array('overview_info' => $overview_info,
									'lodging_info' => $lodging_info,
									'traffic_info' => $traffic_info,
									'shopping_info' => $shopping_info,
									'food_info' => $food_info,
									'local_features' => $local_features,
									'meta_title' => $meta_title,
									'meta_keywords' => $meta_keywords,
									'meta_description' => $meta_description,
									'overview_meta_title' => $overview_meta_title,
									'overview_meta_keywords' => $overview_meta_keywords,
									'overview_meta_description' => $overview_meta_description,
									'lodging_meta_title' => $lodging_meta_title,
									'lodging_meta_keywords' => $lodging_meta_keywords,
									'lodging_meta_description' => $lodging_meta_description,
									'traffic_meta_title' => $traffic_meta_title,
									'traffic_meta_keywords' => $traffic_meta_keywords,
									'traffic_meta_description' => $traffic_meta_description,
									'shopping_meta_title' => $shopping_meta_title,
									'shopping_meta_keywords' => $shopping_meta_keywords,
									'shopping_meta_description' => $shopping_meta_description,
									'food_meta_title' => $food_meta_title,
									'food_meta_keywords' => $food_meta_keywords,
									'food_meta_description' => $food_meta_description,
									'features_meta_title' => $features_meta_title,
									'features_meta_keywords' => $features_meta_keywords,
									'features_meta_description' => $features_meta_description,
									'weather' => $weather
									);
			
			if($action=='edit_category_confirm'){
				tep_db_perform('destination_guide_categories', $sql_date_array, 'update', 'dg_categories_id="'.(int)$dg_categories_id.'" ');
				tep_db_perform('destination_guide_categories_description', $sql_date_array1, 'update', 'dg_categories_id="'.(int)$dg_categories_id.'" ');
				
				$messageStack->add_session('数据更新成功！', 'success');

				$tmp_link = tep_href_link('destination_guide.php', 'DgPath='.$ParentDgPath.'&DgID=' . (int)$dg_categories_id.'&'.tep_get_all_get_params(array('x','y','action','dg_categories_state','dg_categories_id','DgPath','DgID')) );
				
			}elseif($action=='create_category_confirm'){
				tep_db_perform('destination_guide_categories', $sql_date_array);
				$dg_categories_id = tep_db_insert_id();
				$sql_date_array1['dg_categories_id'] = $dg_categories_id;
				tep_db_perform('destination_guide_categories_description', $sql_date_array1);
				$messageStack->add_session('数据添加成功！', 'success');
				$tmp_link = tep_href_link('destination_guide.php', 'DgPath='.$DgPath.'&'.tep_get_all_get_params(array('x','y','action','dg_categories_state','dg_categories_id','DgPath','DgID')) );
				
			}
			
			//处理图片
			if(is_array($_POST['images_name'])){
				tep_db_query('DELETE FROM `destination_guide_categories_images` WHERE `dg_categories_id`="'.(int)$dg_categories_id.'" ;');
				foreach($_POST['images_name'] as $key => $val){
					if(tep_not_null($_FILES['file_images_name']['tmp_name'][$key])){
						$newfile_name = 'destination_guide_'.(int)$dg_categories_id.'_'.$_FILES['file_images_name']['name'][$key];
						if(@move_uploaded_file($_FILES['file_images_name']['tmp_name'][$key], DIR_FS_DOCUMENT_ROOT.'images/destination_guide/'. $newfile_name)){
							@chmod(DIR_FS_DOCUMENT_ROOT.'images/destination_guide/'. $newfile_name, 0644);
							tep_db_query('INSERT INTO `destination_guide_categories_images` (`images_name`,`dg_categories_id`,`images_sort_order`) VALUES ("'.$newfile_name.'", "'.(int)$dg_categories_id.'", "'.(int)$_POST['images_sort_order'][$key].'");');
						}
					}else{
						tep_db_query('INSERT INTO `destination_guide_categories_images` (`images_name`,`dg_categories_id`,`images_sort_order`) VALUES ("'.$val.'", "'.(int)$dg_categories_id.'", "'.(int)$_POST['images_sort_order'][$key].'");');
					}
				}
			}
			//处理地图图片
			
			if(tep_not_null($_FILES['file_map']['tmp_name'])){
				$map_name = 'destination_guide_'.(int)$dg_categories_id.'_map_'.$_FILES['file_map']['name'];
				if(@move_uploaded_file($_FILES['file_map']['tmp_name'], DIR_FS_DOCUMENT_ROOT.'images/destination_guide/'. $map_name)){
					@chmod(DIR_FS_DOCUMENT_ROOT.'images/destination_guide/'. $map_name, 0644);
					tep_db_query('UPDATE `destination_guide_categories` SET `map_image` = "'.$map_name.'" WHERE `dg_categories_id` = "'.(int)$dg_categories_id.'" LIMIT 1 ;');
				}
			}

			if(tep_not_null($tmp_link)){
				tep_redirect($tmp_link);
			}
			exit;
		}
		
		
	break;
	
	case 'edit_category':
		if(!isset($_POST['dg_categories_id'])){
			$categories_sql = tep_db_query('SELECT * FROM `destination_guide_categories` c, `destination_guide_categories_description` cd WHERE c.dg_categories_id="'.(int)$DgID.'" AND cd.dg_categories_id=c.dg_categories_id ');
			$categories_row = tep_db_fetch_array($categories_sql);
			if((int)$categories_row['dg_categories_id']){
				$categories = new objectInfo($categories_row);
				foreach((array)$categories as $key => $val){
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
function popupWindowAvailableTour(url) {
  window.open(url,'popupWindowAvailableTour','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=800,height=600,screenX=50,screenY=50,top=50,left=50');
}
</script>
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
//删除目录
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
            <td class="pageHeading">
			<a href="<?php echo tep_href_link('destination_guide.php')?>" style="font-size:18px">目的地指南</a> 
			<?php
				$count_DgPath = count($DgPath_array);
				if($count_DgPath){
					for($i=0; $i<$count_DgPath; $i++){
						$cate_sql = tep_db_query('SELECT dg_categories_id,dg_categories_name FROM `destination_guide_categories` WHERE dg_categories_id="'.$DgPath_array[$i].'" ');
						$cate_row = tep_db_fetch_array($cate_sql);
						echo ' &gt;&gt; <a style="font-size:18px" href="'.tep_href_link('destination_guide.php','DgPath='.tep_get_gd_category_patch($cate_row['dg_categories_id'])).'">'.db_to_html($cate_row['dg_categories_name']).'</a>';
					}
				}
			?>
			</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      
      <tr>
        <td>
		<fieldset>
		  <legend align="left">
		  <?php
		  if($action=='create_category'){
		  	echo db_to_html('新建目录');
		  }
		  if($action=='edit_category'){
		  	echo db_to_html('编辑目录');
		  }
		  ?>
		  
		  </legend>

		<?php echo tep_draw_form('form_dg_category', 'destination_guide_category.php', tep_get_all_get_params(array('y','x', 'action')), 'post', 'id="form_dg_category" enctype="multipart/form-data" '); ?>
		<table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top">
			<table border="0" cellspacing="1" cellpadding="2">
              
              <tr class="dataTableRow">
                <td align="right" class="dataTableContent">名称：</td>
                <td align="left" class="dataTableContent"><?php echo tep_draw_input_field('dg_categories_name','','size="100"');?>*
                  <input name="dg_categories_id" type="hidden" id="dg_categories_id" value="<?php echo (int)$dg_categories_id?>">
                  <?php
				  if($action=='edit_category' || $action=='edit_category_confirm' ){
				  	$action_value = 'edit_category_confirm';
					$parent_id_value = $parent_id;
				  }else{
				  	$action_value = 'create_category_confirm';
					$parent_id_value = $current_dg_category_id;
				  }
				  ?>
				  <input name="action" type="hidden" id="action" value="<?=$action_value?>"></td>
                </tr>
              <tr class="dataTableRow">
                <td align="right" class="dataTableContent">英文名称：</td>
                <td align="left" class="dataTableContent">
				<?php echo tep_draw_input_field('dg_categories_name_en','',' size="20" style="ime-mode: disabled;" autocomplete="off" ');?>
				* 只能输入英文或数字，用于定义url地址所需，一旦定义请勿随意修改。				</td>
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
					if($val['id'] != $dg_categories_id || !(int)$dg_categories_id){
						$categories_menu_array[] = array('id'=> $val['id'], 'text'=> $val['text']);
					}
				}
				//print_r($categories_array);
				echo tep_draw_pull_down_menu('parent_id', $categories_menu_array, $parent_id_value);
				?>				</td>
              </tr>
              <tr class="dataTableRow">
                <td align="right" class="dataTableContent">meta_title：</td>
                <td align="left" class="dataTableContent"><?php echo tep_draw_input_field('meta_title','','size="100"');?>如是顶级目录请填写此项</td>
                </tr>
              <tr class="dataTableRow">
                <td align="right" class="dataTableContent">meta_keywords：</td>
                <td align="left" class="dataTableContent"><?php echo tep_draw_input_field('meta_keywords','','size="100"');?>如是顶级目录请填写此项</td>
                </tr>
              <tr class="dataTableRow">
                <td align="right" nowrap class="dataTableContent">meta_description：</td>
                <td align="left" class="dataTableContent"><?php echo tep_draw_input_field('meta_description','','size="100"');?>如是顶级目录请填写此项</td>
                </tr>
              <tr class="dataTableRow">
                <td align="right" class="dataTableContent">概况：</td>
                <td align="left" class="dataTableContent">
				<?php
				$text_display = 'none';
				if($_GET['style']=='1'){
					$text_display = '';
				}
				?>
				<?php
				echo tep_draw_textarea_field('overview_info', 'virtual', '110', '20');
				/*if($text_display=='none'){
				?>
			<iframe id="message___Frame_overview_info" src="FCKeditor/editor/fckeditor.html?InstanceName=overview_info&amp;Toolbar=Default" frameborder="no" height="300" scrolling="no" width="100%"></iframe>
				<?php
				}*/
				?>			</td>
                </tr>
              
              <tr class="dataTableRow">
                <td align="right" class="dataTableContent">概况meta_title：</td>
                <td align="left" class="dataTableContent"><?php echo tep_draw_input_field('overview_meta_title','','size="100"');?></td>
                </tr>
              <tr class="dataTableRow">
                <td align="right" class="dataTableContent">概况meta_keywords：</td>
                <td align="left" class="dataTableContent"><?php echo tep_draw_input_field('overview_meta_keywords','','size="100"');?></td>
                </tr>
              <tr class="dataTableRow">
                <td align="right" nowrap class="dataTableContent">概况meta_description：</td>
                <td align="left" class="dataTableContent"><?php echo tep_draw_input_field('overview_meta_description','','size="100"');?></td>
                </tr>
              <tr class="dataTableRow">
                <td align="right" class="dataTableContent">概况图片组（建议347px×203px）：</td>
                <td align="left" class="dataTableContent">
				<?php
				$images_sql = tep_db_query('SELECT * FROM `destination_guide_categories_images` WHERE dg_categories_id ="'.(int)$dg_categories_id.'" ORDER BY images_sort_order ASC ');
				$images_rows = tep_db_fetch_array($images_sql);
				?>
				
<script type="text/javascript">
//删除目录图片
function ReMoveImage(d_id,images_id){
	var del_div = document.getElementById(d_id);
	if(images_id=='0'){
		if(del_div!=null){
			var d = document.getElementById('images_array');
			var olddiv = del_div;
			d.removeChild(olddiv);
		}
	}else{
		if(window.confirm("您真的要删除这张图片吗？\t")==true){
			var url = "<?php echo preg_replace($p,$r,tep_href_link('destination_guide_category.php','action=delete_image_confirm&dg_categories_id='.(int)$dg_categories_id))?>&images_id="+images_id;
			ajax.open("GET", url, true);
			ajax.send(null); 
			
			ajax.onreadystatechange = function() { 
				if (ajax.readyState == 4 && ajax.status == 200 ) { 
					if(del_div!=null && ajax.responseText.indexOf('[DELDONE]')>-1){
						var d = document.getElementById('images_array');
						var olddiv = del_div;
						d.removeChild(olddiv);
					}
				}		
			}
		}
	}
}

function AddImageInput(sd_id){
	var ni = document.getElementById(sd_id);
	var child_div = ni.getElementsByTagName("div");
	var id_num = child_div.length;
	var new_id = check_set_obj_id('images_id_' + id_num);
	var newdiv = document.createElement('div');
	newdiv.setAttribute("id",new_id);
	newdiv.style.paddingTop='20px';
	newdiv.innerHTML = '输入图片URL：<?php echo tep_draw_input_field('images_name[]','','size="68"');?> 排序序号：<input name="images_sort_order[]" value="'+(id_num+1)+'" size="4" type="text"><input type="button" onClick="ReMoveImage(&quot;'+ new_id +'&quot;,0)" value="删除"><br>或<br>上传图片：<input name="file_images_name[]" type="file" size="89">';
	ni.appendChild(newdiv);
}

//自动设置唯一的id号
function check_set_obj_id(id_name){
	var set_id = id_name;
	var obj = document.getElementById(id_name);
	if(obj!=null){
		var new_set_id = set_id +'_a';
		return check_set_obj_id(new_set_id);
	}else{
		return set_id;
	}
	
	/*DWREngine.setAsync(false);
	dwrTest.hello(function(data){result=data})
	DWREngine.setAsync(true);
	return result; 
	*/
}

</script>
				
				<div id="images_array">
					<?php
					$id_num = 0;
					do{
					?>
					<div id="images_id_<?=$id_num?>" style="padding-top:20px;">
					输入图片URL：<?php echo tep_draw_input_field('images_name[]',$images_rows['images_name'],'size="68"');?> 排序序号：<?php echo tep_draw_input_field('images_sort_order[]',$images_rows['images_sort_order'],'size="4"');?><input type="button" onClick="ReMoveImage('images_id_<?=$id_num?>',<?php echo (int)$images_rows['images_id']?>)" value="删除"><br>
					或<br>
					上传图片：<input name="file_images_name[]" type="file" size="89">
					</div>
					<?php
						$id_num++;
					}while($images_rows = tep_db_fetch_array($images_sql));
					?>
				</div>
				<div><input type="button" onClick="AddImageInput('images_array')" value="增加"></div>				</td>
              </tr>
              <tr class="dataTableRow">
                <td align="right" class="dataTableContent">住宿：</td>
                <td align="left" class="dataTableContent">
				<?php
				echo tep_draw_textarea_field('lodging_info', 'virtual', '110', '20', '', 'style="display:'.$text_display.'"');
				if($text_display=='none'){
				?>
			<iframe id="message___Frame_lodging_info" src="FCKeditor/editor/fckeditor.html?InstanceName=lodging_info&amp;Toolbar=Default" frameborder="no" height="300" scrolling="no" width="100%"></iframe>
				<?php
				}
				?>			</td>
                </tr>
              <tr class="dataTableRow">
                <td align="right" class="dataTableContent">住宿meta_title：</td>
                <td align="left" class="dataTableContent"><?php echo tep_draw_input_field('lodging_meta_title','','size="100"');?></td>
                </tr>
              <tr class="dataTableRow">
                <td align="right" class="dataTableContent">住宿meta_keywords：</td>
                <td align="left" class="dataTableContent"><?php echo tep_draw_input_field('lodging_meta_keywords','','size="100"');?></td>
                </tr>
              <tr class="dataTableRow">
                <td align="right" nowrap class="dataTableContent">住宿meta_description：</td>
                <td align="left" class="dataTableContent"><?php echo tep_draw_input_field('lodging_meta_description','','size="100"');?></td>
                </tr>
              <tr class="dataTableRow">
                <td align="right" class="dataTableContent">交通：</td>
                <td align="left" class="dataTableContent">
				<?php
				echo tep_draw_textarea_field('traffic_info', 'virtual', '110', '20', '', 'style="display:'.$text_display.'"');
				if($text_display=='none'){
				?>
			<iframe id="message___Frame_traffic_info" src="FCKeditor/editor/fckeditor.html?InstanceName=traffic_info&amp;Toolbar=Default" frameborder="no" height="300" scrolling="no" width="100%"></iframe>
				<?php
				}
				?>			</td>
                </tr>
              <tr class="dataTableRow">
                <td align="right" class="dataTableContent">交通meta_title：</td>
                <td align="left" class="dataTableContent"><?php echo tep_draw_input_field('traffic_meta_title','','size="100"');?></td>
                </tr>
              <tr class="dataTableRow">
                <td align="right" class="dataTableContent">交通meta_keywords：</td>
                <td align="left" class="dataTableContent"><?php echo tep_draw_input_field('traffic_meta_keywords','','size="100"');?></td>
                </tr>
              <tr class="dataTableRow">
                <td align="right" nowrap class="dataTableContent">交通meta_description：</td>
                <td align="left" class="dataTableContent"><?php echo tep_draw_input_field('traffic_meta_description','','size="100"');?></td>
                </tr>
              <tr class="dataTableRow">
                <td align="right" class="dataTableContent">购物：</td>
                <td align="left" class="dataTableContent">
				<?php
				echo tep_draw_textarea_field('shopping_info', 'virtual', '110', '20', '', 'style="display:'.$text_display.'"');
				if($text_display=='none'){
				?>
			<iframe id="message___Frame_shopping_info" src="FCKeditor/editor/fckeditor.html?InstanceName=shopping_info&amp;Toolbar=Default" frameborder="no" height="300" scrolling="no" width="100%"></iframe>
				<?php
				}
				?>			</td>
                </tr>
              <tr class="dataTableRow">
                <td align="right" class="dataTableContent">购物meta_title：</td>
                <td align="left" class="dataTableContent"><?php echo tep_draw_input_field('shopping_meta_title','','size="100"');?></td>
                </tr>
              <tr class="dataTableRow">
                <td align="right" class="dataTableContent">购物meta_keywords：</td>
                <td align="left" class="dataTableContent"><?php echo tep_draw_input_field('shopping_meta_keywords','','size="100"');?></td>
                </tr>
              <tr class="dataTableRow">
                <td align="right" nowrap class="dataTableContent">购物meta_description：</td>
                <td align="left" class="dataTableContent"><?php echo tep_draw_input_field('shopping_meta_description','','size="100"');?></td>
                </tr>
              <tr class="dataTableRow">
                <td align="right" class="dataTableContent">美食：</td>
                <td align="left" class="dataTableContent">
				<?php
				echo tep_draw_textarea_field('food_info', 'virtual', '110', '20', '', 'style="display:'.$text_display.'"');
				if($text_display=='none'){
				?>
			<iframe id="message___Frame_food_info" src="FCKeditor/editor/fckeditor.html?InstanceName=food_info&amp;Toolbar=Default" frameborder="no" height="300" scrolling="no" width="100%"></iframe>
				<?php
				}
				?>			</td>
                </tr>
              <tr class="dataTableRow">
                <td align="right" class="dataTableContent">美食meta_title：</td>
                <td align="left" class="dataTableContent"><?php echo tep_draw_input_field('food_meta_title','','size="100"');?></td>
                </tr>
              <tr class="dataTableRow">
                <td align="right" class="dataTableContent">美食meta_keywords：</td>
                <td align="left" class="dataTableContent"><?php echo tep_draw_input_field('food_meta_keywords','','size="100"');?></td>
                </tr>
              <tr class="dataTableRow">
                <td align="right" nowrap class="dataTableContent">美食meta_description：</td>
                <td align="left" class="dataTableContent"><?php echo tep_draw_input_field('food_meta_description','','size="100"');?></td>
                </tr>
              <tr class="dataTableRow">
                <td align="right" class="dataTableContent">特色：</td>
                <td align="left" class="dataTableContent">
				<?php
				echo tep_draw_textarea_field('local_features', 'virtual', '110', '20', '', 'style="display:'.$text_display.'"');
				if($text_display=='none'){
				?>
			<iframe id="message___Frame_local_features" src="FCKeditor/editor/fckeditor.html?InstanceName=local_features&amp;Toolbar=Default" frameborder="no" height="300" scrolling="no" width="100%"></iframe>
				<?php
				}
				?>			</td>
                </tr>
              <tr class="dataTableRow">
                <td align="right" class="dataTableContent">特色meta_title：</td>
                <td align="left" class="dataTableContent"><?php echo tep_draw_input_field('features_meta_title','','size="100"');?></td>
                </tr>
              <tr class="dataTableRow">
                <td align="right" class="dataTableContent">特色meta_keywords：</td>
                <td align="left" class="dataTableContent"><?php echo tep_draw_input_field('features_meta_keywords','','size="100"');?></td>
                </tr>
              <tr class="dataTableRow">
                <td align="right" nowrap class="dataTableContent">特色meta_description：</td>
                <td align="left" class="dataTableContent"><?php echo tep_draw_input_field('features_meta_description','','size="100"');?></td>
                </tr>
              <tr class="dataTableRow">
                <td align="right" class="dataTableContent">地图：</td>
                <td align="left" class="dataTableContent">
				输入地图代码：<?php echo tep_draw_textarea_field('map_image', 'virtual', '83', '3', '', ' id="map_image" ');?><br>
				或<br>
				上传图片：<input name="file_map" type="file" size="81" >
				<br />
				提示：<b>地图代码</b>是指&lt;iframe或http://开头的代码，如果是上传图片建议图片宽度不要超过712*420像素，顶级目录746*425。</td>
                </tr>
              
              <tr class="dataTableRow">
                <td align="right" class="dataTableContent">天气预报：</td>
                <td align="left" class="dataTableContent">
				<?php echo tep_draw_textarea_field('weather', 'virtual', '97', '3', '', ' id="weather" ');?>
 				<br />
				参考网站:<a href="http://www.t7online.com/" target="_blank">http://www.t7online.com/</a>
				<br>
				<a href="javascript:void(0)" onClick="get_weather_code()">预览</a>
				<br>
				<div id="weather_iframe" style="width:206px; border: 1px dashed #666666;">				</div>
				<script type="text/javascript">
				function get_weather_code(){
					var weather = document.getElementById('weather');
					var weather_iframe = document.getElementById('weather_iframe');
					weather_iframe.innerHTML = weather.value;
				}
				</script>
				
				<br>
				提示：在iframe代码中添加 width="206"，如果您在预览时看不到右边的边框代表已经超宽。				</td>
              </tr>
              <tr class="dataTableRow">
                <td align="right" class="dataTableContent">热门景点：</td>
                <td align="left" class="dataTableContent"><?php echo tep_draw_input_field('hot_dg_categories_ids','','size="100"');?>多个ID号请用英文","号隔开</td>
              </tr>
              <tr class="dataTableRow">
                <td align="right" class="dataTableContent">推荐行程：</td>
                <td align="left" class="dataTableContent"><?php echo tep_draw_input_field('recommend_products_ids','','size="100"');?>多个ID号请用英文","号隔开。<a href="javascript:popupWindowAvailableTour('<?php echo tep_href_link('affiliate_validcats_admin.php')?>')"><b>查看产品编号</b></a></td>
              </tr>
              <tr class="dataTableRow">
                <td align="right" class="dataTableContent">状态：</td>
                <td align="left" class="dataTableContent">
				<?php
				if($dg_categories_state == '1'){
					$dg_categories_checked = 'checked';
					$dg_categories_checked_0 = '';
				}else{
					$dg_categories_checked = '';
					$dg_categories_checked_0 = 'checked';
				}
				?>
				<input name="dg_categories_state" type="radio" value="1" <?=$dg_categories_checked?>>
				显示
				<input type="radio" name="dg_categories_state" value="0" <?=$dg_categories_checked_0?>>关闭				</td>
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
					if($action=='edit_category' || $action=='edit_category_confirm' ){
						$BackPath = $ParentDgPath;
					}else{
						$BackPath = $DgPath;
					}
					echo '<a href="' . tep_href_link('destination_guide.php', 'DgPath='.$BackPath.'&'.tep_get_all_get_params(array('DgPath','DgID','y','x', 'action')) ). '">' .tep_image_button('button_back.gif', IMAGE_BACK) . '</a>';
				?>				</td>
                <td align="left" valign="middle" class="smallText">
				<input name="submit" type="submit" id="submit" value="<?= db_to_html(' 确定 ')?>">
				<input name="reset" type="reset" id="reset" value="<?= db_to_html(' 重置 ')?>">
				<?php
				if($action=='edit_category' || $action=='edit_category_confirm' ){
					if(count($DgPath_array)>=3){
						echo '<a href="' .(HTTP_SERVER .'/destination_guide_details.php') . '?dg_categories_id=' .$dg_categories_id. '" target="_blank">'.tep_image_button('button_preview.gif', IMAGE_PREVIEW).'</a>';
					}
					if(count($DgPath_array)==1){
						echo '<a href="' .(HTTP_SERVER .'/destination_guide.php') . '?dg_categories_id=' .$dg_categories_id. '" target="_blank">'.tep_image_button('button_preview.gif', IMAGE_PREVIEW).'</a>';
					}
				}
				?>
				</td>
              </tr>
            </table>
			</td>
          </tr>
        </table>
		<?='</form>'?>
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
