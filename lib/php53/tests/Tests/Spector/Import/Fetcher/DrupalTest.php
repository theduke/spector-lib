<?php

use Spector\Import\Remote;
use Spector\LogEntry;
use Spector\Import\Import;
use Spector\Import\Fetcher\Drupal;

class DrupalTest extends PHPUnit_Framework_TestCase
{
	
	protected function getImport()
	{
			$remote = new Remote();
			
			$remote->setHost('localhost');
			$remote->setUsername('root');
			$remote->setPassword('crevo2020');
			$remote->setResourcePath('jpi_development');		
		
			$import = new Import();
			$import->setFetcher(Import::FETCHER_DRUPAL);
			$import->setHandler(Import::HANDLER_ENTRIES);
			
			$import->setRemote($remote);
			
			return $import;
	}
	
	
	public function testFetch()
	{
		$import = $this->getImport();
		
		$fetcher = new \Spector\Import\Fetcher\Drupal();
		$fetcher->initialize($import);
		$entries = $fetcher->fetchData();
	}
	
}
	
	