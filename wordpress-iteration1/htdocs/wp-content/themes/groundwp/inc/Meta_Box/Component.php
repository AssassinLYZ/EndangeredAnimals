<?php
/**
 * GroundWP\GroundWP\Meta_Box\Component class
 *
 * @package groundwp
 */

namespace GroundWP\GroundWP\Meta_Box;

use GroundWP\GroundWP\Component_Interface;
use function GroundWP\GroundWP\groundwp;
use function add_action;

/**
 * Class for managing metabox.
 *
 * @link https://developer.wordpress.org/plugins/metadata/custom-meta-boxes/
 */
class Component implements Component_Interface {

	/**
	 * Stores the meta options
	 *
	 * @var array
	 */
	private $meta_option;

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() {
		return 'meta_box';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'load-post.php', array( $this, 'action_meta_box_init' ) );
		add_action( 'load-post-new.php', array( $this, 'action_meta_box_init' ) );
	}

	/**
	 * Initialize Metabox options.
	 *
	 * @return void
	 */
	public function action_meta_box_init() {

		add_action( 'add_meta_boxes', [ $this, 'action_meta_box_setup' ] );
		add_action( 'save_post', [ $this, 'action_meta_box_save' ] );

		/**
		 * Set metabox options
		 *
		 * @see https://php.net/manual/en/filter.filters.sanitize.php
		 */
		$this->meta_option = [
			'groundwp-container-layout' => [],
			'groundwp-sidebar-style'    => [],
		];
	}

	/**
	 *  Setup Metabox
	 */
	public function action_meta_box_setup() {

		// Get all public posts.
		$post_types = get_post_types( [ 'public' => true ] );

		$meta_box_name = sprintf(
			// Translators: %s is the theme name.
			__( '%s Settings', 'groundwp' ),
			'GroundWP'
		);

		// Enable for all posts.
		foreach ( $post_types as $type ) {

			if ( 'attachment' !== $type ) {
				add_meta_box(
					'groundwp_settings_meta_box',              // Id.
					$meta_box_name,                         // Title.
					array( $this, 'meta_box_markup' ),      // Callback.
					$type,                                  // Post_type.
					'side',                                 // Context.
					'default'                               // Priority.
				);
			}
		}
	}

	/**
	 * Get metabox options
	 */
	public function get_meta_option() {
		return $this->meta_option;
	}

	/**
	 * Metabox Markup
	 *
	 * @param object $post Post object.
	 *
	 * @return void
	 */
	public function meta_box_markup( $post ) {

		wp_nonce_field( basename( __FILE__ ), 'groundwp_settings_meta_box' );
		$stored = get_post_meta( $post->ID );

		if ( is_array( $stored ) ) {

			// Set stored and override defaults.
			foreach ( $stored as $key => $value ) {
				$this->meta_option[ $key ]['default'] = ( isset( $stored[ $key ][0] ) ) ? $stored[ $key ][0] : ''; //phpcs:ignore
			}
		}

		// Get defaults.
		$meta = $this->get_meta_option();

		$container_layout = ( isset( $meta['groundwp-container-layout']['default'] ) ) ? $meta['groundwp-container-layout']['default'] : 'default';
		$sidebar_style    = ( isset( $meta['groundwp-sidebar-style']['default'] ) ) ? $meta['groundwp-sidebar-style']['default'] : 'default';

		/**
		 * Option: Container Layout
		 */
		?>
		<div class="groundwp-container-layout-meta-wrap components-base-control__field">
			<p class="post-attributes-label-wrapper">
				<strong> <?php esc_html_e( 'Container Layout', 'groundwp' ); ?> </strong>
			</p>
			<select name="groundwp-container-layout" id="groundwp-container-layout">
				<?php foreach ( groundwp()->get_container_layout_choices( true ) as $container_layout_key => $container_layout_name ) : ?>
                    <option value="<?php echo $container_layout_key; //phpcs:ignore ?>" <?php selected( $container_layout, $container_layout_key ); ?> >
						<?php echo $container_layout_name; //phpcs:ignore ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>
		<?php

		/**
		 * Option: Sidebar Style
		 */
		?>
		<div class="groundwp-sidebar-style-meta-wrap components-base-control__field">
			<p class="post-attributes-label-wrapper">
				<strong> <?php esc_html_e( 'Sidebar Style', 'groundwp' ); ?> </strong>
			</p>
			<select name="groundwp-sidebar-style" id="groundwp-sidebar-style">
				<?php foreach ( groundwp()->get_sidebar_style_choices( true ) as $sidebar_style_key => $sidebar_style_name ) : ?>
                    <option value="<?php echo $sidebar_style_key; //phpcs:ignore ?>" <?php selected( $sidebar_style, $sidebar_style_key ); ?> >
						<?php echo $sidebar_style_name; //phpcs:ignore ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>
		<?php

	}

	/**
	 * Metabox Save
	 *
	 * @param number $post_id Post ID.
	 *
	 * @return void
	 */
	public function action_meta_box_save( $post_id ) {

		// Checks save status.
		$is_autosave    = wp_is_post_autosave( $post_id );
		$is_revision    = wp_is_post_revision( $post_id );
		$is_valid_nonce = ( isset( $_POST['groundwp_settings_meta_box'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['groundwp_settings_meta_box'] ) ), basename( __FILE__ ) ) ) ? true : false;

		// Exits script depending on save status.
		if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
			return;
		}

		/**
		 * Get meta options
		 */
		$post_meta = $this->get_meta_option();

		foreach ( $post_meta as $key => $data ) {

			$meta_value = filter_input( INPUT_POST, $key, FILTER_DEFAULT );

			// Store values.
			if ( $meta_value ) {
				update_post_meta( $post_id, $key, $meta_value );
			} else {
				delete_post_meta( $post_id, $key );
			}
		}

	}
}
