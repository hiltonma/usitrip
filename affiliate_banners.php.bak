<?php
/*
$Id: affiliate_banners.php,v 1.1.1.1 2004/03/04 23:37:54 ccwjr Exp $

OSC-Affiliate

Contribution based on:

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2002 - 2003 osCommerce

Released under the GNU General Public License
*/
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );

require('includes/application_top.php');

// 网站联盟开关
if (strtolower(AFFILIATE_SWITCH) === 'false') {
	echo '<div align="center">此功能暂不开放！回<a href="' . tep_href_link('index.php') . '">首页</a></div>';
	exit();
}

if (!tep_session_is_registered('affiliate_id')) {
	$navigation->set_snapshot();
	if(tep_not_null($_COOKIE['LoginDate'])){
		$messageStack->add_session('login', LOGIN_OVERTIME);
		setcookie('LoginDate', '');
	}
	tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
}
checkAffiliateVerified();

require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_AFFILIATE_BANNERS);

$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_AFFILIATE_BANNERS));


$bLinkImage = $bLinkText = $bLinkImageText = "";
$bLinkImage = $bLink1Image = $bLink2Image = "";
//生成各种方式的联盟代码 start {
if(tep_not_null($_GET['action']) && $_POST['ajax']=="true"){
	require(DIR_FS_INCLUDES . 'ajax_encoding_control.php');
	$js_str = '';
	switch ($_GET['action']){
		case "Products":	//取产品线路代码
		if(tep_not_null($_POST['products_model'])){
			$rProductsId = tep_db_get_field_value('products_id','products',' products_model="'.trim($_POST['products_model']).'" ');
		}else {
			$rProductsId = ((int)$_POST['rProductsId']) ? (int)$_POST['rProductsId'] : (int)$_GET['rProductsId'];
		}
		if((int)$rProductsId){
			$affiliate_pbanners_values = tep_db_query("select p.products_image, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . $rProductsId . "' and pd.products_id = '" . $rProductsId . "' and p.products_status = '1' and pd.language_id = '" . $languages_id . "'");

			while ($affiliate_pbanners = tep_db_fetch_array($affiliate_pbanners_values)) {
				$product_image = $affiliate_pbanners['products_image'];
				$product_image = (stripos($product_image,'http://') === false ? DIR_WS_IMAGES:'') . $product_image;
				$product_image = str_replace('/picture/detail_','/picture/thumb_',$product_image);

				$products_name = ($affiliate_pbanners['products_name']);

				//图片广告
				$bLinkImage = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO , 'ref=' . $affiliate_id . '&utm_source=' . $affiliate_id . '&utm_medium=af&utm_term=detaillink&' . '&products_id=' . $rProductsId . '&affiliate_banner_id=1').'" target="_blank"><img src="' . $product_image . '" border="0" alt="' . tep_db_output($affiliate_pbanners['products_name']) . '"></a>';
				$js_str .= 'jQuery("#codeProductsImages").val("'.tep_db_input(tep_db_prepare_input($bLinkImage)).'");';

				//文字广告
				$affiliate_pbanners['affiliate_banners_image'] = (stripos($product_image,'http://') === false ? DIR_WS_IMAGES:'') . $affiliate_pbanners['affiliate_banners_image'];
				$bLinkText = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO , 'ref=' . $affiliate_id .'&utm_source=' . $affiliate_id . '&utm_medium=af&utm_term=detaillink&' . '&products_id=' . $rProductsId . '&affiliate_banner_id=1').'" target="_blank">' . tep_db_output($affiliate_pbanners['products_name']) . '</a>';
				$js_str.= 'jQuery("#codeProductsText").val("'.tep_db_input(tep_db_prepare_input($bLinkText)).'");';
				//图文广告
				$iframe_src = tep_href_link('affiliate_banners_public.php','_ref='.$affiliate_id.'&_utm_source=' . $affiliate_id .'&_utm_medium=af&_utm_term=detaillink'. '&_products_id=' . $rProductsId . '&_affiliate_banner_id=1');
				$iframe_src .= '&iframe_action=products';
				$bLinkImageText = '<iframe scrolling="no" frameborder="0" marginheight="0" marginwidth="0" width="100%" height="150" src="'.$iframe_src.'"></iframe>';
				$js_str.= 'jQuery("#codeProductsImagesText").val("'.tep_db_input(tep_db_prepare_input($bLinkImageText)).'");';
				//预览控制
				_switch_links_type($js_str);
			}
		}else {
			$js_str.= 'alert("产品不存在！");';
		}

		$js_str .= 'jQuery("#createCodeButtonP").attr("disabled",false);';
		$js_str .= 'jQuery("#createCodeButtonP").html("生成代码");';
		break;

		case "Cat":	//取景点目录代码
		$rCatId = ((int)$_POST['rCatId']) ? (int)$_POST['rCatId'] : (int)$_GET['rCatId'];
		$aSql = tep_db_query("select pd.categories_name, p.categories_image from " . TABLE_CATEGORIES_DESCRIPTION . " as pd ," . TABLE_CATEGORIES . " as p where pd.categories_id = p.categories_id and pd.categories_id = '" .$rCatId . "' and pd.language_id = '" . $languages_id . "' Limit 1");
		while($aRows = tep_db_fetch_array($aSql)){
			$categories_name = preg_replace('/ .+$/','',trim($aRows['categories_name']));
			$categories_image = '';
			$_tmp_src = 'image/affiliate/banners/'.strtolower(CHARSET).'/'.strtolower($_POST['tips']).'/'.$rCatId.'/'.$_POST['image_size'].'.jpg';
			if(file_exists(DIR_FS_CATALOG.$_tmp_src)){
				//优先使用已经设计的图片
				$categories_image = HTTP_SERVER.'/'.$_tmp_src;
			}else{
				if(!tep_not_null($aRows['categories_image'])){
					$categories_image = HTTP_SERVER . DIR_WS_HTTP_CATALOG.'image/q_a_touxiang.jpg';
				}else{
					$categories_image = (stripos($aRows['categories_image'],'http://') === false ? DIR_WS_IMAGES:'') . $aRows['categories_image'];
					$categories_image = str_replace('/picture/detail_','/picture/thumb_',$categories_image);
				}
			}
			//图片广告
			$code_images = '<a href="' . tep_href_link(FILENAME_DEFAULT , 'cPath='.$rCatId.'&ref='.$affiliate_id.'&utm_source=' . $affiliate_id .'&utm_medium=af&utm_term=catgorylink') .'" target="_blank"><img src="' . $categories_image . '" border="0" alt="' . tep_db_output($categories_name) . '"></a>';
			$js_str.= 'jQuery("#codeProductsImages").val("'.tep_db_input(tep_db_prepare_input($code_images)).'");';
			//文字广告
			$code_text = '<a href="' . tep_href_link(FILENAME_DEFAULT , 'cPath='.$rCatId.'&ref='.$affiliate_id.'&utm_source=' . $affiliate_id .'&utm_medium=af&utm_term=catgorylink') .'" target="_blank">'.tep_db_output($categories_name).'</a>';
			$js_str.= 'jQuery("#codeProductsText").val("'.tep_db_input(tep_db_prepare_input($code_text)).'");';
			//图文广告
			$code_image_text = '<div>'.$code_images.'<br>'.$code_text.'</div>';
			$js_str.= 'jQuery("#codeProductsImagesText").val("'.tep_db_input(tep_db_prepare_input($code_image_text)).'");';
			//预览控制
			_switch_links_type($js_str);
		}
		$js_str .= 'jQuery("#createCodeButtonC").html("生成代码");';
		$js_str .= 'jQuery("#createCodeButtonC").attr("disabled",false);';
		break;

		case 'Custom':	//取自定义链接代码
		$textLink = $_POST['custom_links_url'].(strpos($_POST['custom_links_url'],'?')!==false ? '&' : '?').('ref='.$affiliate_id.'&utm_source=' . $affiliate_id .'&utm_medium=af&utm_term=customlink');
		$code_custom_html = '<a href="' . $textLink .'" target="_blank">'.html_to_db(ajax_to_general_string($_POST['custom_links_text'])).'</a>';
		$code_custom_url = $textLink;
		$js_str .= 'jQuery("#codeCustomHtml").val("'.tep_db_input(tep_db_prepare_input($code_custom_html)).'");';
		$js_str .= 'jQuery("#codeCustomUrl").val("'.tep_db_input(tep_db_prepare_input($code_custom_url)).'");';
		$js_str .= 'jQuery("#createCodeButtonD").html("生成代码");';
		$js_str .= 'jQuery("#createCodeButtonD").attr("disabled",false);';
		break;

		case 'Search':	//搜索框嵌入代码
		$iframe_src = tep_href_link('affiliate_banners_public.php','_ref='.$affiliate_id.'&_utm_source=' . $affiliate_id .'&_utm_medium=af&_utm_term=searchlink');
		$iframe_height = '271';
		if($_POST['searchType']=='all' || $_POST['searchType']=='side'){
			$iframe_src .= '&_searchType='.$_POST['searchType'];
			if($_POST['searchType']=='all'){
				if($_POST['search_logo']==='1'){
					$iframe_src .= '&_search_logo='.$_POST['search_logo'];
				}
				if($_POST['search_keywords']==='1'){
					$iframe_src .= '&_search_keywords='.$_POST['search_keywords'];
				}
				$iframe_src .= '&iframe_action=searchT';
				$iframe_height = '100';
			}else {
				$iframe_src .= '&iframe_action=searchB';
			}
		}

		$searchLinkHtml = '<iframe id="searchBI" scrolling="no" frameborder="0" marginheight="0" marginwidth="0" width="100%" height="271"'.$iframe_height.'" src="'.$iframe_src.'"></iframe>';
		$js_str .= 'jQuery("#codeSearchHtml").val("'.tep_db_input(tep_db_prepare_input($searchLinkHtml)).'");';
		$js_str .= 'jQuery("#createCodeButtonE").html("生成代码");';
		$js_str .= 'jQuery("#createCodeButtonE").attr("disabled",false);';
		$js_str .= '_preview(\'codeSearchHtml\');';	//预览
		break;

		case 'Index':	//首页链接代码
		$link_adderss = HTTP_SERVER.'/?ref='.$affiliate_id.'&utm_source=' . $affiliate_id .'&utm_medium=af&utm_term=indexlink';
		$image_src = HTTP_SERVER.'/image/affiliate/banners/'.strtolower(CHARSET).'/'.strtolower($_POST['tips']).'/'.$_POST['image_size'].'.jpg';
		//图片广告
		$code_images = '<a href="' . $link_adderss .'" target="_blank"><img src="' . $image_src . '" border="0" alt="走四方"></a>';
		$js_str.= 'jQuery("#codeProductsImages").val("'.tep_db_input(tep_db_prepare_input($code_images)).'");';
		//文字广告
		$code_text = '<a href="' . $link_adderss .'" target="_blank">走四方旅游网</a>';;
		$js_str.= 'jQuery("#codeProductsText").val("'.tep_db_input(tep_db_prepare_input($code_text)).'");';
		//图文广告
		$code_image_text = '<div>'.$code_images.'<br>'.$code_text.'</div>';
		$js_str.= 'jQuery("#codeProductsImagesText").val("'.tep_db_input(tep_db_prepare_input($code_image_text)).'");';
		//预览控制
		_switch_links_type($js_str);
		$js_str .= 'jQuery("#createCodeButtonF").html("生成首页代码");';
		$js_str .= 'jQuery("#createCodeButtonF").attr("disabled",false);';
		break;

		case 'Theme':	//主题活动链接代码
		$link_adderss = tep_href_link('affiliate_banners_public.php','ref='.$affiliate_id.'&utm_source=' . $affiliate_id .'&utm_medium=af&utm_term=themelink'.'&theme_name='.$_POST['theme_name']);
		$image_src = HTTP_SERVER.'/image/affiliate/banners/'.strtolower(CHARSET).'/'.strtolower($_POST['tips']).'/'.strtolower($_POST['theme_name']).'/'.$_POST['image_size'].'.jpg';
		$title_text = '走四方旅游网';
		switch ($_POST['theme_name']){
			case 'googleapple': $title_text = '硅谷科技之旅游苹果谷歌'; break;
			case 'familyfun': $title_text = '亲子游-一睹名校风采'; break;
			case 'shopping': $title_text = '去美国购物吧'; break;
			case '2012yellow_stone': $title_text = '黄石公园开团有大礼'; break;
			case 'yhuts': $title_text = '独家入住！黄石公园小木屋'; break;
		}
		//图片广告
		$code_images = '<a href="' . $link_adderss .'" target="_blank"><img src="' . $image_src . '" border="0" alt="'.$title_text.'"></a>';
		$js_str.= 'jQuery("#codeProductsImages").val("'.tep_db_input(tep_db_prepare_input($code_images)).'");';
		//文字广告
		$code_text = '<a href="' . $link_adderss .'" target="_blank">'.$title_text.'</a>';;
		$js_str.= 'jQuery("#codeProductsText").val("'.tep_db_input(tep_db_prepare_input($code_text)).'");';
		//图文广告
		$code_image_text = '<div>'.$code_images.'<br>'.$code_text.'</div>';
		$js_str.= 'jQuery("#codeProductsImagesText").val("'.tep_db_input(tep_db_prepare_input($code_image_text)).'");';
		//预览控制
		_switch_links_type($js_str);
		$js_str .= 'jQuery("#createCodeButtonG").html("生成代码");';
		$js_str .= 'jQuery("#createCodeButtonG").attr("disabled",false);';
		break;

	}

	$js_str .= '';
	$js_str = preg_replace('/[[:space:]]+/', ' ',$js_str);
	echo '[JS]'.db_to_html($js_str).'[/JS]';
	exit;
}
//生成各种方式的联盟代码 end }

/**
 * 控制显示预览的区域，此函数只用于本页的（图文广告下面首页、主题活动、景点和线路行程）。所以以_开头
 *
 * @param unknown_type $js_str
 */
function _switch_links_type(&$js_str){
	switch($_POST['links_type']){
		case 'imageTextAdPr':
			$js_str.= 'jQuery("#get_code_view_panel").html(jQuery("#codeProductsImagesText").val());';
			break;
		case 'imageAdPr':
			$js_str.= 'jQuery("#get_code_view_panel").html(jQuery("#codeProductsImages").val());';
			break;
		case 'textAdPr':
		default: $js_str.= 'jQuery("#get_code_view_panel").html(jQuery("#codeProductsText").val());';
	}
}


/*//推荐产品
$referProducts = getAffiliateAllProducts(100);
*/
$rProductsId = ((int)$_POST['rProductsId']) ? (int)$_POST['rProductsId'] : (int)$_GET['rProductsId'];
$products_model = tep_get_products_model($rProductsId);

//推荐目录{
$rfCats = array();
$rfCats[] = array('id'=>24,'name'=>'美西');
$rfCats[] = array('id'=>25,'name'=>'美东');
$rfCats[] = array('id'=>33,'name'=>'夏威夷');
$rfCats[] = array('id'=>34,'name'=>'佛罗里达');
$rfCats[] = array('id'=>54,'name'=>'加拿大');
$rfCats[] = array('id'=>208,'name'=>'拉丁美洲');
$rfCats[] = array('id'=>157,'name'=>'欧洲');
//$rfCats[] = array('id'=>182,'name'=>'酒店');
$rfCats[] = array('id'=>298,'name'=>'海外游学');
//$rfCats[] = array('id'=>299,'name'=>'签证');
for($i=0, $n=sizeof($rfCats); $i < $n; $i++){
	$cSql = tep_db_query('SELECT c.categories_id, cd.categories_name FROM `categories` c, `categories_description` cd
							 WHERE c.categories_id=cd.categories_id and c.categories_status="1" and c.parent_id="'.$rfCats[$i]['id'].'" and cd.language_id="1" ORDER BY sort_order Limit 500');
	while($cRows = tep_db_fetch_array($cSql)){
		$rfCats[$i]['child'][] = array('id'=>$cRows['categories_id'], 'name'=>preg_replace('/ .+$/','',trim($cRows['categories_name'])));
	}
}
//print_r($rfCats);
//推荐目录}

require(DIR_FS_CLASSES . 'affiliate.php');
$affiliate = new affiliate;
//自动帮客户创建优惠券编码
$affiliate->createCouponCode($affiliate_id);

//自动帮老客户创建优惠码，上线后由周豪华执行一次后即可删除此代码(已经完成)
//$affiliate->autoCreateCouponCodeForAllOldCustomers();

//我的优惠券代码
$my_coupon_code = $affiliate->couponCode($affiliate_id);


$content = CONTENT_AFFILIATE_BANNERS;

$is_my_account = true;

require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

require(DIR_FS_INCLUDES . 'application_bottom.php');

?>