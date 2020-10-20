<?php

add_action('wp_enqueue_scripts', 'rk_frontend_scripts');

function rk_frontend_scripts()
{

    if (in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1', '10.0.0.3'))) :
        wp_enqueue_script('rk-livereload', 'http://localhost:35729/livereload.js?snipver=1', array(), null, true);
    endif;

    $min = (in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1', '10.0.0.3'))) ? '' : '.min';

    // wp_enqueue_style( 'rk-style', RK_URL . 'assets/css/style.css', array(), false, 'all' );

    // wp_enqueue_script( 'jquery-mask', RK_URL . 'assets/lib/jquery.mask' . $min . '.js', array( 'jquery' ), '1.14.16', true );

    wp_register_script('rk-frontend-script', RK_URL . 'assets/js/rk-frontend-script' . $min . '.js', array('jquery'), '1.0.1', true);

    wp_enqueue_script('rk-frontend-script');

    wp_localize_script('rk-frontend-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}

add_action('admin_enqueue_scripts', 'rk_admin_scripts');

function rk_admin_scripts()
{

    $min = (in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1'))) ? '' : '.min';

    wp_enqueue_style('rk-admin- style', RK_URL . 'assets/css/admin-style.css', array(), false, 'all');

    // wp_enqueue_script( 'jquery-mask', RK_URL . 'assets/lib/jquery.mask' . $min . '.js', array( 'jquery' ), '1.14.16', true );

    wp_register_script('rk-admin-script', RK_URL . 'assets/js/rk-admin-script' . $min . '.js', array('jquery'), '1.0.0', true);

    wp_enqueue_script('rk-admin-script');

    wp_localize_script('rk-admin-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}
