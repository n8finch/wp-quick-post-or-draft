<?php
/**
 * WP Quick Draft or Post Plugin
 *
 * @package     WPQuickPostDraft\Main
 * @since       1.0.0
 * @author      n8finch
 * @link        https://n8finch.com
 * @license     GNU General Public License 2.0+
 */
namespace WPQuickPostDraft\Main;


add_action( 'wp_footer', __NAMESPACE__ . '\wpqpd_do_form' );


function wpqpd_do_form() {
	echo '<div id="wp-quick-post-draft-form">
 	<h2>New Post</h2>
  <form>
    <fieldset>
      <label for="title">Title</label>
      <input type="text" name="title" id="wpqpd-post-title" placeholder="Post Title" class="text ui-widget-content ui-corner-all">
      <label for="wpqpd-post-content">Content</label>';
//      <textarea name="wpqpd-post-content" id="wpqpd-post-content" rows="8" class="text ui-widget-content ui-corner-all" placeholder="Post Content"></textarea>

	wp_editor( '', 'wpqpd-post-content', $settings = array(
		'textarea_name' => 'wpqpd_post_content',
		'textarea_rows' => 8,
		'media_buttons' => true
	) );
      
    echo '<label for="fep-category">Choose a Category: </label>';

	wp_dropdown_categories( array(
		'id'               => 'wpqpd-post-category',
		'hide_empty'       => 0,
		'name'             => 'post_category',
		'orderby'          => 'name',
		'selected'         => $post['category'],
		'hierarchical'     => true,
		'show_option_none' => __( 'None', 'frontend-publishing' )
	) );

	echo '<div class="wp-quick-post-draft-button-group"><button id="wp-quick-post-draft-button-draft">Save Draft</button> <button id="wp-quick-post-draft-button-post">Post Now</button> <div class="ajax-loader"><img src="' . WPQPD_URL . 'css/spinner.svg" width="32" height="32" /></div></div>     
      
     </fieldset>
  </form>
</div>
 

<button id="wp-quick-post-draft-button"> <span class="dashicons dashicons-welcome-write-blog"></span> </button>';
}