<?php
/**
 * Page template used for series
 */
	
	// latest media item
	$latestArgs = array(
		'post_type' => get_post_type(),
		'posts_per_page' => 1,
		'post_parent' => get_the_ID(),
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
		
			$link = get_the_permalink();
			
			header('Location: ' . $link);
			die();
		
		}
	}