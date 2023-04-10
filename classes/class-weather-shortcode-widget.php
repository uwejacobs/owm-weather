<?php
/**
 * Weather widget class
 *
 * @since      1.0.0
 * @package    OWM_Widget
 * @subpackage Includes
 * @category   Classes
 */

namespace OWM_Widget\Classes;

class Weather_Shortcode_Widget extends \WP_Widget
{

    /**
     * Constructor magic method
     *
     * @since  1.0.0
     * @access public
     * @return self
     */
    public function __construct()
    {

        $options = [
            'classname'                   => 'owm-weather-shortcode-widget',
            'description'                 => __('Add OWM Weather from shortcode.', 'owm-weather'),
            'customize_selective_refresh' => true,
        ];

        // Run the parent constructor.
        parent :: __construct(
            'owm_weather_shortcode_widget',
            $name = __('OWM Weather Shortcode', 'owm-weather'),
            $options
        );
    }

    /**
     * Widget UI form
     *
     * @since  1.0.0
     * @access public
     * @param array $instance Current widget settings.
     * @return void
     */
    public function form($instance)
    {

        $instance  = wp_parse_args((array) $instance, [ 'title' => '', 'shortcode' => '' ]);
        $title     = $instance['title'];
        $shortcode = $instance['shortcode'];

        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'owm-weather'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
            <br /><span class="description"><?php _e('Title text will display above the weather content.', 'owm-weather'); ?></span>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('shortcode'); ?>"><?php _e('Shortcode:', 'owm-weather'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('shortcode'); ?>" type="text" value="<?php echo esc_attr($shortcode); ?>" />
            <br /><span class="description"><?php _e('Paste in the shortcode of the weather you would like to display.', 'owm-weather'); ?></span>
        </p>
        <?php
    }

    /**
     * Update the widget form
     *
     * @since  1.0.0
     * @access public
     * @param  array $new_instance New settings for this instance as input by the user via
     *                             WP_Widget::form().
     * @param  array $old_instance Old settings for this instance.
     * @return array Updated settings.
     */
    public function update($new_instance, $old_instance)
    {

        $instance              = $old_instance;
        $new_instance          = wp_parse_args((array) $new_instance, [ 'title' => '', 'shortcode' => '' ]);
        $instance['title']     = sanitize_text_field($new_instance['title']);
        $instance['shortcode'] = sanitize_text_field($new_instance['shortcode']);

        return $instance;
    }

    /**
     * Frontend widget display
     *
     * @since  1.0.0
     * @access public
     * @param  array $args Display arguments including 'before_title', 'after_title',
     *                     'before_widget', and 'after_widget'.
     * @param  array $instance Settings for the current widget instance.
     * @return void
     */
    public function widget($args, $instance)
    {

        if (! empty($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = '';
        }
        $shortcode = $instance['shortcode'];

        echo $args['before_widget'];
        if (! empty($title)) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        if (! empty($shortcode)) {
            echo do_shortcode($shortcode);
        }

        echo $args['after_widget'];
    }
}
