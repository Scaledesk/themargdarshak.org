<?php
/**
 * FormForAll :: Admin/Back-office features
 */

add_action('init', 'formforall_plugin_init');
add_action('admin_menu', 'formforall_admin_menu');


/*******************************************************/
/* POST EDIT */
/*******************************************************/
/**
 * Init to add the button
 */
function formforall_plugin_init() {
    // Controls
    if (!current_user_can('edit_posts') && !current_user_can('edit_pages') && get_user_option('rich_editing') == 'true') return;
    // Languages
    load_plugin_textdomain('formforall', false, 'formforall/languages');
    // Links to TinyMCE
    add_filter('mce_external_plugins', 'formforall_register_tinymce_plugin');
    add_filter('mce_buttons', 'formforall_add_tinymce_button');
    add_filter('tiny_mce_before_init', 'formforall_tinymce_settings');
}

/**
 * JS registration
 */
function formforall_register_tinymce_plugin($plugin_array) {
	if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
		$plugin_array['formforall'] = plugins_url( basename( __DIR__ ) . '/js/formforall.js' );
	} else {
		$plugin_array['formforall'] = plugins_url( basename( dirname(__FILE__) ) . '/js/formforall.js' );
	}
    return $plugin_array;
}

/**
 * Button registration
 */
function formforall_add_tinymce_button($buttons) {
    //Add the button ID to the $button array
    $buttons[] = 'formforall';
    return $buttons;
}

/**
 * Send parameters to tinymce
 */
function formforall_tinymce_settings($settings) {
    $option = get_option('formforall');
    
    // Current params
    if (!isset($option['user_id'])) $option['user_id'] = '';
    $settings['formforall_user_id'] = $option['user_id'];
    if (!isset($option['api_key'])) $option['api_key'] = '';
    $settings['formforall_api_key'] = $option['api_key'];
    
    // Translations
    $settings['formforall_trans_form'] = __("Form", 'formforall');
    $settings['formforall_trans_addform'] = __("Add a form", 'formforall');
    $settings['formforall_trans_errorparam'] = __("Parameters have not been set correctly", 'formforall');
    
    return $settings;
}




/*******************************************************/
/* SETTINGS */
/*******************************************************/
/**
 * Menu item
 */
function formforall_admin_menu() {
    add_options_page('FormForAll', 'FormForAll', 'manage_options', 'formforall-settings', 'formforall_admin_page_display');
}

/**
 * Page display
 */
function formforall_admin_page_display() {
    // Controls
    if (!current_user_can('manage_options')) wp_die( __('You do not have sufficient permissions to access this page.') );
    
    $option_name = 'formforall';
    $buffer = '';
    
    // Save
    if (isset($_POST['formforall_api_key'])) {
	$option['user_id'] = esc_html($_POST['formforall_user_id']);
	$option['api_key'] = esc_html($_POST['formforall_api_key']);	
	update_option($option_name, $option);
	$buffer .= '<div class="updated"><p><strong>'.__('Settings saved.', 'menu-test' ).'</strong></p></div>';
    }
    
    // Get options
    $option = get_option($option_name);
    if (!isset($option['user_id'])) $option['user_id'] = '';
    if (!isset($option['api_key'])) $option['api_key'] = '';
	
    // Display
    $buffer .= '<h2>' . __('FormForAll configuration', 'formforall') . '</h2>';
    $buffer .= '<div id="poststuff" style="padding-top:10px; padding-right:15px; position:relative;">';
    $buffer .= '<div class="postbox">';
    $buffer .= '<form name="form1" method="post" action="">';
    $buffer .= '<h3>' . __("Parameters", 'formforall' ) . '</h3>';
    
    $buffer .= '<div style="float: left; padding: 10px 5px; width: 90px"><label for="formforall_user_id">' . __("User Id", 'formforall' ) . '</label></div>';
    $buffer .= '<div style="float: left; padding: 5px;"><input type="text" name="formforall_user_id" value="' . $option['user_id'] . '" size="50"></div>';
    $buffer .= '<div style="clear: both"></div>';
    
    $buffer .= '<div style="float: left; padding: 10px 5px; width: 90px"><label for="formforall_api_key">' . __("User API key", 'formforall' ) . '</label></div>';
    $buffer .= '<div style="float: left; padding: 5px;"><input type="text" name="formforall_api_key" value="' . $option['api_key'] . '" size="50"></div>';
    $buffer .= '<div style="float: left; padding: 10px;"><a href="http://www.formforall.com" class="submit" target="_blank">' . __("To get your User API key", 'formforall' ) . '</a></div>';
    $buffer .= '<div style="clear: both"></div>';
    
    $buffer .= '<p class="submit" style="padding-left: 10px;"><input type="submit" name="Submit" class="button-primary" value="' . esc_attr('Save Changes') . '" /></p>';
    $buffer .= '</form>';
    
    echo $buffer;
}

?>
