<?php

namespace Spector;

class Log
{
	protected $_writers = array();
	
	protected $_project;
	protected $_environment;
	protected $_type = 'log';
	protected $_bucket = 'main';
	
	public function __construct()
	{
		
	}
	
	public function log($message, $severity, $data=null, $bucket=null, $type=null, $environment=null, $project=null, $time=null)
	{
		if (!$project) $project = $this->_project;
		if (!$environment) $environment = $this->_environment;
		if (!$bucket) $bucket = $this->_bucket;
		if (!$type) $type = $this->_type;
		if(!$time) $time = new \DateTime();
		
		$entry = new LogEntry();
		$entry->setProject($project);
		$entry->setEnvironment($environment);
		$entry->setBucket($bucket);
		$entry->setMessage($message);
		$entry->setSeverity(strtoupper($severity));
		$entry->setTime($time);
		$entry->setData($data);
		$entry->setType($type);
		
		$this->logEntry($entry);
	}
	
	public function __call($name, $arguments)
	{
		$arguments = array_values($arguments);
		array_splice($arguments, 1, 0, $name);
		
		call_user_method_array('log', $this, $arguments);
	}
	
	public function logEntry(LogEntry $entry)
	{
		$entry->validate();
		
		foreach ($this->_writers as $writer)
		{
			$writer->write($entry);
		}
	}
	
	public function addWriter(Writer $writer)
	{
		$writer->validate();
		$writer->initialize();
		
		$this->_writers[] = $writer;
	}
	
	public function setProject($project)
	{
		$this->_project = $project;
	}
	
	public function getProject()
	{
		return $this->_project;
	}
	
	public function setEnvironment($environment)
	{
		$this->_environment = $environment;
	}
	
	public function getEnvironment()
	{
		return $this->_environment;
	}
	public function setBucket($bucket)
	{
		$this->_bucket = $bucket;
	}
	
	public function getBucket()
	{
		return $this->_bucket;
	}
	
	public function setType($type)
	{
		$this->_type = $type;
	}
	
	public function getType()
	{
		return $this->_type;
	}
}