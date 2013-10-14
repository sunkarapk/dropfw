// TODO: Remove this

jQuery.fn.slideFromEdge = function(element,options) {
	var settings = {
		'direction': 'right',
		'duration': 600,
		'image': false,
		'src': 'sidebar.png',
		'image_element': '#sidebar_image'
	};
	$.extend(settings, options);
	var rightPos = parseInt($(element).css(settings['direction']));
	$(this).click(function() {
		var curPos = parseInt($(element).css(settings['direction']));
		if(settings['image']) {
			var curSrc = $(settings['image_element']).attr('src');
			if(curSrc == settings['src'])
				imgSrc = curSrc.match(/[^\.]+/)[0] + '-active.' + curSrc.match(/\.[a-z]+/)[0].substr(1);
			else
				imgSrc = settings['src'];
			$(settings['image_element']).attr('src', imgSrc);
		}
		eval('$(element).animate({'+settings['direction']+': rightPos - curPos},'+ settings['duration']+');');
	});
}
