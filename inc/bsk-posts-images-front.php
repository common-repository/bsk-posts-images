<?php

class BSKPostsImagesFront{

	public function __construct() {
		global $wpdb;
		
		add_shortcode('bsk-posts-images', array($this, 'bsk_posts_images_show'));
	}
	
	function bsk_posts_images_show( $atts ){
		$atts = shortcode_atts( array(
									  'type' => 'gallery',
									  'id' => 0,
									  'index' => 0,
									  'linkto' => true
									 ), 
								$atts );
		extract( $atts );
		$return_str = '';
		
		switch( $type ){
			case 'single':
				$return_str = $this->bsk_posts_images_show_single( $id, $index, $linkto );
			break;
			case 'slider':
				$return_str = $this->bsk_posts_images_show_slider( $id );
			break;
			case 'scroller':
				$return_str = $this->bsk_posts_images_show_scroller( $id );
			break;
			case 'gallery':
			default:
				$return_str = $this->bsk_posts_images_show_gallery( $id );
			break;
		}
		
		return $return_str;
	}
	
	function bsk_posts_images_show_single( $id, $index, $linkto ){
		global $post;
		if($id < 1){
			$id = $post->ID;
		}
		$post_saved_images = get_post_meta( $id, 'bsk_posts_images_image_list', true);
		if( !$post_saved_images || 
			count($post_saved_images) < 1 
			|| $index > count($post_saved_images)
			|| !$post_saved_images[$index - 1] ){
			return;
		}
		$image_str = '<img src="'.$post_saved_images[$index - 1].'" alt="'.$post->post_title.'" />';
		if( $linkto != false && $linkto != 'false' ){
			$image_str = '<a href="'.get_permalink($id).'">'.$image_str.'</a>';
		}

		return $image_str;
	}
	
	function bsk_posts_images_show_gallery( $id ){
		global $post;
		if($id < 1){
			$id = $post->ID;
		}
		$post_saved_images = get_post_meta( $id, 'bsk_posts_images_image_list', true);
		if( !$post_saved_images || 
			count($post_saved_images) < 1 ){
			return;
		}
		$saved_gallery_column = get_option('_bsk_posts_images_gallery_column_');
		$saved_gallery_border = get_option('_bsk_posts_images_gallery_border_');
		$column_width = floor( 100 / $saved_gallery_column );
		$gallery_str = 	'<style type="text/css">
							.bsk-posts-images-gallery-'.$id.' {
								margin:0;
								paddign:0;
							}
							.bsk-posts-images-gallery-item-'.$id.' {
								float: left;
								margin: 10px 0 0 0;
								padding:0;
								text-align: center;
								width: '.$column_width.'%;
							}
							.bsk-posts-images-gallery-icon-'.$id.' img {
								border: '.$saved_gallery_border.'px solid #cfcfcf;
								height: auto;
								max-width: 90%;
								padding: 3%;
							}
						</style>';
		$gallery_str .= '<div class="bsk-posts-images-gallery-'.$id.'">';
		foreach($post_saved_images as $image_url){
			if( !$image_url ){
				continue;
			}
			$gallery_str .='<ul class="bsk-posts-images-gallery-item-'.$id.'">
								<li class="bsk-posts-images-gallery-icon-'.$id.'" style="list-style:none;">
									<img src="'.$image_url.'" alt=""/>
								</li>
							</ul>';
		}
							
		$gallery_str .='	<br style="clear: both;" />
						</div>';
		
		return $gallery_str;
	}
	
	function bsk_posts_images_show_slider( $id ){
		global $post;
		if($id < 1){
			$id = $post->ID;
		}
		$post_saved_images = get_post_meta( $id, 'bsk_posts_images_image_list', true);
		if( !$post_saved_images || 
			count($post_saved_images) < 1 ){
			return;
		}

		$width = get_option('_bsk_posts_images_slider_width_'); 
		$width_type = get_option('_bsk_posts_images_slider_width_type_'); 
		if( $width_type == 'percent' && $width > 100){
			$width = 100;
		}
		$height = get_option('_bsk_posts_images_slider_height_');
		$speed = get_option('_bsk_posts_images_slider_speed_');
		$images_count = get_option('_bsk_posts_images_slider_images_count_');
		$width_str = $width.'px';
		if( $width_type == 'percent' ){
			$width_str = $width.'%';
		}
		
		$li_width = floor($width / $images_count);
		
		$li_width = $width_type == 'percent' ? 'width: '.$li_width.'%;' : 'width:'.$li_width.'px;';
		$li_a_width = $width_type == 'percent' ? 'width: 100%;' : 'width:'.$li_width.'px;';
		$li_img_width = $width_type == 'percent' ? 'max-width: 100%;' : 'max-width:'.$li_width.'px;';
		$output = '<style type="text/css">
						.bsk-posts-images-slider-tmpl-1-container{
							'.$li_width.'
						}
						.bsk-posts-images-slider-tmpl-1-container li{
							'.$li_width.'
							height: '.$height.'px;
						}
						.bsk-posts-images-slider-tmpl-1-container li a{
							'.$li_a_width.'
						}
						.bsk-posts-images-slider-tmpl-1-container img {
							'.$li_img_width.'
							max-height: '.$height.'px;
						}
					</style>';
		$output .= '<div class="bsk-posts-images-slider-tmpl-1-container" id="bsk_posts_image_slider_tmpl_1_container_'.$id.'" style="width:'.$width_str.';height:'.$height.'px;">';
		$output .= '<ul id="bsk_posts_images_slider_tmpl_1_'.$id.'" class="bsk-posts-images-slider-tmpl-1">';
		foreach($post_saved_images as $image_url){
			if( !$image_url ){
				continue;
			}
			$output .='<li style="list-style:none;margin:0;padding:0;"><img src="'.$image_url.'" alt=""></li>';
		}
		$output .= '</ul>';
		$output .= '<input type="hidden" name="bsk_posts_images_slider_tmpl_1_speed_'.$id.'" id="bsk_posts_images_slider_tmpl_1_speed_'.$id.'_id" value="'.$speed.'" />';
		$output .= '<input type="hidden" name="bsk_posts_images_slider_tmpl_1_image_count_'.$id.'" id="bsk_posts_images_slider_tmpl_1_image_count_'.$id.'_id" value="'.$images_count.'" />';
		$output .= '</div>';
		
		return $output;
	}
	
	function bsk_posts_images_show_scroller( $id ){
		global $post;
		if($id < 1){
			$id = $post->ID;
		}
		$post_saved_images = get_post_meta( $id, 'bsk_posts_images_image_list', true);
		if( !$post_saved_images || 
			count($post_saved_images) < 1 ){
			return;
		}
		
		$width = get_option('_bsk_posts_images_scroll_width_'); 
		$width_type = get_option('_bsk_posts_images_scroll_width_type_'); 
		if( $width_type == 'percent' && $width > 100){
			$width = 100;
		}
		$height = get_option('_bsk_posts_images_scroll_height_');
		$speed = get_option('_bsk_posts_images_scroll_speed_');
		$direction = get_option('_bsk_posts_images_scroll_direction_');
		$width_str = $width.'px';
		if( $width_type == 'percent' ){
			$width_str = $width.'%';
		}
		
		$output .= '<div class="bsk-posts-images-scroller-container" id="bsk_posts_image_scroller_container_'.$id.'" style="width:'.$width_str.';height:'.$height.'px;">';
		$output .= '<ul id="bsk_posts_images_scroller_'.$id.'" class="bsk-posts-images-scroller">';
		foreach($post_saved_images as $image_url){
			if( !$image_url ){
				continue;
			}
			$output .='<li style="list-style:none;"><img src="'.$image_url.'" alt=""></li>';
		}
		$output .= '</ul>';
		$output .= '<input type="hidden" name="bsk_posts_images_scroller_speed_'.$id.'" id="bsk_posts_images_scroller_speed_'.$id.'_id" value="'.$speed.'" />';
		$output .= '<input type="hidden" name="bsk_posts_images_scroller_direction_'.$id.'" id="bsk_posts_images_scroller_direction_'.$id.'_id" value="'.$direction.'" />';
		$output .= '</div>';
		
		return $output;
	}
}

        