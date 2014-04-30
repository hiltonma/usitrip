<?php ob_start();?>
<div id="footpage">
	<div id="abouts">
    	<div class="abouts_left" id="left">
        	<div class="left_func">
                <!-- 攻略标题 -->
				<?php foreach($type_info as $value){?>
                <div class="func_list">
				
                    <h2><a <?php if($value['type_id']==$parent_id)echo 'class="click"';?> href="<?= tep_href_link('raiders_list.php','parent_id='.$value['type_id']);?>"><?=$value['type_name']?>攻略</a></h2>
                    <ul>
						<?php foreach($value['son'] as $v){?>
                        <li><a <?php if($v['type_id']==$type_id)echo 'class="click"';?> href="<?= tep_href_link('raiders_list.php','type_id='.$v['type_id'].'&parent_id='.$value['type_id']);?>"><?=$v['type_name']?>攻略<b>></b></a></li>
						<?php }?>
                    </ul>
                </div>
				<?php }?>
            </div>
        	
            <!-- 最新专题 -->
            <div class="lastest_action">
            	<h2>最新专题</h2>
                <ul>
				 <?php 
				 $_tag1 = "raiders_banner_1";
				 $_tag2 = "raiders_banner_2";
				 $bannersR1 = get_banners($_tag1);
				 $bannersR2 = get_banners($_tag2);
		for($i=0, $n=sizeof($bannersR1); $i<$n; $i++){
			if(tep_not_null($bannersR1[$i]['FinalCode'])){
				echo $bannersR1[$i]['FinalCode'];
			}else{
			?>
			<li><a href="<?=$bannersR1[$i]['links'];?>" tag="<?= $_tag1?>" target="_blank"><img border="0" alt="<?=$bannersR1[$i]['alt'];?>" src="<?=$bannersR1[$i]['src'];?>" /></a></li>
			<?php
			}
		}
		?>
		 <?php 
		for($i=0, $n=sizeof($bannersR2); $i<$n; $i++){
			if(tep_not_null($bannersR2[$i]['FinalCode'])){
				echo $bannersR2[$i]['FinalCode'];
			}else{
			?>
			<li><a href="<?=$bannersR2[$i]['links'];?>" tag="<?= $_tag1?>" target="_blank"><img border="0" alt="<?=$bannersR2[$i]['alt'];?>" src="<?=$bannersR2[$i]['src'];?>" /></a></li>
			<?php
			}
		}
		?>
                <!--	<li><a href="" target="_blank"><img src="http://www.xiaoming.com/image/nav/abouts_img4.jpg" alt="" /></a></li>
                    <li><a href="" target="_blank"><img src="http://www.xiaoming.com/image/nav/abouts_img4.jpg" alt="" /></a></li>-->
                </ul>
            </div>
            <!-- 最新专题 end -->
            
            <!-- 热销排行 -->
          <div class="arrange_warp cfix">
                <div class="title_1"><h2>热销排行榜</h2></div>
                <div class="cont">
                        <ul>
						<?php foreach($best_sell as $value){?>
                            <li class="s_1"><span class="color_orange fr">$<?php echo (int)$value['products_price']?>+</span><a title="<?=$value['products_name']?>" href="<?=tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $value['products_id'])?>"><?=$value['products_name']?></a></li>
							<?php }?>
                       </ul>
                    </div>
          </div>
    	</div>  
    
        <div class="abouts_right" id="right">
        	<div class="aboutsTit">
            	<ul>
                	<li><?=$type_name?>攻略</li>
                </ul>
            </div>
            <div class="aboutsCont ">
            	<div class="func_cont_list">
            	<ul>
					<?php $i=0; foreach($list_info['info'] as $value){?>
                        <li <?php if(++$i%5==0) echo 'class="x"';?>><a href="<?=tep_href_link('raiders_info.php','parent_id='.$parent_id.'&type_id='.$type_id.'&article_id='.$value['article_id'])?>" title="<?=$value['article_title']?>"><?=$value['article_title']?></a><label><?=date('Y-m-d',strtotime($value['add_time']))?></label></li>
						
					<?php } ?>
					</ul>
                </div>
                <?php echo $list_info['page']?>
            </div>
        </div>
    </div>

</div>
<?php echo  db_to_html(ob_get_clean());?>