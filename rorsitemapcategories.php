<?php
	include_once('includes/application_top.php');
	header('Content-Type: text/xml; charset=big5');
	echo '<?xml version="1.0" encoding="big5"?>' . "\n";
?>
<rss version="2.0" xmlns:ror="http://rorweb.com/0.1/" >
<channel>
<title>Sitemap for <?php echo STORE_NAME; ?></title>
<link><?php echo HTTP_SERVER.DIR_WS_HTTP_CATALOG; ?></link>  
<?php
	
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
	 * Define the SQL for the categories query 
	 */
	 //echo $languages_id;
	$sql = "SELECT 
					c.categories_id as cID, cd.categories_video_description, cd.categories_map, cd.categories_name,
					c.date_added as category_date_added, 
					c.last_modified as category_last_mod,
					MAX(p.products_date_added) as products_date_added, 
					MAX(p.products_last_modified) as products_last_mod   
			FROM " . TABLE_CATEGORIES . " c, ".TABLE_CATEGORIES_DESCRIPTION." cd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c 
				LEFT JOIN " . TABLE_PRODUCTS . " p 
					ON (p2c.products_id = p.products_id) 
			WHERE c.categories_id = p2c.categories_id and c.categories_id = cd.categories_id and p2c.categories_id = cd.categories_id and cd.language_id=".$languages_id."
			GROUP BY cID  
			ORDER BY 
				category_date_added ASC, 
				category_last_mod ASC, 
				products_date_added ASC, 
				products_last_mod ASC";
	
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
		 * Initialize the container
		 */
		$container = array();

		/*
		 * Loop query result and populate container
		 */
		$set_introduction = 0;
		$set_map = 0;
		//echo $language_id;
		while( $result = tep_db_fetch_array($query) ){
			$container[$result['cID']] = str_replace('&','&amp;',db_to_html($result['categories_name']));
			if($result['categories_video_description']!='')
			{
				$container_tab[$result['cID'].'_video'] = 1;
			}
			
			if($result['categories_map'] != '')
			{
				$container_tab[$result['cID'].'_map'] = 1;
			}
			

		} # end while

		/*
		 * Free the resource...could be large
		 * ...clean as we go
		 */
		tep_db_free_result($query);

		/*
		 * Sort the container based on last mod date
		 */
		arsort($container);
	} # end if

	/*
	 * Loop the result set
	 * Basic sanity check
	 */
	if ( sizeof($container) > 0 ){
		$total = sizeof($container);
		$_total = $total;
		
		foreach( $container as $cID => $last_mod ){
			
			$changefreq = 'weekly';
			$priority = max( number_format($_total/$total, 1, '.', ','), .1);
			$_total--;	
			
			
			$location = tep_href_link(FILENAME_DEFAULT, 'cPath=' . $cID, 'NONSSL', false);	
			$container = array('loc' => htmlspecialchars(utf8_encode($location)),
												 'title' => $last_mod,
												 'changefreq' => $changefreq,
												 'priority' => $priority
												);			
			echo generateNode($container);
			
			
			if($container_tab[$cID.'_video']==1){
				$location = tep_href_link(FILENAME_DEFAULT, 'cPath=' . $cID .'&mnu=introduction', 'NONSSL', false);	
				$container = array('loc' => htmlspecialchars(utf8_encode($location)),
													 'title' => $last_mod.' - Introduction',
													 'changefreq' => $changefreq,
													 'priority' => $priority
													);			
				echo generateNode($container);
			}
			
			$location = tep_href_link(FILENAME_DEFAULT, 'cPath=' . $cID.'&mnu=tours', 'NONSSL', false);	
			$container = array('loc' => htmlspecialchars(utf8_encode($location)),
												 'title' => $last_mod.' - Tours',
												 'changefreq' => $changefreq,
												 'priority' => $priority
												);			
			echo generateNode($container);
			
			$location = tep_href_link(FILENAME_DEFAULT, 'cPath=' . $cID.'&mnu=vcpackages', 'NONSSL', false);	
			$container = array('loc' => htmlspecialchars(utf8_encode($location)),
												 'title' => $last_mod.' - Vacation Packages',
												 'changefreq' => $changefreq,
												 'priority' => $priority
												);			
			echo generateNode($container);
			
			$location = tep_href_link(FILENAME_DEFAULT, 'cPath=' . $cID.'&mnu=recommended', 'NONSSL', false);	
			$container = array('loc' => htmlspecialchars(utf8_encode($location)),
												 'title' => $last_mod.' - Recommended',
												 'changefreq' => $changefreq,
												 'priority' => $priority
												);			
			echo generateNode($container);
			
			
			if($container_tab[$cID.'_map']==1){
				$location = tep_href_link(FILENAME_DEFAULT, 'cPath=' . $cID .'&mnu=maps', 'NONSSL', false);	
				$container = array('loc' => htmlspecialchars(utf8_encode($location)),
													 'title' => $last_mod.' - Map',
													 'changefreq' => $changefreq,
													 'priority' => $priority
													);			
				echo generateNode($container);
			}
			/////////put menu link
			
			
			
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

