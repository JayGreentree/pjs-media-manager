<?php
	$path = $_SERVER['DOCUMENT_ROOT'];
	
	include_once $path . '/wp-config.php';
	include_once $path . '/wp-load.php';
	include_once $path . '/wp-includes/wp-db.php';

	global $wpdb;
	global $wp_query;
	
	$postType = $_POST['post_type'];
	$page = $_POST['paged'];
	$results = array();
	$results['page'] = $page + 1;
	
	$seriesArgs = array(
		'post_type' => $postType,
		'posts_per_page' => 6,
		'orderby' => 'meta_value',
		'meta_key' => 'series_start_date',
		'order' => 'DESC',
		'paged' => $page,
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
		
			// variables
			$seriesId = get_the_ID();
			$title = get_the_title();
			$graphic = get_field('series_graphic');
			$link = get_the_permalink();
			$startDate = get_field('series_start_date');
			$viewSeriesText = get_field('pjs_mm_view_series_text', 'option');
			$endDate = '';
			
			if (!$viewSeriesText) {
				$viewSeriesText = 'View Series';
			}
			
			// get end date
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
				
				if ($startDate == $endDate) {
					$date = $startDate;
				} else {
					$date = $startDate . ' - ' . $endDate;
				}
				
				$results['cards'] .= '<div class="card hidden pjs-mm-trans">';
					$results['cards'] .= '<div class="container">';
						$results['cards'] .= '<a href="' . $link . '">';
							$results['cards'] .= '<div class="image" style="background:url(' . $graphic['sizes']['pjs-mm'] . ') no-repeat center / cover;"></div>';
						$results['cards'] .= '</a>';
						$results['cards'] .= '<div class="details">';
							$results['cards'] .= '<h2>' . $title . '</h2>';
							$results['cards'] .= '<p>' . $date . '</p>';
							$results['cards'] .= '<div class="btns">';
								$results['cards'] .= '<a href="' . $link . '">' . $viewSeriesText . '</a>';
							$results['cards'] .= '</div>';
						$results['cards'] .= '</div>';
					$results['cards'] .= '</div>';
				$results['cards'] .= '</div>';
				
			}
		
		}
	}
	
	echo json_encode($results);