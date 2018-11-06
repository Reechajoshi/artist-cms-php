<?php

	if(isset($_GET['Aid']))
	{
		$Aid = base64_decode( $_GET[ 'Aid' ] );

		if( isset( $_GET['ac']))
		{	
			if ( $_GET['ac'] == "save" )
			{
				if ( ( isset( $_POST[ 'aname' ] )) || (isset( $_POST[ 'ayear' ] )) || (isset( $_POST[ 'collection' ] )) || ( isset
				( $_POST[ 'acansize' ] )) )
				{
					$Aname = trim($_POST['aname']);
					$Adesc = $_POST['htmlsrc'];			
					$Ayear = trim($_POST['ayear']);
					$Acollection = $_POST['collection'];
					$Acansize = trim($_POST['acansize']);	
					$subgroup = trim($_POST['subgroup']);

					if( ( strlen($Aname) !== 0 ) && ( strlen($Ayear) !== 0 )  && ( strlen($Acansize) !== 0 ) & is_numeric($Ayear) 
					&& strlen($Ayear) ===4 )
					{
						if(!$_FILES['img']['name']=="")  //file input not blank 
						{
							$imgDir = $_POST['Oldimg'];
							//$OldThumbId = $_POST['OldThumbId'];
							$d = date('Y-m-d G:i:s');
							foreach($_FILES as $f)
							{
								if($f['error']==0)
								{
									$iname = $f['name'];
									$s = @getimagesize($f['tmp_name']);
									if(isset($s['mime']))
									{
										$m = $s['mime'];
								
										if(($m=='image/jpeg')||($m=='image/gif')||($m=='image/png')||($m=='image/jpg') ||($m==
										'image/tiff')||($m=='image/tif'))
										{	
											$iext = explode('/',$m);
											$unqid = $hlp->getunqid( $Aname );
											$imgn = $unqid.".".$iext[1];
											
											$tmpPath = "$imgDir/temp.".$iext[1];
											$currentImagePath = "$imgDir/original.jpeg" ;
											
											$MainImagePath = "$imgDir/main.jpeg";
											$thumbPath = "$imgDir/thumb.250.jpeg";
											
											$continue = true;
											if( move_uploaded_file( $f['tmp_name'],$tmpPath ))
											{
												$size = filesize( $tmpPath );
												$cmd = "convert '$tmpPath' '$currentImagePath' ";
												
												exec ( $cmd, $outa, $ret );
												
												if( intval( $ret ) === 0 )
												{
												
													$s = @getimagesize( $currentImagePath );
													$cmdMain = "convert '$currentImagePath' -geometry 1200x '$MainImagePath' ";
													
													exec ( $cmdMain, $outa, $ret );
													
													if($ret !== 0)
													$continue = false;
													
													@unlink( $tmpPath );
														
													$size = filesize( $currentImagePath );
													$m = "image/jpeg";
													$iext[1] = "jpg";
												}
												else
												{
													@unlink( $tmpPath );
													// rmdir as command if failed
													$continue = false;
												}
												
												if($continue)
												{
													$q="Update artworks set Aname = '$Aname' , Adate = now() ,Adesc = '$Adesc' , AimgSize = '$size' , AimgWidth = ".$s[0]." , AimgHeight = ".$s[1]." , AimgMime = '$m' , Acanvas ='$Acansize' , Ayear =$Ayear , Acollection = $Acollection , subgroup='$subgroup' where Aid = '$Aid';";
													
												
													if( ( $res = $hlp->_db->db_query( $q ) ) !== false )
													{
														$hlp->echo_ok( "Artworks has been updated." );
														
														$thumbPath = "$imgDir/thumb.250.jpeg";
											
														$cmd = "convert $currentImagePath -geometry 250x $thumbPath";
														
														exec( $cmd, $op, $ret );
														if( intval( $ret ) === 0 && file_exists( $thumbPath ) )
														{	
															$s = @getimagesize( $thumbPath );
															if( isset( $s[ 1 ] ) )
															{
																$ht = $s[ 1 ];
																if( $ht > 250 )
																{									
																	$new_ht=$ht-250;
																	$cmd = "convert ${thumbPath} -gravity South -chop x${new_ht}+0+0 ${thumbPath}";
																
																	
																	exec( $cmd, $outa, $ret );
																	
																	if( intval( $ret ) !== 0 )
																	{
																		@unlink( $thumbPath );
																		$hlp->echo_err("unable to create 
																		thumbnail1.");
																	}
																}
																/*else
																{
																	$cmd = "convert ${currentImagePath} -resize 250x -background 
																	transparent -compose Copy -gravity center -extent 250x250 
																	${thumbPath} ";
																	
																	exec( $cmd, $outa, $ret );
																	if( intval( $ret ) !== 0 )
																	{
																		@unlink( $thumbPath );
																		$hlp->echo_err("unable to create thumbnail2.");
																	}
																}*/
															} //isset s [1]
														}
														else
														{
															@unlink( $thumbPath );
															$hlp->echo_err( "Unable to create Thumbnail3." );
														}
																				
													}	//query ok
													else
														$hlp->echo_err( "Sorry, unable to add record. contact 
														support." );
												} // continue
												else
													$hlp->echo_err( "Unable to process request. contact 
													support." );	
											}//move _uploaded file?
											else 
											$hlp->echo_err("Sorry, unable to upload image.");
										}  // mime type check
										else
											$hlp->echo_err("Sorry, uploaded file type is not supported.");
									}
									else
										$hlp->echo_err("Sorry, uploaded file type is not supported.");
								}
								else
									$hlp->echo_err("Saving failed");
							}	//foreach ends
						}// Ends File Input not blank
						else
						{
							$q = "Update artworks set Aname = '$Aname' , Adate = now() , Adesc = '$Adesc' , Acanvas ='$Acansize' , Ayear =$Ayear , Acollection = $Acollection , subgroup = '$subgroup' where Aid='$Aid' ;";
							
							$res = $hlp->_db->db_query( $q );
							if($res)
							$hlp->echo_ok( "Artwork has been upated" );
							else
							$hlp->echo_err("Arwork updation has been failed");
						}
						
					}
					else
						$hlp->echo_err("Please enter appropriate values in all the fields");
					
					
				} 	// end of if isset ac
			}		//end of ac=save
		}			//end of ac isset		
	
		$q = "select Aid , gid , Adesc , Aname , AimgName , Acanvas , Ayear , Acollection , subgroup from artworks 
		where Aid='$Aid';";
		if( ( $res = $hlp->_db->db_query( $q ) ) !== false )
		{
			$row = $hlp->_db->db_get( $res );	
			$gid = $row[ 'gid' ]; // Gid is needed for the subgroup combo box
			$Aid = $row[ 'Aid' ];
			$Aname = trim($row[ 'Aname' ]);
			$Ayear = trim($row[ 'Ayear' ]);         
			$subgroup = trim($row[ 'subgroup' ]);  
			$Acollection = $row[ 'Acollection' ];
			$Acansize = trim($row[ 'Acanvas' ]);
			
			$Adesc = $row[ 'Adesc' ];
			$imgName = $row[ 'AimgName' ];  
			$Oldimg = "data/$imgName";
			$thumbPath = "data/$imgName/thumb.250.jpeg";
			$colpvt=false; 
			$colinst=false;
			$colavail=false;
			$Acollection = intval($Acollection);   
			
			($Acollection === 0) ? ($colavail='checked') : ( $colavail='' ); //To select radio button Available
			($Acollection === 1) ? ($colpvt='checked') : ( $colpvt='' );
			($Acollection === 2) ? ($colinst='checked') : ( $colinst='' );
			
			$tdstyle = "class=rviewcell";
			$style ="style='padding-top:7px;padding-bottom:7px; 
			padding-right:10px;width:150px;color:#333;text-align:right;'";
			$valstyle = "style='padding-left:10px;padding-top:10px;'";

			$frm_submit = $me."?b=artedt&Aid=".base64_encode( $Aid )."&ac=save";
			
			echo('<form name=frmWrt method="post" action="'.$frm_submit.'" enctype="multipart/form-data">
				<input type="hidden" name=Oldimg value="'.$Oldimg.'" />
				<input type="hidden" name=OldThumbId value="'.$OldThumbId.'" /> 

				<div style="padding-top:10px" >
					<div style="width:80px;float:left;padding-left:10px;" >Title : </div>
					<div><input type=text maxlength="200" name=aname id=aname value="'.$Aname.'" 
					style="width:200px;" ></div>
				</div>
				
				<div style="padding-top:10px" >
					<div style="width:80px;float:left;padding-left:10px;" >Image : </div>
					<div ><img src="'.$thumbPath.'" />&#160;&#160;&#160;</div>
					<div style="width:80px;float:left;padding-left:10px;" >&#160;</div>
					<div><input type=file name=img style="width:300px;"></div>
					<div style="width:80px;float:left;padding-left:10px;">&#160;</div>
					<div style="color:#333;"> Upload a new image to replace the existing</div>
				</div>
					
				<div style="padding-top:10px" >
					<div style="width:80px;float:left;padding-left:10px;" >Canvas size : </div>
					<div><input type=text maxlength="200" name=acansize id=acansize value="'.$Acansize.'" 
					style="width:200px;" ></div>
				</div>
				
				<div style="padding-top:10px" >
					<div style="width:80px;float:left;padding-left:10px;" >Year : </div>
					<div><input type=text name=ayear id=ayear value="'.$Ayear.'" style="width:200px;" ></div>
				</div>
				<div style="padding-top:10px" >
					<div style="width:80px;float:left;padding-left:10px;" >Subgroup : </div>
					<div>'.$hlp->get_subgroupHTML_by_group($gid,$subgroup).'</div>
	</div>
				
				<div style="padding-top:10px" >
					<div style="width:80px;float:left;padding-left:10px;" >Collection : </div>
					<div>
					
					<input type="radio" id=available value="0"  name="collection" '.$colavail.'/><label 
					for=available >Available</label> 
					<input type="radio" id=private value="1" name="collection" '.$colpvt.'/>
					<label for=private>Private</label>
					<input type="radio" id=institute value="2"  name="collection"  '.$colinst.'/><label 
					for=institute >Institutional</label>
					
					</div>
			</div>
					
				
					
					
				<div style="padding-top:10px" >
					
				</div>
				<div style="padding-top:10px;" ><textarea id="htmlsrc" name="htmlsrc" rows="20" cols="80" 
				style="width: 95%">'.$Adesc.'</textarea></div> 
				<div style="width:320px;text-align:center;padding-top:10px;" ><button type=submit 
				class=roundbutton style="width:100px;" >Save</button></div>	
				</form>');
	
				echo( '<body>' );
		}
		echo( '<body>' );
		
		echo( '<div class=gencon style="padding-top:3px;">' );
		
		
		$toolbar_type = "BasicToolbar";
		require("ckeditor/init.php");
		$CKEditor = new CKEditor();
		$CKEditor->returnOutput = true;
		
		echo($CKEditor->replace("htmlsrc",array("toolbar"=>$toolbar_type)));
	}
?>