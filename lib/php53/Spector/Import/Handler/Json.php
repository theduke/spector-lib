<?php

namespace Spector\Import\Handler;

use Spector\LogEntry;

class Json extends AbstractHandler implements Handler
{
	
	/* (non-PHPdoc)
	 * @see Spector\Import\Handler.Handler::getEntries()
	 */
	public function getEntries($data, \Spector\Import\Import $import)
	{
		$newEntries = array();
		
		$entries = explode("\n", $data);

		foreach ($entries as $rawEntry) 
		{
			if (!strlen($rawEntry)) continue;
			
			$rawEntry = json_decode($rawEntry);

			if ($rawEntry === false)
			{
				echo 'Could not decode json: ' . $rawEntry;
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