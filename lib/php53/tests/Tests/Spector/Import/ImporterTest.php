<?php

use Spector\Import\Importer;
use Spector\Import\Remote;
use Spector\Import\Import;

class ImporterTest extends PHPUnit_Framework_TestCase
{
	protected function getImport()
	{
			$remote = new Remote();
			
			$remote->setResourcePath(SPECTOR_DATA_PATH . '/import/php_error.log');		
		
			$import = new Import();
			$import->setFetcher(Import::FETCHER_FILE);
			$import->setHandler(Import::HANDLER_PHPLOG);
			
			$import->setRemote($remote);
			
			return $import;
	}
	
	public function testImport()
	{
		$import = $this->getImport();
		$importer = new Importer();
		
		$entries = $importer->doImport($import);
		
		$this->assertEquals(count($entries), 122);
	}
}