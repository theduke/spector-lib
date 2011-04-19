<?php

namespace Spector\Formatter;

use Spector\Writer\Writable;

class Serialized implements Formatter
{
	public function format(Writable $entry)
	{
		// escape newlines
		return str_replace("\n", '\n', serialize($entry->toArray())) . PHP_EOL;
	}
}