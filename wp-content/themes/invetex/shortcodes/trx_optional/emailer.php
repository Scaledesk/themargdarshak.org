<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('invetex_sc_emailer_theme_setup')) {
	add_action( 'invetex_action_before_init_theme', 'invetex_sc_emailer_theme_setup' );
	function invetex_sc_emailer_theme_setup() {
		add_action('invetex_action_shortcodes_list', 		'invetex_sc_emailer_reg_shortcodes');
		if (function_exists('invetex_exists_visual_composer') && invetex_exists_visual_composer())
			add_action('invetex_action_shortcodes_list_vc','invetex_sc_emailer_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_emailer group=""]

if (!function_exists('invetex_sc_emailer')) {	
	function invetex_sc_emailer($atts, $content = null) {
		if (invetex_in_shortcode_blogger()) return '';
		extract(invetex_html_decode(shortcode_atts(array(
			// Individual params
			"group" => "",
			"open" => "no",
			"align" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"width" => "",
			"height" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . invetex_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= invetex_get_css_dimensions_from_values($width, $height);
		// Load core messages
		invetex_enqueue_messages();
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
					. ' class="sc_emailer' . ($align && $align!='none' ? ' align' . esc_attr($align) : '') . (invetex_param_is_on($open) ? ' sc_emailer_opened style_line' : '') . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
					. ($css ? ' style="'.esc_attr($css).'"' : '') 
					. (!invetex_param_is_off($animation) ? ' data-animation="'.esc_attr(invetex_get_animation_classes($animation)).'"' : '')
					. '>'
					. (invetex_param_is_on($open) ? '<div class="lable">'.esc_html__('Newsletter', 'invetex').'</div>' : '')
				. '<form class="sc_emailer_form">'
				. '<input type="text" class="sc_emailer_input" name="email" value="" placeholder="'.esc_attr__('Enter Your Email', 'invetex').'">'

			. (invetex_param_is_off($open) ? '<a href="#" class="sc_emailer_button icon-mail" title="'.esc_attr__('Submit', 'invetex').'" data-group="'.esc_attr($group ? $group : esc_html__('E-mailer subscription', 'invetex')).'"></a>' : '')

			. (invetex_param_is_on($open) ? '<a href="#" class="sc_emailer_button" title="'.esc_attr__('Submit', 'invetex').'" data-group="'.esc_attr($group ? $group : esc_html__('E-mailer subscription', 'invetex')).'">'.esc_html__('Subscribe', 'invetex').'</a>' : '')

					. '</form>'
			. '</div>';
		return apply_filters('invetex_shortcode_output', $output, 'trx_emailer', $atts, $content);
	}
	invetex_require_shortcode("trx_emailer", "invetex_sc_emailer");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'invetex_sc_emailer_reg_shortcodes' ) ) {
	//add_action('invetex_action_shortcodes_list', 'invetex_sc_emailer_reg_shortcodes');
	function invetex_sc_emailer_reg_shortcodes() {
	
		invetex_sc_map("trx_emailer", array(
			"title" => esc_html__("E-mail collector", 'invetex'),
			"desc" => wp_kses_data( __("Collect the e-mail address into specified group", 'invetex') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"group" => array(
					"title" => esc_html__("Group", 'invetex'),
					"desc" => wp_kses_data( __("The name of group to collect e-mail address", 'invetex') ),
					"value" => "",
					"type" => "text"
				),
				"open" => array(
					"title" => esc_html__("Open", 'invetex'),
					"desc" => wp_kses_data( __("Initially open the input field on show object", 'invetex') ),
					"divider" => true,
					"value" => "yes",
					"type" => "switch",
					"options" => invetex_get_sc_param('yes_no')
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'invetex'),
					"desc" => wp_kses_data( __("Align object to left, center or right", 'invetex') ),
					"divider" => true,
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
if ( !function_exists( 'invetex_sc_emailer_reg_shortcodes_vc' ) ) {
	//add_action('invetex_action_shortcodes_list_vc', 'invetex_sc_emailer_reg_shortcodes_vc');
	function invetex_sc_emailer_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_emailer",
			"name" => esc_html__("E-mail collector", 'invetex'),
			"description" => wp_kses_data( __("Collect e-mails into specified group", 'invetex') ),
			"category" => esc_html__('Content', 'invetex'),
			'icon' => 'icon_trx_emailer',
			"class" => "trx_sc_single trx_sc_emailer",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "group",
					"heading" => esc_html__("Group", 'invetex'),
					"description" => wp_kses_data( __("The name of group to collect e-mail address", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "open",
					"heading" => esc_html__("Opened", 'invetex'),
					"description" => wp_kses_data( __("Initially open the input field on show object", 'invetex') ),
					"class" => "",
					"value" => array(esc_html__('Initially opened', 'invetex') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'invetex'),
					"description" => wp_kses_data( __("Align field to left, center or right", 'invetex') ),
					"admin_label" => true,
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
			)
		) );
		
		class WPBakeryShortCode_Trx_Emailer extends INVETEX_VC_ShortCodeSingle {}
	}
}
?>