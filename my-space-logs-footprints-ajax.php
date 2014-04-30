<?php
require_once('includes/application_top.php');
header("Content-type: text/html; charset=utf-8");


function ajax_str($str){
	global $include;
	if($include!=true){
		return iconv(CHARSET,'utf-8'.'//IGNORE',$str);
	}else{
		return $str;
	}
}

if($include!=true && $_GET['action']=='delete_confirmation' && (int)$_GET['blog_footprints_id']){
	// Delete blog_footprints
	tep_db_query('DELETE FROM `blog_footprints` WHERE blog_footprints_id="'.(int)$_GET['blog_footprints_id'].'" AND customers_id ="'.(int)$customer_id.'" ');

}
?>
<?php
//½ÅÓ¡
$footprints_sql = tep_db_query('SELECT bf.blog_footprints_id, bf.blog_id,bf.blog_footprints_date,  b.customers_id as cser, b.blog_title  FROM `blog_footprints` bf,`blog` b  WHERE bf.blog_id = b.blog_id AND bf.customers_id="'.(int)$customer_id.'" Group By bf.blog_id  ORDER BY bf.blog_footprints_date DESC limit 100');
while($footprints_rows = tep_db_fetch_array($footprints_sql)){
	
?>

<div class="rizhi_cai">
              <p><span class="bg3_right_title_l"><a href="<?php echo tep_href_link('user-space-logs-details.php','cser='.(int)$footprints_rows['cser'].'&blog_id='.(int)$footprints_rows['blog_id']) ?>" class="lanzi4"><?php echo ajax_str(db_to_html(tep_db_output($footprints_rows['blog_title'])))?></a><span class="huise">&nbsp;&nbsp;<?php echo ajax_str(db_to_html(tep_db_output($footprints_rows['blog_footprints_date'])))?></span></span> <span  class="bg3_right_title_r "><a href="javascript:DeleteFootPrints('<?php echo $footprints_rows['blog_footprints_id'] ?>')" class="huise_di"><?php echo ajax_str(db_to_html('É¾³ý'))?></a></span></p></div>
			  
<?php }?>
	  