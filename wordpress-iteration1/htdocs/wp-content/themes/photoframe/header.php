<!DOCTYPE html>
<!--[if IE 7 | IE 8]>
<html class="ie" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="profile" href="http://gmpg.org/xfn/11" />
	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

<div id="container">

	<div class="site-wrapper-all">

		<?php ilovewp_helper_display_hero_header(); ?>

		<header id="site-masthead" class="site-section site-section-masthead">
			<div class="site-section-wrapper site-section-wrapper-masthead">
				<div id="site-logo"><div class="site-logo-wrapper-dark"><?php
				if ( function_exists( 'jetpack_has_site_logo' ) && jetpack_has_site_logo() ) {
					jetpack_the_site_logo();
				} elseif ( function_exists( 'has_custom_logo' ) && has_custom_logo() ) {
					photoframe_the_custom_logo();
				} else { ?>
					<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
					<p class="site-description"><?php bloginfo( 'description' ); ?></p>
				<?php } ?></div><!-- .site-logo-wrapper-dark --><?php 
				if ( get_theme_mod( 'theme-logo-white' ) != '' ) { ?><div class="site-logo-wrapper-light"><a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php if ( get_theme_mod( 'theme-logo-white' ) != '' ) { echo esc_url( get_theme_mod('theme-logo-white') ); } ?>" alt="<?php esc_attr(bloginfo('name')); ?>" class="custom-logo site-logo-light" /></a></div><?php } ?></div><!-- #site-logo --><!-- ws fix 
			--><div id="site-section-primary-menu">
					<div class="site-navbar-header">

						<?php wp_nav_menu( array(
							'container_id'   => 'menu-main-slick',
							'menu_id' => 'menu-slide-in',
							'sort_column' => 'menu_order', 
							'theme_location' => 'primary'
						) ); 
						?>

					</div><!-- .site-navbar-header -->
					<nav id="site-primary-nav">

					<?php if (has_nav_menu( 'primary' )) { 
					wp_nav_menu( array(
						'container' => '', 
						'container_class' => '', 
						'menu_class' => 'large-nav sf-menu mobile-menu site-menu-dark clearfix', 
						'menu_id' => 'site-primary-menu', 
						'sort_column' => 'menu_order', 
						'theme_location' => 'primary', 
						'link_after' => '' ) );
					}
					?>
				</nav><!-- #site-primary-nav -->
				</div><!-- #site-section-primary-menu -->
			</div><!-- .site-section-wrapper .site-section-wrapper-masthead -->
		</header><!-- #site-masthead .site-section-masthead -->