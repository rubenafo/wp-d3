<?php
add_action( 'admin_menu', 'wpd3_add_admin_menu' );
add_action( 'admin_init', 'wpd3_settings_init' );

function wpd3_add_admin_menu(  ) { 
	add_options_page( 'Wp-D3', 'Wp-D3', 'manage_options', 'Wp-D3', 'wpd3_options_page' );
}

function wpd3_settings_init(  ) { 
	register_setting( 'pluginPage', 'wpd3_settings' );
	add_settings_section(
		'wpd3_pluginPage_section', 
		__( 'Global Settings', 'Wp-D3' ), 
		'wpd3_settings_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'wpd3_version', 
		__( 'Active D3.js library', 'wpd3' ), 
		'wpd3_select_field_0_render', 
		'pluginPage', 
		'wpd3_pluginPage_section' 
	);
	
	add_settings_field( 
		'', 
		__( 'Wp-D3 Post and Pages', 'wpd3' ), 
		'wpd3_existing_charts_info_render', 
		'pluginPage',
		'wpd3_pluginPage_section' 
	);
}

function wpd3_existing_charts_info_render () {
	?>
	<div style="height:600px; width:400px; overflow:auto;align:left; float:left; padding-right:20px;border-right:1px solid white">
	<table cellspacing="0">
    <thead>
   		<th id="" style="text-align:center">Post Title</th>
      <th id="" style="text-align:center">Chart ID</th>
      <th id=""> </th>
    </thead>

    <tbody >
		<?php
    	$count = 0;
		$alternateRow = false;
    	$custom_posts = query_posts("showposts=-1");
		$nonce = wp_create_nonce("wpd3-nonce");
    	foreach ($custom_posts as $post) {
				$keys = get_post_custom_keys($post->ID);
				foreach ((array) $keys as $key) {
					if (preg_match("/wpd3-/", $key)) {
						if ($alternateRow == true) { 
						?>
							<tr class="alternate" style="text-align:center">
						<?php
							$alternateRow = false;
						}
						else {
						?>
							<tr style="text-align:center">
						<?php
							$alternateRow = true;
						}
						?>
						<td> <?php echo(get_the_title($post->ID));?></td>
						<td> <?php echo($key);?></td>
						<td>
							<a class="button button-secondary" value ="" target="_blank" href="admin-ajax.php?action=previewContent&postId=<?php echo($post->ID);?>&editor=<?php echo($key);?>&security=<?php echo $nonce; ?>">Preview</a>
						</td>
						</tr>
          <?php $count++;
				}
      }
    }?>
		</tbody>
		</table>
		</div>

		<div style="height:600px; width:400px; overflow:auto;align:left; padding-left:20px;border-right:1px solid white">
	<table cellspacing="0">
    <thead>
   		<th id="" style="text-align:center">Page Title</th>
      <th id="" style="text-align:center">Chart ID</th>
      <th id=""> </th>
    </thead>

    <tbody>
	 
		<?php
    	$count = 0;
			$alternateRow = false;
    	$all_pages = get_all_page_ids();
    	foreach ($all_pages as $page) {
				$keys = get_post_custom_keys($page);
				foreach ($keys as $key) {
					if (preg_match("/wpd3-/", $key)) {
						if ($alternateRow == true) { 
						?>
							<tr class="alternate" style="text-align:center">
						<?php
							$alternateRow = false;
						}
						else {
						?>
							<tr style="text-align:center">
						<?php
							$alternateRow = true;
						}
						?>
						<td> <?php echo(get_the_title($page));?></td>
						<td> <?php echo($key);?></td>
						<td>
							<a class="button button-secondary" value ="" target="_blank" href="admin-ajax.php?action=previewContent&postId=<?php echo($page);?>&editor=<?php echo($key);?>&security=<?php echo $nonce; ?>">Preview</a>
						</td>
						</tr>
          <?php $count++;
				}
      }
    }?>
		</tbody>
		</table>
		</div>


		<?php	
}

function wpd3_select_field_0_render(  ) { 

	$options = get_option( 'wpd3_settings' );
	?>
	<select name='wpd3_settings[wpd3_version]'>
		<option value='1' <?php selected( $options['wpd3_version'], 1); ?>>D3 v3.5</option>
		<option value='2' <?php selected( $options['wpd3_version'], 2); ?>>D3 v4.5</option>
	</select>
	<?php
}

function wpd3_settings_section_callback(  ) { 
	echo __( 'In this page you can change the D3.js library in use and preview the published posts that contain Wp-D3 charts', 'Wp-D3' );
}

function wpd3_options_page(  ) { 
	?>
	<form action='options.php' method='post'>
		<h2>Wp-D3</h2>
		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>
	</form>
	<?php
}
?>
