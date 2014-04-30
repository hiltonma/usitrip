<?php echo tep_get_design_body_header(HEADING_TITLE); ?>  

<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
    </tr>
	<tr>        
    <td class="main"><?php echo TEXT_PARA_ACK_1;?> <a href="acknowledgement_of_card_billing.doc" class="style1" target="_blank"><b><?php echo HEADING_TITLE; ?></b></a> <?php echo TEXT_PARA_ACK_1_2;?> 
	</td>
	</tr>
	<tr>
        <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
    </tr>
	<tr>
        <td class="main"><?php echo TEXT_PARA_ACK_2;?></td>
    </tr>
	<tr>
        <td class="main"><p>
				<?php echo TEXT_PARA_ACK_5; ?>
				</p>
		</td>
    </tr>
	<tr>
        <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
    </tr>
 </table>
  <!--  main center box start -->
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
      <tbody>
	  <tr> 
           <td > 
					
					<!-- start of box content ---> 
					 <?php echo tep_draw_form('theForm', tep_href_link(FILENAME_ACKNOWLEDGEMENT_PRINT_PREVIEW, '', 'SSL'), 'post') . tep_draw_hidden_field('action', 'process'); ?>						
							<table border="0" cellspacing="1" cellpadding="0" width="70%" align="center"><tbody>
							 <tr> 
								<td class="main" colspan="2" height="5">&nbsp;</td>
							  </tr>

							  <tr> 								
             				 	<td width="51%" class="main"><strong><?php echo db_to_html("¶©µ¥ºÅ:");?></strong></td>
								<td width="49%" class="main"><strong>C<?php echo $HTTP_GET_VARS['order_id'] ?></strong><input name="order_id" type="hidden" size="27" value="<?php echo $HTTP_GET_VARS['order_id'] ?>" ></td>
							  </tr>
							  <tr> 
								<td class="main" colspan="2" height="5">&nbsp;</td>
							  </tr>
							  
							 <tr> 								
             				 	<td width="51%" class="main"><strong><?php echo TEXT_FULL_NAME;?></strong></td>
								<td width="49%" class="main"><input name="fname" size="27" value="" type="text"></td>
							  </tr>
							  <tr> 
								<td class="main" colspan="2" height="5">&nbsp;</td>
							  </tr>
							  <tr> 								
             				 	<td width="51%" class="main"><strong><?php echo TEXT_ORDER_AMOUNT;?></strong></td>
								<td width="49%" class="main">
								 <?php
									if(isset($HTTP_GET_VARS['order_id']) && $HTTP_GET_VARS['order_id'] != '' ){
									$totalValue = tep_get_order_final_price_of_oid($HTTP_GET_VARS['order_id']);
									}
								  ?>
									<input name="oamount" size="27" value="<?php echo number_format($totalValue,2,'.',''); ?>" type="text">(US dollars)</td>
							  </tr>
							  <tr> 
								<td class="main" colspan="2" height="5">&nbsp;</td>
							  </tr>
							  <tr> 								
             				 	<td width="51%" class="main"><strong><?php echo TEXT_CARD_NUMBER;?></strong></td>
								<td width="49%" class="main"><input name="ccnumber" size="27" value="" type="text"></td>
							  </tr>
							  <tr> 
								<td class="main" colspan="2" height="5">&nbsp;</td>
							  </tr>
							  <tr> 								
              					<td class="main"><strong><?php echo TEXT_EXPIRATION_DATE;?></strong></td>
								<td class="main"><input name="ccexpire1" size="2" value=""  type="text" maxlength="2"> / <input name="ccexpire2" size="2" maxlength="2" value=""  type="text"></td>
							  </tr>
							  <tr style="display:none"> 
								<td class="main" colspan="2" height="5">&nbsp;</td>
							  </tr>
							  <tr style="display:none"> 								
              					<td class="main"><strong><?php echo TEXT_CARD_CVV;?></strong></td>
								<td class="main"><input name="cccode" size="4" maxlength="4" value=""  type="text"></td>
							  </tr>
							  <tr> 
								<td class="main" colspan="2" height="5">&nbsp;</td>
							  </tr>
							   <tr> 								
              					<td class="main"><strong><?php echo TEXT_NAME_APPEAR_ON_CARD;?></strong></td>
								<td class="main"><input name="ccname" size="27" value=""  type="text"></td>
							  </tr>
							  <tr> 
								<td class="main" colspan="2" height="5">&nbsp;</td>
							  </tr>
							  <tr> 								
              					<td class="main"><strong><?php echo TEXT_BILLING_ADDRESS;?></strong></td>
								<td class="main">
								<textarea rows="5" name="ccbaddress" cols="30"></textarea>
								</td>
							  </tr> 
							  <tr> 
								<td class="main" colspan="2" height="5">&nbsp;</td>
							  </tr>
							   <tr> 
							   <td class="main"></td>
								<td class="main"><input type="submit" name="submit" value="<?php echo db_to_html("´òÓ¡Ô¤ÀÀ")?>"></td>
							  </tr>
							 <tr> 
								<td class="main" colspan="2" height="5">&nbsp;</td>
							  </tr>
							 </tbody>
							</table>
							
				</form>	
							
					<!-- end of box content --->

			</td>
        
      </tr>
      
     </tbody></table>
	   <!--  main center box end-->


<?php echo tep_get_design_body_footer();?>