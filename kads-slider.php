<?php
/*
  Plugin Name: Kads Slider
  Description: Kads Slider is the most powerful and intuitive WordPress plugin to create sliders which was never possible before. Fully responsive, SEO optimized and works with any WordPress theme. Create beautiful sliders and tell stories without any code.
  Author: huynhduy1985
  Version: 1.0.0
  Author URI: http://kadrealestate.com/
 */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );



function kads_slider_get_file_uri($path = '') {
    return plugins_url($path, __FILE__);
}

function kads_slider_get_file($path = '') {
    return plugin_dir_path( __FILE__ ) . $path;
}
/**
 * Load a template part into a template
 *
 * Makes it easy for a theme to reuse sections of code in a easy to overload way
 * for child themes.
 *
 * The template is included using include_once
 *
 *
 * @since 1.0.0
 *
 * @param string $name The slug name for the generic template.
 * @param array $args The name of the specialised template.
 */
function kads_slider_template($name, $args = array()) {
    extract($args);
    $file = kads_slider_get_file('templates/' . $name . '.php');
    if (file_exists($file)) {
        include( $file );
    }
}

/**
 * Load a template part into a template
 *
 * Makes it easy for a theme to reuse sections of code in a easy to overload way
 * for child themes.
 *
 * The template is included using include_once
 *
 *
 * @since 1.0.0
 *
 * @param string $name The slug name for the generic template.
 * @param array $args The name of the specialised template.
 */
function kads_slider_get_template($name, $args = array()) {
    extract($args);
    $file = kads_slider_get_file('templates/' . $name . '.php');
    ob_start();
    if (file_exists($file)) {
        include( $file );
    }
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}

//load_plugin_textdomain('kads-slider', false, basename( dirname( __FILE__ ) ) . '/languages' );

require kads_slider_get_file('includes/kads-class.php');
require kads_slider_get_file('includes/kads-functions.php');
