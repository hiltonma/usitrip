<?php
/**
 * 游记操作类
 * @formatter:off
 * @author lwkai 2013-4-9 下午5:46:22
 */
class Travel {
	
	/**
	 * 数据库操作对象
	 * @var Db_Mysql
	 * @author lwkai 2013-4-9 上午11:56:54
	 */
	private $_db = null;
	
	/**
	 * 游记表
	 * @var string
	 * @author lwkai 2013-4-9 上午11:57:19
	 */
	private $_table = 'travel_notes';
	
	/**
	 * 保存一些不希望重复NEW的对象，避免调用方法时，NEW出来N个相同的对象
	 * @var array
	 * @author lwkai 2013-4-11 上午11:17:03
	 */
	private $_obj = array();
	
	/**
	 * 翻页对象
	 * @var Paging
	 * @author lwkai 2013-4-19 下午3:30:46
	 */
	private $_pageing = null;
	
	/**
	 * 在输出前，需要格式化的字段
	 * @var array
	 * @author lwkai 2013-4-23 下午3:14:13
	 */
	private $_format = array('travel_notes_title');
	
	/**
	 * 创建图片操作对象
	 * @return Image
	 * @author lwkai 2013-4-11 上午9:16:41
	 */
	private function image() {
		if (isset($this->_obj['image']) && $this->_obj['image'] != null) {
			return $this->_obj['image'];
		} else {
			$this->_obj['image'] = new Image();
			return $this->_obj['image'];
		}
	}
	
	/**
	 * 取得图片拍摄信息操作对象
	 * @return Exif_Manage
	 * @author lwkai 2013-4-11 上午9:21:08
	 */
	private function exif(){
		if (isset($this->_obj['exif']) && $this->_obj['exif'] != null) {
			return $this->_obj['exif'];
		} else {
			$this->_obj['exif'] = new Exif_Manage();
			return $this->_obj['exif'];
		}
	}
	
	/**
	 * 游记操作类
	 * 
	 * @author lwkai 2013-4-23 下午3:14:40
	 */
	public function __construct(){
		$this->_db = Db::get_db();
	}
	
	/**
	 * 根据游记ID，获取游记的发布人ID
	 * @param int $id 游记ID
	 * @return number 返回发布用户的ID
	 * @author lwkai 2013-4-9 下午5:43:51
	 */
	public function getUserId($id) {
		$rs = $this->_db->query("select customers_id from " . $this->_table . " where travel_notes_id='" . intval($id) . "'")->getOne();
		return $rs['customers_id'];
	}
	
	/**
	 * 根据游记ID取得对应的记录行
	 * @param int $id 游记ID
	 * @return array
	 * @author lwkai 2013-4-16 下午2:51:12
	 */
	public function get($id) {
		$rs = $this->_db->query("select * from " . $this->_table . " where travel_notes_id='" . intval($id) . "'")->getOne();
		if ($rs) {
			$rs['travel_notes_title'] = htmlspecialchars($rs['travel_notes_title']);
		}
		return $rs;
	}
	
	/**
	 * 添加一个浏览数
	 * @param int $id 游记ID
	 * @return number
	 * @author lwkai 2013-5-3 下午2:42:25
	 */
	public function addViews($id) {
		$num = 0;
		$rs = $this->_db->query("select image_id from travel_image where travel_notes_id='" . intval($id) . "'")->getAll();
		$image = new Image();
		foreach($rs as $key => $val) {
			$num += $image->getViews($val['image_id']);
		}
		$sql = "select day_id from travel_day where travel_notes_id='" . intval($id) . "'";
		$rs = $this->_db->query($sql)->getAll();
		$mood = new Mood();
		foreach($rs as $key=>$val) {
			$num += $mood->getViews($val['day_id']);
		}
		return ($this->update(array('read_number'=>$num), 'travel_notes_id=' . intval($id)));
	}
	
	/**
	 * 根据用户ID，取得该用户的所有游记
	 * @param int $user_id 发表游记的用户ID
	 * @param string $order_by 排序[可选]
	 * @return array 
	 * @author lwkai 2013-4-17 下午2:34:16
	 */
	public function getUserListAll($user_id, $order_by = '') {
		$sql = "select * from " . $this->_table . " where customers_id='" . intval($user_id) . "'";
		if ($order_by != '') {
			$sql .= ' order by ' . $order_by;
		}
		$rs = $this->_db->query($sql)->getAll();
		$rs = $this->format($rs);
		return $rs;
	}
	
	/**
	 * 取得用户的游记页,当前如果有$_GET['page']，则取这一页，否则取第一页
	 * @param int $user_id 用户ID
	 * @param int $pagesize 一页显示多少条游记[可选，默认10条]
	 * @param string $order_by 排序[可选]
	 * @return multitype:multitype: 
	 * @author lwkai 2013-4-23 上午10:20:05
	 */
	public function getUserList($user_id, $pagesize = 10, $order_by = '') {
		$sql = "select * from " . $this->_table . " where customers_id='" . intval($user_id) . "'";
		if ($order_by != '') {
			$sql .= ' order by ' . $order_by;
		}
		$this->_pageing = new Paging($this->_db, new Url($this->_db, 'index'), $sql, $pagesize);
		$pageing = new Paging($this->_db, $this->_url, $sql, 10);
		$rs = $this->_db->query($this->_pageing->getSql())->getAll();
		$rs = $this->format($rs);
		return $rs;
	}
	
	/**
	 * 返回用户的图片总数
	 * @param int $user_id 用户ID
	 * @return int
	 * @author lwkai 2013-4-23 上午9:58:01
	 */
	public function getUserImagesNum($user_id){
		return $this->image()->getUserImagesNum($user_id);
	}
	
	/**
	 * 取得游记的某一页数据
	 * @param number $pagesize 一页多少条记录
	 * @param string $where 条件[可选]
	 * @author lwkai 2013-4-19 下午3:26:25
	 */
	public function getList($pagesize = 10,$where = '',$sql = '') {
		if ($sql == '') {
			$sql = "select * from " . $this->_table . " where 1=1";
			if (SYS_IS_AUDIT == 'true') { // 如果打开需要审核的开关
				$sql .= " and verify=1";
			}
			if ($where != '') {
				$sql .= ' and ' . $where;
			}
			$sql .= " order by is_top desc,add_time desc";
		}
		$this->_pageing = new Paging($this->_db, new Url($this->_db, 'index'), $sql, $pagesize);
		$rs = $this->_db->query($this->_pageing->getSql())->getAll();
		$rs = $this->format($rs);
		return $rs;
	}
	
	/**
	 * 取得当前已经发布的所有目的地
	 * @return multitype:NULL 
	 * @author lwkai 2013-4-22 下午1:04:21
	 */
	public function getAttractionslist(){
		$sql = "SELECT city_id FROM `travel_city_top` where `sort_id` > 0 order by `sort_id` asc";
		$rs = $this->_db->query($sql)->getAll();
		$rtn = array();
		foreach($rs as $key => $val) {
			$_t = Attractions_Usitrip::getAttractionsById($val['city_id']);
			$rtn[$val['city_id']] = $_t[0]['city'];
		}
		return $rtn;
	}
	
	/**
	 * 取得前一次分页查询的页码信息
	 * @param int $max_page_num 显示多少个页码
	 * @return array
	 * @author lwkai 2013-4-19 下午3:34:13
	 */
	public function getPageInfo($max_page_num){
		if ($this->_pageing != null) {
			return $this->_pageing->getPageLinksToArray($max_page_num);
		}
		return array();
	}
	
	/**
	 * 取得前一次分页查询的总记录数
	 * @return number
	 * @author lwkai 2013-4-19 下午5:23:08
	 */
	public function getRowsTotal(){
		if ($this->_pageing != null) {
			return $this->_pageing->getRowsCount();
		}
		return 0;
	}
	
	/**
	 * 删除一个游记,返回受影响的记录数
	 * @param int $id 需要删除的游记ID
	 * @return number
	 * @author lwkai 2013-4-9 上午11:58:54
	 */
	public function del($id) {
		$pic = new Image();
		$mood = new Mood();
		$pic->del($id);
		$mood->del($id);
		// 删除封面图
		$rs = $this->_db->query("select cover_image from " . $this->_table . " where travel_notes_id='" . intval($id) . "'")->getOne();
		$file = new File();
		$size = Image::getSize('face');
		$file->delete(DIR_FS_ROOT . 'upimg' . DS . $size['width'] . 'x' . $size['height'] . DS . $rs['cover_image']);
		
		// 删除游记主表记录
		$rtn = $this->_db->delete($this->_table, "travel_notes_id='" . intval($id) . "'");
		return $rtn;
	}
	
	/**
	 * 删除图片与心情
	 * @param int $id 图片或者心情ID
	 * @param string $target 区分标记[Image图片,Mood心情]
	 * @author lwkai 2013-4-26 下午2:48:01
	 */
	public function delPicOrMood($id, $target){
		if ('Image' == $target || 'Mood' == $target) {
			$delobj = new $target();
			$travel_id = $delobj->getTravelId($id);
			$rtn = $delobj->delOne($id);
			// 如果删除掉了一条心情或者图片，则需要更新主表的心情与评论
			// 获得心情总数
			$travel_like = Like_Factory::getLike('Travels');
			$like_num = $travel_like->countLike($travel_id);
			// 获得评论总数
			$travel_comment = Comment_Factory::getComment('Travels');
			$comment_num = $travel_comment->getCommentsNum($travel_id);
			$this->update(array('like_number' => $like_num,'replay_number'=>$comment_num), "travel_notes_id='" . intval($travel_id) . "'");
		}
		return $rtn;
	}
	
	/**
	 * 修改游记数据，返回受影响的记录数
	 * @param array $date 要修改的键值对数组
	 * @param string $where 修改条件
	 * @return int
	 */
	public function update($data,$where) {
		$rs = $this->_db->update($this->_table, $data, $where);
		return $rs;
	}
	
	/**
	 * 把图片保存到数据库
	 * @param XML $val2 XML对象
	 * @param int $location_id 拍摄景点ID
	 * @param int $travel_notes_id 游记ID
	 * @param string $date 针对某个日期添加图片时的具体日期[可选]
	 * @author lwkai 2013-4-9 下午3:46:02
	 */
	private function addToImage($val2, $location_id, $travel_notes_id, $date='') {
		$data = array();
		$data['location_id'] = $location_id;
		$data['image_src'] = $val2->getElementsByTagName('url')->item(0)->nodeValue;
		$data['image_src'] = str_replace(DIR_WS_ROOT . 'upimg/source', '', $data['image_src']);
		$data['image_desc'] = $val2->getElementsByTagName('desc')->item(0)->nodeValue;
		$data['is_cover'] = $val2->getElementsByTagName('isCover')->item(0)->nodeValue == 'false' ? 0 : 1;
		
		/* 记录拍摄时间，方便后续排序用  */
		$date_arr = explode('-',$date);
		$len = count($date_arr);
		if ($len == 3 && checkdate($date_arr[1], $date_arr[2], $date_arr[0])) {
			$data['time_taken'] = $date;
		} else {
			$data['time_taken'] = $val2->getElementsByTagName('exif')->item(0)->getElementsByTagName('Camera_Time')->item(0)->nodeValue;
		}
		$data['travel_notes_id'] = $travel_notes_id;
		$data = Convert::iconv('utf-8','gb2312', $data);
		$pic_id = $this->image()->add($data);
		$this->addToExif($val2->getElementsByTagName('exif')->item(0), $pic_id);
		// 生成封面图
		if ($data['is_cover'] == 1) {
			$this->image()->frontCover($data['image_src']);
			$this->_db->update($this->_table, array('cover_image'=>$data['image_src']),"travel_notes_id='" . $travel_notes_id . "'");
		}
	}
	
	/**
	 * 修改封面图
	 * @param int $travel_id 游记ID
	 * @param int $image_id  图片ID
	 * @author lwkai 2013-4-17 下午3:08:55
	 */
	public function updateCover($travel_id,$image_id){
		$image = new Image();
		$rs = $image->get($image_id);
		$wh = Image::getSize('face');
		$travel_rs = $this->get($travel_id);
		$file = new File();
		$file->delete(DIR_FS_ROOT . 'upimg' . DS . $wh['width'] . 'x' . $wh['height'] . $travel_rs['cover_image']);
		$image->update(array('is_cover'=>0),"travel_notes_id='" . intval($travel_id) . "' and is_cover=1");
		$this->image()->frontCover($rs['image_src']);
		$image->update(array('is_cover'=>1),"travel_notes_id='" . intval($travel_id) . "' and image_id='" . intval($image_id) . "'");
		$this->update(array('cover_image'=>$rs['image_src']), "travel_notes_id='" . intval($travel_id) . "'");
	}
	
	/**
	 * 保存图片上的EXIF信息到数据表
	 * @param XML $exif XML数据对象
	 * @param int $pic_id 插入的图片ID
	 * @author lwkai 2013-4-9 下午3:45:07
	 */
	private function addToExif($exif, $pic_id) {
		/* 保存图片的EXIF信息 */
		$data = array();
		$data['exif_camera'] = $exif->getElementsByTagName('Camera_Model')->item(0)->nodeValue;
		$data['exif_time'] = $exif->getElementsByTagName('Camera_Time')->item(0)->nodeValue;
		$data['exif_iso'] = $exif->getElementsByTagName('Camera_ISO')->item(0)->nodeValue;
		$data['exif_aperture'] = $exif->getElementsByTagName('Fnumber')->item(0)->nodeValue;
		$data['exif_shutter_speed'] = $exif->getElementsByTagName('Exposure_Time')->item(0)->nodeValue;
		$data['exif_exposure_compensation'] = $exif->getElementsByTagName('Exposure_Bias')->item(0)->nodeValue;
		$data['exif_focal_length'] = $exif->getElementsByTagName('Focal_Length')->item(0)->nodeValue;
		$data['image_id'] = $pic_id;
		$data = Convert::iconv('utf-8', 'gb2312', $data);
		$this->exif()->add($data);
	}
	
	/**
	 * 添加游记
	 * @param string $param 要添加的数据，此字符串，是一个标准的XML的字符串具体格式如下
	 * @param boolean $id_have 如果有ID的话，就不执行先插入旅途主档的操作，直接插入图片到旅途图片表
	 * <xml> 
	 *     <userId>255</userId>
	 *     <name>测试游记三</name> 
	 *     <description></description> 
	 *     <destination>!517!</destination> 
	 *     <coverPic>!517!</coverPic> 
	 *     <tripLine>!140!</tripLine>
	 *     批次开始 
	 *     <batchList> 
	 *         <batch> 
	 *             <batchdes>!</batchdes> 
	 *             <batchPicList>
	 *                 图片开始循环 
	 *                 <batchPic> 
	 *                     <url>/lvtu/upimg/1365491849.2768.jpg</url> 
	 *                     <category>undefined</category> 
	 *                     <desc></desc> 
	 *                     <isCover>false</isCover> 
	 *                     <picWidth>1920</picWidth> 
	 *                     <picLength>1200</picLength> 
	 *                     <exif> 
	 *                         <Camera_Time>2013:04:09 15:17:53</Camera_Time> 
	 *                         <Camera_Model>undefined</Camera_Model> 
	 *                         <Fnumber></Fnumber> 
	 *                         <Camera_ISO>undefined</Camera_ISO> 
	 *                         <Exposure_Time></Exposure_Time> 
	 *                         <Exposure_Bias></Exposure_Bias> 
	 *                         <Focal_Length></Focal_Length> 
	 *                         <GpsLongitude>0</GpsLongitude> 
	 *                         <GpsLatitude>0</GpsLatitude> 
	 *                     </exif> 
	 *                 </batchPic>
	 *                 循环结束 
	 *             </batchPicList> 
	 *         </batch> 
	 *     </batchList>
	 *     批次结束 
	 *     <picNum>6</picNum>
	 * </xml>
	 * @return 游记中最新插入的这条记录的ID
	 * @author lwkai 2013-4-9 下午3:15:12
	 */
	public function add($param) {
		$xml = $this->stringToXml($param);
		if ($xml) {
			$data = array();
			$data['travel_notes_title'] = $xml->getElementsByTagName('name')->item(0)->nodeValue;
			$data['customers_id'] = $_SESSION['customer_id'];
			$data['image_number'] = $xml->getElementsByTagName('picNum')->item(0)->nodeValue;
			$data['add_time'] = date('Y-m-d H:i:s');
			$data['destination'] = $xml->getElementsByTagName('destination')->item(0)->nodeValue;
			$data['products_id'] = $xml->getElementsByTagName('tripLine')->item(0)->nodeValue;
			$data['products_id'] = $data['products_id'] ? trim($data['products_id'],'!') : '';
			$data = Convert::iconv('utf-8', 'gb2312', $data);
			$travel_notes_id = $this->_db->insert($this->_table, $data);
	
			/* 保存图片 注意 这里需要先保存主表后得到刚插入的记录ID 供下面用 */
			$this->rraversalPictures($xml, $travel_notes_id);
			return $travel_notes_id;
		}
		return false;
	}
	
	/**
	 * 检测某一条游记是否还存在,存在返回真，否则返回假
	 * @param int $id 游记ID
	 * @return boolean
	 * @author lwkai 2013-4-27 下午5:46:59
	 */
	public function isHave($id){
		$sql = "select travel_notes_id from " . $this->_table . " where travel_notes_id='" . intval($id) . "'";
		$rtn = $this->_db->query($sql)->getOne();
		if ($rtn) {
			return true;
		}
		return false;
	}
	
	/**
	 * 对已经存在的游记加入新的图片，如果是针对某一天插入图片，则$data参数不可少，针对游记添加图片，则$data不需要。
	 * 即：追加图片
	 * @param string $param XML格式的图片数据字符串，格式如ADD方法中的说明
	 * @param string $date 插入到哪一天的那个日期字符串
	 * @return boolean
	 */
	public function addImage($param, $date='') {
		$xml = $this->stringToXml($param);
		if ($xml) {
			$travel_notes_id = $xml->getElementsByTagName('id')->item(0)->nodeValue;
			if ($this->isHave($travel_notes_id)) {
				$num = $this->rraversalPictures($xml, $travel_notes_id, $date);
				$this->update(array('image_number'=>'image_number + ' . $num), "travel_notes_id='" . intval($travel_notes_id) . "'");
				return $travel_notes_id;
			}
		}
		return false;
	}
	
	/**
	 * 检测游记是否已经审核,通过返回真，否则返回假
	 * @param int $id 游记ID
	 * @return boolean
	 * @author lwkai 2013-4-19 下午2:29:16
	 */
	public function isVerify($id) {
		$sql = "select travel_notes_id from " . $this->_table . " where travel_notes_id='" . intval($id) . "' and verify=1";
		$rs = $this->_db->query($sql)->getOne();
		if ($rs) {
			return true;
		}
		return false;
	}
	
	/**
	 * 判断是否是自己发表的游记,是自己的返回真。
	 * @param int $id 游记ID
	 * @return boolean
	 * @author lwkai 2013-4-19 下午2:46:35
	 */
	public function isSelf($id) {
		$user_id = isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : 0;
		$sql = "select customers_id from " . $this->_table . " where travel_notes_id='" . intval($id) . "'";
		$rs = $this->_db->query($sql)->getOne();
		if ($rs['customers_id'] == $user_id) {
			return true;
		}
		return false;
	}
	
	/**
	 * 把 XML格式的字符串，转换成XML对象，成功返回XML对象，失败返回false
	 * @param string $str xml格式的字符串
	 * @return DOMDocument|boolean
	 */
	private function stringToXml($str) {
		if ($str != '') {
			$xml = new DOMDocument();
			if ($xml->loadXML($str)){
				return $xml;
			}
		}
		return false;
	}
	
	/**
	 * 遍历XML图片数据，并保存
	 * @param DOMDocument $xml XML对象
	 * @param int $travel_notes_id 游记ID
	 * @param string $date 日期字符串，只需要年月日, 可选，如果是针对某天添加图片，则日期不可少
	 * @return number
	 */
	private function rraversalPictures($xml, $travel_notes_id, $date = '') {
		$i = 0;
		foreach($xml->getElementsByTagName('batchList')->item(0)->getElementsByTagName('batch') as $val) { //几批
			foreach($val->getElementsByTagName('batchPicList')->item(0)->getElementsByTagName('batchPic') as $val2) { // 每一批图
				/* 保存图片信息 */
				$this->addToImage($val2, trim($val->getElementsByTagName('batchdes')->item(0)->nodeValue,'!'), $travel_notes_id,$date);
				$i++;
			}
		}
		return $i;
	}
	
	/**
	 * 检测游记标题是否已经存在,存在返回真，否则返回假
	 * @param string $title 要检测的标题文字
	 * @return boolean
	 * @author lwkai 2013-4-23 上午11:30:53
	 */
	public function checkTravelTitle($title){
		$sql = "select travel_notes_id from travel_notes where travel_notes_title = '" . Convert::db_input($title) . "'";
		$rs = $this->_db->query($sql)->getOne();
		if ($rs) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * 把需要的字段给格式化
	 * @param array $rs 数据库结果集的数组
	 * @return string
	 * @author lwkai 2013-4-23 下午3:16:59
	 */
	private function format($rs){
		foreach($rs as $key=>$val) {
			foreach($this->_format as $k=>$v){
				$val[$v] = htmlspecialchars($val[$v]);
			}
			$rs[$key] = $val;
		}
		return $rs;
	}
	
	/**
	 * 游记被点击了喜欢操作的处理
	 * @param int $id 要进行喜欢操作的对象ID
	 * @param string $targetType 要进行喜欢操作的类型[Image:图片1,Mood:心情2,Travels:游记3]
	 * @param int $praiseActivityType 喜欢的操作动作[1添加,2取消]
	 * @param int $userid 操作的用户ID
	 * @author lwkai 2013-4-24 下午1:48:49
	 */
	public function like($id, $targetType, $praiseActivityType, $userid){
		// 图片加1 或者 心情加1 都需要在游记的喜欢总数上加1
		$like = Like_Factory::getLike($targetType);
		switch ($praiseActivityType) {
			case 1: //添加喜欢
				if (!$like->isLike($id, $userid)) {
					$status = $like->addLike($id, $userid);
				}
				$status = $status > 0 ? 0 : 618; //JS那边 0 为成功 所以返回的结果大于0则表示有记录被修改
				break;
			case 2:// 取消喜欢
				if ($like->isLike($id, $userid)) {
					$status = $like->delLike($id, $userid);
				}
				$status = $status > 0 ? 0 : 618; //JS那边 0 为成功 所以返回的结果大于0则表示有记录被修改
				break;
		}
		if ($targetType != 'Travels') { // 如果不是对游记进行喜欢操作,则更新喜欢总数
			$travel_like = Like_Factory::getLike('Travels');
			$travel_id = $like->getTravelId($id);
			$num = $travel_like->countLike($travel_id);
			$this->update(array('like_number' => $num), "travel_notes_id='" . intval($travel_id) . "'");
		}
		return $status;
	}
}