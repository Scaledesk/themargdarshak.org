<?php
/**
 * Invetex Framework: Testimonial support
 *
 * @package	invetex
 * @since	invetex 1.0
 */

// Theme init
if (!function_exists('invetex_testimonial_theme_setup')) {
	add_action( 'invetex_action_before_init_theme', 'invetex_testimonial_theme_setup', 1 );
	function invetex_testimonial_theme_setup() {
	
		// Add item in the admin menu
		add_action('add_meta_boxes',		'invetex_testimonial_add_meta_box');

		// Save data from meta box
		add_action('save_post',				'invetex_testimonial_save_data');

		// Register shortcodes [trx_testimonials] and [trx_testimonials_item]
		add_action('invetex_action_shortcodes_list',		'invetex_testimonials_reg_shortcodes');
		if (function_exists('invetex_exists_visual_composer') && invetex_exists_visual_composer())
			add_action('invetex_action_shortcodes_list_vc','invetex_testimonials_reg_shortcodes_vc');

		// Meta box fields
		invetex_storage_set('testimonial_meta_box', array(
			'id' => 'testimonial-meta-box',
			'title' => esc_html__('Testimonial Details', 'invetex'),
			'page' => 'testimonial',
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
				"testimonial_author" => array(
					"title" => esc_html__('Testimonial author',  'invetex'),
					"desc" => wp_kses_data( __("Name of the testimonial's author", 'invetex') ),
					"class" => "testimonial_author",
					"std" => "",
					"type" => "text"),
				"testimonial_position" => array(
					"title" => esc_html__("Author's position",  'invetex'),
					"desc" => wp_kses_data( __("Position of the testimonial's author", 'invetex') ),
					"class" => "testimonial_author",
					"std" => "",
					"type" => "text"),
				"testimonial_email" => array(
					"title" => esc_html__("Author's e-mail",  'invetex'),
					"desc" => wp_kses_data( __("E-mail of the testimonial's author - need to take Gravatar (if registered)", 'invetex') ),
					"class" => "testimonial_email",
					"std" => "",
					"type" => "text"),
				"testimonial_link" => array(
					"title" => esc_html__('Testimonial link',  'invetex'),
					"desc" => wp_kses_data( __("URL of the testimonial source or author profile page", 'invetex') ),
					"class" => "testimonial_link",
					"std" => "",
					"type" => "text")
				)
			)
		);
		
		// Add supported data types
		invetex_theme_support_pt('testimonial');
		invetex_theme_support_tx('testimonial_group');
		
	}
}


// Add meta box
if (!function_exists('invetex_testimonial_add_meta_box')) {
	//add_action('add_meta_boxes', 'invetex_testimonial_add_meta_box');
	function invetex_testimonial_add_meta_box() {
		$mb = invetex_storage_get('testimonial_meta_box');
		add_meta_box($mb['id'], $mb['title'], 'invetex_testimonial_show_meta_box', $mb['page'], $mb['context'], $mb['priority']);
	}
}

// Callback function to show fields in meta box
if (!function_exists('invetex_testimonial_show_meta_box')) {
	function invetex_testimonial_show_meta_box() {
		global $post;

		// Use nonce for verification
		echo '<input type="hidden" name="meta_box_testimonial_nonce" value="'.esc_attr(wp_create_nonce(admin_url())).'" />';
		
		$data = get_post_meta($post->ID, invetex_storage_get('options_prefix').'_testimonial_data', true);
	
		$fields = invetex_storage_get_array('testimonial_meta_box', 'fields');
		?>
		<table class="testimonial_area">
		<?php
		if (is_array($fields) && count($fields) > 0) {
			foreach ($fields as $id=>$field) { 
				$meta = isset($data[$id]) ? $data[$id] : '';
				?>
				<tr class="testimonial_field <?php echo esc_attr($field['class']); ?>" valign="top">
					<td><label for="<?php echo esc_attr($id); ?>"><?php echo esc_attr($field['title']); ?></label></td>
					<td><input type="text" name="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($meta); ?>" size="30" />
						<br><small><?php echo esc_attr($field['desc']); ?></small></td>
				</tr>
				<?php
			}
		}
		?>
		</table>
		<?php
	}
}


// Save data from meta box
if (!function_exists('invetex_testimonial_save_data')) {
	//add_action('save_post', 'invetex_testimonial_save_data');
	function invetex_testimonial_save_data($post_id) {
		// verify nonce
		if ( !wp_verify_nonce( invetex_get_value_gp('meta_box_testimonial_nonce'), admin_url() ) )
			return $post_id;

		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}

		// check permissions
		if ($_POST['post_type']!='testimonial' || !current_user_can('edit_post', $post_id)) {
			return $post_id;
		}

		$data = array();

		$fields = invetex_storage_get_array('testimonial_meta_box', 'fields');

		// Post type specific data handling
		if (is_array($fields) && count($fields) > 0) {
			foreach ($fields as $id=>$field) { 
				if (isset($_POST[$id])) 
					$data[$id] = stripslashes($_POST[$id]);
			}
		}

		update_post_meta($post_id, invetex_storage_get('options_prefix').'_testimonial_data', $data);
	}
}






// ---------------------------------- [trx_testimonials] ---------------------------------------

/*
[trx_testimonials id="unique_id" style="1|2|3"]
	[trx_testimonials_item user="user_login"]Testimonials text[/trx_testimonials_item]
	[trx_testimonials_item email="" name="" position="" photo="photo_url"]Testimonials text[/trx_testimonials]
[/trx_testimonials]
*/

if (!function_exists('invetex_sc_testimonials')) {
	function invetex_sc_testimonials($atts, $content=null){	
		if (invetex_in_shortcode_blogger()) return '';
		extract(invetex_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "testimonials-1",
			"columns" => 1,
			"slider" => "yes",
			"slides_space" => 0,
			"controls" => "no",
			"interval" => "",
			"autoheight" => "no",
			"align" => "",
			"custom" => "no",
			"ids" => "",
			"cat" => "",
			"count" => "3",
			"offset" => "",
			"orderby" => "date",
			"order" => "desc",
			"scheme" => "",
			"bg_color" => "",
			"bg_image" => "",
			"bg_overlay" => "",
			"bg_texture" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
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

		$count = max(1, (int) $count);
		$columns = max(1, min(12, (int) $columns));
		if (invetex_param_is_off($custom) && $count < $columns) $columns = $count;
		
		invetex_storage_set('sc_testimonials_data', array(
			'id' => $id,
            'style' => $style,
            'columns' => $columns,
            'counter' => 0,
            'slider' => $slider,
            'css_wh' => $ws . $hs
            )
        );

		if (invetex_param_is_on($slider)) invetex_enqueue_slider('swiper');
	
		$output = ($bg_color!='' || $bg_image!='' || $bg_overlay>0 || $bg_texture>0 || invetex_strlen($bg_texture)>2 || ($scheme && !invetex_param_is_off($scheme) && !invetex_param_is_inherit($scheme))
					? '<div class="sc_testimonials_wrap sc_section'
							. ($scheme && !invetex_param_is_off($scheme) && !invetex_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
							. '"'
						.' style="'
							. ($bg_color !== '' && $bg_overlay==0 ? 'background-color:' . esc_attr($bg_color) . ';' : '')
							. ($bg_image !== '' ? 'background-image:url(' . esc_url($bg_image) . ');' : '')
							. '"'
						. (!invetex_param_is_off($animation) ? ' data-animation="'.esc_attr(invetex_get_animation_classes($animation)).'"' : '')
						. '>'
						. '<div class="sc_section_overlay'.($bg_texture>0 ? ' texture_bg_'.esc_attr($bg_texture) : '') . '"'
								. ' style="' . ($bg_overlay>0 ? 'background-color:rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].','.min(1, max(0, $bg_overlay)).');' : '')
									. (invetex_strlen($bg_texture)>2 ? 'background-image:url('.esc_url($bg_texture).');' : '')
									. '"'
									. ($bg_overlay > 0 ? ' data-overlay="'.esc_attr($bg_overlay).'" data-bg_color="'.esc_attr($bg_color).'"' : '')
									. '>' 
					: '')
				. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_testimonials sc_testimonials_style_'.esc_attr($style)
 					. ' ' . esc_attr(invetex_get_template_property($style, 'container_classes'))
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ($align!='' && $align!='none' ? ' align'.esc_attr($align) : '')
					. '"'
				. ($bg_color=='' && $bg_image=='' && $bg_overlay==0 && ($bg_texture=='' || $bg_texture=='0') && !invetex_param_is_off($animation) ? ' data-animation="'.esc_attr(invetex_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. '>'
			. (!empty($subtitle) ? '<h6 class="sc_testimonials_subtitle sc_item_subtitle">' . trim(invetex_strmacros($subtitle)) . '</h6>' : '')
			. (!empty($title) ? '<h2 class="sc_testimonials_title sc_item_title">' . trim(invetex_strmacros($title)) . '</h2>' : '')
			. (!empty($description) ? '<div class="sc_testimonials_descr sc_item_descr">' . trim(invetex_strmacros($description)) . '</div>' : '')
			. (invetex_param_is_on($slider) 
				? ('<div class="sc_slider_swiper swiper-slider-container'
								. ' ' . esc_attr(invetex_get_slider_controls_classes($controls))
								. (invetex_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
								. ($hs ? ' sc_slider_height_fixed' : '')
								. '"'
							. (!empty($width) && invetex_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
							. (!empty($height) && invetex_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
							. ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
							. ($columns > 1 ? ' data-slides-per-view="' . esc_attr($columns) . '"' : '')
							. ($slides_space > 0 ? ' data-slides-space="' . esc_attr($slides_space) . '"' : '')
							. ' data-slides-min-width="250"'
						. '>'
					. '<div class="slides swiper-wrapper">')
				: ($columns > 1 
					? '<div class="sc_columns columns_wrap">' 
					: '')
				);
	
		$content = do_shortcode($content);
			
		if (invetex_param_is_on($custom) && $content) {
			$output .= $content;
		} else {
			global $post;
		
			if (!empty($ids)) {
				$posts = explode(',', $ids);
				$count = count($posts);
			}
			
			$args = array(
				'post_type' => 'testimonial',
				'post_status' => 'publish',
				'posts_per_page' => $count,
				'ignore_sticky_posts' => true,
				'order' => $order=='asc' ? 'asc' : 'desc',
			);
		
			if ($offset > 0 && empty($ids)) {
				$args['offset'] = $offset;
			}
		
			$args = invetex_query_add_sort_order($args, $orderby, $order);
			$args = invetex_query_add_posts_and_cats($args, $ids, 'testimonial', $cat, 'testimonial_group');
	
			$query = new WP_Query( $args );
	
			$post_number = 0;
				
			while ( $query->have_posts() ) { 
				$query->the_post();
				$post_number++;
				$args = array(
					'layout' => $style,
					'show' => false,
					'number' => $post_number,
					'posts_on_page' => ($count > 0 ? $count : $query->found_posts),
					"descr" => invetex_get_custom_option('post_excerpt_maxlength'.($columns > 1 ? '_masonry' : '')),
					"orderby" => $orderby,
					'content' => false,
					'terms_list' => false,
					'columns_count' => $columns,
					'slider' => $slider,
					'tag_id' => $id ? $id . '_' . $post_number : '',
					'tag_class' => '',
					'tag_animation' => '',
					'tag_css' => '',
					'tag_css_wh' => $ws . $hs
				);
				$post_data = invetex_get_post_data($args);
				$post_data['post_content'] = wpautop($post_data['post_content']);	// Add <p> around text and paragraphs. Need separate call because 'content'=>false (see above)
				$post_meta = get_post_meta($post_data['post_id'], invetex_storage_get('options_prefix').'_testimonial_data', true);
				$thumb_sizes = invetex_get_thumb_sizes(array('layout' => $style));
				$args['author'] = $post_meta['testimonial_author'];
				$args['position'] = $post_meta['testimonial_position'];
				$args['link'] = !empty($post_meta['testimonial_link']) ? $post_meta['testimonial_link'] : '';	//$post_data['post_link'];
				$args['email'] = $post_meta['testimonial_email'];
				$args['photo'] = $post_data['post_thumb'];
				$mult = invetex_get_retina_multiplier();
				if (empty($args['photo']) && !empty($args['email'])) $args['photo'] = get_avatar($args['email'], $thumb_sizes['w']*$mult);
				$output .= invetex_show_post_layout($args, $post_data);
			}
			wp_reset_postdata();
		}
	
		if (invetex_param_is_on($slider)) {
			$output .= '</div>'
				. '<div class="sc_slider_controls_wrap"><a class="sc_slider_prev" href="#"></a><a class="sc_slider_next" href="#"></a></div>'
				. '<div class="sc_slider_pagination_wrap"></div>'
				. '</div>';
		} else if ($columns > 1) {
			$output .= '</div>';
		}

		$output .= '</div>'
					. ($bg_color!='' || $bg_image!='' || $bg_overlay>0 || $bg_texture>0 || invetex_strlen($bg_texture)>2 || ($scheme && !invetex_param_is_off($scheme) && !invetex_param_is_inherit($scheme))
						?  '</div></div>'
						: '');
	
		// Add template specific scripts and styles
		do_action('invetex_action_blog_scripts', $style);

		return apply_filters('invetex_shortcode_output', $output, 'trx_testimonials', $atts, $content);
	}
	invetex_require_shortcode('trx_testimonials', 'invetex_sc_testimonials');
}
	
	
if (!function_exists('invetex_sc_testimonials_item')) {
	function invetex_sc_testimonials_item($atts, $content=null){	
		if (invetex_in_shortcode_blogger()) return '';
		extract(invetex_html_decode(shortcode_atts(array(
			// Individual params
			"author" => "",
			"position" => "",
			"link" => "",
			"photo" => "",
			"email" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
		), $atts)));

		invetex_storage_inc_array('sc_testimonials_data', 'counter');
	
		$id = $id ? $id : (invetex_storage_get_array('sc_testimonials_data', 'id') ? invetex_storage_get_array('sc_testimonials_data', 'id') . '_' . invetex_storage_get_array('sc_testimonials_data', 'counter') : '');
	
		$thumb_sizes = invetex_get_thumb_sizes(array('layout' => invetex_storage_get_array('sc_testimonials_data', 'style')));

		if (empty($photo)) {
			if (!empty($email))
				$mult = invetex_get_retina_multiplier();
				$photo = get_avatar($email, $thumb_sizes['w']*$mult);
		} else {
			if ($photo > 0) {
				$attach = wp_get_attachment_image_src( $photo, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$photo = $attach[0];
			}
			$photo = invetex_get_resized_image_tag($photo, $thumb_sizes['w'], $thumb_sizes['h']);
		}

		$post_data = array(
			'post_content' => do_shortcode($content)
		);
		$args = array(
			'layout' => invetex_storage_get_array('sc_testimonials_data', 'style'),
			'number' => invetex_storage_get_array('sc_testimonials_data', 'counter'),
			'columns_count' => invetex_storage_get_array('sc_testimonials_data', 'columns'),
			'slider' => invetex_storage_get_array('sc_testimonials_data', 'slider'),
			'show' => false,
			'descr'  => 0,
			'tag_id' => $id,
			'tag_class' => $class,
			'tag_animation' => '',
			'tag_css' => $css,
			'tag_css_wh' => invetex_storage_get_array('sc_testimonials_data', 'css_wh'),
			'author' => $author,
			'position' => $position,
			'link' => $link,
			'email' => $email,
			'photo' => $photo
		);
		$output = invetex_show_post_layout($args, $post_data);

		return apply_filters('invetex_shortcode_output', $output, 'trx_testimonials_item', $atts, $content);
	}
	invetex_require_shortcode('trx_testimonials_item', 'invetex_sc_testimonials_item');
}
// ---------------------------------- [/trx_testimonials] ---------------------------------------



// Add [trx_testimonials] and [trx_testimonials_item] in the shortcodes list
if (!function_exists('invetex_testimonials_reg_shortcodes')) {
	//add_filter('invetex_action_shortcodes_list',	'invetex_testimonials_reg_shortcodes');
	function invetex_testimonials_reg_shortcodes() {
		if (invetex_storage_isset('shortcodes')) {

			$testimonials_groups = invetex_get_list_terms(false, 'testimonial_group');
			$testimonials_styles = invetex_get_list_templates('testimonials');
			$controls = invetex_get_list_slider_controls();

			invetex_sc_map_before('trx_title', array(
			
				// Testimonials
				"trx_testimonials" => array(
					"title" => esc_html__("Testimonials", 'invetex'),
					"desc" => wp_kses_data( __("Insert testimonials into post (page)", 'invetex') ),
					"decorate" => true,
					"container" => false,
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
						"style" => array(
							"title" => esc_html__("Testimonials style", 'invetex'),
							"desc" => wp_kses_data( __("Select style to display testimonials", 'invetex') ),
							"value" => "testimonials-1",
							"type" => "select",
							"options" => $testimonials_styles
						),
						"columns" => array(
							"title" => esc_html__("Columns", 'invetex'),
							"desc" => wp_kses_data( __("How many columns use to show testimonials", 'invetex') ),
							"value" => 1,
							"min" => 1,
							"max" => 6,
							"step" => 1,
							"type" => "spinner"
						),
						"slider" => array(
							"title" => esc_html__("Slider", 'invetex'),
							"desc" => wp_kses_data( __("Use slider to show testimonials", 'invetex') ),
							"value" => "yes",
							"type" => "switch",
							"options" => invetex_get_sc_param('yes_no')
						),
						"controls" => array(
							"title" => esc_html__("Controls", 'invetex'),
							"desc" => wp_kses_data( __("Slider controls style and position", 'invetex') ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $controls
						),
						"slides_space" => array(
							"title" => esc_html__("Space between slides", 'invetex'),
							"desc" => wp_kses_data( __("Size of space (in px) between slides", 'invetex') ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => 0,
							"min" => 0,
							"max" => 100,
							"step" => 10,
							"type" => "spinner"
						),
						"interval" => array(
							"title" => esc_html__("Slides change interval", 'invetex'),
							"desc" => wp_kses_data( __("Slides change interval (in milliseconds: 1000ms = 1s)", 'invetex') ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => 7000,
							"step" => 500,
							"min" => 0,
							"type" => "spinner"
						),
						"autoheight" => array(
							"title" => esc_html__("Autoheight", 'invetex'),
							"desc" => wp_kses_data( __("Change whole slider's height (make it equal current slide's height)", 'invetex') ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => "yes",
							"type" => "switch",
							"options" => invetex_get_sc_param('yes_no')
						),
						"align" => array(
							"title" => esc_html__("Alignment", 'invetex'),
							"desc" => wp_kses_data( __("Alignment of the testimonials block", 'invetex') ),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => invetex_get_sc_param('align')
						),
						"custom" => array(
							"title" => esc_html__("Custom", 'invetex'),
							"desc" => wp_kses_data( __("Allow get testimonials from inner shortcodes (custom) or get it from specified group (cat)", 'invetex') ),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => invetex_get_sc_param('yes_no')
						),
						"cat" => array(
							"title" => esc_html__("Categories", 'invetex'),
							"desc" => wp_kses_data( __("Select categories (groups) to show testimonials. If empty - select testimonials from any category (group) or from IDs list", 'invetex') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => invetex_array_merge(array(0 => esc_html__('- Select category -', 'invetex')), $testimonials_groups)
						),
						"count" => array(
							"title" => esc_html__("Number of posts", 'invetex'),
							"desc" => wp_kses_data( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'invetex') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => esc_html__("Offset before select posts", 'invetex'),
							"desc" => wp_kses_data( __("Skip posts before select next part.", 'invetex') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Post order by", 'invetex'),
							"desc" => wp_kses_data( __("Select desired posts sorting method", 'invetex') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "date",
							"type" => "select",
							"options" => invetex_get_sc_param('sorting')
						),
						"order" => array(
							"title" => esc_html__("Post order", 'invetex'),
							"desc" => wp_kses_data( __("Select desired posts order", 'invetex') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => invetex_get_sc_param('ordering')
						),
						"ids" => array(
							"title" => esc_html__("Post IDs list", 'invetex'),
							"desc" => wp_kses_data( __("Comma separated list of posts ID. If set - parameters above are ignored!", 'invetex') ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "",
							"type" => "text"
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
					),
					"children" => array(
						"name" => "trx_testimonials_item",
						"title" => esc_html__("Item", 'invetex'),
						"desc" => wp_kses_data( __("Testimonials item (custom parameters)", 'invetex') ),
						"container" => true,
						"params" => array(
							"author" => array(
								"title" => esc_html__("Author", 'invetex'),
								"desc" => wp_kses_data( __("Name of the testimonmials author", 'invetex') ),
								"value" => "",
								"type" => "text"
							),
							"link" => array(
								"title" => esc_html__("Link", 'invetex'),
								"desc" => wp_kses_data( __("Link URL to the testimonmials author page", 'invetex') ),
								"value" => "",
								"type" => "text"
							),
							"email" => array(
								"title" => esc_html__("E-mail", 'invetex'),
								"desc" => wp_kses_data( __("E-mail of the testimonmials author (to get gravatar)", 'invetex') ),
								"value" => "",
								"type" => "text"
							),
							"photo" => array(
								"title" => esc_html__("Photo", 'invetex'),
								"desc" => wp_kses_data( __("Select or upload photo of testimonmials author or write URL of photo from other site", 'invetex') ),
								"value" => "",
								"type" => "media"
							),
							"_content_" => array(
								"title" => esc_html__("Testimonials text", 'invetex'),
								"desc" => wp_kses_data( __("Current testimonials text", 'invetex') ),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => invetex_get_sc_param('id'),
							"class" => invetex_get_sc_param('class'),
							"css" => invetex_get_sc_param('css')
						)
					)
				)

			));
		}
	}
}


// Add [trx_testimonials] and [trx_testimonials_item] in the VC shortcodes list
if (!function_exists('invetex_testimonials_reg_shortcodes_vc')) {
	//add_filter('invetex_action_shortcodes_list_vc',	'invetex_testimonials_reg_shortcodes_vc');
	function invetex_testimonials_reg_shortcodes_vc() {

		$testimonials_groups = invetex_get_list_terms(false, 'testimonial_group');
		$testimonials_styles = invetex_get_list_templates('testimonials');
		$controls			 = invetex_get_list_slider_controls();
			
		// Testimonials			
		vc_map( array(
				"base" => "trx_testimonials",
				"name" => esc_html__("Testimonials", 'invetex'),
				"description" => wp_kses_data( __("Insert testimonials slider", 'invetex') ),
				"category" => esc_html__('Content', 'invetex'),
				'icon' => 'icon_trx_testimonials',
				"class" => "trx_sc_columns trx_sc_testimonials",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_testimonials_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Testimonials style", 'invetex'),
						"description" => wp_kses_data( __("Select style to display testimonials", 'invetex') ),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip($testimonials_styles),
						"type" => "dropdown"
					),
					array(
						"param_name" => "slider",
						"heading" => esc_html__("Slider", 'invetex'),
						"description" => wp_kses_data( __("Use slider to show testimonials", 'invetex') ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'invetex'),
						"class" => "",
						"std" => "yes",
						"value" => array_flip(invetex_get_sc_param('yes_no')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "controls",
						"heading" => esc_html__("Controls", 'invetex'),
						"description" => wp_kses_data( __("Slider controls style and position", 'invetex') ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'invetex'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"std" => "no",
						"value" => array_flip($controls),
						"type" => "dropdown"
					),
					array(
						"param_name" => "slides_space",
						"heading" => esc_html__("Space between slides", 'invetex'),
						"description" => wp_kses_data( __("Size of space (in px) between slides", 'invetex') ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'invetex'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "interval",
						"heading" => esc_html__("Slides change interval", 'invetex'),
						"description" => wp_kses_data( __("Slides change interval (in milliseconds: 1000ms = 1s)", 'invetex') ),
						"group" => esc_html__('Slider', 'invetex'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => "7000",
						"type" => "textfield"
					),
					array(
						"param_name" => "autoheight",
						"heading" => esc_html__("Autoheight", 'invetex'),
						"description" => wp_kses_data( __("Change whole slider's height (make it equal current slide's height)", 'invetex') ),
						"group" => esc_html__('Slider', 'invetex'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => array("Autoheight" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", 'invetex'),
						"description" => wp_kses_data( __("Alignment of the testimonials block", 'invetex') ),
						"class" => "",
						"value" => array_flip(invetex_get_sc_param('align')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "custom",
						"heading" => esc_html__("Custom", 'invetex'),
						"description" => wp_kses_data( __("Allow get testimonials from inner shortcodes (custom) or get it from specified group (cat)", 'invetex') ),
						"class" => "",
						"value" => array("Custom slides" => "yes" ),
						"type" => "checkbox"
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
						"param_name" => "cat",
						"heading" => esc_html__("Categories", 'invetex'),
						"description" => wp_kses_data( __("Select categories (groups) to show testimonials. If empty - select testimonials from any category (group) or from IDs list", 'invetex') ),
						"group" => esc_html__('Query', 'invetex'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip(invetex_array_merge(array(0 => esc_html__('- Select category -', 'invetex')), $testimonials_groups)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", 'invetex'),
						"description" => wp_kses_data( __("How many columns use to show testimonials", 'invetex') ),
						"group" => esc_html__('Query', 'invetex'),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Number of posts", 'invetex'),
						"description" => wp_kses_data( __("How many posts will be displayed? If used IDs - this parameter ignored.", 'invetex') ),
						"group" => esc_html__('Query', 'invetex'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "3",
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => esc_html__("Offset before select posts", 'invetex'),
						"description" => wp_kses_data( __("Skip posts before select next part.", 'invetex') ),
						"group" => esc_html__('Query', 'invetex'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Post sorting", 'invetex'),
						"description" => wp_kses_data( __("Select desired posts sorting method", 'invetex') ),
						"group" => esc_html__('Query', 'invetex'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"std" => "date",
						"class" => "",
						"value" => array_flip(invetex_get_sc_param('sorting')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Post order", 'invetex'),
						"description" => wp_kses_data( __("Select desired posts order", 'invetex') ),
						"group" => esc_html__('Query', 'invetex'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"std" => "desc",
						"class" => "",
						"value" => array_flip(invetex_get_sc_param('ordering')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("Post IDs list", 'invetex'),
						"description" => wp_kses_data( __("Comma separated list of posts ID. If set - parameters above are ignored!", 'invetex') ),
						"group" => esc_html__('Query', 'invetex'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
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
					invetex_vc_width(),
					invetex_vc_height(),
					invetex_get_vc_param('margin_top'),
					invetex_get_vc_param('margin_bottom'),
					invetex_get_vc_param('margin_left'),
					invetex_get_vc_param('margin_right'),
					invetex_get_vc_param('id'),
					invetex_get_vc_param('class'),
					invetex_get_vc_param('animation'),
					invetex_get_vc_param('css')
				),
				'js_view' => 'VcTrxColumnsView'
		) );
			
			
		vc_map( array(
				"base" => "trx_testimonials_item",
				"name" => esc_html__("Testimonial", 'invetex'),
				"description" => wp_kses_data( __("Single testimonials item", 'invetex') ),
				"show_settings_on_create" => true,
				"class" => "trx_sc_collection trx_sc_column_item trx_sc_testimonials_item",
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_testimonials_item',
				"as_child" => array('only' => 'trx_testimonials'),
				"as_parent" => array('except' => 'trx_testimonials'),
				"params" => array(
					array(
						"param_name" => "author",
						"heading" => esc_html__("Author", 'invetex'),
						"description" => wp_kses_data( __("Name of the testimonmials author", 'invetex') ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link", 'invetex'),
						"description" => wp_kses_data( __("Link URL to the testimonmials author page", 'invetex') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "email",
						"heading" => esc_html__("E-mail", 'invetex'),
						"description" => wp_kses_data( __("E-mail of the testimonmials author", 'invetex') ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "photo",
						"heading" => esc_html__("Photo", 'invetex'),
						"description" => wp_kses_data( __("Select or upload photo of testimonmials author or write URL of photo from other site", 'invetex') ),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					/*
					array(
						"param_name" => "content",
						"heading" => esc_html__("Testimonials text", 'invetex'),
						"description" => wp_kses_data( __("Current testimonials text", 'invetex') ),
						"class" => "",
						"value" => "",
						"type" => "textarea_html"
					),
					*/
					invetex_get_vc_param('id'),
					invetex_get_vc_param('class'),
					invetex_get_vc_param('css')
				),
				'js_view' => 'VcTrxColumnItemView'
		) );
			
		class WPBakeryShortCode_Trx_Testimonials extends INVETEX_VC_ShortCodeColumns {}
		class WPBakeryShortCode_Trx_Testimonials_Item extends INVETEX_VC_ShortCodeCollection {}
		
	}
}
?>