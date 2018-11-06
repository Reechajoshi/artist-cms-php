<?php

$parent_tab = 'TAB_LEIT';
$frm_submit = "$me?b=leitall";
$uri = $me.'?b=leitall&s='.base64_encode( $lId );
$frmname = "allleit";
$pgnum = 1;
$comboHTML = false;
$srctxt = false;
	//DELETE CODE
	if( isset( $_GET[ 'ac' ] ) || isset( $_GET[ 'oId' ] ))
	{
		$lId = base64_decode( $_GET[ 'lId' ] );
		$oId = base64_decode( $_GET[ 'oId' ] );
		
		$imgname = base64_decode( $_GET[ 'lImgId' ] );
		if( $_GET[ 'ac' ] == 'd' )
		{
			
			@unlink("user/leitmotif/$imgname/original.jpeg");
			@unlink("user/leitmotif/$imgname/thumb.250.jpeg");
			exec("rm -rf user/leitmotif/$imgname",$outa,$ret);
			if( intval( $ret ) === 0 )
			{
				$ret = $hlp->delete_order_no($lId, $oId, "leitmotif");
				if($ret)
					$hlp->echo_ok( "Leitmotif removed." );
				else
					$hlp->echo_err( "Sorry, unable to remove leitmotif." );
			}
			else
				$hlp->echo_err("Error occured while removing leitmotif.");
		}
	}//event removed
	echo( '<div class="gencon icheight txt buttonmenuwithbg" >' );
	echo( $hlp->getLinkAncHtml('anewc',100,'asb rviewdash','#','addDynTabDirect("'.$parent_tab.'","New Leitmotif","org","New Leitmotif","'.$me.'?b=leitnew")',20,'images/ic/newc.png','New') );
	echo( '</div>' );
	//retriving value from database
	if( isset( $_GET['cbo'] ) )
	{
		$pgnum = $_POST['pageCombo'];
		$srctxt = trim( $_POST[ 'cbosrctxt' ] );
	}	
	if( isset( $_POST[ 'srctxt' ] ) )
		$srctxt = trim( $_POST[ 'srctxt' ] );
	$allx = $hlp->_db->db_return( "select count(*) cnt from leitmotif ;", array( 'cnt' ) );
	$allcnt = intval( $allx[0] );
	if( $allcnt > 0 )
	{
		$q="select count(*) cnt from leitmotif  where lDesc like '%$srctxt%';";
		$cntx = $hlp->_db->db_return( $q, array( "cnt" ) );
		$cnt = intval( $cntx[0] );
		if( $cnt > 0 )
		{ 
			$q="select * from leitmotif where lDesc like '%$srctxt%' order by order_no ";			
			$startIndex = ( ($pgnum-1)*$LEIT_SHOW_PER_PAGE );	
			if( $cnt > $LEIT_SHOW_PER_PAGE )
			{
				$comboHTML = $hlp->getDisplayPageComboHTML( $parent_tab,$cnt,$frm_submit."&cbo",$frmname,$pgnum,
				$LEIT_SHOW_PER_PAGE);
				$q .= " LIMIT $startIndex,$LEIT_SHOW_PER_PAGE ;";
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
					$lId = $row[ 'lId' ];
					$caption_style = "color:#8e8e8e;float:left;width:80px;";
					$val_style = "color:#3f95b1;padding-left:20px;";
					$lCreatedate = date( 'l, d F Y',strtotime( $row[ 'lCreatedate' ] ) );
					$lModifydate = date( 'l, d F Y',strtotime( $row[ 'lModifydate' ] ) );
					$lImgId = $row[ 'lImgId' ];
					$lDesc = $row[ 'lDesc' ];
					$lOrder_no = $row[ 'order_no' ];
					$ltitle = substr(strip_tags($lDesc), 0 ,5)." Priority: $lOrder_no";
					$desc = stripslashes(strip_tags( substr(preg_replace("/&#?[a-z0-9]+;/i","",$lDesc),0,95) ));
					
					//$ltitle = 'abc';
					$limgName = $row[ 'lImgId' ];
					$thumbPath = "user/leitmotif/$limgName/thumb.250.jpeg";
					
							echo( '<div name=entrydiv class="gencon bviewdash" 									
							style="white-space:nowrap;width:100%; padding-top:10px;" ><table name=tbl class=txt  
							style="background-color:#f8f8f8;" border=0 width=100%>' );
					
					echo( '<tr valign=top><td align=left valign=top style="width:400px;">
						<div ><table class=txt valign=top><tr valign=top><td valign=top>' );
						
					if( file_exists( $thumbPath ) )	
						echo( '<div style="float:left" ><img width=150 src="'.$thumbPath.'" 					
						/>&#160;&#160;&#160;</div></td><td>' );
						
						echo( '<div style="padding-top:7px;" >
								<table class=txt style="width:300px;" valign=top>
									
									<tr valign=top>	
										<td valign=top style="'.$caption_style.'" ><b>Desc:</b></td><td style="'.
										$val_style.'" >'.$desc.'&#160;</td>
									</tr>
				
									<!--<tr valign=top>
										<td valign=top style="'.$caption_style.'" ><b>Date Created:</b></td><td 
										style="'.$val_style.'" >'.$lCreatedate.'&#160;</td>
									</tr> -->
									
									<tr valign=top>
										<td valign=top style="'.$caption_style.'" ><b>Date Modified:</b></td><td 
										style="'.$val_style.'" >'.$lModifydate.'&#160;</td>
									</tr> 
								
								</table>		
							<div></td></tr></table></td><td valign=top><div style="float:right;">' );
					
					/*echo( $hlp->getLinkAncHtml( 'aeditm',60,'asb ','#','tt="Edit :'.addslashes( "$ltitle" ).
					'";addDynTabMain("'.$parent_tab.'","Edit ","'.addslashes("$ltitle").'",tt,"'.$me.
					'?b=leitedt&oId='.base64_encode($lOrder_no).'&lId='.base64_encode($lId ).'",true, true);',20,'images/ic/itick.gif','Edit',$parent_tab ) );*/
					
					
					/*echo( $hlp->getLinkAncHtml( 'aeditm',60,'asb ','#','tt="Edit :'.addslashes( $ltitle ).
					'";addDynTabMain("'.$parent_tab.'","Edit ","'.addslashes( $ltitle ).'",tt,"'.$me.
					'?b=leitedt&oId='.base64_encode($lOrder_no).'&lId='.base64_encode($lId ).'",true, true);',20,'images/ic/itick.gif','Edit',$parent_tab ) );*/
					echo( $hlp->getLinkAncHtml( 'aeditm',60,'asb ','#','tt="Edit :'.addslashes( $ltitle ).
					'";addDynTabMain("'.$parent_tab.'","Edit ","'.addslashes( $ltitle ).'",tt,"'.$me.
					'?b=leitedt&oId='.base64_encode($ltitle).'&lId='.base64_encode($lId ).'",true, true);',20,'images/ic/itick.gif','Edit',$parent_tab ) );
					
					
					echo( $hlp->getLinkAncHtml( 'aeditm',60,'asb ',$me.'?b=leitall&ac=d&oId='.base64_encode($lOrder_no).'&lId='.base64_encode( $lId).'&lImgId='.base64_encode( $lImgId ),'confirm( "Are you sure, you want to delete \"'.addslashes( "$ltitle" ).
					'\"?" )',20,
					'images/ic/itick.gif','Delete',$parent_tab,true ) );
					
					
					echo( '</div></td></tr>' );
						
					echo( '</table></div>' );
				}	
			}
			else
				echo( "<div style='padding:20px;' >No Leitmotif to show for search text \"$srctxt\".</div>" );	
		}
		else
		{
			echo( '<div style="padding-top:15px;"><div class="txtheadwithbg" >Showing 0 of 0.</div></div>' );
			$hlp->searchBox( $parent_tab, $frm_submit, $srctxt, $comboHTML, $frmname, false, false );
			echo( "No Leitmotif to show." );	
		}	
	}
	else
	
		echo( "<div style='padding:20px;' >Cannot show the Leitmotif.</div>" );
		
?>
</body>
