<?php
/**
 * Section template used for Vimeo videos
 */

	global $videoUrl;
	$videoIdArray = explode('/', $videoUrl);
	
	foreach ($videoIdArray as $videoIdArrayItem) {
		$videoId = $videoIdArrayItem;
	}
	
	echo '<div class="video">';
		echo '<iframe src="https://player.vimeo.com/video/' . $videoId . '?loop=false&amp;byline=false&amp;portrait=false&amp;title=false&amp;speed=true&amp;transparent=0&amp;gesture=media" frameborder="0" allowfullscreen="" allowtransparency="" allow="autoplay"></iframe>';
	echo '</div>';
?>

<script>
	$(document).ready(function(){
		
		// create video player
		const videoPlayer = new Plyr(document.querySelector('.pjs-mm-video .video'), {
			fullscreen: {iosNative:true},
			settings: ['captions', 'quality', 'loop'],
		});
		
		// pause video player
		$('.pjs-mm-video .wrapper .links .show-audio').click(function(){
			if ($('.pjs-mm-video .wrapper .audio').hasClass('open')) {
				videoPlayer.pause();
			} else {
				
			}
		});
		
	});
</script>