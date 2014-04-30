<?php
ob_start();
$cruisesStartDateOptions = str_replace('请选择您的出发日期', '请选择出航日期', html_to_db($avaliabledate));
if(is_array($cruisesData)){
?>
  <!-- 邮轮价格明细开始 -->
  <div class="cruisePrices">
	<div class="condition"><span>以下价格仅供参考，房间人数不同，价格亦有不同，最终价格请以我们最终与您确认的为准！</span></div>
	<?php foreach((array)$cruisesData['cabins'] as $key => $cabins){ //客舱循环?>

	<table border="0" cellpadding="0" cellspacing="0" class="cabin">
	  <tr class="title">
		<td rowspan="9" class="cabinDes"><ul>
			<li>
			  <h4><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=cruisesIntroduction&'.tep_get_all_get_params(array('info','mnu','page'))).'#anchorCabin';?>"><?= tep_db_output($cabins['cruises_cabin_name']);?></a></h4>
			</li>
			<li><a target="_blank" href="<?= tep_db_output($cabins['images'][0]['images_url']);?>"><img src="<?= tep_db_output($cabins['images'][0]['images_url_thumb_min']);?>" alt="客舱内景" title="客舱内景" /></a></li>
			<li><?= tep_db_output($cabins['cruises_cabin_content']);?></li>
		  </ul></td>
		<td>分类</td>
		<td>甲板</td>
		<td>价格/人</td>
		<td>描述<i>(点击查看)</i></td>
		<td>&nbsp;</td>
	  </tr>
	  
	<?php
	$ln=0;
	foreach((array)$cabins['decks'] as $key1 => $decks){
		$ln++;
		$tr_class="tableColor";
		if($ln>0 && $ln%2==0){
			$tr_class="";
		}
		$deck_type = preg_replace('@:.*$@','',$decks['cruises_cabin_deck_name']);
		$deck_name = preg_replace('@^.*:@','',$decks['cruises_cabin_deck_name']);
		$detailedPrice = $currencies->display_price($decks['options_values_price'], $tax_rate_val_get);	//详细标准价
		if($product_info['display_room_option']=="1"){
			$detailedPrice = '单人:'.$currencies->display_price($decks['single_values_price'], $tax_rate_val_get).' ';	//详细标准价1人
			$detailedPrice .= '两人:'.$currencies->display_price($decks['double_values_price'], $tax_rate_val_get).' ';	//详细标准价2人
			$detailedPrice .= '三人:'.$currencies->display_price($decks['triple_values_price'], $tax_rate_val_get).' ';	//详细标准价3人
			$detailedPrice .= '四人:'.$currencies->display_price($decks['quadruple_values_price'], $tax_rate_val_get).' ';	//详细标准价3人
			$detailedPrice .= '小孩:'.$currencies->display_price($decks['kids_values_price'], $tax_rate_val_get).' ';	//详细标准价儿童
		}
	?>
	  <tr class="<?= $tr_class;?>">
		<td><?= $deck_type?></td>
		<td><p><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=cruisesIntroduction&'.tep_get_all_get_params(array('info','mnu','page'))).'#anchorDeck';?>"><?= $deck_name?></a></p></td>
		<td><span><?= $currencies->display_price($decks['optionsValuesMinPrice'], $tax_rate_val_get)?> <?php if($product_info['display_room_option']=="1") echo "起";?></span></td>
		<td><span><a href="javascript:;" onclick="javascript:showPopupDecks('viewDeck_<?= $decks['products_options_values_id']?>');">查看</a></span>
		<div id="viewDeck_<?= $decks['products_options_values_id']?>" style="display:none">
		<?= tep_db_output($decks['cruises_cabin_deck_content']);?>
		<hr />
		入住人数：<?= $decks['min_num_guest']?> - <?= $decks['max_per_of_guest']?>人。
		<hr />
		详细价格：<?= $detailedPrice;?>
		</div>
		</td>
		<td><strong><a href="javascript:void(0)" onclick="AddToCartForCruises(<?= $decks['products_options_id']?>, <?= $decks['products_options_values_id']?>, <?= $decks['min_num_guest']?>, <?= $decks['max_per_of_guest']?>);">去预订&gt;&gt;</a></strong></td>
	  </tr>
	  
	  <?php }?>
	<?php
	//如果不够6行，则补够6行
	if($ln<6){
		for($i=$ln; $i<6; $i++){
		$tr_class="";
		if($i>0 && $i%2==0){
			$tr_class="tableColor";
		}
	?>  
	  <tr class="<?= $tr_class;?>">
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	  </tr>
	<?php
		}
	}
	?>
	</table>
	<?php } ?>
	<div class="explain">
	<strong>政府收费和税收: <span><?= tep_db_output($cruisesData['tax']);?></span></strong>
	<?= tep_db_output($cruisesData['tax_content']);?>
	</div>
  </div>
  <!-- 邮轮价格明细结束 -->
  
	<div class="popup" id="PopupCabinDescriptions">
            <table cellpadding="0" cellspacing="0" border="0" class="popupTable">
              <tr>
                <td class="topLeft"></td>
                <td class="side"></td>
                <td class="topRight"></td>
              </tr>
              <tr>
                <td class="side"></td>
                <td class="con"><div class="popupCon" id="PopupCabinDescriptionsCon" >
                    <div class="popupTitle" id="drag">
                      <div class="popupTitleCon"><b>甲板详细描述</b></div>
                      <div class="popupClose" id="PopupCabinDescriptionsClose" onclick="closePopup('PopupCabinDescriptions')"></div>
                    </div>
                    <div class="description" style="width:500px;">此处存放甲板详细描述和价格信息</div>
                    <div class="btnCenter"> <a href="javascript:void(0);"  class="btn btnGrey" ><button type="button" onclick="closePopup('PopupCabinDescriptions')">关 闭</button></a> </div>

                  </div></td>
                <td class="side"></td>
              </tr>
              <tr>
                <td class="botLeft"></td>
                <td class="side"></td>
                <td class="botRight"></td>
              </tr>
            </table>
          </div>
<script type="text/javascript">
function showPopupDecks(sroceId){
    var PopupDecks = new showPopup('PopupCabinDescriptions','PopupCabinDescriptionsCon','PopupCabinDescriptionsClose',{dragId:"drag"});
	jQuery("#PopupCabinDescriptionsCon .description").html(jQuery("#"+sroceId).html());
}

<?php //加入到购物车，邮轮专用?>
function AddToCartForCruises(optionId, optionValueId, roomMinPer, roomMaxPer){
	
	var error = false;
	var errorMsg = '';
	/*
	var dateBox = document.getElementById('availabletourdate');
	if(dateBox==null || dateBox.value.length<10){
		errorMsg +="请选择出航日期！"+"\n";
		error = true;
	}
	var perObj = document.getElementById('room-0-adult-total');
	if(perObj==null || perObj.value<1){
		errorMsg +="请选择人数！"+"\n";
		error = true;
	}
	*/
	var radioOption = document.getElementById("radioid_"+optionId+"__"+optionValueId);
	if(radioOption==null){
		errorMsg +="无法找到该甲板！"+"\n";
		error = true;
	}
	
	if(error == true){
		alert(errorMsg);
		return false;
	}
	
	//自动打开房间选择器，以及限制房间人数
	jQuery('#ConTitleA_hot-search-params').trigger('click');
	
	//}
	/*id="radioid_187__1019" name="id[187]" value="1019"*/
	//自动确认客舱甲板分类信息{
	cleanAllCruisesOptionForBookingBox();
	jQuery('#radioid_'+optionId+'__'+optionValueId).attr("checked", true);
	selected_radio_deck(document.getElementById("deckTd_"+optionId+'__'+optionValueId), roomMinPer, roomMaxPer);
	ConfirmDeckInfo();
	//重新设置房间
	setNumRooms(1);
	
	//自动执行设置第二步的税及其它产品选项的默认值
	var conTitleProductsOptions = jQuery("div [id^='ConTitle_ProductsOptions']");
	for(var i=0; i<conTitleProductsOptions.length; i++){
		var Steps2Num = conTitleProductsOptions[i].id.replace('ConTitle_ProductsOptions','');
		if(parseInt(Steps2Num,10) > 0){
			SetShowSteps2(parseInt(Steps2Num,10));
		}
	}
	jQuery("html,body").animate({scrollTop:jQuery('#product_book_module').position().top}); //到booking盒
	//}
	
	//AddToCart();
}


</script>
 
 <?php
}
echo db_to_html(ob_get_clean());
//print_vars($cruisesData);
?>