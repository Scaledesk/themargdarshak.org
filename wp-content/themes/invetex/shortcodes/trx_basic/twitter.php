<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('invetex_sc_twitter_theme_setup')) {
	add_action( 'invetex_action_before_init_theme', 'invetex_sc_twitter_theme_setup' );
	function invetex_sc_twitter_theme_setup() {
		add_action('invetex_action_shortcodes_list', 		'invetex_sc_twitter_reg_shortcodes');
		if (function_exists('invetex_exists_visual_composer') && invetex_exists_visual_composer())
			add_action('invetex_action_shortcodes_list_vc','invetex_sc_twitter_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_twitter id="unique_id" user="username" consumer_key="" consumer_secret="" token_key="" token_secret=""]
*/

if (!function_exists('invetex_sc_twitter')) {	
	function invetex_sc_twitter($atts, $content=null){	
		if (invetex_in_shortcode_blogger()) return '';
		extract(invetex_html_decode(shortcode_atts(array(
			// Individual params
			"user" => "",
			"consumer_key" => "",
			"consumer_secret" => "",
			"token_key" => "",
			"token_secret" => "",
			"count" => "3",
			"controls" => "yes",
			"interval" => "",
			"autoheight" => "no",
			"align" => "",
			"scheme" => "",
			"bg_color" => "",
			"bg_image" => "",
			"bg_overlay" => "",
			"bg_texture" => "",
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
	
		$twitter_username = $user ? $user : invetex_get_theme_option('twitter_username');
		$twitter_consumer_key = $consumer_key ? $consumer_key : invetex_get_theme_option('twitter_consumer_key');
		$twitter_consumer_secret = $consumer_secret ? $consumer_secret : invetex_get_theme_option('twitter_consumer_secret');
		$twitter_token_key = $token_key ? $token_key : invetex_get_theme_option('twitter_token_key');
		$twitter_token_secret = $token_secret ? $token_secret : invetex_get_theme_option('twitter_token_secret');
		$twitter_count = max(1, $count ? $count : intval(invetex_get_theme_option('twitter_count')));
	
		if (empty($id)) $id = "sc_testimonials_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
		if (!empty($height) && invetex_param_is_on($autoheight)) $autoheight = "no";
		if (empty($interval)) $interval = mt_rand(5000, 10000);
	
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
		$ws = invetex_get_css_dimensions_from_values($width);
		$hs = invetex_get_css_dimensions_from_values('', $height);
	
		$css .= ($hs) . ($ws);
	
		$output = '';
	
		if (!empty($twitter_consumer_key) && !empty($twitter_consumer_secret) && !empty($twitter_token_key) && !empty($twitter_token_secret)) {
			$data = invetex_get_twitter_data(array(
				'mode'            => 'user_timeline',
				'consumer_key'    => $twitter_consumer_key,
				'consumer_secret' => $twitter_consumer_secret,
				'token'           => $twitter_token_key,
				'secret'          => $twitter_token_secret
				)
			);
			if ($data && isset($data[0]['text'])) {
				invetex_enqueue_slider('swiper');
				$output = ($bg_color!='' || $bg_image!='' || $bg_overlay>0 || $bg_texture>0 || invetex_strlen($bg_texture)>2 || ($scheme && !invetex_param_is_off($scheme) && !invetex_param_is_inherit($scheme))
						? '<div class="sc_twitter_wrap sc_section'
								. ($scheme && !invetex_param_is_off($scheme) && !invetex_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
								. ($align && $align!='none' && !invetex_param_is_inherit($align) ? ' align' . esc_attr($align) : '')
								. '"'
							.' style="'
								. ($bg_color !== '' && $bg_overlay==0 ? 'background-color:' . esc_attr($bg_color) . ';' : '')
								. ($bg_image !== '' ? 'background-image:url('.esc_url($bg_image).');' : '')
								. '"'
							. (!invetex_param_is_off($animation) ? ' data-animation="'.esc_attr(invetex_get_animation_classes($animation)).'"' : '')
							. '>'
							. '<div class="sc_section_overlay'.($bg_texture>0 ? ' texture_bg_'.esc_attr($bg_texture) : '') . '"'
									. ' style="' 
										. ($bg_overlay>0 ? 'background-color:rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].','.min(1, max(0, $bg_overlay)).');' : '')
										. (invetex_strlen($bg_texture)>2 ? 'background-image:url('.esc_url($bg_texture).');' : '')
										. '"'
										. ($bg_overlay > 0 ? ' data-overlay="'.esc_attr($bg_overlay).'" data-bg_color="'.esc_attr($bg_color).'"' : '')
										. '>' 
						: '')
						. '<div class="sc_twitter'
								. (!empty($class) ? ' '.esc_attr($class) : '')
								. ($bg_color=='' && $bg_image=='' && $bg_overlay==0 && ($bg_texture=='' || $bg_texture=='0') && $align && $align!='none' && !invetex_param_is_inherit($align) ? ' align' . esc_attr($align) : '')
								. '"'
							. ($bg_color=='' && $bg_image=='' && $bg_overlay==0 && ($bg_texture=='' || $bg_texture=='0') && !invetex_param_is_off($animation) ? ' data-animation="'.esc_attr(invetex_get_animation_classes($animation)).'"' : '')
							. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
							. '>'
								. '<div class="sc_slider_swiper sc_slider_nopagination swiper-slider-container'
										. (invetex_param_is_on($controls) ? ' sc_slider_controls' : ' sc_slider_nocontrols')
										. (invetex_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
										. ($hs ? ' sc_slider_height_fixed' : '')
										. '"'
									. (!empty($width) && invetex_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
									. (!empty($height) && invetex_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
									. ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
									. '>'
									. '<div class="slides swiper-wrapper">';
				$cnt = 0;
				if (is_array($data) && count($data) > 0) {
					foreach ($data as $tweet) {
						if (invetex_substr($tweet['text'], 0, 1)=='@') continue;
							$output .= '<div class="swiper-slide" data-style="'.esc_attr(($ws).($hs)).'" style="'.esc_attr(($ws).($hs)).'">'
										. '<div class="sc_twitter_item">'
											. '<span class="sc_twitter_icon icon-twitter"></span>'
											. '<div class="sc_twitter_content">'
												. '<a href="' . esc_url('https://twitter.com/'.($twitter_username)).'" class="sc_twitter_author" target="_blank">@' . esc_html($tweet['user']['screen_name']) . '</a> '
												. force_balance_tags(invetex_prepare_twitter_text($tweet))
											. '</div>'
										. '</div>'
									. '</div>';
						if (++$cnt >= $twitter_count) break;
					}
				}
				$output .= '</div>'
						. '<div class="sc_slider_controls_wrap"><a class="sc_slider_prev" href="#"></a><a class="sc_slider_next" href="#"></a></div>'
						. '</div>'
					. '</div>'
					. ($bg_color!='' || $bg_image!='' || $bg_overlay>0 || $bg_texture>0 || invetex_strlen($bg_texture)>2
						?  '</div></div>'
						: '');
			}
		}
		return apply_filters('invetex_shortcode_output', $output, 'trx_twitter', $atts, $content);
	}
	invetex_require_shortcode('trx_twitter', 'invetex_sc_twitter');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'invetex_sc_twitter_reg_shortcodes' ) ) {
	//add_action('invetex_action_shortcodes_list', 'invetex_sc_twitter_reg_shortcodes');
	function invetex_sc_twitter_reg_shortcodes() {
	
		invetex_sc_map("trx_twitter", array(
			"title" => esc_html__("Twitter", 'invetex'),
			"desc" => wp_kses_data( __("Insert twitter feed into post (page)", 'invetex') ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"user" => array(
					"title" => esc_html__("Twitter Username", 'invetex'),
					"desc" => wp_kses_data( __("Your username in the twitter account. If empty - get it from Theme Options.", 'invetex') ),
					"value" => "",
					"type" => "text"
				),
				"consumer_key" => array(
					"title" => esc_html__("Consumer Key", 'invetex'),
					"desc" => wp_kses_data( __("Consumer Key from the twitter account", 'invetex') ),
					"value" => "",
					"type" => "text"
				),
				"consumer_secret" => array(
					"title" => esc_html__("Consumer Secret", 'invetex'),
					"desc" => wp_kses_data( __("Consumer Secret from the twitter account", 'invetex') ),
					"value" => "",
					"type" => "text"
				),
				"token_key" => array(
					"title" => esc_html__("Token Key", 'invetex'),
					"desc" => wp_kses_data( __("Token Key from the twitter account", 'invetex') ),
					"value" => "",
					"type" => "text"
				),
				"token_secret" => array(
					"title" => esc_html__("Token Secret", 'invetex'),
					"desc" => wp_kses_data( __("Token Secret from the twitter account", 'invetex') ),
					"value" => "",
					"type" => "text"
				),
				"count" => array(
					"title" => esc_html__("Tweets number", 'invetex'),
					"desc" => wp_kses_data( __("Tweets number to show", 'invetex') ),
					"divider" => true,
					"value" => 3,
					"max" => 20,
					"min" => 1,
					"type" => "spinner"
				),
				"controls" => array(
					"title" => esc_html__("Show arrows", 'invetex'),
					"desc" => wp_kses_data( __("Show control buttons", 'invetex') ),
					"value" => "yes",
					"type" => "switch",
					"options" => invetex_get_sc_param('yes_no')
				),
				"interval" => array(
					"title" => esc_html__("Tweets change interval", 'invetex'),
					"desc" => wp_kses_data( __("Tweets change interval (in milliseconds: 1000ms = 1s)", 'invetex') ),
					"value" => 7000,
					"step" => 500,
					"min" => 0,
					"type" => "spinner"
				),
				"align" => array(
					"title" => esc_html__("Alignment", 'invetex'),
					"desc" => wp_kses_data( __("Alignment of the tweets block", 'invetex') ),
					"divider" => true,
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => invetex_get_sc_param('align')
				),
				"autoheight" => array(
					"title" => esc_html__("Autoheight", 'invetex'),
					"desc" => wp_kses_data( __("Change whole slider's height (make it equal current slide's height)", 'invetex') ),
					"value" => "yes",
					"type" => "switch",
					"options" => invetex_get_sc_param('yes_no')
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", 'invetex'),
					"desc" => wp_kses_data( __("Select color scheme for this block", 'invetex') ),
					"value" => "",
					"type" => "checklist",
					"options" => invetex_get_sc_param('schemes')
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
if ( !function_exists( 'invetex_sc_twitter_reg_shortcodes_vc' ) ) {
	//add_action('invetex_action_shortcodes_list_vc', 'invetex_sc_twitter_reg_shortcodes_vc');
	function invetex_sc_twitter_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_twitter",
			"name" => esc_html__("Twitter", 'invetex'),
			"description" => wp_kses_data( __("Insert twitter feed into post (page)", 'invetex') ),
			"category" => esc_html__('Content', 'invetex'),
			'icon' => 'icon_trx_twitter',
			"class" => "trx_sc_single trx_sc_twitter",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "user",
					"heading" => esc_html__("Twitter Username", 'invetex'),
					"description" => wp_kses_data( __("Your username in the twitter account. If empty - get it from Theme Options.", 'invetex') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "consumer_key",
					"heading" => esc_html__("Consumer Key", 'invetex'),
					"description" => wp_kses_data( __("Consumer Key from the twitter account", 'invetex') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "consumer_secret",
					"heading" => esc_html__("Consumer Secret", 'invetex'),
					"description" => wp_kses_data( __("Consumer Secret from the twitter account", 'invetex') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "token_key",
					"heading" => esc_html__("Token Key", 'invetex'),
					"description" => wp_kses_data( __("Token Key from the twitter account", 'invetex') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "token_secret",
					"heading" => esc_html__("Token Secret", 'invetex'),
					"description" => wp_kses_data( __("Token Secret from the twitter account", 'invetex') ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "count",
					"heading" => esc_html__("Tweets number", 'invetex'),
					"description" => wp_kses_data( __("Number tweets to show", 'invetex') ),
					"class" => "",
					"divider" => true,
					"value" => 3,
					"type" => "textfield"
				),
				array(
					"param_name" => "controls",
					"heading" => esc_html__("Show arrows", 'invetex'),
					"description" => wp_kses_data( __("Show control buttons", 'invetex') ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(invetex_get_sc_param('yes_no')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "interval",
					"heading" => esc_html__("Tweets change interval", 'invetex'),
					"description" => wp_kses_data( __("Tweets change interval (in milliseconds: 1000ms = 1s)", 'invetex') ),
					"class" => "",
					"value" => "7000",
					"type" => "textfield"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", 'invetex'),
					"description" => wp_kses_data( __("Alignment of the tweets block", 'invetex') ),
					"class" => "",
					"value" => array_flip(invetex_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "autoheight",
					"heading" => esc_html__("Autoheight", 'invetex'),
					"description" => wp_kses_data( __("Change whole slider's height (make it equal current slide's height)", 'invetex') ),
					"class" => "",
					"value" => array("Autoheight" => "yes" ),
					"type" => "checkbox"
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
		) );
		
		class WPBakeryShortCode_Trx_Twitter extends INVETEX_VC_ShortCodeSingle {}
	}
}
?>