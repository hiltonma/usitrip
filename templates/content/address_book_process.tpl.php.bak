<?php 
	 if (isset($HTTP_GET_VARS['edit'])) { $show_hh_title =  HEADING_TITLE_MODIFY_ENTRY; } elseif (isset($HTTP_GET_VARS['delete'])) { $show_hh_title = HEADING_TITLE_DELETE_ENTRY; } else { $show_hh_title = HEADING_TITLE_ADD_ENTRY; } 
	 echo tep_get_design_body_header($show_hh_title,1); ?>
	 <!-- content main body start -->
			<table width="100%"  border="0" cellspacing="0" cellpadding="0">
										
			  <tr>
				<td class="main">
				
<?php if (!isset($HTTP_GET_VARS['delete'])) echo tep_draw_form('addressbook', tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, (isset($HTTP_GET_VARS['edit']) ? 'edit=' . $HTTP_GET_VARS['edit'] : ''), 'SSL'), 'post', 'id="addressbook"'); // onsubmit="return check_form(addressbook);" ?><table border="0" width="75%" class="automarginclass" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">

<?php
  if ($messageStack->size('addressbook') > 0) {
?>
      <tr>
        <td><?php echo $messageStack->output('addressbook'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
}
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD

  if (isset($HTTP_GET_VARS['delete'])) {
?>
      <tr>
        <td class="main"><b><?php echo DELETE_ADDRESS_TITLE; ?></b></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" width="50%" valign="top"><?php echo DELETE_ADDRESS_DESCRIPTION; ?></td>
                <td align="right" width="50%" valign="top"><table border="0" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="main" align="center" valign="top"><b><?php echo SELECTED_ADDRESS; ?></b><br><?php echo tep_image(DIR_WS_IMAGES . 'arrow_south_east.gif'); ?></td>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main" valign="top"><?php echo db_to_html(tep_address_label($customer_id, $HTTP_GET_VARS['delete'], true, ' ', '<br>')); ?></td>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td><?php echo '<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') . '">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
                <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'delete=' . $HTTP_GET_VARS['delete'] . '&action=deleteconfirm', 'SSL') . '">' . tep_template_image_button('button_delete.gif', IMAGE_BUTTON_DELETE) . '</a>'; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
  } else {
?>
      <tr>
        <td><?php include(DIR_FS_MODULES . 'address_book_details.php'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
    if (isset($HTTP_GET_VARS['edit']) && is_numeric($HTTP_GET_VARS['edit'])) {
?>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td><?php echo '<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') . '">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
                <td align="right"><?php echo tep_draw_hidden_field('action', 'update') . tep_draw_hidden_field('edit', $HTTP_GET_VARS['edit']) . tep_template_image_submit('button_update.gif', IMAGE_BUTTON_UPDATE); ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
    } else {
      if (sizeof($navigation->snapshot) > 0) {
        $back_link = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], array(tep_session_name())), $navigation->snapshot['mode']);
      } else {
        $back_link = tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL');
      }
?>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td><?php echo '<a href="' . $back_link . '">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
                <td align="right"><?php echo tep_draw_hidden_field('action', 'process') . tep_template_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>

<?php
    }
  }
?>
    </table><?php if (!isset($HTTP_GET_VARS['delete'])) echo '</form>'; ?>

	</td>
							  </tr>
							  <tr>
								<td height="15"></td>
							  </tr>
							  
							  
							</table><!-- content main body end -->
							
							<script type="text/javascript">
		function formCallback(result, form) {
			window.status = "valiation callback for form '" + form.id + "': result = " + result;
		}
		
		var valid = new Validation('addressbook', {immediate : true,useTitles:true, onFormValidate : formCallback});
			
		Validation.addAllThese([
							['validate-length-firstname', '', {
								minLength : <?php echo ENTRY_FIRST_NAME_MIN_LENGTH; ?>
							}],
							['validate-length-lastname', '', {
								minLength : <?php echo ENTRY_LAST_NAME_MIN_LENGTH; ?>
							}],
							['validate-length-telephone', '', {
								minLength : <?php echo ENTRY_TELEPHONE_MIN_LENGTH; ?>
							}],['validate-length-street', '', {
								minLength : <?php echo ENTRY_STREET_ADDRESS_MIN_LENGTH; ?>
							}],
							['validate-length-postcode', '', {
								minLength : <?php echo ENTRY_POSTCODE_MIN_LENGTH; ?>
							}],						
							['validate-length-city', '', {
								minLength : <?php echo ENTRY_CITY_MIN_LENGTH; ?>
							}],
							['validate-length-state', '', {
								minLength : <?php echo ENTRY_STATE_MIN_LENGTH; ?>
							}]
						]);
	</script>
<?php echo tep_get_design_body_footer();?>
