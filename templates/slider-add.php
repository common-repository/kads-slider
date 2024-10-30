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

global $kads_slider_max_id;

if (!isset($kads_slider_options)) {
    return;
}
$icons = 'welcome-add-page';
$addnew = __('Add New', 'kads-slider');
if ($slider->ID) {
    $icons = 'edit';
    $addnew = __('Edit', 'kads-slider');
}

wp_enqueue_media();
if (!empty($messagse)) {
    print '<div id="message" class="updated notice is-dismissible"><p>' . $messagse . '</p></div>';
}
$kads_size = array("8px", "9px", "10px", "11px", "12px", "14px", "16px", "18px", "20px", "22px", "24px", "26px", "28px", "36px", "72px");
$slider_images = array();
$slidervalues = array();
if($slider->contents){
    $slidervalues = $slider->contents;
}
$max_images = 0;
if (isset($slidervalues['images'])) {
    $slider_images = $slidervalues['images'];
    $max_images = count($slider_images) - 1;
}
$kads_slider_max_id = $max_images;
$timeslinesize = 56;
wp_localize_script('kads-jquery-kads-slider-fnc', 'kadsOptions', array(
    'id' => $max_images,
    'ajax' => admin_url('admin-ajax.php'),
    'url' => kads_slider_get_file_uri('assets/')
));


?>

<div class="kads-slider-page-admin">
    <div class="wrap">
        <div class="box-admin">
            <div class="kads-slider-header-left">
                <h1 class="title"><span class="dashicons dashicons-<?php echo esc_attr($icons) ?>"></span><span class="color"><?php echo esc_attr($addnew) ?></span><?php _e('Slider', 'kads-slider') ?></h1>
            </div>
            <div class="kads-slider-header-right">
                <button type="button" class="button button-secondary"><?php _e('Help', 'kads-slider') ?></button>
            </div>
        </div>
        <form id="sliders_form" method="post" name="form" autocomplete="off" action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">
            <input id="actions-form" type="hidden" name="action" value="save-slider"/>
            <input type="hidden" name="id" value="<?php echo esc_attr($slider->ID) ?>"/>
            <input type="hidden" name="nonce" value="<?php echo esc_attr($nonce) ?>"/>


            <div class="blocks">
                <h3 class="title-line">
                    <?php _e('Kadeck slider', 'kads-slider') ?>
                </h3>
                <div class="block-two">
                    <div class="block">
                        <label>
                            <?php _e('Title slider', 'kads-slider') ?>
                        </label>
                        <input type="text" name="title" placeholder="<?php _e('Title here', 'kads-slider') ?>" size="30" value="<?php echo esc_attr($slider->post_title) ?>" id="title" spellcheck="true" autocomplete="off" />
                    </div>
                    <div class="block">
                        <label>
                            <?php _e('Shortcode slider', 'kads-slider') ?>
                        </label>
                        <input type="text" class="block-shortcode" value="<?php echo esc_attr('[kads_slider id="' . $slider->post_name . '"]') ?>" id="title" spellcheck="true" autocomplete="off" />
                    </div>
                </div>
            </div>

            <div class="block-wap">
                <div class="block-admin">
                    <div class="box-list-items">
                        <?php
                        if ($slider_images) {
                            foreach ($slider_images as $k => $item) {
                                ?>
                                <div class="box-item" id="box-images-<?php echo esc_attr($k) ?>"  data-id="<?php echo esc_attr($k) ?>">
                                    <div class="box-item-edit box-item-actions" title="Edit item">
                                        <?php
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
                                            <a class="box-image-edit manage-events" href="#" actions="edit-image">
                                                <span class="bg"<?php echo __($hd_style); ?>></span>
                                            </a>    
                                            <?php
                                        } else {
                                            ?>
                                            <a class="box-image-edit box-type-videos manage-events" href="#" actions="edit-image">
                                                <span class="box-image-icons">
                                                    <span class="dashicons dashicons-video-alt3"></span>
                                                </span>
                                            </a>    
                                            <?php
                                        }
                                        $data = isset($item['data']) ? $item['data'] : '';
                                        $layers = isset($item['layers']) ? $item['layers'] : array();
                                        ?>

                                        <span class="box-layer-list">
                                            <?php
                                            foreach ($layers as $key => $layer) {
                                                $ltype = isset($layer['type']) ? $layer['type'] : 'html';
                                                switch ($ltype) {
                                                    case 'html':
                                                        ?>
                                                        <span class="layer"><?php print $layer['html'] ?></span>   
                                                        <?php
                                                        break;
                                                    case 'image':
                                                        ?>
                                                        <span class="layer"><span class="dashicons dashicons-format-image"></span></span>   
                                                        <?php
                                                        break;
                                                    case 'button':
                                                        ?>
                                                        <span class="layer"><span class="dashicons dashicons-share"></span></span>   
                                                        <?php
                                                        break;
                                                    default:
                                                        ?>
                                                        <span class="layer"><span class="dashicons dashicons-format-video"></span></span>   
                                                        <?php
                                                        break;
                                                }
                                            }
                                            ?>
                                        </span>
                                        <span class="box-layer-wrap">
                                            <a class="box-layer-actions box-image-edit manage-events" actions="copy-image" title="Copy image">
                                                <span class="dashicons dashicons-admin-page"></span>
                                            </a>
                                            <a class="box-layer-actions box-image-edit manage-events" actions="edit-image" title="Edit image">
                                                <span class="dashicons dashicons-edit"></span>
                                            </a>
                                            <a class="box-layer-actions box-image-preview manage-events" actions="preview-image" title="Preview image">
                                                <span class="dashicons dashicons-visibility"></span>
                                            </a>
                                            <a class="box-layer-actions box-image-delete manage-events" actions="remove-image" title="Remove image">
                                                <span class="dashicons dashicons-trash"></span>
                                            </a>
                                        </span>
                                        <input class="item" name="slider[images][]" value="<?php echo __($data) ?>" type="hidden">
                                    </div>
                                </div>

                                <?php
                            }
                        }
                        ?>
                        <div class="box-item box-item-add">
                            <span class="image-bg dashicons dashicons-admin-media"></span>
                            <div class="box-image-add">
                                <h4>
                                    <a href="#" class="add-new-image manage-events" title="<?php _e('Add new image', 'kads-slider') ?>" actions="add-image">
                                        <?php _e('Add new image', 'kads-slider') ?><br>
                                        <span class="dashicons dashicons-plus"></span>
                                    </a>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="sidebar">
                    <div class="sidebar-inner">
                        <div class="sidebar-content">
                            <h3 class="title-line"><?php _e('Publish', 'kads-slider') ?> </h3>
                            <div class="content-actions">
                                <a class="button-preview button manage-events"  actions="slider-preview" href="#"  id="post-preview"><?php _e('Preview Changes', 'kads-slider') ?></a>
                            </div>
                            <div class="clear"></div>
                            <div class="publishing-actions">
                                <div id="delete-action">
                                    <a class="submitdelete deletion" href="#">
                                        <?php _e('Delete', 'kads-slider') ?>
                                    </a>
                                    <div class="clear"></div>
                                </div>
                                <div id="publishing-action">
                                    <span class="spinner"></span>
                                    <button type="submit"  class="button button-primary button-large" ><?php _e('Publish', 'kads-slider') ?> </button>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <div class="sidebar-content">
                            <h3><?php _e('Basic configuration', 'kads-slider') ?> </h3>
                            <table class="table-block">
                                <?php
                                foreach ($kads_slider_options as $option) :
                                    $label = isset($option['label']) ? $option['label'] : '';
                                    $desc = isset($option['desc']) ? $option['desc'] : '';
                                    $val = '';
                                    if(isset($option['name'])){
                                        $name = $option['name'];
                                        $val = isset($slidervalues[$name]) ? $slidervalues[$name] : '';
                                    }
                                    ?>
                                    <tr>
                                        <th><?php echo esc_html($label) ?></th>
                                        <td>
                                            <?php 
                                            kads_slider_control($option, 'slider',$val) ?>
                                            <p class="desc"><?php echo esc_html($desc) ?></p>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="slider-add-warper"> 
        <div class="slider-add-inner">
            <div class="slider-add-inner-bg">
                <input id="slider-current-edit" type="hidden" value="-1"/>
                <div class="slider-groups-actions">
                    <button type="button" class="button slider-save-button manage-events" actions="save-image" title="<?php _e('Save setting item', 'kads-slider') ?>"><span class="dashicons dashicons-migrate"></span><?php _e('Save', 'kads-slider') ?></button>
                    <button type="button" class="button slider-reset-button manage-events" actions="reset-image" title="<?php _e('Reset Old setting item', 'kads-slider') ?>"><span class="dashicons dashicons-controls-repeat"></span><?php _e('Reset', 'kads-slider') ?></button>
                    <button class="button button-close manage-events"  actions="close-image" type="button"><span class="dashicons dashicons-no-alt"></span> Close</button>
                </div>

                <div class="box-slider">
                    <div class="box-line">
                        <div class="control-row">
                            <span class="label label-main">
                                <?php _e('Image/Video', 'kads-slider') ?>
                            </span>
                            <div class="controls">
                                <input type="text" class="input input-main input-change input-main-change" data-key="main-images"/>
                                <span class="color-main">
                                    <span title="Background color" class="label"><span class="dashicons dashicons-art"></span></span>
                                    <input class="color-picker-hex input-change input-main-change" data-key="color" id="main-background" autocomplete="off" type="text" maxlength="7" value="#FFFFFF" data-default-color="#FFFFFF" />
                                </span>
                                <button type="button" class="button upload-button button-primary slider-upload-button manage-events" actions="upload-button" data-action="main" data-type="image, video" title="<?php _e('Choose in wordpress media', 'kads-slider') ?>"><?php _e('Choose media', 'kads-slider') ?></button>
                            </div>
                        </div>
                    </div>
                    <div class="box-line">
                        <div class="control-row">
                            <div class="admin-rows">
                                <div class="col-6">
                                    <span class="label label-main">
                                        <?php _e('Animate', 'kads-slider') ?>
                                    </span>
                                    <div class="controls">
                                        <select id="main-animation" autocomplete="off"  data-key="mainanimate" ismain="1" class="select-style">
                                            <option value="fade" selected="selected"><?php _e('Fade', 'kads-slider')?></option>
                                            <option value="slide"><?php _e('Slide', 'kads-slider')?></option>
                                            <option value="boxrandom"><?php _e('Box Random', 'kads-slider')?></option>
                                            <option value="boxrain"><?php _e('Box Rain', 'kads-slider')?></option>
                                            <option value="boxrainreverse"><?php _e('Box Rain Reverse', 'kads-slider')?></option>
                                            <option value="boxraingrow"><?php _e('Box Rain Grow', 'kads-slider')?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="slider-editor">
                        <h3 class="title-line">
                            <?php _e('Layer Edit', 'kads-slider') ?>
                        </h3>
                        <div class="wapper-add-layer">
                            <div class="add-layer">
                                <span class="dashicons dashicons-editor-kitchensink"></span> <?php _e('Add Layer', 'kads-slider') ?>
                                <ul>
                                    <li>
                                        <a class="add-html-text manage-events" actions="add-html-layer">
                                            <span class="dashicons dashicons-menu"></span> <?php _e('Add html/text', 'kads-slider') ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="add-image manage-events" actions="add-image-layer">
                                            <span class="dashicons dashicons-format-image"></span> <?php _e('Add Image', 'kads-slider') ?>
                                        </a>
                                    </li>

                                    <li>
                                        <a class="add-video manage-events" actions="add-video-layer">
                                            <span class="dashicons dashicons-video-alt"></span> <?php _e('Add video', 'kads-slider') ?>
                                        </a>
                                    </li>

                                    <li>
                                        <a class="add-button manage-events" actions="add-button-layer">
                                            <span class="dashicons dashicons-share-alt"></span> <?php _e('Add Button', 'kads-slider') ?>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="list-control">
                            <div class="hd-control">
                                <div class="hd-control-row">
                                    <?php kads_slider_control_text_select('font-family', $fonts, array('class' => 'w-7')) ?>
                                    <?php kads_slider_control_text_select('font-size', $kads_size) ?>
                                    <?php kads_slider_control_text_select('line-height', $kads_size, array('icon' => 'fa fa-text-height')) ?>
                                    <?php kads_slider_control_text_select('letter-spacing', array("1px", "2px", "3px", "4px", "5px", "7px", "9px", "12px"), array('icon' => 'fa fa-text-width')) ?>

                                </div>
                                <div class="hd-control-row">
                                    <button type="button" class="button button-style style-1 button-style-key manage-events" actions="add-style-key" id="hd-font-weight" data-key="font-weight" data-value="700" title="Bold"><span class="dashicons dashicons-editor-bold"></span></button>
                                    <button type="button" class="button button-style style-1 button-style-key manage-events" actions="add-style-key" id="hd-font-style" data-key="font-style" data-value="italic" title="Italic"><span class="dashicons dashicons-editor-italic"></span></button>
                                    <button type="button" class="button button-style style-1 button-style-key manage-events" actions="add-style-key" id="hd-text-decoration" data-key="text-decoration" data-value="underline"  title="Underline"><span class="dashicons dashicons-editor-underline"></span></button>
                                    <button type="button" class="button button-style style-1 button-style-key manage-events" actions="add-style-key" id="hd-text-decoration-line-through" data-key="text-decoration" data-value="line-through" title="Strike Through"><span class="dashicons dashicons-editor-strikethrough"></span></button>
                                    <button type="button" class="button button-style style-1 button-style-key manage-events" actions="add-style-key" id="hd-text-transform-uppercase" data-key="text-transform" data-value="uppercase" title="To Uppercase"><?php _e('AA', 'kads-slider') ?></button>
                                    <button type="button" class="button button-style style-1 button-style-key manage-events" actions="add-style-key" id="hd-text-transform-lowercase" data-key="text-transform" data-value="lowercase" title="To Lowercase"><?php _e('aa', 'kads-slider') ?></button>
                                    <button class="button button-style style-1 button-style-key manage-events" actions="add-style-key" data-key="text-align" data-value="left" title="Text left"><span class="dashicons dashicons-editor-alignleft"></span></button>
                                    <button class="button button-style style-1 button-style-key manage-events" actions="add-style-key" data-key="text-align" data-value="center" title="Text center"><span class="dashicons dashicons-editor-aligncenter"></span></button>
                                    <button class="button button-style style-1 button-style-key manage-events" actions="add-style-key" data-key="text-align" data-value="right" title="Text right"><span class="dashicons dashicons-editor-alignright"></span></button>

                                </div>

                            </div>
                            <div class="hd-control">
                                <div class="hd-control-row">
                                    <button class="button button-style style-1 button-style-position manage-events" actions="add-style-position" data-key="left" title="Layer left"><span class="dashicons dashicons-align-left"></span></button>
                                    <button class="button button-style style-1 button-style-position manage-events" actions="add-style-position" data-key="center" title="Layer center"><span class="dashicons dashicons-align-center"></span></button>
                                    <button class="button button-style style-1 button-style-position manage-events" actions="add-style-position" data-key="right" title="Layer right"><span class="dashicons dashicons-align-right"></span></button>

                                    <span class="item-row item-row-fix">
                                        <span title="Text color" class="label"><span class="dashicons dashicons-editor-textcolor"></span></span>
                                        <input class="color-picker-hex input-change input-style-change input-style-color controls-data" data-actions="color" data-key="color" id="hd-color" type="text" autocomplete="off" maxlength="7"  value="#FFFFFF" data-default-color="#FFFFFF" />
                                    </span>
                                    <span class="item-row item-row-fix">
                                        <span title="Background color" class="label"><span class="dashicons dashicons-art"></span></span>
                                        <input class="color-picker-hex input-change input-style-change input-style-background controls-data" data-actions="color" data-key="background-color" id="hd-background" autocomplete="off" type="text" maxlength="7" value="#FFFFFF" data-default-color="#FFFFFF" />
                                    </span>
                                    <span class="item-row">
                                        <span title="HTML tags" class="label"><span class="dashicons dashicons-editor-code"></span></span>
                                        <select id="html-tags" class="select-style controls-data" data-actions="select" data-key="tags" autocomplete="off">
                                            <option value="div" selected="selected"><?php _e('Element DIV', 'kads-slider')?></option>
                                            <option value="p"><?php _e('Paragraph', 'kads-slider')?></option>
                                            <option value="h1"><?php _e('Heading H1', 'kads-slider')?></option>
                                            <option value="h2"><?php _e('Heading H2', 'kads-slider')?></option>
                                            <option value="h3"><?php _e('Heading H3', 'kads-slider')?></option>
                                            <option value="h4"><?php _e('Heading H4', 'kads-slider')?></option>
                                            <option value="h5"><?php _e('Heading H5', 'kads-slider')?></option>
                                            <option value="h6"><?php _e('Heading H6', 'kads-slider')?></option>
                                        </select>
                                    </span>
                                </div>

                                <div class="hd-control-row">
                                    <span class="item-row">
                                        <span title="Desktop" class="label"><span class="dashicons dashicons-desktop"></span></span>
                                        <?php kads_slider_control_yesno_button('desktop'); ?>
                                    </span>
                                    <span class="item-row">
                                        <span title="Desktop" class="label"><span class="dashicons dashicons-laptop"></span></span>
                                        <?php kads_slider_control_yesno_button('laptop'); ?>
                                    </span>

                                    <span class="item-row">
                                        <span title="desktop" class="label"><span class="dashicons dashicons-tablet"></span></span>
                                        <?php kads_slider_control_yesno_button('tablet'); ?>
                                    </span>
                                    <span class="item-row">
                                        <span title="desktop" class="label"><span class="dashicons dashicons-smartphone"></span></span>
                                        <?php kads_slider_control_yesno_button('smartphone'); ?>
                                    </span>
                                </div>
                            </div>
                            <div class="hd-control">
                                <div class="hd-control-row">
                                    <?php
                                    kads_slider_control_text_select('class', array("btn", "btn btn-primary", "btn btn-border", "line-title"), array(
                                        'data' => array('att', 'key'),
                                        'label' => 'Class: ',
                                        'class' => 'w-5'
                                            )
                                    )
                                    ?>
                                </div>
                            </div>
                            <div class="hd-control">
                                <span class="item-row">
                                    <span title="Gird" class="label"><?php _e('Gird: ', 'kads-slider')?></span>
                                    <select id="html-gird" autocomplete="off"  data-key="gird" class="select-style">
                                        <option value="0"><?php _e('Disable', 'kads-slider')?></option>
                                        <option value="10"><?php _e('Gird 10', 'kads-slider')?></option>
                                        <option value="20"><?php _e('Gird 20', 'kads-slider')?></option>
                                    </select>
                                </span>
                            </div>
                            <div class="hd-control" style="width: 100%; margin-bottom: 5px;">
                                <span class="item-row">
                                    <span title="Animation In" class="label"><?php _e('Animate In:', 'kads-slider')?> </span>
                                    <select id="html-animation-in" autocomplete="off"  data-key="animateIn" data-state="animateInState" state="0" class="select-style controls-data" data-actions="select">
                                        <option value="fade"><?php _e('Fade', 'kads-slider')?></option>
                                        <option value="bounce"><?php _e('Bounce', 'kads-slider')?></option>
                                        <option value="flash"><?php _e('Flash', 'kads-slider')?></option>
                                        <option value="pulse"><?php _e('Pulse', 'kads-slider')?></option>
                                        <option value="slingshot"><?php _e('Sling Shot', 'kads-slider')?></option>
                                        <option value="pulsate"><?php _e('Pulsate', 'kads-slider')?></option>
                                        <option value="heartbeat"><?php _e('Heart Beat', 'kads-slider')?></option>
                                        <option value="rubberBand"><?php _e('Rubber Band', 'kads-slider')?></option>
                                        <option value="shake"><?php _e('Shake', 'kads-slider')?></option>
                                        <option value="strobe"><?php _e('Strobe', 'kads-slider')?></option>
                                        <option value="spin"><?php _e('Spin', 'kads-slider')?></option>
                                        <option value="swing"><?php _e('Swing', 'kads-slider')?></option>
                                        <option value="tada"><?php _e('Tada', 'kads-slider')?></option>
                                        <option value="flip"><?php _e('Flip', 'kads-slider')?></option>
                                        <option value="lightSpeed"><?php _e('lightSpeed', 'kads-slider')?></option>
                                        <option value="rotate"><?php _e('Rotate', 'kads-slider')?></option>
                                        <option value="roll"><?php _e('Roll', 'kads-slider')?></option>
                                    </select>
                                </span>
                                <span class="item-row html-animation-in">
                                    <span title="From" class="label"><?php _e('From: ', 'kads-slider')?></span>
                                    <select id="html-animation-in-state" autocomplete="off"  data-key="animateInState" state="1" class="select-style controls-data" data-actions="select">
                                        
                                    </select>
                                </span>
                                <span class="item-row" style="margin-left: 15px;">
                                    <span title="Animation Out" class="label"><?php _e('Animate Out:', 'kads-slider')?> </span>
                                    <select id="html-animation-out" autocomplete="off"  data-key="animateOut" data-state="animateOutState" class="select-style controls-data" data-actions="select">
                                        <option value="fade"><?php _e('Fade', 'kads-slider')?></option>
                                        <option value="bounce"><?php _e('Bounce', 'kads-slider')?></option>
                                        <option value="flash"><?php _e('Flash', 'kads-slider')?></option>
                                        <option value="pulse"><?php _e('Pulse', 'kads-slider')?></option>
                                        <option value="slingshot"><?php _e('Sling Shot', 'kads-slider')?></option>
                                        <option value="pulsate"><?php _e('Pulsate', 'kads-slider')?></option>
                                        <option value="heartbeat"><?php _e('Heart Beat', 'kads-slider')?></option>
                                        <option value="rubberBand"><?php _e('Rubber Band', 'kads-slider')?></option>
                                        <option value="shake"><?php _e('Shake', 'kads-slider')?></option>
                                        <option value="strobe"><?php _e('Strobe', 'kads-slider')?></option>
                                        <option value="spin"><?php _e('Spin', 'kads-slider')?></option>
                                        <option value="swing"><?php _e('Swing', 'kads-slider')?></option>
                                        <option value="tada"><?php _e('Tada', 'kads-slider')?></option>
                                        <option value="flip"><?php _e('Flip', 'kads-slider')?></option>
                                        <option value="lightSpeed"><?php _e('lightSpeed', 'kads-slider')?></option>
                                        <option value="rotate"><?php _e('Rotate', 'kads-slider')?></option>
                                        <option value="roll"><?php _e('Roll', 'kads-slider')?></option>
                                    </select>
                                </span>
                                <span class="item-row html-animation-out">
                                    <span title="From" class="label">From: </span>
                                    <select id="html-animation-out-state" autocomplete="off"  data-key="animateOutState" state="2" class="select-style controls-data" data-actions="select">
                                    </select>
                                </span>

                            </div>
                        </div>

                        <div class="box-gird">
                            <div class="box-gird-image">

                            </div>
                            <div class="box-gird-bg">

                            </div>
                        </div>
                    </div>
                    <div class="kads-slider-timeline-cont"> 
                        <div id="kads-slider-layer-warp" class="kads-slider-layer-warp">
                            <div class="kads-slider-layer-list">
                                <div class="kads-slider-timeline-controls">
                                    <div class="kads-slider-timeline-current-time">
                                        <button class="button button-primary button-play manage-events" actions="layer-play"><span class="dashicons dashicons-controls-play"></span></button>
                                        <button class="button button-pause manage-events" actions="layer-pause"><span class="dashicons dashicons-controls-pause"></span></button>
                                    </div>
                                </div>
                                <div class="kads-slider-layer-row">
                                    <div class="kads-slider-layer-label">
                                        <span class="kads-slider-layer-icons">
                                            <span class="dashicons dashicons-dashboard"></span>
                                        </span>
                                        <span class="kads-slider-layer-labeltext"><?php _e('Slider background', 'kads-slider')?></span>
                                    </div>
                                </div> 
                                <div class="kads-slider-layer-row kads-slider-overplay-wrap">
                                    <div class="kads-slider-layer-label">
                                        <span class="kads-slider-layer-icons">
                                            <span class="dashicons dashicons-shield-alt"></span>
                                        </span>
                                        <span class="kads-slider-layer-labeltext"><?php _e('Slider Overplay', 'kads-slider')?></span>
                                    </div>
                                </div>
                                <ul class="kads-slider-child-layer-list kads-slider-list-sortable">

                                </ul>
                                <div class="kads-slider-layer-botspace"></div>
                            </div>
                            <div class="kads-slider-layer-frames" tabindex="0">
                                <div class="kads-slider-frames-rows" style="width: <?php echo esc_attr($timeslinesize * 100.55) ?>px">
                                    <div id="kads-slider-frames-times" class="kads-slider-frames-times">000</div>
                                    <div class="kads-slider-timeline-ruler-cont">
                                        <div class="kads-slider-timeline-ruler">
                                            <?php
                                            $firstI = ' first';
                                            for ($i = 0; $i <= $timeslinesize; $i++) {
                                                ?>
                                                <span class="kads-slider-timeline-lable<?php echo esc_attr($firstI) ?>"><?php echo esc_html($i) ?>s</span>
                                                <?php
                                                $firstI = '';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="kads-slider-frames-row">
                                        <div class="kads-slider-timeline-range" noat="1">
                                            <div style="width: 83px;" class="kads-slider-range kads-slider-range-bg"><div class="kads-slider-range-value">800</div></div>
                                        </div>
                                    </div> 
                                    <div class="kads-slider-frames-row kads-slider-overplay-wrap">
                                        <div class="kads-slider-timeline-range" noat="1">
                                            <div style="width: 83px;" class="kads-slider-range kads-slider-range-overplay"><div class="kads-slider-range-value">800</div></div>
                                        </div>
                                    </div>
                                    <ul class="kads-slider-ul-list-row">

                                    </ul>
                                    <div id="kads-slider-timeline-delayindicator" class="kads-slider-timeline-delayindicator" style="left: 260px;"></div>
                                </div>
                                <div class="kads-slider-scroll-timeline-wrap">
                                    <div class="kads-slider-scroll-timeline"></div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="kads-slider-actions-popup-bg add-actions-popup-bg"></div>
                <div class="kads-slider-actions-popup add-actions-popup">
                    <div class="popup-inner">
                        <a href="#" class="close"><span class="dashicons dashicons-trash"></span></a>
                        <div class="control-row">
                            <span class="label-full">
                                <?php _e('Media link', 'kads-slider') ?>
                            </span>
                            <div>
                                <input type="text" class="input input-layer"/>
                                <button type="button" class="button upload-button manage-events" actions="upload-button" data-action="layer" data-type="video" title="<?php _e('Choose in wordpress media', 'kads-slider') ?>"><?php _e('Select', 'kads-slider') ?></button>
                                <button type="button" class="button button-primary upload-ok" title="<?php _e('Save that', 'kads-slider') ?>"><span class="dashicons dashicons-yes"></span><?php _e('OK', 'kads-slider') ?></button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="kads-slider-actions-popup button-actions-popup">
                    <div class="popup-inner">
                        <a href="#" class="close manage-events" actions="button-popup-action" data-action="cancel"><span class="dashicons dashicons-no"></span></a>
                        <h4><?php _e('Set Value for Button', 'kads-slider') ?></h4>
                        <form id="button-form-popup">
                            <div class="control-row">
                                <span class="label">
                                    <?php _e('Url:', 'kads-slider') ?>
                                </span>
                                <input type="text" name="link" class="input input-link"/>
                            </div>
                            <div class="control-row">
                                <span class="label">
                                    <?php _e('Text:', 'kads-slider') ?>
                                </span>
                                <input type="text" name="html" class="input input-html"/>
                            </div>
                            <div class="control-row">
                                <span class="label-full">
                                    <?php _e('Style List Button ', 'kads-slider') ?>
                                </span>
                                <div class="list-style-button">
                                    <label class="bt-style-label"><input type="radio" class="bt-style bt-style-none" name="layout" checked="checked" value=""> <?php _e('Slider Style', 'kads-slider') ?></label>
                                    <label class="bt-style-label kads-simple-button default"><input type="radio" class="bt-style bt-style-default" name="layout" value="default"> <?php _e('Default Style Button', 'kads-slider') ?></label>
                                    <label class="bt-style-label kads-simple-button green"><input type="radio" class="bt-style bt-style-green" name="layout" value="green"> <?php _e('Style Button Green', 'kads-slider') ?></label>
                                    <label class="bt-style-label kads-simple-button gray"><input type="radio" class="bt-style bt-style-gray" name="layout" value="gray"> <?php _e('Style Button', 'kads-slider') ?></label>
                                    <label class="bt-style-label kads-simple-button blue"><input type="radio" class="bt-style bt-style-blue" name="layout" value="blue"> <?php _e('Style Button', 'kads-slider') ?></label>
                                    <label class="bt-style-label kads-simple-button red"><input type="radio" class="bt-style bt-style-red" name="layout" value="red"> <?php _e('Style Button', 'kads-slider') ?></label>
                                    <label class="bt-style-label kads-simple-button red-border"><input type="radio" class="bt-style bt-style-red-border" name="layout" value="red-border"> <?php _e('Style Button', 'kads-slider') ?></label>
                                </div>
                            </div>
                            <div class="control-row">
                                <div>
                                    <button type="button" class="button button-primary manage-events" actions="button-popup-action" data-action="save" title="<?php _e('Save that', 'kads-slider') ?>"><span class="dashicons dashicons-yes"></span><?php _e('OK', 'kads-slider') ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div style="display: none">
                    <div class="html-layer-template">
                        <div id="box-{0}" current="{0}" class="box box-layer ui-widget-content">
                            <div class="warper-edit">
                                <div class="actions-edit">
                                    <div class="actions-group actions-group-view">
                                        <button class="button button-style button-edit hd-edit-html manage-events" actions="layer-edit" title="Edit layer"><span class="dashicons dashicons-edit"></span></button>
                                        <button class="button button-style button-edit hd-delete-html manage-events" current="{0}" actions="layer-delete" title="Delete layer"><span class="dashicons dashicons-trash"></span></button>
                                    </div>
                                    <div class="actions-group actions-group-save">
                                        <button class="button button-style button-edit hd-save-html manage-events" actions="layer-save" title="Save layer"><span class="dashicons dashicons-yes"></span></button>
                                        <button class="button button-style button-edit hd-cancel-html manage-events" actions="layer-cancel" title="Cancel layer"><span class="dashicons dashicons-no-alt"></span></button>
                                    </div>
                                </div>
                                <div class="content-edit">{1}</div>
                                <div class="content-edit-textarea">
                                    <textarea>{1}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="html-add-item-template">
                        <div class="box-item" id="box-images-{0}">
                            <div  class="box-item-edit" data-id="{0}" title="Edit item">
                                <a class="box-image-edit manage-events" actions="edit-image" href="#"><span class="bg"></span></a>
                                <span class="box-layer-list"></span>
                                <span class="box-layer-wrap">
                                    <span class="box-layer-actions box-image-edit manage-events" actions="edit-image" title="Edit image"><span class="dashicons dashicons-edit"></span></span>
                                    <span class="box-layer-actions box-image-preview manage-events" actions="preview-image" title="Preview image"><span class="dashicons dashicons-visibility"></span></span>
                                    <span class="box-layer-actions box-image-delete manage-events" actions="remove-image" title="Remove image"><span class="dashicons dashicons-trash"></span></span>
                                </span>
                                <input type="hidden" class="item" name="slider[images][]" value=""/>
                            </div>
                        </div>
                    </div>
                    <div class="html-layer-timeline-label">
                        <li id="timelinelable-{0}" class="kads-slider-layer-item-{0} kads-slider-layer-item-label" data-id="{0}"> 
                            <div class="kads-slider-layer-row">
                                <div class="kads-slider-layer-label">
                                    <span class="kads-slider-layer-icons"><span class="dashicons dashicons-editor-textcolor"></span></span>
                                    <span class="kads-slider-layer-labeltext">layer</span>
                                </div>
                                <div class="kads-slider-layer-controls">
                                    <a href="#" class="kads-slider-layer-duplicate manage-events" actions="layer-copy" current="{0}" title="Duplicate">
                                        <i class="fa fa-clone" aria-hidden="true"></i>
                                    </a>
                                    <a href="#" class="kads-slider-layer-remove manage-events" actions="layer-delete" current="{0}" title="Remove">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div> 
                        </li>
                    </div>
                    <div class="html-layer-timeline">
                        <li id="timeline-{0}" class="kads-slider-layer-item-{0} kads-slider-layer-item-timeline" data-id="{0}"> 
                            <div class="kads-slider-frames-row">
                                <div class="kads-slider-timeline-range">
                                    <div style="width: 0px;" class="kads-slider-range kads-slider-range-delay"></div>
                                    <div style="width: 31px;" class="kads-slider-range kads-slider-range-show"><div class="kads-slider-range-value">300</div></div>
                                </div>
                            </div> 
                        </li>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="slider-preview-warper">
        <div class="container">
            <button type="button"  class="button preview-close manage-events" actions="preview-close" ><span class="dashicons dashicons-share-alt2"></span><?php _e('Close', 'kads-slider') ?> </button>
        </div>
        <div class="slider-preview-container">
            <div class="bg"></div>
            <div class="slider-preview-inner">
                <div class="box-gird-preview"></div>
            </div>
        </div>
    </div>
    <div class="slider-preview-all-warper">
        <div class="container">
            <button type="button"  class="button preview-close manage-events" actions="preview-all-close" ><span class="dashicons dashicons-share-alt2"></span><?php _e('Close', 'kads-slider') ?> </button>
        </div>
        <div id="slider-preview-all-warper" class="inner">
            
        </div>
    </div>
</div>