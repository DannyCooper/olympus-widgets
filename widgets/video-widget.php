<?php
/**
 * Video Widget
 *
 * Build the Video Widget
 *
 * @package     olympus-widgets
 * @copyright   Copyright (c) 2015, Danny Cooper
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

/**
 * Registers the Video Widget
 */
class Olympus_Video_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
				parent::__construct( 'olympus_video_widget',
					__( 'Olympus Video Widget', 'olympus-widgets' ),
					array( 'description' => __( 'A Simple Video Widget', 'olympus-widgets' ) )
				);
	}

	/**
	 * Outputs the content of the widget.
	 *
	 * @param array $args     The array of form elements.
	 * @param array $instance The current instance of the widget.
	 */
	public function widget($args, $instance) {

		$widget_title = isset( $instance['widget_title'] ) ? apply_filters( 'widget_title', $instance['widget_title'] ) : '';
		$video_url = $instance['video_url'];
		$widget_desc = $instance['widget_desc'];
		$video_width = absint( $instance['video_width'] );

		echo $args['before_widget'];

		if ( ! empty( $widget_title ) ) {
			echo $args['before_title'] . $widget_title . $args['after_title'];
		}

		if ( ! empty( $widget_desc ) ) {
			echo '<div class="widget-description">' . wpautop( $widget_desc ) . '</div>';
		}

		if ( strpos( $video_url,'.mp4' ) !== false ) : ?>

            <video controls>
              <source src="<?php echo esc_url( $video_url ); ?>" type="video/mp4">
            </video>
 
        <?php else :

			$embed_code = wp_oembed_get( $video_url, array( 'width' => $video_width ) );

			echo $embed_code;

		endif;

		echo $args['after_widget'];
	}

	/**
	 * Generates the administration form for the widget.
	 *
	 * @param array $instance The array of keys and values for the widget.
	 */
	public function form($instance) {

		// Get the options into variables, escaping html characters on the way.
		$widget_title = isset( $instance['widget_title'] ) ? esc_attr( $instance['widget_title'] ) : '';
		$video_url = isset( $instance['video_url'] ) ? esc_attr( $instance['video_url'] ) : '';
		$widget_desc = isset( $instance['widget_desc'] ) ? esc_attr( $instance['widget_desc'] ) : '';
		$video_width = isset( $instance['video_width'] ) ? esc_attr( $instance['video_width'] ) : '320';
		?>
         
        <p>
        <label for="<?php echo $this->get_field_id( 'widget_title' ); ?>"><?php  _e( 'Title', 'olympus-widgets' ); ?>:
        <input id="<?php echo $this->get_field_id( 'widget_title' ); ?>" name="<?php echo $this->get_field_name( 'widget_title' ); ?>" type="text" class="widefat" value="<?php echo $widget_title; ?>" /></label>
        </p>
        
        <p>
        <label for="<?php echo $this->get_field_id( 'video_url' ); ?>"><?php  _e( 'Video URL', 'olympus-widgets' ); ?>:
        <input id="<?php echo $this->get_field_id( 'video_url' ); ?>" name="<?php echo $this->get_field_name( 'video_url' ); ?>" type="text" class="widefat" value="<?php echo $video_url; ?>" /></label>
        </p>

        <p>
        <label for="<?php echo $this->get_field_id( 'video_width' ); ?>"><?php  _e( 'Video Width (px)', 'olympus-widgets' ); ?>:
        <input id="<?php echo $this->get_field_id( 'video_width' ); ?>" name="<?php echo $this->get_field_name( 'video_width' ); ?>" type="text" class="widefat" value="<?php echo $video_width; ?>" /></label>
        </p>

        <p>
		<label for="<?php echo $this->get_field_id( 'widget_desc' ); ?>"><?php _e( 'Video Description:', 'olympus-widgets' ); ?></label>
		<textarea class="widefat" id="<?php echo $this->get_field_id( 'widget_desc' ); ?>" name="<?php echo $this->get_field_name( 'widget_desc' ); ?>" rows="7" cols="20" ><?php echo $widget_desc; ?></textarea>
        </p>
                        
		<?php
	}

	/**
	 * Processes the widget's options to be saved.
	 *
	 * @param array $new_instance The new instance of values to be generated via the update.
	 * @param array $old_instance The previous instance of values before the update.
	 */
	public function update($new_instance, $old_instance) {

		$instance = $old_instance;

		$instance['widget_title'] = ( ! empty( $new_instance['widget_title'] ) ) ? strip_tags( $new_instance['widget_title'] ) : '';
		$instance['widget_desc'] = ( ! empty( $new_instance['widget_desc'] ) ) ? strip_tags( $new_instance['widget_desc'] ) : '';
		$instance['video_width'] = ( ! empty( $new_instance['video_width'] ) ) ? absint( $new_instance['video_width'] ) : '';
		$instance['video_url'] = ( ! empty( $new_instance['video_url'] ) ) ? esc_url( $new_instance['video_url'] ) : '';
		return $instance;

	}

}//end class

// Register the widget.
add_action( 'widgets_init', function(){
	 register_widget( 'Olympus_Video_Widget' );
});
