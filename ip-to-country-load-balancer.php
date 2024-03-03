<?php
/**
 * Plugin Name: 		IP to Country Load Balancer (ByREV)
 * Plugin URI: 			https://github.com/byrevx/ip-to-country-load-balancer
 * Description: 		WordPress plugin for country IP mapping using a free collection of servers that offer free API access. Is designed to balance the load among servers. The list of API servers used by the plugin only includes free servers that do not require prior registration or usage keys for API services. 
 * Version: 			1.0.0
 * Requires at least:	5.9.0
 * Requires PHP:		7.4
 * Author: 				Vicol Emilian Robert
 * Author URI: 			https://robertvicol.com/
 * Author Email: 		byrev@yahoo.com
 * Contributors:        byrev
 * License:             GPLv2 or later
 * License URI:         https://www.gnu.org/licenses/gpl-2.0.html
*/

/*
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation. You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

if ( ! defined( 'ABSPATH' ) ) { exit; }

define('__BYREV_IP2CLB_FOLDER', dirname(__FILE__));
define('__BYREV_IP2CLB_URL', plugin_dir_url( __FILE__ ));
define('__BYREV_IP2CLB_NAME','IP to Country Load Balancer (ByREV)');
define('__BYREV_IP2CLB_VERSION','1.0.0');

require_once('includes/functions.php');

// Activation and Deactivation Hooks
register_activation_hook(__FILE__, 'IP2clb_activate');
register_deactivation_hook(__FILE__, 'IP2clb_deactivate');

function IP2clb_activate() {
    // Code to perform upon plugin activation
    __IP2clb_activate();
}

function IP2clb_deactivate() {
    // Code to perform upon plugin deactivation
    __IP2clb_deactivate();
}

// Add a new submenu under WP Settings Menu
function IP2clb_menu() {
    add_options_page(__BYREV_IP2CLB_NAME, 'IP2Country Free API', 'manage_options', 'ip2country', 'IP2clb_options_page');
}
add_action('admin_menu', 'IP2clb_menu');


// Display the plugin Settings options page
function IP2clb_options_page() {
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }
    
    // Render the settings template
    include(sprintf("%s/templates/settings-tabs.php", __BYREV_IP2CLB_FOLDER));
}

// Register and define the settings
add_action('admin_init', 'IP2clb_admin_init');
function IP2clb_admin_init(){
    register_setting('IP2clb_options', 'IP2clb_options', 'IP2clb_validate_options');
}

// Validate user data for some/all of your settings
function IP2clb_validate_options($input) {
    // Check all user data and sanitize as necessary before saving
    return $input;
}

// Add settings link on plugin page
function IP2clb_settings_link($links) { 
    $settings_link = '<a href="options-general.php?page=ip2country">Settings</a>'; 
    array_unshift($links, $settings_link); 
    return $links; 
  }
  
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'IP2clb_settings_link' );


// Add JS Scripts for Admin Config Page!
function IP2clb_theme_scripts_admin($hook) {
    if ('settings_page_ip2country' != $hook) {
        return;
    }
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'jquery-ui-core' );
    wp_enqueue_script( 'jquery-ui-tabs' );
}
add_action('admin_enqueue_scripts', 'IP2clb_theme_scripts_admin');
