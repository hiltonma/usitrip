

/*
String
*/

YAHOO.util.String =  new function()
{	
	this.trim = function(sString){return sString.replace(/(^\s+)|\s+$/g,"");};
};

/*
Interaction
*/
YAHOO.util.Interaction = new function()
{
	var Interaction = this;

	Interaction.popElementByAttribute = function(	oNode, sAttributeName , sAttributeValue ,iMaxLevel )
	{
	
		var trimString = YAHOO.util.String.trim;

		var re = new RegExp("(^|\\s)"+  trimString( sAttributeValue ) +"(\\s|$)");
		
		var oElement;
		var i = iMaxLevel;
			
		while(oNode)
		{
			
			if(i!=null)
			{
				if( i<0){return null;}	
				else{ i--;};				
			};
			
			var c;
			var sa ;
			try{
				
				sa = oNode.getAttribute(sAttributeName);
				switch(true)
				{
					case (sAttributeName=='className' || sAttributeName=='class'):;
					c =  trimString( oNode['className']);
					break;

					case (sAttributeName=='tagName' || sAttributeName=='nodeName'):;
					c =  trimString( oNode['tagName']).toUpperCase();
					sAttributeValue = sAttributeValue.toUpperCase();
					re = new RegExp("(^|\\s)"+  trimString( sAttributeValue ) +"(\\s|$)");
					break;
					
					default:;
					c =  trimString( (sa ||  oNode[sAttributeName] ) + '' );
					break;
				};
				
			}catch(err){return null;};
			
		


			if( c == null ){return null;}
			else
			{
				switch(true)
				{
				

					case ( (sAttributeValue=='*') && (sa!=null) && (sa!='') ) : ;
					return oNode ;
					break;

					case (  (sAttributeValue!='*') && re.test(c) ): ;
					return oNode ;
					break;
					
					default:;
					oNode = oNode['parentNode'];
					if(!oNode){return null; };	
				};
				
				
			};
		};
		
		
	};
	
	var aSupportInteractionEvent = ('click,mousedown,mouseup,mouseover,mouseout,mousemove,mousewheel,keydown,keyup,keypress,focus,blur').split(',');
	Interaction.SupportInteractionEvent = {};
	while(aSupportInteractionEvent.length>0)
	{
		Interaction.SupportInteractionEvent[aSupportInteractionEvent.shift()] = 1;
	};
	
	
	Interaction.addListener = function ( dInteractionTarget ,sEventType, fCallback ,   sAttributeName , sAttributeValue , bStopEvent , iMaxLevel  )
	{
	
	
		if(!fCallback ){  fCallback = function(){};};
		
		var sElementId = null;
		if(typeof( dInteractionTarget ) =='string')
		{
			
			var obj =  document.getElementById(dInteractionTarget);
			if(! obj )
			{
				sElementId = dInteractionTarget;
				dInteractionTarget = document;				
			};
		};	
		
	

		var trimString=function(sString){return sString.replace(/(^\s+)|\s+$/g,"");};
		sEventType =  trimString( sEventType + '').toLowerCase();
		if(!Interaction.SupportInteractionEvent[ sEventType ])
		{
			return true;
		};

		
		var InteractionHandler = function(e,oArg)
		{
			
			var oNode = e.target || e.srcElement;
			var sName = sAttributeName || 'className';		
			
			oNode = Interaction.popElementByAttribute(	oNode ,sName , sAttributeValue ,iMaxLevel);
			
			if(oNode)
			{
				if(!e.target){e.target = e.srcElement ;};
				
				fCallback(e,oNode,dInteractionTarget);
				if( bStopEvent)
				{
					YAHOO.util.Event.preventDefault(e);
					YAHOO.util.Event.stopPropagation(e);					
					return false;
				}
				else
				{
					return true;
				};
			};
		};
		YAHOO.util.Event.addListener(dInteractionTarget,sEventType,InteractionHandler);
		
		
		if(sElementId)
		{
			dInteractionTarget = document;
			var findElementAfterWindowOnload = function()
			{
				dInteractionTarget = document.getElementById(sElementId);
				YAHOO.util.Event.removeListener(window,'load',findElementAfterWindowOnload);
				YAHOO.util.Event.removeListener( document ,sEventType, InteractionHandler  );
				YAHOO.util.Event.addListener(dInteractionTarget,sEventType,InteractionHandler);
			};
			
			YAHOO.util.Event.addListener(window,'load',findElementAfterWindowOnload);
			
		};
		

		return InteractionHandler;
	};

	Interaction.removeListener = function ( dInteractionTarget ,sEventType, fCallback  )
	{
		YAHOO.util.Event.removeListener(dInteractionTarget,sEventType,fCallback );
	};
};








YAHOO.util.Browser = new function()
{
	var B = this;
	B["ie"] = !!( document.all && !window.opera );
	B["ie7+="] = !!(B["ie"] && window.XMLHttpRequest);
	B["ie7-"] = !!(	B["ie"] && !B["ie7+="]	);
	
};





YAHOO.util.setTimeout2= function (fn,ms,param)
{		
		
		if(typeof(fn)=='function')
		{
			
			return (function (fn,ms,param)
			{
				var fo = function ()
				{								
					fn(param);
				};			
				return setTimeout(fo,ms);
			})(fn,ms,param);
		}
		else if(typeof(fn)=='string')
		{
			return  setTimeout(fn,ms);
		}
		else
		{
			throw Error('setTimeout2 Error\nInvalid function type');
		};
};




YAHOO.util.Event.unregisterCustomEvent =  function(oTarget,sEventType)
{
	
	
	
	if(!oTarget)
	{
		return false;
	};
	
	if(!oTarget['_CustomEvent_'])
	{
		oTarget['_CustomEvent_'] = {};		
	};
	if(sEventType)
	{		
		sEventType =( '_CustomEvent_' +  sEventType  ).toUpperCase(); 
		var customEvents = oTarget['_CustomEvent_'];
		delete customEvents[ sEventType ];
	}
	else
	{
		return false;
	};

};

YAHOO.util.Event.registerCustomEvent   =  function(oTarget,sEventType)
{
	
	
	if(!oTarget)
	{
		return false;
	};
	
	if(!oTarget['_CustomEvent_'])
	{
		oTarget['_CustomEvent_'] = {};		
	};
	if(sEventType)
	{
		var customEvents = oTarget['_CustomEvent_'];
		var sEventTypeRow = sEventType.toUpperCase(); 
		sEventType =( '_CustomEvent_' +  sEventType  ).toUpperCase(); 
		if( !customEvents[sEventType])
		{
			customEvents[sEventType] = new YAHOO.util.CustomEvent(sEventTypeRow,oTarget);
			
			oTarget.addCustomEvent = function(sEventType,callback,arg)
			{
				sEventType =( '_CustomEvent_' +  sEventType  ).toUpperCase(); 
				var customEvents = this['_CustomEvent_'];
				if( customEvents[sEventType] && customEvents[sEventType]['subscribe']  )
				{
					customEvents[sEventType]['subscribe'](callback,arg,true);
					
					return true;
				}
				else
				{
					return false
				};
			};
			
			oTarget.removeCustomEvent = function(sEventType,callback)
			{
				sEventType =( '_CustomEvent_' +  sEventType  ).toUpperCase(); 
				var customEvents = this['_CustomEvent_'];
				if( customEvents[sEventType] && customEvents[sEventType]['subscribe']  )
				{
					customEvents[sEventType]['unsubscribe'](callback);
					return true;
				}
				else
				{
					return false
				};
			};

			oTarget.fireCustomEvent = function(sEventType,arg)
			{
				arg = arg || this;
				sEventType =( '_CustomEvent_' +  sEventType  ).toUpperCase(); 
				var customEvents = this['_CustomEvent_'];
				
				if( customEvents[sEventType]   )
				{
					customEvents[sEventType]['fire'](arg);
					return true;
				}
				else
				{
					return false
				};
			};
		};
	}
	else
	{
		return false;
	};
};





YAHOO.util._guid = Date.parse(new Date);
YAHOO.util.getUniqueId = function()
{
	return  YAHOO.util._guid++;
};


	

YAHOO.util.Dom.getUniqueElementId = function(oNode)
{
	var gid = function()
	{
		return  [ '_YUNIQUEID' , YAHOO.util.getUniqueId() , Math.ceil( Math.random() * 100000 ), Date.parse(new Date) ].join('_');
	};
	
	if(!oNode)
	{
		var sid = gid();
		while(document.getElementById(sid))
		{
			sid = gid();
		};
		return sid;
	}
	else
	{
		if(oNode.id=='')
		{
			var sid = gid();
			while(document.getElementById(sid))
			{
				sid = gid();
			};
			oNode.id =  sid;
			return sid;
		}
		else
		{
			return oNode['id'];
		};	
	};
};

YAHOO.util.Dom.createIframe = function(oArguments,oApprenTarget)
{
	
	var F  = null;
	
	if( typeof(oArguments) == 'string')
	{
		/*url*/
		oArguments = 
		{
			src:oArguments
		};
	}
	else if( typeof(oArguments) != 'object')
	{
		oArguments = {};
	};


	oApprenTarget = oApprenTarget || document.body;
	var fid = YAHOO.getUniqueElementId();
	
	if(typeof(oArguments)=='object')
	{
		
		fid =  oArguments['name'] || oArguments['id']  || fid;
	
	};
	

	var defaultArgs = 
	{
			id:fid,
			name:fid,				
			frameborder:'0',
			framemargin:'0',
			framespace:'0',
			scrolling:'auto'
	};
	for(var j in defaultArgs)
	{
		if( oArguments[j] == null)
		{
			oArguments[j] = defaultArgs[j];			
		};
	};
	
	
	
	if(window.XMLHttpRequest || document.compatMode)
	{	/*Gecko, Safari, Opera, IE6*/
	
		F = document.createElement(	'iframe');
		for(i in oArguments)
		{
			F.setAttribute(i,oArguments[i]);				
		};
		oApprenTarget.appendChild(F);
	}
	else if(window.XMLHttpRequest ==null && document.uniqueID)
	{
		/*for IE5+ need different way to get <IFRAME>*/
		var properties = [];
		var j=0;
		for(var i in oArguments)
		{
			properties[j] = [i,'=',oArguments[i]].join('');
			j++;
		};
		properties = properties.join(' ');
	
		F = document.createElement(	['<IFRAME' , properties , '></IFRAME>'].join(' ')); 
		oApprenTarget.insertAdjacentHTML('BeforeEnd',F.outerHTML);
		F = null;
		
		F = document.getElementById(	oArguments['id'] );

		
		if(!F.contentWindow)
		{
	
			F.contentWindow = document.frames[F.getAttribute('id')];
			F.contentDocument = F.contentWindow.document;
		
		};
	}
	return F;


};



YAHOO.util.Dom.getElementsByClass = function(sClassName,sTagName,oNode,iMaxLength)
{
	
	if(!sClassName){return [];};
		sTagName = sTagName || "*";
		oNode = oNode || document;

		var a = [];		
		var els = oNode.getElementsByTagName(sTagName);
		var elsLen = els.length;
		
		var pattern = new RegExp("(^|\\s)"+sClassName+"(\\s|$)");
		for (i = 0, j = 0; i < elsLen; i++)
		{
			if ( pattern.test(els[i].className) ) 
			{
				a[j] = els[i];
				j++;
				if(!isNaN( iMaxLength ))
				{
					iMaxLength--;
					if(iMaxLength<=0){return a;};
				};
			}
		};
		return a;
	
};



YAHOO.util.Dom.popElementByClass = function(oNode,sClass,iMaxLevel)
{
	
	sClass = YAHOO.util.String.trim( sClass );
	var re = new RegExp("(^|\\s)"+ sClass +"(\\s|$)");
	
	while(oNode)
	{
		
		if(iMaxLevel!=null)
		{
			if( iMaxLevel<0){return null;}	
			else{ iMaxLevel--;};				
		};
		
		
		var c = oNode['className']  ;
		
		if(c==null ){return null;}
		else
		{
			c = YAHOO.util.String.trim( c ) ;
			if (  re.test(c) ) {return oNode;}		
			else
			{				
				oNode = oNode['parentNode'];
				if(!oNode){return null;};
			};
		};
	};
	return null;
};



YAHOO.util.Dom.getNextElementByTag = function(oNode,sTag)
{
	
	while(oNode['nextSibling'])
	{
		oNode = oNode['nextSibling'];
		var t = oNode.tagName;
		
		if(t && t==sTag.toUpperCase())
		{			
			return oNode;
		};
	};

	return null;
};

YAHOO.util.Dom.getPreviousElementByTag = function(oNode,sTag)
{
	
	while(oNode['previousSibling'])
	{
		oNode = oNode['previousSibling'];
		var t = oNode.tagName;
		
		if(t && t==sTag.toUpperCase())
		{			
			return oNode;
		};
	};

	return null;
};


YAHOO.util.Dom.addClass = function(oNode,sClassName)
{
			var pattern = new RegExp("(^|\\s)"+sClassName+"(\\s|$)");
			
			var c = oNode['className'];
			if(!pattern.test(c))
			{
				oNode['className'] = [c,sClassName].join(' ');
			};
};

YAHOO.util.Dom.removeClass = function(oNode,sClassName)
{
			var pattern = new RegExp("(^|\\s)"+sClassName+"(\\s|$)");
			
			var c = oNode['className'];
			
			if(pattern.test(c))
			{
				oNode['className'] = c.replace( pattern, '');
			};
};

YAHOO.util.Dom.hasClass = function(oNode,sClassName)
{
		var pattern = new RegExp("(^|\\s)"+sClassName+"(\\s|$)");
		return pattern.test(oNode['className'])
};



