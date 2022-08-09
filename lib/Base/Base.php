<?php

namespace Plugin_Name_Replace_Me\Base;


use DI\Container;
use DI\ContainerBuilder;
use DI\DependencyException;
use DI\NotFoundException;
use Underpin\Exceptions\Exception;
use Underpin\Exceptions\Item_Not_Found;
use Underpin\Exceptions\Unmet_Requirements;
use Underpin\Helpers\Array_Helper;
use Underpin\Helpers\String_Helper;
use Underpin\Interfaces\Singleton;
use Underpin\Loaders\Logger;
use Underpin\WordPress\Interfaces;
use Underpin\WordPress\Interfaces\Integration_Provider;

/**
 * Base Class.
 *
 * This class is the singleton instance that fires up the entire plugin. Things like setting up the dependency injection
 * container happen here.
 *
 * This class should not directly call any methods to WordPress, or anything that is outside this plugin's code. All of
 * that setup should happen directly in the index.php file.
 */
class Base implements Interfaces\Base, Singleton {

	protected static self          $instance;
	protected Integration_Provider $builder;
	protected Provider             $provider;
	private Container              $container;

	/**
	 * @throws Exception
	 */
	public function __construct() {
		try {
			$this->container = ( new ContainerBuilder )
				->addDefinitions( String_Helper::before( __DIR__, '/lib/Base' ) . '/config.php' )
				->build();
		} catch ( \Exception $e ) {
			throw new Exception( message: $e->getMessage(), code: $e->getCode(), type: 'alert', previous: $e );
		}

		try {
			Logger::set_volume( $this->get_config( 'logger.volume' ) );
		} catch ( Item_Not_Found ) {
			// Ignore the attempt to set the volume if the config is not set.
		}
	}

	/**
	 * Checks if the PHP version meets the minimum requirements.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if the minimum requirements are met, false otherwise.
	 */
	public function is_supported(): bool {
		try {
			$version = $this->get_config( 'plugin.minimum_php_version' );
		} catch ( Item_Not_Found $e ) {
			return false;
		}

		return version_compare( phpversion(), $version, '>=' );
	}

	/**
	 * Gets the dependency injection container.
	 *
	 * @see https://php-di.org/
	 *
	 * @return Container
	 */
	public function get_container(): Container {
		return $this->container;
	}

	/**
	 * @throws Item_Not_Found
	 */
	public function get_config( string $param ) {
		try {
			return Array_Helper::dot( $this->get_container()->get( 'configuration' ), $param );
		} catch ( DependencyException|NotFoundException $e ) {
			throw new Item_Not_Found( item: $param, previous: $e );
		}
	}

	/**
	 * @throws DependencyException
	 * @throws NotFoundException|Unmet_Requirements
	 */
	public function get_provider(): Provider {
		if ( ! isset( $this->provider ) ) {
			try {
				if ( $this->get_builder()->minimum_requirements_met() ) {
					$this->provider = $this->get_container()->get( Provider::class );
				}
			} catch ( DependencyException|NotFoundException $e ) {
				Logger::alert( $e );
				throw $e;
			}
		}

		return $this->provider;
	}

	/**
	 * @throws DependencyException
	 * @throws NotFoundException
	 */
	public function get_builder(): Integration {
		if ( ! isset( $this->builder ) ) {
			try {
				$this->builder = $this->get_container()->get( Integration::class );
			} catch ( DependencyException|NotFoundException $e ) {
				Logger::alert( $e );
				throw $e;
			}
		}

		return $this->builder;
	}

	/**
	 * Gets the instance of the app base.
	 *
	 * @return static
	 */
	public static function instance(): static {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

}