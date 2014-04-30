<?php

/* If you have the Header Tags Controller already installed, replicate the
   global details from header_tags.php into the indented section below  */

   // Define your email address to appear on all pages
   define('HEAD_REPLY_TAG_ALL','service@crystallight.com.tw');

   // For all pages not defined or left blank, and for articles not defined
   // These are included unless you set the toggle switch in each section below to OFF ( '0' )
   // The HEAD_TITLE_TAG_ALL is included BEFORE the specific one for the page
   // The HEAD_DESC_TAG_ALL is included AFTER the specific one for the page
   // The HEAD_KEY_TAG_ALL is included AFTER the specific one for the page
   define('HEAD_TITLE_TAG_ALL','Crystal Light Centrum');
   define('HEAD_DESC_TAG_ALL','Crystal Light Centrum is dedicated to offering you the best in Crystals, Gemstones, Tarot cards and healing sessions. But we have much more...');
   define('HEAD_KEY_TAG_ALL','Crystals, Crystal, crystal light centrum, light healing, Centrum, Healing, Tarot cards, light, quartz, gemstones, minerals, jewelry, stones, Chakra, amethyst ,chakras, Gilbert Williams, Oracle, articles, Metaphysical, ruby, rocks, session, courses, new age, calendars');

/* End of Indented Section */

// DEFINE TAGS FOR INDIVIDUAL PAGES

// index.php
define('HTTA_PRODUCTS_ON','1'); // Include HEAD_TITLE_TAG_ALL in Title
define('HTKA_PRODUCTS_ON','1'); // Include HEAD_KEY_TAG_ALL in Keywords
define('HTDA_PRODUCTS_ON','1'); // Include HEAD_DESC_TAG_ALL in Description
define('HEAD_TITLE_TAG_ARTICLES','Products');
define('HEAD_DESC_TAG_ARTICLES','Products');
define('HEAD_KEY_TAG_ARTICLES','Products');

// product_info.php - if left blank in articles_description table these values will be used
define('HTTA_PRODUCT_INFO_ON','1');
define('HTKA_PRODUCT_INFO_ON','1');
define('HTDA_PRODUCT_INFO_ON','1');
define('HEAD_TITLE_TAG_PRODUCT_INFO','Products');
define('HEAD_DESC_TAG_PRODUCT_INFO','');
define('HEAD_KEY_TAG_PRODUCT_INFO','');

// products_new.php - new articles
// If HEAD_KEY_TAG_PRODUCTS_NEW is left blank, it will build the keywords from the articles_names of all new articles
define('HTTA_PRODUCTS_NEW_ON','1');
define('HTKA_PRODUCTS_NEW_ON','1');
define('HTDA_PRODUCTS_NEW_ON','1');
define('HEAD_TITLE_TAG_PRODUCTS_NEW','Products New');
define('HEAD_DESC_TAG_PRODUCTS_NEW','');
define('HEAD_KEY_TAG_PRODUCTS_NEW','');

// products_reviews_info.php and products_reviews.php - if left blank in articles_description table these values will be used
define('HTTA_PRODUCT_REVIEWS_INFO_ON','1');
define('HTKA_PRODUCT_REVIEWS_INFO_ON','1');
define('HTDA_PRODUCT_REVIEWS_INFO_ON','1');
define('HEAD_TITLE_TAG_PRODUCT_REVIEWS_INFO','Products Reviews');
define('HEAD_DESC_TAG_PRODUCT_REVIEWS_INFO','');
define('HEAD_KEY_TAG_PRODUCT_REVIEWS_INFO','');

?>