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
	 * Define the SQL for the products query 
	 */
	$sql = "SELECT products_id as pID, 
								 products_date_added as date_added, 
								 products_last_modified as last_mod, 
								 products_ordered  
					FROM " . TABLE_PRODUCTS . " 
					WHERE products_status = '1' 
					ORDER BY products_last_modified DESC, 
					         products_date_added DESC, 
							 products_ordered DESC";
	
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
								 				 'lastmod' => date ("Y-m-d", strtotime($lastmod)),
								 				 'changefreq' => $changefreq,
								 				 'priority' => $priority
												);

			/*
			 * Echo the generated node
			 */
			echo generateNode($container);
			
			$location = tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $result['pID'].'mnu=reviews&','NONSSL', false);
			$container = array('loc' => htmlspecialchars(utf8_encode($location)),
								 				 'lastmod' => date ("Y-m-d", strtotime($lastmod)),
								 				 'changefreq' => $changefreq,
								 				 'priority' => $priority
												);

			/*
			 * Echo the generated node
			 */
			echo generateNode($container);
			
			$location = tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $result['pID'].'mnu=qanda&','NONSSL', false);
			$container = array('loc' => htmlspecialchars(utf8_encode($location)),
								 				 'lastmod' => date ("Y-m-d", strtotime($lastmod)),
								 				 'changefreq' => $changefreq,
								 				 'priority' => $priority
												);

			/*
			 * Echo the generated node
			 */
			echo generateNode($container);
			
			$location = tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $result['pID'].'mnu=photos&','NONSSL', false);
			$container = array('loc' => htmlspecialchars(utf8_encode($location)),
								 				 'lastmod' => date ("Y-m-d", strtotime($lastmod)),
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
	echo '</urlset>';

	/*
	 * Include the application_bottom.php script 
	 */
	include_once('includes/application_bottom.php');
?>