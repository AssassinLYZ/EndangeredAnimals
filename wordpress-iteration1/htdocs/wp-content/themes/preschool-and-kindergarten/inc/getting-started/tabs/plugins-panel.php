<?php
/**
 * Help Panel.
 *
 * @package Preschool_And_Kindergarten
 */
?>
<!-- Updates panel -->
<div id="plugins-panel" class="panel-left visible">
	<h4><?php _e( 'Recommended Plugins', 'preschool-and-kindergarten' ); ?></h4>

	<p><?php printf( __( 'Here is a list of recommended plugins you should install to get most out of the Preschool and Kindergarten. Though every plugin is optional, we recommend you to at least install %1$sRara One Click Demo Import%2$s & %1$sContact Form 7%2$s to create a website similar to the Preschool and Kindergarten demo and also to ensure every feature of the theme works correctly.', 'preschool-and-kindergarten' ), '<strong>', '</strong>' ) ?></p>

	<hr/>

	<?php 
	$free_plugins = array(             
		'rara-one-click-demo-import' => array(
			'slug' 	 	=> 'rara-one-click-demo-import',
			'filename'  => 'rara-one-click-demo-import.php',
		),                
		'contact-form-7' => array(
			'slug' 		=> 'contact-form-7',
			'filename' 	=> 'wp-contact-form-7.php',
		),                
	);

	if( $free_plugins ){ ?>
		<h4 class="recomplug-title"><?php esc_html_e( 'Free Plugins', 'preschool-and-kindergarten' ); ?></h4>
		<p><?php esc_html_e( 'These Free Plugins might be handy for you.', 'preschool-and-kindergarten' ); ?></p>
		<div class="recomended-plugin-wrap">
    		<?php
    		foreach( $free_plugins as $plugin ) {
    			$info     = preschool_and_kindergarten_call_plugin_api( $plugin['slug'] );
    			$icon_url = preschool_and_kindergarten_check_for_icon( $info->icons ); ?>    
    			<div class="recom-plugin-wrap">
    				<div class="plugin-img-wrap">
    					<img src="<?php echo esc_url( $icon_url ); ?>" />
    					<div class="version-author-info">
    						<span class="version"><?php printf( esc_html__( 'Version %s', 'preschool-and-kindergarten' ), $info->version ); ?></span>
    						<span class="seperator">|</span>
    						<span class="author"><?php echo esc_html( strip_tags( $info->author ) ); ?></span>
    					</div>
    				</div>
    				<div class="plugin-title-install clearfix">
    					<span class="title" title="<?php echo esc_attr( $info->name ); ?>">
    						<?php echo esc_html( $info->name ); ?>	
    					</span>
                        <div class="button-wrap">
    					   <?php echo Preschool_And_Kindergarten_Getting_Started_Page_Plugin_Helper::instance()->get_button_html( $plugin['slug'], $plugin['filename'] ); ?>
                        </div>
    				</div>
    			</div>
    			<?php
    		} 
            ?>
		</div>
	<?php
	} 
?>
</div><!-- .panel-left -->