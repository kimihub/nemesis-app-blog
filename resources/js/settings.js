$(function() {

	$('#dropdb').click(function(e) {
		if (confirm('WTF are you doing ??? you\'re about to delete every datas !'))
		{
			showMessage('Emptying database...');
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
			$('#ajax-loading').fadeOut();
			return false;
		}
	};

	$('#purgeimages').click(function(e) {
		if (confirm('Re-generate thumbnails ?'))
		{
			showMessage('Re-generating thumbnails...');
			$('#ajax-loading').fadeIn();
			var medias_id = $.parseJSON(NEMESIS.MEDIAS_ID);
			shiftMedia(medias_id);
		}
		return false;
	})


});
