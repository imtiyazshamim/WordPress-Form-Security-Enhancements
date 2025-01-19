<?php
////for site security
////////////////////HTML not allowed////////////////////
add_filter('wpcf7_posted_data', 'sanitize_contact_form_data');

function sanitize_contact_form_data($posted_data) {
    // Array of fields to sanitize
    $fields_to_sanitize = array('namefield', 'organization', 'message');

    foreach ($fields_to_sanitize as $field) {
        if (isset($posted_data[$field])) {
            // Remove HTML tags from the field
            $posted_data[$field] = strip_tags($posted_data[$field]);
            
            // Remove URLs from the field
            $posted_data[$field] = preg_replace('/\bhttps?:\/\/\S+/', '', $posted_data[$field]);
        }
    }

    // Return the modified data
    return $posted_data;
}

/////////Special characters not allowed//////////////
add_filter('wpcf7_validate_text', 'custom_text_validation_filter', 10, 2);
add_filter('wpcf7_validate_text*', 'custom_text_validation_filter', 10, 2);
add_filter('wpcf7_validate_textarea', 'custom_text_validation_filter', 10, 2);
add_filter('wpcf7_validate_textarea*', 'custom_text_validation_filter', 10, 2);

function custom_text_validation_filter($result, $tag) {
    $name = $tag->name;
    $value = isset($_POST[$name]) ? sanitize_text_field($_POST[$name]) : '';

    // Adjust the regex to block more specific special characters if needed
    if (preg_match('/[^a-zA-Z0-9\s]/', $value)) {
        $result->invalidate($tag, "Special characters are not allowed.");
    }

    return $result;
}

//////////////////////////////////////
// Validate text fields for character limit
add_filter('wpcf7_validate_text', 'custom_length_validation_filter', 10, 2);
add_filter('wpcf7_validate_text*', 'custom_length_validation_filter', 10, 2);

// Validate email fields for character limit
add_filter('wpcf7_validate_email', 'custom_length_validation_filter', 10, 2);
add_filter('wpcf7_validate_email*', 'custom_length_validation_filter', 10, 2);

// Validate tel fields for character limit
add_filter('wpcf7_validate_tel', 'custom_length_validation_filter', 10, 2);
add_filter('wpcf7_validate_tel*', 'custom_length_validation_filter', 10, 2);

// Validate textarea fields for character limit
add_filter('wpcf7_validate_textarea', 'custom_length_validation_filter', 10, 2);
add_filter('wpcf7_validate_textarea*', 'custom_length_validation_filter', 10, 2);

function custom_length_validation_filter($result, $tag) {
    $name = $tag->name;
    $value = isset($_POST[$name]) ? sanitize_text_field($_POST[$name]) : '';

    $max_lengths = array(
        'namefield' => 20,
        'emailid' => 40,
        'phonenumber' => 15,
        'message' => 500
    );

    if (isset($max_lengths[$name]) && strlen($value) > $max_lengths[$name]) {
        $result->invalidate($tag, "This field should not exceed " . $max_lengths[$name] . " characters.");
    }

    return $result;
}

////for security - Disclosure of Sensitive Information Regarding Multiple Website Plugins, Including Version Details //////////////  
remove_action('wp_head', 'wp_generator');
# Protect the wp-content/plugins directory
// Remove version numbers from scripts and styles
function remove_plugin_version($src) {
    if (strpos($src, 'ver='))
        $src = remove_query_arg('ver', $src);
    return $src;
}
add_filter('style_loader_src', 'remove_plugin_version', 9999);
add_filter('script_loader_src', 'remove_plugin_version', 9999);

///Disable Plugin Version Disclosure
function remove_css_js_version( $src ) {
    if( strpos( $src, '?ver=' ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
}
add_filter( 'style_loader_src', 'remove_css_js_version', 10, 2 );
add_filter( 'script_loader_src', 'remove_css_js_version', 10, 2 );

/// Hide WordPress Version

remove_action('wp_head', 'wp_generator');

/////Disable feed
function disable_feeds() {
    wp_die(__('No feed available, please visit our <a href="'. get_bloginfo('url') .'">homepage</a>!'));
}

add_action('do_feed', 'disable_feeds', 1);
add_action('do_feed_rdf', 'disable_feeds', 1);
add_action('do_feed_rss', 'disable_feeds', 1);
add_action('do_feed_rss2', 'disable_feeds', 1);
add_action('do_feed_atom', 'disable_feeds', 1);
add_action('do_feed_rss2_comments', 'disable_feeds', 1);
add_action('do_feed_atom_comments', 'disable_feeds', 1); 
///Disabling Specific Feed Links
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'feed_links_extra', 3);

