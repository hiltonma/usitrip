<?php 
/**
 * 添加旅游照片控制器
 * @author lwkai
 * @date 2012-11-12 下午6:03:14
 * @link <1275124829@163.com>lwkai
 * @formatter:off
 */
class Album_Con extends Abstract_Default {
    public function __construct($module) {
        parent::__construct($module);
    }
    
    /**
     * 添加游记照片页面
     * 
     * @author lwkai 2013-1-29 下午6:06:49
     */
    public function add_action() {
    	if(!isset($_SESSION['customer_id']) || $_SESSION['customer_id'] == '') {
    		header('Location:' . $this->_url->create('',array('module'=>'index')));
    	}
    	$this->_data['current_js_file'] = 'index.js';
    	$this->_data['current_css_file'] = 'addalbum-201301081326.css';
    	$this->_data['head_title'] = 'usitrip走四方-华人首选出国旅游网_专业美国华人旅行社_旅游攻略景点线路_签证移民留学游学';
    	$this->_data['test_product_url'] = $this->_url->create('',array('product_id'=>1,'module'=>'product_info'));
    	$this->_data['test_page_2'] = $this->_url->create('',array('module'=>'product_list','cpath'=>25,'tabs'=>'topic','page'=>2));
    	$this->_template_file = 	'addalbum.html';
    }
    
    /**
     * 添加游记页面 根据用户输入的目的地返回相应提示信息
     * 
     * @author lwkai 2013-1-28 下午1:23:14
     */
    public function searchdestin_action() {
    	$key = isset($_GET['q']) ? $_GET['q'] : '';
    	if(!$key) return;
    	$key = iconv('utf-8', 'gb2312//IGNORE', $key);
    	$arr = Attractions_Usitrip::getAttractionsByWord($key);
    	echo (json_encode(Convert::iconv('gb2312', 'utf-8', $arr)));
    	//echo '[{"name":"石家庄—河北","id":271274},{"name":"朔州—山西","id":271290},{"name":"沈阳—辽宁","id":271296},{"name":"四平—吉林","id":271312},{"name":"松原—吉林","id":271316},{"name":"双鸭山—黑龙江","id":271325},{"name":"绥化—黑龙江","id":271330},{"name":"宿迁—江苏","id":271335},{"name":"苏州—江苏","id":271344},{"name":"绍兴—浙江","id":271350}]';
    }
    
    /**
     * 添加游记页面 根据用户输入的拍摄地点返回提示信息
     * 
     * @author lwkai 2013-1-29 上午11:35:32
     */
    public function searchpoi_action() {
    	$key = isset($_GET['q']) ? $_GET['q'] : '';
    	if (!$key) return;
    	$key = Convert::iconv('utf-8', 'gb2312', $key);
    	$arr = Attractions_Usitrip::getAttractionsByWord($key);
    	echo json_encode(Convert::iconv('gb2312', 'utf-8', $arr));
    	//echo '[{"name":"阳泉","id":271287},{"name":"运城","id":271292},{"name":"营口","id":271303},{"name":"延边","id":271318},{"name":"伊春","id":271326},{"name":"盐城","id":271337},{"name":"扬州","id":271338},{"name":"鹰潭","id":271386},{"name":"宜春","id":271389},{"name":"烟台","id":271397}]';
    }
    
    /**
     * 添加游记页面 检测游记标题是否存在  
     * 以下内容是表示可用
     * {"isReName":true}
     * @author lwkai 2013-1-29 上午9:25:05
     */
    public function check_action() {
    	$name = isset($_GET['name']) ? $_GET['name'] : '';
    	$name = empty($name) ? (isset($_POST['name']) ? $_POST['name'] : '') : $name;
    	if ($name) {
    		$name = Convert::iconv('utf-8','gb2312', $name);
    		$travel = new Travel();
    		$rtn = $travel->checkTravelTitle($name);
    		if ($rtn) {
    			echo '{"isReName":false}';
    		} else {
    			echo '{"isReName":true}';
    		}
    	} else {
    		echo '{"isReName":true}';
    	}
    }
    
    /**
     * 根据用户输入 查找当前用户购买过的线路
     * 
     * @author lwkai 2013-3-20 下午6:09:43
     */
    public function searchtrip_action(){
    	$key = isset($_GET['q']) ? $_GET['q'] : '';
    	$key = Convert::iconv('utf-8', 'gb2312', $key);
    	$arr = Attractions_Usitrip::getOrdersProducts($key);
    	foreach ($arr as $key => $val) {
    		$val['name'] = str_replace('*','',$val['name']);
    		$arr[$key] = $val;
    	}
    	echo json_encode(Convert::iconv('gb2312', 'utf-8', $arr));
    }
    
    /**
     * 添加游记页面 上传图片接收函数
     * 
     * @author lwkai 2013-1-29 上午11:23:58
     */
    public function upimg_action() {
    	$ext = $_FILES['myfile']['name'];
    	$ext = strtolower(substr($ext,strrpos($ext,'.') + 1));
    	$name = microtime(true) . '.' . $ext;
    	// 根据年月日 创建目录
    	$datePath = date('Y-m-d');
		$datePath = str_replace("-",DS,$datePath);
    	$file = new File();
		$file->createDir(DIR_FS_ROOT . 'upimg' . DS . 'source' . DS . $datePath);
    	$img = DIR_FS_ROOT . 'upimg' . DS . 'source' . DS . $datePath . DS . $name;
    	move_uploaded_file($_FILES['myfile']['tmp_name'], $img);

    	$pic_modified_time = filectime($img);
    	list($picWidth,$picHeight) = getimagesize($img);

    	$exif_obj = new Exif($img);
    	$exif = $exif_obj->getExif();

    	// 创建图片操作对象
    	$image = new Image();
    	// 生成站点所需要的一系列图片，并返回预览图，这里面不包括封面图的生成。
    	$PreviewPic = $image->handlePic($img,$exif_obj->getOrientationOfNumber($exif['picture']['Orientation']));
		
    	$str = '{"exif":{"Camera_Time":"' . ($exif['shooting']['DateTimeOriginal'] ? $exif['shooting']['DateTimeOriginal'] : date('Y:m:d H:i:s',$pic_modified_time)) . '",';
    	$str .= '"Camera_Model":"' . $exif['picture']['Model'] . '",';
    	$str .= '"Fnumber":"' . $exif['shooting']['ApertureValue'] . '",';
    	$str .= '"Camera_ISO":"' . $exif['shooting']['ISOSpeedRatings'] . '",';
    	$str .= '"Exposure_Time":"' . $exif['shooting']['ExposureTime'] . '",';
    	$str .= '"Exposure_Bias":"' . $exif['shooting']['ExposureBiasValue'] . '",';
    	$str .= '"Focal_Length":"' . $exif['shooting']['FocalLength'] . '",';
    	$str .= '"GpsLongitude":0,';
    	$str .= '"GpsLatitude":0,';
    	$str .= '"Image_Width":' . ($exif['shooting']['ExifImageLength'] ? $exif['shooting']['ExifImageLength'] : 0) . ',';
    	$str .= '"Image_height":' . ($exif['shooting']['ExifImageWidth'] ? $exif['shooting']['ExifImageWidth'] : 0) . ',';
    	$str .= '"Pic_Modified_Time":' . $pic_modified_time . ',';
    	$str .= '"name":"' . $_FILES['myfile']['name'] . '",';
    	$str .= '"image_Width":0,';
    	$str .= '"image_height":0,';
    	$str .= '"pic_Modified_Time":0},';
    	
    	$str .= '"activity":[{"length":' . $picHeight . ',';
    	$str .= '"width":' . $picWidth . ',';
    	$str .= '"url":"' . $PreviewPic . '",';
    	$str .= '"atType":2,';
    	$str .= '"src":"' . DIR_WS_ROOT . 'upimg/source/' . str_replace("\\",'/',$datePath) . '/' . $name . '",'; //原图地址
    	$str .= '"textContent":true}]}';
    	echo $str;
    }
    
    /**
     * 保存游记
     * @author lwkai 2013-2-26 上午10:28:23
     */
    public function save_action(){
    	$param = isset($_POST['param']) ? $_POST['param'] : '';
    	if (!$param || !isset($_SESSION['customer_id']) || empty($_SESSION['customer_id'])) {
    		echo '{"albumId":null,"msg":"Error"}';
    	} else {
    		$travel = new Travel();
    		$rtn = $travel->add($param);
    		echo '{"albumId":' . $rtn . '}';
    		//echo '{"albumId":' . $travel_notes_id . '}';
    	}
    }
    
    /**
     * ajax修改游记标题
     * 
     * @author lwkai 2013-3-22 下午5:57:04
     */
    public function saveminalbum_action(){
    	$trip_notes_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    	if((!isset($_SESSION['customer_id']) || $_SESSION['customer_id'] == '') && !isset($_SESSION['lvtu_login_id'])) {
    		echo '{"albumId":' . $trip_notes_id . ',"isInner":true}';
    		return;
    	}
    	if (!$trip_notes_id) {
    		echo '{"albumId":' . $trip_notes_id . ',"isInner":true}';
    	} else {
    		$destination = isset($_GET['destination']) ? $_GET['destination'] : '';
    		$title = isset($_GET['paName']) ? $_GET['paName'] : '';
    		$title = Convert::iconv('utf-8', 'gb2312', $title);
    		$title = Convert::db_input($title);
    		$data = array();
    		if (!empty($destination)) {
	    		$data['destination'] = $destination;
    		}
    		if (!empty($title)) {
	    		$data['travel_notes_title'] = $title;
    		}
    		$travel = new Travel();
    		$rtn = $travel->update($data, "travel_notes_id = '" . $trip_notes_id . "'");
			if ($rtn) {
    			echo '{"albumId":' . $trip_notes_id . ',"isInner":false}';
			} else {
				echo '{"albumId":' . $trip_notes_id . ',"isInner":true}';
			}
    	}
    }
    
    /**
     * 添加每天的文字描述
     * 
     * @author lwkai 2013-3-25 上午11:11:29
     */
    public function addfeel_action() {
    	$rtn = array();
    	if(!isset($_SESSION['customer_id']) || $_SESSION['customer_id'] == '') {
    		$rtn['code'] = -1;
    		echo json_encode($rtn);
    		return;
    	}
    	$context = isset($_GET['desc']) ? $_GET['desc'] : '';
    	$tag = ((isset($_GET['before']) || isset($_GET['after'])) ? (isset($_GET['before']) ? 'before' : (isset($_GET['after']) ? 'after' : '')) : '');
    	$albumid = isset($_GET['albumId']) ? intval($_GET['albumId']) : 0;
    	$day = (isset($_GET['day']) ? $_GET['day'] : '');
    	$context = Convert::iconv('utf-8', 'gb2312', $context);
    	if ($albumid > 0) {
    		$data = array();
    		$data['description'] = $context;
    		$data['travel_notes_id'] = $albumid;
    		$data['time_taken'] = $day;
    		$data['tag'] = $tag;
    		$mood = new Mood();
    		$insertid = $mood->add($data);
    		$rtn['code'] = 0;
    		$rtn['id'] = ($insertid ? $insertid : -1);
    		echo json_encode($rtn);
    	}
    }
    
    /**
     * 修改每天的文字描述
     * 
     * @author lwkai 2013-3-25 下午6:06:15
     */
    public function updatefeel_action() {
    	if((!isset($_SESSION['customer_id']) || $_SESSION['customer_id'] == '') && !isset($_SESSION['lvtu_login_id'])) {
    		echo '{"result":null}';
    		return;
    	}
    	$content = isset($_GET['mood_text']) ? $_GET['mood_text'] : '';
    	$content = Convert::iconv('utf-8', 'gb2312', $content);
    	$id = isset($_GET['picTextId']) ? intval($_GET['picTextId']) : 0;
    	if ($id > 0) {
    		$data = array('description'=>Convert::db_input($content));
    		$mood = new Mood();
    		$rtn = $mood->update($data, 'day_id="' . $id . '"');
    		if ($rtn) {
    			echo '{"result":true}';
    		} else {
    			echo '{"result":false}';
    		}
    		return;
    	}
    	echo '{"result":false}';
    }
    
    /**
     * 编辑图片下的说明与拍摄地点
     * 
     * @author lwkai 2013-3-27 下午4:46:03
     */
    public function editwebpic_action() {
    	if(!isset($_SESSION['customer_id']) && !isset($_SESSION['lvtu_login_id'])) {
    		echo '{"editstatus":8000}';
    		return;
    	}
    	$picid = isset($_GET['picId']) ? intval($_GET['picId']) : 0;
    	$addressId = isset($_GET['addressId']) ? intval($_GET['addressId']) : 0;
    	$desc = isset($_GET['desc']) ? $_GET['desc'] : '';
    	$albumid = isset($_GET['albumId']) ? intval($_GET['albumId']) : 0;
    	if ($picid > 0 && !empty($desc)) {
    		$data = array();
    		$data['image_desc'] = Convert::db_input(Convert::iconv('utf-8', 'gb2312', $desc));
    		if ($addressId > 0) {
    			$data['location_id'] = $addressId;
				$adname = Attractions_Usitrip::getAttractionsById($addressId);
				$alibumPicAddressNameHref = $this->_url->create('',array('pagename'=>'advanced_search_result.php','tcw'=>rawurlencode($adname[0]['city'])),array('is_self'=>'usitrip'));
    		}
    		if ($albumid > 0) {
    			$data['travel_notes_id'] = $albumid;
    		}
    		$image = new Image();
    		$rtn = $image->update($data, ' image_id = "' . $picid . '"');
    		if ($rtn) {
    			echo '{"editstatus":0,"href":"' . $alibumPicAddressNameHref . '"}';
    		} else {
    			echo '{"editstatus":-1}';
    		}
    		return;
    	}
    	echo '{"editstatus":-1}';
    }
    
    /**
     * 删除心情和图片，delstatus 的值 80000[未登录],0[成功],-1[失败]
     * @author lwkai 2013-3-26 上午11:59:30
     */
    public function deletewebpic_action() {
    	if((!isset($_SESSION['customer_id']) || $_SESSION['customer_id'] == '') && (!isset($_SESSION['lvtu_login_id']) || $_SESSION['lvtu_login_id'] == '')) {
    		echo '{"delstatus":8000}';
    		return;
    	}
		if (isset($_GET['picId'],$_GET['target'])) {
			$id = intval($_GET['picId']);
			$target = intval($_GET['target']);
			$tag = false;
			switch ($target) {
				case 2:
					$tag = 'Mood';
					break;
				case 1:
					$tag = 'Image';
					break;
				default:
					$tag = $target;
			}
			if ($tag) {
				$travel = new Travel();
				$rtn = $travel->delPicOrMood($id, $tag);
			}
			if ($rtn) {
    			echo '{"delstatus":0}';
    		} else {
    			echo '{"delstatus":-1}';
    		}
		} else {
    		echo '{"delstatus":-1}';
		}
    }
    
    /**
     * 对喜欢的操作处理
     * 
     * @author lwkai 2013-4-1 上午9:58:55
     */
    public function praisepic_action(){
    	$id = isset($_GET['picId']) ? intval($_GET['picId']) : 0;
    	$praiseActivityType = isset($_GET['praiseActivityType']) ? intval($_GET['praiseActivityType']) : 0;
    	$targetType = isset($_GET['targetType']) ? intval($_GET['targetType']) : 0;
    	$userid = isset($_GET['visitorId']) ? intval($_GET['visitorId']) : 0;
		$name = '';
		$status = -1;
		if (isset($_SESSION['customer_id']) && $userid == intval($_SESSION['customer_id'])) {
			$travel = new Travel();
			//$targetType 1为图片，2为心情，3为游记
			switch($targetType) {
				case 1:
					$targetType = 'Image';
					break;
				case 2:
					$targetType = 'Mood';
					break;
				case 3:
					$targetType = 'Travels';
					break;
			}
			$status = $travel->like($id, $targetType, $praiseActivityType, $userid);
		}
    	echo '{"status":' . $status . '}';
    }
    
	/**
	 * 评论AJAX提交处理
	 */
	public function comment_action(){
		$rtn = array('result'=>false);
		if (isset($_POST['picId'],$_POST['targetType'],$_POST['content'],$_SESSION['customer_id'])) {
			$user_id = $_SESSION['customer_id'];
			$content = ($_POST['content'] != '' ? Convert::iconv('utf-8','gb2312',$_POST['content']) : '');
			$id = intval($_POST['picId']);
			$target = intval($_POST['targetType']);
			$typename = false;
			switch ($target) {
				case 1:
					$typename = 'Image';
					break;
				case 2:
					$typename = 'Mood';
					break;
			}
			if ($typename) {
				$data = array(
					'content'=>$content,
					'commented_id'=>$id,
					'user_id'=>$user_id
				);
				$comment = Comment_Factory::getComment($typename);
				$r = $comment->saveComment($data);
				if ($r) {
					$rtn['result'] = true;
				}
			}
		}
		echo json_encode($rtn);
	}
	
	/**
	 * 保存评论回复
	 */
	public function savereply_action(){
		$rtn = array('status'=>false);
		if (isset($_GET['content'],$_GET['picReplyId'],$_GET['targetType'],$_SESSION['customer_id'],$_GET['commentId'])) {
			$user_id = $_SESSION['customer_id'];
			$content = ($_GET['content'] != '' ? Convert::iconv('utf-8','gb2312',$_GET['content']) : '');
			$id = intval($_GET['picReplyId']);
			$target = intval($_GET['targetType']);
			$toid = intval($_GET['commentId']);
			$typename = false;
			switch ($target) {
				case 1:
					$typename = 'Image';
					break;
				case 2:
					$typename = 'Mood';
					break;
			}
			if ($typename) {
				$data = array(
					'content' => $content,
					'commented_id' => $id,
					'user_id'=>$user_id,
					'replay_to'=>$toid
				);
				$comment = Comment_Factory::getComment($typename);
				$r = $comment->replayComment($data);
				if ($r) {
					$rtn['status'] = true;
				}
			}
		}
		echo json_encode($rtn);
	}
	
	/**
	 * 获得已经保存的评论内容
	 */
	public function comments_action(){
		$rtn = array('comments'=>array());	
		if (isset($_GET['pageNo'],$_GET['picId'],$_GET['target'],$_GET['visitorId'],$_SESSION['customer_id'])) {
			$user_id = intval($_GET['visitorId']);
			$target = intval($_GET['target']);
			$id = intval($_GET['picId']);
			$page = intval($_GET['pageNo']);
			if ($_SESSION['customer_id'] == $user_id) {
				$comment = false;
				switch ($target) {
					case 1:
						$comment = Comment_Factory::getComment('Image');
						break;
					case 2:
						$comment = Comment_Factory::getComment('Mood');
				}
				if ($comment) {
					$rs = $comment->getComments($page, $id);
					foreach ($rs as $key => $val) {
						$temp = array(
							'type'        => 0,
							'content'     => htmlspecialchars($val['content']),
							'companyType' => 1,
							'createTime'  => date('m月d日',strtotime($val['create_time'])),
							'commentId'   => $val['comment_id']
						);
						$user_info_rs = User_Usitrip::getUserToComment($val['user_id']);
						$user_info = array(
							'userName'  => $user_info_rs['customers_firstname'],
							'userId'    => $user_info_rs['customers_id'],
							'nickName'  => $user_info_rs['customers_firstname'],
							'headUrl'   => $user_info_rs['customers_face'],
							'gender'    => $user_info_rs['customers_gender'],
							'isDaren'   => 2,
							'newHander' => 10001
						);
						$temp['userInfo'] = $user_info;
						if ($val['replay_to'] > 0) {
							$user_info_rs = User_Usitrip::getUserToComment($val['replay_to']);
							$temp['userToReply'] = array('userId'=>$user_info_rs['customers_id'],'nickName'=>$user_info_rs['customers_firstname']);
						}
						$rtn['comments'][] = $temp;
					}
				}
			}
		}
		echo json_encode(Convert::iconv('gb2312', 'utf-8', $rtn));
		//echo '{"comments":[{"type":0,"content":"谢谢亲欣赏～","userInfo":{"userName":"uyoi7251,1774053493@sina","userId":"114203","nickName":"sophia59zc","headUrl":"http://headshot.user.qunar.com/headshotsByName/uyoi7251.png?l","gender":0,"isDaren":1,"newHander":"11110"},"companyType":1,"createTime":"3月15日","commentId":80516,"userToReply":{"userId":"96761","nickName":"123---123"}},{"type":0,"content":"我就拍不出这个效果啊。拍得真不错我就拍不出这个效果啊。拍得真不错","userInfo":{"userName":"dwag6158","userId":"96761","nickName":"123---123","headUrl":"http://headshot.user.qunar.com/headshotsByName/dwag6158.png?l","gender":0,"isDaren":2,"newHander":"11111"},"companyType":1,"createTime":"3月15日","commentId":80507},{"type":0,"content":"我就拍不出这个效果啊。拍得真不错","userInfo":{"userName":"dwag6158","userId":"96761","nickName":"123---123","headUrl":"http://headshot.user.qunar.com/headshotsByName/dwag6158.png?l","gender":0,"isDaren":2,"newHander":"11111"},"companyType":1,"createTime":"3月15日","commentId":80505},{"type":0,"content":"我就拍不出这个效果啊。拍得真不错123","userInfo":{"userName":"dwag6158","userId":"96761","nickName":"123---123","headUrl":"http://headshot.user.qunar.com/headshotsByName/dwag6158.png?l","gender":0,"isDaren":2,"newHander":"11111"},"companyType":1,"createTime":"3月15日","commentId":80504},{"type":0,"content":"我就拍不出这个效果啊。拍得真不错。。。","userInfo":{"userName":"dwag6158","userId":"96761","nickName":"123---123","headUrl":"http://headshot.user.qunar.com/headshotsByName/dwag6158.png?l","gender":0,"isDaren":2,"newHander":"11111"},"companyType":1,"createTime":"3月15日","commentId":80503},{"type":0,"content":"我就拍不出这个效果啊。拍得真不错","userInfo":{"userName":"dwag6158","userId":"96761","nickName":"123---123","headUrl":"http://headshot.user.qunar.com/headshotsByName/dwag6158.png?l","gender":0,"isDaren":2,"newHander":"11111"},"companyType":1,"createTime":"3月15日","commentId":80501},{"type":0,"content":"我就拍不出这个效果啊。拍得真不错","userInfo":{"userName":"dwag6158","userId":"96761","nickName":"123---123","headUrl":"http://headshot.user.qunar.com/headshotsByName/dwag6158.png?l","gender":0,"isDaren":2,"newHander":"11111"},"companyType":1,"createTime":"3月15日","commentId":80500},{"type":0,"content":"我就拍不出这个效果啊。拍得真不错","userInfo":{"userName":"dwag6158","userId":"96761","nickName":"123---123","headUrl":"http://headshot.user.qunar.com/headshotsByName/dwag6158.png?l","gender":0,"isDaren":2,"newHander":"11111"},"companyType":1,"createTime":"3月15日","commentId":80499},{"type":0,"content":"我就拍不出这个效果啊。拍得真不错","userInfo":{"userName":"dwag6158","userId":"96761","nickName":"123---123","headUrl":"http://headshot.user.qunar.com/headshotsByName/dwag6158.png?l","gender":0,"isDaren":2,"newHander":"11111"},"companyType":1,"createTime":"3月15日","commentId":80498},{"type":0,"content":"我就拍不出这个效果啊。拍得真不错","userInfo":{"userName":"dwag6158","userId":"96761","nickName":"123---123","headUrl":"http://headshot.user.qunar.com/headshotsByName/dwag6158.png?l","gender":0,"isDaren":2,"newHander":"11111"},"companyType":1,"createTime":"3月15日","commentId":80497}]}';
	}
	
	/**
	 * 评论总记录数
	 */
	public function getcommentnum_action(){
		$id = isset($_GET['picId']) ? intval($_GET['picId']) : 0;
		$target = isset($_GET['target']) ? intval($_GET['target']) : 0;
		$user_id = isset($_GET['visitorId']) ? intval($_GET['visitorId']) : 0;
		$rtn = 0;
		$comment = false;	
		switch ($target) {
			case '1':
				$comment = Comment_Factory::getComment('Image');
				break;
			case '2':
				$comment = Comment_Factory::getComment('Mood');
				break;
		}
		if ($comment) {
			$rtn = $comment->getCommentsNum($id);
		}
		echo '{"totalCommentNum":' . $rtn . '}';
	}
    /**
     * 显示添加的游记
     * 
     * @author lwkai 2013-2-26 上午11:35:18
     */
    public function show_action() {
    	$travel_id = isset($_GET['albumid']) ? (int)$_GET['albumid'] : 0;
    	if (empty($travel_id)) {
    		My_Exception::mythrow('notfind', '游记已经删除！');
    		return;
    	}
    	$this->_data['current_css_file'] = 'albumDay-201302261059.css';
    	$this->_data['albumid'] = $travel_id;
    	$travel_obj = new Travel();
    	$travel_obj->addViews($travel_id);
    	if ((!isset($_SESSION['lvtu_login_id']) && SYS_IS_AUDIT == 'true' && !$travel_obj->isVerify($travel_id)) && !$travel_obj->isSelf($travel_id)) {
    		My_Exception::mythrow('notfind', '此游记还未审核！请待审核之后再来！');
    		return;
    	}
    	$rs = $travel_obj->get($travel_id);
    	if (!$rs) {
    		My_Exception::mythrow('notfind', '游记已经删除！');
    		return;
    	}
    	
    	$_list = $travel_obj->getAttractionslist();
    	$list = array(array());
    	foreach($_list as $key => $val) {
    		$tmp = array(
    				'name' => $val,
    				'href' => $this->_url->create('',array('module'=>'index','action'=>'index','attraction'=>$key))
    		);
    		$list[] = $tmp;
    	}
    	$this->_data['list'] = $list;
    	$this->_data['productid'] = $rs['products_id'];
    	$this->_data['to_line_name'] = Attractions_Usitrip::getProductsName($rs['products_id']);
    	$this->_data['to_line_href'] = $this->_url->create('',array('product_id'=>$rs['products_id']),array('is_self'=>'usitrip'));
    	$this->_data['image_number'] = $rs['image_number'];
    	$this->_data['travel_notes_title'] = $rs['travel_notes_title'];
    	$this->_data['like_number'] = $rs['like_number'];
    	$this->_data['replay_number'] = $rs['replay_number'];
    	$this->_data['read_number'] = $rs['read_number'];
		$this->_data['is_like'] = '2';
		$like = Like_Factory::getLike('Travels');
		$rtn = $like->isLike($rs['travel_notes_id'], $_SESSION['customer_id']);
		if ($rtn) {
			$this->_data['is_like'] = '1';
		}
    	$this->_data['my_home'] = $this->_url->create('',array('action'=>'userInfo','userId'=>$rs['customers_id']));
    	if ((isset($_SESSION['customer_id']) && $rs['customers_id'] == $_SESSION['customer_id']) || isset($_SESSION['lvtu_login_id'])) {
    		$this->_data['is_self'] = true;
    		if (isset($_SESSION['lvtu_login_id'])) {
    			$this->_data['album_username'] = Attractions_Usitrip::getUserName($rs['customers_id']);
    		} else {
    			$this->_data['album_username'] = isset($_SESSION['customer_first_name']) ? $_SESSION['customer_first_name'] : '';
    		}
    	} else {
    		$this->_data['is_self'] = false;
    		$this->_data['album_username'] = Attractions_Usitrip::getUserName($rs['customers_id']);
    	}
    	$address = Attractions_Usitrip::getAttractionsById(str_replace(',,',',',trim(str_replace('!', ',', $rs['destination']),',')));
    	$des = ',';
    	foreach($address as $val2) {
    		$des .= $val2['city'] . ',';
    	}
    	$this->_data['to_address'] = trim($des,',');
    	$this->_data['to_address_id'] = $rs['destination'];
    	$image = new Image();
    	$rs = $image->getList("travel_notes_id='" . intval($travel_id) . "' group by time_taken order by time_taken asc");
    	$day = '';
    	$daycount = 0;
    	// URL传过来的第几天的日期
    	$currentDay = isset($_GET['dayid']) ? Convert::db_input($_GET['dayid']) : '';
    	$currentDayStr = '';
    	$firstDay = '';
    	$lastDay = '';
    	$nextDayNum = '';
    	$i = 0;
		$startdate = strtotime($day);
    	foreach($rs as $key => $val) {
    		if ($day == '') {
    			$val['day'] = '第1天';
    			$daycount = 1;
    			$day = $val['time_taken'];
				$startdate = strtotime($day);
    			// 记录第一天的日期，如果传过来的日期查不到数据，则需要用第一天的来查
    			$firstDay = $val['time_taken'];
    			// 如果没从GET传过来日期，则把第一天的日期做为当前日期
    			if (empty($currentDay) || $val['time_taken'] == $currentDay || $i == (int)$currentDay || (is_numeric($currentDay) && (int)$currentDay > count($rs))) {
    				$currentDayStr = $val['day'];
    				$currentDay =  $firstDay;
    				$val['current'] = 'true';
    			}
    		} else {
    			$enddate = strtotime($val['time_taken']);
    			$day_temp = round(($enddate-$startdate)/3600/24) + 1;
    			$daycount = $day_temp;
    			if ($currentDayStr != '' && $nextDayNum == '') {
    				$nextDayNum = $i;
    			}
    			$val['day'] = '第' . $day_temp . '天';
    			$day = $val['time_taken'];
    			if ($currentDay == $day || $i == (int)$currentDay) {
    				$currentDayStr = $val['day'];
    				$val['current'] = 'true';
    				$currentDay = $day;
    			}
    			$lastDay = $val['time_taken'];
    		}
    		if (!empty($val['location_id'])) {
    			$address = Attractions_Usitrip::getAttractionsById($val['location_id']);
    			$val['to_address'] = $address[0]['city'];
    		}
    		$val['href'] = $this->_url->create('',array('action'=>'show','albumid'=>$travel_id,'dayid'=>$val['time_taken']));
    		$rs[$key] = $val;
    		$i++;
    	}
    	$nextDayNum = $nextDayNum == '' ? $i : $nextDayNum;
    	$this->_data['daylist'] = $rs;
    	$this->_data['next'] = count($rs);
    	$this->_data['nextid'] = $nextDayNum;
    	$this->_data['day_count'] = $daycount;
    	$this->_data['current_day'] = $currentDay;
    	$this->_data['current_day_str'] = $currentDayStr;
    	$this->_data['last_day'] = $lastDay;
    	$this->_data['first_day'] = $firstDay;
    	
    	/* 取得当前日期的图片数据 */
    	$showmap = false;
		// 图片喜欢操作类，检测当前用户，对每个图片的喜欢标记状态
		$like = Like_Factory::getLike('Image');
		$image = new Image();
		$wh = $image->getSize('list');
    	$rs = $image->getList("travel_notes_id='" . $travel_id . "' and time_taken='" . $currentDay . "'");
    	foreach($rs as $key => $val) {
    		$image->addViews($val['image_id']);
    		if (!empty($val['location_id'])) {
    			$adname = Attractions_Usitrip::getAttractionsById($val['location_id']);
    			$val['addressName'] = $adname[0]['city'];
				$val['alibumPicAddressNameHref'] = $this->_url->create('',array('pagename'=>'advanced_search_result.php','tcw'=>rawurlencode($val['addressName'])),array('is_self'=>'usitrip'));
    			if (intval($this->_data['productid']) > 0) {
    				$showmap = true;
    			}
    		}
			// 检测当前用户是否对这个图片进行了喜欢操作
			$rtn = $like->isLike($val['image_id'], $_SESSION['customer_id']);
			$val['is_like'] = '2';
			if ($rtn) {
				$val['is_like'] = '1';
			}
			list($picWidth,$picHeight) = getimagesize(DIR_FS_ROOT . 'upimg' . DS . $wh['width'] . 'x' . $wh['height'] . $val['image_src']);
    		$val['image_src'] = DIR_WS_ROOT . 'upimg/' . $wh['width'] . 'x' . $wh['height'] . $val['image_src'];
    		$val['image_href'] = $this->_url->create('',array('action'=>'picwebdetail','albumid'=>$val['travel_notes_id'],'picid'=>$val['image_id'])) . '#' . $val['image_id'];//album/picWebDetail/picId--3.html#3
    		$val['image_desc'] = htmlspecialchars($val['image_desc']);
    		$val['width'] = $picWidth;
    		$val['height'] = $picHeight;
    		$rs[$key] = $val;
    		
    	}
    	
    	// 根据当前日期 取得当天心情
    	$like = Like_Factory::getLike('Mood');
    	$mood = new Mood();
    	if ($image->getList("travel_notes_id='" . $travel_id . "'")) { // 如果当前游记还有图片，则按天数来取心情，如果没有图片了，则去取所有心情，避免因为没有图片后，之前的心情也不显示。
    		$beforelist = $mood->getList("travel_notes_id='" . $travel_id . "' and time_taken='" . $currentDay . "' and tag='before' order by day_id desc");
    	} else {
    		$beforelist = $mood->getList("travel_notes_id='" . $travel_id . "' and tag='before' order by day_id desc");
    	}
    	foreach($beforelist as $key=>$val) {
    		$mood->addViews($val['day_id']);
    		$val['description'] = htmlspecialchars($val['description']);
			// 检测当前用户对心情的喜欢操作状态
			$rtn = $like->isLike($val['day_id'],$_SESSION['customer_id']);
			$val['is_like'] = '2';
			if ($rtn) {
				$val['is_like'] = '1';
			}
    		$beforelist[$key] = $val;
    	}
    	// 根据当前日期 取得当天底部心情
    	if ($image->getList("travel_notes_id='" . $travel_id . "'")) {
    		$afterlist = $mood->getList("travel_notes_id='" . $travel_id . "' and time_taken='" . $currentDay . "' and tag='after' order by day_id desc");
    	} else {
    		$afterlist = $mood->getList("travel_notes_id='" . $travel_id . "' and tag='after' order by day_id desc");
    	}
    	foreach($afterlist as $key=>$val) {
    		$mood->addViews($val['day_id']);
    		$val['description'] = htmlspecialchars($val['description']);
			// 检测当前用户对心情的喜欢操作状态
			$rtn = $like->isLike($val['day_id'],$_SESSION['customer_id']);
			$val['is_like'] = '2';
			if ($rtn) {
				$val['is_like'] = '1';
			}
    		$afterlist[$key] = $val;
    	}
    	$this->_data['beforelist'] = $beforelist;
    	$this->_data['afterlist'] = $afterlist;
    	$this->_data['showmap'] = $showmap;
    	$this->_data['piclist'] = $rs;
    	$this->_template_file = 'album.html';
    }
    
    /**
     * 幻灯模式显示游记
     * 
     * @author lwkai 2013-2-28 下午4:54:08
     */
    public function picwebdetail_action(){
    	$this->_data['current_css_file'] = 'picture-201302261140.css';
    	$travel_id = isset($_GET['albumid']) ? intval($_GET['albumid']) : 0;
    	if ($travel_id == 0) {
    		My_Exception::mythrow('notfind', '您访问的游记不存在！');
    		return;
    	}
    	$pic_id = isset($_GET['picid']) ? intval($_GET['picid']) : 0;
    	$travel = new Travel();
    	if (!$travel->isHave($travel_id)) {
    		My_Exception::mythrow('notfind', '您访问的游记不存在！');
    		return;
    	}
    	//$travel_user_id = $travel->getUserId($travel_id);
    	$travel_rs = $travel->get($travel_id);
    	$image = new Image();
		$pic_pre_list = $image->getList("travel_notes_id='" . $travel_id . "' order by time_taken asc");
		$wh = Image::getSize('projector');
		$preview_wh = Image::getSize('preview');
		$current = array('visitorId'=>$_SESSION['customer_id'],'travel_id'=>$travel_id);
		$exif = array();
		$exif_obj = new Exif_Manage();
		// 图片喜欢操作类，检测当前用户，对每个图片的喜欢标记状态
		$like = Like_Factory::getLike('Image');
		$day = '';
		$startdate = 0;
		$index = 0;
		foreach($pic_pre_list as $key => $val) {
			$val['source_src'] = DIR_WS_ROOT . 'upimg/source' . $val['image_src'];
			$val['preview_src'] = DIR_WS_ROOT . 'upimg/' . $preview_wh['width'] . 'x' . $preview_wh['height'] . $val['image_src'];
			list($picWidth,$picHeight) = getimagesize(DIR_FS_ROOT . 'upimg' . DS . $wh['width'] . 'x' . $wh['height'] . $val['image_src']);
			$val['width'] = $picWidth;
			$val['height'] = $picHeight;
			$val['image_src'] = DIR_WS_ROOT . 'upimg/' . $wh['width'] . 'x' . $wh['height'] . $val['image_src'];
			$val['exif'] = $exif_obj->get($val['image_id']);
			$val['href'] = $this->_url->create('',array('action'=>'picwebdetail','albumid'=>$travel_id,'picid'=>$val['image_id'])) . '#' . $val['image_id'];
			$val['picUserIds'] = $travel_rs['customers_id'];
			$val['nickName'] = Attractions_Usitrip::getUserName($travel_rs['customers_id']);
			// 检测当前用户是否对这个图片进行了喜欢操作
			$rtn = $like->isLike($val['image_id'], $_SESSION['customer_id']);
			$val['is_like'] = '2';
			if ($rtn) {
				$val['is_like'] = '1';
			}
			// 取得每个图片的拍摄地点
			if (!empty($val['location_id'])) {
				$adname = Attractions_Usitrip::getAttractionsById($val['location_id']);
				$val['addressName'] = $adname[0]['city'];
				$val['alibumPicAddressNameHref'] = $this->_url->create('',array('pagename'=>'advanced_search_result.php','tcw'=>rawurlencode($val['addressName'])),array('is_self'=>'usitrip'));
				//$showmap = true;
			}

			// 算出图片拍摄天数
			if ($day == '' || $day == $val['time_taken']) {
				$day = $val['time_taken'];
				$startdate = strtotime($val['time_taken']);
				$day_temp = 1;
				$val['day'] = '第1天' . (isset($val['addressName']) ? '●' . $val['addressName'] : '');
			} else {
				$enddate = strtotime($val['time_taken']);
				$day_temp = round(($enddate-$startdate)/3600/24) + 1;
				$val['day'] = '第' . $day_temp . '天' . (isset($val['addressName']) ? '●' . $val['addressName'] : '');
			}
			
			$current['albumPicAddressId'] = $val['location_id'];
			$current['albumPicAddressName'] = $val['addressName'];
			
			// 当前图片的处理
			if (intval($val['image_id']) == $pic_id || $pic_id == 0) {
				$current['source_img'] = $val['source_src'];
				$current['image'] = $val['image_src'];
				$current['image_desc'] = $val['image_desc'];
				$current['width'] = $val['width'];
				$current['height'] = $val['height'];
				$current['currIsPraised'] = $val['is_like'];
				$current['image_id'] = $val['image_id'];
				$current['imgindex'] = $index;
				$current['newuserId'] = $travel_rs['customers_id'];
				$current['albumName'] = $travel_rs['travel_notes_title'];
				if (!empty($val['location_id'])){
					$current['albumPicAddressId'] = $val['location_id'];
				}
				if (!empty($val['addressName'])) {
					$current['albumPicAddressName'] = $val['addressName'];
				}
				if (!empty($val['alibumPicAddressNameHref'])){
					$current['alibumPicAddressNameHref'] = $val['alibumPicAddressNameHref'];
				}
				$current['like_number'] = $travel_rs['like_number'];
				$current['read_number'] = $travel_rs['read_number'];
				$current['replay_number'] = $travel_rs['replay_number'];
				$current['product_href'] = $this->_url->create('',array('product_id'=>$travel_rs['products_id']),array('is_self'=>'usitrip'));
				$current['product_name'] = Attractions_Usitrip::getProductsName($travel_rs['products_id']);
				$current['currentDay'] = $day_temp;
				$current['userInfoUrl'] = $this->_url->create('',array('action'=>'userinfo','userid'=>$travel_rs['customers_id']));
				$exif = $val['exif'];
				$current['username'] = Attractions_Usitrip::getUserName($current['newuserId']);
				//$val['class'] = true;
				$pic_id = $val['image_id'];
			}
			$pic_pre_list[$key] = $val;
			$index ++;
		}
		$this->_data['exif'] = $exif;
		$this->_data['current'] = $current;
		//$this->
		$this->_data['picPreList'] = $pic_pre_list;
		$this->_data['goback'] = $this->_url->create('',array('action'=>'show','albumid'=>$travel_id));
    	$this->_template_file = 'picWebDetail.html';
    }
    
    /**
     * 用户主页
     * 
     * @author lwkai 2013-2-28 下午5:01:24
     */
    public function userinfo_action(){
    	$this->_data['current_css_file'] = 'personal-201302281658.css';
    	$userid = isset($_GET['userid']) ? $_GET['userid'] : (isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : '');
    	if (!$userid) {
    		header('Location:' . $this->_url->create('',array('ret'=>$this->_url->create('',$_GET),'pagename'=>'login.php'),array('is_self' => 'usitrip')));
    	} else {
    		$pagesize = 6;
    		$travel = new Travel();
    		$this->_data['host_id'] = $userid;
    		$this->_data['guest_id'] = (isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : '');
    		$this->_data['page'] = (isset($_GET['page']) ? intval($_GET['page']) : 1);

    		$rs = $travel->getUserList($userid, $pagesize, 'add_time desc');
    		$this->_data['albumNum'] = $travel->getRowsTotal();
    		$this->_data['pageSize'] = $pagesize;
    		$arr = array();
    		$view_counts = 0;
    		$mood = new Mood();
    		foreach($rs as $val) {
    			$wh = Image::getSize('face');
    			$val['image_src'] = DIR_WS_ROOT . 'upimg/' . $wh['width'] . 'x' . $wh['height'] . $val['cover_image'];//$rs2['image_src'] . '.600x400.jpg';
    			$ids = $val['destination'];
    			$ids = str_replace(',,',',',trim(str_replace('!', ',', $ids),','));
    			$destination = Attractions_Usitrip::getAttractionsById($ids);
    			$des = ',';
    			foreach($destination as $val2) {
    				$des .= $val2['city'] . ',';
    			}
    			$val['destination'] = trim($des,',');
    			$val['add_text'] = $this->_url->create('',array('action'=>'show','albumid'=>$val['travel_notes_id']));
    			
    			$val['day_desction'] = $mood->getOneByTravelId($val['travel_notes_id']);
    			$val['add_time'] = date('Y/m/d',strtotime($val['add_time']));
    			$arr[] = $val;

    			$view_counts += $val['read_number'];
    		}
    		$this->_data['piclist'] = $arr;
    		$this->_data['view_counts'] = $view_counts;
    		$this->_data['travel_counts'] = $this->_data['albumNum'];
    		$this->_data['pic_counts'] = $travel->getUserImagesNum($userid);
    		$user_info_rs = User_Usitrip::getUserToComment($userid);
    		$this->_data['user_image'] = $user_info_rs['customers_face'];
    		$this->_data['user_index_href'] = $this->_url->create('',array('userid'=>$userid));
    		$this->_data['username'] = $user_info_rs['customers_firstname'];
    		$this->_template_file = 'myindex.html';
    	}
    }
    
    /**
     * 针对已经存在的游记添加图片
     * @author lwkai 2013-3-26 下午2:45:24
     */
    public function addpicalbum_action() {
    	if(!isset($_SESSION['customer_id']) || $_SESSION['customer_id'] == '') {
    		$url = $this->_url->create('',array('ret'=>$this->_url->create('',$_GET),'pagename'=>'login.php'),array('is_self' => 'usitrip'));
			header('Location:' . $url);
    	}
    	$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    	if (empty($id)) {
    		My_Exception::mythrow('notfind', '对不起！游记不存在或者已经删除！');
    		return;
    	}
    	$travel = new Travel();
    	if ($travel->isHave($id)) {
    		$rs = $travel->get($id);
	    	$this->_data['travel_title'] = $rs['travel_notes_title'];
	    	$this->_data['travel_id'] = $rs['travel_notes_id'];
	    	$this->_data['current_css_file'] = 'editalbum-201303261350.css';
	    	$this->_template_file = 'addPicAlbum.html';
    	} else {
    		My_Exception::mythrow('notfind', '对不起！该游记已经不存在或者已经删除！');
    		return;
    	}
    }
    
    /**
     * 删除游记, 同时删除掉对应的图片，评论等。
     * 
     * @author lwkai 2013-4-9 上午11:50:12
     */
    public function deletealbum_action() {
    	$rtn = -1;
    	if((!isset($_SESSION['customer_id']) || $_SESSION['customer_id'] == '') && (!isset($_SESSION['lvtu_login_id']) || $_SESSION['lvtu_login_id']=='')) {
    		$rtn = 8000;
    	} else {
	    	if (isset($_GET['id']) && intval($_GET['id'])) {
	    		$id = intval($_GET['id']);
	    		$travel = new Travel();
	    		$user_id = $travel->getUserId($id); 
	    		$rtn = $travel->del($id);
	    		if($rtn) {
	    			$rtn = 0;
	    		} else {
	    			$rtn = -1;
	    		}
	    	}
    	}
    	echo '{"delAlbumFlag":' . $rtn . ',"userLoginId":"' . $user_id . '"}';
    }
    
    /**
     * 针对已经存在的游记添加图片,此处可针对游记添加图片，
     * 也可对针某一天添加图片，如果有POST date 参数，则为针对某一天添加图片
     */
    public function savepictureother_action(){
    	$param = isset($_POST['param']) ? $_POST['param'] : '';
    	$date = isset($_POST['date']) ? $_POST['date'] : '';
    	if (!$param || !isset($_SESSION['customer_id']) || empty($_SESSION['customer_id'])) {
    		echo '{"albumId":null,"msg":"Error"}';
    	} else {
    		$travel = new Travel();
    		$rtn = $travel->addImage($param,$date);
    		$rtn || $rtn = 'null';
    		echo '{"albumId":' . $rtn . '}';
    	}
    }
    
    /**
     * 取得当前用户的所有游记
     * 
     * @author lwkai 2013-4-17 下午1:58:59
     */
    public function getalbumnamelist_action(){
    	$rtn = array('getANLResult'=>-1,'picAlbumList'=>array());
    	if (isset($_GET['albumId'],$_SESSION['customer_id'])) {
	    	$albumid = (isset($_GET['albumId']) ? intval($_GET['albumId']) : 0);
	    	if ($albumid) {
	    		$travel = new Travel();
	    		$user_id = $travel->getUserId($albumid);
	    		$travel_rs = $travel->getUserListAll($user_id);
	    		$temp_arr = array();
	    		foreach($travel_rs as $key=>$val) {
	    			$temp_arr[] = array('id'=>$val['travel_notes_id'],'name' => $val['travel_notes_title']);
	    		}
	    		$rtn['picAlbumList'] = $temp_arr;
	    		$rtn['getANLResult'] = 0;
	    	} else {
	    		$rtn['getANLResult'] = -1;
	    	}
    	} else {
    		$rtn['getANLResult'] = 8000;
    	}
    	echo json_encode(Convert::iconv('gb2312', 'utf-8', $rtn));
    }
    /**
     * 保存AJAX提交的设某个图为封面图的操作
     * 
     * @author lwkai 2013-4-17 下午3:01:29
     */
    public function savecover_action() {
    	$rtn = -1;
    	if (isset($_GET['picId'], $_GET['albumId'], $_SESSION['customer_id'])) {
    		$pic_id = intval($_GET['picId']);
    		$album_id = intval($_GET['albumId']);
    		$travel = new Travel();
    		$travel->updateCover($album_id, $pic_id);
    		$rtn = 200;
    	} else {
    		$rtn = 8000; 
    	}
    	echo '{"code":' . $rtn . '}';
    }
    
    /**
     * 提供接口给主站用，主站的照片分享显示的图片在此获得
     * @author lwkai 2013-07-18 17:03
     */
    public function getProductsImages_action() {
    	$rtn = array();
    	if (isset($_GET['pid'])) {
    		$products_id = intval($_GET['pid']);
    		$image = new Image();
    		$rtn = $image->getImageByProductId($products_id);
    		foreach($rtn as $key => $val) {
    			$val['pic'] = CN_HTTP_SERVER . DIR_WS_ROOT . 'upimg/225x186' . $val['image_src'];
    			$val['username'] = Attractions_Usitrip::getUserName($val['customers_id']);
    			$val['href'] = $this->_url->create('',array('module'=>'album','action'=>'show','albumid'=>$val['travel_notes_id']));
    			unset($val['image_src'],$val['customers_id'],$val['travel_notes_id']);
    			$rtn[$key] = $val; 
    		}
    	}
    	echo json_encode(Convert::iconv('gb2312', 'utf-8', $rtn));
    }
    
    public function __destruct(){
    	parent::__destruct();
    }
}
