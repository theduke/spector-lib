<?php

namespace Spector\Import;

use Spector\Common\Configurable;

class Import extends Configurable
{
	protected $_name;
	
	protected $_fetcher;
	protected $_handler;
	
	protected $_defaultProject;
	protected $_defaultBucket;
	protected $_defaultType;
	protected $_defaultEnvironment;
	
	protected $_deleteSource = false;
	protected $_lastImportIdentifier;
	
	/**
	 * Enter description here ...
	 * @var Spector\Import\Remote
	 */
	protected $_remote;
	
	const FETCHER_FILE 			= '\Spector\Import\Fetcher\File';
	const FETCHER_FILE_SSH 	= '\Spector\Import\Fetcher\FileSSH';
	const FETCHER_DRUPAL		= '\Spector\Import\Fetcher\Drupal';
	
	const HANDLER_SERIALIZED 	= '\Spector\Import\Handler\Serialized';
	const HANDLER_PHPLOG 			= '\Spector\Import\Handler\PHPLog';
	const HANDLER_ENTRIES			= '\Spector\Import\Handler\Entries';

	public function getName()
	{
	    return $this->_name;
	}

	public function setName($_name)
	{
	    $this->_name = $_name;
	}
	public function getFetcher()
	{
	    return $this->_fetcher;
	}

	public function setFetcher($_fetcher)
	{
	    $this->_fetcher = $_fetcher;
	}

	public function getHandler()
	{
	    return $this->_handler;
	}

	public function setHandler($_handler)
	{
	    $this->_handler = $_handler;
	}

	public function getDefaultProject()
	{
	    return $this->_defaultProject;
	}

	public function setDefaultProject($_defaultProject)
	{
	    $this->_defaultProject = $_defaultProject;
	}

	public function getDefaultBucket()
	{
	    return $this->_defaultBucket;
	}

	public function setDefaultBucket($_defaultBucket)
	{
	    $this->_defaultBucket = $_defaultBucket;
	}

	public function getDefaultType()
	{
	    return $this->_defaultType;
	}

	public function setDefaultType($_defaultType)
	{
	    $this->_defaultType = $_defaultType;
	}

	public function getDefaultEnvironment()
	{
	    return $this->_defaultEnvironment;
	}

	public function setDefaultEnvironment($_defaultEnvironment)
	{
	    $this->_defaultEnvironment = $_defaultEnvironment;
	}

	public function deleteSource($flag=null)
	{
			if (is_bool($flag)) $this->_deleteSource = $flag;
			
			return $this->_deleteSource;
	}

	public function getLastImportIdentifier()
	{
	    return $this->_lastImportIdentifier;
	}

	public function setLastImportIdentifier($_lastImportIdentifier)
	{
	    $this->_lastImportIdentifier = $_lastImportIdentifier;
	}
	
	/**
	 * Enter description here ...
	 * 
	 * @return \Spector\Import\Remote
	 */
	public function getRemote()
	{
	    return $this->_remote;
	}

	/**
	 * Enter description here ...
	 * 
	 * @param \Spector\Import\Remote $_remote
	 */
	public function setRemote(Remote $_remote)
	{
	    $this->_remote = $_remote;
	}
}