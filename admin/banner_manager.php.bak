<?php
/*
  $Id: banner_manager.php,v 1.1.1.1 2004/03/04 23:38:12 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  // 备注添加删除
  if($_GET['ajax']=="true"){
  	include DIR_FS_CLASSES . 'Remark.class.php';
  	$remark = new Remark('banner_manager');
  	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
  }
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  $banner_extension = tep_banner_image_extension();

  if (tep_not_null($action)) {
    switch ($action) {
      case 'setflag':
        if ( ($_GET['flag'] == '0') || ($_GET['flag'] == '1') ) {
          tep_set_banner_status($_GET['bID'], $_GET['flag']);

          $messageStack->add_session(SUCCESS_BANNER_STATUS_UPDATED, 'success');
        } else {
          $messageStack->add_session(ERROR_UNKNOWN_STATUS_FLAG, 'error');
        }

        tep_redirect(tep_href_link(FILENAME_BANNER_MANAGER, 'page=' . $_GET['page'] . '&bID=' . $_GET['bID']));
        break;
      case 'insert':
      case 'update':
        if (isset($_POST['banners_id'])) $banners_id = tep_db_prepare_input($_POST['banners_id']);
        $banners_title = tep_db_prepare_input($_POST['banners_title']);
        $banners_url = tep_db_prepare_input($_POST['banners_url']);
        $new_banners_group = tep_db_prepare_input($_POST['new_banners_group']);
        $banners_group = (empty($new_banners_group)) ? tep_db_prepare_input($_POST['banners_group']) : $new_banners_group;
        $banners_html_text = tep_db_prepare_input($_POST['banners_html_text']);
        $banners_image_local = tep_db_prepare_input($_POST['banners_image_local']);
        $banners_image_target = tep_db_prepare_input($_POST['banners_image_target']);
        $db_image_location = '';
        $expires_date = tep_db_prepare_input($_POST['expires_date']);
        $expires_impressions = tep_db_prepare_input($_POST['expires_impressions']);
        $date_scheduled = tep_db_prepare_input($_POST['date_scheduled']);
		$banner_sort_order = tep_db_prepare_input($_POST['banner_sort_order']);
		$banner_language_code_name = tep_db_prepare_input($_POST['banner_language_code_name']);	
		$banners_type = tep_db_prepare_input($_POST['banners_type']);
		$categories_ids = tep_db_prepare_input($_POST['categories_ids']);
		if($banners_type=='Image' || $banners_type=='Flash'){
			$banners_html_text = '';
		}	

        $banner_error = false;
        if (empty($banners_title)) {
          $messageStack->add(ERROR_BANNER_TITLE_REQUIRED, 'error');
          $banner_error = true;
        }

        if (empty($banners_group)) {
          $messageStack->add(ERROR_BANNER_GROUP_REQUIRED, 'error');
          $banner_error = true;
        }

        if (empty($banners_html_text)) {
          if (empty($banners_image_local)) {
            $banners_image = new upload('banners_image');
            $banners_image->set_destination(DIR_FS_CATALOG_IMAGES . $banners_image_target);
            if ( ($banners_image->parse() == false) || ($banners_image->save() == false) ) {
              $messageStack->add('Error: Please upload files or input HTML Text content.', 'error');
			  $banner_error = true;
            }
          }
        }

        if ($banner_error == false) {
          $db_image_location = (tep_not_null($banners_image_local)) ? $banners_image_local : $banners_image_target . $banners_image->filename;
          $sql_data_array = array('banners_title' => $banners_title,
                                  'banners_url' => $banners_url,
                                  'banners_image' => $db_image_location,
                                  'banners_type' => $banners_type,
								  'banners_group' => $banners_group,
                                  'banners_html_text' => $banners_html_text,
								  'banner_sort_order' => $banner_sort_order,
								  'banner_language_code_name' => $banner_language_code_name,
								  'categories_ids' => $categories_ids
								  );

          if ($action == 'insert') {
            $insert_sql_data = array('date_added' => 'now()',
                                     'status' => '1');

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            tep_db_perform(TABLE_BANNERS, $sql_data_array);

            $banners_id = tep_db_insert_id();

            $messageStack->add_session(SUCCESS_BANNER_INSERTED, 'success');
          } elseif ($action == 'update') {
            tep_db_perform(TABLE_BANNERS, $sql_data_array, 'update', "banners_id = '" . (int)$banners_id . "'");

            $messageStack->add_session(SUCCESS_BANNER_UPDATED, 'success');
          }

          if (tep_not_null($expires_date)) {
            list($day, $month, $year) = explode('/', $expires_date);

            $expires_date = $year .
                            ((strlen($month) == 1) ? '0' . $month : $month) .
                            ((strlen($day) == 1) ? '0' . $day : $day);

            tep_db_query("update " . TABLE_BANNERS . " set expires_date = '" . tep_db_input($expires_date) . "', expires_impressions = null where banners_id = '" . (int)$banners_id . "'");
          } elseif (tep_not_null($expires_impressions)) {
            tep_db_query("update " . TABLE_BANNERS . " set expires_impressions = '" . tep_db_input($expires_impressions) . "', expires_date = null where banners_id = '" . (int)$banners_id . "'");
          }else{
            tep_db_query("update " . TABLE_BANNERS . " set expires_date = NULL, expires_impressions = null where banners_id = '" . (int)$banners_id . "'");
		  }

          if (tep_not_null($date_scheduled)) {
            list($day, $month, $year) = explode('/', $date_scheduled);

            $date_scheduled = $year .
                              ((strlen($month) == 1) ? '0' . $month : $month) .
                              ((strlen($day) == 1) ? '0' . $day : $day);

            tep_db_query("update " . TABLE_BANNERS . " set date_scheduled = '" . tep_db_input($date_scheduled) . "' where banners_id = '" . (int)$banners_id . "'");
          }else{
            tep_db_query("update " . TABLE_BANNERS . " set date_scheduled = NULL where banners_id = '" . (int)$banners_id . "'");
		  }

          tep_redirect(tep_href_link(FILENAME_BANNER_MANAGER, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . 'bID=' . $banners_id));
        } else {
          $action = 'new';
        }
        break;
      case 'deleteconfirm':
        $banners_id = tep_db_prepare_input($_GET['bID']);

        if (isset($_POST['delete_image']) && ($_POST['delete_image'] == 'on')) {
          $banner_query = tep_db_query("select banners_image from " . TABLE_BANNERS . " where banners_id = '" . (int)$banners_id . "'");
          $banner = tep_db_fetch_array($banner_query);

          if (is_file(DIR_FS_CATALOG_IMAGES . $banner['banners_image'])) {
            if (is_writeable(DIR_FS_CATALOG_IMAGES . $banner['banners_image'])) {
              unlink(DIR_FS_CATALOG_IMAGES . $banner['banners_image']);
            } else {
              $messageStack->add_session(ERROR_IMAGE_IS_NOT_WRITEABLE, 'error');
            }
          } else {
            $messageStack->add_session(ERROR_IMAGE_DOES_NOT_EXIST, 'error');
          }
        }

        tep_db_query("delete from " . TABLE_BANNERS . " where banners_id = '" . (int)$banners_id . "'");
        tep_db_query("delete from " . TABLE_BANNERS_HISTORY . " where banners_id = '" . (int)$banners_id . "'");

        if (function_exists('imagecreate') && tep_not_null($banner_extensio)) {
          if (is_file(DIR_WS_IMAGES . 'graphs/banner_infobox-' . $banners_id . '.' . $banner_extension)) {
            if (is_writeable(DIR_WS_IMAGES . 'graphs/banner_infobox-' . $banners_id . '.' . $banner_extension)) {
              unlink(DIR_WS_IMAGES . 'graphs/banner_infobox-' . $banners_id . '.' . $banner_extension);
            }
          }

          if (is_file(DIR_WS_IMAGES . 'graphs/banner_yearly-' . $banners_id . '.' . $banner_extension)) {
            if (is_writeable(DIR_WS_IMAGES . 'graphs/banner_yearly-' . $banners_id . '.' . $banner_extension)) {
              unlink(DIR_WS_IMAGES . 'graphs/banner_yearly-' . $banners_id . '.' . $banner_extension);
            }
          }

          if (is_file(DIR_WS_IMAGES . 'graphs/banner_monthly-' . $banners_id . '.' . $banner_extension)) {
            if (is_writeable(DIR_WS_IMAGES . 'graphs/banner_monthly-' . $banners_id . '.' . $banner_extension)) {
              unlink(DIR_WS_IMAGES . 'graphs/banner_monthly-' . $banners_id . '.' . $banner_extension);
            }
          }

          if (is_file(DIR_WS_IMAGES . 'graphs/banner_daily-' . $banners_id . '.' . $banner_extension)) {
            if (is_writeable(DIR_WS_IMAGES . 'graphs/banner_daily-' . $banners_id . '.' . $banner_extension)) {
              unlink(DIR_WS_IMAGES . 'graphs/banner_daily-' . $banners_id . '.' . $banner_extension);
            }
          }
        }

        $messageStack->add_session(SUCCESS_BANNER_REMOVED, 'success');

        tep_redirect(tep_href_link(FILENAME_BANNER_MANAGER, 'page=' . $_GET['page']));
        break;
    }
  }

// check if the graphs directory exists
  $dir_ok = false;
  if (function_exists('imagecreate') && tep_not_null($banner_extension)) {
    if (is_dir(DIR_WS_IMAGES . 'graphs')) {
      if (is_writeable(DIR_WS_IMAGES . 'graphs')) {
        $dir_ok = true;
      } else {
        $messageStack->add(ERROR_GRAPHS_DIRECTORY_NOT_WRITEABLE.':'.DIR_WS_IMAGES . 'graphs', 'error');
      }
    } else {
      $messageStack->add(ERROR_GRAPHS_DIRECTORY_DOES_NOT_EXIST.':'.DIR_WS_IMAGES . 'graphs', 'error');
    }
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script language="javascript"><!--
function popupImageWindow(url) {
  window.open(url,'popupImageWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<div id="spiffycalendar" class="text"></div>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('banner_manager');
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
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
<?php
  if ($action == 'new') {
    $form_action = 'insert';

    $parameters = array('expires_date' => '',
                        'date_scheduled' => '',
                        'banners_title' => '',
                        'banners_url' => '',
                        'banners_group' => '',
                        'banners_image' => '',
                        'banners_html_text' => '',
                        'expires_impressions' => '',
						'banner_sort_order' => '',
						'banner_language_code_name' => '',
						'banners_type' => '',
						'categories_ids' => '',
						);

    $bInfo = new objectInfo($parameters);
	
	
	$arr_languages_display_type = array();
	$arr_languages_display_type[] = array('id' => 'all','text' => 'All Languages Sites');
	$arr_languages_display_type[] = array('id' => 'tchinese','text' => 'Traditional Chinese Site');
	$arr_languages_display_type[] = array('id' => 'schinese','text' => 'Simple Chinese Site');

    if (isset($_GET['bID'])) {
      $form_action = 'update';

      $bID = tep_db_prepare_input($_GET['bID']);

      $banner_query = tep_db_query("select banners_title, banners_url, banners_image, banners_group, banners_html_text, status, date_format(date_scheduled, '%d/%m/%Y') as date_scheduled, date_format(expires_date, '%d/%m/%Y') as expires_date, expires_impressions, date_status_change, banner_sort_order, banner_language_code_name, banners_type, categories_ids from " . TABLE_BANNERS . " where banners_id = '" . (int)$bID . "'");
      $banner = tep_db_fetch_array($banner_query);

      $bInfo->objectInfo($banner);
    } elseif (tep_not_null($_POST)) {
      $bInfo->objectInfo($_POST);
    }

    $groups_array = array();
    $groups_query = tep_db_query("select distinct banners_group from " . TABLE_BANNERS . " order by banners_group");
    while ($groups = tep_db_fetch_array($groups_query)) {
      $groups_array[] = array('id' => $groups['banners_group'], 'text' => $groups['banners_group']);
    }
?>
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr><?php echo tep_draw_form('new_banner', FILENAME_BANNER_MANAGER, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . 'action=' . $form_action, 'post', 'enctype="multipart/form-data"'); if ($form_action == 'update') echo tep_draw_hidden_field('banners_id', $bID); ?>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo TEXT_BANNERS_TITLE; ?></td>
            <td class="main"><?php echo tep_draw_input_field('banners_title', $bInfo->banners_title, '', true); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_BANNERS_URL; ?></td>
            <td class="main"><?php echo tep_draw_input_field('banners_url', $bInfo->banners_url); ?></td>
          </tr>
          <tr>
            <td class="main">Banner Type:</td>
            <td class="main">
			<?php
			$type_array = array();
			$type_array[] = array('id'=>'Image', 'text'=>'Image');
			$type_array[] = array('id'=>'Flash', 'text'=>'Flash');
			$type_array[] = array('id'=>'Couplet', 'text'=>'Couplet');
			$type_array[] = array('id'=>'HtmlText', 'text'=>'HtmlText');
			echo tep_draw_pull_down_menu('banners_type', $type_array, $bInfo->banners_type, ' onChange="show2off(this.value)" ');
			?>
			<script type="text/javascript">
			function show2off(val){
				var BannersHtmlTextTr = document.getElementById('BannersHtmlTextTr');
				if(BannersHtmlTextTr==null){
					alert('BannersHtmlTextTr not exist');
				}else{
					if(val=='Couplet' || val=='HtmlText'){
						BannersHtmlTextTr.style.display = '';
					}else{
						BannersHtmlTextTr.style.display = 'none';
						//new_banner.banners_html_text.value = '';
					}
				}
			}
			</script>
			</td>
          </tr>
          
		  <tr>
            <td class="main" valign="top"><?php echo TEXT_BANNERS_GROUP; ?><a target="_blank" href="banner_manager_help.html" title="help">[?]</a></td>
            <td class="main">
			<?php echo tep_draw_pull_down_menu('banners_group', $groups_array, $bInfo->banners_group)?>
			<?php
			if($login_groups_id=='1' || $login_groups_id=='4'){
				echo TEXT_BANNERS_NEW_GROUP . '<br>' . tep_draw_input_field('new_banners_group', '', '', ((sizeof($groups_array) > 0) ? false : true));
			}
			?>
			
			</td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		  
		  <?php
		  //html text 输入框只有在对联或HtmlText类型才能出现
		  $BannersHtmlTextTrDisplay = 'none';
		  $BannerFileTrDisplay = '';
		  if($bInfo->banners_type=='HtmlText' || $bInfo->banners_type=='Couplet'){
			  $BannersHtmlTextTrDisplay = '';
			  $BannerFileTrDisplay = '';
		  }
		  ?>

          <tr style="display:<?=$BannersHtmlTextTrDisplay?>" id="BannersHtmlTextTr">
            <td valign="top" class="main"><?php echo TEXT_BANNERS_HTML_TEXT; ?></td>
            <td class="main"><?php echo tep_draw_textarea_field('banners_html_text', 'soft', '60', '5', $bInfo->banners_html_text); ?></td>
          </tr>
		  <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          
		  <tr style="display:<?=$BannerFileTrDisplay?>" id="BannerFileTr">
            <td class="main" valign="top">Banner File:</td>
            <td class="main"><?php echo tep_draw_file_field('banners_image') . ' ' . TEXT_BANNERS_IMAGE_LOCAL . '<br>' . DIR_FS_CATALOG_IMAGES . tep_draw_input_field('banners_image_local', (isset($bInfo->banners_image) ? $bInfo->banners_image : '')); ?></td>
          </tr>
		  <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <?php /* 
		  <tr>
            <td class="main"><?php echo TEXT_BANNERS_IMAGE_TARGET; ?></td>
            <td class="main"><?php echo DIR_FS_CATALOG_IMAGES . tep_draw_input_field('banners_image_target'); ?></td>
          </tr>
		  <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		  */?>
		   
		   <tr>
            <td class="main"><?php echo TEXT_BANNERS_SORT_ORDER; ?></td>
            <td class="main"><?php echo tep_draw_input_field('banner_sort_order', $bInfo->banner_sort_order); ?></td>
          </tr>
		  <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		   <tr>
            <td class="main"><?php echo TEXT_BANNERS_LANGUAGE_DISPLAY; ?></td>
            <td class="main"><?php 
			//echo tep_draw_input_field('banner_language_code_name', $bInfo->banner_language_code_name);
			echo tep_draw_pull_down_menu('banner_language_code_name',$arr_languages_display_type,$bInfo->banner_language_code_name);
			?></td>
          </tr>	  
		  <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		   <tr>
            <td class="main">Categories ids:</td>
            <td class="main">
			<?php 
			echo tep_draw_input_field('categories_ids', $bInfo->categories_ids);
			?>
			<?php echo db_to_html('主要用于Catalog List Top 650px或Box for Catalog 270px的广告位，如果输入了目录id号，则只有在这些目录下面才会显示该广告，多个目录请用,号分隔，如：25,33,68');?>
			</td>
          </tr>	  
		  <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		  
		  
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_BANNERS_SCHEDULED_AT; ?><br><small>(dd/mm/yyyy)</small></td>
            <td valign="top" class="main"><?php echo tep_draw_input_field('date_scheduled', tep_get_date_disp($bInfo->date_scheduled), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1);GeCalendar.OutPutType=\'d/m/y\'; GeCalendar.SetDate(this);"');?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td valign="top" class="main"><?php echo TEXT_BANNERS_EXPIRES_ON; ?><br><small>(dd/mm/yyyy)</small></td>
            <td class="main"><?php echo tep_draw_input_field('expires_date', $bInfo->expires_date, ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1);GeCalendar.OutPutType=\'d/m/y\'; GeCalendar.SetDate(this);"');?><?php /*echo TEXT_BANNERS_OR_AT . '<br>' . tep_draw_input_field('expires_impressions', $bInfo->expires_impressions, 'maxlength="7" size="7"') . ' ' . TEXT_BANNERS_IMPRESSIONS*/; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo TEXT_BANNERS_BANNER_NOTE . '<br>' . TEXT_BANNERS_INSERT_NOTE . '<br>' . TEXT_BANNERS_EXPIRCY_NOTE . '<br>' . TEXT_BANNERS_SCHEDULE_NOTE; ?></td>
            <td class="main" align="right" valign="top" nowrap><?php echo (($form_action == 'insert') ? tep_image_submit('button_insert.gif', IMAGE_INSERT) : tep_image_submit('button_update.gif', IMAGE_UPDATE)). '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_BANNER_MANAGER, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . (isset($_GET['bID']) ? 'bID=' . $_GET['bID'] : '')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
          </tr>
        </table></td>
      </form></tr>
<?php
  } else {
  
  
?>
      <tr>
        <td>
		<fieldset>
		<legend align="left"> Search </legend>
		<form name="SearchForm" method="get" action="">
		<table width="400" border="0" cellpadding="0" cellspacing="0">
		  <tr>
			<td height="25" align="right" class="main">Banners Key:&nbsp;</td>
			<td align="left" class="main"><?php echo tep_draw_input_field('keywords')?>&nbsp;</td>
		    <td align="right" class="main">Type:&nbsp;</td>
		    <td align="left" class="main">
			<?php
			$type_array = array();
			$type_array[] = array('id' => '', 'text' => db_to_html('不限'));
			$type_query = tep_db_query("select distinct banners_type from " . TABLE_BANNERS . " order by banners_type");
			while ($type = tep_db_fetch_array($type_query)) {
			  $type_array[] = array('id' => $type['banners_type'], 'text' => $type['banners_type']);
			}
			echo tep_draw_pull_down_menu('banners_type', $type_array);
			?>
			</td>
		  </tr>
		  <tr>
			<td height="25" align="right" class="main">Groups:&nbsp;</td>
			<td align="left" class="main">
			<?php
			$groups_array = array();
			$groups_array[] = array('id' => '', 'text' => db_to_html('不限'));
			$groups_query = tep_db_query("select distinct banners_group from " . TABLE_BANNERS . " order by banners_group");
			while ($groups = tep_db_fetch_array($groups_query)) {
			  $groups_array[] = array('id' => $groups['banners_group'], 'text' => $groups['banners_group']);
			}
			echo tep_draw_pull_down_menu('banners_group', $groups_array);
			?>
			</td>
		    <td align="right" class="main">Site:&nbsp;</td>
		    <td align="left" class="main">
			<?php
			$site_array = array();
			$site_array[] = array('id' => '', 'text' => db_to_html('不限'));
			$site_query = tep_db_query("select distinct banner_language_code_name from " . TABLE_BANNERS . " order by banner_language_code_name");
			while ($site = tep_db_fetch_array($site_query)) {
			  $site_array[] = array('id' => $site['banner_language_code_name'], 'text' => $site['banner_language_code_name']);
			}
			echo tep_draw_pull_down_menu('banner_language_code_name', $site_array);
			?>
			</td>
		  </tr>
		  <tr>
		    <td height="25" align="right"><input type="submit" name="Submit" value="Search"></td>
		    <td>&nbsp;</td>
		    <td>&nbsp;</td>
		    <td>&nbsp;</td>
		  </tr>
		</table>
        </form>
		</fieldset>

		</td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" nowrap="nowrap"><?php echo TABLE_HEADING_BANNERS; ?>
				<a href="<?php echo tep_href_link('banner_manager.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=banners_title;DESC');?>" title="down"><img src="images/arrow_down.gif" border="0"></a>
				<a href="<?php echo tep_href_link('banner_manager.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=banners_title;ASC');?>" title="up"><img border="0" src="images/arrow_up.gif"></a>
				</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">Type</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">Categories ids</td>
                <td class="dataTableHeadingContent" nowrap="nowrap"><?php echo TABLE_HEADING_GROUPS; ?>
				<a href="<?php echo tep_href_link('banner_manager.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=banners_group;DESC');?>" title="down"><img src="images/arrow_down.gif" border="0"></a>
				<a href="<?php echo tep_href_link('banner_manager.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=banners_group;ASC');?>" title="up"><img border="0" src="images/arrow_up.gif"></a>
				<a target="_blank" href="banner_manager_help.html" title="help">[?]</a>
				</td>
                <td class="dataTableHeadingContent" nowrap="nowrap"><?php echo TABLE_HEADING_STATISTICS; ?>
				<a href="<?php echo tep_href_link('banner_manager.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=banners_shown;DESC');?>" title="down"><img src="images/arrow_down.gif" border="0"></a>
				<a href="<?php echo tep_href_link('banner_manager.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=banners_shown;ASC');?>" title="up"><img border="0" src="images/arrow_up.gif"></a>
				<?php echo TABLE_HEADING_STATISTICS1; ?>
				<a href="<?php echo tep_href_link('banner_manager.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=banners_clicked;DESC');?>" title="down"><img src="images/arrow_down.gif" border="0"></a>
				<a href="<?php echo tep_href_link('banner_manager.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=banners_clicked;ASC');?>" title="up"><img border="0" src="images/arrow_up.gif"></a>
				</td>
				<td class="dataTableHeadingContent" align="right" nowrap="nowrap"><?php echo TABLE_HEADING_ORDERS_NUM; ?>/<?php echo TABLE_HEADING_ORDERS_SUM; ?></td>
                <td class="dataTableHeadingContent" align="right" nowrap="nowrap"><?php echo TABLE_HEADING_SORT_ORDER; ?>
				<a href="<?php echo tep_href_link('banner_manager.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=banner_sort_order;DESC');?>" title="down"><img src="images/arrow_down.gif" border="0"></a>
				<a href="<?php echo tep_href_link('banner_manager.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=banner_sort_order;ASC');?>" title="up"><img border="0" src="images/arrow_up.gif"></a>
				</td>
				<td class="dataTableHeadingContent" nowrap="nowrap"><?php echo TABLE_HEADING_LANGUAGE_DISPLAY_SITE; ?>
				<a href="<?php echo tep_href_link('banner_manager.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=banner_language_code_name;DESC');?>" title="down"><img src="images/arrow_down.gif" border="0"></a>
				<a href="<?php echo tep_href_link('banner_manager.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=banner_language_code_name;ASC');?>" title="up"><img border="0" src="images/arrow_up.gif"></a>
				</td>
				<td class="dataTableHeadingContent" align="right" nowrap="nowrap">Scheduled At
				<a href="<?php echo tep_href_link('banner_manager.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=date_scheduled;DESC');?>" title="down"><img src="images/arrow_down.gif" border="0"></a>
				<a href="<?php echo tep_href_link('banner_manager.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=date_scheduled;ASC');?>" title="up"><img border="0" src="images/arrow_up.gif"></a>
				</td>				
				<td class="dataTableHeadingContent" align="right" nowrap="nowrap">Expires On
				<a href="<?php echo tep_href_link('banner_manager.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=expires_date;DESC');?>" title="down"><img src="images/arrow_down.gif" border="0"></a>
				<a href="<?php echo tep_href_link('banner_manager.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=expires_date;ASC');?>" title="up"><img border="0" src="images/arrow_up.gif"></a>
				</td>				
				<td class="dataTableHeadingContent" align="right" nowrap="nowrap"><?php echo TABLE_HEADING_STATUS; ?>
				<a href="<?php echo tep_href_link('banner_manager.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=status;DESC');?>" title="down"><img src="images/arrow_down.gif" border="0"></a>
				<a href="<?php echo tep_href_link('banner_manager.php',tep_get_all_get_params(array('page','y','x', 'action', 'sort')).'sort=status;ASC');?>" title="up"><img border="0" src="images/arrow_up.gif"></a>
				</td>				
                <td class="dataTableHeadingContent" align="right" nowrap="nowrap"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $order_by = ' banner_sort_order ASC '; 
	if(tep_not_null($_GET['sort'])){
		$order_sort_field = preg_replace('/;.+$/','',$_GET['sort']);
		$order_sort_az = preg_replace('/^.+;/','',$_GET['sort']);
		$order_by = ' '.$order_sort_field.' '.$order_sort_az;
	}
	
	$where = ' WHERE 1 ';
	if(tep_not_null($_GET['keywords']) ){
		$where .= ' AND banners_title Like "%'.tep_db_prepare_input($_GET['keywords']).'%" ';
	}
	if(tep_not_null($_GET['banners_type'])){
		$where .= ' AND banners_type = "'.tep_db_prepare_input($_GET['banners_type']).'" ';
	}
	if(tep_not_null($_GET['banners_group'])){
		$where .= ' AND banners_group = "'.tep_db_prepare_input($_GET['banners_group']).'" ';
	}
	if(tep_not_null($_GET['banner_language_code_name'])){
		$where .= ' AND banner_language_code_name = "'.tep_db_prepare_input($_GET['banner_language_code_name']).'" ';
	}
	if(tep_not_null($_GET['bID'])){
		$where .= ' AND banners_id = "'.tep_db_prepare_input($_GET['bID']).'" ';
	}
	
	$banners_query_raw = "select banners_id, banners_title, banners_image, banners_type, banners_group, status, expires_date, expires_impressions, date_status_change, date_scheduled, date_added, banner_sort_order, banner_language_code_name , categories_ids,
	sum(banners_shown) as banners_shown, sum(banners_clicked) as banners_clicked 
	from " . TABLE_BANNERS . " left join ".TABLE_BANNERS_HISTORY." using(banners_id) ".$where." Group By banners_id order by ".$order_by;  //order by banners_title, banners_group
    $banners_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $banners_query_raw, $banners_query_numrows);
    $banners_query = tep_db_query($banners_query_raw);
    while ($banners = tep_db_fetch_array($banners_query)) {
      //$info_query = tep_db_query("select sum(banners_shown) as banners_shown, sum(banners_clicked) as banners_clicked from " . TABLE_BANNERS_HISTORY . " where banners_id = '" . (int)$banners['banners_id'] . "'");
      //$info = tep_db_fetch_array($info_query);
		$info = array();
      if ((!isset($_GET['bID']) || (isset($_GET['bID']) && ($_GET['bID'] == $banners['banners_id']))) && !isset($bInfo) && (substr($action, 0, 3) != 'new')) {
        $bInfo_array = array_merge($banners, $info);
        $bInfo = new objectInfo($bInfo_array);
      }

      $banners_shown = ($banners['banners_shown'] != '') ? $banners['banners_shown'] : '0';
      $banners_clicked = ($banners['banners_clicked'] != '') ? $banners['banners_clicked'] : '0';

      if (isset($bInfo) && is_object($bInfo) && ($banners['banners_id'] == $bInfo->banners_id)) {
        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_BANNER_STATISTICS, 'page=' . $_GET['page'] . '&bID=' . $bInfo->banners_id) . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_BANNER_MANAGER, 'page=' . $_GET['page'] . '&bID=' . $banners['banners_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent" nowrap="nowrap"><?php echo '<a href="javascript:popupImageWindow(\'' . FILENAME_POPUP_IMAGE . '?banner=' . $banners['banners_id'] . '\')">' . tep_image(DIR_WS_IMAGES . 'icon_popup.gif', 'View Banner') . '</a>&nbsp;' . $banners['banners_title']; ?></td>
                <td class="dataTableContent" nowrap="nowrap"><?php echo $banners['banners_type']; ?></td>
                <td class="dataTableContent" nowrap="nowrap"><?php echo $banners['categories_ids']; ?></td>
                <td class="dataTableContent" nowrap="nowrap"><?php echo $banners['banners_group']; ?></td>
                <td class="dataTableContent"  align="center"><?php echo $banners_shown . ' / ' . $banners_clicked; ?></td>
                <td class="dataTableContent"  align="right" nowrap>
				<?php
				//取得订单数和总金额
				$orders_num_str = 'SELECT count(*) as orders_num, sum(ot.value) as orders_sum FROM `orders` o, `ad_source_clicks_stores` ad,  `orders_total` ot WHERE ad.clicks_id=o.customers_ad_click_id AND ad.customers_advertiser=o.customers_advertiser AND o.customers_advertiser="SiteInnerAds" AND o.orders_id=ot.orders_id AND o.orders_status!="6" AND ot.class ="ot_total" AND ad.utm_term ="'.$banners['banners_id'].'" '; //Group By ad.utm_term
				$orders_num_sql = tep_db_query($orders_num_str);
				$orders_num_row = tep_db_fetch_array($orders_num_sql);
				if((int)$orders_num_row['orders_num']){
					echo $orders_num_row['orders_num'] . ' / ' . $currencies->format($orders_num_row['orders_sum']);
				}
				?>
				</td>
				<td class="dataTableContent" align="center"><?php echo $banners['banner_sort_order']; ?></td>	
				<td class="dataTableContent" ><?php echo ucfirst($banners['banner_language_code_name']); ?></td>				
				<td class="dataTableContent" ><?php echo str_replace(' 00:00:00','',$banners['date_scheduled']); ?></td>				
				<td class="dataTableContent" ><?php echo str_replace(' 00:00:00','',$banners['expires_date']); ?></td>				
                <td class="dataTableContent" align="right">
<?php
      if ($banners['status'] == '1') {
        echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', 'Active', 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_BANNER_MANAGER, 'page=' . $_GET['page'] . '&bID=' . $banners['banners_id'] . '&action=setflag&flag=0') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', 'Set Inactive', 10, 10) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_BANNER_MANAGER, 'page=' . $_GET['page'] . '&bID=' . $banners['banners_id'] . '&action=setflag&flag=1') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', 'Set Active', 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', 'Inactive', 10, 10);
      }
?></td>
                <td class="dataTableContent" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_BANNER_STATISTICS, 'page=' . $_GET['page'] . '&bID=' . $banners['banners_id']) . '">' . tep_image(DIR_WS_ICONS . 'statistics.gif', ICON_STATISTICS) . '</a>&nbsp;'; if (isset($bInfo) && is_object($bInfo) && ($banners['banners_id'] == $bInfo->banners_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_BANNER_MANAGER, 'page=' . $_GET['page'] . '&bID=' . $banners['banners_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }
?>
              <tr>
                <td colspan="12"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $banners_split->display_count($banners_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_BANNERS); ?></td>
                    <td class="smallText" align="right"><?php echo $banners_split->display_links($banners_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
                  <tr>
                    <td align="right" colspan="2"><?php echo '<a href="' . tep_href_link(FILENAME_BANNER_MANAGER, 'action=new') . '">' . tep_image_button('button_new_banner.gif', IMAGE_NEW_BANNER) . '</a>'; ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();
  switch ($action) {
    case 'delete':
      $heading[] = array('text' => '<b>' . $bInfo->banners_title . '</b>');

      $contents = array('form' => tep_draw_form('banners', FILENAME_BANNER_MANAGER, 'page=' . $_GET['page'] . '&bID=' . $bInfo->banners_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $bInfo->banners_title . '</b>');
      if ($bInfo->banners_image) $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('delete_image', 'on', true) . ' ' . TEXT_INFO_DELETE_IMAGE);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . '&nbsp;<a href="' . tep_href_link(FILENAME_BANNER_MANAGER, 'page=' . $_GET['page'] . '&bID=' . $_GET['bID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (is_object($bInfo)) {
        $heading[] = array('text' => '<b>' . $bInfo->banners_title . '</b>');

        $contents[] = array('align' => 'left', 'text' => '<a href="' . tep_href_link(FILENAME_BANNER_MANAGER, 'page=' . $_GET['page'] . '&bID=' . $bInfo->banners_id . '&action=new') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_BANNER_MANAGER, 'page=' . $_GET['page'] . '&bID=' . $bInfo->banners_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_BANNERS_DATE_ADDED . ' ' . tep_date_short($bInfo->date_added));

        if ( (function_exists('imagecreate')) && ($dir_ok) && ($banner_extension) ) {
          $banner_id = $bInfo->banners_id;
          $days = '3';
          include(DIR_WS_INCLUDES . 'graphs/banner_infobox.php');
          $contents[] = array('align' => 'left', 'text' => '<br>' . tep_image(DIR_WS_IMAGES . 'graphs/banner_infobox-' . $banner_id . '.' . $banner_extension));
        } else {
          include(DIR_WS_FUNCTIONS . 'html_graphs.php');
          $contents[] = array('align' => 'left', 'text' => '<br>' . tep_banner_graph_infoBox($bInfo->banners_id, '3'));
        }

        $contents[] = array('text' => tep_image(DIR_WS_IMAGES . 'graph_hbar_blue.gif', 'Blue', '5', '5') . ' ' . TEXT_BANNERS_BANNER_VIEWS . '<br>' . tep_image(DIR_WS_IMAGES . 'graph_hbar_red.gif', 'Red', '5', '5') . ' ' . TEXT_BANNERS_BANNER_CLICKS);

        if ($bInfo->date_scheduled) $contents[] = array('text' => '<br>' . sprintf(TEXT_BANNERS_SCHEDULED_AT_DATE, tep_date_short($bInfo->date_scheduled)));

        if ($bInfo->expires_date) {
          $contents[] = array('text' => '<br>' . sprintf(TEXT_BANNERS_EXPIRES_AT_DATE, tep_date_short($bInfo->expires_date)));
        } elseif ($bInfo->expires_impressions) {
          $contents[] = array('text' => '<br>' . sprintf(TEXT_BANNERS_EXPIRES_AT_IMPRESSIONS, $bInfo->expires_impressions));
        }

        if ($bInfo->date_status_change) $contents[] = array('text' => '<br>' . sprintf(TEXT_BANNERS_STATUS_CHANGE, tep_date_short($bInfo->date_status_change)));
		
		if(tep_not_null($bInfo->banners_image)){
			$banner_image = HTTP_SERVER.'/images/'.$bInfo->banners_image;
			$contents[] = array('text' => tep_image($banner_image));
		}
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
