<?php

namespace Spector\Track;

use Spector\Writer\Writable;

class Tracker
{
	protected static $_entry;
	
	/**
	 * @var Spector\Writer\Writer
	 */
	protected static $_writer;
	
	public static function start()
	{
		$e = self::$_entry = new Entry();
		
		$e->ip = $_SERVER['REMOTE_ADDR'];
		$e->sessionId = session_id();
		$e->time = microtime();
		
		$e->method = $_SERVER['REQUEST_METHOD'];
		$e->host = $_SERVER['HTTP_HOST'];
		$e->path = $_SERVER['REQUEST_URI'];
		$e->query = $_SERVER['QUERY_STRING'];
		
		$e->referer = $_SERVER['HTTP_REFERER'];
		$e->userAgent = $_SERVER['HTTP_USER_AGENT'];
		
		$e->cookies = $_COOKIE;
	}
	
	public static function finish()
	{
		$e = self::$_entry;
		
		$e->length = microtime() - $e->time;
		
		self::persist($e);
	}
	
	public static function getEntry()
	{
		return $this->_entry;
	}
	
	protected static function persist(Writable $entry)
	{
		if (!self::$_writer)
		{
			throw new \Exception('No writer set.');
		}
		
		self::$_writer->write($entry);
	}
	
}