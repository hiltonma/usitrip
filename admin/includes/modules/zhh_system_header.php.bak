<?php
//面包屑 start
$Bread = "";
$jnb=sizeof($breadcrumb->_trail);
if((int)$jnb){
	for ($jib=0; $jib<$jnb; $jib++) {
		if(($jnb-1) != $jib){
			$Bread.= '<a class="caozuo" href="' . $breadcrumb->_trail[$jib]['link'] . '" >' . str_replace('Top','首页',trim($breadcrumb->_trail[$jib]['title'])) . '</a> &gt; ';
		}else{
			$Bread.= '<span>' . str_replace('Top','首页',trim($breadcrumb->_trail[$jib]['title'])) . '</span>';
		}
	}
}
//面包屑 end
?>