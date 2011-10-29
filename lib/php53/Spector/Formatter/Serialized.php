<?php

namespace Spector\Formatter;

use Spector\Writer\Writable;

class Serialized implements Formatter
{
	const SEPARATOR = '-|-|-|-|-|-|-|-|-|-|-|-|-';
	
	protected $_separator = self::SEPARATOR;
	
	public function format(Writable $entry)
	{
		$output = serialize($entry->toArray());
		
		// if newline is separator, escape it
		if ($this->_separator === "\n") 
		{
			$output = str_replace("\n", "\\n", $output);
		}
		
		return $output . $this->_separator;
	}
	
	public function setSeparator($sep)
	{
		$this->_separator = $sep;
	}
	
	public function getSeparator()
	{
		return $this->_separator;
	}
}
