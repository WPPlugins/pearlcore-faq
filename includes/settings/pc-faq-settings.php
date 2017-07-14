<?php

/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 *
 */
function pcs_faq_option_name() {

    // This gets the theme name from the stylesheet (lowercase and without spaces)
    $themename = get_option('stylesheet');
    $themename = preg_replace("/\W/", "_", strtolower($themename));

    $optionsframework_settings = get_option('optionsframework');
    $optionsframework_settings['id'] = $themename;
    update_option('optionsframework', $optionsframework_settings);

    // echo $themename;
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 *
 */
function pcs_faq_options() {

    $login_label_defaults = array(
        'size' => '15px',
        'face' => 'georgia',
        'style' => 'bold',
        'color' => '#bada55'
    );

    // If using image radio buttons, define a directory path
    $imagepath = PC_FAQ_ASSETS_URL . 'images/';

    $options = array();

    $options[] = array(
        'name' => __('General Settings', PC_FAQ_TEXT_DOMAIN),
        'type' => 'heading',
        'id' => 'general_setting'
    );

    $options[] = array(
        'name' => __('Select Theme', GL_WPSLW_TEXT_DOMAIN),
        'desc' => "Images for layout.",
        'id' => "pc_faq_theme",
        'std' => "theme-a",
        'type' => "images",
        'options' => array(
            'theme-a' => array('name' => 'Theme 1', 'image_url' => $imagepath . 'theme/theme-a.png'),
            'theme-b' => array('name' => 'Theme 2', 'image_url' => $imagepath . 'theme/theme-b.png'),
        ),
        'wpslw_form_id' => 'general_setting'
    );
    
    $options[] = array(
        'name' => __('Faq Title Style', PC_FAQ_TEXT_DOMAIN),
        'id' => "pc_title_style",
        'std' => array(
            'size' => '18px',
            'face' => 'arial',
            'style' => 'bold',
            'color' => '#000'
        ),
        'type' => 'typography',
        'pc_form_id' => 'general_setting'
    );

    $options[] = array(
        'name' => __('Question Background Color', PC_FAQ_TEXT_DOMAIN),
        'id' => "pc_question_bg",
        'std' => '#f3f3f3',
        'type' => 'color',
        'pc_form_id' => 'general_setting'
    );

    $options[] = array(
        'name' => __('Question Toogle Icon Color', PC_FAQ_TEXT_DOMAIN),
        'id' => "pc_question_icon_color",
        'std' => '#000',
        'type' => 'color',
        'pc_form_id' => 'general_setting'
    );

    $options[] = array(
        'name' => __('Question Text Style', PC_FAQ_TEXT_DOMAIN),
        'id' => "pc_question_style",
        'std' => array(
            'size' => '14px',
            'face' => 'arial',
            'style' => 'normal',
            'color' => '#000'
        ),
        'type' => 'typography',
        'pc_form_id' => 'general_setting'
    );

    $options[] = array(
        'name' => __('Show Like Button And Count', PC_FAQ_TEXT_DOMAIN),
        'id' => "pc_like_show",
        'std' => 'no',
        'type' => 'radio',
        'options' => array(
            'yes' => 'Yes',
            'no' => 'No'
        ),
        'pc_form_id' => 'general_setting'
    );

    $options[] = array(
        'name' => __('Like Count Style', PC_FAQ_TEXT_DOMAIN),
        'id' => "pc_count_style",
        'std' => array(
            'size' => '12px',
            'face' => 'arial',
            'style' => 'normal',
            'color' => '#000'
        ),
        'type' => 'typography',
        'pc_form_id' => 'general_setting'
    );

    $options[] = array(
        'name' => __('Like Icon Color', PC_FAQ_TEXT_DOMAIN),
        'id' => "pc_like_icon_color",
        'std' => '#000',
        'type' => 'color',
        'pc_form_id' => 'general_setting'
    );

    $options[] = array(
        'name' => __('After Liked Icon Color', PC_FAQ_TEXT_DOMAIN),
        'id' => "pc_after_like_icon_color",
        'std' => '#5890ff',
        'type' => 'color',
        'pc_form_id' => 'general_setting'
    );
    
    $options[] = array(
        'name' => __('Show Date', PC_FAQ_TEXT_DOMAIN),
        'id' => "pc_date_show",
        'std' => 'no',
        'type' => 'radio',
        'options' => array(
            'yes' => 'Yes',
            'no' => 'No'
        ),
        'pc_form_id' => 'general_setting'
    );

    $options[] = array(
        'name' => __('Date Style', PC_FAQ_TEXT_DOMAIN),
        'id' => "pc_date_style",
        'std' => array(
            'size' => '12px',
            'face' => 'arial',
            'style' => 'normal',
            'color' => '#000'
        ),
        'type' => 'typography',
        'pc_form_id' => 'general_setting'
    );
    
    $options[] = array(
        'name' => __('Show Author', PC_FAQ_TEXT_DOMAIN),
        'id' => "pc_author_show",
        'std' => 'no',
        'type' => 'radio',
        'options' => array(
            'yes' => 'Yes',
            'no' => 'No'
        ),
        'pc_form_id' => 'general_setting'
    );

    $options[] = array(
        'name' => __('Author Style', PC_FAQ_TEXT_DOMAIN),
        'id' => "pc_author_style",
        'std' => array(
            'size' => '12px',
            'face' => 'arial',
            'style' => 'normal',
            'color' => '#27CCC0'
        ),
        'type' => 'typography',
        'pc_form_id' => 'general_setting'
    );

    $options[] = array(
        'name' => __('Products FAQ Settings', PC_FAQ_TEXT_DOMAIN),
        'type' => 'heading',
        'id' => 'product_setting'
    );

    $options[] = array(
        'name' => __('Show Faq on Single Product', PC_FAQ_TEXT_DOMAIN),
        'id' => "pc_show_faq_product",
        'std' => 'no',
        'type' => 'radio',
        'options' => array(
            'yes' => 'Yes',
            'no' => 'No'
        ),
        'pc_form_id' => 'product_setting'
    );

    $options[] = array(
        'name' => __('Tab Title', PC_FAQ_TEXT_DOMAIN),
        'id' => "pc_tab_title",
        'std' => 'Faq',
        'type' => 'text',
        'class' => 'mini',
        'pc_form_id' => 'product_setting'
    );

    $options[] = array(
        'name' => __('FAQ Title', PC_FAQ_TEXT_DOMAIN),
        'id' => "pc_faq_product_title",
        'std' => 'Frequently Asked Questions',
        'type' => 'text',
        'class' => '',
        'pc_form_id' => 'product_setting'
    );
    $options[] = array(
        'name' => __('FAQ Title Style', PC_FAQ_TEXT_DOMAIN),
        'id' => "pc_faq_product_title_style",
        'std' => array(
            'size' => '18px',
            'face' => 'arial',
            'style' => 'bold',
            'color' => '#000'
        ),
        'type' => 'typography',
        'pc_form_id' => 'product_setting'
    );

    $options[] = array(
        'name' => __('Show Like Button And Count', PC_FAQ_TEXT_DOMAIN),
        'id' => "pc_faq_product_like_show",
        'std' => 'no',
        'type' => 'radio',
        'options' => array(
            'yes' => 'Yes',
            'no' => 'No'
        ),
        'pc_form_id' => 'product_setting'
    );

    /**
     * @since      2.2
     */
    $options[] = array(
        'name' => __('Ask Question Settings', PC_FAQ_TEXT_DOMAIN),
        'type' => 'heading',
        'id' => 'ask_question_setting'
    );
    
    $options[] = array(
        'name' => __('Ask Question Title', PC_FAQ_TEXT_DOMAIN),
        'id' => "pc_faq_ask_question_title",
        'std' => 'Have a Question? Submit it here!',
        'type' => 'text',
        'class' => '',
        'pc_form_id' => 'ask_question_setting'
    );
    $options[] = array(
        'name' => __('Ask Question Style', PC_FAQ_TEXT_DOMAIN),
        'id' => "pc_faq_ask_question_title_style",
        'std' => array(
            'size' => '18px',
            'face' => 'arial',
            'style' => 'bold',
            'color' => '#000'
        ),
        'type' => 'typography',
        'pc_form_id' => 'ask_question_setting'
    );
    

    return $options;
}
