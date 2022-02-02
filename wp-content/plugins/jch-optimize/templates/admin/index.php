<?php

use JchOptimize\Awf\Document\Document;

/** @var Document $this */
$tab = $this->container->input->get('tab', 'main');
$appName = JCH_PRO ? 'JCH Optimize Pro' : 'JCH Optimize';
?>

<div class="wrap">
    <h1><?php echo $appName; ?> Settings</h1>
    <nav class="nav-tab-wrapper">
        <a href="?page=jch_optimize" class="nav-tab<?php echo $tab == 'main' ? ' nav-tab-active' : ''; ?>">
                <?php _e( 'Dashboard', 'jch-optimize' ); ?>
        </a>
        <a href="?page=jch_optimize&tab=optimizeimages" class="nav-tab<?php echo $tab == 'optimizeimages' ? ' nav-tab-active' : ''; ?>">
                <?php _e( 'Optimize Images', 'jch-optimize' ); ?>
        </a>
        <a href="?page=jch_optimize&tab=configurations" class="nav-tab<?php echo $tab == 'configurations' ? ' nav-tab-active' : ''; ?>">
                <?php _e( 'Configurations', 'jch-optimize' ); ?>
        </a>
        <a href="?page=jch_optimize&tab=help" class="nav-tab<?php echo $tab == 'help' ? ' nav-tab-active' : ''; ?>">
                <?php _e( 'Help', 'jch-optimize' ); ?>
        </a>
    </nav>
    <div class="tab-content pt-5">
	    <?php echo $this->getBuffer(); ?>
    </div>
</div>
