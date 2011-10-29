<?php

namespace Spector\Formatter;

use Spector\Writer\Writable;

interface Formatter
{
	public function format(Writable $entry);
}
