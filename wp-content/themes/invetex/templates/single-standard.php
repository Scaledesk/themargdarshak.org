<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'invetex_template_single_standard_theme_setup' ) ) {
	add_action( 'invetex_action_before_init_theme', 'invetex_template_single_standard_theme_setup', 1 );
	function invetex_template_single_standard_theme_setup() {
		invetex_add_template(array(
			'layout' => 'single-standard',
			'mode'   => 'single',
			'need_content' => true,
			'need_terms' => true,
			'title'  => esc_html__('Single standard', 'invetex'),
			'thumb_title'  => esc_html__('Fullwidth image (crop)', 'invetex'),
			'w'		 => 1170,
			'h'		 => 659
		));
	}
}

// Template output
if ( !function_exists( 'invetex_template_single_standard_output' ) ) {
	function invetex_template_single_standard_output($post_options, $post_data) {
		$post_data['post_views']++;
		$avg_author = 0;
		$avg_users  = 0;
		if (!$post_data['post_protected'] && $post_options['reviews'] && invetex_get_custom_option('show_reviews')=='yes') {
			$avg_author = $post_data['post_reviews_author'];
			$avg_users  = $post_data['post_reviews_users'];
		}
		$show_title = invetex_get_custom_option('show_post_title')=='yes' && (invetex_get_custom_option('show_post_title_on_quotes')=='yes' || !in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote')));
		$title_tag = invetex_get_custom_option('show_page_title')=='yes' ? 'h3' : 'h1';

		invetex_open_wrapper('<article class="' 
				. join(' ', get_post_class('itemscope'
					. ' post_item post_item_single'
					. ' post_featured_' . esc_attr($post_options['post_class'])
					. ' post_format_' . esc_attr($post_data['post_format'])))
				. '"'
				. ' itemscope itemtype="http://schema.org/'.($avg_author > 0 || $avg_users > 0 ? 'Review' : 'Article')
				. '">');

		if ($show_title && $post_options['location'] == 'center' && invetex_get_custom_option('show_page_title')=='no') {
			?>
			<<?php echo esc_html($title_tag); ?> itemprop="<?php echo (float) $avg_author > 0 || (float) $avg_users > 0 ? 'itemReviewed' : 'headline'; ?>" class="post_title entry-title"><?php echo trim($post_data['post_title']); ?></<?php echo esc_html($title_tag); ?>>
		<?php 
		}

		if (!$post_data['post_protected'] && (
			!empty($post_options['dedicated']) ||
			(invetex_get_custom_option('show_featured_image')=='yes' && $post_data['post_thumb'])	// && $post_data['post_format']!='gallery' && $post_data['post_format']!='image')
		)) {
			?>
			<section class="post_featured">
			<?php
			if (!empty($post_options['dedicated'])) {
				echo trim($post_options['dedicated']);
			} else {
				invetex_enqueue_popup();
				?>
				<div class="post_thumb" data-image="<?php echo esc_url($post_data['post_attachment']); ?>" data-title="<?php echo esc_attr($post_data['post_title']); ?>">
					<a class="hover_icon hover_icon_view" href="<?php echo esc_url($post_data['post_attachment']); ?>" title="<?php echo esc_attr($post_data['post_title']); ?>"><?php echo trim($post_data['post_thumb']); ?></a>
				</div>
				<?php 
			}
			?>
			</section>
			<?php
		}

		invetex_open_wrapper('<section class="post_content'.(!$post_data['post_protected'] && $post_data['post_edit_enable'] ? ' '.esc_attr('post_content_editor_present') : '').'" itemprop="'.($avg_author > 0 || $avg_users > 0 ? 'reviewBody' : 'articleBody').'">');

		if ($show_title && $post_options['location'] != 'center' && invetex_get_custom_option('show_page_title')=='no') {
			?>
			<<?php echo esc_html($title_tag); ?> itemprop="<?php echo (float) $avg_author > 0 || (float) $avg_users > 0 ? 'itemReviewed' : 'headline'; ?>" class="post_title entry-title"><?php echo trim($post_data['post_title']); ?></<?php echo esc_html($title_tag); ?>>
			<?php

		}
			if (!$post_data['post_protected'] && invetex_get_custom_option('show_post_info')=='yes') {
				$post_options['info_parts'] = array('snippets'=>true);
				invetex_template_set_args('post-info', array(
					'post_options' => $post_options,
					'post_data' => $post_data
				));
				get_template_part(invetex_get_file_slug('templates/_parts/post-info.php'));
			}


		invetex_template_set_args('reviews-block', array(
			'post_options' => $post_options,
			'post_data' => $post_data,
			'avg_author' => $avg_author,
			'avg_users' => $avg_users
		));
		get_template_part(invetex_get_file_slug('templates/_parts/reviews-block.php'));


		// Prepare args for all rest template parts
		// This parts not pop args from storage!
		invetex_template_set_args('single-footer', array(
			'post_options' => $post_options,
			'post_data' => $post_data
		));


		// Post content
		if ($post_data['post_protected']) { 
			echo trim($post_data['post_excerpt']);
			echo get_the_password_form(); 
		} else {
			if (!invetex_storage_empty('reviews_markup') && invetex_strpos($post_data['post_content'], invetex_get_reviews_placeholder())===false) 
				$post_data['post_content'] = invetex_sc_reviews(array()) . ($post_data['post_content']);
			echo trim(invetex_gap_wrapper(invetex_reviews_wrapper($post_data['post_content'])));
			wp_link_pages( array( 
				'before' => '<nav class="pagination_single"><span class="pager_pages">' . esc_html__( 'Pages:', 'invetex' ) . '</span>', 
				'after' => '</nav>',
				'link_before' => '<span class="pager_numbers">',
				'link_after' => '</span>'
				)
			);

			if((invetex_get_custom_option('show_post_tags') == 'yes' && !empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links)) || !invetex_param_is_off(invetex_get_custom_option("show_share"))){
				?>
				<div class="single_footer_info">
					<?php
					get_template_part(invetex_get_file_slug('templates/_parts/share.php'));
					if ( invetex_get_custom_option('show_post_tags') == 'yes' && !empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links)) {
						?>
						<div class="post_info_bottom">
							<span class="post_info_item post_info_tags"><span class="icon icon-lightbulb-light"></span><?php echo join(', ', $post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links); ?></span>
						</div>
						<?php
					}
					?>
					<div class="post_info_bottom">
						<a class="post_counters_item post_counters_comments icon-comment-light" title="<?php echo esc_attr( sprintf(__('Comments - %s', 'invetex'), get_comments_number()) ); ?>" href="<?php echo esc_url(get_comments_link()); ?>"><span class="post_counters_number"><?php echo trim(get_comments_number()); ?></span><?php echo ' '.esc_html__('Comments', 'invetex'); ?></a>
					</div>
				</div>
				<?php
			}

		}

		if (!$post_data['post_protected'] && invetex_get_custom_option('show_post_navi') == 'yes') { ?>
			<section class="single_post_nav">
					<div class="post_nav">
						<?php
						$cur = get_post();
						$args = array(
							'post_type' => $post_data['post_type'],
							'posts_per_page' => -1,
							'post_status' => current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish',
							'ignore_sticky_posts' => true
						);
						$args = invetex_query_add_posts_and_cats($args, '', $post_data['post_type'],
							!empty($post_data['post_terms'][$post_data['post_taxonomy']]->terms_ids)
								? join(',', $post_data['post_terms'][$post_data['post_taxonomy']]->terms_ids)
								: '', $post_data['post_taxonomy']);
						$args = invetex_query_add_sort_order($args);
						$q = new WP_Query( $args );
						$prev = $next = null;
						$found = false;
						while ( $q->have_posts() ) { $q->the_post();
							if (!$found) {
								if ($cur->ID == get_the_ID())
									$found = true;
								else {
									$prev = get_post(get_the_ID());
								}
							} else {
								$next = get_post(get_the_ID());
								break;
							}
						}
						wp_reset_postdata();
						if ( $prev ) {
							$link = get_permalink($prev->ID).'#top_of_page';
							$desc = $prev->post_title;
							?>
							<a class="post_nav_item post_nav_prev" href="<?php echo esc_url($link); ?>">
								<span class="post_nav_arrow"></span>
								<span class="post_nav_info">
									<span class="post_nav_info_title"><?php echo trim($desc); ?></span>
								</span>
								<div class="post_info">
									<?php
									$post_date = apply_filters('invetex_filter_post_date', get_the_date('Y-m-d H:i:s',$prev->ID), $prev->ID);
									$post_date_diff = invetex_get_date_or_difference($post_date);
									?>
									<span class="post_info_item">
											<span class="post_counters_item post_info_posted"><?php	echo esc_html($post_date_diff); ?></span>
									</span>
									<span class="post_info_item">
										<span class="post_counters_number icon-comment-light"><?php echo trim(get_comments_number($prev->ID)).esc_html__(' Comments', 'invetex'); ?></span>
									</span>
								</div>
							</a>
						<?php
						}
						if ( $next ) {
							$link = get_permalink( $next->ID ).'#top_of_page';
							$desc = $next->post_title;
							?>
							<a class="post_nav_item post_nav_next" href="<?php echo esc_url($link); ?>">
								<span class="post_nav_arrow"></span>
								<span class="post_nav_info">
									<span class="post_nav_info_title"><?php echo trim($desc); ?></span>
								</span>
								<div class="post_info">
									<?php
									$post_date = apply_filters('invetex_filter_post_date', get_the_date('Y-m-d H:i:s',$next->ID), $next->ID);
									$post_date_diff = invetex_get_date_or_difference($post_date);
									?>
									<span class="post_info_item">
											<span class="post_counters_item post_info_posted"><?php	echo esc_html($post_date_diff); ?></span>
									</span>
									<span class="post_info_item">
										<span class="post_counters_number icon-comment-light"><?php echo trim(get_comments_number($next->ID)).esc_html__(' Comments', 'invetex'); ?></span>
									</span>
								</div>
							</a>
						<?php
						}
						?>
					</div>
			</section>
		<?php
		}



		if (!$post_data['post_protected'] && $post_data['post_edit_enable']) {
			get_template_part(invetex_get_file_slug('templates/_parts/editor-area.php'));
		}
			
		invetex_close_wrapper();	// .post_content
			
		if (!$post_data['post_protected']) {
			get_template_part(invetex_get_file_slug('templates/_parts/author-info.php'));
		}

		$sidebar_present = !invetex_param_is_off(invetex_get_custom_option('show_sidebar_main'));
		if (!$sidebar_present) invetex_close_wrapper();	// .post_item
		get_template_part(invetex_get_file_slug('templates/_parts/related-posts.php'));
		if ($sidebar_present) invetex_close_wrapper();		// .post_item

		// Show comments
		if ( !$post_data['post_protected'] && (comments_open() || get_comments_number() != 0) ) {
			comments_template();
		}

		// Manually pop args from storage
		// after all single footer templates
		invetex_template_get_args('single-footer');
	}
}
?>