<?php
/* ͶƱ */
!defined('_MODE_KEY') && exit('Access error!');
if(isset($_POST['works_id']) && ($_GET['mod'] == 'votes')){    
    if ($vote_is_end){        
        $works_id = (int)$_POST['works_id'];
        $ip = tep_get_ip_address();
        $sql = "SELECT COUNT(*) AS rows FROM `daren_votes` WHERE TO_DAYS(votetime) = TO_DAYS(NOW()) AND votes_ip='".$ip."'";
        $query = tep_db_query($sql);
        $res = tep_db_fetch_array($query);
        $votesnum = $res['rows'];
        
        if ($votesnum < 3){
            $sql = "INSERT INTO `daren_votes` ( `works_id` , `votes_ip` , `votetime` )VALUES ('".$works_id."', '".$ip."', NOW( ));";
            if (tep_db_query($sql)){
                $sql = "UPDATE  `daren_works` SET  `works_votes` =  works_votes+1 WHERE  `works_id` =".$works_id;
                tep_db_query($sql);
                $query_sql = tep_db_query("SELECT works_votes FROM daren_works WHERE works_id=".$works_id);
                $votes = tep_db_fetch_array($query_sql);
                
                echo $votes['works_votes'];            
                exit;
            }
        }else{
            echo 0;
            exit;
        }
    }else{
        echo 0;
        exit;
    }
}else{
    tep_redirect('trip_player.php');
}

