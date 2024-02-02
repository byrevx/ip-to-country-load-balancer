<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
require_once('ByREV\Geo\ByREV_FREE_IP2C_WP.php');
use ByREV\Geo\ByREV_FREE_IP2C_WP;
use ByREV\Networking\ByREV_IP;

add_action('plugins_loaded', 'ByREV_IP2clb_plugin_init');

function ByREV_IP2clb($ip=null, $server_name=null) {
    global $IP2clb;
    if ($IP2clb == null)
        return false;
    return $IP2clb->getCountry($ip, false, $server_name);
}

function ByREV_Free_FrontEnd_IP2C($ip=null) {
    global $IP2clb;
    if ($IP2clb == null)
        return false;
    return $IP2clb->get_FrontEnd_ApiServer($ip);
}

function ByREV_IP2clb_plugin_init(){    

    $options = get_option('IP2clb_options');

    // apply config    
    $config = [];    
    $config['enabled'] =  !empty($options['enabled']) ? (int)$options['enabled'] : 0;
    $config['backend'] = !empty($options['backend']) ? (Array)$options['backend'] : [];
    $config['frontend'] = !empty($options['frontend']) ? (Array)$options['frontend'] : [];
    $config['frontend-assets'] =  !empty($options['frontend-assets']) ? (int)$options['frontend-assets'] : 0;
    $config['insert-demo'] = !empty($options['insert-demo']) ? (int)$options['insert-demo'] : 0;

    $GLOBALS['IP2clb'] = new ByREV_FREE_IP2C_WP($config);                
}

// ~~~~~~~~~~ utils ~~~~~~~~~~~~~~~~~

function byrev_bg_gradient($bw, $color = ['#ff7700', '#ccf', '#FFF', '#f0f0f1']) {    
    $bwr = $bw+10;
    $style = 'background-image: linear-gradient(to right, '.$color[0].' 0%, '.$color[1].' '.$bw.'%, '.$color[2].' '.$bwr.'%, '.$color[3].' 100%)';
    return $style;
}


function i_checked($val, $var=[], string $field1='', string $field2=null) {
    if (empty( $var) & !is_array($var))
        return;
    if (empty($var[$field1])) 
        return;
    if ($field2 == null) {
        checked($val, $var[$field1]);
        return;
    }
    checked($val, $var[$field1][$field2]);
}



/*============================================
 ativate and deactivate plugin functions
=============================================*/
function __IP2clb_activate() {

}

function __IP2clb_deactivate() {
    delete_option( 'IP2clb_options' );
}


/*==================================================
 Internal API, CheckServerHealth for Admin Page Test
====================================================*/
function ByREV_IP2clb_CheckServerHealth() {

    global $IP2clb;
    $api_servers = $IP2clb->Free_IP2C_Servers;
    
    $randIPs = ByREV_IP::getRandomsIPv4(count($api_servers)+1);
    
    $i = 0;
    $result = [];
    foreach ($api_servers as $name=>$server) {
        $ip = $randIPs[$i];  
        $country_code = ByREV_IP2clb($ip, $name);
        $time = round($IP2clb->time*1000000);
        $result[] = ['server'=>$name, 'random-ip'=> $ip, 'country_code'=> $country_code, 'time'=>$time];
        $i++;
    }
    
    return json_encode($result);
}

/*==================================================
 ip2c_shortcode
====================================================*/
function ByREV_free_ip2c_shortcode($atts = [], $content = null) {
    global $IP2clb;
    return $IP2clb->shortcode($atts, $content);
}
add_shortcode('IP2clb', 'ByREV_free_ip2c_shortcode');


# @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

// Insert js code (if FrontendSupport is enabled)
function ByREV_IP2clb_FrontendSupport() {
    global $IP2clb;

    if (!$IP2clb->frontendSupport())
        return;

    $assets_js = __BYREV_IP2CLB_URL.'assets/byrev-ip2c.js';
    wp_register_script( 'byrev-ip2c-frontent-js', $assets_js);
    wp_enqueue_script( 'byrev-ip2c-frontent-js' );
}

// Admin - Check Server Health
add_action('plugins_loaded', function () {

    add_action('rest_api_init', function () {
        register_rest_route('ip2country/', 'servers-health/', array(
        'methods' => 'GET',
        'callback' => 'ByREV_IP2clb_CheckServerHealth',
      ));
    });

    ByREV_IP2clb_FrontendSupport();
});
  
// DEMO Code in frontend
function ByREV_IP2clb_FrontEndDemo() {
    global $IP2clb;

    if (!$IP2clb->frontendSupport())
        return;

    if (!$IP2clb->insertDemo())
        return;

    include('demo-code.php');
}

add_action ('wp_footer', 'ByREV_IP2clb_FrontEndDemo');