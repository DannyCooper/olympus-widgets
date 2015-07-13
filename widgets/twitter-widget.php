<?php
/**
 * Twitter Widget
 *
 * Build the Twitter Widget
 *
 * @package     olympus-widgets
 * @copyright   Copyright (c) 2015, Pippin Williamson
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

/**
 * Registers the Twitter Widget
 */
class Olympus_Twitter_Widget extends WP_Widget {


	/**
	 * Twitter Consumer Key
	 *
	 * @var string
	 */
	public $consumer_key;

	/**
	 * Twitter Consumer Secret Key
	 *
	 * @var string
	 */
	public $consumer_secret;

	/**
	 * Twitter Access Token
	 *
	 * @var string
	 */
	public $access_token;

	/**
	 * Twitter Access Token Secret
	 *
	 * @var string
	 */
	public $access_token_secret;

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'olympus_twitter_widget',
			'Olympus Recent Tweets',
			array( 'description' => __( 'Display your recent tweets', 'olympus-widgets' ) )
		);

		$this->consumer_key = get_option( 'olympus_twitter_consumer_key' );
		$this->consumer_secret = get_option( 'olympus_twitter_consumer_secret' );
		$this->access_token = get_option( 'olympus_twitter_access_token' );
		$this->access_token_secret = get_option( 'olympus_twitter_access_token_secret' );
	}

	/**
	 * Outputs the content of the widget.
	 *
	 * @param array $args     The array of form elements.
	 * @param array $instance The current instance of the widget.
	 */
	public function widget($args, $instance) {

		$widget_title = isset( $instance['widget_title'] ) ? apply_filters( 'widget_title', $instance['widget_title'] ) : '';

		echo $args['before_widget'];

		if ( ! empty( $widget_title ) ) {
			echo $args['before_title'] . $widget_title . $args['after_title'];
		}

		// Check API settings and die if not set.
		if ( empty( $this->consumer_key ) ||
			empty( $this->consumer_secret ) ||
			empty( $this->access_token ) ||
			empty( $this->access_token_secret ) ) {

				echo '<strong>'.__( 'Please fill in the <a href=" ' . admin_url( 'customize.php?autofocus[control]=twitter_consumer_key' ) . ' ">API Key settings!</a>','olympus-widgets' ).'</strong>' . $args['after_widget'];
				return;

		}

		if ( empty( $instance['cachetime'] ) || empty( $instance['username'] ) ) {
			echo '<strong>'.__( 'Please fill in all the widget settings!','olympus-widgets' ).'</strong>' . $args['after_widget'];
			return;
		}

				// Check if cache needs update.
				$olympus_twitter_plugin_last_cache_time = get_option( 'olympus_twitter_plugin_last_cache_time' );
				$diff = time() - $olympus_twitter_plugin_last_cache_time;
				$crt = $instance['cachetime'] * 3600;

				// Yes, it needs update.
		if ( $diff >= $crt || empty( $olympus_twitter_plugin_last_cache_time ) ) {

			if ( ! require_once( OLYMPUS_WIDGETS_PLUGIN_DIR . '/inc/api/twitteroauth/twitteroauth.php' ) ) {
				echo '<strong>' . __( 'Couldn\'t find twitteroauth.php!' , 'olympus-widgets' ) . '</strong>' . $args['after_widget'];
				return;
			}

			/**
			 * Connect to Twitter API
			 *
			 * @param string $cons_key
			 * @param string $cons_secret
			 * @param string $oauth_token
			 * @param string $oauth_token_secret
			 */
			function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
				$connection = new TwitterOAuth( $cons_key, $cons_secret, $oauth_token, $oauth_token_secret );
				return $connection;
			}

			$connection = getConnectionWithAccessToken( $this->consumer_key, $this->consumer_secret, $this->access_token, $this->access_token_secret );
			$tweets = $connection->get( 'https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name='.$instance['username'].'&count=50&exclude_replies='.$instance['excludereplies'] ) or die( 'Couldn\'t retrieve tweets! Wrong username?' );

			if ( ! empty( $tweets->errors ) ) {
				if ( $tweets->errors[0]->message == 'Invalid or expired token' ) {
					echo '<strong>'.$tweets->errors[0]->message.'!</strong><br />' . __( 'You\'ll need to regenerate it <a href="https://dev.twitter.com/apps" target="_blank">here</a>!','olympus-widgets' ) . $args['after_widget'];
				} else {
					echo '<strong>'.$tweets->errors[0]->message.'</strong>' . $args['after_widget'];
				}
				return;
			}

			$tweets_array = array();
			for ( $i = 0;$i <= count( $tweets ); $i++ ) {
				if ( ! empty( $tweets[ $i ] ) ) {
					$tweets_array[ $i ]['created_at'] = $tweets[ $i ]->created_at;

						// Clean tweet text.
						$tweets_array[ $i ]['text'] = preg_replace( '/[\x{10000}-\x{10FFFF}]/u', '', $tweets[ $i ]->text );

					if ( ! empty( $tweets[ $i ]->id_str ) ) {
						$tweets_array[ $i ]['status_id'] = $tweets[ $i ]->id_str;
					}
				}
			}

			// Save tweets to wp option.
				update_option( 'olympus_twitter_plugin_tweets',serialize( $tweets_array ) );
				update_option( 'olympus_twitter_plugin_last_cache_time',time() );

			echo '<!-- twitter cache has been updated! -->';
		}

			$olympus_twitter_plugin_tweets = maybe_unserialize( get_option( 'olympus_twitter_plugin_tweets' ) );
		if ( ! empty( $olympus_twitter_plugin_tweets ) && is_array( $olympus_twitter_plugin_tweets ) ) {
			print '
                <div class="olympus-recent-tweets">
                    <ul>';
				$fctr = '1';
			foreach ( $olympus_twitter_plugin_tweets as $tweet ) {
				if ( ! empty( $tweet['text'] ) ) {
					if ( empty( $tweet['status_id'] ) ) { $tweet['status_id'] = ''; }
					if ( empty( $tweet['created_at'] ) ) { $tweet['created_at'] = ''; }

					print '<li><span>' . $this->olympus_convert_links( $tweet['text'] ) . '</span><br /><a class="twitter-time" target="_blank" href="http://twitter.com/'.$instance['username'].'/statuses/'.$tweet['status_id'].'">'. $this->olympus_relative_time( $tweet['created_at'] ) . '</a></li>';
					if ( $fctr == $instance['tweetstoshow'] ) { break; }
					$fctr++;
				}
			}

				print '
                    <div class="clear"></div></ul>
                    <a href="https://twitter.com/'.$instance['username'].'" class="twitter-follow-button" data-show-count="false">Follow @'.$instance['username'].'</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?\'http\':\'https\';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+\'://platform.twitter.com/widgets.js\';fjs.parentNode.insertBefore(js,fjs);}}(document, \'script\', \'twitter-wjs\');</script>
                </div>';
		} else {
			print '
                <div class="olympus-recent-tweets">
                    ' . __( '<b>Error!</b> Couldn\'t retrieve tweets for some reason!','olympus-widgets' ) . '
                </div>';
		}

		echo $args['after_widget'];
	}

	/**
	 * Generates the administration form for the widget.
	 *
	 * @param array $instance The array of keys and values for the widget.
	 */
	public function form($instance) {
		$defaults = array( 'title' => '', 'cachetime' => '24', 'username' => '', 'tweetstoshow' => '5' );
		$instance = wp_parse_args( (array) $instance, $defaults );

		// Get the options into variables, escaping html characters on the way.
		$widget_title = isset( $instance['widget_title'] ) ? esc_attr( $instance['widget_title'] ) : 'Twitter Feed';
		$cachetime = isset( $instance['cachetime'] ) ? absint( $instance['cachetime'] ) : '24';
		$username = isset( $instance['username'] ) ? esc_attr( $instance['username'] ) : '';
		$tweetstoshow = isset( $instance['tweetstoshow'] ) ? absint( $instance['tweetstoshow'] ) : '5';
		$excludereplies = isset( $instance['excludereplies'] ) ? esc_attr( $instance['excludereplies'] ) : '';

		// Check settings and die if not set.
		if ( empty( $this->consumer_key ) ||
			empty( $this->consumer_secret ) ||
			empty( $this->access_token ) ||
			empty( $this->access_token_secret ) ) {

				echo '<p><strong>'.__( 'Please fill in the <a href=" ' . admin_url( 'customize.php?autofocus[control]=twitter_consumer_key' ) . ' ">API Key settings!</a>','olympus-widgets' ).'</strong></p>';

		}

		echo '
        <p><label>' . __( 'Title:','olympus' ) . '</label>
            <input type="text" name="'.$this->get_field_name( 'widget_title' ).'" id="'.$this->get_field_id( 'widget_title' ).'" value="'.$widget_title.'" class="widefat" /></p>
            <p><label>' . __( 'Twitter Username:','olympus-widgets' ) . '</label>
            <input type="text" name="'.$this->get_field_name( 'username' ).'" id="'.$this->get_field_id( 'username' ).'" value="'.$username.'" class="widefat" /></p>   
        <p><label>' . __( 'Cache Tweets for:','olympus-widgets' ) . '</label>
            <input type="text" name="'.$this->get_field_name( 'cachetime' ).'" id="'.$this->get_field_id( 'cachetime' ).'" value="'.$cachetime.'" class="small-text" /> hours</p>                                                                                                               
        <p><label>' . __( 'Tweets to display:','olympus-widgets' ) . '</label>
            <select type="text" name="'.$this->get_field_name( 'tweetstoshow' ).'" id="'.$this->get_field_id( 'tweetstoshow' ).'">';
			$i = 1;
		for ( i; $i <= 10; $i++ ) {
			echo '<option value="'.$i.'"';
			if ( $tweetstoshow === $i ) { echo ' selected="selected"';
			} echo '>'.$i.'</option>';
		}
			echo '
            </select></p>
        <p><label>' . __( 'Exclude replies:','olympus-widgets' ) . '</label>
            <input type="checkbox" name="'.$this->get_field_name( 'excludereplies' ).'" id="'.$this->get_field_id( 'excludereplies' ).'" value="true"';
		if ( ! empty( $excludereplies ) && 'true' === $excludereplies ) {
			print ' checked="checked"';
		}
			print ' /></p>';

	}

	/**
	 * Processes the widget's options to be saved.
	 *
	 * @param array $new_instance The new instance of values to be generated via the update.
	 * @param array $old_instance The previous instance of values before the update.
	 */
	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['widget_title'] = strip_tags( $new_instance['widget_title'] );
		$instance['cachetime'] = strip_tags( $new_instance['cachetime'] );
		$instance['username'] = strip_tags( $new_instance['username'] );
		$instance['tweetstoshow'] = strip_tags( $new_instance['tweetstoshow'] );
		$instance['excludereplies'] = strip_tags( $new_instance['excludereplies'] );

		if ( $old_instance['username'] !== $new_instance['username'] ) {
			delete_option( 'olympus_twitter_plugin_last_cache_time' );
		}

		return $instance;
	}

	/**
	 * Processes the widget's options to be saved.
	 *
	 * @param array $a The new instance of values to be generated via the update.
	 */
	public function olympus_relative_time( $a ) {
		// Get current timestamp.
		$b = strtotime( 'now' );
		// Get timestamp when tweet created.
		$c = strtotime( $a );
		// Get difference.
		$d = $b - $c;
		// Calculate different time values.
		$minute = 60;
		$hour = $minute * 60;
		$day = $hour * 24;
		$week = $day * 7;

		if ( is_numeric( $d ) && $d > 0 ) {
			// If less then 3 seconds.
			if ( $d < 3 ) { return __( 'right now','olympus-widgets' ); }
			// If less then minute.
			if ( $d < $minute ) { return floor( $d ) . __( ' seconds ago','olympus-widgets' ); }
			// If less then 2 minutes.
			if ( $d < $minute * 2 ) { return __( 'about 1 minute ago','olympus-widgets' ); }
			// If less then hour.
			if ( $d < $hour ) { return floor( $d / $minute ) . __( ' minutes ago','olympus-widgets' ); }
			// If less then 2 hours.
			if ( $d < $hour * 2 ) { return __( 'about 1 hour ago','olympus-widgets' ); }
			// If less then day.
			if ( $d < $day ) { return floor( $d / $hour ) . __( ' hours ago','olympus-widgets' ); }
			// If more then day, but less then 2 days.
			if ( $d > $day && $d < $day * 2 ) { return __( 'yesterday','olympus-widgets' ); }
			// If less then year.
			if ( $d < $day * 365 ) { return floor( $d / $day ) . __( ' days ago','olympus-widgets' ); }
			// Else return more than a year.
			return __( 'over a year ago','olympus' );
		}
	}

	/**
	 * Processes the widget's options to be saved.
	 *
	 * @param array $status The new instance of values to be generated via the update.
	 * @param array $targetBlank The previous instance of values before the update.
	 * @param array $linkMaxLen The previous instance of values before the update.
	 */
	public function olympus_convert_links( $status, $targetBlank = true, $linkMaxLen = 250 ) {

		// The target.
		$target = $targetBlank ? ' target="_blank" ' : '';

		// Convert link to url.
		$status = preg_replace( '/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[A-Z0-9+&@#\/%=~_|]/i', '<a href="\0" target="_blank">\0</a>', $status );

		// Convert @ to follow.
		$status = preg_replace( '/(@([_a-z0-9\-]+))/i',"<a href=\"http://twitter.com/$2\" title=\"Follow $2\" $target >$1</a>",$status );

		// Convert # to search.
		$status = preg_replace( '/(#([_a-z0-9\-]+))/i',"<a href=\"https://twitter.com/search?q=$2\" title=\"Search $1\" $target >$1</a>",$status );

		// Return the status.
		return $status;
	}

}//end class

// Register the widget.
add_action( 'widgets_init', function(){
	 register_widget( 'Olympus_Twitter_Widget' );
});
