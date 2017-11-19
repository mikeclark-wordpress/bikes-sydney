<?php
/**
 * Sydney child functions
 *
 */


/**
 * Enqueues the parent stylesheet. Do not remove this function.
 *
 */
add_action( 'wp_enqueue_scripts', 'sydney_child_enqueue' );
function sydney_child_enqueue() {
    
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    
    //http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js
    //wp_deregister_script( 'jquery' );
    //wp_enqueue_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js',false, null, true);
    
    //wp_deregister_script( 'schedule-bikepickup' );
    wp_enqueue_script( 'fancybox', get_stylesheet_directory_uri() .'/fancybox/jquery.fancybox-1.3.4.pack.js', array('jquery'), null, true);
        
        
    /* Enqueue css Begin */
    //wp_deregister_style( 'fontawesome' );
    wp_enqueue_style( 'fancybox_css', get_stylesheet_directory_uri() .'/fancybox/jquery.fancybox-1.3.4.css', false, null, 'all');


}

/* ADD YOUR CUSTOM FUNCTIONS BELOW */

require_once 'bike-post/bike.php';
 
require_once 'gallery-metabox/gallery.php';
