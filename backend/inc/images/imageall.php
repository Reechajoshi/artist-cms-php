<?php
	
	$parent_tab = 'TAB_IMAGES';
	
	echo( '<div class="gencon icheight txt buttonmenuwithbg" >' );
	echo( $hlp->getLinkAncHtml('anewc',100,'asb rviewdash','#','addDynTabDirect("'.$parent_tab.'","New Image","org","New Image ","'.$me.'?b=imgnew")',20,'images/ic/newc.png','Upload') );
	
	echo('</div>');
	
	$pgnum = 1;
	$frm_submit = "$me?b=imgall";
	$comboHTML = false;
	$srctxt = false;
	$frmname = "allimage";
	$DATA_DESTINATION = "content_img/";
	
	if( isset( $_GET[ 'ac' ] ) )
	{
		if( $_GET[ 'ac' ] == 'd' )
		{
			$iid = base64_decode( $_GET[ 'iid' ] );
			$q = "delete from images where iid='$iid';";
			if( $hlp->_db->db_query( $q ) )
			{
				$hlp->echo_ok( "Image has been removed." );
				@unlink( $DATA_DESTINATION.$iid );
			}	
			else
				$hlp->echo_err( "Sorry,unable to remove image." );
		}
	}
	
	if( isset( $_GET['cbo'] ) )
	{
		$pgnum = $_POST['pageCombo'];
	}	
	
	if( isset( $_POST[ 'srctxt' ] ) )
		$srctxt = trim( $_POST[ 'srctxt' ] );
	
	$allcntx = $hlp->_db->db_return( "select count(*) cnt from images;", array( 'cnt' ) );	
	$allcnt = intval( $allcntx[0] );
	
	if( $allcnt > 0 )
	{
		$q = "select count(*) cnt from images where iname like '%$srctxt%';";
		$cntx = $hlp->_db->db_return( $q, array( 'cnt' ) );		
		$cnt = intval( $cntx[0] );
		
		if( $cnt > 0 )
		{
			$q = "select * from images where iname like '%$srctxt%' order by idate desc";
			$startIndex = ( ($pgnum-1)*$IMAGE_DISPLAY_PER_PAGE );	
			
			if( $cnt > $IMAGE_DISPLAY_PER_PAGE )
			{
				$comboHTML = $hlp->getDisplayPageComboHTML( $parent_tab,$cnt,$frm_submit."&cbo",$frmname,$pgnum,$IMAGE_DISPLAY_PER_PAGE);
				
				$q .= " LIMIT $startIndex,$IMAGE_DISPLAY_PER_PAGE ;";
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
					$iid = $row['iid'];
					$iname = $row['iname'];
					$idate = date( 'l, d F Y',strtotime( $row[ 'idate' ] ) );
					$isize = $hlp->format_space( $row[ 'isize' ] );
					$iwidth = $row[ 'iwidth' ];
					$iheight = $row[ 'iheight' ];
					
					$caption_style = "color:#8e8e8e;float:left;width:80px;";
					$val_style = "color:#3f95b1;white-space:normal;width:610px;";
					
					$imgpath = $DATA_DESTINATION.$iid;
					
					echo( '<div name=entrydiv class="gencon bviewdash" style="white-space:nowrap;width:100%;padding-top:10px;" ><table name=tbl class=txt  style="background-color:#f8f8f8;" border=0 width=100%>' );
					echo( '<tr><td align=left valign=top style="width:800px;">
						<div ><table class=txt ><tr><td>' );
					if( file_exists( $imgpath ) )	
						echo( '<div style="float:left" ><img width=150 src="'.$imgpath.'" />&#160;&#160;&#160;</div></td><td>' );
					echo( '<div id=txt style="color:#10647e;"><b>'.$iname.'</b></div>' );
					echo( '<div style="padding-top:7px;" >
							<table class=txt style="width:650px;" >
								<tr>
									<td style="'.$caption_style.'" ><b>Size:</b></td><td style="'.$val_style.'" >'.$isize.'&#160;</td>
								</tr>
								<tr>	
									<td style="'.$caption_style.'" ><b>Date:</b></td><td style="'.$val_style.'" >'.$idate.'&#160;</td>
								</tr>
								<tr>	
									<td style="'.$caption_style.'" ><b>Resolution:</b></td><td style="'.$val_style.'" >'.$iwidth."x".$iheight.'&#160;</td>
								</tr>
							</table>		
						<div></td></tr></table></td><td><div>' );
					
					echo( $hlp->getLinkAncHtml( 'aeditm',60,'asb ',$me.'?b=imgall&ac=d&iid='.base64_encode( $iid ),'confirm( "Are you sure, you want to delete \"'.addslashes( $iname ).'\"?" )',20,'images/ic/itick.gif','Delete',$parent_tab,true ) );
					
					echo( '</div></td></tr>' );
						
					echo( '</table></div>' );
				}	
			}
			else
				echo( "<div style='padding:20px;' >No images to show for search text \"$srctxt\".</div>" );	
		}
		else
		{
			echo( '<div style="padding-top:15px;"><div class="txtheadwithbg" >Showing 0 of 0.</div></div>' );
			$hlp->searchBox( $parent_tab,$frm_submit,$srctxt,$comboHTML,$frmname,false,false );
			echo( "There are no images to show for search text \"$srctxt\"." );	
		}	
	}
	else
		echo( "<div style='padding:20px;' >No images to show.</div>" );

?>