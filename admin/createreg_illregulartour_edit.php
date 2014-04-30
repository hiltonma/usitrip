<?php
if(isset($_GET['numberofsection']) && $_GET['numberofsection'] != '')
{
$totalsections = (int)$_GET['numberofsection'];
$ppproducts_id = $_GET['product_id'];
$sec_cnt_db=1;
//echo "totoal sec".$totalsections;
$product_num_row = regu_irregular_section_numrow($ppproducts_id);
//echo $ppproducts_id;
if(isset($ppproducts_id) && $ppproducts_id>0  && $product_num_row>0)
{
	//echo "in if";
	$regu_irregular_array=regu_irregular_section_detail($ppproducts_id);
	foreach($regu_irregular_array as $k=>$v)
	{
		if(is_array($v))
		{
		$pInfo->products_is_regular_tour=$regu_irregular_array[$k]['producttype'];
		$nn=$sec_cnt_db;
		$pInfo->products_id=$ppproducts_id;
		
		$pInfo->operate_start_date=$regu_irregular_array[$k]['operate_start_date'];
		
		$pInfo->operate_end_date=$regu_irregular_array[$k]['operate_end_date'];
		
				$pInfo->products_durations_description = tep_get_irreg_products_duration_description($ppproducts_id,$pInfo->operate_start_date,$pInfo->operate_end_date);
		switch ($pInfo->products_is_regular_tour) 
		{
    		  case '0': $is_regu = false; $not_regu = true; break;
    		  case '1':
    		  default: $is_regu = true; $not_regu = false;
   		 }
		/*if($regu_irregular_array['producttype']==1)//regular tour
		{
			
		}
		else //irreuglar tour
		{
		
		}*/
		//--regulart irregular edit section data display----//
		?>
			<table width="100%"  border="0" cellpadding="1" cellspacing="1" >
		   <tr>
            <td class="main" nowrap><?php echo 'Tour Start Date'; ?></td>
            <td class="main"><?php 
			if($pInfo->operate_start_date == ''){
			$pInfo->operate_start_date ='01-01';
			}			
			$operate_start_date_array = explode('-',$pInfo->operate_start_date);
			echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' .tep_draw_pull_down_menu('operate_start_date_month'.$nn, $months_operate_array, $operate_start_date_array[0],'').'&nbsp;'.tep_draw_pull_down_menu('operate_start_date_day'.$nn, $days_operate_array, $operate_start_date_array[1],'').'&nbsp;'.tep_draw_pull_down_menu('operate_start_date_year'.$nn, $operate_years_array, $operate_start_date_array[2],''); ?></td>
          </tr>
		 <tr>
            <td class="main"><?php echo 'Tour End Date'; ?></td>
            <td class="main"><?php 
			if($pInfo->operate_end_date == ''){
			$pInfo->operate_end_date ='12-31';
			}
			$operate_end_date_array = explode('-',$pInfo->operate_end_date);
			
			echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' .tep_draw_pull_down_menu('operate_end_date_month'.$nn, $months_operate_array, $operate_end_date_array[0],'').'&nbsp;'.tep_draw_pull_down_menu('operate_end_date_day'.$nn, $days_operate_array, $operate_end_date_array[1],'').'&nbsp;'.tep_draw_pull_down_menu('operate_end_date_year'.$nn, $operate_years_array, $operate_end_date_array[2],''); ?></td>
         </tr>
		   <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_TYPE; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('products_is_regular_tour'.$nn, '1', $is_regu, '','') . '&nbsp;' . TEXT_PRODUCT_REGULAR_TOUR ; ?></td>
          </tr>
          <?php  /* standard pricing - start */ ?>
          <?php
		  	$get_edit_standard_prices = tep_db_query("select * from ".TABLE_PRODUCTS_REG_IRREG_STANDARD_PRICE." where products_id = '".$pInfo->products_id."' and operate_start_date = '".$pInfo->operate_start_date."' and operate_end_date = '".$pInfo->operate_end_date."'");
			$row_edit_standard_prices = tep_db_fetch_array($get_edit_standard_prices);
			//edit section
		  ?>
          <tr>
			 <td class="main" nowrap>Regular Price of This Section: </td>
             <td class="main">					 
			 	<table border="0" cellpadding="2" cellspacing="0" bgcolor="FFFFFF" width="80%">
					<tr>
						<td colspan="10"><?php echo tep_black_line(); ?></td>
					</tr>
					<tr class="dataTableHeadingRow">
						<td width="15%"  class="dataTableHeadingContent"><?php echo tep_draw_separator('pixel_trans.gif', '20', '1'); ?>
						<?php if($display_room_yes){ echo 'Single'; }else{echo 'Adult';}?>
						</td>
                        <td class="dataTableHeadingContent" width="15%" ><?php echo tep_draw_separator('pixel_trans.gif', '20', '1'); ?>Single PU</td>
						<td class="dataTableHeadingContent" width="15%" ><?php echo tep_draw_separator('pixel_trans.gif', '20', '1'); ?>Double</td>
						<td class="dataTableHeadingContent" width="15%" ><?php echo tep_draw_separator('pixel_trans.gif', '20', '1'); ?>Triple</td>
						<td class="dataTableHeadingContent" width="15%" ><?php echo tep_draw_separator('pixel_trans.gif', '20', '1'); ?>Quadruple</td>
						<td class="dataTableHeadingContent" width="15%" ><?php echo tep_draw_separator('pixel_trans.gif', '20', '1'); ?>Kids</td>
					</tr>
					<tr>
						<td colspan="10"><?php echo tep_black_line(); ?></td>
				    </tr>
					<tr class="dataTableRow">
						<td class="dataTableContent" width="15%"><?php echo $display_tour_agency_opr_currency_note . 'RP &nbsp;' . tep_draw_input_field('products_single'.$nn, $row_edit_standard_prices['products_single'], 'size="7"'.$price_input_readonly);
						if($access_full_edit == 'true') { 
							echo '<br />CP &nbsp;' . tep_draw_input_field('products_single_cost'.$nn, $row_edit_standard_prices['products_single_cost'], 'size="7"'.$price_input_readonly); ?><br>&nbsp;&nbsp;&nbsp;&nbsp;<input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.products_single_cost<?php echo $nn; ?>, document.new_product.products_single<?php echo $nn; ?>);"  type=button value="算底价(e)" <?= $price_button;?>>
						<?php 
						}else{ 
							echo tep_draw_hidden_field('products_single_cost'.$nn, $row_edit_standard_prices['products_single_cost']);
						}	
						?>
						</td>
                        
                        <td class="dataTableContent" width="15%"><?php echo $display_tour_agency_opr_currency_note . 'RP &nbsp;' . tep_draw_input_field('products_single_pu'.$nn, $row_edit_standard_prices['products_single_pu'], 'size="7"'.$price_input_readonly);
						if($access_full_edit == 'true') { 
							echo '<br />CP &nbsp;' . tep_draw_input_field('products_single_pu_cost'.$nn, $row_edit_standard_prices['products_single_pu_cost'], 'size="7"'.$price_input_readonly); ?><br>&nbsp;&nbsp;&nbsp;&nbsp;<input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.products_single_pu_cost<?php echo $nn; ?>, document.new_product.products_single_pu<?php echo $nn; ?>);"  type=button value="算底价(e)" <?= $price_button;?>>
						<?php 
						}else{ 
							echo tep_draw_hidden_field('products_single_pu_cost'.$nn, $row_edit_standard_prices['products_single_pu_cost']);
						}	
						?>
						</td>
						
						<td class="dataTableContent" width="15%"><?php echo $display_tour_agency_opr_currency_note . 'RP &nbsp;' . tep_draw_input_field('products_double'.$nn, $row_edit_standard_prices['products_double'], 'size="7" '.$price_input_readonly.($display_room_yes ? '' : 'disabled="disabled"').'');
						if($access_full_edit == 'true') { 
							echo '<br />CP &nbsp;' . tep_draw_input_field('products_double_cost'.$nn, $row_edit_standard_prices['products_double_cost'], 'size="7"'.$price_input_readonly); ?><br>&nbsp;&nbsp;&nbsp;&nbsp;<input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.products_double_cost<?php echo $nn; ?>, document.new_product.products_double<?php echo $nn; ?>);"  type=button value="算底价(e)" <?= $price_button;?>>
						<?php 
						}else{
							echo tep_draw_hidden_field('products_double_cost'.$nn, $row_edit_standard_prices['products_double_cost']);
						} 
						?>
						</td>						
						
						<td class="dataTableContent" width="15%"><?php echo $display_tour_agency_opr_currency_note . 'RP &nbsp;' . tep_draw_input_field('products_triple'.$nn, $row_edit_standard_prices['products_triple'], 'size="7" '.($display_room_yes ? '' : 'disabled="disabled"').''.$price_input_readonly);
						if($access_full_edit == 'true') { 
							echo '<br />CP &nbsp;' . tep_draw_input_field('products_triple_cost'.$nn, $row_edit_standard_prices['products_triple_cost'], 'size="7"'.$price_input_readonly); ?><br>&nbsp;&nbsp;&nbsp;&nbsp;<input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.products_triple_cost<?php echo $nn; ?>, document.new_product.products_triple<?php echo $nn; ?>);"  type=button value="算底价(e)" <?= $price_button;?>>
						<?php 
						}else{
							echo tep_draw_hidden_field('products_triple_cost'.$nn, $row_edit_standard_prices['products_triple_cost']);
						}
						?>
						</td>
						
						<td class="dataTableContent" width="15%"><?php echo $display_tour_agency_opr_currency_note . 'RP &nbsp;' . tep_draw_input_field('products_quadr'.$nn, $row_edit_standard_prices['products_quadr'], 'size="7" '.($display_room_yes ? '' : 'disabled="disabled"').''.$price_input_readonly);
						if($access_full_edit == 'true') { 
							echo '<br />CP &nbsp;' . tep_draw_input_field('products_quadr_cost'.$nn, $row_edit_standard_prices['products_quadr_cost'], 'size="7"'.$price_input_readonly); ?><br>&nbsp;&nbsp;&nbsp;&nbsp;<input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.products_quadr_cost<?php echo $nn; ?>, document.new_product.products_quadr<?php echo $nn; ?>);"  type=button value="算底价(e)" <?= $price_button;?>>
						<?php 
						}else{
							echo tep_draw_hidden_field('products_quadr_cost'.$nn, $row_edit_standard_prices['products_quadr_cost']);
						} 
						?>
						</td>
						
						<td class="dataTableContent" width="15%"><?php echo $display_tour_agency_opr_currency_note. 'RP &nbsp;' . tep_draw_input_field('products_kids'.$nn, $row_edit_standard_prices['products_kids'], 'size="7"'.$price_input_readonly);
						if($access_full_edit == 'true') { 
							echo '<br />CP &nbsp;' . tep_draw_input_field('products_kids_cost'.$nn, $row_edit_standard_prices['products_kids_cost'], 'size="7"'.$price_input_readonly); ?><br>&nbsp;&nbsp;&nbsp;&nbsp;<input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.products_kids_cost<?php echo $nn; ?>, document.new_product.products_kids<?php echo $nn; ?>);"  type=button value="算底价(e)" <?= $price_button;?>>
						<?php 
						}else{
							echo tep_draw_hidden_field('products_kids_cost'.$nn, $row_edit_standard_prices['products_kids_cost']);
						}
						?>
						</td>
					</tr>
					<tr>
						<td colspan="6" class="errorText">(If leave blank, The standard price under Room and Price section will be applied).</td>
					</tr>
				</table>
			 </td>
		  </tr>
          <tr>
			<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '20'); ?></td>
		  </tr>				
          <?php  /* standard pricing - end */ ?>
         <?php  
		 if($pInfo->products_is_regular_tour == "") $daily_check_true = 'CHECKED'; //IF INSERTING NEW PRODUCT
		
		if($pInfo->products_id && $pInfo->products_is_regular_tour != "" && $pInfo->products_is_regular_tour == '1')
		{

			$daily_check_true = '';
			$week_check_true = '';
			

				$startdate_count_query = tep_db_query("select count(*) as start_count from " . TABLE_PRODUCTS_REG_IRREG_DATE . " where products_id = ". $pInfo->products_id ." and operate_end_date='".$pInfo->operate_end_date."' and  operate_start_date='".$pInfo->operate_start_date."' order by products_start_day");
				$startdate_count = tep_db_fetch_array($startdate_count_query); 
				//$startdate_count['start_count'];
				if($startdate_count['start_count'] == '10')
				{
					$daily_check_true = 'CHECKED';
   				    $i =1;
					$startdate_query = tep_db_query("select * from " . TABLE_PRODUCTS_REG_IRREG_DATE . " where products_id = ". $pInfo->products_id ." and operate_end_date='".$pInfo->operate_end_date."' and  operate_start_date='".$pInfo->operate_start_date."' order by products_start_day");
					while ($startdate = tep_db_fetch_array($startdate_query)) 
					{
						$daily_start_date_id_result[$i] = $startdate['products_start_day_id'];
						$daily_price_result[$i] = $startdate['extra_charge'];
						$daily_prefix_result[$i] =  $startdate['prefix'];
						$daily_sort_order_result[$i] =  $startdate['sort_order'];
						$i++;
					}
				}
				else
				{
					$week_check_true = 'CHECKED';
					$i =1;
					$startdate_query = tep_db_query("select * from " . TABLE_PRODUCTS_REG_IRREG_DATE . " where products_id = ".$pInfo->products_id." and operate_end_date='".$pInfo->operate_end_date."' and  operate_start_date='".$pInfo->operate_start_date."' order by products_start_day");

					while ($startdate = tep_db_fetch_array($startdate_query)) 
					{
						//echo $nn.$startdate['products_start_day'];
						$checked[$startdate['products_start_day']] = "CHECKED";
						$weekday_start_date_id_result[$startdate['products_start_day']] = $startdate['products_start_day_id'];
						 $weekday_price_result[$nn.$startdate['products_start_day']] = $startdate['extra_charge'];
						$weekday_price_result_cost[$nn.$startdate['products_start_day']] = $startdate['extra_charge_cost'];
						$weekday_prefix_result[$nn.$startdate['products_start_day']] =  $startdate['prefix'];
						$weekday_sort_order_result[$nn.$startdate['products_start_day']] =  $startdate['sort_order'];

$weekday_single_price_result[$nn.$startdate['products_start_day']] = $startdate['spe_single'];

$weekday_single_pu_price_result[$nn.$startdate['products_start_day']] = $startdate['spe_single_pu'];

$weekday_double_price_result[$nn.$startdate['products_start_day']] = $startdate['spe_double'];

$weekday_triple_price_result[$nn.$startdate['products_start_day']] = $startdate['spe_triple'];

$weekday_quadruple_price_result[$nn.$startdate['products_start_day']] = $startdate['spe_quadruple'];

$weekday_kids_price_result[$nn.$startdate['products_start_day']] = $startdate['spe_kids'];

$weekday_single_price_cost_result[$nn.$startdate['products_start_day']] = $startdate['spe_single_cost'];

$weekday_single_pu_price_cost_result[$nn.$startdate['products_start_day']] = $startdate['spe_single_pu_cost'];

$weekday_double_price_cost_result[$nn.$startdate['products_start_day']] = 
	$startdate['spe_double_cost'];

$weekday_triple_price_cost_result[$nn.$startdate['products_start_day']] = $startdate['spe_triple_cost'];

$weekday_quadruple_price_cost_result[$nn.$startdate['products_start_day']] = 
	$startdate['spe_quadruple_cost'];

$weekday_kids_price_cost_result[$nn.$startdate['products_start_day']] = $startdate['spe_kids_cost'];






						$i++;
					}
				}
		}
		 
		 ?>
		 <tr>
		 <td class="main"></td>
            <td class="main" id="script_lwk"><script type="text/javascript">
							function myval(cur,pre,nn,name){
								if (pre < 1 || name == "") return;
								var vals = jQuery('tr.line' + nn + '_' + pre).find('#copy' + nn + '_' + pre).find("input[name^=\"" + name + "\"]").not("input[name^=\"" + name + "_cost\"]").val();
								jQuery('tr.line' + nn + '_' + cur).find("#copy" + nn + '_' + cur).find("input[name^=\"" + name + "\"]").not("input[name^=\"" + name + "_cost\"]").val(vals);
								vals = jQuery('tr.line' + nn + '_' + pre).find('#copy' + nn + '_' + pre).find("input[name^=\"" + name + "_cost\"]").val();
								jQuery('tr.line' + nn + '_' + cur).find("#copy" + nn + '_' + cur).find("input[name^=\"" + name + "_cost\"]").val(vals);
							}
							
							function lwk_copy_weekday(cur,pre,nn,arr){
								var contype = arr.constructor;
								if (pre < 1 || (contype != Array && contype != String)) return;
								
								jQuery('tr.line' + nn + '_' + cur + " > td.index > input[type=\"checkbox\"]").attr("checked","checked");
								vals = jQuery('tr.line' + nn + '_' + pre).find('#copy' + nn + '_' + pre).find('input[name^=\"weekday_prefix\"]').val();
								jQuery('tr.line' + nn + '_' + cur).find('#copy' + nn + '_' + cur).find('input[name^=\"weekday_prefix\"]').val(vals);
								vals = jQuery('tr.line' + nn + '_' + pre + ' > td.js_order > input[name^=\"weekday_sort_order\"]').val();
								vals = parseInt(vals,10) + 1;
								if (isNaN(vals)) vals = 0;
								jQuery('tr.line' + nn + '_' + cur + ' > td.js_order > input[name^=\"weekday_sort_order\"]').val(vals);
								
								if (contype == Array) {
									for (key in arr) {
										myval(cur,pre,nn,arr[key]);	
									}
								} else if (contype == String) {
									myval(cur,pre,nn,arr);
								}	
							}
							
							function test(index,nn){
								if (index - 1 < 1) return;
								var cur = index;
								var pre = index - 1;
								jQuery('#show_' + nn + index).click();
								do {
									var checked_val =jQuery('tr.line' + nn + '_' + pre + " > td.index > input[type=\"checkbox\"]").attr('checked') ;
									if (checked_val == true) {
										var arr = [];
										arr[arr.length] = 'weekday_price';
										arr[arr.length] = 'weekday_single_price';
										arr[arr.length] = 'weekday_single_pu_price';
										arr[arr.length] = 'weekday_double_price';
										arr[arr.length] = 'weekday_triple_price';
										arr[arr.length] = 'weekday_quadruple_price';
										arr[arr.length] = 'weekday_kids_price';
										
										lwk_copy_weekday(cur,pre,nn,arr);
										break;
									}
									pre -- ;
								}while(pre > 1);
							}
							
							/* 第二个同上功能区 */
							function myval2(cur,pre,nn,name){
								if (pre < 1 || name == "") return;
								var vals = jQuery('#table_id_irregular' + nn + '_' + pre).find("input[name^=\"" + name + "\"]").not("input[name^=\"" + name + "_cost\"]").val();
								jQuery('#table_id_irregular' + nn + '_' + cur).find("input[name^=\"" + name + "\"]").not("input[name^=\"" + name + "_cost\"]").val(vals);
								vals = jQuery('#table_id_irregular' + nn + '_' + pre).find("input[name^=\"" + name + "_cost\"]").val();
								jQuery('#table_id_irregular' + nn + '_' + cur).find("input[name^=\"" + name + "_cost\"]").val(vals);
							}
							
							function lwk_copy_weekday2(cur,pre,nn,arr){
								var contype = arr.constructor;
								if (pre < 1 || (contype != Array && contype != String)) return;
								
								
								vals = jQuery('#table_id_irregular' + nn + '_' + pre).find('input[name^=\"avaliable_day_prefix\"]').val();
								jQuery('#table_id_irregular' + nn + '_' + cur).find('input[name^=\"avaliable_day_prefix\"]').val(vals);
								vals = jQuery('#table_id_irregular' + nn + '_' + pre).find('input[name^=\"avaliable_day_sort_order\"]').val();
								vals = parseInt(vals,10) + 1;
								if (isNaN(vals)) vals = 0;
								jQuery('#table_id_irregular' + nn + '_' + cur).find('input[name^=\"avaliable_day_sort_order\"]').val(vals);
								
								if (contype == Array) {
									for (key in arr) {
										myval2(cur,pre,nn,arr[key]);	
									}
								} else if (contype == String) {
									myval2(cur,pre,nn,arr);
								}	
							}
							function mytest(index,nn){
								if (index - 1 < 1) return;
								var cur = index;
								var pre = index - 1;
								var arr = [];
								arr[arr.length] = 'avaliable_day_price';
								arr[arr.length] = 'avaliable_single_price';
								arr[arr.length] = 'avaliable_single_pu_price';
								arr[arr.length] = 'avaliable_double_price';
								arr[arr.length] = 'avaliable_triple_price';
								arr[arr.length] = 'avaliable_quadruple_price';
								arr[arr.length] = 'avaliable_kids_price';
								
								lwk_copy_weekday2(cur,pre,nn,arr);
							}
							</script>					 
					  <table border="0" cellpadding="2" cellspacing="0" bgcolor="FFFFFF">
					  
						  <tr>
							<td colspan="10"><?php echo tep_black_line(); ?></td>
						  </tr>
						  <tr class="dataTableHeadingRow">
							<td class="dataTableHeadingContent" width="13%" align="left">&nbsp;</td>
							<td class="dataTableHeadingContent" width="8%" align="center" nowrap>Prefix +/-</td>
							<td align="center" class="dataTableHeadingContent" width="14%" >Price</td>

							<td colspan="5" class="dataTableHeadingContent" >
							
							<table width="100%"   border="0" cellspacing="5" cellpadding="5">
								  <tr>
									<td width="10%"  class="dataTableHeadingContent">
							 		
							
							<?php if($display_room_yes){ echo 'Single'; }else{echo 'Adult';}?></td>
							<td class="dataTableHeadingContent" width="10%" >Single PU</td>
							<td class="dataTableHeadingContent" width="10%" >Double</td>
							<td class="dataTableHeadingContent" width="10%" >Tiple</td>
							<td class="dataTableHeadingContent" width="10%" >Quadruple</td>
							<td class="dataTableHeadingContent" width="10%" >Kids</td>
							</tr>
							</table>
							
							</td>
							
							<td class="dataTableHeadingContent" width="10%" align="center">Sort Order</td>
							<td class="dataTableHeadingContent" width="10%" align="center" colspan="center">Special</td>
						  </tr>
						  <tr>
							<td colspan="10"><?php echo tep_black_line(); ?></td>
						  </tr>
							
						  <?php
						for($j=1;$j<8;$j++) {
							if($j==1) $day = 'Sunday';
							if($j==2) $day = 'Monday';
							if($j==3) $day = 'Tuesday';
							if($j==4) $day = 'Wednesday';
							if($j==5) $day = 'Thursday';
							if($j==6) $day = 'Friday';
							if($j==7) $day = 'Saturday';
							?>						 
						   	<tr class="dataTableRow line<?=$nn?>_<?=$j?>">
								<td class="dataTableContent index">
									&nbsp;<input type="hidden" name="weekday_start_date_id_<?php echo $nn."_".$j;?>" value="<?php echo $weekday_start_date_id_result[$j];?>">
									<?php echo tep_draw_checkbox_field('weekday_option'.$nn."_".$j, $checked[$j], $checked[$j]) . '&nbsp;' .$day; ?>
								</td>
								<td class="dataTableContent"  align="center">
									<?php echo tep_draw_input_field('weekday_prefix'.$nn.$j, $weekday_prefix_result[$nn.$j], $price_input_readonly.'size="2" onKeyUp="return weekday_prefix_check_irregular('.$nn.$j.');"'); ?>
								</td>
								<?php 
								if($weekday_single_price_result[$nn.$j]>0 || $weekday_single_pu_price_result[$nn.$j]>0 || $weekday_double_price_result[$nn.$j]>0 || $weekday_triple_price_result[$nn.$j]>0 || $weekday_quadruple_price_result[$nn.$j]>0 || $weekday_kids_price_result[$nn.$j]>0) { 
									$check_spe_price_regular=true;
								} else {
									$check_spe_price_regular=false;
								}
								if($access_full_edit == 'true') {
									?>
							 		<td colspan="6"  class="dataTableContent" id="copy<?=$nn?>_<?=$j?>" align="left" valign="top" style=" white-space:nowrap;">
							 			<table  border="0" cellspacing="0" cellpadding="2" width="100%">
								  			<tr>
												<td class="dataTableContent"  align="left" nowrap >RP 
													<?php 
														echo $display_tour_agency_opr_currency_note;  
														echo tep_draw_input_field('weekday_price'.$nn.$j, $weekday_price_result[$nn.$j], 'size="7" onKeyUp="return weekday_price_check_irregular('.$nn.$j.');" '.($check_spe_price_regular?'disabled="disabled"':'').' '.$price_input_readonly); 
														if($access_full_edit == 'true') { 
															echo  '<br>CP '.$display_tour_agency_opr_currency_note . tep_draw_input_field('weekday_price_cost'.$nn.$j, $weekday_price_result_cost[$nn.$j], 'size="7"'.$price_input_readonly); 
													?><br>&nbsp;&nbsp;<input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.weekday_price_cost<?php echo $nn.$j;?>, document.new_product.weekday_price<?php echo $nn.$j;?>);"  type=button value="算底价(e)" <?= $price_button;?>>
													<?php } ?>
												</td>
												<td>									
							   						<?php 
													if($weekday_single_price_result[$nn.$j]>0 || $weekday_single_pu_price_result[$nn.$j]>0 || $weekday_double_price_result[$nn.$j]>0 || $weekday_triple_price_result[$nn.$j]>0 || $weekday_quadruple_price_result[$nn.$j]>0 || $weekday_kids_price_result[$nn.$j]>0) { 
														$display_val = 'visible';
													}else { 
														$display_val='none';
													}?>
							  						<div id="regular_extra_price_<?php echo $nn.$j;?>" style="display:<?php echo $display_val?>; float:left">
														<table width="100%"   border="0" cellspacing="5" cellpadding="5">
														  <tr>
															<td width="10%">
																<?php 
																	echo tep_draw_input_field('weekday_single_price'.$nn.$j, $weekday_single_price_result[$nn.$j], 'size="7"'.$price_input_readonly).'<br>'.tep_draw_input_field('weekday_single_price_cost'.$nn.$j, $weekday_single_price_cost_result[$nn.$j], 'size="7"'.$price_input_readonly).'<br><input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.weekday_single_price_cost'.$nn.$j.', weekday_single_price'.$nn.$j.');"  type=button value="算底价(e)" '. $price_button.'>'; 
																?>
															</td>
															<td width="10%">
																<?php 
																	echo tep_draw_input_field('weekday_single_pu_price'.$nn.$j, $weekday_single_pu_price_result[$nn.$j], 'size="7"'.$price_input_readonly).'<br>'.tep_draw_input_field('weekday_single_pu_price_cost'.$nn.$j, $weekday_single_pu_price_cost_result[$nn.$j], 'size="7"'.$price_input_readonly).'<br><input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.weekday_single_pu_price_cost'.$nn.$j.', weekday_single_pu_price'.$nn.$j.');"  type=button value="算底价(e)" '. $price_button.'>'; 
																?>
															</td>
															
															<td width="10%"> 
																<?php 
																	echo tep_draw_input_field('weekday_double_price'.$nn.$j, $weekday_double_price_result[$nn.$j], 'size="7"'.$price_input_readonly).'<br>'.tep_draw_input_field('weekday_double_price_cost'.$nn.$j, $weekday_double_price_cost_result[$nn.$j], 'size="7"'.$price_input_readonly).'<br><input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.weekday_double_price_cost'.$nn.$j.', weekday_double_price'.$nn.$j.');"  type=button value="算底价(e)" '. $price_button.'>'; 
																?>
															</td>
															<td width="10%">
																<?php 
																	echo tep_draw_input_field('weekday_triple_price'.$nn.$j, $weekday_triple_price_result[$nn.$j], 'size="7"'.$price_input_readonly).'<br>'.tep_draw_input_field('weekday_triple_price_cost'.$nn.$j, $weekday_triple_price_cost_result[$nn.$j], 'size="7"'.$price_input_readonly).'<br><input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.weekday_triple_price_cost'.$nn.$j.', weekday_triple_price'.$nn.$j.');"  type=button value="算底价(e)" '. $price_button.'>'; 
																?>
															 </td>
															<td width="10%">
																<?php 
																	echo tep_draw_input_field('weekday_quadruple_price'.$nn.$j, $weekday_quadruple_price_result[$nn.$j], 'size="7"'.$price_input_readonly).'<br>'.tep_draw_input_field('weekday_quadruple_price_cost'.$nn.$j, $weekday_quadruple_price_cost_result[$nn.$j], 'size="7"'.$price_input_readonly).'<br><input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.weekday_quadruple_price_cost'.$nn.$j.', weekday_quadruple_price'.$nn.$j.');"  type=button value="算底价(e)" '. $price_button.'>'; 
																?>
															</td>
															<td width="10%"> 
																<?php 
																	echo tep_draw_input_field('weekday_kids_price'.$nn.$j, $weekday_kids_price_result[$nn.$j], 'size="7"'.$price_input_readonly).'<br>'.tep_draw_input_field('weekday_kids_price_cost'.$nn.$j, $weekday_kids_price_cost_result[$nn.$j], 'size="7"'.$price_input_readonly).'<br><input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.weekday_kids_price_cost'.$nn.$j.', weekday_kids_price'.$nn.$j.');"  type=button value="算底价(e)" '. $price_button.'>'; 
																?>
															</td>
														  </tr>
														</table>
													</div>
												</td>
											</tr>
										</table>
									</td>
							   		<?php 
								}else{
									  //echo $pInfo->products_is_regular_tour;?>
							<td colspan="6" width="50%" class="dataTableContent" align="left">
							<?php echo tep_draw_input_field('weekday_price'.$nn.$j,$weekday_price_result[$nn.$j],'size="7" '.$price_input_readonly.($check_spe_price_regular?'disabled="disabled"':'').''); ?>
							  

							 <?php if($weekday_single_price_result[$nn.$j]>0 || $weekday_single_pu_price_result[$nn.$j]>0 || $weekday_double_price_result[$nn.$j]>0 || $weekday_triple_price_result[$nn.$j]>0 || $weekday_quadruple_price_result[$nn.$j]>0 || $weekday_kids_price_result[$nn.$j]>0) { $display_val = 'visible';}else { $display_val='none';}?> 

							  <div id="regular_extra_price_<?php echo $nn.$j;?>" style="display:<?php echo  $display_val; ?>; padding-left:77px; margin-top:-19px;  float:left">
							  
							  &nbsp;<?php echo tep_draw_input_field('weekday_single_price'.$nn.$j, $weekday_single_price_result[$nn.$j], 'size="7"'.$price_input_readonly); ?>
							  &nbsp;<?php echo tep_draw_input_field('weekday_single_pu_price'.$nn.$j, $weekday_single_pu_price_result[$nn.$j], 'size="7"'.$price_input_readonly); ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo tep_draw_input_field('weekday_double_price'.$nn.$j, $weekday_double_price_result[$nn.$j], 'size="7"'.$price_input_readonly); ?>
						    &nbsp;&nbsp;&nbsp;<?php echo tep_draw_input_field('weekday_triple_price'.$nn.$j, $weekday_triple_price_result[$nn.$j], 'size="7"'.$price_input_readonly); ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo tep_draw_input_field('weekday_quadruple_price'.$nn.$j, $weekday_quadruple_price_result[$nn.$j], 'size="7"'.$price_input_readonly); ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo tep_draw_input_field('weekday_kids_price'.$nn.$j, $weekday_kids_price_result[$nn.$j], 'size="7"'.$price_input_readonly); ?>
							</div>
							</td>
							 <?php 
							 	}
								?>
								<td class="dataTableContent js_order" width="70" align="center">
									<?php echo tep_draw_input_field('weekday_sort_order'.$nn.$j, $weekday_sort_order_result[$nn.$j], 'size="7" onKeyUp="return weekday_sort_order_check_irregular('.$nn.$j.');"'); ?>
								</td>
								<td class="dataTableContent" width="10%" align="center">
									<?php 
									if(!tep_not_null($price_input_readonly)){
										if($check_spe_price_regular){
											?>
											<a href="javascript:void(0)" onclick="javascript:delete_spe_price_regular(<?php echo $nn.$j;?>,<?php echo ($access_full_edit=='true'?1:0)?>)">Delete spe. Price</a>
											
											<?php 
										}else{
											?>
											<a href="javascript:void(0)" id="show_<?php echo $nn.$j?>" onclick="javascript:show_hide_regular_div(<?php echo $nn.$j;?>, <?php echo ($display_room_yes==''?0:1)?>)">Sepecial Price</a>
											
											<?php 
										}
									}
									?>
								</td>
								<td><?php 
									if ($j > 1) {
										echo '<input type="button" value="同上" onclick="test(' . $j . ',' . $nn . ')"/>';
									} else {
										echo '&nbsp;';
									}?>
								</td>

                           </tr>
						<?php } ?>
						<tr>
							<td colspan="10"><?php echo tep_black_line(); ?></td>
					    </tr>
			  </table> 
		  	</td>
		 </tr>
		 
		 
		 <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		  <tr>
            <td class="main"></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('products_is_regular_tour'.$nn, '0', $not_regu, '', '') . '&nbsp;' . TEXT_PRODUCT_NOT_REGULAR_TOUR; ?></td>
          </tr>
		  <?php 
if ($pInfo->products_is_regular_tour != "" && $pInfo->products_is_regular_tour == '0') {
	$i = 1;
	$edit_irregular_data = '';
	$available_query = tep_db_query("select * from " . TABLE_PRODUCTS_REG_IRREG_DATE . " where products_id = " . $pInfo->products_id . " and operate_end_date='" . $pInfo->operate_end_date . "' and  operate_start_date='" . $pInfo->operate_start_date . "' order by available_date, products_start_day_id");
	while ($available = tep_db_fetch_array($available_query)) {
		if ($available['spe_double'] > 0 || $available['spe_triple'] > 0 || $available['spe_quadruple'] > 0 || $available['spe_kids'] > 0) {
			$check_spe_price = true;
		} else {
			$check_spe_price = false;
		}
		
		if ($access_full_edit == 'true') {
			
			$edit_irregular_data .= '
						<table border=0 id="table_id_irregular' . $nn . '_' . $i . '" cellpadding="2" cellspacing="2" width="100%">
						<tbody>
						<tr class="' . (floor($i / 2) == ($i / 2) ? 'attributes-even' : 'attributes-odd') . '">
						<td class="dataTableContent" width="15%"  style=" white-space:nowrap;">' . $i . '. <input name="avaliable_day_date_' . $nn . '_' . $i . '" size="12" value="' . $available['available_date'] . '" type="text"></td>
						<td class="dataTableContent" align="center" width="10%" ><input name="avaliable_day_prefix_' . $nn . '_' . $i . '" ' . $price_input_readonly . ' size="2" value="' . $available['prefix'] . '" type="text"></td>
						<td class="dataTableContent" align="left" width="16%"  style=" white-space:nowrap;">RP ' . $display_tour_agency_opr_currency_note . '<input name="avaliable_day_price_' . $nn . '_' . $i . '" ' . ($check_spe_price ? 'disabled="disabled"' : '') . $price_input_readonly . ' size="7" value="' . $available['extra_charge'] . '" type="text"><br>CP ' . $display_tour_agency_opr_currency_note . '<input name="avaliable_day_price_cost_' . $nn . '_' . $i . '" ' . $price_input_readonly . ' size="7" value="' . $available['extra_charge_cost'] . '" type="text"><br>&nbsp;&nbsp;&nbsp;<input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.avaliable_day_price_cost_' . $nn . '_' . $i . ', document.new_product.avaliable_day_price_' . $nn . '_' . $i . ');"  type=button value="算底价(e)" ' . $price_button . '></td>
						<td class="dataTableContent"  align="left" width="10%">';
			if ($check_spe_price) {
				$edit_irregular_data .= '<input name="avaliable_single_price_' . $nn . '_' . $i . '" size="7" ' . $price_input_readonly . ' value="' . $available['spe_single'] . '" type="text"><br><input name="avaliable_single_price_cost_' . $nn . '_' . $i . '"  size="7" ' . $price_input_readonly . ' value="' . $available['spe_single_cost'] . '" type="text"><br><input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.avaliable_single_price_cost_' . $nn . '_' . $i . ', document.new_product.avaliable_single_price_' . $nn . '_' . $i . ');"  type=button value="算底价(e)" ' . $price_button . '>';
			} else {
				$edit_irregular_data .= '<div id="show_sub_div_s_' . $nn . '_' . $i . '" style="display:none" ><input name="avaliable_single_price_' . $nn . '_' . $i . '" size="7" ' . $price_input_readonly . ' type="text"><br><input name="avaliable_single_price_cost_' . $nn . '_' . $i . '"  size="7" ' . $price_input_readonly . ' value="' . $available['spe_single_cost'] . '" type="text"><br><input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.avaliable_single_price_cost_' . $nn . '_' . $i . ', document.new_product.avaliable_single_price_' . $nn . '_' . $i . ');"  type=button value="算底价(e)" ' . $price_button . '></div><div id="hide_sub_div_s_' . $nn . '_' . $i . '" style="display:block" >' . tep_draw_separator("pixel_trans.gif", "54", "15") . '</div>';
			}
			$edit_irregular_data .= '</td>
						<td class="dataTableContent"  align="left" width="10%">';
			if ($check_spe_price) {
				$edit_irregular_data .= '<input name="avaliable_single_pu_price_' . $nn . '_' . $i . '" size="7" ' . $price_input_readonly . ' value="' . $available['spe_single_pu'] . '" type="text"><br><input name="avaliable_single_pu_price_cost_' . $nn . '_' . $i . '" ' . $price_input_readonly . ' size="7" value="' . $available['spe_single_pu_cost'] . '" type="text"><br><input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.avaliable_single_pu_price_cost_' . $nn . '_' . $i . ', document.new_product.avaliable_single_pu_price_' . $nn . '_' . $i . ');"  type=button value="算底价(e)" ' . $price_button . '>';
			} else {
				$edit_irregular_data .= '<div id="show_sub_div_sp_' . $nn . '_' . $i . '" style="display:none" ><input name="avaliable_single_pu_price_' . $nn . '_' . $i . '" size="7" ' . $price_input_readonly . ' type="text"><br><input name="avaliable_single_pu_price_cost_' . $nn . '_' . $i . '" ' . $price_input_readonly . ' size="7" value="' . $available['spe_single_pu_cost'] . '" type="text"><br><input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.avaliable_single_pu_price_cost_' . $nn . '_' . $i . ', document.new_product.avaliable_single_pu_price_' . $nn . '_' . $i . ');"  type=button value="算底价(e)" ' . $price_button . '></div><div id="hide_sub_div_sp_' . $nn . '_' . $i . '" style="display:block" >' . tep_draw_separator("pixel_trans.gif", "54", "15") . '</div>';
			}
			$edit_irregular_data .= '</td>
						<td class="dataTableContent"  align="left" width="10%">';
			if ($check_spe_price) {
				$edit_irregular_data .= '<input name="avaliable_double_price_' . $nn . '_' . $i . '" size="7" ' . $price_input_readonly . ' value="' . $available['spe_double'] . '" type="text"><br><input name="avaliable_double_price_cost_' . $nn . '_' . $i . '"  size="7" ' . $price_input_readonly . ' value="' . $available['spe_double_cost'] . '" type="text"><br><input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.avaliable_double_price_cost_' . $nn . '_' . $i . ', document.new_product.avaliable_double_price_' . $nn . '_' . $i . ');"  type=button value="算底价(e)" ' . $price_button . '>';
			} else {
				$edit_irregular_data .= '<div id="show_sub_div_d_' . $nn . '_' . $i . '" style="display:none" ><input name="avaliable_double_price_' . $nn . '_' . $i . '" size="7" ' . $price_input_readonly . ' type="text"><br><input name="avaliable_double_price_cost_' . $nn . '_' . $i . '"  size="7" ' . $price_input_readonly . ' value="' . $available['spe_double_cost'] . '" type="text"><br><input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.avaliable_double_price_cost_' . $nn . '_' . $i . ', document.new_product.avaliable_double_price_' . $nn . '_' . $i . ');"  type=button value="算底价(e)" ' . $price_button . '></div><div id="hide_sub_div_d_' . $nn . '_' . $i . '" style="display:block" >' . tep_draw_separator("pixel_trans.gif", "54", "15") . '</div>';
			}
			$edit_irregular_data .= '</td>
						<td class="dataTableContent" align="left" width="10%">';
			if ($check_spe_price) {
				$edit_irregular_data .= '<input name="avaliable_triple_price_' . $nn . '_' . $i . '" size="7" ' . $price_input_readonly . ' value="' . $available['spe_triple'] . '" type="text"><br><input name="avaliable_triple_price_cost_' . $nn . '_' . $i . '"  size="7" ' . $price_input_readonly . ' value="' . $available['spe_triple_cost'] . '" type="text"><br><input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.avaliable_triple_price_cost_' . $nn . '_' . $i . ', document.new_product.avaliable_triple_price_' . $nn . '_' . $i . ');"  type=button value="算底价(e)" ' . $price_button . '>';
			} else {
				$edit_irregular_data .= '<div id="show_sub_div_t_' . $nn . '_' . $i . '" style="display:none" ><input name="avaliable_triple_price_' . $nn . '_' . $i . '" size="7" ' . $price_input_readonly . ' type="text"><br><input name="avaliable_triple_price_cost_' . $nn . '_' . $i . '" ' . $price_input_readonly . ' size="7" value="' . $available['spe_triple_cost'] . '" type="text"><br><input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.avaliable_triple_price_cost_' . $nn . '_' . $i . ', document.new_product.avaliable_triple_price_' . $nn . '_' . $i . ');"  type=button value="算底价(e)" ' . $price_button . '></div><div id="hide_sub_div_t_' . $nn . '_' . $i . '" style="display:block" >' . tep_draw_separator("pixel_trans.gif", "54", "15") . '</div>';
			}
			$edit_irregular_data .= '</td>
						<td class="dataTableContent" align="left" width="10%">';
			if ($check_spe_price) {
				$edit_irregular_data .= '<input name="avaliable_quadruple_price_' . $nn . '_' . $i . '" size="7" ' . $price_input_readonly . ' value="' . $available['spe_quadruple'] . '" type="text"><br><input name="avaliable_quadruple_price_cost_' . $nn . '_' . $i . '"  size="7" ' . $price_input_readonly . ' value="' . $available['spe_quadruple_cost'] . '" type="text"><br><input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.avaliable_quadruple_price_cost_' . $nn . '_' . $i . ', document.new_product.avaliable_quadruple_price_' . $nn . '_' . $i . ');"  type=button value="算底价(e)" ' . $price_button . '>';
			} else {
				$edit_irregular_data .= '<div id="show_sub_div_q_' . $nn . '_' . $i . '" style="display:none" ><input name="avaliable_quadruple_price_' . $nn . '_' . $i . '" size="7" ' . $price_input_readonly . ' type="text"><br><input name="avaliable_quadruple_price_cost_' . $nn . '_' . $i . '"  size="7" ' . $price_input_readonly . ' value="' . $available['spe_quadruple_cost'] . '" type="text"><br><input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.avaliable_quadruple_price_cost_' . $nn . '_' . $i . ', document.new_product.avaliable_quadruple_price_' . $nn . '_' . $i . ');"  type=button value="算底价(e)" ' . $price_button . '></div><div id="hide_sub_div_q_' . $nn . '_' . $i . '" style="display:block" >' . tep_draw_separator("pixel_trans.gif", "54", "15") . '</div>';
			}
			$edit_irregular_data .= '</td>
						<td class="dataTableContent" align="left" width="10%">';
			if ($check_spe_price) {
				$edit_irregular_data .= '<input name="avaliable_kids_price_' . $nn . '_' . $i . '" size="7" ' . $price_input_readonly . ' value="' . $available['spe_kids'] . '" type="text"><br><input name="avaliable_kids_price_cost_' . $nn . '_' . $i . '"  size="7" ' . $price_input_readonly . ' value="' . $available['spe_kids_cost'] . '" type="text"><br><input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.avaliable_kids_price_cost_' . $nn . '_' . $i . ', document.new_product.avaliable_kids_price_' . $nn . '_' . $i . ');"  type=button value="算底价(e)" ' . $price_button . '>';
			} else {
				$edit_irregular_data .= '<div id="show_sub_div_k_' . $nn . '_' . $i . '" style="display:none" ><input name="avaliable_kids_price_' . $nn . '_' . $i . '" size="7" ' . $price_input_readonly . ' type="text"><br><input name="avaliable_kids_price_cost_' . $nn . '_' . $i . '" ' . $price_input_readonly . ' size="7" value="' . $available['spe_kids_cost'] . '" type="text"><br><input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.avaliable_kids_price_cost_' . $nn . '_' . $i . ', document.new_product.avaliable_kids_price_' . $nn . '_' . $i . ');"  type=button value="算底价(e)" ' . $price_button . '></div><div id="hide_sub_div_k_' . $nn . '_' . $i . '" style="display:block" >' . tep_draw_separator("pixel_trans.gif", "54", "15") . '</div>';
			}
			$edit_irregular_data .= '</td>

						<td class="dataTableContent" align="center" width=10%"><input name="avaliable_day_sort_order_' . $nn . '_' . $i . '" size="7" value="' . $available['sort_order'] . '" type="text"></td><td align="center" width="10%"><input src="includes/languages/schinese/images/buttons/button_delete.gif" name="delete_' . $nn . '_' . $i . '" ' . $price_button . ' onClick="return clearrow_irregular(' . $nn . ',' . $i . ');" type="image"></td><td></td><td colspan=2 class="dataTableContent" width="10%"> ' . (tep_not_null($price_input_readonly) ? '' : ($check_spe_price ? '<a href="javascript:void(0)" onclick="javascript:delete_spe_price(' . $nn . ',' . $i . ');">Delete spe. Price</a>' : '<a href="javascript:void(0)" onclick="javascript:enter_spec_price(' . $nn . ',' . $i . ')">Sepecial Price</a>')) . ' </td><td>';
			if ($i > 1) {
				$edit_irregular_data .= '<input type="button" onclick="mytest(' . $i . ',' . $nn . ')" value="同上" />';
			}
			$edit_irregular_data .= '</td>
						</tr></tbody></table>';
		} else {
			$edit_irregular_data .= '<table border=0 id="table_id_irregular' . $nn . '_' . $i . '" cellpadding="2" cellspacing="2" width="100%"><tbody><tr class="' . (floor($i / 2) == ($i / 2) ? 'attributes-even' : 'attributes-odd') . '"><td class="dataTableContent" width="15%">' . $i . '. <input name="avaliable_day_date_' . $nn . '_' . $i . '" size="12" value="' . $available['available_date'] . '" type="text"></td><td class="dataTableContent" align="center" width="10%" ><input name="avaliable_day_prefix_' . $nn . '_' . $i . '" ' . $price_input_readonly . ' size="2" value="' . $available['prefix'] . '" type="text"></td><td class="dataTableContent" align="left" width="10%"><input name="avaliable_day_price_' . $nn . '_' . $i . '" ' . ($check_spe_price ? 'disabled="disabled"' : '') . ' size="7" value="' . $available['extra_charge'] . '" type="text"></td><td class="dataTableContent"  align="left" width="10%">' . ($check_spe_price ? '<input name="avaliable_single_price_' . $nn . '_' . $i . '" ' . $price_input_readonly . ' size="7" value="' . $available['spe_single'] . '" type="text">' : '<div id="show_sub_div_s_' . $nn . '_' . $i . '" style="display:none" ><input name="avaliable_single_price_' . $nn . '_' . $i . '" size="7" ' . $price_input_readonly . ' type="text"></div><div id="hide_sub_div_sp_' . $nn . '_' . $i . '" style="display:block" >' . tep_draw_separator("pixel_trans.gif", "54", "15") . '&nbsp;') . '</div></td><td class="dataTableContent"  align="left" width="10%">' . ($check_spe_price ? '<input name="avaliable_single_pu_price_' . $nn . '_' . $i . '" ' . $price_input_readonly . ' size="7" value="' . $available['spe_single_pu'] . '" type="text">' : '<div id="show_sub_div_sp_' . $nn . '_' . $i . '" style="display:none" ><input name="avaliable_single_pu_price_' . $nn . '_' . $i . '" ' . $price_input_readonly . ' size="7"  type="text"></div><div id="hide_sub_div_sp_' . $nn . '_' . $i . '" style="display:block" >' . tep_draw_separator("pixel_trans.gif", "54", "15") . '&nbsp;') . '</div></td><td class="dataTableContent"  align="left" width="10%">' . ($check_spe_price ? '<input name="avaliable_double_price_' . $nn . '_' . $i . '" ' . $price_input_readonly . ' size="7" value="' . $available['spe_double'] . '" type="text">' : '<div id="show_sub_div_d_' . $nn . '_' . $i . '" style="display:none" ><input name="avaliable_double_price_' . $nn . '_' . $i . '" ' . $price_input_readonly . ' size="7"  type="text"></div><div id="hide_sub_div_d_' . $nn . '_' . $i . '" style="display:block" >' . tep_draw_separator("pixel_trans.gif", "54", "15") . '&nbsp;') . '</div></td><td class="dataTableContent" align="left" width="10%">' . ($check_spe_price ? '<input name="avaliable_triple_price_' . $nn . '_' . $i . '" ' . $price_input_readonly . ' size="7" value="' . $available['spe_triple'] . '" type="text">' : '<div id="show_sub_div_t_' . $nn . '_' . $i . '" style="display:none" ><input name="avaliable_triple_price_' . $nn . '_' . $i . '" ' . $price_input_readonly . ' size="7"  type="text"></div><div id="hide_sub_div_t_' . $nn . '_' . $i . '" style="display:block" >' . tep_draw_separator("pixel_trans.gif", "54", "15") . '&nbsp;') . '</div></td><td class="dataTableContent" align="left" width="10%">' . ($check_spe_price ? '<input name="avaliable_quadruple_price_' . $nn . '_' . $i . '" ' . $price_input_readonly . ' size="7" value="' . $available['spe_quadruple'] . '" type="text">' : '<div id="show_sub_div_q_' . $nn . '_' . $i . '" style="display:none" ><input name="avaliable_quadruple_price_' . $nn . '_' . $i . '" ' . $price_input_readonly . ' size="7"  type="text"></div><div id="hide_sub_div_q_' . $nn . '_' . $i . '" style="display:block" >' . tep_draw_separator("pixel_trans.gif", "54", "15") . '&nbsp;') . '</div></td><td class="dataTableContent" align="left" width="10%">' . ($check_spe_price ? '<input name="avaliable_kids_price_' . $nn . '_' . $i . '" ' . $price_input_readonly . ' size="7" value="' . $available['spe_kids'] . '" type="text">' : '<div id="show_sub_div_k_' . $nn . '_' . $i . '" style="display:none" ><input name="avaliable_kids_price_' . $nn . '_' . $i . '" ' . $price_input_readonly . ' size="7" type="text"></div><div id="hide_sub_div_k_' . $nn . '_' . $i . '" style="display:block" >' . tep_draw_separator("pixel_trans.gif", "54", "15") . '&nbsp;') . '</div></td><td class="dataTableContent" align="center" width=10%"><input name="avaliable_day_sort_order_' . $nn . '_' . $i . '" size="7" value="' . $available['sort_order'] . '" type="text"></td><td align="center" width="10%"><input src="includes/languages/schinese/images/buttons/button_delete.gif" name="delete_' . $nn . '_' . $i . '" ' . $price_button . ' onClick="return clearrow_irregular(' . $nn . ',' . $i . ');" type="image"></td><td></td><td colspan=2 class="dataTableContent" width="10%"> ' . (tep_not_null($price_input_readonly) ? '' : ($check_spe_price ? '<a href="javascript:void(0)" onclick="javascript:delete_spe_price(' . $nn . ',' . $i . ');">Delete spe. Price</a>' : '<a href="javascript:void(0)" onclick="javascript:enter_spec_price(' . $nn . ',' . $i . ')">Sepecial Price</a>')) . ' </td></tr></tbody></table>';
		}
		$i ++;
	}
}
		?>
         <tr> 
		 <td class="main"></td>
            <td class="main" >
					  <table border="0" width="100%" cellpadding="2" cellspacing="0" >
					      <tr>
							<td colspan="15"><?php echo tep_black_line(); ?></td>
						  </tr>
						  <tr class="dataTableHeadingRow">
						    <td colspan="15" class="attributeBoxContent" align="left">
							Irregular Tour</td>
						  </tr>
						   <tr>
							<td colspan="15"><?php echo tep_black_line(); ?></td>
						  </tr>
					  	<tr>
						    <td colspan="15" class='main'>
							Durations small description 
							<?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' .tep_draw_textarea_field('products_durations_description'.$nn, 'soft', '100', '2',$pInfo->products_durations_description); ?>							</td>
					    </tr>
						  <tr>
							<td colspan="15"><?php echo tep_black_line(); ?></td>
						  </tr>
						  <tr class="dataTableHeadingRow">
							<td class="dataTableHeadingContent" width="15%" align="left">Dates</td>
							<td class="dataTableHeadingContent" width="10%" align="center">Prefix +/-</td>
							<td class="dataTableHeadingContent" width="13%" align="center">Price</td>
							<td class="dataTableHeadingContent" width="10%" align="center"><?php if($display_room_yes){ echo 'Single'; }else{echo 'Adult';}?></td>
							<td class="dataTableHeadingContent" width="10%" align="center">Single PU</td>
							<td class="dataTableHeadingContent" width="10%" align="center">Double</td>
							<td class="dataTableHeadingContent" width="10%" align="center">Tiple</td>
							<td class="dataTableHeadingContent" width="10%" align="center">Quadruple</td>
							<td class="dataTableHeadingContent" width="10%" align="center">Kids</td>
							<td class="dataTableHeadingContent" width="10%"align="center" colspan="center">Sort<br /> 
						    Order</td>
							<td class="dataTableHeadingContent" width="10%" align="center" colspan="center">Insert</td>
							<td class="dataTableHeadingContent" width="10%" align="center" colspan="center">Special</td>
						  </tr>
						  <tr>
							<td colspan="15"><?php echo tep_black_line(); ?></td>
						  </tr>
						  
						 
						  <tr class="dataTableRow">
							<td class=dataTableContent colspan="15">
							  <div id="div_id_avaliable_day_date<?=$nn;?>">
							  	<?php 
									if($pInfo->products_is_regular_tour != "" && $pInfo->products_is_regular_tour == '0')
									{
										echo $edit_irregular_data;
									}
								?>
							  </div>						  	</td>	
						  </tr>						  
						  
						
						 <tr>
							<td colspan="15"><?php echo tep_black_line(); ?></td>
					    </tr>		
						  
						 
						   <tr class="dataTableRow" bgcolor="#FF0000">
							<td class="dataTableContent" width="15%"  style=" white-space:nowrap;"><input type="hidden" name="numberofdates<?=$nn;?>" value="<?php echo $i; ?>">&nbsp;<?php echo tep_draw_input_field('avaliable_date_'.$nn, '', 'size="10" id="avaliable_date_'.$nn.'"');?><a href="javascript:NewCal('avaliable_date_<?php echo $nn;?>');"><img src='images/cal.gif' width='16' height='16' border='0' alt='Pick a date'></a><br />(YYYY-MM-DD)</td>
							<td class="dataTableContent" width="10%" align="center"><?php echo tep_draw_input_field('avaliable_day_prefix'.$nn, '', 'size="2"'); ?></td>
							 <?php  if($access_full_edit == 'true') {?>
							 <td colspan="7"  class="dataTableContent" align="left" valign="top" style=" white-space:nowrap;">
							 <table  border="0" cellspacing="5" cellpadding="5">
								  <tr>
									<td class="dataTableContent" nowrap>
									<?php echo 'RP '.$display_tour_agency_opr_currency_note.tep_draw_input_field('avaliable_day_price'.$nn, '', 'size="7"'.$price_input_readonly).'<br>CP '.$display_tour_agency_opr_currency_note.tep_draw_input_field('avaliable_day_price_cost'.$nn, '', 'size="7"'.$price_input_readonly).'<br>&nbsp;&nbsp;&nbsp;<input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.avaliable_day_price_cost'.$nn.', avaliable_day_price'.$nn.');"  type=button value="算底价(e)" '. $price_button.'>'; ?>
									</td>
									<td>									
							   <div id="extra_price_<?php echo $nn;?>" style="display:none; float:left">
								<table width="100%"   border="0" cellspacing="5" cellpadding="5">
								  <tr>
									<td width="10%"><?php echo tep_draw_input_field('avaliable_single_price'.$nn, '', 'size="7"'.$price_input_readonly).'<br>'.tep_draw_input_field('avaliable_single_price_cost'.$nn, '', 'size="7"'.$price_input_readonly).'<br><input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.avaliable_single_price_cost'.$nn.', avaliable_single_price'.$nn.');"  type=button value="算底价(e)" '. $price_button.'>'; ?>
							 		</td>
									<td width="10%"><?php echo tep_draw_input_field('avaliable_single_pu_price'.$nn, '', 'size="7"'.$price_input_readonly).'<br>'.tep_draw_input_field('avaliable_single_pu_price_cost'.$nn, '', 'size="7"'.$price_input_readonly).'<br><input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.avaliable_single_pu_price_cost'.$nn.', avaliable_single_pu_price'.$nn.');"  type=button value="算底价(e)" '. $price_button.'>'; ?>
							 		</td>
									<td width="10%"> <?php echo tep_draw_input_field('avaliable_double_price'.$nn, '', 'size="7"'.$price_input_readonly).'<br>'.tep_draw_input_field('avaliable_double_price_cost'.$nn, '', 'size="7"'.$price_input_readonly).'<br><input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.avaliable_double_price_cost'.$nn.', avaliable_double_price'.$nn.');"  type=button value="算底价(e)" '. $price_button.'>'; ?>
						   			</td>
									<td width="10%"><?php echo tep_draw_input_field('avaliable_triple_price'.$nn, '', 'size="7"'.$price_input_readonly).'<br>'.tep_draw_input_field('avaliable_triple_price_cost'.$nn, '', 'size="7"'.$price_input_readonly).'<br><input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.avaliable_triple_price_cost'.$nn.', avaliable_triple_price'.$nn.');"  type=button value="算底价(e)" '. $price_button.'>'; ?>
									 </td>
									<td width="10%"><?php echo tep_draw_input_field('avaliable_quadruple_price'.$nn, '', 'size="7"'.$price_input_readonly).'<br>'.tep_draw_input_field('avaliable_quadruple_price_cost'.$nn, '', 'size="7"'.$price_input_readonly).'<br><input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.avaliable_quadruple_price_cost'.$nn.', avaliable_quadruple_price'.$nn.');"  type=button value="算底价(e)" '. $price_button.'>'; ?>
							 		</td>
									<td width="10%"> <?php echo tep_draw_input_field('avaliable_kids_price'.$nn, '', 'size="7"'.$price_input_readonly).'<br>'.tep_draw_input_field('avaliable_kids_price_cost'.$nn, '', 'size="7"'.$price_input_readonly).'<br><input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.avaliable_kids_price_cost'.$nn.', avaliable_kids_price'.$nn.');"  type=button value="算底价(e)" '. $price_button.'>'; ?>
									</td>
								  </tr>
								</table>
								</div>
								</td>
								</tr>
									</table>
								
							</td>
							   <?php }else{ ?>
							<td colspan="7" width="50%" class="dataTableContent"  align="left">
							<?php echo tep_draw_input_field('avaliable_day_price'.$nn, '', 'size="7"'); ?>
							  <div id="extra_price_<?php echo $nn;?>" style="display:none; padding-left:77px; margin-top:-19px;  float:left">&nbsp;<?php echo tep_draw_input_field('avaliable_single_price'.$nn, '', 'size="7"'); ?>&nbsp;<?php echo tep_draw_input_field('avaliable_single_pu_price'.$nn, '', 'size="7"'); ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo tep_draw_input_field('avaliable_double_price'.$nn, '', 'size="7"'); ?>
						    &nbsp;&nbsp;&nbsp;<?php echo tep_draw_input_field('avaliable_triple_price'.$nn, '', 'size="7"'); ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo tep_draw_input_field('avaliable_quadruple_price'.$nn, '', 'size="7"'); ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo tep_draw_input_field('avaliable_kids_price'.$nn, '', 'size="7"'); ?>
							</div>
							</td>
							 <?php } ?>
							<td class="dataTableContent" width="10%" align="center"><?php echo tep_draw_input_field('avaliable_day_sort_order'.$nn, '', 'size="7"'); ?></td>							
							<td class="dataTableContent" width="10%" align="center"><?php echo tep_image_submit('button_insert.gif', '', $price_button.'onClick="return add_irregular('.$nn.')"'); ?> </td>
							<td class="dataTableContent" width="10%" align="center"><?php echo (tep_not_null($price_input_readonly) ? ' ' : '<a href="javascript:void(0)" onclick="javascript:show_hide_div('.$nn.')">Sepecial Price</a>');?></td>
                        	</tr>						  						
						   
						 <tr>
							<td  height="10" colspan="15">&nbsp;</td>
					    </tr>	 
						<tr>
							<td colspan="15"><?php echo tep_black_line(); ?></td>
					    </tr>
						  <tr>
							<td  height="50" colspan="15">&nbsp;</td>
						  </tr>
			  </table> 
					  
		  	</td>
		 </tr>
		 
			 
		 
			
		 <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
</table>
		<?php
		unset($checked);
		unset($weekday_start_date_id_result);
		unset($weekday_prefix_result);
		unset($weekday_price_result);
		unset($weekday_sort_order_result);
		$sec_cnt_db++;
		}
	}
}

for($nn=$sec_cnt_db;$nn<=$totalsections;$nn++){

	$pInfo->products_is_regular_tour="";

$pInfo->products_id="";

$pInfo->operate_start_date="";

$pInfo->products_durations_description = "";
$pInfo->operate_end_date="";
switch ($pInfo->products_is_regular_tour) 
		{
    		  case '0': $is_regu = false; $not_regu = true; break;
    		  case '1':
    		  default: $is_regu = true; $not_regu = false;
   		 }

	?>
	
		 <table  border="0" cellspacing="1" cellpadding="1">
		   <tr>
            <td class="main" nowrap><?php echo 'Tour Start Date'; ?></td>
            <td class="main"><?php 
			if($pInfo->operate_start_date == ''){
			$pInfo->operate_start_date ='01-01';
			}			
			$operate_start_date_array = explode('-',$pInfo->operate_start_date);
			echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' .tep_draw_pull_down_menu('operate_start_date_month'.$nn, $months_operate_array, $operate_start_date_array[0],'').'&nbsp;'.tep_draw_pull_down_menu('operate_start_date_day'.$nn, $days_operate_array, $operate_start_date_array[1],''); ?></td>
          </tr>
		 <tr>
            <td class="main"><?php echo 'Tour End Date'; ?></td>
            <td class="main"><?php 
			if($pInfo->operate_end_date == ''){
			$pInfo->operate_end_date ='12-31';
			}
			$operate_end_date_array = explode('-',$pInfo->operate_end_date);
			
			echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' .tep_draw_pull_down_menu('operate_end_date_month'.$nn, $months_operate_array, $operate_end_date_array[0],'').'&nbsp;'.tep_draw_pull_down_menu('operate_end_date_day'.$nn, $days_operate_array, $operate_end_date_array[1],''); ?></td>
         </tr>
		   <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_TYPE; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('products_is_regular_tour'.$nn, '1', $is_regu, '','') . '&nbsp;' . TEXT_PRODUCT_REGULAR_TOUR ; ?></td>
          </tr>
         <?php 
		 if($pInfo->products_is_regular_tour == "") $daily_check_true = 'CHECKED'; //IF INSERTING NEW PRODUCT
		
		if($pInfo->products_id && $pInfo->products_is_regular_tour != "" && $pInfo->products_is_regular_tour == '1')
		{
			$daily_check_true = '';
			$week_check_true = '';
			

				$startdate_count_query = tep_db_query("select count(*) as start_count from " . TABLE_PRODUCTS_START_DATE . " where products_id = ". $pInfo->products_id ." order by products_start_day");
				$startdate_count = tep_db_fetch_array($startdate_count_query); 
				//$startdate_count['start_count'];
				if($startdate_count['start_count'] == '10')
				{
					$daily_check_true = 'CHECKED';
   				    $i =1;
					$startdate_query = tep_db_query("select * from " . TABLE_PRODUCTS_REG_IRREG_DATE . " where products_id = ". $pInfo->products_id ." and operate_end_date='".$pInfo->operate_end_date."' and  operate_start_date='".$pInfo->operate_start_date."' order by products_start_day");
					while ($startdate = tep_db_fetch_array($startdate_query)) 
					{
						$daily_start_date_id_result[$i] = $startdate['products_start_day_id'];
						$daily_price_result[$i] = $startdate['extra_charge'];
						$daily_prefix_result[$i] =  $startdate['prefix'];
						$daily_sort_order_result[$i] =  $startdate['sort_order'];
						$i++;
					}
				}
				else
				{
					$week_check_true = 'CHECKED';
					$i =1;
					$startdate_query = tep_db_query("select * from " . TABLE_PRODUCTS_REG_IRREG_DATE . " where products_id = ".$pInfo->products_id." and operate_end_date='".$pInfo->operate_end_date."' and  operate_start_date='".$pInfo->operate_start_date."' order by products_start_day");
					while ($startdate = tep_db_fetch_array($startdate_query)) 
					{
						
						$checked[$startdate['products_start_day']] = "CHECKED";
						$weekday_start_date_id_result[$startdate['products_start_day']] = $startdate['products_start_day_id'];
						$weekday_price_result[$startdate['products_start_day']] = $startdate['extra_charge'];
						$weekday_price_result_cost[$startdate['products_start_day']] = $startdate['extra_charge_cost'];
						$weekday_prefix_result[$startdate['products_start_day']] =  $startdate['prefix'];
						$weekday_sort_order_result[$startdate['products_start_day']] =  $startdate['sort_order'];
						$i++;
					}
				}
		}
		 
		 ?>
		 <tr>
		 <td class="main"></td>
            <td class="main">					 
					  <table border="0" cellpadding="2" cellspacing="0" bgcolor="FFFFFF">
					 
						  <tr>
							<td colspan="4"><?php echo tep_black_line(); ?></td>
						  </tr>
						  <tr class="dataTableHeadingRow">
							<td class="dataTableHeadingContent" width="250" align="left"></td>
							<td class="dataTableHeadingContent" width="50" align="center">Prefix +/-</td>
							<td class="dataTableHeadingContent" width="70" >Price</td>
							<td class="dataTableHeadingContent" width="70" align="center">Sort Order</td>
						  </tr>
						  <tr>
							<td colspan="4"><?php echo tep_black_line(); ?></td>
						  </tr>
						  <?php
						  for($j=1;$j<8;$j++)
						  {
						  	if($j==1) $day = 'Sunday';
							if($j==2) $day = 'Monday';
							if($j==3) $day = 'Tuesday';
							if($j==4) $day = 'Wednesday';
							if($j==5) $day = 'Thursday';
							if($j==6) $day = 'Friday';
							if($j==7) $day = 'Saturday';
						  ?>						 
						   <tr class="dataTableRow">
							<td class="dataTableContent">&nbsp;<input type="hidden" name="weekday_start_date_id_<?php echo $nn."_".$j;?>" value="<?php echo $weekday_start_date_id_result[$j];?>">
							<?php echo tep_draw_checkbox_field('weekday_option'.$nn."_".$j, $checked[$j], $checked[$j]) . '&nbsp;' .$day; ?></td>
							<td class="dataTableContent" width="50" align="center"><?php echo tep_draw_input_field('weekday_prefix'.$nn.$j, $weekday_prefix_result[$j], 'size="2" onKeyUp="return weekday_prefix_check_irregular('.$nn.$j.');"'); ?></td>
							<td class="dataTableContent" width="70" align="center">
							<?php //echo tep_draw_input_field('weekday_price'.$nn.$j, $weekday_price_result[$j], 'size="7" onKeyUp="return weekday_price_check_irregular('.$nn.$j.');"'); ?></td>
							<?php 
							echo tep_draw_input_field('weekday_price'.$nn.$j, $weekday_price_result[$j], 'size="7" onKeyUp="return weekday_price_check_irregular('.$nn.$j.');"'.$price_input_readonly); 
							if($access_full_edit == 'true') { 
							echo  '&nbsp;' . tep_draw_input_field('weekday_price_cost'.$nn.$j, $weekday_price_result_cost[$j], 'size="7"'.$price_input_readonly); ?>&nbsp;<input onClick="calculate_retail_price(document.new_product.products_margin,  document.new_product.weekday_price_cost<?php echo $nn.$j;?>, document.new_product.weekday_price<?php echo $nn.$j;?>);"  type=button value="算底价(e)" <?= $price_button;?>>
							<?php } ?>
							<td class="dataTableContent" width="70" align="center"><?php echo tep_draw_input_field('weekday_sort_order'.$nn.$j, $weekday_sort_order_result[$j], 'size="7" onKeyUp="return weekday_sort_order_check_irregular('.$nn.$j.');"'); ?></td>
                           </tr>
						<?php } ?>
						<tr>
							<td colspan="4"><?php echo tep_black_line(); ?></td>
					    </tr>
			  </table> 
		  	</td>
		 </tr>
		 
		 
		 <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		  <tr>
            <td class="main"></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('products_is_regular_tour'.$nn, '0', $not_regu, '', '') . '&nbsp;' . TEXT_PRODUCT_NOT_REGULAR_TOUR; ?></td>
          </tr>
		  <?php $i =1;
		 		if($pInfo->products_is_regular_tour != "" && $pInfo->products_is_regular_tour == '0')
				{
						$available_query = tep_db_query("select * from " . TABLE_PRODUCTS_REG_IRREG_DATE . " where products_id = ".$pInfo->products_id." and operate_end_date='".$pInfo->operate_end_date."' and  operate_start_date='".$pInfo->operate_start_date."' order by  available_date, products_start_day_id");
						while ($available = tep_db_fetch_array($available_query)) 
						{
			$edit_irregular_data .= '<table id="table_id_irregular'.$nn.'_'.$i.'" cellpadding="2" cellspacing="2" width="100%"><tbody><tr class="'.(floor($i/2) == ($i/2) ? 'attributes-even' : 'attributes-odd').'"><td class="dataTableContent">'.$i.'. <input name="avaliable_day_date_'.$nn.'_'.$i.'" size="12" value="'.$available['available_date'].'" type="text"></td><td class="dataTableContent" align="center" width="50"><input name="avaliable_day_prefix_'.$nn.'_'.$i.'" '.$price_input_readonly.' size="2" value="'.$available['prefix'].'" type="text"></td><td class="dataTableContent" align="center" width="70"><input name="avaliable_day_price_'.$nn.'_'.$i.'" size="7" value="'.$available['extra_charge'].'" type="text"></td><td class="dataTableContent" align="center" width="70"><input name="avaliable_day_sort_order_'.$nn.'_'.$i.'" size="7" value="'.$available['sort_order'].'" type="text"></td><td align="center" width="70"><input src="includes/languages/schinese/images/buttons/button_delete.gif" name="delete_'.$nn.'_'.$i.'" '.$price_button.' onClick="return clearrow_irregular('.$nn.','.$i.');" type="image"></td></tr></tbody></table>';
							$i++;
						}
					  
				}
		?>
         <tr> 
		 <td class="main"></td>
            <td class="main">
					  <table border="0" cellpadding="2" cellspacing="0">
					      <tr>
							<td colspan="5"><?php echo tep_black_line(); ?></td>
						  </tr>
						  <tr class="dataTableHeadingRow">
						    <td colspan="5" class="attributeBoxContent" align="left">
							Irregular Tour</td>
						  </tr>
						    <tr>
							<td colspan="4"><?php echo tep_black_line(); ?></td>
						  </tr>
					  	<tr>
						    <td colspan="5" class='main'>
							Durations small description
							<?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' .tep_draw_textarea_field('products_durations_description'.$nn, 'soft', '100', '2',$pInfo->products_durations_description); ?>
							</td>
					    </tr>
						  <tr>
							<td colspan="5"><?php echo tep_black_line(); ?></td>
						  </tr>
						  <tr class="dataTableHeadingRow">
							<td class="dataTableHeadingContent" width="250" align="left">Dates</td>
							<td class="dataTableHeadingContent" width="50" align="center">Prefix +/-</td>
							<td class="dataTableHeadingContent" width="70" >Price</td>
							<td class="dataTableHeadingContent" width="70" align="center">Sort Order</td>
							<td class="dataTableHeadingContent" width="70" align="center"></td>
						  </tr>
						  <tr>
							<td colspan="5"><?php echo tep_black_line(); ?></td>
						  </tr>
						  
						 
						  <tr class="dataTableRow">
							<td class=dataTableContent colspan="5">
							  <div id="div_id_avaliable_day_date<?=$nn;?>"></div>	
						  	</td>	
						  </tr>
						  
						  
						<?php 
						if($pInfo->products_is_regular_tour != "" && $pInfo->products_is_regular_tour == '0')
						{
						?>
						<script>document.getElementById("div_id_avaliable_day_date<?=$nn;?>").innerHTML = '<?php echo $edit_irregular_data; ?>';</script>
						<?php 
						} 
						?>
						 <tr>
							<td colspan="5"><?php echo tep_black_line(); ?></td>
					    </tr>		
						  
						  <tr class="dataTableRow">
							<td class="dataTableContent"><input type="hidden" name="numberofdates<?=$nn;?>" value="<?php echo $i; ?>">&nbsp;<?php echo tep_draw_input_field('avaliable_date_'.$nn, '', 'size="10" id="avaliable_date_'.$nn.'"' ); ?><a href="javascript:NewCal('avaliable_date_<?php echo $nn;?>');"><img src='images/cal.gif' width='16' height='16' border='0' alt='Pick a date'></a>(YYYY-MM-DD)</td>
							<td class="dataTableContent" width="50" align="center"><?php echo tep_draw_input_field('avaliable_day_prefix'.$nn, '', 'size="2"'); ?></td>
							<td class="dataTableContent" width="70" align="center"><?php echo tep_draw_input_field('avaliable_day_price'.$nn, '', 'size="7"'); ?></td>
							<td class="dataTableContent" width="70" align="center"><?php echo tep_draw_input_field('avaliable_day_sort_order'.$nn, '', 'size="7"'); ?></td>
							<td class="dataTableContent" width="70" align="center"><?php echo tep_image_submit('button_insert.gif', '', $price_button.'onClick="return add_irregular('.$nn.')"'); ?>
							<input type="button" onClick="return add_irregular(<?php echo $nn;?>)" value="Insert">
							</td>
                        </tr>
						   
						 <tr>
							<td  height="10" colspan="5">&nbsp;</td>
					    </tr>	 
						<tr>
							<td colspan="5"><?php echo tep_black_line(); ?></td>
					    </tr>
						  <tr>
							<td  height="50" colspan="5">&nbsp;</td>
						  </tr>
			  </table> 
					  
		  	</td>
		 </tr>
		 
			
		 <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		</table>

	<?php
		
	}


}
?>