<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('invetex_sc_anchor_theme_setup')) {
	add_action( 'invetex_action_before_init_theme', 'invetex_sc_anchor_theme_setup' );
	function invetex_sc_anchor_theme_setup() {
		add_action('invetex_action_shortcodes_list', 		'invetex_sc_anchor_reg_shortcodes');
		if (function_exists('invetex_exists_visual_composer') && invetex_exists_visual_composer())
			add_action('invetex_action_shortcodes_list_vc','invetex_sc_anchor_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_anchor id="unique_id" description="Anchor description" title="Short Caption" icon="icon-class"]
*/

if (!function_exists('invetex_sc_anchor')) {	
	function invetex_sc_anchor($atts, $content = null) {
		if (invetex_in_shortcode_blogger()) return '';
		extract(invetex_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"description" => '',
			"icon" => '',
			"url" => "",
			"separator" => "no",
			// Common params
			"id" => ""
		), $atts)));
		$output = $id 
			? '<a id="'.esc_attr($id).'"'
				. ' class="sc_anchor"' 
				. ' title="' . ($title ? esc_attr($title) : '') . '"'
				. ' data-description="' . ($description ? esc_attr(invetex_strmacros($description)) : ''). '"'
				. ' data-icon="' . ($icon ? $icon : '') . '"' 
				. ' data-url="' . ($url ? esc_attr($url) : '') . '"' 
				. ' data-separator="' . (invetex_param_is_on($separator) ? 'yes' : 'no') . '"'
				. '></a>'
			: '';
		return apply_filters('invetex_shortcode_output', $output, 'trx_anchor', $atts, $content);
	}
	invetex_require_shortcode("trx_anchor", "invetex_sc_anchor");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'invetex_sc_anchor_reg_shortcodes' ) ) {
	//add_action('invetex_action_shortcodes_list', 'invetex_sc_anchor_reg_shortcodes');
	function invetex_sc_anchor_reg_shortcodes() {
	
		invetex_sc_map("trx_anchor", array(
			"title" => esc_html__("Anchor", 'invetex'),
			"desc" => wp_kses_data( __("Insert anchor for the TOC (table of content)", 'invetex') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"icon" => array(
					"title" => esc_html__("Anchor's icon",  'invetex'),
					"desc" => wp_kses_data( __('Select icon for the anchor from Fontello icons set',  'invetex') ),
					"value" => "",
					"type" => "icons",
					"options" => invetex_get_sc_param('icons')
				),
				"title" => array(
					"title" => esc_html__("Short title", 'invetex'),
					"desc" => wp_kses_data( __("Short title of the anchor (for the table of content)", 'invetex') ),
					"value" => "",
					"type" => "text"
				),
				"description" => array(
					"title" => esc_html__("Long description", 'invetex'),
					"desc" => wp_kses_data( __("Description for the popup (then hover on the icon). You can use:<br>'{{' and '}}' - to make the text italic,<br>'((' and '))' - to make the text bold,<br>'||' - to insert line break", 'invetex') ),
					"value" => "",
					"type" => "text"
				),
				"url" => array(
					"title" => esc_html__("External URL", 'invetex'),
					"desc" => wp_kses_data( __("External URL for this TOC item", 'invetex') ),
					"value" => "",
					"type" => "text"
				),
				"separator" => array(
					"title" => esc_html__("Add separator", 'invetex'),
					"desc" => wp_kses_data( __("Add separator under item in the TOC", 'invetex') ),
					"value" => "no",
					"type" => "switch",
					"options" => invetex_get_sc_param('yes_no')
				),
				"id" => invetex_get_sc_param('id')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'invetex_sc_anchor_reg_shortcodes_vc' ) ) {
	//add_action('invetex_action_shortcodes_list_vc', 'invetex_sc_anchor_reg_shortcodes_vc');
	function invetex_sc_anchor_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_anchor",
			"name" => esc_html__("Anchor", 'invetex'),
			"description" => wp_kses_data( __("Insert anchor for the TOC (table of content)", 'invetex') ),
			"category" => esc_html__('Content', 'invetex'),
			'icon' => 'icon_trx_anchor',
			"class" => "trx_sc_single trx_sc_anchor",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Anchor's icon", 'invetex'),
					"description" => wp_kses_data( __("Select icon for the anchor from Fontello icons set", 'invetex') ),
					"class" => "",
					"value" => invetex_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Short title", 'invetex'),
					"description" => wp_kses_data( __("Short title of the anchor (for the table of content)", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "description",
					"heading" => esc_html__("Long description", 'invetex'),
					"description" => wp_kses_data( __("Description for the popup (then hover on the icon). You can use:<br>'{{' and '}}' - to make the text italic,<br>'((' and '))' - to make the text bold,<br>'||' - to insert line break", 'invetex') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "url",
					"heading" => esc_html__("External URL", 'invetex'),
					"description" => wp_kses_data( __("External URL for this TOC item", 'invetex') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "separator",
					"heading" => esc_html__("Add separator", 'invetex'),
					"description" => wp_kses_data( __("Add separator under item in the TOC", 'invetex') ),
					"class" => "",
					"value" => array("Add separator" => "yes" ),
					"type" => "checkbox"
				),
				invetex_get_vc_param('id')
			),
		) );
		
		class WPBakeryShortCode_Trx_Anchor extends INVETEX_VC_ShortCodeSingle {}
	}
}
?>