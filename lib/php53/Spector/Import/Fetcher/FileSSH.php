<?php

namespace Spector\Import\Fetcher;

use Spector\Import\Fetcher\Fetcher;
use Spector\Import;

class FileSSH extends AbstractFetcher implements Fetcher
{
	public function validateConfig(Import\Import $config)
	{
		if (! ($remote = $config->getRemote()))
		{
			throw new \Exception('No remote data set.');
		}
		
		if (!($remote->getHost() && ($remote->getUsername() && $remote->getPassword())))
		{
			throw new \Exception('Host, username or password not set.');
		}
		
		$file = $config->getRemote()->getResourcePath();
		
		if (!$file)
		{
			throw new \Exception("No file path set.");
		}
	}

	public function fetchData()
	{
		if (!function_exists('ssh2_connect'))
		{
			throw new \Exception('PHP SSH2 module not installed.');
		}
		
		$r = $this->_config->getRemote();

		$host = $r->getHost();
		$port = $r->getPort();
		
		if (!$port) $port = 22;
		
		$user = $r->getUserName();
		$password = $r->getPassword();
		
		$path = $r->getResourcePath();
		
		$handle = ssh2_connect($host, (float)$port);
		
		if (!$handle) 
			throw new \Exception("Could not connect to server $host on port $port");
		
		if (!ssh2_auth_password($handle, $user, $password))
			throw new \Exception("Could not login. Invalid ahtentication details.");
			
		$localPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'spector';
		
		if (!is_dir($localPath))
		{
			$flag = mkdir($localPath, 0777, true);
			if (!$flag) throw new \Exception("Tmp directory $localPath does not exist and could not be created.");
		}
		
		$localPath .= basename($path);
		
		$flag = ssh2_scp_recv($handle, $path, $localPath);
		
		if (!$flag) throw new \Exception("Could not copy file from $host:$path to $localPath");
		
		$content = file_get_contents($localPath);
		
		if (true) //$this->_config->deleteSource())
		{
			file_put_contents($localPath, '');
			ssh2_scp_send($handle, $localPath, $path, 0777);
		}
		
		return $content;
	}
}
