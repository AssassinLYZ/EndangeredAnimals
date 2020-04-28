<?php

/**
 * Class Toolset_Framework_Installer_Ajax
 */
class Toolset_Framework_Installer_Ajax {

	public function __construct() {

		add_action( 'wp_ajax_fidemo_installation_proccess', array( $this, 'fidemo_installation_proccess' ) );

	}


	/**
	 *
	 */
	function fidemo_installation_proccess() {
		$step = intval( $_POST['step'] );
		$capability = apply_filters( 'fidemo_ajax_minimal_capability', 'manage_options' );

		if ( ! current_user_can( $capability ) ) {
			$data = array(
				'type' => 'capability',
				'message' => __( 'You do not have permissions for that.', 'wpcf-access' ),
			);
			wp_send_json_error( $data );
		}

		if ( ! isset( $_POST['wpnonce'] ) || ! wp_verify_nonce( $_POST['wpnonce'], 'fidemo_nonce' )
		) {
			$data = array(
				'type' => 'nonce',
				'message' => __( 'Your security credentials have expired. Please reload the page to get new ones.', 'wpcf-access' ),
			);
			wp_send_json_error( $data );
		}
		$data = array();


		require_once FIDEMO_ABSPATH . '/classes/helper.class.php';

		switch ( $step ) {
			case 0:
				$this->backup_site_settings();
				require_once FIDEMO_ABSPATH . '/classes/download.class.php';
				$fi_download = new Toolset_Framework_Installer_Download();
				$data = $fi_download->download_site();
				break;
			case 1:
				require_once FIDEMO_ABSPATH . '/classes/unpack.class.php';
				$fi_unpack = new Toolset_Framework_Installer_Unpack();
				$data = $fi_unpack->unpack_site();
				break;
			case 2:
				require_once FIDEMO_ABSPATH . '/classes/import_db.class.php';
				$fi_unpack = new Toolset_Framework_Installer_Import_Db();
				$data = $fi_unpack->import_db();
				break;
			case 3:
				require_once FIDEMO_ABSPATH . '/classes/configure_site.class.php';
				$fi_unpack = new Toolset_Framework_Installer_Configure();
				$data = $fi_unpack->configure_site();
				break;
			case 4:
				require_once FIDEMO_ABSPATH . '/classes/finalize_site.class.php';
				$fi_unpack = new Toolset_Framework_Installer_Finalize();
				$data = $fi_unpack->finalize_site();
				break;
			// Reserve 5-9 numbers if we will need to add more steps in the future
			case 10:
				$data = $this->restore_site_settings();
				break;
		}

		if ( $data['status'] === 'error' ) {
			wp_send_json_error( $data );
		} else {
			wp_send_json_success( $data );
		}

		die();
	}


	/**
	 * Get backup options file name and path
	 *
	 * @return string
	 */
	private function get_options_backup_file_path() {
		$upload_dir = wp_upload_dir();
		$file = $upload_dir['basedir'] . '/toolset_frameworkinstaller_options.json';

		return $file;
	}


	/**
	 * Save selected WP options to file
	 */
	public function backup_site_settings() {
		if ( ! class_exists( 'Toolset_Framework_Installer_Import_Db ' ) ) {
			require_once FIDEMO_ABSPATH . '/classes/import_db.class.php';
		}
		$import_db_class = new Toolset_Framework_Installer_Import_Db();
		$options = $import_db_class->get_saved_options_array();
		$options['siteurl'] = '';
		$options['home'] = '';
		$options['blogname'] = '';
		$options['blogdescription'] = '';

		foreach ( $options as $option_name => $option_value ) {
			$option = get_option( $option_name, $option_value );
			$options[ $option_name ] = $option;
		}
		$file = $this->get_options_backup_file_path();
		global $wp_filesystem;

		if ( empty( $wp_filesystem ) ) {
			require_once( ABSPATH . '/wp-admin/includes/file.php' );
			WP_Filesystem();
		}

		$wp_filesystem->put_contents(
			$file,
			wp_json_encode( $options ),
			FS_CHMOD_FILE
		);
	}


	/**
	 * Restore saved WP options and return a tip for the user
	 *
	 * @return array
	 */
	public function restore_site_settings() {
		$file = $this->get_options_backup_file_path();
		$options = join( '', file( $file ) );
		$options = json_decode( $options );
		foreach ( $options as $option_name => $option_value ) {
			update_option( $option_name, $option_value );
		}

		$output_message = __( 'Framework Installer plugin requires PHP max_execution_time to be set at 120 seconds and memory_limit to be set at128 MB or more. ', 'wpvdemo' )
			.
			__( 'Please make the necessary changes in your php.ini configuration file. ', 'wpvdemo' )
			.
			__( 'Contact your web host provider if you are not sure how to do this. ', 'wpvdemo' )
			.
			__( 'Once changes are made refresh a page and try to install reference site again.', 'wpvdemo' );

		$output = array( 'status' => 'complete', 'message' => $output_message );
		return $output;
	}
}
