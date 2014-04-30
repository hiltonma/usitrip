<?php
/**
 *MCache Module
 *进入页面 
 *$mcache->fetch('product',array('id'=>1));
 *返回false =>没有该缓存 需要更新缓存 
 *$mcache->insert('product',array('id'=>1));
 *@author vincent
 *@version alpha
 *@package cn.usitrip.com
 **/
if(!defined('USE_MCACHE')) define('USE_MCACHE' , true);
if(!defined('MCACHE_DIR'))define('MCACHE_DIR',DIR_FS_CATALOG.'mcache');
if(!defined('MCACHE_LOG'))define('MCACHE_LOG',false);
if(!defined('MCACHE_LOG_FILE'))define('MCACHE_LOG_FILE',DIR_FS_CATALOG.'logs/mcache.log');
class MCache {
	const HOURS = 3600;
	const DAY = 86400;
	const MINUTE = 60;
	const WEEK = 604800;
	const MONTH = 2592000;
	/**
	 * PHP cache 存放文件夹
	 * @var unknown_type
	 */
	protected  $cachePath = MCACHE_DIR;
	/**
	 * 使用缓存的次数
	 * @var unknown_type
	 */
	protected $stat_hit = 0 ;
	/**
	 * 生成缓存的次数
	 *  @var unknown_type
	 */
	protected $stat_add = 0 ;	
	/**
	 * 缓存数据
	 * @var array 
	 */
	protected $data = array();
	/**
	 * 需要新加入的缓存
	 * @var array
	 */
	protected $_need_save = array();	
	protected $_need_delete = array();
	protected $_logfp = null;
	protected $use_mcache = USE_MCACHE;
	protected $_loaded = array() ;
	protected $_reqtimes = 0 ;
	private static $_self = null;
	/**
	 * 设置一些需要保存到其他地方的key
	 */
	protected $_exclude = array('categories_list_base'=>'qcache','site_main_config'=>'qcache');
	
	private function __construct(){			
	}	
	function __destruct(){
		if(USE_MCACHE){ //关闭缓存 不保存缓存文件
			foreach($this->_need_save as $inf){			
				$this->saveTo($inf['path'], $inf['content']);
			}
		}
		foreach($this->_need_delete as $inf){
				$unlink = @unlink($inf['path']);
		}
			if( is_resource($this->_logfp)){
				@fclose($this->_logfp);
		}
	}
	static public function instance(){
		if(self::$_self == null){
			self::$_self = new MCache();
		}
		return self::$_self;
	}
	
	public static function stat(){
		$mcache = MCache::instance();	
		$filesize = 0 ;
		foreach($mcache->_loaded as $f){
			$filesize+= filesize($f);
		}
		$filesize = (round(($filesize/1024)*100)/100).'K';		
		return array(
			'命中次数'=>$mcache->stat_hit ,
			'创建缓存'=>$mcache->stat_add,
			'缓存目录'=>basename($mcache->cachePath),
			'缓存数量'=>count($mcache->data),
			'待删除文件'=>count($mcache->_need_delete),
			'待保存文件'=>count($mcache->_need_save),
			'开关'=>(int)$mcache->use_mcache,
			'请求数'=>$mcache->_reqtimes,
			'载入文件尺寸'=>$filesize
		);
	}
	/**
	 * 记录日志
	 * @param unknown_type $msg
	 */
	public function log($msg){		
		if(!MCACHE_LOG) return ;
		$log = date('Y-m-d H:i:s ',time()).' '.$msg."\n";
		if($this->_logfp == null){
			$this->_logfp = @fopen(MCACHE_LOG_FILE,'a');
		}
		@fwrite($this->_logfp, $log);
	}
	/**
	 * 将缓存插入到缓存数组
	 * @param string $cacheId
	 * @param mix $content
	 */
	public function add($key , $content){
		//if(!USE_MCACHE) return ;	//$this->log("add ".$key);
		$this->data[$key] = $content ;		
		if(array_key_exists($key, $this->_exclude))
		{}
		else{//保存为php array
			$this->_need_save[] = array(
				'path'=>$this->keyToPath($key,false),
				'content'=>"<?php \n//MCache File don't modify-vincent-".date('Y-m-d H:i:s',time())." \n return ".var_export($content ,true).";\n?>"
			);
		}
		$this->stat_add += 1 ;
	}
	public static  function cacheExists($key){
		return isset(self::instance()->data[$key]) ;
	}
	/**
	 * 销毁缓存,删除缓存文件\
	 * 关闭缓存时可以删除缓存文件
	 * @param string $cacheId
	 */
	public function destory($key){		
		if(array_key_exists($key, $this->_exclude)){
			return ;
		}	
		/*if(!preg_match('/[a-z0-9_]+/i', $key)){	}*/
		unset($this->data[$key]);
		//if(MCACHE_LOG)$this->log("destory ".$key);
		$inf = array('key'=>$key , 'path'=>$this->keyToPath($key));
		if(!isset($this->_need_delete[$key])){
			$this->_need_delete[$key] =  $inf ;
		}
		//@unlink($file);
		//$this->log($file);
	}
	
	/**
	 * 检查主目录是否有效 无效的话就报错
	 * 根据目录创建文件夹，如果目录不存在则自动创建
	 * @param string $path
	 */
	private function saveTo($path ,$content){
		if(!is_dir($this->cachePath) || !is_writable ($this->cachePath)){
			//if(MCACHE_LOG)$this->log('['.$this->cachePath.'] not exists or not writeable');
			//throw new Exception($this->cachePath.' not exists or not writeable');
			$this->use_mcache = false;
		}
		$filefullpath = $this->cachePath.'/'.$path;
		$filepath = dirname($filefullpath);	
		if(!file_exists($filepath)){
			$pathParts = explode('/',$path);
			array_pop($pathParts);
			$pathPartsLen = count($pathParts);	
			$newFilePath  = $this->cachePath.'/';
			for($i = 0;$i < $pathPartsLen;$i++){
				$newFilePath  .= $pathParts[$i];
				if(!file_exists($newFilePath)){
					@mkdir($newFilePath);
					//if(MCACHE_LOG)$this->log("mkdir ".$newFilePath);
				}
				if($i< $pathPartsLen - 1) $newFilePath  .= '/';
			}
		}
		$fp = @fopen($filefullpath , 'w');
		$writed = @fwrite($fp, $content );
		@fclose($fp);
	}
	/**
	 * 生成缓存的KEY
	 * @param string $type
	 * @param mix $param
	 */
	static public function key($type , $param){		
		return $type;
	}
	/**
	 * 将key映射到相关的缓存文件
	 * @param string $key
	 * @param boolean $fullpath 是否包含全部路径
	 */
	private function keyToPath($key , $fullpath =true){
		$key = strtolower($key);
		$keyPrefix = substr($key , 0 , strpos($key , '_'));
		
		if($keyPrefix != ''){
			$rfullpath =  $this->cachePath.'/'.$keyPrefix.'/'.$key.'.cache.php' ;
			$rpath = $keyPrefix.'/'.$key.'.cache.php' ;
		}else{
			$rfullpath = $this->cachePath.'/'.$key.'.cache.php' ;
			$rpath = $key.'.cache.php' ;
		}
		return $fullpath?  $rfullpath : $rpath ;
	}
	/**
	 * 输入key 返回缓存,如果为为null则读取缓存失败
	 * @param unknown_type $key
	 * @param unknown_type $expireTime
	 */
	public function fetch($key , $expireTime = 0){		
		if(!USE_MCACHE) return null;
		$this->_reqtimes++ ;
		if(isset($this->data[$key])){
			$this->stat_hit = $this->stat_hit+ 1 ;//if(MCACHE_LOG) $this->log("hit:".$key);
			return $this->data[$key];
		}else{
			$path = $this->keyToPath($key);
			if(file_exists($path)){
				if($expireTime > 0){
					$load = time() - filemtime($path)  > $expireTime?false:true;	
				}else {
					$load = true ;
				}
				if($load === true){
					/*
					if(MCACHE_LOG){
						$this->_loaded[] =$path;
					}*/
					$content = include($path);
					$this->stat_hit = $this->stat_hit+ 1 ;
					$this->data[$key] = $content ; 
					return $content ;
				}else{
					return null;
				}
			}else
			{
				return null;
			}
		}
	}
	/**
	 * 检查$data中是否包含$need需要的栏位
	 * $need可以是字符串或者数组 c.xxx 会将c去掉
	 * @param unknown_type $data
	 * @param unknown_type $need
	 * @author vincent
	 * @modify by vincent at 2011-4-26 下午03:49:37
	 */
	static public function fieldCheck($data , $need){
		if(is_string($need) && strpos($need , ',')){
			$need = explode(',',$need);
		}
		$need = (array)$need;	
		$missed= array();
		foreach($need as $key =>$field){					
			$pos = stripos($field, ' as ');
			if($pos !== false) $field = substr($field,0,$pos);	
			$field = trim($field);			
			if(strpos($field , '.')!== false ){
				$f = explode('.',$field);
				$field = $f[count($f)-1];				
			}
			if(!array_key_exists($field, $data)){
				$missed[] = $field ;
			}
		}
		if(empty($missed)){
			echo "fieldCheck:All Founded";
		}else{
			echo "fieldCheck:Miss ".implode(',',$missed);
		}
	}
	
	//------------------------------------------------------------------------------------------------------------------------------------------------
	/**
	 * 返回分类缓存的内容
	 * 缓存不存在则创建
	 */
	public function _categories_base(){		
		$mcache = self::instance();
		$mcache->_reqtimes++;
		if(isset($mcache->data['categories_list_base'])){
			$mcache->stat_hit++;
			return $mcache->data['categories_list_base'];
		}else
		{
			$categories_query = tep_db_query('select '
			.'c.categories_id,c.categories_urlname,c.categories_image,c.parent_id,c.sort_order,c.categories_status,c.categories_status_for_tc_bbs,c.categories_status_for_tc_bbs_display,c.categories_feature_status,c.categories_map_image,c.categories_recommended_tours_ids,c.categories_banner_image,c.catalog_source,c.categories_top_attractions,c.categories_top_attractions_tourtab,c.categories_more_dept_cities,c.categories_destinations'
			.',cd.language_id,cd.categories_name,cd.categories_heading_title,cd.categories_description,cd.categories_seo_description,cd.categories_logo_alt_tag,cd.categories_first_sentence,cd.categories_head_title_tag,cd.categories_head_desc_tag,cd.categories_head_keywords_tag,cd.categories_recommended_tours,cd.categories_map,cd.categories_video,cd.categories_video_description,cd.categories_top_banner_image_alt_tag'
			.' from '. TABLE_CATEGORIES.' c ,'.TABLE_CATEGORIES_DESCRIPTION.' cd WHERE c.categories_id =cd.categories_id and c.categories_status=1 Order BY c.parent_id,c.sort_order, cd.categories_name');
			$cats = array();
			while ($row = tep_db_fetch_array($categories_query)) {
				$cats[$row['categories_id']] = $row;
			}
			$mcache->data['categories_list_base'] = $cats;
			return $cats;			
		}
	}
	/*
	public function _categories_base(){		
		$cats = $this->fetch('categories_list_base');
		if($cats == null){			
			/*$categories_query = tep_db_query('select '
			.'c.categories_urlname,c.categories_id,c.categories_image'
			.',cd.categories_name,c.parent_id,cd.language_id,cd.categories_heading_title,cd.categories_description,cd.categories_seo_description,cd.categories_logo_alt_tag
			,cd.categories_first_sentence,cd.categories_head_title_tag,cd.categories_head_desc_tag,
			cd.categories_head_keywords_tag,cd.categories_recommended_tours,cd.categories_map,cd.categories_video,
			cd.categories_video_description,cd.categories_top_banner_image_alt_tag'
			.' from '. TABLE_CATEGORIES.' c ,'.TABLE_CATEGORIES_DESCRIPTION.' cd WHERE c.categories_id =cd.categories_id order by c.parent_id,c.sort_order, cd.categories_name');
			$categories_query = tep_db_query('select '
			.'c.categories_id,c.categories_urlname,c.categories_image,c.parent_id,c.sort_order,c.categories_status,c.categories_status_for_tc_bbs,c.categories_status_for_tc_bbs_display,c.categories_feature_status,c.categories_map_image,c.categories_recommended_tours_ids,c.categories_banner_image,c.catalog_source,c.categories_top_attractions,c.categories_top_attractions_tourtab,c.categories_more_dept_cities,c.categories_destinations'
			.',cd.language_id,cd.categories_name,cd.categories_heading_title,cd.categories_description,cd.categories_seo_description,cd.categories_logo_alt_tag,cd.categories_first_sentence,cd.categories_head_title_tag,cd.categories_head_desc_tag,cd.categories_head_keywords_tag,cd.categories_recommended_tours,cd.categories_map,cd.categories_video,cd.categories_video_description,cd.categories_top_banner_image_alt_tag'
			.' from '. TABLE_CATEGORIES.' c ,'.TABLE_CATEGORIES_DESCRIPTION.' cd WHERE c.categories_id =cd.categories_id Order BY c.parent_id,c.sort_order, cd.categories_name');
			$cats = array();
			while ($row = tep_db_fetch_array($categories_query)) {
				$cats[$row['categories_id']] = $row;
			}
			$this->add('categories_list_base',$cats);
		}
		return $cats ;
	}	*/
	/**
	 * 过滤需要的数组栏位
	 * @param unknown_type $rawArray
	 * @param unknown_type $filter
	 * @author vincent
	 * @modify by vincent at 2011-4-25 下午06:55:12
	 */
	static public function filterField($rawArray , $filter){
		$array = array();
		foreach($filter as $f){
			$array[$f] = $rawArray[$f];
		}
		return $array;
	}
	/**
	 * 必须确定缓存已经被加载
	 * 根据数据的栏位在指定的缓存块中查询
	 * 如果数据栏位包含 languages_id项,将针对当前languages_id选项进行过滤
	 * @param string $cacheKey 数据的缓存KEY
	 * @param string $field 栏位名称
	 * @param mix $value 栏位值
	 * @param boolean $isList true返回所有符合条件的项目false则只返回第一个符合的项目
	 * @param filter 筛选需要的栏位 用逗号隔开,默认不进行筛选
	 */
	static public function search_by_field($cacheKey,$field , $value  , $isList = false,$filter = ''){
		global $languages_id;
		$mcache = MCache::instance();
		$arraylist = $mcache->data[$cacheKey];
		if(is_string($value) && strpos($values,',')!== false) $value = explode(',',$value);
		if(!empty($filter)) {
			$useFilter = true ;
			$needFileds = (array)explode(',',$filter);
		}else {
			$useFilter = false ;
		}
		
		$result = array();
		$i = 0 ;
		foreach($arraylist as $array){
			if($i == 0 ){
				if(!isset($array[$field])) { trigger_error("you field not cached [".$field."]",E_USER_ERROR);}
			}
			if(isset($array['languages_id']) && !empty($languages_id)){ //如果有languages_id栏位将按照languages_id栏位进行过滤
				if($array['languages_id'] != $languages_id) continue ;
			}
			if(is_array($value )){
				foreach($value as $v){
					if($array[$field] == $v ){	
						$result[] = $useFilter ? self::filterField($array, $needFileds):$array;						
					}
				}
			}else if($array[$field] == $value){
				if($isList) $result[] = $useFilter ? self::filterField($array, $needFileds):$array;else return $array ;
			}
			$i++;
		}
		return $result ;
	}

	/**
	 * 该函数查询缓存数据的categories值
	 * @param int $categories_id 分类ID	
	 * @param mix $filter 过滤需要的栏位
	 */
	static function fetch_categories($categories_id,$filter=''){
		$cats = MCache::instance()->_categories_base();
		return isset($cats[$categories_id])? $cats[$categories_id] : array();
	}
	/**
	 * 在category缓存里 按照field搜索value
	 * @param string $field 栏位名称
	 * @param mix $value 栏位值
	 * @param boolean $isList true返回所有符合条件的项目false则只返回第一个符合的项目
	 */
	static function search_categories($field , $value  , $isList = false , $filter=''){
		$mcache = MCache::instance();
		$mcache->_categories_base();
		return MCache::search_by_field('categories_list_base',$field , $value,$isList,$filter);
	}
	/**
	 * 更新categories相关缓存对象
	 * @param unknown_type $param
	 */
	static function update_categories($param = ''){
		
		self::instance()->destory('categories_list_base');
	}
	/**
	 * 更新全站配置缓存
	 * @param unknown_type $param
	 */
	static function update_main_config($param = ''){
		self::instance()->destory('site_main_config');
	}
	/**
	 * 
	 * @param unknown_type $productId
	 * @author vincent
	 * @modify by vincent at 2011-4-28 上午11:49:13
	 */
	static function update_product($product_id,$condition=''){
		if(!is_numeric($product_id)){
			if($product_id == 'condition'){
				if($condition!=''){
					$product_info_query = tep_db_query('SELECT products_id FROM '.TABLE_PRODUCTS.' WHERE '.$condition);
					while($product_info= tep_db_fetch_array($product_info_query)){
		    			self::instance()->destory('productdetail_'.intval($product_info['products_id']));
		    		}
				}
			}
		}else{
			self::instance()->destory('productdetail_'.intval($product_id));
		}
	}
	/**
	 * 读取产品详情页缓存
	 * @param int $product_id
	 * @param string $field
	 * @author vincent
	 * @modify by vincent at 2011-4-25 下午05:12:14
	 */
	static function product_detail($product_id , $field=''){
		
		$product_id =intval($product_id);
		$key = "productdetail_".$product_id ;
		$mcache = MCache::instance();
		$contents = $mcache->fetch($key,MCache::DAY);//每天刷新缓存一次
		global $languages_id ;
		if($contents == null){
			//生成product的缓存
			 $product_info_query = tep_db_query("SELECT 
			 p.products_info_tpl, p.tour_type_icon, p.products_class_id,p.products_class_content, p.products_durations,
			 p.products_durations_type, p.products_video, p.products_type, p.operate_start_date ,p.operate_end_date,
			 p.products_single,p.products_single_pu,p.products_double,p.products_triple,p.products_quadr,p.products_kids, 
			 p.display_room_option,p.maximum_no_of_guest,p.products_id, p.is_visa_passport, pd.products_notes, pd.products_name,
			 pd.products_description, pd.products_small_description, pd.products_pricing_special_notes,  pd.products_other_description,
			 pd.products_package_excludes, pd.products_package_special_notes, p.products_is_regular_tour, p.products_model, p.provider_tour_code,
			 p.products_quantity, p.products_image, p.products_image_med, p.products_image_lrg, pd.products_url, p.products_price, 
			 p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id, p.departure_city_id, p.departure_end_city_id, 
			 p.agency_id, p.display_pickup_hotels, p.display_itinerary_notes, p.display_hotel_upgrade_notes,p.products_map, p.products_stock_status, 
			 p.upgrade_to_product_id, pd.travel_tips,pd.language_id
			 FROM " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd 
			 WHERE p.products_status = '1' 
			 AND p.products_id = '" . $product_id . "' 
			 AND pd.products_id = p.products_id ");
			 $contents = array();
    		while($product_info= tep_db_fetch_array($product_info_query)){
    			$contents['lang_'.$product_info['language_id']] = $product_info;
    		}
			$mcache->add($key, $contents);
		}
		
		$content = $contents['lang_'.$languages_id];
		if($field == '')
			return $content;
		else
		 return $content[$field];
	}
	
	/**
	 * 读取产品出发日期，返回array
	 * @param int $product_id
	 * @author Howard
	 * @modify by Howard at 2011-6-24 下午23:12:14
	 */
	static function product_departure_date($product_id){
		$product_id =intval($product_id);
		if(!USE_MCACHE || USE_MCACHE!=true){ return get_avaliabledate($product_id); }
		$key = "productdeparturedate_".$product_id ;
		$mcache = MCache::instance();
		$content = $mcache->fetch($key,MCache::DAY);//每天刷新缓存一次
		if($content == null){
			$content = get_avaliabledate($product_id);
			$mcache->add($key, $content);
		}
		return $content;
	}
	
}
?>