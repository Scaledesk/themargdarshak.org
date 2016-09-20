<?php 
// Get template args
extract(invetex_template_get_args('top-panel-top'));

if (in_array('contact_info', $top_panel_top_components) && ($contact_info=trim(invetex_get_custom_option('contact_info')))!='') {
	?>
	<div class="top_panel_top_contact_area icon-location-light"><?php echo force_balance_tags($contact_info); ?></div>
	<?php
}
?>

<?php
if (in_array('open_hours', $top_panel_top_components) && ($open_hours=trim(invetex_get_custom_option('contact_open_hours')))!='') {
	?>
	<div class="top_panel_top_open_hours icon-clock-light"><?php echo force_balance_tags($open_hours); ?></div>
	<?php
}
?>

<?php
if (in_array('phone', $top_panel_top_components) && ($phone=trim(invetex_get_custom_option('contact_phone')))!='') {
	?>
	<div class="top_panel_top_ophone icon-call-out"><?php echo force_balance_tags($phone); ?></div>
<?php
}
?>

<div class="top_panel_top_user_area<?php echo (in_array('cart', $top_panel_top_components) && function_exists('invetex_exists_woocommerce') && invetex_exists_woocommerce() && (invetex_is_woocommerce_page() && invetex_get_custom_option('show_cart')=='shop' || invetex_get_custom_option('show_cart')=='always') && !(is_checkout() || is_cart() || defined('WOOCOMMERCE_CHECKOUT') || defined('WOOCOMMERCE_CART'))) ? ' cart_show' : ' cart_hide'; ?>">

	<?php if(!invetex_param_is_off( invetex_get_custom_option('show_sidebar_outer'))){ ?>
		<div class="menu_pushy_wrap clearfix">
			<a href="#" class="menu_pushy_button icon-1460034782_menu2"></a>
		</div>
	<?php } ?>

	<?php
	if (in_array('search', $top_panel_top_components) && invetex_get_custom_option('show_search')=='yes') {
		?>
		<div class="top_panel_top_search"><?php echo trim(invetex_sc_search(array('state' => invetex_get_theme_option('search_style')=='default' ? 'fixed' : 'closed'))); ?></div>
		<?php
	}

	$menu_user = invetex_get_nav_menu('menu_user');
	if (empty($menu_user)) {
		?>
		<ul id="<?php echo (!empty($menu_user_id) ? esc_attr($menu_user_id) : 'menu_user'); ?>" class="menu_user_nav">
		<?php
	} else {
		$menu = invetex_substr($menu_user, 0, invetex_strlen($menu_user)-5);
		$pos = invetex_strpos($menu, '<ul');
		if ($pos!==false && invetex_strpos($menu, 'menu_user_nav')===false)
			$menu = invetex_substr($menu, 0, $pos+3) . ' class="menu_user_nav"' . invetex_substr($menu, $pos+3);
		if (!empty($menu_user_id))
			$menu = invetex_set_tag_attrib($menu, '<ul>', 'id', $menu_user_id);
		echo str_replace('class=""', '', $menu);
	}
	

	if (in_array('currency', $top_panel_top_components) && function_exists('invetex_is_woocommerce_page') && invetex_is_woocommerce_page() && invetex_get_custom_option('show_currency')=='yes') {
		?>
		<li class="menu_user_currency">
			<a href="#">$</a>
			<ul>
				<li><a href="#"><b>&#36;</b> <?php esc_html_e('Dollar', 'invetex'); ?></a></li>
				<li><a href="#"><b>&euro;</b> <?php esc_html_e('Euro', 'invetex'); ?></a></li>
				<li><a href="#"><b>&pound;</b> <?php esc_html_e('Pounds', 'invetex'); ?></a></li>
			</ul>
		</li>
		<?php
	}

	if (in_array('language', $top_panel_top_components) && invetex_get_custom_option('show_languages')=='yes' && function_exists('icl_get_languages')) {
		$languages = icl_get_languages('skip_missing=1');
		if (!empty($languages) && is_array($languages)) {
			$lang_list = '';
			$lang_active = '';
			foreach ($languages as $lang) {
				$lang_title = esc_attr($lang['translated_name']);	//esc_attr($lang['native_name']);
				if ($lang['active']) {
					$lang_active = $lang_title;
				}
				$lang_list .= "\n"
					.'<li><a rel="alternate" hreflang="' . esc_attr($lang['language_code']) . '" href="' . esc_url(apply_filters('WPML_filter_link', $lang['url'], $lang)) . '">'
						.'<img src="' . esc_url($lang['country_flag_url']) . '" alt="' . esc_attr($lang_title) . '" title="' . esc_attr($lang_title) . '" />'
						. ($lang_title)
					.'</a></li>';
			}
			?>
			<li class="menu_user_language">
				<a href="#"><span><?php echo trim($lang_active); ?></span></a>
				<ul><?php echo trim($lang_list); ?></ul>
			</li>
			<?php
		}
	}

	if (in_array('bookmarks', $top_panel_top_components) && invetex_get_custom_option('show_bookmarks')=='yes') {
		// Load core messages
		invetex_enqueue_messages();
		?>
		<li class="menu_user_bookmarks"><a href="#" class="bookmarks_show icon-star" title="<?php esc_attr_e('Show bookmarks', 'invetex'); ?>"><?php esc_html_e('Bookmarks', 'invetex'); ?></a>
		<?php 
			$list = invetex_get_value_gpc('invetex_bookmarks', '');
			if (!empty($list)) $list = json_decode($list, true);
			?>
			<ul class="bookmarks_list">
				<li><a href="#" class="bookmarks_add icon-star-empty" title="<?php esc_attr_e('Add the current page into bookmarks', 'invetex'); ?>"><?php esc_html_e('Add bookmark', 'invetex'); ?></a></li>
				<?php 
				if (!empty($list) && is_array($list)) {
					foreach ($list as $bm) {
						echo '<li><a href="'.esc_url($bm['url']).'" class="bookmarks_item">'.($bm['title']).'<span class="bookmarks_delete icon-cancel" title="'.esc_attr__('Delete this bookmark', 'invetex').'"></span></a></li>';
					}
				}
				?>
			</ul>
		</li>
		<?php 
	}

	if (in_array('login', $top_panel_top_components) && invetex_get_custom_option('show_login')=='yes') {
		if ( !is_user_logged_in() ) {
			// Load core messages
			invetex_enqueue_messages();
			// Anyone can register ?
			if ( (int) get_option('users_can_register') > 0) {
				?><li class="menu_user_register_login"><a href="#popup_login" class="popup_link popup_login_link icon-key-light"><?php esc_html_e('Login', 'invetex'); ?></a><?php esc_html_e(' or ', 'invetex'); ?><a href="#popup_registration" class="popup_link popup_register_link"><?php esc_html_e('Register', 'invetex'); ?></a></li><?php
			}
			else { ?>
				<li class="menu_user_login"><a href="#popup_login" class="popup_link popup_login_link icon-key-light"><?php esc_html_e('Login', 'invetex'); ?></a></li><?php
			}
		} else {
			$current_user = wp_get_current_user();
			?>
			<li class="menu_user_controls">
				<a href="#"><?php
					$user_avatar = '';
					$mult = invetex_get_retina_multiplier();
					if ($current_user->user_email) $user_avatar = get_avatar($current_user->user_email, 16*$mult);
					if ($user_avatar) {
						?><span class="user_avatar"><?php echo trim($user_avatar); ?></span><?php
					}?><span class="user_name"><?php echo trim($current_user->display_name); ?></span></a>
				<ul>
					<?php if (current_user_can('publish_posts')) { ?>
					<li><a href="<?php echo esc_url(home_url('/')); ?>/wp-admin/post-new.php?post_type=post" class="icon icon-doc"><?php esc_html_e('New post', 'invetex'); ?></a></li>
					<?php } ?>
					<li><a href="<?php echo get_edit_user_link(); ?>" class="icon icon-cog"><?php esc_html_e('Settings', 'invetex'); ?></a></li>
				</ul>
			</li>
			<li class="menu_user_logout"><a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>" class="icon icon-logout"><?php esc_html_e('Logout', 'invetex'); ?></a></li>
			<?php 
		}
	}

	if (in_array('cart', $top_panel_top_components) && function_exists('invetex_exists_woocommerce') && invetex_exists_woocommerce() && (invetex_is_woocommerce_page() && invetex_get_custom_option('show_cart')=='shop' || invetex_get_custom_option('show_cart')=='always') && !(is_checkout() || is_cart() || defined('WOOCOMMERCE_CHECKOUT') || defined('WOOCOMMERCE_CART'))) { 
		?>
		<li class="menu_user_cart">
			<?php get_template_part(invetex_get_file_slug('templates/headers/_parts/contact-info-cart.php')); ?>
		</li>
		<?php
	}
	?>

	</ul>

</div>