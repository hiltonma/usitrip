<?php
/*
  FAQ system for OSC 2.2 MS2 v2.1  22.02.2005
  Originally Created by: http://adgrafics.com admin@adgrafics.net
  Updated by: http://www.webandpepper.ch osc@webandpepper.ch v2.0 (03.03.2004)
  Last Modified: http://shopandgo.caesium55.com timmhaas@web.de v2.1 (22.02.2005)
  Released under the GNU General Public License
  osCommerce, Open Source E-Commerce Solutions
  Copyright (c) 2004 osCommerce
*/
?>
<tr class="pageHeading"><td><?php echo $title ?></td></tr>
<tr><td><table border="0" width="100%" cellpadding="2" cellspacing="1" bgcolor="#ffffff">
  <tr class="dataTableHeadingRow">
	<td align="center" class="dataTableHeadingContent"><?php echo FAQ_NUMBER;?></td>
	<td align="center" class="dataTableHeadingContent"><?php echo FAQ_DATE;?></td>
	<td align="center" class="dataTableHeadingContent"><?php echo tep_image(DIR_WS_IMAGES . 'icons/sort.gif', FAQ_SORT_BY); ?></td>
	<td align="center" class="dataTableHeadingContent"><?php echo FAQ_QUESTION;?></td>
	<td align="center" class="dataTableHeadingContent"><?php echo FAQ_ID;?></td>
	<td align="center" class="dataTableHeadingContent"><?php echo FAQ_STATUS;?></td>
	<td align="center" class="dataTableHeadingContent" colspan="2"><?php echo FAQ_ACTION;?></td>
  </tr>
<?
  $no = 1;
  if (sizeof($data) > 0) {
    while (list($key, $val) = each($data)) {
      $no % 2 ? $bgcolor="#DEE4E8" : $bgcolor="#F0F1F1";
?>
  <tr bgcolor="<?php echo $bgcolor?>">
    <td align="center" class="dataTableContent"><?php echo $no;?></td>
    <td align="center" class="dataTableContent"nowrap><?php echo $val['d']?></td>
    <td align="center" class="dataTableContent"><?php echo $val['v_order'];?></td>
    <td align="left" class="dataTableContent"><?php echo $val['question'] . ' (' . $val['language'] . ')';?></td>
    <td align="center" class="dataTableContent"><?php echo $val['faq_id'];?></td>
    <td align="center" class="dataTableContent" nowrap>
<?php 
  	  if ($val['visible'] == 1) {
		echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;
		<a href="' . tep_href_link(FILENAME_FAQ_MANAGER, "faq_action=Visible&faq_id=$val[faq_id]&visible=$val[visible]".(isset($HTTP_GET_VARS['cID']) ? '&cID='.$HTTP_GET_VARS['cID'] : '').(isset($HTTP_GET_VARS['page']) ? '&page='.$HTTP_GET_VARS['page'] : '')) . '">
		' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', FAQ_DEACTIVATION_ID . " $val[faq_id]", 10, 10) . '</a>';
  	  }else {
		echo tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10) . '&nbsp;&nbsp;
		<a href="' . tep_href_link(FILENAME_FAQ_MANAGER, "faq_action=Visible&faq_id=$val[faq_id]&visible=$val[visible]".(isset($HTTP_GET_VARS['cID']) ? '&cID='.$HTTP_GET_VARS['cID'] : '').(isset($HTTP_GET_VARS['page']) ? '&page='.$HTTP_GET_VARS['page'] : '')) . '">
		' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', FAQ_ACTIVATION_ID . " $val[faq_id]", 10, 10) . '</a>';
  	  };
?>
</td>
<td align=center class="dataTableContent">
<?php echo '<a href="' . tep_href_link(FILENAME_FAQ_MANAGER, "faq_action=Edit&faq_id=$val[faq_id]&faq_lang=$val[language]".(isset($HTTP_GET_VARS['cID']) ? '&cID='.$HTTP_GET_VARS['cID'] : '').(isset($HTTP_GET_VARS['page']) ? '&page='.$HTTP_GET_VARS['page'] : ''), 'NONSSL') . '">' . tep_image(DIR_WS_ICONS . 'edit.gif', FAQ_EDIT_ID . " $val[faq_id]") . '</a>'; ?>
</td>
<td align=center class="dataTableContent">
<?php echo '<a href="' . tep_href_link(FILENAME_FAQ_MANAGER, "faq_action=Delete&faq_id=$val[faq_id]".(isset($HTTP_GET_VARS['cID']) ? '&cID='.$HTTP_GET_VARS['cID'] : '').(isset($HTTP_GET_VARS['page']) ? '&page='.$HTTP_GET_VARS['page'] : ''), 'NONSSL') . '">' . tep_image(DIR_WS_ICONS . 'delete.gif', FAQ_DELETE_ID . " $val[faq_id]") . '</a>'; ?>
</td>
</tr>
<?
  	  $no++;
  	}
  } else {
?>
   <tr bgcolor="#DEE4E8">
    <td colspan="7"><?php echo FAQ_ALERT; ?></td>
   </tr>
<?
  }
?>
</table>
</td></tr>
<tr><td align="right">
<?php echo '<a href="' . tep_href_link(FILENAME_FAQ_CATEGORIES, tep_get_all_get_params(array('faq_action','faq_id','visible','faq_lang')) , 'NONSSL') . '">' . tep_image_button('button_back.gif', FAQ_BACK) . '</a>'; ?>
<?php echo '<a href="' . tep_href_link(FILENAME_FAQ_MANAGER, tep_get_all_get_params(array('faq_action','faq_id','visible','faq_lang')), 'NONSSL').'&faq_action=Added'.'">' . tep_image_button('button_insert.gif', FAQ_ADD) . '</a>'; ?>
<?php //echo '<a href="' . tep_href_link(FILENAME_FAQ_MANAGER, '', 'NONSSL') . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?>
</td></tr>
