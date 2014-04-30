<?php
include_once('includes/application_top.php');
header('Content-Type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
	http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
<?php
	/*
	 * Define the uniform node function 
	 */
	function GenerateNode($data){
		$content = '';
		$content .= "\t" . '<url>' . "\n";
		$content .= "\t\t" . '<loc>'.trim($data['loc']).'</loc>' . "\n";
		$content .= "\t\t" . '<lastmod>'.trim($data['lastmod']).'</lastmod>' . "\n";
		$content .= "\t\t" . '<changefreq>'.trim($data['changefreq']).'</changefreq>' . "\n";
		$content .= "\t\t" . '<priority>'.trim($data['priority']).'</priority>' . "\n";
		$content .= "\t" . '</url>' . "\n";
		return $content;
	} # end function

	/*
	 * Define the SQL for the categories query 
	 */
	$sql = "SELECT 
					c.categories_id as cID, cd.categories_video_description, cd.categories_map,
					c.date_added as category_date_added, 
					c.last_modified as category_last_mod,
					MAX(p.products_date_added) as products_date_added, 
					MAX(p.products_last_modified) as products_last_mod   
			FROM " . TABLE_CATEGORIES . " c, ".TABLE_CATEGORIES_DESCRIPTION." cd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c 
				LEFT JOIN " . TABLE_PRODUCTS . " p 
					ON (p2c.products_id = p.products_id) 
			WHERE c.categories_id = p2c.categories_id and c.categories_id = cd.categories_id and p2c.categories_id = cd.categories_id
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
			$container[$result['cID']] = max( $result['category_date_added'], 
																				$result['category_last_mod'], 
																				$result['products_last_mod'], 
																				$result['products_date_added']
																			 );
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
												 'lastmod' => date ("Y-m-d", strtotime($last_mod)),
												 'changefreq' => $changefreq,
												 'priority' => $priority
												);			
			echo generateNode($container);
			
			
			//echo $container_tab[$cID.'_video'];
			if($container_tab[$cID.'_video']==1){
			//echo 'here';
				$location = tep_href_link(FILENAME_DEFAULT, 'cPath=' . $cID .'&mnu=introduction', 'NONSSL', false);	
				$container = array('loc' => htmlspecialchars(utf8_encode($location)),
													 'lastmod' => date ("Y-m-d", strtotime($last_mod)),
													 'changefreq' => $changefreq,
													 'priority' => $priority
													);			
				echo generateNode($container);
			}
			
			$location = tep_href_link(FILENAME_DEFAULT, 'cPath=' . $cID.'&mnu=tours', 'NONSSL', false);	
			$container = array('loc' => htmlspecialchars(utf8_encode($location)),
												 'lastmod' => date ("Y-m-d", strtotime($last_mod)),
												 'changefreq' => $changefreq,
												 'priority' => $priority
												);			
			echo generateNode($container);
			
			$location = tep_href_link(FILENAME_DEFAULT, 'cPath=' . $cID.'&mnu=vcpackages', 'NONSSL', false);	
			$container = array('loc' => htmlspecialchars(utf8_encode($location)),
												 'lastmod' => date ("Y-m-d", strtotime($last_mod)),
												 'changefreq' => $changefreq,
												 'priority' => $priority
												);			
			echo generateNode($container);
			
			$location = tep_href_link(FILENAME_DEFAULT, 'cPath=' . $cID.'&mnu=recommended', 'NONSSL', false);	
			$container = array('loc' => htmlspecialchars(utf8_encode($location)),
												 'lastmod' => date ("Y-m-d", strtotime($last_mod)),
												 'changefreq' => $changefreq,
												 'priority' => $priority
												);			
			echo generateNode($container);
			
			
			if($container_tab[$cID.'_map']==1){
				$location = tep_href_link(FILENAME_DEFAULT, 'cPath=' . $cID .'&mnu=maps', 'NONSSL', false);	
				$container = array('loc' => htmlspecialchars(utf8_encode($location)),
													 'lastmod' => date ("Y-m-d", strtotime($last_mod)),
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
	echo '</urlset>';
	
	/*
	 * Include the application_bottom.php script 
	 */
	include_once('includes/application_bottom.php');
?>