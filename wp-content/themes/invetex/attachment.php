<?php
/**
 * Attachment page
 */
get_header(); 

while ( have_posts() ) { the_post();

	// Move invetex_set_post_views to the javascript - counter will work under cache system
	if (invetex_get_custom_option('use_ajax_views_counter')=='no') {
		invetex_set_post_views(get_the_ID());
	}

	invetex_show_post_layout(
		array(
			'layout' => 'attachment',
			'sidebar' => !invetex_param_is_off(invetex_get_custom_option('show_sidebar_main'))
		)
	);

}

get_footer();
?>