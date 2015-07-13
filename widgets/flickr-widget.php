<?php
/**
 * Flickr Widget
 *
 * Build the Flickr Widget
 *
 * @package     olympus-widgets
 * @copyright   Copyright (c) 2015, Danny Cooper
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

/**
 * Registers the Flickr Widget.
 */
class Olympus_Flickr_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
				parent::__construct(
					'olympus_flickr_widget',
					__( 'Olympus Flickr Widget', 'olympus-widgets' ),
					array( 'description' => __( 'A Simple Flickr Widget', 'olympus-widgets' ) )
				);
	}

	/**
	 * Outputs the content of the widget.
	 *
	 * @param array $args     The array of form elements.
	 * @param array $instance The current instance of the widget.
	 */
	public function widget( $args, $instance ) {
		extract( $args );

		$widget_title = isset( $instance['widget_title'] ) ? apply_filters( 'widget_title', $instance['widget_title'] ) : '';
		$widget_desc = $instance['widget_desc'];
		$flickr_id = $instance['flickr_id'];

		echo $args['before_widget'];

		if ( $widget_title ) {
			echo $args['before_title'] . $widget_title . $args['after_title'];
		}

		if ( ! empty( $widget_desc ) ) {
				echo '<div class="widget-description">' . wpautop( $widget_desc ) . '</div>';
		}

		if ( ! empty( $flickr_id ) ) {
			echo '<div class="flickr_badge_wrapper"><script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=6&display=latest&size=m&layout=x&source=user&user=' . esc_attr( $flickr_id ) . '"></script>
            <div class="clear"></div></div>';
		}

		echo $args['after_widget'];
	}

	/**
	 * Generates the administration form for the widget.
	 *
	 * @param array $instance The array of keys and values for the widget.
	 */
	public function form( $instance ) {

		// Get the options into variables, escaping html characters on the way.
		$widget_title = isset( $instance['widget_title'] ) ? esc_attr( $instance['widget_title'] ) : '';
		$widget_desc = isset( $instance['widget_desc'] ) ? esc_attr( $instance['widget_desc'] ) : '';
		$flickr_id = isset( $instance['flickr_id'] ) ? esc_attr( $instance['flickr_id'] ) : '';

		?>
        <p>Get your Flickr ID here: <a target="_blank" href="http://idgettr.com/">http://idgettr.com</a></p>

        <p>
        <label for="<?php echo $this->get_field_id( 'widget_title' ); ?>"><?php _e( 'Title:', 'olympus-widgets' ) ?></label>
        <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'widget_title' ); ?>" name="<?php echo $this->get_field_name( 'widget_title' ); ?>" value="<?php echo $widget_title; ?>" />
        </p>

        <p>
        <label for="<?php echo $this->get_field_id( 'widget_desc' ); ?>"><?php _e( 'Widget Text:', 'olympus-widgets' ); ?></label>
        <textarea class="widefat" id="<?php echo $this->get_field_id( 'widget_desc' ); ?>" name="<?php echo $this->get_field_name( 'widget_desc' ); ?>" rows="5" cols="20" ><?php echo $widget_desc; ?></textarea>
        </p>

        <p>
        <label for="<?php echo $this->get_field_id( 'flickr_id' ); ?>"><?php _e( 'Flickr ID:', 'olympus-widgets' ) ?></label>
        <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'flickr_id' ); ?>" name="<?php echo $this->get_field_name( 'flickr_id' ); ?>" value="<?php echo $flickr_id; ?>" />
        </p>

        <?php
	}

	/**
	 * Processes the widget's options to be saved.
	 *
	 * @param array $new_instance The new instance of values to be generated via the update.
	 * @param array $old_instance The previous instance of values before the update.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['widget_title'] = strip_tags( $new_instance['widget_title'] );
		$instance['flickr_id'] = $new_instance['flickr_id'];
		$instance['widget_desc'] = $new_instance['widget_desc'];

		return $instance;
	}

}//end class

// Register the widget.
add_action( 'widgets_init', function(){
	 register_widget( 'Olympus_Flickr_Widget' );
});
