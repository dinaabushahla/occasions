<?php
/**
 * UnicaEvents Framework: media (images, galleries, video and audio) manipulations
 *
 * @package	unicaevents
 * @since	unicaevents 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('unicaevents_media_theme_setup')) {
	add_action( 'unicaevents_action_before_init_theme', 'unicaevents_media_theme_setup' );
	function unicaevents_media_theme_setup() {

		// Substitute audio, video and galleries in widget text
		add_filter( 'widget_text',						'unicaevents_widget_text_substitutes' );

		// AJAX: Get attachment url
		add_action('wp_ajax_get_attachment_url',		'unicaevents_callback_get_attachment_url');
		add_action('wp_ajax_nopriv_get_attachment_url',	'unicaevents_callback_get_attachment_url');
	}
}







/* Images & galleries
------------------------------------------------------------------------------------- */

// AJAX callback: Get attachment url
if ( !function_exists( 'unicaevents_callback_get_attachment_url' ) ) {
	function unicaevents_callback_get_attachment_url() {
		global $_REQUEST, $UNICAEVENTS_GLOBALS;
		
		if ( !wp_verify_nonce( $_REQUEST['nonce'], $UNICAEVENTS_GLOBALS['ajax_url'] ) )
			die();
	
		$response = array('error'=>'');
		
		$id = (int) $_REQUEST['attachment_id'];
		
		$response['data'] = wp_get_attachment_url($id);
		
		echo json_encode($response);
		die();
	}
}

// Return url from img tag or shortcode, inserted in post
if (!function_exists('unicaevents_get_post_image')) {
	function unicaevents_get_post_image($post_text, $get_src=true) {
		$src = '';
		$tags = array('<img>', '[trx_image]', '[image]');
		for ($i=0; $i<count($tags); $i++) {
			$tag = $tags[$i];
			if (($pos_start = unicaevents_strpos($post_text, unicaevents_substr($tag, 0, -1).' '))!==false) {
				$pos_end = unicaevents_strpos($post_text, unicaevents_substr($tag, -1), $pos_start);
				$tag_text = unicaevents_substr($post_text, $pos_start, $pos_end-$pos_start+1);
				if ($get_src) {
					if (($src = unicaevents_get_tag_attrib($tag_text, $tag, 'src'))=='')
						$src = unicaevents_get_tag_attrib($tag_text, $tag, 'url');
				} else
					$src = $tag_text;
				if ($src!='') break;
			}
		}
		if ($src == '' && $get_src) $src = unicaevents_get_first_url($post_text);
		return $src;
	}
}

// Return gallery tag from photos array
if (!function_exists('unicaevents_build_gallery_tag')) {
	function unicaevents_build_gallery_tag($photos, $w, $h, $zoom=false, $link='') {
		$engine = 'swiper';
		$gallery_text = '';
		$gallery_items_in_bg = unicaevents_get_theme_setting('slides_type')=='bg' && $engine!='chop';
		if (count($photos) > 0) {
			if ($engine == 'chop') {
				$effects2D = array("vertical", "horizontal", "half", "multi");
				$effects3D  = array("3DBlocks", "3DFlips");
				$chop_effect = $effects2D[min(3, mt_rand(0,3))].'|'.($effects3D[min(1, mt_rand(0,1))]);
			}
			$id = "sc_slider_".str_replace('.', '', mt_rand());
			$interval = mt_rand(5000, 10000);
			$gallery_text = '
				<div id="'.esc_attr($id).'" class="sc_slider sc_slider_'.esc_attr($engine)
					.($engine=='swiper' ? ' swiper-slider-container' : '')
					.' sc_slider_controls'
					.' sc_slider_pagination'
					.'"'
					.(!empty($w) && unicaevents_strpos($w, '%')===false ? ' data-old-width="' . esc_attr($w) . '"' : '')
					.(!empty($h) && unicaevents_strpos($h, '%')===false ? ' data-old-height="' . esc_attr($h) . '"' : '')
					.($engine=='chop' ? ' data-effect="'.esc_attr($chop_effect).'"' : '')
					.' data-interval="'.esc_attr($interval).'"'
					.'>
					<div class="slides'
						.($engine=='swiper' ? ' swiper-wrapper' : '').'"'
						.($engine=='swiper' ? ' style="height:'.esc_attr($h).'px;"' : '')
						.'>
					';
			$numSlide = 0;
			if (is_array($photos) && count($photos) > 0) {
				foreach ($photos as $photo) {
					$numSlide++;
					if ($gallery_items_in_bg) {
						$photo_min = unicaevents_get_resized_image_url($photo, $w, $h);
						$gallery_text .= '<div' 
							. ' class="'.esc_attr($engine).'-slide"'
							. ' style="background-image:url(' . esc_url($photo_min) . ');'
							. (!empty($w) ? 'width:' . esc_attr($w) . (unicaevents_strpos($w, '%')!==false ? '' : 'px').';' : '')
							. (!empty($h) ? 'height:' . esc_attr($h) . (unicaevents_strpos($h, '%')!==false ? '' : 'px').';' : '')
							. '">' 
							. ($zoom ? '<a href="'.esc_url($photo).'"></a>' : ($link ? '<a href="'.esc_url($link).'"></a>' : '')) 
							. '</div>';
					} else {
						$photo_min = unicaevents_get_resized_image_tag($photo, $w, $h);
						$gallery_text .= '<div'
							. ' class="'.esc_attr($engine).'-slide' . ($engine=='chop' && $numSlide==1 ? ' cs-activeSlide': '') . '"'
							. ' style="'.($engine=='chop' && $numSlide==1 ? 'display:block;' : '')
							. (!empty($w) ? 'width:' . esc_attr($w) . (unicaevents_strpos($w, '%')!==false ? '' : 'px').';' : '')
							. (!empty($h) ? 'height:' . esc_attr($h) . (unicaevents_strpos($h, '%')!==false ? '' : 'px').';' : '')
							. '">'
							. ($zoom ? '<a href="'. esc_url($photo) . '">'.($photo_min).'</a>' 
									 : (!empty($link) ? '<a href="'. esc_url($link) . '">'.($photo_min).'</a>' : $photo_min))
							. '</div>';
					}
				}
			}
			$gallery_text .= '</div>';
			if ($engine=='swiper' || $engine=='chop') {
				$gallery_text .= '<div class="sc_slider_controls_wrap"><a class="sc_slider_prev" href="#"></a><a class="sc_slider_next" href="#"></a></div>';
				$gallery_text .= '<div class="sc_slider_pagination_wrap"></div>';
			}
			$gallery_text .= '</div>';
			
			unicaevents_enqueue_slider($engine);
			if ($zoom) unicaevents_enqueue_popup();
		}
		return $gallery_text;
	}
}

// Return array with images from gallery, inserted in post
if (!function_exists('unicaevents_get_post_gallery')) {
	function unicaevents_get_post_gallery($text, $id=0, $max_slides=-1) {
		$rez = array();
		$ids = array();
		$ids_list = $orderby = '';
		if ($id > 0) {		// Get by post id
			$gallery  = get_post_gallery( $id, false );
			$ids_list = isset($gallery['ids']) ? $gallery['ids'] : '';
			$orderby  = isset($gallery['orderby']) ? $gallery['orderby'] : '';
		} else if ($text) {	// Parse from text
			$tag = '[gallery]';
			$ids_list = unicaevents_get_tag_attrib($text, $tag, 'ids');
			$orderby  = unicaevents_get_tag_attrib($text, $tag, 'orderby');
		}
		if ($ids_list!='') {
			$ids = explode(',', $ids_list);
			if ($orderby=='rand' || $orderby=='random') {
				shuffle($ids);
			}
		}
		if (is_array($ids) && count($ids) > 0) {
			$cnt = 0;
			foreach ($ids as $v) {
				if ($max_slides > 0 && $cnt++ >= $max_slides) break;
				$src = wp_get_attachment_image_src( $v, 'full' );
				if (isset($src[0]) && $src[0]!='')
					$rez[] = $src[0];
			}
		}
		return $rez;
	}
}

// Substitute standard Wordpress galleries
if (!function_exists('unicaevents_substitute_gallery')) {
	function unicaevents_substitute_gallery($post_text, $post_id, $w, $h, $zoom=false) {
		$tag = '[gallery]';
		$post_photos = false;
		while (($pos_start = unicaevents_strpos($post_text, unicaevents_substr($tag, 0, -1)))!==false) {
			$pos_end = unicaevents_strpos($post_text, unicaevents_substr($tag, -1), $pos_start);
			$tag_text = unicaevents_substr($post_text, $pos_start, $pos_end-$pos_start+1);
			if (($ids = unicaevents_get_tag_attrib($tag_text, $tag, 'ids'))!='') {
				$ids_list = explode(',', $ids);
				$photos = array();
				if (is_array($ids_list) && count($ids_list) > 0) {
					foreach ($ids_list as $v) {
						$src = wp_get_attachment_image_src( $v, 'full' );
						if (isset($src[0]) && $src[0]!='')
							$photos[] = $src[0];
					}
				}
			} else {
				if ($post_photos===false)
					$post_photos = unicaevents_get_post_gallery('', $post_id);
				$photos = $post_photos;
			}
			
			$post_text = unicaevents_substr($post_text, 0, $pos_start) . unicaevents_build_gallery_tag($photos, $w, $h, $zoom) . unicaevents_substr($post_text, $pos_end + 1);
		}
		return $post_text;
	}
}

// Return tag <img> with resized image from specified post or full image url
if (!function_exists('unicaevents_get_resized_image_tag')) {
	function unicaevents_get_resized_image_tag( $post, $w=null, $h=null, $c=null, $u=true, $find_thumb=false, $itemprop=false ) {
		static $mult = 0;
		if ($mult == 0) $mult = min(2, max(1, unicaevents_get_theme_option("retina_ready")));
		if (is_object($post))		$alt = unicaevents_get_post_title( $post->ID );
		else if ((int) $post > 0) 	$alt = unicaevents_get_post_title( $post );
		else						$alt = basename($post);
		$url = unicaevents_get_resized_image_url($post, $w ? $w*$mult : $w, $h ? $h*$mult : $h, $c, $u, $find_thumb);
		return $url!='' ? ('<img class="wp-post-image"' . ($w ? ' width="'.esc_attr($w).'"' : '') . ($h ? ' height="' . esc_attr($h) . '"' : '') . ' alt="' . esc_attr($alt) . '" src="' . esc_url($url) . '"' . ($itemprop ? ' itemprop="image"' : '') . '>') : '';
	}
}

// Return url for the resized image from specified post or full image url
if (!function_exists('unicaevents_get_resized_image_url')) {
	function unicaevents_get_resized_image_url( $post, $w=null, $h=null, $c=null, $u=true, $find_thumb=false ) {
		$url = '';
		if (is_object($post) || abs((int) $post) != 0) {
			$thumb_id = is_object($post) || $post > 0 ? get_post_thumbnail_id( is_object($post) ? $post->ID : $post ) : abs($post);
			if (!$thumb_id && $find_thumb) {
				$args = array(
						'numberposts' => 1,
						'order' => 'ASC',
						'post_mime_type' => 'image',
						'post_parent' => $post,
						'post_status' => 'any',
						'post_type' => 'attachment',
					);
				$attachments = get_children( $args );
				if (is_array($attachments) && count($attachments) > 0) {
					foreach ( $attachments as $attachment ) {
						$thumb_id = $attachment->ID;
						break;
					}
				}
			}
			if ($thumb_id) {
				$src = wp_get_attachment_image_src( $thumb_id, 'full' );
				$url = $src[0];
			}
			if ($url == '' && $find_thumb) {
				if (($data = get_post(is_object($post) ? $post->ID : $post))!==null) {
					$url = unicaevents_get_tag_attrib($data->post_content, '<img>', 'src');
				}
			}
		} else
			$url = trim(chop($post));
		if ($url != '' && $url !== '0') {
			if (unicaevents_strpos($url, '<img')!==false) {
				$url = unicaevents_get_tag_attrib($url, '<img>', 'src');
			}
			if ($c === null) $c = true;	//$c = get_option('thumbnail_crop')==1;
			if ( ! ($new_url = unicaevents_resize_image( $url, $w, $h, $c, true, $u)) ) $new_url = true ? $url : get_the_post_thumbnail($url, array($w, $h));
		} else 
			$new_url = '';
		return $new_url;
	}
}

// Resize and/or crop image
if (!function_exists('unicaevents_resize_image')) {
	function unicaevents_resize_image( $url, $width = null, $height = null, $crop = null, $single = true, $upscale = false ) {
	
		// Validate inputs.
		if ( ! $url || ( ! $width && ! $height ) ) return false;
	
		// Caipt'n, ready to hook.
		if ( true === $upscale ) add_filter( 'image_resize_dimensions', 'unicaevents_resize_image_upscale', 10, 6 );
	
		// Define upload path & dir.
		$upload_info = wp_upload_dir();
		$upload_dir = $upload_info['basedir'];
		$upload_url = $upload_info['baseurl'];
		
		$http_prefix = "http://";
		$https_prefix = "https://";
		
		/* if the $url scheme differs from $upload_url scheme, make them match 
		   if the schemes differe, images don't show up. */
		if (!strncmp($url, $https_prefix, strlen($https_prefix))) 		//if url begins with https:// make $upload_url begin with https:// as well
			$upload_url = str_replace($http_prefix, $https_prefix, $upload_url);
		else if (!strncmp($url, $http_prefix, strlen($http_prefix))) 	//if url begins with http:// make $upload_url begin with http:// as well
			$upload_url = str_replace($https_prefix, $http_prefix, $upload_url);		
		
		// Check if $img_url is local.
		if ( false === strpos( $url, $upload_url ) ) return false;
		
		// Clear thumb sizes from image name
		$url = unicaevents_clear_thumb_sizes($url);
		
		// Define path of image.
		$rel_path = str_replace( $upload_url, '', $url );
		$img_path = ($upload_dir) . ($rel_path);
	
		// Check if img path exists, and is an image indeed.
		if ( ! file_exists( $img_path ) or ! getimagesize( $img_path ) ) return false;
	
		// Get image info.
		$info = pathinfo( $img_path );
		$ext = $info['extension'];
		list( $orig_w, $orig_h ) = getimagesize( $img_path );
	
		// Get image size after cropping.
		$dims = image_resize_dimensions( $orig_w, $orig_h, $width, $height, $crop );
		$dst_w = $dims[4];
		$dst_h = $dims[5];
	
		// Return the original image only if it exactly fits the needed measures.
		if ( ! $dims && ( ( ( null === $height && $orig_w == $width ) xor ( null === $width && $orig_h == $height ) ) xor ( $height == $orig_h && $width == $orig_w ) ) ) {
			$img_url = $url;
			$dst_w = $orig_w;
			$dst_h = $orig_h;
		} else {
			// Use this to check if cropped image already exists, so we can return that instead.
			$suffix = "{$dst_w}x{$dst_h}";
			$dst_rel_path = str_replace( '.' . ($ext), '', $rel_path );
			$destfilename = "{$upload_dir}{$dst_rel_path}-{$suffix}.{$ext}";
	
			if ( ! $dims || ( true == $crop && false == $upscale && ( $dst_w < $width || $dst_h < $height ) ) ) {
				// Can't resize, so return false saying that the action to do could not be processed as planned.
				return false;
			}
			// Else check if cache exists.
			elseif ( file_exists( $destfilename ) && getimagesize( $destfilename ) ) {
				$img_url = "{$upload_url}{$dst_rel_path}-{$suffix}.{$ext}";
			}
			// Else, we resize the image and return the new resized image url.
			else {
	
				$editor = wp_get_image_editor( $img_path );
	
				if ( is_wp_error( $editor ) || is_wp_error( $editor->resize( $width, $height, $crop ) ) )
					return false;
	
				$resized_file = $editor->save();
	
				if ( ! is_wp_error( $resized_file ) ) {
					$resized_rel_path = str_replace( $upload_dir, '', $resized_file['path'] );
					$img_url = ($upload_url) . ($resized_rel_path);
				} else
					return false;
	
			}
		}
	
		// Okay, leave the ship.
		if ( true === $upscale ) remove_filter( 'image_resize_dimensions', 'unicaevents_resize_image_upscale' );
	
		// Return the output.
		if ( $single ) {
			// str return.
			$image = $img_url;
		} else {
			// array return.
			$image = array (
				0 => $img_url,
				1 => $dst_w,
				2 => $dst_h
			);
		}
	
		return $image;
	}
}

// Determination new image dimensions
if (!function_exists('unicaevents_resize_image_upscale')) {
	function unicaevents_resize_image_upscale( $default, $orig_w, $orig_h, $dest_w, $dest_h, $crop ) {
		if ( ! $crop ) return null; // Let the wordpress default function handle this.
	
		// Here is the point we allow to use larger image size than the original one.
		$aspect_ratio = $orig_w / $orig_h;
		$new_w = $dest_w;
		$new_h = $dest_h;
	
		if ( ! $new_w ) {
			$new_w = intval( $new_h * $aspect_ratio );
		}
	
		if ( ! $new_h ) {
			$new_h = intval( $new_w / $aspect_ratio );
		}
	
		$size_ratio = max( $new_w / $orig_w, $new_h / $orig_h );
	
		$crop_w = round( $new_w / $size_ratio );
		$crop_h = round( $new_h / $size_ratio );
	
		$s_x = floor( ( $orig_w - $crop_w ) / 2 );
		$s_y = floor( ( $orig_h - $crop_h ) / 2 );
	
		return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
	}
}

// Clear thumb sizes from image name
if (!function_exists('unicaevents_clear_thumb_sizes')) {
	function unicaevents_clear_thumb_sizes($url) {
		$pi = pathinfo($url);
		$parts = explode('-', $pi['filename']);
		$suff = explode('x', $parts[count($parts)-1]);
		if (count($suff)==2 && (int) $suff[0] > 0 && (int) $suff[1] > 0) {
			array_pop($parts);
			$url = $pi['dirname'] . '/' . join('-', $parts) . '.' . $pi['extension'];
		}
		return $url;
	}
}







/* Audio
------------------------------------------------------------------------------------- */

// Return url from audio tag or shortcode, inserted in post
if (!function_exists('unicaevents_get_post_audio')) {
	function unicaevents_get_post_audio($post_text, $get_src=true) {
		$src = '';
		$tags = array('<audio>', '[trx_audio]', '[audio]');
		for ($i=0; $i<count($tags); $i++) {
			$tag = $tags[$i];
			$tag_end = unicaevents_substr($tag,0,1) . '/' . unicaevents_substr($tag,1);
			if (($pos_start = unicaevents_strpos($post_text, unicaevents_substr($tag, 0, -1).' '))!==false) {
				$pos_end = unicaevents_strpos($post_text, unicaevents_substr($tag, -1), $pos_start);
				$pos_end2 = unicaevents_strpos($post_text, $tag_end, $pos_end);
				$tag_text = unicaevents_substr($post_text, $pos_start, ($pos_end2!==false ? $pos_end2+7 : $pos_end)-$pos_start+1);
				if ($get_src) {
					if (($src = unicaevents_get_tag_attrib($tag_text, $tag, 'src'))=='') {
						if (($src = unicaevents_get_tag_attrib($tag_text, $tag, 'url'))=='' && $i==1) {
							$parts = explode(' ', $tag_text);
							$src = isset($parts[1]) ? str_replace(']', '', $parts[1]) : '';
						}
					}
				} else
					$src = $tag_text;
				if ($src!='') break;
			}
		}
		if ($src == '' && $get_src) $src = unicaevents_get_first_url($post_text);
		return $src;
	}
}

// Substitute audio tags
if (!function_exists('unicaevents_substitute_audio')) {
	function unicaevents_substitute_audio($post_text, $in_frame=true) {
		$tag = '<audio>';
		$tag_end = '</audio>';
		$pos_start = -1;
		while (($pos_start = unicaevents_strpos($post_text, unicaevents_substr($tag, 0, -1).' ', $pos_start+1))!==false) {
			$pos_end = unicaevents_strpos($post_text, unicaevents_substr($tag, -1), $pos_start);
			$pos_end2 = unicaevents_strpos($post_text, $tag_end, $pos_end);
			$tag_text = unicaevents_substr($post_text, $pos_start, ($pos_end2!==false ? $pos_end2+7 : $pos_end)-$pos_start+1);
			if (($src = unicaevents_get_tag_attrib($tag_text, $tag, 'src'))=='')
				$src = unicaevents_get_tag_attrib($tag_text, $tag, 'url');
			if ($src != '') {
				$id = unicaevents_get_tag_attrib($tag_text, $tag, 'id');
				$tag_w = unicaevents_get_tag_attrib($tag_text, $tag, 'width');
				$tag_h = unicaevents_get_tag_attrib($tag_text, $tag, 'height');
				$tag_a = unicaevents_get_tag_attrib($tag_text, $tag, 'align');
				if (!$tag_a) $tag_a = unicaevents_get_tag_attrib($tag_text, $tag, 'data-align');
				$tag_s = unicaevents_get_tag_attrib($tag_text, $tag, 'style')
						. ($tag_w!='' ? 'width:' . esc_attr($tag_w) . (unicaevents_substr($tag_w, -1)!='%' ? 'px' : '') . ';' : '')
						. ($tag_h!='' ? 'height:' . esc_attr($tag_h) . 'px;' : '');
				$pos_end = $pos_end2!==false ? $pos_end2+8 : $pos_end+1;
				$tag_text = '<div'.($id ? ' id="'.esc_attr($id).'"' : '') . ' class="sc_audio_container' . (!$in_frame && $tag_a ? ' align'.esc_attr($tag_a) : '') . '">'
					. (unicaevents_strpos($src, 'soundcloud.com') !== false 
						? '<iframe width="100%" height="166" scrolling="no" frameborder="no" src="'.esc_url('https://w.soundcloud.com/player/?url='.($src)).'"></iframe>'
						: $tag_text)
					. '</div>';
				if ($in_frame) {
					$tag_image = unicaevents_get_tag_attrib($tag_text, $tag, 'data-image');
					$tag_text = unicaevents_get_audio_frame($tag_text, $tag_image, $tag_s);
				}
				$post_text = unicaevents_substr($post_text, 0, (unicaevents_substr($post_text, $pos_start-3, 3)=='<p>' ? $pos_start-3 : $pos_start))
					. ($tag_text)
					. unicaevents_substr($post_text, (unicaevents_substr($post_text, $pos_end, 4)=='</p>' ? $pos_end+4 : $pos_end));
				$pos_start += unicaevents_strlen($tag_text);
				$pos_start = min($pos_start, unicaevents_strlen($post_text)-2); 
			}
		}
		return $post_text;
	}
}

// Return audio frame layout
if (!function_exists('unicaevents_get_audio_frame')) {
	function unicaevents_get_audio_frame($audio, $image_thumb='', $style='') {
		$tag    = '<audio>';
		$tag_w  = unicaevents_get_tag_attrib($audio, $tag, 'width');
		$tag_h  = unicaevents_get_tag_attrib($audio, $tag, 'height');
		$class  = unicaevents_get_tag_attrib($audio, $tag, 'class');
		$author = unicaevents_get_tag_attrib($audio, $tag, 'data-author');
		$title  = unicaevents_get_tag_attrib($audio, $tag, 'data-title');
		$anim   = unicaevents_get_tag_attrib($audio, $tag, 'data-animation');
		$align  = unicaevents_get_tag_attrib($audio, $tag, 'data-align');
		$image  = unicaevents_get_tag_attrib($audio, $tag, 'data-image');
		$image  = !empty($image) ? $image : (!empty($image_thumb) ? $image_thumb : '');

		$style .= !empty($image) ? '' : '';
		$class .= !empty($image) ? ' sc_audio_image' : (!empty($title) || !empty($author) ? ' sc_audio_info' : '');

		$header = '';
		if( !empty($title) || !empty($author) ){
			$header = '<div class="img_container"><img src="' . esc_url($image) . '"></div>
						<div class="sc_audio_header">'
						. ($title  != '' ? '<h6 class="sc_audio_title">'.($title).'</h6>' : '')
						. ($author != '' ? '<div class="sc_audio_author"><span class="sc_audio_author_by">'.esc_html__('by', 'unicaevents').' </span><span class="sc_audio_author_name">'.($author).'</span></div>' : '')
					.'</div>';
		}

		$html = '<div class="sc_audio_player' . ($class ? ' '.esc_attr($class) : '') . ($align ? ' align'.esc_attr($align) : '') . '"' 
						. ' data-width="'.esc_attr($tag_w).'"'
						. ' data-height="'.esc_attr($tag_h).'"'
						. ($anim ? ' data-animation="'.esc_attr($anim).'"' : '')
						. ($style ? ' style="' . esc_attr($style) . '"' : '')
						. '>'
					. ($header)
					. ($audio)
				. '</div>'
			;
		return $html;
	}
}






	
/* Video
------------------------------------------------------------------------------------- */

// Return url from video tag or shortcode, inserted in post
if (!function_exists('unicaevents_get_post_video')) {
	function unicaevents_get_post_video($post_text, $get_src=true) {
		$src = '';
		$tags = array('<video>', '[trx_video]', '[video]', '<iframe>');
		for ($i=0; $i<count($tags); $i++) {
			$tag = $tags[$i];
			$tag_end = unicaevents_substr($tag,0,1) . '/' . unicaevents_substr($tag,1);
			if (($pos_start = unicaevents_strpos($post_text, unicaevents_substr($tag, 0, -1).' '))!==false) {
				$pos_end  = unicaevents_strpos($post_text, unicaevents_substr($tag, -1), $pos_start);
				$pos_end2 = unicaevents_strpos($post_text, $tag_end, $pos_end);
				$tag_text = unicaevents_substr($post_text, $pos_start, ($pos_end2!==false ? $pos_end2+unicaevents_strlen($tag_end)-1 : $pos_end)-$pos_start+1);
				if ($get_src) {
					if (($src = unicaevents_get_tag_attrib($tag_text, $tag, 'src'))=='')
						if (($src = unicaevents_get_tag_attrib($tag_text, $tag, 'url'))=='' && $i==1) {
							$parts = explode(' ', $tag_text);
							$src = isset($parts[1]) ? str_replace(']', '', $parts[1]) : '';
						}
				} else
					$src = $tag_text;
				if ($src!='') break;
			}
		}
		if ($src == '' && $get_src) $src = unicaevents_get_first_url($post_text);
		//if (!unicaevents_is_youtube_url($src) && !unicaevents_is_vimeo_url($src)) $src = '';
		return $src;
	}
}

// Substitute video tags and shortcodes
if (!function_exists('unicaevents_substitute_video')) {
	function unicaevents_substitute_video($post_text, $w, $h, $in_frame=true) {
		$tag = '<video>';
		$tag_end = '</video>';
		$pos_start = -1;
		while (($pos_start = unicaevents_strpos($post_text, unicaevents_substr($tag, 0, -1).' ', $pos_start+1))!==false) {
			$pos_end = unicaevents_strpos($post_text, unicaevents_substr($tag, -1), $pos_start);
			$pos_end2 = unicaevents_strpos($post_text, $tag_end, $pos_end);
			$tag_text = unicaevents_substr($post_text, $pos_start, ($pos_end2!==false ? $pos_end2+unicaevents_strlen($tag_end)-1 : $pos_end)-$pos_start+1);
			if (unicaevents_get_tag_attrib($tag_text, $tag, 'data-frame')=='no') continue;
			if (($src = unicaevents_get_tag_attrib($tag_text, $tag, 'src'))=='')
				$src = unicaevents_get_tag_attrib($tag_text, $tag, 'url');
			if ($src != '') {
				$auto = unicaevents_get_tag_attrib($tag_text, $tag, 'autoplay');
				$src = unicaevents_get_video_player_url($src, $auto!=''); // && is_single());
				$id = unicaevents_get_tag_attrib($tag_text, $tag, 'id');
				$tag_w = unicaevents_get_tag_attrib($tag_text, $tag, 'width');
				$tag_h = unicaevents_get_tag_attrib($tag_text, $tag, 'height');
				$tag_a = unicaevents_get_tag_attrib($tag_text, $tag, 'align');
				if (!$tag_a) $tag_a = unicaevents_get_tag_attrib($tag_text, $tag, 'data-align');
				$tag_s = unicaevents_get_tag_attrib($tag_text, $tag, 'style');
				$tag_s2 = unicaevents_get_tag_attrib($tag_text, $tag, 'data-style');
				$tag_c = unicaevents_get_tag_attrib($tag_text, $tag, 'controls');
				$tag_l = unicaevents_get_tag_attrib($tag_text, $tag, 'loop');
				$tag_image = unicaevents_get_tag_attrib($tag_text, $tag, 'data-image');
				$video = '<iframe'.($id ? ' id="'.esc_attr($id).'"' : '').' class="video_frame' . (!$in_frame && $tag_a ? ' align'.esc_attr($tag_a) : '') . '"'
					. ' src="' . esc_url($src) . '"'
					. ' width="' . esc_attr($tag_w ? $tag_w : $w) . '"'
					. ' height="' . esc_attr($tag_h ? $tag_h : $h) . '"'
					. ($tag_a ? ' data-align="'.esc_attr($tag_a).'"' : '')
					. ' frameborder="0" webkitAllowFullScreen="webkitAllowFullScreen" mozallowfullscreen="mozallowfullscreen" allowFullScreen="allowFullScreen"></iframe>';
				if ( $in_frame && (!is_single() || empty($auto) || !empty($tag_image)) ) {
					$video = unicaevents_get_video_frame($video, $tag_image, $tag_s, $tag_s2);
				}
				$pos_end = $pos_end2!==false ? $pos_end2+8 : $pos_end+1;
				$post_text = unicaevents_substr($post_text, 0, (unicaevents_substr($post_text, $pos_start-3, 3)=='<p>' ? $pos_start-3 : $pos_start)) 
					. ($video)
					. unicaevents_substr($post_text, (unicaevents_substr($post_text, $pos_end, 4)=='</p>' ? $pos_end+4 : $pos_end));
			}
		}
		return $post_text;
	}
}

// Return video frame layout
if (!function_exists('unicaevents_get_video_frame')) {
	function unicaevents_get_video_frame($video, $image='', $style='', $style2='') {
		$tag   = unicaevents_strpos($video, '<iframe')!==false ? '<iframe>' : '<video>';
		$tag_w = unicaevents_get_tag_attrib($video, $tag, 'width');
		$tag_h = unicaevents_get_tag_attrib($video, $tag, 'height');
		$tag_anim = unicaevents_get_tag_attrib($video, $tag, 'data-animation');
		$tag_align = unicaevents_get_tag_attrib($video, $tag, 'data-align');
		$video = '<div class="sc_video_player'.($style2 ? ' sc_video_bordered' : '').($tag_align ? ' align'.esc_attr($tag_align) : '').'"' 
					. ($style2 ? ' style="' . esc_attr($style2) . '"' : '') 
					. ($tag_anim ? ' data-animation="'.esc_attr($tag_anim).'"' : '')
					. '>'
				. '<div class="sc_video_frame' . ($image ? ' sc_video_play_button hover_icon hover_icon_play' : '') . '"' 
					. ' data-width="'.esc_attr($tag_w).'"'
					. ' data-height="'.esc_attr($tag_h).'"'
					. ($image ? ' data-video="'.esc_attr($video).'"' : '') 
					. ($style ? ' style="' . esc_attr($style) . '"' : '')
					. '>'
					. ($image ? (unicaevents_strpos($image, '<img')!==false ? $image : '<img alt="" src="'.esc_url($image).'">') : $video)
				. '</div>'
			. '</div>';
		return $video;
	}
}

// Return video player URL
if (!function_exists('unicaevents_get_video_player_url')) {
	function unicaevents_get_video_player_url($url, $autoplay=false) {
		$url = str_replace(
			array(
				'http://youtu.be/',
				'http://www.youtu.be/',
				'http://youtube.com/watch?v=',
				'http://www.youtube.com/watch?v=',
				'http://youtube.com/watch/?v=',
				'http://www.youtube.com/watch/?v=',
				'http://vimeo.com/',
				'http://www.vimeo.com/',
				'https://youtu.be/',
				'https://www.youtu.be/',
				'https://youtube.com/watch?v=',
				'https://www.youtube.com/watch?v=',
				'https://youtube.com/watch/?v=',
				'https://www.youtube.com/watch/?v=',
				'https://vimeo.com/',
				'https://www.vimeo.com/'
			),
			array(
				'http://youtube.com/embed/',
				'http://youtube.com/embed/',
				'http://youtube.com/embed/',
				'http://youtube.com/embed/',
				'http://youtube.com/embed/',
				'http://youtube.com/embed/',
				'http://player.vimeo.com/video/',
				'http://player.vimeo.com/video/',
				'https://youtube.com/embed/',
				'https://youtube.com/embed/',
				'https://youtube.com/embed/',
				'https://youtube.com/embed/',
				'https://youtube.com/embed/',
				'https://youtube.com/embed/',
				'https://player.vimeo.com/video/',
				'https://player.vimeo.com/video/'
			),
			trim(chop($url)));
		if ($autoplay && $url!='') {
			if (unicaevents_strpos($url, 'autoplay')===false) {
				$url .= (unicaevents_strpos($url, '?')===false ? '?' : '&') . 'autoplay=1';
			}
		}
		return $url;
	}
}

// Return cover image from video url
if (!function_exists('unicaevents_get_video_cover_image')) {
	function unicaevents_get_video_cover_image($url) {
		$image = '';
		if (unicaevents_is_youtube_url($url)) {
			$parts = parse_url($url);
			if (!empty($parts['query'])) {
				parse_str( $parts['query'], $args );
				if (!empty($args['v']))
					$image = $args['v'];
			} else if (!empty($parts['path'])) {
				$args = explode('/', $parts['path']);
				$image = array_pop($args);
			}
			if (!empty($image)) $image = unicaevents_get_protocol().'://i1.ytimg.com/vi/'.($image).'/0.jpg';
		}
		return $image;
	}
}

if (!function_exists('unicaevents_is_youtube_url')) {
	function unicaevents_is_youtube_url($url) {
		return unicaevents_strpos($url, 'youtu.be')!==false || unicaevents_strpos($url, 'youtube.com')!==false;
	}
}

if (!function_exists('unicaevents_is_vimeo_url')) {
	function unicaevents_is_vimeo_url($url) {
		return unicaevents_strpos($url, 'vimeo.com')!==false;
	}
}


/* Other media links
------------------------------------------------------------------------------------- */

// Substitute all media tags
if (!function_exists('unicaevents_substitute_all')) {
	function unicaevents_substitute_all($text, $w=275, $h=200) {
		if (unicaevents_get_custom_option('substitute_gallery')=='yes') {
			$text = unicaevents_substitute_gallery($text, 0, $w, $h);
		}
		$text = unicaevents_do_shortcode(apply_filters('unicaevents_filter_sc_clear_around', $text));
		if (unicaevents_get_custom_option('substitute_video')=='yes') {
			$text = unicaevents_substitute_video($text, $w, $h);
		}
		if (unicaevents_get_custom_option('substitute_audio')=='yes') {
			$text = unicaevents_substitute_audio($text);
		}
		return $text;
	}
}

// Substitute audio, video and galleries in widget text
if ( !function_exists( 'unicaevents_widget_text_substitutes' ) ) {
	function unicaevents_widget_text_substitutes( $text ){
		return unicaevents_substitute_all($text);
	}
}

// Return url from tag a, inserted in post
if (!function_exists('unicaevents_get_post_link')) {
	function unicaevents_get_post_link($post_text) {
		$src = '';
		$target = '';
		$tag = '<a>';
		$tag_end = '</a>';
		if (($pos_start = unicaevents_strpos($post_text, unicaevents_substr($tag, 0, -1).' '))!==false) {
			$pos_end = unicaevents_strpos($post_text, unicaevents_substr($tag, -1), $pos_start);
			$pos_end2 = unicaevents_strpos($post_text, $tag_end, $pos_end);
			$tag_text = unicaevents_substr($post_text, $pos_start, ($pos_end2!==false ? $pos_end2+7 : $pos_end)-$pos_start+1);
			$src = unicaevents_get_tag_attrib($tag_text, $tag, 'href');
			$target = unicaevents_get_tag_attrib($tag_text, $tag, 'target');
		}
		if ($src == '') $src = unicaevents_get_first_url($post_text);
		return array('url'=>$src, 'target'=>$target);
	}
}

// Return first url from post content
if (!function_exists('unicaevents_get_first_url')) {
	function unicaevents_get_first_url($post_text) {
		$src = '';
		if (($pos_start = unicaevents_strpos($post_text, 'http'))!==false) {
			for ($i=$pos_start; $i<unicaevents_strlen($post_text); $i++) {
				$ch = unicaevents_substr($post_text, $i, 1);
				if (unicaevents_strpos("< \n\"\']", $ch)!==false) break;
				$src .= $ch;
			}
		}
		return $src;
	}
}
?>