<?php
/**
 * 图片库管理类
 */

class picture_db{
	function __construct(){

	}

	/**
	 * 用新图替换旧图片
	 *
	 * @param unknown_type $imageDir 
	 */
	public function replaceImage(&$messageStack,$imageDir){
		if(!tep_not_null($imageDir)) return false;
		$data = false;
		$tmp_array = explode(';',$_POST['upload_all_picture']);
		$_n = 0;
		foreach((array)$tmp_array as $key => $val){
			$_array = explode('|',$val);
			if(sizeof($_array)==6){
				if((int)$_array[0] == 1 && tep_not_null($_array[3]) && tep_not_null($_array[4])){
					$data[$_n]['picture_name'] = $_POST['picture_name'];
					$data[$_n]['picture_dir_file'] = $_array[3];
					$data[$_n]['picture_url'] = $_array[4];
					$data[$_n]['picture_url_thumb'] = $_array[5];
					rename($data[$_n]['picture_dir_file'],$imageDir);
					rename(str_replace('detail_','thumb_',$data[$_n]['picture_dir_file']), str_replace('detail_','thumb_',$imageDir));
					@rename(str_replace('detail_','no_watermark_',$data[$_n]['picture_dir_file']), str_replace('detail_','no_watermark_',$imageDir));
					if((int)$_POST['add_watermark']===1){	//加水印
						$this->addWatermark($imageDir);
					}
					
					tep_db_fast_update('picture', 'picture_dir_file="'.$imageDir.'"',$data[$_n],'picture_name');
					$messageStack->add_session('恭喜！图片更新成功！', 'success');
					break; //换图片只能换1个
				}
			}
		}
		return $data;
	}

	/**
	 * 上传和压缩图片返回字符串供JS处理
	 *
	 * @param unknown_type $imageDir 图片保存位置，默认情况下文件直接上传到/images/picture/
	 * @param unknown_type $http_url 图片的URL目录
	 * @param $to_name 要保存的图片名称，只用于单个图片更新时才需要指定这个文件名
	 */
	public function uploadImage($imageDir='',$http_url='',$to_name=''){
		/*定义图片文件路径*/
		if($imageDir =='') $imageDir = DIR_FS_DOCUMENT_ROOT.'images/picture/';
		if($http_url =='') $http_url = IMAGES_HOST.'/images/picture/';
		if(!file_exists($imageDir)){
			die("The file $imageDir does not exist");
		}
		//上传和压缩图片 {
		$tmp_microtime = time();
		$new_name = 'detail_'.mt_rand(0,9).'_'.$tmp_microtime;
		if($to_name != ''){
			$new_name = $to_name;
		}

		$headers = getallheaders();
		//$a = print_r($headers,true);
		//$a.= print_r($GLOBALS,true);
		//return $a;

		$exc_name = preg_replace('/^.*\./','.',$headers['Image-Name']);
		/*上传后文件名称*/
		$new_name .= strtolower($exc_name);

		$image_name = $imageDir.$new_name;
		$file = fopen($image_name, 'wb');
		if(fwrite($file, $GLOBALS['HTTP_RAW_POST_DATA'])=== FALSE){
			return "0";
			exit();
		}

		//生成缩略图
		imageCompression($image_name,150, str_replace('detail_','thumb_',$image_name));
		fclose($file);

		return "1"."|".$new_name."|".$imageDir."|".$image_name."|".$http_url.$new_name."|".$http_url.str_replace('detail_','thumb_',$new_name);
		//状态码  	|       文件名 |  目录名      |全名（目录+文件）|图片网址             	|图片缩略图网址
		//上传和压缩图片 }
	}
	/**
	 * 写图片资料到数据库
	 *
	 * @param unknown_type $messageStack 出现提示对象
	 * @return unknown
	 */
	public function insert(&$messageStack){
		//写资料到数据库 {
		if(!tep_not_null($_POST['upload_all_picture'])){
			$messageStack->add_session('对不起，您没有上传任何图片！', 'error');
			return 'error';
			//tep_redirect(tep_href_link('picture_db.php'));
		}

		$data = false;
		$tmp_array = explode(';',$_POST['upload_all_picture']);
		$_n = 0;
		foreach((array)$tmp_array as $key => $val){
			$_array = explode('|',$val);
			if(sizeof($_array)==6){
				if((int)$_array[0] == 1 && tep_not_null($_array[3]) && tep_not_null($_array[4])){
					$data[$_n]['countries_id'] = $_POST['countries_id'];
					$data[$_n]['picture_name'] = $_POST['picture_name'];
					$data[$_n]['zone_id'] = $_POST['zone_id'];
					$data[$_n]['city_id'] = $_POST['city_id'];
					$data[$_n]['added_time'] = date('Y-m-d H:i:s');
					$data[$_n]['added_admin'] = $_SESSION['login_id'];

					$data[$_n]['picture_dir_file'] = $_array[3];
					$data[$_n]['picture_url'] = $_array[4];
					$data[$_n]['picture_url_thumb'] = $_array[5];
					//根据国家、州省、城市重新格式化图片名 start{
					$format_name = true;
					if($format_name == true){
						$ex_name = '';
						if(file_exists($data[$_n]['picture_dir_file']) && ((int)$_POST['countries_id'] || (int)$_POST['zone_id'] || (int)$_POST['city_id'])){
							$ex_name = '_'.str_replace(' ','-', strtolower(tep_get_country_iso_code_2((int)$_POST['countries_id'])).'_'.strtolower(tep_get_zone_code_from_zone((int)$_POST['zone_id'])).'_'.strtolower(tep_get_tour_city_code((int)$_POST['city_id'])) );
							$_strrpos = strrpos($data[$_n]['picture_dir_file'],'.');
							if($_strrpos!==false){
								$new_address = substr($data[$_n]['picture_dir_file'],0,$_strrpos) . $ex_name. substr($data[$_n]['picture_dir_file'],$_strrpos);
								rename($data[$_n]['picture_dir_file'], $new_address);
								rename(str_replace('detail_','thumb_',$data[$_n]['picture_dir_file']), str_replace('detail_','thumb_',$new_address));
								@rename(str_replace('detail_','no_watermark_',$data[$_n]['picture_dir_file']), str_replace('detail_','no_watermark_',$new_address));
								
								$data[$_n]['picture_dir_file'] = $new_address;

								$_strrpos = strrpos($data[$_n]['picture_url'],'.');
								$data[$_n]['picture_url'] = substr($data[$_n]['picture_url'],0,$_strrpos) . $ex_name. substr($data[$_n]['picture_url'],$_strrpos);

								$_strrpos = strrpos($data[$_n]['picture_url_thumb'],'.');
								$data[$_n]['picture_url_thumb'] = substr($data[$_n]['picture_url_thumb'],0,$_strrpos) . $ex_name. substr($data[$_n]['picture_url_thumb'],$_strrpos);
								if((int)$_POST['add_watermark']===1){	//加水印
									$this->addWatermark($data[$_n]['picture_dir_file']);
								}
							}

						}
					}
					//根据国家、州省、城市重新格式化图片名 end}
					$_n++;
				}
			}
		}
		if(is_array($data)){
			for($i = 0, $n = sizeof($data); $i < $n; $i++){
				if(file_exists($data[$i]['picture_dir_file'])){
					$picture_id = tep_db_fast_insert('`picture`', $data[$i]);
				}
			}

			$messageStack->add_session('恭喜！图片写入到数据库成功！', 'success');
			return 'success';
			//tep_redirect(tep_href_link('picture_db.php'));
		}
		//写资料到数据库 }
	}

	/**
	 * 删除图片
	 *
	 * @param unknown_type $picture_id
	 * @return 返回被删除的图片数目
	 */
	public function delete($picture_id){
		$delNum = 0;
		$sql = tep_db_query('SELECT picture_dir_file FROM `picture` WHERE picture_id ="'.(int)$picture_id.'" ');
		$row = tep_db_fetch_array($sql);
		if(tep_not_null($row['picture_dir_file'])){
			if(@unlink($row['picture_dir_file'])===true){
				@unlink(str_replace('/detail_','/thumb_',$row['picture_dir_file']));
				@unlink(str_replace('/detail_','/no_watermark_',$row['picture_dir_file']));
				tep_db_query('DELETE FROM `picture` WHERE picture_id ="'.(int)$picture_id.'" ');
				$delNum++;
			}
		}
		return $delNum;
	}

	/**
	 * 图片库列表
	 *
	 * @param unknown_type $splitPage 是否需要分页功能，默认是不需要的。
	 * @return unknown
	 */
	public function lists($splitPage = false){
		$data = false;
		//取得图片数据 start{
		$field = ' pi.* ';
		$table = ' picture pi ';
		$where = ' where 1 ';
		$group_by = '';
		$order_by = 'order by pi.picture_id desc ';
		if((int)$_GET['countries_id']){
			$where .= ' AND pi.countries_id = '.(int)$_GET['countries_id'];
		}
		if((int)$_GET['zone_id']){
			$where .= ' AND pi.zone_id = '.(int)$_GET['zone_id'];
		}
		if((int)$_GET['city_id']){
			$where .= ' AND pi.city_id = '.(int)$_GET['city_id'];
		}
		if(tep_not_null($_GET['picture_name'])){
			$where .= ' AND pi.picture_name like "%'.tep_db_input(tep_db_prepare_input($_GET['picture_name'])).'%" ';
		}
		if(tep_not_null($_GET['city_name'])){
			$str_sql='select city_id from tour_city where city like "%'.$_GET['city_name'].'%"';
			//echo $str_sql;
			$sql_query=tep_db_query($str_sql);
			$sql_add='';
			while($tmp=tep_db_fetch_array($sql_query)){
				$sql_add.=','.$tmp['city_id'];
			}
			if($sql_add)
			$where.=' and pi.city_id in('.substr($sql_add, 1).')';
// 			echo $sql_add;
		}
		$sql_str = 'SELECT '.$field.' FROM '.$table.' '.$where.' '.$group_by.' '.$order_by;
		$picture_query_numrows = 0;
		if($splitPage != false){
			$picture_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $sql_str, $picture_query_numrows);
		}
		$picture_query = tep_db_query($sql_str);
		while($picture_rows = tep_db_fetch_array($picture_query)){
			$data[] = $picture_rows;
		}
		if(is_array($data) && isset($picture_split)){
			$data['splitPageResults']['display_count'] = $picture_split->display_count($picture_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS);
			$data['splitPageResults']['display_links'] = $picture_split->display_links($picture_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $_GET['page'],tep_get_all_get_params(array('page','y','x', 'action')));

		}
		//取得图片数据 end}
		return $data;
	}

	/**
	 * 从图片库中取得已经使用了该图片的产品IDs
	 *
	 * @param unknown_type $picture_id 图片id
	 */
	public function getProductsIds($picture_id){
		$products_ids = false;
		$sql = tep_db_query('SELECT picture_url, picture_url_thumb FROM `picture` WHERE picture_id="'.(int)$picture_id.'" ');
		$row = tep_db_fetch_array($sql);		
		if(tep_not_null($row['picture_url'])){
			$products_ids = $this->getProductsIdsFromImageUrl($row['picture_url']);
		}
		if(tep_not_null($row['picture_url_thumb'])){
			if(is_array($products_ids) && sizeof($products_ids)>0){
				$_tmp = $this->getProductsIdsFromImageUrl($row['picture_url_thumb']);
				$products_ids = array_merge($products_ids,(array)$_tmp );
			}else{
				$products_ids = $this->getProductsIdsFromImageUrl($row['picture_url_thumb']);
			}
		}
		if(is_array($products_ids)){
			$products_ids = array_unique($products_ids);
		}
		return $products_ids;
	}

	/**
	 * 根据图片url取得产品IDs
	 *
	 * @param unknown_type $url
	 */
	private function getProductsIdsFromImageUrl($url){
		$products_ids = false;
		$p_where_str = ' products_image ="[S]" || products_image_med ="[S]" || products_image_lrg ="[S]" || products_image_sm_1 ="[S]" ||	products_image_xl_1 ="[S]" || products_image_sm_2 ="[S]" || products_image_xl_2 || products_image_sm_3 || products_image_xl_3 ="[S]" || products_image_sm_4 ="[S]" || products_image_xl_4 ="[S]" ';
		$pe_where_str = ' product_image_name ="[S]" ';
		$pt_where_str = ' travel_img ="[S]" ';
		
		if(tep_not_null($url)){
			//查产品表
			$pSql = tep_db_query('SELECT products_id FROM `products` WHERE '.str_replace('[S]',$url,$p_where_str));
			while ($pRows = tep_db_fetch_array($pSql)) {
				if((int)$pRows['products_id']){
					$products_ids[] = $pRows['products_id'];
				}
			}
			//查产品扩展图片表
			$peSql = tep_db_query('SELECT products_id FROM `products_extra_images` WHERE '.str_replace('[S]',$url,$pe_where_str));
			while ($peRows = tep_db_fetch_array($peSql)) {
				if((int)$peRows['products_id']){
					$products_ids[] = $peRows['products_id'];
				}
			}
			//查产品行程表
			$ptSql = tep_db_query('SELECT products_id FROM `products_travel` WHERE '.str_replace('[S]',$url,$pt_where_str));
			while ($ptRows = tep_db_fetch_array($ptSql)) {
				if((int)$ptRows['products_id']){
					$products_ids[] = $ptRows['products_id'];
				}
			}			
		}
		if(is_array($products_ids)){
			$products_ids = array_unique($products_ids);
		}
		return $products_ids;
	}
	

	/**
	 * 给图片添加水印
	 *
	 * @param unknown_type $image_name 大图路径
	 * @param unknown_type $watermark_name 水印图片路径，建议用png图片作水印
	 */
	private function addWatermark($image_name){
		$watermark_name = DIR_FS_CATALOG."image/watermark/black.png";
		copy($image_name,str_replace('detail_','no_watermark_',$image_name));	//备份没有水印的图
		$make_action = makeCopyrightLwk($image_name, $watermark_name,9,100);			
	}

}

?>