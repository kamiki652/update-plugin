<?php
/**
 * Plugin Name:       Update Plugin (Minimal)
 * Description:       A minimal WordPress plugin boilerplate.
 * Version:           1.0.0
 * Author:            kamiki652
 * Text Domain:       update-plugin
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Example functionality: Display a notice in the WordPress admin area.
 */
add_action( 'admin_notices', function() {
    echo '<div class="notice notice-success is-dismissible"><p>Update Plugin が有効化されました！</p></div>';
} );
