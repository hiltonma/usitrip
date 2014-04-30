<?php if( (int)$_GET['cPath']>0 || basename($PHP_SELF)!='index.php'){?>
	  <div id="search2"><div class="w936x">
			    <div id="navg" class="tb2_">
				    <ul>
					  <li class="hovertabx"><?php echo db_to_html('旅行团')?></li>
					  
					  <?php /*暂时取消这些选项
					  <li class="normaltabx">&nbsp;住 宿</li>
					  <li class="normaltabx">&nbsp;景 点</li>
					  */?>
				    </ul>
			    </div>
			    <div class="cttx" id="cont">
				<?php echo tep_draw_form('advanced_search', tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false), 'get', '') . tep_hide_session_id(); ?>
				  <div class="disx"><div class="input_s">
				  <h4 class="ttt"><?php echo db_to_html('出发地')?></h4>
				  <?php
					$city_class_query = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as c, " . TABLE_ZONES . " as s, " . TABLE_COUNTRIES . " as co   where c.state_id = s.zone_id and c.departure_city_status = '1' and s.zone_country_id = co.countries_id and c.city !='' order by c.city");
					$city_class_array[0]['id'] = '';
					$city_class_array[0]['text'] = db_to_html('-选择出发地-');
					 
					while ($city_class = tep_db_fetch_array($city_class_query)) 
					{
						$city_class_array[] = array('id' => $city_class['city_id'],
						'text' => db_to_html($city_class['city']));
					}
					echo tep_draw_pull_down_menu('departure_city_id', $city_class_array, $_GET['departure_city_id'], 'class="input_search"');
					unset($city_class_array);											
				?>
				  
					</select>
				  <h4 class="ttt"><?php echo db_to_html('行程天数')?></h4>
					<select name="products_durations" id="products_durations" class="input_search">
						<option value="" selected="selected"><?php echo TEXT_DURATION_OPTION_1;?></option>											
						<option value="0-1"><?php echo TEXT_DURATION_OPTION_LESS_ONE;?></option>
						<option value="1-1"><?php echo TEXT_DURATION_OPTION_2;?></option>
						<option value="2-2"><?php echo TEXT_DURATION_OPTION_3;?></option>
						<option value="2-3"><?php echo TEXT_DURATION_OPTION_4;?></option>
						<option value="3-3"><?php echo TEXT_DURATION_OPTION_5;?></option>
						<option value="3-4"><?php echo TEXT_DURATION_OPTION_6;?></option>
						<option value="4-4"><?php echo TEXT_DURATION_OPTION_7;?></option>
						<option value="4-"><?php echo TEXT_DURATION_OPTION_8;?></option>
						<option value="5-"><?php echo TEXT_DURATION_OPTION_9;?></option>
					</select>
					<?php
					if($HTTP_GET_VARS['products_durations'] != '') { 
						$javavar ="document.advanced_search.products_durations.value='".$HTTP_GET_VARS['products_durations']."';";
						echo '<script type="text/javascript">';
						echo $javavar;
						echo '</script>';
					}
					?>


				  <h4 class="ttt"><?php echo db_to_html('价格')?></h4>
				  <select name="products_price" id="products_price" class="input_search">
						<option value="0" selected="selected"><?php echo db_to_html('选择价格范围')?></option>											
						<option value="1"><?php echo db_to_html('500以下')?></option>
						<option value="2"><?php echo db_to_html('500-1000')?></option>
						<option value="3"><?php echo db_to_html('1000-3000')?></option>
						<option value="4"><?php echo db_to_html('3000-7000')?></option>
                        <option value="5"><?php echo db_to_html('7000-15000')?></option>
						<option value="6"><?php echo db_to_html('15000以上')?></option>
					</select>
					</div>
					
					<div class="input_s2">
					  <h4 class="ttt"><?php echo db_to_html('目的地')?></h4>
				  <?php
					$city_class_query = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as c, " . TABLE_ZONES . " as s, " . TABLE_COUNTRIES . " as co   where c.state_id = s.zone_id and c.departure_city_status = '1' and s.zone_country_id = co.countries_id and c.city !='' order by c.city");
					
					$city_class_array[0]['id'] = '';
					$city_class_array[0]['text'] = db_to_html('-选择目的地-');
					while ($city_class = tep_db_fetch_array($city_class_query)) 
					{
						$city_class_array[] = array('id' => $city_class['city_id'],
						'text' => db_to_html($city_class['city']));
					}
					echo tep_draw_pull_down_menu('purpose_city_id', $city_class_array, $_GET['purpose_city_id'], 'class="input_search"'); 
					unset($city_class_array);											
				?>
					  
					</select>
					  <h4 class="ttt"><?php echo db_to_html('出发日期')?></h4>
					  <script type="text/javascript"><!--
						var dateAvailable = new ctlSpiffyCalendarBox("dateAvailable", "advanced_search", "products_date_available","btnDate","",scBTNMODE_CUSTOMBLUE);
						dateAvailable.writeControl(); dateAvailable.dateFormat="yyyy-MM-dd";
					//--></script>
					
					</div>
				    
					<div class="guanjianzi">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td><?php echo tep_draw_input_field('keywords',$HTTP_GET_VARS['keywords'],' class="input_search2" size="26"'); ?></td>
						<td><div class="button"><?php echo tep_template_image_submit('button_search.gif', 'Search'); ?></div></td>
					  </tr>
					</table>

				    </div>
				   <?php /*热门关键词
				   <div class="zizi">热门关键词∶哈哈 细心系 咯咯 哈哈</div>
				   */?>

				  </div>
				</form>  
				  
				  <div class="undisx"><div class="input_s2"><h4 class="ttt">出发日期</h4>
				    <select name="" size="1" class="input_search">
					  <option></option>
				  </select></div></div>
				  <div class="undisx"><div class="input_s2"><h4 class="ttt">出发日期</h4>
				    <select name="" size="1" class="input_search">
					  <option></option>
				  </select></div></div>
			    </div>
				  </div>
			    <div class="clear"></div></div>
<?php }?>