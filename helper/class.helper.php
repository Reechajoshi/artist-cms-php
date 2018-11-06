<?php
	include_once( "class.db.php" );
	
	class chlp
	{
		var $_isIE = false;
		
		var $_db = false;
		var $_db_datastore = false;
		
		function chlp($db_connect = true)
		{
			GLOBAL $DB_NAME, $DB_USER, $DB_PASS;
			
			if( $db_connect )
				$this->_db = new cdb( $DB_NAME, $DB_USER, $DB_PASS );
			
			$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
			$this->_isIE = (strpos($ua,"msie")!==false);
			$this->_isGecko = (strpos($ua,"gecko")!==false);
			
			@session_start();
		}
		
		function get_home_banner()
		{
			$max_banner_i = 7; 
			$bi = 1;
			
			if(isset($_SESSION['hbanner']))
				$bi = 1 + intval($_SESSION['hbanner']);
			else
				 $bi = floor( rand(1,($max_banner_i+1)) );
			
			if($bi>$max_banner_i) { $bi=1; }
			$_SESSION['hbanner'] = $bi;			
			return($_SESSION['hbanner']);
		}
		
		function echo_err($m)
		{
			$c='txtheadwithbg';
			echo("<div class='$c gencon'> $m </div>");
		}
		
		function echo_ok($m)
		{
			$c='txtheadwithbg';
			echo("<div class='$c gencon'> $m </div>");
		}
		
		function echoFileHeader($contenttype,$filename,$size,$asattachment = true)
		{
			header( "Content-Type: $contenttype" );
			header( "Content-Disposition: ".( ( $asattachment )?( "attachment" ):( "inline" ) )."; filename=\"".$filename."\"");
			header( "Accept-Ranges: bytes" );
			header( "Content-Length: $size" );
			header( "Connection: keep-alive" );
		}
		
		function trimText($str,$size=100)
		{
			if( strlen( $str ) > ($size - 3) )
				return( substr( $str,0,$size-3 )."..." );
			else
				return( $str );
		}
		
		function getunqid($s)
		{
			return(md5(uniqid(time(),true).$s));
		}
		
		function is_backend_login_ok()
		{
			$u = $_SERVER["PHP_AUTH_USER"];
			$p = $_SERVER["PHP_AUTH_PW"];
			return( ( $u == 'jamie' && ( $p == 'pin' ) ) );
		}
	}
?>