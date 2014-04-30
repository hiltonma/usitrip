<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top">
		<table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft"><?php require(DIR_WS_INCLUDES . 'column_left.php'); ?></table>
	</td>
   <td width="100%" valign="top">
   <h3>Service Request List</h3>
   <table border="0" width="100%" cellspacing="0" cellpadding="2">
	   <tbody>
			<tr class="dataTableHeadingRow">
				<td class="dataTableHeadingContent" colspan="6"> </td>
				<!--<td class="dataTableHeadingContent">No.</td>
				<td class="dataTableHeadingContent">Customer</td>
				<td class="dataTableHeadingContent">Name</td>
				<td class="dataTableHeadingContent" >Mobile</td>
				<td class="dataTableHeadingContent">Email</td>
				<td class="dataTableHeadingContent">Added Time</td>-->
				<td class="dataTableHeadingContent">Action</td>
			</tr>
		
		<?php  foreach($page_data as $row) {?>
			<tr class="dataTableRow" onmouseover="this.style.backgroundColor='#fff'"  onmouseout="this.style.backgroundColor=''" >
				<td class="dataTableContent"><strong>No.</strong><?php echo $row['customers_transfer_request_id']?></td>
				<td class="dataTableContent"><strong>Name:</strong></td>
				<td class="dataTableContent"><?php echo tep_output_string(tep_db_output($row['name']))?>(#<a href="<?php echo tep_href_link('customers.php','language=sc&page=1&&action=edit&cID='.$row['customers_id'])?>" target="_blank"><?php echo $row['customers_id']?></a>)</td>
				<td class="dataTableContent"><strong>Contact:</strong></td>
				<td class="dataTableContent" ><?php echo tep_output_string(tep_db_output($row['mobile_phone']))?> ,<?php echo tep_output_string(tep_db_output($row['email']))?></td>
				<td class="dataTableContent"><?php echo $row['created_time']?></td>
				<td class="dataTableContent"><button style="cursor:pointer" type="button" onclick="if(confirm('Confirm for delete record #<?php echo $row['customers_transfer_request_id']?>')) location.href='<?php echo tep_href_link('customer_service_request.php' , 'action=delete&customers_transfer_request_id='.$row['customers_transfer_request_id'])?>';" > DELETE </button></a></td>
			</tr>
			<tr class="dataTableRow">
				<td colspan="1" class="dataTableContent">FromUrl</td>
				<td colspan="7" class="dataTableContent"><a href="<?php echo $row['from_url']?>"><?php echo $row['from_url']?></a></td>
			</td>
			</tr>
			<tr class="dataTableRow">
				<td colspan="1" class="dataTableContent">Service Descript</td>
				<td colspan="7" class="dataTableContent">
				<?php echo tep_output_string(tep_db_output($row['comment']))?>					
				</td>
			</td>
			</tr>
		<?php } // endforeach?>
		
			<tr>
				<td class="smallText" valign="top" colspan="8">
				<?php echo $pager_toolbar ?>
				</td>
			</tr>
	 </tbody>
   </table>
	</td>	
  </tr>
</table>

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
</body>
</html>