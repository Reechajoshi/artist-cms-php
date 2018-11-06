<?php
	
	$parent_tab = 'TAB_CURRENT';
	$pgnum = 1;
	$frm_submit = "$me?b=contall";
	$comboHTML = false;
	$srctxt = false;
	$frmname = "allart";
	$uri = $me.'?b=contall&s='.base64_encode( $cid );
	//DELETE CODE
	if( isset( $_GET[ 'ac' ] ) || isset( $_GET[ 'oId' ] ))
	{
		$cId = base64_decode( $_GET[ 'cId' ] );
		$oId = base64_decode( $_GET[ 'oId' ] );
		$imgname = base64_decode( $_GET[ 'cImgId' ] );
		if( $_GET[ 'ac' ] == 'd' )
		{
			@unlink("user/currently/$imgname/original.jpeg");
			@unlink("user/currently/$imgname/thumb.250.jpeg");
			
			exec("rm -rf user/currently/$imgname",$outa,$ret);
			
			if( intval( $ret ) === 0 )
			{
				if($hlp->delete_order_no($cId, $oId, "currently"))
					$hlp->echo_ok( "Event has been removed." );
				else
					$hlp->echo_err( "Sorry, unable to remove event." );
			}
			else
				$hlp->echo_err("Error occured while removing event.");
		}
	}//event removed
		
	echo( '<div class="gencon icheight txt buttonmenuwithbg" >' );
	
	echo( $hlp->getLinkAncHtml('anewc',100,'asb rviewdash','#','addDynTabDirect("'.$parent_tab.'","New Event","org","New Event","'
	.$me.'?b=contnew")',20,'images/ic/newc.png','New') );

	echo( '</div>' );
	
	//retriving value from database
	if( isset( $_GET['cbo'] ) )
	{
		$pgnum = $_POST['pageCombo'];
		$srctxt = trim( $_POST[ 'cbosrctxt' ] );
	}	
	
	if( isset( $_POST[ 'srctxt' ] ) )
		$srctxt = trim( $_POST[ 'srctxt' ] );

	$allx = $hlp->_db->db_return( "select count(*) cnt from currently;", array( 'cnt' ) );
	$allcnt = intval( $allx[0] );

	if( $allcnt > 0 )
	{
		$q="select count(*) cnt from currently  where cTitle like '%$srctxt%';";
		$cntx = $hlp->_db->db_return( $q, array( "cnt" ) );
		
		$cnt = intval( $cntx[0] );
		if( $cnt > 0 )
		{ 
			$q="select * from currently where cTitle like '%$srctxt%' order by order_no desc";			
			$startIndex = ( ($pgnum-1)*$CONT_SHOW_PER_PAGE );	
			
			if( $cnt > $CONT_SHOW_PER_PAGE )
			{
				$comboHTML = $hlp->getDisplayPageComboHTML( $parent_tab,$cnt,$frm_submit."&cbo",$frmname,$pgnum,
				$CONT_SHOW_PER_PAGE);
				
				$q .= " LIMIT $startIndex,$CONT_SHOW_PER_PAGE ;";
			}
			$res = $hlp->_db->db_query( $q );	
			if( $res )
			{
				$showNumRow = intval( $hlp->_db->db_num_rows( $res ) );
						
				if( ( $startIndex + 1 ) === ( $showNumRow + $startIndex ) && $pgnum == 1 )
					echo( '<div style="padding-top:15px;"><div class="txtheadwithbg" >Showing 1 of 
					1.</div></div>' );
				else
					echo( '<div style="padding-top:15px;"><div class="txtheadwithbg" >Showing '.( $startIndex + 1 
					)." to ".( $showNumRow + $startIndex ).' of '.$cnt.'.</div></div>' );
				$hlp->searchBox( $parent_tab, $frm_submit, $srctxt, $comboHTML, $frmname, false, false );
				while( ( $row = $hlp->_db->db_get( $res ) ) !== false )
				{
					$cId = $row[ 'cId' ];
					$val_style = "color:#3f95b1;padding-left:25px;";
					$caption_style = "color:#8e8e8e;float:left;width:80px;";
					$cCreatedate = date( 'l, d F Y',strtotime( $row[ 'cCreatedate' ] ) );
					$cModifydate = date( 'l, d F Y',strtotime( $row[ 'cModifydate' ] ) );
					$cImgId = $row[ 'cImgId' ];
					$cTitle=$row[ 'cTitle' ];
					$cTitleLink = $row[ 'cTitleLink' ];         
					$cDesc = strip_tags(stripslashes($row[ 'cDesc' ]));
					$cOrder_no = $row[ 'order_no' ];
					$cImgTitle = $row[ 'cImgTitle' ];
					
					$cimgName = $row[ 'cImgId' ];
					$thumbPath = "user/currently/$cimgName/thumb.250.jpeg";
							echo( '<div name=entrydiv class="gencon bviewdash" 									
							style="white-space:nowrap;width:100%; padding-top:10px;" ><table name=tbl class=txt  
							style="background-color:#f8f8f8;" border=0 width=100%>' );
					echo( '<tr valign=top><td align=left valign=top style="width:400px;">
						<div ><table class=txt valign=top><tr valign=top><td valign=top>' );
						
					if( file_exists( $thumbPath ) )	
						echo( '<div style="float:left" ><img width=150 src="'.$thumbPath.'" 					
						/>&#160;&#160;&#160;</div></td><td>' );
						//display TITLE
						echo( '<div id="txt" style = "color:#10647e;"><b>'.$cTitle.'</b></div>' );
						echo( '<div style="padding-top:7px;" >
								<table class=txt style="width:300px;" valign=top>
									
									<tr valign=top>	
										<td valign=top style="'.$caption_style.'" ><b>Desc:</b></td><td style="'.
										$val_style.'" >'.$hlp->trimText( substr( $cDesc,0,80) ).'&#160;</td>
									</tr>
				
									<tr valign=top>
										<td valign=top style="'.$caption_style.'" ><b>Date Modified:</b></td><td 
										style="'.$val_style.'" >'.$cModifydate.'&#160;</td>
									</tr> 
								
								</table>		
							<div></td></tr></table></td><td valign=top><div style="float:right;">' );
					
					echo( $hlp->getLinkAncHtml( 'aeditm',60,'asb ','#','tt="Edit :'.addslashes( $cTitle ).
					'";addDynTabMain("'.$parent_tab.'","Edit ","'.addslashes( $cTitle ).'",tt,"'.$me.
					'?b=contedt&oId='.base64_encode($cOrder_no).'&cId='.base64_encode($cId ).'",true, true);',20,'images/ic/itick.gif','Edit',$parent_tab ) );
					
					
					echo( $hlp->getLinkAncHtml( 'aeditm',60,'asb ',$me.'?b=contall&ac=d&oId='.base64_encode($cOrder_no).'&cId='.base64_encode( $cId).'&cImgId='.base64_encode( $cImgId ),'confirm( "Are you sure, you want to delete \"'.addslashes( $cTitle ).
					'\"?" )',20,
					'images/ic/itick.gif','Delete',$parent_tab,true ) );
					
					
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
			echo( "No Events to show." );	
		}	
	}
	else
		echo( "<div style='padding:20px;' >Cannot show the Events.</div>" );
?>
