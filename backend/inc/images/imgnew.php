<?php

	$DATA_DESTINATION = "backend/content_img/";
	
	$uri = $me."?b=imgnew&up"; 
	
	
	if(isset($_GET['up']))
	{
		//print_r($_FILES);	
		if((isset( $_POST['imgname']) ) )
		{
			if(!$_FILES['img']['name']=="")  //file input not blank 
			{
				
				$imgname = trim($_POST['imgname']);	
				$d = date('Y-m-d G:i:s');
				$unqid = $hlp->getunqid($imgname);
				
				
				foreach($_FILES as $f)
				{
					if($f['error']==0)
					{
						$s = @getimagesize($f['tmp_name']);
						
						if(isset($s['mime']))
						{
							$m=$s['mime'];
							
							if( ($m == 'image/jpeg' ) || ( $m == 'image/gif' ) || ( $m == 'image/png' ) || ( $m == 'image/jpg' ) || ( $m == 'image/bmp' ) )
							{
								$iext = explode('/',$m);
								$unqid = $unqid.".".$iext[1];
								
								//$currentImagePath = $DATA_DESTINATION."/".$unqid;
								$currentImagePath = "content_img/".$unqid;
							
								if( move_uploaded_file( $f['tmp_name'],$currentImagePath ))
								{
									/* iid  iname idate  isize  iwidth  iheight imime */
									$q = "insert into images( iid, idate, iname, isize, iwidth, iheight) values ( '$unqid', now(), '$imgname',".filesize( $currentImagePath )." ,".$s[0].",".$s[1]." );";
								
									//echo($q);
									$res = $hlp->_db->db_query( $q );
									
									if($res !== false)
										$hlp->echo_ok("Image stored succesfully");
									else
									{
										@unlink( $currentImagePath );
										$hlp->echo_err("Image saving failed");
									}
									
								}
								else
								{
									@unlink( $currentImagePath );	
									$hlp->echo_err("Image saving failed,contact support");
								}
							}
							else
								$hlp->echo_err("Filetype not supported");
						}// s[mime]
						else
							$hlp->echo_err("Filetype not supported");
					}
					else
						$hlp->echo_errr("Something went wrong,Please try again");
				}//foreach ends
			}
			else
				$hlp->echo_err("Please upload an image");
		}// post name 
	}// isset up
	
	echo("<div>
		<form name=frmWrt method='post' action=".$uri." enctype='multipart/form-data'>
			<div style='padding-top:10px' >
				<div style='width:80px;float:left;padding-left:10px;' >Title : </div>
				<div><input type=text maxlength='200' name=imgname style='width:200px;' ></div>
			</div>
			<div style='padding-top:10px' >
				<div style='width:80px;float:left;padding-left:10px;' >Image : </div>
				<div><input type=file name=img style='width:300px;' ></div>
			</div>
		
			<div style='padding-top:10px' >
				<div style='width:320px;text-align:left;padding-top:10px;' >
					<button type=submit class=roundbutton style='width:100px;' >Upload</button>
				</div>
			</div>
	  </form>
	
	</div>");
			
			

?>