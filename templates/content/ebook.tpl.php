<?php ob_start();?>
<div class="contentLandingpage"><a name="top"></a>
	<div class="dhan_banner"><img src="/image/<?= $style1_01?>.jpg" /></div>
	<div class="dhan_banner"><img src="/image/<?= $style1_02?>.jpg" usemap="#Map" /></div>
	<div class="dhan_banner1"><img src="/image/<?= $style1_03?>.jpg" usemap="#Map2" /></div>
	
	
	<div class="contentLandingpageC">
		<div class="contentTxt">
			<p class="contentBt">内容简介：</p>
			<p class="contentBtCont">美国旅游出行必备，介绍了有关出入境、风土人情、交通出行、
				酒店住宿、旅游行程等美国旅游必备的内容。从行前准备到美国旅游，全
			面渗透美利坚的方方面面……</p>
			<table class="contentBtTab">
				<tr><td>第一章 前言</td><td>第二章 关于美国</td></tr>
				<tr><td>第三章 行前准备</td><td>第四章 入境</td></tr>
				<tr><td>第五章 美国旅游篇</td><td>第六章 美国生活篇</td></tr>
				<tr><td>第七章 购物篇</td><td>第八章 付小费问题</td></tr>
				<tr><td>第九章 关于走四方网</td><td></td></tr>
			</table>
		</div>
		<div class="contentAbstract">
			<p class="contentAbstractBt1">第一章 前言</p>
			<p class="contentAbstractTxt">对于大多数人来说，离开熟悉的地方去异域他乡，无论是旅行还是生活，都是一件既兴奋又紧张的事。是否能在陌生的环境如鱼得水，找到旅
			行的快乐，决定了是否能拥有一次好的旅行。一次好的旅行会让您爱上行走的快乐，一次坏的旅行会让您失去了解世界的勇气。为自己的旅行做好准备，拥有快乐旅行，精彩人生由此开始。《走四方网美国出行指南》是一本由专业旅游预定网站“走四方网www.tousforfun.com”为出行美国的游客，留学生，商务人士，探亲访友者们准备的电子书，其中包括关于美国概况，行前准备，入境知识，旅行篇，生活篇，美景美食推荐，购物名店推荐等内容全面详细的介绍，为您的美国之行奠定完整的知识储备，让您在美国的出行游刃有余。<br />出发前准备充分，旅游时处处小心，既可以达到享受旅游的目的，又能够玩得安全、安心。掌握本书的知识，避免因为文化差异所带来的各种尴尬，轻松搜罗异域风情，理性应对紧急情况，美国之行必将拥有完美记忆。</p>
	<p class="contentAbstractBt">第二章 关于美国</p>
			<p class="contentAbstractTxt">2.1. 美国概况<br />
				美国全称美利坚合众国（United States of America），是一个由五十个州和一个联邦直辖特区组成的宪政联邦共和制国家。其东濒大西洋，西临太平洋，北靠加拿大，南接墨西哥。美国国土面积超过962万平方公里，位居全球第三，次于俄罗斯和加拿大；其人口总量也超过三亿人，少于中国和印度。1776年7月4日，大陆会议在费城正式通过《独立宣言》，宣告美国诞生。自1870年以来，美国国民经济就高居全球第一。今天的美国则是联合国安理会五个常任理事国之一，其在全球的政治、经济、军事、娱乐等众多领域的庞大影响力更是其他国家所无法匹敌的。<br />
			……</p>
	<p class="contentAbstractBt">第三章 行前准备</p>
			<p class="contentAbstractTxt">包括护照、签证、机票、行李等几个方面最常见的问题。细节完美决定了旅行的质量，参照下面的内容，能为我们的旅行规避许多不必要的麻烦。<br />
			……</p>
	<p class="contentAbstractBt">第四章 入境</p>
			<p class="contentAbstractTxt">4.1. 国际航班之抵达流程<br />
				4.1.2. 卫生检疫：您在飞机内如果得到检疫所发的卫生健康卡,请填写必要事项并交到卫生检疫站。<br />
				4.1.3. 边防检查：请您确认入境卡是否填好,并连同护照、签证一并交边防检查站查验。<br />
				4.1.4. 领取交运行李：到行李提取处提取行李时，要认真核对是否自己的行李，并拿好自己的行李和行李牌出机场。<br />
			……</p>
		</div>
		<div class="contentRMore"><p>更多精彩内容，请下载《走四方美国出行指南》电子书详细阅读吧！<a href="javascript:scroll(0,0);" class="contentRMorea">返回顶部下载</a></p></div>
	</div>
</div>

<?php echo  db_to_html(ob_get_clean());?>

<map id="Map" name="Map">
  <area alt="" href="<?= $download_file_name_exe.'.zip'?>" coords="451,16,585,53" shape="rect"><area alt="" href="<?= $download_file_name_pdf.'.zip'?>" coords="720,15,860,53" shape="rect">
</map>
<map id="Map2" name="Map2">
  <area alt="" href="<?= $download_file_name_chm.'.zip'?>" coords="455,24,595,64" shape="rect"><area alt="" href="<?= $download_file_name_umd.'.zip'?>" coords="727,22,861,59" shape="rect">
</map>