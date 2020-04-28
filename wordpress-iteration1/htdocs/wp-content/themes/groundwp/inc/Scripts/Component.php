<?php
/**
 * GroundWP\GroundWP\Scripts\Component class
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP\Scripts;

use GroundWP\GroundWP\Component_Interface;
use GroundWP\GroundWP\Templating_Component_Interface;
use GroundWP\GroundWP\Traits\Slug;
use function add_action;
use function get_theme_file_path;
use function get_theme_file_uri;
use function wp_enqueue_script;
use function wp_register_script;
use function GroundWP\GroundWP\groundwp;

/**
 * Class for easy enqueuing scripts
 * Exposes template tags:
 * * `groundwp()->ez_enqueue_script()`
 * * `groundwp()->enqueue_main()`
 */
class Component implements Component_Interface, Templating_Component_Interface {

	use Slug;

	/**
	 * Library scripts default path
	 *
	 * @var string
	 */
	public $library_path = '/assets/js';

	/**
	 * List of enabled main scripts
	 *
	 * @var array
	 */
	private $main_scripts = [];

	/**
	 * List of dependencies for main script
	 *
	 * @var array
	 */
	private $main_deps = [];


	/**
	 * Script localized data
	 *
	 * @var array
	 */
	private $localize_data = [];


	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'wp_enqueue_scripts', [ $this, 'register_main' ], 999 );
	}


	/**
	 * WordPress wp_enqueue_scripts hook callback
	 * will be using this function to register our library scripts
	 *
	 * @deprecated
	 */
	public function register_scripts() {
		$library = $this->library_scripts();

		array_map( [ $this, 'register_individual_script' ], array_keys( $library ), $library );
	}

	/**
	 * Register main script of the theme
	 */
	public function register_main() {
		$handler = $this->ez_enqueue_script( '/assets/js/groundwp-main.min.js', true, $this->main_deps );

		$main_data = [
			'scripts' => $this->main_scripts,
		];

		wp_localize_script( $handler, 'groundwpMainData', $main_data );

		foreach ( $this->localize_data as $data ) {
			foreach ( $data as $key => $value ) {
				wp_localize_script( $handler, $key, $value );
			}
		}

	}

	/**
	 * Function to register library script
	 *
	 * @param string $name handler name.
	 * @param array $val library data.
	 */
	public function register_individual_script( $name, $val ) {
		$uri     = get_theme_file_uri( $this->library_path . '/' . $val['file'] );
		$path    = get_theme_file_path( $this->library_path . '/' . $val['file'] );
		$version = isset( $val['version'] ) ? $val['version'] : groundwp()->get_asset_version( $path );

		wp_register_script( $name, $uri, [], $version, true );
	}

	/**
	 * Register your library scripts here
	 *
	 * @return array an array of library scripts to be registered
	 * @deprecated
	 */
	private function library_scripts() {
		return [
			'vue'                   => [
				'file'    => 'libs/vue.min.js',
				'version' => '2.6.11',
			],
			'console-logger'        => [
				'file' => 'libs/console-logger.js',
			],
			'intersection-observer' => [
				'file' => 'libs/intersection-observer-component.js',
			],
		];
	}

	/**
	 * Gets template tags to expose as methods on the Template_Tags class instance, accessible through `groundwp()`.
	 *
	 * @return array Associative array of $method_name => $callback_info pairs. Each $callback_info must either be
	 *               a callable or an array with key 'callable'. This approach is used to reserve the possibility of
	 *               adding support for further arguments in the future.
	 */
	public function template_tags() {
		return [
			'ez_enqueue_script' => [ $this, 'ez_enqueue_script' ],
			'enqueue_main'      => [ $this, 'enqueue_main' ],
		];
	}


	/**
	 * Enqueue script
	 *
	 * @param string $path Script path relative to theme root.
	 * @param bool $footer Should script be placed in footer or not. Default is false.
	 * @param array $deps Dependencies for script. Default is [].
	 *
	 * @return string handler
	 */
	public function ez_enqueue_script( $path, $footer = false, $deps = [] ) {
		$path_url     = get_theme_file_uri( $path );
		$path_dir     = get_theme_file_path( $path );
		$file_version = groundwp()->get_asset_version( $path_dir );

		$handler = groundwp()->get_default_prefix( pathinfo( $path )['filename'] );

		wp_enqueue_script( $handler, $path_url, $deps, $file_version, $footer );

		return $handler;
	}

	/**
	 * Enable scripts bundled in main js file of the theme
	 *
	 * @param string $id script id.
	 * @param array $deps WordPress script dependencies, any non WordPress included dependency should be imported in the source code of the script, as it has to be.
	 * @param array $localize_data script localized data, keys for global object name and values for data.
	 */
	public function enqueue_main( $id, $deps = [], $localize_data = [] ) {
		if ( ! in_array( $id, $this->main_scripts ) ) {
			// add script to enabled scripts.
			$this->main_scripts[] = $id;
			// add dependencies.
			foreach ( $deps as $dep ) {
				if ( ! in_array( $dep, $this->main_deps ) ) {
					$this->main_deps[] = $dep;
				}
			}

			$this->localize_data[] = $localize_data;
		}
	}
}
