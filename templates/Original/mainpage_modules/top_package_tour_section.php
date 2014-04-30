<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td><?php
		$popular_query = tep_db_query("select * from ".TABLE_OTHER_TOUR_SECTION." where other_section_name = 'top_package_tour_section' ");
		$popular_result = tep_db_fetch_array($popular_query);
		echo stripslashes($popular_result['other_description']);
		 ?></td>
	</tr>
</table>