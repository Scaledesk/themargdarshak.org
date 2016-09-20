<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('invetex_sc_section_theme_setup')) {
	add_action( 'invetex_action_before_init_theme', 'invetex_sc_section_theme_setup' );
	function invetex_sc_section_theme_setup() {
		add_action('invetex_action_shortcodes_list', 		'invetex_sc_section_reg_shortcodes');
		if (function_exists('invetex_exists_visual_composer') && invetex_exists_visual_composer())
			add_action('invetex_action_shortcodes_list_vc','invetex_sc_section_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_section id="unique_id" class="class_name" style="css-styles" dedicated="yes|no"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_section]
*/

invetex_storage_set('sc_section_dedicated', '');

if (!function_exists('invetex_sc_section')) {	
	function invetex_sc_section($atts, $content=null){	
		if (invetex_in_shortcode_blogger()) return '';
		extract(invetex_html_decode(shortcode_atts(array(
			// Individual params
			"dedicated" => "no",
			"align" => "none",
			"columns" => "none",
			"pan" => "no",
			"scroll" => "no",
			"scroll_dir" => "horizontal",
			"scroll_controls" => "hide",
			"color" => "",
			"scheme" => "",
			"bg_color" => "",
			"bg_image" => "",
			"bg_overlay" => "",
			"bg_texture" => "",
			"bg_tile" => "no",
			"bg_padding" => "yes",
			"font_size" => "",
			"font_weight" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link_caption" => esc_html__('Learn more', 'invetex'),
			"link" => '',
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
	
		if ($bg_image > 0) {
			$attach = wp_get_attachment_image_src( $bg_image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$bg_image = $attach[0];
		}
	
		if ($bg_overlay > 0) {
			if ($bg_color=='') $bg_color = invetex_get_scheme_color('bg');
			$rgb = invetex_hex2rgb($bg_color);
		}
	
		$class .= ($class ? ' ' : '') . invetex_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= ($color !== '' ? 'color:' . esc_attr($color) . ';' : '')
			.($bg_color !== '' && $bg_overlay==0 ? 'background-color:' . esc_attr($bg_color) . ';' : '')
			.($bg_image !== '' ? 'background-image:url(' . esc_url($bg_image) . ');'.(invetex_param_is_on($bg_tile) ? 'background-repeat:repeat;' : 'background-repeat:no-repeat;background-size:cover;') : '')
			.(!invetex_param_is_off($pan) ? 'position:relative;' : '')
			.($font_size != '' ? 'font-size:' . esc_attr(invetex_prepare_css_value($font_size)) . '; line-height: 1.3em;' : '')
			.($font_weight != '' && !invetex_param_is_inherit($font_weight) ? 'font-weight:' . esc_attr($font_weight) . ';' : '');
		$css_dim = invetex_get_css_dimensions_from_values($width, $height);
		if ($bg_image == '' && $bg_color == '' && $bg_overlay==0 && $bg_texture==0 && invetex_strlen($bg_texture)<2) $css .= $css_dim;
		
		$width  = invetex_prepare_css_value($width);
		$height = invetex_prepare_css_value($height);
	
		if ((!invetex_param_is_off($scroll) || !invetex_param_is_off($pan)) && empty($id)) $id = 'sc_section_'.str_replace('.', '', mt_rand());
	
		if (!invetex_param_is_off($scroll)) invetex_enqueue_slider();
	
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_section' 
					. ($class ? ' ' . esc_attr($class) : '') 
					. ($scheme && !invetex_param_is_off($scheme) && !invetex_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($columns) && $columns!='none' ? ' column-'.esc_attr($columns) : '') 
					. (invetex_param_is_on($scroll) && !invetex_param_is_off($scroll_controls) ? ' sc_scroll_controls sc_scroll_controls_'.esc_attr($scroll_dir).' sc_scroll_controls_type_'.esc_attr($scroll_controls) : '')
					. '"'
				. (!invetex_param_is_off($animation) ? ' data-animation="'.esc_attr(invetex_get_animation_classes($animation)).'"' : '')
				. ($css!='' || $css_dim!='' ? ' style="'.esc_attr($css.$css_dim).'"' : '')
				.'>' 
				. '<div class="sc_section_inner">'
					. ($bg_image !== '' || $bg_color !== '' || $bg_overlay>0 || $bg_texture>0 || invetex_strlen($bg_texture)>2
						? '<div class="sc_section_overlay'.($bg_texture>0 ? ' texture_bg_'.esc_attr($bg_texture) : '') . '"'
							. ' style="' . ($bg_overlay>0 ? 'background-color:rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].','.min(1, max(0, $bg_overlay)).');' : '')
								. (invetex_strlen($bg_texture)>2 ? 'background-image:url('.esc_url($bg_texture).');' : '')
								. '"'
								. ($bg_overlay > 0 ? ' data-overlay="'.esc_attr($bg_overlay).'" data-bg_color="'.esc_attr($bg_color).'"' : '')
								. '>'
								. '<div class="sc_section_content' . (invetex_param_is_on($bg_padding) ? ' padding_on' : ' padding_off') . '"'
									. ' style="'.esc_attr($css_dim).'"'
									. '>'
						: '')
					. (invetex_param_is_on($scroll) 
						? '<div id="'.esc_attr($id).'_scroll" class="sc_scroll sc_scroll_'.esc_attr($scroll_dir).' swiper-slider-container scroll-container"'
							. ' style="'.($height != '' ? 'height:'.esc_attr($height).';' : '') . ($width != '' ? 'width:'.esc_attr($width).';' : '').'"'
							. '>'
							. '<div class="sc_scroll_wrapper swiper-wrapper">' 
							. '<div class="sc_scroll_slide swiper-slide">' 
						: '')
					. (invetex_param_is_on($pan) 
						? '<div id="'.esc_attr($id).'_pan" class="sc_pan sc_pan_'.esc_attr($scroll_dir).'">' 
						: '')
							. (!empty($subtitle) ? '<h6 class="sc_section_subtitle sc_item_subtitle">' . trim(invetex_strmacros($subtitle)) . '</h6>' : '')
							. (!empty($title) ? '<h2 class="sc_section_title sc_item_title">' . trim(invetex_strmacros($title)) . '</h2>' : '')
							. (!empty($description) ? '<div class="sc_section_descr sc_item_descr">' . trim(invetex_strmacros($description)) . '</div>' : '')
							. '<div class="sc_section_content_wrap">' . do_shortcode($content) . '</div>'
							. (!empty($link) ? '<div class="sc_section_button sc_item_button">'.invetex_do_shortcode('[trx_button link="'.esc_url($link).'"]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
					. (invetex_param_is_on($pan) ? '</div>' : '')
					. (invetex_param_is_on($scroll) 
						? '</div></div><div id="'.esc_attr($id).'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_'.esc_attr($scroll_dir).' '.esc_attr($id).'_scroll_bar"></div></div>'
							. (!invetex_param_is_off($scroll_controls) ? '<div class="sc_scroll_controls_wrap"><a class="sc_scroll_prev" href="#"></a><a class="sc_scroll_next" href="#"></a></div>' : '')
						: '')
					. ($bg_image !== '' || $bg_color !== '' || $bg_overlay > 0 || $bg_texture>0 || invetex_strlen($bg_texture)>2 ? '</div></div>' : '')
					. '</div>'
				. '</div>';
		if (invetex_param_is_on($dedicated)) {
			if (invetex_storage_get('sc_section_dedicated')=='') {
				invetex_storage_set('sc_section_dedicated', $output);
			}
			$output = '';
		}
		return apply_filters('invetex_shortcode_output', $output, 'trx_section', $atts, $content);
	}
	invetex_require_shortcode('trx_section', 'invetex_sc_section');
}

if (!function_exists('invetex_sc_block')) {	
	function invetex_sc_block($atts, $content=null) {
		$atts['class'] = (!empty($atts['class']) ? $atts['class'] . ' ' : '') . 'sc_section_block';
		return apply_filters('invetex_shortcode_output', invetex_sc_section($atts, $content), 'trx_block', $atts, $content);
	}
	invetex_require_shortcode('trx_block', 'invetex_sc_block');
}


/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'invetex_sc_section_reg_shortcodes' ) ) {
	//add_action('invetex_action_shortcodes_list', 'invetex_sc_section_reg_shortcodes');
	function invetex_sc_section_reg_shortcodes() {
	
		$sc = array(
			"title" => esc_html__("Block container", 'invetex'),
			"desc" => wp_kses_data( __("Container for any block ([trx_section] analog - to enable nesting)", 'invetex') ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"title" => array(
					"title" => esc_html__("Title", 'invetex'),
					"desc" => wp_kses_data( __("Title for the block", 'invetex') ),
					"value" => "",
					"type" => "text"
				),
				"subtitle" => array(
					"title" => esc_html__("Subtitle", 'invetex'),
					"desc" => wp_kses_data( __("Subtitle for the block", 'invetex') ),
					"value" => "",
					"type" => "text"
				),
				"description" => array(
					"title" => esc_html__("Description", 'invetex'),
					"desc" => wp_kses_data( __("Short description for the block", 'invetex') ),
					"value" => "",
					"type" => "textarea"
				),
				"link" => array(
					"title" => esc_html__("Button URL", 'invetex'),
					"desc" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'invetex') ),
					"value" => "",
					"type" => "text"
				),
				"link_caption" => array(
					"title" => esc_html__("Button caption", 'invetex'),
					"desc" => wp_kses_data( __("Caption for the button at the bottom of the block", 'invetex') ),
					"value" => "",
					"type" => "text"
				),
				"dedicated" => array(
					"title" => esc_html__("Dedicated", 'invetex'),
					"desc" => wp_kses_data( __("Use this block as dedicated content - show it before post title on single page", 'invetex') ),
					"value" => "no",
					"type" => "switch",
					"options" => invetex_get_sc_param('yes_no')
				),
				"align" => array(
					"title" => esc_html__("Align", 'invetex'),
					"desc" => wp_kses_data( __("Select block alignment", 'invetex') ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => invetex_get_sc_param('align')
				),
				"columns" => array(
					"title" => esc_html__("Columns emulation", 'invetex'),
					"desc" => wp_kses_data( __("Select width for columns emulation", 'invetex') ),
					"value" => "none",
					"type" => "checklist",
					"options" => invetex_get_sc_param('columns')
				), 
				"pan" => array(
					"title" => esc_html__("Use pan effect", 'invetex'),
					"desc" => wp_kses_data( __("Use pan effect to show section content", 'invetex') ),
					"divider" => true,
					"value" => "no",
					"type" => "switch",
					"options" => invetex_get_sc_param('yes_no')
				),
				"scroll" => array(
					"title" => esc_html__("Use scroller", 'invetex'),
					"desc" => wp_kses_data( __("Use scroller to show section content", 'invetex') ),
					"divider" => true,
					"value" => "no",
					"type" => "switch",
					"options" => invetex_get_sc_param('yes_no')
				),
				"scroll_dir" => array(
					"title" => esc_html__("Scroll and Pan direction", 'invetex'),
					"desc" => wp_kses_data( __("Scroll and Pan direction (if Use scroller = yes or Pan = yes)", 'invetex') ),
					"dependency" => array(
						'pan' => array('yes'),
						'scroll' => array('yes')
					),
					"value" => "horizontal",
					"type" => "switch",
					"size" => "big",
					"options" => invetex_get_sc_param('dir')
				),
				"scroll_controls" => array(
					"title" => esc_html__("Scroll controls", 'invetex'),
					"desc" => wp_kses_data( __("Show scroll controls (if Use scroller = yes)", 'invetex') ),
					"dependency" => array(
						'scroll' => array('yes')
					),
					"value" => "hide",
					"type" => "checklist",
					"options" => invetex_get_sc_param('controls')
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'invetex'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'invetex') ),
					"value" => "",
					"type" => "checklist",
					"options" => invetex_get_sc_param('schemes')
				),
				"color" => array(
					"title" => esc_html__("Fore color", 'invetex'),
					"desc" => wp_kses_data( __("Any color for objects in this section", 'invetex') ),
					"divider" => true,
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", 'invetex'),
					"desc" => wp_kses_data( __("Any background color for this section", 'invetex') ),
					"value" => "",
					"type" => "color"
				),
				"bg_image" => array(
					"title" => esc_html__("Background image URL", 'invetex'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the background", 'invetex') ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"bg_tile" => array(
					"title" => esc_html__("Tile background image", 'invetex'),
					"desc" => wp_kses_data( __("Do you want tile background image or image cover whole block?", 'invetex') ),
					"value" => "no",
					"dependency" => array(
						'bg_image' => array('not_empty')
					),
					"type" => "switch",
					"options" => invetex_get_sc_param('yes_no')
				),
				"bg_overlay" => array(
					"title" => esc_html__("Overlay", 'invetex'),
					"desc" => wp_kses_data( __("Overlay color opacity (from 0.0 to 1.0)", 'invetex') ),
					"min" => "0",
					"max" => "1",
					"step" => "0.1",
					"value" => "0",
					"type" => "spinner"
				),
				"bg_texture" => array(
					"title" => esc_html__("Texture", 'invetex'),
					"desc" => wp_kses_data( __("Predefined texture style from 1 to 11. 0 - without texture.", 'invetex') ),
					"min" => "0",
					"max" => "11",
					"step" => "1",
					"value" => "0",
					"type" => "spinner"
				),
				"bg_padding" => array(
					"title" => esc_html__("Paddings around content", 'invetex'),
					"desc" => wp_kses_data( __("Add paddings around content in this section (only if bg_color or bg_image enabled).", 'invetex') ),
					"value" => "yes",
					"dependency" => array(
						'compare' => 'or',
						'bg_color' => array('not_empty'),
						'bg_texture' => array('not_empty'),
						'bg_image' => array('not_empty')
					),
					"type" => "switch",
					"options" => invetex_get_sc_param('yes_no')
				),
				"font_size" => array(
					"title" => esc_html__("Font size", 'invetex'),
					"desc" => wp_kses_data( __("Font size of the text (default - in pixels, allows any CSS units of measure)", 'invetex') ),
					"value" => "",
					"type" => "text"
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", 'invetex'),
					"desc" => wp_kses_data( __("Font weight of the text", 'invetex') ),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'100' => esc_html__('Thin (100)', 'invetex'),
						'300' => esc_html__('Light (300)', 'invetex'),
						'400' => esc_html__('Normal (400)', 'invetex'),
						'700' => esc_html__('Bold (700)', 'invetex')
					)
				),
				"_content_" => array(
					"title" => esc_html__("Container content", 'invetex'),
					"desc" => wp_kses_data( __("Content for section container", 'invetex') ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
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
		);
		invetex_sc_map("trx_block", $sc);
		$sc["title"] = esc_html__("Section container", 'invetex');
		$sc["desc"] = esc_html__("Container for any section ([trx_block] analog - to enable nesting)", 'invetex');
		invetex_sc_map("trx_section", $sc);
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'invetex_sc_section_reg_shortcodes_vc' ) ) {
	//add_action('invetex_action_shortcodes_list_vc', 'invetex_sc_section_reg_shortcodes_vc');
	function invetex_sc_section_reg_shortcodes_vc() {
	
		$sc = array(
			"base" => "trx_block",
			"name" => esc_html__("Block container", 'invetex'),
			"description" => wp_kses_data( __("Container for any block ([trx_section] analog - to enable nesting)", 'invetex') ),
			"category" => esc_html__('Content', 'invetex'),
			'icon' => 'icon_trx_block',
			"class" => "trx_sc_collection trx_sc_block",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "dedicated",
					"heading" => esc_html__("Dedicated", 'invetex'),
					"description" => wp_kses_data( __("Use this block as dedicated content - show it before post title on single page", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"value" => array(esc_html__('Use as dedicated content', 'invetex') => 'yes'),
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
				array(
					"param_name" => "columns",
					"heading" => esc_html__("Columns emulation", 'invetex'),
					"description" => wp_kses_data( __("Select width for columns emulation", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(invetex_get_sc_param('columns')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", 'invetex'),
					"description" => wp_kses_data( __("Title for the block", 'invetex') ),
					"admin_label" => true,
					"group" => esc_html__('Captions', 'invetex'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "subtitle",
					"heading" => esc_html__("Subtitle", 'invetex'),
					"description" => wp_kses_data( __("Subtitle for the block", 'invetex') ),
					"group" => esc_html__('Captions', 'invetex'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "description",
					"heading" => esc_html__("Description", 'invetex'),
					"description" => wp_kses_data( __("Description for the block", 'invetex') ),
					"group" => esc_html__('Captions', 'invetex'),
					"class" => "",
					"value" => "",
					"type" => "textarea"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Button URL", 'invetex'),
					"description" => wp_kses_data( __("Link URL for the button at the bottom of the block", 'invetex') ),
					"group" => esc_html__('Captions', 'invetex'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link_caption",
					"heading" => esc_html__("Button caption", 'invetex'),
					"description" => wp_kses_data( __("Caption for the button at the bottom of the block", 'invetex') ),
					"group" => esc_html__('Captions', 'invetex'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "pan",
					"heading" => esc_html__("Use pan effect", 'invetex'),
					"description" => wp_kses_data( __("Use pan effect to show section content", 'invetex') ),
					"group" => esc_html__('Scroll', 'invetex'),
					"class" => "",
					"value" => array(esc_html__('Content scroller', 'invetex') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "scroll",
					"heading" => esc_html__("Use scroller", 'invetex'),
					"description" => wp_kses_data( __("Use scroller to show section content", 'invetex') ),
					"group" => esc_html__('Scroll', 'invetex'),
					"admin_label" => true,
					"class" => "",
					"value" => array(esc_html__('Content scroller', 'invetex') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "scroll_dir",
					"heading" => esc_html__("Scroll direction", 'invetex'),
					"description" => wp_kses_data( __("Scroll direction (if Use scroller = yes)", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"group" => esc_html__('Scroll', 'invetex'),
					"value" => array_flip(invetex_get_sc_param('dir')),
					'dependency' => array(
						'element' => 'scroll',
						'not_empty' => true
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "scroll_controls",
					"heading" => esc_html__("Scroll controls", 'invetex'),
					"description" => wp_kses_data( __("Show scroll controls (if Use scroller = yes)", 'invetex') ),
					"class" => "",
					"group" => esc_html__('Scroll', 'invetex'),
					'dependency' => array(
						'element' => 'scroll',
						'not_empty' => true
					),
					"value" => array_flip(invetex_get_sc_param('controls')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", 'invetex'),
					"description" => wp_kses_data( __("Select color scheme for this block", 'invetex') ),
					"group" => esc_html__('Colors and Images', 'invetex'),
					"class" => "",
					"value" => array_flip(invetex_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Fore color", 'invetex'),
					"description" => wp_kses_data( __("Any color for objects in this section", 'invetex') ),
					"group" => esc_html__('Colors and Images', 'invetex'),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", 'invetex'),
					"description" => wp_kses_data( __("Any background color for this section", 'invetex') ),
					"group" => esc_html__('Colors and Images', 'invetex'),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_image",
					"heading" => esc_html__("Background image URL", 'invetex'),
					"description" => wp_kses_data( __("Select background image from library for this section", 'invetex') ),
					"group" => esc_html__('Colors and Images', 'invetex'),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "bg_tile",
					"heading" => esc_html__("Tile background image", 'invetex'),
					"description" => wp_kses_data( __("Do you want tile background image or image cover whole block?", 'invetex') ),
					"group" => esc_html__('Colors and Images', 'invetex'),
					"class" => "",
					'dependency' => array(
						'element' => 'bg_image',
						'not_empty' => true
					),
					"std" => "no",
					"value" => array(esc_html__('Tile background image', 'invetex') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "bg_overlay",
					"heading" => esc_html__("Overlay", 'invetex'),
					"description" => wp_kses_data( __("Overlay color opacity (from 0.0 to 1.0)", 'invetex') ),
					"group" => esc_html__('Colors and Images', 'invetex'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_texture",
					"heading" => esc_html__("Texture", 'invetex'),
					"description" => wp_kses_data( __("Texture style from 1 to 11. Empty or 0 - without texture.", 'invetex') ),
					"group" => esc_html__('Colors and Images', 'invetex'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_padding",
					"heading" => esc_html__("Paddings around content", 'invetex'),
					"description" => wp_kses_data( __("Add paddings around content in this section (only if bg_color or bg_image enabled).", 'invetex') ),
					"group" => esc_html__('Colors and Images', 'invetex'),
					"class" => "",
					'dependency' => array(
						'element' => array('bg_color','bg_texture','bg_image'),
						'not_empty' => true
					),
					"std" => "yes",
					"value" => array(esc_html__('Disable padding around content in this block', 'invetex') => 'no'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", 'invetex'),
					"description" => wp_kses_data( __("Font size of the text (default - in pixels, allows any CSS units of measure)", 'invetex') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", 'invetex'),
					"description" => wp_kses_data( __("Font weight of the text", 'invetex') ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'invetex') => 'inherit',
						esc_html__('Thin (100)', 'invetex') => '100',
						esc_html__('Light (300)', 'invetex') => '300',
						esc_html__('Normal (400)', 'invetex') => '400',
						esc_html__('Bold (700)', 'invetex') => '700'
					),
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
		);
		
		// Block
		vc_map($sc);
		
		// Section
		$sc["base"] = 'trx_section';
		$sc["name"] = esc_html__("Section container", 'invetex');
		$sc["description"] = wp_kses_data( __("Container for any section ([trx_block] analog - to enable nesting)", 'invetex') );
		$sc["class"] = "trx_sc_collection trx_sc_section";
		$sc["icon"] = 'icon_trx_section';
		vc_map($sc);
		
		class WPBakeryShortCode_Trx_Block extends INVETEX_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Section extends INVETEX_VC_ShortCodeCollection {}
	}
}
?>