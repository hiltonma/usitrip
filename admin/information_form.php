<?PHP
  /*
  Module: Information Pages Unlimited
  		  File date: 2003/03/02
		  Based on the FAQ script of adgrafics
  		  Adjusted by Joeri Stegeman (joeri210 at yahoo.com), The Netherlands

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Released under the GNU General Public License
  */
?>
<tr class=pageHeading><td><?php echo $title ?></td></tr>
<tr class="dataTableRow"><td><font color=red>
<?
echo QUEUE_INFORMATION_LIST;
$data=browse_information();
$no=1;
if (sizeof($data) > 0) {
  while (list($key, $val)=each($data)) {
		echo "$val[v_order], ";
		$no++;
		}
}
?>
</font>
</td></tr>
	<tr><td>
<table border="0" cellpadding=0 cellspacing="2">
<tr><td><?php echo QUEUE_INFORMATION;?> </td>
<td>
<?php if ($edit[v_order]) {$no=$edit[v_order];}; echo tep_draw_input_field('v_order', "$no", 'size=3 maxlength=4'); ?>
<?php
echo VISIBLE_INFORMATION;
if ($edit[visible]==1) {
echo tep_image(DIR_WS_ICONS . 'icon_status_green.gif', INFORMATION_ID_ACTIVE);
}else{
echo tep_image(DIR_WS_ICONS . 'icon_status_red.gif', INFORMATION_ID_DEACTIVE);
}
?>
<?php if ($edit[visible]) {$checked= "checked";}; echo tep_draw_checkbox_field('visible', '1', "$checked") . VISIBLE_INFORMATION_DO; ?></td>
</tr>

<tr><td><?php echo TITLE_INFORMATION;?><br></td>
	<td>


<?php echo tep_draw_input_field('info_title', "$edit[info_title]", 'maxlength=255'); ?></td>
</tr>

<tr><td><?php echo DESCRIPTION_INFORMATION;?><br>
</td>
<td>


<?php echo tep_draw_textarea_field('description', '', '60', '10', "$edit[description]"); ?></td>
  <?php if (HTML_AREA_WYSIWYG_DISABLE == 'Disable') {} else { ?>
          <script language="JavaScript1.2" defer>
// MaxiDVD Added WYSIWYG HTML Area Box + Admin Function v1.7 - 2.2 MS2 HTML Email HTML - <body>
           var config = new Object();  // create new config object
           config.width = "<?php echo EMAIL_AREA_WYSIWYG_WIDTH; ?>px";
           config.height = "<?php echo EMAIL_AREA_WYSIWYG_HEIGHT; ?>px";
           config.bodyStyle = 'background-color: <?php echo HTML_AREA_WYSIWYG_BG_COLOUR; ?>; font-family: "<?php echo HTML_AREA_WYSIWYG_FONT_TYPE; ?>"; color: <?php echo HTML_AREA_WYSIWYG_FONT_COLOUR; ?>; font-size: <?php echo HTML_AREA_WYSIWYG_FONT_SIZE; ?>pt;';
           config.debug = <?php echo HTML_AREA_WYSIWYG_DEBUG; ?>;
           editor_generate('description',config);
<?php }
// MaxiDVD Added WYSIWYG HTML Area Box + Admin Function v1.7 - 2.2 MS2 HTML Email HTML - <body>
   ?>
          </script>
</tr>
<tr>
  <td>信息类别</td>
  <td>
    
	<?php
	$option_array = array();
	$option_array[0] = array('id' => 0, 'text' => '--无--');
	$option_array[1] = array('id' =>'美国西海岸景点介绍', 'text' => '美国西海岸景点介绍');
	$option_array[2] = array('id' =>'美国东海岸景点介绍', 'text' => '美国东海岸景点介绍');
	$option_array[3] = array('id' =>'夏威夷景点介绍', 'text' => '夏威夷景点介绍');
	$option_array[4] = array('id' =>'加拿大景点介绍', 'text' => '加拿大景点介绍');
	$option_array[5] = array('id' =>'美国旅游建议', 'text' => '美国旅游建议');
	$option_array[6] = array('id' =>'旅美常识', 'text' => '旅美常识');
	$option_array[7] = array('id' =>'海外游学FAQ', 'text' => '海外游学FAQ');
	
	//$info_type = '夏威夷景点介绍';
	echo tep_draw_pull_down_menu('info_type',$option_array,"$edit[info_type]");
	?>    </td>
</tr>
<tr>
  <td>关键字：</td>
  <td><?php echo tep_draw_input_field('info_keyword', "$edit[info_keyword]", ' size="50" maxlength=255'); ?>多个关键词用“|”号隔开</td>
</tr>
<tr>
  <td>meta_title：</td>
  <td><?php echo tep_draw_input_field('meta_title', "$edit[meta_title]", ' size="63"'); ?></td>
</tr>
<tr>
  <td>meta_keywords：</td>
  <td><?php echo tep_draw_input_field('meta_keywords', "$edit[meta_keywords]", ' size="63"'); ?></td>
</tr>
<tr>
  <td>meta_description：</td>
  <td><?php echo tep_draw_textarea_field('meta_description', '', '60', '5', "$edit[meta_description]"); ?></td>
</tr>
<tr><td></td>
<td align=right>
<?php
echo tep_image_submit('button_insert.gif', IMAGE_INSERT);
echo '<a href="' . tep_href_link(FILENAME_INFORMATION_MANAGER, '', 'NONSSL') . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
 ?></td>
</tr>
</table>
</form>
	</td></tr>
