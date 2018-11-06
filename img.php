<?php
	if( isset( $_GET['i'] ) && isset( $_GET['t'] ) )
	{
		$imgdir = $_GET['i'];
		$imgtype = intval($_GET['t']);
		switch($imgtype)
		{
			case 0 : $imgtype = 'thumb.150.png'; break;
			case 1 : $imgtype = 'thumb.250.jpeg'; break;
			case 2 : $imgtype = 'main.jpeg'; break;
			default: $imgtype = ''; break;
		}
		
		$img_file = "backend/data/${imgdir}/${imgtype}";
		if( file_exists( $img_file ) )
		{
			//header("Pragma: public");
			//header("Expires: 0");
			//header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			//header("Cache-Control: private",false); 
			header("Accept-Ranges: bytes");
			header("Content-Length: ".filesize($img_file));
			header("Content-Type: image/jpeg" );
			header("Content-Transfer-Encoding: binary");

			readfile($img_file);
		}
	}
?>