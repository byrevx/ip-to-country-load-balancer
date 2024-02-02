<!-- START: ByREV_FREE_IP2C FrontEnd Support DEMO -->
<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }
    use ByREV\Networking\ByREV_IP;

    global $IP2clb;
    $ip = $IP2clb->getIP();
    if (!ByREV_IP::isPublicIP($ip))
        $ip = ByREV_IP::getRandomIPv4();
?>
<style>
    .ip2c_demo {
        display: none;
        padding: 10px; 
        text-align: center; 
        margin: 10px; 
        background: #ebe;
        position: fixed;
        width: 100%;
        top: 30px;
        z-index: 4294967294;
        opacity: 0.8;
    }
 </style>

 <!-- The HTML code used to display a message 1 -->
 <div id="i2pc-country" style="" class="ip2c_demo">
    Your Country: <span style"color: red">?</span> is not in NATO | IP: <b>?</b>
 </div>

  <!-- The HTML code used to display a message 2 -->
  <div id="i2pc-country2" style="" class="ip2c_demo">
    Your Country: <span style="color: blue">?</span> is a NATO member state | IP: <b>?</b>
 </div>

   <!-- The HTML code used to display a message 2 -->
  <div id="i2pc-country3" style="" class="ip2c_demo">
    Your Country: <span style="color: blue">?</span> | IP: <b>?</b>
 </div>


 <script>     
    // we initialize a variable with the necessary data to use the API. It can also be used with any IP in the function call.
    var ip2c_api = '<?php echo $IP2clb->get_FrontEnd_ApiServer($ip); ?>';   

    // callback function which will be called after the API call has finished and returned a positive result.
    var ip2c_callback = 'ShowInfoBox';

    // A list of countries that we will check, a different message will be displayed depending on the group in which the country will be found.
    var target_country1 = ['AT','CY','IE','SE','MT','CN'];
    var target_country2 = ['US','FR','DE', 'RO','BG','CA','NO'];

    // displaying a pre-set message  
    function ShowInfoBox() {                            
        var index = target_country1.indexOf(country_code);
        let ok = 0;
        if (index !== -1) {
            var e = document.getElementById("i2pc-country");
            e.querySelector("span").innerHTML = country_code;  // in the 'country_code' variable, there is the two-letter country code, resulting from the API call
            e.querySelector("b").innerHTML = ip2c; 
            e.style.display = "block";      
            ok = 1;   
        }        

        var index = target_country2.indexOf(country_code);
        if (index !== -1) {
            var e = document.getElementById("i2pc-country2");
            e.querySelector("span").innerHTML = country_code;  // in the 'country_code' variable, there is the two-letter country code, resulting from the API call
            e.querySelector("b").innerHTML = ip2c; 
            e.style.display = "block";      
            ok = 1;      
        }       

        if (ok == 0) {
            var e = document.getElementById("i2pc-country3");
            e.querySelector("span").innerHTML = country_code;  // in the 'country_code' variable, there is the two-letter country code, resulting from the API call
            e.querySelector("b").innerHTML = ip2c; 
            e.style.display = "block";  
        }
    }   
 </script>
<!-- END: ByREV_FREE_IP2C FrontEnd Support DEMO -->
<?php 