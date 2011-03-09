<?php

namespace Spector\Import\Handler;

use Spector\LogEntry;

class Serialized extends AbstractHandler implements Handler
{
	
	/* (non-PHPdoc)
	 * @see Spector\Import\Handler.Handler::getEntries()
	 */
	public function getEntries($data, \Spector\Import\Import $import)
	{
		$newEntries = array();
		
		$entries = explode(\Spector\Writer\StreamWriter::SERIALIZE_SEPERATOR, $data);

		foreach ($entries as $rawEntry) 
		{
			if (!strlen($rawEntry)) continue;
			
			$rawEntry = unserialize($rawEntry);

			if ($rawEntry === false)
			{
				continue;
				/** @todo add logging */
			}
			
			$entry = new LogEntry();
			
			$entry->setTime($rawEntry['time']);
			$entry->setMessage($rawEntry['message']);
			$entry->setSeverity($rawEntry['severity']);
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