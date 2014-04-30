<!--product_info_module2_description start-->
					  <div class="product_content2_n_l">
					  <div class="biaoti8_n">
										 <table width="100%"  border="0" cellspacing="0" cellpadding="0">
										  <tr>

											  <?php if($product_info['display_hotel_upgrade_notes'] == '1') { ?>

												<td>
													<table width="100%"  border="0" cellspacing="1" cellpadding="1">
													  <tr>
													  <td width="5"></td>
													  <td class="main">
													  		 <table border="0" cellspacing="0" cellpadding="0">
															  <tr>
																<td width="16"><img src="image/f.gif" alt="<?php echo TEXT_ALTER_TAG; ?>" /></td>
																<td width="5"></td>
																<td ><?php echo TEXT_HEADING_STANDARD_HOTEL;?></td>
																<td width="45">&nbsp;</td>
																<td width="16"><img src="image/upgrad_hotel.gif" alt="<?php echo TEXT_ALTER_TAG; ?>" /></td>
																<td width="5"></td>
																<td><?php echo TEXT_HEADING_UPGRADE_AVAILABLE;?></td>
															  </tr>
															</table>

													  </td>
													  <td width="5"></td>
													  </tr>
													</table>
												</td>
											   <?php } ?>

											<td align="right">
												<table width="100%"  cellpadding="0" cellspacing="0">
												<tr>
												<td width="100%"  align="right" nowrap="nowrap">
												<a href="<?php echo tep_href_link('printitinerary.php', 'products_id='.$product_info['products_id']); ?>" target="_blank" rel="nofollow"><img src="image/daying.gif" alt="<?php echo TEXT_ALTER_TAG; ?>" /></a>
												</td>
												<td width="5">&nbsp;</td>
												<td nowrap="nowrap" align="right" ><a class="huise_di3" href="<?php echo tep_href_link('printitinerary.php', 'products_id='.$product_info['products_id']); ?>" target="_blank" rel="nofollow"><?php echo TEXT_HEADING_PRINTABLE_VERSION; ?></a></td>
												<td width="5">&nbsp;</td>
												</tr>
												</table>
											</td>
										  </tr>

										</table>
					    </div>
										 <?php if($product_info['display_itinerary_notes'] == '1' || $product_info['display_hotel_upgrade_notes'] == '1') { ?>

										 <div style="float:left; padding-top:5px; width:622px;">

											  <?php if($product_info['display_itinerary_notes'] == '1') { ?>
											   <table width="100%"  border="0" cellspacing="0" cellpadding="0">
											  <tr>
												<td style=" background-color: #D5EFFD; border-bottom: 1px solid #CBEBFC;" width="40" height="37" align="center" valign="middle"><img src="image/itinerary_worning_icon.gif" alt="<?php echo TEXT_ALTER_TAG; ?>" /></td>
												<td style=" background-color: #EDF8FE; border-bottom: 1px solid #CBEBFC; " >
													<table width="100%"  border="0" cellspacing="1" cellpadding="1">
													  <tr>
													  <td width="5"></td>
													  <td class="main"><strong><?php echo TEXT_HEADING_TOURS_ITINERARY_HEADER_NOTES;?></strong></td>
													  <td width="5"></td>
													  </tr>
													</table>
												</td>
											  </tr>
											  </table>
											  <?php } ?>


										</div>
										<?php } ?>
										<?php  if($product_info['is_visa_passport'] > 0){ ?>
							<div style="float:left; display:inline; width:100%;">
							<table width="100%"  border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td style=" background-color: #D5EFFD; border-bottom: 1px solid #CBEBFC;height:37px;" width="40" align="center" valign="middle"><img src="<?php echo DIR_WS_TEMPLATE_IMAGES;?>itinerary_worning_icon.gif" alt="" /></td>
								<td style=" background-color: #EDF8FE; border-bottom: 1px solid #CBEBFC; " >												
									<table width="100%"  border="0" cellspacing="1" cellpadding="1">
									<tr>
									<td width="5"></td>
									<td class="main">
									<?php  if($product_info['is_visa_passport'] == 1){ ?>
									 <div>										
										<?php echo '<strong>'.TEXT_VISA_PASS_NOTREQ.'</strong>';?>
									 </div>
									 <?php }?>
									 <?php  if($product_info['is_visa_passport'] == 2){ ?>
									 <div>
										<?php echo '<strong>'.TEXT_VISA_PASS_YREQ.'</strong>';?>
									 </div>
									 <?php }?>
									  </td>
									  <td width="5"></td>
									  </tr>
									  </table>
								</td>
								</tr>
								</table>
								</div>
						<?php }?>
<?php
include('products_remaning_seats.php');
?>



				<?php
				$h_sql = tep_db_query('SELECT hotel_id, hotel_name FROM `hotel` Order By hotel_name_length DESC ');
				$pat = array();
				$rep = array();
				$pattens = array();
				$replace = array();
				$separator = '###';

				while($h_rows = tep_db_fetch_array($h_sql)){
					$pat[count($pat)] = "/(".preg_quote($h_rows['hotel_name']).")/";
					$str = $h_rows['hotel_name'];
					$len = mb_strlen($str,'GB2312');
					$str_sp = '';
					for($i=0;$i<$len;$i++){
						$str_sp .= mb_substr($str,$i,1,'GB2312').$separator;
					}
					$str_sp = substr($str_sp,0,(strlen($str_sp)-strlen($separator)));

					$rep[count($rep)] = $str_sp;
					$pattens[count($pattens)] = "/(".preg_quote($str_sp).")/";
					$replace[count($replace)] = '<a href="'.tep_href_link('hotel.php','hotel_id='.(int)$h_rows['hotel_id'].'&products_id='.(int)$product_info['products_id']).'" target="_blank" class="sp3">'.str_replace($separator,'',$str_sp).'</a>';
				}

				$products_description = stripslashes2($product_info['products_description']);
				$products_description = preg_replace($pat,$rep,$products_description);
				$products_description = preg_replace($pattens,$replace,$products_description);
				$rep_text_on= false;
				if(count($key_pert)>0 && $rep_text_on==true){
					$products_description = preg_replace($key_pert,$key_rep,$products_description);
				}
				?>
			   <?php if($tous_with_new_design == 'true'){ ?>

									<?php
									echo db_to_html($products_description);
									?>

									<div id="ajax_load"><?php //AJAX引入附加注意、评论等内容?></div>
										<?php
										$liaotiantool=true;
										if($liaotiantool==true){//客户聊天工具
											include_once('live_chat.php');
										}//客户聊天工具end
										?>

										<div class="biaoti8_n2"><h5>&nbsp;&nbsp;<?php echo TEXT_HEADING_TOURS_DETAILS_RESERVATION_PROCCESS_ETICKET;?></h5></div>
										<?php
										$TOURS_DEFAULT_RESERVATION_PROCCESS_ETICKET = stripslashes2(TOURS_DEFAULT_RESERVATION_PROCCESS_ETICKET);
										if($isHotels){
											$TOURS_DEFAULT_RESERVATION_PROCCESS_ETICKET = str_replace(array('出团','导游'), array('入住酒店','酒店工作人员'),$TOURS_DEFAULT_RESERVATION_PROCCESS_ETICKET);
										}
										echo db_to_html($TOURS_DEFAULT_RESERVATION_PROCCESS_ETICKET);
										?>
										<div class="biaoti8_n2"><h5>&nbsp;&nbsp;<?php echo TEXT_HEADING_TOURS_DETAILS_TERMS_AND_CONDITIONS;?></h5></div>
										<?php echo stripslashes2(db_to_html(TOURS_DEFAULT_TERMS_AND_CONDITIONS)); ?>

				<?php }else{ ?>

									<div style="float:left; display:inline; margin-left:5px;">
									<?php
									echo db_to_html($products_description);
									?>
									</div>
									<div id="ajax_load"><?php //AJAX引入附加注意、评论等内容?></div>
										<?php
										$liaotiantool=true;
										if($liaotiantool==true){//客户聊天工具
											include_once('live_chat.php');
										}//客户聊天工具end
										?>

				<?php	}	?>


			      </div>
<!--product_info_module2_description end-->
<style type="text/css">
<!--
.boxSeat {
	font-size: 12px; /* font-size: 0.625em;  */
	font-family: Tahoma, Arial, Verdana, Helvetica, sans-serif, 宋体, 黑体;
	color: #0678b6;
	width: 615px;
}
.boxSeat img {
	border: 0;
	padding: 0 10px;
}
.boxSeat h3 {
	font-weight: bold;
	font-size: 14px;
	color: #000;
	line-height: 35px;
	margin: 0;
}
.boxSeat h3 em {
	color: #BBB;
	font-style: normal;
	font-weight: normal;
	font-size: 12px;
}
.boxSeat table {
	width: 100%;
	border-collapse: collapse;
}
.boxSeat .titleBar {
	height: 26px;
	background: #58A4CD;
	color: #FFF;
	padding-left: 10px;
}
.titleBar .years {
	margin-left: 40px;
}
.boxSeat .titleBar span,.boxSeat .titleBar a,.boxSeat .titleBar strong {
	float: left;
}
.boxSeat .titleBar strong {
	padding: 0 10px;
}
.boxSeat .titleBar .update {
	float: right;
	padding-right: 15px;
}
.boxSeat .arrowL,.boxSeat .arrowR{
	display: block;
	width: 12px;
	height: 12px;
	background: url(<?= DIR_WS_TEMPLATE_IMAGES;?>arrow_l.gif) no-repeat;
}
.boxSeat .arrowR{
	background: url(<?= DIR_WS_TEMPLATE_IMAGES;?>arrow_r.gif) no-repeat;
}
.surplusSeat {
	clear: both;
	border-left: solid 1px #69C5F4;
	border-bottom: solid 1px #69C5F4;
}
.surplusSeat td {
	background: #FFF;
	
	height: 22px;
	border-right: solid 0px #69C5F4;
	text-align: center;
}
.surplusSeat .date td{
	background: #D4EFFD;
}
.surplusSeat .week td{
	background: #F5FBFE;
}
.surplusSeat .seat td {
	color: #000;
	height: 30px;
}
.surplusSeat .date .selected {
	background: #FFF4B8;
	font-weight: bold;
}
h3 em {
	color: #BBB;
	font-style: normal;
	font-weight: normal;
	font-size: 12px;
}
.emstyle {
    color: #353535;
    font-style: normal;
    font-weight: bold;
    font-size: 12px;
    float: right;
}
.surplusSeat1 {
	clear: both;
	border-left: solid 0px #69C5F4;
	border-bottom: solid 0px #69C5F4;
}
.surplusSeat1 td {
	background: #FFF;
	
	height: 22px;
	border-right: solid 1px #69C5F4;
	text-align: center;
}
.surplusSeat1 .date td{
	background: #D4EFFD;
}
.surplusSeat1 .week td{
	background: #F5FBFE;
}
.surplusSeat1 .seat td {
	color: #000;
	height: 30px;
}
.surplusSeat1 .date .selected {
	background: #FFF4B8;
	font-weight: bold;
}
.surplusSeat2 {
	clear: both;
	border-left: solid 0px #69C5F4;
	border-bottom: solid 0px #69C5F4;
}
.surplusSeat2 td {
	background: #FFF;
	width: 35px;
	height: 22px;
	border-right: solid 1px #69C5F4;
	text-align: center;
}
.surplusSeat2 .date td{
	background: #D4EFFD;
}
.surplusSeat2 .week td{
	background: #F5FBFE;
}
.surplusSeat2 .seat td {
	color: #000;
	height: 30px;
}
.surplusSeat2 .date .selected {
	background: #FFF4B8;
	font-weight: bold;
}
-->
</style>



