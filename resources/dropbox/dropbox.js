$(function(){

	var dropbox = $('#dropbox'),
		message = $('.message', dropbox);

	var toggleAttach = function () {
		if ($(this).hasClass('selected'))
		{
			var progress = $(this).find('.progress');
			if (progress)
				progress.remove();

			$(this).removeClass('selected').removeClass('done');
			$('#media_'+$(this).attr('data-id')).removeAttr('name');
		}
		else
		{
			$(this).addClass('selected').addClass('done');
			$('#media_'+$(this).attr('data-id')).attr('name', 'medias[]');
		}
	};

	dropbox.filedrop({
		// The name of the $_FILES entry:
		paramname:'pic',

		maxfiles: 10,
    	maxfilesize: 10,
		url: NEMESIS.BLOG_ROOT+'dropbox/upload',

		uploadFinished:function(i,file,response){

			var result = parseInt(response);

			if (result > 0)
			{
				showMessage('Image envoyée !');
				$.data(file).addClass('done');
				$('.medias').append($('<input></input>').attr('id', 'media_'+response).attr('type', 'hidden').attr('name', 'medias[]').val(response));
				$.data(file).addClass('selected').attr('data-id', response).click(toggleAttach);
			}
			else
				showMessage('<span class="error">Erreur dans l\'envoie de l\'image</span>');

			// response is the JSON object that post_file.php returns
		},

    	error: function(err, file) {
			switch(err) {
				case 'BrowserNotSupported':
					//showMsg('Your browser does not support HTML5 file uploads!');
					break;
				case 'TooManyFiles':
					alert('Too many files! Please select 5 at most! (configurable)');
					break;
				case 'FileTooLarge':
					alert(file.name+' is too large! Please upload files up to 2mb (configurable).');
					break;
				default:
					break;
			}
		},

		// Called before each upload is started
		beforeEach: function(file){
			if(!file.type.match(/^image\//)){
				alert('Only images are allowed!');

				// Returning false will cause the
				// file to be rejected
				return false;
			}
		},

		uploadStarted:function(i, file, len){
			createImage(file, 1);
		},

		progressUpdated: function(i, file, progress) {
			$.data(file).find('.progress').width(progress);
		}

	});

	$('.medias input').each(function () {
		createImage($(this).val());
	});

	function createImage(file, upload, nolazyload){

		if (upload)
			var preview = $('<div></div>').addClass('preview').append($('<span></span>').addClass('imageHolder').append($('<img></img>')).append($('<span></span>').addClass('uploaded'))).append($('<div></div>').addClass('progressHolder').append($('<div></div>').addClass('progress')));
		else
		{
			var preview = $('<div></div>').addClass('preview').append($('<span></span>').addClass('imageHolder').append($('<img></img>')).append($('<span></span>').addClass('uploaded')));
		}

		var	image = $('img', preview);

		if (upload) {

			var reader = new FileReader();

			image.width = 100;
			image.height = 100;

			reader.onload = function(e){

				// e.target.result holds the DataURL which
				// can be used as a source of the image:

				image.attr('src',e.target.result);
			};

			// Reading the file as a DataURL. When finished,
			// this will trigger the onload function above:
			reader.readAsDataURL(file);

			message.hide();
			image.addClass('loaded');
		}
		else
		{
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

			if (!$('#media_'+file).length)
			{
				$('.medias').append($('<input></input>').attr('id', 'media_'+file).attr('type', 'hidden').attr('name', 'medias[]').val(file));
			}

			if ($('#media_'+file).attr('name') == 'medias[]')
			{
				preview.addClass('selected').addClass('done');
			}

			preview.click(toggleAttach);
		}

		dropbox.prepend(preview);

		// Associating a preview container
		// with the file, using jQuery's $.data():

		if (upload)
			$.data(file,preview);
		else
			return preview;
	}

	function showMsg(msg){
		message.html(msg);
	}

	function getExtension(filename) {
		return filename.split('.').pop();
	}

	$('.addLink').bind("keydown", function (e) {
		var keyCode = e.keyCode || e.which;
		if (keyCode == 13) {
			if (e.preventDefault) e.preventDefault();

			$(this).attr('disabled', 'disabled');
			$(this).addClass('disabled');
			var val = $(this).val();
			var el = this;
			var ext = (getExtension(val)).toLowerCase();

			if (ext == 'jpg' || ext == 'jpeg' || ext == 'png' || ext == 'gif')
			{
				$.post(NEMESIS.BLOG_ROOT+'dropbox/upload', {'url': encodeURIComponent(val)}).done(function(data) {
					if (data) {

						console.log(data);

						if (data.error)
							showMessage('<span class="error">'+data.error+'</span>');
						else
						{

							var result = parseInt(data);

							if (result > 0)
							{
								$(el).val('');
								var preview = createImage(data, false, true);
								showMessage('Image uploadée !');
							}
							else
								showMessage('<span class="error">Error when image sent</span>');
						}
					}
					$(el).removeAttr('disabled');
					$(el).removeClass('disabled');
				}, "json");
			}
			else
			{
				$(this).removeAttr('disabled');
				$(this).removeClass('disabled');
			}
			return false;
		}
	});

});
