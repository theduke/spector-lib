<?php

namespace Spector\Writer;

use Spector\LogEntry;

class StreamWriter extends BaseWriter implements Writer
{
	protected $_stream;
	
	protected $_handle;
	
	public function __construct($stream=null, $format=null)
	{
		$this->setStream($stream);
		$this->setFormat($format);
	}
	
	public function _write(Writable $entry)
	{
		$output = $this->_formatter->format($entry);
		
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
}