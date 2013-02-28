<?php
/**
 * Subscribe package for FuelPHP
 *
 * @package    Subscribe
 * @version    1.0
 * @author     hackoh
 * @license    MIT License
 * @copyright  2013 hackoh
 * @link       http://github.com/hackoh
 */

namespace Subscribe;

class SubscribeException extends \FuelException {}
class DriverNotFoundException extends \FuelException {}
class InvalidDriverException extends \FuelException {}
class InvalidWrapClassException extends \FuelException {}

/**
 * Subscribe
 *
 * @package     Subscribe
 */
class Subscribe
{

	/**
	 * @var
	 */
	protected static $_instances = array();

	/**
	 * Initializing class.
	 */
	public static function _init()
	{
		\Config::load('subscribe', true);
	}

	/**
	 * Subscribe using options.
	 *  
	 * @param  array  $options [description]
	 * @return array  Subscribe_Entry instances.
	 */
	public static function get($name, $options = array())
	{
		// Get order setting.
		$order_by = \Arr::get($options, 'order_by', array('created_at' => 'desc'));

		// Get limit setting.
		$limit = \Arr::get($options, 'limit', null);

		// Get offset setting.
		$offset = \Arr::get($options, 'offset', 0);

		// If include driver names are not given, set to null.
		$includes = \Arr::get($options, 'includes', null);

		// Set wrap class name.
		$class_name = \Arr::get($options, 'class_name', 'Subscribe_Entry');

		// If exclude driver names are not given, set to empty array.
		//$excludes = \Arr::get($options, 'excludes', array());

		// Split key and order from order_by option.
		list ($key, $order) = static::_compile_order_by($order_by);

		if (is_array($name))
		{
			$configs = array($name);
		}
		else
		{
			// Get driver configs.
			$configs = \Config::get('subscribe.subscribers', array());
		}

		// If include driver names are not given, set to all driver names. 
		! $includes and $includes = array_keys($configs);

		$configs = \Arr::filter_keys($configs, $includes);

		// Exclude from include array by exclude option. 
		//$includes = \Arr::filter_keys($includes, $excludes, true);

		// Create cache key.
		$cache_key = $name.'.'.md5(serialize($configs).$limit.$offset.$key.$order);

		$cache_exists = false;

		if ($expire = (int) \Config::get('subscribe.expire', 0))
		{
			try
			{
				// If expire setting is given then set result from cache.
				$feeds = \Cache::get('subscribe.'.$cache_key);
				$cache_exists = true;
			}
			catch (\CacheNotFoundException $e) {}
		}

		if ( ! $cache_exists)
		{

			$feeds = array();

			foreach ($configs as $name => $config)
			{
				// Get a driver.
				$driver = Subscribe_Driver::instance($name, $config);

				// Get results and merge.
				$feeds = \Arr::merge($feeds, $driver->get());
			}

			// Sort by order option.
			$feeds = \Arr::sort($feeds, $key, $order);

			if ($limit)
			{
				// If limit parameter is given, then splice array using offset option.
				$feeds = array_splice($feeds, $offset, $limit);
			}

			if ($expire)
			{
				// Set cache using cache_key.
				\Cache::set('subscribe.'.$cache_key, $feeds, $expire ? : false);
			}
		}

		$entries = array();

		foreach ($feeds as $feed)
		{
			$entry = call_user_func(array($class_name, 'forge'), $feed);
			
			if ( ! $entry instanceof Subscribe_Entry)
			{
				throw new InvalidWrapClassException('class_name is invalid.');
			}

			$entries[] = $entry;
		}

		return $entries;
	}

	protected static function _compile_order_by(array $order_by)
	{
		$key = \Arr::get(array_keys($order_by), 0);
		$order = \Arr::get(array_values($order_by), 0);
		return array($key, $order);
	}
}