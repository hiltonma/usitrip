<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-cache, must-revalidate");           // HTTP/1.1
header("Pragma: no-cache");   
/*
include_once "includes/configure.php";
include_once "includes/database_tables.php";
include_once "includes/functions/database.php";
include_once "includes/functions/html_output.php";
include_once "includes/functions/general.php";
*/
include_once "includes/application_top.php";
require(DIR_FS_INCLUDES . 'ajax_encoding_control.php');


if(isset($HTTP_GET_VARS['categories_urlname']) && $HTTP_GET_VARS['categories_urlname'] != ''){

$current_cat_id_array = tep_get_categori_id_from_url($HTTP_GET_VARS['categories_urlname']);


?>
					<?php if($HTTP_GET_VARS['action_attributes'] == 'columnleft') {  //column left?>
					
					
														<?php if(isset($HTTP_GET_VARS['level_no']) && $HTTP_GET_VARS['level_no'] == '2') { //check level?>
																	 	<?php
																		echo  '<table width="100%"  border="0" align="left" cellspacing="0" cellpadding="0">';
																		$region_dropdown_array = tep_get_paths('',$current_cat_id_array['parent_id'],'','','1');
																		$region_dropdown_array = str_replace(TEXT_MU_DI_DI,TEXT_DROP_DOWN_SELECT_REGION,$region_dropdown_array);
																		 if(!empty($region_dropdown_array)){
																		echo '<tr><td>';	
																		echo tep_draw_pull_down_menu('region',$region_dropdown_array, $HTTP_GET_VARS['categories_urlname'], 'class="input_search" onChange="search_tour_ajax_column_left(this.value,2);"');
																		echo '</td></tr>';
																		echo '<tr><td height="3"></td></tr>';
																		}
																		?>
																							
																		<?php
																		$cat_dropdown_array = tep_get_paths('',$current_cat_id_array['categories_id']);
																		$cat_dropdown_array = str_replace(TEXT_MU_DI_DI,TEXT_DROP_DOWN_SELECT_REGION,$cat_dropdown_array);
																		if(!empty($cat_dropdown_array)){
																		echo '<tr><td>';																			
																		echo tep_draw_pull_down_menu('category', $cat_dropdown_array, '', 'class="input_search" ');																		
																		echo '</td></tr>';
																		echo '<tr><td height="3"></td></tr>';
																		}
																		echo  '</table><div class="clear"></div>';
																		
																		?>
																					
														
														<?php }else{
														?>
																		<?php
																		 $region_dropdown_array = tep_get_paths('',$current_cat_id_array['categories_id'],'','','1');
																		 $region_dropdown_array = str_replace(TEXT_MU_DI_DI,TEXT_DROP_DOWN_SELECT_REGION,$region_dropdown_array);
																		 if(!empty($region_dropdown_array)){																		 
																		 echo  '<table width="100%" align="left" border="0" cellspacing="0" cellpadding="0">';
																		 echo '<tr><td>';																			
																		 echo tep_draw_pull_down_menu('region',$region_dropdown_array , $HTTP_GET_VARS['categories_urlname'], 'class="input_search" onChange="search_tour_ajax_column_left(this.value,2);"');																		
																		 echo '</td></tr>';
																		 echo '<tr><td height="3"></td></tr>';
																		 echo  '</table><div class="clear"></div>';
																		 
																		  }
																		?>
																		
															<?php						
															} //end of level check else						
															?>	
					
					<?php }else{ //index page ?>
														<?php if(isset($HTTP_GET_VARS['level_no']) && $HTTP_GET_VARS['level_no'] == '2') { //check level?>
																	   <?php 
																	   $region_dropdown_array = tep_get_paths('',$current_cat_id_array['parent_id'],'','','1');
																	   $region_dropdown_array = str_replace(TEXT_MU_DI_DI,TEXT_DROP_DOWN_SELECT_REGION,$region_dropdown_array);
																	   
																		 if(!empty($region_dropdown_array)){
																	   ?>
																	   <div class="middle_l_form">
																		<!--<div class="text_22"></div>-->
																		<div class="form_1">
																		<?php
																		
																		 echo tep_draw_pull_down_menu('region', $region_dropdown_array, $HTTP_GET_VARS['categories_urlname'], 'class="input_search" onChange="search_tour_ajax(this.value,2);"');
																		
																		?>
																		</div>
																	  </div>
																	  <?php } ?>
																	  
																	  
																	 <?php
																	 $cat_dropdown_array = tep_get_paths('',$current_cat_id_array['categories_id']);
																	 $cat_dropdown_array = str_replace(TEXT_MU_DI_DI,TEXT_DROP_DOWN_SELECT_REGION,$cat_dropdown_array);
																		if(!empty($cat_dropdown_array)){
																	  ?>
																	<div class="middle_l_form">
																		<!--<div class="text_22"></div>-->
																		<div class="form_1">												
																		<?php
																		
																		 echo tep_draw_pull_down_menu('category', $cat_dropdown_array, '', 'class="input_search" ');
																		
																		?>
																		</div>
																	</div>				
																	 <?php } ?>
														<?php }else{
														?>
																	 <?php 
																	  $region_dropdown_array = tep_get_paths('',$current_cat_id_array['categories_id'],'','','1');
																	  $region_dropdown_array = str_replace(TEXT_MU_DI_DI,TEXT_DROP_DOWN_SELECT_REGION,$region_dropdown_array);
																		 if(!empty($region_dropdown_array)){
																	 ?>
																	  <div class="middle_l_form">
																		<!--<div class="text_22"></div>-->
																		<div class="form_1">
																		
																		<?php
																		
																		 echo tep_draw_pull_down_menu('region', $region_dropdown_array, $HTTP_GET_VARS['categories_urlname'], 'class="input_search" onChange="search_tour_ajax(this.value,2);"');
																		
																		?>
																		</div>
																	</div>
																	
																	 <?php } ?>
															<?php						
															} //end of level check else						
															?>	
						
						<?php } //end of else check index page call ?>
						
															
																													
<?php 
} //get cpath  from category url end

else{	//howard added

	echo '<div class="middle_l_form"><div class="form_1">';
	echo  '<table width="100%"  border="0" align="left" cellspacing="0" cellpadding="0">';
	$region_dropdown_array = tep_get_paths('',$current_cat_id_array['parent_id'],'','','1');
	$region_dropdown_array = str_replace(TEXT_MU_DI_DI,TEXT_DROP_DOWN_SELECT_REGION,$region_dropdown_array);
	if(!empty($region_dropdown_array)){
		echo '<tr><td>';	
		echo tep_draw_pull_down_menu('region',$region_dropdown_array, '', 'class="input_search" onChange="search_tour_ajax_column_left(this.value,2);"');
		echo '</td></tr>';
		echo '<tr><td height="3"></td></tr>';
	}
	echo '</table>';
	echo '</div></div>';

 }?>