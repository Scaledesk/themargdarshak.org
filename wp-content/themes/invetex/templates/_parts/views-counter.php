<?php 
if (is_singular()) {
	if (invetex_get_theme_option('use_ajax_views_counter')=='yes') {
		invetex_storage_set_array('js_vars', 'ajax_views_counter', array(
			'post_id' => get_the_ID(),
			'post_views' => invetex_get_post_views(get_the_ID())
		));
	} else
		invetex_set_post_views(get_the_ID());
}
?>