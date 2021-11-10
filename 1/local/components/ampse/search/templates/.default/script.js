$(document).ready(function () {

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
				template: '.default'
			},
			beforeSend: function () {
				$(".header-mobile__search .error span").text('');
				$(".header-mobile__search .error").hide();
				$('#header-search-result').html('');
				$(".header-mobile__search-populars").addClass('d-none');
			},
			error: function (jqXHR, textStatus, errorThrown) {

			},
			success: function (res) {
				console.log(res.data);
				if(res.data.error) {
					$(".header-mobile__search-result").addClass("active");
					$(".header-mobile__search-populars").removeClass('d-none');

					$(".header-mobile__search .error span").text(val);
					$(".header-mobile__search .error").show();
				} else {
					$(".header-mobile__search-result").addClass("active");
					//$(".header-mobile__search-populars").addClass('d-none');
					$('#header-search-result').html(res.data.content);
				}
			},
			complete: function (jqXHR, textStatus) {

			}
		});

	}, 2000);

	//header-search
	$(".header-mobile__search-reset").hide();
	$(".header-mobile__search-input").focus(function () {
		$(".menu").addClass('d-none');
		$(".header-mobile__search-content").removeClass('d-none');
		$(".header-mobile__search-cancel").removeClass('d-none');
		$(".header-mobile__search-populars").removeClass('d-none');
	});

	$(".header-mobile__search-cancel").click(function () {
		$(".menu").removeClass('d-none');
		$(".header-mobile__search-content").addClass('d-none');
		$(".header-mobile__search-cancel").addClass('d-none');
		$(".header-mobile__search-populars").addClass('d-none');

		$(".header-mobile__search-input").val('');
		$('#header-search-result').html('');
	});

	$(".header-mobile__search-input").on("input", function () {

		updateSearchResult(this);

		let val = $(this).val();
		if (val.length === 0) {
			$(".header-mobile__search-reset").hide();
		} else {
			$(".header-mobile__search-reset").show();
		}

		/*
		let val = $(this).val();
		let error = false;

		if (val.length === 0) {
			$(".header-mobile__search-result").removeClass("active");
			$(".header-mobile__search-populars").removeClass('d-none');
		} else {
			$(".header-mobile__search-result").addClass("active");
			$(".header-mobile__search-populars").addClass('d-none');
		}

		if(error) {
			$(".error span").text(val);
		}
		*/
	});


	$('.header-mobile__search .populars__list a').click(function (e) {
		e.preventDefault();
		var q = $(this).text();
		$(".header-mobile__search-input").val(q).trigger('input');
	});

});