$(function(){
	
	var dropbox = $('#dropbox');
	
	var toggleAttach = function () {
		if ($(this).hasClass('selected'))
		{
			$(this).removeClass('selected').removeClass('done');
		}
		else
		{
			$(this).addClass('selected').addClass('done');
		}
	};
	
	$('.medias input').each(function () {
		createImage($(this).val());
	});
	
						
	function createImage(file,nolazyload){

		
		var preview = $('<div></div>').addClass('preview').append($('<span></span>').addClass('imageHolder').append($('<img></img>')).append($('<span></span>').addClass('uploaded')));
		
		var	image = $('img', preview);
			
		
		image.addClass('loaded');
		
		if (!nolazyload)
		{
			image.attr('data-original', NEMESIS.BLOG_ROOT+'images/small/'+file);
			image.addClass('lazy');
			image.attr('src', NEMESIS.BLOG_ROOT+'images/pixel.gif');
			image.show().lazyload({ 
				effect : "fadeIn"
			});
		}
		else
		{
			image.attr('src', NEMESIS.BLOG_ROOT+'images/small/'+file);
		}
		
		preview.attr('data-id', file);
		
		preview.click(toggleAttach);

		dropbox.prepend(preview);
		
		return preview;
	}

	var deleteItems = function(el) {
		if ($(el) && $(el).length)
		{
			$.ajax(NEMESIS.BLOG_ROOT+'dropbox/delete/'+$(el).eq(0).attr('data-id')).done(function(data) {
				if (data)
				{
					displayLog('L\'image '+data+ ' vient d\'être supprimée');
					$(el).eq(0).remove();
				}
				deleteItems('.selected');
			}); 
		}
		else
		{
			showMessage('Suppression terminée');
			$('#ajax-loading').fadeOut(function() {
				$('.commands').fadeIn();
			});		
			return false;
		}
	};
		
	$('#deleteSelected').click(function (e) {
		if ($('.selected').length) {
			if (confirm('Supprimer les éléments sélectionnés ?')) {
				
				$('.commands').fadeOut(function() {
					showMessage('Suppression des éléments sélectionnés...');
					$('#ajax-loading').fadeIn();
					displayLog('');
					deleteItems('.selected');
				});
				
			}
		}
		else
			showMessage('<span clas="error">Aucun élément n\'est sélectionné</span>');
		return false;
	});

});