<?php

namespace Spector\Formatter;

interface Formatter
{
	public function format(LogEntry $entry);
}