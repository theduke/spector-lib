<?php

namespace Spector\Import\Handler;

use Spector\LogEntry;

class Serialized extends AbstractHandler implements Handler
{
	protected $_separator = \Spector\Formatter\Serialized::SEPARATOR;
	
	/* (non-PHPdoc)
	 * @see Spector\Import\Handler.Handler::getEntries()
	 */
	public function getEntries($data, \Spector\Import\Import $import)
	{
		$newEntries = array();
		
		$entries = explode($this->_separator, $data);

		foreach ($entries as $rawEntry) 
		{
			if (!strlen($rawEntry)) continue;
			
			// if newline is separator, unescape it
			if ($this->_separator === "\n")
			{
				$rawEntry = str_replace("\\n", "\n", $rawEntry);
			}
			
			$rawEntry = unserialize($rawEntry);
			
			if ($rawEntry === false)
			{
				echo 'Could not unserialize: ' . $rawEntry;
			}
			
			$entry = new LogEntry();
			
			// handle time correctly
			$time = $rawEntry['time'];
			if ($time instanceof \DateTime) {
				$time = new \MongoDate($time->getTimestamp());
			}
			else if (is_numeric($time)) {
				$time = new \MongoDate($time);
			}
			
			$entry->setTime($time);
			$entry->setMessage($rawEntry['message']);
			
			// if severity is still using old string style, try to map it
			$severity = $rawEntry['severity'];
			if (!is_numeric($severity))
			{
				$severity = \Spector\Logger::mapSeverity($severity, true);
			} 
			else $severity = (int) $severity;
			
			$entry->setSeverity($severity);
			$entry->setData($rawEntry['data']);
			
			if ($rawEntry['project']) 		$entry->setProject($rawEntry['project']);
			if ($rawEntry['type'])				$entry->setType($rawEntry['type']);
			if ($rawEntry['environment'])	$entry->setEnvironment($rawEntry['environment']);
			if ($rawEntry['bucket'])			$entry->setBucket($rawEntry['bucket']);
			
			$this->mergeDefaults($entry, $import);
			
			$newEntries[] = $entry;
		}

		return $newEntries;
	}
}
