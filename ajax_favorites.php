<?php
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );

require_once('includes/application_top.php');
header("Content-type: text/html; charset=".CHARSET);

$action = $_GET['action'];
switch($action){
	case "add_favorites":	//添加收藏
		$product_id = (int)$_GET['product_id'];
		//检测产品
		if(!(int)$product_id){
			echo db_to_html('[ERROR]没有产品ID！[/ERROR]');
			exit;
		}
		//检测用户
		if(!tep_session_is_registered('customer_id') || !(int)$customer_id){
			$_SESSION['favorites'][] = array('products_id'=>$product_id, 'customers_id'=> $osCsid);
			echo db_to_html('[SUCCESS]收藏成功，由于您尚未登录账号：<br>&nbsp;1.您需要登录之后产品才能保存到您的收藏夹中；<br>&nbsp;2.现在登录请点击“进入收藏夹”。[/SUCCESS]');
			exit;
		}else{
			$check_sql = tep_db_query('SELECT customers_favorites_id FROM `customers_favorites` WHERE products_id="'.$product_id.'" and customers_id="'.$customer_id.'" Limit 1 ');
			$check = tep_db_fetch_array($check_sql);
			if((int)$check['customers_favorites_id']){
				tep_db_query('UPDATE `customers_favorites` SET `updated_time`=NOW() WHERE products_id="'.$product_id.'" and customers_id="'.$customer_id.'" ;');
			}else{
				tep_db_query('INSERT INTO `customers_favorites` (`products_id`,`customers_id`,`added_time`,`updated_time`) VALUES ( '.$product_id.', '.$customer_id.', NOW(), NOW());');
			}
			echo db_to_html('[SUCCESS]收藏成功！[/SUCCESS]');
			exit;
		}
	break;
	
	case "del_favorites":	//删除已收藏的团
		if(!(int)$customer_id){
			echo db_to_html('[JS] alert("登录超时，请刷新页面重新登录！"); [/JS]');
			exit;
		}else{
			tep_db_query('DELETE FROM `customers_favorites` WHERE customers_favorites_id="'.(int)$_GET['f_id'].'" and customers_id="'.(int)$customer_id.'" ');
			echo db_to_html('[JS] jQuery("#FavObj_'.(int)$_GET['f_id'].'").fadeOut(200); [/JS]');
			exit;
		}
	break;
}
?>