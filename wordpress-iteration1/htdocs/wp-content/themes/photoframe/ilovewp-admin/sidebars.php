<?php 

/*-----------------------------------------------------------------------------------*/
/* Initializing Widgetized Areas (Sidebars)																			 */
/*-----------------------------------------------------------------------------------*/

function photoframe_widgets_init() {

	register_sidebar(array(
		'name' => __('Sidebar (UNUSED)','photoframe'),
		'id' => 'sidebar',
		'description' => __('Widgets added to this widgetized area are NOT displayed anywhere. This area is defined for the purpose of capturing the default widgets that are automatically added on new WordPress installations.','photoframe'),
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '',
		'after_title' => '',
	));

	register_sidebar(array(
		'name' => __('Homepage: Welcome Widgets (Left)','photoframe'),
		'id' => 'homepage-welcome-widgets-left',
		'description' => __('Recommended widgets: An image widget, social icons widget, subscribe banners, etc.','photoframe'),
		'before_widget' => '<div class="widget widget-welcome %2$s clearfix" id="%1$s">',
		'after_widget' => '</div>',
		'before_title'  => '<p class="widget-title"><span class="page-title-span">',
		'after_title'   => '</span></p>',
	));

	register_sidebar(array(
		'name' => __('Homepage: Welcome Widgets (Right)','photoframe'),
		'id' => 'homepage-welcome-widgets-right',
		'description' => __('We recommend adding a single [Text Widget].','photoframe'),
		'before_widget' => '<div class="widget widget-welcome %2$s clearfix" id="%1$s">',
		'after_widget' => '</div>',
		'before_title'  => '<p class="widget-title"><span class="page-title-span">',
		'after_title'   => '</span></p>',
	));

	register_sidebar(array(
		'name' => __('Homepage: Above Recent Posts','photoframe'),
		'id' => 'homepage-welcome-above-recent-posts',
		'description' => __('By default this area is 1100px wide and appears between the Featured Pages block and the Recent Posts block.','photoframe'),
		'before_widget' => '<div class="widget widget-welcome %2$s clearfix" id="%1$s">',
		'after_widget' => '</div>',
		'before_title'  => '<p class="widget-title"><span class="page-title-span">',
		'after_title'   => '</span></p>',
	));

	register_sidebar( array(
		'name'          => esc_html__( 'Footer: Full Width', 'photoframe' ),
		'id'            => 'footer-full',
		'description' => __('This is a full width widgetized area that appears before the 3 columns. Intended for an Instagram Feed widget.','photoframe'),
		'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget-content-wrapper">',
		'after_widget'  => '</div><!-- .widget-content-wrapper --></div>',
		'before_title'  => '<p class="widget-title"><span class="page-title-span">',
		'after_title'   => '</span></p>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer: Column 1', 'photoframe' ),
		'id'            => 'footer-col-1',
		'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget-content-wrapper">',
		'after_widget'  => '</div><!-- .widget-content-wrapper --></div>',
		'before_title'  => '<p class="widget-title"><span class="page-title-span">',
		'after_title'   => '</span></p>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer: Column 2', 'photoframe' ),
		'id'            => 'footer-col-2',
		'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget-content-wrapper">',
		'after_widget'  => '</div><!-- .widget-content-wrapper --></div>',
		'before_title'  => '<p class="widget-title"><span class="page-title-span">',
		'after_title'   => '</span></p>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer: Column 3', 'photoframe' ),
		'id'            => 'footer-col-3',
		'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget-content-wrapper">',
		'after_widget'  => '</div><!-- .widget-content-wrapper --></div>',
		'before_title'  => '<p class="widget-title"><span class="page-title-span">',
		'after_title'   => '</span></p>',
	) );

} 

add_action( 'widgets_init', 'photoframe_widgets_init' );