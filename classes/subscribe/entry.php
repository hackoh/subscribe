<?php

namespace Subscribe;

class Subscribe_Entry
{

	public static function forge($data)
	{
		return new static($data);
	}

	protected $_data = array(
		'title' => null,
		'content' => null,
		'uri' => null,
		'created_at' => 0,
		'updated_at' => 0,
		'driver' => null,
		'screen_name' => null,
		'main_uri' => null, 
	);

	public function __construct($data = array())
	{
		foreach ($data as $key => $value)
		{
			$this->_data[$key] = $value;
		}
	}

	public function __get($property)
	{
		if (array_key_exists($property, $this->_data))
		{
			return $this->_data[$property];
		}
		else
		{
			throw new \OutOfBoundsException('Property "'.$property.'" not found for '.get_called_class().'.');
		}
	}

	public function __set($property, $value)
	{
		if (array_key_exists($property, $this->_data))
		{
			throw new \OutOfBoundsException('Property "'.$property.'" value can\'t update for '.get_called_class().'.');
		}
		else
		{
			throw new \OutOfBoundsException('Property "'.$property.'" not found for '.get_called_class().'.');
		}
	}
}