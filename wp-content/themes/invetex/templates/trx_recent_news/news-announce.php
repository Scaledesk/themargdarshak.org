<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'invetex_template_news_announce_theme_setup' ) ) {
	add_action( 'invetex_action_before_init_theme', 'invetex_template_news_announce_theme_setup', 1 );
	function invetex_template_news_announce_theme_setup() {
		invetex_add_template(array(
			'layout' => 'news-announce',
			'template' => 'news-announce',
			'mode'   => 'news',
			'title'  => esc_html__('Recent News /Style Announce/', 'invetex'),
			'thumb_title'  => esc_html__('Medium image Announce (crop)', 'invetex'),
			'w'		 => 370,
			'h'		 => 334
		));
	}
}

// Template output
if ( !function_exists( 'invetex_template_news_announce_output' ) ) {
	function invetex_template_news_announce_output($post_options, $post_data) {
		$style = $post_options['layout'];
		$number = $post_options['number'];
		$count = $post_options['posts_on_page'];
		$post_format = $post_data['post_format'];
		$readmore = esc_html__('Read More','invetex');
		$grid = array(
			array('full'),
			array('full', 'full'),
			array('big', 'medium', 'big right'),
			array('big', 'medium', 'big right', 'full'),
			array('big', 'medium', 'big right', 'full', 'full'),
			array('big', 'medium', 'big right', 'big r', 'medium l', 'big right r'),
			array('big', 'medium', 'big right', 'big r', 'medium l', 'big right r', 'full'),
			array('big', 'medium', 'big right', 'big r', 'medium l', 'big right r', 'full', 'full'),
			array('big', 'medium', 'big right', 'big r', 'medium l', 'big right r', 'big', 'medium', 'big right')
		);
		if($count > 9) $count = 9;
		$thumb_slug = $grid[$count-$number >= 9 ? 9 : ($count-1)%9][($number-1)%9];
		?><article id="post-<?php echo esc_html($post_data['post_id']); ?>"
			<?php post_class( 'post_item post_layout_'.esc_attr($style)
							.' post_format_'.esc_attr($post_format)
							.' post_size_'.esc_attr($thumb_slug)
							); ?>
			>
			<?php if ($post_data['post_flags']['sticky']) {	?>
				<span class="sticky_label"></span>
			<?php } ?>
			<?php if ($post_data['post_gallery'] || $post_data['post_thumb']){ ?>
				<div class="post_featured">
					<?php
					if ($post_data['post_gallery']){
						echo $post_data['post_gallery'];
					}
					else if ($post_data['post_thumb']) {
						$post_data['post_video'] = $post_data['post_audio'] = $post_data['post_gallery'] = '';
						invetex_template_set_args('post-featured', array(
							'post_options' => $post_options,
							'post_data' => $post_data
						));
						get_template_part(invetex_get_file_slug('templates/_parts/post-featured.php'));
					}
					?>
				</div>
			<?php } ?>
			<div class="post_des">
				<?php if( !in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote')) && isset($post_data['post_terms']['category']->terms_links)) { ?>
					<div class="cat_post_info"><span class="post_categories"><?php echo join(' ', $post_data['post_terms']['category']->terms_links); ?></span></div>
				<?php } ?>
				<?php if(!in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote'))) { ?>
					<h5 class="post_title entry-title"><a href="<?php echo esc_url($post_data['post_link']); ?>" rel="bookmark"><?php echo trim($post_data['post_title']); ?></a></h5>
						<div class="post_info">
							<span class="post_meta_date"><?php echo esc_html($post_data['post_date']); ?></span>
							<span class="post_meta_comments icon-comment-light"><?php echo trim($post_data['post_comments']).esc_attr__(' Comments', 'invetex'); ?></span>
						</div>
				<?php }
				if ($post_data['post_excerpt']) {
					echo in_array($post_data['post_format'], array('quote', 'link', 'chat', 'aside', 'status'))
						? $post_data['post_excerpt']
						: wpautop(invetex_strshort($post_data['post_excerpt'], isset($post_options['descr'])
								? $post_options['descr']
								: 85
							)
						);
				}
				if(!in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote')) && $post_data['post_link']) { ?>
					<a class="readmore" href="<?php echo esc_url($post_data['post_link']); ?>"><?php echo trim($readmore); ?></a><?php
				}
				?>
			</div>

		</article>
		<?php
	}
}
?>