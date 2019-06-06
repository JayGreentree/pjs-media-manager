<?php
/**
 * Plugin Name: PJS Media Manager
 * Plugin URI: https://www.plainjoestudios.com/
 * Description: Manage your media and display them elegantly on your website.
 * Version: 1.1.7
 * Author: PlainJoe Studios
 * Author URI: https://www.plainjoestudios.com/
 * License: GPLv2 or later
 * Text Domain: pjs-media-manager
 */

if (!defined('ABSPATH')) {
	die();
}

if (!class_exists('pjsMediaManager')) {
	
	define('PLUGIN_PATH', plugin_dir_path(__FILE__));
	define('PLUGIN_URL', plugin_dir_url(__FILE__));
	
	// create class pjsMediaManager
	class pjsMediaManager {
		
		function __construct() {
			add_action('init', array($this, 'create_pjs_media'));
			add_filter('template_include', array($this, 'templates'));
		}
		
		function register_scripts() {
			add_action('wp_enqueue_scripts', array($this, 'enqueue'));
		}
		
		function activate() {
			if (is_plugin_active('advanced-custom-fields-pro/acf.php')) {
				$this->create_pjs_media();
				flush_rewrite_rules();
			} else {
				$requireAcfErr = 'Sorry, this plugin requires the Advanced Custom Fields Pro plugin to be installed and active.';
				$requireAcfErr .= '<br><a href="' . admin_url('plugins.php') . '">Return to Plugins</a>';
				wp_die($requireAcfErr);
			}
		}
		
		function deactivate() {
			flush_rewrite_rules();
		}
		
		function create_pjs_media() {
			require_once(PLUGIN_PATH . '/templates/post-type/media.php');
		}
		
		function enqueue() {
			// plugin JS/CSS
			wp_enqueue_style('pjs-media-manager', PLUGIN_URL . '/css/pjs.css', null);
			wp_enqueue_style('pjs-media-manager-responsive', PLUGIN_URL . '/css/responsive.css', null);
			wp_enqueue_script('pjs-media-manager-jquery', PLUGIN_URL . '/js/jquery.min.js', null);
			wp_enqueue_script('pjs-media-manager-javascript', PLUGIN_URL . '/js/pjs.js', null);
			wp_enqueue_script('pjs-media-manager-ajax', PLUGIN_URL . '/includes/ajax/load-more.js', null);
			
			// included plyr JS/CSS
			wp_enqueue_style('pjs-media-manager-plyr', PLUGIN_URL . '/includes/plyr/plyr.min.css', null);
			wp_enqueue_script('pjs-media-manager-plyr', PLUGIN_URL . '/includes/plyr/plyr.min.js', null);
		}
		
		// setup templates
		function templates($template) {
			$queryVars = get_query_var('podcast-type');
			
			if (is_singular('pjs_media')) {
				return PLUGIN_PATH . '/templates/page/type.php';
			} elseif (is_post_type_archive('pjs_media')) {
				return PLUGIN_PATH . 'templates/page/archive.php';
			}
			
			if ($queryVars == 'audio' || $queryVars == 'video') {
				return PLUGIN_PATH . '/templates/page/podcast.php';
			}
			
			return $template;
		}
		
	}
	
	$pjsMediaManager = new pjsMediaManager();
	$pjsMediaManager->register_scripts();
	
	// activation
	register_activation_hook(__FILE__, array($pjsMediaManager, 'activate'));
	
	// deactivation
	register_deactivation_hook(__FILE__, array($pjsMediaManager, 'deactivate'));
	
	// flush rewrite rules upon saving the settings
	function pjs_mm_settings_update() {
		if (!$option = get_option('pjs-mm-flush-rewrite-rules')) {
			return false;
		}
		
		if ($option == 1) {
			flush_rewrite_rules();
			update_option('pjs-mm-flush-rewrite-rules', 0);
		}
		
		return true;
	}
	add_action('init', 'pjs_mm_settings_update', 99999);

	function pjs_mm_settings_save() {
		update_option('pjs-mm-flush-rewrite-rules', 1);
		return true;
	}
	add_action('acf/save_post', 'pjs_mm_settings_save', 10, 2);


	// creates a new image size for uploaded images
	function pjs_mm_image_size() {
		add_image_size('pjs-mm', 800, 450, false);
	}
	add_action('init', 'pjs_mm_image_size');


	// creates the ACF fields used in the plugin
	function pjs_mm_create_acf_fields() {
		require_once(PLUGIN_PATH . '/includes/acf/fields.php');
	}
	add_action('acf/init', 'pjs_mm_create_acf_fields');


	// creates the podcast feed
	function pjs_mm_podcast_query_vars($vars) {
		$vars[] = 'podcast-type';
		return $vars;
	}
	add_filter('query_vars', 'pjs_mm_podcast_query_vars');

	function pjs_mm_podcast_rewrite_rule() {
		add_rewrite_tag('%type%', '([^&]+)');
		add_rewrite_rule('podcast/([^/]*)/?', 'index.php?podcast-type=$matches[1]', 'top');
		flush_rewrite_rules();
	}
	add_action('init', 'pjs_mm_podcast_rewrite_rule', 10, 0);
	
}