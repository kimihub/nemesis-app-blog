$(function() {
	
	$('#dropdb').click(function(e) {
		if (confirm('Mais qu\'est-ce que tu fous ??? t\'es sur le point de tout effacer !'))
		{
			showMessage('Suppression de la base de donnée...');
			$.ajax(NEMESIS.BLOG_ROOT+'settings/dropdb').done(function(data) {
				displayLog(data.toString());
				$('#ajax-loading').fadeOut();
				hideMessage();
			});
		}
		return false;
	});
	
	var shiftMedia = function (medias_id) {
		
		if (medias_id.length)
		{
			$.post(NEMESIS.BLOG_ROOT+'settings/purgecacheimages', {id: medias_id.shift()}).done(function(data) {
				displayLog(data.toString());
				shiftMedia(medias_id);
			});
		}
		else
		{
			hideMessage();
			$('.ajax-loading').fadeOut();
			return false;
		}
	};
	
	$('#purgeimages').click(function(e) {
		if (confirm('Re-generer toutes les miniatures ?'))
		{
			showMessage('Re-création des miniatures...');
			$('.ajax-loading').fadeIn();
			var medias_id = $.parseJSON(NEMESIS.MEDIAS_ID);
			shiftMedia(medias_id);
		}
		return false;
	})


});