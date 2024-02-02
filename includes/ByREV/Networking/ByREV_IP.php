<?php
namespace ByREV\Networking;

class ByREV_IP {

    # Private IPv4 address ranges IPv4 converted to LongInt number.
    # This eliminates calling the ip2long function 32 times
    const privateRangesIPv4_Int = [
        [0, 16777215],
        [167772160, 184549375],
        [1681915904, 1686110207],
        [2130706432, 2147483647],
        [2851995648, 2852061183],
        [2886729728, 2887778303],
        [3221225472, 3221225727],
        [3221225984, 3221226239],
        [3227017984, 3227018239],
        [3232235520, 3232301055],
        [3323068416, 3323199487],
        [3325256704, 3325256959],
        [3405803776, 3405804031],
        [3758096384, 4294967294],
        [4026531840, 4294967294],
        [4294967295, 4294967295],
    ];   

    # The set of IPs v4 in "string" format as a backup for future updates in privateRangesIPv4_Int
    public $privateRangesIPv4_str = [
        ['0.0.0.0', '0.255.255.255'],
        ['10.0.0.0', '10.255.255.255'],
        ['100.64.0.0', '100.127.255.255'],
        ['127.0.0.0', '127.255.255.255'],
        ['169.254.0.0', '169.254.255.255'],
        ['172.16.0.0', '172.31.255.255'],
        ['192.0.0.0', '192.0.0.255'],
        ['192.0.2.0', '192.0.2.255'],
        ['192.88.99.0', '192.88.99.255'],
        ['192.168.0.0', '192.168.255.255'],
        ['198.18.0.0', '198.19.255.255'],
        ['198.51.100.0', '198.51.100.255'],
        ['203.0.113.0', '203.0.113.255'],
        ['224.0.0.0', '255.255.255.254'],
        ['240.0.0.0', '255.255.255.254'],
        ['255.255.255.255', '255.255.255.255']
    ];    

    /* ----------------------------------------------------------------------------------------------
        take the visitor's IP.
        more detailed/filed is returned in $ipInfo (if they are needed)
    -------------------------------------------------------------------------------------------------*/  
    function getIP(&$ipInfo = null)
    { 
        $ipInfo = [];
        $ip = '::1';

        $fields = [
            'REMOTE_ADDR',
            'HTTP_X_SUCURI_CLIENTIP',   // Sucuri firewall
            'HTTP_INCAP_CLIENT_IP',     // Incapsula firewall
            'HTTP_CF_CONNECTING_IP',    // CloudFlare CDN
            'HTTP_X_REAL_IP',
        ];

        // Get real client IP if ...
        foreach ($fields as $field) {
            if (isset($_SERVER[$field]) && filter_var($_SERVER[$field], FILTER_VALIDATE_IP)) {
                $ipRemoteAdd = $ip = $_SERVER[$field];
                $ipInfo[$field] = $ipRemoteAdd;                
            }
        }

        // Real client IP if they are behind Proxy 
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {			
            $ipForwarded = trim (end (explode (',', $_SERVER['HTTP_X_FORWARDED_FOR']) ) );            
            
            if (filter_var($ipForwarded, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                $ip = $ipForwarded;
                $ipInfo[$field] = $ipForwarded;
            }
        }

        return $ip;        
    }

    /* ====================================================================================
    Get randoms IPs 
    The functions check if the IP does not fall within the private IP address ranges.
    $verify_range : To generate any IP without being verified, use the parameter = false
    ===================================================================================== */

    function getRandomIPv4($verify_range=true) {
        if (!$verify_range)
            return long2ip(rand(0, 4294967295));    

        // get only Public IP adress; // return "false" if after 100 iterations a valid IP was not found!
        for ($i=0; $i<100; $i++)  {
            $ip = long2ip(rand(0, 4294967295));
            if (self::isPublicIPv4($ip))
                return $ip;
        }

        return false;

    }

    // Get list with randoms IPs 
    function getRandomsIPv4(Int $n=1, $verify_range=true) { 
        $ipv4 = [];
        for ($i=1; $i<$n; $i++)
            $ipv4[] = self::getRandomIPv4($verify_range);
        return $ipv4;
    }           

    // check if IPv4 is public (otherwise is in private/reserved range)
    function isPublicIPv4($ip) {
        // Check if the IP is valid and IPv4 type.
        if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return false;
        }        

        // Convert the IP to a LongInt number.
        $long = ip2long($ip);
            
        // Check if the IP is within the private IP address ranges.
        foreach (static::privateRangesIPv4_Int as $r) {
            if ($long >= $r[0] && $long <= $r[1]) {
                return false;
            }
        }        

        // If the IP does not fall into any of the private ranges, it is a public IP.
        return true;
    }

    /* ====================================================================================
    Get randoms IPs v6
    The functions check if the IP does not fall within the private IP address ranges.
    $verify_range : To generate any IP without being verified, use the parameter = false
    ===================================================================================== */    
    function getRandomIPv6($verify_range=true) {
        $ipv6 = '';

        if (!$verify_range) {
            for ($i = 0; $i < 8; $i++) {
                $ipv6 .= dechex(mt_rand(0, 65535)) . ':';
            }
            return substr($ipv6, 0, -1);
        } 
        
        else 
        
        { 
          return  self::getRandomPublicIPv6();
        }
        return $ipv6;
    }

    //Get random public IPs v6
    function getRandomPublicIPv6() {
        $ipv6 = '';
        do {
            for ($i = 0; $i < 8; $i++) {
                $ipv6 .= dechex(mt_rand(0, 65535)) . ':';
            }
            $ipv6 = substr($ipv6, 0, -1);
        } while (self::isReservedIPv6($ipv6));
        return $ipv6;
    }         


    /* ====================================================================================
    The generated IPs are chosen randomly, either v4 or v6.
    The functions check if the IP does not fall within the private IP address ranges.
    ===================================================================================== */    

    function getRandomIP($verify_range=true) {
        if (mt_rand(0,1) == 0)
            return getRandomIPv6($verify_range);
        getRandomIPv4($verify_range);
    }

    // Get list with randoms IPs 
    function getRandomsIP(Int $n=1, $verify_range=true) {
        $ips = [];
        for ($i=1; $i<$n; $i++)
            $ips[] = self::getRandomIP($verify_range);
        return $ips;
    }           

    /* ====================================================================================
    Check IPv6 if is Public IP or is Reserved/Private IP
    ===================================================================================== */       
    function isReservedIPv6($ipv6){        
        if (!self::isIPv6($ipv6))
            return false;

        $reservedPrefixes = array('::', '100::', '2001::', '2001:db8:', 'fc00:', 'fe80:');
        foreach ($reservedPrefixes as $prefix) {
            if (substr($ipv6, 0, strlen($prefix)) === $prefix) { 
                return true;
            }
        } 
        return false;
    } 

    // check if IPv6 is public (otherwise is in private/reserved range)
    function isPublicIPv6($ipv6) {
        if (!self::isIPv6($ipv6))        
            return false;

        return !self::isReservedIPv6($ipv6);
    }


    /* =============================================== */
    // check if the ip IPv4 valid
    function isIPv4($ip) {
        return (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false);
    }

    // check if the ip IPv6 valid
    function isIPv6($ip) { 
        return (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false);
    }    

    // check if the ip has a valid format (IPv6 or IPv4)
    function isIP($ip) {        
        return (filter_var($ip, FILTER_VALIDATE_IP) !== false);
    }

    // check if the ip isPublicIP v4 or v6
    function isPublicIP($ip) {
        if (self::isPublicIPv4($ip))
            return true;
        if (self::isPublicIPv6($ip))
            return true;      

        return false;  
    }
}
