<?php

namespace Spector\Track;

/**
 * Enter description here ...
 * 
 * 
 * 
 * @author theduke
 *
 *
 * @property id
 * 
 * @property time
 * @property length
 * 
 * @property ip
 * 
 * @property username
 * @property sessionId
 * 
 * @property userAgent
 * 
 * @property extraData
 * 
 * @property method
 * @property host
 * @property path
 * @property query
 * 
 * @property referer
 * 
 * @property cookies
 *
 */
class Entry
{
	protected $_id;
	
	protected $_time;
	protected $_length;
	
	protected $_ip;
	protected $_username;
	
	protected $_sessionId;
	
	protected $_userAgent;
	
	protected $_method;
	protected $_host;
	protected $_path;
	protected $_query;
	
	protected $_cookies;
	
	protected $_referer;
	
	protected $_extraData = array();
	
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
			$a[str_replace('_', '', $key)] = $this->$key;
		}
		
		if (!$this->_id) unset($a['id']);
		
		return $a;
	}
}

