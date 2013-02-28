<?php

/**
 * Subscribe class tests
 *
 * @group Subscribe
 */
class Test_Subscribe extends TestCase
{
	protected function setUp()
	{
		Subscribe::_init();
	}

	public function test_factory_method()
	{
		$config = array(
			'driver' => 'facebook',
 			'screen_name' => 'facebook',
 			'id' => '20531316728',
 			'ua' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.97 Safari/537.22',
		);
		$driver = Subscribe_Driver::instance('test', $config);

		$expected = 'Subscribe\Subscribe_Facebook_Driver';

		$this->assertEquals($expected, get_class($driver));

		$config = array(
			'driver' => 'twitter',
			'screen_name' => 'hackoh',
		);
		$driver = Subscribe_Driver::instance('test', $config);

		$expected = 'Subscribe\Subscribe_Twitter_Driver';

		$this->assertEquals($expected, get_class($driver));

	}

	public function test_twitter_driver_get()
	{
		$driver = Subscribe_Twitter_Driver::forge(array(
 			'screen_name' => 'hackoh',
 			'timeout' => 10,
		));

		$entries = $driver->get();
		$entry = current($entries);

		$expected = 'twitter';

		$this->assertEquals($expected, $entry['driver']);
	}

	public function test_facebook_driver_get()
	{
		$driver = Subscribe_Facebook_Driver::forge(array(
 			'screen_name' => 'facebook',
 			'id' => '20531316728',
 			'timeout' => 10,
 			'ua' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.97 Safari/537.22',
		));

		$entries = $driver->get();
		$entry = current($entries);

		$expected = 'facebook';

		$this->assertEquals($expected, $entry['driver']);
	}

	public function test_get_twitter()
 	{
 		$entries = Subscribe::get(array(
 			'driver' => 'twitter',
 			'screen_name' => 'hackoh',
 			'timeout' => 10,
 		), array(
 			'limit' => 5
 		));

 		$test = $entries ? true : false;
 		$expected = true;

		$this->assertEquals($expected, $test);

		$test = count($entries);
		$expected = 5;

		$this->assertEquals($expected, $test);

		$entry = current($entries);

		$expected = 'twitter';

		$this->assertEquals($expected, $entry->driver);

		$expected = 'hackoh';

		$this->assertEquals($expected, $entry->screen_name);
	}

	public function test_get_facebook()
 	{
 		$entries = Subscribe::get(array(
 			'driver' => 'facebook',
 			'screen_name' => 'facebook',
 			'id' => '20531316728',
 			'timeout' => 10,
 			'ua' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.97 Safari/537.22',
 		), array(
 			'limit' => 5
 		));

 		$test = $entries ? true : false;
 		$expected = true;

		$this->assertEquals($expected, $test);

		$test = count($entries);
		$expected = 5;

		$this->assertEquals($expected, $test);

		$entry = current($entries);

		$expected = 'facebook';

		$this->assertEquals($expected, $entry->driver);

		$expected = 'facebook';

		$this->assertEquals($expected, $entry->screen_name);
	}
}