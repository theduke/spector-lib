<?php

interface Spector_Writer
{
	public function write(Spector_LogEntry $entry);
	
	public function validate();
	
	public function initialize();
}