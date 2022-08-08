<?php
/**
 * PLUGIN NAME REPLACE ME
 *
 * Plugin Name:       PLUGIN NAME REPLACE ME
 * Plugin URI:        PLUGIN URL REPLACE ME
 * Description:       PLUGIN DESCRIPTION REPLACE ME
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      8.1
 * Author:            AUTHOR NAME REPLACE ME
 * Author URI:        AUTHOR URL REPLACE ME
 * Text Domain:       plugin-name-replace-me
 */

use DI\DependencyException;
use DI\NotFoundException;
use Plugin_Name_Replace_Me\Base;
use Underpin\Loaders\Logger;

try {
	// Load the autoloader.
	require_once( plugin_dir_path( __FILE__ ) . 'vendor/autoload.php' );

	// Used in the config.php file.
	define( "PLUGIN_NAME_REPLACE_ME_FILE", __FILE__ );

	// Initialize the plugin.
	if ( ( Base\Base::instance() )->minimum_requirements_met() ) {
		add_action( 'admin_notices', function () {
			echo '<div class="error">
<p>PLUGIN NAME REPLACE ME could not be set up because it does not support the minimum version. This plugin requires at least PHP version ' . Base\Base::instance()->get_config( 'plugin.minimum_php_version' ) . ' and at least WordPress version ' . Base\Base::instance()->get_config( 'plugin.minimum_wp_version' ) . '</p>
</div>';
		} );
	};
} catch ( Exception $exception ) {
	// Log anything that goes wrong to the logger.
	Logger::alert( $exception );

	// Also put an admin notice in the admin screen.
	add_action( 'admin_notices', function () {
		echo '<div class="error"><p>PLUGIN NAME REPLACE ME could not be set up.</p></div>';
	} );
}

/**
 * Fires up the plugin.
 *
 * @return Base\Provider|null
 */
function plugin_name_replace_me(): ?Base\Provider {
	try {
		return Base\Base::instance()->get_provider();
	} catch ( DependencyException|NotFoundException $e ) {
		// Log if something went wrong.
		Logger::alert( $e );

		return null;
	}
}