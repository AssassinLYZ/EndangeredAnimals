<?php
/**
 * Services Section
 * 
 * @package Preschool_and_Kindergarten
*/  
     $section_title = get_theme_mod( 'preschool_and_kindergarten_services_section_title' );
     $description   = get_theme_mod( 'preschool_and_kindergarten_services_section_description' );
     $services_post = get_theme_mod( 'preschool_and_kindergarten_services_post' );
            
     ?>
<div class="section-3">
    <div class="container">
	    <?php
	    preschool_and_kindergarten_get_section_header( $section_title, $description );  

		if($services_post){

            $services_query = new WP_Query( array( 'p' => $services_post ));
        
            if( $services_query->have_posts() ){ $services_query->the_post();?>
				<div class="row">
					
					<div class="text-holder">
						<?php  the_content(); ?>
					</div>
					
					<?php 
					if( has_post_thumbnail() ){ ?>
						<div class="img-holder">
							<?php the_post_thumbnail(); ?>
						</div>
					<?php 
					} ?>
				
				</div>
	    <?php }
	     wp_reset_postdata(); }?>
	</div>
</div>

