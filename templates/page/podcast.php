<?php
/**
 * Page template used for the video podcast RSS feed
 */

	// variables
	$postType = 'pjs_media';
	$podcastType = get_query_var('podcast-type');
	$image = get_field('image', 'option');
	$title = get_field('title', 'option');
	$subTitle = get_field('sub_title', 'option');
	$desc = get_field('description', 'option');
	$summary = get_field('summary', 'option');
	$name = get_field('owner_name', 'option');
	$email = get_field('owner_email', 'option');
	$keywords = get_field('keywords', 'option');
	$category = get_field('category', 'option');
	$subCategory = get_field('sub_category', 'option');
	
	$link = get_the_permalink();
	$author = get_bloginfo('name');
	$copyright = date('Y') . ' ' . get_bloginfo('name');
	
	// rss feed setup
	header('Content-type: text/xml');
	echo '<?xml version="1.0" encoding="UTF-8"?>';
	echo '<rss xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" version="2.0">';
		echo '<channel>';
			echo '<title>' . $title . '</title>';
			echo '<link>' . $link . '</link>';
			echo '<language>en-us</language>';
			echo '<itunes:subtitle>' . $subTitle . '</itunes:subtitle>';
			echo '<itunes:author>' . $author . '</itunes:author>';
			echo '<itunes:summary>' . $summary . '</itunes:summary>';
			echo '<description>' . $desc . '</description>';
			echo '<itunes:owner>';
				echo '<itunes:name>' . $name . '</itunes:name>';
				echo '<itunes:email>' . $email . '</itunes:email>';
			echo '</itunes:owner>';
			echo '<itunes:explicit>no</itunes:explicit>';
			echo '<itunes:image href="' . $image['url'] . '"></itunes:image>';
			echo '<itunes:category text="' . $category . '">';
				echo '<itunes:category text="' . $subCategory. '"></itunes:category>';
			echo '</itunes:category>';
			echo '<itunes:keywords>' . $keywords . '</itunes:keywords>';
			echo '<copyright>' . $copyright . '</copyright>';
			
			// RSS feed items
			$seriesArgs = array(
				'post_type' => $postType,
				'posts_per_page' => -1,
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
			$seriesQuery = new WP_Query($seriesArgs);
			
			if ($seriesQuery->have_posts()) {
				while ($seriesQuery->have_posts()) { $seriesQuery->the_post();
				
					$title = get_the_title();
					$videoUrl = get_field('video_url');
					$audioUrl = get_field('audio_url');
					$link = get_the_permalink();
					$date = get_field('date');
					$desc = get_field('description');
					
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
					
					if ($podcastType == 'video') {
						if ($videoUrl) {
							
							echo '<item>';
								echo '<title>' . $title . '</title>';
								echo '<itunes:summary>' . $desc . '</itunes:summary>';
								echo '<description>' . $desc . '</description>';
								echo '<link>' . $link . '</link>';
								echo '<enclosure url="' . $videoUrl . '" type="video/quicktime" />';
								echo '<pubDate>' . $date . '</pubDate>';
								echo '<itunes:author>' . $speakers . '</itunes:author>';
								echo '<itunes:explicit>no</itunes:explicit>';
								echo '<itunes:keywords></itunes:keywords>';
								echo '<guid>' . $link . '</guid>';
							echo '</item>';
							
						}
					} else {
						if ($audioUrl) {
							
							echo '<item>';
								echo '<title>' . $title . '</title>';
								echo '<itunes:summary>' . $desc . '</itunes:summary>';
								echo '<description>' . $desc . '</description>';
								echo '<link>' . $link . '</link>';
								echo '<enclosure url="' . $audioUrl . '" type="audio/mpeg" />';
								echo '<pubDate>' . $date . '</pubDate>';
								echo '<itunes:author>' . $speakers . '</itunes:author>';
								echo '<itunes:explicit>no</itunes:explicit>';
								echo '<itunes:keywords></itunes:keywords>';
								echo '<guid>' . $link . '</guid>';
							echo '</item>';
							
						}
					}
				
				}
			}
			
		echo '</channel>';
	echo '</rss>';