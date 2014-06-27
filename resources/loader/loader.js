$(function() {

	// ajax loading image
	if (!$('#ajax-loading').length)
		$('<img id="ajax-loading"></img>').attr('src', NEMESIS.BLOG_ROOT+'images/ajax-loader.gif').css({
				'position': 'fixed',
				'right' : '30px',
				'top' : '5px',
				'z-index': '1000'
			}).appendTo(document.body).hide();
			
	showMessage =  function(message) {
		$('.main-container').addClass('loading');
		$('.loader').html(message);
	};
	
	hideMessage =  function(message) {
		$('.main-container').removeClass('loading');
	};
			
});