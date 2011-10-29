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
		
		if (!$result) return $entries;
		
		while ($row = mysql_fetch_object($result))
		{
			$entry = new LogEntry();
			
			$message = $this->dblog_format_message($row);
			
			if ($row->type === 'php')
			{
				$type = 'php';
			} else {
				$type = 'log';
			}
			
			// convert to microseconds
			$time = $row->timestamp;
			$time = new \MongoDate($time);
			
			$data = array(
				'userId' => $row->uid,
				'link' => $row->link,
				'location' => $row->location,
				'referer'	=> $row->referer,
				'hostname' => $row->hostname
			);
			
			// add additional unused vars
			$data = array_merge($data, self::extractUnusedVars($row));
			
			$entry->setMessage($message);
			$entry->setType($type);
			$entry->setSeverity((int) $row->severity);
			$entry->setTime($time);
			$entry->setData($data);
			$entry->setBucket($row->type);
			
			$entries[] = $entry;
			
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
		// check if new entries even exist
		$countQuery = 'SELECT COUNT(wid) as count FROM watchdog';
		
		if ($lastId)
		{
			$countQuery .= ' WHERE wid > ' . (int) $lastId . ';';
		}
		
		$result = mysql_query($countQuery);
		if (!$result) throw new \Exception('Could not query database: "' . mysql_error() . '" (Error ID: ' . mysql_errno());
		
		$info = mysql_fetch_object($result);
		if ($info->count < 1) return null;
		
		// new entries exist, so query the db and return the result handle
		
		$q = 'SELECT * FROM watchdog';
		
		if ($lastId)
		{
			$q .= ' WHERE wid > ' . (int) $lastId . ';';
		}
		
		$result = mysql_query($q, $handle);
		$result2 = mysql_query(str_replace('drupal_', '', $q), $handle);
		
		if ($result === false && $result2 === false)
		{
			throw new \Exception('Could not query database: "' . mysql_error() . '" (Error ID: ' . mysql_errno());
		}
		
		return $result ? $result : $result2;
	}
	
	protected function dblog_format_message($dblog) 
	{
	  // Legacy messages and user specified text
	  if ($dblog->variables === 'N;' || !$dblog->variables) {
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
	  if (!is_array($args)) {
	    return $string;
	  }
	  else {
	    return strtr($string, $args);
	  }
	}
	
	/**
	 * Find additional vars in dblog vars that were not used in the message string.
	 * Those were added manually and should go into the data property of a log entry.
	 * 
	 * @param stdObject $dblog
	 * @return array
	 */
	public static function extractUnusedVars($dblog)
	{
		$vars = array();
		
		if (is_array($dblog->variables))
		{
			foreach ($dblog->variables as $key => $value)
			{
				if (strpos($dblog->message, $key) === false) 
				{
					$vars[$key] = $message;
				}
			}
		}
		
		return $vars;
	}
}
