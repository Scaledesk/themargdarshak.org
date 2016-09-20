<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('invetex_sc_chat_theme_setup')) {
	add_action( 'invetex_action_before_init_theme', 'invetex_sc_chat_theme_setup' );
	function invetex_sc_chat_theme_setup() {
		add_action('invetex_action_shortcodes_list', 		'invetex_sc_chat_reg_shortcodes');
		if (function_exists('invetex_exists_visual_composer') && invetex_exists_visual_composer())
			add_action('invetex_action_shortcodes_list_vc','invetex_sc_chat_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_chat id="unique_id" link="url" title=""]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_chat]
[trx_chat id="unique_id" link="url" title=""]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_chat]
...
*/

if (!function_exists('invetex_sc_chat')) {	
	function invetex_sc_chat($atts, $content=null){	
		if (invetex_in_shortcode_blogger()) return '';
		extract(invetex_html_decode(shortcode_atts(array(
			// Individual params
			"photo" => "",
			"title" => "",
			"link" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . invetex_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= invetex_get_css_dimensions_from_values($width, $height);
		$title = $title=='' ? $link : $title;
		if (!empty($photo)) {
			if ($photo > 0) {
				$attach = wp_get_attachment_image_src( $photo, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$photo = $attach[0];
			}
			$photo = invetex_get_resized_image_tag($photo, 75, 75);
		}
		$content = do_shortcode($content);
		if (invetex_substr($content, 0, 2)!='<p') $content = '<p>' . ($content) . '</p>';
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_chat' . (!empty($class) ? ' '.esc_attr($class) : '') . '"' 
				. (!invetex_param_is_off($animation) ? ' data-animation="'.esc_attr(invetex_get_animation_classes($animation)).'"' : '')
				. ($css ? ' style="'.esc_attr($css).'"' : '') 
				. '>'
					. '<div class="sc_chat_inner">'
						. ($photo ? '<div class="sc_chat_avatar">'.($photo).'</div>' : '')
						. ($title == '' ? '' : ('<div class="sc_chat_title">' . ($link!='' ? '<a href="'.esc_url($link).'">' : '') . ($title) . ($link!='' ? '</a>' : '') . '</div>'))
						. '<div class="sc_chat_content">'.($content).'</div>'
					. '</div>'
				. '</div>';
		return apply_filters('invetex_shortcode_output', $output, 'trx_chat', $atts, $content);
	}
	invetex_require_shortcode('trx_chat', 'invetex_sc_chat');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'invetex_sc_chat_reg_shortcodes' ) ) {
	//add_action('invetex_action_shortcodes_list', 'invetex_sc_chat_reg_shortcodes');
	function invetex_sc_chat_reg_shortcodes() {
	
		invetex_sc_map("trx_chat", array(
			"title" => esc_html__("Chat", 'invetex'),
			"desc" => wp_kses_data( __("Chat message", 'invetex') ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"title" => array(
					"title" => esc_html__("Item title", 'invetex'),
					"desc" => wp_kses_data( __("Chat item title", 'invetex') ),
					"value" => "",
					"type" => "text"
				),
				"photo" => array(
					"title" => esc_html__("Item photo", 'invetex'),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the item photo (avatar)", 'invetex') ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"link" => array(
					"title" => esc_html__("Item link", 'invetex'),
					"desc" => wp_kses_data( __("Chat item link", 'invetex') ),
					"value" => "",
					"type" => "text"
				),
				"_content_" => array(
					"title" => esc_html__("Chat item content", 'invetex'),
					"desc" => wp_kses_data( __("Current chat item content", 'invetex') ),
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
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'invetex_sc_chat_reg_shortcodes_vc' ) ) {
	//add_action('invetex_action_shortcodes_list_vc', 'invetex_sc_chat_reg_shortcodes_vc');
	function invetex_sc_chat_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_chat",
			"name" => esc_html__("Chat", 'invetex'),
			"description" => wp_kses_data( __("Chat message", 'invetex') ),
			"category" => esc_html__('Content', 'invetex'),
			'icon' => 'icon_trx_chat',
			"class" => "trx_sc_container trx_sc_chat",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("Item title", 'invetex'),
					"description" => wp_kses_data( __("Title for current chat item", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "photo",
					"heading" => esc_html__("Item photo", 'invetex'),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for the item photo (avatar)", 'invetex') ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", 'invetex'),
					"description" => wp_kses_data( __("URL for the link on chat title click", 'invetex') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
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
		
		class WPBakeryShortCode_Trx_Chat extends INVETEX_VC_ShortCodeContainer {}
	}
}
?>