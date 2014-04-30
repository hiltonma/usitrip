<?php
//overseas_study_download.php
//海外游学资料下载栏目
ob_start();
?>
<div class="mate_warp border_1 margin_b10">
	<div class="title_1"><h3 class="left-side-title">游学资料下载</h3></div>
    <ul class="studytour">
    	<li><a href="/download/overseas-study/america-visa-registration-form-for-students.doc">美国签证报名表——学生</a></li>
        <li><a href="/download/overseas-study/america-visa-material-list.doc">美国签证资料准备表</a></li>
        <li><a href="/download/overseas-study/overseas-study-tour-registration-form.zip">各国海外游学报名表</a></li>
        <li><a href="/download/overseas-study/visa-pack-for-america-study-tour.zip">美国游学签证资料包</a></li>
        <li><a href="/download/overseas-study/visa-pack-for-uk-study-tour.zip">英国游学签证资料包</a></li>
        <li><a href="/download/overseas-study/visa-pack-for-australian-study-tour.zip">澳洲游学签证资料包</a></li>
        <li><a href="/download/overseas-study/visa-pack-for-canada-study-tour.zip">加拿大游学签证资料包</a></li>
        <li><a href="/download/overseas-study/2012-overseas-study-summer-camp-manual.zip">2012年海外游学夏令营手册</a></li>
    </ul>
</div>

<?php
echo db_to_html(ob_get_clean());
?>