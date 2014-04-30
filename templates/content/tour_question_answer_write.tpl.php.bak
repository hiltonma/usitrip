<?php echo tep_draw_form('product_question_answer_write', tep_href_link(FILENAME_QUESTION_ANSWER_WRITE, 'action=process&que_id='.$que_id.'&cPath='.$cPath.'&mnu='.$mnu.'&products_id=' . $HTTP_GET_VARS['products_id']), 'post', 'onSubmit="return checkForm();"'); ?><table border="0" width="100%" cellspacing="0" cellpadding="0">

      <tr> 

        <td>
		</br>
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
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
			<tr>

          	<td class="pageHeading"><?php echo TEXT_QUESTION_AND_ANSWERS; ?></td>

      		</tr>
			
			
              <tr > 

                <td align="center" valign="top"><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">

                    <tr class="infoBoxContents"> 

                      <td></br><table border="0" width="100%" cellspacing="2" cellpadding="2" class="unnamed1">

                          <?php 
							if($success == true){
							?>
							<tr>
				
							<td  colspan="3" class="main"><b><?php echo TEXT_REPLAY_SEND;?></b></td>
				
							</tr>
							<?php
							}else{
							
							?>
						 
						  <tr> 

                            <td width="21%" class="main"><b><?php echo TEXT_YOUR_NAME; ?></b>&nbsp;<font color="#FF0000"><?php echo TEXT_REQUIRED_FIELDS; ?></font></td>

                            <td colspan="2" class="main"><?php echo tep_draw_input_field('replay_name'); ?></td>

                          </tr>

                          <tr> 

                            <td class="main"><b><?php echo TEXT_YOUR_EMAIL;?></b>&nbsp;<font color="#FF0000"><?php echo TEXT_REQUIRED_FIELDS; ?></font></td>

                            <td colspan="2" class="main"><?php echo tep_draw_input_field('replay_email'); ?></td>

                          </tr>
						   <tr> 

                            <td class="main" nowrap><b><?php echo TEXT_YOUR_EMAIL_CONFIRM;?></b>&nbsp;<font color="#FF0000"><?php echo TEXT_REQUIRED_FIELDS; ?></font></td>

                            <td colspan="2" class="main"><?php echo tep_draw_input_field('c_replay_email'); ?></td>

                          </tr>
                         
                          <tr> 

                            <td class="main"><b><?php echo TEXT_YOUR_ANSWERS;?></b>&nbsp;<font color="#FF0000"><?php echo TEXT_REQUIRED_FIELDS; ?></font></td>

                            <td width="65%"  class="main"><?php echo tep_draw_textarea_field('anwers', 'soft', 45, 10); ?></td>

                            <td width="14%"  class="main">&nbsp;</td>
                          </tr>

                          <tr> 

                            <td colspan="2" align="right" class="smallText"><?php echo TEXT_NOTE;?></td>

                            <td align="right" class="smallText">&nbsp;</td>
                          </tr>
					
                          <?php // BOF: WebMakers.com Added: Split to two rows ?>

                          <tr> 

                            <td colspan="3" align="center" class="main">&nbsp;</td>

                          </tr>
							<?php
							}
							?>
                          <?php // BOF: WebMakers.com Added: Split to two rows ?>

                        </table></td>

                    </tr>
					
                  

                  </table>
				  
				  
				  
				   <table width="100%" cellspacing="0" cellpadding="2" >
				    <tr> 

                      <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '15'); ?></td>

                    </tr>
					 </table>
					  <table width="100%" cellspacing="1" cellpadding="2" class="infoBox" > 
                    <tr class="infoBoxContents"> 

                      <td><table border="0" width="100%" cellspacing="0" cellpadding="2">

                          <tr> 

                            <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

                            <td class="main"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, "products_id=$products_id&cPath=$cPath",'NONSSL',false,true,"qanda") . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>

                            <td class="main" align="right"><?php if($success != true){ 
							echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); } ?></td>

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
