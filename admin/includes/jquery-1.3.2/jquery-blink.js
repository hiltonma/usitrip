(function($)
{
	$.fn.blink = function(options)
	{
		var defaults = { delay:350 };
		var options = $.extend(defaults, options);
		
		return this.each(function()
		{
			var obj = $(this);
			setInterval(function()
			{
				if($(obj).css("color") == "red")
				{
					$(obj).css('color','darkblue');
				}
				else if($(obj).css("color") == "darkblue")
				{
					$(obj).css('color','green');
				}
				else if($(obj).css("color") == "green")
				{
					$(obj).css('color','darkorange');
				}
				else
				{
					$(obj).css('color','red');
				}
			}, options.delay);
		});
	}
}(jQuery))