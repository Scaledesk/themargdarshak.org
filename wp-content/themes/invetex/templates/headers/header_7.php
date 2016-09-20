<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'invetex_template_header_7_theme_setup' ) ) {
	add_action( 'invetex_action_before_init_theme', 'invetex_template_header_7_theme_setup', 1 );
	function invetex_template_header_7_theme_setup() {
		invetex_add_template(array(
			'layout' => 'header_7',
			'mode'   => 'header',
			'title'  => esc_html__('Header 7', 'invetex'),
			'icon'   => invetex_get_file_url('templates/headers/images/7.jpg'),
			'thumb_title'  => esc_html__('Original image', 'invetex'),
			'w'		 => null,
			'h_crop' => null,
			'h'      => null
			));
	}
}

// Template output
if ( !function_exists( 'invetex_template_header_7_output' ) ) {
	function invetex_template_header_7_output($post_options, $post_data) {

		// Get custom image (for blog) or featured image (for single)
		$header_css = '';
		if (is_singular()) {
			//$post_id = get_the_ID();
			//$header_image = wp_get_attachment_url(get_post_thumbnail_id($post_id));
		}
		if (empty($header_image))
			$header_image = invetex_get_custom_option('top_panel_image');
		if (empty($header_image))
			$header_image = get_header_image();
		if (!empty($header_image)) {
			// Uncomment next rows if you want crop image
			//$thumb_sizes = invetex_get_thumb_sizes(array( 'layout' => $post_options['layout'] ));
			//$header_image = invetex_get_resized_image_url($header_image, $thumb_sizes['w'], $thumb_sizes['h'], null, false, false, true);
			$header_css = ' style="background-image: url('.esc_url($header_image).')"';
		}
		?>
		
		<div class="top_panel_fixed_wrap"></div>

		<header class="top_panel_wrap top_panel_style_7 scheme_<?php echo esc_attr($post_options['scheme']); ?>">
			<div class="top_panel_wrap_inner top_panel_inner_style_7 top_panel_position_<?php echo esc_attr(invetex_get_custom_option('top_panel_position')); ?>">

			<?php if (invetex_get_custom_option('show_top_panel_top')=='yes') { ?>
				<div class="top_panel_top">
					<div class="content_wrap clearfix">
						<?php
						invetex_template_set_args('top-panel-top', array(
							'top_panel_top_components' => array('contact_info', 'open_hours', 'phone', 'login', 'currency', 'cart', 'bookmarks', 'language')
						));
						get_template_part(invetex_get_file_slug('templates/headers/_parts/top-panel-top.php'));
						?>
					</div>
				</div>
			<?php } ?>

			<div class="top_panel_middle">
				<div class="content_wrap">
					<div class="contact_logo">
						<?php invetex_show_logo(true, true, false, false, true, false); ?>
					</div>
					<div class="menu_main_wrap">
						<nav class="menu_main_nav_area menu_hover_<?php echo esc_attr(invetex_get_theme_option('menu_hover')); ?>">
							<?php
							$menu_main = invetex_get_nav_menu('menu_main');
							if (empty($menu_main)) $menu_main = invetex_get_nav_menu();
							echo trim($menu_main);
							?>
						</nav>
						<?php
						if (invetex_get_custom_option('show_search')=='yes')
							echo trim(invetex_sc_search(array('class'=>"top_panel_el top_panel_icon", 'state'=>"closed", 'style'=>invetex_get_theme_option('search_style'))));
						?>

						<?php
						if (invetex_get_custom_option('show_socials')=='yes') {
							?>
							<div class="top_panel_top_socials top_panel_el">
								<?php echo trim(invetex_sc_socials(array('size'=>'tiny'))); ?>
							</div>
						<?php
						}
						?>
					</div>
				</div>
			</div>

			</div>
		</header>

		<section class="top_panel_image" <?php echo trim($header_css); ?>>
			<div class="top_panel_image_hover"></div>
			<div class="top_panel_image_header">
				<h1 itemprop="headline" class="top_panel_image_title entry-title"><?php echo strip_tags(invetex_get_blog_title()); ?></h1>
				<div class="breadcrumbs">
					<?php if (!is_404()) invetex_show_breadcrumbs(); ?>
				</div>
			</div>
		</section>


		<nav class="menu_pushy_nav_area pushy pushy-left scheme_<?php echo esc_attr(invetex_get_custom_option('sidebar_outer_scheme')); ?>">
			<div class="pushy_inner">
				<a href="#" class="close-pushy"></a>
				<?php get_template_part(invetex_get_file_slug('sidebar_outer.php')); ?>
			</div>
		</nav>

		<!-- Site Overlay -->
		<div class="site-overlay"></div>

		<?php
		invetex_storage_set('header_mobile', array(
				'open_hours' => true,
				'login' => true,
				'socials' => true,
				'bookmarks' => false,
				'contact_address' => true,
				'contact_phone' => true,
				'woo_cart' => true,
				'search' => true,
				'class' => 'header_mobile_style_7'
			)
		);
	}
}
?>