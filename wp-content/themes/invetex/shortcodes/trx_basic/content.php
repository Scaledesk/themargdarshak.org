<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('invetex_sc_content_theme_setup')) {
	add_action( 'invetex_action_before_init_theme', 'invetex_sc_content_theme_setup' );
	function invetex_sc_content_theme_setup() {
		add_action('invetex_action_shortcodes_list', 		'invetex_sc_content_reg_shortcodes');
		if (function_exists('invetex_exists_visual_composer') && invetex_exists_visual_composer())
			add_action('invetex_action_shortcodes_list_vc','invetex_sc_content_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_content id="unique_id" class="class_name" style="css-styles"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_content]
*/

if (!function_exists('invetex_sc_content')) {	
	function invetex_sc_content($atts, $content=null){	
		if (invetex_in_shortcode_blogger()) return '';
		extract(invetex_html_decode(shortcode_atts(array(
			"scheme" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"top" => "",
			"bottom" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . invetex_get_css_position_as_classes($top, '', $bottom);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ' class="sc_content content_wrap' 
				. ($scheme && !invetex_param_is_off($scheme) && !invetex_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
				. ($class ? ' '.esc_attr($class) : '') 
				. '"'
			. (!invetex_param_is_off($animation) ? ' data-animation="'.esc_attr(invetex_get_animation_classes($animation)).'"' : '')
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '').'>' 
			. do_shortcode($content) 
			. '</div>';
		return apply_filters('invetex_shortcode_output', $output, 'trx_content', $atts, $content);
	}
	invetex_require_shortcode('trx_content', 'invetex_sc_content');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'invetex_sc_content_reg_shortcodes' ) ) {
	//add_action('invetex_action_shortcodes_list', 'invetex_sc_content_reg_shortcodes');
	function invetex_sc_content_reg_shortcodes() {
	
		invetex_sc_map("trx_content", array(
			"title" => esc_html__("Content block", 'invetex'),
			"desc" => wp_kses_data( __("Container for main content block with desired class and style (use it only on fullscreen pages)", 'invetex') ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'invetex'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'invetex') ),
					"value" => "",
					"type" => "checklist",
					"options" => invetex_get_sc_param('schemes')
				),
				"_content_" => array(
					"title" => esc_html__("Container content", 'invetex'),
					"desc" => wp_kses_data( __("Content for section container", 'invetex') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"top" => invetex_get_sc_param('top'),
				"bottom" => invetex_get_sc_param('bottom'),
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
if ( !function_exists( 'invetex_sc_content_reg_shortcodes_vc' ) ) {
	//add_action('invetex_action_shortcodes_list_vc', 'invetex_sc_content_reg_shortcodes_vc');
	function invetex_sc_content_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_content",
			"name" => esc_html__("Content block", 'invetex'),
			"description" => wp_kses_data( __("Container for main content block (use it only on fullscreen pages)", 'invetex') ),
			"category" => esc_html__('Content', 'invetex'),
			'icon' => 'icon_trx_content',
			"class" => "trx_sc_collection trx_sc_content",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'invetex'),
					"description" => wp_kses_data( __("Select color scheme for this block", 'invetex') ),
					"group" => esc_html__('Colors and Images', 'invetex'),
					"class" => "",
					"value" => array_flip(invetex_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				invetex_get_vc_param('id'),
				invetex_get_vc_param('class'),
				invetex_get_vc_param('animation'),
				invetex_get_vc_param('css'),
				invetex_get_vc_param('margin_top'),
				invetex_get_vc_param('margin_bottom')
			)
		) );
		
		class WPBakeryShortCode_Trx_Content extends INVETEX_VC_ShortCodeCollection {}
	}
}
?>