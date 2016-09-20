<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('invetex_sc_googlemap_theme_setup')) {
	add_action( 'invetex_action_before_init_theme', 'invetex_sc_googlemap_theme_setup' );
	function invetex_sc_googlemap_theme_setup() {
		add_action('invetex_action_shortcodes_list', 		'invetex_sc_googlemap_reg_shortcodes');
		if (function_exists('invetex_exists_visual_composer') && invetex_exists_visual_composer())
			add_action('invetex_action_shortcodes_list_vc','invetex_sc_googlemap_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_googlemap id="unique_id" width="width_in_pixels_or_percent" height="height_in_pixels"]
//	[trx_googlemap_marker address="your_address"]
//[/trx_googlemap]

if (!function_exists('invetex_sc_googlemap')) {	
	function invetex_sc_googlemap($atts, $content = null) {
		if (invetex_in_shortcode_blogger()) return '';
		extract(invetex_html_decode(shortcode_atts(array(
			// Individual params
			"zoom" => 16,
			"style" => 'default',
			"scheme" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "100%",
			"height" => "400",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . invetex_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= invetex_get_css_dimensions_from_values($width, $height);
		if (empty($id)) $id = 'sc_googlemap_'.str_replace('.', '', mt_rand());
		if (empty($style)) $style = invetex_get_custom_option('googlemap_style');
		$api_key = invetex_get_theme_option('api_google');
		invetex_enqueue_script( 'googlemap', invetex_get_protocol().'://maps.google.com/maps/api/js'.($api_key ? '?key='.$api_key : ''), array(), null, true );
		invetex_enqueue_script( 'invetex-googlemap-script', invetex_get_file_url('js/core.googlemap.js'), array(), null, true );
		invetex_storage_set('sc_googlemap_markers', array());
		$content = do_shortcode($content);
		$output = '';
		$markers = invetex_storage_get('sc_googlemap_markers');
		if (count($markers) == 0) {
			$markers[] = array(
				'title' => invetex_get_custom_option('googlemap_title'),
				'description' => invetex_strmacros(invetex_get_custom_option('googlemap_description')),
				'latlng' => invetex_get_custom_option('googlemap_latlng'),
				'address' => invetex_get_custom_option('googlemap_address'),
				'point' => invetex_get_custom_option('googlemap_marker')
			);
		}
		$output .= 
			($content ? '<div id="'.esc_attr($id).'_wrap" class="sc_googlemap_wrap'
					. ($scheme && !invetex_param_is_off($scheme) && !invetex_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
					. '">' : '')
			. '<div id="'.esc_attr($id).'"'
				. ' class="sc_googlemap'. (!empty($class) ? ' '.esc_attr($class) : '').'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. (!invetex_param_is_off($animation) ? ' data-animation="'.esc_attr(invetex_get_animation_classes($animation)).'"' : '')
				. ' data-zoom="'.esc_attr($zoom).'"'
				. ' data-style="'.esc_attr($style).'"'
				. '>';
		$cnt = 0;
		foreach ($markers as $marker) {
			$cnt++;
			if (empty($marker['id'])) $marker['id'] = $id.'_'.intval($cnt);
			$output .= '<div id="'.esc_attr($marker['id']).'" class="sc_googlemap_marker"'
				. ' data-title="'.esc_attr($marker['title']).'"'
				. ' data-description="'.esc_attr(invetex_strmacros($marker['description'])).'"'
				. ' data-address="'.esc_attr($marker['address']).'"'
				. ' data-latlng="'.esc_attr($marker['latlng']).'"'
				. ' data-point="'.esc_attr($marker['point']).'"'
				. '></div>';
		}
		$output .= '</div>'
			. ($content ? '<div class="sc_googlemap_content">' . trim($content) . '</div></div>' : '');
			
		return apply_filters('invetex_shortcode_output', $output, 'trx_googlemap', $atts, $content);
	}
	invetex_require_shortcode("trx_googlemap", "invetex_sc_googlemap");
}


if (!function_exists('invetex_sc_googlemap_marker')) {	
	function invetex_sc_googlemap_marker($atts, $content = null) {
		if (invetex_in_shortcode_blogger()) return '';
		extract(invetex_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"address" => "",
			"latlng" => "",
			"point" => "",
			// Common params
			"id" => ""
		), $atts)));
		if (!empty($point)) {
			if ($point > 0) {
				$attach = wp_get_attachment_image_src( $point, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$point = $attach[0];
			}
		}
		$content = do_shortcode($content);
		invetex_storage_set_array('sc_googlemap_markers', '', array(
			'id' => $id,
			'title' => $title,
			'description' => !empty($content) ? $content : $address,
			'latlng' => $latlng,
			'address' => $address,
			'point' => $point ? $point : invetex_get_custom_option('googlemap_marker')
			)
		);
		return '';
	}
	invetex_require_shortcode("trx_googlemap_marker", "invetex_sc_googlemap_marker");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'invetex_sc_googlemap_reg_shortcodes' ) ) {
	//add_action('invetex_action_shortcodes_list', 'invetex_sc_googlemap_reg_shortcodes');
	function invetex_sc_googlemap_reg_shortcodes() {
	
		invetex_sc_map("trx_googlemap", array(
			"title" => esc_html__("Google map", 'invetex'),
			"desc" => wp_kses_data( __("Insert Google map with specified markers", 'invetex') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"zoom" => array(
					"title" => esc_html__("Zoom", 'invetex'),
					"desc" => wp_kses_data( __("Map zoom factor", 'invetex') ),
					"divider" => true,
					"value" => 16,
					"min" => 1,
					"max" => 20,
					"type" => "spinner"
				),
				"style" => array(
					"title" => esc_html__("Map style", 'invetex'),
					"desc" => wp_kses_data( __("Select map style", 'invetex') ),
					"value" => "default",
					"type" => "checklist",
					"options" => invetex_get_sc_param('googlemap_styles')
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'invetex'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'invetex') ),
					"value" => "",
					"type" => "checklist",
					"options" => invetex_get_sc_param('schemes')
				),
				"width" => invetex_shortcodes_width('100%'),
				"height" => invetex_shortcodes_height(240),
				"top" => invetex_get_sc_param('top'),
				"bottom" => invetex_get_sc_param('bottom'),
				"left" => invetex_get_sc_param('left'),
				"right" => invetex_get_sc_param('right'),
				"id" => invetex_get_sc_param('id'),
				"class" => invetex_get_sc_param('class'),
				"animation" => invetex_get_sc_param('animation'),
				"css" => invetex_get_sc_param('css')
			),
			"children" => array(
				"name" => "trx_googlemap_marker",
				"title" => esc_html__("Google map marker", 'invetex'),
				"desc" => wp_kses_data( __("Google map marker", 'invetex') ),
				"decorate" => false,
				"container" => true,
				"params" => array(
					"address" => array(
						"title" => esc_html__("Address", 'invetex'),
						"desc" => wp_kses_data( __("Address of this marker", 'invetex') ),
						"value" => "",
						"type" => "text"
					),
					"latlng" => array(
						"title" => esc_html__("Latitude and Longitude", 'invetex'),
						"desc" => wp_kses_data( __("Comma separated marker's coorditanes (instead Address)", 'invetex') ),
						"value" => "",
						"type" => "text"
					),
					"point" => array(
						"title" => esc_html__("URL for marker image file", 'invetex'),
						"desc" => wp_kses_data( __("Select or upload image or write URL from other site for this marker. If empty - use default marker", 'invetex') ),
						"readonly" => false,
						"value" => "",
						"type" => "media"
					),
					"title" => array(
						"title" => esc_html__("Title", 'invetex'),
						"desc" => wp_kses_data( __("Title for this marker", 'invetex') ),
						"value" => "",
						"type" => "text"
					),
					"_content_" => array(
						"title" => esc_html__("Description", 'invetex'),
						"desc" => wp_kses_data( __("Description for this marker", 'invetex') ),
						"rows" => 4,
						"value" => "",
						"type" => "textarea"
					),
					"id" => invetex_get_sc_param('id')
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'invetex_sc_googlemap_reg_shortcodes_vc' ) ) {
	//add_action('invetex_action_shortcodes_list_vc', 'invetex_sc_googlemap_reg_shortcodes_vc');
	function invetex_sc_googlemap_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_googlemap",
			"name" => esc_html__("Google map", 'invetex'),
			"description" => wp_kses_data( __("Insert Google map with desired address or coordinates", 'invetex') ),
			"category" => esc_html__('Content', 'invetex'),
			'icon' => 'icon_trx_googlemap',
			"class" => "trx_sc_collection trx_sc_googlemap",
			"content_element" => true,
			"is_container" => true,
			"as_parent" => array('only' => 'trx_googlemap_marker,trx_form,trx_section,trx_block,trx_promo'),
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "zoom",
					"heading" => esc_html__("Zoom", 'invetex'),
					"description" => wp_kses_data( __("Map zoom factor", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"value" => "16",
					"type" => "textfield"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'invetex'),
					"description" => wp_kses_data( __("Map custom style", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(invetex_get_sc_param('googlemap_styles')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'invetex'),
					"description" => wp_kses_data( __("Select color scheme for this block", 'invetex') ),
					"class" => "",
					"value" => array_flip(invetex_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				invetex_get_vc_param('id'),
				invetex_get_vc_param('class'),
				invetex_get_vc_param('animation'),
				invetex_get_vc_param('css'),
				invetex_vc_width('100%'),
				invetex_vc_height(240),
				invetex_get_vc_param('margin_top'),
				invetex_get_vc_param('margin_bottom'),
				invetex_get_vc_param('margin_left'),
				invetex_get_vc_param('margin_right')
			)
		) );
		
		vc_map( array(
			"base" => "trx_googlemap_marker",
			"name" => esc_html__("Googlemap marker", 'invetex'),
			"description" => wp_kses_data( __("Insert new marker into Google map", 'invetex') ),
			"class" => "trx_sc_collection trx_sc_googlemap_marker",
			'icon' => 'icon_trx_googlemap_marker',
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => true,
			"as_child" => array('only' => 'trx_googlemap'), // Use only|except attributes to limit parent (separate multiple values with comma)
			"params" => array(
				array(
					"param_name" => "address",
					"heading" => esc_html__("Address", 'invetex'),
					"description" => wp_kses_data( __("Address of this marker", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "latlng",
					"heading" => esc_html__("Latitude and Longitude", 'invetex'),
					"description" => wp_kses_data( __("Comma separated marker's coorditanes (instead Address)", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'invetex'),
					"description" => wp_kses_data( __("Title for this marker", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "point",
					"heading" => esc_html__("URL for marker image file", 'invetex'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for this marker. If empty - use default marker", 'invetex') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				invetex_get_vc_param('id')
			)
		) );
		
		class WPBakeryShortCode_Trx_Googlemap extends INVETEX_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Googlemap_Marker extends INVETEX_VC_ShortCodeCollection {}
	}
}
?>