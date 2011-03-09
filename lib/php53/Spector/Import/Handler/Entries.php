<?php

namespace Spector\Import\Handler;

use Spector\LogEntry;

class Entries extends AbstractHandler implements Handler
{
	
	/* (non-PHPdoc)
	 * @see Spector\Import\Handler.Handler::getEntries()
	 */
	public function getEntries($data, \Spector\Import\Import $import)
	{
		$entries = array();
		
		foreach ($data as $entry)
		{
			$entries[] = $this->mergeDefaults($entry, $import);
		}
		
		return $entries;
	}
}