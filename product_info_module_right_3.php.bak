<?php 
//标准价格明细 start {
if($product_info['is_transfer'] != '1') {
if($product_info['display_room_option'] == '1'){
	//根据美国团、欧洲团、日本团添加床型提示
	#$tips_tpl_html = '<a href="javascript:void(0);" class="tipslayer sp3" style="font-weight: normal;">[?]<span>%s</span></a>';
	$tips_tpl_html = '<span style="display:block;z-index:999;">%s</span>';
	$single_pu_tips = sprintf($tips_tpl_html, TEXT_TOUR_SINGLE_PU_OCC_TIPS);
	$single_bed_tips = sprintf($tips_tpl_html, db_to_html('1张Queen sized大床<br />(152 x 203厘米)<br/>或1张King sized大床<br/>(193 x 203厘米)'));
	$double_bed_tips = sprintf($tips_tpl_html, db_to_html('2张Full sized小型双人床<br/>(137 x 190厘米)<br/>或1张Queen sized大床<br/>(152 x 203厘米)'));
	$triple_bed_tips = sprintf($tips_tpl_html, db_to_html('2张Full sized小型双人床<br/>(137 x 190厘米)'));
	$quadr_bed_tips = $triple_bed_tips;
	$child_bed_tips = '';
	if($cPathOnly=='195'){	//日本团
		$single_bed_tips = sprintf($tips_tpl_html, db_to_html('房间内提供一张大床，<br />100-120cm×190-210cm'));
		$double_bed_tips = sprintf($tips_tpl_html, db_to_html('房间内提供两张Full size床，<br />150-180cm×190-210cm'));
		$triple_bed_tips = sprintf($tips_tpl_html, db_to_html('房间内提供两张Full size床，<br />150-180cm×190-210cm'));
		$quadr_bed_tips ='';
	}
	if($cPathOnly=='157'){	//欧洲团
		$single_bed_tips = sprintf($tips_tpl_html, db_to_html('房间内提供一张加长单人床，<br />X-Long : 39 × 80 inches (99 x 203 cm)'));
		$double_bed_tips = sprintf($tips_tpl_html, db_to_html('房间内提供两张Twin size床，<br />Two beds: 39 x 75 inches (or 99 x 190 cm)'));
		$triple_bed_tips = sprintf($tips_tpl_html, db_to_html('房间内提供两张 Twin size床，（可添加一张Roll-Away）<br />Two beds: 39 x 75 inches (or 99 x 190 cm)
'));
		$quadr_bed_tips ='';
	}
	if(intval($product_info['agency_id']) == 88){
		$single_pu_tips = $single_bed_tips = $double_bed_tips = $triple_bed_tips = $quadr_bed_tips = '';
	}
?>

<h2 style="display:none"><?php echo TEXT_HEADING_REGULAR_SPECIAL_PRICE;?>:</h2>	
<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('a.tipslayer').hover(function(){
		jQuery(this).find('span').css('top',jQuery(this).height());
		jQuery(this).find('span').show();	
	},function(){
		jQuery(this).find('span').hide();	
	});	
});
</script>			
<table width="100%" cellspacing="0" cellpadding="0" border="0" class="price">
	<tbody><tr>
			<?php if($product_info['products_double'] > 0) { ?>
			<th><a href="javascript:void(0)" class="tipslayer sp3 lwk_sp3"><?php echo TEXT_TOUR_DOU_OCC_PRICE;?><?php echo $double_bed_tips;?></a></th>
			<?php }?>
				
			<?php if($product_info['products_triple'] > 0) { ?>
			<th><a href="javascript:void(0)" class="tipslayer sp3 lwk_sp3"><?php echo TEXT_TOUR_TRI_OCC_PRICE;?><?php echo $triple_bed_tips;?></a></th>
			<?php }?>
				
			<?php if($product_info['products_quadr'] > 0) { ?>
			<th><a href="javascript:void(0)" class="tipslayer sp3 lwk_sp3"><?php echo TEXT_TOUR_QUAD_OCC_PRICE;?><?php echo $quadr_bed_tips;?></a></th>
			<?php }?>
				
			<?php if($product_info['products_single'] > 0) { ?>
			<th><a href="javascript:void(0)" class="tipslayer sp3 lwk_sp3"><?php echo TEXT_TOUR_SINGLE_OCC_PRICE;?><?php echo $single_bed_tips;?></a></th>
			<?php }?>
			<?php if($product_info['products_single_pu'] > 0) { ?>
			<th><a href="javascript:void(0)" class="tipslayer sp3 lwk_sp3"><?php echo TEXT_TOUR_SINGLE_PU_OCC_PRICE;?><?php echo $single_pu_tips;?></a></th>
			<?php }?>
				
			<?php if($product_info['products_kids'] > 0) { ?>
			<th><?php echo TEXT_TOUR_KIDS_OCC_PRICE;?><!--<a href="javascript:void(0)" class="tipslayer sp3 lwk_sp3"><?php echo TEXT_TOUR_KIDS_OCC_PRICE;?><?php echo $child_bed_tips ?></a>--></th>
			<?php }?>
				
			</tr>

		<tr>
			<?php if($product_info['products_double'] > 0) { ?>
			<td>
			<?php echo str_replace('/','<br />',preg_replace("/\.00$/",'',$currencies->display_price($product_info['products_double'],$tax_rate_val_get))); echo db_to_html('/人');?>
			<?php 
			if(tep_not_null($product_info['oldProductsDouble'])){
				echo '<br><del>'.db_to_html('原价：').str_replace('/','<br />',preg_replace("/\.00$/",'',$currencies->display_price($product_info['oldProductsDouble'],$tax_rate_val_get))).db_to_html('/人').'</del>';
			}
			?>
			</td>
			<?php }?>
					
			<?php if($product_info['products_triple'] > 0) { ?>
			<td>
			<?php echo str_replace('/','<br />',preg_replace("/\.00$/",'',$currencies->display_price($product_info['products_triple'],$tax_rate_val_get))); echo db_to_html('/人');?>
			<?php 
			if(tep_not_null($product_info['oldProductsTriple'])){
				echo '<br><del>'.db_to_html('原价：').str_replace('/','<br />',preg_replace("/\.00$/",'',$currencies->display_price($product_info['oldProductsTriple'],$tax_rate_val_get))).db_to_html('/人').'</del>';
			}
			?>
			</td>
			<?php }?>
					
			<?php if($product_info['products_quadr'] > 0) { ?>
			<td>
			<?php echo str_replace('/','<br />',preg_replace("/\.00$/",'',$currencies->display_price($product_info['products_quadr'],$tax_rate_val_get))); echo db_to_html('/人');?>
			<?php 
			if(tep_not_null($product_info['oldProductsQuadr'])){
				echo '<br><del>'.db_to_html('原价：').str_replace('/','<br />',preg_replace("/\.00$/",'',$currencies->display_price($product_info['oldProductsQuadr'],$tax_rate_val_get))).db_to_html('/人').'</del>';
			}
			?>
			</td>
			<?php }?>
					
			<?php if($product_info['products_single'] > 0) { ?>
			<td>
			<?php echo str_replace('/','<br />',preg_replace("/\.00$/",'',$currencies->display_price($product_info['products_single'],$tax_rate_val_get))); echo db_to_html('/人');?>
			<?php 
			if(tep_not_null($product_info['oldProductsSingle'])){
				echo '<br><del>'.db_to_html('原价：').str_replace('/','<br />',preg_replace("/\.00$/",'',$currencies->display_price($product_info['oldProductsSingle'],$tax_rate_val_get))).db_to_html('/人').'</del>';
			}
			?>
			</td>
			<?php }?>
					
			<?php if($product_info['products_single_pu'] > 0) { ?>
			<td>
			<?php echo str_replace('/','<br />',preg_replace("/\.00$/",'',$currencies->display_price($product_info['products_single_pu'],$tax_rate_val_get))); echo db_to_html('/人');?>
			<?php 
			if(tep_not_null($product_info['oldProductsSinglePu'])){
				echo '<br><del>'.db_to_html('原价：').str_replace('/','<br />',preg_replace("/\.00$/",'',$currencies->display_price($product_info['oldProductsSinglePu'],$tax_rate_val_get))).db_to_html('/人').'</del>';
			}
			?>
			</td>
			<?php }?>
					
			<?php if($product_info['products_kids'] > 0) { ?>
			<td>
			<?php echo str_replace('/','<br />',preg_replace("/\.00$/",'',$currencies->display_price($product_info['products_kids'],$tax_rate_val_get))); echo db_to_html('/人');?>
			<?php 
			if(tep_not_null($product_info['oldProductsKids'])){
				echo '<br><del>'.db_to_html('原价：').str_replace('/','<br />',preg_replace("/\.00$/",'',$currencies->display_price($product_info['oldProductsKids'],$tax_rate_val_get))).db_to_html('/人').'</del>';
			}
			?>
			</td>
			<?php }?>
					
		</tr>
</tbody></table>

<?php
}else{
?>
<h2><?php echo TEXT_HEADING_REGULAR_SPECIAL_PRICE?>:</h2>

<table cellspacing="0" cellpadding="0" border="0" class="price">
	<tr>
		<th>
		<?php if($isTickets){
			echo db_to_html("普通票价格").'<a class="tipslayer sp3" onmouseover="jQuery(&quot;#ptTickets1&quot;).show();" onmouseout="jQuery(&quot;#ptTickets1&quot;).hide();" style="z-index:1;">[?]
              <span class="tip" id="ptTickets1" style="display: none;">'.db_to_html("普通票价格适用于所有3岁及以上，身高等于或高于122cm(48inches）的游客。").'</span></a>';?>
        <?php }else{echo db_to_html("成人:");} //echo TEXT_AGE_11_OR_UP;?>
        </th>
		<th><?php echo $currencies->display_price($product_info['products_single'],$tax_rate_val_get); ?></th>
	</tr>														
	<tr>	
		<?php if($product_info['products_kids'] > 0) { ?>
		<td><?php if($isTickets){
			echo db_to_html("折扣票价格").'<a class="tipslayer sp3" style=" position:relative;" onmouseover="jQuery(&quot;#ptTickets2&quot;).show();" onmouseout="jQuery(&quot;#ptTickets2&quot;).hide();">[?]
              <span id="ptTickets2" style="display: none; background:#FFFFFF; border: 1px solid #52AADE;left: 0;line-height: 18px; padding: 5px;position: absolute; top:18px;width: 260px; ">'.db_to_html("由于个别骑乘项目的限制，入园时身高在122 cm（48inches） 以下的游客可享受折扣票价格，入园时将进行身高测量。<br>两岁及两岁以下儿童免费。").'</span></a>';?>
        <?php }else{echo db_to_html("小孩:");}?></td>
		<td><?php echo $currencies->display_price($product_info['products_kids'],$tax_rate_val_get); ?></td>
		<?php } ?>		
	</tr>
</table>													
<?php
}
//标准价格明细 end }
?>

<?php
//sections standard prices if exists
			$sections_standard_price = tep_db_query("select * from ".TABLE_PRODUCTS_REG_IRREG_STANDARD_PRICE." where products_id = '".(int)$HTTP_GET_VARS['products_id']."' and products_single > 0 and date_format(str_to_date(operate_end_date, '%m-%d-%Y'), '%Y-%m-%d') >= now()");
			if(tep_db_num_rows($sections_standard_price) > 0){
			while($row_sections_standard_prices = tep_db_fetch_array($sections_standard_price)){
			
			$tour_agency_opr_currency = tep_get_tour_agency_operate_currency($_GET['products_id']);
			if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
				$row_sections_standard_prices['products_price'] = tep_get_tour_price_in_usd($row_sections_standard_prices['products_price'],$tour_agency_opr_currency);
				$row_sections_standard_prices['products_single'] = tep_get_tour_price_in_usd($row_sections_standard_prices['products_single'],$tour_agency_opr_currency);
				$row_sections_standard_prices['products_single_pu'] = tep_get_tour_price_in_usd($row_sections_standard_prices['products_single_pu'],$tour_agency_opr_currency);
				$row_sections_standard_prices['products_double'] = tep_get_tour_price_in_usd($row_sections_standard_prices['products_double'],$tour_agency_opr_currency);
				$row_sections_standard_prices['products_triple'] = tep_get_tour_price_in_usd($row_sections_standard_prices['products_triple'],$tour_agency_opr_currency);
				$row_sections_standard_prices['products_quadr'] = tep_get_tour_price_in_usd($row_sections_standard_prices['products_quadr'],$tour_agency_opr_currency);
				$row_sections_standard_prices['products_kids'] = tep_get_tour_price_in_usd($row_sections_standard_prices['products_kids'],$tour_agency_opr_currency);
			}
		 ?>
		 <table width="100%"  border="0" cellspacing="0" cellpadding="0">													
			<tr>
			<td  class="main"><b><?php echo tep_get_display_reg_special_picing_title('',$row_sections_standard_prices['operate_start_date'],$row_sections_standard_prices['operate_end_date']);?></b></td>													
			</tr>
			</table>		
		 <?php 
			if($product_info['display_room_option'] == '1')
			{
				?>
				<table class="price"  width="98%"  border="0" cellspacing="0" cellpadding="4">
					<tr>
						<?php if($row_sections_standard_prices['products_double'] > 0) { ?>
					<th><?php echo TEXT_TOUR_DOU_OCC_PRICE;?></th>
					<?php } ?>		
					<?php if($row_sections_standard_prices['products_triple'] > 0) { ?>
					<th><?php echo TEXT_TOUR_TRI_OCC_PRICE;?></th>
					<?php } ?>												 
					<?php if($row_sections_standard_prices['products_quadr'] > 0) { ?>
					<th><?php echo TEXT_TOUR_QUAD_OCC_PRICE;?></th>
					<?php } ?>
					<?php if($row_sections_standard_prices['products_single'] > 0) { ?>	
					<th><?php echo TEXT_TOUR_SINGLE_OCC_PRICE;?></th>
					<?php } ?>
                    <?php if($row_sections_standard_prices['products_single_pu'] > 0) { ?>	
                    <th><?php echo TEXT_TOUR_SINGLE_PU_OCC_PRICE;?><a href="javascript:void(0);" class="tipslayer sp3" style="font-weight: normal;">[?]<span><?php echo TEXT_TOUR_SINGLE_PU_OCC_TIPS;?></span></a></th>
                    <?php } ?>
					<?php if($row_sections_standard_prices['products_kids'] > 0) { ?>
					<th><?php echo TEXT_TOUR_KIDS_OCC_PRICE;?></th>
					<?php } ?>			
					</tr>
					<tr>
					<?php if($row_sections_standard_prices['products_double'] > 0) { ?>
					<td><span class="sp1">$<?php echo $row_sections_standard_prices['products_double']; ?></span></td>
					<?php } ?>		
					<?php if($row_sections_standard_prices['products_triple'] > 0) { ?>
					<td><span class="sp1">$<?php echo $row_sections_standard_prices['products_triple']; ?></span></td>
					<?php } ?>												 
					<?php if($row_sections_standard_prices['products_quadr'] > 0) { ?>
					<td><span class="sp1">$<?php echo $row_sections_standard_prices['products_quadr']; ?></span></td>
					<?php } ?>
					<?php if($row_sections_standard_prices['products_single'] > 0) { ?>	
					<td><span class="sp1">$<?php echo $row_sections_standard_prices['products_single']; ?></span></td>
					<?php } ?>
                    <?php if($row_sections_standard_prices['products_single_pu'] > 0) { ?>	
					<td><span class="sp1">$<?php echo $row_sections_standard_prices['products_single_pu']; ?></span></td>
					<?php } ?>
					<?php if($row_sections_standard_prices['products_kids'] > 0) { ?>
					<td><span class="sp1">$<?php echo $row_sections_standard_prices['products_kids']; ?></span></td>
					<?php } ?>			
					</tr>
				</table>

				<?php
			}
			else
			{?>
			
			<table class="SpecialPriceTableBorder" width="60%"  border="0" cellspacing="0" cellpadding="4">
					<tr class="TrSpecialPriceTableBorder">
						<td><?php echo TEXT_AGE_11_OR_UP;?></td>
					<?php if($row_sections_standard_prices['products_kids'] > 0) { ?>
					<td><?php echo TEXT_AGE_2_TO_10;?></td>
					<?php } ?>		
					</tr>														
					<tr>														  	
					<td><span class="sp1">$<?php echo $row_sections_standard_prices['products_single']; ?></span></td>
					<?php if($row_sections_standard_prices['products_kids'] > 0) { ?>

					<td><span class="sp1">$<?php echo $row_sections_standard_prices['products_kids']; ?></span></td>
					<?php } ?>		
					</tr>
				</table>													
			<?php
			}		
			?>
		
		 <?php
		 }//end while
		 } // end if sections standard prices
		 ?>
		   
<?php if($display_pricing_for_special_price == 'true' || $display_pricing_for_special_price_attribute == 'true' || $display_pricing_for_reg_special_price == 'true'){ //start of special pricing notes?>
<?php												  
//start of departure date special price
if($display_pricing_for_special_price == 'true') {
?>
<h2><?php echo TEXT_HEADING_DEPARTURE_DATE_HOLIDAY_PRICE;?>:</h2>
<script type="text/javascript">
jQuery(document).ready(function(e) {
    jQuery('ul.J-ptabMenu > li').click(function(){
		jQuery('ul.J-ptabMenu > li').removeClass('current');
		jQuery(this).addClass('current');
		jQuery('div.J-ptabCont > div').hide();
		jQuery('div.J-ptabCont > div').eq(jQuery('ul.J-ptabMenu > li').index(jQuery(this))).show();	
	});
});
</script>
<div class="pr_c_p">		
<?php
$dis_special_price_available_query_sql = "select * from ".TABLE_PRODUCTS_REG_IRREG_DATE." where products_id = ".(int)$HTTP_GET_VARS['products_id']." and  (spe_single > 0 or  spe_single_pu > 0 OR spe_double > 0 OR spe_triple > 0 OR spe_quadruple > 0 OR spe_kids > 0  ) and available_date !='' and available_date > now()  order by available_date";
$dis_special_price_available_query = tep_db_query($dis_special_price_available_query_sql);
$i = 0;
$temp_html = '';
while($dis_special_price_available_result = tep_db_fetch_array($dis_special_price_available_query))
{	
//amit modified to make sure price on usd
if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
$dis_special_price_available_result['spe_single'] = tep_get_tour_price_in_usd($dis_special_price_available_result['spe_single'],$tour_agency_opr_currency);
$dis_special_price_available_result['spe_single_pu'] = tep_get_tour_price_in_usd($dis_special_price_available_result['spe_single_pu'],$tour_agency_opr_currency);
$dis_special_price_available_result['spe_double'] = tep_get_tour_price_in_usd($dis_special_price_available_result['spe_double'],$tour_agency_opr_currency);
$dis_special_price_available_result['spe_triple'] = tep_get_tour_price_in_usd($dis_special_price_available_result['spe_triple'],$tour_agency_opr_currency);
$dis_special_price_available_result['spe_quadruple'] = tep_get_tour_price_in_usd($dis_special_price_available_result['spe_quadruple'],$tour_agency_opr_currency);
$dis_special_price_available_result['spe_kids'] = tep_get_tour_price_in_usd($dis_special_price_available_result['spe_kids'],$tour_agency_opr_currency);
}
//amit modified to make sure price on usd	
?>

	<?php //onClick="javascript:toggel_div(\'dis_special_price_available_' . $dis_special_price_available_result['products_start_day_id'] . '\')"
	$ul .= '	<li>' . $dis_special_price_available_result['available_date'] . '</li>';
	
    if($product_info['display_room_option'] == '1') {
		$div .= '<div style="display:none"><table width="100%" id="dis_special_price_available_' . $dis_special_price_available_result['products_start_day_id'] . '"><tr>';
		if($dis_special_price_available_result['spe_double'] > 0) { 
			$div .= '<td>' . TEXT_TOUR_DOU_OCC_PRICE . '</td>';
		} 
		if($dis_special_price_available_result['spe_triple'] > 0) {
			$div .= '<td>' . TEXT_TOUR_TRI_OCC_PRICE . '</td>';
		}												 
		if($dis_special_price_available_result['spe_quadruple'] > 0) {
			$div .= '<td>' . TEXT_TOUR_QUAD_OCC_PRICE . '</td>';
		}
		if($dis_special_price_available_result['spe_single'] > 0) {	
			$div .= '<td>' . TEXT_TOUR_SINGLE_OCC_PRICE . '</td>';
		}
		if($dis_special_price_available_result['spe_single_pu'] > 0) {
			$div .= '<td>' . TEXT_TOUR_SINGLE_PU_OCC_PRICE . '<a href="javascript:void(0);" class="tipslayer sp3" style="font-weight: normal;">[?]<span>' . TEXT_TOUR_SINGLE_PU_OCC_TIPS . '</span></a></td>';
		}
		if($dis_special_price_available_result['spe_kids'] > 0) {
			$div .= '<td>' . TEXT_TOUR_KIDS_OCC_PRICE . '</td>';
		}
		$div .= '</tr><tr>';
		
		if($dis_special_price_available_result['spe_double'] > 0) {
			$div .= '<td><span class="jifen_num dazi">' . $currencies->display_price($dis_special_price_available_result['spe_double'], $tax_rate_val_get) . '</span></td>';
		}		
		if($dis_special_price_available_result['spe_triple'] > 0) {
			$div .= '<td><span class="jifen_num dazi">' . $currencies->display_price($dis_special_price_available_result['spe_triple'], $tax_rate_val_get) . '</span></td>';
		}												 
		if($dis_special_price_available_result['spe_quadruple'] > 0) {
			$div .= '<td><span class="jifen_num dazi">' . $currencies->display_price($dis_special_price_available_result['spe_quadruple'], $tax_rate_val_get) . '</span></td>';
		}
		if($dis_special_price_available_result['spe_single'] > 0) {
			$div .= '<td><span class="jifen_num dazi">' . $currencies->display_price($dis_special_price_available_result['spe_single'], $tax_rate_val_get) . '</span></td>';
		}
		if($dis_special_price_available_result['spe_single_pu'] > 0) {	
			$div .= '<td><span class="jifen_num dazi">' . $currencies->display_price($dis_special_price_available_result['spe_single_pu'], $tax_rate_val_get) . '</span></td>';
		}
		if($dis_special_price_available_result['spe_kids'] > 0) {
			$div .= '<td><span class="jifen_num dazi">' . $currencies->display_price($dis_special_price_available_result['spe_kids'], $tax_rate_val_get) . '</span></td>';
		}						
    
		$div .= '</tr></table></div>';
	} else {
		$div .= '<div style="display:none;" id="dis_special_price_available_' . $dis_special_price_available_result['products_start_day_id'] . '">';
		$div .= '<table width="100%"><tr><td>';
		if($isTickets){
			 $div .= db_to_html("普通票价格");
		}else{
			$div .= TEXT_AGE_11_OR_UP;
		}
		$div .= '</td>';
		if($dis_special_price_available_result['spe_kids'] > 0) { 
			$div .= '<td>';
			if($isTickets){
				$div .= db_to_html("折扣票价格");
			}else{
				$div .= TEXT_AGE_2_TO_10;
			}
			$div .= '</td>';
		}												 
		
		$div .= '</tr><tr>';
														  	
		$div .= '<td><span class="sp1">' . $currencies->display_price($dis_special_price_available_result['spe_single'], $tax_rate_val_get) . '</span></td>';
		if($dis_special_price_available_result['spe_single_pu'] > 0){
			$div .= '<td><span class="sp1">' . $currencies->display_price($dis_special_price_available_result['spe_single_pu'], $tax_rate_val_get) . '</span></td>';
		}
		if($dis_special_price_available_result['spe_kids'] > 0) {
			$div .= '<td><span class="sp1">' . $currencies->display_price($dis_special_price_available_result['spe_kids'], $tax_rate_val_get) . '</span></td>';
		}
		$div .= '</tr></table></div>';		
	}
	if (($i+1) % 3 == 0) {
		$temp_html .= '<div><ul class="tab J-ptabMenu">' . $ul . '</ul><div class="tabCon J-ptabCont">' . $div . '</div></div>';
		$ul = '';
		$div = '';
	}
	$i++;
	/*  以下是原来旧代码 by lwkai 2012-04-24 start {
	<table width="100%" cellspacing="2" cellpadding="0"  border="0">
	<tr><td width="18" height="16" valign="middle"><img src="image/arrow_special_price.gif"></td><td  valign="top"><a class="sp3" onClick="javascript:toggel_div('dis_special_price_available_<?php echo $dis_special_price_available_result['products_start_day_id'];?>')"><?php echo $dis_special_price_available_result['available_date'];?></a></td></tr>
	</table>
	<?php 
if($product_info['display_room_option'] == '1')
{
	
?>
	<table class="price" <?php //width="425"?> style="display:none;"  id="dis_special_price_available_<?php echo $dis_special_price_available_result['products_start_day_id'];?>" border="0" cellspacing="0" cellpadding="4">
	<tr>
	<?php if($dis_special_price_available_result['spe_double'] > 0) { ?>
	<th><?php echo TEXT_TOUR_DOU_OCC_PRICE;?></th>
	<?php } ?>		
	<?php if($dis_special_price_available_result['spe_triple'] > 0) { ?>
	<th><?php echo TEXT_TOUR_TRI_OCC_PRICE;?></th>
	<?php } ?>												 
	<?php if($dis_special_price_available_result['spe_quadruple'] > 0) { ?>
	<th><?php echo TEXT_TOUR_QUAD_OCC_PRICE;?></th>
	<?php } ?>
	<?php if($dis_special_price_available_result['spe_single'] > 0) { ?>	
	<th><?php echo TEXT_TOUR_SINGLE_OCC_PRICE;?></th>
	<?php } ?>
	<?php if($dis_special_price_available_result['spe_single_pu'] > 0) { ?>	
	<th><?php echo TEXT_TOUR_SINGLE_PU_OCC_PRICE;?><a href="javascript:void(0);" class="tipslayer sp3" style="font-weight: normal;">[?]<span><?php echo TEXT_TOUR_SINGLE_PU_OCC_TIPS;?></span></a></th>
	<?php } ?>
	<?php if($dis_special_price_available_result['spe_kids'] > 0) { ?>
	<th><?php echo TEXT_TOUR_KIDS_OCC_PRICE;?></th>
	<?php } ?>			
	</tr>
	<tr>
	<?php if($dis_special_price_available_result['spe_double'] > 0) { ?>
	<td><span class="jifen_num dazi"><?php echo $currencies->display_price($dis_special_price_available_result['spe_double'], $tax_rate_val_get); ?></span></td>
	<?php } ?>		
	<?php if($dis_special_price_available_result['spe_triple'] > 0) { ?>
	<td><span class="jifen_num dazi"><?php echo $currencies->display_price($dis_special_price_available_result['spe_triple'], $tax_rate_val_get); ?></span></td>
	<?php } ?>												 
	<?php if($dis_special_price_available_result['spe_quadruple'] > 0) { ?>
	<td><span class="jifen_num dazi"><?php echo $currencies->display_price($dis_special_price_available_result['spe_quadruple'], $tax_rate_val_get); ?></span></td>
	<?php } ?>
	<?php if($dis_special_price_available_result['spe_single'] > 0) { ?>	
	<td><span class="jifen_num dazi"><?php echo $currencies->display_price($dis_special_price_available_result['spe_single'], $tax_rate_val_get); ?></span></td>
	<?php } ?>
	<?php if($dis_special_price_available_result['spe_single_pu'] > 0) { ?>	
	<td><span class="jifen_num dazi"><?php echo $currencies->display_price($dis_special_price_available_result['spe_single_pu'], $tax_rate_val_get); ?></span></td>
	<?php } ?>
	<?php if($dis_special_price_available_result['spe_kids'] > 0) { ?>
	<td><span class="jifen_num dazi"><?php echo $currencies->display_price($dis_special_price_available_result['spe_kids'], $tax_rate_val_get); ?></span></td>
	<?php } ?>			
	</tr>
	</table>
	<?php
}
else
{
	
	?>
	<table class="price" width="60%" style="display:none;" id="dis_special_price_available_<?php echo $dis_special_price_available_result['products_start_day_id'];?>"  border="0" cellspacing="0" cellpadding="4">
	<tr>
	<th><?php if($isTickets){echo db_to_html("普通票价格");}else{echo TEXT_AGE_11_OR_UP;}?></th>
	<?php if($dis_special_price_available_result['spe_kids'] > 0) { ?>
	<th><?php if($isTickets){echo db_to_html("折扣票价格");}else{echo TEXT_AGE_2_TO_10;}?></th>
	<?php } ?>												 
		
	</tr>
	<tr>														  	
	<td><span class="sp1"><?php echo $currencies->display_price($dis_special_price_available_result['spe_single'], $tax_rate_val_get); ?></span></td>
	<?php if($dis_special_price_available_result['spe_single_pu'] > 0){?>
	<td><span class="sp1"><?php echo $currencies->display_price($dis_special_price_available_result['spe_single_pu'], $tax_rate_val_get); ?></span></td>
	<?php }?>
	<?php if($dis_special_price_available_result['spe_kids'] > 0) { ?>
	<td><span class="sp1"><?php echo $currencies->display_price($dis_special_price_available_result['spe_kids'], $tax_rate_val_get); ?></span></td>
	<?php } ?>		
	</tr>
	</table>															
	<?php
}		
?>
	
</div>
<?php */
// 以下是老代码 end by lwkai 2012-04-24 }
 } //end of while loop
 $temp_html .= '<div><ul class="tab J-ptabMenu"> '. $ul . '</ul><div class="tabCon J-ptabCont">' . $div . '</div></div>';
 echo $temp_html;
 echo '</div>';
}
//end of departure date special price

//start of regurla special price tour
if($display_pricing_for_reg_special_price == 'true') {
?>
<?php												  												 
if($display_pricing_for_special_price != 'true') {
?>
<h2><?php echo TEXT_HEADING_DEPARTURE_DATE_HOLIDAY_PRICE;?>:</h2>	
<?php } ?> 													   
<?php
$dis_reg_special_price_available_query_sql = "select * from ".TABLE_PRODUCTS_REG_IRREG_DATE." where products_id = ".(int)$HTTP_GET_VARS['products_id']." and  (spe_single > 0 or  spe_single_pu > 0 OR spe_double > 0 OR spe_triple > 0 OR spe_quadruple > 0 OR spe_kids > 0  ) and available_date =''  order by available_date";
$dis_reg_special_price_available_query = tep_db_query($dis_reg_special_price_available_query_sql);
while($dis_reg_special_price_available_result = tep_db_fetch_array($dis_reg_special_price_available_query))
{		
//amit modified to make sure price on usd
if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
$dis_reg_special_price_available_result['spe_single'] = tep_get_tour_price_in_usd($dis_reg_special_price_available_result['spe_single'],$tour_agency_opr_currency);
$dis_reg_special_price_available_result['spe_single_pu'] = tep_get_tour_price_in_usd($dis_reg_special_price_available_result['spe_single_pu'],$tour_agency_opr_currency);
$dis_reg_special_price_available_result['spe_double'] = tep_get_tour_price_in_usd($dis_reg_special_price_available_result['spe_double'],$tour_agency_opr_currency);
$dis_reg_special_price_available_result['spe_triple'] = tep_get_tour_price_in_usd($dis_reg_special_price_available_result['spe_triple'],$tour_agency_opr_currency);
$dis_reg_special_price_available_result['spe_quadruple'] = tep_get_tour_price_in_usd($dis_reg_special_price_available_result['spe_quadruple'],$tour_agency_opr_currency);
$dis_reg_special_price_available_result['spe_kids'] = tep_get_tour_price_in_usd($dis_reg_special_price_available_result['spe_kids'],$tour_agency_opr_currency);
}
//amit modified to make sure price on usd	
?>
<div class="pr_c_p">
	<table width="100%" cellSpacing=2 cellPadding=0  border=0>
	<tr><td width="18" style="height:16px;" valign="middle"><img src="image/arrow_special_price.gif" alt="" /></td><td  valign="top"><a  class="sp3" onClick="javascript:toggel_div('dis_special_price_available_<?php echo $dis_reg_special_price_available_result['products_start_day_id'];?>')"><?php echo tep_get_display_reg_special_picing_title($dis_reg_special_price_available_result['products_start_day'],$dis_reg_special_price_available_result['operate_start_date'],$dis_reg_special_price_available_result['operate_end_date']);?></a></td></tr>
	</table>
	<?php 
if($product_info['display_room_option'] == '1')
{
?>
	<table class="price" <?php //width="425"?> style="display:none;"  id="dis_special_price_available_<?php echo $dis_reg_special_price_available_result['products_start_day_id'];?>" border="0" cellspacing="0" cellpadding="4">
	<tr>
	<?php if($dis_reg_special_price_available_result['spe_double'] > 0) { ?>
	<th><?php echo TEXT_TOUR_DOU_OCC_PRICE;?></th>
	<?php } ?>		
	<?php if($dis_reg_special_price_available_result['spe_triple'] > 0) { ?>
	<th><?php echo TEXT_TOUR_TRI_OCC_PRICE;?></th>
	<?php } ?>												 
	<?php if($dis_reg_special_price_available_result['spe_quadruple'] > 0) { ?>
	<th><?php echo TEXT_TOUR_QUAD_OCC_PRICE;?></th>
	<?php } ?>
	<?php if($dis_reg_special_price_available_result['spe_single'] > 0) { ?>	
	<th><?php echo TEXT_TOUR_SINGLE_OCC_PRICE;?></th>
	<?php } ?>
	<?php if($dis_reg_special_price_available_result['spe_single_pu'] > 0) { ?>	
	<th><?php echo TEXT_TOUR_SINGLE_PU_OCC_PRICE;?><a href="javascript:void(0);" class="tipslayer sp3" style="font-weight: normal;">[?]<span><?php echo TEXT_TOUR_SINGLE_PU_OCC_TIPS;?></span></a></th>
	<?php } ?>
	<?php if($dis_reg_special_price_available_result['spe_kids'] > 0) { ?>
	<th><?php echo TEXT_TOUR_KIDS_OCC_PRICE;?></th>
	<?php } ?>			
	</tr>
	<tr>
	<?php if($dis_reg_special_price_available_result['spe_double'] > 0) { ?>
	<td><span class="jifen_num dazi"><?php echo $currencies->display_price($dis_reg_special_price_available_result['spe_double'],$tax_rate_val_get); ?></span></td>
	<?php } ?>		
	<?php if($dis_reg_special_price_available_result['spe_triple'] > 0) { ?>
	<td><span class="jifen_num dazi"><?php echo $currencies->display_price($dis_reg_special_price_available_result['spe_triple'],$tax_rate_val_get); ?></span></td>
	<?php } ?>												 
	<?php if($dis_reg_special_price_available_result['spe_quadruple'] > 0) { ?>
	<td><span class="jifen_num dazi"><?php echo $currencies->display_price($dis_reg_special_price_available_result['spe_quadruple'],$tax_rate_val_get); ?></span></td>
	<?php } ?>
	<?php if($dis_reg_special_price_available_result['spe_single'] > 0) { ?>	
	<td><span class="jifen_num dazi"><?php echo $currencies->display_price($dis_reg_special_price_available_result['spe_single'],$tax_rate_val_get); ?></span></td>
	<?php } ?>
	<?php if($dis_reg_special_price_available_result['spe_single_pu'] > 0) { ?>	
	<td><span class="jifen_num dazi"><?php echo $currencies->display_price($dis_reg_special_price_available_result['spe_single_pu'],$tax_rate_val_get); ?></span></td>
	<?php } ?>
	<?php if($dis_reg_special_price_available_result['spe_kids'] > 0) { ?>
	<td><span class="jifen_num dazi"><?php echo $currencies->display_price($dis_reg_special_price_available_result['spe_kids'],$tax_rate_val_get); ?></span></td>
	<?php } ?>			
	</tr>
	</table>
	<?php
}
else
{?>
	<table class="price" width="60%" style="display:none;" id="dis_special_price_available_<?php echo $dis_reg_special_price_available_result['products_start_day_id'];?>"  border="0" cellspacing="0" cellpadding="4">
	<tr>
	<th><?php if($isTickets){echo db_to_html("普通票价格");}else{echo TEXT_AGE_11_OR_UP;}?></th>
	<?php if($dis_reg_special_price_available_result['spe_kids'] > 0) { ?>
	<th><?php if($isTickets){echo db_to_html("折扣票价格");}else{echo TEXT_AGE_2_TO_10;}?></th>
	<?php } ?>												 
	</tr>
	<tr>														  	
	<td><span class="sp1"><?php echo $currencies->display_price($dis_reg_special_price_available_result['spe_single'],$tax_rate_val_get); ?></span></td>
	<?php if($dis_reg_special_price_available_result['spe_single_pu'] > 0) { ?>
	<td><span class="sp1"><?php echo $currencies->display_price($dis_reg_special_price_available_result['spe_single_pu'],$tax_rate_val_get); ?></span></td>
	<?php }?>
	<?php if($dis_reg_special_price_available_result['spe_kids'] > 0) { ?>
	<td><span class="sp1"><?php echo $currencies->display_price($dis_reg_special_price_available_result['spe_kids'],$tax_rate_val_get); ?></span></td>
	<?php } ?>		
	</tr>
	</table>															
	<?php
}		
?>
	
</div>
<?php } //end of while loop

}
//end of regurla special price tour
//start of display attribute special price
if($display_pricing_for_special_price_attribute == 'true'){														
?>
<div class="pr_c_p">
	<?php
$att_loop_cnt = 0;
$sprice_products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$HTTP_GET_VARS['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' order by popt.products_options_sort_order");
while ($sprice_products_options_name = tep_db_fetch_array($sprice_products_options_name_query)) {
$sprice_products_options_array = array();
$sprice_products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix, pa.single_values_price, pa.double_values_price, pa.triple_values_price, pa.quadruple_values_price, pa.kids_values_price from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pa.options_id = '" . (int)$sprice_products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "' and (pa.single_values_price > 0 or pa.double_values_price > 0 OR pa.triple_values_price > 0 OR pa.quadruple_values_price > 0 OR pa.kids_values_price > 0) order by pa.products_options_sort_order");

if(tep_db_num_rows($sprice_products_options_query) > 0){
?>
	<h2><?php echo TEXT_HEADING_PRODUCT_ATTRIBUTE_SPECIAL_PRICE;?>(<?php echo db_to_html($sprice_products_options_name['products_options_name']);?>):</h2>
	<?php
}


while ($sprice_products_options = tep_db_fetch_array($sprice_products_options_query)) {
$att_loop_cnt++;
//amit modified to make sure price on usd
if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
$sprice_products_options['single_values_price'] = tep_get_tour_price_in_usd($sprice_products_options['single_values_price'],$tour_agency_opr_currency);
$sprice_products_options['double_values_price'] = tep_get_tour_price_in_usd($sprice_products_options['double_values_price'],$tour_agency_opr_currency);
$sprice_products_options['triple_values_price'] = tep_get_tour_price_in_usd($sprice_products_options['triple_values_price'],$tour_agency_opr_currency);
$sprice_products_options['quadruple_values_price'] = tep_get_tour_price_in_usd($sprice_products_options['quadruple_values_price'],$tour_agency_opr_currency);
$sprice_products_options['kids_values_price'] = tep_get_tour_price_in_usd($sprice_products_options['kids_values_price'],$tour_agency_opr_currency);
}
//amit modified to make sure price on usd																
//amit added to show Holiday Surcharge -- for special price start
$sprice_products_options['single_values_price'] = number_format($sprice_products_options['single_values_price'], 2, '.', '');
$sprice_products_options['double_values_price'] = number_format($sprice_products_options['double_values_price'], 2, '.', '');
$sprice_products_options['triple_values_price'] = number_format($sprice_products_options['triple_values_price'], 2, '.', '');
$sprice_products_options['quadruple_values_price'] = number_format($sprice_products_options['quadruple_values_price'], 2, '.', '');
$sprice_products_options['kids_values_price'] = number_format($sprice_products_options['kids_values_price'], 2, '.', '');
if($sprice_products_options['price_prefix']=='-'){
$prf_sp_price_prefix = $sprice_products_options['price_prefix'];
}else{
$prf_sp_price_prefix =  '+';
}
?>
	<table width="100%" cellspacing="2" cellpadding="0"  border="0">
	<tr><td width="18" height="16" valign="middle"><img src="image/arrow_special_price.gif" alt="<?php echo TEXT_ALTER_TAG; ?>" /></td><td valign="top"><a class="sp3"  onClick="javascript:toggel_div('dis_special_price_product_option_<?php echo (int)$sprice_products_options_name['products_options_id'];?>_<?php echo (int)$sprice_products_options['products_options_values_id'];?>')"><?php echo  db_to_html($sprice_products_options['products_options_values_name']);?></a></td></tr>
		
	</table>
	<?php 
if($product_info['display_room_option'] == '1')
{
?>
	<table class="price" <?php //width="425"?> style="display:none;"  id="dis_special_price_product_option_<?php echo (int)$sprice_products_options_name['products_options_id'];?>_<?php echo (int)$sprice_products_options['products_options_values_id'];?>" border="0" cellspacing="0" cellpadding="4">
	<tr>
	<?php if($sprice_products_options['double_values_price'] > 0) { ?>
	<th><?php echo TEXT_TOUR_DOU_OCC_PRICE;?></th>
	<?php } ?>		
	<?php if($sprice_products_options['triple_values_price'] > 0) { ?>
	<th><?php echo TEXT_TOUR_TRI_OCC_PRICE;?></th>
	<?php } ?>												 
	<?php if($sprice_products_options['quadruple_values_price'] > 0) { ?>
	<th><?php echo TEXT_TOUR_QUAD_OCC_PRICE;?></th>
	<?php } ?>
	<?php if($sprice_products_options['single_values_price'] > 0) { ?>	
	<th><?php echo TEXT_TOUR_SINGLE_OCC_PRICE;?></th>
	<?php } ?>
	<?php if($sprice_products_options['kids_values_price'] > 0) { ?>
	<th><?php echo TEXT_TOUR_KIDS_OCC_PRICE;?></th>
	<?php } ?>			
	</tr>
	<tr>
	<?php if($sprice_products_options['double_values_price'] > 0) { ?>
	<td><span class="sp1"><?php echo $prf_sp_price_prefix; ?><?php echo $currencies->display_price($sprice_products_options['double_values_price'],$tax_rate_val_get); ?></span></td>
	<?php } ?>		
	<?php if($sprice_products_options['triple_values_price'] > 0) { ?>
	<td><span class="jifen_num dazi"><?php echo $prf_sp_price_prefix; ?><?php echo $currencies->display_price($sprice_products_options['triple_values_price'],$tax_rate_val_get); ?></span></td>
	<?php } ?>												 
	<?php if($sprice_products_options['quadruple_values_price'] > 0) { ?>
	<td><span class="jifen_num dazi"><?php echo $prf_sp_price_prefix; ?><?php echo $currencies->display_price($sprice_products_options['quadruple_values_price'],$tax_rate_val_get); ?></span></td>
	<?php } ?>
	<?php if($sprice_products_options['single_values_price'] > 0) { ?>	
	<td><span class="jifen_num dazi"><?php echo $prf_sp_price_prefix; ?><?php echo $currencies->display_price($sprice_products_options['single_values_price'],$tax_rate_val_get); ?></span></td>
	<?php } ?>
	<?php if($sprice_products_options['kids_values_price'] > 0) { ?>
	<td><span class="jifen_num dazi"><?php echo $prf_sp_price_prefix; ?><?php echo $currencies->display_price($sprice_products_options['kids_values_price'],$tax_rate_val_get); ?></span></td>
	<?php } ?>			
	</tr>
	</table>
	<?php
}
else
{?>
	<table class="price" width="60%" style="display:none;" id="dis_special_price_product_option_<?php echo (int)$sprice_products_options_name['products_options_id'];?>_<?php echo (int)$sprice_products_options['products_options_values_id'];?>" border="0" cellspacing="0" cellpadding="4">
	<tr>
	<td class="TdSpecialPriceTableBorder"><?php if($isTickets){echo db_to_html("普通票价格");}else{echo TEXT_AGE_11_OR_UP;}?></td>
	<?php if($sprice_products_options['kids_values_price'] > 0) { ?>
	<td class="TdSpecialPriceTableBorder"><?php if($isTickets){echo db_to_html("折扣票价格");}else{echo TEXT_AGE_2_TO_10;}?></td>
	<?php } ?>												 
	</tr>
	<tr>														  	
	<td><span class="sp1"><?php echo $prf_sp_price_prefix; ?><?php echo $currencies->display_price($sprice_products_options['single_values_price'],$tax_rate_val_get); ?></span></td>
	<?php if($sprice_products_options['kids_values_price'] > 0) { ?>
	<td><span class="sp1"><?php echo $prf_sp_price_prefix; ?><?php echo $currencies->display_price($sprice_products_options['kids_values_price'],$tax_rate_val_get); ?></span></td>
	<?php } ?>		
	</tr>
	</table>	
	<?php
}		
?>
	
	<?php

//amit added to show Holiday Surcharge -- for special price end

}
}

?>
</div>
<?php
}
//end of display attribute special price
?>

<?php }  // end of display specia pring notes ?>

<div class="clear"></div>

<?php if($product_info['products_pricing_special_notes'] != ''){ ?>
<?php echo stripslashes2(db_to_html($product_info['products_pricing_special_notes'])); ?>
<?php }  
//end is_transfer check
}else {
	//接送服务价格显示
	if($product_info['transfer_type'] == '0') { ?>
<h2><?php echo db_to_html('标准价格')?>:</h2>				
<table cellspacing="0" cellpadding="0" border="0" class="price priceTransfer">
	<tbody>
		<tr>
			<?php if($product_info['products_single'] > 0) { ?>
			<th><?php echo db_to_html("1-3位") ?>&nbsp;</th>
			<?php }?>
			<?php if($product_info['products_double'] > 0) { ?>
			<th><?php echo db_to_html("4-6位") ?>&nbsp;</th>
			<?php }?>				
			<?php if($product_info['products_triple'] > 0) { ?>
			<th><?php echo db_to_html("超时时间") ?>&nbsp;</th>
			<?php }?>				
		</tr>
		<tr>
			<?php if($product_info['products_single'] > 0) { ?>
			<td><?php echo str_replace('/','<br />',$currencies->display_price($product_info['products_single'],$tax_rate_val_get)); ?></td>
			<?php }?>					
			<?php if($product_info['products_double'] > 0) { ?>
			<td><?php echo str_replace('/','<br />',$currencies->display_price($product_info['products_double'],$tax_rate_val_get)); ?></td>
			<?php }?>					
			<?php if($product_info['products_triple'] > 0) { ?>
			<td><?php echo str_replace('/','<br />',$currencies->display_price($product_info['products_triple'],$tax_rate_val_get)); ?></td>
			<?php }?>
		</tr>
</tbody>
</table>
<?php 
}	else if($product_info['transfer_type'] == '1') {
	 
			$locationQuery = tep_db_query("SELECT * FROM ".TABLE_PRODUCTS_TRANSFER_LOCATION." WHERE products_id = '".intval($products_id)."' ORDER BY type ASC,products_transfer_location_id ASC");
			$location_names = array();
			while($row = tep_db_fetch_array($locationQuery)){
				$location_names[$row['products_transfer_location_id']] =  ($row['zipcode'] == '0' ||  $row['zipcode'] == '')? $row['short_address']:$row['short_address'].'('.$row['zipcode'].')';
			}			
			$query = tep_db_query("SELECT * FROM ".TABLE_PRODUCTS_TRANSFER_ROUTE."  WHERE products_id = ".intval($products_id).' ORDER BY products_transfer_route_id ASC');
			?>
	<h2><?php echo db_to_html('标准价格')?>:</h2>				
	<table cellspacing="0" cellpadding="0" border="0" class="price priceTransfer">
	<tbody>
		<tr>
			<th><?php echo db_to_html("线路") ?>&nbsp;</th>
			<th><?php echo db_to_html("1-3位") ?>&nbsp;</th>		
			<th><?php echo db_to_html("4-6位") ?>&nbsp;</th>			
			
		</tr>
		<?php 
		
		while($row = tep_db_fetch_array($query )){ ?>
		<tr>
			<td><?php echo  db_to_html($location_names[$row['pickup_location_id']].'-'.$location_names[$row['dropoff_location_id']]) ?></td>		
			<td><?php echo str_replace('/','<br />',$currencies->display_price($row['price1'],$tax_rate_val_get)); ?></td>		
			<td><?php echo str_replace('/','<br />',$currencies->display_price($row['price2'],$tax_rate_val_get)); ?></td>
		</tr>
		<?}?>
</tbody>

</table>

<?php } 
ob_start();
?>
<table width="100%" cellspacing="2" cellpadding="0"  border="0">
	<tr>
	<td valign="top">
	<?php 
	
	if($product_info['products_triple']>0.1) { 
		
	?>
	<ul><b>时间说明:</b>
		<li>8:30AM - 10:30PM之间为标准时间。</li>
		<li>8:30AM以前，10:30PM以后为超时时间。</li>
		<li>在超时时间内每车需要额外增加 <?php  echo str_replace('/','<br />',$currencies->display_price($product_info['products_triple'],$tax_rate_val_get)); ?>。</li>
		<li>所有航班时间以官方网站公布时间为准。</li>
	</ul>
	<?php } ?>
	<ul><b>行李要求:</b>
	<li>乘坐6位客人，大件行李不超过4件 (手提袋， 背包，不计算在内)。</li>
	<li>乘坐5位客人，大件行李不超过5件(手提袋， 背包，不计算在内)。</li>
	<li>乘坐4位或4位以下客人，大件行李不超过6件（手提袋，背包等不计算在内)。</li>
	</ul>
	<ul><b>乘坐人数：</b>
	<li>一部车要求乘坐人数最多为6人，超过6人将按第二部/第三部车计算。</li>
	</ul>
	</td></tr>		
</table>
<?php
echo db_to_html(ob_get_clean());
	
} ?>




