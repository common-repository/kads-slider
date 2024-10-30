<?php
/*
 *  @package       WordPress
 *  @subpackage    Kadeck
 *  @author        huynhduy1985
 *  @link          http://kads.kadrealestate.com/
 *  @since         Kadeck 1.0
 *  
 *  
 */
if (!$slider) {
    return;
}
$jsKey = new kads_jsEncode();
$domain = $_SERVER['SERVER_NAME'];
// kads-slider

wp_enqueue_style('kads-jquery-ui-css', kads_slider_get_file_uri('assets/css/style.css'));
wp_enqueue_style('font-awesome-animation', kads_slider_get_file_uri('assets/css/font-awesome-animation.css'));
wp_enqueue_style('font-awesome', kads_slider_get_file_uri('assets/css/font-awesome.min.css'));
wp_enqueue_script('owl.carousel', kads_slider_get_file_uri('assets/js/owl.carousel.min.js'), array('jquery'), FALSE, TRUE);
wp_enqueue_script('owl.kads', kads_slider_get_file_uri('assets/js/owl.kads.min.js'), array('jquery'), FALSE, TRUE);
wp_localize_script('owl.carousel', 'kads_slider_options', array(
    'key' => $jsKey->encodeString($domain, 100),
    'ajax' => admin_url('admin-ajax.php'),
    'url' => kads_slider_get_file_uri('assets/')
));


$slider_options = $slider->contents;
$slider_images = array();
if (isset($slider_options->images)) {
    $slider_images = $slider_options->images;
}
$dataanimaile = array('fade', 'slide', 'slide');
?>
<div class="kads-slider-wrap">
    <div class="inner-wrap">
        <div class="owl-carousel" id="kads-slider-id-<?php echo $slider->ID ?>">
            <?php
            if ($slider_images) {
                $i = 0;
                foreach ($slider_images as $k => $item) {
                    $typeItem = isset($item['type']) ? $item['type'] : '';
                    ?>
                    <div class="kads-slider-item" id="box-images-<?php echo esc_attr($k) ?>" type="<?php echo esc_attr($typeItem) ?>" animate="<?php echo $dataanimaile[$i] ?>">
                        <div class="box-images-wraper">
                            <?php
                            if ($typeItem == 'image') {
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
                                <div class="box-image">
                                    <div class="bg"<?php echo __($hd_style); ?>></div>
                                </div>    
                                <?php
                            } else {
                                if (isset($item['html'])) {
                                    ?>
                                    <div class="box-image box-type-videos">
                                        <?php print $item['html']; ?>
                                    </div>    
                                    <?php
                                }
                            }
                            $data = isset($item['data']) ? $item['data'] : '';
                            $layers = isset($item['layers']) ? $item['layers'] : array();
                            ?>
                            <div class="layer-container">
                                <?php
                                $ij = 0;
                                foreach ($layers as $kl => $layer) {
                                    $att = '';
                                    $css = '';
                                    $class = '';
                                    $html = '';
                                    $tags = 'div';
                                    foreach ($layer as $key => $value) {
                                        switch ($key) {
                                            case 'el':
                                                break;
                                            case 'font-family':
                                            case 'font-size':
                                            case 'line-height':
                                            case 'letter-spacing':
                                                $value = str_replace(array('px', ' px'), '', $value);
                                                $css .= $key . ':' . $value . 'px;';
                                                break;

                                            case 'text-align':
                                            case 'color':
                                            case 'background-color':
                                                $css .= $key . ':' . $value . ';';
                                                break;

                                            case 'top':
                                            case 'left':
                                            case 'width':
                                            case 'height':
                                                $value = str_replace(array('px', ' px'), '', $value);
                                                $css .= $key . ':' . $value . 'px;';
                                                $att .= $key . ' = "' . $value . '" ';
                                                break;
                                            case 'wait':
                                                $att .= $key . ' = "' . (absint($value) * 10) . '" ';
                                                break;
                                            case 'speed':
                                                $att .= $key . ' = "' . (absint($value) / 10) . '" ';
                                                break;
                                            case 'desktop':
                                            case 'laptop':
                                            case 'tablet':
                                            case 'smartphone':
                                                $class .= 'hidden-' . $key . ' ';
                                                break;
                                            case 'layout':
                                            case 'class':
                                                $class .= $value . ' ';
                                                break;
                                            case 'type':
                                                if ($value == 'button') {
                                                    $class .= 'kads-i-button ';
                                                    $tags = 'a';
                                                }
                                                $att .= $key . ' = "' . $value . '" ';
                                                break;
                                            case 'link':
                                                $att .= 'href = "' . $value . '" ';
                                                break;
                                            case 'html':
                                                $html = $value;
                                                break;
                                            case 'tags':
                                                $tags = $value;
                                                break;
                                            default :
                                                $att .= $key . ' = "' . $value . '" ';
                                                break;
                                        }
                                    }
                                    if (empty($tags)) {
                                        $tags = 'div';
                                    }

                                    $ij++;
                                    echo sprintf('<%1$s id="layer-%6$s-%7$s" style="%2$s" class="%3$slayer-item layer-load" %4$s>%5$s</%1$s>', $tags, $css, $class, $att, $html, $k, $ij);
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php
                    $i++;
                }
            }
            ?>
        </div>
    </div>
</div>
<script>
    (function ($) {
        $(document).ready(function () {
            $('#kads-slider-id-<?php echo $slider->ID ?>').owlCarousel({<?php echo $slider_options->params ?>});
        });
    })(jQuery);
</script>