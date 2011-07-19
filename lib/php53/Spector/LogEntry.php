<?php 

namespace Spector;

use Spector\Writer\Writable;

class LogEntry implements Writable
{
	const EMERGENCY = 0;
	const ALERT = 1;
	const CRITICAL = 2;
	const ERROR = 3;
	const WARNING = 4;
	const NOTICE = 5;
	const INFO = 6;
	const DEBUG = 7;
	
	const OTHER = 8;
	const MONITOR = 9;
	
	protected $_id;
	
	protected $_project;
	protected $_environment;
	protected $_bucket;
	
	protected $_type;
	
	protected $_severity;
	
	
	/**
	 * @var \DateTime
	 */
	protected $_time;
	protected $_message;
	protected $_messageId;
	protected $_data;
	
	public function validate()
	{
		if (!is_numeric($this->_severity))
		{
			throw new \Exception('No severity set');
		}
		
		foreach (array('_project', '_environment', '_bucket', '_message', '_time') as $property)
		{
			if (!$this->$property) throw new \Exception("No $property set.");
		}
	}
	
	public function setId(MongoId $id)
	{
		$this->_id = $id;
	}
	
	public function getId()
	{
		return $this->_id;
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
	
	public function setSeverity($severity)
	{
		$this->_severity = $severity;
	}
	
	public function getSeverity()
	{
		return $this->_severity;
	}
	public function setTime($time)
	{
		$this->_time = $time;
	}
	
	public function getTime()
	{
		return $this->_time;
	}
	public function setMessage($message)
	{
		$this->_message = $message;
	}
	
	public function getMessage()
	{
		return $this->_message;
	}
	public function setData($data)
	{
		$this->_data = $data;
	}
	
	public function setMessageId($id)
	{
		$this->_messageId = $id;
	}
	
	public function getMessageId()
	{
		return $this->_messageId;
	}
	
	public function getData()
	{
		return $this->_data;
	}
	
	public function toArray()
	{
		$entry = array(
			'project' => $this->_project,
			'environment' => $this->_environment,
			'bucket' => $this->_bucket,
			'severity' => $this->_severity,
			'time'	=> $this->_time,
			'message' => $this->_message,
			'messageId' => $this->_messageId,
			'data' => $this->_data,
			'type' => $this->_type
		);
		
		if ($this->_id) $entry['_id'] = $this->_id;
		
		return $entry;
	}
	
	public function fromArray($entry)
	{
		foreach ($entry as $key => $value)
		{
			$setter = 'set' . ucfirst($key);
			if (method_exists($this, $setter))
			{
				call_user_func($setter, $value);	
			}
		}
	}
}