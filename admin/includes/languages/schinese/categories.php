<?php
/*
  $Id: categories.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

// BOF MaxiDVD: Added For Ultimate-Images Pack!
define('TEXT_PRODUCTS_IMAGE_NOTE','<b>Image (435*245):</b><small><br>Main Image used in <br><u>catalog & description</u> pages.<small>');

define('TEXT_PRODUCTS_MAP_NOTE','<b>Map :</b><small><br>Map used in <br><u>catalog & description</u> pages.<small>');

define('TEXT_PRODUCTS_IMAGE_MEDIUM', '<b>Small (159*103):</b><br><small> Image on<br><u>products list</u> pages.</small>');

define('TEXT_PRODUCTS_IMAGE_LARGE', '<b>Pop-up Image:</b><br><small> Large Image on<br><u>pop-up window</u> page.</small>');

define('TEXT_PRODUCTS_IMAGE_LINKED', '<u>Store Product/s Sharing this Image =</u>');
define('TEXT_PRODUCTS_IMAGE_REMOVE', '<b>Remove</b> this Image from this Product?');
define('TEXT_PRODUCTS_IMAGE_DELETE', '<b>Delete</b> this Image from the Server (Permanent!)?');
define('TEXT_PRODUCTS_IMAGE_REMOVE_SHORT', 'Remove');
define('TEXT_PRODUCTS_IMAGE_DELETE_SHORT', 'Delete');
define('TEXT_PRODUCTS_IMAGE_TH_NOTICE', '<b>SM = Small Images.</b> If a "SM" image is used<br>(Alone) NO Pop-up window link is created, the "SM"<br> will be placed directly under the products<br>description. If used in conjunction with an <br>"XL" image on the right, a Pop-up Window Link<br> is created and the "XL" image will be<br>shown in a Pop-up window.<br><br>');
define('TEXT_PRODUCTS_IMAGE_XL_NOTICE', '<b>XL = Large Images.</b> Used for the Pop-up image<br><br><br>');
define('TEXT_PRODUCTS_IMAGE_ADDITIONAL', 'Additional Images - These will appear below product description if used.');

define('TEXT_PRODUCTS_FEATURED_STATUS','Featured Status:');

define('HEADING_TITLE', 'Categories / Products');
define('HEADING_TITLE_SEARCH', 'Search:');
define('HEADING_TITLE_GOTO', 'Go To:');

define('TABLE_HEADING_ID', 'ID');
define('TABLE_HEADING_CATEGORIES_PRODUCTS', 'Categories / Products');
define('TABLE_HEADING_ACTION', 'Action');
define('TABLE_HEADING_STATUS', 'Status');

define('TEXT_PRODUCTS_DESTINATION', 'Attractions:(途经景点)'); // Products Destination

define('TEXT_PRODUCTS_SMALL_DESCRIPTION','Highlights:'); //Products Small Description

define('TEXT_NEW_PRODUCT', 'New Product in &quot;%s&quot;');
define('TEXT_CATEGORIES', 'Categories:');
define('TEXT_SUBCATEGORIES', 'Subcategories:');
define('TEXT_PRODUCTS', 'Products:');
define('TEXT_PRODUCTS_PRICE_INFO', 'Price:');
define('TEXT_PRODUCTS_TAX_CLASS', 'Tax Class:');
define('TEXT_PRODUCTS_AVERAGE_RATING', 'Average Rating:');
define('TEXT_PRODUCTS_QUANTITY_INFO', 'Quantity:');
define('TEXT_DATE_ADDED', 'Date Added:');
define('TEXT_DELETE_IMAGE', 'Delete Image');

define('TEXT_DATE_AVAILABLE', 'Date Available:');
define('TEXT_LAST_MODIFIED', 'Last Modified:');
define('TEXT_IMAGE_NONEXISTENT', 'IMAGE DOES NOT EXIST');
define('TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS', 'Please insert a new category or product in this level.');
define('TEXT_PRODUCT_MORE_INFORMATION', 'For more information, please visit this products <a href="http://%s" target="blank"><u>webpage</u></a>.');
define('TEXT_PRODUCT_DATE_ADDED', 'This product was added to our catalog on %s.');
define('TEXT_PRODUCT_DATE_AVAILABLE', 'This product will be in stock on %s.');

define('TEXT_EDIT_INTRO', 'Please make any necessary changes');
define('TEXT_EDIT_CATEGORIES_ID', 'Category ID:');
define('TEXT_EDIT_CATEGORIES_NAME', 'Category Name:');
define('TEXT_EDIT_CATEGORIES_IMAGE', 'Category Image:');
define('TEXT_EDIT_SORT_ORDER', 'Sort Order:');
define('TEXT_EDIT_CATEGORIES_HEADING_TITLE', 'Category heading title:');
define('TEXT_EDIT_CATEGORIES_DESCRIPTION', 'Category heading Description:');
define('TEXT_EDIT_CATEGORIES_INTRODUCTION_VIDEO', 'Category Introduction Video:');
define('TEXT_EDIT_CATEGORIES_INTRODUCTION_VIDEO_DISCRIPTION', 'Category Introduction Video Description:');

define('TEXT_EDIT_CATEGORIES_INTRODUCTION', 'Category Introduction:');
define('TEXT_EDIT_CATEGORIES_INTRODUCTION_VIDEO_DISCRIPTION', 'Category Introduction Video Description:');
define('IMAGE_RELATED_CATS', 'Related Categories');



define('TEXT_EDIT_CATEGORIES_RECOMMENDED_TORUS', 'Category Recommended Tours:');
define('TEXT_EDIT_CATEGORIES_MAP', 'Category Map:');
define('TEXT_EDIT_CATEGORIES_TITLE_TAG', 'Category Title Meta Tag :');
define('TEXT_EDIT_CATEGORIES_DESC_TAG', 'Category Description Meta Tag :');
define('TEXT_EDIT_CATEGORIES_KEYWORDS_TAG', 'Category Key Word Meta Tag :');

define('TEXT_EDIT_CATEGORIES_SEO_DESCRIPTION','Category Seo Description');
define('TEXT_EDIT_CATEGORIES_LOGO_ALT_TAG','Category Logo Alt Tag');
define('TEXT_EDIT_CATEGORIES_FIRST_SENTENCE','Category First Sentence');

define('TEXT_INFO_COPY_TO_INTRO', 'Please choose a new category you wish to copy this product to');
define('TEXT_INFO_CURRENT_CATEGORIES', 'Current Categories:');

define('TEXT_INFO_HEADING_NEW_CATEGORY', 'New Category');
define('TEXT_INFO_HEADING_EDIT_CATEGORY', 'Edit Category');
define('TEXT_INFO_HEADING_DELETE_CATEGORY', 'Delete Category');
define('TEXT_INFO_HEADING_MOVE_CATEGORY', 'Move Category');
define('TEXT_INFO_HEADING_DELETE_PRODUCT', 'Delete Product');
define('TEXT_INFO_HEADING_MOVE_PRODUCT', 'Move Product');
define('TEXT_INFO_HEADING_COPY_TO', 'Copy To');

define('TEXT_DELETE_CATEGORY_INTRO', 'Are you sure you want to delete this category?');
define('TEXT_DELETE_PRODUCT_INTRO', 'Are you sure you want to permanently delete this product?');

define('TEXT_DELETE_WARNING_CHILDS', '<b>WARNING:</b> There are %s (child-)categories still linked to this category!');
define('TEXT_DELETE_WARNING_PRODUCTS', '<b>WARNING:</b> There are %s products still linked to this category!');

define('TEXT_MOVE_PRODUCTS_INTRO', 'Please select which category you wish <b>%s</b> to reside in');
define('TEXT_MOVE_CATEGORIES_INTRO', 'Please select which category you wish <b>%s</b> to reside in');
define('TEXT_MOVE', 'Move <b>%s</b> to:');

define('TEXT_NEW_CATEGORY_INTRO', 'Please fill out the following information for the new category');
define('TEXT_CATEGORIES_NAME', 'Category Name:');
define('TEXT_CATEGORIES_IMAGE', 'Category Image:');
define('TEXT_SORT_ORDER', 'Sort Order:');

define('TEXT_CATEGORY_STATUS', 'Category Status:');
define('TEXT_CATEGORY_AVAILABLE', 'Active');
define('TEXT_CATEGORY_NOT_AVAILABLE', 'In Active');
define('TEXT_PRODUCT_REGULAR_TOUR', 'Regular Tour 定期日期');
define('TEXT_PRODUCT_NOT_REGULAR_TOUR', 'Irregular Tour 不定期日期');

define('TEXT_PRODUCTS_REGION','Tour Region:');
define('TEXT_PRODUCTS_DURATION_SELECT_CITY', 'Start City:'); //Products City For Departure:
define('TEXT_PRODUCTS_DEPARTURE_END_CITY', 'End City:'); //Tour End City:
define('TEXT_PRODUCTS_TYPE', 'Start Date:'); //Products Type:
define('TEXT_PRODUCTS_STATUS', 'Status:'); //Products Status:
define('TEXT_PRODUCTS_VACATION_PACKAGE', 'Vacation Package:');
define('TEXT_PRODUCT_AVAILABLE_VACATION_PACKAGE', 'Yes');
define('TEXT_PRODUCT_NOT_AVAILABLE_VACATION_PACKAGE', 'No');

define('TEXT_PRODUCTS_DATE_AVAILABLE', 'Products Start Date:');


define('TEXT_PRODUCT_AVAILABLE', 'Available');
define('TEXT_PRODUCT_NOT_AVAILABLE', 'Unavailable');
define('TEXT_PRODUCTS_MANUFACTURER', 'Products Manufacture:');
define('TEXT_PRODUCTS_AGENCY', 'Provider:'); //Products Travel Agency:

define('TEXT_PRODUCTS_NAME', 'Products Name:');
define('TEXT_PRODUCTS_DESCRIPTION', 'Itinerary:'); //Products Description
define('TEXT_PRODUCTS_OTHER_DESCRIPTION', 'Tour Package Includes:');
define('TEXT_PRODUCTS_PACKAGE_EXCLUDES', 'Tour Package Excludes:');
define('TEXT_PRODUCTS_PACKAGE_SPECIAL_NOTES', '特别事项:');
define('TEXT_PRODUCTS_QUANTITY', 'Products Quantity:');
define('TEXT_PRODUCTS_MODEL', 'Tour Code:'); //Products Model
define('TEXT_PRODUCTS_DURATION', 'Duration:'); //Products Duration:
define('TEXT_PRODUCTS_DEPARTURE_PLACE', 'Departure Time and Location:'); //Departure Places
define('TEXT_PRODUCTS_IMAGE', 'Products Image:');
define('TEXT_PRODUCTS_URL', 'Products URL:');
define('TEXT_PRODUCTS_URL_WITHOUT_HTTP', '<small>(without http://)</small>');
define('TEXT_PRODUCTS_PRICE_NET', 'Price (before tax):'); // Products Price (Net):
define('TEXT_PRODUCTS_PRICE_GROSS', 'Price (after tax):'); //Products Price (Gross):
define('TEXT_PRODUCTS_WEIGHT', 'Products Weight:');
define('TEXT_NONE', '--none--');

define('EMPTY_CATEGORY', 'Empty Category');

define('TEXT_HOW_TO_COPY', 'Copy Method :');
define('TEXT_COPY_AS_LINK', 'Link product');
define('TEXT_COPY_AS_DUPLICATE', 'Duplicate product');

define('ERROR_CANNOT_LINK_TO_SAME_CATEGORY', 'Error: Can not link products in the same category.');
define('ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE', 'Error: Catalog images directory is not writeable: ' . DIR_FS_CATALOG_IMAGES);
define('ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST', 'Error: Catalog images directory does not exist: ' . DIR_FS_CATALOG_IMAGES);
define('ERROR_CANNOT_MOVE_CATEGORY_TO_PARENT', 'Error: Category cannot be moved into child category.');

//Header Tags Controller Admin
define('TEXT_PRODUCT_METTA_INFO', '<b>Meta Tag Information</b>');
define('TEXT_PRODUCTS_PAGE_TITLE', 'Page Title:'); //Products Page Title
define('TEXT_PRODUCTS_HEADER_DESCRIPTION', 'Meta Description:'); // Page Header Description
define('TEXT_PRODUCTS_KEYWORDS', 'Meta Keywords:'); //Product Keywords
define('IMAGE_EDIT_ATTRIBUTES', 'Edit Product Attributes');

define('HEADING_TITLE_DURATION','Select Duration:');
define('HEADING_TITLE_DEPARTURE','Select Departure:');

define('TABLE_HEADING_CATEGORIES_ID','ID');
define('HEADING_TITLE_PRODUCTS_TYPE','Tour Category:');
define('HEADING_TITLE_OPERATE_START_DATE', 'Operate Start Date');
define('HEADING_TITLE_OPERATE_END_DATE', 'Operate End Date');
define('TEXT_PRODUCTS_SPECIAL_RPICING_NOTE','Pricing Special Notes:');

define('TEXT_PRODUCTS_VIDEO','Video:');
define('TEXT_EDIT_CATEGORIES_MAP_IMAGE','Upload Map Image');
define('TEXT_EDIT_CATEGORIES_BANNER_IMAGE','Upload Top Banner Image<br/>size: 591*165');
define('TABLE_HEADING_TOUR_CODE','Tour Code:');
define('TEXT_AFFILIATE_VALIDPRODUCTS', 'Click Here:');
define('TEXT_AFFILIATE_INDIVIDUAL_BANNER_VIEW', 'to view available products.');
define('TEXT_AFFILIATE_INDIVIDUAL_BANNER_HELP', 'Select the product number from the popup window and enter the number in the Product ID field.');


//default define for tours
define('TOURS_DEFAULT_PRICING_NOTES', '<div class="pr_2"><div class="p_p">*Price for Single Occupancy applies when one person stays in one standard hotel room.</div><div class="p_p">*Price for Double/Triple/Quadruple Occupancy applies when two/three/four people stay in one standard hotel room respectively. </div><div class="p_p">*Triple/Quadruple price does not guarantee a third/forth bed in the room but it can be requested at the time of check-in.</div><div class="p_p">*Price for child applies when a 2-9 years old child staying in one standard room as a third or fourth person. </div><div class="p_p">*Maximum room capacity: 4 people including adult and child/infant.</div></div>');
define('TOURS_DEFAULT_PACKAGE_SPECIAL_NOTES', '<LI>If you need to book air ticket, we recommend that you please book your air ticket after you receive confirmation email for your reservation within one to two business days from us. </LI><LI>Spring/Summer: wear lightweight clothing and comfortable shoes.<BR/>Fall/Winter: wear layered clothing in the winter to ensure comfort in the cold weather.</LI>');
define('TOURS_DEFAULT_PACKAGE_INCULDES', '<LI>Complimentary pick-up and drop-off to chosen location (see Pick-up Time and Locations)</LI><LI>3-night hotel accommodations</LI><LI>Admission to above-mentioned national park(s)</LI><LI>Ground transportation in air-conditioned deluxe tour coach</LI><LI>Professional tour escort</LI>');
define('TOURS_DEFAULT_PACKAGE_EXCLUDES', '<LI>Airfares or related transportation between your home and Los Angeles airport or chosen pickup location</LI><LI>All personal expenses including hotel room service and meals; however, you have the option to pay for meals that tour guide arranges</LI><LI>Optional tours (Prices may vary depending on availability.):<br/>-After-dusk Sightseeing in Las Vegas (**$25.00/person included dinner buffet.)<br/>-All luxury shows in Las Vegas<br/></LI><LI>Gratuities for tour guide (min. $5.00/day per person)</LI>');


//default define for tour packages
define('TOURS_VACATION_PACKAGE_DEFAULT_PRICING_NOTES', '<div class="pr_2"><div class="p_p">1*Price for Single Occupancy applies when one person stays in one standard hotel room.</div><div class="p_p">*Price for Double/Triple/Quadruple Occupancy applies when two/three/four people stay in one standard hotel room respectively. </div><div class="p_p">*Triple/Quadruple price does not guarantee a third/forth bed in the room but it can be requested at the time of check-in.</div><div class="p_p">*Price for child applies when a 2-9 years old child staying in one standard room as a third or fourth person. </div><div class="p_p">*Maximum room capacity: 4 people including adult and child/infant.</div></div>');
define('TOURS_VACATION_PACKAGE_DEFAULT_PACKAGE_SPECIAL_NOTES', '<LI><span class="sp1_red">Please coordinate schedule of your flight with that of your tour. We recommend that you please book your air tickets after you receive E-Ticket confirming all details for your tour reservation within one to two business days from us.</span></LI><LI>Please meet tour guide at <span class="sp1_red">Baggage Claim Area</span> after your arrival at Los Angeles airport on the first day.</LI><LI>Should weather or other unforeseen circumstances cause a flight delay for more than 45 minutes, you will have to arrange transportation on your own. However, you are welcome to contact local tour provider to see if there is available staff to accommodate your needs. </LI><LI>We strongly recommend that you should contact airline and local tour provider to confirm your flight arrival information and pickup arrangement before your departure.</LI><LI><span class="sp1_red">Please arrange your flight after 9:30pm on the last day to complete your tour activities.</span></LI><LI>Spring/Summer: wear lightweight clothing and comfortable shoes.<br/>Fall/Winter: wear layered clothing in the winter to ensure comfort in the cold weather.</LI>');
define('TOURS_VACATION_PACKAGE_DEFAULT_PACKAGE_INCULDES', '<LI>Complimentary airport pick-up (8:00am-10:00pm) and drop-off</LI><LI>3-night hotel accommodations</LI><LI>Admission to above-mentioned national park(s)</LI><LI>Ground transportation in air-conditioned deluxe tour coach</LI><LI>Professional tour escort</LI>');
define('TOURS_VACATION_PACKAGE_DEFAULT_PACKAGE_EXCLUDES', '<LI>Airfares between your home and Los Angeles airport</LI><LI>All personal expenses including hotel room service and meals; however, you have the option to pay for meals that tour guide arranges</LI><LI>Optional tours (Prices may vary depending on availability.):<br/>-After-dusk Sightseeing in Las Vegas (**$25.00/person included dinner buffet.)<br/>-All luxury shows in Las Vegas<br/></LI><LI>Gratuities for tour guide (min. $5.00/day per person)</LI>');

define('TEXT_PRODUCTS_TO_CATEGORIES','Product Category:');

define('TEXT_PRODUCTS_DISPLAY_PICKUP_HOTELS','Display Pickup Hotels:');

define('TEXT_PRODUCTS_DISPLAY_ITINERARY_NOTES','Display Day-to-day Itinerary Note:');
define('TEXT_PRODUCTS_DISPLAY_HOTEL_UPGRADE_NOTES','Display Hotel Upgrade Note:');

define('TEXT_PRODUCTS_MIN_GUEST','参团最少人数:');
define('TEXT_PRODUCTS_BUY_TWO_GET_ONE_SURCHARGE','买二送一免费人员的附加费用');
define('TEXT_USE_BUY_TWO_GET_ONE_PRICE','是否使用买二送一公式计价');

define('TEXT_PRODUCTS_TEXT_RADIO_YES','Yes');
define('TEXT_PRODUCTS_TEXT_RADIO_NO','No');

define('TEXT_HEADING_SINGLE_OCC_PRICE','Single Occupancy Standard Price:'); //Single Occupancy Price:
define('TEXT_HEADING_SINGLE_PU_OCC_PRICE','Single Occupancy Pair Up Price:'); //Single Occupancy Pair Up Price:
define('TEXT_HEADING_DOUBLE_OCC_PRICE','Double Occupancy Standard Price:'); //Double Occupancy Price:
define('TEXT_HEADING_TRIPLE_OCC_PRICE','Triple Occupancy Standard Price:'); //Triple Occupancy Price:
define('TEXT_HEADING_QUADRUPLE_OCC_PRICE','Quadruple Occupancy Standard Price:'); //Quadruple Occupancy Price:
define('TEXT_HEADING_KIDS_OCC_PRICE','Kids Standard Price:'); //Kids Price:
define('TEXT_HEADING_ADULT_PRICE','Adult Standard Price:');  //Adult Price:
define('TEXT_HEADING_KIDS_PRICE','Child Standard Price:');  //Kids Price:
define('TEXT_HEADING_SPL_NOTES','电子参团凭证默认的温馨提示语：');  //Special Note:
define('TEXT_HEADING_TITLE_TOUR_CATEGORIZATION','Tour Categorization');
define('TEXT_HEADING_TITLE_ROOM_AND_PRICE','Room and Price 房间与标准价格');
define('TEXT_HEADING_TITLE_TOUR_OPERATION','Tour Operation');
define('TEXT_HEADING_TITLE_TOUR_CONTENT','Tour Content');
define('TEXT_HEADING_TITLE_TOUR_TAG_INFORMATION','Meta Tag Information');
define('TEXT_PRODUCTS_URL_NAME','Product URLname:');
define('TEXT_HEADING_IMAGES_VIDEOS','Tour Image/Video');
define('TEXT_HEADING_ETICKET_ITINERARY','Itinerary');
define('TEXT_HEADING_ETICKET_HOTEL','Hotel');
define('TEXT_HEADING_ETICKET_NOTES','Notes');
define('TEXT_HEADING_ETICKET_INFORMATION','E-ticket Information');
define('TEXT_HEADING_ETICKET','E-Ticket');
define('TEXT_HEADING_CITIES_BY_COUNTRY','Filter Cities by Country:');
define('TEXT_HEADING_CITIES_BY_STATE','Filter Cities by State:');

define('TEXT_HEADING_TITLE_TOUR_ATTRIBUTES','Tour Attributes');

define('TEXT_CALCULATE_RETAIL_PRICE','算底价');
define('TEXT_CALCULATE_RETAIL_PRICE_ATTRIBUTE','算底价');
define('TEXT_CALCULATE_RETAIL_PRICE_OPRATION','算底价');
define('TEXT_NOTICE_CP_AND_RP','Note: RP = Retail Price,  CP = Cost Price');
define('TEXT_HEADING_GROSS_PROFIT','Gross Profit');

define('TEXT_EDIT_CATEGORIES_TOP_BANNER_IMAGE_ALT_TAG','Upload Top Banner Image Alt Tag');
define('ERROR_URL_EXIST', 'Category URLname already exist for other category. Please update to other Category URLname.');
define('TEXT_PRODUCTS_PROVIDER_MODEL', 'Provider Tour Code:');
define('TEXT_PRODUCTS_MAX_CILD_AGE','Maximum Allow Child Age:');
define('TEXT_PRODUCTS_MAX_CILD_DISPLY_YEAR_NOTE',' years &nbsp;&nbsp;  <small style="color:#FF0000;"><b>Note:</b> Leave Black if you want use default max allow child age by agency</small>');
define('TEXT_PRODUCTS_TRASACTION_FEE',' Transaction Fee:');
define('TEXT_PRODUCTS_TRASACTION_FEE_NOTE','% &nbsp;&nbsp;  <small style="color:#FF0000;"><b>Note:</b> Leave None Selected if you want use default transaction fee by agency</small>');

define('TEXT_PRODUCTS_STOCK','库存状态:');
define('TEXT_PRODUCTS_IN_STOCK','正常');
define('TEXT_PRODUCTS_OUT_STOCK','已卖完');

define('TEXT_PRODUCTS_TOUR_TYPE_ICON', '显示的类型标签:');
define('TEXT_SOLDOUT_DATES', 'Sold out dates:');
define('TEXT_REMAINING_SEATS','本团座位剩余情况:');
define('TEXT_PRODUCTS_UPGRADE_TO', 'Upgrade to:'); //Products Model
define('TEXT_HEADING_ETICKET_PICKUP','Pick-up Details');
define('TXT_PAGE_TEMPLATES','Page Templates');
define('TXT_DEFAULT','Default');
define('TXT_LAS_VEGAS_SHOW','Las Vegas Show');
define('TXT_VENUES','Venues');
define('TXT_NO','No');
define('TXT_MINIMUM_AGE','Minimum Age');
define('TXT_UNLIMITED','Unlimited');
define('TXT_OVER','Over');
define('TEXT_AGENCY_OPERATE_LANGUAGE', 'Operating Language:');
define('TEXT_AGENCY_OPERATE_LANGUAGE_NOTE', '<small style="color:#FF0000;"><b>Note:</b> Leave Black if you want to use Agency Default Language: %s</span>');
define('TXT_ADD_NEW', 'Add New');
define('TEXT_REMAINING_SEATS','Remaining Seats:');
define('TXT_SELECT_DATE', 'Tour Date');
define('TXT_SEATS', 'Seats');
define('TEXT_PRODUCTS_URL_NAME_IN_ENGLISH','Tour Name in English:');

//howard added 2010-09-28
define('AGENCY_CONTACT_INFORMATION','地接社或服务公司信息：');
define('AGENCY_NAME','公司名称');
define('AGENCY_PHONE','联系电话');
define('AGENCY_EMERENCY_CONTACTPERSON','紧急联系人');
define('AGENCY_EMERENCY_PHONE_NUMBER','紧急联系电话');
define('AGENCY_NOTES','建议组合团填写地接社或服务公司信息');
define('TEXT_IS_PASSPORT_VISA', 'Display Visa/Passport Note?');
define('TEXT_PRODUCTS_TEXT_RADIO_VISA_NO','None');
define('TEXT_PRODUCTS_TEXT_RADIO_VISAPASS_NO','No Canadian Visa/Passport');
define('TEXT_PRODUCTS_TEXT_RADIO_VISAPASS_YES','Yes Canadian Visa/Passport Required');
define('TEXT_PRODUCTS_TEXT_RADIO_YES','Yes');
define('TEXT_PRODUCTS_TEXT_RADIO_NO','No');

define('TEXT_PRODUCTS_NAME_PROVIDER', 'Tour Name by Provider: <small style="color:#FF0000;">(Optional)</small>');
//hotel-extension 
define('TEXT_PRODUCT_HOTELS_PRE_POST','Hotels - Early Arrival And Late Departure');
define('TEXT_IS_HOTEL', '是否为酒店产品?');
define('TEXT_PRODUCT_HOTELS_NEARBY_ATTRACTIONS', '<b>Hotels - Nearby Attractions</b>');
define('TEXT_IS_CRUISES','是否为邮轮团');

?>