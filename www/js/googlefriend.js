var skin = {};
skin['BORDER_COLOR'] = '#cccccc';
skin['ENDCAP_BG_COLOR'] = '#F0F0F0';
skin['ENDCAP_TEXT_COLOR'] = '#333333';
skin['ENDCAP_LINK_COLOR'] = '#0000cc';
skin['ALTERNATE_BG_COLOR'] = '#ffffff';
skin['CONTENT_BG_COLOR'] = '#ffffff';
skin['CONTENT_LINK_COLOR'] = '#0000cc';
skin['CONTENT_TEXT_COLOR'] = '#333333';
skin['CONTENT_SECONDARY_LINK_COLOR'] = '#7777cc';
skin['CONTENT_SECONDARY_TEXT_COLOR'] = '#666666';
skin['CONTENT_HEADLINE_COLOR'] = '#333333';
skin['DEFAULT_COMMENT_TEXT'] = '- Leave a comment -';
skin['HEADER_TEXT'] = 'Comments';
skin['POSTS_PER_PAGE'] = '5';

$(function () {
//	alert('sdfsd');
	google.friendconnect.container.setParentUrl('/' /* location of rpc_relay.html and canvas.html */);
	google.friendconnect.container.renderWallGadget({
		id: 'comments',
		site: '08462178510422205665',
		'view-params':{"disableMinMax":"true","scope":"PAGE","allowAnonymousPost":"true","startMaximized":"true"}
	},
	skin);
});
