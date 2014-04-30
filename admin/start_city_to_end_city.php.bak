<?php
require('includes/application_top.php');

$action = isset($_POST['action']) ? $_POST['action'] : $_GET['action'];
$error = false;
switch($action){
	case "addConfirm":
	case "updateConfirm":
		$city_id = (int)$_POST['city_id'];
		$corresponding_destination_string = ajax_to_general_string(tep_db_prepare_input($_POST['corresponding_destination_string']));
		if($city_id<1 || !tep_not_null($corresponding_destination_string)){
			die("No city_id or corresponding_destination_string");
		}
		$corresponding_destination_string = html_to_db($corresponding_destination_string);
		$corresponding_destination_string = str_replace('，',',',$corresponding_destination_string);
		$tmp_array = explode(',',$corresponding_destination_string);
		$end_city_ids = array();
		foreach((array)$tmp_array as $key => $val){
			if(tep_not_null($val)){
				$sql = tep_db_query('select city_id from '.TABLE_TOUR_CITY.' where city ="'.tep_db_input(trim($val)).'" ');
				$row = tep_db_fetch_array($sql);
				if((int)$row['city_id']){
					$end_city_ids[] = $row['city_id'];
				}
			}
		}
		
		$end_city_ids_string = implode(',',array_unique($end_city_ids));
		
		tep_db_query('update '.TABLE_TOUR_CITY.' set corresponding_destination_ids="'.$end_city_ids_string.'" where city_id="'.$city_id.'" ');
		$sql = tep_db_query('select corresponding_destination_ids from '.TABLE_TOUR_CITY.' where city_id="'.$city_id.'" ');
		$row = tep_db_fetch_array($sql);
		
		$_city_string = tep_db_output(implode(', ',tep_get_city_names($row['corresponding_destination_ids'])));
		
		$js_str = '[JS]';
		$js_str .= 'jQuery("#showEndCityString_'.$city_id.'").text("'.$_city_string.'");';
		$js_str .= 'jQuery("#editEndCity_'.$city_id.'").hide();';
		$js_str .= '[/JS]';
		$js_str = preg_replace('/[[:space:]]+/',' ',$js_str);
		echo db_to_html($js_str);
		exit;
	break;
	default:
	
		//数据查询 {
		$order_by = ' order by  ttc.city_id ASC , ttc.city_code ASC';
		$_where = "";
		if(tep_not_null($_GET['city'])){
			//$where .= ' AND (city Like "%'.tep_db_input(tep_db_prepare_input($_GET['city'])).'%" || key_words1 Like "%'.tep_db_input(tep_db_prepare_input($_GET['city'])).'%") ';
			$_where .= ' AND city Like "%'.tep_db_input(tep_db_prepare_input($_GET['city'])).'%" ';
		}
		
		$sql_str = 
		"select ttc.corresponding_destination_ids, ttc.departure_city_status, ttc.city_id, ttc.city_img, ttc.city, ttc.city_code, ttc.is_attractions, ttc.regions_id, ttc.state_id, r.countries_id, rd.regions_name, c.countries_name, ts.zone_name from " . TABLE_TOUR_CITY . " as ttc, ".TABLE_REGIONS_DESCRIPTION." as rd, ".TABLE_REGIONS." r, ".TABLE_ZONES." as ts, ".TABLE_COUNTRIES." c where ttc.regions_id = rd.regions_id and r.regions_id = rd.regions_id and r.countries_id = c.countries_id and rd.language_id='".$languages_id."'  and ttc.state_id = ts.zone_id and ttc.departure_city_status ='1' and ttc.city_code !=''".$_where.$order_by;

		$keywords_query_numrows = 0;
		$keywords_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $sql_str, $keywords_query_numrows);
		
		$keywords_query = tep_db_query($sql_str);
		//数据查询 }
	
	break;
}

//取得所有城市，用于AJAX选择菜单
//$all_city_sql = tep_db_query("select ttc.departure_city_status, ttc.city_id, ttc.city_img, ttc.city, ttc.city_code, ttc.is_attractions, ttc.regions_id, ttc.state_id, r.countries_id, rd.regions_name, c.countries_name, ts.zone_name from " . TABLE_TOUR_CITY . " as ttc, ".TABLE_REGIONS_DESCRIPTION." as rd, ".TABLE_REGIONS." r, ".TABLE_ZONES." as ts, ".TABLE_COUNTRIES." c where ttc.regions_id = rd.regions_id and r.regions_id = rd.regions_id and r.countries_id = c.countries_id and rd.language_id='".$languages_id."'  and ttc.state_id = ts.zone_id and ttc.departure_city_status ='1' and ttc.city_code !='' ");



$p=array('/&amp;/','/&quot;/');
$r=array('&','"');
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">

<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
<script language="javascript" src="includes/javascript/add_global.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script type="text/javascript">
//提交表单
function submitEndCity(formObj){
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo("start_city_to_end_city.php",'action=updateConfirm')) ?>");
	var form_id = formObj.id;
	ajax_post_submit(url,form_id);
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
    <td width="100%" valign="top">
	<table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo START_CITY_TO_END_CITY_HEADING?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		 
        </table></td>
      </tr>
      <tr>
        <td>
          <!--search form start-->
		  <fieldset>
		  <legend align="left"> Search Module </legend>
		  <?php echo tep_draw_form('form_search', 'start_city_to_end_city.php', tep_get_all_get_params(array('page','y','x', 'action')), 'get', 'id="form_search" '); ?>
			<table width="200" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="main" nowrap><?= START_CITY?><?= tep_draw_input_field('city')?>&nbsp;</td>
					<td class="main" nowrap><input type="submit" value="搜索(Enter)" /> <a href="<?= tep_href_link("start_city_to_end_city.php");?>">清空搜索选项</a></td>
				</tr>
			</table>
			<div><?php if($login_groups_id == '1'){ echo $sql_str; }?></div>

		  <?php echo '</form>';?>
		  </fieldset>
		  <!--search form end-->		  </td>
      </tr>
      <tr>
        <td>
		<fieldset>
		  <legend align="left"> Stats Results </legend>
		  <table id="dataTable" border="0" cellspacing="1" cellpadding="0">
			  <tr class="dataTableHeadingRow">
			    <td width="150" align="center" nowrap="nowrap" class="dataTableHeadingContent"><?= TITLE_ID?></td>
				<td width="300" align="center" nowrap="nowrap" class="dataTableHeadingContent"><?= TITLE_START_CITY?></td>
			    <td width="300" align="center" nowrap="nowrap" class="dataTableHeadingContent"><?= TITLE_END_CITY?></td>
			    <td align="center" nowrap="nowrap" class="dataTableHeadingContent"><?= TITLE_ACTION?></td>
			  </tr>
			<?php while($cities_rows = tep_db_fetch_array($keywords_query)){?>  
			  <tr id="tr_<?= $cities_rows['city_id'];?>" class="dataTableRow">
			    <td class="dataTableContent"><?= $cities_rows['city_id'];?></td>
			    <td height="25" class="dataTableContent" ><?= $cities_rows['city'];?></td>
		        <td nowrap class="dataTableContent" >
				<div id="showEndCityString_<?= $cities_rows['city_id']?>"><?php $_city_string = implode(', ',tep_get_city_names($cities_rows['corresponding_destination_ids'])); echo $_city_string;?></div>
				<div id="editEndCity_<?= $cities_rows['city_id']?>" style="display:none">
				<form id="editFrom_<?= $cities_rows['city_id']?>" onSubmit="submitEndCity(this); return false;">
				<?php echo tep_draw_input_field('corresponding_destination_string',$_city_string,' size="60"');?>
				<input name="city_id" type="hidden" value="<?= $cities_rows['city_id']?>" />
				<button type="submit"><?= BUTTON_SUBMIT?></button>
				</form>
				</div>
				</td>
		        <td nowrap class="dataTableContent">
		        	[<a href="javascript:void(0);" onClick="jQuery('#editEndCity_<?= $cities_rows['city_id']?>').show();">Edit</a>]&nbsp;		        	
					</td>
			  </tr>
			  
			<?php }?>  
			</table>
		</fieldset>		</td>
      </tr>
      <tr>
            
			<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $keywords_split->display_count($keywords_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                <td class="smallText" align="right"><?php echo $keywords_split->display_links($keywords_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $_GET['page'],tep_get_all_get_params(array('page','y','x', 'action'))); ?>&nbsp;</td>
              </tr>
            </table></td>
          </tr>
    </table>
	</td>
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
