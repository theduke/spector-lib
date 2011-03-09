<?php

namespace Spector\Import\Fetcher;

use Spector\LogEntry;
use Spector\Import;
use Spector\Import\Fetcher\Fetcher;

class Drupal extends AbstractFetcher implements Fetcher 
{

	public function validateConfig(Import\Import $config)
	{
		if (!$config->getRemote())
		{
			throw new \Exception('No remote data set.');
		}
		
		$r = $config->getRemote();
		$file = $config->getRemote()->getResourcePath();
		
		if (!($r->host && $r->username && $r->password && $r->resourcePath))
		{
			throw new \Exception('Remote database data not set.');
		}
	}

	public function fetchData()
	{
		if (!$this->_config) throw new \Exception('Not initialized.');
		
		$config = $this->_config;
		
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
	
	protected function connect(Import\Import $config)
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
		
		mysql_query("SET NAMES 'utf8'", $handle);
		
		return $handle;
	}
	
	protected function queryDatabase($handle, $lastId=null)
	{
		$q = 'SELECT * FROM drupal_watchdog';
		
		if ($lastId)
		{
			$q .= ' WHERE wid > ' . $lastId;
		}
		
		$result = mysql_query($q, $handle);
		$result2 = mysql_query(str_replace('drupal_', '', $q), $handle);
		
		if ($result === false && $result2 === false)
		{
			throw new \Exception('Could not query database.');
		}
		
		return $result ? $result : $result2;
	}
	
	protected function dblog_format_message($dblog) 
	{
	  // Legacy messages and user specified text
	  if ($dblog->variables === 'N;') {
	    return $dblog->message;
	  }
	  // Message to translate with injected variables
	  else {
	  	$vars = $dblog->variables;
	  	
	    return $this->drupal_t($dblog->message, unserialize($vars));
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