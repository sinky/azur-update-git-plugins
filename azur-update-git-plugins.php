<?php 
/*
Plugin Name: Azur Update GIT Plugins
Plugin URI: https://github.com/sinky/
Version: 1.0
Author: Marco Krage
Author URI: https://my-azur.de
Description: Update my WP Plugins with GIT
*/

// If this file is called directly, then abort execution.
if ( ! defined( 'WPINC' ) ) {
	die( "Aren't you supposed to come here via WP-Admin?" );
}


add_action('admin_bar_menu', 'azur_update_git_plugins_admin_bar_menu', 100);
function azur_update_git_plugins_admin_bar_menu($admin_bar){
	$admin_bar->add_menu( array(
		'id'    => 'my-item',
		'title' => 'Update Azur GIT Plugins',
		'href'  => admin_url('admin-ajax.php?action=azur_update_git_plugins'),
		'meta'  => array(
			'target' => '_blank'
		)
	));
}


// Ajax
function azur_update_git_plugins() {
	if(!current_user_can('administrator')) {
		die('no no, admin plz!');
	}
	echo '<h1>Azur Update GIT Plugins</h1>';

	$plugins_url = plugins_url();


	$all_plugins = get_plugins();
	$all_plugins = array_keys($all_plugins);

	$azur_plugins = preg_grep("/^azur-/", $all_plugins);

	$azur_plugins = array_map(function($plugin){
		return array_shift(array_values(explode("/", $plugin)));	
	}, $azur_plugins);


	foreach($azur_plugins as $azur_plugin) {
		echo "<h2>$azur_plugin</h2>";
		echo '<pre>';
		chdir(WP_PLUGIN_DIR."/$azur_plugin");
		if(file_exists(".git")){
			passthru("git fetch --all");
			passthru("git reset --hard origin/master");
			echo(PHP_EOL);
		}else{
			echo "Not a git repository (.git not found)".PHP_EOL;
			echo(PHP_EOL);
		}
		echo '</pre>';
	}
	
	wp_die();
}
add_action( 'wp_ajax_azur_update_git_plugins', 'azur_update_git_plugins' );

