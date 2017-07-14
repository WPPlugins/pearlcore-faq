<?php

/**
 * @package   Options_Framework
 * @license   GPL-2.0+
 */
class Pcs_Faq_Framework_Interface {

    /**
     * Generates the tabs that are used in the options menu
     */
    static function pcs_faq_framework_tabs() {
        $counter = 0;
        $options = & Pcs_Faq_Framework::_pcs_framework_options();
        $menu = '';
        if ($options):
            foreach ($options as $value) :
                // Heading for Navigation
                if ($value['type'] == "heading") :
                    $counter++;
                    $class = '';
                    $class = !empty($value['id']) ? $value['id'] : $value['name'];
                    $class = preg_replace('/[^a-zA-Z0-9._\-]/', '', strtolower($class)) . '-tab';
                    $menu .= '<a id="options-group-' . $counter . '-tab" class="nav-tab ' . $class . '" title="' . esc_attr($value['name']) . '" href="' . esc_attr('#options-group-' . $counter) . '">' . esc_html($value['name']) . '</a>';
                endif;
            endforeach;
        endif;
        return $menu;
    }

    /**
     * Generates the options fields that are used in the form.
     */
    static function pcs_faq_framework_fields() {

        global $allowedtags;

        // Gets the unique option id
        $option_name = pc_faq_setting_name();

        $settings = get_option($option_name);
        $options = & Pcs_Faq_Framework::_pcs_framework_options();

        $counter = 0;
        $menu = '';

        $pcs_field_count = 0;

        foreach ($options as $value) {
            $pcs_field_count++;
            $class = '';
            $val = '';
            $select_value = '';
            $pc_id = (isset($value['id'])) ? $value['id'] : '';
            $output = '';
            $std = (isset($value['std'])) ? $value['std'] : '';
            if (($pcs_field_count % 2) == 0):
                $class .= ' even ';
            else:
                $class .= ' odd ';
            endif;

            if (( $value['type'] != "heading" ) && ( $value['type'] != "info" )) {

                $value['id'] = preg_replace('/[^a-zA-Z0-9._\-]/', '', strtolower($value['id']));

                $id = 'section-' . $value['id'];

                $class .= 'section';
                if (isset($value['type'])) {
                    $class .= ' section-' . $value['type'];
                }
                if (isset($value['class'])) {
                    $class .= ' ' . $value['class'];
                }

                $output .= '<div id="' . esc_attr($id) . '" class="' . esc_attr($class) . '">' . "\n";
                if (isset($value['name'])) {
                    $output .= '<div class="pcs_field_left">' . "\n";
                    $output .= '<h4 class="heading">' . esc_html($value['name']) . '</h4>' . "\n";
                    $output .= '</div>' . "\n";
                }
                if ($value['type'] != 'editor') {
                    $output .= '<div class="option pcs_field_right">' . "\n" . '<div class="controls">' . "\n";
                } else {
                    $output .= '<div class="option pcs_field_right">' . "\n" . '<div>' . "\n";
                }
            }

            // Set default value to $val
            if (isset($value['std'])) {
                $val = $value['std'];
            }

            // If the option is already saved, override $val
            if (( $value['type'] != 'heading' ) && ( $value['type'] != 'info')) {
                if (isset($settings[($value['id'])])) {
                    $val = $settings[($value['id'])];
                    // Striping slashes of non-array options
                    if (!is_array($val)) {
                        $val = stripslashes($val);
                    }
                }
            }

            // If there is a description save it for labels
            $explain_value = '';
            if (isset($value['desc'])) {
                $explain_value = $value['desc'];
            }

            if (has_filter('optionsframework_' . $value['type'])) {
                $output .= apply_filters('optionsframework_' . $value['type'], $option_name, $value, $val);
            }


            switch ($value['type']) {

                // Basic text input
                case 'text':
                    $output .= '<input id="' . esc_attr($value['id']) . '" class="of-input" name="' . esc_attr($option_name . '[' . $value['id'] . ']') . '" type="text" value="' . esc_attr($val) . '" />';
                    break;

                // Password input
                case 'password':
                    $output .= '<input id="' . esc_attr($value['id']) . '" class="of-input" name="' . esc_attr($option_name . '[' . $value['id'] . ']') . '" type="password" value="' . esc_attr($val) . '" />';
                    break;

                // Textarea
                case 'textarea':
                    $rows = '8';

                    if (isset($value['settings']['rows'])) {
                        $custom_rows = $value['settings']['rows'];
                        if (is_numeric($custom_rows)) {
                            $rows = $custom_rows;
                        }
                    }

                    $val = stripslashes($val);
                    $output .= '<textarea id="' . esc_attr($value['id']) . '" class="of-input" name="' . esc_attr($option_name . '[' . $value['id'] . ']') . '" rows="' . $rows . '">' . esc_textarea($val) . '</textarea>';
                    break;

                // Select Box
                case 'select':
                    $output .= '<select class="of-input" name="' . esc_attr($option_name . '[' . $value['id'] . ']') . '" id="' . esc_attr($value['id']) . '">';

                    foreach ($value['options'] as $key => $option) {
                        $output .= '<option' . selected($val, $key, false) . ' value="' . esc_attr($key) . '">' . esc_html($option) . '</option>';
                    }
                    $output .= '</select>';
                    break;


                // Radio Box
                case "radio":
                    $name = $option_name . '[' . $value['id'] . ']';
                    foreach ($value['options'] as $key => $option) {
                        $id = $option_name . '-' . $value['id'] . '-' . $key;
                        $output .= '<input class="of-input of-radio" type="radio" name="' . esc_attr($name) . '" id="' . esc_attr($id) . '" value="' . esc_attr($key) . '" ' . checked($val, $key, false) . ' /><label for="' . esc_attr($id) . '">' . esc_html($option) . '</label>';
                    }
                    break;

                // Image Selectors
                case "images":
                    $name = $option_name . '[' . $value['id'] . ']';
                    foreach ($value['options'] as $key => $option) {
                        $selected = '';
                        if ($val != '' && ($val == $key)) {
                            $selected = ' of-radio-img-selected';
                        }
                        $output .= '<input type="radio" id="' . esc_attr($value['id'] . '_' . $key) . '" class="of-radio-img-radio" value="' . esc_attr($key) . '" name="' . esc_attr($name) . '" ' . checked($val, $key, false) . ' />';
                        $output .= '<div class="of-radio-img-label">' . esc_html($key) . '</div>';
                        $output .= '<img src="' . esc_url($option['image_url']) . '" alt="' . $option['name'] . '" class="of-radio-img-img' . $selected . '" onclick="document.getElementById(\'' . esc_attr($value['id'] . '_' . $key) . '\').checked=true;" />';
                    }
                    break;
                // Checkbox
                case "checkbox":
                    $val = (isset($settings[$pc_id])) ? $settings[$pc_id] : $std;
                    $output .= '<input '
                            . 'class="checkbox of-input" '
                            . 'type="hidden" '
                            . 'value="0" '
                            . 'name="' . esc_attr($option_name . '[' . $pc_id . ']') . '" />';
                    $output .= '<input '
                            . 'id="' . esc_attr($value['id']) . '" '
                            . 'class="checkbox of-input" '
                            . 'type="checkbox" '
                            . 'name="' . esc_attr($option_name . '[' . $value['id'] . ']') . '" '
                            . (($val) ? ' ' . 'checked="checked"' : '') . ' '
                            . ' />';
                    $output .= '<label class="explain" for="' . esc_attr($value['id']) . '">' . wp_kses($explain_value, $allowedtags) . '</label>';
                    break;

                // Multicheck
                case "multicheck":
                    foreach ($value['options'] as $key => $option) {
                        $checked = '';
                        $label = $option;
                        $option = preg_replace('/[^a-zA-Z0-9._\-]/', '', strtolower($key));

                        $id = $option_name . '-' . $value['id'] . '-' . $option;
                        $name = $option_name . '[' . $value['id'] . '_' . $option . ']';
                        $pc_multi = $pc_id . '_' . $option;

                        $val = '';
                        if (isset($settings[$pc_multi])):
                            $val = $settings[$pc_multi];
                        elseif (is_array($std) && in_array($option, $std)):
                            $val = $option;
                        endif;
                        $val = esc_html(esc_attr($val));
                        $output .= '<input class="checkbox of-input" type="hidden" name="' . esc_attr($name) . '" value="0" />';
                        $output .= '<input '
                                . 'id="' . esc_attr($id) . '" '
                                . 'class="checkbox of-input" '
                                . 'type="checkbox" '
                                . 'name="' . esc_attr($name) . '" '
                                . (('on' == $val) ? ' checked="checked"' : '') . ' '
                                . ' /><label for="' . esc_attr($id) . '">' . esc_html($label) . '</label>';
                    }
                    break;

                // Color picker
                case "color":
                    $default_color = '';
                    if (isset($value['std'])) {
                        if ($val != $value['std'])
                            $default_color = ' data-default-color="' . $value['std'] . '" ';
                    }
                    $output .= '<input name="' . esc_attr($option_name . '[' . $value['id'] . ']') . '" id="' . esc_attr($value['id']) . '" class="of-color"  type="text" value="' . esc_attr($val) . '"' . $default_color . ' />';

                    break;

                // Uploader
                case "upload":
                    $output .= Pcs_Framework_Media_Uploader::pcs_framework_uploader($value['id'], $val, null);

                    break;

                // Typography
                case 'typography':

                    unset($font_size, $font_style, $font_face, $font_color);

                    $typography_defaults = array(
                        'size' => '',
                        'face' => '',
                        'style' => '',
                        'color' => ''
                    );

                    $typography_stored = wp_parse_args($val, $typography_defaults);
                    $typography_options = array(
                        'sizes' => pc_faq_of_recognized_font_sizes(),
                        'faces' => pc_faq_of_recognized_font_faces(),
                        'styles' => pc_faq_of_recognized_font_styles(),
                        'color' => true
                    );

                    if (isset($value['options'])) {
                        $typography_options = wp_parse_args($value['options'], $typography_options);
                    }

                    // Font Size
                    if ($typography_options['sizes']) {
                        $pc_typography_size = (isset($settings[$pc_id . '_size'])) ? $settings[$pc_id . '_size'] : '';
                        if (!$pc_typography_size):
                            $pc_typography_size = (isset($typography_stored['size'])) ? $typography_stored['size'] : '';
                        endif;
                        $font_size = '<select class="of-typography of-typography-size" name="' . esc_attr($option_name . '[' . $pc_id . '_size]') . '" '
                                . 'id="' . esc_attr($pc_id . '_size') . '">';
                        $sizes = $typography_options['sizes'];
                        foreach ($sizes as $i) {
                            $size = $i . 'px';
                            $font_size .= '<option value="' . esc_attr($size) . '" ' . selected($pc_typography_size, $size, false) . '>' . esc_html($size) . '</option>';
                        }
                        $font_size .= '</select>';
                    }

                    // Font Face
                    if ($typography_options['faces']) {
                        $pc_typography_face = (isset($settings[$pc_id . '_face'])) ? $settings[$pc_id . '_face'] : '';
                        if (!$pc_typography_face):
                            $pc_typography_face = (isset($typography_stored['face'])) ? $typography_stored['face'] : '';
                        endif;
                        $font_face = '<select class="of-typography of-typography-face" name="' . esc_attr($option_name . '[' . $pc_id . '_face]') . '" '
                                . 'id="' . esc_attr($pc_id . '_face') . '">';
                        $faces = $typography_options['faces'];
                        foreach ($faces as $key => $face) {
                            $font_face .= '<option value="' . esc_attr($key) . '" ' . selected($pc_typography_face, $key, false) . '>' . esc_html($face) . '</option>';
                        }
                        $font_face .= '</select>';
                    }

                    // Font Styles
                    if ($typography_options['styles']) {
                        $pc_typography_style = (isset($settings[$pc_id . '_style'])) ? $settings[$pc_id . '_style'] : '';
                        if (!$pc_typography_style):
                            $pc_typography_style = (isset($typography_stored['style'])) ? $typography_stored['style'] : '';
                        endif;
                        $font_style = '<select class="of-typography of-typography-style" name="' . $option_name . '[' . $pc_id . '_style]" '
                                . 'id="' . $pc_id . '_style">';
                        $styles = $typography_options['styles'];
                        foreach ($styles as $key => $style) {
                            $font_style .= '<option value="' . esc_attr($key) . '" ' . selected($pc_typography_style, $key, false) . '>' . $style . '</option>';
                        }
                        $font_style .= '</select>';
                    }

                    // Font Color
                    if ($typography_options['color']) {
                        $pc_color_value = '';
                        $default_color = '';
                        if (isset($value['std']['color'])) {
                            $pc_color_value = $value['std']['color'];
                            if ($val != $value['std']['color'])
                                $default_color = ' data-default-color="' . $value['std']['color'] . '" ';
                        }
                        if (isset($settings[$pc_id . '_color']) && $settings[$pc_id . '_color'] != ''):
                            $pc_color_value = $settings[$pc_id . '_color'];
                        endif;

                        $font_color = '<input name="' . esc_attr($option_name . '[' . $pc_id . '_color]') . '" id="' . esc_attr($pc_id . '_color') . '" class="of-color of-typography-color"  type="text" '
                                . ''
                                . 'value="' . esc_attr($pc_color_value) . '"'
                                . '' . $default_color . ' />';
                    }

                    // Allow modification/injection of typography fields
                    $typography_fields = compact('font_size', 'font_face', 'font_style', 'font_color');
                    $typography_fields = apply_filters('pc_faq_of_typography_fields', $typography_fields, $typography_stored, $option_name, $value);
                    $output .= implode('', $typography_fields);

                    break;

                // Background
                case 'background':

                    $background = $val;

                    // Background Color
                    $default_color = '';
                    if (isset($value['std']['color'])) {
                        if ($val != $value['std']['color'])
                            $default_color = ' data-default-color="' . $value['std']['color'] . '" ';
                    }
                    $output .= '<input name="' . esc_attr($option_name . '[' . $value['id'] . '][color]') . '" id="' . esc_attr($value['id'] . '_color') . '" class="of-color of-background-color"  type="text" value="' . esc_attr($background['color']) . '"' . $default_color . ' />';

                    // Background Image
                    if (!isset($background['image'])) {
                        $background['image'] = '';
                    }

                    $output .= Pcs_Framework_Media_Uploader::pcs_framework_uploader($value['id'], $background['image'], null, esc_attr($option_name . '[' . $value['id'] . '][image]'));

                    $class = 'of-background-properties';
                    if ('' == $background['image']) {
                        $class .= ' hide';
                    }
                    $output .= '<div class="' . esc_attr($class) . '">';

                    // Background Repeat
                    $output .= '<select class="of-background of-background-repeat" name="' . esc_attr($option_name . '[' . $value['id'] . '][repeat]') . '" id="' . esc_attr($value['id'] . '_repeat') . '">';
                    $repeats = pc_faq_of_recognized_background_repeat();

                    foreach ($repeats as $key => $repeat) {
                        $output .= '<option value="' . esc_attr($key) . '" ' . selected($background['repeat'], $key, false) . '>' . esc_html($repeat) . '</option>';
                    }
                    $output .= '</select>';

                    // Background Position
                    $output .= '<select class="of-background of-background-position" name="' . esc_attr($option_name . '[' . $value['id'] . '][position]') . '" id="' . esc_attr($value['id'] . '_position') . '">';
                    $positions = pc_faq_of_recognized_background_position();

                    foreach ($positions as $key => $position) {
                        $output .= '<option value="' . esc_attr($key) . '" ' . selected($background['position'], $key, false) . '>' . esc_html($position) . '</option>';
                    }
                    $output .= '</select>';

                    // Background Attachment
                    $output .= '<select class="of-background of-background-attachment" name="' . esc_attr($option_name . '[' . $value['id'] . '][attachment]') . '" id="' . esc_attr($value['id'] . '_attachment') . '">';
                    $attachments = pc_faq_of_recognized_background_attachment();

                    foreach ($attachments as $key => $attachment) {
                        $output .= '<option value="' . esc_attr($key) . '" ' . selected($background['attachment'], $key, false) . '>' . esc_html($attachment) . '</option>';
                    }
                    $output .= '</select>';
                    $output .= '</div>';

                    break;

                // Editor
                case 'editor':
                    $output .= '<div class="explain">' . wp_kses($explain_value, $allowedtags) . '</div>' . "\n";
                    echo $output;
                    $textarea_name = esc_attr($option_name . '[' . $value['id'] . ']');
                    $default_editor_settings = array(
                        'textarea_name' => $textarea_name,
                        'media_buttons' => false,
                        'tinymce' => array('plugins' => 'wordpress,wplink')
                    );
                    $editor_settings = array();
                    if (isset($value['settings'])) {
                        $editor_settings = $value['settings'];
                    }
                    $editor_settings = array_merge($default_editor_settings, $editor_settings);
                    wp_editor($val, $value['id'], $editor_settings);
                    $output = '';
                    break;

                // Info
                case "info":
                    $id = '';
                    $class = 'section';
                    if (isset($value['id'])) {
                        $id = 'id="' . esc_attr($value['id']) . '" ';
                    }
                    if (isset($value['type'])) {
                        $class .= ' section-' . $value['type'];
                    }
                    if (isset($value['class'])) {
                        $class .= ' ' . $value['class'];
                    }

                    $output .= '<div ' . $id . 'class="' . esc_attr($class) . '">' . "\n";
                    if (isset($value['name'])) {
                        $output .= '<h4 class="heading">' . esc_html($value['name']) . '</h4>' . "\n";
                    }
                    if (isset($value['desc'])) {
                        $output .= '<div class="pcs_sub_section_desc">' . $value['desc'] . '</div>' . "\n";
                    }
                    $output .= '</div>' . "\n";
                    break;

                // Heading for Navigation
                case "heading":
                    $counter++;
                    if ($counter >= 2) {
                        $output .= '</div>' . "\n";
                    }
                    $class = '';
                    $class = !empty($value['id']) ? $value['id'] : $value['name'];
                    $class = preg_replace('/[^a-zA-Z0-9._\-]/', '', strtolower($class));
                    $output .= '<div id="options-group-' . $counter . '" class="group ' . $class . '">';
                    $output .= '<h3>' . esc_html($value['name']) . '</h3>' . "\n";
                    break;
            }

            if (( $value['type'] != "heading" ) && ( $value['type'] != "info" )) {
                $output .= '</div>';
                if (( $value['type'] != "checkbox" ) && ( $value['type'] != "editor" )) {
                    $output .= '<div class="explain">' . wp_kses($explain_value, $allowedtags) . '</div>' . "\n";
                }
                $output .= '</div></div>' . "\n";
            }

            echo $output;
        }

        // Outputs closing div if there tabs
        if (Pcs_Faq_Framework_Interface::pcs_faq_framework_tabs() != '') {
            echo '</div>';
        }
    }

}
