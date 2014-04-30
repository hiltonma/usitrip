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
<tr><td>
<table border="0" width=100%  cellpadding=2 cellspacing=1 bgcolor="#ffffff">
<tr class="dataTableHeadingRow">
	<td align=center class="dataTableHeadingContent"><?php echo NO_INFORMATION;?></td>
	<td align=center class="dataTableHeadingContent">
<?php  echo tep_image(DIR_WS_ICONS . 'sort.gif', SORT_BY); ?></td>
	<td align=center class="dataTableHeadingContent"><?php echo TITLE_INFORMATION;?></td>
	<td align=center class="dataTableHeadingContent"><?php echo ID_INFORMATION;?></td>
	<td align=center class="dataTableHeadingContent">Àà±ð</td>
	<td align=center class="dataTableHeadingContent"><?php echo PUBLIC_INFORMATION;?></td>
	<td align=center class="dataTableHeadingContent" colspan=2><?php echo ACTION_INFORMATION;?></td>
</tr>
<?
 $no=1;
 if (sizeof($data) > 0) {
  while (list($key, $val)=each($data)) {
  $no % 2 ? $bgcolor="#DEE4E8" : $bgcolor="#F0F1F1";
?>
   <tr bgcolor="<?php echo $bgcolor?>">

    <td align="right" class="dataTableContent"><?php echo $no;?></td>
    <td align="center" class="dataTableContent"><?php echo $val[v_order];?></td>
    <td class="dataTableContent"><?php echo $val[info_title];?></td>
    <td align="center" class="dataTableContent"><?php echo $val[information_id];?></td>
    <td align="center" class="dataTableContent"><?php echo $val[info_type];?></td>
    <td nowrap  class="dataTableContent">
<?php
if ($val[visible]==1) {
echo tep_image(DIR_WS_ICONS . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;
<a href="' . tep_href_link(FILENAME_INFORMATION_MANAGER, "adgrafics_information=Visible&information_id=$val[information_id]&visible=$val[visible]") . '">
' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', DEACTIVATION_ID_INFORMATION . " $val[information_id]", 10, 10) . '</a>';
}else {
echo tep_image(DIR_WS_ICONS . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10) . '&nbsp;&nbsp;
<a href="' . tep_href_link(FILENAME_INFORMATION_MANAGER, "adgrafics_information=Visible&information_id=$val[information_id]&visible=$val[visible]") . '">
' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', ACTIVATION_ID_INFORMATION . " $val[information_id]", 10, 10) . '</a>';
};
?></td>
    <td align=center class="dataTableContent">
<?php echo '<a href="' . tep_href_link(FILENAME_INFORMATION_MANAGER, "adgrafics_information=Edit&information_id=$val[information_id]", 'NONSSL') . '">' . tep_image(DIR_WS_ICONS . 'edit.gif', EDIT_ID_INFORMATION . " $val[information_id]") . '</a>'; ?></td>
    <td align=center class="dataTableContent">
<?php echo '<a href="' . tep_href_link(FILENAME_INFORMATION_MANAGER, "adgrafics_information=Delete&information_id=$val[information_id]", 'NONSSL') . '">' . tep_image(DIR_WS_ICONS . 'delete.gif', DELETE_ID_INFORMATION . " $val[information_id]") . '</a>'; ?></td>
   </tr>
<?$no++;
  }} else {?>
   <tr bgcolor="#DEE4E8">
    <td colspan=8><?php echo ALERT_INFORMATION;?></td>
   </tr>
<?}?>
</table>
</td></tr>
<tr><td align=right>
<?php echo '<a href="' . tep_href_link(FILENAME_INFORMATION_MANAGER, 'adgrafics_information=Added', 'NONSSL') . '">' . tep_image_button('button_new.gif', ADD_INFORMATION) . '</a>'; ?>
<?php echo '<a href="' . tep_href_link(FILENAME_INFORMATION_MANAGER, '', 'NONSSL') . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?>
</td></tr>
