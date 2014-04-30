<?php
	include_once('includes/application_top.php');	
	header('Content-Type: text/xml; charset=big5');		
	echo '<?xml version="1.0" encoding="big5"?>' . "\n";*/
?>
 <rss version="2.0" xmlns:ror="http://rorweb.com/0.1/" >
<channel>
<title>Sitemap for <?php echo STORE_NAME; ?></title>
<link><?php echo HTTP_SERVER.DIR_WS_HTTP_CATALOG; ?></link>
<?php

	/*
	 * Define the uniform node function 
	 */
	function GenerateNode($data){
		$content = '';
		$content .= "\t" . '<item>' . "\n";
		$content .= "\t\t" . '<link>'.trim($data['loc']).'</link>' . "\n";
		$content .= "\t\t" . '<title>'.trim($data['title']).'</title>' . "\n";
		//$content .= "\t\t" . '<lastmod>'.trim($data['lastmod']).'</lastmod>' . "\n";
		$content .= "\t\t" . '<ror:updatePeriod>'.trim($data['changefreq']).'</ror:updatePeriod>' . "\n";
		$content .= "\t\t" . '<ror:sortOrder>'.trim($data['priority']).'</ror:sortOrder>' . "\n";
		$content .= "\t\t" . '<ror:resourceOf>sitemap</ror:resourceOf>' . "\n";
		$content .= "\t" . '</item>' . "\n";
		return $content;
	} # end function

	/*
	 * Define the SQL for the products query 
	 */
	$sql = "SELECT p.products_id as pID, pd.products_name,
								 p.products_date_added as date_added, 
								 p.products_last_modified as last_mod, 
								 p.products_ordered  
					FROM " . TABLE_PRODUCTS . " as p, ".TABLE_PRODUCTS_DESCRIPTION." as pd 
					WHERE p.products_status = '1' and p.products_id = pd.products_id and pd.language_id=".$languages_id."
					ORDER BY p.products_last_modified DESC, 
					         p.products_date_added DESC, 
							 p.products_ordered DESC";
	
	/*
	 * Execute the query
	 */
	$query = tep_db_query($sql);

	/*
	 * If there are returned rows...
	 * Basic sanity check 
	 */
	if ( tep_db_num_rows($query) > 0 ){

		/*
		 * Initialize the variable containers
		 */
		$container = array();
		$number = 0;
		$top = 0;

		/*
		 * Loop the query result set
		 */
		while( $result = tep_db_fetch_array($query) ){
			$top = max($top, $result['products_ordered']);
			
			if ( tep_not_null($result['last_mod']) ){
				$lastmod = $result['last_mod'];
			} else {
				$lastmod = $result['date_added'];
			}
			$changefreq = 'weekly';
			$ratio = ($top > 0) ? ($result['products_ordered']/$top) : 0;
			$priority = $ratio < .1 ? .1 : number_format($ratio, 1, '.', ''); 
			
			/*
			 * Initialize the content container array
			 */
			$location = tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $result['pID'], 'NONSSL', false);
			$container = array('loc' => htmlspecialchars(utf8_encode($location)),
								 				 'title' => str_replace('&','&amp;',db_to_html($result['products_name'].' - Tour Detail')),
													 'changefreq' => $changefreq,
													 'priority' => $priority
												);

			
			
			/*
			 * Echo the generated node
			 */
			echo generateNode($container);
			
			$location = tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $result['pID'].'mnu=reviews&','NONSSL', false);
			$container = array('loc' => htmlspecialchars(utf8_encode($location)),
								 				 'title' => str_replace('&','&amp;',db_to_html($result['products_name'].' - Reviews')),
								 				 'changefreq' => $changefreq,
								 				 'priority' => $priority
												);

			/*
			 * Echo the generated node
			 */
			echo generateNode($container);
			
			$location = tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $result['pID'].'mnu=qanda&','NONSSL', false);
			$container = array('loc' => htmlspecialchars(utf8_encode($location)),
								 				 'title' => str_replace('&','&amp;',db_to_html($result['products_name'].' - Questions and Answers')),
								 				 'changefreq' => $changefreq,
								 				 'priority' => $priority
												);

			/*
			 * Echo the generated node
			 */
			echo generateNode($container);
			
			$location = tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $result['pID'].'mnu=photos&','NONSSL', false);
			$container = array('loc' => htmlspecialchars(utf8_encode($location)),
								 				 'title' => str_replace('&','&amp;',db_to_html($result['products_name'].' - Traveler Photos')),
								 				 'changefreq' => $changefreq,
								 				 'priority' => $priority
												);

			/*
			 * Echo the generated node
			 */
			echo generateNode($container);
			
		} # end while
	} # end if
	
	/*
	 * Close the urlset
	 */
	?>
	</channel>
</rss>
	<?php

	/*
	 * Include the application_bottom.php script 
	 */
	include_once('includes/application_bottom.php');
?>