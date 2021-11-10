$(document).ready(function () {

	let input       = $("#searchSection .basket__section-input");
	let loader 		= $('#searchSection .form-field__load');
	let cancel 		= $('#searchSection .form-field__cancel');
	let select 		= $('#searchSection .form-field__select');
	let content 	= $('#searchSection .form-field__select-wrapper');
	let error 		= $('#searchSection .form-field__error');
	let error_text 	= $('#searchSection .form-field__error > span');
	let populars 	= $('#searchSection .populars');

	let updateSearchResult = limitExecByInterval(function(input) {

		let val = $(input).val();

		if(val.length < 3) {
			return;
		}

		var query = {
			c: 'upside:search',
			action: 'search',
			mode: 'ajax'
		};

		$.ajax({
			url: '/bitrix/services/main/ajax.php?' + $.param(query, true),
			type: 'POST',
			data: {
				q: val,
				template: 'page-search'
			},
			beforeSend: function () {
				$(error).addClass('d-none');
				$(error_text).text(val);
				$(select).addClass('d-none');
				$(loader).removeClass('d-none');

				$(cancel).removeClass('d-none');
			},
			error: function (jqXHR, textStatus, errorThrown) {

			},
			success: function (res) {
				console.log(res.data.error);
				if(res.data.error) {
					$(error).removeClass('d-none');
					$(populars).removeClass('d-none');
					$('#retairocketSearch').innerHTML = '<div data-retailrocket-markup-block="6114d30197a52823682a43c3" data-search-phrase="search_phrase"></div>';
					retailrocket.markup.render()
				} else {
					$(select).removeClass('d-none');
					$(error).addClass('d-none');
					$(content).html(res.data.content);
					$(populars).addClass('d-none');
					$('#retairocketSearch').innerHTML = '<div data-retailrocket-markup-block="6114d31097a52823682a43c4" data-search-phrase="search_phrase"></div>';
					retailrocket.markup.render();
				}

				$(loader).addClass('d-none');
			},
			complete: function (jqXHR, textStatus) {

			}
		});

	}, 2000);


	$(input).on("input", function () {
		updateSearchResult(this);
	});

	$(cancel).click(function (e) {
		e.preventDefault();
		$(error).addClass('d-none');
		$(error_text).text('');
		$(input).val('');
		$(select).addClass('d-none');
		$(loader).addClass('d-none');

		$(cancel).addClass('d-none');
		$(populars).removeClass('d-none');
	});


	$('.populars__list a').click(function (e) {
		e.preventDefault();
		var q = $(this).text();
		$(input).val(q).trigger('input');
		if (!$(document).find('.search-section').hasClass('active')) {
			$(document).find('.sidebar_search').click();
		}
	});
});