<?php
/*
Plugin Name: Ajaxify WP Login
Plugin URI : https://wordpress.org/plugins/wp-login-ajaxify/
Description: Login Into Wordpress Via Ajax Request.
Version: 1.0.2
Author: Sajjad Hossain Sagor
Author URI: https://profiles.wordpress.org/sajjad67
Text Domain: wp-login-ajax

License: GPL2
This WordPress Plugin is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

This free software is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this software. If not, see http://www.gnu.org/licenses/gpl-2.0.html.
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// ---------------------------------------------------------
// Define Plugin Folders Path
// ---------------------------------------------------------
define( "WPLA_PLUGIN_PATH", plugin_dir_path( __FILE__ ) );
define( "WPLA_PLUGIN_URL", plugin_dir_url( __FILE__ ) );

// ---------------------------------------------------------
// Enqueue Script To Login Page
// ---------------------------------------------------------
add_action( 'login_enqueue_scripts', 'wpla_enqueue_script', 1 );

function wpla_enqueue_script()
{	
	wp_enqueue_script( 'wpla-script', plugins_url( "/assets/js/app.js", __FILE__ ), array( 'jquery' ), false );
}

add_action( "login_footer", "wpla_plugin_ajax_url" );

function wpla_plugin_ajax_url()
{	
	$loading_gif = plugins_url( '/assets/img/loading.gif', __FILE__ );
	
	echo "<style>.updating::before{content: '';position: absolute;top: 0;left: 0;right: 0;bottom: 0;background-color: rgba(187, 187, 187, 0.5);width: 100%;height: 100%;z-index: 2;background-image: url('$loading_gif');background-repeat: no-repeat;background-position: center;}</style><script>var wpla_plugin_ajax_url = '" . admin_url( 'admin-ajax.php' ) . "';</script>";
}

add_action( "wp_ajax_wpla_login_ajax", "wpla_login_ajax" );

add_action( "wp_ajax_nopriv_wpla_login_ajax", "wpla_login_ajax" );

function wpla_login_ajax()
{
	extract( $_POST );

	$credentials = array( 'user_login' => $login, 'user_password'=> $pass, 'remember' => ! empty( $rememberme ) );

	$loginResult = wp_signon( $credentials );

	$result = array();

	if ( strtolower( get_class( $loginResult ) ) == 'wp_user' )
	{
		$result['wp_success'] = "Successfully Logged in, redirecting...";
		
		wp_send_json( $result );
	}
	elseif ( strtolower( get_class( $loginResult ) ) == 'wp_error' )
	{
		$result['wp_error'] = $loginResult->get_error_message();
		
		wp_send_json( $result );
	}
	
	die();
}
