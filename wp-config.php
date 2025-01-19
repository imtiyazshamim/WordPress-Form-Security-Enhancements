<?php

/**for site security */
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);

/***29-05***/
define('DISALLOW_FILE_EDIT', true);
define('DISALLOW_FILE_MODS', true);
/*Cookie - httponly */
// Ensure cookies are set with HttpOnly and Secure flags
if (!defined('COOKIEPATH')) {
    define('COOKIEPATH', '/');
}

if (!defined('COOKIE_DOMAIN')) {
    define('COOKIE_DOMAIN', false);
}

if (!defined('COOKIEHASH')) {
    define('COOKIEHASH', md5($_SERVER['HTTP_HOST']));
}

// Override the default setcookie function to include HttpOnly and Secure flags
function set_secure_cookie($name, $value, $expire = 0, $path = COOKIEPATH, $domain = COOKIE_DOMAIN, $secure = true, $httponly = true) {
    setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
}

// Set authentication cookies with HttpOnly and Secure flags
add_action('set_auth_cookie', 'set_secure_auth_cookie', 10, 5);
function set_secure_auth_cookie($auth_cookie, $expire, $expiration, $user_id, $scheme) {
    set_secure_cookie(AUTH_COOKIE, $auth_cookie, $expire);
    set_secure_cookie(SECURE_AUTH_COOKIE, $auth_cookie, $expire);
    set_secure_cookie(LOGGED_IN_COOKIE, $auth_cookie, $expire);
}

// Set logged in cookies with HttpOnly and Secure flags
add_action('set_logged_in_cookie', 'set_secure_logged_in_cookie', 10, 5);
function set_secure_logged_in_cookie($logged_in_cookie, $expire, $expiration, $user_id, $scheme) {
    set_secure_cookie(LOGGED_IN_COOKIE, $logged_in_cookie, $expire);
}

/*Cookie - httponly close */