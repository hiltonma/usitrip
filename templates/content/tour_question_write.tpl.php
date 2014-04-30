<?php echo tep_draw_form('product_queston_write', tep_href_link(FILENAME_QUESTION_WRITE, 'action=process&cPath='.$cPath.'&mnu='.$mnu.'&products_id=' . $HTTP_GET_VARS['products_id']), 'post', 'onSubmit="return checkForm();"'); ?>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">

          <tr>

            <td class="pageHeading" valign="top"><?php  echo $products_name; ?></td>

            <td class="pageHeading" align="right" valign="top"><?php echo $products_price; ?></td>

          </tr>

        </table></td>

      </tr>

      <tr>

        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>

      </tr>

      <tr>

        <td>
		</br>
		<table width="100%" border="0" cellspacing="0" cellpadding="2" >
			<tr>

          	<td class="pageHeading"><?php echo TEXT_PRODUCTS_QUESTION_ANSWERS;?></td>

      		</tr>
			
			
              <tr > 

                <td align="center" valign="top">
				
				<table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox" >

                    <tr class="infoBoxContents"> 

                      <td></br><table border="0" width="100%" cellspacing="2" cellpadding="2" >
							<?php 
							if($success == true){
							?>
							<tr>
				
							<td class="main" colspan="2"></br><b><?php echo TEXT_NO_QUESTION_ADDED_SUCCESS;?></b></td>
				
							</tr>
							<?php
							}else{
							
							?>
                          <tr> 

                            <td width="22%" class="main"><b><?php echo TEXT_YOUR_NAME; ?></b>&nbsp;<font color="#FF0000"><?php echo TEXT_REQUIRED_FIELDS; ?></font></td>

                            <td width="78%" class="main"><?php echo tep_draw_input_field('customers_name'); ?></td>

                          </tr>

                          <tr> 

                            <td class="main"><b><?php echo TEXT_YOUR_EMAIL;?></b>&nbsp;<font color="#FF0000"><?php echo TEXT_REQUIRED_FIELDS; ?></font></td>

                            <td class="main"><?php echo tep_draw_input_field('customers_email'); ?></td>

                          </tr>
  
						    <tr> 

                            <td class="main" nowrap><b><?php echo TEXT_YOUR_EMAIL_CONFIRM;?></b>&nbsp;<font color="#FF0000"><?php echo TEXT_REQUIRED_FIELDS; ?></font></td>

                            <td class="main"><?php echo tep_draw_input_field('c_customers_email'); ?></td>

                          </tr>

                         
                          <tr> 

                            <td class="main"><b><?php echo TEXT_YOUR_QUESTION;?></b>&nbsp;<font color="#FF0000"><?php echo TEXT_REQUIRED_FIELDS; ?></font></td>

                            <td  class="main"><?php echo tep_draw_textarea_field('questions', 'soft', 60, 15); ?></td>

                          </tr>

                          <tr> 

                            <td colspan="2" align="right" class="smallText"><?php echo TEXT_NOTE;?></td>

                          </tr>
							<?php
							 } // end of else 
							?>
                          <?php // BOF: WebMakers.com Added: Split to two rows ?>
							
                          <tr> 

                            <td colspan="2" align="center" class="main">&nbsp;</td>

                          </tr>

                          <?php // BOF: WebMakers.com Added: Split to two rows ?>

                        </table></td>

                    </tr>

                   

                  </table>
				  
				  <table width="100%" cellspacing="0" cellpadding="2" >
				   <tr> 

                      <td ><?php echo tep_draw_separator('pixel_trans.gif', '100%', '15'); ?></td>

                    </tr>
				  </table>
				  <table width="100%" cellspacing="1" cellpadding="2" class="infoBox" >
				  

                    <tr class="infoBoxContents"> 

                      <td><table border="0" width="100%" cellspacing="0" cellpadding="2" >

                          <tr > 

                            <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

                            <td class="main"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, "products_id=$products_id&cPath=$cPath",'NONSSL',false,true,"qanda") . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>

                            <td class="main" align="right"><?php 
							 if($success != true){
							 echo tep_template_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); 
							 } 
							 ?></td>

                            <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

                          </tr>

                        </table></td>

                    </tr>
				  </table>
				  
				  </td>

            </table></td>

      </tr>

    </table></form> 

<!-- body_text_eof //-->
