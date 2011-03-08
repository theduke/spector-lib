<?php

namespace Spector\Import;

class Remote extends Spector\Common\Configurable
{
	protected $_type;
	
	protected $_host;
	protected $_port;
	
	protected $_username;
	protected $_password;
	protected $_key;
	
	protected $_resourcePath;
	
	/**
	 * 
	 * @return 
	 */
	public function getType()
	{
	    return $this->_type;
	}

	/**
	 * 
	 * @param $_type
	 */
	public function setType($_type)
	{
	    $this->_type = $_type;
	}

	/**
	 * 
	 * @return 
	 */
	public function getHost()
	{
	    return $this->_host;
	}

	/**
	 * 
	 * @param $_host
	 */
	public function setHost($_host)
	{
	    $this->_host = $_host;
	}

	/**
	 * 
	 * @return 
	 */
	public function getPort()
	{
	    return $this->_port;
	}

	/**
	 * 
	 * @param $_port
	 */
	public function setPort($_port)
	{
	    $this->_port = $_port;
	}

	/**
	 * 
	 * @return 
	 */
	public function getUsername()
	{
	    return $this->_username;
	}

	/**
	 * 
	 * @param $_username
	 */
	public function setUsername($_username)
	{
	    $this->_username = $_username;
	}

	/**
	 * 
	 * @return 
	 */
	public function getPassword()
	{
	    return $this->_password;
	}

	/**
	 * 
	 * @param $_password
	 */
	public function setPassword($_password)
	{
	    $this->_password = $_password;
	}

	/**
	 * 
	 * @return 
	 */
	public function getKey()
	{
	    return $this->_key;
	}

	/**
	 * 
	 * @param $_key
	 */
	public function setKey($_key)
	{
	    $this->_key = $_key;
	}

	/**
	 * 
	 * @return 
	 */
	public function getResourcePath()
	{
	    return $this->_resourcePath;
	}

	/**
	 * 
	 * @param $_resourcePath
	 */
	public function setResourcePath($_resourcePath)
	{
	    $this->_resourcePath = $_resourcePath;
	}
}