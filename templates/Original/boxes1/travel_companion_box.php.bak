<?php //结伴同游
$travel_companion_box = TRAVEL_COMPANION_OFF_ON;
if(($content=='index_products' || $content=='index_nested' ||  $content=='index_default' ) && $travel_companion_box == 'true'){
	//取得当前目录
	
	if($cPathOnly!='' && $cPathOnly != '134'){
		$cat_all = tep_get_categories($cat_all,$cPathOnly);
		$in_str = '';
		foreach((array)$cat_all as $key ){
			$in_str .= ','.(int)$key['id'];
		}
		$where_cate_in = ' AND ( categories_id in('.$cPathOnly. $in_str.') ||  categories_id =0 || categories_id="" || categories_id is null )';
	}
	$TcPath=$cPath;
	//if($cPathOnly=='24'){ $TcPath ='west'; }
	//if($cPathOnly=='25'){ $TcPath ='east'; }
	if($cPathOnly=='33'){ $TcPath =$cPathOnly; }
	if($cPathOnly=='54'){ $TcPath =$cPathOnly; }
	if($cPathOnly=='157'){ $TcPath =$cPathOnly; }

	$_limit = 10;
	$t_c_sql = ('SELECT t_companion_id, t_companion_title, t_companion_content, last_time, categories_id, products_id, customers_name, click_num, reply_num FROM `travel_companion` WHERE status ="1" '.$where_cate_in.' Order By last_time DESC limit '.$_limit);
	$t_c_sql = tep_db_query($t_c_sql);
	$t_c_rows = tep_db_fetch_array($t_c_sql);
	
?>
<script type="text/javascript">
function hepl_window(url) {
	window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=no,width=708,height=800,screenX=150,screenY=150,top=150,left=150')
}

function show_travel_companion_tips(int_num, tips_id_mun){
	var jiesong_info_tips = document.getElementById('travel_companion_tips_'+tips_id_mun);
	if(jiesong_info_tips!=null){
		if(int_num>0){
			jiesong_info_tips.style.display="";
		}else{
			jiesong_info_tips.style.display="none";
		}
	}
}

</script>
  <?php
  $travel_companion_get = "";
  if(tep_not_null($TcPath)){
	  $travel_companion_get .= 'TcPath='.$TcPath;
  }
  ?>
  <div class="mate_warp border_1 margin_b10 clearfix" style="height:auto">
  <div class="title_1">
  <h2 class="left-side-title"><?php echo db_to_html('结伴同游')?> <a target="_blank" title="<?php echo db_to_html('帮助')?>" href="<?php echo tep_href_link('companions_process.php')?>"><?php echo db_to_html('帮助')?></a><span><a href="<?php echo tep_href_link('new_travel_companion_index.php',$travel_companion_get)?>" target="_blank" ><?php echo db_to_html('我要发布')?></a></span></h2>
  </div>
  <div class="cont">
  <div class="friend-bar">
		<?php
		/*if($content!='index_default'){	//首页不显示这块	  
			  if(!(int)$customer_id){
				echo db_to_html('&nbsp;<a href="javascript:void(0);" onClick="showPopup(&quot;CreateNewCompanion&quot;,&quot;CreateNewCompanionCon&quot;,1);" >发起活动</a>&nbsp;|&nbsp;<a href="'.tep_href_link('login.php').'" >[登录]</a>/<a href="'.tep_href_link("create_account.php","", "SSL").'">[注册]</a>');
			  }else{
				echo db_to_html('<a href="javascript:void(0);" onClick="showPopup(&quot;CreateNewCompanion&quot;,&quot;CreateNewCompanionCon&quot;,1);" >发起活动</a>');
			  }
		}else{
			if(!(int)$customer_id){
				echo db_to_html('&nbsp;&nbsp;<a href="'.tep_href_link('login.php').'" >[登录]</a>/<a href="'.tep_href_link("create_account.php","", "SSL").'">[注册]</a>');
			}else{
				echo db_to_html('&nbsp;&nbsp;');
			}
		}	  */
		?>			  
  </div>
	 
	  <div class="friend-list">
	  <ul>
	  	
		<?php 
		if(tep_not_null($TcPath)){
			$substr_leng = 30;
		}else{
			$substr_leng = 29;
		}
		
		if((int)$t_c_rows['t_companion_id']){
			$do_loop = $_limit;
			do{
				
		?>
        <li style="z-index:<?=$do_loop?>;">
		<p>
		<?php
		$last_time_a = substr($t_c_rows['last_time'],0,10);
		$last_time_array = explode('-',$last_time_a);
		//echo $last_time_array[1].'/'.$last_time_array[2].'/'.substr($last_time_array[0],2,2);
		echo db_to_html(preg_replace('/^0/','',$last_time_array[1]).'月'.preg_replace('/^0/','',$last_time_array[2]).'日');
		?>
		</p>
		
		<?php
		$TcPaStr = '';
		if(tep_not_null($TcPath)){
			$TcPaStr = '&TcPath='.$TcPath;
		}
		?>
		<a href="<?php echo tep_href_link('new_bbs_travel_companion_content.php','t_companion_id='.(int)$t_c_rows['t_companion_id'].$TcPaStr)?>" target="_blank" <?php /* 按Sofia 的意思暂不需要这个效果 onmousemove="show_travel_companion_tips(1,<?php echo (int)$t_c_rows['t_companion_id']?>)" onmouseout="show_travel_companion_tips(0,<?php echo (int)$t_c_rows['t_companion_id']?>)" */ ?> >
		<?php
		$Tcate_string ='';
		if((int)$t_c_rows['categories_id'] && !tep_not_null($TcPath)){
			$Tcate_string = '['.tep_get_categories_name((int)$t_c_rows['categories_id']).'] ';
		}
		?>
		<?php echo cutword(db_to_html($Tcate_string . strip_tags($t_c_rows['t_companion_title'])),$substr_leng,'');?>
		
		
		</a>
		<?php /* 按Sofia 的意思 暂不显示这块 <div class="note-info" id="travel_companion_tips_<?php echo (int)$t_c_rows['t_companion_id']?>" style="text-decoration:none; display:none"> 
		<em></em>
		<dl>
		<dd><b><?php echo TITLE_BBS_CONTENT?></b>:<?php echo cutword(db_to_html(strip_tags($t_c_rows['t_companion_content'])), 110);?></dd>
		<dd style="padding-top:6px"><?php echo db_to_html('发起人:'.strip_tags($t_c_rows['customers_name']))?>&nbsp;&nbsp;&nbsp;&nbsp;<span><?php echo db_to_html('回复/查看:'.strip_tags($t_c_rows['reply_num'].'/'.$t_c_rows['click_num']))?></span></dd>
		</dl>
		<div class="come">
		<div class="huise">
		<?php
		if((int)$t_c_rows['products_id']){
			echo db_to_html('来自:'.tep_get_products_name((int)$t_c_rows['products_id']));
		}
		?>
		</div></div></div>*/ ?>
		
		</li>
		<?php
				$do_loop--;
			}while($t_c_rows = tep_db_fetch_array($t_c_sql));
		?>
        
		
		<?php
		}
		?>
		
        
      </ul>
	  
	  </div>
      </div>
      <?php if($content=='index_products'){
      	$banner_name='product_list_traverl_companion';//结伴同游里的广告栏
      	include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'banner_box.php');
      }?>
</div>
<?php require_once('travel_companion_tpl.php');?>
<?php
}
//结伴同游end
?>
