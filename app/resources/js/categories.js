var getCategoriesOrder = function () {	
	
	var position = [];
	
	$('.sortable li').each(function(index) {
		position.push($(this).attr('data-id'));
	});
	
	return position;
};

var addCategory = function() {
	
	if ($('.nameCategory').val().trim() !='')
	{
		$('#ajax-loading').fadeIn();
		$.post(NEMESIS.BLOG_URL+"cat/manage/", { category: $('.nameCategory').val()}).done(function(data) {
			$('#ajax-loading').fadeOut();
			if (data.alert)
				showMessage('<span class="error">'+data.alert+'</span>');
			else if (data.category_id)
			{
				$('ul.sortable')
					.append(
					$('<li></li>').attr('data-id', data.category_id)
						.append(
							$('<input></input>').attr('type', 'button').val('-').addClass('deleteCat').click(deleteCategory)
						)
						.append($('.nameCategory').val())
					);
				
				$('.sortable').sortable();
				$('.nameCategory').val('');
				hideMessage();
			}			
				
		}, "json");
	}
};

var deleteCategory = function() {
	
	if (confirm('Supprimer la thèmatique ?'))
	{
		var el = $(this);
		var dataId = el.parent().attr('data-id');
		el.parent().remove();
		
		$('#ajax-loading').fadeIn();
		
		$.post(NEMESIS.BLOG_URL+"cat/manage/", { 'delete': dataId, position : getCategoriesOrder()}).done(function(data) {
			$('#ajax-loading').fadeOut();
			hideMessage();
		}, "json");
	}
};

var setOrder = function() {
	var el = this;
	$('#ajax-loading').fadeIn();
	showMessage('Enregistrement de l\'ordre des thèmatiques ...');
	$.post(NEMESIS.BLOG_URL+"cat/manage/", { position : getCategoriesOrder()}).done(function(data) {
		$('#ajax-loading').fadeOut();
		hideMessage();
	}, "json");
};


var setup = function () {

	$('.sortable').sortable();
	
	$('.addCategory').click(addCategory);
	
	$('.deleteCat').click(deleteCategory);
	
	$('.position').click(setOrder);
};

$(setup);