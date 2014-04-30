<?php
/*
  $Id: languages.php,v 1.1.1.1 2004/03/04 23:38:39 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

require('includes/application_top.php');
// 备注添加删除
if($_GET['ajax']=="true"){
	include DIR_FS_CLASSES . 'Remark.class.php';
	$remark = new Remark('hotel');
	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
}

require(DIR_FS_DOCUMENT_ROOT.'includes/functions/hotels_functions.php');

$action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

if (tep_not_null($action) && $hotel_permissions['能看不能编'] != true ) {
	switch ($action) {
        case 'setstate':
            break;

        case 'insert':
            $hotel_name = tep_db_prepare_input($HTTP_POST_VARS['hotel_name']);
            $hotel_description = tep_db_prepare_input($HTTP_POST_VARS['hotel_description']);
            $hotel_stars = (int) ($HTTP_POST_VARS['hotel_stars']);
            $hotel_address = ($HTTP_POST_VARS['hotel_address']);
            $hotel_phone = ($HTTP_POST_VARS['hotel_phone']);
            $hotel_fax = ($HTTP_POST_VARS['hotel_fax']);
            $hotel_url_name = ($HTTP_POST_VARS['hotel_url_name']);
            $hotel_map = ($HTTP_POST_VARS['hotel_map']);
            $hotel_pic_alt = tep_db_prepare_input($HTTP_POST_VARS['hotel_pic_alt']);
            $related_hotel = trim($HTTP_POST_VARS['related_hotel']);
            $products_id = trim($HTTP_POST_VARS['products_id']);
			$meals_id = (int) $_POST['meals_id'];
			$internet_id = (int) $_POST['internet_id'];
			$approximate_location_id = (int) $_POST['approximate_location_id'];
            $add_date = date('Y-m-d H:i:s');


            if (tep_not_null($hotel_name)) {
                $sql_data_array = array('hotel_name' => $hotel_name,
                    'hotel_name_length' => strlen($hotel_name),
                    'hotel_description' => $hotel_description,
                    'hotel_stars' => $hotel_stars,
                    'hotel_address' => $hotel_address,
                    'hotel_phone' => $hotel_phone,
                    'hotel_fax' => $hotel_fax,
                    'hotel_url_name' => $hotel_url_name,
                    'hotel_map' => $hotel_map,
                    'related_hotel' => $related_hotel,
                    'related_high_hotel' => $related_high_hotel,
                    'add_date' => $add_date,
                    'up_date' => $add_date,
					'products_id'=>$products_id,
					'meals_id'=>$meals_id,
					'internet_id'=>$internet_id,
					'approximate_location_id'=>$approximate_location_id
                );
                tep_db_perform('hotel', $sql_data_array);
                $hotel_id = tep_db_insert_id();
                //写入供应商资料
                foreach ((array) $_POST['agency_box'] as $key => $value) {
                    if (tep_not_null($value)) {
                        $sql_data_array = array('hotel_id' => $hotel_id,
                            'agency_id' => $value
                        );
                        tep_db_perform('hotel_to_travel_agency', $sql_data_array);
                    }
                }
                //添加酒店图片
                $new_file_name = $_POST['hotel_pic_url'];

                if (tep_not_null($_FILES['file_image']['name'])) {
                    $spildname = strtolower(preg_replace("/^(.*\.)/", "", $_FILES['file_image']['name']));
                    $new_file_name = $login_id . '_' . date('YmdHis') . '.' . $spildname;
                    if (!move_uploaded_file($_FILES['file_image']['tmp_name'], DIR_FS_CATALOG_IMAGES . 'hotel/' . $new_file_name)) {
                        $new_file_name = '';
                    }
                }
                if (tep_not_null($new_file_name)) {
                    $sql_data_array = array('hotel_id' => $hotel_id,
                        'hotel_pic_alt' => $hotel_pic_alt,
                        'hotel_pic_url' => $new_file_name);
                    tep_db_perform('hotel_pic', $sql_data_array);
                }

                //优化表
                tep_db_query('OPTIMIZE TABLE `hotel` , `hotel_pic` , `hotel_to_travel_agency` ');

                $messageStack->add_session('酒店添加成功！', 'success');
                tep_redirect(tep_href_link('hotel.php'));
            }


            break;
        case 'save':
            $hotel_name = tep_db_prepare_input($HTTP_POST_VARS['hotel_name']);
            $hotel_description = tep_db_prepare_input($HTTP_POST_VARS['hotel_description']);
            $hotel_map = ($HTTP_POST_VARS['hotel_map']);
            $up_date = date('Y-m-d H:i:s');
            $hotel_id = (int) $_POST['hotel_id'];
            $hotel_stars = (int) $_POST['hotel_stars'];
            $related_hotel = trim($HTTP_POST_VARS['related_hotel']);
            $related_high_hotel = trim($HTTP_POST_VARS['related_high_hotel']);
			$products_id = trim($HTTP_POST_VARS['products_id']);
			$meals_id = (int) $_POST['meals_id'];
			$internet_id = (int) $_POST['internet_id'];
			$approximate_location_id = (int) $_POST['approximate_location_id'];
            
			if (tep_not_null($hotel_name)) {
                $sql_data_array = array('hotel_name' => $hotel_name,
                    'hotel_name_length' => strlen($hotel_name),
                    'hotel_description' => $hotel_description,
                    'up_date' => $up_date,
                    'related_hotel' => $related_hotel,
                    'related_high_hotel' => $related_high_hotel,
                    'hotel_stars' => $hotel_stars,
                    'hotel_address' => $hotel_address,
                    'hotel_phone' => $hotel_phone,
                    'hotel_fax' => $hotel_fax,
                    'hotel_url_name' => $hotel_url_name,
                    'hotel_map' => $hotel_map,
					'products_id'=>$products_id,
					'meals_id'=>$meals_id,
					'internet_id'=>$internet_id,
					'approximate_location_id'=>$approximate_location_id
                );
                tep_db_perform('hotel', $sql_data_array, 'update', ' hotel_id = "' . (int) $_POST['hotel_id'] . '"');

                $hotel_id = (int) $_POST['hotel_id'];


                //更新供应商资料
                tep_db_query('DELETE FROM `hotel_to_travel_agency` WHERE `hotel_id` = "' . $hotel_id . '" ');
                foreach ((array) $_POST['agency_box'] as $key => $value) {
                    if (tep_not_null($value)) {
                        $sql_data_array = array('hotel_id' => $hotel_id,
                            'agency_id' => $value
                        );
                        tep_db_perform('hotel_to_travel_agency', $sql_data_array);
                    }
                }

                //更新(删除、添加)酒店图片
                //删除图片
                //print_r($_POST['del_box']);
                //exit;
                foreach ((array) $_POST['del_box'] as $key => $value) {
                    if ((int) $value) {
                        $sql = tep_db_query('SELECT hotel_pic_id, hotel_pic_url FROM `hotel_pic` WHERE `hotel_pic_id` = "' . (int) $value . '" LIMIT 1');
                        $row = tep_db_fetch_array($sql);
                        if (!preg_match('/^http:\/\//', $row['hotel_pic_url'])) {
                            @unlink(DIR_WS_CATALOG_IMAGES . 'hotel/' . $row['hotel_pic_url']);
                        }
                        tep_db_query('DELETE FROM `hotel_pic` WHERE `hotel_pic_id` = "' . (int) $value . '" LIMIT 1');
                    }
                }
                //更新图片alt和排序信息
                foreach ((array) $_POST['hotel_pic_id'] as $key => $value) {
                    $hotel_pic_alt = tep_db_prepare_input($_POST['hotel_pic_alt'][$key]);
                    $hotel_pic_sort = (int) $_POST['hotel_pic_sort'][$key];
                    $hotel_pic_url = $_POST['hotel_pic_url_array'][$key];
                    $sql_data_array = array('hotel_pic_alt' => $hotel_pic_alt,
                        'hotel_pic_sort' => $hotel_pic_sort,
                        'hotel_pic_url' => $hotel_pic_url
                    );
                    tep_db_perform('hotel_pic', $sql_data_array, 'update', 'hotel_pic_id = "' . (int) $_POST['hotel_pic_id'][$key] . '"');
                }

                //添加图片
                $pic_num_tmp = 0;

                foreach ((array) $_POST['js_hotel_pic_url_array'] as $key => $value) {
                    $new_file_name = $value;
                    $hotel_pic_alt = $_POST['hotel_pic_alt_array'][$key];
                    $hotel_pic_sort = (int) $_POST['hotel_pic_sort_array'][$key];

                    if (tep_not_null($_FILES['file_image_array']['name'][$key])) {
                        $pic_num_tmp++;

                        $spildname = strtolower(preg_replace("/^(.*\.)/", "", $_FILES['file_image_array']['name'][$key]));
                        $new_file_name = $login_id . '_' . date('YmdHis') . '_' . $pic_num_tmp . '.' . $spildname;

                        if (!move_uploaded_file($_FILES['file_image_array']['tmp_name'][$key], DIR_FS_CATALOG_IMAGES . 'hotel/' . $new_file_name)) {
                            $new_file_name = '';
                        }
                    }

                    if (tep_not_null($new_file_name)) {
                        $sql_data_array = array('hotel_id' => $hotel_id,
                            'hotel_pic_alt' => $hotel_pic_alt,
                            'hotel_pic_url' => $new_file_name,
                            'hotel_pic_sort' => $hotel_pic_sort
                        );
                        tep_db_perform('hotel_pic', $sql_data_array);
                    }
                }
            }
            //优化表
            tep_db_query('OPTIMIZE TABLE `hotel` , `hotel_pic` , `hotel_to_travel_agency` ');

            $messageStack->add_session('酒店更新成功！', 'success');
            tep_redirect(tep_href_link('hotel.php'));

            break;
        case 'deleteconfirm':
            if ((int) $_GET['hotel_id']) {
                //1删除酒店图片
                $hotel_sql = tep_db_query('SELECT hotel_pic_id, hotel_pic_url FROM `hotel_pic` WHERE `hotel_id` = "' . (int) $_GET['hotel_id'] . '" ');
                while ($hotel_rows = tep_db_fetch_array($hotel_sql)) {
                    $hotel_pic_url = $hotel_rows['hotel_pic_url'];
                    if (!preg_match('/^http:\/\//', $hotel_pic_url) && tep_not_null($hotel_rows['hotel_pic_url'])) {
                        $hotel_pic_url = DIR_FS_CATALOG_IMAGES . 'hotel/' . $hotel_pic_url;
                        @unlink($hotel_pic_url);
                    }
                    tep_db_query('DELETE FROM `hotel_pic` WHERE `hotel_pic_id` = "' . (int) $hotel_rows['hotel_pic_id'] . '" ');
                }
                //2删除酒店对应供应商id
                tep_db_query('DELETE FROM `hotel_to_travel_agency` WHERE `hotel_id` = "' . (int) $_GET['hotel_id'] . '" ');
                //3删除酒店信息
                tep_db_query('DELETE FROM `hotel` WHERE `hotel_id` = "' . (int) $_GET['hotel_id'] . '" ');
                //优化表
                tep_db_query('OPTIMIZE TABLE `hotel` , `hotel_pic` , `hotel_to_travel_agency` ');

                $messageStack->add_session('酒店删除成功！', 'success');
                tep_redirect(tep_href_link('hotel.php'));
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
        <script type="text/javascript" src="includes/menu.js"></script>
        <script type="text/javascript" src="includes/big5_gb-min.js"></script>
        <script type="text/javascript" src="includes/general.js"></script>
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
                window.alert("<?php echo db_to_html('不能创建XMLHttpRequest对象实例.') ?>");
            }
        </script>

<?php
$p = array('/&amp;/', '/&quot;/');
$r = array('&', '"');
?>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
    </head>
    <body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();<?php echo $edit_load; ?>">
        <!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
        <!-- header_eof //-->

        <!-- body //-->
        
		<?php
		//echo $login_id;
		include DIR_FS_CLASSES . 'Remark.class.php';
		$listrs = new Remark('hotel');
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
                <td width="100%" valign="top">
                    <fieldset>
                        <legend align="left"> Search Module </legend>
<?php echo tep_draw_form('form_search', 'hotel.php', tep_get_all_get_params(array('page', 'y', 'x', 'action')), 'get'); ?>

                        <table border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td align="right" nowrap class="main">酒店名称：</td>
                                <td align="left" class="main"><?php echo tep_draw_input_field('search_hotel_name'); ?> <?php echo tep_draw_checkbox_field('match', '1'); ?>完全匹配&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td align="left" class="main">&nbsp;</td>
                                <td align="left" nowrap class="main">星级：</td>
                                <td align="left" class="main">
<?php
        $search_hotel_stars_options = array();
        $search_hotel_stars_options[] = array('id' => '0', 'text' => '不限');
        $search_hotel_stars_options[] = array('id' => '1', 'text' => '1星');
        $search_hotel_stars_options[] = array('id' => '2', 'text' => '经济型');
        $search_hotel_stars_options[] = array('id' => '3', 'text' => '3星');
        $search_hotel_stars_options[] = array('id' => '4', 'text' => '4星');
        $search_hotel_stars_options[] = array('id' => '5', 'text' => '5星');
        $search_hotel_stars_options[] = array('id' => '6', 'text' => '6星');
        echo tep_draw_pull_down_menu('search_hotel_stars', $search_hotel_stars_options);
?>
                                </td>
                                <td align="left" class="main">&nbsp;</td>
                                <td align="left" nowrap class="main">供应商：</td>
                                <td align="left" class="main">
<?php
        $agencys_sql = tep_db_query('SELECT tta.agency_id, tta.agency_name FROM `tour_travel_agency` tta, `hotel_to_travel_agency` htta WHERE htta.agency_id=tta.agency_id GROUP BY tta.agency_id ORDER BY tta.agency_name ');
        $search_agency_options = array();
        $search_agency_options[] = array('id' => 0, 'text' => '不限');
        while ($agencys = tep_db_fetch_array($agencys_sql)) {
            $search_agency_options[] = array('id' => $agencys['agency_id'], 'text' => $agencys['agency_name']);
        }
        echo tep_draw_pull_down_menu('search_agency', $search_agency_options);
?>
                                </td>
                                <td class="main"><input name="search" type="hidden" value="1"><button type="submit">搜索</button></td>
                            </tr>
                        </table>

<?php echo '</form>'; ?>
                    </fieldset>

                    <table border="0" width="100%" cellspacing="0" cellpadding="2">
                        <tr>
                            <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td class="pageHeading">酒店管理</td>
                                        <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                                    </tr>
                                </table></td>
                        </tr>
                        <tr>
                            <td>
		<?php
		if($hotel_permissions['能看不能编'] == true){
			ob_start();
		}
		?>
							<table id="TableList" border="0" width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td valign="top">
										
										<table border="0" width="100%" cellspacing="0" cellpadding="2">
                                                <tr class="dataTableHeadingRow">
                                                    <td class="dataTableHeadingContent">酒店ID</td>
                                                    <td class="dataTableHeadingContent">酒店名称</td>
                                                    <td class="dataTableHeadingContent">供应商</td>
                                                    <td class="dataTableHeadingContent">星级</td>
                                                    <td class="dataTableHeadingContent">添加日期</td>
                                                    <td class="dataTableHeadingContent">修改日期</td>
                                                    <td class="dataTableHeadingContent">地图</td>
                                                    <td class="dataTableHeadingContent">酒店图片</td>
                                                    <td class="dataTableHeadingContent">评论</td>
                                                    <td class="dataTableHeadingContent" align="right">Action</td>
                                                </tr>
<?php
        $select = ' * ';
        $table = ' hotel h ';
        $where = ' 1 ';
        $order_by = ' h.hotel_id DESC ';
        //搜索 start
		if ($_GET['search']=='1') {
			if(tep_not_null($_GET['search_hotel_name'])){
				if($_GET['match']=='1'){
					$where .= ' and h.hotel_name ="'.tep_db_prepare_input($_GET['search_hotel_name']).'" ';
				}else{
					$where .= ' and h.hotel_name Like "%'.tep_db_prepare_input($_GET['search_hotel_name']).'%" ';
				}
			} 
			if((int)$_GET['search_hotel_stars']){
				$where .= ' and h.hotel_stars ="'.(int)$_GET['search_hotel_stars'].'" ';
			}
			if((int)$_GET['search_agency']){
				$table .= ' , hotel_to_travel_agency htta ';
				$where .= ' and htta.agency_id ="'.(int)$_GET['search_agency'].'" and htta.hotel_id = h.hotel_id ';
			}
        }
		
		if(isset($_GET['search_one_hotel']) && $_GET['search_one_hotel'] == 'true'){
			$where .= ' and h.hotel_id ="'.tep_db_prepare_input($_GET['hotel_id']).'" ';
		}
		//搜索 end

        $hotel_query_raw = "select {$select} from {$table} where {$where} order by {$order_by} ";
        //echo $hotel_query_raw;
		$hotel_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $hotel_query_raw, $hotel_query_numrows);
        $hotel_query = tep_db_query($hotel_query_raw);

        while ($hotel = tep_db_fetch_array($hotel_query)) {
            if (isset($HTTP_GET_VARS['hotel_id']) && !isset($HInfo) && substr($action, 0, 3) != 'new' && $HTTP_GET_VARS['hotel_id'] == $hotel['hotel_id']) {
                $HInfo = new objectInfo($hotel);
            }

            if (isset($HInfo) && is_object($HInfo) && $HInfo->hotel_id == $hotel['hotel_id'] && $hotel['hotel_id'] == $HTTP_GET_VARS['hotel_id']) {
                echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link('hotel.php', 'page=' . $HTTP_GET_VARS['page'] . '&action=edit&hotel_id=' . $HInfo->hotel_id) . '\'">' . "\n";
            } else {
                echo '                  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link('hotel.php', 'page=' . $HTTP_GET_VARS['page'] . '&hotel_id=' . $hotel['hotel_id']) . '\'">' . "\n";
            }

//取得供应商

            $agency_sql = tep_db_query('SELECT tta.agency_id, tta.agency_name  FROM `hotel_to_travel_agency` htta, `tour_travel_agency` tta WHERE htta.hotel_id = "' . $hotel['hotel_id'] . '" AND htta.agency_id = tta.agency_id Group By htta.agency_id');
            $agency = '';
            while ($agency_row = tep_db_fetch_array($agency_sql)) {
                $agency .= '<a href="' . tep_href_link('travel_agency.php', 'adgrafics_information=Edit&agency_id=' . $agency_row['agency_id']) . '" target="_blank">' . tep_db_output($agency_row['agency_name']) . "</a><br>";
            }

//取得图片数量
            $pic_sql = tep_db_query('SELECT count(*) as total FROM `hotel_pic` WHERE hotel_id="' . $hotel['hotel_id'] . '" ');
            $pic_row = tep_db_fetch_array($pic_sql);
?>
                                                <td class="dataTableContent" align="left"><?php echo $hotel['hotel_id'] ?></td>
                                                <td class="dataTableContent" align="left"><?php echo $hotel['hotel_name'] ?></td>
                                                <td class="dataTableContent" align="left" nowrap="nowrap"><?php echo $agency ?></td>
                                                <td class="dataTableContent" align="left"><?php echo $hotel['hotel_stars'] ?></td>
                                                <td class="dataTableContent" align="left"><?php echo $hotel['add_date'] ?></td>
                                                <td class="dataTableContent" align="left"><?php echo $hotel['up_date'] ?></td>
                                                <td class="dataTableContent" align="left"><?php echo tep_not_null($hotel['hotel_map']) ? 'Yes' : 'No'; ?></td>
                                                <td class="dataTableContent" align="left"><?php echo (int) $pic_row['total']; ?></td>
                                                <td class="dataTableContent" align="left"><?php echo (int) $hotel['reviews_num'] ?></td>
                                                <td class="dataTableContent" align="right">
                                                    <a href="<?php echo tep_href_link('hotel.php', 'page=' . $HTTP_GET_VARS['page'] . '&hotel_id=' . $hotel['hotel_id'] . '&action=edit&'. tep_get_all_get_params(array('page','y','x', 'action', 'hotel_id'))) ?>" ><img border="0" title="编辑" alt="编辑" src="images/icons/edit.gif"/></a>
<?php if ($hotel['hotel_id'] == $_GET['hotel_id']) { ?>
                                                        &nbsp;<img border="0" alt="" src="images/icon_arrow_right.gif"/>    
<?php } else { ?>
                                                    &nbsp;<img border="0" title="Info" alt="Info" src="images/icon_info.gif"/>
<?php } ?>
                                                </td>
                                    </tr>
<?php
                                            }
?>
                                            <tr>        
                                                <td colspan="10"><table border="0" width="100%" cellspacing="0" cellpadding="2">        
                                                        <tr>        
                                                            <td class="smallText" valign="top"><?php echo $hotel_split->display_count($hotel_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_VOTE); ?></td>
                                                            <td class="smallText" align="right"><?php echo $hotel_split->display_links($hotel_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'],tep_get_all_get_params(array('page','y','x', 'action'))); ?></td>
                                                        </tr>        
<?php
                                            if (empty($action)) {
?>
                                                <tr>
                                                    <td align="right" colspan="2"><?php echo '<a href="' . tep_href_link('hotel.php', 'page=' . $HTTP_GET_VARS['page'] . '&action=new') . '">' . tep_image_button('button_new.gif', IMAGE_NEW_LANGUAGE) . '</a>'; ?></td>
                                                </tr>
<?php
                                            }
?>
                                            </table></td>
                                    </tr>
                                </table></td>

                        <script type="text/JavaScript">
                            function submit_check(){
                                var agency_box = document.getElementById("agency_box");
                                for(i=0; i<agency_box.length; i++){
                                    agency_box.options[i].selected = true;
                                }
	
                                document.getElementById("entries_form").submit();
                            }

                            function add_to_agency(){
                                var agency_str = document.getElementById("agency_str");
                                var agency_box = document.getElementById("agency_box");
                                var s = agency_box.length;
                                var ready_add_value = agency_str.value;
                                var ready_add_text = agency_str.options[agency_str.selectedIndex].text;
                                var add_action = true;
                                for(i=0; i<agency_box.length; i++){
                                    agency_box.options[i].selected = true;
                                    if(ready_add_value == agency_box.options[i].value){
                                        add_action = false; 
                                    }
                                }
                                if(add_action==true && ready_add_value>0){
                                    agency_box.options[s] = new Option(ready_add_text, ready_add_value);
                                    agency_box.options[s].selected = true;
                                }
                            }

                            function move_form_categories(){
                                var agency_box = document.getElementById("agency_box");
                                for(i=0; i<agency_box.length; i++){
                                    if( agency_box.options[i].selected ){
                                        agency_box.remove(i);
                                        break;
                                    }
                                }

                            }
                        </script>

                        <script type="text/javascript">
                            var pic_a_num = 0;
                            //增加节点
                            function add_new_pic(){
                                var oNews = document.getElementById("pic");
                                if(oNews!=null){
                                    var divIdName = 'pic_a_'+ pic_a_num;
                                    var newdiv = document.createElement('div');
                                    newdiv.setAttribute("id",divIdName);
                                    newdiv.innerHTML = '<table border="0" cellspacing="0" cellpadding="0"><tr><td width="200" height="30" align="left" class="infoBoxContent"><input name="file_image_array[]" type="file"></td><td width="180" align="right" class="infoBoxContent">Url:<input type="text" name="js_hotel_pic_url_array[]" size="20"> Alt:<input onBlur="this.value = traditionalized(this.value)" type="text" name="hotel_pic_alt_array[]" size="20"></td><td width="50" align="right" class="infoBoxContent"><input onBlur="this.value = traditionalized(this.value)" type="text" name="hotel_pic_sort_array[]" size="4"></td><td width="50" align="right" class="infoBoxContent"><a href="javascript:del_pic(\''+ divIdName +'\');">remove</a></td></tr></table>';

                                    oNews.appendChild(newdiv);
                                    pic_a_num++;
		
                                }
                            }
                            //删除节点
                            function del_pic(id){
                                var id = document.getElementById(id);
                                if(id!=null){
                                    var d = document.getElementById("pic");
                                    var del_idName = id;
                                    d.removeChild(del_idName);
                                }
                            }
                        </script>


<?php
                                            $heading = array();
                                            $contents = array();
//供应商选择器
                                            $agency_obj = new SelectMenusObj;
                                            $agency_sel_box = '<select name="agency_str" id="agency_str" style="color:#999999" size="5" ondblclick="add_to_agency()">' . $agency_obj->selected_menus('tour_travel_agency', 'agency_id', 'agency_name', max(1, $agency_id), 0, '', '') . '</select>';

                                            $agency_sql = tep_db_query('SELECT * FROM `hotel_to_travel_agency` htta, `tour_travel_agency` tta WHERE htta.hotel_id = "' . (int) $hotel_id . '" AND htta.agency_id = tta.agency_id Group By tta.agency_id ');
                                            $tmp_str = '';
                                            while ($agency_rows = tep_db_fetch_array($agency_sql)) {
                                                $tmp_str .= '<option value="' . $agency_rows['agency_id'] . '" selected="selected" >' . tep_db_output($agency_rows['agency_name']) . '</option>';
                                            }
                                            $agency_selected_box = '<select name="agency_box[]" size="5" multiple="multiple" id="agency_box">' . $tmp_str . '</select>';
                                            $html_str = '
  			<table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td valign="top" class="infoBoxContent">未选择</td>
                <td valign="top" class="infoBoxContent">&nbsp;</td>
                <td valign="top" class="infoBoxContent">已选择</td>
              </tr>
              <tr>
                <td valign="top" class="infoBoxContent">' . $agency_sel_box . '</td>
                <td valign="top" class="infoBoxContent">
				<input name="Submit" type="button" title="增加到分类" onclick="add_to_agency()" value=" &gt;&gt; " /><br /><br />
<input type="button" title="撤销分类" name="Submit" onclick="move_form_categories()" value=" &lt;&lt; " /></td>
                <td valign="top" class="infoBoxContent">' . $agency_selected_box . '</td>
              </tr>
            </table>
  ';

                                            switch ($action) {
                                                case 'new':
                                                    $heading[] = array('text' => '<b>添加新酒店</b>');

                                                    $contents = array('form' => tep_draw_form('hotel', 'hotel.php', 'action=insert', 'post', 'enctype="multipart/form-data" onSubmit="submit_check(); return false; "'));
                                                    $contents[] = array('text' => '请在下面创建新的酒店信息');
                                                    $contents[] = array('text' => '<br>酒店名称<br>' . tep_draw_input_field('hotel_name', '', 'size="34"', true));
													 $contents[] = array('text' => '<br>对应的酒店产品ID<br>' . tep_draw_input_field('products_id', '', 'size="12"', true));
                                                    $contents[] = array('text' => '<br>酒店星级<br>' . tep_draw_radio_field('hotel_stars', '0') . '无&nbsp;'
                                                        . tep_draw_radio_field('hotel_stars', '1') . '1星&nbsp;'
                                                        . tep_draw_radio_field('hotel_stars', '2') . '经济型&nbsp;'
                                                        . tep_draw_radio_field('hotel_stars', '3') . '3星&nbsp;'
                                                        . tep_draw_radio_field('hotel_stars', '4') . '4星&nbsp;'
                                                        . tep_draw_radio_field('hotel_stars', '5') . '5星&nbsp;'
                                                        . tep_draw_radio_field('hotel_stars', '6') . '6星&nbsp;');
													
													$contents[] = array('text' => '<br>餐食：' . tep_draw_pull_down_menu('meals_id',getHotelMealsOptions(),9));
													$contents[] = array('text' => '<br>网络：' . tep_draw_pull_down_menu('internet_id',getHotelInternetOptions(),9));
													$contents[] = array('text' => '<br>位置：' . tep_draw_pull_down_menu('approximate_location_id',getHotelApproximateLocation(),1));
													
													$contents[] = array('text' => '<br>电话<br>' . tep_draw_input_field('hotel_phone', '', 'size="24"'));
													$contents[] = array('text' => '<br>传真<br>' . tep_draw_input_field('hotel_fax', '', 'size="24"'));
                                                    $contents[] = array('text' => '<br>酒店地址<br>' . tep_draw_input_field('hotel_address', '', 'size="60"'));

                                                    $contents[] = array('text' => '<br>酒店简介<br>' . tep_draw_textarea_field('hotel_description', '10', '60', '12') . '<br>（支持html代码）');
                                                    $contents[] = array('text' => '<br>提供此酒店的供应商');
                                                    $contents[] = array('text' => $html_str);

                                                    $contents[] = array('text' => '<br>同行程其他酒店<br>' . tep_draw_input_field('related_hotel', '', 'size="42"'));
                                                    $contents[] = array('text' => '<br>同行程升级酒店<br>' . tep_draw_input_field('related_high_hotel', '', 'size="42"'));

                                                    $contents[] = array('text' => '<br>酒店URLname<br>' . tep_draw_input_field('hotel_url_name', '', 'size="42"'));
                                                    $contents[] = array('text' => '<br>酒店google地图<br>' . tep_draw_textarea_field('hotel_map', '10', '60', '12'));
                                                    $contents[] = array('text' => '<br>酒店图片<br><input name="file_image" type="file" id="file_image" size="34" />');
                                                    $contents[] = array('text' => 'Or Url:' . tep_draw_input_field('hotel_pic_url', '', 'size="42"'));

                                                    $contents[] = array('text' => '<br>图片alt<br>' . tep_draw_input_field('hotel_pic_alt', '', 'size="42"'));

                                                    $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_insert.gif', IMAGE_INSERT) . ' <a href="' . tep_href_link('hotel.php', 'page=' . $HTTP_GET_VARS['page']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_draw_hidden_field('action', 'insert'));
                                                    break;

                                                case 'edit':
                                                    $heading[] = array('text' => '<b>编辑酒店信息</b>');

                                                    $contents = array('form' => tep_draw_form('hotel', 'hotel.php', 'page=' . $HTTP_GET_VARS['page'] . '&action=save', 'post', 'enctype="multipart/form-data" onSubmit="submit_check(); return false; "'));
                                                    $contents[] = array('text' => '编辑酒店信息');
                                                    $contents[] = array('text' => '<br>' . '酒店名称：' . '<br>' . tep_draw_input_field('hotel_name', $HInfo->hotel_name, 'size="34"') . tep_draw_hidden_field('hotel_id', $HInfo->hotel_id));
													 $contents[] = array('text' => '<br>对应的酒店产品ID<br>' . tep_draw_input_field('products_id', $HInfo->products_id, 'size="12"', true));
                                                    $hotel_stars = $HInfo->hotel_stars;
                                                    $contents[] = array('text' => '<br>酒店星级<br>' . tep_draw_radio_field('hotel_stars', '0') . '无&nbsp;'
                                                        . tep_draw_radio_field('hotel_stars', '1') . '1星&nbsp;'
                                                        . tep_draw_radio_field('hotel_stars', '2') . '经济型&nbsp;'
                                                        . tep_draw_radio_field('hotel_stars', '3') . '3星&nbsp;'
                                                        . tep_draw_radio_field('hotel_stars', '4') . '4星&nbsp;'
                                                        . tep_draw_radio_field('hotel_stars', '5') . '5星&nbsp;'
                                                        . tep_draw_radio_field('hotel_stars', '6') . '6星&nbsp;');
                                                    $meals_id = ($HInfo->meals_id) ? $HInfo->meals_id : 9;
													
													$contents[] = array('text' => '<br>餐食：' . tep_draw_pull_down_menu('meals_id',getHotelMealsOptions(),$meals_id));
													
													$internet_id = ($HInfo->internet_id) ? $HInfo->internet_id : 9;
													$contents[] = array('text' => '<br>网络：' . tep_draw_pull_down_menu('internet_id',getHotelInternetOptions(),$internet_id));
													$approximate_location_id = ($HInfo->approximate_location_id) ? $HInfo->approximate_location_id : 1;
													$contents[] = array('text' => '<br>位置：' . tep_draw_pull_down_menu('approximate_location_id',getHotelApproximateLocation(),$approximate_location_id));
													
                                                    $contents[] = array('text' => '<br>电话<br>' . tep_draw_input_field('hotel_phone', $HInfo->hotel_phone, 'size="24"'));
													$contents[] = array('text' => '<br>传真<br>' . tep_draw_input_field('hotel_fax', $HInfo->hotel_fax, 'size="24"'));
                                                    $contents[] = array('text' => '<br>酒店地址<br>' . tep_draw_input_field('hotel_address', $HInfo->hotel_address, 'size="60"'));

                                                    $contents[] = array('text' => '<br>' . '酒店简介：<br>' . tep_draw_textarea_field('hotel_description', '10', '60', '12', $HInfo->hotel_description) . '<br>（支持html代码）');
                                                    $contents[] = array('text' => '<br>酒店的供应商');
                                                    $contents[] = array('text' => $html_str);

                                                    $contents[] = array('text' => '<br>同行程其他酒店<br>' . tep_draw_input_field('related_hotel', $HInfo->related_hotel, 'size="42"'));
                                                    $contents[] = array('text' => '<br>同行程升级酒店<br>' . tep_draw_input_field('related_high_hotel', $HInfo->related_high_hotel, 'size="42"'));

                                                    $contents[] = array('text' => '<br>酒店URLname<br>' . tep_draw_input_field('hotel_url_name', $HInfo->hotel_url_name, 'size="42"'));
                                                    $contents[] = array('text' => '<br>' . '酒店地图：<br>' . tep_draw_textarea_field('hotel_map', '10', '60', '12', $HInfo->hotel_map));

                                                    $pic_sql = tep_db_query('SELECT * FROM `hotel_pic` WHERE hotel_id="' . (int) $hotel_id . '" ORDER BY `hotel_pic_sort` ASC');
                                                    $pic_str = '<div id="pic">';
                                                    $table_tpl = '
	  <table border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="200" align="left" class="infoBoxContent">%s</td>
			<td width="180" align="right" class="infoBoxContent">%s</td>
			<td width="50" align="right" class="infoBoxContent">%s</td>
			<td width="50" align="right" class="infoBoxContent">%s</td>
		  </tr>
		</table>
	  ';

                                                    while ($pic_rows = tep_db_fetch_array($pic_sql)) {
                                                        $hotel_pic_url = $pic_rows['hotel_pic_url'];
                                                        if (!preg_match('/^http:\/\//', $hotel_pic_url)) {
                                                            $hotel_pic_url = DIR_WS_CATALOG_IMAGES . 'hotel/' . $hotel_pic_url;
                                                        }

                                                        $hotel_pic_alt_input = tep_draw_hidden_field('hotel_pic_id[]', $pic_rows['hotel_pic_id']);
                                                        $hotel_pic_alt_input .= 'Url:' . tep_draw_input_field('hotel_pic_url_array[]', $pic_rows['hotel_pic_url']);
                                                        $hotel_pic_alt_input .= 'Alt:' . tep_draw_input_field('hotel_pic_alt[]', $pic_rows['hotel_pic_alt'], 'size="20"');
                                                        $hotel_pic_sort_input = tep_draw_input_field('hotel_pic_sort[]', $pic_rows['hotel_pic_sort'], 'size="4"');

                                                        $pic_str .= '<div id="pic_' . $pic_rows['hotel_pic_id'] . '">' . sprintf($table_tpl, '<a href="' . $hotel_pic_url . '" target="_blank" title="' . $pic_rows['hotel_pic_alt'] . '"><img src="' . $hotel_pic_url . '" width="180" border="0" style="margin:5px;" /></a><br />', $hotel_pic_alt_input, $hotel_pic_sort_input, '<input type="checkbox" name="del_box[]" value="' . $pic_rows['hotel_pic_id'] . '" />') . '</div>';
                                                    }
                                                    $pic_str .='</div><div><input name="add_pic" type="button" id="add_pic" onclick="add_new_pic();" value="添加图片" /><div style="color:red"><b>提示：</b>设置不重复数字的排序可以提高网速！</div></div>';

                                                    $contents[] = array('text' => '<br>' . '酒店图片：<br>');
                                                    $contents[] = array('text' => sprintf($table_tpl, '图片', 'ALT', '排序', '删除'));
                                                    $contents[] = array('text' => '<div id="div_hotel_pic">' . $pic_str . '</div>');


                                                    $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . tep_href_link('hotel.php', 'page=' . $HTTP_GET_VARS['page']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                                                    break;

                                                case 'delete':
                                                    $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_HOTEL . '</b>');

                                                    if ($login_groups_id == '1') {
                                                        $remove_language = true;
                                                    }
                                                    $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
                                                    $contents[] = array('text' => '<br><b>' . $HInfo->hotel_name . '</b>');
                                                    $contents[] = array('align' => 'center', 'text' => '<br>' . (($remove_language) ? '<a href="' . tep_href_link('hotel.php', 'page=' . $HTTP_GET_VARS['page'] . '&hotel_id=' . $HTTP_GET_VARS['hotel_id'] . '&action=deleteconfirm') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>' : '') . ' <a href="' . tep_href_link('hotel.php', 'page=' . $HTTP_GET_VARS['page']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                                                    break;

                                                default:
                                                    if (is_object($HInfo)) {
                                                        $heading[] = array('text' => '<b>' . $HInfo->hotel_name . '</b>');

                                                        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link('hotel.php', 'page=' . $HTTP_GET_VARS['page'] . '&hotel_id=' . $HTTP_GET_VARS['hotel_id'] . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link('hotel.php', 'page=' . $HTTP_GET_VARS['page'] . '&hotel_id=' . $HTTP_GET_VARS['hotel_id'] . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> <a href="' . HTTP_SERVER . '/hotel.php?hotel_id=' . $HTTP_GET_VARS['hotel_id'] . '&action=preview' . '" target="_blank">' . tep_image_button('button_preview.gif', 'preview') . '</a> ');
                                                        $contents[] = array('text' => '<br>' . '酒店名称：' . $HInfo->hotel_name);
                                                        $contents[] = array('text' => '酒店星级：' . $HInfo->hotel_stars . ' 星');
                                                        $contents[] = array('text' => '<br>' . '电话：' . $HInfo->hotel_phone);
                                                        $contents[] = array('text' => '传真：' . $HInfo->hotel_fax);
                                                        $contents[] = array('text' => '酒店地址：' . $HInfo->hotel_address);
                                                        $contents[] = array('text' => '<br>' . '酒店简介：' . $HInfo->hotel_description);
                                                        $contents[] = array('text' => '<br>' . '酒店地图：' . $HInfo->hotel_map);
                                                    }
                                                    break;
                                            }

                                            if ((tep_not_null($heading)) && (tep_not_null($contents))) {
                                                echo '            <td width="30%" valign="top">' . "\n";

                                                $box = new box;
                                                echo $box->infoBox($heading, $contents);

                                                echo '            </td>' . "\n";
                                            }
?>
                                </tr>                    
                            </table>
		<?php
		if($hotel_permissions['能看不能编'] == true){
			$ob_html = ob_get_clean();
			$ob_html = preg_replace('/\<[[:space:]]*form[[:space:]]*/is','<XXXform ',$ob_html);
			$ob_html = preg_replace('/\<[[:space:]]*\/[[:space:]]*form[[:space:]]*\>/is','</XXXform>',$ob_html);
			$ob_html = preg_replace('/\<[[:space:]]*input[[:space:]]*/is','<input disabled="disabled" ',$ob_html);
			$ob_html = preg_replace('/\<[[:space:]]*button[[:space:]]*/is','<button disabled="disabled" ',$ob_html);
			$ob_html = preg_replace('/\<[[:space:]]*textarea[[:space:]]*/is','<textarea disabled="disabled" ',$ob_html);
			//去掉删除功能
			$ob_html = str_replace('action=delete','action=notDelete',$ob_html);
			echo $ob_html;
		}
		?>					
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
                    <br>                    
                    </body>                    
                    </html>                    
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>