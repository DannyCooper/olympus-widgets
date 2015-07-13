<?php
/**
 * Defines customizer options
 *
 * @package Customizer Library Demo
 */

/**
 * Create the demo options
 */
function customizer_library_demo_options() {

	// Theme defaults.
	$primary_color = '#5bc08c';
	$secondary_color = '#666';

	// Stores all the controls that will be added.
	$options = array();

	// Stores all the sections to be added.
	$sections = array();

	// Stores all the panels to be added.
	$panels = array();

	// Adds the sections to the $options array.
	$options['sections'] = $sections;

	// Panel Example.
	$panel = 'olympus-widget-settings';

	$panels[] = array(
		'id' => $panel,
		'title' => __( 'Olympus Widget Settings' ),
		'priority' => '100',
	);

	// Logo.
	$section = 'olympus-social-profiles';

	$sections[] = array(
		'id' => $section,
		'title' => __( 'Social Profiles', 'olympus-widgets' ),
		'priority' => '30',
		'description' => __( 'Enter your social profile URLs below.', 'olympus-widgets' ),
		'panel' => 'olympus-widget-settings',
	);

	$options['olympus_twitter_url'] = array(
		'id' => 'olympus_twitter_url',
		'option_type' => 'option',
		'label'   => __( 'Twitter URL', 'olympus-widgets' ),
		'section' => $section,
		'type'    => 'url',
		'transport' => 'postMessage',
	);

	$options['olympus_facebook_url'] = array(
		'id' => 'olympus_facebook_url',
		'option_type' => 'option',
		'label'   => __( 'Facebook URL', 'olympus-widgets' ),
		'section' => $section,
		'type'    => 'url',
		'transport' => 'postMessage',
	);

	$options['olympus_google_url'] = array(
		'id' => 'olympus_google_url',
		'option_type' => 'option',
		'label'   => __( 'Google+ URL', 'olympus-widgets' ),
		'section' => $section,
		'type'    => 'url',
		'transport' => 'postMessage',
	);

	$options['olympus_pinterest_url'] = array(
		'id' => 'olympus_pinterest_url',
		'option_type' => 'option',
		'label'   => __( 'Pinterest URL', 'olympus-widgets' ),
		'section' => $section,
		'type'    => 'url',
		'transport' => 'postMessage',
	);

	$options['olympus_linkedin_url'] = array(
		'id' => 'olympus_linkedin_url',
		'option_type' => 'option',
		'label'   => __( 'LinkedIn URL', 'olympus-widgets' ),
		'section' => $section,
		'type'    => 'url',
		'transport' => 'postMessage',
	);

	$options['olympus_rss_url'] = array(
		'id' => 'olympus_rss_url',
		'option_type' => 'option',
		'label'   => __( 'RSS URL', 'olympus-widgets' ),
		'section' => $section,
		'type'    => 'url',
		'transport' => 'postMessage',
	);

	$options['olympus_youtube_url'] = array(
		'id' => 'olympus_youtube_url',
		'option_type' => 'option',
		'label'   => __( 'YouTube URL', 'olympus-widgets' ),
		'section' => $section,
		'type'    => 'url',
		'transport' => 'postMessage',
	);

	$options['olympus_instagram_url'] = array(
		'id' => 'olympus_instagram_url',
		'option_type' => 'option',
		'label'   => __( 'Instagram URL', 'olympus-widgets' ),
		'section' => $section,
		'type'    => 'url',
		'transport' => 'postMessage',
	);

	$options['olympus_email_url'] = array(
		'id' => 'olympus_email_url',
		'option_type' => 'option',
		'label'   => __( 'Email Address', 'olympus-widgets' ),
		'section' => $section,
		'type'    => 'text',
		'transport' => 'postMessage',
	);

	// File Upload.
	$section = 'olympus-widgets-disable';

	$sections[] = array(
		'id' => $section,
		'title' => __( 'Disable Olympus Widgets', 'olympus-widgets' ),
		'priority' => '30',
		'description' => __( 'This option allows you to remove widgets you aren\'t using, decluttering your \'Available Widgets\' section', 'olympus-widgets' ),
		'panel' => 'olympus-widget-settings',
	);

	$options['olympus_author_widget_disable'] = array(
		'id' => 'olympus_author_widget_disable',
		'option_type' => 'option',
		'label'   => __( 'Disable Author Widget?', 'olympus-widgets' ),
		'section' => $section,
		'type'    => 'checkbox',
		'default' => 0,
		'transport' => 'postMessage',
	);

	$options['olympus_dribbble_widget_disable'] = array(
		'id' => 'olympus_dribbble_widget_disable',
		'option_type' => 'option',
		'label'   => __( 'Disable Dribbble Widget?', 'olympus-widgets' ),
		'section' => $section,
		'type'    => 'checkbox',
		'default' => 0,
		'transport' => 'postMessage',
	);

	$options['olympus_facebook_widget_disable'] = array(
		'id' => 'olympus_facebook_widget_disable',
		'option_type' => 'option',
		'label'   => __( 'Disable Facebook Widget?', 'olympus-widgets' ),
		'section' => $section,
		'type'    => 'checkbox',
		'default' => 0,
		'transport' => 'postMessage',
	);

	$options['olympus_featured_posts_widget_disable'] = array(
		'id' => 'olympus_featured_posts_widget_disable',
		'option_type' => 'option',
		'label'   => __( 'Disable Featured Posts Widget?', 'olympus-widgets' ),
		'section' => $section,
		'type'    => 'checkbox',
		'default' => 0,
		'transport' => 'postMessage',
	);

	$options['olympus_flickr_widget_disable'] = array(
		'id' => 'olympus_flickr_widget_disable',
		'option_type' => 'option',
		'label'   => __( 'Disable Flickr Widget?', 'olympus-widgets' ),
		'section' => $section,
		'type'    => 'checkbox',
		'default' => 0,
		'transport' => 'postMessage',
	);

	$options['olympus_social_widget_disable'] = array(
		'id' => 'olympus_social_widget_disable',
		'option_type' => 'option',
		'label'   => __( 'Disable Social Widget?', 'olympus-widgets' ),
		'section' => $section,
		'type'    => 'checkbox',
		'default' => 0,
		'transport' => 'postMessage',
	);

	$options['olympus_twitter_widget_disable'] = array(
		'id' => 'olympus_twitter_widget_disable',
		'option_type' => 'option',
		'label'   => __( 'Twitter Video Widget?', 'olympus-widgets' ),
		'section' => $section,
		'type'    => 'checkbox',
		'default' => 0,
		'transport' => 'postMessage',
	);	
	
	$options['olympus_video_widget_disable'] = array(
		'id' => 'olympus_video_widget_disable',
		'option_type' => 'option',
		'label'   => __( 'Disable Video Widget?', 'olympus-widgets' ),
		'section' => $section,
		'type'    => 'checkbox',
		'default' => 0,
		'transport' => 'postMessage',
	);

	$section = 'panel-section';

	$sections[] = array(
		'id' => $section,
		'title' => __( 'Twitter API Settings', 'olympus-widgets' ),
		'priority' => '10',
		'panel' => $panel,
	);

	$options['olympus_twitter_consumer_key'] = array(
		'id' => 'olympus_twitter_consumer_key',
		'option_type' => 'option',
		'label'   => __( 'Consumer Key', 'olympus-widgets' ),
		'section' => $section,
		'type'    => 'text',
		'transport' => 'postMessage',
	);

	$options['olympus_twitter_consumer_secret'] = array(
		'id' => 'olympus_twitter_consumer_secret',
		'option_type' => 'option',
		'label'   => __( 'Consumer Secret', 'olympus-widgets' ),
		'section' => $section,
		'type'    => 'text',
		'transport' => 'postMessage',
	);

	$options['olympus_twitter_access_token'] = array(
		'id' => 'olympus_twitter_access_token',
		 'option_type' => 'option',
		'label'   => __( 'Consumer Access Token', 'olympus-widgets' ),
		'section' => $section,
		'type'    => 'text',
		'transport' => 'postMessage',
	);

	$options['olympus_twitter_access_token_secret'] = array(
		'id' => 'olympus_twitter_access_token_secret',
		 'option_type' => 'option',
		'label'   => __( 'Consumer Access Token Secret', 'olympus-widgets' ),
		'section' => $section,
		'type'    => 'text',
		'transport' => 'postMessage',
	);

	$section = 'social-profiles-section';

	$sections[] = array(
		'id' => $section,
		'title' => __( 'Social Profiles', 'olympus-widgets' ),
		'priority' => '10',
		'panel' => $panel,
	);

	// Adds the sections to the $options array.
	$options['sections'] = $sections;

	// Adds the panels to the $options array.
	$options['panels'] = $panels;

	$customizer_library = Customizer_Library::Instance();
	$customizer_library->add_options( $options );

	// To delete custom mods use: customizer_library_remove_theme_mods(); .
}
add_action( 'init', 'customizer_library_demo_options' );
