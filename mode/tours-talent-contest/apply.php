<?php
/*立即报名*/
!defined('_MODE_KEY') && exit('Access error!');
if(tep_session_is_registered('customer_id') ){
    /* 如果填写了个人资料就跳转到添加页面 */    
    tep_redirect('trip_player.php?mod=talent_info');
    
}else{    
    tep_redirect('trip_player.php?focus=true#loginForm');
}

?>