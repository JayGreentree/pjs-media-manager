<?php
/**
 * Determine which template pieces should be used (series or single)
 */

	$type = get_field('type');
	
	if ($type == 'series') {
		include(plugin_dir_path(__FILE__) . 'series.php');
	} else {
		include(plugin_dir_path(__FILE__) . 'single.php');
	}