<?php
/**
 * 文章采集类GetHtml
 * @author Howard
 * @modify by Howard at 2011-09-11
 */

class GetHtml {
	var $fileURL;	//网页文件名称
	var $inputCharSet = "gb2312";   //网页源编码
	var $outCharSet = "gb2312";   //输出编码
	var $dom;
	var $domGetType;	//抓取类型，有dom和curl两种方式
	var $heardSeparatedTag; //内容的开始点
	var $footSeparatedTag; //内容的结束点
/**
 * 载入网页内容
 * @param $fileURL 网页文件地址
 */
	function getHmtlAllContent($fileURL, $inputCharSet="", $outCharSet="", $domGetType="dom", $heardSeparatedTag="", $footSeparatedTag=""){
		if($inputCharSet!=""){
			$this->inputCharSet = $inputCharSet;
		}
		if($outCharSet!=""){
			$this->outCharSet = $outCharSet;
		}
		
		$this->dom = new DOMDocument;//new DOMDocument("1.0","utf-8");
		$this->dom->preserveWhiteSpace = false;
		$this->domGetType = $domGetType;
		$this->heardSeparatedTag = $heardSeparatedTag;
		$this->footSeparatedTag = $footSeparatedTag;
		
		if($this->heardSeparatedTag!="" || $this->footSeparatedTag!=""){ //如要切掉头尾，用curl方式截取
			$this->domGetType = "curl";
		}
		
		if($this->domGetType=="curl"){
			$dm = $this->cUrlGet($fileURL);
			$dm = mb_convert_encoding($dm, 'HTML-ENTITIES', $this->inputCharSet); //此处转换编码，非常重要。
			if($this->heardSeparatedTag!=""){
				$hTag = mb_convert_encoding($this->heardSeparatedTag, 'HTML-ENTITIES', $this->inputCharSet);
				$dm = preg_replace('@^.*'.$hTag.'@si','',$dm);
			}
			if($this->footSeparatedTag!=""){
				$fTag = mb_convert_encoding($this->footSeparatedTag, 'HTML-ENTITIES', $this->inputCharSet);
				$dm = preg_replace('@'.$fTag.'.*@si','',$dm);
			}
			
			if(@$this->dom->loadHTML($dm)==false){
				sleep(2);
				$this->getHmtlAllContent($fileURL, $inputCharSet, $outCharSet, $domGetType, $heardSeparatedTag, $footSeparatedTag);
			}
			//echo '['.$dm.']';
			//exit;
		}else{
			if(@$this->dom->loadHTMLFile($fileURL)==false){
				sleep(2);
				$this->getHmtlAllContent($fileURL, $inputCharSet, $outCharSet, $domGetType, $heardSeparatedTag, $footSeparatedTag);
			}
		}
		//echo $this->domGetType;
	}
	
/**
 * 在已载入的网页内容中获取我们需要的那些内容
 * @param $tagName 目标的标签
 * @param $attrName 目标的属性 如id、class等
 * @param $attrValue目标的属性的值
 * @param $filterTagName排除一些标签(支持数组)
 * @param $filterAttrName被排除的那些标签的属性(支持数组)
 * @param $filterAttrValue被排除的那些标签的属性值(支持数组)
 * @param $getRange 如果出现重复的标签内容是否抓取所有内容，如果该为0则抓取全部，默认为0；否则根据$getRange的值来抓取，格式如1,3,5-9,12等
 */
	function getTags($tagName, $attrName="", $attrValue="", $filterTagName="", $filterAttrName="", $filterAttrValue="", $getRange=0){
		$html = '';
		//$dom = new DOMDocument("1.0","utf-8");
		//$dom->preserveWhiteSpace = false;
		//@$dom->loadHTMLFile($fileURL);
		
		$domxpath = new DOMXPath($this->dom);
		$newDom = new DOMDocument;
		$newDom->formatOutput = true;
		if($attrName!=""){
			$filtered = $domxpath->query("//".$tagName. '[@' . $attrName . "='".$attrValue."']");
		}else{
			$filtered = $domxpath->query("//".$tagName);
		}
		
		// $filtered =  $domxpath->query('//div[@class="className"]');
		// '//' when you don't know 'absolute' path
	
		// since above returns DomNodeList Object
		// I use following routine to convert it to string(html); copied it from someone's post in this site. Thank you.
		
		$ns = array();
		if($getRange!=0 && $getRange!=""){
			$tmpArray = explode(',', $getRange);
			foreach($tmpArray as $key => $val){
				$val = trim($val);
				if(preg_match('/^\d+$/',$val)){
					$ns[]=($val-1);
				}else{
					$nArray = explode('-',$val);
					$st = min($nArray[0],$nArray[1]);
					$et = max($nArray[0],$nArray[1]); 
					for($j=$st; $j<=$et; $j++){
						$ns[]=($j-1);
					}
				}
			}
		}else{
			$ns[0]=0;
		}
		
		$i = 0;
		while( $myItem = $filtered->item($i++) ){
			$_i = $i-1;
			if(!in_array($_i, $ns) && $getRange!="0" && $getRange!=""){
				continue;
			}
			
			$node = $newDom->importNode( $myItem, true );    // import node
			$newDom->appendChild($node);                    // append node
		}
		$html = $newDom->saveHTML();
		//过滤js和css样式表代码
		$fp = array('@<script[^>]*?>.*?</script>@si',
					'@<noscript[^>]*?>.*?</noscript>@si',
					'@<style[^>]*?>.*?</style>@si'
					);
		$html = preg_replace($fp,'',$html);
		//过滤部分元素(支持字符及数组)
		if($filterTagName!=""){
			if(is_array($filterTagName)){
				$filterTagNames = $filterTagName;
				$filterAttrNames = $filterAttrName;
				$filterAttrValues = $filterAttrValue;
			}else{
				$filterTagNames[0] = $filterTagName;
				$filterAttrNames[0] = $filterAttrName;
				$filterAttrValues[0] = $filterAttrValue;
			}
			for($i=0, $n=sizeof($filterTagNames); $i<$n; $i++){
				$fDom =  new DOMDocument();
				@$fDom->loadHTML($html);
				$root = $fDom -> documentElement;
				foreach($root->getElementsByTagName($filterTagNames[$i]) as $elem) {
					if($filterAttrNames[$i]=="" || $elem->getAttribute($filterAttrNames[$i])==$filterAttrValues[$i]){
						$elem->parentNode->removeChild($elem);
						$i--;
						//echo $filterTagName.":".$filterAttrName.":".$_AttrVal.":".$filterAttrValue."<hr />";
					}
				} 
				$html = $fDom->saveHTML();
			}
		}
		
		if(function_exists('mb_convert_encoding')){
			$html = mb_convert_encoding($html, 'utf-8', 'HTML-ENTITIES');
		}else{
			$html = $this->html_to_utf8($html);
		}
		
		if(strtolower($this->inputCharSet)!="utf-8" && $this->inputCharSet!=""){
			$html = iconv('utf-8',$this->inputCharSet.'//IGNORE', $html);
		}
		if(strtolower($this->inputCharSet) != strtolower($this->outCharSet)){
			$html = iconv($this->inputCharSet ,$this->outCharSet.'//IGNORE', $html);
		}
		
		return $html;
	}
	
	//将网页编码转成utf8
	function html_to_utf8 ($data){
		return preg_replace("/\\&\\#([0-9]{3,10})\\;/e", '$this->_html_to_utf8("\\1")', $data);
	}
	
	function _html_to_utf8 ($data){
		if ($data > 127)
			{
			$i = 5;
			while (($i--) > 0)
				{
				if ($data != ($a = $data % ($p = pow(64, $i))))
					{
					$ret = chr(base_convert(str_pad(str_repeat(1, $i + 1), 8, "0"), 2, 10) + (($data - $a) / $p));
					for ($i; $i > 0; $i--)
						$ret .= chr(128 + ((($data % pow(64, $i)) - ($data % ($p = pow(64, $i - 1)))) / $p));
					break;
					}
				}
			}
			else
			$ret = "&#$data;";
		return $ret;
	}
	//用cURL方式取得数据
	function cUrlGet($url){
		// 初始化一个 cURL 对象
		$curl = curl_init();
		// 设置你需要抓取的URL
		curl_setopt($curl, CURLOPT_URL, $url);
		// 设置header
		curl_setopt($curl, CURLOPT_HEADER, 1);
		// 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		// 运行cURL，请求网页
		$data = curl_exec($curl);
		// 关闭URL请求
		curl_close($curl);
		// 显示获得的数据
		return $data;
	}
	
}
?>