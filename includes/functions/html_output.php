<?php
/*
  $Id: html_output.php,v 1.2 2004/03/05 00:36:42 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

////
// The HTML href link wrapper function. use for JS code. 主要用于JS代码中的url地址
  function tep_href_link_noseo($page = '', $parameters = '', $connection = 'NONSSL', $add_session_id = false, $search_engine_safe = true) {
    global $request_type, $session_started, $SID, $spider_flag;

    if (!tep_not_null($page)) {
      die('</td></tr></table></td></tr></table><br><br><font color="#ff0000"><b>Error!</b></font><br><br><b>Unable to determine the page link!<br><br>');
    }

    if ($connection == 'NONSSL') {
      $link = HTTP_SERVER . DIR_WS_HTTP_CATALOG;
    } elseif ($connection == 'SSL') {
      if (ENABLE_SSL === true && in_array($page,array('login.php','create_account.php','providers_login.php'))) {	//只有登录和注册页开放SSL，其它的不要开放因为另一个域名imagecss.usitrip.com.cn没证
		$link = HTTPS_SERVER . DIR_WS_HTTP_CATALOG;
      } else {
        $link = HTTP_SERVER . DIR_WS_HTTP_CATALOG;
      }
    } else {
      die('</td></tr></table></td></tr></table><br><br><font color="#ff0000"><b>Error!</b></font><br><br><b>Unable to determine connection method on a link!<br><br>Known methods: NONSSL SSL</b><br><br>');
    }

    if (tep_not_null($parameters)) { 
      $link .= $page.'?'.tep_output_string($parameters);
      $separator = '&';
    } else {
      $link .= $page;
      $separator = '?';
    }

    while ( (substr($link, -1) == '&') || (substr($link, -1) == '?') ) $link = substr($link, 0, -1);

// Add the session ID when moving from different HTTP and HTTPS servers, or when SID is defined
    if ( ($add_session_id == true) && ($session_started == true) && (SESSION_FORCE_COOKIE_USE == 'False') ) {
      if (tep_not_null($SID)) {
        $_sid = $SID;
      } elseif ( ( ($request_type == 'NONSSL') && ($connection == 'SSL') && (ENABLE_SSL == true) ) || ( ($request_type == 'SSL') && ($connection == 'NONSSL') ) ) {
        if (HTTP_COOKIE_DOMAIN != HTTPS_COOKIE_DOMAIN) {
          $_sid = tep_session_name() . '=' . tep_session_id();
        }
      }
    }

    if ( (SEARCH_ENGINE_FRIENDLY_URLS == 'true') && ($search_engine_safe == true) ) {
      while (strstr($link, '&&')) $link = str_replace('&&', '&', $link);

      $link = str_replace('?', '/', $link);
      $link = str_replace('&', '/', $link);
      $link = str_replace('=', '/', $link);

      $separator = '?';
    }

    if (isset($_sid) && !$spider_flag) {
      $link .= $separator . tep_output_string($_sid);
    }

    return $link;
  }

// The HTML href link wrapper function - SEOurls - as above, except for special handling of index.php and product_info.php
// $add_session_id 是否需要加osCsid参数的意思。
  function tep_href_link($page = '', $parameters = '', $connection = 'NONSSL', $add_session_id = false, $search_engine_safe = true, $tablink = '',$s2pcat = '') {
    global $request_type, $session_started, $SID;
//	echo $parameters;
	
    if (!tep_not_null($page)) {
      die('</td></tr></table></td></tr></table><br><br><font color="#ff0000"><b>Error!</b></font><br><br><b>Unable to determine the page link!<br><br>');
    }

    if ($connection == 'NONSSL') {
      $link = HTTP_SERVER . DIR_WS_HTTP_CATALOG;
    } elseif ($connection == 'SSL') {
      if (ENABLE_SSL === true && in_array($page,array('login.php','create_account.php','providers_login.php'))) {	//只有登录和注册页开放SSL，其它的不要开放因为另一个域名imagecss.usitrip.com.cn没证
		$link = HTTPS_SERVER . DIR_WS_HTTPS_CATALOG;
      } else {
        $link = HTTP_SERVER . DIR_WS_HTTP_CATALOG;
      }
    } else {
      die('</td></tr></table></td></tr></table><br><br><font color="#ff0000"><b>Error!</b></font><br><br><b>Unable to determine connection method on a link!<br><br>Known methods: NONSSL SSL</b><br><br>');
    }

    // SEOurls - hack to change links of forms:
    // index.php?cPath=2_3            => /category-name/subcategory-name/
    // product_info.php?products_id=1 => /category-name/subcategory-name/product-name.html
    $no_osCsid = false;
	switch($page){
		case FILENAME_DEFAULT:	//目录和产品列表页
			if(preg_match('/cPath=([0-9_]+)/', $parameters, $m)) {
				$cPath = $m[1];
				$parameters = preg_replace('/cPath=[0-9_]+&?/', '', $parameters);
				$page = seo_get_path_from_cpath($cPath);
			}
			
			if(preg_match('/mnu=introductionCruises/', $parameters, $m)) {				
				$parameters = preg_replace('/mnu=introductionCruises/', '', $parameters);
				$page = $page.'introductionCruises/';
			}else if(preg_match('/mnu=introduction/', $parameters, $m)) {				
				$parameters = preg_replace('/mnu=introduction/', '', $parameters);
				$page = $page.'introduction/';
			}else if(preg_match('/mnu=tours/', $parameters, $m)) {				
				$parameters = preg_replace('/mnu=tours/', '', $parameters);
				//$page = $page.'tours/';
			}else if(preg_match('/mnu=vcpackages/', $parameters, $m)) {				
				$parameters = preg_replace('/mnu=vcpackages/', '', $parameters);
				$page = $page.'packages/';
			}else if(preg_match('/mnu=recommended/', $parameters, $m)) {				
				$parameters = preg_replace('/mnu=recommended/', '', $parameters);
				$page = $page.'recommended/';
			}else if(preg_match('/mnu=special/', $parameters, $m)) {				
				$parameters = preg_replace('/mnu=special/', '', $parameters);
				$page = $page.'special/';
			}else if(preg_match('/cat_mnu_sel=special/', $parameters, $m)) {				
				$parameters = preg_replace('/cat_mnu_sel=special/', '', $parameters);
				$page = $page.'special/';
			}else if(preg_match('/cat_mnu_sel=diy/', $parameters, $m)) {				
				$parameters = preg_replace('/cat_mnu_sel=diy/', '', $parameters);
				$page = $page.'diy/';
			}else if(preg_match('/mnu=diy/', $parameters, $m)) {				
				$parameters = preg_replace('/mnu=diy/', '', $parameters);
				$page = $page.'diy/';
			}else if(preg_match('/mnu=show/', $parameters, $m)) {				
				$parameters = preg_replace('/mnu=show/', '', $parameters);
				$page = $page.'show/';
			}else if(preg_match('/cat_mnu_sel=show/', $parameters, $m)) {				
				$parameters = preg_replace('/cat_mnu_sel=show/', '', $parameters);
				$page = $page.'show/';
			}else if(preg_match('/mnu=maps/', $parameters, $m)) {				
				$parameters = preg_replace('/mnu=maps/', '', $parameters);
				$page = $page.'maps/';
			}else{
				//当目录不是大峡谷飞机团和秀团、酒店的就显示套餐为默认页面
				//echo '['.$cPath.']';
				$tmp_array = explode('_',$cPath);
				if(!in_array('31',$tmp_array) && !in_array('182',$tmp_array) && !in_array('189',$tmp_array) && !in_array('196',$tmp_array) && 
				   !in_array('197',$tmp_array) && !in_array('203',$tmp_array) && !in_array('204',$tmp_array) && 
				   tep_not_null($cPath)){
					
					$parameters = preg_replace('/mnu=vcpackages/', '', $parameters);
					$page = $page.'packages/';
				}
			}
	
			if(preg_match('/page=([0-9]+)/', $parameters, $m)) {
				$page1 = $m[1];
				$parameters = preg_replace('/page=[0-9]+&?/', '', $parameters);
				$page = $page.'p-'.$page1.'/';
			}
			if(preg_match('/sort=([0-9a-zA-Z]+)/', $parameters, $m)) {
				$sort1 = $m[1];
				$parameters = preg_replace('/sort=[0-9a-zA-Z]+&?/', '', $parameters);
				$page = $page.'s-'.$sort1.'/';
			}	
		break;
		case FILENAME_PRODUCT_INFO:	//产品详细页
			if(preg_match('/products_id=([0-9]+)/', $parameters, $m)) {
				$products_id = $m[1];
				$parameters = preg_replace('/products_id=[0-9]+&?/', '', $parameters);
				$parameters = preg_replace('/cPath=[0-9_]+&?/', '', $parameters);
				//$parameters = preg_replace('/mnu=[0-9a-zA-Z]+&?/', '', $parameters);
				
				if(preg_match('/mnu=qanda/', $parameters, $m)) {				
						$parameters = preg_replace('/mnu=qanda/', '', $parameters);
						$tablink = "/question-&-answer/";
						//Howard added 2011-03-29 
						if(preg_match('/seeAll=all-questions/', $parameters, $m)){
							$parameters = preg_replace('/seeAll=all-questions/', '', $parameters);
							$tablink = "/all-questions/";
						}

						
				}else if(preg_match('/mnu=prices/', $parameters, $m)) {				
						$parameters = preg_replace('/mnu=prices/', '', $parameters);
						$tablink = "/prices/";
				}else if(preg_match('/mnu=departure/', $parameters, $m)) {				
						$parameters = preg_replace('/mnu=departure/', '', $parameters);
						$tablink = "/departure/";
				}else if(preg_match('/mnu=notes/', $parameters, $m)) {				
						$parameters = preg_replace('/mnu=notes/', '', $parameters);
						$tablink = "/notes/";
				}else if(preg_match('/mnu=frequentlyqa/', $parameters, $m)) {				
						$parameters = preg_replace('/mnu=frequentlyqa/', '', $parameters);
						$tablink = "/frequentlyqa/";
				}else if(preg_match('/mnu=reviews/', $parameters, $m)) {				
						$parameters = preg_replace('/mnu=reviews/', '', $parameters);
						$tablink = "/reviews/";
						//Howard added 2011-03-29 
						if(preg_match('/seeAll=all-reviews/', $parameters, $m)){
							$parameters = preg_replace('/seeAll=all-reviews/', '', $parameters);
							$tablink = "/all-reviews/";
						}
						
				}else if(preg_match('/mnu=photos/', $parameters, $m)) {
						$parameters = preg_replace('/mnu=photos/', '', $parameters);
						$tablink = "/traveler-photos/";
						//Howard added 2011-03-29 
						if(preg_match('/seeAll=all-photos/', $parameters, $m)){
							$parameters = preg_replace('/seeAll=all-photos/', '', $parameters);
							$tablink = "/all-photos/";
						}
						
				}else if(preg_match('/mnu=video/', $parameters, $m)) {
						$parameters = preg_replace('/mnu=video/', '', $parameters);
						$tablink = "/video/";
				}else if(preg_match('/mnu=cruisesIntroduction/', $parameters, $m)) {
						$parameters = preg_replace('/mnu=cruisesIntroduction/', '', $parameters);
						$tablink = "/cruisesIntroduction/";
				}else{				
						$parameters = preg_replace('/mnu=[0-9a-zA-Z]+&?/', '', $parameters);
						$tablink = "";
				}
					
				$page = seo_get_products_path($products_id, true, $tablink);
				
				///amit modified for new rewrite start
				
				if(preg_match('/rn=([0-9]+)/', $parameters, $m)) {
					$page1 = $m[1];
					$parameters = preg_replace('/rn=[0-9]+&?/', '', $parameters);
					$page = $page.'rn-'.$page1.'/';
				}
				
				if(preg_match('/page=([0-9]+)/', $parameters, $m)) {
					$page1 = $m[1];
					$parameters = preg_replace('/page=[0-9]+&?/', '', $parameters);
					$page = $page.'p-'.$page1.'/';
				}
				if(preg_match('/page1=([0-9]+)/', $parameters, $m)) {
					$page1 = $m[1];
					$parameters = preg_replace('/page1=[0-9]+&?/', '', $parameters);
					$page = $page.'vp-'.$page1.'/';
				}
				if(preg_match('/sort=([0-9a-zA-Z]+)/', $parameters, $m)) {
					$sort1 = $m[1];
					$parameters = preg_replace('/sort=[0-9a-zA-Z]+&?/', '', $parameters);
					$page = $page.'s-'.$sort1.'/';
				}	
				if(preg_match('/sort1=([0-9a-zA-Z]+)/', $parameters, $m)) {
					$sort1 = $m[1];
					$parameters = preg_replace('/sort1=[0-9a-zA-Z]+&?/', '', $parameters);
					$page = $page.'vs-'.$sort1.'/';
				}		
				
				//amit modified for new rewrite end
			
			}
		break;
		case FILENAME_JOIN_AFFILATE_PROGRAM_HTML:
			$page = 'join-affiliate-program.html';
		break;
		case FILENAME_ADVANCED_SEARCH_RESULT:	//搜索页面
			if(preg_match('/show_dropdown=true/', $parameters, $m)) {				
				$parameters = preg_replace('/show_dropdown=(true)&?/', '', $parameters);
				$page = 'tours-search';
				$inside_search = 'true';
				/*if(preg_match('/keywords=([^&]+)/', $parameters, $m)){
					$parameters = preg_replace('/keywords=([^&]+)&?/', '', $parameters);
					//$page .= '/keywords/'.rawurlencode($m[1]);
					$page .= '/keywords/'.$m[1];
				}*/
			}
	
		break;
		case FILENAME_ARTICLE_INFO:
			if(preg_match('/articles_id=([0-9]+)/', $parameters, $m)) {
				$articles_id = $m[1];
				
				$parameters = preg_replace('/articles_id=[0-9]+&?/', '', $parameters);
				//$parameters = preg_replace('/cPath=[0-9_]+&?/', '', $parameters);
				//$parameters = preg_replace('/mnu=[0-9a-zA-Z]+&?/', '', $parameters);
				
				$url_pre = 'articles/';
	
				$no_html_ext = SEO_EXTENSION;
				if(preg_match('/rn=([0-9]+)/', $parameters, $m)) {
					$no_html_ext = '/';
				}
	
				$page = seo_get_article_seo_path($articles_id, $url_pre, $no_html_ext);
	
				///amit modified for new rewrite start
				
				if(preg_match('/rn=([0-9]+)/', $parameters, $m)) {
				$page1 = $m[1];
				$parameters = preg_replace('/rn=[0-9]+&?/', '', $parameters);
				$page = $page.'rn-'.$page1.'/';
				}
	
				if(preg_match('/page=([0-9]+)/', $parameters, $m)) {
				$page1 = $m[1];
				$parameters = preg_replace('/page=[0-9]+&?/', '', $parameters);
				$page = $page.'p-'.$page1.'/';
				}
				if(preg_match('/page1=([0-9]+)/', $parameters, $m)) {
				$page1 = $m[1];
				$parameters = preg_replace('/page1=[0-9]+&?/', '', $parameters);
				$page = $page.'vp-'.$page1.'/';
				}
				if(preg_match('/sort=([0-9a-zA-Z]+)/', $parameters, $m)) {
					$sort1 = $m[1];
					$parameters = preg_replace('/sort=[0-9a-zA-Z]+&?/', '', $parameters);
					$page = $page.'s-'.$sort1.'/';
					}
				if(preg_match('/sort1=([0-9a-zA-Z]+)/', $parameters, $m)) {
					$sort1 = $m[1];
					$parameters = preg_replace('/sort1=[0-9a-zA-Z]+&?/', '', $parameters);
					$page = $page.'vs-'.$sort1.'/';
					}
				
				//amit modified for new rewrite end
	
			}
		break;
		case "seo_news.php":	//seo资讯首页
			$page = "default/";
		break;
		case "article_news_list.php":	//seo资讯列表
			$page = "default/";
			if(preg_match('/class_id=([0-9]+)/', $parameters, $m)) {
				$parameters = preg_replace('/class_id=([0-9]+)/', '', $parameters);
				$page = $page.'cid-'.$m[1].'/';
			}
			if(preg_match('/page=([0-9]+)/', $parameters, $m)) {
				$parameters = preg_replace('/page=([0-9]+)/', '', $parameters);
				$page = $page.'p-'.$m[1].'/';
			}
		break;
		case "article_news_content.php":	//seo资讯文章详细页
			$page = "article/";
			if(preg_match('/news_id=([0-9]+)/', $parameters, $m)) {
				$parameters = preg_replace('/news_id=([0-9]+)/', '', $parameters);
				$page = $page.'nid-'.$m[1].'/';
			}
		break;
		case "destination_guide_details.php":	//目的地指南详细页面
			/*目的地指南根页面：highlights_guide.html
			  美国：highlights_guide/america.html
			  纽约概况：highlights_guide/newyork.html
			  纽约景点：highlights_guide/newyork_highlights.html
			  纽约住宿：highlights_guide/newyork_Accommodation.html
			  纽约交通：highlights_guide/newyork_traffic.html
			  纽约饮食：highlights_guide/newyork_food.html
			  纽约购物：highlights_guide/newyork_shopping.html 
			*/
			$dg_name_en = ''; 
			if(preg_match('/dg_categories_id=([0-9]+)/', $parameters, $m)) {	//处理dg_categories_id参数			
				//$parameters = preg_replace('/dg_categories_id=([0-9]+)&?/', '', $parameters);
				$dg_name_en = seo_get_name_for_dg_categories_name_en($m[1]);
			}
			if(tep_not_null($dg_name_en)){
				$page = "highlights_guide/";
				$parameters = preg_replace('/dg_categories_id=([0-9]+)&?/', '', $parameters);
				if(preg_match('/field=(overview)/', $parameters, $m) || !preg_match('/field=/', $parameters)) {				
					$parameters = preg_replace('/field=overview&?/', '', $parameters);
					$page = $page.$dg_name_en.'.html';
					
				}
				if(preg_match('/field=(lodging)/', $parameters, $m)) {				
					$parameters = preg_replace('/field=lodging&?/', '', $parameters);
					$page = $page.$dg_name_en.'_accommodation.html';
				}
				if(preg_match('/field=(traffic)/', $parameters, $m)) {				
					$parameters = preg_replace('/field=traffic&?/', '', $parameters);
					$page = $page.$dg_name_en.'_traffic.html';
				}
				if(preg_match('/field=(shopping)/', $parameters, $m)) {				
					$parameters = preg_replace('/field=shopping&?/', '', $parameters);
					$page = $page.$dg_name_en.'_shopping.html';
				}
				if(preg_match('/field=(food)/', $parameters, $m)) {				
					$parameters = preg_replace('/field=food&?/', '', $parameters);
					$page = $page.$dg_name_en.'_food.html';
				}
				if(preg_match('/field=(features)/', $parameters, $m)) {				
					$parameters = preg_replace('/field=features&?/', '', $parameters);
					$page = $page.$dg_name_en.'_features.html';
				}
				if(preg_match('/field=(map)/', $parameters, $m)) {				
					$parameters = preg_replace('/field=map&?/', '', $parameters);
					$page = $page.$dg_name_en.'_map.html';
				}
			}
		break;
		case "landing-page.php":	//landing page
			$page = "landingpage/";
			$no_osCsid = true;
			if(preg_match('/landingpagename=(\S+[^\/&?])/', $parameters, $m)) {
				$parameters = preg_replace('/landingpagename=(\S+[^\/&?])&?/', '', $parameters);
				$page .= $m[1]."/";
			}
		break;
		case "destination_guide.php":	//目的地指南根页面
			$page = "highlights_guide.html";
			
		break;
		case "links.php":	//友情连接页面
			$page = "links.html";
			if(preg_match('/cId=([0-9]+)/', $parameters, $m)) {
				$parameters = preg_replace('/cId=([0-9]+)&?/', '', $parameters);
				$page = "hezuo".$m[1].".html";
			}
			$no_osCsid = true;
		break;
		case "new_travel_companion_index.php"://新结伴同游主页和列表页
			//$parameters = preg_replace('/cId=([0-9]+)&?/', '', $parameters); //参数太多，直接循环处理
			//在无-号和非汉字字符的情况下优化 !preg_match('/[^\x{4e00}-\x{9fa5}]+/u',iconv(CHARSET,'utf-8',$parameters)
			$page = "jiebantongyou/";
			if(!preg_match('/\-/',$parameters)){
				$parameters = str_replace(array('&','?'),'&',$parameters);
				$parameters = preg_replace('/^&*(.+)&*$/','$1',$parameters);
				if(tep_not_null($parameters)){
					$par_list = explode('&',$parameters);
					for($n=0; $n <sizeof($par_list); $n++){
						$tmp_array = explode('=',$par_list[$n]);
						if(tep_not_null($tmp_array[0]) && $tmp_array[1]!=""){
							//$page .= $tmp_array[0]."-".rawurlencode($tmp_array[1])."-";	//将参数值转义，在404文件中需要转回rawurldecode
							$page .= $tmp_array[0]."-".$tmp_array[1]."-";	//不明白为什么不需要转义
						}
					}
					$page = substr($page,0,-1).'.html';
				}
				$parameters = "";
			}
			
		break;
		case "new_bbs_travel_companion_content.php"://新结伴同游详细页
			$page = "jiebantongyou-content/";
			if(preg_match('/t_companion_id=([0-9]+)&?/', $parameters, $m)) {
				$parameters = preg_replace('/t_companion_id=([0-9]+)&?/', '', $parameters);
				$page .= 'tci-'.$m[1]."-";
			}
			if(preg_match('/TcPath=([0-9_]+)&?/', $parameters, $m)) {
				$parameters = preg_replace('/TcPath=([0-9_]+)&?/', '', $parameters);
				$page .= 'tcp-'.$m[1]."-";
			}
			$page = substr($page,0,-1).'.html';
		break;
		
		case "bbs_travel_companion.php":	//结伴同游bbs主页面
		case "bbs_travel_companion_rightindex.php": //结伴同游右页面列表
		case "bbs_travel_companion_content.php":	//结伴同游帖子详细内容
			$no_osCsid = true;
			if($page=="bbs_travel_companion.php"){
				$page = "bbs_travel_companion/";
			}
			if($page=="bbs_travel_companion_rightindex.php"){
				$page = "bbs_travel_companion_rightindex/";
			}
			if($page=="bbs_travel_companion_content.php"){
				$page = "bbs/";
			}
			
			if(preg_match('/TcPath=([0-9_]+)/', $parameters, $m)) {
				$parameters = preg_replace('/TcPath=([0-9_]+)&?/', '', $parameters);
				$page .= "tp_".$m[1]."/";
			}
			if(preg_match('/products_id=([0-9]+)/', $parameters, $m)) {
				$parameters = preg_replace('/products_id=([0-9]+)&?/', '', $parameters);
				$page .= "pid_".$m[1]."/";
			}

			if(preg_match('/page=([0-9]+)/', $parameters, $m)) {
				$parameters = preg_replace('/page=([0-9]+)&?/', '', $parameters);
				$page .= "page_".$m[1]."/";
			}
			if(preg_match('/sort_name=(\S+[^\/&?])/', $parameters, $m)) {
				$parameters = preg_replace('/sort_name=(\S+[^\/&?])&?/', '', $parameters);
				$page .= "sort_name_".$m[1]."/";
			}
			if(preg_match('/customers_id=([0-9]+)/', $parameters, $m)) {
				$parameters = preg_replace('/customers_id=([0-9]+)&?/', '', $parameters);
				$page .= "cus_".$m[1]."/";
			}
			if(preg_match('/t_companion_id=([0-9]+)/', $parameters, $m)) {
				$parameters = preg_replace('/t_companion_id=([0-9]+)&?/', '', $parameters);
				$page .= "tid_".$m[1]."/";
			}
			
			

			if($page=="bbs_travel_companion/"){
				$page = 'bbs_travel_companion.html';
			}
			if($page=="bbs_travel_companion_rightindex/"){
				$page = 'bbs_travel_companion_rightindex.html';
			}
			if($page=="bbs/"){
				$page = 'bbs_travel_companion_content.html';
			}

		break;
		
		case 'all_question_answers.php':	//客户咨询页
			$page = 'all_question_answers/';
			if(preg_match('/page=([0-9]+)/', $parameters, $m)) {
				$parameters = preg_replace('/page=([0-9]+)&?/', '', $parameters);
				$page .= "page_".$m[1]."/";
			}
			if(preg_match('/tabid=([0-9]+)/', $parameters, $m)) {
				$parameters = preg_replace('/tabid=([0-9]+)&?/', '', $parameters);
				$page .= "tabid_".$m[1]."/";
			}
		break;
		
		case 'tours-experts.php':	//走四方专家
			if(!preg_match('/\-/',$parameters)){
				$page = 'tours-experts/';
				parse_str($parameters, $parameters_array);
				unset($parameters_array['tours'],$parameters_array['language'],$parameters_array['osCsid']);
				
				if(isset($parameters_array['uid']) && $parameters_array['uid']!=''){
					$page .= 'uid-'.$parameters_array['uid'].'-';
					unset($parameters_array['uid']);
				}
				if(isset($parameters_array['mod']) && $parameters_array['mod']!=''){
					$page .= 'mod-'.$parameters_array['mod'].'-';
					unset($parameters_array['mod']);
				}
				if(isset($parameters_array['action']) && $parameters_array['action']!=''){
					$page .= 'action-'.$parameters_array['action'].'-';
					unset($parameters_array['action']);
				}
				
				
				foreach($parameters_array as $pat_k=>$pat_v){
					if($pat_k!='' && $pat_v!=''){ $page .= $pat_k.'-'.$pat_v.'-';}
				}
				if($page!='tours-experts/'){
					$page = substr($page,0,-1).'.html';
					$parameters='';
					unset($parameters_array);
				}
			}
		break;
		
		case "create_site_map.php":	//网站地图 xml版
			$page = "sitemap.xml";
		break;
		case "sitemap.php":	//网站地图html版
			$page = "sitemap.html";
		break;
		case "reviews.php":	//评价回访
			$page = "previews/";
			if(preg_match('/page=([0-9]+)&?/', $parameters, $m)) {
				$parameters = preg_replace('/page=([0-9]+)&?/', '', $parameters);
				$page .= 'page-'.$m[1].'-';
			}
			$page = substr($page,0,-1).'.html';
		break;
		case 'magazine.php':	//《途E游》
			  $page = 'magazine/';
			  parse_str($parameters, $parameters_array);
			  unset($parameters_array['osCsid']);
			  
			  if(isset($parameters_array['No']) && $parameters_array['No']!=''){
				  $page .= 'No-'.$parameters_array['No'].'/';
				  unset($parameters_array['No']);
			  }
			  $page .= '?';
			  foreach($parameters_array as $pat_k=>$pat_v){
				  $page .= $pat_k;
				  $pat_v && $page .= '='.$pat_v.'&';
			  }
			  if(substr($page, -1) == '?' || substr($page, -1) == '&'){
				  $page = substr($page,0,-1);
			  }
			  $parameters='';
			  unset($parameters_array);
		break;
		case 'Ebook.php':	//《出行指南》
			  $page = 'Ebook/';
			  parse_str($parameters, $parameters_array);
			  unset($parameters_array['osCsid']);
			  
			  if(isset($parameters_array['No']) && $parameters_array['No']!=''){
				  $page .= 'No-'.$parameters_array['No'].'/';
				  unset($parameters_array['No']);
			  }
			  $page .= '?';
			  foreach($parameters_array as $pat_k=>$pat_v){
				  $page .= $pat_k;
				  $pat_v && $page .= '='.$pat_v.'&';
			  }
			  if(substr($page, -1) == '?' || substr($page, -1) == '&'){
				  $page = substr($page,0,-1);
			  }
			  $parameters='';
			  unset($parameters_array);
		break;
		case 'visa.php': //签证
			$page = 'qianzheng/';
			//$parameters='';
		break;
		case 'question_detail.php'://提问详细
			$page='question_detail-'.$parameters.'.html';
			$parameters='';
			break;
		case 'raiders_list.php': //攻略列表
			if(!@class_exists(Raiders))
				require('admin/includes/classes/Raiders.class.php');
			$page=Raiders::outUrl('raiders_list.php',$parameters);
			$parameters='';
			break;
		case 'raiders_info.php': //攻略详细
			if(!@class_exists(Raiders))
				require('admin/includes/classes/Raiders.class.php');
			$page=Raiders::outUrl('raiders_info.php',$parameters);
			$parameters='';
			break;
	}


	$parameters = preg_replace('/&+/','&',$parameters);
	
	if (tep_not_null($parameters)) {
	  $link .= $page . '?' . tep_output_string($parameters);
	  $separator = '&';
    } else {
		
			 if($page == FILENAME_DEFAULT) {
			 $page = '';
			 }
			 
	  $link .= $page;
      $separator = '?';
    }

	
	
    while ( (substr($link, -1) == '&') || (substr($link, -1) == '?') ) $link = substr($link, 0, -1);

// Add the session ID when moving from different HTTP and HTTPS servers, or when SID is defined
    if ( ($add_session_id == true) && ($session_started == true) && (SESSION_FORCE_COOKIE_USE == 'False') ) {
      if (tep_not_null($SID)) {
        $_sid = $SID;
      } elseif ( ( ($request_type == 'NONSSL') && ($connection == 'SSL') && (ENABLE_SSL == true) ) || ( ($request_type == 'SSL') && ($connection == 'NONSSL') ) ) {
        if (HTTP_COOKIE_DOMAIN != HTTPS_COOKIE_DOMAIN) {
          $_sid = tep_session_name() . '=' . tep_session_id();
        }
      }
    }

    if ( ((SEARCH_ENGINE_FRIENDLY_URLS == 'true') && ($search_engine_safe == true)) || $inside_search == 'true'  ) {
      while (strstr($link, '&&')) $link = str_replace('&&', '&', $link);

      $link = str_replace('?', '/', $link);
      $link = str_replace('&', '/', $link);
      $link = str_replace('=', '/', $link);

      $separator = '?';
    }

    if (isset($_sid) && $no_osCsid ==false) {
		if(strpos($link, '?')!==false){
			$separator = '&';
		}
      $link .= $separator . $_sid;
    }

    return $link;
  }

///
//SEOurls - 目的地指南目录url地址
function seo_get_name_for_dg_categories_name_en($dg_categories_id){
    $res = tep_db_query("select dg_categories_name_en from `destination_guide_categories` where dg_categories_id = '" . (int)$dg_categories_id. "'");
    $row = tep_db_fetch_array($res);
    return $row['dg_categories_name_en'];
}

//// 

 // SEOurls - function to create category path from cPath
  function seo_get_path_from_cpath($cPath) {
    $ret = '';
    $cPath_array = explode('_', $cPath);
    $category_id = $cPath_array[count($cPath_array)-1];
    /*
    $res = tep_db_query("select c1.categories_urlname from " . TABLE_CATEGORIES . " c1 where c1.categories_id = '" . (int)$category_id. "'");
    $cat_array = tep_db_fetch_array($res);*/
    $cat_array = MCache::fetch_categories((int)$category_id , 'categories_urlname');
    
    return $cat_array['categories_urlname'];
  }
      
////

// SEOurls - new funtion to get category path for a given product
// WARNING: works only for products 3 levels deep in category hierarchy
 
  function seo_get_products_path($products_id, $full = false, $tablink = '') { // $full == true => include product

    $ret = '';

    

    if($full) {

      $res = tep_db_query("select p.products_urlname from " . TABLE_PRODUCTS . " p where p.products_id = '" . (int)$products_id . "'");

      $cat_array = tep_db_fetch_array($res);
		if($tablink != ''){
     //return $cat_array['products_urlname'] .$tablink.SEO_EXTENSION;
			return $cat_array['products_urlname'] .$tablink;
	   }else{
	     return $cat_array['products_urlname'] . SEO_EXTENSION;
	   }

    } else {

      return '';

    }



  } 


function seo_get_article_seo_path($articles_id,$url_prifix,$add_ext='') {
    $ret = '';
  
    $res = tep_db_query("select p.articles_seo_url,t.topics_id from " . TABLE_ARTICLES . " p, ".TABLE_ARTICLES_TO_TOPICS." t where p.articles_id=t.articles_id and p.articles_id = '" . (int)$articles_id . "'");
    $cat_array = tep_db_fetch_array($res);
	$current_topic_id = $cat_array['topics_id'];

	$topic_path  = seo_get_topic_url($current_topic_id);
    return $url_prifix.$topic_path.$cat_array['articles_seo_url'].$add_ext;
  }


//function to get articles info page url with topic names

function seo_get_topic_url($current_topic_id)
{
$catname1 = tep_output_generated_topic_path($current_topic_id);
				if($catname1=='Top')
				{
					$catname_url = '';	
				}
				else
				{
					$catname = str_replace('&nbsp;&gt;&nbsp;','/',strtolower($catname1));
					$catname_url = str_replace(' ','-',$catname).'/';
				}
				
		return $catname_url;
}

function tep_output_generated_topic_path($id, $from = 'topic') {
    $calculated_topic_path_string = '';
    $calculated_topic_path = tep_generate_topic_path($id, $from);
    for ($i=0, $n=sizeof($calculated_topic_path); $i<$n; $i++) {
      for ($j=0, $k=sizeof($calculated_topic_path[$i]); $j<$k; $j++) {
        $calculated_topic_path_string .= $calculated_topic_path[$i][$j]['text'] . '&nbsp;&gt;&nbsp;';
      }
      $calculated_topic_path_string = substr($calculated_topic_path_string, 0, -16) . '<br>';
    }
    $calculated_topic_path_string = substr($calculated_topic_path_string, 0, -4);

    if (strlen($calculated_topic_path_string) < 1) $calculated_topic_path_string = 'Top';

    return $calculated_topic_path_string;
  }
  
    function tep_generate_topic_path($id, $from = 'topic', $topics_array = '', $index = 0) {
    global $languages_id;

    if (!is_array($topics_array)) $topics_array = array();

    if ($from == 'article') {
      $topics_query = tep_db_query("select topics_id from " . TABLE_ARTICLES_TO_TOPICS . " where articles_id = '" . (int)$id . "'");
      while ($topics = tep_db_fetch_array($topics_query)) {
        if ($topics['topics_id'] == '0') {
          $topics_array[$index][] = array('id' => '0', 'text' => TEXT_TOP);
        } else {
          $topic_query = tep_db_query("select cd.topics_name, c.parent_id from " . TABLE_TOPICS . " c, " . TABLE_TOPICS_DESCRIPTION . " cd where c.topics_id = '" . (int)$topics['topics_id'] . "' and c.topics_id = cd.topics_id and cd.language_id = '" . (int)$languages_id . "'");
          $topic = tep_db_fetch_array($topic_query);
          $topics_array[$index][] = array('id' => $topics['topics_id'], 'text' => $topic['topics_name']);
          if ( (tep_not_null($topic['parent_id'])) && ($topic['parent_id'] != '0') ) $topics_array = tep_generate_topic_path($topic['parent_id'], 'topic', $topics_array, $index);
          $topics_array[$index] = array_reverse($topics_array[$index]);
        }
        $index++;
      }
    } elseif ($from == 'topic') {
      $topic_query = tep_db_query("select cd.topics_name, c.parent_id from " . TABLE_TOPICS . " c, " . TABLE_TOPICS_DESCRIPTION . " cd where c.topics_id = '" . (int)$id . "' and c.topics_id = cd.topics_id and cd.language_id = '" . (int)$languages_id . "'");
      $topic = tep_db_fetch_array($topic_query);
      $topics_array[$index][] = array('id' => $id, 'text' => $topic['topics_name']);
      if ( (tep_not_null($topic['parent_id'])) && ($topic['parent_id'] != '0') ) $topics_array = tep_generate_topic_path($topic['parent_id'], 'topic', $topics_array, $index);
    }

    return $topics_array;
  }


////

// The HTML image wrapper function
  function tep_image($src, $alt = '', $width = '', $height = '', $parameters = '') {
	if ( (empty($src) || ($src == DIR_WS_IMAGES)) && (IMAGE_REQUIRED == 'false') ) {
      return false;
    }

// alt is added to the img tag even if it is null to prevent browsers from outputting
// the image filename as default
    $image = '<img src="' . tep_output_string($src) . '" border="0" alt="' . tep_output_string($alt) . '"';

    if (tep_not_null($alt)) {
      $image .= ' title=" ' . tep_output_string($alt) . ' "';
    }

    /*if ( (CONFIG_CALCULATE_IMAGE_SIZE == 'true') && (empty($width) || empty($height)) ) {
      if ($image_size = @getimagesize($src)) {
        if (empty($width) && tep_not_null($height)) {
          $ratio = $height / $image_size[1];
          $width = $image_size[0] * $ratio;
        } elseif (tep_not_null($width) && empty($height)) {
          $ratio = $width / $image_size[0];
          $height = $image_size[1] * $ratio;
        } elseif (empty($width) && empty($height)) {
          $width = $image_size[0];
          $height = $image_size[1];
        }
      } elseif (IMAGE_REQUIRED == 'false') {
        return false;
      }
    }
	*/

    if (tep_not_null($width) && tep_not_null($height)) {
      //$image .= ' width="' . tep_output_string($width) . '" height="' . tep_output_string($height) . '"';
	  $image .= getimgHW3hw($src,$width,$height);
    }

    if (tep_not_null($parameters)) $image .= ' ' . $parameters;

    $image .= ' />';

    return $image;
  }

////
// The HTML form submit button wrapper function
// Outputs a button in the selected language
  function tep_image_submit($image, $alt = '', $parameters = '') {
    global $language;
// BOM Mod: force all buttons to come from the tempalte folders
//  $image_submit = '<input type="image" src="' . tep_output_string(DIR_WS_LANGUAGES . $language . '/images/buttons/' . $image) . '" border="0" alt="' . tep_output_string($alt) . '"';
    $image_submit = '<input type="image" src="' . tep_output_string(DIR_WS_TEMPLATE_IMAGES.'buttons/' . $language . '/' .  $image) . '" style="border:0px;" alt="' . tep_output_string($alt) . '"';
// EOM
    if (tep_not_null($alt)) $image_submit .= ' title=" ' . tep_output_string($alt) . ' "';

    if (tep_not_null($parameters)){
		if(preg_match('/style=/',$parameters)){
			$image_submit = str_replace('style="border:0px;"','',$image_submit);
		}
		$image_submit .= ' ' . $parameters;
	}

    $image_submit .= ' />';

    return $image_submit;
  }
  
  function tep_image_reset($image, $width, $height, $alt = '', $parameters = '') {
    global $language;
    $image_submit = '<input value=" " type="reset" style="width:'.$width.'px; height:'.$height.'px; border:0px; background-image: url(/'.tep_output_string(DIR_WS_TEMPLATE_IMAGES.'buttons/' . $language . '/' .  $image).'); background-repeat: no-repeat; border-top-style: none; border-right-style: none; border-bottom-style: none; border-left-style: none; cursor: pointer;" alt="' . tep_output_string($alt) . '"';
// EOM
    if (tep_not_null($alt)) $image_submit .= ' title=" ' . tep_output_string($alt) . ' "';

    if (tep_not_null($parameters)) $image_submit .= ' ' . $parameters;

    $image_submit .= ' />';

    return $image_submit;
  }

////
// Output a function button in the selected language
  function tep_image_button($image, $alt = '', $parameters = '') {
    global $language;
// BOM Mod: force all buttons to come from the tempalte folders
//    return tep_image(DIR_WS_LANGUAGES . $language . '/images/buttons/' . $image, $alt, '', '', $parameters);
     return tep_image(DIR_WS_TEMPLATE_IMAGES.'buttons/' . $language . '/' .  $image, $alt, '', '', $parameters);
// EOM
  }

////
// Output a separator either through whitespace, or with an image
  function tep_draw_separator($image = 'pixel_black.gif', $width = '100%', $height = '1') {
	//return tep_image(DIR_WS_IMAGES . $image, '', $width, $height);
	return tep_image(DIR_WS_TEMPLATE_IMAGES. $image, '', $width, $height);
  }

////
// Output a form
  function tep_draw_form($name, $action, $method = 'post', $parameters = '') {
    $form = '<form name="' . tep_output_string($name) . '" action="' . tep_output_string($action) . '" method="' . tep_output_string($method) . '"';

    if (tep_not_null($parameters)) $form .= ' ' . $parameters;

    $form .= '>';

    return $form;
  }
  
function tep_draw_form_close($formname = ''){
	return '</form>';
}

////
// Output a form input field
  function tep_draw_input_field($name, $value = '', $parameters = '', $type = 'text', $reinsert_value = true,$enterkey=false) {
    global $language;
	$atuoTextFun = '';
	if($language=='schinese' || strtolower(CHARSET)=='gb2312'){
		$atuoTextFun = 'simplized';
	}else{
		$atuoTextFun = 'traditionalized';
	}
	//vincent 2011-5-12 修改正则支持 onBlur和onblur
	$onBlur = 'onBlur="this.value = '.$atuoTextFun.'(this.value);"';
	if(preg_match('/onBlur="/i',$parameters)){
		$onBlurExp = preg_replace('/.*onBlur="(.*)"[;]*/i','$1"',$parameters);
		$parameters = preg_replace('/onBlur="(.*)"/i','',$parameters);
		//echo $onBlurExp;
		$onBlur = preg_replace('/"$/',' ',$onBlur). $onBlurExp;
		//echo $onBlur;
	}
	$nameStr =$name == ''?'':' name="'.tep_output_string($name).'"';//vincent add 
	$field = '<input '.$onBlur.' type="' . tep_output_string($type) . '"'.$nameStr;
	if($enterkey){
		$field .= ' onkeydown="return jQuery_Enter(event,this,\''.$atuoTextFun.'\');"';
	}

    if ( (isset($GLOBALS[$name])) && ($reinsert_value == true) ) {
      $field .= ' value="' . tep_output_string(stripslashes($GLOBALS[$name])) . '"';
    } elseif (tep_not_null($value)) {
      $field .= ' value="' . tep_output_string(stripslashes($value)) . '"';
    }

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= ' />';

    return $field;
  }

//输出数字或英文的输入框,即关闭输入法
function tep_draw_input_num_en_field($name, $value='', $parameters=''){
    if(preg_match('/style="([^"]+)"/',$parameters,$m)){
		$parameters = preg_replace('/style="([^"]+)"/','', $parameters);
	}
	$parameters .= ' style="ime-mode: disabled; '.$m[1].'" ';
	return tep_draw_input_field($name, $value, $parameters, 'text', true);
}

////
// Output a form password field
  function tep_draw_password_field($name, $value = '', $parameters = 'maxlength="40"') {
    return tep_draw_input_field($name, $value, $parameters, 'password', false);
  }

////
// Output a selection field - alias function for tep_draw_checkbox_field() and tep_draw_radio_field()
  function tep_draw_selection_field($name, $type, $value = '', $checked = false, $parameters = '') {
    $selection = '<input type="' . tep_output_string($type) . '" name="' . tep_output_string($name) . '"';

    if (tep_not_null($value)) $selection .= ' value="' . tep_output_string($value) . '"';

    if ( ($checked == true) || ( isset($GLOBALS[$name]) && is_string($GLOBALS[$name]) && ( ($GLOBALS[$name] == 'on') || (isset($value) && (stripslashes($GLOBALS[$name]) == $value)) ) ) ) {
      $selection .= ' checked="checked"';
    }

    if (tep_not_null($parameters)) $selection .= ' ' . $parameters;

    $selection .= ' />';

    return $selection;
  }

////
// Output a form checkbox field
  function tep_draw_checkbox_field($name, $value = '', $checked = false, $parameters = '') {
    return tep_draw_selection_field($name, 'checkbox', $value, $checked, $parameters);
  }

////
// Output a form radio field
  function tep_draw_radio_field($name, $value = '', $checked = false, $parameters = '') {
	$name_v = $_GET[$name];
	if(isset($_POST[$name])){ $name_v = $_POST[$name]; }
	if($name_v==$value && tep_not_null($value)){
		$checked = true;
	}
	return tep_draw_selection_field($name, 'radio', $value, $checked, $parameters);
  }
  /**
   * 返回input输入框需要执行的内码转换函数
   * */
  function tep_input_js_convert_function(){
	   global $language;
		if($language=='schinese' || strtolower(CHARSET)=='gb2312'){
			$onBlur = 'this.value = simplized(this.value);';
		}else{
			$onBlur = 'this.value = traditionalized(this.value);';
		}
		return $onBlur ;
  }

////
// Output a form textarea field

 
  
  /**
   * 输出一个多行文框
   * 
   * @param string $name 多行文本框的名称
   * @param string $wrap 
   * @param string $width 宽度
   * @param string $height 高度
   * */
  function tep_draw_textarea_field($name, $wrap, $width, $height, $text = '', $parameters = '', $reinsert_value = true) {
    global $language;
	if($language=='schinese' || strtolower(CHARSET)=='gb2312'){
		$onBlur = 'onBlur="this.value = simplized(this.value);"';
	}else{
		$onBlur = 'onBlur="this.value = traditionalized(this.value);"';
	}
	
	if(preg_match('/onBlur="/i',$parameters)){
		$onBlurExp = preg_replace('/.*onBlur="(.*)"[;]*/i','$1"',$parameters);
		$parameters = preg_replace('/onBlur="(.*)"/i','',$parameters);
		//echo $onBlurExp;
		$onBlur = preg_replace('/"$/',' ',$onBlur). $onBlurExp;
		//echo $onBlur;
	}

	$field = '<textarea '.$onBlur.' name="' . tep_output_string($name) . '"  cols="' . tep_output_string($width) . '" rows="' . tep_output_string($height) . '"'; 
	if(tep_not_null($wrap)){
		$field .= ' wrap="' . tep_output_string($wrap) . '" ';
	}
	


    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    if ( (isset($GLOBALS[$name])) && ($reinsert_value == true) ) {
      $field .= stripslashes($GLOBALS[$name]);
    } elseif (tep_not_null($text)) {
      $field .= $text;
    }

    $field .= '</textarea>';

    return $field;
  }

////
// Output a form hidden field
  function tep_draw_hidden_field($name, $value = '', $parameters = '') {
    $field = '<input type="hidden" name="' . tep_output_string($name) . '"';

    if (tep_not_null($value)) {
      $field .= ' value="' . tep_output_string($value) . '"';
    } elseif (isset($GLOBALS[$name])) {
      $field .= ' value="' . tep_output_string(stripslashes($GLOBALS[$name])) . '"';
    }

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= ' />';

    return $field;
  }

////
// Hide form elements
  function tep_hide_session_id() {
    global $session_started, $SID;

    if (($session_started == true) && tep_not_null($SID)) {
      return tep_draw_hidden_field(tep_session_name(), tep_session_id());
    }
  }

  /**
   * 生成下拉选择框，注意数组中的ID不能有重复，如果有，则需要调另一个  tep_draw_pull_down_menu
   * tep_simple_drop_down('下拉名称',array('id'=>'显示的文字'[[,'id'=>'显示的文字'][,...]]),'显示的文字[设置默认选中的]','下拉其他参数','是否显示必填提示');
   * eg:
   *     tep_simple_drop_down('selectName',array('0'=>'请选择','1'=>'选择一','2'=>'选择二'),'选择一');
   *     输出 ：<select name='selectName'>
   *               <option value="0">请选择</option>
   *               <option value="1" selected>选择一</option>
   *               <option value="2">选择二</option>
   *           </select>
   * @param string $name 下拉名称
   * @param array $values 下拉选项值
   * @param string $default 默认选中的值 [传 显示的文字内容]
   * @param string $parameters 下拉的其他参数 如 id,style,onchange
   * @param boolean $required 是否显示一个表示必填的星号
   */
  function tep_simple_drop_down($name, $values, $default = '', $parameters = '', $required = false){
  	$arr = array();
  	foreach ((array)$values as $key => $val){
  		$arr[] = array('id' => $key, 'text' => $val);
  	}
  	return tep_draw_pull_down_menu($name, $arr,$default,$parameters,$required);
  }
  
  
////
// Output a form pull down menu
  function tep_draw_pull_down_menu($name, $values, $default = '', $parameters = '', $required = false) {
    $field = '<select name="' . tep_output_string($name) . '"';

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    if (empty($default) && isset($GLOBALS[$name])) $default = stripslashes($GLOBALS[$name]);

    for ($i=0, $n=sizeof($values); $i<$n; $i++) {
      $field .= '<option value="' . tep_output_string($values[$i]['id']) . '"';
      if ($default == $values[$i]['id']) {
        $field .= ' selected="selected"';
      }

      $field .= '>' . tep_output_string($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>';
    }
    $field .= '</select>';

    if ($required == true) $field .= TEXT_FIELD_REQUIRED;

    return $field;
  }

////
// Creates a pull-down list of countries
  function tep_get_country_list($name, $selected = '', $parameters = '') {
  global $country;
  /*
  if(!tep_not_null($selected)){
  	if(!(int)$country){
		$selected="44";	//China
	}else{
		$selected = $country;
	}
  }*/
  
  $countries_selected = tep_db_query("select countries_id, countries_name from " . TABLE_COUNTRIES . " where countries_iso_code_2 = 'US' || countries_iso_code_2 = 'CN' || countries_iso_code_2 = 'CA' || countries_iso_code_2 = 'HK' || countries_iso_code_2 = 'TW' order by countries_name ASC");
  $countries_array = array();
  
  if(!(int)$country){
  	$countries_array[] = array('id' => '', 'text' => PULL_DOWN_DEFAULT);
  }
  while($countries_selected_values = tep_db_fetch_array($countries_selected)){
  	$countries_array[] = array('id' => $countries_selected_values['countries_id'],
							   'text' => $countries_selected_values['countries_name']);
  }
    /*$countries_array = array(array('id' => $countries_selected_values['countries_id'],
							   'text' => $countries_selected_values['countries_name']));
	*/  
    $countries_array[] = array('id' => '0',
							   'text' => '');
	  
	$countries = tep_get_countries();

    for ($i=0, $n=sizeof($countries); $i<$n; $i++) {
      $countries_array[] = array('id' => $countries[$i]['countries_id'], 'text' => $countries[$i]['countries_name']);
    }

    return tep_draw_pull_down_menu($name, $countries_array, $selected, $parameters);
  }

// howard added 取得居中弹出层
function tep_popup($popupTip, $popupConCompare, $width = "490", $h4 = "", $contents ){	
	$html = '
	<!--<a href="javascript:;" onclick="showPopup(&quot;'.$popupTip.'&quot;,&quot;'.$popupConCompare.'&quot;,1);">Open</a>-->
	<div class="popup" id="'.$popupTip.'">
	  <table width="100%" cellpadding="0" cellspacing="0" border="0" class="popupTable">
		<tr>
		  <td class="topLeft"></td><td class="side"></td><td class="topRight"></td></tr><tr><td  class="side"></td>
			<td class="con">
			  <div class="popupCon" id="'.$popupConCompare.'" style="width:'.$width.'px; ">
			 	 <div class="popupConTop">
				  <h3><b>'.$h4.'</b></h3><span><a href="javascript:closePopup(&quot;'.$popupTip.'&quot;)"><img src="'.DIR_WS_TEMPLATE_IMAGES.'popup/icon_x.gif" /></a></span>
				</div>
				'.$contents.'
			  </div>
		  </td><td class="side"></td></tr><tr><td class="botLeft"></td><td class="side"></td><td class="botRight"></td></tr>
	  </table>
	</div>
	';
	return $html;
}
/**
 * 取得居中弹出层 没有头部按钮需要自己增加确定按钮
 * @author vincent
 */
function tep_popup_alert($popupTip, $popupConCompare, $width = "490", $h4 = "", $contents ){
	$html = '<div class="popup" id="'.$popupTip.'">
	  <table width="100%" cellpadding="0" cellspacing="0" border="0" class="popupTable">
		<tr>
		  <td class="topLeft"></td><td class="side"></td><td class="topRight"></td></tr><tr><td  class="side"></td>
			<td class="con">
			  <div class="popupCon" id="'.$popupConCompare.'" style="width:'.$width.'px; ">			 	
				'.$contents.'
			  </div>
		  </td><td class="side"></td></tr><tr><td class="botLeft"></td><td class="side"></td><td class="botRight"></td></tr>
	  </table>
	</div>
	';
	return $html;
}


//干掉了头部标题 vincent
function tep_popup_notop($popupTip, $popupConCompare, $width = "490", $h4 = "", $contents ){
	$html = '
	<!--<a href="javascript:;" onclick="showPopup(&quot;'.$popupTip.'&quot;,&quot;'.$popupConCompare.'&quot;,1);">Open</a>-->
	<div class="popup" id="'.$popupTip.'">
	  <table width="100%" cellpadding="0" cellspacing="0" border="0" class="popupTable">
		<tr>
		  <td class="topLeft"></td><td class="side"></td><td class="topRight"></td></tr><tr><td  class="side"></td>
			<td class="con">
			  <div class="popupCon" id="'.$popupConCompare.'" style="width:'.$width.'px; ">
				'.$contents.'
			  </div>
		  </td><td class="side"></td></tr><tr><td class="botLeft"></td><td class="side"></td><td class="botRight"></td></tr>
	  </table>
	</div>
	';
	return $html;
}

//自动检测$texts中是否有与走四方网相关的网址，如果有则加超级连接
function auto_add_tff_links($texts,$target="_blank", $class=""){
				
	$pet = '/(http:\/\/)*((www|cn)+\.usitrip\.com[\w\/\?\&\.\=%\-]*)/';
	$texts = trim(tep_db_output($texts));
	$texts = preg_replace($pet,'<a target="'.$target.'" class="'.$class.'" href="http://$2">$1$2</a>',$texts);
	return $texts;
}

//取得带星期几的日期
function convert_datetime($str) {
	list($date, $time) = explode(' ', $str);
	list($year, $month, $day) = explode('-', $date);
	list($hour, $minute, $second) = explode(':', $time);

	$timestamp = @mktime(0, 0, 0, $month, $day, $year);

	return @strftime("%A",$timestamp); //date("l",$timestamp);
}

//SEO 301重定向函数
function moved_permanently_301($link){
	global $error404, $_POST;
	if(!isset($error404) && !$_POST){
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: ".$link."");
		exit;
	}
}
/*
 *日期选择控件，剔除无效的日期
 */
function tep_draw_date_input_adv($varname , $default , $startYear = 1950 , $endYear = '',$yearPos = '1970'){
	if(!defined('TEP_DRAW_DATE_INPUT_ADV')){
		$html.= '<script type="text/javascript">';
		$html.= 'function ddia_update(src,pos){
			if(pos == 1){y = jQuery(src);m = y.next();d = m.next();}
			else if(pos == 2){m = jQuery(src);y = m.prev();d = m.next();}
			else if(pos == 3){d = jQuery(src);m= d.prev();y= m.prev();}
			else alert("data input error");
			var oldday = d.val();
			if(pos < 3){
				var maxd = new Date(y.val(),m.val(),0).getDate();
				var html = "<option value=\\"0\\">选择日</option>" ;
				for(i=1;i<=maxd;i++){
					html+= "<option value=\\""+(i<10?"0"+i:i)+"\\" "+(oldday == i? " selected ":"")+">"+i+"日</option>";
				}
				d.html(html);
			}
			d.next().val(y.val()+"-"+m.val()+"-"+d.val());
		}';
		$html.='</script>';
		define('TEP_DRAW_DATE_INPUT_ADV' ,true);
	}

	if($endYear == '') $endYear = intval(date('Y',time()));
  	$rawDefault = $default;	
  	$rawDefault = str_replace(array('/','-',':'), ' ', $rawDefault);
  	$defaultArr = explode(" ",$rawDefault);	
  	$pyear = intval($defaultArr[0]);
  	if(strlen($pyear) !=4){
  		$year = intval($defaultArr[2]);$month = intval($defaultArr[0]);$day = intval($defaultArr[1]);
  	}else{
  		$year = intval($defaultArr[0]);$month = intval($defaultArr[1]);$day = intval($defaultArr[2]);
  	} 
	
	$html.='<select onchange="ddia_update(this,1)">';
	for($i = $startYear ; $i<= $endYear;$i++){
		if($yearPos == $i){
			$html.= '<option value="0000" '.(0 == $year? ' selected ':'').' >选择年</option>';
		}
		$html.= '<option value="'.$i.'" '.($i == $year? 'selected':'').' >'.$i."年</option>";
  	}
	$html.='</select><select onchange="ddia_update(this,2)" >';

	if($month == 0 ) $html.= '<option value="0" selected >选择月</option>';
	else  $html.= '<option value="0" >选择月</option>';
	
	for($i = 1 ; $i<= 12;$i++){
  		$istr = str_pad($i, 2,'0',STR_PAD_LEFT );  		
  		$html.= '<option value="'.$istr.'" '.($i == $month?'selected':'').'  >'.$i."月</option>";
  	}
	$html.='</select><select id="ddiad"  onchange="ddia_update(this,3)">';
	if($day == 0 )$html.= '<option value="0" selected >选择日</option>';
	else $html.= '<option value="0"  >选择日</option>';
	$maxday = date('t',strtotime($year.'-'.$month.'-'.$day)); 
	for($i = 1 ; $i<= $maxday;$i++){
		$istr = str_pad($i, 2,'0',STR_PAD_LEFT);  		
		$html.= '<option value="'.$istr.'" '.($i == $day?'selected':'').' >'.$i."日</option>";
	}
	$html.='</select><input type="hidden" name="'.$varname.'" value="'.$year.'-'.$month.'-'.$day.'">';
	echo $html;
}
/**
 * 日期输入框
 * @param string $varname
 * @param string $default
 * @param int $startYear 起始年份默认1950
 * @param int $endYear 结束年份 默认为到当前年份
 * @author vincent
 * @modify by vincent at 2011-5-12 上午11:08:27
 */
function tep_draw_date_input($varname  ,$default , $startYear = 1950 , $endYear = ''){
  	if($endYear == '') $endYear = intval(date('Y',time()));
  	$rawDefault = $default;
  	$rawDefault = str_replace(array('/','-',':'), ' ', $rawDefault);
  	$defaultArr = explode(" ",$rawDefault);
  	$pyear = intval($defaultArr[0]);
  	if(strlen($pyear) !=4){
  		$year = intval($defaultArr[2]);$month = intval($defaultArr[0]);$day = intval($defaultArr[1]);
  	}else{
  		$year = intval($defaultArr[0]);$month = intval($defaultArr[1]);$day = intval($defaultArr[2]);
  	}  	
  	
  	$html = '';
  	$html.='<select  id="'.$varname.'_year" onchange="document.getElementById(\''.$varname.'\').value = document.getElementById(\''.$varname.'_year\').value+\'-\'+document.getElementById(\''.$varname.'_month\').value+\'-\'+document.getElementById(\''.$varname.'_day\').value;">' ;
	if($year == 0)$html.= '<option value="0000" selected >选择年</option>';
  	else $html.= '';
  	for($i = $startYear ; $i<= $endYear;$i++){
  		if($i == $year){
  			$html.= '<option value="'.$i.'" selected >'.$i."年</option>";
  		}else $html.= '<option value="'.$i.'">'.$i."年</option>";
  	}
  $html.='</select>';
  $html.='<select id="'.$varname.'_month"  onchange="document.getElementById(\''.$varname.'\').value = document.getElementById(\''.$varname.'_year\').value+\'-\'+document.getElementById(\''.$varname.'_month\').value+\'-\'+document.getElementById(\''.$varname.'_day\').value;">';

  if($month == 0 ) $html.= '<option value="0" selected >选择月</option>';
  else  $html.= '<option value="0" >选择月</option>';
  for($i = 1 ; $i<= 12;$i++){
  		$istr = str_pad($i, 2,'0',STR_PAD_LEFT );  		
  		if($i == $month){
  			$html.= '<option value="'.$istr.'" selected >'.$i."月</option>";
  		}else $html.= '<option value="'.$istr.'">'.$i."月</option>";
  	}
  $html.='</select>';
  $html.='<select id="'.$varname.'_day"  onchange="document.getElementById(\''.$varname.'\').value = document.getElementById(\''.$varname.'_year\').value+\'-\'+document.getElementById(\''.$varname.'_month\').value+\'-\'+document.getElementById(\''.$varname.'_day\').value;">';
  if($day == 0 )$html.= '<option value="0" selected >选择日</option>';
  else $html.= '<option value="0"  >选择日</option>';
  for($i = 1 ; $i<= 31;$i++){
  		$istr = str_pad($i, 2,'0',STR_PAD_LEFT);  		
  		if($i == $day){
  			$html.= '<option value="'.$istr.'" selected >'.$i."日</option>";
  		}else
  			$html.= '<option value="'.$istr.'">'.$i."日</option>";
  	}
  	
  	$html .= '</select><input type="hidden" id="'.$varname.'" name="'.$varname.'" value="'.$year.'-'.$month.'-'.$day.'">';
  	return $html;
  }
  /**
   * 输出国家 省 地区框
   * @param unknown_type $refobj 要写入的元素名字
   * @param unknown_type $country 国家ID
   * @param unknown_type $state 省名字
   * @param unknown_type $city 城市名字
   * @param boolean $allowUserInput 是否允许在没有下层数据的情况下让用户自行输入内容
   * @author vincent
   * @modify by vincent at 2011-5-12 下午05:35:38
   */
  function tep_draw_full_address_input($refobj ,$country = '',$state='',$city='',$allowUserInput = false){
	global $module_ai_countries;	
	if(empty($module_ai_countries)){
		$module_ai_countries = array();
		$module_ai_countries[] = array('countries_id'=>'','countries_name'=>'请选择国家','countries_iso_code_2'=>'TOP');		
	  	$countryQuery   = tep_db_query("select countries_id, countries_name,countries_iso_code_2 from " . TABLE_COUNTRIES . " WHERE 1 order by countries_name ASC");
	  	while($row = tep_db_fetch_array($countryQuery))$module_ai_countries[] = $row;
	}
	$countrySelector1 = ''; 	$countrySelector2 = '';	
  	foreach($module_ai_countries as $row){
  		$selected = $country == $row['countries_id'] ? ' selected ':'';
  		$txt = '<option value="'.$row['countries_id'].'" '.$selected.'>'.$row['countries_name'].'</option>';  		
  		if(in_array(strtoupper($row['countries_iso_code_2']),array('TOP','US','CN','CA','HK','TW')))
  			$countrySelector1.= $txt;
  		else 
  			$countrySelector2.=$txt;
  	}
  	$html = '<select name="country"  id="'.$refobj.'_country" onchange="javascript:module_ai_refresh(\''.$refobj.'\',jQuery(this).val())" >'.$countrySelector1.$countrySelector2.'</select>';
	//洲
  	if($country != ''){
		$stateQuery = tep_db_query("select zone_name,zone_id from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' order by zone_code");
		$stateSelector = '';$state_id = '' ;
	  	while($row = tep_db_fetch_array($stateQuery)){
	  		if(empty($row)) continue ;
	  		if($state == $row['zone_name']){
	  			$selected = ' selected ';
	  			$state_id =  $row['zone_id'];
	  		}else{
	  		  $selected = '';
	  		}
	  		$stateSelector.='<option value="'.$row['zone_name'].'" '.$selected.'>'.$row['zone_name'].'</option>';
	  	}
	  	if($stateSelector == ''){return $html;} ; 
	  	$html .= '<select name="state" onchange="javascript:module_ai_refresh(\''.$refobj.'\',jQuery(\'#'.$refobj.'_country\').val(),jQuery(this).val())"><option value="">请选择省/州</option>'.$stateSelector.'</select>';
	}else{
		$html .= '<select name="state" ><option value="" selected>请选择省/州</option></select>';
	}
	//城市
	if($state_id != ''){
		$cityQuery = tep_db_query("select city_name,city_id from  zones_city  where zone_id = '" . (int)$state_id . "' order by city_id");
		$citySelector = '';				
		while($row = tep_db_fetch_array($cityQuery)){
			if(empty($row)) continue ;
	  		$selected =$city== $row['city_name']?' selected ':'';
	  		$citySelector.='<option value="'.$row['city_name'].'" '.$selected.'>'.$row['city_name'].'</option>';
	  	}
	  	if($citySelector == ''){return $html;} ;
	  	$html .= '<select name="city"><option value="">请选择城市</option>'.$citySelector.'</select>';
	}else{
		$html .= '<select name="city" ><option value="" selected>请选择城市</option></select>';
	}
	return $html;
}
/**
 * 联动地址栏需要的JS 
 * 调用 tep_draw_full_address_input 请同时调用该函数
 * @author vincent
 * @modify by vincent at 2011-5-12 下午05:44:21
 */
function tep_draw_full_address_input_js(){	
	if(defined("TEP_DRAW_FULL_ADDRESS_INPUT_JS")) return ;
	else define("TEP_DRAW_FULL_ADDRESS_INPUT_JS" , 'used');
	//jQuery("#"+refobj).html("000");
	global $p,$r;
	
	return 'function module_ai_refresh(refobj,country_id,state){
	if(typeof(state) == "undefined") state = "";
	var url = url_ssl("'.preg_replace($p,$r,tep_href_link_noseo('account_edit_ajax.php')).'");    
	jQuery.get(url , {"action":"draw_full_address_input","refobj":refobj,"country":country_id,"state":state},function(data){jQuery("#"+refobj).html(data);});}';
	/**///IE9
	/*return 'function module_ai_refresh(refobj,country_id,state){
	if(typeof(state) == "undefined") state = "";
	var ajax=false;
	if(window.XMLHttpRequest){
		ajax = new XMLHttpRequest();
	}else if (window.ActiveXObject){
		try{
			ajax = new ActiveXObject("Msxml2.XMLHTTP");
       }catch(e){
			try {
				ajax = new ActiveXObject("Microsoft.XMLHTTP");
			}catch(e1){}
		}
	}
	if (!ajax) {	
		window.alert("'.db_to_html('不能创建XMLHttpRequest对象实例.').'");
    }else{
	   ajax.open("GET","'.tep_href_link('account_edit_ajax.php').'?action=draw_full_address_input&country="+country_id+"&state="+encodeURI(state)+"&refobj="+refobj,true);
	   ajax.onreadystatechange = function(){
	   		if (ajax.readyState ==4 && ajax.status==200){ 
	   			document.getElementById(refobj).innerHTML = ajax.responseText;
			}
      }
       ajax.send(null);
   }
   }
	';
	return 'function module_ai_refresh(refobj,country_id,state){
	if(typeof(state) == "undefined"){ state = "";}
	var url = "account_edit_ajax.php?action=draw_full_address_input&country="+country_id+"&state="+encodeURI(state)+"&refobj="+refobj; 
	ajax_get_submit(url);
	}';*/
}
?>