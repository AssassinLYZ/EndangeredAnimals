<?php
/**
 * lessons Section
 * 
 * @package Preschool_and_Kindergarten
*/  
     $section_title = get_theme_mod( 'preschool_and_kindergarten_lessons_section_title' );
     $description   = get_theme_mod( 'preschool_and_kindergarten_lessons_section_description' ); 
     $lesson_one    = get_theme_mod( 'preschool_and_kindergarten_lesson_post_one' );
     $lesson_two    = get_theme_mod( 'preschool_and_kindergarten_lesson_post_two' );
     $lesson_three  = get_theme_mod( 'preschool_and_kindergarten_lesson_post_three' );

     $lessons_posts = array( $lesson_one, $lesson_two, $lesson_three );
     $lessons_posts = array_diff( array_unique( $lessons_posts ), array('') );
?>  
<section class="section-2">
    <div class="container">
		<?php 
		    preschool_and_kindergarten_get_section_header( $section_title, $description ); 
            
            if( $lessons_posts ):
            	
			    $lesson_qry = new WP_Query(array(
                    'post__in'   => $lessons_posts,
                    'orderby'   => 'post__in',
                    'posts_per_page' => -1,
                    'ignore_sticky_posts' => true
                    ));

				if( $lesson_qry->have_posts() ){ ?>
				    <div class="row">
				       
				       <?php 
				        while( $lesson_qry->have_posts() ){ $lesson_qry->the_post(); ?>
						
							<div class="col">
								
								<?php 
								    if( has_post_thumbnail() ){ ?>
										<div class="img-holder">
											<?php the_post_thumbnail( 'preschool-and-kindergarten-lesson-thumb', array( 'itemprop' => 'image' ) ); ?>
										</div>
								<?php } ?>
								
								<div class="text-holder">
									<h3 class="title"><?php the_title(); ?></h3>
									<?php the_content(); ?>
								</div>
							
							</div>		
							
			            <?php } ?>
				
				    </div>
				<?php } 
			wp_reset_postdata();
		endif; ?>
	</div>
</section>