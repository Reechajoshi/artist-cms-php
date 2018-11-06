// JS Helper for mgtech
///////////////////////////////////////////////////////////////////////////////////////////////////
var _CONTENT_DEF_TEXT_SIZE=11;

CHelp = {
	_M_LINK_COL_SPEED: 5,
	_M_LINK_RED_START: 255,
	_M_LINK_GREEN_START: 255,
	_M_LINK_BLUE_START: 255,
	
	_M_LINK_RED_END: 26,
	_M_LINK_GREEN_END: 0,
	_M_LINK_BLUE_END: 105,
	
	// LINK ANIMATION STATE ////////////////////////////////////////////////////////////////////////////////////////////////
	_mLinkLastClick: null, //USED IN OVER EVENTS TO NOT TRIGGER ON ONE WHICH HAS BEEN CLICKED
	_mLinkChangeHandle: new Array(),
	_mLinkRedMap: new Array(),
	_mLinkGreenMap: new Array(),
	_mLinkBlueMap: new Array(),
	
	// CONTENT STATE
	_mContentMap: new Array(),
	_lastHash: -1,
	
	init: function(doc)
	{
		CUi.init(document);
		
		CHelp.onResize();
		CHelp.onBodyLoad();
		
		CHelp.monitorHashChange();
				
		window.onresize = CHelp.onResize;		
		
		// TODO THIS SHOULD ALL COME FROM CENTRAL LOCATION
		CHelp._M_TITLE_LIST = new Array();		
	},
	
	load_done: function()
	{
		if( !CUi._isIE )
		{
			var ell = document.getElementById('ccover');
			
			if(ell)
			{
				var sob = 0, sob_end = 1, sob_inc=+0.2;
				ccover.style.opacity = sob; 
				ell.innerHTML = '';
				
				var interID = setInterval( function() {
					sob += sob_inc;
					
					if(((sob_inc<0)?(sob<=sob_end):(sob>=sob_end)))
					{
						clearInterval(interID); 
						ccover.style.opacity = sob_end;
						ell.parentNode.removeChild(ell);						
					}
					else
						ccon.style.opacity = sob;							
				}, 50 );
			}
		}
	},
	
	onResize: function()
	{
		var ccon = CUi.doc.getElementById('ccon');
		var w = CUi.get_view_cxy(true), maxw = 1200;
		CUi.logFB('width = ' + w);
		
		if(w > maxw)
			ccon.style.width=maxw+'px';
		else
			ccon.style.width='100%';
	},
	
	monitorHashChange: function()
	{		
		CHelp._lastHash = location.hash.substr(1);		
		CUi.logFB('start = ' + CHelp._lastHash + " - " + location.hash.substr(1));
		setInterval( function() {
			cur_lastHash = location.hash.substr(1);
			if( CHelp._lastHash != cur_lastHash )
			{
				CUi.logFB('onc = ' + CHelp._lastHash + " - " + cur_lastHash);
				CHelp.clickMe( cur_lastHash );
			}
		}, 100 );
	},
	
	setContent: function(cid,chtml)
	{		
		CHelp._mContentMap[cid] = chtml;
	},
	
	clickMe: function(lid)
	{
		try {			
			var anclk = 'req.x?fl=' + lid;
			CHelp.displayContent(anclk, lid);			
		} catch(e) {  }
		return( false );
	},
	
	setContentTitle: function(lid)
	{
		if(CUtil.varok(CHelp._M_TITLE_LIST[lid]))
			CUi.setDocumentTitle(CHelp._M_TITLE_LIST[lid]);
	},
	
	isContentOK: function(lid)
	{
		return( CUtil.varok(CHelp._mContentMap[lid]) );
	},
	
	setTopMenu: function(lid)
	{
		var tmelm = CUi.doc.getElementById('topmenu');
		if(tmelm)
		{
			var elc = tmelm.getElementsByTagName('DIV');
			if(elc)
			{
				for( ix=0; ix < elc.length; ix++ )
				{
					elc[ix].style.display = ( ( elc[ix].getAttribute('name') == lid ) ? ('none') : ('block') );					
				}
			}
		}
	},
	
	displayContent: function(lk, lid)
	{
		if(CHelp.isContentOK(lid))
		{
			var belm = CUi.doc.getElementById('maincon');
			if(belm)
			{				
				try {
					CHelp.setTopMenu(lid);
					belm.innerHTML = String(CHelp._mContentMap[lid]);
					CHelp.setContentTitle(lid);
					location.hash = "#" + lid;
					CHelp._lastHash = lid;
				} catch(ex) { alert(ex.message); }
				return(true);				
			}
		}
		else
		{
			CUi.showWorking();
			
			CTalk.sendSimplePost(lk, function(txt) {
				try {
					CUi.hideWorking();
					if(txt)
					{	
						CHelp.setContent(lid,txt);
						CHelp.displayContent(lk, lid);
					}
					else
					{					
						CHelp.setContent(lid,'');
						CHelp.displayContent(lk, lid);
					}
				} catch(e) { }
			} );			
		}

		return(false);
	},
	
	highLink: function(lk,onover)
	{
		// COL MAX:  1A 0 69( 26 0 105)
		var pelm = lk.parentNode.parentNode, lid = pelm.getAttribute('name'), ired = 0, iblue = 0, igreen = 0, ireddiff = (CHelp._M_LINK_RED_START - CHelp._M_LINK_RED_END), 
		igreendiff = (CHelp._M_LINK_GREEN_START - CHelp._M_LINK_GREEN_END), ibluediff = (CHelp._M_LINK_BLUE_START - CHelp._M_LINK_BLUE_END);						
	
		if(CHelp._mLinkLastClick == lid)
			return ;
			
		//NOTE: ASSUMTION: since green is biggest diff, do it this way, need to change if start end change; this was not made auto blah!!!
		igreen = CHelp._M_LINK_COL_SPEED;
		ired = (ireddiff / igreendiff) * igreen;
		iblue = (ibluediff / igreendiff) * igreen;						
		
		if(!onover)
			{ ired *= -1; igreen *= -1; iblue *= -1; }
		
		var initMaps = function(beFirstTime)
		{
			if(beFirstTime) //IF THIS IS THE FIRST TIME, THEN INVERT THIS SENSE ...
				onover = !onover;
				
			CHelp._mLinkRedMap[lid] = ( (onover) ? (CHelp._M_LINK_RED_END) : (CHelp._M_LINK_RED_START) );
			CHelp._mLinkGreenMap[lid] = ( (onover) ? (CHelp._M_LINK_GREEN_END) : (CHelp._M_LINK_GREEN_START) );
			CHelp._mLinkBlueMap[lid] = ( (onover) ? (CHelp._M_LINK_BLUE_END) : (CHelp._M_LINK_BLUE_START) );
			
			pelm.style.borderBottomColor = CUtil.makeRealColourHex(CHelp._mLinkRedMap[lid],CHelp._mLinkGreenMap[lid],CHelp._mLinkBlueMap[lid]);
		}, 
		stopInterval = function()
		{
			initMaps();
			clearInterval(CHelp._mLinkChangeHandle[lid]);
		}
		
		if(!CUtil.varok(CHelp._mLinkRedMap[lid]))
			initMaps(true);
		
		if(CHelp._mLinkChangeHandle[lid])
			clearInterval(CHelp._mLinkChangeHandle[lid]);
		
		if(CHelp._mLinkGreenMap[lid] <= CHelp._M_LINK_GREEN_END)
			CHelp._mLinkGreenMap[lid] = CHelp._M_LINK_GREEN_END;
		else if(CHelp._mLinkGreenMap[lid] >= CHelp._M_LINK_GREEN_START)
			CHelp._mLinkGreenMap[lid] = CHelp._M_LINK_GREEN_START;
		
		CHelp._mLinkChangeHandle[lid] = setInterval( function() {					
			pelm.style.borderBottomColor = CUtil.makeRealColourHex(CHelp._mLinkRedMap[lid],CHelp._mLinkGreenMap[lid],CHelp._mLinkBlueMap[lid]);						
			
			CHelp._mLinkRedMap[lid] -= ired;
			CHelp._mLinkGreenMap[lid] -= igreen;
			CHelp._mLinkBlueMap[lid] -= iblue;
			
			//NOTE: ASSUMTION: This needs to move in accordance with the setting of igreen above, and changes based on largest diff.
			if(CHelp._mLinkGreenMap[lid] <= CHelp._M_LINK_GREEN_END)
				{ stopInterval(); }
			else if(CHelp._mLinkGreenMap[lid] >= CHelp._M_LINK_GREEN_START)
				{ stopInterval(); }			
		}, 20 );
	},
	
	setupResizeDIM: function(specElm)
	{	
		specElm.style.width = CHelp.getMainWidth() + 'px';
		specElm.style.paddingTop = "10px";
		specElm.style.zIndex=10;

		var belm = CUi.doc.getElementById('basecon');
		if(belm)
			belm.style.height = CHelp.getConHeight();
			
		CUi.hookResize( 'main', function(e) {
			specElm.style.width = CHelp.getMainWidth() + 'px';
			belm.style.height = CHelp.getConHeight() + 'px';
		} );
	},
	
	getMainWidth: function()
	{
		var clw = CUtil.getDim(true,50);		
		if(clw > 1000) return(1000); else return(clw);
		//else return( CUtil.max( 800, clw ) );
	},
	
	getConHeight: function()
	{
		return( CUtil.getDim(false,35) - _BODY_TOP_HEIGHT - _BODY_BASE_EXTRA_HEIGHT - (2 * _BODY_BAR_HEIGHT) );
	},
	
	_M_MAX_TEXT_SIZE: (_CONTENT_DEF_TEXT_SIZE + 5),
	_M_MIN_TEXT_SIZE: (_CONTENT_DEF_TEXT_SIZE - 2),
	
	_mCurTextSize: _CONTENT_DEF_TEXT_SIZE,
	
	initTextSize: function()
	{
		CHelp.setTextSize(_CONTENT_DEF_TEXT_SIZE);
	},
	
	decTextSize: function()
	{
		if(CHelp._mCurTextSize > CHelp._M_MIN_TEXT_SIZE)
		{
			CHelp.setTextSize( --CHelp._mCurTextSize );
		}
	},
	
	incTextSize: function()
	{
		if(CHelp._mCurTextSize < CHelp._M_MAX_TEXT_SIZE)
		{
			CHelp._mCurTextSize += 2;
			CHelp.setTextSize( CHelp._mCurTextSize);
		}
	},
	
	setCurrentTextSize: function()
	{
		CHelp.setTextSize( CHelp._mCurTextSize );
	},
	
	setTextSize: function(ts)
	{
		var elm = CUi.doc.getElementById('basecon');
		if(elm)
		{
			CHelp._mCurTextSize = ts;
			
			CUtil.applyToChildNodes(elm, 'DIV', true, function(ob) {
				ob.style.fontSize = CHelp._mCurTextSize + 'pt';
			} );
		}
	},
	
	ID_POP: '_id_pop_img',
	
	pop_img: function(ob,iw,ih)
	{
		try {
			var isrc = ob.href,
				itxt = CUtil.getOBText(ob),
				html = "<div style='height:12'><u>" + itxt + "</u></div><br/><img src=\"" + isrc + "\" border=0 width=" + iw + " />";
			
			new CWin.win(CHelp.ID_POP, {
				autoOpen: true,
				withFrills: false,
				width: iw,			
				height: ih,
				capHeight: 28,
				modal: true,
				positionMid: true,
				closeOnEscape: true,
				html: html,
				htmlCenter: true,
				fadeShade: false
			} );
			
			CUi.setOMD(CHelp.ID_POP, function() {
				var wx = CWin.getMe(CHelp.ID_POP);
				if(wx)
				{
					wx.destroy();
					CUi.clearOMD(CHelp.ID_POP);
				}
			} );
		} catch(e) {}
	},	
	
	productSubDisp: function(aob)
	{
		var elMenuTop = CUtil.getParentByName(aob,"menut");
		
		if(elMenuTop)
		{
			var elSub = CUtil.getChildByName(elMenuTop,'submenux','DIV',false),
				elIPMid = CUtil.getChildByName(aob,'ipmid','SPAN',false);
						
			if(elSub)
			{
				if( elSub.style.display == 'block' )
				{
					elSub.style.display = 'none';
					elIPMid.innerHTML = '+';
				}
				else
				{
					elSub.style.display = 'block';
					elIPMid.innerHTML = ' - ';
				}
			}
		}
	},
	
	submitEnqForm : function(obj){
		var parDiv = CUtil.getParentByName( obj, 'enqMainDiv' );
		if( parDiv )
		{
			var eleName = new Array();
			eleValue = new Array();
			CUtil.applyToMultiChildNodes( parDiv, new Array( 'INPUT', 'TEXTAREA'), true, function(ele){
				eleName.push( ele.name );
				eleValue.push( ele.value );
				
			} );
			CTalk.sendPost( "req.x?ac=enq", eleName, eleValue,function( resp ){ 
					
					if(resp=="Ok")
					{
						alert("Enquiry is sent,we will contact you shortly");
						CUtil.applyToMultiChildNodes( parDiv, new Array( 'INPUT', 'TEXTAREA'), true, function(ele){
						eleName.push( ele.name );
						eleValue.push( ele.value="" );
						});
					}
					else if(resp=="wrongemail")
					{
						alert("Please specify the correct email id"); 
					}
					else if(resp=="Nok")
					{
						alert("Enquiry is not sent"); 
					}
				
			} );

		}	
	},
	
	_adjustHomeImage: function()		
	{
		var iW = 1200, iH = 768;
		var winW = 630, winH = 460;
		var topch = 90;
		
		try {
			if (document.body && document.body.offsetWidth) {
				winW = document.body.offsetWidth;
				winH = document.body.offsetHeight;
			}
			
			if (document.compatMode=='CSS1Compat' &&
				document.documentElement &&
				document.documentElement.offsetWidth ) {
					winW = document.documentElement.offsetWidth;
					winH = document.documentElement.offsetHeight;
			}
			
			if (window.innerWidth && window.innerHeight) {
				winW = window.innerWidth;
				winH = window.innerHeight;
			}
			
			var mimg = document.getElementById( 'mimg' );
			var tbar = document.getElementById( 'tbar' );
			
			if( mimg )
			{
				if((winH - (topch*2)) <= iH)
				{
					if( (winH - (topch*2)) < 500 )
						mimg.style.height = 500;
					else
						mimg.style.height = (winH - (topch*2));
				}
				else
					mimg.style.height = iH;
				
				mimg.style.top = topch;
				
				setTimeout( function() {
					var newW = mimg.clientWidth;
					mimg.style.left = (winW - newW) /2;
					tbar.style.left = (winW - newW) /2;
					tbar.style.top = 0;
					tbar.style.height = topch;
					tbar.style.width = newW;
					tbar.childNodes[1].style.width =newW;
					tbar.childNodes[1].style.height = topch-10;
				}, 25 );
			}			
		} catch(e) { alert(e.message); }
	}
};