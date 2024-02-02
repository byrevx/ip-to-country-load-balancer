<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<h3>The test result will be sorted in the order of the fastest API servers.</h3>

<button class="button" type="button" name="servers-health-check" id="servers-health-check">Chech Severs Health</button>
<p id ="servers-health" class="">
...
</p>
<h3>Status: <span id="check-status"><b>Click to <u>"Chech Severs Health"</u> Button and wait until the test is finished!</b></span></h3>

<hr class="hr-codex" />

<script>
jQuery('#servers-health-check').click(function() {
    jQuery('#servers-health').html('Please wait a few moments for the test to finish...');
    //return;

    jQuery.ajax({
        url: '/wp-json/ip2country/servers-health/',
        method: 'GET',
        success: function(data) {
            try {
                var jsonData = JSON.parse(data);
                console.log(jsonData);

                jsonData.sort((a, b) => a.time - b.time);

                var table = '<table class="check-result"><tr><th>Server Name</th><th>Response (ms)</th><th>Random IP</th><th>Country Code</th><th>Flag</th></tr>';
                for (var i = 0; i < jsonData.length; i++) {
                    let cc = jsonData[i]['country_code'];
                    let time = jsonData[i]['time'];
                    let name = jsonData[i]['server'];
                    let ip = jsonData[i]['random-ip'];
                    let flag = '<img src="https://countryflag.org/flag-'+cc.toLowerCase()+'-48.png" />';
                    table += '<tr><td>' + name + '</td> <td style="text-align: center;" >' + time + '</td> <td>' + ip + '</td><td style="text-align: center;" >' + cc + '</td><td style="text-align: center;" class="ip2c-flag" >' + flag + '</td></tr>';
                }
                table += '</table>';                
                jQuery('#servers-health').html(table);
                jQuery('#check-status').html('DONE!');

                //jQuery('#servers-health').html(JSON.stringify(table));
            } catch (error) {
                console.log("Eroare: datele primite nu sunt un JSON valid.");
                jQuery('#servers-health').html("An error occurred: The data received is not a valid JSON.");
                jQuery('#check-status').html('FAIL!');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log("Error: " + textStatus, errorThrown);
            jQuery('#servers-health').html("An unknown error occurred when checking the servers: " + textStatus);
            jQuery('#check-status').html('ERROR!');
        }
    });
});
</script>