<?php

namespace Spector\Writer;

use Spector\LogEntry;

class StreamWriter extends BaseWriter implements Writer
{
	protected $_stream;
	
	protected $_handle;
	
	protected $_format;
	
	const FORMAT_ECHO = 'echo';
	const FORMAT_READABLE = 'readable';
	const FORMAT_CSV = 'csv';
	const FORMAT_SERIALIZED = 'serialized';
	
	const SERIALIZE_SEPERATOR = '-|-|-|-|-|-|-|-|-|-|-|-|-';
	
	public function __construct($stream=null, $format=null)
	{
		$this->setStream($stream);
		$this->setFormat($format);
	}
	
	public function write(Writable $entry)
	{
		$output = '';
		
		switch ($this->_format)
		{
			case self::FORMAT_READABLE:
				
				if ($entry instanceof LogEntry)
				{
					$output = sprintf('%s: %s: %s', date('Y-M-d h:i:s', $entry->getTime()->getTimestamp()), $entry->getSeverity(), $entry->getMessage()) . PHP_EOL;
				} else {
					throw new \Exception('Not implemented.');
				}
				
				break;
			case self::FORMAT_ECHO:
				
				if ($entry instanceof LogEntry)
				{
					$output = $entry->getMessage() . PHP_EOL;
				} else {
					throw new \Exception('Not implemented.');
				}
				
				break;
			case self::FORMAT_CSV:
				break;
			case self::FORMAT_SERIALIZED:
				$output = serialize($entry->toArray()) . self::SERIALIZE_SEPERATOR;
				break;
		}
		
		$flag = fwrite($this->_handle, $output);
		
		if (!$flag)
			throw new \Exception("Could not write log message to stream: {$this->_stream}");
	}
	
	public function validate()
	{
		if (!$this->_stream || !$this->_format)
			throw new \Exception('Stream or format not set.');
	}
	
	public function initialize()
	{
		$this->_handle = fopen($this->_stream, 'a');
		
		if (!$this->_handle)
			throw new \Exception("Could not open stream {$this->_stream}");
	}
	
	public function shutdown()
	{
		fclose($this->_handle);
	}
		
	public function setStream($stream)
	{
		$this->_stream = $stream;
	}
	
	public function getStream()
	{
		return $this->_stream;
	}
	
	public function setFormat($format)
	{
		$this->_format = $format;
	}
	
	public function getFormat()
	{
		return $this->_format;
	}
}