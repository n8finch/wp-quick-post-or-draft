<?php
/**
 * WP Quick Post or Draft Plugin
 *
 * @package     WPQuickPostDraft
 * @author      n8finch
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: WP Quick Post or Draft Plugin
 * Plugin URI:  https://n8finch.com
 * Description: Allows logged in user with appropriate permissions to post quickly on the front end via XML-RPC or the WP REST API if enabled.
 * Version:     1.0.0
 * Author:      Nate Finch
 * Author URI:  https://n8finch.com
 * Text Domain: wp-quick-post-draft
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

namespace WPQuickPostDraft;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Cheatin&#8217; uh?' );
}

/**
 * Setup the plugin's constants.
 *
 * @since 1.0.0
 *
 * @return void
 */
function init_constants() {
	$plugin_url = plugin_dir_url( __FILE__ );
	if ( is_ssl() ) {
		$plugin_url = str_replace( 'http://', 'https://', $plugin_url );
	}

	define( 'WPQPD_URL', $plugin_url );
	define( 'WPQPD_DIR', plugin_dir_path( __DIR__ ) );
}

/**
 * Initialize the plugin hooks
 *
 * @since 1.0.0
 *
 * @return void
 */
function init_hooks() {
	register_activation_hook( __FILE__, __NAMESPACE__ . '\flush_rewrites' );
	register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
}

/**
 * Flush the rewrites.
 *
 * @since 1.0.0
 *
 * @return void
 */
function flush_rewrites() {
	init_autoloader();

//	Custom\register_custom_post_type();

	flush_rewrite_rules();
}

/**
 * Kick off the plugin by initializing the plugin files.
 *
 * @since 1.0.0
 *
 * @return void
 */


function init_autoloader() {

	require_once( 'src/support/autoloader.php' );

	Support\autoload_files( __DIR__ . '/src/' );
}

/**
 * Enqueue jQuery UI and our scripts and styles
 *
 * @since 1.0.0
 *
 * @return void
 */
function add_these_plugin_styles_and_scripts() {
	wp_enqueue_style( 'included-styles', WPQPD_URL . 'css/included_styles.css' );
	wp_enqueue_script( 'included-js', WPQPD_URL . 'js/included_js.js', array(
		'jquery',
		'jquery-ui-dialog'
	), false, false );
}

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\add_these_plugin_styles_and_scripts' );

/**
 * Launch the plugin
 *
 * @since 1.0.0
 *
 * @return void
 */
function launch() {
	init_autoloader();
}


add_action('init', __NAMESPACE__ . '\init_plugin_files', 999);

function init_plugin_files() {
	if ( current_user_can('administrator') ) {
		init_constants();
		init_hooks();
		launch();
	}
}