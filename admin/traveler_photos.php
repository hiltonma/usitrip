<?php
/*
  $Id: reviews.php,v 1.1.1.1 2004/03/04 23:38:56 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
	 case 'ReMoveAllWaterImages':	//删除所有水印图片
		function delete_water_images($path,$include_subdirectory = false) {
			if(!file_exists($path) || !is_dir($path) || $path==''){
				return false;
			}
			echo "<dl>";
			$d = dir($path);
			while(false !== ($v = $d->read())) {
				if($v == "." || $v == "..") { continue; }
				$file = $d->path."/".$v;
				if(file_exists($file) && preg_match('/^watermark\_/',$v)){
					if(unlink($file)){
						echo "<dt>$v <b>Deleted.</b></dt>";
					}
				}
				if($include_subdirectory==true && is_dir($file)){
					delete_water_images($file);
				}
			}
			$d->close();
			echo "</dl>";
		}
		
		delete_water_images("/home/howard/public_html/usitrip/images/reviews", true);
	  break;
	 case 'CreateAllWaterImages':	//为所有相片创建水印
		function create_water_images($path,$file_name_header,$include_subdirectory = false) {
			if(!file_exists($path) || !is_dir($path) || $path==''){
				return false;
			}
			$echo_str ='<div>Start...</div>';

			$d = dir($path);
			
			while(false !== ($v = $d->read())) {
				$num++;	
				if($v == "." || $v == "..") { continue; }
				$file = $d->path."/".$v;
				if(file_exists($file) && preg_match('/^'.preg_quote($file_name_header).'/',$v)){
					
					if(defined('USE_WATER_LOGO') && USE_WATER_LOGO=='true'){
						$v_w_name = $d->path.'/watermark_'.$v;
						$make_action = makeCopyright($file, DIR_FS_CATALOG."image/logo_water.gif",$v_w_name,9,50);
						//$echo_str .= '<div>makeCopyright('.$file.','.DIR_FS_CATALOG."image/logo_water.gif".','.$d->path.'/watermark_'.$v.',9,50)</div>';
						if((int)$make_action){
							$echo_str .= "<div>$v_w_name <b>Create successfully.</b></div>";
						}
						//echo 'make_action:'.$make_action;
					}
				}
				if($include_subdirectory==true && is_dir($file)){
					create_water_images($file);
				}
			}
			$d->close();
			$echo_str ='<div>Done.</div>';

			return $echo_str;

		}
		echo create_water_images("/home/howard/public_html/usitrip/images/reviews",'detail_', false);
	  break;
	 case 'setflag':
        if ( ($HTTP_GET_VARS['flag'] == '0') || ($HTTP_GET_VARS['flag'] == '1') ) {
         
		  if (isset($HTTP_GET_VARS['tpID'])) {
		  	$traveler_photo_id = tep_db_prepare_input($HTTP_GET_VARS['tpID']);
			
			//exit;
         	tep_db_query("update " . TABLE_TRAVELER_PHOTOS . " set image_status = '" . $HTTP_GET_VARS['flag'] . "' where traveler_photo_id = '" . $traveler_photo_id . "'");
			
			if ($HTTP_GET_VARS['flag'] == '1'){
			$customer_query = tep_db_query("select customer_id, points_pending from " . TABLE_CUSTOMERS_POINTS_PENDING . " where points_status = 1 and points_type = 'PH' and orders_id = '" . (int)$traveler_photo_id . "' limit 1");
		          $customer_points = tep_db_fetch_array($customer_query);
		          if (tep_db_num_rows($customer_query)) {
			          
				          tep_db_query("update " . TABLE_CUSTOMERS_POINTS_PENDING . " set points_status = 2 where orders_id = '" . (int)$traveler_photo_id . "' and points_type = 'PH' limit 1");
				          tep_auto_fix_customers_points((int)$customer_points['customer_id']);	//自动校正用户积分
						  $sql = "optimize table " . TABLE_CUSTOMERS_POINTS_PENDING . "";
						  
					  $messageStack->add_session(SUCCESS_POINTS_UPDATED, 'success');
		          }
				  
			}// end if
          }
          //更新首页xml文件
		  update_xml_for_index_page_photo_sharing();
        }
       
		tep_redirect(tep_href_link(FILENAME_TRAVELER_PHOTOS, 'page=' . $HTTP_GET_VARS['page'] . '&tpID=' . $traveler_photo_id.(isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : '').(isset($HTTP_GET_VARS['sortorder']) ? '&sortorder=' . $HTTP_GET_VARS['sortorder'] . '' : '')));
        break;
		
      case 'setflag_homepage':
        if ( ($HTTP_GET_VARS['flag'] == '0') || ($HTTP_GET_VARS['flag'] == '1') ) {
         
		  if (isset($HTTP_GET_VARS['tpID'])) {
		  	$traveler_photo_id = tep_db_prepare_input($HTTP_GET_VARS['tpID']);
			
			//exit;
         	tep_db_query("update " . TABLE_TRAVELER_PHOTOS . " set is_display_homepage = '" . $HTTP_GET_VARS['flag'] . "' where traveler_photo_id = '" . $traveler_photo_id . "'");
          	//更新首页xml文件
		  	update_xml_for_index_page_photo_sharing();
          }
          
        }
       
		tep_redirect(tep_href_link(FILENAME_TRAVELER_PHOTOS, 'page=' . $HTTP_GET_VARS['page'] . '&tpID=' . $traveler_photo_id.(isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : '').(isset($HTTP_GET_VARS['sortorder']) ? '&sortorder=' . $HTTP_GET_VARS['sortorder'] . '' : '')));
        break;
	  case 'update':
        $traveler_photo_id = tep_db_prepare_input($HTTP_POST_VARS['traveler_photo_id']);
        $insert_id = rand(1000,3000);
		 if(basename($_FILES['image_name']['name']) != '' ){								
				$tmp_image_name = '';
				$uploadfile = DIR_FS_CATALOG_IMAGES.'reviews/'.$insert_id.'_'.basename($_FILES['image_name']['name']);
				if (move_uploaded_file($_FILES['image_name']['tmp_name'],$uploadfile)) {
					$tmp_image_name = $insert_id.'_'.basename($_FILES['image_name']['name']);
				} 
			$detail_image = 0;
			$thumb_image = 0;
			$target = DIR_FS_CATALOG_IMAGES.'reviews/';
			$size = getimagesize($uploadfile);
			$width = $size[0];
			//exit;
			if($width>425)
			{
				$detail_image = 1;
				imageCompression($uploadfile,425,$target  .'detail_'. $tmp_image_name);
			}
			
			if($width>70)
			{
				$thumb_image = 1;
				imageCompression($uploadfile,70,$target  .'thumb_'. $tmp_image_name);
			}
			
			if($detail_image == 1){
				
				//move_uploaded_file($imgfile,$target.'detail_'.$insert_id.'_'. $entry); 
				@unlink($uploadfile);
			}		
			$image_name_disp = $tmp_image_name;
			
			//delete previouse image
			if($_POST['previous_image_name'] != '' && basename($_FILES['image_name']['name'][$key]) != $_POST['previous_image_name']){
			   
				$basename_image_name = basename($_POST['previous_image_name']);
				$image_name0 = str_replace($basename_image_name, 'thumb_'.$basename_image_name, $_POST['previous_image_name'] );
				$image_name1 = str_replace($basename_image_name, 'detail_'.$basename_image_name, $_POST['previous_image_name'] );
				$image_name2 = str_replace($basename_image_name, 'watermark_detail_'.$basename_image_name, $_POST['previous_image_name'] );
		
			   if(DIR_FS_DOCUMENT_ROOT . '/images/reviews/'. $image_name0)
				{
					@unlink(DIR_FS_DOCUMENT_ROOT . '/images/reviews/'. $image_name0);
				}
				if(DIR_FS_DOCUMENT_ROOT . '/images/reviews/'. $image_name1)
				{
					@unlink(DIR_FS_DOCUMENT_ROOT . '/images/reviews/'. $image_name1);
				}
				if(DIR_FS_DOCUMENT_ROOT . '/images/reviews/'. $image_name2)
				{
					@unlink(DIR_FS_DOCUMENT_ROOT . '/images/reviews/'. $image_name2);
				}
				if(DIR_FS_DOCUMENT_ROOT . '/images/reviews/' . $_POST['previous_image_name'])
				{
					@unlink(DIR_FS_DOCUMENT_ROOT . '/images/reviews/' . $_POST['previous_image_name']);
				}
			   
			   
			   //@unlink(DIR_FS_CATALOG_IMAGES.'reviews/' . $_POST['previouse_image_name']);
			}
		}else{
				//assine image name form privouse value
				
				$image_name_disp = $_POST['previous_image_name'];
		}

        
		$sql_data_array = array('customers_name' => $customers_name,
							    'customers_email' => tep_db_prepare_input($HTTP_POST_VARS['customers_email']),
								'image_name' => $image_name_disp,
								'image_title' => tep_db_prepare_input($HTTP_POST_VARS['image_title']),
								'image_status' => tep_db_prepare_input($HTTP_POST_VARS['image_status']),
								'is_display_homepage' => tep_db_prepare_input($HTTP_POST_VARS['is_display_homepage']),
							    'image_desc' => tep_db_prepare_input($HTTP_POST_VARS['image_desc'])
							    );
		

        /*tep_db_query("update " . TABLE_TRAVELER_PHOTOS . " set reviews_rating = '" . tep_db_input($reviews_rating) . "', reviews_status='" .$reviews_status. "', last_modified = now() where traveler_photo_id = '" . (int)$traveler_photo_id . "'");
        tep_db_query("update " . TABLE_TRAVELER_PHOTOS_DESCRIPTION . " set reviews_title = '" . tep_db_input($reviews_title) . "', reviews_text = '" . tep_db_input($reviews_text) . "' where traveler_photo_id = '" . (int)$traveler_photo_id . "'");*/
		
		tep_db_perform(TABLE_TRAVELER_PHOTOS, $sql_data_array, 'update', "traveler_photo_id = '" . (int)$traveler_photo_id . "'");
		
		if (tep_db_prepare_input($HTTP_POST_VARS['image_status']) == '1'){
			$customer_query = tep_db_query("select customer_id, points_pending from " . TABLE_CUSTOMERS_POINTS_PENDING . " where points_status = 1 and points_type = 'PH' and orders_id = '" . (int)$traveler_photo_id . "' limit 1");
		          $customer_points = tep_db_fetch_array($customer_query);
		          if (tep_db_num_rows($customer_query)) {
			          
				          tep_db_query("update " . TABLE_CUSTOMERS_POINTS_PENDING . " set points_status = 2 where orders_id = '" . (int)$traveler_photo_id . "' and points_type = 'PH' limit 1");
				          tep_auto_fix_customers_points((int)$customer_points['customer_id']);	//自动校正用户积分
						  $sql = "optimize table " . TABLE_CUSTOMERS_POINTS_PENDING . "";
						  
					  $messageStack->add_session(SUCCESS_POINTS_UPDATED, 'success');
		          }
				  
			}// end if

        tep_redirect(tep_href_link(FILENAME_TRAVELER_PHOTOS,tep_get_all_get_params(array('action'))));
        break;
      case 'deleteconfirm':
        $traveler_photo_id = tep_db_prepare_input($HTTP_GET_VARS['tpID']);

   		$image_name_query = tep_db_query("select image_name from ".TABLE_TRAVELER_PHOTOS." where traveler_photo_id=".$traveler_photo_id);
		$row_image_name = tep_db_fetch_array($image_name_query);
		
		$basename_image_name = basename($row_image_name['image_name']);
		$image_name0 = str_replace($basename_image_name, 'thumb_'.$basename_image_name, $row_image_name['image_name'] );
		$image_name1 = str_replace($basename_image_name, 'detail_'.$basename_image_name, $row_image_name['image_name'] );
		$image_name2 = str_replace($basename_image_name, 'watermark_detail_'.$basename_image_name, $row_image_name['image_name'] );
		
		if(DIR_FS_DOCUMENT_ROOT . '/images/reviews/' . $image_name0)
		{
			@unlink(DIR_FS_DOCUMENT_ROOT . '/images/reviews/' . $image_name0);
		}
		if(DIR_FS_DOCUMENT_ROOT . '/images/reviews/' . $image_name1)
		{
			@unlink(DIR_FS_DOCUMENT_ROOT . '/images/reviews/' . $image_name1);
		}
		if(DIR_FS_DOCUMENT_ROOT . '/images/reviews/' . $image_name2)
		{
			@unlink(DIR_FS_DOCUMENT_ROOT . '/images/reviews/' . $image_name2);
		}
		if(DIR_FS_DOCUMENT_ROOT . '/images/reviews/' . $row_image_name['image_name'])
		{
			@unlink(DIR_FS_DOCUMENT_ROOT . '/images/reviews/' . $row_image_name['image_name']);
		}
		

		tep_db_query("delete from " . TABLE_TRAVELER_PHOTOS . " where traveler_photo_id = '" . (int)$traveler_photo_id . "'");
        //tep_db_query("delete from " . TABLE_TRAVELER_PHOTOS_DESCRIPTION . " where traveler_photo_id = '" . (int)$traveler_photo_id . "'");
		
          //更新首页xml文件
		  update_xml_for_index_page_photo_sharing();

        if(isset($HTTP_GET_VARS['pID']))
		{
			tep_redirect(tep_href_link(FILENAME_TRAVELER_PHOTOS, 'page=' . $HTTP_GET_VARS['page'].'&pID='.$HTTP_GET_VARS['pID']));
		}
		else
		{
			tep_redirect(tep_href_link(FILENAME_TRAVELER_PHOTOS, 'page=' . $HTTP_GET_VARS['page']));
		}
        break;
    }
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
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
	  	<?php
		if ($action != 'edit') {
		?>
		<td align="right" class="main"><input type="hidden" name="abc" value="">
			<?php 
			  $all_products_statuses = array(array('id' => '', 'text' => 'All Tours'));
			 	$all_products_query = tep_db_query("select p.products_model, p.products_id, pd.products_name, p.products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, ".TABLE_TRAVELER_PHOTOS." ptp where pd.products_id = ptp.products_id and p.products_id = ptp.products_id and p.products_id = pd.products_id and pd.products_name!='' and pd.language_id = '" . (int)$languages_id . "' group by ptp.products_id order by products_name");
				while ($all_products = tep_db_fetch_array($all_products_query)) {
				
					  $all_products_statuses[] = array('id' => $all_products['products_id'],
                               					 'text' => $all_products['products_name'].' ['.$all_products['products_model'].']');
				
			 	}
				?>
				<?php

			  	echo tep_draw_form('goto', FILENAME_TRAVELER_PHOTOS,'','get');
				
			    echo '<b>Go to:</b>&nbsp;&nbsp;' . tep_draw_pull_down_menu('pID', $all_products_statuses, $_GET['pID'], ' style="width:550px;" onChange="this.form.submit();"');
				//style="width:500px;" 
				
			    echo '</form>';
				?>
		</td>
		<?php } ?>
	  </tr>
	  <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">
			<?php echo HEADING_TITLE; ?>
			<?php if($login_groups_id=='1'){?>
			<a href="<?php echo tep_href_link('traveler_photos.php','action=ReMoveAllWaterImages')?>"><input type="button" value="ReMoveAllWaterImages"></a>
			<a href="<?php echo tep_href_link('traveler_photos.php','action=CreateAllWaterImages')?>"><input type="button" value="CreateAllWaterImages"></a>
			<?php 
			}
			?>
			
			</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
<?php
  //if ($action == 'edit') {} elseif ($action == 'preview') {} else {
   if ($action == 'edit') {
   		?>
		<tr>
        	<td>
				<table align="left" border="0" width="50%" cellspacing="2" cellpadding="2">
					<?php echo tep_draw_form('frmedit', FILENAME_TRAVELER_PHOTOS,tep_get_all_get_params(array('action')).'action=update', 'post', 'enctype="multipart/form-data"'); 
					
					$edit_data_query = tep_db_query("select * from ".TABLE_TRAVELER_PHOTOS." where traveler_photo_id = '".$_GET['tpID']."'");
					$row_edit_data = tep_db_fetch_array($edit_data_query);
					echo tep_draw_hidden_field('traveler_photo_id', $_GET['tpID']);
					//if (!isset($rInfo->reviews_status)) $rInfo->reviews_status = '1';
					switch ($row_edit_data['image_status']) {
					  case '0': $in_status = false; $out_status = true; break;
					  case '1':
					  default: $in_status = true; $out_status = false;
					}
					switch ($row_edit_data['is_display_homepage']) {
					  case '0': $yes_status = false; $no_status = true; break;
					  case '1': $yes_status = true; $no_status = false; break;
					  default: $yes_status = false; $no_status = true; break;
					}
					?>
					<tr>
						<td class="main"><?php echo TEXT_EDIT_CUSTOMERS_NAME; ?></td>
						<td class="main"><?php echo tep_draw_input_field('customers_name',$row_edit_data['customers_name'],' class="pr_b_text" style="width: 50%;"'); ?></td>
					</tr>
					
					<tr>
						<td class="main"><?php echo TEXT_EDIT_CUSTOMERS_EMAIL; ?></td>
						<td class="main"><?php echo tep_draw_input_field('customers_email',$row_edit_data['customers_email'],' class="pr_b_text" style="width: 50%;"');  ?></td>
					</tr>
					<tr>
						<td class="main"><?php echo TEXT_EDIT_IMAGE_STATUS; ?></td>
						<td class="main"><?php echo tep_draw_radio_field('image_status', '1', $in_status) . '&nbsp;On&nbsp;' . tep_draw_radio_field('image_status', '0', $out_status).'Off'; ?></td>
					</tr>
					<tr>
						<td class="main"><?php echo TEXT_EDIT_IMAGE_DISPLAY_HOMEPAGE; ?></td>
						<td class="main"><?php echo tep_draw_radio_field('is_display_homepage', '1', $yes_status) . '&nbsp;Yes&nbsp;' . tep_draw_radio_field('is_display_homepage', '0', $no_status).'No'; ?></td>
					</tr>
					<tr>
						<td class="main"><?php echo TEXT_EDIT_IMAGE_TITLE; ?></td>
						<td class="main"><?php echo tep_draw_input_field('image_title',$row_edit_data['image_title'],' class="pr_b_text" style="width: 70%;"');  ?> </td>
					</tr>
					
					<tr>
						<td class="main"><?php echo TEXT_EDIT_IMAGE; ?></td>
						<td class="main">
							<?php 
								if($row_edit_data['image_name']!='')
								{
								$image_name = $row_edit_data['image_name'];
								$basename_image_name = basename($row_edit_data['image_name']);
								$image_name0 = str_replace($basename_image_name, 'thumb_'.$basename_image_name, $row_edit_data['image_name'] );
								$image_name1 = str_replace($basename_image_name, 'detail_'.$basename_image_name, $row_edit_data['image_name'] );
								$image_name2 = str_replace($basename_image_name, 'watermark_detail_'.$basename_image_name, $row_edit_data['image_name'] );
								
									if(file_exists(DIR_FS_DOCUMENT_ROOT . '/images/reviews/'. $image_name0)) {
										echo tep_image(HTTP_CATALOG_SERVER.DIR_WS_CATALOG_IMAGES . 'reviews/'. $image_name0);
									}
									else if(file_exists(DIR_FS_DOCUMENT_ROOT . '/images/reviews/'. $image_name1)){
										echo tep_image(HTTP_CATALOG_SERVER.DIR_WS_CATALOG_IMAGES . 'reviews/'. $image_name1,'',100,100);
									}
									else
									{
										echo tep_image(HTTP_CATALOG_SERVER.DIR_WS_CATALOG_IMAGES . 'reviews/' . $image_name,'',100,100);
										//echo tep_image(HTTP_CATALOG_SERVER.DIR_WS_CATALOG_IMAGES . 'reviews/thumb_' . $reviews['image_name']);
									}
									
									
								}
								echo '<br />';
								echo tep_draw_file_field('image_name');
								echo tep_draw_hidden_field('previous_image_name', $row_edit_data['image_name']);
							?>
						</td>
					</tr>
					
					<tr>
						<td class="main"><?php echo TEXT_EDIT_IMAGE_DESC; ?></td>
						<td class="main"><?php //echo tep_draw_input_field('image_desc',$row_edit_data['image_desc'],' class="pr_b_text" style="width: 50%;"');
						echo tep_draw_textarea_field('image_desc', 'soft', '50', '5', $row_edit_data['image_desc']);
						  ?>&nbsp; </td>
					</tr>
					
					<tr>
        <td></td><td class="main" align="right"><?php echo tep_image_submit('button_update.gif', IMAGE_PREVIEW) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_TRAVELER_PHOTOS,tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
      </tr>

					<?php
					echo '</form>';
					?>
				</table>
			</td>
		</tr>
		<?php
   } else {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
				  <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CUSTOMERS_EMAIL; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_PHOTO; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_DATE_ADDED; ?></td>
				 <td class="dataTableHeadingContent" align="right"><?php echo TEXT_EDIT_IMAGE_DISPLAY_HOMEPAGE; ?></td>              
				 <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_IMAGE_STATUS; ?></td>              
                 <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    if(isset($_GET['pID'])&&$_GET['pID']!='')
	{
		$where = " where products_id=".$_GET['pID'];
	}
	else
	{
		$where = "";
	}
	$reviews_query_raw = "select traveler_photo_id, products_id, added_date, image_name, image_status, is_display_homepage, customers_email from " . TABLE_TRAVELER_PHOTOS . " ".$where." order by added_date DESC";
    $reviews_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $reviews_query_raw, $reviews_query_numrows);
    $reviews_query = tep_db_query($reviews_query_raw);
    while ($reviews = tep_db_fetch_array($reviews_query)) {
      if ((!isset($HTTP_GET_VARS['tpID']) || (isset($HTTP_GET_VARS['tpID']) && ($HTTP_GET_VARS['tpID'] == $reviews['traveler_photo_id']))) && !isset($rInfo)) {
        $reviews_text_query = tep_db_query("select * from " . TABLE_TRAVELER_PHOTOS . " where traveler_photo_id = '" . (int)$reviews['traveler_photo_id'] . "' ");
        $reviews_text = tep_db_fetch_array($reviews_text_query);

        $products_image_query = tep_db_query("select products_image from " . TABLE_PRODUCTS . " where products_id = '" . (int)$reviews['products_id'] . "'");
        $products_image = tep_db_fetch_array($products_image_query);

        $products_name_query = tep_db_query("select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$reviews['products_id'] . "' and language_id = '" . (int)$languages_id . "'");
        $products_name = tep_db_fetch_array($products_name_query);

        //$reviews_average_query = tep_db_query("select (avg(reviews_rating) / 5 * 100) as average_rating from " . TABLE_TRAVELER_PHOTOS . " where products_id = '" . (int)$reviews['products_id'] . "'");
        //$reviews_average = tep_db_fetch_array($reviews_average_query);

        $review_info = array_merge((array)$reviews_text,  (array)$products_name);
        $rInfo_array = array_merge((array)$reviews, (array)$review_info, (array)$products_image);
        $rInfo = new objectInfo($rInfo_array);
      }

      if (isset($rInfo) && is_object($rInfo) && ($reviews['traveler_photo_id'] == $rInfo->traveler_photo_id) ) {
        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_TRAVELER_PHOTOS, 'tpID=' . $rInfo->traveler_photo_id .(isset($HTTP_GET_VARS['page']) ? '&page=' . $HTTP_GET_VARS['page'] . '' : '').(isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : '') .(isset($HTTP_GET_VARS['sortorder']) ? '&sortorder=' . $HTTP_GET_VARS['sortorder'] . '' : ''). '&action=preview') . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_TRAVELER_PHOTOS, 'tpID=' .  $reviews['traveler_photo_id'].(isset($HTTP_GET_VARS['page']) ? '&page=' . $HTTP_GET_VARS['page'] . '' : '').(isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : '') .(isset($HTTP_GET_VARS['sortorder']) ? '&sortorder=' . $HTTP_GET_VARS['sortorder'] . '' : '')) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php //echo '<a href="' . tep_href_link(FILENAME_TRAVELER_PHOTOS, 'page=' . $HTTP_GET_VARS['page'] . '&tpID=' . $reviews['traveler_photo_id'] . '&action=preview') . '">' . tep_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '</a>&nbsp;' ; ?><?php echo "<a target='_blank' href=".HTTP_CATALOG_SERVER."/".seo_get_products_path($reviews['products_id'], true,'traveler-photos').">".tep_get_products_name($reviews['products_id']).' ['.tep_get_products_model($reviews['products_id'])."]</a>"; ?></td>
                <td class="dataTableContent" ><?php echo $reviews['customers_email']; ?></td>
				<td class="dataTableContent" align="right">
					<?php 
						$basename_image_name = basename($reviews['image_name']);
						$image_name0 = str_replace($basename_image_name, 'thumb_'.$basename_image_name, $reviews['image_name'] );
						$image_name1 = str_replace($basename_image_name, 'detail_'.$basename_image_name, $reviews['image_name'] );
						$image_name2 = str_replace($basename_image_name, 'watermark_detail_'.$basename_image_name, $reviews['image_name'] );
					
						if(file_exists(DIR_FS_DOCUMENT_ROOT . '/images/reviews/' . $image_name0))
						{
							echo tep_image(HTTP_CATALOG_SERVER.DIR_WS_CATALOG_IMAGES . 'reviews/' . $image_name0); //'<a href="javascript:popupImageWindow(\'' . FILENAME_POPUP_IMAGE . '?tphoto=' . $reviews['traveler_photo_id'] . '\')">'..'</a>'
						}
						else if(file_exists(DIR_FS_DOCUMENT_ROOT . '/images/reviews/' . $image_name1))
						{
							echo tep_image(HTTP_CATALOG_SERVER.DIR_WS_CATALOG_IMAGES . 'reviews/' . $image_name1,'',70,50); //'<a href="javascript:popupImageWindow(\'' . FILENAME_POPUP_IMAGE . '?tphoto=' . $reviews['traveler_photo_id'] . '\')">'..'</a>'
						}
						else if(file_exists(DIR_FS_DOCUMENT_ROOT . '/images/reviews/' . $reviews['image_name']))
						{
							echo tep_image(HTTP_CATALOG_SERVER.DIR_WS_CATALOG_IMAGES . 'reviews/' . $reviews['image_name'],'',70,50); //'<a href="javascript:popupImageWindow(\'' . FILENAME_POPUP_IMAGE . '?tphoto=' . $reviews['traveler_photo_id'] . '\')">'..'</a>'
						}

						?>
				</td>
               
				
				
				<td class="dataTableContent" align="right"><?php echo tep_date_short($reviews['added_date']); ?></td>
                <td class="dataTableContent" align="center">
				<?php
					  if ($reviews['is_display_homepage'] == '1') {
						echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_TRAVELER_PHOTOS, 'action=setflag_homepage&flag=0&tpID=' . $reviews['traveler_photo_id'].(isset($HTTP_GET_VARS['page']) ? '&page=' . $HTTP_GET_VARS['page'] . '' : '').(isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : '').(isset($HTTP_GET_VARS['sortorder']) ? '&sortorder=' . $HTTP_GET_VARS['sortorder'] . '' : '')) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
					  } else {
						echo '<a href="' . tep_href_link(FILENAME_TRAVELER_PHOTOS, 'action=setflag_homepage&flag=1&tpID=' . $reviews['traveler_photo_id'] .(isset($HTTP_GET_VARS['page']) ? '&page=' . $HTTP_GET_VARS['page'] . '' : ''). (isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : ''). (isset($HTTP_GET_VARS['sortorder']) ? '&sortorder=' . $HTTP_GET_VARS['sortorder'] . '' : '')) .'">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
					  }
				?>				
				</td>
				<td class="dataTableContent" align="center">
				<?php
					  if ($reviews['image_status'] == '1') {
						echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_TRAVELER_PHOTOS, 'action=setflag&flag=0&tpID=' . $reviews['traveler_photo_id'].(isset($HTTP_GET_VARS['page']) ? '&page=' . $HTTP_GET_VARS['page'] . '' : '').(isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : '').(isset($HTTP_GET_VARS['sortorder']) ? '&sortorder=' . $HTTP_GET_VARS['sortorder'] . '' : '')) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
					  } else {
						echo '<a href="' . tep_href_link(FILENAME_TRAVELER_PHOTOS, 'action=setflag&flag=1&tpID=' . $reviews['traveler_photo_id'] .(isset($HTTP_GET_VARS['page']) ? '&page=' . $HTTP_GET_VARS['page'] . '' : ''). (isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : '').(isset($HTTP_GET_VARS['sortorder']) ? '&sortorder=' . $HTTP_GET_VARS['sortorder'] . '' : '')) .'">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
					  }
				?>				</td>
			    <td class="dataTableContent" align="right"><?php if ( (is_object($rInfo)) && ($reviews['traveler_photo_id'] == $rInfo->traveler_photo_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . tep_href_link(FILENAME_TRAVELER_PHOTOS, 'page=' . $HTTP_GET_VARS['page'] . '&tpID=' . $reviews['traveler_photo_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }
?>
              <tr>
                <td colspan="7"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $reviews_split->display_count($reviews_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_PHOTOS); ?></td>
                    <td class="smallText" align="right"><?php echo $reviews_split->display_links($reviews_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'],'pID='.$HTTP_GET_VARS['pID']); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
    $heading = array();
    $contents = array();

    switch ($action) {
      case 'delete':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_REVIEW . '</b>');

        $contents = array('form' => tep_draw_form('reviews', FILENAME_TRAVELER_PHOTOS, 'page=' . $HTTP_GET_VARS['page'] . '&tpID=' . $rInfo->traveler_photo_id . '&pID='.$HTTP_GET_VARS['pID'].'&action=deleteconfirm'));
        $contents[] = array('text' => TEXT_INFO_DELETE_REVIEW_INTRO);
        $contents[] = array('text' => '<br><b>' . $rInfo->products_name . '</b>');
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_TRAVELER_PHOTOS, 'page=' . $HTTP_GET_VARS['page'] . '&pID=' . $HTTP_GET_VARS['pID'] . '&tpID=' . $rInfo->traveler_photo_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      default:
      if (isset($rInfo) && is_object($rInfo)) {
        $heading[] = array('text' => '<b>' . $rInfo->products_name . '</b>');
//'page=' . $HTTP_GET_VARS['page'] . '&tpID=' . $rInfo->traveler_photo_id
       $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_TRAVELER_PHOTOS,  tep_get_all_get_params(array('action')). '&tpID=' . $rInfo->traveler_photo_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_TRAVELER_PHOTOS, 'page=' . $HTTP_GET_VARS['page'] . '&pID=' . $HTTP_GET_VARS['pID'] . '&tpID=' . $rInfo->traveler_photo_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_DATE_ADDED . ' ' . tep_date_short($rInfo->added_date));
			
        if (tep_not_null($rInfo->last_modified)) $contents[] = array('text' => TEXT_INFO_LAST_MODIFIED . ' ' . tep_date_short($rInfo->last_modified));
		
		
		$basename_image_name = basename($rInfo->image_name);
		$image_name0 = str_replace($basename_image_name, 'thumb_'.$basename_image_name, $rInfo->image_name );
		$image_name1 = str_replace($basename_image_name, 'detail_'.$basename_image_name, $rInfo->image_name );
		$image_name2 = str_replace($basename_image_name, 'watermark_detail_'.$basename_image_name, $rInfo->image_name );
		
		if(file_exists(DIR_FS_DOCUMENT_ROOT . '/images/reviews/' . $image_name1))
		{
			$image_dir_file_name = HTTP_CATALOG_SERVER.DIR_WS_CATALOG_IMAGES . 'reviews/'.$image_name1;
			if(file_exists(DIR_FS_DOCUMENT_ROOT . '/images/reviews/' . $image_name2)){
				$image_dir_file_name = HTTP_CATALOG_SERVER.DIR_WS_CATALOG_IMAGES . 'reviews/'.$image_name2;
			}elseif(defined('USE_WATER_LOGO') && USE_WATER_LOGO=='true'){
				makeCopyright(DIR_FS_CATALOG.'images/reviews/'.$image_name1, DIR_FS_CATALOG."image/logo_water.gif",str_replace('/detail_','/watermark_detail_',DIR_FS_CATALOG.'images/reviews/'.$image_name1),9,50);
			}
			$contents[] = array('text' => '<br>' . tep_image($image_dir_file_name, $rInfo->image_title));
		}
		else if(file_exists(DIR_FS_DOCUMENT_ROOT . '/images/reviews/' . $rInfo->image_name))
		{
			$image_dir_file_name = HTTP_CATALOG_SERVER.DIR_WS_CATALOG_IMAGES . 'reviews/'.$rInfo->image_name;
			if(file_exists(DIR_FS_DOCUMENT_ROOT . '/images/reviews/watermark_' . $rInfo->image_name)){
				$image_dir_file_name = HTTP_CATALOG_SERVER.DIR_WS_CATALOG_IMAGES . 'reviews/watermark_'.$rInfo->image_name;
			}elseif(defined('USE_WATER_LOGO') && USE_WATER_LOGO=='true'){
				makeCopyright(DIR_FS_CATALOG.'images/reviews/'.$rInfo->image_name, DIR_FS_CATALOG."image/logo_water.gif",str_replace('reviews/','reviews/watermark_',DIR_FS_CATALOG.'images/reviews/'.$rInfo->image_name),9,50);
			}
			$contents[] = array('text' => '<br>' . tep_image($image_dir_file_name, $rInfo->image_title));
		}
		else if(file_exists(DIR_FS_DOCUMENT_ROOT . '/images/reviews/' . $image_name0))
		{
			$contents[] = array('text' => '<br>' . tep_image(HTTP_CATALOG_SERVER.DIR_WS_CATALOG_IMAGES . 'reviews/'.$image_name0, $rInfo->image_title));
		}
		else
		{
		}
		
		
        $contents[] = array('text' => '<br>' . TEXT_INFO_REVIEW_AUTHOR . ' ' . $rInfo->customers_name);
		if (tep_not_null($rInfo->customers_email)){
        $contents[] = array('text' =>  TEXT_INFO_REVIEW_AUTHOR_EMAIL . ' ' . $rInfo->customers_email);			
		}
		$contents[] = array('text' => '<br>' . TEXT_INFO_IMAGE_TITLE . ' ' . $rInfo->image_title);
		$contents[] = array('text' => '<br>' . TEXT_INFO_IMAGE_DESC . '<br /> ' . $rInfo->image_desc);
		//$contents[] = array('text' => TEXT_INFO_REVIEW_RATING . ' ' . tep_image(HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . 'stars_' . $rInfo->reviews_rating . '.gif'));
        //$contents[] = array('text' => TEXT_INFO_REVIEW_READ . ' ' . $rInfo->reviews_read);
        //$contents[] = array('text' => '<br>' . TEXT_INFO_REVIEW_SIZE . ' ' . $rInfo->reviews_text_size . ' bytes');
        //$contents[] = array('text' => '<br>' . TEXT_INFO_PRODUCTS_AVERAGE_RATING . ' ' . number_format($rInfo->average_rating, 2) . '%');
      }
        break;
    }

    if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
      echo '            <td width="25%" valign="top">' . "\n";

      $box = new box;
      echo $box->infoBox($heading, $contents);

      echo '            </td>' . "\n";
    }
?>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
