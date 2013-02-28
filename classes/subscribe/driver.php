<?php

namespace Subscribe;

abstract class Subscribe_Driver
{
	protected static $_instances = array();

	public static function forge($config)
	{
		return new static($config);
	}

	/**
	 * Driver instance's factory.
	 * @param  string $name   driver name
	 * @param  array  $config [description]
	 * @return Subscribe_Driver         [description]
	 */
	public static function instance($name, $config = array())
	{

		$driver = \Arr::get($config, 'driver');

		if ( ! $driver)
		{
			// Driver name is not specified in config.
			throw new DriverNotFoundException('driver name is not found.');
		}

		$instance = \Arr::get(static::$_instances, $driver.'.'.$name);

		if ( ! $instance)
		{

			$driver_class_name = sprintf('Subscribe_%s_Driver', ucfirst($driver));
			
			if ( ! class_exists($driver_class_name))
			{
				// Driver class is not found.
				throw new DriverNotFoundException('driver class is not found.');
			}

			$instance = call_user_func(array('\\'.$driver_class_name, 'forge'), $config);

			if ( ! $instance instanceof Subscribe_Driver)
			{
				// Driver class is not instance of Subscribe_Driver.
				throw new InvalidDriverException('driver class is invalid.');
			}

			\Arr::set(static::$_instances, $name, $instance);
		}
		return $instance;
	}

	/**
	 * [subscribe description]
	 * @return array Subscribe_Entry instances.
	 */
	abstract public function get();
}