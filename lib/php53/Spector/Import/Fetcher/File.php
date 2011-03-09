<?php

namespace Spector\Import\Fetcher;

use Spector\Import\Fetcher\Fetcher;
use Spector\Import;

class File extends AbstractFetcher implements Fetcher 
{
	public function validateConfig(Import\Import $config)
	{
		if (!$config->getRemote())
		{
			throw new \Exception('No remote data set.');
		}
		
		$file = $config->getRemote()->getResourcePath();
		
		if (!$file)
		{
			throw new \Exception("No file path set.");
		} else if (!is_file($file) || !is_readable($file))
		{
			throw new \Exception("Filepath '$file' not readable or does not exist.");
		}
	}

	public function fetchData()
	{
		$path = $this->_config->getRemote()->getResourcePath();
		
		$data = file_get_contents($path);
		
		if ($data === false) throw new Exception('Could not read file: ' . $path);
		
		if ($this->_config->deleteSource())
		{
			file_put_contents($path, '');
		}
		
		return $data;
	}
}