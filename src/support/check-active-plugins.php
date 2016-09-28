<?php


add_action( 'admin_init', __NAMESPACE__ . '\check_for_active_wp_rest_api');


/**
 * Detect plugin. For use in Admin area only.
 *
 * @since 1.0.0
 *
 * @return void
 */
function check_for_active_wp_rest_api() {
	$is_active = is_wp_rest_api_active();
}

/**
 * Checks if the WP REST API is active.
 *
 * @since 1.0.0
 *
 * @return bool
 */
function is_wp_rest_api_active() {
	static $is_active = null;

	if ( is_null( $is_active ) ) {
		$is_active = is_plugin_active( 'rest-api/plugin.php' );
	}

	return $is_active;
}





/* Register activation hook. */
register_activation_hook( __FILE__, __NAMESPACE__ . '\wp_quick_post_or_draft_activation_hook' );

/**
 * Runs only when the plugin is activated.
 */
function wp_quick_post_or_draft_activation_hook() {

	/* Create transient data */
	set_transient( 'wp-quick-post-or-draft-transient', true, 5 );
}


/* Add admin notice */
add_action( 'admin_notices', __NAMESPACE__ . '\wp_quick_post_or_draft_activation_notice' );


/**
 * Admin Notice on Activation.
 */
function wp_quick_post_or_draft_activation_notice(){

	/* Check transient, if available display notice */
	if( is_plugin_active( 'rest-api/plugin.php' ) && get_transient( 'wp-quick-post-or-draft-transient' ) ){
		?>
		<div class="notice notice-success is-dismissible">
			<p><?php _e( 'Looks like the WP-REST API plugin is active. That\'s great! We\'ll go with that to post from the front end. -WP Quick Post or Draft', 'wp-quick-post-or-draft' ); ?></p>
		</div>
		<?php
		/* Delete transient, only display this notice once. */
		delete_transient( 'wp-quick-post-or-draft-transient' );
	} elseif (get_transient( 'wp-quick-post-or-draft-transient' ) ){
		?>
		<div class="notice notice-success is-dismissible">
			<p><?php _e( 'Looks like we\'ll be using XML-RPC to post from the front end. That\'s great! We\'ll go with that . -WP Quick Post or Draft', 'wp-quick-post-or-draft' ); ?></p>
		</div>
		<?php
		/* Delete transient, only display this notice once. */
		delete_transient( 'wp-quick-post-or-draft-transient' );
	}
}
