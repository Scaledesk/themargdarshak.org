<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'invetex_template_header_2_theme_setup' ) ) {
	add_action( 'invetex_action_before_init_theme', 'invetex_template_header_2_theme_setup', 1 );
	function invetex_template_header_2_theme_setup() {
		invetex_add_template(array(
			'layout' => 'header_2',
			'mode'   => 'header',
			'title'  => esc_html__('Header 2', 'invetex'),
			'icon'   => invetex_get_file_url('templates/headers/images/2.jpg')
			));
	}
}

// Template output
if ( !function_exists( 'invetex_template_header_2_output' ) ) {
	function invetex_template_header_2_output($post_options, $post_data) {

		// WP custom header
		$header_css = '';
		if ($post_options['position'] != 'over') {
			$header_image = get_header_image();
			$header_css = $header_image!='' 
				? ' style="background-image: url('.esc_url($header_image).')"' 
				: '';
		}
		?>
		
		<div class="top_panel_fixed_wrap"></div>

		<header class="top_panel_wrap top_panel_style_3 boxed_style scheme_<?php echo esc_attr($post_options['scheme']); ?>">
			<div class="top_panel_wrap_inner top_panel_inner_style_3 top_panel_position_<?php echo esc_attr(invetex_get_custom_option('top_panel_position')); ?>">
			
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

			<div class="top_panel_middle" <?php echo trim($header_css); ?>>
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
				'class' => 'header_mobile_style_3 boxed_style'
			)
		);
	}
}
?>