<?php

	$parent_tab = 'TAB_ARTWORKS';
	$pgnum = 1;
	$frm_submit = "$me?b=grpall";
	$comboHTML = false;
	$srctxt = false;
	$frmname = "allgrp";
	
	if( isset( $_GET[ 'ac' ] ) )
	{
		if( $_GET[ 'ac' ] == 'ngrp' )
		{
			$newgrp = trim( $_POST[ 'newgrp' ] );
			$unqid = $hlp->getunqid( $newgrp );
			if( strlen( $newgrp ) > 0 )
			{
				if( strlen( $newgrp )<=40 )
				{
					$q = "insert into groups ( gid , gname , gcreatedate , gmodifydate ) values ( '$unqid', '$newgrp',now(),now() );";
					if( ( $res = $hlp->_db->db_query( $q ) ) !== false && ( intval( $hlp->_db->db_affected( $res ) ) === 1 ) )
					$hlp->echo_ok( "Group \" $newgrp \" has been added." );
					else
					$hlp->echo_err( "Unable to add group \"$newgrp\"." );
				}
				else
				{
					$hlp->echo_err( "Unable to add group" );
					$hlp->echo_err( "Group name should be maximum 40 characters long" );
				}	
			}
			else
				{
					$hlp->echo_err( "Unable to add group" );
					$hlp->echo_err( "Please specify group name." );
				}
		}
	
		else if( $_GET[ 'ac' ] == 'd' )
		{
			$gid = base64_decode( $_GET[ 'gid' ] );
			$artcnt = base64_decode( $_GET[ 'artcnt' ] );
			if( $artcnt < 1)
			{
				$q = "delete from groups where gid='$gid';";
				
				if( $hlp->_db->db_query( $q ) )
					$hlp->echo_ok( "Group has been removed." );
				else
					$hlp->echo_err( "Sorry, unable to remove group." );
			}
			else
			{
			//	$hlp->echo_err( "Sorry, unable to remove group." );
				$hlp->echo_err("Please remove the artworks from the group");
			}
		}
		else if ( $_GET ['ac'] == 'renamegrp' )
		{
			$gid = base64_decode( $_GET[ 'gid' ] );
			$newn = trim($_GET['newn']);
			if(strlen( $newn ) > 0 )
			{
				if(strlen( $newn ) <= 40)
				{
					if( $hlp->renameGroup( $gid,$newn ) )
					$hlp->echo_ok( "Group $newn has been renamed");
					else
					$hlp->echo_err( "Renaming failed" );
				}
				else
				$hlp->echo_err( "Group name should be maximum 40 characters long" );
				
			}
			else
				$hlp->echo_err( "Group name cannot be blank" );
		}
	}
	
	echo( '<div class="gencon icheight txt buttonmenuwithbg" >' );
	
	echo( $hlp->getLinkAncHtml( 'anewc',100,'asb rviewdash','#','document.getElementById("frmnewgrp").style.display="block";',20,'images/ic/newc.png','New' ) );
	/*
		echo( $hlp->getLinkAncHtml( 'artworks',60,'asb ','#','tt="Artworks of  :'.addslashes( $gname ).'";addDynTabDirect("'.$parent_tab.'","View ","'.addslashes( $gname ).'",tt,"'.$me.'?b=artall&gid='.base64_encode( $gid ).'&gname='.base64_encode( $gname ).'",true, true);',20,'images/ic/itick.gif','Artworks',$parent_tab ) );
	*/
		echo( '</div>' );
		
		echo( " <div style='display:none;' name=frmnewgrp id=frmnewgrp >
					<form method=post action='$me?b=grpall&ac=ngrp' >
						<div style='padding:20px;' >
							<div style='width:70px;float:left;' >Group:</div>
							<div style='width:320px;float:left;' ><input type=text name=newgrp style='width:300px;'></div>
							<div><button class=roundbutton type=submit >Add</button></div>
						</div>
					</form>	
				<div class='txtheadwithbg' >&#160;</div>
	</div>" );

	
	if( isset( $_GET['cbo'] ) )
	{
		$pgnum = $_POST['pageCombo'];
		$srctxt = trim( $_POST[ 'cbosrctxt' ] );
	}	
	
	if( isset( $_POST[ 'srctxt' ] ) )
		$srctxt = trim( $_POST[ 'srctxt' ] );

	$allcntx = $hlp->_db->db_return( "select count(*) cnt from groups;", array( 'cnt' ) );
	$allcnt = intval( $allcntx[0] );
	
	if( $allcnt > 0 )
	{
		$q = "select count(*) cnt from groups where gname like '%$srctxt%';";
		$cntx = $hlp->_db->db_return( $q, array( "cnt" ) );
		$cnt = intval( $cntx[0] );
		
		if( $cnt > 0 )
		{
			$q = "select *, (select count(*) cnt from artworks a where a.gid=g.gid) artcnt , (select max(Adate)from artworks where artworks.gid=g.gid ) maxtime from groups g where gname like '%$srctxt%' order by gcreatedate desc ";
			
			$startIndex = ( ($pgnum-1)*$GROUP_DISPLAY_PER_PAGE );	
			
			if( $cnt > $GROUP_DISPLAY_PER_PAGE )
			{
				$comboHTML = $hlp->getDisplayPageComboHTML( $parent_tab,$cnt,$frm_submit."&cbo",$frmname,$pgnum,$GROUP_DISPLAY_PER_PAGE);
				
				$q .= " LIMIT $startIndex,$GROUP_DISPLAY_PER_PAGE ;";
			}
			
			$res = $hlp->_db->db_query( $q );	
			if( $res )
			{
				$showNumRow = intval( $hlp->_db->db_num_rows( $res ) );
						
				if( ( $startIndex + 1 ) === ( $showNumRow + $startIndex ) && $pgnum == 1 )
					echo( '<div style="padding-top:15px;"><div class="txtheadwithbg" >Showing 1 of 1.</div></div>' );
				else
					echo( '<div style="padding-top:15px;"><div class="txtheadwithbg" >Showing '.( $startIndex + 1 )." to ".( $showNumRow + $startIndex ).' of '.$cnt.'.</div></div>' );
				
				$hlp->searchBox( $parent_tab,$frm_submit,$srctxt,$comboHTML,$frmname,false,false );
				
				while( ( $row = $hlp->_db->db_get( $res ) ) !== false )
				{
					$gid = $row[ 'gid' ];
					$gcreatedate = date( 'l, d F Y',strtotime( $row[ 'gcreatedate' ] ) );
					$gmodifydate = date( 'l, d F Y',strtotime( $row[ 'gmodifydate' ] ) );
					$gname = $row[ 'gname' ];
					$artcnt = $row[ 'artcnt' ];
					$maxtime=date( 'l, d F Y',strtotime( $row[ 'maxtime' ] ) );
		
					if( $artcnt < 1)
					{
						$maxtime = $gmodifydate;
					}
		
					$caption_style = "color:#8e8e8e;float:left;width:80px;";
					$val_style = "color:#3f95b1;white-space:normal;width:610px;";
					
					echo( '<div name=entrydiv class="gencon bviewdash" style="white-space:nowrap;width:100%;padding-top:10px;" ><table name=tbl class=txt  style="background-color:#f8f8f8;" border=0 width=100%>' );
					echo( '<tr><td align=left valign=top style="width:800px;">
						<div ><table class=txt ><tr><td>' );
					
					echo( '<div id=txt style="color:#10647e;"><b>'.$gname.'</b></div>' );
					echo( '<div style="padding-top:7px;" >
							<table class=txt style="width:650px;" >
						<!--		<tr>
									<td style="'.$caption_style.'" ><b>Created:</b></td><td style="'.$val_style.'" >'.$gcreatedate.'&#160;</td>
								</tr>  
						-->
								<tr>
									<td style="'.$caption_style.'" ><b>Modified:</b></td><td style="'.$val_style.'" >'.$maxtime.'&#160;</td>
								</tr>
								<tr>
									<td style="'.$caption_style.'" ><b>Artworks:</b></td><td style="'.$val_style.'" >'.$artcnt.'&#160;</td>
								</tr>
							</table>		
						<div></td></tr></table></td><td><div>' );
	
					echo( $hlp->getLinkAncHtml( 'artworks',60,'asb ','#','tt="Artworks of '.addslashes( $gname ).'";addDynTabDirect("'.$parent_tab.'","View ","'.addslashes( $gname ).'",tt,"'.$me.'?b=artall&gid='.base64_encode( $gid ).'&gname='.base64_encode( $gname ).'",true, true);',20,'images/ic/itick.gif','Artworks',$parent_tab ) );
					
					echo($hlp->getLinkAncHtml('arenamem',65,'asb ','#','n=prompt("Enter new name",""); if(n && n!="") { document.location.replace("'.$me.'?b=grpall&ac=renamegrp&newn=" + escape(n) + "&gid='.base64_encode($gid).'",true, true); }else {if(n == null){/*... */} else {alert("Group name cannot be blank")}}',20,'images/ic/irename.gif','Rename'));
					
					/*
					echo($hlp->getLinkAncHtml('arenamem',65,'asb ','#','n=prompt("Enter new name",""); if(CUtil.varok(n) && n.length>0) { document.location.replace("'.$me.'?b=grpall&ac=renamegrp&newn=" + escape(n) + "&gid='.base64_encode($gid).'",true, true); }else {alert("Group name cannot be blank");document.location.replace("'.$me.'?b=grpall&ac=renamegrp&newn=" + escape(n) + "&gid='.base64_encode($gid).'",true, true); }',20,'images/ic/irename.gif','Rename'));
					*/
					
					if(intval($artcnt) === 0 )
					{
						echo( $hlp->getLinkAncHtml( 'aeditm',60,'asb ',$me.'?b=grpall&ac=d&gid='.base64_encode( $gid ).'&artcnt='.base64_encode($artcnt),'confirm( "Are you sure, you want to delete \"'.addslashes( $gname ).'\"?" )',20,'images/ic/itick.gif','Delete',$parent_tab,true ) );
					}
					else
					{
					/*	echo( $hlp->getLinkAncHtml( 'aeditm',60,'asb ',$me.'?b=grpall&ac=d&gid='.base64_encode( $gid ).'&artcnt='.base64_encode($artcnt),'confirm( "Are you sure you want to delete group \"'.addslashes( $gname ).'\"?" )',20,'images/ic/itick.gif','Delete',$parent_tab,true ) ); */
					echo( $hlp->getLinkAncHtml( 'aeditm',60,'asb ',$me.'?b=grpall&ac=d&gid='.base64_encode( $gid ).'&artcnt='.base64_encode($artcnt),'alert( "Please remove the artworks from the group \"'.addslashes( $gname ).'\"" )',20,'images/ic/itick.gif','Delete',$parent_tab,true ) );
		
					}
					
					echo( '</div></td></tr>' );
						
					echo( '</table></div>' );
				}	
			}
			else
				echo( "<div style='padding:20px;' >No groups to show for search text \"$srctxt\".</div>" );	
		}
		else
		{
			echo( '<div style="padding-top:15px;"><div class="txtheadwithbg" >Showing 0 of 0.</div></div>' );
			$hlp->searchBox( $parent_tab,$frm_submit,$srctxt,$comboHTML,$frmname,false,false );
			echo( "There are no groups to show for search text \"$srctxt\"." );	
		}	
	}
	else
		echo( "<div style='padding:20px;' >No groups to show.</div>" );	

?>