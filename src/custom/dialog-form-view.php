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


add_action( 'wp_footer', __NAMESPACE__ . '\quickcheck' );

function quickcheck() {
	echo '<div id="wp-quick-post-draft-form">
 	<h2>New Post</h2>
  <form>
    <fieldset>
      <label for="title">Title</label>
      <input type="text" name="title" id="wpqpd-post-title" placeholder="Post Title" class="text ui-widget-content ui-corner-all">
      <label for="wpqpd-post-content">Content</label>
      <textarea name="wpqpd-post-content" id="wpqpd-post-content" rows="8" class="text ui-widget-content ui-corner-all" placeholder="Post Content"></textarea>
      
      <label for="fep-category">Choose a Category</label>';

	wp_dropdown_categories( array(
		'id'               => 'wpqpd-post-category',
		'hide_empty'       => 0,
		'name'             => 'post_category',
		'orderby'          => 'name',
		'selected'         => $post['category'],
		'hierarchical'     => true,
		'value_field'      => 'slug',
		'show_option_none' => __( 'None', 'frontend-publishing' )
	) );

	echo '


      

	  <button id="wp-quick-post-draft-button-draft">Save Draft</button> <button id="wp-quick-post-draft-button-post">Post Now</button>      
      
     </fieldset>
  </form>
</div>
 

<button id="wp-quick-post-draft-button">New Post</button>';
}