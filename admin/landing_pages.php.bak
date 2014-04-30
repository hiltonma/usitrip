<?php
/*
  $Id: categories.php,v 1.2 2004/03/29 00:18:17 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  // 备注添加删除
  if($_GET['ajax']=="true"){
  	include DIR_FS_CLASSES . 'Remark.class.php';
  	$remark = new Remark('landing_pages');
  	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
  }
  
  require('includes/functions/categories_description.php');
  $languages = tep_get_languages();

  define('DIR_FS_LANDING_IMAGES', DIR_FS_CATALOG_IMAGES . 'landing/');

if (tep_not_null($action)) {
    switch ($action) {
	
	case 'delete_product_confirm':
  
	if (isset($HTTP_POST_VARS['landing_page_id'])) {
	  $landing_page_id = tep_db_prepare_input($HTTP_POST_VARS['landing_page_id']);
	  $delete_query = tep_db_query("delete from ".TABLE_LANDING_PAGES." where landing_page_id=".$landing_page_id); 
	}
    tep_redirect(tep_href_link(FILENAME_LANDING_PAGES));
	break;
	
	case 'new_page_insert':
	case 'edit_page';
		$landing_page_id = tep_db_prepare_input($_POST['landing_page_id']);
		$landing_page_heading = tep_db_prepare_input($HTTP_POST_VARS['landing_page_heading']);
		$landing_page_url = tep_db_prepare_input($HTTP_POST_VARS['landing_page_url']);
		
		$page_head_keywords_tag = tep_db_prepare_input($HTTP_POST_VARS['page_head_keywords_tag']);
		$page_head_desc_tag = tep_db_prepare_input($HTTP_POST_VARS['page_head_desc_tag']);
		$page_head_title_tag = tep_db_prepare_input($HTTP_POST_VARS['page_head_title_tag']);
		
		
		
		$sql_data_array = array('landing_page_url' => $landing_page_url,
							    'landing_page_heading' => $landing_page_heading,
								'landing_page_desc' => tep_db_prepare_input($HTTP_POST_VARS['landing_page_desc']),
								'landing_page_poi' => tep_db_prepare_input($HTTP_POST_VARS['landing_page_poi']),
								'landing_original_page_name' => tep_db_prepare_input($HTTP_POST_VARS['landing_original_page_name']),
							    'page_meta_title' => $page_head_title_tag,
							    'page_meta_description' => $page_head_desc_tag,
								'page_meta_keyword' => $page_head_keywords_tag,
								'featured_products_id' => $HTTP_POST_VARS['featured_products_id'],
								'seasonal_products_id' => $HTTP_POST_VARS['seasonal_products_id']						    
								);
	  
        //$sql_data_array = array('sort_order' => $sort_order);
        if ($action == 'new_page_insert') {
          tep_db_perform(TABLE_LANDING_PAGES, $sql_data_array);
		  $landing_page_id = tep_db_insert_id();
        } elseif ($action == 'edit_page') {
			tep_db_perform(TABLE_LANDING_PAGES, $sql_data_array, 'update', "landing_page_id = '" . (int)$landing_page_id . "'");
        }
		
				  //amit added for image and  description for introduction section START		  
				  
		  foreach($_POST['cat_intro_alt_introfile'] as $key=>$val){	
				if( $val != ''){					
					if(isset($_POST['db_categories_introduction_id'][$key]) && $_POST['db_categories_introduction_id'][$key] !=''){					
						if($_POST['remove_id_form_db'][$key] == 'on'){							
							
							//simple delete form db
							if($_POST['previouse_image_introfile'][$key] != ''){
										//unlink previouse image
								@unlink(DIR_FS_LANDING_IMAGES . $_POST['previouse_image_introfile'][$key]);
							}
							tep_db_query("delete from " . TABLE_LANDING_PAGE_IMAGES . " where image_id = '" . (int)$_POST['db_categories_introduction_id'][$key] . "'");
						}else{
										//wirte code for upload new image	
									 if(basename($_FILES['image_introfile']['name'][$key]) != '' ){								
											$tmp_categories_introduction_image_name = '';
											$uploadfile = DIR_FS_LANDING_IMAGES.$landing_page_id.'_'.basename($_FILES['image_introfile']['name'][$key]);
											if (move_uploaded_file($_FILES['image_introfile']['tmp_name'][$key],$uploadfile)) {
												$tmp_categories_introduction_image_name = $landing_page_id.'_'.basename($_FILES['image_introfile']['name'][$key]);
											} 
										
										$categories_introduction_image_name = $tmp_categories_introduction_image_name;
										
										//delete previouse image
										if($_POST['previouse_image_introfile'][$key] != '' && basename($_FILES['image_introfile']['name'][$key]) != $_POST['previouse_image_introfile'][$key]){
										   @unlink(DIR_FS_LANDING_IMAGES . $_POST['previouse_image_introfile'][$key]);
										}
									}else{
											//assine image name form privouse value
											
											$categories_introduction_image_name = $_POST['previouse_image_introfile'][$key];
									}
								
								$sql_data_array_intorupdate = array(
											 'landing_page_id' => $landing_page_id,
											 'page_image' => $categories_introduction_image_name,
											 'image_alt' => tep_db_prepare_input($_POST['cat_intro_alt_introfile'][$key]),
											 'image_sort_order' => tep_db_prepare_input($_POST['cat_intro_sort_order'][$key])
											 );
				
							 tep_db_perform(TABLE_LANDING_PAGE_IMAGES, $sql_data_array_intorupdate,'update',"image_id = '" . (int)$_POST['db_categories_introduction_id'][$key] . "'");
								
								
						}
						
					}else{
							//add new seciton

										//wirte code for upload new image												
									if(basename($_FILES['image_introfile']['name'][$key]) != '' ){								
											$tmp_categories_introduction_image_name = '';
											$uploadfile = DIR_FS_LANDING_IMAGES.$landing_page_id.'_'.basename($_FILES['image_introfile']['name'][$key]);
											if (move_uploaded_file($_FILES['image_introfile']['tmp_name'][$key],$uploadfile)) {
												$tmp_categories_introduction_image_name = $landing_page_id.'_'.basename($_FILES['image_introfile']['name'][$key]);
											} 
										
										$categories_introduction_image_name = $tmp_categories_introduction_image_name;
										
										//delete previouse image
										if($_POST['previouse_image_introfile'][$key] != '' && basename($_FILES['image_introfile']['name'][$key]) != $_POST['previouse_image_introfile'][$key]){
										   @unlink(DIR_FS_LANDING_IMAGES . $_POST['previouse_image_introfile'][$key]);
										}
									}else{
									$categories_introduction_image_name = '';
									}
				
							$sql_data_array_intoradd = array(
											 'landing_page_id' => $landing_page_id,
											 'page_image' => $categories_introduction_image_name,
											 'image_alt' => tep_db_prepare_input($_POST['cat_intro_alt_introfile'][$key]),
											 'image_sort_order' => tep_db_prepare_input($_POST['cat_intro_sort_order'][$key])
											 );
				
							tep_db_perform(TABLE_LANDING_PAGE_IMAGES, $sql_data_array_intoradd);
				
				}
			}	  
		  
  		  //amit added for image and  description for introduction section END		  

		
	}
	
			tep_redirect(tep_href_link(FILENAME_LANDING_PAGES));

}

}
  ?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />
<script type="text/javascript" src="includes/general.js"></script>
<script type="text/javascript" src="includes/javascript/general.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
<div id="spiffycalendar" class="text"></div>
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('landing_pages');
$list = $listrs->showRemark();
?>
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">

<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>

    </table></td> 
	<?php
	if(isset($_GET['action']) && ($_GET['action']=='new_product' || $_GET['action']=='edit_product')) {
		if($_GET['action']=='edit_product'){
			
			$action = 'edit_page';
			$edit_data_query = tep_db_query("select * from ".TABLE_LANDING_PAGES." where landing_page_id = '" . (int)$_GET['landing_page_id'] . "'");
			$row_edit_data = tep_db_fetch_array($edit_data_query);
		}
		else
		{
			$action = 'new_page_insert';
		}
	?>
		<td width="100%" valign="top">
			<table border="0" width="100%" cellspacing="0" cellpadding="2">
			  <tr>
				<td><table border="0" width="100%" cellspacing="0" cellpadding="0">
				  <tr>
					<td class="pageHeading"><?php echo 'New Landing Page'; ?></td>
					<td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
					
				  </tr>
				</table></td>
			  </tr>
			  
			  <tr>
				<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
			  </tr>
			  <tr>
				<td><?php echo tep_draw_form('new_page', FILENAME_LANDING_PAGES,'action='.$action.'', 'post', 'enctype="multipart/form-data"'); ?>
					<table border="0" cellspacing="0" cellpadding="2">
					   <tr>
					   <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
					   </tr>
					   <?php
   						 /*for ($i=0; $i<sizeof($languages); $i++) {
							?>
							  <tr>
								<td class="main"><?php if ($i == 0) echo TEXT_EDIT_PAGE_NAME; ?></td>
								<td class="main"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('landing_page_heading[' . $languages[$i]['id'] . ']', (($landing_page_heading[$languages[$i]['id']]) ? stripslashes($landing_page_heading[$languages[$i]['id']]) : $row_edit_data['landing_page_heading']),'style="width:250px;"'); ?></td>
							  </tr>
							<?php
						}*/
						
						echo tep_draw_hidden_field('landing_page_id', $_GET['landing_page_id']);
							?>
						
						<tr>
							<td class="main"><?php echo TEXT_EDIT_PAGE_NAME; ?></td>
							<td class="main"><?php echo tep_draw_input_field('landing_page_heading',$row_edit_data['landing_page_heading'],'style="width:250px;"'); ?></td>
						</tr>
						<tr>
						 <td class="main" valign="top"><?php echo TEXT_EDIT_PAGE_DESCRIPTION; ?></td>
						 <td class="main" valign="top">
						  <?php echo tep_draw_textarea_field('landing_page_desc', 'soft', '90', '18', $row_edit_data['landing_page_desc']); //stripslashes(tep_get_category_head_keywords_tag($cInfo->categories_id, $languages[$i]['id']))?>
						 </td>
					    </tr>
						<tr>
							<td class="main"><?php echo TEXT_EDIT_PAGE_URL; ?></td>
							<td class="main">landing/<?php echo tep_draw_input_field('landing_page_url',$row_edit_data['landing_page_url'],'style="width:250px;"'); ?>&nbsp; </td>
						</tr>
						<tr>
							<td class="main"><?php echo TEXT_EDIT_ORIGINAL_PAGE_NAME; ?></td>
							<td class="main">landing/<?php echo tep_draw_input_field('landing_original_page_name',$row_edit_data['landing_original_page_name'],'style="width:250px;"'); ?>&nbsp; (donot change)</td>
						</tr>
						
						
						<tr>
							<td class="main" valign="top"><?php echo 'Page Images: ';?></td>
							<td class="main" valign="top">
								<div>
								<table width="100%"  border="1" cellspacing="3" cellpadding="3">
								  <tr class="dataTableHeadingRow">
									<td width="33%" class="dataTableHeadingContent">Image</td>
									<!--<td width="30%" class="dataTableHeadingContent">Descirption</td>-->
									<td width="28%" class="dataTableHeadingContent">Alt Tag</td>
									<td width="3%" nowrap="nowrap" class="dataTableHeadingContent">Sort Order</td>
									<td width="3%" class="dataTableHeadingContent">Remove</td>
								  </tr>
								</table>
								</div>				
								<div id="myDiv">
								<?php
								$category_intro_query_sql  = "select *  from " . TABLE_LANDING_PAGE_IMAGES . " where landing_page_id = '" . $HTTP_GET_VARS['landing_page_id'] . "' order by image_sort_order";
								$category_intro_query = tep_db_query($category_intro_query_sql);
								
								$tt_into_cnt_row = tep_db_num_rows($category_intro_query);
								//$category_intro = tep_db_fetch_array($category_intro_query);
								if($tt_into_cnt_row > 0){
									?>
									<input type="hidden" value="<?php echo $tt_into_cnt_row; ?>" id="theValue" />
									<?php
									$row = 1;
									while($category_intro = tep_db_fetch_array($category_intro_query)){
									?>
									<div id="my<?php echo $row;?>Div">
									<table width="100%" border="0" cellspacing="3" cellpadding="3">
									  <tr>
										<td valign="top">
											<?php
											if($category_intro['page_image']!= '') {
											?>
											<img src="<?php echo HTTP_CATALOG_SERVER.'/images/landing/'.$category_intro['page_image'];?>" alt="<?php echo $category_intro['image_alt'];?>" width="150"><br/>
											<?php } ?>
											<input type='file' name='image_introfile[]'>
											<input type="hidden" name="previouse_image_introfile[]" value="<?php echo $category_intro['page_image'];?>">
										</td>
										<td valign='top'><textarea name='cat_intro_alt_introfile[]' rows="3" cols="28" ><?php echo stripslashes($category_intro['image_alt']);?>&nbsp;</textarea></td>
										<td valign='top'><input type="text" name='cat_intro_sort_order[]' size="10" value="<?php echo $category_intro['image_sort_order'];?>"></td>
										<td valign='top'><input type="hidden" name="db_categories_introduction_id[]" value="<?php echo $category_intro['image_id'];?>"> <input type="hidden" id="remove_id_form_db_<?php echo $category_intro['image_id'];?>" name="remove_id_form_db[]"  value="off"><input type="checkbox" name="delete_frm_db[]" onClick="document.getElementById('remove_id_form_db_<?php echo $category_intro['image_id'];?>').value='on'"></td>
									  </tr>
									</table>
									</div>		
									<?php		
									$row++;
									}
								}else{
								?>
								<input type="hidden" value="1" id="theValue" />
								<div id="my1Div">
								<table width="100%" border="0" cellspacing="3" cellpadding="3">
								  <tr>
									<td valign="top"><input type="file" name="image_introfile[]">		
									</td>
									<td valign='top'><textarea name='cat_intro_alt_introfile[]' rows="3" cols="28" >&nbsp;</textarea></td>
									<td valign='top'><input type="text" name='cat_intro_sort_order[]' size="10" value="1"></td>
									<td valign='top'><a href="javascript:;" onClick="removeEvent('my1Div')">Remove</a></td>
								  </tr>
								</table>
								</div>
								<?php
								}
								?>
								</div>
								<p><a href="javascript:;" onClick="addEvent();"><b><font color="#000099">Add More Images</font></b></a></p>
			
			</td>
          </tr>
		   <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		  <tr>
			 <td class="main" valign="top"><?php echo TEXT_EDIT_PAGE_POI; ?></td>
			 <td class="main" valign="top">
			  <?php echo tep_draw_textarea_field('landing_page_poi', 'soft', '70', '10', $row_edit_data['landing_page_poi']);?>
			 </td>
		</tr>
		<?php 
			  $all_products_statuses = array(array('id' => '', 'text' => 'All Tours'));
			 	$all_products_query = tep_db_query("select p.products_id, pd.products_name, p.products_price, p.products_model from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and p.products_status=1 and pd.products_name!='' and pd.language_id = '" . (int)$languages_id . "' order by products_name");
				while ($all_products = tep_db_fetch_array($all_products_query)) {
				
					  $all_products_statuses[] = array('id' => $all_products['products_id'],
                               					 'text' => $all_products['products_name'].' ['.$all_products['products_model'].']');
				
			 	}
				?>
				
		<tr>
		 <td class="main" valign="top"><?php echo TEXT_EDIT_FEATURED_PRODUCT; ?></td>
		 <td class="main" valign="top">
		  <?php echo tep_draw_pull_down_menu('featured_products_id', $all_products_statuses, $row_edit_data['featured_products_id'], ' style="width:500px;"');	?>
		 </td>
		</tr>
		<tr>
		 <td class="main" valign="top"><?php echo TEXT_EDIT_SEASONAL_PRODUCT; ?></td>
		 <td class="main" valign="top">
		  <?php echo tep_draw_pull_down_menu('seasonal_products_id', $all_products_statuses, $row_edit_data['seasonal_products_id'], ' style="width:500px;"');	?>
		 </td>
		</tr>
		 <tr>
             <td colspan="2" class="main"><hr></td>
         </tr>
		 
		 
         <tr>
 		 	<td class="main"></td><td><?php echo TEXT_PRODUCT_METTA_INFO; ?></td>
 		 </tr>
		 <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
			 <td class="main" valign="top"><?php echo TEXT_EDIT_CATEGORIES_TITLE_TAG; ?></td>
             <td class="main" valign="top">
			  <?php echo tep_draw_textarea_field('page_head_title_tag', 'soft', '70', '3', $row_edit_data['page_meta_title']); //stripslashes(tep_get_category_head_keywords_tag($cInfo->categories_id, $languages[$i]['id']))?>
			 </td>
		  </tr>
          <tr>
            <td colspan="2" class="main"><hr></td>
          </tr>

              <tr>
			    <td class="main" valign="top"><?php echo TEXT_EDIT_CATEGORIES_DESC_TAG; ?></td>
                <td class="main" valign="top">
				<?php echo tep_draw_textarea_field('page_head_desc_tag', 'soft', '70', '3', $row_edit_data['page_meta_description']); //stripslashes(tep_get_category_head_keywords_tag($cInfo->categories_id, $languages[$i]['id']))?>
			    </td>
			</tr>
          <tr>
            <td colspan="2" class="main"><hr></td> 
          </tr>
		  <tr>
		     <td class="main" valign="top"><?php echo TEXT_EDIT_CATEGORIES_KEYWORDS_TAG; ?></td>
             <td class="main" valign="top">
			   <?php echo tep_draw_textarea_field('page_head_keywords_tag', 'soft', '70', '3', $row_edit_data['page_meta_keyword']); //stripslashes(tep_get_category_head_keywords_tag($cInfo->categories_id, $languages[$i]['id']))?></td>
		  </tr>

          <tr>
            <td colspan="2" class="main"><hr></td> </td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
         

		  <tr>
        <td class="main" align="right" colspan="2"><?php echo tep_image_submit('button_update.gif', IMAGE_PREVIEW) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_LANDING_PAGES,'') . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
      </tr>
					 </table>
				<?php echo '</form>'; ?>
				</td>
			   </tr>
			  
			</table>
		</td>
	
	<?php	
	}
	else
	{
	?>
	<td width="100%" valign="top">
		
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PAGE_ID; ?></td>
				<td class="dataTableHeadingContent" nowrap="nowrap">
					<?php echo TABLE_HEADING_PAGE_HEADING; ?><br>
				</td>
				
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
   	
	$rows = 0;
    $products_count = 0;
	  
	  $products_query_raw = "select * from ".TABLE_LANDING_PAGES."";
	  $products_query = tep_db_query($products_query_raw);
	
	if(!isset($_GET['landing_page_id']) || $_GET['landing_page_id']=='')
	{
		$set_id_query = tep_db_query($products_query_raw." limit 0,1");
		if(tep_db_num_rows($set_id_query)>0)
		{
		$row_set_id = tep_db_fetch_array($set_id_query);
		$_GET['landing_page_id'] = $row_set_id['landing_page_id'];
		}
	}
    
	while ($products = tep_db_fetch_array($products_query)) {
      $products_count++;
      $rows++;
	  
	  if (isset($_GET['landing_page_id']) && ($products['landing_page_id'] == $_GET['landing_page_id']) ) {
        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_LANDING_PAGES, tep_get_all_get_params(array('landing_page_id', 'action')) . 'landing_page_id=' . $products['landing_page_id'] . '&action=edit_product') . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_LANDING_PAGES, tep_get_all_get_params(array('landing_page_id')) . 'landing_page_id=' . $products['landing_page_id']) . '\'">' . "\n";
		//tep_get_all_get_params(array('oID')) . 'oID=' . $orders['orders_id'])
      }
	  ?>
	  <td class="dataTableContent" width="5%"><?php echo $products['landing_page_id']; ?></td>
	  <td class="dataTableContent"><?php echo $products['landing_page_heading']; ?></td>
	  <td class="dataTableContent" align="right">
	  	<?php //if (isset($_GET['landing_page_id']) && ($products['landing_page_id'] == $_GET['landing_page_id']) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_LANDING_PAGES, 'landing_page_id=' . $products['landing_page_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; }
		echo '<a href="' . tep_href_link(FILENAME_LANDING_PAGES, 'landing_page_id=' . $products['landing_page_id'] . '&action=edit_product') . '">' . tep_image(DIR_WS_ICONS . 'edit.gif', IMAGE_EDIT) . '</a> &nbsp; <a href="' . tep_href_link(FILENAME_LANDING_PAGES, 'landing_page_id=' . $products['landing_page_id'] . '&action=delete_product') . '">' . tep_image(DIR_WS_ICONS . 'delete.gif', IMAGE_DELETE) . '</a> ';
		 ?>&nbsp;
	  </td>
	  <?php	
	}
echo '</tr>';
?>
<tr>
	<td class="smallText" align="right" colspan="4"><?php echo '<a href="' . tep_href_link(FILENAME_LANDING_PAGES, 'action=new_product') . '">' . tep_image_button('button_new.gif', IMAGE_NEW_PRODUCT) . '</a>'; ?></td>
</tr>
            </table></td>
<?php
    $heading = array();
    $contents = array();
	if(isset($_GET['landing_page_id']))
	{
	$page_name_query = tep_db_query("select landing_page_heading from ".TABLE_LANDING_PAGES." where landing_page_id=".$_GET['landing_page_id']."");
			$row_page_name = tep_db_fetch_array($page_name_query);
	}		
    switch ($action) {
	case 'delete_product':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_PRODUCT . '</b>');

        $contents = array('form' => tep_draw_form('products', FILENAME_LANDING_PAGES, 'action=delete_product_confirm') . tep_draw_hidden_field('landing_page_id', $_GET['landing_page_id']));
        $contents[] = array('text' => TEXT_DELETE_PRODUCT_INTRO);
        $contents[] = array('text' => '<br><b>' . $row_page_name['landing_page_heading'] . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_LANDING_PAGES, 'landing_page_id=' . $_GET['landing_page_id']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
   break;
   /*default:
        if ($rows > 0) {
          if (isset($_GET['landing_page_id'])) { // category info box contents
            
			$heading[] = array('text' => '<b>' . $row_page_name['landing_page_heading'] . '</b>');
            $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_LANDING_PAGES, 'landing_page_id=' . $_GET['landing_page_id'] . '&action=edit_product') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_LANDING_PAGES, 'landing_page_id=' . $_GET['landing_page_id'] . '&action=delete_product') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> ');

          }
        }
        break;*/
	}

    if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
      echo '<td width="25%" valign="top">' . "\n";

      $box = new box;
      echo $box->infoBox($heading, $contents);

      echo '            </td>' . "\n";
    }
?>
          </tr>
        </table></td>
      </tr>
    </table>
  
</td>
<?php
}
?>
 </tr>
</table>