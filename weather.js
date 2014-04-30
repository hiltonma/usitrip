var Weather = new function()
{
	var Weather = this;
	Weather.Message = {
	'FailForecast':'Fail to get Weather Forcast',
	'InvalidQuery':'Invalid Query',
	'Loading':'Loading...',
	'Close':'close',
	'Open':'open',
	'Cities':'Cities',
	'edit':'edit',
	'Deleting':'Deleting...'
	};
	
	var YUE = YAHOO.util.Event  ;
	var YUD = YAHOO.util.Dom;
	var YUI = YAHOO.util.Interaction;
	var dQueryBox ;
	var dSubmitBtn ;
	var dSuggestMenu ;
	var nTransactionId ;
	var nForeCastId ;
	var dWeather;
	var dWeatherUl;
	var dExtendForecast;
	var zIndex = 100;
	
	/*private function*/
	var animCollapse = function(dObj,bFixWidth,bKeepAlive,bNoOpacity,fCallBack)
	{
			var oStyle = dObj.style;
			oStyle.overflow  = 'hidden';
			oStyle.display = 'block';
			if(!dObj.Timer){dObj.Timer = setTimeout('void(0)',0);};
			var nH = dObj.offsetHeight;
			var nW = dObj.offsetWidth;
			var n =100;
			
			var fAction = function()
			{
				if( n>0 )
				{
					n += (0-100)/5;
					oStyle.height =( nH * n/100 ) +'px';
					if(!bFixWidth)
					{
						oStyle.width =( nW * n/100 ) +'px';
					};
					if(!bNoOpacity)
					{
						YUD.setStyle( dObj ,'opacity',Math.floor(n)/100);
					};
					setTimeout(fAction,0);
				}
				else
				{
					if(!bKeepAlive)
					{
						dObj.parentNode.removeChild(dObj);
					}
					else
					{
						oStyle.display = 'none';
					};
					if(typeof(fCallBack)=='function')
					{
						fCallBack();
					};
				};
			};
			clearTimeout(dObj.Timer);
			fAction();
	};
	
	/*private function*/
	var animExpand = function(dObj,bFixWidth,bKeepAlive,bNoOpacity,fCallBack)
	{
			var oStyle = dObj.style;
			oStyle.display = 'block';
			oStyle.overflow = 'visible';
			if(!dObj.Timer){dObj.Timer = setTimeout('void(0)',0);};
			var nH = dObj.offsetHeight;
			var nW = dObj.offsetWidth;
			oStyle.overflow = 'hidden';
			var n =0;
			var d = YUD;
			var fAction = function()
			{
				if( n<95 )
				{
					n += (100-n)/3;
					oStyle.height =( nH * n/100 ) +'px';
					if(!bFixWidth)
					{
						oStyle.width =( nW * n/100 ) +'px';
					};
					dObj.Timer = setTimeout(fAction,0);
				}
				else
				{
					oStyle.overflow = 'visible';
					oStyle.height ='auto';
					if(!bFixWidth)
					{
						dObj.style.width ='auto';
					};
					if(typeof(fCallBack)=='function')
					{
						fCallBack();
					};
				};
			};
			clearTimeout(dObj.Timer);
			fAction();
	};
	
	/*private function*/
	var onFormSubmit = function()
	{
		WeatherSuggest.onBlur();
		clearTimeout(WeatherSuggest.Timer);
		dQueryBox.value = YAHOO.util.String.trim(dQueryBox.value.toUpperCase());
		if( dQueryBox.value !='')
		{
			var dTempWeatherItem = document.createElement('li');
				dTempWeatherItem.innerHTML =  Weather.Message.Loading +   dQueryBox.value ;
				dTempWeatherItem.className='weather-item on-query';
				if(dWeatherUl.firstChild)
				{
					dWeatherUl.insertBefore(dTempWeatherItem,dWeatherUl.firstChild);
				}
				else
				{
					dWeatherUl.appendChild(dTempWeatherItem);
				};
				var responseSuccess = function(oXHR)
				{
					var dDiv = document.createElement('div');
					dDiv.innerHTML = oXHR.responseText;
					var dWeatherItem = dDiv.getElementsByTagName('li')[0];
					if(dWeatherItem)
					{
						dWeatherUl.replaceChild(dWeatherItem , dTempWeatherItem);
						animExpand(dWeatherItem);
					}
					else
					{
						responseFailure();
					};
				};
				var responseFailure = function(oError)
				{
					dTempWeatherItem.innerHTML = Weather.Message.InvalidQuery;
					YUD.addClass(dTempWeatherItem,'invalid-query');
					var clear = function()
					{
						animCollapse(dTempWeatherItem);
					};
					setTimeout( clear,1000);
				}
				var callback =
				{
					success: responseSuccess,
					failure: responseFailure,
					argument: null
				};
				dQueryBox.focus();
				var sUrl = ['weather_function.php?action=add&' , 
				dQueryBox.name , 
				'=' , encodeURIComponent(dQueryBox.value) , '&nocache=', Math.random()
				].join('');
				
				var cObj = YAHOO.util.Connect.asyncRequest('GET',sUrl,callback ,null);
				dQueryBox.value = '';
		};
		return false;
	};
	
	/*private function*/
	var WeatherInit = function()
	{
		dWeather = document.getElementById('weather');
		dQueryBox = document.getElementById('weather-query');
		dQueryBox.autoComplete = 'off';
		dSubmitBtn = document.getElementById('weather-submit');
		dWeatherUl = dWeather.getElementsByTagName('ul')[0];
		dQueryBox = document.getElementById('weather-query');
		dQueryBox.form.onsubmit = onFormSubmit;
		WeatherSuggest.init();/*!*/
	};
	
	/*private function*/	
	var onEditClick = function(e,dEl,dObj)
	{
			
			//alert(e+"\n"+dEl+"\n"+dObj);
			
		if(!dEl.showForm)
		{
			dEl.showForm = false;
			dEl.Timer =setTimeout('void(0)',0);
			/*initialize all objects*/
			WeatherInit();
		};
		dEl.showForm = !dEl.showForm;
		var dWeather = document.getElementById('weather');
		if( dWeather )
		{
			var isShow = dEl.showForm;
			var dForm = dWeather.getElementsByTagName('form')[0];
			if(isShow)
			{
				var c = function(){dEl.innerHTML = Weather.Message.Close ; dQueryBox.focus();};
				animExpand(dForm,true,true,true,c );
			}
			else
			{
				var c = function(){dEl.innerHTML = Weather.Message.Edit};
				animCollapse(dForm,true,true,true,c );
			};
		};
	};

	var onloadpage = function ()
	{
		
		WeatherInit();
		var dWeather = document.getElementById('weather');
		dQueryBox = document.getElementById('weather-query');
		var dForm = dWeather.getElementsByTagName('form')[0];
		var c = function(){ //dQueryBox.focus();
		};
		animExpand(dForm,true,true,true,c );
	}
	
	//YUI.addListener('weather','click',onEditClick ,'class','weather-edit',true,2);
	
	
	var onWeatherItemMouseOver = function(e,dEl,dObj)
	{
		if(!dEl.enhance)
		{
			dEl.enhance = true;
			dEl.Timer =setTimeout('void(0)',0);
		};
		clearTimeout( dEl.Timer);
		YUD.addClass(dEl,'mouseover');
	};
	YUI.addListener('weather','mouseover',onWeatherItemMouseOver ,'class',
	'weather-item',true,6);
	
	/*private function*/
	var onWeatherItemMouseOut = function(e,dEl,dObj)
	{
		if(!dEl.enhance)
		{
			dEl.enhance = true;
			dEl.Timer =setTimeout('void(0)',0);
		};
		var fAction = function()
		{
			YUD.removeClass(dEl,'mouseover');
		};
		clearTimeout( dEl.Timer);
		dEl.Timer = setTimeout( fAction,10);
	};
	YUI.addListener('weather','mouseout',onWeatherItemMouseOut ,'class',
	'weather-item',true,6);
	
	/*private function*/
	var onWeatherDeleteClick = function(e,dEl,dObj)
	{
		if(e.type == 'keyup' && e.keyCode!=46){return;};
		
		var dWeatherItem = YUD.popElementByClass(dEl,'weather-item',10);
		if(dEl.className!='weather-delete')
		{
			dEl = YAHOO.util.Dom.getElementsByClass('weather-delete','a',dWeatherItem,1)[0];
		};
		if(dWeatherItem )
		{
			var killMe = function()
			{
				
				animCollapse(dWeatherItem);
			};
			var callback =
			{
				success: killMe,
				failure: killMe,
				argument: null
			};
			var sUrl =dEl.href + "&nocache=" + Math.random() + Date.parse(new Date);
			var cObj = YAHOO.util.Connect.asyncRequest('GET',sUrl,callback ,null);
			
			dWeatherItem.disabled = true;
			dWeatherItem.innerHTML = Weather.Message.Deleting;
			dWeatherItem.className='weather-item on-query';
			
			
		};
	};
	YUI.addListener('weather','keyup',onWeatherDeleteClick ,'class','weather-city',true,6);
	YUI.addListener('weather','click',onWeatherDeleteClick ,'class','weather-delete',true,6);
	
	/*private function*/
	var onWeatherForeCaseClick = function(e,dEl)
	{
		var sArg='&json=true';
		if(!dExtendForecast)
		{
			dExtendForecast = document.createElement('div');
			dExtendForecast.className = 'weather-extent-forecast';
			dExtendForecast.id = YUD.getUniqueElementId();
			document.body.appendChild(dExtendForecast);
			var DnD = new YAHOO.util.DD(dExtendForecast.id);
			sArg = '';
			var closeExtendForecast = function(e)
			{
				if(dExtendForecast.style.display.toLowerCase()=='none'){return;};
				var dEl = e.target || e.srcElement;
				var hide = function()
				{
					dExtendForecast.style.display='none';
					dExtendForecast.style.height = 'auto';
				};
				if(dEl.className == 'exit-forecast')
				{
					dExtendForecast.style.display='none';
					var fCallBack =
					animCollapse(dExtendForecast,true,true,true,hide);
					YUE.preventDefault(e);
					YUE.stopPropagation(e);
					return false;
				}
				else
				{
					dEl =  YUD.popElementByClass(dEl,'weather-extent-forecast',10);
					if(!dEl)
					{
						animCollapse(dExtendForecast,true,true,true,hide);
					};
				};
			};
			YUE.addListener(document,'click',closeExtendForecast);
		};
		var oStyle = dExtendForecast.style;
		
		oStyle.display='block';
		oStyle.left='0';
		oStyle.top='2.4em';
		
		dEl.parentNode.appendChild(dExtendForecast);
		var weatherItem = YUD.popElementByClass(dEl,'weather-item',10);
		weatherItem.style.zIndex = (zIndex+=2);
		var responseSuccess = function(oXHR)
		{
			if( oXHR.tId >= nForeCastId )
			{
				try
				{
					var s = oXHR.responseText.split('<!--')[0];//remove gzip comment;
					if(oXHR.responseText.indexOf('<')>=0)
					{
						YUD.removeClass(dExtendForecast,'on-query-forecast');
						dExtendForecast.innerHTML = oXHR.responseText ;
					}
					else
					{
						var aData =eval( '(' + s + ')' );
					};
				}catch(err)
				{
					responseFailure();
					return;
				};
			};
		};
		var responseFailure = function(oError)
		{
			dExtendForecast.innerHTML =Weather.Message.FailForecast;
		};
		var callback =
		{
			success: responseSuccess,
			failure: responseFailure,
			argument: null
		};
		dExtendForecast.innerHTML = Weather.Message.Loading;
		YUD.addClass(dExtendForecast,'on-query-forecast');
		var sUrl = dEl.href.split('#')[1] + '&nocache='+ Math.random() + sArg;
		
		var cObj = YAHOO.util.Connect.asyncRequest('GET',sUrl,callback ,null);
		nForeCastId =  cObj.tId ;
	};
	YUI.addListener('weather','click',onWeatherForeCaseClick ,'class',
	'weather-forecast',true,3);
	
	/*private function*/
	var WeatherSuggest = new function()
	{
		var WeatherSuggest = this;
		var nSelectedIndex = 0;
		var dSelectedItem = null;
		WeatherSuggest.Timer = setTimeout('void(0)',0);
		
		WeatherSuggest.init = function()
		{
			dSuggestMenu = document.createElement('div');
			dSubmitBtn.form.insertBefore( dSuggestMenu , dSubmitBtn.nextSibling);
			dSuggestMenu.className = 'weather-suggest';
			YUE.addListener(dQueryBox,'keyup',onWeatherSuggestKeyUp);
			YUE.addListener(document,'click',WeatherSuggest.onBlur);
			YUE.addListener(document,'keyup',WeatherSuggest.onBlur);
			YUI.addListener(dSuggestMenu,'mouseover',onSuggestMenuMouseAction ,'class',
			'weather-suggest-item',true,2);
			YUI.addListener(dSuggestMenu,'mouseout',onSuggestMenuMouseAction ,'class',
			'weather-suggest-item',true,2);
			YUI.addListener(dSuggestMenu,'mousedown',onSuggestMenuMouseAction ,'class',
			'weather-suggest-item',true,2);
		};
		
		var onSuggestMenuMouseAction = function(e,dEl)
		{
			switch(e.type)
			{
				case 'mouseover':;
				YUD.addClass(dEl,'weather-suggest-item-hover');
				break;
				case 'mouseout':;
				YUD.removeClass(dEl,'weather-suggest-item-hover');
				;break;
				case 'mousedown':;
				YUD.removeClass(dEl,'weather-suggest-item-hover');
				selectWeatherItem(dEl,true);
				var closeMenu = function()
				{
					WeatherSuggest.onBlur();
				};
				setTimeout(closeMenu,500);
				break;
				default:return;
			};
		};
		
		var selectWeatherItem = function(dItem,bNotScroll)
		{
			if(dSelectedItem && dSelectedItem!=dItem)
			{
				YUD.removeClass(dSelectedItem,'selected');
			};
			dSelectedItem = dItem;
			YUD.addClass(dItem,'selected');
			dQueryBox.value = dItem.getAttribute('number');
			dQueryBox.title = dItem.lastChild.value;
			if(!bNotScroll)
			{
				dItem.parentNode.scrollTop = Math.max(0 ,
				(dItem.offsetTop - dItem.offsetHeight * 2));
			};
			dQueryBox.focus();
		};
		
		var onWeatherSuggestKeyUp = function(e)
		{
			var eK =  e.keyCode;
			switch(true)
			{
				case (eK >=37 && eK <=40):;//Arrow Key
				if(dSuggestMenu)
				{
					var dLI = dSuggestMenu.getElementsByTagName('li');
					var ln = dLI.length;
					var sn = nSelectedIndex;
					if(ln>0)
					{
						switch(eK)
						{
							case 38:sn-=1;break;
							case 40:sn+=1;break;
							default:return true;
						};
						sn = Math.min( Math.max(0,sn), (ln-1) );
						nSelectedIndex = sn;
						selectWeatherItem(dLI[sn]);
						return true;
					};
				};
				YUE.preventDefault(e);
				YUE.stopPropagation(e);
				return false;
				case (eK == 13):;//enter
				return true;
				case (eK == 192):;//tab
				return true;
				default:;break;
			};
			var fAction = function()
			{
				var responseSuccess = function(oXHR)
				{
					if( oXHR.tId >= nTransactionId )
					{
						YUD.removeClass(dSuggestMenu,'has-more-suggest-data');
						try
						{
							var s = oXHR.responseText.split('<!--')[0];//remove gzip comment;
							var aData =eval( '(' + s + ')' );
						}catch(err)
						{
							responseFailure();
							return;
						};
						var sHTML = [];
						var aData =  aData.data;
						if(aData.length > 10 )
						{
							YUD.addClass(dSuggestMenu,'has-more-suggest-data');
						};
						WeatherSuggest.onBlur();
						if(aData.length > 0)
						{
							for(var n=0;n<aData.length;n++)
							{
								var t = aData[n].text.split(' ');
								sHTML[n] = ['<li class="weather-suggest-item" selectedIndex="'
								,n,'" number="',aData[n].number,
								'" description="'+ aData[n].text +'"><em>' ,
								t.shift() , '</em> ' ,  t.join(' ') , '</li>'].join('');
							};
							dQueryBox.value = YAHOO.util.String.trim(dQueryBox.value);
							if(dQueryBox.value!='')
							{
								dSuggestMenu.style.display='block';
								dSuggestMenu.innerHTML =  ['<ul><h6>'+aData.length , ' ',
								Weather.Message.Cities	,
								'</h6>' ,  sHTML.join('') , '</ul>'].join('') ;
							};
						};
						dQueryBox.blur();/*avoid Firefox autocomplete box*/
						dQueryBox.focus();
						YUD.removeClass(dQueryBox,'on-query');
					};
				};
				
				var responseFailure = function(oError)
				{
					WeatherSuggest.onBlur();
					YUD.removeClass(dQueryBox,'on-query');
				};
				
				var callback =
				{
					success: responseSuccess,
					failure: responseFailure,
					argument: null
				};
				dQueryBox.focus();
				YUD.addClass(dQueryBox,'on-query');
				var sUrl = ['weather_function.php?action=suggest&' ,
				dQueryBox.name , '=' , encodeURIComponent(sKey) , '&nocache=',
				Math.random()].join('');
				var cObj = YAHOO.util.Connect.asyncRequest('GET',sUrl,callback ,null);
				nTransactionId =  cObj.tId ;
			};
			clearTimeout(WeatherSuggest.Timer);
			
			var sKey = YAHOO.util.String.trim(dQueryBox.value);
			WeatherSuggest.Timer = setTimeout(fAction,400);
			dSuggestMenu.innerHTML = '';
			YUE.preventDefault(e);
			YUE.stopPropagation(e);
			return false;
		};
		
		WeatherSuggest.onBlur = function(e)
		{
			if(e )
			{
				var dEl = e.target || e.srcElement;
				while(dEl.parentNode)
				{
					if(dEl.tagName == "A"){break;}
					else if(dEl.tagName == "INPUT" && dEl != dQueryBox){break;}
					else if(dEl == dWeather || dEl == dQueryBox){return true;};
					dEl = dEl.parentNode;
				};
			};
			dSuggestMenu.style.display='none';
			dSuggestMenu.innerHTML ='';
			nSelectedIndex = 0;
			dSelectedItem = null;
		};
	};
	
	/* To Load Weather Input On Load */
	onloadpage();
	
};
