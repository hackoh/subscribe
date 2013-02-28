<?php

namespace Subscribe;

class Subscribe_Twitter_Driver extends Subscribe_Driver
{
	
	protected $_uri = 'https://api.twitter.com/1/statuses/user_timeline.json?screen_name=:screen_name';
	protected $_screen_name;
	protected $_timeout;
	protected $_feeds = array();

	protected function __construct($config)
	{
		$this->_uri = \Arr::get($config, 'uri', $this->_uri);
		$this->_timeout = \Arr::get($config, 'timeout', 0);

		$this->_screen_name = \Arr::get($config, 'screen_name');

		if ( ! $this->_screen_name)
		{
			throw new \InvalidArgumentException('screen_name parameter is not found.');
		}
	}

	public function get()
	{
		if ( ! $this->_feeds)
		{
			$context = stream_context_create(array(
				'http' => array(
					'timeout' => $this->_timeout
				)
			));
			$content = file_get_contents(\Str::tr($this->_uri, array('screen_name' => $this->_screen_name)), false, $context);
			$content = json_decode($content, true);
			foreach ($content as $entry)
			{
				$this->_feeds[] = array(
					'screen_name' => $this->_screen_name,
					'title' => \Arr::get($entry, 'text'),
					'content' => \Arr::get($entry, 'text'),
					'uri' => sprintf('https://twitter.com/%s/status/%s', $this->_screen_name, \Arr::get($entry, 'id')),
					'created_at' => strtotime(\Arr::get($entry, 'created_at')),
					'updated_at' => strtotime(\Arr::get($entry, 'created_at')),
					'driver' => 'twitter',
					'main_uri' => \Str::tr('https://twitter.com/:screen_name', array('screen_name' => $this->_screen_name)),
				);
			}
		}
		return $this->_feeds;
	}
}