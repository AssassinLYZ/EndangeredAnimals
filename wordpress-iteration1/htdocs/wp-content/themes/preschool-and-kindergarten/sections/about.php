<?php   
/**
 * About Section
 * 
 * @package Preschool_and_Kindergarten
*/ 
    $about_page = get_theme_mod('preschool_and_kindergarten_about_page');
        
        if($about_page){

	        $about_query = new WP_Query( array( 
	        	
	        	'p' => $about_page,
	        	'post_type' => 'page'

	        	));
        
		        if($about_query->have_posts()){ $about_query->the_post();?>
			        <section class="welcome">
				        <div class="container">
							<div class="row">
							    
							    <?php 
							    if(has_post_thumbnail()){ ?>
									<div class="img-holder">
										<?php the_post_thumbnail('preschool-and-kindergarten-about-thumb', array( 'itemprop' => 'image' ) ); ?>
									</div>
							    <?php 
							    } ?>
								
								<div class="text-holder">
									<h2 class="title"><?php the_title(); ?></h2>
									<?php the_excerpt(); ?>
									<a href="<?php the_permalink(); ?>" class="btn-more"><?php echo esc_html__('Read More','preschool-and-kindergarten');?></a>
								</div>

							</div>
						</div>
					</section>	
			    <?php 

			    }
			    
			    wp_reset_postdata(); 
	    } 
?>