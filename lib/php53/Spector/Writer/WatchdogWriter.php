<?php

namespace Spector\Writer;

use Spector\LogEntry;

class WatchdogWriter
{
	public function _write(Writable $entry)
	{
		watchdog($entry->getBucket(), $entry->getMessage(), array(), $entry->getSeverity());
	}
	
	public function validate()
	{
		
	}
	
	public function initialize()
	{
		if (!function_exists('watchdog'))
		{
			throw new \Exception('Watchdog function does not exist. Drupal not initialized?');
		}
	}
	
	public function shutdown()
	{
		
	}
}