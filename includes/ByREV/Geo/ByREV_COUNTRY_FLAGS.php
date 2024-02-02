<?php
namespace ByREV\Geo;

require_once('ByREV_COUNTRY_ISO_ALPHA_2.php');
use ByREV\GEO\CountryCode2;

    /* ============================================================================================================
    default api flag : https://countryflag.org/flag-{c}-{s}.{e}; 
        args:
        {c} = country
        {s} = permited resolution/size (size is width based, height is variable, depending the flag format / ratio)
        {e} = file valid extension: avif, webp, jxl, jpeg, png
    ============================================================================================================ */
class Country {    

    //default API:
    const default_api_flag = [
            'api' => 'https://countryflag.org/flag-{c}-{s}.{f}',
            'format' => ['avif', 'webp', 'jxl', 'jpg', 'png'],
            'size' => [100, 1024, 128, 1280, 1440, 150, 16, 1920, 200, 250, 2560, 32, 320, 3840, 48, 64, 640, 720],
    ];

    public $api = self::default_api_flag['api'];
    public $size = self::default_api_flag['size'];
    public $format = self::default_api_flag['format'];
    
    public function __construct(Array $api_flag = []) 
    {
        // replace default api, but we must use the arguments and the format wich can be seen in the 'default_api_flag' const.
        if (!empty($api_flag)) {            
                $this->api = !empty($api_flag['api']) ? $api_flag[$k] : $this->default_api_flag['api'];        
                $this->format = !empty($api_flag['format']) ? $api_flag[$k] : $this->default_api_flag['format'];   
                $this->size = !empty($api_flag['size']) ? $api_flag[$k] : $this->default_api_flag['size'];                     
        } 
    }


    /**
     * ===================================================================
     * get Country Flag by country_code (of two letters)
     *
     * @param string $country_code - 2 chars only
     * @param int $size - permited resolution/size (size is width based, height is variable, depending the flag format / ratio); default: 720 (pixels)
     * @param string $format - file valid extension: avif, webp, jxl, jpeg, png; default: avif
     * @param string $default - default value rturned if $country_code is invalid; default = 'https://countryflag.org/flag-ro-128.jpg'
     * @return string Country Flag URL; 
     * ===================================================================
     */     
    public function url(string $country_code, int $size=720, string $format='avif', string $default = 'https://countryflag.org/flag-ro-128.jpg' ) {
            
        if (strlen($country_code) != 2)
            return $default;    
                    
        if (!isset( CountryCode2::ISO_Alpha[ strtoupper($country_code) ] ))
            return $default;            
        
        if (!in_array($size, $this->size))
            $size = 720;

        if ($format == 'jpeg')
            $format = 'jpg';

        if (!in_array($format, $this->format))
            $format = 'avif';

        return str_replace(['{c}','{s}', '{f}'], [ strtolower($country_code), $size, $format], $this->api);
        
    }

    public function link(string $country_code, int $size=720, string $format='avif', string $default = 'https://countryflag.org/flag-ro-128.jpg' ) {
        $url= $this->url($country_code, $size, $format, $default);
        $name = $this->name($country_code);
        $link = '<img src="'.$url.'" alt="'.$name.' Flag" width="'.$size.'" >';  
        return $link;
    }
    
    /* ===================================================================
        get Country Name by country_code (of two letters)
    =================================================================== */
    public function name(string $country_code, string $default = '--') {
        if (strlen($country_code) != 2)
            return $default;        

        $country_code = strtoupper($country_code);
        return  array_key_exists($country_code, CountryCode2::ISO_Alpha) ? CountryCode2::ISO_Alpha[$country_code] : $default;        
    }
    
    /* ===================================================================================
        get Country Code by country_name
        $country_name = Full country name or partial name;
        $partial = by default, a search is also made if the country name is only partial.
            If this functionality is not desired, the "$partial" argument must be set to FALSE:
            How this option works:
            "America" will return US
            "Great Britain" will return GB
            "Emirates" will return AE
        $default = The default answer if the search did not give any positive result.
    ===================================================================================== */    
    public function code(string $country_name, bool $partial=true, string $default = '--') {
        $country_name = strtolower($country_name);

        foreach (CountryCode2::ISO_Alpha as $code=>$name) {
            $name = strtolower($name);
            if ($name == $country_name)
                return $code;
            
            // search by partial name
            if ($partial) {
                $names = explode(' ', $country_name);
                $names = array_map('trim', $names);
                $c=count($names);
                $n = 0;                
                foreach ($names as $_name) {
                    if (strpos($name, $_name) !== FALSE)
                        $n++;
                    if ($n == $c)
                        return $code;
                }
            }
        }        
    }
}