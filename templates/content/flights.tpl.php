
<script type="text/javascript" src="<?php echo DIR_WS_JAVASCRIPT,"calendar_flight"?>.js" charset="utf-8"></script>
<div id="in_left">

<?php

//搜索框
include(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/boxes1/' .'search.php');

 ob_start(); ?>		</div><div class="in_right">
			<ul class="chooseTab">
        		<li style="width:auto; margin-right:5px;" class="selected" id="l_tech2"><a id="h_tech2" href="#">往返机票</a><span></span></li>
                 <li style="width:auto; margin-right:5px;" id="l_study"><a id="h_study" href="#">单程机票</a><span></span></li>
					<li style="width:auto; margin-right:5px;" id="l_tech"><a id="h_tech" href="#">多个城市联程</a><span></span></li>
			</ul>
	<div class="" id="airlines">
			<form action="" method="post" name="flights_form">
				<p class="blod">请输入您的旅程信息：</p>
				<!-- 往返机票 -->
				<div class="J-show">
				</div>
				<div class="J-item">
					<div>
					<p>
						<span class="red pdr5">*</span>飞离城市：<input type="text" name="from_city[]" class="input mr45" /><span class="red pdr5">*</span>飞抵城市：<input type="text" class="input" name="to_city[]"/></p>
					<p>
						<span class="red pdr5">*</span>出发日期：<input type="text" readonly="" name="go_time[]" id="formDate" value="" class="date" />
						<input type="checkbox" class="chkbox J-dateCanModify" name="go_time_change[]"/>日期可机动
						<span class="J-jidong pdr5 hide">
							<input type="checkbox" class="chkbox" name="b_e_g_b[]" value="1"/>前
							<input type="checkbox" class="chkbox" name="b_e_g_a[]" value="1"/>后
							<select name="go_days[]">
								<option value="1">1天</option>
								<option value="2">2天</option>
								<option value="3">3天</option>
								<option value="4">4天</option>
								<option value="5">5天</option>
								<option value="6">6天</option>
								<option value="7">7天</option>
							</select>
						</span>
						<span class="del delete-airlines hide"></span>
					</p>
					<p id="box2" class="J-return">
						<span class="red pdr5">*</span>返回日期：<input type="text" readonly="" name="back_time[]" id="formDate2" class="date" />
						<input type="checkbox" class="chkbox J-dateCanModify" name="back_time_change[]"/>日期可机动
						<span class="J-jidong pdr5 hide">
							<input type="checkbox" class="chkbox" name="b_e_b_b[]" value="1"/>前
							<input type="checkbox" class="chkbox" name="b_e_b_a[]" value="1" />后
							<select name="back_day[]">
								<option value="1">1天</option>
								<option value="2">2天</option>
								<option value="3">3天</option>
								<option value="4">4天</option>
								<option value="5">5天</option>
								<option value="6">6天</option>
								<option value="7">7天</option>
							</select>
						</span>
					</p>
					</div>
				</div>
				<p class="pdl45 hide"><span class="del add-airlines"></span></p>
				<!-- 以下是共用信息 -->
				<p>
					<span class="red h75 iblock pdr5">*</span>
					<span class="blod h75 iblock pdr5">请选择航空公司：</span>
					<select name="flights_company[]" size="4" multiple="multiple" class="selectlist">
						<option value="均可" selected="">均可</option>
						<option value="American Airlines">American Airlines</option>
						<option value="Continental Airlines">Continental Airlines</option>
						<option value="Delta Airlines">Delta Airlines</option>
						<option value="United Airlines">United Airlines</option>
						<option value="Asiana Airlines">Asiana Airlines</option>
						<option value="DKorean Air">Korean Air</option>
						<option value="Air China">Air China</option>
						<option value="China Eastern">China Eastern</option>
						<option value="Cathy Pacific Airways">Cathy Pacific Airways</option>
						<option value="Eva Airways">Eva Airways</option>
						<option value="China Airlines">China Airlines</option>
						<option value="Hainan Airlines">Hainan Airlines</option>
					</select>
					<span class="red pdr5">提示：按Ctrl键可以多重选择</span>
				</p>
				<p>
					<span class="red pdr5">*</span>
					<span class="blod pdr5">请选择机舱：</span>
					<select name="cabin">
						<option value="经济舱" selected="selected">经济舱</option>
						<option value="商务舱">商务舱</option>
						<option value="头等舱">头等舱</option>
					</select>
				</p>
				<p class="ie6">
					<span class="red pdr5">*</span>
					<span class="blod pdr5">请选择飞行方式：</span>
					<input type="checkbox" id="chk21" name="zhifei"/><label for="chk21">直飞</label>
					<input type="checkbox" id="chk22" name="yici"/><label for="chk22">可有一次转机</label>
					<input type="checkbox" id="chk23" name="moretimes"/><label for="chk23">可有一次以上转机</label>
					<input type="checkbox" id="chk24" name="allway" checked=""/><label for="chk24">均可</label>
					<span class="red pdr5">提示：可以多重选择</span>
				</p>
				<p class="line"></p>
				<p class="blod">请输入乘客信息(填写拼音与护照一致)：</p>
				<ul class="fare-list">
					<li>
						<h5>乘客1</h5>
						<ul class="fare-list-inputs uifix">
							<li>
								<span class="red pdr5">*</span>
								姓(Last Name) : <input type="text" name="lastName[]" class="" />
							</li>
							<li>
								Middle Name :
								<input type="text" class="" name="middleName[]"/>
							</li>
							<li>
								<span class="red pdr5">*</span>
								名(First Name) : <input type="text" name="firstName[]" class="" />
							</li>
							<li><span class="del delete-fare hide"></span></li>
						</ul>
					</li>
				</ul>
				<div class="J-fare-bak hide"></div>
				<p class="pdl45"><span class="del add-fare"></span></p>
				<p><span class="blod pdr5">备注：</span><textarea name="remark" class="texteare"></textarea></p>
				<p>
					<span class="red pdr5">*</span>
					联系电话 :
					<input type="text" class="input  mr45" name="phone"/> 
					<span class="red pdr5">*</span>
					Email :
					<input type="text" class="input" name="email"/>
				</p>
				<p></p>
				<p class="textCenter"><span class="del submit"></span></p>
			</form>
			</div>
			</div>
    <!--</div>-->

<?php echo  db_to_html(ob_get_clean());?>