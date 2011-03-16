<?php

use Spector\Import\Remote;
use Spector\LogEntry;
use Spector\Import\Import;
use Spector\Import\Fetcher\Drupal;

class FileSSHTest extends PHPUnit_Framework_TestCase
{
	
	protected function getImport()
	{
			$remote = new Remote();
			
			$remote->setHost('localhost');
			$remote->setUsername('theduke');
			$remote->setPassword('pcadmin3');
			$remote->setResourcePath(SPECTOR_DATA_PATH . '/import/php_error.log');		
		
			$import = new Import();
			$import->setFetcher(Import::FETCHER_DRUPAL);
			$import->setHandler(Import::HANDLER_ENTRIES);
			
			$import->setRemote($remote);
			
			return $import;
	}
	
	
	public function testFetch()
	{
		$import = $this->getImport();
		
		$fetcher = new \Spector\Import\Fetcher\FileSSH();
		$fetcher->initialize($import);
		$data = $fetcher->fetchData();
		
		$this->assertEquals(30728, strlen($data));
	}
	
}
	
	