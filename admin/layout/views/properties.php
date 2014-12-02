<?php 

/**
 * Properties page content
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://30lines.com
 * @since      1.0.0
 *
 * @package    Topline_Plugin
 * @subpackage Topline_Plugin/admin/layout/views
 */

$properties = new WP_Query([
	'post_type' => 'thirty_lines_prop'
	]);

?>

<div class="topline-body">


	<h1>Properties</h1>
	
	<table class="table table-striped table-bordered" id="properties-table">
		<thead>
			<tr>
				<th style="width: 5%"># id</th>
				<th style="width: 45%">Name</th>
				<th style="width: 10%"># Floor plans</th>
				<th style="width: 5%">Connected?</th>
				<th style="width: 25%">Updated</th>
			</tr>
		</thead>
		<tbody>
		<?php while ( $properties->have_posts() ) { 
			$properties->the_post();

			$floor_plans = get_children(['post_parent' => get_the_id()]);
			?>
			<tr>
				<td><?php echo the_id(); ?></td>
				<td><a href="<?php echo get_edit_post_link(); ?>"><?php echo the_title(); ?></a></td>
				<td><?php echo count($floor_plans); ?></td>
				<td><?php if(false) { echo '<i class="fa fa-check-square-o"></i>'; } else { echo '<i class="fa fa-square-o"></i>'; } ?></td>
				<td><?php echo the_modified_date('m/d/Y'); ?></td>
			</tr>
		<?php }

		wp_reset_postdata(); ?>
		
		</tbody>
		<tfoot>
			<tr>
				<th># id</th>
				<th>Name</th>
				<th># Floor plans</th>
				<th>Connected?</th>
				<th>Updated</th>
			</tr>
		</tfoot>
	</table>

</div>

<?php

/**
 * end of Main page content
 */

?>