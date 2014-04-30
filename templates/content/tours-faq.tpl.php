<?php /*
<div class="leftside">
   <?php echo db_to_html($info_rows['description'])?>
</div>

<?php require(DIR_FS_TEMPLATES . TEMPLATE_NAME .'/column_right.php'); ?>
*/?>
<div id="help">
<div class="leftside otherpage">
    <div class="nei-leftside " >
        <div class="nei-leftside-top " ><b></b><span></span></div>
        <div class="leftside-box">
	    <h3><?php echo db_to_html('帮助中心')?></h3>
	    <div class="chufa-city">

	   <ul class="mudidi-doc">
       <?php
	   $catalog_sql = tep_db_query('SELECT information_id,info_title FROM `information` WHERE info_type ="旅美常识" AND visible=1 order by v_order ');
	   while($catalog_rows = tep_db_fetch_array($catalog_sql)){
	   	$class_li = '';
	   	if($catalog_rows['information_id']==(int)$info_rows['information_id']){
			$class_li = ' class="s" ';
		}
	   ?>
	   <li <?=$class_li?>><a href="<?= tep_href_link('tours-faq.php','information_id='.$catalog_rows['information_id'])?>" title="<?php echo db_to_html($catalog_rows['info_title'])?>"><?php echo db_to_html($catalog_rows['info_title'])?></a></li>
	   <?php
	   }
	   ?>
       <!--
	   <li class="s"><a>走四方订团流程</a></li>
       <li><a>签证与护照</a></li>
       <li><a>旅美须知</a></li>
       <li ><a>结伴同游帮助</a></li>
       -->
	   </ul>

        </div>
          <h3 class="left-side-title"><?php echo db_to_html('热门问题')?></h3>
	      <div class="faqList">
           <ul>
           <?php
		   $hit_question_sql = tep_db_query('SELECT * FROM `tour_question` WHERE languages_id ="'.(int)$languages_id.'" AND que_replied > 0 AND set_hit ="1" ORDER BY date DESC ');
		   while($hit_question = tep_db_fetch_array($hit_question_sql)){
		   ?>
				<li><a href="<?= tep_href_link('all_question_answers.php','que_id='.(int)$hit_question['que_id'])?>"><?php echo cutword(db_to_html(tep_db_output($hit_question['question'])),54,'...')?></a></li>
            <?php
			}
			?>
			<!--
			    <li><a>旅行团一般需要提前多久预订？</a></li>
                <li><a>旅游团负责接机送机吗？导游在哪儿</a></li>
                <li><a>若提前到达，可否安排住宿和交通？</a></li>
                <li><a>哪些物品不能携带出境？</a></li>
                <li><a>结伴同游怎么操作？</a></li>
			-->
            </ul>
            </div>

        </div>
    <div class="nei-leftside-bottom"><b></b><span></span></div>
   <div class="clear"></div></div>

      <div class="need-help">
    <div class="nei-leftside-top-help " ><b></b><span></span></div>
  <div class="leftside-box leftside-box-help" style="border-left:1px solid #52B9F4; border-right:1px solid #52B9F4; "><p><?php echo db_to_html('没有找到理想的答案？')?><a href="<?= tep_href_link('tour_question.php')?>"><?php echo db_to_html('我要提问')?></a></p></div>
  <div class="leftside-box leftside-box-help" style="border-left:1px solid #52B9F4; border-right:1px solid #52B9F4; "><p><?php echo db_to_html('如有任何意见或建议请发送邮件至')?><a href=mailto:jiandu@usitrip.com class="pageResults"><?php echo db_to_html('jiandu@usitrip.com')?></a></p></div>
    <div class="nei-leftside-bottom-help"><b></b><span></span></div>
   </div>
    </div>

<div class="rightcontent" style=" width:712px;">
<?php echo db_to_html($info_rows['description'])?>


</div>
</div>