
jQuery(document).ready(function(){
    /** Variables from Customizer for Slider settings */
    var slider_auto, slider_loop, slider_control, animation, rtl, slider_testimonial_auto;
    
    if( preschool_and_kindergarten_data.auto == '1' ){
        slider_auto = true;
    }else{
        slider_auto = false;
    }
    
    if( preschool_and_kindergarten_data.loop == '1' ){
        slider_loop = true;
    }else{
        slider_loop = false;
    }
    
    if( preschool_and_kindergarten_data.control == '1' ){
        slider_control = true;
    }else{
        slider_control = false;
    }

    if( preschool_and_kindergarten_data.animation == 'slide' ){
        animation = '';
    }else{
        animation = preschool_and_kindergarten_data.animation;
    }

    if( preschool_and_kindergarten_data.rtl == '1' ){
        rtl = true;
    }else{
        rtl = false;
    }

        //testimonials sections
    if( preschool_and_kindergarten_data.t_auto == '1' ){
        slider_testimonial_auto = true;
    }else{
        slider_testimonial_auto = false;
    }

    /** Home Page Slider */
    jQuery('.banner-slider').owlCarousel({
        items         : 1,
        autoplay      : slider_auto,
        loop          : slider_loop,
        nav           : slider_control,
        animateOut    : animation,
        autoplaySpeed : preschool_and_kindergarten_data.speed,
        navSpeed      : preschool_and_kindergarten_data.a_speed,
        lazyLoad      : true,
        rtl           : rtl
    });

    jQuery("#lightSlider").owlCarousel({
        
        items          : 1,// slidemove will be 1 if loop is true
        margin   : 0,
        dots         : false,
        nav: true,
        rtl           : rtl,
        mouseDrag    : false,
        autoplay          :slider_testimonial_auto,
    });

    //mobile-menu
    jQuery('.mobile-menu').append('<div class="btn-close-menu"></div>');

    jQuery('.menu-opener').click(function(){
        jQuery('body').addClass('menu-open');
    });

    jQuery('.overlay').click(function(){
        jQuery('body').removeClass('menu-open');
    });

    jQuery('.btn-close-menu').click(function(){
        jQuery('body').removeClass('menu-open');
    });

    jQuery('.primary-menu ul .menu-item-has-children').append('<div class="angle-down"></div>');
    jQuery('.primary-menu ul li .angle-down').click(function(){
        jQuery(this).prev().slideToggle();
        jQuery(this).toggleClass('active');
    });

    //accessible menu in IE
    jQuery("#site-navigation ul li a").focus(function(){
        jQuery(this).parents("li").addClass("focus");
    }).blur(function(){
        jQuery(this).parents("li").removeClass("focus");
   });

});
