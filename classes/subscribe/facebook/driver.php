<?php

namespace Subscribe;

class Subscribe_Facebook_Driver extends Subscribe_Driver
{

	protected $_uri = 'http://www.facebook.com/feeds/page.php?format=json&id=:id';
	protected $_id;
	protected $_ua;
	protected $_screen_name;
	protected $_timeout;
	protected $_feeds = array();

	protected function __construct($config)
	{
		$this->_uri = \Arr::get($config, 'uri', $this->_uri);
		$this->_timeout = \Arr::get($config, 'timeout', 0);
		$this->_screen_name = \Arr::get($config, 'screen_name');
		$this->_id = \Arr::get($config, 'id');
		$this->_ua = \Arr::get($config, 'ua', \Input::user_agent());


		if ( ! $this->_screen_name)
		{
			throw new \InvalidArgumentException('screen_name parameter is not found.');
		}
		if ( ! $this->_id)
		{
			throw new \InvalidArgumentException('id parameter is not found.');
		}
		if ( ! $this->_ua)
		{
			throw new \InvalidArgumentException('User-agent is not found.');
		}
	}

	public function get()
	{
		if ( ! $this->_feeds)
		{
			$header = array(
				'User-Agent: '.$this->_ua
			);
			$context = stream_context_create(array(
				'http' => array(
					'timeout'  => $this->_timeout,
					'method'  => 'GET',
					'header'  => implode("\r\n", $header)
				)
			));
			$content = file_get_contents(\Str::tr($this->_uri, array('id' => $this->_id)), false, $context);
			$content = json_decode($content, true);
			foreach (\Arr::get($content, 'entries') as $entry)
			{
				$this->_feeds[] = array(
					'screen_name' => $this->_screen_name,
					'title' => \Arr::get($entry, 'title'),
					'content' => \Arr::get($entry, 'content'),
					'uri' => \Arr::get($entry, 'alternate'),
					'created_at' => strtotime(\Arr::get($entry, 'published')),
					'updated_at' => strtotime(\Arr::get($entry, 'updated')),
					'driver' => 'facebook',
					'main_uri' => \Str::tr('https://www.facebook.com/:screen_name', array('screen_name' => $this->_screen_name)),
				);
			}
		}
		return $this->_feeds;
	}
}