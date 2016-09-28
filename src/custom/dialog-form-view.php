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


add_action('wp_footer', __NAMESPACE__ . '\quickcheck');

function quickcheck() {
	echo '<div id="wp-quick-post-draft-form" title="New Post"> 
  <form>
    <fieldset>
      <label for="title">Title</label>
      <input type="text" name="title" id="wpqpd-post-title" value="Post Title" class="text ui-widget-content ui-corner-all">
      <label for="wpqpd-post-content">Content</label>
      <textarea name="wpqpd-post-content" id="wpqpd-post-content" rows="8" class="text ui-widget-content ui-corner-all"></textarea>
      
      <label for="fep-category">Choose a Category</label>';

      wp_dropdown_categories( array( 'i'               => 'wpqpd-category',
	                               'hide_empty'       => 0,
	                               'name'             => 'post_category',
	                               'orderby'          => 'name',
	                               'selected'         => $post['category'],
	                               'hierarchical'     => true,
	                               'show_option_none' => __( 'None', 'frontend-publishing' )
	) );

	echo '<input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
      

	  <button id="wp-quick-post-draft-button-draft">Save Draft</button> <button id="wp-quick-post-draft-button-post">Post Now</button>      
      
     </fieldset>
  </form>
</div>
 

<button id="wp-quick-post-draft-button">New Post</button>';
}