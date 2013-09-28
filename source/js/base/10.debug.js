if (XUI.debug)
	XUI.assert = function(con, message) 
	{ 
		if(con) return;
		alert(message);
		throw message;  
	};
else
	XUI.assert = function() {};