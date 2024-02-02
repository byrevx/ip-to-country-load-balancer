<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php    
    global $IP2clb;

    $BackEnd_Servers = $IP2clb->getBackEnd_Servers();
    $FrontEnd_Servers = $IP2clb->getFrontEnd_Servers();
    $Sum_BackEnd = $IP2clb->_Sum_BackEnd;
    $Sum_FrontEnd = $IP2clb->_Sum_FrontEnd;
        
    $options = get_option('IP2clb_options', 'missing');

    // Set Options if missing - 1st run
    if ($options == 'missing') {
        update_option( 'IP2clb_options', $IP2clb->options_default);      
        $options = get_option('IP2clb_options' );
    }    

    // Check Real Time Test TAB in Config Menu
    $rts_enabled = !empty($options['rts']);

    // We check if the user of the plugin has agreed with the terms and conditions
    $agree = !empty($options['agree']);
?>

<style>
<?php include('style.css'); ?>
</style>

<div class="wrap">
    <h2><?php echo __BYREV_IP2CLB_NAME; ?></h2>
    <form method="post" action="options.php">
        <?php settings_fields('IP2clb_options'); ?>
        <?php $options = get_option('IP2clb_options'); ?>
                     
        <div id="tabs-ip2c">
            <ul>        
                <li><a href="#tabs-1">Config</a></li>       
                <?php if ($agree): ?>         
                    <li><a href="#tabs-2">Shortcode</a></li>            
                    <?php if ($rts_enabled && $agree) : ?>
                        <li><a href="#tabs-3">Built-In Test</a></li>
                    <?php endif; ?>                
                    <li><a href="#tabs-4">Servers Health</a></li>                                                                                       
                    <li><a href="#tabs-5">UseCase</a></li>       
                <?php endif; ?>     
                <li><a href="#tabs-6">Privacy Policy</a></li>                            
            </ul>
            <div class="wrap">
                
                <div id="tabs-1" class="tabs-i2pc">
                    <?php include('settings-tab-config.php'); ?>                     
                </div>
                
                <div id="tabs-2" class="tabs-i2pc" style="display: none;">
                    <?php include('settings-tab-shortcode.php'); ?>                     
                </div>             

                <?php if ($rts_enabled) : ?>
                <div id="tabs-3" class="tabs-i2pc" style="display: none;">
                    <?php include('settings-tab-test.php'); ?>
                </div>  
                <?php endif; ?>

                <div id="tabs-4" class="tabs-i2pc" style="display: none;">
                    <?php include('settings-tab-server-health.php'); ?>                     
                </div>                       

                <div id="tabs-5" class="tabs-i2pc" style="display: none;">
                    <?php include('settings-tab-usecase.php'); ?>                     
                </div>            

                <div id="tabs-6" class="tabs-i2pc" style="display: none;">
                    <?php include('settings-tab-privacy.php'); ?>                     
                </div>                     
            </div>
        </div>


    </form>
</div>

<script>

(function($) {
    $(document).ready(function() {
        $( "#tabs-ip2c" ).tabs();
        
        $("a[href=#tabs-6]").click(function() {
            var index = $('#tabs-ip2c a[href="#tabs-6"]').parent().index();            
            $("#tabs-ip2c").tabs("option", "active", index);
        });

    });
})(jQuery);

</script>

<?php

