<?php

namespace Plugin_Name_Replace_Me\Base;


use DI\DependencyException;
use DI\NotFoundException;
use Underpin\Interfaces;
use Underpin\Loaders\Logger;

/**
 * Add any methods that you want to make publicly available as this plugin's API.
 * This class is accessed using plugin_name_replace_me() and the methods here are intended to be used to provide the
 * various functionalities in this plugin.
 *
 * This provider should provide instances from the builder, translating potentially long chains into sane, readable
 * values.
 */
class Provider implements Interfaces\Provider {

	/**
	 * Retrieves the plugin object.
	 *
	 * @return ?Plugin
	 */
	private function builder(): ?Plugin {
		try {
			return Base::instance()->get_builder();
		} catch ( DependencyException|NotFoundException $e ) {
			Logger::log_exception( 'emergency', $e );
			return null;
		}
	}

}