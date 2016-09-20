<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('invetex_sc_audio_theme_setup')) {
	add_action( 'invetex_action_before_init_theme', 'invetex_sc_audio_theme_setup' );
	function invetex_sc_audio_theme_setup() {
		add_action('invetex_action_shortcodes_list', 		'invetex_sc_audio_reg_shortcodes');
		if (function_exists('invetex_exists_visual_composer') && invetex_exists_visual_composer())
			add_action('invetex_action_shortcodes_list_vc','invetex_sc_audio_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_audio url="http://trex2.themerex.dnw/wp-content/uploads/2014/12/Dream-Music-Relax.mp3" image="http://trex2.themerex.dnw/wp-content/uploads/2014/10/post_audio.jpg" title="Insert Audio Title Here" author="Lily Hunter" controls="show" autoplay="off"]
*/

if (!function_exists('invetex_sc_audio')) {	
	function invetex_sc_audio($atts, $content = null) {
		if (invetex_in_shortcode_blogger()) return '';
		extract(invetex_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"author" => "",
			"image" => "",
			"mp3" => '',
			"wav" => '',
			"src" => '',
			"url" => '',
			"align" => '',
			"controls" => "",
			"autoplay" => "",
			"frame" => "on",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => '',
			"height" => '',
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		if ($src=='' && $url=='' && isset($atts[0])) {
			$src = $atts[0];
		}
		if ($src=='') {
			if ($url) $src = $url;
			else if ($mp3) $src = $mp3;
			else if ($wav) $src = $wav;
		}
		if ($image > 0) {
			$attach = wp_get_attachment_image_src( $image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$image = $attach[0];
		}
		$class .= ($class ? ' ' : '') . invetex_get_css_position_as_classes($top, $right, $bottom, $left);
		$data = ($title != ''  ? ' data-title="'.esc_attr($title).'"'   : '')
				. ($author != '' ? ' data-author="'.esc_attr($author).'"' : '')
				. ($image != ''  ? ' data-image="'.esc_url($image).'"'   : '')
				. ($align && $align!='none' ? ' data-align="'.esc_attr($align).'"' : '')
				. (!invetex_param_is_off($animation) ? ' data-animation="'.esc_attr(invetex_get_animation_classes($animation)).'"' : '');
		$audio = '<audio'
			. ($id ? ' id="'.esc_attr($id).'"' : '')
			. ' class="sc_audio' . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
			. ' src="'.esc_url($src).'"'
			. (invetex_param_is_on($controls) ? ' controls="controls"' : '')
			. (invetex_param_is_on($autoplay) && is_single() ? ' autoplay="autoplay"' : '')
			. ' width="'.esc_attr($width).'" height="'.esc_attr($height).'"'
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. ($data)
			. '></audio>';
		if ( invetex_get_custom_option('substitute_audio')=='no') {
			if (invetex_param_is_on($frame)) {
				$audio = invetex_get_audio_frame($audio, $image, $s);
			}
		} else {
			if ((isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') && (isset($_POST['action']) && $_POST['action']=='vc_load_shortcode')) {
				$audio = invetex_substitute_audio($audio, false);
			}
		}
		if (invetex_get_theme_option('use_mediaelement')=='yes')
			invetex_enqueue_script('wp-mediaelement');
		return apply_filters('invetex_shortcode_output', $audio, 'trx_audio', $atts, $content);
	}
	invetex_require_shortcode("trx_audio", "invetex_sc_audio");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'invetex_sc_audio_reg_shortcodes' ) ) {
	//add_action('invetex_action_shortcodes_list', 'invetex_sc_audio_reg_shortcodes');
	function invetex_sc_audio_reg_shortcodes() {
	
		invetex_sc_map("trx_audio", array(
			"title" => esc_html__("Audio", 'invetex'),
			"desc" => wp_kses_data( __("Insert audio player", 'invetex') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"url" => array(
					"title" => esc_html__("URL for audio file", 'invetex'),
					"desc" => wp_kses_data( __("URL for audio file", 'invetex') ),
					"readonly" => false,
					"value" => "",
					"type" => "media",
					"before" => array(
						'title' => esc_html__('Choose audio', 'invetex'),
						'action' => 'media_upload',
						'type' => 'audio',
						'multiple' => false,
						'linked_field' => '',
						'captions' => array( 	
							'choose' => esc_html__('Choose audio file', 'invetex'),
							'update' => esc_html__('Select audio file', 'invetex')
						)
					),
					"after" => array(
						'icon' => 'icon-cancel',
						'action' => 'media_reset'
					)
				),
				"image" => array(
					"title" => esc_html__("Cover image", 'invetex'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site for audio cover", 'invetex') ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"title" => array(
					"title" => esc_html__("Title", 'invetex'),
					"desc" => wp_kses_data( __("Title of the audio file", 'invetex') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"author" => array(
					"title" => esc_html__("Author", 'invetex'),
					"desc" => wp_kses_data( __("Author of the audio file", 'invetex') ),
					"value" => "",
					"type" => "text"
				),
				"controls" => array(
					"title" => esc_html__("Show controls", 'invetex'),
					"desc" => wp_kses_data( __("Show controls in audio player", 'invetex') ),
					"divider" => true,
					"size" => "medium",
					"value" => "show",
					"type" => "switch",
					"options" => invetex_get_sc_param('show_hide')
				),
				"autoplay" => array(
					"title" => esc_html__("Autoplay audio", 'invetex'),
					"desc" => wp_kses_data( __("Autoplay audio on page load", 'invetex') ),
					"value" => "off",
					"type" => "switch",
					"options" => invetex_get_sc_param('on_off')
				),
				"align" => array(
					"title" => esc_html__("Align", 'invetex'),
					"desc" => wp_kses_data( __("Select block alignment", 'invetex') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => invetex_get_sc_param('align')
				),
				"width" => invetex_shortcodes_width(),
				"height" => invetex_shortcodes_height(),
				"top" => invetex_get_sc_param('top'),
				"bottom" => invetex_get_sc_param('bottom'),
				"left" => invetex_get_sc_param('left'),
				"right" => invetex_get_sc_param('right'),
				"id" => invetex_get_sc_param('id'),
				"class" => invetex_get_sc_param('class'),
				"animation" => invetex_get_sc_param('animation'),
				"css" => invetex_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'invetex_sc_audio_reg_shortcodes_vc' ) ) {
	//add_action('invetex_action_shortcodes_list_vc', 'invetex_sc_audio_reg_shortcodes_vc');
	function invetex_sc_audio_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_audio",
			"name" => esc_html__("Audio", 'invetex'),
			"description" => wp_kses_data( __("Insert audio player", 'invetex') ),
			"category" => esc_html__('Content', 'invetex'),
			'icon' => 'icon_trx_audio',
			"class" => "trx_sc_single trx_sc_audio",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "url",
					"heading" => esc_html__("URL for audio file", 'invetex'),
					"description" => wp_kses_data( __("Put here URL for audio file", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("Cover image", 'invetex'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for audio cover", 'invetex') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'invetex'),
					"description" => wp_kses_data( __("Title of the audio file", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "author",
					"heading" => esc_html__("Author", 'invetex'),
					"description" => wp_kses_data( __("Author of the audio file", 'invetex') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "controls",
					"heading" => esc_html__("Controls", 'invetex'),
					"description" => wp_kses_data( __("Show/hide controls", 'invetex') ),
					"class" => "",
					"value" => array("Hide controls" => "hide" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "autoplay",
					"heading" => esc_html__("Autoplay", 'invetex'),
					"description" => wp_kses_data( __("Autoplay audio on page load", 'invetex') ),
					"class" => "",
					"value" => array("Autoplay" => "on" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'invetex'),
					"description" => wp_kses_data( __("Select block alignment", 'invetex') ),
					"class" => "",
					"value" => array_flip(invetex_get_sc_param('align')),
					"type" => "dropdown"
				),
				invetex_get_vc_param('id'),
				invetex_get_vc_param('class'),
				invetex_get_vc_param('animation'),
				invetex_get_vc_param('css'),
				invetex_vc_width(),
				invetex_vc_height(),
				invetex_get_vc_param('margin_top'),
				invetex_get_vc_param('margin_bottom'),
				invetex_get_vc_param('margin_left'),
				invetex_get_vc_param('margin_right')
			),
		) );
		
		class WPBakeryShortCode_Trx_Audio extends INVETEX_VC_ShortCodeSingle {}
	}
}
?>