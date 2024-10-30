<?php

function bsk_posts_images_option() {
  
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	$action = 'options.php';
	$post_types = get_post_types();
	unset($post_types['attachment']);
	unset($post_types['revision']);
	unset($post_types['nav_menu_item']);
	
	$saved_posttypes = get_option('_bsk_posts_images_posttypes_');
	?>
	
	<div class="wrap" style="padding-left:15px;">
        <h2>BSK Posts Images</h2>
        <form action="<?php echo $action; ?>" method="POST">
        <h3>Plugin Settings</h3>
    
        <p>Use the settings below to manage what post type do you want to support.</p> 
        <table style="text-align:left;">
        	<?php foreach($post_types as $key => $post_type){ ?>
        	<tr>
            	<td style="width:150px;"><input type="checkbox" name="_bsk_posts_images_posttypes_[]" value="<?php echo $key; ?>" <?php if(in_array($key, $saved_posttypes)){ echo 'checked="checked"'; } ?> /><?php echo $post_type; ?></td>
                <td style="padding-left:20px;"></td>
            </tr>
            <?php } ?>
        </table>
        <br />
        <br />
        <h3>Single Show</h3>
        <p>You may use shortcode <span style="font-weight:bold;">[bsk-posts-images type="single" index="1"]</span> to show a single image. The first image that upload to the post/page where the shortcode stay will be shown. </p>
        <p>You may use shortcode <span style="font-weight:bold;">[bsk-posts-images type="single" id="123" index="1"]</span> to show a single image. The first image that upload to the post/page which ID is 123 will be shown. </p>
        <p>If you want the image link to the post/page which it attached to then you should use shortcode <span style="font-weight:bold;">[bsk-posts-images type="single" index="1" linkto=true]</span> or <span style="font-weight:bold;">[bsk-posts-images type="single" id="123" index="1" linkto=true]</span>. </p>
        <br />
        <br />
        <h3>Gallery Setting</h3>
        <p>Here you may set the column and border if you show the images as a gallery.</p>
        <p>You may use shortcode <span style="font-weight:bold;">[bsk-posts-images type="gallery"]</span> into post/page where you want to show. The images are those that uploaded to the post/page where the shortcode stay.</p>
        <p>You may use shortcode <span style="font-weight:bold;">[bsk-posts-images type="gallery" id="123"]</span> to show all images as gallery. And images are those that uploaded to the post which ID is 123.</p>
        <?php
			$saved_gallery_column = get_option('_bsk_posts_images_gallery_column_');
			$saved_gallery_border = get_option('_bsk_posts_images_gallery_border_');
		?>
        <table style="text-align:left;">
            <tr>
                <td style="width:150px;">
                    <select name="_bsk_posts_images_gallery_column_" style="width:70px;">
                        <?php for($column_item = 1; $column_item < 6; $column_item++){ ?>
                        <option value="<?php echo $column_item; ?>" <?php if($column_item == $saved_gallery_column){ echo 'selected="selected"'; } ?>><?php echo $column_item; ?></option>
                        <?php } ?>            
                    </select>
                </td>
                <td>How many columns to show?</td>
            </tr>
            <tr>
            	<td style="width:150px;"><input type="text" name="_bsk_posts_images_gallery_border_" value="<?php echo $saved_gallery_border; ?>" style="width:50px;" maxlength="1" /> px</td>
                <td>How many pixel(s) to wrap image? </td>
            </tr>
        </table>
        <br />
        <br />
        <h3>Slider Show Setting</h3>
        <p>Here you may set the column and border if you show the images as a slider</p>
        <p>You may use shortcode <span style="font-weight:bold;">[bsk-posts-images type="slider"]</span> to show all images as a slider. The images are those that uploaded to the post/page where the shortcode stay.</p>
        <p>You may use shortcode <span style="font-weight:bold;">[bsk-posts-images type="slider" id="123"]</span> to show all images as a slider. And images are those that uploaded to the post which ID is 123.</p>
		<?php 
            $width = get_option('_bsk_posts_images_slider_width_'); 
            $width_type = get_option('_bsk_posts_images_slider_width_type_'); 
			if( $width_type == 'percent' && $width > 100){
				$width = 100;
			}
			$height = get_option('_bsk_posts_images_slider_height_');
			$speed = get_option('_bsk_posts_images_slider_speed_');
			$images_count = get_option('_bsk_posts_images_slider_images_count_');
        ?>
        <table style="text-align:left;">
            <tr>
            	<td>
                	<input name="_bsk_posts_images_slider_width_" id="_bsk_posts_images_slider_width_id" value="<?php echo $width; ?>" />&nbsp;&nbsp;
                    <select name="_bsk_posts_images_slider_width_type_" id="_bsk_posts_images_slider_width_type_id" style="width:70px;" >
                    	<option value="percent" <?php if ($width_type == 'percent') echo ' selected="selected"'; ?>>%</option>
                        <option value="pixels" <?php if ($width_type == 'pixels') echo ' selected="selected"'; ?>>pixels</option>
                    </select>
                </td>
                <td>Enter a width for you slider ( pixel or %)</td>
            </tr>
            <tr>
            	<td><input name="_bsk_posts_images_slider_height_" value="<?php echo $height; ?>" />&nbsp;&nbsp;&nbsp;px</td>
                <td>Enter a height for you slider (pixel)</td>
            </tr>
            <tr>
            	<?php  ?>
            	<td>
                	<select name="_bsk_posts_images_slider_speed_" style="width:80px;">
                    	<?php
							$speed_val = 6;
							for($i = 1; $i < 6; $i++){
								$speed_val--;
						?>
                        <option value="<?php echo $speed_val; ?>"<?php if ($speed == $speed_val) echo ' selected="selected"' ?>><?php echo $i; ?></option>	
                        <?php
							}
						?>
                    </select>
                </td>
                <td>How fast would you like your slider to move?</td>
            </tr>
            <tr>
            	<td>
                	<select name="_bsk_posts_images_slider_images_count_" style="width:80px;">
                    	<?php 
							for($i = 1; $i < 6; $i++){
						?>
                        <option value="<?php echo $i; ?>"<?php if ($images_count == $i) echo ' selected="selected"' ?>><?php echo $i; ?></option>	
                        <?php
							}
						?>
                    </select>
                </td>
                <td>How many images to display on screen at once?</td>
            </tr>
        </table>
        <br />
        <br />
        <h3>Scroller Show Setting</h3>
        <p>Here you may do seting if you show the images as a scroller</p>
        <p>You may use shortcode <span style="font-weight:bold;">[bsk-posts-images type="scroller"]</span> into post/page where you want to show. The images are those that uploaded to the post/page where the shortcode stay.</p>
        <p>You may use shortcode <span style="font-weight:bold;">[bsk-posts-images type="scroller" id="123"]</span> to show all images as gallery. And images are those that uploaded to the post which ID is 123.</p>
		<?php 
            $width = get_option('_bsk_posts_images_scroll_width_'); 
            $width_type = get_option('_bsk_posts_images_scroll_width_type_'); 
			if( $width_type == 'percent' && $width > 100){
				$width = 100;
			}
			$height = get_option('_bsk_posts_images_scroll_height_');
			$speed = get_option('_bsk_posts_images_scroll_speed_');
			$direction = get_option('_bsk_posts_images_scroll_direction_');
        ?>
        <table style="text-align:left;">
            <tr>
            	<td>
                	<input name="_bsk_posts_images_scroll_width_" id="_bsk_posts_images_scroll_width_id" value="<?php echo $width; ?>" />&nbsp;&nbsp;
                    <select name="_bsk_posts_images_scroll_width_type_" id="_bsk_posts_images_scroll_width_type_id" style="width:70px;" >
                    	<option value="percent" <?php if ($width_type == 'percent') echo ' selected="selected"'; ?>>%</option>
                        <option value="pixels" <?php if ($width_type == 'pixels') echo ' selected="selected"'; ?>>pixels</option>
                    </select>
                </td>
                <td>Enter a width for you scroller ( pixel or %)</td>
            </tr>
            <tr>
            	<td><input name="_bsk_posts_images_scroll_height_" value="<?php echo $height; ?>" />&nbsp;&nbsp;&nbsp;px</td>
                <td>Enter a height for you scroller ( pixel )</td>
            </tr>
            <tr>
            	<?php  ?>
            	<td>
                	<select name="_bsk_posts_images_scroll_speed_" style="width:80px;">
                    	<?php
							for($i = 0; $i < 6; $i++){
						?>
                        <option value="<?php echo $i; ?>"<?php if ($speed == $i) echo ' selected="selected"' ?>><?php echo $i; ?></option>	
                        <?php
							}
						?>
                    </select>
                </td>
                <td>How fast would you like your scroller to move?</td>
            </tr>
            <tr>
            	<td>
                	<select name="_bsk_posts_images_scroll_direction_" style="width:80px;">
                    	<option value="backwards"<?php if ($direction == 'backwards') echo ' selected="selected"' ?>>backwards</option>	
						<option value="forwards"<?php if ($direction == 'forwards') echo ' selected="selected"' ?>>forwards</option>	
                    </select>
                </td>
                <td>What direction to scroll images?</td>
            </tr>
        </table>
        <?php settings_fields( 'bsk-posts-images-settings' ); ?>
        <p style="margin-top: 20px"><button class="button-primary" type="submit" id="bsk_posts_images_option_submit">Save Settings</button></p>
        </form>
        
        <h3>Plugin Support Centre</h3>
        <ul>
            <li><a href="http://www.bannersky.com/html/bsk-posts-images.html" target="_blank">Visit the Support Centre</a> if you have a question on using this plugin</li>
        </ul>
	</div>
<?php
	}
?>

