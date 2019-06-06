<?php
/**
 * Section template used for Youtube videos
 */

	global $videoUrl;
	$videoIdArray = explode('/', $videoUrl);
	
	foreach ($videoIdArray as $videoIdArrayItem) {
		$videoId = $videoIdArrayItem;
	}
	
	if (strpos($videoId, '?v=')) {
		$videoIdArray = explode('?v=', $videoId);
		
		foreach ($videoIdArray as $videoIdArrayItem) {
			$videoId = $videoIdArrayItem;
		}
	}
	
	echo '<div class="video">';
		echo '<iframe src="https://www.youtube.com/embed/' . $videoId . '?origin=https://plyr.io&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1" frameborder="0" allowfullscreen="" allowtransparency="" allow="autoplay"></iframe>';
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