<?php
/**
 * PLUGIN NAME REPLACE ME
 *
 * Plugin Name:       PLUGIN NAME REPLACE ME
 * Plugin URI:        PLUGIN URL REPLACE ME
 * Description:       PLUGIN DESCRIPTION REPLACE ME
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      8.1
 * Author:            AUTHOR NAME REPLACE ME
 * Author URI:        AUTHOR URL REPLACE ME
 * Text Domain:       plugin-name-replace-me
 */

use DI\DependencyException;
use DI\NotFoundException;
use Plugin_Name_Replace_Me\Base;
use Underpin\Exceptions\Unmet_Requirements;
use Underpin\Helpers\Processors\Array_Processor;
use Underpin\Loaders\Logger;

// Used in the config.php file.
const PLUGIN_NAME_REPLACE_ME_FILE = __FILE__;

try {
	// Load the autoloader.
	require_once( plugin_dir_path( PLUGIN_NAME_REPLACE_ME_FILE ) . 'vendor/autoload.php' );

	// Initialize the plugin.
	Base\Base::instance()->get_builder()->minimum_requirements_met();
} catch ( Unmet_Requirements $e ) {
	add_action( 'admin_notices', function () use ( $e ) {
		$outdated = (string) ( new Array_Processor( $e->unmet_expected ) )
			->to_indexed()
			->map( fn ( $item ) => $item['key'] . ' must be at least version ' . $item['value'] )
			->set_separator( ', ' );

		echo "<div class='error'><p>PLUGIN NAME REPLACE ME can't run because this site doesn't meet the minimum requirements. Unmet Requirements: $outdated</p></div>";
	} );
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
	} catch ( Unmet_Requirements ) {
		return null;
	}
}