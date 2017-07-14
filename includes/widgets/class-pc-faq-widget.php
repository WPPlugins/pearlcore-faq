<?php
if (!defined('ABSPATH')) :
    exit; // Exit if accessed directly
endif;

/**
 * Handle Widget Functionality
 *
 * @class 		Woo_Bag_Widget
 * @version		1.0.0
 * @package		Woo_Bag/Classes/
 * @subpackage          Woo_Bag/includes
 * @category            Class
 * @author 		Creative Gaters
 */
class Pc_Faq_Widget extends WP_Widget {

    /**
     * Constructor
     */
    public function __construct() {

        $this->widget_cssclass = 'pc_faq_widget';
        $this->widget_description = __("Display the Faq in the sidebar with category.", PC_FAQ_TEXT_DOMAIN);
        $this->widget_id = 'pc_faq_widget';
        $this->widget_name = __('Pearlcore Faq', PC_FAQ_TEXT_DOMAIN);


        $this->settings = array(
            'pc_title' => array(
                'std' => 'Frequently Asked Questions',
                'type' => 'text',
                'label' => __('Faq Title', PC_FAQ_TEXT_DOMAIN),
            ),
            'pc_category' => array(
                'std' => '',
                'type' => 'select',
                'label' => __('Faq Category', PC_FAQ_TEXT_DOMAIN),
                'options' => pc_faq_get_custom_categories(),
            ),
            'pc_enable_ask_question' => array(
                'std' => 'no',
                'type' => 'radio',
                'label' => __('Enable Ask Question', PC_FAQ_TEXT_DOMAIN),
                'options' => array(
                    'yes' => 'Yes',
                    'no' => 'No'
                ),
            ),
        );
        $widget_ops = array(
            'classname' => $this->widget_cssclass,
            'description' => $this->widget_description
        );

        parent::__construct($this->widget_id, $this->widget_name, $widget_ops);
    }

    public function widget($args, $instance) {

        $pc_title = (isset($instance['pc_title'])) ? $instance['pc_title'] : '';
        $pc_category = (isset($instance['pc_category'])) ? $instance['pc_category'] : '';
        $pc_enable_ask_question = (isset($instance['pc_enable_ask_question'])) ? $instance['pc_enable_ask_question'] : '';
        $type = 'pc-faq';
        if ($pc_category):
            $query_args = array(
                'post_type' => $type,
                'post_status' => 'publish',
                'fa_category' => $pc_category,
                'numberposts' => 10,
            );
        else:
            $query_args = array(
                'post_type' => $type,
                'post_status' => 'publish',
                'numberposts' => 10,
            );
        endif;
        $this->widget_start($args, $instance);

        if ($pc_enable_ask_question == 'yes'):
            echo pc_get_site_faq($query_args, $pc_title).pc_faq_ask_question_form();
        else:
            echo pc_get_site_faq($query_args, $pc_title);
        endif;

        $this->widget_end($args);
    }

    /**
     * Output the html at the start of a widget
     *
     * @param  array $args
     * @return string
     */
    public function widget_start($args, $instance) {

        echo $args['before_widget'];

        if ($title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base)) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
    }

    /**
     * Output the html at the end of a widget
     *
     * @param  array $args
     * @return string
     */
    public function widget_end($args) {
        echo $args['after_widget'];
    }

    /**
     * form function.
     *
     * @see WP_Widget->form
     * @param array $instance
     */
    public function form($instance) {

        if (!$this->settings) {
            return;
        }
        foreach ($this->settings as $key => $setting) {
            $std = (isset($setting['std']) ? $setting['std'] : '');
            $value = isset($instance[$key]) ? $instance[$key] : $std;
            ?>
            <div class="<?php echo $key; ?>">
                <?php
                switch ($setting['type']) {

                    case 'text' :
                        ?>
                        <p>
                            <label for="<?php echo $this->get_field_id($key); ?>"><?php _e($setting['label'], PC_FAQ_TEXT_DOMAIN); ?></label>
                            <input class="widefat" id="<?php echo $key; ?>" name="<?php echo $this->get_field_name($key); ?>" type="text" value="<?php echo esc_attr($value); ?>" />
                        </p>
                        <?php
                        break;

                    case 'number' :
                        ?>
                        <p>
                            <label for="<?php echo $this->get_field_id($key); ?>"><?php _e($setting['label'], PC_FAQ_TEXT_DOMAIN); ?></label>
                            <input class="widefat" id="<?php echo esc_attr($this->get_field_id($key)); ?>" name="<?php echo $this->get_field_name($key); ?>" type="number" step="<?php echo esc_attr($setting['step']); ?>" min="<?php echo esc_attr($setting['min']); ?>" max="<?php echo esc_attr($setting['max']); ?>" value="<?php echo esc_attr($value); ?>" />
                        </p>
                        <?php
                        break;

                    case 'select' :
                        ?>
                        <p>
                            <label for="<?php echo $this->get_field_id($key); ?>"><?php _e($setting['label'], PC_FAQ_TEXT_DOMAIN); ?></label>
                            <select class="widefat" id="<?php echo esc_attr($this->get_field_id($key)); ?>" name="<?php echo $this->get_field_name($key); ?>">
                                <?php foreach ($setting['options'] as $option_key => $option_value) : ?>
                                    <option value="<?php echo esc_attr($option_key); ?>" <?php selected($option_key, $value); ?>><?php echo esc_html($option_value); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </p>
                        <?php
                        break;
                    case 'radio' :
                        ?>
                        <p>
                            <label for="<?php echo $this->get_field_id($key); ?>"><?php _e($setting['label'], PC_FAQ_TEXT_DOMAIN); ?></label>
                            <br/>
                            <?php foreach ($setting['options'] as $option_key => $option_value) : ?>
                                <input type="radio" value="<?php echo esc_attr($option_key); ?>" <?php checked($option_key, $value); ?> name="<?php echo $this->get_field_name($key); ?>"><?php echo esc_html($option_value); ?><br/>
                            <?php endforeach; ?>
                        </p>
                        <?php
                        break;

                    case 'checkbox' :
                        ?>
                        <p>
                            <input id="<?php echo esc_attr($this->get_field_id($key)); ?>" name="<?php echo esc_attr($this->get_field_name($key)); ?>" type="checkbox" value="1" <?php checked($value, 1); ?> />
                            <label for="<?php echo $this->get_field_id($key); ?>"><?php _e($setting['label'], PC_FAQ_TEXT_DOMAIN); ?></label>
                        </p>
                        <?php
                        break;
                }
                ?>
            </div>
            <?php
        }
    }

}

/**
 * Register Widgets
 *
 * @since 1.0.0
 */
function pc_register_widgets() {
    register_widget('Pc_Faq_Widget');
}

add_action('widgets_init', 'pc_register_widgets');
