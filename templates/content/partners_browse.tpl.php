<?php
//$ref_url = 'usitrip.com/landing-page/new_years_tours.html';
if(tep_not_null($_GET['ref_url'])){
	$sql = tep_db_query('SELECT customers_firstname,customers_referer_url,customers_info_date_account_created FROM `customers` c, `customers_info` ci WHERE ci.customers_info_id = c.customers_id AND c.customers_referer_url = "'.(string)$_GET['ref_url'].'" Group By c.customers_id Order By ci.customers_info_date_account_created DESC ');
	$sum = (int)tep_db_num_rows($sql);
}

?>
<table width="100%" border="0" cellpadding="0" cellspacing="1">
  <tr>
    <td height="25" bgcolor="#C9C9C9"><strong>&nbsp;Name</strong></td>
    <td bgcolor="#C9C9C9"><strong>&nbsp;Ref. </strong></td>
    <td bgcolor="#C9C9C9"><strong>&nbsp;Account Created</strong></td>
  </tr>
 <?php
 if((int)$sum){
 while($rows = tep_db_fetch_array($sql)){
 ?> 
  <tr>
    <td height="20" bgcolor="#ECE9D8">&nbsp;<?php echo tep_db_output(db_to_html($rows['customers_firstname'])) ?></td>
    <td bgcolor="#ECE9D8">&nbsp;<?php echo tep_db_output(db_to_html($rows['customers_referer_url'])) ?></td>
    <td bgcolor="#ECE9D8">&nbsp;<?php echo tep_db_output(db_to_html($rows['customers_info_date_account_created'])) ?></td>
  </tr>
<?php
  }
}
?>
  <tr>
    <td height="25" colspan="3" bgcolor="#C9C9C9"><strong>&nbsp;Sum: <?php echo $sum ?></strong></td>
  </tr>
</table>
