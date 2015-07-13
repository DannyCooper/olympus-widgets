<?php
/**
 * Facebook Widget
 *
 * Build the Facebook Widget
 *
 * @package     olympus-widgets
 * @copyright   Copyright (c) 2015, Danny Cooper
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

/**
 * Registers the Facebook Widget.
 */
class Olympus_Facebook_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
				parent::__construct(
					'olympus_facebook_widget',
					__( 'Olympus Facebook Widget', 'olympus-widgets' ),
					array( 'description' => __( 'A Simple Facebook Widget', 'olympus-widgets' ) )
				);

				add_action( 'wp_footer', array( $this, 'load_scripts' ) );
	}

	/**
	 * Load Facebook SDK.
	 */
	public function load_scripts() {

		?>
            <div id="fb-root"></div>
            <script>(function(d, s, id) {
              var js, fjs = d.getElementsByTagName(s)[0];
              if (d.getElementById(id)) return;
              js = d.createElement(s); js.id = id;
              js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.3";
              fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>
        <?php

	}

	/**
	 * Outputs the content of the widget.
	 *
	 * @param array $args     The array of form elements.
	 * @param array $instance The current instance of the widget.
	 */
	public function widget( $args, $instance ) {

		$widget_title = isset( $instance['widget_title'] ) ? apply_filters( 'widget_title', $instance['widget_title'] ) : '';
		$facebook_id = isset( $instance['facebook_id'] ) ? $instance['facebook_id'] : '';
		$widget_desc = isset( $instance['widget_desc'] ) ? $instance['widget_desc'] : '';

		echo $args['before_widget'];

		if ( $widget_title ) {
			echo $args['before_title'] . $widget_title . $args['after_title'];
		}

		if ( $widget_desc ) { echo '<div class="widget-description">' . wpautop( $widget_desc ) . '</div>'; }

		if ( ! empty( $facebook_id ) ) {

			 echo	'<div class="fb-page" data-href="https://www.facebook.com/facebook" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="false"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/facebook"><a href="https://www.facebook.com/ ' . $facebook_id . ' ">Facebook</a></blockquote></div></div>';

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
		$widget_title = isset( $instance['widget_title'] ) ? esc_attr( $instance['widget_title'] ) : 'Our Facebook Page';
		$facebook_id = isset( $instance['facebook_id'] ) ? esc_attr( $instance['facebook_id'] ) : 'WordPress';
		$widget_desc = isset( $instance['widget_desc'] ) ? esc_attr( $instance['widget_desc'] ) : '';

		?>

        <p>
        <label for="<?php echo $this->get_field_id( 'widget_title' ); ?>"><?php _e( 'Title:', 'olympus-widgets' ) ?></label>
        <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'widget_title' ); ?>" name="<?php echo $this->get_field_name( 'widget_title' ); ?>" value="<?php echo $widget_title; ?>" />
        </p>

        <p>
        <label for="<?php echo $this->get_field_id( 'widget_desc' ); ?>"><?php _e( 'Widget Text:', 'olympus-widgets' ); ?></label> 
        <textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id( 'widget_desc' ); ?>" name="<?php echo $this->get_field_name( 'widget_desc' ); ?>" type="text"><?php echo $widget_desc; ?></textarea>
        </p>

        <p>
        <label for="<?php echo $this->get_field_id( 'facebook_id' ); ?>"><?php _e( 'Facebook Page ID:', 'olympus-widgets' ) ?></label>
        <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'facebook_id' ); ?>" name="<?php echo $this->get_field_name( 'facebook_id' ); ?>" value="<?php echo $facebook_id; ?>" />
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

		$instance['facebook_id'] = esc_attr( $new_instance['facebook_id'] );
		$instance['widget_desc'] = $new_instance['widget_desc'];

		return $instance;
	}

}//end class

// Register the widget.
add_action( 'widgets_init', function(){
	 register_widget( 'Olympus_Facebook_Widget' );
});
