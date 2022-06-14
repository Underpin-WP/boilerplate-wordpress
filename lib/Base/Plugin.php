<?php

namespace Plugin_Name_Replace_Me\Base;

use Underpin\Exceptions\Item_Not_Found;
use Underpin\Exceptions\Unmet_Requirements;
use Underpin\Helpers\Processors\Array_Processor;
use Underpin\Loaders\Logger;
use Underpin\WordPress\Interfaces\Integration_Provider;

class Plugin implements Integration_Provider {

	public function __construct(
		/**
		 * Add loaders that are used in this plugin here. Type hint a concrete implementation and the
		 * dependency injection wrapper will take it from there.
		 */
	) {

	}

	/**
	 * @throws Item_Not_Found
	 */
	public function get_file(): string {
		try {
			return Base::instance()->get_config( 'plugin.file' );
		} catch ( Item_Not_Found $e ) {
			Logger::log_exception( 'error', $e );
			throw $e;
		}
	}

	/**
	 * @throws Item_Not_Found
	 */
	public function get_dir(): string {
		try {
			return Base::instance()->get_config( 'plugin.dir' );
		} catch ( Item_Not_Found $e ) {
			Logger::log_exception( 'error', $e );
			throw $e;
		}
	}

	/**
	 * @throws Item_Not_Found
	 */
	public function get_name(): string {
		try {
			return Base::instance()->get_config( 'plugin.name' );
		} catch ( Item_Not_Found $e ) {
			Logger::log_exception( 'error', $e );
			throw $e;
		}
	}

	/**
	 * @throws Item_Not_Found
	 */
	public function get_description(): string {
		try {
			return Base::instance()->get_config( 'plugin.description' );
		} catch ( Item_Not_Found $e ) {
			Logger::log_exception( 'error', $e );
			throw $e;
		}
	}

	/**
	 * @throws Item_Not_Found
	 */
	public function get_version(): string {
		try {
			return Base::instance()->get_config( 'plugin.version' );
		} catch ( Item_Not_Found $e ) {
			Logger::log_exception( 'error', $e );
			throw $e;
		}
	}

	public function get_url(): string {
		try {
			return Base::instance()->get_config( 'plugin.url' );
		} catch ( Item_Not_Found $e ) {
			Logger::log_exception( 'error', $e );
			return $e;
		}
	}

	public function minimum_requirements_met(): bool {
		try {
			$unsupported = ( new Array_Processor( [
				'supports_php_version' => Base::instance()->supports_php_version(),
				'supports_wp_version'  => Base::instance()->supports_wp_version(),
			] ) )->filter( fn ( $item ) => false === $item )->to_array();

			if ( ! empty( $unsupported ) ) {
				throw new Unmet_Requirements( $unsupported );
			}
		} catch ( Unmet_Requirements $e ) {
			add_action( 'admin_notices', function () {
				echo '<div class="error">
<p>PLUGIN NAME REPLACE ME could not be set up because it does not support the minimum version. This plugin requires at least PHP version ' . Base::instance()->get_config( 'plugin.minimum_php_version' ) . ' and at least WordPress version ' . Base::instance()->get_config( 'plugin.minimum_wp_version' ) . '</p>
</div>';
			} );

		} catch ( \Exception $e ) {
			add_action( 'admin_notices', function () {
				echo '<div class="error"><p>PLUGIN NAME REPLACE ME could not be set up.</p></div>';
			} );

			return false;
		}
	}

}