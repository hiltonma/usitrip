<?php
/*
  $Id: links.php,v 1.2 2004/03/12 19:28:57 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  //require(DIR_FS_LANGUAGES . $language . '/privacy-policy.php');
$pageId = strval(strtolower($_GET['page']));
if(!in_array($pageId, array('australia','canada','usa','uk','qa','schengen','tips'))){
	$pageId='index';
}
$pageSelf = tep_href_link('visa-help.php','page='.$pageId);

  $pages = array(
	'index'=>array('签证协助','<!--<div class="pathLinks" id="BreadTop"><a href="'.HTTP_SERVER.'">首&nbsp;页</a> &gt;  <span>签证协助</span></div>-->
    <div class="contentLandingpage">
     <div class="dhan_banner">
      <img src="imgs/style1_01.jpg" />
    </div> 
     <div class="pgContent">
      <div class="pgContentLeft">
        <div class="pgContentLittlevan"><a href="'.tep_href_link('visa-help.php','page=qa','NONSSL',false).'">出国签证问与答</a></div>
        <div class="pgContentLittlevan"><a href="'.tep_href_link('visa-help.php','page=usa','NONSSL',false).'">美国签证</a></div>
        <div class="pgContentLittlevan"><a href="'.tep_href_link('visa-help.php','page=canada','NONSSL',false).'">加拿大签证</a></div>
        <div class="pgContentLittlevan"><a href="'.tep_href_link('visa-help.php','page=uk','NONSSL',false).'">英国签证</a></div>
        <div class="pgContentLittlevan"><a href="'.tep_href_link('visa-help.php','page=schengen','NONSSL',false).'">申根签证</a></div>
        <div class="pgContentLittlevan"><a href="'.tep_href_link('visa-help.php','page=australia','NONSSL',false).'">澳大利亚签证</a></div>
        <div class="pgContentLittlevan"><a href="'.tep_href_link('visa-help.php','page=tips','NONSSL',false).'">出境贴士</a></div>
      </div>
      <div class="pgContentRight">
        <div class="pgContentJs">
           <div class="pgContentJsPic"><a href="'.HTTP_SERVER.'/yellowstone-tours/packages/"><img src="imgs/style1_pic1.jpg" /></a></div>
           <div class="pgContentJsTxt">黄石国家公园，（Yellowstone National Park）简称黄石公园。是世界第一座国家公园，成立于1872年。黄石公园位于美国中西部怀俄明州的西北角，并向西北方向延伸到爱达荷州和蒙大拿州，面积达7988平方公里。这片地区原本是印地安人的圣地，但因美国探险家路易斯与克拉克的发掘，而成为世界上最早的国家公园。</div>
        </div>
        <div class="pgContentJs2">
           <div class="pgContentJsPic"><a href="'.HTTP_SERVER.'/hawaii-tours/packages/"><img src="imgs/style1_pic2.jpg" /></a></div>
           <div class="pgContentJsTxt">夏威夷，是夏威夷群岛中最大的岛屿，地处热带，气候却温和宜人，是世界上旅游工业最发达的地方之一，拥有得天独厚的美丽环境，风光明媚，海滩迷人。</div>
        </div>
        <div class="pgContentJs2">
           <div class="pgContentJsPic"><a href="'.HTTP_SERVER.'/las-vegas-tours/packages/"><img src="imgs/style1_pic3.jpg" /></a></div>
           <div class="pgContentJsTxt">拉斯维加斯（Las Vegas） 是美国内华达州的最大城市，以赌博业为中心的庞大的旅游、购物、度假产业而著名，是世界知名的度假圣地之一。从一个巨型游乐场到一个真正有血有肉、活色生香的城市，拉斯维加斯在10年间脱胎换骨。每年来拉斯维加斯旅游的3890万旅客中，来购物和享受美食的占了大多数，专程来赌博的只占少数。</div>
        </div>
      </div>
     </div>
    <div style="clear:both"></div>
    </div>'),
  //---------------------------------------------------------------------------------------------------------------------------------------  
  	'australia'=>array('澳大利亚签证','<div class="faqs_main">
					<h4><span class="title_customer">1、澳大利亚使馆领区划分： </span></h4>
                    <p>&middot;北京领区: 北京、黑龙江、吉林、辽宁、天津、山东、河北、内蒙古、陕西、河南、宁夏、山西、四川、重庆、甘肃、青海、新疆、西藏 <br />
  &middot;上海领区: 上海、江苏、浙江、安徽、江西、湖北<br />
  &middot;广州领区：广东、福建、湖南、广西、云南、贵州、海南 
</p>
                   <h4><span class="title_customer">2、所需的资料如下:</span></h4>
		            <p> &middot;因私护照原件（护照在签证日期截止后应有6个月有效期，护照内应有两张相邻的空白页)；<br />
   &middot;六张2寸白底彩照（与护照照片同底最好）<br />
   &middot;身份证及户口全家页复印件（父母、夫妻、子女全家页逐页复印）<br />
   &middot;工作单位营业执照或机构代码证复印件加盖公章(已年审）<br />
   &middot;单位抬头信笺纸4张加盖公章（有单位地址、电话、负责人签章，信笺抬头与营业执照或机构代码证上单位名称一致，电话一定要真实，使<br />&nbsp;&nbsp;馆会打电话核实）<br />
   &middot;资金证明：（1）人民币五万元以上的资金证明信原件、定期存单复印件、活期存折往来账复印件一并提供（资金越多越好）（2）房产证复<br />&nbsp;&nbsp;印件（有汽车行驶证、股票交割单等经济证明请尽量提供）<br />
   &middot;结婚证复印件<br />
   &middot;退休人员请提供退休证原件，以上（4）、（5）项不必提供10.18岁一下小孩随父母出游，请提供亲属关系公证（我社可协助办理）如果您<br />&nbsp;&nbsp;想办理个人旅游、 探亲访友和商务签证，我们也可以办理，所提供的资料和ADS签证资料基本一样，但是需要提供您在当地的住宿地址、接<br />&nbsp;&nbsp;待人情况、当地公司情况等资料。<br />
</p>		           
		            <h4><a href="http://www.china.embassy.gov.au/bjngchinese/home.html" style="color:#00388A"><span class="title_customer"  style="color:#00388A">点击进入澳大利亚领事馆官网</span></a></h4>
		  <p style="margin:0;">(所有签证信息以澳领馆官网解释为准，走四方仅提供参考，不承担任何责任。)</p>
         </div>  '),
  //----------------------------------------------------------------------------------------------------------------------------------------------
  'canada'=>array('加拿大签证',
  '<div class="faqs_main">
					<h4><a id="doe1" name="doe1"><span class="title_customer">1、签证申请中心:</span></a></h4>
                    <p>北京，上海，广州，重庆</p>
                   <h4><a id="hli2" name="hli2"><span class="title_customer">2、所需材料：</span></a></h4>
		            <p> &middot;填写完整并签名的签证申请表格<br />
   &middot;正确的签证申请以及签证费用<br />
   &middot;有足够空白页（双面）的6个月有效护照<br />
   &middot;护照复印件<br />
   &middot;旧护照（如有）<br />
   &middot;照片要求细则--两张近照（边框大小至少是35毫米x45 毫米，且拍摄于六个月之内，白色背景为宜）<br />
   &middot;其他签证所需材料，详见签证类型与清单<br />
   &middot;签证信息

</p>		            <h4><a id="wmp3" name="wmp3"><span class="title_customer">3、关于面谈</span></a></h4>
		            <p>加拿大使馆会抽查一定人数赴使馆面谈，如被抽中，需积极配合使馆要求，按时赴加拿大使馆面谈。<br />    因提供虚假或伪造资料而导致的拒签，根据加拿大法律，申请人两年内不得再次申请赴加进行任何活动。因资料不齐备或不充分而导致的拒签，可补充资料后随时再次申请签证。

</p>
		            <h4><a href="http://www.vfs-canada.com.cn/chinese/index.aspx" style="color:#00388A"><span class="title_customer"  style="color:#00388A">点击进入驻华加拿大签证申请中心官网</span></a></h4>
		  <p style="margin:0;">(所有签证信息以加领馆官网解释为准，走四方仅提供参考，不承担任何责任。)</p>
                    <h4><a id="wmp3" name="wmp3"><span class="title_customer">4、在美居住的中国公民申请加拿大签证所需材料：</span></a></h4>
		            <p>&middot;有效护照（请务必确保您证件的有效期在出行期间是在六个月以上）。 <br />
&middot;近6个月之内的两张签证照片。请在其中的一张背面写上姓名、出生日期，尺寸：35 mm x 45 mm (1 3/8″ x 1 3/4″)<br />
&middot;充分证据用以证明：您有工作、家庭或其他纽带的关系， 这样您在行程结束后一定会离开加拿大并返回原出发国；<br />
&middot;具体旅游行程<br />
&middot;财力证明（如银行存款证明，直系家属材力证明等）<br />
&middot;申请表格（必须以英文或法文填写）
</p>
		            <h4><a href="http://www.cic.gc.ca/english/visit/apply-how.asp" style="color:#00388A"><span class="title_customer"  style="color:#00388A">点击进入驻美加拿大领馆官网站</span></a></h4>
		  <p style="margin:0;">(所有签证信息以加领馆官网解释为准，走四方仅提供参考，不承担任何责任。)</p>
         </div>  
  '),
  //----------------------------------------------------------------------------------------------------------------------------------------------
  'uk'=>array('英国签证'
  ,'<div class="faqs_main">
					<h4><a id="doe1" name="doe1"><span class="title_customer">1、签证申请中心: </span></a></h4>
                    <p>北京,上海,沈阳,武汉,重庆,成都,杭州,南京,广州,深圳,福州</p>
                   <h4><a id="hli2" name="hli2"><span class="title_customer">2、所需材料：</span></a></h4>
		            <p> &middot;填写完整并亲笔签名的签证申请表(全英文填写.可以在线填写并打印出来,也可以在打印出来的空白表格上手写,日期需填递交材料当天日<br />&nbsp;&nbsp;期)<br />
&middot;一张最近的,护照照片尺寸(45mm高X 35mm宽)的彩照,浅灰色或白色背景<br />
&middot;护照原件及首页复印件,护照至少有正反连续两页空白页,最后一页要有本人亲笔签名<br />
&middot;在职和请假证明<br />
&middot;户口本<br />
&middot;至少6个月以上的资金来源证明<br />
&middot;银行存款原件及存款证明<br />
&middot;其他财产证明<br />
&middot;旅行日程/预定情况(官方建议不要购买机票,但是需要所去地点及酒店预定的确认,以及费用收据)<br />
&middot;以往旅行记录的旧护照(如有)</p>		           
                   <h4><a id="wmp3" name="wmp3"><span class="title_customer">3、关于面谈</span></a></h4>
		            <p>只需递交材料,但有可能会被要求面谈<br />
    不需要预约,根据先来先服务的原则接受申请.所有申请人必须亲自前往签证申请中心递交生物数据(10指数码指纹采集及数码照相)
</p>
                    <h4><a id="wmp3" name="wmp3"><span class="title_customer">4、在美国申请英国签证</span></a></h4>
		            <p>必须在网上提交申请表格。<br />
   无需签证：美国、加拿大、澳大利亚、新西兰、新加坡、日本、马来西亚、香港特区SAR、英国海外BNO护照、台湾护照

</p>
		            <h4><a href="http://www.visa4uk.fco.gov.uk/" style="color:#00388A"><span class="title_customer"  style="color:#00388A">点击进入英国签证中心官网</span></a></h4>
		  <p style="margin:0;">(所有签证信息以英领馆官网解释为准，走四方仅提供参考，不承担任何责任。)</p>
         </div>  '),
  //----------------------------------------------------------------------------------------------------------------------------------------------
  'usa'=>array('美国签证',
  ' <div class="faqs_main">
					<h4><a id="doe1" name="doe1"><span class="title_customer">1、关于美国领区的划分，请选择正确的领区，以利于您申请签证</span></a></h4>
					<p>美国领事馆辖区<br />
     &middot;北京大使馆：北京市，天津市，甘肃省，河北省，河南省，湖北省，湖南省，内蒙古自治区，江西省，宁夏回族自治区，山东省，山西省，<br />&nbsp;&nbsp;陕西省，青海省和新疆维吾尔自治区。<br />
     &middot;成都领事馆：四川，重庆，云南，贵州，西藏<br />
     &middot;广州领事馆：福建省，广东省，广西壮族自治区和海南省。<br />
     &middot;上海领事馆：上海市，安徽省，江苏省和浙江省。<br />
     &middot;沈阳领事馆：黑龙江省，吉林省和辽宁省。<br />
     &middot;香港领事馆：香港和澳门地区。<br />
中国公民应该到管辖其居住地的美国大使馆或领事馆递交申请，不论其居住地是否与户口一致；只有到适当的使领馆申请，得到签证的机率才会最大。在暂住地不间断居住半年以上人员，可在暂住地领馆申请，但需提供相关证明。

</p>					
                   <h4><a id="hli2" name="hli2"><span class="title_customer">2、准备签证申请所需材料：</span></a></h4>
		            <p> &middot;在线填写非移民电子签证申请表（DS-160），填表时上传51mmX51mm的数码照片。携带申请确认件（含照片&amp;条形码）<br/>
      &middot;于6个月内拍摄的2英寸x2英寸（51毫米x51毫米）正方形白色背景的彩色正面照一张。<br/>
&middot;申请费收据。<br/>
      &middot;有效护照。护照有效期必须比你计划在美停留时间至少长出六个月。<br/>
      &middot;含有以前赴美签证的护照，包括已失效的护照。
</p>		            <h4><a id="wmp3" name="wmp3"><span class="title_customer">3、签证面谈程序</span></a></h4>
		            <p>&middot;按照预约时间，提前1小时到达领事馆，核实签证资料并准备好身份证、护照原件、预约号码<br/>
  &middot;排队等候，听从使馆工作人员安排，服从武警指挥<br/>
  &middot;进入领事馆内等候区，注意显示屏叫号<br/>
  &middot;领事馆官员将审查申请人的材料<br/>
  &middot;用无墨扫描器提取申请人的指纹（只有符合外交或公务签证类别的申请人、未满14周岁或已满80周岁的申请人以及某些急需赴美接受紧急医<br />&nbsp;&nbsp;药治疗的申请人可以免留指纹）<br/>
  &middot;询问一些问题，您需根据签证资料如实回答<br/>
  &middot;仪表端庄，整洁，使用普通话<br/>
  &middot;领取签证。通常情况下可在面谈后的当天下午或次日领取护照和签证，但是不能完全保证如此。有时由于技术上的原因，会导致推迟发放申请人的护照和签证。护照领取通知单上有明确的领取时间。
</p>
		            <h4><a href="http://chinese.usembassy-china.org.cn/" style="color:#00388A"><span class="title_customer"  style="color:#00388A">点击进入驻华美国使馆中文官网</span></a></h4>

		          
		  <p>(所有签证信息以美领馆官网解释为准，走四方仅提供参考，不承担任何责任。)</p>
         </div>  '),
  //----------------------------------------------------------------------------------------------------------------------------------------------
  'schengen'=>array('申根签证',
  '<div class="faqs_main">
                        <p>申根签证，又称欧洲短期签证，在一个或多个欧盟国共停留90天以内的旅游、探亲访友。<br />
                          欧盟国包括： Austria, Belgium, the Czech Republic, Denmark, Estonia, Finland, France, Germany, Greece, Hungary, Iceland, Italy, Latvia, Lithuania, Luxembourg, Malta, Netherlands, Norway, Poland, Portugal, Slovakia, Slovenia, Spain, Sweden, Switzerland.</p>
                        <h4><span class="title_customer">1、申根签证申请国家： </span></h4>
                        <p>向此次旅行地停留日最多的国家申请，如在两个国家停留日同等，则需向这两个国家中先进入的国家申请。无需签证：美国、加拿大、澳大利亚、新西兰、新加坡、日本、马来西亚、香港特区SAR护照、澳门特区护照、英国海外BNO护照。</p>
                        <h4><span class="title_customer">2、在中国申请申根签证</span></h4>
                        <p> <b>A.法国领馆申请中心：</b> 北京、上海、广州、武汉、成都、沈阳、香港<br />
                          <b>所需材料(需准备两套，一套为原件，另一套为A4复印件):</b><br />
                          &middot;短期签证申请表<br />
                          &middot;2张近期白底免冠护照相片35mm*45mm<br />
                          &middot;往返机票订票单<br />
                          &middot;与在申根国家停留时间相符的包括医疗及因故被接回国保险金额为3万欧元即30万元人民币出国人员境外医疗保险单<br />
                          &middot;护照(10年有效期,至少在离开申根国家后仍有3个月有效期)(复印件需护照前5页及有章和签证页复印件)<br />
                          &middot;由工作单位出具的包括申请者姓名、职务、在国外旅游时间的证明<br />
                          &middot;户口本原件(复印件需户口本的所有页)<br />
                        &middot;在申根国家居住和接待证明</p>
                        <h4 style="margin:0"><a href="http://www.ambafrance-cn.org/主页.html?lang=zh" style="color:#00388A"><span class="title_customer"  style="color:#00388A">点击进入驻华法国签证申请中心官网</span></a></h4>
                        <p style="margin:0">(所有签证信息以法领馆官网解释为准，走四方仅提供参考，不承担任何责任。)</p><p>&nbsp;</p>
                        <p><b>B. 意大利领馆签证申请中心：</b>北京,上海,广州<br />
                          <b>所需材料:</b><br />
                          &middot;申根签证申请表<br />
                          &middot;1张近期护照尺寸，背景清晰的照片<br />
                          &middot;签证到期后仍有3个月有效期的护照(护照原件、护照首页2张复印件及以往签证复印件)<br />
                          &middot;往返机票预订单或机票<br />
                          &middot;在申根国家有效的境外医疗保险,最低保额3万欧元(原件及复印件)<br />
                          &middot;宾馆确认单或者意大利担保人的担保信(原件)<br />
                        &middot;信用卡/旅行支票(原件及复印件)/现金(每人每天至少30欧元,面试时出示原件)/银行账户对账单(最近和最新的,原件和复印件)/意大利担保                         
                        <br /> &nbsp;&nbsp;人的银行担保信(原件及复印件)<br />
&middot;具体旅行计划(英文)<br />
&middot;反映社会职业地位的材料，如户口本、银行存折、房产证和汽车证(如有)、教育背景材料、结婚证等(原件及复印件)，最好提供公司的准假<br /> &nbsp;&nbsp;证明，此信必须提供原件，用英文或意大利文在公司抬头纸上开具，盖公章并由负责人签名，需注明公司地址、电话及传真号码，并写明申<br /> &nbsp;&nbsp;请人职位、薪水和在意大利或者申根 地区停留的时间，担保其返回中国<br />
                        
                        <p>
                        <h4 style="margin:0"><a href="http://www.italyvac.cn/" style="color:#00388A"><span class="title_customer"  style="color:#00388A">点击进入驻华意大利签证申请中心官网</span></a></h4>
                        <p style="margin:0">(所有签证信息以意领馆官网解释为准，走四方仅提供参考，不承担任何责任。)</p><p>&nbsp;</p>
                        <p><b>C.德国领馆签证申请中心：</b>北京、上海、广州、成都、香港<br />
                          <b>所需材料(所有材料均需一份原件及一份复印件):</b><br />
                          &middot;2份用电脑填写完整并签名的在线申根签证申请表,附3张白底近期证件照(2张贴于申请表照片栏)，不接受手填申请表<br />
                          &middot;亲笔签名的护照(签证到期后有效期不少于90天)，护照必须至少尚有2张空白页，及标有个人信息的护照首页复印件1份<br />
                          &middot;个人旅游行程单、机票订单、旅馆订单、参团旅游需提供旅行社开具且签名的预定证明，写明日程安排、往返时间、当地交通、食宿<br />
                          &middot;工作单位出具的写明申请人职位、工资、休假天数、保留职位的准假证明原件，证明中需列出单位地址、电话和传真号码、加盖公司印章，<br /> &nbsp;&nbsp;签名并写明签名人姓名和职务。退休人员提交退休证原件.<br />
                          &middot;旅费(旅行和拘留费用)证明；最近3个月的工资单或银行工资卡对账单等收入证明(新存入的定期银行存款不能作为证明)<br />
                          &middot;入境后医疗保险证明原件，保险金额不低于3万欧元<br />
                          &middot;户口簿原件及所有页码和加页的复印件<br />
                        </p>
                        <h4 style="margin:0"><a href="http://www.shanghai.diplo.de/Vertretung/shanghai/zh/Startseite.html" style="color:#00388A"><span class="title_customer"  style="color:#00388A">点击进入驻华德国签证申请中心官网</span></a></h4>
                        <p style="margin:0">(所有签证信息以德领馆官网解释为准，走四方仅提供参考，不承担任何责任。)</p>
                        <h4><span class="title_customer">3、在美国申请申根签证：</span></h4>
                        <p><b>A.法国领馆申请中心：</b>Atlanta, Boston, Chicago, Honolulu, Houston, Los Angles, Miami, New Orleans, New York, San Francisco, Washington D.C. <br />
                          <b>关于面谈：</b>申请人必需前往居住地所属的法国领事馆面谈。面谈需事先网上预约<br />
                          <b>所需材料(需准备两套,一套为原件,另一套为复印件):</b><br />
                          &middot;申请表及 2" x 2" 近期正面照2张(护照照片要求)<br />
                          &middot;护照(最近十年内签发,至少在离开申根国家后仍有三个月有效期，至少有二页空白页)<br />
                          &middot;有效绿卡，或I-551，或有效返美签证及有效I-94(如F1需提供有效I-20)，或Advanced parole, 以上证件必须在签证到期后3个月内有效。<br /> &nbsp;&nbsp;(不接受在美持有B1/B2签证者的申请）<br />
                          &middot;往返机票订票单?4 每日酒店预订确认单，每日参团行程及酒店voucher<br />
                          &middot;财力证明(需证明在旅游期间，有至少每天$100的财力)。包括:近3个月银行详细清单,近3个月的工资单,雇主证明，注明年薪、被批准的具<br /> &nbsp;&nbsp;体休假日期，并证明休假期后的持续雇佣<br />
                          &middot;如果财力不足，可提资助人的资助信笔信?6 保险公司证明信，证明在您在法国旅游的时间内,受保meidcal, hospitalization, <br /> &nbsp;&nbsp;repatriation至少$45,000。<br />
                        <h4 style="margin:0"><a href="http://www.france-consulat.org/" style="color:#00388A"><span class="title_customer"  style="color:#00388A">点击进入法国领事馆官网</span></a></h4>
                        <p>(所有签证信息以法领馆官网解释为准，走四方仅提供参考，不承担任何责任。)</p><p>&nbsp;</p>
                        <p><b>B.德国领馆申请中心：</b>Atlanta, Chicago, Houston, Los Angeles, Miami, New York, San Francisco, Washington D.C.<br />
                          <b>关于面谈：</b>申请人必需前往居住地所属的德国领事馆面谈。<br />
                          <b>所需材料(需准备两套，一套为原件，另一套为复印件):</b><br />
                        &middot;申请表格</p>
                        <p style="margin:0"><a href="http://www.germany.info/Vertretung/usa/en/04__Legal/02__Directory__Services/01__Visa/__Visa__Form__Instructions.html" style="color:#00388A"><span  style="color:#00388A">http://www.germany.info/Vertretung/usa/en/04__Legal/02__Directory__Services/01__Visa/__Visa__Form__Instructions.html</span></a></p>
                        <p>&middot;2张近期白底免冠护照相片35mm*45mm<br />
                          &middot;护照(最近10年内签发，至少在离开申根国家后仍有3个月有效期，至少有2页空白页)<br />
                          &middot;有效绿卡，有效在美居住证，或有效返美签证及有效I-94(A,E,F需提供有效I-20；G,H,I,J需提供J1; L, O,R) 以上证件必须在签证到期后<br /> &nbsp;&nbsp;3个月内有效。<br />
                          &middot;最近两个月银行证明<br />
                          &middot;保险公司证明信，证明在在德国旅游的时间内，受保meidcal, hospitalization, repatriation至少30,000欧($50,000)。<br />
                          &middot;现任雇主证明信<br />
                          &middot;团体旅行行程安排、酒店预订、机票订位单<br />
                        </p>
                        <h4 style="margin:0"><a href="http://www.germany.info/Vertretung/usa/en/04__Legal/01__Consulate__Finder/List/__Consular__Districts.html" style="color:#00388A"><span class="title_customer"  style="color:#00388A">点击进入德国领事馆官网</span></a></h4>
                        <p>(所有签证信息以德领馆官网解释为准，走四方仅提供参考，不承担任何责任。)</p><p>&nbsp;</p>
                        <p><b>C.意大利领馆申请中心：</b>Boston, Chicago, Detroit, Philadelphia, Houston, Los Angeles, Newark, New York, San Francisco<br />
                          <b>关于面谈：</b>申请人必需前往居住地所属的意大利领事馆或签证中心面谈。<br />
                          <b>所需材料(需准备两套，一套为原件，另一套为复印件):</b><br />
                          &middot;申请表格<br />
                          &middot;1 张护照正面近照 (3.5 cm x 4.5 cm）<br />
                          &middot;护照(最近10年内签发，至少在离开申根国家后仍有3个月有效期，至少有2页空白页)有效绿卡，有效在美居住证，或有效返美签证及有效I-94(A,E,F需提供有效I-204. Return-3 往返机票订票单<br />
                          &middot;每日酒店预订确认单，每日参团行程及酒店voucher<br />
                          &middot;财力证明(需证明在旅游期间，有至少每天$100的财力)。包括: 近3个月银行详细清单, 近3个月的工资单, 雇主证明，注明年薪、被批准的具体休假日期，并证明休假期后的持续雇佣. 
                        </p>
                        <h4 style="margin:0"><a href="http://www.consnewyork.esteri.it/NR/exeres/2F299E82-6707-4A06-B866-7DE55F3E6950,frameless.htm?NRMODE=Published" style="color:#00388A"><span class="title_customer"  style="color:#00388A">点击进入意大利领事馆官网</span></a></h4>
                        <p>(所有签证信息以意领馆官网解释为准，走四方仅提供参考，不承担任何责任。) </p>
                        </p>
                      </div>'),
  //----------------------------------------------------------------------------------------------------------------------------------------------
  'tips'=>array('出境贴士',
  '<div class="faqs_main">
					<h4><span class="title_customer">1、证件：</span></h4>
                    <p>取得签证时，仔细核对护照与签证信息是否一致性。旅行时，必须随身携带妥善保管有效旅行证件原件（护照，签证居留证）。</p>
                   <h4><span class="title_customer">2、行李：</span></h4>
		            <p>最好直接问承担航程的航空公司，因为超重的行李各航空公司有严格的收费标准。一般大件行李不能超过50磅（22kg），每人限带2件。随身小件1件及电脑包1件。液体行李最好随大件托运。随身带的不超过100ml，最好集中在一个可封口的透明袋。<br />
 不要将行李上锁，不要携带入境国禁止物品，包括鲜果等易腐败食品入境。

</p>		           
<h4><span class="title_customer">3、入境：</span></h4>
                    <p>填写入境登记卡和海关申报单。</p>
                   <h4><span class="title_customer">4、接机：</span></h4>
		            <p>一般在行李提取处等候接机人员来接机（或按电子旅行凭证上所示），如还找不到则打紧急联络电话。</p>	
                    <h4><span class="title_customer">5、酒店：</span></h4>
                    <p>入住酒店时，请向酒店索要酒店标识卡备用；国外酒店一般不提供牙膏牙刷拖鞋等个人用品。</p>
                   <h4><span class="title_customer">6、电器：</span></h4>
		            <p>带好转换插头，美加是110v，是双直孔插头。意大利是127v，欧洲大部分是220v，但是双圆孔插头。</p>	
                    <h4><span class="title_customer">7、电话：</span></h4>
		            <p>国内游客，出发前，最好开通手机国际漫游，以备紧急联络时使用。酒店房内电话很贵，最好使用大堂或公用电话相对合算。</p>	
                    <h4><span class="title_customer">8、小费：</span></h4>
                    <p>出游西方国家，对提供服务的人支付小费是一种习惯和礼貌， 走四方网友情提议入乡随俗。美加餐馆一般15%，欧洲 10% ；乘出租车或酒店免费shuttle bus要给司机小费；酒店服务生提携行李或需整理房间服务，都可留下小费。</p>
                   <h4><span class="title_customer">9、天气：</span></h4>
		            <p>出游前事先在<a href="http://www.weather.com" style="color:#00388A">www.weather.com</a>查询当地天气，欧洲夏季气候多变，美国加州海风大，请自备雨具及长袖衣装。</p>
                    <h4><span class="title_customer">10、保险：</span></h4>
                    <p>旅游团均有当地政府规定的巴士营运保险。为您的行程保驾护航，建议购买个人旅游及医疗保险。</p>
                   <h4><span class="title_customer">11、习惯：</span></h4>
		            <p>向导游问清集合的时间及地点，不要随意迟到。<br />
 夜间不要一个人随意行走，不要随身携带过多现金。<br />
 国外的禁烟措施比国内严格，建筑物内，餐厅内都是禁烟的。即使室外，也应尽量避免在儿童较多的场合吸烟（如迪斯尼）。<br />
 餐厅就餐时，在门口等待领位入座，避免自己直入找位的习惯。 
</p>			           
		            
         </div>  '
  ),
  //----------------------------------------------------------------------------------------------------------------------------------------------
  'qa'=>array('出国签证问与答'
  ,' <div class="faqs_main">
					<div class="faq_q_list"><ul>
                             <li><a href="'.$pageSelf.'#doe1">1、签证是如何分类的？ </a></li>
							 <li><a href="'.$pageSelf.'#hli2">2、签证与护照有什么区别？</a></li>
							 <li><a href="'.$pageSelf.'#wmp3">3、签证上的信息包含哪些？</a></li>
							 <li><a href="'.$pageSelf.'#cip4">4、申办外国签证一般需要多长时间？</a></li>
							 <li><a href="'.$pageSelf.'#ica5">5、按领事辖区范围申办签证是什么意思？</a></li>
							 <li><a href="'.$pageSelf.'#wpr6">6、什么是返签证明？</a></li>
							 <li><a href="'.$pageSelf.'#wpr7">7、使领馆拒绝发给签证怎么办？</a></li>
							 <li><a href="'.$pageSelf.'#wpr8">8、签证费和签证受理费是一回事吗？</a></li>
                             <li><a href="'.$pageSelf.'#wpr9">9、如果发现签证上的信息有误怎么办？</a></li>
                             <li><a href="'.$pageSelf.'#wpr10">10、申请人尚未离境签证就过期了，怎么办？</a></li></ul></div>

							
					<h4><a id="doe1" name="doe1"><span class="title_customer">1、签证是如何分类的？ </span></a><a class="sp1 font_weight_normal" href="javascript:scroll(0,0);">  TOP</a></h4>
					<p>根据签证类别可分为：外交签证、公务签证和普通签证。 <br />
根据出入境事由可分为：移民签证、非移民签证、留学签证、旅游签证、工作签证、商务签证和家属签证等。<br />
根据出入境情况可分为：出境签证、入境签证和过境签证等。<br />
根据停留次数可分为：一次签证、两次签证和多次签证。<br />
根据停留期长短可分为：长期签证、短期签证。在往访国停留时间超过3个月的签证被称作长期签证，停留期不足3个月的签证称为短期签证。
</p>					
                   <h4><a id="hli2" name="hli2"><span class="title_customer"> 2、签证与护照有什么区别？</span></a><a class="sp1 font_weight_normal" href="javascript:scroll(0,0);">  TOP</a></h4>
		            <p>护照是持有人国籍和身份的证明，签证是往访国家对持照人入出其国境的许可证明。进行国际间旅行，通常需要同时持有效护照和签证。</p>		            <h4><a id="wmp3" name="wmp3"><span class="title_customer"> 3、签证上的信息包含哪些？</span></a><a class="sp1 font_weight_normal" href="javascript:scroll(0,0);">  TOP</a></h4>
		            <p>各国签证内容不同，风格各异，但签证上所列信息内容基本一致。签证上一般都注明：签证的种类、签证代号、入出境(过境)目的、停留期限、有效次数、签发机构、签发地点、签证官员签署、印章、签发日期和签证费用等。领取签证后，应仔细检查，避免信息失误，影响行程。</p>
		            <h4><a id="cip4" name="cip4"><span class="title_customer"> 4、申办外国签证一般需要多长时间？</span></a><a class="sp1 font_weight_normal" href="javascript:scroll(0,0);">  TOP</a></h4>

		            <p>各国驻华使（领）馆虽然都被授权向申请赴本国旅行或移居的外国人颁发签证，但由于各国使（领）馆的权限不一，受理和审批签证的程序各异，所以从受理申请到正式颁发所需时间不尽相同。具体情况请向往访国驻华使领馆咨询。</p>		            
                    <h4><a id="ica5" name="ica5"><span class="title_customer"> 5、按领事辖区范围申办签证是什么意思？</span></a><a class="sp1 font_weight_normal" href="javascript:scroll(0,0);">  TOP</a></h4>
		            <p>领事辖区指为领事馆执行职权而设定的区域。每个领事机构都有自己的辖区，且只能在所辖区域内行使职权。国与国之间通过领事条约（协定）确定每个领事机构的辖区。</p>
                    <p>目前，外国在华设立的领事机构有137个。中国公民申请外国签证时，首先确定往访国驻华领事机构辖区是否包括本地区。如果是，则应前往该机构办理。比如：美国驻沈阳总领事馆的辖区为黑龙江省、吉林省和辽宁省。这三个省的中国公民申请美国签证时，应该到美国驻沈阳总领事馆办理。</p>	
                    <h4><a id="wpr6" name="wpr6"><span class="title_customer"> 6、什么是返签证明？</span></a><a class="sp1 font_weight_normal" href="javascript:scroll(0,0);">  TOP</a></h4>
		            <p>返签证明是申请人前往国家（地区）的移民机关或出入境管理机关出具的同意发给该申请人签证的一种证明。持有这种返签证明后，一般还必须到该国驻外国使领馆办理签证。也有国家规定，持返签证明的申请人必须同时持本人有效护照，在抵达该国口岸时办理签证。</p>		                    <h4><a id="wpr7" name="wpr7"><span class="title_customer">7、使领馆拒绝发给签证怎么办？</span></a><a class="sp1 font_weight_normal" href="javascript:scroll(0,0);">  TOP</a></h4>

		            <p>颁发签证是一个国家主权范围内的事务，主权国家有权自行决定是否给其他国家公民颁发本国签证，且无 需说明理由。因此，公民申请外国签证被拒属于正常情况。与签证官吵闹、纠缠或冲突无助于问题的解决。应沉着冷静地向签证机关了解原因，说明情况，尽快补齐 所需材料，再次申办。</p>          
                    <h4><a id="wpr8" name="wpr8"><span class="title_customer">8、签证费和签证受理费是一回事吗？</span></a><a class="sp1 font_weight_normal" href="javascript:scroll(0,0);">  TOP</a></h4>
		  <p>签证费和签证受理费是两个概念。签证费指为办理签证所支付的费用，一般在办妥签证后收取。如遭拒签， 则不必交纳。签证受理费是为审核签证材料支付的受理费用，一般签证申请被受理时，无论最终是否获签，该费用均不退还。各国自主决定如何收取签证费用。通 常，各国根据对等原则确定收费标准。</p>
          <h4><a id="wpr9" name="wpr9"><span class="title_customer">9、如果发现签证上的信息有误怎么办？</span></a><a class="sp1 font_weight_normal" href="javascript:scroll(0,0);">  TOP</a></h4>
		  <p>申请人领取签证后，应逐项核对签证上的内容。如果发现信息有误，应立即向颁发签证的部门提出并要求更正。只要在签证有效期内和出国境之前，申请人均可提出更正要求。</p>
          <h4><a id="wpr10" name="wpr10"><span class="title_customer">10、申请人尚未离境签证就过期了，怎么办？</span></a><a class="sp1 font_weight_normal" href="javascript:scroll(0,0);">  TOP</a></h4>
		  <p>申请人获得签证后，应抓紧在签证有效期之内办理离境手续。如果因特殊原因不能在签证有效期内抵达目的地，应持有关材料到外国驻华使（领）馆重新申请签证。</p></div>  ')  
  ); 
  

  if($pageId !='index'){
	  $pages[$pageId][1] = '<div id="footpage"><table width="99%" cellspacing="0" cellpadding="0" border="0">
    
  <tbody><tr>
    <td width="100%" class="pageHeading">'.$pages[$pageId][0].'</td><td nowrap="nowrap" align="right" class="pageHeading">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" class="mainbodybackground">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tbody><tr><td height="15"></td></tr>
			     <tr>
					 <td width="10%"></td>
					 <td width="80%" class="main">'.$pages[$pageId][1].'</td><td width="10%"></td></tr><tr><td height="30"></td></tr></tbody></table></td></tr></tbody></table></div>';
  }


  $HEADING_TITLE = db_to_html($pages[$pageId][0]);
 	 $HTML_CONTENT =db_to_html($pages[$pageId][1]);
 	  	//seo信息
	$the_title = db_to_html($pages[$pageId][0].'-走四方旅游网');
	$the_desc = db_to_html('　');
	$the_key_words = db_to_html('　');
	//seo信息 end
 
  
  $add_div_footpage_obj = true;
  $content = 'visa-help';
  
  
 
$BreadOff=true;
  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');
?>
