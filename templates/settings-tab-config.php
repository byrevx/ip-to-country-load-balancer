<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php 
    $frontend_enabled_ = empty($options['frontend-assets']) ? 0 : 1;
    $style_demo_enabled = empty($options['insert-demo']) ? 'background: auto;' : 'background: #ffffcc;';
    
    $frontend_enabled = (!$frontend_enabled_) ? 'disabled' : '';    
    
    $status_agree = (!$agree) ? 'class="ip2c-disabled"' : '';

    $backed_active = !empty($options['backend']);
    $frontend_active = !empty($options['frontend']);

    $backend_enabled = (!$backed_active) ? 'disabled' : '';
?>
<style>
    
</style>
        <table class="form-table">
        <tbody>

            <tr valign="top" >
                <th scope="row">I Agree to Privacy Policy</th>
                <td>
                    <label class="label-ckb" >
                        <input id="ip2c-agree" name="IP2clb_options[agree]" type="checkbox" value="1" <?php i_checked('1', $options,'agree'); ?> /> Yes
                    </label>
                    <span class="option-info" style="">Agree to the conditions <b>Privacy Policy</b> </span>
                    <hr />
                    <span>By using this plugin, you hereby consent to <a href="#tabs-6">Privacy Policy</a> and agree to its terms. <br />
                        Read more in tab/page <a href="#tabs-6">Privacy Policy</a>.</span>

                    <?php if (!$agree) : ?>
                    <hr />
                    <p class="important">If you want to use this plugin, you must agree to the Privacy Policy and agree to its terms.</p>                        
                    <?php endif; ?>
                </td>
            </tr>          
            <tr valign="top" <?php echo $status_agree; ?>>
                <th scope="row">API Servers</th>
                <td>
                    <div>
                        <label class="label-ckb">Free APi Servers &dArr;</label>
                        <span class="">Bandwidth ~ (rate/mo) &dArr;</span>
                        <hr />
                    </div>                    
                    <?php $i=0; foreach($BackEnd_Servers as $name=>$val) : 
                        $bw = round( ($val['rate_mo']*100)/$Sum_BackEnd, 1 ); 
                        $bwr = $bw+10;
                       
                        $style = 'background-image: linear-gradient(to right, #ff0000 0%, #ccf '.$bw.'%, #FFF '.$bwr.'%, #f0f0f1 100%);';
                        $style = byrev_bg_gradient($bw);
                       
                        $home = $val['home'];
                        $home = "<a href='$home' target='noopener noreferrer' >$name</a>";   

                        $privacy = !empty($val['privacy']) ? $val['privacy'] : $val['home'];

                        if ($privacy != '') {
                            $privacy = "<a class='ip2c-privacy' title='Privacy - Terms' href='$privacy' target='noopener noreferrer' > <span>&#8505;</span> </a>";  
                        }

                    ?>
                    <div id="backends-check">
                        <label class="label-ckb">                                                        
                            <input class="backend-check" name="IP2clb_options[backend][<?php echo $name; ?>]" type="checkbox" value="1" <?php i_checked(1, $options,'backend',$name); ?> />                         
                            <b><?php echo $home; ?> <?php echo $privacy; ?></b>                        
                        </label>
                        <span class="bw-srv" style="<?php echo $style; ?>"><?php echo $bw; ?>'%'</span>
                    </div>
                    <?php $i++; endforeach; ?>
                   
                    <button type="button" class="button-toggle" id="toggleSelect">Select All</button>
                    
                    <?php if(!$backed_active && $agree) : ?>
                    <hr />
                    <p class="important">In order for the plugin's functions to work as expected, it is necessary that at least one server be selected from the list.</p>
                    <?php endif; ?>

                </td>
            </tr>

            <tr valign="top" <?php echo $status_agree; ?>>
                <th scope="row">Frontend API Servers</th>
                <td>
                    <div>
                        <label class="label-ckb">Free APi Servers &dArr;</label>
                        <span class="">Bandwidth ~ (rate/mo) &dArr;</span>
                        <hr />
                    </div>  

                    <?php $i=0; foreach($FrontEnd_Servers as $name=>$val) : 
                        $bw = round( ($val['rate_mo']*100)/$Sum_FrontEnd, 1 ); 
                        $bwr = $bw+10;
                        $style = 'background-image: linear-gradient(to right, #ff0000 0%, #ccf '.$bw.'%, #FFF '.$bwr.'%, #f0f0f1 100%);';
                        $style = byrev_bg_gradient($bw);
                        $home = $val['home'];
                        $home = "<a href='$home' target='noopener noreferrer' >$name</a>";
                    ?>
                    <div>
                        <label class="label-ckb">                              
                            <input name="IP2clb_options[frontend][<?php echo $name; ?>]" type="checkbox" value="1" <?php i_checked(1, $options,'frontend',$name); ?> />                        
                            <b><?php echo $home; ?></b>                        
                        </label>
                        <span class="bw-srv" style="<?php echo $style; ?>"><?php echo $bw; ?>'%'</span>
                    </div>
                    <?php $i++; endforeach; ?>
                                     
                    <?php if(!$frontend_active && $agree && $frontend_enabled_ ) : ?>
                    <hr />
                    <p class="important">In order for the plugin's frontend functions to work as expected, it is necessary that at least one server be selected from the list.</p>
                    <?php endif; ?>

                </td>
            </tr>                 

            <tr valign="top" <?php echo $status_agree; ?> style="<?php echo $style_frontend_enabled; ?>">
                <th scope="row">FrontEnd Support</th>
                <td>
                    <label class="label-ckb" >
                        <input id="frontend-assets" name="IP2clb_options[frontend-assets]" type="checkbox" value="1" <?php i_checked('1', $options,'frontend-assets'); ?> /> Enable
                    </label>                    
                    <span class="option-info" style="">Add .JS file in Footer Template</span>
                    <hr />
                    <span>The request to the API server is made from the Browser using <b>Javascript</b>.</span>
                </td>
            </tr>                 

            <tr valign="top" <?php echo $status_agree; ?> >
                <th scope="row">Enable Built-In Test</th>
                <td>
                    <label class="label-ckb" >
                        <input <?php echo $backend_enabled; ?> id="ip2c-rts" name="IP2clb_options[rts]" type="checkbox" value="1" <?php i_checked('1', $options,'rts'); ?> /> Enable
                    </label>
                    <span class="option-info" style=""><b>Built-In Test</b> Config Menu</span>
                    <hr />
                    <span>The plugin configuration page will load a little slower. Do not use all the time.</span>
                </td>
            </tr>                      

            <tr valign="top" <?php echo $status_agree; ?> style="<?php echo $style_demo_enabled; ?>">
                <th scope="row">Insert DEMO CODE in website</th>
                <td>
                    <label class="label-ckb" >
                        <input <?php echo $frontend_enabled; ?> id="insert-demo" name="IP2clb_options[insert-demo]" type="checkbox" value="1" <?php i_checked('1', $options,'insert-demo'); ?> /> Enable
                    </label>
                    <span class="option-info" style="">Used only to test the functionality! </span>
                    <hr />
                    <span>It does not work on  <code>localhost</code> <i>(dev environment)</i> if the IP is not public!</span>
                    <br />
                    <span>In this case, the IP will be changed to a random one. (for test).</span>
                </td>
            </tr>                  

            <!-- Add more options as needed -->
                </tbody>
        </table>

        <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
        </p>    

<script>    

// Get the references to the checkbox elements
var frontendAssetsCheckbox = document.getElementById('frontend-assets');
var insertDemoCheckbox = document.getElementById('insert-demo');

// Add an event listener to the 'frontend assets' checkbox
frontendAssetsCheckbox.addEventListener('change', function() {
  let stat = (!frontendAssetsCheckbox.checked);
  insertDemoCheckbox.disabled = stat;
});

// select all, deselect all
var allSelected = false;

  document.getElementById('toggleSelect').addEventListener('click', function() {
    var checkboxes = document.querySelectorAll('#backends-check .backend-check');
    allSelected = !allSelected;

    for (var i = 0; i < checkboxes.length; i++) {
      checkboxes[i].checked = allSelected;
    }

    this.textContent = allSelected ? 'Deselect All' : 'Select All';
  });

</script>