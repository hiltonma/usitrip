<?php
/*
$Id: split_page_results.php,v 1.2 2004/03/05 00:36:42 ccwjr Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2003 osCommerce

Released under the GNU General Public License
*/

/**
 * 分页类，不返回执行结果
 * @author Administrator
 *
 */
class splitPageResults {

	/**
	 * SQL 查询语句
	 * 已经生成好当前页的句子，执行他则得到对应页的记录结果  
	 * @var string
	 */
	public $sql_query;
	
	/**
	 * 记录总行数
	 * @var int
	 */
	public $number_of_rows;
	public $current_page_number;
	public $number_of_pages;
	public $number_of_rows_per_page;
	public $page_name;

	/* class constructor */
	/**
	 * 分页类，根据页码，处理好分页SQL句子，以待查询得到当前需要的页数据
	 * @param string $query SQL句子
	 * @param int $max_rows 一页多少条记录
	 * @param unknown_type $count_key
	 * @param unknown_type $page_holder
	 * @param unknown_type $show_old_pagin
	 */
	function splitPageResults($query, $max_rows, $count_key = '*', $page_holder = 'page', $show_old_pagin = 'false') {

		if($show_old_pagin == 'true'){

			global $HTTP_GET_VARS, $HTTP_POST_VARS;

			$this->sql_query = $query;
			$this->page_name = $page_holder;

			if (isset($HTTP_GET_VARS[$page_holder])) {
				$page = $HTTP_GET_VARS[$page_holder];
			} elseif (isset($HTTP_POST_VARS[$page_holder])) {
				$page = $HTTP_POST_VARS[$page_holder];
			} else {
				$page = '';
			}

			if (empty($page) || !is_numeric($page)) $page = 1;
			$this->current_page_number = $page;

			$this->number_of_rows_per_page = $max_rows;

			$pos_to = strlen($this->sql_query);
			$pos_from = stripos($this->sql_query, ' from', 0);

			$pos_group_by = stripos($this->sql_query, ' group by', $pos_from);
			if (($pos_group_by < $pos_to) && ($pos_group_by != false)) $pos_to = $pos_group_by;

			$pos_having = stripos($this->sql_query, ' having', $pos_from);
			if (($pos_having < $pos_to) && ($pos_having != false)) $pos_to = $pos_having;

			$pos_order_by = stripos($this->sql_query, ' order by', $pos_from);
			if (($pos_order_by < $pos_to) && ($pos_order_by != false)) $pos_to = $pos_order_by;

			if (stripos($this->sql_query, 'distinct') || stripos($this->sql_query, 'group by')) {
				$count_string = 'distinct ' . tep_db_input($count_key);
			} else {
				$count_string = tep_db_input($count_key);
			}
			$temp_sql = "select count(" . $count_string . ") as total " . preg_replace('/ order by.+/',' ',substr($this->sql_query, $pos_from, ($pos_to - $pos_from)));

			$count_query = tep_db_query($temp_sql);
			$count = tep_db_fetch_array($count_query);

			$this->number_of_rows = $count['total'];

			$this->number_of_pages = ceil($this->number_of_rows / $this->number_of_rows_per_page);

			if ($this->current_page_number > $this->number_of_pages) {
				$this->current_page_number = $this->number_of_pages;
			}

			$offset = ($this->number_of_rows_per_page * ($this->current_page_number - 1));
			//newer version of mysql can not handle neg number in limit, temp fix
			if ($offset < '0'){
				$offset = '1';
			}
			$this->sql_query .= " limit " . $offset . ", " . $this->number_of_rows_per_page;

		}else{

			global $HTTP_GET_VARS, $HTTP_POST_VARS;

			$this->sql_query = $query;
			//EC - added variable to hold lower-case version of passed in query
			$this->poscheck_query = strtolower($query);
			$this->page_name = $page_holder;

			if (isset($HTTP_GET_VARS[$page_holder])) {
				$page = $HTTP_GET_VARS[$page_holder];
			} elseif (isset($HTTP_POST_VARS[$page_holder])) {
				$page = $HTTP_POST_VARS[$page_holder];
			} else {
				$page = '';
			}

			if (empty($page) || !is_numeric($page)) $page = 1;
			$this->current_page_number = $page;

			$this->number_of_rows_per_page = $max_rows;

			$pos_to = strlen($this->sql_query);
			//EC - commented out original, replaced with check on lowercase query
			// $pos_from = stripos($this->sql_query, ' from', 0);
			$pos_from = stripos($this->poscheck_query, ' from', 0);

			$pos_group_by = stripos($this->sql_query, ' group by', $pos_from);

			//EC - commented out original, replaced with check on lowercase query
			//$pos_group_by = stripos($this->sql_query, ' group by', $pos_from);
			$pos_group_by = stripos($this->poscheck_query, ' group by', $pos_from);
			if (($pos_group_by < $pos_to) && ($pos_group_by != false)) $pos_to = $pos_group_by;


			//EC - commented out original, replaced with check on lowercase query
			//$pos_having = stripos($this->sql_query, ' having', $pos_from);
			$pos_having = stripos($this->poscheck_query, ' having', $pos_from);
			if (($pos_having < $pos_to) && ($pos_having != false)) $pos_to = $pos_having;

			//EC - commented out original, replaced with check on lowercase query
			//$pos_order_by = stripos($this->sql_query, ' order by', $pos_from);
			$pos_order_by = stripos($this->poscheck_query, ' order by', $pos_from);
			if (($pos_order_by < $pos_to) && ($pos_order_by != false)) $pos_to = $pos_order_by;


			//EC - commented out original, replaced with check on lowercase query
			//if (stripos($this->sql_query, 'distinct') || stripos($this->sql_query, 'group by')) 
			// Phocea - Added condition on the count_key since count(distinct *) return an error in mysql!!
			if (stripos($this->poscheck_query, 'distinct') || stripos($this->poscheck_query, 'group by') && $count_key != '*') {
				$count_string = 'distinct ' . tep_db_input($count_key);
			} else {
				$count_string = tep_db_input($count_key);
			}

			// Phocea - IF we have a group by we need to count how many groups are being returned and not simply the individual
			// rows returned. So we wrap the original query around a count.
			if (stripos($this->poscheck_query, 'group by')) {
				$count_query_sql = "select count(*) as total from (select count(" . $count_string . ")" . preg_replace('/ order by.+/',' ',substr($this->sql_query, $pos_from)) .") as SubQ1";
			} else {
				$count_query_sql = "select count(" . $count_string . ") as total " . preg_replace('/ order by.+/',' ',substr($this->sql_query, $pos_from, ($pos_to - $pos_from)));
			}
			$count_query = tep_db_query($count_query_sql);
			$count = tep_db_fetch_array($count_query);

			$this->count_query = "select count(*) from (select count(" . $count_string . ")" . preg_replace('/ order by.+/',' ',substr($this->sql_query, $pos_from)).") as SubQ1";

			$this->number_of_rows = $count['total'];

			$this->number_of_pages = ceil($this->number_of_rows / $this->number_of_rows_per_page);

			if ($this->current_page_number > $this->number_of_pages) {
				$this->current_page_number = $this->number_of_pages;
			}

			$offset = ($this->number_of_rows_per_page * ($this->current_page_number - 1));
			if ($offset < 0)
			{
				$offset = 0 ;
			}
			$this->sql_query .= " limit " . $offset . ", " . $this->number_of_rows_per_page;

		}// end of show new code
	}

	/* class functions */
	/**
 * 显示分页页码按钮
 * @param unknown_type $max_page_links
 * @param unknown_type $parameters
 * @param int $type 非0和0两种模式 显示不同外观，应用不同CSS CSS 需要自己再定义
 * @return string
 * by lwkai 2012-04-02 modify
 */
	function display_links_2011($max_page_links, $parameters = '', $type=0) {
		global $PHP_SELF, $request_type;

		$display_links_string = '';

		$class = ' ';
		// BOM Mod:allow for a call when there are no rows to be displayed
		if ($this->number_of_pages > 0) {

			if (tep_not_null($parameters) && (substr($parameters, -1) != '&')) $parameters .= '&';

			// previous button - not displayed on first page
			if ($this->current_page_number > 1) {
				$display_links_string .= '<a href="' . tep_href_link(basename($PHP_SELF), $parameters . $this->page_name . '=1' , $request_type) . '" class="go first' . ($type > 0 ? ' first' . $type : '') . '"></a>';
				$display_links_string .= '<a href="' . tep_href_link(basename($PHP_SELF), $parameters . $this->page_name . '=' . ($this->current_page_number - 1), $request_type) . '" class="go pre' . ($type > 0 ? ' pre' . $type : '') . '" title=" ' . PREVNEXT_TITLE_PREVIOUS_PAGE . ' ">'.db_to_html('上一页').'</a>';
			}

			// check if number_of_pages > $max_page_links
			$cur_window_num = intval($this->current_page_number / $max_page_links);
			if ($this->current_page_number % $max_page_links) $cur_window_num++;

			$max_window_num = intval($this->number_of_pages / $max_page_links);
			if ($this->number_of_pages % $max_page_links) $max_window_num++;
			$display_links_string .= '<span>&nbsp;|';
			// previous window of pages
			if ($cur_window_num > 1) $display_links_string .= '<a  href="' . tep_href_link(basename($PHP_SELF), $parameters . $this->page_name . '=' . (($cur_window_num - 1) * $max_page_links), $request_type) . '" title=" ' . sprintf(PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE, $max_page_links) . ' ">...</a> ' . ($type > 0 ? '' : '|') . ' ';

			// page nn button

			for ($jump_to_page = 1 + (($cur_window_num - 1) * $max_page_links); ($jump_to_page <= ($cur_window_num * $max_page_links)) && ($jump_to_page <= $this->number_of_pages); $jump_to_page++) {
				if ($jump_to_page == $this->current_page_number) {
					$display_links_string .= '<b' . ($type > 0 ? ' class="type' . $type . '"' : '') . '>' . $jump_to_page . '</b>' . ($type > 0 ? '' : '|') . '';
				} else {
					$display_links_string .= '<a' . ($type > 0 ? ' class="type' . $type . '"' : '') . ' href="' . tep_href_link(basename($PHP_SELF), $parameters . $this->page_name . '=' . $jump_to_page, $request_type) . '"  title=" ' . sprintf(PREVNEXT_TITLE_PAGE_NO, $jump_to_page) . ' ">' . $jump_to_page . '</a>' . ($type > 0 ? '' : '|') . '';
				}
			}


			// next window of pages
			if ($cur_window_num < $max_window_num) $display_links_string .= '<a href="' . tep_href_link(basename($PHP_SELF), $parameters . $this->page_name . '=' . (($cur_window_num) * $max_page_links + 1), $request_type) . '" title=" ' . sprintf(PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE, $max_page_links) . ' ">...</a> ' . ($type > 0 ? '' : '|') . '';
			$display_links_string .= '</span>';
			// next button
			if (($this->current_page_number < $this->number_of_pages) && ($this->number_of_pages != 1)) {
				$display_links_string .= '<a href="' . tep_href_link(basename($PHP_SELF), $parameters . $this->page_name . '=' . ($this->current_page_number + 1), $request_type) . '" title=" ' . PREVNEXT_TITLE_NEXT_PAGE . ' " class="go next' . ($type > 0 ? ' next' . $type : '') . '">'.db_to_html('下一页').'</a>';
				$display_links_string .= '<a href="' . tep_href_link(basename($PHP_SELF), $parameters . $this->page_name . '='.$this->number_of_pages , $request_type) . '" class="go last' . ($type > 0 ? ' last' . $type : '') . '"></a>';
			}

		} else {  // if zero rows, then simply say that
			$display_links_string .= '<b' . ($type > 0 ? ' class="type' . $type . '"' : '') . '>0</b>';
		}
		// EMO Mod
		return $display_links_string;
	}
	// display split-page-number-links
	function display_links($max_page_links, $parameters = '', $type=0) {
		global $PHP_SELF, $request_type;

		$display_links_string = '';

		$class = ' ';
		// BOM Mod:allow for a call when there are no rows to be displayed
		if ($this->number_of_pages > 0) {

			if (tep_not_null($parameters) && (substr($parameters, -1) != '&')) $parameters .= '&';

			// previous button - not displayed on first page
			if ($this->current_page_number > 1) $display_links_string .= '<a ' . ($type > 0 ? ' class="pre' . $type . '"' : '') . '" href="' . tep_href_link(basename($PHP_SELF), $parameters . $this->page_name . '=' . ($this->current_page_number - 1), $request_type) . '" title=" ' . PREVNEXT_TITLE_PREVIOUS_PAGE . ' ">' . PREVNEXT_BUTTON_PREV . '</a>&nbsp;&nbsp;';

			// check if number_of_pages > $max_page_links
			$cur_window_num = intval($this->current_page_number / $max_page_links);
			if ($this->current_page_number % $max_page_links) $cur_window_num++;

			$max_window_num = intval($this->number_of_pages / $max_page_links);
			if ($this->number_of_pages % $max_page_links) $max_window_num++;

			// previous window of pages
			if ($cur_window_num > 1) $display_links_string .= '<a href="' . tep_href_link(basename($PHP_SELF), $parameters . $this->page_name . '=' . (($cur_window_num - 1) * $max_page_links), $request_type) . '" title=" ' . sprintf(PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE, $max_page_links) . ' ">...</a> ' . ($type > 0 ? '' : '|') . ' ';

			// page nn button
			for ($jump_to_page = 1 + (($cur_window_num - 1) * $max_page_links); ($jump_to_page <= ($cur_window_num * $max_page_links)) && ($jump_to_page <= $this->number_of_pages); $jump_to_page++) {
				if ($jump_to_page == $this->current_page_number) {
					$display_links_string .= '&nbsp;<b>' . $jump_to_page . '</b>&nbsp;' . ($type > 0 ? '' : '|') . '';
				} else {
					$display_links_string .= '&nbsp;<a' . ($type > 0 ? ' class="type' . $type . '"' : '') . ' href="' . tep_href_link(basename($PHP_SELF), $parameters . $this->page_name . '=' . $jump_to_page, $request_type) . '"  title=" ' . sprintf(PREVNEXT_TITLE_PAGE_NO, $jump_to_page) . ' ">' . $jump_to_page . '</a>&nbsp;' . ($type > 0 ? '' : '|') . '';
				}
			}

			// next window of pages
			if ($cur_window_num < $max_window_num) $display_links_string .= '<a href="' . tep_href_link(basename($PHP_SELF), $parameters . $this->page_name . '=' . (($cur_window_num) * $max_page_links + 1), $request_type) . '" title=" ' . sprintf(PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE, $max_page_links) . ' ">...</a> ' . ($type > 0 ? '' : '|') . '&nbsp;';

			// next button
			if (($this->current_page_number < $this->number_of_pages) && ($this->number_of_pages != 1)) $display_links_string .= '&nbsp;<a' . ($type > 0 ? ' class="next' . $type . '"' : '') . ' href="' . tep_href_link(basename($PHP_SELF), $parameters . $this->page_name . '=' . ($this->current_page_number + 1), $request_type) . '" title=" ' . PREVNEXT_TITLE_NEXT_PAGE . ' ">' . PREVNEXT_BUTTON_NEXT . '</a>&nbsp;';

		} else {  // if zero rows, then simply say that
			$display_links_string .= '&nbsp;<b>0</b>&nbsp;';
		}
		// EMO Mod
		return $display_links_string;
	}

	//amit added to display link for question and answers start

	// display split-page-number-links
	function display_links_quesion($max_page_links, $parameters = '') {
		global $PHP_SELF, $request_type;

		$display_links_string = '';

		$class = ' ';
		// BOM Mod:allow for a call when there are no rows to be displayed
		if ($this->number_of_pages > 0) {

			if (tep_not_null($parameters) && (substr($parameters, -1) != '&')) $parameters .= '&';

			// previous button - not displayed on first page
			if ($this->current_page_number > 1) $display_links_string .= '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, $parameters . $this->page_name . '=' . ($this->current_page_number - 1), $request_type) . '" title=" ' . PREVNEXT_TITLE_PREVIOUS_PAGE . ' ">' . PREVNEXT_BUTTON_PREV . '</a>&nbsp;&nbsp;';

			// check if number_of_pages > $max_page_links
			$cur_window_num = intval($this->current_page_number / $max_page_links);
			if ($this->current_page_number % $max_page_links) $cur_window_num++;

			$max_window_num = intval($this->number_of_pages / $max_page_links);
			if ($this->number_of_pages % $max_page_links) $max_window_num++;

			// previous window of pages
			if ($cur_window_num > 1) $display_links_string .= '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, $parameters . $this->page_name . '=' . (($cur_window_num - 1) * $max_page_links), $request_type) . '" title=" ' . sprintf(PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE, $max_page_links) . ' ">...</a> | ';

			// page nn button
			for ($jump_to_page = 1 + (($cur_window_num - 1) * $max_page_links); ($jump_to_page <= ($cur_window_num * $max_page_links)) && ($jump_to_page <= $this->number_of_pages); $jump_to_page++) {
				if ($jump_to_page == $this->current_page_number) {
					$display_links_string .= '&nbsp;<b>' . $jump_to_page . '</b>&nbsp;|';
				} else {
					$display_links_string .= '&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, $parameters . $this->page_name . '=' . $jump_to_page, $request_type) . '"  title=" ' . sprintf(PREVNEXT_TITLE_PAGE_NO, $jump_to_page) . ' ">' . $jump_to_page . '</a>&nbsp;|';
				}
			}

			// next window of pages
			if ($cur_window_num < $max_window_num) $display_links_string .= '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, $parameters . $this->page_name . '=' . (($cur_window_num) * $max_page_links + 1), $request_type) . '" title=" ' . sprintf(PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE, $max_page_links) . ' ">...</a> |&nbsp;';

			// next button
			if (($this->current_page_number < $this->number_of_pages) && ($this->number_of_pages != 1)) $display_links_string .= '&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, $parameters . $this->page_name .'=' . ($this->current_page_number + 1), $request_type) . '" title=" ' . PREVNEXT_TITLE_NEXT_PAGE . ' ">' . PREVNEXT_BUTTON_NEXT . '</a>&nbsp;';

		} else {  // if zero rows, then simply say that
			$display_links_string .= '&nbsp;<b>0</b>&nbsp;';
		}
		// EMO Mod
		return $display_links_string;
	}

	//amit added to display link for question and answers end


	//amit added to ajax base pagging start
	function display_links_ajax($max_page_links, $parameters = '',$link_ajax_pagename='product_listing_index_products_ajax.php',$ajax_frm_name='frm_slippage_ajax_product',$res_destination_div='div_product_listing') {
		global $PHP_SELF, $request_type;

		$display_links_string = '';

		//$class = ' ';
		$class = '';
		// BOM Mod:allow for a call when there are no rows to be displayed
		if ($this->number_of_pages > 0) {

			if (tep_not_null($parameters) && (substr($parameters, -1) != '&')) $parameters .= '&amp;';

			// previous button - not displayed on first page
			if ($this->current_page_number > 1) $display_links_string .= '<a href="javascript:void(0);"   onclick="sendFormData(\''.$ajax_frm_name.'\',\''.$link_ajax_pagename.'?'.$parameters . $this->page_name . '=' . ($this->current_page_number - 1).'&addhash=true\',\''.$res_destination_div.'\',\'true\');" title=" ' . PREVNEXT_TITLE_PREVIOUS_PAGE . ' " class="go pre">' . PREVNEXT_BUTTON_PREV_SUB . '</a>';
			$display_links_string .= '<span>&nbsp;|';

			// check if number_of_pages > $max_page_links
			$cur_window_num = intval($this->current_page_number / $max_page_links);
			if ($this->current_page_number % $max_page_links) $cur_window_num++;

			$max_window_num = intval($this->number_of_pages / $max_page_links);
			if ($this->number_of_pages % $max_page_links) $max_window_num++;

			// previous window of pages
			if ($cur_window_num > 1) $display_links_string .= '<a href="javascript:void(0);" onclick="sendFormData(\''.$ajax_frm_name.'\',\''.$link_ajax_pagename.'?'.$parameters . $this->page_name . '=' . (($cur_window_num - 1) * $max_page_links).'&addhash=true\',\''.$res_destination_div.'\',\'true\');" title=" ' . sprintf(PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE, $max_page_links) . ' ">...</a> | ';

			// page nn button
			for ($jump_to_page = 1 + (($cur_window_num - 1) * $max_page_links); ($jump_to_page <= ($cur_window_num * $max_page_links)) && ($jump_to_page <= $this->number_of_pages); $jump_to_page++) {
				if ($jump_to_page == $this->current_page_number) {
					$display_links_string .= '<b>' . $jump_to_page . '</b>|';
				} else {
					$display_links_string .= '<a href="javascript:void(0);"   onclick="sendFormData(\''.$ajax_frm_name.'\',\''.$link_ajax_pagename.'?'.$parameters . $this->page_name . '=' . $jump_to_page.'&addhash=true\',\''.$res_destination_div.'\',\'true\');" title=" ' . sprintf(PREVNEXT_TITLE_PAGE_NO, $jump_to_page) . ' ">' . $jump_to_page . '</a>|';
				}
			}

			// next window of pages
			if ($cur_window_num < $max_window_num) $display_links_string .= '<a href="javascript:void(0);" onclick="sendFormData(\''.$ajax_frm_name.'\',\''.$link_ajax_pagename.'?'.$parameters . $this->page_name . '=' . (($cur_window_num) * $max_page_links + 1).'&addhash=true\',\''.$res_destination_div.'\',\'true\');"  title=" ' . sprintf(PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE, $max_page_links) . ' ">...</a> |';

			$display_links_string.='</span>';
			// next button
			if (($this->current_page_number < $this->number_of_pages) && ($this->number_of_pages != 1)) $display_links_string .= '<a href="javascript:void(0);" onclick="sendFormData(\''.$ajax_frm_name.'\',\''.$link_ajax_pagename.'?'.$parameters . $this->page_name . '=' . ($this->current_page_number + 1).'&addhash=true\',\''.$res_destination_div.'\',\'true\');"   title=" ' . PREVNEXT_TITLE_NEXT_PAGE . ' " class="go next">' . PREVNEXT_BUTTON_NEXT_SUB . '</a>';

		} else {  // if zero rows, then simply say that
			$display_links_string .= '<b>0</b>';
		}
		// EMO Mod
		return $display_links_string;
	}

	function display_links_ajax_nextprev_only($max_page_links, $parameters = '',$link_ajax_pagename='product_listing_index_products_ajax.php',$ajax_frm_name='frm_slippage_ajax_product',$res_destination_div='div_product_listing') {
		global $PHP_SELF, $request_type;

		$display_links_string = '';

		$class = ' ';
		// BOM Mod:allow for a call when there are no rows to be displayed
		if ($this->number_of_pages > 0) {

			if (tep_not_null($parameters) && (substr($parameters, -1) != '&')) $parameters .= '&amp;';

			// previous button - not displayed on first page
			if ($this->current_page_number > 1) $display_links_string .= '<a href="javascript:void(0);"   onclick="sendFormData(\''.$ajax_frm_name.'\',\''.$link_ajax_pagename.'?'.$parameters . $this->page_name . '=' . ($this->current_page_number - 1).'\',\''.$res_destination_div.'\',\'true\');" title=" ' . PREVNEXT_TITLE_PREVIOUS_PAGE . ' ">' . '<img src="image/pre_wht.gif" />' . '</a>&nbsp;&nbsp;';

			// check if number_of_pages > $max_page_links
			$cur_window_num = intval($this->current_page_number / $max_page_links);
			if ($this->current_page_number % $max_page_links) $cur_window_num++;

			$max_window_num = intval($this->number_of_pages / $max_page_links);
			if ($this->number_of_pages % $max_page_links) $max_window_num++;

			//no need of this links to traveler photos page -- condition
			/*
			// previous window of pages

			if ($cur_window_num > 1) $display_links_string .= '<a href="javascript:void(0);" onclick="sendFormData(\''.$ajax_frm_name.'\',\''.$link_ajax_pagename.'?'.$parameters . $this->page_name . '=' . (($cur_window_num - 1) * $max_page_links).'\',\''.$res_destination_div.'\',\'true\');"   title=" ' . sprintf(PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE, $max_page_links) . ' ">...</a> | ';

			// page nn button
			for ($jump_to_page = 1 + (($cur_window_num - 1) * $max_page_links); ($jump_to_page <= ($cur_window_num * $max_page_links)) && ($jump_to_page <= $this->number_of_pages); $jump_to_page++) {
			if ($jump_to_page == $this->current_page_number) {
			$display_links_string .= '&nbsp;<b>' . $jump_to_page . '</b>&nbsp;|';
			} else {
			$display_links_string .= '&nbsp;<a href="javascript:void(0);"   onclick="sendFormData(\''.$ajax_frm_name.'\',\''.$link_ajax_pagename.'?'.$parameters . $this->page_name . '=' . $jump_to_page.'\',\''.$res_destination_div.'\',\'true\');" title=" ' . sprintf(PREVNEXT_TITLE_PAGE_NO, $jump_to_page) . ' ">' . $jump_to_page . '</a>&nbsp;|';
			}
			}

			// next window of pages
			if ($cur_window_num < $max_window_num) $display_links_string .= '<a href="javascript:void(0);" onclick="sendFormData(\''.$ajax_frm_name.'\',\''.$link_ajax_pagename.'?'.$parameters . $this->page_name . '=' . (($cur_window_num) * $max_page_links + 1).'\',\''.$res_destination_div.'\',\'true\');"   title=" ' . sprintf(PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE, $max_page_links) . ' ">...</a> |&nbsp;';

			//}//no need of this links to traveler photos page -- condition end*/

			// next button
			if (($this->current_page_number < $this->number_of_pages) && ($this->number_of_pages != 1)) $display_links_string .= '&nbsp;<a href="javascript:void(0);" onclick="sendFormData(\''.$ajax_frm_name.'\',\''.$link_ajax_pagename.'?'.$parameters . $this->page_name . '=' . ($this->current_page_number + 1).'\',\''.$res_destination_div.'\',\'true\');"   title=" ' . PREVNEXT_TITLE_NEXT_PAGE . ' ">' . '<img src="image/next_wht.gif" />' . '</a>&nbsp;';

		} else {  // if zero rows, then simply say that
			$display_links_string .= '&nbsp;<b>0</b>&nbsp;';
		}
		// EMO Mod
		//echo $display_links_string;
		return $display_links_string;
	}


	//amit added to ajax base pagging end


	//amit added for paggin without hash link start
	function display_links_ajax_withouthash($max_page_links, $parameters = '',$link_ajax_pagename='product_listing_index_products_ajax.php',$ajax_frm_name='frm_slippage_ajax_product',$res_destination_div='div_product_listing') {
		global $PHP_SELF, $request_type;

		$display_links_string = '';

		$class = ' ';
		// BOM Mod:allow for a call when there are no rows to be displayed
		if ($this->number_of_pages > 0) {

			if (tep_not_null($parameters) && (substr($parameters, -1) != '&')) $parameters .= '&amp;';

			// previous button - not displayed on first page
			if ($this->current_page_number > 1) $display_links_string .= '<a href="javascript:void(0);"   onclick="sendFormData(\''.$ajax_frm_name.'\',\''.$link_ajax_pagename.'?'.$parameters . $this->page_name . '=' . ($this->current_page_number - 1).'\',\''.$res_destination_div.'\',\'true\');" title=" ' . PREVNEXT_TITLE_PREVIOUS_PAGE . ' " class="go pre">' . PREVNEXT_BUTTON_PREV_SUB . '</a>';
			$display_links_string .= '<span>&nbsp;|';

			// check if number_of_pages > $max_page_links
			$cur_window_num = intval($this->current_page_number / $max_page_links);
			if ($this->current_page_number % $max_page_links) $cur_window_num++;

			$max_window_num = intval($this->number_of_pages / $max_page_links);
			if ($this->number_of_pages % $max_page_links) $max_window_num++;

			//no need of this links to traveler photos page -- condition

			// previous window of pages

			if ($cur_window_num > 1) $display_links_string .= '<a href="javascript:void(0);" onclick="sendFormData(\''.$ajax_frm_name.'\',\''.$link_ajax_pagename.'?'.$parameters . $this->page_name . '=' . (($cur_window_num - 1) * $max_page_links).'\',\''.$res_destination_div.'\',\'true\');"   title=" ' . sprintf(PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE, $max_page_links) . ' ">...</a> | ';

			// page nn button
			for ($jump_to_page = 1 + (($cur_window_num - 1) * $max_page_links); ($jump_to_page <= ($cur_window_num * $max_page_links)) && ($jump_to_page <= $this->number_of_pages); $jump_to_page++) {
				if ($jump_to_page == $this->current_page_number) {
					$display_links_string .= '<b>' . $jump_to_page . '</b>|';
				} else {
					$display_links_string .= '<a href="javascript:void(0);"   onclick="sendFormData(\''.$ajax_frm_name.'\',\''.$link_ajax_pagename.'?'.$parameters . $this->page_name . '=' . $jump_to_page.'\',\''.$res_destination_div.'\',\'true\');" title=" ' . sprintf(PREVNEXT_TITLE_PAGE_NO, $jump_to_page) . ' ">' . $jump_to_page . '</a>|';
				}
			}

			// next window of pages
			if ($cur_window_num < $max_window_num) $display_links_string .= '<a href="javascript:void(0);" onclick="sendFormData(\''.$ajax_frm_name.'\',\''.$link_ajax_pagename.'?'.$parameters . $this->page_name . '=' . (($cur_window_num) * $max_page_links + 1).'\',\''.$res_destination_div.'\',\'true\');"   title=" ' . sprintf(PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE, $max_page_links) . ' ">...</a> |';

			//}//no need of this links to traveler photos page -- condition end

			$display_links_string .= '</span>';
			// next button
			if (($this->current_page_number < $this->number_of_pages) && ($this->number_of_pages != 1)) $display_links_string .= '<a href="javascript:void(0);" onclick="sendFormData(\''.$ajax_frm_name.'\',\''.$link_ajax_pagename.'?'.$parameters . $this->page_name . '=' . ($this->current_page_number + 1).'\',\''.$res_destination_div.'\',\'true\');"   title=" ' . PREVNEXT_TITLE_NEXT_PAGE . ' " class="go next">' . PREVNEXT_BUTTON_NEXT_SUB . '</a>';

		} else {  // if zero rows, then simply say that
			$display_links_string .= '<b>0</b>';
		}
		// EMO Mod
		//echo $display_links_string;
		return $display_links_string;
	}

	//amit added for paggin without hash link end

	// display number of total products found
	function display_count($text_output) {
		$to_num = ($this->number_of_rows_per_page * $this->current_page_number);
		if ($to_num > $this->number_of_rows) $to_num = $this->number_of_rows;

		$from_num = ($this->number_of_rows_per_page * ($this->current_page_number - 1));

		if ($to_num == 0) {
			$from_num = 0;
		} else {
			$from_num++;
		}

		return sprintf($text_output, $from_num, $to_num, $this->number_of_rows);
	}

	//Added to display providers listing ------- Start
	function display_links_providers($query_numrows, $max_rows_per_page, $max_page_links, $current_page_number, $parameters = '', $page_name = 'page') {
		global $PHP_SELF;
		$action_page=DIR_WS_PROVIDERS.basename($PHP_SELF);

		if ( tep_not_null($parameters) && (substr($parameters, -1) != '&') ) $parameters .= '&';

		// calculate number of pages needing links
		$num_pages = ceil($query_numrows / $max_rows_per_page);

		$pages_array = array();
		for ($i=1; $i<=$num_pages; $i++) {
			$pages_array[] = array('id' => $i, 'text' => $i);
		}

		if ($num_pages > 1) {
			$display_links = tep_draw_form('pages', basename($PHP_SELF), '', 'get');

			if ($current_page_number > 1) {
				$display_links .= '<a href="' . tep_href_link($action_page, $parameters . $page_name . '=' . ($current_page_number - 1), 'SSL') . '" class="splitPageLink">' . PREVNEXT_BUTTON_PREV . '</a>&nbsp;&nbsp;';
			} else {
				$display_links .= PREVNEXT_BUTTON_PREV . '&nbsp;&nbsp;';
			}

			$display_links .= sprintf(TEXT_RESULT_PAGE_PROVIDERS, tep_draw_pull_down_menu($page_name, $pages_array, $current_page_number, 'onChange="this.form.submit();"'), $num_pages);

			if(!tep_not_null($current_page_number))
			$current_page_number=1;

			if (($current_page_number < $num_pages) && ($num_pages != 1)) {
				$display_links .= '&nbsp;&nbsp;<a href="' . tep_href_link($action_page, $parameters . $page_name . '=' . ($current_page_number + 1), 'SSL') . '" class="splitPageLink">' . PREVNEXT_BUTTON_NEXT . '</a>';
			} else {
				$display_links .= '&nbsp;&nbsp;' . PREVNEXT_BUTTON_NEXT;
			}

			if ($parameters != '') {
				if (substr($parameters, -1) == '&') $parameters = substr($parameters, 0, -1);
				$pairs = explode('&', $parameters);
				while (list(, $pair) = each($pairs)) {
					list($key,$value) = explode('=', $pair);
					$display_links .= tep_draw_hidden_field(rawurldecode($key), rawurldecode($value));
				}
			}

			if (SID) $display_links .= tep_draw_hidden_field(tep_session_name(), tep_session_id());

			$display_links .= '</form>';
		} else {
			$display_links = sprintf(TEXT_RESULT_PAGE_PROVIDERS, $num_pages, $num_pages);
		}
		return $display_links;
	}
	//Added to display providers listion ------- End
}


##名称：通用分页类
##功能：显示如 |< << [1] 2 [3] [4] [5] [6] [7] [8] [9] [10] ... >> >|之类的页码，并可单击页数打开页面。
//new set_pagination($from,$where,$totalRows_String,$now_page,$pageNum_String);
define('FIRST_PAGE','首页');
define('PREVIOUS_PAGE','上一页');
define('NEXT_PAGE','下一页');
define('FINAL_PAGE','尾页');
define('QUICK_TO','快速到');
define('TO_PAGE','页');

class set_pagination
{
	//总页数
	var $totalPages;
	//总记录数
	var $totalRows;
	//当前页是第几页默认为第1页
	var $now_page;
	//在url显示的当前页变数的名称
	var $pageNum_String;
	//SQL查询的开始行数
	var $startRow=0;
	//当页结束行数
	var $endRow = 0;
	//到达页的表单名称
	var $form_name_and_id;
	//每屏显示几个页码数位，默认为6，必须是偶数
	var $dis_sum;
	//设置页码文字大小默认为12px;
	var $text_size;
	//非简化方式显示开关，1为完全显示，0为以简化方式显示
	var $full;
	//当前页面档案名
	var $PhpSelf;

	function set_pagination($from,$where, $now_page='1',$pageNum_String='page',$form_name_and_id='form_page',$dis_sum='6',$text_size='12px',$full=1,$maxRows = MAX_DISPLAY_SEARCH_RESULTS){
		$this->now_page= abs(intval($now_page));
		if($this->now_page < 1){ $this->now_page=1;}
		$this->pageNum_String=(string)$pageNum_String;
		$this->form_name_and_id=$form_name_and_id;
		$this->dis_sum=(int)$dis_sum;
		if($this->dis_sum % 2!=0 || $this->dis_sum <2 ){ $this->dis_sum=20; }
		$this->text_size=$text_size;
		$this->full=$full;
		$this->PhpSelf = preg_replace('/.*\//','/',$_SERVER['SCRIPT_FILENAME']);
		//$this->PhpSelf = preg_replace('/\.php/','.html',$this->PhpSelf);

		//总记录数
		$count_query = tep_db_query("select count(*) as total from $from $where ");
		$this->totalRows = tep_db_result($count_query,"0","total");
		//if($this->totalRows<1){ echo "总记录为0"; }
		//总页数
		$this->totalPages=ceil(($this->totalRows) / $maxRows );
		//SQL查询的开始行数
		$this->startRow= ($this->now_page -1) * $maxRows;
		//结束行数
		$this->endRow= min(( $this->startRow + $maxRows ),$this->totalRows);

		//如果当前页大於总页数则立即返回到最后一页
		if($now_page > $this->totalPages && $this->totalRows>0){
			$m=$pageNum_String.'='.$now_page;
			$r=$pageNum_String.'='.$this->totalPages;
			$_SERVER['QUERY_STRING']=ereg_replace($m,$r,$_SERVER['QUERY_STRING']);
			$Chg_page = $this->PhpSelf."?".$_SERVER['QUERY_STRING'];
			header("Location: $Chg_page");
			exit;
		}

	}


	//替换从地址栏传来的有关页&&总记录$_GET变数
	function queryString()
	{
		$queryString_RecSQL = "";
		if (!empty($_SERVER['QUERY_STRING'])) {
			$params = explode("&", $_SERVER['QUERY_STRING']);
			$newParams = array();
			foreach ($params as $param) {
				if (stristr($param, $this -> pageNum_String ) == false ) {
					array_push($newParams, $param);
				}
			}
			if (count($newParams) != 0) {
				$queryString_RecSQL = "&amp;" . implode("&", $newParams);
			}
		}
		//$queryString_RecSQL ="&amp;".$this -> totalRows_String."=".$this -> totalRows . $queryString_RecSQL;
		return $queryString_RecSQL;
	}

	//分页函数
	function pagination()
	{
		//如果$full=0以最简化的方式显示
		$page = $this -> now_page;


		$table_top = '<table border="0" cellspacing="0" cellpadding="0" style="margin:0px;"><tr><td valign="bottom"><dl>';
		for($I=1; $I<=min(($this -> totalPages + 1 - ($this -> now_page-1) + (($this -> dis_sum/2)-1) ),$this -> dis_sum); $I++)
		//for($I=1; $I<=100; $I++)
		{
			if($page >=( ($this -> dis_sum/2)+1) )
			{
				if(($page - ($this -> dis_sum/2))== $this -> now_page )
				{
					$display_pages .= '<dd><span style="font-size:' . $this -> text_size . '; background-color:#FF6600; color:#FFFFFF">'.($page - ($this -> dis_sum/2)).'</span></dd> ';
				}else
				{
					$to1page = $this->PhpSelf."?".$this -> pageNum_String."=".($page-($this -> dis_sum/2)) . $this-> queryString();
					//$display_pages .= '<a class="font_14" href="'.$to1page.'">['.($page-($this -> dis_sum/2)).']</a> ';
					$display_pages .= '<dd><a class="font_14" href="'.$to1page.'">'.($page-($this -> dis_sum/2)).'</a></dd> ';
				}
			}
			$page++;
		}
		//if($page-($this -> dis_sum/2)<= $this -> totalPages ) { $display_pages = $display_pages.'<span style="font-size:' . $this -> text_size . '">...</span> ';}

		$first_href = $this->PhpSelf."?".$this -> pageNum_String."=1" . $this-> queryString();
		$back_href = $this->PhpSelf."?".$this -> pageNum_String."=" . max(0, ($this -> now_page - 1)) . $this-> queryString();
		$next_href = $this->PhpSelf."?".$this -> pageNum_String."=" . min(($this -> totalPages), $this -> now_page+1) . $this-> queryString();
		$end_href =  $this->PhpSelf."?".$this -> pageNum_String."=" . ($this -> totalPages) . $this-> queryString();
		$go_1 = '<dd><a class="font_14" href="' . $first_href .'"><span  style="font-size:' . $this -> text_size . '" title="First">'.FIRST_PAGE.'</span></a></dd> <dd><a class="font_14" href="' . $back_href .'"><span  style="font-size:' . $this -> text_size . '" title="Previous">'.PREVIOUS_PAGE.'</span></a> </dd>';
		$go_2 = '<dd><a class="font_14" href="' . $next_href .'"><span  style="font-size:' . $this -> text_size . '" title="Next">'.NEXT_PAGE.'</span></a></dd> <dd><a class="font_14" href="' . $end_href .'"><span  style="font-size:' . $this -> text_size . '" title="Final">'.FINAL_PAGE.'</span></a></dd>';
		$go_page_from = '<form action="'.$this->PhpSelf.'" method="get" name="'.$this -> form_name_and_id.'" id="'.$this -> form_name_and_id.'" style=" margin:0px; padding:0px;">';

		foreach($_GET as $b => $c){
			if($b!="GO" && $b!=$this -> pageNum_String && $b!=""){
				$input.= '<input name="'.$b.'" type="hidden" value="'.$c.'" />';
			}
		}

		$go_page_from1 = '
</dl>
</td>

<!--隐藏快速到的输入框
<td valign="bottom">
&nbsp;'.QUICK_TO.'
</td>
<td valign="bottom">
<input name="'.$this -> pageNum_String.'" type="text" size="3" class="text_1_sel text_border" style="font-size:12px; ime-mode:disabled; "  value="'.$this -> now_page.'" maxlength="10" />
'.$input.'
</td>
<td valign="bottom">
'.TO_PAGE.' 
</td>
<td valign="bottom">
<input name="button" class="pl_button1" value="GO" type="submit" title="GO">
</td>

<td valign="bottom">
<input name="GO" type="hidden" id="GO" value="GO" />

</td>
-->

</tr>
</table>
</form>'; 
		if($this ->full ==0){ $go_page_from=""; $go_page_from1="</td></tr></table>"; $display_pages=""; }

		if($this -> now_page > 1 ){ $display_pages = $go_1 . $display_pages;}
		if(($this -> now_page-1) < $this -> totalPages-1 ){ $display_pages .= $go_2;}
		//echo $go_page_from . $display_pages . $go_page_from1;
		//echo $display_pages;
		return ($go_page_from . $table_top . $display_pages . $go_page_from1);
	}
}

?>
