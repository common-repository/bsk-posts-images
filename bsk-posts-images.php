<?php

/*
Plugin Name: BSK Posts Images
Plugin URI: http://www.bannersky.com/html/bsk-posts-image.html
Description: The plugin let you upload images attached to one post and let you show all images as a gallery. Or you may show a special image. All image can be linked to the URL you want.
Version: 1.0
Author: BannerSky
Author URI: http://www.bannerksy.com

------------------------------------------------------------------------

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, 
or any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*/



class BSKPostsImages{

	var $_bsk_posts_images_option_page = 'bsk-posts-images';
	
	var $_bsk_posts_images_front_OBJECT = NULL;

	public function __construct() {
		global $wpdb;
		
		add_action('init', array($this, 'bsk_posts_images_post_action'));
		if( is_admin() ) {
			add_action( 'admin_init', array($this, 'bsk_posts_images_register_settings') );
			
			add_action('admin_enqueue_scripts', array($this, 'bsk_posts_images_admin_css_scripts'));
			add_action('load-post.php', array($this, 'bsk_posts_images_meta_box_setup'));
			add_action('load-post-new.php', array($this, 'bsk_posts_images_meta_box_setup'));
			add_action('save_post', array($this, 'bsk_posts_images_meta_box_save_all'));
			
			//add option page
			add_action('admin_menu', array($this, 'bsk_posts_images_option_page'));
		}else{
			add_action('wp_enqueue_scripts', array($this, 'bsk_posts_images_css_and_scripts'));
		}
		
		//hooks
		register_activation_hook(__FILE__, array($this, 'bsk_posts_images_activate'));
		register_deactivation_hook( __FILE__, array($this, 'bsk_posts_images_deactivate' ));
		register_uninstall_hook( __FILE__, 'BSKPostsImages::bsk_posts_images_uninstall' );
		
		require_once('inc/bsk-posts-images-front.php');
		//require_once('inc/bsk-posts-images-widget.php');
		
		$this->_bsk_posts_images_front_OBJECT = new BSKPostsImagesFront();
		//add_action( 'widgets_init', create_function( '', 'register_widget( "BSKPostsImagesWidget" );' ) );
		
		//ajax function
		add_action('wp_ajax_bskpostsimageinsert', array($this, 'bsk_posts_images_ajax_insert_image') );
		add_action('wp_ajax_bskpostsimagedelete', array($this, 'bsk_posts_images_ajax_delete_image') );
		add_action('wp_ajax_bskpostsimagemove', array($this, 'bsk_posts_images_ajax_move_image') );		
		
	}
	
	function bsk_posts_images_activate() {
		$current_options =	get_option('_bsk_posts_images_posttypes_');
		if(!$current_options){
			update_option('_bsk_posts_images_posttypes_', array('post', 'page'));
		}
		
		$current_options =	get_option('_bsk_posts_images_gallery_column_');
		if(!$current_options){
			update_option('_bsk_posts_images_gallery_column_', 3);
		}
		$current_options =	get_option('_bsk_posts_images_gallery_border_');
		if(!$current_options){
			update_option('_bsk_posts_images_gallery_border_', 2);
		}
		//for slider show
		$current_options =	get_option('_bsk_posts_images_slider_width_');
		if(!$current_options){
			update_option('_bsk_posts_images_slider_width_', 100);
		}
		$current_options =	get_option('_bsk_posts_images_slider_width_type_');
		if(!$current_options){
			update_option('_bsk_posts_images_slider_width_type_', 'percent');
		}
		$current_options =	get_option('_bsk_posts_images_slider_height_');
		if(!$current_options){
			update_option('_bsk_posts_images_slider_height_', 200);
		}
		$current_options =	get_option('_bsk_posts_images_slider_speed_');
		if(!$current_options){
			update_option('_bsk_posts_images_slider_speed_', 3);
		}
		$current_options =	get_option('_bsk_posts_images_slider_images_count_');
		if(!$current_options){
			update_option('_bsk_posts_images_slider_images_count_', 4);
		}
		
		//for scroll show
		$current_options =	get_option('_bsk_posts_images_scroll_width_');
		if(!$current_options){
			update_option('_bsk_posts_images_scroll_width_', 100);
		}
		$current_options =	get_option('_bsk_posts_images_scroll_width_type_');
		if(!$current_options){
			update_option('_bsk_posts_images_scroll_width_type_', 'percent');
		}
		$current_options =	get_option('_bsk_posts_images_scroll_height_');
		if(!$current_options){
			update_option('_bsk_posts_images_scroll_height_', 200);
		}
		$current_options =	get_option('_bsk_posts_images_scroll_speed_');
		if(!$current_options){
			update_option('_bsk_posts_images_scroll_speed_', 3);
		}
		$current_options =	get_option('_bsk_posts_images_scroll_direction_');
		if(!$current_options){
			update_option('_bsk_posts_images_scroll_direction_', 'forwards');
		}
		
		// Clear the permalinks
		flush_rewrite_rules();
	}
	
	
	function bsk_posts_images_deactivate(){
		// Clear the permalinks
		flush_rewrite_rules();
	}

    function bsk_posts_images_uninstall(){
		delete_option('_bsk_posts_images_posttypes_');
		
		return;
	}
	
	function bsk_posts_images_register_settings(){
		register_setting( 'bsk-posts-images-settings', '_bsk_posts_images_posttypes_' );
		register_setting( 'bsk-posts-images-settings', '_bsk_posts_images_gallery_column_' );
		register_setting( 'bsk-posts-images-settings', '_bsk_posts_images_gallery_border_' );
		register_setting( 'bsk-posts-images-settings', '_bsk_posts_images_slider_width_'); 
        register_setting( 'bsk-posts-images-settings', '_bsk_posts_images_slider_width_type_'); 
		register_setting( 'bsk-posts-images-settings', '_bsk_posts_images_slider_height_');
		register_setting( 'bsk-posts-images-settings', '_bsk_posts_images_slider_speed_');
		register_setting( 'bsk-posts-images-settings', '_bsk_posts_images_slider_images_count_');
		register_setting( 'bsk-posts-images-settings', '_bsk_posts_images_scroll_width_'); 
        register_setting( 'bsk-posts-images-settings', '_bsk_posts_images_scroll_width_type_'); 
		register_setting( 'bsk-posts-images-settings', '_bsk_posts_images_scroll_height_');
		register_setting( 'bsk-posts-images-settings', '_bsk_posts_images_scroll_speed_');
		register_setting( 'bsk-posts-images-settings', '_bsk_posts_images_scroll_direction_');
	}
	

	function bsk_posts_images_post_action(){
		if( isset( $_POST['bsk_posts_images_action'] ) && strlen($_POST['bsk_posts_images_action']) >0 ) {
			do_action( 'bsk_posts_images_' . $_POST['bsk_posts_images_action'], $_POST );
		}
	}
	
	function bsk_posts_images_admin_css_scripts(){
		wp_enqueue_style('thickbox');
		wp_enqueue_script('thickbox');
		
		wp_enqueue_script('jquery');
		
		wp_enqueue_script('bsk-posts-images-admin', plugins_url('/js/bsk-posts-images-admin.js', __FILE__), array('jquery'));
		//wp_enqueue_style('bsk-posts-images-admin', plugins_url('/css/bsk-posts-images-admin.css', __FILE__));
	}
	
	function bsk_posts_images_css_and_scripts(){
		wp_enqueue_script('jquery');
		

		wp_enqueue_script('bsk-posts-images-slider', plugins_url('js/jcarousellite_1.0.1.min.js', __FILE__), array( 'jquery' ));
		wp_enqueue_script('bsk-posts-images-scroller', plugins_url('js/jquery.simplyscroll.min.js', __FILE__), array('jquery'));
		wp_enqueue_script('bsk-posts-images', plugins_url('js/bsk-posts-images.js', __FILE__), array('jquery', 'bsk-posts-images-scroller', 'bsk-posts-images-slider'));
		
		wp_enqueue_style('bsk-posts-images', plugins_url('/css/bsk-posts-images.css', __FILE__));
	}
	
	
	
	function bsk_posts_images_meta_box_setup(){
		add_action( 'add_meta_boxes', array( $this, 'bsk_posts_images_add_post_meta_box' ) );
	}
	
	function bsk_posts_images_add_post_meta_box() {
		$saved_option = get_option('_bsk_posts_images_posttypes_');
		if(!$saved_option || count($saved_option) < 1){
			return;
		}
		
		foreach($saved_option as $post_type){
			add_meta_box(
				'bsk-posts-images-meta-box-'.$post_type,   // Unique ID
				esc_html__( 'BSK Posts Images'),      // Title
				array( $this, 'bsk_posts_images_extras_meta_box'),      // Callback function
				$post_type,             		  // Admin page (or post type)
				'normal',                				// Context
				'high'                   		// Priority
			);
		}
	}
	
	function bsk_posts_images_extras_meta_box( $object, $box ){
		
		wp_nonce_field( plugin_basename( __FILE__ ), 'bsk_posts_images_extras_meta_box_nonce' );
		?>
        <div class="bsk-posts-images-metabox">
            <ul>
            <?php
			$post_saved_images = get_post_meta( $object->ID, 'bsk_posts_images_image_list', true);
			for($images_item = 1; $images_item < 6; $images_item++){
				$image_url = '';
				if($post_saved_images && count($post_saved_images) > 1){
					$image_url = $post_saved_images[$images_item-1];
				}
			?>
	            <li>
                	<label>Image <?php echo $images_item; ?></label>
                	<div id="wp-content-wrap-0" class="wp-editor-wrap hide-if-no-js wp-media-buttons" style="display:inline-block;">
                        <span id="bsk_posts_images_container_0" class="bsk_posts_images_container" style="width:160px; display:inline-block;">
                            <img src="<?php echo $image_url; ?>" width="150" id="bsk_posts_images_image_id_<?php echo $images_item; ?>" style="display:inline-block;"/>
                        </span>
                    </div>
                    <input type="button" id="bsk_posts_upload_image_btn_id_<?php echo $images_item; ?>" value="Add Media" style="display:inline-block;" rel="<?php echo $images_item; ?>" class="button bsk-posts-images-upload-btn" />
                    <input type="button" id="bsk_posts_images_delete_btn_<?php echo $images_item; ?>" value="Delete" rel="<?php echo $images_item; ?>" style="display:inline-block;" class="button bsk-posts-images-delete-btn" />
                    <input type="button" id="bsk_posts_images_down_btn_<?php echo $images_item; ?>" value="Move down" rel="<?php echo $images_item; ?>" style="display:inline-block;" class="button bsk-posts-images-down-btn" />
                    <input type="button" id="bsk_posts_images_up_btn_<?php echo $images_item; ?>" value="Move up" rel="<?php echo $images_item; ?>" style="display:inline-block;" class="button bsk-posts-images-up-btn" />
                </li>
            <?php
			}
			?>
            </ul>
            <input type="hidden" name="bsk_posts_images_max_image_index" id="bsk_posts_images_max_image_index_id" value="<?php echo ($images_item - 1); ?>" />
            <input type="hidden" name="bsk_posts_images_current_POST_ID" id="bsk_posts_images_current_POST_ID_id" value="<?php echo $object->ID; ?>" />
            <input type="hidden" name="bsk_posts_images_extra_save" value="true" />
        </div>
        <?php
	}
	
	function bsk_posts_images_meta_box_save_all( $post_id ){
		if ( !(isset($_POST['bsk_posts_images_extra_save']) && $_POST['bsk_posts_images_extra_save'] == 'true') ){ 
			return;
		}
		
		// verify this came from the our screen and with proper authorization,
		// because save_post can be triggered at other times
		if ( !wp_verify_nonce( $_POST['bsk_posts_images_extras_meta_box_nonce'], plugin_basename( __FILE__ ) ) ){
			return;
		}
	}
	
	function bsk_posts_images_option_page(){
		require 'inc/bsk-posts-images-option.php';
		
		add_options_page($this->_bsk_posts_images_option_page, 'BSK Posts Images', 'manage_options', $this->_bsk_posts_images_option_page, 'bsk_posts_images_option');
	}
	
	function bsk_posts_images_ajax_insert_image(){
		$image_index = $_POST['index'];
		$image_url = $_POST['image'];
		$post_id = $_POST['postid'];
		//echo '$image_index:'.$image_index.' $image_url;'.$image_url.' $image_id;'.$image_id.' $post_id;'.$post_id;
		if(!$image_index || !$image_url || !$post_id){
			echo 'ERROR';
			die();
		}
		$post_saved_images = get_post_meta( $post_id, 'bsk_posts_images_image_list', true);
		if ( !$post_saved_images ){
			$post_saved_images = array();
			$post_saved_images[0] = "";
			$post_saved_images[1] = "";
			$post_saved_images[2] = "";
			$post_saved_images[3] = "";
			$post_saved_images[4] = "";
		}
		$post_saved_images[$image_index - 1] = $image_url;
		update_post_meta($post_id, 'bsk_posts_images_image_list', $post_saved_images );
		
		echo 'SUCCESS';
		die();
	}
	
	function bsk_posts_images_ajax_delete_image(){
		$image_index = $_POST['index'];
		$post_id = $_POST['postid'];
		
		if(!$image_index || !$post_id){
			echo 'ERROR';
			die();
		}
		$post_saved_images = get_post_meta( $post_id, 'bsk_posts_images_image_list', true);
		$post_saved_images[$image_index - 1] = "";
		update_post_meta( $post_id, 'bsk_posts_images_image_list', $post_saved_images );
		
		echo 'SUCCESS';
		die();
	}
	
	function bsk_posts_images_ajax_move_image(){
		$current_index = $_POST['currindex'];
		$dest_index = $_POST['destindex'];
		$curr_image_url = $_POST['currimage'];
		$dest_image_url = $_POST['destimage'];
		$post_id = $_POST['postid'];
		
		if(!$post_id || !$current_index || !$dest_index){
			echo 'ERROR';
		}
		
		$post_saved_images = get_post_meta( $post_id, 'bsk_posts_images_image_list', true);
		$post_saved_images[$current_index -1] = $dest_image_url;
		$post_saved_images[$dest_index -1] = $curr_image_url;
		update_post_meta( $post_id, 'bsk_posts_images_image_list', $post_saved_images );

		echo 'SUCCESS';
		die();
	}
}

$bsk_posts_images_sample = new BSKPostsImages();        
        