<?php

////
// The HTML form submit button wrapper function
// Outputs a button in the selected language
  function tep_template_image_submit($image, $alt = '', $parameters = '') {
    global $language;

    $style= 'style="border:0px;"';
	if(preg_match('/style=/',$parameters)){
		$style= '';
	}
	$image_submit = '<input type="image" src="' . tep_output_string(DIR_WS_TEMPLATE_IMAGES.'buttons/' . $language . '/' .  $image) . '" '.$style.' alt="' . tep_output_string($alt) . '"';

    if (tep_not_null($alt)) $image_submit .= ' title=" ' . tep_output_string($alt) . ' "';

    if (tep_not_null($parameters)) $image_submit .= ' ' . $parameters;

    $image_submit .= ' />';

    return $image_submit;
  }

////
// Output a function button in the selected language
  function tep_template_image_button($image, $alt = '', $parameters = ' style="cursor: pointer;"') {
    global $language;

    return tep_image(DIR_WS_TEMPLATE_IMAGES . 'buttons/' . $language . '/' .  $image, $alt, '', '', $parameters);
  }


  function table_image_border_top($left, $right,$header){
if (MAIN_TABLE_BORDER == 'yes'){
?>
      <!--Lango Added for Template MOD: BOF-->
  <tr>
<td valign="top" width="100%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
<?php
// BOF: WebMakers.com Added: Show Featured Products
if (SHOW_HEADING_TITLE_ORIGINAL!='yes') {
?>
                     <tr>
                      <td bgcolor="#99AECE"><table width="100%" bordercolor="#42ADE8" border="0" cellspacing="0" cellpadding="1">
                          <tr>

                            <td><table width="100%" bordercolor="#42ADE8" border="0" cellspacing="0" cellpadding="1">
                                <tr>
                                  <td bgcolor="#f8f8f9"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                      <tr>
                                        <td class="pageHeading"><?php echo $header;?></center></td>
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
<?php
}
?>
  <tr>
<td valign="top" width="100%"><table width="100%" border="0" cellspacing="0" cellpadding="0">

                     <tr>
                      <td bgcolor="#99AECE"><table width="100%" bordercolor="#42ADE8" border="0" cellspacing="0" cellpadding="1">
                          <tr>

                            <td><table width="100%" bordercolor="#42ADE8" border="0" cellspacing="0" cellpadding="1">
                                <tr>
                                  <td bgcolor="#f8f8f9"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                      <tr>
                                        <td>

<?php
}

}
  function table_image_border_bottom(){
if (MAIN_TABLE_BORDER == 'yes'){
?>
</td>
                                      </tr>
                                    </table></td>
                                </tr>

                              </table></td>
                          </tr>
                          

      </table></td>
  </tr>
      </table></td>
  </tr>

      </table></td>
  </tr>
      <!--Lango Added for Template MOD: EOF-->
<?php
}
}
?>
