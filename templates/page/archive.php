<?php
/**
 * Page template used for the media landing page
 */
 
get_header();


	$postType = get_post_type();
	$seriesArchiveTitle = get_field('pjs_mm_series_title', 'option');
	$loadMoreText = get_field('pjs_mm_load_more_text', 'option');
	
	// latest media item
	$latestArgs = array(
		'post_type' => $postType,
		'posts_per_page' => 1,
		'orderby' => 'meta_value',
		'meta_key' => 'date',
		'order' => 'DESC',
		'meta_query' => array(
			array(
				'key'   => 'type',
				'value' => 'item',
			),
		),
	);
	$latestQuery = new WP_Query($latestArgs);
	
	if ($latestQuery->have_posts()) {
		while ($latestQuery->have_posts()) { $latestQuery->the_post();
		
			$seriesTitle = get_the_title($post->post_parent);
			$seriesGraphic = get_field('series_graphic', $post->post_parent);
			$title = get_the_title();
			$date = get_field('date');
			$videoUrl = get_field('video_url');
			$audioUrl = get_field('audio_url');
			$notesUrl = get_field('notes_url');
			$desc = get_field('description');
			
			$speakers = '';
			$speakersList = wp_get_post_terms($post->ID, 'speakers');
			$speakersCount = count($speakersList);
			$i = 1;
			
			foreach($speakersList as $speaker) {
				if ($i == $speakersCount) {
					$speakers .= $speaker->name;
				} else {
					$speakers .= $speaker->name . ', ';
				}
				$i++;
			}
			
			if (strpos($videoUrl, 'youtube.com')) {
				$videoType = 'youtube';
			} elseif (strpos($videoUrl, 'youtu.be')) {
				$videoType = 'youtube';
			} elseif (strpos($videoUrl, 'vimeo.com')) {
				$videoType = 'vimeo';
			} else {
				$videoType = 'mp4';
			}
			
			echo '<section class="pjs-mm-video">';
				echo '<div class="bg" style="background:url(' . $seriesGraphic['url'] . ') no-repeat center / cover;"></div>';
				echo '<div class="wrapper pjs-mm-trans">';
					if ($videoType == 'youtube') {
						include(WP_PLUGIN_DIR . '/pjs-media-manager/templates/player/youtube.php');
					} elseif ($videoType == 'vimeo') {
						include(WP_PLUGIN_DIR . '/pjs-media-manager/templates/player/vimeo.php');
					} elseif ($videoType == 'mp4') {
						include(WP_PLUGIN_DIR . '/pjs-media-manager/templates/player/mp4.php');
					}
					include(WP_PLUGIN_DIR . '/pjs-media-manager/templates/player/audio.php');
					if ($audioUrl || $notesUrl) {
						echo '<div class="links">';
							if ($audioUrl) {
								echo '<a href="javascript:;" class="show-video">Video</a>';
								echo '<a href="javascript:;" class="show-audio">Audio</a>';
							}
							if ($notesUrl) {
								echo '<a href="' . $notesUrl . '" target="_blank">Notes</a>';
							}
						echo '</div>';
					}
					echo '<div class="meta">';
						echo '<h4>' . $seriesTitle . '</h4>';
						echo '<h1>' . $title . '</h1>';
						if ($speakers) {
							echo '<p>' . $date . ' - <b>' . $speakers . '</b></p>';
						} else {
							echo '<p>' . $date . '</p>';
						}
					echo '</div>';
				echo '</div>';
			echo '</section>';
		
		}
	}
	
	// series archive
	$k = 0;
	
	$seriesArgs = array(
		'post_type' => $postType,
		'posts_per_page' => -1,
		'orderby' => 'meta_value',
		'meta_key' => 'series_start_date',
		'order' => 'DESC',
		'meta_query' => array(
			array(
				'key'   => 'type',
				'value' => 'series',
			),
		),
	);
	$seriesQuery = new WP_Query($seriesArgs);
	
	if ($seriesQuery->have_posts()) {
		while ($seriesQuery->have_posts()) { $seriesQuery->the_post();
		
			$seriesId = get_the_ID();
			
			$endDateArgs = array(
				'post_type' => $postType,
				'posts_per_page' => 1,
				'post_parent' => $seriesId,
				'orderby' => 'meta_value',
				'meta_key' => 'date',
				'order' => 'DESC',
			);
			$endDateQuery = new WP_Query($endDateArgs);
			
			if ($endDateQuery->have_posts()) {
				while ($endDateQuery->have_posts()) { $endDateQuery->the_post();
					$endDate = get_field('date');
				}
			}
			
			if ($endDate != '') {
				$k++;
			}
		
		}
	}
	
	echo '<section class="pjs-mm-archive">';
		echo '<div class="wrapper">';
			if ($seriesArchiveTitle) {
				echo '<h1>' . $seriesArchiveTitle . '</h1>';
			}
			echo '<div class="cards" post-type="' . $postType . '" page="1" total="' . $k . '">';
				// cards are added through AJAX call in includes/ajax/load-more.js
			echo '</div>';
			echo '<div class="loader"><img src="' . plugins_url('/pjs-media-manager/images/loader.svg') . '" /></div>';
			echo '<div class="btns">';
				if ($loadMoreText) {
					echo '<a href="javascript:;">' . $loadMoreText . '</a>';
				} else {
					echo '<a href="javascript:;">Load More</a>';
				}
			echo '</div>';
		echo '</div>';
	echo '</section>';


get_footer();
