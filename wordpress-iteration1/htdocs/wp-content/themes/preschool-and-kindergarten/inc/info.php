<?php
/**
 * Preschool and Kindergarten Theme Info
 *
 * @package preschool_and_kindergarten
 */

function preschool_and_kindergarten_customizer_theme_info( $wp_customize ) {
	
    $wp_customize->add_section( 'theme_info' , array(
		'title'       => __( 'Information Links' , 'preschool-and-kindergarten' ),
		'priority'    => 6,
		));

	$wp_customize->add_setting('theme_info_theme',array(
		'default' => '',
		'sanitize_callback' => 'wp_kses_post',
		));
    
    $theme_info = '';
	$theme_info .= '<h3 class="sticky_title">' . __( 'Need help?', 'preschool-and-kindergarten' ) . '</h3>';
    $theme_info .= '<span class="sticky_info_row"><label class="row-element">' . __( 'View demo', 'preschool-and-kindergarten' ) . ': </label><a href="' . esc_url( 'https://demo.rarathemes.com/preschool-and-kindergarten/' ) . '" target="_blank">' . __( 'here', 'preschool-and-kindergarten' ) . '</a></span><br />';
	$theme_info .= '<span class="sticky_info_row"><label class="row-element">' . __( 'View documentation', 'preschool-and-kindergarten' ) . ': </label><a href="' . esc_url( 'https://docs.rarathemes.com/docs/preschool-and-kindergarten/' ) . '" target="_blank">' . __( 'here', 'preschool-and-kindergarten' ) . '</a></span><br />';
    $theme_info .= '<span class="sticky_info_row"><label class="row-element">' . __( 'Theme info', 'preschool-and-kindergarten' ) . ': </label><a href="' . esc_url( 'https://rarathemes.com/wordpress-themes/preschool-and-kindergarten/' ) . '" target="_blank">' . __( 'here', 'preschool-and-kindergarten' ) . '</a></span><br />';
    $theme_info .= '<span class="sticky_info_row"><label class="row-element">' . __( 'Support ticket', 'preschool-and-kindergarten' ) . ': </label><a href="' . esc_url( 'https://rarathemes.com/support-ticket/' ) . '" target="_blank">' . __( 'here', 'preschool-and-kindergarten' ) . '</a></span><br />';
	$theme_info .= '<span class="sticky_info_row"><label class="row-element">' . __( 'Rate this theme', 'preschool-and-kindergarten' ) . ': </label><a href="' . esc_url( 'https://wordpress.org/support/theme/preschool-and-kindergarten/reviews/' ) . '" target="_blank">' . __( 'here', 'preschool-and-kindergarten' ) . '</a></span><br />';
	$theme_info .= '<span class="sticky_info_row"><label class="more-detail row-element">' . __( 'More WordPress Themes', 'preschool-and-kindergarten' ) . ': </label><a href="' . esc_url( 'https://rarathemes.com/wordpress-themes/' ) . '" target="_blank">' . __( 'here', 'preschool-and-kindergarten' ) . '</a></span><br />';


	$wp_customize->add_control( new Preschool_and_Kindergarten_Theme_Info( $wp_customize ,'theme_info_theme',array(
		'label' => __( 'About Preschool and Kindergarten' , 'preschool-and-kindergarten' ),
		'section' => 'theme_info',
		'description' => $theme_info
		)));

	$wp_customize->add_setting('theme_info_more_theme',array(
		'default' => '',
		'sanitize_callback' => 'wp_kses_post',
		));

}
add_action( 'customize_register', 'preschool_and_kindergarten_customizer_theme_info' );


if( class_exists( 'WP_Customize_control' ) ){

	class Preschool_and_Kindergarten_Theme_Info extends WP_Customize_Control
	{
    	/**
       	* Render the content on the theme customizer page
       	*/
       	public function render_content()
       	{
       		?>
       		<label>
       			<strong class="customize-text_editor"><?php echo esc_html( $this->label ); ?></strong>
       			<br />
       			<span class="customize-text_editor_desc">
       				<?php echo wp_kses_post( $this->description ); ?>
       			</span>
       		</label>
       		<?php
       	}
    }//editor close
    
}//class close

if( class_exists( 'WP_Customize_Section' ) ) :
/**
 * Adding Go to Pro Section in Customizer
 * https://github.com/justintadlock/trt-customizer-pro
 */
class Preschool_and_Kindergarten_Customize_Section_Pro extends WP_Customize_Section {

	/**
	 * The type of customize section being rendered.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $type = 'pro-section';

	/**
	 * Custom button text to output.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $pro_text = '';

	/**
	 * Custom pro button URL.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $pro_url = '';

	/**
	 * Add custom parameters to pass to the JS via JSON.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function json() {
		$json = parent::json();

		$json['pro_text'] = $this->pro_text;
		$json['pro_url']  = esc_url( $this->pro_url );

		return $json;
	}

	/**
	 * Outputs the Underscore.js template.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	protected function render_template() { ?>

		<li id="accordion-section-{{ data.id }}" class="accordion-section control-section control-section-{{ data.type }} cannot-expand">

			<h3 class="accordion-section-title">
				{{ data.title }}

				<# if ( data.pro_text && data.pro_url ) { #>
					<a href="{{ data.pro_url }}" class="button button-secondary alignright" target="_blank">{{ data.pro_text }}</a>
				<# } #>
			</h3>
		</li>
	<?php }
}
endif;

add_action( 'customize_register', 'preschool_and_kindergarten_sections_pro' );
function preschool_and_kindergarten_sections_pro( $manager ) {
	// Register custom section types.
	$manager->register_section_type( 'Preschool_and_Kindergarten_Customize_Section_Pro' );

	// Register sections.
	$manager->add_section(
		new Preschool_and_Kindergarten_Customize_Section_Pro(
			$manager,
			'preschool_and_kindergarten_view_pro',
			array(
				'title'    => esc_html__( 'Pro Available', 'preschool-and-kindergarten' ),
                'priority' => 5, 
				'pro_text' => esc_html__( 'VIEW PRO THEME', 'preschool-and-kindergarten' ),
				'pro_url'  => 'https://rarathemes.com/wordpress-themes/preschool-and-kindergarten-pro/'
			)
		)
	);
}

