<?php
//overseas_study_fqa.php
//海外游学FAQ
ob_start();
?>
<div class="mate_warp border_1 margin_b10">
	<div class="title_1"><h3 class="left-side-title">海外游学常见问题</h3></div>
    <ul class="studytour">
    	<li><a href="<?php echo tep_href_link('studytour.php')?>#st1">如何报名海外游学?</a></li>
        <li><a href="<?php echo tep_href_link('studytour.php')?>#st2">参加游学团能提高哪些能力?</a></li>
        <li><a href="<?php echo tep_href_link('studytour.php')?>#st3">学生能从夏令营中学到些什么?</a></li>
        <li><a href="<?php echo tep_href_link('studytour.php')?>#st4">游学的营地是怎样的?请详细介绍下?</a></li>
        <li><a href="<?php echo tep_href_link('studytour.php')?>#st11">家长如何了解孩子在境外的学习生活情况?</a></li>
        <li><a href="<?php echo tep_href_link('studytour.php')?>#st13">冬夏令营营员的家长是否可以随团?</a></li>
        <p class="more"><a href="<?php echo tep_href_link('studytour.php')?>" style="float:right;">&gt;&gt;了解更多</a></p>
    </ul>
</div>

<?php
echo db_to_html(ob_get_clean());
?>