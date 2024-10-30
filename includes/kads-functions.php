<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


global $kads_slider_sc_params;


/**
 * sliders menu
 *
 * @since 1.0.0
 */
function kads_sliders_menu() {
    global $kads_slider_admin_page;
    $kads_slider_admin_page = array();
    $kads_slider_admin_page[] = add_menu_page('Sliders List', 'Sliders', 'manage_options', 'kads-slider-sliders-items', 'kads_sliders_options', 'dashicons-format-gallery', 30);
    $kads_slider_admin_page[] = add_submenu_page('kads-slider-sliders-items', 'Add new slider', 'Add New', 'manage_options', 'kads-slider', 'kads_slider_add_new_slider');
    foreach ($kads_slider_admin_page as $page) {
        add_action('load-' . $page, 'kads_slider_admin_add_help_tab');
        add_action("admin_print_scripts-$page", 'kads_slider_admin_script');
    }
}

add_action('admin_menu', 'kads_sliders_menu');

/**
 * Initializes WordPress hooks admin
 */
function kads_slider_admin_script() {
    wp_enqueue_style('kads-animate-style', kads_slider_get_file_uri('assets/css/animate.css'));
    wp_enqueue_style('kads-slider-style', kads_slider_get_file_uri('assets/css/style.css'));
    wp_enqueue_style('kads-jquery-ui-css', kads_slider_get_file_uri('assets/css/jquery-ui.min.css'));
    wp_enqueue_style('kads-fontawesome', kads_slider_get_file_uri('assets/css/font-awesome.min.css'));
    wp_enqueue_style('kads-admin-style', kads_slider_get_file_uri('assets/css/admin-style.css'), array('wp-color-picker'));
    wp_enqueue_style('kads-admin-sumoselect', kads_slider_get_file_uri('assets/sumoselect/sumoselect.css') );


    wp_enqueue_script('kads-json3', kads_slider_get_file_uri('assets/js/json3.min.js') , array('jquery'), FALSE, TRUE);
    wp_enqueue_script('kads-sumoselect', kads_slider_get_file_uri('assets/sumoselect/jquery.sumoselect.min.js') , array('jquery'), FALSE, TRUE);

    wp_enqueue_script('kads-fnc', kads_slider_get_file_uri('assets/js/fnc.js'), array('jquery'), FALSE, TRUE);

    wp_enqueue_script(
        'kads-jquery-kads-slider-fnc', kads_slider_get_file_uri('assets/js/kads-slider-manager.js'), 
        array(
            'jquery', 'wp-color-picker', 'jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-position',
            'jquery-ui-mouse', 'jquery-ui-draggable', 'jquery-ui-droppable', 'jquery-ui-resizable',
            'jquery-ui-selectable','jquery-ui-sortable', 'jquery-ui-accordion', 'jquery-ui-menu',
            'jquery-ui-autocomplete', 'jquery-ui-button', 'jquery-ui-dialog', 'jquery-ui-progressbar',
            'jquery-ui-selectmenu', 'jquery-ui-slider', 'jquery-ui-spinner','jquery-ui-tabs',
            'jquery-ui-tooltip', 'jquery-effects-core','jquery-effects-blind', 'jquery-effects-bounce',
            'jquery-effects-clip',  'jquery-effects-drop','jquery-effects-explode','jquery-effects-fade',
            'jquery-effects-fold','jquery-effects-highlight','jquery-effects-puff','jquery-effects-pulsate',
            'jquery-effects-scale','jquery-effects-shake','jquery-effects-size','jquery-effects-slide',
            'jquery-effects-transfer'
        ), 
        FALSE, TRUE);
}

/**
 * Register slider
 *
 * @since 1.0.0
 */
function kads_slider_register_slider() {
    $labels = array(
        'name' => _x('Slider', 'post type general name', 'kads-slider'),
        'singular_name' => _x('Slider', 'post type singular name', 'kads-slider'),
        'add_new' => __('Add New', 'kads-slider'),
        'add_new_item' => __('Add New Slider', 'kads-slider'),
        'edit_item' => __('Edit Slider', 'kads-slider'),
        'new_item' => __('New Slider', 'kads-slider'),
        'all_items' => __('All Slider', 'kads-slider'),
        'view_item' => __('View Slider', 'kads-slider'),
        'search_items' => __('Search Slider', 'kads-slider'),
        'not_found' => __('No sliders found', 'kads-slider'),
        'not_found_in_trash' => __('No sliders found in Trash', 'kads-slider'),
        'parent_item_colon' => '',
        'menu_name' => _x('Slider', 'post type general name', 'kads-slider'),
    );

    $args = array(
        'labels' => $labels,
        'public' => false,
        'publicly_queryable' => false,
        'show_ui' => true,
        'show_in_menu' => false,
        'query_var' => true,
        'capability_type' => 'post',
        'has_archive' => false,
        'hierarchical' => false,
        'exclude_from_search' => true,
        'menu_position' => null,
        'menu_icon' => 'dashicons-camera',
        'supports' => array('title')
    );
    register_post_type('kadsslider', $args);
}

add_action('init', 'kads_slider_register_slider');

/**
 * List sliders for theme
 *
 * @since 1.0.0
 */
function kads_sliders_options() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    $args = array();
    $args['sliders'] = kads_slider_get_sliders();
    kads_slider_template('slider-admin', $args);
}

function kads_slider_options() {
    $arg = array(
        array(
            'name' => 'startposition',
            'type' => 'number',
            'label' => __('Start Position', 'kads-slider'),
            'default' => '0',
            'desc' => __("Start position or URL Hash string like '#id'.", 'kads-slider'),
        ),
        array(
            'name' => 'loop',
            'type' => 'yesno',
            'label' => __('Loop', 'kads-slider'),
            'default' => '0',
            'desc' => __('Infinity loop. Duplicate last and first items to get loop illusion.', 'kads-slider'),
        ),
        array(
            'name' => 'items',
            'type' => 'number',
            'label' => __('Number of Items', 'kads-slider'),
            'default' => '1',
            'desc' => __('The number of items you want to see on the screen.', 'kads-slider'),
        ),
        array(
            'name' => 'nav',
            'type' => 'yesno',
            'label' => __('Navigation', 'kads-slider'),
            'default' => '0',
            'desc' => __("Show next/prev buttons.", 'kads-slider'),
        ),
        array(
            'name' => 'dots',
            'type' => 'yesno',
            'label' => __('Navigation Dots', 'kads-slider'),
            'default' => '0',
            'desc' => __("Show dots navigation.", 'kads-slider'),
        ),
        array(
            'name' => 'fullwidth',
            'type' => 'yesno',
            'label' => __('Full Width', 'kads-slider'),
            'default' => '0',
            'desc' => __("Full Width.", 'kads-slider'),
        ),
        array(
            'name' => 'fullheight',
            'type' => 'yesno',
            'label' => __('Full Height', 'kads-slider'),
            'default' => '0',
            'desc' => __("Full Height.", 'kads-slider'),
        ),
        array(
            'name' => 'overplay',
            'type' => 'yesno',
            'label' => __('Slider Overplay', 'kads-slider'),
            'default' => '0',
            'desc' => __("Will add Over for slider", 'kads-slider'),
        ),
        array(
            'name' => 'autoplay',
            'type' => 'yesno',
            'label' => __('Autoplay', 'kads-slider'),
            'default' => '0',
            'desc' => __("Autoplay of Slider", 'kads-slider'),
        ),
        array(
            'name' => 'autoplaytimeout',
            'type' => 'number',
            'label' => __('Autoplay Timeout', 'kads-slider'),
            'default' => '5000',
            'desc' => __("Timeout when Slider Autoplay", 'kads-slider'),
        ),
        array(
            'name' => 'autoplayhoverpause',
            'type' => 'yesno',
            'label' => __('Hover Pause', 'kads-slider'),
            'default' => '0',
            'desc' => __("Autoplay Pause on mouse hover.", 'kads-slider'),
        ),
        array(
            'name' => 'speed',
            'type' => 'number',
            'label' => __('Speed', 'kads-slider'),
            'default' => '250',
            'desc' => __("Pause on mouse hover.", 'kads-slider'),
        ),
        array(
            'name' => 'mousedrag',
            'type' => 'yesno',
            'label' => __('Mouse Drag', 'kads-slider'),
            'default' => '1',
            'desc' => __('Mouse drag enabled.', 'kads-slider'),
        ),
        array(
            'name' => 'touchdrag',
            'type' => 'yesno',
            'label' => __('Touch Drag', 'kads-slider'),
            'default' => '1',
            'desc' => __('Touch drag enabled.', 'kads-slider'),
        ),
        array(
            'name' => 'pulldrag',
            'type' => 'yesno',
            'label' => __('Pull Drag', 'kads-slider'),
            'default' => '1',
            'desc' => __('Pull drag enabled.', 'kads-slider'),
        ),
        array(
            'name' => 'freedrag',
            'type' => 'yesno',
            'label' => __('Free Drag', 'kads-slider'),
            'default' => '0',
            'desc' => __('Free Drag enabled.', 'kads-slider'),
        ),
        array(
            'name' => 'video',
            'type' => 'yesno',
            'label' => __('Video', 'kads-slider'),
            'default' => '0',
            'desc' => __("Enable fetching YouTube/Vimeo/Vzaar videos.", 'kads-slider'),
        ),
        array(
            'name' => 'videoheight',
            'type' => 'number',
            'label' => __('Video Height', 'kads-slider'),
            'default' => '0',
            'desc' => __("Set height for videos.", 'kads-slider'),
        ),
        array(
            'name' => 'animate',
            'type' => 'yesno',
            'label' => __('Animate', 'kads-slider'),
            'default' => '0',
            'desc' => __("Enable Animate Css3 only", 'kads-slider'),
        ),
        array(
            'name' => 'animatein',
            'type' => 'select',
            'label' => __('Animate In', 'kads-slider'),
            'default' => '',
            'list' => kads_slider_control_list_animate(),
            'desc' => __("Class for CSS3 animation in.", 'kads-slider'),
        ),
        array(
            'name' => 'animateout',
            'type' => 'select',
            'label' => __('Animate Out', 'kads-slider'),
            'default' => '',
            'list' => kads_slider_control_list_animate(),
            'desc' => __("Class for CSS3 animation out.", 'kads-slider'),
        ),
        array(
            'name' => 'fallbackeasing',
            'type' => 'select',
            'label' => __('Animate css2', 'kads-slider'),
            'default' => 'swing',
            'list' => kads_slider_control_list_animate_css2(),
            'desc' => __("Easing for CSS2 $.animate.", 'kads-slider'),
        ),
        array(
            'name' => 'center',
            'type' => 'yesno',
            'label' => __('Center', 'kads-slider'),
            'default' => '0',
            'desc' => __('Center item. Works well with even an odd number of items.', 'kads-slider'),
        ),
        array(
            'name' => 'margin',
            'type' => 'number',
            'label' => __('Margin', 'kads-slider'),
            'default' => '0',
            'desc' => __('Margin-right(px) on item.', 'kads-slider'),
        ),
        array(
            'name' => 'stagepadding',
            'type' => 'number',
            'label' => __('Stage Padding', 'kads-slider'),
            'default' => '0',
            'desc' => __('Padding left and right on stage (can see neighbours).', 'kads-slider'),
        ),
    );
    return $arg;
}

/**
 * add new slider for theme
 *
 * @since 1.0.0
 */
function kads_slider_add_new_slider() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    $args = array();
    $post = kads_slider_requested_arguments(INPUT_POST);
    if (isset($post['action']) && $post['action'] == 'save-slider') {
        $data_saved = kads_slider_save_actions($post);
        if (isset($data_saved['messagse'])) {
            $args['messagse'] = $data_saved['messagse'];
        } else {
            //wp_redirect('admin.php?page=kads-slider&id=9');
            
            $args['slider'] = kads_slider_get_slider($data_saved);
            $args['nonce'] = wp_create_nonce('kads-slider-manager-' . date("l"));
            $args['kads_slider_options'] = kads_slider_options();
            $args['fonts'] = kads_slider_list_font_family();

            kads_slider_template('slider-add', $args);
        }
    }
    else{
        
        $id = absint(filter_input(INPUT_GET, 'id'));
        $args['slider'] = kads_slider_get_slider($id);
        $args['nonce'] = wp_create_nonce('kads-slider-manager-' . date("l"));
        $args['kads_slider_options'] = kads_slider_options();
        $args['fonts'] = kads_slider_list_font_family();

        kads_slider_template('slider-add', $args);
    }
}





function kads_slider_admin_add_help_tab() {
    global $kads_slider_admin_page;
    $screen = get_current_screen();

    /*
     * Check if current screen is My Admin Page
     * Don't add help tab if it's not
     */
    if (!in_array($screen->id, $kads_slider_admin_page)) {
        return;
    }


    // Add my_help_tab if current screen is My Admin Page
    $screen->add_help_tab(array(
        'id' => 'my_help_tab',
        'title' => __('My Help Tab'),
        'content' => '<p>' . __('Descriptive content that will show in My Help Tab-body goes here.') . '</p>',
    ));
}



function kads_slider_start_session() {
    if (!session_id()) {
        session_start();
    }
    return session_id();
}

/**
 * Control for theme
 *
 * @since 1.0.0
 */
function kads_slider_control($option = array(), $data = '', $value = '') {
    if (!is_array($option)) {
        return;
    }
    $type = isset($option['type']) ? $option['type'] : 'text';

    if (!isset($option['name']) && !empty($option['name'])) {
        return;
    }
    $name = $option['name'];
    if (!empty($data)) {
        $name = $data . '[' . $name . ']';
    }
    if ($value == '') {
        $value = isset($option['default']) ? $option['default'] : '';
    }
    $name_class = kads_slider_url_seo($name);
    switch ($type) {
        case 'select':
            $list = isset($option['list']) ? $option['list'] : array();
            ?>
            <div class="kads-slider-control kads-slider-control-select">
                <select class="control-select" name="<?php echo esc_attr($name) ?>"  autocomplete="off">
                    <?php foreach ($list as $key => $text) : ?>
                    <option value="<?php echo esc_attr($key) ?>" <?php selected($key, $value)?>><?php echo esc_attr($text) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php
            break;
        case 'number':
            $min = isset($option['min']) ? $option['min'] : '0';
            $max = isset($option['max']) ? $option['max'] : '999999';
            ?>
            <div class="kads-slider-control kads-slider-control-number">
                <div class="quantity">
                    <input type="text" name="<?php echo esc_attr($name) ?>" autocomplete="off" min="<?php echo esc_attr($min) ?>" max="<?php echo esc_attr($max) ?>" step="1" value="<?php echo esc_attr($value) ?>">
                </div>
            </div>
            <?php
            break;
        case 'yesno':
            $ivalue = absint($value);
            $no_lable = ' selected';
            $yes_lable = '';
            if ($ivalue) {
                $no_lable = '';
                $yes_lable = ' selected';
            }
            ?>
            <div class="kads-slider-control kads-slider-control-yesno">
                <label class="yesno yes<?php echo esc_attr($yes_lable) ?>" for="kads-slider-yesno-control-<?php echo esc_attr($name_class) ?>">
                    <input class="screen-reader-text" autocomplete="off" id="kads-yesno-control-<?php echo esc_attr($name_class) ?>" name="<?php echo esc_attr($name) ?>" <?php checked($ivalue, 1) ?> type="radio" value="1">
                    <span class="button display-options">
                        <?php _e('Yes', 'kads-slider') ?>
                    </span>
                </label>
                <label class="yesno no<?php echo esc_attr($no_lable) ?>" for="kads-slider-yesno-control-<?php echo esc_attr($name_class) ?>">
                    <input class="screen-reader-text" autocomplete="off" id="kads-yesno-control-<?php echo esc_attr($name_class) ?>" name="<?php echo esc_attr($name) ?>"  <?php checked($ivalue, 0) ?> type="radio" value="0">
                    <span class="button display-options">
                        <?php _e('No', 'kads-slider') ?>
                    </span>
                </label>
            </div>
            <?php
            break;

        default:
            ?>
            <div class="kads-slider-control kads-slider-control-text">
                <input type="text" autocomplete="off" name="<?php echo esc_attr($name) ?>"  value="<?php echo esc_attr($value) ?>" />
            </div>
            <?php
            break;
    }
}

/**
 * Get slider object for given ID and 'edit' filter context.
 *
 * @since 1.0.0
 *
 * @param int $id
 * @return object
 */
function kads_slider_get_slider($id) {
    if (!$id) {
        $slider = new stdClass();
        $slider->ID = 0;
        $slider->post_author = 0;
        $slider->post_content = '';
        $slider->post_content_filtered = '';
        $slider->post_title = '';
        $slider->post_excerpt = '';
        $slider->post_type = '';
        $slider->post_name = '';
        $slider->comment_status = '';
        $slider->comment_status = '';
        $slider->contents = array();

        return 0;
    }
    $slider = get_post($id, OBJECT);
    
    $contents = kads_slider_build_contents($slider->post_content);
        
    $slider->contents = $contents; 
    return $slider;
}

function kads_slider_build_contents($content) {
    $contents = (array)json_decode($content);
    $items = kads_slider_object_to_array($contents);
    if($items['images'])
    {
        $image = array();
        foreach ($items['images'] as $data) {
            $decode = urldecode($data);
            $arg2 = (array) json_decode($decode);
            $child = kads_slider_object_to_array($arg2);
            $child['data'] = $data;
            $image[] = $child;
        }
        $items['images'] = $image;
    }
    return $items;
}

function kads_slider_build_contents_frontend($content) {
    $items = json_decode($content);
    
    if($items->images)
    {
        $arr = kads_slider_object_to_array((array)$items->images);
        $image = array();
        foreach ($arr as $data) {
            $decode = urldecode($data);
            $arg2 = (array) json_decode($decode);
            $child = kads_slider_object_to_array($arg2);
            $child['data'] = $data;
            $image[] = $child;
        }
        $items->images = $image;
    }
    $mItems = (array)$items;
    $arrParam = array();
    $animate = (isset($mItems['animate'])? absint($mItems['animate']):0);
    foreach ($mItems as $k => $v) {
        $n = absint($v);
        switch ($k) {
            case 'items':
                if($n){
                    $arrParam['items'] = 'items:'.$v;
                }
                else{
                    $arrParam['items'] = 'items:1';
                }
                break;
            case 'fullwidth':
                if($n){
                    $arrParam['layer'] = 'layer:true';
                    $arrParam['layerFullWidth'] = 'layerFullWidth:true';
                }
                break;
            case 'fullheight':
                if($n){
                    $arrParam['layer'] = 'layer:true';
                    $arrParam['layerFullHeight'] = 'layerFullHeight:true';
                }
                break;
            case 'overplay':
                if($n){
                    $arrParam['layer'] = 'layer:true';
                    $arrParam['layerOverplay'] = 'layerOverplay:true';
                }
                break;
            case 'autoplay':
                if($n){
                    $arrParam['layer'] = 'layer:true';
                    $arrParam['layerAutoPlay'] = 'layerAutoPlay:true';
                }
                break;
            case 'autoplaytimeout':
                $arrParam['layer'] = 'layer:true';
                if($n){
                    $arrParam['layerTimeout'] = 'layerTimeout:'.$n;
                }
                break;
            case 'speed':
                if($n){
                    $arrParam['smartSpeed'] = 'smartSpeed:'.$n;
                }
                break;
            case 'nav':
                if($n){
                $arrParam['nav'] = 'nav:true';
                $arrParam['navText'] = 'navText: [\'<i class="fas fa-angle-left"></i>\', \'<i class="fas fa-angle-right"></i>\']';
                $arrParam['navClass'] = 'navClass: [\'owl-prev\', \'owl-next\']';
                }
                break;
            case 'dots':
                if($n){
                    $arrParam['dots'] = 'dots:true';
                }
                else{
                    $arrParam['dots'] = 'dots:false';
                }
                break;
            case 'video':
                if($n){
                $arrParam['video'] = 'video:true';
                }
                break;
            case 'videoheight':
                if($n){
                    $arrParam['videoheight'] = 'videoheight:'.$n;
                }
                break;
            case 'animateout':
                if($v && $animate){
                    $arrParam['animateOut'] = 'animateOut:"' . $v . '"';
                }
                break;
            case 'animatein':
                if($v && $animate){
                    $arrParam['animateIn'] = 'animateIn:"' . $v . '"';
                }
                break;
            case 'fallbackeasing':
                if($v){
                    $arrParam['fallbackEasing'] = 'fallbackEasing:"' . $v . '"';
                }
                break;
            case 'loop':
                if($n){
                    $arrParam['loop'] = 'loop:true';
                }
            case 'center':
                if($n){
                    $arrParam['center'] = 'center:true';
                }
                else{
                    $arrParam['center'] = 'center:false';
                }
                break;
            case 'mousedrag':
                if(!$n){
                    $arrParam['mouseDrag'] = 'mouseDrag:false';
                }
                break;
            case 'touchdrag':
                if(!$n){
                    $arrParam['touchDrag'] = 'touchDrag:false';
                }
                break;
            case 'pulldrag':
                if(!$n){
                    $arrParam['pullDrag'] = 'pullDrag:false';
                }
                break;
            case 'freedrag':
                if($n){
                    $arrParam['freeDrag'] = 'freeDrag:true';
                }
                break;
            case 'margin':
                if($n){
                    $arrParam['margin'] = 'margin:' . $n ;
                }
                break;
            case 'stagepadding':
                if($n){
                    $arrParam['stagePadding'] = 'stagePadding:' . $n;
                }
                break;
            case 'startposition':
                if($n){
                    $arrParam['startPosition'] = 'startPosition:' . $n ;
                }
                break;
                
            default:
                break;
        }
    }
    $items->params = implode($arrParam, ',');
    return $items;
}

function kads_slider_object_to_array($items) {
    if (is_array($items) || is_object($items)) {
        $result = array();
        foreach ($items as $key => $value) {
            $result[$key] = kads_slider_object_to_array($value);
        }
        return $result;
    }
    return $items;
}

function kads_slider_set_session($key, $value) {
    $k = 'kads_slider_' . $key;
    $_SESSION[$k] = $value;
}

function kads_slider_get_session($key) {
    $k = 'kads_slider_' . $key;
    if (isset($_SESSION[$k])) {
        return $_SESSION[$k];
    }
    return '';
}

/**
 * Get sliders objects context.
 *
 * @since 1.0.0
 *
 * @return objects
 */
function kads_slider_get_sliders($paged = 0) {
    $offset = 0;
    if ($paged) {
        $offset = $paged * 20;
    }
    $args = array(
        'posts_per_page' => 20,
        'offset' => $offset,
        'post_type' => 'kadsslider',
    );
    $sliders = get_posts($args);
    return $sliders;
}

/**
 * Save post slider for theme
 *
 * @since 1.0.0
 */
function kads_slider_save_actions($post = array()) {

    $nonce = isset($post['nonce']) ? $post['nonce'] : '';

    if (!wp_verify_nonce($nonce, 'kads-slider-manager-' . date("l"))) {
        return array('messagse' => 'Sorry, you are not allowed to edit this slider.');
    }

    $slider_title = isset($post['title']) ? $post['title'] : '';
    if (empty($slider_title)) {
        return array('messagse' => 'The name not be empty');
    }

    $slider_setting = isset($post['slider']) ? $post['slider'] : array();
    $post_name = kads_slider_url_seo($slider_title);
    // Gather post data.
    $slider_post = array(
        'post_title' => $slider_title,
        'post_name' => $post_name,
        'post_content' => json_encode($slider_setting),
        'post_status' => 'publish',
        'post_type' => 'kadsslider',
    );
    if (isset($post['id']) && $post['id']) {
        $slider_post['ID'] = $post['id'];
        $post_id = wp_update_post($slider_post, true);
        if (is_wp_error($post_id)) {
            $errors = $post_id->get_error_messages();
            $messagse = array();
            foreach ($errors as $error) {
                $messagse[] = $error;
            }
            return array('messagse' => implode('<br/>', $messagse));
        }
    } else {
        if(!kads_slider_get_post_slider_name($post_name))
        {
            $post_id = wp_insert_post($slider_post);
        }
        else{
            return array('messagse' => 'The name already exists in the system please use a different name');
        }
    }
    // Insert the post into the database.

    if (!$post_id) {
        return array('messagse' => 'There has been an error processing your request.');
    }
    return $post_id;
}

/**
 * Get data from post and get
 *
 * @param int $type <p>
 * One of <b>INPUT_GET</b>, <b>INPUT_POST</b>,
 * <b>INPUT_COOKIE</b>, <b>INPUT_SERVER</b>, or
 * <b>INPUT_ENV</b>.
 * </p>
 * @since 1.0.0
 */
function kads_slider_requested_arguments($type) {
    $dataArray = ($tmp = filter_input_array($type)) ? $tmp : Array();
    return $dataArray;
}

function kads_slider_list_font_family() {
    $list = array(
        'sans-serif',
        'Verdana',
        'Tahoma',
        'Times New Roman',
        'Georgia',
        'Times',
        'Courier New',
        'Arial Black',
        'Arial',
        'serif',
        'Roboto'
    );
    return $list;
}

/**
 * Get sliders objects context.
 *
 * @since 1.0.0
 *
 * @return objects
 */
function kads_slider_control_yesno_button($key = '', $default = 1) {
    ?>
    <div class="hd-control-yesno hd-control-<?php echo esc_attr($key) ?>">
        <div id="hd-control-yesno-button-<?php echo esc_attr($key) ?>" class="selection controls-data" data-actions="button" data-key="<?php echo esc_attr($key) ?>">
            <label class="responsive-design yesno yes<?php echo $default ? ' selected' : '' ?>" for="kads-slider-yesno-yes-control-<?php echo esc_attr($key) ?>" data-key="<?php echo esc_attr($key) ?>" data-value="1">
                <input class="screen-reader-text" autocomplete="off" id="kads-slider-yesno-yes-control-<?php echo esc_attr($key) ?>" name="hd-control-<?php echo esc_attr($key) ?>" data-key="<?php echo esc_attr($key) ?>" <?php checked($default, 1) ?> type="radio" value="1">
                <span class="button display-options">
    <?php _e('Yes', 'kads-slider') ?>
                </span>
            </label>
            <label class="responsive-design yesno no<?php echo $default ? '' : ' selected' ?>" for="kads-slider-yesno-no-control-<?php echo esc_attr($key) ?>" data-key="<?php echo esc_attr($key) ?>" data-value="0">
                <input class="screen-reader-text" autocomplete="off" id="kads-slider-yesno-no-control-<?php echo esc_attr($key) ?>" name="hd-control-<?php echo esc_attr($key) ?>" data-key="<?php echo esc_attr($key) ?>" type="radio" value="0"  <?php checked($default, 0) ?>>
                <span class="button display-options" >
    <?php _e('No', 'kads-slider') ?>
                </span>
            </label>
        </div>
    </div>
    <?php
}

/**
 * Get sliders objects context.
 *
 * @since 1.0.0
 *
 * @return objects
 */
function kads_slider_control_text_select($key = '', $list = array(), $args = array()) {

    $class = isset($args['class']) ? $args['class'] : 'w-2';
    $data = isset($args['data']) ? $args['data'] : array('key');
    $default = isset($args['default']) ? $args['default'] : '';
    $label = isset($args['label']) ? $args['label'] : '';
    $icon = isset($args['icon']) ? $args['icon'] : '';
    $att = '';
    foreach ($data as $value) {
        $att .= 'data-' . $value . '="' . esc_attr($key) . '" ';
    }
    ?>
    <span class="list-font">
    <?php if (!empty($label) || !empty($icon)): ?>
            <span><span class="<?php echo esc_attr($icon) ?>"></span><?php echo esc_attr($label) ?></span>
        <?php endif; ?>
        <input type="text" id="hd-<?php echo esc_attr($key) ?>" class="<?php echo esc_attr($class) ?> input-style-change input-change controls-data" data-actions="text" <?php echo __($att) ?>  value="<?php echo esc_attr($default) ?>"> 
        <button id="bt-hd-<?php echo esc_attr($key) ?>" type="button" class="button button-style button-style-text-select"><span class="dashicons dashicons-arrow-down"></span></button>
        <script>
            (function ($) {
                $(document).ready(function () {
                    $("#hd-<?php echo esc_attr($key) ?>").autocomplete({
                        source: ['<?php echo trim(implode("','", $list), ',') ?>'],
                        minLength: 0,
                        select: function (event, ui) {
                            $('#hd-<?php echo esc_attr($key) ?>').kadsSliderChangeVal(ui.item.value);
                        }
                    });
                    $('#bt-hd-<?php echo esc_attr($key) ?>').click(function () {
                        $('#hd-<?php echo esc_attr($key) ?>').trigger("focus"); //or "click", at least one should work
                        $('#hd-<?php echo esc_attr($key) ?>').autocomplete('search', '');
                    });
                });
            })(jQuery);
        </script>
    </span>
    <?php
}
/**
 * Shortcode slider
 *
 * @since 1.0.0
 *
 * @return objects
 */
function kads_slider($atts) {
    $args = shortcode_atts(array(
        'title' => '',
        'id' => '',
        'animate' => 'fadeInUp',
            ), $atts, 'slider');
    $html = '';
    
    if(!empty($args['id']))
    {
        $args['slider'] = kads_slider_get_slider_name($args['id']);
        $html = kads_slider_get_template('slider', $args);
        return $html;
    }
    return $html;
}
add_shortcode('kads_slider', 'kads_slider');
/**
 * Get slider object for given ID and 'edit' filter context.
 *
 * @since 1.0.0
 *
 * @param int $name
 * @return object
 */
function kads_slider_get_post_slider_name($name) {
    if (empty($name)) {
        return 0;
    }
    
    
    $sliders = new WP_Query( array('post_type' => 'kadsslider', 'name' => $name ) );
    if(isset($sliders->post))
    {
        return $sliders->post;
    }
    return 0;
}

/**
 * Get slider object for given ID and 'edit' filter context.
 *
 * @since 1.0.0
 *
 * @param int $name
 * @return object
 */
function kads_slider_get_slider_name($name) {
    $slider = kads_slider_get_post_slider_name($name);
    if($slider)
    {
        $contents = kads_slider_build_contents_frontend($slider->post_content);
        $slider->contents = $contents;
    }
    return $slider;
}


/**
 * Get slider object for given ID and 'edit' filter context.
 *
 * @since 1.0.0
 *
 * @param int $name
 * @return object
 */
function kads_slider_ajax_manager_preview() {
    $post = kads_slider_requested_arguments(INPUT_POST);
    $slider = new stdClass();
    $slider->ID = '_'.rand(999, 9999);
    $slider_setting = isset($post['slider']) ? $post['slider'] : array();
    $slider->post_content = json_encode($slider_setting);
    $slider->contents = kads_slider_build_contents($slider->post_content);
    $args = array();
    $theme = 'slider';
    $args['slider'] = $slider;
    $html = kads_slider_get_template($theme, $args);
    wp_send_json_success($html);
}
add_action('wp_ajax_kads-slider-manager-preview', 'kads_slider_ajax_manager_preview');
add_action('wp_ajax_nopriv_kads-slider-manager-preview', 'kads_slider_ajax_manager_preview');




function kads_slider_control_list_animate() {

    return array(
        '' => '',
        'bounce' => 'bounce',
        'flash' => 'flash',
        'pulse' => 'pulse',
        'rubberBand' => 'rubberBand',
        'shake' => 'shake',
        'headShake' => 'headShake',
        'swing' => 'swing',
        'tada' => 'tada',
        'wobble' => 'wobble',
        'jello' => 'jello',
        'bounceIn' => 'bounceIn',
        'bounceInDown' => 'bounceInDown',
        'bounceInLeft' => 'bounceInLeft',
        'bounceInRight' => 'bounceInRight',
        'bounceInUp' => 'bounceInUp',
        'bounceOut' => 'bounceOut',
        'bounceOutDown' => 'bounceOutDown',
        'bounceOutLeft' => 'bounceOutLeft',
        'bounceOutRight' => 'bounceOutRight',
        'bounceOutUp' => 'bounceOutUp',
        'fadeIn' => 'fadeIn',
        'fadeInDown' => 'fadeInDown',
        'fadeInDownBig' => 'fadeInDownBig',
        'fadeInLeft' => 'fadeInLeft',
        'fadeInLeftBig' => 'fadeInLeftBig',
        'fadeInRight' => 'fadeInRight',
        'fadeInRightBig' => 'fadeInRightBig',
        'fadeInUp' => 'fadeInUp',
        'fadeInUpBig' => 'fadeInUpBig',
        'fadeOut' => 'fadeOut',
        'fadeOutDown' => 'fadeOutDown',
        'fadeOutDownBig' => 'fadeOutDownBig',
        'fadeOutLeft' => 'fadeOutLeft',
        'fadeOutLeftBig' => 'fadeOutLeftBig',
        'fadeOutRight' => 'fadeOutRight',
        'fadeOutRightBig' => 'fadeOutRightBig',
        'fadeOutUp' => 'fadeOutUp',
        'fadeOutUpBig' => 'fadeOutUpBig',
        'flipInX' => 'flipInX',
        'flipInY' => 'flipInY',
        'flipOutX' => 'flipOutX',
        'flipOutY' => 'flipOutY',
        'lightSpeedIn' => 'lightSpeedIn',
        'lightSpeedOut' => 'lightSpeedOut',
        'rotateIn' => 'rotateIn',
        'rotateInDownLeft' => 'rotateInDownLeft',
        'rotateInDownRight' => 'rotateInDownRight',
        'rotateInUpLeft' => 'rotateInUpLeft',
        'rotateInUpRight' => 'rotateInUpRight',
        'rotateOut' => 'rotateOut',
        'rotateOutDownLeft' => 'rotateOutDownLeft',
        'rotateOutDownRight' => 'rotateOutDownRight',
        'rotateOutUpLeft' => 'rotateOutUpLeft',
        'rotateOutUpRight' => 'rotateOutUpRight',
        'hinge' => 'hinge',
        'rollIn' => 'rollIn',
        'rollOut' => 'rollOut',
        'zoomIn' => 'zoomIn',
        'zoomInDown' => 'zoomInDown',
        'zoomInLeft' => 'zoomInLeft',
        'zoomInRight' => 'zoomInRight',
        'zoomInUp' => 'zoomInUp',
        'zoomOut' => 'zoomOut',
        'zoomOutDown' => 'zoomOutDown',
        'zoomOutLeft' => 'zoomOutLeft',
        'zoomOutRight' => 'zoomOutRight',
        'zoomOutUp' => 'zoomOutUp',
        'slideInDown' => 'slideInDown',
        'slideInLeft' => 'slideInLeft',
        'slideInRight' => 'slideInRight',
        'slideInUp' => 'slideInUp',
        'slideOutDown' => 'slideOutDown',
        'slideOutLeft' => 'slideOutLeft',
        'slideOutRight' => 'slideOutRight',
        'slideOutUp' => 'slideOutUp'
    );
}

function kads_slider_control_list_animate_css2() {
    return array(
        'swing' => 'Swing',
        'easeInQuad' => 'In Quad',
        'easeInCubic' => 'In Cubic',
        'easeInQuart' => 'In Quart',
        'easeInQuint' => 'In Quint',
        'easeInSine' => 'In Sine',
        'easeInExpo' => 'In Expo',
        'easeInCirc' => 'In Circ',
        'easeInBack' => 'In Back',
        'easeOutQuad' => 'Out Quad',
        'easeOutCubic' => 'Out Cubic',
        'easeOutQuart' => 'Out Quart',
        'easeOutQuint' => 'Out Quint',
        'easeOutSine' => 'Out Sine',
        'easeOutExpo' => 'Out Expo',
        'easeOutCirc' => 'Out Circ',
        'easeOutBack' => 'Out Back',
        'easeInOutQuad' => 'In Out Quad',
        'easeInOutCubic' => 'In Out Cubic',
        'easeInOutQuart' => 'In Out Quart',
        'easeInOutQuint' => 'In Out Quint',
        'easeInOutSine' => 'In Out Sine',
        'easeInOutExpo' => 'In Out Expo',
        'easeInOutCirc' => 'In Out Circ',
        'easeInOutBack' => 'In Out Back',
    );
}

function kads_slider_url_seo($string) {
    $trans = array(
        "đ" => "d", "ă" => "a", "â" => "a", "á" => "a", "à" => "a",
        "ả" => "a", "ã" => "a", "ạ" => "a",
        "ấ" => "a", "ầ" => "a", "ẩ" => "a", "ẫ" => "a", "ậ" => "a",
        "ắ" => "a", "ằ" => "a", "ẳ" => "a", "ẵ" => "a", "ặ" => "a",
        "é" => "e", "è" => "e", "ẻ" => "e", "ẽ" => "e", "ẹ" => "e",
        "ế" => "e", "ề" => "e", "ể" => "e", "ễ" => "e", "ệ" => "e",
        "í" => "i", "ì" => "i", "ỉ" => "i", "ĩ" => "i", "ị" => "i",
        "ư" => "u", "ô" => "o", "ơ" => "o", "ê" => "e",
        "Ư" => "u", "Ô" => "o", "Ơ" => "o", "Ê" => "e",
        "ú" => "u", "ù" => "u", "ủ" => "u", "ũ" => "u", "ụ" => "u",
        "ứ" => "u", "ừ" => "u", "ử" => "u", "ữ" => "u", "ự" => "u",
        "ó" => "o", "ò" => "o", "ỏ" => "o", "õ" => "o", "ọ" => "o",
        "ớ" => "o", "ờ" => "o", "ở" => "o", "ỡ" => "o", "ợ" => "o",
        "ố" => "o", "ồ" => "o", "ổ" => "o", "ỗ" => "o", "ộ" => "o",
        "ú" => "u", "ù" => "u", "ủ" => "u", "ũ" => "u", "ụ" => "u",
        "ứ" => "u", "ừ" => "u", "ử" => "u", "ữ" => "u", "ự" => "u",
        "ý" => "y", "ỳ" => "y", "ỷ" => "y", "ỹ" => "y", "ỵ" => "y",
        "Ý" => "Y", "Ỳ" => "Y", "Ỷ" => "Y", "Ỹ" => "Y", "Ỵ" => "Y",
        "Đ" => "D", "Ă" => "A", "Â" => "A", "Á" => "A", "À" => "A",
        "Ả" => "A", "Ã" => "A", "Ạ" => "A",
        "Ấ" => "A", "Ầ" => "A", "Ẩ" => "A", "Ẫ" => "A", "Ậ" => "A",
        "Ắ" => "A", "Ằ" => "A", "Ẳ" => "A", "Ẵ" => "A", "Ặ" => "A",
        "É" => "E", "È" => "E", "Ẻ" => "E", "Ẽ" => "E", "Ẹ" => "E",
        "Ế" => "E", "Ề" => "E", "Ể" => "E", "Ễ" => "E", "Ệ" => "E",
        "Í" => "I", "Ì" => "I", "Ỉ" => "I", "Ĩ" => "I", "Ị" => "I",
        "Ư" => "U", "Ô" => "O", "Ơ" => "O", "Ê" => "E",
        "Ư" => "U", "Ô" => "O", "Ơ" => "O", "Ê" => "E",
        "Ú" => "U", "Ù" => "U", "Ủ" => "U", "Ũ" => "U", "Ụ" => "U",
        "Ứ" => "U", "Ừ" => "U", "Ử" => "U", "Ữ" => "U", "Ự" => "U",
        "Ó" => "O", "Ò" => "O", "Ỏ" => "O", "Õ" => "O", "Ọ" => "O",
        "Ớ" => "O", "Ờ" => "O", "Ở" => "O", "Ỡ" => "O", "Ợ" => "O",
        "Ố" => "O", "Ồ" => "O", "Ổ" => "O", "Ỗ" => "O", "Ộ" => "O",
        "Ú" => "U", "Ù" => "U", "Ủ" => "U", "Ũ" => "U", "Ụ" => "U",
        "Ứ" => "U", "Ừ" => "U", "Ử" => "U", "Ữ" => "U", "Ự" => "U",);

    //remove any '-' from the string they will be used as concatonater
    $str = trim($string);
    $str = str_replace('-', ' ', $str);

    $str = strtr($str, $trans);

    // remove any duplicate whitespace, and ensure all characters are alphanumeric
    $str = preg_replace(array('/\s+/', '/[^A-Za-z0-9\-]/'), array('-', ''), $str);

    // lowercase and trim
    $str = trim(strtolower($str));
    return $str;
}

function kads_slider_remove() {
    if (!isset($_POST['id'])) {
        wp_send_json_error();
    }
    $post_id = absint($_POST['id']);
    if($post_id){
        $slider =  get_post($post_id);

        if($slider->post_type == 'kadsslider'){
            wp_delete_post( $slider->ID, true );
        }
    }
    else{
        wp_send_json_error();
    }
    wp_send_json_success();
}

add_action('wp_ajax_kads_slider_ajax_remove', 'kads_slider_remove');
add_action('wp_ajax_nopriv_kads_slider_ajax_remove', 'kads_slider_remove');

