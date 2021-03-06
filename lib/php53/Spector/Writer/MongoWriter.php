<?php

namespace Spector\Writer;

class MongoWriter extends BaseWriter implements Writer
{
	protected $_connection;
	protected $_database;
	protected $_collection = 'log_entries';
	
	public function __construct($connection=null, $database=null)
	{
		$this->setConnection($connection);
		$this->setDatabase($database);
	}
	
	public function validate()
	{
		if (!($this->_connection instanceof \Mongo))
			throw new \Exception('No Mongo connection set.');
			
		$flag = ($this->_database instanceof \MongoDB) || is_string($this->_database); 
		if (!$flag)
			throw new \Exception('Database is neither MongoDB instance nor string');
			
		if (!($this->_collection instanceof \MongoCollection) && !is_string($this->_collection))
			throw new \Exception('Collection is neither MongoCollection instance nor string');
	}
	
	public function initialize()
	{
		if (is_string($this->_database))
			$this->_database = $this->_connection->selectDB($this->_database);
		
		if (is_string($this->_collection))
			$this->_collection = $this->_database->selectCollection($this->_collection);
	}
	
	public function shutdown()
	{
		$this->_connection->close();
	}
	
	public function _write(Writable $entry)
	{
		$data = $entry->toArray();
		
		if (!isset($data['time']) || !$data['time'])
		{
			$data['time'] = new MongoDate();
		}
		
		$this->_collection->insert($data);
	}
	
	public function setConnection($connection)
	{
		$this->_connection = $connection;
	}
	
	public function getConnection()
	{
		return $this->_connection;
	}
	
	public function setDatabase($database)
	{
		$this->_database = $database;
	}
	
	public function getDatabase()
	{
		return $this->_database;
	}
	
	public function setCollection($collection)
	{
		$this->_collection = $collection;
	}
	
	public function getCollection()
	{
		return $this->_collection;
	}
}
