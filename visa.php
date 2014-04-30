<?php
//本页不能使用SSL连接，否则下不了签证单
if($_SERVER['SERVER_PORT']=='443'){
	$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	header('Location: ' . $url);
	exit;
}

require('includes/application_top.php');
require('includes/classes/visa.php');

//通过产品名字取得Title信息
function get_visa_info_title($visa_product,$visa_purpose){
	$data =false;
	$rt = '';
	for($i=0, $n=count($visa_product); $i<$n; $i++)
	{		
		if ($visa_product[$i]['visa_purpose'] == $visa_purpose)
		{
			$data = $visa_product[$i];
			break;
		}		
	}	
	
	if (is_array($data))
	{
		//$rt .= '<p><a href="'.tep_href_link_noseo('visa.php','action=loginto&visa_products_id='.$data['visa_products_id']).'" rel="nofollow" target="_blank">在线预订</a><span class="color1">$'.$data['visa_product_price'].'</span></p><h3>'.$data['visa_purpose'].'</h3>';
		$rt .= '<p><a href="'.tep_href_link('product_info.php','products_id=2750').'" rel="nofollow" target="_blank">在线预订</a><span class="color1">$'.$data['visa_product_price'].'</span></p><h3>'.$data['visa_purpose'].'</h3>';
	}
	return $rt;
}
	

$visa = new visa();

$action_ = $_GET['action'];
switch($action_){
	case 'loginto': //下签证订单 start{
		//$VIS_TAG_NAME = urlencode(iconv('gb2312','utf-8',iconv(CHARSET,'gb2312',urldecode($_GET['VIS_TAG_NAME']))));
		$visa_products_id = (int)$_GET['visa_products_id'];
		
		$visa_product = $visa->get_product_by_visa_products_id($visa_products_id);
		if ( is_array($visa_product) ){	
		
			if (!tep_session_is_registered('customer_id')) {
				$messageStack->add_session('login', NEXT_NEED_SIGN);
				//$navigation->set_snapshot();
				if(tep_not_null($_COOKIE['LoginDate'])){
					$messageStack->add_session('login', LOGIN_OVERTIME);
					setcookie('LoginDate', '');
				}
				tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
			}
			$visa_info = $visa->get_visa_info($customer_id);
			if(is_array($visa_info)){			
				$SRV_UNID = $visa_product['visa_srv_unid'];
				$VIS_TAG_NAME = urlencode( iconv('gb2312','utf-8',$visa_product['visa_vis_tag_name']) );//从数据库里读取出来的就是gb2312的
				//$url = VISA_DOMAIN . VISA_USER_ORDER_URL. $visa_info['URL_VISA_ORDER'].'&VIS_TAG_NAME='.$VIS_TAG_NAME.'&SRV_UNID='.$SRV_UNID;//参数VISA_USER_ORDER_URL多余
				$url = VISA_DOMAIN . $visa_info['URL_VISA_ORDER'].'&VIS_TAG_NAME='.$VIS_TAG_NAME.'&SRV_UNID='.$SRV_UNID;
				//echo 'page redirecting,please wait.........';
				tep_redirect($url);
			} else {
				exit('签证系统繁忙！请稍后再试！');
			}
		}else {
			//echo 'redirect to visa home page';
			tep_redirect(tep_href_link('visa.php'));
		}
	//下签证订单 end }
	break;
	case 'viewMyVisaOrder': //查看我的签证订单 start{
		if (!tep_session_is_registered('customer_id')){
			tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
		}
		$visa_info = $visa->get_visa_info($customer_id);
		$url = VISA_DOMAIN . $visa_info['URL_VISA_ORDER_LIST'];
		tep_redirect($url);
	//查看我的签证订单 end}
	break;
	case 'add_order_from_lujia': //visa的定单状态改变后，lujia会回发数据，不用我们管对方提交的 start { 
		echo 'prepare.';
		$fromUrl = $_SERVER['HTTP_REFERER'];
		$fromUrl = preg_replace('/^http:\/\//i', '', $fromUrl);
		$arr = split('/',$fromUrl);
		$fromDomain = $arr[0];
		echo 'check source.';
		
		//如果是生产站,一定要判断来源
		if(IS_LIVE_SITES ===true){
			if ($fromDomain != str_replace('http://','',VISA_DOMAIN)){
				echo '';
				exit();
			}
		}
		
		//这里开始处理数据	
		$order_info = tep_db_prepare_input($_POST['order']);
		$sql = 'INSERT INTO `visa_temp`(`order`)values(\''.$order_info.'\')';
		$sql_query=tep_db_query($sql);
		
		$visa->visa_add_order_info_returned_fromlujia($order_info);	
		echo 'finished.';		
		exit();
	//visa的定单状态改变后，lujia会回发数据，不用我们管对方提交的 end }
	break;
	case 'add_visa_from_lujia':	//签证订单提交到大使馆后,如果状态改变,则路嘉提交信息过来,保存之 start{
		echo 'visa_prepare.';
		$fromUrl = $_SERVER['HTTP_REFERER'];
		$fromUrl = preg_replace('/^http:\\/\\//i', '', $fromUrl);
		$arr = split('/',$fromUrl);
		$fromDomain = $arr[0];
		echo 'visa_check_source.';
		
		//如果是生产站,一定要判断来源
		if(IS_LIVE_SITES ===true){
			if ($fromDomain != str_replace('http://','',VISA_DOMAIN)){
				echo 'domain check error';
				exit();
			}
		}
		
		//这里开始处理数据	
		//$order_info = tep_db_prepare_input($_POST['order']);
		//$sql = 'INSERT INTO `visa_temp`(`order`)values(\''.$order_info.'\')';
		//$sql_query=tep_db_query($sql);
	
		$visa_data = $_POST['visa_data'];
		
		$rt = $visa->visa_add_visa_info_returned_fromlujia($visa_data);
		echo 'visa_finished.';		
		exit();
	//签证订单提交到大使馆后,如果状态改变,则路嘉提交信息过来,保存之 end}
	break;
	case 'communication': //与路嘉交流/留言的查看 start{
		//这里需要添加人员权限判断的功能
		if (!tep_not_null($_SESSION['visa_com_session'])){		
			$cert_rt = false;
			$cert_rt = $visa->get_cert($_GET['cert']);
			if ($cert_rt == false){ 
				echo 'ERROR: user cert fail.'; exit();
			}else{
				$_SESSION['visa_com_session'] = "1";
			}
		}
		
		$visa_lujia_user = $_GET['user_name'];
		$visa_order_id = (int)$_GET['visa_order_id'];	
		if($visa_order_id>0 && tep_not_null($visa_lujia_user)){
			$off_corner_tl_tr = true;
			$BreadOff = true;
			$content = 'visa';
			
			$the_title = db_to_html('美国旅游签证_美国签证办理_办美国签证多少钱_Usitrip走四方旅游网');
			$the_key_words = db_to_html('去美国旅游签证,美国旅游签证多少钱,代办美国签证,美国签证预约');
			$the_desc = db_to_html('美国当地华人旅行社-走四方提供商务,留学游学,工作签证办理;全球唯一的美国签证中文在线提交系统及签证服务,专业团队,100%保障!');
			ob_start();
	?>
	<style type="text/css">
	body{font-size:12px;}
	.tbList { border:1px solid #CCCCCC; border-collapse:collapse; font-size:14px; }
	.tbList th{ background-color:#006699; color:#FFFFFF; font-weight:bolder; font-size:90%; border:1px solid #CCCCCC; padding:3px;}
	.tbList td{ border:1px solid #CCCCCC; padding:3px; font-size:90%;}
	.tbList td span.imp2{color:#FF0000; font-weight:bolder;}
	.tbList td span.imp1{color:#FF0000; font-weight:normal;}
	.tbList td span.imp0{color:#000000; font-weight:normal;}
	.tbList tr.bc{ background-color:#EEEEEE}
	</style>
	<script type="text/javascript" src="jquery-1.3.2/merger/merger.min.js"></script>
	<script language="javascript" type="text/javascript">
	function fn_visa_msg_read(id)
	{
		var url = "visa.php?action=communication_read&id="+id;;
	
		jQuery.get(url, {}, function(data){
			if (data.substring(0,5).toUpperCase()=="ERROR"){ alert(data); }	else{ alert("操作成功"); window.location.href = window.location.href; }
		}); 
	}
	</script>
	
		签证订单 (订单号 <b><?php echo $visa_order_id;?></b> ) 与走四方交流的内容:
		<div>
		<?php
		$data2 = $visa->get_visa_order_info_by_visa_order_id($visa_order_id);
		$visa_VIS_STATUS = $visa->get_visa_to_embassy_status($visa_order_id);
		?>
		<table class="tbList">
			<tr>
				<th>订单号</th>
				<th>客户姓名</th>
				<th>访美目的</th>
				<th>签证状态</th>
				<th>付款状态</th>
				<th>预计赴美日期</th>
				<th>希望签证日期</th>
			</tr>
			<tr>
				<td><?php echo $visa_order_id;?></td>
				<td><?php echo $data2['ORD_USR_NAME'];?></td>
				<td><?php echo $data2['ORD_NAME'];?></td>
				<td><?php echo $visa->match_visa_to_embassy_status_name($rows['VIS_STATUS']);?></td>
				<td><?php if ($data2['ORD_PAY_MONEY']>= $data2['ORD_PRICE']){ echo '已付款'; }else{ echo '未付款';} ?></td>
				<td><?php echo $data2['ORD_EXT2'];?></td>
				<td><?php echo $data2['ORD_EXT3'];?></td>
			</tr>
		</table>
		</div>
	
		<div style="width:1100px;">
		<div style="float:left; width:500px; height:20px; background-color:#FFFFFF; overflow:hidden; border:1px solid #CCCCCC; padding:5px; margin:2px; text-align:center; font-weight:bolder; font-size:16px;">走四方</div>
		<div style="float:left; width:500px; height:20px; background-color:#CCFFFF; overflow:hidden; border:1px solid #CCCCCC; padding:5px; margin:2px; text-align:center; font-weight:bolder; font-size:16px;">路嘉</div>
		<?php
		
		//$visa_order_com = $visa->visa_order_com_get_lists($visa_order_id);
		//tep_get_admin_customer_name
		$sql = 'SELECT * FROM visa_order_communication WHERE visa_order_id='.$visa_order_id.' ORDER BY CASE visa_order_com_root_id WHEN 0 THEN visa_order_com_id ELSE visa_order_com_root_id END DESC, visa_order_com_parent_id ASC, add_date ASC';
		$sql_query = tep_db_query($sql);
		
		$admin_id_temp = -1;
		$last_from = '';
		$last_from_temp = '';
		
		while($rows = tep_db_fetch_array($sql_query))
		{
			if ($rows['admin_id']>0) { 
				$last_from_temp = 'usi'; 
			} 
			else {
				$last_from_temp = 'lujia'; 
			}
		?>
		<?php
			if ((int)$rows['visa_order_com_root_id']==0) {
				$last_from = '';
		?>
		<div style="line-height:5px; height:15px; float:left; width:95%; background-color: #CCCCCC;">
			<a href="javascript:void(0)" style="float:right;">隐藏</a>
		</div>
		<?php }?>
		<div>
			<?php if( ($rows['admin_id']==0 && (int)$rows['visa_order_com_root_id']==0) || ($last_from_temp == $last_from ) ){?>
			<div style="float:left; width:500px; height:90px; overflow:hidden; border:1px solid #FFFFFF; padding:5px; margin:2px;"></div>
			<?php }?>
			
			<div style="float:left; width:500px; height:90px; overflow:hidden; border:1px solid #CCCCCC; padding:5px; margin:2px; 
			<?php if($rows['admin_id']==0){ ?> background-color:#CCFFFF;<?php }?>
			<?php if ((int)$_GET['visa_order_com_parent_id']==(int)$rows['visa_order_com_id']){ ?> background-color:#FFCC33;<?php }?>
			">
				<div>
					<span style="color:#999999;"><?php if($rows['admin_id']>0){ echo tep_get_admin_customer_name($rows['admin_id']);}else{ echo '<b>'.tep_db_output($rows['sender_name']).'</b>';}?></span>
					<?php echo tep_db_output($rows['title']);?><br/>
					<div style=" padding:5px 3px; height:50px; overflow:auto;"><?php echo tep_db_output( $rows['message']);?>	</div>
				</div>
				<div style="">
					<span style="float:right;color:#666666;">时间:<?php echo $rows['add_date'];?></span>				
	
				
				<?php
				if($rows['admin_id']==0){ $to_name = '走四方'; }else{ $to_name = '路嘉'; }
				
				if ($rows['need_reply']=='1')
				{ 				
					if($rows['is_replied']=='1'){ echo '<span style="color:#0000FF">'.$to_name.'已回复</span>'; }
					else{ echo '<span style="color:#FF0000">'.$to_name.'未回复</span>'; }			
				?>
				<a href="<?php 
				echo '?action=communication&visa_order_id='.$visa_order_id;
				
				if ($rows['visa_order_com_root_id']==0) { echo '&visa_order_com_root_id='.$rows['visa_order_com_id']; }
				else { echo '&visa_order_com_root_id='.$rows['visa_order_com_root_id']; }
				
				echo '&visa_order_com_parent_id='.$rows['visa_order_com_id'];
				echo '&user_name='.$visa_lujia_user;
				echo '#a_form_add';
				?>">
					<?php 
					if( $rows['admin_id']==0) {
						//if ($rows['is_replied']=='1'){ echo '追加';} else { echo '回复'; }
						echo '追加';
					}else{
						if ($rows['is_replied']=='1'){ echo '追加';} else { echo '回复'; }
					}
					?>
				</a>
				<?php 
				}
								
				if($rows['is_read']=='0'){ 
					echo '<span style="color:#FF0000">';
					if ($rows['admin_id']>0){ echo '路嘉';}else{ echo '走四方';}
					echo '未读</span>'; 
				}				
				
				if(($rows['admin_id']>0) && ($rows['is_read']=='0')){?>
				<input name="" type="button" value="我已读" onclick="fn_visa_msg_read(<?php echo $rows['visa_order_com_id'];?>)" style="font-size:12px; padding:0;">
				<?php
				}
				?>
				</div>
			</div>
		</div>
		<?php
			if ($rows['admin_id']>0) { 
				$last_from = 'usi';
			} 
			else {
				$last_from = 'lujia';
			}
		}
		?>
		</div>
		<div style="clear:both;"></div>
		<a name="a_form_add"></a>
		<br/>
		<a href="?action=communication&visa_order_id=<?php echo $visa_order_id;?>&user_name=<?php echo $visa_lujia_user;?>">给走四方新增留言</a>
		<?php
		$data =false;
		$is_reply = false;
		$visa_order_com_parent_id = (int)$_GET['visa_order_com_parent_id'];
		if ($visa_order_com_parent_id>0){
			$sql = 'SELECT title,message FROM visa_order_communication WHERE visa_order_com_id='.$visa_order_com_parent_id;
			$is_reply = true;
			$sql_query = tep_db_query($sql);
			while($rows =  tep_db_fetch_array($sql_query))
			{
				$data = $rows ;
			}
		}
		?>
		
		
		<form name="form1" id="form1" action="<?= tep_href_link_noseo('visa.php','action=communication_add&visa_order_id='.$visa_order_id.'&visa_order_com_root_id='.$visa_order_com_root_id.'&visa_order_com_parent_id='.$visa_order_com_parent_id.'&user_name='.$visa_lujia_user)?>" method="post" style=" margin-top:10px;">
		
		<table class="tbList">
			<tr>
				<td width="100" align="right">主题:</td>
				<td width="300">
				
				<?php if ($is_reply == true){?>			
				<input name="title" type="text" style="width:300px;" value="<?php echo 're:'.$data['title'];?>">			
				<?php
				}else{ 
				?>
				<select name="title" style="width:300px;">
					<option value="填写表格">填写表格</option>
					<option value="表格审核">表格审核</option>
					<option value="提交成功">提交成功</option>
					<option value="预约面签">预约面签</option>
					<option value="材料准备">材料准备</option>
					<option value="陪签安排">陪签安排</option>
					<option value="签证结果">签证结果</option>
				</select>
				<?php
				}
				?>
				<span style="color:#FF0000">*</span></td>
			</tr>
			<?php if ($is_reply == true){ ?>
			<tr>
				<td align="right">内容:</td><td><?php echo $data['message'];?></td>
			</tr>
			<?php } ?>
			<tr>
				<td align="right"><?php if ($is_reply == true){ echo '回复的';}?>内容:</td>
				<td>
				<?php echo tep_draw_textarea_field('message', '', '50', '3', $text = '', $parameters = '', $reinsert_value = true);?>
				<span style="color:#FF0000">*</span></td>
			</tr>
			<tr>
				<td align="right">是否需要回复:</td>
				<td>
				<label><input name="need_reply" type="radio" value="1" <?php if ($is_reply <> true){?>checked="checked"<?php }?>>是的,需要对方回复</label>
				<label><input name="need_reply" type="radio" value="0" <?php if ($is_reply == true){?>checked="checked"<?php }?>>否,不需要回复</label>
				</td>
			</tr>
			<tr><td></td><td><input name="" type="submit" value="<?php if ($is_reply == true){ echo '回复';}else{ echo '发送';}?>"></td></tr>		
		</table>
		</form>	
	<?php
			echo db_to_html(ob_get_clean());
			require(DIR_FS_INCLUDES . 'application_bottom.php');
		}
		else
		{
			echo '[visa order id] OR [user_name] error';
			exit();
		}	

	//与路嘉交流/留言的查看 end}
	break;
	case 'communication_add': //给走四方新增留言 start{
				
		if (!tep_not_null($_SESSION['visa_com_session']))
		{
			echo 'ERROR: user certicate fail, maybe logined too long.';
			exit();
		}
		
		$visa_lujia_user = $_GET['user_name'];
		$visa_order_id = (int)$_GET['visa_order_id'];
		$visa_order_com_root_id = (int)$_GET['visa_order_com_root_id'];
		$visa_order_com_parent_id = (int)$_GET['visa_order_com_parent_id'];
		if($visa_order_id>0) //AND $visa_order_com_parent_id>0
		{
			$data = false;
			$data['admin_id'] = 0;
			
			$data['title'] = tep_db_prepare_input(iconv(CHARSET,'gb2312',$_POST['title']));
			$data['message'] = tep_db_prepare_input(iconv(CHARSET,'gb2312',$_POST['message']));
			$data['need_reply'] = (int)$_POST['need_reply'];
			$data['visa_order_id'] = $visa_order_id;
			$data['add_date'] = date('Y-m-d H:i:s');		
			$data['sender_name'] = tep_db_prepare_input(iconv(CHARSET,'gb2312',$visa_lujia_user));
			$data['visa_order_com_root_id'] = $visa_order_com_root_id;
			$data['visa_order_com_parent_id'] = $visa_order_com_parent_id;
	
			if(tep_not_null($data['title']) && tep_not_null($data['message']) )
			{
				if( $visa_order_com_parent_id >0 )
				{
					$sql = 'UPDATE visa_order_communication SET is_replied=\'1\',is_read=\'1\',read_date=\''.date('Y-m-d H:i:s').'\' WHERE visa_order_com_id='.$visa_order_com_parent_id.' AND admin_id>0 AND is_replied=\'0\' AND admin_id>0';
					tep_db_query($sql);
				}
			
				tep_db_fast_insert('visa_order_communication',$data);
				
				echo '<script>alert("success"); window.location.href="visa.php?action=communication&visa_order_id='.$visa_order_id.'&user_name='.$visa_lujia_user.'";</script>';
				exit();
			}
			else
			{
				echo '<script>alert("error"); window.history.go(-1);</script>';
				exit();
			}
			
		}	
	//给走四方新增留言 end}
	break;
	case 'communication_read': //我已阅读留言之数据操作 start{
		if (!tep_not_null($_SESSION['visa_com_session']))
		{
			echo 'ERROR: user certicate fail, maybe logined too long.';
			exit();
		}
	
		$id = (int)$_GET['id'];
	
		if($id>0)
		{	
			$sql = 'UPDATE visa_order_communication SET is_read=\'1\',read_date=\''.date('Y-m-d H:i:s').'\' WHERE visa_order_com_id='.$id;
			tep_db_query($sql);
		}
		else
		{
			echo 'ERROR: parameter lost';
			exit();	
		}
	//我已阅读留言之数据操作 end}
	break;
	case 'communication_status': //获取留言读取回复的状态 start{
		$cert_rt = false;
		$cert_rt = $visa->get_cert($_GET['cert']);
		if ($cert_rt == false){ 
			echo '{"RST":"fail"}'; 
			exit();
		}
		$sql = 'SELECT a.`lujia_not_replied`,b.`lujia_not_read`,c.`usitrip_not_replied`,d.`usitrip_not_read` FROM  (  SELECT 1 AS id, GROUP_CONCAT(a1.visa_order_id) AS `lujia_not_replied` FROM( SELECT distinct visa_order_id FROM visa_order_communication  WHERE admin_id >0 AND need_reply = \'1\' AND is_replied = \'0\' ) AS a1  ) AS a, (  SELECT 1 AS id, GROUP_CONCAT(b1.visa_order_id) AS `lujia_not_read` FROM( SELECT distinct visa_order_id FROM visa_order_communication  WHERE admin_id >0 AND is_read = \'0\' ) AS b1  ) AS b, (  SELECT 1 AS id, GROUP_CONCAT(c1.visa_order_id) AS `usitrip_not_replied` FROM( SELECT distinct visa_order_id FROM visa_order_communication  WHERE admin_id =0 AND need_reply = \'1\' AND is_replied = \'0\' ) AS c1  ) AS c, (  SELECT 1 AS id, GROUP_CONCAT(d1.visa_order_id) AS `usitrip_not_read` FROM( SELECT distinct visa_order_id FROM visa_order_communication WHERE admin_id =0 AND is_read = \'0\' ) AS d1  ) AS d WHERE a.id=b.id and a.id=c.id and a.id=d.id';
		//echo $sql; exit();
		$sql_query = tep_db_query($sql);
		$rows = tep_db_fetch_array($sql_query);
		echo '{"RST":"ok","lujia_not_replied":"'.$rows['lujia_not_replied'].'","lujia_not_read":"'.$rows['lujia_not_read'].'","usitrip_not_replied":"'.$rows['usitrip_not_replied'].'","usitrip_not_read":"'.$rows['usitrip_not_read'].'"}';
		exit();	
	//获取留言读取回复的状态 end}
	break;
	case 'email_from_lujia': //从路嘉来的邮件内容,收到后进行转发,解决路嘉服务器与发信人的域名不匹配问题 start{
		
		$cert_rt = false;
		$cert_rt = $visa->get_cert($_GET['cert']);
		//$cert_rt = true;
		if ($cert_rt == false)
		{ 
			echo 'ERROR: user cert fail.'; exit();
		}
		else
		{
			/*
			TO_EMAIL 收件人地址
			TO_NAME 收件人姓名
			TITLE 邮件标题
			CONTENT 邮件内容（HTML）
			FILE0，FILE1，FILE2... 附件（文件二进制的BASE64格式编码）,FILENAME0,FILENAME1,FILENAME2...
			*/
			echo 'prepare to forward email from lujia.';		
			
			$POST = $_POST;
			
	//		$POST = false;
	//		$POST['TO_EMAIL'] = 'xuyuefang1998@163.com';
	//		$POST['TO_NAME'] = '';
	//		$POST['TITLE'] = '这是一封来自aben的测试邮件';
	//		$POST['CONTENT'] = '<html><body>start中文开始end</body></html>';
	//		$POST['FILE0'] = base64_encode(file_get_contents(DIR_FS_CATALOG.'tmp/1.gif'));
	//		$POST['FILE1'] = base64_encode(file_get_contents(DIR_FS_CATALOG.'tmp/1.gif'));
	//		$POST['FILENAME0'] = '1.gif';
	//		$POST['FILENAME1'] = '2.gif';
			
			//echo $POST['FILE0'];exit();
			
			$visa->visa_forward_email_fromlujia($POST);
			echo 'email forward finished.';
			exit();//终止输出
		}
		
	//从路嘉来的邮件内容,收到后进行转发,解决路嘉服务器与发信人的域名不匹配问题 end}
	break;
	default: //一般浏览页面start{
		$off_corner_tl_tr = true;
		$BreadOff = true;
		$content = 'visa';
		
		
		$the_title = db_to_html('美国旅游签证_美国签证办理_办美国签证多少钱_Usitrip走四方旅游网');
		$the_key_words = db_to_html('去美国旅游签证,美国旅游签证多少钱,代办美国签证,美国签证预约');
		$the_desc = db_to_html('美国当地华人旅行社-走四方提供商务,留学游学,工作签证办理;全球唯一的美国签证中文在线提交系统及签证服务,专业团队,100%保障!');
		
		$visa_product = $visa -> get_visa_product_list();
	//一般浏览页面end}
}

require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_FS_INCLUDES . 'application_bottom.php');
?>