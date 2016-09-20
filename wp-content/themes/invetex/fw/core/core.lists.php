<?php
/**
 * Invetex Framework: return lists
 *
 * @package invetex
 * @since invetex 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }



// Return styles list
if ( !function_exists( 'invetex_get_list_styles' ) ) {
	function invetex_get_list_styles($from=1, $to=2, $prepend_inherit=false) {
		$list = array();
		for ($i=$from; $i<=$to; $i++)
			$list[$i] = sprintf(esc_html__('Style %d', 'invetex'), $i);
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}


// Return list of the shortcodes margins
if ( !function_exists( 'invetex_get_list_margins' ) ) {
	function invetex_get_list_margins($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_margins'))=='') {
			$list = array(
				'null'		=> esc_html__('0 (No margin)',	'invetex'),
				'tiny'		=> esc_html__('Tiny',		'invetex'),
				'small'		=> esc_html__('Small',		'invetex'),
				'medium'	=> esc_html__('Medium',		'invetex'),
				'large'		=> esc_html__('Large',		'invetex'),
				'huge'		=> esc_html__('Huge',		'invetex'),
				'tiny-'		=> esc_html__('Tiny (negative)',	'invetex'),
				'small-'	=> esc_html__('Small (negative)',	'invetex'),
				'medium-'	=> esc_html__('Medium (negative)',	'invetex'),
				'large-'	=> esc_html__('Large (negative)',	'invetex'),
				'huge-'		=> esc_html__('Huge (negative)',	'invetex')
				);
			$list = apply_filters('invetex_filter_list_margins', $list);
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_margins', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}


// Return list of the line styles
if ( !function_exists( 'invetex_get_list_line_styles' ) ) {
	function invetex_get_list_line_styles($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_line_styles'))=='') {
			$list = array(
				'solid'	=> esc_html__('Solid', 'invetex'),
				'dashed'=> esc_html__('Dashed', 'invetex'),
				'dotted'=> esc_html__('Dotted', 'invetex'),
				'double'=> esc_html__('Double', 'invetex'),
				'image'	=> esc_html__('Image', 'invetex')
				);
			$list = apply_filters('invetex_filter_list_line_styles', $list);
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_line_styles', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}


// Return list of the animations
if ( !function_exists( 'invetex_get_list_animations' ) ) {
	function invetex_get_list_animations($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_animations'))=='') {
			$list = array(
				'none'			=> esc_html__('- None -',	'invetex'),
				'bounce'		=> esc_html__('Bounce',		'invetex'),
				'elastic'		=> esc_html__('Elastic',	'invetex'),
				'flash'			=> esc_html__('Flash',		'invetex'),
				'flip'			=> esc_html__('Flip',		'invetex'),
				'pulse'			=> esc_html__('Pulse',		'invetex'),
				'rubberBand'	=> esc_html__('Rubber Band','invetex'),
				'shake'			=> esc_html__('Shake',		'invetex'),
				'swing'			=> esc_html__('Swing',		'invetex'),
				'tada'			=> esc_html__('Tada',		'invetex'),
				'wobble'		=> esc_html__('Wobble',		'invetex')
				);
			$list = apply_filters('invetex_filter_list_animations', $list);
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_animations', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}


// Return list of the enter animations
if ( !function_exists( 'invetex_get_list_animations_in' ) ) {
	function invetex_get_list_animations_in($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_animations_in'))=='') {
			$list = array(
				'none'				=> esc_html__('- None -',			'invetex'),
				'bounceIn'			=> esc_html__('Bounce In',			'invetex'),
				'bounceInUp'		=> esc_html__('Bounce In Up',		'invetex'),
				'bounceInDown'		=> esc_html__('Bounce In Down',		'invetex'),
				'bounceInLeft'		=> esc_html__('Bounce In Left',		'invetex'),
				'bounceInRight'		=> esc_html__('Bounce In Right',	'invetex'),
				'elastic'			=> esc_html__('Elastic In',			'invetex'),
				'fadeIn'			=> esc_html__('Fade In',			'invetex'),
				'fadeInUp'			=> esc_html__('Fade In Up',			'invetex'),
				'fadeInUpSmall'		=> esc_html__('Fade In Up Small',	'invetex'),
				'fadeInUpBig'		=> esc_html__('Fade In Up Big',		'invetex'),
				'fadeInDown'		=> esc_html__('Fade In Down',		'invetex'),
				'fadeInDownBig'		=> esc_html__('Fade In Down Big',	'invetex'),
				'fadeInLeft'		=> esc_html__('Fade In Left',		'invetex'),
				'fadeInLeftBig'		=> esc_html__('Fade In Left Big',	'invetex'),
				'fadeInRight'		=> esc_html__('Fade In Right',		'invetex'),
				'fadeInRightBig'	=> esc_html__('Fade In Right Big',	'invetex'),
				'flipInX'			=> esc_html__('Flip In X',			'invetex'),
				'flipInY'			=> esc_html__('Flip In Y',			'invetex'),
				'lightSpeedIn'		=> esc_html__('Light Speed In',		'invetex'),
				'rotateIn'			=> esc_html__('Rotate In',			'invetex'),
				'rotateInUpLeft'	=> esc_html__('Rotate In Down Left','invetex'),
				'rotateInUpRight'	=> esc_html__('Rotate In Up Right',	'invetex'),
				'rotateInDownLeft'	=> esc_html__('Rotate In Up Left',	'invetex'),
				'rotateInDownRight'	=> esc_html__('Rotate In Down Right','invetex'),
				'rollIn'			=> esc_html__('Roll In',			'invetex'),
				'slideInUp'			=> esc_html__('Slide In Up',		'invetex'),
				'slideInDown'		=> esc_html__('Slide In Down',		'invetex'),
				'slideInLeft'		=> esc_html__('Slide In Left',		'invetex'),
				'slideInRight'		=> esc_html__('Slide In Right',		'invetex'),
				'wipeInLeftTop'		=> esc_html__('Wipe In Left Top',	'invetex'),
				'zoomIn'			=> esc_html__('Zoom In',			'invetex'),
				'zoomInUp'			=> esc_html__('Zoom In Up',			'invetex'),
				'zoomInDown'		=> esc_html__('Zoom In Down',		'invetex'),
				'zoomInLeft'		=> esc_html__('Zoom In Left',		'invetex'),
				'zoomInRight'		=> esc_html__('Zoom In Right',		'invetex')
				);
			$list = apply_filters('invetex_filter_list_animations_in', $list);
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_animations_in', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}


// Return list of the out animations
if ( !function_exists( 'invetex_get_list_animations_out' ) ) {
	function invetex_get_list_animations_out($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_animations_out'))=='') {
			$list = array(
				'none'				=> esc_html__('- None -',			'invetex'),
				'bounceOut'			=> esc_html__('Bounce Out',			'invetex'),
				'bounceOutUp'		=> esc_html__('Bounce Out Up',		'invetex'),
				'bounceOutDown'		=> esc_html__('Bounce Out Down',	'invetex'),
				'bounceOutLeft'		=> esc_html__('Bounce Out Left',	'invetex'),
				'bounceOutRight'	=> esc_html__('Bounce Out Right',	'invetex'),
				'fadeOut'			=> esc_html__('Fade Out',			'invetex'),
				'fadeOutUp'			=> esc_html__('Fade Out Up',		'invetex'),
				'fadeOutUpBig'		=> esc_html__('Fade Out Up Big',	'invetex'),
				'fadeOutDown'		=> esc_html__('Fade Out Down',		'invetex'),
				'fadeOutDownSmall'	=> esc_html__('Fade Out Down Small','invetex'),
				'fadeOutDownBig'	=> esc_html__('Fade Out Down Big',	'invetex'),
				'fadeOutLeft'		=> esc_html__('Fade Out Left',		'invetex'),
				'fadeOutLeftBig'	=> esc_html__('Fade Out Left Big',	'invetex'),
				'fadeOutRight'		=> esc_html__('Fade Out Right',		'invetex'),
				'fadeOutRightBig'	=> esc_html__('Fade Out Right Big',	'invetex'),
				'flipOutX'			=> esc_html__('Flip Out X',			'invetex'),
				'flipOutY'			=> esc_html__('Flip Out Y',			'invetex'),
				'hinge'				=> esc_html__('Hinge Out',			'invetex'),
				'lightSpeedOut'		=> esc_html__('Light Speed Out',	'invetex'),
				'rotateOut'			=> esc_html__('Rotate Out',			'invetex'),
				'rotateOutUpLeft'	=> esc_html__('Rotate Out Down Left','invetex'),
				'rotateOutUpRight'	=> esc_html__('Rotate Out Up Right','invetex'),
				'rotateOutDownLeft'	=> esc_html__('Rotate Out Up Left',	'invetex'),
				'rotateOutDownRight'=> esc_html__('Rotate Out Down Right','invetex'),
				'rollOut'			=> esc_html__('Roll Out',			'invetex'),
				'slideOutUp'		=> esc_html__('Slide Out Up',		'invetex'),
				'slideOutDown'		=> esc_html__('Slide Out Down',		'invetex'),
				'slideOutLeft'		=> esc_html__('Slide Out Left',		'invetex'),
				'slideOutRight'		=> esc_html__('Slide Out Right',	'invetex'),
				'zoomOut'			=> esc_html__('Zoom Out',			'invetex'),
				'zoomOutUp'			=> esc_html__('Zoom Out Up',		'invetex'),
				'zoomOutDown'		=> esc_html__('Zoom Out Down',		'invetex'),
				'zoomOutLeft'		=> esc_html__('Zoom Out Left',		'invetex'),
				'zoomOutRight'		=> esc_html__('Zoom Out Right',		'invetex')
				);
			$list = apply_filters('invetex_filter_list_animations_out', $list);
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_animations_out', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}

// Return classes list for the specified animation
if (!function_exists('invetex_get_animation_classes')) {
	function invetex_get_animation_classes($animation, $speed='normal', $loop='none') {
		// speed:	fast=0.5s | normal=1s | slow=2s
		// loop:	none | infinite
		return invetex_param_is_off($animation) ? '' : 'animated '.esc_attr($animation).' '.esc_attr($speed).(!invetex_param_is_off($loop) ? ' '.esc_attr($loop) : '');
	}
}


// Return list of the main menu hover effects
if ( !function_exists( 'invetex_get_list_menu_hovers' ) ) {
	function invetex_get_list_menu_hovers($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_menu_hovers'))=='') {
			$list = array(
				'fade'			=> esc_html__('Fade',		'invetex'),
				'slide_line'	=> esc_html__('Slide Line',	'invetex'),
				'slide_box'		=> esc_html__('Slide Box',	'invetex'),
				'zoom_line'		=> esc_html__('Zoom Line',	'invetex'),
				'path_line'		=> esc_html__('Path Line',	'invetex'),
				'roll_down'		=> esc_html__('Roll Down',	'invetex'),
				'color_line'	=> esc_html__('Color Line',	'invetex'),
				);
			$list = apply_filters('invetex_filter_list_menu_hovers', $list);
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_menu_hovers', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}


// Return list of the button's hover effects
if ( !function_exists( 'invetex_get_list_button_hovers' ) ) {
	function invetex_get_list_button_hovers($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_button_hovers'))=='') {
			$list = array(
				'default'		=> esc_html__('Default',			'invetex'),
				'fade'			=> esc_html__('Fade',				'invetex'),
				'slide_left'	=> esc_html__('Slide from Left',	'invetex'),
				'slide_top'		=> esc_html__('Slide from Top',		'invetex'),
				//'arrow'			=> esc_html__('Arrow',				'invetex'),
				);
			$list = apply_filters('invetex_filter_list_button_hovers', $list);
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_button_hovers', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}


// Return list of the input field's hover effects
if ( !function_exists( 'invetex_get_list_input_hovers' ) ) {
	function invetex_get_list_input_hovers($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_input_hovers'))=='') {
			$list = array(
				'default'	=> esc_html__('Default',	'invetex'),
				'accent'	=> esc_html__('Accented',	'invetex'),
				'path'		=> esc_html__('Path',		'invetex'),
				'jump'		=> esc_html__('Jump',		'invetex'),
				'underline'	=> esc_html__('Underline',	'invetex'),
				'iconed'	=> esc_html__('Iconed',		'invetex'),
				);
			$list = apply_filters('invetex_filter_list_input_hovers', $list);
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_input_hovers', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}


// Return list of the search field's styles
if ( !function_exists( 'invetex_get_list_search_styles' ) ) {
	function invetex_get_list_search_styles($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_search_styles'))=='') {
			$list = array(
				'default'	=> esc_html__('Default',	'invetex'),
				'fullscreen'=> esc_html__('Fullscreen',	'invetex'),
				//'slide'		=> esc_html__('Slide',		'invetex'),
				//'expand'	=> esc_html__('Expand',		'invetex'),
				);
			$list = apply_filters('invetex_filter_list_search_styles', $list);
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_search_styles', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}


// Return list of categories
if ( !function_exists( 'invetex_get_list_categories' ) ) {
	function invetex_get_list_categories($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_categories'))=='') {
			$list = array();
			$args = array(
				'type'                     => 'post',
				'child_of'                 => 0,
				'parent'                   => '',
				'orderby'                  => 'name',
				'order'                    => 'ASC',
				'hide_empty'               => 0,
				'hierarchical'             => 1,
				'exclude'                  => '',
				'include'                  => '',
				'number'                   => '',
				'taxonomy'                 => 'category',
				'pad_counts'               => false );
			$taxonomies = get_categories( $args );
			if (is_array($taxonomies) && count($taxonomies) > 0) {
				foreach ($taxonomies as $cat) {
					$list[$cat->term_id] = $cat->name;
				}
			}
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_categories', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}


// Return list of taxonomies
if ( !function_exists( 'invetex_get_list_terms' ) ) {
	function invetex_get_list_terms($prepend_inherit=false, $taxonomy='category') {
		if (($list = invetex_storage_get('list_taxonomies_'.($taxonomy)))=='') {
			$list = array();
			if ( is_array($taxonomy) || taxonomy_exists($taxonomy) ) {
				$terms = get_terms( $taxonomy, array(
					'child_of'                 => 0,
					'parent'                   => '',
					'orderby'                  => 'name',
					'order'                    => 'ASC',
					'hide_empty'               => 0,
					'hierarchical'             => 1,
					'exclude'                  => '',
					'include'                  => '',
					'number'                   => '',
					'taxonomy'                 => $taxonomy,
					'pad_counts'               => false
					)
				);
			} else {
				$terms = invetex_get_terms_by_taxonomy_from_db($taxonomy);
			}
			if (!is_wp_error( $terms ) && is_array($terms) && count($terms) > 0) {
				foreach ($terms as $cat) {
					$list[$cat->term_id] = $cat->name;	// . ($taxonomy!='category' ? ' /'.($cat->taxonomy).'/' : '');
				}
			}
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_taxonomies_'.($taxonomy), $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}

// Return list of post's types
if ( !function_exists( 'invetex_get_list_posts_types' ) ) {
	function invetex_get_list_posts_types($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_posts_types'))=='') {
			// Return only theme inheritance supported post types
			$list = apply_filters('invetex_filter_list_post_types', $list);
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_posts_types', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}


// Return list post items from any post type and taxonomy
if ( !function_exists( 'invetex_get_list_posts' ) ) {
	function invetex_get_list_posts($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'post',
			'post_status'		=> 'publish',
			'taxonomy'			=> 'category',
			'taxonomy_value'	=> '',
			'posts_per_page'	=> -1,
			'orderby'			=> 'post_date',
			'order'				=> 'desc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));

		$hash = 'list_posts_'.($opt['post_type']).'_'.($opt['taxonomy']).'_'.($opt['taxonomy_value']).'_'.($opt['orderby']).'_'.($opt['order']).'_'.($opt['return']).'_'.($opt['posts_per_page']);
		if (($list = invetex_storage_get($hash))=='') {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'invetex');
			$args = array(
				'post_type' => $opt['post_type'],
				'post_status' => $opt['post_status'],
				'posts_per_page' => $opt['posts_per_page'],
				'ignore_sticky_posts' => true,
				'orderby'	=> $opt['orderby'],
				'order'		=> $opt['order']
			);
			if (!empty($opt['taxonomy_value'])) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => $opt['taxonomy'],
						'field' => (int) $opt['taxonomy_value'] > 0 ? 'id' : 'slug',
						'terms' => $opt['taxonomy_value']
					)
				);
			}
			$posts = get_posts( $args );
			if (is_array($posts) && count($posts) > 0) {
				foreach ($posts as $post) {
					$list[$opt['return']=='id' ? $post->ID : $post->post_title] = $post->post_title;
				}
			}
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set($hash, $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}


// Return list pages
if ( !function_exists( 'invetex_get_list_pages' ) ) {
	function invetex_get_list_pages($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'page',
			'post_status'		=> 'publish',
			'posts_per_page'	=> -1,
			'orderby'			=> 'title',
			'order'				=> 'asc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));
		return invetex_get_list_posts($prepend_inherit, $opt);
	}
}


// Return list of registered users
if ( !function_exists( 'invetex_get_list_users' ) ) {
	function invetex_get_list_users($prepend_inherit=false, $roles=array('administrator', 'editor', 'author', 'contributor', 'shop_manager')) {
		if (($list = invetex_storage_get('list_users'))=='') {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'invetex');
			$args = array(
				'orderby'	=> 'display_name',
				'order'		=> 'ASC' );
			$users = get_users( $args );
			if (is_array($users) && count($users) > 0) {
				foreach ($users as $user) {
					$accept = true;
					if (is_array($user->roles)) {
						if (is_array($user->roles) && count($user->roles) > 0) {
							$accept = false;
							foreach ($user->roles as $role) {
								if (in_array($role, $roles)) {
									$accept = true;
									break;
								}
							}
						}
					}
					if ($accept) $list[$user->user_login] = $user->display_name;
				}
			}
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_users', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}


// Return slider engines list, prepended inherit (if need)
if ( !function_exists( 'invetex_get_list_sliders' ) ) {
	function invetex_get_list_sliders($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_sliders'))=='') {
			$list = array(
				'swiper' => esc_html__("Posts slider (Swiper)", 'invetex')
			);
			$list = apply_filters('invetex_filter_list_sliders', $list);
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_sliders', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}


// Return slider controls list, prepended inherit (if need)
if ( !function_exists( 'invetex_get_list_slider_controls' ) ) {
	function invetex_get_list_slider_controls($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_slider_controls'))=='') {
			$list = array(
				'no'		=> esc_html__('None', 'invetex'),
				'side'		=> esc_html__('Side', 'invetex'),
				'bottom'	=> esc_html__('Bottom', 'invetex'),
				'pagination'=> esc_html__('Pagination', 'invetex')
				);
			$list = apply_filters('invetex_filter_list_slider_controls', $list);
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_slider_controls', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}


// Return slider controls classes
if ( !function_exists( 'invetex_get_slider_controls_classes' ) ) {
	function invetex_get_slider_controls_classes($controls) {
		if (invetex_param_is_off($controls))	$classes = 'sc_slider_nopagination sc_slider_nocontrols';
		else if ($controls=='bottom')			$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_bottom';
		else if ($controls=='pagination')		$classes = 'sc_slider_pagination sc_slider_pagination_bottom sc_slider_nocontrols';
		else									$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_side';
		return $classes;
	}
}

// Return list with popup engines
if ( !function_exists( 'invetex_get_list_popup_engines' ) ) {
	function invetex_get_list_popup_engines($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_popup_engines'))=='') {
			$list = array(
				"pretty"	=> esc_html__("Pretty photo", 'invetex'),
				"magnific"	=> esc_html__("Magnific popup", 'invetex')
				);
			$list = apply_filters('invetex_filter_list_popup_engines', $list);
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_popup_engines', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}

// Return menus list, prepended inherit
if ( !function_exists( 'invetex_get_list_menus' ) ) {
	function invetex_get_list_menus($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_menus'))=='') {
			$list = array();
			$list['default'] = esc_html__("Default", 'invetex');
			$menus = wp_get_nav_menus();
			if (is_array($menus) && count($menus) > 0) {
				foreach ($menus as $menu) {
					$list[$menu->slug] = $menu->name;
				}
			}
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_menus', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}

// Return custom sidebars list, prepended inherit and main sidebars item (if need)
if ( !function_exists( 'invetex_get_list_sidebars' ) ) {
	function invetex_get_list_sidebars($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_sidebars'))=='') {
			if (($list = invetex_storage_get('registered_sidebars'))=='') $list = array();
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_sidebars', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}

// Return sidebars positions
if ( !function_exists( 'invetex_get_list_sidebars_positions' ) ) {
	function invetex_get_list_sidebars_positions($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_sidebars_positions'))=='') {
			$list = array(
				'none'  => esc_html__('Hide',  'invetex'),
				'left'  => esc_html__('Left',  'invetex'),
				'right' => esc_html__('Right', 'invetex')
				);
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_sidebars_positions', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}

// Return sidebars class
if ( !function_exists( 'invetex_get_sidebar_class' ) ) {
	function invetex_get_sidebar_class() {
		$sb_main = invetex_get_custom_option('show_sidebar_main');
		$sb_outer = invetex_get_custom_option('show_sidebar_outer');
		return (invetex_param_is_off($sb_main) ? 'sidebar_hide' : 'sidebar_show sidebar_'.($sb_main))
				. ' ' . (invetex_param_is_off($sb_outer) ? 'sidebar_outer_hide' : 'sidebar_outer_show sidebar_outer_'.($sb_outer));
	}
}

// Return body styles list, prepended inherit
if ( !function_exists( 'invetex_get_list_body_styles' ) ) {
	function invetex_get_list_body_styles($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_body_styles'))=='') {
			$list = array(
				'boxed'	=> esc_html__('Boxed',		'invetex'),
				'wide'	=> esc_html__('Wide',		'invetex')
				);
			if (invetex_get_theme_setting('allow_fullscreen')) {
				$list['fullwide']	= esc_html__('Fullwide',	'invetex');
				$list['fullscreen']	= esc_html__('Fullscreen',	'invetex');
			}
			$list = apply_filters('invetex_filter_list_body_styles', $list);
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_body_styles', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}

// Return css-themes list
if ( !function_exists( 'invetex_get_list_themes' ) ) {
	function invetex_get_list_themes($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_themes'))=='') {
			$list = invetex_get_list_files("css/themes");
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_themes', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}

// Return templates list, prepended inherit
if ( !function_exists( 'invetex_get_list_templates' ) ) {
	function invetex_get_list_templates($mode='') {
		if (($list = invetex_storage_get('list_templates_'.($mode)))=='') {
			$list = array();
			$tpl = invetex_storage_get('registered_templates');
			if (is_array($tpl) && count($tpl) > 0) {
				foreach ($tpl as $k=>$v) {
					if ($mode=='' || in_array($mode, explode(',', $v['mode'])))
						$list[$k] = !empty($v['icon']) 
									? $v['icon'] 
									: (!empty($v['title']) 
										? $v['title'] 
										: invetex_strtoproper($v['layout'])
										);
				}
			}
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_templates_'.($mode), $list);
		}
		return $list;
	}
}

// Return blog styles list, prepended inherit
if ( !function_exists( 'invetex_get_list_templates_blog' ) ) {
	function invetex_get_list_templates_blog($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_templates_blog'))=='') {
			$list = invetex_get_list_templates('blog');
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_templates_blog', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}

// Return blogger styles list, prepended inherit
if ( !function_exists( 'invetex_get_list_templates_blogger' ) ) {
	function invetex_get_list_templates_blogger($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_templates_blogger'))=='') {
			$list = invetex_array_merge(invetex_get_list_templates('blogger'), invetex_get_list_templates('blog'));
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_templates_blogger', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}

// Return single page styles list, prepended inherit
if ( !function_exists( 'invetex_get_list_templates_single' ) ) {
	function invetex_get_list_templates_single($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_templates_single'))=='') {
			$list = invetex_get_list_templates('single');
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_templates_single', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}

// Return header styles list, prepended inherit
if ( !function_exists( 'invetex_get_list_templates_header' ) ) {
	function invetex_get_list_templates_header($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_templates_header'))=='') {
			$list = invetex_get_list_templates('header');
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_templates_header', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}

// Return form styles list, prepended inherit
if ( !function_exists( 'invetex_get_list_templates_forms' ) ) {
	function invetex_get_list_templates_forms($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_templates_forms'))=='') {
			$list = invetex_get_list_templates('forms');
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_templates_forms', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}

// Return article styles list, prepended inherit
if ( !function_exists( 'invetex_get_list_article_styles' ) ) {
	function invetex_get_list_article_styles($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_article_styles'))=='') {
			$list = array(
				"boxed"   => esc_html__('Boxed', 'invetex'),
				"stretch" => esc_html__('Stretch', 'invetex')
				);
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_article_styles', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}

// Return post-formats filters list, prepended inherit
if ( !function_exists( 'invetex_get_list_post_formats_filters' ) ) {
	function invetex_get_list_post_formats_filters($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_post_formats_filters'))=='') {
			$list = array(
				"no"      => esc_html__('All posts', 'invetex'),
				"thumbs"  => esc_html__('With thumbs', 'invetex'),
				"reviews" => esc_html__('With reviews', 'invetex'),
				"video"   => esc_html__('With videos', 'invetex'),
				"audio"   => esc_html__('With audios', 'invetex'),
				"gallery" => esc_html__('With galleries', 'invetex')
				);
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_post_formats_filters', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}

// Return portfolio filters list, prepended inherit
if ( !function_exists( 'invetex_get_list_portfolio_filters' ) ) {
	function invetex_get_list_portfolio_filters($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_portfolio_filters'))=='') {
			$list = array(
				"hide"		=> esc_html__('Hide', 'invetex'),
				"tags"		=> esc_html__('Tags', 'invetex'),
				"categories"=> esc_html__('Categories', 'invetex')
				);
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_portfolio_filters', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}

// Return hover styles list, prepended inherit
if ( !function_exists( 'invetex_get_list_hovers' ) ) {
	function invetex_get_list_hovers($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_hovers'))=='') {
			$list = array();
			$list['circle effect1']  = esc_html__('Circle Effect 1',  'invetex');
			$list['circle effect2']  = esc_html__('Circle Effect 2',  'invetex');
			$list['circle effect3']  = esc_html__('Circle Effect 3',  'invetex');
			$list['circle effect4']  = esc_html__('Circle Effect 4',  'invetex');
			$list['circle effect5']  = esc_html__('Circle Effect 5',  'invetex');
			$list['circle effect6']  = esc_html__('Circle Effect 6',  'invetex');
			$list['circle effect7']  = esc_html__('Circle Effect 7',  'invetex');
			$list['circle effect8']  = esc_html__('Circle Effect 8',  'invetex');
			$list['circle effect9']  = esc_html__('Circle Effect 9',  'invetex');
			$list['circle effect10'] = esc_html__('Circle Effect 10',  'invetex');
			$list['circle effect11'] = esc_html__('Circle Effect 11',  'invetex');
			$list['circle effect12'] = esc_html__('Circle Effect 12',  'invetex');
			$list['circle effect13'] = esc_html__('Circle Effect 13',  'invetex');
			$list['circle effect14'] = esc_html__('Circle Effect 14',  'invetex');
			$list['circle effect15'] = esc_html__('Circle Effect 15',  'invetex');
			$list['circle effect16'] = esc_html__('Circle Effect 16',  'invetex');
			$list['circle effect17'] = esc_html__('Circle Effect 17',  'invetex');
			$list['circle effect18'] = esc_html__('Circle Effect 18',  'invetex');
			$list['circle effect19'] = esc_html__('Circle Effect 19',  'invetex');
			$list['circle effect20'] = esc_html__('Circle Effect 20',  'invetex');
			$list['square effect1']  = esc_html__('Square Effect 1',  'invetex');
			$list['square effect2']  = esc_html__('Square Effect 2',  'invetex');
			$list['square effect3']  = esc_html__('Square Effect 3',  'invetex');
			$list['square effect5']  = esc_html__('Square Effect 5',  'invetex');
			$list['square effect6']  = esc_html__('Square Effect 6',  'invetex');
			$list['square effect7']  = esc_html__('Square Effect 7',  'invetex');
			$list['square effect8']  = esc_html__('Square Effect 8',  'invetex');
			$list['square effect9']  = esc_html__('Square Effect 9',  'invetex');
			$list['square effect10'] = esc_html__('Square Effect 10',  'invetex');
			$list['square effect11'] = esc_html__('Square Effect 11',  'invetex');
			$list['square effect12'] = esc_html__('Square Effect 12',  'invetex');
			$list['square effect13'] = esc_html__('Square Effect 13',  'invetex');
			$list['square effect14'] = esc_html__('Square Effect 14',  'invetex');
			$list['square effect15'] = esc_html__('Square Effect 15',  'invetex');
			$list['square effect_dir']   = esc_html__('Square Effect Dir',   'invetex');
			$list['square effect_shift'] = esc_html__('Square Effect Shift', 'invetex');
			$list['square effect_book']  = esc_html__('Square Effect Book',  'invetex');
			$list['square effect_more']  = esc_html__('Square Effect More',  'invetex');
			$list['square effect_fade']  = esc_html__('Square Effect Fade',  'invetex');
			$list['square effect_pull']  = esc_html__('Square Effect Pull',  'invetex');
			$list['square effect_slide'] = esc_html__('Square Effect Slide', 'invetex');
			$list['square effect_border'] = esc_html__('Square Effect Border', 'invetex');
			$list = apply_filters('invetex_filter_portfolio_hovers', $list);
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_hovers', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}


// Return list of the blog counters
if ( !function_exists( 'invetex_get_list_blog_counters' ) ) {
	function invetex_get_list_blog_counters($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_blog_counters'))=='') {
			$list = array(
				'views'		=> esc_html__('Views', 'invetex'),
				'likes'		=> esc_html__('Likes', 'invetex'),
				'rating'	=> esc_html__('Rating', 'invetex'),
				'comments'	=> esc_html__('Comments', 'invetex')
				);
			$list = apply_filters('invetex_filter_list_blog_counters', $list);
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_blog_counters', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}

// Return list of the item sizes for the portfolio alter style, prepended inherit
if ( !function_exists( 'invetex_get_list_alter_sizes' ) ) {
	function invetex_get_list_alter_sizes($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_alter_sizes'))=='') {
			$list = array(
					'1_1' => esc_html__('1x1', 'invetex'),
					'1_2' => esc_html__('1x2', 'invetex'),
					'2_1' => esc_html__('2x1', 'invetex'),
					'2_2' => esc_html__('2x2', 'invetex'),
					'1_3' => esc_html__('1x3', 'invetex'),
					'2_3' => esc_html__('2x3', 'invetex'),
					'3_1' => esc_html__('3x1', 'invetex'),
					'3_2' => esc_html__('3x2', 'invetex'),
					'3_3' => esc_html__('3x3', 'invetex')
					);
			$list = apply_filters('invetex_filter_portfolio_alter_sizes', $list);
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_alter_sizes', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}

// Return extended hover directions list, prepended inherit
if ( !function_exists( 'invetex_get_list_hovers_directions' ) ) {
	function invetex_get_list_hovers_directions($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_hovers_directions'))=='') {
			$list = array(
				'left_to_right' => esc_html__('Left to Right',  'invetex'),
				'right_to_left' => esc_html__('Right to Left',  'invetex'),
				'top_to_bottom' => esc_html__('Top to Bottom',  'invetex'),
				'bottom_to_top' => esc_html__('Bottom to Top',  'invetex'),
				'scale_up'      => esc_html__('Scale Up',  'invetex'),
				'scale_down'    => esc_html__('Scale Down',  'invetex'),
				'scale_down_up' => esc_html__('Scale Down-Up',  'invetex'),
				'from_left_and_right' => esc_html__('From Left and Right',  'invetex'),
				'from_top_and_bottom' => esc_html__('From Top and Bottom',  'invetex')
			);
			$list = apply_filters('invetex_filter_portfolio_hovers_directions', $list);
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_hovers_directions', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}


// Return list of the label positions in the custom forms
if ( !function_exists( 'invetex_get_list_label_positions' ) ) {
	function invetex_get_list_label_positions($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_label_positions'))=='') {
			$list = array(
				'top'		=> esc_html__('Top',		'invetex'),
				'bottom'	=> esc_html__('Bottom',		'invetex'),
				'left'		=> esc_html__('Left',		'invetex'),
				'over'		=> esc_html__('Over',		'invetex')
			);
			$list = apply_filters('invetex_filter_label_positions', $list);
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_label_positions', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}


// Return list of the bg image positions
if ( !function_exists( 'invetex_get_list_bg_image_positions' ) ) {
	function invetex_get_list_bg_image_positions($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_bg_image_positions'))=='') {
			$list = array(
				'left top'	   => esc_html__('Left Top', 'invetex'),
				'center top'   => esc_html__("Center Top", 'invetex'),
				'right top'    => esc_html__("Right Top", 'invetex'),
				'left center'  => esc_html__("Left Center", 'invetex'),
				'center center'=> esc_html__("Center Center", 'invetex'),
				'right center' => esc_html__("Right Center", 'invetex'),
				'left bottom'  => esc_html__("Left Bottom", 'invetex'),
				'center bottom'=> esc_html__("Center Bottom", 'invetex'),
				'right bottom' => esc_html__("Right Bottom", 'invetex')
			);
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_bg_image_positions', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}


// Return list of the bg image repeat
if ( !function_exists( 'invetex_get_list_bg_image_repeats' ) ) {
	function invetex_get_list_bg_image_repeats($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_bg_image_repeats'))=='') {
			$list = array(
				'repeat'	=> esc_html__('Repeat', 'invetex'),
				'repeat-x'	=> esc_html__('Repeat X', 'invetex'),
				'repeat-y'	=> esc_html__('Repeat Y', 'invetex'),
				'no-repeat'	=> esc_html__('No Repeat', 'invetex')
			);
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_bg_image_repeats', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}


// Return list of the bg image attachment
if ( !function_exists( 'invetex_get_list_bg_image_attachments' ) ) {
	function invetex_get_list_bg_image_attachments($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_bg_image_attachments'))=='') {
			$list = array(
				'scroll'	=> esc_html__('Scroll', 'invetex'),
				'fixed'		=> esc_html__('Fixed', 'invetex'),
				'local'		=> esc_html__('Local', 'invetex')
			);
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_bg_image_attachments', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}


// Return list of the bg tints
if ( !function_exists( 'invetex_get_list_bg_tints' ) ) {
	function invetex_get_list_bg_tints($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_bg_tints'))=='') {
			$list = array(
				'white'	=> esc_html__('White', 'invetex'),
				'light'	=> esc_html__('Light', 'invetex'),
				'dark'	=> esc_html__('Dark', 'invetex')
			);
			$list = apply_filters('invetex_filter_bg_tints', $list);
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_bg_tints', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}

// Return custom fields types list, prepended inherit
if ( !function_exists( 'invetex_get_list_field_types' ) ) {
	function invetex_get_list_field_types($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_field_types'))=='') {
			$list = array(
				'text'     => esc_html__('Text',  'invetex'),
				'textarea' => esc_html__('Text Area','invetex'),
				'password' => esc_html__('Password',  'invetex'),
				'radio'    => esc_html__('Radio',  'invetex'),
				'checkbox' => esc_html__('Checkbox',  'invetex'),
				'select'   => esc_html__('Select',  'invetex'),
				'date'     => esc_html__('Date','invetex'),
				'time'     => esc_html__('Time','invetex'),
				'button'   => esc_html__('Button','invetex')
			);
			$list = apply_filters('invetex_filter_field_types', $list);
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_field_types', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}

// Return Google map styles
if ( !function_exists( 'invetex_get_list_googlemap_styles' ) ) {
	function invetex_get_list_googlemap_styles($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_googlemap_styles'))=='') {
			$list = array(
				'default' => esc_html__('Default', 'invetex')
			);
			$list = apply_filters('invetex_filter_googlemap_styles', $list);
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_googlemap_styles', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}

// Return iconed classes list
if ( !function_exists( 'invetex_get_list_icons' ) ) {
	function invetex_get_list_icons($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_icons'))=='') {
			$list = invetex_parse_icons_classes(invetex_get_file_dir("css/fontello/css/fontello-codes.css"));
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_icons', $list);
		}
		return $prepend_inherit ? array_merge(array('inherit'), $list) : $list;
	}
}

// Return socials list
if ( !function_exists( 'invetex_get_list_socials' ) ) {
	function invetex_get_list_socials($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_socials'))=='') {
			$list = invetex_get_list_files("images/socials", "png");
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_socials', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}

// Return flags list
if ( !function_exists( 'invetex_get_list_flags' ) ) {
	function invetex_get_list_flags($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_flags'))=='') {
			$list = invetex_get_list_files("images/flags", "png");
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_flags', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}

// Return list with 'Yes' and 'No' items
if ( !function_exists( 'invetex_get_list_yesno' ) ) {
	function invetex_get_list_yesno($prepend_inherit=false) {
		$list = array(
			'yes' => esc_html__("Yes", 'invetex'),
			'no'  => esc_html__("No", 'invetex')
		);
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}

// Return list with 'On' and 'Of' items
if ( !function_exists( 'invetex_get_list_onoff' ) ) {
	function invetex_get_list_onoff($prepend_inherit=false) {
		$list = array(
			"on" => esc_html__("On", 'invetex'),
			"off" => esc_html__("Off", 'invetex')
		);
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}

// Return list with 'Show' and 'Hide' items
if ( !function_exists( 'invetex_get_list_showhide' ) ) {
	function invetex_get_list_showhide($prepend_inherit=false) {
		$list = array(
			"show" => esc_html__("Show", 'invetex'),
			"hide" => esc_html__("Hide", 'invetex')
		);
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}

// Return list with 'Ascending' and 'Descending' items
if ( !function_exists( 'invetex_get_list_orderings' ) ) {
	function invetex_get_list_orderings($prepend_inherit=false) {
		$list = array(
			"asc" => esc_html__("Ascending", 'invetex'),
			"desc" => esc_html__("Descending", 'invetex')
		);
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}

// Return list with 'Horizontal' and 'Vertical' items
if ( !function_exists( 'invetex_get_list_directions' ) ) {
	function invetex_get_list_directions($prepend_inherit=false) {
		$list = array(
			"horizontal" => esc_html__("Horizontal", 'invetex'),
			"vertical" => esc_html__("Vertical", 'invetex')
		);
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}

// Return list with item's shapes
if ( !function_exists( 'invetex_get_list_shapes' ) ) {
	function invetex_get_list_shapes($prepend_inherit=false) {
		$list = array(
			"round"  => esc_html__("Round", 'invetex'),
			"square" => esc_html__("Square", 'invetex')
		);
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}

// Return list with item's sizes
if ( !function_exists( 'invetex_get_list_sizes' ) ) {
	function invetex_get_list_sizes($prepend_inherit=false) {
		$list = array(
			"tiny"   => esc_html__("Tiny", 'invetex'),
			"small"  => esc_html__("Small", 'invetex'),
			"medium" => esc_html__("Medium", 'invetex'),
			"large"  => esc_html__("Large", 'invetex')
		);
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}

// Return list with slider (scroll) controls positions
if ( !function_exists( 'invetex_get_list_controls' ) ) {
	function invetex_get_list_controls($prepend_inherit=false) {
		$list = array(
			"hide" => esc_html__("Hide", 'invetex'),
			"side" => esc_html__("Side", 'invetex'),
			"bottom" => esc_html__("Bottom", 'invetex')
		);
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}

// Return list with float items
if ( !function_exists( 'invetex_get_list_floats' ) ) {
	function invetex_get_list_floats($prepend_inherit=false) {
		$list = array(
			"none" => esc_html__("None", 'invetex'),
			"left" => esc_html__("Float Left", 'invetex'),
			"right" => esc_html__("Float Right", 'invetex')
		);
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}

// Return list with alignment items
if ( !function_exists( 'invetex_get_list_alignments' ) ) {
	function invetex_get_list_alignments($justify=false, $prepend_inherit=false) {
		$list = array(
			"none" => esc_html__("None", 'invetex'),
			"left" => esc_html__("Left", 'invetex'),
			"center" => esc_html__("Center", 'invetex'),
			"right" => esc_html__("Right", 'invetex')
		);
		if ($justify) $list["justify"] = esc_html__("Justify", 'invetex');
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}

// Return list with horizontal positions
if ( !function_exists( 'invetex_get_list_hpos' ) ) {
	function invetex_get_list_hpos($prepend_inherit=false, $center=false) {
		$list = array();
		$list['left'] = esc_html__("Left", 'invetex');
		if ($center) $list['center'] = esc_html__("Center", 'invetex');
		$list['right'] = esc_html__("Right", 'invetex');
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}

// Return list with vertical positions
if ( !function_exists( 'invetex_get_list_vpos' ) ) {
	function invetex_get_list_vpos($prepend_inherit=false, $center=false) {
		$list = array();
		$list['top'] = esc_html__("Top", 'invetex');
		if ($center) $list['center'] = esc_html__("Center", 'invetex');
		$list['bottom'] = esc_html__("Bottom", 'invetex');
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}

// Return sorting list items
if ( !function_exists( 'invetex_get_list_sortings' ) ) {
	function invetex_get_list_sortings($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_sortings'))=='') {
			$list = array(
				"date" => esc_html__("Date", 'invetex'),
				"title" => esc_html__("Alphabetically", 'invetex'),
				"views" => esc_html__("Popular (views count)", 'invetex'),
				"comments" => esc_html__("Most commented (comments count)", 'invetex'),
				"author_rating" => esc_html__("Author rating", 'invetex'),
				"users_rating" => esc_html__("Visitors (users) rating", 'invetex'),
				"random" => esc_html__("Random", 'invetex')
			);
			$list = apply_filters('invetex_filter_list_sortings', $list);
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_sortings', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}

// Return list with columns widths
if ( !function_exists( 'invetex_get_list_columns' ) ) {
	function invetex_get_list_columns($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_columns'))=='') {
			$list = array(
				"none" => esc_html__("None", 'invetex'),
				"1_1" => esc_html__("100%", 'invetex'),
				"1_2" => esc_html__("1/2", 'invetex'),
				"1_3" => esc_html__("1/3", 'invetex'),
				"2_3" => esc_html__("2/3", 'invetex'),
				"1_4" => esc_html__("1/4", 'invetex'),
				"3_4" => esc_html__("3/4", 'invetex'),
				"1_5" => esc_html__("1/5", 'invetex'),
				"2_5" => esc_html__("2/5", 'invetex'),
				"3_5" => esc_html__("3/5", 'invetex'),
				"4_5" => esc_html__("4/5", 'invetex'),
				"1_6" => esc_html__("1/6", 'invetex'),
				"5_6" => esc_html__("5/6", 'invetex'),
				"1_7" => esc_html__("1/7", 'invetex'),
				"2_7" => esc_html__("2/7", 'invetex'),
				"3_7" => esc_html__("3/7", 'invetex'),
				"4_7" => esc_html__("4/7", 'invetex'),
				"5_7" => esc_html__("5/7", 'invetex'),
				"6_7" => esc_html__("6/7", 'invetex'),
				"1_8" => esc_html__("1/8", 'invetex'),
				"3_8" => esc_html__("3/8", 'invetex'),
				"5_8" => esc_html__("5/8", 'invetex'),
				"7_8" => esc_html__("7/8", 'invetex'),
				"1_9" => esc_html__("1/9", 'invetex'),
				"2_9" => esc_html__("2/9", 'invetex'),
				"4_9" => esc_html__("4/9", 'invetex'),
				"5_9" => esc_html__("5/9", 'invetex'),
				"7_9" => esc_html__("7/9", 'invetex'),
				"8_9" => esc_html__("8/9", 'invetex'),
				"1_10"=> esc_html__("1/10", 'invetex'),
				"3_10"=> esc_html__("3/10", 'invetex'),
				"7_10"=> esc_html__("7/10", 'invetex'),
				"9_10"=> esc_html__("9/10", 'invetex'),
				"1_11"=> esc_html__("1/11", 'invetex'),
				"2_11"=> esc_html__("2/11", 'invetex'),
				"3_11"=> esc_html__("3/11", 'invetex'),
				"4_11"=> esc_html__("4/11", 'invetex'),
				"5_11"=> esc_html__("5/11", 'invetex'),
				"6_11"=> esc_html__("6/11", 'invetex'),
				"7_11"=> esc_html__("7/11", 'invetex'),
				"8_11"=> esc_html__("8/11", 'invetex'),
				"9_11"=> esc_html__("9/11", 'invetex'),
				"10_11"=> esc_html__("10/11", 'invetex'),
				"1_12"=> esc_html__("1/12", 'invetex'),
				"5_12"=> esc_html__("5/12", 'invetex'),
				"7_12"=> esc_html__("7/12", 'invetex'),
				"10_12"=> esc_html__("10/12", 'invetex'),
				"11_12"=> esc_html__("11/12", 'invetex')
			);
			$list = apply_filters('invetex_filter_list_columns', $list);
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_columns', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}

// Return list of locations for the dedicated content
if ( !function_exists( 'invetex_get_list_dedicated_locations' ) ) {
	function invetex_get_list_dedicated_locations($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_dedicated_locations'))=='') {
			$list = array(
				"default" => esc_html__('As in the post defined', 'invetex'),
				"center"  => esc_html__('Above the text of the post', 'invetex'),
				"left"    => esc_html__('To the left the text of the post', 'invetex'),
				"right"   => esc_html__('To the right the text of the post', 'invetex'),
				"alter"   => esc_html__('Alternates for each post', 'invetex')
			);
			$list = apply_filters('invetex_filter_list_dedicated_locations', $list);
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_dedicated_locations', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}

// Return post-format name
if ( !function_exists( 'invetex_get_post_format_name' ) ) {
	function invetex_get_post_format_name($format, $single=true) {
		$name = '';
		if ($format=='gallery')		$name = $single ? esc_html__('gallery', 'invetex') : esc_html__('galleries', 'invetex');
		else if ($format=='video')	$name = $single ? esc_html__('video', 'invetex') : esc_html__('videos', 'invetex');
		else if ($format=='audio')	$name = $single ? esc_html__('audio', 'invetex') : esc_html__('audios', 'invetex');
		else if ($format=='image')	$name = $single ? esc_html__('image', 'invetex') : esc_html__('images', 'invetex');
		else if ($format=='quote')	$name = $single ? esc_html__('quote', 'invetex') : esc_html__('quotes', 'invetex');
		else if ($format=='link')	$name = $single ? esc_html__('link', 'invetex') : esc_html__('links', 'invetex');
		else if ($format=='status')	$name = $single ? esc_html__('status', 'invetex') : esc_html__('statuses', 'invetex');
		else if ($format=='aside')	$name = $single ? esc_html__('aside', 'invetex') : esc_html__('asides', 'invetex');
		else if ($format=='chat')	$name = $single ? esc_html__('chat', 'invetex') : esc_html__('chats', 'invetex');
		else						$name = $single ? esc_html__('standard', 'invetex') : esc_html__('standards', 'invetex');
		return apply_filters('invetex_filter_list_post_format_name', $name, $format);
	}
}

// Return post-format icon name (from Fontello library)
if ( !function_exists( 'invetex_get_post_format_icon' ) ) {
	function invetex_get_post_format_icon($format) {
		$icon = 'icon-';
		if ($format=='gallery')		$icon .= 'pictures';
		else if ($format=='video')	$icon .= 'video';
		else if ($format=='audio')	$icon .= 'note';
		else if ($format=='image')	$icon .= 'picture';
		else if ($format=='quote')	$icon .= 'quote';
		else if ($format=='link')	$icon .= 'link';
		else if ($format=='status')	$icon .= 'comment';
		else if ($format=='aside')	$icon .= 'doc-text';
		else if ($format=='chat')	$icon .= 'chat';
		else						$icon .= 'book-open';
		return apply_filters('invetex_filter_list_post_format_icon', $icon, $format);
	}
}

// Return fonts styles list, prepended inherit
if ( !function_exists( 'invetex_get_list_fonts_styles' ) ) {
	function invetex_get_list_fonts_styles($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_fonts_styles'))=='') {
			$list = array(
				'i' => esc_html__('I','invetex'),
				'u' => esc_html__('U', 'invetex')
			);
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_fonts_styles', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}

// Return Google fonts list
if ( !function_exists( 'invetex_get_list_fonts' ) ) {
	function invetex_get_list_fonts($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_fonts'))=='') {
			$list = array();
			$list = invetex_array_merge($list, invetex_get_list_font_faces());
			// Google and custom fonts list:
			//$list['Advent Pro'] = array(
			//		'family'=>'sans-serif',																						// (required) font family
			//		'link'=>'Advent+Pro:100,100italic,300,300italic,400,400italic,500,500italic,700,700italic,900,900italic',	// (optional) if you use Google font repository
			//		'css'=>invetex_get_file_url('/css/font-face/Advent-Pro/stylesheet.css')									// (optional) if you use custom font-face
			//		);
			$list = invetex_array_merge($list, array(
				'Advent Pro' => array('family'=>'sans-serif'),
				'Alegreya Sans' => array('family'=>'sans-serif'),
				'Arimo' => array('family'=>'sans-serif'),
				'Asap' => array('family'=>'sans-serif'),
				'Averia Sans Libre' => array('family'=>'cursive'),
				'Averia Serif Libre' => array('family'=>'cursive'),
				'Bree Serif' => array('family'=>'serif',),
				'Cabin' => array('family'=>'sans-serif'),
				'Cabin Condensed' => array('family'=>'sans-serif'),
				'Caudex' => array('family'=>'serif'),
				'Comfortaa' => array('family'=>'cursive'),
				'Cousine' => array('family'=>'sans-serif'),
				'Crimson Text' => array('family'=>'serif'),
				'Cuprum' => array('family'=>'sans-serif'),
				'Dosis' => array('family'=>'sans-serif'),
				'Economica' => array('family'=>'sans-serif'),
				'Exo' => array('family'=>'sans-serif'),
				'Expletus Sans' => array('family'=>'cursive'),
				'Karla' => array('family'=>'sans-serif'),
				'Lato' => array('family'=>'sans-serif'),
				'Lekton' => array('family'=>'sans-serif'),
				'Lobster Two' => array('family'=>'cursive'),
				'Maven Pro' => array('family'=>'sans-serif'),
				'Merriweather' => array('family'=>'serif'),
				'Montserrat' => array('family'=>'sans-serif'),
				'Neuton' => array('family'=>'serif'),
				'Noticia Text' => array('family'=>'serif'),
				'Old Standard TT' => array('family'=>'serif'),
				'Open Sans' => array('family'=>'sans-serif'),
				'Orbitron' => array('family'=>'sans-serif'),
				'Oswald' => array('family'=>'sans-serif'),
				'Overlock' => array('family'=>'cursive'),
				'Oxygen' => array('family'=>'sans-serif'),
				'Philosopher' => array('family'=>'serif'),
				'PT Serif' => array('family'=>'serif'),
				'Puritan' => array('family'=>'sans-serif'),
				'Poppins' => array('family'=>'sans-serif'),
				'Lora' => array('family'=>'serif'),
				'Raleway' => array('family'=>'sans-serif'),
				'Roboto' => array('family'=>'sans-serif'),
				'Roboto Slab' => array('family'=>'sans-serif'),
				'Roboto Condensed' => array('family'=>'sans-serif'),
				'Rosario' => array('family'=>'sans-serif'),
				'Share' => array('family'=>'cursive'),
				'Signika' => array('family'=>'sans-serif'),
				'Signika Negative' => array('family'=>'sans-serif'),
				'Source Sans Pro' => array('family'=>'sans-serif'),
				'Tinos' => array('family'=>'serif'),
				'Ubuntu' => array('family'=>'sans-serif'),
				'Vollkorn' => array('family'=>'serif')
				)
			);
			$list = apply_filters('invetex_filter_list_fonts', $list);
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_fonts', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}

// Return Custom font-face list
if ( !function_exists( 'invetex_get_list_font_faces' ) ) {
	function invetex_get_list_font_faces($prepend_inherit=false) {
		static $list = false;
		if (is_array($list)) return $list;
		$list = array();
		$dir = invetex_get_folder_dir("css/font-face");
		if ( is_dir($dir) ) {
			$hdir = @ opendir( $dir );
			if ( $hdir ) {
				while (($file = readdir( $hdir ) ) !== false ) {
					$pi = pathinfo( ($dir) . '/' . ($file) );
					if ( substr($file, 0, 1) == '.' || ! is_dir( ($dir) . '/' . ($file) ) )
						continue;
					$css = file_exists( ($dir) . '/' . ($file) . '/' . ($file) . '.css' ) 
						? invetex_get_folder_url("css/font-face/".($file).'/'.($file).'.css')
						: (file_exists( ($dir) . '/' . ($file) . '/stylesheet.css' ) 
							? invetex_get_folder_url("css/font-face/".($file).'/stylesheet.css')
							: '');
					if ($css != '')
						$list[$file] = array('css' => $css);
				}
				@closedir( $hdir );
			}
		}
		return $list;
	}
}
?>