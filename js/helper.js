CUtil = window.prototype = {

	delCookie: function(name)
	{
		CUtil.setCookie(name,"",-1);
	},

	setCookie: function( name, value, expires, path, domain, secure ) 
	{
		// set time, it's in milliseconds
		var today = new Date();
		today.setTime( today.getTime() );

		//day to ms = expires * 1000 * 60 * 60 * 24;
		if (expires)
			expires = new Date( today.getTime() + (expires) ); 

		document.cookie = name + "=" +escape( value ) +
		( ( expires ) ? ";expires=" + expires.toGMTString() : "" ) + 
		( ( path ) ? ";path=" + path : "" ) + 
		( ( domain ) ? ";domain=" + domain : "" ) +
		( ( secure ) ? ";secure" : "" );
	},

	getCookie: function(check_name)
	{
		var a_all_cookies = document.cookie.split( ';' );
		var a_temp_cookie = '';
		var cookie_name = '';
		var cookie_value = '';
		var b_cookie_found = false;
		
		for ( i = 0; i < a_all_cookies.length; i++ )
		{
			a_temp_cookie = a_all_cookies[i].split( '=' );
			
			cookie_name = a_temp_cookie[0].replace(/^\s+|\s+$/g, '');
		
			if ( cookie_name == check_name )
			{
				b_cookie_found = true;
				if ( a_temp_cookie.length > 1 )
				{
					cookie_value = unescape( a_temp_cookie[1].replace(/^\s+|\s+$/g, '') );
				}
				return cookie_value;
				break;
			}
			a_temp_cookie = null;
			cookie_name = '';
		}

		if ( !b_cookie_found )
			return null;
	}
};