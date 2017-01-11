$(document).ready(function() {
	(function() {
		$('<i id="back-to-top"></i>').appendTo($('body'));

		$(window).scroll(function() {
			if($(this).scrollTop() != 0) {
				$('#back-to-top').fadeIn();	
			} else {
				$('#back-to-top').fadeOut();
			}
		});
		
		$('#back-to-top').click(function() {
			$('body,html').animate({scrollTop:0},600);
		});
	})();
});