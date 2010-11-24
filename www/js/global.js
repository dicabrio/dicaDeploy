//global.js

// Google Analytics
_uacct = "UA-711464-4";
//if( document.referrer )
//{
//	if( document.referrer.indexOf( 'google' ) != -1 )
//	{
//		var urlPattern = /(\?|&)q=([^&]*)/;
//		var aMatches = urlPattern.exec( document.referrer );
//		if( aMatches != null )
//		{
//			//doUrchin( aMatches[2], 'query' );
//		}
//		else
//		{
//			urchinTracker();
//		}
//	}
//	else
//	{
//		urchinTracker();
//	}
//}
//else
//{
//	urchinTracker();
//}

$(setLinkBehaviours);
$(relNoFollow);

var XMLHttpFactories = [
	function() { return new XMLHttpRequest() }
	, function() { return new ActiveXObject( 'Msxml2.XMLHTTP' ) }
	, function() { return new ActiveXObject( 'Msxml3.XMLHTTP' ) }
	, function() { return new ActiveXObject( 'Microsoft.XMLHTTP' ) }
];

function GetXmlHttpObject() {
	var xmlHttp = false;

	for( var i = 0; i < XMLHttpFactories.length; i++ ) {
		try {
			xmlHttp = XMLHttpFactories[i]();
		} catch(e) {
			continue;
		}
		break;
	}
	return xmlHttp;
}

function sendRequest( url, callback, postData, preloadAction, addObj ) {
	var req = GetXmlHttpObject();

	if (!req) return;

	var method = ( postData ) ? "POST" : "GET";

	req.open(method,url,true);
	req.setRequestHeader('User-Agent','XMLHTTP/1.0');

	if (postData) {
		req.setRequestHeader('Content-type','application/x-www-form-urlencoded');
	}

	req.onreadystatechange = function() {

		if( preloadAction != null )
		{
			preloadAction( req.readyState );
		}

		if( req.readyState != 4 )
		{
			return;
		}
		else
		{
			callback( req, addObj );
		}
	}

	if (req.readyState == 4) {
		return;
	}

	req.send( postData );
}

function trim( text ) {
	return text.replace( /^\s*|\s*$/g, '' );
}


// Heel handig... bron: http://www.crisis.nl
function insertAfter( newNode, existingNode ) {
	if( existingNode.nextSibling )
	{
		existingNode.parentNode.insertBefore( newNode, existingNode.nextSibling );
	}
	else
	{
		existingNode.parentNode.appendChild( newNode );
	}
}

function setLinkBehaviours() {
	var Links = document.getElementsByTagName( 'A' );

	for( var i = 0; i < Links.length; i++ ) {
		if (Links[i].className.indexOf('external') !== -1) {
			Links[i].onclick = function() {
				var FakeLinkWindow = window.open( this.href, 'target', '' );
				return false;
			}
		}

		if (Links[i].className.indexOf('download') !== -1) {
			Links[i].onclick = function() {
				doUrchin(this.href, 'downloads');
			}
		}
	}
}

function doUrchin( Link, Prefix ) {
	Link = Link.replace( /^(http(s)?:\/\/)/i, '' );
	Link = Link.replace( /\./i, '_' );

	if( Prefix != null ) {
		Link = '/' + Prefix + '/' + Link;
	}

	urchinTracker( Link );
}

function relNoFollow() {

	$('span[title*=http://]').bind('mouseout', fakelinkMouseOut).bind('mouseover', fakelinkMouseOver).click(fakelinkClick).addClass('fakelink');

}

function fakelinkMouseOver() {
	this.className = 'fakelink-hover';
}

function fakelinkMouseOut() {
	this.className = 'fakelink';
}

function fakelinkClick() {
	var FakeLinkWindow = window.open( this.title, 'target', '' );
//	doUrchin( this.title, 'span' );
}

// does not do anything right now!
// This script will determine if you need tot popup a window or not
function blaat() {
	for (var i = 0, link; link = document.links[i]; i++) {
		if( link.className.indexOf("pdf") >= 0 || link.className.indexOf("extern") >= 0 ) {
			link.target = "_blank";

			if (lang == "nl") {
				insertAfter(document.createTextNode(" (nieuw venster)"), link);
			} else {
				insertAfter(document.createTextNode(" (new window)"), link);
			}
		}
	}
}
