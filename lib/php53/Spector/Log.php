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
	
	/**
	 * Add writers based on an array specification
	 * @param array $data
	 */
	public function fromArray(array $data) {
		// first handle writers
		if (isset($data['writers'])) {
			foreach ($data['writers'] as $type => $specs) {
				
				// if its an actual writer instance just add it
				if ($specs instanceof Writer) {
					$this->addWriter($specs);
					continue;
				}
				
				switch ($type)
				{
					case 'Mongo':
						$writer = new MongoWriter();
						
						if (!isset($specs['server']) || !isset($specs['database'])) {
							throw new \Exception('Server or database not set.');
						}
						
						$connection = new Mongo($specs['server'] . (isset($specs['port']) ? $specs['port'] : ''));
						
						$writer->setConnection($connection);
						$writer->setDatabase($specs['database']);
						break;
					default:
						$writer = new $type();
						$writer->fromArray($specs);
						break;
				}
				
				$this->addWriter($writer);
			}
			
			unset($data['writers']);
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