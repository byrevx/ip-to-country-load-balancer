<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php
    $usecase = $IP2clb->options_default['usecase'];
?>

<?php foreach ( $usecase as $case=>$data) : ?>
    <h2><?php echo $data['title']; ?></h2>
    <p class="description" >
        <pre class="use-case"><?php echo $data['desc']; ?></pre>
    </p>      
    <hr />          
<?php endforeach; ?>
