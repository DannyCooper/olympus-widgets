<?php
/**
 * Author Widget
 *
 * Build the Author Widget
 *
 * @package     olympus-widgets
 * @copyright   Copyright (c) 2015, Danny Cooper
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

/**
 * Registers the Author Widget
 */
class Olympus_Author_Widget extends WP_Widget {


	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
				parent::__construct(
					'olympus_profile_widget',
					__( 'Olympus Author Widget', 'olympus-widgets' ),
					array( 'description' => __( 'A Simple Author Widget', 'olympus-widgets' ) )
				);

				if ( get_option( 'author_widget_disable' ) === 1 ) {
					return;
				}

				add_action( 'admin_enqueue_scripts', array( $this, 'upload_scripts' ) );

	}

	/**
	 * Upload the Javascripts for the media uploader
	 */
	public function upload_scripts() {

		wp_enqueue_script( 'media-upload' );
		wp_enqueue_script( 'thickbox' );
		wp_enqueue_script( 'upload_media_widget', OLYMPUS_WIDGETS_PLUGIN_URL . 'js/upload-media.js', array( 'jquery' ) );

		wp_enqueue_style( 'thickbox' );
	}

	/**
	 * Outputs the content of the widget.
	 *
	 * @param array $args     The array of form elements.
	 * @param array $instance The current instance of the widget.
	 */
	public function widget( $args, $instance ) {

		$widget_title = isset( $instance['widget_title'] ) ? apply_filters( 'widget_title', $instance['widget_title'] ) : '';
		$widget_desc = $instance['widget_desc'];
		$author_image = $instance['author_image'];

		echo $args['before_widget'];

		if ( $widget_title ) {
			echo $args['before_title'] . $widget_title . $args['after_title'];
		}

		if ( ! empty( $author_image ) ) {
			echo '<img class="olympus-author-image" src="' . esc_url( $author_image ) . '"/>';
		}

		if ( ! empty( $widget_desc ) ) {
			echo '<div class="widget-description">' . wpautop( wp_kses_post( $widget_desc ) ) . '</div>';
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
		$widget_title = isset( $instance['widget_title'] ) ? sanitize_text_field( $instance['widget_title'] ) : 'About Me';
		$widget_desc = isset( $instance['widget_desc'] ) ? wp_kses_post( $instance['widget_desc'] ) : '';
		$author_image = isset( $instance['author_image'] ) ? esc_url( $instance['author_image'] ) : '';

		?>

        <p>
        <label for="<?php echo $this->get_field_id( 'widget_title' ); ?>"><?php _e( 'Title:', 'olympus-widgets' ) ?></label>
        <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'widget_title' ); ?>" name="<?php echo $this->get_field_name( 'widget_title' ); ?>" value="<?php echo $widget_title; ?>" />
        </p>

        <p>
        <label for="<?php echo $this->get_field_id( 'widget_desc' ); ?>"><?php _e( 'Widget Text:', 'olympus-widgets' ); ?></label>
        <textarea class="widefat" id="<?php echo $this->get_field_id( 'widget_desc' ); ?>" name="<?php echo $this->get_field_name( 'widget_desc' ); ?>" rows="5" cols="20" ><?php echo $widget_desc; ?></textarea>
        </p>

        <p>
            <label for="<?php echo $this->get_field_name( 'author_image' ); ?>"><?php _e( 'Your Image:', 'olympus-widgets' ); ?></label>
            <input name="<?php echo $this->get_field_name( 'author_image' ); ?>" id="<?php echo $this->get_field_id( 'author_image' ); ?>" class="widefat" type="text" size="36"  value="<?php echo esc_url( $author_image ); ?>" />
            <input class="upload_image_button" type="button" value="Upload Image" />
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

		$instance['widget_title'] = sanitize_text_field( $new_instance['widget_title'] );
		$instance['author_image'] = esc_url_raw( $new_instance['author_image'] );
		$instance['widget_desc'] = $new_instance['widget_desc'];

		return $instance;
	}

}//end class

// Register the widget.
add_action( 'widgets_init', function(){
	 register_widget( 'Olympus_Author_Widget' );
});
