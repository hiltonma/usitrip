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
	  //右边广告栏 start{
	  $_tag1 = "raiders_banner_1";
	  $_tag2 = "raiders_banner_2";
	  $bannersR1 = get_banners($_tag1);
	  $bannersR2 = get_banners($_tag2);
	  ?>
	  <div class="banner_warp margin_b10">
        <!--广告栏-->
        <?php 
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
            	<div class="func_cont_list cifx">
                	<!-- 文章标题 -->
                	<h1 class="gl_title"><?=$info['info']['article_title']?></h1>
                    <p class="gl_from"><span>发布时间：<?=date('Y-m-d',strtotime($info['info']['add_time']))?></span><span class="blank"></span><span>来源于：<?=$info['info']['article_from']?></span></p>
                    <!-- 文章标题 end -->
                    
                   	<!-- 文章内容 -->
                    <div class="article_cont">
                    	<?=$info['info']['article_content']?>
                    </div>
                    <!-- 文章内容 end  -->
                    
                    <div class="article_about">
                    	<p class="art_search">相关搜索：<?php foreach($info['tags'] as $value){?><a href="<?=RaidersTags::checkTagsUrl($value['tags_url'])?>" target="_blank"><?=$value['tags_name']?></a><?php }?></p>
                        <p class="art_pn cfix">
                        	<span class="art_prev">上一篇：<?php if($info['ago']){?><a href="<?=tep_href_link('raiders_info.php','parent_id='.$parent_id.'&type_id='.$type_id.'&article_id='.$info['ago']['article_id'])?>"><?=$info['ago']['article_title']?></a><?php }else{echo '没有了!!!';}?></span>
                            <span class="art_next">下一篇：<?php if($info['after']){?><a href="<?=tep_href_link('raiders_info.php','parent_id='.$parent_id.'&type_id='.$type_id.'&article_id='.$info['after']['article_id'])?>"><?=$info['after']['article_title']?></a><?php }else{echo '没有了!!!';}?></span>
                        </p>
                    </div>
                    
                    <div class="cfix">
                    <!-- Baidu Button BEGIN -->
                    	<span class="share_title">分享到：</span>
                        <div id="bdshare" class="bdshare_t bds_tools get-codes-bdshare">
                        <a class="bds_qzone">QQ空间</a>
                        <a class="bds_tsina">新浪微博</a>
                        <a class="bds_tqq">腾讯微博</a>
                        <a class="bds_renren">人人网</a>
                        <a class="bds_t163">网易微博</a>
                        <span class="bds_more">更多</span>
                        <a class="shareCount"></a>
                        </div>
                        <script type="text/javascript" id="bdshare_js" data="type=tools&amp;uid=18719" ></script>
                        <script type="text/javascript" id="bdshell_js"></script>
                        <script type="text/javascript">
                        document.getElementById("bdshell_js").src = "http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion=" + Math.ceil(new Date()/3600000);
                        </script>
					<!-- Baidu Button END -->
                    </div> 
                </div>
            </div>
            <!-- 相关推荐 -->
            <div class="recom_box">
                <h2 class="recom_title">推荐相关内容</h2>
                <ul>
				<?php foreach($recommend as $value){?>
                    <li class="fl"><em>·</em><a href="<?=tep_href_link('raiders_info.php','parent_id='.$parent_id.'&type_id='.$value['article_type'].'&article_id='.$value['article_id'])?>"><?=$value['article_title']?></a></li>
					<?php }?>
                </ul>
            </div>
            <!-- 相关推荐 end -->
        </div>
    </div>

</div>
<?php echo  db_to_html(ob_get_clean());?>