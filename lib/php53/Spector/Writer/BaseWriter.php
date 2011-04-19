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
		if (isset($arr['formatter']))
		{
			$type = $arr['formatter'];
			
			// try to trigger autoloading for class
			// php wont autoload when instantiating with variable as class name!
			$className = ($type[0] === '\\') ? substr($type, 1) : $type;
			class_exists($className, true);
			
			$this->setFormatter(new $type());
			
			unset($arr['formatter']);
		}
		
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