<?php
	if(isset($_GET['s']))
	{
		$subg = ($_GET['s']);
		$q = "select Aname, Ayear, Acanvas, Adesc, Acollection, AimgName from artworks where gid='${GROUP_I}' and subgroup='${subg}'";
	}
	else
		$q = "select Aname, Ayear, Acanvas, Adesc, Acollection, AimgName from artworks where gid='${GROUP_I}'";
		
	$res = $hlp->_db->db_query( $q );

	if( $res )
	{
		$pthumb_info = "<div class='thumbAll captionHover'></div>";
		$ix = 0;
		
		while( ( $row = $hlp->_db->db_get( $res ) ) !== false )
		{
			if( ($ix++ % 3) == 0 )
				echo($pthumb_info);
				
			$atitle = $row['Aname'];
			$ayear = $row['Ayear'];
			$acanvas = $row['Acanvas'];
			$adesc = $row['Adesc'];
			
			$acoltypes = intval($row['Acollection']);
			switch($acoltypes)
			{
				case 0 : $acoltypes = 'Is Available'; break;
				case 1 : $acoltypes = 'In private collection'; break;
				case 2 : $acoltypes = 'In institutional collection'; break;
				default: $acoltypes = ''; break;
			}
			
		//	$athumb = '/img.php?i='.$row['AimgName'].'&t=1';
			$athumb = 'backend/data/'.$row['AimgName'].'/thumb.250.jpeg';
			$afull = 'backend/data/'.$row['AimgName'].'/main.jpeg'; ///img.php?i='.$row['AimgName'].'&t=2';
			
			echo('<div class="thumbAll">');
			echo('<a href="'.$afull.'" class="thumbnailBlockImage"><img id=lz'.$ix.' data-old="img/dum_load.png" src="'.$athumb.'" alt="" ></a>');
			echo('<div class="captionThumb">');
			echo("<p>${atitle}</p><p>${ayear}</p><p>${acanvas}</p><p>${adesc}</p><p>${acoltypes}</p>");
			echo('</div></div>');echo("\n\n");
		}

		$hlp->_db->db_free($res);
	}	
?>