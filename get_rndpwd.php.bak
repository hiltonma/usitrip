<?php
require('includes/application_top.php');
get_Convertutf8($_SESSION['language']);

if(tep_not_null($_GET['phone'])&&check_mobilephone($_GET['phone'])){
       //检查该手机是否已经注册过
      $check_sql = tep_db_query('SELECT * FROM `customers` WHERE confirmphone ="'.tep_db_input(tep_db_prepare_input($_GET['phone'])).'"');
      if((int)tep_db_num_rows($check_sql)){
          echo '4';
      }else{
          $phonenum= $_GET['phone'];
          $rndpwd=(integer)(rand(1000,9999));
          $content='尊敬的用户，你在走四方网的临时手机验证码是:'.$rndpwd.'。';
          $sms=new cpunc_SMS;
          if($sms->SendSMS($phonenum,$content,$chartset='GB2312')==true){
              echo '000';
          }else{
              echo '2';
          }
     }
}else{
    echo '3';
    //echo general_to_ajax_string(db_to_html('你输入的手机号有误！'));
}
?>