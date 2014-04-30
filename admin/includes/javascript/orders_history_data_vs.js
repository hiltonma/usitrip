//orders_history_data_vs.js
//订单更新的历史记录前两条的不同信息用颜色区分
!function(){
	//对比两组单元格对象的内容，并改变内容不同的单元格的样式（新对象加粗，旧对象加中划线），vs_class是对比的精细度td为对比单元格str为单元格中的每个字符都对比space为以空格为边界对比
	function cell_vs(newTd, oldTd, vs_class ){	
		if(vs_class!='str' && vs_class!='space' && vs_class!='td'){
			alert('只接受str|space|td级别的对比！');
			return false;
		}
		for(var j=0; j<Math.max(newTd.length, oldTd.length); j++){
			var oldText = '';
			var newText = '';
			if(jQuery(oldTd[j]).text() != jQuery(newTd[j]).text()){
				switch(vs_class){
					case 'td':	//单元格级别对比
						newText = '<ufo>' + jQuery(newTd[j]).text() + '</ufo>';
						oldText = '<del>' + jQuery(oldTd[j]).text() + '</del>';
					break;
					case 'str':		//代码级别对比
						var nTexts = jQuery(newTd[j]).text();
						var oTexts = jQuery(oldTd[j]).text();
						var delimiter = '';
					case 'space': //空格分割对比
						if(vs_class=='space'){
							delimiter = ' ';
							nTexts = jQuery(newTd[j]).text().split(delimiter);
							oTexts = jQuery(oldTd[j]).text().split(delimiter);
						}
						for(var k=0; k<Math.max(nTexts.length, oTexts.length); k++){
							if(typeof(nTexts[k])!='undefined' && typeof(oTexts[k])!='undefined' && nTexts[k]!=oTexts[k]){	//新旧都有但不同
								newText+= '<ufo>' + nTexts[k] + '</ufo>' + delimiter;
								oldText+= '<del>' + oTexts[k] + '</del>' + delimiter;
							}else if(typeof(nTexts[k])=='undefined' && typeof(oTexts[k])!='undefined'){	//有旧没有新
								oldText+= '<del>' + oTexts[k] + '</del>' + delimiter;
							}else if(typeof(nTexts[k])!='undefined' && typeof(oTexts[k])=='undefined'){	//有新没有旧
								newText+= '<ufo>' + nTexts[k] + '</ufo>' + delimiter;
							}else{	//新旧一样
								newText+= nTexts[k] + delimiter;
								oldText+= oTexts[k] + delimiter;
							}
						}
					break;
				}
			}
			
			if(typeof(oldTd[j])!='undefined' && oldText!=''){
				jQuery(oldTd[j]).html(oldText);
			}
			if(typeof(newTd[j])!='undefined' && newText!=''){
				jQuery(newTd[j]).html(newText);
			}
		}
	};

	
	//上车地址历史记录处理对比
	departure_location = function() {
		var newLineTd = new Array();
		var oldLineTd = new Array();
		jQuery('table[id^="departureLocationHistory_"] > tbody > tr:nth-child(3)').each(function(i) {	//最新记录的上一条记录(1是标题，2是最新记录，3是最新记录的上一条记录)
			oldLineTd[i] = jQuery(this).find(' > td').first();
			var id = jQuery(this).parents('table').attr('id');
			newLineTd[i] = jQuery('table[id^="'+id+'"] > tbody > tr:nth-child(2) > td').first();
		});
		cell_vs(newLineTd, oldLineTd, 'space');
	};
	
	//航班信息历史记录处理对比
	flightInfo = function() {
		jQuery('table[id^="flightInformationHistory_"]').each(function() {
			var id = jQuery(this).attr('id');
			var newTable = '';
			var oldTable = '';
			jQuery('table[id="'+ id +'"] .flignt_info_history').each(function(i){
				//只对比最后俩历史记录
				if(i==0){	//第1条记录数据
					newTable = jQuery(this);
				}
				if(i==1){	//第2条记录数据
					oldTable = jQuery(this);
				}
			});
			if(newTable!='' && oldTable!=''){	//取各个单元格数据比对
				var checked = true;
				var newTd = jQuery(newTable).find('td:gt(1)');	//第1、2个是添加日期的单元格内容，不用对比
				var oldTd = jQuery(oldTable).find('td:gt(1)');
				if(jQuery(newTd).length != jQuery(oldTd).length || jQuery(newTd).length!=20){
					alert('航班信息历史表格已经修改，需要重新设计程序！');
					checked = false;
				}
				if(checked == true){	//开始比对各个单元格数据
					var vsClass = 'space';	//设置对比级别'td'为单元格级别对比，'str'是代码级别对比，代码级别更细！
					cell_vs(newTd, oldTd, vsClass);
				}
			}
		});
	};
	
	//客人信息更新历史记录对比(只对比第1、2条记录的第2(客人姓名信息)、第3列(房间人数信息))
	guest_info = function() {
		
		var newGuest = new Array();	//客人姓名信息（新）
		var oldGuest = new Array();	//客人姓名信息（旧）
		var newLodging = new Array();	//房间人数信息（新） 
		var oldLodging = new Array();	//房间人数信息（旧） 
		
		jQuery('table[id^="guestInfoUpdatedHistories_"] > tbody > tr:nth-child(3)').each(function(i) {	
			var _ob = jQuery(this).find(' > td');
			//alert(jQuery(_ob).length);
			oldGuest[i] = jQuery(_ob).eq(1);
			oldLodging[i] = jQuery(_ob).eq(2);	//如果商务中心要求要找最后一条已审核的来比的话则要把此值2改成“已审核”的第一条的索引值即可
			var id = jQuery(this).parents('table').attr('id');
			var _nb = jQuery('table[id^="'+id+'"] > tbody > tr:nth-child(2) > td');
			newGuest[i] = jQuery(_nb).eq(1);
			newLodging[i] = jQuery(_nb).eq(2); //如果商务中心要求要找最后一条已审核的来比的话则要把此值2改成“已审核”的第一条的索引值即可
		});
		//1.客人姓名对比
		for(var i=0; i<oldGuest.length; i++){
			var oldGuestTable = jQuery(oldGuest[i]).find('table');	//first();	//此单元格中只有一个表格
			var newGuestTable = jQuery(newGuest[i]).find('table');	//first();	//此单元格中只有一个表格
			var oldTd = jQuery(oldGuestTable).find('td');	//查找所有后代的td
			var newTd = jQuery(newGuestTable).find('td');
			cell_vs(newTd, oldTd, 'td');
		}
		//2.房间人数对比
		for(var i=0; i<oldLodging.length; i++){
			//alert('第'+ (i+1) +'个产品人数信息：'+ "\n\n" +jQuery(oldLodging[i]).html());
			var oldTd = jQuery(oldLodging[i]).find('td');	//查找所有后代的td
			var newTd = jQuery(newLodging[i]).find('td');
			cell_vs(newTd, oldTd, 'td');
		}
	};
	
	//当日游客手机号码历史对比
	cellphone_number = function(){
		var newLineTd = new Array();
		var oldLineTd = new Array();
		jQuery('table[id^="TcellphoneUpdatedHistory_"] > tbody > tr:nth-child(3)').each(function(i) {	//最新记录的上一条记录(1是标题，2是最新记录，3是最新记录的上一条记录)
			oldLineTd[i] = jQuery(this).find(' > td').first();
			var id = jQuery(this).parents('table').attr('id');
			newLineTd[i] = jQuery('table[id^="'+id+'"] > tbody > tr:nth-child(2) > td').first();
		});
		cell_vs(newLineTd, oldLineTd, 'str');
	};
	
	//执行上车地址对比
	departure_location();
	//执行航班信息对比
	flightInfo();
	//执行客户信息对比
	guest_info();
	//执行当日游客手机码码历史对比
	cellphone_number();
}();