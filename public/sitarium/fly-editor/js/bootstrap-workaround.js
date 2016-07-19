//+function ($) {
	
	// Small hack to allow multiple Bootstrap to run simultaneously
	if ($.fn.modal) {
//		console.log('$.fn.modal already set');
		$(document).off('click.bs.modal.data-api');
	}
		
	if ($.fn.collapse) {
//		console.log('$.fn.collapse already set');
		$(document).off('click.bs.collapse.data-api');
	}
		
//}(jQuery);
