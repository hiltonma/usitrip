<?php
// /catalog/includes/header_tags.php
// WebMakers.com Added: Header Tags Generator v2.3
// Add META TAGS and Modify TITLE
//
// NOTE: Globally replace all fields in products table with current product name just to get things started:
// In phpMyAdmin use: UPDATE products_description set PRODUCTS_HEAD_TITLE_TAG = PRODUCTS_NAME
//
require(DIR_FS_LANGUAGES . $language . '/' . 'header_tags.php');
exit;
echo '<!-- BOF: Generated Meta Tags -->' . "\n";

$the_desc='';
$the_key_words='';
$the_title='';

// Define specific settings per page:
switch (true) {

// INDEX.PHP
  case (strstr($_SERVER['PHP_SELF'],FILENAME_DEFAULT) or strstr($PHP_SELF,FILENAME_DEFAULT) ):
  // parse cpatch to get category
   /* $the_category_query = tep_db_query("select cd.categories_name, cd.categories_head_desc_tag ,cd.categories_head_title_tag, cd.categories_head_keywords_tag from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . $current_category_id . "' and cd.categories_id = '" . $current_category_id . "' and cd.language_id = '" . $languages_id . "'");
    
    $the_category = tep_db_fetch_array($the_category_query);*/
  	$the_category = MCache::fetch_categories($current_category_id,'categories_name,categories_head_desc_tag,categories_head_title_tag,categories_head_keywords_tag');

    $the_manufacturers_query= tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "'");
    $the_manufacturers = tep_db_fetch_array($the_manufacturers_query);


 if (empty($the_category['categories_head_desc_tag'])) {
        $the_desc= "usitrip provides tour packages for all budgets across the United States for customer. We send satisfied customers on USA travel tours,  Our customers enjoy a wide range of well planned and individually tailored tour packages. Our goal is to ensure that every customer is satisfied. Discover tour packages with us!";
        }else{
    if (HTDA_DEFAULT_ON=='1') {
      $the_desc= $the_category['categories_head_desc_tag'] . '  ' . HEAD_DESC_TAG_ALL;
      	if($the_category['categories_head_desc_tag'] == ''){
   				$the_desc = "usitrip provides yellowstone, yosemite, grand canyon and mt.rushmore national park treval tours and vacation tour packages guide for all budgets across the United States for customer. We send satisfied customers on national parks travel tours,  Our customers enjoy a wide range of well planned and individually tailored yellowstone, yosemite, grand canyon and mt.rushmore vacation tour packages. ";
   				$the_first_sentence = "Discover yellowstone, yosemite, grand canyon and mt.rushmore national parks vacation tours and tour packages with us!";
   			}
    } else {
      $the_desc= $the_category['categories_head_desc_tag'] . '  ' . HEAD_DESC_TAG_DEFAULT;
    }
   }

    if (HTKA_DEFAULT_ON=='1') {
      $the_key_words= clean_html_comments($the_category['categories_head_keywords_tag']) . ' ' . HEAD_KEY_TAG_DEFAULT;
   	if($the_category['categories_head_title_tag'] == ''){
   		$the_key_words = "    yellowstone,west yellowstone,vacation yellowstone,tour yellowstone,yellowstone national park tour(s),yosemite,yosemite vacation,yosemite national park california,yosemite california,yosemite park ca,yosemite park california,yosemite national park california,grand canyon,grand canyon tours,grand canyon national park,grand canyon vacation,grand canyon arizona,grand canyon travel,las vegas grand canyon tours,grand canyon az,grand canyon las vegas,grand canyon trip,grand canyon vacation package,grand canyon tours,mt rushmore toursmt rushmore national park";
    } 
    }else {
      $the_key_words= clean_html_comments($the_category['categories_head_keywords_tag']) . ' ' . HEAD_KEY_TAG_DEFAULT;
    }

    if (HTTA_DEFAULT_ON=='1') {
      $the_title= $the_category['categories_head_title_tag'] . ' ' . HEAD_TITLE_TAG_ALL . " " . $the_category['categories_name'] . $the_manufacturers['manufacturers_name'];
   		if($the_category['categories_head_title_tag'] == ''){
   				$the_title = "yellowstone, yosemite, grand canyon and mt.rushmore national park travel tours and vacation tour packages california";
   			}
    } else {
      $the_title= $the_category['categories_head_title_tag'] . ' ' . HEAD_TITLE_TAG_ALL;
     }
    break;

// PRODUCT_INFO.PHP
  case ( strstr($_SERVER['PHP_SELF'],'product_info.php') or strstr($PHP_SELF,'product_info.php') ):
//    $the_product_info_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, pd.products_head_title_tag, pd.products_head_keywords_tag, pd.products_head_desc_tag, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . $HTTP_GET_VARS['products_id'] . "' and pd.products_id = '" . $HTTP_GET_VARS['products_id'] . "'");
    $the_product_info_query = tep_db_query("select pd.language_id, p.products_id, pd.products_name, pd.products_description, pd.products_head_title_tag, pd.products_head_keywords_tag, pd.products_head_desc_tag, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "'" . " and pd.language_id ='" .  (int)$languages_id . "'");
    $the_product_info = tep_db_fetch_array($the_product_info_query);

    if (empty($the_product_info['products_head_desc_tag'])) {
      $the_desc= HEAD_DESC_TAG_ALL;
    } else {
      if ( HTDA_PRODUCT_INFO_ON=='1' ) {
        $the_desc= $the_product_info['products_head_desc_tag'] . ' ' . HEAD_DESC_TAG_ALL;
      } else {
        $the_desc= $the_product_info['products_head_desc_tag'];
      }
    }

    if (empty($the_product_info['products_head_keywords_tag'])) {
      $the_key_words= HEAD_KEY_TAG_ALL;
    } else {
      if ( HTKA_PRODUCT_INFO_ON=='1' ) {
        $the_key_words= $the_product_info['products_head_keywords_tag'] . ' ' . HEAD_KEY_TAG_ALL;
      } else {
        $the_key_words= $the_product_info['products_head_keywords_tag'];
      }
    }

    if (empty($the_product_info['products_head_title_tag'])) {
      $the_title= HEAD_TITLE_TAG_ALL;
    } else {
      if ( HTTA_PRODUCT_INFO_ON=='1' ) {
        $the_title= HEAD_TITLE_TAG_ALL . ' ' . clean_html_comments($the_product_info['products_head_title_tag']);
      } else {
        $the_title= clean_html_comments($the_product_info['products_head_title_tag']);
      }
    }

    break;


// PRODUCTS_NEW.PHP
  case ( strstr($_SERVER['PHP_SELF'],'products_new.php') or strstr($PHP_SELF,'products_new.php') ):
    if ( HEAD_DESC_TAG_WHATS_NEW!='' ) {
      if ( HTDA_WHATS_NEW_ON=='1' ) {
        $the_desc= HEAD_DESC_TAG_WHATS_NEW . ' ' . HEAD_DESC_TAG_ALL;
      } else {
        $the_desc= HEAD_DESC_TAG_WHATS_NEW;
      }
    } else {
      $the_desc= HEAD_DESC_TAG_ALL;
    }

    if ( HEAD_KEY_TAG_WHATS_NEW!='' ) {
      if ( HTKA_WHATS_NEW_ON=='1' ) {
        $the_key_words= HEAD_KEY_TAG_WHATS_NEW . ' ' . HEAD_KEY_TAG_ALL;
      } else {
        $the_key_words= HEAD_KEY_TAG_WHATS_NEW;
      }
    } else {
      $the_key_words= HEAD_KEY_TAG_ALL;
    }

    if ( HEAD_TITLE_TAG_WHATS_NEW!='' ) {
      if ( HTTA_WHATS_NEW_ON=='1' ) {
        $the_title= HEAD_TITLE_TAG_ALL . ' ' . HEAD_TITLE_TAG_WHATS_NEW;
      } else {
        $the_title= HEAD_TITLE_TAG_WHATS_NEW;
      }
    } else {
      $the_title= HEAD_TITLE_TAG_ALL;
    }

    break;


// SPECIALS.PHP
  case ( strstr($_SERVER['PHP_SELF'],'specials.php')  or strstr($PHP_SELF,'specials.php') ):
    if ( HEAD_DESC_TAG_SPECIALS!='' ) {
      if ( HTDA_SPECIALS_ON=='1' ) {
        $the_desc= HEAD_DESC_TAG_SPECIALS . ' ' . HEAD_DESC_TAG_ALL;
      } else {
        $the_desc= HEAD_DESC_TAG_SPECIALS;
      }
    } else {
      $the_desc= HEAD_DESC_TAG_ALL;
    }

    if ( HEAD_KEY_TAG_SPECIALS=='' ) {
      // Build a list of ALL specials product names to put in keywords
      $new = tep_db_query("select p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, p.products_image, s.specials_new_products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SPECIALS . " s where p.products_status = '1' and s.products_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and s.status = '1' order by s.specials_date_added DESC ");
      $row = 0;
      $the_specials='';
      while ($new_values = tep_db_fetch_array($new)) {
        $the_specials .= clean_html_comments($new_values['products_name']) . ', ';
      }
      if ( HTKA_SPECIALS_ON=='1' ) {
        $the_key_words= $the_specials . ' ' . HEAD_KEY_TAG_ALL;
      } else {
        $the_key_words= $the_specials;
      }
    } else {
      $the_key_words= HEAD_KEY_TAG_SPECIALS . ' ' . HEAD_KEY_TAG_ALL;
    }

    if ( HEAD_TITLE_TAG_SPECIALS!='' ) {
      if ( HTTA_SPECIALS_ON=='1' ) {
        $the_title= HEAD_TITLE_TAG_SPECIALS . ' - ' . HEAD_TITLE_TAG_ALL;
      } else {
        $the_title= HEAD_TITLE_TAG_SPECIALS;
      }
    } else {
      $the_title= HEAD_TITLE_TAG_ALL;
    }

    break;


// PRODUCTS_REVIEWS_INFO.PHP and PRODUCTS_REVIEWS.PHP
  case ( strstr($_SERVER['PHP_SELF'],'product_reviews_info.php') or strstr($_SERVER['PHP_SELF'],'product_reviews.php') or strstr($PHP_SELF,'product_reviews_info.php') or strstr($PHP_SELF,'product_reviews.php') ):
    if ( HEAD_DESC_TAG_PRODUCT_REVIEWS_INFO=='' ) {
      if ( HTDA_PRODUCT_REVIEWS_INFO_ON=='1' ) {
        $the_desc= tep_get_header_tag_products_desc(isset($HTTP_GET_VARS['reviews_id'])) . ' ' . HEAD_DESC_TAG_ALL;
      } else {
        $the_desc= tep_get_header_tag_products_desc(isset($HTTP_GET_VARS['reviews_id']));
      }
    } else {
      $the_desc= HEAD_DESC_TAG_PRODUCT_REVIEWS_INFO;
    }

    if ( HEAD_KEY_TAG_PRODUCT_REVIEWS_INFO=='' ) {
      if ( HTKA_PRODUCT_REVIEWS_INFO_ON=='1' ) {
        $the_key_words= tep_get_header_tag_products_keywords(isset($HTTP_GET_VARS['reviews_id'])) . ' ' . HEAD_KEY_TAG_ALL;
      } else {
        $the_key_words= tep_get_header_tag_products_keywords(isset($HTTP_GET_VARS['reviews_id']));
      }
    } else {
      $the_key_words= HEAD_KEY_TAG_PRODUCT_REVIEWS_INFO;
    }

    if ( HEAD_TITLE_TAG_PRODUCT_REVIEWS_INFO=='' ) {
      if ( HTTA_PRODUCT_REVIEWS_INFO_ON=='1' ) {
        $the_title= tep_get_header_tag_products_title(isset($HTTP_GET_VARS['reviews_id'])) . ' - ' . HEAD_TITLE_TAG_ALL;
      } else {
        $the_title= tep_get_header_tag_products_title(isset($HTTP_GET_VARS['reviews_id']));
      }
    } else {
      $the_title= HEAD_TITLE_TAG_PRODUCT_REVIEWS_INFO;
    }

    break;



//advance search title 

 case((basename($PHP_SELF)==FILENAME_ADVANCED_SEARCH_RESULT)):
    $the_desc= HEAD_DESC_TAG_ALL;
    $the_key_words= HEAD_KEY_TAG_ALL;
    $the_title = '';
    if (isset($HTTP_GET_VARS['keywords']) ||  isset($HTTP_GET_VARS['departure_city_id'])) 
	{

		
   			 if (isset($HTTP_GET_VARS['departure_city_id'])) 
			 {
		  		$departure_city_id = $HTTP_GET_VARS['departure_city_id'];
		  		$departcity = 'select * from '.TABLE_TOUR_CITY.' where city_id ="'.$departure_city_id.'" ';	 
				  $departcityresult = tep_db_query($departcity);
				  $departcityrow = tep_db_fetch_array($departcityresult);	 
				  $the_title .= $departcityrow['city']." ";
				  
			} 
			
			if (isset($HTTP_GET_VARS['keywords']))
			{
			 $the_title .= " ".$HTTP_GET_VARS['keywords'];
			}	
			
		 $the_title .= " -usitrip";
	 }
	 else
	{
	$the_title= HEAD_TITLE_TAG_ALL;
	}
	
  break;



// ALL OTHER PAGES NOT DEFINED ABOVE
  default:
    $the_desc= HEAD_DESC_TAG_ALL;
    $the_key_words= HEAD_KEY_TAG_ALL;
    $the_title= HEAD_TITLE_TAG_ALL;
    break;

  }

echo ' <title>' . $the_title . '</title>' . "\n";
echo ' <meta http-equiv="Content-Type" content="text/html; charset=' . CHARSET  . '">'."\n";
echo ' <META NAME="Description" Content="' . $the_desc . '">' . "\n";
echo ' <META NAME="Keywords" CONTENT="' . $the_key_words . '">' . "\n";
echo ' <META NAME="Reply-to" CONTENT="' . HEAD_REPLY_TAG_ALL . '">' . "\n";
echo ' <META NAME="robots" CONTENT="index, follow">' . "\n";
//echo ' <META NAME="revisit-after" CONTENT="30 days">' . "\n";
//echo ' <META NAME="generator" CONTENT="CRELoaded osCommerce Version 6.15">' . "\n";


echo '<!-- EOF: Generated Meta Tags -->' . "\n";
?>
