<?php
/*
	Plugin Name: Launch Control
	Plugin URI: http://cuestartup.com/wordpress-signup-membership-plugin/
	Description: Quick Registration Holding Page - With a click of a button hide your website when you need to change a few things or run an upgrade, making it only accessible admins and allow users to register.
	Version: 1.1.0
	Author: Launch Control
	Author URI: http://cuestartup.com/
	License: GPL2 or later
	 * Text Domain: launch_control
*/
/*  Copyright 2014  Launch Control  (email : info@cuestartup.com)

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/
	
	
	define( 'LIB_DIR', 'lib' ); 
	define( 'PLUGIN_URL', WP_PLUGIN_URL . '/' . basename (dirname ( __FILE__ ) ) . '/' );
	
	include_once 'functions.php';
	
	$mt_options = mt_get_option();
		
	if ($mt_options['state'] == "launchcontrol") {
		if (($mt_options['lib_options']['admin_bar_enabled'] == "1")) { 
			add_filter('show_admin_bar', '__return_true');  																	 
		} else {
			add_filter('show_admin_bar', '__return_false');  																	 
		}
	}
	
	
	/*launchcontrol mode is active*/
	
	add_action( 'template_redirect', 'mt_template_redirect' );
	add_action( 'admin_menu', 'dashboard_menu' );
	add_action( 'wp_logout','user_logout');
	register_deactivation_hook( __FILE__, 'deactivate_plugin' );
	
	include_once 'page-options.php';

	function dashboard_menu()
	{
		$page = add_menu_page('Launch Control', 'Launch Control', 'manage_options', 'Launch Control', 'mt_manage_options', PLUGIN_URL . '/images/icon-small.png');
		add_action( "admin_print_styles-$page", 'admin_head' );
	}
	
	
	function mn_plugin_init() {
		load_plugin_textdomain( 'launchcontrol', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
	add_action('plugins_loaded', 'mn_plugin_init');
	
	function admin_head()
	{	
	
		echo css_activate ();
		wp_enqueue_script( 'launchcontrol', PLUGIN_URL .'js/init.js' );
	    wp_enqueue_style  ('launchcontrol', PLUGIN_URL .'css/admin.css' );	
	}
	
	function deactivate_plugin()
	{
		return delete_option( basename(dirname(__FILE__)) );
	}

		
?>
