<?php 
namespace ByREV\Geo;

require_once('ByREV_FREE_IP2C.php');
use ByREV\GEO\ByREV_Free_IP2C;

require_once __DIR__ . '/../Networking/ByREV_IP.php';
use ByREV\Networking\ByREV_IP;

class ByREV_Free_IP2C_WP extends ByREV_Free_IP2C 
{

    function __construct(Array $config=[]) {
        parent::__construct($config);        
    }

    function shortcode($atts = [], $content = null) 
    { 
        $atts = shortcode_atts([
            'show' => 'country',       
            'ip' => null, 
            'size' => 720,
            'ext' => 'avif',
        ], $atts);
    
        $get = strtolower( $atts['show'] );
        $ip = ByREV_IP::isIP($atts['ip']) ? $atts['ip'] : '8.8.8.8';
        $size = intval($atts['size']);
        $ext = strtolower($atts['ext']);
    
        $result = '';
    
        switch ($get) {
            case 'country':
                $result = $this->getCountry($ip);
                break;
    
            case 'ip':
                $result = $this->getIP();
                break;
    
            case 'flag':
                $country_code = $this->getCountry($ip);
                $result = $this->flag_link_byCountry($country_code, $size, $ext);
                break;
    
            default:
            $result = $this->getCountry($ip);
        }
    
        return $result;        
    }



}