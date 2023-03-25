<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

// Remove version of Wordpres from the HTML Header
remove_action('wp_head', 'wp_generator');

// Remove the REST API lines from the HTML Header
remove_action('wp_head', 'rest_output_link_wp_head', 10);
remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);


/**
 * Enqueue the css
 */
add_action( 'wp_enqueue_scripts', 'fedorafr_theme_enqueue_styles' );
function fedorafr_theme_enqueue_styles() {
    $version = wp_get_theme()->get('Version');
	wp_enqueue_style('child-style', get_stylesheet_uri(), ['parenthandle'], $version);
    wp_enqueue_style('fedorafr-style', get_stylesheet_uri(), [], $version);
}


/**
 * RSS links from the HTML Header.
 */
add_action('init', 'fedorafr_rss');
function fedorafr_rss() {
    $url = get_bloginfo('url');

    // Disable built-in RSS from the HTML Header
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'feed_links_extra', 3);

    if (strpos($url, 'planet') !== false) {
        add_action('wp_head', function() {
            echo '<link rel="alternate" type="application/rss+xml" title="RSS 2.0 Feed" href="'.get_bloginfo('rss2_url').'" />';
        });
    }
}


/**
* Remove Emoji from WP.
*/
add_action('init', 'fedorafr_disable_emojis');
function fedorafr_disable_emojis() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    add_filter('tiny_mce_plugins', 'fedorafr_disable_emojis_tinymce');
}


/**
 * Remove Emoji from WP TinyMCE.
 */
function fedorafr_disable_emojis_tinymce($plugins) {
    if (is_array($plugins)) {
       return array_diff($plugins, ['wpemoji']);
    } else {
        return [];
    }
}
