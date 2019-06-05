$(document).ready(function() {
	
	// show audio player
	$('.pjs-mm-video .wrapper .links .show-audio').click(function() {
		if ($('.pjs-mm-video .wrapper .audio').hasClass('open')) {
			
		} else {
			$('.pjs-mm-video .wrapper .plyr--video').slideUp(300);
			$('.pjs-mm-video .wrapper .video-mp4').slideUp(300);
			$('.pjs-mm-video .wrapper .audio').slideDown(300);
			$('.pjs-mm-video .wrapper .audio').addClass('open');
		}
	});
	
	// show video player
	$('.pjs-mm-video .wrapper .links .show-video').click(function() {
		if ($('.pjs-mm-video .wrapper .audio').hasClass('open')) {
			$('.pjs-mm-video .wrapper .plyr--video').slideDown(300);
			$('.pjs-mm-video .wrapper .video-mp4').slideDown(300);
			$('.pjs-mm-video .wrapper .audio').slideUp(300);
			$('.pjs-mm-video .wrapper .audio').removeClass('open');
		} else {
			
		}
	});
	
});