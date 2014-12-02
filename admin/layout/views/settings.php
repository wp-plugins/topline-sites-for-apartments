<?php 

/**
 * Settings page content
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

<div class="topline-body">


	<h1>Settings</h1>
	<form action="<?php echo admin_url('options-general.php?page=topline-plugin&view=settings'); ?>" method="POST">
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<label for="install_type">Install Type</label>
				</th>
				<td>
					<select name="install_type" id="install_type" <?php if($credentials['api_key'] && !startsWith($credentials['api_key'], 'U')) { echo 'disabled'; } ?>>
						<option <?php if($credentials['install_type'] == 'property') { echo 'selected'; } ?> value="property">Property</option>
						<option <?php if($credentials['install_type'] == 'company') { echo 'selected'; } ?> value="company">Company</option>
					</select>
						<?php if($credentials['api_key'] && !startsWith($credentials['api_key'], 'U')) { echo '<p class="description">The API Key you\'ve installed set this install type.</p>'; } ?>
				</td>
			</tr>
		</tbody>
	</table>
	<?php if($user) { ?>
	<h2>Topline Credentials</h2>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<label for="user_name">Name </label>
					</th>
					<td>
						<input name="user_name" type="text" id="user_name" disabled value="<?php echo $user['first_name']; ?> <?php echo $user['last_name']; ?>" class="regular-text ltr">
						<p class="description">Username: <strong><?php echo $user['username']; ?></strong></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="user_email">E-mail Address </label>
					</th>
					<td>
						<input name="user_email" type="email" id="user_email" disabled value="<?php echo $user['email']; ?>" class="regular-text ltr">
						<p class="description">This address is used for admin purposes, like feed downtime.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="api_key">API Key </label>
					</th>
					<td>
						<input name="api_key" type="text" id="api_key" disabled value="<?php echo $credentials['api_key']; ?>" class="regular-text ltr">
						<p class="description">Try to keep this secret. It gives access to special TopLine data.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="sync_rate">Sync Rate <span>(seconds)</span></label>
					</th>
					<td>
						<input name="sync_rate" type="text" id="sync_rate" value="<?php echo $credentials['sync_rate'] ?: '3600'; ?>" class="all-options ltr">
						<p class="description">This is how often we should pull availability feed data from TopLine.</p>
					</td>
				</tr>
			</tbody>
		</table>
	<?php } ?>
		<table>
			<tr>
				<th>
					<button type="submit" class="btn btn-primary btn-block"	>
			Save Changes
					</button>
				</th>
			</tr>
		</table>
	</form>
</div>

<?php

/**
 * end of Main page content
 */

?>