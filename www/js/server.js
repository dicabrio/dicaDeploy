$(function () {
	$('.default').each(function () {
		var aClassNames = $(this).attr('className').split(' ');
		for (var i = 0; i < aClassNames.length; i++) {
			if (aClassNames[i] == 'default' || aClassNames[i] == 'error') {
				continue;
			}

			var elID = '#'+aClassNames[i];
			var defaultVal = $(this).html();

			if ($(elID).attr('value') == "") {
				$(elID).attr('value', defaultVal).addClass('defaultvalue').bind('focus click',function () {
					if (this.value == defaultVal) {
						$(this).attr('value', "").toggleClass('defaultvalue');
					}
				}).bind('blur', function () {
					if (this.value == "") {
						$(this).attr('value', defaultVal).toggleClass('defaultvalue');
					}
				});
			}


			$('#form').submit(function () {
				if ($(elID).attr('value') == defaultVal && $(elID).hasClass('defaultvalue')) {
					$(elID).attr('value', "");
				}
			});
		}
		$(this).remove();
	});
});