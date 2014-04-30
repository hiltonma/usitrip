jQuery(document).ready(function() {
	// 日期弹出框事件
	jQuery('#airlines input.date').live('focus',function() {
	    jQuery('div.cal-wrapper').remove();
	    var cal = new Calendar_flight({dateFormat:"YYYY-MM-DD WEEK"});
		var box = jQuery(this).parents('p').eq(0);
		cal.appendTo(box[0]);
		cal.notClickClose();
		var _obj = {};
		_obj.dataNum = '1';
		var date = new Date();
		_obj.minDate = (date.getFullYear() + '-' + ((date.getMonth()+1).toString().length < 2 ? '0' + (date.getMonth()+1) : date.getMonth()+1) + '-' + date.getDate());
		if (jQuery(this).attr("id") == "formDate2") {
			var _date = jQuery('input[id="formDate"]').val().trim();
			if (_date) {
				_obj.minDate = _date.split(' ')[0];
			}
		} else {
			var index = jQuery('input[id="formDate"]').index(jQuery(this));
			if (index) {
				var _date = jQuery('input[id="formDate"]').eq(--index).val().trim();
				if (_date) {
					_obj.minDate = _date.split(' ')[0];
				}
			}
		}
		cal.open(jQuery(this)[0], _obj);
	});

	// 顶部的TAB选项卡事件
	jQuery('.chooseTab li').click(function(e) {
		jQuery('.chooseTab li').removeClass('selected');
		jQuery(this).addClass('selected');
		e.preventDefault();
		var index = jQuery('.chooseTab li').index(jQuery(this));
		jQuery('form[name="flights_form"]').attr('tabs',index);
		switch(index) {
			case 0:
				var inpt = jQuery('#box2').show().find('input[id="formDate2"]');
				inpt.val(inpt.attr('val'));
				jQuery('.add-airlines').parents('p').css('display','none');
				jQuery('span.delete-airlines').not('.hide').click();
				break;
			case 1:
				var inpt = jQuery('#box2').hide().find('input[id="formDate2"]');
				inpt.attr('val',inpt.val()).val('');
				jQuery('.add-airlines').parents('p').css('display','none');
				jQuery('span.delete-airlines').not('.hide').click();
				break;
			case 2:
				jQuery('#box2').hide();
				jQuery('.add-airlines').parents('p').css('display','block');
				break;
		}
	});
	
	// 删除航班
	jQuery('span.delete-airlines').live('click',function() {
	    jQuery(this).parents('div').eq(0).remove();
	});
	
	// 删除乘客
	jQuery('span.delete-fare').live('click',function(){
	    jQuery(this).parents('ul').eq(0).parents('li').eq(0).remove();
	});
	
	// 日期可机动的事件
	jQuery('.J-dateCanModify').live('click',function() {
		if (jQuery(this).attr('checked') == true) {
		    jQuery(this).siblings("span.J-jidong").css('display','inline');
		} else {
		    jQuery(this).siblings("span.J-jidong").css('display','none');
		}
	});
	
	// 添加城市按钮的事件
	jQuery('.add-airlines').click(function() {
		var html = jQuery('.J-item').find('div').eq(0).clone();
		jQuery('.J-show').append(html);
		html.find('span.delete-airlines').removeClass('hide');
		html.find('#box2').hide();
	});
	
	// 添加乘客按钮的事件
	jQuery('.add-fare').click(function(){
	    var html = jQuery('.J-fare-bak').find('li').eq(0).clone();
	    jQuery('ul.fare-list').append(html);
	    html.find('span.delete-fare').removeClass('hide');
	    var index = jQuery('.J-fare-bak').data('number');
	    index ++;
	    jQuery('.J-fare-bak').data('number',index);
	    html.find('h5').html('\u4e58\u5ba2' + index);
	})
	// 复制一个航班信息到顶部容器中。
	jQuery('.J-item').hide().find('div').eq(0).clone().appendTo(jQuery('.J-show'));
	
	// 复制一个乘客信息到页面
	jQuery('ul.fare-list').find('li').eq(0).clone().appendTo(jQuery('.J-fare-bak'));
	// 乘客序号默认1
	jQuery('.J-fare-bak').data('number',1);
	// 提交表单事件
	jQuery('span.submit').click(function(){
		var index = jQuery("form[name='flights_form']").attr('tabs');
		var msg = '';
		jQuery('.J-show input[name="from_city[]"]').each(function(){
			if (jQuery(this).val().trim() == '') {
				msg += '您还有飞离城市未填写！\n';
				return false;
			}
		});
		jQuery('.J-show input[name="to_city[]"]').each(function(){
			if(jQuery(this).val().trim() == '') {
				msg += '您还有飞抵城市未填写！\n';
				return false;
			}
		});
		jQuery('.J-show input[name="go_time[]"]').each(function(){
			if (jQuery(this).val().trim() == '') {
				msg += '您还有出发日期未选择！\n';
				return false;
			}
		});
		jQuery('input[name="email"]').each(function(){
			var e_mail_check=/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/;
			if (jQuery(this).val().trim() == '') {
				msg += '你的邮件未填写！\n';
				return false;
			}
			if (!e_mail_check.test(jQuery(this).val().trim())){
				msg += '邮箱格式不正确！\n';
				return false;
			}
		});
		if (index == '0') {
			var back_time = jQuery('.J-show input[name="back_time[]"]').eq(0).val();
			msg += !back_time ? '返回日期您还未选择！\n' : '';
		}

		jQuery('.fare-list').find('input[name="lastName[]"]').each(function(){
			if (jQuery(this).val().trim() == '') {
				msg += '您还有乘客姓未填写，如果不需要，请删除多余的乘客！\n';
				return false;	
			}	
		});
		jQuery('.fare-list').find('input[name="firstName[]"]').each(function(){
			if (jQuery(this).val().trim() == '') {
				msg += '您还有乘客名未填写，如果不需要，请删除多余的乘客！\n';
				return false;	
			}	
		});
		var phone = jQuery('input[name="phone"]').val().trim();
		msg += !phone ? '您还未填写联系电话！\n' : '';
		if (msg) {
			alert(msg);
			return; 
		}
		jQuery(this).parents('form').submit();
	});
	
	// 标记当前默认的选项卡索引值
	jQuery('form[name="flights_form"]').attr('tabs','0');
});
