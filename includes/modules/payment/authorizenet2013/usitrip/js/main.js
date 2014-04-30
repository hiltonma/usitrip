/*@charset "utf-8";*/
$(document).ready(function(){
	$('#formAuthorize').submit(function(){
		var _submit = '#submitAuthorize';
		$(_submit).attr('disabled', 'disabled').text('正在支付，请稍候……');
		$.post($(this).attr('action'), $(this).serialize(), function(json){
			if(!json){ alert('支付服务器无响应，请稍后再试！'); $(_submit).attr('disabled', '').text('确定付款'); return false;}
			if(json['x_response_code']!='1'){	//支付失败
				var error = '错误代码：' + json['x_response_code']+ '-' + json['x_response_reason_code'] + '\n错误信息：' + json['x_response_reason_text'];
				alert("支付失败！错误信息如下：\n\n" + error);
				$(_submit).attr('disabled', '').text('确定付款');
			}else{	/*支付成功*/ alert('支付成功！');	$(_submit).text('支付成功！')}
			if(json['goToUrl']){
				window.location = json['goToUrl'];
				return false;
			}
		}, 'json');
		return false;
	});
});