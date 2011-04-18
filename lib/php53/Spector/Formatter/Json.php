<?php

namespace Spector\Formatter\Formatter;

use Spector\Writer\Writable;

class Json implements Formatter
{
	public function format(Writable $entry)
	{
		$output = json_encode($entry->toArray());

		// escape newlines
		return str_replace("\n", '\n', $output) . PHP_EOL;
	}
}