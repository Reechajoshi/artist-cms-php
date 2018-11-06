<?php
	header("Expires: Mon, 26 Jul 1990 05:00:00 GMT");
	header("Cache-Control: no-cache");
	header("Pragma: no-cache");

	require( 'conf/vars.php' );
	require( 'helper/class.helper.php' );
	
	$me = $_SERVER[ "PHP_SELF" ];
	$hlp = new chlp();
	
	echo('<html><head>');	
	if( $_GET['a']=='t' )
	{
		echo( '</head>' );
		require( 'inc/frmtop.php' );
	}
	else if( $_GET['a']=='r' )
	{
		require( 'inc/head.php' );//only required for TAB
		require( 'inc/menu_main.php' );
		echo( '</head>' );
		echo( '<body onload=menuInit();></body>' );
	}
	else if( $_GET['a']=='art' )
	{
		require( 'inc/head.php' );//only required for TAB
		require( 'inc/artworks/menu.php' );
		echo( '</head>' );
		echo( '<body onload=menuInit();></body>' );
	}
	else if( $_GET['a']=='cont' )
	{
		require( 'inc/head.php' );//only required for TAB
		require( 'inc/content/menu.php' );
		echo( '</head>' );
		echo( '<body onload=menuInit();></body>' );
	} // for content
		
	else if( $_GET['a']=='leit' )
	{
		require( 'inc/head.php' );//only required for TAB
		require( 'inc/leitmotif/menu.php' );
		echo( '</head>' );
		echo( '<body onload=menuInit();></body>' );
		
	} // for leitmotif		
	
	else if( $_GET['a']=='img' )
	{
		require( 'inc/head.php' );//only required for TAB
		require( 'inc/images/menu.php' );
		echo( '</head>' );
		echo( '<body onload=menuInit();></body>' );
	}
	else if( $_GET['b'] == 'grpall' )
	{
		require( 'inc/head.php' );
		echo( '<link rel="stylesheet" type="text/css" href="styles/ui.css.x">' );
		echo('</head>');
		require( 'inc/artworks/groups.php' );
	}
	else if( $_GET['b'] == 'artall' )
	{
		require( 'inc/head.php' );
		require( 'inc/artworks/artall.php' );
	}
	else if( $_GET['b'] == 'artnew')
	{
		echo( '<link rel="stylesheet" type="text/css" href="styles/ui.css.x">' );
		echo( "</head>" );
		require( 'inc/artnew.php' );
	}	
	else if( $_GET['b'] == 'artedt' )
	{	
		echo( '<link rel="stylesheet" type="text/css" href="styles/ui.css.x">' );
		echo( "</head>" );
		require( 'inc/artworks/edit.php' );
	}
	else if( $_GET['b'] == 'contall' ) // for content tab
	{
		require( 'inc/head.php' );
		require( 'inc/content/contall.php' );
	}	
	
	
	else if( $_GET['b'] == 'contnew' ) // for currently 
	{
	
		echo( '<link rel="stylesheet" type="text/css" href="styles/ui.css.x">' );
		echo( "</head>" );
		require( 'inc/content/contnew.php' );
	}
	
	else if( $_GET['b'] == 'contedt' )
	{	
		echo( '<link rel="stylesheet" type="text/css" href="styles/ui.css.x">' );
		echo( "</head>" );
		require( 'inc/content/edit.php' );
	}
	else if( $_GET['b'] == 'leitall' ) // for leitmotif tab
	{
		require( 'inc/head.php' );
		require( 'inc/leitmotif/leitall.php' );
		
	}	
	else if( $_GET['b'] == 'leitnew' ) // for leitmotif 
	{
		
		echo( '<link rel="stylesheet" type="text/css" href="styles/ui.css.x">' );
		echo( "</head>" );
		require( 'inc/leitmotif/leitnew.php' );
		
	}
	else if( $_GET['b'] == 'leitedt' )
	{	
		
		echo( '<link rel="stylesheet" type="text/css" href="styles/ui.css.x">' );
		echo( "</head>" );
		require( 'inc/leitmotif/edit.php' );
		
	}
	
	else if( $_GET['b'] == 'imageupload' )
		require( "Fileuploader/uploader.html" );
	
	else if( $_GET['b'] == 'imgall' ) // for content tab
	{
		require( 'inc/head.php' );
		require( 'inc/images/imageall.php' );
	}
	else if( $_GET['b'] == 'imgnew' ) // for content tab
	{
		echo( '<link rel="stylesheet" type="text/css" href="styles/ui.css.x">' );
		echo( "</head>" );
		require( 'inc/images/imgnew.php' );
	}
		
	else
	{
		echo( "<head></head><frameset rows='40,*'>
			<frame src='$me?a=t' frameborder=0 marginheight=0 marginwidth=0 name=ft noresize=noresize scrolling=no />
			<frame src='$me?a=r' frameborder=0 marginheight=0 marginwidth=0 name=fb noresize=noresize scrolling=auto />
		</frameset>" );
	}
	echo('</html>');
?>