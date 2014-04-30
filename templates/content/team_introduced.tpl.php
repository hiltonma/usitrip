<?php ob_start();?>
	<div id="abouts">
    	<?php
		require(DIR_FS_TEMPLATES . TEMPLATE_NAME .'/about_left.php');
		?>
        <div class="abouts_right" id="right">
        	<div class="aboutsTit">
            	<ul>
                	<li>团队介绍</li>
                </ul>
            </div>
            <div class="aboutsCont">
                    <div class="about2_1">
                    <p>The Usitrip Team是一个专业的、充满激情的精英团队，我们的核心高层人员均来自全国优秀的互联网公司，美国总部团队更聚集美国旅游行业优秀人才。其中有网络技术精英负责网站的建设开发、从事多年旅游产品开发和操作的专家负责业务运做，并资深MBA管理专家负责公司的整体运营管理，成熟的网络、顺畅的运做，高效的管理保证了客户得到的是优质、安全、完善的服务。同时，而这个团队还在吸纳各方人才并不断壮大当中。</p>
                    <p>我们拥有着改革旅游电子商务的决心和热情，势将以创造出新颖、便利、优异的走四方旅游网品牌，作为团队的奋斗目标！ </p>
                    <p><img src="/image/nav/abouts_img4.jpg"  /></p>
                    </div>
                    <div class="about2_2">
                    	<dl>
                        	<dt><img src="/image/nav/abouts_img5.jpg" width="255" height="168" /></dt>
                            <dd>公司形象墙</dd>
                        </dl>
                    <dl class="noMargin">
                        	<dt><img src="/image/nav/abouts_img6.jpg" width="255" height="168" /></dt>
                            <dd>公司形象墙</dd>
                        </dl>
                    <dl>
                        	<dt><img src="/image/nav/abouts_img7.jpg" width="255" height="168" /></dt>
                            <dd>会议室</dd>
                        </dl>
                    <dl class="noMargin">
           	    <dt><img src="/image/nav/abouts_img8.jpg" width="255" height="168" /></dt>
                            <dd>商务中心</dd>
                      </dl>
                    <dl>
                        	<dt><img src="/image/nav/abouts_img9.jpg" width="255" height="168" /></dt>
                            <dd>公司一角</dd>
                      </dl>
                    <dl class="noMargin">
                        	<dt><img src="/image/nav/abouts_img10.jpg" width="255" height="168" /></dt>
                            <dd>公司一角</dd>
                        </dl>
                    <dl>
                        	<dt><img src="/image/nav/abouts_img11.jpg" width="255" height="168" /></dt>
                            <dd>团队讨论</dd>
                        </dl>
                    <dl class="noMargin">
           	    <dt><img src="/image/nav/abouts_img12.jpg" width="255" height="168" /></dt>
                            <dd>优秀员工</dd>
                        </dl>
                    <dl>
                        	<dt><img src="/image/nav/abouts_img13.jpg" width="255" height="168" /></dt>
                            <dd>积极工作</dd>
                        </dl>
                        <dl class="noMargin">
                        	<dt><img src="/image/nav/abouts_img14.jpg" width="255" height="168" /></dt>
                            <dd>英语培训活动</dd>
                        </dl>
                        <dl>
                        	<dt><img src="/image/nav/abouts_img15.jpg" width="255" height="168" /></dt>
                            <dd>积极工作</dd>
                        </dl>
                         <dl class="noMargin">
                        	<dt><img src="/image/nav/abouts_img16.jpg" width="255" height="168" /></dt>
                            <dd>羽毛球活动</dd>
                        </dl>
                        <div class="del_float" style="clear:both"></div>
                        
                    </div>
          </div>
        </div>
        <div class="del_float" style="clear:both"></div>
    </div>
<?php echo  db_to_html(ob_get_clean());?>