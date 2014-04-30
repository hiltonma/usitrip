	function div_view_statu(obj,statu)
	{
		if (statu=="close")
		{
			document.getElementById(obj).style.display="none";
		}
		if (statu=="show")
		{
			document.getElementById(obj).style.display="";
		}
	}