<?php

use Spector\Import\Handler\Serialized;
use Spector\Import\Remote;
use Spector\LogEntry;
use Spector\Import\Import;
use Spector\Import\Fetcher\Drupal;

class SerializedHandlerTest extends PHPUnit_Framework_TestCase
{
	protected function getImport()
	{
			$remote = new Remote();
			
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
		
		$data = file_get_contents(SPECTOR_DATA_PATH . '/import/serialized.data');
		
		$handler = new Serialized();
		$entries = $handler->getEntries($data, $import);
		
		var_dump($entries);
		$entry = $entries[8];
		
		$this->assertEquals('php', $entry->getType());
		$this->assertEquals(LogEntry::CRITICAL, $entry->getSeverity());
		$this->assertEquals(1289590219000000 , $entry->getTime());
		$this->assertEquals(339, strlen($entry->getMessage()));
		$this->assertEquals(826, strlen($entry->getData()));
	}
	
}
	
	