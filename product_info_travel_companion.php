<?php
$product_info_travel_companion = TRAVEL_COMPANION_OFF_ON;
if($product_info_travel_companion== 'true'){
//统计当前团的结伴同游总数
$sql = tep_db_query('SELECT count(*) as total  FROM `travel_companion` WHERE products_id="'.(int)$products_id.'" AND status=1 ');
$row = tep_db_fetch_array($sql);
$total_companion = (int)$row['total'];
//取得当前团的所属目录
if((int)count($cPath_array)){
	$categories_id = $cPath_array[(count($cPath_array)-1)];
	$categories_name = preg_replace('/ .+/','',tep_get_categories_name($categories_id,1));
}

?>
<script type="text/javascript">
function hepl_window(url) {
	window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=no,width=708,height=800,screenX=150,screenY=150,top=150,left=150')
}

</script>

<?php
	require_once('travel_companion_tpl.php');

	//结伴同游帖子列表
		$travel_sql = tep_db_query('SELECT * FROM `travel_companion` WHERE products_id="'.(int)$products_id.'" AND status=1 ORDER BY t_companion_id DESC Limit 5 ');
		$travel_rows = tep_db_fetch_array($travel_sql);
		
?>

	<div class="pr_1_n">
	 <div class="pr_2_b_n" style="background:#fff">
		<div class="pr_3_b_img_n"><img src="image/p2.gif" /></div>
		<div class="pr_3_b_t_n"><?php echo db_to_html('结伴同游')?><a target="_blank" href="<?php echo tep_href_link('companions_process.php')?>" style="padding-left:4px"><img src="image/what.gif" alt="<?php echo db_to_html('帮助')?>" border="0" /></a></div>
	   <?php if((int)$total_companion){?>
	   <div class="target huise"><?php echo db_to_html('<p>共<b class="jifen_num">'.(int)$total_companion.'</b>人发帖&nbsp;</p>')?><p><a target="_blank" href="<?php echo tep_href_link('new_travel_companion_index.php','TcPath='.$cPath.'&products_id='.(int)$products_id);?>" class="sp3"><?php echo db_to_html('更多')?></a></p></div>
	   <?php }?>
	   
	 </div>
	  <div class="pr_2">
		<ul class="travel-c">
		<?php
		if((int)$travel_rows['t_companion_id']){
			do{
			$sir_ro_ms = '';
			if((int)$travel_rows['t_gender']=='1'){
				$sir_ro_ms = '先生';
			}
			if((int)$travel_rows['t_gender']=='2'){
				$sir_ro_ms = '女士';
			}
		?>
		<li><div class="tc-title"><p><b><?php echo db_to_html('发起人:')?></b><?php echo db_to_html(tep_db_output($travel_rows['customers_name']));?> <?php echo db_to_html($sir_ro_ms)?></p><span><?php echo db_to_html(substr($travel_rows['add_time'],0,10))?></span></div>
		<?php
		$TcPaStr = '';
		if(tep_not_null($TcPath)){
			$TcPaStr = '&TcPath='.$TcPath;
		}
		?>
			<div class="tc-content"><a href="<?php echo tep_href_link('new_bbs_travel_companion_content.php','t_companion_id='.(int)$travel_rows['t_companion_id']).$TcPaStr?>"><?php echo cutword(db_to_html(tep_db_output($travel_rows['t_companion_title'])),40);?></a></div></li>
		<?php
			}while($travel_rows = tep_db_fetch_array($travel_sql));
		}else{
			echo db_to_html('寻找游伴，拼房节省费用吧！');
		}
		?>
		
		</ul>
		<p style="text-align:right; float:right; padding-top:5px;">
		<?php if(tep_not_null($categories_name)){?>
		<?php echo db_to_html('查看 <a target="_blank" href="'.tep_href_link('new_travel_companion_index.php','TcPath='.$cPath).'" class="tell_f_a">'.$categories_name.'</a> 结伴同游帖');?>
		<?php }?>
		</p>
		<div align="right" style=" float:right; width:99%;margin-top:4px;">
<style type="text/css">
<!--
.jb_right_button {
float:left;
height:35px;
position:relative;
width:740px;
}
.jb_right_button_but {
float:right;
}
.jb_fb {
background:none repeat scroll 0 0 transparent;
border:medium none;
cursor:pointer;
font-size:12px;
height:23px;
}
.botton_nei {
border:1px solid #F8B709;
margin:0;
padding:0;
}
.botton_nei span {
background:url("image/jb_bt_bg.jpg") repeat-x scroll 0 0 transparent;
color:#000000;
cursor:pointer;
display:block;
padding:5px 10px 4px;
}
-->
</style>


        <button onclick="showPopup(&quot;CreateNewCompanion&quot;,&quot;CreateNewCompanionCon&quot;,1);" type="button" class="jb_fb jb_right_button_but"><div class="botton_nei"><span><?= db_to_html('发布立即结伴帖')?></span></div></button>
        
        </div>
	  </div>
	  </div>

<?php
//结伴同游帖子列表 end

}
?>