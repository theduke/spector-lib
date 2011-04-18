<?php

namespace Spector\Writer;

use Spector\LogEntry;

interface Writer
{
	public function write(Writable $entry);
	
	public function validate();
	
	public function initialize();
	
	public function shutdown();
	
	public function setFormatter();
}