<?php
	
	$parent_tab = 'TAB_ARTWORKS';
	
	$pgnum = 1;
	$frm_submit = "$me?b=artall";
	$comboHTML = false;
	$srctxt = false;
	$frmname = "allart";
	
	$gid = false;
	if(isset($_GET['gid']))
	{
		$gid = base64_decode($_GET['gid']);
	}
	if(isset($_GET['gname']))
	{
		$gname = base64_decode($_GET['gname']);
	}
	
	if( isset( $_GET[ 'ac' ] ) )
	{
		if( isset( $_GET['Aid'] ) )
			$Aid = base64_decode( $_GET[ 'Aid' ] );
		if(isset($_GET['i_id']))
			$imgname = base64_decode( $_GET[ 'i_id' ] );
		
		if( $_GET[ 'ac' ] == 'ngrp' )
		{
			$newgrp = trim( $_POST[ 'newgrp' ] );
			$unqid = $hlp->getunqid( $newgrp );
			if( strlen( $newgrp ) > 0 )
			{
				if( strlen( $newgrp )<=40 )
				{
					$q = "insert into subgroups ( gid , subgroup ) values ( '$gid', '$newgrp' );";
					if( ( $res = $hlp->_db->db_query( $q ) ) !== false && ( intval( $hlp->_db->db_affected( $res ) ) === 1 ) )
						$hlp->echo_ok( "Subgroup \" $newgrp \" has been added." );
					else
						$hlp->echo_err( "Unable to add subgroup \"$newgrp\" , possibly that subgroup is already in the database" );
				}
				else
				{
					$hlp->echo_err( "Unable to add subgroup" );
					$hlp->echo_err( "Group name should be maximum 40 characters long" );
				}	
			}
			else
			{
				$hlp->echo_err( "Unable to add subgroup" );
				$hlp->echo_err( "Please specify subgroup" );
			}
		}
		
		if( $_GET[ 'ac' ] == 'd' )
		{
			@unlink("data/$imgname/original.jpeg");
			@unlink("data/$imgname/thumb.250.jpeg");
			
			exec("rm -rf data/$imgname",$outa,$ret);
			
			if( intval( $ret ) === 0 )
			{
				$q = "delete from artworks where Aid='$Aid';";
			
				$res = $hlp->_db->db_query( $q );
				
				if ( $res !== false)
					$hlp->echo_ok( "Artwork removed." );
				else
					$hlp->echo_err( "Sorry, unable to remove artwork." );
			}
			else
				$hlp->echo_err("Sorry, unable to remove artwork.");
		}
	}//ac set
	
	
	echo( '<div class="gencon icheight txt buttonmenuwithbg" >' );
	echo( $hlp->getLinkAncHtml('anewc',100,'asb rviewdash','#','addDynTabDirect("'.$parent_tab.'","New Artwork","org","New Artwork in '.$gname.'","'.$me.'?b=artnew&gid='.base64_encode( $gid ).'&gname='.base64_encode($gname).'")',20,'images/ic/newc.png','New') );
	
	echo( $hlp->getLinkAncHtml( 'anewc',100,'asb rviewdash','#','document.getElementById("frmnewgrp").style.display="block";',20,'images/ic/newc.png','New Subgroup' ) );
	
	echo( '</div>' );
		
	echo( " <div style='display:none;' name=frmnewgrp id=frmnewgrp >
					<form method=post action='$me?b=artall&ac=ngrp&gid=".base64_encode($gid)."' >
						<div style='padding:20px;' >
							<div style='width:70px;float:left;' >Subgroup:</div>
							<div style='width:320px;float:left;' ><input type=text name=newgrp style='width:300px;'></div>
							<div><button class=roundbutton type=submit >Add</button></div>
						</div>
					</form>	
				<div class='txtheadwithbg' >&#160;</div>
	</div>" );
	
	//echo( '</div>' );
	if( isset( $_GET['cbo'] ) )
	{
		$pgnum = $_POST['pageCombo'];
		$srctxt = trim( $_POST[ 'cbosrctxt' ] );
	}	
	
	if( isset( $_POST[ 'srctxt' ] ) )
		$srctxt = trim( $_POST[ 'srctxt' ] );
	
	$allx = $hlp->_db->db_return( "select count(*) cnt from artworks;", array( 'cnt' ) );
	$allcnt = intval( $allx[0] );
	if( $allcnt > 0 )
	{
		if( $gid )
		{
			$q = "select count(*) cnt from artworks  where Aname like '%$srctxt%' and gid='$gid';";
			$frm_submit .='&gid='.base64_encode($gid);
		}
		$cntx = $hlp->_db->db_return( $q, array( "cnt" ) );
		$cnt = intval( $cntx[0] );
		
		if( $cnt > 0 )
		{ 
			$q="select Aid, Aname , Adesc , gid , Adate , Acanvas , Ayear , Acollection ,AimgName , subgroup from artworks where Aname like '%$srctxt%' ".( ( $gid) ? ( " and gid = '$gid' ") : ('') ). " order by Adate  desc ";  
			
			
			$startIndex = ( ($pgnum-1)*$ART_SHOW_PER_PAGE );	
			
			if( $cnt > $ART_SHOW_PER_PAGE )
			{
				$comboHTML = $hlp->getDisplayPageComboHTML( $parent_tab,$cnt,$frm_submit."&cbo",$frmname,$pgnum,$ART_SHOW_PER_PAGE);
				
				$q .= " LIMIT $startIndex,$ART_SHOW_PER_PAGE ;";
			}
			
			$res = $hlp->_db->db_query( $q );	
			if( $res )
			{
				$showNumRow = intval( $hlp->_db->db_num_rows( $res ) );
						
				if( ( $startIndex + 1 ) === ( $showNumRow + $startIndex ) && $pgnum == 1 )
					echo( '<div style="padding-top:15px;"><div class="txtheadwithbg" >Showing 1 of 1.</div></div>' );
				else
					echo( '<div style="padding-top:15px;"><div class="txtheadwithbg" >Showing '.( $startIndex + 1 )." to ".( $showNumRow + $startIndex ).' of '.$cnt.'.</div></div>' );
				
				$hlp->searchBox( $parent_tab, $frm_submit, $srctxt, $comboHTML, $frmname, false, false );
				
				while( ( $row = $hlp->_db->db_get( $res ) ) !== false )
				{
					$Aid = $row[ 'Aid' ];
		
					$Adate = date( 'l, d F Y',strtotime( $row[ 'Adate' ] ) );
					$Aname = $row[ 'Aname' ];
					$gid=$row[ 'gid' ];
					$Ayear = $row[ 'Ayear' ];         
					$Acollection = $row[ 'Acollection' ];
					$Acansize = $row[ 'Acanvas' ];
					$Adesc = $row[ 'Adesc' ];
					$AimgName = $row[ 'AimgName' ];
					$subgroup = $row[ 'subgroup' ];
					
							//style="color:#10647e;"	
					$thumbPath = "data/$AimgName/thumb.250.jpeg";
					
					$Acollection = intval($Acollection);
					switch($Acollection)
					{
						case 0 : $Acollection = 'Available';break;
						case 1 : $Acollection = 'Private';break;
						case 2 : $Acollection = 'Institutional';break;
					}
					
					$caption_style = "color:#8e8e8e;float:left;width:80px;";
					$val_style = "color:#3f95b1;white-space:normal;width:610px;padding-left:5px;";
					echo( '<div name=entrydiv class="gencon bviewdash" style="white-space:nowrap;width:100%;padding-top:10px;" ><table name=tbl class=txt  style="background-color:#f8f8f8;" border=0 width=100%>' );
					echo( '<tr valign=top><td align=left valign=top style="width:400px;">
						<div ><table class=txt valign=top><tr valign=top><td valign=top>' );
					if( file_exists( $thumbPath ) )	
						echo( '<div style="float:left" ><img width=150 src="'.$thumbPath.'" />&#160;&#160;&#160;</div></td><td>' );
						echo( '<div id="txt" style = color:#10647e;><b>'.$Aname.'</b></div>' );
						echo( '<div style="padding-top:7px;" >
								<table class=txt style="width:300px;" valign=top>
									<tr valign=top>
										<td valign=top style="'.$caption_style.'" ><b>Date:</b></td><td style="'.$val_style.'" >'.$Adate.'&#160;</td>
									</tr> 
									<tr valign=top>
										<td valign=top style="'.$caption_style.'" ><b>Canvas size:</b></td><td style="'.$val_style.'" >'.$hlp->trimText($Acansize).'&#160;</td>
									</tr>
									<tr valign=top>
										<td valign=top style="'.$caption_style.'" ><b>Year:</b></td><td style="'.$val_style.'" >'.$Ayear.'&#160;</td>
									</tr>
									<tr valign=top>
										<td valign=top style="'.$caption_style.'" ><b>Subgroup:</b></td><td style="'.$val_style.'" >'.$subgroup.'&#160;</td>
									</tr>
									<tr valign=top>
										<td valign=top style="'.$caption_style.'" ><b>Collection:</b></td><td style="'.$val_style.'" >'.$Acollection.'&#160;</td>
									</tr>
									<tr valign=top>	
										<td valign=top style="'.$caption_style.'" ><b>Desc:</b></td><td style="'.$val_style.'" >'.$hlp->trimText( $Adesc ).'&#160;</td>
									</tr>
				
								</table>		
							<div></td></tr></table></td><td valign=top><div style="float:right;">' );
					
					echo( $hlp->getLinkAncHtml( 'aeditm',60,'asb ','#','tt="Edit :'.addslashes( $Aname ).'";addDynTabMain("'.$parent_tab.'","Edit ","'.addslashes( $Aname ).'",tt,"'.$me.'?b=artedt&Aid='.base64_encode( $Aid ).'",true, true);',20,'images/ic/itick.gif','Edit',$parent_tab ) );
					
					
					echo( $hlp->getLinkAncHtml( 'aeditm',60,'asb ',$me.'?b=artall&ac=d&Aid='.base64_encode( $Aid ).'&gid='.base64_encode( $gid ).'&i_id='.base64_encode( $AimgName ),'confirm( "Are you sure, you want to delete \"'.addslashes( $Aname ).'\"?" )',20,'images/ic/itick.gif','Delete',$parent_tab,true ) );
					
					
					echo( '</div></td></tr>' );
						
					echo( '</table></div>' );
				}	
			}
			else
				echo( "<div style='padding:20px;' >No artworks to show for search text \"$srctxt\".</div>" );	
		}
		else
		{
			echo( '<div style="padding-top:15px;"><div class="txtheadwithbg" >Showing 0 of 0.</div></div>' );
			$hlp->searchBox( $parent_tab, $frm_submit, $srctxt, $comboHTML, $frmname, false, false );
			echo( "No artworks to show." );	
		}	
	}
	else
		echo( "<div style='padding:20px;' >No artworks to show.</div>" );
		

?>