<?php
require('includes/application_top_providers.php');
if (!session_is_registered('providers_id')){
	tep_redirect(tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_LOGIN, '', 'SSL'));
}
$provider_id=$_SESSION['providers_id'];
$agency_id=$_SESSION['providers_agency_id'];

require(DIR_FS_PROVIDERS_LANGUAGES . $language . '/' . FILENAME_PROVIDERS_SOLD_DATES);

if($_POST['btnupdate'] != ''){
	/**========== Start - insert soldout dates ==========**/
	foreach($_POST['products_id'] as $k=>$v){
		$products_id=(int)$v;
		if($_POST['mainSoldOutValue_'.$products_id] > 0){
			$qry_remove_soldout_dates = "DELETE FROM ".TABLE_PRODUCTS_SOLDOUT_DATES." WHERE products_id='".(int)$products_id . "'";
			$res_remove_soldout_dates = tep_db_query($qry_remove_soldout_dates);
			for($i=1; $i <= $_POST['mainSoldOutValue_'.$products_id]; $i++){
				if($_POST['products_soldout_date_'.$products_id.'_'.$i] != ""){
					$products_soldout_date=/*tep_get_date_db*/($_POST['products_soldout_date_'.$products_id.'_'.$i]);
					$qry_remove_dup_soldout_dates = "DELETE FROM ".TABLE_PRODUCTS_SOLDOUT_DATES." WHERE products_id='".(int)$products_id . "' AND products_soldout_date = '".$products_soldout_date."'";
					$res_remove_dup_soldout_dates = tep_db_query($qry_remove_dup_soldout_dates);
					
					$qry_add_soldout_dates = "INSERT INTO ".TABLE_PRODUCTS_SOLDOUT_DATES."(products_id, products_soldout_date) VALUES ('".(int)$products_id."', '".$products_soldout_date."')";
					$res_add_soldout_dates = tep_db_query($qry_add_soldout_dates);
				}
			}
		}
	}
	tep_redirect(FILENAME_PROVIDERS_SOLD_DATES."?".tep_get_all_get_params(array("action"))."msg=1");
	/**========== End - insert soldout dates ==========**/
}
if($_POST['btnRemainingSeatsupdate'] != ''){
     foreach($_POST['products_id'] as $k=>$v){
         $products_id=(int)$v;
         if($_POST['mainRemainingSeatsValue_'.$products_id] > 0){
             $qry_remove_remaining_seats = "DELETE FROM products_remaining_seats WHERE products_id='".(int)$products_id . "'";
             $res_remove_remaining_seats = tep_db_query($qry_remove_remaining_seats);
             for($i=1; $i <= $_POST['mainRemainingSeatsValue_'.$products_id]; $i++){
						if($_POST['products_remaining_seats_'.$products_id.'_'.$i]!=""&&$_POST['products_remaining_seats_num_'.$products_id.'_'.$i]!=""){
							$qry_remove_dup_remaining_seats = "DELETE FROM products_remaining_seats WHERE products_id='".(int)$products_id . "' AND departure_date = '".$_POST['products_remaining_seats_'.$products_id.'_'.$i]."' AND remaining_seats_num = '".$_POST['products_remaining_seats_num_'.$products_id.'_'.$i]."'";
							$res_remove_dup_remaining_seats = tep_db_query($qry_remove_dup_remaining_seats);

							$qry_add_remaining_seats = "INSERT INTO products_remaining_seats(products_id, departure_date,remaining_seats_num,update_date) VALUES ('".(int)$products_id."', '".$_POST['products_remaining_seats_'.$products_id.'_'.$i]."','".$_POST['products_remaining_seats_num_'.$products_id.'_'.$i]."',now())";
							$res_add_remaining_seats = tep_db_query($qry_add_remaining_seats);
	     }
         }

     }

}
 tep_redirect(FILENAME_PROVIDERS_SOLD_DATES."?".tep_get_all_get_params(array("action"))."msg=2");
}
if($_POST['btnupdateall'] != ''){
    
	/**========== Start - insert soldout dates ==========**/
	foreach($_POST['products_id'] as $k=>$v){
		$products_id=(int)$v;
		if($_POST['mainSoldOutValue_'.$products_id] > 0){
			$qry_remove_soldout_dates = "DELETE FROM ".TABLE_PRODUCTS_SOLDOUT_DATES." WHERE products_id='".(int)$products_id . "'";
			$res_remove_soldout_dates = tep_db_query($qry_remove_soldout_dates);
			for($i=1; $i <= $_POST['mainSoldOutValue_'.$products_id]; $i++){
				if($_POST['products_soldout_date_'.$products_id.'_'.$i] != ""){
					$products_soldout_date=/*tep_get_date_db*/($_POST['products_soldout_date_'.$products_id.'_'.$i]);
					$qry_remove_dup_soldout_dates = "DELETE FROM ".TABLE_PRODUCTS_SOLDOUT_DATES." WHERE products_id='".(int)$products_id . "' AND products_soldout_date = '".$products_soldout_date."'";
					$res_remove_dup_soldout_dates = tep_db_query($qry_remove_dup_soldout_dates);
					
					$qry_add_soldout_dates = "INSERT INTO ".TABLE_PRODUCTS_SOLDOUT_DATES."(products_id, products_soldout_date) VALUES ('".(int)$products_id."', '".$products_soldout_date."')";
					$res_add_soldout_dates = tep_db_query($qry_add_soldout_dates);
				}
			}
		}
	}
	//tep_redirect(FILENAME_PROVIDERS_SOLD_DATES."?".tep_get_all_get_params(array("action"))."msg=1");
	/**========== End - insert soldout dates ==========**/


     foreach($_POST['products_id'] as $k=>$v){
         $products_id=(int)$v;
         if($_POST['mainRemainingSeatsValue_'.$products_id] > 0){
             $qry_remove_remaining_seats = "DELETE FROM products_remaining_seats WHERE products_id='".(int)$products_id . "'";
             $res_remove_remaining_seats = tep_db_query($qry_remove_remaining_seats);
             for($i=1; $i <= $_POST['mainRemainingSeatsValue_'.$products_id]; $i++){
                if($_POST['products_remaining_seats_'.$products_id.'_'.$i]!=""&&$_POST['products_remaining_seats_num_'.$products_id.'_'.$i]!=""){
                        $qry_remove_dup_remaining_seats = "DELETE FROM products_remaining_seats WHERE products_id='".(int)$products_id . "' AND departure_date = '".$_POST['products_remaining_seats_'.$products_id.'_'.$i]."' AND remaining_seats_num = '".$_POST['products_remaining_seats_num_'.$products_id.'_'.$i]."'";
                        $res_remove_dup_remaining_seats = tep_db_query($qry_remove_dup_remaining_seats);

                        $qry_add_remaining_seats = "INSERT INTO products_remaining_seats(products_id, departure_date,remaining_seats_num,update_date) VALUES ('".(int)$products_id."', '".$_POST['products_remaining_seats_'.$products_id.'_'.$i]."','".$_POST['products_remaining_seats_num_'.$products_id.'_'.$i]."',now())";
                        $res_add_remaining_seats = tep_db_query($qry_add_remaining_seats);
	        }
             }

          }

     }
  //tep_redirect(FILENAME_PROVIDERS_SOLD_DATES."?".tep_get_all_get_params(array("action"))."msg=2");

tep_redirect(FILENAME_PROVIDERS_SOLD_DATES."?".tep_get_all_get_params(array("action"))."msg=3");
}


require(DIR_FS_PROVIDERS_INCLUDES . FILENAME_PROVIDERS_HEADER);
?>
<script type="text/javascript"  src="datetimepicker.js"></script>
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="body_min_height">
	<tr valign="top">
		<td colspan="2" align="center">
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
		<tr valign="top">
			<td class="login_heading" colspan="2">&nbsp;<b><?php echo TXT_SOLDOUT_DATES;?></b></td>
		</tr>
<?php 
	if($_GET['msg']=='1'){?>
		<tr valign="top">
			<td class="successMsg" colspan="2" align="center">&nbsp;<?php echo MSG_SUCCESS; ?></td>
		</tr>
<?php 
	}?>
<?php
	if($_GET['msg']=='2'){?>
		<tr valign="top">
			<td class="successMsg" colspan="2" align="center">&nbsp;<?php echo MSG_REMAININGSEATS_SUCCESS; ?></td>
		</tr>
<?php
	}?>
<?php
	if($_GET['msg']=='3'){?>
		<tr valign="top">
			<td class="successMsg" colspan="2" align="center">&nbsp;<?php echo MSG_ALL_SUCCESS; ?></td>
		</tr>
<?php
	}?>
	<tr>
		<td class="main" colspan="2">
		<form name="frmSoldoutDates"  id="frmSoldoutDates" action="" method="post">		
			<table border="0" width="100%" cellspacing="3" cellpadding="2" bgcolor="#F0F0FF" class="grayBorder login">
				<tr class="dataTableHeadingRow">
					<td class="dataTableHeadingContent"><?php echo HEADING_UPDATE_TEMPLATE; ?></td>
					<td class="dataTableHeadingContent"><?php echo HEADING_TOUR_CODE; ?></td>
					<td class="dataTableHeadingContent"><?php echo HEADING_TOUR_NAME; ?></td>
					<?php /*?><td class="dataTableHeadingContent"><?php echo HEADING_TOUR_NAME_PROVIDER; ?></td><?php */?>
				</tr>
<?php
	$qry_product_info = "SELECT p.products_id, p.provider_tour_code, p.products_model, pd.products_name  FROM " . TABLE_PRODUCTS . " p, ".TABLE_PRODUCTS_DESCRIPTION." pd WHERE p.products_id=pd.products_id AND p.products_status=1 AND pd.language_id=1 AND p.agency_id='".$agency_id."'";
	$res_product_info = tep_db_query($qry_product_info);
	
	if(tep_db_num_rows($res_product_info) > 0){
		while($row_product_info = tep_db_fetch_array($res_product_info)){
			$products_id=(int)$row_product_info['products_id'];
	?>
		<tr valign="top">
			
			<td align="center"><a href="<?php echo tep_href_link(DIR_WS_PROVIDERS.FILENAME_ETICKET_TEMPLATE, "products_id=".(int)$products_id."&frm=solddates", "SSL");?>" class="orddtl_link" target="_blank"><h2><?php echo tep_image(DIR_WS_ICONS . 'small_edit.gif', LNK_UPDATE_TEMPLATE);?></h2></a></td>
			<td><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_id);?>" class="orddtl_link" target="_blank"><h2><?php echo tep_db_output($row_product_info['provider_tour_code']);?></h2></a></td>
			<td><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_id);?>" class="orddtl_link" target="_blank"><h2><?php echo tep_db_output($row_product_info['products_name'])." [".$row_product_info['products_model']."]";?></h2></a></td>
			<?php /*?><td><?php echo tep_db_output($row_product_info['products_name_provider']);?></td><?php */?>
			
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="2">
			<div id="mainSoldOutDiv_<?php echo $products_id;?>" style="width:95%;">
		<?php
			$qry_sold_dates="SELECT * FROM ".TABLE_PRODUCTS_SOLDOUT_DATES." sd WHERE sd.products_id='".$products_id."'";
			$res_sold_dates=tep_db_query($qry_sold_dates);
			$total_sold_dates_num=tep_db_num_rows($res_sold_dates);
			$total_sold_dates=$total_sold_dates_num;
			if($total_sold_dates_num <= 0){
				$total_sold_dates=1;
			}
			echo tep_draw_hidden_field("products_id[]", $products_id, 'id="products_id_'.$products_id.'"');
			echo tep_draw_hidden_field("mainSoldOutValue_".$products_id, $total_sold_dates, 'id="mainSoldOutValue_'.$products_id.'"');
			$count=0;
			if($total_sold_dates_num > 0){
				while($row_sold_dates=tep_db_fetch_array($res_sold_dates)){
				$count++;
			?>
				<div id="div_products_soldout_date_<?php echo $products_id."_".$count; ?>" style="float:left; ">
					<table width="125px" border="0" cellspacing="3" cellpadding="0">
						<tr>
							<td align="left">
								<nobr><?php echo tep_draw_input_field('products_soldout_date_'.$products_id."_".$count, $row_sold_dates['products_soldout_date'], 'size="10" id="products_soldout_date_'.$products_id."_".$count.'"');?><a href="javascript:NewCal('products_soldout_date_<?php echo $products_id."_".$count;?>');" id="products_soldout_dt_lnk_<?php echo $products_id."_".$count;?>"><?php echo tep_image(DIR_WS_TEMPLATE_IMAGES.'calic.gif', TXT_PICK_DATE, '34', '21');?></a>&nbsp;<a href="javascript:remove_date('<?php echo $products_id."_".$count;?>');" id="products_soldout_delete_lnk_<?php echo $products_id."_".$count;?>"><?php echo tep_image(DIR_WS_TEMPLATE_IMAGES.'no.gif', TXT_DELETE, '20', '20');?></a></nobr>
							</td>
						</tr>
					</table>
				</div>
		<?php	
				}
			}else{
						$count=1;
					?>
							<div id="div_products_soldout_date_<?php echo $products_id."_".$count; ?>" style="float:left; ">
								<table width="125px" border="0" cellspacing="3" cellpadding="0">
									<tr>
										<td align="left">
											<nobr><?php echo tep_draw_input_field('products_soldout_date_'.$products_id."_".$count, '', 'size="10" id="products_soldout_date_'.$products_id."_".$count.'"');?><a href="javascript:NewCal('products_soldout_date_<?php echo $products_id."_".$count;?>');"><?php echo tep_image(DIR_WS_TEMPLATE_IMAGES.'calic.gif', TXT_PICK_DATE, '34', '21');?></a></nobr>
										</td>
									</tr>
								</table>
							</div>
			<?php }?>
				</div>
				<div style="clear:both;"><input type="button" name="btnAddDate" value="<?php echo LNK_ADD_NEW;?>" onClick="addSoldoutDates('<?php echo $products_id;?>');" /> &nbsp; <input type="submit" name="btnupdate" value="Update">
				</div>
			</td>
		</tr>
                <tr>
			<td align="left">&nbsp;</td>
			<td colspan="2">
                                       
                                        
                                     <div><b><?php echo THE_REMAINING_SEATS;?></b></div>
									 <div id="mainRemainingSeatsDiv_<?php echo $products_id;?>" style="width:95%;">
                                                 <?php
                                                   $qry_remaining_seats = "SELECT * FROM  products_remaining_seats as prs WHERE prs.products_id = '".$products_id."' ORDER BY `departure_date` ASC";
                                                   $res_remaining_seats = tep_db_query($qry_remaining_seats);
                                                   $total_remaining_seats_num = tep_db_num_rows($res_remaining_seats);
                                                   $total_remaining_seats = $total_remaining_seats_num;
                                                   if($total_remaining_seats_num <= 0){
                                                                       $total_remaining_seats = 1;
                                                   }
                                                   echo tep_draw_hidden_field("products_id[]", $products_id, 'id="products_id_'.$products_id.'"');
                                                   echo tep_draw_hidden_field("mainRemainingSeatsValue", $total_remaining_seats, 'id="mainRemainingSeatsValue"');
                                                   echo tep_draw_hidden_field("mainRemainingSeatsValue_".$products_id, $total_remaining_seats, 'id="mainRemainingSeatsValue_'.$products_id.'"');
                                                   $counts=0;
                                                   if($total_remaining_seats_num > 0){
                                                       while($row_remaining_seats=tep_db_fetch_array($res_remaining_seats)){
                                                       $counts++;


                                                 ?>
                                                   <div id="div_products_remaining_seats_<?php echo $products_id."_".$counts; ?>">
                                                   <table border="0" cellpadding="3" cellspacing="0">
                                           <tr>
                                                      <td width="270" valign="top" nowrap="nowrap" style="font-size:12px">Departure dates&nbsp;<?php echo $counts; ?><label><?php echo tep_draw_input_field('products_remaining_seats_'.$products_id."_".$counts,$row_remaining_seats['departure_date'], 'size="10" id="products_remaining_seats_'.$products_id."_".$counts.'"');?><a href="javascript:NewCal('products_remaining_seats_<?php echo $products_id."_".$counts;?>');" id="products_remaining_seats_lnk_<?php echo $products_id."_".$counts;?>"><?php echo tep_image(DIR_WS_TEMPLATE_IMAGES.'calic.gif', TXT_PICK_DATE, '34', '21');?></a></label></td>
                                              <td width="210" valign="top" style="font-size:12px">Remaining seats<?php echo tep_draw_input_field('products_remaining_seats_num_'.$products_id."_".$counts,$row_remaining_seats['remaining_seats_num'], 'size="2" style="ime-mode:disabled" id="products_remaining_seats_num_'.$products_id."_".$counts.'"');?><a href="javascript:remove_departure_date('<?php echo $products_id."_".$counts;?>');" id="products_remaining_seats_num_delete_lnk_<?php echo $products_id."_".$counts;?>"><?php echo tep_image(DIR_WS_TEMPLATE_IMAGES.'no.gif', TXT_DELETE, '20', '20');?></a></td>
                                                   </tr>
                                                   </table>
                                                   </div>
                                                                   <?php }
                                                               }else{
                                                                      $counts = 1;?>
                                                                      <div id="div_products_remaining_seats_<?php echo $products_id."_".$counts; ?>">
                                                                      <table border="0" cellpadding="3" cellspacing="0">
                                                                      <tr>
                                                        <td width="270" valign="top" nowrap="nowrap" style="font-size:12px">Departure dates&nbsp;<?php echo $counts; ?><label><?php echo tep_draw_input_field('products_remaining_seats_'.$products_id."_".$counts,'', 'size="10" id="products_remaining_seats_'.$products_id."_".$counts.'"');?><a href="javascript:NewCal('products_remaining_seats_<?php echo $products_id."_".$counts;?>');"><?php echo tep_image(DIR_WS_TEMPLATE_IMAGES.'calic.gif', TXT_PICK_DATE, '34', '21');?></a></label></td>
                                                <td width="210" valign="top" nowrap="nowrap" style="font-size:12px">Remaining seats<?php echo tep_draw_input_field('products_remaining_seats_num_'.$products_id."_".$counts,'', 'size="2" style="ime-mode:disabled" id="products_remaining_seats_num_'.$products_id."_".$counts.'"');?></td>
                                                      </tr>
                                                      </table>
                                                   </div>


                                                              <?php }?>

                                                
                                                   </div>
                                                      <div style="clear:both;"><input type="button" name="btnAddRemainingSeatsDate" value="<?php echo LNK_ADD_NEW_DEPARTUREDATES;?>" onClick="addDepartureDates('<?php echo $products_id;?>');" /> &nbsp; <input type="submit" name="btnRemainingSeatsupdate" value="Update">
                                                   </div>
                                             </td>
                                        </tr>
                                                  
                              
                
<?php }?>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td><td align="left"><input type="submit" name="btnupdateall" value="Update"></td></tr>
		<tr><td>&nbsp;</td></tr>
			</table>
		</form>
		</td>
	</tr>
<?php	}else{
			echo '<tr><td>'.MSG_RECORD_NOT_FOUND.'</td></tr>';
		}
?>
		</table>
		</td>
	</tr>
</table>
<script type="text/javascript">
<!--//
/* Start - add sold out dates */
function addSoldoutDates(prod_id){
	var ni = document.getElementById('mainSoldOutDiv_'+prod_id);
	var numi = document.getElementById('mainSoldOutValue_'+prod_id);
	var num = parseInt(numi.value) + 1;
	numi.value = num;
	var divIdName = "div_products_soldout_date_"+prod_id+"_"+num;
	var newdiv = document.createElement('div');
	newdiv.setAttribute("id",divIdName);
	
	newdiv.innerHTML = '<div id="div_products_soldout_date_'+prod_id+"_"+num+'" style="float:left; "><table width="125px" border="0" cellspacing="3" cellpadding="0"><tr><td align="left"><nobr><input type="text" name="products_soldout_date_'+prod_id+"_"+num+'" size="10" id="products_soldout_date_'+prod_id+"_"+num+'"><a href="javascript:NewCal(\'products_soldout_date_'+prod_id+"_"+num+'\');" id="products_soldout_dt_lnk_'+prod_id+"_"+num+'"><?php echo tep_db_input(tep_image(DIR_WS_TEMPLATE_IMAGES."calic.gif", TXT_PICK_DATE, 34, 21));?></a>&nbsp;<a href="javascript:remove_date(\''+prod_id+"_"+num+'\');" id="products_soldout_delete_lnk_'+prod_id+"_"+num+'"><?php echo tep_db_input(tep_image(DIR_WS_TEMPLATE_IMAGES."no.gif", TXT_DELETE, 20, 20));?></a></nobr></td></tr></table></div>';
	ni.appendChild(newdiv);
}

function remove_date(cal_id){
	with(document){
		getElementById('products_soldout_date_'+cal_id).value="";
		getElementById('products_soldout_date_'+cal_id).style.display="none";
		getElementById('products_soldout_dt_lnk_'+cal_id).style.display="none";
		getElementById('products_soldout_delete_lnk_'+cal_id).style.display="none";
	}
}
/* End - add sold out dates */
function addDepartureDates(prod_id){
   var ni = document.getElementById('mainRemainingSeatsDiv_'+prod_id);
   var numi = document.getElementById('mainRemainingSeatsValue_'+prod_id);
   var num = parseInt(numi.value) + 1;
   numi.value = num;
   var divIdName = "div_products_remaining_seats_"+prod_id+"_"+num;
   var newdiv = document.createElement("div");
   newdiv.setAttribute("id",divIdName);

   newdiv.innerHTML = '<div id="div_products_remaining_seats_'+prod_id+"_"+num+'" style="float:left; "><table border="0" cellpadding="3" cellspacing="0"><tr><td width="270" valign="top" nowrap="nowrap" style="font-size:12px">Departure dates&nbsp;'+num+'<nobr><input type="text" name="products_remaining_seats_'+prod_id+"_"+num+'" size="10" id="products_remaining_seats_'+prod_id+"_"+num+'"><a href="javascript:NewCal(\'products_remaining_seats_'+prod_id+"_"+num+'\');" id="products_remaining_seats_lnk_'+prod_id+"_"+num+'"><?php echo tep_db_input(tep_image(DIR_WS_TEMPLATE_IMAGES."calic.gif", TXT_PICK_DATE, 34, 21));?></a></td><td width="210" valign="top" nowrap="nowrap" style="font-size:12px">Remaining seats<input type="text" name="products_remaining_seats_num_'+prod_id+"_"+num+'" size="2" style="ime-mode:disabled" id="products_remaining_seats_num_'+prod_id+"_"+num+'"><a href="javascript:remove_departure_date(\''+prod_id+"_"+num+'\');" id="products_remaining_seats_num_delete_lnk_'+prod_id+"_"+num+'"><?php echo tep_db_input(tep_image(DIR_WS_TEMPLATE_IMAGES."no.gif", TXT_DELETE, 20, 20));?></a></nobr></td></tr></table></div>';
   ni.appendChild(newdiv);

}

function remove_departure_date(cal_id){
    with(document){
        getElementById('products_remaining_seats_'+cal_id).value="";
		getElementById('products_remaining_seats_'+cal_id).style.display="none";
		getElementById('products_remaining_seats_lnk_'+cal_id).style.display="none";
		getElementById('products_remaining_seats_num_'+cal_id).value="";
		getElementById('products_remaining_seats_num_'+cal_id).style.display="none";
		getElementById('products_remaining_seats_num_delete_lnk_'+cal_id).style.display="none";
		getElementById('div_products_remaining_seats_'+cal_id).value="";
		getElementById('div_products_remaining_seats_'+cal_id).style.display="none";

	}
}
//--></script>
<?php require(DIR_FS_PROVIDERS_INCLUDES . FILENAME_PROVIDERS_FOOTER);?>