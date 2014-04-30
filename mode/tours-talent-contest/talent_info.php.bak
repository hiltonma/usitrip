<?php
/* 填写相关信息 */
!defined('_MODE_KEY') && exit('Access error!');
//header('Content-Type:text/html;charset=GB2312');
if(tep_session_is_registered('customer_id') ){   
    //$customer_id = $_SESSION['customer_id'];     
    //$smarty->assign('customer_id', $customer_id);   
    $getaction = $_GET['action'];
    if ($_POST['action'] && $_POST['action'] == 'update'){
        $sql_data_array = array();
        $error = false;
        //gender
        include('mode/tours-talent-contest/lib/function.php');
        
        $street_address = tep_db_prepare_input($_POST['street_address']);        
        $street_address = html_to_db($street_address);        
        $street_address = iconv('utf-8','gb2312',$street_address);        
        $street_address = getSafeHtml($street_address);
        $street_address = htmlspecialchars($street_address, ENT_QUOTES);
        
        
        $city = tep_db_prepare_input($_POST['city']);
        //$city = html_to_db($city);
        $city = iconv('utf-8','gb2312',$city);
        //$city = getSafeHtml($city);
        //$city = htmlspecialchars($city, ENT_QUOTES);
        
        $state = tep_db_prepare_input($_POST['state']);
        //$state = html_to_db($state);
        $state = iconv('utf-8','gb2312',$state);
        //$state = getSafeHtml($state);
        //$state = htmlspecialchars($state, ENT_QUOTES);
        
        
        if(isset($HTTP_POST_VARS['gender']) && in_array($HTTP_POST_VARS['gender'],array('f','m'))){
            $sql_data_array['customers_gender'] = $HTTP_POST_VARS['gender'];
        }
        
           
        
        //处理用户详细地址更新
        
        if ($error == false){            
            $sql_address_data_array = array(      						
      						'entry_country_id' => tep_db_prepare_input($HTTP_POST_VARS['defaultAddress_country']),
      						'entry_state'=>$state,
      						'entry_city'=>$city,      						
      						'entry_street_address'=>$street_address
            );
            //$sql_address_data_array = html_to_db ($sql_address_data_array);   
            //print_r($sql_address_data_array);
            
            if(is_numeric($account['customers_default_address_id']) && !empty($account['_default_address']) ){ ///检查是否包含默认地址没有则创建一个 
            tep_db_perform(TABLE_ADDRESS_BOOK, $sql_address_data_array, 'update', "customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$account['customers_default_address_id'] . "'");
            }else{
                $sql_address_data_array['customers_id'] = intval($customer_id);
                tep_db_perform(TABLE_ADDRESS_BOOK, $sql_address_data_array, 'insert');
                $customers_default_address_id = tep_db_insert_id();
                $sql_data_array['customers_default_address_id'] = $customers_default_address_id;
                $sql_data_array['customers_default_ship_address_id'] = $customers_default_address_id;
            }
        }
        
        
        $mobile_phone = tep_db_prepare_input((int)$_POST['mobile_phone']);
        
        
        $sql_data_array['customers_mobile_phone'] = $mobile_phone;
        
        //$sql_data_array = html_to_db ($sql_data_array);
        //print_r($sql_data_array);
        tep_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '" . (int)$customer_id . "'");        
        tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_account_last_modified = now() where customers_info_id = '" . (int)$customer_id . "'");        
        /* 达人信息 */
        
       
        
        $blog_url = tep_db_prepare_input($_POST['blog_url']);
        $blog_url = html_to_db($blog_url);
        $blog_url = iconv('utf-8','gb2312',$blog_url);
        $blog_url = getSafeHtml($blog_url);
        $blog_url = htmlspecialchars($blog_url, ENT_QUOTES);
        
        
       
        
        $user_job = tep_db_prepare_input($_POST['user_job']);
        $user_job = html_to_db($user_job);
        $user_job = iconv('utf-8','gb2312',$user_job);
        $user_job = getSafeHtml($user_job);
        $user_job = htmlspecialchars($user_job, ENT_QUOTES);
        
        
        
        $rem_trip = tep_db_prepare_input($_POST['rem_trip']);
        $rem_trip = html_to_db($rem_trip);
        $rem_trip = iconv('utf-8','gb2312',$rem_trip);
        $rem_trip = getSafeHtml($rem_trip);
        $rem_trip = htmlspecialchars($rem_trip, ENT_QUOTES);
        
        
        $most_to = tep_db_prepare_input($_POST['most_to']);
        $most_to = html_to_db($most_to);
        $most_to = iconv('utf-8','gb2312',$most_to);
        $most_to = getSafeHtml($most_to);
        $most_to = htmlspecialchars($most_to, ENT_QUOTES);
        
      
        
        $customer_id_res = tep_db_query("SELECT COUNT(*) AS rows FROM daren_user_info WHERE customers_id=".(int)$customer_id);
        $customer_info = tep_db_fetch_array($customer_id_res);
        $customer_count = $customer_info['rows'];
        if ($customer_count > 0){
            $sql = "UPDATE daren_user_info SET blog_url='".$blog_url."', user_job='".$user_job."', rem_trip='".$rem_trip."', most_to='".$most_to."' WHERE customers_id=".(int)$customer_id;            
        }else{            
            $sql = "INSERT INTO daren_user_info(customers_id, blog_url, user_job, rem_trip, most_to) values(".(int)$customer_id.", '".$blog_url."','".$user_job."','".$rem_trip."','".$most_to."')";
        }
        tep_db_query($sql);
        $getaction = $_POST['getaction'];
        
        if($getaction == 'edit'){
            echo "<script type='text/javascript'>
                alert('修改成功!');
                window.location.href='trip_player.php?mod=homepage&id=".intval($customer_id)."';
            </script>";
            //tep_redirect('trip_player.php?mod=homepage&id='.);        
            //tep_redirect('trip_player.php?mod=talent_readinfo');
        }else{
            echo "<script type='text/javascript'>
                window.location.href='trip_player.php?mod=talent_readinfo';
            </script>";
            exit;
        }
        
    }
    
    
    /* 显示用户相关信息 */
    $account = tep_get_customers_info_fix( (int)$customer_id );    
    /*取得走四方达人相关信息*/
    $sql = "SELECT * FROM  `daren_user_info` WHERE customers_id=".$customer_id;
    $account_query = tep_db_query($sql);
    $account_talent = tep_db_fetch_array($account_query);
    $account['blog_url'] = $account_talent['blog_url'];
    $account['user_job'] = $account_talent['user_job'];
    $account['rem_trip'] = $account_talent['rem_trip'];
    $account['most_to'] = $account_talent['most_to'];
    if ($account['entry_city'] == 'undefined'){
        $account['entry_city'] = ' ';
    }
    if ($account['entry_state'] == 'undefined'){
        $account['entry_state'] = ' ';
    }
    
    $src = 'images/face/'.$account['customers_face'];
    $WH = getimgHW3hw_wh($src,114,114);
    $wh_array=explode("@",$WH);
    $account['face_width'] = $wh_array[0];
    $account['face_height'] = $wh_array[1];
    $tep_draw_full_address_input = tep_draw_full_address_input('defaultAddress', $account['entry_country_id'], $account['entry_state'],$account['entry_city']);
    
    $smarty->assign('getaction', $getaction);
    $smarty->assign('tep_draw_full_address_input',$tep_draw_full_address_input);  
    $smarty->assign('account',$account);      
      
 }else{
    echo "<script type='text/javascript'>
    window.location.href='trip_player.php';
    </script>";
    tep_redirect('trip_player.php');
 }
?>