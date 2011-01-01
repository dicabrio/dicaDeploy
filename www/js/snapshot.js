$(function () {

	var tabBarLength = 0;
	$('#tabmenu li').each(function () {
		tabBarLength += $(this).width()+2;
	});

	//$('#tabmenu').width(tabBarLength);

	$('#action .prev').click(function (e) {
		e.preventDefault();

		$('#tabmenu').animate({
			'left' : 10
		}, 500);
	});

	$('#action .next').click(function (e) {
		e.preventDefault();

		$('#tabmenu').animate({
			'left' : -(tabBarLength - $('#tabholder').width() + $('#action').width())
		}, 500);
	});

	if ($('#tabholder').width() < tabBarLength) {
		$('#action').show();
	// add navigation
	} else {
		// remove the navigation
		$('#action').hide();
	}
})
