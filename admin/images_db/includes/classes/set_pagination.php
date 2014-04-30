<?php
##名称：通用分页类
##功能：显示如 |< << [1] 2 [3] [4] [5] [6] [7] [8] [9] [10] ... >> >|之类的页码，并可单击页数打开页面。

class set_pagination
{
	//替换从地址栏传来的有关页&&总记录$_GET变量
	function queryString($pageNum_String , $totalRows_String , $totalRows_RecSQL)
	{
		$queryString_RecSQL = "";
		if (!empty($_SERVER['QUERY_STRING'])) {
		  $params = explode("&", $_SERVER['QUERY_STRING']);
		  $newParams = array();
		  foreach ($params as $param) {
			if (stristr($param, $pageNum_String ) == false && stristr($param, $totalRows_String ) == false) {
			  array_push($newParams, $param);
			}
		  }
		  if (count($newParams) != 0) {
			$queryString_RecSQL = "&amp;" . implode("&", $newParams);
		  }
		}
		$queryString_RecSQL ="&amp;".$totalRows_String."=".$totalRows_RecSQL . $queryString_RecSQL;
		return $queryString_RecSQL;
	}
	
	//分页函数
	function pagination($pageNum_String, $totalRows_String, $totalRows_RecSQL, $totalPages, $dis_sum, $now_page, $text_size, $full=1)
	{
		$_SERVER["SCRIPT_URI"] = $_SERVER['PHP_SELF'];
		//如果$full=0以最简化的方式显示
		$page = $now_page;
		$table_top = "<table><tr><td>";
		//$_GET[$totalRows_String] = min($now_page, $totalRows_RecSQL);
		for($I=1; $I<=min(($totalPages + 1 - ($now_page-1) + (($dis_sum/2)-1) ),$dis_sum); $I++)
		{
			if($page >=( ($dis_sum/2)+1) )
			{
				if(($page - ($dis_sum/2))== $now_page )
				{
					$display_pages .= '<span style="font-size:' . $text_size . '; background-color:#000000; color:#FFFFFF">'.($page - ($dis_sum/2)).'</span> ';
				}else
				{
					$to1page = $_SERVER["SCRIPT_URI"]."?".$pageNum_String."=".($page-($dis_sum/2)) . $this-> queryString($pageNum_String , $totalRows_String , $totalRows_RecSQL);
					//$display_pages .= '<span style=font-size:' . $text_size . '><a href="'.$to1page.'">['.($page-($dis_sum/2)).']</a></span> ';
					$display_pages .= '<a href="'.$to1page.'">['.($page-($dis_sum/2)).']</a> ';
				}
			}
			$page++;	
		}
		if($page-($dis_sum/2)<= $totalPages ) { $display_pages = $display_pages.'<span style="font-size:' . $text_size . '">……</span> ';}
		
		$first_href = $_SERVER["SCRIPT_URI"]."?".$pageNum_String."=1" . $this-> queryString($pageNum_String , $totalRows_String , $totalRows_RecSQL);
		$back_href = $_SERVER["SCRIPT_URI"]."?".$pageNum_String."=" . max(0, ($now_page - 1)) . $this-> queryString($pageNum_String , $totalRows_String , $totalRows_RecSQL);
		$next_href = $_SERVER["SCRIPT_URI"]."?".$pageNum_String."=" . min(($totalPages), $now_page+1) . $this-> queryString($pageNum_String , $totalRows_String , $totalRows_RecSQL);
		$end_href =  $_SERVER["SCRIPT_URI"]."?".$pageNum_String."=" . ($totalPages) . $this-> queryString($pageNum_String , $totalRows_String , $totalRows_RecSQL);
		$go_1 = '<a href="' . $first_href .'"><span  style="font-size:' . $text_size . '" title="first page">|&lt;&lt;</span></a> <a href="' . $back_href .'"><span  style="font-size:' . $text_size . '" title="Previous">上一页</span></a> ';
		$go_2 = '<a href="' . $next_href .'"><span  style="font-size:' . $text_size . '" title="Next">下一页</span></a> <a href="' . $end_href .'"><span  style="font-size:' . $text_size . '" title="last page">&gt;&gt;|</span></a>　';
		$go_page_from = '<form action="" method="get" id="go_page" style="font-size:'.$text_size.'; margin:0px; ">';

		foreach($_GET as $b => $c){
			if($b!="GO" && $b!=$pageNum_String && $b!=""){
			$input.= '<input name="'.$b.'" type="hidden" value="'.$c.'" />';
			}
		}

		$go_page_from1 = '
</td>
<td>
Go to Page
</td>
<td>
<input name="'.$pageNum_String.'" type="text" size="3" style="font-size:12px; ime-mode:disabled; "  value="'.$now_page.'" maxlength="10" />
'.$input.'
</td>
<td>
页  
</td>
<td>
<input type="submit" value="GO" style="font-size:12px " />
</td>

<td>
<input name="GO" type="hidden" id="GO" value="GO" />

</td>
</tr>
</table>
</form>'; 
		if($full==0){ $go_page_from=""; $go_page_from1="</td></tr></table>"; $display_pages=""; }

		if($now_page > 1 ){ $display_pages = $go_1 . $display_pages;}
		if(($now_page-1) < $totalPages-1 ){ $display_pages .= $go_2;}
		//echo $go_page_from . $display_pages . $go_page_from1;
		return ($go_page_from . $table_top . $display_pages . $go_page_from1);
	}
}
?>