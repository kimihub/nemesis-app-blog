

var setupWyziwym = function() {
	var prevValue = '<empty>';
	var previewOutput = $('#caption-preview');
	var previewTextarea = $('#caption');
	var showdown = new Attacklab.showdown.converter();
	var updateTextareaPreview = function() {
		var newValue = previewTextarea.val();
		if (newValue != prevValue) {
			prevValue = newValue;
			var newHtml = $("<div>"+ showdown.makeHtml(newValue) +"</div>");
			previewOutput.html(newHtml);
		}
	}
	setInterval(updateTextareaPreview, 100);
	$('#caption').val(toMarkdown($('#caption').val()));
	$('#caption').wysiwym(Wysiwym.Markdown, {
		helpEnabled: true,
		helpToggle: true
	});
};

var triggerForm = function(form) {
	var showdown = new Attacklab.showdown.converter();
	$('#caption').val(showdown.makeHtml($('#caption').val()));

	$('.commands').fadeOut(function() {
		$('#ajax-loading').fadeIn();
		$.post(NEMESIS.BLOG_ROOT+'post/add', $(form).serialize()+submitValue).done(function(data) {
			showMessage('Redirect to the post...');
			$('#ajax-loading').fadeOut();
			location.replace(NEMESIS.BLOG_ROOT+'post/'+data);
		});
	});
	return false;
};

var submitValue = '';

$(function() {

	setupWyziwym();
	$('.commands input[type="button"]').click(function() {
		$('html, body').animate({
			scrollTop: $('#'+$(this).attr('data-id')).offset().top - $('.commands').height() - 10
		}, 50);
	});


	$('textarea').autogrow();
	$('input').keydown(function(e) {
		var keyCode = e.keyCode || e.which;
		if (keyCode == 13) {
			if (e.preventDefault) e.preventDefault();
			return false;
		}
	});

	$('input').keyup(function(e) {
			return false;
	});

	$('form input[name="draft"]').click(function(e) {
		e.preventDefault();
		submitValue = '&'+$(this).attr('name')+'='+$(this).val();
		showMessage('Saving post...');
		triggerForm($('form'));
	});

	$('form input[name="save"]').click(function(e) {
		e.preventDefault();
		submitValue = '&'+$(this).attr('name')+'='+$(this).val();
		showMessage('Saving post...');
		triggerForm($('form'));
	});

	$('form').bind('submit', function(e) {
		e.preventDefault();
		return false;
	});
});
