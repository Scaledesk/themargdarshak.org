<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'invetex_template_testimonials_3_theme_setup' ) ) {
	add_action( 'invetex_action_before_init_theme', 'invetex_template_testimonials_3_theme_setup', 1 );
	function invetex_template_testimonials_3_theme_setup() {
		invetex_add_template(array(
			'layout' => 'testimonials-3',
			'template' => 'testimonials-3',
			'mode'   => 'testimonials',
			'title'  => esc_html__('Testimonials /Style 3/', 'invetex'),
			'thumb_title'  => esc_html__('Avatar (small)', 'invetex'),
			'w'		 => 80,
			'h'		 => 80
		));
	}
}

// Template output
if ( !function_exists( 'invetex_template_testimonials_3_output' ) ) {
	function invetex_template_testimonials_3_output($post_options, $post_data) {
		$show_title = true;
		$parts = explode('_', $post_options['layout']);
		$style = $parts[0];
		$columns = max(1, min(12, empty($parts[1]) ? (!empty($post_options['columns_count']) ? $post_options['columns_count'] : 1) : (int) $parts[1]));
		if (invetex_param_is_on($post_options['slider'])) {
			?><div class="swiper-slide" data-style="<?php echo esc_attr($post_options['tag_css_wh']); ?>" style="<?php echo esc_attr($post_options['tag_css_wh']); ?>"><?php
		} else if ($columns > 1) {
			?><div class="column-1_<?php echo esc_attr($columns); ?> column_padding_bottom"><?php
		}
		?>
			<div<?php echo !empty($post_options['tag_id']) ? ' id="'.esc_attr($post_options['tag_id']).'"' : ''; ?> class="sc_testimonial_item<?php echo (!empty($post_options['tag_class']) ? ' '.esc_attr($post_options['tag_class']) : ''); ?>"<?php echo !empty($post_options['tag_css']) ? ' style="'.esc_attr($post_options['tag_css']).'"' : '';?>>
				<div class="sc_testimonial_content"><?php echo trim($post_data['post_content']); ?></div>
				<?php if ($post_options['author']) { ?>
				<div class="sc_testimonial_author"><?php 
					echo (!empty($post_options['link'])
							? '<a href="'.esc_url($post_options['link']).'" class="sc_testimonial_author_name">'.($post_options['author']).'</a>' 
							: '<span class="sc_testimonial_author_name">'.($post_options['author']).'</span>')
						. (!empty($post_options['position'])
							? '<span class="sc_testimonial_author_position">'.($post_options['position']).'</span>'
							: ''); ?></div>
				<?php } ?>
				<?php if ($post_options['photo']) { ?>
				<div class="sc_testimonial_avatar"><?php echo trim($post_options['photo']); ?></div>
				<?php } ?>
			</div>
		<?php
		if (invetex_param_is_on($post_options['slider']) || $columns > 1) {
			?></div><?php
		}
	}
}
?>