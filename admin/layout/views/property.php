<?php 

/**
 * Property page content
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


	<h1><span class="solo-prop-heading">Property</span> <?php echo ' - ' . $property['name']; ?> <a href="#" class="topline-edit-link" data-toggle-off="property-display" data-toggle-on="property-edit"><i class="fa fa-pencil-square-o"></i></a></h1>
	
	<?php 
	if($property) {

		$fullAddress = $property['address']['address_line1'] . '<br>' . $property['address']['city'] . ' ' . $property['address']['state'] . ' ' . $property['address']['zip'];
		?>
		<div id="map">
		</div>
		<script>
			var maplocation = { property: "<?php echo $property['name']; ?>", address: "<?php echo $fullAddress;?>", lat: <?php echo $property['latitude']; ?>, lng: <?php echo $property['longitude']; ?> }
		</script>
		<div class="topline-well" style="overflow:auto;">
			
		<form action="<?php echo admin_url('options-general.php?page=topline-plugin&view=property'); ?>" method="POST">
			<div class="property-edit" style="display:none;">
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row">Address</th>
							<td>
								<fieldset><legend class="screen-reader-text"><span>Address</span></legend>
								<label for="address_line1">Street</label>
								<input name="address_line1" type="text" id="address_line1" value="<?php echo $property['address']['address_line1']; ?>" placeholder="52 E. Lynn St." class="large-text"><br>
								<div class="city-text">
									<label for="address_city">City</label><br />
									<input name="address_city" type="text" id="address_city" value="<?php echo $property['address']['city']; ?>" placeholder="Columbus" class="medium-text">
								</div>
								<div class="state-text">
									<label for="address_state">State</label><br />
									<input name="address_state" type="text" id="address_state" value="<?php echo $property['address']['state']; ?>" placeholder="OH" class="medium-text">
								</div>
								<div class="zip-text">
									<label for="address_zip">Zip</label><br />
									<input name="address_zip" type="text" id="address_zip" value="<?php echo $property['address']['zip']; ?>" placeholder="43215" class="medium-text">
								</div>
								</fieldset>
							</td>
						</tr>
						<tr>
							<th scope="row" colspan="2">
								<h3>Contact</h3>
							</th>
						</tr>
						<tr>
							<th scope="row">
								<label for="property_phone">Phone </label>
							</th>
							<td>
								<input name="property_phone" type="text" id="property_phone" value="<?php echo $property['phone']; ?>" class="regular-text ltr">
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="property_email">Email </label>
							</th>
							<td>
								<input name="property_email" type="text" id="property_email" value="<?php echo $property['email']; ?>" class="regular-text ltr">
							</td>
						</tr>
						<tr>
							<th scope="row" colspan="2">
								<label for="property_description"><h3>Description</h3> </label>
							</th>
						</tr>
						<tr>
							<td colspan="2">
								<textarea id="property_description" class="full-textarea" name="property_description"><?php echo $property['description'];?></textarea>
							</td>
						</tr>
					</tbody>
				</table>
				<button type="submit" class="property-btn btn btn-primary btn-block"	>
					Save Changes
				</button>
			</div>
	</form>

			<div class="property-display">
				<div class="property-info phone" style="width:20%;float:left;">
					<h5>Address</h5>
					<p style="margin-top:0;"><?php echo $fullAddress; ?></p>
				</div>
			
				<div class="property-info contact" style="width:20%;float:left;margin:0 2.5%">
					<h5>Contact</h5>
					<p style="margin-top:0;">
						<?php echo $property['email']; ?><br>
						<?php echo $property['phone']; ?></p>
				</div>
				<div class="property-desc">
					<h5>Description</h5>
					<p style="margin-top:0;"><?php echo preg_replace('/[\x00-\x1F\x80-\xFF]/', '', html_entity_decode($property['description'])); ?></p>
				</div>
			</div>
				
		</div>
		<h1 style="clear:right;">Floor plans: <span class="subview-icons">
			<a href="#" class="list-view active"><i class="fa fa-list-alt"></i></a> 
			<a href="#" class="grid-view"><i class="fa fa-th"></i></a></span>
		</h1>
		<div class="floorplans">
		<div class="floorplans-list-view">
		<h2>Table View</h2>
		<?php //var_dump($floorplans); exit;?>
		<table class="table table-striped table-bordered" id="floorplans-table">
		<thead>
			<tr>
				<th style="width: 5%"># id</th>
				<th style="width: 18%">Name</th>
				<th style="width: 8%">Beds</th>
				<th style="width: 8%">Baths</th>
				<th style="width: 10%">Sq. Ft.</th>
				<th style="width: 14%">Min. Rent</th>
				<th style="width: 6%">Available?</th>
				<th style="width: 6%">Updated</th>
			</tr>
		</thead>
		<tbody>

		<?php
		foreach($floorplans as $floorplan) { ?>
			<tr>
				<td><?php echo $floorplan['id']; ?></td>
				<td><a href="#"><?php echo $floorplan['name']; ?></a></td>
				<td><?php echo $floorplan['bedrooms']; ?></td>
				<td><?php echo $floorplan['bathrooms']; ?></td>
				<td><?php echo $floorplan['squarefeet']['min']; ?></td>
				<td><?php echo '$' . $floorplan['rent']['min']; ?></td>
				<td><?php if($floorplan['available']) { echo '<i class="fa fa-check-square-o"></i> Yes'; } else { echo '<i class="fa fa-square-o"></i> No'; } ?></td>
				<td><?php echo date('m/d/Y'); ?></td>
			</tr>
		<?php
		}
		?>	

		</tbody>
		<tfoot>
			<tr>
				<th># id</th>
				<th>Name</th>
				<th>Beds</th>
				<th>Baths</th>
				<th>Sq. Ft.</th>
				<th>Min. Rent</th>
				<th>Connected?</th>
				<th>Updated</th>
			</tr>
		</tfoot>
	</table>
	</div>

	<div class="floorplans-grid-view">	
		<h2>Grid View</h2>
		<?php
		foreach($floorplans as $floorplan) {
			?>
			<div class="floorplan" style="background-image:url(<?php echo $floorplan['images'][0]; ?>);">
				<h3><a href="#"><?php echo $floorplan['name'];?></a></h3>
			</div>
			<?php
		}
		?>
		</div>
		<?php 
		} 
	
	?>
	</div>
	
</div>

<?php

/**
 * end of Main page content
 */

?>