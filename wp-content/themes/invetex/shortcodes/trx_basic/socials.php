<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('invetex_sc_socials_theme_setup')) {
	add_action( 'invetex_action_before_init_theme', 'invetex_sc_socials_theme_setup' );
	function invetex_sc_socials_theme_setup() {
		add_action('invetex_action_shortcodes_list', 		'invetex_sc_socials_reg_shortcodes');
		if (function_exists('invetex_exists_visual_composer') && invetex_exists_visual_composer())
			add_action('invetex_action_shortcodes_list_vc','invetex_sc_socials_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_socials id="unique_id" size="small"]
	[trx_social_item name="facebook" url="profile url" icon="path for the icon"]
	[trx_social_item name="twitter" url="profile url"]
[/trx_socials]
*/

if (!function_exists('invetex_sc_socials')) {	
	function invetex_sc_socials($atts, $content=null){	
		if (invetex_in_shortcode_blogger()) return '';
		extract(invetex_html_decode(shortcode_atts(array(
			// Individual params
			"size" => "small",		// tiny | small | medium | large
			"shape" => "square",	// round | square
			"type" => invetex_get_theme_setting('socials_type'),	// icons | images
			"socials" => "",
			"custom" => "no",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . invetex_get_css_position_as_classes($top, $right, $bottom, $left);
		invetex_storage_set('sc_social_data', array(
			'icons' => false,
            'type' => $type
            )
        );
		if (!empty($socials)) {
			$allowed = explode('|', $socials);
			$list = array();
			for ($i=0; $i<count($allowed); $i++) {
				$s = explode('=', $allowed[$i]);
				if (!empty($s[1])) {
					$list[] = array(
						'icon'	=> $type=='images' ? invetex_get_socials_url($s[0]) : 'icon-'.trim($s[0]),
						'url'	=> $s[1]
						);
				}
			}
			if (count($list) > 0) invetex_storage_set_array('sc_social_data', 'icons', $list);
		} else if (invetex_param_is_off($custom))
			$content = do_shortcode($content);
		if (invetex_storage_get_array('sc_social_data', 'icons')===false) invetex_storage_set_array('sc_social_data', 'icons', invetex_get_custom_option('social_icons'));
		$output = invetex_prepare_socials(invetex_storage_get_array('sc_social_data', 'icons'));
		$output = $output
			? '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_socials sc_socials_type_' . esc_attr($type) . ' sc_socials_shape_' . esc_attr($shape) . ' sc_socials_size_' . esc_attr($size) . (!empty($class) ? ' '.esc_attr($class) : '') . '"' 
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. (!invetex_param_is_off($animation) ? ' data-animation="'.esc_attr(invetex_get_animation_classes($animation)).'"' : '')
				. '>' 
				. ($output)
				. '</div>'
			: '';
		return apply_filters('invetex_shortcode_output', $output, 'trx_socials', $atts, $content);
	}
	invetex_require_shortcode('trx_socials', 'invetex_sc_socials');
}


if (!function_exists('invetex_sc_social_item')) {	
	function invetex_sc_social_item($atts, $content=null){	
		if (invetex_in_shortcode_blogger()) return '';
		extract(invetex_html_decode(shortcode_atts(array(
			// Individual params
			"name" => "",
			"url" => "",
			"icon" => ""
		), $atts)));
		if (!empty($name) && empty($icon)) {
			$type = invetex_storage_get_array('sc_social_data', 'type');
			if ($type=='images') {
				if (file_exists(invetex_get_socials_dir($name.'.png')))
					$icon = invetex_get_socials_url($name.'.png');
			} else
				$icon = 'icon-'.esc_attr($name);
		}
		if (!empty($icon) && !empty($url)) {
			if (invetex_storage_get_array('sc_social_data', 'icons')===false) invetex_storage_set_array('sc_social_data', 'icons', array());
			invetex_storage_set_array2('sc_social_data', 'icons', '', array(
				'icon' => $icon,
				'url' => $url
				)
			);
		}
		return '';
	}
	invetex_require_shortcode('trx_social_item', 'invetex_sc_social_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'invetex_sc_socials_reg_shortcodes' ) ) {
	//add_action('invetex_action_shortcodes_list', 'invetex_sc_socials_reg_shortcodes');
	function invetex_sc_socials_reg_shortcodes() {
	
		invetex_sc_map("trx_socials", array(
			"title" => esc_html__("Social icons", 'invetex'),
			"desc" => wp_kses_data( __("List of social icons (with hovers)", 'invetex') ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"type" => array(
					"title" => esc_html__("Icon's type", 'invetex'),
					"desc" => wp_kses_data( __("Type of the icons - images or font icons", 'invetex') ),
					"value" => invetex_get_theme_setting('socials_type'),
					"options" => array(
						'icons' => esc_html__('Icons', 'invetex'),
						'images' => esc_html__('Images', 'invetex')
					),
					"type" => "checklist"
				), 
				"size" => array(
					"title" => esc_html__("Icon's size", 'invetex'),
					"desc" => wp_kses_data( __("Size of the icons", 'invetex') ),
					"value" => "small",
					"options" => invetex_get_sc_param('sizes'),
					"type" => "checklist"
				), 
				"shape" => array(
					"title" => esc_html__("Icon's shape", 'invetex'),
					"desc" => wp_kses_data( __("Shape of the icons", 'invetex') ),
					"value" => "square",
					"options" => invetex_get_sc_param('shapes'),
					"type" => "checklist"
				), 
				"socials" => array(
					"title" => esc_html__("Manual socials list", 'invetex'),
					"desc" => wp_kses_data( __("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebook.com/my_profile. If empty - use socials from Theme options.", 'invetex') ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"custom" => array(
					"title" => esc_html__("Custom socials", 'invetex'),
					"desc" => wp_kses_data( __("Make custom icons from inner shortcodes (prepare it on tabs)", 'invetex') ),
					"divider" => true,
					"value" => "no",
					"options" => invetex_get_sc_param('yes_no'),
					"type" => "switch"
				),
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
				"name" => "trx_social_item",
				"title" => esc_html__("Custom social item", 'invetex'),
				"desc" => wp_kses_data( __("Custom social item: name, profile url and icon url", 'invetex') ),
				"decorate" => false,
				"container" => false,
				"params" => array(
					"name" => array(
						"title" => esc_html__("Social name", 'invetex'),
						"desc" => wp_kses_data( __("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", 'invetex') ),
						"value" => "",
						"type" => "text"
					),
					"url" => array(
						"title" => esc_html__("Your profile URL", 'invetex'),
						"desc" => wp_kses_data( __("URL of your profile in specified social network", 'invetex') ),
						"value" => "",
						"type" => "text"
					),
					"icon" => array(
						"title" => esc_html__("URL (source) for icon file", 'invetex'),
						"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the current social icon", 'invetex') ),
						"readonly" => false,
						"value" => "",
						"type" => "media"
					)
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'invetex_sc_socials_reg_shortcodes_vc' ) ) {
	//add_action('invetex_action_shortcodes_list_vc', 'invetex_sc_socials_reg_shortcodes_vc');
	function invetex_sc_socials_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_socials",
			"name" => esc_html__("Social icons", 'invetex'),
			"description" => wp_kses_data( __("Custom social icons", 'invetex') ),
			"category" => esc_html__('Content', 'invetex'),
			'icon' => 'icon_trx_socials',
			"class" => "trx_sc_collection trx_sc_socials",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"as_parent" => array('only' => 'trx_social_item'),
			"params" => array_merge(array(
				array(
					"param_name" => "type",
					"heading" => esc_html__("Icon's type", 'invetex'),
					"description" => wp_kses_data( __("Type of the icons - images or font icons", 'invetex') ),
					"class" => "",
					"std" => invetex_get_theme_setting('socials_type'),
					"value" => array(
						esc_html__('Icons', 'invetex') => 'icons',
						esc_html__('Images', 'invetex') => 'images'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "size",
					"heading" => esc_html__("Icon's size", 'invetex'),
					"description" => wp_kses_data( __("Size of the icons", 'invetex') ),
					"class" => "",
					"std" => "small",
					"value" => array_flip(invetex_get_sc_param('sizes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "shape",
					"heading" => esc_html__("Icon's shape", 'invetex'),
					"description" => wp_kses_data( __("Shape of the icons", 'invetex') ),
					"class" => "",
					"std" => "square",
					"value" => array_flip(invetex_get_sc_param('shapes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "socials",
					"heading" => esc_html__("Manual socials list", 'invetex'),
					"description" => wp_kses_data( __("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebook.com/my_profile. If empty - use socials from Theme options.", 'invetex') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "custom",
					"heading" => esc_html__("Custom socials", 'invetex'),
					"description" => wp_kses_data( __("Make custom icons from inner shortcodes (prepare it on tabs)", 'invetex') ),
					"class" => "",
					"value" => array(esc_html__('Custom socials', 'invetex') => 'yes'),
					"type" => "checkbox"
				),
				invetex_get_vc_param('id'),
				invetex_get_vc_param('class'),
				invetex_get_vc_param('animation'),
				invetex_get_vc_param('css'),
				invetex_get_vc_param('margin_top'),
				invetex_get_vc_param('margin_bottom'),
				invetex_get_vc_param('margin_left'),
				invetex_get_vc_param('margin_right')
			))
		) );
		
		
		vc_map( array(
			"base" => "trx_social_item",
			"name" => esc_html__("Custom social item", 'invetex'),
			"description" => wp_kses_data( __("Custom social item: name, profile url and icon url", 'invetex') ),
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => false,
			'icon' => 'icon_trx_social_item',
			"class" => "trx_sc_single trx_sc_social_item",
			"as_child" => array('only' => 'trx_socials'),
			"as_parent" => array('except' => 'trx_socials'),
			"params" => array(
				array(
					"param_name" => "name",
					"heading" => esc_html__("Social name", 'invetex'),
					"description" => wp_kses_data( __("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", 'invetex') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "url",
					"heading" => esc_html__("Your profile URL", 'invetex'),
					"description" => wp_kses_data( __("URL of your profile in specified social network", 'invetex') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("URL (source) for icon file", 'invetex'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for the current social icon", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				)
			)
		) );
		
		class WPBakeryShortCode_Trx_Socials extends INVETEX_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Social_Item extends INVETEX_VC_ShortCodeSingle {}
	}
}
?>