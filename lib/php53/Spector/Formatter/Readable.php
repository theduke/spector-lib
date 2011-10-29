<?php

namespace Spector\Formatter;

use Spector\Logger;

use Spector\Writer\Writable;

class Readable implements Formatter
{
	public function format(Writable $entry)
	{
		$output = sprintf('%s: %s: %s', 
			date('Y-M-d h:i:s', $entry->getTime()->getTimestamp()), 
			Logger::mapSeverity($entry->getSeverity()), 
			$entry->getMessage()) 
			. PHP_EOL;
			
		return $output;
	}
}
