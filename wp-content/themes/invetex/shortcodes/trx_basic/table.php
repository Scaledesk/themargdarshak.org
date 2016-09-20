<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('invetex_sc_table_theme_setup')) {
	add_action( 'invetex_action_before_init_theme', 'invetex_sc_table_theme_setup' );
	function invetex_sc_table_theme_setup() {
		add_action('invetex_action_shortcodes_list', 		'invetex_sc_table_reg_shortcodes');
		if (function_exists('invetex_exists_visual_composer') && invetex_exists_visual_composer())
			add_action('invetex_action_shortcodes_list_vc','invetex_sc_table_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_table id="unique_id" style="1"]
Table content, generated on one of many public internet resources, for example: http://www.impressivewebs.com/html-table-code-generator/
[/trx_table]
*/

if (!function_exists('invetex_sc_table')) {	
	function invetex_sc_table($atts, $content=null){	
		if (invetex_in_shortcode_blogger()) return '';
		extract(invetex_html_decode(shortcode_atts(array(
			// Individual params
			"align" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"width" => "100%"
		), $atts)));
		$class .= ($class ? ' ' : '') . invetex_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= invetex_get_css_dimensions_from_values($width);
		$content = str_replace(
					array('<p><table', 'table></p>', '><br />'),
					array('<table', 'table>', '>'),
					html_entity_decode($content, ENT_COMPAT, 'UTF-8'));
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_table' 
					. (!empty($align) && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. '"'
				. (!invetex_param_is_off($animation) ? ' data-animation="'.esc_attr(invetex_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				.'>' 
				. do_shortcode($content) 
				. '</div>';
		return apply_filters('invetex_shortcode_output', $output, 'trx_table', $atts, $content);
	}
	invetex_require_shortcode('trx_table', 'invetex_sc_table');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'invetex_sc_table_reg_shortcodes' ) ) {
	//add_action('invetex_action_shortcodes_list', 'invetex_sc_table_reg_shortcodes');
	function invetex_sc_table_reg_shortcodes() {
	
		invetex_sc_map("trx_table", array(
			"title" => esc_html__("Table", 'invetex'),
			"desc" => wp_kses_data( __("Insert a table into post (page). ", 'invetex') ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"align" => array(
					"title" => esc_html__("Content alignment", 'invetex'),
					"desc" => wp_kses_data( __("Select alignment for each table cell", 'invetex') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => invetex_get_sc_param('align')
				),
				"_content_" => array(
					"title" => esc_html__("Table content", 'invetex'),
					"desc" => wp_kses_data( __("Content, created with any table-generator", 'invetex') ),
					"divider" => true,
					"rows" => 8,
					"value" => "Paste here table content, generated on one of many public internet resources, for example: http://www.impressivewebs.com/html-table-code-generator/ or http://html-tables.com/",
					"type" => "textarea"
				),
				"width" => invetex_shortcodes_width(),
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
if ( !function_exists( 'invetex_sc_table_reg_shortcodes_vc' ) ) {
	//add_action('invetex_action_shortcodes_list_vc', 'invetex_sc_table_reg_shortcodes_vc');
	function invetex_sc_table_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_table",
			"name" => esc_html__("Table", 'invetex'),
			"description" => wp_kses_data( __("Insert a table", 'invetex') ),
			"category" => esc_html__('Content', 'invetex'),
			'icon' => 'icon_trx_table',
			"class" => "trx_sc_container trx_sc_table",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "align",
					"heading" => esc_html__("Cells content alignment", 'invetex'),
					"description" => wp_kses_data( __("Select alignment for each table cell", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(invetex_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "content",
					"heading" => esc_html__("Table content", 'invetex'),
					"description" => wp_kses_data( __("Content, created with any table-generator", 'invetex') ),
					"class" => "",
					"value" => esc_html__("Paste here table content, generated on one of many public internet resources, for example: http://www.impressivewebs.com/html-table-code-generator/ or http://html-tables.com/", 'invetex'),
					"type" => "textarea_html"
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
			'js_view' => 'VcTrxTextContainerView'
		) );
		
		class WPBakeryShortCode_Trx_Table extends INVETEX_VC_ShortCodeContainer {}
	}
}
?>