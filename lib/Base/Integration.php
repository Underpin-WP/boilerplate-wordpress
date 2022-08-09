<?php

namespace Plugin_Name_Replace_Me\Base;

use Underpin\Exceptions\Item_Not_Found;
use Underpin\Exceptions\Unmet_Requirements;
use Underpin\Helpers\Processors\Array_Processor;
use Underpin\Loaders\Logger;
use Underpin\WordPress\Interfaces\Integration_Provider;

/**
 * Integration Class.
 *
 * This plugin actually integrates with WordPress, using Underpin loaders in the constructor.
 */
class Integration implements Integration_Provider {

	public function __construct(
		/**
		 * Add loaders that are used in this plugin here. Type hint a concrete implementation and the
		 * dependency injection wrapper will take it from there.
		 */
	) {

	}

	/**
	 * Gets the plugin file.
	 *
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
	 * Gets the plugin directory.
	 *
	 * @throws Item_Not_Found
	 */
	public function get_dir(): string {
		return Base::instance()->get_config( 'plugin.dir' );
	}

	/**
	 * Gets the plugin name.
	 *
	 * @throws Item_Not_Found
	 */
	public function get_name(): string {
		return Base::instance()->get_config( 'plugin.name' );
	}

	/**
	 * Gets the plugin description
	 *
	 * @throws Item_Not_Found
	 */
	public function get_description(): string {
		return Base::instance()->get_config( 'plugin.description' );
	}

	/**
	 * Gets the plugin version.
	 *
	 * @throws Item_Not_Found
	 */
	public function get_version(): string {
		return Base::instance()->get_config( 'plugin.version' );
	}

	/**
	 * Gets the plugin URL.
	 *
	 * @return string
	 * @throws Item_Not_Found
	 */
	public function get_url(): string {
		return Base::instance()->get_config( 'plugin.url' );
	}

	/**
	 * Retrieves the unsupported requirements for this plugin. Checks against an array of requirements.
	 *
	 * @param array $checks An array keyed by the requirement name. Each item is an array containing
	 *                      "minimum" and "supported" values.
	 *
	 * @return array The list of unsupported items.
	 */
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

	/**
	 * Returns true if the minimum plugin requirements are met, otherwise throws an Unmet_Requirements exception.
	 *
	 * @return bool
	 * @throws Unmet_Requirements
	 */
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