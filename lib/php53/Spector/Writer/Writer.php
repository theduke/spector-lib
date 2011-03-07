<?php

namespace Spector\Writer;

interface Writer
{
	public function write(LogEntry $entry);
	
	public function validate();
	
	public function initialize();
	
	public function shutdown();
}