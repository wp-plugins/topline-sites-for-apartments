<?php 

/**
 * Welcome page content
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://30lines.com
 * @since      1.0.0
 *
 * @package    Topline_Plugin
 * @subpackage Topline_Plugin/admin/layout/views
 */

?>

<div class="topline-side">

	<div class="quick-connect">

		<form class="form account-form" action="<?php echo admin_url('options-general.php?page=topline-plugin&view=welcome'); ?>" method="POST">
			
			<?php if($status){ ?>
				<p class="topline-form-error"><?php echo $status; ?></p>
			<?php } ?>

        <div class="form-group">
          <label for="username" class="placeholder-hidden">Username</label>
          <input type="text" class="form-control" name="username" id="username" placeholder="Username" tabindex="1">
        </div> <!-- /.form-group -->

        <div class="form-group">
          <label for="apikey" class="placeholder-hidden">API Key</label>
          <input type="text" class="form-control" id="apikey" name="apikey" placeholder="API Key" tabindex="2">
        </div> <!-- /.form-group -->


        <div class="form-group">
          <button type="submit" class="btn btn-primary btn-block btn-lg" tabindex="4">
            Login &nbsp; <i class="fa fa-play-circle"></i>
          </button>
        </div> <!-- /.form-group -->

      </form>
	</div>


</div>

<?php if(!$credentials['install_type']) { ?>
<div class="topline-body with-side">


	<h1>Welcome to TopLine</h1>

	<p>Your premiere Multifamily / Property Integration &amp; Lead Management System Plugin for WordPress. We will guide you from integration and implementation to advanced lead nurturing campaigns.</p>
	

	<h2 class="tagline">Lead Nurturing</h2>
	<div class="timeline">
		<img src="<?php echo plugins_url( 'img/timeline.png', dirname(dirname(__FILE__) )); ?>" alt="TopLine">
	</div>


</div>
<?php } else { ?>
	<h1>Add Your Topline Credentials</h1>
	
<?php } ?>


<?php

/**
 * end of Welcome page content
 */ 

?>