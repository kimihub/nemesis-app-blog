$(function() {	

	$('.medias ul li a').html5lightbox({skinfolder:NEMESIS.BLOG_RESOURCES_URL+"jquery.plugins/skins/default/"});

	$('.gallery ul.notLoaded li.controlLeft').click(function(e) {
		
		e.stopPropagation();
		
		var el = this;
		
		$(el).parent().removeClass('notLoaded');
		
		if ($(el).parent().hasClass('locked'))
			return false;
	
		$(el).parent().addClass('locked');
		
		var current = $(el).parent().find('li.current');
		
		current.fadeOut('slow', function() {
			current.removeClass('current');

			var prev = current.prev();
		
			if (prev.hasClass('controlLeft'))
			{
				prev = $(el).parent().find('li').last().prev();
			}
			
			prev.fadeOut(0, function() {
				prev.addClass('current');
				prev.fadeIn('slow');
				$(el).parent().removeClass('locked');
			});
			
		});
		
		
	});
	
	$('.gallery ul.notLoaded li.controlRight').click(function(e) {
		e.stopPropagation();
		
		var el = this;
		
		$(el).parent().removeClass('notLoaded');
		
		if ($(el).parent().hasClass('locked'))
			return false;
	
		$(el).parent().addClass('locked');
		
		var current = $(el).parent().find('li.current');
		
		current.fadeOut('slow', function() {
			current.removeClass('current');

			var next = current.next();
		
			if (next.hasClass('controlRight'))
			{
				next = $(el).parent().find('li').first().next();
			}
			
			next.fadeOut(0, function() {
				next.addClass('current');
				next.fadeIn('slow');
				$(el).parent().removeClass('locked');
			});
			
		});
	});
});	