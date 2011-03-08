<?php

namespace Spector\Import\Fetcher;

use SpecSpector\Import;

interface Fetcher
{
	public function validateConfig(Import\Import $config);
	public function initialize(Import\Import $config);
	
	public function fetchData();
}