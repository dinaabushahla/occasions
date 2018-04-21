<?php
// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


// Theme init
if (!function_exists('unicaevents_importer_theme_setup')) {
	add_action( 'unicaevents_action_after_init_theme', 'unicaevents_importer_theme_setup' );		// Fire this action after load theme options
	function unicaevents_importer_theme_setup() {
		if (is_admin() && current_user_can('import') && unicaevents_get_theme_option('admin_dummy_data')=='yes') {
			new unicaevents_dummy_data_importer();
		}
	}
}

class unicaevents_dummy_data_importer {

	// Theme specific settings
	var $options = array(
		'debug'					=> false,						// Enable debug output
		'posts_at_once'			=> 10,							// How many posts imported at one AJAX call
		'data_type'				=> 'vc',						// Default dummy data type
		'file_with_content'		=> array(
			'no_vc'				=> 'demo/dummy_data.xml',		// Name of the file with demo content without VC wrappers
			'vc'				=> 'demo/dummy_data_vc.xml'		// Name of the file with demo content for Visual Composer
			),
		'file_with_mods'		=> 'demo/theme_mods.txt',		// Name of the file with theme mods
		'file_with_options'		=> 'demo/theme_options.txt',	// Name of the file with theme options
		'file_with_templates'	=> 'demo/templates_options.txt',// Name of the file with templates options
		'file_with_widgets'		=> 'demo/widgets.txt',			// Name of the file with widgets data
		'uploads_folder'		=> 'imports',					// Folder with images on demo server
		'domain_dev'			=> '',							// Domain on developer's server. 								MUST BE SET IN THEME!
		'domain_demo'			=> '',							// Domain on demo-server.										MUST BE SET IN THEME!
		'taxonomies'			=> array(),						// List of required taxonomies: 'post_type' => 'taxonomy', ...	MUST BE SET OR CHANGED IN THEME!
		'required_plugins'		=> array(						// Required plugins slugs. 										MUST BE SET OR CHANGED IN THEME!
			'trx_utils'
		),
		'additional_options'	=> array(						// Additional options slugs (for export plugins settings).		MUST BE SET OR CHANGED IN THEME!
			// WP
			'blogname',
			'blogdescription',
			'posts_per_page',
			'show_on_front',
			'page_on_front',
			'page_for_posts'
		)
	);

	var $error    = '';				// Error message
	var $success  = '';				// Success message
	var $result   = 0;				// Import posts percent (if break inside)
	
	var $last_slider = 0;			// Last imported slider number. 															MUST BE SET OR CHANGED IN THEME!

	var $export_mods = '';
	var $export_options = '';
	var $export_templates = '';
	var $export_widgets = '';
	var $uploads_url = '';
	var $uploads_dir = '';
	var $import_log = '';
	var $import_last_id = 0;
		
	var	$response = array(
			'action' => '',
			'error' => '',
			'result' => '100'
		);

	//-----------------------------------------------------------------------------------
	// Constuctor
	//-----------------------------------------------------------------------------------
	function __construct() {
	    $this->options = apply_filters('unicaevents_filter_importer_options', $this->options);
		$uploads_info = wp_upload_dir();
		$this->uploads_dir = $uploads_info['basedir'];
		$this->uploads_url = $uploads_info['baseurl'];
		if ($this->options['debug']) define('IMPORT_DEBUG', true);
		$this->import_log = unicaevents_get_file_dir('core/core.importer/importer.log');
		$log = explode('|', unicaevents_fgc($this->import_log));
		$this->import_last_id = (int) $log[0];
		$this->result = empty($log[1]) ? 0 : (int) $log[1];
		$this->last_slider = empty($log[2]) ? '' : $log[2];
		// Add menu item
		add_action('admin_menu', 					array($this, 'admin_menu_item'));
		// Add menu item
		add_action('admin_enqueue_scripts', 		array($this, 'admin_scripts'));
		// AJAX handler
		add_action('wp_ajax_unicaevents_importer_start_import',		array($this, 'importer'));
		add_action('wp_ajax_nopriv_unicaevents_importer_start_import',	array($this, 'importer'));
	}

	//-----------------------------------------------------------------------------------
	// Admin Interface
	//-----------------------------------------------------------------------------------
	
	// Add menu item
	function admin_menu_item() {
		if ( current_user_can( 'manage_options' ) ) {
			// Add in admin menu 'Theme Options'
			unicaevents_admin_add_menu_item('theme', array(
				'page_title' => esc_html__('Install Dummy Data', 'unicaevents'),
				'menu_title' => esc_html__('Install Dummy Data', 'unicaevents'),
				'capability' => 'manage_options',
				'menu_slug'  => 'trx_importer',
				'callback'   => array($this, 'build_page'),
				'icon'		 => ''
				)
			);
		}
	}
	
	// Add script
	function admin_scripts() {
		unicaevents_enqueue_style(  'unicaevents-importer-style',  unicaevents_get_file_url('core/core.importer/core.importer.css'), array(), null );
		unicaevents_enqueue_script( 'unicaevents-importer-script', unicaevents_get_file_url('core/core.importer/core.importer.js'), array('jquery'), null, true );	
	}
	
	
	//-----------------------------------------------------------------------------------
	// Build the Main Page
	//-----------------------------------------------------------------------------------
	function build_page() {
		// Check required plugins
		if (!$this->check_required_plugins()) {
			?>
			<div class="error">
				<h4><?php esc_html_e('UnicaEvents Importer', 'unicaevents'); ?></h4>
				<p><?php echo trim($this->error); ?></p>
			</div>
			<?php
			return;
		}
		// Export data
		global $UNICAEVENTS_GLOBALS;
		if ( isset($_POST['exporter_action']) ) {
			if ( !isset($_POST['nonce']) || $_POST['nonce']!=$UNICAEVENTS_GLOBALS['nonce'] )
				$this->error = esc_html__('Incorrect WP-nonce data! Operation canceled!', 'unicaevents');
			else
				$this->exporter();
		}
		?>

		<div class="trx_importer">
			<div class="trx_importer_section">
				<h2 class="trx_title"><?php esc_html_e('UnicaEvents Importer', 'unicaevents'); ?></h2>
				<p><b><?php esc_html_e('Attention! Important info:', 'unicaevents'); ?></b></p>
				<ol>
					<li><?php esc_html_e('Data import will replace all existing content - so you get a complete copy of our demo site', 'unicaevents'); ?></li>
					<li><?php esc_html_e('Data import can take a long time (sometimes more than 10 minutes) - please wait until the end of the procedure, do not navigate away from the page.', 'unicaevents'); ?></li>
					<li><?php esc_html_e('Web-servers set the time limit for the execution of php-scripts. Therefore, the import process will be split into parts. Upon completion of each part - the import will resume automatically!', 'unicaevents'); ?></li>
				</ol>

				<form id="trx_importer_form">

					<p><b><?php esc_html_e('Select the data to import:', 'unicaevents'); ?></b></p>

					<p>
					<?php
					$checked = 'checked="checked"';
					if (!empty($this->options['file_with_content']['vc']) && file_exists(unicaevents_get_file_dir($this->options['file_with_content']['vc']))) {
						?>
						<input type="radio" <?php echo ($this->options['data_type']=='vc' ? trim($checked) : ''); ?> value="vc" name="data_type" id="data_type_vc" /><label for="data_type_vc"><?php esc_html_e('Import data for edit in the Visual Composer', 'unicaevents'); ?></label><br>
						<?php
						if ($this->options['data_type']=='vc') $checked = '';
					}
					if (!empty($this->options['file_with_content']['no_vc']) && file_exists(unicaevents_get_file_dir($this->options['file_with_content']['no_vc']))) {
						?>
						<input type="radio" <?php echo ($this->options['data_type']=='no_vc' || $checked ? trim($checked) : ''); ?> value="no_vc" name="data_type" id="data_type_no_vc" /><label for="data_type_no_vc"><?php esc_html_e('Import data without Visual Composer wrappers', 'unicaevents'); ?></label>
						<?php
					}
					?>
					</p>

					<p>
					<input type="checkbox" checked="checked" value="1" name="import_posts" id="import_posts" /> <label for="import_posts"><?php esc_html_e('Import posts', 'unicaevents'); ?></label><br>
					<span class="import_posts_params">
						<input type="radio" checked="checked" value="1" name="fetch_attachments" id="fetch_attachments_1" /> <label for="fetch_attachments_1"><?php esc_html_e('Upload attachments from demo-server', 'unicaevents'); ?></label><br>
						<input type="radio" value="0" name="fetch_attachments" id="fetch_attachments_0" /> <label for="fetch_attachments_0"><?php esc_html_e('Leave existing attachments', 'unicaevents'); ?></label>
					</span>
					</p>

					<p>
					<input type="checkbox" checked="checked" value="1" name="import_tm" id="import_tm" /> <label for="import_tm"><?php esc_html_e('Import Theme Mods', 'unicaevents'); ?></label><br>
					<input type="checkbox" checked="checked" value="1" name="import_to" id="import_to" /> <label for="import_to"><?php esc_html_e('Import Theme Options', 'unicaevents'); ?></label><br>
					<input type="checkbox" checked="checked" value="1" name="import_tpl" id="import_tpl" /> <label for="import_tpl"><?php esc_html_e('Import Templates Options', 'unicaevents'); ?></label><br>
					<input type="checkbox" checked="checked" value="1" name="import_widgets" id="import_widgets" /> <label for="import_widgets"><?php esc_html_e('Import Widgets', 'unicaevents'); ?></label><br><br>

					<?php do_action('unicaevents_action_importer_params', $this); ?>
					</p>

					<div class="trx_buttons">
						<?php if ($this->import_last_id > 0 || !empty($this->last_slider)) { ?>
							<h4 class="trx_importer_complete"><?php sprintf(esc_html__('Import posts completed by %s', 'unicaevents'), $this->result.'%'); ?></h4>
							<input type="button" value="<?php
								if ($this->import_last_id > 0)
									printf(esc_html__('Continue import (from ID=%s)', 'unicaevents'), $this->import_last_id);
								else
									esc_html_e('Continue import sliders', 'unicaevents');
								?>" data-last_id="<?php echo esc_attr($this->import_last_id); ?>" data-last_slider="<?php echo esc_attr($this->last_slider); ?>">
							<input type="button" value="<?php esc_attr_e('Start import again', 'unicaevents'); ?>">
						<?php } else { ?>
							<input type="button" value="<?php esc_attr_e('Start import', 'unicaevents'); ?>">
						<?php } ?>
					</div>

				</form>
				
				<div id="trx_importer_progress" class="notice notice-info style_<?php echo esc_attr(unicaevents_get_theme_setting('admin_dummy_style')); ?>">
					<h4 class="trx_importer_progress_title"><?php esc_html_e('Import demo data', 'unicaevents'); ?></h4>
					<table border="0" cellpadding="4" style="margin-bottom:2em;">
					<tr class="import_posts">
						<td class="import_progress_item"><?php esc_html_e('Posts', 'unicaevents'); ?></td>
						<td class="import_progress_status"></td>
					</tr>
					<tr class="import_tm">
						<td class="import_progress_item"><?php esc_html_e('Theme Mods', 'unicaevents'); ?></td>
						<td class="import_progress_status"></td>
					</tr>
					<tr class="import_to">
						<td class="import_progress_item"><?php esc_html_e('Theme Options', 'unicaevents'); ?></td>
						<td class="import_progress_status"></td>
					</tr>
					<tr class="import_tpl">
						<td class="import_progress_item"><?php esc_html_e('Templates Options', 'unicaevents'); ?></td>
						<td class="import_progress_status"></td>
					</tr>
					<tr class="import_widgets">
						<td class="import_progress_item"><?php esc_html_e('Widgets', 'unicaevents'); ?></td>
						<td class="import_progress_status"></td>
					</tr>
					<?php do_action('unicaevents_action_importer_import_fields', $this); ?>
					</table>
					<h4 class="trx_importer_progress_complete"><?php esc_html_e('Congratulations! Data import complete!', 'unicaevents'); ?> <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('View site', 'unicaevents'); ?></a></h4>
				</div>
				
			</div>

			<div class="trx_exporter_section">
				<h2 class="trx_title"><?php esc_html_e('UnicaEvents Exporter', 'unicaevents'); ?></h2>
				<form id="trx_exporter_form" action="#" method="post">

					<input type="hidden" value="<?php echo esc_attr($UNICAEVENTS_GLOBALS['nonce']); ?>" name="nonce" />
					<input type="hidden" value="all" name="exporter_action" />

					<div class="trx_buttons">
						<?php if ($this->export_options!='') { ?>

							<table border="0" cellpadding="6">
							<tr>
								<th align="left"><?php esc_html_e('Theme Mods', 'unicaevents'); ?></th>
								<td><?php unicaevents_fpc(unicaevents_get_file_dir('core/core.importer/export/theme_mods.txt'), $this->export_mods); ?>
									<a download="theme_mods.txt" href="<?php echo esc_url(unicaevents_get_file_url('core/core.importer/export/theme_mods.txt')); ?>"><?php esc_html_e('Download', 'unicaevents'); ?></a>
								</td>
							</tr>
							<tr>
								<th align="left"><?php esc_html_e('Theme Options', 'unicaevents'); ?></th>
								<td><?php unicaevents_fpc(unicaevents_get_file_dir('core/core.importer/export/theme_options.txt'), $this->export_options); ?>
									<a download="theme_options.txt" href="<?php echo esc_url(unicaevents_get_file_url('core/core.importer/export/theme_options.txt')); ?>"><?php esc_html_e('Download', 'unicaevents'); ?></a>
								</td>
							</tr>
							<tr>
								<th align="left"><?php esc_html_e('Templates Options', 'unicaevents'); ?></th>
								<td><?php unicaevents_fpc(unicaevents_get_file_dir('core/core.importer/export/templates_options.txt'), $this->export_templates); ?>
									<a download="templates_options.txt" href="<?php echo esc_url(unicaevents_get_file_url('core/core.importer/export/templates_options.txt')); ?>"><?php esc_html_e('Download', 'unicaevents'); ?></a>
								</td>
							</tr>
							<tr>
								<th align="left"><?php esc_html_e('Widgets', 'unicaevents'); ?></th>
								<td><?php unicaevents_fpc(unicaevents_get_file_dir('core/core.importer/export/widgets.txt'), $this->export_widgets); ?>
									<a download="widgets.txt" href="<?php echo esc_url(unicaevents_get_file_url('core/core.importer/export/widgets.txt')); ?>"><?php esc_html_e('Download', 'unicaevents'); ?></a>
								</td>
							</tr>
							
							<?php do_action('unicaevents_action_importer_export_fields', $this); ?>

							</table>

						<?php } else { ?>

							<input type="submit" value="<?php esc_attr_e('Export Theme Options', 'unicaevents'); ?>">

						<?php } ?>
					</div>

				</form>
			</div>
		</div>
		<?php
	}

	// Check for required plugings
	function check_required_plugins() {
		global $UNICAEVENTS_GLOBALS;
		$not_installed = '';
		if (in_array('trx_utils', $UNICAEVENTS_GLOBALS['required_plugins']) && !defined('TRX_UTILS_VERSION') )
			$not_installed .= 'UnicaEvents Utilities';
		$not_installed = apply_filters('unicaevents_filter_importer_required_plugins', $not_installed);
		if ($not_installed) {
			$this->error = '<b>'.esc_html__('Attention! For correct installation of the demo data, you must install and activate the following plugins: ', 'unicaevents').'</b><br>'.($not_installed);
			return false;
		}
		return true;
	}
	
	
	//-----------------------------------------------------------------------------------
	// Export dummy data
	//-----------------------------------------------------------------------------------
	function exporter() {
		global $wpdb, $UNICAEVENTS_GLOBALS;
		$suppress = $wpdb->suppress_errors();

		// Export theme mods
		$this->export_mods = serialize($this->prepare_domains(get_theme_mods()));

		// Export theme, templates and categories options and VC templates
		$rows = $wpdb->get_results( "SELECT option_name, option_value FROM " . esc_sql($wpdb->options) . " WHERE option_name LIKE '" . esc_sql($UNICAEVENTS_GLOBALS['theme_slug']) . "_options%'" );
		$options = array();
		if (is_array($rows) && count($rows) > 0) {
			foreach ($rows as $row) {
				$options[$row->option_name] = $this->prepare_uploads(unicaevents_unserialize($row->option_value));
			}
		}
		// Export additional options
		if (is_array($this->options['additional_options']) && count($this->options['additional_options']) > 0) {
			foreach ($this->options['additional_options'] as $opt) {
				$rows = $wpdb->get_results( "SELECT option_name, option_value FROM " . esc_sql($wpdb->options) . " WHERE option_name LIKE '" . esc_sql($opt) . "'" );
				if (is_array($rows) && count($rows) > 0) {
					foreach ($rows as $row) {
						$options[$row->option_name] = unicaevents_unserialize($row->option_value);
					}
				}
			}
		}
		$this->export_options = serialize($this->prepare_domains($options));

		// Export templates options
		$rows = $wpdb->get_results( "SELECT option_name, option_value FROM " . esc_sql($wpdb->options) . " WHERE option_name LIKE '".esc_sql($UNICAEVENTS_GLOBALS['theme_slug'])."_options_template_%'" );
		$options = array();
		if (is_array($rows) && count($rows) > 0) {
			foreach ($rows as $row) {
				$options[$row->option_name] = $this->prepare_uploads(unicaevents_unserialize($row->option_value));
			}
		}
		$this->export_templates = serialize($this->prepare_domains($options));

		// Export widgets
		$rows = $wpdb->get_results( "SELECT option_name, option_value FROM " . esc_sql($wpdb->options) . " WHERE option_name = 'sidebars_widgets' OR option_name LIKE 'widget_%'" );
		$options = array();
		if (is_array($rows) && count($rows) > 0) {
			foreach ($rows as $row) {
				$options[$row->option_name] = $this->prepare_uploads(unicaevents_unserialize($row->option_value));
			}
		}
		$this->export_widgets = serialize($this->prepare_domains($options));

		// Export Theme specific post types
		do_action('unicaevents_action_importer_export', $this);

		$wpdb->suppress_errors( $suppress );
	}
	
	
	//-----------------------------------------------------------------------------------
	// Export specified table
	//-----------------------------------------------------------------------------------
	function export_dump($table) {
		global $wpdb;
		$rows = array();
		if ( count($wpdb->get_results( "SHOW TABLES LIKE '".esc_sql($wpdb->prefix . $table)."'", ARRAY_A )) == 1 ) {
			$rows = $wpdb->get_results( "SELECT * FROM ".esc_sql($wpdb->prefix . $table), ARRAY_A );
		}
		return $rows;
	}
	
	
	//-----------------------------------------------------------------------------------
	// Import dummy data
	//-----------------------------------------------------------------------------------
	function importer() {

		global $UNICAEVENTS_GLOBALS;

		if ($this->options['debug']) dfl(esc_html__('AJAX handler for importer', 'unicaevents'));

		if ( !isset($_POST['importer_action']) || !isset($_POST['ajax_nonce']) || $_POST['ajax_nonce']!=$UNICAEVENTS_GLOBALS['ajax_nonce'] ) {
			die();
		}

		$action = $this->response['action'] = $_POST['importer_action'];

		if ($this->options['debug']) dfl( sprintf(esc_html__('Dispatch action: %s', 'unicaevents'), $action) );
		
		global $wpdb;
		$suppress = $wpdb->suppress_errors();

		ob_start();

		// Start import - clear tables, etc.
		if ($action == 'import_start') {
			if (!empty($_POST['clear_tables'])) $this->clear_tables();

		// Import posts
		} else if ($action == 'import_posts') {
			$result = $this->import_posts();
			if ($result >= 100) do_action('unicaevents_action_importer_after_import_posts', $this);
			$this->response['result'] = $result;

		// Import Theme Mods
		} else if ($action == 'import_tm') {
			$this->import_theme_mods();

		// Import Theme Options
		} else if ($action == 'import_to') {
			$this->import_theme_options();

		// Import Templates Options
		} else if ($action == 'import_tpl') {
			$this->import_templates_options();

		// Import Widgets
		} else if ($action == 'import_widgets') {
			$this->import_widgets();

		// End import - flush rules, etc.
		} else if ($action == 'import_end') {
			flush_rewrite_rules();

		// Import Theme specific posts
		} else {
			do_action('unicaevents_action_importer_import', $this, $action);
		}

		ob_end_clean();

		$wpdb->suppress_errors($suppress);

		if ($this->options['debug']) dfl( sprintf(esc_html__('AJAX handler finished - send results to client', 'unicaevents'), $action) );

		echo json_encode($this->response);
		die();
	}


	// Import XML file with posts data
	function import_posts() {
		// Load WP Importer class
		if ($this->options['debug']) dfl(esc_html__('Start import posts', 'unicaevents'));
		if ( !defined('WP_LOAD_IMPORTERS') ) define('WP_LOAD_IMPORTERS', true); // we are loading importers
		if ( !class_exists('WP_Import') ) {
			require unicaevents_get_file_dir('core/core.importer/wordpress-importer.php');
		}
		if ( class_exists( 'WP_Import' ) ) {
			$theme_xml = unicaevents_get_file_dir($this->options['file_with_content'][$_POST['data_type']=='vc' ? 'vc' : 'no_vc']);
			$importer = new WP_Import();
			$importer->debug = $this->options['debug'];
			$importer->posts_at_once = $this->options['posts_at_once'];
			$importer->fetch_attachments = isset($_POST['fetch_attachments']) && $_POST['fetch_attachments']==1;
			$importer->uploads_folder = $this->options['uploads_folder'];
			$importer->demo_url = 'http://' . $this->options['domain_demo'] . '/';
			$importer->start_from_id = (int) $_POST['last_id'] > 0 ? $this->import_last_id : 0;
			$importer->import_log = $this->import_log;
			$this->prepare_taxonomies();
			$result = $importer->import($theme_xml);
			if ($result>=100) unicaevents_fpc($this->import_log, '');
		}
		return $result;
	}
	
	
	// Delete all data from tables
	function clear_tables() {
		global $wpdb;
		if (unicaevents_strpos($_POST['clear_tables'], 'posts')!==false && $this->import_last_id==0) {
			if ($this->options['debug']) dfl( esc_html__('Clear posts tables', 'unicaevents') );
			$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->comments));
			if ( is_wp_error( $res ) ) dfl( esc_html__( 'Failed truncate table "comments".', 'unicaevents' ) . ' ' . ($res->get_error_message()) );
			$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->commentmeta));
			if ( is_wp_error( $res ) ) dfl( esc_html__( 'Failed truncate table "commentmeta".', 'unicaevents' ) . ' ' . ($res->get_error_message()) );
			$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->postmeta));
			if ( is_wp_error( $res ) ) dfl( esc_html__( 'Failed truncate table "postmeta".', 'unicaevents' ) . ' ' . ($res->get_error_message()) );
			$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->posts));
			if ( is_wp_error( $res ) ) dfl( esc_html__( 'Failed truncate table "posts".', 'unicaevents' ) . ' ' . ($res->get_error_message()) );
			$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->terms));
			if ( is_wp_error( $res ) ) dfl( esc_html__( 'Failed truncate table "terms".', 'unicaevents' ) . ' ' . ($res->get_error_message()) );
			$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->term_relationships));
			if ( is_wp_error( $res ) ) dfl( esc_html__( 'Failed truncate table "term_relationships".', 'unicaevents' ) . ' ' . ($res->get_error_message()) );
			$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->term_taxonomy));
			if ( is_wp_error( $res ) ) dfl( esc_html__( 'Failed truncate table "term_taxonomy".', 'unicaevents' ) . ' ' . ($res->get_error_message()) );
		}
		do_action('unicaevents_action_importer_clear_tables', $this, $_POST['clear_tables']);
	}

	
	// Prepare additional taxes
	function prepare_taxonomies() {
		if ($this->options['debug']) dfl(esc_html__('Create custom taxonomies', 'unicaevents'));
		if (isset($this->options['taxonomies']) && is_array($this->options['taxonomies']) && count($this->options['taxonomies']) > 0) {
			foreach ($this->options['taxonomies'] as $type=>$tax) {
				unicaevents_require_data( 'taxonomy', $tax, array(
					'post_type'			=> array( $type ),
					'hierarchical'		=> false,
					'query_var'			=> $tax,
					'rewrite'			=> true,
					'public'			=> false,
					'show_ui'			=> false,
					'show_admin_column'	=> false,
					'_builtin'			=> false
					)
				);
			}
		}
	}


	// Import theme mods
	function import_theme_mods() {
		if (empty($this->options['file_with_mods'])) return;
		if ($this->options['debug']) dfl(esc_html__('Import Theme Mods', 'unicaevents'));
		$txt = unicaevents_fgc(unicaevents_get_file_dir($this->options['file_with_mods']));
		$data = unicaevents_unserialize($txt);
		// Replace upload url in options
		if (is_array($data) && count($data) > 0) {
			foreach ($data as $k=>$v) {
				if (is_array($v) && count($v) > 0) {
					foreach ($v as $k1=>$v1) {
						$v[$k1] = $this->replace_uploads($v1);
					}
				} else
					$v = $this->replace_uploads($v);
			}
			$theme = get_option( 'stylesheet' );
			update_option( "theme_mods_$theme", $data );
		}
	}


	// Import theme options
	function import_theme_options() {
		if (empty($this->options['file_with_options'])) return;
		if ($this->options['debug']) dfl(esc_html__('Reset Theme Options', 'unicaevents'));
		unicaevents_options_reset();
		if ($this->options['debug']) dfl(esc_html__('Import Theme Options', 'unicaevents'));
		$txt = unicaevents_fgc(unicaevents_get_file_dir($this->options['file_with_options']));
		$data = unicaevents_unserialize($txt);
		// Replace upload url in options
		if (is_array($data) && count($data) > 0) {
			foreach ($data as $k=>$v) {
				if (is_array($v) && count($v) > 0) {
					foreach ($v as $k1=>$v1) {
						$v[$k1] = $this->replace_uploads($v1);
					}
				} else
					$v = $this->replace_uploads($v);
				if ($k == 'mega_main_menu_options' && isset($v['last_modified']))
					$v['last_modified'] = time()+30;
				update_option( $k, $v );
			}
		}
		unicaevents_load_main_options();
	}


	// Import templates options
	function import_templates_options() {
		if (empty($this->options['file_with_templates'])) return;
		if ($this->options['debug']) dfl(esc_html__('Import Templates Options', 'unicaevents'));
		$txt = unicaevents_fgc(unicaevents_get_file_dir($this->options['file_with_templates']));
		$data = unicaevents_unserialize($txt);
		// Replace upload url in options
		if (is_array($data) && count($data) > 0) {
			foreach ($data as $k=>$v) {
				if (is_array($v) && count($v) > 0) {
					foreach ($v as $k1=>$v1) {
						$v[$k1] = $this->replace_uploads($v1);
					}
				} else
					$v = $this->replace_uploads($v);
				update_option( $k, $v );
			}
		}
	}


	// Import widgets
	function import_widgets() {
		if (empty($this->options['file_with_widgets'])) return;
		if ($this->options['debug']) dfl(esc_html__('Import Widgets', 'unicaevents'));
		$txt = unicaevents_fgc(unicaevents_get_file_dir($this->options['file_with_widgets']));
		$data = unicaevents_unserialize($txt);
		if (is_array($data) && count($data) > 0) {
			foreach ($data as $k=>$v) {
				update_option( $k, $this->replace_uploads($v) );
			}
		}
	}


	// Import any SQL dump
	function import_dump($slug, $title) {
		if (empty($this->options['file_with_'.$slug])) return;
		if ($this->options['debug']) dfl(sprintf(esc_html__('Import dump of "%s"', 'unicaevents'), $title));
		$txt = unicaevents_fgc(unicaevents_get_file_dir($this->options['file_with_'.$slug]));
		$data = unicaevents_unserialize($txt);
		if (is_array($data) && count($data) > 0) {
			global $wpdb;
			foreach ($data as $table=>$rows) {
				// Clear table, if it is not 'users' or 'usermeta'
				if (!in_array($table, array('users', 'usermeta')))
					$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->prefix . $table));
				$values = $fields = '';
				if (is_array($rows) && count($rows) > 0) {
					foreach ($rows as $row) {
						$f = '';
						$v = '';
						if (is_array($row) && count($row) > 0) {
							foreach ($row as $field => $value) {
								$f .= ($f ? ',' : '') . "'" . esc_sql($field) . "'";
								$v .= ($v ? ',' : '') . "'" . esc_sql($value) . "'";
							}
						}
						if ($fields == '') $fields = '(' . $f . ')';
						$values .= ($values ? ',' : '') . '(' . $v . ')';
						// If query length exceed 64K - run query, because MySQL not accept long query string
						// If current table 'users' or 'usermeta' - run queries row by row, because we append data
						if (unicaevents_strlen($values) > 64000 || in_array($table, array('users', 'usermeta'))) {
							// Attention! All items in the variable $values escaped on the loop above - esc_sql($value)
							$q = "INSERT INTO ".esc_sql($wpdb->prefix . $table)." VALUES {$values}";
							$wpdb->query($q);
							$values = $fields = '';
						}
					}
				}
				if (!empty($values)) {
					// Attention! All items in the variable $values escaped on the loop above - esc_sql($value)
					$q = "INSERT INTO ".esc_sql($wpdb->prefix . $table)." VALUES {$values}";
					$wpdb->query($q);
				}
			}
		}
	}

	
	// Replace uploads dir to new url
	function replace_uploads($str) {
		return unicaevents_replace_uploads_url($str, $this->options['uploads_folder']);
	}

	
	// Replace uploads dir to imports then export data
	function prepare_uploads($str) {
		if ($this->options['uploads_folder']=='uploads') return $str;
		if (is_array($str) && count($str) > 0) {
			foreach ($str as $k=>$v) {
				$str[$k] = $this->prepare_uploads($v);
			}
		} else if (is_string($str)) {
			$str = str_replace('/uploads/', "/{$this->options['uploads_folder']}/", $str);
		}
		return $str;
	}
	
	// Replace dev domain to demo domain then export data
	function prepare_domains($str) {
		if ($this->options['domain_dev']==$this->options['domain_demo']) return $str;
		if (is_array($str) && count($str) > 0) {
			foreach ($str as $k=>$v) {
				$str[$k] = $this->prepare_domains($v);
			}
		} else if (is_string($str)) {
			$str = str_replace($this->options['domain_dev'], $this->options['domain_demo'], $str);
		}
		return $str;
	}
}
?>