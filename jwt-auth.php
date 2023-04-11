<?php
/**
 * Plugin Name: JWT Auth
 * Plugin URI:  https://github.com/usefulteam/jwt-auth
 * Description: WordPress JWT Authentication.
 * Version:     3.0.1
 * Author:      Useful Team
 * Author URI:  https://usefulteam.com
 * License:     GPL-3.0
 * License URI: https://oss.ninja/gpl-3.0?organization=Useful%20Team&project=jwt-auth
 * Text Domain: jwt-auth
 * Domain Path: /languages
 *
 * @package jwt-auth
 */

// add_action( 'rest_api_init', 'register_jwt_auth');
// function register_jwt_auth() {
// } 

defined( 'ABSPATH' ) || die( "Can't access directly" );
	
// Helper constants.
define( 'JWT_AUTH_PLUGIN_DIR', rtrim( plugin_dir_path( __FILE__ ), '/' ) );
define( 'JWT_AUTH_PLUGIN_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );
define( 'JWT_AUTH_PLUGIN_VERSION', '3.0.1' );

if ( !function_exists( 'is_rest' ) ) {
    /**
     * Checks if the current request is a WP REST API request.
     *
     * Case #1: After WP_REST_Request initialisation
     * Case #2: Support "plain" permalink settings and check if `rest_route` starts with `/`
     * Case #3: It can happen that WP_Rewrite is not yet initialized,
     *          so do this (wp-settings.php)
     * Case #4: URL Path begins with wp-json/ (your REST prefix)
     *          Also supports WP installations in subfolders
     *
     * @returns boolean
     * @author matzeeable
     */
    function is_rest() {
        if (defined('REST_REQUEST') && REST_REQUEST // (#1)
                || isset($_GET['rest_route']) // (#2)
                        && strpos( $_GET['rest_route'], '/', 0 ) === 0)
                return true;

        // (#3)
        global $wp_rewrite;
        if ($wp_rewrite === null) $wp_rewrite = new WP_Rewrite();
            
        // (#4)
        $rest_url = wp_parse_url( trailingslashit( rest_url( ) ) );
        $current_url = wp_parse_url( add_query_arg( array( ) ) );
        return strpos( $current_url['path'] ?? '/', $rest_url['path'], 0 ) === 0;
    }
}


if( is_rest() ){
	// Require composer.
	require JWT_AUTH_PLUGIN_DIR . '/vendor/autoload.php';
	
	// Require classes.
	require JWT_AUTH_PLUGIN_DIR . '/class-auth.php';
	require JWT_AUTH_PLUGIN_DIR . '/class-setup.php';
	require JWT_AUTH_PLUGIN_DIR . '/class-devices.php';
	
	JWTAuth\Setup::getInstance();
}

