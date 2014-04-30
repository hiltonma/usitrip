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
<tr>
	<td align="left" valign="top">
		
		<?php echo tep_draw_form('search', FILENAME_TRAVEL_AGENCY, tep_get_all_get_params(array('search')), 'get'); ?>
		<table width="45%" cellpadding="2" cellspacing="2">			
			<tr>
				<td class="smallText" valign="top">Search by agency name/agency id/language: </td>
				<td class="smallText" valign="top"><?php echo tep_draw_input_field('search', $HTTP_GET_VARS['search'], '80'); ?></td>
				<td><?php echo tep_image_submit('button_search.gif', IMAGE_SEARCH).'&nbsp;';?></td>
			</tr>
		</table>
		<?php echo '</form>';?>
	</td>
</tr>
<tr><td>
<table border="0" width="100%"  cellpadding=0 cellspacing=1 bgcolor="#ffffff">
<tr class="dataTableHeadingRow">
	<td class="dataTableHeadingContent" width="30" nowrap="nowrap">&nbsp;<?php echo 'ID';?>&nbsp;<br /><?php echo '<a href="' . tep_href_link(FILENAME_TRAVEL_AGENCY, tep_get_all_get_params(array('sort','order','agency_id','adgrafics_information')).'sort=aid&order=ascending', 'NONSSL').'"><img src="images/arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_TRAVEL_AGENCY, tep_get_all_get_params(array('sort','order','agency_id','adgrafics_information')).'sort=aid&order=decending', 'NONSSL').'"><img src="images/arrow_down.gif" border="0"></a>&nbsp;'; ?></td>
	<td  class="dataTableHeadingContent" nowrap><?php echo AGENCY_NAME_INFORMATION;?>&nbsp;<br /><?php echo '<a href="' . tep_href_link(FILENAME_TRAVEL_AGENCY, tep_get_all_get_params(array('sort','order','agency_id','adgrafics_information')).'sort=name&order=ascending', 'NONSSL').'"><img src="images/arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_TRAVEL_AGENCY, tep_get_all_get_params(array('sort','order','agency_id','adgrafics_information')).'sort=name&order=decending', 'NONSSL').'"><img src="images/arrow_down.gif" border="0"></a>&nbsp;'; ?></td>
	
	<td class="dataTableHeadingContent" nowrap><?php echo 'Time<br>Zone';?>&nbsp;<br /><?php echo '<a href="' . tep_href_link(FILENAME_TRAVEL_AGENCY, tep_get_all_get_params(array('sort','order','agency_id','adgrafics_information')).'sort=timezone&order=ascending', 'NONSSL').'"><img src="images/arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_TRAVEL_AGENCY, tep_get_all_get_params(array('sort','order','agency_id','adgrafics_information')).'sort=timezone&order=decending', 'NONSSL').'"><img src="images/arrow_down.gif" border="0"></a>'; ?> 
<?php  //echo tep_image(DIR_WS_ICONS . 'sort.gif', SORT_BY); ?></td>
	
	<td class="dataTableHeadingContent"><?php echo TITLE_AGENCY_CODE;?>&nbsp;<br /><?php echo '<a href="' . tep_href_link(FILENAME_TRAVEL_AGENCY, tep_get_all_get_params(array('sort','order','agency_id','adgrafics_information')).'sort=tourcode&order=ascending', 'NONSSL').'"><img src="images/arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_TRAVEL_AGENCY, tep_get_all_get_params(array('sort','order','agency_id','adgrafics_information')).'sort=tourcode&order=decending', 'NONSSL').'"><img src="images/arrow_down.gif" border="0"></a>'; ?> </td>
	<td  class="dataTableHeadingContent" ><?php echo 'Operate Currency';?></td>
	<td  class="dataTableHeadingContent" nowrap><?php echo TITLE_INFORMATION;?></td>
	<td  class="dataTableHeadingContent"><?php echo TEXT_HEADING_ADDRESS;?></td>
	<td  class="dataTableHeadingContent"><?php echo TEXT_OPERATOR_LANGUAGE;?></td>	
	<td  class="dataTableHeadingContent"><?php echo TEXT_AGENCY_MAJOR_CATEGORIES;?></td>	
	<td  class="dataTableHeadingContent"><?php echo TEXT_AGENCY_DEFUAL_MAX_ALLOW_CHILD_AGE;?></td>	
	<?php /*?><td  class="dataTableHeadingContent"><?php echo TEXT_AGENCY_LAST_UPDATE_BY_WHOM;?></td>	
	<td  class="dataTableHeadingContent"><?php echo TEXT_HEDING_AGENCY_NEXT_UPDATE_DUE_DATE;?></td><?php */?>
	<td  class="dataTableHeadingContent"><?php echo 'Tran. Fee';?></td>
	<td  class="dataTableHeadingContent" nowrap="nowrap"><?php echo 'Provider CXLN Policy&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';?></td>	
	<td  class="dataTableHeadingContent" nowrap="nowrap"><?php echo STORE_OWNER.' CXLN Policy&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; ?></td>

</tr>
<?
 $no=1;
 if (sizeof($data) > 0) {
  while (list($key, $val)=each($data)) {
  $no % 2 ? $bgcolor="#DEE4E8" : $bgcolor="#F0F1F1";
?>
   <tr bgcolor="<?php echo $bgcolor?>">

    <td  class="dataTableContent" valign="top" nowrap="nowrap" >&nbsp;<?php //echo $no; ?> <?php echo $val['agency_id'];?>&nbsp;</td>
    <td class="dataTableContent" valign="top" width="10%">
		<?php echo $val['agency_name'];?><br /><br />
		<?php echo $val['agency_name1'];?><br />
		<?php
		if($allow_travle_agency_edit == true){
			echo '<a href="' . tep_href_link(FILENAME_TRAVEL_AGENCY, tep_get_all_get_params(array('agency_id','adgrafics_information'))."adgrafics_information=Edit&agency_id=$val[agency_id]", 'NONSSL') . '">' . tep_image(DIR_WS_ICONS . 'edit.gif', EDIT_ID_INFORMATION . " $val[agency_id]") . '</a>';
		}else{
		?>
		<input name="edit_none" type="button" disabled="disabled" value="±à¼­" />
		<?php 
		}
		?>
		<?php // echo '<a href="' . tep_href_link(FILENAME_TRAVEL_AGENCY, tep_get_all_get_params(array('agency_id','adgrafics_information'))."adgrafics_information=Delete&agency_id=$val[agency_id]", 'NONSSL') . '">' . tep_image(DIR_WS_ICONS . 'delete.gif', DELETE_ID_INFORMATION . " $val[agency_id]") . '</a>'; ?>
	</td>
	<td class="dataTableContent" valign="top" nowrap="nowrap"><?php if($val['agency_timezone'] != '') {echo $val['agency_timezone'];}?></td>
	<td class="dataTableContent" valign="top" nowrap><?php echo $val['agency_code'];?></td>
	<td class="dataTableContent" valign="top" nowrap><?php echo $val['operate_currency_code'];?></td>
    <td class="dataTableContent" width="20%" valign="top">
	<?php
	
	 if($val['contactperson'] != ''){
	 echo 'Name: '.$val['contactperson'];
	 }
	 ?>
	<?php  if($val['phone']!=''){echo '<br>Ph: '.$val['phone'];}if($val['fax']!=''){echo '<br>fax: '. $val['fax'];}?>
	
	<?php
	if($val['emerency_contactperson']!=''){
	echo '<br>Eg. Co.: ' .$val['emerency_contactperson'];
	}
	
	if($val['emerency_number']!=''){
	echo '<br>Eg. No: ' .$val['emerency_number'];
	}
	?>
	</td>
	
	
	<td class="dataTableContent" valign="top">
	<?php echo $val['address'];?><br><?php echo $val['city'].', '.$val['state'].' '.$val['zip'].', '.$val['country'];?>
	<?php 
	if($val['emailaddress'] != ''){
	?>
	<br>E-mail: <?php echo $val['emailaddress'];?>
	<?php } ?>
	<?php 
	if($val['website'] != ''){
	?>
	<br>Web: <a href="http://<?php echo $val['website'];?>" target="_blank"><?php echo $val['website'];?></a>
	<?php } ?>
	</td>	
	<td class="dataTableContent" valign="top"><?php echo $val['agency_oper_lang'];?></td>
	<td class="dataTableContent" valign="top" width="20%"><?php echo nl2br($val['major_categories']);?></td>
	<td class="dataTableContent" valign="top" width="20%"><?php echo $val['default_max_allow_child_age'];?> years</td>
	<?php /*?><td class="dataTableContent" valign="top"><?php echo $val['last_update_by'];?></td>
	<td class="dataTableContent" valign="top" nowrap><?php echo $val['next_update_due_date'];?></td><?php */?>
	<td class="dataTableContent" valign="top" nowrap><?php 
	if($val['default_transaction_fee'] != ''){
	echo $val['default_transaction_fee'].'%';
	}
	?></td>    
	<td class="dataTableContent" valign="top" width="150"><?php echo nl2br($val['provider_cxln_policy']);?></td>
	<td class="dataTableContent" valign="top" width="150"><?php echo nl2br($val['store_cxln_policy']);?></td>
   </tr>
<?$no++;
  }} else {?>
   <tr bgcolor="#DEE4E8">
    <td colspan=6><?php echo ALERT_INFORMATION;?></td>
   </tr>
<?}?>
</table>
</td></tr>
<tr><td align=right>
<?php 
	if($allow_travle_agency_edit == true){
		echo '<a href="' . tep_href_link(FILENAME_TRAVEL_AGENCY, 'adgrafics_information=Added', 'NONSSL') . '">' . tep_image_button('button_new.gif', ADD_INFORMATION) . '</a>';
	}?>
<?php echo '<a href="' . tep_href_link(FILENAME_TRAVEL_AGENCY, '', 'NONSSL') . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?>
</td></tr>
