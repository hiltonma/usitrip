<?php

// /catalog/includes/languages/english/header_tags.php

// WebMakers.com Added: Header Tags Generator v2.3

// Add META TAGS and Modify TITLE

//

// DEFINITIONS FOR /includes/languages/english/header_tags.php



// Define your email address to appear on all pages

define('HEAD_REPLY_TAG_ALL', STORE_OWNER_EMAIL_ADDRESS);



// For all pages not defined or left blank, and for products not defined

// These are included unless you set the toggle switch in each section below to OFF ( '0' )

// The HEAD_TITLE_TAG_ALL is included BEFORE the specific one for the page

// The HEAD_DESC_TAG_ALL is included AFTER the specific one for the page

// The HEAD_KEY_TAG_ALL is included BEFORE the specific one for the page

define('HEAD_TITLE_TAG_ALL', '東海岸旅遊,西海岸旅遊,拉斯維加斯旅遊,夏威夷旅遊,佛羅里達旅遊-usitrip');

define('HEAD_DESC_TAG_ALL','購買旅遊方案，查詢旅遊線路，瀏覽旅遊景點，汽車旅遊，雙飛旅遊，選擇旅行社在usitrip.com,獲得百分百滿意');

define('HEAD_KEY_TAG_ALL','usitrip, 旅遊，低價旅遊線路,汽車旅遊，旅行，徒步旅遊，景點旅遊，旅行社，熱點旅遊線路，

折扣旅遊方案');



// DEFINE TAGS FOR INDIVIDUAL PAGES



// index.php

define('HTTA_DEFAULT_ON','1'); // Include HEAD_TITLE_TAG_ALL in Title

define('HTKA_DEFAULT_ON','1'); // Include HEAD_KEY_TAG_ALL in Keywords

define('HTDA_DEFAULT_ON','1'); // Include HEAD_DESC_TAG_ALL in Description

/*
define('HEAD_TITLE_TAG_DEFAULT', 'usitrip');

define('HEAD_DESC_TAG_DEFAULT','購買旅遊方案，查詢旅遊線路，瀏覽旅遊景點，汽車旅遊，雙飛旅遊，選擇旅行社在usitrip.com,獲得百分百滿意');

define('HEAD_KEY_TAG_DEFAULT','usitrip, 旅遊，低價旅遊線路,汽車旅遊，旅行，徒步旅遊，景點旅遊，旅行社，熱點旅遊線路，

折扣旅遊方案');
*/


// product_info.php - if left blank in products_description table these values will be used

define('HTTA_PRODUCT_INFO_ON','1');

define('HTKA_PRODUCT_INFO_ON','1');

define('HTDA_PRODUCT_INFO_ON','1');

define('HEAD_TITLE_TAG_PRODUCT_INFO','');

define('HEAD_DESC_TAG_PRODUCT_INFO','');

define('HEAD_KEY_TAG_PRODUCT_INFO','');



// products_new.php - whats_new

define('HTTA_WHATS_NEW_ON','1');

define('HTKA_WHATS_NEW_ON','1');

define('HTDA_WHATS_NEW_ON','1');

define('HEAD_TITLE_TAG_WHATS_NEW','New Products');

define('HEAD_DESC_TAG_WHATS_NEW','I am ON PRODUCTS_NEW as HEAD_DESC_TAG_WHATS_NEW and over ride the HEAD_DESC_TAG_ALL');

define('HEAD_KEY_TAG_WHATS_NEW','I am on PRODUCTS_NEW as HEAD_KEY_TAG_WHATS_NEW and over ride HEAD_KEY_TAG_ALL');



// specials.php

// If HEAD_KEY_TAG_SPECIALS is left blank, it will build the keywords from the products_names of all products on special

define('HTTA_SPECIALS_ON','1');

define('HTKA_SPECIALS_ON','1');

define('HTDA_SPECIALS_ON','1');

define('HEAD_TITLE_TAG_SPECIALS','Specials');

define('HEAD_DESC_TAG_SPECIALS','');

define('HEAD_KEY_TAG_SPECIALS','');



// product_reviews_info.php and product_reviews.php - if left blank in products_description table these values will be used

define('HTTA_PRODUCT_REVIEWS_INFO_ON','1');

define('HTKA_PRODUCT_REVIEWS_INFO_ON','1');

define('HTDA_PRODUCT_REVIEWS_INFO_ON','1');

define('HEAD_TITLE_TAG_PRODUCT_REVIEWS_INFO','');

define('HEAD_DESC_TAG_PRODUCT_REVIEWS_INFO','');

define('HEAD_KEY_TAG_PRODUCT_REVIEWS_INFO','');


// logo top
define('TOP_WORD_ADS','您身邊的旅遊服務專家！');
?>