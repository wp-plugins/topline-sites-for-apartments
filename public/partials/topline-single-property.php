<?php get_header(); ?>

	<?php 
	$unique_prop_id 	= get_post_meta( $post->ID, 'unique_prop_id', true);
	$officehours 		= get_post_meta( $post->ID, 'prop_officehours', true );
	$amenities 			= get_post_meta( $post->ID, 'prop_amenities', true );
	$floorplans 		= get_post_meta( $post->ID, 'prop_floorplans', true );
	$providerType 		= get_post_meta( $post->ID, 'provider_type', true);
	$website 			= get_post_meta( $post->ID, 'prop_website', true );
	$addr_line_1 		= get_post_meta( $post->ID, 'prop_addr_line_1', true );
	$city 				= get_post_meta( $post->ID, 'prop_city', true );
	$state 				= get_post_meta( $post->ID, 'prop_state', true );
	$zip 				= get_post_meta( $post->ID, 'prop_zip', true );
	$phone 				= get_post_meta( $post->ID, 'prop_phone', true );
	$email 				= get_post_meta( $post->ID, 'prop_email', true );


	$tagline 			= get_post_meta( $post->ID, 'prop_tagline', true );
			
	
	$officehours = unserialize($officehours); 
	$floorplans = unserialize($floorplans); 
	$amenities = unserialize($amenities); 
 

	?>

    <h1><?php the_title();?></h1>
    
    <div class="contact">
		<p class="address">
			<?php echo $addr_line_1; ?>
			<br>
			<?php echo $city;?>, <?php echo $state;?> <?php echo $zip;?>
		</p>
     
        <p class="phone"><?php echo $phone;?> </p>
        <?php if($website) { ?>
		<p class="website"><a href="<?php echo $website;?>">Website</a></p>
        <?php } ?>                    
   </div>
 	<div class="images-attached">
	<?php
	$images =& get_children( 'post_type=attachment&post_mime_type=image&post_parent=' . $post->ID );

	foreach ( $images as $attachment_id => $attachment ): 
		$image = wp_get_attachment_image_src($attachment_id, 'full', false); ?>
		<img src="<?php echo $image[0]; ?>">
	<?php
	endforeach;
	?>
 	</div>

	<div class="main">
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			<section class="entry-content clearfix" itemprop="articleBody">
				<div class="content sub-module">
					<h2 class="tagline"><?php echo $tagline;?></h2>
					<?php the_content(); ?>
				</div>


				<div class="amenities sub-module" id="amenities_view" style="display: none;">
					<h2 class="tagline">Features &amp; Amenities </h2>
					<?php
					foreach($amenities as $feature) { 
						echo $feature . '<br>';
					}
					?>
				</div>

				<div class="floor_view sub-module" id="floor_view" style="display:none;">
					<ul class="floor_plans">
						<?php
						foreach($floorplans as $floorplan) { ?>
							<li>
								<h2><?php echo $floorplan['name']; ?></h2>
								<div>
									<a class="fp-image" href="<?php echo $floorplan['images'][0]; ?>" data-colorbox title="<?php echo $floorplan['name']; ?>, <?php echo $floorplan['bedrooms']; ?> Bed &amp; <?php echo $floorplan['bathrooms']; ?> Bath">
										<img src="<?php echo $floorplan['images'][0]; ?>" />
									</a>
									<table>
										<tbody>
											<tr class="titles">
												<td>BEDS</td><td>BATHS</td><td>RENT</td><td>SQFT</td><td class="last">AVAILABILITY</td>
											</tr>
											<tr>
												<td><?php if($floorplan['bedrooms'] == 0) { echo 'Studio'; } else { echo $floorplan['bedrooms']; } ?></td><td><?php echo $floorplan['bathrooms']; ?></td>
												<?php if($floorplan['rent']['min'] == $floorplan['rent']['max']) { ?>
													<td>$<?php echo $floorplan['rent']['min']; ?></td>
												<?php } else { ?>
												<td>$<?php echo $floorplan['rent']['min']; ?> - $<?php echo $floorplan['rent']['max']; ?></td>
												<?php } ?>

												<?php if($floorplan['squarefeet']['min'] == $floorplan['squarefeet']['max']) { ?>
													<td><?php echo $floorplan['squarefeet']['min']; ?></td>
												<?php } else { ?>
												<td><?php echo $floorplan['squarefeet']['min']; ?> up to <?php echo $floorplan['squarefeet']['max']; ?></td>
												<?php } ?>
												<td><a href="http://units.realtydatatrust.com/unitavailability.aspx?ils=6148&fid=<?php echo $floorplan['id']; ?>" target="_blank" class="availability"><?php echo (($floorplan['available']) ? 'Availability' : 'Join Waitlist'); ?></a></td>
											</tr>
										</tbody>
									</table>
								</div>
							</li>
						<?php
						} ?>
					</ul>
				</div>
			</section>
		<?php endwhile; else : ?>
			<p>Uh Oh. Something is missing. Try double checking things.</p>
		<?php endif; ?>
	
	</div>


<div class="sidebar">

		<?php get_sidebar(); ?>

</div>

			
<?php get_footer(); ?>
