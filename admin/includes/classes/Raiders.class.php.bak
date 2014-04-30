<?php
class Raiders extends T{
	function __construct(){
		$this->setIdName('article_id');
		$this->table='raiders_article';
	}
	function set($arr){
		switch ($this->select_type){
			case 1 : //首页显示 
// 				$this->setFileds('article_id,article_title,add_time,article_type');
// 				$this->setOderBy('add_time DESC');
// 				$this->setLimit(8);
				$str_sql='SELECT ra.article_id,ra.article_title,ra.add_time,ra.article_type,rt.parent_id FROM '.$this->table.' ra,raiders_type rt WHERE ra.article_type=rt.type_id AND ra.is_show=1 ORDER BY add_time DESC LIMIT 8 ';
// 				$this->data['is_show']=1;
				$this->setStrSql($str_sql);
				break;
			case 2 : //列表页，前台显示
				$this->setFileds('article_id,article_title,add_time');
				$this->setOderBy('article_id DESC');
				$this->data['is_show']=1;
				$this->data['article_type']=$arr;
				break;
			case 3 ://后台列表页
				$str_sql='SELECT ra.article_id,ra.article_title,ra.article_from,ra.add_time,ra.is_show,rp.type_name,ra.add_user,ra.article_desc,ra.article_key_words,ra.release_time FROM raiders_article ra ,raiders_type rp WHERE ra.article_type=rp.type_id ';
// 				$str_sql="select * from ".$this->table;
				(isset($_GET['title'])&&$_GET['title'])?$str_sql.=' AND ra.article_title like "%'.$_GET['title'].'%"':'';
				isset($_GET['type'])?$str_sql.=' AND  ra.article_type='.(int)$_GET['type']:'';
				(isset($_GET['is_show'])&&$_GET['is_show']!=='')?$str_sql.=' AND ra.is_show='.(int)$_GET['is_show']:'';
				(isset($_GET['add_user'])&&$_GET['add_user'])?$str_sql.=' AND ra.add_user='.(int)$_GET['add_user']:'';
// 				$str_sql.=' GROUP BY ra.article_id';
				$str_sql.=' ORDER BY ra.article_id DESC';
// 				echo $str_sql;
				$this->setStrSql($str_sql);
				break;
			case 4 ://推荐
				if(!$arr)
					return 0;
				$str_sql='SELECT rt.tags_number from raiders_tags rt,raiders_article_tags rat WHERE rat.tags_id=rt.tags_id AND rat.article_id= '.$arr['article_id'];
				$this->setStrSql($str_sql);
				break;
		}
		
	}
	/**
	 * 获取列表显示
	 * @param string $type_str type_id string like 1,2,3
	 * @param int $type_id 类型
	 * @return string
	 */
	function getIndexList($type_str,$type_id=0){
		if($type_id){
			$str_sql='SELECT article_id,article_title,add_time FROM '.$this->table.' WHERE is_show=1 AND article_type='.$type_id.' ORDER BY article_id DESC';
		}else if($type_str){
			$str_sql='SELECT article_id,article_title,add_time FROM '.$this->table.' WHERE article_type IN ('.$type_str.') AND is_show=1 ORDER BY article_id DESC';
			
		}else{
			$str_sql='SELECT article_id,article_title,add_time FROM '.$this->table.' WHERE is_show=1 ORDER BY article_id DESC';
		}
		$row_max = TRAVEL_LIST_MAX_ROW;
		$row_max = 20;	//每页显示几行
		$reply_split = new splitPageResults($str_sql, $row_max);
		$rows_count = $reply_split->number_of_rows;	//记录总数
		$rows_count_html_code = $reply_split->display_count(TEXT_DISPLAY_NUMBER_OF_REVIEWS);	//记录总数的信息页面
		$rows_count_pages = $reply_split->number_of_pages; //总页数
		$current_page = $reply_split->current_page_number; //当前页号
		$rows_page_links_code = $reply_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info'))); //翻页连接代码
		$info['info']=$this->doSelect($reply_split->sql_query);
		$info['page']=html_to_db($rows_page_links_code);
		return $info;
	}
	/**
	 * 删除文章的TAGS
	 */
	function dropTags($rct_id,$uid,$r_id){
		$str_sql='UPADATE '.$this->table.' SET tags_id_str=REPLACE(tags_id_str,",'.$uid.',",",") WHERE article_id='.$r_id;
		tep_db_query($str_sql);
		$str_sql='DELETE FROM raiders_article_tags WHERE article_tags_id='.$rct_id;
		tep_db_query($str_sql);
		return 1;
	}
	/**
	 * 通过tep_href_link生成URL
	 * @param string $page_name 页面名称
	 * @param string $parameters 后来传入的参数
	 * @return string
	 */
	static function outUrl_($page_name,$parameters){
		if($parameters){
		$parameters=str_replace('=', '_usi_', $parameters);
		$parameters=str_replace('&', '_trip_', $parameters);
		$page_name=str_replace('.php', '', $page_name);
		return $page_name.'__'.$parameters.'.html';
		}else{
			return str_replace('.php', '.html', $page_name);
		}
		
	}
	static function outUrl($page_name,$parameters){
		if($parameters){
			$parameters=str_replace('.html', '', $parameters);
			$parameters=str_replace('parent_id=', '_u', $parameters);
			$parameters=str_replace('type_id=', '_s', $parameters);
			$parameters=str_replace('article_id=', '_r', $parameters);
			$parameters=str_replace('page=', '_w', $parameters);
			$parameters=str_replace('&', '_t', $parameters);
			$page_name=str_replace('.php', '', $page_name);
			$page_name=str_replace( 'raiders_list','rl', $page_name);
			$page_name=str_replace( 'raiders_info','ri', $page_name);
			return $page_name.'_z'.$parameters.'.html';
		}else{
			$page_name=str_replace( 'raiders_list','rl', $page_name);
			$page_name=str_replace( 'raiders_info','ri', $page_name);
			return str_replace('.php', '.html', $page_name);
		}
	}
	/**
	 * 404页面解析 
	 * @param string $url url地址
	 * @return string;
	 */
	static function inUrl($url){
		$arr_tmp=explode('__', $string);
		if(count($arr_tmp)>1){
			$page_name=$arr_tmp[0];
			$parameters=$arr_tmp[1];
			$parameters=str_replace('_usi_', '=', $parameters);
			$parameters=str_replace('_trip_','&' , $parameters);
			$parameters=str_replace('.html','' , $parameters);
			$str_return=$page_name.'.php?'.$parameters;
		}else{
			$str_return=str_replace('.html', '.php', $url);
		}
		return $str_return;
	}
	/**
	 * 网站攻略的地图
	 * @return string
	 */
	function getMap(){
		$url='';
		$xml='';
		$xml .= '<url>';
		$xml .= '<loc>raiders_list.html</loc>';
		$xml .= '<priority>0.6</priority>';
		$xml .= '</url>';
		$str_sql='SELECT type_id,parent_id FROM raiders_type';
		$query=tep_db_query($str_sql);
		while($row=tep_db_fetch_array($query)){
			if($row['parent_id'])
			$parameters='type_id='.$row['type_id'].'&parent_id='.$row['parent_id'];
			else
				$parameters='parent_id='.$row['type_id'];
			$url=tep_href_link('raiders_list.php', $parameters);
			$xml .= '<url>';
			$xml .= '<loc>'.$url.'</loc>';
			$xml .= '<priority>0.6</priority>';
			$xml .= '</url>';
		}
		$str_sql='SELECT ra.article_id,ra.article_type,rt.parent_id FROM raiders_article ra, raiders_type rt WHERE ra.article_type=rt.type_id';
		$query=tep_db_query($str_sql);
		while($row=tep_db_fetch_array($query)){
			$url=tep_href_link('raiders_info.php','parent_id='.$row['parent_id'].'&type_id='.$row['article_type'].'&article_id='.$row['article_id']);
			$xml .= '<url>';
			$xml .= '<loc>'.$url.'</loc>';
			$xml .= '<priority>0.6</priority>';
			$xml .= '</url>';
		}
		return $xml;
	}
	/**
	 * 推荐
	 * @param int $article_id 文章ID
	 * @param array $tags_arr tags uid 的集合
	 * @param int $type_id 类型ID
	 */
	function recommend($article_id,$tags_arr,$type_id){
		$tags_uid=$this->getUidMix($tags_arr);
		$number=8;
		$article_id_array=$arr_return=array();
		$article_id_array[]=$article_id;
		foreach($tags_uid as $value){
			$str_sql='SELECT article_id,article_title,article_type FROM '.$this->table.' WHERE 1 AND is_show=1 ';
			$str_sql.=$this->createFullTextTags($value);
			$str_sql.=' AND article_id NOT IN ('.join(',',$article_id_array).')';
			$str_sql.=' LIMIT '.$number;
			$arr_tmp=$this->doSelect($str_sql);
			foreach($arr_tmp as $v){
				$article_id_array[]=$v['article_id'];
				$arr_return[]=$v;
				$number--;
			}
		}
		if($number>0){
			$str_sql='SELECT article_id,article_title,article_type FROM '.$this->table.' WHERE article_type='.$type_id.' AND is_show=1 AND article_id NOT IN ('.join(',',$article_id_array).') LIMIT '.$number;
			$arr_tmp=$this->doSelect($str_sql);
			foreach($arr_tmp as $v){
				$article_id_array[]=$v['article_id'];
				$arr_return[]=$v;
				$number--;
			}
		}
		if($number>0){
			$str_sql='SELECT article_id,article_title,article_type FROM '.$this->table.' WHERE  article_id NOT IN ('.join(',',$article_id_array).') AND is_show=1 LIMIT '.$number;
			$arr_tmp=$this->doSelect($str_sql);
			foreach($arr_tmp as $v){
				$article_id_array[]=$v['article_id'];
				$arr_return[]=$v;
				$number--;
			}
		}
		return $arr_return;
	}
	/**
	 * 创建全文搜索需要的SQL字段
	 * @param array $tags_uid_arr tags_uid 的数组
	 * @return string
	 */
	function createFullTextTags($tags_uid_arr){
		$need_str='';
		foreach($tags_uid_arr as $value){
			$need_str.=' AND MATCH (tags_id_str) AGAINST ("'.$value.'")';
		}
		if($need_str)
			return $need_str;
		else 
			return '';
// 		return substr($need_str,5);
	}
	/**
	 * 获取所有的UID组合
	 * @param array $arr 一维的uid数组
	 */
	function getUidMix($arr){
		$mark=0;
		$need=array();
		foreach($arr as $value){
			foreach($need as $v){
				$need[]=array_merge(array($value),$v);
			}
			$need[]=array($value);
		}
		return $this->OrderByArrayCout($need);
	}
	/**
	 * 对数组的子数组的大小进行排序
	 * @param array $arr
	 * @return array
	 */
	function OrderByArrayCout($arr){
		$need=$return=array();
		foreach($arr as $key=>$value){
			$need[$key]=count($value);
		}
		arsort($need);
		foreach($need as $key=>$value){
			$return[$key]=$arr[$key];
		}
		return $return;
	}
	/**
	 * 获取文章的标签
	 * @param unknown_type $article_id
	 * @return multitype:multitype:
	 */
	function getAticleTags($article_id){
		$str_sql='SELECT rt.tags_name,rt.tags_url,rt.tags_uid FROM raiders_article_tags rat,raiders_tags rt WHERE rat.tags_id=rt.tags_id AND rat.article_id= '.$article_id;
		return $this->doSelect($str_sql);
	}
	/**
	 * 获取一篇文章的信息
	 * @param unknown_type $article_id
	 * @param unknown_type $type_id
	 * @param unknown_type $parent_id
	 * @return Ambigous <string, multitype:>
	 */
	function getOneInfo($article_id,$type_id,$parent_id,$is_test=''){
		$info['info']=$this->getOne(' article_id='.$article_id.(($is_test!=1)?' AND is_show=1 ':''));
		$info['tags']=$this->getAticleTags($article_id);
		if($type_id){
			$str_sql='SELECT article_id,article_title FROM '.$this->table.' WHERE article_type='.(int)$type_id.' AND is_show=1 ';
		}elseif($parent_id){
			$str_sql='SELECT article_id,article_title FROM '.$this->table.' WHERE article_type IN('.$parent_id.') '.' AND is_show=1 ';
		}else{
			
		}
		$str_sql_ago.=$str_sql.' AND article_id<'.(int)$article_id.' LIMIT 1';
// 		$this->setStrSql($str_sql_ago);
		$tmp=$this->doSelect($str_sql_ago);
		if($tmp){
		$info['ago']=$tmp[0];
		}else{$info['ago']='';}
		$str_sql_after.=$str_sql.' AND article_id>'.(int)$article_id.' LIMIT 1';
// 		$this->setStrSql($str_sql_after);
		$tmp=$this->doSelect($str_sql_after);
// 		$tmp=$this->getList();
		if($tmp){
			$info['after']=$tmp[0];
		}else{$info['after']='';
		}
		return $info;
	}
	/**
	 * 更改在线状态
	 * @param unknown_type $article_id
	 * @param unknown_type $value
	 */
	function changeInline($article_id,$value){
		$str_sql='UPDATE '.$this->table.' SET is_show='.$value.' WHERE article_id='.$article_id;
		tep_db_query($str_sql);
	}
	/**
	 * 删除文章
	 * @param int $article_id 文章ID
	 */
	function dropArticle($article_id){
		$str_sql='DELETE FROM '.$this->table.' WHERE article_id='.(int)$article_id;
		tep_db_query($str_sql);
		$this->dropArticleTags($article_id);
	}
	/**
	 * 删除文章标签
	 * @param int $article_id 文章ID
	 */
	function dropArticleTags($article_id){
		$str_sql='DELETE FROM raiders_article_tags WHERE article_id='.(int)$article_id;
		tep_db_query($str_sql);	
	}
}