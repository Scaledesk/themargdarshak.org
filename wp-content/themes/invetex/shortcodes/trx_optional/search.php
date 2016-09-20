<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('invetex_sc_search_theme_setup')) {
	add_action( 'invetex_action_before_init_theme', 'invetex_sc_search_theme_setup' );
	function invetex_sc_search_theme_setup() {
		add_action('invetex_action_shortcodes_list', 		'invetex_sc_search_reg_shortcodes');
		if (function_exists('invetex_exists_visual_composer') && invetex_exists_visual_composer())
			add_action('invetex_action_shortcodes_list_vc','invetex_sc_search_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_search id="unique_id" open="yes|no"]
*/

if (!function_exists('invetex_sc_search')) {	
	function invetex_sc_search($atts, $content=null){	
		if (invetex_in_shortcode_blogger()) return '';
		extract(invetex_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "",
			"state" => "",
			"ajax" => "",
			"title" => esc_html__('Search', 'invetex'),
			"scheme" => "original",
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
		//if (empty($style)) $style = invetex_get_theme_option('search_style');
		if ($style == 'fullscreen') {
			if (empty($ajax)) $ajax = "no";
			if (empty($state)) $state = "closed";
		} else if ($style == 'expand') {
			if (empty($ajax)) $ajax = invetex_get_theme_option('use_ajax_search');
			if (empty($state)) $state = "closed";
		} else if ($style == 'slide') {
			if (empty($ajax)) $ajax = invetex_get_theme_option('use_ajax_search');
			if (empty($state)) $state = "closed";
		} else {
			if (empty($ajax)) $ajax = invetex_get_theme_option('use_ajax_search');
			if (empty($state)) $state = "fixed";
		}
		// Load core messages
		invetex_enqueue_messages();
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') . ' class="search_wrap search_style_'.esc_attr($style).' search_state_'.esc_attr($state)
						. (invetex_param_is_on($ajax) ? ' search_ajax' : '')
						. ($class ? ' '.esc_attr($class) : '')
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!invetex_param_is_off($animation) ? ' data-animation="'.esc_attr(invetex_get_animation_classes($animation)).'"' : '')
					. '>
						<div class="search_form_wrap">
							<form role="search" method="get" class="search_form" action="' . esc_url(home_url('/')) . '">
								<button type="submit" class="search_submit icon-search-light" title="' . ($state=='closed' ? esc_attr__('Open search', 'invetex') : esc_attr__('Start search', 'invetex')) . '"></button>
								<input type="text" class="search_field" placeholder="' . esc_attr($title) . '" value="' . esc_attr(get_search_query()) . '" name="s" />'
								. ($style == 'fullscreen' ? '<a class="search_close icon-1460034721_close"></a>' : '')
							. '</form>
						</div>'
						. (invetex_param_is_on($ajax) ? '<div class="search_results widget_area' . ($scheme && !invetex_param_is_off($scheme) && !invetex_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') . '"><a class="search_results_close icon-cancel"></a><div class="search_results_content"></div></div>' : '')
					. '</div>';
		return apply_filters('invetex_shortcode_output', $output, 'trx_search', $atts, $content);
	}
	invetex_require_shortcode('trx_search', 'invetex_sc_search');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'invetex_sc_search_reg_shortcodes' ) ) {
	//add_action('invetex_action_shortcodes_list', 'invetex_sc_search_reg_shortcodes');
	function invetex_sc_search_reg_shortcodes() {
	
		invetex_sc_map("trx_search", array(
			"title" => esc_html__("Search", 'invetex'),
			"desc" => wp_kses_data( __("Show search form", 'invetex') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", 'invetex'),
					"desc" => wp_kses_data( __("Select style to display search field", 'invetex') ),
					"value" => "regular",
					"options" => invetex_get_list_search_styles(),
					"type" => "checklist"
				),
				"state" => array(
					"title" => esc_html__("State", 'invetex'),
					"desc" => wp_kses_data( __("Select search field initial state", 'invetex') ),
					"value" => "fixed",
					"options" => array(
						"fixed"  => esc_html__('Fixed',  'invetex'),
						"opened" => esc_html__('Opened', 'invetex'),
						"closed" => esc_html__('Closed', 'invetex')
					),
					"type" => "checklist"
				),
				"title" => array(
					"title" => esc_html__("Title", 'invetex'),
					"desc" => wp_kses_data( __("Title (placeholder) for the search field", 'invetex') ),
					"value" => esc_html__("Search &hellip;", 'invetex'),
					"type" => "text"
				),
				"ajax" => array(
					"title" => esc_html__("AJAX", 'invetex'),
					"desc" => wp_kses_data( __("Search via AJAX or reload page", 'invetex') ),
					"value" => "yes",
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
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'invetex_sc_search_reg_shortcodes_vc' ) ) {
	//add_action('invetex_action_shortcodes_list_vc', 'invetex_sc_search_reg_shortcodes_vc');
	function invetex_sc_search_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_search",
			"name" => esc_html__("Search form", 'invetex'),
			"description" => wp_kses_data( __("Insert search form", 'invetex') ),
			"category" => esc_html__('Content', 'invetex'),
			'icon' => 'icon_trx_search',
			"class" => "trx_sc_single trx_sc_search",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", 'invetex'),
					"description" => wp_kses_data( __("Select style to display search field", 'invetex') ),
					"class" => "",
					"value" => invetex_get_list_search_styles(),
					"type" => "dropdown"
				),
				array(
					"param_name" => "state",
					"heading" => esc_html__("State", 'invetex'),
					"description" => wp_kses_data( __("Select search field initial state", 'invetex') ),
					"class" => "",
					"value" => array(
						esc_html__('Fixed', 'invetex')  => "fixed",
						esc_html__('Opened', 'invetex') => "opened",
						esc_html__('Closed', 'invetex') => "closed"
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'invetex'),
					"description" => wp_kses_data( __("Title (placeholder) for the search field", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"value" => esc_html__("Search &hellip;", 'invetex'),
					"type" => "textfield"
				),
				array(
					"param_name" => "ajax",
					"heading" => esc_html__("AJAX", 'invetex'),
					"description" => wp_kses_data( __("Search via AJAX or reload page", 'invetex') ),
					"class" => "",
					"value" => array(esc_html__('Use AJAX search', 'invetex') => 'yes'),
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
			)
		) );
		
		class WPBakeryShortCode_Trx_Search extends INVETEX_VC_ShortCodeSingle {}
	}
}
?>