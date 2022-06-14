<?php

namespace Plugin_Name_Replace_Me\Base;


use DI\Container;
use DI\ContainerBuilder;
use DI\DependencyException;
use DI\NotFoundException;
use Underpin\Exceptions\Exception;
use Underpin\Exceptions\Item_Not_Found;
use Underpin\Helpers\Array_Helper;
use Underpin\Interfaces\Provider;
use Underpin\Interfaces\Singleton;
use Underpin\Loaders\Logger;
use Underpin\WordPress\Interfaces;
use Underpin\WordPress\Interfaces\Integration_Provider;

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
			$this->container = ( new ContainerBuilder )->addDefinitions( PLUGIN_NAME_REPLACE_ME_ROOT . '/config.php' )->build();
		} catch ( \Exception $e ) {
			throw new Exception( message: $e->getMessage(), code: $e->getCode(), previous: $e );
		}
	}

	/**
	 * Checks if the PHP version meets the minimum requirements.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if the minimum requirements are met, false otherwise.
	 * @throws Item_Not_Found
	 */
	public function supports_php_version(): bool {
		return version_compare( phpversion(), $this->get_config( 'plugin.minimum_php_version' ), '>=' );
	}

	/**
	 * Checks if the WP version meets the minimum requirements.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if the minimum requirements are met, false otherwise.
	 * @throws Item_Not_Found
	 */
	public function supports_wp_version(): bool {
		global $wp_version;

		return version_compare( $wp_version, $this->get_config( 'plugin.minimum_wp_version' ), '>=' );
	}

	public function minimum_requirements_met(): bool {
		try {
			return $this->supports_php_version() && $this->supports_wp_version();
		} catch ( \Exception $e ) {
			Logger::log_exception( 'error', $e );
			add_action( 'admin_notices', function () {
				echo '<div class="error">
<p>PLUGIN NAME REPLACE ME could not be set up because it does not support the minimum version. This plugin requires at least PHP version ' . $this->get_config( 'plugin.minimum_php_version' ) . ' and at least WordPress version ' . $this->get_config( 'plugin.minimum_wp_version' ) . '</p>
</div>';
			} );

			return false;
		}
	}

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
	 * @throws NotFoundException
	 */
	public function get_provider(): Provider {
		if ( ! isset( $this->provider ) ) {
			try {
				$this->builder = $this->get_container()->get( Provider::class );
			} catch ( DependencyException|NotFoundException $e ) {
				Logger::log_exception( 'emergency', $e );
				throw $e;
			}
		}

		return $this->provider;
	}

	/**
	 * @throws DependencyException
	 * @throws NotFoundException
	 */
	public function get_builder(): Plugin {
		if ( ! isset( $this->builder ) ) {
			try {
				$this->builder = $this->get_container()->get( Plugin::class );
			} catch ( DependencyException|NotFoundException $e ) {
				Logger::log_exception( 'emergency', $e );
				throw $e;
			}
		}

		return $this->builder;
	}

	public static function instance(): static {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

}