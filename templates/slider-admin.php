<?php

/* 
 *  Some infomation in this theme
 *  
 *  
 *  @package       WordPress
 *  @subpackage    Kadeck
 *  @author        huynhduy1985
 *  @link          http://kads.kadrealestate.com/
 *  @since         Kadeck 1.0
 */
wp_localize_script('kads-jquery-kads-slider-fnc', 'kadsOptions', array(
    'id' => $max_images,
    'ajax' => admin_url('admin-ajax.php'),
    'url' => kads_slider_get_file_uri('assets/')
));
?>
<div class="wrap">
    <div class="box-admin">
        <div class="kads-header-left">
            <h1 class="title"><span class="dashicons dashicons-images-alt2"></span><span class="color"><?php _e('Kadeck', 'kads-slider') ?></span><?php _e('Slider', 'kads-slider') ?></h1>
        </div>
        <div class="kads-header-right">
            <button type="button" class="button button-secondary"><?php _e('Help', 'kads-slider') ?></button>
        </div>
    </div>
            
    <div class="box-admin">
        <h3><?php _e('List sliders', 'kads-slider') ?></h3>
        <div class="box-list-items">
            <?php foreach ($sliders as $slider) {
                $contents = kads_slider_build_contents($slider->post_content);
                $slider_images = array();
                if (isset($contents['images'])) {
                    $slider_images = $contents['images'];
                } 
                ?>
            <div class="box-item">
                <a href="admin.php?page=kads-slider&id=<?php echo esc_attr($slider->ID)?>" title="<?php _e('Add new slider', 'kads-slider') ?>">
                    <span class="title">
                        <?php echo esc_html($slider->post_title)?>
                    </span>
                    <div class="content-list-item">
                        <?php 
                        if(isset($slider_images[0])){
                            $item = $slider_images[0];
                            if (isset($item['type']) && $item['type'] == 'image') {
                                $hd_style = '';
                                $hd_o = '';
                                if (isset($item['link']) && $item['link']) {
                                    $hd_o .='background-image: url(' . esc_url($item['link']) . ');';
                                }
                                if (isset($item['color']) && $item['color']) {
                                    $hd_o .='background-color: ' . esc_url($item['color']) . ';';
                                }
                                if ($hd_o) {
                                    $hd_style = ' style="' . $hd_o . '"';
                                }
                                ?>
                                <div class="box-image-admin">
                                    <span class="bg"<?php echo __($hd_style); ?>></span>
                                </div>    
                                <?php
                            } else {
                                ?>
                                <div class="box-image-admin">
                                    <span class="box-image-icons">
                                        <span class="dashicons dashicons-video-alt3"></span>
                                    </span>
                                </div>    
                                <?php
                            }
                        }
                        
                        ?>
                    </div>
                </a>
                <div class="box-item-shortcode">
                    <div class="float-left">
                    [kads_slider id="<?php echo esc_attr($slider->post_name)?>"]
                    </div>
                    <div class="float-right">
                        <button class="bt-icons bt-remove" type="button" data-title="<?php echo esc_attr($slider->post_title)?>" data-id="<?php echo esc_attr($slider->ID)?>" title="<?php _e('Remove Slider', 'kads-slider')?>">
                            <span class="dashicons dashicons-no-alt"></span>
                        </button>
                        <button class="bt-icons bt-copy" type="button" data-id="<?php echo esc_attr($slider->ID)?>" title="<?php _e('Copy Slider', 'kads-slider')?>">
                            <span class="dashicons dashicons-admin-page"></span>
                        </button>
                    </div>
                </div>
            </div>
            <?php }?>
            <div class="box-item box-item-add">
                <div class="box-content-add">
                    <h4>
                        <a href="admin.php?page=kads-slider" title="<?php _e('Add new slider', 'kads-slider') ?>">
                        <?php _e('Add new slider', 'kads-slider') ?><br>
                        <span class="dashicons dashicons-plus"></span>
                        </a>
                    </h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="kads-slider-loading">
    <div class="kads-slider-overplay"></div>
    <div class="kads-slider-loading-warp">
        <div class="kads-slider-loading-content">
            <img class="loading-img" src="<?php echo kads_slider_get_file_uri('assets/images/loading.gif'); ?>" >
            <span><?php _e('Loading data...', 'kads-slider') ?></span>
        </div>
    </div>
</div>