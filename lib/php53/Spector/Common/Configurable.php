<?php

namespace Spector\Common;

class Configurable
{
	public function __set($key, $value)
	{
		$property =  '_' . $key;
		
		$setter = 'set' . ucfirst($key);
		
		if (method_exists($this, $setter))
		{
			$this->$setter($value);
		}
		else if (property_exists($this, '_' . $key))
		{
			$this->$property = $value;
		}
		else {
			throw new \Exception("Property $key does not exist");
		}
	}
	
	public function __get($key)
	{
		$value = null;
		
	  $property =  '_' . $key;
		
		$getter = 'get' . ucfirst($key);
		
		if (method_exists($this, $getter))
		{
			$value = $this->$getter();
		}
		else if (property_exists($this, $property))
		{
			$value = $this->$property;
		}
		else {
			throw new \Exception("Property $key does not exist");
		}
		
		return $value;
	}
	
	public function toArray()
	{
		$a = array();
		
		foreach (get_object_vars($this) as 	$key)
		{
			
			
			$a[str_replace('_', '', $key)] = is_object($this->$key) && method_exists($this->$key, 'toArray') ? $this->$key->toArray() : $this->$key;
		}
		
		if (!$this->_id) unset($a['id']);
		
		return $a;
	}
	
	public function fromArray(array $a)
	{
		foreach ($a as $key => $value) 
		{
			if (!property_exists($this, '_' . $key)) continue;
			$this->$key = $value;
		}
	}
}
