<?php
$query = tep_db_query("select COUNT( DISTINCT p.products_id ) as rows from {$dataTables} {$dataWhere}");

$query = tep_db_fetch_array($query);
$total=(int)$query["rows"];
$db_perpage = MAX_DISPLAY_SEARCH_RESULTS;
//  取得翻页条 第四个参数是 设置另一种显示模式 共2种 0 或者大于零
$_pageType = 1;
$pages=makePager($total,'pp',$db_perpage,true,$_pageType);
$limit=" LIMIT ".($pp-1)*$db_perpage.",$db_perpage;";
$query = tep_db_query($querySQL.$limit);
$DataList = array();
while ($rt = tep_db_fetch_array($query)){//格式化数据
	//当前显示价格,价格已在SQL语句里运算
	if($rt['s_status']=='1' && ($rt['expires_date']>$expires_date || $rt['expires_date']=='' || $rt['expires_date']=='0000-00-00 00:00:00')){
		$rt['old_price']=$rt['products_price'];
	}
	//出团时间
	$rt['operate'] = tep_get_display_operate_info($rt['products_id'],1);
	//是否售完
	$rt['isSoldOut'] = false;
	if(count($rt['operate'])<1 || $rt['products_stock_status']==0){
		$rt['isSoldOut'] = true;
		tep_set_products_not_stock($rt['products_id']);
	}
	//结伴发贴数
	$rt['companion'] = (int)get_product_companion_post_num($rt['products_id']);
	//问题咨询数
	$rt['question'] = (int)get_product_question_num($rt['products_id']);
	//产品评论数
	$rt['reviews'] = (int)get_product_reviews_num($rt['products_id']);
	//照片数
	//$rt['photos'] = (int)get_traveler_photos_num($rt['products_id']);
	//满意度
	$_rating = tep_get_products_rating($rt['products_id']);
	$rt['rating'] = ($_rating['rating_total_avg'] ? number_format($_rating['rating_total_avg'],0):'100').'%';
	
	//出发城市departure_city_id,目的城市departure_end_city_id
	$rt['departure_city_id'] = explode(',',$rt['departure_city_id']);
	$rt['departure_end_city_id'] = explode(',',$rt['departure_end_city_id']);
	$city_ids = array_merge($rt['departure_city_id'],$rt['departure_end_city_id']);
	$cityquery = tep_db_query("select city_id, city from " . TABLE_TOUR_CITY . " where city_id in ('".join("','",$city_ids)."')  order by city");
	$rt['s_city']=$rt['e_city']=array();
	while($cityclass = tep_db_fetch_array($cityquery)){
		if(in_array($cityclass['city_id'],$rt['departure_city_id']))$rt['s_city'][$cityclass['city_id']] = $cityclass['city'];
		if(in_array($cityclass['city_id'],$rt['departure_end_city_id']))$rt['e_city'][$cityclass['city_id']] = $cityclass['city'];
	}
	unset($rt['departure_city_id'],$rt['departure_end_city_id']);
	
	
	//持续时间
	if($rt['products_durations_type'] == 0){
			$rt['products_durations_type'] =  TEXT_DURATION_DAYS;
	}else if($rt['products_durations_type'] == 1){
			$rt['products_durations_type'] =  TEXT_DURATION_HOURS;
	}else if($rt['products_durations_type'] == 2){
			$rt['products_durations_type']=  TEXT_DIRATION_MINUTES;
	}
	//==================优惠 START=============================
	$rt['gift']['num']=0;
	//====================特价========================
	if(	($rt['s_status']=='1' && !(int)$rt['s_specials_type'] && ($rt['expires_date']>$expires_date || $rt['expires_date']=='' || $rt['expires_date']=='0000-00-00 00:00:00') ) || preg_match('~specil-jia~is',$rt['tour_type_icon']) ){
		$rt['gift']['num']++;
		$rt['gift']['item'][$rt['gift']['num']]['name'] = db_to_html('特价');
		$rt['gift']['item'][$rt['gift']['num']]['key'] = 'specil';
	}
	unset($rt['s_status'],$rt['expires_date']);
	//=================双人折扣======================
	if(
	($rt['pdrp_status']=='1' && $rt['people_number']=='2' 
	&& ($rt['pdrp_pddb']<="{$now_date} 00:00:00" || $rt['pdrp_pddb']=='' || $rt['pdrp_pddb']=='0000-00-00 00:00:00') 
	&& ($rt['pdrp_pdde']>="{$now_date} 00:00:00" || $rt['pdrp_pdde']=='' || $rt['pdrp_pdde']=='0000-00-00 00:00:00') 
	&& ($rt['pdrp_eld_date']=='' || !preg_match("~{$now_date}~is",$rt['pdrp_eld_date']))
	)
	|| preg_match('~2-pepole-spe~is',$rt['tour_type_icon']) ){
		$rt['gift']['num']++;
		$rt['gift']['item'][$rt['gift']['num']]['name'] = db_to_html('双人折扣');
		$rt['gift']['item'][$rt['gift']['num']]['key'] = '2pepole';
	}
	unset($rt['pdrp_status'],$rt['people_number'],$rt['pdrp_pddb'],$rt['pdrp_pdde'],$rt['pdrp_eld_date']);
	//=================买2送1==========================
	if(
	($rt['pbgo_status']=='1' && $rt['products_class_id']=='4' && $rt['use_buy_two_get_one_price']=='1' 
	&& ($rt['one_or_two_option']=='0' || $rt['one_or_two_option']=='1')
	&& ($rt['pbgo_pddb']<="{$now_date} 00:00:00" || $rt['pbgo_pddb']=='' || $rt['pbgo_pddb']=='0000-00-00 00:00:00') 
	&& ($rt['pbgo_pdde']>="{$now_date} 00:00:00" || $rt['pbgo_pdde']=='' || $rt['pbgo_pdde']=='0000-00-00 00:00:00') 
	&& ($rt['pbgo_eld_date']=='' || !preg_match("~{$now_date}~is",$rt['pbgo_eld_date']))
	)
	|| preg_match('~buy2-get-1~is',$rt['tour_type_icon']) ){
		$rt['gift']['num']++;
		$rt['gift']['item'][$rt['gift']['num']]['name'] = db_to_html('买2送1');
		$rt['gift']['item'][$rt['gift']['num']]['key'] = 'b2g1';
	}
	//=================买2送2==========================
	if(
	($rt['pbgo_status']=='1' && $rt['products_class_id']=='4' && $rt['use_buy_two_get_one_price']=='1' 
	&& ($rt['one_or_two_option']=='0' || $rt['one_or_two_option']=='2')
	&& ($rt['pbgo_pddb']<="{$now_date} 00:00:00" || $rt['pbgo_pddb']=='' || $rt['pbgo_pddb']=='0000-00-00 00:00:00') 
	&& ($rt['pbgo_pdde']>="{$now_date} 00:00:00" || $rt['pbgo_pdde']=='' || $rt['pbgo_pdde']=='0000-00-00 00:00:00') 
	&& ($rt['pbgo_eld_date']=='' || !preg_match("~{$now_date}~is",$rt['pbgo_eld_date']))
	)
	|| preg_match('~buy2-get-2~is',$rt['tour_type_icon']) ){
		$rt['gift']['num']++;
		$rt['gift']['item'][$rt['gift']['num']]['name'] = db_to_html('买2送2');
		$rt['gift']['item'][$rt['gift']['num']]['key'] = 'b2g2';
	}
	unset($rt['pbgo_status'],$rt['use_buy_two_get_one_price'],$rt['pbgo_pddb'],$rt['pbgo_pdde'],$rt['pbgo_eld_date']);
	
	unset($rt['tour_type_icon'],$rt['products_class_id']);
	//=====================低价保证==========================
	if(strpos(','.LOW_PRICE_GUARANTEE_PRODUCTS.',',",{$rt['products_id']},")!==false){
		$rt['gift']['num']++;
		$rt['gift']['item'][$rt['gift']['num']]['name'] = db_to_html('低价保证');
		$rt['gift']['item'][$rt['gift']['num']]['key'] = 'low ';
	}
	//==================优惠 END=============================
	$rt['products_name1']=strstr($rt['products_name'], '**');
	if($rt['products_name1']!='' && $rt['products_name1']!==false)$rt['products_name']=str_replace($rt['products_name1'],'',$rt['products_name']);
	$DataList[]=$rt;
}
$lasthash='';
?>
<div class="chosen" id="<?php echo $ajaxTypename;?>_form_panel">
  <form action="<?php echo $pageurl.'?'.$pagequery;?>" method="post" name="<?php echo $ajaxTypename;?>_form" id="<?php echo $ajaxTypename;?>_form">
  <p><?php echo db_to_html('共<b id="'.$ajaxTypename.'_form_num">'.$total.'</b>个行程：')?></p>
  <?php 
 if($cat_mnu_sel!="recommended"){
 foreach($_schSlt as $sltkey=>$sltval){
	  if(count($sltval)){
  ?>
	  <span class="<?php echo $ajaxTypename;?>_mod_<?php echo $sltkey?>"><i style="cursor:default"><?php echo $sltval['name']?></i><a href="<?php echo makesearchUrl($sltkey,'');?>" <?php if($openAjaxUrl){?>onclick="return jQuery_Search_click('<?php echo $ajaxTypename;?>',$(this));"<?php }?> mod='<?php echo $sltkey?>'><img src="<?= IMAGES_HOST;?>/image/icons/icon_del.gif" /></a></span>
	  <input type="hidden" name="<?php echo $sltkey?>" value="<?php echo $sltval['id']?>" />
  <?php }}
 }
  ?>
  </form>
  <?php if(count($DataList)){ ?>
  <div id="SortToolBar" class="option <?php echo $ajaxTypename;?>_option_other">
          <?php
		  $this_modname = 'st';
		  $_SEARCH_DATA['SortType']=array();
		  $_SEARCH_DATA['SortType'][0]=array('key'=>'a','name'=>'升序');
		  $_SEARCH_DATA['SortType'][1]=array('key'=>'d','name'=>'降序');
          $_SEARCH_DATA['Sort']=$_SEARCH_DATA['SortTop']=array();
		  $_SEARCH_DATA['Sort'][1]=array('key'=>'d','name'=>'持续时间','top'=>'1');
		  $_SEARCH_DATA['Sort'][0]=array('key'=>'p','name'=>'价格','top'=>'1');
		  $_SEARCH_DATA['Sort'][2]=array('key'=>'o','name'=>'热销');
		  ?>
          <select mod='<?php echo $this_modname;?>' onChange="jQuery_Change_Sort(this)" class="<?php echo $ajaxTypename;?>_ajax">
            <option value="<?php echo makesearchUrl($this_modname,'');?>">---<?php echo db_to_html('默认排序');?>---</option>
			<?php
            foreach($_SEARCH_DATA['Sort'] as $_stv){
				foreach($_SEARCH_DATA['SortType'] as $_stpv){
					$_val = $_stv['key'].'_'.$_stpv['key'];
					$_name = db_to_html('按'.$_stv['name'].$_stpv['name']);
					$href = makesearchUrl($this_modname,$_val);
					$selected = ($_val==$$this_modname)?'selected':'';
					echo "<option value='{$href}' val='{$_val}' $selected>{$_name}</option>";
				}
				$_stv['top'] && $_SEARCH_DATA['SortTop'][]=$_stv;
			}
			?>
            </select>
            <?php
			$_temp_st = explode('_',$$this_modname.'');
            foreach($_SEARCH_DATA['SortTop'] as $_stvtop){
				$selected = '';
				$_val = $_stvtop['key'].'_a';
				if($_temp_st[0]==$_stvtop['key']){
					$selected = 'selected'.$_temp_st[1];
					$_val = $_stvtop['key'].'_'.($_temp_st[1]=='a'?'d':'a');
				}
				$href = makesearchUrl($this_modname,$_val);
			?>
            <div class="orderby <?php echo $selected;?>">
              <a href="<?php echo $href;?>" val="<?php echo $_val;?>" mod="<?php echo $this_modname;?>" class="<?php echo $ajaxTypename;?>_ajax"><?php echo db_to_html($_stvtop['name'])?></a>
            </div>
            <?php
			}
			?><div class="" style="float:right;color:#111;"><?php echo db_to_html('排序：')?></div>
          </div>
          <?php } ?>
</div>
<?php if($showSearchOptionUL && $openAjaxUrl){//此处开启ajax浏览 ?>
<script type="text/javascript">
jQuery().ready(function() {
	jQuery_Search_Init('<?php echo $ajaxTypename;?>');
});
</script>
<?php }?>
<div class="proList" id="<?php echo $ajaxTypename;?>_ResultPanel">
<?php
if(isset($ajax) && $ajax=='true' && $openAjaxUrl){
ob_end_clean();
include(DIR_FS_INCLUDES . 'application_top_gzip.php');
echo $total.'|###|';
echo $ajaxOutput['search_option'].'|###|';
}
//echo $querySQL;
//print_r($DataList);
if(count($DataList)){
?>
        <div class="proListTop" id="proListTop">
          <div class="page <?php echo $ajaxTypename;?>_option_other after_<?php echo $ajaxTypename;?>_load <?php echo $_pageType == 0 ? '' : 'page' . $_pageType ?>">
          <?php echo $pages;?>
          <div class="del_float"></div>
          </div>
          <?php /* 排序ToolBar Start { ?>
          <div class="option <?php echo $ajaxTypename;?>_option_other">
          <?php
		  $this_modname = 'st';
		  $_SEARCH_DATA['SortType']=array();
		  $_SEARCH_DATA['SortType'][0]=array('key'=>'a','name'=>'升序');
		  $_SEARCH_DATA['SortType'][1]=array('key'=>'d','name'=>'降序');
          $_SEARCH_DATA['Sort']=$_SEARCH_DATA['SortTop']=array();
		  $_SEARCH_DATA['Sort'][1]=array('key'=>'d','name'=>'持续时间','top'=>'1');
		  $_SEARCH_DATA['Sort'][0]=array('key'=>'p','name'=>'价格','top'=>'1');
		  $_SEARCH_DATA['Sort'][2]=array('key'=>'o','name'=>'热销');
		  ?>
          <select mod='<?php echo $this_modname;?>' onChange="jQuery_Change_Sort(this)" class="<?php echo $ajaxTypename;?>_ajax">
            <option value="<?php echo makesearchUrl($this_modname,'');?>">---<?php echo db_to_html('默认排序');?>---</option>
			<?php
            foreach($_SEARCH_DATA['Sort'] as $_stv){
				foreach($_SEARCH_DATA['SortType'] as $_stpv){
					$_val = $_stv['key'].'_'.$_stpv['key'];
					$_name = db_to_html('按'.$_stv['name'].$_stpv['name']);
					$href = makesearchUrl($this_modname,$_val);
					$selected = ($_val==$$this_modname)?'selected':'';
					echo "<option value='{$href}' val='{$_val}' $selected>{$_name}</option>";
				}
				$_stv['top'] && $_SEARCH_DATA['SortTop'][]=$_stv;
			}
			?>
            </select>
            <?php
			$_temp_st = explode('_',$$this_modname.'');
            foreach($_SEARCH_DATA['SortTop'] as $_stvtop){
				$selected = '';
				$_val = $_stvtop['key'].'_a';
				if($_temp_st[0]==$_stvtop['key']){
					$selected = 'selected'.$_temp_st[1];
					$_val = $_stvtop['key'].'_'.($_temp_st[1]=='a'?'d':'a');
				}
				$href = makesearchUrl($this_modname,$_val);
			?>
            <div class="orderby <?php echo $selected;?>">
              <a href="<?php echo $href;?>" val="<?php echo $_val;?>" mod="<?php echo $this_modname;?>" class="<?php echo $ajaxTypename;?>_ajax"><?php echo db_to_html($_stvtop['name'])?></a>
            </div>
            <?php
			}
			?><div class="" style="float:right;color:#111;">排序：</div>
          </div>
          <?php  } 排序ToolBar End */?>
<?php if(!$lasVegas){?>
<?php 
$productComparisonStr = '行程对比';
if($isHotels){
	$productComparisonStr = '产品对比';
}
?>
            <div class="compareBar" id="compareBar" style="visibility:hidden;">
             <div class="con">
               <h2><?php echo db_to_html($productComparisonStr)?></h2>
               <ul>
<?php
$maxChecked =3;
$prodcutDB = $_COOKIE['prodcutDB'];
!is_array($prodcutDB) && $prodcutDB=array();
$i=0;
foreach($prodcutDB as $pid=>$item){
	$i++;
	if($i>$maxChecked){
		$i--;
		unset($prodcutDB[$pid]);
		dCookie("prodcutDB[{$pid}][name]",'');
		dCookie("prodcutDB[{$pid}][no]",'');
	}elseif($item['name']=='' || $item['no']==''){
		$i--;
		unset($prodcutDB[$pid]);
		dCookie("prodcutDB[{$pid}][name]",'');
		dCookie("prodcutDB[{$pid}][no]",'');
	}else{
		$item['name'] = utf8tohtml($item['name']);
		$item['no'] = utf8tohtml($item['no']);
?>
                 <li onMouseOut="this.className=''" onMouseOver="this.className='over'" pdbid='<?php echo $pid;?>'><p><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $pid)?>" title="<?php echo $item['name']?>" target="product_win<?php echo $pid;?>"><?php echo $item['no']?></a></p><span><a href="javascript:void(0);" onClick="delProdcutItem('<?php echo $pid;?>');"><img src="image/icons/icon_del_red.gif"/></a></span></li>
<?php
	}
}
?>
               </ul>
               <a href="javascript:;" class="btn btnGrey btnCompareSmall"><button type="button" onclick="window.location.href='<?php echo tep_href_link('product_compare.php','');?>'"><?php echo db_to_html('去比较')?></button></a>
             </div>
            </div>
<?php }?>
        </div>
<?php 
//vincent 对夏威夷产品进行排序 start
//  1035，1036，1052，1053，157，913，727，1037，1038，1039 
//以上ID的数据会按顺序优先显示
if($cPathOnly == 33){
	//echo $cPathOnly;
	$orderArray  = array('1035'=>1, '1036'=>2,'1052'=>3,'1053'=>4,'157'=>5,'913'=>6,'727'=>7,'1037'=>8,'1038'=>9,'1039'=>10);
	$orderedDataList = array();
	foreach($DataList as $pkey=>$product){
		if(array_key_exists( $product['products_id'] ,$orderArray)){
			$orderedDataList[$orderArray[$product['products_id']]] = $product;
			unset($DataList[$pkey]);
		}
	}
	ksort($orderedDataList);
	$DataList = array_merge($orderedDataList,$DataList );
}
//对夏威夷首页进行排序 end
foreach($DataList as $pkey=>$product){
	$itemclass = $pkey%2 ?'proListCon proListConEven':'proListCon';
	//$targetname = 'product_win'.$product['products_id'];
	$targetname = '_self';
	$href = tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product['products_id']);
	$product['products_name'] = db_to_html($product['products_name']);
	$product['products_name1'] = db_to_html($product['products_name1']);
	$chkattr='';
	if($prodcutDB[$product['products_id']]){
		$itemclass .= ' proListConSelected';
		$chkattr = " ischecked='true' checked=true";
	}
?>
        <div class="<?php echo $itemclass;?>"  id="proListCon_<?php echo $product['products_id']?>">
          <h2><a href="<?php echo $href;?>" target="<?php echo $targetname?>"><?php echo $product['products_name']?></a></h2>
          <h3><?php echo str_replace('**','',$product['products_name1'])?></h3>
          <div class="left">
            <div class="pic">
			<?php
			//团购标签
			if((int)is_group_buy_product($product['products_id'])){
			?>
			<div class="listGroup"><?= db_to_html('团购');?></div>
			<?php
			}
			?>
              <?php if($product['gift']['num']){?>
              <div class="gift"<?php if($product['gift']['num']<=1) echo ' style="padding-top:1px;"';?>>
              <?php 
				  echo '<table><tbody><tr><td>'.$product['gift']['item'][$product['gift']['num']]['name'];
				  if($product['gift']['num']>1) echo db_to_html('<br /><span>更多优惠</span>');
				  echo '</td></tr></tbody></table>';
			  ?>
              </div>
              <?php }?>
              <div class="PicBorder"><div class="picbox"><a href="<?php echo $href;?>" target="<?php echo $targetname?>"><?php echo tep_image( get_thumbnails_fast($product['products_image'] = ((stripos( $product['products_image'],'http://')===false) ? 'images/':''). $product['products_image']), $product['products_name'].$product['products_name1'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) ;?></a></div>
              </div>
            </div>
            <ul>
            <li><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$product['products_id']);?>" target="<?php echo $targetname?>"><?php echo TEXT_TRAVEL_COMPANION_POSTS."(<span>{$product['companion']}</span>)"?></a></li>

            
            <li><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=reviews&products_id='.$product['products_id']);?>"  target="<?php echo $targetname?>"><?php echo TEXT_REVIEW."(<span>{$product['reviews']}</span>)"?></a></li>

            <!--<li><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=photos&products_id='.$product['products_id']);?>" target="<?php echo $targetname?>"><?php echo TEXT_PHOTOS."(<span>{$product['photos']}</span>)"?></a></li>-->
            <li><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$product['products_id']);?>" target="<?php echo $targetname?>"><?php echo db_to_html('满意度')."(<span>{$product['rating']}</span>)"?></a></li>
			

            <li><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=qanda&products_id='.$product['products_id']);?>"  target="<?php echo $targetname?>"><?php echo TEXT_QANDA."(<span>{$product['question']}</span>)"?></a></li>
            <?php 
             if(!$lasVegas){?>
            <li class="compare">
            	<div class="compareLeft" style="display:none">
            		<input id="pdb_chk_<?php echo $product['products_id']?>" type="checkbox" onClick="jQuery_ProdcutDB(&quot;<?php echo $product['products_id']?>&quot;,&quot;<?php echo dformatString($product['products_name']).dformatString($product['products_name1']);?>&quot;,&quot;<?php echo dformatString($product['products_model'])?>&quot;,&quot;<?php echo $href;?>&quot;); " <?php echo $chkattr;?> /> <?php echo db_to_html('比较');?>&nbsp;</div>
            <a target="product_compare" class="btn btnGrey btnCompareSmall" href="<?php echo tep_href_link('product_compare.php','');?>"><button type="button" onclick="window.location.href=&quot;<?php echo tep_href_link('product_compare.php','');?>&quot;"><?php echo db_to_html('去比较')?></button></a>
            </li>
            <?php }?>
            </ul>
          </div>
          <ul class="mid">
            <li>
              <h4><?php 
			  if ($isHotels) {
				  echo db_to_html('酒店编号：');
			  } else {
			  	echo db_to_html('旅游团号：');
			  }?></h4>
              <p><b><?php echo $product['products_model']?>&nbsp;</b></p>
            </li>
            <?php if($lasVegas){?>
            <li>
              <h4><?php echo db_to_html('表演场地：');?></h4>
              <p><?php echo db_to_html(tep_get_products_name($product['products_hotel_id']));?>&nbsp;</p>
            </li>
            <li>
              <h4><?php echo db_to_html('表演日期：');?></h4>
              <p><?php echo join('<br/>',$product['operate'])?>&nbsp;</p>
            </li>
            <li>
              <h4><?php echo db_to_html('观看年龄：');?></h4>
              <p><?php
			  if($product['min_watch_age']<1){
				  echo db_to_html('老少佳宜');
			  }else{
				  echo db_to_html($product['min_watch_age'].'岁以上');
			  }
			  ?></p>
            </li>
            <li>
              <h4><?php echo db_to_html('表演时长：');?></h4>
              <p><?php echo $product['products_durations'].' '.$product['products_durations_type']?>&nbsp;</p>
            </li>
            <?php }else{?>
			
			<?php
			if($isHotels){
			?>
			<li>
              <h4><?php echo db_to_html('酒店星级：');?></h4>
              <p class="stars<?= (int)$product['hotel_stars']?>"><?php echo db_to_html($product['hotel_stars']."星")?>&nbsp;</p>
            </li>
			<?php
				if((int)$product['meals_id']){
			?>
			<li>
              <h4><?php echo db_to_html('餐饮服务：');?></h4>
              <p><?php echo db_to_html(getHotelMealsOptions($product['meals_id']))?>&nbsp;</p>
            </li>
			<?php
				}
				if((int)$product['internet_id']){
			?>
			<li>
              <h4><?php echo db_to_html('网络服务：');?></h4>
              <p><?php echo db_to_html(getHotelInternetOptions($product['internet_id']))?>&nbsp;</p>
            </li>
			<?php
				}
				if((int)$product['approximate_location_id']){
			?>
			<li>
              <h4><?php echo db_to_html('酒店位置：');?></h4>
              <p><?php
			  //　酒店所在城市
			  echo db_to_html(join(', ',$product['s_city']));
			  
			  
			  
			   echo db_to_html(getHotelApproximateLocation($product['approximate_location_id']))?>&nbsp;
			  <?php if(tep_not_null($product['hotel_map'])){?>
			  <a id="aMaps<?= $product['products_id']?>" class="mapView" href="javascript:showHotelMap(<?= $product['products_id']?>,&quot;<?= dformatString($product['products_name'])?>&quot;);"><?= db_to_html("地图上查看");?></a>
			  <script type="text/javascript">
			  var MapsDatas = MapsDatas || new Array();
			  MapsDatas[<?= $product['products_id']?>] = '<?= preg_replace('@iframe width="\d+"@','iframe width="500"',preg_replace('/[[:space:]]+/',' ',db_to_html(tep_db_prepare_input($product['hotel_map']))));?>';
			  </script>
			  <?php
			  }
			  
			  ?>
			  
			  </p>
			  

			  
            </li>
			<?php
				}
				/*if(tep_not_null($product['hotel_phone'])){
			?>
			<li>
              <h4><?php echo db_to_html('酒店电话：');?></h4>
              <p><?php echo db_to_html($product['hotel_phone'])?>&nbsp;</p>
            </li>
			<?php
				}  */
				if(tep_not_null($product['hotel_address'])){
			?>
			<li>
              <h4><?php echo db_to_html('酒店地址：');?></h4>
              <p><?php echo db_to_html($product['hotel_address'])?>&nbsp;</p>
            </li>
			
			
			<?php
				}
				
				$departureCitysTitle = db_to_html("所在城市：");
				$class = 'display:none';
			}else{
				$departureCitysTitle = TEXT_DEPART_FROM; //出发地点
				$class = '';
			}
			
			if($isCruises){
				$departureCitysTitle = db_to_html("出发港口：");
				$class='';
			}
			?>
			<li style=" <?php echo $class?>">
              <h4><?php echo $departureCitysTitle;?></h4>
              <p><?php echo db_to_html(join(', ',$product['s_city']))?>&nbsp;</p>
            </li>
			
            <?php if(!$isHotels){
					$endCitysTitle = "结束城市：";
					if($isCruises){
						$endCitysTitle = "抵达港口：";
					}
			?>
			<li>
              <h4><?php echo db_to_html($endCitysTitle);?></h4>
              <p><?php echo db_to_html(join(', ',$product['e_city']))?>&nbsp;</p>
            </li>
            <li>
            	<?php 
			  	$TEXT_OPERATE = TEXT_OPERATE;
				if($isCruises){
					$TEXT_OPERATE = db_to_html("开航日期：");
				}
				?>
				<h4><?php echo $TEXT_OPERATE;//出团时间?></h4>
            	<p>
            		<?php  
					$date_arr = explode('、',$product['operate'][0]);
					if (count($date_arr) > 3) {
						$show=true;
						$before = array_splice($date_arr,0,3);
						echo join('、',$before);
						if (count($product['operate']) == 1){
							echo '<span class="more" onmouseover="jQuery(\'#MoreCon1' . $product['products_id'] . '\').show();" onmouseout="jQuery(\'#MoreCon1' . $product['products_id'] . '\').hide();">'; 
							echo '<a href="javascript:;" >' . db_to_html('查看全部') . '</a>'; 
							echo '<span id="MoreCon1' . $product['products_id'] . '" style="display:none" class="MoreCon"><span class="topArrow"></span><span class="con">';
							echo join('、',$date_arr);
							echo '</span><span id="tipBg"></span> 
									</span> 
								</span>';
						}
					} else {
						echo join('、',$date_arr);
					}
					//$product['operate'][0];?>
					<?php if(count($product['operate'])>1){
						//110815-2_订单详细页“查看全部”优化
						$all_operate = $product['operate'];
						$irregdate = trim($all_operate[count($all_operate) -1 ]);
						if(strpos($irregdate,'- ') === false){
							unset($all_operate[count($all_operate) -1 ]);
						}else{
							$irregdate = '';
						}
						$regdate = implode("<br />",$all_operate );
						?>
						<span class="more" onmouseover="jQuery('#MoreCon<?php echo $product['products_id']?>').show();" onmouseout="jQuery('#MoreCon<?php echo $product['products_id']?>').hide();"> 
							<a href="javascript:;" ><?php echo db_to_html('查看全部');?></a> 
							<span id="MoreCon<?php echo $product['products_id']?>" class="MoreCon"> 
								<span class="topArrow"></span><span class="con">									
								<?php 
								if ($show == true) {
									echo join('、',$date_arr);
								}
								$index = 1 ;
								if(trim($regdate) != '') {
									echo db_to_html('<strong>'.$index.'、常规日期</strong><br/>');
									echo $regdate;
									$index++;
								}
								if(trim($irregdate) != '') {
									echo db_to_html('<br/><strong>'.$index.'、特殊日期</strong><br/>');
									echo  $irregdate;
								}
								//echo  implode("<br/>" ,$product['operate']);?></span><span id="tipBg"></span> 
							</span> 
						</span> 
                       <?php }?>
             <?php /*
              echo $product['operate'][0]; //只显示时间段，不显示具体时间 0为时间段 1为具体时间 by lwkai modify 2012-10-23
			  if (isset($product['operate'][1]) && $product['operate'][1]) { // 显示具体时间段，显示状态为隐藏，点击可看 by lwkai modify 2012-12-04
			  ?>
			  <span onmouseout="jQuery('#MoreCon<?php echo $product['products_id']?>').hide();" onmouseover="jQuery('#MoreCon<?php echo $product['products_id']?>').show();" class="more"> 
								<a href="javascript:;"><?php echo db_to_html('查看全部')?></a> 
								<span id="MoreCon<?php echo $product['products_id']?>" class="MoreCon" style="display: none;"> 
									<span class="topArrow"></span><span class="con">									
									<strong><?php echo db_to_html('1、常規日期')?></strong><br><?php echo $product['operate'][0];?><br><strong><?php echo db_to_html('2、特殊日期')?></strong><br><?php echo $product['operate'][1];?></span><span id="tipBg" class="tipBg"></span> 
								</span> 
							</span>
			<?php
              }
              //echo join('<br/>',$product['operate'])*/?>&nbsp;</p>
            </li>
            <li style="display:none">
              <h4><?php echo db_to_html('持续时间：');?></h4>
              <p><?php echo $product['products_durations'].' '.$product['products_durations_type']?>&nbsp;</p>
            </li>
            <?php }?>
			<?php }?>
			<?php if($lasVegas){ 
				$lasVegasText = '介　　绍：';
			} else {
				$lasVegasText = '行程特色：';
			}
			if ($isHotels) {
				$lasVegasText = '酒店特色：';
			}?>
			<li>
              <h4><?php echo db_to_html($lasVegasText);?></h4>
              <p><?php echo cutword(db_to_html(strip_tags($product['products_small_description'])),128);?>&nbsp;</p>
            </li>
		  <?php
		  //大瀑布团签证提示 start
		  if($product['is_visa_passport'] > 0){
			if($product['is_visa_passport']==1){
				echo '<li class="NotReqVisa">'.TEXT_VISA_PASS_NOTREQ.'</li>';
			}
			if($product['is_visa_passport']==2){
				echo '<li class="NotReqVisa">'.TEXT_VISA_PASS_YREQ.'</li>';
			}
		  }
		  //大瀑布团签证提示 end
		  ?>
          </ul>
          <div class="right">
            <del><?php if($product['old_price']){echo $currencies->display_price($product['old_price'],tep_get_tax_rate($product['products_tax_class_id']));}?></del>
            <b><?php echo str_replace('.00','',$currencies->display_price($product['final_price'],tep_get_tax_rate($product['products_tax_class_id'])))?><span><?php echo db_to_html('起')?></span></b>
            <?php if($product['isSoldOut']){?>
            <?php /*<a href="javascript:;" onclick="productSoldOut('<?php echo $product['products_id'];?>')" class="btnA btnABlue btnAddCar" title="<?php echo db_to_html('点此输入您的E-mail地址，该团恢复预订时我们将发邮件通知您！');?>"><?php echo db_to_html('恢复预订通知');?></a>*/?>
            <a href="<?php echo $href;?>" class="btnA btnABlue btnAddCar"><?php echo db_to_html('恢复预订通知');?></a>
            <?php }elseif(!$isCruises){ ?>
            <?php /*<a id="add_cart_a_link_<?php echo $product['products_id'];?>" href="javascript:;" class="btn btnOrange btnAddCar" onClick="jQueryAddCart('<?php echo $product['products_id'];?>','<?php echo $product['display_room_option'];?>','<?php echo $product['min_num_guest'];?>');"><button type="button"><?php echo db_to_html('放入购物车');?></button></a> */ // 隐藏放入购物车 ?>
			<a href="<?php echo $href;?>" class="btn btnOrange btnAddCar" target="<?php echo $targetname?>"><?php $_str = (!$isHotels ? '预订行程':'预订酒店'); echo db_to_html($_str);?></a>
            <?php }else{ ?>
            <!--<a href="<?php echo $href;?>" class="btn btnOrange btnAddCar" ><button type="button"><?php echo db_to_html('放入购物车');?></button></a>-->
			<?php }?>
            <?php /*<a id="add_favorites_a_link_<?php echo $product['products_id'];?>" href="javascript:;" class="btn btnGrey btnAddCollect" onClick="jQueryAddFavorites('<?php echo $product['products_id'];?>');"><button type="button"><?php echo db_to_html('放入收藏夹');?></button></a> */ ?>
			<?php  
			// by lwkai add 赠积分 start {
            if ((USE_POINTS_SYSTEM == 'true') && (DISPLAY_POINTS_INFO == 'true')) {
				if(!in_array($product['products_id'], array_trim(explode(',',NOT_GIFT_POINTS_PRODUCTS)))){
					if ($new_price = tep_get_products_special_price($product['products_id'])) {
						$products_price_points = tep_display_points($new_price,tep_get_tax_rate($product['products_tax_class_id']));
					} else {
						$products_price_points = tep_display_points($product['products_price'],tep_get_tax_rate($product['products_tax_class_id']));
					}
					$products_points = tep_calc_products_price_points($products_price_points);
					$products_points = get_n_multiple_points($products_points , $product['products_id']);
					if ((USE_POINTS_FOR_SPECIALS == 'true') || $new_price == false) {
					?><span class="btn btnGrey btnAddCollect" style="border:1px solid #ccc;width:88px;text-align:center;cursor:default;"><?php echo db_to_html(sprintf('赠%s<a href="'.tep_href_link('points.php').'">积分</a>',number_format($products_points,POINTS_DECIMAL_PLACES)));#sprintf(TEXT_PRODUCT_POINTS ,number_format($products_points,POINTS_DECIMAL_PLACES));?></span>
					<?php
					}
				}
			}
			
			// 按Sofia的意思 先隐藏积分 end }
            ?>
            
          </div>
        </div>
<?php
}
?>
<div class="proListBot">
  <div class="page <?php echo $ajaxTypename;?>_option_other after_<?php echo $ajaxTypename;?>_load <?php echo $_pageType == 0 ? '' : 'page' . $_pageType ?>">
    <?php echo $pages;?>
  </div>
</div>

<?php if($openAjaxUrl){ ?>
<script type="text/javascript">
jQuery().ready(function() {
	jQuery_Search_Init_Other('<?php echo $ajaxTypename;?>');
});
</script>
<?php }?>
<?php
//已售完团提示
include('product_recoverybook.php');
?>
<div class="popup" id="addToCart">
  <table width="100%" cellpadding="0" cellspacing="0" border="0" class="popupTable">
    <tr>
      <td class="topLeft"></td><td class="side"></td><td class="topRight"></td></tr><tr><td class="side"></td>
        <td class="con">
          <div class="popupCon addSuccess" id="addToCartPanel" style="width:500px; ">
          	<div class="successTip">
                <div class="img"><img src="<?= DIR_WS_TEMPLATE_IMAGES;?>success.jpg"></div>
				<div class="words">
				<p><?php echo db_to_html('行程“');?><a href="" id="Cart_Pname"></a><?php echo db_to_html('”已经放入购物车。');?></p>
				<p><?php echo db_to_html('购物车中已有');?><font id='Cart_Sum'></font><?php echo db_to_html('个行程 共计：');?><span><b id='CartTotal'></b></span></p>
				</div>
            </div>
            <div class="errorTip"></div>
            <div class="popupBtn"><a href="<?php echo tep_href_link('shopping_cart.php','');?>" class="btn btnOrange"><?php echo db_to_html('进入购物车');?></a><a href="javascript:void(0);" class="btn  btn2 btnGrey btnOrange2" onclick="closePopup('addToCart');"><?php echo db_to_html('继续购物');?></button></a></div>

          </div>
      </td><td class="side"></td></tr><tr><td class="botLeft"></td><td class="side"></td><td class="botRight"></td></tr>
  </table>
</div>

<div class="popup" id="addToFavorites">
  <table width="100%" cellpadding="0" cellspacing="0" border="0" class="popupTable">
    <tr>
      <td class="topLeft"></td><td class="side"></td><td class="topRight"></td></tr><tr><td class="side"></td>
        <td class="con">
          <div class="popupCon addSuccess" id="addToFavoritesPanel" style="width:400px; ">
			<div class="successTip">
            	<div class="img"><img src="<?= DIR_WS_TEMPLATE_IMAGES?>success.jpg"></div>
				<div class="words">
					<p><?php echo db_to_html('行程“');?><a href="" id="Favorites_Pname"></a><?php echo db_to_html('”已经放入收藏夹。');?></p>
					<div id="Favorites_Content"></div>
				</div>
            </div>
			<div class="popupBtn">
				<a href="javascript:;" class="btn btnOrange"><button type="button" onclick="window.location.href='<?php echo tep_href_link('my_favorites.php','');?>'"><?php echo db_to_html('进入收藏夹');?></button></a><a href="javascript:void(0);" class="btn btnGrey" onclick="closePopup('addToFavorites');"><button type="button"><?php echo db_to_html('继续购物');?></button></a>
			</div>

          </div>
      </td><td class="side"></td></tr><tr><td class="botLeft"></td><td class="side"></td><td class="botRight"></td></tr>
  </table>
</div>
<?php if(!$lasVegas){?>
<div class="popup" id="popupTip">
  <table width="100%" cellpadding="0" cellspacing="0" border="0" class="popupTable">
    <tr>
      <td class="topLeft"></td><td class="side"></td><td class="topRight"></td></tr><tr><td class="side"></td>
        <td class="con">
          <div class="popupCon" id="popupConCompare" style="width:490px; ">
            <div class="popupConTop">
              <h3><b><?php echo db_to_html($productComparisonStr);?></b><?php echo db_to_html('(您已经选择了'.$maxChecked.'条产品，目前只支持'.$maxChecked.'条'.$productComparisonStr.')');?></h3><span><a href="javascript:closePopup('popupTip')"><img src="<?= DIR_WS_ICONS;?>icon_x.gif" /></a></span>
            </div>
            <ul>
<?php
foreach($prodcutDB as $pid=>$item){
	$item['name'] = utf8tohtml($item['name']);
?>
                <li onMouseOut="this.className=''" onMouseOver="this.className='over'" pdbid='<?php echo $pid;?>'><p><?php echo $item['name']?></p><span><a href="javascript:void(0);" onClick="javascript:delProdcutItem('<?php echo $pid;?>');"><img src="image/icons/icon_del_red.gif" /></a></span></li>
<?php
}
?>
            </ul>
			<div class="popupBtn">
            <a href="javascript:;" class="btn btnOrange btnCompare"><button type="button" onclick="window.location.href='<?php echo tep_href_link('product_compare.php','');?>'"><?php echo db_to_html('开始对比');?></button></a></div>
          </div>
      </td><td class="side"></td></tr><tr><td class="botLeft"></td><td class="side"></td><td class="botRight"></td></tr>
  </table>
</div>
<!--
<div id="popupBg" class="popupBg">
<iframe scrolling="no" height="100%" width="100%" marginwidth="0" marginheight="0" frameborder="0" id="popupBgIframe"></iframe>
</div>
-->
<script type="text/javascript"> 
var _ProdcutDB_Num = <?php echo count($prodcutDB);?>;
var maxChecked = <?php echo $maxChecked;?>;
</script>
<script type="text/javascript" language="javascript" src="<?php echo DIR_WS_JAVASCRIPT;?>prodcut.list.js"></script>
<?php }?>

<?php
}else{
	if($lasVegas){
		echo db_to_html('<font color=red>对不起，没有找到信息！</font>');
	}else{
		include(DIR_FS_MODULES .'search_null.php');
	}
}
if(isset($ajax) && $ajax=='true' && $openAjaxUrl){
ob_end_flush();
exit;
}
?>
</div>
<?php
if($openAjaxUrl){
?>
<script language="javascript">
jQuery(document).ready(function(){
	jQueryAutoLoad('<?php echo $ajaxTypename;?>');
});
</script>
<?php
}
?>

<?php if($isHotels){?>
			  <div class="popup" id="popupMapInfo">
				<table width="100%" cellpadding="0" cellspacing="0" border="0" class="popupTable">
				<tr><td class="topLeft"></td><td class="side"></td><td class="topRight"></td></tr><tr><td class="side"></td><td class="con">
				
				  <div class="popupCon" id="popupMapConInfo" style="width:500px;">
					<div class="popupConTop" id="dragMapInfo">
					  <h3><b><?= db_to_html("酒店地图")?></b></h3><span onclick="closePopup('popupMapInfo')"><img src="<?= DIR_WS_ICONS;?>icon_x.gif" alt="关闭" title="关闭" /></span>
					</div>
					
					<p id="googleMap" class="myUnionHelp">酒店地图代码框</p>
				   
				  </div>
				  
				</td><td class="side"></td></tr><tr><td class="botLeft"></td><td class="side"></td><td class="botRight"></td></tr>
				</table>
				</div>
				
				<script type="text/javascript">
				function showHotelMap(productsId,h3Title){
					jQuery('#googleMap').html(MapsDatas[productsId]);
					jQuery('#popupMapInfo h3').html('<b><?= db_to_html("酒店地图：")?>'+h3Title+'</b>');
					showPopup('popupMapInfo','popupMapConInfo','off');
				}
				//设置弹出层顶部拖曳 
				new divDrag([GetIdObj('dragMapInfo'),GetIdObj('popupMapInfo')]);
				</script>
<?php } ?>