$(function() {
	displayLog = function(message) {
	
		if (!$('#logs').length)
		{
			$('<div></div>').attr('id', 'logs').css({
				'display': 'none',
				'height': '30%',
				'overflow-y': 'scroll',
				'background': 'rgba(255, 255, 255, 0.75)',
				'border-bottom': '6px solid #ddd',
				'padding': '7px',
				'z-index': '999',
				'position': 'fixed',
				'top': 0,
				'left': 0,
				'right': 0,
				'cursor': 'pointer',
				'color': 'black'
			}).click(function() {$('#logs').hide('slow')}).appendTo(document.body);
			
			
			$('#logs').show('slow');
			
		}
		else
		{
			$('#logs').show();
		}
		
		$('#ajax-loading').fadeIn();
		
		$('#logs').append('<div>'+message+'</div>');
		$('#logs').animate({ scrollTop: $('#logs').get(0).scrollHeight }, 0);
	};
	
	$('.delete').each(function() {
		
		$(this).removeClass('delete');	
		$(this).click(function(e) {
			e.preventDefault();
			if (confirm('Supprimer l\'article ?'))
			{
				showMessage('Suppression de d\'un article...');
				$.ajax(NEMESIS.BLOG_ROOT+'post/delete/'+$(this).attr('data-id')).done(function(data) {
					showMessage(data);
					location.replace(location.href);
				});
			}
			return false;
		});
	});
});


/*Browser detection patch*/
jQuery.browser = {};
jQuery.browser.mozilla = /mozilla/.test(navigator.userAgent.toLowerCase()) && !/webkit/.test(navigator.userAgent.toLowerCase());
jQuery.browser.webkit = /webkit/.test(navigator.userAgent.toLowerCase());
jQuery.browser.opera = /opera/.test(navigator.userAgent.toLowerCase());
jQuery.browser.msie = /msie/.test(navigator.userAgent.toLowerCase());

