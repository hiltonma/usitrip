<?php
/*
  $Id: breadcrumb.php,v 1.1.1.1 2004/03/04 23:40:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class breadcrumb {
    var $_trail;

    function breadcrumb() {
      $this->reset();
    }

    function reset() {
      $this->_trail = array();
    }
	/**
	 * 插入导航项目,默认插入到最后
	 * @param string $title
	 * @param url $link
	 * @param int $position
	 * @author vincent
	 * @modify by vincent at 2011-5-11 下午03:57:15
	 */
    function add($title, $link = '',$position = null) {
    	$position = is_numeric($position)? intval($position) : null;
    	if($position < 1) $position = null;
    	if($position == null)    $this->_trail[] = array('title' => $title, 'link' => $link);
    	else{
    		$newtrail = array();
    		$pos = 1 ;$processed = false;
    		$len = count($this->_trail) ;
    		for($i= 0 ; $i< $len;$i++){
    			if($i+1 == $position){
    				$newtrail[] = array('title' => $title, 'link' => $link);
    				$processed = true ;
    			}
    			$newtrail[]  = $this->_trail[$i] ;
    		}
    		if(!$processed) $newtrail[] = array('title' => $title, 'link' => $link);
    		$this->_trail = $newtrail;    		
    	}
    }

    function trail($separator = ' - ') {
      $trail_string = '';

      for ($i=0, $n=sizeof($this->_trail); $i<$n; $i++) {
        if (isset($this->_trail[$i]['link']) && tep_not_null($this->_trail[$i]['link'])) {
          $trail_string .= '<a href="' . $this->_trail[$i]['link'] . '" class="menu_topnav">' . $this->_trail[$i]['title'] . '</a>';
        } else {
          $trail_string .= $this->_trail[$i]['title'];
        }

        if (($i+1) < $n) $trail_string .= $separator;
      }

      return $trail_string;
    }
    /**
     * 编辑导航项目
     * @param unknown_type $url
     * @param unknown_type $title
     * @param unknown_type $link
     * @author vincent
     * @modify by vincent at 2011-5-11 下午03:51:33
     */
    function edit($url, $title='',$link=''){
    	foreach($this->_trail as $key=>$item){
    		if($item['link'] == $url){
    			if($title!='')$this->_trail[$key]['title'] =  $title;
    			if($link!='')$this->_trail[$key]['link'] =  $link;
    		}
    	}
    }
    /**
     * 检查指定的url是否在导航中
     * @param unknown_type $url
     * @author vincent
     * @modify by vincent at 2011-5-11 下午04:09:01
     */
    function exists($url){
    	foreach($this->_trail as $item){
    		if($item['link'] == $url) return true;
    	}
    	return false;
    }
    function size() {
	return sizeof($this->_trail);
    }
  }

//James:class from headerNavigation to menu.
?>
