<?php

namespace Plugin_Name_Replace_Me\Base;


use DI\DependencyException;
use DI\NotFoundException;
use Underpin\Interfaces;
use Underpin\Loaders\Logger;

/**
 * Plugin Provider.
 *
 * Add any methods that you want to make publicly available as this plugin's API.
 * This class is accessed using plugin_name_replace_me() and the methods here are intended to be used to provide the
 * various functionalities in this plugin.
 *
 * This provider should provide instances of plugin constructs, using loaders added in the plugin builder. Ideally, this
 * class should not directly call any functions or methods in WordPress, or anything outside this plugin. Instead,
 * it should work directly through the Plugin class using the builder() method.
 */
class Provider implements Interfaces\Provider {

	/**
	 * Retrieves the plugin object.
	 *
	 * @return ?Integration
	 */
	private function builder(): ?Integration {
		try {
			return Base::instance()->get_builder();
		} catch ( DependencyException|NotFoundException $e ) {
			Logger::emergency( $e );

			return null;
		}
	}

}