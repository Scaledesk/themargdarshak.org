<?php
/* Revolution Slider support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('invetex_revslider_theme_setup')) {
	add_action( 'invetex_action_before_init_theme', 'invetex_revslider_theme_setup', 1 );
	function invetex_revslider_theme_setup() {
		if (invetex_exists_revslider()) {
			add_filter( 'invetex_filter_list_sliders',					'invetex_revslider_list_sliders' );
			add_filter( 'invetex_filter_shortcodes_params',			'invetex_revslider_shortcodes_params' );
			add_filter( 'invetex_filter_theme_options_params',			'invetex_revslider_theme_options_params' );
			if (is_admin()) {
				add_action( 'invetex_action_importer_params',			'invetex_revslider_importer_show_params', 10, 1 );
				add_action( 'invetex_action_importer_clear_tables',	'invetex_revslider_importer_clear_tables', 10, 2 );
				add_action( 'invetex_action_importer_import',			'invetex_revslider_importer_import', 10, 2 );
				add_action( 'invetex_action_importer_import_fields',	'invetex_revslider_importer_import_fields', 10, 1 );
			}
		}
		if (is_admin()) {
			add_filter( 'invetex_filter_importer_required_plugins',	'invetex_revslider_importer_required_plugins', 10, 2 );
			add_filter( 'invetex_filter_required_plugins',				'invetex_revslider_required_plugins' );
		}
	}
}

if ( !function_exists( 'invetex_revslider_settings_theme_setup2' ) ) {
	add_action( 'invetex_action_before_init_theme', 'invetex_revslider_settings_theme_setup2', 3 );
	function invetex_revslider_settings_theme_setup2() {
		if (invetex_exists_revslider()) {

			// Add Revslider specific options in the Theme Options
			invetex_storage_set_array_after('options', 'slider_engine', "slider_alias", array(
				"title" => esc_html__('Revolution Slider: Select slider',  'invetex'),
				"desc" => wp_kses_data( __("Select slider to show (if engine=revo in the field above)", 'invetex') ),
				"override" => "category,services_group,page",
				"dependency" => array(
					'show_slider' => array('yes'),
					'slider_engine' => array('revo')
				),
				"std" => "",
				"options" => invetex_get_options_param('list_revo_sliders'),
				"type" => "select"
				)
			);

		}
	}
}

// Check if RevSlider installed and activated
if ( !function_exists( 'invetex_exists_revslider' ) ) {
	function invetex_exists_revslider() {
		return function_exists('rev_slider_shortcode');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'invetex_revslider_required_plugins' ) ) {
	//add_filter('invetex_filter_required_plugins',	'invetex_revslider_required_plugins');
	function invetex_revslider_required_plugins($list=array()) {
		if (in_array('revslider', invetex_storage_get('required_plugins'))) {
			$path = invetex_get_file_dir('plugins/install/revslider.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> 'Revolution Slider',
					'slug' 		=> 'revslider',
					'source'	=> $path,
					'required' 	=> false
					);
			}
		}
		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check RevSlider in the required plugins
if ( !function_exists( 'invetex_revslider_importer_required_plugins' ) ) {
	//add_filter( 'invetex_filter_importer_required_plugins',	'invetex_revslider_importer_required_plugins', 10, 2 );
	function invetex_revslider_importer_required_plugins($not_installed='', $list='') {
		//if (in_array('revslider', invetex_storage_get('required_plugins')) && !invetex_exists_revslider() )
		if (invetex_strpos($list, 'revslider')!==false && !invetex_exists_revslider() )
			$not_installed .= '<br>Revolution Slider';
		return $not_installed;
	}
}

// Add checkbox to the one-click importer
if ( !function_exists( 'invetex_revslider_importer_show_params' ) ) {
	//add_action( 'invetex_action_importer_params',	'invetex_revslider_importer_show_params', 10, 1 );
	function invetex_revslider_importer_show_params($importer) {
		if (!empty($importer->options['files'][$importer->options['demo_type']]['file_with_revsliders'])) {
			?>
			<input type="checkbox" <?php echo in_array('revslider', invetex_storage_get('required_plugins')) && $importer->options['plugins_initial_state'] 
											? 'checked="checked"' 
											: ''; ?> value="1" name="import_revslider" id="import_revslider" /> <label for="import_revslider"><?php esc_html_e('Import Revolution Sliders', 'invetex'); ?></label><br>
			<?php
		}
	}
}

// Clear tables
if ( !function_exists( 'invetex_revslider_importer_clear_tables' ) ) {
	//add_action( 'invetex_action_importer_clear_tables',	'invetex_revslider_importer_clear_tables', 10, 2 );
	function invetex_revslider_importer_clear_tables($importer, $clear_tables) {
		if (invetex_strpos($clear_tables, 'revslider')!==false && $importer->last_slider==0) {
			if ($importer->options['debug']) dfl(esc_html__('Clear Revolution Slider tables', 'invetex'));
			global $wpdb;
			$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->prefix) . "revslider_sliders");
			if ( is_wp_error( $res ) ) dfl( esc_html__( 'Failed truncate table "revslider_sliders".', 'invetex' ) . ' ' . ($res->get_error_message()) );
			$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->prefix) . "revslider_slides");
			if ( is_wp_error( $res ) ) dfl( esc_html__( 'Failed truncate table "revslider_slides".', 'invetex' ) . ' ' . ($res->get_error_message()) );
			$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->prefix) . "revslider_static_slides");
			if ( is_wp_error( $res ) ) dfl( esc_html__( 'Failed truncate table "revslider_static_slides".', 'invetex' ) . ' ' . ($res->get_error_message()) );
		}
	}
}

// Import posts
if ( !function_exists( 'invetex_revslider_importer_import' ) ) {
	//add_action( 'invetex_action_importer_import',	'invetex_revslider_importer_import', 10, 2 );
	function invetex_revslider_importer_import($importer, $action) {
		if ( $action == 'import_revslider' && !empty($importer->options['files'][$importer->options['demo_type']]['file_with_revsliders']) ) {
			if (file_exists(WP_PLUGIN_DIR . '/revslider/revslider.php')) {
				require_once WP_PLUGIN_DIR . '/revslider/revslider.php';
				if ($importer->options['debug']) dfl( esc_html__('Import Revolution sliders', 'invetex') );
				// Process next slider
				$slider = new RevSlider();
				$sliders = $importer->options['files'][$importer->options['demo_type']]['file_with_revsliders'];
				$attempt = !empty($_POST['attempt']) ? (int) $_POST['attempt']+1 : 1;
				for ($i=0; $i<count($sliders); $i++) {
					if ($i+1 <= $importer->last_slider) {
						if ($importer->options['debug']) 
							dfl( sprintf(esc_html__('Skip previously loaded file: %s', 'invetex'), basename($sliders[$i])) );
						continue;
					}
					if ($importer->options['debug'])
						dfl( sprintf(esc_html__('Process slider "%s". Attempt %d.', 'invetex'), basename($sliders[$i]), $attempt) );
					$need_del = false;
					if (!is_array($_FILES)) $_FILES = array();
					if (substr($sliders[$i], 0, 5)=='http:' || substr($sliders[$i], 0, 6)=='https:') {
						$tm = round( 0.9 * max(30, ini_get('max_execution_time')));
						$response = download_url($sliders[$i], $tm);
						if (is_string($response)) {
							$_FILES["import_file"] = array("tmp_name" => $response);
							$need_del = true;
						}
					} else
						$_FILES["import_file"] = array("tmp_name" => invetex_get_file_dir($sliders[$i]));
					if (!empty($_FILES["import_file"]["tmp_name"])) {
						$response = $slider->importSliderFromPost();
						if ($need_del && file_exists($_FILES["import_file"]["tmp_name"]))
							unlink($_FILES["import_file"]["tmp_name"]);
					} else {
						$response = array("success" => false);
					}
					if ($response["success"] == false) {
						$msg = sprintf(esc_html__('Revolution Slider "%s" import error. Attempt %d.', 'invetex'), basename($sliders[$i]), $attempt);
						if ($attempt < 3) {
							$importer->response['attempt'] = $attempt;
						} else {
							unset($importer->response['attempt']);
							$importer->response['error'] = $msg;
						}
						if ($importer->options['debug'])  {
							dfl( $msg );
							dfo( $response );
						}
					} else {
						unset($importer->response['attempt']);
						if ($importer->options['debug']) 
							dfl( sprintf(esc_html__('Slider "%s" imported', 'invetex'), basename($sliders[$i])) );
					}
					break;
				}
				// Write last slider into log
				$num = $i + (empty($importer->response['attempt']) ? 1 : 0);
				invetex_fpc($importer->import_log, $num < count($sliders) ? '0|100|'.$num : '');
				$importer->response['result'] = min(100, round($num / count($sliders) * 100));
			} else {
				if ($importer->options['debug']) 
					dfl( sprintf(esc_html__('Can not locate plugin Revolution Slider: %s', 'invetex'), WP_PLUGIN_DIR.'/revslider/revslider.php') );
			}
		}
	}
}

// Display import progress
if ( !function_exists( 'invetex_revslider_importer_import_fields' ) ) {
	//add_action( 'invetex_action_importer_import_fields',	'invetex_revslider_importer_import_fields', 10, 1 );
	function invetex_revslider_importer_import_fields($importer) {
		?>
		<tr class="import_revslider">
			<td class="import_progress_item"><?php esc_html_e('Revolution Slider', 'invetex'); ?></td>
			<td class="import_progress_status"></td>
		</tr>
		<?php
	}
}


// Lists
//------------------------------------------------------------------------

// Add RevSlider in the sliders list, prepended inherit (if need)
if ( !function_exists( 'invetex_revslider_list_sliders' ) ) {
	//add_filter( 'invetex_filter_list_sliders',					'invetex_revslider_list_sliders' );
	function invetex_revslider_list_sliders($list=array()) {
		$list["revo"] = esc_html__("Layer slider (Revolution)", 'invetex');
		return $list;
	}
}

// Return Revo Sliders list, prepended inherit (if need)
if ( !function_exists( 'invetex_get_list_revo_sliders' ) ) {
	function invetex_get_list_revo_sliders($prepend_inherit=false) {
		if (($list = invetex_storage_get('list_revo_sliders'))=='') {
			$list = array();
			if (invetex_exists_revslider()) {
				global $wpdb;
				$rows = $wpdb->get_results( "SELECT alias, title FROM " . esc_sql($wpdb->prefix) . "revslider_sliders" );
				if (is_array($rows) && count($rows) > 0) {
					foreach ($rows as $row) {
						$list[$row->alias] = $row->title;
					}
				}
			}
			$list = apply_filters('invetex_filter_list_revo_sliders', $list);
			if (invetex_get_theme_setting('use_list_cache')) invetex_storage_set('list_revo_sliders', $list);
		}
		return $prepend_inherit ? invetex_array_merge(array('inherit' => esc_html__("Inherit", 'invetex')), $list) : $list;
	}
}

// Add RevSlider in the shortcodes params
if ( !function_exists( 'invetex_revslider_shortcodes_params' ) ) {
	//add_filter( 'invetex_filter_shortcodes_params',			'invetex_revslider_shortcodes_params' );
	function invetex_revslider_shortcodes_params($list=array()) {
		$list["revo_sliders"] = invetex_get_list_revo_sliders();
		return $list;
	}
}

// Add RevSlider in the Theme Options params
if ( !function_exists( 'invetex_revslider_theme_options_params' ) ) {
	//add_filter( 'invetex_filter_theme_options_params',			'invetex_revslider_theme_options_params' );
	function invetex_revslider_theme_options_params($list=array()) {
		$list["list_revo_sliders"] = array('$invetex_get_list_revo_sliders' => '');
		return $list;
	}
}
?>