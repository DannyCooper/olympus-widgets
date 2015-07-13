<?php
/**
 * Plugin Name: Olympus Widgets
 * Plugin URI: http://olympusthemes.com/widgets
 * Description: Adds eight new widgets you can use in your sidebar.
 * Author: Olympus Themes
 * Author URI: http://olympusthemes.com
 * Version: 1.0.1
 * Text Domain: olympus-widgets
 * License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * @package olympus-widgets
 */

/**
 * Main Olympus_Widgets Class
 *
 * @since 1.0.0
 */
class Olympus_Widgets {

	/**
	 * Load plugin files
	 */
	function __construct() {

		// Plugin Folder Path.
		if ( ! defined( 'OLYMPUS_WIDGETS_PLUGIN_DIR' ) ) {
			define( 'OLYMPUS_WIDGETS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		}

		// Plugin Folder URL.
		if ( ! defined( 'OLYMPUS_WIDGETS_PLUGIN_URL' ) ) {
			define( 'OLYMPUS_WIDGETS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}

		add_action( 'init', array( $this, 'init' ) );
		
		$this->widget_include( 'olympus_author_widget_disable' , 'author-widget' );
		$this->widget_include( 'olympus_social_widget_disable', 'social-widget' );
		$this->widget_include( 'olympus_facebook_widget_disable', 'facebook-widget' );
		$this->widget_include( 'olympus_dribbble_widget_disable', 'dribbble-widget' );
		$this->widget_include( 'olympus_flickr_widget_disable', 'flickr-widget' );
		$this->widget_include( 'olympus_video_widget_disable', 'video-widget' );
		$this->widget_include( 'olympus_featured_posts_widget_disable', 'featured-posts-widget' );
		$this->widget_include( 'olympus_twitter_posts_widget_disable', 'twitter-widget' );

		require_once( OLYMPUS_WIDGETS_PLUGIN_DIR . '/inc/customizer/customizer-library.php' );
		require_once( OLYMPUS_WIDGETS_PLUGIN_DIR . '/inc/customizer/customizer-settings.php' );

	}
	
	/**
	 * Load plugin CSS & Languages.
	 */
	public function init() {

		if( ! is_admin() ) {
			wp_enqueue_style( 'olympus_widgets_styles', OLYMPUS_WIDGETS_PLUGIN_URL . 'css/style.css' );
		}
		
		load_plugin_textdomain( 'olympus-widgets', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );

	}	
	
	/**
	 * Check if widget is disabled in plugin settings. If not disabled, load the widget class
	 *
	 * @param string $option setting option to check.
	 * @param string $file filename of widget.
	 */
	public function widget_include( $option, $file ) {

		if ( get_option( $option ) !== '1' ) {
			require_once( OLYMPUS_WIDGETS_PLUGIN_DIR . '/widgets/' . $file . '.php' );
		}

	}

}

$olympus_widgets = new Olympus_Widgets();