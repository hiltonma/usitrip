<?php
/*
本统计表以订单为中心，可能有重复的客户产生
*/

  require('includes/application_top.php');
  // 备注添加删除
  if($_GET['ajax']=="true"){
  	include DIR_FS_CLASSES . 'Remark.class.php';
  	$remark = new Remark('stats_order_analysis');
  	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
  }
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  $search_msn ='';
 function get_customers_advertiser($id){
 	$str_sql='select customers_advertiser from ad_source_clicks_stores where clicks_id='.$id;
	$check_sql = tep_db_query($str_sql);
    $check_rows = tep_db_fetch_array($check_sql);
	return $check_rows['customers_advertiser'];
 }
  
  if (isset($HTTP_GET_VARS['page']) && ($HTTP_GET_VARS['page'] > 1)) {$rows = $HTTP_GET_VARS['page'] * MAX_DISPLAY_SEARCH_RESULTS_ADMIN - MAX_DISPLAY_SEARCH_RESULTS_ADMIN;}else{
    $rows = 0;
  }
  if(!tep_not_null($_GET['search_type'])){
    $search_type ='orders';
  }
  /**
   * 把URL地址只留下www.xxx.com
   * @param string $url
   */
  function getUrlOutOther($url){
  	$tmp_arr=explode('.com', $url);
  	if(count($tmp_arr)>1){
  		return $tmp_arr[0].'.com';
  	}else{
  		return $url;
  	}
  }
  
  //公共搜索
  $where_exc = ''; 
  if($_GET['search_action']=='1'){
    if(tep_not_null($_GET['s_customers_name'])){
        $s_customers_name = trim($_GET['s_customers_name']);
        $where_exc .= ' AND (c.customers_firstname Like binary ("%'.$s_customers_name.'%") || c.customers_lastname Like binary ("%'.$_GET['s_customers_name'].'%") ) ';
    }
    /**{
    if((int)$_GET['s_customers_referer_type']){
        $s_customers_referer_type = (int)$_GET['s_customers_referer_type'];
        $where_exc .= ' AND c.customers_referer_type ="'.(int)$s_customers_referer_type.'" ';

    }
    **/

    if (tep_not_null($_GET['ref_type'])){
        $ref_type = $_GET['ref_type'];   
        
        if (is_array($ref_type) && $ref_type != 'Array'){
           
            $ref_type_id_arr = array_values(array_unique(array_filter($ref_type))); 
        }else{
            
            $ref_type = $_GET['ref_type_id'];            
            $ref_type_id = explode(',',$ref_type);
            $ref_type_id_arr = array_values(array_unique(array_filter($ref_type_id)));
        }  
       
        $ref_id_1 = array();
        for ($i=0; $i<count($ref_type_id_arr); $i++){
            $ref_id_1[] = getChild($ref_type_id_arr[$i]);            
            if ($i == count($ref_type_id_arr)-1){
                $ref_id_m .= $ref_type_id_arr[$i];
            }else{
                $ref_id_m .= $ref_type_id_arr[$i].',';
            }
            
            
        }    
        for($i=0; $i<count($ref_id_1); $i++){
            for ($j=0; $j<count($ref_id_1[$i]); $j++){
                
                /*if ($i == count($ref_id_1[$i][$j])-1){
                    $ref_id .= $ref_id_1[$i][$j][0];
                }else{
                    $ref_id .= $ref_id_1[$i][$j][0].',';
                }*/
                
                $ref_id .= $ref_id_1[$i][$j][0].',';
            }
        }
        
        
        if (tep_not_null($ref_id) || tep_not_null($ref_id_m)){
            $where_exc .= ' AND c.customers_referer_type IN ('.$ref_id.$ref_id_m.') ';
        }
    }
    
    
    if(tep_not_null($_GET['zhuce_start_date'])){
        $reg_start_date = trim($_GET['zhuce_start_date']);
        $where_exc .= ' AND ci.customers_info_date_account_created >="'.$reg_start_date.' 00:00:00" ';
    }
    if(tep_not_null($_GET['zhuce_end_date'])){
        $reg_end_date = trim($_GET['zhuce_end_date']);
        $where_exc .= ' AND ci.customers_info_date_account_created <="'.$reg_end_date.' 23:59:59" ';
    }
    if((int)$_GET['country_id']){
        $where_exc .= ' AND ab.entry_country_id ="'.(int)$_GET['country_id'].'" ';
    }
    if($_GET['customers_char_set']=='gb2312' || $_GET['customers_char_set']=='big5' ){
        $where_exc .= ' AND c.customers_char_set ="'.$_GET['customers_char_set'].'" ';
    }
	
  }
  
  if($search_type =='orders'){
      //以订单为中心
      //订单添加的搜索
  	$order_next='';
  	$sql_order_by=' Order By o.orders_id DESC';
    if($_GET['search_action']=='1'){
        if(tep_not_null($_GET['s_orders_id'])){
            $s_orders_id = trim($_GET['s_orders_id']);
            $where_exc .= ' AND o.orders_id Like binary ("'.$s_orders_id.'%") ';
        }
        if(tep_not_null($_GET['buy_start_date'])){
            $buy_start_date = trim($_GET['buy_start_date']);
            $where_exc .= ' AND o.date_purchased >="'.$buy_start_date.' 00:00:00" ';
        }
        if(tep_not_null($_GET['buy_end_date'])){
            $buy_end_date = trim($_GET['buy_end_date']);
            $where_exc .= ' AND o.date_purchased <="'.$buy_end_date.' 23:59:59" ';
        }
        if((int)$_GET['s_orders_status']){
            $s_orders_status = (int)$_GET['s_orders_status'];
            $where_exc .= ' AND o.orders_status ="'.(int)$s_orders_status.'" ';
        }
		if(isset($_GET['order_next'])){
		$order_next=$_GET['order_next']; 
		unset($_GET['order_next']);
		$where_exc.= ' AND o.customers_advertiser like "%'.$order_next.'%" ';
		}
		if(isset($_GET['from_order_by'])){
			$from_order_by=$_GET['from_order_by'];
			unset($_GET['from_order_by']);
			$sql_order_by= ' order by o.customers_advertiser '.$from_order_by;
			$from_order_by=($from_order_by=='DESC')?'ASC':'DESC';
		}
		if(isset($_GET['pay_status'])&&$_GET['pay_status']!=''){
			if($_GET['pay_status']==1){
				$where_exc.=' AND o.orders_paid<>0 ';
			}elseif($_GET['pay_status']==2){
				$where_exc.=' AND o.orders_paid=0 ';
			}
		}
		if(isset($_GET['order_is_from'])&&$_GET['order_is_from']!=''){
			$where_exc.=' AND o.is_other_owner='.$_GET['order_is_from'];
			$order_is_from=$_GET['order_is_from'];
		}
		if(isset($_GET['my_contry'])&&$_GET['my_contry']!=''){
			if($_GET['my_contry']==1){
				$where_exc.=' AND o.guest_emergency_cell_phone LIKE "+86 %" ';
			}else{
				$where_exc.=' AND o.guest_emergency_cell_phone NOT LIKE "+86 %"';
			}
		}
        if ((int)$_GET['get_sem_name']){
            
        }
    }
     $order_is_array=array(0=>'客户带连接地址下单',1=>'客人自己下单',2=>'销售跟踪的订单',3=>'客服帮忙下单');
      $orders_query_raw = "select DISTINCT c.customers_id, c.customers_firstname, c.customers_lastname, c.customers_referer_url, c.customers_referer_type,
                            ci.customers_info_date_account_created, ab.entry_country_id, 
                            o.orders_id, o.date_purchased, o.orders_status,o.guest_emergency_cell_phone,orders_cancelled_total,
                            ot.text as order_total,o.is_other_owner,o.orders_owner_admin_id,o.customers_advertiser,o.customers_ad_click_id,o.orders_paid,o.is_other_owner
      ";
      $From_where = " FROM " . TABLE_CUSTOMERS . " c, address_book ab , " .TABLE_CUSTOMERS_INFO." ci, " . TABLE_ORDERS . " o, ".TABLE_ORDERS_TOTAL." ot where ci.customers_info_id = c.customers_id AND c.customers_id = o.customers_id AND ot.orders_id = o.orders_id AND ot.class='ot_total' AND c.customers_default_address_id = ab.address_book_id ".$where_exc.$sql_order_by;
      $orders_query_raw .= $From_where;
      
      $outputcsv_sql = $orders_query_raw;
  }else{
      //以客户为中心
      $orders_query_raw = "select c.*, ci.*, ab.entry_country_id ";
      $From_where = " FROM (" . TABLE_CUSTOMERS . " c, " .TABLE_CUSTOMERS_INFO." ci , address_book ab ) where ci.customers_info_id = c.customers_id AND c.customers_default_address_id = ab.address_book_id ".$where_exc." Group By c.customers_id Order By c.customers_id DESC";
      $orders_query_raw .= $From_where;
      
      $outputcsv_sql = $orders_query_raw;
  }
  
// 	  echo $orders_query_raw;
      $orders_query_numrows = 0;
      $orders_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $orders_query_raw, $orders_query_numrows);
      $orders_query = tep_db_query($orders_query_raw);

// 导出.csv数据
if ($_GET['outputcsv'] == 'outputcsv'){    
    $orders_query_raw = tep_db_query($outputcsv_sql);
    $data = "已有订单,Order ID,用户名,来自国家地区,注册时间,客户来源(ref URL type),客户来源,金额,订单状态\n";
    while($orders = tep_db_fetch_array($orders_query_raw)){
       // print_r($orders);        
        
        $check_sql = tep_db_query('SELECT count(*) total FROM `orders` WHERE customers_id ="'.(int)$orders['customers_id'].'" LIMIT 1');
        $check_rows = tep_db_fetch_array($check_sql);
        if((int)$check_rows['total']){
            $data .= 'Yes,';
        }else{
            $data .= ' '.',';
        }
        
        $data .= (int)$orders['customers_id'].",";
        $data .= getSaveCsv($orders['customers_firstname']).' '.getSaveCsv($orders['customers_lastname']) . ",";
        $data .= getSaveCsv(tep_get_country_name($orders['entry_country_id'])) . ",";
        $data .= $orders['customers_info_date_account_created'] . ",";
        $data .= getRefTypeName($orders['customers_referer_type']) . ",";
        
        //当目标为用户时显示用户来源url				
        if($search_type!='orders'){
            if(tep_not_null($orders['customers_referer_url'])){
                $data .= getSaveCsv($orders['customers_referer_url']) . ",";
            }else{
                $data .= ' '.',';
            }
        }else{
            $data .= '="' . $orders['date_purchased']. '",';
        }
        
        // 订单金额
        $b_arr = array('</b>','<b>');
        $r_arr = array('','');
        $data .= getSaveCsv(str_replace($b_arr, $r_arr, $orders['order_total'])).',';
        
        // 订单状态
        $status_sql = tep_db_query('SELECT * FROM `orders_status` WHERE orders_status_id="'.(int)$orders['orders_status'].'" AND language_id="'.(int)$languages_id.'" ');
        $status_rows = tep_db_fetch_array($status_sql);
        $data .= getSaveCsv($status_rows['orders_status_name']) ."\n";
        
    }
    
    // 文件名
    $filename = '';
    // 国家地区
    if (tep_not_null($_GET['country_id'])){
        $country_str = '_From('. tep_get_country_name((int)$_GET['country_id']) .')';
    }
    // 语言
    if (tep_not_null($_GET['customers_char_set'])){
        switch($_GET['customers_char_set']){
            case 'gb2312':
                $lang = '_Lang(cn)';
                break;
            case 'big5':
                $lang = '_Lang(tw)';
                break;
        }
    }
    // 用户名    
    if (tep_not_null($_GET['s_customers_name'])){
        $customer_name = '_Aid('.$_GET['s_customers_name'].')';
    }
    // 分类
    if (tep_not_null($_GET['ref_type'])){
        $ref_type = $_GET['ref_type'];
        $ref_type_id_arr1 = array_values(array_unique(array_filter($ref_type)));
        if (is_array($ref_type)){
            $ref_type_id_arr1 = array_values(array_unique(array_filter($ref_type))); 
        }else{
            $ref_type = $_GET['ref_type_id'];
            $ref_type_id = explode(',',$ref_type);
            $ref_type_id_arr1 = array_values(array_unique(array_filter($ref_type_id)));
        }
        $ref_id_str .= '_Refer(';
        
        for ($i=0; $i<count($ref_type_id_arr); $i++){
            $ref_pid = tep_db_query("SELECT * FROM ". TABLE_CUSTOMERS_REF_TYPE2 . " WHERE customers_ref_type_id=".$ref_type_id_arr[$i] ." AND customers_ref_type_pid=0");
            while($ref_pid_arr = tep_db_fetch_array($ref_pid)){
                
                $ref_arr[] = $ref_pid_arr['customers_ref_type_id'];
                $nref_arr[$ref_pid_arr['customers_ref_type_id']][] = '';
                
                $ref_rrname[] = $ref_pid_arr['customers_ref_type_name'];
            }
        }
        
        for ($i=0;$i<count($ref_rrname);$i++){
            
            $ref_id_str .= $ref_rrname[$i].',';
            
        }
        if (empty($ref_arr)){
            $ref_arr = array();
        }
        $ref_type_id_arr  = array_values(array_diff($ref_type_id_arr1, $ref_arr));
       
        for ($i=0; $i<count($ref_type_id_arr); $i++){
            $c_ref_id = tep_db_query("SELECT * FROM ". TABLE_CUSTOMERS_REF_TYPE2 . " WHERE customers_ref_type_id=".$ref_type_id_arr[$i] );
            while($c_ref_id_arr = tep_db_fetch_array($c_ref_id)){
                $c_ref[$c_ref_id_arr['customers_ref_type_pid']][] = $c_ref_id_arr['customers_ref_type_name'];
            }
        }
        
        if (is_array($c_ref)){
            foreach ($c_ref as $key=>$v){
               $p_ref_id = tep_db_query("SELECT * FROM ". TABLE_CUSTOMERS_REF_TYPE2 . " WHERE customers_ref_type_id=".$key );            
               $p_ref = tep_db_fetch_array($p_ref_id);
               $ref_id_str .= $p_ref['customers_ref_type_name'].'[';
               for ($i=0; $i<count($v); $i++){
                   if ($i == count($v)-1){
                       $ref_id_str .= $v[$i];
                   }else{
                       $ref_id_str .= $v[$i].',';
                   }
                   
               }
               $ref_id_str .= '],';
                
            }
        }
        $ref_id_str .= ')';
    }
    
    
    // 订单ID
    if (tep_not_null($_GET['s_orders_id'])){
        $orders_id = '_Oid(' . $_GET['s_orders_id'] . ')';
    }
    // 注册时间
    if (tep_not_null($_GET['zhuce_start_date'])){
        $reg_date = '_Register('. $_GET['zhuce_start_date'] . '-' . $_GET['zhuce_end_date'] .')';
    }
    // 购买时间
    if (tep_not_null($_GET['buy_start_date'])){
        $buy_date = '_Buy('. $_GET['buy_start_date'] . '-' . $_GET['buy_end_date'] .')';
    }
    // 订单状态
    if (tep_not_null($_GET['s_orders_status']) && $_GET['s_orders_status'] != 0){
        
        $status_sql = tep_db_query('SELECT * FROM `orders_status` WHERE language_id="'.(int)$_GET['s_orders_status'].'" ');
        $status_rows = tep_db_fetch_array($status_sql);
        $orders_status = '_Status(' . $status_rows['orders_status_name'] . ')';
    }
    
    if($search_type !='orders'){
        $search_type_str .= 'TFF_CustomerReport';
    }else{
        $search_type_str .= 'TFF_OrdersReport';
    }
   
    $filename = $search_type_str.$country_str.$lang.$customer_name.$ref_id_str.$orders_id.$reg_date.$buy_date.$orders_status.'.csv';
    
    header("Pragma: public"); header("Expires: 0"); 
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
    header("Content-Type: application/force-download"); 
    header("Content-type: text/csv; charset=gb2312");
    header("Content-Transfer-Encoding: binary"); 
    header("Content-Disposition: attachment; filename=".$filename."" );    
    
    echo $data;
    exit;
}

if(!tep_not_null($_GET['customers_char_set'])){	//取得用户简繁比例
    $cn_tw_sql_str = 'select c.customers_char_set '.$From_where;
    $cn_tw_sql = tep_db_query($cn_tw_sql_str);
    //echo $cn_tw_sql_str; 
    $cn_num = 0;
    $tw_num = 0;
    $cn_tw_sum = 0;
    while($cn_tw_rows = tep_db_fetch_array($cn_tw_sql)){
        $cn_tw_sum++;
        if($cn_tw_rows['customers_char_set']=='gb2312'){
            $cn_num++;
        }
        if($cn_tw_rows['customers_char_set']=='big5'){
            $tw_num++;
        }
    }
    
    //$cn_num_proportion = rand(($cn_num/$cn_tw_sum*10000),0) /100 ; 
    $cn_num_proportion = round(($cn_num/max($cn_tw_sum,1)*10000),0) /100 ;
    $tw_num_proportion = round(($tw_num/max($cn_tw_sum,1)*10000),0) /100 ;
}
// 根据id返回客户来源分类名称
function getRefTypeName($ref_type_id){
    $query = tep_db_query('SELECT customers_ref_type_name FROM '. TABLE_CUSTOMERS_REF_TYPE2 . ' WHERE customers_ref_type_id='.$ref_type_id);
    $ref_type_name = tep_db_fetch_array($query);
    return $ref_type_name['customers_ref_type_name'];
}
// 获取安全的csv数据
function getSaveCsv($value){
    if (tep_not_null($value)){
        $data =  str_replace(',', '', $value);
        return  str_replace(array("\r","\n"),array("",""),$data);
    }else{
        return false;
    }
}
// 显示客户来源分类option
function displayArray2($data, $level, $ref_type_id,$pid) {
                    if (is_array($data)) {
                        foreach ($data as $arr) {
                            
                            if ($pid == $arr['id']) {
                                $selected = ' selected';
                            } else {
                                $selected = '';
                            }
                            if ($level == 0) {

                                $select = "<option value='" . $arr['id'] . "' " . $selected . ">" . str_repeat('-', $level) . $i . $arr['name'] . '</option>';
                            } else {
                                $select = '<option value="' . $arr['id'] . '" ' . $selected . '>|' . str_repeat('-', $level) . $arr['name'] . '</option>';
                            }

                            echo $select;
                            displayArray2($arr['child'], $level + 1, $ref_type_id, $pid);
                        }
                    }
}
// 根据id返回该id的所有分类
function getChild($Id){
$SortArray = array();
$result=mysql_query("SELECT * FROM ".TABLE_CUSTOMERS_REF_TYPE2." WHERE customers_ref_type_pid='".$Id."'");
while ($rows=mysql_fetch_array($result)){
if($rows["customers_ref_type_pid"]==0){
    $Int=-1;
}else{
    $Int=$Id;
}
$SortArray[] = array($rows["customers_ref_type_id"]);
$SortArray = array_merge($SortArray,getChild($rows["customers_ref_type_id"]));
}
return $SortArray;
}
$str_url='';
foreach($_GET as $key=>$value){
	 	$str_url.="&&$key=$value";
	 }
if($str_url){
	$str_url_from=substr($str_url, 2).'&&';
	$str_url_by=substr($str_url, 2).'&&from_order_by='.$from_order_by;
}else{
	$str_url_from='search_action=1&&';
	$str_url_by='search_action=1&&from_order_by=ASC';
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">

<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>

<script type="text/javascript" src="includes/menu.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="includes/big5_gb-min.js"></script>
<script type="text/javascript" src="includes/general.js"></script>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>

<script type="text/javascript">
//创建ajax对象
var ajax = false;
if(window.XMLHttpRequest) {
     ajax = new XMLHttpRequest();
}
else if (window.ActiveXObject) {
    try {
            ajax = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
    try {
            ajax = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (e) {}
    }
}
if (!ajax) {
    window.alert("<?php echo db_to_html('不能创建XMLHttpRequest对象实例.')?>");
}
</script>
<?php
$p=array('/&amp;/','/&quot;/');
$r=array('&','"');
?>

<script type="text/javascript">
function write_ref_id(ref_id, cus_id){
    if(document.getElementById('td_ref_' + cus_id)!=null){
        document.getElementById('td_ref_' + cus_id).focus();
    }
    var url = "<?php echo preg_replace($p,$r,tep_href_link('stats_order_analysis_ajax.php','action=1')) ?>" +"&customers_id="+ cus_id +"&customers_referer_type=" + ref_id;
    ajax.open("GET", url, true);
    ajax.send(null); 
    
    ajax.onreadystatechange = function() { 
        if (ajax.readyState == 4 && ajax.status == 200 ) { 
            //alert('修改成功！');
        }		
    }
}
</script>

<div id="spiffycalendar" class="text"></div>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('stats_order_analysis');
$list = $listrs->showRemark();
?>
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
          <!--search form start-->
          <fieldset>
          <legend align="left"> Search Module </legend>
          <?php echo tep_draw_form('form_search', 'stats_order_analysis.php', tep_get_all_get_params(array('page','y','x', 'action')), 'get'); ?>
          
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><table border="0" cellspacing="0" cellpadding="0" style="margin:10px;">
                  
                  <tr>
                    <td height="30" align="right" valign="middle" nowrap class="main">搜索目标</td>
                    <td class="main" align="left">
                    
                    &nbsp;<?php echo tep_draw_radio_field('search_type', 'orders', '','',' onClick="form_search.submit(); document.getElementById(\'s_option\').style.display=\'\'" ')?> 订单 <?php echo tep_draw_radio_field('search_type', 'customers', '','',' onClick="form_search.submit(); document.getElementById(\'s_option\').style.display=\'none\'" ')?> 用户					</td>
                    <td><input name="search_action" type="hidden" id="search_action" value="1"></td>
                    <td class="main" align="right">&nbsp;</td>
                    <td class="main" align="left">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="main" align="right">&nbsp;</td>
                    <td class="main" align="left">&nbsp;</td>
                    <td class="main" align="left">&nbsp;</td>
                    <td class="main" align="left">&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="30" align="right" valign="middle" nowrap class="main">来自国家/地区</td>
                    <td align="left" valign="middle" class="main">&nbsp;<?php echo tep_get_country_list('country_id','',' style="width:138px;"');?></td>
                    <td>&nbsp;</td>
                    <td align="right" nowrap class="main">浏览走四方网时所用语言</td>
                    <td class="main" align="left">&nbsp;
                    <?php
                    $values_array = array();
                    $values_array[0] = array('id'=>'','text'=>'--不限--');
                    $values_array[1] = array('id'=>'gb2312','text'=>'简体');
                    $values_array[2] = array('id'=>'big5','text'=>'繁体');
                    
                    echo tep_draw_pull_down_menu('customers_char_set', $values_array);
                    ?>
                    </td>
                    <td>&nbsp;</td>
                    <td align="right" nowrap class="main">国家:</td>
                    <td class="main" align="left">
					<select name="my_contry">
						<option value="">==请选择==</option>
						<option value="1" <?php if($_GET['my_contry']==1)echo 'selected';?>>国内</option>
						<option value="2" <?php if($_GET['my_contry']==2)echo 'selected'?>>国外</option>
					</select></td>
                    <td class="main" align="left">&nbsp;</td>
                    <td class="main" align="left">&nbsp;</td>
                    </tr>
                  <tr>
                    <td height="30" align="right" valign="middle" nowrap class="main"><?php echo TABLE_HEADING_CUSTOMERS; ?></td>
                    <td align="left" valign="middle" class="main">&nbsp;<?php echo tep_draw_input_field('s_customers_name')?></td>
                    <td>&nbsp;</td>
                    <!--<td align="right" nowrap class="main"><?php echo TABLE_HEADING_CUSTOMERS_FROM; ?></td>
                    <td class="main" align="left">&nbsp;
                <?php
                /*
                $ref_type_sql = tep_db_query('SELECT * FROM `customers_ref_type` ORDER BY `sort_num` ASC, `customers_ref_type_id` ASC ');
                $ref_type_rows = tep_db_fetch_array($ref_type_sql);
                $values_array = array();
                $values_array[] = array('id'=> '0' , 'text'=> 'All');
                do{
                    $values_array[] = array('id'=> $ref_type_rows['customers_ref_type_id'] , 'text'=> $ref_type_rows['customers_ref_type_name']);
                }while($ref_type_rows = tep_db_fetch_array($ref_type_sql));
                echo tep_draw_pull_down_menu('s_customers_referer_type', $values_array);
                */
                ?>					</td>
                    <td>&nbsp;</td>-->
                    <td align="right" nowrap class="main"><?php echo TABLE_HEADING_REG_TIME; ?></td>
                    <td class="main" align="left">&nbsp;
                      <table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td nowrap class="main">&nbsp;<?php echo tep_draw_input_field('zhuce_start_date', tep_get_date_disp($reg_start_date), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"');?></td>
                          <td class="main">&nbsp;至&nbsp;</td>
                          <td nowrap class="main"><?php echo tep_draw_input_field('zhuce_end_date', tep_get_date_disp($reg_end_date), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"');?></td>
                        </tr>
                      </table></td>
					  <td></td>
                    <td class="main" align="right">订单来源：</td>
                    <td class="main" align="left"><input type="text" name="order_next" value="<?=$order_next?>" /></td>
					<td class="main" align="right">付款状态</td>
					<td class="main" align="left">
					<select name="pay_status">
					<option value="">付款状态</option>
					<option value="1" <?php if($pay_status==1) echo 'selected';?>>已付款</option>
					<option value="2" <?php if($pay_status==2) echo 'selected';?>>未付款</option>
					</select></td>
                  </tr>
                  <?php
                  $s_option_display = 'none';
                  if($search_type =='orders'){
                    $s_option_display = '';
                  }
                  ?>
                  <tr id="s_option" style="display:<?= $s_option_display?>" >
                    <td height="30" align="right" nowrap class="main">Order ID</td>
                    <td class="main" align="left">&nbsp;<?php echo tep_draw_input_field('s_orders_id')?></td>
                    <td>&nbsp;</td>
                    <td align="right" nowrap class="main"><?php echo TABLE_HEADING_BUY_TIME; ?></td>
                    <td class="main" align="left">
                    <table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td nowrap class="main">&nbsp;<?php echo tep_draw_input_field('buy_start_date', tep_get_date_disp($buy_start_date), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"');?></td>
                          <td class="main">&nbsp;至&nbsp;</td>
                          <td nowrap class="main"><?php echo tep_draw_input_field('buy_end_date', tep_get_date_disp($buy_end_date), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"');?></td>
                        </tr>
                      </table>					</td>
                    <td>&nbsp;</td>
                    <td align="right" nowrap class="main"><?php echo TABLE_HEADING_ORDER_STATUS; ?></td>
                    <td class="main" align="left">&nbsp;
                <?php
                $status_sql = tep_db_query('SELECT * FROM `orders_status` WHERE language_id="'.(int)$languages_id.'" ');
                $status_rows = tep_db_fetch_array($status_sql);
                $values_array = array();
                $values_array[] = array('id'=> '0' , 'text'=> 'All');
                do{
                    $values_array[] = array('id'=> $status_rows['orders_status_id'] , 'text'=> $status_rows['orders_status_name']);
                }while($status_rows = tep_db_fetch_array($status_sql));
                echo tep_draw_pull_down_menu('s_orders_status', $values_array,'','style="width:230px;"');
                ?></td>
                    <td class="main" align="left">如何来的：</td>
                    <td class="main" align="left">
					<select name="order_is_from">
						<option value="">请选择</option>
						<?php foreach($order_is_array as $key=>$value){?>
						<option value="<?=$key?>" <?php if($order_is_from==$key&&$order_is_from!='') echo'selected'; ?>><?=$value?></option>
						<?php }?>
					</select>
					</td>
                  </tr>
                  <!-- 修改搜索（参考前台UI） start-->
                  <tr>
                      <td colspan="10">
                        <style>
                            .chooseCon {
                                
                                overflow: hidden;
                               
                                width: 1044px;
                            }
                            .chooseCon h2 {
                                border-bottom: 1px dashed #E7E7E7;
                                height: 30px;
                            }
                            
                            h1, h2, h3, h4, h5, h6 {
                                font-size: 12px;
                            }
                            .chooseCon h2 b {
                                background: url("/image/icons/icon_uparrow.gif") no-repeat scroll right center transparent;
                                color: #00378A;
                                cursor: pointer;
                                float: left;
                                font-size: 14px;
                                line-height: 25px;
                                padding-right: 20px;
                            }
                            .chooseCon h2 span {
                                float: right;
                                font-weight: normal;
                                line-height: 30px;
                            }
                            .chooseCon ul {
                                width: 100%;
                            }
 
                            ul, li, ol {
                                list-style: none outside none;
                            }
                            .chooseCon li {
                                border-bottom: 1px dashed #E7E7E7;
                                color: #111111;
                                float: left;
                                line-height: 20px;
                                overflow: hidden;
                                width: 100%;
                            }
                            .chooseCon li b {
                                display: inline;
                                float: left;
                            }
                            .chooseCon li div a {
                                color: #111111;
                                display: block;
                                float: left;
                                line-height: 12px;
                                margin: 5px 16px 5px 0;
                                padding: 2px 3px 3px;
                                white-space: nowrap;
                            }
                            .chooseCon li div a.selected, .chooseCon li a.selected:hover {
                                background: none repeat scroll 0 0 #138DDA;
                                color: #FFFFFF;
                                text-decoration: none;
                            }
                        </style>
                        
                          <div class="chooseCon" >
                              <h2>
                                <b id="_search_option_btnshow" onClick="jQuery_ChangeOption('_search');">客户来源 ref URL type --- 筛选</b>
                                
                                <span><a href="<?php echo tep_href_link('stats_order_analysis.php') ?>">重置筛选条件</a>&nbsp;&nbsp;<a href="referer_type.php" >编辑分类</a></span>
                              </h2>

                              <ul id="_search_option" <?php if($search_type=='orders') echo 'style="display:none"';?>>	
                               <?php 
                                /**
                                 * Tree 树型类(无限分类)
                                 *
                                 * @author Kvoid
                                 * @copyright http://kvoid.com
                                 * @version 1.0
                                 * @access public
                                 * @example
                                 *   $tree= new Tree($result);
                                 *   $arr=$tree->leaf(0);
                                 *   $nav=$tree->navi(15);
                                 */
                                class Tree {

                                    private $result;
                                    private $tmp;
                                    private $arr;
                                    private $already = array();

                                    /**
                                     * 构造函数
                                     *
                                     * @param array $result 树型数据表结果集
                                     * @param array $fields 树型数据表字段，array(分类id,父id)
                                     * @param integer $root 顶级分类的父id
                                     */
                                    public function __construct($result, $fields = array('id', 'pid'), $root = 0) {
                                        $this->result = $result;
                                        $this->fields = $fields;
                                        $this->root = $root;
                                        $this->handler();
                                    }

                                    /**
                                     * 树型数据表结果集处理
                                     */
                                    private function handler() {
                                        foreach ($this->result as $node) {
                                            $tmp[$node[$this->fields[1]]][] = $node;
                                        }
                                        krsort($tmp);
                                        for ($i = count($tmp); $i > 0; $i--) {
                                            foreach ($tmp as $k => $v) {
                                                if (!in_array($k, $this->already)) {
                                                    if (!$this->tmp) {
                                                        $this->tmp = array($k, $v);
                                                        $this->already[] = $k;
                                                        continue;
                                                    } else {
                                                        foreach ($v as $key => $value) {
                                                            if ($value[$this->fields[0]] == $this->tmp[0]) {
                                                                $tmp[$k][$key]['child'] = $this->tmp[1];
                                                                $this->tmp = array($k, $tmp[$k]);
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                            $this->tmp = null;
                                        }
                                        $this->tmp = $tmp;
                                    }

                                    /**
                                     * 反向递归
                                     */
                                    private function recur_n($arr, $id) {
                                        foreach ($arr as $v) {
                                            if ($v[$this->fields[0]] == $id) {
                                                $this->arr[] = $v;
                                                if ($v[$this->fields[1]] != $this->root)
                                                    $this->recur_n($arr, $v[$this->fields[1]]);
                                            }
                                        }
                                    }

                                    /**
                                     * 正向递归
                                     */
                                    private function recur_p($arr) {
                                        foreach ($arr as $v) {
                                            $this->arr[] = $v[$this->fields[0]];
                                            if ($v['child'])
                                                $this->recur_p($v['child']);
                                        }
                                    }

                                    /**
                                     * 菜单 多维数组
                                     *
                                     * @param integer $id 分类id
                                     * @return array 返回分支，默认返回整个树
                                     */
                                    public function leaf($id = null) {
                                        $id = ($id == null) ? $this->root : $id;
                                        return $this->tmp[$id];
                                    }

                                    /**
                                     * 导航 一维数组
                                     *
                                     * @param integer $id 分类id
                                     * @return array 返回单线分类直到顶级分类
                                     */
                                    public function navi($id) {
                                        $this->arr = null;
                                        $this->recur_n($this->result, $id);
                                        krsort($this->arr);
                                        return $this->arr;
                                    }

                                    /**
                                     * 散落 一维数组
                                     *
                                     * @param integer $id 分类id
                                     * @return array 返回leaf下所有分类id
                                     */
                                    public function leafid($id) {
                                        $this->arr = null;
                                        $this->arr[] = $id;
                                        $this->recur_p($this->leaf($id));
                                        return $this->arr;
                                    }

                                }
                                    $query = tep_db_query('SELECT `customers_ref_type_id` , `customers_ref_type_value` , `customers_ref_type_name` , `customers_ref_type_pid`  FROM ' . TABLE_CUSTOMERS_REF_TYPE2);
                                    $n = 0;
                                    while ($cate = tep_db_fetch_array($query)) {
                                        $arr[$n]['id'] = $cate['customers_ref_type_id'];
                                        $arr[$n]['name'] = $cate['customers_ref_type_name'];
                                        $arr[$n]['value'] = $cate['customers_ref_type_value'];
                                        $arr[$n]['pid'] = $cate['customers_ref_type_pid'];
                                        $n++;
                                    }
                                    $tree = new Tree($arr);
                                    $trees = $tree->leaf();
                                    
                                    foreach($trees as $k=>$tree){    
                                        
                                  ?>
                                    <li class="moreOpt" mod="m" id="_search_option_item">
                                        <b><?php echo $tree['name'] ?>：</b>
                                        <div>
                                            <a href="javascript:" onClick="setStatus(this);" val="p_<?php echo $tree['id'] ?>" 
                                            <?php
                                            if (tep_not_null($ref_type_id_arr)){
                                                if (in_array($tree['id'],$ref_type_id_arr)){
                                                    echo 'class="selected"';
                                                }else{
                                                    echo 'class=""';
                                                }
                                            }
                                            ?>
                                            >全部</a>
                                            <input style="display:none" type="checkbox"  name="ref_type[]" id="p_<?php echo $tree['id'] ?>" value="<?php echo $tree['id']; ?>"
                                            <?php
                                            if (tep_not_null($ref_type_id_arr)){
                                                if (in_array($tree['id'],$ref_type_id_arr)){
                                                    echo 'checked';
                                                }else{
                                                    echo '';
                                                }
                                            }
                                            ?>
                                            />
                                            <?php
                                                if (is_array($tree['child'])){
                                                    foreach($tree['child'] as $k=>$child){
                                            ?>
                                            <a href="javascript:" onClick="setStatus(this);" val="c_<?php echo $child['id']; ?>" 
                                            <?php
                                            if (tep_not_null($ref_type_id_arr)){                                                
                                            
                                                if (in_array($child['id'],$ref_type_id_arr)){
                                                    echo 'class="selected"';
                                                }else{
                                                    echo 'class=""';
                                                }
                                            }
                                            ?>
                                            ><?php echo $child['name'] ?></a>
                                            <input style="display:none" type="checkbox" name="ref_type[]" id="c_<?php echo $child['id'] ?>"   value="<?php echo $child['id'] ?>"
                                            <?php
                                            if (tep_not_null($ref_type_id_arr)){
                                                if (in_array($child['id'],$ref_type_id_arr)){
                                                    echo 'checked';
                                                }else{
                                                    echo '';
                                                }
                                            }
                                            ?>
                                            />
                                            <?php 
                                                    }
                                                }
                                            ?>
                                        </div>
                                    </li>
                                  <?php 
                                     
                                    }
                                  ?>
                            </ul>
                            <div class="moreOpts"></div>
                            <input type="hidden" name="ref_type_id" id="ref_type_id" value="<?php echo $ref_id_m ?>"/>
                            <script type="text/javascript">
                            
                            // 设置选中状态
                            function  setStatus(obj){
                                
                                var ref_pid = jQuery(obj).siblings('input');    
                                //var ref_all_id = jQuery("#ref_type_id").val(); // 已经存在的所有值
                                //var ref_id = jQuery(obj).attr('val'); // 当前id
                                var myReg = /p_/;
                                var str = jQuery(obj).attr("val");
                                if (jQuery("#"+jQuery(obj).attr("val")).attr("checked") == true){
                                    jQuery("#"+jQuery(obj).attr("val")).attr("checked", false);
                                    jQuery(obj).attr('class',"");
                                }else{
                               
                                    if (myReg.test(str)){                                    
                                        jQuery(obj).attr('class',"selected");                                    
                                        jQuery(obj).siblings('a').attr('class','');
                                        // 设置“全部”为checked状态，其他同辈元素为空
                                        
                                        for (var i=0; i<ref_pid.length; i++){
                                        
                                            jQuery("#"+jQuery(ref_pid[i]).attr("id")).attr("checked", false);
                                            
                                        }
                                        jQuery(obj).siblings('input:first').attr("checked", true);
                                    }else{
                                        jQuery(obj).siblings('input:first').attr("checked", false);
                                        jQuery("#"+jQuery(obj).attr("val")).attr("checked", true);
                                        jQuery(obj).siblings('a:first').attr('class','');
                                        jQuery(obj).attr('class',"selected");
                                    }
                                
                                }
                                setRefTypeIds(obj);
                                
                            }
                            
                            function setRefTypeIds(obj){
                                var ref_type_tes = jQuery("#ref_type_id").val();
                                var ref_add_id = jQuery("#"+jQuery(obj).attr('val')).val();
                                if (ref_type_tes == ''){
                                
                                    jQuery("#ref_type_id").val(ref_add_id);
                                }else{
                                    jQuery("#ref_type_id").val(ref_type_tes + ',' + ref_add_id);
                                }
                                
                            }
                            
                            
                            
                            jQuery('#MoreOptsA').click(function(){
                                if(jQuery("#MoreOptsA").html() == "展开更多筛选项&amp;gt;&amp;gt;"){
                                    /* 写cookie记录操作状态 */
                                    setCookie('moreOptsStatus', 'show', 1);
                                    moreOptsShowOrHide();
                                }else{
                                    /* 写cookie记录操作状态 */
                                    setCookie('moreOptsStatus', 'hide', 1);
                                    moreOptsShowOrHide();
                                }
                            });
                            function jQuery_ChangeOption(sch_id){
                                var _search_option=jQuery('#'+sch_id+'_option');
                                var a = jQuery('#'+sch_id+'_option_btnshow');
                                if(_search_option.attr('isHidden') == "true"){
                                    //_search_option.slideDown();
                                    _search_option.attr('isHidden','');
                                    a.removeClass('hidden');
                                    /* 写cookie记录操作状态 */
                                    setCookie(sch_id+'_option', 'show', 1);
                                    jQuery("#MoreOptsA").show();
                                    jQuery(_search_option).show();
                                }else{
                                    _search_option.attr('isHidden','true');
                                   // _search_option.slideUp();
                                    a.addClass('hidden');
                                    /* 写cookie记录操作状态 */
                                    setCookie(sch_id+'_option', 'hide', 1);
                                    jQuery("#MoreOptsA").hide();
                                    jQuery(_search_option).hide();
                                }
                            }
                            /* 写和取得 cookie {*/
                            function setCookie(name, value) {
                                var argv = setCookie.arguments;
                                var argc = setCookie.arguments.length;
                                var expires = (argc > 2) ? argv[2] : null;
                                if (expires != null) {
                                   var LargeExpDate = new Date ();
                                   LargeExpDate.setTime(LargeExpDate.getTime() + (expires*1000*3600*24));
                                }
                                document.cookie = name + "=" + escape (value)+"; path=/"+((expires == null) ? "" : ("; expires=" +LargeExpDate.toGMTString()));
                            }
                            function getCookie(Name) {
                                var search = Name + "="
                                if (document.cookie.length > 0) {
                                   offset = document.cookie.indexOf(search);
                                   if(offset != -1) {
                                    offset += search.length;
                                    end = document.cookie.indexOf(";", offset);
                                    if(end == -1) end = document.cookie.length;
                                    return unescape(document.cookie.substring(offset, end));
                                   }else {
                                    return '';
                                   }
                                }
                            }
                            /* 写和取得 cookie }*/                            
                            jQuery().ready(function() {
                                var NowCookie = getCookie("_search_option");
                                if(NowCookie=="hide"){
                                    var _search_option=jQuery('#'+'_search'+'_option');
                                    var a = jQuery('#'+'_search'+'_option_btnshow');
                                    _search_option.attr('isHidden','true');
                                    _search_option.hide();
                                    a.addClass('hidden');
                                    jQuery("#MoreOptsA").hide();
                                }
                            });
                            
                            </script>


                            </div>
                      </td>
                  </tr>
                  <!-- by panda修改搜索（参考前台UI） end-->
                  <tr>
                    <td class="main" align="right">&nbsp;</td>
                    <td class="main" align="left">&nbsp;
                       
                        <input name="Send" type="submit" value="Send" style="width:100px; height:30px; margin-top:10px;">
                        <input name="outputcsv" type="submit" value="outputcsv" style="width:100px; height:30px; margin-top:10px;">
                    </td>
                    <td>&nbsp;</td>
                    <td class="main" align="right">&nbsp;</td>
                    <td class="main" align="left">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td colspan="4" align="right" nowrap class="main">
                    <?php
                    //统计订单总金额
                    if($search_type =='orders' && tep_not_null($s_orders_status)){
                        //$sql_str = "select SUM(ot.value) as orders_sum ";
                        $sql_str = "select ot.value as orders_sum, orders_total_id ";
                        $color = '#000000';
                        if($s_orders_status=='6'){
                            //$sql_str = "select SUM(o.orders_cancelled_total) as orders_sum ";
                            $sql_str = "select o.orders_cancelled_total as orders_sum ";
                            $color = '#FF0000';
                        }
                        
                        //$sql_str.=" FROM " . TABLE_CUSTOMERS . " c,  address_book ab , " .TABLE_CUSTOMERS_INFO." ci, ". TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS . " o, ".TABLE_ORDERS_TOTAL." ot where ci.customers_info_id = c.customers_id AND c.customers_id = o.customers_id AND o.orders_id = op.orders_id AND ot.orders_id = o.orders_id AND ot.class='ot_total'  AND c.customers_default_address_id = ab.address_book_id ".$where_exc ." ";
                        $sql_str.=" FROM " . TABLE_CUSTOMERS . " c,  address_book ab , " .TABLE_CUSTOMERS_INFO." ci, ". TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS . " o, ".TABLE_ORDERS_TOTAL." ot where ci.customers_info_id = c.customers_id AND c.customers_id = o.customers_id AND o.orders_id = op.orders_id AND ot.orders_id = o.orders_id AND ot.class='ot_total'  AND c.customers_default_address_id = ab.address_book_id ".$where_exc ." Group By o.orders_id ";
                        
                        $sql = tep_db_query($sql_str);
                        $orders_total = 0;
                        while( $row = tep_db_fetch_array($sql)){
                            //echo $row['orders_sum'].'&nbsp;&nbsp;'.$row['orders_total_id'].'<br>';
                            $orders_total += $row['orders_sum'];
                        }
                        echo '<div>订单总金额：<b style="color:'.$color.'">'.$currencies->format($orders_total).'</b></div>';
                    }
                    ?>
                    <?php
                    //用户简繁比例
                    if(!tep_not_null($_GET['customers_char_set'])){
                        echo '<div>&nbsp;&nbsp;简体占 <b>'.$cn_num_proportion. '%</b>';
                        echo '&nbsp;&nbsp;繁体占 <b>'.$tw_num_proportion. '%</b></div>';
                        
                    }
                    ?>					</td>
                    </tr>
                </table></td>
              </tr>
            </table>

          <?php echo '</form>';?>
          </fieldset>
          <!--search form end-->
          </td>
      </tr>
      <tr>
        <td>
        <fieldset>
          <legend align="left"> Stats Results </legend>

        <table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr>
            <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $orders_split->display_count($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                <td class="smallText" align="right"><?php echo $orders_split->display_links($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'],tep_get_all_get_params(array('page','y','x', 'action'))); ?>&nbsp;</td>
              </tr>
            </table></td>
          </tr>

          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="1" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" nowrap="nowrap"><?php echo TABLE_HEADING_BUY_STATUS; ?></td>
                <td class="dataTableHeadingContent" nowrap="nowrap">Order ID</td>
                <td class="dataTableHeadingContent" nowrap="nowrap"><?php echo TABLE_HEADING_CUSTOMERS; ?></td>
                <td class="dataTableHeadingContent" nowrap="nowrap">来自国家/地区</td>
                
                <td class="dataTableHeadingContent" nowrap="nowrap"><?php echo TABLE_HEADING_REG_TIME; ?></td>
                <td class="dataTableHeadingContent" nowrap="nowrap"><?php echo TABLE_HEADING_CUSTOMERS_FROM; ?></td>
				<?php  if($search_type=='orders'){?>
				<td class="dataTableHeadingContent" nowrap="nowrap"><a href="?<?php echo $str_url_by?>">订单来源</a></td>
				<td class="dataTableHeadingContent" nowrap="nowrap">销售工号</td>
				<td class="dataTableHeadingContent" nowrap="nowrap">付款状态</td>
				<td class="dataTableHeadingContent" nowrap="nowrap">如何下的订单</td>
				<td class="dataTableHeadingContent" nowrap="nowrap">订单(国内/国外)</td>
				<?php }?>
                <td class="dataTableHeadingContent" nowrap="nowrap">
                <?php
                if($search_type!='orders'){
                    echo '客户来源';
                }else{
                    echo TABLE_HEADING_BUY_TIME;
                }
                ?>
                </td>
                <td class="dataTableHeadingContent" nowrap="nowrap"><?php echo TABLE_HEADING_TOTAL_ORDER; ?></td>
                <td class="dataTableHeadingContent" nowrap="nowrap"><?php echo TABLE_HEADING_ORDER_STATUS; ?></td>
              </tr>
<?php
  while ($orders = tep_db_fetch_array($orders_query)) {
    $rows++;

    if (strlen($rows) < 2) {
      $rows = '0' . $rows;
    }
    
    $bg_color = "#F0F0F0";
    if((int)$rows %2 ==0 && (int)$rows){
        $bg_color = "#ECFFEC";
    }
?>
              <tr class="dataTableRow" style="cursor:auto; background-color:<?= $bg_color;?>">
                <td class="dataTableContent">
                <?php
                $check_sql = tep_db_query('SELECT count(*) total FROM `orders` WHERE customers_id ="'.(int)$orders['customers_id'].'" LIMIT 1');
                $check_rows = tep_db_fetch_array($check_sql);
                if((int)$check_rows['total']){
                    echo 'Yes';
                }
                ?>
                </td>
                <td class="dataTableContent"><?php echo $orders['orders_id']; ?></td>
                <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS, 'cID='.(int)$orders['customers_id'].'&action=edit', 'NONSSL') . '">' . $orders['customers_firstname'] . ' ' . $orders['customers_lastname'] . '</a>'; ?></td>
                
                <td class="dataTableContent"><?php echo tep_get_country_name($orders['entry_country_id'])?></td>
                <td class="dataTableContent"><?php echo $orders['customers_info_date_account_created'];?></td>
                <td class="dataTableContent" id="td_ref_<?php echo (int)$orders['customers_id'];?>">
                <?php
                /**
                $ref_type_sql = tep_db_query('SELECT * FROM `customers_ref_type` ORDER BY `sort_num` ASC, `customers_ref_type_id` ASC ');
                $ref_type_rows = tep_db_fetch_array($ref_type_sql);
                $values_array = array();
                $values_array[] = array('id'=> '0' , 'text'=> '');
                do{
                    $values_array[] = array('id'=> $ref_type_rows['customers_ref_type_id'] , 'text'=> $ref_type_rows['customers_ref_type_name']);
                }while($ref_type_rows = tep_db_fetch_array($ref_type_sql));
                $customers_referer_type = $orders['customers_referer_type'];
                echo tep_draw_pull_down_menu('customers_referer_type', $values_array, $customers_referer_type, 'onChange="write_ref_id(this.value,'.(int)$orders['customers_id'].')"');
                */
                $customers_referer_type = $orders['customers_referer_type'];
                ?>
                <select name="customers_referer_type" onChange="write_ref_id(this.value, <?=(int)$orders['customers_id'];?>)">   
                    <option value=''></option>
                    <?php  displayArray2($trees, 0, $ref_type_id, $customers_referer_type); ?>
                </select>
                </td>
				<?php  if($search_type=='orders'){?>
				<td class="dataTableContent"><a href="<?php 
				if($orders['customers_advertiser']==''&&$orders['customers_ad_click_id']!=0){
					 $order_from=get_customers_advertiser($orders['customers_ad_click_id']);
					 }
					 	elseif($orders['customers_advertiser']!=''){
					 		$order_from=getUrlOutOther($orders['customers_advertiser']);}
					 	else {
						if($orders['orders_paid']!=0)
						$order_from='usitrip.com';
						else
						$order_from='';
						} 
					 	echo '?'.$str_url_from.'order_next='.$order_from;?>"><?php echo $order_from;?></a></td>
				<td class="dataTableContent"><?php  echo tep_get_job_number_from_admin_id($orders['orders_owner_admin_id']);?></td>
				
				<td class="dataTableContent"><?php if($orders['orders_paid']!=0)echo '<font color="red">已付款</font>';else echo'未付款';?></td>
				<td class="dataTableContent"><?php echo $order_is_array[$orders['is_other_owner']];?></td>
                <td class="dataTableContent"><?php echo (substr($orders['guest_emergency_cell_phone'], 0,3)=='+86')?'国内':'国外'?></td>
				<?php }?>
				<td class="dataTableContent">
                <?php
                //当目标为用户时显示用户来源url
                
                if($search_type!='orders'){
                    if(tep_not_null($orders['customers_referer_url'])){
                        echo '<a href="'.$orders['customers_referer_url'].'" target="_blank" title="'.$orders['customers_referer_url'].'" >'.substr($orders['customers_referer_url'], 0, 26).'...</a>';
                    }else{
                        echo '&nbsp;';
                    }
                }else{
                    echo $orders['date_purchased'];
                }
                ?>
				
                </td>
				 
                <td class="dataTableContent">
                <?php
                /*echo $orders['order_total'];
                if($orders['orders_status']=='6'){
                    echo '<div  style="color:#FF0000">'.$currencies->format($orders['orders_cancelled_total']).'</div>';
                }                
                */
                ?>
                </td>
                <td class="dataTableContent">
                <?php
                $status_sql = tep_db_query('SELECT * FROM `orders_status` WHERE orders_status_id="'.(int)$orders['orders_status'].'" AND language_id="'.(int)$languages_id.'" ');
                $status_rows = tep_db_fetch_array($status_sql);
                echo $status_rows['orders_status_name'];
                ?>				</td>
              </tr>
              
              <?php
              if($search_type !='orders'){
              //取得该用户的所有订单
                $cus_order_sql = tep_db_query("select o.orders_id, o.date_purchased, o.orders_status, o.orders_cancelled_total, ot.text as order_total
                 FROM ". TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS . " o, ".TABLE_ORDERS_TOTAL." ot where o.customers_id ='".(int)$orders['customers_id']."'  AND o.orders_id = op.orders_id AND ot.orders_id = o.orders_id AND ot.class='ot_total' Group By o.orders_id Order By o.orders_id DESC " );
                
                while($cus_order_rows = tep_db_fetch_array($cus_order_sql)){
              ?>
              <tr class="dataTableRow" style="cursor:auto; background-color:<?= $bg_color;?>">
                <td class="dataTableContent">&nbsp;</td>
                <td class="dataTableContent"><?php echo $cus_order_rows['orders_id']; ?></td>
                <td class="dataTableContent">&nbsp;</td>
                <td class="dataTableContent">&nbsp;</td>
                <td class="dataTableContent">&nbsp;</td>
                <td class="dataTableContent">&nbsp;</td>
                <td class="dataTableContent"><?php echo $cus_order_rows['date_purchased']?></td>
                <td class="dataTableContent">
                <?php
                echo $cus_order_rows['order_total'];
                if($cus_order_rows['orders_status']=='6'){
                    echo '<div  style="color:#FF0000">'.$currencies->format($cus_order_rows['orders_cancelled_total']).'</div>';
                }
                
                ?>

                </td>
                <td class="dataTableContent">
                <?php
                $status_sql = tep_db_query('SELECT * FROM `orders_status` WHERE orders_status_id="'.(int)$cus_order_rows['orders_status'].'" AND language_id="'.(int)$languages_id.'" ');
                $status_rows = tep_db_fetch_array($status_sql);
                echo $status_rows['orders_status_name'];
                ?>				</td>
              </tr>
              <?php
                }
              }
              ?>
<?php
  }
?>
            </table></td>
          </tr>
          <tr>
            <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $orders_split->display_count($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                <td class="smallText" align="right"><?php echo $orders_split->display_links($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'],tep_get_all_get_params(array('page','y','x', 'action'))); ?>&nbsp;</td>                
              </tr>
            </table></td>
          </tr>
        </table>
        
        </fieldset>
        </td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
