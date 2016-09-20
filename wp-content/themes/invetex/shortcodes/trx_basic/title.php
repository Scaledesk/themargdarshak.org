<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('invetex_sc_title_theme_setup')) {
	add_action( 'invetex_action_before_init_theme', 'invetex_sc_title_theme_setup' );
	function invetex_sc_title_theme_setup() {
		add_action('invetex_action_shortcodes_list', 		'invetex_sc_title_reg_shortcodes');
		if (function_exists('invetex_exists_visual_composer') && invetex_exists_visual_composer())
			add_action('invetex_action_shortcodes_list_vc','invetex_sc_title_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_title id="unique_id" style='regular|iconed' icon='' image='' background="on|off" type="1-6"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_title]
*/

if (!function_exists('invetex_sc_title')) {	
	function invetex_sc_title($atts, $content=null){	
		if (invetex_in_shortcode_blogger()) return '';
		extract(invetex_html_decode(shortcode_atts(array(
			// Individual params
			"type" => "1",
			"style" => "regular",
			"align" => "",
			"font_weight" => "",
			"font_size" => "",
			"color" => "",
			"icon" => "",
			"image" => "",
			"picture" => "",
			"image_size" => "small",
			"position" => "left",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . invetex_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= invetex_get_css_dimensions_from_values($width)
			.($align && $align!='none' && !invetex_param_is_inherit($align) ? 'text-align:' . esc_attr($align) .';' : '')
			.($color ? 'color:' . esc_attr($color) .';' : '')
			.($font_weight && !invetex_param_is_inherit($font_weight) ? 'font-weight:' . esc_attr($font_weight) .';' : '')
			.($font_size   ? 'font-size:' . esc_attr($font_size) .';' : '')
			;
		$type = min(6, max(1, $type));
		if ($picture > 0) {
			$attach = wp_get_attachment_image_src( $picture, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$picture = $attach[0];
		}
		$pic = $style!='iconed' 
			? '' 
			: '<span class="sc_title_icon sc_title_icon_'.esc_attr($position).'  sc_title_icon_'.esc_attr($image_size).($icon!='' && $icon!='none' ? ' '.esc_attr($icon) : '').'"'.'>'
				.($picture ? '<img src="'.esc_url($picture).'" alt="" />' : '')
				.(empty($picture) && $image && $image!='none' ? '<img src="'.esc_url(invetex_strpos($image, 'http')===0 ? $image : invetex_get_file_url('images/icons/'.($image).'.png')).'" alt="" />' : '')
				.'</span>';
		$output = '<h' . esc_attr($type) . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_title sc_title_'.esc_attr($style)
					.($align && $align!='none' && !invetex_param_is_inherit($align) ? ' sc_align_' . esc_attr($align) : '')
					.(!empty($class) ? ' '.esc_attr($class) : '')
					.'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!invetex_param_is_off($animation) ? ' data-animation="'.esc_attr(invetex_get_animation_classes($animation)).'"' : '')
				. '>'
					. ($pic)
					. ($style=='divider' ? '<span class="sc_title_divider_before"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></span>' : '')
					. do_shortcode($content) 
					. ($style=='divider' ? '<span class="sc_title_divider_after"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></span>' : '')
				. '</h' . esc_attr($type) . '>';
		return apply_filters('invetex_shortcode_output', $output, 'trx_title', $atts, $content);
	}
	invetex_require_shortcode('trx_title', 'invetex_sc_title');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'invetex_sc_title_reg_shortcodes' ) ) {
	//add_action('invetex_action_shortcodes_list', 'invetex_sc_title_reg_shortcodes');
	function invetex_sc_title_reg_shortcodes() {
	
		invetex_sc_map("trx_title", array(
			"title" => esc_html__("Title", 'invetex'),
			"desc" => wp_kses_data( __("Create header tag (1-6 level) with many styles", 'invetex') ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Title content", 'invetex'),
					"desc" => wp_kses_data( __("Title content", 'invetex') ),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"type" => array(
					"title" => esc_html__("Title type", 'invetex'),
					"desc" => wp_kses_data( __("Title type (header level)", 'invetex') ),
					"divider" => true,
					"value" => "1",
					"type" => "select",
					"options" => array(
						'1' => esc_html__('Header 1', 'invetex'),
						'2' => esc_html__('Header 2', 'invetex'),
						'3' => esc_html__('Header 3', 'invetex'),
						'4' => esc_html__('Header 4', 'invetex'),
						'5' => esc_html__('Header 5', 'invetex'),
						'6' => esc_html__('Header 6', 'invetex'),
					)
				),
				"style" => array(
					"title" => esc_html__("Title style", 'invetex'),
					"desc" => wp_kses_data( __("Title style", 'invetex') ),
					"value" => "regular",
					"type" => "select",
					"options" => array(
						'regular' => esc_html__('Regular', 'invetex'),
						'underline' => esc_html__('Underline', 'invetex'),
						'divider' => esc_html__('Divider', 'invetex'),
						'iconed' => esc_html__('With icon (image)', 'invetex')
					)
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'invetex'),
					"desc" => wp_kses_data( __("Title text alignment", 'invetex') ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => invetex_get_sc_param('align')
				), 
				"font_size" => array(
					"title" => esc_html__("Font_size", 'invetex'),
					"desc" => wp_kses_data( __("Custom font size. If empty - use theme default", 'invetex') ),
					"value" => "",
					"type" => "text"
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", 'invetex'),
					"desc" => wp_kses_data( __("Custom font weight. If empty or inherit - use theme default", 'invetex') ),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'inherit' => esc_html__('Default', 'invetex'),
						'100' => esc_html__('Thin (100)', 'invetex'),
						'300' => esc_html__('Light (300)', 'invetex'),
						'400' => esc_html__('Normal (400)', 'invetex'),
						'600' => esc_html__('Semibold (600)', 'invetex'),
						'700' => esc_html__('Bold (700)', 'invetex'),
						'900' => esc_html__('Black (900)', 'invetex')
					)
				),
				"color" => array(
					"title" => esc_html__("Title color", 'invetex'),
					"desc" => wp_kses_data( __("Select color for the title", 'invetex') ),
					"value" => "",
					"type" => "color"
				),
				"icon" => array(
					"title" => esc_html__('Title font icon',  'invetex'),
					"desc" => wp_kses_data( __("Select font icon for the title from Fontello icons set (if style=iconed)",  'invetex') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "icons",
					"options" => invetex_get_sc_param('icons')
				),
				"image" => array(
					"title" => esc_html__('or image icon',  'invetex'),
					"desc" => wp_kses_data( __("Select image icon for the title instead icon above (if style=iconed)",  'invetex') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "images",
					"size" => "small",
					"options" => invetex_get_sc_param('images')
				),
				"picture" => array(
					"title" => esc_html__('or URL for image file', 'invetex'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site (if style=iconed)", 'invetex') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"image_size" => array(
					"title" => esc_html__('Image (picture) size', 'invetex'),
					"desc" => wp_kses_data( __("Select image (picture) size (if style='iconed')", 'invetex') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "small",
					"type" => "checklist",
					"options" => array(
						'small' => esc_html__('Small', 'invetex'),
						'medium' => esc_html__('Medium', 'invetex'),
						'large' => esc_html__('Large', 'invetex')
					)
				),
				"position" => array(
					"title" => esc_html__('Icon (image) position', 'invetex'),
					"desc" => wp_kses_data( __("Select icon (image) position (if style=iconed)", 'invetex') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "left",
					"type" => "checklist",
					"options" => array(
						'top' => esc_html__('Top', 'invetex'),
						'left' => esc_html__('Left', 'invetex')
					)
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
if ( !function_exists( 'invetex_sc_title_reg_shortcodes_vc' ) ) {
	//add_action('invetex_action_shortcodes_list_vc', 'invetex_sc_title_reg_shortcodes_vc');
	function invetex_sc_title_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_title",
			"name" => esc_html__("Title", 'invetex'),
			"description" => wp_kses_data( __("Create header tag (1-6 level) with many styles", 'invetex') ),
			"category" => esc_html__('Content', 'invetex'),
			'icon' => 'icon_trx_title',
			"class" => "trx_sc_single trx_sc_title",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "content",
					"heading" => esc_html__("Title content", 'invetex'),
					"description" => wp_kses_data( __("Title content", 'invetex') ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				array(
					"param_name" => "type",
					"heading" => esc_html__("Title type", 'invetex'),
					"description" => wp_kses_data( __("Title type (header level)", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Header 1', 'invetex') => '1',
						esc_html__('Header 2', 'invetex') => '2',
						esc_html__('Header 3', 'invetex') => '3',
						esc_html__('Header 4', 'invetex') => '4',
						esc_html__('Header 5', 'invetex') => '5',
						esc_html__('Header 6', 'invetex') => '6'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Title style", 'invetex'),
					"description" => wp_kses_data( __("Title style: only text (regular) or with icon/image (iconed)", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Regular', 'invetex') => 'regular',
						esc_html__('Underline', 'invetex') => 'underline',
						esc_html__('Divider', 'invetex') => 'divider',
						esc_html__('With icon (image)', 'invetex') => 'iconed'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'invetex'),
					"description" => wp_kses_data( __("Title text alignment", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(invetex_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'invetex'),
					"description" => wp_kses_data( __("Custom font size. If empty - use theme default", 'invetex') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", 'invetex'),
					"description" => wp_kses_data( __("Custom font weight. If empty or inherit - use theme default", 'invetex') ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'invetex') => 'inherit',
						esc_html__('Thin (100)', 'invetex') => '100',
						esc_html__('Light (300)', 'invetex') => '300',
						esc_html__('Normal (400)', 'invetex') => '400',
						esc_html__('Semibold (600)', 'invetex') => '600',
						esc_html__('Bold (700)', 'invetex') => '700',
						esc_html__('Black (900)', 'invetex') => '900'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Title color", 'invetex'),
					"description" => wp_kses_data( __("Select color for the title", 'invetex') ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Title font icon", 'invetex'),
					"description" => wp_kses_data( __("Select font icon for the title from Fontello icons set (if style=iconed)", 'invetex') ),
					"class" => "",
					"group" => esc_html__('Icon &amp; Image', 'invetex'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => invetex_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("or image icon", 'invetex'),
					"description" => wp_kses_data( __("Select image icon for the title instead icon above (if style=iconed)", 'invetex') ),
					"class" => "",
					"group" => esc_html__('Icon &amp; Image', 'invetex'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => invetex_get_sc_param('images'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "picture",
					"heading" => esc_html__("or select uploaded image", 'invetex'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site (if style=iconed)", 'invetex') ),
					"group" => esc_html__('Icon &amp; Image', 'invetex'),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "image_size",
					"heading" => esc_html__("Image (picture) size", 'invetex'),
					"description" => wp_kses_data( __("Select image (picture) size (if style=iconed)", 'invetex') ),
					"group" => esc_html__('Icon &amp; Image', 'invetex'),
					"class" => "",
					"value" => array(
						esc_html__('Small', 'invetex') => 'small',
						esc_html__('Medium', 'invetex') => 'medium',
						esc_html__('Large', 'invetex') => 'large'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "position",
					"heading" => esc_html__("Icon (image) position", 'invetex'),
					"description" => wp_kses_data( __("Select icon (image) position (if style=iconed)", 'invetex') ),
					"group" => esc_html__('Icon &amp; Image', 'invetex'),
					"class" => "",
					"std" => "left",
					"value" => array(
						esc_html__('Top', 'invetex') => 'top',
						esc_html__('Left', 'invetex') => 'left'
					),
					"type" => "dropdown"
				),
				invetex_get_vc_param('id'),
				invetex_get_vc_param('class'),
				invetex_get_vc_param('animation'),
				invetex_get_vc_param('css'),
				invetex_get_vc_param('margin_top'),
				invetex_get_vc_param('margin_bottom'),
				invetex_get_vc_param('margin_left'),
				invetex_get_vc_param('margin_right')
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Title extends INVETEX_VC_ShortCodeSingle {}
	}
}
?>