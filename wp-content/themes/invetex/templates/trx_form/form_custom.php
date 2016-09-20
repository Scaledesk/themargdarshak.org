<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'invetex_template_form_custom_theme_setup' ) ) {
	add_action( 'invetex_action_before_init_theme', 'invetex_template_form_custom_theme_setup', 1 );
	function invetex_template_form_custom_theme_setup() {
		invetex_add_template(array(
			'layout' => 'form_custom',
			'mode'   => 'forms',
			'title'  => esc_html__('Custom Form', 'invetex')
			));
	}
}

// Template output
if ( !function_exists( 'invetex_template_form_custom_output' ) ) {
	function invetex_template_form_custom_output($post_options, $post_data) {
		$form_style = invetex_get_theme_option('input_hover');
		?>
		<form <?php echo !empty($post_options['id']) ? ' id="'.esc_attr($post_options['id']).'_form"' : ''; ?>
			class="sc_input_hover_<?php echo esc_attr($form_style); ?>"
			data-formtype="<?php echo esc_attr($post_options['layout']); ?>" 
			method="post" 
			action="<?php echo esc_url($post_options['action'] ? $post_options['action'] : admin_url('admin-ajax.php')); ?>">
			<?php
			invetex_sc_form_show_fields($post_options['fields']);
			echo trim($post_options['content']);
			?>
			<div class="result sc_infobox"></div>
		</form>
		<?php
	}
}
?>