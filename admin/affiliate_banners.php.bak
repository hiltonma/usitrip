<?php
/*
  $Id: affiliate_banners.php,v 2.00 2003/10/12

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $affiliate_banner_extension = tep_banner_image_extension();

  if ($HTTP_GET_VARS['action']) {
    switch ($HTTP_GET_VARS['action']) {
	 case 'move_banner_confirm':
        $abID = tep_db_prepare_input($HTTP_POST_VARS['abID']);
        $new_group_id = tep_db_prepare_input($HTTP_POST_VARS['affiliate_banners_group_move']);
		$cPath = tep_db_prepare_input($HTTP_POST_VARS['cPath']);
		
		tep_db_query("update " . TABLE_AFFILIATE_BANNERS . " set affiliate_banners_group = '" . $new_group_id . "' where affiliate_banners_id = '" . (int)$abID . "'");

        tep_redirect(tep_href_link(FILENAME_AFFILIATE_BANNER_MANAGER, 'cPath=' . $new_group_id . '&abID=' . $abID));
        break;
      case 'setaffiliate_flag':
        if ( ($HTTP_GET_VARS['affiliate_flag'] == '0') || ($HTTP_GET_VARS['affiliate_flag'] == '1') ) {
          tep_set_affiliate_banner_status($HTTP_GET_VARS['abID'], $HTTP_GET_VARS['affiliate_flag']);
          $messageStack->add_session(SUCCESS_BANNER_STATUS_UPDATED, 'success');
        } else {
          $messageStack->add_session(ERROR_UNKNOWN_STATUS_FLAG, 'error');
        }
        tep_redirect(tep_href_link(FILENAME_AFFILIATE_BANNER_MANAGER, 'selected_box=affiliate&page=' . $HTTP_GET_VARS['page'] .(isset($HTTP_GET_VARS['cPath']) ? '&cPath=' . $HTTP_GET_VARS['cPath'] . '' : ''). '&abID=' . $HTTP_GET_VARS['abID']));
        break;
      case 'insert':
      case 'update':
        $affiliate_banners_id = tep_db_prepare_input($HTTP_POST_VARS['affiliate_banners_id']);
       
        $affiliate_products_id  = tep_db_prepare_input($HTTP_POST_VARS['affiliate_products_id']);
// Added Category Banners
        $affiliate_category_id  = tep_db_prepare_input($HTTP_POST_VARS['affiliate_category_id']);
// End Category Banners
        $new_affiliate_banners_group = tep_db_prepare_input($HTTP_POST_VARS['new_affiliate_banners_group']);
        if(!empty($new_affiliate_banners_group)){
		//insert on bew table		
			$check_banner_group_name_query="select * from ". TABLE_AFFILIATE_BANNERS_GROUPS . " where affiliate_banners_group_name='".$new_affiliate_banners_group."'";
			$check_banner_group_name=tep_db_query($check_banner_group_name_query);
			if(tep_db_num_rows($check_banner_group_name)==0)
			{
					$sql_data_array = array( 'affiliate_banners_group_name' => tep_db_input($new_affiliate_banners_group),
									'affiliate_banners_date_added' => 'now()');
					tep_db_perform(TABLE_AFFILIATE_BANNERS_GROUPS, $sql_data_array);
					$affiliate_banners_group = tep_db_insert_id();
			}
			else
			{	
				$fetch_banner_group_name=tep_db_fetch_array($check_banner_group_name);
				$affiliate_banners_group = $fetch_banner_group_name['affiliate_banners_group_id'];
			}

		
		}else{
		$affiliate_banners_group =  tep_db_prepare_input($HTTP_POST_VARS['affiliate_banners_group']);
		
		}
		
		
        $affiliate_banners_image_target = tep_db_prepare_input($HTTP_POST_VARS['affiliate_banners_image_target']);
        $affiliate_html_text = tep_db_prepare_input($HTTP_POST_VARS['affiliate_html_text']);
        $affiliate_banners_image_local = tep_db_prepare_input($HTTP_POST_VARS['affiliate_banners_image_local']);
        $affiliate_banners_image_target = tep_db_prepare_input($HTTP_POST_VARS['affiliate_banners_image_target']);
        $db_image_location = '';

        
		if(empty($affiliate_category_id))
	  	{
	  	  $affiliate_products_query = tep_db_query("select pd.products_name, p.products_image from " . TABLE_PRODUCTS_DESCRIPTION . " as pd ," . TABLE_PRODUCTS . " as p where pd.products_id = p.products_id and pd.products_id = '" . $affiliate_products_id . "'");
		  $affiliate_products = tep_db_fetch_array($affiliate_products_query);
		  $affiliate_banners_title = $affiliate_products['products_name'];
		  $db_image_location = $affiliate_products['products_image'];
	  	}
	  	else
		{
	  	  $affiliate_products_query = tep_db_query("select pd.categories_name, p.categories_image from " . TABLE_CATEGORIES_DESCRIPTION . " as pd ," . TABLE_CATEGORIES . " as p where pd.categories_id = p.categories_id and pd.categories_id = '" . $affiliate_category_id . "' ");
		  $affiliate_products = tep_db_fetch_array($affiliate_products_query);
		  $affiliate_banners_title = $affiliate_products['categories_name'];
		  $db_image_location = $affiliate_products['categories_image'];
		}
	  
		
		$affiliate_banner_error = false;
        if (empty($affiliate_banners_title)) {
          $messageStack->add(ERROR_BANNER_TITLE_REQUIRED, 'error');
          $affiliate_banner_error = true;
        }
     if (empty($affiliate_banners_group)) {
          $messageStack->add(ERROR_BANNER_GROUP_REQUIRED, 'error');
          $affiliate_banner_error = true;
        }

        if ( ($affiliate_banners_image) && ($affiliate_banners_image != 'none') && (is_uploaded_file($affiliate_banners_image)) ) {
          if (!is_writeable(DIR_FS_CATALOG_IMAGES . $affiliate_banners_image_target)) {
            if (is_dir(DIR_FS_CATALOG_IMAGES . $affiliate_banners_image_target)) {
              $messageStack->add(ERROR_IMAGE_DIRECTORY_NOT_WRITEABLE, 'error');
            } else {
              $messageStack->add(ERROR_IMAGE_DIRECTORY_DOES_NOT_EXIST, 'error');
            }
            $affiliate_banner_error = true;
          }
        }

        if (!$affiliate_banner_error) {
          if (empty($affiliate_html_text)) {
            if ( ($affiliate_banners_image) && ($affiliate_banners_image != 'none') && (is_uploaded_file($affiliate_banners_image)) ) {
              $image_location = DIR_FS_CATALOG_IMAGES . $affiliate_banners_image_target . $affiliate_banners_image_name;
              copy($affiliate_banners_image, $image_location);
            }
            $db_image_location = (!empty($affiliate_banners_image_local)) ? $affiliate_banners_image_local : $affiliate_banners_image_target . $affiliate_banners_image_name;
          }
		  
		   if ($HTTP_POST_VARS['affiliate_banners_title'] != ''){
		   $affiliate_banners_title = tep_db_prepare_input($HTTP_POST_VARS['affiliate_banners_title']);
			}
          if (!$affiliate_products_id) $affiliate_products_id="0";
// Added Category Banners
          if (!$affiliate_category_id) $affiliate_category_id="0";
// End Category Banners
            $sql_data_array = array('affiliate_banners_title' => $affiliate_banners_title,
                                    'affiliate_products_id' => $affiliate_products_id,
// Added Category Banners
                                    'affiliate_category_id' => $affiliate_category_id,
// End Category Banners
                                    'affiliate_banners_image' => $db_image_location,
                                    'affiliate_banners_group' => $affiliate_banners_group);

          if ($HTTP_GET_VARS['action'] == 'insert') {
            $insert_sql_data = array('affiliate_date_added' => 'now()',
                                     'affiliate_status' => '1');
            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
            tep_db_perform(TABLE_AFFILIATE_BANNERS, $sql_data_array);
            $affiliate_banners_id = tep_db_insert_id();

          // Banner ID 1 is generic Product Banner
            if ($affiliate_banners_id==1) tep_db_query("update " . TABLE_AFFILIATE_BANNERS . " set affiliate_banners_id = affiliate_banners_id + 1");
            $messageStack->add_session(SUCCESS_BANNER_INSERTED, 'success');
          } elseif ($HTTP_GET_VARS['action'] == 'update') {
            $insert_sql_data = array('affiliate_date_status_change' => 'now()');
            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
            tep_db_perform(TABLE_AFFILIATE_BANNERS, $sql_data_array, 'update', 'affiliate_banners_id = \'' . $affiliate_banners_id . '\'');
            $messageStack->add_session(SUCCESS_BANNER_UPDATED, 'success');
          }
		  if ($HTTP_GET_VARS['action'] == 'insert') {	
          	  tep_redirect(tep_href_link(FILENAME_AFFILIATE_BANNER_MANAGER, 'action=new&page=' . $HTTP_GET_VARS['page'] .(isset($HTTP_GET_VARS['cPath']) ? '&cPath=' . $HTTP_GET_VARS['cPath'] . '' : '') . '&abID=' . $affiliate_banners_id));
          }else{
			  tep_redirect(tep_href_link(FILENAME_AFFILIATE_BANNER_MANAGER, 'selected_box=affiliate&page=' . $HTTP_GET_VARS['page'] .(isset($HTTP_GET_VARS['cPath']) ? '&cPath=' . $HTTP_GET_VARS['cPath'] . '' : '') . '&abID=' . $affiliate_banners_id));
          }
		
		} else {
          $HTTP_GET_VARS['action'] = 'new';
        }
        break;
      case 'deleteconfirm':
        $affiliate_banners_id = tep_db_prepare_input($HTTP_GET_VARS['abID']);
        $delete_image = tep_db_prepare_input($HTTP_POST_VARS['delete_image']);

        if ($delete_image == 'on') {
          $affiliate_banner_query = tep_db_query("select affiliate_banners_image from " . TABLE_AFFILIATE_BANNERS . " where affiliate_banners_id = '" . tep_db_input($affiliate_banners_id) . "'");
          $affiliate_banner = tep_db_fetch_array($affiliate_banner_query);
          if (is_file(DIR_FS_CATALOG_IMAGES . $affiliate_banner['affiliate_banners_image'])) {
            if (is_writeable(DIR_FS_CATALOG_IMAGES . $affiliate_banner['affiliate_banners_image'])) {
              unlink(DIR_FS_CATALOG_IMAGES . $affiliate_banner['affiliate_banners_image']);
            } else {
              $messageStack->add_session(ERROR_IMAGE_IS_NOT_WRITEABLE, 'error');
            }
          } else {
            $messageStack->add_session(ERROR_IMAGE_DOES_NOT_EXIST, 'error');
          }
        }

        tep_db_query("delete from " . TABLE_AFFILIATE_BANNERS . " where affiliate_banners_id = '" . tep_db_input($affiliate_banners_id) . "'");
        tep_db_query("delete from " . TABLE_AFFILIATE_BANNERS_HISTORY . " where affiliate_banners_id = '" . tep_db_input($affiliate_banners_id) . "'");

        $messageStack->add_session(SUCCESS_BANNER_REMOVED, 'success');

        tep_redirect(tep_href_link(FILENAME_AFFILIATE_BANNER_MANAGER, 'selected_box=affiliate&page=' . $HTTP_GET_VARS['page'].(isset($HTTP_GET_VARS['cPath']) ? '&cPath=' . $HTTP_GET_VARS['cPath'] . '' : '')));
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
<script language="javascript"><!--
function popupImageWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=600,height=300,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
<script language="javascript"><!--

function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=780,height=550,screenX=100,screenY=100,top=75,left=75')
}
//--></script>

<script language="javascript">
function check_form(theForm){

/*
 if (theForm.affiliate_products_id.value == "")
  {
    alert("Please enter the Full Name.");
    theForm.affiliate_products_id.focus();
    return (false);
  }
  */
  
  if(theForm.affiliate_products_id.value != '' && theForm.affiliate_products_id.value > 0 && theForm.affiliate_category_id.value != '' && theForm.affiliate_category_id.value > 0 )
  {
  	alert("Please enter either Category ID or Product ID.");
    theForm.affiliate_products_id.focus();
    return (false);
  }
//alert(theForm.affiliate_products_id.value);
//alert(theForm.typeofadd.checkded);
return true;
}
</script>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<div id="spiffycalendar" class="text"></div>
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
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
	  <tr>
        <td align="right" class="main">
			<?php
			
			if ($HTTP_GET_VARS['action'] != 'new') { 
			
	$groups_array = array();
	/* amit commented for old affiliate banner groups
    $groups_query = tep_db_query("select distinct affiliate_banners_group from " . TABLE_AFFILIATE_BANNERS . " order by affiliate_banners_group");
    while ($groups = tep_db_fetch_array($groups_query)) {
      $groups_array[] = array('id' => $groups['affiliate_banners_group'], 'text' => $groups['affiliate_banners_group']);
    }
	*/
	$groups_query = tep_db_query("select distinct affiliate_banners_group_name,affiliate_banners_group_id from " . TABLE_AFFILIATE_BANNERS_GROUPS . " order by affiliate_banners_group_name");
    $groups_array[] = array('id' => '', 'text' => 'Select All Banner Groups');
  
	while ($groups = tep_db_fetch_array($groups_query)) {
		
      $groups_array[] = array('id' => $groups['affiliate_banners_group_id'], 'text' => $groups['affiliate_banners_group_name']);
    }
			    echo tep_draw_form('goto', FILENAME_AFFILIATE_BANNERS, '', 'get');
				echo HEADING_TITLE_GOTO . ' ' . tep_draw_pull_down_menu('cPath', $groups_array, $abInfo->affiliate_banners_group, 'onChange="this.form.submit();"');
				echo '</form>';
				
				}
			?>
		</td>
      </tr>
<?php
  if ($HTTP_GET_VARS['action'] == 'new') {
    $form_action = 'insert';
    if ($HTTP_GET_VARS['abID']) {
      $abID = tep_db_prepare_input($HTTP_GET_VARS['abID']);
      $form_action = 'update';

      $affiliate_banner_query = tep_db_query("select * from " . TABLE_AFFILIATE_BANNERS . " where affiliate_banners_id = '" . tep_db_input($abID) . "'");
      $affiliate_banner = tep_db_fetch_array($affiliate_banner_query);

      $abInfo = new objectInfo($affiliate_banner);
    } elseif ($HTTP_POST_VARS) {
      $abInfo = new objectInfo($HTTP_POST_VARS);
    } else {
      $abInfo = new objectInfo(array());
    }

    $groups_array = array();
	/* amit commented for old affiliate banner groups
    $groups_query = tep_db_query("select distinct affiliate_banners_group from " . TABLE_AFFILIATE_BANNERS . " order by affiliate_banners_group");
    while ($groups = tep_db_fetch_array($groups_query)) {
      $groups_array[] = array('id' => $groups['affiliate_banners_group'], 'text' => $groups['affiliate_banners_group']);
    }
	*/
	$groups_query = tep_db_query("select distinct affiliate_banners_group_name,affiliate_banners_group_id from " . TABLE_AFFILIATE_BANNERS_GROUPS . " order by affiliate_banners_group_name");
    while ($groups = tep_db_fetch_array($groups_query)) {
      $groups_array[] = array('id' => $groups['affiliate_banners_group_id'], 'text' => $groups['affiliate_banners_group_name']);
    }
	
	
	
	
	
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr><?php echo tep_draw_form('new_banner', FILENAME_AFFILIATE_BANNER_MANAGER, 'page=' . $HTTP_GET_VARS['page'] .(isset($HTTP_GET_VARS['cPath']) ? '&cPath=' . $HTTP_GET_VARS['cPath'] . '' : ''). '&action=' . $form_action, 'post', 'enctype="multipart/form-data" onSubmit="return check_form(new_banner);" '); if ($form_action == 'update') echo tep_draw_hidden_field('affiliate_banners_id', $abID); ?>
        <td><table border="0" cellspacing="2" cellpadding="2">
          <!--<tr>
            <td class="main"><?php //echo TEXT_BANNERS_TITLE; ?></td>
            <td class="main"><?php //echo tep_draw_input_field('affiliate_banners_title', $abInfo->affiliate_banners_title, '', true); ?>
			</td>
          </tr>
          <tr>
            <td colspan="2"><?php //echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>-->
		   <tr>
            <td colspan="2" class="main"><b><?php echo '<font size="2">Step-I</font>'; ?>  Enter Product or Category ID</b></td>
          </tr>
          <tr>
            <td class="main" width="22%"><?php echo TEXT_BANNERS_LINKED_PRODUCT; ?></td>
            <td class="main"><input type="radio" name="typeofadd" <?php if($abInfo->affiliate_products_id > 0){echo 'checked';} ?>  value="prod_in" onClick="javascript:document.new_banner.affiliate_products_id.focus();" ><?php echo tep_draw_input_field('affiliate_products_id', $abInfo->affiliate_products_id, '', false); ?></td>
          </tr>
          <tr>
            <td class="main" colspan=2><?php echo TEXT_BANNERS_LINKED_PRODUCT_NOTE ?></td>
          </tr>
          <tr>
            <td class="main" colspan=2>&nbsp;&nbsp;</b><?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_AFFILIATE_VALIDPRODS_CATES) . '\')"><b>' . TEXT_AFFILIATE_VALIDPRODUCTS . '</b></a>'; ?>&nbsp;&nbsp;<?php echo TEXT_AFFILIATE_INDIVIDUAL_BANNER_VIEW;?></td>
          </tr>
          <tr>
            <td class="main" colspan=2><?php echo TEXT_AFFILIATE_INDIVIDUAL_BANNER_HELP;?>
          </tr>

		  <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_BANNERS_LINKED_CATEGORY; ?></td>
            <td class="main"><input type="radio"  name="typeofadd" <?php if($abInfo->affiliate_category_id > 0){echo 'checked';} ?> value="cat_in"  onClick="javascript:document.new_banner.affiliate_category_id.focus();" ><?php echo tep_draw_input_field('affiliate_category_id', $abInfo->affiliate_category_id, '', false); ?></td>
          </tr>
          <tr>
            <td class="main" colspan=2><?php echo TEXT_BANNERS_LINKED_CATEGORY_NOTE ?></td>
          </tr>
          <tr>
            <td class="main" colspan=2>&nbsp;&nbsp;</b><?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_AFFILIATE_VALIDPRODS_CATES) . '\')"><b>' . TEXT_AFFILIATE_VALIDPRODUCTS . '</b></a>'; ?>&nbsp;&nbsp;<?php echo TEXT_AFFILIATE_CATEGORY_BANNER_VIEW;?></td>
          </tr>
          <tr>
            <td class="main" colspan=2><?php echo TEXT_AFFILIATE_CATEGORY_BANNER_HELP;?>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '15'); ?></td>
          </tr>
		   <tr>
            <td colspan="2" class="main"><b><?php echo '<font size="2">Step-II</font>'; ?>  Update Banner (Leave blank to use default)</b></td>
          </tr>
		   <tr>
            <td class="main" valign="top">Banner
			<?php //echo TEXT_BANNERS_IMAGE; ?></td>
            <td class="main"><?php echo tep_draw_file_field('affiliate_banners_image') . ' <br>' . DIR_FS_CATALOG_IMAGES . tep_draw_input_field('affiliate_banners_image_local', $abInfo->affiliate_banners_image); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '15'); ?></td>
          </tr>
		   <tr>
            <td colspan="2" class="main"><b><?php echo '<font size="2">Step-III</font>'; ?>  Enter Text (Leave blank to use default)</b></td>
          </tr>
		  <tr>
            <td class="main"><?php echo TEXT_BANNERS_TITLE; ?></td>
            <td class="main"><?php echo tep_draw_input_field('affiliate_banners_title', $abInfo->affiliate_banners_title, '', false); ?>
			</td>
          </tr>
		  
          <tr>
            <td class="main"><?php //echo TEXT_BANNERS_IMAGE_TARGET; ?></td>
            <td class="main"><?php // echo DIR_FS_CATALOG_IMAGES . tep_draw_input_field('affiliate_banners_image_target'); ?></td>
          </tr>

          <tr>
            <td class="main" valign="top"><?php echo TEXT_BANNERS_GROUP; ?></td>
			<?php
			if(isset($HTTP_GET_VARS['cPath']) &&  $form_action = 'insert'){
				$abInfo->affiliate_banners_group =	$HTTP_GET_VARS['cPath']; 
			}
			
			?>
            <td class="main"><?php echo tep_draw_pull_down_menu('affiliate_banners_group', $groups_array, $abInfo->affiliate_banners_group) . TEXT_BANNERS_NEW_GROUP . '<br>' . tep_draw_input_field('new_affiliate_banners_group', '', '', ((sizeof($groups_array) > 0) ? false : true)); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
<?php
$affiliate_banners_values = tep_db_query("select * from " . TABLE_AFFILIATE_BANNERS . " where affiliate_banners_id = '" . tep_db_input($abID) . "' order by affiliate_banners_title");
  if (tep_db_num_rows($affiliate_banners_values)) {

    while ($affiliate_banners = tep_db_fetch_array($affiliate_banners_values)) {
      //echo "select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . $affiliate_banners['affiliate_products_id'] . "' and language_id = '" . $languages_id . "'";
	  if($affiliate_banners['affiliate_category_id']!=0)
	  {
	  	  $affiliate_products_query = tep_db_query("select pd.categories_name, p.categories_image from " . TABLE_CATEGORIES_DESCRIPTION . " as pd ," . TABLE_CATEGORIES . " as p where pd.categories_id = p.categories_id and pd.categories_id = '" . $affiliate_banners['affiliate_category_id'] . "' and pd.language_id = '" . $languages_id . "'");
		  $affiliate_products = tep_db_fetch_array($affiliate_products_query);
		  $products_name = $affiliate_products['categories_name'];
		  $products_images = $affiliate_products['categories_image'];
	  }
	  else
	  {
		  $affiliate_products_query = tep_db_query("select pd.products_name, p.products_image from " . TABLE_PRODUCTS_DESCRIPTION . " as pd ," . TABLE_PRODUCTS . " as p where pd.products_id = p.products_id and pd.products_id = '" . $affiliate_banners['affiliate_products_id'] . "' and pd.language_id = '" . $languages_id . "'");
		  $affiliate_products = tep_db_fetch_array($affiliate_products_query);
		  $products_name = $affiliate_products['products_name'];
		  $products_images = $affiliate_products['products_image'];
	  }
	  
	  
	    if($affiliate_banners['affiliate_banners_image'] != ''){
	   $products_images = $affiliate_banners['affiliate_banners_image'];
	  }
	  
	   if ($affiliate_banners['affiliate_banners_title'] != ''){
		   $products_name = tep_db_prepare_input($affiliate_banners['affiliate_banners_title']);
			}
      
      $prod_id = $affiliate_banners['affiliate_products_id'];
      $ban_id = $affiliate_banners['affiliate_banners_id'];
	   
	   define('AFFILIATE_KIND_OF_BANNERS','1');
      switch (AFFILIATE_KIND_OF_BANNERS) {
        case 1: // Link to Products
          if ($prod_id > 0) {
            $link = '<a href="' . tep_catalog_href_link(FILENAME_PRODUCT_INFO , 'ref=' . $affiliate_id . '&products_id=' . $prod_id . '&affiliate_banner_id=' . $ban_id ). '" target="_blank"><img src="' . HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . $products_images . '" border="0" alt="' . $affiliate_products['products_name'] . '"></a>';
            $link2 = '<a href="' . tep_catalog_href_link(FILENAME_PRODUCT_INFO , 'ref=' . $affiliate_id . '&products_id=' . $prod_id . '&affiliate_banner_id=' . $ban_id ). '" target="_blank">' . $products_name . '</a>'; 
   		  } else { // generic_link
            $link = '<a href="' . tep_catalog_href_link(FILENAME_DEFAULT , 'cPath='.$affiliate_banners['affiliate_category_id'].'&ref=' . $affiliate_id . '&affiliate_banner_id=' . $ban_id ). '" target="_blank"><img src="' . HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . $products_images . '" border="0" alt="' . $affiliate_banners['affiliate_banners_title'] . '"></a>';
		    $link2 = '<a href="' . tep_catalog_href_link(FILENAME_DEFAULT , 'cPath='.$affiliate_banners['affiliate_category_id'].'&ref=' . $affiliate_id . '&affiliate_banner_id=' . $ban_id ). '" target="_blank">'.$affiliate_banners['affiliate_banners_title'].'</a>';
      
          }		   
          break;
        case 2: // Link to Products
          if ($prod_id > 0) {
            $link = '<a href="' . tep_catalog_href_link(FILENAME_PRODUCT_INFO , 'ref=' . $affiliate_id . '&products_id=' . $prod_id . '&affiliate_banner_id=' . $ban_id ). '" target="_blank"><img src="' . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_AFFILIATE_SHOW_BANNER . '?ref=' . $affiliate_id . '&affiliate_banner_id=' . $ban_id . '" border="0" alt="' . $affiliate_products['products_name'] . '"></a>';
			$link2 = '<a href="' . tep_catalog_href_link(FILENAME_PRODUCT_INFO , 'ref=' . $affiliate_id . '&products_id=' . $prod_id . '&affiliate_banner_id=' . $ban_id ). '" target="_blank">'.$products_name.'</a>';
          } else { // generic_link
            $link = '<a href="' . tep_catalog_href_link(FILENAME_DEFAULT , 'cPath='.$affiliate_banners['affiliate_category_id'].'&ref=' . $affiliate_id . '&affiliate_banner_id=' . $ban_id ). '" target="_blank"><img src="' . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_AFFILIATE_SHOW_BANNER . '?ref=' . $affiliate_id . '&affiliate_banner_id=' . $ban_id . '" border="0" alt="' . $affiliate_banners['affiliate_banners_title'] . '"></a>';
			$link2 = '<a href="' . tep_catalog_href_link(FILENAME_DEFAULT , 'cPath='.$affiliate_banners['affiliate_category_id'].'&ref=' . $affiliate_id . '&affiliate_banner_id=' . $ban_id ). '" target="_blank">'.$affiliate_banners['affiliate_banners_title'].'</a>';
          }
          break; 
      }

?>
    <tr>
	    <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
		 <tr> 
            <td><td> 
          </tr> 
          <tr>
            <td class="infoBoxHeading" align="center"><?php echo TEXT_AFFILIATE_NAME; ?>&nbsp;<?php echo  $products_name; ?></td>
          </tr>
         <tr>
            <td class="smallText" align="center"><br><?php echo $link; ?></td>
          </tr>
          <tr>
            <td class="smallText" align="center"><?php echo TEXT_AFFILIATE_INFO; ?></td>
          </tr>
          <tr>
            <td align="center"><textarea cols="60" rows="3" class="boxText"><?php echo $link; ?></textarea> 
			<?php //echo tep_draw_textarea_field('affiliate_banner', 'soft', '60', '6', $link); ?></td>
          </tr>
		   <tr> 
            <td><td> 
          </tr> 
		   <tr> 
            <td class="smallText" align="center"><b>Text Version:</b> <?php echo $link2; ?></td> 
          </tr> 
          <tr> 
            <td class="smallText" align="center"><?php echo TEXT_AFFILIATE_INFO; ?></td> 
          </tr> 
          <tr> 
            <td class="smallText" align="center"> 
             <textarea cols="60" rows="3" class="boxText"><?php echo $link2; ?></textarea> 
            </td> 
          </tr>		
     </table></td>
          </tr>
  <?php
  }
      }
  ?>
        
       
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main" align="right" valign="top" nowrap><?php echo (($form_action == 'insert') ? tep_image_submit('button_preview.gif', IMAGE_INSERT) : tep_image_submit('button_update.gif', IMAGE_UPDATE)). '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNER_MANAGER, 'selected_box=affiliate&page=' . $HTTP_GET_VARS['page'] .(isset($HTTP_GET_VARS['cPath']) ? '&cPath=' . $HTTP_GET_VARS['cPath'] . '' : '') . '&abID=' . $HTTP_GET_VARS['abID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
          </tr>
        </table></td>
      </form></tr>
	   </table></td>
      </tr>
<?php
  } else {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_BANNERS; ?></td>
				<td class="dataTableHeadingContent" align="right"><?php echo 'Status'; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_CATEGORY_ID; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRODUCT_ID; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_STATISTICS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    if(isset($HTTP_GET_VARS['cPath']) && $HTTP_GET_VARS['cPath'] != '' && $HTTP_GET_VARS['cPath']>0 )
	{
		$affiliate_banners_query_raw = "select * from " . TABLE_AFFILIATE_BANNERS . " where affiliate_banners_group=".$HTTP_GET_VARS['cPath']." order by affiliate_banners_title, affiliate_banners_group";
	}
	else
	{
	$affiliate_banners_query_raw = "select * from " . TABLE_AFFILIATE_BANNERS . " order by affiliate_banners_title, affiliate_banners_group";
	}
    $affiliate_banners_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $affiliate_banners_query_raw, $affiliate_banners_query_numrows);
    $affiliate_banners_query = tep_db_query($affiliate_banners_query_raw);
    while ($affiliate_banners = tep_db_fetch_array($affiliate_banners_query)) {
      $info_query = tep_db_query("select sum(affiliate_banners_shown) as affiliate_banners_shown, sum(affiliate_banners_clicks) as affiliate_banners_clicks from " . TABLE_AFFILIATE_BANNERS_HISTORY . " where affiliate_banners_id = '" . $affiliate_banners['affiliate_banners_id'] . "'");
      $info = tep_db_fetch_array($info_query);

      if (((!$HTTP_GET_VARS['abID']) || ($HTTP_GET_VARS['abID'] == $affiliate_banners['affiliate_banners_id'])) && (!$abInfo) && (substr($HTTP_GET_VARS['action'], 0, 3) != 'new')) {
        $abInfo_array = array_merge($affiliate_banners, $info);
        $abInfo = new objectInfo($abInfo_array);
      }

      $affiliate_banners_shown = ($info['affiliate_banners_shown'] != '') ? $info['affiliate_banners_shown'] : '0';
      $affiliate_banners_clicked = ($info['affiliate_banners_clicks'] != '') ? $info['affiliate_banners_clicks'] : '0';

      if ( (is_object($abInfo)) && ($affiliate_banners['affiliate_banners_id'] == $abInfo->affiliate_banners_id) ) {
        echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_AFFILIATE_BANNERS,'abID=' . $abInfo->affiliate_banners_id . '&action=new'.(isset($HTTP_GET_VARS['cPath']) ? '&cPath=' . $HTTP_GET_VARS['cPath'] . '' : '').(isset($HTTP_GET_VARS['page']) ? '&page=' . $HTTP_GET_VARS['page'] . '' : ''))  . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_AFFILIATE_BANNERS, 'abID=' . $affiliate_banners['affiliate_banners_id']).(isset($HTTP_GET_VARS['cPath']) ? '&cPath=' . $HTTP_GET_VARS['cPath'] . '' : '').(isset($HTTP_GET_VARS['page']) ? '&page=' . $HTTP_GET_VARS['page'] . '' : '') . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo '<a href="javascript:popupImageWindow(\'' . FILENAME_AFFILIATE_POPUP_IMAGE . '?banner=' . $affiliate_banners['affiliate_banners_id'] . '\')">' . tep_image(DIR_WS_IMAGES . 'icon_popup.gif', ICON_PREVIEW) . '</a>&nbsp;' . $affiliate_banners['affiliate_banners_title']; ?></td>
<? // Added Category Banners

?>
<td class="dataTableContent" align="right">
<?php
      if ($affiliate_banners['affiliate_status'] == '1') {
        echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', 'Active', 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNER_MANAGER, 'page=' . $HTTP_GET_VARS['page'] . '&abID=' . $affiliate_banners['affiliate_banners_id'] . '&action=setaffiliate_flag&affiliate_flag=0').(isset($HTTP_GET_VARS['cPath']) ? '&cPath=' . $HTTP_GET_VARS['cPath'] . '' : '') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', 'Set Inactive', 10, 10) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNER_MANAGER, 'page=' . $HTTP_GET_VARS['page'] .(isset($HTTP_GET_VARS['cPath']) ? '&cPath=' . $HTTP_GET_VARS['cPath'] . '' : ''). '&abID=' . $affiliate_banners['affiliate_banners_id'] . '&action=setaffiliate_flag&affiliate_flag=1') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', 'Set Active', 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', 'Inactive', 10, 10);
      }
?>			</td>

                <td class="dataTableContent" align="right"><?php if ($affiliate_banners['affiliate_category_id']>0) echo $affiliate_banners['affiliate_category_id']; else echo '&nbsp;'; ?></td>
<? // End Category Banners
?>
                <td class="dataTableContent" align="right"><?php if ($affiliate_banners['affiliate_products_id']>0) echo $affiliate_banners['affiliate_products_id']; else echo '&nbsp;'; ?></td>
                <td class="dataTableContent" align="right"><?php echo $affiliate_banners_shown . ' / ' . $affiliate_banners_clicked; ?></td>
                <td class="dataTableContent" align="right"><?php if ( (is_object($abInfo)) && ($affiliate_banners['affiliate_banners_id'] == $abInfo->affiliate_banners_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNER_MANAGER, 'selected_box=affiliate&page=' . $HTTP_GET_VARS['page'] .(isset($HTTP_GET_VARS['cPath']) ? '&cPath=' . $HTTP_GET_VARS['cPath'] . '' : ''). '&abID=' . $affiliate_banners['affiliate_banners_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }
?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $affiliate_banners_split->display_count($affiliate_banners_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_BANNERS); ?></td>
                    <td class="smallText" align="right"><?php echo $affiliate_banners_split->display_links($affiliate_banners_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></td>
                  </tr>
                  <tr>
                    <td align="right" colspan="2"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNER_GROUPS, (isset($HTTP_GET_VARS['cPath']) ? 'affiliate_banners_group_id=' . $HTTP_GET_VARS['cPath'] . '' : '')) . '">' . tep_image_button('button_back.gif', 'Back') . '</a>'; ?>&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNER_MANAGER, 'selected_box=affiliate&action=new'.(isset($HTTP_GET_VARS['cPath']) ? '&cPath=' . $HTTP_GET_VARS['cPath'] . '' : '')) . '">' . tep_image_button('button_new_banner.gif', IMAGE_NEW_BANNER) . '</a>'; ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();
  switch ($HTTP_GET_VARS['action']) {
  	 case 'move_banner':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_MOVE_PRODUCT . '</b>');

        $contents = array('form' => tep_draw_form('banners_move', FILENAME_AFFILIATE_BANNER_MANAGER, 'action=move_banner_confirm' . (isset($HTTP_GET_VARS['cPath']) ? '&cPath=' . $HTTP_GET_VARS['cPath'] . '' : '')) . tep_draw_hidden_field('abID', $abInfo->affiliate_banners_id));
       
		 $sql = "select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . $abInfo->affiliate_products_id . "' and language_id = '" . $languages_id . "'"; 
        $product_description_query = tep_db_query($sql);
        $product_description = tep_db_fetch_array($product_description_query);
        
		
		if($abInfo->affiliate_banners_image == ''){

				   if($abInfo->affiliate_category_id !=0 )
					  {
						  $affiliate_products_query = tep_db_query("select pd.categories_name, p.categories_image from " . TABLE_CATEGORIES_DESCRIPTION . " as pd ," . TABLE_CATEGORIES . " as p where pd.categories_id = p.categories_id and pd.categories_id = '" . $abInfo->affiliate_category_id . "' and pd.language_id = '" . $languages_id . "'");
						  $affiliate_products = tep_db_fetch_array($affiliate_products_query);
						  $products_name = $affiliate_products['categories_name'];
						  $abInfo->affiliate_banners_image = $affiliate_products['categories_image'];
					  }
					  else
					  {
						  $affiliate_products_query = tep_db_query("select pd.products_name, p.products_image from " . TABLE_PRODUCTS_DESCRIPTION . " as pd ," . TABLE_PRODUCTS . " as p where pd.products_id = p.products_id and pd.products_id = '" . $abInfo->affiliate_products_id . "' and pd.language_id = '" . $languages_id . "'");
						  $affiliate_products = tep_db_fetch_array($affiliate_products_query);
						  $products_name = $affiliate_products['products_name'];
						  $abInfo->affiliate_banners_image = $affiliate_products['products_image'];
					  }
						   
		}
		
	$groups_array_move = array();
	
	$groups_query_move = tep_db_query("select distinct affiliate_banners_group_name,affiliate_banners_group_id from " . TABLE_AFFILIATE_BANNERS_GROUPS . " order by affiliate_banners_group_name");
   // $groups_array_move[] = array('id' => '', 'text' => 'Select All Banner Groups');
  
	while ($groups_move = tep_db_fetch_array($groups_query_move)) {
		
      $groups_array_move[] = array('id' => $groups_move['affiliate_banners_group_id'], 'text' => $groups_move['affiliate_banners_group_name']);
    }
		
		$contents[] = array('text' => sprintf(TEXT_MOVE_PRODUCTS_INTRO, $abInfo->affiliate_banners_title ));
		
       // $contents[] = array('text' => '<br>'.TEXT_INFO_CURRENT_CATEGORIES . '<br><b>' . $groups_array_move[$abInfo->affiliate_banners_id]['text'] . '</b>');
        $contents[] = array('text' => '<br>' . sprintf(TEXT_MOVE, $abInfo->affiliate_banners_title) . '<br>' .  tep_draw_pull_down_menu('affiliate_banners_group_move', $groups_array_move, $abInfo->affiliate_banners_group));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_move.gif', IMAGE_MOVE) . ' <a href="' . tep_href_link(FILENAME_AFFILIATE_BANNER_MANAGER, 'abID=' . $abInfo->affiliate_banners_id .(isset($HTTP_GET_VARS['cPath']) ? '&cPath=' . $HTTP_GET_VARS['cPath'] . '' : '').(isset($HTTP_GET_VARS['page']) ? '&page=' . $HTTP_GET_VARS['page'] . '' : '')). '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
    case 'delete':
      $heading[] = array('text' => '<b>' . $abInfo->affiliate_banners_title . '</b>');

      $contents = array('form' => tep_draw_form('affiliate_banners', FILENAME_AFFILIATE_BANNER_MANAGER, 'page=' . $HTTP_GET_VARS['page'] .(isset($HTTP_GET_VARS['cPath']) ? '&cPath=' . $HTTP_GET_VARS['cPath'] . '' : ''). '&abID=' . $abInfo->affiliate_banners_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $abInfo->affiliate_banners_title . '</b>');
      if ($abInfo->affiliate_banners_image) $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('delete_image', 'on', true) . ' ' . TEXT_INFO_DELETE_IMAGE);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . '&nbsp;<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNER_MANAGER, 'selected_box=affiliate&page=' . $HTTP_GET_VARS['page'] .(isset($HTTP_GET_VARS['cPath']) ? '&cPath=' . $HTTP_GET_VARS['cPath'] . '' : ''). '&abID=' . $HTTP_GET_VARS['abID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (is_object($abInfo)) {
        $sql = "select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . $abInfo->affiliate_products_id . "' and language_id = '" . $languages_id . "'"; 
        $product_description_query = tep_db_query($sql);
        $product_description = tep_db_fetch_array($product_description_query);
        
		
		if($abInfo->affiliate_banners_image == ''){

				   if($abInfo->affiliate_category_id !=0 )
					  {
						  $affiliate_products_query = tep_db_query("select pd.categories_name, p.categories_image from " . TABLE_CATEGORIES_DESCRIPTION . " as pd ," . TABLE_CATEGORIES . " as p where pd.categories_id = p.categories_id and pd.categories_id = '" . $abInfo->affiliate_category_id . "' and pd.language_id = '" . $languages_id . "'");
						  $affiliate_products = tep_db_fetch_array($affiliate_products_query);
						  $products_name = $affiliate_products['categories_name'];
						  $abInfo->affiliate_banners_image = $affiliate_products['categories_image'];
					  }
					  else
					  {
						  $affiliate_products_query = tep_db_query("select pd.products_name, p.products_image from " . TABLE_PRODUCTS_DESCRIPTION . " as pd ," . TABLE_PRODUCTS . " as p where pd.products_id = p.products_id and pd.products_id = '" . $abInfo->affiliate_products_id . "' and pd.language_id = '" . $languages_id . "'");
						  $affiliate_products = tep_db_fetch_array($affiliate_products_query);
						  $products_name = $affiliate_products['products_name'];
						  $abInfo->affiliate_banners_image = $affiliate_products['products_image'];
					  }
						   
		}
		
		
		$heading[] = array('text' => '<b>' . $abInfo->affiliate_banners_title . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNER_MANAGER, 'selected_box=affiliate&page=' . $HTTP_GET_VARS['page'] .(isset($HTTP_GET_VARS['cPath']) ? '&cPath=' . $HTTP_GET_VARS['cPath'] . '' : '') . '&abID=' . $abInfo->affiliate_banners_id . '&action=new') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_AFFILIATE_BANNER_MANAGER, 'selected_box=affiliate&page=' . $HTTP_GET_VARS['page'] . '&abID=' . $abInfo->affiliate_banners_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_AFFILIATE_BANNER_MANAGER, 'abID=' . $abInfo->affiliate_banners_id .(isset($HTTP_GET_VARS['cPath']) ? '&cPath=' . $HTTP_GET_VARS['cPath'] . '' : '').(isset($HTTP_GET_VARS['page']) ? '&page=' . $HTTP_GET_VARS['page'] . '' : ''). '&action=move_banner') . '">' . tep_image_button('button_move.gif', IMAGE_MOVE) . '</a>');
      //  $contents[] = array('text' => $product_description['products_name']);
		 $contents[] = array('text' => $products_name);
		
		//amit added for banner images start
		 $contents[] = array('text' => '<br>'.tep_image(HTTP_CATALOG_SERVER.'/'.DIR_WS_IMAGES.$abInfo->affiliate_banners_image));
		//amit added for banner images end
        $contents[] = array('text' => '<br>' . TEXT_BANNERS_DATE_ADDED . ' ' . tep_date_short($abInfo->affiliate_date_added));
        $contents[] = array('text' => '' . sprintf(TEXT_BANNERS_STATUS_CHANGE, tep_date_short($abInfo->affiliate_date_status_change)));
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
