<?php 
//联系方式 start{
//联系我们 Howard 取消于2013-07-09
//include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'contact_us.php');
//小广告栏  Howard 取消于2013-07-09
/*$banner_name='product_info_contact_us_down 250px';
include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'banner_box.php');*/
?>
<?php
//接送服务
if(0 && $product_info['is_transfer'] == 1){//  Howard 取消于2013-07-09
	ob_start();
?>
    <div class="widget">
        <div class="title titleYellow">
            <b></b><span></span>
            <h3>自定义服务</h3>
        </div>
        <ul class="similar history shuttleRightSide">
            <li>
                <p>如果我们提供的服务没有您所需的，请您将所需要的服务告诉我们，我们会尽力为您热诚服务。</p>
                <div class="btnCenter">
                    <a href="javascript:;" onclick="showPopup('PopupTransferServiceRequest','PopupTransferServiceRequestCon');" class="btn btnGrey"><button type="button">提交所需服务</button></a>
                </div>
            </li>
        </ul>
    </div>
<?php 
	echo db_to_html(ob_get_clean());
}else{

//普通线路{
?>
<style type="text/css">
.popup .jb_fb_tc_tab{
	float:none;
	margin:auto;
	width:auto;
}
.popup .jb_fb_tc_bt{
	display:block;
	float:none;
}
</style>
<?php
if(!$_GET['seeAll']){	
	/*
		$key = "companion_".intval($products_id) ;
		$data = MCache::instance()->fetch($key,MCache::HOURS); //每小时更新一次
		if($data != '') 
			echo db_to_html($data) ;
		else{*/
		require_once('travel_companion_tpl.php');
			ob_start();
//结伴同游帖子列表 start
$product_info_travel_companion = TRAVEL_COMPANION_OFF_ON;
if($product_info_travel_companion== 'true'){
	//统计当前团的结伴同游总数
	$sql = tep_db_query('SELECT count(*) as total  FROM `travel_companion` WHERE products_id="'.(int)$products_id.'" AND status=1 ');
	$row = tep_db_fetch_array($sql);
	$total_companion = (int)$row['total'];
	//取得当前团的所属目录
	if((int)count($cPath_array)){
		$categories_id = $cPath_array[(count($cPath_array)-1)];
		$categories_name = preg_replace('/ .+/','',tep_get_categories_name($categories_id,1));
	}

	

	//$travel_sql = tep_db_query('SELECT * FROM `travel_companion` WHERE products_id="'.(int)$products_id.'" AND status=1 ORDER BY t_companion_id DESC Limit 10 ');
	$travel_sql = tep_db_query('SELECT t_companion_id,t_companion_title,customers_name,DATE_FORMAT(add_time,\'%m/%e/%Y\') AS formated_add_time FROM `travel_companion` WHERE products_id="'.(int)$products_id.'" AND status=1 ORDER BY t_companion_id DESC Limit 10 ');
	$travel_rows = tep_db_fetch_array($travel_sql);

	
?>
	<div class="widget mate_warp">
		<div class=" title titleSmall ">

		<h3>结伴同游</h3><span><a  href="javascript:void(0)" onclick="showPopup(&quot;CreateNewCompanion&quot;,&quot;CreateNewCompanionCon&quot;,'off','','','fixedTop','CreateNewCompanionLink');"><?php echo '我要发布'?></a></span>
	</div>
	<ul class="companion">
		<?php
		if((int)$travel_rows['t_companion_id']){
			do{
			$sir_ro_ms = '';
			if((int)$travel_rows['t_gender']=='1'){
				//$sir_ro_ms = '先生';
			}
			if((int)$travel_rows['t_gender']=='2'){
				//$sir_ro_ms = '女士';
			}
		?>
		<li><a title="<?php echo tep_db_output($travel_rows['t_companion_title']);?>" href="<?php echo tep_href_link('new_bbs_travel_companion_content.php','t_companion_id='.(int)$travel_rows['t_companion_id']).$TcPaStr?>"><?php echo cutword(tep_db_output($travel_rows['t_companion_title']),22);?></a>
		<span style="display:none"><?php 
		echo tep_db_output($travel_rows['customers_name']). $sir_ro_ms;?></span><?php echo date('m月d日',strtotime($travel_rows['formated_add_time'])); #echo substr($travel_rows['formated_add_time'],0,10).'发布'?>
			<?php
				$TcPaStr = '';
				if(tep_not_null($TcPath)){
					$TcPaStr = '&TcPath='.$TcPath;
				}
				?>
		</li>
		<?php
			}while($travel_rows = tep_db_fetch_array($travel_sql));
		}else{
			echo '<li>寻找游伴，拼房节省费用吧！</li>';
		}
		?>
		  
		  <li>
			<?php if((int)$total_companion){?>
			<p class="more"><a target="_blank" href="<?php echo tep_href_link('new_travel_companion_index.php','TcPath='.$cPath.'&products_id='.(int)$products_id);?>" ><?php echo '更多结伴同游&gt;&gt;'?></a></p>
			<?php }?>
			<div id="CreateNewCompanionLink" class="new" style="display:none"><a  href="javascript:void(0)" onclick="showPopup(&quot;CreateNewCompanion&quot;,&quot;CreateNewCompanionCon&quot;,'off','','','fixedTop','CreateNewCompanionLink');"><?php echo '发布立即结伴帖'?></a></div>
		  </li>
	</ul>
    <div class="del_float"></div>
	</div>
			
<?php
	$data = ob_get_clean() ;
	echo  db_to_html($data);
/*
$data = ob_get_clean() ;
MCache::instance()->add($key,$data);
echo  db_to_html($data);}*/
		
}
}
//结伴同游帖子列表 end
?>

<?php
//您浏览过的线路 start{  
if(0 && (int)count($_COOKIE['view_history'])){	//Howard 取消于2013-07-09

	// 取得列的列表
	foreach ($_COOKIE['view_history'] as $key => $value) {
		$srot_products_id[$key]  = $value['products_id'];
		$date_time[$key] = $value['date_time'];
	}
	// 将资料根据 date_time 降幂排列，根据 products_id 升幂排列
	// 把 $_COOKIE['view_history'] 作为最后一个参数，以通用键排序
	array_multisort($date_time, SORT_DESC, $srot_products_id, SORT_ASC, $_COOKIE['view_history']);

?>     
 <div class="widget"> 
  <div class="title titleSmall">
      <b></b><span></span>
      <h3><?php echo db_to_html('您浏览过的线路')?></h3>
  </div>
   <ul class="history">
	<?php
	$tmp_var = 0;
	foreach ($_COOKIE['view_history'] as $key => $value){
		$tmp_var++;
		if($tmp_var<7){
			$sql = tep_db_query("select p.products_id, p.products_price, p.products_tax_class_id, pd.products_name FROM " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$value['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id !='".(int)$products_id."' ");
			$row = tep_db_fetch_array($sql);
			if((int)$row['products_id']){
				$price_text = "";
				$tour_agency_opr_currency = tep_get_tour_agency_operate_currency($row['products_id']);
				if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
					$row['products_price'] = tep_get_tour_price_in_usd($row['products_price'],$tour_agency_opr_currency);
				}
				if (tep_get_products_special_price($row['products_id'])) 
				{
					$price_text.= '<del style="display:none">' .  $currencies->display_price($row['products_price'], tep_get_tax_rate($row['products_tax_class_id'])) . '</del> <b>' . $currencies->display_price(tep_get_products_special_price($row['products_id']), tep_get_tax_rate($row['products_tax_class_id'])) . '</b>';
				} 
				else 
				{
					$price_text.= '<b>'.$currencies->display_price($row['products_price'], tep_get_tax_rate($row['products_tax_class_id'])).'</b>';
				}
				echo '<li><a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $row['products_id']).'" title="'.db_to_html(tep_db_output($row['products_name'])).'">'.cutword(db_to_html(tep_db_output($row['products_name'])),28).'</a>'.$price_text.'</li>';
			}
		}
	}
	?>
  </ul>
  
  </div>
<?php
}
//您浏览过的线路 end}
?>

<?php 
if(0 && !$_GET['seeAll']){  //Howard 取消于2013-07-09
	//对价格进行格式化 以25为一个范围
	$price_span = 50 ;
	$low =  floor(intval($product_info['products_price'])/$price_span);
	$lowprice = $low *$price_span;
	$highprice = $lowprice + 50 ;
	
	$key = "product_html_price_".$lowprice.'_'.$highprice;
	$data = MCache::instance()->fetch($key  ,MCache::HOURS); //每小时更新一次
	if($data != '')
		 echo  db_to_html($data) ;
	else{
				ob_start();
				
	
//您也许还会关注这些 的行程列表 -start{
//查询与该页面产品具有您也许还会关注这些 的行程
//@author vincent

$otherWhere="";
if($isHotels){
	$otherWhere=" and p.is_hotel=1 ";
}

$product_list_sql = 'SELECT pd.products_name as name,p.products_id,p.products_image as img,p.products_urlname as url,p.products_tax_class_id,p.products_price FROM '. TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION .' pd  WHERE pd.products_id = p.products_id AND p.products_id <>"'.(int)$products_id.'" AND p.products_status=1 AND pd.language_id = \'' . (int)$languages_id . '\' AND ( p.products_price > '.$lowprice.' AND p.products_price< '.$highprice.' ) '.$otherWhere.'Limit 5 ';
$products_list = vin_db_fetch_all($product_list_sql);

if(count($products_list)>0 ){
?>
<div class="widget">

        <div class="title titleYellow">
            <b></b><span></span>
            <h3>您也许还会关注这些</h3>
        </div>
        <ul class="similar lazyLoad">
        <?php
		foreach($products_list as $product){
			$_img = get_thumbnails_fast(((stripos($product['img'],'http://') === false) ? 'images/':'') . $product['img']);
		?>
            <li>
                <div class="left"><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product['products_id'])?>"><img src="image/blank.gif" src2="<?php echo $_img?>" alt="<?php echo tep_db_output($product['name'])?>" title="<?php echo tep_db_output($product['name'])?>"/></a></div>
                <div class="right"><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product['products_id'])?>" alt="<?php echo $product['name']?>" title="<?php echo $product['name']?>"><?php echo cutword(tep_db_output($product['name'] ),40)?></a><span><?php echo $currencies->display_price($product['products_price'],tep_get_tax_rate($product['products_tax_class_id']))?></span></div>
                <div class="del_float"></div>
            </li>
           <?php }?>            
        </ul>
    </div>

<?php }
	$data = ob_get_clean();
	MCache::instance()->add($key,$data);
	echo  db_to_html($data);
	}
}
//您也许还会关注这些 的行程 end}
?>

<?php 

if($_GET['seeAll'] == false){
//同一出发地行程列表 -start
/* * */
$key = "product_html_samedeparturecity_".$product_info['departure_city_id'];
$data = MCache::instance()->fetch($key  ,MCache::HOURS); //每小时更新一次
if($data != '')
	 echo  db_to_html($data);
else{
			ob_start();
			
/**
 * 查询与该页面产品具有相同出发地的行程
 * @author vincent
 * */
//print_r($product_info);
$cityid = array_pop( explode(',',$product_info['departure_city_id']));
$products_list =array();
if(tep_not_null($cityid)){
$product_list_sql = 'SELECT p.products_id ,p.products_price,p.products_tax_class_id,pd.products_name as name,p.products_price as price ,p.products_urlname as url FROM '. TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION .' pd  WHERE pd.products_id = p.products_id AND p.products_id <>"'.(int)$products_id.'" AND p.products_status=1  AND pd.language_id = \'' . (int)$languages_id . '\' AND  p.departure_city_id IN (  '.$cityid.') Limit 9 ';
//生成出发城市链接 vincent 2011.3.30
$city_sql = tep_db_query('select * from '.TABLE_TOUR_CITY.' where city_id in ('.$cityid.') AND is_attractions !="1" AND departure_city_status ="1" AND city_code!="" ORDER BY city_code ASC ');
if(!is_array($categories_more_city_link)){
	$categories_more_city_link=tep_href_link(FILENAME_DEFAULT,'cPath='.$cPathOnly.'&mnu='.$cat_mnu_sel);
	$categories_more_city_link = explode('?',$categories_more_city_link);
}

	$citylinkhtml = '';
      while($row = tep_db_fetch_array($city_sql)){
          $href = $categories_more_city_link[0].'fc-'.$row['city_id'].'/'.($categories_more_city_link[1]?'?'.$categories_more_city_link[1]:'');
          $cityname = preg_replace('/ .+/','',tep_get_city_name($row['city_id']));
          $citylinkhtml.= '<a href="'.$href.'">'.$cityname.'</a>';
      }

$products_list = vin_db_fetch_all($product_list_sql);
if(count($products_list)>0){
?>
<div class="widget">

        <div class="title titleYellow">
            <b></b><span></span>
            <h3><?php echo $citylinkhtml?> 出发的行程还有</h3>
        </div>
        <ul class="similar history">
        <?php foreach($products_list as $product){?>
            <li>             
                <a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product['products_id'])?>" alt="<?php echo tep_db_output($product['name'])?>" title="<?php echo tep_db_output($product['name'])?>"><?php echo cutword(tep_db_output($product['name'] ),28)?></a>
           <?php
            			$price_text = "";
						$tour_agency_opr_currency = tep_get_tour_agency_operate_currency($product['products_id']);
						if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
							$product['products_price'] = tep_get_tour_price_in_usd($product['products_price'],$tour_agency_opr_currency);
						}
						if (tep_get_products_special_price($product['products_id'])) 
						{
							$price_text.= '<del style="display:none">' .  $currencies->display_price($product['products_price'], tep_get_tax_rate($product['products_tax_class_id'])) . '</del> <b>' . $currencies->display_price(tep_get_products_special_price($product['products_id']), tep_get_tax_rate($product['products_tax_class_id'])) . '</b>';
						} 
						else 
						{
							$price_text.= '<b>'.$currencies->display_price($product['products_price'], tep_get_tax_rate($product['products_tax_class_id'])).'</b>';
						} 
           ?>   <?php echo $price_text;?>       
            </li>
           <?php }?>            
        </ul>
    </div>

<?php }}
	$data = ob_get_clean();
	MCache::instance()->add($key,$data);
	echo  db_to_html($data);
}
}//同一出发地行程列表 end?>

<?php
if(0){	//  Howard 取消于2013-07-09
	$key = "product_html_customers_select_".intval($products_id);
	$data = MCache::instance()->fetch($key  ,MCache::HOURS); //每小时更新一次
	if($data != '')
		 echo  db_to_html($data) ;
	else{
				ob_start();
//订购此产品的游客还浏览了 start
$products_orders_sql = tep_db_query('SELECT distinct o.customers_id FROM '.TABLE_ORDERS_PRODUCTS.' op, '.TABLE_ORDERS.' o WHERE op.products_id = "'.(int)$products_id.'" and o.orders_id=op.orders_id Limit 20');
$customers_id_string = "";
while($products_orders = tep_db_fetch_array($products_orders_sql)){
	$customers_id_string .= $products_orders['customers_id'].',';
}
$customers_id_string = substr($customers_id_string,0,-1);
if(tep_not_null($customers_id_string)){
	$o_p_str = ('SELECT p.products_id, p.products_price, p.products_tax_class_id, op.products_name FROM '.TABLE_ORDERS.' o, '.TABLE_PRODUCTS.' p, '.TABLE_ORDERS_PRODUCTS.' op WHERE o.customers_id in('.$customers_id_string.') and o.orders_id=op.orders_id and p.products_id != "'.(int)$products_id.'" and p.products_id=op.products_id Group By p.products_id Order By p.products_price DESC Limit 5 ');
	//echo $o_p_str;
	$o_p_sql = tep_db_query($o_p_str);
	$o_p_rows = tep_db_fetch_array($o_p_sql);
	if((int)$o_p_rows['products_id']){

?>    
<div class="widget">  
  <div class="title titleSmall">
      <b></b><span></span>
      <h3>订购此产品的游客还浏览了</h3>
  </div>
     <ul class="history">
      <?php
		do{
			$price_text = "";
			$tour_agency_opr_currency = tep_get_tour_agency_operate_currency($o_p_rows['products_id']);
			if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
				$o_p_rows['products_price'] = tep_get_tour_price_in_usd($o_p_rows['products_price'],$tour_agency_opr_currency);
			}
			if (tep_get_products_special_price($o_p_rows['products_id'])) 
			{
				$price_text.= '<del>' .  $currencies->display_price($o_p_rows['products_price'], tep_get_tax_rate($o_p_rows['products_tax_class_id'])) . '</del> <b>' . $currencies->display_price(tep_get_products_special_price($o_p_rows['products_id']), tep_get_tax_rate($o_p_rows['products_tax_class_id'])) . '</b>';
			} 
			else 
			{
				$price_text.= '<b>'.$currencies->display_price($o_p_rows['products_price'], tep_get_tax_rate($o_p_rows['products_tax_class_id'])).'</b>';
			}
			
			$_products_name = tep_get_products_name($o_p_rows['products_id']);
			echo '<li><a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $o_p_rows['products_id']).'" title="'.tep_db_output($_products_name).'">'.cutword(tep_db_output($_products_name),48).'</a>'.$price_text.'</li>';
		}while($o_p_rows = tep_db_fetch_array($o_p_sql));
	  ?>
  </ul>
  </div>
<?php
	}
}
	
	$data = ob_get_clean();
	MCache::instance()->add($key,$data);
	echo  db_to_html($data);
	}
//订购此产品的游客还浏览了 end
}
?>

<?php
if(0){	// Howard 取消于2013-07-09
	//SEO优化 所属分类 start
	if(is_array($seo_parent_cats) && sizeof($seo_parent_cats)){?>
	<div class="widget sort">
	  <div class="title titleSmall">
				<b></b><span></span>
				<h3><?php echo db_to_html('所属分类');?></h3>
	  </div>
	  <ul class="sortList">
		<?php for($i=0; $i < sizeof($seo_parent_cats); $i++){?>
		<li>
			<a href="<?= tep_href_link(FILENAME_DEFAULT, 'cPath='.tep_get_category_patch($seo_parent_cats[$i]['categories_id']).$seo_parent_cats_get);?>"><?php echo db_to_html(tep_db_output(preg_replace('/ .*/','',$seo_parent_cats[$i]['categories_name'])));?></a>
		</li>
		<?php }?>
	  </ul>
	</div>
	<?php
	}
}
?>
<?php
// 订阅 Howard 取消于2013-07-09
//include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'email.php');
// 广告 Howard 取消于2013-07-09
/*$banner_name = 'product_info 265px';
include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'banner_box.php');*/
?>
<?php
// 让走四方联系我 start
//echo db_to_html($TffContactMe->right_html_type2011($language));
// 让走四方联系我 end

//积分说明链接 start{
/*
?>
    <div class="widget lazyLoad">
        <a href="<?php echo tep_href_link('points.php')?>" ><img src="image/blank.gif"  src2="image/detail_right_ad<?php if($language == 'tchinese')echo '_tr';?>.jpg" /></a>
    </div>
<?php
*/
//积分说明链接 end}

//普通线路 }
} 

//我们的优势
include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'advantages.php');
?>