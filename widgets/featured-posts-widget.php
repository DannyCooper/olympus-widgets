<?php
/**
 * Featured Posts Widget
 *
 * Build the Featured Posts Widget
 *
 * @package     olympus-widgets
 * @copyright   Copyright (c) 2015, Danny Cooper
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

/**
 * Registers the Featured Posts Widget.
 */
class Olympus_Featured_Posts_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
				parent::__construct(
					'olympus_featured_posts_widget',
					__( 'Olympus Featured Posts Widget', 'olympus-widgets' ),
					array( 'description' => __( 'Display your most important posts.', 'olympus-widgets' ) )
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
		$post_count = $instance['post_count'];

		echo $args['before_widget'];

		if ( $widget_title ) {
			echo $args['before_title'] . $widget_title . $args['after_title'];
		}

		if ( ! empty( $widget_desc ) ) {
			echo '<div class="widget-description">'.wpautop( $widget_desc ).'</div>';
		}

		$this->featured_post_loop( $post_count );

		echo $args['after_widget'];
	}

	/**
	 * Generates the administration form for the widget.
	 *
	 * @param array $instance The array of keys and values for the widget.
	 */
	public function form( $instance ) {

		// Get the options into variables, escaping html characters on the way.
		$widget_title = isset( $instance['widget_title'] ) ? esc_attr( $instance['widget_title'] ) : 'Featured Posts';
		$widget_desc = isset( $instance['widget_desc'] ) ? esc_attr( $instance['widget_desc'] ) : '';
		$post_count = isset( $instance['post_count'] ) ? absint( $instance['post_count'] ) : '5';

		?>
        <p><?php esc_html_e( 'Tag your posts as \'featured\' to make them display in this widget.', 'olympus-widgets' ); ?></p>

        <p>
        <label for="<?php echo $this->get_field_id( 'widget_title' ); ?>"><?php _e( 'Title:', 'olympus-widgets' ) ?></label>
        <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'widget_title' ); ?>" name="<?php echo $this->get_field_name( 'widget_title' ); ?>" value="<?php echo $widget_title; ?>" />
        </p>

        <p>
        <label for="<?php echo $this->get_field_id( 'widget_desc' ); ?>"><?php _e( 'Widget Text:', 'olympus-widgets' ); ?></label>
        <textarea class="widefat" id="<?php echo $this->get_field_id( 'widget_desc' ); ?>" name="<?php echo $this->get_field_name( 'widget_desc' ); ?>" rows="5" cols="20" ><?php echo $widget_desc; ?></textarea>
        </p>

        <p>
        <label for="<?php echo $this->get_field_id( 'post_count' ); ?>"><?php _e( 'Show how many posts?:', 'olympus-widgets' ) ?></label>
        <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'post_count' ); ?>" name="<?php echo $this->get_field_name( 'post_count' ); ?>" value="<?php echo $post_count; ?>" />
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
		$instance['post_count'] = absint( $new_instance['post_count'] );
		$instance['widget_desc'] = $new_instance['widget_desc'];

		return $instance;
	}

	/**
	 * Loop through and display the featured posts
	 *
	 * @param int $post_count number of posts to display.
	 */
	public function featured_post_loop( $post_count = 5 ) {

		$query = new WP_Query( 'tag=featured&posts_per_page=' . $post_count );

		if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>

            <div class="olympus-featured-post">

                <?php if ( has_post_thumbnail() ) {
					the_post_thumbnail( 'large' );
} ?>

                <h3 class="olympus-post-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>

                <small class="olympus-post-date"><?php the_time( 'F jS, Y' ); ?></small>
                
            </div> 

			<?php endwhile; else : ?>

            <p><?php esc_html_e( 'Tag your posts as \'featured\' to make them display in this widget.', 'olympus-widgets' ); ?></p>

			<?php endif;

			wp_reset_postdata();

	}

}//end class

// Register the widget.
add_action( 'widgets_init', function(){
	 register_widget( 'Olympus_Featured_Posts_Widget' );
});
