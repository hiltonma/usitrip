<?php
/**
 * 对图片的操作类，暂时只实现了删除
 * @author lwkai by 2013-04-08
 */
class Image extends Abstract_Manage{
	
	/**
	 * 图片尺寸 
	 * @var array('projector'=>幻灯图大小,'list'=>列表图大小,'face'=>我的发现页图片大小,'min'=>上传成功后显示的预览小图,'preview'=>幻灯图小图)
	 * projector=>array('width'=>宽度,'height'=>高度,'quality'=>生成的图片质量[0-100])
	 * @author lwkai 2013-4-11 上午10:01:48
	 */
	private static $_size = array(
		'projector' => array('width' => 1728,'height' => 1080,'quality' => 100),
		'list'      => array('width' => 665, 'height' => 416, 'quality' => 100),
		'face'      => array('width' => 600, 'height' => 400, 'quality' => 100),
		'min'       => array('width' => 225, 'height' => 186, 'quality' => 90),
		'preview'   => array('width' => 90,  'height' => 60,  'quality' => 90)
	);
	
	/**
	 * 本类需要操作的数据表
	 * @var string [travel_image]
	 */
	protected $_table = 'travel_image';
	
	/**
	 * 当前对象类型标记 【图片为1 心情为 2 游记为3】
	 * @var string
	 */
	protected $_target = 'Image';
	
	/**
	 * 栏位前缀名称
	 * @var string
	 */
	protected $_field_prefix = 'image';
	
	/**
	 * 文件操作对象
	 * @return File
	 * @author lwkai 2013-4-11 上午9:29:43
	 */
	private function file() {
		if (isset($this->_obj['file']) && $this->_obj instanceof File) {
			return $this->_obj['file'];
		} else {
			$this->_obj['file'] = new File(); // 注意此属性我未在内中定义 ，运行时 此属性是公有。特此标注
			return  $this->_obj['file'];
		}
	}
	
	/**
	 * 图片表操作类
	 */
	public function __construct() {
		//parent::__construct();
	}
	
	/**
	 * 返回图片大小尺寸数组
	 * @param string $name 需要的图片标记名['projector'幻灯大图,'list'列表页图,'face'封面图,'min'预览小图]
	 * @author lwkai 2013-4-12 下午3:23:43
	 */
	public static function getSize($name){
		if (in_array($name,array_keys(self::$_size))) {
			return self::$_size[$name];
		}
		return false;
	}
	
	/**
	 * 对图片进行处理，生成所需要的规格图
	 * @param string $pic_address 需要处理的图片地址，完整绝对路径
	 * @param string $orientation 图片旋转方向，传入即开启旋转检测，空为不检测，默认为空
	 * @author lwkai 2013-4-11 上午10:21:21
	 */
	public function handlePic($pic_address, $orientation = ''){
		$name = basename($pic_address);
		$datePath = date('Y-m-d');
		$datePath = str_replace("-",DS,$datePath);
		$rotate = false;
		if ($orientation != '') { //如果传入了旋转参数值，则进行旋转检测
			$new_address = DIR_FS_ROOT . 'upimg' . DS .$name;
			$pic = new Picture_Zoom(array('img'=>$pic_address, 'saveimg' => $new_address));
			$rotate = $pic->rotate($orientation); // 检测并根据需要进行旋转
			if($rotate == true) { // 如果图片进行了旋转，则把源图地址改变成旋转后的图片
				$pic_address = $new_address;
			}
		}
		foreach(self::$_size as $key => $val) {
			if ($key == 'face') continue; //我的发现页面需要的图,不是每个图都需要，所以这里跳过
			$newimg = DIR_FS_ROOT . 'upimg' . DS . $val['width'] . 'x' . $val['height'] . DS . $datePath;
			$this->file()->createDir($newimg);
			$newimg .= DS . $name;
			isset($val['quality']) || $val['quality'] = 90;
			$pic = new Picture_Zoom(array('img' => $pic_address, 'saveimg' => $newimg, 'maxheight'=>$val['height'],'maxwidth'=>$val['width'],'quality'=>$val['quality']));
			if ($key == 'min' || $key == 'preview') {
				$pic->cut(true);
			} else {
				$pic->zoom();
			} 
		}
		if ($rotate == true) {
			$this->file()->delete($new_address);
		}
		return DIR_WS_ROOT . 'upimg/' . self::$_size['min']['width'] . 'x' . self::$_size['min']['height'] . '/' . str_replace("\\",'/',$datePath) . '/' .  $name;
	}
	
	/**
	 * 处理封面图片
	 * @param string 图片地址，数据库中的原样，不需要绝对路径
	 * @author lwkai 2013-4-11 上午11:20:35
	 */
	public function frontCover($pic_address) {
		$newname = DIR_FS_ROOT . 'upimg' . DS . self::$_size['face']['width'] . 'x' . self::$_size['face']['height'] . DS . str_replace('/',DS,$pic_address);
		$path = dirname($newname);
		$this->file()->createDir($path);
		$source = DIR_FS_ROOT . 'upimg' . DS . self::$_size['projector']['width'] . 'x' . self::$_size['projector']['height'] . DS . str_replace('/',DS,$pic_address);
		$pic = new Picture_Zoom(array('img'=>$source,'saveimg'=>$newname,'maxheight'=>self::$_size['face']['height'],'maxwidth'=>self::$_size['face']['width']));
		$pic->cut(true);
	}
	
	/**
	 * 添加图片。
	 * 返回插入的记录ID
	 * @param array $data 需要插入数据表的键值对数组
	 * @return int
	 */
	public function add($data) {
		$rs = $this->db()->insert($this->_table, $data);
		return $rs;
	}
	
	/**
	 * 修改图片数据,返回受影响的记录数
	 * @param array $data 这个是数据库的字段与值的健值对应数组
	 * @param string $where 条件
	 * @return number
	 * @author lwkai 2013-4-17 下午4:13:40
	 */
	public function update($data, $where) {
		$rs = $this->db()->update($this->_table, $data, $where);
		return $rs;
	}

	/**
	 * 删除图片
	 * @param int $id 游记ID
	 * @author lwkai 2013-4-9 下午1:23:25 
	 */
	public function del($id) {
		$sql = "select * from travel_image where travel_notes_id='" . intval($id) . "'";
		$rs = $this->db()->query($sql)->getAll();
		foreach ($rs as $key => $val) {
			// 这里写上删除图片的代码
			foreach(self::$_size as $k => $v) {
				if ($k == 'face') continue; //我的发现页面需要的图,删除游记的时候才删除
				$img = DIR_FS_ROOT . 'upimg' . DS . $v['width'] . 'x' . $v['height'] . $val['image_src'];
				$this->file()->delete($img);
			}
			//删掉原图
			$this->file()->delete(DIR_FS_ROOT . 'upimg' . DS . 'source' . $val['image_src']);
			$rtn = $this->delOne($val['image_id'],true);
		}
		return $rtn;
	}
	
	/**
	 * 删除一条心情
	 * @param int $id 心情ID
	 * @param boolean $del 图片文件是否已经删除，true 表示不需要再删除图片
	 * @return Ambigous <number, number>
	 * @author lwkai 2013-4-27 下午1:48:13
	 */
	public function delOne($id,$del = false) {
		if ($del == false) {
			$sql = "select image_src from " . $this->_table . " where " . $this->_field_prefix . "_id='" . intval($id) . "'";
			$rs = $this->db()->query($sql)->getOne();
			foreach(self::$_size as $k=>$v) {
				if ($k == 'face') continue;
				$img = DIR_FS_ROOT . 'upimg' . DS . $v['width'] . 'x' . $v['height'] . $rs['image_src'];
				$this->file()->delete($img);
			}
			//删掉原图
			$this->file()->delete(DIR_FS_ROOT . 'upimg' . DS . 'source' . $rs['image_src']);
		}
		// 初始化EXIF类，删除掉对应的EXIF信息
		$exif = new Exif_Manage();
		$exif->del($id);
		return parent::del($id);
	}
	
	/**
	 * 根据图片ID取得游记ID
	 * @param int $image_id 图片ID
	 * @return int
	 * @author lwkai 2013-4-12 下午23:27:15
	 */
	public function getTravelId($image_id) {
		$rs = $this->db()->query("select travel_notes_id from " . $this->_table . " where " . $this->_field_prefix . "_id='" . intval($image_id) . "'")->getOne();
		return intval($rs['travel_notes_id']);
	}
	
	/**
	 * 根据传入的ID取得对应的所有图片
	 * @param string $by 排序条件，不传，则不进行排序,注[不需要写 order by 关键字]
	 * @return array
	 * @author lwkai 2013-4-16 下午1:23:34
	 */
	public function getList($where = '') {
		$sql = "select * from " . $this->_table;// . " where travel_notes_id='" . intval($id) . "'";
		if ($where != '') {
			$sql .= ' where ' . $where;
		}
		$rtn = $this->db()->query($sql)->getAll();
		return $rtn;
	}
	
	/**
	 * 浏览数加1
	 * @param int $id 增加浏览数的ID
	 * @author lwkai 2013-5-3 下午2:29:39
	 */
	public function addViews($id) {
		return $this->update(array('read_number'=>'read_number + 1'), 'image_id=' . intval($id));
	}
	
	/**
	 * 根据图片ID取得浏览数
	 * @param int $id 图片ID
	 * @return Ambigous <>|number
	 * @author lwkai 2013-5-3 下午3:05:58
	 */
	public function getViews($id) {
		$sql = "select read_number from " . $this->_table . " where image_id='" . intval($id) . "'";
		$rs = $this->db()->query($sql)->getOne();
		if ($rs) {
			return $rs['read_number'];
		} else {
			return 0;
		}
	}
	
	/**
	 * 根据游记ID取得总天数
	 * @param int $id 游记ID
	 * @return int 
	 * @author lwkai 2013-4-22 上午9:34:41
	 */
	public function getCountDay($id) {
		$sql = "select time_taken from " . $this->_table . " where travel_notes_id='" . intval($id) . "' order by time_taken asc limit 1";
		$rs = $this->db()->query($sql)->getOne();
		$start_date = $rs['time_taken'];
		$sql = "select time_taken from " . $this->_table . " where travel_notes_id='" . intval($id) . "' order by time_taken desc limit 1";
		$rs = $this->db()->query($sql)->getOne();
		$end_date = $rs['time_taken'];
		$day_temp = round((strtotime($end_date)-strtotime($start_date))/3600/24) + 1;
		return $day_temp;
	}
	
	/**
	 * 根据用户取得他发布的所有图片总数
	 * @param int $userid 用户ID
	 * @return number
	 * @author lwkai 2013-4-23 上午9:52:51
	 */
	public function getUserImagesNum($userid) {
		$sql = "select count(img.image_id) as number from " . $this->_table . " as img, travel_notes as tn where tn.travel_notes_id=img.travel_notes_id and customers_id='" . intval($userid) . "'";
		$rs = $this->db()->query($sql)->getOne();
		return $rs['number'];
	}
	
	/**
	 * 根据图片ID取得对应行的数据
	 * @param int $image_id 图片ID
	 * @return array
	 * @author lwkai 2013-4-17 下午3:40:57
	 */
	public function get($image_id) {
		$sql = "select * from " . $this->_table . " where " . $this->_field_prefix . "_id='" . intval($image_id) . "'";
		$rs = $this->db()->query($sql)->getOne();
		return $rs;
	}
	
	/**
	 * 根据产品ID取得最新的几个图片
	 * @param int $productid 产品ID
	 * @param int $num 需要的图片数量
	 * @return array
	 */
	public function getImageByProductId($productid, $num=6){
		$num = intval($num) > 0 ? intval($num) : 6;
		$sql = "select tn.travel_notes_id,ti.image_src,tn.customers_id from travel_image ti,travel_notes tn where tn.products_id='" . intval($productid) . "' and ti.travel_notes_id=tn.travel_notes_id order by ti.image_id desc limit " . $num;
		$rs = $this->db()->query($sql)->getAll();
		return $rs;
	}
}
?>