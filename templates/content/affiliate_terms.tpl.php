<?php echo tep_get_design_body_header(HEADING_TITLE); ?>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>
      <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="1" >
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="4">
             
            </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="4" >
              <tr>
                <td class="main"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo TEXT_INFORMATION; ?></font></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      
    </table>
	<?php echo tep_get_design_body_footer();?>