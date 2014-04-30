<?php
!defined('_MODE_KEY') && exit('Access error!');
if(!$isExpertsSelf){
	showmsg('访问错误\r\n您不是该专家,您不能访问这里！',tep_href_link($baseUrl,"mod=home&uid={$uid}"));
}
$isWritingsMPro = (isset($_GET['aid']) && is_numeric($_GET['aid']))?true:false;
if(!$isWritingsMPro){
	unset($crumbData[count($crumbData)-1]);	
}else{
	$aid = intval($_GET['aid']);	
}
//=======================================================================================
if($isWritingsMPro){
	$sql = "SELECT t.name as typename,w.title,w.tid FROM `experts_writings` w,`experts_writings_type` t WHERE w.`tid`=t.`tid` and w.`aid`='{$aid}' and w.`uid`='{$uid}' and w.`is_draft`='0'";
	$writings = tep_db_get_one($sql);
	if(!$writings){
		showmsg('访问错误\r\n连接地址错误或文章不存在！',tep_href_link($baseUrl,"mod=writings&uid={$uid}"));
	}
}
if(isset($_POST['postType']) && ($_POST['postType'] == 'cancell' || $_POST['postType'] == 'recom')){
	$products_id = $_POST['products_id'];
	if(count($products_id)){
		if($_POST['postType'] == 'cancell'){
			$products_id = "'".join("','",$products_id)."'";
			tep_db_query("DELETE FROM `experts_writings_products` WHERE `aid`='{$aid}' AND `uid`='{$uid}' AND `products_id` in({$products_id});");
		}else{
			if($_POST['isPost'] == 1){
				$insertSQL = "INSERT INTO `experts_writings_products` (`aid`,`uid`,`products_id`) VALUES ";
				$ext = '';
				foreach($products_id as $pid){
					$insertSQL .= " {$ext}('{$aid}','{$uid}','{$pid}')";
					$ext = ',';
				}
				tep_db_query_check($insertSQL);
			}else{
				$products_id = $products_id[0];
				tep_db_query("UPDATE `experts_remarks` SET `recom` = '{$products_id}' WHERE `uid` = '{$uid}' limit 1;");
			}
		}
		!$_SERVER['HTTP_REFERER'] && $_SERVER['HTTP_REFERER'] = tep_href_link($baseUrl,"mod=writings&uid={$uid}&action={$mproducts}&aid={$aid}");
		ObHeader($_SERVER['HTTP_REFERER']);
	}else{
		showmsg('操作失败！\r\n您没有选择行程！');
	}
}else{
	if($isWritingsMPro){
		//===========================
		$writings['time'] = date('m/d/Y H:i',strtotime($writings['time']));
		$crumb['Title'] = $writings['typename'];
		$crumb['NoSeo'] = true;
		$crumb['Url'] = tep_href_link($baseUrl,"uid={$uid}&mod=writings&tid={$writings['tid']}");
		$crumbData[] = $crumb;
		
		$crumb['Title'] = $writings['title'];
		$crumb['NoSeo'] = true;
		$crumb['Url'] = tep_href_link($baseUrl,"uid={$uid}&mod=writings&action=view&aid={$aid}");
		$crumbData[] = $crumb;
		//=======================================================================================
	}
	if($isWritingsMPro){
		$crumb['Title'] = '管理文章推荐行程';
	}else{
		$crumb['Title'] = '管理主页推荐行程';
	}
	$crumb['NoSeo'] = false;
	$crumb['Url'] = tep_href_link($baseUrl,"uid={$uid}&mod=writings&action=mproducts&aid={$aid}");
	$crumbData[] = $crumb;
		//=======================================================================================		
	$now_date = date('Y-m-d');
	$expires_date = $now_date.date(' H:i:s');
	//此为取价格列时需要的条件
	$specilWhere = " s.status='1' 
				AND (s.expires_date > '{$expires_date}' or s.expires_date is null or s.expires_date ='0000-00-00 00:00:00') ";
	//价格列
	$specilClunmsName = " round(IF({$specilWhere}, s.specials_new_products_price, p.products_price)/cur.value) ";
	if($isWritingsMPro){
		$sql = "SELECT ep.* , p.products_model, pd.products_name,p.products_tax_class_id,
				{$specilClunmsName} as final_price
				FROM  `experts_writings_products` ep, products p
				left join specials s on s.products_id = p.products_id, 
				tour_travel_agency ta, products_description pd ,currencies cur
				WHERE ep.`products_id` = p.`products_id` AND ep.`products_id` = pd.`products_id` 
				AND p.products_status = '1' and pd.language_id = '1' and p.agency_id = ta.agency_id and ta.operate_currency_code = cur.code
				AND ep.`aid` =  '{$aid}' AND ep.`uid` =  '{$uid}'";
	}else{
		$sql = "SELECT p.products_model, pd.products_name,p.products_tax_class_id,p.`products_id`,
				{$specilClunmsName} as final_price
				FROM  products p
				left join specials s on s.products_id = p.products_id, 
				tour_travel_agency ta, products_description pd ,currencies cur
				WHERE p.`products_id` = pd.`products_id` 
				AND p.products_status = '1' and pd.language_id = '1' and p.agency_id = ta.agency_id and ta.operate_currency_code = cur.code
				AND p.`products_id` =  '{$expertsInfo['recom']}'";
	}
	$query = tep_db_query($sql);
	$wProducts = array();
	$notInProducts = array();
	while ($rt = tep_db_fetch_array($query)){
		//当前显示价格,价格已在SQL语句里运算
		$rt['products_name1']=strstr($rt['products_name'], '**');
		if($rt['products_name1']!='' && $rt['products_name1']!==false)$rt['products_name']=str_replace($rt['products_name1'],'',$rt['products_name']);
		$rt['final_price'] = $currencies->display_price($rt['final_price'],tep_get_tax_rate($rt['products_tax_class_id']));
	
		$rt['ordernum'] = getProducts_OrderNum($rt['uid'],$rt['products_id']);//取得对应产品的推广定单数
		$notInProducts[] = $rt['products_id'];
		$wProducts[]=$rt;
	}
	//=======================================================================================
	$categoryData = array();
	$categoryData[] = array('id'=>25,name=>'美 东');
	$categoryData[] = array('id'=>24,name=>'美 西');
	$categoryData[] = array('id'=>33,name=>'夏威夷');
	$categoryData[] = array('id'=>196,name=>'特色美国游');
	$categoryData[] = array('id'=>54,name=>'加拿大');
	$categoryData[] = array('id'=>208,name=>'拉丁美洲');
	$categoryData[] = array('id'=>157,name=>'欧 洲');
	$categoryData[] = array('id'=>193,name=>'亚洲旅游');
	//=======================================================================================
	$category = intval($_GET['category']);
	$isHave = false;
	foreach($categoryData as $key=>$cv){
		if($category == $cv['id']){
			$isHave = true;
			break;
		}
	}
	!$isHave && $category = $categoryData[0]['id'];
	$childCateIds = tep_get_category_subcategories_ids($category);
	
	$notInProducts = "'".join("','",$notInProducts)."'";
	
	$FromAndWhere = "products p 
			left join specials s on s.products_id = p.products_id, 
			".TABLE_PRODUCTS_TO_CATEGORIES . " p2c,tour_travel_agency ta, products_description pd ,currencies cur
			WHERE p.`products_id` = pd.`products_id` and p.products_id = p2c.products_id 
			AND p.products_status = '1' and pd.language_id = '1' and p.agency_id = ta.agency_id and ta.operate_currency_code = cur.code 
			and p.`products_id`not in(".$notInProducts.")
			and p2c.categories_id in (" . $childCateIds . ")";
	$orderBy = " order by p.products_ordered DESC,p.products_last_modified DESC ";
	
	$query = tep_db_query("select COUNT( DISTINCT p.products_id ) as rows FROM {$FromAndWhere}");
	$query = tep_db_fetch_array($query);
	$total=(int)$query["rows"];
	$db_perpage = 15;
	
	$sql = "SELECT p.products_id,p.products_model, pd.products_name,p.products_tax_class_id,
			{$specilClunmsName} as final_price
			FROM {$FromAndWhere} group by p.products_id {$orderBy}";
	
	$pages=makePager($total,'page',$db_perpage,false);
	$limit=" LIMIT ".($page-1)*$db_perpage.",$db_perpage;";
	$query = tep_db_query($sql.$limit);
	
	$categoryProducts = array();
	while ($rt = tep_db_fetch_array($query)){
		//当前显示价格,价格已在SQL语句里运算
		$rt['products_name1']=strstr($rt['products_name'], '**');
		if($rt['products_name1']!='' && $rt['products_name1']!==false)$rt['products_name']=str_replace($rt['products_name1'],'',$rt['products_name']);
		$rt['final_price'] = $currencies->display_price($rt['final_price'],tep_get_tax_rate($rt['products_tax_class_id']));
	
		$rt['ordernum'] = getProducts_OrderNum($rt['uid'],$rt['products_id']);//取得对应产品的推广定单数
		
		$categoryProducts[]=$rt;
	}
}
?>