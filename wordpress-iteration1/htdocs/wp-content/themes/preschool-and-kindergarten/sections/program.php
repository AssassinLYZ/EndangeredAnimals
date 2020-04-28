<?php
/**
 * Program Section
 * 
 * @package Preschool_and_Kindergarten
*/ 
      $section_title      = get_theme_mod('preschool_and_kindergarten_program_section_title');
      $description        = get_theme_mod('preschool_and_kindergarten_program_section_description');            
      $program_post_one   = get_theme_mod('preschool_and_kindergarten_program_post_one');
      $program_post_two   = get_theme_mod('preschool_and_kindergarten_program_post_two');
      $program_post_three = get_theme_mod('preschool_and_kindergarten_program_post_three');
             
      $program_post = array( $program_post_one, $program_post_two, $program_post_three );
      $program_post = array_diff( array_unique( $program_post ), array('') );
?>
    <section class="featured">
		<div class="container">
		    <?php 
		    preschool_and_kindergarten_get_section_header( $section_title, $description ); 
            
            if( $program_post ):

		        $program_qry = new WP_Query(array(
			    	 
			    	 'post__in'   => $program_post,
	                 'orderby'   => 'post__in',
	                 'posts_per_page' => -1,
	                 'ignore_sticky_posts' => true

		    	));

			    if($program_qry->have_posts()){ ?>
				
					<div class="row">
					
					    <?php 
					    while($program_qry->have_posts()){ $program_qry->the_post() ?>
						
							<div class="col">
								<div class="holder">
							
								    <?php
								    if(has_post_thumbnail()){ ?>
										<div class="img-holder">
											<?php the_post_thumbnail('preschool-and-kindergarten-program-thumb', array( 'itemprop' => 'image' ) ); ?>
										</div>
								    <?php 
								    } ?>

									<div class="text-holder">
										<h3 class="title"><?php the_title(); ?></h3>
										<?php  the_excerpt(); ?>
										<a href="<?php the_permalink(); ?>" class="btn-detail"><?php esc_html_e('View Details','preschool-and-kindergarten');?></a>
									</div>
								
								</div>
							</div>

					    <?php 
					    } ?>
					
				    </div>
				
				<?php } 
				
				wp_reset_postdata(); 
				
		    endif ?>
		</div>
	</section>