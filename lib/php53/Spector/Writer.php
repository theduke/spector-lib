<?php

namespace Spector;

interface Writer
{
	public function write(LogEntry $entry);
	
	public function validate();
	
	public function initialize();
}