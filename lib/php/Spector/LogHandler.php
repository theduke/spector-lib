<?php

require_once 'Log.php';

class Spector_LogHandler
{
	protected static $_log;
	
	public static function log($message, $severity, $data=null, $bucket=null, $type=null, $environment=null, $project=null, $time=null)
	{
		if (!self::$_log)
			throw new Exception('Log not set.');
			
		self::$_log->log($message, $severity, $data, $bucket, $type, $environment, $project, $time);
	}
	
	public static function setLog(Spector_Log $log)
	{
		self::$_log = $log;
	}
	
	public static function getLog()
	{
		return self::$_log;
	}
}