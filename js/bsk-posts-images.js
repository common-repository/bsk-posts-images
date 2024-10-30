jQuery(document).ready( function($) {

	function bsk_posts_images_scroller(){
		if(! ($('.bsk-posts-images-scroller').length > 0) ){
			return;
		}
		$(".bsk-posts-images-scroller").each(function() {
			var scroller_id = $(this).attr( "id" );
			var scroller_post_id = scroller_id.replace('bsk_posts_images_scroller_', '');
			var speed_val = $("#bsk_posts_images_scroller_speed_" + scroller_post_id + "_id").val();
			var direction_val = $("#bsk_posts_images_scroller_direction_" + scroller_post_id + "_id").val();
			if(speed_val == 0){
				$(this).simplyScroll( {speed:0, auto:false} );
			}else{
				speed_val_int = parseInt(speed_val);
				$("#"+scroller_id).simplyScroll( {speed:speed_val_int, direction:direction_val} );
			}
		});
	}
	
	function bsk_posts_images_slider(){
		if(! ($('.bsk-posts-images-slider-tmpl-1-container').length > 0) ){
			return;
		}
		$(".bsk-posts-images-slider-tmpl-1-container").each(function() {
			var slider_id = $(this).attr( "id" );
			var slider_post_id = slider_id.replace('bsk_posts_image_slider_tmpl_1_container_', '');
			var speed_val = $("#bsk_posts_images_slider_tmpl_1_speed_" + slider_post_id + "_id").val();
			var speed_val_int = parseInt(speed_val)*1000;
			var image_count_val = $("#bsk_posts_images_slider_tmpl_1_image_count_" + slider_post_id + "_id").val();
			var image_count_val_int = parseInt(image_count_val);
			
			jQuery("#" + slider_id).jCarouselLite({
					btnNext: ".next",
					btnPrev: ".prev",
					auto: speed_val_int,
					speed: 500,
					circular: true,
					visible: image_count_val_int,
				});
		});
	}
	
	bsk_posts_images_scroller();
	bsk_posts_images_slider();
});
