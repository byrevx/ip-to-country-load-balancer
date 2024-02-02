<?php
namespace ByREV\Geo;

require_once __DIR__ . '/../Networking/ByREV_IP.php';
use ByREV\Networking\ByREV_IP;

require_once('ByREV_COUNTRY_FLAGS.php');
use ByREV\GEO\Country;

class ByREV_Free_IP2C
{    
    private $LBS = [];
    private $LBS_FrontEnd = [];

    public $filter_backend = [];
    public $filter_frontend = [];
    
    // the last server-index returned after the execution of the functions getCountry(), getServer() or  getServer_Cors();
    // Server name-index in $Free_IP2C_Servers
    public $server = '';         

    // last IP used to get country
    public $ip = null;   

    // milliseconds - the time it took to execute the last getCountry function call
    public $time = 0;

    public $Free_IP2C_Servers = [

        'ipwhois.io' => [
            'privacy' => 'https://ipwhois.io/privacy',
            'home' => 'https://ipwhois.io/',
            'endpoint' => 'https://ipwho.is/{ip}?fields=country_code',   //?
            'response' => 'json',
            'field' => 'country_code',        
            'rate_min' => 0.22768,        
            'rate_mo' => 10000,
            'cors' => true,
        ],    

        'country.is' => [
            'privacy' => '',
            'home' => 'https://country.is/',
            'endpoint' => 'https://api.country.is/{ip}',
            'response' => 'json',
            'field' => 'country',        
            'rate_min' => -1,        
            'rate_mo' => 1296000,  // // no info about limit; set for 30/min = 1296000 
            'cors' => true,
        ],    

        
        'ipapi.co' => [
            'privacy' => 'https://ipapi.co/privacy/',
            'home' => 'https://ipapi.co/',
            'endpoint' => 'https://ipapi.co/{ip}/country_code/',       
            'response' => 'text',
            'field' => '',        
            'rate_min' => 0.6944,        
            'rate_day' => 1000,
            'rate_mo' => 30000, 
            'cors' => false,    
        ],       

        'get.geojs.io' => [
            'privacy' => 'https://www.geojs.io/privacy/',
            'home' => 'https://www.geojs.io/',
            'endpoint' => 'https://get.geojs.io/v1/ip/country/{ip}.json',
            'response' => 'json',
            'field' => 'country',        
            'rate_min' => -1,                
            'rate_mo' => 5184000,    //set for 120/min fix No rate limits (yet) / Cross-origin resource sharing (CORS) / IPv4 and IPv6 / HTTPS only
            'cors' => false,
        ],        

        'freeipapi.com' => [
            'privacy' => 'https://freeipapi.com/privacy',
            'home' => 'https://freeipapi.com/',
            'endpoint' => 'https://freeipapi.com/api/json/{ip}',
            'response' => 'json',
            'field' => 'countryCode',        
            'rate_min' => 60,                
            'rate_mo' => 1296000,    // 60 requests per minute. 2592000/mo
            'cors' => false, // ? true
        ],        
        
        'freegeoip.live' => [
            'privacy' => 'https://freegeoip.live/privay-policy.html',
            'home' => 'https://freegeoip.live/',
            'endpoint' => 'https://freegeoip.live/json/{ip}',
            'response' => 'json',
            'field' => 'country_code',        
            'rate_min' => -1,                
            'rate_mo' => 1296000,    // no info about limit; set for 30/min = 1296000 
            'cors' => true,
        ],          

        'reallyfreegeoip.org' => [
            'privacy' => '',     // NONE - missing from website!
            'home' => 'https://reallyfreegeoip.org/',
            'endpoint' => 'https://reallyfreegeoip.org/json/{ip}',
            'response' => 'json',
            'field' => 'country_code',        
            'rate_min' => -1,                
            'rate_mo' => 864000,    // No account needed and no limits. Just play nice; set for 20/min,
            'cors' => false,
        ],       

        'ip2c.org' => [
            'privacy' => 'https://about.ip2c.org/#privacygdpr',
            'home' =>       'https://about.ip2c.org/#about',
            'endpoint' =>   'https://ip2c.org/{ip}',
            'response' =>   'csv',            
            'bounded'=>     ';',
            'field' =>      '1',        // filed start from 0 
            'rate_min' =>   -1,                
            'rate_mo' =>    1339200,    // set as 30 req/min No account needed and no limits. Amount of requests per user / per day is unlimited, just be reasonable. 
            'cors' =>       false,      
        ],             

        'geoip.ximi.se' => [
            'privacy' => '',        // NONE - missing from website!
            'home' =>       'https://geoip.ximi.se/',
            'endpoint' =>   'https://geoip.ximi.se/api/{ip}/short',
            'response' => 'json',
            'field' => ['country'=>'code'],
            'rate_min' =>   -1,                
            'rate_mo' =>    446400,    // set as 10 req/min The API follows a fair use policy. There are no limits by default but if the service is abused your IP may get blocked.
            'cors' =>       false,     // is TRUE, but field is not simple index "flat" is array !w
        ],           

        'ipwhois.io' => [
            'privacy' => 'https://ipwhois.io/privacy', 
            'home' => 'https://ipwhois.io',
            'endpoint' => 'http://ipwho.is/{ip}',
            'response' => 'json',
            'field' => 'country_code',        
            'rate_min' => -1,                
            'rate_mo' => 10000,    // You can use our API for free up to 10,000 requests per month (identification by IP address and Referer header),
            'cors' => false,
        ],               

        'www.hostip.info' => [
            'privacy' => 'https://www.hostip.info/faq.php', 
            'home' => 'https://www.hostip.info/',
            'endpoint' => 'https://api.hostip.info/get_json.php?ip={ip}',
            'response' => 'json',
            'field' => 'country_code',        
            'rate_min' => -1,                
            'rate_mo' => 446400,    //  set as 10 req/min ~ 446400/mo; No limit is specified, but we must keep the requests at an acceptable, common sense level.
            'cors' => false,
        ],             

    ]; 

    /* ----------------------------------------------------------------------------------------------
        Get servers list & data (for using in BackEnd)
        - It's practically all the servers in the list
        - They can be accessed directly from the variable: $instance->Free_IP2C_Servers
    -------------------------------------------------------------------------------------------------*/
    public function getBackEnd_Servers() {
        return $this->Free_IP2C_Servers;
    }

    /* ----------------------------------------------------------------------------------------------
        Get servers list & data (for using in FrontEnd)
        - Only Server with CORS
    -------------------------------------------------------------------------------------------------*/
    private $_FrontEnd_Servers = [];
    public function getFrontEnd_Servers() {
         
        // returns the result that was already saved previously (from preview call); cached var;
        if (!empty($this->_FrontEnd_Servers))
            return $this->_FrontEnd_Servers;

        foreach ($this->FrontEnd_Servers as $server_name => $bandwidth) {
            $this->_FrontEnd_Servers[$server_name] = $this->Free_IP2C_Servers[$server_name];
        }

        return $this->_FrontEnd_Servers;
    }
    /* ---------------------------------------------------------------------------------------------------------------------------------------------
        Get Random Server Data/info from Server List (Free_IP2C_Servers);
        This result is for backend use - Basically, it searches in all the servers in the list
        Ispired from func: https://robertvicol.com/tech/software-optimisation-never-end-asymmetric-load-balancing-algoritm-review/
        "Asymetric Load Balancing Server" funtion variant    
    ------------------------------------------------------------------------------------------------------------------------------------------------*/    
    private $Bandwidth_BackEnd = null;
    private $Sum_BackEnd = 0;

    public function getServer() 
    {        
        // prepare server list for Load Balanced algorithm and save for later use.
        if ($this->Bandwidth_BackEnd == null) {
            asort($this->LBS);
            $this->Bandwidth_BackEnd = [];
            $segment_bandwidth = 0;
            foreach ($this->LBS as $server=>$bandwidth) {
                $segment_bandwidth+= $bandwidth;
                $this->Bandwidth_BackEnd[$server] = $segment_bandwidth;
            }
            
            $this->Sum_BackEnd = end( $this->Bandwidth_BackEnd );
        }
        if ($this->Sum_BackEnd == 0)
            return '';

        // Choose a server from the list
        $rand_bandwidth = mt_rand(1,  $this->Sum_BackEnd);
        
        foreach ($this->Bandwidth_BackEnd as $server=>$bandwidth) {
            if ($rand_bandwidth <= $bandwidth)
                break;
        }

        // Returns the data for the chosen server
        $this->server = $server;
        return $this->Free_IP2C_Servers[$server];
    }

    /* ----------------------------------------------------------------------------------------------
        Get Random Server Data/info from Server List (Free_IP2C_Servers);
        This result filters servers only for FontEnd use - ONLY Server with CORS
        "Asymetric Load Balancing Server" funtion variant
    -------------------------------------------------------------------------------------------------*/
    private $Bandwidth_FrontEnd = null;
    private $Sum_FrontEnd = 0;

    public function getServer_Cors() 
    { 
        // prepare server list for Load Balanced algorithm and save for later use.
        if ($this->Bandwidth_FrontEnd == null) 
        {
            asort($this->LBS_FrontEnd);             // Sort server list in ascending order, based on the bandwidth. Mandatory requirement for Load Balanced algorithm to work!
            $this->Bandwidth_FrontEnd = [];           
            $segment_bandwidth = 0;
            foreach ($this->LBS_FrontEnd as $server=>$bandwidth) {
                $segment_bandwidth+= $bandwidth;
                $this->Bandwidth_FrontEnd[$server] = $segment_bandwidth;
            }
            
            $this->Sum_FrontEnd = end( $this->Bandwidth_FrontEnd );
        }

        if ($this->Sum_FrontEnd == 0)
            return '';

        // Choose a server from the list
        $rand_bandwidth = mt_rand(1,  $this->Sum_FrontEnd);

        foreach ($this->Bandwidth_FrontEnd as $server=>$bandwidth) {
            if ($rand_bandwidth <= $bandwidth)
                break;
        }

        // Returns the data for the chosen server
        $this->server = $server;
        return $this->Free_IP2C_Servers[$server];
    }


    /* ----------------------------------------------------------------------------------------------
    Prepare server API data for using in frontend;
    -------------------------------------------------------------------------------------------------*/
    public function get_FrontEnd_ApiServer($ip=null) {

        $api_server = $this->getServer_cors();

        if (empty($api_server))
            return;

        if ($ip==null)
            $ip = $this->getIP();

        $endpoint = str_replace('{ip}', $ip, $api_server['endpoint']);
        $field = $api_server['field'];
        $response = $api_server['response'];

        $server_info = [
            'ip' => $ip,
            'endpoint' => $endpoint,
            'field' => $field,
            'type_response' => $response,            
        ];

        return json_encode($server_info);
    }


    /* --------------------------------------------
        Return Frontend Support status;
        TURE fi is enabled, otherwise FALSE.
    -----------------------------------------------*/       
    public function frontendSupport() {
        return empty($this->config['frontend-assets']) ? false : true;
    }

    public function insertDemo() {
        return empty($this->config['insert-demo']) ? false : true;
    }

    /* ----------------------------------------------------------------------------------------------
        Get Country by IP. 
        if the IP is missing from the parameters, then it will automatically take the visitor's IP.
        Important: For using in FrontEnd, $frontend must be true  (CORS)! 
        * Any server can be used if desired, as follows:
          - $frontend must be FALSE 
          - $server_name must contain the name of the chosen server;
    -------------------------------------------------------------------------------------------------*/      
    function getCountry($ip=null, $frontend=false, $server_name=null) {
        $time_pre = microtime(true);

        if ($ip==null)
            $ip = $this->getIP();

        $this->ip = $ip;
        
        if ($frontend)
            $api_server = $this->getServer_cors();
        elseif ( empty($server_name) )
            $api_server = $this->getServer();        
        else {             
             return $this->getCountryByServer($server_name, $ip); 
            }

        if (empty($api_server))
            return '';                

        $endpoint = str_replace('{ip}', $ip, $api_server['endpoint']);
        $ipdata = @file_get_contents($endpoint);

        // decode and get country (or empty string for errors)
        $country = self::get_Response_for_CountryField($api_server, $ipdata);

        $this->time = (microtime(true) - $time_pre)/1000;
        return $country;
     }

    /* ----------------------------------------------------------------------------------------------
        Get Country by Server name & IP
    -------------------------------------------------------------------------------------------------*/     
    function getCountryByServer($server_name, $ip) 
    {
        $time_pre = microtime(true);
        
        $this->server = $server_name;

        if (empty($this->Free_IP2C_Servers[$server_name]))
            return '';

        $api_server = $this->Free_IP2C_Servers[$server_name];

        // prepare endpoint API server URL
        $endpoint = str_replace('{ip}', $ip, $api_server['endpoint']);
       
        // retrive data prom API server
        $ipdata = @file_get_contents($endpoint);

        // decode and get country (or empty string for errors)
        $country = self::get_Response_for_CountryField($api_server, $ipdata);

        $this->time = (microtime(true) - $time_pre)/1000;
        return $country;
    }     

    /* -----------------------------------
        Get Country from Response Fields
    -------------------------------------*/   
    function get_Response_for_CountryField($api_server, $ipdata) 
    {        
        $country = '';

        $response_filed = !empty($api_server['response']) ? $api_server['response'] : '';

        switch ($response_filed) {
            case 'json':
                
                $data = json_decode($ipdata, true);
                $country_field = $api_server['field'];                
                // key=>field value
                if (is_array($country_field)) {                   
                    foreach ($country_field as $key=>$field) {
                        $country = !empty($data[$key][$field]) ? $data[$key][$field] : ''; 
                        break;
                    }
                }
                else
                // simple filed value
                    $country = !empty($data[$country_field]) ? $data[$country_field] : ''; 
                break;

            case 'text':
                $country = print_r($ipdata, true);
                break;

            case 'csv':
                $bounded = $api_server['bounded'];
                $field = $api_server['field'];
                $data = explode($bounded, $ipdata);
                $country = !empty($data[$field]) ? $data[$field] : '';

                break;
        }

        return $country;
    }

    /* ----------------------------------------------------------------------------------------------
        take the visitor's IP.
        more detailed/filed is returned in $ipInfo (if they are needed)
        using: class ByREV_IP
    -------------------------------------------------------------------------------------------------*/  
     static function getIP(&$ipInfo = null)
     {
        return ByREV_IP::getIP($ipInfo);   
     }

    /* ----------------------------------------------------------------------------------------------
        get Flag url by country_code
    -------------------------------------------------------------------------------------------------*/      
    public function flag_url_byCountry(string $country_code, int $size=720, string $format='avif') {
        return $this->country->url($country_code, $size, $format);
    }     


    /* ----------------------------------------------------------------------------------------------
        get Flag link by country_code
    -------------------------------------------------------------------------------------------------*/     
    public function flag_link_byCountry(string $country_code, int $size=720, string $format='avif') {
        return $this->country->link($country_code, $size, $format);        
    }           

    /* ----------------------------------------------------------------------------------------------
        get Flag url by IP location
    -------------------------------------------------------------------------------------------------*/       
    public function flag_url_byIP(string $ip=null,  int $size=720, string $format='avif') {
        if ($ip==null)
            $ip = ByREV_IP::getIP();

        $country_code = $this->getCountry($ip);

        return $this->flag_url_byCountry($country_code, $size, $format);
    }      

    public function flag_link_byIP(string $ip=null,  int $size=720, string $format='avif') {
        if ($ip==null)
            $ip = ByREV_IP::getIP();

        $country_code = $this->getCountry($ip);

        return $this->flag_link_byCountry($country_code, $size, $format);
    }      
    

    /* ----------------------------------------------------------------------------------------------
        Construct:
        - prepare CORS & NON-CORS data;
        - $filter_backend : array with server omited from backend use
        - $filter_frontend : array with server omited from frontend use        
    -------------------------------------------------------------------------------------------------*/  
    public $_Sum_FrontEnd = 0;
    public $_Sum_BackEnd = 0;
    public $country = null;
    public function __construct(Array $config=[]) 
    {
        $byrev_LBS = [];
        $byrev_LBS_FrontEnd = [];

        foreach ($this->Free_IP2C_Servers as $s_name=>$s_info) {                        
                $byrev_LBS[$s_name] = $s_info['rate_mo'];
                        
            if ($s_info['cors'])
                $byrev_LBS_FrontEnd[$s_name] = $s_info['rate_mo'];            
        }

        asort($byrev_LBS);              // dup
        asort($byrev_LBS_FrontEnd);     // dup

        $this->LBS = $byrev_LBS;  
        $this->LBS_FrontEnd = $byrev_LBS_FrontEnd;             

        // save original unfiltered servers for future use;        
        $this->BackEnd_Servers = $byrev_LBS;    
        $this->FrontEnd_Servers = $byrev_LBS_FrontEnd;    
        // -- and all Sum/Bandwidth built-in Server;
        $this->_Sum_BackEnd = array_sum($byrev_LBS);
        $this->_Sum_FrontEnd = array_sum($byrev_LBS_FrontEnd);    
        
        $this->options_default();

        if (!empty($config))
            $this->config($config);

        $this->country = new Country();
    }
        

    public $options_default=[];
    public $usecase=[];
    function options_default() {

        // Agree explicitly to the conditions in this Privacy Policy through a checkbox
        $agree = '0';

        // Real Time Test DISABLED by Default
        $rts = '0';

        // FrontEnd Support DISABLED by Default
        $frontend_assets = '0';

        // insert-demo in FrontEnd website; DISABLED by Defaul
        // It should only be used to test the functionality!
        $insert_demo = '0';

        // Enable all BackEnd servers
        $backend = [];
        foreach ($this->BackEnd_Servers as $name=>$val) {
            $backend[$name] = '1';
        }

        // Enable all FronEnd servers
        $frontend = [];
        foreach ($this->FrontEnd_Servers as $name=>$val) {
            $frontend[$name] = '1';
        }        
        
        // LOAD Code example from "byrev-free-ip2c-usecase.txt"
        $this->usecase = array (
            'case1' => '',
            'case2' => '',
            'case3' => '',
            'case4' => '',
        );

        $usecase_data = file_get_contents(dirname(__FILE__).'/byrev-free-ip2c-usecase.txt');

        $usecase_data = explode('[[~]]', $usecase_data);
        if (count($usecase_data)>0) {
            foreach ($usecase_data as $index=>$info) {
                $info = trim($info);
                if (empty($info))
                    continue;

                $name = 'case'.($index+1);
                $data = explode('@@', $info);
                if ( count($data) > 0) {
                    $title = htmlentities(trim($data[0]));  // "clean" as text
                    $desc = htmlentities(trim($data[1]));   // "clean" as text
                } else {
                    $title = 'Case '.$index;
                    $desc = '...';
                }
                $this->usecase[$name] = ['title'=>$title, 'desc'=>$desc];                
            }
        }
        
        // Set Default Options
        $this->options_default = [      
            'agree' => $agree,
            'rts' => $rts,
            'frontend-assets'=> $frontend_assets,
            'backend' => $backend,
            'frontend' => $frontend,
            'insert-demo' => $insert_demo,
            'usecase' => $this->usecase,
        ];
    }

    // Load Options/Config
    public $config;
    public function config(Array $config=[]) {

        $this->config = $config;

        // reset
        $this->Bandwidth_FrontEnd = null;
        $this->Sum_FrontEnd = 0;
    
        $this->Bandwidth_BackEnd = null;
        $this->Sum_BackEnd = 0;  
        
        // load config;
        $_frontend = !empty($config['frontend']) && is_array($config['frontend']) ? $config['frontend'] : [];
        $_backend = !empty($config['backend']) && is_array($config['backend']) ? $config['backend'] : [];  

        foreach ($this->LBS as $server=>$info) {            
            if (empty($_backend[$server]))
                unset($this->LBS[$server]);
        }   

        foreach ($this->LBS_FrontEnd as $server=>$info) {            
            if (empty($_frontend[$server]))
                unset($this->LBS_FrontEnd[$server]);
        }

    }    
}

