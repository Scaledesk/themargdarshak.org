<?php
/**
 * Theme custom styles
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if (!function_exists('invetex_action_theme_styles_theme_setup')) {
	add_action( 'invetex_action_before_init_theme', 'invetex_action_theme_styles_theme_setup', 1 );
	function invetex_action_theme_styles_theme_setup() {
	
		// Add theme fonts in the used fonts list
		add_filter('invetex_filter_used_fonts',			'invetex_filter_theme_styles_used_fonts');
		// Add theme fonts (from Google fonts) in the main fonts list (if not present).
		add_filter('invetex_filter_list_fonts',			'invetex_filter_theme_styles_list_fonts');

		// Add theme stylesheets
		add_action('invetex_action_add_styles',			'invetex_action_theme_styles_add_styles');
		// Add theme inline styles
		add_filter('invetex_filter_add_styles_inline',		'invetex_filter_theme_styles_add_styles_inline');

		// Add theme scripts
		add_action('invetex_action_add_scripts',			'invetex_action_theme_styles_add_scripts');
		// Add theme scripts inline
		add_filter('invetex_filter_localize_script',		'invetex_filter_theme_styles_localize_script');

		// Add theme less files into list for compilation
		add_filter('invetex_filter_compile_less',			'invetex_filter_theme_styles_compile_less');


		/* Color schemes
		
		// Block's border and background
		bd_color		- border for the entire block
		bg_color		- background color for the entire block
		// Next settings are deprecated
		//bg_image, bg_image_position, bg_image_repeat, bg_image_attachment  - first background image for the entire block
		//bg_image2,bg_image2_position,bg_image2_repeat,bg_image2_attachment - second background image for the entire block
		
		// Additional accented colors (if need)
		accent2			- theme accented color 2
		accent2_hover	- theme accented color 2 (hover state)		
		accent3			- theme accented color 3
		accent3_hover	- theme accented color 3 (hover state)		
		
		// Headers, text and links
		text			- main content
		text_light		- post info
		text_dark		- headers
		text_link		- links
		text_hover		- hover links
		
		// Inverse blocks
		inverse_text	- text on accented background
		inverse_light	- post info on accented background
		inverse_dark	- headers on accented background
		inverse_link	- links on accented background
		inverse_hover	- hovered links on accented background
		
		// Input colors - form fields
		input_text		- inactive text
		input_light		- placeholder text
		input_dark		- focused text
		input_bd_color	- inactive border
		input_bd_hover	- focused borde
		input_bg_color	- inactive background
		input_bg_hover	- focused background
		
		// Alternative colors - highlight blocks, form fields, etc.
		alter_text		- text on alternative background
		alter_light		- post info on alternative background
		alter_dark		- headers on alternative background
		alter_link		- links on alternative background
		alter_hover		- hovered links on alternative background
		alter_bd_color	- alternative border
		alter_bd_hover	- alternative border for hovered state or active field
		alter_bg_color	- alternative background
		alter_bg_hover	- alternative background for hovered state or active field 
		// Next settings are deprecated
		//alter_bg_image, alter_bg_image_position, alter_bg_image_repeat, alter_bg_image_attachment - background image for the alternative block
		
		*/

		// Add color schemes
		invetex_add_color_scheme('original', array(

			'title'					=> esc_html__('Original', 'invetex'),
			
			// Whole block border and background
			'bd_color'				=> '#e0e0e0', //ok
			'bg_color'				=> '#fafafa', //ok
			
			// Headers, text and links colors
			'text'					=> '#6d7275', //ok
			'text_light'			=> '#888490', //ok
			'text_dark'				=> '#28262b', //ok
			'text_link'				=> '#28262b', //ok
			'text_hover'			=> '#54be73', //ok

			// Inverse colors
			'inverse_text'			=> '#ffffff',
			'inverse_light'			=> '#ffffff',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#ffffff',
		
			// Input fields
			'input_text'			=> '#8a8a8a',
			'input_light'			=> '#acb4b6',
			'input_dark'			=> '#6d7275', //ok
			'input_bd_color'		=> '#dddddd',
			'input_bd_hover'		=> '#e4e4e4', // ok can change)
			'input_bg_color'		=> '#ebebeb', //ok
			'input_bg_hover'		=> '#ffffff',//ok
		
			// Alternative blocks (submenu items, etc.)
			'alter_text'			=> '#716f75', //ok
			'alter_light'			=> '#acb4b6',
			'alter_dark'			=> '#28262b',
			'alter_link'			=> '#20c7ca',
			'alter_hover'			=> '#54be73', //ok
			'alter_bd_color'		=> '#dddddd',
			'alter_bd_hover'		=> '#bbbbbb',
			'alter_bg_color'		=> '#ffffff', //ok
			'alter_bg_hover'		=> '#f0f0f0',
			)
		);


		invetex_add_color_scheme('original_in', array(

			'title'					=> esc_html__('Original In', 'invetex'),

			// Whole block border and background
			'bd_color'				=> '#e0e0e0', //ok
			'bg_color'				=> '#ffffff', //ok

			// Headers, text and links colors
			'text'					=> '#6d7275', //ok
			'text_light'			=> '#888490', //ok
			'text_dark'				=> '#28262b', //ok
			'text_link'				=> '#28262b', //ok
			'text_hover'			=> '#54be73', //ok

			// Inverse colors
			'inverse_text'			=> '#ffffff',
			'inverse_light'			=> '#ffffff',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#ffffff',

			// Input fields
			'input_text'			=> '#8a8a8a',
			'input_light'			=> '#acb4b6',
			'input_dark'			=> '#6d7275', //ok
			'input_bd_color'		=> '#dddddd',
			'input_bd_hover'		=> '#e4e4e4', // ok can change)
			'input_bg_color'		=> '#ebebeb', //ok
			'input_bg_hover'		=> '#ffffff',//ok

			// Alternative blocks (submenu items, etc.)
			'alter_text'			=> '#716f75', //ok
			'alter_light'			=> '#acb4b6',
			'alter_dark'			=> '#28262b',
			'alter_link'			=> '#20c7ca',
			'alter_hover'			=> '#54be73', //ok
			'alter_bd_color'		=> '#dddddd',
			'alter_bd_hover'		=> '#bbbbbb',
			'alter_bg_color'		=> '#fafafa', //ok
			'alter_bg_hover'		=> '#f0f0f0',
			)
		);


		// Add color schemes
		/*
		invetex_add_color_scheme('light', array(

			'title'					=> esc_html__('Light', 'invetex'),

			// Whole block border and background
			'bd_color'				=> '#dddddd',
			'bg_color'				=> '#f7f7f7',
		
			// Headers, text and links colors
			'text'					=> '#8a8a8a',
			'text_light'			=> '#acb4b6',
			'text_dark'				=> '#232a34',
			'text_link'				=> '#20c7ca',
			'text_hover'			=> '#189799',

			// Inverse colors
			'inverse_text'			=> '#ffffff',
			'inverse_light'			=> '#ffffff',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#ffffff',
		
			// Input fields
			'input_text'			=> '#8a8a8a',
			'input_light'			=> '#acb4b6',
			'input_dark'			=> '#232a34',
			'input_bd_color'		=> '#e7e7e7',
			'input_bd_hover'		=> '#dddddd',
			'input_bg_color'		=> '#ffffff',
			'input_bg_hover'		=> '#f0f0f0',
		
			// Alternative blocks (submenu items, etc.)
			'alter_text'			=> '#8a8a8a',
			'alter_light'			=> '#acb4b6',
			'alter_dark'			=> '#232a34',
			'alter_link'			=> '#20c7ca',
			'alter_hover'			=> '#189799',
			'alter_bd_color'		=> '#e7e7e7',
			'alter_bd_hover'		=> '#dddddd',
			'alter_bg_color'		=> '#ffffff',
			'alter_bg_hover'		=> '#f0f0f0',
			)
		);
*/
		// Add color schemes
		invetex_add_color_scheme('dark', array(

			'title'					=> esc_html__('Dark', 'invetex'),
			
			// Whole block border and background
			'bd_color'				=> '#7d7d7d',
			'bg_color'				=> '#222326', //ok

			// Headers, text and links colors
			'text'					=> '#aab0b6', //ok
			'text_light'			=> '#ebebeb', //ok
			'text_dark'				=> '#ebebeb', //ok
			'text_link'				=> '#c2c0c4', //ok
			'text_hover'			=> '#54be73', //ok

			// Inverse colors
			'inverse_text'			=> '#ffffff', //ok
			'inverse_light'			=> '#e0e0e0',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#e5e5e5',
		
			// Input fields
			'input_text'			=> '#999999',
			'input_light'			=> '#aaaaaa',
			'input_dark'			=> '#d0d0d0',
			'input_bd_color'		=> '#909090',
			'input_bd_hover'		=> '#888888',
			'input_bg_color'		=> '#292a2e',
			'input_bg_hover'		=> '#505050',
		
			// Alternative blocks (submenu items, etc.)
			'alter_text'			=> '#969da3', //ok
			'alter_light'			=> '#ffffff', //ok
			'alter_dark'			=> '#ffffff', //ok
			'alter_link'			=> '#20c7ca',
			'alter_hover'			=> '#29fbff',
			'alter_bd_color'		=> '#909090',
			'alter_bd_hover'		=> '#888888',
			'alter_bg_color'		=> '#222326', //ok
			'alter_bg_hover'		=> '#1c1b1f', //1c1b1f
			)
		);


		/* Font slugs:
		h1 ... h6	- headers
		p			- plain text
		link		- links
		info		- info blocks (Posted 15 May, 2015 by John Doe)
		menu		- main menu
		submenu		- dropdown menus
		logo		- logo text
		button		- button's caption
		input		- input fields
		*/

		//Lora
		//Poppins

		// Add Custom fonts
		invetex_add_custom_font('h1', array(
			'title'			=> esc_html__('Heading 1', 'invetex'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '3.214em',
			'font-weight'	=> '500',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0.5em',
			'margin-bottom'	=> '0.35em'
			)
		);
		invetex_add_custom_font('h2', array(
			'title'			=> esc_html__('Heading 2', 'invetex'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '2.286em',
			'font-weight'	=> '500',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0.6667em',
			'margin-bottom'	=> '0.4em'
			)
		);
		invetex_add_custom_font('h3', array(
			'title'			=> esc_html__('Heading 3', 'invetex'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '1.857em',
			'font-weight'	=> '500',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0.6667em',
			'margin-bottom'	=> '0.6em'
			)
		);
		invetex_add_custom_font('h4', array(
			'title'			=> esc_html__('Heading 4', 'invetex'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '1.286em',
			'font-weight'	=> '500',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '1.2em',
			'margin-bottom'	=> '0.75em'
			)
		);
		invetex_add_custom_font('h5', array(
			'title'			=> esc_html__('Heading 5', 'invetex'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '1.143em',
			'font-weight'	=> '500',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '1.2em',
			'margin-bottom'	=> '0.85em'
			)
		);
		invetex_add_custom_font('h6', array(
			'title'			=> esc_html__('Heading 6', 'invetex'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '1.071em',
			'font-weight'	=> '500',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '1.25em',
			'margin-bottom'	=> '0.65em'
			)
		);
		invetex_add_custom_font('p', array(
			'title'			=> esc_html__('Text', 'invetex'),
			'description'	=> '',
			'font-family'	=> 'Poppins',
			'font-size' 	=> '14px',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.85em',
			'margin-top'	=> '',
			'margin-bottom'	=> '1em'
			)
		);
		invetex_add_custom_font('link', array(
			'title'			=> esc_html__('Links', 'invetex'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> ''
			)
		);
		invetex_add_custom_font('info', array(
			'title'			=> esc_html__('Post info', 'invetex'),
			'description'	=> '',
			'font-family'	=> 'Lora',
			'font-size' 	=> '0.929em',
			'font-weight'	=> '',
			'font-style'	=> 'i',
			'line-height'	=> '1.3em',
			'margin-top'	=> '',
			'margin-bottom'	=> '1em'
			)
		);
		invetex_add_custom_font('menu', array(
			'title'			=> esc_html__('Main menu items', 'invetex'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '0.929em',
			'font-weight'	=> '500',
			'font-style'	=> '',
			'line-height'	=> '1.28em',
			'margin-top'	=> '1.75em',
			'margin-bottom'	=> '1.75em'
			)
		);
		invetex_add_custom_font('submenu', array(
			'title'			=> esc_html__('Dropdown menu items', 'invetex'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.2857em',
			'margin-top'	=> '',
			'margin-bottom'	=> ''
			)
		);
		invetex_add_custom_font('logo', array(
			'title'			=> esc_html__('Logo', 'invetex'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '2.6em',
			'font-weight'	=> '600',
			'font-style'	=> '',
			'line-height'	=> '1em',
			'margin-top'	=> '3em',
			'margin-bottom'	=> '1.7em'
			)
		);
		invetex_add_custom_font('button', array(
			'title'			=> esc_html__('Buttons', 'invetex'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '0.786em',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.2857em'
			)
		);
		invetex_add_custom_font('input', array(
			'title'			=> esc_html__('Input fields', 'invetex'),
			'description'	=> '',
			'font-family'	=> 'Lora',
			'font-size' 	=> '0.929em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.5em'
			)
		);

	}
}



//------------------------------------------------------------------------------
// Theme fonts
//------------------------------------------------------------------------------

// Add theme fonts in the used fonts list
if (!function_exists('invetex_filter_theme_styles_used_fonts')) {
	//add_filter('invetex_filter_used_fonts', 'invetex_filter_theme_styles_used_fonts');
	function invetex_filter_theme_styles_used_fonts($theme_fonts) {
		$theme_fonts['Lora'] = 1;
		$theme_fonts['Poppins'] = 1;
		return $theme_fonts;
	}
}

// Add theme fonts (from Google fonts) in the main fonts list (if not present).
// To use custom font-face you not need add it into list in this function
// How to install custom @font-face fonts into the theme?
// All @font-face fonts are located in "theme_name/css/font-face/" folder in the separate subfolders for the each font. Subfolder name is a font-family name!
// Place full set of the font files (for each font style and weight) and css-file named stylesheet.css in the each subfolder.
// Create your @font-face kit by using Fontsquirrel @font-face Generator (http://www.fontsquirrel.com/fontface/generator)
// and then extract the font kit (with folder in the kit) into the "theme_name/css/font-face" folder to install
if (!function_exists('invetex_filter_theme_styles_list_fonts')) {
	//add_filter('invetex_filter_list_fonts', 'invetex_filter_theme_styles_list_fonts');
	function invetex_filter_theme_styles_list_fonts($list) {
		// Example:
		// if (!isset($list['Advent Pro'])) {
		//		$list['Advent Pro'] = array(
		//			'family' => 'sans-serif',																						// (required) font family
		//			'link'   => 'Advent+Pro:100,100italic,300,300italic,400,400italic,500,500italic,700,700italic,900,900italic',	// (optional) if you use Google font repository
		//			'css'    => invetex_get_file_url('/css/font-face/Advent-Pro/stylesheet.css')									// (optional) if you use custom font-face
		//			);
		// }
		if (!isset($list['Lora']))	  $list['Lora'] = array('family'=>'serif');
		if (!isset($list['Poppins'])) $list['Poppins'] = array('family'=>'sans-serif');
		return $list;
	}
}



//------------------------------------------------------------------------------
// Theme stylesheets
//------------------------------------------------------------------------------

// Add theme.less into list files for compilation
if (!function_exists('invetex_filter_theme_styles_compile_less')) {
	//add_filter('invetex_filter_compile_less', 'invetex_filter_theme_styles_compile_less');
	function invetex_filter_theme_styles_compile_less($files) {
		if (file_exists(invetex_get_file_dir('css/theme.less'))) {
		 	$files[] = invetex_get_file_dir('css/theme.less');
		}
		return $files;	
	}
}

// Add theme stylesheets
if (!function_exists('invetex_action_theme_styles_add_styles')) {
	//add_action('invetex_action_add_styles', 'invetex_action_theme_styles_add_styles');
	function invetex_action_theme_styles_add_styles() {
		// Add stylesheet files only if LESS supported
		if ( invetex_get_theme_setting('less_compiler') != 'no' ) {
			invetex_enqueue_style( 'invetex-theme-style', invetex_get_file_url('css/theme.css'), array(), null );
			wp_add_inline_style( 'invetex-theme-style', invetex_get_inline_css() );
		}
	}
}

// Add theme inline styles
if (!function_exists('invetex_filter_theme_styles_add_styles_inline')) {
	//add_filter('invetex_filter_add_styles_inline', 'invetex_filter_theme_styles_add_styles_inline');
	function invetex_filter_theme_styles_add_styles_inline($custom_style) {
		// Todo: add theme specific styles in the $custom_style to override
		//       rules from style.css and shortcodes.css
		// Example:
		//		$scheme = invetex_get_custom_option('body_scheme');
		//		if (empty($scheme)) $scheme = 'original';
		//		$clr = invetex_get_scheme_color('text_link');
		//		if (!empty($clr)) {
		// 			$custom_style .= '
		//				a,
		//				.bg_tint_light a,
		//				.top_panel .content .search_wrap.search_style_default .search_form_wrap .search_submit,
		//				.top_panel .content .search_wrap.search_style_default .search_icon,
		//				.search_results .post_more,
		//				.search_results .search_results_close {
		//					color:'.esc_attr($clr).';
		//				}
		//			';
		//		}

		// Submenu width
		$menu_width = invetex_get_theme_option('menu_width');
		if (!empty($menu_width)) {
			$custom_style .= "
				/* Submenu width */
				.menu_side_nav > li ul,
				.menu_main_nav > li ul {
					width: ".intval($menu_width)."px;
				}
				.menu_side_nav > li > ul ul,
				.menu_main_nav > li > ul ul {
					left:".intval($menu_width+4)."px;
				}
				.menu_side_nav > li > ul ul.submenu_left,
				.menu_main_nav > li > ul ul.submenu_left {
					left:-".intval($menu_width+1)."px;
				}
			";
		}
	
		// Logo height
		$logo_height = invetex_get_custom_option('logo_height');
		if (!empty($logo_height)) {
			$custom_style .= "
				/* Logo header height */
				.sidebar_outer_logo .logo_main,
				.top_panel_wrap .logo_main,
				.top_panel_wrap .logo_fixed {
					height:".intval($logo_height)."px;
				}
			";
		}
	
		// Logo top offset
		$logo_offset = invetex_get_custom_option('logo_offset');
		if (!empty($logo_offset)) {
			$custom_style .= "
				/* Logo header top offset */
				.top_panel_wrap .logo {
					margin-top:".intval($logo_offset)."px;
				}
			";
		}

		// Logo footer height
		$logo_height = invetex_get_theme_option('logo_footer_height');
		if (!empty($logo_height)) {
			$custom_style .= "
				/* Logo footer height */
				.contacts_wrap .logo img {
					height:".intval($logo_height)."px;
				}
			";
		}

		// Custom css from theme options
		$custom_style .= invetex_get_custom_option('custom_css');

		return $custom_style;	
	}
}


//------------------------------------------------------------------------------
// Theme scripts
//------------------------------------------------------------------------------

// Add theme scripts
if (!function_exists('invetex_action_theme_styles_add_scripts')) {
	//add_action('invetex_action_add_scripts', 'invetex_action_theme_styles_add_scripts');
	function invetex_action_theme_styles_add_scripts() {
		if (invetex_get_theme_option('show_theme_customizer') == 'yes' && file_exists(invetex_get_file_dir('js/theme.customizer.js')))
			invetex_enqueue_script( 'invetex-theme_styles-customizer-script', invetex_get_file_url('js/theme.customizer.js'), array(), null );
	}
}

// Add theme scripts inline
if (!function_exists('invetex_filter_theme_styles_localize_script')) {
	//add_filter('invetex_filter_localize_script',		'invetex_filter_theme_styles_localize_script');
	function invetex_filter_theme_styles_localize_script($vars) {
		if (empty($vars['theme_font']))
			$vars['theme_font'] = invetex_get_custom_font_settings('p', 'font-family');
		$vars['theme_color'] = invetex_get_scheme_color('text_dark');
		$vars['theme_bg_color'] = invetex_get_scheme_color('bg_color');
		return $vars;
	}
}
?>