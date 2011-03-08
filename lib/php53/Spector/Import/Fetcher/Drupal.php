<?php

use Spector\LogEntry;
use Spector\Import\Fetcher\Fetcher;

class File extends AbstractFetcher implements Fetcher 
{

	public function validateConfig(Spector\Import\Import $config)
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

	public function fetchData(Spector\Import\Import $config)
	{
		$entries = array();
		
		$handle = $this->connect($config);
		$result = $this->queryDatabase($handle, $config->getLastImportIdentifier());
		
		while ($row = mysql_fetch_object($result))
		{
			$entry = new LogEntry();
			
			$message = $this->dblog_format_message($row);
			
			if ($row->type === 'php')
			{
				$type = 'php';
			} else {
				$type = 'log';
				$message = $row->type . ' : ' . $message;
			}
			
			// convert to microseconds
			$time = $row->timestamp * 1000000;
			
			$data = array(
				'userId' => $row->uid,
				'link' => $row->link,
				'location' => $row->location,
				'referer'	=> $row->referer,
				'hostname' => $row->hostname
			);
			
			$entry->setMessage($message);
			$entry->setType($type);
			$entry->setSeverity($row->severity);
			$entry->setTime($time);
			$entry->setData(serialize($data));
			
			$entries[] = $entry;
		}
		
		if ($row)
		{
			$config->setLastImportIdentifier($row->wid);
		}
		
		return $entries;
	}
	
	protected function connect(Spector\Import\Import $config)
	{
		$r = $this->_config->getRemote();

		$host = $r->getHost();
		$port = $r->getPort();
		
		if (!$port) $port = 3306;
		
		$user = $r->getUserName();
		$password = $r->getPassword();
		
		$dbname = $r->getResourcePath();
		
		$handle = mysql_connect($host . ':' . $port, $user, $password);
		
		if ($handle === false)
		{
			throw new \Exception("Could not connect to MYSQL Server on '$host:$port'.");
		}
		
		if(!mysql_select_db($dbname))
		{
			throw new \Exception("Could not select database $dbname");
		}
		
		return $handle;
	}
	
	protected function queryDatabase($handle, $lastId=null)
	{
		$q = 'SELECT * FROM drupal_watchdog';
		
		if ($lastId)
		{
			$q .= ' WHERE wid > ' . $lastId;
		}
		
		$result = mysql_query($q);
		
		if ($result === false)
		{
			throw new \Exception('Could not execute query query database.');
		}
		
		return $result;
	}
	
	protected function dblog_format_message($dblog) {
	  // Legacy messages and user specified text
	  if ($dblog->variables === 'N;') {
	    return $dblog->message;
	  }
	  // Message to translate with injected variables
	  else {
	    return $this->drupal_t($dblog->message, unserialize($dblog->variables));
	  }
	}
	
	function drupal_t($string, $args = 0) 
	{
	  if (!$args) {
	    return $string;
	  }
	  else {
	    return strtr($string, $args);
	  }
	}
}