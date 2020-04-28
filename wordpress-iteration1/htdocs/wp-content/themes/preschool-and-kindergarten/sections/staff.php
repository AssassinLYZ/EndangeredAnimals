<?php
/**
 * Staff Section
 * 
 * @package Preschool_and_Kindergarten
*/ 
    $section_title = get_theme_mod('preschool_and_kindergarten_staff_section_title');
    $description   = get_theme_mod('preschool_and_kindergarten_staff_section_description');
    $staff_one     = get_theme_mod( 'preschool_and_kindergarten_staff_post_one' );
    $staff_two     = get_theme_mod( 'preschool_and_kindergarten_staff_post_two' );
    $staff_three   = get_theme_mod( 'preschool_and_kindergarten_staff_post_three' );
    
    $staffs_posts = array( $staff_one, $staff_two, $staff_three );
    $staffs_posts = array_diff( array_unique( $staffs_posts ), array('') );
?>
<section class="our-staff">
    <div class="container">
		<?php 
		preschool_and_kindergarten_get_section_header( $section_title, $description ); 
        
        if( $staffs_posts ):

			$staff_qry = new WP_Query(array(
			    	    'post__in'   => $staffs_posts,
	                    'orderby'   => 'post__in',
	                    'posts_per_page' => -1,
	                    'ignore_sticky_posts' => true
			));

		    if( $staff_qry->have_posts() ){ ?>
				<div class="row">
				    <?php 
				    while( $staff_qry->have_posts() ){
				    	$staff_qry->the_post() ?>
						<div class="col">
							<?php 
							if( has_post_thumbnail() ){ ?>
								<div class="img-holder">
									<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('preschool-and-kindergarten-staff-thumb', array( 'itemprop' => 'image' ) ); ?></a>
								</div>
						    <?php 
						    } ?>
							<div class="text-holder">
								<strong class="name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></strong>
								<?php if( has_excerpt() ){?><span class="designation"><?php the_excerpt(); ?></span><?php } ?>
							</div>
						</div>
			        <?php 
			        } ?>
				</div>
		    <?php 
		    } 
		    wp_reset_postdata(); 
		endif;
		?>
	</div>
</section>