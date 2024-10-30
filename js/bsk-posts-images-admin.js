var bsk_posts_image_upload_btn_id = -1;
var bsk_posts_image_upload_POST_ID_id = -1;

jQuery(document).ready( function($) {
	var wpActiveEditor;
	
	window.send_to_editor = function(h) {
		var ed, mce = typeof(tinymce) != 'undefined', qt = typeof(QTags) != 'undefined';
		
		//customized process
		var clikced_image_btn_id = 'bsk_posts_upload_image_btn_id_' + bsk_posts_image_upload_btn_id;
		if ( $('#'+clikced_image_btn_id).length > 0 ){
			imgurl = $("img",h).attr("src");
			imageID = $("img",h).attr("data-id");	  
			var start = -1;
			var end   = -1;
			var start_flag = 'src="';
			var fieldName  = '';
			
			start = imgurl.indexOf('wp-content');
			image_relative = imgurl.substr(start, imgurl.length - start);
			
			container_to_fix = 'bsk_posts_images_image_id_' + bsk_posts_image_upload_btn_id;

			if(bsk_posts_image_upload_POST_ID_id < 1){
				return;
			}
			//ajax insert image
			var data = {
				action: 'bskpostsimageinsert',
				index: bsk_posts_image_upload_btn_id,
				image: imgurl,
				postid: bsk_posts_image_upload_POST_ID_id
			};
			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			$.post(ajaxurl, data, function(response) {
				if(response != 'ERROR'){
					$('#'+container_to_fix).attr("src", imgurl);
					$('#'+container_to_fix).css('display', 'inline-block');
				}
			});
				
			try{tb_remove();}catch(e){};
			
			bsk_posts_image_upload_btn_id = -1;
			
			return false;
		}
		
		if ( !wpActiveEditor ) {
			if ( mce && tinymce.activeEditor ) {
				ed = tinymce.activeEditor;
				wpActiveEditor = ed.id;
			} else if ( !qt ) {
				bsk_posts_image_upload_btn_id = -1;
				return false;
			}
		} else if ( mce ) {
			if ( tinymce.activeEditor && (tinymce.activeEditor.id == 'mce_fullscreen' || tinymce.activeEditor.id == 'wp_mce_fullscreen') )
				ed = tinymce.activeEditor;
			else
				ed = tinymce.get(wpActiveEditor);
		}
	
		if ( ed && !ed.isHidden() ) {
			// restore caret position on IE
			if ( tinymce.isIE && ed.windowManager.insertimagebookmark )
				ed.selection.moveToBookmark(ed.windowManager.insertimagebookmark);
	
			if ( h.indexOf('[caption') !== -1 ) {
				if ( ed.wpSetImgCaption )
					h = ed.wpSetImgCaption(h);
			} else if ( h.indexOf('[gallery') !== -1 ) {
				if ( ed.plugins.wpgallery )
					h = ed.plugins.wpgallery._do_gallery(h);
			} else if ( h.indexOf('[embed') === 0 ) {
				if ( ed.plugins.wordpress )
					h = ed.plugins.wordpress._setEmbed(h);
			}
	
			ed.execCommand('mceInsertContent', false, h);
		} else if ( qt ) {
			QTags.insertContent(h);
		} else {
			document.getElementById(wpActiveEditor).value += h;
		}
	
		try{tb_remove();}catch(e){};
		
		bsk_posts_image_upload_btn_id = -1;
	}
	
	
	$(".bsk-posts-images-upload-btn").click(function(){
		button_index = $(this).attr("rel");
		current_post_id = $("#bsk_posts_images_current_POST_ID_id").val();
		bsk_posts_image_upload_btn_id = button_index;
		bsk_posts_image_upload_POST_ID_id = current_post_id;
		
		tb_show("", "media-upload.php?type=image&TB_iframe=true");
		return false;
	});
	
	$(".bsk-posts-images-delete-btn").click(function(){
		button_index = $(this).attr("rel");
		current_post_id = $("#bsk_posts_images_current_POST_ID_id").val();
		bsk_posts_image_upload_btn_id = button_index;
		bsk_posts_image_upload_POST_ID_id = current_post_id;
		
		//ajax insert image
		var data = {
			action: 'bskpostsimagedelete',
			index: bsk_posts_image_upload_btn_id,
			postid: bsk_posts_image_upload_POST_ID_id
		};
		$.post(ajaxurl, data, function(response) {
			if(response != 'ERROR'){
				$('#bsk_posts_images_image_id_'+button_index).attr("src", '');
				$('#bsk_posts_images_image_id_'+button_index).css('display', 'none');
			}
		});
	});
	
	$(".bsk-posts-images-down-btn").click(function(){
		button_index = $(this).attr("rel");
		current_post_id = $("#bsk_posts_images_current_POST_ID_id").val();
		bsk_posts_image_upload_POST_ID_id = current_post_id;
		bsk_max_index = $("#bsk_posts_images_max_image_index_id").val();
		if(button_index >= bsk_max_index){
			return;
		}
		dest_index = parseInt(button_index) + 1;
		current_iamge = $('#bsk_posts_images_image_id_'+button_index).attr("src");
		dest_iamge = $('#bsk_posts_images_image_id_'+dest_index).attr("src");
		
		//ajax insert image
		var data = {
			action: 'bskpostsimagemove',
			currindex: button_index,
			destindex: dest_index,
			currimage: current_iamge,
			destimage: dest_iamge,
			postid: bsk_posts_image_upload_POST_ID_id
		};
		$.post(ajaxurl, data, function(response) {
			if(response != 'ERROR'){
				$('#bsk_posts_images_image_id_' + button_index).attr("src", dest_iamge );
				$('#bsk_posts_images_image_id_' + button_index).css('display', 'inline-block');
				$('#bsk_posts_images_image_id_' + dest_index).attr("src", current_iamge );
				$('#bsk_posts_images_image_id_' + dest_index).css('display', 'inline-block');
			}
		});
	});
	
	$(".bsk-posts-images-up-btn").click(function(){
		button_index = $(this).attr("rel");
		current_post_id = $("#bsk_posts_images_current_POST_ID_id").val();
		bsk_posts_image_upload_POST_ID_id = current_post_id;
		bsk_max_index = $("#bsk_posts_images_max_image_index_id").val();
		if(button_index == 1){
			return;
		}
		dest_index = parseInt(button_index) - 1;
		current_iamge = $('#bsk_posts_images_image_id_'+button_index).attr("src");
		dest_iamge = $('#bsk_posts_images_image_id_'+dest_index).attr("src");
		
		//ajax insert image
		var data = {
			action: 'bskpostsimagemove',
			currindex: button_index,
			destindex: dest_index,
			currimage: current_iamge,
			destimage: dest_iamge,
			postid: bsk_posts_image_upload_POST_ID_id
		};
		$.post(ajaxurl, data, function(response) {
			if(response != 'ERROR'){
				$('#bsk_posts_images_image_id_' + button_index).attr("src", dest_iamge );
				$('#bsk_posts_images_image_id_' + button_index).css('display', 'inline-block');
				$('#bsk_posts_images_image_id_' + dest_index).attr("src", current_iamge );
				$('#bsk_posts_images_image_id_' + dest_index).css('display', 'inline-block');
			}
		});
	});
	
});
