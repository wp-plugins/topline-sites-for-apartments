<?php 

/**
 * Company page content
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
	<h3>QuickLinks</h3>
	<a href="<?php echo admin_url('options-general.php?page=topline-plugin&view=properties&action=add_new_prop'); ?>">Add Property</a>

</div>


<div class="topline-body with-side">


	<h1>Company</h1>
	<?php if($user) { ?>
		<p>If using company API code this page should show list of properties available to install.</p>
	<?php } ?>

	<form action="<?php echo admin_url('options-general.php?page=topline-plugin&view=company'); ?>" method="POST">
	<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<label for="company_name">Name </label>
					</th>
					<td>
						<input name="company_name" type="text" id="company_name" value="<?php echo $company['company_name']; ?>" placeholder="The Lines at 30" class="regular-text ltr">
						<p class="description">What you call your grouping of properties or your <strong>Corporate company name.</strong></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="company_email">E-mail Address </label>
					</th>
					<td>
						<input name="company_email" type="email" id="company_email" value="<?php echo $company['company_email']; ?>" placeholder="company@30lines.com" class="regular-text ltr">
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="company_phone">Phone Number </label>
					</th>
					<td>
						<input name="company_phone" type="phone" id="company_phone" value="<?php echo $company['company_phone']; ?>" placeholder="(800) 555-5555" class="regular-text ltr">
					</td>
				</tr>
				<tr>
				<th scope="row">Address</th>
				<td><fieldset><legend class="screen-reader-text"><span>Address</span></legend>
				<label for="company_street">Street</label>
				<input name="company_street" type="text" id="company_street" value="<?php echo $company['company_street']; ?>" placeholder="52 E. Lynn St." class="large-text"><br>
				<div class="city-text">
					<label for="company_city">City</label><br />
					<input name="company_city" type="text" id="company_city" value="<?php echo $company['company_city']; ?>" placeholder="Columbus" class="medium-text">
				</div>
				<div class="state-text">
					<label for="company_state">State</label><br />
					<input name="company_state" type="text" id="company_state" value="<?php echo $company['company_state']; ?>" placeholder="OH" class="medium-text">
				</div>
				<div class="zip-text">
					<label for="company_zipcode">Zipcode</label><br />
					<input name="company_zipcode" type="text" id="company_zipcode" value="<?php echo $company['company_zipcode']; ?>" placeholder="43215" class="medium-text">
				</div>
				</fieldset></td>
				</tr>
				<tr>
					<th><h2>Social Media</h2></th>
				</tr>
				<tr>
					<th scope="row">
						<label for="company_twitter">Twitter </label>
					</th>
					<td>
						<input name="company_twitter" type="text" id="company_twitter" value="<?php echo $company['company_twitter']; ?>" placeholder="http://twitter.com/30lines" class="regular-text ltr">
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="company_facebook">Facebook </label>
					</th>
					<td>
						<input name="company_facebook" type="text" id="company_facebook" value="<?php echo $company['company_facebook']; ?>" placeholder="http://facebook.com/30lines" class="regular-text ltr">
					</td>
				</tr>
			</tbody>
		</table>
		<table>
			<tr>
				<th>
					<button type="submit" class="btn btn-primary btn-block"	>
			Save Changes
					</button>
				</th>
			</tr>
		</table>
	


</div>

<?php

/**
 * end of Main page content
 */

?>