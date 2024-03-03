<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<div id="ip2c-privacy">
    <h3># Privacy Policy</h3>

    <h4>## Introduction</h4>

    <p>Our plugin ("we", "our", or "mine") operates the API services provided by third parties. This page informs you of our policies regarding the collection, use, and disclosure of personal data when you use our plugin and the choices you have associated with that data.</p>

    <h4>## Use of External Services</h4>

    <p>This plugin offers API services provided by third parties. These external services are not under our control, and we cannot guarantee the quality, safety, or reliability of these services. We also cannot guarantee how these services use, store, or disclose your data.</p>

    <p>By using our plugin, you acknowledge and agree that we are not responsible for any loss or damage of any sort that you may suffer as a result of using these external services. We strongly recommend that you review the privacy policies of these third-party services before using them.</p>

    <h4>## Consent</h4>

    <p>By using our plugin, you hereby consent to our Privacy Policy and agree to its terms. Before you can use our plugin, you will be asked to agree explicitly to the conditions in this Privacy Policy through a checkbox. If you do not agree with any part of this policy, please do not use our plugin.</p>
    <h4>## Third parties Privacy Policy and Terms</h4>
    
    <?php 
        $i=0; foreach($BackEnd_Servers as $name=>$val) : 
        $privacy = !empty($val['privacy']) ? $val['privacy'] : $val['home'];

        if ($privacy != '') {
            $privacy = "<a class='ip2c-privacy' title='Privacy - Terms' href='$privacy' target='noopener noreferrer' > $privacy </a> <br />";  
        }        

        ?>  <b><?php echo $privacy; ?></b> <?php

    endforeach; 
    ?>
    <b>Using Flags:</b>
    <a class='ip2c-privacy' title='Privacy - Terms' href='https://countryflag.org/privacy.html' target='noopener noreferrer' > https://countryflag.org/privacy.html </a> <br />
    
   
    <hr />

    <h4>## Changes to This Privacy Policy</h4>

    <p>We may update our Privacy Policy from time to time. Thus, we advise you to review this page periodically for any changes. These changes are effective immediately after they are posted on this page.</p>

    <h4>## Contact Us</h4>

    <p>If you have any questions or suggestions about our Privacy Policy, do not hesitate to contact us at byrev@yahoo.com .</p>
    <p><i>Regards, Emilian Robert Vicol</i></p>
</div>
<?php