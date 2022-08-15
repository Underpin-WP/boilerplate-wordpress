<?php

namespace Plugin_Name_Replace_Me\Core\Abstracts;


use Plugin_Name_Replace_Me\Core\Base\Base;
use Plugin_Name_Replace_Me\Core\Models\Beer\Provider;
use Underpin\Exceptions\Item_Not_Found;
use Underpin\Exceptions\Unmet_Requirements;
use Underpin\Helpers\Processors\Array_Processor;
use Underpin\Interfaces\Integration_Provider;
use Underpin\Loaders\Logger;

/**
 * Integration Class.
 *
 * Add any abstract methods that all systems (WordPress, custom REST, etc) need to implement in-order for this plugin
 * to work.
 */
abstract class Integration implements Integration_Provider {

	/**
	 * Returns true if the minimum plugin requirements are met, otherwise throws an Unmet_Requirements exception.
	 *
	 * @return bool
	 * @throws Unmet_Requirements
	 */
	abstract public function minimum_requirements_met(): bool;

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

}