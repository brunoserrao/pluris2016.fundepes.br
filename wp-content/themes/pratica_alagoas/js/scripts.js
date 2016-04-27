$(function(){
	// Caroussel
	$('#banners-home').owlCarousel({
		items	: 1,
		navigation : true,
		autoPlay  : 5000,
		responsive: true,
		// singleItem: true
	});

	// Calendario
	if ($('#calendario').length > 0) {
		$('#calendario').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,basicWeek,basicDay'
			},
			lang: 'pt-br',
			editable: false,
			events: jsonUrl
		});
	};
})