var loadingCount = 0,
	totalPages = NEMESIS.POSTS_COUNT;

$(function() {
	var pageCount;
	var htmlResponse;
	
	var resizeBody = function() {
		if ($(window).height() > $(document.body).height())
			$(document.body).height($(window).height()+80);
	};
	
	if (totalPages > 1)
	{
	
		resizeBody();
		$(window).resize(resizeBody);
		
		var infiniteScroll = $('.main-container').infiniteScrollHelper({
			
			loadMore: function(page) {
				$('#ajax-loading').fadeIn();
				$.post(NEMESIS.POSTS_ROOT, {'page':((page-1)*NEMESIS.POSTS_NUMBER)}).done(function(data){
					$('#ajax-loading').fadeOut();
					$('#main').append(data);
				});
				pageCount = page;
				$('.loader').text('Chargement des articles suivants ...');
			},
			
			doneLoading: function(pageCount) {
				// we would typical return some sort of loading state variable here to indicate whether we are still loading or not

				loadingCount++;

				if (loadingCount > 10) {
					
					loadingCount = 0;

					if (pageCount >= totalPages) { // if we are at the last page, destroy the plugin instance
						$('.main-container').infiniteScrollHelper('destroy');
					}
					return true;
				}
				else {
				
					$(window).resize(function() {return false});
					return false;
				}
			},
			
			bottomBuffer: 80,
			interval: 50
		});
	}
});