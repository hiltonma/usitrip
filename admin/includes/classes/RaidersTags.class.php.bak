<?php
/**
 * 攻略标签类
 * @author wtj
 * @date 2013-11-6
 */
class RaidersTags extends T{
	function __construct(){
		$this->setTableName('raiders_tags');
		$this->setIdName('tags_id');
	}
	/**
	 * (non-PHPdoc)
	 * @see T::set()
	 */
	function set($arr){
		switch($this->select_type){
			case 1 : //后台产品更新的时候获取获得相应ID 
				if($arr){
				$str_sql="SELECT rat.article_tags_id,rt.tags_id,rt.tags_name,rt.tags_uid FROM raiders_tags rt,raiders_article_tags rat WHERE rat.tags_id=rt.tags_id AND rat.article_id=".$arr;
				$this->setStrSql($str_sql);
				}
				break;
			case 2 : //通过几个id查询标签表的一些信息
				if($arr){
				$str_sql='SELECT tags_id,tags_name,tags_uid FROM '.$this->table.' WHERE tags_id IN ('.$arr.')';
				$this->setStrSql($str_sql);
				}
				break;
			case 3 :
				$str_sql='SELECT tags_id,tags_name,tags_uid FROM '.$this->table.' WHERE tags_name like "%'.$arr.'%" LIMIT 8';
				$this->setStrSql($str_sql);
				break;
			case 4: //后台显示
				$this->setFileds('tags_id,tags_name,tags_url');
				$this->setOderBy('tags_id');
				break;
		}
	}
	/**
	 * 获取后台列表页
	 * @param string $tags_name 标签名称
	 * @param string $url 标签的URL
	 * @return array
	 */
	function getBackList($tags_name,$url){
		$str_sql='SELECT tags_id,tags_name,tags_url FROM '.$this->table.' WHERE 1';
		$tags_name?$str_sql.=' AND tags_name like "%'.$tags_name.'%" ':'';
		$url?$str_sql=' AND tags_url like "%'.$url.'%"':'';
		$this->setStrSql($str_sql);
		return $this->getPageList();
	}
	/**
	 * 获取文章的标签
	 * @param string $str_tags 传入的标签ID string like 1,2,3
	 * @return void|string
	 */
	function getArticleTags($str_tags){
		$arr_tmp=explode(',', $str_tags);
		$arr=array();
		foreach($arr_tmp as $value){
			if($value)
			$arr[$value]=$value;
		}
		$str_need=join(',', $arr);
		if(!$str_need)
			return;
		$this->setGetType(2);
		$arr_info=$this->getList($str_need);
		$str='';
		foreach($arr_info as $value){
			$str.=','.$value['tags_uid'];
		}
		return $str;
	}
	/**
	 * 修改文章的标签
	 * @param int $article_id 文章ID
	 * @param string $str_tags 
	 */
	function changeArticleTags($article_id,$str_tags){
		if(!str_tags)return;
		$str_need='';
		$arr=$arr_need=array();
		$str_sql='DELETE FROM raiders_article_tags WHERE article_id='.$article_id;
		tep_db_query($str_sql);
		$arr=explode(',', $str_tags);
		foreach($arr as $value){
			if($value){
			$arr_need[$value]=$value;
			}
		}
		foreach($arr_need as $value){
			if($value)
			$str_need.=",($value,$article_id)";
		}
		if($str_need){
		$str_sql='INSERT INTO raiders_article_tags(tags_id,article_id)VALUES'.substr($str_need, 1);
		tep_db_query($str_sql);
		}
	}
	function showTags($tags_name){
		$this->setGetType(3);
		$arr=$this->getList($tags_name);
		foreach($arr as $key=>$value){
			$arr[$key]['tags_name']=iconv(CHARSET, 'UTF-8', $value['tags_name']);
		}
		return $arr;
	}
	/**
	 * 删除
	 * @param int $tags_id 标签ID
	 * @return boolean
	 */
	function drop($tags_id){
		$str_sql='SELECT article_tags_id FROM raiders_article_tags WHERE tags_id='.$tags_id.' LIMIT 1';
		if($this->doSelect($str_sql)){
			return false;
		}else{
			$this->dropOne($tags_id);
			return true;
		}
	}
	/**
	 * 检查是否加了http
	 * @param string $url tags
	 * @return string
	 */
	static function checkTagsUrl($url){
		$str=substr($url, 0,4);
		if(strtolower($str)!='http'){
			return 'http://'.$url;
		}else{
			return $url;
		}
	}
	/**
	 * 单独拿出UID的组合
	 * @param array $arr tags的数组
	 * @return array
	 */
	static function downTagsArray($arr){
		$arr_return=array();
		foreach($arr as $value){
			$arr_return[]=$value['tags_uid'];
		}
		return $arr_return;
	}
	function getArticleShow($article_id){
		$str_sql="SELECT rt.tags_id,rt.tags_name FROM ".$this->table.' rt,raiders_article_tags rat WHERE rat.tags_id=rt.tags_id AND rat.article_id='.$article_id;
		return $this->doSelect($str_sql);
	}
}