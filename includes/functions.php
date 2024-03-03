<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
require_once('ByREV\Geo\ByREV_FREE_IP2C_WP.php');
use ByREV\Geo\ByREV_FREE_IP2C_WP;
use ByREV\Networking\ByREV_IP;

add_action('plugins_loaded', 'ByREV_IP2clb_plugin_init');

/**
 * Looks up the country code for an IP address using IP to Country Load Balancer.
 *
 * This function retrieves the country code for the provided IP address, considering
 * potential configuration and server selection.
 *
 * The function performs the following:
 *
 * 1. Checks if the global variable `$IP2clb` is not null (indicating the plugin is initialized).
 * 2. If `$IP2clb` is null, returns `false`.
 * 3. Calls the `getCountry` method of the `$IP2clb` object (assumed) to retrieve
 *    the country code, passing the following arguments:
 *    - `$ip`: The IP address (optional, defaults to null).
 *    - `false`: Disables caching (assumed behavior).
 *    - `$server_name`: The optional server name to use (defaults to null).
 *
 * @param string $ip (optional) The IP address to lookup. Defaults to null.
 * @param string $server_name (optional) The specific server name to use. Defaults to null.
 * @param global $IP2clb Reference to the global IP2Country object (assumed).
 * @uses ByREV_FREE_IP2C_WP::getCountry() (assumed) Performs the country code lookup.
 *
 * @return string|false The country code on success, or `false` if not available.
 *
 * @note This function assumes the `$IP2clb` global variable is initialized
 *       and has the `getCountry` method.
 */
function ByREV_IP2clb($ip=null, $server_name=null) {
    global $IP2clb;
    if ($IP2clb == null)
        return false;
    return $IP2clb->getCountry($ip, false, $server_name);
}

/**
 * Retrieves the IP to Country Load Balancer server information for frontend usage.
 *
 * This function fetches the IP to Country Load Balancer server information suitable for frontend use,
 * considering potential configuration and availability.
 *
 * The function performs the following:
 *
 * 1. Checks if the global variable `$IP2clb` is not null (indicating the plugin is initialized).
 * 2. If `$IP2clb` is null, returns `false`.
 * 3. Calls the `get_FrontEnd_ApiServer` method of the `$IP2clb` object (assumed)
 *    to retrieve the server information, potentially considering configuration options.
 *
 * @param string $ip (optional) The IP address to use for server selection. Defaults to null.
 * @param global $IP2clb Reference to the global IP2Country object (assumed).
 * @uses ByREV_FREE_IP2C_WP::get_FrontEnd_ApiServer() (assumed) Retrieves server information for frontend.
 *
 * @return mixed The retrieved server information or `false` if not available.
 *
 * @note This function assumes the `$IP2clb` global variable is initialized
 *       and has the `get_FrontEnd_ApiServer` method.
 */
function ByREV_Free_FrontEnd_IP2C($ip=null) {
    global $IP2clb;
    if ($IP2clb == null)
        return false;
    return $IP2clb->get_FrontEnd_ApiServer($ip);
}

/**
 * Initializes the IP to Country Load Balancer plugin upon plugin activation.
 *
 * This function performs the following actions:
 *
 * 1. Retrieves the plugin options from the database using `get_option('IP2clb_options')`.
 * 2. Initializes an empty configuration array (`$config`).
 * 3. Extracts configuration values from the retrieved options:
 *    - `enabled`: Boolean, defaults to 0 (false).
 *    - `backend`: Array, defaults to an empty array.
 *    - `frontend`: Array, defaults to an empty array.
 *    - `frontend-assets`: Integer, defaults to 0.
 *    - `insert-demo`: Integer, defaults to 0.
 * 4. Creates a new instance of the `ByREV_FREE_IP2C_WP` class using the extracted configuration and stores it in the global variable `$GLOBALS['IP2clb']`.
 *
 * @uses get_option() Retrieves options from the database.
 * @uses ByREV_FREE_IP2C_WP (assumed) Class for the IP2Country functionality.
 *
 * @note This function is typically executed upon plugin activation.
 */
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

/**
 * Generates a CSS background-image property with a linear gradient.
 *
 * This function takes two arguments:
 *
 * - `$bw` (int): The width of the first color band as a percentage of the total width.
 * - `$color` (array, optional): An array of four colors representing the gradient stops. Defaults to
 *   ['#ff7700', '#ccf', '#FFF', '#f0f0f1'].
 *
 * The function constructs a CSS `background-image` property with a linear gradient from right to left,
 * using the provided colors and the specified width for the first color band.
 *
 * @param int $bw The width of the first color band as a percentage.
 * @param array $color (optional) The array of colors for the gradient stops. Defaults to specific colors.
 *
 * @return string The CSS style rule for the background-image property.
 */
function byrev_bg_gradient($bw, $color = ['#ff7700', '#ccf', '#FFF', '#f0f0f1']) {    
    $bwr = $bw+10;
    $style = 'background-image: linear-gradient(to right, '.$color[0].' 0%, '.$color[1].' '.$bw.'%, '.$color[2].' '.$bwr.'%, '.$color[3].' 100%)';
    return $style;
}


/**
 * Checks if a specific value matches an element within a potentially nested array.
 *
 * This function takes several arguments:
 *
 * - `$val` (mixed): The value to compare with the element in the array.
 * - `$var` (mixed, optional): The array to search within. Defaults to an empty array.
 * - `$field1` (string): The first level key to access within the array. Defaults to an empty string.
 * - `$field2` (string, optional): The second level key to access within the array if the first level exists. Defaults to null.
 *
 * The function checks the following conditions:
 *
 * 1. If `$var` is empty or not an array, it returns immediately.
 * 2. If the element at the `$field1` key is empty, it returns immediately.
 * 3. If `$field2` is provided, it checks if the element at `$field1->$field2` matches `$val`.
 * 4. If `$field2` is not provided, it calls the Wordpress `checked` function with `$val` and the element at the `$field1` key.
 *
 * @param mixed $val The value to compare.
 * @param mixed $var (optional) The array to search within. Defaults to empty array.
 * @param string $field1 The first level key in the array. Defaults to empty string.
 * @param string $field2 (optional) The second level key in the array (if first level exists). Defaults to null.
 *
 * @return void (no explicit return value) - Outputs the HTML checked (or not) attribute (https://developer.wordpress.org/reference/functions/checked/)
 */
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


/**
 * Performs server health checks for internal API usage (Admin Page Test).
 *
 * This function fetches a list of free IP2Country servers (`$IP2clb->Free_IP2C_Servers`)
 * and performs the following checks for each server:
 *
 * 1. Generates a random IPv4 address (`ByREV_IP::getRandomsIPv4`).
 * 2. Looks up the country code for the IP address using the server (`ByREV_IP2clb`).
 * 3. Records the lookup time in microseconds (`$IP2clb->time * 1000000`).
 *
 * The function then returns an array of results, one for each server, containing:
 * - Server name (`server`)
 * - Random IP address (`random-ip`)
 * - Country code (`country_code`)
 * - Lookup time in microseconds (`time`)
 *
 * @uses global $IP2clb
 * @uses ByREV_IP::getRandomsIPv4() Generates random IPv4 addresses.
 * @uses ByREV_IP2clb() Uses the server to look up country code for an IP.
 * @uses json_encode() Encodes the results array as JSON.
 *
 * @note This function is intended for internal API usage and should not be exposed publicly.
 * @note The generated API key is temporary and used only for this single request.
 *
 * /*==================================================
 *  Internal API, CheckServerHealth for Admin Page Test
 * ====================================================*/
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

/**
 * Generates a unique API key used for CheckServerHealth in the Admin Page Test.
 * 
 * @param bool $reset (optional) Set to `true` to reset the key, defaults to `false`.
 * @param int $expire (optional) The expiration time for the key in seconds, defaults to 3600 (1 hour).
 * 
 * @return string|null The generated API key on success, `null` if the key is expired or invalid.
 * 
 * @throws Exception If session couldn't be started.
 * 
 * @uses session_status() Checks the session status.
 * @uses @session_start() Starts a new session, suppressing errors.
 * @uses openssl_random_pseudo_bytes() Generates a cryptographically secure random string.
 * @uses hash() Calculates the SHA-256 hash of the generated string.
 * @uses base64_encode() Encodes the key as a base64 string for storage in the session.
 * @uses base64_decode() Decodes the key from base64 when retrieved.
 * 
 * @note This function stores the key and its expiration time in the session.
 * @note The key is used for Check Server Health in the Admin Page Test one time only.
 * @note Calling the function with `$reset = true` will invalidate the current key; which happens automatically for Check Server Health
 */
function ByREV_ajax_key($reset=false, $expire=3600)  
{ 
    if(session_status() !== PHP_SESSION_ACTIVE) @session_start();

    $expired = isset($_SESSION['byrev-api-key-expired']) ? $_SESSION['byrev-api-key-expired'] < time() : false;

    if (!isset($_SESSION['byrev-api-key']) || $expired) {
        $key = hash('sha256', openssl_random_pseudo_bytes(32));
        $_SESSION['byrev-api-key'] = $key ;
        $_SESSION['byrev-api-key-expired'] = time() + $expire;
    }

    $key = $_SESSION['byrev-api-key'];

    if ($reset)
        unset($_SESSION['byrev-api-key']);

    return $key;  
}

/**
 * Renders the IP2Country shortcode.
 *
 * This function is used to render the shortcode `[IP2clb]` in your WordPress content.
 * It expects attributes and content to be passed, then delegates the rendering to the
 * `IP2clb` object's `shortcode` method.
 *
 * @param array $atts (optional) An associative array of shortcode attributes.
 * @param string $content (optional) The content enclosed within the shortcode.
 *
 * @return string The rendered content generated by the `IP2clb` object's `shortcode` method.
 *
 * @uses add_shortcode() Registers the shortcode with WordPress.
 * @uses $IP2clb (global) The `IP2clb` object (assumed to be initialized elsewhere).
 *
 * @note This function requires the `IP2clb` object to be available globally.
 *       Ensure it's properly initialized before using this shortcode.
 */
function ByREV_free_ip2c_shortcode($atts = [], $content = null) {
    global $IP2clb;
    return $IP2clb->shortcode($atts, $content);
}
add_shortcode('IP2clb', 'ByREV_free_ip2c_shortcode');

/**
 * Enqueues JavaScript code for IP to Country Load Balancer frontend support (if enabled).
 *
 * This function checks if the `IP2clb` object supports frontend usage and,
 * if so, registers and enqueues the necessary JavaScript file (`byrev-ip2c.js`)
 * from the plugin's assets directory.
 *
 * @uses global $IP2clb
 * @uses wp_register_script() Registers the script.
 * @uses wp_enqueue_script() Enqueues the registered script.
 *
 * @note This function relies on the `$IP2clb` object being available globally and
 *       having a `frontendSupport()` method to determine support.
 */
function ByREV_IP2clb_FrontendSupport() {
    global $IP2clb;

    if (!$IP2clb->frontendSupport())
        return;

    $assets_js = __BYREV_IP2CLB_URL.'assets/byrev-ip2c.js';
    wp_register_script( 'byrev-ip2c-frontent-js', $assets_js);
    wp_enqueue_script( 'byrev-ip2c-frontent-js' );
}

/**
 * Registers a REST endpoint for "Servers Health" checks and enables frontend support.
 *
 * This code snippet performs two main actions:
 *
 * 1. **Registers a REST endpoint:**
 *    - Endpoint path: `/ip2country/servers-health/`
 *    - Method: GET
 *    - Callback function: `ByREV_IP2clb_CheckServerHealth` (assumed to perform server health checks)
 *    - Permission callback:
 *       - Only accessible from the plugin admin page.
 *       - Requires a valid API key passed in the request parameter `byrevapikey`.
 *       - The API key is used once and then deleted.
 * 2. **Enables frontend support:**
 *    - Calls the function `ByREV_IP2clb_FrontendSupport` (assumed to handle necessary actions for frontend functionality).
 *
 * @uses add_action('plugins_loaded', ...) Registers actions on plugin load.
 * @uses add_action('rest_api_init', ...) Registers actions on REST API initialization.
 * @uses register_rest_route() Registers a REST route.
 * @uses ByREV_IP2clb_CheckServerHealth() (assumed) Function for server health checks.
 * @uses ByREV_ajax_key(true) (assumed) Function to generate and manage API key.
 * @uses ByREV_IP2clb_FrontendSupport() (assumed) Function for enabling frontend support.
 *
 * @note This API endpoint is intended for internal plugin usage and shouldn't be exposed publicly.
 * @note The API key usage is limited to one server health check request.
 */
add_action('plugins_loaded', function () {

    // Registering a REST endpoint for Servers Health checks. 
    // It represents the support for the "Servers Health" page in the plugin.
    add_action('rest_api_init', function () {
        register_rest_route('ip2country/', 'servers-health/', array(
        'methods' => 'GET',                     
        'callback' => 'ByREV_IP2clb_CheckServerHealth',       // CheckServerHealth for Admin Page Test; Only as Internal API, 
        'permission_callback' => function () {            
            // This call is only accessible from the plugin admin page only, no code is implemented for public access.
            // NOTE: The API key is used only once, after which it will be deleted!
            $key = (!empty($_REQUEST['byrevapikey'])) ? $_REQUEST['byrevapikey'] : null;
            return (!empty($key) && ( $key === ByREV_ajax_key(true)) );                                
        },
      ));
    });

    ByREV_IP2clb_FrontendSupport();
});
  
/**
 * Runs demo code for IP to Country Load Balancer frontend functionality (if enabled and available).
 *
 * This function checks if the `IP2clb` object supports frontend usage and if demo
 * code insertion is allowed. If both conditions are met, it includes a separate file
 * named `demo-code.php` assumed to contain the demo logic.
 *
 * @uses global $IP2clb
 * @uses include() Includes the demo code file.
 *
 * @note This function uses a separate file (`demo-code.php`) for the actual demo logic.
 * @note This function relies on the `$IP2clb` object being available globally and
 *       having `frontendSupport()` and `insertDemo()` methods.
 */
function ByREV_IP2clb_FrontEndDemo() {
    global $IP2clb;

    if (!$IP2clb->frontendSupport())
        return;

    if (!$IP2clb->insertDemo())
        return;

    include('demo-code.php');
}

add_action ('wp_footer', 'ByREV_IP2clb_FrontEndDemo');

