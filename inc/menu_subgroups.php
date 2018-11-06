<?php
	//$q = "select distinct subgroup from artworks, groups where artworks.gid='${group_id_for_menu}'";
	
	$q = "select distinct subgroup from subgroups where subgroups.gid='${group_id_for_menu}';";
	$res = $hlp->_db->db_query( $q );
			
	if( $res )
	{
		echo('<ul>');
		while( ( $row = $hlp->_db->db_get( $res ) ) !== false )
		{
			$sg = trim($row['subgroup']);
			$sg_enc=urlencode($sg);
			if($sg != null && strlen($sg)>0)
				echo("<li><a href='portfolio.html.php?g=${group_id_for_menu}&s=${sg_enc}'>${sg}</a></li>");
		}
		echo('</ul>');
		$hlp->_db->db_free($res);
	}	
?>