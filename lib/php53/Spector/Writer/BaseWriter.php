<?php

namespace Spector\Writer;

use Spector\Formatter\Formatter;

abstract class BaseWriter
{
	
	/**
	 * Enter description here ...
	 * 
	 * @var Spector\Formatter\Formatter
	 */
	protected $_formatter;
	
	public function fromArray(array $arr)
	{
		foreach ($arr as $property => $value)
		{
			$setter = "set" . ucfirst($property);
			if (method_exists($this, $setter))
			{
				call_user_func(array($this, $setter), $value);
			} else if (property_exists($this, $property)) {
				$this->$property = $value;
			}
		}
	}
	
	public function write(Writable $entry)
	{
		$this->_write($entry);
	}
	
	public function setFormatter(Formatter $formatter)
	{
		$this->_formatter = $formatter;
	}
}