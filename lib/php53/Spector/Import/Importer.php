<?php

namespace Spector\Import;

use Spector\Import\Handler\Handler;

use Spector\Import\Fetcher\Fetcher;

class Importer
{
	
	public function doImport(Import $import)
	{
		$fetcher = $this->getFetcher($import);
		$handler = $this->getHandler($import);
		
		$fetcher->initialize($import);
		
		$data = $fetcher->fetchData();
		
		return $handler->getEntries($data, $import);
	}
	
	protected function getFetcher(Import $import)
	{
		$fetcherClass = $import->getFetcher();
		
		$this->loadClass($fetcherClass);
		
		$fetcher = new $fetcherClass();
		
		if (!($fetcher instanceof Fetcher))
		{
			throw new \Exception("Invalid Fetcher class '$fetcherClass'");
		}
		
		return $fetcher;
	}
	
	protected function getHandler(Import $import)
	{
		$handlerClass = $import->getHandler();
		
		$this->loadClass($handlerClass);
		
		$handler = new $handlerClass();
		
		if (!($handler instanceof Handler))
		{
			throw new \Exception("Invalid handler class '$handlerClass'");
		}
		
		return $handler;
	}
	
	protected function loadClass($class)
	{
		$classToLoad = $class[0] === '\\' ? substr($class, 1) : $class;
		
		class_exists($classToLoad, true);
	}
}