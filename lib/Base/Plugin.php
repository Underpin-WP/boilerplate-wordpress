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
			Logger::error( $e );
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
			Logger::error( $e );
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
			Logger::error( $e );
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
			Logger::error( $e );
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
			Logger::error( $e );
			throw $e;
		}
	}

	public function get_url(): string {
		try {
			return Base::instance()->get_config( 'plugin.url' );
		} catch ( Item_Not_Found $e ) {
			Logger::error( $e );

			return $e;
		}
	}

	protected function get_unsupported_requirements( array $checks ): array {
		return ( new Array_Processor( $checks ) )
			->filter( fn ( $item ) => ! isset( $item['supported'] ) || false === $item['supported'] )
			->each( fn ( $item ) => $item['minimum'] )
			->to_array();
	}

	/**
	 * Checks if the WP version meets the minimum requirements.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if the minimum requirements are met, false otherwise.
	 */
	protected function is_supported(): bool {
		global $wp_version;

		try {
			$required_version = Base::instance()->get_config( 'plugin.minimum_wp_version' );
		} catch ( Item_Not_Found $e ) {
			return false;
		}

		return version_compare( $wp_version, $required_version, '>=' );
	}

	public function minimum_requirements_met(): bool {

		try {
			$php_version = Base::instance()->get_config( 'plugin.minimum_php_version' );
			$wp_version  = Base::instance()->get_config( 'plugin.minimum_wp_version' );
		} catch ( Item_Not_Found ) {
			return false;
		}

		$unsupported = $this->get_unsupported_requirements( [
			'PHP'       => [
				'minimum'   => $php_version,
				'supported' => Base::instance()->is_supported(),
			],
			'WordPress' => [
				'minimum'   => $wp_version,
				'supported' => $this->is_supported(),
			],
		] );

		if ( ! empty( $unsupported ) ) {
			throw new Unmet_Requirements( $unsupported );
		}

		return true;
	}

}