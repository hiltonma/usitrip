<?php
/*
$Id: html_output.php,v 1.1.1.1 2004/03/04 23:39:54 ccwjr Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2003 osCommerce

Released under the GNU General Public License
*/
////
// The HTML href link wrapper function
function tep_href_link_noseo($page = '', $parameters = '', $connection = 'NONSSL') {
	if ($page == '') {
		die('</td></tr></table></td></tr></table><br><br><font color="#ff0000"><b>Error!</b></font><br><br><b>Unable to determine the page link!<br><br>Function used:<br><br>tep_href_link(\'' . $page . '\', \'' . $parameters . '\', \'' . $connection . '\')</b>');
	}

	if (ENABLE_SSL == 'true') {
		$connection = 'SSL';
	}
	if ($connection == 'NONSSL') {
		$link = HTTP_SERVER . DIR_WS_ADMIN;
	} elseif ($connection == 'SSL') {
		if (ENABLE_SSL == 'true') {
			$link = HTTPS_SERVER . DIR_WS_ADMIN;
		} else {
			$link = HTTP_SERVER . DIR_WS_ADMIN;
		}
	} else {
		die('</td></tr></table></td></tr></table><br><br><font color="#ff0000"><b>Error!</b></font><br><br><b>Unable to determine connection method on a link!<br><br>Known methods: NONSSL SSL<br><br>Function used:<br><br>tep_href_link(\'' . $page . '\', \'' . $parameters . '\', \'' . $connection . '\')</b>');
	}
	if ($parameters == '') {
		$link = $link . $page . '?' . SID;
	} else {
		$link = $link . $page . '?' . $parameters . '&' . SID;
	}
	//echo "bhavik";
	if(isset($_GET['durations']) && $_GET['durations'] != '')
	{
		$link .= 'durations='.$_GET['durations'];
	}
	if(isset($_GET['departure']) && $_GET['departure'] != '')
	{
		$link .= 'departure='.$_GET['departure'];
	}

	while ( (substr($link, -1) == '&') || (substr($link, -1) == '?') ) $link = substr($link, 0, -1);

	return $link;
}

////
// The HTML href link wrapper function
function tep_href_link($page = '', $parameters = '', $connection = 'SSL') {
	return tep_href_link_noseo($page, $parameters, $connection);
}

function tep_catalog_href_link($page = '', $parameters = '', $connection = 'NONSSL') {
	if ($connection == 'NONSSL') {
		$link = HTTP_CATALOG_SERVER . DIR_WS_CATALOG;
	} elseif ($connection == 'SSL') {
		if (ENABLE_SSL_CATALOG == 'true') {
			$link = HTTPS_CATALOG_SERVER . DIR_WS_CATALOG;
		} else {
			$link = HTTP_CATALOG_SERVER . DIR_WS_CATALOG;
		}
	} else {
		die('</td></tr></table></td></tr></table><br><br><font color="#ff0000"><b>Error!</b></font><br><br><b>Unable to determine connection method on a link!<br><br>Known methods: NONSSL SSL<br><br>Function used:<br><br>tep_href_link(\'' . $page . '\', \'' . $parameters . '\', \'' . $connection . '\')</b>');
	}


	if($page == FILENAME_DEFAULT) {
		if(preg_match('/cPath=([0-9_]+)/', $parameters, $m)) {
			$cPath = $m[1];
			$parameters = preg_replace('/cPath=[0-9_]+&?/', '', $parameters);
			$page = seo_get_path_from_cpath($cPath);
		}
		if(preg_match('/page=([0-9]+)/', $parameters, $m)) {
			$page1 = $m[1];
			$parameters = preg_replace('/page=[0-9]+&?/', '', $parameters);
			$page = $page.'p-'.$page1.'/';
		}
		if(preg_match('/sort=([0-9a-zA-Z]+)/', $parameters, $m)) {
			$sort1 = $m[1];
			$parameters = preg_replace('/sort=[0-9a-zA-Z]+&?/', '', $parameters);
			$page = $page.'s-'.$sort1.'/';
		}
	} elseif($page == FILENAME_PRODUCT_INFO) {
		if(preg_match('/products_id=([0-9]+)/', $parameters, $m)) {
			$products_id = $m[1];
			$parameters = preg_replace('/products_id=[0-9]+&?/', '', $parameters);
			$parameters = preg_replace('/cPath=[0-9_]+&?/', '', $parameters);
			$page = seo_get_products_path($products_id, true, $tablink);
		}
	}

	if ($parameters == '') {
		$link .= $page;
	} else {
		$link .= $page . '?' . $parameters;
	}

	while ( (substr($link, -1) == '&') || (substr($link, -1) == '?') ) $link = substr($link, 0, -1);

	return $link;
}


// SEOurls - function to create category path from cPath
function seo_get_path_from_cpath($cPath) {
	$ret = '';
	$cPath_array = explode('_', $cPath);
	$category_id = $cPath_array[count($cPath_array)-1];

	$res = tep_db_query("select c1.categories_urlname from " . TABLE_CATEGORIES . " c1 where c1.categories_id = '" . (int)$category_id. "'");
	$cat_array = tep_db_fetch_array($res);

	return $cat_array['categories_urlname'];
}

////
define('SEO_EXTENSION','.html');

// SEOurls - new funtion to get category path for a given product
// WARNING: works only for products 3 levels deep in category hierarchy

function seo_get_products_path($products_id, $full = false, $tablink = '') { // $full == true => include product

	$ret = '';



	if($full) {

		$res = tep_db_query("select p.products_urlname from " . TABLE_PRODUCTS . " p where p.products_id = '" . (int)$products_id . "'");

		$cat_array = tep_db_fetch_array($res);
		if($tablink != ''){
			//return $cat_array['products_urlname'] .$tablink.SEO_EXTENSION;

			if($tablink == '-question-answer'){
				$tablink = '/question-&-answer/';
			}
			if($tablink == 'traveler-photos'){
				$tablink = '/traveler-photos/';
			}

			return $cat_array['products_urlname'] .$tablink;
		}else{
			return $cat_array['products_urlname'] . SEO_EXTENSION;
		}

	} else {

		return '';

	}



}


////

////
// The HTML image wrapper function
function tep_image($src, $alt = '', $width = '', $height = '', $params = '') {
	$image = '<img src="' . $src . '" border="0" alt="' . $alt . '"';
	if ($alt) {
		$image .= ' title=" ' . $alt . ' "';
	}
	if ($width) {
		$image .= ' width="' . $width . '"';
	}
	if ($height) {
		$image .= ' height="' . $height . '"';
	}
	if ($params) {
		$image .= ' ' . $params;
	}
	$image .= '>';

	return $image;
}

////
// The HTML form submit button wrapper function
// Outputs a button in the selected language
function tep_image_submit($image, $alt = '', $parameters = '') {
	global $language;

	$image_submit = '<input type="image" src="' . tep_output_string(DIR_WS_LANGUAGES . $language . '/images/buttons/' . $image) . '" border="0" alt="' . tep_output_string($alt) . '"';

	if (tep_not_null($alt)) $image_submit .= ' title=" ' . tep_output_string($alt) . ' "';

	if (tep_not_null($parameters)) $image_submit .= ' ' . $parameters;

	$image_submit .= '>';

	return $image_submit;
}

////
// Draw a 1 pixel black line
function tep_black_line() {
	return tep_image(DIR_WS_IMAGES . 'pixel_black.gif', '', '100%', '1');
}

////
// Output a separator either through whitespace, or with an image
function tep_draw_separator($image = 'pixel_black.gif', $width = '100%', $height = '1') {
	return tep_image(DIR_WS_IMAGES . $image, '', $width, $height);
}

////
// Output a function button in the selected language
function tep_image_button($image, $alt = '', $params = '') {
	global $language;

	return tep_image(DIR_WS_LANGUAGES . $language . '/images/buttons/' . $image, $alt, '', '', $params);
}

////
// javascript to dynamically update the states/provinces list when the country is changed
// TABLES: zones
function tep_js_zone_list($country, $form, $field) {
	$countries_query = tep_db_query("select distinct zone_country_id from " . TABLE_ZONES . " order by zone_country_id");
	$num_country = 1;
	$output_string = '';
	while ($countries = tep_db_fetch_array($countries_query)) {
		if ($num_country == 1) {
			$output_string .= '  if (' . $country . ' == "' . $countries['zone_country_id'] . '") {' . "\n";
		} else {
			$output_string .= '  } else if (' . $country . ' == "' . $countries['zone_country_id'] . '") {' . "\n";
		}

		$states_query = tep_db_query("select zone_name, zone_id from " . TABLE_ZONES . " where zone_country_id = '" . $countries['zone_country_id'] . "' order by zone_name");

		$num_state = 1;
		while ($states = tep_db_fetch_array($states_query)) {
			if ($num_state == '1') $output_string .= '    ' . $form . '.' . $field . '.options[0] = new Option("' . PLEASE_SELECT . '", "");' . "\n";
			$output_string .= '    ' . $form . '.' . $field . '.options[' . $num_state . '] = new Option("' . $states['zone_name'] . '", "' . $states['zone_id'] . '");' . "\n";
			$num_state++;
		}
		$num_country++;
	}
	$output_string .= '  } else {' . "\n" .
	'    ' . $form . '.' . $field . '.options[0] = new Option("' . TYPE_BELOW . '", "");' . "\n" .
	'  }' . "\n";

	return $output_string;
}

////
// Output a form
function tep_draw_form($name, $action, $parameters = '', $method = 'post', $params = '') {
	$form = '<form name="' . tep_output_string($name) . '" action="';
	if (tep_not_null($parameters)) {
		$form .= tep_href_link($action, $parameters);
	} else {
		$form .= tep_href_link($action);
	}
	$form .= '" method="' . tep_output_string($method) . '"';
	if (tep_not_null($params)) {
		$form .= ' ' . $params;
	}
	$form .= '>';

	return $form;
}
function tep_draw_form_close($formname = ''){
	return '</form>';
}
////
// Output a form input field
function tep_draw_input_field($name, $value = '', $parameters = '', $required = false, $type = 'text', $reinsert_value = true) {
	global $language;
	if($language=='schinese'){
		$onBlur = 'onBlur="this.value = simplized(this.value);"';
	}else{
		$onBlur = 'onBlur="this.value = traditionalized(this.value);"';
	}

	if(preg_match('/onBlur="/',$parameters)){
		$onBlurExp = preg_replace('/.*onBlur="(.*)"[;]*/','$1"',$parameters);
		$parameters = preg_replace('/onBlur="(.*)"/','',$parameters);
		//echo $onBlurExp;
		$onBlur = preg_replace('/"$/',' ',$onBlur). $onBlurExp;
		//echo $onBlur;
	}

	$field = '<input '.$onBlur.' type="' . tep_output_string($type) . '" name="' . tep_output_string($name) . '"';


	if ( (isset($GLOBALS[$name])) && ($reinsert_value == true) ) {
		$field .= ' value="' . tep_output_string(stripslashes($GLOBALS[$name])) . '"';
	} elseif (tep_not_null($value)) {
		$field .= ' value="' . tep_output_string($value) . '"';
	}

	if (tep_not_null($parameters)) $field .= ' ' . $parameters;

	$field .= ' />';

	return $field;
}

//输出数字或英文的输入框,即关闭输入法
function tep_draw_input_num_en_field($name, $value='', $parameters=''){
	if(preg_match('/style="([^"]+)"/',$parameters,$m)){
		$parameters = preg_replace('/style="([^"]+)"/','', $parameters);
	}
	$parameters .= ' style="ime-mode: disabled; '.$m[1].'" ';
	return tep_draw_input_field($name, $value, $parameters);
}

////
// Output a form password field
function tep_draw_password_field($name, $value = '', $required = false) {
	$field = tep_draw_input_field($name, $value, 'maxlength="40"', $required, 'password', false);

	return $field;
}

////
// Output a form filefield
function tep_draw_file_field($name, $required = false) {
	$field = tep_draw_input_field($name, '', '', $required, 'file');

	return $field;
}





//Admin begin
////
// Output a selection field - alias function for tep_draw_checkbox_field() and tep_draw_radio_field()
//  function tep_draw_selection_field($name, $type, $value = '', $checked = false, $compare = '') {
//    $selection = '<input type="' . tep_output_string($type) . '" name="' . tep_output_string($name) . '"';
//
//    if (tep_not_null($value)) $selection .= ' value="' . tep_output_string($value) . '"';
//
//    if ( ($checked == true) || (isset($GLOBALS[$name]) && is_string($GLOBALS[$name]) && ($GLOBALS[$name] == 'on')) || (isset($value) && isset($GLOBALS[$name]) && (stripslashes($GLOBALS[$name]) == $value)) || (tep_not_null($value) && tep_not_null($compare) && ($value == $compare)) ) {
//      $selection .= ' CHECKED';
//    }
//
//    $selection .= '>';
//
//    return $selection;
//  }
//
////
// Output a form checkbox field
//  function tep_draw_checkbox_field($name, $value = '', $checked = false, $compare = '') {
//    return tep_draw_selection_field($name, 'checkbox', $value, $checked, $compare);
//  }
//
////
// Output a form radio field
//  function tep_draw_radio_field($name, $value = '', $checked = false, $compare = '') {
//    return tep_draw_selection_field($name, 'radio', $value, $checked, $compare);
//  }
////
// Output a selection field - alias function for tep_draw_checkbox_field() and tep_draw_radio_field()
function tep_draw_selection_field($name, $type, $value = '', $checked = false, $compare = '', $parameter = '') {
	$selection = '<input type="' . $type . '" name="' . $name . '"';
	if ($value != '') {
		$selection .= ' value="' . $value . '"';
	}
	//if ( ($checked == true) || ($GLOBALS[$name] == 'on') || ($value && ($GLOBALS[$name] == $value)) || ($value && ($value == $compare)) ) {
	if ( ($checked == true) || ($GLOBALS[$name] == 'on') || (tep_not_null($GLOBALS[$name]) && $GLOBALS[$name] == $value) || ($value && ($value == $compare)) ) {
		$selection .= ' CHECKED';
	}
	if ($parameter != '') {
		$selection .= ' ' . $parameter;
	}
	$selection .= '>';

	return $selection;
}

////
// Output a form checkbox field
function tep_draw_checkbox_field($name, $value = '', $checked = false, $compare = '', $parameter = '') {
	return tep_draw_selection_field($name, 'checkbox', $value, $checked, $compare, $parameter);
}


////
// Output a form radio field
function tep_draw_radio_field($name, $value = '', $checked = false, $compare = '', $parameter = '') {
	return tep_draw_selection_field($name, 'radio', $value, $checked, $compare, $parameter);
}
//Admin end

////
// Output a form textarea field
function tep_draw_textarea_field($name, $wrap, $width, $height, $text = '', $parameters = '', $reinsert_value = true) {
	if($language=='schinese' || strtolower(CHARSET)=='gb2312'){
		$onBlur = 'onBlur="this.value = simplized(this.value);"';
	}else{
		$onBlur = 'onBlur="this.value = traditionalized(this.value);"';
	}
	
	if(preg_match('/onBlur="/i',$parameters)){
		$onBlurExp = preg_replace('/.*onBlur="(.*)"[;]*/i','$1"',$parameters);
		$parameters = preg_replace('/onBlur="(.*)"/i','',$parameters);
		//echo $onBlurExp;
		$onBlur = preg_replace('/"$/',' ',$onBlur). $onBlurExp;
		//echo $onBlur;
	}
	$field = '<textarea '.$onBlur.' name="' . tep_output_string($name) . '" wrap="' . tep_output_string($wrap) . '" cols="' . tep_output_string($width) . '" rows="' . tep_output_string($height) . '"';

	if (tep_not_null($parameters)) $field .= ' ' . $parameters;

	$field .= '>';

	if ( (isset($GLOBALS[$name])) && ($reinsert_value == true) ) {
		$field .= stripslashes($GLOBALS[$name]);
	} elseif (tep_not_null($text)) {
		$field .= $text;
	}

	$field .= '</textarea>';

	return $field;
}

////
// Output a form hidden field
function tep_draw_hidden_field($name, $value = '', $parameters = '') {
	$field = '<input type="hidden" name="' . tep_output_string($name) . '"';

	if (tep_not_null($value)) {
		$field .= ' value="' . tep_output_string($value) . '"';
	} elseif (isset($GLOBALS[$name]) && is_string($GLOBALS[$name])) {
		$field .= ' value="' . tep_output_string(stripslashes($GLOBALS[$name])) . '"';
	}

	if (tep_not_null($parameters)) $field .= ' ' . $parameters;

	$field .= '>';

	return $field;
}


////
// Output a form pull down menu
/**
 * 输出下拉式菜单
 * @param $name 名称
 * @param $values 所有值
 * @param $default 默认值
 * @param $parameters 附加参数
 * @param $required
 */
function tep_draw_pull_down_menu($name, $values, $default = '', $parameters = '', $required = false) {
	$field = '<select name="' . tep_output_string($name) . '"';

	if (tep_not_null($parameters)) $field .= ' ' . $parameters;

	$field .= '>';

	if (empty($default) && isset($GLOBALS[$name])) $default = stripslashes($GLOBALS[$name]);

	foreach ($values as $key => $val) {
		if($val['optgroup']!=true || $default == $val['id']){
			$field .= '<option value="' . tep_output_string($val['id']) . '"';
			if ($default == $val['id']) {
				$field .= ' SELECTED';
			}
			$field .= '>' . tep_output_string($val['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>';
		}else{
			$field .= '<optgroup label="' . tep_output_string($val['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '"></optgroup>';
		}
	}
	$field .= '</select>';

	if ($required == true) $field .= TEXT_FIELD_REQUIRED;

	return $field;
}
//tom added 用于个人中心管理 start
function tep_draw_pull_down_menu_onclick($name, $values, $default = '', $parameters = '', $required = false) {
	$field = '<select name="' . tep_output_string($name) . '"';

	if (tep_not_null($parameters)) $field .= ' ' . $parameters;

	$field .= '>';

	if (empty($default) && isset($GLOBALS[$name])) $default = stripslashes($GLOBALS[$name]);

	for ($i=0, $n=sizeof($values); $i<$n; $i++) {
		$field .= '<option value="' . tep_output_string($values[$i]['id']) . '"';

		$field .= 'onclick="'.tep_output_string($values[$i]['onclick']) . '"';
		if ($default == $values[$i]['id']) {
			$field .= ' SELECTED';
		}
		$field .= '>' . tep_output_string($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>';
	}
	$field .= '</select>';

	if ($required == true) $field .= TEXT_FIELD_REQUIRED;

	return $field;
}
//tom added 用于个人中心管理 end

function tep_draw_mselect_menu($name, $values, $selected_vals, $params = '', $required = false) {

	$field = '<select name="' . $name . '"';

	if ($params) $field .= ' ' . $params;

	$field .= ' multiple>';

	for ($i=0; $i<sizeof($values); $i++) {

		if ($values[$i]['id'])

		{

			$field .= '<option value="' . $values[$i]['id'] . '"';

			if ( ((strlen($values[$i]['id']) > 0) && ($GLOBALS[$name] == $values[$i]['id'])) ) {

				$field .= ' SELECTED';

			}

			else

			{

				for ($j=0; $j<sizeof($selected_vals); $j++) {

					if ($selected_vals[$j]['id'] == $values[$i]['id'])

					{

						$field .= ' SELECTED';

					}

				}

			}

		} else { $field .= '<option value="0"'; } // Added 09/20/06 To correctly offer "Top" as category

		$field .= '>' . $values[$i]['text'] . '</option>';

	}

	$field .= '</select>';



	if ($required) $field .= TEXT_FIELD_REQUIRED;



	return $field;

}

function convert_datetime($str) {
	list($date, $time) = explode(' ', $str);
	list($year, $month, $day) = explode('-', $date);
	list($hour, $minute, $second) = explode(':', $time);
	$timestamp = @mktime(0, 0, 0, $month, $day, $year);
	return date("D",$timestamp);
}

function seo_get_articles_path($articles_id,$url_prifix) {
	$ret = '';

	$res = tep_db_query("select p.articles_seo_url,t.topics_id from " . TABLE_ARTICLES . " p, ".TABLE_ARTICLES_TO_TOPICS." t where p.articles_id=t.articles_id and p.articles_id = '" . (int)$articles_id . "'");
	$cat_array = tep_db_fetch_array($res);
	$current_topic_id = $cat_array['topics_id'];

	$topic_path  = seo_get_topic_url($current_topic_id);
	return $url_prifix.$topic_path.$cat_array['articles_seo_url'].SEO_EXTENSION;
}

function seo_get_topic_url($current_topic_id)
{
	$catname1 = tep_output_generated_topic_path($current_topic_id);
	if($catname1=='Top')
	{
		$catname_url = '';
	}
	else
	{
		$catname = str_replace('&nbsp;&gt;&nbsp;','/',strtolower($catname1));
		$catname_url = str_replace(' ','-',$catname).'/';
	}

	return $catname_url;
}

/**
   * 输出国家 省 地区框
   * @param unknown_type $refobj 要写入的元素名字
   * @param unknown_type $country 国家ID
   * @param unknown_type $state 省名字
   * @param unknown_type $city 城市名字
   * @param unknown_type $func_name onchange时调用的js函数名称
   * @param boolean $allowUserInput 是否允许在没有下层数据的情况下让用户自行输入内容
   * @author vincent
   * @modify by vincent at 2011-5-12 下午05:35:38
   */
function tep_draw_full_address_input($refobj ,$country = '',$state='',$city='',$allowUserInput = false ,$func_name='module_ai_refresh'){
	global $module_ai_countries;
	if(empty($module_ai_countries)){
		$module_ai_countries = array();
		$module_ai_countries[] = array('countries_id'=>'','countries_name'=>'请选择国家','countries_iso_code_2'=>'TOP');
		$countryQuery   = tep_db_query("select countries_id, countries_name,countries_iso_code_2 from " . TABLE_COUNTRIES . " WHERE 1 order by countries_name ASC");
		while($row = tep_db_fetch_array($countryQuery))$module_ai_countries[] = $row;
	}
	$countrySelector1 = ''; 	$countrySelector2 = '';
	foreach($module_ai_countries as $row){
		$selected = $country == $row['countries_id'] ? ' selected ':'';
		$txt = '<option value="'.$row['countries_id'].'" '.$selected.'>'.$row['countries_name'].'</option>';
		if(in_array(strtoupper($row['countries_iso_code_2']),array('TOP','US','CN','CA','HK','TW')))
		$countrySelector1.= $txt;
		else
		$countrySelector2.=$txt;
	}
	$html = '<select name="country"  id="'.$refobj.'_country" onchange="javascript:'.$func_name.'(\''.$refobj.'\',jQuery(this).val())" >'.$countrySelector1.$countrySelector2.'</select>';
	//洲
	if($country != ''){
		$stateQuery = tep_db_query("select zone_name,zone_id from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' order by zone_code");
		$stateSelector = '';$state_id = '' ;
		while($row = tep_db_fetch_array($stateQuery)){
			if(empty($row)) continue ;
			if($state == $row['zone_name']){
				$selected = ' selected ';
				$state_id =  $row['zone_id'];
			}else{
				$selected = '';
			}
			$stateSelector.='<option value="'.$row['zone_name'].'" '.$selected.'>'.$row['zone_name'].'</option>';
		}
		if($stateSelector == ''){return $html;} ;
		$html .= '<select name="state" onchange="javascript:'.$func_name.'(\''.$refobj.'\',jQuery(\'#'.$refobj.'_country\').val(),jQuery(this).val())"><option value="">请选择省/州</option>'.$stateSelector.'</select>';
	}else{
		$html .= '<select name="state" ><option value="" selected>请选择省/州</option></select>';
	}
	//城市
	if($state_id != ''){
		$cityQuery = tep_db_query("select city_name,city_id from  zones_city  where zone_id = '" . (int)$state_id . "' order by city_id");
		$citySelector = '';
		while($row = tep_db_fetch_array($cityQuery)){
			if(empty($row)) continue ;
			$selected =$city== $row['city_name']?' selected ':'';
			$citySelector.='<option value="'.$row['city_name'].'" '.$selected.'>'.$row['city_name'].'</option>';
		}
		if($citySelector == ''){return $html;} ;
		$html .= '<select name="city"><option value="">请选择城市</option>'.$citySelector.'</select>';
	}else{
		$html .= '<select name="city" ><option value="" selected>请选择城市</option></select>';
	}
	return $html;
}
/**
 * 联动地址栏需要的JS 
 * 调用 tep_draw_full_address_input 请同时调用该函数
 * @author vincent
 * @modify by vincent at 2011-5-12 下午05:44:21
 */
function tep_draw_full_address_input_js(){
	//if(defined("TEP_DRAW_FULL_ADDRESS_INPUT_JS")) return ;
	//else define("TEP_DRAW_FULL_ADDRESS_INPUT_JS" , 'used');
	//jQuery("#"+refobj).html("000");
	global $p,$r;

	return 'function module_ai_refresh(refobj,country_id,state){
	if(typeof(state) == "undefined") state = "";
	var url = url_ssl("'.preg_replace($p,$r,tep_href_link_noseo('account_edit_ajax.php')).'");    
	jQuery.get(url , {"action":"draw_full_address_input","refobj":refobj,"country":country_id,"state":state},function(data){jQuery("#"+refobj).html(data);});}';
	/**///IE9
	/*return 'function module_ai_refresh(refobj,country_id,state){
	if(typeof(state) == "undefined") state = "";
	var ajax=false;
	if(window.XMLHttpRequest){
	ajax = new XMLHttpRequest();
	}else if (window.ActiveXObject){
	try{
	ajax = new ActiveXObject("Msxml2.XMLHTTP");
	}catch(e){
	try {
	ajax = new ActiveXObject("Microsoft.XMLHTTP");
	}catch(e1){}
	}
	}
	if (!ajax) {
	window.alert("'.db_to_html('不能创建XMLHttpRequest对象实例.').'");
	}else{
	ajax.open("GET","'.tep_href_link('account_edit_ajax.php').'?action=draw_full_address_input&country="+country_id+"&state="+encodeURI(state)+"&refobj="+refobj,true);
	ajax.onreadystatechange = function(){
	if (ajax.readyState ==4 && ajax.status==200){
	document.getElementById(refobj).innerHTML = ajax.responseText;
	}
	}
	ajax.send(null);
	}
	}
	';
	return 'function module_ai_refresh(refobj,country_id,state){
	if(typeof(state) == "undefined"){ state = "";}
	var url = "account_edit_ajax.php?action=draw_full_address_input&country="+country_id+"&state="+encodeURI(state)+"&refobj="+refobj;
	ajax_get_submit(url);
	}';*/
}

/**
 * 输出数据库数据用作表单的默认值
 * @author Howard
 * @param unknown_type $vars
 * @return unknown
 */
function tep_ouput_for_form($vars){
	return tep_db_prepare_input($vars);
}

/**
 * 可移动弹出层的模板框架函数
 * @author Howard
 */
function pop_layer_tpl($layer_array, $show=false) {
	if (!is_array($layer_array)) {
		return false;
	}
	$start_display = "";
	if ($show != false) {
		$start_display = ' style="display:block" ';
	}
	$html_code = '
	<div class="popup" id="' . $layer_array['pop'] . '" onClick="findLayer(\'' . $layer_array['pop'] . '\')" ' . $start_display . '>
	<table width="100%" cellpadding="0" cellspacing="0" border="0" class="popupTable">
	<tr>
	<td class="topLeft"></td><td class="side"></td><td class="topRight"></td></tr><tr><td class="side"></td>
	<td class="con">
	<div class="popupCon" id="' . $layer_array['con'] . '" style="width:' . $layer_array['con_width'] . '">
	<div class="popupConTop" id="' . $layer_array['drag'] . '" ondblclick="changeCon(document.getElementById(\'' . $layer_array['pop'] . '_H_title_top\'),\'' . $layer_array['pop'] . '\',\'' . $layer_array['body_id'] . '\');">
	<h4><b>' . $layer_array['title'] . '</b></h4>
	<div class="popupClose" onClick="document.getElementById(\'' . $layer_array['pop'] . '\').style.display=\'none\'"><img src="images/icons/icon_x.gif" alt="close"/></div>
	<div class="popupChange" title="最小化/还原" id="' . $layer_array['pop'] . '_H_title_top" onClick="changeCon(this,\'' . $layer_array['pop'] . '\',\'' . $layer_array['body_id'] . '\');">-</div>
	</div>
	<div id="' . $layer_array['body_id'] . '">' . $layer_array['body_con'] . '</div>
	</div>
	</td><td class="side"></td></tr><tr><td class="botLeft"></td><td class="side"></td><td class="botRight"></td></tr>
	</table>
	<iframe class="iframeBg" frameborder="0"></iframe>
	</div>
	';
	return $html_code;
}

?>
