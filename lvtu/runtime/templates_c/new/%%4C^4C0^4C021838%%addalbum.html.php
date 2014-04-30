<?php /* Smarty version 2.6.22, created on 2013-12-27 17:27:46
         compiled from addalbum.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "head.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div id="body">
	<div class="crumbs"></div>
	<h1><a href="javascript:void(0)" class="J-publish publish"></a><a href="<?php echo $this->_tpl_vars['nav_index']; ?>
" class="myhome myfind"></a><a href="<?php echo $this->_tpl_vars['nav_index']; ?>
" class="a_all"><span class="title"><i></i>全部游记</span></a></h1>

	<div class="lvtu_specialadd fn-bc fn-pr mt5">
	


		<div class="lvtu_specialadd_title f16 fb darkgray png24">创建新游记</div>
		<div class="lvtu_specialadd_content">
			<!--content_add-->
			<div class="lvtu_specialadd_content_add">
				<div class="lvtu_special_title pb20 fn-clearfix">
					<div class="l fn-fl fn-tr pr5 f14" >游记标题：</div>
					<div class="m fn-fl">
						<input class="border-ca" name="" type="text" id="albumName"/>
					</div>
					<div class="r fn-fl">
					<!-- 填写错误出现 -->
					<p id="doubleName" class="specialadd_talk_error f12 darkgray666 fn-none png24">
					<span>游记已存在，无法创建，请修改标题</span>
					</p>
					<!-- 填写错误出现 end -->
					</div>
				</div>

<!-- 				<div class="lvtu_special_describe pb20 fn-clearfix">
					<div class="l fn-fl fn-tr pr5 f14">游记描述：</div>
					<div class="m fn-fl">
						<textarea class="border-ca" name="" rows="" id="description"></textarea>
					</div>
					<div class="r fn-fl">
					<p id ="albumDesc" class="specialadd_talk_error f12 darkgray666 fn-none png24">
					<span>152字/145字,您已超过7字</span>
					</p>
					</div>
				</div> -->
				<div class="lvtu_special_trip_line pb20 fn-clearfix">
					<div class="l fn-fl fn-tr pr5 f14">旅游线路：</div>
					<div class="m fn-fl J-addTrip">
						<input class="border-ca" name="" id="trip_line" />
					</div>
					<div class="r fn-fl">
					<p id ="albumDesc" class="specialadd_talk_error f12 darkgray666 fn-none png24">
					<span>152字/145字,您已超过7字</span>
					</p>
					</div>
				</div>
				<div class="lvtu_special_destination fn-clearfix">
					<div class="l fn-fl fn-tr pr5 f14">目的地：</div>
					<div class="J-addGuide m fn-fl">
						<input class="border-ca" type="text" value="" id="addressId"/>
					</div>
					
					<div class="r fn-fl">
					<p>可选多个，可以是省、市、也可以是某些小地方</p>
					<p class="specialadd_talk_error f12 darkgray666 fn-none png24">
					<span>* 请选择此行的目的地</span>
					</p>
					</div>
				</div>
			</div>



<!--添加第一批照片按钮-->
<div id="batch0">
	<div class="lvtu_specialadd_input fn-clearfix fn-pr">
		<!-- 新手引导覆盖层 -->
		
		
		   <div class="J-addGuidet add_guidet_fc fn-pa fn-none"></div>
		
		
		<div class="l fn-fl fn-tr pr5 f14"></div>
		<div id="uploadFlash" class="m fn-fl">
		  <input type="button" id="id_upload" class="upload_botton" name="id_upload"/>
	 	          <span class="jk_upload_add_t fn-pa"></span>
		</div>
		<div class="r fn-fl">
		<p class="f12 darkgray666">请按具体拍摄地分批添加，一次先选30张，多出的等会再选</p>	 
		</div>
	</div>

	<div class="lvtu_special_line pb20 "></div>  
	
	<div class="lvtu_special_shoot pb20 fn-clearfix fn-none">
	       <div class="l fn-fl fn-tr pr5 f14 png24">拍摄地点：</div>
	       <div class="m fn-fl"><input id="psAddressId" class="border-ca" name="batchdes" type="text" value="" ></div>
			<div class="r fn-fl">
			<p class="specialadd_talk_error f12 darkgray666 fn-none png24">
			<span>请至少添加拍摄地点</span>
			</p>	 
			</div>
	 </div>
	 <div class="lvtu_specialadd_content_pic fn-none">
	    
	    <div  id="imageId"  class="lvtu_special_pic fn-pr fn-oauto">
	      <ul>
	      </ul>
	      <div id='query0' class='some_file_queue'></div>
	    </div>
	</div>
</div>
<div id="nextPic">

</div>

<div id="uploadTemplate" style="display:none">
    <div id="batch">
		<div class="lvtu_specialadd_input_next mt20 fn-clearfix">
			<div class="l fn-fl fn-tr pr5 f14"></div>
			<div class="m fn-fl">
			  <input type="file"   id="id_upload" name="id_upload"/>
		 	  <span class="jk_upload_add_t fn-pa"></span>
			</div>
			<div class="r fn-fl">
		<p>请按具体拍摄地分批添加，一次先选30张，多出的等会再选	</p>	 
		</div>
		</div>
	</div>
</div>

<div id="PicTemplate" style="display:none">
		<div class="lvtu_special_line pb20 "></div>  
		
		<div class="lvtu_special_shoot pb20 fn-clearfix fn-none">
	       <div class="l fn-fl fn-tr pr5 f14">拍摄地点：</div>
	       <div class="m fn-fl"><input id="batchdes" class="border-ca" name="batchdes" type="text" value="" ></div>
			<div class="r fn-fl">
			<p class="specialadd_talk_error f12 darkgray666 fn-none png24">
			<span>请至少添加拍摄地点</span>
			</p>	 
			</div>
	    </div>
	    
		 <div class="lvtu_specialadd_content_pic fn-none">
		  
		    <div   id="imageIdX"   class="lvtu_special_pic fn-pr fn-oauto">
		      <ul>
		      </ul>
		      	<div id='query' class='some_file_queue'></div>
		      
		    </div>
		</div>
</div>
    
    

	<!--预览/创建-->
		<div class="lvtu_special_button fn-tc">
			<!-- <input type="button" class="lvtu_special_button_preview mr20" value=""> -->
			<input id="addAlbum" type="button" class="J-special-btn lvtu_special_button_create png24" value=""><span class="tc"></span>
		</div>
		</div>
		<!--lvtu_specialadd_content end-->
		</div>
	<!--lvtu_specialadd end-->
	
	
	<div id="category" style="display:none">
			</div>
	
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "foot.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<input id="userId" value="96761" type="hidden"/>
	<input id="newHandler" value="1111" type="hidden"/>

<!-- 还可添加 -->
<!-- <div class="addAlbum_content f14 fn-bc">本相册还可以再添加<em class="orange">15</em>张照片，超出部分请<em class="deongaree"><a href="#">新建相册</a></em></div> -->
<!-- 最多添加 -->
<!-- <div class="addAlbum_content f14 fn-bc">一次最多添加<em class="orange">50</em>张照片，超出部分下次上传.</div>
 --><!-- <div class="addAlbum_reg fn-tc mt20">
<span class="mr10"><input class="addAlbum_btn_reelect" type="button" value=""  /></span>
<span><input class="addAlbum_btn_confirm" type="button" value="" /></span>
</div> -->




	


<script type="text/javascript">		
var pathUpload = "<?php echo $this->_tpl_vars['nav_index']; ?>
";
/*(function(){				
		//-- load ga script				
		var s = document.createElement('script');	
		var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
		s.src = _bdhmProtocol + 'hm.baidu.com/h.js?bcf78240c63ef6dd5612459011b359d1';				
		var head = document.getElementsByTagName('head');				
		if(head&&head[0]) { head[0].appendChild(s); }			
})();	*/
</script>
<!-- 上传JS -->
<script src="<?php echo @DIR_WS_JS; ?>
index-201301041651.js"></script>
<script src="<?php echo @DIR_WS_JS; ?>
addalbum-201301081801.js" charset="utf-8"></script>
</body>
</html>