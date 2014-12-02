<div class="top-bar">
	<a href="<?php echo site_url(); ?>/wp-admin/options-general.php?page=topline-plugin&view=dashboard" class="navbar-brand navbar-brand-img">
		<img src="<?php echo plugins_url( '../img/logo-new.png', dirname(__FILE__) ); ?>" alt="TopLine">
	</a>
	<?php if($user) { ?>
	<ul class="nav navbar-nav navbar-right">    

		<li class="dropdown navbar-profile">
			<a class="dropdown-toggle" data-toggle="dropdown" href="javascript:;">
				<img src="<?php echo $user['avatar']; ?>" class="navbar-profile-avatar" alt="<?php echo $user['first_name']; ?>">
				<span class="navbar-profile-label"><?php echo $user['email']; ?> &nbsp;</span>
				<i class="fa fa-caret-down"></i>
			</a>

			<ul class="dropdown-menu" role="menu">

			<!-- 	<li>
					<a href="#" class="disabled">
						<i class="fa fa-user"></i> 
						&nbsp;&nbsp;My Profile
					</a>
				</li>

				<li>
					<a href="#" class="disabled">
						Plans &amp; Billing
					</a>
				</li> -->

				<li>
					<a href="<?php echo admin_url('options-general.php?page=topline-plugin&view=settings'); ?>">
						Settings
					</a>
				</li>

				<li class="divider"></li>
				<li><strong style="margin:0 0.5em;">TopLine: <?php echo $user['username']; ?></strong></li>
				<li>
					<a href="<?php echo admin_url('options-general.php?page=topline-plugin&remove_credentials=true'); ?>" class="remove-credentials">
						<i class="fa fa-sign-out"></i> 
						&nbsp;&nbsp;Remove Credentials
					</a>
				</li>

			</ul>

		</li>

	</ul>
	<?php } else if($view != 'welcome') { ?>
		<ul class="nav navbar-nav navbar-right">    

		<li class="dropdown navbar-profile">
			<a class="dropdown-toggle" data-toggle="dropdown" href="javascript:;">
				<i class="fa fa-2x fa-cog" style="position:relative;top:4px;"></i>
			</a>

			<ul class="dropdown-menu" role="menu">

			<!-- 	<li>
					<a href="#" class="disabled">
						<i class="fa fa-user"></i> 
						&nbsp;&nbsp;My Profile
					</a>
				</li>

				<li>
					<a href="#" class="disabled">
						Plans &amp; Billing
					</a>
				</li> -->

				<li>
					<a href="<?php echo admin_url('options-general.php?page=topline-plugin&view=settings'); ?>">
						Settings
					</a>
				</li>

				<li class="divider"></li>

				<li>
					<a href="<?php echo admin_url('options-general.php?page=topline-plugin&view=welcome'); ?>" class="add-credentials">
						<i class="fa fa-sign-in"></i> 
						&nbsp;&nbsp;Add Credentials
					</a>
				</li>
				

			</ul>

		</li>

	</ul>
	<?php } ?>

	<ul class="nav navbar-nav navbar-right content-nav">
	<?php if($credentials['install_type'] === 'property') { ?>
		<li <?php if($view == 'property') { echo 'class="open"'; } ?>>
			<a href="<?php echo admin_url('options-general.php?page=topline-plugin&view=property'); ?>">
				Property
			</a>
		</li>
	
	<?php } else if($credentials['install_type'] === 'company') { ?>
		<li <?php if($view == 'company') { echo 'class="open"'; } ?>>
			<a href="<?php echo admin_url('options-general.php?page=topline-plugin&view=company'); ?>">
				Company
			</a>
		</li>
		<li <?php if($view == 'properties') { echo 'class="open"'; } ?>>
			<a href="<?php echo admin_url('options-general.php?page=topline-plugin&view=properties'); ?>">
				Properties
			</a>
		</li>
	<?php } ?>
	</ul>
</div>