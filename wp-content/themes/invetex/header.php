<?php
/**
 * The Header for our theme.
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="<?php
		// Add class 'scheme_xxx' into <html> because it used as context for the body classes!
		$body_scheme = invetex_get_custom_option('body_scheme');
		if (empty($body_scheme) || invetex_is_inherit_option($body_scheme)) $body_scheme = 'original';
		echo 'scheme_' . esc_attr($body_scheme); 
		?>">

<head>
	<?php wp_head(); ?>
</head>
<body <?php body_class();?>>
	<?php do_action( 'before' ); ?>
	<?php
		$body_style  = invetex_get_custom_option('body_style');
		$class = $style = '';
		if (invetex_get_custom_option('bg_custom')=='yes' && ($body_style=='boxed' || invetex_get_custom_option('bg_image_load')=='always')) {
			if (($img = invetex_get_custom_option('bg_image_custom')) != '')
				$style = 'background: url('.esc_url($img).') ' . str_replace('_', ' ', invetex_get_custom_option('bg_image_custom_position')) . ' no-repeat fixed;';
			else if (($img = invetex_get_custom_option('bg_pattern_custom')) != '')
				$style = 'background: url('.esc_url($img).') 0 0 repeat fixed;';
			else if (($img = invetex_get_custom_option('bg_image')) > 0)
				$class = 'bg_image_'.($img);
			else if (($img = invetex_get_custom_option('bg_pattern')) > 0)
				$class = 'bg_pattern_'.($img);
			if (($img = invetex_get_custom_option('bg_color')) != '')
				$style .= 'background-color: '.($img).';';
		}
	?>
	<div class="body_wrap<?php echo !empty($class) ? ' '.esc_attr($class) : ''; ?>"<?php echo !empty($style) ? ' style="'.esc_attr($style).'"' : ''; ?>>
		<?php
		$video_bg_show = invetex_get_custom_option('show_video_bg')=='yes';
		$youtube = invetex_get_custom_option('video_bg_youtube_code');
		$video   = invetex_get_custom_option('video_bg_url');
		$overlay = invetex_get_custom_option('video_bg_overlay')=='yes';
		if ($video_bg_show && (!empty($youtube) || !empty($video))) {
			if (!empty($youtube)) {
				?>
				<div class="video_bg<?php echo !empty($overlay) ? ' video_bg_overlay' : ''; ?>" data-youtube-code="<?php echo esc_attr($youtube); ?>"></div>
				<?php
			} else if (!empty($video)) {
				$info = pathinfo($video);
				$ext = !empty($info['extension']) ? $info['extension'] : 'src';
				?>
				<div class="video_bg<?php echo !empty($overlay) ? ' video_bg_overlay' : ''; ?>"><video class="video_bg_tag" width="1280" height="720" data-width="1280" data-height="720" data-ratio="16:9" preload="metadata" autoplay loop src="<?php echo esc_url($video); ?>"><source src="<?php echo esc_url($video); ?>" type="video/<?php echo esc_attr($ext); ?>"></source></video></div>
				<?php
			}
		}
		?>
		<div class="page_wrap">
			<?php
			$top_panel_style = invetex_get_custom_option('top_panel_style');
			$top_panel_position = invetex_get_custom_option('top_panel_position');
			$top_panel_scheme = invetex_get_custom_option('top_panel_scheme');
			// Top panel 'Above' or 'Over'
			if (in_array($top_panel_position, array('above', 'over'))) {
				invetex_show_post_layout(array(
					'layout' => $top_panel_style,
					'position' => $top_panel_position,
					'scheme' => $top_panel_scheme
					), false);
				// Mobile Menu
				get_template_part(invetex_get_file_slug('templates/headers/_parts/header-mobile.php'));
			}
			// Slider
			get_template_part(invetex_get_file_slug('templates/headers/_parts/slider.php'));
			// Top panel 'Below'
			if ($top_panel_position == 'below') {
				invetex_show_post_layout(array(
					'layout' => $top_panel_style,
					'position' => $top_panel_position,
					'scheme' => $top_panel_scheme
					), false);
				// Mobile Menu
				get_template_part(invetex_get_file_slug('templates/headers/_parts/header-mobile.php'));
			}
			// Top of page section: page title and breadcrumbs
			$show_title = invetex_get_custom_option('show_page_title')=='yes';
			$show_navi = apply_filters('invetex_filter_show_post_navi', false);
			$show_breadcrumbs = invetex_get_custom_option('show_breadcrumbs')=='yes';
			$show_top_post_info = invetex_get_custom_option('show_top_post_info')=='yes';
			if ($show_title || $show_breadcrumbs) {
				?>
				<div class="top_panel_title top_panel_style_<?php echo esc_attr(str_replace('header_', '', $top_panel_style)); ?> <?php echo (!empty($show_title) ? ' title_present'.  ($show_navi ? ' navi_present' : '') : '') . (!empty($show_breadcrumbs) ? ' breadcrumbs_present' : ''); ?> scheme_<?php echo esc_attr($top_panel_scheme); ?> is_page_paddings_<?php echo esc_attr(invetex_get_custom_option('body_paddings')); ?>">
					<div class="top_panel_title_inner top_panel_inner_style_<?php echo esc_attr(str_replace('header_', '', $top_panel_style)); ?> <?php echo (!empty($show_title) ? ' title_present_inner' : '') . (!empty($show_breadcrumbs) ? ' breadcrumbs_present_inner' : ''); ?>">
						<div class="content_wrap">
							<?php
							if ($show_title) {

								if ($show_top_post_info && is_single() && get_the_category() && !is_search()){ ?>
									<div class="cat_post_info"><span class="post_categories">
										<?php foreach((get_the_category()) as $category) {
											echo '<a class="category_link" href="'.esc_url(get_category_link($category->cat_ID)).'" title="'.esc_attr($category->cat_name).'">'.esc_html($category->cat_name).'</a>';
											//break;
										} ?>
									</span></div>
									<?php
								}

								if ($show_navi) {
									?><div class="post_navi"><?php 
										previous_post_link( '<span class="post_navi_item post_navi_prev">%link</span>', '%title', true, '', 'product_cat' );
										next_post_link( '<span class="post_navi_item post_navi_next">%link</span>', '%title', true, '', 'product_cat' );
									?></div><?php
								} else {
									?><h1 class="page_title"><?php echo strip_tags(invetex_get_blog_title()); ?></h1><?php
								}
							}
							?>

							<?php if($show_top_post_info && !is_search()){ ?>
								<div class="post_info">
									<?php
										$post_id = get_the_ID();
										$post_date = apply_filters('invetex_filter_post_date', get_the_date('Y-m-d H:i:s'), $post_id, get_post_type());
										$post_date_diff = invetex_get_date_or_difference($post_date);
										?>
										<span class="post_info_item">
											<span class="post_counters_item post_info_posted icon-calendar-light"><?php
												echo (in_array(get_post_type(), array('post', 'page', 'product'))
													? ''
													: ($post_date <= date('Y-m-d')
														? esc_html__('Started', 'invetex')
														: esc_html__('Will start', 'invetex')));
												echo esc_html($post_date_diff); ?></span>
										</span>
									<?php
									if (is_single()) { ?>
										<span class="post_info_item">
											<span class="post_counters_item post_info_posted_by icon-pencil-light"><?php esc_html_e('by', 'invetex'); ?> <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'), '')); ?>" class="post_info_author"><?php echo trim(get_the_author()); ?></a></span>
										</span>
									<?php
									}
									?>
									<span class="post_info_item">
										<a class="post_counters_item post_counters_comments icon-comment-light" title="<?php echo esc_attr( sprintf(__('Comments - %s', 'invetex'), get_comments_number()) ); ?>" href="<?php echo esc_url(get_comments_link()); ?>"><span class="post_counters_number"><?php echo trim(get_comments_number()); ?></span><?php echo ' '.esc_html__('Comments', 'invetex'); ?></a>
									</span>
								</div>
							<?php } ?>

							<?php
							if ($show_breadcrumbs) {
								?><div class="breadcrumbs"><?php if (!is_404()) invetex_show_breadcrumbs(); ?></div><?php
							}
							?>
						</div>
					</div>
				</div>
				<?php
			}
			?>
			<div class="page_content_wrap page_paddings_<?php echo esc_attr(invetex_get_custom_option('body_paddings')); ?>">
				<?php
				// Content and sidebar wrapper
				if ($body_style!='fullscreen') invetex_open_wrapper('<div class="content_wrap">');
				// Main content wrapper
				invetex_open_wrapper('<div class="content">');
				?>