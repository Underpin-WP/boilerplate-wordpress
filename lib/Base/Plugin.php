<?php

namespace Plugin_Name_Replace_Me\Base;

use Exception;
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

	protected function get_unsupported_requirements( array $checks ): array {
		return ( new Array_Processor( $checks ) )
			->filter( fn ( $item ) => ! isset( $item['supported'] ) || false === $item['supported'] )
			->each( fn ( $item ) => $item['minimum'] )
			->to_array();
	}


	public function minimum_requirements_met(): bool {
		try {
			$unsupported = $this->get_unsupported_requirements( [
				'PHP'       => [ 'minimum' => Base::instance()->get_config( 'plugin.minimum_php_version' ), 'supported' => Base::instance()->supports_php_version() ],
				'WordPress' => [ 'minimum' => Base::instance()->get_config( 'plugin.minimum_wp_version' ), 'supported' => Base::instance()->supports_wp_version() ],
			] );

			if ( ! empty( $unsupported ) ) {
				throw new Unmet_Requirements( $unsupported );
			}
		} catch ( Unmet_Requirements $e ) {
			add_action( 'admin_notices', function () use ( $e ) {
				$outdated = (string) ( new Array_Processor( $e->unmet_expected ) )
					->to_indexed()
					->map( fn ( $item ) => $item['key'] . ': ' . $item['minimum'] )
					->set_separator( ', ' );

				echo "<div class='error'><p>PLUGIN NAME REPLACE ME can't run because this site doesn't meet the minimum requirements. Unmet Requirements: $outdated</p></div>";
			} );

			return false;
		} catch ( Exception $e ) {
			add_action( 'admin_notices', function () {
				echo '<div class="error"><p>PLUGIN NAME REPLACE ME could not be set up.</p></div>';
			} );

			return false;
		}

		return true;
	}

}