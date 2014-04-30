<?php echo tep_get_design_body_header(CONFIRMATION_NEWSLLETER_EMAIL); ?>
<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td>
		<?php
		if((int)$customer_id){
			$customers_sql = tep_db_query('SELECT customers_firstname FROM `customers` WHERE `customers_id` = "'.$customer_id.'" limit 1;');
			$customers_row = tep_db_fetch_array($customers_sql);
			if($_GET['confirmation']=='true' ){
				tep_db_query('UPDATE `customers` SET `customers_newsletter` = "1" WHERE `customers_id` = "'.$customer_id.'" LIMIT 1 ;');
				echo sprintf(CONFIRMATION_NEWSLLETER_EMAIL_TRUE,db_to_html(tep_db_output($customers_row['customers_firstname'])));
			}
			if($_GET['confirmation']=='false'){
				tep_db_query('UPDATE `customers` SET `customers_newsletter` = "0" WHERE `customers_id` = "'.$customer_id.'" LIMIT 1 ;');
				echo sprintf(CONFIRMATION_NEWSLLETER_EMAIL_FALSE,db_to_html(tep_db_output($customers_row['customers_firstname'])));
			}
		}
		?>
		</td>
	</tr>
</table>
<?php echo tep_get_design_body_footer();?>