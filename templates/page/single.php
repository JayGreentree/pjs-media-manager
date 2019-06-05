<?php
/**
 * Page template used for media items
 */

get_header();


	$relatedTitle = get_field('pjs_mm_related_title', 'option');
	$slug = get_field('pjs_mm_slug', 'option');
	$viewMediaItem = get_field('pjs_mm_view_media_item_text', 'option');
	$viewAllSeries = get_field('pjs_mm_view_all_series_text', 'option');
	
	if (!$slug) {
		$slug = 'media';
	}
	
	if (!$viewMediaItem) {
		$viewMediaItem = 'View Media Item';
	}
	
	if (!$viewAllSeries) {
		$viewAllSeries = 'View All Series';
	}
	
	$postType = get_post_type();
	$seriesId = $post->post_parent;
	$seriesTitle = get_the_title($seriesId);
	$seriesGraphic = get_field('series_graphic', $seriesId);
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
		echo '<div class="wrapper">';
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
				echo '<p>' . $desc . '</p>';
			echo '</div>';
		echo '</div>';
	echo '</section>';

	// related media items
	$relatedArgs = array(
		'post_type' => $postType,
		'posts_per_page' => -1,
		'post_parent' => $seriesId,
		'post__not_in' => array(get_the_ID()),
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
	$relatedQuery = new WP_Query($relatedArgs);
	
	echo '<section class="pjs-mm-related">';
		echo '<div class="wrapper">';
			if ($relatedTitle) {
				echo '<h1>' . $relatedTitle . '</h1>';
			}
			echo '<div class="cards">';
			
			if ($relatedQuery->have_posts()) {
				while ($relatedQuery->have_posts()) { $relatedQuery->the_post();
				
					// variables
					$title = get_the_title();
					$graphic = get_field('series_graphic', $post->post_parent);
					$link = get_the_permalink();
					$date = get_field('date');
					
					$speakers = '';
					$speakersList = wp_get_post_terms($post->ID, 'speakers');
					$speakersCount = count($speakersList);
					$i = 1;
					
					foreach($speakersList as $speaker):
						if ($i == $speakersCount):
							$speakers .= $speaker->name;
						else:
							$speakers .= $speaker->name . ', ';
						endif;
						$i++;
					endforeach;
					
					echo '<div class="card">';
						echo '<div class="container">';
							echo '<a href="' . $link . '">';
								echo '<div class="image" style="background:url(' . $graphic['sizes']['pjs-mm'] . ') no-repeat center / cover;"></div>';
							echo '</a>';
							echo '<div class="details">';
								echo '<h2>' . $title . '</h2>';
								if ($speakers) {
									echo '<p>' . $date . '<br>' . $speakers . '</p>';
								} else {
									echo '<p>' . $date . '</p>';
								}
								echo '<div class="btns">';
									echo '<a href="' . $link . '">' . $viewMediaItem . '</a>';
								echo '</div>';
							echo '</div>';
						echo '</div>';
					echo '</div>';
				
				}
			}
			
			echo '</div>';
			echo '<div class="btns">';
				echo '<a href="' . get_site_url() . '/' . $slug . '/">' . $viewAllSeries . '</a>';
			echo '</div>';
		echo '</div>';
	echo '</section>';


get_footer();