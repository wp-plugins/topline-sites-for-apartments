<?php get_header(); 

	wp_enqueue_style('topline-floorplan', plugins_url() . '/topline/admin/css/topline-floorplan.css', array(), '1.0.0' );	

?>
<div class="topline main-content has-sidebar">
	<?php
		global $post;
	?>
	<div id="content" class="site-content" role="main">
		<article class="page floor-plan-template single-floor-plan floor-plan-overview">
			<h2><?php the_title();?></h2>

			
				<?php 

					$floorplan_data = get_post_meta($post->ID);

					$floorplan_content .= '<ul class="topline floor-plan plan-' . $floorplan_data['fp_unique_id'][0] . '">';
					$floorplan_content .= 	'<li class="topline floor-plan-info bedrooms">' . $floorplan_data['fp_bedrooms'][0] . ' Bed</li>';
					$floorplan_content .= 	'<li class="topline floor-plan-info bathrooms">' . $floorplan_data['fp_bathrooms'][0] . ' Bath</li>';

					if($floorplan_data['fp_sqft_min'][0] === $floorplan_data['fp_sqft_max'][0] ) {
						$floorplan_content .= 	'<li class="topline floor-plan-info sqft-range">Square Feet: ' . $floorplan_data['fp_sqft_min'][0] . '</li>';
					} else {
						$floorplan_content .= 	'<li class="topline floor-plan-info sqft-range">Square Feet: ' . $floorplan_data['fp_sqft_min'][0] . ' - ' . $floorplan_data['fp_sqft_max'][0] . '</li>';
					}

					if($floorplan_data['fp_rent_min'][0] === $floorplan_data['fp_rent_max'][0]) {
						$floorplan_content .= 	'<li class="topline floor-plan-info rent-range">Rent: $' . $floorplan_data['fp_rent_min'][0] . '</li>';
					} else {
						$floorplan_content .= 	'<li class="topline floor-plan-info rent-range">Rent: $' . $floorplan_data['fp_rent_min'][0] . ' - ' . $floorplan_data['fp_rent_max'][0] . '</li>';
					}
					
					$availability = $floorplan_data['fp_available'][0];
					if($availability != '' && $availability != '1') {
						$floorplan_content .= 	'<li class="topline floorplan-info availability date-available">' . $floorplan_data['fp_available'][0] . '</li>';
					} elseif($availability === '1') {
						$floorplan_content .= 	'<li class="topline floorplan-info availability available">Currently Available</li>';
					} else {
						$floorplan_content .= 	'<li class="topline floorplan-info availability not-available">Not Available</li>';
					}
					
					
					$floorplan_content .= '</ul>'; 

					$floorplan_images = get_post_meta($post->ID, 'fp_images', true);
					$floorplan_images = unserialize($floorplan_images);

					$floorplan_content .= '<ul class="topline floor-plan-images">';
					foreach($floorplan_images as $image_src) {
						$floorplan_content .= 	'<li class="topline image"><a href="' . get_permalink() . '"><img src="' . $image_src . '" /></a></li>';
					}
					$floorplan_content .= '</ul>';

					echo $floorplan_content;

				?>

		
		</article>

	</div>

<div id="secondary">
<?php get_sidebar(); ?>
</div>

</div>
			
<?php get_footer(); ?>
