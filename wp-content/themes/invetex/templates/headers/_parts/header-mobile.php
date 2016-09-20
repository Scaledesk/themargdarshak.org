<?php
$header_options = invetex_storage_get('header_mobile');
$contact_phone = trim(invetex_get_custom_option('contact_phone'));
$contact_email = trim(invetex_get_custom_option('contact_email'));
?>
	<div class="header_mobile <?php echo esc_attr($header_options['class']); ?>">
		<div class="content_wrap">
			<div class="menu_button icon-menu"></div>
			<?php 
			invetex_show_logo(true,false,false,false,true,false);
			if ($header_options['woo_cart']){
				if (function_exists('invetex_exists_woocommerce') && invetex_exists_woocommerce() && (invetex_is_woocommerce_page() && invetex_get_custom_option('show_cart')=='shop' || invetex_get_custom_option('show_cart')=='always') && !(is_checkout() || is_cart() || defined('WOOCOMMERCE_CHECKOUT') || defined('WOOCOMMERCE_CART'))) { 
					?>
					<div class="menu_main_cart top_panel_icon">
						<?php get_template_part(invetex_get_file_slug('templates/headers/_parts/contact-info-cart.php')); ?>
					</div>
					<?php
				}
			}
			?>
		</div>
		<div class="side_wrap">
			<div class="close"><?php esc_html_e('Close', 'invetex'); ?></div>
			<div class="panel_top">
				<nav class="menu_main_nav_area">
					<?php
						$menu_main = invetex_get_nav_menu('menu_main');
						if (empty($menu_main)) $menu_main = invetex_get_nav_menu();
						$menu_main = invetex_set_tag_attrib($menu_main, '<ul>', 'id', 'menu_mobile');
						echo trim($menu_main);
					?>
				</nav>
				<?php 
				if ($header_options['search'] && invetex_get_custom_option('show_search')=='yes')
					echo trim(invetex_sc_search(array()));
				
				if ($header_options['login']) {
					if ( is_user_logged_in() ) { 
						?>
						<div class="login"><a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>" class="popup_link"><?php esc_html_e('Logout', 'invetex'); ?></a></div>
						<?php
					} else {
						// Load core messages
						invetex_enqueue_messages();
						// Load Popup engine
						invetex_enqueue_popup();
						?>
						<div class="login"><a href="#popup_login" class="popup_link popup_login_link icon-user"><?php esc_html_e('Login', 'invetex'); ?></a><?php
							if (invetex_get_theme_option('show_login')=='yes') {
								get_template_part(invetex_get_file_slug('templates/headers/_parts/login.php'));
							}?>
						</div>
						<?php
						// Anyone can register ?
						if ( (int) get_option('users_can_register') > 0) {
							?>
							<div class="login"><a href="#popup_registration" class="popup_link popup_register_link icon-pencil"><?php esc_html_e('Register', 'invetex'); ?></a><?php
								if (invetex_get_theme_option('show_login')=='yes') {
									get_template_part(invetex_get_file_slug('templates/headers/_parts/register.php'));
								}?>
							</div>
							<?php 
						}
					}
				}
				?>
			</div>
			
			<?php if ($header_options['contact_address'] || $header_options['contact_phone'] || $header_options['open_hours']) { ?>
			<div class="panel_middle">
				<?php

				if ($header_options['contact_phone'] && !empty($contact_phone)) {
					?><div class="contact_field contact_phone">
						<span class="contact_icon icon-call-out"></span>
						<span class="contact_label contact_phone"><?php echo force_balance_tags($contact_phone); ?></span>
					</div><?php
				}
				
				invetex_template_set_args('top-panel-top', array(
					'menu_user_id' => 'menu_user_mobile',
					'top_panel_top_components' => array(
						($header_options['open_hours'] ? 'open_hours' : '')
					)
				));
				get_template_part(invetex_get_file_slug('templates/headers/_parts/top-panel-top.php'));
				?>
			</div>
			<?php } ?>

			<div class="panel_bottom">
				<?php if ($header_options['socials'] && invetex_get_custom_option('show_socials')=='yes') { ?>
					<div class="contact_socials">
						<?php echo trim(invetex_sc_socials(array('size'=>'small'))); ?>
					</div>
				<?php } ?>
			</div>
		</div>
		<div class="mask"></div>
	</div>