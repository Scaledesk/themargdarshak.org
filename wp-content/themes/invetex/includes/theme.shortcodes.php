<?php
if (!function_exists('invetex_theme_shortcodes_setup')) {
	add_action( 'invetex_action_before_init_theme', 'invetex_theme_shortcodes_setup', 1 );
	function invetex_theme_shortcodes_setup() {
		add_filter('invetex_filter_googlemap_styles', 'invetex_theme_shortcodes_googlemap_styles');
	}
}


// Add theme-specific Google map styles
if ( !function_exists( 'invetex_theme_shortcodes_googlemap_styles' ) ) {
	function invetex_theme_shortcodes_googlemap_styles($list) {
		$list['dark']		= esc_html__('Dark', 'invetex');
		$list['simple']		= esc_html__('Simple', 'invetex');
		$list['greyscale']	= esc_html__('Greyscale', 'invetex');
		$list['inverse']	= esc_html__('Inverse', 'invetex');
		$list['apple']		= esc_html__('Apple', 'invetex');
		return $list;
	}
}
?>