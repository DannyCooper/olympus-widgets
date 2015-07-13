<?php
/**
 * Social Profiles Widget
 *
 * Build the Social Profiles Widget
 *
 * @package     olympus-widgets
 * @copyright   Copyright (c) 2015, Danny Cooper
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

/**
 * Registers the Social Profiles Widget.
 */
class Olympus_Social_Widget extends WP_Widget {

	/**
	 * List of social profiles.
	 *
	 * @var array $social_profiles
	 */
	public $social_profiles = array(
		'twitter',
		'facebook',
		'google',
		'pinterest',
		'linkedin',
		'youtube',
		'instagram',
		'rss',
		'email',
	);

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {

		parent::__construct(
			'olympus_social_widget',
			__( 'Olympus Social Profiles', 'olympus-widgets' ),
			array( 'description' => __( 'This widget displays the social profiles you entered in the Settings Panel.', 'olympus-widgets' ) )
		);

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

		echo $args['before_widget'];

		if ( ! empty( $widget_title ) ) {
			echo $args['before_title'] . $widget_title . $args['after_title'];
		}

		echo '<div class="widget-description">'.wpautop( $widget_desc ).'</div>';

		$this->generate_social_links();

		echo $args['after_widget'];

	}

	/**
	 * Generates the administration form for the widget.
	 *
	 * @param array $instance The array of keys and values for the widget.
	 */
	public function form( $instance ) {

		if ( isset( $instance['title'] ) ) {
			$widget_title = $instance['title'];
		} else {
			$widget_title = __( 'Social Media', 'olympus-widgets' );
		}
		$widget_desc = isset( $instance['widget_desc'] ) ? esc_attr( $instance['widget_desc'] ) : '';

		?>
      <p><?php _e( 'This widget displays the social profiles you entered in the <a href=" ' . admin_url( 'customize.php?autofocus[control]=olympus_twitter_url' ) . ' ">Social Settings Panel</a>.', 'olympus-widgets' ); ?></p>

      <p>
      <label for="<?php echo $this->get_field_id( 'widget_title' ); ?>"><?php _e( 'Title:', 'olympus-widgets' ); ?></label> 
      <input class="widefat" id="<?php echo $this->get_field_id( 'widget_title' ); ?>" name="<?php echo $this->get_field_name( 'widget_title' ); ?>" type="text" value="<?php echo esc_attr( $widget_title ); ?>" />
      </p>

      <p>
      <label for="<?php echo $this->get_field_id( 'widget_desc' ); ?>"><?php _e( 'Widget Text:', 'olympus-widgets' ); ?></label> 
      <textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id( 'widget_desc' ); ?>" name="<?php echo $this->get_field_name( 'widget_desc' ); ?>" type="text"><?php echo $widget_desc; ?></textarea>
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
		$instance['widget_title'] = ( ! empty( $new_instance['widget_title'] ) ) ? strip_tags( $new_instance['widget_title'] ) : '';
		$instance['widget_desc'] = ( ! empty( $new_instance['widget_desc'] ) ) ? strip_tags( $new_instance['widget_desc'] ) : '';
		return $instance;

	}

	/**
	 * Display the social media profiles
	 */
	public function generate_social_links() {

		echo '<ul class="social-widget">';

		foreach ( $this->social_profiles as $profile ) {

			$option = 'olympus_' . $profile . '_url';
			$option_value = get_option( $option );

			if ( $option_value && $option !== 'olympus_email_url' ) {
				echo '<li>
                      <a class="' . esc_attr( $option ) . '" href="' . esc_url( $option_value ) . '">
                           
                      </a>
                  </li>';
			} elseif ( $option === 'olympus_email_url' ) {
				echo '<li>
				  <a class="' . esc_attr( $option ) . '" href="mailto:' . sanitize_email( $option_value ) . '">

				  </a>
				</li>';	
			}
		}

		echo '</ul>';

	}

}//end class

// Register the widget.
add_action( 'widgets_init', function(){
	 register_widget( 'Olympus_Social_Widget' );
});
