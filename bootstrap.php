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


Autoloader::add_core_namespace('Subscribe');

Autoloader::add_classes(array(
	/**
	 * Subscribe core classes.
	 */
	'Subscribe\\Subscribe'			=> __DIR__.'/classes/subscribe.php',
	'Subscribe\\Subscribe_Entry'	=> __DIR__.'/classes/subscribe/entry.php',
	'Subscribe\\Subscribe_Driver'	=> __DIR__.'/classes/subscribe/driver.php',

	/**
	 * Subscribe drivers.
	 */
	'Subscribe\\Subscribe_Twitter_Driver'	=> __DIR__.'/classes/subscribe/twitter/driver.php',
	'Subscribe\\Subscribe_Facebook_Driver'	=> __DIR__.'/classes/subscribe/facebook/driver.php',

	/**
	 * Subscribe exceptions.
	 */
	'Subscribe\\SubscribeException'			=> __DIR__.'/classes/subscribe.php',
	'Subscribe\\DriverNotFoundException'	=> __DIR__.'/classes/subscribe.php',
	'Subscribe\\InvalidDriverException'		=> __DIR__.'/classes/subscribe.php',
	'Subscribe\\InvalidWrapClassException'	=> __DIR__.'/classes/subscribe.php',
));
