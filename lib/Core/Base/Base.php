<?php

namespace Plugin_Name_Replace_Me\Core\Base;


use DI\Container;
use DI\ContainerBuilder;
use DI\DependencyException;
use DI\NotFoundException;
use Plugin_Name_Replace_Me\Core\Enums\Base_Events;
use Plugin_Name_Replace_Me\Core\Providers\Unmet_Requirements_Provider;
use Underpin\Exceptions\Exception;
use Underpin\Exceptions\Invalid_Registry_Item;
use Underpin\Exceptions\Item_Not_Found;
use Underpin\Exceptions\Operation_Failed;
use Underpin\Exceptions\Unknown_Registry_Item;
use Underpin\Exceptions\Unmet_Requirements;
use Underpin\Helpers\Array_Helper;
use Underpin\Helpers\String_Helper;
use Underpin\Interfaces;
use Underpin\Interfaces\Integration_Provider;
use Underpin\Interfaces\Observer;
use Underpin\Interfaces\Singleton;
use Underpin\Loaders\Logger;
use Underpin\Traits\With_Broadcaster;
use UnitEnum;

/**
 * Base Class.
 *
 * This class is the singleton instance that fires up the entire plugin. Things like setting up the dependency injection
 * container happen here.
 *
 * This class should not directly call any methods to WordPress, or anything that is outside this plugin's code. All of
 * that setup should happen directly in the index.php file.
 */
class Base implements Interfaces\Base, Singleton, Interfaces\Can_Broadcast {

	use With_Broadcaster;

	protected static self          $instance;
	protected Integration_Provider $builder;
	protected Provider             $provider;
	private Container              $container;
	private bool                   $initialized = false;
	private array                  $configs     = [];

	/**
	 * @throws Operation_Failed
	 */
	public function __construct() {
		$this->set_config( require( String_Helper::before( __DIR__, '/Base' ) . '/config.php' ) );
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
	 * @throws NotFoundException|Unmet_Requirements|Item_Not_Found
	 */
	public function get_provider(): Provider {
		if ( ! isset( $this->provider ) ) {
			try {
				if ( $this->get_integration()->minimum_requirements_met() ) {
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
	 * @throws Item_Not_Found
	 */
	public function get_integration(): Integration_Provider {
		if ( ! isset( $this->builder ) ) {
			try {
				$this->builder = $this->get_container()->get( $this->get_config( 'integration.instance' ) );

				if ( $this->builder instanceof Interfaces\Feature_Extension ) {
					$this->builder->do_actions();
				}
			} catch ( DependencyException|NotFoundException|Item_Not_Found $e ) {
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

	/**
	 * Adds a configuration to merge into the final set of configs.
	 *
	 * @throws Operation_Failed
	 */
	public function set_config( array $config ): static {
		if ( $this->initialized ) {
			throw new Operation_Failed( 'Cannot set configuration after plugin is initialized.' );
		}

		$this->configs[] = $config;

		return $this;
	}

	/**
	 * Setup call.
	 *
	 * @throws Operation_Failed|Exception
	 */
	public function init(): void {
		if ( $this->initialized ) {
			throw new Operation_Failed( 'PLUGIN NAME REPLACE ME has already been initialized.' );
		}

		try {
			$this->container = ( new ContainerBuilder )
				->addDefinitions( [ 'configuration' => array_merge_recursive( ...$this->configs ) ] )
				->build();
		} catch ( \Exception $e ) {
			throw new Exception( message: $e->getMessage(), code: $e->getCode(), type: 'alert', previous: $e );
		}

		try {
			Logger::set_volume( $this->get_config( 'logger.volume' ) );
		} catch ( Item_Not_Found ) {
			// Ignore the attempt to set the volume if the config is not set.
		}

		try {
			$this->get_integration()->minimum_requirements_met();
			$this->broadcast( Base_Events::Ready );
		} catch ( Unmet_Requirements $e ) {
			$this->broadcast( Base_Events::Requirements_Not_Met, new Unmet_Requirements_Provider( $e->unmet_expected ) );
		} catch ( \Exception $exception ) {
			throw new Exception( 'Something went wrong during setup.', previous: $exception );
		}

		$this->initialized = true;
	}

	/**
	 * Attaches an observer to the specified event.
	 *
	 * When the event of the specified key is broadcasted, the Observer's notify method is called.
	 *
	 * @param UnitEnum $key
	 * @param Observer $observer
	 *
	 * @return $this
	 * @throws \ReflectionException
	 */
	public function attach( UnitEnum $key, Observer $observer ): static {
		try {
			$this->get_broadcaster()->attach( $key, $observer );
		} catch ( Unknown_Registry_Item|Invalid_Registry_Item $e ) {
			// Fail silently. These items are logged automatically.
		}

		return $this;
	}

	/**
	 * Detaches an observer from the specified event.
	 *
	 * @param UnitEnum $key
	 * @param string   $observer_id
	 *
	 * @return $this
	 */
	public function detach( UnitEnum $key, string $observer_id ): static {
		try {
			$this->get_broadcaster()->detach( $key, $observer_id );
		} catch ( Unknown_Registry_Item $e ) {
			// Fail silently. These items are logged automatically.
		}

		return $this;
	}

}