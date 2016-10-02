<?php
/**
 * WP Quick Post or Draft
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
 * Check if WP REST API is installed and activated using TGM Plugin Activation
 */

require_once dirname( __FILE__ ) . '/src/support/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', __NAMESPACE__ . '\wpqpd_register_required_plugins' );

/**
 * Register the required plugins for this theme.
 */
function wpqpd_register_required_plugins() {
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(

		//Call the WP REST API Plugin
		array(
			'name'     => 'WordPress REST API (Version 2)',
			'slug'     => 'rest-api',
			'required' => true,
		),

	);

	/*
	 * Array of configuration settings. Amend each line as needed.
	 */
	$config = array(
		'id'           => 'wp-quick-post-draft',
		// Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',
		// Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins',
		// Menu slug.
		'parent_slug'  => 'plugins.php',
		// Parent menu slug.
		'capability'   => 'manage_options',
		// Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,
		// Show admin notices or not.
		'dismissable'  => true,
		// If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',
		// If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,
		// Automatically activate plugins after installation or not.
		'message'      => '',
		// Message to output right before the plugins table.
		'strings'      => array(
			'notice_can_install_required'     => _n_noop(

				'In order to use the WP Quick Post or Draft Plugin, you will need to have the following plugin installed and activated: %1$s.',
				'In order to use the WP Quick Post or Draft Plugin, you will need to have the following plugin installed and activated: %1$s.',
				'wp-quick-post-draft'
			),
			'notice_can_activate_required'    => _n_noop(
				'In order to use the WP Quick Post or Draft Plugin, you will need to have the following plugin installed and activated: %1$s.',
				'In order to use the WP Quick Post or Draft Plugin, you will need to have the following plugin installed and activated: %1$s.',
				'wp-quick-post-draft'
			),
		)
	);

	tgmpa( $plugins, $config );
}


/**
 * Setup the plugin's constants.
 *
 * @since 1.0.0
 *
 * @return void
 */
function wpqpd_init_constants() {
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
function wpqpd_init_hooks() {
	register_activation_hook( __FILE__, __NAMESPACE__ . '\wpqpd_flush_rewrites' );
	register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
}

/**
 * Flush the rewrites.
 *
 * @since 1.0.0
 *
 * @return void
 */
function wpqpd_flush_rewrites() {
	wpqpd_init_autoloader();

	flush_rewrite_rules();
}

/**
 * Kick off the plugin by initializing the plugin files.
 *
 * @since 1.0.0
 *
 * @return void
 */


function wpqpd_init_autoloader() {

	require_once( 'src/support/autoloader.php' );

	Support\wpqpd_autoload_files( __DIR__ . '/src/' );
}

/**
 * Enqueue jQuery UI and our scripts and styles
 *
 * @since 1.0.0
 *
 * @return void
 */
function wpqpd_add_these_plugin_styles_and_scripts() {
	//enqueue main styles and scripts
	wp_enqueue_style( 'included-styles', WPQPD_URL . 'css/included_styles.css' );
	wp_enqueue_script( 'included-js', WPQPD_URL . 'js/included_js.js', array(
		'jquery',
		'jquery-ui-dialog'
	), false, false );

	//use local data for actually posting to the admin
	wp_localize_script( 'included-js', 'wpqpd_submit_info', array(
			'root'            => esc_url_raw( rest_url() ),
			'nonce'           => wp_create_nonce( 'wp_rest' ),
			'success'         => __( 'Thanks for your submission!', 'your-text-domain' ),
			'failure'         => __( 'Your submission could not be processed.', 'your-text-domain' ),
			'current_user_id' => get_current_user_id()
		)
	);

	wp_enqueue_media();
}

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\wpqpd_add_these_plugin_styles_and_scripts' );

/**
 * Launch the plugin
 *
 * @since 1.0.0
 *
 * @return void
 */
function wpqpd_launch() {
	wpqpd_init_autoloader();
}


add_action( 'init', __NAMESPACE__ . '\wpqpd_init_plugin_files', 999 );

function wpqpd_init_plugin_files() {
	if ( is_user_logged_in() && current_user_can( 'edit_posts' ) ) {
		wpqpd_init_constants();
		wpqpd_init_hooks();
		wpqpd_launch();
	}
}