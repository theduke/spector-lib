<?php

namespace Spector\Import\Handler;

use Spector\LogEntry;

abstract class AbstractHandler
{
	protected function getDefaultEntry(\Spector\Import\Import $import)
	{
		$entry = new LogEntry();
		
		if ($p = $import->getDefaultProject()) 			$entry->setProject($p);
		if ($b = $import->getDefaultBucket())  			$entry->setBucket($b);
		if ($e = $import->getDefaultEnvironment()) 	$entry->setEnvironment($e);
		if ($t = $import->getDefaultType())					$entry->setType($t);
		
		return $entry;
	}
	
	protected function mergeDefaults(\Spector\LogEntry $entry, \Spector\Import\Import $import)
	{
		if (!$entry->getProject()) 			$entry->setProject($import->getDefaultProject());
		if (!$entry->getBucket())  			$entry->setBucket($import->getDefaultBucket());
		if (!$entry->getEnvironment()) 		$entry->setEnvironment($import->getDefaultEnvironment());
		if (!$entry->getType())				$entry->setType($import->getDefaultType());
		
		return $entry;
	}
}
