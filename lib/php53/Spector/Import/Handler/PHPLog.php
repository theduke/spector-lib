<?php

namespace Spector\Import\Handler;

use Spector\LogEntry;

class PHPLog extends AbstractHandler implements Handler
{
	
	protected function mapPhpType($type)
	{
		$severity = null;
		
		switch ($type)
		{
			case 'Warning':
			case 'Notice':
				$severity = LogEntry::WARNING;
				break;
			case 'Fatal error':
			case 'Parse error':
				$severity = LogEntry::CRITICAL;
				break;
			case 'Strict Standards':
			case 'Deprecated':
				$severity = LogEntry::DEBUG;
				break;
			default:
				$severity = LogEntry::INFO;
				break;
		}
		
		return $severity;
	}
	
	/* (non-PHPdoc)
	 * @see Spector\Import\Handler.Handler::getEntries()
	 */
	public function getEntries($data, \Spector\Import\Import $import)
	{

		$messages = explode(PHP_EOL, $data);
		
		$lines = count($messages);
		
		if (!$lines) continue;
		
		$entries = array();
		
		$entry = null;
		$stackTrace = '';
		
		for ($i=0; $i < $lines; $i++)
		{
			$message = $messages[$i];
			
			$pattern = '/\[(.*?)\]\sPHP (.*?)\:\s+(.*)/';
			$matches = array();
			
			preg_match($pattern, $message, $matches);
			
			if (count($matches) !== 4)
			{
				if (is_string($message)) $stackTrace .= $message;
				
				// set stack and persist if we are at end of file
				if ($i === $lines - 1 && $entry)
				{
					$entry->setData($stackTrace);
					$entries[] = $entry;
				}
			} else 
			{
				// save previous entry
				if ($entry)
				{
					$entry->setData($stackTrace);
					$entries[] = $entry;
					
					$stackTrace = '';
				}
				
				// create new entry
				$entry = new LogEntry();
				
				$severity = $this->mapPhpType($matches[2]);
			
				$time = strtotime($matches[1]);
				// convert to microseconds
				$time *= 1000000;
				
				$entry->setMessage($matches[3]);
				$entry->setTime($time);
				$entry->setSeverity($severity);
				
				$entry->setType('php');
				$this->mergeDefaults($entry, $import);
			}
		}
		
		return $entries;
	}
}