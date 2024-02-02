
<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<h2>Testing the plugin on this server:</h2>

<b>Get country by any IP</b>
<pre class="code-text">
$ip= '8.8.8.8';
$c = $IP2clb-&gt;getCountry($ip);
echo 'Country: '.$c;
echo 'Test IP: '.$ip;
</pre>

<b>Result</b>
<pre class="code-output">
<?php
    $ip= '8.8.8.8';
    $c = $IP2clb->getCountry($ip);  
    echo 'Country: '.$c.'<br />';
    echo 'Test IP: '.$ip;
?>
</pre >

<hr class="hr-codex" />

<b>Get country code by visitors IP's</b>
<pre class="code-text">
$ip = $IP2clb-&gt;getIP($ipInfo).PHP_EOL;
echo 'Visitor IP: '.$ip;
echo 'IP Info: '; print_r($ipInfo);
</pre>
<b>Result</b>
<pre class="code-output">
<?php
    $ip = $IP2clb->getIP($ipInfo).PHP_EOL;
    echo 'Visitor IP: '.$ip.PHP_EOL;
    echo 'IP Info: '; print_r($ipInfo);
?>
</pre >

<hr class="hr-codex" />


<b>Get country <i>flag</i> by random IPs.</b>
<pre class="code-text">

use ByREV\Networking\ByREV_IP;

// generate public random IPv4
$randIP = ByREV_IP::getRandomIPv4();

$country_code = ByREV_IP2clb($randIP);

// get flag LINK by country code, width=128px and "jpg" format
$flag = $IP2clb-&gt;flag_link_byCountry($country_code, 128, 'jpg');

echo "IP: $randIP &lt;br /&gt;";
echo "API Server: $IP2clb-&gt;server &lt;br /&gt;";
echo "Country Code: $country_code &lt;br /&gt;";
echo '&lt;br /&gt;';
echo $flag;

// get flag URL by IP, width=150px &amp; "avif" format
$flag = $IP2clb-&gt;flag_url_byIP('8.8.8.8', 150, 'avif');
$flag = "&lt;img style='border: 3px solid black; box-shadow: 0px 0px 10px red;' src='$flag' &gt;";
echo '&lt;br /&gt;';
echo '&lt;br /&gt;';
echo $flag;

</pre>
<b>Result</b>
<pre class="code-output">
<?php
    use ByREV\Networking\ByREV_IP;
    // generate public random IPv4 
    $randIP = ByREV_IP::getRandomIPv4();                                       

    $country_code = ByREV_IP2clb($randIP); 

    // get flag LINK by country code,  width=128px and "jpg" format
    $flag = $IP2clb->flag_link_byCountry($country_code, 128, 'jpg');  

    echo "IP:  $randIP <br />";
    echo "API Server:  $IP2clb->server <br />";
    echo "Country Code: $country_code <br />";
    echo '<br />';
    echo $flag;

    // get flag LINK by IP, width=150px & "avif" format
    $flag = $IP2clb->flag_url_byIP('8.8.8.8', 150, 'avif');   
    $flag = "<img style='border: 3px solid black; box-shadow: 0px 0px 10px red;' src='$flag' >";
    echo '<br />';
    echo '<br />';
    echo $flag;
?>
</pre >

<hr class="hr-codex" />


<b>Get the <i>server name</i> with which the last request was made</b>
<pre class="code-text">
$ip= '4.4.4.4';
$c = $IP2clb-&gt;getCountry($ip);
echo 'Country: '.$c.PHP_EOL;
echo 'Server: '.$IP2clb-&gt;server;
</pre>
<b>Result</b>
<pre class="code-output">
<?php
    $ip= '4.4.4.4';
    $c = $IP2clb->getCountry($ip);      
    echo 'Country: '.$c.PHP_EOL;
    echo "Server: $IP2clb->server";
?>
</pre >

<hr class="hr-codex" />

<b>Get the Tesponse Time of the last server used.</b>
<pre class="code-text">
$time = $IP2clb-&gt;time;
echo 'getCountry Response (ms): '.round($time*1000000,2);
</pre>
<b>Result</b>
<pre class="code-output">
<?php
    $time = $IP2clb->time;
    echo 'Server Response (ms): '.round($time*1000000,2);
?>
</pre >

<?php