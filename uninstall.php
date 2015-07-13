<?php

	//if uninstall not called from WordPress exit
	if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) 
		exit();

	$options = array(
		'olympus_twitter_consumer_key',
		'olympus_twitter_consumer_secret',
		'olympus_twitter_access_token',
		'olympus_twitter_access_token_secret',

		'olympus_twitter_url',
		'olympus_facebook_url',
		'olympus_google_url',
		'olympus_pinterest_url',
		'olympus_linkedin_url',
		'olympus_rss_url',
		'olympus_youtube_url',
		'olympus_instagram_url',
		'olympus_email_url',

		'olympus_author_widget_disable',
		'olympus_dribbble_widget_disable',
		'olympus_facebook_widget_disable',
		'olympus_featured_posts_widget_disable',
		'olympus_flickr_widget_disable',
		'olympus_social_disable',
		'olympus_twitter_disable',
		'olympus_video_disable'
	);

	foreach( $options as $option ) {
		delete_option( $option );
	}