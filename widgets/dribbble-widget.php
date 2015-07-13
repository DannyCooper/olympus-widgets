<?php
/**
 * Dribbble Widget
 *
 * Build the Dribbble Widget
 *
 * @package     olympus-widgets
 * @copyright   Copyright (c) 2015, Danny Cooper
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

/**
 * Registers the Dribbble Widget.
 */
class Olympus_Dribbble_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
				parent::__construct(
					'olympus_dribbble_widget',
					__( 'Olympus Dribbble Widget', 'olympus-widgets' ),
					array( 'description' => __( 'Display your Dribbble Shots.', 'olympus-widgets' ) )
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
		$dribbble_username = isset( $instance['dribbble_username'] ) ? esc_attr( $instance['dribbble_username'] ) : '';
		$widget_desc = isset( $instance['widget_desc'] ) ? esc_attr( $instance['widget_desc'] ) : '';
		$max_shots = isset( $instance['max_shots'] ) ? absint( $instance['max_shots'] ) : '6';

		echo $args['before_widget'];

		if ( ! empty( $widget_title ) ) {
			echo $args['before_title'] . $widget_title . $args['after_title'];
		}

		if ( ! empty( $widget_desc ) ) {
			echo '<div class="widget-description">' . wpautop( $widget_desc ) . '</div>';
		}

		$this->wpDribbble( $dribbble_username, $max_shots );

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
		$dribbble_username = isset( $instance['dribbble_username'] ) ? esc_attr( $instance['dribbble_username'] ) : '';
		$widget_desc = isset( $instance['widget_desc'] ) ? esc_attr( $instance['widget_desc'] ) : '';
		$max_shots = isset( $instance['max_shots'] ) ? esc_attr( $instance['max_shots'] ) : '6';
		?>
        
        <p>
        <label for="<?php echo $this->get_field_id( 'widget_title' ); ?>"><?php  _e( 'Title', 'olympus-widgets' ); ?>:
        <input id="<?php echo $this->get_field_id( 'widget_title' ); ?>" name="<?php echo $this->get_field_name( 'widget_title' ); ?>" type="text" class="widefat" value="<?php echo $widget_title; ?>" /></label>
        </p>
        
        <p>
        <label for="<?php echo $this->get_field_id( 'widget_desc' ); ?>"><?php _e( 'Widget Text:', 'olympus-widgets' ); ?></label>
        <textarea class="widefat" id="<?php echo $this->get_field_id( 'widget_desc' ); ?>" name="<?php echo $this->get_field_name( 'widget_desc' ); ?>" rows="5" cols="20" ><?php echo $widget_desc; ?></textarea>
        </p>        

        <p>
        <label for="<?php echo $this->get_field_id( 'dribbble_username' ); ?>"><?php  _e( 'Dribbble Username', 'olympus-widgets' ); ?>:
        <input id="<?php echo $this->get_field_id( 'dribbble_username' ); ?>" name="<?php echo $this->get_field_name( 'dribbble_username' ); ?>" type="text" class="widefat" value="<?php echo $dribbble_username; ?>" /></label>
        </p>

        <p>
        <label for="<?php echo $this->get_field_id( 'max_shots' ); ?>"><?php  _e( 'Max Shots', 'olympus-widgets' ); ?>:
        <input id="<?php echo $this->get_field_id( 'max_shots' ); ?>" name="<?php echo $this->get_field_name( 'max_shots' ); ?>" type="text" class="widefat" value="<?php echo $max_shots; ?>" /></label>
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

		/* Strip tags to remove HTML (important for text inputs) ---------------------*/
		$instance['widget_title'] = strip_tags( $new_instance['widget_title'] );
		$instance['widget_desc'] = $new_instance['widget_desc'];
		$instance['dribbble_username'] = esc_attr( $new_instance['dribbble_username'] );

		return $instance;

	}

	/**
	 * Display the Dribbble feed
	 *
	 * @param string $username Dribbble username.
	 * @param int    $shots Number of shots to show.
	 */
	function wpDribbble($username, $shots) {

		include_once( ABSPATH . WPINC . '/feed.php' );

		if ( function_exists( 'fetch_feed' ) ) :
			$rss = fetch_feed( "http://dribbble.com/players/$username/shots.rss" );
			add_filter( 'wp_feed_cache_transient_lifetime', create_function( '$a', 'return 1800;' ) );
			if ( ! is_wp_error( $rss ) ) :
				$items = $rss->get_items( 0, $rss->get_item_quantity( $shots ) );
			endif;
		endif;

		if ( ! empty( $items ) ) : ?>

            <ol class="dribbbles">
                <?php
				foreach ( $items as $item ) :
					$title = $item->get_title();
					$link = $item->get_permalink();
					$date = $item->get_date( 'F d, Y' );
					$description = $item->get_description();

					preg_match( '/src="(http.*(jpg|jpeg|gif|png))/', $description, $image_url );
					$image = $image_url[1];
					$image = preg_replace( '/(\.jpg|\.jpeg|\.gif|\.png)/', '_teaser$1',$image );
				?>
                <li class="group">
                    <div class="dribbble-img">
                        <a href="<?php echo $link; ?>" class="dribbble-link"><img src="<?php echo $image; ?>" alt="<?php echo $title;?>"/></a> 
                    </div>
                </li>
                <?php endforeach;?>
                <div class="clear"></div>
            </ol>

    <?php endif;

	}

}//end class

// Register the widget.
add_action( 'widgets_init', function(){
	 register_widget( 'Olympus_Dribbble_Widget' );
});
