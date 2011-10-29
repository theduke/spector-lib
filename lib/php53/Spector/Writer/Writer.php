<?php

namespace Spector\Writer;

use Spector\Formatter\Formatter;

use Spector\LogEntry;

interface Writer
{
	public function write(Writable $entry);
	
	public function validate();
	
	public function initialize();
	
	public function shutdown();
	
	public function setFormatter(Formatter $formatter);
}
