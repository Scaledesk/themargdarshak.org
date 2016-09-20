<?php
/**
 * Single post
 */
get_header(); 

$single_style = invetex_storage_get('single_style');
if (empty($single_style)) $single_style = invetex_get_custom_option('single_style');

while ( have_posts() ) { the_post();
	invetex_show_post_layout(
		array(
			'layout' => $single_style,
			'sidebar' => !invetex_param_is_off(invetex_get_custom_option('show_sidebar_main')),
			'content' => invetex_get_template_property($single_style, 'need_content'),
			'terms_list' => invetex_get_template_property($single_style, 'need_terms')
		)
	);
}

get_footer();
?>